<?php
add_action('woocommerce_before_account_navigation', 'connie_before_account_wpr');
function connie_before_account_wpr(){
    echo '<div class="sidebar-tabs">';
}

add_action('woocommerce_after_account_navigation', 'connie_after_account_wpr');
function connie_after_account_wpr(){
    echo '</div>';
}

add_action('woocommerce_before_edit_account_form', 'connie_before_account_edit_account_form');
function connie_before_account_edit_account_form(){
    echo '<div class="tab-pane-spacing">';
}

add_action('woocommerce_after_edit_account_form', 'connie_after_edit_account_form');
function connie_after_edit_account_form(){
    echo '</div>';
}


function connie_woo_my_account_order() {
    $myorder = array(
        'manage-events' => __( 'Manage Events', 'woocommerce' ),
        'edit-account'       => __( 'Account Settings', 'woocommerce' ),
        'dashboard'          => __( 'Dashboard', 'woocommerce' ),
        'orders'             => __( 'Orders', 'woocommerce' ),
        'downloads'          => __( 'Download MP4s', 'woocommerce' ),
        'edit-address'       => __( 'Addresses', 'woocommerce' ),
        'payment-methods'    => __( 'Payment Methods', 'woocommerce' ),
        'customer-logout'    => __( 'Logout', 'woocommerce' ),
    );
    return $myorder;
}
add_filter ( 'woocommerce_account_menu_items', 'connie_woo_my_account_order' );

/**
 * Add endpoint
 */
function connie_add_my_account_endpoint() {

    add_rewrite_endpoint( 'manage-events', EP_PAGES );

}

add_action( 'init', 'connie_add_my_account_endpoint' );

/**
 * Manage Event content
 */
function connie_manage_events_endpoint_content() {
    require_once(get_template_directory() .'/include/manage-events.php');
}

add_action( 'woocommerce_account_manage-events_endpoint', 'connie_manage_events_endpoint_content' );




