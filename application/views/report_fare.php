<div class="page page-table">
    <div class="row ui-section">

        <div class="col-md-12 breadcum_container">
          <section class="panel panel-default breadcum_section">

            <h3 class="section-header"><?php echo $this->lang->line("fare").' '.$this->lang->line("report"); ?></h3>
          
            <a href="<?php echo base_url(); ?>index.php/home">
              <i class="imd imd-home"></i> <?php echo $this->lang->line("dashboard"); ?>
            </a>
            
            <i class="imd imd-keyboard-arrow-right"></i>
            
            <a href="<?php echo base_url(); ?>index.php/reports">
              <i class="imd imd-list"></i> <?php echo $this->lang->line("fare").' '.$this->lang->line("report"); ?>
            </a>

          </section>
        </div>


        <div class="col-md-12">
        <section class="panel panel-default table-dynamic">
          <div class="table-filters">
              <div class="col-md-12" >
              <div class="row">
                   <form name="fare_report_form" id="fare_report_form" method="post">                    
                <div class="col-sm-2 col-md-offset-3 label_dropdown">
                <label for="bus_id">Bus</label><br/>
                    <span class="ui-select">
                        <select id="bus_id" name="bus_id">
                            <option value=""><?php echo $this->lang->line("select_bus"); ?></option>
                            <?php
                            foreach($bus_list as $arr)    
                            {
                                $id = $arr->id;

                                $name = $arr->name;

                                $number = $arr->bus_number;

                            ?>

                                <option value="<?php echo $id;?>"><?php echo $name."&nbsp;($number)";?></option>

                            <?php
                            }
                            ?>    

                        </select>
                    </span>
                </div>

                              

                <div class="col-sm-2" style="margin-top:-11px;">     
                  <div class="ui-input-group">                                   
                      <input type="text" class="form-control col-sm-3" id="for_date" name="for_date" required>
                      <label for="for_date"><?php echo $this->lang->line("date"); ?></label> 
                  </div>
                </div>

                
                <div class="col-md-2">
                    <div class="ui-input-group">
                         <input type="button" onclick="get_fare_report();" id="search" class="btn btn-primary md-button md-default-theme" value="Search" ><div class="divider"></div>
                    </div>
                </div>
                </form>
              </div>
              <div class="divider divider-dashed divider-lg pull-in"></div>
              </div>

          </div>
          
          <table id="example" class="display" cellspacing="0" width="100%">
            <thead>
                <tr>                
                    <!--th >#</th-->
                    <th ><?php echo $this->lang->line("bus"); ?></th>
                    <th ><?php echo $this->lang->line("route"); ?></th>
                    <th ><?php echo $this->lang->line("date"); ?></th>
                    <th ><?php echo $this->lang->line("total").' '.$this->lang->line("passenger"); ?></th>
                    <th ><?php echo $this->lang->line("total").' '.$this->lang->line("amount"); ?></th>

                </tr>
            </thead>
     
            <tfoot>
                <tr>
                    <!--th >#</th-->
                    <th ><?php echo $this->lang->line("bus"); ?></th>
                    <th ><?php echo $this->lang->line("route"); ?></th>
                    <th ><?php echo $this->lang->line("date"); ?></th>
                    <th ><?php echo $this->lang->line("total").' '.$this->lang->line("passenger"); ?></th>
                    <th ><?php echo $this->lang->line("total").' '.$this->lang->line("amount"); ?></th>
                </tr>
            </tfoot>
            
            <tbody>

            </tbody>

        </table>
        </section>
      </div>
        
    </div>
</div>
   


   <div id='myModal001' class="modal fade" role="dialog" style='display:none;'>
      <div class="modal-dialog-big">
              
                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                  </div>
                  <div class="modal-body" id='fare_details'>
                   
                   
                  </div>
                  <div class="modal-footer">
                    
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line("close"); ?></button>
                  </div>
                </div>
                
              </div>
    </div>

<link rel="stylesheet" href="<?php echo base_url();?>theme/styles/jquery-ui.css">

<script type="text/javascript">

$(document).ready(function() 
{
   $('#example').on( 'init.dt', function () 
    {
        $("#example_wrapper").find("#example_filter").find("input[type='text']").attr("id","csbox");
        $("#example_wrapper").find("#example_filter").find("input[type='text']").attr("name","हिन्दी के लिए पहले अंग्रेजी में शब्द type करे और उसके बाद SPACEBAR key दबाएँ.");  

        $("#csbox").mouseover(function(e)
        {
            var x = e.pageX - 100;
            var y = e.pageY - 80;

            $(".edit_tooltip").show();
            $(".edit_tooltip").css({left:x+"px", top:y+"px"});
            $(".edit_tooltip").find("p").html($(this).attr("name"));
        });
        $("#csbox").mouseout(function(e)
        {                         
            $(".edit_tooltip").hide();                          
        });

        $(".moreless").hide();
    
    }).dataTable();
   

   $( "#for_date" ).datepicker({
        dateFormat: 'dd-mm-yy',
        changeMonth: SHOW_MONTH,
        changeYear: SHOW_YEAR,
        numberOfMonths: 1
    });


  get_fare_report();


} );
</script>