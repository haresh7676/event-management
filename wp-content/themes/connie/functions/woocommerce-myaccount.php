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
        'edit-account'       => __( 'Account Settings', 'woocommerce' ),
        'payment-methods'    => __( 'Payment Settings', 'woocommerce' ),
        'orders'             => __( 'My Tickets', 'woocommerce' ),
        'manage-events' => __( 'Manage Events', 'woocommerce' ),
        'help-center' => __( 'Help Center', 'woocommerce' ),
        'report-problem' => __( 'Report a Problem', 'woocommerce' ),
        'about' => __( 'About', 'woocommerce' ),
        'terms-and-policies' => __( 'Terms and Policies', 'woocommerce' ),
        //  'dashboard'          => __( 'Dashboard', 'woocommerce' ),
        //'downloads'          => __( 'Download', 'woocommerce' ),
        //'edit-address'       => __( 'Addresses', 'woocommerce' ),
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
    add_rewrite_endpoint( 'help-center', EP_PAGES );
    add_rewrite_endpoint( 'report-problem', EP_PAGES );
    add_rewrite_endpoint( 'about', EP_PAGES );
    add_rewrite_endpoint( 'terms-and-policies', EP_PAGES );
}

add_action( 'init', 'connie_add_my_account_endpoint' );
add_filter("woocommerce_get_query_vars", function ($vars) {

    foreach (["manage-events", "help-center", "report-problem", "about", "terms-and-policies"] as $e) {
        $vars[$e] = $e;
    }

    return $vars;

});

function wpb_woo_endpoint_title( $title, $id ) {
    if ( is_wc_endpoint_url( 'downloads' ) && in_the_loop() ) { // add your endpoint urls
        $title = "Download MP3s"; // change your entry-title
    }
    elseif ( is_wc_endpoint_url( 'orders' ) && in_the_loop() ) {
        $title = "My Tickets";
    }
    elseif ( is_wc_endpoint_url( 'edit-account' ) && in_the_loop() ) {
        $title = "Account Settings";
    }
    elseif ( is_wc_endpoint_url( 'manage-events' ) && in_the_loop() ) {
        $title = "Manage Events";
    }
    elseif ( is_wc_endpoint_url( 'help-center' ) && in_the_loop() ) {
        $title = "Help Center";
    }
    elseif ( is_wc_endpoint_url( 'report-problem' ) && in_the_loop() ) {
        $title = "Report a Problem";
    }
    elseif ( is_wc_endpoint_url( 'about' ) && in_the_loop() ) {
        $title = "About";
    }
    elseif ( is_wc_endpoint_url( 'terms-and-policies' ) && in_the_loop() ) {
        $title = "Terms and Policies";
    }
    elseif ( is_wc_endpoint_url( 'payment-methods' ) && in_the_loop() ) {
        $title = "Payment Settings";
    }
    return $title;
}
add_filter( 'the_title', 'wpb_woo_endpoint_title', 10, 2 );

/**
 * Manage Event content
 */
function connie_manage_events_endpoint_content() {
    require_once(get_template_directory() .'/include/manage-events.php');
}

function connie_help_center_endpoint_content() {
    require_once(get_template_directory() .'/include/help-center.php');
}

function connie_report_problem_endpoint_content() {
    require_once(get_template_directory() .'/include/report-problem.php');
}

function connie_about_endpoint_content() {
    require_once(get_template_directory() .'/include/about.php');
}

function connie_terms_and_policies_endpoint_content() {
    require_once(get_template_directory() .'/include/terms-and-policies.php');
}

add_action( 'woocommerce_account_manage-events_endpoint', 'connie_manage_events_endpoint_content' );
add_action( 'woocommerce_account_help-center_endpoint', 'connie_help_center_endpoint_content' );
add_action( 'woocommerce_account_report-problem_endpoint', 'connie_report_problem_endpoint_content' );
add_action( 'woocommerce_account_about_endpoint', 'connie_about_endpoint_content' );
add_action( 'woocommerce_account_terms-and-policies_endpoint', 'connie_terms_and_policies_endpoint_content' );




