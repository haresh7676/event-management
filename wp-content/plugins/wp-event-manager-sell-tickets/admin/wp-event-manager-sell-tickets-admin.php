<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WP_Event_Manager_WCPL_Admin
 */
class WP_Event_Manager_Sell_Tickets_Admin {

	/** @var object Class Instance */
	private static $instance;

	/**
	 * Get the class instance
	 *
	 * @return static
	 */
	public static function get_instance() {
		return null === self::$instance ? ( self::$instance = new self ) : self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'woocommerce_subscription_product_types', array( $this, 'woocommerce_subscription_product_types' ) );
		add_filter( 'product_type_selector', array( $this, 'product_type_selector' ) );
		add_action( 'woocommerce_process_product_meta_event_ticket', array( $this, 'save_event_ticket_data' ),99 );
		//add_action( 'woocommerce_process_product_meta_event_package_subscription', array( $this, 'save_event_ticket_data' ) );
	
		add_action( 'woocommerce_product_options_general_product_data', array( $this, 'product_data' ) );
		add_filter( 'parse_query', array( $this, 'parse_query' ) );
	}



	/**
	 * Types for subscriptions
	 *
	 * @param  array $types
	 * @return array
	 */
	public function woocommerce_subscription_product_types( $types ) {
		$types[] = 'event_package_subscription';
		return $types;
	}

	/**
	 * Add the product type
	 *
	 * @param array $types
	 * @return array
	 */
	public function product_type_selector( $types ) {
		$types['event_ticket'] = __( 'Event Ticket', 'wp-event-manager-wc-paid-listings' );
		
		if ( class_exists( 'WC_Subscriptions' ) ) {
			$types['event_ticket_subscription'] = __( 'Event Ticket Subscription', 'wp-event-manager-wc-paid-listings' );
			
		}
		return $types;
	}

	/**
	 * Show the event package product options
	 */
	public function product_data() {
		global $post;
		$post_id = $post->ID;
		get_event_manager_template( 'html-event-ticket-data.php', array(), 'wp-event-manager-sell-tickets', EVENT_MANAGER_SELL_TICKETS_PLUGIN_DIR. '/templates/admin/' );

	}

	/**
	 * Save Event Package data for the product
	 *
	 * @param  int $post_id
	 */
	public function save_event_ticket_data( $post_id ) {
		$event_id 		            = isset( $_POST['event_id'] ) ? $_POST['event_id'] : '';
		$show_ticket_description 	= isset( $_POST['_show_ticket_description'] ) ? $_POST['_show_ticket_description'] : '';
		$ticket_fee_pay_by 	        = isset( $_POST['_ticket_fee_pay_by'] ) ? $_POST['_ticket_fee_pay_by'] : '';
		$minimum_tickets 	        = isset( $_POST['minimum_order'] ) ? $_POST['minimum_order'] : '';
		$maximum_tickets 	        = isset( $_POST['maximum_order'] ) ? $_POST['maximum_order'] : '';
		$remaining_tickets 	        = isset( $_POST['_remaining_tickets'] ) ? $_POST['_remaining_tickets'] : '';
		$ticket_visibility 	        = isset( $_POST['ticket_visibility'] ) ? $_POST['ticket_visibility'] : '';
		$stock 	        = isset( $_POST['_stock'] ) ? $_POST['_stock'] : '';
		$regular_price 	        = isset( $_POST['_regular_price'] ) ? $_POST['_regular_price'] : '';
		$show_ticket_description 	        = isset( $_POST['_show_ticket_description'] ) ? $_POST['_show_ticket_description'] : '';
		$ticket_fee_pay_by 	        = isset( $_POST['ticket_fee_pay_by'] ) ? $_POST['ticket_fee_pay_by'] : '';
		$ticket_fee_pay_by 	        = isset( $_POST['_sold_individually'] ) ? $_POST['_sold_individually'] : '';
		 
		$ticket_sales_start_date = isset( $_POST['_ticket_sales_start_date'] ) ? $_POST['_ticket_sales_start_date'] : '';
		$sales_start_time 	= isset( $_POST['_ticket_sales_start_time'] ) ? $_POST['_ticket_sales_start_time'] : '';
		$ticket_sales_end_date   = isset( $_POST['_ticket_sales_end_date'] ) ? $_POST['_ticket_sales_end_date'] : '';
		$sales_end_time 	= isset( $_POST['_ticket_sales_end_time'] ) ? $_POST['_ticket_sales_end_time'] : '';
		
		 
		$paid_tickets       = get_post_meta($event_id,'_paid_tickets',true );
		$free_tickets       = get_post_meta($event_id,'_free_tickets',true);
		$donation_tickets   = get_post_meta($event_id,'_donation_tickets',true);
		 
		/**
		 * Below three ticket type will be updated in event meta key
		 * for all three type of ticket (paid,free and donation ) it will fetch the current ticket details and update perticular ticket
		 * which is currently updating from admin side product.
		 *
		*/
		//paid ticket
		if( !empty($paid_tickets) && is_array( $paid_tickets )  )
		{
		
			foreach ( $paid_tickets as $key => $ticket )
			{
				if(isset($ticket['product_id']) &&  $ticket['product_id'] == $post_id ){
					$paid_tickets[$key]['ticket_name']                       = get_the_title($post_id);
					$paid_tickets[$key]['ticket_visibility']                 =  $ticket_visibility;
					$paid_tickets[$key]['ticket_quantity']                   = $stock;
					$paid_tickets[$key]['ticket_price']                      = $regular_price;
					$paid_tickets[$key]['ticket_sales_start_date']           = $ticket_sales_start_date;
					$paid_tickets[$key]['ticket_sales_start_time']           = $sales_start_time;
					$paid_tickets[$key]['ticket_sales_end_date']             = $ticket_sales_end_date;
					$paid_tickets[$key]['ticket_sales_end_time']             = $sales_end_time;
					$paid_tickets[$key]['ticket_description']                = get_post_field('post_content', $post_id);
					$paid_tickets[$key]['ticket_show_description']           = $show_ticket_description;
					$paid_tickets[$key]['ticket_minimum']                    = $minimum_tickets;
					$paid_tickets[$key]['ticket_maximum']                    = $maximum_tickets;
					$paid_tickets[$key]['show_remaining_tickets']            = $remaining_tickets;
					$paid_tickets[$key]['ticket_individually']               = $ticket_fee_pay_by;
		
				}
				else
					continue;
			}
			 
			update_post_meta($event_id,'_paid_tickets',$paid_tickets );
		}
		
		//free ticket
		if( !empty($free_tickets) && is_array( $free_tickets )  )
		{
		
			foreach ( $free_tickets as $key => $ticket )
			{
				if(isset($ticket['product_id']) &&  $ticket['product_id'] == $post_id ){
					$free_tickets[$key]['ticket_name']                       = get_the_title($post_id);
					$free_tickets[$key]['ticket_visibility']                 =  $ticket_visibility;
					$free_tickets[$key]['ticket_quantity']                   = $stock;
					$free_tickets[$key]['ticket_price']                      = $regular_price;
					$free_tickets[$key]['ticket_sales_start_date']           = $ticket_sales_start_date;
					$free_tickets[$key]['ticket_sales_start_time']           = $sales_start_time;
					$free_tickets[$key]['ticket_sales_end_date']             = $ticket_sales_end_date;
					$free_tickets[$key]['ticket_sales_end_time']             = $sales_end_time;
					$free_tickets[$key]['ticket_description']                = get_post_field('post_content', $post_id);
					$free_tickets[$key]['ticket_show_description']           = $show_ticket_description;
					$free_tickets[$key]['ticket_minimum']                    = $minimum_tickets;
					$free_tickets[$key]['ticket_maximum']                    = $maximum_tickets;
					$free_tickets[$key]['show_remaining_tickets']            = $remaining_tickets;
					$free_tickets[$key]['ticket_individually']               = $ticket_fee_pay_by;
		
				}
				else
					continue;
			}
			update_post_meta($event_id,'_free_tickets',$free_tickets );
		}
		//donation ticket
		if( !empty($donation_tickets) && is_array( $donation_tickets )  )
		{
		
			foreach ( $donation_tickets as $key => $ticket )
			{
				if(isset($ticket['product_id']) &&  $ticket['product_id'] == $post_id ){
					$donation_tickets[$key]['ticket_name']                       = get_the_title($post_id);
					$donation_tickets[$key]['ticket_visibility']                 =  $ticket_visibility;
					$donation_tickets[$key]['ticket_quantity']                   = $stock;
					$donation_tickets[$key]['ticket_price']                      = $regular_price;
					$donation_tickets[$key]['ticket_sales_start_date']           = $ticket_sales_start_date;
					$donation_tickets[$key]['ticket_sales_start_time']           = $sales_start_time;
					$donation_tickets[$key]['ticket_sales_end_date']             = $ticket_sales_end_date;
					$donation_tickets[$key]['ticket_sales_end_time']             = $sales_end_time;
					$donation_tickets[$key]['ticket_description']                = get_post_field('post_content', $post_id);
					$donation_tickets[$key]['ticket_show_description']           = $show_ticket_description;
					$donation_tickets[$key]['ticket_minimum']                    = $minimum_tickets;
					$donation_tickets[$key]['ticket_maximum']                    = $maximum_tickets;
					$donation_tickets[$key]['show_remaining_tickets']            = $remaining_tickets;
					$donation_tickets[$key]['ticket_individually']               = $ticket_fee_pay_by;
		
				}
				else
					continue;
			}
			update_post_meta($event_id,'_donation_tickets',$donation_tickets );
		}
	}


	/**
	 * Filters and sorting handler
	 *
	 * @param  WP_Query $query
	 * @return WP_Query
	 */
	public function parse_query( $query ) {
		global $typenow;

		if ( 'event_listing' === $typenow ) {
			if ( isset( $_GET['package'] ) ) {
				$query->query_vars['meta_key']   = '_user_package_id';
				$query->query_vars['meta_value'] = absint( $_GET['package'] );
			}
		}

		return $query;
	}
}
WP_Event_Manager_Sell_Tickets_Admin::get_instance();
