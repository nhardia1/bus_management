<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(1);
class Seat extends CI_Controller 
{

	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('seat_model');
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
	public function index(){
		$this->custom_model->check_login_session();

		//$data['staff_list']=$this->staff_model->staff_list();

		$tmpl = array ( 'table_open'  => '<table id="big_table" class="display" cellspacing="0" width="100%">' );
       
        $this->table->set_template($tmpl); 
        
        //$this->table->set_heading('Staff Name','Contact Number','Address','Type','Action');
        $this->table->set_heading($this->lang->line("seat_template"),$this->lang->line("seat_type"),$this->lang->line("seat_cocach_type"),$this->lang->line("seat_capacity"),$this->lang->line("action"));

		$page_title['page_title'] = $this->lang->line("seat").' '.$this->lang->line("configuration"); //'Staff Management';

		$this->load->view('header',$page_title);
		$this->load->view('seats',$data);
		$this->load->view('footer');
	}
	public function datatable()
    {			
		
		$this->datatables->select('id,template_name,seat_type_name,seat_cocach_type,seat_capacity');       
		$this->datatables->where('is_deleted',0);
		$this->datatables->unset_column('id');
		$this->datatables->from('seat_configuration');

		$this->load->helper('common_helper');
		$this->datatables->edit_column('seat_type_name','$1','get_configure_type(seat_type_name)');
		$this->datatables->edit_column('seat_cocach_type','$1','get_configure_type(seat_cocach_type)');
		$this->datatables->add_column('edit', '<a name="'.$this->lang->line("staff_edit_staff").'" class="edit_btn" href='.base_url().'index.php/seat/add_template/id/$1><span class="imd imd-mode-edit"></span></a>
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
						<div class="modal-footer"> <a href="'.base_url().'index.php/seat/delete_template/id/$1">'.$this->lang->line("ok").'</a>
							<button type="button" class="btn btn-default" data-dismiss="modal">'.$this->lang->line("cancel").'</button>
						</div>
					</div>
				</div>
			</div>', 'id');   
        echo $this->datatables->generate();
    }
	public function add_template(){

		$this->custom_model->check_login_session();

		$array = $this->uri->uri_to_assoc();
		if(!empty($array['id']))
		{
			$id=$array['id'];
			
			$data['single_seat_detail'] = $this->seat_model->get_seat_details($id);
			
			$data['id']=$id;
			
			$page_title['page_title'] = $this->lang->line("edit").' '.$this->lang->line("seat"); //'Edit Staff';
		}
		else
		{
			$page_title['page_title'] = $this->lang->line("add").' '.$this->lang->line("seat"); //'Add Staff';
		}

		$this->load->view('header',$page_title);
		$this->load->view('add_seat_template',$data);
		$this->load->view('footer');

	}
	public function insert_seat()
	{
		//print_r($_POST);die;
		$this->custom_model->check_login_session();

		print $data = $this->seat_model->insert_seat();

	}

	public function update_seat()
	{
		$this->custom_model->check_login_session();

		print $data = $this->seat_model->update_seat();

	}

	
	

	



}
?>