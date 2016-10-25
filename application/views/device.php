<link rel="stylesheet" href="<?php echo base_url();?>theme/styles/jquery-ui.css" type="text/css" media="screen"/>


<div class="page">


    <div class="row ui-section">

          <div class="col-md-12 breadcum_container">
            <section class="panel panel-default breadcum_section">

              <h3 class="section-header"><?php echo $this->lang->line("device").' '.$this->lang->line("management"); ?></h3>
            
              <a href="<?php echo base_url(); ?>index.php/home">
                <i class="imd imd-home"></i> <?php echo $this->lang->line("dashboard") ?> 
              </a>
              
              <i class="imd imd-keyboard-arrow-right"></i>
              
              <a href="<?php echo base_url(); ?>index.php/device">
                <i class="imd imd-phone-iphone"></i> <?php echo $this->lang->line("device") ?>
              </a>

            </section>
    </div>

        <div class="col-md-12">
            <section class="panel panel-default table-dynamic">


            <?php echo $this->table->generate(); ?>
           </section>
         </div>
      </div> 
    </div>       
