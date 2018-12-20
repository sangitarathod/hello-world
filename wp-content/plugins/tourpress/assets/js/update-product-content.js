jQuery(document).ready(function(){
	jQuery('#refresh_product_content').click(function(event){
        jQuery('#neo_loading_image').show();
		event.preventDefault();
        jQuery('#neo_updated_products_list').append("<br>*************************************Started*********************************");
		var location = jQuery('#neo_location').val();
		var product_type = jQuery('#neo_product_type').val();
		var offset = 0;
    //    jQuery('#neo_loading_image').show();
        setTimeout(
  function() 
  {
        neo_process_ajax(location,product_type,offset);
  }, 500);
		
	})

});


function neo_process_ajax(location,product_type,offset=0){
	jQuery.ajax({
            url : product_data.ajaxurl,
            type : 'post',
            async: false,
            data : {
                action: 'update_products',
                location: location,
                product_type: product_type,
                offset: offset
            },
            success : function( response ) {
            	if(response){
            		offset=offset+1;
            		jQuery('#neo_updated_products_list').append(response);
            		
                      setTimeout(
  function() 
  {
        neo_process_ajax(location,product_type,offset);
  }, 500);
            	}else{
            		jQuery('#neo_loading_image').hide();
            		jQuery('#neo_updated_products_list').append("<br>*************************************END*********************************");
            	}
            }
        });
}