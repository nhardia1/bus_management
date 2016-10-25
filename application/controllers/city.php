<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class City extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('custom_model');
		$this->load->model('city_model');
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

		$array = $this->uri->uri_to_assoc();
		if(!empty($array['id']))
		{
			$id=$array['id'];

			$data['single_city_detail'] = $this->city_model->getCityDetail($id);

			$page_title['page_title']= $this->lang->line("edit").' '.$this->lang->line("city"); //'Edit City';
		}
		else
		{
			$page_title['page_title']= $this->lang->line("add").' '.$this->lang->line("city"); //'Add City';
		}

		$tmpl = array ( 'table_open'  => '<table id="big_table" class="display" cellspacing="0" width="100%">' );
        
        $this->table->set_template($tmpl); 
        
        //$this->table->set_heading('State Name','City Name','Action');
        $this->table->set_heading($this->lang->line("city_state_name"),$this->lang->line("city_city_name"),$this->lang->line("action"));
		
		$data['all_states'] = $this->city_model->get_all_states();

		$this->load->view('header',$page_title);
		$this->load->view('city',$data);
		$this->load->view('footer');
	}
	


	public function datatable()
    {
    	
		$table = 'city';
		$table2 = 'state';
		$this->datatables->select('city.id,state.name as state_name,city.name');       
		$this->datatables->from($table);
		$this->datatables->join($table2, 'city.state_id=state.id');

		$this->datatables->where('city.is_deleted',0);
		$this->datatables->unset_column('city.id');
		
		$this->load->helper('common_helper');
		$this->datatables->edit_column('city.name','$1','first_upper_case(city.name)');


		$this->datatables->add_column('edit', '<a name="'.$this->lang->line("city_edit").'"  class="edit_btn" href='.base_url().'index.php/city/index/id/$1><span class="imd imd-mode-edit"></span></a>
			&nbsp;

			<button ui-wave class="delete_btn" name="'.$this->lang->line("city_delete").'" data-toggle="modal" data-target="#myModal_$1" ><span class="imd imd-delete"></span></button>
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
						<div class="modal-footer"> <a href="'.base_url().'index.php/city/delete_city/id/$1">'.$this->lang->line("ok").'</a>
							<button type="button" class="btn btn-default" data-dismiss="modal">'.$this->lang->line("cancel").'</button>
						</div>
					</div>
				</div>
			</div>', 'city.id');   
        echo $this->datatables->generate();
    }




	public function city_list()
	{
		$this->custom_model->check_login_session();


		$arr = $this->city_model->getAll();
		$recordsTotal=count($arr);
		$recordsFiltered=$recordsTotal;
		//$result = json_encode($arr);
		$arr1= array(
		  "draw"=>1,
		  "recordsTotal"=> $recordsTotal,
		  "recordsFiltered"=>$recordsFiltered ,
		  "data"=>$arr
		);
		echo json_encode($arr1);
	}


	public function get_list()
	{
		$this->custom_model->check_login_session();

		$data = $this->city_model->getAll();
		print $result=json_encode($data);
		//print_r($result);die;
	}

	public function get_states_list()
	{
		$this->custom_model->check_login_session();

		$data = $this->city_model->get_all_states();
		print $result=json_encode($data);
		
	}



	public function insert_city()
	{
		$this->custom_model->check_login_session();

		print $data = $this->city_model->insert_city();

	}

	public function update_city()
	{
		$this->custom_model->check_login_session();

		print $data = $this->city_model->update_city();

	}

	public function delete_city($city_id)
	{
		$this->custom_model->check_login_session();

		$array = $this->uri->uri_to_assoc();
		$id=$array['id'];

		$this->city_model->delete_city($id);

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
}
?>