<?php
/**
 * PayPal Adaptive Payments Gateway.
 *
 * @package  WC_PayPal_Adaptive_Payments_Gateway
 * @category Gateway
 * @author   WooThemes
 */

class WC_PayPal_Adaptive_Payments_Gateway extends WC_Payment_Gateway {

	/**
	 * Init and hook in the gateway.
	 */
	public function __construct() {
		global $woocommerce;

		$this->id                  = 'paypal-adaptive-payments';
		$this->icon                = apply_filters( 'woocommerce_paypal_ap_icon', plugins_url( 'assets/images/paypal.png', plugin_dir_path( __FILE__ ) ) );
		$this->has_fields          = false;
		$this->method_title        = __( 'PayPal Adaptive Payments', 'woocommerce-gateway-paypal-adaptive-payments' );
        $this->method_description  = __( 'PayPal Adaptive Payments handles payments between a sender of a payment and one or more receivers of the payment. It’s possible to split the order total with secondary receivers, so you can pay commissions or partners.');
		$this->order_button_text   = __( 'Proceed to PayPal', 'woocommerce-gateway-paypal-adaptive-payments' );

		// API URLs.
		$this->api_prod_url        = 'https://svcs.paypal.com/AdaptivePayments/';
		$this->api_sandbox_url     = 'https://svcs.sandbox.paypal.com/AdaptivePayments/';
		$this->payment_prod_url    = 'https://www.paypal.com/cgi-bin/webscr';
		$this->payment_sandbox_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		$this->notify_url          = WC()->api_request_url( 'WC_PayPal_Adaptive_Payments_Gateway' );

		// Load the form fields.
		$this->init_form_fields();

		// Load the settings.
		$this->init_settings();

		// Define user set variables.
		$this->title          = $this->get_option( 'title' );
		$this->description    = $this->get_option( 'description' );
		$this->api_username   = $this->get_option( 'api_username' );
		$this->api_password   = $this->get_option( 'api_password' );
		$this->api_signature  = $this->get_option( 'api_signature' );
		$this->app_id         = $this->get_option( 'app_id' );
		$this->receiver_email = $this->get_option( 'receiver_email' );
		$this->method         = $this->get_option( 'method' );
		$this->invoice_prefix = $this->get_option( 'invoice_prefix', 'WC-' );
		$this->header_image   = $this->get_option( 'header_image', '' );
		$this->sandbox        = $this->get_option( 'sandbox' );
		$this->debug          = $this->get_option( 'debug' );

		add_action( 'woocommere_paypal_adaptive_payments_ipn', array( $this, 'process_ipn' ) );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_api_wc_paypal_adaptive_payments_gateway', array( $this, 'check_ipn_response' ) );

		// Active logs.
		if ( 'yes' == $this->debug ) {
			if ( class_exists( 'WC_Logger' ) ) {
				$this->log = new WC_Logger();
			} else {
				$this->log = $woocommerce->logger();
			}
		}

		$this->admin_notices();
	}

	/**
	 * Returns a bool that indicates if currency is amongst the supported ones.
	 *
	 * @return bool
	 */
	public function using_supported_currency() {
		if ( ! in_array( get_woocommerce_currency(), apply_filters( 'woocommerce_paypal_ap_supported_currencies', array( 'AUD', 'BRL', 'CAD', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF', 'ILS', 'JPY', 'MYR', 'MXN', 'NOK', 'NZD', 'PHP', 'PLN', 'GBP', 'SGD', 'SEK', 'CHF', 'TWD', 'THB', 'TRY', 'USD' ) ) ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Returns a value indicating the the Gateway is available or not. It's called
	 * automatically by WooCommerce before allowing customers to use the gateway
	 * for payment.
	 *
	 * @return bool
	 */
	public function is_available() {
		// Test if is valid for use.
		$available = parent::is_available() &&
					! empty( $this->api_username ) &&
					! empty( $this->api_password ) &&
					! empty( $this->api_signature ) &&
					! empty( $this->app_id ) &&
					! empty( $this->receiver_email ) &&
					$this->using_supported_currency();

		return $available;
	}

	/**
	 * Displays notifications when the admin has something wrong with the configuration.
	 */
	protected function admin_notices() {
		if ( is_admin() ) {
			// Checks if email is not empty.
			if ( 'yes' == $this->get_option( 'enabled' ) && (
					empty( $this->api_username )
					|| empty( $this->api_password )
					|| empty( $this->api_signature )
					|| empty( $this->app_id )
					|| empty( $this->receiver_email )
				)
			) {
				add_action( 'admin_notices', array( $this, 'plugin_not_configured_message' ) );
			}

			// Checks that the currency is supported
			if ( ! $this->using_supported_currency() ) {
				add_action( 'admin_notices', array( $this, 'currency_not_supported_message' ) );
			}
		}
	}

	/**
	 * Initialise Gateway Settings Form Fields.
	 */
	public function init_form_fields() {

		$this->form_fields = array(
			'enabled' => array(
				'title'   => __( 'Enable/Disable', 'woocommerce-gateway-paypal-adaptive-payments' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable PayPal Adaptive Payments', 'woocommerce-gateway-paypal-adaptive-payments' ),
				'default' => 'yes'
			),
			'title' => array(
				'title'       => __( 'Title', 'woocommerce-gateway-paypal-adaptive-payments' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce-gateway-paypal-adaptive-payments' ),
				'default'     => __( 'PayPal', 'woocommerce-gateway-paypal-adaptive-payments' ),
				'desc_tip'    => true,
			),
			'description' => array(
				'title'       => __( 'Description', 'woocommerce-gateway-paypal-adaptive-payments' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce-gateway-paypal-adaptive-payments' ),
				'default'     => __( 'Pay via PayPal; you can pay with your credit card if you don\'t have a PayPal account', 'woocommerce-gateway-paypal-adaptive-payments' )
			),
			'api_username' => array(
				'title'       => __( 'PayPal API Username', 'woocommerce-gateway-paypal-adaptive-payments' ),
				'type'        => 'text',
				'description' => __( 'Please enter your PayPal API username; this is needed in order to take payment.', 'woocommerce-gateway-paypal-adaptive-payments' ),
				'default'     => '',
				'desc_tip'    => true,
			),
			'api_password' => array(
				'title'       => __( 'PayPal API Password', 'woocommerce-gateway-paypal-adaptive-payments' ),
				'type'        => 'text',
				'description' => __( 'Please enter your PayPal API password; this is needed in order to take payment.', 'woocommerce-gateway-paypal-adaptive-payments' ),
				'default'     => '',
				'desc_tip'    => true,
			),
			'api_signature' => array(
				'title'       => __( 'PayPal API Signature', 'woocommerce-gateway-paypal-adaptive-payments' ),
				'type'        => 'text',
				'description' => __( 'Please enter your PayPal API signature; this is needed in order to take payment.', 'woocommerce-gateway-paypal-adaptive-payments' ),
				'default'     => '',
				'desc_tip'    => true,
			),
			'app_id' => array(
				'title'       => __( 'PayPal Application ID', 'woocommerce-gateway-paypal-adaptive-payments' ),
				'type'        => 'text',
				'description' => __( 'Please enter your PayPal Application ID; you need create an application on PayPal.', 'woocommerce-gateway-paypal-adaptive-payments' ),
				'default'     => '',
				'desc_tip'    => true,
			),
			'receiver_email' => array(
				'title'       => __( 'Receiver Email', 'woocommerce-gateway-paypal-adaptive-payments' ),
				'type'        => 'email',
				'description' => __( 'Input your main receiver email for your PayPal account.', 'woocommerce-gateway-paypal-adaptive-payments' ),
				'default'     => '',
				'desc_tip'    => true,
				'placeholder' => 'you@youremail.com'
			),
			'method' => array(
				'title'       => __( 'Payment Method', 'woocommerce-gateway-paypal-adaptive-payments' ),
				'type'        => 'select',
				'description' => __( 'Select the payment method: Parallel Payment - payment from a sender that is split directly among 2-6 receivers or Chained Payment - payment from a sender that is indirectly split among 1-9 secondary receivers.', 'woocommerce-gateway-paypal-adaptive-payments' ),
				'default'     => 'parallel',
				'desc_tip'    => true,
				'options'     => array(
					'parallel' => __( 'Parallel Payment', 'woocommerce-gateway-paypal-adaptive-payments' ),
					'chained'  => __( 'Chained Payment', 'woocommerce-gateway-paypal-adaptive-payments' )
				)
			),
			'invoice_prefix' => array(
				'title'       => __( 'Invoice Prefix', 'woocommerce-gateway-paypal-adaptive-payments' ),
				'type'        => 'text',
				'description' => __( 'Please enter a prefix for your invoice numbers. If you use your PayPal account for multiple stores ensure this prefix is unique as PayPal will not allow orders with the same invoice number.', 'woocommerce-gateway-paypal-adaptive-payments' ),
				'default'     => 'WC-',
				'desc_tip'    => true,
			),
			'design' => array(
				'title'       => __( 'Design', 'woocommerce-gateway-paypal-adaptive-payments' ),
				'type'        => 'title',
				'description' => '',
			),
			'header_image' => array(
				'title'       => __( 'Header Image (optional)', 'woocommerce-gateway-paypal-adaptive-payments' ),
				'type'        => 'text',
				'description' => __( 'The URL of an image that displays in the header of a payment page. The URL cannot exceed 1,024 characters. The image dimensions are 90 pixels high x 750 pixels wide.', 'woocommerce-gateway-paypal-adaptive-payments' ),
				'default'     => '',
				'desc_tip'    => true,
			),
			'testing' => array(
				'title'       => __( 'Gateway Testing', 'woocommerce-gateway-paypal-adaptive-payments' ),
				'type'        => 'title',
				'description' => '',
			),
			'sandbox' => array(
				'title'       => __( 'PayPal sandbox', 'woocommerce-gateway-paypal-adaptive-payments' ),
				'type'        => 'checkbox',
				'label'       => __( 'Enable PayPal sandbox', 'woocommerce-gateway-paypal-adaptive-payments' ),
				'default'     => 'no',
				'description' => sprintf( __( 'PayPal sandbox can be used to test payments. Sign up for a developer account <a href="%s">here</a>.', 'woocommerce-gateway-paypal-adaptive-payments' ), 'https://developer.paypal.com/' ),
			),
			'debug' => array(
				'title'       => __( 'Debug Log', 'woocommerce-gateway-paypal-adaptive-payments' ),
				'type'        => 'checkbox',
				'label'       => __( 'Enable logging', 'woocommerce-gateway-paypal-adaptive-payments' ),
				'default'     => 'no',
				'description' => sprintf( __( 'Log PayPal events, such as IPN requests, inside <code>woocommerce/logs/' . esc_attr( $this->id ) . '-%s.txt</code>', 'woocommerce-gateway-paypal-adaptive-payments' ), sanitize_file_name( wp_hash( $this->id ) ) ),
			)
		);
	}

	/**
	 * Generate payment arguments for PayPal.
	 *
	 * @param  WC_Order $order Order data.
	 *
	 * @return array           PayPal payment arguments.
	 */
	protected function generate_payment_args( $order ) {
		$pre_wc_30 = version_compare( WC_VERSION, '3.0', '<' );

		$args = array(
			'actionType'         => 'PAY',
			'currencyCode'       => get_woocommerce_currency(),
			'trackingId'         => $this->invoice_prefix . ( $pre_wc_30 ? $order->id : $order->get_id() ),
			'returnUrl'          => str_replace( '&amp;', '&', $this->get_return_url( $order ) ),
			'cancelUrl'          => str_replace( '&amp;', '&', $order->get_cancel_order_url() ),
			'ipnNotificationUrl' => $this->notify_url,
			// 'senderEmail'        => $order->billing_email,
			// 'memo'               => '',
			'requestEnvelope'    => array(
				'errorLanguage' => 'en_US',
				'detailLevel'   => 'ReturnAll'
			)
		);

		$receivers  = array();
		$commission = 0;
		if ( sizeof( $order->get_items() ) > 0 ) {
			foreach ( $order->get_items() as $item ) {
				if ( $item['qty'] ) {
					$product_id        = $item['product_id'];
					$product_receivers = get_post_meta( $product_id, '_paypal_adaptive_receivers', true );
					$product_receivers = array_filter( explode( PHP_EOL, $product_receivers ) );

					if ( ! is_array( $product_receivers ) || empty( $product_receivers ) ) {
						continue;
					}

					foreach ( $product_receivers as $receiver ) {
						$receiver = array_map( 'sanitize_text_field', array_filter( explode( '|', $receiver ) ) );
						if ( ! is_array( $receiver ) || empty( $receiver ) ) {
							continue;
						}

						$email          = $receiver[0];
						$line_total     = $order->get_line_total( $item, true );
						$receiver_total = round( $line_total / 100 * $receiver[1], 2 );

						// Sets the total commission.
						$commission += $receiver_total;

						// Adds a receiver or sum the commission amount.
						if ( isset( $receivers[ $email ] ) ) {
							$receivers[ $email ]['amount'] = number_format( $receivers[ $email ]['amount'] + $receiver_total, 2, '.', '' );
						} else {
							$receivers[ $email ] = array(
								'amount' => number_format( $receiver_total, 2, '.', '' ),
								'email'  => $email
							);

							if ( 'chained' == $this->method ) {
								$receivers[ $email ]['primary'] = 'false';
							}
						}
					}
				}
			}
		}

		// Set receiver list.
		if ( $commission == $order->get_total() && 'parallel' == $this->method ) {

			$args['receiverList'] = array(
				'receiver' => array_values( $receivers )
			);

		} else if ( 0 < $commission ) {

			// Primary receiver / store owner.
			if ( 'chained' == $this->method ) {
				$primary_receiver = array(
					'amount'  => number_format( $order->get_total(), 2, '.', '' ),
					'email'   => $this->receiver_email,
					'primary' => 'true'
				);
			} else {
				$primary_receiver = array(
					'amount' => number_format( $order->get_total() - $commission, 2, '.', '' ),
					'email'  => $this->receiver_email,
				);
			}

			// Adds the primary receiver at the beginning of the list.
			array_unshift( $receivers, $primary_receiver );

			$args['receiverList'] = array(
				'receiver' => array_values( $receivers )
			);

		} else {
			// Single receiver.
			$args['receiverList'] = array(
				'receiver' => array(
					array(
						'amount' => number_format( $order->get_total(), 2, '.', '' ),
						'email'  => $this->receiver_email
					)
				)
			);
		}

		$args = apply_filters( 'woocommerce_paypal_ap_payment_args', $args, $order );

		return $args;
	}

	/**
	 * Set PayPal payment options.
	 *
	 * @param string   $pay_key The pay key that identifies the payment
	 * @param WC_Order $order   Order object
	 */
	protected function set_payment_options( $pay_key, $order ) {

		$data = array(
			'payKey'          => $pay_key,
			'requestEnvelope' => array(
				'errorLanguage' => 'en_US',
				'detailLevel'   => 'ReturnAll'
			),
			'displayOptions'  => array(
				'businessName' => trim( substr( get_option( 'blogname' ), 0, 128 ) )
			),
			'senderOptions'   => array(
				'referrerCode' => 'WooThemes_Cart'
			),
		);

		// Set data for receiverOptions (receiver and invoiceData).
		// In redirect flow this won't affect line items display in PayPal review
		// page, but should be displayed when embedded flow is used. Keep the
		// passed info here when implementing #20.
		$receivers = array();
		foreach ( $order->get_items() as $item ) {
			if ( ! $item['qty'] ) {
				continue;
			}

			$product_id        = $item['product_id'];
			$product           = wc_get_product( $product_id );
			$product_receivers = get_post_meta( $product_id, '_paypal_adaptive_receivers', true );
			$product_receivers = array_filter( explode( PHP_EOL, $product_receivers ) );

			$invoice_item = array(
				'name'      => $product->get_title(),
				'price'     => $order->get_line_total( $item ),
				'itemCount' => $item['qty'],
				'itemPrice' => $product->get_price(),
			);

			// Primary receiver always retrieve all items info.
			if ( ! isset( $receivers[ $this->receiver_email ] ) ) {
				$receivers[ $this->receiver_email ] = array(
					'receiver'    => array( 'email' => $this->receiver_email ),
					'invoiceData' => array( 'item' => array(), 'totalTax' => $order->get_total_tax(), 'totalShipping' => $order->get_total_shipping() ),
				);
			}
			$receivers[ $this->receiver_email ]['invoiceData']['item'][] = $invoice_item;

			if ( ! is_array( $product_receivers ) || empty( $product_receivers ) ) {
				continue;
			}

			foreach ( $product_receivers as $receiver ) {
				$receiver = array_map( 'sanitize_text_field', array_filter( explode( '|', $receiver ) ) );
				if ( ! is_array( $receiver ) || empty( $receiver ) ) {
					continue;
				}

				$email = $receiver[0];
				if ( ! isset( $receivers[ $email ] ) ) {
					$receivers[ $email ] = array(
						'receiver'    => array( 'email' => $email ),
						'invoiceData' => array( 'item' => array() ),
					);
				}

				$receivers[ $email ]['invoiceData']['item'][] = $invoice_item;
			}
		}

		if ( ! empty( $receivers ) ) {
			$data['receiverOptions'] = array_values( $receivers );
		}

		if ( '' != $this->header_image ) {
			$data['displayOptions']['headerImageUrl'] = $this->header_image;
		}

		// Sets the post params.
		$params = array(
			'body'        => json_encode( $data ),
			'timeout'     => 60,
			'httpversion' => '1.1',
			'headers'     => array(
				'X-PAYPAL-SECURITY-USERID'      => $this->api_username,
				'X-PAYPAL-SECURITY-PASSWORD'    => $this->api_password,
				'X-PAYPAL-SECURITY-SIGNATURE'   => $this->api_signature,
				'X-PAYPAL-REQUEST-DATA-FORMAT'  => 'JSON',
				'X-PAYPAL-RESPONSE-DATA-FORMAT' => 'JSON',
				'X-PAYPAL-APPLICATION-ID'       => $this->app_id,
			)
		);

		if ( 'yes' == $this->sandbox ) {
			$url = $this->api_sandbox_url;
		} else {
			$url = $this->api_prod_url;
		}

		if ( 'yes' == $this->debug ) {
			$this->log->add( $this->id, 'Setting payment options with the following data: ' . print_r( $data, true ) );
		}

		$response = wp_safe_remote_post( $url . 'SetPaymentOptions', $params );
		if ( ! is_wp_error( $response ) && 200 == $response['response']['code'] && 'OK' == $response['response']['message'] ) {
			if ( 'yes' == $this->debug ) {
				$this->log->add( $this->id, 'Payment options configured successfully!' );
			}
		} else {
			if ( 'yes' == $this->debug ) {
				$this->log->add( $this->id, 'Failed to configure payment options: ' . print_r( $response, true ) );
			}
		}
	}

	/**
	 * Get the payment data.
	 *
	 * @param  WC_Order $order Order data.
	 *
	 * @return array
	 */
	protected function get_payment_data( $order ) {
		$error_message = __( 'An error has occurred while processing your payment, please try again. Or contact us for assistance.', 'woocommerce-gateway-paypal-adaptive-payments' );
		$data = $this->generate_payment_args( $order );

		// Sets the post params.
		$params = array(
			'body'        => json_encode( $data ),
			'timeout'     => 60,
			'httpversion' => '1.1',
			'headers'     => array(
				'X-PAYPAL-SECURITY-USERID'      => $this->api_username,
				'X-PAYPAL-SECURITY-PASSWORD'    => $this->api_password,
				'X-PAYPAL-SECURITY-SIGNATURE'   => $this->api_signature,
				'X-PAYPAL-REQUEST-DATA-FORMAT'  => 'JSON',
				'X-PAYPAL-RESPONSE-DATA-FORMAT' => 'JSON',
				'X-PAYPAL-APPLICATION-ID'       => $this->app_id,
			)
		);

		if ( 'yes' == $this->sandbox ) {
			$url = $this->api_sandbox_url;
		} else {
			$url = $this->api_prod_url;
		}

		if ( 'yes' == $this->debug ) {
			$this->log->add( $this->id, 'Requesting payment key for order ' . $order->get_order_number() . ' with the following data: ' . print_r( $data, true ) );
		}

		$response = wp_safe_remote_post( $url . 'Pay', $params );

		if ( is_wp_error( $response ) ) {
			if ( 'yes' == $this->debug ) {
				$this->log->add( $this->id, 'WP_Error in generate payment key: ' . $response->get_error_message() );
			}
		} else if ( 200 == $response['response']['code'] && 'OK' == $response['response']['message'] ) {
			$body = json_decode( $response['body'], true );

			if ( isset( $body['payKey'] ) ) {
				$pay_key = esc_attr( $body['payKey'] );

				if ( 'yes' == $this->debug ) {
					$this->log->add( $this->id, 'Payment key successfully created! The key is: ' . $pay_key );
				}

				// Just set the payment options.
				$this->set_payment_options( $pay_key, $order );

				return array(
					'success' => true,
					'message' => '',
					'key'     => $pay_key
				);
			}

			if ( isset( $body['error'] ) ) {
				if ( 'yes' == $this->debug ) {
					$this->log->add( $this->id, 'Failed to generate the payment key: ' . print_r( $body, true ) );
				}

				foreach ( $body['error'] as $error ) {
					if ( '579042' == $error['errorId'] ) {
						$error_message = sprintf( __( 'Your order has expired, please %s to try again.', 'woocommerce-gateway-paypal-adaptive-payments' ), '<a href="' . esc_url( $order->get_cancel_order_url() ) . '">' . __( 'click here', 'woocommerce-gateway-paypal-adaptive-payments' ) . '</a>' );
						break;
					} else if ( isset( $error['message'] ) ) {
						$order->add_order_note( sprintf( __( 'PayPal Adaptive Payments error: %s', 'woocommerce-gateway-paypal-adaptive-payments' ), esc_html( $error['message'] ) ) );
					}
				}
			}

		} else {
			if ( 'yes' == $this->debug ) {
				$this->log->add( $this->id, 'Error in generate payment key: ' . print_r( $response, true ) );
			}
		}

		return array(
			'success' => false,
			'message' => $error_message,
			'key'     => ''
		);
	}

	/**
	 * Process the payment and return the result.
	 *
	 * @param  int $order_id
	 *
	 * @return array
	 */
	public function process_payment( $order_id ) {
		$order        = new WC_Order( $order_id );
		$payment_data = $this->get_payment_data( $order );

		if ( $payment_data['success'] ) {
			if ( 'yes' == $this->sandbox ) {
				$url = $this->payment_sandbox_url;
			} else {
				$url = $this->payment_prod_url;
			}

			return array(
				'result'   => 'success',
				'redirect' => esc_url_raw( add_query_arg( array( 'cmd' => '_ap-payment', 'paykey' => $payment_data['key'] ), $url ) )
			);
		} else {

			wc_add_notice( $payment_data['message'], 'error' );

			return array(
				'result'   => 'fail',
				'redirect' => ''
			);
		}
	}

	/**
	 * Check for PayPal IPN Response
	 */
	public function check_ipn_response() {
		@ob_clean();

		$ipn_response = ! empty( $_POST ) ? $_POST : false;

		if ( $ipn_response ) {

			header( 'HTTP/1.1 200 OK' );

			do_action( 'woocommere_paypal_adaptive_payments_ipn', $ipn_response );

		} else {

			wp_die( 'PayPal IPN Request Failure', 'PayPal IPN', array( 'response' => 200 ) );

		}
	}

	/**
	 * Process the IPN.
	 *
	 * @param array $posted PayPal IPN POST data.
	 */
	public function process_ipn( $posted ) {
		$posted = stripslashes_deep( $posted );

		if ( ! isset( $posted['tracking_id'] ) ) {
			exit;
		}

		// Extract the order ID.
		$order_id = intval( str_replace( $this->invoice_prefix, '', $posted['tracking_id'] ) );

		if ( 'yes' == $this->debug ) {
			$this->log->add( $this->id, 'Checking IPN response for order #' . $order_id . '...' );
		}

		// Get the order data.
		$order = wc_get_order( $order_id );

		// Checks whether the invoice number matches the order.
		// If true processes the payment.
		if ( $order ) {
			$status = esc_attr( $posted['status'] );

			if ( 'yes' == $this->debug ) {
				$this->log->add( $this->id, 'Payment status: ' . $status );
			}

			switch ( $status ) {
				case 'CANCELED' :
					$order->update_status( 'cancelled', __( 'Payment canceled via IPN.', 'woocommerce-gateway-paypal-adaptive-payments' ) );

					break;
				case 'CREATED' :
					$order->update_status( 'on-hold', __( 'The payment request was received. Funds will be transferred once the payment is approved.', 'woocommerce-gateway-paypal-adaptive-payments' ) );

					break;
				case 'COMPLETED' :
					// Check order not already completed.
					if ( $order->get_status() == 'completed' ) {
						if ( 'yes' == $this->debug ) {
							$this->log->add( $this->id, 'Aborting, Order #' . $order_id . ' is already complete.' );
						}
						exit;
					}

					if ( ! empty( $posted['sender_email'] ) ) {
						update_post_meta( $order_id, 'Payer PayPal address', sanitize_text_field( $posted['sender_email'] ) );
					}

					$order->add_order_note( __( 'The payment was successful.', 'woocommerce-gateway-paypal-adaptive-payments' ) );
					$order->payment_complete();

					break;
				case 'INCOMPLETE' :
					$order->update_status( 'on-hold', __( 'Some transfers succeeded and some failed for a parallel payment or, for a delayed chained payment, secondary receivers have not been paid.', 'woocommerce-gateway-paypal-adaptive-payments' ) );

					break;
				case 'ERROR' :
					$order->update_status( 'failed', __( 'The payment failed and all attempted transfers failed or all completed transfers were successfully reversed.', 'woocommerce-gateway-paypal-adaptive-payments' ) );

					break;
				case 'REVERSALERROR' :
					$order->update_status( 'failed', __( 'One or more transfers failed when attempting to reverse a payment.', 'woocommerce-gateway-paypal-adaptive-payments' ) );

					break;
				case 'PROCESSING' :
					$order->update_status( 'on-hold', __( 'The payment is in progress.', 'woocommerce-gateway-paypal-adaptive-payments' ) );

					break;
				case 'PENDING' :
					$order->update_status( 'pending', __( 'The payment is awaiting processing.', 'woocommerce-gateway-paypal-adaptive-payments' ) );

					break;

				default :
					// No action.
					break;
			}
		} else {
			if ( 'yes' == $this->debug ) {
				$this->log->add( $this->id, 'Invalid IPN response for order #' . $order_id . '!' );
			}
		}
	}

	/**
	 * Adds error message when the plugin is not configured properly.
	 *
	 * @return string
	 */
	public function plugin_not_configured_message() {
		$id = 'woocommerce_paypal-adaptive-payments_';
		if (
			isset( $_POST[ $id . 'api_username' ] ) && ! empty( $_POST[ $id . 'api_username' ] )
			&& isset( $_POST[ $id . 'api_password' ] ) && ! empty( $_POST[ $id . 'api_password' ] )
			&& isset( $_POST[ $id . 'api_signature' ] ) && ! empty( $_POST[ $id . 'api_signature' ] )
			&& isset( $_POST[ $id . 'app_id' ] ) && ! empty( $_POST[ $id . 'app_id' ] )
			&& isset( $_POST[ $id . 'receiver_email' ] ) && ! empty( $_POST[ $id . 'receiver_email' ] )
		) {
			return;
		}

		echo '<div class="error"><p><strong>' . __( 'PayPal Adaptive Payments Disabled', 'woocommerce-gateway-paypal-adaptive-payments' ) . '</strong>: ' . __( 'You must fill the API Username, API Password, API Signature, Application ID and Receiver Email options.', 'woocommerce-gateway-paypal-adaptive-payments' ) . '</p></div>';
	}

	/**
	 * Adds error message when an unsupported currency is used.
	 *
	 * @return string
	 */
	public function currency_not_supported_message() {
		echo '<div class="error"><p><strong>' . __( 'PayPal Adaptive Payments Disabled', 'woocommerce-gateway-paypal-adaptive-payments' ) . '</strong>: ' . __( 'PayPal does not support your store currency.', 'woocommerce-gateway-paypal-adaptive-payments' ) . '</p></div>';
	}

}
