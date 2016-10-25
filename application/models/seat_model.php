<?php
class Seat_model extends CI_Model 
{

	public function __construct()
	{  	
	  	parent::__construct();
		
		$this->load->database();
	}


	public function seat_list() {
		
		$this->db->select('id,seat_type_name,seat_cocach_type,seat_capacity');
		$this->db->where('is_deleted',0);
		$query = $this->db->get('seat_configuration');
		return $query->result();
	}
	public function get_seat_details($id) 
	{
		$this->db->select('id,template_name,seat_type_name,seat_cocach_type,seat_capacity');
		$this->db->where('is_deleted',0);
		$this->db->where('id',$id);
		$query = $this->db->get('seat_configuration');
		return $query->row_array();
	}

	public function insert_seat() 
	{
		$session = $this->session->userdata('user_details');
		$errors = array();
		$data = array();
		if(!empty($_POST['seat_type']) && !empty($_POST['coach_type']) && !empty($_POST['seat_capacity']) && !empty($_POST['seat_allocation']))
		{
			$status=1;
			//$user_id=$session['id'];
			$seat_type=$_POST['seat_type'];
			$template_name=$_POST['template_name'];
			$coach_type=$_POST['coach_type'];
			$seat_capacity=$_POST['seat_capacity'];
			$seat_allocation=$_POST['seat_allocation'];
			$created_by=$session['id'];
			$date=date(CREATED_DATE);
			
			$seat_data=array("template_name"=>$template_name,
							"seat_type_name"=>$seat_type,
							"seat_cocach_type"=>$coach_type,
							"seat_capacity"=>$seat_capacity,
							"seat_allocation"=>$seat_allocation,
							"created_date"=>$date,
							"created_by"=>$session['id'],
							"is_deleted"=>0);
			$this->db->insert('seat_configuration', $seat_data);
		}

		if (!empty($errors)) {
		  $data  = $errors;
		} else {
		  $data = 'Seat template has been added successfully.';
		}
		return $data;
		
	}


	public function delete_seat($id) 
	{
		$session = $this->session->userdata('user_details');
		$login_user_id=$session['id'];
		
		$errors = array();
		$data = array();
		
		
			$this->db->set('is_deleted', 1);
			$this->db->set('last_modified_by', $login_user_id);
			$this->db->set('last_modified_date', date(LAST_MODIFIED_DATE));
			$this->db->where("id =",$id);
			$this->db->update('staff');			
			echo "<script>alert('Staff Deleted');</script>";
			redirect('staff/index', 'refresh');
	}


	//Developer 1 ----------------------------------------------------------------------
	public function execute_sql($sql) 
	{
		$query = $this->db->query($sql);
		
		return $query->result();
	}

	public function update_seat() 
	{
		$errors='';
		$session = $this->session->userdata('user_details');
		$errors = array();
		$data = array();
		if(!empty($_POST['seat_type']) && !empty($_POST['coach_type']) && !empty($_POST['seat_capacity']) && !empty($_POST['seat_allocation']))
		{
			$status=1;
			$id=$_POST['id'];
			$template_name=$_POST['template_name'];
			$seat_type=$_POST['seat_type'];
			$coach_type=$_POST['coach_type'];
			$seat_capacity=$_POST['seat_capacity'];
			$seat_allocation=$_POST['seat_allocation'];
			$created_by=$session['id'];
			$date=date(CREATED_DATE);
			$seat_data=array("template_name"=>$template_name,
							"seat_type_name"=>$seat_type,
							"seat_cocach_type"=>$coach_type,
							"seat_capacity"=>$seat_capacity,
							"seat_allocation"=>$seat_allocation,
							"last_modified_date" => $date,
							"last_modified_by" => $created_by);
			$this->db->where('id',$id);
			$this->db->update('seat_configuration',$seat_data);
		}

		if (!empty($errors)) {
		  $data  = $errors;
		} else {
		  $data = 'Staff details has been updated successfully.';
		}
		return $data;
	}



}
?>