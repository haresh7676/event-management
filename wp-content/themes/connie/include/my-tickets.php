<div class="tab-pane fade active show" id="myTickets">
    <div class="my-tickets sub-tab-design">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#upcoming">Upcoming</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#pastEvents">Past Events</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#favorites">Favorites</a>
            </li>
        </ul>
        <div class="tab-content">
            <!-- My Ticket Tab1 -->
            <div class="tab-pane fade show active" id="upcoming">
                <div class="my-ticket-card-row">
                    <div class="my-ticket-pic">
                        <img src="assets/images/myticket-01.png" alt="">
                    </div>
                    <div class="my-ticket-detail">
                        <h3>Dreamcon</h3>
                        <ul>
                            <li><img src="assets/images/clock.png" alt="">Fri, May 3rd-May 5th, 12:00 pm</li>
                            <li><img src="assets/images/map-icon.png" alt="">Waco Convention Center</li>
                        </ul>
                    </div>
                    <div class="ticket-status-btn align-self-center">
                        <button>See Details</button>
                        <button>Favorited</button>
                    </div>
                </div>
                <div class="my-ticket-card-row">
                    <div class="my-ticket-pic">
                        <img src="assets/images/myticket-02.png" alt="">
                    </div>
                    <div class="my-ticket-detail">
                        <h3>Quirkcon</h3>
                        <ul>
                            <li><img src="assets/images/clock.png" alt="">Fri, May 31st - Jun 2nd, 12:00pm</li>
                            <li><img src="assets/images/map-icon.png" alt="">Durham Convention Center</li>
                        </ul>
                    </div>
                    <div class="ticket-status-btn align-self-center">
                        <button>See Details</button>
                        <button>Favorited</button>
                    </div>
                </div>
                <div class="my-ticket-edit-save-btn">
                    <button>Edit</button>
                    <button>Save</button>
                </div>
            </div>
            <!-- My Ticket Tab2 -->
            <div class="tab-pane fade" id="pastEvents">
                <div class="my-ticket-card-row past-events">
                    <div class="my-ticket-pic">
                        <img src="assets/images/myticket-01.png" alt="">
                    </div>
                    <div class="my-ticket-detail">
                        <h3>Dreamcon</h3>
                        <ul>
                            <li><img src="assets/images/clock.png" alt="">Fri, May 3rd-May 5th, 12:00 pm</li>
                            <li><img src="assets/images/map-icon.png" alt="">Waco Convention Center</li>
                        </ul>
                    </div>
                    <div class="ticket-status-btn align-self-center">
                        <button>See Details</button>
                    </div>
                </div>
                <div class="my-ticket-card-row past-events">
                    <div class="my-ticket-pic">
                        <img src="assets/images/myticket-02.png" alt="">
                    </div>
                    <div class="my-ticket-detail">
                        <h3>Quirkcon</h3>
                        <ul>
                            <li><img src="assets/images/clock.png" alt="">Fri, May 31st - Jun 2nd, 12:00pm</li>
                            <li><img src="assets/images/map-icon.png" alt="">Durham Convention Center</li>
                        </ul>
                    </div>
                    <div class="ticket-status-btn align-self-center">
                        <button>See Details</button>
                    </div>
                </div>
                <div class="my-ticket-edit-save-btn">
                    <button>Edit</button>
                    <button>Save</button>
                </div>
            </div>
            <!-- My Ticket Tab3 -->
            <div class="tab-pane fade" id="favorites">
                <?php
                $userid = get_current_user_id();
                if(function_exists('get_user_favorites')) {
                    $userfav = get_user_favorites($userid, '');
                    if (!empty($userfav)) {
                        $userfav = array_reverse($userfav);
                        $the_query = new WP_Query( array( 'post_type' => 'event_listing', 'post__in' => $userfav ) );
                        if ( $the_query->have_posts() ) {
                            while ( $the_query->have_posts() ) {
                                $the_query->the_post();
                                echo '<div class="my-ticket-card-row">';
                                echo '<div class="my-ticket-pic">';
                                display_event_banner();
                                echo '</div>';
                                echo '<div class="my-ticket-detail">';
                                echo '<h3>' . get_the_title() . '</h3>';
                                echo '<ul>';
                                $newformate = 'D, M jS';
                                echo '<li><img src="'.get_template_directory_uri().'/assets/images/clock.png" alt="">'. date_i18n( $newformate, strtotime(get_event_start_date()) ).((strtotime(get_event_start_date()) != strtotime(get_event_end_date())) ? date_i18n( ' - M jS,', strtotime(get_event_end_date()) ):','). display_event_start_time(false,false,false).'</li>';
                                echo '<li><img src="'.get_template_directory_uri().'/assets/images/map-icon.png" alt="">'.display_event_venue_name(false,false,false).'</li>';
                                echo '</ul>';
                                echo '</div>';
                                echo '<div class="ticket-status-btn align-self-center">';
                                echo "<button onclick=window.open('".get_permalink(get_the_ID())."')>See Details</button>";
                                echo get_favorites_button(get_the_ID(), '');
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            // no posts found
                        }
                        /* Restore original Post Data */
                        wp_reset_postdata();
                    }
                }
                ?>
                <!--<div class="my-ticket-edit-save-btn">
                    <button>Edit</button>
                    <button>Save</button>
                </div>-->
            </div>
        </div>
    </div>
</div>