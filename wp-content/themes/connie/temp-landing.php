<?php /* Template Name: Landing Page */ ?>
<?php get_header(); ?>
<div class="row">
    <div class="landing-page-banner">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/landing-page-banner.png" class="img-fluid" alt="">
        <div class="landing-banner-content">
            <h2>Nerdy, Geeky Events Near You </h2>
            <div class="landing-serach">
                <form method="GET" action="<?php echo site_url(); ?>/events">
                    <input type="text" class="event-search"  id="search_keywords" name="search_keywords" placeholder="Search for events">
                    <input type="text" class="city-search" id="search_location"name="search_location" placeholder="Los Angeles"  autocomplete="off">
                    <button type="submit" class="landing-submit-btn">Search</button>
                </form>
            </div>
        </div>
    </div>
    <div class="landing-page-content">
        <div class="landing-page-title">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/calendar-icon.png" alt="">
            <h4>Upcoming events</h4>
        </div>
        <?php do_action( 'event_manager_event_filters_start', $atts ); ?>
        <?php echo do_shortcode('[events per_page="9" show_filters="false" ]'); ?>
    </div>
</div>

<?php get_footer('blank'); ?>
