<?php 
$staff_type=staff_type();

$get_seat_type=get_seat_type();
$get_coach_type=get_coach_type();
$get_seat_allocation=get_seat_allocation();
?>
<style>
div.seatCharts-container {
  /*min-width: 700px;*/
}
div.seatCharts-cell {

  height: 16px;
  width: 16px;
  margin: 3px;
  float: left;
  text-align: center;
  outline: none;
  font-size: 13px;
  line-height:16px;
  color: blue;

}
div.seatCharts-seat {
  background-color: green;
  color: white;
  -webkit-border-radius: 5px;
  -moz-border-radius: 5px;
  border-radius: 5px;
  cursor: default;
}
div.seatCharts-seat:focus {
  border: none;
}
/*
.seatCharts-seat:focus {
  outline: none;
}
*/

div.seatCharts-space {
  background-color: white;
}
div.seatCharts-row {
  height: 50px;
}

div.seatCharts-row:after {
  clear: both;
}

div.seatCharts-seat.selected {
  background-color: aqua;
}

div.seatCharts-seat.focused {
  background-color: #6db131;
}

div.seatCharts-seat.available {
  background-color: green;
}

div.seatCharts-seat.unavailable {
  background-color: red;
  cursor: not-allowed;
}

ul.seatCharts-legendList {
  list-style: none;
}
li.seatCharts-legendItem {
  margin-top: 10px;
  line-height: 2;
}
.front-indicator {
  width: 145px;
  margin: 5px 32px 15px 32px;
  background-color: #f6f6f6;  
  color: #adadad;
  text-align: center;
  padding: 3px;
  border-radius: 5px;
}
div.seatCharts-cell {
  color: #182C4E;
  height: 25px;
  width: 25px;
  line-height: 25px;
  
}
div.seatCharts-seat {
  color: #FFFFFF;
  cursor: pointer;  
}
div.seatCharts-row {
  height: 35px;
}
div.seatCharts-seat.available {
  background-color: #B9DEA0;

}
div.seatCharts-seat.available.first-class {
/*  background: url(vip.png); */
  background-color: #3a78c3;
}
div.seatCharts-seat.focused {
  background-color: #76B474;
}
div.seatCharts-seat.selected {
  background-color: #E6CAC4;
}
div.seatCharts-seat.unavailable {
  background-color: #472B34;
}
div.seatCharts-container {
  width: 200px;
  padding: 20px;
  float: left;
}
div.seatCharts-legend {
  padding-left: 0px;
  position: absolute;
  bottom: 16px;
}
ul.seatCharts-legendList {
  padding-left: 0px;
}
span.seatCharts-legendDescription {
  margin-left: 5px;
  line-height: 30px;
}
.checkout-button {
  display: block;
  margin: 10px 0;
  font-size: 14px;
}
#selected-seats {
  max-height: 90px;
  overflow-y: scroll;
  overflow-x: none;
  width: 170px;
}
</style>
<div class="page">



  <div class="row ui-section">
<div class="col-md-12 breadcum_container">
            <section class="panel panel-default breadcum_section">

              <h3 class="section-header">
                <?php 
                if(isset($single_seat_detail)){
               
                echo $this->lang->line("edit").' '.$this->lang->line("seat_template"); //echo 'Edit Staff'; 
               
              }else {
                
                echo $this->lang->line("add").' '.$this->lang->line("seat_template"); //echo 'Add Staff';
               
              } ?>

                </h3>
            
              <a href="<?php echo base_url(); ?>index.php/home">
                <i class="imd imd-home"></i> <?php echo $this->lang->line("dashboard"); ?> 
              </a>
              
              <i class="imd imd-keyboard-arrow-right"></i>
              
              <a href="<?php echo base_url(); ?>index.php/seat">
                <i class="imd imd-account-child"></i> <?php echo $this->lang->line("seat").' '.$this->lang->line("configuration"); ?>
              </a>

              <i class="imd imd-keyboard-arrow-right"></i>
              <?php 
              if(isset($single_seat_detail)){
                echo  "<a href='".base_url()."index.php/seat/add_template/id/".$id."'>";
                echo $this->lang->line("edit");
                echo "</a>";
              }else {
                echo "<a href='".base_url()."index.php/seat/add_template'>";
                echo $this->lang->line("add");
                echo "</a>";
              }
               ?> 
       
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

            <form name="seatForm" id="seatForm" method='post' enctype='multipart/form-data' data-parsley-validate>

              <div class="col-md-6">

                  <div class="ui-input-group">

                     <input type="text" class="form-control" name='template_name' id="template_name" 
                     <?php if(isset($single_seat_detail)){  ?>
                     value="<?php echo $single_seat_detail['template_name']; ?>"
                     <?php } ?>
                     required>

                     <label for="staff_address"><?php echo $this->lang->line("seat_template"); ?></label>

                  </div>

                </div>

               <div class="col-md-6 label_dropdown">
                  
                  <label for="staff-type"><?php echo $this->lang->line("seat_type"); ?></label><br/>

                  <span class="ui-select">

                    <select id="seat_type" name='seat_type' required>
                         <?php 
                         foreach ($get_seat_type as $value) {?>
                                <option value="<?php echo $value->id;?>" <?php if($single_seat_detail['seat_type_name']==$value->id){ echo 'selected="selected"';}?>><?php echo $value->value;?></option>
                              <?php
                          } ?>
                      </select>
                    </span>
                  </div>
                   <div class="col-md-6 label_dropdown">
                  <label for="staff-type"><?php echo $this->lang->line("seat_cocach_type"); ?></label><br/>
                  <span class="ui-select">
                    <select id="coach_type" name='coach_type' required>
                        <?php 
                         foreach ($get_coach_type as  $value) {?>
                                <option value="<?php echo $value->id;?>" <?php if($single_seat_detail['seat_cocach_type']==$value->id){ echo 'selected="selected"';}?>><?php echo $value->value;?></option>
                              <?php
                          } ?>
                      </select>
                    </span>

                  </div>

                <div class="clearfix"></div>

                <div class="col-md-6">

                  <div class="ui-input-group">

                     <input type="text" class="form-control" name='seat_capacity' id="seat_capacity" 
                     <?php if(isset($single_seat_detail)){  ?>
                     value="<?php echo $single_seat_detail['seat_capacity']; ?>"
                     <?php } ?>
                     required>

                     <label for="staff_address"><?php echo $this->lang->line("seat_capacity"); ?></label>

                  </div>

                </div>

                 <div class="col-md-6 label_dropdown">
                  
                  <label for="staff-type"><?php echo $this->lang->line("seat_allocation"); ?></label><br/>

                  <span class="ui-select">

                    <select id="seat_allocation" name='seat_allocation' required>
                           <?php 
                         foreach ($get_seat_allocation as $value) {?>
                                <option value="<?php echo $value->id;?>" <?php if($single_seat_detail['seat_allocation']==$value->id){ echo 'selected="selected"';}?>><?php echo $value->value;?></option>
                              <?php
                          } ?>
                      </select>

                    </span>

                  </div>
                 <div class="col-md-12" align="left">
                  
                        <div id="seat-map">
                          <div class="front-indicator">Seats</div>
                        </div>

                  </div>
                   <div class="clearfix"></div>
                  <div class="col-md-12" align="right">

                    <?php if(isset($single_seat_detail)){  ?>

                    <input  type="hidden" id="submit_value" name="submit_value" value="update"/>

                    <input  type="hidden" id="id" name="id" value="<?php if(isset($single_seat_detail)){ echo $single_seat_detail['id']; } ?>"/>
                    
                    <input id="update" type="submit" value="Update" ui-wave class="btn btn-primary md-button">
                    <?php
                  } else { ?>

                  <input  type="hidden" id="submit_value" name="submit_value" value="insert"/>

                  <input id="submit" type="submit" value="Save" ui-wave class="btn btn-primary md-button">
                  
                  <?php } ?>

                </div>

              </form>

            </div>      

          </div>

        </div>

      </section>

    </div>

  </div>
  
</div>



<script src="<?php echo base_url();?>assets/js/jquery.seat-charts.js"></script>
<script>

var firstSeatLabel = 1;
$(document).ready(function(){

 $counter = $('#counter'),
          $total = $('#total'),
          sc = $('#seat-map').seatCharts({
          map: [
            'cc_cc',
            'cc_cc',
            'cc_cc',
            'cc_cc',
            'cc_cc',
            'cc_cc',
            'cc_cc',
            'cc_cc',
            'cc_cc',
            'cc_cc',
            'cc_cc',
            'ccccc',
          ],
          seats: {
            f: {
              price   : 100,
              classes : 'first-class', //your custom CSS class
              category: 'First Class'
            },
            e: {
              price   : 40,
              classes : 'economy-class', //your custom CSS class
              category: 'Economy Class'
            }         
          
          },
          naming : {
            top : false,
            left: false,
            getLabel : function (character, row, column) {
              return firstSeatLabel++;
            },
          },
          legend : {
            node : $('#legend'),
              items : [
              [ 'f', 'available',   'First Class' ],
              [ 'e', 'available',   'Economy Class'],
              [ 'f', 'unavailable', 'Already Booked']
              ]         
          },
        });

  $('#staff_type').on('change', function() {
    if ( this.value == '3')    
    {
      $("#type").show();
    }
    else
    {
      $("#type").hide();
      $("#staff_license_number").val('');
      $("#staff_expiry_date").val('');
      $(".bus_image_preview").remove();
      $("#fileUpload").val('');
      
      //$("#image_form_submit").val(0);
    }
    if(this.value == '2'){
      $("#agency_dropdown").show();
    }else{
      $("#agency_dropdown").hide();
    }
  });
});



$('#seatForm').submit(function(){
            var form_data = new FormData($('#seatForm')[0]);                  
            var updateUrl ="<?php echo base_url(); ?>index.php/seat/update_seat";
            var insertUrl ="<?php echo base_url(); ?>index.php/seat/insert_seat";


            var seat_type = $("#seat_type").val();
            var coach_type = $("#coach_type").val();
            var seat_capacity = $("#seat_capacity").val();
            var seat_allocation = $("#seat_allocation").val();
            var id = $("#id").val();
            var submit_value = $("#submit_value").val();
            if(seat_type!='' && coach_type!='' && seat_capacity!='' && seat_allocation!='')
            {
                if(submit_value =='update'){
                  url=updateUrl;
                }else if(submit_value =='insert'){
                  url=insertUrl;
                }
                console.log(url);
                $.ajax({
                  type: "POST",
                  url: url,
                  dataType: 'text',  // what to expect back from the PHP script, if anything
                  cache: false,
                  contentType: false,
                  processData: false,
                  data: form_data, 
                success: function(result){

                  $("#output_msg").show();
                  $("#output_msg").find("div").html(result);
                  if(result.indexOf('success')>=0){                    
                      alert_msg_box(result,'seat');                      
                    
                  }
                }
              });
             
            }
            return false;
          });
</script>

<!-- calendar -->
<script type="text/javascript" src="<?php echo base_url(); ?>public/parsley/parsley.min.js"></script>

<link rel="stylesheet" href="<?php echo base_url();?>theme/styles/jquery-ui.css">
<script>
  // initialize date picker 
var dt = new Date().getFullYear() + 30;
$( "#staff_expiry_date" ).datepicker({
        dateFormat: 'dd-mm-yy',
        changeMonth: SHOW_MONTH,
        changeYear: SHOW_YEAR,
        numberOfMonths: 1,
        yearRange: '2000:'+ dt
       
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
    control.makeTransliteratable(['staff_name','staff_address']);
  }
</script>
<?php 
if($this->session->userdata('message')!='english'){?>
<script>
 google.setOnLoadCallback(onLoad);
</script>
<?php }
?>