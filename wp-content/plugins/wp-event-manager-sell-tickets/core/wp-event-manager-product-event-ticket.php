<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Event Package Product Type
 * WP_Event_Manager_WCPL_Product_Event_Package
 */
class WC_Product_Event_Ticket extends WP_Event_Manager_Event_Ticket_Product {
	/**
	 * Constructor
	 *
	 * @param int|WC_Product|object $product Product ID, post object, or product object
	 */
	public function __construct( $product ) {
		$this->product_type = 'event_ticket';
		parent::__construct( $product );
	}
	/**
	 * Get internal type.
	 *
	 * @return string
	 */
	public function get_type() {
		return 'event_ticket';
	}	
}
