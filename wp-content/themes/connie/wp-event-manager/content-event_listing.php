<div  class="line-layout">
<li  <?php event_listing_class(); ?> data-longitude="<?php echo esc_attr( $post->geolocation_lat ); ?>" data-latitude="<?php echo esc_attr( $post->geolocation_long ); ?>">
  <div class="event-info-row-listing"> 
	<a href="<?php display_event_permalink(); ?>">
	   <div class="row">
            <div class="col-md-1">
                <div class="organizer-logo">         
                    <?php  display_event_banner(); ?>                       
                </div>               
            </div>            
            <div class="col-md-4">                
                <div class="event-title">                   
                    <h4><?php echo esc_html( get_the_title() ); ?></h4>
                    <div class="boxes-view-listing-registered-code">
                        <?php do_action('event_already_registered_title');?>
                    </div>  
                </div>
                <div class="event-organizer-name">
                    <?php display_organizer_name( '<normal>', '<normal>' ); ?>
                </div>                
            </div>
            <div class="col-md-2">		        
    			   <div class="date">
    			        <date>
    			        <?php 
    			             $date_format = WP_Event_Manager_Date_Time::get_event_manager_view_date_format();
    			             echo date_i18n($date_format, strtotime(get_event_start_date()));?>
    			        </date>    			        
    			   </div>       			  
	        </div>
	        <div class="col-md-3">		
		        <div class="event-location"><i class="glyphicon glyphicon-map-marker"></i>
		          <?php if(get_event_location()=='Anywhere' || get_event_location() == ''): echo __('Online Event','wp-event-manager'); else:  display_event_location(false); endif; ?>
		       </div>		
	        </div>
	        <div class="col-md-2">
                <div class="event-ticket"><?php echo '#'.get_event_ticket_option(); ?></div>            
            </div>
            <div class="col-md-3"> <?php if ( get_option( 'event_manager_enable_event_types' ) ) { display_event_type(); } ?></div>
        </div>
      </a> 
     </div>
   </li>
</div>

<!-- Box Layout -->
<a <?php event_listing_class(); ?> href="<?php display_event_permalink(); ?>">
 <div class="box-layout u-events-box">
    <div class="event-img u-event-pic"><?php display_event_banner(); ?> <?php echo (function_exists('get_favorites_button'))?get_favorites_button($post->ID, ''):''; ?><!--<i class="fas fa-heart"></i>--></div>
      <!-- <div class="boxes-view-box-registered-code">
                <?php /*do_action('event_already_registered_title');*/?>
        </div>-->
    <div class="u-event-details">
        <div class="event-start-date date-month"><?php $date_format = WP_Event_Manager_Date_Time::get_event_manager_view_date_format();
            //echo date_i18n( $date_format, strtotime(get_event_start_date()) );?>
            <span><?php echo date_i18n( 'M', strtotime(get_event_start_date()) ); ?></span>
            <label><?php echo date_i18n( 'd', strtotime(get_event_start_date()) ); ?></label>
        </div>
        <div class="event-disc">
            <!--<div class="event-title">-->
                <h4><?php the_title(); ?></h4>
            <!--</div>-->
            <ul>
                <?php $newformate = 'D, M jS'; ?>
                <li><img src="<?php echo get_template_directory_uri(); ?>/assets/images/clock.png" alt=""><?php echo date_i18n( $newformate, strtotime(get_event_start_date()) ); ?><?php echo (strtotime(get_event_start_date()) != strtotime(get_event_end_date())) ? date_i18n( ' - M jS,', strtotime(get_event_end_date()) ):','; ?>&nbsp;<?php display_event_start_time();?></li>
                <li><img src="<?php echo get_template_directory_uri(); ?>/assets/images/map-icon.png" alt=""><?php if(get_event_location()=='Anywhere'): echo __('Online Event','wp-event-manager'); else:  display_event_venue_name(false); endif; ?></li>
                <li>Starts at $35.00</li>
            </ul>
            <!--<div class="event-location">
             <i class="glyphicon glyphicon-map-marker"></i>
             <?php /*if(get_event_location()=='Anywhere'): echo __('Online Event','wp-event-manager'); else:  display_event_location(false); endif; */?>
            </div>
            <div class="box-footer">
             <?php /*if ( get_option( 'event_manager_enable_event_types' ) ) {  */?>
              <div class=""> <?php /*display_event_type(); */?> </div>
              <?php /*} */?>
              <div class="event-ticket"><?php /*echo '#'.get_event_ticket_option(); */?></div>
            </div>-->
        </div>
    </div>
 </div>
</a>     
<!-- Box Layout end-->

<script> 
jQuery(document).ready(function($) 
{   
   ContentEventListing.init();
});</script>

