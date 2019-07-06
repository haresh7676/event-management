<?php
global $post;
	$start_date = get_post_meta(get_the_ID(),'_event_start_date',true);
	$end_date =get_post_meta(get_the_ID(),'_event_end_date',true);
?>

<a href='<?php  echo esc_url( get_permalink( get_the_ID() ));?>'><?php the_title();?></a>
<br/>
<span><?php  _e('Start date :','wp-event-manager-google-maps');?><?php _e($start_date,'wp-event-manager-google-maps');?></span>
<br/>
<span><?php  _e('End date :','wp-event-manager-google-maps');?><?php _e($end_date,'wp-event-manager-google-maps');?></span>
<br/>
<span><?php  _e('Event Type :','wp-event-manager-google-maps');?><?php _e(display_event_type( $post ),'wp-event-manager-google-maps');?></span>