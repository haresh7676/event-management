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
function get_myfavoratelist(){
     $userid = get_current_user_id();
     global $wpdb;
     $r = $wpdb->get_col($wpdb->prepare( "SELECT item_id FROM {$wpdb->prefix}gd_mylist WHERE user_id = '%s' ORDER BY insert_date DESC", $userid) );
     
     return $r;     
}
function get_sell_start_price($event_id){
    if(!empty($event_id)) {
        global $wpdb;
        if( empty( $event_id ) )
            return;
        /*$r = $wpdb->get_col( $wpdb->prepare( "SELECT meta_value FROM  {$wpdb->postmeta} WHERE meta_key LIKE '_price' AND meta_value != '0' AND post_id IN (SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key LIKE '_event_id' AND meta_value LIKE '%s') ORDER BY meta_value ASC LIMIT 1", $event_id) );*/
        $r = $wpdb->get_col( $wpdb->prepare( "SELECT meta_value FROM  {$wpdb->postmeta} WHERE meta_key LIKE '_price' AND post_id IN (SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key LIKE '_event_id' AND meta_value LIKE '%s') ORDER BY meta_value ASC LIMIT 1", $event_id) );
        if(!empty($r)){
            if($r[0] == 0 || $r[0] == ''){
                return 'Free Ticket Available';
            }else {
                return 'Starts at ' . wc_price($r[0]);
            }
        }else{
            return 'No Ticket Available';
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
    $datetime=date('Y-m-d');
    $query_args['meta_query'][] = array(
        'key' => '_event_start_date',
        'value'   => $datetime,
        'type' => 'date',
        'compare' => '>=',
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

function connice_my_save_account_details_redirect($user_id){
    wp_safe_redirect( wc_get_endpoint_url( 'edit-account') );
    exit;
}
add_action( 'woocommerce_save_account_details', 'connice_my_save_account_details_redirect', 10, 1 );

function connice_my_save_address_redirect($user_id, $load_address){
    // $load_address is either 'billing' or 'shipping'
    wp_safe_redirect( wc_get_endpoint_url( 'edit-address', $load_address) );
    exit;
}
add_action( 'woocommerce_customer_save_address', 'connice_my_save_address_redirect', 10, 2 );


function connice_remove_password_strength() {
    wp_dequeue_script( 'wc-password-strength-meter' );
}
add_action( 'wp_print_scripts', 'connice_remove_password_strength', 10 );
/* get event list by current users */
if(!function_exists('connice_event_listing_by_current_user')){
    function connice_event_listing_by_current_user($fields = '',$all = '-1'){
        $args     = apply_filters( 'event_manager_get_dashboard_events_args', array(
            'post_type'           => 'event_listing',
            'post_status'         => array( 'publish', 'expired', 'pending' ),
            'ignore_sticky_posts' => 1,
            'posts_per_page'      => $all,
            'orderby'             => 'date',
            'order'               => 'desc',
            'author'              => get_current_user_id()
       ) );
        if(!empty($fields)){
            $args['fields'] = $fields;
        }
        $events = get_posts($args);
        return $events;
    }
}
/* get product list by current users */
if(!function_exists('connice_product_list_by_events')){
    function connice_product_list_by_events($events = array(),$fields = '',$all = '-1'){
        if(!empty($events) && count($events) > 0){
            $events = implode (", ", $events);
        }
        if(count($events) <= 0)
            return;

        $args     = array(
            'post_type'           => 'product',
            'post_status'         => array( 'publish'),
            'ignore_sticky_posts' => 1,
            'posts_per_page'      => $all,
            'order'      => 'ASC',
            'meta_query' => array(
                array(
                    'key'   => '_event_id',
                    'value' => ($events),
                    'compare' => 'IN'
                )
            )
        );
        if(!empty($fields)){
            $args['fields'] = $fields;
        }
        $products = get_posts($args);
        return $products;
    }
}

/*$ids = array(170);
$orderids = get_orders_ids_by_product_id($ids);*/
/*$events = connice_event_listing_by_current_user('ids');
$productids = connice_product_list_by_events($events,'ids');
$orders = get_orders_ids_by_product_id($productids);*/

//exit;
function get_orders_ids_by_product_id( $productids = array(),$pageNumber = 1,$perPageCount = 10, $order_status = array( 'wc-completed','wc-processing' ) ){
    global $wpdb;
    $lowerLimit = ($pageNumber - 1) * $perPageCount;
    if(!empty($productids) && count($productids) > 0){
        $productids = implode (", ", $productids);
    }
    if(count($productids) <= 0)
        return;

    $resultstotal = $wpdb->get_col("
        SELECT order_items.order_id
        FROM {$wpdb->prefix}woocommerce_order_items as order_items
        LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
        LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
        WHERE posts.post_type = 'shop_order'
        AND posts.post_status IN ( '" . implode( "','", $order_status ) . "' )
        AND order_items.order_item_type = 'line_item'
        AND order_item_meta.meta_key = '_product_id'
        AND order_item_meta.meta_value IN ($productids)");

    if(!empty($resultstotal)){
        $resultstotal = array_unique($resultstotal);
        $results['count'] = count($resultstotal);
    }

    $resultsget = $wpdb->get_col("
        SELECT order_items.order_id
        FROM {$wpdb->prefix}woocommerce_order_items as order_items
        LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
        LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
        WHERE posts.post_type = 'shop_order'
        AND posts.post_status IN ( '" . implode( "','", $order_status ) . "' )
        AND order_items.order_item_type = 'line_item'
        AND order_item_meta.meta_key = '_product_id'
        AND order_item_meta.meta_value IN ($productids) limit " . ($lowerLimit) . " , " . ($perPageCount) . " ");

    if(!empty($resultsget)){
        $resultsget = array_unique($resultsget);
    }
    $results['data'] = $resultsget;
    return $results;
}


add_action( 'wp_head', 'reportproblem_wp_footer' );
 
function reportproblem_wp_footer() {
    $myaccountsettings =  get_fields('account-settings');
    $formid = 1;
    $formid2 = 1;
    if(!empty($myaccountsettings) && isset($myaccountsettings['manage_event'])){
        $formid = isset($myaccountsettings['manage_event']['report_a_problem_form_id'])?$myaccountsettings['manage_event']['report_a_problem_form_id']:1;
    }
    if(!empty($myaccountsettings) && isset($myaccountsettings['manage_event'])){
        $formid2 = isset($myaccountsettings['manage_event']['volunteer_form_id'])?$myaccountsettings['manage_event']['volunteer_form_id']:1;
    }
    if(!empty($myaccountsettings) && isset($myaccountsettings['manage_event'])){
        $formid3 = isset($myaccountsettings['manage_event']['add_discount_code_form_id'])?$myaccountsettings['manage_event']['add_discount_code_form_id']:1;
    }
?>
<script type="text/javascript">
    jQuery(document).ready(function() {
        var formid = '<?php echo $formid ?>';
        var formid2 = '<?php echo $formid2 ?>';
        var formid3 = '<?php echo $formid3 ?>';        
        document.addEventListener( 'wpcf7mailsent', function( event ) {            
            if (formid == event.detail.contactFormId) {

            } else if (formid2 == event.detail.contactFormId){                
                jQuery('.wpcf7-mail-sent-ok').ajaxComplete(function(){jQuery(this).delay(2000).fadeOut('slow');});
            } else if (formid3 == event.detail.contactFormId){                
                jQuery(".wpcf7").on('mailsent.wpcf7', function(e) {                  
                    jQuery('.reloaddiscount').trigger('click');                    
                });
            }
        }, false );
    });
</script>
<?php
}

//add_action( 'wp_footer', 'mycustom_wp_footer' );
 
function mycustom_wp_footer() {
?>
<script type="text/javascript">
document.addEventListener( 'wpcf7submit', function( event ) {
    //window.scrollTo({ top: 0, behavior: 'smooth' });
    jQuery(".modal").scrollTop(0);   
    jQuery(".wpcf7").on('invalid', function(e) {
        jQuery('html, body, .modal').animate({
            scrollTop: jQuery(".wpcf7-not-valid").first().offset().top - 150
        }, 2000);
    });
     jQuery('.wpcf7-mail-sent-ok').ajaxComplete(function(){jQuery(this).delay(2000).fadeOut('slow');});
}, false );
</script>
<?php
}

function is_serial($string) {
    return (@unserialize($string) !== false);
}


/*
add_filter( 'the_editor', 'add_required_attribute_to_wp_editor', 10, 1 );

function add_required_attribute_to_wp_editor( $editor ) {
    $editor = str_replace( '<textarea', '<textarea required="required"', $editor );
    return $editor;
}
*/

add_filter('gettext', 'core_text_changes_func');
function core_text_changes_func($translated_text){
    if($translated_text == 'Sorry, that username already exists!'){
        $translated_text = 'Sorry, that phone number already exists!';
    }
    return $translated_text;
}

add_action( 'wpcf7_before_send_mail', 'wpcf7_add_text_to_mail_body' );

function wpcf7_add_text_to_mail_body($contact_form){
    $form_id = $_POST['_wpcf7'];
    $myaccountsettings =  get_fields('account-settings');    
    if(!empty($myaccountsettings) && isset($myaccountsettings['manage_event'])){
        $formid = isset($myaccountsettings['manage_event']['report_a_problem_form_id'])?$myaccountsettings['manage_event']['report_a_problem_form_id']:1;
    }    
     if (!empty($formid) && $form_id == $formid && !empty($_POST['organizername'])): 

        $toEmail = $_POST['organizername'];
     
        // set the email address to recipient
        $mailProp = $contact_form->get_properties('mail');
        $mailProp['mail']['recipient'] = $toEmail;
        $contact_form->set_properties(array('mail' => $mailProp['mail']));
    endif;

}

add_filter('cf7_2_post_status_shop_coupon','filter_darft_to_publish',10,3);
function filter_darft_to_publish($post_status, $form_id, $form_data){
  if(isset($form_data['post_status'])){    
    $post_status = $form_data['post_status'];
  }
  return $post_status;
}


add_filter( 'woocommerce_coupon_is_valid', 'custom_woocommerce_coupon_is_valid', 1, 2 );

function custom_woocommerce_coupon_is_valid( $valid, $coupon ) {
    global $wpdb, $woocommerce;    
    $productids = array();
    $couponid = $coupon->id;    
    if(!empty($couponid)){
        $post_author_id = get_post_field( 'post_author', $couponid );
        wp_reset_query();
        if(!empty($post_author_id)){
            $posts_args = array(
                'orderby' => 'post_date',
                'order' => 'DESC',
                'post_type' => 'product',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'author' => $post_author_id,
                'fields' => 'ids'
            );            
            $productids = get_posts($posts_args);
        }
    }    
    /*
    $min_quantity = 12;*/

    if (sizeof($productids)>0) {
        /*pr($woocommerce->cart->get_cart());
        exit;*/
        $valid = false;
        if (sizeof($woocommerce->cart->get_cart())>0) {
            foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) {
                if (in_array($cart_item['product_id'], $productids) || in_array($cart_item['variation_id'], $productids)) {
                    //if ( $cart_item['qty'] > $min_quantity ) $valid = true;
                    $valid = true;
                }

            }
        }
    }
    return $valid;
}