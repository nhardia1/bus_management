<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';
class Webservices1_0 extends REST_Controller
{  
	/*<!--------------------------CONSTRUCTER FUNCTION------------------------------------------->*/
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('custom_model');
	}
	
	///////// Check for login of user is valid or not /////////////////
	function checkin_get()
	{
		header('Access-Control-Allow-Origin: *');
		$pincode=$this->get('pincode');
		//$deviceid=$this->get('deviceid');
		//$device_name=$this->get('device_name');
		if($pincode=='')
		{
			$data['type'] =   "Failure" ;
			$data['message'] =   "Pincode is missing"; 
			$this->response(array($data)); 
		}
		else
		{
			$pincodes=$this->custom_model->check_pin($pincode);
			if($pincodes!=''){
				$this->response(array('details'=>$pincodes));  
			}else{
				$this->response(array('status' => 'Pincode is not matched.'));
			}

		}
		
		///////// Check for login of sales pereson is valid or not /////////////////
	}
	///////// Check for login of user is valid or not /////////////////
	
	///////// list of arive magazines /////////////////
	
	
	
}  
?>