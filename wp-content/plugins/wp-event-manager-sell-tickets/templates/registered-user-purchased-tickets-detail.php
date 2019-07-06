<?php if(!empty($tickets_type)) { ?>
<li><?php _e('Ticket Type : ','wp-event-manager-sell-tickets'); _e($tickets_type,'wp-event-manager-sell-tickets');?></li>
<?php 
    }
if(!empty($ticket_price)) { ?>
<li><?php _e('Ticket Price : ','wp-event-manager-sell-tickets'); _e($ticket_price,'wp-event-manager-sell-tickets');?></li>
<?php } 
if(!empty($ticket_names) && is_array($ticket_names) ) { ?>
<li><?php _e('Ticket Name : ','wp-event-manager-sell-tickets'); echo implode(',',$ticket_names);?></li>
<?php } ?>
