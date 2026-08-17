<?php
/**
 * The template for displaying "flyer" page
 */

global $aPageManagementData;
global $aDateData;

if( !function_exists('is_tacfv_flyer_attr') ) {
	function is_tacfv_flyer_attr($aAttrData=array()) {
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

if( is_tacfv_flyer_attr($aPageManagementData['flyer']) ) {

get_header();
?>

<div class="wrap">
<!-- <div id="primary" class="content-area"> -->
<main id="main" class="site-main entry-main">

<?php while ( have_posts() ) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<header class="entry-header">
<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

</header><!-- .entry-header -->
<div class="entry-content">

<?php the_content(); ?>

<?php
$args = array(
	'post_type' => 'vendors',
	'post_parent' => 0,
	'post_status' => 'publish',
	'tax_query' => array(
		'relation' => 'AND',
		array(
			'taxonomy' => 'vendor-results',
			'field' => 'name',
			'terms' =>array('当選'),
		),
		array(
			'taxonomy' => 'vendor-source',
			'field' => 'name',
			'terms' =>array('Web申込み','書類'),
		),
	),
	'meta_key' => 'vendor_manage_pass_booth_number',
	'orderby' => 'meta_value_num',
	'order' => 'ASC',
	'posts_per_page' => -1
);
$the_query = new WP_Query($args);
if( $the_query->have_posts() ) :
?>
<table id="flyer-data-table" class="flyer-data-table">
<caption></caption>
<thead>
<tr>
<th class="number" style="text-align: right; ">No.</th>
<th class="photo">写真</th>
<th class="name">店名</th>
<th class="from">地域</th>
<th class="genres">ジャンル</th>
<?php if(is_user_logged_in() && current_user_can('administrator')): ?>
<th class="upload">Upload</th>
<?php endif; ?>
</tr>
</thead>
<tbody>
<?php
	while( $the_query->have_posts() ) :
		$the_query->the_post();
		$post_id         = get_the_ID();
		$shop_genres     = get_the_terms($post_id, 'vendor-genres')[0]->name;
		$shop_name       = get_post_meta($post_id, 'vendor_entry_shop', true);
		$photo_selection = get_post_meta($post_id, 'vendor_entry_photo', true);
		$photo_entry     = get_post_meta($post_id, 'vendor_entry_flyer_photo', true);
		$photo_iclusive  = get_post_meta($post_id, 'vendor_entry_flyer_photo_inclusiv', true);
		$photo_upload    = get_post_meta($post_id, 'vendor_upload_photo', true);
		$booth_number    = get_post_meta($post_id, 'vendor_manage_pass_booth_number', true);
		$come_from       = get_post_meta($post_id, 'vendor_manage_pass_comefrom', true);
		$location_area   = get_post_meta($post_id, 'vendor_manage_pass_location', true);

		// 表示する写真の選別
		$photo = '';
		if( $photo_upload ) :
			$photo = $photo_upload;
		elseif( preg_match('/選考用写真/', $photo_iclusive) ) :
			$photo = $photo_selection;
		elseif( preg_match('/アップロード/', $photo_iclusive) ) :
			$photo = $photo_entry;
		endif;

		// ジャンルは「飲食」と「食品」のみ表示
		$shop_genres = '飲食'===$shop_genres || '食品'===$shop_genres? $shop_genres: '';

?>
<tr>
<td class="number"><?php echo esc_html($booth_number); ?></td>
<td class="photo"><a href="<?php echo esc_attr($photo); ?>" target="_blank"><img src="<?php echo esc_attr($photo); ?>"></a></td>
<td class="name"><?php echo esc_html($shop_name); ?></td>
<td class="from"><?php echo esc_html($come_from); ?></td>
<td class="genres"><?php echo esc_html($shop_genres); ?></td>
<?php
		if(is_user_logged_in() && current_user_can('administrator')) {
			// 管理者がログインしている場合は「アップロード」ボタンを表示
			$attr_array = array();
			if( isset($aPageManagementData['file']) && is_array($aPageManagementData['file']) ) {
				foreach( $aPageManagementData['file'] as $key=>$val ) :
					$attr_array[] = $key.'='.$val;
				endforeach;
			}
			$upload_url = get_bloginfo('url').'/accessible/file/?'.implode('&', $attr_array);
?>
<td class="upload">
<form action="<?php echo esc_attr($upload_url); ?>" method="post" target="upload">
<input type="hidden" name="post_id" value="<?php echo esc_attr($post_id); ?>">
<input type="hidden" name="booth_number" value="<?php echo esc_attr($booth_number); ?>">
<input type="hidden" name="shop_name" value="<?php echo esc_attr($shop_name); ?>">
<?php if(empty($photo)): // 写真がない場合のみ表示 ?>
<button type="submit" style="font-size: 14px; font-weight: normal; border-radius: 4px; padding: 0.5em 1em;">Go!</button>
<?php endif; ?>
</form>
</td>
<?php
		}
?>
</tr>
<?php
	endwhile;
?>
</tbody>
</table>
<?php
endif;
wp_reset_postdata();
?>
</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
<?php endwhile; // End the loop. ?>
</main><!-- #main -->
<!--</div><!-- #primary -->
</div><!-- .wrap -->

</div><!-- #content -->

<footer id="colophon" class="site-footer">
<div class="wrap">

<div class="site-info">
<a href="https://arttsuchizawa.com/" target="_blank">&#169; 土澤アートクラフトフェア</a>
</div><!-- .site-info -->

</div><!-- .wrap -->
</footer><!-- #colophon -->
</div><!-- .site-content-contain -->
</div><!-- #page -->
<?php //wp_footer(); ?>
</body>
</html>
<?php
} else {

	get_template_part('page', 'accessible');

}
