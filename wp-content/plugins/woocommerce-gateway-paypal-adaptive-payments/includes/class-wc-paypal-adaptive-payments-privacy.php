<?php
if ( ! class_exists( 'WC_Abstract_Privacy' ) ) {
	return;
}

class WC_PayPal_Adaptive_Payments_Privacy extends WC_Abstract_Privacy {
	/**
	 * Constructor
	 *
	 */
	public function __construct() {
		parent::__construct( __( 'PayPal Adaptive Payments', 'woocommerce-gateway-paypal-adaptive-payments' ) );

		$this->add_exporter( 'woocommerce-gateway-paypal-adaptive-payments-order-data', __( 'WooCommerce PP Adaptive Order Data', 'woocommerce-gateway-paypal-adaptive-payments' ), array( $this, 'order_data_exporter' ) );
		$this->add_eraser( 'woocommerce-gateway-paypal-adaptive-payments-order-data', __( 'WooCommerce PP Adaptive Data', 'woocommerce-gateway-paypal-adaptive-payments' ), array( $this, 'order_data_eraser' ) );
	}

	/**
	 * Returns a list of orders that are using PP adaptive payment method.
	 *
	 * @param string  $email_address
	 * @param int     $page
	 *
	 * @return array WP_Post
	 */
	protected function get_pp_adaptive_orders( $email_address, $page ) {
		$user = get_user_by( 'email', $email_address ); // Check if user has an ID in the DB to load stored personal data.

		$order_query    = array(
			'payment_method' => array( 'paypal-adaptive-payments' ),
			'limit'          => 10,
			'page'           => $page,
		);

		if ( $user instanceof WP_User ) {
			$order_query['customer_id'] = (int) $user->ID;
		} else {
			$order_query['billing_email'] = $email_address;
		}

		return wc_get_orders( $order_query );
	}

	/**
	 * Gets the message of the privacy to display.
	 *
	 */
	public function get_privacy_message() {
		return wpautop( sprintf( __( 'By using this extension, you may be storing personal data or sharing data with an external service. <a href="%s" target="_blank">Learn more about how this works, including what you may want to include in your privacy policy.</a>', 'woocommerce-gateway-paypal-adaptive-payments' ), 'https://docs.woocommerce.com/privacy/?woocommerce-gateway-paypal-adaptive-payments' ) );
	}

	/**
	 * Handle exporting data for Orders.
	 *
	 * @param string $email_address E-mail address to export.
	 * @param int    $page          Pagination of data.
	 *
	 * @return array
	 */
	public function order_data_exporter( $email_address, $page = 1 ) {
		$done           = false;
		$data_to_export = array();

		$orders = $this->get_pp_adaptive_orders( $email_address, (int) $page );

		$done = true;

		if ( 0 < count( $orders ) ) {
			foreach ( $orders as $order ) {
				$product_receivers = get_post_meta( $product_id, '_paypal_adaptive_receivers', true );
				$product_receivers = array_filter( explode( PHP_EOL, $product_receivers ) );

				if ( ! is_array( $product_receivers ) || empty( $product_receivers ) ) {
					continue;
				}

				foreach ( $product_receivers as $receiver ) {
					$receiver = array_map( 'sanitize_text_field', array_filter( explode( '|', $receiver ) ) );

					if ( ! is_array( $receiver ) || empty( $receiver ) || $receiver[0] != $email_address ) {
						continue;
					}

					$receiver_total = round( $line_total / 100 * $receiver[1], 2 );

					$data_to_export[] = array(
						'group_id'    => 'woocommerce_orders',
						'group_label' => __( 'Orders', 'woocommerce-gateway-paypal-adaptive-payments' ),
						'item_id'     => 'order-' . $order->get_id(),
						'data'        => array(
							array(
								'name'  => __( 'PP Adaptive Receivers total commission', 'woocommerce-gateway-paypal-adaptive-payments' ),
								'value' => $receiver_total,
							),
						),
					);
				}
			}

			$done = 10 > count( $orders );
		}

		return array(
			'data' => $data_to_export,
			'done' => $done,
		);
	}

	/**
	 * Finds and erases order data by email address.
	 *
	 * @since 3.4.0
	 * @param string $email_address The user email address.
	 * @param int    $page  Page.
	 * @return array An array of personal data in name value pairs
	 */
	public function order_data_eraser( $email_address, $page ) {
		$orders = $this->get_pp_adaptive_orders( $email_address, (int) $page );

		$items_removed  = false;
		$items_retained = false;
		$messages       = array();

		foreach ( (array) $orders as $order ) {
			$order = wc_get_order( $order->get_id() );

			list( $removed, $retained, $msgs ) = $this->maybe_handle_order( $order );
			$items_removed  |= $removed;
			$items_retained |= $retained;
			$messages        = array_merge( $messages, $msgs );
		}

		// Tell core if we have more orders to work on still
		$done = count( $orders ) < 10;

		return array(
			'items_removed'  => $items_removed,
			'items_retained' => $items_retained,
			'messages'       => $messages,
			'done'           => $done,
		);
	}

	/**
	 * Handle eraser of data tied to Orders
	 *
	 * @param WC_Order $order
	 * @return array
	 */
	protected function maybe_handle_order( $order ) {
		$order_id = $order->get_id();
		$pp_recvs = get_post_meta( $order_id, '_paypal_adaptive_receivers', true );

		if ( empty( $pp_recvs ) ) {
			return array( false, false, array() );
		}

		delete_post_meta( $order_id, '_paypal_adaptive_receivers' );

		return array( true, false, array( __( 'PayPal Adaptive Payments Order Data Erased.', 'woocommerce-gateway-paypal-adaptive-payments' ) ) );
	}
}

new WC_PayPal_Adaptive_Payments_Privacy();
