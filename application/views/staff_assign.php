<link rel="stylesheet" href="<?php echo base_url();?>theme/styles/jquery-ui.css" type="text/css" media="screen"/> 


<div class="page page-table">
  <div class="row ui-section">
    
    <div class="col-md-12 breadcum_container">
            <section class="panel panel-default breadcum_section">

              <h3 class="section-header"><?php echo $this->lang->line("staff")." ".$this->lang->line("assign")." ".$this->lang->line("management");?></h3>
            
              <a href="<?php echo base_url();?>index.php/home">
                <i class="imd imd-home"></i> <?php echo $this->lang->line("dashboard");?> 
              </a>
              
              <i class="imd imd-keyboard-arrow-right"></i>
              
              <a href="<?php echo base_url();?>index.php/staff_assign">
                <i class="imd imd-account-child"></i> <?php echo $this->lang->line("staff")." ".$this->lang->line("assign");?>
              </a>

            </section>
        </div>
    

    <div class="col-md-12">
      <section class="panel panel-default table-dynamic">
        <div class="add_btn">
              <a type=button style="float: right;" href="<?php echo base_url();?>index.php/staff_assign/add" class="md-fab blue_bg md-button md-default-theme"  aria-label="" data-toggle="tooltip" title="<?php echo $this->lang->line("bus_assign_staff_to_bus");?>"><span class="imd imd-add"></span></a>
        </div>

         <div class="row">
  <div class="col-md-12">
    <div class="ui-tab-container ui-tab-horizontal">
      <div justified="false" class="ui-tab ng-isolate-scope">               
        
        <ul class="nav nav-tabs">
          <li id="tab1">
            <a class="ng-binding" href="<?php echo base_url()?>index.php/bus">
                <i class="imd imd-directions-bus"></i>
                <span class="ng-scope"><?php echo $this->lang->line("buses");?></span>
            </a>
            <span class="plus_link" data-toggle="tooltip" title="<?php echo $this->lang->line('bus_add_bus'); ?>" style="background-color:#F44336;">
              <a href="<?php echo base_url()?>index.php/bus/add_bus">+</a>
            </span>
          </li>
          

          <li id="tab2">
            <a class="ng-binding" href="<?php echo base_url()?>index.php/device_assign">
              <i class="imd imd-tap-and-play"></i>
              <span class="ng-scope"><?php echo $this->lang->line("device")." ".$this->lang->line("assigned");?></span>
            </a>
             <span class="plus_link" data-toggle="tooltip" title="<?php echo $this->lang->line("bus_assign_device_to_bus") ;?>">
              <a href="<?php echo base_url()?>index.php/device_assign/add">+</a>
            </span>
          </li>
          

          <li id="tab3" class="active">
            <a class="ng-binding" href="<?php echo base_url()?>index.php/staff_assign">
                 <i class="imd imd-account-child"></i>
                 <span class="ng-scope"><?php echo $this->lang->line("staff")." ".$this->lang->line("assigned");?></span> 
            </a>
            <span class="plus_link" data-toggle="tooltip" title="<?php echo $this->lang->line("bus_assign_staff_to_bus") ;?>" style="background-color:#000000;">
              <a href="<?php echo base_url()?>index.php/staff_assign/add">+</a>
            </span>
          </li>
        
        </ul>

        <div class="tab-content">
          <div id="contenttab1" class="tab-pane"></div>
          <div id="contenttab2" class="tab-pane"></div>
          <div id="contenttab3" class="tab-pane active"><?php //echo $this->table->generate(); ?>
            <table id="example" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                
                <!--th >#</th-->
                <th ><?php echo $this->lang->line("bus_name");?></th>
                <th ><?php echo $this->lang->line("driver_name");?></th>
                <th ><?php echo $this->lang->line("conductor_name");?></th>
                <th ><?php echo $this->lang->line("operator_name");?></th>
                <th ><?php echo $this->lang->line("cust_satffbookeddate");?></th>
                <th ><?php echo $this->lang->line("action"); ?></th>
            </tr>
        </thead>
 
        <tfoot>
            <tr>
                
                <!--th >#</th-->
                <th ><?php echo $this->lang->line("bus_name");?></th>
                <th ><?php echo $this->lang->line("driver_name");?></th>
                <th ><?php echo $this->lang->line("conductor_name");?></th>
                <th ><?php echo $this->lang->line("operator_name");?></th>
                <th ><?php echo $this->lang->line("cust_satffbookeddate");?></th>
                <th ><?php echo $this->lang->line("action"); ?></th>
            </tr>
        </tfoot>
        <?php $sno = 0;
        foreach ($staff_assign_list as $key => $obj) 
        { 
            $sno++;
            
            $id = $obj->id;
            $bus_id = $obj->bus_id;
            $driver_id = $obj->driver;
            $conductor_id = $obj->conductor;
            $operator_id=$obj->operator;
            $helper_id = $obj->helper;
            $other_id = $obj->other;
          ?>
          <tr>
                
                <!--td ><?php echo $sno; ?></td-->
                <td ><?php echo ucfirst($obj->name); ?></td>
                <td ><?php echo ucfirst($obj->driver_name); ?></td>
                <td ><?php echo ucfirst($obj->conductor_name); ?></td>
                <td ><?php echo ucfirst($obj->operator_name); ?></td>
                <td ><?php echo ucfirst($obj->fordate); ?></td>

                <td >
                 

                 <a class="edit_btn" data-toggle="tooltip" title="<?php echo $this->lang->line("route_edit_route");?>" href="<?php echo base_url()?>index.php/staff_assign/edit/bid/<?php echo $bus_id; ?>/drivid/<?php echo $driver_id ; ?>/conducid/<?php echo $conductor_id; ?>/opepid/<?php echo  $operator_id;?>"><span class='imd imd-mode-edit'></span></a>
                 <span class="space"></span>
                
                 <button ui-wave class="delete_btn" data-toggle="modal" title="<?php echo $this->lang->line("route_delete_route");?>" data-target="#myModal<?php echo $id;?>" ><span class="imd imd-delete"></span></button>

               </td>
            </tr>
                  <!-- Modal -->
            <div class="modal fade" id="myModal<?php echo $id;?>" role="dialog">
              <div class="modal-dialog">
              
                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo $this->lang->line("confirmation"); ?></h4>
                  </div>
                  <div class="modal-body">
                   <p><?php echo $this->lang->line("delete_confirm"); ?></p>
                  </div>
                  <div class="modal-footer">
                     <a href="<?php echo base_url()?>index.php/staff_assign/delete/id/<?php echo $obj->id; ?>"> <?php echo $this->lang->line("ok"); ?></a>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line("cencel"); ?></button>
                  </div>
                </div>
                
              </div>
            </div>
          <?php
        }
         ?>
    </table>

          </div>
        </div>
      </div>
    </div>      
  </div>
</div>   
      </section>
    </div>
  </div>
</div>
 <script>
    $(document).ready(function() {
    $('#example').on( 'init.dt', function () 
        {
            $("#example_wrapper").find("#example_filter").find("input[type='text']").attr("id","csbox");
            $("#example_wrapper").find("#example_filter").find("input[type='text']").attr("name","हिन्दी के लिए पहले अंग्रेजी में शब्द type करे और उसके बाद SPACEBAR key दबाएँ.");  

            $("#csbox").mouseover(function(e)
            {
                var x = e.pageX - 100;
                var y = e.pageY - 80;

                $(".edit_tooltip").show();
                $(".edit_tooltip").css({left:x+"px", top:y+"px"});
                $(".edit_tooltip").find("p").html($(this).attr("name"));
            });
            $("#csbox").mouseout(function(e)
            {                         
                $(".edit_tooltip").hide();                          
            });

        }).dataTable();   
    } );
    </script>