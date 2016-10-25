<?php
//$bus_types = array(1=>"Chassis",2=>"Single deck",3=>"Double deck",4=>"Minibus",5=>"Coach");
$bus_types=getBusTemplate();
?>
<div class="page">
  <div class="row ui-section">
    <div class="col-md-12 breadcum_container">
      <section class="panel panel-default breadcum_section">

        <h3 class="section-header"><?php echo $this->lang->line("edit").' '.$this->lang->line("bus"); ?></h3>

        <a href="<?php echo base_url(); ?>index.php/home">
          <i class="imd imd-home"></i> <?php echo $this->lang->line("dashboard") ?> 
        </a>

        <i class="imd imd-keyboard-arrow-right"></i>

        <a href="<?php echo base_url(); ?>index.php/bus">
          <i class="imd imd-directions-bus"></i> <?php echo $this->lang->line("bus") ?>
        </a>

        <i class="imd imd-keyboard-arrow-right"></i>

        <a href="<?php echo base_url(); ?>index.php/bus/edit_bus/id/<?php echo $id; ?>">
          <?php echo $this->lang->line("edit") ?>
        </a>

      </section>
    </div>
    <div class="col-md-12">
      <section class="panel panel-default">
        <div class="panel-body" id="output_msg">
          <div class="alert alert-info"></div>
        </div>


        <div class="panel-body ">

          <div class="row">
            <div class="col-md-12">

              <form name="bus_edit_form" id="bus_edit_form" method='post' enctype='multipart/form-data' data-parsley-validate>
                <?php /*echo "<pre>";print_r($bus_photo_detail); echo "</pre>";
                echo  count($bus_photo_detail);*/
                ?>
                <div class="col-md-4">
                  <div class="ui-input-group">
                    <input type="text" class="form-control" id="bus_name" name="bus_name" <?php if(isset($single_bus_detail)){   ?>
                    value="<?php echo $single_bus_detail['name']; ?>"
                    <?php    } ?>
                    placeholder='Enter Bus Title' required >
                    <label for="bus_name"><?php echo $this->lang->line("bus_title") ?></label>
                  </div>
                </div>

                
                <div class="col-md-4">
                  <div class="ui-input-group">

                    <input type="text" class="form-control" name='bus_number' id="bus_number" <?php if(isset($single_bus_detail)){   ?>
                    value="<?php echo $single_bus_detail['bus_number']; ?>"
                    <?php    } ?>
                    placeholder="Enter Bus Number" required>
                    <label for="bus_number"><?php echo $this->lang->line("bus").' '.$this->lang->line("number")." (उदाहरण : MP 09 AB 1234)"; ?></label>
                  </div>
                </div>


                <div class="col-md-4">
                  <div class="ui-input-group">

                   <input type="text" class="form-control" name='chassis_number' id="chassis_number" 
                   <?php if(isset($single_bus_detail)){   ?>
                   value="<?php echo $single_bus_detail['chassis_number']; ?>"
                   <?php    } ?>
                   placeholder="Enter Chassis Number" required>
                   <label for="chassis_number"><?php echo $this->lang->line("chassis").' '.$this->lang->line("number"); ?></label>
                 </div>
               </div>
               <div class="clearfix"></div>

              

                <div class="col-md-4 label_dropdown">
                                    <label for="bus_type"><?php echo $this->lang->line("operator_name") ?></label><br/>
                                    <span class="ui-select">
                                         <select id="operator_name" name="operator_name" required>
                                        <?php
                                          echo "<option value=''>".$this->lang->line("select_operator")."</option>";
                                          
                                          foreach ($all_staff as $key => $staff) 
                                          {
                                             
                                              if($staff->id==$single_bus_detail['operator_id']){
                                                $selected="selected='selected'";
                                              }else{
                                                $selected='';

                                              }
                                             if($staff->staff_type=='Operator'){
                                              echo "<option value=".$staff->id." $selected>".$staff->name."</option>";
                                            }
                                          }
                                        ?>
                                        </select>
                                    </span>
                </div>

                <div class="col-md-4">
                  <div class="ui-input-group">

                    <input type="text" class="form-control" name="bus_model" id="bus_model"
                    <?php if(isset($single_bus_detail)){   ?>
                    value="<?php echo $single_bus_detail['model']; ?>"
                    <?php    } ?>
                    placeholder="Bus Model" required>
                    <label for="bus_model"><?php echo $this->lang->line("bus").' '.$this->lang->line("model"); ?></label>
                  </div>
                </div>

                 <div class="col-md-4 label_dropdown">
                                    <label for="bus_type"><?php echo $this->lang->line("type") ?></label><br/>
                                    <span class="ui-select">
                                        <select id="bus_type" name="bus_type">
                                            <option value=""><?php echo $this->lang->line("select").' '.$this->lang->line("bus").' '.$this->lang->line("type"); ?></option>
                                            <?php
                                            foreach ($bus_types as $bt) 
                                            {

                                               if($bt->id==$single_bus_detail['type']){
                                                $selected="selected='selected'";
                                              }else{
                                                $selected='';

                                              }
                                            ?>   
                                                <option value="<?php echo $bt->id;?>" capacity="<?php echo $bt->seat_capacity;?>" <?php echo  $selected; ?>><?php echo $bt->template_name;?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <input type="hidden" name="bus_capacity" id="bus_capacity">
                                    </span>
                                </div>


                <!--///////////////////////////////////////////////-->
                <div class="col-md-12" style="padding-top:15px;">
                  <label for="bus_model"><?php echo $this->lang->line("upload").' '.$this->lang->line("document"); ?>&nbsp;<span style="font-size:12px; font-style:italic; font-weight:200px;">(File support : doc, txt, docx, pdf.)</span></label>
                </div>
                <div class="col-md-12"><div class="upload_image col-md-6"><span><?php echo $this->lang->line("select").' '.$this->lang->line("file"); ?>...</span><input type="file" id='bus_document' name="bus_document" class="" title="" ></div><div class="col-md-3 text-right" >
                <?php if(isset($single_bus_detail['document'])){ 


                 echo '<b><a href="'.base_url().'public/uploads/bus/'.$single_bus_detail['document'].'">'.end(explode("_",$single_bus_detail['document'])).'</a></b>';

               } ?>
               <input type="hidden" id='image_form_submit' name="image_form_submit" value="1"/>
             </div></div>


             <div class="clearfix"></div>

             <!--///////////////////////////////////////////////-->

             <!--/////////////////////////Bus Registration,permit,insurance/////////////////////////-->
             


             <div class="col-md-4">
              <label for="bus_model"><?php echo $this->lang->line("upload").' '.$this->lang->line("bus").' '.$this->lang->line("registration"); ?>&nbsp;<span style="font-size:12px; font-style:italic; font-weight:200px;">(File support : jpeg, gif, png, jpg.)</span></label>
              <div class="upload_image col-md-8"><span><?php echo $this->lang->line("select").' '.$this->lang->line("file"); ?>...</span><input type="file" id='bus_registration' name="bus_registration" class="" title="" ></div>
              <div class="col-md-4">
              <?php if(isset($single_bus_detail['registration_image']) && $single_bus_detail['registration_image']!=''){ 
                echo '<img class="bus_image_preview" data-toggle="modal" data-target="#registration_Modal" src='.base_url().'public/uploads/bus/'.$single_bus_detail['registration_image'].'>';

                

              } ?></div>
              <input type="hidden" id='registration_form_submit' name="registration_form_submit" value="1"/>
            </div>

            <div class="col-md-4">
              <label for="bus_model"><?php echo $this->lang->line("upload").' '.$this->lang->line("bus").' '.$this->lang->line("permit"); ?>&nbsp;<span style="font-size:12px; font-style:italic; font-weight:200px;">(File support : jpeg, gif, png, jpg.)</span></label>
              <div class="upload_image col-md-8"><span><?php echo $this->lang->line("select").' '.$this->lang->line("file"); ?>...</span><input type="file" id='bus_permit' name="bus_permit" class="" title="" ></div>
              <div class="col-md-4">
              <?php if(isset($single_bus_detail['permit_image']) && !empty($single_bus_detail['permit_image'])){ 
                echo '<img class="bus_image_preview" data-toggle="modal" data-target="#permit_Modal" src='.base_url().'public/uploads/bus/'.$single_bus_detail['permit_image'].'>';

              } ?></div>
              <input type="hidden" id='permit_form_submit' name="permit_form_submit" value="1"/>
            </div>

            <div class="col-md-4">
              <label for="bus_model"><?php echo $this->lang->line("upload").' '.$this->lang->line("bus").' '.$this->lang->line("insurance"); ?>&nbsp;<span style="font-size:12px; font-style:italic; font-weight:200px;">(File support : jpeg, gif, png, jpg.)</span></label>
              <div class="upload_image col-md-8"><span><?php echo $this->lang->line("select").' '.$this->lang->line("file"); ?>...</span><input type="file" id='bus_insurance' name="bus_insurance" class="" title="" ></div>
              <div class="col-md-4">
              <?php if(isset($single_bus_detail['insurance_image']) && !empty($single_bus_detail['insurance_image'])){ 
                echo '<img class="bus_image_preview" data-toggle="modal" data-target="#insurance_Modal" src='.base_url().'public/uploads/bus/'.$single_bus_detail['insurance_image'].'>';

              } ?></div>
              <input type="hidden" id='insurance_form_submit' name="insurance_form_submit" value="1"/>
            </div>
            <!--////////////////////////End Bus Registration,permit,Insurance//////-->

            <!--</div>-->
            <div class="clearfix"></div>




            <div class="col-md-12" style="padding-top:10px;">
              <label for="bus_model"><?php echo $this->lang->line("upload").' '.$this->lang->line("photo"); ?>&nbsp;<span style="font-size:12px; font-style:italic; font-weight:200px;">(File support : jpeg, gif, png, jpg.)</span></label> 


              <br>
              <?php if(count($bus_photo_detail)<1){ ?>
              <div>
                <div class="upload_image col-md-6">
                  <span><?php echo $this->lang->line("select").' '.$this->lang->line("file"); ?>...</span>
                  <input type="file" name="bus_photo[]" onchange="show_file_name(this);" />
                </div>
                <span class="showfilename"></span>
              </div>

              <div class="add_btn col-md-3 text-right" id="bus_add_btn_more">
                <a class="md-fab md-primary md-fab-sm md-button md-default-theme bus_add_more">
                  <span class="imd imd-add"></span>
                </a>
              </div>
              <?php } ?> 
            </div>


            <div class="clearfix"></div>
            <div id="bus_add_more_photo" class="col-md-12">
              <?php foreach ($bus_photo_detail as $key=>$value) {


                $img='';
               if($key=='0'){  
                if($value->image != '')
                  $img = '<img class="bus_image_preview" data-toggle="modal" data-target="#myModal_'.$value->id.'" src='.base_url().'public/uploads/bus/'.$value->image.'>';
                
                echo '<div><div class="upload_image col-md-6"><span>'.$this->lang->line("select").' '.$this->lang->line("file").'...</span><input type="file" name="bus_photo['.$value->id.']" onchange="show_file_name(this);" /></div><div class="add_btn col-md-3 text-right" id="bus_add_btn_more"><a class="md-fab md-primary md-fab-sm md-button md-default-theme bus_add_more"><span class="imd imd-add"></span></a></div><div class="clearfix"><input type="hidden" id="image_form_submit" name="image_form_submit" value="1"/>
                '.$img.'<input type="hidden" id="photo_name" name="photo_name['.$value->id.']" value="'.$value->image.'"/>
                </div></div>';

              }else{
                if($value->image != '')
                  $img = '<img class="bus_image_preview" data-toggle="modal" data-target="#myModal_'.$value->id.'" src='.base_url().'public/uploads/bus/'.$value->image.'>';
                
                echo '<div><div class="upload_image col-md-6"><span>'.$this->lang->line("select").' '.$this->lang->line("file").'...</span><input type="file" name="bus_photo['.$value->id.']" onchange="show_file_name(this);" /></div><div class="add_btn col-md-3 text-right" id=""><a class="md-fab md-primary md-fab-sm md-button md-default-theme bus_remove_more"><span class="imd imd-remove"></span></a></div><div class="clearfix"><input type="hidden" id="image_form_submit" name="image_form_submit" value="1"/>
                '.$img.'
                <input type="hidden" id="photo_name" name="photo_name['.$value->id.']" value="'.$value->image.'"/>
                </div></div>';
              }
              ?>

              <div class="modal fade" id="myModal_<?php echo $value->id ;?>" role="dialog">
                <div class="modal-dialog"> 

                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title"><?php echo end(explode("_",$value->image)); ?></h4>
                    </div>
                    <div class="modal-body">
                      <p>
                       <?php

                       echo '<img style="width:100%;height:60%;" src='.base_url().'public/uploads/bus/'.$value->image.'>';

                       ?>
                     </div>
                     <div class="modal-footer"> 
                      <button type="button" class="btn btn-default" data-dismiss="modal">CANCEL</button>
                    </div>
                  </div>
                </div>
              </div>
              <?php 

            } ?>
          </div>


          <div class="col-md-12" align="right">
           <input type="hidden" id="id" name="id" value="<?php echo $single_bus_detail['id']; ?>"/>
           <input id="update" type="submit" value="Update" ui-wave class="btn btn-primary md-button">
         </div>

       </form>

     </div>                        
   </div>
 </div>
</section>
</div>
</div>
</div>



<!--Start model for insurance-->
<div class="modal fade" id="registration_Modal" role="dialog">
  <?php
  $bus_registration=$single_bus_detail['registration_image'];
  echo  call_model($bus_registration); ?>
</div>
<!--End model for registration-->

<!--Start model for insurance-->
<div class="modal fade" id="permit_Modal" role="dialog">
  <?php
  $bus_permit=$single_bus_detail['permit_image'];
  echo  call_model($bus_permit); ?>
</div>
<!--End model for registration-->

<!--Start model for insurance-->
<div class="modal fade" id="insurance_Modal" role="dialog">
  <?php
  $bus_insurance=$single_bus_detail['insurance_image'];
  echo  call_model($bus_insurance); ?>
</div>
<!--End model for registration-->

<?php

function call_model($bus){ ?>
<div class="modal-dialog"> 

  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title"><?php echo end(explode("_",$bus)) ; ?></h4>
    </div>
    <div class="modal-body">
      <p>
       <?php

       echo '<img style="width:100%;height:60%;" src='.base_url().'public/uploads/bus/'.$bus.'>';

       ?>
     </div>
     <div class="modal-footer"> 
      <button type="button" class="btn btn-default" data-dismiss="modal">CANCEL</button>
    </div>
  </div>
</div>
<?php
}

?>




<script>



$('#bus_edit_form').submit(function(){
  // AJAX Code To Submit Form.
 

  var file_data = $('#bus_document').prop('files')[0];   
  var form_data = new FormData($('#bus_edit_form')[0]);                  
  form_data.append('bus_document', file_data);


  var bus_name = $("#bus_name").val();
  var bus_number = $("#bus_number").val();
  var chassis_number = $("#chassis_number").val();
  var bus_type = $("#bus_type").val();
  var bus_capacity= $("#bus_capacity").val();
  var bus_model= $("#bus_model").val();
  var bus_document= $("#bus_document").val();
  var bus_registration= $("#bus_registration").val();
  var bus_permit= $("#bus_permit").val();
  var bus_insurance= $("#bus_insurance").val();
  var id= $("#id").val();
      // Returns successful data submission message when the entered information is stored in database.
      if(bus_name!='' && bus_number!='' && chassis_number!='' && bus_type!=''  && bus_model!=''){

        show_processing();

        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>index.php/bus/update_bus",
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data, 

                success: function(result){

                  $("#output_msg").show();
                  $("#output_msg").find("div").html(result);
                  if(result.indexOf('success')>=0){
                   alert_msg_box(result,'b');
                 }
               }
             });


      }
      return false;
    });


$(function()
{
  $('#bus_document').on('change',function()
  {
       // output raw value of file input
       show_file_name(this);

  }); 
  $('#bus_registration').on('change',function()
    {
       // output raw value of file input
       show_file_name(this);
     
    });
    $('#bus_permit').on('change',function()
    {
       // output raw value of file input
       show_file_name(this);
     
    });
    $('#bus_insurance').on('change',function()
    {
       // output raw value of file input
       show_file_name(this);
     
    });


  $(".bus_remove_more").click(function()
  {
    $(this).parent().parent().remove();
  });

$('#bus_type').on('change',function(){
      var capacity=$('option:selected', this).attr('capacity');
      $("#bus_capacity").val(capacity);

    });

});


</script>


<script type="text/javascript" src="<?php echo base_url(); ?>public/parsley/parsley.min.js"></script>

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
    control.makeTransliteratable(['bus_name']);
  }
  google.setOnLoadCallback(onLoad);
</script>