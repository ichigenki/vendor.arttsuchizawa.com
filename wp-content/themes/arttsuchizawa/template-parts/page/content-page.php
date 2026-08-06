<?php
/**
 * Template part for displaying page content in page.php
 */

global $post;
$article_class = $post->post_name.'-article'
?>

<article id="post-<?php the_ID(); ?>" <?php post_class($article_class); ?>>
<?php if( !post_password_required($post->ID) ): ?>
<header class="entry-header">
<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
<?php //twentyseventeen_edit_link( get_the_ID() ); ?>
</header><!-- .entry-header -->
<?php endif; ?>

<?php
$management_id = get_page_by_path('management')->ID;
$args = array(
	'post_type' => 'page',
	'post_parent' => $management_id,
	'post_status' => 'publish',
	'posts_per_page' => -1
);
$get_posts = get_posts($args);
if( $get_posts ) :
	foreach( $get_posts as $post ) :
		$slug = $post->post_name;
		if( is_page($slug) ) :
			get_template_part( 'template-parts/page/entry-content', $slug );
		endif;
	endforeach;
endif;
wp_reset_postdata();
?>

</article><!-- #post-<?php the_ID(); ?> -->
