<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since Twenty Seventeen 1.0
 * @version 1.0
 */

get_header(); ?>

<div class="wrap">

<?php if ( have_posts() ) : ?>
<header class="page-header">
<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
</header><!-- .page-header -->
<?php endif; ?>

<div id="primary" class="content-area">
<main id="main" class="site-main">

<?php
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/post/content', get_post_format() );

	endwhile;

endif;
?>

</main><!-- #main -->
</div><!-- #primary -->
</div><!-- .wrap -->

<?php
get_footer();
