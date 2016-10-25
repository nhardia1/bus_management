<?php
class Device_assign_model extends CI_Model 
{

	public function __construct()
	{
	  	parent::__construct();
		
		$this->load->database();
	}


	public function execute_sql($sql) 
	{
		$query = $this->db->query($sql);
		
		return $query->result();
	}


	
	public function get_all_device() 
	{
		$sql="select id,name FROM devices where is_deleted=0";

		return $this->execute_sql($sql);
	}

	
	public function get_assign_device_detail($bid,$did)
	{
		$data = array();
		$data1 = array();
		$data2 = array();

		$data['fordates'] = "";
		$data['bus_id'] = "";
		$data['device_id'] = "";
		$data['bus_name'] = "";
		$data['device_name'] = "";

		
		$sql = "SELECT CONCAT('\"',GROUP_CONCAT(DATE_FORMAT(fordate,'%d-%m-%Y') SEPARATOR '\",\"'),'\"') as dates FROM device_assigned WHERE bus_id = $bid AND device_id = $did AND is_deleted = 0";
		
		$data1 = $this->execute_sql($sql);

		if(isset($data1) && !empty($data1))
		{
			$data['fordates'] = $data1[0] -> dates;
		}
				

		$sql = "SELECT 
					da.bus_id,da.device_id,b.name as bus_name,d.name as device_name
				FROM 
					device_assigned da 
				LEFT JOIN devices d ON da.device_id = d.id
				LEFT JOIN bus b ON da.bus_id = b.id
				WHERE 
					da.bus_id = $bid AND da.device_id = $did AND da.is_deleted = 0 LIMIT 1";
		
		$data2 = $this->execute_sql($sql);		
		
		if(isset($data2) && !empty($data2))
		{
			$data['bus_id'] = $data2[0] -> bus_id;
			$data['device_id'] = $data2[0] -> device_id;
			$data['bus_name'] = $data2[0] -> bus_name;
			$data['device_name'] = $data2[0] -> device_name;
		}
				

		return $data;	
	}


	public function assigned()
	{
		
		$sql="SELECT da.id,da.bus_id,da.device_id,b.name,d.name as 'device_name',da.fordate 
		FROM device_assigned da
		INNER JOIN bus b ON da.bus_id=b.id 
		INNER JOIN devices d ON da.device_id=d.id
		WHERE b.is_deleted=0 AND d.is_deleted=0 AND da.is_deleted=0";
		
		return $this->execute_sql($sql);
	
	}
	

	public function get_all_bus() 
	{
		$sql="SELECT id as bus_id,name,bus_number 
				FROM bus 
				WHERE is_deleted = 0
				ORDER BY name,bus_number
				";

		return $this->execute_sql($sql);	
	}


	
	public function insert() 
	{
		$session = $this->session->userdata('user_details');
		$errors = '';
		$data = array();
		
		if(!isset($_POST['bus_name']) || $_POST['bus_name'] == "")
		{
	  		$errors = 'Bus name is required.';
		}

		if(!isset($_POST['bus_device']) || $_POST['bus_device'] == "")
		{

	  		$errors = 'Device name is required.';
		}

		if(!isset($_POST['assign_date']) || $_POST['assign_date'] == "")
		{

	  		$errors = 'Date is required.';
		}

		if($errors == "" && !empty($_POST['bus_name']) && !empty($_POST['bus_device']) && !empty($_POST['assign_date']))
		{			
			$user_id = $session['id'];
			$bus_id = $_POST['bus_name'];
			$assign_date = $_POST['assign_date'];
			$created_date = date(CREATED_DATE);
			$device = $_POST['bus_device'];

			if($assign_date!="")
			{
				//Remove old records
				$sql1 = "DELETE FROM device_assigned WHERE device_id = $device AND bus_id = '$bus_id'";
				$this->db->query($sql1);


				$assign_date_arr = explode(",", $assign_date);

				$c = 0;
				foreach($assign_date_arr as $date)
				{
					$chk1 = false;
					$chk2 = false;

					$date = date(CHANGE_INTO_DATE_FORMAT,strtotime($date));

					$chk1 = $this->chk_device_assigned($device,$date);					

					if($chk1 == true)
					{
						$sql = "INSERT INTO device_assigned(bus_id,device_id,fordate,created_by,created_date,last_modified_date) 
						VALUES('$bus_id','$device','$date','$user_id','$created_date','$created_date')";

						$this->db->query($sql);
					}
					else
					{
						$c++;
					}
				}

				if($c==1)
				{
					$errors = "Device is already assigned for selected date.";
				}
				elseif($c>1)
				{
					$errors = "Device has been assigned successfully ( for some selected dates it is assigned already ).";
				}				
			}			
		}

		if (!empty($errors)) 
		{
		  $data  = $errors;
		} 
		else 
		{
		  $data = 'Device has been assigned successfully.';
		}
		
		return $data;
		
	}


	public function update() 
	{
		$session = $this->session->userdata('user_details');
		$errors = '';
		$data = array();
		
		if(!isset($_POST['bus_name']) || $_POST['bus_name'] == "")
		{
	  		$errors = 'Bus name is required.';
		}

		if(!isset($_POST['bus_device']) || $_POST['bus_device'] == "")
		{

	  		$errors = 'Device name is required.';
		}

		if(!isset($_POST['assign_date']) || $_POST['assign_date'] == "")
		{

	  		$errors = 'Date is required.';
		}

		if($errors == "" && !empty($_POST['bus_name']) && !empty($_POST['bus_device']) && !empty($_POST['assign_date']))
		{			
			$user_id = $session['id'];
			$bus_id = $_POST['bus_name'];
			$assign_date = $_POST['assign_date'];
			$created_date = date(CREATED_DATE);
			$device = $_POST['bus_device'];

			//Remove old records
			$sql = "DELETE FROM device_assigned WHERE device_id = $device AND bus_id = '$bus_id'";
			$this->db->query($sql);

			if($assign_date!="")
			{
				$assign_date_arr = explode(",", $assign_date);

				$c = 0;
				
				foreach($assign_date_arr as $date)
				{
					$chk1 = false;
					$chk2 = false;

					$date = date(CHANGE_INTO_DATE_FORMAT,strtotime($date));

					$chk1 = $this->chk_device_assigned($device,$date);					

					if($chk1 == true)
					{
						$sql = "INSERT INTO device_assigned(bus_id,device_id,fordate,created_by,created_date,last_modified_date) 
						VALUES('$bus_id','$device','$date','$user_id','$created_date','$created_date')";

						$this->db->query($sql);
					}
					else
					{
						$c++;
					}
				}

				if($c==1)
				{
					$errors = "Device is already assigned for selected date.";
				}
				elseif($c>1)
				{
					$errors = "Device has been assigned successfully ( for some selected dates it is assigned already ).";
				}				
			}			
		}

		if (!empty($errors)) 
		{
		  $data  = $errors;
		} 
		else 
		{
		  $data = 'Device has been assigned successfully.';
		}
		
		return $data;
		
	}

	function chk_device_assigned($device,$date,$bus="")
	{
		if($bus!="")
		{
			$bus = "AND bus_id != $bus";
		}

		$sql = "SELECT id FROM device_assigned WHERE is_deleted = 0 AND device_id = $device AND fordate = '$date' $bus LIMIT 1";

		$arr = $this->execute_sql($sql);

		if(isset($arr) && !empty($arr))
		{
			return false;
		}
		else
		{
			return true;
		}

	}


	
	public function delete($id) 
	{
		$session = $this->session->userdata('user_details');
		$login_user_id=$session['id'];
		
		$errors = array();
		$data = array();
		
		
		$this->db->set('is_deleted', 1);
		$this->db->set('last_modified_by', $login_user_id);
		$this->db->set('last_modified_date', date(LAST_MODIFIED_DATE));
		$this->db->where("id =",$id);
				
		echo "<script>alert('Device has been deassign successfully.');</script>";
		
		redirect('device_assign', 'refresh');
	}


	public function get_device_date()
	{
		$bid=$_POST['bus_name'];
		$did=$_POST['bus_device'];
		$data = array();
		$data1 = array();
		$data2 = array();

		$data['fordates'] = "";		

		
		$sql = "SELECT CONCAT(GROUP_CONCAT(DATE_FORMAT(fordate,'%d-%m-%Y'))) as dates FROM device_assigned WHERE bus_id = $bid AND device_id = $did AND is_deleted = 0";

		//$sql = "SELECT CONCAT('\"',GROUP_CONCAT(DATE_FORMAT(fordate,'%d-%m-%Y') SEPARATOR '\",\"'),'\"') as dates FROM device_assigned WHERE bus_id = $bid AND device_id = $did AND is_deleted = 0";
		
		$data1 = $this->execute_sql($sql);

		if(isset($data1) && !empty($data1))
		{
			$data = $data1[0] -> dates;

			return $data;
		}

		return $data;	
	}


}
?>