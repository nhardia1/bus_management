<?php
class Webservice_model extends CI_Model 
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

	
	public function city_lat_long() 
	{
		$sql = "SELECT id, name FROM city WHERE is_deleted = 0";
		
		$rec = $this->execute_sql($sql);

		$city = array();
		if(isset($rec) && !empty($rec))
		{
			foreach($rec as $obj)
			{
				$str = "";

				$str = $this->calculate_lat_long(trim($obj->name));

				$arr = explode("#",$str);

				$lat = $arr[0];
				$lon = $arr[1];

				$sql = "UPDATE city SET lattitude = '$lat', longitude = '$lon' WHERE id = ".$obj->id." LIMIT 1";

				$this->db->query($sql);
			}
		}

		return $city;
	}

	
	function calculate_lat_long($name) 
	{
		$name = strtolower($name);
		$address = ucfirst($name).'+India';
		$address = preg_replace("/[^ \w]+/", "+", $address);

		$geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$address.'&sensor=false');

		$output= json_decode($geocode);

		$lat = $output->results[0]->geometry->location->lat;
		$long = $output->results[0]->geometry->location->lng;

		return "$lat#$long";
	}


	public function bus_location($bus_id,$route_id,$device_id,$fordate,$lat,$long,$trip_num)
	{
		$date = date(CHANGE_INTO_DATE_FORMAT);

		if($fordate!="")
		{
			$fordate = date(CREATED_DATE,strtotime(str_replace("%20"," ",$fordate)));
		}

		$sql = "INSERT INTO 
					bus_location(bus_id,route_id,device_id,for_date,lattitude,longitude,trip_num,created_date,resource_type,last_modified_date) 
				VALUES('$bus_id','$route_id','$device_id','$fordate','$lat','$long','$trip_num','$date','app','$date')";
				
		$res = $this->db->query($sql);

		return $res;
	}


	public function passenger_count($bus_id,$route_id,$device_id,$fordate,$count_info)
	{
		$date = date(CHANGE_INTO_DATE_FORMAT);

		$savedate = "";

		if($fordate!="")
		{
			$savedate = date(CREATED_DATE,strtotime(str_replace("%20", " ", $fordate)));

			$fordate = date(CHANGE_INTO_DATE_FORMAT,strtotime($savedate));
		}

		
		$count_info_arr = explode(":",$count_info);
		if(isset($count_info_arr) && !empty($count_info_arr))
		{
			
			foreach($count_info_arr as $str)
			{
				$ids = explode(",",$str);	

				$from = $ids[0];
				$to = $ids[1];
				$count = $ids[2];
				$trip = $ids[3];

				$sql = "SELECT id 
						FROM num_of_passenger
						WHERE 	bus_id = '$bus_id' AND 
								route_id = '$route_id' AND 
								device_id = '$device_id' AND 
								date(fordate) = '$fordate' AND 
								from_city = '$from' AND 
								to_city = '$to' AND 
								trip_type = '$trip'
								LIMIT 1";

				$result = $this->execute_sql($sql) ;

				if(isset($result) && !empty($result))
				{
					$sql = "UPDATE 
								num_of_passenger 
							SET 
								count = '$count',
								last_modified_date =  '$date'
							WHERE 	
								bus_id = '$bus_id' AND 
								route_id = '$route_id' AND 
								device_id = '$device_id' AND 
								fordate = '$fordate' AND 
								from_city = '$from' AND 
								to_city = '$to' AND 
								trip_type = '$trip' 
							LIMIT 1";
				}				
				else
				{	
					$sql = "INSERT INTO 
							num_of_passenger(bus_id,route_id,device_id,fordate,from_city,to_city,count,trip_type,created_date,resource_type,last_modified_date) 
						    VALUES('$bus_id','$route_id','$device_id','$savedate','$from','$to','$count','$trip','$date','app','$date')";
						
				}	
				
				$res = $this->db->query($sql);
			}	
		}
			
		return $res;
	}



	public function device_name($device_id) {
		$date = date(CHANGE_INTO_DATE_FORMAT);

		$name = "";

		$sql = "SELECT id,name FROM devices WHERE  device_id = '$device_id' LIMIT 1";

		$result = $this->execute_sql($sql);

		if(isset($result) && !empty($result))	{
			if(trim($result[0] -> name) == ""){	
				$id = $result[0] -> id;
				
				$name = "Device - $id";

				$sql = "UPDATE devices  SET name = '$name', is_deleted = 0, last_modified_date = '$date'
					WHERE id = '$id' LIMIT 1";
				$this->db->query($sql);	
			}
			else	{
				$name = trim($result[0] -> name);
			}	
		}
		else {
			$sql = "INSERT INTO devices(device_id,created_date,resource_type,last_modified_date) VALUES('$device_id','$date','app','$date')";

			$this->db->query($sql);

			$id = $this->db->insert_id();

			if(isset($id) && !empty($id) && $id>0)	{
				$name = "Device - $id";

				$sql = "UPDATE devices 	SET name = '$name',  is_deleted = 0, last_modified_date = '$date' 
					WHERE id = '$id' LIMIT 1";

				$this->db->query($sql);	
			}			

		}
		return $name;
	}



	public function get_bus_details($device_id,$date) {
		$date = date(CHANGE_INTO_DATE_FORMAT,strtotime($date));
		$response = array();

		$sql = "SELECT bus_id  FROM device_assigned WHERE device_id IN ( SELECT id FROM devices WHERE device_id = '$device_id' AND is_deleted = 0) AND
					fordate = '$date'  AND is_deleted = 0 LIMIT 1";	

		$result = $this->execute_sql($sql);

		//echo "<br/><br/>".$sql;	

		if(isset($result) && !empty($result)) {
			$bus_id = $result[0] -> bus_id;

			//Get bus details --------------------------------------------------
			$sql = "SELECT b.id,b.name,b.bus_number,b.registration_image,b.permit_image,b.insurance_image FROM 
						bus b WHERE b.id = $bus_id AND b.is_deleted = 0 LIMIT 1";

			//echo "<br/><br/>".$sql;				

			$bus_info = $this->execute_sql($sql);

			if(isset($bus_info) && !empty($bus_info))  {
				$bus = array();
				
				$bus['id'] = $bus_info[0] -> id;
				
				$bus['name'] = $bus_info[0] -> name;
				
				$bus['number'] = $bus_info[0] -> bus_number;
				
				$bus['registration'] = "";
				if(isset($bus_info[0] -> registration_image) && !empty($bus_info[0] -> registration_image)) {
					$bus['registration'] = base_url().BUS_UPLOADS.$bus_info[0] -> registration_image;
				}	
				
				
				$bus['permit'] = "";
				if(isset($bus_info[0] -> permit_image) && !empty($bus_info[0] -> permit_image)) {
					$bus['permit'] = base_url().BUS_UPLOADS.$bus_info[0] -> permit_image;
				}


				$bus['insurance'] = "";				
				if(isset($bus_info[0] -> insurance_image) && !empty($bus_info[0] -> insurance_image)) {
					$bus['insurance'] = base_url().BUS_UPLOADS.$bus_info[0] -> insurance_image;
				}

				//Get driver details ------------------------------------------
				$sql = "SELECT s.name, s.contact_number, s.image_path FROM  staff s LEFT JOIN staff_assigned sa ON s.id = sa.driver WHERE sa.bus_id = $bus_id AND sa.fordate = '$date' AND s.	staff_type = 2 AND sa.is_deleted = 0 AND s.is_deleted = 0 LIMIT 1";

				//echo "<br/><br/>".$sql;			

				$result = $this->execute_sql($sql);

				if(isset($result) && !empty($result))	{
					$bus['driver_name'] = $result[0] -> name;

					$bus['driver_number'] = $result[0] -> contact_number;

					$bus['driver_licence_url'] = "";
					if(isset($result[0] -> image_path) && !empty($result[0] -> image_path))	{
						$bus['driver_licence_url'] = base_url().STAFF_UPLOADS.$result[0] -> image_path;
					}	
				}
				else
				{
					$bus['driver_name'] = "";

					$bus['driver_number'] = "";

					$bus['driver_licence_url'] = "";
					
					$bus['driver_licence_url'] = "";					
				}

				$response['data'] = $bus;

				$response['response'] = true;	

			}
			else
			{
				$response['msg'] = "No bus details found for selected device and date.";
				
				$response['response'] = false;
			}	

		}
		else
		{
			$response['msg'] = "No bus found for selected device and date.";
			
			$response['response'] = false;
		}

		//echo "<pre>";print_r($response);

		return $response;
	}	



	public function get_route_details($device_id,$date,$bus_id)
	{
		$date = date(CHANGE_INTO_DATE_FORMAT,strtotime($date));

		$response = array();

		$sql = "SELECT 
					r.id, r.name, r.source_city, r.destination_city, brv.max_trip
				FROM 
					bus_route_validity brv
				LEFT JOIN route r ON brv.route_id = r.id	
				WHERE 
					brv.bus_id IN ( SELECT id FROM bus WHERE id = '$bus_id' AND is_deleted = 0) AND
					brv.valid_from <= '$date' AND brv.valid_to >= '$date' AND brv.is_deleted = 0 AND r.is_deleted = 0
				LIMIT 1";	

		//echo "<br/><br/>".$sql;		

		$result = $this->execute_sql($sql);

		if(isset($result) && !empty($result))
		{
			$id = $result[0] -> id;
			$name = $result[0] -> name;
			$source_city = $result[0] -> source_city;
			$destination_city = $result[0] -> destination_city;
			$max_trip = $result[0] -> max_trip;

			$response['route_id'] = $id;
			$response['route_name'] = $name;
			$response['source_city'] = $source_city;
			$response['destination_city'] = $destination_city;
			$response['max_trip'] = $max_trip;
		}
		else
		{
			$response['msg'] = "No route found for selected bus. Please check also check the validity date of route for bus.";
			
			$response['response'] = false;
		}

		return $response;

	}	


	public function bus_timing_details($device_id,$date,$bus_id)
	{
		$date = date(CHANGE_INTO_DATE_FORMAT,strtotime($date));

		$response = array();

		$sql = "SELECT 
					r.id, r.name, r.source_city, r.destination_city, brv.max_trip
				FROM 
					bus_route_validity brv
				LEFT JOIN route r ON brv.route_id = r.id	
				WHERE 
					brv.bus_id IN ( SELECT id FROM bus WHERE id = '$bus_id' AND is_deleted = 0) AND
					brv.valid_from <= '$date' AND brv.valid_to >= '$date' AND brv.is_deleted = 0 AND r.is_deleted = 0
				LIMIT 1";	

		//echo "<br/><br/>".$sql;		

		$result = $this->execute_sql($sql);

		if(isset($result) && !empty($result))
		{
			$id = $result[0] -> id;
			

				//Get route timing details --------------------------------------------------
				$sql = "SELECT c.id,c.name as city_name, c.lattitude, c.longitude,bt.arrival_date,
						bt.arrival_hour, bt.arrival_minute, bt.arrival_am_pm,bt.departure_date,
						bt.departure_hour, bt.departure_minute, bt.departure_am_pm,
						bt.trip_type,bt.journey_date,bt.arrival_date 
					FROM 
						bus_timing bt
					LEFT JOIN city c ON bt.city_id = c.id						
					WHERE 
						bt.route_id = $id AND bt.bus_id = $bus_id AND bt.journey_date = '$date'  AND bt.is_deleted = 0 AND c.is_deleted = 0";

				
				"<br/><br/>".$sql;	
						
				$timing = $this->execute_sql($sql);				

				if(isset($timing) && !empty($timing))
				{
					foreach($timing as $obj)
					{
						$city_id = $obj -> id;
						$city_name = $obj -> city_name;					
						$lattitude = $obj -> lattitude;
						$longitude = $obj -> longitude;

						$arrival_date = $obj -> arrival_date;
						$arrival_hour = $obj -> arrival_hour;
						$arrival_minute = $obj -> arrival_minute;
						$arrival_am_pm = $obj -> arrival_am_pm;
						
						$departure_date = $obj -> departure_date;
						$departure_hour = $obj -> departure_hour;
						$departure_minute = $obj -> departure_minute;
						$departure_am_pm = $obj -> departure_am_pm;
						
						$trip_type = $obj -> trip_type;
						$journey_date = $obj -> journey_date;
						$arrival_date = $obj -> arrival_date;

						$arrival = "";
						if($arrival_date!="" && $arrival_hour!="" && $arrival_minute!="")
						{
							$arrival = "$arrival_date:$arrival_hour:$arrival_minute:$arrival_am_pm";
						}

						$departure = "";
						if($departure_date!="" && $departure_hour!="" && $departure_minute!="")
						{
							$departure = "$departure_date:$departure_hour:$departure_minute:$departure_am_pm";
						}

						
						$stoppage_order = 0;
						
						if(isset($response['source_city']) && $response['source_city'] == $city_id)
						{
							$stoppage_order = 1;
						}
						elseif(isset($response['destination_city']) && $response['destination_city'] == $city_id)
						{
							$stoppage_order = count($stps) + 2;
						}
						elseif(isset($stps[$city_id]))	
						{
							$stoppage_order = $stps[$city_id];
						}

						$str .= "$city_id,$city_name,$lattitude,$longitude,$arrival,$departure,$trip_type,$stoppage_order,$journey_date,$arrival_date#";

					}

					$response['data'] = $str;	

					$response['response'] = true;	
				} else {
					$response['data'] = '';
					$response['message'] = "Please enter bus timing from admin panel for selected bus.";

					$response['response'] = false;
				}	
		}
		else {
			$response['msg'] = "No route found for selected bus. Please check also check the validity date of route for bus.";
			
			$response['response'] = false;
		}

		return $response;

	}	



	public function get_fare_details($device_id,$date,$bus_id,$route_id) {
		
		$date = date(CHANGE_INTO_DATE_FORMAT,strtotime($date));

		$response = array();

		$sql = "SELECT id,source_city,destination_city FROM route WHERE id = '$route_id' AND is_deleted = 0	LIMIT 1";	

		$result = $this->execute_sql($sql);

		if(isset($result) && !empty($result)) {
			$route_id = $result[0] -> id;
			$source_city = $result[0] -> source_city;
			$destination_city = $result[0] -> destination_city;

			//Get bus details --------------------------------------------------
			$sql = "SELECT 
						from_city,to_city,fare
					FROM 
						route_fare
					WHERE 
						route_id = $route_id AND is_deleted = 0";


			$info = $this->execute_sql($sql);

			if(isset($info) && !empty($info)) {
				$fare_info = "";
				
				foreach($info as $obj) {
					$from_city = $obj -> from_city;					
					$to_city = $obj -> to_city;					
					$fare = $obj -> fare;

					$fare_info .= "$from_city,$to_city,$fare#";
				}				

				$response['data'] = $fare_info;
				$response['response'] = true;	

			}  else {
				$response['msg'] = "No fare details found for a route.";
				
				$response['response'] = false;
			}	
		} 	else {
			$response['msg'] = "Requested route does not exist in the system.";
			
			$response['response'] = false;
		}

		return $response;
	}

	
	public function bus_running_status($bus_id,$route_id,$device_id,$fordate,$trip_num,$lattitude,$longitude,$message) {
		$date = date(CHANGE_INTO_DATE_FORMAT);

		$last_modify_date = date(LAST_MODIFIED_DATE);

		$savedate = "";

		if($fordate!="")	{
			$savedate = date(CREATED_DATE,strtotime(str_replace("%20", " ", $fordate)));

			$fordate = date(CHANGE_INTO_DATE_FORMAT,strtotime($savedate));
		}

		
		$res='';
		if(isset($bus_id) && !empty($bus_id) && isset($route_id) && !empty($route_id) && isset($device_id) && !empty($device_id) && isset($fordate) && !empty($fordate) && isset($trip_num) && !empty($trip_num)&& isset($lattitude) && !empty($lattitude) && isset($longitude) && !empty($longitude) && isset($message) && !empty($message))	{
			
			$message = str_replace("%20", " ", $message);
				$sql = "SELECT id FROM running_status WHERE 	bus_id = '$bus_id' AND route_id = '$route_id' AND device_id = '$device_id' AND date(for_date) = '$fordate' LIMIT 1";
				$result = $this->execute_sql($sql) ;
				if(isset($result) && !empty($result)) {
					$sql = "UPDATE running_status SET device_id = '$device_id',
								for_date = '$fordate',
								trip_num = '$trip_num',
								lattitude = '$lattitude',
								longitude = '$longitude',
								message = '$message',
								last_modified_date = '$last_modify_date'
							WHERE bus_id = '$bus_id' AND 
								route_id = '$route_id' AND 
								device_id = '$device_id' AND 
								for_date = '$fordate' 
							LIMIT 1";
				} else {	
					$sql = "INSERT INTO running_status(bus_id,route_id,device_id,for_date,trip_num,lattitude,longitude,message,created_date,resource_type,last_modified_date) 
						    VALUES('$bus_id','$route_id','$device_id','$fordate','$trip_num','$lattitude','$longitude','$message','$date','app','$last_modify_date')";
				}	
				$res = $this->db->query($sql);
		}
		return $res;
	}	
}
?>