<?php
class Staff_model extends CI_Model 
{

	public function __construct()
	{
	  
	  	//$this->load->database();
	  	parent::__construct();
		$this->load->database();
		

	}

	public function staff_list() {
		
		$this->db->select('id,name,address,contact_number,staff_type');
		$this->db->where('is_deleted',0);
		$query = $this->db->get('staff');
		return $query->result();
	}

	
	public function insert_staff() 
	{
		$session = $this->session->userdata('user_details');
		$errors = array();
		$data = array();

		if(empty($_POST['staff_name']))	{
	  		$errors = 'staff name is required.';
		} else if(empty($_POST['staff_contact_number'])) {
	  		$errors = 'Contact Number is required.';
		} else if( !is_numeric($_POST['staff_contact_number']) ){
			$errors = 'Please insert a valid contact number.';
		} else if(empty($_POST['staff_address'])) {
	  		$errors = 'Address is required.';
		} else if(empty($_POST['staff_type'])) {
	  		$errors = 'Type is required.';
		}

		$image_path=$_POST['staff_lic_photo']; $uploaded = 1;
		if($_POST['image_form_submit']>0 && $_POST['staff_type'] == 3 && isset($_FILES['fileUpload']['name']) && !empty($_FILES['fileUpload']['name']))
		{
			$image_name = "fileUpload";
			
			$_FILES['fileUpload']['name'] = str_replace(" ", "-", $_FILES['fileUpload']['name']);
			$_FILES['fileUpload']['name'] = str_replace("_", "-", $_FILES['fileUpload']['name']);
			$config['file_name'] = "img_".time()."_".$_FILES['fileUpload']['name'];
			$image_path = $config['file_name'];
			$config['upload_path'] = STAFF_UPLOADS;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';


			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload($image_name))
			{
				$errors = $this->upload->display_errors();
				$uploaded = 0;
			}
			else
			{
				$res['file_msg'] = $this->upload->data();
				$uploaded = 1;				
			}
			
		}

		//Added New Upload
		$profile_image_path=$_POST['staff_photo']; $profuploaded = 1;
		if(isset($_FILES['imageUpload']['name']) && !empty($_FILES['imageUpload']['name']))
		{
			$image_name = "imageUpload";
			
			$_FILES['imageUpload']['name'] = str_replace(" ", "-", $_FILES['imageUpload']['name']);
			$_FILES['imageUpload']['name'] = str_replace("_", "-", $_FILES['imageUpload']['name']);
			$config['file_name'] = "img_".time()."_".$_FILES['imageUpload']['name'];
			$profile_image_path = $config['file_name'];
			$config['upload_path'] = STAFF_UPLOADS;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';


			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload($image_name))
			{
				$errors = $this->upload->display_errors();
				$profuploaded = 0;
			}
			else
			{
				$res['file_msg'] = $this->upload->data();
				$profuploaded = 1;				
			}
			
		}
		//Added New Upload


		if($_POST['staff_type'] != 3)
		{
			$image_path = "";
		}

		if($_POST['staff_type'] == 2)
		{
			$agency_id = $_POST['agency_id'];
		}else{
			$agency_id = "null";
		}

		if(!empty($_POST['staff_name']) && !empty($_POST['staff_contact_number']) && !empty($_POST['staff_address']) && !empty($_POST['staff_type']) && $uploaded == 1)
		{
			$status=1;
			//$user_id=$session['id'];
			$staff_name=$_POST['staff_name'];
			$staff_contact_number=$_POST['staff_contact_number'];
			$staff_address=$_POST['staff_address'];
			$staff_type=$_POST['staff_type'];
			$staff_license_number=$_POST['staff_license_number'];
			$staff_expiry_date=$_POST['staff_expiry_date'];
			$created_by=$session['id'];
			$date=date(CREATED_DATE);
			$staff_pin=md5($_POST['staff_pin']);
			$plain_pin=($_POST['staff_pin']);

			
			if(!empty($_POST['staff_expiry_date']))
			{
				$staff_expiry_date = date(CHANGE_INTO_DATE_FORMAT,strtotime($staff_expiry_date));
			}else{
				$staff_expiry_date='NULL';

			}
			$this->db->select('contact_number');
			$this->db->where('is_deleted',0);
			$this->db->where('contact_number',$staff_contact_number);
			
			$query = $this->db->get('staff');
			if($query->num_rows() >0)
			{				
				$errors='Contact Number Already Exist.';
			}
			else
			{
				$staff_type_arr = staff_type();
				$staff_type_num = $staff_type;
				//$staff_type = $staff_type_arr[$staff_type];
				$staff_type=get_new_staff_type($staff_type_num);				

				if(!empty($_POST['staff_expiry_date'])){
					$sql = "INSERT INTO staff(name,contact_number,address,staff_type,staff_type_num,staff_pin,plain_pin,agency_id,license_number,expiry_date,created_by,created_date,image_path,profile_image,last_modified_date) 
						VALUES('$staff_name','$staff_contact_number','$staff_address','$staff_type','$staff_type_num','$staff_pin',$plain_pin,$agency_id,'$staff_license_number','$staff_expiry_date','$created_by','$date','$image_path','$profile_image_path','$date')";

				}else{
					$sql = "INSERT INTO staff(name,contact_number,address,staff_type,staff_type_num,staff_pin,plain_pin,agency_id,license_number,created_by,created_date,image_path,profile_image,last_modified_date) 
						VALUES('$staff_name','$staff_contact_number','$staff_address','$staff_type','$staff_type_num','$staff_pin',$plain_pin,$agency_id,'$staff_license_number','$created_by','$date','$image_path','$profile_image_path','$date')";

				}
				

				$this->db->query($sql);
				

			}
		}

		if (!empty($errors)) {
		  $data  = $errors;
		} else {
		  $data = 'Staff has been added successfully.';
		}
		return $data;
		
	}


	public function delete_staff($id) 
	{
		$session = $this->session->userdata('user_details');
		$login_user_id=$session['id'];
		
		$errors = array();
		$data = array();
		
		
			$this->db->set('is_deleted', 1);
			$this->db->set('last_modified_by', $login_user_id);
			$this->db->set('last_modified_date', date(LAST_MODIFIED_DATE));
			$this->db->where("id =",$id);
			$this->db->update('staff');			
			echo "<script>alert('Staff Deleted');</script>";
			redirect('staff/index', 'refresh');
	}


	//Developer 1 ----------------------------------------------------------------------
	public function execute_sql($sql) 
	{
		$query = $this->db->query($sql);
		
		return $query->result();
	}

	public function get_staff_details($id) 
	{
		
		//$this->db->where('status',1);
		$this->db->select('id,name,contact_number,address,staff_type,staff_type_num,license_number,expiry_date,image_path,resource_type,plain_pin,agency_id,profile_image');
		$this->db->where('is_deleted',0);
		$this->db->where('id',$id);
		$query = $this->db->get('staff');
		return $query->row_array();
	}


	public function update_staff() 
	{
		$errors='';
		$session = $this->session->userdata('user_details');
		$errors = array();
		$data = array();

		if (empty($_POST['staff_name'])){
	  		$errors = 'staff name is required.';
		} else if ( empty( $_POST['staff_contact_number'] ) ){
	  		$errors = 'Contact Number is required.';
		} else if( !is_numeric($_POST['staff_contact_number']) ){
			$errors = 'Please insert a valid contact number.';
		}else if (empty($_POST['staff_address'])){
	  		$errors = 'Address is required.';
		} else if (empty($_POST['staff_type'])){
	  		$errors = 'Type is required.';
		}

		$image_path=$_POST['staff_lic_photo']; $uploaded = 1;
		if($_POST['image_form_submit']>0 && $_POST['staff_type'] == 3 && isset($_FILES['fileUpload']['name']) && !empty($_FILES['fileUpload']['name']))
		{
			$image_name = "fileUpload";
			$_FILES['fileUpload']['name'] = str_replace(" ", "-", $_FILES['fileUpload']['name']);
			$_FILES['fileUpload']['name'] = str_replace("_", "-", $_FILES['fileUpload']['name']);
			$config['file_name'] = "img_".time()."_".$_FILES['fileUpload']['name'];
			$image_path=$config['file_name'];
			$config['upload_path'] = STAFF_UPLOADS;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			//$config['max_size']	= '500';

			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload($image_name))
			{
				$errors = $this->upload->display_errors();
				$uploaded = 0;
			}
			else
			{
				$res['file_msg'] = $this->upload->data();
				$uploaded = 1;

				$id = $_POST['id'];
				$sql = "UPDATE 
							staff 
						SET 
							image_path = '$image_path'
						WHERE
							id = '$id'";

				$this->db->query($sql);				
			}
				
			
		}
		//Added New Upload
		$profile_image_path=$_POST['staff_photo']; $profuploaded = 1;
		if(isset($_FILES['imageUpload']['name']) && !empty($_FILES['imageUpload']['name']))
		{
			$image_name = "imageUpload";
			
			$_FILES['imageUpload']['name'] = str_replace(" ", "-", $_FILES['imageUpload']['name']);
			$_FILES['imageUpload']['name'] = str_replace("_", "-", $_FILES['imageUpload']['name']);
			$config['file_name'] = "img_".time()."_".$_FILES['imageUpload']['name'];
			$profile_image_path = $config['file_name'];
			$config['upload_path'] = STAFF_UPLOADS;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';


			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload($image_name))
			{
				$errors = $this->upload->display_errors();
				$profuploaded = 0;
			}
			else
			{
				$res['file_msg'] = $this->upload->data();
				$profuploaded = 1;	
				$id = $_POST['id'];
				$sql = "UPDATE 
							staff 
						SET 
							profile_image = '$profile_image_path'
						WHERE
							id = '$id'";

				$this->db->query($sql);				
			}
			
		}
		//Added New Upload

		if($_POST['staff_type'] != 3)
		{
			$image_path = "";
		}



		if(!empty($_POST['staff_name']) && !empty($_POST['staff_contact_number']) && !empty($_POST['staff_address']) && !empty($_POST['staff_type']))
		{
			$id = $_POST['id'];
			$staff_name=$_POST['staff_name'];
			$staff_contact_number=$_POST['staff_contact_number'];
			$staff_address=$_POST['staff_address'];
			$staff_type=$_POST['staff_type'];
			$staff_license_number=$_POST['staff_license_number'];
			$staff_expiry_date=$_POST['staff_expiry_date'];
			
			if(isset($staff_expiry_date) && !empty($staff_expiry_date))
			{
				$staff_expiry_date = date(CHANGE_INTO_DATE_FORMAT,strtotime($staff_expiry_date));
			}
			if($_POST['staff_type'] == 2)
			{
				$agency_id = $_POST['agency_id'];
			}else{
				$agency_id = "null";
			}
			$user_id=$session['id'];
			$date=date(CREATED_DATE);

			$this->db->select('contact_number');
			$this->db->where('is_deleted',0);
			$this->db->where('id <>',$id);
			$this->db->where('contact_number',$staff_contact_number);
			//$this->db->where('name',$name);
			$query = $this->db->get('staff');
			if($query->num_rows() >0)
			{
				$errors = 'Contact Number Already Exist.';
			}
			else
			{
				$staff_type_arr = staff_type();
				$staff_type_num = $staff_type;
				$staff_type=get_new_staff_type($staff_type_num);			
				$user_id = $session['id'];
				$date = date(LAST_MODIFIED_DATE);
				$sql = "UPDATE 
							staff 
						SET 
							name = '$staff_name',
							contact_number = '$staff_contact_number',
							address = '$staff_address',
							staff_type = '$staff_type',
							staff_type_num = '$staff_type_num',
							agency_id='$agency_id',
							license_number = '$staff_license_number',
							expiry_date = '$staff_expiry_date',
							image_path = '$image_path',
							last_modified_date = '$date', 
							last_modified_by = '$user_id'
						WHERE
							id = '$id'";

				$this->db->query($sql);
			}
		}

		if (!empty($errors)) {
		  $data  = $errors;
		} else {
		  $data = 'Staff details has been updated successfully.';
		}
		return $data;
	}





}
?>