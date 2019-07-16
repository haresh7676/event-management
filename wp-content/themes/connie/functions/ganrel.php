<?php 
add_action( 'template_redirect', 'redirect_to_specific_page' );

function redirect_to_specific_page() {
	$pages = array('create-event');
	if ( is_page($pages) && !is_user_logged_in() ) {
		wp_redirect( site_url().'/sign-in/', 301 ); 
		exit;
	}
}