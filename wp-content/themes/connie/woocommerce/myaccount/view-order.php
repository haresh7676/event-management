<?php
/**
 * View Order
 *
 * Shows the details of a particular order on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/view-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$orderid = $order->get_order_number();
$order_items= $order->get_items();
if(!empty($order_items)) {
    foreach ($order_items as $item_id => $item) {
        $order_productid = $item->get_product_id();
    }
    $eventid = '';
    if (!empty($order_productid)) {
        $eventid = get_post_meta($order_productid, '_event_id', true);
    }
}
?>
<div class="event-detail-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-sm-12">
                <?php
                if(!empty($eventid)){
                    global $post;
                    $post = get_post( $eventid, OBJECT );
                    setup_postdata( $post ); ?>
                    <div class="u-events-box">
                        <div class="u-event-pic">
                            <?php display_event_banner(); ?>
                        </div>
                        <div class="u-event-details">
                            <div class="event-disc">
                                <h4><?php echo get_the_title(); ?></h4>
                                <?php
                                echo '<ul>';
                                $newformate = 'D, M jS';
                                echo '<li><img src="'.get_template_directory_uri().'/assets/images/clock.png" alt="">'. date_i18n( $newformate, strtotime(get_event_start_date()) ).((strtotime(get_event_start_date()) != strtotime(get_event_end_date())) ? date_i18n( ' - M jS,', strtotime(get_event_end_date()) ):','). display_event_start_time(false,false,false).'</li>';
                                echo '<li><img src="'.get_template_directory_uri().'/assets/images/map-icon.png" alt="">'.display_event_venue_name(false,false,false).'</li>';
                                echo '</ul>';
                                ?>
                            </div>
                        </div>
                    </div>
                <?php
                    wp_reset_postdata();
                } ?>
            </div>
            <div class="col-lg-8 col-sm-12">
                <div class="grab-tickets">
                    <div class="grab-tickets-title">
                        <span>Grab Your Tickets</span>
                        <a href="#">Details</a>
                    </div>
                    <div class="grab-tickets-step">
                        <div class="grab-tickets-box" onclick=window.open('<?php echo site_url().'/my-account/my-tickets/?download_ticket=true&order_id='.$orderid; ?>')>
                            <span>View Tickets</span>
                            <label class="grab-tickets-icon"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/step-view-icon.svg" class="img-fluid" alt=""></label>
                        </div>
                        <div class="grab-tickets-box" onclick=window.open('<?php echo site_url().'/my-account/my-tickets/?download_ticket=true&order_id='.$orderid; ?>')>
                            <span>Print Tickets</span>
                            <label class="grab-tickets-icon"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/step-print-icon.svg" class="img-fluid" alt=""></label>
                        </div>
                        <!--<div class="grab-tickets-box">
                            <span>Transfer Tickets</span>
                            <label class="grab-tickets-icon"><i class="fas fa-envelope"></i></label>
                        </div>
                        <div class="grab-tickets-box">
                            <span>Sell Tickets</span>
                            <label class="grab-tickets-icon"><img src="<?php /*echo get_template_directory_uri(); */?>/assets/images/step-sell-icon.svg" class="img-fluid" alt=""></label>
                        </div>-->
                    </div>
                </div>
                <div class="grab-tickets grab-ticket-card">
                    <div class="grab-tickets-title">
                        <span>My Tickets</span>
                        <!--<a href="#">Edit Tickets</a>-->
                    </div>
                    <div class="grab-tickets-card-detail">
                        <?php
                        if(!empty($order_items)) {
                            echo '<ul>';
                            foreach ($order_items as $item_id => $item) {
                                echo '<li>';
                                echo '<span>'.$item->get_name().'</span>';
                                echo '<label>'.esc_html($order->get_billing_first_name()) . ' ' . esc_html($order->get_billing_last_name()).'</label>';
                                echo '</li>';
                            }
                            echo '</ul>';
                        }
                        ?>
                    </div>
                </div>
                <div class="grab-tickets grab-ticket-card">
                    <div class="grab-tickets-title">
                        <span>Ticket Order</span>
                    </div>
                    <div class="grab-tickets-card-detail">
                        <ul>
                            <li>
                                <span>Delivery </span>
                                <label><?php echo $order->get_shipping_method(); ?></label>
                            </li>
                            <li>
                                <span>Confirmation Number</span>
                                <label><strong>#<?php echo $order->get_order_number(); ?></strong></label>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="grab-tickets ticket-insurance">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="insurance-title">Ticket Insurance</div>
                        </div>
                        <div class="col-lg-7">
                            <div class="insurance-detail">
                                <p>The insurance component provides reimbursement for 100 percent of the ticket price including taxes and shipping charges. With event ticket insurance, your financial loss will be covered if you can’t attend an event. Event Ticket Protector also includes access to the Allianz Global Assistance Event 24-hour assistance hotline. A staff of multilingual problem solvers is available to help you with any medical, legal or travel-related emergency. Please call 1-800-424-3396.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
