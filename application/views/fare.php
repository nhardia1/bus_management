<div class="page page-table">
    <div class="row ui-section">

        <div class="col-md-12 breadcum_container">
            <section class="panel panel-default breadcum_section">

              <h3 class="section-header"><?php echo $this->lang->line("fare")." ".$this->lang->line("management");?></h3>
            
              <a href="<?php echo base_url();?>index.php/home">
                <i class="imd imd-home"></i> <?php echo $this->lang->line("dashboard"); ?>
              </a>
              
              <i class="imd imd-keyboard-arrow-right"></i>
              
              <a href="<?php echo base_url();?>index.php/route/routes_fare_details">
                <i class="imd imd-attach-money"></i> <?php echo $this->lang->line("fare"); ?>
              </a>

            </section>
        </div>

        <div class="col-md-12">
            <section class="panel panel-default table-dynamic">


<div class="add_btn">
      <a type=button style="float: right;" data-toggle="tooltip" title="<?php echo $this->lang->line("add_fare");?>" onClick="location='<?php echo base_url()?>index.php/route/add_fare'" class="md-fab blue_bg md-button md-default-theme"  aria-label=""><span class="imd imd-add"></span></a>
</div>

      <table id="example" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                
                <!--th >#</th-->
                <th ><?php echo $this->lang->line("route_name");?></th>
                <th ><?php echo $this->lang->line("cust_farefrom");?></th>
                <th ><?php echo $this->lang->line("cust_fareto");?></th>
                <th ><?php echo $this->lang->line("fare");?></th>
                <th ><?php echo $this->lang->line("action");?></th>
            </tr>
        </thead>
 
        <tfoot>
            <tr>
                
                <!--th >#</th-->
               <th ><?php echo $this->lang->line("route_name");?></th>
                <th ><?php echo $this->lang->line("cust_farefrom");?></th>
                <th ><?php echo $this->lang->line("cust_fareto");?></th>
                <th ><?php echo $this->lang->line("fare");?></th>
                <th ><?php echo $this->lang->line("action");?></th>
            </tr>
        </tfoot>
        <?php $sno = 0;
        foreach ($routes_fare_details as $key => $obj) 
        { 
          $sno++;
          ?>
          <tr>
                
                <!--td ><?php echo $sno ; ?></td-->
                <td ><?php echo ucfirst($obj['name']);?></td>
                <td ><?php echo $obj['source_city'] ; ?></td>
                <td ><?php echo $obj['destination_city'] ; ?></td>
                <td ><?php echo $obj['fare']; ?></td>
                <td >
                 
                 <a class="edit_btn" data-toggle="tooltip" title="<?php echo $this->lang->line("edit_fare");?>" href="<?php echo base_url()?>index.php/route/add_fare/<?php echo $obj['id']; ?>"><span class='imd imd-mode-edit'></span></a> 

                 <span class="space"></span>
                
                 <button ui-wave class="delete_btn" data-toggle="modal" title="<?php echo $this->lang->line("delete_fare");?>" data-target="#myModal<?php echo $obj['id'];?>" ><span class="imd imd-delete"></span></button>

               </td>
            </tr>
             <!-- Modal -->
            <div class="modal fade" id="myModal<?php echo $obj['id'];?>" role="dialog">
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
                     <a href="<?php echo base_url()?>index.php/route/delete_route_fare/id/<?php echo $obj['id']; ?>"><?php echo $this->lang->line("ok"); ?></a>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line("cencel"); ?></button>
                  </div>
                </div>
                
              </div>
            </div>
          <?php
        }
         ?>

    </table>
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