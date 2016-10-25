<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Device extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('device_model');
		$this->load->model('custom_model');
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

		$tmpl = array ( 'table_open'  => '<table id="big_table" class="display" cellspacing="0" width="100%">' );
       
        $this->table->set_template($tmpl); 
        
       // $this->table->set_heading('Device ID','Device Name','Action');
        $this->table->set_heading($this->lang->line("device_id"),$this->lang->line("device_name"),$this->lang->line("action"));

		$this->load->view('header');
		$this->load->view('device',$data);

		$this->load->view('footer');
	}


	public function datatable()
    {			
		$this->datatables->select('id,device_id,name');       
		$this->datatables->where('is_deleted',0);
		$this->datatables->unset_column('id');
		$this->datatables->from('devices');

		$this->load->helper('common_helper');
		$this->datatables->edit_column('name','$1','first_upper_case(name)');
		
		$this->datatables->add_column('edit', '<a name="'.$this->lang->line("edit_device").'" class="edit_btn" href='.base_url().'index.php/device/add_device/id/$1><span class="imd imd-mode-edit"></span></a>
			&nbsp;

			<button ui-wave class="delete_btn" name="'.$this->lang->line("delete_device").'" data-toggle="modal" data-target="#myModal_$1" ><span class="imd imd-delete"></span></button>
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
						<div class="modal-footer"> <a href="'.base_url().'index.php/device/delete_device/id/$1">'.$this->lang->line("ok").'</a>
							<button type="button" class="btn btn-default" data-dismiss="modal">'.$this->lang->line("cancel").'</button>
						</div>
					</div>
				</div>
			</div>', 'id');   
        echo $this->datatables->generate();
    }


	
	public function add_device()
	{
		//echo "hi..";die;
		$this->custom_model->check_login_session();

		$array = $this->uri->uri_to_assoc();
		if(!empty($array['id']))
		{
			$id=$array['id'];
			$data['device_list'] = $data = $this->device_model->device_list();
			$data['single_device_detail'] = $this->device_model->get_device_details($id);
			
			$data['id']=$id;

		}
		//print_r($data);
		$this->load->view('header');

		$this->load->view('device_add',$data);

		$this->load->view('footer');
	}

	public function insert_device()
	{
		
		$this->custom_model->check_login_session();

		print $data = $this->device_model->insert_device();

	}

	

	public function update_device()
	{
		$this->custom_model->check_login_session();

		print $data = $this->device_model->update_device();

	}

	public function delete_device()
	{
		$this->custom_model->check_login_session();

		$array = $this->uri->uri_to_assoc();
		$id=$array['id'];
		
		$this->device_model->delete_device($id);

	}

	public function get_device_name()
	{
		
		$this->custom_model->check_login_session();

		print $data = $this->device_model->get_device_name();

	}

	
	
	

	



}//End of class
?>