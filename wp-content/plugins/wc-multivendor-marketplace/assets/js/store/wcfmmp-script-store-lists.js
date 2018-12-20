jQuery(document).ready(function($) {
	var form = $('.wcfmmp-store-search-form');
	var xhr;
	var timer = null;
	
	if( $('.wcfmmp-store-search-form').length > 0 ) {

		form.on('keyup', '#search', function() {
			var self = $(this),
				data = {
					search_term: self.val(),
					action: 'wcfmmp_stores_list_search',
					pagination_base: form.find('#pagination_base').val(),
					per_row: $per_row,
					_wpnonce: form.find('#nonce').val()
				};
	
			if (timer) {
				clearTimeout(timer);
			}
	
			if ( xhr ) {
				xhr.abort();
			}
	
			timer = setTimeout(function() {
				form.find('.wcfmmp-overlay').show();
	
				xhr = $.post(wcfm_params .ajax_url, data, function(response) {
					if (response.success) {
						form.find('.wcfmmp-overlay').hide();
	
						var data = response.data;
						$('#wcfmmp-stores-wrap').html( $(data).find( '.wcfmmp-stores-content' ) );
					}
				});
			}, 500);
		} );
	}
});