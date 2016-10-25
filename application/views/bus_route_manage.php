<?php
$max_trips = array(1,2,3,4);
?>

<style type="text/css"> label { padding-left: 4px; } </style>

<div class="page" >

    <div class="row ui-section">
        <div class="col-md-12 breadcum_container">
            <section class="panel panel-default breadcum_section">


              <h3 class="section-header">
                <?php if(isset($bus_route_route_id) && !empty($bus_route_route_id)) 
                { 
                    echo $this->lang->line("assign").' '.$this->lang->line("bus").' '.$this->lang->line("route");//"Assign Bus-Route";
                }
                 else 
                { 
                    echo $this->lang->line("assign").' '.$this->lang->line("bus").' '.$this->lang->line("route"); //"Assign Bus-Route";
                }?>                
              </h3>
            
              <a href="<?php echo base_url();?>index.php/home">
                <i class="imd imd-home"></i> <?php echo $this->lang->line("dashboard"); ?>
              </a>
              
              <i class="imd imd-keyboard-arrow-right"></i>
              
              <a href="<?php echo base_url();?>index.php/route/bus_route_list">
                <i class="imd imd-directions"></i> <?php echo $this->lang->line("bus").' '.$this->lang->line("route"); ?>
              </a>

              <i class="imd imd-keyboard-arrow-right"></i>
              
              <?php if(isset($bus_route_bus_id) && !empty($bus_route_bus_id) && isset($bus_route_route_id) && !empty($bus_route_route_id)) 
              { 
              ?>
                    <a href="<?php echo base_url();?>index.php/route/manage/<?php echo $bus_route_bus_id;?>/<?php echo $bus_route_route_id;?>">
                       <?php echo $this->lang->line("edit") ; ?>
                    </a>
              <?php  
              }
              else
              {
              ?>
                    <a href="<?php echo base_url();?>index.php/route/manage">
                       <?php echo $this->lang->line("add") ; ?>
                    </a>
              <?php  
              }
              ?> 
              

            </section>
        </div>

        <div class="col-md-12">
            <section class="panel panel-default">

        
                <div class="panel-body" id="output_msg">
                    <div class="alert alert-info"></div>                            
                </div>
                    

                <div class="panel-body padding-lg">
                    <div class="row">
                        <div class="col-md-12" >

                            <form role="form" name="add_bus_route_form" id="add_bus_route_form">
                               
                               <div class="row">
                                    
                                    <div class="col-sm-3 label_dropdown">
                                    <label for="bus_route_bus_id"><?php echo $this->lang->line("bus") ; ?></label><br/>
                                        <span class="ui-select">
                                            <select id="bus_route_bus_id" name="bus_route_bus_id" onchange="get_route_stoppage_details();">
                                                <option value=""><?php echo $this->lang->line("select_bus"); ?></option>
                                                <?php
                                                foreach($bus_list as $arr)    
                                                {
                                                    $id = $arr->id;

                                                    $name = $arr->name;

                                                    $number = $arr->bus_number;

                                                    $sel_bus = "";
                                                    if($bus_route_bus_id == $id)
                                                    {
                                                        $sel_bus = "selected='selected'";
                                                    }
                                                ?>

                                                    <option value="<?php echo $id;?>" <?php echo $sel_bus;?>><?php echo $name."&nbsp;($number)";?></option>

                                                <?php
                                                }
                                                ?>    

                                            </select>
                                        </span>
                                    </div>

                                   
                                    <div class="col-sm-3 label_dropdown">
                                        <label for="bus_route_route_id"><?php echo $this->lang->line("route") ; ?></label><br/>
                                        <span class="ui-select">
                                            <select id="bus_route_route_id" name="bus_route_route_id" onchange="get_route_stoppage_details();">
                                                <option value=""><?php echo $this->lang->line("select_route"); ?></option>
                                                <?php
                                                foreach($route_list as $arr)    
                                                {
                                                    $id = $arr->id;

                                                    $name = $arr->name;

                                                    $sel_route = "";
                                                    if($bus_route_route_id == $id)
                                                    {
                                                        $sel_route = "selected='selected'";
                                                    }
                                                ?>

                                                    <option value="<?php echo $id;?>" <?php echo $sel_route;?>><?php echo $name;?></option>

                                                <?php
                                                }
                                                ?>                                                 
                                            </select>
                                        </span>
                                    </div>


                                    <div class="col-sm-2" style="margin-top:-11px;">     
                                    <div class="ui-input-group">                                   
                                        <input type="text" class="form-control col-sm-3" id="bus_route_from_date" name="bus_route_from_date" onchange="enable_disable_btn();" required>
                                        <label for="bus_route_from_date"><?php echo $this->lang->line("from_date"); ?></label> 
                                    </div></div>

                                    
                                    
                                    <div class="col-sm-2"  style="margin-top:-11px;">
                                        <div class="ui-input-group">                                   
                                        <input type="text" class="form-control col-sm-3" id="bus_route_to_date" name="bus_route_to_date" onchange="enable_disable_btn();" required> 
                                        <label for="bus_route_to_date"><?php echo $this->lang->line("to_date"); ?></label>
                                    </div></div>

                                    <!--input type="hidden" name="bus_route_max_trip" id="bus_route_max_trip" value="2" /-->
                                    <div class="col-sm-2 label_dropdown">
                                        <label for="bus_route_route_id"><?php echo $this->lang->line("maximum").' '.$this->lang->line("trip") ; ?></label><br/>
                                        <span class="ui-select">
                                            <select id="bus_route_max_trip" name="bus_route_max_trip" onchange="get_bus_timing_details();">
                                                <!--option value="">Maximum Trip</option-->                                
                                                <?php
                                                foreach($max_trips as $trip)    
                                                {
                                                    $id = $trip;

                                                    $name = $trip;

                                                    $sel_trip = "";
                                                    if($bus_route_max_trip == $id)
                                                    {
                                                        $sel_trip = "selected='selected'";
                                                    }
                                                    elseif($id == 1)
                                                    {
                                                        $sel_trip = "selected='selected'";
                                                    }
                                                ?>

                                                    <option value="<?php echo $id;?>" <?php echo $sel_trip;?>><?php echo $name;?></option>

                                                <?php
                                                }
                                                ?>                                                 
                                            </select>
                                        </span>
                                    </div>

                                   
                                </div>


                                
                                <div class="divider divider-dashed divider-lg pull-in"></div>

                                <div class="row" style="padding-bottom:1%;" id="journey_date_content">
                                    <div class="col-sm-2 col-md-offset-5">     
                                    <div class="ui-input-group">                                   
                                        <input type="text" class="form-control col-sm-3" id="bus_route_journey_date" name="bus_route_journey_date" onchange="get_bus_timing_details();" required>
                                        <label for="bus_route_journey_date"><?php echo $this->lang->line("select_journey_date"); ?></label> 
                                    </div></div>
                                </div>

                                
                                <div align="center" id="bus_time_matrix"></div>                        
                               
                                
                                <div class="row col-md-12" align="right" style="padding-top:1%;">                                    
                                    <input type="button" onclick="save_bus_timing();" id="btn_save" class="btn btn-primary md-button md-default-theme" value="Save" >

                                    <div class="divider"></div>
                                </div>
                            </form>

                        </div>                        
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>


<link rel="stylesheet" href="<?php echo base_url();?>theme/styles/jquery-ui.css">

<script type="text/javascript">
$(function()
{
    
    $("#btn_save").attr("disabled",true);
    
    $("#btn_save").addClass("btn_disabled");


    $( "#bus_route_from_date" ).datepicker({
        dateFormat: 'dd-mm-yy',
        changeMonth: SHOW_MONTH,
        changeYear: SHOW_YEAR,
        numberOfMonths: 1,
        onClose: function( selectedDate ) 
        {
            $( "#bus_route_to_date" ).datepicker( "option", "minDate", selectedDate );
        }
    });

    $( "#bus_route_to_date" ).datepicker({
        dateFormat: 'dd-mm-yy',
        changeMonth: SHOW_MONTH,
        changeYear: SHOW_YEAR,
        numberOfMonths: 1,
        onClose: function( selectedDate ) 
        {
            $( "#bus_route_from_date" ).datepicker( "option", "maxDate", selectedDate );
        }
    });


    get_route_stoppage_details();

    var jdate = "<?php echo $jdate;?>";

    if(jdate != "" && jdate != undefined)
    {
        $("#bus_route_journey_date").val(jdate);

        get_bus_timing_details();
    }

});  


function enable_disable_btn()
{
    if($("#bus_route_bus_id").val() == "" || $("#bus_route_bus_id").val()<=0 || $("#bus_route_route_id").val() == "" || $("#bus_route_route_id").val()<=0 || $("#bus_route_from_date").val() == "" || $("#bus_route_to_date").val() == "")
    {      
        $("#btn_save").attr("disabled",true);
      
        $("#btn_save").addClass("btn_disabled");
    }
    else
    {
      $("#btn_save").removeAttr("disabled");
      
      $("#btn_save").removeClass("btn_disabled");
    }  


    if($("#bus_route_from_date").val() == "" || $("#bus_route_to_date").val() == "")
    {
        $("#bus_route_journey_date").datepicker("destroy");
        $("#journey_date_content").val("");
        $("#journey_date_content").hide();
    }
    else
    {
        $("#journey_date_content").show();
        $("#bus_time_matrix").html("");

        $("#bus_route_journey_date").datepicker("destroy");
        $( "#bus_route_journey_date" ).datepicker({
        dateFormat: 'dd-mm-yy',
        changeMonth: SHOW_MONTH,
        changeYear: SHOW_YEAR,
        numberOfMonths: 1,
        minDate: $("#bus_route_from_date").val(),
        maxDate: $("#bus_route_to_date").val()       
        });

        get_bus_timing_details();
    }

}


function get_bus_timing_details()
{
    var call_url = BASE_PATH_URL+"index.php/route/get_bus_timing_details";
    
    if($("#bus_route_journey_date").val() == "")
    { 
        $("#bus_time_matrix").html("");

        return;
    }
    
    var post_data = $("#add_bus_route_form").serialize();            

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
                $("#bus_time_matrix").html(obj.matrix); 

                $( ".journey_date" ).datepicker({
                dateFormat: 'dd-mm-yy',
                changeMonth: SHOW_MONTH,
                changeYear: SHOW_YEAR,
                numberOfMonths: 1,
                minDate: $("#bus_route_from_date").val(),
                maxDate: $("#bus_route_to_date").val()       
                }); 


                $("#bus_time_matrix").find("ul.nav-tabs").find("li").bind("click",function(i,v)
                {
                    $("#bus_time_matrix").find("ul.nav-tabs").find("li").removeClass("active");
                    $(this).addClass("active");

                    $("#bus_time_matrix").find("div.tab-content").find(".tab-pane").removeClass("active");
                    $("#bus_time_matrix").find("div.tab-content").find("#content"+$(this).attr("id")).addClass("active");
                });
            }
            else
            {                
                alert_msg_box(res.msg,'');
            } 
           
        }

    });
}


function get_route_stoppage_details()
{
    var call_url = BASE_PATH_URL+"index.php/route/bus_route_details";
    
    if($("#bus_route_bus_id").val() == "" || $("#bus_route_bus_id").val()<=0 || $("#bus_route_route_id").val() == "" || $("#bus_route_route_id").val()<=0)
    { 
        $("#bus_route_from_date").val("");
        $("#bus_route_to_date").val("");
        $("#bus_route_max_trip").val("");

        enable_disable_btn();        

        return;
    }
    
   
    var post_data = {"rid":$("#bus_route_route_id").val(), "bid":$("#bus_route_bus_id").val()};          

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
                    $("#bus_route_from_date").val(obj.valid_from);
                    $("#bus_route_to_date").val(obj.valid_to);
                    $("#bus_route_max_trip").val(obj.max_trip);
                }
                else
                {
                    alert_msg_box(res.msg,'');
                }   

                enable_disable_btn();
            }

    });
}

</script>