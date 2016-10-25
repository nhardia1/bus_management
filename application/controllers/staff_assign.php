<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff_assign extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
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

		$page_title['page_title'] = $this->lang->line("staff").' '.$this->lang->line("assigning").' '.$this->lang->line("management"); //'Staff Assigning Management';	
		
		$data['staff_assign_list'] = $this->staff_assign_model->assigned();
		
		/*$tmpl = array ( 'table_open'  => '<table id="big_table" class="display" cellspacing="0" width="100%">' );
        $this->table->set_template($tmpl);*/ 
        
        //$this->table->set_heading('Bus Name','Driver Name','Conductor Name','Helper Name','Other Name','Staff booked for date','Action');
        /*$this->table->set_heading($this->lang->line("bus_name"),$this->lang->line("driver_name"),$this->lang->line("conductor_name"),$this->lang->line("helper_name"),$this->lang->line("other_name"),$this->lang->line("cust_satffbookeddate"),$this->lang->line("action"));*/

		$this->load->view('header',$page_title);
		
		$this->load->view('staff_assign',$data);
		
		$this->load->view('footer');
	}

	
	/*public function datatable()
    {   	
		
		
		
		$this->datatables->select('id,bus_id,driver,conductor,helper,other,fordate');

		$this->datatables->from('staff_assigned');
		

		$this->load->helper('common_helper');
		$this->datatables->edit_column('bus_id','$1','convert_datatable_busname_number(bus_id)');
		$this->datatables->edit_column('driver','$1','convert_datatable_staff_name(driver)');
		$this->datatables->edit_column('conductor','$1','convert_datatable_staff_name(conductor)');
		$this->datatables->edit_column('helper','$1','convert_datatable_staff_name(helper)');
		$this->datatables->edit_column('other','$1','convert_datatable_staff_name(other)');
		$this->datatables->edit_column('fordate','$1','convert_datatable_date(fordate)');
		

		$this->datatables->where('is_deleted',0);
		
		$this->datatables->unset_column('id');		
		

		$this->datatables->add_column('edit', '<a name="'.$this->lang->line("edit_assigned_staff").'" class="edit_btn" href='.base_url().'index.php/staff_assign/edit/bid/$2/drivid/$3/conducid/$4/helpid/$5/otherid/$6><span class="imd imd-mode-edit"></span></a>
			&nbsp;

			<button ui-wave class="delete_btn" name="'.$this->lang->line("delete_assigned_staff").'" data-toggle="modal" data-target="#myModal_$1" ><span class="imd imd-delete"></span></button>
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
						<div class="modal-footer"> <a href="'.base_url().'index.php/staff_assign/delete/id/$1">'.$this->lang->line("ok").'</a>
							<button type="button" class="btn btn-default" data-dismiss="modal">'.$this->lang->line("cancel").'</button>
						</div>
					</div>
				</div>
			</div>', 'id,bus_id,driver,conductor,helper,other');   
        echo $this->datatables->generate();
    }*/
	
	public function add()
	{
		$this->custom_model->check_login_session();

		$page_title['page_title'] = $this->lang->line("add").' '.$this->lang->line("staff").' '.$this->lang->line("assign"); //'Add Staff Assign';
				
		$data['all_staff'] = $this->staff_assign_model->get_all_staff();
		
		$data['all_bus'] = $this->staff_assign_model->get_all_bus();

		$this->load->view('header',$page_title);

		$this->load->view('staff_assign_add',$data);

		$this->load->view('footer');
	}


	public function edit()
	{
		$this->custom_model->check_login_session();

		$param = $this->uri->uri_to_assoc();
		
		if(!empty($param['bid']) && !empty($param['drivid']) && !empty($param['conducid']))
		{
			$bid = $param['bid'];
			$drivid = $param['drivid'];
			$conducid = $param['conducid'];
			$opepid = $param['opepid'];
			
			$data = $this->staff_assign_model->get_assign_staff_detail($bid,$drivid,$conducid,$opepid);

			$page_title['page_title'] = $this->lang->line("edit").' '.$this->lang->line("staff").' '.$this->lang->line("assign"); //'Edit Staff Assign';

			$data['all_staff'] = $this->staff_assign_model->get_all_staff();
		}
		else
		{
			redirect("home","refresh");
		}		
		
		$this->load->view('header',$page_title);

		$this->load->view('staff_assign_edit',$data);

		$this->load->view('footer');
	}

	
	public function insert()
	{
		$this->custom_model->check_login_session();

		print $data = $this->staff_assign_model->insert();

	}

	
	public function update()
	{
		$this->custom_model->check_login_session();

		print $data = $this->staff_assign_model->update();

	}

	
	public function delete()
	{
		$this->custom_model->check_login_session();

		$param = $this->uri->uri_to_assoc();
		
		$id = $param['id'];

		$this->staff_assign_model->delete($id);

	}

	
	public function check()
	{		
		if(isset($_POST['driver']) && !empty($_POST['driver']) && isset($_POST['date']) && !empty($_POST['date']) && isset($_POST['bus']) && !empty($_POST['bus']) && isset($_POST['conductor']) && !empty($_POST['conductor']) && isset($_POST['helper']) && !empty($_POST['helper']))
		{	
			$this->custom_model->check_login_session();

			$res = $this->staff_assign_model->chk_staff_assigned($_POST['driver'],$_POST['conductor'],date("Y-m-d",strtotime($_POST['date'])),$_POST['bus']);

			if($res === false)
			{
				echo "Staff is already assigned for selected date.";
			}
			else
			{
				echo "";
			}
		}
			
	}

}
?>