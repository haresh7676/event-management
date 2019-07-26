<div class="cart-wpr checkout-step">
<?php
global $woocommerce;
echo do_shortcode('[event_sell_tickets event_id="'.get_the_ID().'"]');
echo '<div class="min-cart-main" id="mode-mini-cart">';
woocommerce_mini_cart();
echo '</div>';
?>
</div>

