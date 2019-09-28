<?php global $post;
$themesettings =  get_fields('theme-settings');
?>
<div class="row">
<?php if ( get_option( 'event_manager_hide_expired_content', 1 ) && 'expired' === $post->post_status ) : ?>
    <div class="event-manager-info"><?php _e( 'This listing has been expired.', 'wp-event-manager' ); ?></div>
<?php else : ?>
        <div class="container">
            <div class="event-profile-banner">
                <!--<img src="assets/images/event-profile-banner.png" class="img-fluid" alt="">-->
                <?php $event_banners = get_event_banner(); ?>
                <?php if( is_array( $event_banners) && sizeof($event_banners ) > 1 )
                { ?>
            </div>
        </div>
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
                        <h3 title="<?php echo esc_attr( $post->post_title ); ?>"><?php echo esc_attr( $post->post_title ); ?></h3>
                        <?php $organizername = display_organizer_name('','',false); 
                        $eventlocations = get_event_location();
                        if(!empty($organizername)){ ?>
                            <p>Hosted By  <?php display_organizer_name(); ?></p>    
                        <?php } 
                        if(!empty($eventlocations)){ ?>
                        <label>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/pin.png">
                            <div class="t-address-text"><?php echo get_event_location() ?></div>
                        </label>
                        <?php } ?>
                        <div class="ticket-contact">
                            <?php if(!empty($themesettings) && (isset($themesettings['contact_form']) && !empty($themesettings['contact_form']))){ ?>
                                <h4><a href="javascript:void(0)" data-toggle="modal" data-target="#contactmodule">Contact</a></h4>
                                <div class="modal fade volnuteer-form" id="contactmodule" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/close.png" class="modal-close-icon" alt="">
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <h5 class="modal-title">Contact Us</h5>
                                                <div class="volunteer-body">
                                                    <?php echo apply_filters('the_content',$themesettings['contact_form']); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php
                            $websiteurl= get_organizer_website();
                            $facebook= get_organizer_facebook();
                            $twitter= get_organizer_twitter();
                            $linkedin=get_organizer_linkedin();
                            $xing=get_organizer_xing();
                            $pinterest=get_organizer_pinterest();
                            $instagram=get_organizer_instagram();
                            $youtube=get_organizer_youtube();
                            $googleplus=get_organizer_google_plus();
                            if(   $websiteurl||
                                  $facebook  ||
                                  $twitter   ||
                                  $linkedin  ||
                                  $xing      ||
                                  $pinterest ||
                                  $instagram ||
                                  $youtube   ||
                                  $googleplus )
                           {  ?>
                            <ul>
                                <?php if($websiteurl) { ?>
                                    <li><a href=" <?php echo $websiteurl; ?>" class="website-link" target="_blank"  itemprop="url" rel="nofollow"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/website-icon.svg" alt=""></a></li>
                                <?php  } ?>    
                                <?php if($facebook) { ?>
                                    <li class="facebook"><a href=" <?php echo esc_url($facebook); ?>"  class="facebook" target="_blank" itemprop="facebook" rel="nofollow"><i class="fab fa-facebook-f"></i></a></li>
                                <?php } ?>
                                <?php if($twitter) { ?>
                                    <li class="twitter"><a href=" <?php echo esc_url($twitter); ?>"  class="twitter"  target="_blank" itemprop="twitter" rel="nofollow"><i class="fab fa-twitter"></i></a></li>
                                <?php } ?>
                                <?php if($googleplus) { ?>
                                    <li class="g-plus"><a href=" <?php echo esc_url($googleplus); ?>"  class="g-plus"  target="_blank" itemprop="twitter" rel="nofollow"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/g-plus.png" alt=""></a></li>
                                <?php } ?>
                                <?php if($youtube) { ?>
                                    <li class="youtube"><a href=" <?php echo esc_url($youtube); ?>"  class="youtube-link" target="_blank" itemprop="youtube" rel="nofollow"><i class="fab fa-youtube"></i></a></a></li>
                                <?php } ?>
                                <?php if($linkedin) { ?>
                                    <li class="linkedin"><a href=" <?php echo esc_url($linkedin); ?>" class="linkedin-link" target="_blank" itemprop="linkedin" rel="nofollow"><i class="fab fa-linkedin-in"></i></li>
                                <?php } ?>
                                <?php if($instagram) { ?>
                                    <li class="instagram"><a href=" <?php echo $instagram; ?>"  class="instagram-link" target="_blank"><i class="fab fa-instagram"></i></a></li>
                                <?php } ?> 
                                 <?php if($googleplus) { ?>
                                    <li class="g-plus"><a href=" <?php echo $googleplus; ?>"  class="gplus-link" target="_blank" itemprop="gplus" rel="nofollow"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/g-plus.png" alt=""></a></li></li>
                                <?php } ?>  
                            </ul>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="buy-ticket">
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
                            echo '<div class="ticket-pass-buy">';
                            echo '<ul>';
                            foreach ( $all_tickets as $post_data ) : setup_postdata( $post_data );
                                $price            = get_post_meta($post_data->ID,'_price',true);
                                $price            = $price == 0 ? __('Free', 'wp-event-manager-sell-tickets'): $price;
                                $ticket_type = get_post_meta($post_data->ID,'_ticket_type',true);
                                echo '<li>';
                                echo '<span>'.__( get_the_title($post_data->ID) , 'wp-event-manager-sell-tickets').'</span>';
                                echo '<label>';
                                if($ticket_type == 'donation'){
                                    //echo '<input type="number" name="donation_price-" id="donation_price" value="'.$price.'"  min="'.$price.'" />';
                                    _e( get_woocommerce_currency_symbol(),'wp-event-manager-sell-tickets');
                                    if(!empty($price) && $price != 'Free'){
                                        _e( $price ,'wp-event-manager-sell-tickets');
                                    }else{
                                        echo '0+';
                                    }
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
                            echo '</div>';
                            echo '<a href="'.get_permalink($event_id).'tickets" class="buy-ticket-btn">Buy Tickets</a>';
                        }
                        //echo  do_shortcode('[event_sell_tickets]'); ?>
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
                        <div id="event-datepicker"></div>
                        <?php
                        $startdate = date_i18n( 'Y-m-d,', strtotime(get_event_start_date()) );
                        $enddate = date_i18n( 'Y-m-d', strtotime(get_event_end_date()) );
                        if(!empty($startdate) && !empty($enddate)) {
                            $date_from = strtotime($startdate);
                            $date_to = strtotime($enddate);
                            $eventdata = array();
                            $eventsInfo = array();
                            $day = 1;
                            for ($i = $date_from; $i <= $date_to; $i += 86400) {
                                $eventdata[] = date("Y-m-d", $i);
                                $eventsInfo[] = esc_attr( $post->post_title ).' Day '.$day;
                                $day++;
                            }
                        }
                        ?>

                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/calendar.png" class="img-fluid" alt="">
                        <!--<div id="event-cal-container" class="calendar-container"></div>
                        <?php
/*                        $startdate = date_i18n( 'Y-m-d,', strtotime(get_event_start_date()) );
                        $enddate = date_i18n( 'Y-m-d', strtotime(get_event_end_date()) );
                        if(!empty($startdate) && !empty($enddate)) {
                            $date_from = strtotime($startdate);
                            $date_to = strtotime($enddate);
                            $eventdata = array();
                            $eventsInfo = array();
                            $day = 1;
                            for ($i = $date_from; $i <= $date_to; $i += 86400) {
                                $eventdata[] = date("Y-m-d", $i);
                                $eventsInfo[] = esc_attr( $post->post_title ).' Day '.$day;
                                $day++;
                            }
                        }
                        */?>
                        <script type="text/javascript">
                            jQuery("#event-cal-container").simpleCalendar({
                                events: <?php /*echo json_encode($eventdata) */?>,
                                eventsInfo: <?php /*echo json_encode($eventsInfo) */?>,
                                selectCallback: function (date) {
                                    console.log('date selected ' + date);
                                }
                            });
                        </script>-->
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="date-and-time">
                        <h3 class="e-p-cart-title">Date And Time</h3>
                        <?php $newformate = 'D, M j, Y'; ?>
                        <span class="c-p-span"><?php echo date_i18n( $newformate, strtotime(get_event_start_date()) ); ?>, <?php display_event_start_time();?><?php echo (strtotime(get_event_start_date()) != strtotime(get_event_end_date())) ? date_i18n( ' – D, M j, Y,', strtotime(get_event_end_date()) ):','; ?>&nbsp;<?php display_event_end_time();?></span>
                        <!--<a href="#" class="view-detail">Add to Calendar</a>-->
                        <?php
                        if(!empty($themesettings) && (isset($themesettings['volunteer_form']) && !empty($themesettings['volunteer_form']))){
                        ?>
                        <button type="button" class="btn volnuteer-form-btn" data-toggle="modal" data-target="#exampleModal">Volunteer</button>
                        <div class="modal fade volnuteer-form" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/close.png" class="modal-close-icon" alt="">
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <h5 class="modal-title">Volunteer Form</h5>
                                        <div class="volunteer-body">
                                            <?php echo apply_filters('the_content',$themesettings['volunteer_form']); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div> <!-- calendar Section  -->
        <?php $eventlocation = get_event_location(); 
            if(!empty($eventlocation)){
        ?>
        <div class="profie-cart-box e-profile-location-video profile-location">
            <div class="row">
                <div class="col-lg-5 col-md-5">
                    <h3 class="e-p-cart-title">Location</h3>
                    <?php if(is_event_online($post) ):
                        echo __('Online Event','wp-event-manager');
                    else:
                        //echo '<span class="c-p-span">' . get_event_venue_name() . '<br>'. get_event_address(). ', ' . get_event_pincode() .', '. get_event_location() . '.</span>';
                        echo '<span class="c-p-span">' . get_event_venue_name() . '&nbsp;'. get_event_location() . '.</span>';
                    endif;?>
                    <!--<a href="#" class="view-detail">View Map</a>-->
                </div>
                <div class="col-lg-7 col-md-7">
                    <div class="map-video-box">
                        <?php do_shortcode('[single_event_location_map height="100%" width="100%"]'); ?>
                    </div>
                </div>
            </div>
        </div> <!-- Location map -->
        <?php  } ?>
        <?php $eventvideo = get_post_meta($post->ID,'_event_video',true);
        if(isset($eventvideo) && !empty($eventvideo)){
            $embed = getYoutubeEmbedUrl($eventvideo);
            if(!empty($embed)) {
                ?>
                <div class="profie-cart-box e-profile-location-video profile-video">
                    <div class="row">
                        <div class="col-lg-8 col-md-8">
                            <div class="map-video-box">
                                <iframe width="100%" height="400" style="border:0"
                                        src="<?php echo $embed; ?>"></iframe>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <h3 class="e-p-cart-title">Videos</h3>
                            <!--<span class="c-p-span">We are Quirkcon</span>-->
                        </div>
                    </div>
                </div><!-- Profile Videp -->
                <?php
            }
        } ?>
        <?php $eventalbum = get_post_meta($post->ID,'_event_album',true);
        if(isset($eventalbum) && !empty($eventalbum)){
            $albumcount = count($eventalbum);
            ?>
            <div class="profie-cart-box e-profile-gallery">
                <div class="e-p-gallery-title">
                    <h4>Photos</h4>
                    <?php if($albumcount > 9) { ?>
                        <span><a href="#" class="view-more-video">View more</a></span>
                    <?php } ?>
                </div>
                <div class="e-p-gallery-photos">
                    <div class="row">
                        <div class="col-lg-4 col-md-4">
                            <div class="gallery-big-photo">
                                <img src="<?php echo $eventalbum[0]; ?>" class="img-fluid" alt="">
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-8">
                            <div class="gallery-small-photos">
                                <?php if($albumcount > 1){
                                    echo '<ul>';
                                    $lastcount = ($albumcount > 9) ? 9 : $albumcount;
                                    for ($x =1; $x < $lastcount; $x++) {
                                        echo '<li><a href="#"><img src="'.$eventalbum[$x].'" alt=""></a></li>';
                                    }
                                    echo '</ul>';
                                } ?>
                            </div>
                            <div class="morephotowpr">
                                 <div class="gallery-small-photos gallery-more-photos">
                                     <ul>
                                         <?php if($albumcount > 9){
                                             $lastcount = $albumcount;
                                             for ($x=9; $x < $lastcount; $x++) {
                                                 echo '<li><a href="#"><img src="'.$eventalbum[$x].'" alt=""></a></li>';
                                             }
                                         } ?>
                                     </ul>
                                 </div>
                             </div>
                        </div>
                         <?php if($albumcount > 9) { ?>
                             
                         <?php } ?>
                    </div>
                </div>
            </div><!-- Gallery -->
        <?php }  ?>
    </div>
</div>
<?php endif; ?>
</div>