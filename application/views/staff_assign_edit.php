<script type="text/javascript" src="<?php echo base_url();?>theme/scripts/multidatepicker/jquery-ui.multidatespicker.js"></script>

<script type="text/javascript">
$(function() 
{
        
  $('#assign_date').multiDatesPicker({numberOfMonths: [1,3],
    addDates: [<?php echo $fordates;?>],
    dateFormat: "dd-mm-yy",
    onSelect:function(seldate,b)
    {
        chk_staff_assigned(seldate);
    }
    });
  $("#ui-datepicker-div").hide();

});

</script>

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>theme/scripts/multidatepicker/css/pepper-ginder-custom.css">


<div class="page page-table">
  <div class="row ui-section">
    
    <div class="col-md-12 breadcum_container">
            <section class="panel panel-default breadcum_section">

              <h3 class="section-header"><?php echo $this->lang->line("staff")." ".$this->lang->line("assign");?></h3>
            
              <a href="<?php echo base_url();?>index.php/home">
                <i class="imd imd-home"></i> <?php echo $this->lang->line("dashboard");?> 
              </a>
              
              <i class="imd imd-keyboard-arrow-right"></i>
              
              <a href="<?php echo base_url();?>index.php/staff_assign">
                <i class="imd imd-account-child"></i> <?php echo $this->lang->line("staff")." ".$this->lang->line("assign");?>
              </a>

              <i class="imd imd-keyboard-arrow-right"></i>
              
              <a href="<?php echo base_url();?>index.php/staff_assign/edit/bid/<?php echo $bus_id; ?>/drivid/<?php echo $driver_id; ?>/conducid/<?php echo $conductor_id; ?>/helpid/<?php echo $helper_id;?>/otherid/<?php echo $other_id; ?>">
               <?php echo $this->lang->line("edit");?>
              </a>

            </section>
        </div>
    

    <div class="col-md-12">
      <section class="panel panel-default">
       

            <div class="panel-body" id="output_msg">
              <div class="alert alert-info"></div>
            </div>
            
            <div class="panel-body">                  
            <div class="row">
            
            <div class="col-md-12">
              <form name="staff_assign_form" id="staff_assign_form" method='post' data-parsley-validate>
               
                <div class="col-md-3 label_dropdown">
                  
                  <label class="control-label" for="bus_name"><?php echo $this->lang->line("bus_name");?></label>
                  
                  <span class="ui-select">
                  
                  <select id="bus_name" name="bus_name" required>
                    <?php
                      echo "<option value=".$bus_id.">".$name."</option>";
                    ?>
                  </select>
                  </span>

                </div>

                <div class="col-md-2 label_dropdown" >

                    <label class="control-label" for="driver_name">
                      <?php echo $this->lang->line("driver_name");?>
                    </label>
                
                    <span class="ui-select">
                      <select id="driver_name" name="driver_name" required>
                      <?php
                        echo "<option value=''>".$this->lang->line("select_driver")."</option>";
                        
                        foreach ($all_staff as $key => $staff) 
                        {
                            if($staff->staff_type=='Driver' && $driver_id == $staff->id)
                              echo "<option value=".$staff->id." selected='selected'>".$staff->name."</option>";
                            else if($staff->staff_type=='Driver')
                              echo "<option value=".$staff->id.">".$staff->name."</option>";  
                        }
                      ?>
                      </select>
                    </span>

                </div>

                <div class="col-md-2 label_dropdown" >

                    <label class="control-label" for="conductor_name">
                      <?php echo $this->lang->line("conductor_name");?>
                    </label>
                
                    <span class="ui-select">
                      <select id="conductor_name" name="conductor_name" required>
                      <?php
                        echo "<option value=''>".$this->lang->line("select_conductor")."</option>";
                        
                        foreach ($all_staff as $key => $staff) 
                        {
                          print_r($all_staff);
                            if($staff->staff_type=='Conductor' && $conductor_id == $staff->id)
                              echo "<option value=".$staff->id." selected='selected'>".$staff->name."</option>";
                            else if($staff->staff_type=='Conductor')
                              echo "<option value=".$staff->id.">".$staff->name."</option>";  
                        }
                      ?>
                      </select>
                    </span>

                </div>


                <div class="col-md-2 label_dropdown" >

                    <label class="control-label" for="helper_name">
                      <?php echo $this->lang->line("operator_name");?>
                    </label>
                
                    <span class="ui-select">
                      <select id="operator_name" name="operator_name" required>
                      <?php
                        echo "<option value=''>".$this->lang->line("select_operator")."</option>";
                        
                        foreach ($all_staff as $key => $staff) 
                        {
                            if($staff->staff_type=='Operator' && $operator_id == $staff->id)
                              echo "<option value=".$staff->id." selected='selected'>".$staff->name."</option>";
                            else if($staff->staff_type=='Operator')
                              echo "<option value=".$staff->id.">".$staff->name."</option>";  
                        }
                      ?>
                      </select>
                    </span>

                </div>
                <div class="col-md-12" style="padding-top:1%;">
                  <div class="ui-input-group">
                  
                  <input type="text" class="form-control" type="text" id="assign_date" name='assign_date' required />
                                    
                  
                  <label for="assign_date"><?php echo $this->lang->line("choose_date");?></label>
                  
                  </div>

                </div>

                <div class="card-action no-border col-md-12" align="right">
                  <input  type="hidden" id="cid" name="cid" value=""/>
                  
                  <input id="submit" type="button" value="Update" data-toggle="tooltip" title="<?php echo $this->lang->line("update")." ".$this->lang->line("bus")." ".$this->lang->line("staff"); ?>" ui-wave class="btn btn-primary md-button" />
                 
                </div>

              </form>
            </div></div></div>

        </div>
        
      </section>
    </div>
  </div>
</div>

<script type="text/javascript">

$("#output_msg").hide();

$('#submit').click(function()
{
      // AJAX Code To Submit Form.

      var bus_name = $("#bus_name").val();
      var driver_name = $("#driver_name").val();
      var conductor_name = $("#conductor_name").val();
      var helper_name = $("#helper_name").val();
      var other_name = $("#other_name").val();
      var assign_date = $("#assign_date").val();
      
      
      if(bus_name==''){
        alert_msg_box('Bus name is required.');
      }else if(driver_name==''){
        alert_msg_box('Driver name is required.');
      }
      else if(conductor_name==''){
        alert_msg_box('Conductor name is required.');
      }
      else if(assign_date==''){
        alert_msg_box('Date is required.');
      }


      if(bus_name!="" && driver_name!="" && assign_date!="" && conductor_name!="")
      {

          // Returns successful data submission message when the entered information is stored in database.
          var post_data = $("#staff_assign_form").serialize();
         
          $.ajax({
            method:'POST',
            url:BASE_PATH_URL+"index.php/staff_assign/update",
            data:post_data,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            timeout:1000,
            async : false,
            success: function(res)
            {
              alert_msg_box(res,'sa');
            }
          });
      }  
});

</script> 
