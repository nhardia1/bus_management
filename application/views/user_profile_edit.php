<?php
if(isset($profile_info[0]->dob) && !empty($profile_info[0]->dob) && $profile_info[0]->dob!="0000-00-00")
{
  $dob = date("d-m-Y",strtotime($profile_info[0]->dob));
}
else
{
  $dob = "";
}


if(isset($profile_info[0]->photo) && !empty($profile_info[0]->photo))
{
    $profile_image = '<img class="img-circle" data-toggle="modal" data-target="#myModal" src='.base_url().'public/uploads/user/'.$profile_info[0]->photo.' />';
}

?>

<style type="text/css">
.right_border_img
{
    border: 1px solid rgb(255, 255, 255); 
    height: 70%; 
    width: 1px; 
    margin-left: 85%;
}

.form-control
{
  padding: 5px !important;
}

.head_profile
{
    background-color: rgba(0,0,0,.8);
    padding-left: 10px;
    color: #fff;
}
</style>

<form name="userEditForm" id="userEditForm" method='post' enctype='multipart/form-data' data-parsley-validate>
 <!--div class="head_profile">
      <ul class="list-unstyled list-inline">
          <li><h3>Profile</h3>
          </li>
      </ul>    
  </div-->

<div class="page page-profile">




    <header class="profile-header">         

        <div class="panel-body" id="output_msg">
            <div class="alert alert-info"></div>
        </div>
       

        <div class="panel-body">
          
          <div class="row">

            <div class="col-md-2" >
                
              <div class="col-md-12">
                <div class="profile-img">            
                    <?php echo $profile_image;?>
                </div>
              </div>         
            </div>


            <!--div class="col-md-1"><div class="right_border_img"></div></div-->
            

            <div class="col-md-10" >            
            
              <div class="col-sm-3">
                <div class="ui-input-group">
                  <input type="text" class="form-control" id="user_name" name="user_name" value="<?php echo $profile_info[0]->name;?>" required >
                  <label for="user_name"><?php echo $this->lang->line("full_name");?></label>
                </div>
              </div>

              
              <div class="clearfix"></div>

              <div class="col-sm-3">
                <div class="ui-input-group">
                  <input type="text" class="form-control" maxlength='12' name='user_contact_number' id="user_contact_number" value="<?php echo $profile_info[0]->contact_number;?>" required>
                  <label for="user_contact_number"><?php echo $this->lang->line("contact_number");?></label>
                </div>
              </div>

              <div class="clearfix"></div>
              
                  
              
              <div class="col-sm-3">
                  <div class="ui-input-group">
                      <input type="text" class="form-control" type="text" id="user_dob"  name='user_dob' value="<?php echo $dob;?>" required />
                      <label for="user_dob"><?php echo $this->lang->line("date_of_birth");?></label>
                  </div>
              </div>

               <div class="clearfix"></div>

              <div class="col-md-12" style="margin-bottom:-15px">
                  <label for="bus_model"><span style="font-size:12px; font-weight:200px;"><?php echo $this->lang->line("profile_photo");?></span>
                  </label>
              </div>
                                  
              

              <div class="col-sm-3 upload_image" style="margin-left:13px;margin-top:10px;">
                  <span><?php echo $this->lang->line("select").' '.$this->lang->line("file");?>...</span>

                  <input type="file" id='fileUpload' name='fileUpload' title="Choose File" data-ui-file-upload class="btn-raised btn-w-md btn-success" value="<?php echo $profile_info[0]->photo;?>">

                  <input type="hidden" name="user_photo" id="user_photo" value="<?php echo $profile_info[0]->photo;?>" />
                 
              </div>

              <div class="clearfix"></div>
              
              <div class="col-md-12" style="margin-top:-15px">
                  <label for="bus_model"><span style="font-size:12px; font-style:italic; font-weight:200px;">(File support : jpeg, gif, png, jpg.)</span>
                  </label>
              </div>

              
              <div class="clearfix"></div>
              
              
              <div class="col-sm-3">
                    <input id="update" type="submit" value="Update" ui-wave class="btn btn-raised btn-primary btn-lg">
              </div>

              </div>
            
            
          
              </div>
        </div> 
      
    </header>


    </div>

    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog"> 

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">
              <?php 
              if(isset($profile_info[0]->photo) && !empty($profile_info[0]->photo))
              {
                echo end(explode("_",$profile_info[0]->photo));
              }
              else
              {  echo "Image View";
              }
              ?>
            </h4>
            </div>
            <div class="modal-body">
              <p>
               <?php
              
               echo '<img style="width:100%;height:60%;" src='.base_url().'public/uploads/user/'.$profile_info[0]->photo.'>';
              
               ?>
             </div>
             <div class="modal-footer"> 
              <button type="button" class="btn btn-default" data-dismiss="modal">CANCEL</button>
            </div>
          </div>
        </div>
      </div>
</form>

<script type="text/javascript">
$('#fileUpload').on('change',function()
{
   show_file_name(this);    
});

$('#userEditForm').submit(function()
{

    if($("#user_name").val() == "" || $("#user_contact_number").val() == "" || $("#user_dob").val() == "")
    {
      return;
    }

    show_processing();
    var file_data = $('#fileUpload').prop('files')[0];   
    
    var form_data = new FormData($('#userEditForm')[0]);                  
    
    form_data.append('fileUpload', file_data);
    
    var url ="<?php echo base_url(); ?>index.php/home/profile_save";


    $.ajax({
      type: "POST",
      url: url,
      dataType: 'text',  // what to expect back from the PHP script, if anything
      cache: false,
      contentType: false,
      processData: false,
      data: form_data,
      success: function(result)
      {
          $("#output_msg").show();
          $("#output_msg").find("div").html(result);
          
          alert_msg_box(result,'hm');
          
      }
    });

    return false;
});
</script>

<!-- calendar -->
<script type="text/javascript" src="<?php echo base_url(); ?>public/parsley/parsley.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>theme/styles/jquery-ui.css">
<script>
$( "#user_dob" ).datepicker({
        dateFormat: 'dd-mm-yy',
        changeMonth: SHOW_MONTH,
        changeYear: SHOW_YEAR,
        numberOfMonths: 1 ,
        yearRange: '1950:'      
});
</script>