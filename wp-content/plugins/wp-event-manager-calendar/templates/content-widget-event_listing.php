<?php if(! empty($event_id)){$args = array(			'numberposts' => 1,			'post_type' => 'event_listing',			'post_status' => 'publish',			'post_id' => $event_id,		);$events = get_posts(  $args );if(!empty($events))    {        $events_id=$events[0]->ID;        $start_date = get_post_meta($event_id, '_event_start_date', true);        $timestamp = strtotime($start_date);        $evt_day 	= date( 'j', $timestamp );        $evt_month 	= date( 'F', $timestamp );        $evt_year 	= date( 'Y', $timestamp );    ?>            <!-- Events Display Widget-->            <a href="<?php echo get_permalink($event_id); ?>">             <div class="event-widget">               <div class="event-start-date"><?php echo $evt_month."-".$evt_day."-" .$evt_year; ?></div>                <div class="event-img">                <img src="<?php echo get_event_banner($event_id); ?>"/></div>                           <div class="event-title">                <?php echo get_the_title( $event_id ); ?>                </div>                </div>              </div>             </a>     <?php        }        else{    		_e('No events found','wp-event-manager-calendar');         }}else {		_e('No events found','wp-event-manager-calendar');     }?> 