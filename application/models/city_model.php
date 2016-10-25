<?php
class City_model extends CI_Model 
{

	public function __construct()
	{
		
	  	//$this->load->database();
		parent::__construct();
		$this->load->database();
		

	}


	
	public function getAll() {

		$sql="select city.id,city.name,city.created_date, state.name as state_name FROM city INNER JOIN state ON city.state_id=state.id where city.is_deleted=0";
		/*where city.status=1 and city.is_deleted=0 and state.status=1 and state.is_deleted=0*/
		$query=$this->db->query($sql);
		return $query->result();
	}

	public function getCityDetail($id) {

		$sql="select city.id,city.name, state.name as state_name FROM city INNER JOIN state ON city.state_id=state.id where city.id=$id and city.is_deleted=0";
		/*where city.status=1 and city.is_deleted=0 and state.status=1 and state.is_deleted=0*/
		$query=$this->db->query($sql);
		return $query->row_array();
	}

	public function get_all_states() {
		
		//$this->db->where('status',1);
		//$this->db->where('is_deleted',0);
		$query = $this->db->get('state');
		return $query->result();
	}

	public function insert_city() 
	{
		$session = $this->session->userdata('user_details');
		$errors = array();
		$data = array();
		

		if (empty($_POST['route_states'])){

			$errors = 'State name is required.';
		} else if (empty($_POST['name'])){

			$errors = 'City name is required.';
		}

		if(!empty($_POST['name']) && !empty($_POST['route_states']))
		{
			$status=1;
			$user_id=$session['id'];
			$route_states=$_POST['route_states'];
			$date=date(CREATED_DATE);
			$name=$_POST['name'];

			$this->db->select('name');
			$this->db->where('is_deleted',0);
			$this->db->where('state_id',$route_states);
			$this->db->where('name',$name);
			$query = $this->db->get('city');
			if($query->num_rows() >0)
			{
				
				$errors='City Name Already Exist.';
			}
			else
			{

				/*------------------------------------------------------*/
				/*$name=strtolower($name);
				$address = ucfirst($name).'+India';
				$address = preg_replace("/[^ \w]+/", "+", $address);*/
				$name = trim($name);
				$name = preg_replace("/[\s]+/", "+", $name);
				$address = $name.'+India';

				$geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$address.'&sensor=false');

				$output = json_decode($geocode);
				$lat = 0;
				$long = 0;
				if(isset($output) && !empty($output) && $output->status == "OK")
				{	
					$lat = $output->results[0]->geometry->location->lat;
					$long = $output->results[0]->geometry->location->lng;
				}	
				
				/*------------------------------------------------------*/
				$sql = "INSERT INTO city(user_id,state_id,name,lattitude,longitude,created_date,last_modified_date) 
				VALUES('$user_id','$route_states','$name','$lat','$long','$date','$date')";

				$this->db->query($sql);
				

			}
		}

		if (!empty($errors)) {
			$data  = $errors;
		} else {
			$data = 'City has been added successfully.';
		}
		return $data;
		
	}


	public function delete_city($id) 
	{
		$session = $this->session->userdata('user_details');
		$login_user_id=$session['id'];
		
		$errors = array();
		$data = array();
		
		
		$this->db->set('is_deleted', 1);
		$this->db->set('last_modified_by', $login_user_id);
		$this->db->set('last_modified_date', date(LAST_MODIFIED_DATE));
		$this->db->where("id =",$id);
		$this->db->update('city');			
		echo "<script>alert('City Deleted');</script>";
		redirect('city/index', 'refresh');
	}


	//Developer 1 ----------------------------------------------------------------------
	public function execute_sql($sql) 
	{
		$query = $this->db->query($sql);
		
		return $query->result();
	}

	public function get_city_details($city_id) 
	{
		$sql="select city.id,city.name,state.name as state_name ,state.id as state_id FROM city INNER JOIN state ON city.state_id=state.id where city.is_deleted=0 and state.is_deleted=0 and city.id=$city_id";

		return $this->execute_sql($sql);	
	}


	public function update_city() 
	{
		$session = $this->session->userdata('user_details');
		$errors = array();
		$data = array();

		if (empty($_POST['name'])){

			$errors['name'] = 'City name is required.';
		}
		if (empty($_POST['route_states'])){

			$errors['name'] = 'State name is required.';
		}

		if(!empty($_POST['name']))
		{
			//$cid = $_POST->id;
			$cid = $_POST['cid'];
			$state_id = $_POST['route_states'];
			$name=$_POST['name'];

			$this->db->select('name');
			$this->db->where('is_deleted',0);
			$this->db->where('id <>',$cid);
			$this->db->where('state_id',$state_id);
			$this->db->where('name',$name);
			$query = $this->db->get('city');
			if($query->num_rows() >0)
			{
				$errors = 'City Name Already Exist.';
			}
			else
			{

				$name=strtolower($name);
				$address = ucfirst($name).'+India';
				$address = preg_replace("/[^ \w]+/", "+", $address);

				$geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$address.'&sensor=false');

				$output= json_decode($geocode);

				$lat = $output->results[0]->geometry->location->lat;
				$long = $output->results[0]->geometry->location->lng;
				$user_id = $session['id'];
				$date = date(LAST_MODIFIED_DATE);
				$sql = "UPDATE 
				city 
				SET 
				name = '$name',
				state_id = '$state_id',
				last_modified_date = '$date', 
				last_modified_by = '$user_id'
				WHERE
				id = '$cid'";

				$this->db->query($sql);
				

			}
		}

		if (!empty($errors)) {
			$data  = $errors;
		} else {
			$data = 'City details has been updated successfully.';
		}
		return $data;

		

	}





}
?>