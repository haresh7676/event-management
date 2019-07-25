<?php
echo 'asss';
echo do_shortcode('[event_sell_tickets event_id="'.get_the_ID().'"]');
/*global $woocommerce;

$items = $woocommerce->cart->get_cart();
$currency = get_woocommerce_currency_symbol();

echo '<li><a href="https://www.mywebsite.com/cart/" class="parents"><i class="fas fa-shopping-cart"></i> Cart - ' .  WC()->cart->get_cart_total() . '</a>';
echo '<ul class="mega_menu cart">';
echo '<li class="mega_sub">';
echo '<ul>';

foreach($items as $item => $values) {
    $_product = $values['data']->post;
    $link = get_permalink($_product);
    $image = get_the_post_thumbnail($_product);
    $price = get_post_meta($values['product_id'] , '_price', true);
    $total = floatval( preg_replace( '#[^\d.]#', '', $woocommerce->cart->get_cart_total() ) );
    echo '<li>' . $image . '<a href="' . $link . '">' . $_product->post_title . ' <br /><span style=" text-transform:lowercase;"> ' . $currency.$price . ' x ' . $values['quantity'] .'</span></a></li>';
}

echo '<li><a href="https://www.mywebsite.com/cart/">Go to Checkout</a></li>';
echo '</ul></li></ul>';*/
?>

<?php global $woocommerce; ?>

<a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e('View your shopping cart', 'woothemes'); ?>"><?php echo sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);?> - <?php echo $woocommerce->cart->get_cart_total(); ?></a>

<?php /* do_action( 'woocommerce_before_mini_cart' ); ?>

<ul class="cart_list product_list_widget <?php echo $args['list_class']; ?>">

    <?php if ( sizeof( WC()->cart->get_cart() ) > 0 ) : ?>

        <?php
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
            $product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

                $product_name  = apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
                $thumbnail     = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                $product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                echo "<p>".$product_price."</p>";
                ?>
                <li>
                    <?php if ( ! $_product->is_visible() ) { ?>
                        <?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ) . $product_name; ?>
                    <?php } else { ?>
                        <a href="<?php echo get_permalink( $product_id ); ?>">
                            <?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ) . $product_name; ?>
                        </a>
                    <?php } ?>
                    <?php echo WC()->cart->get_item_data( $cart_item ); ?>
                    <?php   $new_product_price_array = explode ( get_woocommerce_currency_symbol(), $product_price);
                    $new_product_price = number_format((float)$new_product_price_array[1] * $cart_item['quantity'], 2, '.', '');

                    ?>
                    <?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s=%s%s', $cart_item['quantity'], $product_price,get_woocommerce_currency_symbol(), $new_product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
                </li>
                <?php
            }
        }
        ?>

    <?php else : ?>

        <li class="empty"><?php _e( 'No products in the cart.', 'woocommerce' ); ?></li>

    <?php endif; ?>

</ul><!-- end product list -->

<?php if ( sizeof( WC()->cart->get_cart() ) > 0 ) : ?>

    <p class="total"><strong><?php _e( 'Subtotal', 'woocommerce' ); ?>:</strong> <?php echo WC()->cart->get_cart_subtotal(); ?></p>

    <?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

    <p class="buttons">
        <a href="<?php echo WC()->cart->get_cart_url(); ?>" class="button wc-forward"><?php _e( 'View Cart', 'woocommerce' ); ?></a>
        <a href="<?php echo WC()->cart->get_checkout_url(); ?>" class="button checkout wc-forward"><?php _e( 'Checkout', 'woocommerce' ); ?></a>
    </p>

<?php endif; ?>

<?php do_action( 'woocommerce_after_mini_cart' ); */?>

