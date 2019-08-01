<div class="container">
	<div class="cart-main-area tickets">
		<div class="cart-page-title"><a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a></div>
        <div class="wpmc-tabs-wrapper">
            <ul class="wpmc-tabs-list wpmc-2-tabs">
                <li class="wpmc-tab-item-static wpmc-tab-item current">
                    <div class="wpmc-tab-number">1</div>
                    <div class="wpmc-tab-text">Ticket</div>
                </li>
                <li class="wpmc-tab-item wpmc-billing">
                    <div class="wpmc-tab-number">2</div>
                    <div class="wpmc-tab-text">Delivery</div>
                </li>
                <li class="wpmc-tab-item wpmc-review">
                    <div class="wpmc-tab-number">3</div>
                    <div class="wpmc-tab-text">  Payment</div>
                </li>
                <li class="wpmc-tab-item-static wpmc-tab-item">
                    <div class="wpmc-tab-number">4</div>
                    <div class="wpmc-tab-text">Confirmation</div>
                </li>
            </ul>
        </div>
		<div class="cart-content">
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
		</div>
	</div>
</div>