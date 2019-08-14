<?php
add_action('rest_api_init', function () {
    register_rest_route('wp/v2', '/eventlist/', array(
        'methods' => 'GET',
        'callback' => 'callback_eventlist_func',
    ));
});

function callback_eventlist_func()
{
    /*=======Add Deviceinfo===============*/

    /*$dtoken = $_REQUEST['device_token'];
    $dtype = $_REQUEST['device_type'];

    insert_deviceinfo($dtoken, $dtype, $du_id);*/

    /*====================================*/
    //$lang = $_REQUEST['lang'];
    $du_id = !empty($_REQUEST['login_id']) ? $_REQUEST['login_id'] : 'null';
    $login_id = $du_id;

    $paged = (isset($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
    $posts_per_page = 10;
    $query_args = array(
        'post_type' => 'event_listing',
        'posts_per_page' => $posts_per_page,
        'paged' => $paged,
        'post_status' => 'publish'
    );
    /*if(isset($_REQUEST['cat'])) {
        $cat = $_REQUEST['cat'];
        $query_args[ 'category' ] = array($cat);
    }*/
    if ( ! empty( $_REQUEST['search_categories'][0] ) )
    {

        //$operator = 'all' === get_option( 'event_manager_category_filter_type', 'all' ) && sizeof( $args['search_categories'] ) > 1 ? 'AND' : 'IN';

        $query_args['tax_query'][] = array(

            'taxonomy'         => 'event_listing_category',

            'field'            => 'term_id',

            'terms'            => array_values( $_REQUEST['search_categories'] ),

            'operator'         => 'IN'
        );
    }
    if(isset($_REQUEST['upcoming']) && $_REQUEST['upcoming'] == 1) {
        $query_args[ 'orderby' ] = 'meta_value';
        $query_args[ 'order' ] = 'ASC';
        $query_args[ 'meta_key' ] = '_event_start_date';
    }
    if ( ! empty( $_REQUEST['search_location'] ) ) {

        $location_meta_keys = array( 'geolocation_formatted_address', '_event_location', 'geolocation_state_long' );

        $location_search    = array( 'relation' => 'OR' );

        foreach ( $location_meta_keys as $meta_key ) {

            $location_search[] = array(

                'key'     => $meta_key,

                'value'   => $_REQUEST['search_location'],

                'compare' => 'like'
            );
        }
        $query_args['meta_query'][] = $location_search;
    }
    if ( ! empty( $_REQUEST['ticket'] ) ) {
        $ticket_search[] = array(
            'key'     => '_event_ticket_options',

            'value'   => $_REQUEST['ticket'],

            'compare' => 'LIKE',
        );
        $query_args['meta_query'][] = $ticket_search;
    }
    $post = get_posts($query_args);
    $count_posts = wp_count_posts('event_listing');
    if (isset($cat) && !empty($cat)) {
        $cposts = get_posts('post_type=event_listing&cat=$cat');
        $pub_count = count($cposts);
    } else {
        $pub_count = $count_posts->publish;
    }
    $maxnos = ceil($pub_count / $posts_per_page);
    if (!empty($post)) {
        foreach ($post as $key => $articles) {
            $id = $articles->ID;
            $link = site_url() . "/?p=" . $id;
            $title = $articles->post_title;
            $content = $articles->post_content;
            $user_id = $articles->post_author;
            $user = get_user_by('id', $user_id);
            $user_arr = array('id' => $user->ID, 'name' => $user->first_name);
            $att_id = get_post_thumbnail_id($id);
            if (!empty($att_id)) {
                $thumb = get_the_post_thumbnail_url($id, 'feature-image');
                $full = get_the_post_thumbnail_url($id, 'full');
            } else {
                $thumb = "";
                $full = "";
            }
            $post_categories = wp_get_post_categories($id);
            $cats = array();
            foreach ($post_categories as $c) {
                $cat = get_category($c);
                $cats[] = array('id' => $cat->term_id, 'name' => $cat->name, 'slug' => $cat->slug);
            }
            $media_arr = array('id' => $att_id, 'full_url' => $full, 'thumb_url' => $thumb);
            $views = ($login_id == $user_id) ? $views : '';
//$date = human_time_diff( get_the_modified_time( 'U',$id), current_time( 'timestamp' ) ) . " " . esc_html__( '[:en]ago[:ar]منذ[:]', 'boombox' );
            $date = get_the_date('d/m/Y', $id);
            $myvals = get_post_meta($id);
            $metadata = array();
            if(!empty($myvals)){
                $aaraykey = array('_event_banner','_event_album');
                foreach ($myvals as $key => $value){
                    $metavalue = $value[0];
                    if(is_serial($metavalue)){
                        $metavalue = unserialize($metavalue);
                    }
                    $metadata[$key] = $metavalue;
                    if(in_array($key,$aaraykey) && empty($metavalue)){
                        $metadata[$key] = array();
                    }
                }
            }
            $response[] = array(
                'id' => $id,
                'link' => $link,
                'perlink' => get_permalink($id),
                'title' => $title,
                'content' => $content,
                //'social_counts' => $share_count,
                //'post_views' => $views,
                'featured_media' => $media_arr,
                'categories' => $cats,
                'author' => $user_arr,
                'date' => $date,
                'meta_data' => $metadata,
                'isThisQuizData' => false
            );
        }
        echo json_encode(array('flag' => true, 'max_num_page' => $maxnos, 'data' => $response));
    } else {
        echo json_encode(array(
            'flag' => false,
            'message' => 'there is no  event found'
        ));
    }
    exit();
}



add_action( 'rest_api_init', function () {
    register_rest_route( 'wp/v2', '/eventlist/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'callback_event_byid_func',
    ) );
} );

function callback_event_byid_func($request)
{

    $du_id  = !empty($_REQUEST['login_id']) ? $_REQUEST['login_id'] : 'null';
    $id = $request['id'];
    $login_id = $du_id;
    $args = array('p'=> $id, 'post_type' => 'event_listing','post_status'=>array('draft','pending','auto-draft','publish','reject','resubmission'));
    $my_posts = new WP_Query($args);
    if ( $my_posts->have_posts() ) {
        while ( $my_posts->have_posts() ) {
            $my_posts->the_post();
            $pid = get_the_ID();

            /*$terms = get_terms( array(
                'taxonomy' => 'event_listing_category',
                'hide_empty' => false,
                'orderby'=>'term_id',
                'order'=>'ASC'
            ) );
            foreach ($terms as $reaction_key => $reaction_row) {
                $reactionsarr = get_reaction_count($pid,$reaction_row->term_id);
                if(count($reactionsarr) > 0){
                    $total = $reactionsarr[0]->total;
                } else {
                    $total = 0;
                }
                $reactionsarray[] = array(
                    'reaction_name' => $reaction_row->slug,
                    'total'=> $total,
                    'reaction_id'=>$reaction_row->term_id,
                );
            }*/
            $title = html_entity_decode(get_the_title());
            //$title = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $title);
            $link = site_url()."/?p=".$pid;
            $content = get_the_content();
            $user_id = get_the_author_id();
            $user = get_user_by('id',$user_id);
            $user_arr = array('id'=>$user->ID,'name'=>$user->first_name);
            $att_id = get_post_thumbnail_id( $pid );
            if(!empty($att_id))
            {
                $thumb = get_the_post_thumbnail_url( $pid , 'feature-image');
                $full = get_the_post_thumbnail_url( $pid , 'full');
            }
            else
            {
                $thumb = "";
                $full = "";
            }
            $post_categories = wp_get_post_categories( $pid );
            $cats = array();
            foreach($post_categories as $c){
                $cat = get_category( $c );
                $cats[] = array('id' => $cat->term_id,'name' => $cat->name,'slug' => $cat->slug);
            }
            $date = get_the_date('d/m/Y');
            $myvals = get_post_meta($pid);
            $metadata = array();
            if(!empty($myvals)){
                $aaraykey = array('_event_banner','_event_album');
                foreach ($myvals as $key => $value){
                    $metavalue = $value[0];
                    if(is_serial($metavalue)){
                        $metavalue = unserialize($metavalue);
                    }
                    $metadata[$key] = $metavalue;
                    if(in_array($key,$aaraykey) && empty($metavalue)){
                        $metadata[$key] = array();
                    }
                }
            }
            $response1[] = array(
                'id'=> $pid,
                'link'=> $link,
                'perlink'=> get_permalink($pid),
                'title'=>$title,
                'content'=>$content,
                'categories'=>$cats,
                'author'=> $user_arr,
                'date'=>$date,
                'meta_data' => $metadata
            );
        }
        echo json_encode(array('flag'=>true,'data'=>$response1));
    } else {
        echo json_encode(array(
            'flag'=>false,
            'message'=>'invalid event ID'
        ));
    }
    exit();
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'wp/v2', '/event_cat/', array(
        'methods' => 'POST',
        'callback' => 'event_cat_func',
    ) );
} );
function event_cat_func()
{
    $quiz_categories = get_categories(array('taxonomy'=>'event_listing_category', 'hide_empty'=> 0));
    if(!empty($quiz_categories))
    {
        echo json_encode(
            array(
                'flag'=>true,
                'event_category'=>$quiz_categories,
            )
        );
    }
    else
    {
        echo json_encode(
            array(
                'flag'=>false,
                'message'=>'getting empty event taxonomies!'
            )
        );
    }
    exit;
}