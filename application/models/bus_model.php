<?php
class Bus_model extends CI_Model 
{

	public function __construct()
	{  	
	  	parent::__construct();
		
		$this->load->database();
	}


	public function bus_list() {
		
		$this->db->select('id,name,capacity,model,bus_number,type,chassis_number');
		$this->db->where('is_deleted',0);
		$query = $this->db->get('bus');
		return $query->result();
	}

	public function insert_bus() 
	{
		$session = $this->session->userdata('user_details');
		$errors = '';//array();
		$data = array();
		
		if(empty($_POST['bus_name'])){
	  		$errors = 'Bus Name is required.';
		} else if(empty($_POST['bus_number'])){
	  		$errors = 'Bus Number is required.';
		} else if(empty($_POST['chassis_number'])){
	  		$errors = 'Chassis Number is required.';
		} else if(empty($_POST['bus_type'])){
	  		$errors = 'Bus Type is required.';
		} else if(empty($_POST['bus_capacity'])){
	  		$errors = 'Bus Capacity is required.';
		} else if(empty($_POST['bus_model'])){
	  		$errors = 'Bus Model is required.';
		}else if(empty($_POST['operator_name'])){
	  		$errors = 'Bus Operator is required.';
		}

		$re = "/^[a-zA-Z]{2}\s[0-9]{2}\s[a-zA-Z]{2}\s[0-9]{4}$/";
		//$re = "/^[a-zA-Z]{2}[ -][0-9]{1,2}(?: [a-zA-Z])?(?: [a-zA-Z]*)? [0-9]{4}$/"; 
		$bus_number = $_POST['bus_number']; 
		if (!preg_match($re,$bus_number)) {
		  $errors = "Please enter valid bus number.";
		}
		/////////////////////////////Document upload////////////////////////////////////////
		$document_image_path=''; $uploaded = 1;
		if($_POST['image_form_submit']>0 && isset($_FILES['bus_document']['name']) && !empty($_FILES['bus_document']['name']))
		{

			$image_name = "bus_document";

			$config['file_name'] = "doc_".time()."_".$_FILES['bus_document']['name'];

			$document_image_path=$config['file_name'];

			$config['upload_path'] = BUS_UPLOADS;

			$config['allowed_types'] = 'txt|pdf|doc|docx';

			//$config['max_size']	= '1000';

			$this->load->library('upload', $config);

			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload($image_name))
			{
				$errors = 'Error in document upload: '.$this->upload->display_errors('', '');
				$uploaded = 0;
			}
			else
			{
				$res['file_msg'] = $this->upload->data();
				$uploaded = 1;				
			}
			
		}
		/////////////////////////End Document upload////////////////////////////////////////

		/////////////////////////Bus Registration upload////////////////////////////////////

		$registation_image_path=''; $registationUpload = 1;
		if($_POST['registration_form_submit']>0 && isset($_FILES['bus_registration']['name']) && !empty($_FILES['bus_registration']['name']))
		{

			$image_name = "bus_registration";

			$config['file_name'] = "reg_".time()."_".$_FILES['bus_registration']['name'];

			$registation_image_path=$config['file_name'];

			$config['upload_path'] = BUS_UPLOADS;

			$config['allowed_types'] = 'gif|png|jpg|jpeg';

			//$config['max_size']	= '1000';

			$this->load->library('upload', $config);

			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload($image_name))
			{
				$errors = 'Error in bus registration upload: '.$this->upload->display_errors('', '');
				$registationUpload = 0;
			}
			else
			{
				$res['file_msg'] = $this->upload->data();
				$registationUpload = 1;				
			}
			
		}
		/////////////////////////End Bus Registration upload/////////////////////////////////

		/////////////////////////Bus Permit upload////////////////////////////////////

		$permit_image_path=''; $permitUpload = 1;
		if($_POST['permit_form_submit']>0 && isset($_FILES['bus_permit']['name']) && !empty($_FILES['bus_permit']['name']))
		{

			$image_name = "bus_permit";

			$config['file_name'] = "per_".time()."_".$_FILES['bus_permit']['name'];

			$permit_image_path=$config['file_name'];

			$config['upload_path'] = BUS_UPLOADS;

			$config['allowed_types'] = 'gif|png|jpg|jpeg';

			//$config['max_size']	= '1000';

			$this->load->library('upload', $config);

			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload($image_name))
			{
				$errors = 'Error in permit upload: '.$this->upload->display_errors('', '');
				$permitUpload = 0;
			}
			else
			{
				$res['file_msg'] = $this->upload->data();
				$permitUpload = 1;				
			}
			
		}
		/////////////////////////End Bus Permit upload/////////////////////////////////

		/////////////////////////Bus Insurance upload////////////////////////////////////

		$insurance_image_path=''; $insuranceUpload = 1;
		if($_POST['insurance_form_submit']>0 && isset($_FILES['bus_insurance']['name']) && !empty($_FILES['bus_insurance']['name']))
		{

			$image_name = "bus_insurance";

			$config['file_name'] = "ins_".time()."_".$_FILES['bus_insurance']['name'];

			$insurance_image_path=$config['file_name'];

			$config['upload_path'] = BUS_UPLOADS;

			$config['allowed_types'] = 'gif|png|jpg|jpeg';

			//$config['max_size']	= '1000';

			$this->load->library('upload', $config);

			$this->upload->initialize($config);

			if ( ! $this->upload->do_upload($image_name))
			{
				$errors = 'Error in insurance upload: '.$this->upload->display_errors('', '');
				$insuranceUpload = 0;
			}
			else
			{
				$res['file_msg'] = $this->upload->data();
				$insuranceUpload = 1;				
			}
			
		}
		/////////////////////////End Bus Insurance upload/////////////////////////////////



		$photo_path=array();
		$imgUploaded = 1;
		if(isset($_FILES['bus_photo']['name']) && !empty($_FILES['bus_photo']['name']))
		{			
			$this->load->library('upload');

			$files = $_FILES;

            $count = count($_FILES['bus_photo']['name']);
			
			for ($i=0; $i <$count ; $i++) {
				if( !empty($files['bus_photo']['name'][$i]) ) {
					$files['bus_photo']['name'][$i] = str_replace(" ", "-", $files['bus_photo']['name'][$i]);
					$files['bus_photo']['name'][$i] = str_replace("_", "-", $files['bus_photo']['name'][$i]);
					$photo_path[] = $_FILES['bus_photo']['name']= "img_".time()."_".$files['bus_photo']['name'][$i];
	                $_FILES['bus_photo']['type']= $files['bus_photo']['type'][$i];
	                $_FILES['bus_photo']['tmp_name']= $files['bus_photo']['tmp_name'][$i];
	                $_FILES['bus_photo']['error']= $files['bus_photo']['error'][$i];
	                $_FILES['bus_photo']['size']= $files['bus_photo']['size'][$i];
	                $this->upload->initialize($this->set_upload_options());

	               // $photo_path[]=$files['bus_photo']['name'][$i];

	                if($this->upload->do_upload('bus_photo') == false)
	                {
	                   $errors = 'Error in bus photo upload: '.$this->upload->display_errors('', '');
	                   $imgUploaded = 0;
	                   // echo '<br>error='.$errors;           
	                } else {
	                	$imgUploaded = 1;
	                  //echo '<br>msg';      
	                }
				}				
            }		
		}

		if(!empty($_POST['bus_name']) && !empty($_POST['operator_name']) && !empty($_POST['bus_number']) && !empty($_POST['chassis_number']) && !empty($_POST['bus_type']) && !empty($_POST['bus_capacity']) && !empty($_POST['bus_model']))
		{ 
			$status=1;
			//$user_id=$session['id'];
			$bus_name=$_POST['bus_name'];
			$bus_number=$_POST['bus_number'];
			$chassis_number=$_POST['chassis_number'];
			$bus_type=$_POST['bus_type'];
			$bus_capacity=$_POST['bus_capacity'];
			$bus_model=$_POST['bus_model'];
			$bus_operator=$_POST['operator_name'];
			
			$created_by=$session['id'];
			$date=date(CREATED_DATE);
			

			$this->db->select('bus_number');
			$this->db->where('is_deleted',0);
			$this->db->where('bus_number',$bus_number);
			//$this->db->where('name',$name);
			$query = $this->db->get('bus');
			if($query->num_rows() >0)
			{
				
				$errors='Bus Number Already Exist.';
			}
			else
			{
				if(empty($errors)){

					$sql = "INSERT INTO bus(name,operator_id,bus_number,chassis_number,type,capacity,model,created_by,created_date,document,registration_image,permit_image,insurance_image,last_modified_date) 
							VALUES('$bus_name','$bus_operator','$bus_number','$chassis_number','$bus_type','$bus_capacity','$bus_model','$created_by','$date','$document_image_path','$registation_image_path','$permit_image_path','$insurance_image_path','$date')";
					$this->db->query($sql);
					$this->db->insert_id();
					foreach ($photo_path as $key => $value) {
						
						$sql1 = "INSERT INTO bus_photos(bus_id,image,created_by,created_date,last_modified_date) 
							VALUES('$bus_id','$value','$created_by','$date','$date')";

						$this->db->query($sql1);
					}
				}

			}
		}

		if (!empty($errors) && count($errors)>0) {
		  $data  = $errors;
		} else {
		  $data = 'Bus has been added successfully.';
		}
		return $data;
		
	}


	public function delete_bus($id) 
	{
		$session = $this->session->userdata('user_details');
		$login_user_id=$session['id'];
		
		$errors = array();
		$data = array();
		
		
			$this->db->set('is_deleted', 1);
			$this->db->set('last_modified_by', $login_user_id);
			$this->db->set('last_modified_date', date(LAST_MODIFIED_DATE));
			$this->db->where("id =",$id);
			$this->db->update('bus');	

			$this->db->set('is_deleted', 1);
			$this->db->set('last_modified_by', $login_user_id);
			$this->db->set('last_modified_date', date(LAST_MODIFIED_DATE));
			$this->db->where("bus_id =",$id);
			$this->db->update('bus_photos');

			echo "<script>alert('Bus Deleted');</script>";
			redirect('bus/index', 'refresh');
	}


	//Developer 1 ----------------------------------------------------------------------
	public function execute_sql($sql) 
	{
		$query = $this->db->query($sql);
		
		return $query->result();
	}

	public function get_bus_details($id) 
	{
		
		$this->db->select('id,name,operator_id,bus_number,chassis_number,type,capacity,model,document,resource_type,registration_image,permit_image,insurance_image');
		$this->db->where('is_deleted',0);
		$this->db->where('id',$id);
		$query = $this->db->get('bus');
		return $query->row_array();
	}

	public function get_bus_photo_details($id) {
		
		//$this->db->where('status',1);
		$this->db->select('id,image');
		$this->db->where('is_deleted',0);
		$this->db->where('bus_id',$id);
		$query = $this->db->get('bus_photos');
		return $query->result();
	}

	private function set_upload_options()
        {   

            $config = array();
            $config['upload_path'] = BUS_UPLOADS;
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['overwrite']     = FALSE;
            return $config;
        }



	public function update_bus() 
	{
		
		$session = $this->session->userdata('user_details');
		$errors = '';
		$data = array();

		if(empty($_POST['bus_name'])){
	  		$errors = 'Bus Name is required.';
		} else if(empty($_POST['bus_number'])){
	  		$errors = 'Bus Number is required.';
		} else if(empty($_POST['chassis_number'])){
	  		$errors = 'Chassis Number is required.';
		} else if(empty($_POST['bus_type'])){
	  		$errors = 'Bus Type is required.';
		}else if(empty($_POST['bus_model'])){
	  		$errors = 'Bus Model is required.';
		}else if(empty($_POST['operator_name'])){
	  		$errors = 'Bus Operator is required.';
		}
		$photo_name=$_POST['photo_name'];

		$re = "/^[a-zA-Z]{2}\s[0-9]{2}\s[a-zA-Z]{2}\s[0-9]{4}$/";
		//$re = "/^[a-zA-Z]{2}[ -][0-9]{1,2}(?: [a-zA-Z])?(?: [a-zA-Z]*)? [0-9]{4}$/"; 
		$bus_number = $_POST['bus_number']; 
		if (!preg_match($re,$bus_number)) {
		  $errors = "Please enter valid bus number.";
		}


		$image_path=''; $uploaded = 1;
		if($_POST['image_form_submit']>0 && isset($_FILES['bus_document']['name']) && !empty($_FILES['bus_document']['name']))
		{
			$_FILES['bus_document']['name'] = str_replace(" ", "-", $_FILES['bus_document']['name']);
			$_FILES['bus_document']['name'] = str_replace("_", "-", $_FILES['bus_document']['name']);
				$id = $_POST['id'];
				$image_name = "bus_document";
				$config['file_name'] = "doc_".time()."_".$_FILES['bus_document']['name'];
				$image_path=$config['file_name'];
				$config['upload_path'] = BUS_UPLOADS;
				$config['allowed_types'] = 'txt|pdf|doc|docx';
				//$config['max_size']	= '1000';

				$this->load->library('upload', $config);
				$this->upload->initialize($config);

				if ( ! $this->upload->do_upload($image_name))
				{
					$errors = 'Error in document upload: '.$this->upload->display_errors('', '');
					$uploaded = 0;
				}
				else
				{
					//$res['file_msg'] = $this->upload->data();
					//$uploaded = 1;
					$sql_image = "UPDATE 
							bus 
						SET 
							document = '$image_path'
							
						WHERE
							id = '$id'";

				$this->db->query($sql_image);				
				}
			
			
		}
		$registration_image_path=''; $registrationUploaded = 1;
		if($_POST['registration_form_submit']>0 && isset($_FILES['bus_registration']['name']) && !empty($_FILES['bus_registration']['name']))
		{
			$_FILES['bus_registration']['name'] = str_replace(" ", "-", $_FILES['bus_registration']['name']);
			$_FILES['bus_registration']['name'] = str_replace("_", "-", $_FILES['bus_registration']['name']);
				$id = $_POST['id'];
				$image_name = "bus_registration";
				$config['file_name'] = "reg_".time()."_".$_FILES['bus_registration']['name'];
				$registration_image_path=$config['file_name'];
				$config['upload_path'] = BUS_UPLOADS;
				$config['allowed_types'] = 'png|gif|jpg|jpeg';
				//$config['max_size']	= '1000';

				$this->load->library('upload', $config);
				$this->upload->initialize($config);

				if ( ! $this->upload->do_upload($image_name))
				{
					$errors = 'Error in bus registration: '.$this->upload->display_errors('', '');
					$registrationUploaded = 0;
				}
				else
				{
					//$res['file_msg'] = $this->upload->data();
					//$uploaded = 1;
					$sql_image = "UPDATE 
							bus 
						SET 
							registration_image = '$registration_image_path'
							
						WHERE
							id = '$id'";

				$this->db->query($sql_image);				
				}
			
			
		}

		$permit_image_path=''; $permitUploaded = 1;
		if($_POST['permit_form_submit']>0 && isset($_FILES['bus_permit']['name']) && !empty($_FILES['bus_permit']['name']))
		{
			$_FILES['bus_permit']['name'] = str_replace(" ", "-", $_FILES['bus_permit']['name']);
			$_FILES['bus_permit']['name'] = str_replace("_", "-", $_FILES['bus_permit']['name']);
				$id = $_POST['id'];
				$image_name = "bus_permit";
				$config['file_name'] = "per_".time()."_".$_FILES['bus_permit']['name'];
				$permit_image_path=$config['file_name'];
				$config['upload_path'] = BUS_UPLOADS;
				$config['allowed_types'] = 'png|gif|jpg|jpeg';
				//$config['max_size']	= '1000';

				$this->load->library('upload', $config);
				$this->upload->initialize($config);

				if ( ! $this->upload->do_upload($image_name))
				{
					$errors = 'Error in permit upload: '.$this->upload->display_errors('', '');
					$permitUploaded = 0;
				}
				else
				{
					
					$sql_image = "UPDATE 
							bus 
						SET 
							permit_image = '$permit_image_path'
							
						WHERE
							id = '$id'";

				$this->db->query($sql_image);				
				}
			
			
		}

		$insurance_image_path=''; $permitUploaded = 1;
		if($_POST['insurance_form_submit']>0 && isset($_FILES['bus_insurance']['name']) && !empty($_FILES['bus_insurance']['name']))
		{
			$_FILES['bus_insurance']['name'] = str_replace(" ", "-", $_FILES['bus_insurance']['name']);
			$_FILES['bus_insurance']['name'] = str_replace("_", "-", $_FILES['bus_insurance']['name']);
				$id = $_POST['id'];
				$image_name = "bus_insurance";
				$config['file_name'] = "ins_".time()."_".$_FILES['bus_insurance']['name'];
				$insurance_image_path=$config['file_name'];
				$config['upload_path'] = BUS_UPLOADS;
				$config['allowed_types'] = 'png|gif|jpg|jpeg';
				//$config['max_size']	= '1000';

				$this->load->library('upload', $config);
				$this->upload->initialize($config);

				if ( ! $this->upload->do_upload($image_name))
				{
					$errors = 'Error in insurance upload: '.$this->upload->display_errors('', '');
					$permitUploaded = 0;
				}
				else
				{
					
					$sql_image = "UPDATE 
							bus 
						SET 
							insurance_image = '$insurance_image_path'
							
						WHERE
							id = '$id'";

				$this->db->query($sql_image);				
				}
			
			
		}
		$photo_path=array();
		
		if(isset($_FILES['bus_photo']['name']) && !empty($_FILES['bus_photo']['name']))
		{
			


		$this->load->library('upload');

			$files = $_FILES;

            $count = count($_FILES['bus_photo']['name']);
			
			//for ($i=0; $i <$count ; $i++) 
			foreach ($_FILES['bus_photo']['name'] as $i => $name)
				# code...
			
			{ 
				//echo "<br>".$files['bus_photo']['name'][$i];
				if(isset($files['bus_photo']['name'][$i]) && !empty($files['bus_photo']['name'][$i]))
				{
						//echo "<br>1";
						$files['bus_photo']['name'][$i] = str_replace(" ", "-", $files['bus_photo']['name'][$i]);
						$files['bus_photo']['name'][$i] = str_replace("_", "-", $files['bus_photo']['name'][$i]);
						$files['bus_photo']['name'][$i]="img_".time()."_".$files['bus_photo']['name'][$i];
						$_FILES['bus_photo']['name']= $files['bus_photo']['name'][$i];
		                $_FILES['bus_photo']['type']= $files['bus_photo']['type'][$i];
		                $_FILES['bus_photo']['tmp_name']= $files['bus_photo']['tmp_name'][$i];
		                $_FILES['bus_photo']['error']= $files['bus_photo']['error'][$i];
		                $_FILES['bus_photo']['size']= $files['bus_photo']['size'][$i];
		                $this->upload->initialize($this->set_upload_options());

		                $photo_path[]=$files['bus_photo']['name'][$i];

		                if($this->upload->do_upload('bus_photo') == false)
		                {
		                  // echo "<br>".$errors = $this->upload->display_errors();
		                   $imgUploaded = 0;
		                   // echo '<br>error='.$errors;           
		                }
		                else
		                {
		                	$imgUploaded = 1;
		                   //echo '<br>msg';      
		                }
		        }

            }
			
			
		}


		if(!empty($_POST['bus_name']) && !empty($_POST['operator_name']) && !empty($_POST['bus_name']) && !empty($_POST['bus_number']) && !empty($_POST['chassis_number']) && !empty($_POST['bus_type'])  && !empty($_POST['bus_model']))
		{
			$id = $_POST['id'];
			$bus_name=$_POST['bus_name'];
			$operator_name=$_POST['operator_name'];
			$bus_number=$_POST['bus_number'];
			$chassis_number=$_POST['chassis_number'];
			$bus_type=$_POST['bus_type'];
			$bus_model=$_POST['bus_model'];
			$user_id=$session['id'];
			$date=date(LAST_MODIFIED_DATE);
			$created_by=$session['id'];


			$this->db->select('bus_number');
			$this->db->where('is_deleted',0);
			$this->db->where('id <>',$id);
			$this->db->where('bus_number',$bus_number);
			//$this->db->where('name',$name);
			$query = $this->db->get('bus');
			if($query->num_rows() >0)
			{
				$errors = 'Bus Number Already Exist.';
			}
			else
			{
				if(empty($errors)){
						$user_id = $session['id'];
						
						$sql = "UPDATE 
									bus 
								SET 
									name = '$bus_name',
									operator_id = '$operator_name',
									bus_number = '$bus_number',
									chassis_number = '$chassis_number',
									type = '$bus_type',
									model = '$bus_model',
									last_modified_date = '$date', 
									last_modified_by = '$user_id'
								WHERE
									id = '$id'";

						$this->db->query($sql);

						$sql1 = "UPDATE 
									bus_photos 
								SET 
									is_deleted='1'
								WHERE
									bus_id = '$id'";
						$this->db->query($sql1);


						foreach ($photo_name as $key => $image) {
							
							$sql2 = "UPDATE 
									bus_photos 
								SET 
									is_deleted='0',
									last_modified_date = '$date', 
									last_modified_by = '$user_id'
								WHERE
									id = '$key'";

							$this->db->query($sql2);
						}

						foreach ($files['bus_photo']['name'] as $key => $value) {
							if(!empty($value)){
								//echo "not null"."<br>";
									
								$this->db->where('is_deleted',0);
								$this->db->where('id',$key);
								$query_check_photo_for_edit = $this->db->get('bus_photos');
								if($query_check_photo_for_edit->num_rows() >0)
								{
									//echo "update"."<br>";
									//$image_value=$photo_path[$key];
									$sql_photo = "UPDATE 
														bus_photos 
													SET 
														is_deleted='0',
														image='$value',
														last_modified_date = '$date', 
														last_modified_by = '$user_id'
													WHERE
														id = '$key'";

												$this->db->query($sql_photo);
								}
								else
								{
									//echo "insert"."<br>";
									$image_value=$photo_path[$key];
												$sql3 = "INSERT INTO bus_photos(bus_id,image,created_by,created_date) 
													VALUES('$id','$value','$created_by','$date')";

												$this->db->query($sql3);

								}



							}
						}

				}
			}
		}

		if (!empty($errors)) {
		  $data  = $errors;
		} else {
		  $data = 'Bus details has been updated successfully.';
		}
		return $data;

		

	}



}
?>