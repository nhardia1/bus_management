<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tracking extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('session');
		
		$this->load->model('custom_model');

		$this->load->model('tracking_model');

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

		$data['bus_list'] = $this->tracking_model->bus_list();
		
		$this->load->view('header');

		$this->load->view('tracking',$data);

		$this->load->view('footer');
	}


	public function max_trip()
	{
		$this->custom_model->check_login_session();

		$data['bus_list'] = $this->tracking_model->bus_list();
		
		$this->load->view('header');

		$this->load->view('tracking',$data);

		$this->load->view('footer');
	}

	

	public function tracking_bus()
	{
		$this->custom_model->check_login_session();
		
		$info = array();

		if(isset($_POST['bus_loc_bus_name']) && !empty($_POST['bus_loc_bus_name']))
		{
			$bus_id = $_POST['bus_loc_bus_name'];

			//$trip_type = $_POST['bus_loc_trip_type'];

			$data = $this->tracking_model->tracking_bus();

			if(isset($data['location']) && !empty($data['location']) && isset($data['route']) && !empty($data['route']))
			{
				$trip_num = 0;
				
				$info['location']['trip_num'] = $trip_num;

				foreach($data['location'] as $lkey => $obj) 
				{
					$info['location']['trip_num'] = $obj->trip_num;

					$trip_num = $info['location']['trip_num'];

					if(isset($obj->lattitude) && !empty($obj->lattitude) && $obj->lattitude>0 && isset($obj->longitude) && !empty($obj->longitude) && $obj->longitude>0)
					{					

						//$info['location']['titletxt'] = $this->getaddress($obj->lattitude,$obj->longitude);

						//if($info['location']['titletxt']!=false && $info['location']['titletxt']!="")
						//{
							$info['location']['lattitude'] = $obj->lattitude;

							$info['location']['longitude'] = $obj->longitude;

							break;
						//}					
					}
				}

				$summary = "";
				if($trip_num > 0)
				{
					$summary .= "<table width='100%'><tr><td><b>Passengers Count:</b></td></tr>";

					foreach($data['route'] as $obj) 
					{					
						$info['route']['route_id'] = $obj->id;

						$info['route']['source_city'] = $obj->source_city;
						$info['route']['slat'] = $obj->slat;
						$info['route']['slon'] = $obj->slon;
						
						$info['route']['destination_city'] = $obj->destination_city;
						$info['route']['dlat'] = $obj->dlat;
						$info['route']['dlon'] = $obj->dlon;

						
						if($trip_num % 2 == 0)
						{
							$info['route']['destination'] = $this->tracking_model->get_num_of_passenger($bus_id,$obj->id,$obj->destination_city,$obj->destination,$trip_num);

							$info['route']['sourcename'] = $obj->source;

							$summ = explode("</b><br/>", $info['route']['destination']);
							if($summ[1]!="No record found.")
							$summary .= "<tr><td>".$summ[1]."</td></tr>";

							//$summary .= "<tr><td>".$info['route']['destination']."</td></tr>";
							//$summary .= "<tr><td><hr /></td></tr>";
						}
						else
						{
							$info['route']['sourcename'] = $this->tracking_model->get_num_of_passenger($bus_id,$obj->id,$obj->source_city,$obj->source,$trip_num);

							$info['route']['destination'] = $obj->destination;

							$summ = explode("</b><br/>", $info['route']['sourcename']);
							if($summ[1]!="No record found.")
							$summary .= "<tr><td>".$summ[1]."</td></tr>";
							
							//$summary .= "<tr><td>".$info['route']['sourcename']."</td></tr>";
							//$summary .= "<tr><td><hr /></td></tr>";
						}	
					}

				
				
					foreach($data['stoppage'] as $k => $obj) 
					{
						$info['stoppage'][$k]['city_id'] = $obj->city_id;
						$info['stoppage'][$k]['lat'] = $obj->lat;
						$info['stoppage'][$k]['lon'] = $obj->lon;

						$info['stoppage'][$k]['stp'] = $this->tracking_model->get_num_of_passenger($bus_id,$info['route']['route_id'],$obj->city_id,$obj->stoppage,$trip_num,'stoppage');

						$summ = explode("</b><br/>", $info['stoppage'][$k]['stp']);
						if($summ[1]!="No record found.")
						$summary .= "<tr><td>".$summ[1]."</td></tr>";
						
						//$summary .= "<tr><td>".$info['stoppage'][$k]['stp']."</td></tr>";
						//$summary .= "<tr><td><hr /></td></tr>";
					}	
					
					
					$info['summary'] = $summary;
					$info['msg'] = "success";
					//echo "<pre>";print_r($info);die;
				}
				else
				{
					$info['msg'] = $this->lang->line("no_record_found"); //"No record found.";
				}	
			}
			else
			{
				$info['msg'] = $this->lang->line("no_record_found"); //"No record found.";
			}
				
		}
		

		echo json_encode($info);
		
	}

	function getaddress($lat,$lng)
	{
		$url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false';
		$json = @file_get_contents($url);
		$data=json_decode($json);
		$status = $data->status;
		if($status=="OK")
			return $data->results[0]->formatted_address;
		else
			return false;
	}

	public function get_num_of_passenger()
	{
		$this->custom_model->check_login_session();
		$result='';
		if(isset($_POST['bus_id']) && !empty($_POST['bus_id']) && isset($_POST['route_id']) && !empty($_POST['route_id']))
		{	

			$points = $this->tracking_model->get_num_of_passenger();			

			if(isset($points) && !empty($points))
			{

				$result = json_encode($points);
				
			}
			else
			{
				$result = json_encode(array("msg"=> $this->lang->line("no_record_found") //"No record found."
					));
			}	
		}
		else
		{
			$result = json_encode(array("msg"=> $this->lang->line("msg_bus_route_not_define")));

		}


		echo $result;
	}


}//End of class
?>