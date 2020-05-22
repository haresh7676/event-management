<?php

$fee_enable     = get_option('wc_settings_fees_enable');

if($fee_enable== 'yes') :

$ticket_mode = get_post_meta($product_id,'_ticket_mode',true); 
$fee_label   = get_post_meta($product_id,'_fee_label',true);
$fee_value   = get_post_meta($product_id,'_fee_value',true);
$fee_mode    = get_post_meta($product_id,'_fee_mode',true);
$fee_type    = get_post_meta($product_id,'_fee_type',true);

$fee_mode    = $fee_mode == 'fee_per_order' ?  __('Fee Per Order' , 'wp-event-manager-sell-tickets')  : __('Fee Per Ticket' , 'wp-event-manager-sell-tickets') ;


     //Fee value 
    woocommerce_wp_text_input( 
    	array( 
    		'id'          => '_fee_value', 
    		'label'       => __( 'Default Fee', 'wp-event-manager-sell-tickets ' )
    	)
    );  
    
    //Fee label 
    woocommerce_wp_text_input( 
    	array( 
    		'id'          => '_fee_label', 
    		'label'       => __( 'Default Fee Label', 'wp-event-manager-sell-tickets ' )
    	)
    );
    
    //Fee Modes
    woocommerce_wp_select( 
    array( 
    	'id'      => '_fee_mode', 
    	'label'       => __( 'Default Fee Mode', 'wp-event-manager-sell-tickets' ), 
    	'options' => array(
    		'fee_per_order'   => __( 'Fee Per Order', 'wp-event-manager-sell-tickets ' ),
    		'fee_per_ticket'   => __( 'Fee Per Ticket', 'wp-event-manager-sell-tickets ' )
    		)
    	)
    );
    
    // Fee Types
    woocommerce_wp_select( 
    	array( 
    		'id'          => '_fee_type', 
    		'label'       => __( 'Fee Types', 'wp-event-manager-sell-tickets ' ),
    		'options'     => array(
                    		'fixed_fee'     => __( 'Fixed Fee', 'wp-event-manager-sell-tickets ' ),
                            'fee_in_percentage' => __( 'Percentage (%)', 'wp-event-manager-sell-tickets ' ),
                    	    )
    	)
    );
    
?>
<?php endif; ?>