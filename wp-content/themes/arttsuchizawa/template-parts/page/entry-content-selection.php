<?php
/**
 * The template for displaying "selection" content in page.php
 */

//echo __FILE__;
global $aPrefixData;

// 【ショートコード】「選考シート印刷」ページの印刷見本を書き出す
if( !function_exists('shortcode_tacfv_printsample') ) {
	function shortcode_tacfv_printsample() {
		$output = '';
		ob_start();
?>
<style>
img.print-sample {border: 1px solid #ccc; width: 300px; height: auto; }
#printsample-images figcaption.print-sample-caption {font-size: 14px; font-style: normal; text-align: center; margin-bottom: 0; }
</style>
<div id="printsample-images" class="printsample-images" style="display: flex; flex-wrap: wrap;">
<figure class="wp-block-image size-full is-resized"><img fetchpriority="high" decoding="async" width="1920" height="1272" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/selection-printsample.jpg" alt="" class="print-sample" style="margin-right:0.25em;" /><figcaption class="wp-element-caption print-sample-caption"></figcaption></figure>

</div>
<?php
		$output = ob_get_clean();
		return $output;
	}
	add_shortcode( 'tacfv_printsample', 'shortcode_tacfv_printsample' );
}
?>
<div class="entry-content screen-only">

<?php the_content(); ?>

</div><!-- .entry-content -->

<?php
if( !post_password_required() ) :

	$args = array(
		'post_type' => 'vendors',
		'post_status' => 'publish',
		'meta_key' => 'vendor_entry_number',
		'orderby' => 'meta_value_num date',
		'order' => 'ASC',
		'posts_per_page' => -1
	);
	$the_query = new WP_Query($args);
	if( $the_query->have_posts() ) :
		echo '<div id="printing-only" class="ptinting-only">'.PHP_EOL;

		$end = end($the_query->posts);
		reset($the_query->posts);
		while( $the_query->have_posts() ) :
			$the_query->the_post();
			$post_id = get_the_ID();
			$aTermsGenres = get_the_terms($post_id, $aPrefixData['tax'].'genres');
			// ＊＊＊＊ ループ処理ここから ＊＊＊＊
//var_dump();
?>
<section id="vendor001" class="vendor-section<?php if( $post !== $end ) {echo ' page-break';}; ?>">
<div class="vendor-header">
<h1><span class="fare-title"><?php echo esc_html(get_bloginfo('site_name')); ?><br><span class="normal">《選考用紙》</span></h1>
<div id="vendor-id" class="number"><span class="title">No. </span><span class="value"><?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['manage'].'number', true)); ?></span></div>
<div class="vendor-memo"></div>
</div>
<div class="table-wrap">
<table class="vendor-table">
<tbody>
<tr><th>出<br>店<br>名</th><td>
	<table class="child-table">
	<tr>
	<td class="shop_kana"><span class="value" style="font-size:9pt !important; "><?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'shop_kana', true)); ?></span></td>
	<th rowspan="2">代<br>表<br>者</th>
	<td class="name_kana w-25p"><span class="value" style="font-size:9pt !important; "><?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'name_kana', true)); ?></span></td>
	</tr>
	<tr>
	<td class="shop"><span class="value" style="font-size:18pt !important; "><?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'shop', true)); ?></span></td>
	<td class="name"><span class="value" style="font-size:12pt !important; "><?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'name', true)); ?></span></td>
	</tr>
	</table>
</td></tr>
<tr><th>連<br>絡<br>先<br>他</th><td>
	<table class="child-table">
	<tr>
	<td class="phone"><span class="title">電話番号：</span><span class="value"><?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'phone', true)); ?></span></td>
	<td class="mobile"><span class="title">携帯番号：</span><span class="value"><?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'mobile', true)); ?></span></td>
	<td class="besttimes"><span class="title">連絡可能な時間帯：</span><span class="value"><?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'besttimes', true)); ?></span></td>
	</tr>
	</table>
	<table class="child-table bd-t1px">
	<tr>
	<td class="email"><span class="title">メール：</span><span class="value"><?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'email', true)); ?></span></td>
	<td class="birthday w-25p"><span class="title">生年月日：</span><span class="value"><?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'birthday', true)); ?></span></td>
 <?php
			// キャンセル待ち「する」」を含む時にクラス"marker-on"追加
			$cancel = get_post_meta($post_id, $aPrefixData['meta']['entry'].'cancel', true);
			$class = !empty($cancel) && preg_match('/する/', $cancel)? ' marker-on': '';
 ?>
	<td class="cancel w-25p"><span class="title">キャンセル待ち：</span><span class="value bold<?php echo esc_attr($class); ?>"><?php echo esc_html($cancel); ?></span></td>
	</tr>
	</table>
	<table class="child-table bd-t1px">
	<tr>
	<td><span class="title">住所：</span><span class="value">〒<?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'zip', true)); ?>　<?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'address', true)); ?></span></td>
	</tr>
	</table>
</td></tr>
<tr><th>出店作品</th><td>
	<table class="child-table">
	<tr>
	<td><span class="title">ジャンル：</span><span class="value"><?php echo esc_html(get_the_terms($post_id, $aPrefixData['tax'].'genres')[0]->name); ?></span></td>
	</tr>
	<tr>
	<td><span class="title" style="padding-bottom: 1mm;">出店（作品）内容：</span><br><span class="value"><?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'details', true)); ?></span></td>
	</tr>
	</table>
</td></tr>
<tr><th>車<br>両</th><td>
	<table class="child-table">
	<tr>
<?php
			// 車両台数「１台」」以外の時にクラス"marker-on"追加
			$vehicles = get_post_meta($post_id, $aPrefixData['meta']['entry'].'vehicles', true);
			$class = !empty($vehicles) && '１台'!==$vehicles? ' bold': '';
?>
	<td class="vehicles w-25p"><span class="title">車両台数：</span><span class="value<?php echo esc_attr($class); ?>"><?php echo $vehicles; ?></span></td>
	<td class="car-model w-25p" rowspan="2">
		<span class="title">車種：</span><span class="value"><?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'car1_model', true)); ?></span><br>
		<span class="title">車名：</span><span class="value"><?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'car1_name', true)); ?></span><br>
		<span class="title">ナンバー：</span><span class="value"><?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'car1_plate', true)); ?></span>
	</td>
	<td class="car-model w-25p" rowspan="2">
		<span class="title">車種：</span><span class="value"><?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'car2_model', true)); ?></span><br>
		<span class="title">車名：</span><span class="value"><?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'car2_name', true)); ?></span><br>
		<span class="title">ナンバー：</span><span class="value"><?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'car2_plate', true)); ?></span>
	</td>
	<td class="car-model w-25p" rowspan="2">
		<span class="title">車種：</span><span class="value"><?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'car3_model', true)); ?></span><br>
		<span class="title">車名：</span><span class="value"><?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'car3_name', true)); ?></span><br>
		<span class="title">ナンバー：</span><span class="value"><?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'car3_plate', true)); ?></span>
	</td>
	</tr>
	<tr>
<?php
			// 前日搬入「する」」を含む時にクラス"marker-on"追加
			$daybefore = get_post_meta($post_id, $aPrefixData['meta']['entry'].'daybefore', true);
			$class = !empty($daybefore) && preg_match('/する/', $daybefore)? ' marker-on': '';
?>
	<td class="daybefore"><span class="title">前日搬入：</span><span class="value<?php echo esc_attr($class); ?>"><?php echo esc_html($daybefore); ?></span></td>
	</tr>
	</table>
</td></tr>
<tr><th>エ<br>リ<br>ア</th><td>
	<table class="child-table">
	<tr>
	<td class="location">
		<span class="title">第一希望：</span><span class="value"><?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'location_first', true)); ?></span><br>
<?php
			// 屋内希望「する」」を含む時にクラス"marker-on"追加
			$first_indoor = get_post_meta($post_id, $aPrefixData['meta']['entry'].'location_first_indoor', true);
			$class = !empty($first_indoor) && preg_match('/する/', $first_indoor)? ' marker-on': '';
?>
		<span class="title">屋内希望：</span><span class="value<?php echo esc_attr($class); ?>"><?php echo esc_html($first_indoor); ?></span><br>
<?php
			// 口数「1」」より大きい時にクラス"marker-on"追加
			$first_units = get_post_meta($post_id, $aPrefixData['meta']['entry'].'location_first_units', true);
			$class = !empty($first_units) && 1<$first_units? ' marker-on': '';
?>
		<span class="title">口数：</span><span class="value<?php echo esc_attr($class); ?>"><?php echo esc_html($first_units); ?></span>
	</td>
	<td class="location">
		<span class="title">第二希望：</span><span class="value"><?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'location_second', true)); ?></span><br>
<?php
			// 屋内希望「する」」を含む時にクラス"marker-on"追加
			$second_indoor = get_post_meta($post_id, $aPrefixData['meta']['entry'].'location_second_indoor', true);
			$class = !empty($second_indoor) && preg_match('/する/', $second_indoor)? ' marker-on': '';
?>
		<span class="title">屋内希望：</span><span class="value<?php echo esc_attr($class); ?>"><?php echo esc_html($second_indoor); ?></span><br>
<?php
			// 口数「1」」より大きい時にクラス"marker-on"追加
			$second_units = get_post_meta($post_id, $aPrefixData['meta']['entry'].'location_second_units', true);
			$class = !empty($second_units) && 1<$second_units? ' marker-on': '';
?>
		<span class="title">口数：</span><span class="value fs-12pt<?php echo esc_attr($class); ?>"><?php echo esc_html($second_units); ?></span>
	</td>
	</tr>
	</table>
</td></tr>
<tr><th>その他</th><td>
	<table class="child-table">
	<tr>
	<td class="tentsize w-50p"><span class="title">テントサイズ：</span><span class="value bold"><?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'tentsize', true)); ?></span></td>
	<td class="kitchencar w-50p"><span class="title">キッチンカーサイズ：</span><span class="value bold"><?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'kitchencar', true)); ?></span></td>
	</tr>
	</table>
	<table class="child-table bd-t1px">
	<tr>
	<td><span class="title">発電機使用：</span><span class="value"><?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'generator', true)); ?></td>
	<td><span class="title">ガスコンロ使用：</span><span class="value"><?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'gasstove', true)); ?></span></td>
	<td><span class="title">カセットコンロ使用：</span><span class="value"><?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'portablestove', true)); ?></span></td>
	</tr>
	</table>
	<table class="child-table bd-t1px">
	<tr>
<?php
			// 出店回数「はじめて」を含む時にクラス"marker-on"追加
			$times = get_post_meta($post_id, $aPrefixData['meta']['entry'].'times', true);
			$class = !empty($times) && preg_match('/はじめて/', $times)? ' marker-on': '';
?>
	<td class="times"><span class="title">出店回数：</span><span class="value bold<?php echo esc_attr($class); ?>"><?php echo $times; ?></span></td>
	<td class="experience"><span class="title">直近回の出店：</span><span class="value"><?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'experience', true)); ?></span></td>
	</tr>
	</table>
	<table class="child-table bd-t1px">
	<tr>
	<td class="link"><span class="title">リンク：</span><span class="value"><?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'link', true)); ?></span></td>
	</tr>
	</table>
</td></tr>
<tr><th>通<br>信<br>欄</th><td>
	<table class="child-table">
	<tr>
	<td class="message value"><?php echo nl2br(esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'message', true))); ?></td>
	</tr>
	</table>
</td></tr>
<tr><th>選<br>考<br>写<br>真</th><td>
	<table class="child-table">
	<tr>
	<td class="photo"><img src="<?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'photo', true)); ?>" style="max-width:110mm; max-height:80mm; " /></td>
	<th>チ<br>ラ<br>シ<br>用<br>写<br>真</th>
	<td class="flyer-photo va-top"><?php
		$flyerphoto_key = $aPrefixData['meta']['entry'].'flyer_photo';

		$photo_selection = get_post_meta($post_id, 'vendor_entry_photo', true);
		$photo_entry     = get_post_meta($post_id, 'vendor_entry_flyer_photo', true);
		$photo_iclusive  = get_post_meta($post_id, 'vendor_entry_flyer_photo_inclusiv', true);
		$photo_upload    = get_post_meta($post_id, 'vendor_upload_photo', true);
		// 表示する写真の選別
		$photo = '';
		if( $photo_upload ) :
			$photo = $photo_upload;
		elseif( preg_match('/選考用写真/', $photo_iclusive) ) :
			$photo = $photo_selection;
		elseif( preg_match('/アップロード/', $photo_iclusive) ) :
			$photo = $photo_entry;
		endif;
		if( empty($photo) ) {
			echo '<span class="value">'.esc_html(get_post_meta($post_id, $flyerphoto_key.'_inclusiv', true)).'</span>';
		} else {
			echo '<img src="'.esc_attr($photo).'" style="max-width:70mm; max-height:70mm; " />';
		}
	?></td>
	</tr>
	</table>
</td></tr>
<tr><th>シ<br>ェ<br>ア<br>出<br>店</th><td>
	<table class="child-table">
	<tr>
	<td class="share value"><?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'share', true)); ?></td>
	</tr>
	</table>
</td></tr>
</tbody>
</table>
</div>
</section>
<br>
<?php
			// ＊＊＊＊ ループ処理ここまで ＊＊＊＊
		endwhile;

		echo '</div><!-- #ptinting-only -->'.PHP_EOL;
	endif;
	wp_reset_postdata();

endif;
?>
