<link rel="stylesheet" href="<?php echo base_url();?>theme/styles/jquery-ui.css" type="text/css" media="screen"/>

<div class="page page-table">
  <div class="row ui-section">
    
    <div class="col-md-12 breadcum_container">
            <section class="panel panel-default breadcum_section">

              <h3 class="section-header"><?php echo $this->lang->line("city")." ".$this->lang->line("management");?></h3>
            
              <a href="<?php echo base_url();?>index.php/home">
                <i class="imd imd-home"></i> <?php echo $this->lang->line("dashboard");?> 
              </a>
              
              <i class="imd imd-keyboard-arrow-right"></i>
              
              <a href="<?php echo base_url();?>index.php/city">
                <i class="imd imd-location-city"></i> <?php echo $this->lang->line("city");?>
              </a>

            </section>
        </div>
    

    <div class="col-md-12">
      <section class="panel panel-default table-dynamic">
        <div class="table-filters">

            <div class="panel-body" id="output_msg">
              <div class="alert alert-info"></div>
            </div>
            <div class="col-md-12" >
            <div class="row">
              <form name="cityForm" method='post' enctype='multipart/form-data' data-parsley-validate>
                <div class="col-md-2 col-md-offset-3 label_dropdown">
                  <label class="control-label" for="route_states"><?php echo $this->lang->line("city_state_name");?></label>
                  <span class="ui-select">
                  <select id="route-states" name="route_states" required>
                    <?php
                      if(!isset($single_city_detail)){

                        echo "<option value=''>".$this->lang->line("city_select_state")."</option>";
                      }

                      ?>
                    <?php foreach ($all_states as $key => $states) {
                        if($single_city_detail['state_name']==$states->name){
                          echo "<option value=".$states->id." selected='selected'>".$states->name."</option>";
                        }

                        echo "<option value=".$states->id.">".$states->name."</option>";
                      } ?>
                  </select>
                  </span> </div>
                <div class="col-md-3" >
                  
                  <div class="ui-input-group" style="margin:15px 12px !important;">
                    
                    <input class='form-control input_field_text' type="text" id="p_new" name="p_new" value="<?php if(isset($single_city_detail)){ echo $single_city_detail['name']; } ?>" style="margin-top:-9px;" required />

                    <label for="p_new"><?php echo $this->lang->line("city_city_name");?></label>
                  </div>
                </div>
                <div class="card-action no-border col-md-0">
                  <input  type="hidden" id="cid" name="cid" value="<?php if(isset($single_city_detail)){ echo $single_city_detail['id']; } ?>" />
                  <?php if(isset($single_city_detail)){  ?>
                  <input id="update" type="button" value="Update" data-toggle="tooltip" title="<?php echo $this->lang->line("city_update");?>" ui-wave class="btn btn-primary md-button md-default-theme" style="margin-top:14px;">
                  <?php
                } else { ?>
                  <input id="submit" type="button" value="Save" data-toggle="tooltip" title="<?php echo $this->lang->line("city_add");?>" ui-wave class="btn btn-primary md-button md-default-theme" style="margin-top:14px;">
                  <?php } ?>
                </div>
              </form>
            </div>
            <div class="divider divider-dashed divider-lg pull-in"></div>
            </div>

        </div>
        
        <?php echo $this->table->generate(); ?>
        
      </section>
    </div>
  </div>
</div>

<script type="text/javascript">

$("#output_msg").hide();
$('#submit').click(function(){
            // AJAX Code To Submit Form.

            var route_states = $("#route-states").val();
            var name = $("#p_new").val();

            // Returns successful data submission message when the entered information is stored in database.
            var dataString = 'route_states='+ route_states + '&name='+ name;

            $.ajax({
              type: "POST",
              url: "<?php echo base_url(); ?>index.php/city/insert_city",
              data: dataString,
              cache: false,
              headers: {'Content-Type': 'application/x-www-form-urlencoded'},
              timeout:1000,

              success: function(result){

                $("#output_msg").show();
                $("#output_msg").find("div").html(result);
                 if(result.indexOf('success')>=0){
                setTimeout(function(){location.reload();},3000);
                }
              }
            });
          });


$('#update').click(function(){
            // AJAX Code To Submit Form.

            var route_states = $("#route-states").val();
            var name = $("#p_new").val();
            var id = $("#cid").val();
            // Returns successful data submission message when the entered information is stored in database.
            var dataString = 'route_states='+ route_states + '&name='+ name+ '&cid='+ id;

            $.ajax({
              type: "POST",
              url: "<?php echo base_url(); ?>index.php/city/update_city",
              data: dataString,
              cache: false,
              headers: {'Content-Type': 'application/x-www-form-urlencoded'},
              timeout:1000,

              success: function(result){
                // alert(result);
                $("#output_msg").show();
                $("#output_msg").find("div").html(result);
                var redirect = '<?php echo base_url('index.php/city');?>'
                 if(result.indexOf('success')>=0){
                 setTimeout(function(){window.location.href = redirect;},3000);
               }
              }
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
    control.makeTransliteratable(['p_new']);
  }
  google.setOnLoadCallback(onLoad);
</script>