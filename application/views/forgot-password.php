<style>
.page-signup .parsley-errors-list {
  padding: 0;
}
.page-signup .parsley-errors-list li {
  list-style: outside none none;
  color: #f44336;
}
</style>
<div class="wrapper">
        <div class="main-body">

            <div class="card bg-white">
                
                      <?php if(isset($error)) 
                      {
                          echo '<div class="row"><div class="alert alert-danger" close="closeAlert()">';
                              echo $error->email;
                             echo '</div></div>'; 
                      }
                      
                      if(isset($message)) 
                      {

                              echo '<div class="row"><div class="alert alert-danger" close="closeAlert()">';
                              echo $message;
                             echo '</div></div>'; 
                       }
                       ?>
                    
                <div class="card-content">
                                  <div><h4>Forgot Password</h4></div>
                    <form name="form_signin" method='post' action='<?php echo base_url();?>index.php/login/forget_password' class="form-horizontal form-validation" data-parsley-validate>
                        <fieldset>
                            
                            <div class="form-group">
                                <div class="ui-input-group">      
                                  
                                   <input type="email" name="email"
                                                   class="form-control"
                                                   required
                                                   >
                                   <!-- <span class="input-bar"></span>-->
                                    <label>Email</label>
                                </div>
                               
                            </div>
                        </fieldset>

                        <div class="card-action no-border text-right">
                   
                     <button type="submit"
                                            ui-wave
                                            class="btn btn-primary md-button md-default-theme"
                                          data-ng-click="forget_password()"  >Submit</button>
                                            
                                  
                                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url();?>index.php/login/index">Login</a>
                </div>
                    </form>
                </div>
                
                
            </div> 
           
        </div>
    </div>

</div>
<script>
function submit_login(){

    window.location.href='#/signin';
}


</script>