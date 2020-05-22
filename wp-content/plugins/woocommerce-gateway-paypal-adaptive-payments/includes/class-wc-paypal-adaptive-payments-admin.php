<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * PayPal Adaptive Payments Admin.
 *
 * @package  WC_PayPal_Adaptive_Payments_Admin
 * @category Admin
 * @author   WooThemes
 */
class WC_PayPal_Adaptive_Payments_Admin {

	/**
	 * Nonce.
	 *
	 * @var string
	 */
	protected $nonce = 'woocommerce_paypal-adaptive-payments_nonce';

	/**
	 * Initialize the admin actions.
	 */
	public function __construct() {
		add_filter( 'woocommerce_product_data_tabs', array( $this, 'product_data_tabs' ) );
		add_action( 'woocommerce_product_data_panels', array( $this, 'product_panel' ), 10 );
		add_action( 'woocommerce_process_product_meta', array( $this, 'save_product_panel' ), 20, 2 );
	}

	/**
	 * Adds a tab to insert emails for sales commissions.
	 *
	 * @param  array $tabs Product data tabs.
	 *
	 * @return array       Adds the PayPal tab.
	 */
	public function product_data_tabs( $tabs ) {
		$tabs['paypal-adaptive-payments'] = array(
			'label'  => __( 'PayPal Adaptive Payments', 'woocommerce-gateway-paypal-adaptive-payments' ),
			'target' => 'paypal-adaptive-payments-panel',
			'class'  => array(),
		);

		return $tabs;
	}

	/**
	 * Display the PayPal Adaptive Payments panel.
	 *
	 * @return string Receivers field.
	 */
	public function product_panel() {
		global $post;

		wp_nonce_field( basename( __FILE__ ), $this->nonce );

		echo '<div id="paypal-adaptive-payments-panel" class="panel woocommerce_options_panel">';
			echo '<div class="options_group">';
				woocommerce_wp_textarea_input(
					array(
						'id'          => '_paypal_adaptive_receivers',
						'label'       => __( 'PayPal Receivers', 'woocommerce-gateway-paypal-adaptive-payments' ),
						'value'       => esc_attr( get_post_meta( $post->ID, '_paypal_adaptive_receivers', true ) ),
						'desc_tip'    => 'true',
						'description' => __( 'Enter with receivers email and commission percentage (one per line): ninja@email.com|50. Note: The total percentage may not exceed 100%.', 'woocommerce-gateway-paypal-adaptive-payments' ),
						'placeholder' => __( 'ninja@email.com|50', 'woocommerce-gateway-paypal-adaptive-payments' )
					)
				);
			echo '</div>';
		echo '</div>';
	}

	/**
	 * Save the product data panel content.
	 *
	 * @param  int    $post_id Post ID.
	 * @param  object $post    Post data.
	 *
	 * @return null
	 */
	public function save_product_panel( $post_id, $post ) {
		// Verify nonce.
		if ( ! isset( $_POST[ $this->nonce ] ) || ! wp_verify_nonce( $_POST[ $this->nonce ], basename( __FILE__ ) ) ) {
			return;
		}

		// Verify if this is an auto save routine.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Save the receivers field.
		if ( isset( $_POST['_paypal_adaptive_receivers'] ) ) {
			update_post_meta( $post_id, '_paypal_adaptive_receivers', wp_kses( $_POST['_paypal_adaptive_receivers'], array() ) );
		}
	}
}

new WC_PayPal_Adaptive_Payments_Admin();
