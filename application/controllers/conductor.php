<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

class Conductor extends CI_Controller 
{

	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('ui');
		$this->load->helper('common');
		$this->load->model('routes_model');
		$this->load->model('operator_model');
		$this->load->model('custom_model');
		$lang = $this->session->userdata('message');
		
		if($lang == "" || !isset($lang))
		{
			$lang = "english";
		}
		$this->lang->load("message","english");
	}

	public function index()
	{

		
		$this->custom_model->check_frontend_login_session();
		$session=$this->session->userdata('user_detail');
		$conductor_id=$session['id'];

		$fordate = date(CHANGE_INTO_DATE_FORMAT);


		$checkStaff="select bus_route_validity.bus_id,bus_route_validity.route_id from staff_assigned inner join bus_route_validity on staff_assigned.bus_id = bus_route_validity.bus_id where staff_assigned.conductor=$conductor_id AND staff_assigned.fordate='$fordate' AND staff_assigned.is_deleted=0 AND (valid_from <= '$fordate' AND valid_to >= '$fordate')";
		$checkStaffData = $this->custom_model->exec_sql($checkStaff) ;
		if(!empty($checkStaffData)){
				$bus_id=$checkStaffData[0]['bus_id'];
				$route_id=$checkStaffData[0]['route_id'];
				$routeids[]= $route_id;
					//Routes details --------------------------------------------------------------------
					$all = array();	$stp = array();
					$sc = array(); $dc = array();		
					//Routes details --------------------------------------------------------------------
					$arr1 = $this->routes_model->route_details($route_id);			

					if(isset($arr1) && !empty($arr1))
					{
						$sc = $this->routes_model->get_city_name($arr1[0]->source_city);

						$dc = $this->routes_model->get_city_name($arr1[0]->destination_city);
					}

					//Stoppages --------------------------------------------------------------------
					$arr2 = $this->routes_model->route_stoppage_details($route_id);
					if(isset($arr2) && !empty($arr2))
					{
						foreach ($arr2 as $key => $obj) 
						{
							$stp[] = $obj;
						}					
					}
					$all = array_merge($sc,$stp,$dc);

					$route_details[$bus_id.'_'.$route_id]=$all;

		}else{
			$site_data['msg']="No bus route assign to you for today's date";
		}
		$site_data['meta_title']=$this->lang->line("conductor_dashboard");
		$site_data['cities']=getCities();
		$site_data['routes']=$routeids;
		$site_data['route_details']=$route_details;
		$site_data['user_detail']=$session;
		$this->load->view('frontend/conductor',$site_data);
	}
	
	public function check_conductor_login()
	{
	  	$user = $this->session->userdata('user_detail');

		if($user['id'] == "" && $user['staff_type']!="Conductor")
		{
		    redirect('main/index', 'refresh');
		}
		
	}
	public function logout()
  	{
  		$session=$this->session->userdata('user_detail');
  		
	 	if($this->session->userdata('user_detail'))
   		{

			$this->session->unset_userdata('user_detail');
		    session_destroy();
		   	// echo 1;die;

		    redirect('main', 'refresh');
		    
	    }
	    redirect('main', 'refresh');
   
  	}
}
?>