<?php
class WP_Event_Manager_Sell_Tickets_WC_Settings_Tab_Fees {
    /**
     * WP_Event_Manager_Sell_Tickets_WC_Settings_Tab_Fees  the class and hooks required actions & filters.
     *
     */
    public function __construct() {
       add_filter( 'woocommerce_settings_tabs_array', array( $this ,'add_settings_tab'), 50 );
       add_action( 'woocommerce_settings_tabs_settings_fees',array( $this , 'settings_tab') );
       add_action( 'woocommerce_update_options_settings_fees',array( $this ,'update_settings') );       
      
    }
    
    /**
     * Add a new settings tab to the WooCommerce settings tabs array.
     *
     * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Subscription tab.
     * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Subscription tab.
     */
    public  function add_settings_tab( $settings_tabs ) {
        $settings_tabs['settings_fees'] = __( 'Fees', 'wp-event-manager-sell-tickets' );
        
        return $settings_tabs;
    }
    
    /**
     * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
     *
     * @uses woocommerce_admin_fields()
     * @uses self::get_settings()
     */
    public function settings_tab($value) {
        woocommerce_admin_fields( $this->get_settings() );    
        
        //add dynamic rules table
        WP_Event_Manager_Sell_Tickets_WC_Settings_Tab_Fees::wc_settings_rules_dynamic_field_table($value);    
    }
    
    /**
     * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
     *
     * @uses woocommerce_update_options()
     * @uses self::get_settings()
     */
    public function update_settings($value) {
        woocommerce_update_options( $this->get_settings() );
        
        //update dynamic rules
        WP_Event_Manager_Sell_Tickets_WC_Settings_Tab_Fees::woocommerce_update_options_dynamic_field_table($value);
    }
    
    /**
     * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
     *
     * @return array Array of settings for @see woocommerce_admin_fields() function.
     */
    public function get_settings() {
        $settings = array(
            'section_title' => array(
                'name'     => __( 'Fees', 'wp-event-manager-sell-tickets' ),
                'id'       => 'wc_settings_tab_fee_section_title',
                'type'     => 'title',
                'desc'     => ''               
                
            ),
            'fee_enable' => array(
                'name' => __( 'Enable', 'wp-event-manager-sell-tickets ' ),
                'id'   => 'wc_settings_fees_enable',               
                'std'  => '0', // WooCommerce < 2.0
                'default' => '0', // WooCommerce >= 2.0
                'type' => 'checkbox',                
                'desc' => __('Enable Fee Processing','wp-event-manager-sell-tickets' )
            ),
            'fee_value' => array(
                'name' => __( 'Default Fee', 'wp-event-manager-sell-tickets ' ),                
                'id'   => 'wc_settings_fee_value',
                'std'  => '2', // WooCommerce < 2.0
                'default' => '2', // WooCommerce >= 2.0                
                'desc'    => __( 'Please enter just number or decimal with two points. e.g 5 or 2.5 or 1.5 etc', 'wp-event-manager-sell-tickets' ),                
                'type' => 'text'
                
            ),
            'fee_label' => array(
                'name' => __( 'Default Fee Label', 'wp-event-manager-sell-tickets ' ),
                'id'   => 'wc_settings_fee_label',
                'std'  => __( 'Fee', 'wp-event-manager-sell-tickets ' ),     // WooCommerce < 2.0
                'default' =>  __( 'Fee', 'wp-event-manager-sell-tickets ' ), // WooCommerce >= 2.0                
                'type' => 'text'               
                
            ),
            'maximum_fee' => array(
                'name' => __( 'Maximum Fee', 'wp-event-manager-sell-tickets ' ),
                'id'   => 'wc_settings_maximum',
                'std'  => __( 'Maximum Fee', 'wp-event-manager-sell-tickets ' ),     // WooCommerce < 2.0
                'default' =>  '10', // WooCommerce >= 2.0
                'type' => 'text'
            ),
            'fee_modes' => array(
                'name' => __( 'Default Fee Mode', 'wp-event-manager-sell-tickets ' ),
                'id'   => 'wc_settings_fee_modes',
                'std'  => 'fee_per_order',    // WooCommerce < 2.0
                'default' => 'fee_per_order', // WooCommerce >= 2.0      
                'type' => 'select',                
                'options'  => array(
                                    'fee_per_order' =>__( 'Fee Per Order', 'wp-event-manager-sell-tickets ' ), 
                                    'fee_per_ticket' => __( 'Fee Per Ticket', 'wp-event-manager-sell-tickets ' ),
                               )
            ),
            'fee_types' => array(
                'name' => __( 'Fee Types', 'wp-event-manager-sell-tickets ' ),
                'id'   => 'wc_settings_fee_types',              
                'std'  => 'fixed_fee',    // WooCommerce < 2.0
                'default' => 'fixed_fee', // WooCommerce >= 2.0      
                'type' => 'select',                
                'options'  => array(
                                    'fixed_fee'     => __( 'Fixed Fee', 'wp-event-manager-sell-tickets ' ),
                                    'fee_in_percentage' => __( 'Percentage (%)', 'wp-event-manager-sell-tickets' ),
                              )
            ),
            
            'section_end' => array(
                 'type' => 'sectionend',
                 'id' => 'wc_settings_tab_fee_section_end'
            ),     
            
        );
        return apply_filters( 'event_manager_sell_tickets_fees_settings', $settings ); 
    }
    
/**
* Woocommerce Fees tab Table settings section
* This function add the table after the fees settings
* All the fields will be generated using jquery.
* This will add fee per country
*/ 
public function wc_settings_rules_dynamic_field_table($value){
	
	?>
	<table class="fee_settings_rules wc_input_table sortable widefat">
	  <thead>
	      <tr>
	        <th class="sort">&nbsp;</th>
	        <th><a href="http://en.wikipedia.org/wiki/ISO_3166-1#Current_codes" target="_blank" ><?php _e( 'Country Code','wp-event-manager-sell-tickets ' ); ?></a>&nbsp;<?php echo wc_help_tip( __( 'A 2 digit country code, e.g. US. Please do not enter blank value.', 'wp-event-manager-sell-tickets' ) ); ?></th>
		 <th><?php _e( 'Fee Mode','wp-event-manager-sell-tickets ' ); ?></th>
		 <th><?php _e( 'Fee','wp-event-manager-sell-tickets ' ); ?></th>
		 <th><?php _e( 'Maximum Fee','wp-event-manager-sell-tickets ' ); ?></th>
		 <th><?php _e( 'Fee Label','wp-event-manager-sell-tickets ' ); ?></th>
		 <th><?php _e( 'Fee Type','wp-event-manager-sell-tickets ' ); ?></th>
	      </tr>
           </thead>
	 <tbody id="fees">
	  <?php
	     $fee_settings_rules = get_option('fee_settings_rules',array());  
	     foreach ( $fee_settings_rules as $data ) {?>
	  <tr>
	    <td class="sort"></td>
	    <td class="country">
	      <input type="text" name="fee_settings_rules[fee_country][]" value="<?php  _e($data['fee_country'],'wp-event-manager-sell-tickets'); ?>" placeholder="*" style="text-transform:uppercase"  class="wc_input_country_iso ui-autocomplete-input" data-attribute="country_fee"/>
	    </td>
	    <td>
              <select class="fee_settings_rules_fee_mode" name="fee_settings_rules[fee_mode][]" >
		 <option value="fee_per_order" <?php if($data['fee_mode'] == 'fee_per_order') {echo 'selected="selected"';} ?>><?php  _e( 'Fee Per Order', 'wp-event-manager-sell-tickets ' ); ?> </option>
		 <option value="fee_per_ticket" <?php if($data['fee_mode'] == 'fee_per_ticket') {echo 'selected="selected"';} ?>><?php _e( 'Fee Per Ticket', 'wp-event-manager-sell-tickets ' ); ?></option>
	       </select>
	    </td>
	   <td>
	     <input type="text" value="<?php echo esc_attr( $data['fee_value'] ) ?>"  name="fee_settings_rules[fee_value][]"  />
	   </td>
	   <td>
	      <input type="text" value="<?php echo esc_attr( $data['maximum_fee'] ) ?>"  name="fee_settings_rules[maximum_fee][]"   />
	   </td>
	   <td>
	      <input type="text" value="<?php echo esc_attr( $data['fee_label'] ) ?>"  name="fee_settings_rules[fee_label][]"   />
	   </td>

	    <td>
           <select class="fee_settings_rules_fee_mode" name="fee_settings_rules[fee_type][]" >
    		    <option value="fixed_fee" <?php if($data['fee_type'] == 'fixed_fee') {echo 'selected="selected"';} ?>><?php  _e( 'Fixed Fee', 'wp-event-manager-sell-tickets ' ); ?> </option>
    		    <option value="fee_in_percentage" <?php if($data['fee_type'] == 'fee_in_percentage') {echo 'selected="selected"';} ?>><?php _e( 'Percentage (%)', 'wp-event-manager-sell-tickets' ); ?></option>
	       </select>
	    </td>
	 </tr>
	 <?php } ?>
	</tbody>
        <tfoot>
            <tr>
	      <th colspan="10">
		<a href="#" class="button plus insert"><?php _e( 'Insert new rule', 'wp-event-manager-sell-tickets' ); ?></a>
		<a href="#" class="button minus remove_item"><?php _e( 'Remove selected rule(s)', 'wp-event-manager-sell-tickets' ); ?></a>
	      </th>
	    </tr>
	</tfoot>
    </table>
    
    <script type="text/javascript">
	jQuery( function() {
				jQuery('.fee_settings_rules .remove_item').click(function() {
					var $tbody = jQuery('.fee_settings_rules').find('tbody');
					if ( $tbody.find('tr.current').size() > 0 ) {
						$current = $tbody.find('tr.current');
						$current.remove();
						
					} else {
						alert('<?php echo esc_js( __( 'No row(s) selected', 'wp-event-manager-sell-tickets' ) ); ?>');
					}
					return false;
				});
				jQuery('.fee_settings_rules .insert').click(function() {
					var $tbody = jQuery('.fee_settings_rules').find('tbody');
					var size = $tbody.find('tr').size();
					var code = '<tr class="new">\
					                <td class="sort"></td>\
							<td class="country"><input type="text" name="fee_settings_rules[fee_country][]" placeholder="*" style="text-transform:uppercase" class="wc_input_country_iso ui-autocomplete-input" data-attribute="country_fee"></td>\
							<td>\
								<select name="fee_settings_rules[fee_mode][]">\
								<option  value="fee_per_order"><?php _e( 'Fee Per Order', 'wp-event-manager-sell-tickets ' ) ?></option>\
								<option  value="fee_per_ticket"><?php _e( 'Fee Per Ticket', 'wp-event-manager-sell-tickets ' )?></option>\
								</select>\
							</td>\
							<td><input type="text"  name="fee_settings_rules[fee_value][]" placeholder="*" /></td>\
							<td><input type="text"  name="fee_settings_rules[maximum_fee][]" placeholder="*" /></td>\
							<td><input type="text"  name="fee_settings_rules[fee_label][]" placeholder="*" /></td>\
							<td>\
							<select name="fee_settings_rules[fee_type][]">\
							    <option  value="fixed_fee"><?php _e( 'Fixed Fee', 'wp-event-manager-sell-tickets ' ); ?></option>\
							    <option  value="fee_in_percentage"><?php _e( 'Percentage (%)', 'wp-event-manager-sell-tickets' );?></option>\
							</select>\
							</td>\
						</tr>';
					if ( $tbody.find('tr.current').size() > 0 ) {
						$tbody.find('tr.current').after( code );
					} else {
						$tbody.append( code );
					}
					return false;
				});
				jQuery('.fee_settings_rules').on('click','.fee_settings_rules_default_fee_mode',function() {
					jQuery('.fee_settings_rules_default').val('');
					jQuery(this).siblings('.fee_settings_rules_default').val('yes');
				});
				
				var availableCountries = [<?php
					$countries = array();
					foreach ( WC()->countries->get_allowed_countries() as $value => $label )
						$countries[] = '{ label: "' . $label . '", value: "' . $value . '" }';
					echo implode( ', ', $countries );
				?>];
				
				jQuery( "td.country input" ).autocomplete({
					            source: availableCountries,
					            minLength: 3
		             });
		 });
	   </script>
	<?php
	
}

/*
* Update Rules option in fee_settings_rules
* Get the all the fields from the form and update the option in array
* Get the fields from the rules table
*/
public function woocommerce_update_options_dynamic_field_table($value){
    	$all_rules_fields = empty($_POST['fee_settings_rules']) ? array() : $_POST['fee_settings_rules'] ;
    	
    	$fee_settings_rules = array(); 
    
    	foreach($all_rules_fields as $field_column_name => $data )
    	{
    	    
    		foreach( $data as $key => $value )
    		{
    			$fee_settings_rules[$key][$field_column_name] = $value;    			
    		}
    		
    	}
    
       update_option('fee_settings_rules',$fee_settings_rules);  
    
}

}
$GLOBALS['event_manager_sell_tickets_wc_settings_tab_fees'] = new WP_Event_Manager_Sell_Tickets_WC_Settings_Tab_Fees();