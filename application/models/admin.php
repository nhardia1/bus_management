<?php
class Admin extends CI_Model 
{

	public function __construct()
	{		
	  	parent::__construct();
		
		$this->load->database();
		
		$this->table_name = 'users';
	}


	//Developer 1 ----------------------------------------------------------------------
	public function execute_sql($sql) 
	{
		$query = $this->db->query($sql);
		
		return $query->result();
	}

	
	public function get_city_list() 
	{
		
		$sql = "SELECT 
					id,name,stoppage,to_char(created_date, 'dd-mm-YYYY') as created_date
				FROM
					city
				WHERE 
					is_deleted = 0
				ORDER BY 
					created_date DESC";

		return $this->execute_sql($sql);	
	}

	
	public function count_function() 
	{
		
		$sql="SELECT count(id) as count FROM staff WHERE is_deleted = 0
		UNION ALL
		SELECT count(id)  as count FROM bus WHERE is_deleted = 0
		UNION ALL
		SELECT count(id)  as count FROM devices WHERE is_deleted = 0
		UNION ALL
		SELECT count(id)  as count FROM route WHERE is_deleted = 0
		";

		$query = $this->db->query($sql);
		
		return $query-> result_array();			
	}

	
	public function get_bus_validity() 
	{
		$curr_date=date('Y-m-d');
		$date = strtotime($curr_date);
		$date = strtotime("+30 day", $date);
		$last_date=date('Y-m-d', $date);
		
		$sql = "SELECT 
		id,( 
			SELECT 
			bus.name as bus_name
			FROM
			bus
			WHERE 
			bus.id = bus_route_validity.bus_id
			) as bus_name,
			( 
				SELECT 
				route.name as route_name
				FROM
				route
				WHERE 
				route.id = bus_route_validity.route_id
				) as route_name,valid_to
			FROM
			bus_route_validity
			WHERE 
			valid_to >= '$curr_date' and  valid_to <= '$last_date'	AND is_deleted = 0";


		$query = $this->db->query($sql);

		return $query-> result_array();	
	}

	
	public function get_city_details($city_id) 
	{
		$sql = "SELECT 
					id,name,stoppage
				FROM
					city
				WHERE 
					is_deleted = 0	AND id = $city_id
				ORDER BY 
					name";

		return $this->execute_sql($sql);	
	}


	public function get_route_details($route_ids) 
	{
		$sql = "SELECT 
					r.id, c.name as source, c1.name as destination
				FROM
					route r
				LEFT JOIN city c ON r.source_city = c.id
				LEFT JOIN city c1 ON r.destination_city = c1.id	
				WHERE 
					r.id IN ($route_ids)
				GROUP BY 
					r.id";

		$data = $this->execute_sql($sql);	

		$info = array();
		if(isset($data) && !empty($data))
		{
			foreach($data as $obj)
			{
				$info[$obj->id]['source'] = $obj->source;
				$info[$obj->id]['destination'] = $obj->destination;
			}
		}

		return $info;
	}


	public function password_update()
	{
		$cur_datetime=date(LAST_MODIFIED_DATE);

		$this->load->helper('url');
		
		$old_pass =  $this->input->post('oldpass');

		$new_pass =  $this->input->post('newpass');

		$cnewpass =  $this->input->post('cnewpass');

		$type = $this->input->post('type');

		$user_id = $this->input->post('user_id');

		if($old_pass==$new_pass)
		{
			$errors ='New password and old password should not be same.';
		}
		elseif($new_pass!=$cnewpass)
		{
			$errors ='New password and Confirm password not matches.';
		}
		else
		{
		
			$this->db->where('id =', $user_id);

			$this->db->where('is_deleted',0);

			$query = $this->db->get('users');

			if($query->num_rows() >0)
			{
				$this->db->where('id =', $user_id);

				$this->db->where('password =', md5($old_pass));

				$this->db->where('is_deleted',0);

				$query = $this->db->get('users');

				if($query->num_rows() >0)
				{

					$data = array('password'=>md5($new_pass));

					$this->db->where('id', $user_id);

					$this->db->set('last_modified_date', $cur_datetime);

					if($this->db->update('users', $data)=='1')
					{	

						$massage ='Password changed successfully.';	

					}
				}
				else 
				{

					$errors ='Old password does not match.';

				}
			}
			else
			{		
				$errors ='User does not exists.';

			}
		}		

		if (!empty($errors)) {
		  $data  = $errors;
		} else {
		  $data = $massage;
		}
		return $data;
		
	}
	
	public function profile_info()
	{
		$session = $this->session->userdata('user_details');

		$login_user_id = $session['id'];

		$sql = "SELECT 
					id,name,contact_number,dob,photo
				FROM
					users
				WHERE 
					is_deleted = 0	AND id = $login_user_id
				LIMIT 1";

		return $this->execute_sql($sql);
	}


	public function profile_save() 
	{
		$session = $this->session->userdata('user_details');
		
		$errors = array(); $data = array();
		$valid = true;

		if(empty($_POST['user_name']))
		{
	  		$errors = 'Name is required.';
	  		$valid = false;
		} 
		else if(empty($_POST['user_contact_number']))
		{
			$errors = 'Contact Number is required.';
			$valid = false;
		} 
		else if(empty($_POST['user_dob']))
		{
	  		$errors = 'Date of Birth is required.';
	  		$valid = false;
		}


		$image_path = $_POST['user_photo'];

		if(isset($_FILES['fileUpload']['name']) && !empty($_FILES['fileUpload']['name']))
		{
			$image_name = "fileUpload";
			
			$_FILES['fileUpload']['name'] = str_replace(" ", "-", $_FILES['fileUpload']['name']);
			$_FILES['fileUpload']['name'] = str_replace("_", "-", $_FILES['fileUpload']['name']);
			$config['file_name'] = time()."_".$_FILES['fileUpload']['name'];
			$image_path = $config['file_name'];
			$config['upload_path'] = USER_UPLOADS;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';

			$this->load->library('upload', $config);

			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload($image_name))
			{
				$errors = $this->upload->display_errors();
				$image_path = "";
				$valid = false;
			}
			else
			{
				$image_path = $config['file_name'];			
			}
			
		}

		
		if($valid == true)
		{
			$user_name = $_POST['user_name'];
			$user_contact_number = $_POST['user_contact_number'];
			$user_dob = $_POST['user_dob'];
			$created_by = $session['id'];
			$date = date(CREATED_DATE);
			
			if(isset($user_dob) && !empty($user_dob))
			{
				$user_dob = date(CHANGE_INTO_DATE_FORMAT,strtotime($user_dob));
			}


	       $sess_array = $this->session->userdata('user_details');
	       $sess_array['name'] = $user_name;
	       $sess_array['photo'] = $image_path;

	       $sess_array = $this->session->set_userdata('user_details',$sess_array);

			$sql = "UPDATE users SET name = '$user_name', contact_number = '$user_contact_number' , dob = '$user_dob', photo = '$image_path' WHERE id = $created_by LIMIT 1 ";

			$this->db->query($sql);
		}		
		

		if (!empty($errors)) 
		{
		  $data  = $errors;
		} 
		else 
		{
		  $data = 'Profile has been updated successfully.';
		}
		
		return $data;
		
	}



	public function get_today_scheduled_buses($forday) 
	{
		$date = date(CHANGE_INTO_DATE_FORMAT);

		if(isset($forday) && $forday!=0)
		{
	  		$date = date(CHANGE_INTO_DATE_FORMAT,strtotime(" $forday DAY".$date));
		}
		

		$sql = "SELECT * FROM (
		SELECT bt.bus_id,bt.route_id,bt.city_id,bt.departure_date,bt.departure_hour,bt.departure_minute,bt.departure_am_pm,bt.trip_type,bt.trip_num,b.name as bus_name,r.name as route_name,b.bus_number 
				FROM
					bus_timing bt
				LEFT JOIN bus b ON bt.bus_id = b.id
				LEFT JOIN route r ON bt.route_id = r.id
				LEFT JOIN city c ON bt.city_id = c.id	
				WHERE 
					bt.is_deleted = 0 AND bt.journey_date = '$date' AND b.is_deleted = 0 AND r.is_deleted = 0 AND bt.city_id = r.source_city AND bt.trip_type = 1 AND bt.trip_num IN (1,2,3,4,5)
		UNION ALL
		SELECT bt.bus_id,bt.route_id,bt.city_id,bt.departure_date,bt.departure_hour,bt.departure_minute,bt.departure_am_pm,bt.trip_type,bt.trip_num,b.name as bus_name,r.name as route_name,b.bus_number  
				FROM
					bus_timing bt
				LEFT JOIN bus b ON bt.bus_id = b.id
				LEFT JOIN route r ON bt.route_id = r.id
				LEFT JOIN city c ON bt.city_id = c.id	
				WHERE 
					bt.is_deleted = 0 AND bt.journey_date = '$date' AND b.is_deleted = 0 AND r.is_deleted = 0 AND bt.city_id = r.destination_city AND bt.trip_type = 2 AND bt.trip_num IN (1,2,3,4,5)
		) as temp";
	
		//echo $sql;

		return $this->execute_sql($sql);	
	}
	

	public function get_buses_status($fordate) 
	{
		$date = date(CHANGE_INTO_DATE_FORMAT);

		if(isset($fordate) && !empty($fordate) && $fordate!="0000-00-00")
		{
	  		$date = date(CHANGE_INTO_DATE_FORMAT,strtotime($fordate));
		}
		

		$sql = "SELECT * FROM (
		SELECT bt.bus_id,bt.route_id,bt.for_date,bt.lattitude,bt.longitude,bt.trip_num,bt.message,bt.message_details,bt.image_name,b.name as bus_name,r.name as route_name,b.bus_number,c.name as source, c1.name as destination
				FROM
					running_status bt
				LEFT JOIN bus b ON bt.bus_id = b.id
				LEFT JOIN route r ON bt.route_id = r.id
				LEFT JOIN city c ON c.id = r.source_city
				LEFT JOIN city c1 ON c1.id = r.destination_city
				WHERE 
					bt.is_deleted = 0 AND bt.for_date = '$date' AND b.is_deleted = 0 AND r.is_deleted = 0 AND bt.trip_num>0
		) as temp";
	
		
		return $this->execute_sql($sql);	
	}



	public function get_buses_yearly_report($foryear) 
	{
		$year = date("Y");

		if(isset($foryear) && !empty($foryear))
		{
	  		$year = date("Y",strtotime($foryear));
		}
		

		$sql = "SELECT bus_id, route_id, date(fordate) as fordate 
				FROM num_of_passenger
				WHERE YEAR(fordate) = '$year' AND route_id IN (SELECT id FROm route WHERE is_deleted = 0) AND bus_id IN (SELECT id FROm bus WHERE is_deleted = 0)";
	
		$list = array();		
		
		$result = $this->execute_sql($sql);

		if(isset($result) && !empty($result))
		{
			$sno = 0;
			
			foreach($result as $key => $obj)
			{	
				$sno++;

				$bus_id = $obj->bus_id;

				$route_id = $obj->route_id;
				
				$formonth = date("m",strtotime($obj->fordate));
				
				$count = $this->calculate_passenger_and_amount($bus_id,$route_id,$formonth,$foryear);				

				$list[$bus_id][$route_id][$formonth] = $count;
			}
		}

		return $list;
	}


	function calculate_passenger_and_amount($bus_id,$route_id,$formonth,$foryear)
	{
		$total_pass = 0;
		$total_amt = 0;

		$sql = "SELECT nop.from_city, nop.to_city, nop.count
				FROM num_of_passenger nop
				WHERE nop.bus_id = '$bus_id' AND nop.route_id = '$route_id' AND MONTH(nop.fordate) = '$formonth' AND YEAR(nop.fordate) = '$foryear' AND nop.trip_type > 0";
				
		$result = $this->execute_sql($sql);

		if(isset($result) && !empty($result))
		{			
			foreach($result as $obj)				
			{
				$from = $obj->from_city;
				$to = $obj->to_city;
				$count = $obj->count;

				$total_pass = $total_pass + $count;

				$sql = "SELECT rf.fare
				FROM route_fare rf 
				WHERE rf.route_id = '$route_id' AND ( (rf.from_city = '$from' AND rf.to_city = '$to') OR (rf.from_city = '$to' AND rf.to_city = '$from') ) AND rf.fare != '' AND rf.fare>0
				LIMIT 1";				

				$result1 = $this->execute_sql($sql);

				if(isset($result1) && !empty($result1))
				{	
					$fare = $result1[0]->fare;

					$total_amt = $total_amt + ($count * $fare);	
				}	
			}
		}

		return "$total_pass#$total_amt";	
	}


}
?>