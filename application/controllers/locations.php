<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Locations extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('session');
		
		$this->load->model('custom_model');

		$this->load->model('location_model');

		$this->load->helper('url');

		$this->load->helper('form');		
		
	}

	public function save_location($bus_id,$route_id,$device_id,$fordate,$lat,$long)
	{
		if(isset($bus_id) && !empty($bus_id) && $bus_id>0 && isset($route_id) && !empty($route_id) && $route_id>0 && isset($device_id) && !empty($device_id) && isset($lat) && !empty($lat) && isset($long) && !empty($long) && isset($fordate) && !empty($fordate))
		{
			$response = $this->location_model->save_location($bus_id,$route_id,$device_id,$fordate,$lat,$long);

			if($response)
			{
				
				echo json_encode(array("response"=>true));
			}
			else
			{
				
				echo json_encode(array("response"=>false));
			}
		}

	}


	public function save_passenger_count($bus_id,$route_id,$device_id,$from,$to,$count)
	{
		if(isset($bus_id) && !empty($bus_id) && $bus_id>0 && isset($route_id) && !empty($route_id) && $route_id>0 && isset($device_id) && !empty($device_id) && isset($from) && !empty($from) && isset($to) && !empty($to) && isset($count) && !empty($count) && $count>0)
		{
			$response = $this->location_model->save_passenger_count($bus_id,$route_id,$device_id,$from,$to,$count);

			if($response)
			{
				
				echo json_encode(array("response"=>true));
			}
			else
			{
				
				echo json_encode(array("response"=>false));
			}
		}

	}


	public function index()
	{
		$this->custom_model->check_login_session();

		$data['bus_list'] = $this->location_model->bus_list();

		$data['device_list'] = $this->location_model->device_list();

		$this->load->view('header');

		$this->load->view('locations',$data);

		$this->load->view('footer');
	}


	public function locate_bus()
	{
		$this->custom_model->check_login_session();

		if(isset($_POST['bus_loc_bus_name']) && !empty($_POST['bus_loc_bus_name']) && isset($_POST['bus_loc_date']) && !empty($_POST['bus_loc_date']))
		{	

			$points = $this->location_model->locate_bus();

			//echo "<pre>";print_r($points);die;

			if(isset($points) && !empty($points))
			{
				foreach($points as $obj)
				{

				}
			}
			else
			{
				$res = json_encode(array("msg"=>"No record found."));
			}	
		}
		else
		{
			$res = json_encode(array("msg"=>"Please select input criteria for processing."));
		}


		echo $res;
	}



}//End of class
?>