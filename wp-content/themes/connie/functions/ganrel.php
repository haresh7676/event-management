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

function get_sell_start_price($event_id){
    if(!empty($event_id)) {
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_key' => '_event_id',
            'meta_value' => $event_id,
        );
        $all_tickets = get_posts($args);

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