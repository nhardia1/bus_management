<?php
class Operator_model extends CI_Model 
{

	public function __construct()
	{
		
	  	//$this->load->database();
		parent::__construct();
		$this->load->database();
		$this->load->model('routes_model');

	}
	
	public function get_bus_by_operator($operator_id){
		$query="SELECT GROUP_CONCAT(DISTINCT bus_id) as buses FROM `staff_assigned` WHERE `operator`=".$operator_id;
	    $results=$this->db->query($query);
	    $buses= $results->row_array();
	   
	    if($buses['buses']!=''){
	    	$busquery="SELECT * from bus WHERE id IN(".$buses['buses'].")";
		    $busresults=$this->db->query($busquery);
		    return $busresults->result_array();
	    }
	}
	public function get_bus_by_operator_id($operator_id){
	    	$busquery="SELECT DISTINCT(id) from bus WHERE operator_id IN(".$operator_id.")";
		    $busresults=$this->db->query($busquery);
		    return $busresults->result_array();
	    
	}
	public function get_bus_details($busids){
		$this->db->select('*');
        $this->db->from('bus'); 
        $this->db->join('bus_photos','bus_photos.bus_id = bus.id','left');
        $this->db->where('bus.id',array('10','11'));
        $this->db->order_by('bus.id','asc');         
        $query = $this->db->get(); 
        if($query->num_rows() != 0)
        {
            return $query->result_array();
        }
        else
        {
            return false;
        }
	}
	function getAllWhereData($fields,$table,$where)
	{
		$this->db->distinct();
		$this->db->select($fields);
		$this->db->from($table);
		$this->db->where($where);
   	    $query=$this->db->get();
		return $query->result();
	}
	/*function get_multi_search($from,$to,$inputdate){

		if($from!='' && $to!='' && $inputdate!=''){
			$query="SELECT * FROM (`route`) JOIN `bus_route_validity` ON `bus_route_validity`.`route_id` = `route`.`id` WHERE `route`.`source_city` = '$from' AND `route`.`destination_city` = '$to' AND (valid_from <= '$inputdate' AND valid_to >= '$inputdate')";
			$results=$this->db->query($query);
	    	$output= $results->result();
	    	if(empty($output)){
	    		$query="SELECT DISTINCT(route_id) FROM (`route_stoppage`)  WHERE `route_stoppage`.`city_id` = '$from' OR `route_stoppage`.`city_id` = '$to'";
				$results=$this->db->query($query);
		    	$output= $results->result();
		    	return $output;
	    	}else{
	    		return $output;
	    	}
		}
	}*/
	function get_multi_search($from,$to,$inputdate){
		if($from!='0' && $to!='0' && $inputdate!=''){
			$rquery="SELECT route_id FROM (`bus_route_validity`)  WHERE (valid_from <= '$inputdate' AND valid_to >= '$inputdate')";
			$results=$this->db->query($rquery);
	    	$output= $results->result();
	    	//Check in Route
	    	foreach($output as $out){
	    		$route_id=$out->route_id;
	    		$query="SELECT id FROM (`route`)  WHERE `route`.`source_city` = '$from' AND `route`.`destination_city` = '$to' AND id=$route_id";
				$results=$this->db->query($query);
		    	$outp= $results->result_array();
		    	if(empty($outp)){
		    		$out=$this->routes_model->route_stoppages_details($route_id);
					if(in_multiarray($from, $out,"id")==1 && in_multiarray($to, $out,"id")==1){
							$finaloutput[]=$route_id;
					}else if(in_multiarray($to, $out,"id")==1){
						$query="SELECT id FROM (`route`)  WHERE `route`.`source_city` = '$from' AND id=$route_id";
						$results=$this->db->query($query);
				    	$count= $results->num_rows();
				    	if($count>0){
				    		$finaloutput[]=$route_id;
				    	}
					}else if(in_multiarray($from, $out,"id")==1){
						$query="SELECT id FROM (`route`)  WHERE `route`.`destination_city` = '$to' AND id=$route_id";
						$results=$this->db->query($query);
				    	$count= $results->num_rows();
				    	if($count>0){
				    		$finaloutput[]=$route_id;
				    	}

					}
		    	}else{
					foreach ($outp as $value) {
							$finaloutput[]=$value['id'];
					}	
		    		
		    	}

	    	}
		}else if($from!='0' && $inputdate!=''){
			$rquery="SELECT route_id FROM (`bus_route_validity`)  WHERE (valid_from <= '$inputdate' AND valid_to >= '$inputdate')";
			$results=$this->db->query($rquery);
	    	$output= $results->result();
	    	//Check in Route
	    	foreach($output as $out){
	    		$route_id=$out->route_id;
	    		$query="SELECT id FROM (`route`)  WHERE `route`.`source_city` = '$from' AND id=$route_id";
				$results=$this->db->query($query);
		    	$outp= $results->result_array();
		    	if(!empty($outp)){
		    		foreach ($outp as $value) {
							$finaloutput[]=$value['id'];
					}	
		    	}else{
		    		$out=$this->routes_model->route_stoppages_details($route_id);
					if(in_multiarray($from, $out,"id")==1){
							$finaloutput[]=$route_id;
					}

		    	}

	    	}

		}else if($to!='0' && $inputdate!=''){
			$rquery="SELECT route_id FROM (`bus_route_validity`)  WHERE (valid_from <= '$inputdate' AND valid_to >= '$inputdate')";
			$results=$this->db->query($rquery);
	    	$output= $results->result();
	    	//Check in Route
	    	foreach($output as $out){
	    		$route_id=$out->route_id;
	    		$query="SELECT id FROM (`route`)  WHERE `route`.`destination_city` = '$to' AND id=$route_id";
				$results=$this->db->query($query);
		    	$outp= $results->result_array();
		    	if(!empty($outp)){
		    		foreach ($outp as $value) {
							$finaloutput[]=$value['id'];
					}	
		    	}else{
		    		$out=$this->routes_model->route_stoppages_details($route_id);
					if(in_multiarray($to, $out,"id")==1){
							$finaloutput[]=$route_id;
					}

		    	}

	    	}

		}else{
			$rquery="SELECT route_id FROM (`bus_route_validity`)  WHERE (valid_from <= '$inputdate' AND valid_to >= '$inputdate')";
			$results=$this->db->query($rquery);
	    	$output= $results->result();
	    	foreach($output as $out){
	    		$finaloutput[]=$out->route_id;
	    	}

		}
		return $finaloutput;
	}
	function get_busid_route($route_id,$operator_id){
		$this->db->select('bus_id');
        $this->db->from('bus'); 
        $this->db->join('bus_route_validity','bus_route_validity.bus_id = bus.id');
        $this->db->where('bus.operator_id',$operator_id);
        $this->db->where('bus.is_deleted',0);
        $this->db->where('bus_route_validity.route_id',$route_id);
        $query = $this->db->get('');  
		if($query->num_rows() >0)
		{
			$row=$query->row();
			return $row->bus_id;
		}

	}
	function get_busid_by_route($route_id){
		$session=$this->session->userdata('user_detail');
		$this->db->select('bus_id');
        $this->db->from('bus'); 
        $this->db->join('bus_route_validity','bus_route_validity.bus_id = bus.id');
        $this->db->where('bus.operator_id',$session['id']);
        $this->db->where('bus.is_deleted',0);
        $this->db->where('bus_route_validity.route_id',$route_id);
        $query = $this->db->get('');  
       // echo $this->db->last_query();
        if($query->num_rows() >0)
		{
			$row=$query->row();
			return $row->bus_id;
		}
	}
}
?>