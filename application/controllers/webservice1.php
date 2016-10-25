<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Webservice1 extends CI_Controller 
{
	var $tables= array('bus_location','num_of_passenger','running_status','seat_booking_history');
	public function __construct() {
		parent::__construct();
		ini_set('max_execution_time',70000);
		ini_set("memory_limit",-1);
		$this->load->helper("url_helper");
		$this->load->library("zip");
		$this->load->model('sync_model');
		$this->load->model('custom_model');
		$this->load->model('webservice_model');
	}

	//Saving Device Name
	public function device_name($device_id)
	{
		if(isset($device_id) && !empty($device_id))
		{
			$response = $this->webservice_model->device_name($device_id);

			if(isset($response) && !empty($response))
			{				
				echo json_encode(array("id"=>$device_id,"name"=>$response,"response"=>true));
			}
			else
			{				
				echo json_encode(array("id"=>$device_id,"name"=>"","response"=>false));
			}
		}

	}
	//Saving Device Name



	public function checkin($pincode,$deviceid)
	{
		if($pincode=='')
		{
			$data['type'] =   "Failure" ;
			$data['message'] =   "Pincode is missing"; 
			echo json_encode(array("message"=>"Pincode is missing"));
		}
		else
		{
			$pincodes=$this->custom_model->check_pin($pincode);
			if($pincodes!=''){
				if($pincodes[0]->staff_type=='Conductor'){
					$conductor_id=$pincodes[0]->id;
					$fordate = date(CHANGE_INTO_DATE_FORMAT);
					$checkStaff="select bus_route_validity.bus_id,bus_route_validity.route_id from staff_assigned inner join bus_route_validity on staff_assigned.bus_id = bus_route_validity.bus_id where staff_assigned.conductor=$conductor_id AND staff_assigned.is_deleted=0 AND staff_assigned.fordate='$fordate' AND (valid_from <= '$fordate' AND valid_to >= '$fordate')";
					$checkStaffData = $this->sync_model->exec_sql($checkStaff) ;
					if(!empty($checkStaffData)){
						echo json_encode(array('details'=>$pincodes,'message'=>'true','zip'=>'true'));
					}else{
						echo json_encode(array('details'=>$pincodes,'message'=>'true','zip'=>'false'));
					}	
				}else{
					echo json_encode(array('details'=>$pincodes,'message'=>'true','zip'=>'true'));

				}
			}else{
				echo json_encode(array("message"=>"Pincode is not matched"));
			}
		}
		///////// Check for login of sales pereson is valid or not /////////////////
	}
	public function sync_data_agency($agency_id,$device_id,$last_date,$pincode){

		if($agency_id!='' && $device_id!='' && $last_date!='' && $pincode!=''){
			if(isset($last_date) && $last_date!='0')
			{	
				$last_modified_date = date(CREATED_DATE,strtotime(str_replace("%20"," ",$last_date)));
			}
			else
			{
				$last_modified_date = '';
			}


			if($last_modified_date != '' && $last_modified_date != '0000-00-00 00:00:00')
			{
					$append = " AND last_modified_date >= '$last_modified_date' ";
			}
			else
			{
					$append = "";
			}

			$operator_ids=get_operator_by_agency($agency_id);
			
			$buses=$this->sync_model->get_bus_by_operator($operator_ids);


			$busids=$buses[0]->buses;
			
			$time_stamp=time().'_'.$operator_id.'_app';
			$folder_name = SYNC_DOWNLOAD_PATH."/".$time_stamp;	
			$this->create_directory($folder_name);
			$busSql = "SELECT * FROM `bus` WHERE `id` IN(".$busids.") $append";
			$busDatas = $this->sync_model->utf_exec_sql($busSql) ;
			foreach ($busDatas as $data) {

				$busDetailsData[]= "SELECT \"".$data->id."\", \"".$data->name."\",\"".$data->operator_id."\" ,\"".$data->bus_number."\", \"".$data->chassis_number."\", \"".$data->type."\", \"".$data->capacity."\", \"".$data->model."\", \"".$data->document."\", \"".$data->registration_image."\", \"".$data->permit_image."\", \"".$data->insurance_image."\",\"".$data->created_date."\" ,\"".$data->created_by."\", \"".$data->created_date."\", \"".$data->last_modified_by."\", \"".$data->resource_type."\", \"".$data->is_deleted."\"";
				
			}
			$this->createJsonFile($folder_name.'/', "bus", 'id,name,operator_id,bus_number,chassis_number,type,capacity,model,document,registration_image,permit_image,insurance_image,created_by,created_date,last_modified_by,last_modified_date,resource_type,is_deleted', $busDetailsData,'');



			$busPhotosSql = "SELECT * FROM `bus_photos` WHERE `bus_id` IN(".$busids.") $append";
			$busPhotos = $this->sync_model->exec_sql($busPhotosSql) ;
			if(!empty($busPhotos)){
				foreach ($busPhotos as $data) {
					$busPhotosData[]= "SELECT \"".$data['id']."\", \"".$data['bus_id']."\", \"".$data['image']."\", \"".$data['created_date']."\", \"".$data['created_by']."\", \"".$data['last_modified_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
					
				}
				
					$this->createJsonFile($folder_name.'/', "bus_photos", 'id,bus_id,image,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $busPhotosData,'');
			}

			
			$busRouteValiditySql = "SELECT * FROM `bus_route_validity` WHERE `bus_id` IN(".$busids.")";
			$busRoutsValidity = $this->sync_model->exec_sql($busRouteValiditySql) ;
			foreach ($busRoutsValidity as $data) {
				$route[]=$data['route_id'];
				$route_id=implode();
				$busRoutsValidityData[]= "SELECT \"".$data['id']."\", \"".$data['user_id']."\", \"".$data['bus_id']."\", \"".$data['route_id']."\", \"".$data['valid_from']."\",\"".$data['valid_to']."\",\"".$data['max_trip']."\", \"".$data['created_date']."\", \"".$data['created_by']."\", \"".$data['last_modified_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
				
			}
			$route_ids=implode(',', $route);
			$this->createJsonFile($folder_name.'/', "bus_route_validity",'id,user_id,bus_id,route_id,valid_from,valid_to,max_trip,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $busRoutsValidityData,'');




			$busRoutesSql = "SELECT * FROM `route` WHERE `id` IN(".$route_ids.") $append";
			$busRoutes = $this->sync_model->exec_sql($busRoutesSql) ;
			if(!empty($busRoutes)){
				foreach ($busRoutes as $data) {

					$busRoutesData[]= "SELECT \"".$data['id']."\", \"".$data['user_id']."\", \"".$data['name']."\", \"".$data['source_state']."\", \"".$data['source_city']."\", \"".$data['destination_state']."\", \"".$data['destination_city']."\", \"".$data['distance_from_source']."\",\"".$data['stoppage_state']."\", \"".$data['status']."\", \"".$data['created_date']."\",\"".$data['created_by']."\", \"".$data['created_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
					
				}
				
				$this->createJsonFile($folder_name.'/', "route", 'id,user_id,name,source_state,source_city,destination_state,destination_city,distance_from_source,stoppage_state,status,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $busRoutesData,'');
			}


			$fareRoutesSql = "SELECT * FROM `route_fare` WHERE `route_id` IN(".$route_ids.") $append";
			$fareRoutes = $this->sync_model->exec_sql($fareRoutesSql) ;
			if(!empty($fareRoute)){
				foreach ($fareRoutes as $data) {

					$fareRoute[]= "SELECT \"".$data['id']."\", \"".$data['user_id']."\", \"".$data['route_id']."\", \"".$data['from_city']."\", \"".$data['to_city']."\", \"".$data['fare']."\", \"".$data['status']."\",\"".$data['created_date']."\",\"".$data['created_by']."\", \"".$data['created_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
					
				}
				
				$this->createJsonFile($folder_name.'/', "route_fare", 'id,user_id,route_id,from_city,to_city,fare,status,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $fareRoute,'');
			}



			$busRoutesStopageSql = "SELECT * FROM `route_stoppage` WHERE `route_id` IN(".$route_ids.") $append";
			$busRoutesStopages = $this->sync_model->exec_sql($busRoutesStopageSql) ;
			if(!empty($busRoutesStopageData)){
				foreach ($busRoutesStopages as $data) {

					$busRoutesStopageData[]= "SELECT \"".$data['id']."\", \"".$data['user_id']."\", \"".$data['route_id']."\", \"".$data['city_id']."\", \"".$data['stoppage_order']."\", \"".$data['status']."\",\"".$data['created_date']."\",\"".$data['created_by']."\", \"".$data['created_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
					
				}
				
				$this->createJsonFile($folder_name.'/', "route_stoppage", 'id,user_id,route_id,city_id,stoppage_order,status,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $busRoutesStopageData,'');
			}

			$citySql = "SELECT * FROM `city` WHERE is_deleted = 0 $append";
			$cities = $this->sync_model->exec_sql($citySql) ;
			if(!empty($cityData)){
				foreach ($cities as $data) {

					$cityData[]= "SELECT \"".$data['id']."\", \"".$data['user_id']."\", \"".$data['state_id']."\", \"".$data['name']."\", \"".$data['lattitude']."\", \"".$data['longitude']."\",\"".$data['status']."\",\"".$data['created_date']."\",\"".$data['created_by']."\", \"".$data['created_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
					
				}
				
				$this->createJsonFile($folder_name.'/', "city", 'id,user_id,state_id,name,lattitude,longitude,	status,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $cityData,'');
			}
			$stateSql = "SELECT * FROM `state` WHERE is_deleted = 0 $append";
			$states = $this->sync_model->utf_exec_sql($stateSql) ;
			if(!empty($states)){
				foreach ($states as $data) {

					$stateData[]= "SELECT \"".$data->id."\", \"".$data->user_id."\", \"".$data->name."\",\"".$data->status."\",\"".$data->created_date."\",\"".$data->created_by."\", \"".$data->created_date."\", \"".$data->last_modified_by."\", \"".$data->resource_type."\", \"".$data->is_deleted."\"";
					
				}
				
					$this->createJsonFile($folder_name.'/', "state", 'id,user_id,name,status,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $stateData,'');
			}
			


			$staffAssignedSql = "SELECT * FROM `staff_assigned` WHERE `bus_id` IN(".$busids.") AND is_deleted = 0 $append";
			$staffAssigneds = $this->sync_model->exec_sql($staffAssignedSql) ;
			if(!empty($staffAssigneds)){
				foreach ($staffAssigneds as $data) {

					$staffAssignedData[]= "SELECT \"".$data['id']."\", \"".$data['bus_id']."\", \"".$data['driver']."\", \"".$data['conductor']."\", \"".$data['operator']."\", \"".$data['other']."\",\"".$data['fordate']."\",\"".$data['created_date']."\",\"".$data['created_by']."\", \"".$data['last_modified_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
					
				}
				
				$this->createJsonFile($folder_name.'/', "staff_assigned", 'id,bus_id,driver,conductor,operator,other,fordate,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $staffAssignedData,'');
			}


			$staffSql = "SELECT * FROM `staff` WHERE `staff_type_num` IN(2,3,4) $append";
			$staffDatas = $this->sync_model->utf_exec_sql($staffSql) ;
			if(!empty($staffDatas)){
				foreach ($staffDatas as $data) {

					$staffData[]= "SELECT \"".$data->id."\", \"".$data->name."\",\"".$data->contact_number."\" ,\"".$data->address."\", \"".$data->staff_type."\", \"".$data->staff_type_num."\", \"".$data->staff_pin."\", \"".$data->plain_pin."\", \"".$data->agency_id."\", \"".$data->license_number."\", \"".$data->expiry_date."\", \"".$data->image_path."\",\"".$data->profile_image."\",\"".$data->created_date."\" ,\"".$data->created_by."\", \"".$data->created_date."\", \"".$data->last_modified_by."\", \"".$data->resource_type."\", \"".$data->is_deleted."\"";
					
				}
				
					$this->createJsonFile($folder_name.'/', "staff", 'id,name,contact_number,address,staff_type,staff_type_num,staff_pin,plain_pin,agency_id,license_number,expiry_date,image_path,profile_image,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $staffData,'');
			}


			$userRolesSql = "SELECT * FROM `user_roles`";
			$userRoleDatas = $this->sync_model->exec_sql($userRolesSql) ;
			foreach ($userRoleDatas as $data) {
				$userRoleData[]= "SELECT \"".$data['role_id']."\", \"".$data['user_type']."\", \"".$data['can_book_seats']."\", \"".$data['can_add_luggage']."\"";
			}
			if($last_date==0){
				$this->createJsonFile($folder_name.'/', "user_roles", 'role_id,user_type,can_book_seats,can_add_luggage', $userRoleData,'');
			}
			


			$seatTemplatesSql = "SELECT * FROM `seat_configuration`";
			$seatTemplateDatas = $this->sync_model->exec_sql($seatTemplatesSql) ;
			foreach ($seatTemplateDatas as $data) {
				$seatTemplateData[]= "SELECT \"".$data['id']."\", \"".$data['template_name']."\", \"".$data['seat_type_name']."\", \"".$data['seat_cocach_type']."\", \"".$data['seat_capacity']."\", \"".$data['seat_allocation']."\",\"".$data['created_date']."\",\"".$data['created_by']."\", \"".$data['last_modified_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
			}
			if($last_date==0){
			$this->createJsonFile($folder_name.'/', "seat_configuration", 'id,template_name,seat_type_name,seat_cocach_type,seat_capacity,seat_allocation,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $seatTemplateData,'');
			}


			$busSeatSql = "SELECT * FROM `bus_seats` WHERE template_id=8";
			$busSeatDatas = $this->sync_model->exec_sql($busSeatSql) ;
			foreach ($busSeatDatas as $data) {
				$busSeatData[]= "SELECT \"".$data['seat_id']."\", \"".$data['template_id']."\", \"".$data['seat_number']."\", \"".$data['seat_type']."\", \"".$data['seat_status']."\",\"".$data['created_date']."\",\"".$data['created_by']."\", \"".$data['last_modified_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
			}
			if($last_date==0){
			$this->createJsonFile($folder_name.'/', "bus_seats", 'seat_id,template_id,seat_number,seat_type,seat_status,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $busSeatData,'');
			}

			$seatTypeSql = "SELECT * FROM `seat_type`";
			$seatTypeDatas = $this->sync_model->exec_sql($seatTypeSql) ;
			foreach ($seatTypeDatas as $data) {
				$seatTypeData[]= "SELECT \"".$data['id']."\", \"".$data['type']."\", \"".$data['value']."\"";
			}
			if($last_date==0){
				$this->createJsonFile($folder_name.'/', "seat_type", 'id,type,value', $seatTypeData,'');	
			}
			



			$currentDate=date("Y-m-d");
			$bookingHistorySql="SELECT * FROM seat_booking_history WHERE bus_id IN(".$busids.") AND created_date='$currentDate' $append";
			$bookingHistoryDatas = $this->sync_model->exec_sql($bookingHistorySql) ;
			if(!empty($bookingHistoryDatas)){
				foreach ($bookingHistoryDatas as $data) {

					if($data['resource_type']=="web"){
							$bookingData[]= "SELECT \"".$data['booking_id']."\", \"".$data['seat_id']."\", \"".$data['seat_type']."\", \"".$data['bus_id']."\", \"".$data['booked_by_user_id']."\",\"".$data['booked_by_user_type']."\",\"".$data['booked_to_passenger_name']."\",\"".$data['booked_to_passanger_type']."\",\"".$data['booked_to_passenger_phone']."\",\"".$data['booked_source_city']."\",\"".$data['booked_destination_city']."\",\"".$data['booked_source_lattitude']."\",\"".$data['booked_source_longitude']."\",\"".$data['booked_destination_lattitude']."\",\"".$data['booked_destination_longitude']."\",\"".$data['route_id']."\",\"".$data['fare_received']."\",\"".$data['trip_num']."\",\"".$data['is_cancel']."\",\"".$data['cancel_reason']."\",\"".$data['created_date']."\",\"".$data['created_by']."\", \"".$data['last_modified_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
							}
				}
				$this->createJsonFile($folder_name.'/', "seat_booking_history", 'booking_id,seat_id,seat_type,bus_id,booked_by_user_id,booked_by_user_type,booked_to_passenger_name,booked_to_passanger_type,booked_to_passenger_phone,booked_source_city,booked_destination_city,booked_source_lattitude,booked_source_longitude,booked_destination_lattitude,booked_destination_longitude,route_id,fare_received,trip_num,is_cancel,cancel_reason,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $bookingData,'');
			}

			
			//Route Timings
			$routeTimingSql="SELECT * FROM bus_timing WHERE bus_id IN(".$busids.") $append";
			$routeTimingDatas = $this->sync_model->exec_sql($routeTimingSql) ;
			if(!empty($routeTimingDatas)){
				foreach ($routeTimingDatas as $data) {

					
							$routeTimingData[]= "SELECT \"".$data['id']."\", \"".$data['user_id']."\", \"".$data['bus_id']."\", \"".$data['route_id']."\", \"".$data['city_id']."\",\"".$data['journey_date']."\",\"".$data['arrival_date']."\",\"".$data['arrival_hour']."\",\"".$data['arrival_minute']."\",\"".$data['arrival_am_pm']."\",\"".$data['departure_date']."\",\"".$data['departure_hour']."\",\"".$data['departure_minute']."\",\"".$data['departure_am_pm']."\",\"".$data['trip_type']."\",\"".$data['created_date']."\",\"".$data['created_by']."\", \"".$data['last_modified_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
							
				}
				
				$this->createJsonFile($folder_name.'/', "bus_timing", 'id,user_id,bus_id,route_id,city_id,journey_date,arrival_date,arrival_hour,arrival_minute,arrival_am_pm,departure_date,departure_hour,departure_minute,departure_am_pm,trip_type,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $routeTimingData,'');
			}
			//Route Timings


			//Luggage Details
			$currentDate=date("Y-m-d");
			$luggbookingHistorySql="SELECT * FROM luggage_booking_history WHERE bus_id IN(".$busids.") AND is_deleted=0 AND created_date='$currentDate' $append";
			$luggbookingHistoryDatas = $this->sync_model->exec_sql($luggbookingHistorySql) ;
			if(!empty($luggbookingHistoryDatas)){
				foreach ($luggbookingHistoryDatas as $data) {

					if($data['resource_type']=="web"){
							$luggbookingData[]= "SELECT \"".$data['id']."\", \"".$data['luggage_id']."\", \"".$data['bus_id']."\", \"".$data['booked_by_user_id']."\",\"".$data['booked_by_user_type']."\",\"".$data['booked_to_owner_phone']."\",\"".$data['luggage_size']."\",\"".$data['booked_source_city']."\",\"".$data['booked_destination_city']."\",\"".$data['route_id']."\",\"".$data['created_date']."\",\"".$data['created_by']."\", \"".$data['last_modified_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
							}
				}
				$this->createJsonFile($folder_name.'/', "luggage_booking_history", 'id,luggage_id,bus_id,booked_by_user_id,booked_by_user_type,booked_to_owner_phone,luggage_size,booked_source_city,booked_destination_city,route_id,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $luggbookingData,'');
			}
			//Luggage Details



			// create zip
			$destination = SYNC_DOWNLOAD_PATH."/".$time_stamp.'.zip';		
			$source = SYNC_DOWNLOAD_PATH."/".$time_stamp."/";
			
			// insert query in export table
			$current_url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

			$data = array(
				'path' => $destination,
				'device_id' =>  $device_id, 
				'exportDataType' =>  'app_agency_download',
				'sync_url' =>  $current_url,
				'created_date' => $this->give_current_date_time(),			
				'fordate' =>  $record_fordate			
			    );
			$this->sync_model->insertData("exportdata",$data);

			$this->create_last_modified_date_file($folder_name,$pincode);

			$this->create_zip($source,$destination,$time_stamp);
		
		}

	}
	public function sync_data_operator($operator_id,$device_id,$last_date,$pincode){

			

			if(isset($last_date) && $last_date!='0')
			{	
				$last_modified_date = date(CREATED_DATE,strtotime(str_replace("%20"," ",$last_date)));
			}
			else
			{
				$last_modified_date = '';
			}


			if($last_modified_date != '' && $last_modified_date != '0000-00-00 00:00:00')
			{
					$append = " AND last_modified_date >= '$last_modified_date' ";
			}
			else
			{
					$append = "";
			}

			//Getting Buses by Operator Id

			$buses=$this->sync_model->get_bus_by_operator($operator_id);
			$busids=$buses[0]->buses;
			$time_stamp=time().'_'.$operator_id.'_app';
			$folder_name = SYNC_DOWNLOAD_PATH."/".$time_stamp;	
			$this->create_directory($folder_name);
			$busSql = "SELECT * FROM `bus` WHERE `id` IN(".$busids.") $append";
			$busDatas = $this->sync_model->utf_exec_sql($busSql) ;
			foreach ($busDatas as $data) {

				$busDetailsData[]= "SELECT \"".$data->id."\", \"".$data->name."\",\"".$data->operator_id."\" ,\"".$data->bus_number."\", \"".$data->chassis_number."\", \"".$data->type."\", \"".$data->capacity."\", \"".$data->model."\", \"".$data->document."\", \"".$data->registration_image."\", \"".$data->permit_image."\", \"".$data->insurance_image."\",\"".$data->created_date."\" ,\"".$data->created_by."\", \"".$data->created_date."\", \"".$data->last_modified_by."\", \"".$data->resource_type."\", \"".$data->is_deleted."\"";
				
			}
			$this->createJsonFile($folder_name.'/', "bus", 'id,name,operator_id,bus_number,chassis_number,type,capacity,model,document,registration_image,permit_image,insurance_image,created_by,created_date,last_modified_by,last_modified_date,resource_type,is_deleted', $busDetailsData,'');



			$busPhotosSql = "SELECT * FROM `bus_photos` WHERE `bus_id` IN(".$busids.") $append";
			$busPhotos = $this->sync_model->exec_sql($busPhotosSql) ;
			foreach ($busPhotos as $data) {
				$busPhotosData[]= "SELECT \"".$data['id']."\", \"".$data['bus_id']."\", \"".$data['image']."\", \"".$data['created_date']."\", \"".$data['created_by']."\", \"".$data['last_modified_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
				
			}
			if(!empty($busPhotosData)){
				$this->createJsonFile($folder_name.'/', "bus_photos", 'id,bus_id,image,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $busPhotosData,'');
			}
			
			
			$busRouteValiditySql = "SELECT * FROM `bus_route_validity` WHERE `bus_id` IN(".$busids.")";
			$busRoutsValidity = $this->sync_model->exec_sql($busRouteValiditySql) ;
			foreach ($busRoutsValidity as $data) {
				$route[]=$data['route_id'];
				$route_id=implode();
				$busRoutsValidityData[]= "SELECT \"".$data['id']."\", \"".$data['user_id']."\", \"".$data['bus_id']."\", \"".$data['route_id']."\", \"".$data['valid_from']."\",\"".$data['valid_to']."\",\"".$data['max_trip']."\", \"".$data['created_date']."\", \"".$data['created_by']."\", \"".$data['last_modified_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
				
			}
			$route_ids=implode(',', $route);
			$this->createJsonFile($folder_name.'/', "bus_route_validity",'id,user_id,bus_id,route_id,valid_from,valid_to,max_trip,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $busRoutsValidityData,'');




			$busRoutesSql = "SELECT * FROM `route` WHERE `id` IN(".$route_ids.") $append";
			$busRoutes = $this->sync_model->exec_sql($busRoutesSql) ;
			foreach ($busRoutes as $data) {

				$busRoutesData[]= "SELECT \"".$data['id']."\", \"".$data['user_id']."\", \"".$data['name']."\", \"".$data['source_state']."\", \"".$data['source_city']."\", \"".$data['destination_state']."\", \"".$data['destination_city']."\",\"".$data['distance_from_source']."\",\"".$data['stoppage_state']."\", \"".$data['status']."\", \"".$data['created_date']."\",\"".$data['created_by']."\", \"".$data['created_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
				
			}
			if(!empty($busRoutesData)){
			$this->createJsonFile($folder_name.'/', "route", 'id,user_id,name,source_state,source_city,destination_state,destination_city,distance_from_source,stoppage_state,status,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $busRoutesData,'');
			}


			$fareRoutesSql = "SELECT * FROM `route_fare` WHERE `route_id` IN(".$route_ids.") $append";
			$fareRoutes = $this->sync_model->exec_sql($fareRoutesSql) ;
			foreach ($fareRoutes as $data) {

				$fareRoute[]= "SELECT \"".$data['id']."\", \"".$data['user_id']."\", \"".$data['route_id']."\", \"".$data['from_city']."\", \"".$data['to_city']."\", \"".$data['fare']."\", \"".$data['status']."\",\"".$data['created_date']."\",\"".$data['created_by']."\", \"".$data['created_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
				
			}
			if(!empty($fareRoute)){
			$this->createJsonFile($folder_name.'/', "route_fare", 'id,user_id,route_id,from_city,to_city,fare,status,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $fareRoute,'');
			}



			$busRoutesStopageSql = "SELECT * FROM `route_stoppage` WHERE `route_id` IN(".$route_ids.") $append";
			$busRoutesStopages = $this->sync_model->exec_sql($busRoutesStopageSql) ;
			foreach ($busRoutesStopages as $data) {

				$busRoutesStopageData[]= "SELECT \"".$data['id']."\", \"".$data['user_id']."\", \"".$data['route_id']."\", \"".$data['city_id']."\", \"".$data['stoppage_order']."\", \"".$data['status']."\",\"".$data['created_date']."\",\"".$data['created_by']."\", \"".$data['created_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
				
			}
			if(!empty($busRoutesStopageData)){
			$this->createJsonFile($folder_name.'/', "route_stoppage", 'id,user_id,route_id,city_id,stoppage_order,status,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $busRoutesStopageData,'');
			}

			$citySql = "SELECT * FROM `city` WHERE is_deleted = 0 $append";
			$cities = $this->sync_model->exec_sql($citySql) ;
			foreach ($cities as $data) {

				$cityData[]= "SELECT \"".$data['id']."\", \"".$data['user_id']."\", \"".$data['state_id']."\", \"".$data['name']."\", \"".$data['lattitude']."\", \"".$data['longitude']."\",\"".$data['status']."\",\"".$data['created_date']."\",\"".$data['created_by']."\", \"".$data['created_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
				
			}
			if(!empty($cityData)){
			$this->createJsonFile($folder_name.'/', "city", 'id,user_id,state_id,name,lattitude,longitude,	status,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $cityData,'');
			}
			$stateSql = "SELECT * FROM `state` WHERE is_deleted = 0 $append";
			$states = $this->sync_model->utf_exec_sql($stateSql) ;
			foreach ($states as $data) {

				$stateData[]= "SELECT \"".$data->id."\", \"".$data->user_id."\", \"".$data->name."\",\"".$data->status."\",\"".$data->created_date."\",\"".$data->created_by."\", \"".$data->created_date."\", \"".$data->last_modified_by."\", \"".$data->resource_type."\", \"".$data->is_deleted."\"";
				
			}
			if(!empty($stateData)){
				$this->createJsonFile($folder_name.'/', "state", 'id,user_id,name,status,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $stateData,'');
			}
			


			$staffAssignedSql = "SELECT * FROM `staff_assigned` WHERE `bus_id` IN(".$busids.") AND is_deleted = 0 $append";
			$staffAssigneds = $this->sync_model->exec_sql($staffAssignedSql) ;
			foreach ($staffAssigneds as $data) {

				$staffAssignedData[]= "SELECT \"".$data['id']."\", \"".$data['bus_id']."\", \"".$data['driver']."\", \"".$data['conductor']."\", \"".$data['operator']."\", \"".$data['other']."\",\"".$data['fordate']."\",\"".$data['created_date']."\",\"".$data['created_by']."\", \"".$data['last_modified_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
				
			}
			if(!empty($staffAssignedData)){
			$this->createJsonFile($folder_name.'/', "staff_assigned", 'id,bus_id,driver,conductor,operator,other,fordate,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $staffAssignedData,'');
			}


			$staffSql = "SELECT * FROM `staff` WHERE `staff_type_num` IN(2,3,4) $append";
			$staffDatas = $this->sync_model->utf_exec_sql($staffSql) ;
			foreach ($staffDatas as $data) {

				$staffData[]= "SELECT \"".$data->id."\", \"".$data->name."\",\"".$data->contact_number."\" ,\"".$data->address."\", \"".$data->staff_type."\", \"".$data->staff_type_num."\", \"".$data->staff_pin."\", \"".$data->plain_pin."\", \"".$data->agency_id."\", \"".$data->license_number."\", \"".$data->expiry_date."\", \"".$data->image_path."\",\"".$data->profile_image."\",\"".$data->created_date."\" ,\"".$data->created_by."\", \"".$data->created_date."\", \"".$data->last_modified_by."\", \"".$data->resource_type."\", \"".$data->is_deleted."\"";
				
			}
			if(!empty($staffData)){
				$this->createJsonFile($folder_name.'/', "staff", 'id,name,contact_number,address,staff_type,staff_type_num,staff_pin,plain_pin,agency_id,license_number,expiry_date,image_path,profile_image,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $staffData,'');
			}


			$userRolesSql = "SELECT * FROM `user_roles`";
			$userRoleDatas = $this->sync_model->exec_sql($userRolesSql) ;
			foreach ($userRoleDatas as $data) {
				$userRoleData[]= "SELECT \"".$data['role_id']."\", \"".$data['user_type']."\", \"".$data['can_book_seats']."\", \"".$data['can_add_luggage']."\"";
			}
			if($last_date==0){
				$this->createJsonFile($folder_name.'/', "user_roles", 'role_id,user_type,can_book_seats,can_add_luggage', $userRoleData,'');
			}
			


			$seatTemplatesSql = "SELECT * FROM `seat_configuration`";
			$seatTemplateDatas = $this->sync_model->exec_sql($seatTemplatesSql) ;
			foreach ($seatTemplateDatas as $data) {
				$seatTemplateData[]= "SELECT \"".$data['id']."\", \"".$data['template_name']."\", \"".$data['seat_type_name']."\", \"".$data['seat_cocach_type']."\", \"".$data['seat_capacity']."\", \"".$data['seat_allocation']."\",\"".$data['created_date']."\",\"".$data['created_by']."\", \"".$data['last_modified_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
			}
			if($last_date==0){
			$this->createJsonFile($folder_name.'/', "seat_configuration", 'id,template_name,seat_type_name,seat_cocach_type,seat_capacity,seat_allocation,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $seatTemplateData,'');
			}


			$busSeatSql = "SELECT * FROM `bus_seats` WHERE template_id=8";
			$busSeatDatas = $this->sync_model->exec_sql($busSeatSql) ;
			foreach ($busSeatDatas as $data) {
				$busSeatData[]= "SELECT \"".$data['seat_id']."\", \"".$data['template_id']."\", \"".$data['seat_number']."\", \"".$data['seat_type']."\", \"".$data['seat_status']."\",\"".$data['created_date']."\",\"".$data['created_by']."\", \"".$data['last_modified_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
			}
			if($last_date==0){
			$this->createJsonFile($folder_name.'/', "bus_seats", 'seat_id,template_id,seat_number,seat_type,seat_status,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $busSeatData,'');
			}

			$seatTypeSql = "SELECT * FROM `seat_type`";
			$seatTypeDatas = $this->sync_model->exec_sql($seatTypeSql) ;
			foreach ($seatTypeDatas as $data) {
				$seatTypeData[]= "SELECT \"".$data['id']."\", \"".$data['type']."\", \"".$data['value']."\"";
			}
			if($last_date==0){
				$this->createJsonFile($folder_name.'/', "seat_type", 'id,type,value', $seatTypeData,'');	
			}
			



			$currentDate=date("Y-m-d");
			$bookingHistorySql="SELECT * FROM seat_booking_history WHERE bus_id IN(".$busids.") AND created_date='$currentDate' $append";
			$bookingHistoryDatas = $this->sync_model->exec_sql($bookingHistorySql) ;
			foreach ($bookingHistoryDatas as $data) {

				if($data['resource_type']=="web"){
						$bookingData[]= "SELECT \"".$data['booking_id']."\", \"".$data['seat_id']."\", \"".$data['seat_type']."\", \"".$data['bus_id']."\", \"".$data['booked_by_user_id']."\",\"".$data['booked_by_user_type']."\",\"".$data['booked_to_passenger_name']."\",\"".$data['booked_to_passanger_type']."\",\"".$data['booked_to_passenger_phone']."\",\"".$data['booked_source_city']."\",\"".$data['booked_destination_city']."\",\"".$data['booked_source_lattitude']."\",\"".$data['booked_source_longitude']."\",\"".$data['booked_destination_lattitude']."\",\"".$data['booked_destination_longitude']."\",\"".$data['route_id']."\",\"".$data['fare_received']."\",\"".$data['trip_num']."\",\"".$data['is_cancel']."\",\"".$data['cancel_reason']."\",\"".$data['created_date']."\",\"".$data['created_by']."\", \"".$data['last_modified_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
						}
			}
			if(!empty($bookingData)){
			$this->createJsonFile($folder_name.'/', "seat_booking_history", 'booking_id,seat_id,seat_type,bus_id,booked_by_user_id,booked_by_user_type,booked_to_passenger_name,booked_to_passanger_type,booked_to_passenger_phone,booked_source_city,booked_destination_city,booked_source_lattitude,booked_source_longitude,booked_destination_lattitude,booked_destination_longitude,route_id,fare_received,trip_num,is_cancel,cancel_reason,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $bookingData,'');
			}

			//Route Timings
			$routeTimingSql="SELECT * FROM bus_timing WHERE bus_id IN(".$busids.") $append";
			$routeTimingDatas = $this->sync_model->exec_sql($routeTimingSql) ;
			foreach ($routeTimingDatas as $data) {

				
						$routeTimingData[]= "SELECT \"".$data['id']."\", \"".$data['user_id']."\", \"".$data['bus_id']."\", \"".$data['route_id']."\", \"".$data['city_id']."\",\"".$data['journey_date']."\",\"".$data['arrival_date']."\",\"".$data['arrival_hour']."\",\"".$data['arrival_minute']."\",\"".$data['arrival_am_pm']."\",\"".$data['departure_date']."\",\"".$data['departure_hour']."\",\"".$data['departure_minute']."\",\"".$data['departure_am_pm']."\",\"".$data['trip_type']."\",\"".$data['created_date']."\",\"".$data['created_by']."\", \"".$data['last_modified_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
						
			}
			if(!empty($routeTimingData)){
			$this->createJsonFile($folder_name.'/', "bus_timing", 'id,user_id,bus_id,route_id,city_id,journey_date,arrival_date,arrival_hour,arrival_minute,arrival_am_pm,departure_date,departure_hour,departure_minute,departure_am_pm,trip_type,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $routeTimingData,'');
			}
			//Route Timings


			//Luggage Details
			$currentDate=date("Y-m-d");
			$luggbookingHistorySql="SELECT * FROM luggage_booking_history WHERE bus_id IN(".$busids.") AND is_deleted=0 AND created_date='$currentDate' $append";
			$luggbookingHistoryDatas = $this->sync_model->exec_sql($luggbookingHistorySql) ;
			foreach ($luggbookingHistoryDatas as $data) {

				if($data['resource_type']=="web"){
						$luggbookingData[]= "SELECT \"".$data['id']."\", \"".$data['luggage_id']."\", \"".$data['bus_id']."\", \"".$data['booked_by_user_id']."\",\"".$data['booked_by_user_type']."\",\"".$data['booked_to_owner_phone']."\",\"".$data['luggage_size']."\",\"".$data['booked_source_city']."\",\"".$data['booked_destination_city']."\",\"".$data['route_id']."\",\"".$data['created_date']."\",\"".$data['created_by']."\", \"".$data['last_modified_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
						}
			}
			if(!empty($luggbookingData)){
			$this->createJsonFile($folder_name.'/', "luggage_booking_history", 'id,luggage_id,bus_id,booked_by_user_id,booked_by_user_type,booked_to_owner_phone,luggage_size,booked_source_city,booked_destination_city,route_id,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $luggbookingData,'');
			}
			//Luggage Details


			// create zip
			$destination = SYNC_DOWNLOAD_PATH."/".$time_stamp.'.zip';		
			$source = SYNC_DOWNLOAD_PATH."/".$time_stamp."/";
			
			// insert query in export table
			$current_url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

			$data = array(
				'path' => $destination,
				'device_id' =>  $device_id, 
				'exportDataType' =>  'app_download',
				'sync_url' =>  $current_url,
				'created_date' => $this->give_current_date_time(),			
				'fordate' =>  $record_fordate			
			    );
			
			$this->sync_model->insertData("exportdata",$data);

			$this->create_last_modified_date_file($folder_name,$pincode);

			$this->create_zip($source,$destination,$time_stamp);
			
	}
	function sync_data_conductor($conductor_id,$device_id,$last_date,$pincode){

		$time_stamp=time().'_app';
		$folder_name = SYNC_DOWNLOAD_PATH."/".$time_stamp;	
		
		$this->create_directory($folder_name);


		if(isset($last_date) && $last_date!='0')
		{	
			$last_modified_date = date(CREATED_DATE,strtotime(str_replace("%20"," ",$last_date)));
		}
		else
		{
			$last_modified_date = '';
		}


		if($last_modified_date != '' && $last_modified_date != '0000-00-00 00:00:00')
		{
				$append = " AND last_modified_date >= '$last_modified_date' ";
		}
		else
		{
				$append = "";
		}

		$fordate = date(CHANGE_INTO_DATE_FORMAT);


		$checkStaff="select bus_route_validity.bus_id,bus_route_validity.route_id,staff_assigned.driver,staff_assigned.conductor from staff_assigned inner join bus_route_validity on staff_assigned.bus_id = bus_route_validity.bus_id where staff_assigned.conductor=$conductor_id AND staff_assigned.fordate='$fordate' AND staff_assigned.is_deleted=0 AND (valid_from <= '$fordate' AND valid_to >= '$fordate')";
		$checkStaffData = $this->sync_model->exec_sql($checkStaff) ;
		
		if(!empty($checkStaffData)){
			$bus_id=$checkStaffData[0]['bus_id'];
			$route_id=$checkStaffData[0]['route_id'];
			$driver_id=$checkStaffData[0]['driver'];



			$busSql = "SELECT * FROM `bus` WHERE `id` IN(".$bus_id.") $append";
			$busDatas = $this->sync_model->utf_exec_sql($busSql) ;
			if(!empty($busDatas)){
			foreach ($busDatas as $data) {
				$busDetailsData[]= "SELECT \"".$data->id."\", \"".$data->name."\",\"".$data->operator_id."\" ,\"".$data->bus_number."\", \"".$data->chassis_number."\", \"".$data->type."\", \"".$data->capacity."\", \"".$data->model."\", \"".$data->document."\", \"".$data->registration_image."\", \"".$data->permit_image."\", \"".$data->insurance_image."\",\"".$data->created_date."\" ,\"".$data->created_by."\", \"".$data->created_date."\", \"".$data->last_modified_by."\", \"".$data->resource_type."\", \"".$data->is_deleted."\"";
				
			}
			$this->createJsonFile($folder_name.'/', "bus", 'id,name,operator_id,bus_number,chassis_number,type,capacity,model,document,registration_image,permit_image,insurance_image,created_by,created_date,last_modified_by,last_modified_date,resource_type,is_deleted', $busDetailsData,'');
			}

			
			$busPhotosSql = "SELECT * FROM `bus_photos` WHERE `bus_id` IN(".$bus_id.") $append";
			$busPhotos = $this->sync_model->exec_sql($busPhotosSql) ;
			if(!empty($busPhotos)){
			foreach ($busPhotos as $data) {
				$busPhotosData[]= "SELECT \"".$data['id']."\", \"".$data['bus_id']."\", \"".$data['image']."\", \"".$data['created_date']."\", \"".$data['created_by']."\", \"".$data['last_modified_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
				
			}
			$this->createJsonFile($folder_name.'/', "bus_photos", 'id,bus_id,image,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $busPhotosData,'');
			}


		
			
			$staffAssignedSql = "SELECT * FROM staff_assigned  WHERE conductor =$conductor_id AND  is_deleted = 0 AND fordate='$fordate' $append";
				$staffAssigneds = $this->sync_model->exec_sql($staffAssignedSql) ;
				if(!empty($staffAssigneds)){
				foreach ($staffAssigneds as $data) {
					$staffAssignedData[]= "SELECT \"".$data['id']."\", \"".$data['bus_id']."\", \"".$data['driver']."\", \"".$data['conductor']."\", \"".$data['operator']."\", \"".$data['other']."\",\"".$data['fordate']."\",\"".$data['created_date']."\",\"".$data['created_by']."\", \"".$data['last_modified_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
					
				}
			$this->createJsonFile($folder_name.'/', "staff_assigned", 'id,bus_id,driver,conductor,operator,other,fordate,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $staffAssignedData,'');
				}


			$staffsql = "SELECT * FROM staff  WHERE id IN($conductor_id,$driver_id) AND  is_deleted = 0 $append";
			$staffDatas = $this->sync_model->utf_exec_sql($staffsql) ;
			if(!empty($staffDatas)){
				foreach ($staffDatas as $data) {

					$staffData[]= "SELECT \"".$data->id."\", \"".$data->name."\",\"".$data->contact_number."\" ,\"".$data->address."\", \"".$data->staff_type."\", \"".$data->staff_type_num."\", \"".$data->staff_pin."\", \"".$data->plain_pin."\", \"".$data->agency_id."\", \"".$data->license_number."\", \"".$data->expiry_date."\", \"".$data->image_path."\",\"".$data->profile_image."\",\"".$data->created_date."\" ,\"".$data->created_by."\", \"".$data->created_date."\", \"".$data->last_modified_by."\", \"".$data->resource_type."\", \"".$data->is_deleted."\"";
					
				}
			$this->createJsonFile($folder_name.'/', "staff", 'id,name,contact_number,address,staff_type,staff_type_num,staff_pin,plain_pin,agency_id,license_number,expiry_date,image_path,profile_image,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $staffData,'');
			}

			$busRouteValiditySql = "SELECT * FROM `bus_route_validity` WHERE `bus_id` IN(".$bus_id.") $append";
				$busRoutsValidity = $this->sync_model->exec_sql($busRouteValiditySql) ;
				foreach ($busRoutsValidity as $data) {
					$busRoutsValidityData[]= "SELECT \"".$data['id']."\", \"".$data['user_id']."\", \"".$data['bus_id']."\", \"".$data['route_id']."\", \"".$data['valid_from']."\",\"".$data['valid_to']."\",\"".$data['max_trip']."\", \"".$data['created_date']."\", \"".$data['created_by']."\", \"".$data['last_modified_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
					
				}
				$route_ids=implode(',', $route);
				$this->createJsonFile($folder_name.'/', "bus_route_validity",'id,user_id,bus_id,route_id,valid_from,valid_to,max_trip,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $busRoutsValidityData,'');

				$busRoutesSql = "SELECT * FROM `route` WHERE `id` IN(".$route_id.") $append";
				$busRoutes = $this->sync_model->exec_sql($busRoutesSql) ;
				foreach ($busRoutes as $data) {

					$busRoutesData[]= "SELECT \"".$data['id']."\", \"".$data['user_id']."\", \"".$data['name']."\", \"".$data['source_state']."\", \"".$data['source_city']."\", \"".$data['destination_state']."\", \"".$data['destination_city']."\",\"".$data['distance_from_source']."\", \"".$data['stoppage_state']."\", \"".$data['status']."\", \"".$data['created_date']."\",\"".$data['created_by']."\", \"".$data['created_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
					
				}
				$this->createJsonFile($folder_name.'/', "route", 'id,user_id,name,source_state,source_city,destination_state,destination_city,distance_from_source,stoppage_state,status,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $busRoutesData,'');


				$fareRoutesSql = "SELECT * FROM `route_fare` WHERE `route_id` IN(".$route_id.") $append";
				$fareRoutes = $this->sync_model->exec_sql($fareRoutesSql) ;
				if(!empty($fareRoutes)){
					foreach ($fareRoutes as $data) {

					$fareRoute[]= "SELECT \"".$data['id']."\", \"".$data['user_id']."\", \"".$data['route_id']."\", \"".$data['from_city']."\", \"".$data['to_city']."\", \"".$data['fare']."\", \"".$data['status']."\",\"".$data['created_date']."\",\"".$data['created_by']."\", \"".$data['created_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
					
				}
				$this->createJsonFile($folder_name.'/', "route_fare", 'id,user_id,route_id,from_city,to_city,fare,status,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $fareRoute,'');

				}
				



				$busRoutesStopageSql = "SELECT * FROM `route_stoppage` WHERE `route_id` IN(".$route_id.") $append";
				$busRoutesStopages = $this->sync_model->exec_sql($busRoutesStopageSql) ;
				
				if(!empty($busRoutesStopages)){
				foreach ($busRoutesStopages as $data) {

					$busRoutesStopageData[]= "SELECT \"".$data['id']."\", \"".$data['user_id']."\", \"".$data['route_id']."\", \"".$data['city_id']."\", \"".$data['stoppage_order']."\", \"".$data['status']."\",\"".$data['created_date']."\",\"".$data['created_by']."\", \"".$data['created_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
					
				}
				$this->createJsonFile($folder_name.'/', "route_stoppage", 'id,user_id,route_id,city_id,stoppage_order,status,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $busRoutesStopageData,'');
				}


				$citySql = "SELECT * FROM `city` WHERE is_deleted = 0 $append";
				$cities = $this->sync_model->exec_sql($citySql) ;
				if(!empty($cities)){
					foreach ($cities as $data) {

					$cityData[]= "SELECT \"".$data['id']."\", \"".$data['user_id']."\", \"".$data['state_id']."\", \"".$data['name']."\", \"".$data['lattitude']."\", \"".$data['longitude']."\",\"".$data['status']."\",\"".$data['created_date']."\",\"".$data['created_by']."\", \"".$data['created_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
					
				}
				$this->createJsonFile($folder_name.'/', "city", 'id,user_id,state_id,name,lattitude,longitude,	status,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $cityData,'');

				}
				

				$stateSql = "SELECT * FROM `state` WHERE is_deleted = 0 $append";
				$states = $this->sync_model->utf_exec_sql($stateSql) ;
				if(!empty($states)){
					foreach ($states as $data) {

					$stateData[]= "SELECT \"".$data->id."\", \"".$data->user_id."\", \"".$data->name."\",\"".$data->status."\",\"".$data->created_date."\",\"".$data->created_by."\", \"".$data->created_date."\", \"".$data->last_modified_by."\", \"".$data->resource_type."\", \"".$data->is_deleted."\"";
					
				}
				$this->createJsonFile($folder_name.'/', "state", 'id,user_id,name,status,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $stateData,'');


				}
				

				$seatTemplatesSql = "SELECT * FROM `seat_configuration`";
				$seatTemplateDatas = $this->sync_model->exec_sql($seatTemplatesSql) ;
				foreach ($seatTemplateDatas as $data) {
					$seatTemplateData[]= "SELECT \"".$data['id']."\", \"".$data['template_name']."\", \"".$data['seat_type_name']."\", \"".$data['seat_cocach_type']."\", \"".$data['seat_capacity']."\", \"".$data['seat_allocation']."\",\"".$data['created_date']."\",\"".$data['created_by']."\", \"".$data['last_modified_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
				}
				if($last_date==0){
					$this->createJsonFile($folder_name.'/', "seat_configuration", 'id,template_name,seat_type_name,seat_cocach_type,seat_capacity,seat_allocation,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $seatTemplateData,'');

				}
				


				$busSeatSql = "SELECT * FROM `bus_seats` WHERE template_id=8";
				$busSeatDatas = $this->sync_model->exec_sql($busSeatSql) ;
				foreach ($busSeatDatas as $data) {
					$busSeatData[]= "SELECT \"".$data['seat_id']."\", \"".$data['template_id']."\", \"".$data['seat_number']."\", \"".$data['seat_type']."\", \"".$data['seat_status']."\",\"".$data['created_date']."\",\"".$data['created_by']."\", \"".$data['last_modified_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
				}
				if($last_date==0){
					$this->createJsonFile($folder_name.'/', "bus_seats", 'seat_id,template_id,seat_number,seat_type,seat_status,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $busSeatData,'');

				}
				$seatTypeSql = "SELECT * FROM `seat_type`";
				$seatTypeDatas = $this->sync_model->exec_sql($seatTypeSql) ;
				foreach ($seatTypeDatas as $data) {
					$seatTypeData[]= "SELECT \"".$data['id']."\", \"".$data['type']."\", \"".$data['value']."\"";
				}
				if($last_date==0){
				  $this->createJsonFile($folder_name.'/', "seat_type", 'id,type,value', $seatTypeData,'');
				}
				


				//Seat Booking History
				$currentDate=date("Y-m-d");
				$bookingHistorySql="SELECT * FROM seat_booking_history WHERE bus_id IN(".$bus_id.") AND created_date='$currentDate' $append";
				$bookingHistoryDatas = $this->sync_model->exec_sql($bookingHistorySql) ;
				if(!empty($bookingHistoryDatas)){
				foreach ($bookingHistoryDatas as $data) {

					if($data['resource_type']=="web"){
							$bookingData[]= "SELECT \"".$data['booking_id']."\", \"".$data['seat_id']."\", \"".$data['seat_type']."\", \"".$data['bus_id']."\", \"".$data['booked_by_user_id']."\",\"".$data['booked_by_user_type']."\",\"".$data['booked_to_passenger_name']."\",\"".$data['booked_to_passanger_type']."\",\"".$data['booked_to_passenger_phone']."\",\"".$data['booked_source_city']."\",\"".$data['booked_destination_city']."\",\"".$data['booked_source_lattitude']."\",\"".$data['booked_source_longitude']."\",\"".$data['booked_destination_lattitude']."\",\"".$data['booked_destination_longitude']."\",\"".$data['route_id']."\",\"".$data['fare_received']."\",\"".$data['trip_num']."\",\"".$data['is_cancel']."\",\"".$data['cancel_reason']."\",\"".$data['created_date']."\",\"".$data['created_by']."\", \"".$data['last_modified_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
							}
				}
				
				$this->createJsonFile($folder_name.'/', "seat_booking_history", 'booking_id,seat_id,seat_type,bus_id,booked_by_user_id,booked_by_user_type,booked_to_passenger_name,booked_to_passanger_type,booked_to_passenger_phone,booked_source_city,booked_destination_city,booked_source_lattitude,booked_source_longitude,booked_destination_lattitude,booked_destination_longitude,route_id,fare_received,trip_num,is_cancel,cancel_reason,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $bookingData,'');
				}
				//Seat Booking History


				//Route Timings
				$routeTimingSql="SELECT * FROM bus_timing WHERE bus_id IN(".$bus_id.") $append";
				$routeTimingDatas = $this->sync_model->exec_sql($routeTimingSql) ;
				if(!empty($routeTimingDatas)){
				foreach ($routeTimingDatas as $data) {

					
							$routeTimingData[]= "SELECT \"".$data['id']."\", \"".$data['user_id']."\", \"".$data['bus_id']."\", \"".$data['route_id']."\", \"".$data['city_id']."\",\"".$data['journey_date']."\",\"".$data['arrival_date']."\",\"".$data['arrival_hour']."\",\"".$data['arrival_minute']."\",\"".$data['arrival_am_pm']."\",\"".$data['departure_date']."\",\"".$data['departure_hour']."\",\"".$data['departure_minute']."\",\"".$data['departure_am_pm']."\",\"".$data['trip_type']."\",\"".$data['created_date']."\",\"".$data['created_by']."\", \"".$data['last_modified_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
							
				}
				
				$this->createJsonFile($folder_name.'/', "bus_timing", 'id,user_id,bus_id,route_id,city_id,journey_date,arrival_date,arrival_hour,arrival_minute,arrival_am_pm,departure_date,departure_hour,departure_minute,departure_am_pm,trip_type,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $routeTimingData,'');
				}
				//Route Timings


				//Luggage Details
				$currentDate=date("Y-m-d");
				$luggbookingHistorySql="SELECT * FROM luggage_booking_history WHERE bus_id IN(".$bus_id.") AND is_deleted=0 AND created_date='$currentDate' $append";
				$luggbookingHistoryDatas = $this->sync_model->exec_sql($luggbookingHistorySql) ;
				if(!empty($luggbookingHistoryDatas)){
				foreach ($luggbookingHistoryDatas as $data) {

					if($data['resource_type']=="web"){
							$luggbookingData[]= "SELECT \"".$data['id']."\", \"".$data['luggage_id']."\", \"".$data['bus_id']."\", \"".$data['booked_by_user_id']."\",\"".$data['booked_by_user_type']."\",\"".$data['booked_to_owner_phone']."\",\"".$data['luggage_size']."\",\"".$data['booked_source_city']."\",\"".$data['booked_destination_city']."\",\"".$data['route_id']."\",\"".$data['created_date']."\",\"".$data['created_by']."\", \"".$data['last_modified_date']."\", \"".$data['last_modified_by']."\", \"".$data['resource_type']."\", \"".$data['is_deleted']."\"";
							}
				}
				
				$this->createJsonFile($folder_name.'/', "luggage_booking_history", 'id,luggage_id,bus_id,booked_by_user_id,booked_by_user_type,booked_to_owner_phone,luggage_size,booked_source_city,booked_destination_city,route_id,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted', $luggbookingData,'');
				}
				//Luggage Details


				$destination = SYNC_DOWNLOAD_PATH."/".$time_stamp.'.zip';		
				$source = SYNC_DOWNLOAD_PATH."/".$time_stamp."/";
				
				// insert query in export table
				$current_url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

				$data = array(
					'path' => $destination,
					'device_id' =>  $device_id, 
					'exportDataType' =>  'app_condutor_download',
					'sync_url' =>  $current_url,
					'created_date' => $this->give_current_date_time(),			
					'fordate' =>  $record_fordate			
				    );
				
				$this->sync_model->insertData("exportdata",$data);

				$this->create_last_modified_date_file($folder_name,$pincode);

				$this->create_zip($source,$destination,$time_stamp);

		}else{
			echo json_encode(array("message"=>"No Bus is assigned"));
		}
		
	}
	function createJsonFile($path, $tablename, $column_names, $dataarray, $filePrefix = '_add'){
		//echo "$path, $tablename, $column_names, $dataarray";
		$count = 0;
		$data_string = 'INSERT OR REPLACE INTO '.$tablename .' ('.$column_names.') '.$dataarray[0];
		$data = array();
		for ($i=1;$i<count ($dataarray);$i++)
		{
			$count++;
			if ($count==500){
				$data[] = array("sqlData"=> $data_string);
				$data_string = 'INSERT OR REPLACE INTO '.$tablename.' ('.$column_names.') '.$dataarray[$i];
				$i++;
				$count = 1;
			}
			if (isset ($dataarray[$i]))
			{
				$data_string .= " UNION ALL " . $dataarray[$i];
			}
		}
		if ($count != 1 || count ($dataarray) < 500)
		{
			$data[] = array("sqlData"=> $data_string);
		}
		$json = json_encode ($data,JSON_UNESCAPED_UNICODE);
		//echo "<br/>";
		//echo $data_string;
		/*if ($tablename == "project_inspections")
		{
			print_r ($dataarray);
			print_r ($data);
			echo $json;
			die;
		}*/
		createFile($tablename . $filePrefix . '.json', $json, $path);//Write File Here 	
	}
	function upload_database()
	{
		$upload_file_name = "upload_database";
		$userid = $this->input->get_post('user_id');
		$device_type = $this->input->get_post('device_type');

		$device_token = $this->input->get_post('device_token');
		$device_model = $this->input->get_post('device_model');
		$device_registration_id = $this->input->get_post('device_registration_id');
		$device_date = $this->input->get_post('device_date');
		$os_version = $this->input->get_post('os_version');

		if(!$device_type)
		{
			$device_type='Android';
		}

		if(!isset($_FILES[$upload_file_name]))
		{
			$output = array(
				'status' => false,
				'message' => 'File data empty : '.$upload_file_name,
				'data' => ''
				);
			echo json_encode($output);
			die;
		}
		
		if($_FILES[$upload_file_name]["error"] > 0)
		{
			$output = array(
				'status' => false,
				'message' => 'Zip Download Failed',
				'data' => ''
				);
			echo json_encode($output);
			die;
		}
		else
		{
			$zipName=time().'_'.$upload_file_name;
			$destination=SYNC_UPLOAD_PATH .'/'. $zipName.'.zip';
			if(move_uploaded_file($_FILES[$upload_file_name]["tmp_name"], $destination))
			{

				//$this->importdataUploads($destination,$userid,$device_type,$upload_file_name,$device_registration_id,$device_date,$device_model,$os_version);
				
				$this->extractZip(SYNC_UPLOAD_PATH .'/'. $zipName.'.zip', SYNC_UPLOAD_PATH .'/'. $zipName.'/');	

				SYNC_UPLOAD_PATH.'/'.$zipName.'/'.$upload_file_name.'/';
				$folder = @opendir(SYNC_UPLOAD_PATH.'/'.$zipName.'/'.$upload_file_name.'/');		
				
				if(!$folder)
				{
					$output = array(
						'status' => false,
						'message' => 'Zip missing the folder : '.$upload_file_name,
						'data' => 'FolderMissing'
						);
					echo json_encode($output);
					die;					
				}

				$file_types = array("json");
				$output = array();
				
				foreach ($this->tables as $file)
				{					
					$jsonFile = SYNC_UPLOAD_PATH."/$zipName/$upload_file_name/$file.json";
					
					if (file_exists($jsonFile)) 
					{  
						$fileContent = @file_get_contents($jsonFile);

						
												
						if (version_compare(PHP_VERSION, '5.4.0', '>='))
						{
							// if on PHP 5.4 or newer, use JSON "bigint" option
							$data = json_decode ($fileContent, true, 512, JSON_BIGINT_AS_STRING);
							
						}
						else
						{
							// otherwise try workaround (convert number to string first)
							$json2 = $this->fixJson ($fileContent);
							
							$data = json_decode ($json2,true);
						}
						
						$table = $file;


						$var = $this->save_json_data($table,$data,SYNC_UPLOAD_PATH.'/'.$zipName.'/'.$upload_file_name);

						if(!empty($var)){
							$output[$table] = $var;

						}else{
							$output[$table] = array();
						}	
						
					}				

					
				}
				
				//unlink($destination);			

				echo json_encode($output);
			}
		}
	}
	function create_directory($path)
	{	
		if(!file_exists($path))
		{
			$old_umask = umask(0);

			@mkdir($path,0777,true);

			umask($old_umask);
		}
	}
	function create_zip($source, $destination , $zip_file_name)
	{
			
		$folder_in_zip = "";			
		
		
		$this->zip->get_files_from_folder($source, $folder_in_zip); 
		
		//$this->Delete($source);

		
		
		$this->zip->download($zip_file_name.'.zip');	

		
	}
	function extractZip($filePath, $extractPath)
	{
		//$extractPath = Path Begain with current directory
		$zip = new ZipArchive;
		$res = $zip->open($filePath);
		if($res === TRUE)
		{
			$zip->extractTo($extractPath);
			$zip->close();
			return true;
		}
		else
		{
			return false;
		}
	}
	function copyFilestoFolder($sFolder, $dFolder)
	{
		$folder = opendir($sFolder.'/');
		$file_types = array("jpg", "jpeg", "gif", "png", "txt", "ico","pdf","mov","MP4","mp4");
		#$indexFiles = array();//Store File if you want in this array
		while($file = readdir($folder)){
			if(in_array(substr(strtolower($file), strrpos($file,".") + 1), $file_types))
			{
				//echo $sFolder.'/'.$file.'---'.$dFolder.'/'.$file.'<br>';
				copy($sFolder.'/'.$file, $dFolder.'/'.$file);
			}
		}
		return true;
	}
	function give_current_date_time() {
		return $date = date('Y-m-d H:i:s');
	}
	/*function create_last_modified_date_file($folder_name)
	{
		$filename='last_modified_date.json';		
		//echo '<pre>'; print_r($data); die;
		$json_str= $this->give_current_date_time();			
		//echo $json_str; die;
		file_put_contents($folder_name.'/'.$filename,$json_str);
	}*/
	function create_last_modified_date_file($folder_name,$pincode)
	{
		$filename='last_modified_date.json';		
		$data_string = 'INSERT INTO sync_history (last_downloaded_date,device_last_updated_date,resource_type,user_pin) VALUES ("'.$this->give_current_date_time().'","'.$this->give_current_date_time().'","WebtoApp","'.$pincode.'" )';
		$data[] = array("sqlData"=> $data_string);
		$json = json_encode ($data,JSON_UNESCAPED_UNICODE);
		file_put_contents($folder_name.'/'.$filename,$json);
	}
	function Delete($path)
	{
		if (is_dir($path) === true)
		{
			$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::CHILD_FIRST);

			foreach ($files as $file)
			{
				if (in_array($file->getBasename(), array('.', '..')) !== true)
				{
					if ($file->isDir() === true)
					{
						rmdir($file->getPathName());
					}

					else if (($file->isFile() === true) || ($file->isLink() === true))
					{
						unlink($file->getPathname());
					}
				}
			}

			return rmdir($path);
		}

		else if ((is_file($path) === true) || (is_link($path) === true))
		{
			return unlink($path);
		}

		return false;
	}
	function fixJson ($json) 
		 {
		 	return (preg_replace ('/:\s?(\d{14,})/', ': "${1}"', $json));
		 }
	function save_json_data($table,$data,$foldername="")
	{ 
		$date = date(LAST_MODIFIED_DATE);
		if($table=="seat_booking_history"){
			foreach($data as $value){
				$date1 = strtr($value['created_date'], '/', '-');
				$created_date=date('Y-m-d', strtotime($date1));
				$bookingarr=array("booking_id"=>$value['device_unique_key'],
								"seat_id"=>$value['seat_id'],
								"seat_type"=>"seating",
								"bus_id"=>$value['bus_id'],
								"booked_by_user_id"=>$value['booked_by_user_id'],
								"booked_by_user_type"=>$value['booked_by_user_type'],
								"booked_source_city"=>$value['booked_source_city'],
								"booked_destination_city"=>$value['booked_destination_city'],
								"booked_to_passanger_type"=>$value['booked_to_passanger_type'],
								"route_id"=>$value['route_id'],
								"fare_received"=>$value['fare_received'],
								"trip_num"=>1,
								"is_cancel"=>0,
								"resource_type"=>$value['resource_type'],
								"created_date"=>$created_date,
								"created_by"=>$value['booked_by_user_id']);
			    $affected_rows=$this->sync_model->insertBookingData("seat_booking_history",$bookingarr);
			    if($affected_rows>0){
			    	$output[]=$value['device_unique_key'];
			    }
			}
			
		}
		if($table=="bus_location"){
			foreach ($data as $value) {
				$locationarr=array("bus_id"=>$value['bus_id'],
								"route_id"=>$value['route_id'],
								"device_id"=>$value['device_id'],
								"for_date"=>$value['for_date'],
								"lattitude"=>$value['lattitude'],
								"longitude"=>$value['longitude'],
								"created_date"=>$date,
								"trip_num"=>1,
								"device_unique_key"=>$value['device_unique_key']
							);
			}
			$affected_rows=$this->sync_model->insertLocationData("bus_location",$locationarr);
			    if($affected_rows>0){
			    	$output[]=$value['device_unique_key'];
			    }

		}
		return $output;
	}
	public function deleteall(){
		$files = glob(SYNC_UPLOAD_PATH .'/*'); // get all file names
		foreach($files as $file){ // iterate files
		  if(is_file($file))
		    @unlink($file); // delete file
			$this->Delete($file);
		}
		$dfiles=glob(SYNC_DOWNLOAD_PATH.'/*');
		foreach($dfiles as $file){ // iterate files
		  if(is_file($file))
		    @unlink($file); // delete file
			$this->Delete($file);
		}
	}






}//End of class