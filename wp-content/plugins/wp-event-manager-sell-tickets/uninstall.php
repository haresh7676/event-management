<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}


$options = array('wc_settings_fees_enable',
                'wc_settings_fee_value',
                'wc_settings_fee_label',
                'wc_settings_fee_modes',
                'wc_settings_fee_types',
                'fee_settings_rules');

foreach ( $options as $option ) {
	delete_option( $option );
}

$all_fields = get_option( 'event_manager_form_fields', true );
if(is_array($all_fields)){
	$sell_tickets_fields = array('paid_tickets','free_tickets','donation_tickets');
	foreach ($sell_tickets_fields as $key => $value) {
		if(isset($all_fields['event'][$value]))
			unset($all_fields['event'][$value]);
	}
}
update_option('event_manager_form_fields', $all_fields);
