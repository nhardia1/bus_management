<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Route extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		
		$this->load->model('routes_model');
		$this->load->model('custom_model');
		
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('ui');			
		

		$this->load->library('Datatables');
        $this->load->library('table');


		$lang = $this->session->userdata('message');
		
		if($lang == "" || !isset($lang))
		{
			$lang = "english";
		}
		$this->lang->load("message",$lang);
	}

	public function add()
	{
		$this->custom_model->check_login_session();

		$page_title['page_title']= $this->lang->line("add").' '.$this->lang->line("route"); //'Add Route';

		$this->load->view('header',$page_title);

		
		$data['state_list'] = $this->state_list();

		$this->load->view('route_add',$data);

		$this->load->view('footer');
	}

	
	public function prepare_options($arr,$typ = 'option',$selval='')
	{	

		$str = "";

		if(isset($arr) && !empty($arr))
		{
			if($typ == "list")
			{
				$states = array();

				foreach($arr as $obj)
				{
					
					//Dispaly type listing--------------------	
					if(!in_array($obj->sname, $states))
					{
						$states[] = ucfirst($obj->sname);
						$str .= "<span class='state_name'>".ucfirst($obj->sname)."</span>";
					}

					$str .= '<a class="list-group-item" href="javascript:;" onclick="add_stoppage_city('.$obj->id.');" id="stopage_city_'.$obj->id.'" value="'.$obj->id.'">'.ucfirst($obj->name).'<span class="imd imd-add" style="float:right;"></span></a>';
				}
			}
			elseif($typ == "option_bt")
			{
				foreach($arr as $val)
				{		
					$sel = "";
					if($val == $selval && $selval!="")
					{
						$sel = "selected='selected'";						
					}	
					$str .= "<option value='$val' $sel>".$val."</option>";
				}
			}
			else
			{
				foreach($arr as $obj)
				{
					$str .= "<option value='$obj->id'>".ucfirst($obj->name)."</option>";
				}
			}	
		}

		return $str;
	}


	public function state_list()
	{
		$this->custom_model->check_login_session();

		$arr = $this->routes_model->state_list();
		
		return $arr;
	}


	public function city_list()
	{
		$this->custom_model->check_login_session();

		$state_ids = $_POST['state_ids'];

		if(isset($state_ids) && !empty($state_ids))		
		{

			$arr = $this->routes_model->city_list($state_ids,$_POST['typ']);

			$str = $this->prepare_options($arr,$_POST['typ']);
		}
		else
		{
			$str = "Please select input for processing.";
		}		

		echo $str;
		
	}


	public function add_route()
	{
		$this->custom_model->check_login_session();
		$postdata = $_POST;

		if(isset($postdata) && !empty($postdata))		
		{
			$valid = true;

			if($postdata['rnm'] == "")
			{
				$msg = "Please enter route name.";

				$valid = false;
			}
			elseif($postdata['rsc'] == "")
			{
				$msg = "Please select source city.";

				$valid = false;
			}
			elseif($postdata['rdc'] == "")
			{
				$msg = "Please select destination city.";

				$valid = false;
			}
						

			if($valid)
			{
				
				
				$route_name = trim($postdata['rnm']);

				$source_state = trim($postdata['rss']);
				$source_city = trim($postdata['rsc']);

				$dest_state = trim($postdata['rds']);
				$dest_city = trim($postdata['rdc']);

				$stoppage_state = trim($postdata['stp_state']);
				$stoppage_city = trim($postdata['stp_city']);			
				
				$route_id = trim($postdata['eid']);

				if($route_id>0 && $route_id!="")
				{
					$msg = $this->routes_model->edit_route($route_name,$source_state,$source_city,$dest_state,$dest_city,$stoppage_state,$stoppage_city,$route_id);
				}
				else
				{
					$msg = $this->routes_model->add_route($route_name,$source_state,$source_city,$dest_state,$dest_city,$stoppage_state,$stoppage_city);
				}	
			}	
		}
		else
		{
			$msg = "Please select input for processing.";
		}	

		echo $msg;
	}

	

	public function edit($route_id)
	{
		$this->custom_model->check_login_session();

		if(isset($route_id) && !empty($route_id))
		{

			$data['state_list'] = $this->state_list();

			$data['route_details'] = $this->routes_model->route_details($route_id);

			$data['route_stoppage'] = $this->routes_model->route_stoppage_details($route_id);
			
			$page_title['page_title'] = $this->lang->line("edit").' '.$this->lang->line("route"); //'Edit Route';
			
			$this->load->view('header',$page_title);

			$this->load->view('route_edit',$data);

			$this->load->view('footer');
		}
		else
		{
			echo "<script> window.location.href = '".base_url()."index.php/home'; </script>";
		}	
	}

	/*-----------------------------------------------------------------------------------------
	Fare Management
	-----------------------------------------------------------------------------------------*/
	public function add_fare($edit_route_id = "")
	{
		$this->custom_model->check_login_session();

		$data['edit_route_id'] = $edit_route_id;

		$data['route_list'] = $this->routes_model->route_list();

		$page_title['page_title']= $this->lang->line("add").' '.$this->lang->line("fare"); //'Add Fare';

		$this->load->view('header',$page_title);

		$this->load->view('fare_add',$data);

		$this->load->view('footer');
	}

	public function route_details()
	{
		$this->custom_model->check_login_session();

		$postdata = $_POST;
		
		$result = json_encode(array("msg"=>0));

		if(isset($postdata) && !empty($postdata))		
		{
			if($postdata['rid'] != "" && $postdata['rid'] > 0)
			{
				$sc = array();
				$dc = array();
				$stp = array();				
				$all = array();
				

				//Routes details --------------------------------------------------------------------
				$arr1 = $this->routes_model->route_details($postdata['rid']);			

				if(isset($arr1) && !empty($arr1))
				{
					$sc = $this->routes_model->get_city_name($arr1[0]->source_city);

					$dc = $this->routes_model->get_city_name($arr1[0]->destination_city);
				}


				//Stoppages --------------------------------------------------------------------
				$arr2 = $this->routes_model->route_stoppage_details($postdata['rid']);
				if(isset($arr2) && !empty($arr2))
				{
					foreach ($arr2 as $key => $obj) 
					{
						$stp[] = $obj;
					}					
				}


				$all = array_merge($sc,$stp,$dc);

				$matrix = $this->generate_matrix($all,$postdata['rid']);

				$arr = array("msg"=>"success", "all_stp" => $all, "matrix" => $matrix);

				$result = json_encode($arr,JSON_UNESCAPED_UNICODE);

			}
			else
			{
				$result = json_encode(array("msg"=>"Please select Route."));
			}
		}

		echo $result;

	}

	

	public function generate_matrix($arr,$route_id)
	{
		$matrix = "";

		$len = count($arr);
		if($len > 0)
		{
			

			$stored_fare = $this->routes_model->get_route_fare_amounts($route_id);

			//echo "<pre>";print_r($stored_fare);die;

			$matrix = "<table class='table table-bordered table-striped cf no-margin fare_amt_table'><tr><td>#</td>";

			foreach($arr as $key_out => $outer)
			{
				$matrix .= "<td >".$outer->name."</td>";
			}

			$matrix .= "</tr>";

			foreach($arr as $key_out => $outer)
			{
				if($key_out>0)
				{
					$id = $outer->id;
					$name = $outer->name;

					$matrix .= "<tr><td>$name</td>";

					foreach($arr as $key_in => $inner)
					{
						$id_in = $inner->id;

						if($key_in<=$key_out && $key_in!=$key_out)
						{
							$amt = "";
							
							//echo "<br/>$id===$id_in";

							if(isset($stored_fare[$id][$id_in]) && $stored_fare[$id][$id_in]>0)
							{
								$amt = $stored_fare[$id][$id_in];
							}

							$matrix .= '<td><div class="input-group"><span class="input-group-addon fare_amt_money">Rs</span><input type="text" id="fare_amt_'.$id.'_'.$id_in.'" name="fare_amt['.$id.']['.$id_in.']" class="form-control col-sm-2 fare_amt" maxlength="5" placeholder="" value="'.$amt.'" /></div></td>';
						}
						else
						{
							$matrix .= '<td>&nbsp;</td>';		
						}	
					}

					$matrix .= "</tr>";	
				}	
			}

			$matrix .= "</table>";
		}

		//echo $matrix;

		return $matrix;

	}


	public function save_fare()
	{
		$this->custom_model->check_login_session();

		$postdata = $_POST;

		if(isset($postdata) && !empty($postdata))		
		{			
			$valid = true;

			$route_id = $postdata['fare_routes'];

			if($route_id == "" || $route_id<=0)
			{
				$msg = "Please select route name.";

				$valid = false;
			}

			else
			{
				$amt_chk = false;

				foreach ($postdata['fare_amt'] as $from => $arr) 
				{
					foreach ($arr as $to => $amt) 
					{
						if($amt!="" && $amt>0)
						{
							$amt_chk = true;
						}			
					}	
				}

				if($amt_chk == false)
				{
					$msg = "Please enter amount for altleast one city.";

					$valid = false;
				}
			}						

			if($valid)
			{
					

				$msg = $this->routes_model->save_fare();					
			}	
		}
		else
		{
			$msg = "Please select input for processing.";
		}	

		echo $msg;
	}


	/*----------------------------------------------------------------------------------
	Assign Bus Route
	-----------------------------------------------------------------------------------*/
	public function manage($bus_id="",$route_id="",$jdate="")
	{
		$this->custom_model->check_login_session();
		

		if(isset($bus_id) && !empty($bus_id) && $bus_id>0 && isset($route_id) && !empty($route_id) && $route_id>0)
		{
			$data['bus_route_bus_id'] = $bus_id;

			$data['bus_route_route_id'] = $route_id;

			$data['bus_route_max_trip'] = $max_trip;

			$data['jdate'] = $jdate;

			$page_title['page_title'] = $this->lang->line("edit").' '.$this->lang->line("bus").' '.$this->lang->line("route"); //'Edit Bus-Route';

		}
		else
		{
			$data['bus_route_bus_id'] = "";

			$data['bus_route_route_id'] = "";

			$data['bus_route_max_trip'] = "";

			$data['jdate'] = "";

			$page_title['page_title'] = $this->lang->line("add").' '.$this->lang->line("bus").' '.$this->lang->line("route"); //'Add Bus-Route';
		}



		$data['bus_list'] = $this->routes_model->bus_list();

		$data['route_list'] = $this->routes_model->route_list();

		$this->load->view('header',$page_title);

		$this->load->view('bus_route_manage',$data);

		$this->load->view('footer');
	}

	public function bus_route_list()
	{
		$this->custom_model->check_login_session();		

		$page_title['page_title'] = $this->lang->line("bus").' '.$this->lang->line("route").' '.$this->lang->line("management"); //'Bus-Route Management';

		$tmpl = array ( 'table_open'  => '<table id="big_table" class="display" cellspacing="0" width="100%">' );
        
        $this->table->set_template($tmpl); 
        
       // $this->table->set_heading('Bus Name','Route','Valid From','Valid To','Maximum Round Trip','Bus Timings','Action');

        $this->table->set_heading($this->lang->line("bus_name"),$this->lang->line("route"),$this->lang->line("valid_from"),$this->lang->line("valid_to"),$this->lang->line("maximum_round_trip"),$this->lang->line("bus_timings"),$this->lang->line("action"));

		$this->load->view('header',$page_title);

		$this->load->view('bus_route_list',$data);

		$this->load->view('footer');
	}


	public function bus_route_datatable()
    {
    	

		$this->datatables->select('bus.name as bus,route.name as route,bus_route_validity.id, bus_route_validity.bus_id, bus_route_validity.route_id, bus_route_validity.valid_from, bus_route_validity.valid_to, bus_route_validity.max_trip,bus.bus_number');      
		$this->datatables->from('bus_route_validity');
		$this->datatables->join('bus', 'bus_route_validity.bus_id = bus.id');
		$this->datatables->join('route', 'bus_route_validity.route_id = route.id');

		$this->datatables->where('bus_route_validity.is_deleted',0);
		$this->datatables->where('bus.is_deleted',0);
		$this->datatables->where('route.is_deleted',0);

		$this->load->helper('common_helper');
		$this->datatables->edit_column('bus_route_validity.valid_from','$1','convert_datatable_date(bus_route_validity.valid_from)');
		$this->datatables->edit_column('bus_route_validity.valid_to','$1','convert_datatable_date(bus_route_validity.valid_to)');

		$this->datatables->edit_column('bus','$1','convert_datatable_busname(bus,bus.bus_number)');
		
		$this->datatables->edit_column('route','$1','convert_datatable_routename_number(bus_route_validity.route_id)');

		//$this->datatables->unset_column('bus.bus_number');
		$this->datatables->edit_column('bus.bus_number','$1','model_for_bus_route_details(bus_route_validity.max_trip,bus_route_validity.valid_from,bus_route_validity.valid_to,bus_route_validity.bus_id,bus_route_validity.route_id,bus_route_validity.max_trip)');
		
		$this->datatables->unset_column('bus_route_validity.id');
		$this->datatables->unset_column('bus_route_validity.bus_id');
		$this->datatables->unset_column('bus_route_validity.route_id');
			$this->datatables->add_column('edit', '<a name="'.$this->lang->line("edit_bus_route").'" class="edit_btn" href='.base_url().'index.php/route/manage/$2/$3><span class="imd imd-mode-edit"></span></a>
			&nbsp;

			<button ui-wave class="delete_btn" name="'.$this->lang->line("delete_bus_route").'" data-toggle="modal" data-target="#myModal_$1" ><span class="imd imd-delete"></span></button>
			<div class="modal fade" id="myModal_$1" role="dialog">

				<div class="modal-dialog"> 

					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">'.$this->lang->line("confirmation").'</h4>
						</div>
						<div class="modal-body">
							<p>'.$this->lang->line("delete_confirm").'</p>
						</div>
						<div class="modal-footer"> <a href="'.base_url().'index.php/route/delete_bus_route_info/$2/$3">'.$this->lang->line("ok").'</a>
							<button type="button" class="btn btn-default" data-dismiss="modal">'.$this->lang->line("cancel").'</button>
						</div>
					</div>
				</div>
			</div>', 'bus_route_validity.id,bus_route_validity.bus_id, bus_route_validity.route_id');   
        echo $this->datatables->generate();
    }



	public function bus_route_details()
	{
		$this->custom_model->check_login_session();

		$postdata = $_POST;
		
		$result = json_encode(array("msg"=>0));

		if(isset($postdata) && !empty($postdata))		
		{
			if($postdata['rid'] != "" && $postdata['rid'] > 0 && $postdata['bid'] != "" && $postdata['bid'] > 0)
			{
								
				$valid_from = "";$valid_to = ""; $max_trip = "";
				$stored_bus_validity = $this->routes_model->get_bus_validity($postdata['rid'],$postdata['bid']);				
				if(isset($stored_bus_validity) && !empty($stored_bus_validity))
				{
					$valid_from = $stored_bus_validity['valid_from'];
					$valid_to = $stored_bus_validity['valid_to'];
					$max_trip = $stored_bus_validity['max_trip'];
				}

				$matrix = "";
				//$matrix = $this->generate_bustime_matrix($all,$postdata['rid'],$postdata['bid']);

				$arr = array("msg"=>"success","matrix" => $matrix,'valid_from' => $valid_from, 'valid_to' => $valid_to, 'max_trip' => $max_trip);

				$result = json_encode($arr);

			}
			else
			{
				$result = json_encode(array("msg"=>"Please select Bus and Route both."));
			}
		}

		echo $result;

	}


	public function get_bus_timing_details()
	{
		$this->custom_model->check_login_session();

		$postdata = $_POST;
		
		$result = json_encode(array("msg"=>0));

		if(isset($postdata) && !empty($postdata))		
		{
			if($postdata['bus_route_bus_id'] != "" && $postdata['bus_route_bus_id'] > 0 && $postdata['bus_route_route_id'] != "" && $postdata['bus_route_route_id'] > 0  && $postdata['bus_route_from_date'] != ""  && $postdata['bus_route_to_date'] != "" && $postdata['bus_route_journey_date'] != "")
			{
				
				$all = array();	$stp = array();
				$sc = array(); $dc = array();		

				//Routes details --------------------------------------------------------------------
				$arr1 = $this->routes_model->route_details($postdata['bus_route_route_id']);			

				if(isset($arr1) && !empty($arr1))
				{
					$sc = $this->routes_model->get_city_name($arr1[0]->source_city);

					$dc = $this->routes_model->get_city_name($arr1[0]->destination_city);
				}

				//Stoppages --------------------------------------------------------------------
				$arr2 = $this->routes_model->route_stoppage_details($postdata['bus_route_route_id']);
				if(isset($arr2) && !empty($arr2))
				{
					foreach ($arr2 as $key => $obj) 
					{
						$stp[] = $obj;
					}					
				}


				$all = array_merge($sc,$stp,$dc);

				
				$matrix = "";
				$matrix = $this->generate_bustime_matrix($all,$postdata['bus_route_route_id'],$postdata['bus_route_bus_id'],$postdata['bus_route_journey_date'],$postdata['bus_route_max_trip']);

				$arr = array("msg"=>"success","matrix" => $matrix);

				$result = json_encode($arr);

			}
			else
			{
				$result = json_encode(array("msg"=>"Please select all fields first."));
			}
		}

		echo $result;

	}
	


	public function generate_bustime_matrix($arr,$route_id,$bus_id,$journey_date,$maxtrip=1)
	{
		$matrix = "";

		$len = count($arr);

		if($len > 0)
		{		
			$temparr = $arr;
			

			$stored_bus_timings = $this->routes_model->get_bus_timings($route_id,$bus_id,$journey_date);

			$matrix = '<div class="row">
						<div class="col-md-12">
							<div class="ui-tab-container ui-tab-horizontal">
                    			<div class="ui-tab ng-isolate-scope" justified="false">  							
		  							<ul class="nav nav-tabs">';


		  	for($trips=1;$trips<=$maxtrip;$trips++)
		  	{
		        if($trips==1)
		        {
		        	$matrix .= '<li class="active" id="tab'.$trips.'"><a href="javascript:void(0);" class="ng-binding">Trip '.$trips.'</a></li>';
		        }
		        else
		        {
		        	$matrix .= '<li id="tab'.$trips.'"><a href="javascript:void(0);" class="ng-binding">Trip '.$trips.'</a></li>';
		        }	
		    }

		    $matrix .=  '</ul>';


		    $matrix .=  '<div class="tab-content">';  						
		  	for($trips=1;$trips<=$maxtrip;$trips++)
		  	{
		        if($trips==1)
		        {
		        	$matrix .= '<div class="tab-pane active" id="contenttab'.$trips.'">';
		        }
		        else
		        {
		        	$matrix .= '<div class="tab-pane" id="contenttab'.$trips.'">';
		        }	
		    	
		    	$arr = $temparr;
		   
		        //Code for generating Single trip -----------------------------------------------
				$city1 = $arr[0]->name;
				$city2 = $arr[count($arr)-1]->name;
			       		

            //return $matrix;    

			$matrix .= "<div class='row'><div class='col-md-5' style='width:48% !important;'><div class='panel panel-info'><div class='panel-heading'>$city1 <i class='imd imd-arrow-forward'></i> $city2</div>";

			$matrix .= "<table class='table table-bordered table-striped cf no-margin'>";
			
			$matrix .= "<tr style='color:#3B799A;'><td style='width:24%;'>City</td><td style='width:38%;'>Arrival Time</td><td style='width:38%;'>Departure Time</td></tr>";

			$all_hours = array(1,2,3,4,5,6,7,8,9,10,11,12);
			$all_minutes = array(0,5,10,15,20,25,30,35,40,45,50,55);
			$all_ampm = array('am'=>'AM','pm'=>'PM');

			$sno = 0;
			$len = count($arr);

			$arrival_date = $journey_date; $arrival_hh = ""; $arrival_mm = ""; $arrival_ampm = "";
			$depart_date = ""; $depart_hh = ""; $depart_mm = ""; $depart_ampm = "";

			foreach($arr as $key_out => $outer)
			{
				$sno++;
				$id = $outer->id;
				$name = $outer->name;

				$first = "";$last = "";
				if($sno == 1)
				{
					$first = '<i class="imd imd-directions-bus"></i>';
				}
				
				if($sno == $len)
				{
					$last = '<i class="imd imd-stop"></i>';
				}

				
				
				if(isset($stored_bus_timings) && !empty($stored_bus_timings))
				{
					if(isset($stored_bus_timings[$trips]['single_arrival_date'][$id]) && $stored_bus_timings[$trips]['single_arrival_date'][$id]!="0000-00-00")
						$arrival_date = date(DISPLAY_DATE,strtotime($stored_bus_timings[$trips]['single_arrival_date'][$id]));

					if(isset($stored_bus_timings[$trips]['single_arrival_hour'][$id]))
						$arrival_hh = $stored_bus_timings[$trips]['single_arrival_hour'][$id];

					if(isset($stored_bus_timings[$trips]['single_arrival_minute'][$id]))
						$arrival_mm = $stored_bus_timings[$trips]['single_arrival_minute'][$id];

					if(isset($stored_bus_timings[$trips]['single_arrival_am_pm'][$id]))
						$arrival_ampm = $stored_bus_timings[$trips]['single_arrival_am_pm'][$id];

					
					if(isset($stored_bus_timings[$trips]['single_departure_date'][$id]) && $stored_bus_timings[$trips]['single_departure_date'][$id]!="0000-00-00")
						$depart_date = date(DISPLAY_DATE,strtotime($stored_bus_timings[$trips]['single_departure_date'][$id]));

					if(isset($stored_bus_timings[$trips]['single_departure_hour'][$id]))
						$depart_hh = $stored_bus_timings[$trips]['single_departure_hour'][$id];
					
					if(isset($stored_bus_timings[$trips]['single_departure_minute'][$id]))
						$depart_mm = $stored_bus_timings[$trips]['single_departure_minute'][$id];

					if(isset($stored_bus_timings[$trips]['single_departure_am_pm'][$id]))
						$depart_ampm = $stored_bus_timings[$trips]['single_departure_am_pm'][$id];
				

					//echo "<br/>Single===$trips===$arrival_date===$arrival_hh===$arrival_mm===$arrival_ampm===$depart_date===$depart_hh===$depart_mm===$depart_ampm";
				}
				
				
				//Arrival Inputs ------------------------------------------------------
				$matrix .= "<tr><td>$name $first $last</td>";
				$matrix .= '<td><div class="input-group">';

				if($sno > 1)
				{
					$matrix .= '<span class="input-group-addon"><i class="imd imd-access-time"></i></span>';

					$matrix .= '<input type="text" class="form-control col-sm-1 journey_date" name="bus_single_trip_arrival_date['.$trips.']['.$id.']" value="'.$arrival_date.'" placeholder="'.$this->lang->line("select").' '.$this->lang->line("arrival").' '.$this->lang->line("data").'"  />';


					$matrix .= '<select name="bus_single_trip_arrival_hour['.$trips.']['.$id.']" class="form-control">';
					$matrix .= '<option value="">Hour</option>';	
					$matrix .= $this->prepare_options($all_hours,'option_bt',$arrival_hh);					
					$matrix .= '</select>';
				
					$matrix .= '<select name="bus_single_trip_arrival_minute['.$trips.']['.$id.']" class="form-control">';
					$matrix .= '<option value="">Min</option>';
					$matrix .= $this->prepare_options($all_minutes,'option_bt',$arrival_mm);	
					$matrix .= '</select>';

					$matrix .= '<select name="bus_single_trip_arrival_ampm['.$trips.']['.$id.']" class="form-control">';
					$matrix .= $this->prepare_options($all_ampm,'option_bt',$arrival_ampm);	
					$matrix .= '</select>';
				}
				else
				{
					$matrix .= '<select name="bus_single_trip_arrival_hour['.$trips.']['.$id.']" style="display:none;" class="">';
					$matrix .= '<option value="">Hour</option>';	
					//$matrix .= $this->prepare_options($all_hours,'option_bt',$arrival_hh);					
					$matrix .= '</select>';
				}	

				$matrix .= '</div></td>';


				//Departure Inputs ------------------------------------------------------
				$matrix .= '<td><div class="input-group">';

				if($sno != $len)
				{	
					$matrix .= '<span class="input-group-addon"><i class="imd imd-time-to-leave"></i></span>';

					if($sno == 1)
					{
						if($depart_date == "") 
							$depart_date = $journey_date;

						$matrix .= '<input type="text" class="form-control col-sm-1 journey_date" name="bus_single_trip_depart_date['.$trips.']['.$id.']" placeholder="Select Departure Date" value="'.$depart_date.'"  />';
					}
					else
					{
						$matrix .= '<input type="text" class="form-control col-sm-1 journey_date" name="bus_single_trip_depart_date['.$trips.']['.$id.']" value="'.$depart_date.'" placeholder="Select Departure Date"  />';
					}	
					//form-control col-sm-1
					$matrix .= '<select name="bus_single_trip_depart_hour['.$trips.']['.$id.']" class="form-control">';
					$matrix .= '<option value="">Hour</option>';	
					$matrix .= $this->prepare_options($all_hours,'option_bt',$depart_hh);					
					$matrix .= '</select>';
					
					$matrix .= '<select name="bus_single_trip_depart_minute['.$trips.']['.$id.']" class="form-control">';
					$matrix .= '<option value="">Min</option>';
					$matrix .= $this->prepare_options($all_minutes,'option_bt',$depart_mm);	
					$matrix .= '</select>';

					$matrix .= '<select name="bus_single_trip_depart_ampm['.$trips.']['.$id.']" class="form-control">';
					$matrix .= $this->prepare_options($all_ampm,'option_bt',$depart_ampm);	
					$matrix .= '</select>';
				}	

				$matrix .= '</div></td>';
					
				$matrix .= "</tr>";						
			}
			
			$matrix .= "</table></div></div>";

			
			krsort($arr);

			
			//Code for generating Round trip -----------------------------------------------
			$city2 = $arr[0]->name;
			$city1 = $arr[count($arr)-1]->name;

			$matrix .= "<div class='col-md-2' style='width:4% !important;'></div><div class='col-md-5' style='width:48% !important;'><div class='panel panel-info'><div class='panel-heading'>$city1 <i class='imd imd-arrow-forward'></i> $city2</div>";

			$matrix .= "<table class='table table-bordered table-striped cf no-margin'>";

			$matrix .= "<tr style='color:#3B799A;'><td style='width:24%;'>City</td><td style='width:38%;'>Arrival Time</td><td style='width:38%;'>Departure Time</td></tr>";
			
			$sno = 0;
			$arrival_date = $journey_date; $arrival_hh = ""; $arrival_mm = ""; $arrival_ampm = "";
			$depart_date = $journey_date; $depart_hh = ""; $depart_mm = ""; $depart_ampm = "";
			
			foreach($arr as $key_out => $outer)
			{
				$sno++;
				$id = $outer->id;
				$name = $outer->name;

				$first = "";$last = "";
				if($sno == 1)
				{
					$first = '<i class="imd imd-directions-bus"></i>';
				}
				
				if($sno == $len)
				{
					$last = '<i class="imd imd-stop"></i>';
				}

				
				if(isset($stored_bus_timings) && !empty($stored_bus_timings))
				{
					if(isset($stored_bus_timings[$trips]['round_arrival_date'][$id]) && $stored_bus_timings[$trips]['round_arrival_date'][$id]!="0000-00-00")
						$arrival_date = date(DISPLAY_DATE,strtotime($stored_bus_timings[$trips]['round_arrival_date'][$id]));

					if(isset($stored_bus_timings[$trips]['round_arrival_hour'][$id]))
						$arrival_hh = $stored_bus_timings[$trips]['round_arrival_hour'][$id];

					if(isset($stored_bus_timings[$trips]['round_arrival_minute'][$id]))
						$arrival_mm = $stored_bus_timings[$trips]['round_arrival_minute'][$id];

					if(isset($stored_bus_timings[$trips]['round_arrival_am_pm'][$id]))
						$arrival_ampm = $stored_bus_timings[$trips]['round_arrival_am_pm'][$id];

					
					if(isset($stored_bus_timings[$trips]['round_departure_date'][$id]) && $stored_bus_timings[$trips]['round_departure_date'][$id]!="0000-00-00")
						$depart_date = date(DISPLAY_DATE,strtotime($stored_bus_timings[$trips]['round_departure_date'][$id]));

					if(isset($stored_bus_timings[$trips]['round_departure_hour'][$id]))
						$depart_hh = $stored_bus_timings[$trips]['round_departure_hour'][$id];

					if(isset($stored_bus_timings[$trips]['round_departure_minute'][$id]))
						$depart_mm = $stored_bus_timings[$trips]['round_departure_minute'][$id];

					if(isset($stored_bus_timings[$trips]['round_departure_am_pm'][$id]))
						$depart_ampm = $stored_bus_timings[$trips]['round_departure_am_pm'][$id];

					//echo "<br/>Round===$trips===$arrival_date===$arrival_hh===$arrival_mm===$arrival_ampm===$depart_date===$depart_hh===$depart_mm===$depart_ampm";
				}

				$matrix .= "<tr><td>$name $first $last</td>";

				$matrix .= '<td><div class="input-group">';

				if($sno > 1)
				{	
					$matrix .= '<span class="input-group-addon"><i class="imd imd-access-time"></i></span>';

					$matrix .= '<input type="text" class="form-control col-sm-1 journey_date" name="bus_round_trip_arrival_date['.$trips.']['.$id.']" value="'.$arrival_date.'" placeholder="'.$this->lang->line("select").' '.$this->lang->line("arrival").' '.$this->lang->line("date").'"  />';

					$matrix .= '<select name="bus_round_trip_arrival_hour['.$trips.']['.$id.']" class="form-control">';
					$matrix .= '<option value="">Hour</option>';	
					$matrix .= $this->prepare_options($all_hours,'option_bt',$arrival_hh);					
					$matrix .= '</select>';
					
					$matrix .= '<select name="bus_round_trip_arrival_minute['.$trips.']['.$id.']" class="form-control">';
					$matrix .= '<option value="">Min</option>';
					$matrix .= $this->prepare_options($all_minutes,'option_bt',$arrival_mm);	
					$matrix .= '</select>';

					$matrix .= '<select name="bus_round_trip_arrival_ampm['.$trips.']['.$id.']" class="form-control">';
					$matrix .= $this->prepare_options($all_ampm,'option_bt',$arrival_ampm);	
					$matrix .= '</select>';
				}
				else
				{
					$matrix .= '<select name="bus_round_trip_arrival_hour['.$trips.']['.$id.']" style="display:none;" class="">';
					$matrix .= '<option value="">Hour</option>';	
					$matrix .= $this->prepare_options($all_hours,'option_bt',$arrival_hh);					
					$matrix .= '</select>';
				}
									
				$matrix .= '</div></td>';


				$matrix .= '<td><div class="input-group">';
				
				if($sno != $len)
				{
					$matrix .= '<span class="input-group-addon"><i class="imd imd-time-to-leave"></i></span>';

					$matrix .= '<input type="text" class="form-control col-sm-1 journey_date" name="bus_round_trip_depart_date['.$trips.']['.$id.']" value="'.$depart_date.'" placeholder="'.$this->lang->line("select").' '.$this->lang->line("departure").' '.$this->lang->line("date").'"  />';
					
						
					$matrix .= '<select name="bus_round_trip_depart_hour['.$trips.']['.$id.']" class="form-control">';
					$matrix .= '<option value="">Hour</option>';	
					$matrix .= $this->prepare_options($all_hours,'option_bt',$depart_hh);					
					$matrix .= '</select>';
					
					$matrix .= '<select name="bus_round_trip_depart_minute['.$trips.']['.$id.']" class="form-control">';
					$matrix .= '<option value="">Min</option>';
					$matrix .= $this->prepare_options($all_minutes,'option_bt',$depart_mm);	
					$matrix .= '</select>';

					$matrix .= '<select name="bus_round_trip_depart_ampm['.$trips.']['.$id.']" class="form-control">';
					$matrix .= $this->prepare_options($all_ampm,'option_bt',$depart_ampm);	
					$matrix .= '</select>';

					$matrix .= '</div></td>';
				}	
					
				$matrix .= "</tr>";						
			}

			$matrix .= "</table></div></div></div></div>";
			
			}

			$matrix .=  '</div></div></div></div></div>';
		}


		return $matrix;

	}
	
	public function save_bus_timing()
	{
		$this->custom_model->check_login_session();

		$postdata = $_POST;

		if(isset($postdata) && !empty($postdata))		
		{			
			$valid = true;

			$bus_id = $postdata['bus_route_bus_id'];
			$route_id = $postdata['bus_route_route_id'];
			$from_date = $postdata['bus_route_from_date'];
			$to_date = $postdata['bus_route_to_date'];
			$max_trip = $postdata['bus_route_max_trip'];
			$journey_date = $postdata['bus_route_journey_date'];

			if($bus_id == "" || $bus_id<=0)
			{
				$msg = "Please select bus name.";

				$valid = false;
			}
			elseif($route_id == "" || $route_id<=0)
			{
				$msg = "Please select route name.";

				$valid = false;
			}
			elseif($from_date == "")
			{
				$msg = "Please select from date.";

				$valid = false;
			}
			elseif($to_date == "")
			{
				$msg = "Please select to date.";

				$valid = false;
			}
			elseif($max_trip == "" || $max_trip<=0)
			{
				$msg = "Please select maximum round trip.";

				$valid = false;
			}
			elseif($journey_date == "")
			{
				$msg = "Please select journey date.";

				$valid = false;
			}
			elseif($journey_date<$from_date && $journey_date>$to_date)
			{
				$msg = "Journey date should be between from date and to date.";

				$valid = false;
			}

			//echo "<pre>";print_r($postdata);die;
			
			if($valid)
			{
				$msg = $this->routes_model->save_bus_timing();					
			}	
		}
		else
		{
			$msg = "Please select input for processing.";
		}	

		echo $msg;
	}


	public function delete_bus_route_info($bus_id,$route_id)
	{
		$this->custom_model->check_login_session();

		if(isset($bus_id) && !empty($bus_id) && $bus_id>0 && isset($route_id) && !empty($route_id) && $route_id>0)
		{
			$data = $this->routes_model->delete_bus_route_info($bus_id,$route_id);
		}
		else
		{
			echo "<script> window.location.href = '".base_url()."index.php/home'; </script>";
		}	

	}

	/*----------------------------------------------------------------------------------
	Developer - 2
	-----------------------------------------------------------------------------------*/

	public function routes_details()
	{
		$this->custom_model->check_login_session();

		$data['route_details'] = $this->routes_model->route_listing();

		$page_title['page_title'] = $this->lang->line("route").' '.$this->lang->line("management"); //Route Management';

		$this->load->view('header',$page_title);
		$this->load->view('route',$data);
		$this->load->view('footer');

		
	}

	public function delete_route()
	{
		$this->custom_model->check_login_session();

		$array = $this->uri->uri_to_assoc();
		$id=$array['id'];
		
		print $data = $this->routes_model->delete_route($id);

	}


	public function routes_fare_details()
	{
		$this->custom_model->check_login_session();

		$data['routes_fare_details'] = $this->routes_model->fare_listing();
		
		$page_title['page_title'] = $this->lang->line("fare").' '.$this->lang->line("management"); //'Fare Management';

		$this->load->view('header',$page_title);
		$this->load->view('fare',$data);
		$this->load->view('footer');

		
	}


	

	public function delete_route_fare()
	{
		$this->custom_model->check_login_session();

		$array = $this->uri->uri_to_assoc();
		$id=$array['id'];
		print $data = $this->routes_model->delete_route_fare($id);

	}


	//Developer - 2

	public function get_bus_route_timing_details()
	{
		$this->custom_model->check_login_session();

		$postdata = $_POST;
		
		
		$result = json_encode(array("msg"=>0));

		if(isset($postdata) && !empty($postdata))		
		{

			// && $postdata['bus_route_to_date'] != "" 
			if($postdata['bus_route_bus_id'] != "" && $postdata['bus_route_bus_id'] > 0 && $postdata['bus_route_route_id'] != "" && $postdata['bus_route_route_id'] > 0  && $postdata['bus_route_from_date'] != "" && $postdata['bus_route_journey_date'] != "")
			{
				
				$all = array();	$stp = array();
				$sc = array(); $dc = array();		

				//Routes details --------------------------------------------------------------------
				$arr1 = $this->routes_model->route_details($postdata['bus_route_route_id']);			

				if(isset($arr1) && !empty($arr1))
				{
					$sc = $this->routes_model->get_city_name($arr1[0]->source_city);

					$dc = $this->routes_model->get_city_name($arr1[0]->destination_city);
				}

				//Stoppages --------------------------------------------------------------------
				$arr2 = $this->routes_model->route_stoppage_details($postdata['bus_route_route_id']);
				if(isset($arr2) && !empty($arr2))
				{
					foreach ($arr2 as $key => $obj) 
					{
						$stp[] = $obj;
					}					
				}


				$all = array_merge($sc,$stp,$dc);

				
				$matrix = "";
				$matrix = $this->generate_bus_route_matrix($all,$postdata['bus_route_route_id'],$postdata['bus_route_bus_id'],$postdata['bus_route_journey_date'],$postdata['bus_route_max_trip']);

				$arr = array("msg"=>"success","matrix" => $matrix);

				$result = json_encode($arr);

			}
			else
			{
				$result = json_encode(array("msg"=>"Please select all fields first."));
			}
		}

		echo $result;

	}


	public function generate_bus_route_matrix($arr,$route_id,$bus_id,$journey_date,$maxtrip=1)
	{
		$matrix = "";

		$len = count($arr);

		if($len > 0)
		{		
			$temparr = $arr;

			$stored_bus_timings = $this->routes_model->get_bus_timings($route_id,$bus_id,$journey_date);

			//echo "<pre>";print_r($stored_bus_timings);

			$matrix = '<div class="row">
						<div class="col-md-12">
							<div class="ui-tab-container ui-tab-horizontal">
                    			<div class="ui-tab ng-isolate-scope" justified="false">  							
		  							<ul class="nav nav-tabs">';


		  	for($trips=1;$trips<=$maxtrip;$trips++)
		  	{
		        if($trips==1)
		        {
		        	$matrix .= '<li class="active" id="tab'.$trips.'"><a href="javascript:void(0);" class="ng-binding">'.$this->lang->line("trip").' '.$trips.'</a></li>';
		        }
		        else
		        {
		        	$matrix .= '<li id="tab'.$trips.'"><a href="javascript:void(0);" class="ng-binding">'.$this->lang->line("trip").' '.$trips.'</a></li>';
		        }	
		    }

		    $matrix .=  '</ul>';


		    $matrix .=  '<div class="tab-content">';  						
		  	for($trips=1;$trips<=$maxtrip;$trips++)
		  	{
		        if($trips==1)
		        {
		        	$matrix .= '<div class="tab-pane active" id="contenttab'.$trips.'">';
		        }
		        else
		        {
		        	$matrix .= '<div class="tab-pane" id="contenttab'.$trips.'">';
		        }	
		    	
		    	$arr = $temparr;
		   
		        //Code for generating Single trip -----------------------------------------------
				$city1 = $arr[0]->name;
				$city2 = $arr[count($arr)-1]->name;
			       		

            //return $matrix;    

			$matrix .= "<div class='row'><div class='col-md-5' style='width:50% !important;'><div class='panel panel-info'><div class='panel-heading'>$city1 <i class='imd imd-arrow-forward'></i> $city2</div>";

			$matrix .= "<table class='table table-bordered table-striped cf no-margin'>";
			
			$matrix .= "<tr style='color:#3B799A;'><td style='width:33%;'>".$this->lang->line("city")."</td><td style='width:33%;'>".$this->lang->line("arrival_time")."</td><td style='width:33%;'>".$this->lang->line("departure_time")."</td></tr>";

			$all_hours = array(1,2,3,4,5,6,7,8,9,10,11,12);
			$all_minutes = array(0,5,10,15,20,25,30,35,40,45,50,55);
			$all_ampm = array('am'=>'AM','pm'=>'PM');

			$sno = 0;
			$len = count($arr);

			$arrival_date = ""; $arrival_hh = ""; $arrival_mm = ""; $arrival_ampm = "";
			$depart_date = ""; $depart_hh = ""; $depart_mm = ""; $depart_ampm = "";

			foreach($arr as $key_out => $outer)
			{
				$sno++;
				$id = $outer->id;
				$name = $outer->name;

				$first = "";$last = "";
				if($sno == 1)
				{
					$first = '<i class="imd imd-directions-bus"></i>';
				}
				
				if($sno == $len)
				{
					$last = '<i class="imd imd-stop"></i>';
				}

				
				
				if(isset($stored_bus_timings) && !empty($stored_bus_timings))
				{
					if(isset($stored_bus_timings[$trips]['single_arrival_date'][$id]) && $stored_bus_timings[$trips]['single_arrival_date'][$id]!="0000-00-00")
						$arrival_date = date(DISPLAY_DATE,strtotime($stored_bus_timings[$trips]['single_arrival_date'][$id]));

					if(isset($stored_bus_timings[$trips]['single_arrival_hour'][$id]))
						$arrival_hh = $stored_bus_timings[$trips]['single_arrival_hour'][$id];

					if(isset($stored_bus_timings[$trips]['single_arrival_minute'][$id]))
						$arrival_mm = $stored_bus_timings[$trips]['single_arrival_minute'][$id];

					if(isset($stored_bus_timings[$trips]['single_arrival_am_pm'][$id]))
						$arrival_ampm = $stored_bus_timings[$trips]['single_arrival_am_pm'][$id];

					
					if(isset($stored_bus_timings[$trips]['single_departure_date'][$id]) && $stored_bus_timings[$trips]['single_departure_date'][$id]!="0000-00-00")
						$depart_date = date(DISPLAY_DATE,strtotime($stored_bus_timings[$trips]['single_departure_date'][$id]));

					if(isset($stored_bus_timings[$trips]['single_departure_hour'][$id]))
						$depart_hh = $stored_bus_timings[$trips]['single_departure_hour'][$id];
					
					if(isset($stored_bus_timings[$trips]['single_departure_minute'][$id]))
						$depart_mm = $stored_bus_timings[$trips]['single_departure_minute'][$id];

					if(isset($stored_bus_timings[$trips]['single_departure_am_pm'][$id]))
						$depart_ampm = $stored_bus_timings[$trips]['single_departure_am_pm'][$id];
				

				
				}
				
				
				//Arrival Inputs ------------------------------------------------------
				$matrix .= "<tr><td>$name $first $last</td>";
				$matrix .= '<td><div class="">';

				if($sno > 1)
				{
					$arival_time='';					
					
					if($arrival_hh!='' && $arrival_mm!='')
					{
						if($arrival_hh<=9) $arrival_hh = "0$arrival_hh";
						if($arrival_mm<=9) $arrival_mm = "0$arrival_mm";

						$arival_time=$arrival_hh.':'.$arrival_mm.' '.$arrival_ampm;
					}
					
					if($arrival_date!="")
					$matrix .= $arrival_date.'&nbsp;'.$arival_time;
					
				}
				else
				{

					$matrix .= "";
					/*$matrix .= '<select name="bus_single_trip_arrival_hour['.$trips.']['.$id.']" style="display:none;" class="">';
					$matrix .= '<option value="">Hour</option>';	
					//$matrix .= $this->prepare_options($all_hours,'option_bt',$arrival_hh);					
					$matrix .= '</select>';*/
				}	

				$matrix .= '</div></td>';


				//Departure Inputs ------------------------------------------------------
				$matrix .= '<td><div class="">';

				if($sno != $len)
				{
					$depart_time='';
					
					if($depart_hh!='' && $depart_mm!='')
					{
						if($depart_hh<=9) $depart_hh = "0$depart_hh";
						if($depart_mm<=9) $depart_mm = "0$depart_mm";

						$depart_time=$depart_hh.':'.$depart_mm.' '.$depart_ampm;
					}

					if($sno == 1)
					{
						if($depart_date == "") 
							$depart_date = $journey_date;

						$matrix .= $depart_date.'&nbsp;'; 
					}
					else
					{
						$matrix .= $depart_date.'&nbsp;';
						
					}	

					if($depart_date!="")
					$matrix .= $depart_time; 
					
				}
				else
				{
					$matrix .= '';
				}	

				$matrix .= '</div></td>';
					
				$matrix .= "</tr>";						
			}
			
			$matrix .= "</table></div></div>";

			
			krsort($arr);

			
			//Code for generating Round trip -----------------------------------------------
			$city2 = $arr[0]->name;
			$city1 = $arr[count($arr)-1]->name;

			$matrix .= "<div class='col-md-2' style='width:4% !important;'></div><div class='col-md-5' style='width:50% !important;'><div class='panel panel-info'><div class='panel-heading'>$city1 <i class='imd imd-arrow-forward'></i> $city2</div>";

			$matrix .= "<table class='table table-bordered table-striped cf no-margin'>";

			$matrix .= "<tr style='color:#3B799A;'><td style='width:33%;'>".$this->lang->line("city")."</td><td style='width:33%;'>".$this->lang->line("arrival_time")."</td><td style='width:33%;'>".$this->lang->line("departure_time")."</td></tr>";
			
			$sno = 0;
			$arrival_date = ""; $arrival_hh = ""; $arrival_mm = ""; $arrival_ampm = "";
			$depart_date = ""; $depart_hh = ""; $depart_mm = ""; $depart_ampm = "";
			
			foreach($arr as $key_out => $outer)
			{
				$sno++;
				$id = $outer->id;
				$name = $outer->name;

				$first = "";$last = "";
				if($sno == 1)
				{
					$first = '<i class="imd imd-directions-bus"></i>';
				}
				
				if($sno == $len)
				{
					$last = '<i class="imd imd-stop"></i>';
				}

				
				if(isset($stored_bus_timings) && !empty($stored_bus_timings))
				{
					if(isset($stored_bus_timings[$trips]['round_arrival_date'][$id]) && $stored_bus_timings[$trips]['round_arrival_date'][$id]!="0000-00-00")
						$arrival_date = date(DISPLAY_DATE,strtotime($stored_bus_timings[$trips]['round_arrival_date'][$id]));

					if(isset($stored_bus_timings[$trips]['round_arrival_hour'][$id]))
						$arrival_hh = $stored_bus_timings[$trips]['round_arrival_hour'][$id];

					if(isset($stored_bus_timings[$trips]['round_arrival_minute'][$id]))
						$arrival_mm = $stored_bus_timings[$trips]['round_arrival_minute'][$id];

					if(isset($stored_bus_timings[$trips]['round_arrival_am_pm'][$id]))
						$arrival_ampm = $stored_bus_timings[$trips]['round_arrival_am_pm'][$id];

					
					if(isset($stored_bus_timings[$trips]['round_departure_date'][$id]) && $stored_bus_timings[$trips]['round_departure_date'][$id]!="0000-00-00")
						$depart_date = date(DISPLAY_DATE,strtotime($stored_bus_timings[$trips]['round_departure_date'][$id]));

					if(isset($stored_bus_timings[$trips]['round_departure_hour'][$id]))
						$depart_hh = $stored_bus_timings[$trips]['round_departure_hour'][$id];

					if(isset($stored_bus_timings[$trips]['round_departure_minute'][$id]))
						$depart_mm = $stored_bus_timings[$trips]['round_departure_minute'][$id];

					if(isset($stored_bus_timings[$trips]['round_departure_am_pm'][$id]))
						$depart_ampm = $stored_bus_timings[$trips]['round_departure_am_pm'][$id];

					
				}

				$matrix .= "<tr><td>$name $first $last</td>";

				$matrix .= '<td><div class="">';

				if($sno > 1)
				{	
					$arival_time='';
					
					if($arrival_hh!='' && $arrival_mm!='')
					{
						if($arrival_hh<=9) $arrival_hh = "0$arrival_hh";
						if($arrival_mm<=9) $arrival_mm = "0$arrival_mm";

						$arival_time=$arrival_hh.':'.$arrival_mm.' '.$arrival_ampm;
					}
					

					$matrix .= $arrival_date.'&nbsp;'.$arival_time; 

					
				}
				else
				{
					$matrix .= "";
					
				}
									
				$matrix .= '</div></td>';


				$matrix .= '<td><div class="">';
				
				if($sno != $len)
				{
					$depart_time='';
					
					if($depart_hh!='' && $depart_mm!='')
					{
						if($depart_hh<=9) $depart_hh = "0$depart_hh";
						if($depart_mm<=9) $depart_mm = "0$depart_mm";

						$depart_time=$depart_hh.':'.$depart_mm.' '.$depart_ampm;
					}
					

					$matrix .= $depart_date.'&nbsp;'.$depart_time; 	

					
				}
				else
				{
					$matrix .= "";
					
				}

				$matrix .= '</div></td>';	
					
				$matrix .= "</tr>";						
			}

			$matrix .= "</table></div></div></div></div>";
			
			}

			$matrix .=  '</div></div></div></div></div>';
		}


		return $matrix;

	}


}//End of class
?>