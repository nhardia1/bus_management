<?php
class Device_model extends CI_Model 
{

	public function __construct()
	{  	
		parent::__construct();
		
		$this->load->database();
	}


	public function device_list() {
		
		$this->db->select('id,name,device_id');
		$this->db->where('is_deleted',0);
		$query = $this->db->get('devices');
		return $query->result();
	}

	public function insert_device() 
	{
		
		$session = $this->session->userdata('user_details');
		$errors = '' ; //array();
		$data = array();
		
		if(empty($_POST['device_name'])){

			$errors = 'Device Name is required.';

		} 
		
		if(!empty($_POST['device_name']))
		{ 
			
			$device_name=$_POST['device_name'];
			$created_by=$session['id'];
			$date=date(CREATED_DATE);
			

			$this->db->select('name');
			$this->db->where('is_deleted',0);
			$this->db->where('name',$device_name);
			$query = $this->db->get('devices');
			if($query->num_rows() >0)
			{
				
				$errors='Device name already exist.';
			}
			else
			{
				if(empty($errors)){

					$sql = "INSERT INTO devices(name,user_id,created_by,created_date,last_modified_date) 
					VALUES('$device_name',$created_by,'$created_by','$date','$date')";

					$this->db->query($sql);
					$device_id = $this->db->insert_id();
					$device_code=$device_name.'_'.$device_id;
					$this->db->set('device_code', $device_code);
					$this->db->where("id =",$device_id);
					$this->db->update('devices');	

					
				}

			}
		}

		if (!empty($errors)) {
			$data  = $errors;
		} else {
			$data = 'Device has been added successfully.';
		}
		return $data;
		
	}

	public function get_device_name() {
		
		$device_id=$_POST['device_id'];
		$this->db->select('name');
		$this->db->where('is_deleted',0);
		$this->db->where('id',$device_id);
		$query = $this->db->get('devices');
		$device_array=$query->row_array();
		return json_encode($device_array);
		
	}

	


	public function delete_device($id) 
	{
		$session = $this->session->userdata('user_details');
		$login_user_id=$session['id'];
		
		$errors = array();
		$data = array();
		
		
		$this->db->set('is_deleted', 1);
		$this->db->set('last_modified_by', $login_user_id);
		$this->db->set('last_modified_date', date(LAST_MODIFIED_DATE));
		$this->db->where("id =",$id);
		$this->db->update('devices');	

		
		echo "<script>alert('Device Deleted');</script>";
		redirect('device/index', 'refresh');
	}


	//Developer 1 ----------------------------------------------------------------------
	public function execute_sql($sql) 
	{
		$query = $this->db->query($sql);
		
		return $query->result();
	}

	public function get_device_details($id) {
		
		
		$this->db->select('id,name,device_id');
		$this->db->where('is_deleted',0);
		$this->db->where('id',$id);
		$query = $this->db->get('devices');
		return $query->row_array();
	}

	public function update_device() 
	{
		
		$session = $this->session->userdata('user_details');
		$errors = '';
		$data = array();

		if (empty($_POST['device_name'])){

			$errors = 'device name is required.';
		} 

		if(!empty($_POST['device_name']))
		{
			$id = $_POST['device_id'];
			$device_name=$_POST['device_name'];
			
			$user_id=$session['id'];
			$date=date(LAST_MODIFIED_DATE);

			$this->db->select('name');
			$this->db->where('is_deleted',0);
			$this->db->where('id <>',$id);
			$this->db->where('name',$device_name);
			$query = $this->db->get('devices');
			if($query->num_rows() >0)
			{
				$errors = 'Device name already exist.';
			}
			else
			{
				if(empty($errors)){
					$user_id = $session['id'];
					$date = date(LAST_MODIFIED_DATE);
					$sql = "UPDATE 
					devices 
					SET 
					name = '$device_name',
					last_modified_date = '$date', 
					last_modified_by = '$user_id'
					WHERE
					id = '$id'";

					$this->db->query($sql);

				}
			}
		}

		if (!empty($errors)) {
			$data  = $errors;
		} else {
			$data = 'Device details has been updated successfully.';
		}
		return $data;

		

	}



}
?>