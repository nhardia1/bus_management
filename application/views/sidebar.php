<aside class="nav-container ng-scope nav-fixed nav-vertical bg-dark" id="nav-container" style="background-color:#000;opacity:.7;">  

<div class="nav-wrapper">
  <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 100%;">

  <ul data-highlight-active="" data-collapse-nav="" data-slim-scroll="" class="nav" id="nav" style="height: 100%; width: auto; overflow-y: auto; overflow-x: hidden;">
       <li class="nav-title">
            <span class="ng-scope"><?php echo $this->lang->line("navigation"); ?></span>
        </li>
        
       
        <li class="ui-wave active">
            <span class="ink wave-animate"></span>            
            <a href="<?php echo base_url();?>index.php/home"> 
                <i class="imd imd-home"></i>
                <span class="ng-scope"><?php echo $this->lang->line("dashboard"); ?></span> 
            </a>
        </li>

        
        <li class="ui-wave">
            <span class="ink wave-animate"></span>            
            <a href="<?php echo base_url();?>index.php/city"> 
                <i class="imd imd-location-city"></i>
                <span class="ng-scope"><?php echo $this->lang->line("city").' '.$this->lang->line("management"); ?></span> 
            </a>
        </li>
   
        
        <li class="ui-wave">
            <span class="ink wave-animate"></span>            
            <a href="<?php echo base_url();?>index.php/staff"> 
                <i class="imd imd-account-child"></i>
                <span class="ng-scope"><?php echo $this->lang->line("staff").' '.$this->lang->line("management"); ?></span> 
            </a>
        </li>


        <li class="ui-wave">
            <span class="ink wave-animate"></span>            
            <a href="<?php echo base_url();?>index.php/bus"> 
                <i class="imd imd-directions-bus"></i>
                <span class="ng-scope"><?php echo $this->lang->line("bus").' '.$this->lang->line("management"); ?></span> 
            </a>
        </li>

         <li class="ui-wave">
            <span class="ink wave-animate"></span>            
            <a href="<?php echo base_url();?>index.php/seat"> 
                <i class="imd imd-directions-bus"></i>
                <span class="ng-scope"><?php echo $this->lang->line("seat").' '.$this->lang->line("management"); ?></span> 
            </a>
        </li>

                
        <li class="ui-wave">
            <span class="ink wave-animate"></span>            
            <a href="<?php echo base_url();?>index.php/route/routes_details"> 
                <i class="imd imd-map"></i>
                <span class="ng-scope"><?php echo $this->lang->line("route").' '.$this->lang->line("management"); ?></span> 
            </a>
        </li>


        <li class="ui-wave">
            <span class="ink wave-animate"></span>            
            <a href="<?php echo base_url();?>index.php/route/routes_fare_details"> 
                <i class="imd imd-attach-money"></i>
                <span class="ng-scope"><?php echo $this->lang->line("fare").' '.$this->lang->line("management"); ?></span> 
            </a>
        </li>


        <!--side_submenu-->
        <li class="ui-wave">
            <span class="ink wave-animate"></span>            
            <a href="<?php echo base_url();?>index.php/route/bus_route_list"> 
                <i class="imd imd-directions"></i>
                <span class="ng-scope"><?php echo $this->lang->line("cust_assignbusandsettime"); ?></span> 
            </a>
        </li>

        
        <li class="ui-wave">
            <span class="ink wave-animate"></span>            
            <a href="<?php echo base_url();?>index.php/device"> 
                <i class="imd imd-phone-iphone"></i>
                <span class="ng-scope"><?php echo $this->lang->line("device").' '.$this->lang->line("management"); ?></span> 
            </a>
        </li>
 
        <li class="ui-wave">
            <span class="ink wave-animate"></span>            
            <a href="<?php echo base_url();?>index.php/tracking"> 
                <i class="imd imd-location-on"></i>
                <span class="ng-scope"><?php echo $this->lang->line("tracking"); ?></span> 
            </a>
        </li>


        <li class="ui-wave">
            <span class="ink wave-animate"></span>            
            <a href="<?php echo base_url();?>index.php/reports"> 
                <i class="imd imd-list"></i>
                <span class="ng-scope"><?php echo $this->lang->line("report"); ?></span> 
            </a>
        </li>


        <li class="ui-wave">
            <span class="ink wave-animate"></span>            
            <a href="<?php echo base_url();?>index.php/home/change_pass"> 
               <i class="imd imd-settings"></i>
                <span class="ng-scope"><?php echo $this->lang->line("cust_changepassword"); ?></span> 
            </a>
        </li>

        <li class="ui-wave">
            <span class="ink wave-animate" id="help_btn"></span>            
            <a href="#" data-toggle="modal" data-target="#myModalHelp"> 
                <i class="imd imd-help"></i>
                <span class="ng-scope"><?php echo $this->lang->line("help"); ?></span> 
            </a>
        </li>

        <li class="ui-wave">
            <span class="ink wave-animate"></span>            
            <a href="<?php echo base_url();?>index.php/login/logout"> 
                <i class="imd imd-lock"></i>
                <span class="ng-scope"><?php echo $this->lang->line("logout"); ?></span> 
            </a>
        </li>

        
        
    </ul>
  </div>
</div>
</aside>

<div class="quick_links_menu">
    <table width="100%">        
        <tr>
            <td width="100%" colspan="4" class="headrow"><?php echo $this->lang->line("quick").' '.$this->lang->line("add").' '.$this->lang->line("links"); ?></td>           
        </tr>

        
        <tr align="center" class="contentrow">
            
            <td width="25%" class="contenttd"><a href="<?php echo base_url();?>index.php/bus/add_bus" title="<?php echo $this->lang->line("cust_qa1");?>">            
            <div class="tl-icon btn-icon-round btn-icon btn-icon-thin btn-danger"><i class="imd imd-directions-bus"></i></div>
            </a></td>         
            
            
            <td width="25%" class="contenttd"><a href="<?php echo base_url();?>index.php/city" title="<?php echo $this->lang->line("cust_qa2");?>">          
            <div class="tl-icon btn-icon-round btn-icon btn-icon-thin btn-primary"><i class="imd imd-location-city"></i></div>
            </a></td>
            
                       
            <td width="25%" class="contenttd"><a href="<?php echo base_url();?>index.php/route/add" title="<?php echo $this->lang->line("cust_qa3");?>">           
            <div class="tl-icon btn-icon-round btn-icon btn-icon-thin btn-warning"><i class="imd imd-map"></i></div>
            </a></td>

            <td width="25%" class="contenttd"><a href="<?php echo base_url();?>index.php/route/add_fare" title="<?php echo $this->lang->line("cust_qa4");?>">            
            <div class="tl-icon btn-icon-round btn-icon btn-icon-thin btn-info"><i class="imd imd-attach-money"></i></div>
            </a></td>           
        </tr>

       
        <tr align="center" class="contentrow">       
            
            <td width="25%" class="contenttd"><a href="<?php echo base_url();?>index.php/staff/add_staff" title="<?php echo $this->lang->line("cust_qa5");?>">           
             <div class="tl-icon btn-icon-round btn-icon btn-icon-thin btn-success"><i class="imd imd-person-add"></i></div>
            </a></td>            
            
            
            <td width="25%" class="contenttd"><a href="<?php echo base_url();?>index.php/device_assign/add" title="<?php echo $this->lang->line("cust_qa6");?>">            
            <div class="tl-icon btn-icon-round btn-icon btn-icon-thin btn-info"><i class="imd imd-tap-and-play"></i></div>
            </a></td> 

            <td width="25%" class="contenttd"><a href="<?php echo base_url();?>index.php/staff_assign/add" title="<?php echo $this->lang->line("cust_qa7");?>">            
            <div class="tl-icon btn-icon-round btn-icon btn-icon-thin btn-dark"><i class="imd imd-account-child"></i></div>
            </a></td>           
        </tr>

       
    </table>
</div> 


<div class="modal fade" id="myModalHelp" role="dialog">
  <div class="modal-dialog">
  
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Bus Management System</h4>
      </div>
      <div class="modal-body">
       <p>
          <p><?php echo $this->lang->line("cust_help1");?></p>
          <p><?php echo $this->lang->line("cust_help2");?></p>
          <p><?php echo $this->lang->line("cust_help3");?></p>
          <p><?php echo $this->lang->line("cust_help4");?></p>
          <p><?php echo $this->lang->line("cust_help5");?></p>
          <p><?php echo $this->lang->line("cust_help6");?></p>
          <p><?php echo $this->lang->line("cust_help7");?></p>
       </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-help-close" data-dismiss="modal">CLOSE</button>
      </div>
    </div>    
  </div>
</div>

<div class="edit_tooltip">
  <p></p>
</div>  
<div class="delete_tooltip">
  <p></p>
</div>  


<script type="text/javascript">
$(function()
{
    $(".quick_links").find("i").click(function(e)
    {
        var x = e.pageX;
        var y = e.pageY + 20;

        $(".quick_links_menu").slideToggle();
        $(".quick_links_menu").css({left:x+"px", top:y+"px"});
    });

    /*$(".quick_links").find("i").mouseout(function(e)
    {
        $(".quick_links_menu").slideUp();
    });*/

    highlight_menu();

    //Remove Help Active Class
    $('.btn-help-close').click(function(){
        $( ".ui-wave" ).removeClass( "active" );
    });

});

$(document).mouseup(function (e) 
{
    /*var popup = $(".quick_links_menu");

    if (!$('.quick_links').is(e.target) && !popup.is(e.target) && popup.has(e.target).length == 0) 
    {
        popup.slideUp();
    }*/

});


function highlight_menu()
{
   var cpg = "<?php echo $_SERVER['PATH_INFO'];?>"; 

   $("#nav").find("li.ui-wave").removeClass("active");

   if(cpg.indexOf("add_fare")>=0)
   {
        cpg = cpg.replace("/route/add_fare","addfare");
   }
   else if(cpg.indexOf("bus_route_list")>=0)
   {
        cpg = cpg.replace("/route/bus_route_list","assignbus");
   }


   if(cpg.indexOf("/city")>=0)
   {
        $("#nav").find("li.ui-wave:eq(1)").addClass("active");
   }
   
   else if(cpg.indexOf("/staff")>=0)
   {
        $("#nav").find("li.ui-wave:eq(2)").addClass("active");
   }

   else if(cpg.indexOf("/bus")>=0 || cpg.indexOf("/device_assign")>=0 || cpg.indexOf("/staff_assign")>=0)
   {
        $("#nav").find("li.ui-wave:eq(3)").addClass("active");
   }
      
   else if(cpg.indexOf("/routes_details")>=0 || cpg.indexOf("/route/add")>=0 || cpg.indexOf("/route/edit")>=0)
   {
        $("#nav").find("li.ui-wave:eq(5)").addClass("active");
   }
    
   else if(cpg.indexOf("/routes_fare_details")>=0 || cpg.indexOf("addfare")>=0 )
   {
        $("#nav").find("li.ui-wave:eq(6)").addClass("active");
   }

   else if(cpg.indexOf("assignbus")>=0 || cpg.indexOf("/route/manage")>=0)
   {
        $("#nav").find("li.ui-wave:eq(7)").addClass("active");
   }    
   
   
   else if(cpg.indexOf("/device")>=0)
   {
        $("#nav").find("li.ui-wave:eq(8)").addClass("active");
   }
   
   else if(cpg.indexOf("/tracking")>=0)
   {
        $("#nav").find("li.ui-wave:eq(9)").addClass("active");
   }
   
   else if(cpg.indexOf("/reports")>=0)
   {
        $("#nav").find("li.ui-wave:eq(10)").addClass("active");
   }

   else if(cpg.indexOf("/change_pass")>=0)
   {
        $("#nav").find("li.ui-wave:eq(11)").addClass("active");
   }
   else if(cpg.indexOf("/seat")>=0)
   {
        $("#nav").find("li.ui-wave:eq(4)").addClass("active");
   }
   
   else
   {
        $("#nav").find("li.ui-wave:eq(0)").addClass("active");
   }


}
</script>

