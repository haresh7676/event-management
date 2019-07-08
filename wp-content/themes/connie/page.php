<?php
get_header();

if(have_posts()) :
    while (have_posts()) :
        the_post();

        if( have_rows('content') ):
            $sitem = 0;
            while ( have_rows('content') ) :
                the_row();
                $sitem++;
                echo '<div id="'.$sitem.'">';
                get_template_part( 'module/'. get_row_layout() );
                echo '</div>';
            endwhile;

        endif;

    endwhile;
endif;

get_footer();
?>