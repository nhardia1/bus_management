<div class="page-signin">
<div class="signin-header">
        <section class="logo text-center">
            <h1><a href="#/">{{main.brand}}</a></h1>
        </section>
    </div>


    <div class="wrapper">
        <div class="main-body" data-ng-controller="signupCtrl">
            <div class="card bg-white">
                <div class="card-content">
                    <form name="form_signup" class="form-horizontal form-validation" data-ng-submit="submitForm()">
                        <fieldset>
                            

                            <div class="form-group">
                                <div class="ui-input-group">      
                                 
                                   <input  type="text"
                                                class="form-control"
                                                
                                                data-ng-model="user.name"
                                                required
                                                data-ng-minlength="2"
                                                data-ng-maxlength="30">
                                    <span class="input-bar"></span>
                                    <label>User Name</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="ui-input-group">      
                                 
                                    <input type="email"
                                              
                                               class="form-control"
                                               data-ng-model="user.email"
                                               required>
                                    <span class="input-bar"></span>
                                    <label>Email</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="ui-input-group">      
                                  
                                    <input type="password"
                                               class="form-control"
                                               
                                               data-ng-model="user.password"
                                               required
                                               >
                                    <span class="input-bar"></span>
                                    <label>Password</label>
                                </div>
                            </div>
                          
                        </fieldset>
                    </form>
                </div>
                <div class="card-action no-border text-right">
                   
                    <button type="submit"
                                        ui-wave
                                        class="btn btn-primary btn-block btn-lg"
                                        data-ng-disabled="!canSubmit()" onclick="submit_login();">Sign up</button>
               
                </div>

                <div class="divider"></div>
                                  
                                    <div class="card-action no-border text-right">
                                        <a href="#/signin">Login</a>
                                    </div>
            </div> 
        </div>
            
    </div>



</div>
<script>
function submit_login(){

    window.location.href='#/dashboard';
}


</script>