jQuery(document).ready(function($) {
	// Store Sidebar
	if( $('.left_sidebar').length > 0 ) {
		$left_sidebar_height = $('.left_sidebar').outerHeight();
		$right_side_height = $('.right_side').outerHeight();
		console.log( $left_sidebar_height + "::" + $right_side_height );
		if( $left_sidebar_height < $right_side_height ) {
			$('.left_sidebar').css( 'height', $('.right_side').outerHeight() );
		}
	}
		
  // Store Map
  $store_lat = jQuery("#store_lat").val();
	$store_lng = jQuery("#store_lng").val();
	$('#wcfmmp-store-map').css( 'height', $('#wcfmmp-store-map').outerWidth());
  function initialize() {
		var latlng = new google.maps.LatLng( $store_lat, $store_lng );
		var map = new google.maps.Map(document.getElementById("wcfmmp-store-map"), {
				center: latlng,
				blur : true,
				zoom: 15
		});
		var marker = new google.maps.Marker({
				map: map,
				position: latlng,
				draggable: true,
				anchorPoint: new google.maps.Point(0, -29)
		});
	}
	if( $('#wcfmmp-store-map').length > 0 ) {
		initialize();
	}
	
	// Review Ratings
	$('#stars li').on('mouseover', function(){
    var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on
   
    // Now highlight all the stars that's not after the current hovered star
    $(this).parent().children('li.star').each(function(e){
      if (e < onStar) {
        $(this).addClass('hover');
      }
      else {
        $(this).removeClass('hover');
      }
    });
    
  }).on('mouseout', function(){
    $(this).parent().children('li.star').each(function(e){
      $(this).removeClass('hover');
    });
  });
  
  
  /* Start Rating */
  $('.stars').each(function() {
  	$(this).find('li').on('mouseover', function(){
			var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on
		 
			// Now highlight all the stars that's not after the current hovered star
			$(this).parent().children('li.star').each(function(e){
				if (e < onStar) {
					$(this).addClass('hover');
				} else {
					$(this).removeClass('hover');
				}
			});
			
		}).on('mouseout', function(){
			$(this).parent().children('li.star').each(function(e){
				$(this).removeClass('hover');
			});
		});	
  		
  	$(this).find('li').on('click', function() {
      var onStar = parseInt($(this).data('value'), 10); // The star currently selected
      var stars = $(this).parent().children('li.star');
    
			for (i = 0; i < stars.length; i++) {
				$(stars[i]).removeClass('selected');
			}
    
			for (i = 0; i < onStar; i++) {
				$(stars[i]).addClass('selected');
			}
    
			// JUST RESPONSE (Not needed)
			var ratingValue = parseInt($(this).parent().find('li.selected').last().data('value'), 10);
			$(this).parent().parent().find('.rating_value').val(ratingValue);
			$(this).parent().parent().find('.rating_text').text(ratingValue);
		});
  });
  
  $('.store_rating_value').each(function() {
  	var onStar = parseInt($(this).val());
  	var stars = $(this).parent().children('i.fa-star');
  	for (i = 0; i < onStar; i++) {
			$(stars[i]).addClass('selected');
		}
  });
  
  // New Review 
	$('.reviews_area_live').addClass('wcfm_custom_hide');
  $('.reviews_area_dummy').find('button, input[type="text"]').click(function() {
  	if( wcfm_params.is_user_logged_in ) {
			$('.reviews_area_dummy').addClass('wcfm_custom_hide');
			$('.reviews_area_live').removeClass('wcfm_custom_hide');
		} else {
			alert(wcfm_core_dashboard_messages.user_non_logged_in);
		}
  });
  $('.reviews_area_live').find('a.cancel_review_add').click(function( event ) {
  	event.preventDefault();
  	if( wcfm_params.is_user_logged_in ) {
			$('.reviews_area_live').addClass('wcfm_custom_hide');
			$('.reviews_area_dummy').removeClass('wcfm_custom_hide');
		} else {
			alert(wcfm_core_dashboard_messages.user_non_logged_in);
		}
  	return false;
  });
  
  // Review form submit
	$('#wcfmmp_store_review_submit').click(function(event) {
	  event.preventDefault();
	  
	  var	wcfmmp_store_review_comment = $('#wcfmmp_store_review_comment').val();
  
	  // Validations
	  $is_valid = true;
	  
	  $('.wcfm-message').html('').removeClass('wcfm-error').removeClass('wcfm-success').slideUp();
		if(wcfmmp_store_review_comment.length == 0) {
			$is_valid = false;
			$('#wcfmmp_store_review_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>'+wcfm_reviews_messages.no_comment).addClass('wcfm-error').slideDown();
			wcfm_notification_sound.play();
		}
	  
	  if($is_valid) {
			$('#wcfmmp_store_review_form').block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});
			var data = {
				action                   : 'wcfm_ajax_controller',
				controller               : 'wcfm-reviews-submit',
				wcfm_store_review_form   : jQuery('#wcfmmp_store_review_form').serialize()
			}	
			$.post(wcfm_params.ajax_url, data, function(response) {
				if(response) {
					$response_json = $.parseJSON(response);
					$('.wcfm-message').html('').removeClass('wcfm-error').removeClass('wcfm-success').slideUp();
					wcfm_notification_sound.play();
					if($response_json.status) {
						$('#wcfmmp_store_review_comment').val('');
						$('#wcfmmp_store_review_form .wcfm-message').html('<span class="wcicon-status-completed"></span>' + $response_json.message).addClass('wcfm-success').slideDown( "slow" , function() {
						  //window.location = $response_json.redirect;
						  setTimeout(function() {
						  	$('.reviews_area_live').addClass('wcfm_custom_hide');
						  	$('.reviews_area_dummy').addClass('wcfm_custom_hide');
						  }, 2000);
						} );
					} else {
						$('#wcfmmp_store_review_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + $response_json.message).addClass('wcfm-error').slideDown();
					}
					$('#wcfmmp_store_review_form').unblock();
				}
			});	
		}
	});
});