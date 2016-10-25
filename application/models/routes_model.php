<?php
class Routes_model extends CI_Model 
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
	public function exe_sql($sql) 
	{
		$query = $this->db->query($sql);
		
		return $query->result_array();
	}	

	public function get_city_name($city_id) 
	{
		$sql = "SELECT 
					id,name,lattitude,longitude
				FROM
					city
				WHERE 
					is_deleted = 0	AND id = $city_id
				LIMIT 1	
				";
				
				
		return $this->execute_sql($sql);	
	}

	
	public function get_route_name($route_id) 
	{
		$sql = "SELECT 
					id,name
				FROM
					route
				WHERE 
					is_deleted = 0	AND id = $route_id
				LIMIT 1	
				";
				
				
		return $this->execute_sql($sql);	
	}


	public function state_list() 
	{
		$sql = "SELECT 
					id,name
				FROM
					state
				WHERE 
					is_deleted = 0	
				ORDER BY 
					name";

		return $this->execute_sql($sql);	
	}


	public function city_list($state_id,$type="") 
	{
		if($type == "list")
		{
			$sql = "SELECT 
						c.id,c.name,s.name as sname
					FROM
						city c LEFT JOIN state s ON c.state_id = s.id
					WHERE 
						c.is_deleted = 0 AND c.state_id IN($state_id) 
					ORDER BY 
						c.state_id,c.name";
		}
		else
		{
			$sql = "SELECT 
					id,name
				FROM
					city
				WHERE 
					is_deleted = 0 AND state_id IN($state_id) 
				ORDER BY 
					name";
		}				

		return $this->execute_sql($sql);	
	}


	public function route_list() 
	{
		$sql = "SELECT 
					id,name
				FROM
					route
				WHERE 
					is_deleted = 0
				ORDER BY 
					name";

		return $this->execute_sql($sql);	
	}


	public function route_details($route_id) 
	{
		$sql = "SELECT 
					id,name,source_state,source_city,destination_state,destination_city,stoppage_state,distance_from_source
				FROM
					route
				WHERE 
					is_deleted = 0 AND id = $route_id
				ORDER BY 
					name";

		return $this->execute_sql($sql);	
	}

	public function route_stoppage_details($route_id) 
	{
		$sql = "SELECT 
					c.id,c.name,c.lattitude,c.longitude
				FROM
					route_stoppage rs LEFT JOIN city c ON rs.city_id = c.id
				WHERE 
					rs.is_deleted = 0 AND rs.route_id = $route_id
				ORDER BY 
					rs.stoppage_order";

		return $this->execute_sql($sql);	
	}
	public function route_stoppages_details($route_id) 
	{
		$sql = "SELECT 
					c.id,c.name
				FROM
					route_stoppage rs LEFT JOIN city c ON rs.city_id = c.id
				WHERE 
					rs.is_deleted = 0 AND rs.route_id = $route_id
				ORDER BY 
					rs.stoppage_order";

		return $this->exe_sql($sql);	
	}


	public function add_route($route_name,$source_state,$source_city,$dest_state,$dest_city,$stoppage_state,$stoppage_city)
	{
		$session = $this->session->userdata('user_details');
		$user_id=$session['id'];

		$date = date(CREATED_DATE);

		$destination_city_arr = explode(",",$stoppage_city);



		$sourcecity=getSourceCityLatLng($source_city);
		$src_lattitude=$sourcecity->lattitude;
		$src_longitude=$sourcecity->longitude;

		$destcity=getSourceCityLatLng($dest_city);
		$des_lattitude=$destcity->lattitude;
		$des_longitude=$destcity->longitude;


		$distance=(getDrivingDistance($src_lattitude, $des_lattitude, $src_longitude, $des_longitude));


		$sql = "INSERT INTO 
						route(user_id,name,source_state,source_city,destination_state,destination_city,distance_from_source,stoppage_state,created_date,created_by,last_modified_date) 
					VALUES('$user_id','$route_name','$source_state','$source_city','$dest_state','$dest_city','$distance','$stoppage_state','$date','$user_id','$date')";

		$rs = $this->db->query($sql);	

		$insert_id = $this->db->insert_id();
		
		if($insert_id)
		{
			$stoppage_city_arr = explode(",", $stoppage_city);

			foreach($stoppage_city_arr as $idx => $dc_id)
			{
				if($dc_id>0)
				{
					$idx++;			

					$sql = "INSERT INTO 
								route_stoppage(user_id,route_id,city_id,stoppage_order,created_date,created_by,last_modified_date) 
							VALUES('$user_id','$insert_id','$dc_id','$idx','$date','$user_id','$date')";

					$this->db->query($sql);	
				}	

			}

			return "Route has been added succesfully.";
		}
		else
		{
			return "Error occur while processing form.";
		}
	}


	public function edit_route($route_name,$source_state,$source_city,$dest_state,$dest_city,$stoppage_state,$stoppage_city,$route_id)
	{
		$session = $this->session->userdata('user_details');
		$user_id = $session['id'];
		$date = date(LAST_MODIFIED_DATE);

		$destination_city_arr = explode(",",$stoppage_city);

		$source_city=getSourceCityLatLng($source_city);
		$src_lattitude=$source_city->lattitude;
		$src_longitude=$source_city->longitude;

		$dest_city=getSourceCityLatLng($dest_city);
		$des_lattitude=$dest_city->lattitude;
		$des_longitude=$dest_city->longitude;


		$distance=round(getDrivingDistance($src_lattitude, $des_lattitude, $src_longitude, $des_longitude));

		$sql = "UPDATE route SET name = '$route_name', source_state = '$source_state', source_city = '$source_city', destination_state = '$dest_state', destination_city = '$dest_city',distance_from_source='$distance', stoppage_state = '$stoppage_state', last_modified_date = '$date', last_modified_by = '$user_id' WHERE id = $route_id LIMIT 1";

		
		if($this->db->query($sql))
		{	
			//Remove old records
			$sql = "DELETE FROM route_stoppage WHERE route_id = $route_id";
			$this->db->query($sql);

			$stoppage_city_arr = explode(",", $stoppage_city);

			foreach($stoppage_city_arr as $idx => $dc_id)
			{
				if($dc_id>0)
				{
					$idx++;			

					$sql = "INSERT INTO 
								route_stoppage(user_id,route_id,city_id,stoppage_order,created_date,created_by,last_modified_date,last_modified_by) 
							VALUES('$user_id','$route_id','$dc_id','$idx','$date','$user_id','$date','$user_id')";

					$this->db->query($sql);						
				}	

			}

			return "Route has been updated succesfully.";
		}
		else
		{
			return "Error occur while processing form.";
		}		
		
	}



	/*-------------------------------------------------------------------------------------------
	Fare Add, Edit
	---------------------------------------------------------------------------------------------*/
	public function get_route_fare_amounts($route_id)
	{
		$arr = array();

		if(isset($route_id) && !empty($route_id) && $route_id>0)
		{
			$sql = "SELECT 
					id,from_city,to_city,fare
				FROM
					route_fare
				WHERE 
					is_deleted = 0 AND route_id = $route_id
				ORDER BY 
					id";

			$result = $this->execute_sql($sql);	

			if(isset($result) && !empty($result))
			{
				foreach($result as $obj)
				{
					$arr[$obj->from_city][$obj->to_city] = $obj->fare;
				}
			}

		}

		return $arr;
	}

	public function save_fare()
	{
		$session = $this->session->userdata('user_details');
		$user_id = $session['id'];
		
		$date = date(CREATED_DATE);

		$postdata = $_POST;

		$route_id = $postdata['fare_routes'];

		foreach ($postdata['fare_amt'] as $from => $arr) 
		{
			foreach ($arr as $to => $amt) 
			{
				$sql_exist = "SELECT id FROM route_fare WHERE route_id = $route_id AND from_city = $from AND to_city = $to LIMIT 1";

				$chk = $this->db->query($sql_exist);

				if($chk->num_rows()>0)
				{
					$sql = "UPDATE route_fare SET fare = '$amt', is_deleted = 0, last_modified_date = '$date', last_modified_by = '$user_id' WHERE route_id = '$route_id' AND from_city = '$from' AND to_city = '$to' LIMIT 1	";
				}
				else
				{
					$sql = "INSERT INTO 
							route_fare(user_id,route_id,from_city,to_city,fare,created_date,created_by,last_modified_date) 
						VALUES('$user_id','$route_id','$from','$to','$amt','$date','$user_id','$date')";
				}
						
				$rs = $this->db->query($sql);				
			}	
		}
		
		return "Fare details has been saved succesfully.";
		
	}

	

	/*-----------------------------------------------------------------------------------
	Developer 2 
	-----------------------------------------------------------------------------------*/
	public function route_listing() 
	{
			$sql="select  route.id, route.name,
	        (
	        SELECT  city.name As source_city
	        FROM    city
	        WHERE   city.id = route.source_city
	        ) As source_city,

			(
	        SELECT  city.name As destination_city
	        FROM    city
	        WHERE   city.id = route.destination_city
	        ) As destination_city,

	        (
	        SELECT  GROUP_CONCAT(c.name ORDER BY rs.stoppage_order) As cities
	        FROM    route_stoppage rs LEFT JOIN city c ON rs.city_id = c.id
	        WHERE   rs.route_id = route.id AND rs.is_deleted=0 
	        ) As cities,
			route.source_city as source_city_id,route.destination_city as destination_city_id 
			FROM    route
			where route.is_deleted=0
			";

			$query = $this->db->query($sql);
			
			$result=$query->result();		
			
			
			foreach ($result as $key => $value)
			{
				/*$cityname1 = array();
				$city_name = "";
				if($value->cities!="")
				{
					$cities=$value->cities;
				
					$sql1="SELECT name FROM city WHERE id IN($cities)";
					$cityname=$this->execute_sql($sql1);
					foreach ($cityname as $key1 => $value1) 
					{
						
						$cityname1[]=$value1->name;
					}
				
					$city_name=implode(', ', $cityname1);
				}*/
				
				$city_name = $value->cities;
				$data[$key]['cities']=$city_name;

				$id = $value->id;	
				$source_city_id = $value->source_city_id;
				$destination_city_id = $value->destination_city_id;


				$data[$key]['destination_city']=$value->destination_city;
				$data[$key]['source_city']=$value->source_city;
				$data[$key]['id']=$id;
				$data[$key]['name']=$value->name;
				
				$data[$key]['source_city_id']=$source_city_id;
				$data[$key]['destination_city_id']=$destination_city_id;
				

				$amt = "N/A";
				$sql = "SELECT fare FROM route_fare WHERE route_id = $id AND ( (from_city = '$source_city_id' AND to_city = '$destination_city_id') OR (from_city = '$destination_city_id' AND to_city = '$source_city_id')) LIMIT 1";
				$res = $this->execute_sql($sql);
				if(isset($res) && !empty($res))	
				{
					$amt = $res[0]->fare;
				}	
				$data[$key]['fare'] = $amt;

			}
			
			return $data;	
	}


	public function fare_listing() 
	{
			$sql="select  route.id, route.name,
	        (
	        SELECT  city.name As source_city
	        FROM    city
	        WHERE   city.id = route.source_city
	        ) As source_city,

			(
	        SELECT  city.name As destination_city
	        FROM    city
	        WHERE   city.id = route.destination_city
	        ) As destination_city,

	        (
	        SELECT  GROUP_CONCAT(route_stoppage.city_id) As cities
	        FROM    route_stoppage
	        WHERE   route_stoppage.route_id = route.id
	        ) As cities,route.source_city as source_city_id,route.destination_city as destination_city_id 
			FROM    route
			where route.is_deleted=0 and route.id IN(select route_id from route_fare where is_deleted=0)
			";

			$query = $this->db->query($sql);
			
			$result=$query->result();		
			
			
			foreach ($result as $key => $value)
			{
				$city_name = "";
				if($value->cities!="")
				{
					$cities=$value->cities;
				
					$sql1="select name from city where id in($cities)";
					$cityname=$this->execute_sql($sql1);
					foreach ($cityname as $key1 => $value1) {
						
						$cityname1[]=$value1->name;
					}
				
					$city_name=implode(', ', $cityname1);
				}
					$data[$key]['cities']=$city_name;

				$id = $value->id;	
				$source_city_id = $value->source_city_id;
				$destination_city_id = $value->destination_city_id;


				$data[$key]['destination_city']=$value->destination_city;
				$data[$key]['source_city']=$value->source_city;
				$data[$key]['id']=$id;
				$data[$key]['name']=$value->name;
				
				$data[$key]['source_city_id']=$source_city_id;
				$data[$key]['destination_city_id']=$destination_city_id;
				

				$amt = "N/A";
				$sql = "SELECT fare FROM route_fare WHERE route_id = $id AND ( (from_city = '$source_city_id' AND to_city = '$destination_city_id') OR (from_city = '$destination_city_id' AND to_city = '$source_city_id')) LIMIT 1";
				$res = $this->execute_sql($sql);
				if(isset($res) && !empty($res))	
				{
					$amt = $res[0]->fare;
				}	
				$data[$key]['fare'] = $amt;

			}
			
			return $data;	
	}

	public function delete_route($id) 
	{
		$session = $this->session->userdata('user_details');
		$login_user_id=$session['id'];

		$errors = array();
		$data = array();
		

		$this->db->set('is_deleted', 1);
		$this->db->set('last_modified_date', date(LAST_MODIFIED_DATE));
		$this->db->set('last_modified_by', $login_user_id);
		$this->db->where("id =",$id);
		$this->db->update('route');	

		$this->db->set('is_deleted', 1);
		$this->db->set('last_modified_by', $login_user_id);
		$this->db->set('last_modified_date', date(LAST_MODIFIED_DATE));
		$this->db->where("route_id =",$id);
		$this->db->update('route_stoppage');		
		

		echo "<script>alert('Route Deleted');</script>";
			redirect('route/routes_details', 'refresh');
	}




	

	public function delete_route_fare($id) 
	{
		$session = $this->session->userdata('user_details');
		$login_user_id=$session['id'];

		$errors = array();
		$data = array();
		$this->db->set('is_deleted', 1);
		$this->db->set('fare', "");
		$this->db->set('last_modified_by', $login_user_id);
		$this->db->set('last_modified_date', date(LAST_MODIFIED_DATE));
		$this->db->where("route_id =",$id);
		$this->db->update('route_fare');	
		
		
		echo "<script>alert('Route Fare Deleted');</script>";
			redirect('route/routes_fare_details', 'refresh');
	}



	/*-------------------------------------------------------------------------------------------
	Bus Route Assign and Timings
	---------------------------------------------------------------------------------------------*/
	public function bus_route_list()
	{
		$sql = "SELECT 
					b.name as bus, r.name as route, brv.bus_id, brv.route_id, brv.valid_from, brv.valid_to
				FROM
					bus_route_validity brv 
					LEFT JOIN bus b ON brv.bus_id = b.id
					LEFT JOIN route r ON brv.route_id = r.id
				WHERE 
					brv.is_deleted = 0 AND b.is_deleted = 0 AND r.is_deleted = 0
				ORDER BY 
					b.name,r.name
				";
		
		
		return $this->execute_sql($sql);
	}

	public function get_bus_validity($route_id,$bus_id)
	{
		$arr = array();

		if(isset($route_id) && !empty($route_id) && $route_id>0 && isset($bus_id) && !empty($bus_id) && $bus_id>0)
		{
			$sql = "SELECT valid_from,valid_to,max_trip					
				FROM
					bus_route_validity
				WHERE 
					is_deleted = 0 AND route_id = $route_id AND bus_id = $bus_id
				LIMIT 1";

			$result = $this->execute_sql($sql);	

			if(isset($result) && !empty($result))
			{				
				if(isset($result[0]->valid_from) && !empty($result[0]->valid_from))
				{
					$arr['valid_from'] = date(DISPLAY_DATE,strtotime($result[0]->valid_from));
				}
				else
				{
					$arr['valid_from'] = "";
				}	

				if(isset($result[0]->valid_from) && !empty($result[0]->valid_from))
				{
					$arr['valid_to'] = date(DISPLAY_DATE,strtotime($result[0]->valid_to));
				}
				else
				{
					$arr['valid_to'] = "";
				}

				$arr['max_trip'] = $result[0]->max_trip;	
			}

		}

		return $arr;
	}


	public function get_bus_timings($route_id,$bus_id,$journey_date)
	{
		$arr = array();

		if(isset($route_id) && !empty($route_id) && $route_id>0 && isset($bus_id) && !empty($bus_id) && $bus_id>0 && $journey_date!="")
		{
			$journey_date = date(CHANGE_INTO_DATE_FORMAT,strtotime($journey_date));

			$sql = "SELECT 
					city_id,trip_type,trip_num,journey_date,arrival_date,arrival_hour,arrival_minute,arrival_am_pm,departure_date,departure_hour,departure_minute,departure_am_pm
				FROM
					bus_timing
				WHERE 
					is_deleted = 0 AND route_id = $route_id AND bus_id = $bus_id AND journey_date = '$journey_date'
				ORDER BY 
					id";

			$result = $this->execute_sql($sql);	

			if(isset($result) && !empty($result))
			{
				foreach($result as $obj)
				{
					if($obj->trip_type == 1)
					{
						$arr[$obj->trip_num]['single_arrival_date'][$obj->city_id] = $obj->arrival_date;
						$arr[$obj->trip_num]['single_arrival_hour'][$obj->city_id] = $obj->arrival_hour;
						$arr[$obj->trip_num]['single_arrival_minute'][$obj->city_id] = $obj->arrival_minute;
						$arr[$obj->trip_num]['single_arrival_am_pm'][$obj->city_id] = $obj->arrival_am_pm;				

						$arr[$obj->trip_num]['single_departure_date'][$obj->city_id] = $obj->departure_date;
						$arr[$obj->trip_num]['single_departure_hour'][$obj->city_id] = $obj->departure_hour;
						$arr[$obj->trip_num]['single_departure_minute'][$obj->city_id] = $obj->departure_minute;		
						$arr[$obj->trip_num]['single_departure_am_pm'][$obj->city_id] = $obj->departure_am_pm;
					}
					else
					{
						$arr[$obj->trip_num]['round_arrival_date'][$obj->city_id] = $obj->arrival_date;
						$arr[$obj->trip_num]['round_arrival_hour'][$obj->city_id] = $obj->arrival_hour;
						$arr[$obj->trip_num]['round_arrival_minute'][$obj->city_id] = $obj->arrival_minute;
						$arr[$obj->trip_num]['round_arrival_am_pm'][$obj->city_id] = $obj->arrival_am_pm;					

						$arr[$obj->trip_num]['round_departure_date'][$obj->city_id] = $obj->departure_date;
						$arr[$obj->trip_num]['round_departure_hour'][$obj->city_id] = $obj->departure_hour;
						$arr[$obj->trip_num]['round_departure_minute'][$obj->city_id] = $obj->departure_minute;		
						$arr[$obj->trip_num]['round_departure_am_pm'][$obj->city_id] = $obj->departure_am_pm;
					}	
				}
			}

		}

		return $arr;
	}


	public function save_bus_timing()
	{	

		$session = $this->session->userdata('user_details');
		$user_id = $session['id'];
		
		$date = date(CREATED_DATE);

		$postdata = $_POST;		

		$bus_id = $postdata['bus_route_bus_id'];
		$route_id = $postdata['bus_route_route_id'];
		$from_date = date(CHANGE_INTO_DATE_FORMAT,strtotime($postdata['bus_route_from_date']));
		$to_date = date(CHANGE_INTO_DATE_FORMAT,strtotime($postdata['bus_route_to_date']));
		$max_trip = $postdata['bus_route_max_trip'];
		$journey_date = date(CHANGE_INTO_DATE_FORMAT,strtotime($postdata['bus_route_journey_date']));

		
		$sql_exist = "SELECT id FROM bus_route_validity WHERE bus_id = $bus_id AND route_id != $route_id AND (( valid_from BETWEEN '$from_date' AND '$to_date') OR  (valid_to BETWEEN '$from_date' AND '$to_date') )";		
			
		$res = $this->db->query($sql_exist);

		if($res->num_rows()>0)
		{
			return "Bus is already assigned to other route for selected date range.";
			die;
		}
		else
		{
			$sql_exist = "SELECT valid_from,valid_to FROM bus_route_validity WHERE bus_id = $bus_id AND route_id != $route_id";

						
			$result = $this->execute_sql($sql_exist);	

			if(isset($result) && !empty($result))
			{
				
				foreach($result as $obj)
				{
					$valid_from = strtotime(trim($obj -> valid_from));

					$valid_to = strtotime(trim($obj -> valid_to));

					while($valid_from<=$valid_to)
					{
						$valid_from = date("Y-m-d",$valid_from);

						if($valid_from == $from_date || $valid_from == $to_date)
						{
							return "Bus is already assigned to other route for selected date range.";
							die;
						}	

						$valid_from = strtotime("+1 DAY".$valid_from);
					}

				}	
			}
		}	
		
		//Bus Validity Insertion of entries
		$sql_exist = "SELECT id FROM bus_route_validity WHERE bus_id = $bus_id AND route_id = $route_id LIMIT 1";

		$chk = $this->db->query($sql_exist);

		if($chk->num_rows()>0)
		{
			$sql = "UPDATE bus_route_validity SET valid_from = '$from_date',  valid_to = '$to_date', max_trip = '$max_trip', is_deleted = 0, last_modified_date = '$date', last_modified_by = '$user_id' WHERE bus_id = $bus_id AND route_id = $route_id LIMIT 1";
		}
		else
		{
			$sql = "INSERT INTO 
					bus_route_validity(user_id,bus_id,route_id,valid_from,valid_to,max_trip,created_date,created_by,last_modified_date) 
				VALUES('$user_id','$bus_id','$route_id','$from_date','$to_date','$max_trip','$date','$user_id','$date')";
		}

		$rs = $this->db->query($sql);		


		//Remove old entries
		$sql_exist = "DELETE FROM bus_timing WHERE bus_id = $bus_id AND route_id = $route_id AND  journey_date = '$journey_date'";
		$this->db->query($sql_exist);

		
		//Single Trip Insertion of entries
		foreach ($postdata['bus_single_trip_arrival_hour'] as $trip_num => $tarr) 
		{	
			foreach ($tarr as $city_id => $arr_hh) 
			{

				$arr_date = ""; $arr_mm = ""; $arr_mm = "";
				$dep_date = ""; $dep_hh = ""; $dep_mm = ""; $dep_ampm = "";

				if(isset($postdata['bus_single_trip_arrival_date'][$trip_num][$city_id]) && !empty($postdata['bus_single_trip_arrival_date'][$trip_num][$city_id]))
				{ 
					$arr_date = date(CHANGE_INTO_DATE_FORMAT,strtotime($postdata['bus_single_trip_arrival_date'][$trip_num][$city_id]));
				}	
				
				$arr_mm = $postdata['bus_single_trip_arrival_minute'][$trip_num][$city_id];
				$arr_ampm = $postdata['bus_single_trip_arrival_ampm'][$trip_num][$city_id];


				if(isset($postdata['bus_single_trip_depart_date'][$trip_num][$city_id]) && !empty($postdata['bus_single_trip_depart_date'][$trip_num][$city_id]))
				{ 
					$dep_date = date(CHANGE_INTO_DATE_FORMAT,strtotime($postdata['bus_single_trip_depart_date'][$trip_num][$city_id]));
				}
				
				$dep_hh = $postdata['bus_single_trip_depart_hour'][$trip_num][$city_id];
				$dep_mm = $postdata['bus_single_trip_depart_minute'][$trip_num][$city_id];
				$dep_ampm = $postdata['bus_single_trip_depart_ampm'][$trip_num][$city_id];

				//echo "<br/>1===A=$arr_date===D=$dep_date===J=$journey_date===F=$from_date===T=$to_date";
				if($arr_date!="" && strtotime($from_date)>strtotime($arr_date))
				{
					return "Trip $trip_num: $trip_numArrival date should be between From date and To date.";
					die;
				}
			
				if($arr_date!="" && strtotime($to_date)<strtotime($arr_date))
				{
					return "Trip $trip_num: Arrival date should be between From date and To date.";
					die;
				}
				
				if($arr_date!="" && strtotime($journey_date)>strtotime($arr_date))
				{
					return "Trip $trip_num: Arrival date should be greater than or equal to journey date.";
					die;
				}

			

				if($dep_date!="" && strtotime($from_date)>strtotime($dep_date))
				{
					return "Trip $trip_num: Departure date should be between From date and To date.";
					die;
				}
				
				if($dep_date!="" && strtotime($to_date)<strtotime($dep_date))
				{
					return "Trip $trip_num: Departure date should be between From date and To date.";
					die;
				}
				
				if($dep_date!="" && strtotime($journey_date)>strtotime($dep_date))
				{
					return "Trip $trip_num: Departure date should be greater than or equal to journey date.";
					die;
				}

			
				if($arr_date!="" && $dep_date!="" && strtotime($arr_date)>strtotime($dep_date))
				{
					return "Trip $trip_num: Departure date should be should be greater than or equal to Arrival date.";
					die;
				}
				$timing_data=array("user_id"=>$user_id,
									"bus_id"=>$bus_id,
									"route_id"=>$route_id,
									"city_id"=>$city_id,
									"trip_num"=>$trip_num,
									"arrival_hour"=>$arr_hh,
									"arrival_minute"=>$arr_mm,
									"arrival_am_pm"=>$arr_ampm,
									"departure_hour"=>$dep_hh,
									"departure_minute"=>$dep_mm,
									"departure_am_pm"=>$dep_ampm,
									"created_date"=>$date,
									"created_by"=>$user_id,
									"journey_date"=>$journey_date,
									"arrival_date"=>!empty($arr_date) ? $arr_date : NULL,
									"departure_date"=>!empty($dep_date) ? $dep_date : NULL,
									"last_modified_date"=>$date,
									"trip_type"=>1,


									);
				$this->db->insert('bus_timing', $timing_data);

				/*$sql = "INSERT INTO 
						bus_timing(user_id,bus_id,route_id,city_id,trip_num,arrival_hour,arrival_minute,arrival_am_pm,departure_hour,departure_minute,departure_am_pm,created_date,created_by,journey_date,arrival_date,departure_date,last_modified_date,trip_type) 
					VALUES('$user_id','$bus_id','$route_id','$city_id','$trip_num','$arr_hh','$arr_mm','$arr_ampm','$dep_hh','$dep_mm','$dep_ampm','$date','$user_id','$journey_date','$arr_date','$dep_date','$date',1)";
				
						
				$rs = $this->db->query($sql);*/
			}							
		}


		//Round Trip Insertion of entries
		foreach ($postdata['bus_round_trip_arrival_hour'] as $trip_num => $tarr) 
		{	
			foreach ($tarr as $city_id => $arr_hh) 
			{
				$arr_date = ""; $arr_mm = ""; $arr_mm = "";
				$dep_date = ""; $dep_hh = ""; $dep_mm = ""; $dep_ampm = "";

				if(isset($postdata['bus_round_trip_arrival_date'][$trip_num][$city_id]) && !empty($postdata['bus_round_trip_arrival_date'][$trip_num][$city_id]))
				{ 
					$arr_date = date(CHANGE_INTO_DATE_FORMAT,strtotime($postdata['bus_round_trip_arrival_date'][$trip_num][$city_id]));
				}
					
				$arr_mm = $postdata['bus_round_trip_arrival_minute'][$trip_num][$city_id];
				$arr_ampm = $postdata['bus_round_trip_arrival_ampm'][$trip_num][$city_id];

				if(isset($postdata['bus_round_trip_depart_date'][$trip_num][$city_id]) && !empty($postdata['bus_round_trip_depart_date'][$trip_num][$city_id]))
				{
					$dep_date = date(CHANGE_INTO_DATE_FORMAT,strtotime($postdata['bus_round_trip_depart_date'][$trip_num][$city_id]));
				}
					
				$dep_hh = $postdata['bus_round_trip_depart_hour'][$trip_num][$city_id];
				$dep_mm = $postdata['bus_round_trip_depart_minute'][$trip_num][$city_id];
				$dep_ampm = $postdata['bus_round_trip_depart_ampm'][$trip_num][$city_id];

				//echo "<br/>2===A=$arr_date===D=$dep_date===J=$journey_date===F=$from_date===T=$to_date";
				if($arr_date!="" && strtotime($from_date)>strtotime($arr_date))
				{
					return "Trip $trip_num: Arrival date should be between From date and To date.";
					die;
				}
				
				if($arr_date!="" && strtotime($to_date)<strtotime($arr_date))
				{
					return "Trip $trip_num: Arrival date should be between From date and To date.";
					die;
				}
				
				if($arr_date!="" && strtotime($journey_date)>strtotime($arr_date))
				{
					return "Trip $trip_num: Arrival date should be greater than or equal to journey date.";
					die;
				}

				

				if($dep_date!="" && strtotime($from_date)>strtotime($dep_date))
				{
					return "Trip $trip_num: Departure date should be between From date and To date.";
					die;
				}
				
				if($dep_date!="" && strtotime($to_date)<strtotime($dep_date))
				{
					return "Trip $trip_num: Departure date should be between From date and To date.";
					die;
				}
				
				if($dep_date!="" && strtotime($journey_date)>strtotime($dep_date))
				{
					return "Trip $trip_num: Departure date should be greater than or equal to journey date.";
					die;
				}


				if($arr_date!="" && $dep_date!="" && strtotime($arr_date)>strtotime($dep_date))
				{
					return "Trip $trip_num: Departure date should be should be greater than or equal to Arrival date.";
					die;
				}
				$timing_data=array("user_id"=>$user_id,
									"bus_id"=>$bus_id,
									"route_id"=>$route_id,
									"city_id"=>$city_id,
									"trip_num"=>$trip_num,
									"arrival_hour"=>$arr_hh,
									"arrival_minute"=>$arr_mm,
									"arrival_am_pm"=>$arr_ampm,
									"departure_hour"=>$dep_hh,
									"departure_minute"=>$dep_mm,
									"departure_am_pm"=>$dep_ampm,
									"created_date"=>$date,
									"created_by"=>$user_id,
									"journey_date"=>$journey_date,
									"arrival_date"=>!empty($arr_date) ? $arr_date : NULL,
									"departure_date"=>!empty($dep_date) ? $dep_date : NULL,
									"last_modified_date"=>$date,
									"trip_type"=>2,


				);
				$this->db->insert('bus_timing', $timing_data);

				/*$sql = "INSERT INTO 
							bus_timing(user_id,bus_id,route_id,city_id,trip_num,arrival_hour,arrival_minute,arrival_am_pm,departure_hour,departure_minute,departure_am_pm,created_date,created_by,journey_date,arrival_date,departure_date,last_modified_date,trip_type) 
						VALUES('$user_id','$bus_id','$route_id','$city_id','$trip_num','$arr_hh','$arr_mm','$arr_ampm','$dep_hh','$dep_mm','$dep_ampm','$date','$user_id','$journey_date','$arr_date','$dep_date','$date',2)";
				
						
				$rs = $this->db->query($sql);*/	
			}	
				
		}
		
		return "Bus timings has been saved succesfully.";
		
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

	public function delete_bus_route_info($bus_id,$route_id)
	{
		$session = $this->session->userdata('user_details');
		
		$login_user_id = $session['id'];

		$errors = array();
		$data = array();
		

		$this->db->set('is_deleted', 1);
		$this->db->set('last_modified_date', date(LAST_MODIFIED_DATE));
		$this->db->set('last_modified_by', $login_user_id);
		
		$this->db->set('arrival_hour',"");
		$this->db->set('arrival_minute',"");

		$this->db->set('departure_hour',"");
		$this->db->set('departure_minute',"");


		$this->db->where("bus_id =",$bus_id);
		$this->db->where("route_id =",$route_id);
		$this->db->update('bus_timing');	



		$this->db->set('is_deleted', 1);
		$this->db->set('last_modified_by', $login_user_id);
		$this->db->set('last_modified_date', date(LAST_MODIFIED_DATE));
		$this->db->set('valid_from',"");
		$this->db->set('valid_to',"");

		$this->db->where("bus_id =",$bus_id);
		$this->db->where("route_id =",$route_id);
		$this->db->update('bus_route_validity');		
		

		echo "<script>alert('Bus Route has been deleted successfully.');</script>";
		redirect('route/bus_route_list', 'refresh');
	}

}
?>