<?php /* Template Name: browes Event */ ?>
<?php get_header(); ?>
<div class="row">
  <div class="browe-event-content">

    <div class="container">
      <div class="browe-event-title">
        <h4>Explore geeky events in Los Angeles</h4>
      </div>
    </div>
    <?php echo do_shortcode('[events show_filters="false" radius="false"]'); ?>
  </div>
</div>
<?php get_footer(); ?>