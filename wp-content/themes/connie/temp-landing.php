<?php /* Template Name: Landing Page */ ?>
<?php get_header(); ?>

<div class="container-fluid">
    <div class="row">
        <div class="landing-page-banner">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/landing-page-banner.png" class="img-fluid" alt="">
            <div class="landing-banner-content">
                <h2>Nerdy, Geeky Events Near You </h2>
                <div class="landing-serach">
                    <input type="text" class="event-search" placeholder="Search for events">
                    <input type="text" class="city-search" placeholder="Los Angeles">
                    <button type="submit" class="landing-submit-btn">Search</button>
                </div>
            </div>
        </div>
        <div class="landing-page-content">
            <div class="landing-page-title">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/calendar-icon.png" alt="">
                <h4>Upcoming events</h4>
            </div>
            <?php echo do_shortcode('[events per_page="9" show_filters="false" ]'); ?>
            <div class="upcoming-events-list">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-lg-4">
                            <div class="u-events-box">
                                <div class="u-event-pic">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/dreamcon.png" alt="">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="u-event-details">
                                    <div class="date-month">
                                        <span>May</span>
                                        <label>03</label>
                                    </div>
                                    <div class="event-disc">
                                        <h4>Dreamcon</h4>
                                        <ul>
                                            <li><img src="<?php echo get_template_directory_uri(); ?>/assets/images/clock.png" alt="">Fri, May 3rd - May 5th, 12:00pm</li>
                                            <li><img src="<?php echo get_template_directory_uri(); ?>/assets/images/map-icon.png" alt="">Waco Convention Center Waco Convention Center</li>
                                            <li>Starts at $35.00</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="u-events-box">
                                <div class="u-event-pic">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/QuirkConIG.png" alt="">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="u-event-details">
                                    <div class="date-month">
                                        <span>May</span>
                                        <label>03</label>
                                    </div>
                                    <div class="event-disc">
                                        <h4>Quirkcon</h4>
                                        <ul>
                                            <li><img src="<?php echo get_template_directory_uri(); ?>/assets/images/clock.png" alt="">Fri, May 31st - Jun 2nd, 12:00pm</li>
                                            <li><img src="<?php echo get_template_directory_uri(); ?>/assets/images/map-icon.png" alt="">Durham Convention Center</li>
                                            <li>Starts at $27.00</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="u-events-box">
                                <div class="u-event-pic">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/blerd2019.png" alt="">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="u-event-details">
                                    <div class="date-month">
                                        <span>May</span>
                                        <label>03</label>
                                    </div>
                                    <div class="event-disc">
                                        <h4>Blerdcon 2019</h4>
                                        <ul>
                                            <li><img src="<?php echo get_template_directory_uri(); ?>/assets/images/clock.png" alt="">Fri, Jul 12th - Jul 14th, 12:00pm</li>
                                            <li><img src="<?php echo get_template_directory_uri(); ?>/assets/images/map-icon.png" alt="">Hyatt Regency Crystal City</li>
                                            <li>Starts at $55.00</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="u-events-box">
                                <div class="u-event-pic">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/dreamcon.png" alt="">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="u-event-details">
                                    <div class="date-month">
                                        <span>May</span>
                                        <label>03</label>
                                    </div>
                                    <div class="event-disc">
                                        <h4>Dreamcon</h4>
                                        <ul>
                                            <li><img src="<?php echo get_template_directory_uri(); ?>/assets/images/clock.png" alt="">Fri, May 3rd - May 5th, 12:00pm</li>
                                            <li><img src="<?php echo get_template_directory_uri(); ?>/assets/images/map-icon.png" alt="">Waco Convention Center</li>
                                            <li>Starts at $35.00</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="u-events-box">
                                <div class="u-event-pic">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/QuirkConIG.png" alt="">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="u-event-details">
                                    <div class="date-month">
                                        <span>May</span>
                                        <label>03</label>
                                    </div>
                                    <div class="event-disc">
                                        <h4>Quirkcon</h4>
                                        <ul>
                                            <li><img src="<?php echo get_template_directory_uri(); ?>/assets/images/clock.png" alt="">Fri, May 31st - Jun 2nd, 12:00pm</li>
                                            <li><img src="<?php echo get_template_directory_uri(); ?>/assets/images/map-icon.png" alt="">Durham Convention Center</li>
                                            <li>Starts at $27.00</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="u-events-box">
                                <div class="u-event-pic">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/blerd2019.png" alt="">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="u-event-details">
                                    <div class="date-month">
                                        <span>May</span>
                                        <label>03</label>
                                    </div>
                                    <div class="event-disc">
                                        <h4>Blerdcon 2019</h4>
                                        <ul>
                                            <li><img src="<?php echo get_template_directory_uri(); ?>/assets/images/clock.png" alt="">Fri, Jul 12th - Jul 14th, 12:00pm</li>
                                            <li><img src="<?php echo get_template_directory_uri(); ?>/assets/images/map-icon.png" alt="">Hyatt Regency Crystal City</li>
                                            <li>Starts at $55.00</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="u-events-box">
                                <div class="u-event-pic">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/dreamcon.png" alt="">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="u-event-details">
                                    <div class="date-month">
                                        <span>May</span>
                                        <label>03</label>
                                    </div>
                                    <div class="event-disc">
                                        <h4>Dreamcon</h4>
                                        <ul>
                                            <li><img src="<?php echo get_template_directory_uri(); ?>/assets/images/clock.png" alt="">Fri, May 3rd - May 5th, 12:00pm</li>
                                            <li><img src="<?php echo get_template_directory_uri(); ?>/assets/images/map-icon.png" alt="">Waco Convention Center</li>
                                            <li>Starts at $35.00</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="u-events-box">
                                <div class="u-event-pic">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/QuirkConIG.png" alt="">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="u-event-details">
                                    <div class="date-month">
                                        <span>May</span>
                                        <label>03</label>
                                    </div>
                                    <div class="event-disc">
                                        <h4>Quirkcon</h4>
                                        <ul>
                                            <li><img src="<?php echo get_template_directory_uri(); ?>/assets/images/clock.png" alt="">Fri, May 31st - Jun 2nd, 12:00pm</li>
                                            <li><img src="<?php echo get_template_directory_uri(); ?>/assets/images/map-icon.png" alt="">Durham Convention Center</li>
                                            <li>Starts at $27.00</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="u-events-box">
                                <div class="u-event-pic">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/blerd2019.png" alt="">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="u-event-details">
                                    <div class="date-month">
                                        <span>May</span>
                                        <label>03</label>
                                    </div>
                                    <div class="event-disc">
                                        <h4>Blerdcon 2019</h4>
                                        <ul>
                                            <li><img src="<?php echo get_template_directory_uri(); ?>/assets/images/clock.png" alt="">Fri, Jul 12th - Jul 14th, 12:00pm</li>
                                            <li><img src="<?php echo get_template_directory_uri(); ?>/assets/images/map-icon.png" alt="">Hyatt Regency Crystal City</li>
                                            <li>Starts at $55.00</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
