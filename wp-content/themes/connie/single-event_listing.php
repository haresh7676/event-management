<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header();
?>
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
			<?php
			// Start the loop.
			while ( have_posts() ) :
				the_post();
			$tickets = get_query_var('event_action');
	          	if(isset($tickets) && $tickets == 'tickets'){
	                get_template_part( 'template-parts/content', 'single-event-cart' );
	            }
	            else{
	            	get_template_part( 'template-parts/content', 'single' );
	            }
			// End of the loop.
			endwhile;
			?>
	</main><!-- .site-main -->

	<?php //get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>
