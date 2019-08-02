<?php 
add_action( 'template_redirect', function() {

    if ( is_user_logged_in()) return;

    if ( is_page('create-event') ) {
        wp_redirect( site_url( '/sign-in' ) );
        exit();
    }

});
function getYoutubeEmbedUrl($url)
{
    $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_-]+)\??/i';
    $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i';

    if (preg_match($longUrlRegex, $url, $matches)) {
        $youtube_id = $matches[count($matches) - 1];
    }

    if (preg_match($shortUrlRegex, $url, $matches)) {
        $youtube_id = $matches[count($matches) - 1];
    }
    return 'https://www.youtube.com/embed/' . $youtube_id ;
}


add_filter( 'query_vars', 'event_query_vars' );
function event_query_vars( $query_vars )
{
    $query_vars[] = 'event_action';
    return $query_vars;
}

/* Custom re-write rules */
add_action( 'init', 'property_init' );
function property_init()
{
    global
    $wp,$wp_rewrite;
    add_rewrite_tag( '%event_action%', '([^/]*)' );
    add_rewrite_rule(
        '^event/([^/]*)/([^/]*)/?$',
        'index.php?event_listing=$matches[1]&event_action=$matches[2]',
        'top'
    );
    /*add_rewrite_rule(
        '^event/?([^/]*)/?',
        'index.php?post_type=event_listing&event_action=$matches[1]',
        'top'
    );
    add_rewrite_rule(
        '^'.$property_temp_slug.'/([^/]*)$',
        'index.php?pagename='.$property_temp_slug.'&property_id=$matches[1]',
        'top' ); */
    /*add_rewrite_rule(
        '^'.$browse_temp_slug.'/([^/]*)$',
        'index.php?pagename='.$browse_temp_slug.'&complex_name=$matches[1]',
        'top' );*/
    /*add_rewrite_rule(
        '^'.$browse_temp_slug.'/([^/]*)$',
        'index.php?pagename='.$browse_temp_slug.'&town_name=$matches[2]&complex_name=$matches[3]',
        'top' );*/
    /*add_rewrite_rule(
        '^'.$browse_temp_slug.'(/([^/]+))?(/([^/]+))?/?',
        'index.php?pagename='.$browse_temp_slug.'&town_name=$matches[2]&complex_name=$matches[4]',
        'top'
    );*/
}
//get_sell_start_price(12);
function get_sell_start_price($event_id){
    if(!empty($event_id)) {
        global $wpdb;

        if( empty( $event_id ) )
            return;

        $r = $wpdb->get_col( $wpdb->prepare( "SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_key LIKE '_price' AND post_id IN (SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key LIKE '_event_id' AND meta_value LIKE '%s') ORDER BY meta_value ASC LIMIT 1", $event_id) );
        if(!empty($r)){
            if($r[0] == 0 || $r[0] == ''){
                return 'Free Ticket Available';
            }else {
                return 'Starts at ' . wc_price($r[0]);
            }
        }
    }
}

function theme_name_custom_orderby_query_args( $query_args ) {
    $query_args[ 'meta_key' ] = '_event_start_date';
    $query_args['meta_query'][] = array(
        'key' => '_event_publish__status',
        'value' => 'public',
        'type' => 'CHAR',
        'compare' => '='
    );
    return $query_args;
}

add_filter( 'get_event_listings_query_args', 'theme_name_custom_orderby_query_args', 99 );


function connice_add_cpts_to_api( $args, $post_type ) {
    if ( 'event_listing' === $post_type ) {
        $args['show_in_rest'] = true;
    }
    return $args;
}
add_filter( 'register_post_type_args', 'connice_add_cpts_to_api', 10, 2 );

function my_save_account_details_redirect($user_id){
    wp_safe_redirect( wc_get_endpoint_url( 'edit-account') );
    exit;
}
add_action( 'woocommerce_save_account_details', 'my_save_account_details_redirect', 10, 1 );

function my_save_address_redirect($user_id, $load_address){
    // $load_address is either 'billing' or 'shipping'
    wp_safe_redirect( wc_get_endpoint_url( 'edit-address', $load_address) );
    exit;
}
add_action( 'woocommerce_customer_save_address', 'my_save_address_redirect', 10, 2 );


function iconic_remove_password_strength() {
    wp_dequeue_script( 'wc-password-strength-meter' );
}
add_action( 'wp_print_scripts', 'iconic_remove_password_strength', 10 );



add_action("wp_ajax_get_attendees_data", "get_attendees_data");
add_action("wp_ajax_nopriv_get_attendees_data", "get_attendees_data");

function get_attendees_data() {
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
    $output .='<ul>';
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
    $output.='<table width="50%" align="center">';
    $output.='<tr>';
    $output.='<td valign="top" align="left"></td>';
    $output.='<td valign="top" align="center">';
    for ($i = 1; $i <= $pagesCount; $i ++) {
        if ($i == $pageNumber) {
            $output.='<a href="javascript:void(0);" class="current">'.$i.'</a>';
        } else {
            $output.='<a href="javascript:void(0);" class="pages" onclick="showRecords('.$perPageCount.', '.$i.',\'get_report_problem_contact_data\')">'.$i.'</a>';
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
/*$ids = array(170);
$orderids = get_orders_ids_by_product_id($ids);*/
function get_orders_ids_by_product_id( $product_id, $order_status = array( 'wc-completed','wc-processing' ) ){
    global $wpdb;
    $products = implode (", ", $product_id);
    $results = $wpdb->get_col("
        SELECT order_items.order_id
        FROM {$wpdb->prefix}woocommerce_order_items as order_items
        LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
        LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
        WHERE posts.post_type = 'shop_order'
        AND posts.post_status IN ( '" . implode( "','", $order_status ) . "' )
        AND order_items.order_item_type = 'line_item'
        AND order_item_meta.meta_key = '_product_id'
        AND order_item_meta.meta_value IN ($products)
    ");

    return $results;
}