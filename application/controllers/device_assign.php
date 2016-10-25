<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Device_assign extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('custom_model');
		$this->load->model('device_assign_model');
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

		$page_title['page_title'] = $this->lang->line("device").' '.$this->lang->line("assigning").' '.$this->lang->line("management"); //'Device Assigning Management';	
		
		$tmpl = array ( 'table_open'  => '<table id="big_table" class="display" cellspacing="0" width="100%">' );
        
        $this->table->set_template($tmpl); 
        
       //$this->table->set_heading('Bus Name','Device Name','Device booked for date','Action');
        $this->table->set_heading($this->lang->line("bus_name"),$this->lang->line("device_name"),$this->lang->line("cust_devicebookeddate"),$this->lang->line("action"));

		$this->load->view('header',$page_title);
		
		$this->load->view('device_assign',$data);
		
		$this->load->view('footer');
	}

	
	public function datatable()
    {
    	
		$table = 'device_assigned';
		$table2 = 'bus';
		$table3 = 'devices';
		$this->datatables->select("device_assigned.id,device_assigned.bus_id,device_assigned.device_id,bus.name,bus.bus_number,devices.name as device_name,device_assigned.fordate");      
		$this->datatables->from($table);
		$this->datatables->join($table2, 'device_assigned.bus_id=bus.id');
		$this->datatables->join($table3, 'device_assigned.device_id=devices.id');

		$this->datatables->where('device_assigned.is_deleted',0);
		$this->datatables->where('bus.is_deleted',0);
		$this->datatables->where('devices.is_deleted',0);
		
		$this->load->helper('common_helper');
		$this->datatables->edit_column('device_assigned.fordate','$1','convert_datatable_date(device_assigned.fordate)');
		
		$this->datatables->edit_column('bus.name','$1','convert_datatable_busname(bus.name,bus.bus_number)');
		
		$this->datatables->unset_column('bus.bus_number');		
		$this->datatables->unset_column('device_assigned.id');
		$this->datatables->unset_column('device_assigned.bus_id');
		$this->datatables->unset_column('device_assigned.device_id');
		$this->datatables->add_column('edit', '<a name="'.$this->lang->line("edit_device_assign").'" class="edit_btn" href='.base_url().'index.php/device_assign/edit/bid/$2/did/$1><span class="imd imd-mode-edit"></span></a>
			&nbsp;

			<button ui-wave class="delete_btn" name="'.$this->lang->line("delete_device_assign").'" data-toggle="modal" data-target="#myModal_$1" ><span class="imd imd-delete"></span></button>
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
						<div class="modal-footer"> <a href="'.base_url().'index.php/device_assign/delete/id/$3">'.$this->lang->line("ok").'</a>
							<button type="button" class="btn btn-default" data-dismiss="modal">'.$this->lang->line("cancel").'</button>
						</div>
					</div>
				</div>
			</div>', 'device_assigned.device_id,device_assigned.bus_id,device_assigned.id');  

			
        echo $this->datatables->generate();
    }


	public function add()
	{
		$this->custom_model->check_login_session();

		$page_title['page_title'] = $this->lang->line("add").' '.$this->lang->line("device").' '.$this->lang->line("assign"); //'Add Device Assign';
				
		$data['all_device'] = $this->device_assign_model->get_all_device();
		
		$data['all_bus'] = $this->device_assign_model->get_all_bus();

		$this->load->view('header',$page_title);

		$this->load->view('device_assign_add',$data);

		$this->load->view('footer');
	}


	public function edit()
	{
		$this->custom_model->check_login_session();

		$param = $this->uri->uri_to_assoc();

		if(!empty($param['bid']) && !empty($param['did']))
		{
			$bid = $param['bid'];
			$did = $param['did'];
			
			$data = $this->device_assign_model->get_assign_device_detail($bid,$did);

			$page_title['page_title'] = $this->lang->line("edit").' '.$this->lang->line("device").' '.$this->lang->line("assign"); //'Edit Device Assign';

			$data['all_device'] = $this->device_assign_model->get_all_device();
		}
		else
		{
			redirect("home","refresh");
		}		
		
		$this->load->view('header',$page_title);

		$this->load->view('device_assign_edit',$data);

		$this->load->view('footer');
	}

	
	public function insert()
	{
		$this->custom_model->check_login_session();

		print $data = $this->device_assign_model->insert();

	}

	
	public function update()
	{
		$this->custom_model->check_login_session();

		print $data = $this->device_assign_model->update();

	}

	
	public function delete()
	{
		$this->custom_model->check_login_session();

		$param = $this->uri->uri_to_assoc();
		
		$id = $param['id'];

		$this->device_assign_model->delete($id);

	}

	
	public function check()
	{		
		if(isset($_POST['device']) && !empty($_POST['device']) && isset($_POST['date']) && !empty($_POST['date']) && isset($_POST['bus']) && !empty($_POST['bus']))
		{	
			$this->custom_model->check_login_session();

			$res = $this->device_assign_model->chk_device_assigned($_POST['device'],date("Y-m-d",strtotime($_POST['date'])),$_POST['bus']);

			if($res === false)
			{
				echo "Device is already assigned for selected date.";
			}
			else
			{
				echo "";
			}
		}
			
	}


	public function get_device_date()
	{
		$this->custom_model->check_login_session();

		$data = $this->device_assign_model->get_device_date();
		
		print $data;

	}

}
?>