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

<script type="text/javascript">
jQuery(document).ready(function($){
	var POST = <?php echo json_encode($_POST); ?>;
	var email_address = "<?php echo get_bloginfo('admin_email'); ?>";
	console.log(POST);
	if(POST && POST.post_id) {
		var post_id = POST.post_id;
		var booth_number = POST.booth_number;
		var shop_name = POST.shop_name;
		$('#wpforms-565-field_12').val(booth_number);
		$('#wpforms-565-field_4').val(shop_name);
		$('#wpforms-565-field_7').val(email_address);
		$('#wpforms-565-field_7-secondary').val(email_address);
		$('#wpforms-565-field_1_1').prop('checked', true);
		}
});
</script>
<?php

	get_footer();

} else {

	get_template_part('page', 'accessible');

}
