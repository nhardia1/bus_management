<?php $this->load->view('frontend/head'); ?>

<div class="Location">
  <a  href="#contact"  data-toggle="modal"></a>
</div>

<!--Driver Photo-->
        <div class="modal" id="contact" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog" style="width:94%; margin-top:2%; height:92%;">
            <div class="modal-content" style="height:100%">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove-circle"></span></button>
                <h4 class="modal-title" id="myModalLabel">Map Root</h4>
              </div>
              <div class="modal-body" id="map">
                
              </div>
            </div>
          </div>
        </div>
<div class="container outer">
  <!--Bus list Begins, Repeat this div for bus list-->
    <?php if(isset($msg)){?>
    <div class="row" style="padding:0">
        <div class="alert alert-warning" role="alert">
        <strong><?php echo $msg."<style>.outer{border:none !important;}</style>";?></strong> 
      </div>
    </div>  
    <?php }?>
  <?php 
    $j=0;
     $keys = array_keys($route_details);
      if(isset($_GET['sort_by']) && $_GET['sort_by']==2){
          array_multisort(($route_details), SORT_DESC, $route_details, $keys);
          $route_details = array_combine($keys, $route_details);
      }else{
         array_multisort(($route_details), SORT_ASC, $route_details, $keys);
         $route_details = array_combine($keys, $route_details);
      }
    foreach ($route_details as $key => $route_detail) {

              $keyexplode=explode("_",$key);
              $busid=$keyexplode[0];
              $routeid=$keyexplode[1];
              $singleimage=getSingleBusPhoto($busid);
              if($singleimage->image!=''){
                $busimage=base_url().'public/uploads/bus/'.$singleimage->image;
              }else{
                $busimage=base_url().'assets/images/imgbus_fullsize.png';
              }
              $busDetails=getBusDetails($busid);
              for ($k=0; $k < count($route_detail) ; $k++) { 
                  $routepath['route'.$j][]=($route_detail[$k]->name).', '.getStateName($route_detail[$k]->id);
              }

              $details=getStaffCompleteDetails($busid);
              if(!empty($details)){
                  if($details[0]->driver_image!=''){
                      $driver_image=base_url().'public/uploads/staff/'.$details[0]->driver_image;
                  }else{
                    $driver_image=base_url().'assets/images/img_staff_fullsize.png';
                  }
                  if($details[0]->conductor_image!=''){
                      $conductor_image=base_url().'public/uploads/staff/'.$details[0]->conductor_image;
                  }else{
                       $conductor_image=base_url().'assets/images/img_staff_fullsize.png';

                  }
              }else{
                $driver_image=base_url().'assets/images/img_staff_fullsize.png';
                $conductor_image=base_url().'assets/images/img_staff_fullsize.png';

              }

      //echo $busid.'driver'.$driver_image;
        
       $bookeddate=date("Y-m-d");
      

     ?>
    <div class="bus-list clearfix">
      <div class="col-md-12 col-sm-12" style="padding:0">
      <div class="row col-md-4 col-sm-4 pull-left clearfix"><?php echo $busDetails->name;?></div>
      <div class="col-md-10 col-sm-10 book_ticket"  id="<?php echo $busid;?>">
        <input id="ex<?php echo $busid;?>" type="text" data-slider-ticks-snap-bounds="30"/>
      </div>

    
        <div class="col-md-1 col-sm-1" style="padding:0">
            <div class="pull-right thumbnails">
             <a class="bus" id="bus1" onclick="openGallery(<?php echo $busid;?>)"> <img src="<?php echo $busimage;?>" name="aboutme" width="40" height="40" id="aboutme"> </a>
          </div>
        </div>
        <div class="col-md-1 col-sm-1" style="padding:0;">
        <?php 
        ?>
          <div class="pull-right thumbnails">
            <a  onclick="openStaff(<?php echo $busid;?>)"> <img src="<?php echo $driver_image;?>" name="aboutme"  width="40" height="40"> </a>
          </div>
        </div>
        
        <!--Bus Photo-->
      <div class="modal fade" id="modal-gallery-<?php echo $busid?>" role="dialog">
      <div class="modal-dialog" >
        <div class="modal-content">
          <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove-circle"></span></button>
                <h4 class="modal-title"><?php echo $busDetails->name;?>&nbsp;&nbsp;<?php echo $busDetails->bus_number;?></h4>
          </div>
          <div class="modal-body">
               <div id="modal-carousel-<?php echo $busid;?>" class="carousel">
                  <div class="carousel-inner">
                    <?php 
                    $pics=getBusPhotos($busid);
                    
                    if(!empty($pics)){
                      $i=1;
                      foreach ($pics as $value) {
                        if($value!=''){
                            $busimg=base_url().'public/uploads/bus/'.$value->image;
                            }else{
                            $busimg=base_url().'assets/images/imgbus_fullsize.png';
                        }
                      ?>
                       <div  class="item <?php if($i==1){ echo "active";}?>">
                          <img src="<?php echo $busimg;?>">
                        </div>
                     <?php $i++;} ?>

                    <?php }else{ ?>

                      <div  class="item active">
                          <img src="<?php echo base_url().'assets/images/imgbus_fullsize.png';?>">
                        </div>

                    <?php } ?>
                    
                      
                  </div>
                </div>
          </div>
          <div class="modal-footer">
            <a class="btn btn-primary pull-left" href="#modal-carousel-<?php echo $busid;?>" data-slide="prev"><i class="glyphicon glyphicon-chevron-left"></i></a>
            <a class="right btn btn-primary" href="#modal-carousel-<?php echo $busid;?>" data-slide="next"><i class="glyphicon glyphicon-chevron-right"></i></a>
          </div>
        </div>
      </div>
    </div>
        <!--bus Photo-->
        
        <!--Driver Photo-->
        <div class="modal fade" id="modal-staff-<?php echo $busid;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog" style="width:332px;">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove-circle"></span></button>
              </div>
              <div class="modal-body">
              <div id="modal-carousel-staff-<?php echo $busid;?>" class="carousel">
                  <div class="carousel-inner">
                    <div  class="item active">
                          <img src="<?php echo $driver_image;?>">
                          <div class="carousel-caption">
                          <h3><?php if($details[0]->driver_name!=''){echo $details[0]->driver_name;}?></h3>
                          </div>
                        </div>
                        <div  class="item">
                          <img src="<?php echo $conductor_image;?>">
                          <div class="carousel-caption">
                            <h3><?php if($details[0]->conductor_name!=''){echo $details[0]->conductor_name;}?></h3>
                            </div>
                        </div>
                      
                  </div>
                </div>
                </div>

                 <div class="modal-footer">
                    <a class="btn btn-primary pull-left" href="#modal-carousel-staff-<?php echo $busid;?>" data-slide="prev"><i class="glyphicon glyphicon-chevron-left"></i></a>
                    <a class="right btn btn-primary" href="#modal-carousel-staff-<?php echo $busid;?>" data-slide="next"><i class="glyphicon glyphicon-chevron-right"></i></a>
                  </div>
            </div>
          </div>
        </div>
        <!--/Driver Photo-->
    </div>
    </div>
    <!--Bus list End-->
    
    <!--Bus Details Begins-->
  <div class="bus-details clearfix" style="display:none;" id="ticket_book_<?php echo $busid;?>">
      <div class="col-md-9 bus-frame clearfix">
          <ul class="clearfix" style="margin-bottom:0">
              <li class="pull-left stearing-wheel">
                  <div class="stearing"></div>
                </li>
                <?php 
          $seatcount=53;
          $column=$seatcount/4;
          $row1= round($column);
          $middle_seat=$seatcount-2;
         
        ?>
                <li class="seats-holder pull-left">
                  <div class="busrow clearfix">
                      <ul>
                         <?php 
             for($i=0;$i<=$row1-1;$i++){ 
                $seatno=($i * 4 + 1);
                ?>
                              

                               <li class="seat"><a  href="javascript:void(0);" class="<?php echo getBookedSeat($busid,$routeid,$seatno,$bookeddate);?>" id="<?php echo $seatno;?>" onclick="selectSeats(<?php echo $busid?>,<?php echo $seatno?>);" ><?php echo $seatno;?></a></li>
              <?php }
             ?>
                        </ul>
                    </div>
                    <div class="busrow clearfix">
                      <ul>
                           <?php 
             for($i=0;$i<=$row1-1;$i++){ 
                $seatno=($i * 4 + 2);
                ?>
                               <li class="seat"><a href="javascript:void(0);" id="<?php echo $seatno;?>" class="<?php echo getBookedSeat($busid,$routeid,$seatno,$bookeddate);?>" onclick="selectSeats(<?php echo $busid?>,<?php echo $seatno?>);" ><?php echo $seatno;?></a></li>
              <?php }
             ?>
                        </ul>
                    </div>
                    <div class="clearfix">
                      <div class="bus-center pull-left">
                          <!--<ul class="new-pessenger">
                              <li><a href="#" class="Add-pessenger">Add pessenger</a></li>
                                <li><a href="#" class="remove-pessenger">minus</a></li>
                            </ul>-->
                            <!--<ul class="pessenger-count">
                              <li><img src="assets/images/pessenger.png" width="20" height="40" alt="Pessenger"></li>
                                <li><img src="assets/images/pessenger.png" width="20" height="40" alt="Pessenger"></li>
                                <li><img src="assets/images/pessenger.png" width="20" height="40" alt="Pessenger"></li>
                                <li><img src="assets/images/pessenger.png" width="20" height="40" alt="Pessenger"></li>
                                <li><img src="assets/images/pessenger.png" width="20" height="40" alt="Pessenger"></li>
                                <li><img src="assets/images/pessenger.png" width="20" height="40" alt="Pessenger"></li>
                                <li><img src="assets/images/pessenger.png" width="20" height="40" alt="Pessenger"></li>
                                <li><img src="assets/images/pessenger.png" width="20" height="40" alt="Pessenger"></li>
                            </ul>-->
                      </div>
                    <ul class="pull-right">
                      <li class="seat"><a href="javascript:void(0);" id="<?php echo $middle_seat;?>" class="<?php echo getBookedSeat($busid,$routeid,$middle_seat,$bookeddate);?>" onclick="selectSeats(<?php echo $busid?>,<?php echo $middle_seat?>);"><?php echo $middle_seat;?></a></li>
                    </ul>
                    </div>
                    <div class="busrow clearfix">
                      <ul>
                          <?php
             
                           for($i=0;$i<=$row1-1;$i++){ 
                               if($i==($row1-1)){
                              $seatno=($i * 4 + 3)+1;
                                }else{
                                  $seatno=($i * 4 + 3);
                              } 
                              ?>
                               <li class="seat" ><a href="javascript:void(0);" class="<?php echo getBookedSeat($busid,$routeid,$seatno,$bookeddate);?>" id="<?php echo $seatno;?>" onclick="selectSeats(<?php echo $busid?>,<?php echo $seatno?>);"  ><?php echo $seatno;?></a></li>
                            <?php }
                           ?>
                        </ul>
                    </div>
                    <div class="busrow clearfix">
                      <ul>
                           <?php
             
                           for($i=0;$i<=$row1-1;$i++){ 
                               if($i==($row1-1)){
                              $seatno=($i * 4 + 4)+1;
                                }else{
                                  $seatno=($i * 4 + 4);
                              } 
                              ?>
                               <li class="seat"><a href="javascript:void(0);" class="<?php echo getBookedSeat($busid,$routeid,$seatno,$bookeddate);?>" id="<?php echo $seatno;?>" onclick="selectSeats(<?php echo $busid?>,<?php echo $seatno?>);" ><?php echo $seatno;?></a></li>
                          <?php }
                         ?>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>

        <div class="col-md-3 Parcel-Details">
        <a href="javascript:void(0);" onclick="openBooking(<?php echo $busid;?>)" style="display:none;" id="book_modal_<?php echo $busid;?>">Book now</a>
        <table class="table">
        <thead>
            <tr>
                <td>Luggage Selection</td>
                <td>&nbsp;</td>
            </tr>
        </thead>
         <tbody id="luggage_details">
              <?php $luggages=getLuggageDetails($busid,$routeid,$bookeddate);
                  if(!empty($luggages)){
                      foreach ($luggages as $value) { ?>
                        <tr id="<?php echo $value->id;?>"><td><?php echo $value->luggage_size;?></td><td><?php echo $value->booked_to_owner_phone;?></td></tr>
                      <?php }
                  }
              ?>
        </tbody>
    </table>
    </div>
  </div>




 <div class="modal fade booking" id="bus_<?php echo $busid;?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <form name="booking_form" id="booking_form" method="post">
          <div class="modal-dialog" style="width:750px;">
            <div class="modal-content">
              <div class="modal-header booking-header">
                <div class="booking-popup pull-right">
                  <div class="form-group col-md-6" style=" margin-bottom: 0;">
                      <input type="hidden" value="<?php echo $routeid;?>" id="route_id" name="route_id"> 
                      <input type="hidden" value="<?php echo $busid;?>" id="bus_id" name="bus_id">
                      <select name="from_book_city" id="from_book_city" class="from_book_city form-control">
                              <option value="0">Select Source</option>
                              <?php 
                                   for ($L=0; $L < count($route_detail) ; $L++) { 

                                       if($L!=(count($route_detail)-1)){
                                    ?>
                                      <option value="<?php echo $route_detail[$L]->id;?>"><?php echo $route_detail[$L]->name;?></option>
                                  <?php 
                                    }
                                }
                              ?>
                            </select>
                    </div>
                    <div class="form-group col-md-6" style=" margin-bottom: 0;">
                     <select name="to_book_city" id="to_book_city" class="to_book_city form-control" onchange="getFare(<?php echo $busid;?>,<?php echo $routeid;?>);">
                                    <option value="0">Select Destination</option>
                                    <?php 
                                         for ($M=0; $M < count($route_detail) ; $M++) {
                                              if($M!=0){
                                            ?>
                                            <option value="<?php echo $route_detail[$M]->id;?>"><?php echo $route_detail[$M]->name;?></option>
                                        <?php 
                                          }
                                      }
                                    ?>
                            </select>
                    </div>
                </div>
                <h4 class="model-title">Booking</h4>
              </div>
              <div class="modal-body">
                <div class="row" style="width:748px;">
                        <table class="table table-striped custab">
                        <thead>
                            <tr>
                                <th width="60%">Gender</th>
                                <th width="20%">Seat No.</th>
                                <th width="20%" class="text-center">Fare</th>
                            </tr>
                        </thead>
                        <tbody style="height:270px;" id="selected_seats">
                        </tbody>
                        </table>
                        <div class="total">
                        <table>
                          <tbody style="height:inherit !important;">
                          <tr>
                              <td width="60%">&nbsp;</td>
                                <td width="20%">Total Fare</td>
                                <td width="20%" class="text-center" id="total_fare"></td>
                            </tr>
                            </tbody>
                        </table>
                        </div>
          </div>
              </div>
              <div class="modal-footer">
            <a class="btn btn-default pull-left" href="javascript:void(0)" data-dismiss="modal" aria-hidden="true" onclick="cancelTicket(<?php echo $busid;?>);">Cancel</a>
             <a class="right btn btn-primary" href="javascript:void(0)"  onclick="bookSeats(<?php echo $busid;?>)" >Book Ticket</a>
          </div>
            </div>
          </div>
          </form>
        </div>



  
    <!--Bus Details End-->
    <?php $j++; 
    unset($driver_image);
    unset($conductor_image);
    unset($busimage);
  }

    ?>
    <?php //echo json_encode($routepath,JSON_UNESCAPED_UNICODE);?>
</div>

<style>
#map {
  width:96%;
  height:800px;
}
#map * {
    overflow:visible;
}
</style>


<script type="text/javascript">



 
 var center = new google.maps.LatLng(20.5937,78.9629);  
  $(document).ready(function(){
    
  
    

    <?php 
  $M=0;
  foreach ($route_details as $key => $route_detail) { 
  
    $keyexplode=explode("_",$key);
    $busid=$keyexplode[0];
    $routeid=$keyexplode[1];

    $count=count($route_detail)-1;
    for($i=0;$i<=$count;$i++){


      if($i==0){
          $timing=getBusTimingByCityId($busid,$routeid,$route_detail[$i]->id);
          if($timing[0]->departure_minute<2){
            $departure_minute=$timing[0]->departure_minute.'0';
          }else{
            $departure_minute=$timing[0]->departure_minute;
          }
        if($timing[0]->departure_hour!=''){
            $bus_time=$timing[0]->departure_hour.':'.$departure_minute.' '.$timing[0]->departure_am_pm;
        }else{
          $bus_time='';
        }

      }elseif($i==(count($route_detail)-1)){
           $timing=getBusTimingByCityId($busid,$routeid,$route_detail[$i]->id);
          //print_r($timing[0]);die;
          if($timing[0]->arrival_minute<2){
            $arrival_minute=$timing[0]->arrival_minute.'0';
          }else{
            $arrival_minute=$timing[0]->arrival_minute;
          }
        if($timing[0]->arrival_hour!=''){
           $bus_time=$timing[0]->arrival_hour.':'.$arrival_minute.' '.$timing[0]->arrival_am_pm;
        } else{
          $bus_time='';
        } 
      
      }else{

          $timing=getBusTimingByCityId($busid,$routeid,$route_detail[$i]->id);
          if($timing[0]->arrival_minute<2){
            $arrival_minute=$timing[0]->arrival_minute.'0';
          }else{
            $arrival_minute=$timing[0]->arrival_minute;
          }
          if($timing[0]->arrival_hour!=''){
             $bus_time=$timing[0]->arrival_hour.':'.$arrival_minute.' '.$timing[0]->arrival_am_pm;

          }else{
             $bus_time='';
          }
       

      }

      
      $arr[]= ''.$route_detail[$i]->name.'<span class="clearfix">'.$bus_time.'</span>';
      $coun[]=$i;
    }
    
  ?>
       $("#ex<?php echo $busid;?>").slider({
          ticks: <?php echo json_encode($coun);?>,
          ticks_labels: <?php echo json_encode($arr);?>,
          ticks_snap_bounds:4,
          tooltip: 'hide',
          enabled: false,
       });
      
      <?php
    $arr=array();
    $coun=array();
    }
    ?>
    var tmpLatLng;
    var wps=[];
    var call_url='<?php echo base_url();?>operator/getRoutesDetailsForMap';
    

  })
   var directionsService = new google.maps.DirectionsService();
    var num, map, data;
    var requestArray = [], renderArray = [];

    // A JSON Array containing some people/routes and the destinations/stops
    var jsonArray = <?php echo json_encode($routepath,JSON_UNESCAPED_UNICODE);?>;
        
    // 16 Standard Colours for navigation polylines
    var colourArray = ['navy', 'red', 'fuchsia', 'black', 'white', 'lime', 'maroon', 'purple', 'aqua', 'red', 'green', 'silver', 'olive', 'blue', 'yellow', 'teal'];
  function generateRequests(){

        requestArray = [];

        for (var route in jsonArray){
            // This now deals with one of the people / routes

            // Somewhere to store the wayoints
            var waypts = [];
            
            // 'start' and 'finish' will be the routes origin and destination
            var start, finish
            
            // lastpoint is used to ensure that duplicate waypoints are stripped
            var lastpoint

            data = jsonArray[route]

            limit = data.length
            for (var waypoint = 0; waypoint < limit; waypoint++) {
                if (data[waypoint] === lastpoint){
                    // Duplicate of of the last waypoint - don't bother
                    continue;
                }
                
                // Prepare the lastpoint for the next loop
                lastpoint = data[waypoint]

                // Add this to waypoint to the array for making the request
                waypts.push({
                    location: data[waypoint],
                    stopover: true
                });
            }

            // Grab the first waypoint for the 'start' location
            start = (waypts.shift()).location;
            // Grab the last waypoint for use as a 'finish' location
            finish = waypts.pop();
            if(finish === undefined){
                // Unless there was no finish location for some reason?
                finish = start;
            } else {
                finish = finish.location;
            }

            // Let's create the Google Maps request object
            var request = {
                origin: start,
                destination: finish,
                waypoints: waypts,
                travelMode: google.maps.TravelMode.DRIVING
            };

            // and save it in our requestArray
            requestArray.push({"route": route, "request": request});
        }

        processRequests();
    }

    function processRequests(){

        // Counter to track request submission and process one at a time;
        var i = 0;

        // Used to submit the request 'i'
        function submitRequest(){
            directionsService.route(requestArray[i].request, directionResults);
        }

        // Used as callback for the above request for current 'i'
        function directionResults(result, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                
                // Create a unique DirectionsRenderer 'i'
                renderArray[i] = new google.maps.DirectionsRenderer();
                renderArray[i].setMap(map);
                var image='http://fxbytes.com/bus_booking/dev/theme/images/s.png';
                // Some unique options from the colorArray so we can see the routes
                renderArray[i].setOptions({
                    preserveViewport: true,
                    suppressInfoWindows: true,
                    polylineOptions: {
                        strokeWeight: 4,
                        strokeOpacity: 0.8,
                        strokeColor: colourArray[i]
                    },
                    markerOptions:{
                        icon:{
                            path: google.maps.SymbolPath.CIRCLE,
                            scale: 3,
                            strokeColor: colourArray[i]
                        }
                    }
                });

                // Use this new renderer with the result
                renderArray[i].setDirections(result);
                // and start the next request
                nextRequest();
            }

        }

        function nextRequest(){
            // Increase the counter
            i++;
            // Make sure we are still waiting for a request
            if(i >= requestArray.length){
                // No more to do
                return;
            }
            // Submit another request
            submitRequest();
        }

        // This request is just to kick start the whole process
        submitRequest();
    }
  google.maps.event.addDomListener(window, 'load', init);
     function init() {

        // Some basic map setup (from the API docs) 
    
        var mapOptions = {
            position: new google.maps.LatLng(20.5937,78.9629),
            zoom: 6,
            mapTypeControl: true,
            streetViewControl: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
            
        map = new google.maps.Map(document.getElementById('map'), mapOptions);
       
        // Start the request making
         generateRequests();
        
        
    }

    var seatS=[];
  function selectSeats(busid,seatid){
    //$("#bus_"+busid+" #bus_details").html('Bus No'+busid);
    console.log($("#ticket_book_"+busid+" a#"+seatid).hasClass("health-booked"));
    var health_booked=$("#ticket_book_"+busid+" a#"+seatid).hasClass("health-booked");
    var girl_booked=$("#ticket_book_"+busid+" a#"+seatid).hasClass("girl-booked");
    var male_booked=$("#ticket_book_"+busid+" a#"+seatid).hasClass("male-booked");
    if(health_booked!=true && girl_booked!=true && male_booked!=true){
        
   
         var route_id=$("#bus_"+busid+" #route_id").val();
        //$("#bus_"+busid).modal();
         if(seatS.indexOf(seatid) == -1){
                html ='';
                html +='<tr id="'+seatid+'"><td width="60%"><select name="stype[]"><option value="1">Male</option><option value="2">Female</option><option value="3">Health</option></td><td width="20%">'+seatid+'<input type="hidden" name="seatid[]" value='+seatid+'></td><td width="20%" class="text-center" id="single_fare"></td><input type="hidden" name="fare[]" id="fare"></tr>';
                 /*html +='<tr id="'+seatid+'"><td width="60%"><div class="radio radio-primary pull-left"><input type="radio" id="stype'+seatid+'" name="stype'+seatid+'[]"  value="1"><label for="radio1">Male</label></div><div class="radio radio-primary  pull-left"><input type="radio" name="stype'+seatid+'[]" id="stype'+seatid+'"  value="2"><label for="radio2">Female</label></div><div class="radio radio-primary pull-left"><input type="radio" id="stype'+seatid+'" name="stype'+seatid+'[]"  value="3"><label for="radio3">Health</label></div></td><td width="20%">'+seatid+'<input type="hidden" name="seatid[]" value='+seatid+'></td><td width="20%" class="text-center" id="single_fare"></td><input type="hidden" name="fare[]" id="fare"></tr>';*/
                  $("#ticket_book_"+busid+" a#"+seatid).addClass('open');
            seatS.push(seatid);
            $("#bus_"+busid+" #selected_seats").append(html);
            getFare(busid,route_id);
                 }else{
                  $("#ticket_book_"+busid+" a#"+seatid).removeClass('open');
                  seatS.remove(seatid);
            $("#bus_"+busid+" #"+seatid).remove();
                  getFare(busid,route_id);
             }
         if(seatS.length>0){
          $("#book_modal_"+busid).show();
        }else{
          $("#book_modal_"+busid).hide();
        }
    }
  }
  function openBooking(busid){
    $("#bus_"+busid).modal();
  } 
  function getFare(busid,routeid){
            console.log(seatS.length);
            $("#bus_"+busid+" #single_fare").html('');
            $("#bus_"+busid+" #total_fare").html('');
            var dcity=$("#bus_"+busid+" .to_book_city").val();
            var scity=$("#bus_"+busid+" .from_book_city").val();
            var post_data = {"rid":routeid,"scity":scity,"dcity":dcity};
            $.ajax({
                method:'POST',
                url:"http://fxbytes.com/bus_booking/dev/index.php/booking/get_fare",
                data:post_data,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                success: function(res)
                {
                  var total=res*seatS.length;
                  $("#bus_"+busid+" #single_fare").html(res);
                  $("#bus_"+busid+" #fare").val(res);
                  $("#bus_"+busid+" #total_fare").html(total);
                }
            }); 

  }
  function bookSeats(busid){
   
     var form_data=$("#bus_"+busid+" #booking_form").serialize();
    
   //Removde value from array function
    $.ajax({
                method:'POST',
                url:"http://fxbytes.com/bus_booking/dev/index.php/booking/book_ticket",
                data:form_data,
                dataType: "json",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                success: function(res)
                {
                  var busid=res.bus_id;
                  var seats=res.seats;
                  var psnger=res.psngr_type
                  for (var i = 0; i < seats.length; i++) {
                    if(psnger[i]==2){
                      $("#ticket_book_"+busid+" a#"+seats[i]).removeClass("open").addClass("girl-booked");
                    }else if(psnger[i]==3){
                      $("#ticket_book_"+busid+" a#"+seats[i]).removeClass("open").addClass("health-booked");
                    }else{
                      $("#ticket_book_"+busid+" a#"+seats[i]).removeClass("open").addClass("male-booked");
                    }
                  }
                  $("#bus_"+busid).modal('toggle');
                  seatS.length=0;
                  $("#bus_"+busid+" #booking_form")[0].reset();
                  $("#bus_"+busid+" #selected_seats").html('');
                  $("#book_modal_"+busid).hide();
                }
            });   
  }
   function cancelTicket(busid){
      $("#bus_"+busid).modal('toggle');
      $("#bus_"+busid+" #booking_form")[0].reset();
      $("#bus_"+busid+" #selected_seats").html('');
      $("#book_modal_"+busid).hide();
      for (var i = 0; i < seatS.length; i++) {
          $("#ticket_book_"+busid+" a#"+seatS[i]).removeClass("open");
      }
      seatS.length=0;
  }
 
  Array.prototype.remove = function() {
      var what, a = arguments, L = a.length, ax;
      while (L && this.length) {
          what = a[--L];
          while ((ax = this.indexOf(what)) !== -1) {
              this.splice(ax, 1);
          }
      }
      return this;
  };
  //Removde value from array function 
    $(document).ready(function(){
       $('#contact').on('shown.bs.modal', function () {
        google.maps.event.trigger(map, "resize");
        map.setCenter(center);
        });
       $('.book_ticket').on("click", function () {
        seatS.length=0;
         $(".container").find('.bus-details').hide(400);
         $('#ticket_book_'+this.id).show();
      });
       $('#sort_by').on('change', function () {
          var lval = $(this).val(); // get selected value
          if (lval) { // require a URL
              window.location = '<?php echo current_url();?>?sort_by='+lval; // redirect
          }else{
              window.location = '<?php echo current_url();?>';
          }
          return false;
      });
    })

function openStaff(id){
    $("#modal-staff-"+id).modal("show");
} 
</script>
<?php $this->load->view('frontend/foot'); ?>
