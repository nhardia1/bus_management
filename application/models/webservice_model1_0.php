<?php
class Webservice_model1_0 extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
	
	
	////// Check availability of single or multiple row ////////
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
	////// Check login of admin ////////
	
	////// Check availability of single or multiple row ////////
    function checkAvailabilityOfUser($select,$table,$where,$rows) 
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
	////// Check login of admin ////////
	
	 function checkAvailabilityNew($select,$table,$where) 
    {
       $this->db->select($select);
	   $this->db->from($table);
	   $this->db->where($where,null);
	   $query=$this->db->get();
	   return $query->row_array();
    }
	

	
	////// Check availability of single row ////////
    function getAllLatestData($select,$table,$where,$rows) 
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
	////// Check login of admin ////////
	
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
	
	////Get all table records order by ////////	
	function getAllData($fields,$table,$order_id)
	{
		$this->db->select($fields);
		$this->db->from($table);
  	    $this->db->order_by($order_id,'DESC');
   	    $query=$this->db->get();
		return $query->result();
	}
	////Get all table records order by ////////	
	
	////Get all table records where order by ////////	
	function getAllWhereOrderData($fields,$table,$where,$orders)
	{
		$this->db->select($fields);
		$this->db->from($table);
		$this->db->where($where);
  	    $this->db->order_by($orders);
   	    $query=$this->db->get();
		return $query->result();
	}
	////Get all table records where order by ////////
	
	
	////Get all table records where order by ////////	
	function getAllWhereOrderLimitData($fields,$table,$where,$orders,$row,$lower,$upper)
	{ 
		$this->db->select($fields);
		$this->db->from($table);
		$this->db->where($where);
  	    $this->db->order_by($orders);
		$this->db->limit($lower, $upper);
   	    $query=$this->db->get();
		if($row==1)
		{
			return $query->row_array();
		}
		else
		{
			return $query->result_array();
		}
	}
	////Get all table records where order by ////////		
	
	////Get all table records where order by ////////	
	function getAllWhereData($fields,$table,$where)
	{
		$this->db->select($fields);
		$this->db->from($table);
		$this->db->where($where);
   	    $query=$this->db->get();
		return $query->result();
	}
	////Get all table records where order by ////////	

	
	////Delete records from tables ////////	
	function deleteRecord($table,$where)
	{
		$this->db->where($where);
		$this->db->delete($table);
	}
	////Delete records from tables ////////	
	
	
	function getAllActiveUsers()
	{
		$this->db->select('users.*,count( magazine_purchased.magazine_id ) AS total');
		$this->db->from('users');
		$this->db->join('magazine_purchased', 'users.user_id = magazine_purchased.user_id', 'left');
		$this->db->where('users.isActive','1');
  	    $this->db->group_by('users.user_id');
   	    $query=$this->db->get();
		return $query->result_array();
	}
	
	
	
	function getAllUserMagazineDetails($user_id)
	{
		$this->db->select('magazine_purchased.magazine_id, magazines.cover_url, magazines.title,magazines.description');
		$this->db->from('magazine_purchased');
		$this->db->join('magazines', 'magazine_purchased.magazine_id = magazines.magazine_id');
		$this->db->where('magazine_purchased.user_id',$user_id);
   	    $query=$this->db->get();
		return $query->result_array();
	}
	
	function getActiveMagazines($admin_id)
	{
		/*$query="SELECT `magazine_id` as id,`title`,DATE_FORMAT(issue_date,'%M, %Y') as date,case when newstand_item = '1' then 'FREE' else (CONCAT('$',price)) end as price,CONCAT('".base_url()."public/cover_images/',cover_url) as cover_url , case when newstand_item = '0' then 'NO' else 'YES' end as newstand_item,`isActive` from magazines order by magazine_id DESC";*/
		$query="SELECT `magazine_id` as id,`title`,DATE_FORMAT(issue_date,'%M, %Y') as date,CONCAT('$',price) as price,CONCAT('".base_url()."public/cover_images/',cover_url) as cover_url , case when newstand_item = '0' then 'NO' else 'YES' end as newstand_item,`isActive` from magazines where admin_id='".$admin_id."' order by issue_date DESC";
	   $results=$this->db->query($query);
	   	//print_r($this->db->last_query());
		return $results->result_array();
	
	}
	
	////// Check user device login ////////
    function checkAvailabilityOfUserdevice($select,$table,$where,$rows) 
    {
       $this->db->select($select);
	   $this->db->from($table);
	   $this->db->where($where);
	   $query=$this->db->get();
	   return $query->num_rows();
	   /*if($query->num_rows()>0)
	   return $query->result_array();
	   else 
	   return false;*/	
    }
	
}
?>