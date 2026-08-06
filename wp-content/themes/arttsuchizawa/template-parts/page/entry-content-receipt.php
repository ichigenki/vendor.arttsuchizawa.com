<?php
/**
 * The template for displaying "selection" content in page.php
 */

global $aPrefixData;

$date = get_post_meta(CONFIG_ID, 'config_receipt', true);
$issuer = get_post_meta(CONFIG_ID, 'config_receipt_issuer', true);
$address = get_post_meta(CONFIG_ID, 'config_receipt_issuer_address', true);
//var_dump($_POST);

// 【ショートコード】「領収証」ページの印刷見本を書き出す
if( !function_exists('shortcode_tacfv_blankreceipt') ) {
	add_shortcode( 'tacfv_blankreceipt', 'shortcode_tacfv_blankreceipt' );
	function shortcode_tacfv_blankreceipt() {
		$issuer = get_post_meta(CONFIG_ID, 'config_receipt_issuer', true);
		$address = get_post_meta(CONFIG_ID, 'config_receipt_issuer_address', true);
		$output = '';
		ob_start();
?>
<table class="receipt-frame-table" style="width: 560px; ">
<tr>
<td><div class="wrapper">
<div class="header"><span class="title">領収証</span><span class="date">年　　月　　日</span></div>
<div class="recipient"><span class="name">　　　　　　　　　　　　</span><span class="title">御中</span></div>
<div class="price"><span class="title">金額：</span><span class="prefix">¥</span><span class="amount">　　　　　　</span><span class="sufix">-</span></div>
<div class="remarks">但：<?php bloginfo('site_name'); ?>の出店料金として、上記正に領収いたしました。</div>
<div class="issuer"><span class="name"><?php echo $issuer; ?></span><span class="address"><?php echo nl2br($address); ?></span></div>
</div></td>
</tr>
</table>
<?php
		$output = ob_get_clean();
		return $output;
	}
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
		'tax_query' => array(
			array(
				'taxonomy' => 'vendor-results',
				'field' => 'slug',
				'terms' => 'selected',
			),
		),
		'meta_query' => array(
			array(
				'key' => 'vendor_manage_pass_receipt',
				'value' => '必要',
				'compare' => '=',
			),
		),
		'meta_key' => 'vendor_entry_number',
		'orderby' => 'meta_value_num date',
		'order' => 'ASC',
		'posts_per_page' => -1
	);
	$the_query = new WP_Query($args);
	if( $the_query->have_posts() ) :
		echo '<div id="printing-only" class="ptinting-only">'.PHP_EOL;

		$i = 0;
		while( $the_query->have_posts() ) :
			$the_query->the_post();
			$post_id = get_the_ID();
			$price = get_post_meta($post_id, $aPrefixData['meta']['pass'].'price', true);

			// ＊＊＊＊ ループ処理ここから ＊＊＊＊
			if( 0 === $i % 6 ) :
				echo '<table class="receipt-frame-table page-break">'.PHP_EOL;
				echo '<tbody>'.PHP_EOL;
				echo '<tr>'.PHP_EOL;
			endif;
?>
<td><div class="wrapper">
<div class="header"><span class="title">領収証</span><span class="date"><?php echo esc_html(date_i18n('Y年 n月 j日', $date)); ?></span></div>
<div class="recipient"><span class="name"><?php echo esc_html(get_post_meta($post_id, $aPrefixData['meta']['entry'].'shop', true)); ?></span><span class="title">御中</span></div>
<div class="price"><span class="title">金額：</span><span class="prefix">¥</span><span class="amount"><?php echo $price? number_format(esc_html($price)): '　　　　'; ?></span><span class="sufix">-</span></div>
<div class="remarks">但：<?php bloginfo('site_name'); ?>の出店料金として、上記正に領収いたしました。</div>
<div class="issuer"><span class="name"><?php echo esc_html($issuer); ?></span><span class="address"><?php echo nl2br(esc_html($address)); ?></span></div>
</div></td>
<?php
			if( 5 === $i % 6 ) :
				echo '</tr>'.PHP_EOL;
				echo '</tbody>'.PHP_EOL;
				echo '</table>'.PHP_EOL;
			elseif( 1 === $i % 2 ) :
				echo '</tr>'.PHP_EOL;
				echo '<tr>'.PHP_EOL;
			endif;
			// ＊＊＊＊ ループ処理ここまで ＊＊＊＊

			$i++;
		endwhile;

		// 白紙領収証
		echo '<table class="receipt-frame-table">'.PHP_EOL;
		echo '<tbody>'.PHP_EOL;
		echo '<tr>'.PHP_EOL;
		for( $i = 0; $i < 6; $i++ ) :
?>
<td><div class="wrapper">
<div class="header"><span class="title">領収証</span><span class="date">年　　月　　日</span></div>
<div class="recipient"><span class="name">　　　　　　　　　　　　</span><span class="title">御中</span></div>
<div class="price"><span class="title">金額：</span><span class="prefix">¥</span><span class="amount">　　　　　　</span><span class="sufix">-</span></div>
<div class="remarks">但：<?php bloginfo('site_name'); ?>の出店料金として、上記正に領収いたしました。</div>
<div class="issuer"><span class="name"><?php echo esc_html($issuer); ?></span><span class="address"><?php echo nl2br(esc_html($address)); ?></span></div>
</div></td>
<?php
			if( 5 !== $i && 1 === $i % 2 ) :
				echo '</tr><tr>'.PHP_EOL;
			endif;
		endfor;
		echo '</tr>'.PHP_EOL;
		echo '</tbody>'.PHP_EOL;
		echo '</table>'.PHP_EOL;

		echo '</div><!-- #ptinting-only -->'.PHP_EOL;

	endif;
	wp_reset_postdata();

endif;
?>
