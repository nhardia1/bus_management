<?php
//$months = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
$months = array($this->lang->line("jan"),$this->lang->line("feb"),$this->lang->line("mar"),$this->lang->line("apr"),$this->lang->line("may"),$this->lang->line("jun"),$this->lang->line("jul"),$this->lang->line("aug"),$this->lang->line("sep"),$this->lang->line("oct"),$this->lang->line("nov"),$this->lang->line("dec"));
?>

<style type="text/css">
.panel-box
{
    height: auto !important;
}

.page
{
    padding-top: 1% !important;
}

</style>

<div class="page page-dashboard ng-scope" style="height:100%;">


    <div class="row">
       <!-- Stats -->              
       
        <div class="col-sm-3">
            <a href="<?php echo base_url();?>index.php/bus">
            <div class="panel mini-box dash-buses">
                <span class="btn-icon btn-icon-round btn-icon-lg-alt bg-danger">
                    <i class="imd imd-directions-bus"></i>
                </span>
                <div class="box-info">
                    <p class="size-h2"><?php echo $count_data[1]['count']; ?></p>
                    <p class="text-muted"><?php echo $this->lang->line("buses"); ?></p>
                    
                </div>
            </div>
            </a>                    
        </div>
        

        <div class="col-sm-3">
            <a href="<?php echo base_url();?>index.php/route/routes_details">
            <div class="panel mini-box dash-routes">
                <span class="btn-icon btn-icon-round btn-icon-lg-alt bg-warning">
                    <i class="imd imd-map"></i>
                </span>
                <div class="box-info">
                    <p class="size-h2"><?php echo $count_data[3]['count']; ?></p>
                    <p class="text-muted"><?php echo $this->lang->line("routes"); ?></p>
                </div>
            </div>
            </a>                    
        </div>
        

        <div class="col-sm-3">
            <a href="<?php echo base_url();?>index.php/staff">
            <div class="panel mini-box dash-staffs">
                <span class="btn-icon btn-icon-round btn-icon-lg-alt bg-success">
                    <i class="imd imd-account-child"></i>
                </span>
                <div class="box-info">
                    <p class="size-h2"><?php echo $count_data[0]['count']; ?></p>
                    <p class="text-muted"><?php echo $this->lang->line("staff's"); ?></p>
                </div>
            </div>
            </a>
        </div>
        

        <div class="col-sm-3">
            <a href="<?php echo base_url();?>index.php/device">
            <div class="panel mini-box dash-device">
                <span class="btn-icon btn-icon-round btn-icon-lg-alt bg-info">
                    <i class="imd imd-phone-iphone"></i>
                </span>
                <div class="box-info">
                    <p class="size-h2"><?php echo $count_data[2]['count']; ?></p>
                    <p class="text-muted"><?php echo $this->lang->line("devices"); ?></p>
                </div>
            </div>
            </a>
        </div>


        <!-- end stats -->          
    </div>


    <!--Today allocated buses-->                
    <div class="panel panel-info dash-list" style="border:1px solid #7AB8D9;">
        <div class="panel-heading">
            <h3 class="panel-title"><span class="glyphicon glyphicon-th"></span>&nbsp;<b><?php echo $this->lang->line("dashboard_todayallocatedbuses"); ?></b>
                <span style="float: right; margin-top: -5px;" id="btns">
                    <label id="display_date"></label>&nbsp;&nbsp;&nbsp;

                    <a href="#" onclick="get_allocated_buses('d');" class="edit_btn"><span class="imd imd-keyboard-arrow-left"></span></a>

                    <a href="#" onclick="get_allocated_buses('i');" class="delete_btn"><span class="imd imd-keyboard-arrow-right"></span></a>
                </span>
            </h3>
        </div>
        <div class="panel-body">
             <div class="table-responsive">
                <table id="today_buses_list" class="table table-striped table-bordered no-margin">
                    <thead>
                        <tr>
                            <th width="3%">#</th>
                            
                            <th width="17%"><?php echo $this->lang->line("dashboard_busname"); ?></th>
                            
                            <th width="17%"><?php echo $this->lang->line("dashboard_routename"); ?></th>

                            <th width="6%"><?php echo $this->lang->line("trip") ?></th>
                            
                            <th width="28%"><?php echo $this->lang->line("cust_todaygoingbuses"); ?>&nbsp;<span class="help_txt">(<?php echo $this->lang->line("from") ?> <label class='from_help_text'><?php echo $this->lang->line("source"); ?></label>)</span></th>
                            
                            <th width="28%"><?php echo $this->lang->line("cust_todaycomingbuses"); ?>&nbsp;<span class="help_txt">(<?php echo $this->lang->line("from") ?> <label class='to_help_text'><?php echo $this->lang->line("destination");?></label>)</span></th>
                        </tr>

                    </thead>
                    <tbody id="today_buses"></tbody>
                </table>
            </div>
        </div>
    </div>


    <!--Bus Status Details-->                
    <div class="panel panel-success dash-list" id="bus_status_panel" style="border:1px solid #99B073;">
        <div class="panel-heading">
            <h3 class="panel-title"><span class="glyphicon glyphicon-th"></span>&nbsp;<b><?php echo $this->lang->line("dashboard_busstatus"); ?></b>
                
                
                <span style="float: right; margin-top: -5px;" id="btns">                    
                    <input type="text" name="bus_status_for_date" id="bus_status_for_date" value="<?php echo date(DISPLAY_DATE);?>" />                      
                </span>

                <span style="float: right;">                  
                    <label><?php echo $this->lang->line("cust_selectdate"); ?>&nbsp;&nbsp; </label>                       
                </span>               
            </h3>
        </div>
        <div class="panel-body">
             <div class="table-responsive">
                <table id="buses_status_list" class="table table-striped table-bordered no-margin">
                    <thead>
                        <tr>
                            <th width="3%">#</th>
                            
                            <th width="20%"><?php echo $this->lang->line("dashboard_busname"); ?></th>

                            <th width="7%"><?php echo $this->lang->line("trip"); ?></th>
                            
                            <th width="15%"><?php echo $this->lang->line("from"); ?></th>
                            
                            <th width="15%"><?php echo $this->lang->line("to"); ?></th>

                            <th width="40%"><?php echo $this->lang->line("status"); ?></th>                           

                        </tr>                       
                        
                    </thead>
                    <tbody id="buses_status"></tbody>
                </table>
            </div>
        </div>
    </div>


    <!--Yearly Report-->                
    <div class="panel panel-warning dash-list" id="bus_yearly_list_panel"  style="border:1px solid #CCB058;">
        <div class="panel-heading">
            <h3 class="panel-title"><span class="glyphicon glyphicon-th"></span>&nbsp;<b><?php echo $this->lang->line("dashboard_bus_yearly_report"); ?></b>
                
                <span style="float: right; margin-top: -5px;" id="btns">                    
                    <select name="bus_year" id="bus_year" onchange="get_buses_yearly_report();">
                        <?php 
                        $cy = date("Y");
                        for($y=2015;$y<=$cy;$y++)
                        {
                            $s = "";
                            if($y == $cy)
                            {
                                $s = "selected = 'selected'";
                            }

                            echo "<option value='$y' $s>$y</option>";
                        }
                        ?>
                    </select>
                </span>

                <span style="float: right;">                  
                    <label><?php echo $this->lang->line("cust_selectyear"); ?>&nbsp;&nbsp; </label>                       
                </span>               
            </h3>
        </div>
        <div class="panel-body">
             <div class="table-responsive">
                
                <table align="right">
                    <tbody>
                        <tr>
                            <td class="help_txt from_help_text"><i class="imd imd-account-child"></i>&nbsp;<?php echo $this->lang->line("passenger"); ?></td>
                            <td class="help_txt to_help_text">&nbsp;&nbsp;&nbsp;<i class="imd imd-attach-money"></i>&nbsp;<?php echo $this->lang->line("amount"); ?></td>
                        </tr>
                    </tbody>
                </table>


                <table id="bus_yearly_list" class="table table-striped table-bordered no-margin">
                    <thead>
                        <tr>
                            <th width="3%">#</th>

                            <th width="15%"><?php echo $this->lang->line("dashboard_busname"); ?></th>

                            <th width="15%"><?php echo $this->lang->line("dashboard_routename"); ?></th>

                            <th>&nbsp;</th>
                         
                            <?php
                            foreach($months as $mon)
                            {
                            ?>
                                <th width="5%"><?php echo $mon;?></th>
                            <?php
                            }
                            ?>

                            <th width="7%"><?php echo $this->lang->line("total") ; ?> </th>

                        </tr>                        
                    </thead>
                    
                    <tbody id="bus_yearly_list_body"></tbody>

                </table>
            </div>
        </div>
    </div>
                
</div>


<link rel="stylesheet" href="<?php echo base_url();?>theme/styles/jquery-ui.css">

<script type="text/javascript">
var today = 0;
var curr = new Date;
var first = curr.getDate();

$(function()
{
    $( "#bus_status_for_date" ).datepicker({
        dateFormat: 'dd-mm-yy',
        changeMonth: SHOW_MONTH,
        changeYear: SHOW_YEAR,
        yearRange: '2015:',
        numberOfMonths: 1,
        maxDate: new Date()
    });

    $("#bus_status_for_date").change(function()
    {   
        get_buses_status();
    });

    get_allocated_buses('n');

    get_buses_status();

    get_buses_yearly_report();

});


function get_allocated_buses(a)
{
    if(a == 'i')
    {
        today++;
        first = first + 1;
    }
    else if(a == 'd')
    {
        today--;
        first = first - 1;
    }

    var curr = new Date;
    var firstday = new Date(curr.setDate(first)).toUTCString();    
    var startDatePieces = firstday.split(/[\s,]+/);
    var startDate = startDatePieces[0] + ", " + startDatePieces[1] + " " + startDatePieces[2]+ " " + startDatePieces[3];

    $("#display_date").html(startDate);  

    $.ajax({
        type :'GET',
        dataType:'text',
        async:"true",
        url : '<?php echo base_url(); ?>index.php/home/get_today_scheduled_buses/'+today,
        success : function(res)
        {
            $("#today_buses_list").find('tbody#today_buses').html(res);            
        }
    });  
}



function get_buses_status()
{
    var post_data = $("#bus_status_for_date").val();

    post_data = {"fordate":post_data};

    $.ajax({
        type :'POST',
        dataType:'text',
        data: post_data,
        async:"true",
        url : '<?php echo base_url(); ?>index.php/home/get_buses_status',
        success : function(res)
        {
            $("#buses_status_list").find('tbody#buses_status').html(res);            
        }
    });  
}



function get_buses_yearly_report()
{
    var post_data = $("#bus_year").val();

    post_data = {"foryear":post_data};

    $.ajax({
        type :'POST',
        dataType:'text',
        data: post_data,
        async:"true",
        url : '<?php echo base_url(); ?>index.php/home/get_buses_yearly_report',
        success : function(res)
        {
            $("#bus_yearly_list").find('tbody#bus_yearly_list_body').html(res);            
        }
    });  
}

</script>

