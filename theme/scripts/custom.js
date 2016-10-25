/*-----------------------------------------------------------------------------------
Roue Add and Edit Section
-----------------------------------------------------------------------------------*/
function get_city_list(typ)
{
	var call_url = BASE_PATH_URL+"index.php/route/city_list";
            
    var post_data = "";

    if(typ == "ss")//Source State
    {
        post_data = {"state_ids":$("#route_source_state").val(),'typ':'option'};
    }
    else if(typ == "ds")//Destination State
    {
        post_data = {"state_ids":$("#route_destination_state").val(),'typ':'option'};
    }
    else if(typ == "stp_st")//Stoppage State
    {      
        
        var ids = get_selected_state(); 

        if(ids=="0" || ids=="")
        {
        	$("#stoppage_city_list").html('');        	
        	return;
        }             

        post_data = {"state_ids":ids,'typ':'list'};
    }    


    $.ajax({method: 'POST',
            data:post_data,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}, 
            url: call_url,
            async : false,
            success: function(res)
            {	

            	if(typ == "ss")//Source State
    			{
    				var default_txt = "<option value=''>स्रोत शहर को चुनें</option>"+res;
    				$("#route_source_city").html(default_txt);
    			}
    			else if(typ == "ds")//Source State
    			{
    				var default_txt = "<option value=''>गंतव्य शहर को चुनें</option>"+res;
    				$("#route_destination_city").html(default_txt);
    			}
    			else if(typ == "stp_st")//Stoppage State
    			{
    				
    				$("#stoppage_city_list").html(res);

            
    			}
    			
            }

        });

    

}


function get_selected_state()
{
	var ids = 0

	$("#sel_stoppage_states").find("input[type='checkbox']").each(function()
  {
  	if($(this).is(":checked"))
  	{
  		ids = ids + "," + $(this).val();
  	}
  }); 

    return ids; 
}



function add_stoppage_city(city_id)
{
 
    var city_name = $("#stopage_city_"+city_id).text();

    var t = $.trim($("#sortable_stoppage").find("li#sortable_stoppage_li_"+city_id).html());

    if(t == "")
    {	

    	$("#sortable_stoppage").append('<li class="list-group-item" id="sortable_stoppage_li_'+city_id+'">'+city_name+'<span style="float:right;" ><a href="#" onclick="remove_stoppage_city('+city_id+')" class="md-warn btn_delete"><span class="imd imd-delete"></span></a></span></li>');
       
    } 
}


function remove_stoppage_city(city_id)
{
	if(confirm("Are you sure want to delete selected stoppage ?"))
  {
    $("#sortable_stoppage_li_"+city_id).remove();	 
  }  
}


function chk_inputs(pgtype)
{
	  var route_name = $("#route_name").val();
    var route_source_city = $("#route_source_city").val();
    var route_destination_city = $("#route_destination_city").val();

    //var route_source_state = $("#route_source_state").val();
    //var route_destination_state = $("#route_destination_state").val();

    if(route_name == "" || route_source_city == "" || route_destination_city == "")
    {
    	if(pgtype == "add")
      { 
          $("#sel_stoppage_states").find("label").find("input[type='checkbox']").each(function()
        	{
        		$(this).removeAttr("checked");
        	});

        	$("#sel_stoppage_states").find("label").hide();

        	$("#stoppage_city_list").html('');

        	$("#sortable_stoppage").html('');
      }  

      $("#btn_save").attr("disabled",true);
      $("#btn_save").addClass("btn_disabled");

      $("#stoppage_outer_container").hide();
    }
    else
    {
    	$("#sel_stoppage_states").find("label").show();
      $("#btn_save").removeAttr("disabled");
      $("#btn_save").removeClass("btn_disabled");
      $("#stoppage_outer_container").show();
    }    
}

function save_destination()
{   
    var route_name = $("#route_name").val();
    var route_source_city = $("#route_source_city").val();
    var route_destination_city = $("#route_destination_city").val();

    if(route_name == "" || route_source_city == "" || route_destination_city == "")
    {
    	alert("Please fill all mandatory fields.");

    	return;
    }	

	var route_edit_id = $("#route_edit_id").val();
    var route_source_state = $("#route_source_state").val();
    var route_destination_state = $("#route_destination_state").val();
    var state_ids = get_selected_state();


	//Prepare and Process posted form
    var city_ids = [];
    var city_ids_str = "0";
   	var temp = "";
    $("#sortable_stoppage").find("li").each(function()
    {
    	temp = $(this).attr("id");
    	temp = temp.replace("sortable_stoppage_li_","");
    	city_ids_str = city_ids_str + "," + temp;
    });
   
    
    show_processing();

    //Convert posted data and send it for processing
    var post_data = {"eid":route_edit_id,"rnm":route_name,"rss":route_source_state,"rsc":route_source_city,"rds":route_destination_state,"rdc":route_destination_city,"stp_state":state_ids,"stp_city":city_ids_str};


    $.ajax({
        method:'POST',
        url:BASE_PATH_URL+"index.php/route/add_route",
        data:post_data,
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        timeout:1000,
        async : false,
        success: function(res)
        {
        	alert_msg_box(res,'r');
        }
    });
    
    
}


function show_processing()
{
    $(window).scrollTop(0);

    

    $("#output_msg").show();  

    $("#output_msg").find("div").html("Processing...");
}


$(function() 
{
    
   
    var u = window.location.href;
    if(u.indexOf("route/add")>=0)
    {
    	chk_inputs("add");
    }	

    if(u.indexOf("route/edit")>=0 || u.indexOf("route/add")>=0)
    {
      $( "#sortable_stoppage" ).sortable();
    }

    $("#output_msg").hide();    
    

   	//Search for State ------------------------------------------------------
   	$("#search_stoppage_state").bind("keyup",function()
   	{
   		var word = $(this).val();

   		var str = "";

   		$("#sel_stoppage_states").find("label").each(function()
   		{
   			str = $(this).html();

   			if (str.toLowerCase().indexOf(word) < 0)
   			{
   				$(this).hide();
   			}
   			else
   			{
   				$(this).show();
   			}

   		});

   	});

   	
    //Search for City ------------------------------------------------------
   	$("#search_stoppage_city").bind("keyup",function()
   	{
   		var word = $(this).val();

   		var str = "";

   		$("#stoppage_city_list").find("a,span").each(function()
   		{
   			str = $(this).html();

   			if (str.toLowerCase().indexOf(word) < 0)
   			{
   				 $(this).hide();            
   			}
   			else
   			{
   				$(this).show();
   			}

   		});

   	});


    //Show hide sidebar
    $(".menu-button").bind("click",function()
    {
        $("body#app").toggleClass("on-canvas");
    });

    
    $("#top_nav_btn").bind("click",function()
    {
        $("body#app").toggleClass("nav-collapsed-min");

        /*if($("body#app").attr("class").indexOf("nav-collapsed-min")>=0)
        {
            $("#current_view_type").val("2");
        }
        else
        {
            $("#current_view_type").val("1");
        }*/
    });


    $("#nav").find("li.ui-wave").bind("click",function()
    {
        $(this).toggleClass("active");

        $(this).find("ul").toggle();

    });

});


function alert_msg_box(msg,pg)
{
	$("#output_msg").show();
		        	
	$("#output_msg").find("div").html(msg);

	setTimeout(function()
	{
		$("#output_msg").find("div").html('');
		
    $("#output_msg").hide();
   
    if(msg.indexOf('succes')>=0 || msg.indexOf('0success')>=0)  
    {
      if(pg == 'r')
      {
          window.location.href = BASE_PATH_URL + "index.php/route/routes_details";
      }
      else if(pg == 'f')
      {
          window.location.href = BASE_PATH_URL + "index.php/route/routes_fare_details";        
      }
      else if(pg == 'b')
      {
          window.location.href = BASE_PATH_URL + "index.php/bus";        
      }
      else if(pg == 'bt')
      {         
          window.location.href = BASE_PATH_URL + "index.php/route/bus_route_list";        
      }
      else if(pg == 'stf')
      {         
          window.location.href = BASE_PATH_URL + "index.php/staff";        
      }
      else if(pg == 'hm')
      {         
          window.location.href = BASE_PATH_URL + "index.php/home";        
      }
      else if(pg == 'da')
      {         
          window.location.href = BASE_PATH_URL + "index.php/device_assign";        
      }
      else if(pg == 'dv')
      {         
          window.location.href = BASE_PATH_URL + "index.php/device";        
      }
      else if(pg == 'sa')
      {         
          window.location.href = BASE_PATH_URL + "index.php/staff_assign";        
      }
    }  

	},"5000");
}


/*-----------------------------------------------------------------------------------
Fare Add and Edit Section
-----------------------------------------------------------------------------------*/
function get_route_details()
{
    var call_url = BASE_PATH_URL+"index.php/route/route_details";
    
    if($("#fare_routes").val() == "" || $("#fare_routes").val()<=0)
    { 
      $("#source_city").html('');

      $("#destination_city").html('');

      $("#matrix").html('');
      
      $("#btn_save").attr("disabled",true);
      
      $("#btn_save").addClass("btn_disabled");

      return;
    }
    else
    {
      $("#btn_save").removeAttr("disabled");
      
      $("#btn_save").removeClass("btn_disabled");
    } 

    var post_data = {"rid":$("#fare_routes").val()};          

    $.ajax({method: 'POST',
            data:post_data,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}, 
            url: call_url,
            async : false,
            success: function(res)
            {            	
            	var obj = $.parseJSON(res);

            	if(obj.msg == "success")
        			{
    	            	$("#source_city").html(obj.all_stp[0].name);

    	            	$("#destination_city").html(obj.all_stp[obj.all_stp.length-1].name);

    	            	$("#matrix").html(obj.matrix);

                    $(".fare_amt").bind("keypress",function(e)
                    {
                         if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) 
                         {
                            return false;
                         }
                    });

        			}
        			else
        			{
        				alert_msg_box(res.msg,'');
        			}	


            }
    });
}


function save_fare()
{   
    var route_id = $("#fare_routes").val();
    
    if(route_id == "" || route_id<=0)
    {
      alert("Please select route name.");

      return;
    }
   
    show_processing();

    var str = $( "#add_fare_form" ).serialize();
        
    //Convert posted data and send it for processing
    var post_data = str;//{"rid":route_id,"ids_json":str};


    $.ajax({
        method:'POST',
        url:BASE_PATH_URL+"index.php/route/save_fare",
        data:post_data,
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        timeout:1000,
        async : false,
        success: function(res)
        {
          alert_msg_box(res,'f');
        }
    });
    
    
}


/*-----------------------------------------------------------------------------------
Bus Add and Edit Section
-----------------------------------------------------------------------------------*/
$(function()
{
    var photo_upload_html = '<div><div class="upload_image col-md-6"><span>Select File...</span><input type="file" name="bus_photo[]" onchange="show_file_name(this);" /></div><span class="showfilename"></span><div class="add_btn col-md-3 text-right" id=""><a class="md-fab md-primary md-fab-sm md-button md-default-theme bus_remove_more"><span class="imd imd-remove"></span></a></div><div class="clearfix"><input type="hidden" id="photo_form_submit" name="image_form_submit" value="1"/></div></div>';

    $("#bus_add_btn_more").click(function()
    {
        $("#bus_add_more_photo").append(photo_upload_html);
    
        $(".bus_remove_more").click(function()
        {
            $(this).parent().parent().remove();
        });

    });
   
});

function show_file_name(obj)
{ 
    var fnm = $(obj).val();
    if(fnm.length>13)
    {
        fnm = fnm.substr(0,13)+"...";
    }

    $(obj).parent().find("span:first").html(fnm);
    //$(obj).parent().parent().find(".showfilename").html($(obj).val());
}

/*-----------------------------------------------------------------------------------
Bus Route Assigning Section
-----------------------------------------------------------------------------------*/
function save_bus_timing()
{
    var bus_route_bus_id = $("#bus_route_bus_id").val();
    var bus_route_route_id = $("#bus_route_route_id").val();
    var bus_route_from_date = $("#bus_route_from_date").val();
    var bus_route_to_date = $("#bus_route_to_date").val();
    var bus_route_max_trip = $("#bus_route_max_trip").val();
    
    if(bus_route_bus_id == "" || bus_route_route_id == "" || bus_route_from_date == "" || bus_route_to_date == "" || bus_route_max_trip == "")
    {
      alert("Please input all fields.");

      return;
    }
   
    show_processing();

    var str = $("#add_bus_route_form").serialize();
        
    //Convert posted data and send it for processing
    var post_data = str;

    $.ajax({
        method:'POST',
        url:BASE_PATH_URL+"index.php/route/save_bus_timing",
        data:post_data,
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        timeout:1000,
        async : false,
        success: function(res)
        {
          alert_msg_box(res,'bt');
          //$("#output_msg").find("div").html(res);
        }
    });
    
}



/*-----------------------------------------------------------------------------------
Device Assigning Section
-----------------------------------------------------------------------------------*/
function chk_device_assigned(seldate)
{ 
    var device = $("#bus_device").val();
    var bus = $("#bus_name").val();

    var post_data = 'bus='+bus+'&device='+device+'&date='+seldate;

    $.ajax({
        method:'POST',
        url:BASE_PATH_URL+"index.php/device_assign/check",
        data:post_data,
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        timeout:1000,
        async : false,
        success: function(res)
        {
            if(res != "")
            {
              $('#assign_date').multiDatesPicker('removeDates', seldate);
              alert(res);
            }  
        }
    });
      
}



/*-----------------------------------------------------------------------------------
Fare Report Section
-----------------------------------------------------------------------------------*/
function get_fare_report()
{
    var call_url = BASE_PATH_URL+"index.php/reports/fare";
    
    show_processing();

    var post_data = $("#fare_report_form").serialize();        

    $.ajax({method: 'POST',
            data:post_data,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}, 
            url: call_url,
            async : false,
            success: function(res)
            {             
                $('#example').dataTable().fnDestroy();
                $("#example").find("tbody").html(res);
                $('#example').DataTable();
            }
    });
}


/*-----------------------------------------------------------------------------------
Staff Assigning Section
-----------------------------------------------------------------------------------*/
function chk_staff_assigned(seldate)
{ 
    var driver_name = $("#driver_name").val();
    var conductor_name = $("#conductor_name").val();
    var helper_name = $("#helper_name").val();
    var other_name = $("#other_name").val();
    var bus = $("#bus_name").val();

    var post_data = 'bus='+bus+'&driver='+driver_name+'&conductor='+conductor_name+'&helper='+helper_name+'&other='+other_name+'&date='+seldate;

    $.ajax({
        method:'POST',
        url:BASE_PATH_URL+"index.php/staff_assign/check",
        data:post_data,
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        timeout:1000,
        async : false,
        success: function(res)
        {
            if(res != "")
            {
              $('#assign_date').multiDatesPicker('removeDates', seldate);
              alert(res);
            }  
        }
    });
      
}


/*-----------------------------------------------------------------------------------
Report Fare Section
-----------------------------------------------------------------------------------*/
function get_fare_details_info(bus_name,bus_id,route_id,date,trip_num) 
{


  
    var post_data = 'bus_id='+bus_id+'&route_id='+route_id+'&for_date='+date;
    

    $.ajax({
        method:'POST',
        url:BASE_PATH_URL+"index.php/reports/fare_details",
        data:post_data,
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        timeout:1000,
        async : false,
        success: function(res)
        {

            var dArr = date.split("-"); 
            
            newdate = dArr[2]+ "-" +dArr[1]+ "-" +dArr[0];
            
            var heading=bus_name+': ('+newdate+')';
          
            if(res != "")
            {              
              $('.modal-title').html(heading);
           
              $("#fare_details").html(res);
              
            }
            else
            {
              $('.modal-title').html(heading);
              
              $("#fare_details").html('No record found.');
            }  
        }
    });

    $("#fare_details").find("ul.nav-tabs").find("li").bind("click",function(i,v)
    {
        $("#fare_details").find("ul.nav-tabs").find("li").removeClass("active");
        $(this).addClass("active");

        $("#fare_details").find("div.tab-content").find(".tab-pane").removeClass("active");
        $("#fare_details").find("div.tab-content").find("#content"+$(this).attr("id")).addClass("active");
    });

    $('#myModal001').modal('show');
}

function get_bus_route_details_info(from_date,tos_dates,bus_id,route_id,max_trip) 
{

      $("#bus_route_bus_id").val(bus_id);

      $('#bus_route_to_date').html(tos_dates);

      $("#bus_route_from_date").val(from_date);

      $('#bus_route_route_id').val(route_id);
      $('#bus_route_max_trip').val(max_trip);

      $('#myModal001').modal('show');

}

$(document).ready(function()
{
  //$('body').fadeOut("slow").delay(500).fadeIn("slow");  
  //$('.view-container').delay(200).fadeIn("slow");  
   
});