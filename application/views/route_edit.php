<?php
$edit_id = $route_details[0]->id;
$edit_name = $route_details[0]->name;

$edit_source_state = $route_details[0]->source_state;
$edit_source_city = $route_details[0]->source_city;

$edit_destination_state = $route_details[0]->destination_state;
$edit_destination_city = $route_details[0]->destination_city;

$edit_stoppage_state = $route_details[0]->stoppage_state;
$edit_stoppage_state_arr = array();
if(isset($edit_stoppage_state) && !empty($edit_stoppage_state))
{
    $edit_stoppage_state_arr = explode(",", $edit_stoppage_state);
}

?>

<script type="text/javascript">
$(function()
{
    get_city_list('ss');

    get_city_list('ds');

    get_city_list('stp_st');

    $("#route_source_city").val(<?php echo $edit_source_city;?>);
    $("#route_destination_city").val(<?php echo $edit_destination_city;?>);

});
</script>
<style type="text/css">
    label
    {
        padding-left: 4px;
    }

</style>
<div class="page">

    <div class="row ui-section">
       <div class="col-md-12 breadcum_container">
            <section class="panel panel-default breadcum_section">

              <h3 class="section-header"><?php echo $this->lang->line("edit").' '.$this->lang->line("route"); ?></h3>
            
              <a href="<?php echo base_url();?>index.php/home">
                <i class="imd imd-home"></i> <?php echo $this->lang->line("dashboard"); ?> 
              </a>
                
                 <i class="imd imd-keyboard-arrow-right"></i>
              
                            
              <a href="<?php echo base_url();?>index.php/route/routes_details">
                <i class="imd imd-map"></i> <?php echo $this->lang->line("route"); ?>
              </a>

              <i class="imd imd-keyboard-arrow-right"></i>
              
              <a href="<?php echo base_url();?>index.php/route/edit/<?php echo $edit_id;?>">
                <?php echo $this->lang->line("edit"); ?>
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

                            <form role="form" name="add_route_form" ng-model="add_route_form">
                               
                               <div class="row" style="margin-bottom:3px;">
                                    
                                    <div class="col-sm-4">
                                        <div class="ui-input-group">
                                        
                                        <input type="text" class="form-control col-sm-4" id="route_name" onkeyup="chk_inputs('edit');" name="route_name" placeholder="Route Name" value="<?php echo $edit_name;?>"> 
                                        <label for="route_name"><?php echo $this->lang->line("route_name"); ?></label>
                                        </div>
                                    </div>
                                    

                                    <div class="col-sm-3 label_dropdown">
                                        <label for="route_source_state"><?php echo $this->lang->line("source").' '.$this->lang->line("state"); ?></label>
                                        <span class="ui-select">
                                            <select id="route_source_state" name="route_source_state" onchange="get_city_list('ss');chk_inputs('edit');">
                                                <option value=""><?php echo $this->lang->line("route_select_source_state"); ?></option>
                                                <?php
                                                foreach($state_list as $arr)    
                                                {
                                                    $id = $arr->id;

                                                    $name = $arr->name;

                                                    $sel = "";
                                                    if($edit_source_state == $id)
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

                                    <div class="col-sm-3 label_dropdown">
                                        <label for="route_destination_state"><?php echo $this->lang->line("destination").' '.$this->lang->line("state"); ?></label>
                                        <span class="ui-select">
                                            <select id="route_destination_state" name="route_destination_state" onchange="get_city_list('ds');chk_inputs('edit');">
                                                <option value=""><?php echo $this->lang->line("route_select_destination_state"); ?></option>
                                                <?php
                                                foreach($state_list as $arr)    
                                                {
                                                    $id = $arr->id;

                                                    $name = $arr->name;

                                                    $sel = "";
                                                    if($edit_destination_state == $id)
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
                                    
                                    <div class="col-sm-4"></div>
                                    

                                    <div class="col-sm-3 label_dropdown">
                                        <label for="route_source_city"><?php echo $this->lang->line("source").' '.$this->lang->line("city"); ?></label><br/>
                                        <span class="ui-select">
                                            <select id="route_source_city" name="route_source_city" onchange="chk_inputs('edit');">
                                                <option value=""><?php echo $this->lang->line("route_select_source_city"); ?></option>                                                
                                            </select>
                                        </span>
                                    </div>

                                    <div class="col-sm-3 label_dropdown">
                                        <label for="route_destination_city"><?php echo $this->lang->line("destination").' '.$this->lang->line("city")?></label>
                                        <span class="ui-select">
                                            <select id="route_destination_city" name="route_destination_city"  onchange="chk_inputs('edit');">
                                                <option value=""><?php echo $this->lang->line("route_select_destination_city")?></option>                                                
                                            </select>
                                        </span>
                                    </div>
                                </div>  

                               
                                
                                <div class="divider divider-dashed divider-lg pull-in"></div>
                                
                                

                                <div class="row">
                                    

                                    <div class="col-md-3" >
                                        
                                        <div class="panel panel-info">
                                            
                                            <div class="panel-heading"><span class="glyphicon glyphicon-th"></span> <?php echo $this->lang->line("select_stoppage_state")?></div>
                                            
                                            <div id="sel_stoppage_states" class="panel-body ui-map" style="overflow:auto;">
                                                
                                                <input type="text" id="search_stoppage_state" placeholder="<?php echo $this->lang->line("search_state"); ?>" class="form-control col-sm-2" />
                                                
                                                <?php
                                                foreach($state_list as $arr)    
                                                {
                                                    $id = $arr->id;

                                                    $name = $arr->name;

                                                    $sel = "";
                                                    if(in_array($id, $edit_stoppage_state_arr))
                                                    {
                                                        $sel = "checked = 'checked'";
                                                    }
                                                ?>

                                                    <label style="width:200px;">
                                                        <input type="checkbox" value="<?php echo $id;?>" <?php echo $sel;?> onclick="get_city_list('stp_st',<?php echo $id;?>);" /> <?php echo $name;?> <br/>
                                                    </label>

                                                <?php
                                                }
                                                ?>  
                                                
                                                    
                                            </div>

                                        </div>

                                    </div>



                                    <div class="col-md-3">
                                        <div class="panel panel-info">
                                            <div class="panel-heading"><span class="glyphicon glyphicon-th"></span> <?php echo $this->lang->line("select_stoppage_city")?></div>
                                            <div class="panel-body ui-map" style="overflow-y:auto;overflow-x:hidden;">
                                      
                                                <span class="ui-select">
                                                    <span class="stoppage_city_list_outer"><?php echo $this->lang->line("select").' '.$this->lang->line("stoppage").' '.$this->lang->line("city")?></span>
                                                </span>
                                                    
                                                <div class="stoppage_container">
                                                    <input type="text" id="search_stoppage_city" placeholder="<?php echo $this->lang->line("search_city"); ?>" class="form-control col-sm-2" /><br/><br/>
                                                    <p id="stoppage_city_list"></p>
                                                </div>
                                                    
                                                  
                                                  
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <div class="col-md-6">
                                        <div class="panel panel-info">
                                            <div class="panel-heading"><span class="glyphicon glyphicon-th"></span> <?php echo $this->lang->line("selected").' '.$this->lang->line("stoppage/destination").' '.$this->lang->line("city");?></div>
                                            <div class="panel-body ui-map" style="overflow-y:auto;overflow-x:hidden;">
                                                <div class="no-margin">
                                                    <ul id="sortable_stoppage" class="list-group">
                                                        <?php
                                                        if(isset($route_stoppage) && !empty($route_stoppage))
                                                        {
                                                            foreach($route_stoppage as $obj)
                                                            {
                                                                $cid = $obj->id;

                                                                $cnm = $obj->name;

                                                            ?>
                                                                <li class="list-group-item" id="sortable_stoppage_li_<?php echo $cid;?>"><?php echo $cnm;?><span style="float:right;" ><a href="#" onclick="remove_stoppage_city(<?php echo $cid;?>)" class="md-warn btn_delete"><span class="imd imd-delete"></span></a></span></li>

                                                            <?php   

                                                            }

                                                        }
                                                        ?>


                                                    </ul>                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div> 


                                </div>
                                
                                <br/>
                                <div class="row col-md-12" align="right">
                                    <input type="hidden" name="route_edit_id" id="route_edit_id" value="<?php echo $edit_id;?>" />
                                    <input type="button" id="btn_save" onclick="save_destination();" class="btn btn-primary md-button md-default-theme" value="Update"><div class="divider"></div>
                                </div>
                            </form>

                        </div>                        
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<script>
$(function() 
{
    $(".stoppage_container").hide();

    $(".stoppage_city_list_outer").click(function()
    {
        $(".stoppage_container").toggle();
    });
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
    control.makeTransliteratable(['route_name']);
    control.makeTransliteratable(['search_stoppage_state']);
    control.makeTransliteratable(['search_stoppage_city']);
  }
  google.setOnLoadCallback(onLoad);
</script>