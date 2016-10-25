<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

class Operator extends CI_Controller 
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
		$operator_id=$session['id'];
		$record_fordate = $_GET['datetime'];
		if($record_fordate!=''){
			$record_fordate = str_replace('/', '-', $record_fordate);
			$record_fordate=date('Y-m-d', strtotime($record_fordate));
		}else{
			$record_fordate='';
		}
		
		$from_city=$_GET['from_city'];
		$to_city=$_GET['to_city'];
		$search=$_GET['search'];


		if(isset($search) && $search!=''){
			$search_results=$this->operator_model->get_multi_search($from_city,$to_city,$record_fordate);
			foreach ($search_results as $route ) {

						$routeids[]= $route;
						$bus_id=$this->operator_model->get_busid_by_route($route);
						//Routes details --------------------------------------------------------------------
						$all = array();	$stp = array();
						$sc = array(); $dc = array();		
						//Routes details --------------------------------------------------------------------
						$arr1 = $this->routes_model->route_details($route);			

						if(isset($arr1) && !empty($arr1))
						{
							$sc = $this->routes_model->get_city_name($arr1[0]->source_city);

							$dc = $this->routes_model->get_city_name($arr1[0]->destination_city);
						}

						//Stoppages --------------------------------------------------------------------
						$arr2 = $this->routes_model->route_stoppage_details($route);
						if(isset($arr2) && !empty($arr2))
						{
							foreach ($arr2 as $key => $obj) 
							{
								$stp[] = $obj;
							}					
						}
						$all = array_merge($sc,$stp,$dc);
						if($bus_id!=''){
							$route_details[$bus_id.'_'.$route]=$all;	
						}
							
					}

		}else{
			$current_date=date('Y-m-d');
			$buses=$this->operator_model->get_bus_by_operator_id($operator_id);
			foreach($buses as $bus){
					$bus_id=$bus['id'];
					$where=array("bus_id"=>$bus_id,"is_deleted"=>0);
					
					$valid_routes=$this->operator_model->getAllWhereData('route_id','bus_route_validity',$where);
					foreach ($valid_routes as $route ) {
						$routeids[]= $route->route_id;
						//Routes details --------------------------------------------------------------------
						$all = array();	$stp = array();
						$sc = array(); $dc = array();		
						//Routes details --------------------------------------------------------------------
						$arr1 = $this->routes_model->route_details($route->route_id);	


						if(isset($arr1) && !empty($arr1))
						{
							$sc = $this->routes_model->get_city_name($arr1[0]->source_city);

							$dc = $this->routes_model->get_city_name($arr1[0]->destination_city);
							
						}

						//Stoppages --------------------------------------------------------------------
						$arr2 = $this->routes_model->route_stoppage_details($route->route_id);
						if(isset($arr2) && !empty($arr2))
						{
							foreach ($arr2 as $key => $obj) 
							{
								$stp[] = $obj;
							}					
						}
						$all = array_merge($sc,$stp,$dc);

						$route_details[$bus_id.'_'.$route->route_id]=$all;		
						
					}
			}
		}
		//echo "<pre>";
		//print_r($route_details);die;
		$site_data['meta_title']=$this->lang->line("operator_dashboard");
		$site_data['cities']=getCities();
		$site_data['routes']=$routeids;
		$site_data['route_details']=$route_details;
		$site_data['user_detail']=$session;
		$this->load->view('frontend/operator',$site_data);
	}
	/*public function index()
	{

		$this->custom_model->check_frontend_login_session();
		$session=$this->session->userdata('user_detail');
		$operator_id=$session['id'];
		$record_fordate = $_GET['datetime'];
		if($record_fordate!=''){
			$record_fordate = str_replace('/', '-', $record_fordate);
			$record_fordate=date('Y-m-d', strtotime($record_fordate));
		}else{
			$record_fordate='';
		}
		
		$from_city=$_GET['from_city'];
		$to_city=$_GET['to_city'];


		if(isset($from_city) && $from_city!='0'){
			$search_results=$this->operator_model->get_multi_search($from_city,$to_city,$record_fordate);
			foreach ($search_results as $route ) {
						$routeids[]= $route->route_id;
						$bus_id=$this->operator_model->get_busid_by_route($route->route_id);
						//Routes details --------------------------------------------------------------------
						$all = array();	$stp = array();
						$sc = array(); $dc = array();		
						//Routes details --------------------------------------------------------------------
						$arr1 = $this->routes_model->route_details($route->route_id);			

						if(isset($arr1) && !empty($arr1))
						{
							$sc = $this->routes_model->get_city_name($arr1[0]->source_city);

							$dc = $this->routes_model->get_city_name($arr1[0]->destination_city);
						}

						//Stoppages --------------------------------------------------------------------
						$arr2 = $this->routes_model->route_stoppage_details($route->route_id);
						if(isset($arr2) && !empty($arr2))
						{
							foreach ($arr2 as $key => $obj) 
							{
								$stp[] = $obj;
							}					
						}
						$all = array_merge($sc,$stp,$dc);
						$route_details[$bus_id.'_'.$route->route_id]=$all;		
					}



		}else{
			
			$buses=$this->operator_model->get_bus_by_operator_id($operator_id);
			foreach($buses as $bus){
					$bus_id=$bus['id'];
					$where=array("bus_id"=>$bus_id,"is_deleted"=>0);
					$valid_routes=$this->operator_model->getAllWhereData('route_id','bus_route_validity',$where);
					foreach ($valid_routes as $route ) {
						$routeids[]= $route->route_id;
						//Routes details --------------------------------------------------------------------
						$all = array();	$stp = array();
						$sc = array(); $dc = array();		
						//Routes details --------------------------------------------------------------------
						$arr1 = $this->routes_model->route_details($route->route_id);			

						if(isset($arr1) && !empty($arr1))
						{
							$sc = $this->routes_model->get_city_name($arr1[0]->source_city);

							$dc = $this->routes_model->get_city_name($arr1[0]->destination_city);
						}

						//Stoppages --------------------------------------------------------------------
						$arr2 = $this->routes_model->route_stoppage_details($route->route_id);
						if(isset($arr2) && !empty($arr2))
						{
							foreach ($arr2 as $key => $obj) 
							{
								$stp[] = $obj;
							}					
						}
						$all = array_merge($sc,$stp,$dc);

						$route_details[$bus_id.'_'.$route->route_id]=$all;		
						
					}
			}
		}
		$site_data['meta_title']=$this->lang->line("operator_dashboard");
		$site_data['cities']=getCities();
		$site_data['routes']=$routeids;
		$site_data['route_details']=$route_details;
		$site_data['user_detail']=$session;
		$this->load->view('frontend/operator',$site_data);
	}*/
	public function check_operator_login()
	{
	  	$user = $this->session->userdata('user_detail');

		if($user['id'] == "" && $user['staff_type']!="Operator")
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
  	public function search(){
  		$record_fordate = $_POST['datetime'];
		$record_fordate = str_replace('/', '-', $record_fordate);
		$record_fordate=date('Y-m-d', strtotime($record_fordate));
		$from_city=$this->input->post('from_city');
		$to_city=$this->input->post('to_city');
  		$search_results=$this->operator_model->get_search_details($from_city,$to_city,$record_fordate);
  		foreach ($search_results as $route ) {
					$routeids[]= $route->route_id;
					$busid=$route->bus_id;
					//Routes details --------------------------------------------------------------------
					$all = array();	$stp = array();
					$sc = array(); $dc = array();		
					//Routes details --------------------------------------------------------------------
					$arr1 = $this->routes_model->route_details($route->route_id);			

					if(isset($arr1) && !empty($arr1))
					{
						$sc = $this->routes_model->get_city_name($arr1[0]->source_city);

						$dc = $this->routes_model->get_city_name($arr1[0]->destination_city);
					}

					//Stoppages --------------------------------------------------------------------
					$arr2 = $this->routes_model->route_stoppage_details($route->route_id);
					if(isset($arr2) && !empty($arr2))
					{
						foreach ($arr2 as $key => $obj) 
						{
							$stp[] = $obj;
						}					
					}
					$all = array_merge($sc,$stp,$dc);
					$route_details[$busid]=$all;		
				}
		$site_data['meta_title']=$this->lang->line("operator_dashboard");
		$site_data['cities']=getCities();
		$site_data['routes']=$routeids;
		$site_data['route_details']=$route_details;
		$site_data['bus_ids']=$busids;
		$this->load->view('frontend/operator',$site_data);		
  		
  	}


	



}
?>