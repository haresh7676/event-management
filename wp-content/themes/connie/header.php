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
            <div class="logo-box"><a href="<?php echo site_url(); ?>/events/"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" alt=""></a></div>
            <div class="topnav-right pull-right">
                <ul>
                    <li class="browser-event"><a href="<?php echo site_url(); ?>/events/">Browser events</a></li>
                    <li class="active"><a href="<?php echo site_url(); ?>/create-event/">Create Event</a></li>
                    <?php if(is_user_logged_in()){
                        $current_user = wp_get_current_user();
                        ?>
                        <div class="dropdown user-top-nav">
                            <div class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="usr-nm"><?php echo esc_html( $current_user->display_name ); ?></span><i class="usr-icn-bx"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/user-icn.png" alt=""></i><i class="usr-arrow"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/down-arrow.png" alt=""></i>
                            </div>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <?php $myaccount = site_url().'/my-account'; ?>
                                <a class="dropdown-item" href="<?php echo $myaccount; ?>/edit-account/"><i class="drp-icn"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/acc-stng-icn.png" alt=""></i>Account Settings</a>
                                <a class="dropdown-item" href="<?php echo $myaccount; ?>/payment-methods/"><i class="drp-icn"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/paymnt-stng-icn.png" alt=""></i>Payment Settings</a>
                                <a class="dropdown-item" href="<?php echo $myaccount; ?>/orders/"><i class="drp-icn"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/my-tkt-icn.png" alt=""></i>My Tickets</a>
                                <a class="dropdown-item" href="<?php echo $myaccount; ?>/manage-events/"><i class="drp-icn"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/mng-evnt-icn.png" alt=""></i>Manage Events</a>
                                <a class="dropdown-item" href="<?php echo $myaccount; ?>/help-center/"><i class="drp-icn"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/help-cntr-icn.png" alt=""></i>Help Center</a>
                                <a class="dropdown-item" href="<?php echo $myaccount; ?>/report-problem/"><i class="drp-icn"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/rprt-prblm-icn.png" alt=""></i>Report a Problem</a>
                                <a class="dropdown-item" href="<?php echo $myaccount; ?>/about/"><i class="drp-icn"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/abut-icn.png" alt=""></i>About</a>
                                <a class="dropdown-item" href="<?php echo $myaccount; ?>/terms-and-policies/"><i class="drp-icn"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/tm-plc-icn.png" alt=""></i>Terms and Policies</a>
                                <a class="dropdown-item" href="<?php echo wp_logout_url(home_url()); ?>"><i class="drp-icn log-out-bg"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/logout-icn.png" alt=""></i>Logout</a>
                            </div>
                        </div>
                    <?php }else{ ?>
                        <li><a href="<?php echo site_url(); ?>/sign-in/">Sign in</a></li>
                    <?php } ?>
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