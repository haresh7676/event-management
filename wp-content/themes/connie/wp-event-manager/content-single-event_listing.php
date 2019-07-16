<?php global $post; ?>
<div class="row">
<?php if ( get_option( 'event_manager_hide_expired_content', 1 ) && 'expired' === $post->post_status ) : ?>
    <div class="event-manager-info"><?php _e( 'This listing has been expired.', 'wp-event-manager' ); ?></div>
<?php else : ?>
    <div class="event-profile-banner">
        <!--<img src="assets/images/event-profile-banner.png" class="img-fluid" alt="">-->
        <?php $event_banners = get_event_banner(); ?>
        <?php if( is_array( $event_banners) && sizeof($event_banners ) > 1 )
        { ?>
        <div id="single-event-slider" class="carousel slide" data-ride="carousel">

            <!-- Wrapper for slides -->
            <div class="carousel-inner">

                <?php
                $active = 'active';
                foreach($event_banners as $banner_key => $banner_value ){
                    ?>
                    <div class="item <?php echo $active;?>">
                        <img src="<?php echo $banner_value; ?>"  alt="<?php echo esc_attr( get_organizer_name( $post ) );?>">
                    </div>
                    <?php
                    $active ='';
                }
                ?>
            </div>

        </div>
        <div class="clearfix">
            <div id="thumbcarousel" class="carousel slide" data-interval="false">
                <div class="carousel-inner">
                    <?php
                    $slide_to = 0;
                    foreach($event_banners as $banner_key => $banner_value ){
                        if( $slide_to == 0) {
                            $thumbanils_num = +4;
                            echo '<div class="item active">';
                        }
                        elseif( $slide_to == $thumbanils_num){
                            $thumbanils_num = $thumbanils_num + 4;
                            echo '</div><div class="item">';
                        }

                        ?>
                        <div data-target="#myCarousel" data-slide-to="<?php echo $slide_to;?>" class="thumb"><img src="<?php echo $banner_value; ?>"></div>
                        <?php
                        $slide_to++;
                    } ?>
                </div><!-- /items -->

                <a class="left carousel-control" href="#thumbcarousel" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                </a>
                <a class="right carousel-control" href="#thumbcarousel" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                </a>

            </div><!-- /carousel-inner -->

        </div> <!-- /thumbcarousel -->
    </div><!-- /clearfix -->

    <?php
    }
    else {
        display_event_banner();
    }
    ?>
    </div><!-- banner section -->
    <div class="single_event_listing event-profile-content">
    <meta itemprop="title" content="<?php echo esc_attr( $post->post_title ); ?>" />
    <div class="container">
        <div class="ticket-pass-section">
            <div class="row">
                <div class="col-lg-5">
                    <div class="ticket-address">
                        <span>
                             <?php $newformate = 'D, M j, Y'; ?>
                            <?php echo date_i18n( $newformate, strtotime(get_event_start_date()) ); ?>
                        </span>
                        <h3><?php echo esc_attr( $post->post_title ); ?></h3>
                        <p>Hosted By <?php echo esc_attr( $post->post_title ); ?></p>
                        <label><img src="<?php echo get_template_directory_uri(); ?>/assets/images/pin.png"> <?php echo get_event_location() ?></label>
                        <div class="ticket-contact">
                            <h4>Contact</h4>
                            <ul>
                                <li class="facebook"><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                <li class="twitter"><a href="#"><i class="fab fa-twitter"></i></a></li>
                                <li class="g-plus"><a href="#"><img src="assets/images/g-plus.png" alt=""></i></a></li>
                                <li class="email"><a href="#"><i class="fas fa-envelope"></i></a></li>
                                <li class="linkedin"><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="buy-ticket">
                        <div class="ticket-pass-buy">
                            <?php
                            $event_id = $post->ID;
                            $post_author = get_post_field( 'post_author', $event_id );
                            $current_user_id = get_current_user_id();
                            if( isset( $current_user_id ) && $current_user_id == $post_author){
                                 $author_id = $current_user_id;
                                 $post_status = 'any';
                            }
                            else{
                                $current_user_id = '';
                                $post_status = 'publish';

                            }

                                $args = array(
                                           'author'        =>  $current_user_id ,
                                           'post_type' => 'product',
                                           'post_status'=>$post_status,
                                           'posts_per_page' => -1,
                                           'meta_key'     => '_event_id',
                                           'meta_value'   => $event_id,
                                     );

                            $all_tickets=get_posts($args);


                            if(!empty($all_tickets) && $all_tickets[0]->ID >= 1) {
                                echo '<ul>';
                                foreach ( $all_tickets as $post_data ) : setup_postdata( $post_data );
                                    $price            = get_post_meta($post_data->ID,'_price',true);
                                    $price            = $price == 0 ? __('Free', 'wp-event-manager-sell-tickets'): $price;
                                    $ticket_type = get_post_meta($post_data->ID,'_ticket_type',true);
                                    echo '<li>';
                                    echo '<span>'.__( get_the_title($post_data->ID) , 'wp-event-manager-sell-tickets').'</span>';
                                    echo '<label>';
                                    if($ticket_type == 'donation'){
                                        echo '<input type="number" name="donation_price-" id="donation_price" value="'.$price.'"  min="'.$price.'" />';
                                    }
                                    else if(is_numeric($price)){
                                        _e( get_woocommerce_currency_symbol(),'wp-event-manager-sell-tickets');
                                        _e( $price ,'wp-event-manager-sell-tickets');
                                    }
                                    else{
                                        _e( 'Free','wp-event-manager-sell-tickets');
                                    }
                                    echo '</label>';
                                    echo '</li>';
                                endforeach;
                                wp_reset_query();
                                echo '</ul>';
                            }
                            //echo  do_shortcode('[event_sell_tickets]'); ?>
                        </div>
                        <button class="buy-ticket-btn">Buy Tickets</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="profie-cart-box e-profile-about">
            <h3 class="e-profile-about-title">About</h3>
            <?php echo apply_filters( 'display_event_description', get_the_content() ); ?>
        </div> <!-- About info  -->
        <div class="profie-cart-box e-profile-calendar">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="e-p-date-picker">
                        <img src="assets/images/calendar.png" class="img-fluid" alt="">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="date-and-time">
                        <h3 class="e-p-cart-title">Date And Time</h3>
                        <span>Fri, May 31, 2019, 5:00 PM – Sun,<br> June 2, 2019, 6:00 PM EDT</span>
                        <a href="#" class="view-detail">Add to Calendar</a>

                        <button type="button" class="btn volnuteer-form-btn" data-toggle="modal" data-target="#exampleModal">Volunteer</button>
                        <div class="modal fade volnuteer-form" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <img src="assets/images/close.png" class="modal-close-icon" alt="">
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <h5 class="modal-title">Volunteer Form</h5>
                                        <div class="volunteer-body">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="volunteer-filed-row">
                                                        <span>First Name</span>
                                                        <input type="text" class="form-controls">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="volunteer-filed-row">
                                                        <span>Last Name</span>
                                                        <input type="text" class="form-controls">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="volunteer-filed-row">
                                                        <span>Email</span>
                                                        <input type="text" class="form-controls">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="volunteer-filed-row">
                                                        <span>Phone</span>
                                                        <input type="text" class="form-controls">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="volunteer-filed-row">
                                                        <span>Area of Expertise</span>
                                                        <input type="text" class="form-controls">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="volunteer-filed-row">
                                                        <span>Which areas are you best suited to volunteer?</span>
                                                        <textarea class="form-controls"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="volunteer-filed-row">
                                                        <span>Why do you think you would be a good volunteer?</span>
                                                        <textarea class="form-controls"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="volunteer-filed-row">
                                                        <span>Which areas are you best suited to volunteer?</span>
                                                        <textarea class="form-controls"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="volunteer-filed-row">
                                                        <span>Which times/shifts could you be available? (Note: you may not need to be available for the entire shift) *</span>
                                                        <textarea class="form-controls"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="volunteer-filed-row">
                                                        <span>How did you hear about volunteering?</span>
                                                        <textarea class="form-controls"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="volunteer-filed-row">
                                                        <span>What other events have you volunteered at?</span>
                                                        <textarea class="form-controls"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="volunteer-submit-btn">
                                                        <button type="submit">Submit</button>
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
            </div>
        </div> <!-- calendar Section  -->
        <div class="profie-cart-box e-profile-location-video profile-location">
            <div class="row">
                <div class="col-lg-5 col-md-5">
                    <h3 class="e-p-cart-title">Location</h3>
                    <?php if(is_event_online($post) ):
                        echo __('Online Event','wp-event-manager');
                    else:
                        echo '<span>' . get_event_venue_name() . '<br>'. get_event_address(). ', ' . get_event_pincode() .', '. get_event_location() . '.</span>';
                    endif;?>
                    <a href="#" class="view-detail">View Map</a>
                </div>
                <div class="col-lg-7 col-md-7">
                    <div class="map-video-box">
                        <div id='googleMap' class="google-map-loadmore" style="width:100%;height:100%;border:0";></div>
                    </div>
                </div>
            </div>
        </div> <!-- Location map -->
        <div class="profie-cart-box e-profile-location-video profile-video">
            <div class="row">
                <div class="col-lg-8 col-md-8">
                    <div class="map-video-box">
                        <iframe width="100%" height="400" style="border:0" src="https://www.youtube.com/embed/tgbNymZ7vqY"></iframe>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <h3 class="e-p-cart-title">Videos</h3>
                    <span>We are Quirkcon</span>
                </div>
            </div>
        </div><!-- Profile Videp -->
        <div class="profie-cart-box e-profile-gallery">
            <div class="e-p-gallery-title">
                <h4>Photos</h4>
                <span><a href="#">View more</a></span>
            </div>
            <div class="e-p-gallery-photos">
                <div class="row">
                    <div class="col-lg-4 col-md-4">
                        <div class="gallery-big-photo">
                            <img src="assets/images/gallery-big-photo.png" class="img-fluid" alt="">
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="gallery-small-photos">
                            <ul>
                                <li><a href="#"><img src="assets/images/gallery-big-photo1.png" alt=""></a></li>
                                <li><a href="#"><img src="assets/images/gallery-big-photo2.png" alt=""></a></li>
                                <li><a href="#"><img src="assets/images/gallery-big-photo2.png" alt=""></a></li>
                                <li><a href="#"><img src="assets/images/gallery-big-photo2.png" alt=""></a></li>
                                <li><a href="#"><img src="assets/images/gallery-big-photo3.png" alt=""></a></li>
                                <li><a href="#"><img src="assets/images/gallery-big-photo4.png" alt=""></a></li>
                                <li><a href="#"><img src="assets/images/gallery-big-photo4.png" alt=""></a></li>
                                <li><a href="#"><img src="assets/images/gallery-big-photo4.png" alt=""></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- Gallery -->
    </div>
</div>
<?php endif; ?>
</div>