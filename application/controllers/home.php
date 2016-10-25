<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller 
{

	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('custom_model');
		$this->load->model('admin');
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

		$user = $this->session->userdata('user_details');

		$data['count_data'] = $this->admin->count_function('bus');

		$data['bus_validity_data'] = $this->admin->get_bus_validity();		
				
		$page_title['page_title']= $this->lang->line("dashboard"); //'Dashboard';

		$this->load->view('header',$page_title);
		$this->load->view('home',$data);
		$this->load->view('footer');
	}

	public function set_view_type($typ="")
	{
		$this->custom_model->check_login_session();

		if($typ != "" && $typ > 0)
		{
			$this->session->set_userdata('view_type',$typ);
		}
		else
		{
			$this->session->set_userdata('view_type',1);
		}	
	}

	public function change_pass()
	{
		
	 		/////////Session Check Start /////////
		$this->custom_model->check_login_session();
	 	////////Session Check End ////////////
		
		
			
			$this->load->helper('url');
			$this->load->helper('form');
			
			$session_data = $this->session->userdata('user_details');
			
			$page_title['page_title']= $this->lang->line("change").' '.$this->lang->line("password"); //'Change Password';
			
			$data['type'] = $session_data['type'];
			$data['id'] = $session_data['id'];

			$this->load->view('header',$page_title);
			$this->load->view('change_pass',$data);
			$this->load->view('footer');
		
	}
	 // function is use to change the password of Admin
	public function password_update()
	{
		$this->custom_model->check_login_session();

		print $data = $this->admin->password_update();
	}

	public function profile_edit()
	{
		$this->custom_model->check_login_session();
		
		$data['profile_info'] = $this->admin->profile_info();

		$page_title['page_title']= $this->lang->line("edit").' '.$this->lang->line("user");//'Edit User Profile';

		$this->load->view('header',$page_title);
		$this->load->view('user_profile_edit',$data);
		$this->load->view('footer');
	}

	public function profile_save()
	{
		$this->custom_model->check_login_session();

		print $data = $this->admin->profile_save();
	}



	public function get_today_scheduled_buses($forday=0)
	{
		$this->custom_model->check_login_session();
		
		$records = "<tr><td colspan='6' align='center'>".$this->lang->line("no_record_found")."</td></tr>";

		$data = $this->admin->get_today_scheduled_buses($forday);

		$today_date = date(CHANGE_INTO_DATE_FORMAT);

		if(isset($forday) && $forday!=0)
		{
	  		$today_date = date(CHANGE_INTO_DATE_FORMAT,strtotime(" $forday DAY".$today_date));	  		
		}

		if(isset($data) && !empty($data))
		{	
			$temp = array();
			$routes = array();
			$route_ids = "";

			foreach($data as $obj)
			{
				$routes[] = $obj->route_id;
			}

			$route_ids = implode(",", $routes);	

			if($route_ids!="")
			{	
				$routes = $this->admin->get_route_details($route_ids);
			}
			else
			{
				$routes = array();
			}	

			foreach($data as $obj)
			{
				$temp[$obj->bus_id][$obj->route_id][$obj->trip_num]['name'] = $obj->bus_name;
				
				if(isset($obj->bus_number) && !empty($obj->bus_number))
				{
					$temp[$obj->bus_id][$obj->route_id][$obj->trip_num]['name'] = $temp[$obj->bus_id][$obj->route_id][$obj->trip_num]['name']."<span class='help_txt'>&nbsp;(".$obj->bus_number.")</span>";
				}


				$temp[$obj->bus_id][$obj->route_id][$obj->trip_num]['route'] = $obj->route_name;

				if($obj->trip_type == 1 || $obj->trip_type == 3 || $obj->trip_type == 5 || $obj->trip_type == 7  || $obj->trip_type == 9)
				{
					$temp[$obj->bus_id][$obj->route_id][$obj->trip_num]['source'] = $routes[$obj->route_id]['source'];

					$input = "departure";

				}
				else
				{
					$temp[$obj->bus_id][$obj->route_id][$obj->trip_num]['destination'] = $routes[$obj->route_id]['destination'];

					$input = "arrival";
				}

				if(isset($obj->departure_date) && !empty($obj->departure_date) && $obj->departure_date!="0000-00-00")
				{
					$temp[$obj->bus_id][$obj->route_id][$obj->trip_num][$input] = date(DISPLAY_DATE_STRING,strtotime($obj->departure_date));

					if($obj->departure_hour!="" && $obj->departure_minute!="")
					{
						if($obj->departure_hour<9)
						{
							$h = "0".$obj->departure_hour;
						}
						else
						{
							$h = $obj->departure_hour;
						}

						if($obj->departure_minute<9)
						{
							$m = "0".$obj->departure_minute;
						}
						else
						{
							$m = $obj->departure_minute;
						}  

						$temp[$obj->bus_id][$obj->route_id][$obj->trip_num][$input] = $temp[$obj->bus_id][$obj->route_id][$obj->trip_num][$input]." ".$h.":".$m." ".$obj->departure_am_pm;
					}	
				}
				else//if($today_date >= date(CHANGE_INTO_DATE_FORMAT))
				{
					$today_date = date("d-m-Y",strtotime($today_date));

					$temp[$obj->bus_id][$obj->route_id][$obj->trip_num][$input] = "<a target='_blank' href='".base_url()."index.php/route/manage/".$obj->bus_id."/".$obj->route_id."/$today_date'>Click here to set time</a>";
				}
				/*else
				{
					$temp[$obj->bus_id][$obj->route_id][$obj->trip_num][$input] = "Not set";
				}*/
			}

			//echo "<pre>";print_r($temp);die;
			if(isset($temp) && !empty($temp))
			{
				$records = "";
				$sno = 0;
				foreach($temp as $bus_id => $outer)
				{					
					foreach($outer as $route_id => $arr1)
					{				

						foreach($arr1 as $trip_num => $arr)
						{
							$records .= "<tr>";

							$sno++;
							
							$records .= "<td>".$sno."</td>";
							
							$records .= "<td>".ucfirst($arr['name'])."</td>";						

							$records .= "<td>".ucfirst($arr['route'])."</td>";

							$records .= "<td>Trip $trip_num</td>";
							
							$s = "";$d = "";$dis1 = ""; $dis2 = "";
							$s = $arr['source'];
							$d = $arr['destination'];

							if($s!="" && $d!="")
							{	//<i class='imd imd-navigate-next'></i>
								$dis1 = "&nbsp;<span class='help_txt'>(From <label class='from_help_text'>".$s."</label>&nbsp;To&nbsp;<label class='to_help_text'>".$d."</label>)</span>";
							
								$dis2 = "&nbsp;<span class='help_txt'>(From <label class='from_help_text'>".$d."</label>&nbsp;To&nbsp;<label class='to_help_text'>".$s."</label>)</span>";
							}
							
							$records .= "<td>".$arr['departure']."$dis1</td>";
							
							$records .= "<td>".$arr['arrival']."$dis2</td>";
						
							$records .= "</tr>";
						}

						
					}

					
				}
			}
				

		}

		$temp = array();
		$data = array();
		
		echo $records;
		
	}	



	public function get_buses_status()
	{
		$this->custom_model->check_login_session();
		
		$records = "<tr><td colspan='6' align='center'>".$this->lang->line("no_record_found")."</td></tr>";

		$fordate = $_POST['fordate'];

		$data = $this->admin->get_buses_status($fordate);

		if(isset($data) && !empty($data))
		{	
			$sno = 0;
			$records = "";
			
			foreach($data as $obj)
			{
				$records .= "<tr>";
				
				$sno++;
				
				$name = $obj->bus_name;
				if(isset($obj->bus_number) && !empty($obj->bus_number))
				{
					$name = $name."<span class='help_txt'>&nbsp;(".$obj->bus_number.")</span>";
				}

				
				$trip_num = $obj->trip_num;
				if($trip_num % 2 == 1)
				{
					$from = ucfirst($obj->source);
					$to = ucfirst($obj->destination);
				}
				else
				{
					$from = ucfirst($obj->source);
					$to = ucfirst($obj->destination);
				}


				if($trip_num == 1 || $trip_num == 2)
				{
					$trip_dis = "Trip 1";
				}
				elseif($trip_num == 3 || $trip_num == 4)
				{
					$trip_dis = "Trip 2";
				}
				elseif($trip_num == 5 || $trip_num == 6)
				{
					$trip_dis = "Trip 3";
				}
				elseif($trip_num == 7 || $trip_num == 8)
				{
					$trip_dis = "Trip 4";
				}

				$records .= "<td>".$sno."</td>";
				
				$records .= "<td>".ucfirst($name)."</td>";

				$records .= "<td>".$trip_dis."</td>";
				
				$records .= "<td><label class='from_help_text'>".ucfirst($from)."</label></td>";
				
				$records .= "<td><label class='to_help_text'>".ucfirst($to)."</label></td>";
				
				$records .= '<td><a href="#" data-toggle="modal" class="green_count" data-target="#statusModal_'.$sno.'" ><u>'.$obj->message.'</u></a>';

				if($obj->message_details != "" || $obj->image_name != "")
				{
					$records .= '<div class="modal fade" id="statusModal_'.$sno.'" role="dialog">
					<div class="modal-dialog" style="width:900 !important;">
						<!-- Modal content-->
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">'.$obj->message.'</h4>
							</div>
							<div class="modal-body bus-img">';

							if($obj->image_name != "")
							{
								$records .= '<img src="'.base_url().'sync/upload/status/'.$obj->image_name.'" />';
							}

							if($obj->message_details != "")
							{
								$records .= '<p>'.$obj->message_details.'</p>';
							}

							$records .= '</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">'.$this->lang->line("cancel").'</button>
							</div>
						</div>
						</div>
					</div>';
				}	

				$records .= '</td>';
				
				$records .= "</tr>";
			}
		}

		$data = array();
		
		echo $records;
	}	


	public function get_buses_yearly_report()
	{
		$this->custom_model->check_login_session();
		
		$records = "<tr><td colspan='16' align='center'>".$this->lang->line("no_record_found")."</td></tr>";

		$foryear = $_POST['foryear'];

		$data = $this->admin->get_buses_yearly_report($foryear);

		//echo "<pre>";print_r($data);

		if(isset($data) && !empty($data))
		{	
			$sno = 0;
			
			$records = "";
			
			$this->load->model('reports_model');

			foreach($data as $bus_id => $arr)
			{				
				foreach($arr as $route_id => $montharr)
				{
					$sno++;

					$total_pass = 0;
					$total_amt = 0;

					$records .= "<tr>";
					
					$name = $this->reports_model->bus_name($bus_id);

					$routename = $this->reports_model->route_name($route_id);

					$records .= "<td rowspan=2>".$sno."</td>";
				
					$records .= "<td rowspan=2>".$name."</td>";

					$records .= "<td rowspan=2>".$routename."</td>";

					$records .= "<td class='from_help_text'><i class='imd imd-account-child'></i></td>";

					for($i=1;$i<=12;$i++)
					{
						if($i<=9) { $i = "0$i"; }

						$count = $montharr[$i];

						$count_arr = explode("#",$count);
						
						$pass = $count_arr[0];
						if($pass<=0) $pass = "-";

						$records .= "<td class='from_help_text'>".$pass."</td>";

						$total_pass = $total_pass + $pass;					
					}

					$records .= "<td class='pass_subtotal'>".$total_pass."</td>";
			
					$records .= "</tr><tr><td class='to_help_text'><i class='imd imd-attach-money'></i></td>";
					
					for($i=1;$i<=12;$i++)
					{
						if($i<=9) { $i = "0$i"; }

						$count = $montharr[$i];

						$count_arr = explode("#",$count);
						
						$amt = $count_arr[1];
						if($amt<=0) $amt = "-";						

						$records .= "<td class='to_help_text'>".$amt."</td>";

						$total_amt = $total_amt + $amt;						
					}

					$records .= "<td class='amt_subtotal'>".$total_amt." Rs/-</td>";

					$records .= "</tr>";
				}	
			}
		}

		$data = array();
		
		echo $records;
	}	



}
?>