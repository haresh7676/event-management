<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<!-- The steps buttons -->
<div class="wpmc-nav-wrapper">
    <div class="wpmc-footer-left wpmc-nav-buttons backtoticket">
        <?php if( $options['show_back_to_cart_button'] ) : ?>
        <button data-href="<?php echo wc_get_cart_url(); ?>" id="wpmc-back-to-cart" class="button alt" type="button"><?php echo $options['t_back_to_cart']; ?></button>
        <?php endif; ?>
        <?php 
        $custom_linkback = 'javascript:history.go(-1)';
        if(!empty(WC()->cart->get_cart())){
            foreach ( WC()->cart->get_cart() as $cart_item ) {
                $product = $cart_item['data'];                    
                if(!empty($product)){
                    $product_id = $product->get_product_id();            
                }
            }
            $eventid = get_post_meta($product_id,'_event_id',true);
            if(!empty($product_id) && !empty($eventid)){
                $eventname = get_post_by_eventid($eventid);
                $custom_linkback = get_permalink($eventid).'/tickets/';
            }
        }    
        ?>
        <a href="<?php echo $custom_linkback; ?>" class="button button-active alt currentbtn"><?php echo $options['t_previous']; ?></a>
        <button id="wpmc-prev" class="button button-inactive alt" type="button"><?php echo $options['t_previous']; ?></button>
    </div>
    <div class="wpmc-footer-right wpmc-nav-buttons">
        <?php if ( $show_login_step ) : ?>
            <button id="wpmc-next" class="button button-active alt" type="button"><?php echo $options['t_next']; ?></button>
            <button id="wpmc-skip-login" class="button button-active current alt" type="button"><?php echo $options['t_skip_login']; ?></button>
        <?php else : ?>
            <button id="wpmc-next" class="button button-active current alt" type="button"><?php echo $options['t_next']; ?></button>
        <?php endif; ?>
    </div>
</div>
