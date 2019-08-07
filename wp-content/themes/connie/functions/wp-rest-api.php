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
    $du_id = !empty($_REQUEST['login_id']) ? $_REQUEST['login_id'] : 'null';
    insert_deviceinfo($dtoken, $dtype, $du_id);*/

    /*====================================*/
    //$lang = $_REQUEST['lang'];
    //$login_id = $du_id;
    if(isset($_REQUEST['cat'])) {
        $cat = $_REQUEST['cat'];
    }
    $paged = (isset($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
    $posts_per_page = 10;
    $query_args = array(
        'post_type' => 'event_listing',
        'posts_per_page' => $posts_per_page,
        'paged' => $paged,
        //'cat' => array($cat)
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
            /*$fb = get_post_meta($id, 'essb_pc_facebook', true);
            $wp = get_post_meta($id, 'essb_pc_whatsapp', true);
            $tw = get_post_meta($id, 'essb_pc_twitter', true);
            $mail = get_post_meta($id, 'essb_pc_mail', true);
            $sms = get_post_meta($id, 'essb_pc_sms', true);
            $tele = get_post_meta($id, 'essb_pc_telegram', true);
            //$views = get_article_view_count($id);
            //$total = $fb + $wp + $tw + $mail + $sms + $tele;
            //$share_count = get_sharing_count($id);*/
            $att_id = get_post_thumbnail_id($id);
            if (!empty($att_id)) {
                $thumb = get_the_post_thumbnail_url($id, 'feature-image');
                $full = get_the_post_thumbnail_url($id, 'full');
            } else {
                $thumb = "";
                $full = "";
            }
            $post_meta = get_post_meta($id, 'post_content', true);
            foreach ($post_meta as $key => $value) {
                if ($value['type'] == 'desc') {
                    $post_meta[$key]['value'] = trim($value['value']);
                }
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
            $response[] = array(
                'id' => $id,
                'link' => $link,
                'perlink' => get_permalink($id),
                'title' => $title,
                'content' => $content,
                'post_meta_content' => $post_meta,
                //'social_counts' => $share_count,
                //'post_views' => $views,
                'featured_media' => $media_arr,
                'categories' => $cats,
                'author' => $user_arr,
                'date' => $date,
                'isThisQuizData' => false
            );
        }
        echo json_encode(array('flag' => true, 'max_num_page' => $maxnos, 'data' => $response));
    } else {
        echo json_encode(array(
            'flag' => false,
            'message' => 'there is no  articles found'
        ));
    }
    exit();
}