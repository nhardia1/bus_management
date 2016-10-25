<link rel="stylesheet" href="<?php echo base_url();?>theme/styles/jquery-ui.css" type="text/css" media="screen"/>

<div class="page page-table">
    <div class="row ui-section">

        
        <div class="col-md-12 breadcum_container">
            <section class="panel panel-default breadcum_section">

              <h3 class="section-header"><?php echo $this->lang->line("assign").' '.$this->lang->line("bus").' '.$this->lang->line("&").' '.$this->lang->line("set").' '.$this->lang->line("time"); ?></h3>
            
              <a href="<?php echo base_url();?>index.php/home">
                <i class="imd imd-home"></i> <?php echo $this->lang->line("dashboard") ?> 
              </a>
              
              <i class="imd imd-keyboard-arrow-right"></i>
              
              <a href="<?php echo base_url();?>index.php/route/bus_route_list">
                <i class="imd imd-directions"></i> <?php echo $this->lang->line("bus").'-'.$this->lang->line("route") ?> 
              </a>

            </section>
        </div>



        <div class="col-md-12">
            <section class="panel panel-default table-dynamic">
          
          <div class="add_btn">
      <a type=button style="float: right;" data-toggle="tooltip" title="<?php echo $this->lang->line("add_bus_route"); ?>" onClick="location='<?php echo base_url()?>index.php/route/manage'" class="md-fab blue_bg md-button md-default-theme"  aria-label=""><span class="imd imd-add"></span></a>
</div>

    <?php echo $this->table->generate(); ?>
    </section>
  </div>
</div>
</div>


<div id='myModal001' class="modal fade col-md-12" role="dialog" >
      <div class="modal-dialog-big">
              
                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo $this->lang->line("view_bus_trip_time"); ?></h4>
                  </div>
                  <div class="modal-body" id='bus_route_details'>

                      <div class="row" id="journey_date_content">
                         
                        <form role="form" name="add_bus_route_form" id="add_bus_route_form">
                          <div class="col-sm-3  col-md-offset-5" style="margin-top:-11px;">     
                            <div class="ui-input-group">                                                              
                                <input type="text" class="form-control col-sm-3" id="bus_route_journey_date" name="bus_route_journey_date" onchange="get_bus_route_timing_details();" required>
                                <label for="bus_route_journey_date"><?php echo $this->lang->line("select_journey_date"); ?></label>
                            </div>
                          </div>
                                <input type="hidden" class="form-control col-sm-3" id="bus_route_from_date" name="bus_route_from_date" >
                                <input type="hidden" class="form-control col-sm-3" id="bus_route_to_date" name="bus_route_to_date" >
                                <input type="hidden" class="form-control col-sm-3" id="bus_route_bus_id" name="bus_route_bus_id" >

                                <input type="hidden" class="form-control col-sm-3" id="bus_route_route_id" name="bus_route_route_id" >
                                <input type="hidden" class="form-control col-sm-3" id="bus_route_max_trip" name="bus_route_max_trip" >
                                <br>
                               
                                <div align="center" id="bus_time_matrix" class="bus_time_display"></div>
                                
                           
                          
                        </form>

                      </div>
                   
                  </div>
                  <div class="modal-footer">
                    
                    <button type="button" class="btn btn-default close" data-dismiss="modal"><?php echo $this->lang->line("close"); ?></button>
                  </div>
                </div>
                
              </div>
    </div>

    <script>
    $( "#bus_route_journey_date" ).datepicker({
        dateFormat: 'dd-mm-yy',
        changeMonth: SHOW_MONTH,
        changeYear: SHOW_YEAR,
        numberOfMonths: 1,
        minDate: $("#bus_route_from_date").val(),
        maxDate: $("#bus_route_to_date").val()       
        });

$('.close').on('click', function () {
        window.location.reload(true);
    })

    function get_bus_route_timing_details()
{
    var call_url = BASE_PATH_URL+"index.php/route/get_bus_route_timing_details";
    
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


    </script>