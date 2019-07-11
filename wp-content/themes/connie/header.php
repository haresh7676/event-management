<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="profile" href="http://gmpg.org/xfn/11">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap" rel="stylesheet">

	<?php wp_head(); ?>
</head>
<body class="fullpage-wrapper fp-viewing-3">
<div class="container-fluid">
    <div class="row">
        <div class="top-header">
            <div class="logo-box"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" alt=""></div>
            <div class="topnav-right pull-right">
                <ul>
                    <li class="browser-event"><a href="<?php echo site_url(); ?>/events/">Browser events</a></li>
                    <li class="active"><a href="<?php echo site_url(); ?>/post-an-event/">Create Event</a></li>
                    <li><a href="<?php echo site_url(); ?>/sign-in/">Sign in</a></li>
                </ul>
            </div>
        </div>
        <?php
        $banner = get_field('banner_enable'); 
        $bannerimg = get_field('banner_image');
        if($banner == 1 && !empty($bannerimg)){
        ?>
        <div class="landing-page-banner">
            <img src="<?php echo $bannerimg; ?>" class="img-fluid" alt="">
        </div>
        <?php } ?>
    </div>
<?php
//$headeroption = get_fields('header-settings');
$headeroption = array();
//echo '<pre>'; print_r($headeroption);
?>
<!-- Start Smoky Effect in Background -->
<!--<div id="wavybg-wrapper" class="smoky-bg">
    <canvas class="background" width="1349" height="654"></canvas>
</div>-->
<!-- End Smoky Effect in Background -->

<!-- Start Preloader -->

<!-- End Preloader -->

<!-- Start Nav -->
<!--<nav>
    <div class="nav-and-footer-bg"><span style="height: 654px; width: 1349px;"></span></div>
    <div class="container">
        <h1 class="logo">
            <a href="<?php /*site_url(); */?>"><img src="<?php /*echo (!empty($headeroption['logo'])) ? $headeroption['logo'] : ''; */?>" alt=""></a>
        </h1>
        <div class="menu-button">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="menu-body">
            <div class="nav-and-footer-bg"><span style="height: 654px; width: 1349px;"></span></div>
            <?php
/*            wp_nav_menu( array(
                'menu' => 'Main Menu',
                'container_class' => 'navbar-collapse collapse clearfix',
                'menu_class' => 'navigation clearfix'

            ) );
            */?>
            <div class="menu-bg1"></div>
            <div class="menu-bg2"></div>
        </div>
    </div>

</nav>-->
<!-- End Nav -->