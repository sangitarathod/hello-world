jQuery(document).ready(function($) {
	$('#vendor_commission_mode').change(function() {
		$vendor_commission_mode = $(this).val();
		$('.commission_mode_field').addClass('wcfm_ele_hide');
		$('.commission_mode_'+$vendor_commission_mode).removeClass('wcfm_ele_hide');
		resetCollapsHeight($('#vendor_commission_mode').parent());
	}).change();
	
	function addVariationCommissionProperty() {
		$('.var_commission_mode').each(function() {
			$(this).change(function() {
				$vendor_commission_mode = $(this).val();
				$(this).parent().find('.var_commission_mode_field').addClass('wcfm_custom_hide');
				$(this).parent().find('.var_commission_mode_'+$vendor_commission_mode).removeClass('wcfm_custom_hide');
				resetCollapsHeight($('#variations'));
			}).change();
		});
	}
	addVariationCommissionProperty();
	
	$('#withdrawal_payment_methods').find('.payment_options').each(function() {
		$(this).change(function() {
			$payment_option = $(this).val();
			if( $(this).is(':checked') ) {
				$('.withdrawal_mode_'+$payment_option).removeClass('wcfm_ele_hide');
			} else {
				$('.withdrawal_mode_'+$payment_option).addClass('wcfm_ele_hide');
			}
		}).change();
	});
	
	$('#withdrawal_test_mode').change(function() {
		if( $(this).is(':checked') ) {
			$('.withdrawal_mode_live').addClass('wcfm_custom_hide');
			$('.withdrawal_mode_test').removeClass('wcfm_custom_hide');
		} else {
			$('.withdrawal_mode_live').removeClass('wcfm_custom_hide');
			$('.withdrawal_mode_test').addClass('wcfm_custom_hide');
		}
	}).change();
	
	$('#withdrawal_charge_type').change(function() {
		$withdrawal_charge_type = $(this).val();
		if( $withdrawal_charge_type == 'no' ) {
			$('.withdraw_charge_block').addClass('wcfm_custom_hide');
		} else {
			$('.withdraw_charge_block').removeClass('wcfm_custom_hide');
			$('.withdraw_charge_field').addClass('wcfm_ele_hide');
			$('.withdraw_charge_'+$withdrawal_charge_type).removeClass('wcfm_ele_hide');
		}
	}).change();
	
	// Gateway specific charge option
	$('#withdrawal_payment_methods').find('.payment_options').each(function() {
		$(this).change(function() {
			$payment_option = $(this).val();
			if( $(this).is(':checked') ) {
				$('.withdraw_charge_'+$payment_option).removeClass('wcfm_ele_hide');
			} else {
				$('.withdraw_charge_'+$payment_option).addClass('wcfm_ele_hide');
			}
		}).change();
	});
	
	// Vendor Payment Method Specific charge Option
	$('#payment_mode').change(function() {
		$vendor_payment_mode = $(this).val();
		$('.withdraw_charge_block').addClass('wcfm_block_hide');
		$('.withdraw_charge_'+$vendor_payment_mode).removeClass('wcfm_block_hide');
		resetCollapsHeight($('#vendor_withdrawal_mode').parent());
	}).change();
	
	$('#vendor_withdrawal_mode').change(function() {
		$vendor_withdrawal_mode = $(this).val();
		$('.withdrawal_mode_field').addClass('wcfm_ele_hide');
		$('.withdrawal_mode_'+$vendor_withdrawal_mode).removeClass('wcfm_ele_hide');
		if( $vendor_withdrawal_mode != 'global' ) {
			$('#withdrawal_charge_type').change();
		}
		resetCollapsHeight($('#vendor_withdrawal_mode').parent());
	}).change();
});