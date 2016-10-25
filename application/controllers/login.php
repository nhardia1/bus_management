<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('custom_model');
		$this->load->model('login_model');
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
		$this->load->helper('url');
		$this->load->helper('form');		

		$user = $this->session->userdata('user_details');
		if(isset($user['id']) && !empty($user['id']))
		{
		    redirect('home/index', 'refresh');
		}
		
		$this->load->library('form_validation');
		$this->load->view('login_header');
		$this->load->view('signin');
		$this->load->view('login_footer');
	}

	public function login_submit()
	{
		
		$this->session->unset_userdata('user_details');
	   	
		//This method will have the credentials validation
		//$this->custom_model->check_login_session();
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email', 'email', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'password', 'trim|required|xss_clean|callback_check_database');
				 
	   if($this->form_validation->run() == FALSE)
	   {
	     //Field validation failed.  User redirected to login page
	 
	   	$this->load->view('login_header');
		$this->load->view('signin');
		$this->load->view('login_footer');
	   }
	   else
	   {
	   
	     //Go to private area


	     redirect('home/index', 'refresh');
	   }
	}


	function check_database($password)
	 {
	   //Field validation succeeded.  Validate against database
	   $email = $this->input->post('email');
	   $plain_password= $this->input->post('password');
	   
	   //query the database
	   $result = $this->login_model->login($email, $password);
	   
	  /* print_r($result);
	   die;*/
	  if($result)
	   {
		if($this->input->post('remember')==1)
		{
			$year = time() + 31536000;
			setcookie('kidi_user_name', $this->input->post("email"), $year);
			//setcookie('kidi_user_password', $this->input->post("password"), $year);
		}
		else
		{
			if(isset($_COOKIE['user_details']))
				{
				$past = time() - 100;
				setcookie('user_details',  'gone', $past);
				//setcookie('kidi_user_password', 'gone', $past);
				}	
		}
	     $sess_array = array();
	     foreach($result as $row)
	     {
	       $sess_array = array(
	         'id' => $row->id,
	         'email' => $row->email,
	         'name' => $row->name,
		  	 'type' => "admin",
		  	 'photo' => $row->photo
	       );
	       $this->session->set_userdata('message','hindi');
	       $this->session->set_userdata('user_details', $sess_array);
	     }
	     return TRUE;
	   }

	   else
	   {
	   		$this->form_validation->set_message('check_database', 'Invalid email or password');
		     return FALSE;
		   
	     
	   }
	}

	public function forget()
	{
		$this->load->helper('url');

		$this->load->view('login_header');
		$this->load->view('forgot-password');
		$this->load->view('login_footer');
	}

	

	

	public function forget_password()
	{
		$this->load->model(array('login_model'));
		$data = $this->login_model->forget_password();

		$datas = json_decode($data);
		//print_r($datas->message);die;
		if(!empty($datas->errors)){

			$value['error']=$datas->errors;
			$this->load->view('login_header');
			$this->load->view('forgot-password',$value);
			$this->load->view('login_footer');

		}
		else if(!empty($datas->message)){

			$value['message']=$datas->message;
			$this->load->view('login_header');
			$this->load->view('forgot-password',$value);
			$this->load->view('login_footer');
		
		}
		else{

			$this->load->view('login_header');
			$this->load->view('forgot-password');
			$this->load->view('login_footer');

		}

	}
	public function change_password()
	{
		$this->load->model(array('login_model'));
		print $data = $this->login_model->change_password();

	}

	public function logout()
  	{
  		$session=$this->session->userdata('user_details');
  		
	 	if($this->session->userdata('user_details'))
   		{

			$this->session->unset_userdata('user_details');
		    session_destroy();
		   	// echo 1;die;

		    redirect('login', 'refresh');
		    
	    }
	    redirect('login', 'refresh');
   
  	}


	

}
?>