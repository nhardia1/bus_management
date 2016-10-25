<div class="page">
  <div class="row ui-section">
     
     <div class="col-md-12 breadcum_container">
            <section class="panel panel-default breadcum_section">

              <h3 class="section-header"><?php echo $this->lang->line("change")." ".$this->lang->line("password");?></h3>
            
              <a href="<?php echo base_url(); ?>index.php/home">
                <i class="imd imd-home"></i> <?php echo $this->lang->line("dashboard"); ?> 
              </a>
              
              <i class="imd imd-keyboard-arrow-right"></i>
              
              <a href="<?php echo base_url(); ?>index.php/home/change_pass">
                <i class="imd imd-settings"></i> <?php echo $this->lang->line("change")." ".$this->lang->line("password");?>
              </a>

            </section>
    </div>

    <div class="col-md-12">
      <section class="panel panel-default">
          <div class="panel-body" id="output_msg">
            <div class="alert alert-info"></div>
          </div>

        <div class="panel-body" style="padding-top:15px;padding-left:30px;">
          
          <div class="row">
            <div class="col-md-12" >

  <form name="changePassword" id="changePassword" method='post'  data-parsley-validate>   
    <div class="ui-input-group">

        
        <input type="password" class="form-control" value='' name='oldpass' id="oldpass" required>
        <input type="hidden" id="type" name="type" value="<?php echo $type; ?>"/>
        <input type="hidden" id="user_id" name="user_id" value="<?php echo $id; ?>"/>
        <label for="oldpass"><?php echo $this->lang->line("old_password");?></label>
    </div>
<!-- minlength="12"   data-parsley-minlength="12" -->

    <div class="ui-input-group">

        
        <input type="password" class="form-control" value='' name='newpass' id="newpass" required>
        <label for="newpass"><?php echo $this->lang->line("new_password");?></label>
    </div>


     <!-- Message -->
    <div class="ui-input-group">

        
        <input class="form-control" required id="cnewpass" data-parsley-equalto="#newpass" data-parsley-error-message="The confirmed password is not same as new password" type="password" name="cnewpass"  value="">
        <label for="cnewpass"><?php echo $this->lang->line("confirm_password");?></label>
    </div>



   <div class="row">
<div class="col-md-12" align="right">
<input id="submit" type="submit" value="Update" ui-wave class="btn btn-primary md-button">
</div>
</div>
     </form>
   <div>
</div>

</div>
</div>
</div>
</section>
</div>
</div>
</div>


<script>
$('#changePassword').submit(function(){
            // AJAX Code To Submit Form.

           
                
            var oldpass = $("#oldpass").val();
            var newpass = $("#newpass").val();
            var cnewpass = $("#cnewpass").val();
            var staff_type = $("#staff_type").val();
            var id = $("#user_id").val();
            var type = $("#type").val();
            


            if(oldpass!='' && newpass!='' && cnewpass!='' )
            {
                var str = $("#changePassword").serialize();
                
                $.ajax({
                  type: "POST",
                  data:str,
                  url: "<?php echo base_url(); ?>index.php/home/password_update",
                
                  
                
                success: function(result){
                 
                  $("#output_msg").show();
                  $("#output_msg").find("div").html(result);
                  if(result.indexOf('success')>=0){
                    setTimeout(function(){
                      window.location ="<?php echo base_url(); ?>index.php/home";
                    },3000);
                  }
                }
              });
             
            }
            return false;
          });
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>public/parsley/parsley.min.js"></script>