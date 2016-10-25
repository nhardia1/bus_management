<link rel="stylesheet" href="<?php echo base_url();?>theme/styles/jquery-ui.css" type="text/css" media="screen"/> 

<div class="page">
    <div class="row ui-section">

          <div class="col-md-12 breadcum_container">
            <section class="panel panel-default breadcum_section">

              <h3 class="section-header"><?php echo $this->lang->line("bus").' '.$this->lang->line("management"); ?></h3>
            
              <a href="<?php echo base_url(); ?>index.php/home">
                <i class="imd imd-home"></i> <?php echo $this->lang->line("dashboard") ?>
              </a>
              
              <i class="imd imd-keyboard-arrow-right"></i>
              
              <a href="<?php echo base_url(); ?>index.php/bus">
                <i class="imd imd-directions-bus"></i> <?php echo $this->lang->line("bus") ?>
              </a>

            </section>
  </div>

        <div class="col-md-12">
            <section class="panel panel-default table-dynamic">


<div class="add_btn">
      <a type=button style="float: right;" href="<?php echo base_url()?>index.php/bus/add_bus" class="md-fab blue_bg md-button md-default-theme"  aria-label="" data-toggle="tooltip" title="<?php echo $this->lang->line('bus_add_bus'); ?>"><span class="imd imd-add"></span></a>
</div>

  

<div class="row">
  <div class="col-md-12">
    <div class="ui-tab-container ui-tab-horizontal">
      <div justified="false" class="ui-tab ng-isolate-scope">               
        
        <ul class="nav nav-tabs">
          
          <li id="tab1" class="active">
            <a class="ng-binding" href="<?php echo base_url()?>index.php/bus">
              <i class="imd imd-directions-bus"></i>
              <span class="ng-scope"><?php echo $this->lang->line("buses") ?></span>
            </a>
            
            <span class="plus_link" data-toggle="tooltip" title="<?php echo $this->lang->line('bus_add_bus'); ?>" style="background-color:#F44336;">
              <a href="<?php echo base_url()?>index.php/bus/add_bus">+</a>
            </span>
          
          </li>

          <li id="tab2">
            <a class="ng-binding" href="<?php echo base_url()?>index.php/device_assign">
              <i class="imd imd-tap-and-play"></i>
              <span class="ng-scope"><?php echo $this->lang->line("device").' '.$this->lang->line("assigned"); ?></span>              
            </a>
            
            <span class="plus_link" data-toggle="tooltip" title="<?php echo $this->lang->line("bus_assign_device_to_bus") ;?>">
              <a href="<?php echo base_url()?>index.php/device_assign/add">+</a>
            </span>
          </li>
          
          
          <li id="tab3">
            <a class="ng-binding" href="<?php echo base_url()?>index.php/staff_assign">
              <i class="imd imd-account-child"></i>
              <span class="ng-scope"><?php echo $this->lang->line("staff").' '.$this->lang->line("assigned"); ?></span> 
            </a>

            <span class="plus_link" data-toggle="tooltip" title="<?php echo $this->lang->line("bus_assign_staff_to_bus") ;?>" style="background-color:#000000;">
              <a href="<?php echo base_url()?>index.php/staff_assign/add">+</a>
            </span>
          </li>
        </ul>

        <div class="tab-content">
          <div id="contenttab1" class="tab-pane active"><?php echo $this->table->generate(); ?></div>
          <div id="contenttab2" class="tab-pane">Trip 2</div>
          <div id="contenttab3" class="tab-pane">Trip 3</div>
        </div>
      </div>
    </div>      
  </div>
</div>    


</section>
    </div>
  </div>
</div>
