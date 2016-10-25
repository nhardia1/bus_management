<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('custom_model');
		$this->load->model('city_model');
		$this->load->model('staff_model');
		$this->load->helper('url');
		$this->load->helper('form');	
		
		$this->load->library('Datatables');
        $this->load->library('table');
		
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

		//$data['staff_list']=$this->staff_model->staff_list();

		$tmpl = array ( 'table_open'  => '<table id="big_table" class="display" cellspacing="0" width="100%">' );
       
        $this->table->set_template($tmpl); 
        
        //$this->table->set_heading('Staff Name','Contact Number','Address','Type','Action');
        $this->table->set_heading($this->lang->line("staff_name"),$this->lang->line("contact_number"),$this->lang->line("address"),$this->lang->line("cust_stafftype"),$this->lang->line("action"));

		$page_title['page_title'] = $this->lang->line("staff").' '.$this->lang->line("management"); //'Staff Management';

		$this->load->view('header',$page_title);
		$this->load->view('staff',$data);
		$this->load->view('footer');
	}
	

	public function datatable()
    {			
		
		$this->datatables->select('id,name,contact_number,address,staff_type');       
		$this->datatables->where('is_deleted',0);
		$this->datatables->unset_column('id');
		$this->datatables->from('staff');

		$this->load->helper('common_helper');
		//$this->datatables->edit_column('staff_type','$1','get_staff_type(staff_type)');
		$this->datatables->edit_column('name','$1','first_upper_case(name)');
		$this->datatables->edit_column('address','$1','first_upper_case(address)');

		$this->datatables->add_column('edit', '<a name="'.$this->lang->line("staff_edit_staff").'" class="edit_btn" href='.base_url().'index.php/staff/add_staff/id/$1><span class="imd imd-mode-edit"></span></a>
			&nbsp;

			<button ui-wave class="delete_btn" name="'.$this->lang->line("staff_delete_staff").'" data-toggle="modal" data-target="#myModal_$1" ><span class="imd imd-delete"></span></button>
			<div class="modal fade" id="myModal_$1" role="dialog">

				<div class="modal-dialog"> 

					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">'.$this->lang->line("confirmation").'</h4>
						</div>
						<div class="modal-body">
							<p>'.$this->lang->line("delete_confirm").'</p>
						</div>
						<div class="modal-footer"> <a href="'.base_url().'index.php/staff/delete_staff/id/$1">'.$this->lang->line("ok").'</a>
							<button type="button" class="btn btn-default" data-dismiss="modal">'.$this->lang->line("cancel").'</button>
						</div>
					</div>
				</div>
			</div>', 'id');   
        echo $this->datatables->generate();
    }
	
	

	public function add_staff()
	{

		$this->custom_model->check_login_session();

		$array = $this->uri->uri_to_assoc();
		if(!empty($array['id']))
		{
			$id=$array['id'];
			
			$data['single_staff_detail'] = $this->staff_model->get_staff_details($id);
			
			$data['id']=$id;
			
			$page_title['page_title'] = $this->lang->line("edit").' '.$this->lang->line("staff"); //'Edit Staff';
		}
		else
		{
			$page_title['page_title'] = $this->lang->line("add").' '.$this->lang->line("staff"); //'Add Staff';
		}

		$this->load->view('header',$page_title);
		$this->load->view('staff_add',$data);
		$this->load->view('footer');
	}

	public function insert_staff()
	{
		//print_r($_POST);die;
		$this->custom_model->check_login_session();

		print $data = $this->staff_model->insert_staff();

	}

	public function update_staff()
	{
		$this->custom_model->check_login_session();

		print $data = $this->staff_model->update_staff();

	}

	public function delete_staff($staff_id)
	{
		$this->custom_model->check_login_session();

		$array = $this->uri->uri_to_assoc();
		$id=$array['id'];

		$this->staff_model->delete_staff($id);

	}

	

 	public function get_city_details($city_id)
	{
		$this->custom_model->check_login_session();
		
		$data = $this->city_model->get_city_details($city_id);
		
		if(isset($data) && !empty($data))
		{
			echo json_encode(array("msg"=>"success","records"=>$data));
		}
		else
		{
			echo json_encode(array("msg"=>"Record not found for selected city.","records"=>""));
		}


	}

	public function insert_city_state()
	{
		$this->load->model(array('script'));
		print $data = $this->script->insert_city_state();

	}
	
	public function upload() {
    	if($this->input->post('upload')) {

        $config['upload_path'] = APPPATH . 'your upload folder name here/'; 
        $config['file_name'] = filename_here;
        $config['overwrite'] = TRUE;
        $config["allowed_types"] = 'jpg|jpeg|png|gif';
        $config["max_size"] = 1024;
        $config["max_width"] = 400;
        $config["max_height"] = 400;
        $this->load->library('upload', $config);

	        if(!$this->upload->do_upload()) {               
	            $this->data['error'] = $this->upload->display_errors();
	        } else {
	            //success                                      
        	}  
    	}
	}
		

	
}
?>