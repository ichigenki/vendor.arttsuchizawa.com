<?php
/**
 * The template for displaying "image" page
 */

global $aPageManagementData;
global $aDateData;

if( !function_exists('is_tacfv_image_attr') ) {
	function is_tacfv_image_attr($aAttrData=array()) {
		$i = 0;
		$n = 0;
		foreach( $aAttrData as $key => $val ) :
			if( isset($_GET[$key]) && $val === $_GET[$key] ) {
				$i++;
			}
			$n++;
		endforeach;
		if( $n === $i ) {
			return true;
		}
		return false;
	}
}

if( is_tacfv_image_attr($aPageManagementData['file']) ) {

get_header();
?>

<div class="wrap">
<div id="primary" class="content-area">
<main id="main" class="site-main entry-main">

<?php while ( have_posts() ) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<header class="entry-header">
<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
</header><!-- .entry-header -->
<div class="entry-content">
<?php the_content(); ?>
</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
<?php endwhile; // End the loop. ?>
</main><!-- #main -->
</div><!-- #primary -->
</div><!-- .wrap -->

<?php
get_footer();

} else {

	get_template_part('page', 'accessible');

}
