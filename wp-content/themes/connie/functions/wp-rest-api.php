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
    global $wpdb; 
    $userid = '';
    if(!empty($login_id)){             
        $userlist = $wpdb->get_results("SELECT user_id FROM {$wpdb->prefix}usermeta WHERE meta_key = 'app_user_id' and meta_value=".$login_id,ARRAY_A);
        if(!empty($userlist) && count($userlist) > 0){
            $userid = $userlist[0]['user_id'];
        }
    }

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
    if ( ! empty( $_REQUEST['search_keywords'] ) )
    {
        $event_manager_keyword = sanitize_text_field( $_REQUEST['search_keywords'] ); 
        $query_args['s'] = $event_manager_keyword;
    }
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
        'compare' => '>',
    );

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

            if(!empty($login_id)){

            }
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
            $isFavorited = false;
            if(!empty($userid)){
                $query = "SELECT * FROM wp_gd_mylist WHERE item_id = ".$id." 
                    AND user_id = ".$userid;
                $existdata = $wpdb->get_results($query, OBJECT); 
                if(!empty($existdata)){
                    $isFavorited = true;
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
                'isFavorited' => $isFavorited
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
    global $wpdb;
    $userid = '';
    if(!empty($login_id)){
        $userlist = $wpdb->get_results("SELECT user_id FROM {$wpdb->prefix}usermeta WHERE meta_key = 'app_user_id' and meta_value=".$login_id,ARRAY_A);
        if(!empty($userlist) && count($userlist) > 0){
            $userid = $userlist[0]['user_id'];
        }
    }
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
            $isFavorited = false;
            if(!empty($userid)){
                $query = "SELECT * FROM wp_gd_mylist WHERE item_id = ".$pid."  AND user_id = ".$userid;
                $existdata = $wpdb->get_results($query, OBJECT);
                if(!empty($existdata)){
                    $isFavorited = true;
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
                'meta_data' => $metadata,
                'isFavorited' => $isFavorited
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

add_action('rest_api_init', function(){
  register_rest_route('wp/v2','/register/', array(
    'methods' => 'POST',
    'callback' => 'createuser_func',
  ));
});


function createuser_func()
  {
  $exterror = 'Only JPG, PNG and GIF files are allowed.';
  $emptyappid = 'Empty API ID.';
  $emptyupass = 'Empty username.';
  $emptyemail = 'Empty email address';
  $emailhai = 'This email or username address is already registered.';
  $reg_done = 'Successfully registered.';
  /*====================================*/
  $info = array();
  $country_code = $_REQUEST['country_code'];
  $phone_number = $_REQUEST['phone_number'];
  $appuserid = $_REQUEST['app_user_id'];
  $username = $country_code.$phone_number;
  $info['user_nicename'] = $info['nickname'] = sanitize_text_field($username);
  $info['user_login'] = trim($username);
  $info['first_name'] = sanitize_text_field($_REQUEST['fname']);
  $info['last_name'] = sanitize_text_field($_REQUEST['lname']);
  $info['display_name'] = $info['first_name'] . ' ' . $info['last_name'];
  //$info['user_pass'] = sanitize_text_field($_REQUEST['password']);
  $info['user_email'] = sanitize_email($_REQUEST['email']);
  $info['role'] = get_option('default_role');
  if (empty($_REQUEST['email']) || empty($_REQUEST['country_code']) || empty($_REQUEST['phone_number']))
    {
    $emptypassreturn = array(
      'success' => 0,
      'error' => $emptyupass,
      'errorCode' => '011'
    );
    echo json_encode(array(
      'status' => 'failure',
      'user' => $emptypassreturn
    ));
    }
  elseif (empty($_REQUEST['email']))
    {
    $emptyemailreturn = array(
      'success' => 0,
      'error' => $emptyemail,
      'errorCode' => '011'
    );
    echo json_encode(array(
      'status' => 'failure',
      'user' => $emptyemailreturn
    ));
    }
    elseif (empty($appuserid))
    {
    $emptyappuserreturn = array(
      'success' => 0,
      'error' => $emptyappid,
      'errorCode' => '011'
    );
    echo json_encode(array(
      'status' => 'failure',
      'user' => $emptyappuserreturn
    ));
    }
    else
    {
    $user_register = wp_insert_user($info);
    $user = get_user_by('ID', $user_register);
    $user_email = $user->user_email;
    $firstname = $user->first_name;
    $lastname = $user->last_name;
    /*.....Update usermeta for profile...*/
    if (!empty($attachment_id))
      {
      update_user_meta($user_register, 'wp_user_avatar', $attachment_id);
      }

    update_user_meta($user_register, 'app_user_id', $appuserid);    
    if (is_wp_error($user_register))
      {
      $error = $user_register->get_error_codes();
      if (in_array('empty_user_login', $error))
        {
        $emptyemailreturn = array(
          'success' => 0,
          'error' => esc_html($user_register->get_error_message('empty_user_login')) ,
          'errorCode' => '011'
        );
        echo json_encode(array(
          'status' => 'failure',
          'user' => $emptypassreturn
        ));
        }
      elseif (in_array('existing_user_email', $error) || in_array('existing_user_login', $error))
        {
        $emailhaireturn = array(
          'success' => 0,
          'error' => $emailhai,
          'errorCode' => '010'
        );
        echo json_encode(array(
          'status' => 'failure',
          'user' => $emailhaireturn
        ));
        }
      }
      else
      {
      $successreturn = array(
        'success' => 1,
        'message' => $reg_done,
        'errorCode' => '000',
        'userid' => $user_register
      );
      echo json_encode(array(
        'status' => 'success',
        'user' => $successreturn
      ));
      }
    }
  exit();
  }


add_action('rest_api_init', function(){
  register_rest_route('wp/v2','/addremoveFvorite/', array(
    'methods' => 'POST',
    'callback' => 'addremoveFvorite_func',
  ));
});


function addremoveFvorite_func()
  {  
  $emptyappid = 'Empty API ID.';
  $emptyfavorite = 'Empty favorite.';
  $emptyeventid = 'Empty event id.';
  $reg_done = 'Successfully added event to favorite.';
  $remove_done = 'Successfully removed event from favorite.';
  /*====================================*/
  $info = array();
  $login_id = $_REQUEST['login_id'];
  $fvorite = $_REQUEST['fvorite'];    
  $eventid = $_REQUEST['eventid'];      

  if (empty($_REQUEST['fvorite']))
    {
    $emptyfavoritereturn = array(
      'success' => 0,
      'error' => $emptyfavorite,
      'errorCode' => '011'
    );
    echo json_encode(array(
      'status' => 'failure',
      'user' => $emptyfavoritereturn
    ));
    }
  elseif (empty($_REQUEST['eventid']))
    {
    $emptyeventuidreturn = array(
      'success' => 0,
      'error' => $emptyeventid,
      'errorCode' => '011'
    );
    echo json_encode(array(
      'status' => 'failure',
      'user' => $emptyeventuidreturn
    ));
    }
    elseif (empty($login_id))
    {
    $emptyappuserreturn = array(
      'success' => 0,
      'error' => $emptyappid,
      'errorCode' => '011'
    );
    echo json_encode(array(
      'status' => 'failure',
      'user' => $emptyappuserreturn
    ));
    }
    else
    {
        $userid = '';
        global $wpdb; 
        if(!empty($login_id)){                   
            $userlist = $wpdb->get_results("SELECT user_id FROM {$wpdb->prefix}usermeta WHERE meta_key = 'app_user_id' and meta_value=".$login_id,ARRAY_A);

            if(!empty($userlist) && count($userlist) > 0){
                $userid = $userlist[0]['user_id'];
            }
        }
         if(empty($userid)){
            $emptyappuserreturn = array(
                'success' => 0,
                'error' => 'App user id not exist in web',
                'errorCode' => '011'
            );
            echo json_encode(array(
                'status' => 'failure',
                'user' => $emptyappuserreturn
            ));
        }else{
            $query = "SELECT * FROM wp_gd_mylist WHERE item_id = ".$_REQUEST['eventid']." AND user_id = ".$userid;
            $existdata = $wpdb->get_results($query, OBJECT);            
            if(!empty($fvorite) && $fvorite == 1){
                if(empty($existdata)){              
                  $wpdb->insert('wp_gd_mylist',array( 'item_id' => $_REQUEST['eventid'],'user_id' => $userid));
                }
                $successreturn = array(
                    'success' => 1,
                    'message' => $reg_done,
                    'errorCode' => '000'                
                );
                echo json_encode(array(
                    'status' => 'success',
                    'user' => $successreturn
                ));
            }
            if(!empty($fvorite) && $fvorite == 2){                
                if(!empty($existdata)){                                
                  $wpdb->delete('wp_gd_mylist', array( 'item_id' => $_REQUEST['eventid'],'user_id' => $userid) );
                  $successreturn = array(
                    'success' => 1,
                    'message' => $remove_done,
                    'errorCode' => '000'                
                    );
                    echo json_encode(array(
                        'status' => 'success',
                        'user' => $successreturn
                    ));
                }else{
                    $successreturn = array(
                    'success' => 0,
                    'message' => 'Event not exist in our Favorited list',
                    'errorCode' => '011'                
                    );
                    echo json_encode(array(
                        'status' => 'failure',
                        'user' => $successreturn
                    ));
                }
                
            }
        }
    }
  exit();
  }

/* My Favorites event listing */
add_action('rest_api_init', function () {
    register_rest_route('wp/v2', '/MyFavorites/', array(
        'methods' => 'get',
        'callback' => 'callback_favorites_func',
    ));
});

function callback_favorites_func()
{
    /*=======Add Deviceinfo===============*/

    /*$dtoken = $_REQUEST['device_token'];
    $dtype = $_REQUEST['device_type'];

    insert_deviceinfo($dtoken, $dtype, $du_id);*/

    /*====================================*/
    //$lang = $_REQUEST['lang'];
    $du_id = !empty($_REQUEST['login_id']) ? $_REQUEST['login_id'] : 'null';
    $login_id = $du_id;
    global $wpdb; 
    $userid = '';
    if(!empty($login_id)){             
        $userlist = $wpdb->get_results("SELECT user_id FROM {$wpdb->prefix}usermeta WHERE meta_key = 'app_user_id' and meta_value=".$login_id,ARRAY_A);

        if(!empty($userlist) && count($userlist) > 0){
            $userid = $userlist[0]['user_id'];
        }
    }

    if(!empty($userid)){
        $query = "SELECT item_id FROM wp_gd_mylist WHERE user_id = ".$userid;
        $existdata = $wpdb->get_results($query, ARRAY_A); 
        $postid = '';
        if(!empty($existdata)){
            $postid = array_column($existdata, 'item_id');            
        } 
        if(!empty($postid)){
            $paged = (isset($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
            $posts_per_page = 10;
            $query_args = array(
                'post_type' => 'event_listing',
                'posts_per_page' => $posts_per_page,
                'paged' => $paged,
                'post__in' => $postid
            );    
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

                    if(!empty($login_id)){

                    }
                    $views = ($login_id == $user_id) ? $views : '';
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
                    $isFavorited = false;
                    if(!empty($userid)){
                        $query = "SELECT * FROM wp_gd_mylist WHERE item_id = ".$id." 
                            AND user_id = ".$userid;
                        $existdata = $wpdb->get_results($query, OBJECT); 
                        if(!empty($existdata)){
                            $isFavorited = true;
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
                        'isFavorited' => $isFavorited
                    );
                }
                echo json_encode(array('flag' => true, 'max_num_page' => $maxnos, 'data' => $response));
            } else {
                echo json_encode(array(
                    'flag' => false,
                    'message' => 'there is no  event found'
                ));
            }
        }else{
            echo json_encode(array(
                'flag' => false,
                'message' => 'there is no  event found'
            ));
        }       
    }else{
        echo json_encode(array(
            'flag' => false,
            'message' => 'User not found'
        ));
    }

    exit();
}

add_action( 'rest_api_init', 'custom_endpoint');
function custom_endpoint() {
    register_rest_route( 'wp/v2', 'add_to_cart_product', array(
        'methods' => array('GET','POST'),
        'callback' => 'add_to_cart_product',
    ) );
    register_rest_route( 'wp/v2', 'get_cart', array(
        'methods' => array('GET','POST'),
        'callback' => 'get_cart_product',
    ) );
    register_rest_route( 'wp/v2', 'remove_productfromcart', array(
        'methods' => array('GET','POST'),
        'callback' => 'delete_single_product_cart',
    ) );
    register_rest_route( 'wp/v2', 'empty_cart', array(
        'methods' => array('GET','POST'),
        'callback' => 'empty_cart',
    ) );
}


function get_cart_product(){

    global $woocommerce,$wpdb;
    // json data 
    $json = file_get_contents('php://input');
    //Decode json data
    $someArray = json_decode($json, true);
    // Get cart product from database
    $array = $wpdb->get_results("select meta_value from ".$wpdb->prefix."usermeta where meta_key='_woocommerce_persistent_cart_1' and user_id = ".$someArray['user_id']);
    //Get serialize meta value
    $data =$array[0]->meta_value;
    //Unserialize meta value
    $cart_data = unserialize ($data);

    if(isset($cart_data['cart']) && $cart_data['cart'] !=''){
        $carttot=array();
        foreach ($cart_data['cart'] as $key) {
        $carttot[] = $key['line_total'];
        # code...
        }
        $sumcart = array_sum($carttot);
        if($someArray['coupon_code'] !==""){
        $coupon_code = $someArray['coupon_code'];
        $c = new WC_Coupon($coupon_code);
        $myArray = json_decode($c, true);
        print_r($myArray);
        if($myArray['discount_type'] == "percent"){
            $discountamt = $sumcart*$myArray['amount']/100;
            $cart_data['cart']['discount'] = $discountamt;
            $cart_data['cart']['cart_total'] = $sumcart - $discountamt;
        }
        if($myArray['discount_type'] == 'fixed_cart'){
            $cart_data['cart']['discount'] = $myArray['amount'];
            $cart_data['cart']['cart_total'] = $sumcart - $myArray['amount'];
        }
        }
        else{
            $cart_data['cart']['cart_total'] = $sumcart;
        }
        echo json_encode($cart_data['cart']);
    }
    else{
        echo json_encode(array("Message"=>'Cart is emprty add product into cart'));
    }
}
function add_to_cart_product(){

    global $woocommerce,$wpdb;
    
    $json = file_get_contents('php://input');

    $someArray = json_decode($json, true);

    if(isset($someArray['product_id']) && $someArray['product_id']!='')
    {
        $array = $wpdb->get_results("select meta_value from ".$wpdb->prefix."usermeta where meta_key='_woocommerce_persistent_cart_1' and user_id = ".$someArray['user_id']);

        $data =$array[0]->meta_value;
        $cart_data = unserialize ($data);
        $flag;

        $pid=array();
        $keyval=array();

        foreach($cart_data['cart'] as $key => $val) {
            $keyval[]=$key;
            $pid[]=$val['product_id'];
        }

        $count=count($keyval);
        if($count>0)
        {
            for($i=0;$i<$count;$i++)
            {
                $mykey=$keyval[$i];
                //print_r($cart_data['cart'][$mykey]);
                if ($someArray['product_id']==$pid[$i]) 
                { 
                    $product = wc_get_product( $someArray['product_id'] );
                    $product_price = $product->get_price();
                    if(isset($someArray['productquantity'])){
                        $cart_data['cart'][$mykey]['quantity'] = 0;
                        $cart_data['cart'][$mykey]['quantity'] = $cart_data['cart'][$mykey]['quantity']+$someArray['productquantity'];
                    }
                    else{
                        $cart_data['cart'][$mykey]['quantity'] = $cart_data['cart'][$mykey]['quantity']+1;   
                    }

                    $cart_data['cart'][$mykey]['line_subtotal'] = $cart_data['cart'][$mykey]['quantity'] * $product_price;
                    $cart_data['cart'][$mykey]['line_total'] = $cart_data['cart'][$mykey]['line_subtotal'];
                    // echo ""<pre>"";
                    /*print_r($cart_data);*/

                    $updatedquery = update_user_meta($someArray['user_id'],'_woocommerce_persistent_cart_1',$cart_data);
                    if($updatedquery == 1){
                        $carttot=array();
                        foreach ($cart_data['cart'] as $key) {
                        $carttot[] = $key['line_total'];
                        # code...
                        }
                        $sumcart = array_sum($carttot);
                        if($someArray['coupon_code'] !==""){
                            $coupon_code = $someArray['coupon_code'];
                            $c = new WC_Coupon($coupon_code);
                            $myArray = json_decode($c, true);
                            if($myArray['id'] !== 0){
                            if($myArray['discount_type'] == "percent"){
                                $discountamt = $sumcart*$myArray['amount']/100;

                                $cart_data['cart']['discount'] = round($discountamt,2);
                                $cart_data['cart']['cart_total'] = $sumcart - round($discountamt,2);
                            }
                            if($myArray['discount_type'] == 'fixed_cart'){
                                $cart_data['cart']['discount'] = $myArray['amount'];
                                $cart_data['cart']['cart_total'] = $sumcart - $myArray['amount'];
                            }
                        }
                            else{
                            $notapply = "'coupons'=>'Coupon code invalid'";
                            $cart_data['cart']['cart_total'] = $sumcart;
                        }
                        }
                        else{
                            $cart_data['cart']['cart_total'] = $sumcart;
                        }
                        //$cart_data['cart']['cart_total'] = $sumcart;
                        if(isset($notapply)){
                            echo json_encode(array('message' => 'Cart Updated coupon code invlaid', 'data'=>$cart_data,));
                        }
                        else{
                         echo json_encode(array('message' => 'Cart Updated', 'data'=>$cart_data));   
                        }
                    }
                    else{
                        $carttot=array();
                        foreach ($cart_data['cart'] as $key) {
                        $carttot[] = $key['line_total'];
                        # code...
                        }
                        $sumcart = array_sum($carttot);
                         if($someArray['coupon_code'] !==""){
                            $coupon_code = $someArray['coupon_code'];
                            $c = new WC_Coupon($coupon_code);
                            $myArray = json_decode($c, true);
                            if($myArray['id'] !== 0){
                            if($myArray['discount_type'] == "percent"){
                                $discountamt = $sumcart*$myArray['amount']/100;

                                $cart_data['cart']['discount'] = round($discountamt,2);
                                $cart_data['cart']['cart_total'] = $sumcart - round($discountamt,2);
                            }
                            if($myArray['discount_type'] == 'fixed_cart'){
                                $cart_data['cart']['discount'] = $myArray['amount'];
                                $cart_data['cart']['cart_total'] = $sumcart - $myArray['amount'];
                            }
                        }
                            else{
                            $notapply = "'coupons'=>'Coupon code invalid'";
                            $cart_data['cart']['cart_total'] = $sumcart;
                        }
                        }
                        else{
                            $cart_data['cart']['cart_total'] = $sumcart;
                        }
                        //$cart_data['cart']['cart_total'] = $sumcart;
                        if(isset($notapply)){
                            echo json_encode(array('message' => 'Cart not updated and coupon code invlaid', 'data'=>$cart_data,));
                        }
                        else{
                         echo json_encode(array('message' => 'Cart updated and coupon code apply', 'data'=>$cart_data));   
                        }
                        //echo json_encode(array('message' => 'Error','data'=>$cart_data));
                    }
                } 
            }
            if(!in_array($someArray['product_id'], $pid))
            {
                $string = WC_Cart::generate_cart_id( $someArray['product_id'], 0, array());
                $product = wc_get_product( $someArray['product_id'] );
                if(isset($someArray['productquantity']) && $someArray['productquantity'] !=''){
                    $qty = $someArray['productquantity'];
                    $line_subtotal = $qty*$product->get_price();
                    $line_total = $line_subtotal;
                }
                else{
                    $qty = 1;
                    $line_subtotal = $qty*$product->get_price();
                    $line_total = $line_subtotal;
                }
                $cart_data['cart'][$string] = array(
                    'key' => $string,
                    'product_id' => $someArray['product_id'],
                    'variation_id' => 0,
                    'variation' => array(),
                    'quantity' => $qty,
                    'line_tax_data' => array(
                        'subtotal' => array(),
                        'total' => array()
                    ),
                    'line_subtotal' => $line_subtotal,
                    'line_subtotal_tax' => 0,
                    'line_total' => $line_total,
                    'line_tax' => 0,
                );
                //echo ""<pre>"";
                /*print_r($cart_data);*/
                $updatedquery = update_user_meta($someArray['user_id'],'_woocommerce_persistent_cart_1',$cart_data);
                if($updatedquery == 1){
                    $carttot=array();
                    foreach ($cart_data['cart'] as $key) {
                    $carttot[] = $key['line_total'];
                    # code...
                    }
                    $sumcart = array_sum($carttot);
                     if($someArray['coupon_code'] !==""){
                        $coupon_code = $someArray['coupon_code'];
                        $c = new WC_Coupon($coupon_code);
                        $myArray = json_decode($c, true);
                        if($myArray['id'] !== 0){
                        if($myArray['discount_type'] == "percent"){
                            $discountamt = $sumcart*$myArray['amount']/100;
                            $cart_data['cart']['discount'] = $discountamt;
                            $cart_data['cart']['cart_total'] = $sumcart - $discountamt;
                        }
                        if($myArray['discount_type'] == 'fixed_cart'){
                            $cart_data['cart']['discount'] = $myArray['amount'];
                            $cart_data['cart']['cart_total'] = $sumcart - $myArray['amount'];
                        }
                        }
                        else{
                            $notapply = "'data'=>'Coupon code invalid'";
                            $cart_data['cart']['cart_total'] = $sumcart;
                        }
                    }
                    else{
                        $cart_data['cart']['cart_total'] = $sumcart;
                    }
                    //$cart_data['cart']['cart_total'] = $sumcart;
                    echo json_encode(array('message' => 'Product added into Cart', 'data'=>$cart_data));
                }
                else{
                    echo json_encode(array('message' => 'Error'));
                }
            }
        }

        else
        {
            $string = WC_Cart::generate_cart_id( $someArray['product_id'], 0, array());
            $product = wc_get_product($someArray['product_id'] );
            if(isset($someArray['productquantity']) && $someArray['productquantity'] !=''){
                $qty = $someArray['productquantity'];
                $line_subtotal = $qty*$product->get_price();
                $line_total = $line_subtotal;
            }
            else{
                $qty = 1;
                $line_subtotal = $qty*$product->get_price();
                $line_total = $line_subtotal;
            }
            $cart_data['cart'][$string] = array(
                'key' => $string,
                'product_id' => $someArray['product_id'],
                'variation_id' => 0,
                'variation' => array(),
                'quantity' => $qty,
                'line_tax_data' => array(
                    'subtotal' => array(),
                    'total' => array()
                ),
                'line_subtotal' => $line_subtotal,
                'line_subtotal_tax' => 0,
                'line_total' => $line_total,
                'line_tax' => 0,
            );
            /*print_r($cart_data);*/
            $updatedquery = update_user_meta($someArray['user_id'],'_woocommerce_persistent_cart_1',$cart_data);
            if($updatedquery == 1){
                $carttot=array();
                foreach ($cart_data['cart'] as $key) {
                $carttot[] = $key['line_total'];
                }
                $sumcart = array_sum($carttot);
                 if($someArray['coupon_code'] !==""){
                    $coupon_code = $someArray['coupon_code'];
                    $c = new WC_Coupon($coupon_code);
                    $myArray = json_decode($c, true);
                    if($myArray['discount_type'] == "percent"){
                        $discountamt = $sumcart*$myArray['amount']/100;
                        echo $discountamt;
                        $cart_data['cart']['discount'] = $discountamt;
                        $cart_data['cart']['cart_total'] = $sumcart - $discountamt;
                    }
                    if($myArray['discount_type'] == 'fixed_cart'){
                        $cart_data['cart']['discount'] = $myArray['amount'];
                        $cart_data['cart']['cart_total'] = $sumcart - $myArray['amount'];
                    }
                }
                else{
                    $cart_data['cart']['cart_total'] = $sumcart;
                }
                $cart_data['cart']['cart_total'] = $sumcart;
                echo json_encode(array('message' => 'Product added into Cart', 'data'=>$cart_data));
            }
            else{
                echo json_encode(array('message' => 'Product Id Invalid'));
            }
        }
        /*return cart_items(); // API response whatever you want*/
    }
}
function empty_cart(){
    global $woocommerce,$wpdb;

    $json = file_get_contents('php://input');

    $someArray = json_decode($json, true);

    $cart_data =array();

    $emptycartdata = update_user_meta($someArray['user_id'],'_woocommerce_persistent_cart_1',$cart_data);

    if($emptycartdata == 1){
        echo json_encode(array('message' => 'cart empty'));
    }
    else{
        echo json_encode(array('message' => 'cart is already empty'));
    }
}
function delete_single_product_cart(){
    global $woocommerce,$wpdb;

    $json = file_get_contents('php://input');

    $someArray = json_decode($json, true);

    $array = $wpdb->get_results("select meta_value from ".$wpdb->prefix."usermeta where meta_key='_woocommerce_persistent_cart_1' and user_id = ".$someArray['user_id']);

    $data =$array[0]->meta_value;
    
    $cart_data = unserialize ($data);
    if(isset($cart_data['cart'])){

        foreach ($cart_data['cart'] as $key => $val) {
            if($val['product_id'] == $someArray['product_id'])
            {
                $dele = $val['key'];
            }
        }
        if(isset($dele) && $dele!=''){
            unset($cart_data["cart"][$dele]); 
            $delete_product = update_user_meta($someArray['user_id'],'_woocommerce_persistent_cart_1',$cart_data);
            if($delete_product == 1)
            {
                $carttot=array();
                        foreach ($cart_data['cart'] as $key) {
                        $carttot[] = $key['line_total'];
                        # code...
                        }
                        $sumcart = array_sum($carttot);
                        if($someArray['coupon_code'] !==""){
                            $coupon_code = $someArray['coupon_code'];
                            $c = new WC_Coupon($coupon_code);
                            $myArray = json_decode($c, true);
                            if($myArray['id'] !== 0){
                            if($myArray['discount_type'] == "percent"){
                                $discountamt = $sumcart*$myArray['amount']/100;

                                $cart_data['cart']['discount'] = round($discountamt,2);
                                $cart_data['cart']['cart_total'] = $sumcart - round($discountamt,2);
                            }
                            if($myArray['discount_type'] == 'fixed_cart'){
                                $cart_data['cart']['discount'] = $myArray['amount'];
                                $cart_data['cart']['cart_total'] = $sumcart - $myArray['amount'];
                            }
                        }
                            else{
                            $notapply = "'coupons'=>'Coupon code invalid'";
                            $cart_data['cart']['cart_total'] = $sumcart;
                        }
                        }
                        else{
                            $cart_data['cart']['cart_total'] = $sumcart;
                        }
                        //$cart_data['cart']['cart_total'] = $sumcart;
                        if(isset($notapply)){
                            echo json_encode(array('message' => 'Product deleted from cart', 'data'=>$cart_data,));
                        }
                        else{
                         echo json_encode(array('message' => 'Product deleted from cart', 'data'=>$cart_data));   
                        }
                        
                        //echo json_encode(array('message' => 'Product deleted from cart', 'data'=>$cart_data[""cart""]));
            }
            else{
                echo json_encode(array('message' => 'Error in delete Product from cart'));
            }
        }
        else{
            echo json_encode(array('message' => 'There is no product found in cart' ));
        }
    }
}