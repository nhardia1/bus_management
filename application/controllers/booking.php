<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking extends CI_Controller 
{

	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('ui');
		$this->load->model('booking_model');
		$lang = $this->session->userdata('message');
		
		if($lang == "" || !isset($lang))
		{
			$lang = "english";
		}
		$this->lang->load("message","english");
	}

	
	public function index()
	{
		
	}
	public function get_fare(){

		
		$route_id=$_POST['rid'];
		$soure_city=$_POST['scity'];
		$des_city=$_POST['dcity'];
		echo $this->booking_model->get_route_fare_by_city($route_id,$des_city,$soure_city);

	}
	public function book_ticket(){
		$this->booking_model->make_booking();
		echo json_encode(array("bus_id"=>$_POST['bus_id'],"seats"=>$_POST['seatid'],"psngr_type"=>$_POST['stype']));
	}
	public function book_luggage(){
		$last_insert_id=$this->booking_model->make_lugg_booking();
		echo json_encode(array("bus_id"=>$_POST['bus_id'],"select_size"=>$_POST['select_size'],"phone_no"=>$_POST['phone_no'],"lugg_id"=>$last_insert_id));
	}
	public function remove_luggage(){
		$id=$_POST['id'];
		if(isset($id) && $id!=''){
			$this->booking_model->remove_luggage($id);	
			echo json_encode(array('id' => $id,'busid'=>$busid));
		}
	}

	



}
?>