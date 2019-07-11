<?php /* Template Name: browes Event */ ?>
<?php get_header(); ?>
<div class="row">
  <div class="browe-event-content">
    <div class="browe-event-filter">
      <div class="container">
         <form method="GET" action="http://localhost/event-management/events">
        <div class="landing-serach">
          <!-- <input type="text" class="event-search" placeholder="Search for events">
          <input type="text" class="city-search" placeholder="Los Angeles">
          <button type="submit" class="landing-submit-btn">Search</button> -->         
          <input type="text" class="event-search"  id="search_keywords" name="search_keywords" placeholder="Search for events" value="<?php echo $_GET['search_keywords']; ?>">
          <input type="text" class="city-search" id="search_location"name="search_location" placeholder="Los Angeles" value="<?php echo $_GET['search_keywords']; ?>">
          <button type="submit" class="landing-submit-btn">Search</button>                    
        </div>
        <a class="browe-event-filter-btn">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/filter-icon.png" alt="">Filter
        </a>
        <div class="browe-event-filter-content">
          <div class="row">
            <div class="col-md-6 col-lg-4">
              <div class="filter-category">
                <h3>Category</h3>               
                <ul>
                  <div class="row">
                    <div class="col-lg-6 col-sm-6">
                      <li>
                        <label class="fancy-radio radio-inline">
                          <input type="checkbox" value="event-cat" name="search_categories[]" id="search_categories" class="required" checked="checked">
                          <span><i></i>Anime</span>
                        </label>
                      </li>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                      <li>
                        <label class="fancy-radio radio-inline">
                          <input type="checkbox" value="event-cat-2" name="search_categories[]" id="search_categories" class="required">
                          <span><i></i>Film</span>
                        </label>
                      </li>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                      <li>
                        <label class="fancy-radio radio-inline">
                          <input type="checkbox" value="event-cat-3" name="search_categories[]" id="search_categories" class="required">
                          <span><i></i>Comic</span>
                        </label>
                      </li>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                      <li>
                        <label class="fancy-radio radio-inline">
                          <input type="radio" value="1" id="is_multi_year_plan_yes" class="required">
                          <span><i></i>Social</span>
                        </label>
                      </li>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                      <li>
                        <label class="fancy-radio radio-inline">
                          <input type="radio" name="is_multi_year_plan" value="1" id="is_multi_year_plan_yes" class="required">
                          <span><i></i>Esports</span>
                        </label>
                      </li>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                      <li>
                        <label class="fancy-radio radio-inline">
                          <input type="radio" name="is_multi_year_plan" value="1" id="is_multi_year_plan_yes" class="required">
                          <span><i></i>Party</span>
                        </label>
                      </li>
                    </div>
                  </div>
                </ul>
              </div>
            </div>
            <div class="col-md-6 col-lg-4">
              <div class="filter-category">
                <h3>Price</h3>
                <ul>
                  <li>
                    <label class="fancy-radio radio-inline">
                      <input type="radio" name="is_multi_year_plan" value="1" id="is_multi_year_plan_yes" class="required">
                      <span><i></i>Any Price</span>
                    </label>
                  </li>
                  <li>
                    <label class="fancy-radio radio-inline">
                      <input type="radio" name="is_multi_year_plan" value="1" id="is_multi_year_plan_yes" class="required">
                      <span><i></i>Free</span>
                    </label>
                  </li>
                </ul>
              </div>
            </div>
            <div class="col-12">
              <div class="row">
                <div class="filter-apply-btn">
                  <button class="btn apply">Apply</button>
                  <button class="btn cancel">Cancel</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
      </div>
    </div>
    <div class="container">
      <div class="browe-event-title">
        <h4>Explore geeky events in Los Angeles</h4>
      </div>
    </div>
    <?php echo do_shortcode('[events show_filters="false" radius="false"]'); ?>     
  </div>
</div>
<?php get_footer(); ?>