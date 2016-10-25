<?php 
$staff_type=staff_type();
?>
<div class="page">

  

  <div class="row ui-section">
<div class="col-md-12 breadcum_container">
            <section class="panel panel-default breadcum_section">

              <h3 class="section-header">
                <?php 
                if(isset($single_staff_detail)){
               
                echo $this->lang->line("edit").' '.$this->lang->line("staff"); //echo 'Edit Staff'; 
               
              }else {
                
                echo $this->lang->line("add").' '.$this->lang->line("staff"); //echo 'Add Staff';
               
              } ?>

                </h3>
            
              <a href="<?php echo base_url(); ?>index.php/home">
                <i class="imd imd-home"></i> <?php echo $this->lang->line("dashboard"); ?> 
              </a>
              
              <i class="imd imd-keyboard-arrow-right"></i>
              
              <a href="<?php echo base_url(); ?>index.php/staff">
                <i class="imd imd-account-child"></i> <?php echo $this->lang->line("staff").' '.$this->lang->line("management"); ?>
              </a>

              <i class="imd imd-keyboard-arrow-right"></i>
              <?php 
              if(isset($single_staff_detail)){
                echo  "<a href='".base_url()."index.php/staff/add_staff/id/".$id."'>";
                echo $this->lang->line("edit");
                echo "</a>";
              }else {
                echo "<a href='".base_url()."index.php/staff/add_staff'>";
                echo $this->lang->line("add");
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

            <form name="staffForm" id="staffForm" method='post' enctype='multipart/form-data' data-parsley-validate>

               <div class="col-md-6">

                  <div class="ui-input-group">
                  
                    <input type="text" class="form-control" id="staff_name" name="staff_name" 
                    <?php if(isset($single_staff_detail)){   ?> value="<?php echo $single_staff_detail['name']; ?>"
                    <?php  } ?> required >

                    <label for="staff-name"><?php echo $this->lang->line("staff_name"); ?></label>

                  </div>

                </div>

               

                <div class="col-md-1" >  
                 <div class="ui-input-group" style="float: right; width: 25px;">                    
                    <input type="text" placeholder="+91" class="form-control" readonly style="width: 35px; padding-left: 0px;">                   
                  </div>
                </div>

                <div class="col-md-5" style="padding-left:0px;">
                  <div class="ui-input-group">

                    <input type="tel" class="form-control" step="100" 
    data-parsley-validation-threshold="1" data-parsley-trigger="keyup" 
    data-parsley-type="digits" maxlength='10' name='staff_contact_number' id="staff_contact_number" 
                    <?php if(isset($single_staff_detail)){  ?>
                    value="<?php echo $single_staff_detail['contact_number']; ?>"
                    <?php } ?> required>

                    <label for="staff-contact-number"><?php echo $this->lang->line("contact_number"); ?></label>

                  </div>

                </div>


                <div class="clearfix"></div>

                <div class="col-md-6">

                  <div class="ui-input-group">

                     <input type="text" class="form-control" name='staff_address' id="staff_address" 
                     <?php if(isset($single_staff_detail)){  ?>
                     value="<?php echo $single_staff_detail['address']; ?>"
                     <?php } ?>
                     required>

                     <label for="staff_address"><?php echo $this->lang->line("address"); ?></label>

                  </div>

                </div>

                 <div class="col-md-6 label_dropdown">
                  
                  <label for="staff-type"><?php echo $this->lang->line("cust_stafftype"); ?></label><br/>

                  <span class="ui-select">

                    <select id="staff_type" name='staff_type' required>

                      <?php

                      if(!isset($single_staff_detail)){

                        echo "<option value=''>".$this->lang->line("staff_select_type")."</option>";

                      }
                      foreach ($staff_type as $value) { ?>

                      <option value="<?php echo $value->role_id;?>"
                        <?php if($single_staff_detail['staff_type_num']== $value->role_id){ echo "selected='selected'"; } ?> >

                        <?php echo $value->user_type;?></option>

                        <?php  } ?>

                      </select>

                    </span>

                  </div>
                 
                  <div class="clearfix"></div>

                    <div class="col-md-6">

                    <div class="ui-input-group">
                      <input type="text" class="form-control" id="staff_pin" name='staff_pin'  <?php if(isset($single_staff_detail)){  ?>
                     value="<?php echo $single_staff_detail['plain_pin']; ?>"
                     <?php } else{?> value="<?php echo mt_rand(1000, 9999);}?>" readonly="readonly">

                    </div>

                  </div>

                 

                   <div class="clearfix"></div>

                    <div class="col-md-6" id="agency_dropdown" <?php if($single_staff_detail['staff_type_num']=='2' ){ 

                  }else{echo "style='display:none;'"; }?>>

                    <div class="ui-input-group label_dropdown">

                      <span class="ui-select">
                            <select name="agency_id">
                            <?php 
                            $agencies=get_agency();
                            foreach ($agencies as $agency ) { ?>                          
                              <option value="<?php echo $agency->id;?>" <?php if(isset($single_staff_detail['agency_id']) && $single_staff_detail[agency_id]==$agency->id){echo "selected='selected'";} ?>><?php echo $agency->name;?></option>
                           <?php  }
                             
                            ?>
                            </select>
                          </span>
                    </div>

                  </div>

                 
                 


                  <div <?php if($single_staff_detail['staff_type_num']=='3' ){ 

                  }else{echo "style='display:none;'"; }?> id='type'>

                  <div class="col-md-6">

                    <div class="ui-input-group">

                      <input type="text" class="form-control" id="staff_license_number"
                      <?php if(isset($single_staff_detail)){  ?>
                      value="<?php echo $single_staff_detail['license_number']; ?>"
                      <?php } ?>
                      name='staff_license_number' required>

                      <label for="staff_license"><?php echo $this->lang->line("license").' '.$this->lang->line("number"); ?></label>

                    </div>

                  </div>

                    <div class="col-md-6">

                      <div class="ui-input-group">

                        <input type="text" class="form-control pikaday" type="text" id="staff_expiry_date"
                        <?php if(isset($single_staff_detail['expiry_date']) && !empty($single_staff_detail['expiry_date']) && $single_staff_detail['expiry_date']!="0000-00-00")
                        { $single_staff_detail['expiry_date'] = date('d-m-Y',strtotime($single_staff_detail['expiry_date'])); 
                          ?>
                        value="<?php echo date($single_staff_detail['expiry_date']); ?>"
                        <?php } ?>
                        name='staff_expiry_date' value="<?php echo date('d-m-Y'); ?>"/>
                        <label for="staff_expiry_late"><?php echo $this->lang->line("expiry").' '.$this->lang->line("date"); ?></label>

                      </div>

                    </div>

                    

                      <div class="col-md-12">
                          <label for="bus_model"><?php echo $this->lang->line("upload").' '.$this->lang->line("license"); ?>&nbsp;<span style="font-size:12px; font-style:italic; font-weight:200px;">(File support : jpeg, gif, png, jpg.)</span></label>
                      </div>
                      <div class="col-md-12">
                        <div class="upload_image col-md-6"><span><?php echo $this->lang->line("select").' '.$this->lang->line("file"); ?>...</span><input type="file" id='fileUpload' name='fileUpload' title="Choose File" data-ui-file-upload class="btn-raised btn-w-md btn-success"></div><span id="filename"></span><div class="col-md-3 text-right" >

                          <?php if(isset($single_staff_detail['image_path']) && !empty($single_staff_detail['image_path']))
                          { 

                            echo '<img class="bus_image_preview" data-toggle="modal" data-target="#myModal" src='.base_url().'public/uploads/staff/'.$single_staff_detail['image_path'].'>';
                          ?>
                              <input type="hidden" id='staff_lic_photo' name="staff_lic_photo" value="<?php echo $single_staff_detail['image_path'];?>"/>
                          <?php  
                         
                           }
                           else
                            {
                              ?>
                              <input type="hidden" id='staff_lic_photo' name="staff_lic_photo" value=""/>
                             <?php
                             }
                             ?> 
                           <input type="hidden" id='image_form_submit' name="image_form_submit" value="1"/>
                           
                        </div>
                      </div>
                      

                         <div class="clearfix"></div>






                        <div class="modal fade" id="myModal" role="dialog">
                          <div class="modal-dialog"> 

                            <!-- Modal content-->
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title"><?php 
                                if(isset($single_staff_detail['image_path']) && !empty($single_staff_detail['image_path']))
                                {
                                  echo end(explode("_",$single_staff_detail['image_path']));
                                }
                                else
                                {  echo $this->lang->line("image").' '.$this->lang->line("view");
                                }
                                ?>
                              </h4>
                              </div>
                              <div class="modal-body">
                                <p>
                                 <?php
                                
                                 echo '<img style="width:100%;height:60%;" src='.base_url().'public/uploads/staff/'.$single_staff_detail['image_path'].'>';
                                
                                 ?>
                               </div>
                               <div class="modal-footer"> 
                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line("cencel"); ?></button>
                              </div>
                            </div>
                          </div>
                        </div>

                    <!--</div>-->

                  </div>
                  <div class="clearfix"></div>
                  <div class="col-md-12">
                          <label for="bus_model"><?php echo $this->lang->line("upload").' '.$this->lang->line("photo"); ?>&nbsp;<span style="font-size:12px; font-style:italic; font-weight:200px;">(File support : jpeg, gif, png, jpg.)</span></label>
                      </div>
                      <div class="col-md-12">
                       <div class="upload_image col-md-6"><span><?php echo $this->lang->line("select").' '.$this->lang->line("file"); ?>...</span><input type="file" id='imageUpload' name='imageUpload' title="Choose File" data-ui-file-upload class="btn-raised btn-w-md btn-success"></div><span id="photoname"></span><div class="col-md-3 text-right" >

                          <?php if(isset($single_staff_detail['profile_image']) && !empty($single_staff_detail['profile_image']))
                          { 

                            echo '<img class="bus_image_preview" data-toggle="modal" data-target="#myModal" src='.base_url().'public/uploads/staff/'.$single_staff_detail['profile_image'].'>';
                          ?>
                              <input type="hidden" id='staff_photo' name="staff_photo" value="<?php echo $single_staff_detail['profile_image'];?>"/>
                          <?php  
                         
                           }
                           else
                            {
                              ?>
                              <input type="hidden" id='staff_photo' name="staff_photo" value=""/>
                             <?php
                             }
                             ?> 
                           <input type="hidden" id='image_profile_submit' name="image_profile_submit" value="1"/>
                      </div>
                  <div class="col-md-12" align="right">

                    <?php if(isset($single_staff_detail)){  ?>

                    <input  type="hidden" id="submit_value" name="submit_value" value="update"/>

                    <input  type="hidden" id="id" name="id" value="<?php if(isset($single_staff_detail)){ echo $single_staff_detail['id']; } ?>"/>
                    
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

<script type="text/javascript">

    $('#fileUpload').on('change',function()
    {
       show_file_name(this);      
        
    });
    $('#imageUpload').on('change',function()
    {
       show_file_name(this);      
        
    });

    </script>


<script>


$(document).ready(function(){
  $('#staff_type').on('change', function() {
    if ( this.value == '3')    
    {
      $("#type").show();
    }
    else
    {
      $("#type").hide();
      $("#staff_license_number").val('');
      $("#staff_expiry_date").val('');
      $(".bus_image_preview").remove();
      $("#fileUpload").val('');
      
      //$("#image_form_submit").val(0);
    }
    if(this.value == '2'){
      $("#agency_dropdown").show();
    }else{
      $("#agency_dropdown").hide();
    }
  });
});



$('#staffForm').submit(function(){
            // AJAX Code To Submit Form.

            var file_data = $('#fileUpload').prop('files')[0];   
            var form_data = new FormData($('#staffForm')[0]);                  
            form_data.append('fileUpload', file_data);

            var updateUrl ="<?php echo base_url(); ?>index.php/staff/update_staff";
            var insertUrl ="<?php echo base_url(); ?>index.php/staff/insert_staff";


            var staff_name = $("#staff_name").val();
            var staff_contact_number = $("#staff_contact_number").val();
            var staff_address = $("#staff_address").val();
            var staff_type = $("#staff_type").val();
            var id = $("#id").val();
            var submit_value = $("#submit_value").val();
            var staff_license_number = $("#staff_license_number").val();


            if(staff_name!='' && staff_contact_number!='' && staff_address!='' && staff_type!='')
            {
              
              if((staff_type =='3' && staff_license_number !='') || (staff_type != '3' && staff_license_number == ''))
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
                  if(result.indexOf('success')>=0){                    
                      alert_msg_box(result,'stf');                      
                    
                  }
                }
              });
              }
            }
            return false;
          });
</script>

<!-- calendar -->
<script type="text/javascript" src="<?php echo base_url(); ?>public/parsley/parsley.min.js"></script>

<link rel="stylesheet" href="<?php echo base_url();?>theme/styles/jquery-ui.css">
<script>
  // initialize date picker 
var dt = new Date().getFullYear() + 30;
$( "#staff_expiry_date" ).datepicker({
        dateFormat: 'dd-mm-yy',
        changeMonth: SHOW_MONTH,
        changeYear: SHOW_YEAR,
        numberOfMonths: 1,
        yearRange: '2000:'+ dt
       
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
    control.makeTransliteratable(['staff_name','staff_address']);
  }
</script>
<?php 
if($this->session->userdata('message')!='english'){?>
<script>
 google.setOnLoadCallback(onLoad);
</script>
<?php }
?>