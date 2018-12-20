jQuery(document).ready(function(){
    jQuery('#productlist').on('change',function(){		
		var productid = jQuery(this).val();				
		var url="<?php echo site_url();?>/index.php/ajaxdata";
		//alert(url);
		if(productid){			
           jQuery.post(			
			ajaxurl, 
			{
				'action': 'load_variations',
				'data':  {'productid': productid}
			}, 			
			function(response){				
				//alert('The server responded: ' + response);
				jQuery('#attrlist').html(response);                   
			}
		);
        }else{
           // jQuery('#attrlist').html('<option value="">Select Product first</option>');           
        }        
    });
    
	
	
   });
