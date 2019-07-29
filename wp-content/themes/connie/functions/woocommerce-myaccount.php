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


function get_cf7_form_data($formid,$pageNumber = 1,$perPageCount = 10,$author = true){
    global $wpdb;
    $lowerLimit = ($pageNumber - 1) * $perPageCount;
    $current_user = wp_get_current_user();
    $current_user_id = $current_user->ID;
    if($author == true) {
        $resultstotal = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}db7_forms WHERE form_value REGEXP '.*\"eventauthor\";s:[0-9]+:\"$current_user_id\".*' AND form_post_id = ".$formid." order by form_id desc", ARRAY_A);
    }else {
        $resultstotal = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}db7_forms WHERE form_post_id = ".$formid." order by form_id desc", ARRAY_A);
    }
    if(!empty($resultstotal)){
        $results['count'] = count($resultstotal);
    }
    if($author == true) {
        $results['data'] = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}db7_forms WHERE form_value REGEXP '.*\"eventauthor\";s:[0-9]+:\"$current_user_id\".*' AND form_post_id = ".$formid." order by form_id desc limit " . ($lowerLimit) . " , " . ($perPageCount) . " ", ARRAY_A);
    }else{
        $results['data'] = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}db7_forms WHERE form_post_id = ".$formid." order by form_id desc limit " . ($lowerLimit) . " , " . ($perPageCount) . " ", ARRAY_A);
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
    $action = $data['action'];
    $myaccountsettings =  get_fields('account-settings');
    $formid = 1;
    if(!empty($myaccountsettings) && isset($myaccountsettings['manage_event'])){
        $formid = isset($myaccountsettings['manage_event']['volunteer_form_id'])?$myaccountsettings['manage_event']['volunteer_form_id']:1;
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
    $output .='<th>Area of Expertise</th>';
    $output .='<th>Days Avaliable</th>';
    $output .='<th>Hours Avaliable</th>';
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
            $output.='<td>Friday</td>';
            $output.='<td>5</td>';
            $output.='<td>'.(isset($item['form_data']['eventid']) && !empty($item['form_data']['eventid'])?get_post_by_eventid($item['form_data']['eventid']):'-').'</td>';
            $output.='</tr>';
        }
    }
    $output.='</tbody>';
    $output.='</table>';
    $output.='</div>';
    /*pagination */
    $rowCount = (isset($results) && !empty($results['count']))?$results['count']:0;
    $pagesCount = ceil($rowCount / $perPageCount);
    $output.='<table width="50%" align="center">';
    $output.='<tr>';
    $output.='<td valign="top" align="left"></td>';
    $output.='<td valign="top" align="center">';
	for ($i = 1; $i <= $pagesCount; $i ++) {
        if ($i == $pageNumber) {
            $output.='<a href="javascript:void(0);" class="current">'.$i.'</a>';
        } else {
            $output.='<a href="javascript:void(0);" class="pages" onclick="showRecords('.$perPageCount.', '.$i.',\'get_volunteer_data\')">'.$i.'</a>';
        } // endIf
    } // endFor
    $output.='</td>';
    $output.='<td align="right" valign="top">';
    //$output.='Page '.$pageNumber.' of '.$pagesCount;
    $stating = ($pageNumber-1)*$perPageCount+1;
    $stating = ($rowCount == 0)?0:$stating;
    $ending = $perPageCount*$pageNumber;
    $ending = ($rowCount < $ending)?$rowCount:$ending;
    $output.='<span class="pagenav">'.$stating.'-'.$ending.' of '.$rowCount.'</span>';
	$output.='</td>';
    $output.='</tr>';
    $output.='</table>';
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
    $output.='<table width="50%" align="center">';
    $output.='<tr>';
    $output.='<td valign="top" align="left"></td>';
    $output.='<td valign="top" align="center">';
    for ($i = 1; $i <= $pagesCount; $i ++) {
        if ($i == $pageNumber) {
            $output.='<a href="javascript:void(0);" class="current">'.$i.'</a>';
        } else {
            $output.='<a href="javascript:void(0);" class="pages" onclick="showRecords('.$perPageCount.', '.$i.',\'get_team_member_data\')">'.$i.'</a>';
        } // endIf
    } // endFor
    $output.='</td>';
    $output.='<td align="right" valign="top">';
    //$output.='Page '.$pageNumber.' of '.$pagesCount;
    $stating = ($pageNumber-1)*$perPageCount+1;
    $stating = ($rowCount == 0)?0:$stating;
    $ending = $perPageCount*$pageNumber;
    $ending = ($rowCount < $ending)?$rowCount:$ending;
    $output.='<span class="pagenav">'.$stating.'-'.$ending.' of '.$rowCount.'</span>';
    $output.='</td>';
    $output.='</tr>';
    $output.='</table>';
    echo $output;
    die();
}

add_action("wp_ajax_get_report_problem_contact_data", "get_report_problem_contact_data");
add_action("wp_ajax_nopriv_get_report_problem_contact_data", "get_report_problem_contact_data");

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
    $output.='<table width="50%" align="center">';
    $output.='<tr>';
    $output.='<td valign="top" align="left"></td>';
    $output.='<td valign="top" align="center">';
    for ($i = 1; $i <= $pagesCount; $i ++) {
        if ($i == $pageNumber) {
            $output.='<a href="javascript:void(0);" class="current">'.$i.'</a>';
        } else {
            $output.='<a href="javascript:void(0);" class="pages" onclick="showRecords('.$perPageCount.', '.$i.',\'get_team_member_data\')">'.$i.'</a>';
        } // endIf
    } // endFor
    $output.='</td>';
    $output.='<td align="right" valign="top">';
    //$output.='Page '.$pageNumber.' of '.$pagesCount;
    $stating = ($pageNumber-1)*$perPageCount+1;
    $stating = ($rowCount == 0)?0:$stating;
    $ending = $perPageCount*$pageNumber;
    $ending = ($rowCount < $ending)?$rowCount:$ending;
    $output.='<span class="pagenav">'.$stating.'-'.$ending.' of '.$rowCount.'</span>';
    $output.='</td>';
    $output.='</tr>';
    $output.='</table>';
    echo $output;
    die();
}

