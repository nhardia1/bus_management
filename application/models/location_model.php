<?php
class Location_model extends CI_Model 
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

	
	public function save_location($bus_id,$route_id,$device_id,$fordate,$lat,$long)
	{
		$date = date("Y-m-d");

		$sql = "INSERT INTO bus_location(bus_id,route_id,device_id,for_date,lattitude,longitude,created_date,resource_type) VALUES('$bus_id','$route_id','$device_id','$fordate','$lat','$long','$date','app')";
				
		$res = $this->db->query($sql);

		return $res;
	}


	public function save_passenger_count($bus_id,$route_id,$device_id,$from,$to,$count)
	{
		$date = date("Y-m-d");

		$sql = "INSERT INTO num_of_passenger(bus_id,route_id,device_id,from_city,to_city,count,created_date,resource_type) VALUES('$bus_id','$route_id','$device_id','$from','$to','$count','$date','app')";
				
		$res = $this->db->query($sql);

		return $res;
	}



	public function bus_list() 
	{
		$sql = "SELECT id,name FROM bus WHERE id IN(
				SELECT 
					DISTINCT bus_id
				FROM
					device_assigned
				WHERE 
					is_deleted = 0				
				) AND is_deleted = 0
				ORDER BY name
				";
		
				
		return $this->execute_sql($sql);
	}

	public function device_list() 
	{
		$sql = "SELECT id,name FROM devices WHERE id IN(
				SELECT 
					DISTINCT device_id
				FROM
					device_assigned
				WHERE 
					is_deleted = 0				
				) AND is_deleted = 0
				ORDER BY name
				";
		
				
		return $this->execute_sql($sql);
	}


	public function locate_bus() 
	{
		$bus_id = $_POST['bus_loc_bus_name'];
		
		$date = date("Y-m-d",strtotime($_POST['bus_loc_date']));

		$sql = "SELECT 
					lattitude,longitude
				FROM
					bus_location
				WHERE 
					is_deleted = 0 AND bus_id = '$bus_id' AND date(created_date) = '$date'				
				";
		
				
		return $this->execute_sql($sql);
	}

}
?>