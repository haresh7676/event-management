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
        global $wp;
        $customer_order = $wp->query_vars['view-order'];
        $order = wc_get_order($customer_order);
        //$orderid = $order->get_order_number();
        $order_items= $order->get_items();
        if(!empty($order_items)) {
            foreach ($order_items as $item_id => $item) {
                $order_productid = $item->get_product_id();
            }
            $eventid = '';
            if (!empty($order_productid)) {
                $eventid = get_post_meta($order_productid, '_event_id', true);
            }
        }

        $title = "My Tickets".(!empty($eventid)?' - '.get_post_by_eventid($eventid):'');
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


add_filter( 'woocommerce_account_menu_item_classes', 'filter_function_name_8191', 10, 2 );
function filter_function_name_8191( $classes, $endpoint ){
    global $wp;
    if('my-tickets' === $endpoint && isset( $wp->query_vars['view-order'] )){
        $classes[] = 'is-active';
    }
    return $classes;
}

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
            $output.="<td><a href='#' class='getvolunteerdata' data-formdata='".json_encode($item['form_data'])."'>".$item['form_data']['first-name']." ".$item['form_data']['last-name']."</a></td>";
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
        $output.='<td colspan="7">';
        $output.='<p class="norecordtable">No record found.</p>';
        $output.='</td>';
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
    }else{
        $output.='<tr>';
        $output.='<td colspan="5">';
        $output.='<p class="norecordtable">No record found.</p>';
        $output.='</td>';
        $output.='</tr>';
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

    //echo date("Y-m-d H:i:s"); 
    //echo $datetime = new DateTime();
    //echo $now = new \DateTime("now", new \DateTimeZone("Asia/Kolkata"));

    //pr($results);
    //exit;
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
    }else{        
        $output.='<li>';        
        $output.='<p class="norecordtable">No record found.</p>';        
        $output.='</li>';    
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

function getDatetimeNow() {
    $tz_object = new DateTimeZone('Asia/Kolkata');
    //date_default_timezone_set('Brazil/East');

    $datetime = new DateTime();
    $datetime->setTimezone($tz_object);
    return $datetime;
}

function connic_time_elapsed_string($datetime, $full = false) {
    date_default_timezone_set('Asia/Kolkata');
    $now = new DateTime;    
    //$now = getDatetimeNow();

    //$now = new \DateTime("now", new \DateTimeZone("Asia/Kolkata"));
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
    'order-number'  => __( 'Confirmation #', 'woocommerce' ),
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
                    //$output .= '<a href="' . esc_url($order->get_view_order_url()) . '">';
                    $output .= '#' . $order->get_order_number();
                    //$output .= '</a>';
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
    $customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
        'numberposts' => -1,
        'meta_key'    => '_customer_user',
        'meta_value'  => get_current_user_id(),
        'post_type'   => wc_get_order_types( 'view-orders' ),
        'post_status' => array_keys( wc_get_order_statuses() ),
    ) ) );
    if ( $customer_orders ) :
        $upcomingeveent = array();
        $pastevent = array();
        foreach ( $customer_orders as $orderkey => $customer_order ) :
            $order      = wc_get_order( $customer_order );
            $orderid = $order->get_order_number();
            $order_items= $order->get_items();
            foreach ( $order_items as $item_id => $item ) {
                $order_productid = $item->get_product_id();
            }
            $eventid = '';
            if(!empty($order_productid)){
                $eventid = get_post_meta($order_productid,'_event_id',true);
            }
            if(!empty($eventid) && get_post_status ($eventid) == 'expired'){
                $pastevent[$orderid] = $eventid;
            }else{
                $upcomingeveent[$orderid] = $eventid;
            }
        endforeach;
    endif;
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
                $key = array_search (get_the_ID(), $upcomingeveent);
                if(!empty($key)){
                    echo "<button onclick=window.open('".site_url()."/my-account/view-order/".$key."')>See Details</button>";
                }else{
                    echo "<button onclick=window.open('".get_permalink(get_the_ID())."')>See Details</button>";
                }                
                //echo get_favorites_button(get_the_ID(), '');                            
                echo '<div class="mylisting-fav myfavlist">';
                //$arg = array ('echo' => true );
                //do_action('gd_mylist_btn',$arg);
                $postid = get_the_ID(); 
                $userid = !empty(get_current_user_id())?get_current_user_id():'0';
                $isFavorited = false;
                if($userid != 0){
                    $isFavorited = usrebygetfavrite($userid,$postid);            
                }
                echo '<a href="javascript:void(0)" class="btn btn-default addermovefav" id="mylists-'.$postid.'" data-postid="'.$postid.'" data-styletarget="'.$isFavorited.'" data-userid="'.$userid.'" data-action="'.(($isFavorited == 1)?'remove':'add').'" data-ajax="'.admin_url('admin-ajax.php').'"><i class="'.(($isFavorited == 1)?'fas':'far').' fa-heart"></i></a>';
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

add_action("wp_ajax_get_upcoming_ajax", "get_upcoming_ajax");

function get_upcoming_ajax(){
    $customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
        'numberposts' => -1,
        'meta_key'    => '_customer_user',
        'meta_value'  => get_current_user_id(),
        'post_type'   => wc_get_order_types( 'view-orders' ),
        'post_status' => array_keys( wc_get_order_statuses() ),
    ) ) );
    if ( $customer_orders ) :
        $upcomingeveent = array();
        $pastevent = array();
        foreach ( $customer_orders as $orderkey => $customer_order ) :
            $order      = wc_get_order( $customer_order );
            $orderid = $order->get_order_number();
            $order_items= $order->get_items();
            foreach ( $order_items as $item_id => $item ) {
                $order_productid = $item->get_product_id();
            }
            $eventid = '';
            if(!empty($order_productid)){
                $eventid = get_post_meta($order_productid,'_event_id',true);
            }
            if(!empty($eventid) && get_post_status ($eventid) == 'expired'){
                $pastevent[$orderid] = $eventid;
            }else{
                $upcomingeveent[$orderid] = $eventid;
            }
        endforeach;
    endif;
    if(!empty($upcomingeveent)) {
        $the_query = new WP_Query( array( 'post_type' => 'event_listing', 'post__in' => $upcomingeveent ) );
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
                $key = array_search (get_the_ID(), $upcomingeveent);
                echo "<button onclick=window.open('".site_url()."/my-account/view-order/".$key."')>See Details</button>";
                //echo get_favorites_button(get_the_ID(), '');
                echo '<div class="mylisting-fav">';
                //$arg = array ('echo' => true );
                //do_action('gd_mylist_btn',$arg);
                $postid = get_the_ID(); 
                $userid = !empty(get_current_user_id())?get_current_user_id():'0';
                $isFavorited = false;
                if($userid != 0){
                    $isFavorited = usrebygetfavrite($userid,$postid);            
                }
                echo '<a href="javascript:void(0)" class="btn btn-default addermovefav" id="mylists-'.$postid.'" data-postid="'.$postid.'" data-styletarget="'.$isFavorited.'" data-userid="'.$userid.'" data-action="'.(($isFavorited == 1)?'remove':'add').'" data-ajax="'.admin_url('admin-ajax.php').'"><i class="'.(($isFavorited == 1)?'fas':'far').' fa-heart"></i></a>';

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

add_action("wp_ajax_get_pastevent_ajax", "get_pastevent_ajax");

function get_pastevent_ajax(){
    $customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
        'numberposts' => -1,
        'meta_key'    => '_customer_user',
        'meta_value'  => get_current_user_id(),
        'post_type'   => wc_get_order_types( 'view-orders' ),
        'post_status' => array_keys( wc_get_order_statuses() ),
    ) ) );
    if ( $customer_orders ) :
        $upcomingeveent = array();
        $pastevent = array();
        foreach ( $customer_orders as $orderkey => $customer_order ) :
            $order      = wc_get_order( $customer_order );
            $orderid = $order->get_order_number();
            $order_items= $order->get_items();
            foreach ( $order_items as $item_id => $item ) {
                $order_productid = $item->get_product_id();
            }
            $eventid = '';
            if(!empty($order_productid)){
                $eventid = get_post_meta($order_productid,'_event_id',true);
            }
            if(!empty($eventid) && get_post_status ($eventid) == 'expired'){
                $pastevent[$orderid] = $eventid;
            }else{
                $upcomingeveent[$orderid] = $eventid;
            }
        endforeach;
    endif;
    if(!empty($pastevent)) {
        $the_query = new WP_Query( array( 'post_type' => 'event_listing', 'post__in' => $pastevent ) );
        if ( $the_query->have_posts() ) {
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
                echo '<div class="my-ticket-card-row past-events">';
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

/*function action_woocommerce_review_order_after_order_total() { 
    echo '<button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>';
}         

add_action( 'woocommerce_review_order_after_order_total', 'action_woocommerce_review_order_after_order_total', 10 ); */

add_action("wp_ajax_addremove_favoritelisting_ajax", "addremove_favoritelisting_ajax");
add_action("wp_ajax_nopriv_addremove_favoritelisting_ajax", "addremove_favoritelisting_ajax");

function addremove_favoritelisting_ajax(){    
    global $wpdb; 
    $data = $_POST;
    $dataaction = $data['dtaaction'];
    $postid = $data['postid'];
    $userid = $data['userid'];
    $return = array();
    if($dataaction == 'add'){
        $wpdb->insert('wp_gd_mylist',array( 'item_id' => $postid,'user_id' => $userid));
        $return['action'] ='added';
        $return['msg'] = get_the_title($postid).' has been added to your Favorites List.';
    }elseif($dataaction == 'remove'){
        $wpdb->delete('wp_gd_mylist', array( 'item_id' => $postid,'user_id' => $userid) );
        //echo 'removed';
        $return['action'] ='removed';
        $return['msg'] = get_the_title($postid).' has been removed to your Favorites List.';
    }else{
        //echo 'error';
        $return['action'] ='error';
        $return['msg'] = 'Something went wrong please try after some time.';
    }
    echo json_encode($return);
    die();
}

function usrebygetfavrite($userid,$postid){
    global $wpdb; 
    $isFavorited = false;
    if(!empty($userid)){
        $query = "SELECT * FROM wp_gd_mylist WHERE item_id = ".$postid." 
            AND user_id = ".$userid;
        $existdata = $wpdb->get_results($query, OBJECT); 
        if(!empty($existdata)){
            $isFavorited = true;
        }
    }
    return $isFavorited;
}

add_action("wp_ajax_get_volunteer_dataajax", "get_volunteer_dataajax");
function get_volunteer_dataajax(){
    $data = $_POST['formdata'];        
    $html = '<div class="volunteer-modal-content">';
    $html .='<p><b>First name: </b>'.(!empty($data['first-name'])?$data['first-name']:'-').'</p>';
    $html .='<p><b>Last name: </b>'.(!empty($data['last-name'])?$data['last-name']:'-').'</p>';  
    $html .='<p><b>Email: </b>'.(!empty($data['your-email'])?$data['your-email']:'-').'</p>';  
    $html .='<p><b>Phone: </b>'.(!empty($data['tel-phone'])?$data['tel-phone']:'-').'</p>';  
    $html .='<p><b>Area of Expertise: </b>'.(!empty($data['area-of-expertise'])?$data['area-of-expertise']:'-').'</p>';  
    $html .='<p><b>Days Available: </b>'.(!empty($data['days-available']) && is_array($data['days-available'])?implode(", ",$data['days-available']):$data['days-available']).'</p>';
    $html .='<p><b>Hours Available: </b>'.(!empty($data['hours-available'])?$data['hours-available']:'-').'</p>';
    $html .='<p><b>Which areas are you best suited to volunteer?: </b>'.(!empty($data['best-suited-area'])?$data['best-suited-area']:'-').'</p>';
    $html .='<p><b>Why do you think you would be a good volunteer?: </b>'.(!empty($data['why-good-volunteer'])?$data['why-good-volunteer']:'-').'</p>';
    $html .='<p><b>Which times/shifts could you be available?: </b>'.(!empty($data['times-shofts'])?$data['times-shofts']:'-').'</p>';
    $html .='<p><b>How did you hear about volunteering?: </b>'.(!empty($data['how-hear'])?$data['how-hear']:'-').'</p>';
    $html .='<p><b>What other events have you volunteered at?: </b>'.(!empty($data['other-events'])?$data['other-events']:'-').'</p>';      
    $html .='</div>';
    echo $html;
    die();
}

add_action("wp_ajax_get_discount_code_data", "get_discount_code_data");
add_action("wp_ajax_nopriv_get_discount_code_data", "get_discount_code_data");

function get_discount_code_data() {
    $data = $_POST;
    $pageNumber = $data['pageNumber'];
    $perPageCount = $data['perPageCount'];
    $action = $data['action'];
    /*$myaccountsettings =  get_fields('account-settings');
    $formid = 1;
    if(!empty($myaccountsettings) && isset($myaccountsettings['manage_event'])){
        $formid = isset($myaccountsettings['manage_event']['add_team_form_id'])?$myaccountsettings['manage_event']['add_team_form_id']:1;
    }
    $results = get_cf7_form_data($formid,$pageNumber,$perPageCount,true);*/
    $post_author_id = get_current_user_id();
    $posts_args = array(
        'orderby' => 'post_date',
        'order' => 'DESC',
        'post_type' => 'shop_coupon',
        'post_status' => 'any',
        'posts_per_page' => -1,
        'author' => $post_author_id        
    );            
    $resultstotal = get_posts($posts_args);
    $posts_args['posts_per_page'] = $perPageCount;
    if(!empty($pageNumber) && $pageNumber != 1){        
        $posts_args['paged'] = $pageNumber;
    }
    $results = get_posts($posts_args);
    $output = '';
    $output .='<div class="table-responsive">';
    $output .='<table class="table">';
    $output .='<thead class="thead-purple">';
    $output .='<tr>';
    $output .='<th>Code</th>';
    $output .='<th>Discount</th>';
    $output .='<th>Type</th>';
    $output .='<th>Quantity</th>';
    $output .='<th>Uses</th>';
    $output .='<th>State</th>';    
    $output .='<th>&nbsp;</th>';
    $output .='</tr>';
    $output .='</thead>';
    $output .='<tbody>';
    if(isset($results) && !empty($results)){
        foreach ($results as $item){
            $couponid = $item->ID;
            $discount_type = get_post_meta($couponid, 'discount_type', true );
            $amount = get_post_meta($couponid, 'coupon_amount', true );
            $usage_limit = get_post_meta($couponid, 'usage_limit', true );
            $usage_count = get_post_meta($couponid, 'usage_count', true );            
            $output.='<tr>';
            $output.='<td>'.$item->post_title.'</td>';
            $output.='<td>'.(!empty($amount)?$amount:'0').'</td>';
            $output.='<td>'.((!empty($discount_type) && $discount_type == 'percent')?'Percent':'Fixed amount').'</td>';
            $output.='<td>'.((!empty($usage_limit) && $usage_limit != 0)?$usage_limit:'Unlimited').'</td>';
            $output.='<td>'.(!empty($usage_count)?$usage_count:0).'</td>';
            $output.='<td><div class="switchwpr"><label class="switch"><input class="coupon-action-edit" data-id="'.$couponid.'" type="checkbox" '.(($item->post_status == 'publish')?'checked':'').'><span class="slider round"></span></label></div></td>';            
            $output.='<td><a href="javascript:void(0)" data-id="'.$couponid.'" class="coupon-action-delete" data-original-title="" title="" style="cursor: pointer;"><i class="far fa-trash-alt" title="Delete"></i></a></td>';
            $output.='</tr>';
        }
    }else{
        $output.='<tr>';
        $output.='<td colspan="7">';
        $output.='<p class="norecordtable">No Coupon found.</p>';
        $output.='</td>';
        $output.='</tr>';
    }    
    $output.='</tbody>';
    $output.='</table>';
    $output.='</div>';
    /*pagination */
    $rowCount = (isset($resultstotal) && !empty($resultstotal))?count($resultstotal):0;
    $pagesCount = ceil($rowCount / $perPageCount);
    $output.='<div class="export-list-row">';
    $output.='<button data-toggle="modal" data-target="#AddcouponModal">Add discount code</button>';
    $output.='<a href="#" class="reloaddiscount"></a>';
    $output.='<div class="custom-pagination">';
    $output.='<ul>';
    $output.='<li class="pagination-list">';
    for ($i = 1; $i <= $pagesCount; $i ++) {
        if ($i == $pageNumber) {
            $output.='<a href="javascript:void(0);" class="current">'.$i.'</a>';
        } else {
            $output.='<a href="javascript:void(0);" class="pages" onclick="showRecords('.$perPageCount.', '.$i.',\'get_discount_code_data\')">'.$i.'</a>';
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

add_action("wp_ajax_coupon_code_ajax_action", "coupon_code_ajax_action");
function coupon_code_ajax_action(){
    $data = $_POST;
    if(!empty($data['couponid']) && !empty($data['status'])){
        $my_post = array(
            'ID'           => $data['couponid'],
            'post_status'  => $data['status']
        ); 
        // Update the post into the database
        wp_update_post( $my_post );        
    }
    die();
}