<div class="page" ng-controller="AddFareFormCtrl">

    <div class="row ui-section">
        <div class="col-md-12 breadcum_container">
            <section class="panel panel-default breadcum_section">

              <h3 class="section-header"><?php echo $this->lang->line("add")." ".$this->lang->line("fare");?></h3>
            
              <a href="<?php echo base_url();?>index.php/home">
                <i class="imd imd-home"></i> <?php echo $this->lang->line("dashboard") ?>
              </a>
              
              <i class="imd imd-keyboard-arrow-right"></i>
              
              <a href="<?php echo base_url();?>index.php/route/routes_fare_details">
                <i class="imd imd-attach-money"></i> <?php echo $this->lang->line("fare") ?>
              </a>

              <i class="imd imd-keyboard-arrow-right"></i>

              <a href="<?php echo base_url();?>index.php/route/add_fare">
                <?php echo $this->lang->line("add") ?>
              </a>

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

                            <form role="form" name="add_fare_form" id="add_fare_form">
                               
                               <div class="row">
                                    
                                    <div class="col-sm-3 col-md-offset-4 label_dropdown">
                                       <label for="fare_routes"><?php echo $this->lang->line("route");?></label><br/> 
                                       <span class="ui-select">
                                            <select id="fare_routes" name="fare_routes" onchange="get_route_details();">
                                                <option value=""><?php echo $this->lang->line("select_route");?></option>
                                                <?php
                                                foreach($route_list as $arr)    
                                                {
                                                    $id = $arr->id;

                                                    $name = $arr->name;

                                                    $sel = "";
                                                    if($edit_route_id == $id)
                                                    {
                                                        $sel = "selected = 'selected'";
                                                    }
                                                ?>

                                                    <option value="<?php echo $id;?>" <?php echo $sel;?>><?php echo $name;?></option>

                                                <?php
                                                }
                                                ?>  
                                            </select>
                                        </span>
                                    </div>

                                    

                                </div>

                               <div class="row">
                                    
                                    <div class="col-sm-12">
                                        <div><?php echo $this->lang->line("source"); ?> : <b><span id="source_city"></span></b></div><br/>
                                        <div><?php echo $this->lang->line("destination"); ?> : <b><span id="destination_city"></span></b></div>
                                    </div>

                                    
                                </div>
                                
                                <div class="divider divider-dashed divider-lg pull-in"></div>
                                
                                
                                <div class="row">                                    

                                    <div class="col-md-12" >
                                        
                                        <div class="panel panel-info">
                                            
                                            <div class="panel-heading"><span class="glyphicon glyphicon-th"></span> <?php echo $this->lang->line("enter_fare");?></div>
                                            
                                            <div class="" style="overflow:auto;">
                                                                                             
                                                <div align="center" id="matrix"></div>
                                                    
                                            </div>

                                        </div>

                                    </div>                                   


                                </div>
                                
                                <br/>
                                <div class="row col-md-12" align="right">
                                    <input type="button" id="btn_save"  onclick="save_fare();" class="btn  btn-primary md-button md-default-theme" value="Save"><div class="divider"></div>
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
$(function()
{
    var r = "<?php echo $edit_route_id;?>";

    if(r != "")
    {
        get_route_details();
    }
    else
    {
        $("#btn_save").attr("disabled",true);
        $("#btn_save").addClass("btn_disabled");
    }


});   

</script>
