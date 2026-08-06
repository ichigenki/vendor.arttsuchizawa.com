<?php
/**
 * The template for displaying all pages
 */

get_header();
?>

<?php //if( !is_page(array('shoplist')) ) : ?>
<div class="wrap">
<div id="primary" class="content-area">
<?php //endif; ?>
<main id="main" class="site-main">

<?php
while ( have_posts() ) :
	the_post();

		get_template_part( 'template-parts/page/content', 'page' );

endwhile; // End the loop.
?>

</main><!-- #main -->
<?php //if( !is_page(array('shoplist')) ) : ?>
</div><!-- #primary -->
</div><!-- .wrap -->
<?php //endif; ?>

<?php
get_footer();
