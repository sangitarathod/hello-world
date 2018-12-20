jQuery(document).ready(function(){
		jQuery(document).on('click','.top_bar_notification_wrapper',function(){
			jQuery('.notifications ul').toggle();
		});
		jQuery(document).on('click','#gform_submit_button_9',function(){
			jQuery(".popup-overlay").show();
				jQuery(".confirmation-popup").show();
		});

		jQuery(document).on('click', '.status_usps', function() {
			
			if(jQuery(":checkbox:checked").length == 0){
				jQuery(".checkout-popup-overlay").show();
				jQuery(".shipping-popup").show();

				//alert("select a checkbox before selecting a shipping option.")
			} 
			else{
				location.reload(true);
			}
		});
		jQuery(document).on('click', '.status_fedex', function() {
			if(jQuery(":checkbox:checked").length == 0){
				jQuery(".checkout-popup-overlay").show();
				jQuery(".shipping-popup").show();
				//alert("select a checkbox before selecting a shipping option.")
			} 
			else{
				location.reload(true);
			}
		});
		jQuery(document).on('click', '.ok_close', function() {
			jQuery(".checkout-popup-overlay").hide();
				jQuery(".shipping-popup").hide();
		});
		var fedex_url=jQuery('.status_fedex').attr("onClick");
		var usps_url=jQuery('.status_usps').attr("onClick");

		jQuery("#select_all").change(function(){  
			
		    var status = this.checked; 
		    

		    if(jQuery('#select_all').is(":checked")){

		    jQuery(".checkbox").prop('checked', jQuery(this).prop("checked"));
		    var id_array=[];
		    var new_element='';
		    jQuery('.checkbox').each(function(){
		    	if(this.id){
		    		if(!jQuery("#"+this.id).is(':disabled') ) {
		    			jQuery("#"+this.id).prop('checked', jQuery(this).prop("checked"));
		    			id_array.push(this.id);
				    	new_element=id_array.join("-");
		    		}
				    
		    	}
		    });
		   
		   
		    var script = jQuery('#status_fedex').val();
		    var script2 = jQuery('#status_usps').val();
		    var new_url= script+new_element;
		    var new_url2 = script2+new_element;
		    new_url='window.open("'+new_url+'")';
		    new_url2='window.open("'+new_url2+'")';
		    jQuery('.status_fedex').attr("onClick",new_url);
		    jQuery('.status_usps').attr("onClick",new_url2);
			}else{
				jQuery(".checkbox").prop('checked', '');
				jQuery('.status_fedex').attr("onClick",fedex_url);
				jQuery('.status_usps').attr("onClick",usps_url);
			}
		});
		var checkboxes = jQuery('#all-devices td input[type="checkbox"]');
		

		jQuery('#all-devices .checkbox').change(function(){
			console.log("hi");
		  	var check = this.checked; 
		    if(false == jQuery(this).prop("checked")){ 
		        jQuery("#select_all").prop('checked', false); 
		    }
		   
		    if (jQuery('.checkbox:checked').length == jQuery('.checkbox').length ){
		        jQuery("#select_all").prop('checked', true);
		    }
		    
		    if (check == true)
			{	
			   var id = this.id;
			  	var new_check=jQuery('.status_fedex').attr("onClick");
				
			   if(id){
				   var script = jQuery('#status_fedex').val();
				   var script2 = jQuery('#status_usps').val();
				   var new_url='';
				   var new_url2='';
				  
				   if(checkboxes.filter(':checked').length > 1 ){

				   	 id="-"+id;
				   	 jQuery('#status_fedex').val(script+id);
				   	 jQuery('#status_usps').val(script2+id);
				   	 new_url=script+id;
				   	 new_url2=script2+id;
				   	 console.log(new_url);
				   	 console.log(new_url2);
				   	 new_url='window.open("'+new_url+'")';
				   	 new_url2='window.open("'+new_url2+'")';
			         jQuery('.status_fedex').attr("onClick",new_url);
			         jQuery('.status_usps').attr("onClick",new_url2);
				   }
				   else  if(new_check.indexOf('-') >-1)
				   {
				   	 id="-"+id;
				   	 jQuery('#status_fedex').val(script+id);
				   	 jQuery('#status_usps').val(script2+id);
				   	 new_url=script+id;
				   	 new_url2=script2+id;
				   	 console.log(new_url);
				   	 console.log(new_url2);
				   	 new_url='window.open("'+new_url+'")';
				   	 new_url2='window.open("'+new_url2+'")';
			         jQuery('.status_fedex').attr("onClick",new_url);
			         jQuery('.status_usps').attr("onClick",new_url2);
				   
				   }else{
					  
					  jQuery('#status_fedex').val(script+id);
					  jQuery('#status_usps').val(script2+id);
					  new_url=script+id;
				   	 
				   	  new_url2=script2+id;
				   	  new_url='window.open("'+new_url+'")';
				   	  new_url2='window.open("'+new_url2+'")';
			          jQuery('.status_fedex').attr("onClick",new_url);
			          jQuery('.status_usps').attr("onClick",new_url2);
					}
				}
			}else{
				
				var script = jQuery('#status_fedex').val();
				var script2 = jQuery('#status_usps').val();
				if(script.contains("-"+this.id)){
				 var new_script=script.replace("-"+this.id,'');
				 jQuery('#status_fedex').val(new_script);
				 
				}
				if(script.contains(this.id)){
					var new_script=script.replace("-"+this.id,'');
					jQuery('#status_fedex').val(new_script);
				
				}
				//for usps
				if(script2.contains("-"+this.id)){
				 var new_script2=script2.replace("-"+this.id,'');
				 jQuery('#status_usps').val(new_script2);
				 
				}
				if(script2.contains(this.id)){
					var new_script2=script2.replace("-"+this.id,'');
					jQuery('#status_usps').val(new_script2);
				
				}

			    jQuery('.status_fedex').attr("onClick",'window.open("'+new_script+'")');
			    jQuery('.status_usps').attr("onClick",'window.open("'+new_script2+'")');
			}
		});
		jQuery(".toggle").click(function(){
			var id=this.id;
			if(id === "weekly"){
				jQuery("#chartContainer2").hide();
				jQuery("#chartContainer").show();
				jQuery(".toggle").attr("id","monthly");
				jQuery(".toggle").text("View Weekly");
			}
			if(id === "monthly"){
				jQuery("#chartContainer2").show();
				jQuery("#chartContainer").hide();
				jQuery(".toggle").attr("id","weekly");
				jQuery(".toggle").text("View Monthly");
			}
		});

		jQuery(".month_click").click(function(){
			var com_id=this.id;
			if(com_id == "month_comission"){
				jQuery("#chartContainer4").hide();
				jQuery("#chartContainer3").show();
				jQuery(".month_click").attr("id","yearly_comission");
				jQuery(".month_click").text("View Monthly");
			}
			if(com_id == "yearly_comission"){
				jQuery("#chartContainer3").hide();
				jQuery("#chartContainer4").show();
				jQuery(".month_click").attr("id","month_comission");
				jQuery(".month_click").text("View Yearly");
			}
		});

		jQuery('select').on('change', function()
		{
			var optn_val=this.value;
		    jQuery(".dataTables_wrapper").hide();
		    jQuery("#"+optn_val+"_wrapper").show();

		});
		jQuery(".export_btn").click(function(){
		jQuery("#all-devices").table2excel({
		// exclude CSS class
		exclude: ".noExl",
		name: "All Devices",
		filename: "Devices" //do not include extension
		}); 
		});
		
		jQuery(document).on('click','.custom-button-class',function(){
			var ajaxurl=my_ajax_object.ajax_url;
			var data = {
				'action': 'clear_cache',
				
			};
			jQuery.post(ajaxurl, data, function(response) {
			alert('Your cache is cleared now.');
		});
			//
		});

			jQuery( 'input#_is_marketplace' ).change( function() {
				var is_gift_card = jQuery( 'input#_is_marketplace:checked' ).size();
				jQuery( '.show_if_is_marketplace' ).hide();
				jQuery( '.hide_if_is_marketplace' ).hide();
				if ( is_gift_card ) {
					jQuery( '.show_if_is_marketplace' ).show();
				}else{
					jQuery( '.hide_if_is_marketplace' ).hide();
					jQuery( 'ul.product_data_tabs li.general_options a' ).click();
						
				}
				
			});
			jQuery( 'input#_is_marketplace' ).trigger( 'change' );
	

			// Product meta box repeater fields
			jQuery( '#add-row-specs' ).on('click', function() {
				var row = jQuery( '.empty-row.screen-reader-text-specs' ).clone(true);
				row.removeClass( 'empty-row' );
				row.removeClass( 'screen-reader-text-specs' );
				row.removeClass( 'screen-reader-text' );
				row.insertBefore( '#repeatable-fieldset-specs tbody>tr:last' );
				return false;
			});
			jQuery( '#add-row-features' ).on('click', function() {
				var row = jQuery( '.empty-row.screen-reader-text-features' ).clone(true);
				row.removeClass( 'empty-row' );
				row.removeClass( 'screen-reader-text-features' );
				row.removeClass( 'screen-reader-text' );
				row.insertBefore( '#repeatable-fieldset-features tbody>tr:last' );
				return false;
			});
  	
			jQuery( '.remove-row' ).on('click', function() {
				jQuery(this).parents('tr').remove();
				return false;
			});
			
			jQuery( '.ms-bullet' ).on('click', function() {
				alert('hi');
			});
	//console.log(jQuery( window ).height());
	
	});

jQuery('#btn_return_product').on('click',function(){	
		alert("ok");
	});
