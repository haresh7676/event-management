<?php
function mode_theme_update_mini_cart() {
    echo wc_get_template( 'cart/mini-cart.php' );
    die();
}
add_filter( 'wp_ajax_nopriv_mode_theme_update_mini_cart', 'mode_theme_update_mini_cart' );
add_filter( 'wp_ajax_mode_theme_update_mini_cart', 'mode_theme_update_mini_cart' );

remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10 );
remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20 );


function my_woocommerce_widget_shopping_cart_proceed_to_checkout() {
    echo '<a href="' . esc_url( wc_get_checkout_url() ) . '" class="btn btn-default">' . esc_html__( 'Next Step', 'woocommerce' );
    echo '<i class="fas fa-long-arrow-alt-right"></i>';
    echo '</a>';
}
add_action( 'woocommerce_widget_shopping_cart_buttons', 'my_woocommerce_widget_shopping_cart_proceed_to_checkout', 20 );

add_action( 'woocommerce_checkout_before_order_review', 'woocommerce_checkout_before_order_review_add_wpar');
add_action( 'woocommerce_checkout_after_order_review', 'woocommerce_checkout_after_order_review_close_wpar');
function woocommerce_checkout_before_order_review_add_wpar(){
    echo '<div class="payment_main_section jb">';
}
function woocommerce_checkout_after_order_review_close_wpar(){
    echo '</div>';
}
/*add_action( 'wmsc_step_content_billing', 'skyverge_add_checkout_content', 9 );
function skyverge_add_checkout_content() {
    wc_cart_totals_shipping_html();
}*/
add_action( 'wmsc_step_content_billing', 'connie_add_checkout_content', 10 );
function connie_add_checkout_content()
{
    echo '<div class="min-cart-main">';
    echo '<div class="cart-details-title">';
    echo '<h3>'.__('Your Order','wp-event-manager-sell-tickets').'</h3>';
    echo '</div>';
    echo '<div id="mode-mini-cart">';
    woocommerce_mini_cart();
    echo '</div>';
    echo '</div>';
}


function custom_woocommerce_paypal_ap_payment_args( $args, $order ) {
    $order_total = is_callable(array($order, 'get_total')) ? $order->get_total() : $order->order_total;
    $order_subtotal = $order->get_subtotal();
    $order_subtotal = number_format( $order_subtotal, 2 );
    $adminprice = $order_total - $order_subtotal;
    $adminprice = number_format( $adminprice, 2 );
    $args['receiverList']['receiver'][0]['amount'] = $adminprice;
    $args['receiverList']['receiver'][1] =
        array(
            'amount' => $order_subtotal,
            'email'  => 'topscmswp-facilitator@gmail.com'
        );
    return $args;
}
add_filter( 'woocommerce_paypal_ap_payment_args', 'custom_woocommerce_paypal_ap_payment_args', 10, 2 );