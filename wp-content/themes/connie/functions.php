<?php
function projectname_setup() {

	load_theme_textdomain( 'Connie' );

	add_theme_support( 'automatic-feed-links' );

	add_theme_support( 'title-tag' );

	add_theme_support( 'post-thumbnails' );

	register_nav_menus( array(
		'top'    => __( 'Top Menu', 'Connie' ),
		'social' => __( 'Social Links Menu', 'Connie' ),
	) );


	add_theme_support( 'custom-logo', array(
		'width'       => 250,
		'height'      => 250,
		'flex-width'  => true,
	) );

}
add_action( 'after_setup_theme', 'projectname_setup' );


function projectname_scripts() {
	// Theme stylesheet.
	wp_enqueue_style( 'Connie', get_stylesheet_uri() );

    wp_enqueue_style( 'bootstrap-minis', get_theme_file_uri( '/assets/css/bootstrap.min.css' ), array(), '1.0' );

    wp_enqueue_style( 'fonts-css', get_theme_file_uri( '/assets/css/fonts.css' ), array(), '1.0' );

	wp_enqueue_style( 'font-awesome-css', get_theme_file_uri( '/assets/css/fontawesome.css' ), array(), '1.0' );

    wp_enqueue_style( 'main-css', get_theme_file_uri( '/assets/css/main.css' ), array(), rand(0,999) );

    wp_enqueue_style( 'main-jb-css', get_theme_file_uri( '/assets/css/main-jb.css' ), array(), '1.0' );

    wp_enqueue_style( 'custom-css', get_theme_file_uri( '/assets/css/custom.css' ), array(), rand(0,999) );

    

    /*    wp_enqueue_style( 'magnific-popup-css', get_theme_file_uri( '/assets/css/magnific-popup.css' ), array(), '1.0' );

       wp_enqueue_style( 'style-css', get_theme_file_uri( '/assets/css/style.css' ), array(), '1.0' );



       wp_enqueue_script( 'jquery-3-js', get_theme_file_uri( '/assets/js/jquery-3.js' ), array( 'jquery' ), '1.0', true );

       wp_enqueue_script( 'waterpipe', get_theme_file_uri( '/assets/js/waterpipe.js' ), array( 'jquery' ), '4.0.0', true );

       wp_enqueue_script( 'jquery_002', get_theme_file_uri( '/assets/js/jquery_002.js' ), array( 'jquery' ), '1.0', true );

       //wp_enqueue_script( 'fullpage-js', get_theme_file_uri( '/assets/js/jquery.fullpage.min.js' ), array( 'jquery' ), '1.0', true );

       wp_enqueue_script( 'hammer-js', get_theme_file_uri( '/assets/js/hammer.js' ), array( 'jquery' ), '1.0', true );

       wp_enqueue_script( 'DirectionAwareHover-js', get_theme_file_uri( '/assets/js/DirectionAwareHover.js' ), array( 'jquery' ), '1.0', true );

       wp_enqueue_script( 'jquery_003-js', get_theme_file_uri( '/assets/js/jquery_003.js' ), array( 'jquery' ), '1.0', true );

       wp_enqueue_script( 'jquery-jss', get_theme_file_uri( '/assets/js/jquery.js' ), array( 'jquery' ), '1.0', true );

       wp_enqueue_script( 'script-jss', get_theme_file_uri( '/assets/js/script.js' ), array( 'jquery' ), rand(0,999), true );*/



	wp_enqueue_script( 'custom-js', get_theme_file_uri( '/assets/js/custom.js' ), array( 'jquery' ), '1.0', true );

	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'projectname_scripts' );

/*===== Option Page =====*/
if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Theme Header Settings',
		'menu_title'	=> 'Header',
        'post_id'       => 'header-settings',
		'parent_slug'	=> 'theme-general-settings',
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Theme Footer Settings',
		'menu_title'	=> 'Footer',
		'parent_slug'	=> 'theme-general-settings',
	));
	
}
/*===== Option Page =====*/
/*===== SVG =====*/
function cc_mime_types($mimes) {
	$mimes['svg'] = 'image/svg+xml';
	$mimes['ico'] = 'image/ico';
	return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');
/*===== SVG =====*/

/* Used for adding a bunch of function files, so it's possible to contain related code */
foreach (glob(__DIR__ . '/functions/*.php') as $file) {
    include_once $file;
}

function twentysixteen_widgets_init() {
    register_sidebar(
        array(
            'name'          => __( 'Sidebar', 'twentysixteen' ),
            'id'            => 'sidebar-1',
            'description'   => __( 'Add widgets here to appear in your sidebar.', 'twentysixteen' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );

    register_sidebar(
        array(
            'name'          => __( 'Upcoming Event Landing page', 'twentysixteen' ),
            'id'            => 'sidebar-2',
            'description'   => __( 'Appears at the bottom of the content on posts and pages.', 'twentysixteen' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );

    register_sidebar(
        array(
            'name'          => __( 'Content Bottom 2', 'twentysixteen' ),
            'id'            => 'sidebar-3',
            'description'   => __( 'Appears at the bottom of the content on posts and pages.', 'twentysixteen' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );
}
add_action( 'widgets_init', 'twentysixteen_widgets_init' );

function pr($data){
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}