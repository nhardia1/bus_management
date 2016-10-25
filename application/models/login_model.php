<?php
class Login_model extends CI_Model 
{

public function __construct()
{
  $this->load->model('custom_model');
   $this->load->model('custom_model');
  	$this->load->database();
  	parent::__construct();
	$this->load->database();
	

}

function login($email, $password)
{
	   //$newscript = AES_SCRYPT.$password;	
	   //$newpassword = base64_encode($newscript);
	   $this -> db -> select('id, name,email, password,photo');
	   $this -> db -> from('users');
	   $this -> db -> where('email', $email);
	   $this -> db -> where('password', MD5($password));
	   $this -> db -> limit(1);	 
	   $query = $this -> db -> get();
 
   if($query -> num_rows() == 1)
	   {
	     return $query->result();
	    
	   }
	   else
	   {
	     return false;
	     
	   }
	 }






public function forget_password() 
{
	$errors = array();
	$data = array();
	
	// checking for blank values.
	if (!isset($_POST['email'])){

  		$errors['email'] = 'Email name is required.';
	}

	if(!empty($_POST['email']))
	{
		$email=$_POST['email'];
		$this->db->where('email',$email);
		$query = $this->db->get('users');
		if($query->num_rows() >0)
		{	
			$result=$query->row_array();
			
			$maildata['user_name']=$result['name'];
			$maildata['plan_password']=$result['plan_password'];
			$maildata['email']=$result['email'];
			$this->sendmail($maildata);
		}
		else
		{
			$errors['email'] = 'Invalid Email.';
		}
	}

	if (!empty($errors)) {
	  $data['errors']  = $errors;
	} else {
	  $data['message'] = 'Password sent successfully, Please check your mail.';
		//$data['user_details'] =$user_details;
	}

	return json_encode($data);
}


public function change_password() 
{
	$errors = array();
	$data = array();
	// Getting posted data and decodeing json
	
	// checking for blank values.
	if (!isset($_POST['confirm_password']))
	{

  		$errors['new_password'] = 'new password is required.';
	}

	if (!isset($_POST['confirm_password']))
	{

  		$errors['confirm_password'] = 'confirm password is required.';
	}

	if(!empty($_POST['new_password']))
	{
		if($_POST['new_password']==$_POST['confirm_password'])
		{
			$session_data = $this->session->userdata('user_details');
			$email=$session_data['email'];
			$id=$session_data['id'];
			$username=$session_data['username'];
			
			$this->db->select('email');
			$this->db->where('email',$email);
			$query = $this->db->get('users');
			
			if($query->num_rows() >0)
			{	
				$this->db->set('password', md5($_POST['new_password']));
				$this->db->set('plan_password', $_POST['new_password']);
				$this->db->set('last_modified_date', date('Y-m-d h:i:s'));
				$this->db->where("email =",$email);
				$this->db->where("id =",$id);
				$this->db->update('users');
			}
			else
			{
				$errors['email'] = 'Invalid Credentials.';
			}
		}else{
			$errors['email'] = 'Both password are not matched.';
		}
	}

	if (!empty($errors)) {
	  $data['errors']  = $errors;
	} else {
	  $data['message'] = 'Password Change successfully.';
		//$data['user_details'] =$user_details;
	}

	return json_encode($data);
}

public function sendmail($maildata)
{
	$this->load->library('email');
	$config['protocol'] = "smtp";
	$config['smtp_host'] = "ssl://smtp.gmail.com";
	$config['smtp_port'] = "465";
	$config['smtp_user'] = "smtp@fxbytes.com";
	$config['smtp_pass'] = "smtp*123";
	$config['charset'] = "utf-8";
	$config['mailtype'] = "html";
	$config['newline'] = "\r\n";
	$config['priority']    =    "1";
	$this->email->initialize($config);
	$this->email->from('ADMIN_EMAIL','Bus Admin');
	$this->email->to($maildata['email']);
	$this->email->subject('Login Details.');
    $message=$this->load->view('sendmail',$maildata,TRUE);
	$this->email->message($message);
	$this->email->send(); /*uncomment this*/
}



/*public function login_submit() 
{
	$this->session->unset_userdata('user_details');
	$errors = array();
	$data = array();
	// Getting posted data and decodeing json
	//$_POST = json_decode(file_get_contents('php://input'));
	
	// checking for blank values.
	if (!isset($_POST['email'])){

	$errors['email'] = 'Email name is required.';
	}
	if (!isset($_POST['password'])){

	  	$errors['password'] = 'Password name is required.';
	}

	if(!empty($_POST['email']))
	{
		$email=$_POST['email'];
		$password=$_POST['password'];
		$this->db->where('email',$email);
		$this->db->where('password',$password);
		$query = $this->db->get('users');
		if($query->num_rows() >0)
		{	
			$sess_array = array();
			$result=$query->row_array();
			$user_type=$result['type'];
			if($user_type==1)
			{
				$type='admin';
			}
			else
			{
				$type='manager';
			}

			$sess_array = array(
     		'id' => $result['id'],
     		'username' => $result['name'],
     		'email' => $result['email'],
   	 		'type' => $type
   			);
			
   			$user_details=$this->session->set_userdata('user_details', $sess_array);
			//$errors['email'] = 'Login successfully	.';
		}
		else
		{
			$errors['email'] = 'Invalid Credentials.';
		}
		}

		if (!empty($errors)) {
		  $data['errors']  = $errors;
		} else {
		  $data['message'] = 'Login successfully.';
		}

		return json_encode($data);
}*/





}
?>