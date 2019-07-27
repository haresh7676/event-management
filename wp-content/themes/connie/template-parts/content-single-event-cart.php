<div class="cart-wpr checkout-step">
<?php
global $woocommerce;
echo do_shortcode('[event_sell_tickets event_id="'.get_the_ID().'"]');
echo '<div class="min-cart-main">';
echo '<h3 class="section-title">'.__('Your Order','wp-event-manager-sell-tickets').'</h3>';
echo '<div id="mode-mini-cart">';
woocommerce_mini_cart();
echo '</div>';
echo '</div>';
?>
</div>

