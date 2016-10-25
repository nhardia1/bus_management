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
<script  src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script> 


<div class="page page-table" ng-controller="EditCityController">
    <div class="row ui-section">
        <div class="col-lg-8 clearfix">
            <h3 class="section-header">Edit City</h3>
        </div>
        <div class="col-md-12">
            <section class="panel panel-default table-dynamic">
                <div class="table-filters">
                    <div class="row">
                        <div class="panel-body" ng-show="alerts">
                    <alert type="info" close="closeAlert()">{{alerts_msg}}</alert>                            
                    </div>
                        <div class="col-md-8 col-md-offset-2" >
                            <form name="userForm" ng-submit="submitForm()" id="editCityForm">

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="inputEmail3">States Name</label>
                                    <span class="ui-select">
                                        <select id="route-states" name="route_states" ng-model="user.state_name">
                                            <option value="">Select States</option>
                                            <option ng-repeat="states in source_states | orderBy:name" value="{{states.id}}" ng-selected="{{ user.state_id == states.id }}">{{states.name}}</option>
                                        </select>
                                    </span>
                                </div>
                                <div class="form-group" >
                                        <label class="col-sm-2 control-label" for="inputEmail3">City Name</label>
                                        <div class="col-sm-10" >
                                            
                                            <input type="hidden" ng-model="user.id" value="{{user.id}}" />
                                            <input class='form-control input_field_text' type="text" id="p_new" name="p_new" value="" placeholder="Input Value" ng-model="user.name" /><div class="add_city_btn"></div>
                                        </div>
                                </div>

                                <div id="stoppage_listing"></div>
                                <div class="card-action no-border text-right">
                                    
                                    <input type="button" ui-wave class="btn btn-primary btn-w-md" value="Update" data-ng-click="updateCity()" />
                                    
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

