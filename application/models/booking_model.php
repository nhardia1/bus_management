<?php
class Booking_model extends CI_Model 
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
	public function get_route_fare_by_city($route_id,$soure_city,$des_city){
		if(isset($route_id) && !empty($route_id) && $route_id>0)
		{
			$sql = "SELECT 
					fare
				FROM
					route_fare
				WHERE 
					is_deleted = 0 AND route_id = $route_id AND from_city= $soure_city AND to_city=$des_city
				";

			$result = $this->execute_sql($sql);	
			

			if(isset($result) && !empty($result))
			{
				return $result[0]->fare;	
			}else{
				return false;
			}

		}

	}
	public function make_booking(){

		$session = $this->session->userdata('user_detail');

		if(isset($_POST['seatid'])){
			$j=0;
			foreach ($_POST['seatid'] as $seatid) {
				$date=date(CREATED_DATE);
				$booking_id="web_".$session['id']."_".time();
				$bookingarr=array(
								"booking_id"=>$booking_id,
								"seat_id"=>$seatid,
								"seat_type"=>"seating",
								"bus_id"=>$_POST['bus_id'],
								"booked_by_user_id"=>$session['id'],
								"booked_by_user_type"=>$session['staff_type'],
								"booked_source_city"=>$_POST['from_book_city'],
								"booked_destination_city"=>$_POST['to_book_city'],
								"booked_to_passanger_type"=>$_POST['stype'][$j],
								"route_id"=>$_POST['route_id'],
								"fare_received"=>$_POST['fare'][$j],
								"trip_num"=>1,
								"is_cancel"=>0,
								"created_date"=>date(CREATED_DATE),
								"resource_type"=>"web",
								"created_by"=>$session['id']);
				$this->db->insert('seat_booking_history', $bookingarr);
				$j++;
			}


		}
	}
	public function make_lugg_booking(){
		$session = $this->session->userdata('user_detail');
		if(isset($_POST['phone_no']) && isset($_POST['select_size'])){
				$date=date(CREATED_DATE);
				$booking_id="web_".$session['id']."_".time();
				$bookingarr=array(
								"luggage_id"=>$booking_id,
								"bus_id"=>$_POST['bus_id'],
								"booked_by_user_id"=>$session['id'],
								"booked_by_user_type"=>$session['staff_type'],
								"booked_to_owner_phone"=>$_POST['phone_no'],
								"booked_source_city"=>$_POST['booked_source_city'],
								"booked_destination_city"=>$_POST['booked_destination_city'],
								"luggage_size"=>$_POST['select_size'],
								"route_id"=>$_POST['route_id'],
								"created_date"=>date(CREATED_DATE),
								"resource_type"=>"web",
								"created_by"=>$session['id']);
				$this->db->insert('luggage_booking_history', $bookingarr);
				$insert_id = $this->db->insert_id();
				return $insert_id;
		}
	}
	public function remove_luggage($id){

		$session = $this->session->userdata('user_detail');
		$login_user_id=$session['id'];
		$this->db->set('is_deleted', 1);
		$this->db->set('last_modified_by', $login_user_id);
		$this->db->set('last_modified_date', date(LAST_MODIFIED_DATE));
		$this->db->where("id =",$id);
		$this->db->update('luggage_booking_history');	

	}

}	


	

?>