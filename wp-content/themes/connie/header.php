<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="profile" href="http://gmpg.org/xfn/11">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap" rel="stylesheet">

	<?php wp_head(); ?>
</head>
<body style="height: initial; position: relative; overflow: visible;" class="fullpage-wrapper fp-viewing-3">
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