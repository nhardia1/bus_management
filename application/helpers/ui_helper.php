<?php
function getCities(){
    $ci=& get_instance();
    $ci->load->database();     
    $ci->db->select('id,name');
    $ci->db->where('is_deleted',0);
    $query = $ci->db->get('city');
    return $query->result();
}
function getBusPhotos($bus_id){
	$ci=& get_instance();
    $ci->load->database();     
    $ci->db->select('id,image');
    $ci->db->where('is_deleted',0);
    $ci->db->where('bus_id',$bus_id);
    $query = $ci->db->get('bus_photos');
    return $query->result();
}
function getSingleBusPhoto($bus_id){
	$ci=& get_instance();
    $ci->load->database();     
    $ci->db->select('image');
    $ci->db->where('is_deleted',0);
    $ci->db->where('bus_id',$bus_id);
    $ci->db->limit(1);
    $query = $ci->db->get('bus_photos');
    return $query->row();
}
function getBusStaffPhoto($bus_id){
    $ci=& get_instance();
    $ci->load->database();     
    $sql = "select staff_assigned.driver,staff.name,staff.profile_image from staff_assigned INNER JOIN staff ON staff_assigned.driver=staff.id WHERE staff_assigned.bus_id=".$bus_id." limit 1";
    $query = $ci->db->query($sql);
    $arr = $query->result();
    return $arr;
}

function getBusDetails($bus_id){
	$ci=& get_instance();
    $ci->load->database();     
    $ci->db->select('name,bus_number');
    $ci->db->where('is_deleted',0);
    $ci->db->where('id',$bus_id);
    $query = $ci->db->get('bus');
    $row=$query->row();
    return $row;
}
function getBusTimingByCityId($bus_id,$route_id,$city_id){
    $ci=& get_instance();
    $ci->load->database();   
    $sql = "SELECT 
                   arrival_hour,arrival_minute,arrival_am_pm,departure_hour,departure_minute,departure_am_pm
                FROM
                    bus_timing
                WHERE 
                    is_deleted = 0 AND route_id = $route_id AND bus_id = $bus_id AND city_id= $city_id";
    
    $query = $ci->db->query($sql);
    
    $arr = $query->result();
  
    return $arr;
}
function getStaffCompleteDetails($busid,$fordate){
    $ci=& get_instance();
    $ci->load->database();   
    $sql = "SELECT  id,bus_id,driver,conductor,
            (
                SELECT   staff.name As driver_name
                FROM    staff
                WHERE   staff.id = staff_assigned.driver
                ) As driver_name,
                    (
                SELECT   staff.profile_image As driver_image
                FROM    staff
                WHERE   staff.id = staff_assigned.driver
                ) As driver_image,
            (
                SELECT  staff.name As conductor_name
                FROM    staff
                WHERE   staff.id = staff_assigned.conductor
                ) As conductor_name,
            (
                SELECT  staff.profile_image As conductor_image
                FROM    staff
                WHERE   staff.id = staff_assigned.conductor
                ) As conductor_image,
            
            (
                SELECT  bus.name As name
                FROM    bus
                WHERE   bus.id = staff_assigned.bus_id
                ) As name

            FROM staff_assigned 
            
            
            WHERE is_deleted=0 AND bus_id='$busid' AND fordate='$fordate' limit 1";
    
    $query = $ci->db->query($sql);
    
    $arr = $query->result();
  
    return $arr;
   
}
function getStaffCompleteDetailsWithoutDate($busid){
    $ci=& get_instance();
    $ci->load->database();   
    $sql = "SELECT  id,bus_id,driver,conductor,
            (
                SELECT   staff.name As driver_name
                FROM    staff
                WHERE   staff.id = staff_assigned.driver
                ) As driver_name,
                    (
                SELECT   staff.profile_image As driver_image
                FROM    staff
                WHERE   staff.id = staff_assigned.driver
                ) As driver_image,
            (
                SELECT  staff.name As conductor_name
                FROM    staff
                WHERE   staff.id = staff_assigned.conductor
                ) As conductor_name,
            (
                SELECT  staff.profile_image As conductor_image
                FROM    staff
                WHERE   staff.id = staff_assigned.conductor
                ) As conductor_image,
            
            (
                SELECT  bus.name As name
                FROM    bus
                WHERE   bus.id = staff_assigned.bus_id
                ) As name

            FROM staff_assigned 
            
            
            WHERE is_deleted=0 AND bus_id='$busid' ORDER BY id DESC limit 1";
    
    $query = $ci->db->query($sql);
    
    $arr = $query->result();
  
    return $arr;
   
}
function getBookedSeat($busid,$route_id,$seat_id,$booked_date){
    $ci=& get_instance();
    $ci->load->database();   
    $sql = "SELECT 
                  booked_to_passanger_type
                FROM
                    seat_booking_history
                WHERE 
                    is_deleted = 0 AND route_id = $route_id AND bus_id = $busid AND seat_id=$seat_id AND created_date= '$booked_date'";
    
    $query = $ci->db->query($sql);
    if($query->num_rows()>0){
            $row= $query->row();
            if($row->booked_to_passanger_type==2){
                $class='girl-booked';
            }else if($row->booked_to_passanger_type==3){
                $class='health-booked';
            }elseif ($row->booked_to_passanger_type==1) {
                $class='male-booked';
            }else{
                $class='';
            }
    }else{
        $class='';

    }
    return $class;
    

}
function getLuggageDetails($busid,$route_id,$booked_date){
    $ci=& get_instance();
    $ci->load->database();   
    $sql = "SELECT 
                  id,luggage_size,booked_to_owner_phone
                FROM
                    luggage_booking_history 
                WHERE 
                    is_deleted = 0 AND route_id = $route_id AND bus_id = $busid  AND created_date= '$booked_date'";
    
    $query = $ci->db->query($sql);

    $arr = $query->result();
  
    return $arr;

}
function getTotalDistanceFromSource($route_id){
     $ci=& get_instance();
     $ci->load->database();   
     $sql = "SELECT 
                  distance_from_source
                FROM
                    route 
                WHERE 
                    is_deleted = 0 AND id = $route_id";
    
    $query = $ci->db->query($sql);

    $arr = $query->result();
  
    return $arr[0]->distance_from_source;

}
function caculateDistanceFromSource($bus_id,$route_id,$source_city,$fordate){
     $ci=& get_instance();
     $ci->load->database();   
     $sql = "SELECT 
                  lattitude,longitude
                FROM
                    bus_location  
                WHERE 
                    is_deleted = 0 AND route_id = $route_id AND bus_id=$bus_id AND date(created_date)='$fordate'  ORDER BY id DESC LIMIT 1";
    
    $query = $ci->db->query($sql);
    $arr = $query->row();
    $lat2=$arr->lattitude;
    $long2=$arr->longitude;
    $s_city=getSourceCityLatLng($source_city);
    $lat1=$s_city->lattitude;
    $long1=$s_city->longitude;
    if($lat1!='' && $lat2!='' && $long1!='' && $long2!=''){
        $distance=getDrivingDistance($lat1, $lat2, $long1, $long2);
    }else{
        $distance=0;
    }
    return $distance;


}
function getSourceCityLatLng($source_city){
     $ci=& get_instance();
     $ci->load->database();   
     $sql = "SELECT 
                  lattitude,longitude
                FROM
                    city  
                WHERE 
                    is_deleted = 0 AND id = $source_city";
    
    $query = $ci->db->query($sql);
    $arr = $query->row();
    return $arr;


}



function getDrivingDistance($lat1, $lat2, $long1, $long2)
{
    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&mode=driving&language=pl-PL";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    $response_a = json_decode($response, true);
    $elements=$response_a['rows'][0]['elements'][0]['distance']['text'];
    $find = array(","," km");
    $replace = array(".","");
    $final=str_replace($find,$replace,$elements);
    return ($final);
}
function getCityByState($state_id){
    $ci=& get_instance();
    $ci->load->database();   
    $sql = "SELECT 
                  id,name
                FROM
                    city  
                WHERE 
                    is_deleted = 0 AND state_id=$state_id";
    
    $query = $ci->db->query($sql);
    $cities = $query->result();
    return $cities;

}
function getStateCityDropDown($selected_city){
        $ci=& get_instance();
        $ci->load->database();   
        $sql = "SELECT 
                      id,name
                    FROM
                        state  
                    WHERE 
                        is_deleted = 0";
        
        $query = $ci->db->query($sql);
        $states = $query->result();
       
        $final='';
        foreach ($states as $value) {
            $cities=getCityByState($value->id);
            if(!empty($cities)){
                 $final .='<optgroup label="'.$value->name.'">';
                 $final .='<option value="0">Select Option</option>';
                foreach ($cities as $city) {
                    if(isset($selected_city) && $selected_city==$city->id){
                        $selected='selected';
                    }else{
                        $selected='';
                    }   
                    $final .='<option value="'.$city->id.'" '.$selected.'>'.$city->name.'</option>';
                }

            }
            $final .='</optgroup>';
            # code...
        }
    return $final;

}




?>