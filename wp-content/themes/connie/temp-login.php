<?php /* Template Name: Login Register Page */ ?>
<?php get_header('blank'); ?>
<div class="container-fluid login-main">
    <div class="row">
        <!-- left side -->        
         <div class="col-sm-12 col-lg-6 p-0">
          <div class="login-bg">
            <div class="lg-lft-box">
              <div class="login-bg-sdw"></div>
              <div class="icon-logo"><a href="<?php echo site_url(); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-icn.png" alt=""></a></div>
            </div>
          </div>
        </div>
        <!-- right side -->
        <div class="col-sm-12 col-lg-6 p-0 lgn-lft-img">
            <div class="lg-rgt-box">
                <div class="login-header">
                    <h1>Find & Create Geeky, Nerdy Events In Your Neighborhood</h1>
                </div>                
                <?php
                    // Start the loop.
                    while ( have_posts() ) :
                        the_post();

                        // Include the page content template.
                        the_content();
                        // End of the loop.
                    endwhile;
                ?>           
            </div>
        </div>
    </div>
</div>

<?php get_footer('blank'); ?>
