<?php $order = new WC_Order( $order_id );?>
<hr>
<dl class="event-registration-meta">
    
    <dt><?php _e('Buyer\'s Name : ','wp-event-manager-sell-tickets');?></dt>
    <dd><?php echo $order->get_billing_first_name() .'&nbsp'. $order->get_billing_last_name();?></dd>
    <dt><?php _e('Total Quantity :','wp-event-manager-sell-tickets');?></dt>
    <dd><?php echo $order->get_item_count();?></dd>
    
</dl>