<?php
class Staff_assign_model extends CI_Model 
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


	
	public function get_all_staff() 
	{
		$sql="select id,name,staff_type FROM staff where is_deleted=0";

		return $this->execute_sql($sql);
	}

	
	public function get_assign_staff_detail($bid,$drivid,$conducid,$operatorid)
	{
		$data = array();
		$data1 = array();
		$data2 = array();

		$data['fordates'] = "";
		$data['bus_id'] = "";
		$data['driver'] = "";
		$data['bus_name'] = "";
		$data['staff_name'] = "";
		$fordate='';

		$append = "";
		if($operatorid!="" && $operatorid!=0)
		{
			$append = " AND operator=$operatorid";
		}

		$sql = "SELECT CONCAT('\"',GROUP_CONCAT(DATE_FORMAT(fordate,'%d-%m-%Y') SEPARATOR '\",\"'),'\"') as dates FROM staff_assigned WHERE bus_id = $bid AND driver = $drivid AND conductor=$conducid AND is_deleted = 0 $append";
		
		$data1 = $this->execute_sql($sql);

		if(isset($data1) && !empty($data1))
		{
			$data['fordates'] = $data1[0] -> dates;
		}

		$sql="SELECT  id,bus_id,driver,conductor,operator,
		(
	        SELECT   staff.name As driver_name
	        FROM    staff
	        WHERE   staff.id = staff_assigned.driver
	        ) As driver_name,
		(
	        SELECT  staff.name As conductor_name
	        FROM    staff
	        WHERE   staff.id = staff_assigned.conductor
	        ) As conductor_name,
		(
	        SELECT  staff.name As operator_name
	        FROM    staff
	        WHERE   staff.id = staff_assigned.operator
	        ) As operator_name,
		
		(
	        SELECT  bus.name As name
	        FROM    bus
	        WHERE   bus.id = staff_assigned.bus_id
	        ) As name

		FROM staff_assigned 
		
		
		WHERE is_deleted=0 AND bus_id=$bid AND driver=$drivid AND conductor=$conducid $append";

		

		$data2 = $this->execute_sql($sql);		
		
		if(isset($data2) && !empty($data2))
		{

			
			$data['id'] = $data2[0] -> id;
			$data['bus_id'] = $data2[0] -> bus_id;
			$data['name'] = $data2[0] -> name;
			$data['driver_name'] = $data2[0] -> driver_name;
			$data['driver_id'] = $data2[0] -> driver;
			$data['conductor_name'] = $data2[0] -> conductor_name;
			$data['conductor_id'] = $data2[0] -> conductor;
			$data['operator_name'] = $data2[0] -> operator_name;
			$data['operator_id'] = $data2[0] -> operator;
			$data['staff_name'] = $data2[0] -> staff_name;
			
		}
				

		return $data;	
	}


	public function assigned()
	{
		
		

		$sql="SELECT  id,bus_id,driver,conductor,operator,
		(
	        SELECT   staff.name As driver_name
	        FROM    staff
	        WHERE   staff.id = staff_assigned.driver
	        ) As driver_name,
		(
	        SELECT  staff.name As conductor_name
	        FROM    staff
	        WHERE   staff.id = staff_assigned.conductor
	        ) As conductor_name,
	    (
	        SELECT  staff.name As operator_name
	        FROM    staff
	        WHERE   staff.id = staff_assigned.operator
	        ) As operator_name,    
		(
	        SELECT  bus.name As name
	        FROM    bus
	        WHERE   bus.id = staff_assigned.bus_id
	        ) As name,

		other,fordate
		FROM staff_assigned 
		
		
		WHERE is_deleted=0 ";
		
		return $this->execute_sql($sql);
	
	}
	

	public function get_all_bus() 
	{
		$sql="select id as bus_id, name, bus_number FROM bus where is_deleted=0 order by name";

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
		elseif(!isset($_POST['conductor_name']) || $_POST['conductor_name'] == "")
		{
	  		$errors = 'Conductor name is required.';
		}
		elseif(!isset($_POST['driver_name']) || $_POST['driver_name'] == "")
		{

	  		$errors = 'Driver name is required.';
		}
		elseif(!isset($_POST['assign_date']) || $_POST['assign_date'] == "")
		{

	  		$errors = 'Date is required.';
		}


		if($errors == "" && !empty($_POST['bus_name']) && !empty($_POST['conductor_name']) && !empty($_POST['driver_name']) && !empty($_POST['assign_date']))
		{			
			$user_id = $session['id'];
			$bus_id = $_POST['bus_name'];
			$assign_date = $_POST['assign_date'];
			$created_date = date(CREATED_DATE);
			$conductor_id = $_POST['conductor_name'];
			$driver_id = $_POST['driver_name'];
			$operator_id=$_POST['operator_name'];
			$helper_id = $_POST['helper_name'];
			$other_id = $_POST['other_name'];
			
			if($assign_date!="")
			{
				$assign_date_arr = explode(",", $assign_date);

				$c = 0;
				foreach($assign_date_arr as $date)
				{
					$chk1 = false;
					$chk2 = false;

					$date = date(CHANGE_INTO_DATE_FORMAT,strtotime($date));

					
					$chk1 = $this->chk_staff_assigned($driver_id,$conductor_id,$operator_id,$date);					

					if($chk1 == true)
					{
						$sql = "INSERT INTO staff_assigned(bus_id,driver,conductor,operator,fordate,created_by,created_date,last_modified_date) 
						VALUES('$bus_id','$driver_id','$conductor_id','$operator_id','$date','$user_id','$created_date','$created_date')";

						$this->db->query($sql);
					}
					else
					{
						$c++;
					}
				}

				if($c==1)
				{
					$errors = "Staff is already assigned for selected date.";
				}
				elseif($c>1)
				{
					$errors = "Staff has been assigned successfully ( for some selected dates it is assigned already ).";
				}				
			}			
		}

		if (!empty($errors)) 
		{
		  $data  = $errors;
		} 
		else 
		{
		  $data = 'Staff has been assigned successfully.';
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
		elseif(!isset($_POST['driver_name']) || $_POST['driver_name'] == "")
		{

	  		$errors = 'Driver name is required.';
		}
		elseif(!isset($_POST['conductor_name']) || $_POST['conductor_name'] == "")
		{

	  		$errors = 'Conductor name is required.';
		}
		elseif(!isset($_POST['assign_date']) || $_POST['assign_date'] == "")
		{
	  		$errors = 'Date is required.';
		}

		if($errors == "" && !empty($_POST['bus_name']) && !empty($_POST['driver_name']) && !empty($_POST['conductor_name']) && !empty($_POST['assign_date']))
		{			
			$user_id = $session['id'];
			$bus_id = $_POST['bus_name'];
			$assign_date = $_POST['assign_date'];
			$created_date = date(CREATED_DATE);
			$driver_name = $_POST['driver_name'];
			$conductor_name = $_POST['conductor_name'];
			$operator_id=$_POST['operator_name'];
			$helper_name = $_POST['helper_name'];
			$other_name = $_POST['other_name'];
			
			//Remove old records
			$sql = "DELETE FROM staff_assigned WHERE driver = $driver_name AND conductor = $conductor_name AND bus_id = '$bus_id'";
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

					$chk1 = $this->chk_staff_assigned($driver_name,$conductor_name,$operator_id,$date);					

					if($chk1 == true)
					{
						$sql = "INSERT INTO staff_assigned(bus_id,driver,conductor,operator,fordate,created_by,created_date,last_modified_date) 
						VALUES('$bus_id','$driver_name','$conductor_name','$operator_id','$date','$user_id','$created_date','$created_date')";

						$this->db->query($sql);
					}
					else
					{
						$c++;
					}
				}

				if($c==1)
				{
					$errors = "Staff is already assigned for selected date.";
				}
				elseif($c>1)
				{
					$errors = "Staff has been assigned successfully ( for some selected dates it is assigned already ).";
				}				
			}			
		}

		if (!empty($errors)) 
		{
		  $data  = $errors;
		} 
		else 
		{
		  $data = 'Staff has been assigned successfully.';
		}
		
		return $data;
		
	}


	function chk_staff_assigned($driver_id,$conductor_id,$operator_id,$date,$bus="")
	{
		if($bus!="")
		{
			$bus = "AND bus_id != $bus";
		}
		if($operator_id!="" && $operator_id!="0")
		{
			$operator_id = " OR operator = $operator_id";
		}
		else{
			$operator_id = "";
		}
		$sql = "SELECT id FROM staff_assigned WHERE is_deleted = 0
		 AND (driver = $driver_id
		 AND conductor = $conductor_id
		  $operator_id) 
		 AND fordate = '$date' $bus LIMIT 1";

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
		$this->db->update('staff_assigned');			
		
		echo "<script>alert('Staff has been deassign successfully.');</script>";
		
		redirect('staff_assign', 'refresh');
	}


}
?>