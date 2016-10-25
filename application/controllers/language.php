<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Language extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('session');
		
		$this->load->helper('url');

		$this->load->library('user_agent');

		$this->load->model('custom_model');
	}
	
	public function load($lang = 'english')
	{
		$this->custom_model->check_login_session();	

		$this->session->unset_userdata('message');

		if($lang == "english" || $lang == "hindi")
		{	
			$this->session->set_userdata('message',$lang);
		}
		else
		{
			$this->session->set_userdata('message','english');	
		}	

		redirect($this->agent->referrer());
	}
}
?>