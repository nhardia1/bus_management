<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bus extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('bus_model');
		$this->load->model('custom_model');
		$this->load->model('staff_assign_model');
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
       // $this->table->set_heading('Bus Title','Bus Number','Chasis Number','Bus Capacity','Bus Model','Action');

        $this->table->set_heading($this->lang->line("bus_title") , $this->lang->line("bus_number") , $this->lang->line("chassis_number") , $this->lang->line("bus_capacity") , 'Operator',$this->lang->line("action"));
	
		$page_title['page_title']= $this->lang->line("bus").' '.$this->lang->line("management");
		

		$this->load->view('header',$page_title);

		$this->load->view('bus',$data);

		$this->load->view('footer');
	}

	public function datatable()
    { 
    
		$this->datatables->select('id,name,bus_number,chassis_number,capacity,operator_id');       
		$this->datatables->from('bus');
		$this->datatables->where('is_deleted',0);
		$this->datatables->unset_column('id');
		
		$this->load->helper('common_helper');
		$this->datatables->edit_column('name','$1','first_upper_case(name)');
		$this->datatables->edit_column('bus_number','$1','all_upper_case(bus_number)');
		$this->datatables->edit_column('chassis_number','$1','first_upper_case(chassis_number)');
		$this->datatables->edit_column('operator_id','$1','get_operator_name(operator_id)');
		


		$this->datatables->add_column('edit', '<a name="'.$this->lang->line("bus_edit_bus").'" class="edit_btn" href='.base_url().'index.php/bus/edit_bus/id/$1><span class="imd imd-mode-edit"></span></a>
			&nbsp;

			<button ui-wave class="delete_btn" name="'.$this->lang->line("bus_delete_bus").'" data-toggle="modal" data-target="#myModal_$1" ><span class="imd imd-delete"></span></button>
			
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
						<div class="modal-footer"> <a href="'.base_url().'index.php/bus/delete_bus/id/$1">'.$this->lang->line("ok").'</a>
							<button type="button" class="btn btn-default" data-dismiss="modal">'.$this->lang->line("cancel").'</button>
						</div>
					</div>
				</div>
			</div>', 'id');   
        echo $this->datatables->generate();
    }
    
	public function add_bus()
	{
		$this->custom_model->check_login_session();

		$page_title['page_title']=$this->lang->line("add").' '.$this->lang->line("bus"); //'Add Bus'; 
		$data['all_staff'] = $this->staff_assign_model->get_all_staff(); 

		$this->load->view('header',$page_title);

		$this->load->view('bus_add',$data);

		$this->load->view('footer');
	}

	public function insert_bus()
	{
		
		$this->custom_model->check_login_session();

		print $data = $this->bus_model->insert_bus();

	}

	public function edit_bus()
	{
		
		$array = $this->uri->uri_to_assoc();
		$id=$array['id'];
		$data['single_bus_detail'] = $this->bus_model->get_bus_details($id);
		$data['bus_photo_detail'] = $this->bus_model->get_bus_photo_details($id);
		$data['id'] = $id;
		$data['all_staff'] = $this->staff_assign_model->get_all_staff(); 
			
		$page_title['page_title']=$this->lang->line("edit").' '.$this->lang->line("bus");//'Edit Bus';

		$this->load->view('header',$page_title);
		$this->load->view('bus_edit',$data);
		$this->load->view('footer');

	}

	public function update_bus()
	{
		$this->custom_model->check_login_session();

		print $data = $this->bus_model->update_bus();

	}

	public function delete_bus()
	{
		$this->custom_model->check_login_session();

		$array = $this->uri->uri_to_assoc();
		$id=$array['id'];

		$this->bus_model->delete_bus($id);

	}

	
	public function save_bus()
	{
		$this->custom_model->check_login_session();
		$postdata = $_POST;
		//print_r($postdata);die;
		if(isset($postdata) && !empty($postdata))		
		{
			$valid = true;

			if($postdata['bus_name'] == "")
			{
				$msg = "Please enter bus title.";

				$valid = false;
			}
			elseif($postdata['bus_number'] == "")
			{
				$msg = "Please enter bus number.";

				$valid = false;
			}
			elseif($postdata['chassis_number'] == "")
			{
				$msg = "Please enter chassis number.";

				$valid = false;
			}
			elseif($postdata['bus_type'] == "")
			{
				$msg = "Please select bus type.";

				$valid = false;
			}
			elseif($postdata['bus_capacity'] == "")
			{
				$msg = "Please select bus capacity.";

				$valid = false;
			}
						

			if($valid)
			{				
				$bus_name = trim($postdata['bus_name']);
				$bus_number = trim($postdata['bus_number']);
				$chassis_number = trim($postdata['chassis_number']);
				$bus_type = trim($postdata['bus_type']);
				$bus_capacity = trim($postdata['bus_capacity']);

				$bus_model = trim($postdata['bus_model']);
				$bus_document = trim($postdata['bus_document']);			
				
				$bid = trim($postdata['eid']);

				if($bid>0 && $bid!="")
				{
					//$msg = $this->bus_model->edit_bus();
				}
				else
				{
					//$msg = $this->bus_model->add_bus();
				}	
			}	
		}
		else
		{
			$msg = "Please input data for processing.";
		}	

		echo $msg;
	}

	

	



}//End of class
?>