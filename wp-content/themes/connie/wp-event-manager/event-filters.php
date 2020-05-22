<?php wp_enqueue_script( 'wp-event-manager-ajax-filters' ); ?>
<?php do_action( 'event_manager_event_filters_before', $atts ); ?>
<div class="browe-event-filter">
    <div class="container">
<form class="event_filters" id="event_filters">
	<?php do_action( 'event_manager_event_filters_start', $atts ); ?>
	<div class="search_events search-form-containe">
	<?php do_action( 'event_manager_event_filters_search_events_start', $atts ); ?>
        <div class="landing-serach">
        <input type="text" class="event-search" name="search_keywords" id="search_keywords" placeholder="<?php esc_attr_e( 'Search for events', 'wp-event-manager' ); ?>" value="<?php echo esc_attr( $keywords ); ?>" />
        <input type="text" class="city-search" name="search_location" id="search_location"  placeholder="<?php esc_attr_e( 'Location', 'wp-event-manager' ); ?>" value="<?php echo esc_attr( $location ); ?>" />        <button type="submit" class="landing-submit-btn">Search</button>
        </div>
        <a href="javascript:void(0)" class="browe-event-filter-btn">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/filter-icon.png" alt="">Filter
        </a>
        <div class="browe-event-filter-content">
            <div class="row">
                <div class="col-md-6 col-lg-4">
                    <div class="filter-category">
                        <h3>Category</h3>
                        <?php
                        $terms = get_terms( array(
                            'taxonomy' => 'event_listing_category',
                            'hide_empty' => false,
                        ) );
                        if(!empty($terms)){ ?>
                            <ul>
                            <div class="row">
                            <?php
                            $selectedcat = $_GET['search_categories'];
                            foreach ($terms as $term){
                                echo '<div class="col-lg-6 col-sm-6">';
                                echo '<li>';
                                echo '<label class="fancy-radio radio-inline">';
                                if(!empty($selectedcat) && in_array($term->slug,$selectedcat)){
                                    echo '<input type="checkbox" name="search_categories[]" value="'.$term->slug.'" id="search_categories" checked>';
                                }else{
                                    echo '<input type="checkbox" name="search_categories[]" value="'.$term->slug.'" id="search_categories">';
                                }
                                echo '<span title="'.$term->name.'"><i></i>'.$term->name.'</span>';
                                echo '</label>';
                                echo '</li>';
                                echo '</div>';
                                continue;
                            } ?>
                            </div>
                            </ul>
                        <?php
                        }
                       // pr($terms);
                        ?>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="filter-category">
                        <h3>Price</h3>
                        <ul>
                            <li>
                                <label class="fancy-radio radio-inline">
                                    <input type="radio" name="search_ticket_prices[]" id="search_ticket_prices" value="" class="required" <?php echo (!isset($_GET['search_ticket_prices']) || empty($_GET['ticket_price_free']) || $_GET['ticket_price_free'] == 'ticket_price_free')?'checked':''; ?>>
                                    <span><i></i>Any Price</span>
                                </label>
                            </li>
                            <li>
                                <label class="fancy-radio radio-inline">
                                    <input type="radio" name="search_ticket_prices[]" id="search_ticket_prices" value="ticket_price_free" class="required" <?php echo (isset($_GET['search_ticket_prices']) && in_array('ticket_price_free',$_GET['search_ticket_prices']))?'checked':''; ?>>
                                    <span><i></i>Free</span>
                                </label>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-12">
                    <div class="filter-apply-btn">
                        <button class="btn apply">Apply</button>
                        <a href="<?php echo site_url().'/events/';?>" class="btn cancel">Reset</a>
                    </div>
                </div>
            </div>
        </div>
    <?php //do_action( 'event_manager_event_filters_search_events_end', $atts ); ?>
  </div>
  <?php //do_action( 'event_manager_event_filters_end', $atts ); ?>
</form>
    </div>
</div>
<div class="container">
    <div class="browe-event-title">
        <h4>Explore geeky events</h4>
    </div>
</div>
<?php do_action( 'event_manager_event_filters_after', $atts ); ?>
<noscript><?php _e( 'Your browser does not support JavaScript, or it is disabled. JavaScript must be enabled in order to view listings.', 'wp-event-manager' ); ?></noscript>