<?php
class Tracking_model extends CI_Model 
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
		$sql = "SELECT id,name,bus_number FROM bus WHERE is_deleted = 0 ORDER BY name";		
				
		return $this->execute_sql($sql);
	}

	
	public function locate_bus_route() 
	{
		$sql = "SELECT DISTINCT bus_location.bus_id,bus_location.lattitude, bus_location.longitude, bus_location.for_date,bus.name
		FROM bus_location
		INNER JOIN bus
		ON bus_location.bus_id=bus.id
		WHERE 
		bus_location.is_deleted = 0";
				
		return $this->execute_sql($sql);
	}

	public function tracking_bus() 
	{
		$bus_id = $_POST['bus_loc_bus_name'];
		
		//$trip_type = $_POST['bus_loc_trip_type'];
		
		$date = date(CHANGE_INTO_DATE_FORMAT);		

		$data = array();

		$sql = "SELECT bl.lattitude, bl.longitude, bl.trip_num
				FROM bus_location bl
				INNER JOIN bus b ON bl.bus_id = b.id 
				WHERE bl.bus_id='$bus_id' AND date(bl.for_date) = '$date'
				ORDER BY bl.id DESC 
				LIMIT 10";

		echo $sql;die;
		$data['location'] = $this->execute_sql($sql);


		$sql = "SELECT r.id,r.name, r.source_city, r.destination_city,c.name as source,c.lattitude as slat, c.longitude as slon,c1.name as destination,c1.lattitude as dlat, c1.longitude as dlon
				FROM route r 
				LEFT JOIN city c ON r.source_city = c.id 
				LEFT JOIN city c1 ON r.destination_city = c1.id 
				WHERE r.id IN(
					SELECT route_id 
					FROM bus_route_validity 
					WHERE bus_id = '$bus_id' AND valid_from <= '$date' AND valid_to >= '$date' AND is_deleted = 0
					)
				AND r.is_deleted = 0 LIMIT 1";

		$data['route'] = $this->execute_sql($sql);	

				
		$sql = "SELECT rs.city_id, c.name as stoppage,c.lattitude as lat, c.longitude as lon
				FROM route r 
				LEFT JOIN route_stoppage rs ON r.id = rs.route_id
				LEFT JOIN city c ON rs.city_id = c.id				
				WHERE r.id IN(
					SELECT route_id 
					FROM bus_route_validity 
					WHERE bus_id = '$bus_id' AND valid_from <= '$date' AND valid_to >= '$date' AND is_deleted = 0
					)
				AND r.is_deleted = 0 AND rs.is_deleted = 0 
				ORDER BY rs.stoppage_order";		

		$data['stoppage'] = $this->execute_sql($sql);	


		/*$sql = "SELECT nop.from_city, nop.to_city, nop.count
				FROM route r, num_of_passenger nop 
				WHERE r.id IN(
					SELECT route_id 
					FROM bus_route_validity 
					WHERE bus_id = '$bus_id' AND valid_from <= '$date' AND valid_to >= '$date' AND is_deleted = 0
					)
				AND r.is_deleted = 0 AND nop.bus_id = '$bus_id' 
				AND r.source_city = nop.from_city AND r.id = nop.route_id AND date(nop.fordate) = '$date'

				UNION ALL

				SELECT nop.from_city, nop.to_city, nop.count
				FROM route_stoppage rs, num_of_passenger nop 
				WHERE rs.route_id IN(
					SELECT route_id 
					FROM bus_route_validity 
					WHERE bus_id = '$bus_id' AND valid_from <= '$date' AND valid_to >= '$date' AND is_deleted = 0
					)
				AND rs.is_deleted = 0 AND nop.bus_id = '$bus_id' 
				AND rs.city_id = nop.from_city AND rs.route_id = nop.route_id AND date(nop.fordate) = '$date'
				";*/	
		

		return $data;
	}


	public function get_num_of_passenger($bus_id,$route_id,$from_city_id,$from_city_name,$trip_num,$typ='') 
	{		
		$date = date(CHANGE_INTO_DATE_FORMAT);		

		if($trip_num % 2 == 0)
		{
			$sql = "SELECT nop.from_city,(SELECT c.name FROM city c WHERE c.id = nop.from_city) as to_city_name,nop.count
				FROM num_of_passenger nop		
				WHERE nop.to_city = '$from_city_id' AND nop.bus_id = '$bus_id' AND date(nop.fordate) = '$date' AND nop.route_id = '$route_id' AND trip_type = $trip_num";	
		}
		else
		{
			$sql = "SELECT nop.to_city,(SELECT c.name FROM city c WHERE c.id = nop.to_city) as to_city_name,nop.count
				FROM num_of_passenger nop		
				WHERE nop.from_city = '$from_city_id' AND nop.bus_id = '$bus_id' AND date(nop.fordate) = '$date' AND nop.route_id = '$route_id'  AND trip_type = $trip_num";		
		}			

		$rec = $this->execute_sql($sql);

		$str = "Passengers from $typ: <b>$from_city_name</b>";
		if(isset($rec) && !empty($rec))
		{
			foreach($rec as $obj)
			{
				$str .= "<br/>$from_city_name - ".$obj->to_city_name." = ".$obj->count;
			}
		}
		else
		{
			$str = "Passengers from $typ: <b>$from_city_name</b><br/>No record found.";
		}

		return $str;
	}

	

}
?>