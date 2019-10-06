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
     <div class="event-img u-event-pic"><?php display_event_banner(); ?> <div class="whislistwpr">
        <?php $postid = $post->ID; 
        $userid = !empty(get_current_user_id())?get_current_user_id():'0';
        $isFavorited = false;
        if($userid != 0){
            $isFavorited = usrebygetfavrite($userid,$postid);            
        }        
        echo '<a href="javascript:void(0)" class="btn btn-default addermovefav" id="mylists-'.$postid.'" data-postid="'.$postid.'" data-styletarget="'.$isFavorited.'" data-userid="'.$userid.'" data-action="'.(($isFavorited == 1)?'remove':'add').'" data-ajax="'.admin_url('admin-ajax.php').'"><i class="'.(($isFavorited == 1)?'fas':'far').' fa-heart"></i></a>';
        ?>
        <?php //echo do_shortcode('[show_gd_mylist_btn]'); ?></div><?php //echo (function_exists('get_favorites_button'))?get_favorites_button($post->ID, ''):''; ?><!--<i class="fas fa-heart"></i>--></div>      
    <div class="u-event-details">
        <div class="event-start-date date-month"><?php $date_format = WP_Event_Manager_Date_Time::get_event_manager_view_date_format();
            //echo date_i18n( $date_format, strtotime(get_event_start_date()) );?>
            <span><?php echo date_i18n( 'M', strtotime(get_event_start_date()) ); ?></span>
            <label><?php echo date_i18n( 'd', strtotime(get_event_start_date()) ); ?></label>
        </div>
        <div class="event-disc">
            <!--<div class="event-title">-->
                <h4 class="eventtitle-listing" title="<?php the_title(); ?>"><?php the_title(); ?></h4>
            <!--</div>-->
            <ul>
                <?php $newformate = 'D, M jS'; ?>
                <li title="<?php echo date_i18n( $newformate, strtotime(get_event_start_date()) ); ?><?php echo (strtotime(get_event_start_date()) != strtotime(get_event_end_date())) ? date_i18n( ' - M jS,', strtotime(get_event_end_date()) ):','; ?>&nbsp;<?php display_event_start_time();?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/clock.png" alt=""><?php echo date_i18n( $newformate, strtotime(get_event_start_date()) ); ?><?php echo (strtotime(get_event_start_date()) != strtotime(get_event_end_date())) ? date_i18n( ' - M jS,', strtotime(get_event_end_date()) ):','; ?>&nbsp;<?php display_event_start_time();?> - <?php display_event_end_time();?></li>
                <?php 
                $eventvenu = get_event_venue_name();
                $eventlocation = get_event_location();
                $printlocations = '';
                if(!empty($eventvenu) || !empty($eventlocation)){
                    $printlocations = !empty($eventvenu)?$eventvenu:$eventlocation;
                }
                if(!empty($printlocations)){
                ?>
                <li title="<?php if(get_event_location()=='Anywhere'): echo __('Online Event','wp-event-manager'); else: echo $printlocations; endif; ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/map-icon.png" alt=""><?php if(get_event_location()=='Anywhere'): echo __('Online Event','wp-event-manager'); else: echo $printlocations; endif; ?></li>
                <?php } ?>                
                <li><?php echo (function_exists('get_sell_start_price')?get_sell_start_price($post->ID):''); ?> </li>
            </ul>           
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

