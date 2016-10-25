
<?php //echo "<pre>";print_r($device_list);die; ?>
<script type="text/javascript" src="<?php echo base_url(); ?>public/parsley/parsley.min.js"></script>
<div class="page">
  <div class="row ui-section">
    <div class="col-md-12 breadcum_container">
      <section class="panel panel-default breadcum_section">

        <h3 class="section-header">
          <?php 
          if(isset($single_device_detail)){

            echo  $this->lang->line("edit").' '.$this->lang->line("device"); //'Edit Device'; 

          }else {

            echo  $this->lang->line("add").' '.$this->lang->line("device"); //'Add Device';

          } ?>

        </h3>

        <a href="<?php echo base_url(); ?>index.php/home">
          <i class="imd imd-home"></i> <?php echo $this->lang->line("dashboard") ?>
        </a>

        <i class="imd imd-keyboard-arrow-right"></i>

        <a href="<?php echo base_url(); ?>index.php/device">
          <i class="imd imd-phone-iphone"></i> <?php echo $this->lang->line("device").' '.$this->lang->line("management"); ?>
        </a>

        <i class="imd imd-keyboard-arrow-right"></i>

        <?php 
        if(isset($single_device_detail)){
          echo  "<a href='".base_url()."index.php/device/add_device/id/".$id."'>";
          echo  $this->lang->line("edit"); //echo 'Edit'; 
          echo "</a>";
        }else {
          echo "<a href='".base_url()."index.php/deivce/add_device'>";
          echo  $this->lang->line("add"); //echo 'Add';
          echo "</a>";
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

              <form name="deviceForm" id="deviceForm" method='post' data-parsley-validate>

               <div class="col-md-6 label_dropdown">
                  
                  <label for="device-id"><?php echo $this->lang->line("device_id"); ?></label><br/>

                  <span class="ui-select">

                    <select id="device_id" name='device_id'>

                      <?php

                      if(!isset($single_device_detail)){

                        echo "<option value=''>".$this->lang->line("select_device_id")."</option>";

                      }
                      foreach ($device_list as $key => $value) { ?>

                      <option value="<?php echo $value->id;?>"
                        <?php if($single_device_detail['device_id']== $value->device_id){ echo "selected='selected'"; } ?> >

                        <?php echo $value->device_id;?></option>

                        <?php  } ?>

                      </select>

                    </span>

                  </div>




               <div class="col-md-6">

                <div class="ui-input-group">

                  <input type="text" class="form-control" id="device_name" name="device_name" 
                  <?php if(isset($single_device_detail)){   ?> value="<?php echo $single_device_detail['name']; ?>"
                  <?php  } ?> required >

                  <label for="device-name"><?php echo $this->lang->line("name"); ?></label>

                </div>

              </div>



              <div class="clearfix"></div>
              <div class="col-md-12" align="right">

                <?php if(isset($single_device_detail)){  ?>

                <input  type="hidden" id="submit_value" name="submit_value" value="update"/>

                <!--<input  type="hidden" id="id" name="id" value="<?php //if(isset($single_device_detail)){ echo $single_device_detail['id']; } ?>"/>
                -->
                <input id="update" type="submit" value="Update" ui-wave class="btn btn-primary md-button">
                <?php
              } else { ?>

              <input  type="hidden" id="submit_value" name="submit_value" value="insert"/>

              <input id="submit" type="submit" value="Save" ui-wave class="btn btn-primary md-button">

              <?php } ?>

            </div>

          </form>

        </div>      

      </div>

    </div>

  </section>

</div>

</div>

</div>



<script>


$('#device_id').on('change', function(){
    device_id = $('#device_id option:selected').val(); // the dropdown item selected value
    $.ajax({
        type :'POST',
        dataType:'json',
        data : { device_id : device_id },
        url : '<?php echo base_url(); ?>index.php/device/get_device_name',
        success : function(result){
         $('#device_name').val(result['name']);     
        }
    });

});



$('#deviceForm').submit(function(){
            // AJAX Code To Submit Form.

            
            var form_data = new FormData($('#deviceForm')[0]);                  

            var updateUrl ="<?php echo base_url(); ?>index.php/device/update_device";
            var insertUrl ="<?php echo base_url(); ?>index.php/device/insert_device";


            var device_name = $("#device_name").val();
            var id = $("#id").val();
            var submit_value = $("#submit_value").val();



            if(device_name!='')
            {
              
                if(submit_value =='update'){
                  url=updateUrl;
                }else if(submit_value =='insert'){
                  url=insertUrl;
                }
                console.log(url);
                $.ajax({
                  type: "POST",
                  url: url,
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data, 

                success: function(result){

                  $("#output_msg").show();
                  $("#output_msg").find("div").html(result);
                          
                  alert_msg_box(result,'dv');                     
                    
                  
                }
              });
              
            }
            return false;
          });
</script>


<script type="text/javascript">

  // Load the Google Transliterate API
  google.load("elements", "1", {
        packages: "transliteration"
      });

  function onLoad() {
    var options = {
        sourceLanguage:
            google.elements.transliteration.LanguageCode.ENGLISH,
        destinationLanguage:
            [google.elements.transliteration.LanguageCode.HINDI],
        shortcutKey: 'ctrl+g',
        transliterationEnabled: true
    };

    // Create an instance on TransliterationControl with the required
    // options.
    var control =
        new google.elements.transliteration.TransliterationControl(options);

    // Enable transliteration in the textbox with id
    // 'transliterateTextarea'.
    control.makeTransliteratable(['device_name']);
  }
  google.setOnLoadCallback(onLoad);
</script>



