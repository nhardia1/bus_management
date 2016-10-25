<script type="text/javascript" src="<?php echo base_url();?>theme/scripts/multidatepicker/jquery-ui.multidatespicker.js"></script>

<script type="text/javascript">
$(function() 
{
   $('#assign_date').multiDatesPicker({numberOfMonths: [1,3],
   
      dateFormat: "dd-mm-yy",
      onSelect:function(seldate,b)
      {
          chk_device_assigned(seldate);
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

              <h3 class="section-header"><?php echo $this->lang->line("device")." ".$this->lang->line("assign");?></h3>
            
              <a href="<?php echo base_url();?>index.php/home">
                <i class="imd imd-home"></i> <?php echo $this->lang->line("dashboard");?> 
              </a>
              
              <i class="imd imd-keyboard-arrow-right"></i>
              
              <a href="<?php echo base_url();?>index.php/device_assign">
                <i class="imd imd-tap-and-play"></i> <?php echo $this->lang->line("device")." ".$this->lang->line("assign");?>
              </a>

              <i class="imd imd-keyboard-arrow-right"></i>
              
              <a href="<?php echo base_url();?>index.php/device_assign/add">
                <?php echo $this->lang->line("add");?>
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
              <form name="device_assign_form" id="device_assign_form" method='post' data-parsley-validate>
               
                <div class="col-md-6 label_dropdown">
                  
                  <label class="control-label" for="bus_name"><?php echo $this->lang->line("bus_name");?></label>
                  
                  <span class="ui-select">
                  
                  <select id="bus_name" name="bus_name" required>
                    <?php
                      echo "<option value=''>".$this->lang->line("select_bus")."</option>";
                      foreach ($all_bus as $key => $bus) 
                      {
                        echo "<option value=".$bus->bus_id.">".$bus->name." (".$bus->bus_number.")</option>";
                      }
                      ?>
                  </select>
                  </span>

                </div>
                
                
                <div class="col-md-6 label_dropdown" >

                    <label class="control-label" for="bus_device">
                      <?php echo $this->lang->line("device_name");?>
                    </label>
                
                    <span class="ui-select">
                      <select id="bus_device" name="bus_device" required>
                      <?php
                        echo "<option value=''>".$this->lang->line("select_device")."</option>";
                        
                        foreach ($all_device as $key => $device) 
                        {
                            echo "<option value=".$device->id.">".$device->name."</option>";
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
                  
                  <input id="submit" type="button" value="Save" ui-wave class="btn btn-primary md-button" />
                 
                </div>

              </form>
            </div></div></div>

        </div>
        
      </section>
    </div>
  </div>
</div>

<script type="text/javascript">
var bus_device='';
var bus_name='';
$('#bus_device,#bus_name').on('change', function()
{
   bus_device = $('#bus_device').val();
    bus_name = $('#bus_name').val();


    if(bus_device!='' && bus_name!='')
    {
      $.ajax({
        type :'POST',
        dataType:'html',
        data : { 
          bus_name : bus_name,
          bus_device : bus_device
         },
        url : '<?php echo base_url(); ?>index.php/device_assign/get_device_date',
        success : function(result)
        {
            var arr = result.split(",");
            var i = 0;
            var len = arr.length;                


            $('#assign_date').multiDatesPicker('resetDates', 'picked');

            for(;i<len;i++)
            {
                $('#assign_date').multiDatesPicker('addDates', [arr[i]]);
            }


            
                   
        }
    });
}

});


$("#output_msg").hide();

$('#submit').click(function()
{
      // AJAX Code To Submit Form.

      var bus_name = $("#bus_name").val();
      var bus_device = $("#bus_device").val();
      var assign_date = $("#assign_date").val();
      
      if(bus_name!="" && bus_device!="" && assign_date!="")
      {

          // Returns successful data submission message when the entered information is stored in database.
          var post_data = $("#device_assign_form").serialize();
         
          $.ajax({
            method:'POST',
            url:BASE_PATH_URL+"index.php/device_assign/insert",
            data:post_data,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            timeout:1000,
            async : false,
            success: function(res)
            {
              alert_msg_box(res,'da');
            }
          });
      }  
});





</script> 
