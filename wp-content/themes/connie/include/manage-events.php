<?php $myaccountsettings =  get_fields('account-settings'); ?>
<div class="tab-pane manage-events-tab active show" id="manageEvents" >
    <div class="sub-tab-design manage-events" data-ajax="<?php echo admin_url('admin-ajax.php'); ?>">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#editEvent">Edit Event</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#myDashboard">My Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#team">Team</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#volunteer">Volunteer</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#attendees">Attendees</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#refunds">Refunds</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#contact">Contact</a>
            </li>
        </ul>
        <div class="tab-content">
            <!-- My Ticket Tab1 -->
            <div class="tab-pane fade active" id="editEvent">
                <div class="editEvent-main-wpr">
                <?php echo do_shortcode('[event_dashboard]'); ?>
                </div>
            </div>
            <!-- My Ticket Tab2 -->
            <div class="tab-pane fade" id="myDashboard">
                <h2>My Dashboard</h2>
                <div class="mydashboard-main-wpr">
                <?php do_action('event_manager_event_dashboard_before'); ?>
                </div>
            </div>
            <!-- My Ticket Tab3 -->
            <div class="tab-pane fade" id="team">
                <div class="common-table-design get_team_member_data">
                    <div class="tabledataajax"></div>
                    <div class="tableloader" data-loader="<?php echo get_template_directory_uri().'/assets/images/loader.png'; ?>"></div>
                    <div class="export-list-row">
                        <?php if(!empty($myaccountsettings) && (isset($myaccountsettings['manage_event']['add_team_form_id']) && !empty($myaccountsettings['manage_event']['add_team_form_id']))){
                            $add_team_formid = $myaccountsettings['manage_event']['add_team_form_id'];
                            ?>
                        <!-- <button data-toggle="modal" data-target="#AddteamModal">Add New Team Member</button> -->
                        <div class="modal fade volnuteer-form" id="AddteamModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/close.png" class="modal-close-icon" alt="">
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <h5 class="modal-title">Add Team Member</h5>
                                        <div class="volunteer-body">
                                            <?php echo do_shortcode('[contact-form-7 id="'.$add_team_formid.'" title="Add Team Member"]'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <!-- My Ticket Tab4 -->
            <div class="tab-pane fade" id="volunteer">
                <div class="m-e-table-dropdown">
                    <select class="form-controls">
                        <option>Volunteer</option>
                        <option>Volunteer</option>
                        <option>Volunteer</option>
                        <option>Volunteer</option>
                        <option>Volunteer</option>
                        <option>Volunteer</option>
                    </select>
                </div>
                <div class="common-table-design get_volunteer_data">
                    <div class="tabledataajax"></div>
                    <div class="tableloader" data-loader="<?php echo get_template_directory_uri().'/assets/images/loader.png'; ?>"></div>
                </div>
            </div>
            <!-- My Ticket Tab5 -->
            <div class="tab-pane fade" id="attendees">
                <div class="m-e-table-dropdown">
                    <select class="form-controls">
                        <option>Attendees</option>
                        <option>Attendees</option>
                        <option>Attendees</option>
                        <option>Attendees</option>
                        <option>Attendees</option>
                        <option>Attendees</option>
                    </select>
                </div>

                <?php


                /*$customer = wp_get_current_user();

                // Get all customer orders
                $customer_orders = get_posts( array(
                'numberposts' => -1,
                'meta_key'    => '_customer_user',
                'meta_value'  => get_current_user_id(),
                'post_type'   => wc_get_order_types(),
                'post_status' => array_keys( wc_get_order_statuses() ),  //'post_status' => array('wc-completed', 'wc-processing'),
                ) );
                pr($customer_orders);

                echo count( $customer_orders );*/



                $my_orders_columns = apply_filters( 'woocommerce_my_account_my_orders_columns', array(
                    'order-number'  => __( 'Comfirmation #', 'woocommerce' ),
                    'order-date'    => __( 'Date', 'woocommerce' ),
                    'order-buyer'    => __( 'Ticket Buyer', 'woocommerce' ),
                    'order-status'  => __( 'Status', 'woocommerce' ),
                    'order-total'   => __( 'Total', 'woocommerce' ),
                    'order-actions' => '&nbsp;',
                ) );

                $customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
                    'numberposts' => $order_count,
                    'meta_key'    => '_customer_user',
                    'meta_value'  => get_current_user_id(),
                    'post_type'   => wc_get_order_types( 'view-orders' ),
                    'post_status' => array_keys( wc_get_order_statuses() ),
                ) ) );

                if ( $customer_orders ) : ?>

                    <h2><?php echo apply_filters( 'woocommerce_my_account_my_orders_title', __( 'Recent orders', 'woocommerce' ) ); ?></h2>

                    <table class="shop_table shop_table_responsive my_account_orders">

                        <thead>
                        <tr>
                            <?php foreach ( $my_orders_columns as $column_id => $column_name ) : ?>
                                <th class="<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
                            <?php endforeach; ?>
                        </tr>
                        </thead>

                        <tbody>
                        <?php foreach ( $customer_orders as $customer_order ) :
                            $order      = wc_get_order( $customer_order );
                            $item_count = $order->get_item_count();
                            ?>
                            <tr class="order">
                                <?php foreach ( $my_orders_columns as $column_id => $column_name ) : ?>
                                    <td class="<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
                                        <?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
                                            <?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

                                        <?php elseif ( 'order-number' === $column_id ) : ?>
                                            <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
                                                <?php echo _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number(); ?>
                                                <?php echo esc_html( $order->get_billing_email() ); ?>
                                                <?php echo esc_html( $order->get_billing_first_name()); ?>
                                            </a>

                                        <?php elseif ( 'order-date' === $column_id ) : ?>
                                            <time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>

                                        <?php elseif ( 'order-status' === $column_id ) : ?>
                                            <?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>

                                        <?php elseif ( 'order-total' === $column_id ) : ?>
                                            <?php
                                            /* translators: 1: formatted order total 2: total order items */
                                            printf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count );
                                            ?>

                                        <?php elseif ( 'order-actions' === $column_id ) : ?>
                                            <?php
                                            $actions = wc_get_account_orders_actions( $order );

                                            if ( ! empty( $actions ) ) {
                                                foreach ( $actions as $key => $action ) {
                                                    echo '<a href="' . esc_url( $action['url'] ) . '" class="button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
                                                }
                                            }
                                            ?>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
                <div class="common-table-design">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-purple">
                            <tr>
                                <th>Comfirmation #</th>
                                <th>Date</th>
                                <th>Ticket Buyer</th>
                                <th>Emails</th>
                                <th>Price</th>
                                <th>Quantity</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td>x1</td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td>x1</td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td>x1</td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td>x1</td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td>x1</td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td>x1</td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td>x1</td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td>x1</td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td>x1</td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td>x1</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- My Ticket Tab6 -->
            <div class="tab-pane fade" id="refunds">
                <div class="m-e-table-dropdown">
                    <select class="form-controls">
                        <option>Refunds</option>
                        <option>Refunds</option>
                        <option>Refunds</option>
                        <option>Refunds</option>
                        <option>Refunds</option>
                        <option>Refunds</option>
                    </select>
                </div>
                <div class="common-table-design">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-purple">
                            <tr>
                                <th>Comfirmation #</th>
                                <th>Date</th>
                                <th>Ticket Buyer</th>
                                <th>Emails</th>
                                <th>Price</th>
                                <th class="text-center">Accept/Deny</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td align="center">
                                    <button class="accept">Accept</button>
                                    <button class="deny">Deny</button>
                                </td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td align="center">
                                    <span class="accept-denied">Denied</span>
                                </td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td align="center">
                                    <button class="accept">Accept</button>
                                    <button class="deny">Deny</button>
                                </td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td align="center">
                                    <button class="accept">Accept</button>
                                    <button class="deny">Deny</button>
                                </td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td align="center">
                                    <span class="accept-denied">Accepted</span>
                                </td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td align="center">
                                    <span class="accept-denied">Accepted</span>
                                </td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td align="center">
                                    <button class="accept">Accept</button>
                                    <button class="deny">Deny</button>
                                </td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td align="center">
                                    <button class="accept">Accept</button>
                                    <button class="deny">Deny</button>
                                </td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td align="center">
                                    <span class="accept-denied">Accepted</span>
                                </td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td align="center">
                                    <span class="accept-denied">Denied</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- My Ticket Tab7 -->
            <div class="tab-pane fade show" id="contact">
                <div class="manage-events-contact">
                    <div class="m-e-contact-search">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search...">
                    </div>
                    <div class="m-e-contact-details get_report_problem_contact_data">
                        <div class="tabledataajax"></div>
                        <div class="tableloader" data-loader="<?php echo get_template_directory_uri().'/assets/images/loader.png'; ?>"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>