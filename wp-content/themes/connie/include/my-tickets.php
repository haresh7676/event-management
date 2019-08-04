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
            <?php
            $customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
                'numberposts' => -1,
                'meta_key'    => '_customer_user',
                'meta_value'  => get_current_user_id(),
                'post_type'   => wc_get_order_types( 'view-orders' ),
                'post_status' => array_keys( wc_get_order_statuses() ),
            ) ) );
            if ( $customer_orders ) :
                $upcomingeveent = array();
                $pastevent = array();
                foreach ( $customer_orders as $orderkey => $customer_order ) :
                    $order      = wc_get_order( $customer_order );
                    $orderid = $order->get_order_number();
                    $order_items= $order->get_items();
                    foreach ( $order_items as $item_id => $item ) {
                        $order_productid = $item->get_product_id();
                    }
                    $eventid = '';
                    if(!empty($order_productid)){
                        $eventid = get_post_meta($order_productid,'_event_id',true);
                    }
                    if(!empty($eventid) && get_post_status ($eventid) == 'expired'){
                        $pastevent[$orderid] = $eventid;
                    }else{
                        $upcomingeveent[$orderid] = $eventid;
                    }
                endforeach;
            endif;
            ?>
            <div class="tab-pane fade show active" id="upcoming">
                <?php
                if(!empty($upcomingeveent)) {
                    $the_query = new WP_Query( array( 'post_type' => 'event_listing', 'post__in' => $upcomingeveent ) );
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
                            $key = array_search (get_the_ID(), $upcomingeveent);
                            echo "<button onclick=window.open('".site_url()."/my-account/view-order/".$key."')>See Details</button>";
                            echo get_favorites_button(get_the_ID(), '');
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo 'No record found.';
                    }
                    wp_reset_postdata();
                }else{
                    echo 'No record found.';
                }
                ?>
            </div>
            <!-- My Ticket Tab2 -->
            <div class="tab-pane fade" id="pastEvents">
                <?php
                if(!empty($pastevent)) {
                    $the_query = new WP_Query( array( 'post_type' => 'event_listing', 'post__in' => $pastevent ) );
                    if ( $the_query->have_posts() ) {
                        while ( $the_query->have_posts() ) {
                            $the_query->the_post();
                            echo '<div class="my-ticket-card-row past-events">';
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
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo 'No record found.';
                    }
                    wp_reset_postdata();
                }else{
                    echo 'No record found.';
                }
                ?>
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
                            echo 'No record found.';
                        }
                        wp_reset_postdata();
                    }else{
                        echo 'No record found.';
                    }
                }else{
                    echo 'No record found.';
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