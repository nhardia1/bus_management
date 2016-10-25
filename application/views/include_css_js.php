<?php
$lang_selected = $this->session->userdata('message');
if($lang_selected == "" || !isset($lang_selected))
{
  $lang_selected = "hindi";
}
?>    


<!-- Needs images, font... therefore can not be part of main.css -->
<link href="http://fonts.googleapis.com/css?family=Roboto:400,100,500,700,300,300italic,500italic|Roboto+Condensed:400,300" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="<?php echo base_url();?>theme/bower_components/font-awesome/css/font-awesome.min.css">
<!-- end Needs images -->

<link rel="stylesheet" href="<?php echo base_url();?>theme/styles/main.css">

<link rel="stylesheet" href="<?php echo base_url();?>theme/styles/custom.css">

<link rel="stylesheet" href="<?php echo base_url();?>theme/styles/mine.css">

<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>theme/styles/style.css">


<script type="text/javascript">
	
	var BASE_PATH_URL = "<?php echo base_url();?>";

	var SHOW_MONTH = true;

	var SHOW_YEAR = true;

  var LANG_SELECTED = "<?php echo $lang_selected;?>";

</script>


<!--script src="http://maps.google.com/maps/api/js?sensor=false"></script-->

<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>theme/scripts/jquery-1.11.3.min.js"></script>

<script src="<?php echo base_url();?>theme/scripts/jquery-ui.js"></script>

<script src="<?php echo base_url();?>theme/scripts/custom.js"></script>

<!--script type="text/javascript" language="javascript" src="<?php //echo base_url(); ?>theme/scripts/jquery.dataTables.min.js"></script-->

<script src="<?php echo base_url(); ?>theme/scripts/bootstrap.min.js"></script>


<script>
$(document).ready(function()
{
    $('[data-toggle="tooltip"],[data-toggle="modal"]').tooltip();   
});
</script>

<link rel="stylesheet" href="<?php echo base_url();?>public/datatable/css/style.css" type="text/css" media="screen"/>


<script type="text/javascript" src="<?php echo base_url(); ?>public/datatable/js/jquery.dataTables.min.js"></script>

<!--Hindi typing-->
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<script type="text/javascript">
$(document).ready(function() 
{



  var sagment1 = '<?php echo $this->uri->segment(1); ?>' ;
  var sagment2 = '<?php echo $this->uri->segment(2); ?>' ;
  var url='';
  var url1='<?php echo base_url(); ?>index.php/'+sagment1+'/datatable';
  url=url1;
  var url2='<?php echo base_url(); ?>index.php/'+sagment1+'/bus_route_datatable';
  var url3='<?php echo base_url(); ?>index.php/'+sagment1+'/route_fare_datatable';
  if(sagment1=='route' && sagment2=='bus_route_list'){
    url=url2;
  }else if(sagment1=='route' && sagment2=='routes_fare_details'){
    url=url3;
  }
  
  var oTable = $('#big_table').dataTable( {
    "bProcessing": true,
    "bServerSide": true,
    "sAjaxSource": url,
    "bJQueryUI": true,
    "bFilter":true,
    "sPaginationType": "full_numbers",
    "iDisplayStart ":20,
    "oLanguage": {
      "sProcessing": "<img src='<?php echo base_url(); ?>public/datatable/images/ajax-loader_dark.gif'>"
    }, 
    "fnInitComplete": function() 
    {
        $("#big_table_filter").find("input[type='text']").attr("id","csbox");
            $("#big_table_filter").find("input[type='text']").attr("name","हिन्दी के लिए पहले अंग्रेजी में शब्द type करे और उसके बाद SPACEBAR key दबाएँ.");

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


        google.load("elements", "1", {  packages: "transliteration" });         
        function onLoadit() 
        {
          var options = {
              sourceLanguage:
                  google.elements.transliteration.LanguageCode.ENGLISH,
              destinationLanguage:
                  [google.elements.transliteration.LanguageCode.HINDI],
              shortcutKey: 'ctrl+g',
              transliterationEnabled: true
          };
          var control =  new google.elements.transliteration.TransliterationControl(options);
          control.makeTransliteratable(['csbox']);
        }
        google.setOnLoadCallback(onLoadit);
            


        $(".edit_btn").mouseover(function(e)
        {
            var x = e.pageX - 50;
            var y = e.pageY - 50;

            $(".edit_tooltip").show();
            $(".edit_tooltip").css({left:x+"px", top:y+"px"});
            $(".edit_tooltip").find("p").html($(this).attr("name"));
        });

        $(".edit_btn").mouseout(function(e)
        {                         
            $(".edit_tooltip").hide();                          
        });

        $(".delete_btn").mouseover(function(e)
        {
            var x = e.pageX - 50;
            var y = e.pageY - 50;

            $(".delete_tooltip").show();
            $(".delete_tooltip").css({left:x+"px", top:y+"px"});
            $(".delete_tooltip").find("p").html($(this).attr("name"));
        });

        $(".delete_btn").mouseout(function(e)
        {                         
            $(".delete_tooltip").hide();                          
        }); 
    },
    'fnServerData': function(sSource, aoData, fnCallback)
    {
      $.ajax
      ({
        'dataType': 'json',
        'type'    : 'POST',
        'url'     : sSource,
        'data'    : aoData,
        'success' : fnCallback
      });
    }
  } );

     

});


</script>

