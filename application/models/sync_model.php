<?php
class Sync_model extends CI_Model
{
	var $globalArrayVariable = array();
	var $prevGlobalArrayVariable = array();
	var $nextGlobalArrayVariable = array();
	function __construct()
	{
		
		$this->load->helper(array('form', 'url'));
		
	}


	public function execute_sql($sql,$limit=1) 
	{
		$query = $this->db->query($sql);
		
		if($limit == 1)
		{
			return $query->row_array();
		}
		else
		{
			return $query->result();
		}	
	}
	public function exec_sql($sql) 
	{
		$query = $this->db->query($sql);
		return $query->result_array();
		
	}
	public function utf_exec_sql($sql) 
	{
		$query = $this->db->query($sql);
		return $query->result();
		
	}

	function get_data_from_tables($fields,$table_name,$where=array())
	{
		if(!empty($where))
		{
			foreach($where as $key=>$value)
			{
				$this->db->where($key,$value);				
				
			}
				
		}
		$this->db->select($fields);
		$this->db->from($table_name);
		$query = $this->db->get();
		
		
		
		if($query->num_rows()>0)
		{
			
			return $query;
		
		}
		else
		{
			return false;	
		}	
	}

	function get_data_from_tables_wherein_condition($fields,$table_name,$where=array(),$where_in,$where_in_for)
	{
		if(!empty($where))
		{
			foreach($where as $key=>$value)
			{
				$this->db->where($key,$value);				
				
			}
				
		}
		$this->db->where_in($where_in_for,$where_in);
		$this->db->select($fields);
		$this->db->from($table_name);
		$query = $this->db->get();
		
		
		if($query->num_rows()>0)
		{
			
			return $query;
		
		}
		else
		{
			return false;	
		}	
	}



	function insert_replaceV1($table_name,$form_data)
	{
		$query_result=$this->db->replace($table_name,$form_data);		
		 if(!$query_result) {
			 //don't show Duplicate entry '' for key 'PRIMARY' 
			if ($this->db->_error_number()!=1062){
     			echo $this->db->_error_message().' <br> ';
				echo $this->db->last_query().'<br>';
     			echo 'error no '.$this->db->_error_number().'<br>';
		 	}
     		return false;
  		}
		return $this->db->insert_id();
	}
		
	function insert_replace($table_name,$form_data)
	{
		
	    $sql = $this->db->insert_string($table_name,$form_data);
		$sql = str_replace('INSERT INTO','INSERT OR REPLACE INTO',$sql);
		$this->db->query($sql);
	    $last_id = $this->db->insert_id();
		 if(!$last_id) {
			 //don't show Duplicate entry '' for key 'PRIMARY' 
			if ($this->db->_error_number()!=1062){
     			echo $this->db->_error_message().' <br> ';
				echo $this->db->last_query().'<br>';
     			echo 'error no '.$this->db->_error_number().'<br>';
		 	}
     		return false;
  		}
		return $last_id;
	}
	
		
	function add($table_name,$form_data)
	{
		$query_result=$this->db->insert($table_name,$form_data);		
		 if(!$query_result) {
			 //don't show Duplicate entry '' for key 'PRIMARY' 
			if ($this->db->_error_number()!=1062){
     			echo $this->db->_error_message().' <br> ';
				echo $this->db->last_query().'<br>';
     			echo 'error no '.$this->db->_error_number().'<br>';
		 	}
     		return false;
  		}
		return $this->db->insert_id();
	}


	/**
	*This method is used for the update data with table name and where condition more than one key
	*/
	function update_data($table_name,$where=array(),$form_data)
	{
		if(!empty($where))
		{
			foreach($where as $key=>$value)
			{
				$this->db->where($key,$value);
			}
				
		}
		
		$this->db->update($table_name,$form_data);
		
		 if ($this->db->affected_rows() > 0) {
            return TRUE;
        }
		else
		{
		 return FALSE;	
		}
	}


	/**
	*This method is used for the update data with table name and where condition more than one key
	*/
	function update_data_manually($table_name,$where=array(),$form_data,$column_update)
	{
		if(!empty($where))
		{
			foreach($where as $key=>$value)
			{
				$this->db->where($key,$value);
			}
				
		}
		
		$this->db->set($column_update, $column_update, FALSE);
		$this->db->update($table_name,$form_data);

		 if ($this->db->affected_rows() > 0) {
            return TRUE;
        }
		else
		{
		 return FALSE;	
		}
	}
	
	
	
	function get_data($table_name,$where=array(),$fields='*'){
		if(!empty($where))
		{
			foreach($where as $key=>$value)
			{
				$this->db->where($key,$value);				
				
			}
				
		}
		$this->db->select($fields);
		$this->db->from($table_name);
		
		$query = $this->db->get();
		if($query->num_rows()>0)
		{
			
			  foreach ( $query->result_array() as $row )
			  {
			       $temp_result[] = $row;
			  }
 		 		return $temp_result;
		
			
		}
		else
		{
			return false;	
		}	
				
		
		
	}
	function get_dataV2($table_name,$where=array())
	{
		if(!empty($where))
		{
			foreach($where as $key=>$value)
			{
				$this->db->where($key,$value);				
				
			}
				
		}
		
		$query = $this->db->get($table_name);
		//echo $this->db->last_query();
		if($query->num_rows()>0)
		{
			
			
 		 		return $query;
		
			
		}
		else
		{
			return false;	
		}	
				
		
		
	}
	function get_dataV3($fields,$table_name,$where=array())
	{
		if(!empty($where))
		{
			foreach($where as $key=>$value)
			{
				$this->db->where($key,$value);				
				
			}
				
		}
		$this->db->select($fields);
		$this->db->from($table_name);
		$query = $this->db->get();
		
		if($query->num_rows()>0)
		{
			
			
 		 		return $query;
		
			
		}
		else
		{
			return false;	
		}	
	}


	function get_first_sync_dataV3($fields,$table_name,$where=array())
	{
		$primary_key =  $this->get_primary_key_table($table_name);
		if(!empty($where))
		{
			foreach($where as $key=>$value)
			{
				$this->db->where($key,$value);				
				
			}
				
		}
		$this->db->select($fields);
		$this->db->from($table_name);
 		$this->db->limit(1000, 0);
		$this->db->order_by($primary_key, "DESC"); 
 		$query = $this->db->get();
		
		
		if($query->num_rows()>0)
		{
	 		return $query;
		}
		else
		{
			return false;	
		}	
	}


	


	function get_data_for_sync_other_tables($columns,$table,$params,$user_id,$center_id,$child_id,$foreign_key_holder)
	{
		$primary_key =  $this->get_primary_key_table($table);
		if(!empty($where))
		{
			foreach($where as $key=>$value)
			{
				$this->db->where($key,$value);				
			}
		}
 		$this->db->where_in($foreign_key_holder, $this->globalArrayVariable[$child_id][$user_id]);
		$this->db->select($fields);
		$this->db->from($table);
		$this->db->order_by($primary_key, "DESC"); 
 		$query = $this->db->get();

		
		if($query->num_rows()>0)
		{
	 		return $query;
		}
		else
		{
			return false;	
		}	
	}


	function get_data_prev_sync_other_tables($columns,$table,$params,$user_id,$center_id,$child_id,$foreign_key_holder)
	{

		$primary_key =  $this->get_primary_key_table($table);
		if(!empty($where))
		{
			foreach($where as $key=>$value)
			{
				$this->db->where($key,$value);				
			}
		}
 		$this->db->where_in($foreign_key_holder, $this->prevGlobalArrayVariable[$child_id][$user_id]);
		$this->db->select($fields);
		$this->db->from($table);
		$this->db->order_by($primary_key, "DESC"); 
 		$query = $this->db->get();
		
		if($query->num_rows()>0)
		{
	 		return $query;
		}
		else
		{
			return false;	
		}	
	}


	function get_data_next_sync_other_tables($columns,$table,$where=array(),$user_id,$center_id,$child_id,$foreign_key_holder)
	{
		$primary_key =  $this->get_primary_key_table($table);
		
 		$this->db->where_in($foreign_key_holder, $this->nextGlobalArrayVariable[$child_id][$user_id]);
		$this->db->select($fields);
		$this->db->from($table);
		if(!empty($where))
		{
			foreach($where as $key=>$value)
			{
				$this->db->or_where($key,$value);				
			}
		}
		$this->db->order_by($primary_key, "DESC"); 
 		$query = $this->db->get();
		
		if($query->num_rows()>0)
		{
	 		return $query;
		}
		else
		{
			return false;	
		}	
	}

	
	function get_data_with_limit($table_name,$where=array(),$fields='*',$offset,$limit,$column,$order,$primary_key_holder){
		if(!empty($where))
		{
			foreach($where as $key=>$value)
			{
				$this->db->where($key,$value);				
			}
		}

 		$this->db->where_in($primary_key_holder, $_SESSION['news_feed_id_details']);
 		$this->db->select($fields);
		$this->db->from($table_name);
		
		$this->db->limit($limit,$offset);
		$this->db->order_by($column,$order);
		$query = $this->db->get();
		if($query->num_rows()>0)
		{
			foreach ( $query->result_array() as $row )
			{
				$temp_result[] = $row;
			}
			return $temp_result;
		}
		else
		{
			return false;	
		}	
				
		
		
	}
	function get_by_username_email($username_email)
	{
		return $this->db->from('users')->where('email', $username_email)->or_where('user_name', $username_email)->get()->row();
	}
	
	
	function get_all_columns($table)
	{
	   $query="SHOW COLUMNS FROM $table";
	   $results=$this->db->query($query);
	   return $results->result_array();
		
	}
	
	
	function get_primary_key_table($table)
	{
	   $query="SHOW INDEX FROM $table";
	   $results=$this->db->query($query);
	   $data=$results->row_array();
	   return $data['Column_name'];
		
	}
	
	///// Check availability of  row ////////
    function checkAvailability($select,$table,$where,$rows) 
    {
       $this->db->select($select);
	   $this->db->from($table);
	   $this->db->where($where);
	   $query=$this->db->get();
	   if($rows==1)
	   {
	   		return $query->row_array();
	   }
	   else if($rows==2)
	   {
		   return $query->result_array();
	   }
	}
	
	
		
	/////// Update Records ///////////////
	function updateData($table,$data,$where)
	{
      $this->db->where($where);
      $this->db->update($table,$data);
	}
	/////// Update Records ///////////////
	
	
	////inserting data into tables ////////	
	function insertData($table,$data)                                   
	{
	    $sql = $this->db->insert_string($table,$data);
        $this->db->query($sql);
	    $last_id = $this->db->insert_id();	
		return $last_id;
	}
	////inserting data into tables ////////	
	
	function insertBookingData($table,$data)                                   
	{
	    $sql = $this->db->insert_string($table,$data);
        $this->db->query($sql);
	    $affected_rows = $this->db->affected_rows();	
		return $affected_rows;
	}
	function insertLocationData($table,$data)                                   
	{
	    $sql = $this->db->insert_string($table,$data);
        $this->db->query($sql);
	    $affected_rows = $this->db->affected_rows();	
		return $affected_rows;
	}

	

	function record_exist($table_name,$where=array())
	{
		if(!empty($where))
		{
			foreach($where as $key=>$value)
			{
				$this->db->where($key,$value);				
				
			}
				
		}
		// mobile_key column exist in all table so we can use that
		$this->db->select('device_id');
		$this->db->from($table_name);
		
		$query = $this->db->get();
		
		if($query->num_rows()>0)
		{
				
				return true;
		
		}
		else
		{
			
			return false;	
		}	
	}
	/*function get_bus_by_operator($operator_id){
		$query="SELECT GROUP_CONCAT(DISTINCT bus_id) as buses FROM `staff_assigned` WHERE `operator`=".$operator_id;
	    $results=$this->db->query($query);
	    return $results->result();
	}*/
	function get_bus_by_operator($operator_id){
		$query="SELECT GROUP_CONCAT(DISTINCT id) as buses from bus WHERE operator_id IN(".$operator_id.")";
	    $results=$this->db->query($query);
	    return $results->result();
	}
}