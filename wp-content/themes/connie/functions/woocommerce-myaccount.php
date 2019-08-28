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
        'payment-settings'    => __( 'Payment Settings', 'woocommerce' ),
        //'payment-methods'    => __( 'Payment Settings', 'woocommerce' ),
        'my-tickets'             => __( 'My Tickets', 'woocommerce' ),
        //'orders'             => __( 'My Tickets', 'woocommerce' ),
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

    add_rewrite_endpoint( 'payment-settings', EP_PAGES );
    add_rewrite_endpoint( 'my-tickets', EP_PAGES );
    add_rewrite_endpoint( 'manage-events', EP_PAGES );
    add_rewrite_endpoint( 'help-center', EP_PAGES );
    add_rewrite_endpoint( 'report-problem', EP_PAGES );
    add_rewrite_endpoint( 'about', EP_PAGES );
    add_rewrite_endpoint( 'terms-and-policies', EP_PAGES );
}

add_action( 'init', 'connie_add_my_account_endpoint' );
add_filter("woocommerce_get_query_vars", function ($vars) {

    foreach (["payment-settings", "my-tickets", "manage-events", "help-center", "report-problem", "about", "terms-and-policies"] as $e) {
        $vars[$e] = $e;
    }

    return $vars;

});

function wpb_woo_endpoint_title( $title, $id ) {
    if ( is_wc_endpoint_url( 'downloads' ) && in_the_loop() ) { // add your endpoint urls
        $title = "Download MP3s"; // change your entry-title
    }
    elseif ( is_wc_endpoint_url( 'my-tickets' ) && in_the_loop() ) {
        $title = "My Tickets";
    }
    elseif ( is_wc_endpoint_url( 'view-order' ) && in_the_loop() ) {
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
    elseif ( is_wc_endpoint_url( 'payment-settings' ) && in_the_loop() ) {
        $title = "Payment Settings";
    }
    return $title;
}
add_filter( 'the_title', 'wpb_woo_endpoint_title', 10, 2 );

/**
 * Manage Event content
 */
function connie_my_tickets_endpoint_content() {
    require_once(get_template_directory() .'/include/my-tickets.php');
}

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
function connie_payment_settings_endpoint_content() {
    require_once(get_template_directory() .'/include/payment-settings.php');
}
add_action( 'woocommerce_account_payment-settings_endpoint', 'connie_payment_settings_endpoint_content' );
add_action( 'woocommerce_account_my-tickets_endpoint', 'connie_my_tickets_endpoint_content' );
add_action( 'woocommerce_account_manage-events_endpoint', 'connie_manage_events_endpoint_content' );
add_action( 'woocommerce_account_help-center_endpoint', 'connie_help_center_endpoint_content' );
add_action( 'woocommerce_account_report-problem_endpoint', 'connie_report_problem_endpoint_content' );
add_action( 'woocommerce_account_about_endpoint', 'connie_about_endpoint_content' );
add_action( 'woocommerce_account_terms-and-policies_endpoint', 'connie_terms_and_policies_endpoint_content' );


function get_cf7_form_data($formid,$pageNumber = 1,$perPageCount = 10,$author = true,$eventid = ''){
    global $wpdb;
    $lowerLimit = ($pageNumber - 1) * $perPageCount;
    $current_user = wp_get_current_user();
    $current_user_id = $current_user->ID;
    if($author == true) {
        $resultstotal = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}db7_forms WHERE form_value REGEXP '.*\"eventauthor\";s:[0-9]+:\"$current_user_id\".*'".(!empty($eventid)?' AND form_value REGEXP \'.*"eventid";s:[0-9]+:"'.$eventid.'".*\'':'')." AND form_post_id = ".$formid." order by form_id desc", ARRAY_A);
    }else {
        $resultstotal = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}db7_forms WHERE form_post_id = ".$formid.(!empty($eventid)?' AND form_value REGEXP \'.*"eventid";s:[0-9]+:"'.$eventid.'".*\'':'')." order by form_id desc", ARRAY_A);
    }
    if(!empty($resultstotal)){
        $results['count'] = count($resultstotal);
    }
    if($author == true) {
        $results['data'] = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}db7_forms WHERE form_value REGEXP '.*\"eventauthor\";s:[0-9]+:\"$current_user_id\".*'".(!empty($eventid)?' AND form_value REGEXP \'.*"eventid";s:[0-9]+:"'.$eventid.'".*\'':'')." AND form_post_id = ".$formid." order by form_id desc limit " . ($lowerLimit) . " , " . ($perPageCount) . " ", ARRAY_A);
    }else{
        $results['data'] = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}db7_forms WHERE form_post_id = ".$formid.(!empty($eventid)?' AND form_value REGEXP \'.*"eventid";s:[0-9]+:"'.$eventid.'".*\'':'')." order by form_id desc limit " . ($lowerLimit) . " , " . ($perPageCount) . " ", ARRAY_A);
    }

    if(!empty($results['data'])){
        foreach ($results['data'] as $key => $item){
            if($item['form_value']){
                $arr = unserialize(urldecode($item['form_value']));
                $results['data'][$key]['form_data'] = $arr;
            }
        }
    }
    return $results;
}
function get_post_by_eventid($page_id, $output = OBJECT) {
    global $wpdb;
    $post = $wpdb->get_var( $wpdb->prepare( "SELECT post_title FROM $wpdb->posts WHERE ID = %s AND post_type='event_listing'", $page_id ));
    if ( $post )
        return $post;

    return null;
}

add_action("wp_ajax_get_volunteer_data", "get_volunteer_data");
add_action("wp_ajax_nopriv_get_volunteer_data", "get_volunteer_data");

function get_volunteer_data() {
    $data = $_POST;
    $pageNumber = $data['pageNumber'];
    $perPageCount = $data['perPageCount'];
    $selectedeventid = $data['eventid'];
    $action = $data['action'];
    $myaccountsettings =  get_fields('account-settings');
    $formid = 1;
    if(!empty($myaccountsettings) && isset($myaccountsettings['manage_event'])){
        $formid = isset($myaccountsettings['manage_event']['volunteer_form_id'])?$myaccountsettings['manage_event']['volunteer_form_id']:1;
    }
    if(empty($selectedeventid)){
        $selectedeventid = '';
    }
    $results = get_cf7_form_data($formid,$pageNumber,$perPageCount,true,$selectedeventid);
    $output = '';
    $output .='<div class="table-responsive">';
    $output .='<table class="table">';
    $output .='<thead class="thead-purple">';
    $output .='<tr>';
    $output .='<th>Name</th>';
    $output .='<th>Phone</th>';
    $output .='<th>Email</th>';
    $output .='<th>Area of Expertise</th>';
    $output .='<th>Days Available</th>';
    $output .='<th>Hours Available</th>';
    $output .='<th>Event</th>';
    $output .='</tr>';
    $output .='</thead>';
    $output .='<tbody>';
    if(isset($results) && !empty($results['data'])){
        foreach ($results['data'] as $item){
            $output.='<tr>';
            $output.='<td>'.$item['form_data']['first-name'].' '.$item['form_data']['last-name'].'</td>';
            $output.='<td>'.$item['form_data']['tel-phone'].'</td>';
            $output.='<td>'.$item['form_data']['your-email'].'</td>';
            $output.='<td>'.$item['form_data']['area-of-expertise'].'</td>';
            $output.='<td>'.(!empty($item['form_data']['days-available']) && is_array($item['form_data']['days-available'])?implode(", ",$item['form_data']['days-available']):$item['form_data']['days-available']).'</td>';
            $output.='<td>'.$item['form_data']['hours-available'].'</td>';
            $output.='<td>'.(isset($item['form_data']['eventid']) && !empty($item['form_data']['eventid'])?get_post_by_eventid($item['form_data']['eventid']):'-').'</td>';
            $output.='</tr>';
        }
    }else{
        $output.='<tr>';
        $output.='<td colspan="7">No record found.</td>';
        $output.='</tr>';
    }
    $output.='</tbody>';
    $output.='</table>';
    $output.='</div>';
    /*pagination */
    $rowCount = (isset($results) && !empty($results['count']))?$results['count']:0;
    $pagesCount = ceil($rowCount / $perPageCount);
    $output.='<div class="export-list-row">';
    //$output.='<a href="#" class="export-a"><i class="fas fa-chevron-up"></i> Export List</a>';
    $output.='<div class="custom-pagination">';
    $output.='<ul>';
    $output.='<li class="pagination-list">';
	for ($i = 1; $i <= $pagesCount; $i ++) {
        if ($i == $pageNumber) {
            $output.='<a href="javascript:void(0);" class="current">'.$i.'</a>';
        } else {
            $output.='<a href="javascript:void(0);" class="pages" onclick="showRecords('.$perPageCount.', '.$i.',\'get_volunteer_data\')">'.$i.'</a>';
        } // endIf
    } // endFor
    $output.='</li>';
    $output.='<li class="totalofPage">';
    //$output.='Page '.$pageNumber.' of '.$pagesCount;
    $stating = ($pageNumber-1)*$perPageCount+1;
    $stating = ($rowCount == 0)?0:$stating;
    $ending = $perPageCount*$pageNumber;
    $ending = ($rowCount < $ending)?$rowCount:$ending;
    $output.='<span class="pagenav">'.$stating.'-'.$ending.' of '.$rowCount.'</span>';
	$output.='</li>';
    $output.='</ul>';
    $output.='</div>';
    $output.='</div>';
    echo $output;
    die();
}

add_action("wp_ajax_get_team_member_data", "get_team_member_data");
add_action("wp_ajax_nopriv_get_team_member_data", "get_team_member_data");

function get_team_member_data() {
    $data = $_POST;
    $pageNumber = $data['pageNumber'];
    $perPageCount = $data['perPageCount'];
    $action = $data['action'];
    $myaccountsettings =  get_fields('account-settings');
    $formid = 1;
    if(!empty($myaccountsettings) && isset($myaccountsettings['manage_event'])){
        $formid = isset($myaccountsettings['manage_event']['add_team_form_id'])?$myaccountsettings['manage_event']['add_team_form_id']:1;
    }
    $results = get_cf7_form_data($formid,$pageNumber,$perPageCount,true);
    $output = '';
    $output .='<div class="table-responsive">';
    $output .='<table class="table">';
    $output .='<thead class="thead-purple">';
    $output .='<tr>';
    $output .='<th>Name</th>';
    $output .='<th>Phone</th>';
    $output .='<th>Email</th>';
    $output .='<th>Location</th>';
    $output .='<th>Position</th>';
    $output .='</tr>';
    $output .='</thead>';
    $output .='<tbody>';
    if(isset($results) && !empty($results['data'])){
        foreach ($results['data'] as $item){
            $output.='<tr>';
            $output.='<td>'.$item['form_data']['first-name'].' '.$item['form_data']['last-name'].'</td>';
            $output.='<td>'.$item['form_data']['telephone'].'</td>';
            $output.='<td>'.$item['form_data']['your-email'].'</td>';
            $output.='<td>'.$item['form_data']['location'].'</td>';
            $output.='<td>'.$item['form_data']['position'].'</td>';
            $output.='</tr>';
        }
    }
    $output.='</tbody>';
    $output.='</table>';
    $output.='</div>';
    /*pagination */
    $rowCount = (isset($results) && !empty($results['count']))?$results['count']:0;
    $pagesCount = ceil($rowCount / $perPageCount);
    $output.='<div class="export-list-row">';
    $output.='<button data-toggle="modal" data-target="#AddteamModal">Add New Team Member</button>';
    $output.='<div class="custom-pagination">';
    $output.='<ul>';
    $output.='<li class="pagination-list">';
    for ($i = 1; $i <= $pagesCount; $i ++) {
        if ($i == $pageNumber) {
            $output.='<a href="javascript:void(0);" class="current">'.$i.'</a>';
        } else {
            $output.='<a href="javascript:void(0);" class="pages" onclick="showRecords('.$perPageCount.', '.$i.',\'get_team_member_data\')">'.$i.'</a>';
        } // endIf
    } // endFor
    $output.='</li>';
    $output.='<li class="totalofPage">';
    //$output.='Page '.$pageNumber.' of '.$pagesCount;
    $stating = ($pageNumber-1)*$perPageCount+1;
    $stating = ($rowCount == 0)?0:$stating;
    $ending = $perPageCount*$pageNumber;
    $ending = ($rowCount < $ending)?$rowCount:$ending;
    $output.='<span class="pagenav">'.$stating.'-'.$ending.' of '.$rowCount.'</span>';
    $output.='</li>';
    $output.='</ul>';
    $output.='</div>';
    $output.='</div>';
    echo $output;
    die();
}

add_action("wp_ajax_get_report_problem_contact_data", "get_report_problem_contact_data");
//add_action("wp_ajax_nopriv_get_report_problem_contact_data", "get_report_problem_contact_data");

function get_report_problem_contact_data() {
    $data = $_POST;
    $pageNumber = $data['pageNumber'];
    $perPageCount = $data['perPageCount'];
    $action = $data['action'];
    $myaccountsettings =  get_fields('account-settings');
    $formid = 1;
    if(!empty($myaccountsettings) && isset($myaccountsettings['manage_event'])){
        $formid = isset($myaccountsettings['manage_event']['report_a_problem_form_id'])?$myaccountsettings['manage_event']['report_a_problem_form_id']:1;
    }
    $results = get_cf7_form_data($formid,$pageNumber,$perPageCount,true);
    $output = '';
    $output .='<ul class="m-e-contact-list">';
    if(isset($results) && !empty($results['data'])){
        foreach ($results['data'] as $item){
            $readclass = ($item['form_data']['cfdb7_status'] == 'read') ? 'disabled':'';
            $output.='<li class="'.$readclass.'"><div class="m-e-contact-profile-desc">';
            $output.='<span>'.$item['form_data']['first-name'].' '.$item['form_data']['last-name'].'</span>';
            $output.='<h3>'.$item['form_data']['subject'].'</h3>';
            $output.='<p>'.$item['form_data']['your-message'].'</p>';
            $output.='</div>';
            $output.='<div class="m-e-contact-profile-time">'.connic_time_elapsed_string($item['form_date']).'</div>';
            $output.='</li>';
        }
    }
    $output.='</ul>';
    /*pagination */
    $rowCount = (isset($results) && !empty($results['count']))?$results['count']:0;
    $pagesCount = ceil($rowCount / $perPageCount);
    $output.='<div class="export-list-row">';
    $output.='<div class="custom-pagination">';
    $output.='<ul>';
    $output.='<li class="pagination-list">';
    for ($i = 1; $i <= $pagesCount; $i ++) {
        if ($i == $pageNumber) {
            $output.='<a href="javascript:void(0);" class="current">'.$i.'</a>';
        } else {
            $output.='<a href="javascript:void(0);" class="pages" onclick="showRecords('.$perPageCount.', '.$i.',\'get_report_problem_contact_data\')">'.$i.'</a>';
        } // endIf
    } // endFor
    $output.='</li>';
    $output.='<li class="totalofPage">';
    //$output.='Page '.$pageNumber.' of '.$pagesCount;
    $stating = ($pageNumber-1)*$perPageCount+1;
    $stating = ($rowCount == 0)?0:$stating;
    $ending = $perPageCount*$pageNumber;
    $ending = ($rowCount < $ending)?$rowCount:$ending;
    $output.='<span class="pagenav">'.$stating.'-'.$ending.' of '.$rowCount.'</span>';
    $output.='</li>';
    $output.='</ul>';
    $output.='</div>';
    $output.='</div>';
    echo $output;
    die();
}

function connic_time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'yr',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hr',
        'i' => 'min',
        's' => 'sec',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}


add_action("wp_ajax_get_attendees_data", "get_attendees_data");
//add_action("wp_ajax_nopriv_get_attendees_data", "get_attendees_data");

function get_attendees_data() {
    $data = $_POST;
    $pageNumber = $data['pageNumber'];
    $perPageCount = $data['perPageCount'];
    $selectedeventid = $data['eventid'];
    $action = $data['action'];

$my_orders_columns = apply_filters( 'woocommerce_my_account_my_orders_columns', array(
    'order-number'  => __( 'Comfirmation #', 'woocommerce' ),
    'order-date'    => __( 'Date', 'woocommerce' ),
    'order-buyer'    => __( 'Ticket Buyer', 'woocommerce' ),
    'order-email'  => __( 'Emails', 'woocommerce' ),
    'order-status'  => __( 'Status', 'woocommerce' ),
    'order-total'   => __( 'Price', 'woocommerce' ),
    'order-quantity'   => __( 'Quantity', 'woocommerce' ),
    'order-product'   => __( 'Event', 'woocommerce' ),
    'order-actions' => '&nbsp;',
) );
    if(!empty($my_orders_columns)){
        unset($my_orders_columns['order-actions']);
        unset($my_orders_columns['download-ticket']);
    }
    if(!empty($selectedeventid)){
        $events = array($selectedeventid);
    }else {
        $events = connice_event_listing_by_current_user('ids');
    }
$productids = connice_product_list_by_events($events,'ids');
$orders = get_orders_ids_by_product_id($productids,$pageNumber,$perPageCount);
    $output = '<div class="table-responsive">';
    $output .= '<table class="table">';
    $output .= '<thead class="thead-purple"><tr>';
    foreach ($my_orders_columns as $column_id => $column_name) :
        $output .= '<th class="' . esc_attr($column_id) . '"><span class="nobr">' . esc_html($column_name) . '</span></th>';
    endforeach;
    $output .= '</tr></thead>';
    $output .= '<tbody>';
if(!empty($orders['data'])) {
    $customer_orders = get_posts(apply_filters('woocommerce_my_account_my_orders_query', array(
        'numberposts' => $perPageCount,
        'post__in' => $orders['data'],
        'post_type' => wc_get_order_types('view-orders'),
        'post_status' => array_keys(wc_get_order_statuses()),
    )));
    if ($customer_orders) :
        foreach ($customer_orders as $customer_order) :
            $order = wc_get_order($customer_order);
            $item_count = $order->get_item_count();
            $order_items= $order->get_items();
            foreach ( $order_items as $item_id => $item ) {
                $order_productid = $item->get_product_id();
            }
            $eventid = '';
            if(!empty($order_productid)){
                $eventid = get_post_meta($order_productid,'_event_id',true);
            }
            $output .= '<tr class="order">';
            foreach ($my_orders_columns as $column_id => $column_name) :
                $output .= '<td class="' . esc_attr($column_id) . '" data-title="' . esc_attr($column_name) . '">';
                if (has_action('woocommerce_my_account_my_orders_column_' . $column_id)) :
                    do_action('woocommerce_my_account_my_orders_column_' . $column_id, $order);
                elseif ('order-number' === $column_id) :
                    $output .= '<a href="' . esc_url($order->get_view_order_url()) . '">';
                    $output .= '#' . $order->get_order_number();
                    $output .= '</a>';
                elseif ('order-date' === $column_id) :
                    $output .= '<time datetime="' . esc_attr($order->get_date_created()->date('c')) . '">' . esc_html(wc_format_datetime($order->get_date_created())) . '</time>';
                elseif ('order-buyer' === $column_id) :
                    $output .= esc_html($order->get_billing_first_name()) . ' ' . esc_html($order->get_billing_last_name());
                elseif ('order-email' === $column_id) :
                    $output .= esc_html($order->get_billing_email());
                elseif ('order-status' === $column_id) :
                    $output .= esc_html(wc_get_order_status_name($order->get_status()));
                elseif ('order-total' === $column_id) :
                    $output .= $order->get_formatted_order_total();
                elseif ('order-quantity' === $column_id) :
                    $output .= 'x' . $item_count;
                elseif ('order-product' === $column_id) :
                    $output .= (!empty($eventid))?get_the_title($eventid):'';
                elseif ('order-actions' === $column_id) :
                    $actions = wc_get_account_orders_actions($order);
                    if (!empty($actions)) {
                        foreach ($actions as $key => $action) {
                            $output .= '<a href="' . esc_url($action['url']) . '" class="button ' . sanitize_html_class($key) . '">' . esc_html($action['name']) . '</a>';
                        }
                    }
                endif;
                $output .= '</td>';
            endforeach;
            $output .= '</tr>';
        endforeach;
    endif;
}else{
    $output .= '<tr>';
    $output .= '<td colspan="8">';
    $output .= '<p class="norecordtable">No record found.</p>';
    $output .= '</td>';
    $output .= '</tr>';
}
    $output .= '</tbody>';
    $output .= '</table>';
    $output .= '</div>';
    $rowCount = (isset($orders) && !empty($orders['count']))?$orders['count']:0;
    $pagesCount = ceil($rowCount / $perPageCount);
    $output.='<div class="export-list-row">';
    $output.='<div class="custom-pagination">';
    $output.='<ul>';
    $output.='<li class="pagination-list">';
    for ($i = 1; $i <= $pagesCount; $i ++) {
        if ($i == $pageNumber) {
            $output.='<a href="javascript:void(0);" class="current">'.$i.'</a>';
        } else {
            $output.='<a href="javascript:void(0);" class="pages" onclick="showRecords('.$perPageCount.', '.$i.',\'get_attendees_data\')">'.$i.'</a>';
        } // endIf
    } // endFor
    $output.='</li>';
    $output.='<li class="totalofPage">';
    //$output.='Page '.$pageNumber.' of '.$pagesCount;
    $stating = ($pageNumber-1)*$perPageCount+1;
    $stating = ($rowCount == 0)?0:$stating;
    $ending = $perPageCount*$pageNumber;
    $ending = ($rowCount < $ending)?$rowCount:$ending;
    $output.='<span class="pagenav">'.$stating.'-'.$ending.' of '.$rowCount.'</span>';
    $output.='</li>';
    $output.='</ul>';
    $output.='</div>';
    $output.='</div>';
    echo $output;
    die();
}


add_action("wp_ajax_connect_paypal", "connic_connect_paypal");
function connic_connect_paypal(){
    $data = $_POST;
    $paypalEmail = $data['paypalEmail'];
    if(!empty($paypalEmail)) {
        $current_user = wp_get_current_user();
        $current_user_id = $current_user->ID;
        update_user_meta($current_user_id, 'paypal_reciver_id', $paypalEmail);
        echo 'PayPal account has set successfully.';
    }else{
        echo 'Something want to be wrong please try again.';
    }
    die();
}

add_filter( 'woocommerce_checkout_fields' , 'custom_rename_wc_checkout_fields' );

function custom_rename_wc_checkout_fields( $fields ) {

    $fields['billing']['billing_address_2']['label'] = 'Apartment, suite, unit etc.';
    return $fields;

}
add_action("wp_ajax_get_favoritelisting_ajax", "get_favoritelisting_ajax");

function get_favoritelisting_ajax(){
    $userid = get_current_user_id();
    $list = get_myfavoratelist();                
    if (!empty($userid) && !empty($list)) {                    
        $the_query = new WP_Query( array( 'post_type' => 'event_listing', 'post__in' => $list ) );
        if ( $the_query->have_posts() ) {
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
                echo '<div class="my-ticket-card-row">';
                echo '<div class="my-ticket-pic">';
                display_event_banner();
                echo '</div>';
                echo '<div class="my-ticket-detail">';
                echo '<h3>' . get_post_by_eventid(get_the_ID()) . '</h3>';
                echo '<ul>';
                $newformate = 'D, M jS';
                echo '<li><img src="'.get_template_directory_uri().'/assets/images/clock.png" alt="">'. date_i18n( $newformate, strtotime(get_event_start_date()) ).((strtotime(get_event_start_date()) != strtotime(get_event_end_date())) ? date_i18n( ' - M jS,', strtotime(get_event_end_date()) ):','). display_event_start_time(false,false,false).'</li>';
                echo '<li><img src="'.get_template_directory_uri().'/assets/images/map-icon.png" alt="">'.display_event_venue_name(false,false,false).'</li>';
                echo '</ul>';
                echo '</div>';
                echo '<div class="ticket-status-btn align-self-center">';
                echo "<button onclick=window.open('".get_permalink(get_the_ID())."')>See Details</button>";
                //echo get_favorites_button(get_the_ID(), '');                            
                echo '<div class="mylisting-fav myfavlist">';
                $arg = array ('echo' => true );
                do_action('gd_mylist_btn',$arg);
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "<span class='no-record-span'>No record found.</span>";
        }
        wp_reset_postdata();
    }else{
        echo "<span class='no-record-span'>No record found.</span>";
    }
    die();
}