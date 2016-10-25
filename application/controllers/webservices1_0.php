<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Webservices1_0 extends CI_Controller 
{
	var $tables= array('bus_location','num_of_passenger','running_status');
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
				//echo json_encode(array('details'=>$pincodes));
				//calling sync file to upload everything
				//$this->data_download('device123'); 
				$this->sync_data_operator('test',$pincodes[0]->id); 
			}else{
				echo json_encode(array("message"=>"Pincode is not matched"));
			}

		}
		///////// Check for login of sales pereson is valid or not /////////////////
	}
	public function sync_data_operator($deviceid,$operator_id){

		//Getting Buses by Operator Id
			$exptable = array('bus','bus_photos');
			$buses=$this->sync_model->get_bus_by_operator($operator_id);
			foreach ($buses as $value) {
					$bus_id=$value->bus_id;
					//Creating Folder as per operator and timestamp
					$time_stamp=time().'_'.$operator_id.'_app';
					$folder_name = SYNC_DOWNLOAD_PATH."/".$time_stamp;	
					$this->create_directory($folder_name);
					foreach($exptable as $key => $table) {
						switch ($table) {
						case 'bus':
							$condition = " id = '$bus_id' AND is_deleted = 0 $append";
							break;
						
						case 'bus_photos':
							$condition = " bus_id = '$bus_id' AND is_deleted = 0 $append";
							break;

						case 'city':
						$condition = " is_deleted = 0 $append";
						break;

						case 'bus_route_validity':
						$condition = " bus_id = '$bus_id'  AND is_deleted = 0 $append";
						break;
					}
				$columns = $this->get_required_table_columns($table);
				
				$sql = "SELECT $columns FROM $table WHERE $condition";
				$this->create_insert_sql($table,$folder_name,$last_modified_date,$sql,$columns);
			 	}
			} 
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

			$this->create_zip($source,$destination,$time_stamp);
	}
	
	/****************  Data downloaded web services Section	**************/
	/***************************************  Create Files Section	************************/
	function create_last_modified_date_file($folder_name) {
		$filename='last_modified_date.txt';		
		//echo '<pre>'; print_r($data); die;
		$json_str= $this->give_current_date_time();			
		//echo $json_str; die;
		file_put_contents($folder_name.'/'.$filename,$json_str);
	}

	function give_current_date_time() {
		return $date = date('Y-m-d H:i:s');
	}


	/********************** Data download Create Insert SQLS Section ************************/

	function create_insert_sql_for_data_tables($table,$folder_name, $params=array(),$user_id,$last_modified_date) 	{
		$columns= $this->get_required_table_columns($table);
		$new_columns = $columns;


		$result =  $this->sync_model->get_data_from_tables($columns,$table,$params);

		$table_name_sql= "INSERT OR REPLACE INTO ".$table;	
		$insert_value_sql= "" ;	
		if($result) {
			$i=1;
			$num_rows=$result->num_rows();
			foreach ( $result->result_array() as $row )	{

				$value_str='';
				foreach($row as $key => $value){			
					$value_str.= '"'.$this->escape_sqlite_value($value).'", ';
				}

				$values=substr($value_str,0,strlen($value_str)-2);
				$insert_value_sql.=  " SELECT ".$values." UNION ALL"; 

				$final_str= " (".$new_columns.") ".substr( $insert_value_sql,0,strlen($insert_value_sql)-9);


				if( $i%SQL_NUM_ROW==0){ 
					$data[] = array("sqlData"=> $table_name_sql.$final_str);
					$insert_value_sql='';
				}

				$i++;			
			}
			//echo 			$num_rows.'---'.SQL_NUM_ROW.'<br>'; 
			if( count($result) < SQL_NUM_ROW )
			{
				$data[] = array("sqlData"=> $table_name_sql.$final_str);
			}


			$filename=$table.'.json';		
			//echo '<pre>'; print_r($data); die;
			$json_str= json_encode( $data);		


			//echo $json_str; die;
			file_put_contents($folder_name.'/'.$filename,$json_str);
		}
	} 

	function create_insert_sql_for_data_tables_condition2($table,$folder_name, $params=array(),$user_id,$last_modified_date,$where_in,$where_in_for)
	{
		$columns= $this->get_required_table_columns($table);
		$new_columns = $columns;


		$result =  $this->sync_model->get_data_from_tables_wherein_condition($columns,$table,$params,$where_in,$where_in_for);

		$table_name_sql= "INSERT OR REPLACE INTO ".$table;	
		$insert_value_sql= "" ;	
		if($result)
		{
			$i=1;
			$num_rows=$result->num_rows();
			foreach ( $result->result_array() as $row )
			{

				$value_str='';
				foreach($row as $key => $value)
				{			
					$value_str.= '"'.$this->escape_sqlite_value($value).'", ';
				}

				$values=substr($value_str,0,strlen($value_str)-2);
				$insert_value_sql.=  " SELECT ".$values." UNION ALL"; 

				$final_str= " (".$new_columns.") ".substr( $insert_value_sql,0,strlen($insert_value_sql)-9);


				if( $i%SQL_NUM_ROW==0)
				{ 
					$data[] = array("sqlData"=> $table_name_sql.$final_str);
					$insert_value_sql='';
				}

				$i++;			
			}
			//echo 			$num_rows.'---'.SQL_NUM_ROW.'<br>'; 
			if( count($result) < SQL_NUM_ROW )
			{
				$data[] = array("sqlData"=> $table_name_sql.$final_str);
			}


			$filename=$table.'.json';		
			//echo '<pre>'; print_r($data); die;
			$json_str= json_encode( $data);		


			//echo $json_str; die;
			file_put_contents($folder_name.'/'.$filename,$this->fixJson($json_str),FILE_APPEND);
		}
	} 


	/******************** Data download  Create Insert SQLS Section	************************/
	function get_required_table_columns($table)
	{ 		
		
		$table_columns['bus']= 'id,name,bus_number,chassis_number,type,capacity,model,document,registration_image,permit_image,insurance_image,created_by,created_date,last_modified_by,last_modified_date,resource_type,is_deleted';

		

		$table_columns['bus_location']= 'id,bus_id,route_id,device_id,for_date,lattitude,longitude,trip_num,created_date,created_by,last_modified_date,last_modified_by	,resource_type,is_deleted';

		$table_columns['bus_photos']= 'id,bus_id,image,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted';

		$table_columns['bus_route_validity']= 'id,user_id,bus_id,route_id,valid_from,valid_to,max_trip,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted';

		$table_columns['bus_timing']= 'id,user_id,bus_id,route_id,city_id,journey_date,arrival_date,arrival_hour,arrival_minute,arrival_am_pm,departure_date,departure_hour,departure_minute,departure_am_pm,trip_type,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted';

		$table_columns['city']= 'id,user_id,state_id,name,lattitude,longitude,	status,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted';

		$table_columns['devices']= 'id,device_id,name,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted';

		$table_columns['device_assigned']= 'id,bus_id,device_id,fordate,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted';

		$table_columns['num_of_passenger']= 'id,bus_id,route_id,device_id,fordate,from_city,to_city,count,trip_type,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted';

		$table_columns['route']= 'id,user_id,name,source_state,source_city,destination_state,destination_city,stoppage_state,status,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted';

		$table_columns['route_fare']= 'id,user_id,route_id,from_city,to_city,fare,status,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted';

		$table_columns['route_stoppage']= 'id,user_id,route_id,city_id,stoppage_order,status,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted';

		$table_columns['running_status']= 'id,bus_id,route_id,device_id,for_date,lattitude,longitude,trip_num,message,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted';

		$table_columns['staff']= 'id,name,contact_number,address,staff_type,license_number,expiry_date,image_path,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted';

		$table_columns['staff_assigned']= 'id,bus_id,driver,conductor,operator,other,fordate,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted';

		$table_columns['state']= 'id,user_id,name,status,created_date,created_by,last_modified_date,last_modified_by,resource_type,is_deleted';

		return $table_columns[$table];		
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


	function escape_sqlite_value($str)
	{
		return str_replace('"','""',$str);
	}

	
	function create_zip($source, $destination , $zip_file_name)
	{
			
		$folder_in_zip = "";			
		
		
		$this->zip->get_files_from_folder($source, $folder_in_zip); 
		
		$this->Delete($source);

		$this->zip->read_file($source,true);
		
		$this->zip->download($zip_file_name.'.zip');	

		
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


	/******  Data upload web services Section	********/
	/*
	This method is used for upload database
	*/

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

				$this->importdataUploads($destination,$userid,$device_type,$upload_file_name,$device_registration_id,$device_date,$device_model,$os_version);
				
				$this->extractZip(SYNC_UPLOAD_PATH .'/'. $zipName.'.zip', SYNC_UPLOAD_PATH .'/'. $zipName.'/');				

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
						$fileContent = file_get_contents($jsonFile);
												
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

						if($var = $this->save_json_data($table,$data,SYNC_UPLOAD_PATH.'/'.$zipName.'/'.$upload_file_name))
						{						
							$output[$table] = $var;
						}
						else
						{
							$output[$table] = false;
						}
					}				

					
				}
				
				unlink($destination);			

				echo json_encode($output);

				/*if(chmod(SYNC_UPLOAD_PATH .'/'. $zipName,0777))
			  	{
			  		$this->rmdir_recursive(SYNC_UPLOAD_PATH .'/'. $zipName);
			  	}*/
			}
		}
	}	
	/**
	 * This method is used for upload database
	*/
	/*function rmdir_recursive($dir) 
	{
	    foreach(scandir($dir) as $file) 
	    {
	        if ('.' === $file || '..' === $file) continue;
	        if (is_dir("$dir/$file")) rmdir_recursive("$dir/$file");
	        else unlink("$dir/$file");
	    }
	    
	    rmdir($dir);
	}*/

	function update_reference_keys($data)
	{ 	
		$result=array();
		foreach($data as $row)
		{
			//echo '1'; print_r($row);
			// update reference key for project table
			$project='feeds';
			if(isset($row[$project]))
			{  
				foreach ($row[$project] as $value)
				{
					$table='feed_photos';
					if($this->data_inserted($data,$table))
					{
						$from_data=array('feed_id'=>$value['value']);
						$where= array('feed_id'=>$value['mobile_key']);
						$updated=$this->sync_model->update_data_manually($table,$where,$from_data,'updated_date');
						if($updated)
						{
							$temp = array('mobile_key'=>$value['mobile_key'],'value'=>$value['value'],'reference_key'=>'feed_id');
							$result[][$table][]= $temp;
						}
					}
				}

				foreach ($row[$project] as $value)
				{
					$table='feed_videos';
					if($this->data_inserted($data,$table))
					{
						$from_data=array('feed_id'=>$value['value'] );
						$where= array('feed_id'=>$value['mobile_key']);
						$updated=$this->sync_model->update_data_manually($table,$where,$from_data,'updated_date');
						if($updated)
						{
							$temp = array('mobile_key'=>$value['mobile_key'],'value'=>$value['value'],'reference_key'=>'feed_id');
							$result[][$table][]= $temp;
						}
					}
				}			

				foreach ($row[$project] as $value)
				{
					$table='feed_checklist_status';
					if($this->data_inserted($data,$table))
					{
						$from_data=array('feed_id'=>$value['value'] );
						$where= array('feed_id'=>$value['mobile_key']);
						$updated=$this->sync_model->update_data_manually($table,$where,$from_data,'updated_date');
							//echo last_query(); die;
						if($updated)
						{
							$temp = array('mobile_key'=>$value['mobile_key'],'value'=>$value['value'],'reference_key'=>'feed_id');
							$result[][$table][]= $temp;
						}
					}
				}		

			}
		}
		return $result;
	}






	function save_data($table,$data)
	{ 
		$result=array();
		$final=array();
		$insertedArray = array();
		$final_record_ids = '';
		$i=0;
		$mytime_counter = 0;
		$mycheckcounter=0;
		$myresponseArray = array();
		foreach($data as $row)
		{
			unset($row['is_downloaded']);
			unset($row['is_uploaded']);
			unset($row['is_data_uploaded']);
			unset($row['created_by']);
			unset($row['updated_by']);

			// remove the auto increment columns
			switch ($table)
			{
				case 'num_of_passenger':
				case 'bus_location':
				
				$record_id=$row['id'];
				unset($row['id']);
				//unset($row['updated_date']);
				$primary_key='id';
				break;
			}
			
			// update the last_modified_date and created_date with mysql server date
			$now='';
			$now=  date('Y-m-d H:i:s');
			$timenew1 = new DateTime($now);
			$timenew1->add(new DateInterval('PT' . $mytime_counter . 'S'));
			$now = $timenew1->format('Y-m-d H:i:s');
			

			$row['last_modified_date']=$now;
			
			
			if($this->sync_model->record_exist($table,array($primary_key=>$record_id)) or ($row['device_id']!=0 and $this->sync_model->record_exist($table,array('device_id'=>$row['device_id']))))
			{

				switch ($table) {
					case 'bus_location':
					case 'num_of_passenger':
					
						# code...
					$unset_this_value = 'created_date';
					$lmd_value  = 'last_modified_date';
					break;					
					default:
						# code...
					break;
				}
				

				unset($row[$unset_this_value]);
				unset($row[$lmd_value]);
				$row[$lmd_value]=$now;
				$result_current_row = $this->sync_model->get_data($table,array($primary_key=>$record_id));
				//echo $row[$lmd_value].'<br>';
				$last_modified_date = $result_current_row[0][$lmd_value];
				$last_modified_date_timestamp = strtotime($last_modified_date);
				$lmd_value_timestamp = strtotime($row[$lmd_value]);
				//echo $row[$lmd_value].'>'.$last_modified_date.'<br>';
				//echo $lmd_value_timestamp.'<'.$last_modified_date_timestamp.'<br>';
				//if($row[$lmd_value]<$last_modified_date)
				if($lmd_value_timestamp>$last_modified_date_timestamp)
				{
					$this->sync_model->update_data($table,array($primary_key=>$record_id),$row);
				}

				//echo last_query().'<br>';
				if($mycheckcounter==0)
				{
					$final_record_ids = $record_id;
				}
				else
				{
					$final_record_ids.= ",".$record_id;
				}

				//pr($updateFinal);
				//die;

				/*///////////////////////notification code will update here//////////////////////////*/
				
				/////////////////////////////////////////////////////
			}
			else
			{
				$value = $this->sync_model->add($table,$row);

				/* Push notification send when feed added */
				/* Push notification send when feed added */
				


				//echo last_query().'<br>';
				$temp = array('device_id'=>$row['device_id'],'value'=>$value, 'primary_key'=>$primary_key);
				//$temp1 = $row;
				$result[]= $temp;
				//$result1[]= $temp1;
				$final[$table]=$result;
				//$final['digmaa_local_'.$table]=$result1;
				$myresponseArray['insert'] = $final;

				if($mycheckcounter==0)
				{
					$final_record_ids = $value;
				}
				else
				{
					$final_record_ids.= ",".$value;
				}
			}
			//echo last_query().'<br>';
			
			$mytime_counter=$mytime_counter+1;
			$mycheckcounter++;

		}
		if(!empty($myresponseArray) || $final_record_ids!='')
		{
			if($final_record_ids!='')
			{
				$tempresult[] = array('value'=>$final_record_ids, 'primary_key'=>$primary_key);
				$updateFinal[$table]=$tempresult;
				$myresponseArray['update'] = $updateFinal;
			}
			//pr($my['insert']);

			//$fullresponse[] = $my;

			//echo json_encode($my['insert']);
			//die;		
			return $myresponseArray;
		}
		else
			return false;
		
	}




	/**
	 * This method is used for upload images
	*/

	function upload_images()
	{
		$upload_file_name="upload_images";
		$userid = $this->input->get_post('user_id');
		$device_type = $this->input->get_post('device_type');
		$primary_key = 'photo_id';

		if(!$device_type)
		{
			$device_type='iOSDevice';
		}

		$device_token = $this->input->get_post('device_token');
		$device_model = $this->input->get_post('device_model');

		$device_registration_id = $this->input->get_post('device_registration_id');
		$device_date = $this->input->get_post('device_date');
		
		$os_version = $this->input->get_post('os_version');
		



		if(empty($_FILES))
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
			if(move_uploaded_file($_FILES[$upload_file_name]["tmp_name"],$destination )){
				
				$this->importdataUploads($destination,$userid,$device_type,$upload_file_name,$device_registration_id,$device_date,$device_model,$os_version);
				
				$this->extractZip(SYNC_UPLOAD_PATH .'/'. $zipName.'.zip', SYNC_UPLOAD_PATH .'/'. $zipName.'/');
				
				$folder = @opendir(SYNC_UPLOAD_PATH.'/'.$zipName.'/'.$upload_file_name.'/');			
				
				if(!$folder){
					$output = array(
						'status' => false,
						'message' => 'Zip missing the folder : '.$upload_file_name,
						'data' => 'FolderMissing'
						);
					echo json_encode($output);
					die;					
				}
				
				//echo				SYNC_UPLOAD_PATH.$zipName.'/UploadDatabase/'; die;
				
				$source=SYNC_UPLOAD_PATH .'/'. $zipName.'/'.$upload_file_name.'';
				$dest=RESOURCE_POST_PIC_PATH.'/photos/post_pic';
				$this->copyFilestoFolder($source,$dest);

				$output = array(
					'status' => true,
					'message' => 'success',
					'data' => 'success'
					);
				echo json_encode($output);
			}
		}
	}

	/**
	 * This method is used for upload images
	*/

/**
	 * This method is used for upload videos
	*/

function upload_videos()
{
	$upload_file_name="upload_videos";
	$userid = $this->input->get_post('user_id');
	$device_type = $this->input->get_post('device_type');
	$primary_key = 'photo_id';

	if(!$device_type)
	{
		$device_type='iOSDevice';
	}

	$device_token = $this->input->get_post('device_token');
	$device_model = $this->input->get_post('device_model');

	$device_registration_id = $this->input->get_post('device_registration_id');
	$device_date = $this->input->get_post('device_date');

	$os_version = $this->input->get_post('os_version');
	



	if(empty($_FILES))
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
		if(move_uploaded_file($_FILES[$upload_file_name]["tmp_name"],$destination )){

			$this->importdataUploads($destination,$userid,$device_type,$upload_file_name,$device_registration_id,$device_date,$device_model,$os_version);

			$this->extractZip(SYNC_UPLOAD_PATH .'/'. $zipName.'.zip', SYNC_UPLOAD_PATH .'/'. $zipName.'/');

			$folder = @opendir(SYNC_UPLOAD_PATH.'/'.$zipName.'/'.$upload_file_name.'/');			

			if(!$folder){
				$output = array(
					'status' => false,
					'message' => 'Zip missing the folder : '.$upload_file_name,
					'data' => 'FolderMissing'
					);
				echo json_encode($output);
				die;					
			}

				//echo				SYNC_UPLOAD_PATH.$zipName.'/UploadDatabase/'; die;

			$source=SYNC_UPLOAD_PATH .'/'. $zipName.'/'.$upload_file_name.'';
			$dest=RESOURCE_POST_PIC_PATH.'/photos/post_video';
				//$this->copyFilestoFolder($source,$dest);

				//$this->copyVideosToFolderWithFFMPEG($source,$dest);
			$this->copyFilestoFolder($source,$dest);

			$output = array(
				'status' => true,
				'message' => 'success',
				'data' => 'success'
				);
			echo json_encode($output);
		}
	}
}

	/**
	 * This method is used for upload videos
	*/







	function importdataUploads($destination,$userid,$device_type,$export_data_type,$device_registration_id,$device_date,$device_model,$os_version)
	{
		$current_url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$data = array(
			'path' => $destination,
			'created_date' => date('Y-m-d H:i:s'),
			'userid' => $userid, 
			'device' =>  $device_type, 
			'importDataType' =>  $export_data_type,
			'sync_url' =>  $current_url,
			'device_registration_id' =>  $device_registration_id,
			'device_date' =>  $device_date,
			'device_model' =>  $device_model,
			'os_version' =>  $os_version
			);
		$this->sync_model->add("importdata",$data);
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


	function upload_form()
	{
		$this->load->view('upload_form');
	}


	/* 
	service used for registered device in to database table: device_registration table.
	*/

	/* 
	service used for registered device in to database table.
	*/

	/* 
	This method is used for check data is inserted into database table or not
	*/
	function data_inserted($data,$table)
	{
		$inserted= false;
		foreach($data as $row)
		{  		
			if(isset($row[$table]))
			{  
				$inserted= true;
			}
		}		
		return $inserted;
	}
	/* 
	This method is used for check data is inserted into database table or not
	*/

	/* fixJson - work around large integer issue by adding quotes around
		 any large numeric values - causes them to be interpreted as strings
	*/
		 function fixJson ($json) 
		 {
		 	return (preg_replace ('/:\s?(\d{14,})/', ': "${1}"', $json));
		 }
	/* fixJson - work around large integer issue by adding quotes around
		 any large numeric values - causes them to be interpreted as strings
	*/



		 function getUserSetting($user_id,$user_setting_name)
		 {
		 	$this->db->where('user_id =',$user_id);
		 	$this->db->where('user_setting_name =',$user_setting_name);
		 	$this->db->where('user_setting_value =',1);

		 	$query = $this->db->get('user_setting');
		 	if($query->num_rows() >0)
		 	{
		 		return 1;

		 	}
		 	else{
		 		return 0;
		 	}
		 }





		 /***************************************  Data upload web services Section	************************/


	/*------------------------------------------------------------------
	My functions
	-------------------------------------------------------------------*/
	function create_insert_sql($table,$folder_name,$last_modified_date,$sql,$new_columns)
	{
		$table_name_sql= "INSERT OR REPLACE INTO ".$table;
		
		$insert_value_sql= "" ;	
		
		$result = $this->sync_model->execute_sql($sql,2);
		
		if(isset($result) && !empty($result))
		{
			$i=1;
			
			$num_rows = count($result);
			
			foreach ($result as $row )
			{

				$value_str='';
				foreach($row as $key => $value)
				{			
					$value_str.= '"'.$this->escape_sqlite_value($value).'", ';
				}

				$values=substr($value_str,0,strlen($value_str)-2);
				$insert_value_sql.=  " SELECT ".$values." UNION ALL"; 

				$final_str= " (".$new_columns.") ".substr( $insert_value_sql,0,strlen($insert_value_sql)-9);


				if( $i%SQL_NUM_ROW==0)
				{ 
					$data[] = array("sqlData"=> $table_name_sql.$final_str);
					$insert_value_sql='';
				}

				$i++;			
			}
			$filename = $table.'.json';	

			if( count($result) < SQL_NUM_ROW)
			{
				$data[] = array("sqlData"=> $table_name_sql.$final_str);
			}
			$json_str = json_encode($data);
			$fixed_str= $this->fixJson($json_str);
			file_put_contents($folder_name.'/'.$filename,$fixed_str,FILE_APPEND);
		}
	} 




	function save_json_data($table,$data,$foldername="")
	{ 
		$date = date(LAST_MODIFIED_DATE);
		
		//echo "<pre>$table<br/>";print_r($data);

		foreach($data as $row)
		{
			$bool=true;
			if($table == "bus_location")
			{
				$row['created_date'] = $date;
				$row['last_modified_date'] = $date;
				$value = $this->sync_model->add($table,$row);
			}
			elseif($table == "num_of_passenger")
			{
				$bus_id = $row['bus_id'];
				$route_id = $row['route_id'];
				$device_id = $row['device_id'];
				$fordate = date(CHANGE_INTO_DATE_FORMAT,strtotime($row['fordate']));
				$from = $row['from_city'];
				$to = $row['to_city'];
				$count = $row['count'];
				$trip_type = $row['trip_type'];
				$trip_num = $row['trip_num'];


				$sql = "SELECT id 
						FROM $table
						WHERE 	bus_id = '$bus_id' AND 
								route_id = '$route_id' AND 
								device_id = '$device_id' AND 
								date(fordate) = '$fordate' AND 
								from_city = '$from' AND 
								to_city = '$to' AND 
								trip_type = '$trip_type' AND 
								trip_num = '$trip_num' 
								LIMIT 1";

				$result = $this->sync_model->execute_sql($sql) ;

				if(isset($result) && !empty($result))
				{
					$sql = "UPDATE 
								num_of_passenger 
							SET 
								count = '$count',
								last_modified_date =  '$date'
							WHERE 	
								bus_id = '$bus_id' AND 
								route_id = '$route_id' AND 
								device_id = '$device_id' AND 
								fordate = '$fordate' AND 
								from_city = '$from' AND 
								to_city = '$to' AND 
								trip_type = '$trip_type' AND 
								trip_num = '$trip_num' 
							LIMIT 1";

					$value = $this->db->query($sql);					
				}				
				else
				{	
					/*$sql = "INSERT INTO 
							num_of_passenger(bus_id,route_id,device_id,fordate,from_city,to_city,count,trip_type,trip_num,created_date,resource_type,last_modified_date) 
						    VALUES('$bus_id','$route_id','$device_id','$savedate','$from','$to','$count','$trip_type','$trip_num','$date','app','$date')";*/
					
					$row['created_date'] = $date;
					$row['last_modified_date'] = $date;
					$value = $this->sync_model->add($table,$row);	
				}			
				

			}
			elseif($table == "running_status")
			{
				$bus_id = $row['bus_id'];
				$route_id = $row['route_id'];
				$device_id = $row['device_id'];
				$fordate = date(CHANGE_INTO_DATE_FORMAT,strtotime($row['for_date']));
				$lattitude = $row['lattitude'];
				$longitude = $row['longitude'];
				$trip_num = $row['trip_num'];
				$message = $row['message'];
				$message_details = $row['message_details'];
				$image_name = $row['image_name'];
				$image_name_for_db = time()."111".$image_name;

				$sql = "SELECT id 
						FROM $table
						WHERE 	
							bus_id = '$bus_id' AND 
							route_id = '$route_id' AND 
							device_id = '$device_id' AND 
							date(for_date) = '$fordate' AND								
							trip_num = '$trip_num' 
							LIMIT 1";

				$result = $this->sync_model->execute_sql($sql) ;

				if($image_name != "")
				{
					if(file_exists($foldername."/".$image_name))
					{
						if(!copy($foldername."/".$image_name, SYNC_UPLOAD_PATH."/status/".$image_name))
						{
							$image_name_for_db = "";					
						}
					}
				}

				if(isset($result) && !empty($result))
				{
					$sql = "UPDATE 
								$table 
							SET						
								message = '$message',
								message_details = '$message_details',
								image_name = '$image_name_for_db',
								last_modified_date = '$date'
							WHERE 	
								bus_id = '$bus_id' AND 
								route_id = '$route_id' AND 
								device_id = '$device_id' AND 
								date(for_date) = '$fordate' AND								
								trip_num = '$trip_num' 
							LIMIT 1";
					
					$value = $this->db->query($sql);					
				}				
				else
				{	
					/*$sql = "INSERT INTO 
							num_of_passenger(bus_id,route_id,device_id,fordate,from_city,to_city,count,trip_type,trip_num,created_date,resource_type,last_modified_date) 
						    VALUES('$bus_id','$route_id','$device_id','$savedate','$from','$to','$count','$trip_type','$trip_num','$date','app','$date')";*/
					
					$row['created_date'] = $date;
					$row['last_modified_date'] = $date;
					$value = $this->sync_model->add($table,$row);	
				}				

			}

			if($value <= 0)
			{
				$bool=false;
				break;
			}

	

		}


		if($bool==true)
		{

			return true;

		}else{

			return false;
		}

		

		
	}

	public function city_lat_long()
	{
		$this->webservice_model->city_lat_long();
	}	



	public function bus_location($bus_id,$route_id,$device_id,$fordate,$lat,$long,$trip_num)
	{
		if(isset($bus_id) && !empty($bus_id) && $bus_id>0 && isset($route_id) && !empty($route_id) && $route_id>0 && isset($device_id) && !empty($device_id) && isset($lat) && !empty($lat) && isset($long) && !empty($long) && isset($fordate) && !empty($fordate) && $trip_num!="" && $trip_num>0)
		{
			$response = $this->webservice_model->bus_location($bus_id,$route_id,$device_id,$fordate,$lat,$long,$trip_num);

			if($response)
			{				
				echo json_encode(array("response"=>true));
			}
			else
			{				
				echo json_encode(array("response"=>false));
			}
		}

	}


	public function passenger_count($bus_id,$route_id,$device_id,$fordate,$count_info)
	{
		if(isset($bus_id) && !empty($bus_id) && $bus_id>0 && isset($route_id) && !empty($route_id) && $route_id>0 && isset($device_id) && !empty($device_id) && isset($fordate) && !empty($fordate) && isset($count_info) && !empty($count_info))
		{
			$response = $this->webservice_model->passenger_count($bus_id,$route_id,$device_id,$fordate,$count_info);

			if($response)
			{
				
				echo json_encode(array("response"=>true));
			}
			else
			{
				
				echo json_encode(array("response"=>false));
			}
		}

	}
	public function get_all_details($device_id,$date)
	{
		if(isset($device_id) && !empty($device_id) && isset($date) && !empty($date))
		{		
			$all_data = array();

			$all_data['bus_details'] = $this->webservice_model->get_bus_details($device_id,$date);

			if(isset($all_data['bus_details']['response']) || $all_data['bus_details']['response'] == 1  || $all_data['bus_details']['response'] == true)
			{
				
				$bus_id = $all_data['bus_details']['data']['id'];

				
				$all_data['route_details'] = $this->webservice_model->get_route_details($device_id,$date,$bus_id);

				$all_data['bus_timing_details'] = $this->webservice_model->bus_timing_details($device_id,$date,$bus_id);

				
				if(isset($all_data['route_details']['route_id']) || $all_data['route_details']['route_id'] > 0)
				{
					
					$route_id = $all_data['route_details']['route_id'];

					$all_data['fare_details'] = $this->webservice_model->get_fare_details($device_id,$date,$bus_id,$route_id);

				}
					
			}			

			//echo "<pre>";print_r($all_data);die;
			echo json_encode($all_data);
		}
		else
		{				
			echo json_encode(array("data"=>"","response"=>false,"msg"=>"Please input Device id and Date."));
		}
			
	}


	public function bus_running_status($bus_id,$route_id,$device_id,$fordate,$trip_num,$lattitude,$longitude,$message)
	{
		if(isset($bus_id) && !empty($bus_id) && $bus_id>0 && isset($route_id) && !empty($route_id) && $route_id>0 && isset($device_id) && !empty($device_id) && isset($fordate) && !empty($fordate) && isset($trip_num) && !empty($trip_num) && isset($lattitude) && !empty($lattitude) && isset($longitude) && !empty($longitude) && isset($message) && !empty($message))
		{
			$response = $this->webservice_model->bus_running_status($bus_id,$route_id,$device_id,$fordate,$trip_num,$lattitude,$longitude,$message);

			if($response)
			{
				
				echo json_encode(array("response"=>true));
			}
			else
			{
				
				echo json_encode(array("response"=>false));
			}
		}

	}





}//End of class