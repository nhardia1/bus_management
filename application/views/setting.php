<style>
.input_field_text {
  float: left;
  margin-right: 10px;
  width: 90%;
}
.form-group {
  clear: both;
  margin-bottom: 15px;
  overflow: hidden;
}
</style>

<div class="page page-table" ng-controller="signinCtrls">
    <div class="row ui-section">
        <div class="col-lg-8 clearfix">
            <h3 class="section-header">Change Password</h3>
        </div>
        <div class="col-md-12">
            <section class="panel panel-default table-dynamic">
                <div class="table-filters">
                    <div class="row">
                        <div class="panel-body" ng-show="alerts">
                    <alert type="info" close="closeAlert()">{{alerts_msg}}</alert>                            
                    </div>
                        <div class="col-md-8 col-md-offset-2" >
                            <form name="userForm" ng-submit="submitForm()">
                                <div class="form-group" >
                                        <label class="col-sm-2 control-label" for="inputEmail3">New Password</label>
                                        <div class="col-sm-10" >
                                            
                                                <input class='form-control input_field_text' type="password" id="new_password" name="new_password" value="" placeholder="Input Value" data-ng-model="user.new_password" />
                                                <div class="add_city_btn">
                                                </div>
                                        </div>
                                </div>
                                <div class="form-group" >
                                        <label class="col-sm-2 control-label" for="inputEmail3">Confirm Password</label>
                                        <div class="col-sm-10" >
                                            
                                                <input class='form-control input_field_text' type="password" id="change_password" name="change_password" value="" placeholder="Input Value" data-ng-model="user.confirm_password" />
                                                <div class="add_city_btn">
                                                </div>
                                        </div>
                                </div>

                                <div class="card-action no-border text-right">
                                   <input type="button" ui-wave
                                    class="btn btn-primary btn-w-md" value="Update" data-ng-click="change_password()" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
           

                  
                </section>
                </div>
            </div>
        </div>
    </div>
</div>

