<link rel="stylesheet" href="<?php echo base_url();?>theme/styles/jquery-ui.css" type="text/css" media="screen"/>

<div class="page page-table">

    <div class="row ui-section">

       <div class="col-md-12 breadcum_container">
            <section class="panel panel-default breadcum_section">

              <h3 class="section-header"><?php echo $this->lang->line("seat").' '.$this->lang->line("configuration") ?></h3>
            
              <a href="<?php echo base_url(); ?>index.php/home">
                <i class="imd imd-home"></i> <?php echo $this->lang->line("dashboard"); ?>
              </a>
              
              <i class="imd imd-keyboard-arrow-right"></i>
              
              <a href="<?php echo base_url(); ?>index.php/seat">
                <i class="imd imd-account-child"></i> <?php echo $this->lang->line("seat").' '.$this->lang->line("configuration"); ?>
              </a>

            </section>
    </div>

        <div class="col-md-12">
            <section class="panel panel-default table-dynamic">
          
          <div class="add_btn">
                <a type=button style="float: right;" data-toggle="tooltip" title="<?php echo 'Add Template'; ?>" onClick="location='<?php echo base_url()?>index.php/seat/add_template'" class="md-fab blue_bg md-button md-default-theme"  aria-label=""><span class="imd imd-add"></span></a>
          </div>


      <?php 
      echo $this->table->generate();

       ?>

      </section>
      </div>
      </div>
      </div>
      