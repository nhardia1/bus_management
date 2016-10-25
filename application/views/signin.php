<style>
.page-signup .card-content, .page-signup .panel-body {
  padding: 0 !important;
}
.page-signup .card.bg-white {
  padding: 30px 20px;
}
.page-signup .parsley-errors-list {
  padding: 0;
}
.page-signup .parsley-errors-list li {
  list-style: outside none none;
  color: #f44336;
}
.top-email{
	top:-14px;
}
</style>
<script>
	$('#email').keyup(function() {
        $('#label-email').addClass('top-email');
    });
</script>
<div class="wrapper">
  <div class="main-body">
    <div class="card bg-white">
      
         <?php if(validation_errors() != false) {?>
         <div class="panel-body">
        <alert type="info" >
    <div class="alert alert-danger">
      <strong>Alert!</strong> <?php echo validation_errors(); ?>
    </div>
</alert>                            
      </div>
    <?php } ?>
        


      <div class="card-content" style="">
        <form name="form_signin" method='post' action='<?php echo base_url();?>index.php/login/login_submit' class="form-horizontal form-validation" data-parsley-validate>
          <fieldset>
            <div class="form-group">
              <div class="ui-input-group">      

               <input type="email" name='email' id="email" class="form-control" required>
              <!-- <span class="input-bar"></span>-->
               <label id="label-email">Email</label>
             </div>
           </div>
           <div class="form-group">
            <div class="ui-input-group">      

              <input type="password"
              class="form-control" name='password'
              required
              >
            <!-- <span class="input-bar"></span>-->
              <label>Password</label>
            </div>
          </div>
          <div class="divider"></div>
          <div class="form-group">
            <a href="<?php echo base_url();?>index.php/login/forget" class="text-muted text-small">Forgot your password?</a>
          </div>
          <div class="text-right">

     <button type="submit"
     ui-wave
     class="btn btn-primary md-button md-default-theme"
     data-ng-click="login_submit()"
     >Login</button>
     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
   </div>
        </fieldset>

        
      </form>
    </div>
    
 </div> 
</div>
</div>
</div>
