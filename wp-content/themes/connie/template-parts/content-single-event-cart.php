<div class="container">
	<div class="cart-main-area tickets">
		<div class="cart-page-title"><a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a></div>
        <?php //include_once(plugin_dir_path(__FILE__) . "wp-multi-step-checkout/includes/form-tabs.php"); ?>
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