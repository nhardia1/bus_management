<style type="text/css">
.gm-style-iw h1
{
  display: none !important;
}

.map_help_img
{
  height: 20px;
  width: 15px;
}

.pass_summary
{
  background-color: #000;
  border-radius: 3px;
  display: none;
  font-size: 10px !important;
  opacity: 0.75;
  padding: 10px;
  position: absolute;
  right: 2%;
  top: 30%;
  width: auto;
}

.pass_summary table
{
  background-color: #000 !important;
  font-size: 11px;
  letter-spacing: 2px;
  color: #D3D3D3;
}

.pass_summary table hr
{
  margin: 0px !important;
  border-style: dotted !important;
}
</style>

<div class="page">

    <div class="row ui-section">
        <div class="col-md-12 breadcum_container">
            <section class="panel panel-default breadcum_section">

              <h3 class="section-header"><?php echo $this->lang->line("bus").' '.$this->lang->line("tracking"); ?></h3>
            
              <a href="<?php echo base_url();?>index.php/home">
                <i class="imd imd-home"></i> <?php echo $this->lang->line("dashboard"); ?> 
              </a>
              
              
              <i class="imd imd-keyboard-arrow-right"></i>

              <a href="<?php echo base_url();?>index.php/tracking">
               <i class="imd imd-location-on"></i> <?php echo $this->lang->line("bus").' '.$this->lang->line("tracking"); ?>
              </a>

            </section>
        </div>

        <div class="col-md-12">
            <section class="panel panel-default">

        
                <!--div class="panel-body" id="output_msg">
                    <div class="alert alert-info"></div>                            
                </div-->
                    

                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12" >

                            <form role="form" name="bus_location" id="bus_location">
                               
                               <div class="row">
                                    
                                    <div class="col-sm-3 col-md-offset-4 label_dropdown">
                                      <label for="bus_loc_bus_name"><?php echo $this->lang->line("bus_name"); ?></label>
                                        <span class="ui-select">
                                            <select id="bus_loc_bus_name" name="bus_loc_bus_name" onchange="enable_disable_btn();">
                                                
                                                <?php $selected_bus_id=$bus_id;
                                                
                                                
                                                if($selected_bus_ids==''){
                                                  echo "<option value=''>".$this->lang->line("select_bus")."</option>";
                                                }
                                                foreach($bus_list as $arr)    
                                                {
                                                    $id = $arr->id;

                                                    $name = $arr->name;

                                                    $number = $arr->bus_number;
                                                ?>

                                                    <option value="<?php echo $id;?>"

                                                      <?php if($selected_bus_id==$id){
                                                        echo "selected='selected'" ;
                                                      } ?> ><?php echo $name."&nbsp;($number)";?></option>

                                                <?php
                                                }
                                                ?>    

                                            </select>
                                        </span>
                                    </div>

                                    <!--div class="col-sm-4 label_dropdown">
                                      <label for="bus_loc_bus_name">Trip Type</label>
                                        <span class="ui-select">
                                            <select id="bus_loc_trip_type" name="bus_loc_trip_type">
                                                                            
                                                <option value="1">Single Trip</option>
                                                <option value="2">Round Trip</option>                                 

                                            </select>
                                        </span>
                                    </div-->

                                

                                    <div class="col-md-4">
                                        <div class="ui-input-group">
                                             <input type="button" onclick="search_bus_location();" id="btn_bus_search" class="btn btn-primary md-button md-default-theme" value="Locate" ><div class="divider"></div>
                                        </div>
                                    </div>

                                </div>

                               
                                <div class="row">  
                                    
                                    <div class="col-md-12" id="map_help_txt">
                                      
                                      <label class="map_s">
                                        <img class="map_help_img" src="<?php echo base_url();?>theme/images/s.png">
                                      </label>&nbsp;<?php echo $this->lang->line("source"); ?>&nbsp;&nbsp;&nbsp;

                                      <label class="map_d">
                                        <img class="map_help_img" src="<?php echo base_url();?>theme/images/d.png">
                                      </label>&nbsp;<?php echo $this->lang->line("destination"); ?>&nbsp;&nbsp;&nbsp;
                                      
                                      <label class="map_stp">
                                        <img class="map_help_img" src="<?php echo base_url();?>theme/images/stop.png">
                                      </label>&nbsp;<?php echo $this->lang->line("stoppage"); ?>&nbsp;&nbsp;&nbsp;
                                      
                                      <label class="map_c">
                                        <img class="map_help_img" src="<?php echo base_url();?>theme/images/bus.png">
                                      </label>&nbsp;<?php echo $this->lang->line("current_location_of_bus"); ?>&nbsp;&nbsp;&nbsp; 

                                    </div>                               
                                    
                                    <div id="map" style="width: 100%; height: 500px;"></div>
                                </div>
                               
                            </form>

                        </div>                        
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<div class="pass_summary"></div>


<link rel="stylesheet" href="<?php echo base_url();?>theme/styles/jquery-ui.css">
<script type="text/javascript" src="//www.google.fr/jsapi"></script>
<script type="text/javascript">
    google.load("maps", "3.4", {
      other_params: "sensor=false&language=fr"
    });
</script>
<script src="<?php echo base_url();?>theme/scripts/jquery.googlemap.js"></script>


<script>
$(function() 
{
    $("#btn_bus_search").attr("disabled",true);    
    
    $("#btn_bus_search").addClass("btn_disabled");   

    $("#map_help_txt").hide(); 
});

function enable_disable_btn()
{
    if($("#bus_loc_bus_name").val() == "" || $("#bus_loc_bus_name").val()<=0 || $("#bus_loc_date").val() == "")
    {      
        $("#btn_bus_search").attr("disabled",true);
      
        $("#btn_bus_search").addClass("btn_disabled");
    }
    else
    {
      $("#btn_bus_search").removeAttr("disabled");
      
      $("#btn_bus_search").removeClass("btn_disabled");
    }   
}



function search_bus_location()
{
    if($("#bus_loc_bus_name").val() == "" || $("#bus_loc_bus_name").val()<=0)
    {
        alert("Please select bus name.");
        
        return;

    } 


    //var trip_num = $("#bus_loc_trip_type").val();

    var post_data = $("#bus_location").serialize();  

    var call_url = BASE_PATH_URL+"index.php/tracking/tracking_bus";
    
    $.ajax({method: 'POST',
          data:post_data,
          headers: {'Content-Type': 'application/x-www-form-urlencoded'}, 
          url: call_url,
          async : false,
          success: function(res)
          {              
              var obj = $.parseJSON(res);
              
              if(obj.msg.indexOf("success")>=0)
              {
                var trip_num = obj.location.trip_num;
              }
              else
              {
                var trip_num = 0;

                $("#map_help_txt").hide();
              }  
          
              if(obj.msg.indexOf("success")>=0 && trip_num>0)
              {               
                  $("#map").googleMap({
                    zoom: 10, // Initial zoom level (optional)
                    coords: [28.7483894, 77.2060241], // Map center (optional)
                    type: "ROADMAP" // Map type (optional)
                  });


                  $("#map_help_txt").show();

                  if(obj.summary!="")
                  {
                    $(".pass_summary").show();

                    $(".pass_summary").html(obj.summary);
                  }  

                  //Current Location-------------------------------------------
                  if(obj.location.lattitude!="" && obj.location.lattitude>0 && obj.location.longitude != "" && obj.location.longitude > 0)
                  {

                    $.ajax({ url:'http://maps.googleapis.com/maps/api/geocode/json?latlng='+ obj.location.lattitude +','+ obj.location.longitude+'&sensor=true',
                         success: function(data)
                         {
                            var result = data.results[0];                                                 

                            $("#map").addMarker({
                                  coords: [obj.location.lattitude, obj.location.longitude],
                                  title: result.formatted_address,
                                  text:  result.formatted_address,
                                  icon:BASE_PATH_URL+'theme/images/bus.png'
                             });
                          }
                      });                              
                  }
                
                  
                  /*
                  //Drawing complete location from source
                  var l = obj.location.length;
                  if(l>0)
                  {
                      var i = 0;

                      for(;i<l-1;i++)
                      {
                          
                          if(obj.location[i].lattitude!="" && obj.location[i].lattitude>0 && obj.location[i].longitude != "" && obj.location[i].longitude > 0)
                          {
                              $("#map").addMarker({
                                      coords: [obj.location[i].lattitude, obj.location[i].longitude],
                                      //title: result.formatted_address,
                                      //text:  result.formatted_address,
                                      icon:BASE_PATH_URL+'theme/images/rsz_bus.png'
                              });   
                          }

                          
                          var last_lat = obj.location[l-1].lattitude;
                          var last_lon = obj.location[l-1].longitude;

                          if(last_lat!="" && last_lat>0 && last_lon!="" && last_lon>0)
                          {
                                $.ajax({ url:'http://maps.googleapis.com/maps/api/geocode/json?latlng='+ last_lat +','+ last_lon+'&sensor=true',
                               success: function(data)
                               {
                                  var result = data.results[0];                                                 

                                  $("#map").addMarker({
                                        coords: [last_lat, last_lon],
                                        title: result.formatted_address,
                                        text:  result.formatted_address,
                                        icon:BASE_PATH_URL+'theme/images/bus.png'
                                   });
                                }
                              });
                          }

                     }

                  } */ 


                  if(trip_num % 2 == 1)
                  {
                      var s_img = BASE_PATH_URL+'theme/images/s.png';
                      var d_img = BASE_PATH_URL+'theme/images/d.png';;
                  }
                  else
                  {
                      var s_img = BASE_PATH_URL+'theme/images/d.png';
                      var d_img = BASE_PATH_URL+'theme/images/s.png';
                  }


                  //Source-------------------------------------------
                  if(obj.route.slat!="" && obj.route.slon!="" && obj.route.slon>0)
                  {
                     $("#map").addMarker({
                          coords: [obj.route.slat, obj.route.slon],
                          title: obj.route.sourcename,
                          text:  obj.route.sourcename,
                          icon: s_img
                     });
                  }

                  
                  //Destination-------------------------------------------
                  if(obj.route.dlat!="" && obj.route.dlon!="" && obj.route.dlon>0)
                  {
                     $("#map").addMarker({
                          coords: [obj.route.dlat, obj.route.dlon],
                          title: obj.route.destination,
                          text:  obj.route.destination,
                          icon: d_img
                     });
                  }

                  
                  //Stoppage-------------------------------------------
                  var i;
                  var l = obj.stoppage.length;
                  for (i = 0; i < l; i++) 
                  {
                      if(obj.stoppage[i].lat!="" && obj.stoppage[i].lat>0 && obj.stoppage[i].lon!="" && obj.stoppage[i].lon>0)
                      {
                          $("#map").addMarker({
                              coords: [obj.stoppage[i].lat, obj.stoppage[i].lon],
                              title: obj.stoppage[i].stp,
                              text:  obj.stoppage[i].stp,
                              icon: BASE_PATH_URL+'theme/images/stop.png'
                          });
                      }  
                  }



              }
              else
              {
                 
                 $("#map").html("");
                
                 alert(obj.msg);
              }


          }

    });

}
</script>