<style type="text/css">
    label
    {
        padding-left: 4px;
    }

</style>
<div class="page">

    <div class="row ui-section">
        <div class="col-md-12 breadcum_container">
            <section class="panel panel-default breadcum_section">

              <h3 class="section-header">Bus Location</h3>
            
              <a href="<?php echo base_url();?>index.php/home">
                <i class="imd imd-home"></i> Dashboard 
              </a>
              
              
              <i class="imd imd-keyboard-arrow-right"></i>

              <a href="<?php echo base_url();?>index.php/locations">
               Location
              </a>

            </section>
        </div>

        <div class="col-md-12">
            <section class="panel panel-default">

        
                <div class="panel-body" id="output_msg">
                    <div class="alert alert-info"></div>                            
                </div>
                    

                <div class="panel-body ">
                    <div class="row">
                        <div class="col-md-12" >

                            <form role="form" name="bus_location" id="bus_location">
                               
                               <div class="row" style="margin-bottom:3px;">
                                    
                                    <div class="col-sm-4 label_dropdown">
                                      <label for="bus_loc_bus_name">Bus Name</label>
                                        <span class="ui-select">
                                            <select id="bus_loc_bus_name" name="bus_loc_bus_name" onchange="enable_disable_btn();">
                                                <option value="">Select Bus Name</option>
                                                <?php
                                                foreach($bus_list as $arr)    
                                                {
                                                    $id = $arr->id;

                                                    $name = $arr->name;
                                                ?>

                                                    <option value="<?php echo $id;?>"><?php echo $name;?></option>

                                                <?php
                                                }
                                                ?>    

                                            </select>
                                        </span>
                                    </div>

                                    <!--div class="col-sm-4 label_dropdown">
                                        <label for="bus_loc_route_name">Route Name</label>
                                        <span class="ui-select">
                                            <select id="bus_loc_route_name" name="bus_loc_route_name" onchange="enable_disable_btn();">
                                                <option value="">Select Route Name</option>
                                                <?php
                                                foreach($route_list as $arr)    
                                                {
                                                    $id = $arr->id;

                                                    $name = $arr->name;
                                                ?>

                                                    <option value="<?php echo $id;?>"><?php echo $name;?></option>

                                                <?php
                                                }
                                                ?>                                                 
                                            </select>
                                        </span>
                                    </div-->

                                    <div class="col-md-4">
                                        <div class="ui-input-group">
                                            <input type="text" class="form-control" id="bus_loc_date" name="bus_loc_date" style="margin-top:-11px;" onchange="enable_disable_btn();" required> 
                                            <label for="route_name">Date</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="ui-input-group">
                                             <input type="button" onclick="search_bus_location();" id="btn_bus_search" class="btn btn-primary md-button md-default-theme" value="Locate" ><div class="divider"></div>
                                        </div>
                                    </div>

                                </div>

                                
                                <div class="divider divider-dashed divider-lg pull-in"></div>
                                
                                
                                <div class="row">                                
                                    <div id="bus_location_map"></div>
                                </div>
                                
                                <!--br/>
                                <div class="row col-md-12" align="right">
                                    <input type="button" onclick="search_bus_location();" id="btn_bus_search" class="btn btn-primary md-button md-default-theme" value="Locate" ><div class="divider"></div>
                                </div-->
                            </form>

                        </div>                        
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?php echo base_url();?>theme/styles/jquery-ui.css">

<script src="<?php echo base_url();?>theme/scripts/jquery.googlemap.js"></script>

<script>
$(function() 
{
    $( "#bus_loc_date" ).datepicker({
        dateFormat: 'dd-mm-yy',
        changeMonth: SHOW_MONTH,
        changeYear: SHOW_YEAR,
        numberOfMonths: 1,
        maxDate: new Date()
    });


    $("#btn_bus_search").attr("disabled",true);    
    $("#btn_bus_search").addClass("btn_disabled");

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
    if($("#bus_loc_bus_name").val() == "" || $("#bus_loc_bus_name").val()<=0 || $("#bus_loc_date").val() == "")
    {
        alert("Please select all criteria.");
        return;

    }  

    var post_data = $("#bus_location").serialize();        

    var call_url = BASE_PATH_URL+"index.php/locations/locate_bus";
    
    $.ajax({method: 'POST',
          data:post_data,
          headers: {'Content-Type': 'application/x-www-form-urlencoded'}, 
          url: call_url,
          async : false,
          success: function(res)
          {               
              var obj = $.parseJSON(res);

              if(obj.msg == "success")
              {
                $("#bus_location_map").googleMap();

                $("#bus_location_map").html(obj.matrix);                    
              }
              else
              {
                  alert_msg_box(res.msg,'');
              }   
          }

      });

}

</script>