<div class="page" ng-controller="AddFareFormCtrl">

    <div class="row ui-section">
        <div class="col-lg-8 clearfix">
            <h2 class="section-header">Edit Fare</h2>
        </div>
        <div class="col-md-12">
            <section class="panel panel-default">

        
                <div class="panel-body" id="output_msg">
                    <div class="alert alert-info"></div>                            
                </div>
                    

                <div class="panel-body padding-lg">
                    <div class="row">
                        <div class="col-md-12" >

                            <form role="form" name="add_fare_form" id="add_fare_form">
                               
                               <!--div class="row">
                                    
                                    <div class="col-sm-12" align="center">
                                        <span class="ui-select">
                                            <select id="fare_routes" name="fare_routes" onchange="get_route_details();">
                                                <option value="">Select Route</option>
                                                <?php
                                                foreach($route_list as $arr)    
                                                {
                                                    $id = $arr->id;

                                                    $name = $arr->name;
                                                ?>

                                                    <option value="<?php echo $id;?>"><?php echo $name;?></option>

                                                <?php
                                                }
                                                ?>  
                                            </select>
                                        </span>
                                    </div>

                                </div-->

                               <div class="row">
                                    
                                    <div class="col-sm-12">
                                        <div>Route : <span id="route_name"><?php echo $route_name[0]->name;?></span></div><br/>
                                        <div>Source : <span id="source_city"><?php echo $route_source[0]->name;?></span></div><br/>
                                        <div>Destination : <span id="destination_city"><?php echo $route_dest[0]->name;?></span></div>
                                    </div>

                                    
                                </div>
                                
                                <div class="divider divider-dashed divider-lg pull-in"></div>
                                
                                
                                <div class="row">                                    

                                    <div class="col-md-12" >
                                        
                                        <div class="panel panel-info">
                                            
                                            <div class="panel-heading"><span class="glyphicon glyphicon-th"></span> Enter Fare</div>
                                            
                                            <div class="panel-body ui-map" data-slim-scroll data-scroll-height="380px">
                                                                                             
                                                <div align="center" id="matrix"></div>
                                                    
                                            </div>

                                        </div>

                                    </div>



                                    </div> 


                                </div>
                                
                                <br/>
                                <div class="row col-md-12" align="right">
                                    <input type="button" onclick="save_fare();" class="md-raised btn-w-sm md-primary md-button md-default-theme" value="Save"><div class="divider"></div>
                                </div>
                            </form>

                        </div>                        
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

