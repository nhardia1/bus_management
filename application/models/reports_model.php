<?php
class Reports_model extends CI_Model 
{

	public function __construct()
	{  	
	  	parent::__construct();
		
		$this->load->database();
	}

	
	public function execute_sql($sql) 
	{
		$query = $this->db->query($sql);
		
		return $query->result();
	}

 	
 	public function bus_list() 
	{
		$sql = "SELECT 
					id,name,bus_number 
				FROM 
					bus 
				WHERE 
					 is_deleted = 0
				ORDER BY 
					name";		
				
		return $this->execute_sql($sql);
	}



	public function fare() 
	{
		$append = " nop.route_id IN (SELECT id FROM route WHERE is_deleted = 0)  "; $response = "";

		if(isset($_POST['bus_id']) && !empty($_POST['bus_id']))
		{
			$append .= " AND nop.bus_id = ".$_POST['bus_id'];
		}

		if(isset($_POST['for_date']) && !empty($_POST['for_date']))
		{
			$for_date = date(CHANGE_INTO_DATE_FORMAT,strtotime($_POST['for_date']));

			$append .= " AND date(nop.fordate) = '$for_date'";
		}

		
		$sql = "SELECT nop.bus_id, nop.route_id, nop.fordate
				FROM num_of_passenger nop
				WHERE $append 
				GROUP BY date(nop.fordate), nop.route_id, nop.bus_id";
				
		$result = $this->execute_sql($sql);

		if(isset($result) && !empty($result))
		{
			$sno = 0;
			foreach($result as $key => $obj)
			{	
				$sno++;

				$bus_id = $obj->bus_id;

				$route_id = $obj->route_id;
				
				$fordate = date(CHANGE_INTO_DATE_FORMAT,strtotime($obj->fordate));
				$actual_date = date(CHANGE_INTO_DATE_FORMAT,strtotime($obj->fordate));
				
				$count = $this->calculate_amount($bus_id,$route_id,$fordate);

				$busname = $this->bus_name($bus_id);
				$name = $this->only_bus_name($bus_id);

				$route_name = $this->route_name($route_id);

				$fordate = date(DISPLAY_DATE,strtotime($fordate));
				
				$count_arr = explode("#",$count);

				$pass = $count_arr[0];
				$amt = $count_arr[1];

				$response .= "<tr>";
					//$response .= "<td>$sno</td>";
					$response .= "<td>$busname</td>";
					$response .= "<td>$route_name</td> ";
					$response .= "<td>$fordate</td>";
					
					$response .= "<td><a href='#'>
						<div onclick=\"get_fare_details_info('$name',$bus_id,$route_id,'$actual_date');\">$pass</div></a></td>";
					$response .= "<td><a href='#'><div onclick=\"get_fare_details_info('$name',$bus_id,$route_id,'$actual_date');\">$amt</div></a></td>";
				$response .= "</tr>";
			}
		}
		

		return $response;
	}


	function bus_name($bus_id)
	{
		$sql = "SELECT name,bus_number
				FROM bus
				WHERE id = '$bus_id' 
				LIMIT 1";

		$query = $this->db->query($sql);
		
		$result = $query->row_array();

		if(isset($result) && !empty($result))
		{			
			$name=ucfirst($result['name']);
			
			$bus_number=$result['bus_number'];
			
			return $name."<br><span class='bus_helper'>($bus_number)</span>";
		}

		return "N/A";		
	}


	function only_bus_name($bus_id)
	{
		$sql = "SELECT name
				FROM bus
				WHERE id = '$bus_id' 
				LIMIT 1";

		$query = $this->db->query($sql);
		$result = $query->row_array();
		//$result = $this->execute_sql($sql);

		if(isset($result) && !empty($result))
		{			
			$name=ucfirst($result['name']);
			
			return $name;
		}

		return "N/A";	
	}


	function city_name($city_id)
	{
		$sql = "SELECT name
				FROM city
				WHERE id = '$city_id' 
				LIMIT 1";
				
		$result = $this->execute_sql($sql);

		if(isset($result) && !empty($result))
		{			
			return $result[0] -> name;
		}

		return "N/A";	
	}


	function route_name($route_id)
	{
		$sql = "SELECT name
				FROM route
				WHERE id = '$route_id' 
				LIMIT 1";
				
		$result = $this->execute_sql($sql);

		if(isset($result) && !empty($result))
		{			
			$sql = "SELECT  (SELECT c.name FROM city c WHERE c.id = r.source_city) as source,
							(SELECT c.name FROM city c WHERE c.id = r.destination_city) as destination
				FROM route r
				WHERE r.id = '$route_id' 
				LIMIT 1";
			$result1 = $this->execute_sql($sql);

			$path = "";
			if(isset($result1) && !empty($result1))
			{	
				$path = "<br><span class='help_txt'>(".$result1[0]->source." > ".$result1[0]->destination.")</span>";
			}	
			
			return ucfirst($result[0] -> name).$path;
		}

		return "N/A";	
	}


	function calculate_amount($bus_id,$route_id,$fordate)
	{
		$total_pass = 0;
		$total_amt = 0;

		$sql = "SELECT nop.from_city, nop.to_city, nop.count
				FROM num_of_passenger nop
				WHERE nop.bus_id = '$bus_id' AND nop.route_id = '$route_id' AND date(nop.fordate) = '$fordate' AND nop.trip_type > 0";
				
		$result = $this->execute_sql($sql);

		if(isset($result) && !empty($result))
		{			
			foreach($result as $obj)				
			{
				$from = $obj->from_city;
				$to = $obj->to_city;
				$count = $obj->count;

				$total_pass = $total_pass + $count;

				$sql = "SELECT rf.fare
				FROM route_fare rf 
				WHERE rf.route_id = '$route_id' AND ( (rf.from_city = '$from' AND rf.to_city = '$to') OR (rf.from_city = '$to' AND rf.to_city = '$from') ) AND rf.fare != '' AND rf.fare>0
				LIMIT 1";				

				$result1 = $this->execute_sql($sql);

				if(isset($result1) && !empty($result1))
				{	
					$fare = $result1[0]->fare;

					$total_amt = $total_amt + ($count * $fare);	
				}	
			}
		}

		return "$total_pass#$total_amt";	
	}


		
	public function fare_details() 
	{
		$trip_result = array();
		$result = array();
		$response = "";
		$arr= array();
		$for_date = $_POST['for_date'];
		$bus_id = $_POST['bus_id'];
		$route_id = $_POST['route_id'];
		$maxtrip = $_POST['trip_number'];
		

		$sql_trip_type = "SELECT distinct bus_id,count,route_id,fordate,from_city,to_city,trip_type,trip_num
			FROM num_of_passenger 
			WHERE date(fordate) = '$for_date'
			AND bus_id = '$bus_id'
			AND route_id = '$route_id'
			AND trip_type > 0
			";
	
				
		$trip_result = $this->execute_sql($sql_trip_type);
		
		$ii=0;
		foreach ($trip_result as $key => $value) 
		{

			if(isset($arr[$value->trip_num][$value->trip_type][$ii]))
			{			
				$ii++;
			}
			else
			{
				$ii=0;
			}
				
			
			$arr[$value->trip_num][$value->trip_type][$ii]['bus_id']=$value->bus_id;
			$arr[$value->trip_num][$value->trip_type][$ii]['count']=$value->count;
			$arr[$value->trip_num][$value->trip_type][$ii]['route_id']=$value->route_id;
			$arr[$value->trip_num][$value->trip_type][$ii]['fordate']=$value->fordate;
			$arr[$value->trip_num][$value->trip_type][$ii]['from_city']=$value->from_city;
			$arr[$value->trip_num][$value->trip_type][$ii]['to_city']=$value->to_city;
			$arr[$value->trip_num][$value->trip_type][$ii]['trip_type']=$value->trip_type;
			$arr[$value->trip_num][$value->trip_type][$ii]['trip_num']=$value->trip_num;
			
		}
		//echo "<pre>";print_r($arr);die;
		 $maxtrip_number=count($arr);
		
		
		if(isset($arr) && !empty($arr))
		{ 
			$trip_types='';
			$grand_total_amount='';
			$grand_total_count='';
			$grand_total_fare='';

			$response .= '<div class="row">
						<div class="col-md-12">
							<div class="ui-tab-container ui-tab-horizontal">
                    			<div class="ui-tab ng-isolate-scope" justified="false">  							
		  							<ul class="nav nav-tabs">';


		  	for($trips=1;$trips<=$maxtrip_number;$trips++)
		  	{
		        if($trips==1)
		        {
		        	$response .= '<li class="active" id="tab'.$trips.'"><a href="javascript:void(0);" class="ng-binding">'.$this->lang->line("trip").' '.$trips.'</a></li>';
		        }
		        else
		        {
		        	$response .= '<li id="tab'.$trips.'"><a href="javascript:void(0);" class="ng-binding">'.$this->lang->line("trip").' '.$trips.'</a></li>';
		        }	
		    }

		    $response .=  '</ul>';


		    $response .=  '<div class="tab-content">';
		    $table_header = "<table class='table table-bordered table-striped cf no-margin'><thead><tr>
								<th >#</th><th >".$this->lang->line("from_city")."</th><th >".$this->lang->line("to_city")."</th><th >".$this->lang->line("fare")."</th><th >".$this->lang->line("total").' '.$this->lang->line("Passenger")."</th><th >".$this->lang->line("total").' '.$this->lang->line("amount")."</th>
								</tr></thead><tbody>";

		    
		    foreach($arr as $trip_num => $arr1)
		  	{		        
		        if($trip_num==1)
		        {
		        	$response .= '<div class="tab-pane active" id="contenttab'.$trip_num.'">';
		        }
		        else
		        {
		        	$response .= '<div class="tab-pane" id="contenttab'.$trip_num.'">';
		        }

		        foreach($arr1 as $trip_type => $arr2)
		        {	
				
					$sno = 0;				
					$sub_total_amount = 0;				
					$sub_total_count = 0;				
					$sub_total_fare = 0;					

					if($trip_type == 1)
						$trip_type_info = $this->lang->line("single_way"); //"Single Way";
					else
						$trip_type_info = $this->lang->line("round_way");//"Round Way";

					$response .= "<div class='row'><div class='col-md-12' style='width:100% !important;'><div class='panel panel-info'><div class='panel-heading'>$trip_type_info</div>";
					
					$response .= $table_header;					
						
			
					foreach($arr2 as $key => $arr3)
					{

						if(isset($arr3['bus_id']))
							$bus_id = $arr3['bus_id'];

						if(isset($arr3['count']))
							 $count = $arr3['count'];

						if(isset($arr3['route_id']))
							$route_id = $arr3['route_id'];

						if(isset($arr3['from_city']))
						{
							$from_city = $arr3['from_city'];
							$from_city_name = $this->city_name($from_city);
						}
							
						if(isset($arr3['to_city']))
						{
							$to_city = $arr3['to_city'];
							$to_city_name = $this->city_name($to_city);
						}	
						
						if(isset($arr3['fordate']) && $arr3['fordate']!="00-00-0000")
						{
							$fordate = date(DISPLAY_DATE,strtotime($arr3['fordate']));
						}
							
						if(isset($arr3['trip_type']))
							$trip_type = $arr3['trip_type'];
						
						if(isset($arr3['trip_num']))
							$trip_num = $arr3['trip_num'];

			

						$sno++;

						$fordate = date(CHANGE_INTO_DATE_FORMAT,strtotime($fordate));
						
						$fare = $this->get_route_fare($route_id,$from_city,$to_city);

						$bus_name = $this->bus_name($bus_id);
						
						$fordate = date(DISPLAY_DATE,strtotime($fordate));

						$sub_total_amount=$count*$fare;
						$grand_total_amount=$count*$fare;
						$response .= "<tr>";
							$response .= "<td>$sno</td>";
							$response .= "<td>$from_city_name</td>";
							$response .= "<td>$to_city_name</td>";
							
							$response .= "<td>$fare</td>";
							$response .= "<td>$count</td>";
							$response .= "<td>$sub_total_amount</td>";
						$response .= "</tr>";
							
							$sub_total_count=$sub_total_count+$count;
							$sub_total_fare=$sub_total_fare+$sub_total_amount;
							$grand_total_count=$grand_total_count+$count;
							$grand_total_fare=$grand_total_fare+$grand_total_amount;

					}//end of arr3
					
					$response .= "<tr>";
					$response .= "<td colspan='4' style='text-align:right;'>".$this->lang->line("sub_total")."</td>";
					$response .= "<td>$sub_total_count</td>";
					$response .= "<td>$sub_total_fare</td>";
					$response .= "</tr>";
					$response .= "</tbody></table>";
						
				
					$response .= "</div></div></div>";	

				}//end of arr2	

					$response .= "</div>";
					
			}//end of arr1		
			$response .=  '</div></div></div></div></div>';		
			
		}
		
			
			
		
		

		return $response;
	}


	function get_route_fare($route_id,$from_city,$to_city)
	{
		$fare = 0;
		
		$sql = "SELECT fare
		FROM route_fare 
		WHERE route_id = '$route_id' AND ( (from_city = '$from_city' AND to_city = '$to_city') OR (from_city = '$to_city' AND to_city = '$from_city') ) AND fare != '' AND fare>0
		LIMIT 1";				

		$result = $this->execute_sql($sql);

		if(isset($result) && !empty($result))
		{	
			$fare = $result[0]->fare;
		}		

		return $fare;	
	}

}
?>