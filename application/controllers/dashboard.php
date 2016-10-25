<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller 
{

	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('form');
		$lang = $this->session->userdata('message');
		
		if($lang == "" || !isset($lang))
		{
			$lang = "english";
		}
		$this->lang->load("message","english");
	}

	
	public function index()
	{
		
		$site_data['meta_title']=$this->lang->line("dashboard");
		$this->load->view('frontend/dashboard',$site_data);
	}

	



}
?>