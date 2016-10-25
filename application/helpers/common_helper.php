<?php
function convert_datatable_date($dt)
{
    return date("d-m-Y",strtotime($dt));
}


function get_staff_type($staffid)
{
    if($staffid == 1)
        return 'Conductor';
    elseif($staffid == 2)
        return 'Driver';
    elseif($staffid == 3)
        return 'Helper';
    elseif($staffid == 4)
        return 'Other';
    else
        return 'N/A';     

}
function get_new_staff_type($staffid)
{
    if($staffid == 1)
        return 'Agency';
    elseif($staffid == 2)
        return 'Operator';
    elseif($staffid == 3)
        return 'Driver';
    elseif($staffid == 4)
        return 'Conductor';
    else
        return 'N/A';     

}


//Code for generating SNO ---------------------------------------
$sno_c = 1;
$sno_da = 1;

function generate_datatable_sno($typ)
{
    if($typ == "c")
    {
	    global $sno_c;
    	
    	return $sno_c = $sno_c + 1;
    }
    elseif($typ == "da")
    {
	    global $sno_da;
    	
    	return $sno_da = $sno_da + 1;
    }	
}


function convert_datatable_busname($bus_name,$bus_number)
{
    return $bus_name.'<br> <span class="bus_helper"> ('.$bus_number.') </span>';
}

function convert_datatable_busname_number($bus_id)
{
    $ci=& get_instance();
   
    $ci->load->database();     
    
    $ci->db->select('name,bus_number');
   
    $ci->db->where('is_deleted',0);
    
    $ci->db->where('id',$bus_id);
    
    $query = $ci->db->get('bus');
    
    $bus=$query->row_array();
    
    return $bus['name'].'<br><span class="bus_helper"> ('.$bus['bus_number'].') </span>';
}


function model_for_bus_route_details($max_trip,$from_date,$tos_dates,$bus_id,$route_id)
{

    return "<a href='#'><div onclick=\"get_bus_route_details_info('$from_date','$tos_dates',$bus_id,$route_id,$max_trip);\">view trip time </div></a>";

}


function all_upper_case($str)
{
    return strtoupper($str);
}

function first_upper_case($str)
{
    return ucfirst($str);
}

function convert_datatable_routename_number($route_id)
{
    $ci=& get_instance();
   
    $ci->load->database();     
    
    $sql = "SELECT  r.name, (SELECT c.name FROM city c WHERE c.id = r.source_city) as source,
                            (SELECT c.name FROM city c WHERE c.id = r.destination_city) as destination
                FROM route r
                WHERE r.id = '$route_id' 
                LIMIT 1";
    
    $query = $ci->db->query($sql);
    
    $arr = $query->row_array();
    
    $path = "<br><span class='help_txt'>(".$arr['source']." > ".$arr['destination'].")</span>";

    return ucfirst($arr['name']).$path;
}

function convert_datatable_staff_name($id)
{
    $ci=& get_instance();
   
    $ci->load->database();     
    
    $ci->db->select('name');
   
    $ci->db->where('is_deleted',0);
    
    $ci->db->where('id',$id);
    
    $query = $ci->db->get('staff');
    
    $staff=$query->row_array();
    
    return $staff['name'];
}

/*function staff_type(){
    return array(1 => 'कंडक्टर',2 => 'ड्राइवर',3 => 'हेल्पर',4 => 'अन्य'); 
}*/
function staff_type(){

    $ci=& get_instance();
   
    $ci->load->database();     
    
    $ci->db->select('role_id,user_type');
       
    $query = $ci->db->get('user_roles');
    
   return $query->result();

}
function get_agency(){
    $ci=& get_instance();
    $ci->load->database();     
    $ci->db->select('id,name');
    $ci->db->where('staff_type_num',1);
    $query = $ci->db->get('staff');
    return $query->result();

}
function get_seat_type(){
    $ci=& get_instance();
    $ci->load->database();     
    $ci->db->select('id,value');
    $ci->db->where('type','seat_type');
    $query = $ci->db->get('seat_type');
    return $query->result();
}
function get_coach_type(){
    $ci=& get_instance();
    $ci->load->database();     
    $ci->db->select('id,value');
    $ci->db->where('type','coach_type');
    $query = $ci->db->get('seat_type');
    return $query->result();
}
function get_seat_allocation(){
    $ci=& get_instance();
    $ci->load->database();     
    $ci->db->select('id,value');
    $ci->db->where('type','seat_allocation');
    $query = $ci->db->get('seat_type');
    return $query->result();
}
function get_configure_type($id){
    $ci=& get_instance();
    $ci->load->database();     
    $ci->db->select('value');
    $ci->db->where('id',$id);
    $query = $ci->db->get('seat_type');
    $row= $query->row();
    return $row->value;
}

function getBusTemplate(){
    
        $ci=& get_instance();
    $ci->db->select('id,template_name,seat_type_name,seat_cocach_type,seat_capacity');
    $query = $ci->db->get('seat_configuration');
    return $query->result();
}
function createFile($fileName, $fileContent, $path){

        $fh = fopen($path.$fileName, 'a+') or die("can't open file");

        fwrite($fh, $fileContent);

        fclose($fh);

    }
function getStateName($city_id){
        $ci=& get_instance();
        $ci->load->database();
        $sql="select name from state where id=(SELECT state_id from city where id=$city_id)";
        $query = $ci->db->query($sql);
        $row= $query->row();     
        return $row->name;
    
}
/*function get_operator_by_agency($agency_id){

    $ci=& get_instance();
    $ci->load->database();     
    $ci->db->select('id');
    $ci->db->where('agency_id',$agency_id);
    $query = $ci->db->get('staff');
    $row= $query->row();
    return $row->id;

}*/
function get_operator_by_agency($agency_id){

    $ci=& get_instance();
    $ci->load->database();     
    $ci->db->select('id');
    $ci->db->where('agency_id',$agency_id);
    $query = $ci->db->get('staff');
    $row= $query->result_array();
    //echo $ci->db->last_query();
    if(!empty($row)){
        foreach($row as $val){
            $operator_id[]=$val['id'];    
        }
        
    }
    return implode(',',$operator_id);

}
function get_operator_name($operator_id){
    $ci=& get_instance();
    $ci->load->database();     
    $ci->db->select('name');
    $ci->db->where('id',$operator_id);
    $query = $ci->db->get('staff');
    $row= $query->row();
    return $row->name;
}
function in_multiarray($elem, $array,$field)
{
    $top = sizeof($array) - 1;
    $bottom = 0;
    while($bottom <= $top)
    {
        if($array[$bottom][$field] == $elem)
            return true;
        else 
            if(is_array($array[$bottom][$field]))
                if(in_multiarray($elem, ($array[$bottom][$field])))
                    return true;

        $bottom++;
    }        
    return false;
}
 


?>