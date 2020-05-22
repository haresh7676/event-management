<?php
/**
 * The template part for displaying single posts
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php //twentysixteen_excerpt(); ?>

	<?php //twentysixteen_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
			the_content();

			

			if ( '' !== get_the_author_meta( 'description' ) ) {
				get_template_part( 'template-parts/biography' );
			}
			?>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
