<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller 
{

	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->model('custom_model');
	}

	
	public function index()
	{
		
		$site_data['meta_title']="Check In";
		$this->load->view('frontend/login',$site_data);
	}
	public function check_pin()
	 {

	   //Field validation succeeded.  Validate against database
	   $pincode = $this->input->post('pincode');
	   //query the database
	   $result = $this->custom_model->check_pin($pincode);
	  if($result)
	   {
	     $sess_array = array();
	     foreach($result as $row)
	     {
	       $sess_array = array(
	         'id' => $row->id,
	         'name' => $row->name,
		  	 'staff_type'=>$row->staff_type,
		  	 'staff_type_num'=>$row->staff_type_num,
		  	 'profile_image'=>$row->profile_image
	       );

	       $this->session->set_userdata('message','english');
	       $this->session->set_userdata('user_detail', $sess_array);
	       if($sess_array['staff_type']=='Operator'){
	       		redirect('operator'); 
	       }else if($sess_array['staff_type']=='Agency'){
	       		redirect('agency'); 
	       }else if($sess_array['staff_type']=='Conductor'){
	       	    redirect('conductor');
	       }else{
	       		redirect('dashboard');
	       }
	       
	     }
	   }
	   else
	   {
		$this->session->set_flashdata('error', 'Invalid Pin');
		
		redirect('main');
	   }
	}

	



}
?>