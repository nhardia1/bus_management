$(document).ready(function() {
        
   /* activate the carousel */
   

   /* when clicking a thumbnail */
	 

});

//------------------Track and Stops begins-------------------//
jQuery(document).ready(function($){
   var dateNow = new Date();

    /*$("#datetimepicker1").datetimepicker({ format: 'DD/MM/YYYY',defaultDate:dateNow, useCurrent: false}
      );*/
    $("#datetimepicker1").datetimepicker({format: 'DD/MM/YYYY'});
  });
 
	
  
	
	//------------------Track and Stops End -------------------//
function openGallery(id){
    $("#modal-gallery-"+id).modal("show");
}	
