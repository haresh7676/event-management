<div class="cart-wpr checkout-step">
<?php
global $woocommerce;
echo do_shortcode('[event_sell_tickets event_id="'.get_the_ID().'"]');
echo '<div class="min-cart-main">';
echo '<div class="cart-details-title">';
echo '<h3>'.__('Your Order','wp-event-manager-sell-tickets').'</h3>';
echo '</div>';
echo '<div id="mode-mini-cart">';
woocommerce_mini_cart();
echo '</div>';
echo '</div>';
?>
</div>

