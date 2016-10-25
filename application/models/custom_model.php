<?php

class Custom_model extends CI_Model {

	public function __construct()
	{
			
		$this->load->database();
	}
	
	
	public function check_login_session()
	{
	  	$user = $this->session->userdata('user_details');

		if($user['id'] == "")
		{
		    redirect('login/index', 'refresh');
		}
		
	}
	public function check_pin($pincode){
		$this->db->select('id,name,contact_number,address,staff_type,staff_type_num,agency_id,profile_image');
		$this->db->from('staff');
		$this->db->where('staff_pin',md5($pincode));
		$query=$this->db->get();
		if($query->num_rows>0){
			return $query->result();
		}else{
			return false;
		}
	}
	public function checkAvailabilityRecords($select,$table,$where,$rows) 
    {
       $this->db->select($select);
	   $this->db->from($table);
	   $this->db->where($where);
	   $query=$this->db->get();
	   if($rows==1)
	   {
	   		return $query->row_array();
	   }
	   else if($rows==2)
	   {
		   return $query->result_array();
	   }
    }

    public function checkDistinctAvailabilityRecords($select,$table,$where,$rows) 
    {
       $this->db->select($select);
       $this->db->distinct();
	   $this->db->from($table);
	   $this->db->where($where);
	   $query=$this->db->get();
	   if($rows==1)
	   {
	   		return $query->row_array();
	   }
	   else if($rows==2)
	   {
		   return $query->result_array();
	   }
    }
    public function check_frontend_login_session()
	{
	  	$user = $this->session->userdata('user_detail');

		if($user['id'] == "")
		{
		    redirect('main/index', 'refresh');
		}
		
	}
	public function exec_sql($sql) 
	{
		$query = $this->db->query($sql);
		return $query->result_array();
		
	}



}