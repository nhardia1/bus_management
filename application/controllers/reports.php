<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		
		$this->load->model('reports_model');
		$this->load->model('custom_model');
		
		$this->load->helper('url');
		$this->load->helper('form');		
		$lang = $this->session->userdata('message');
		
		if($lang == "" || !isset($lang))
		{
			$lang = "english";
		}
		$this->lang->load("message",$lang);		
	}

	
	public function index()
	{
		$this->custom_model->check_login_session();

		$page_title['page_title'] = $this->lang->line("fare").' '.$this->lang->line("report"); //'Fare Report';

		$this->load->view('header',$page_title);
		
		$data['bus_list'] = $this->reports_model->bus_list();

		$this->load->view('report_fare',$data);

		$this->load->view('footer');
	}

	
	public function fare()
	{
		$this->custom_model->check_login_session();

		$data = $this->reports_model->fare();

		echo $data;
	}	

	public function fare_details()
	{
		$this->custom_model->check_login_session();

		$data = $this->reports_model->fare_details();

		echo $data;
	}


}//End of class
?>