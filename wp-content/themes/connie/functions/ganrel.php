<?php 
add_action( 'template_redirect', 'redirect_to_specific_page' );

function redirect_to_specific_page() {
    if(is_user_logged_in()){

    }else {
        $pages = array('create-event');
        if (is_page($pages)) {
            wp_redirect(site_url() . '/sign-in/', 301);
            exit;
        }
    }
}

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