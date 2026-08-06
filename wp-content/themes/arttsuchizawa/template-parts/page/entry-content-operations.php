<?php
/**
 * The template for displaying "operations" content in page.php
 */

?>
<div class="entry-content">

<?php
if( !empty($_POST) ) {

	$year = !empty($_POST['the_year'])? $_POST['the_year']: '';
	if( empty($year) ) {
?>
<div class="warning-box">
<p class="error bold">「操作するエントリーの種類」欄に記入してください。</p>
</div>
<?php
		the_content();
/*
		echo str_replace(
			'<br><input type="text" name="the_year"',
			'<br><span class="error bold">下の欄に記入してください</span><br><input type="text" name="the_year"',
			get_the_content()
		);
*/
	}
	elseif( !empty($_POST['operations']) ) {
?>
<div class="warning-box">
<form action="" method="post" accept-charset="utf-8">
<input type="hidden" name="the_year" id="the_year" class="hidden" value="<?php echo $year; ?>">
<p class="error" style="font-size: 1.1rem;"><span class="bold">【注意！】</span>「<?php echo $year; ?>」の一括操作を本当に実行しますか？</p>
<p><button type="submit" name="submit" id="submit" class="button submit" value="1">実行する</button></p>
</form>
</div>
<?php
	}
	else {
		$args = array(
			'post_type' => 'vendors',
			'post_status' => 'publish',
			'orderby' => 'date',
			'meta_query' => array(
				array(
					'key' => 'vendor_entry_year',
					'value' => $year,
					'compare' => '=',
				),
			),
			'tax_query' => array(
				array(
					'taxonomy' => 'vendor-source',
					'field' => 'name',
					'terms' =>array('Web申込み','書類'),
					'operator' => 'IN',
				),
			),
			'orderby' => 'date',
			'order' => 'ASC',
			'posts_per_page' => -1
		);
		$the_query = new WP_Query($args);
		if( $the_query->have_posts() ) :
?>
<div class="executed-box">
<p class="message" style="font-weight:bold; ">一括変換が実行されました。</p>
</div>
<table class="">
<thead>
<tr><th class="number" style="text-align: right; padding-right: 1em; ">No.</th><th class="shop">出店名</th><th>地域</th></tr>
</thead>
<tbody>
<?php
			$i = 1;
			while( $the_query->have_posts() ) :
				$the_query->the_post();
				$post_id = get_the_ID();

				// 受付番号の付番
				update_post_meta($post_id, 'vendor_manage_number', $i);

				// 地域を住所から抽出してポストメタに保存
				$address = get_post_meta($post_id, 'vendor_entry_address', true);
				$comefrom = '';
				$array = array(
					'盛岡市','宮古市','大船渡市','花巻市','北上市','久慈市','遠野市','一関市','陸前高田市','釜石市','二戸市','八幡平市','奥州市','滝沢市','雫石町','葛巻町','岩手町','紫波町','矢巾町','西和賀町','金ケ崎町','平泉町','住田町','大槌町','山田町','岩泉町','田野畑村','普代村','軽米町','野田村','九戸村','洋野町','一戸町',
					'北海道','青森県','宮城県','秋田県','山形県','福島県','茨城県','栃木県','群馬県','埼玉県','千葉県','東京都','神奈川県','新潟県','富山県','石川県','福井県','山梨県','長野県','岐阜県','静岡県','愛知県','三重県','滋賀県','京都府','大阪府','兵庫県','奈良県','和歌山県','鳥取県','島根県','岡山県','広島県','山口県','徳島県','香川県','愛媛県','高知県','福岡県','佐賀県','長崎県','熊本県','大分県','宮崎県','鹿児島県','沖縄県'
				);
				foreach( $array as $value ) :
					if( preg_match('/'.$value.'/', $address) ) :
						$comefrom = $value;
					endif;
				endforeach;
				update_post_meta($post_id, 'vendor_manage_pass_comefrom', $comefrom);

				// テーブルに書き出し
				echo '<tr><td style="font-weight: bold; text-align: right; padding-right: 1em; ">';
				echo esc_html(get_post_meta($post_id, 'vendor_manage_number', true));
				echo '</td><td>';
				echo esc_html(get_post_meta($post_id, 'vendor_entry_shop', true));
				echo '</td><td>';
				echo esc_html(get_post_meta($post_id, 'vendor_manage_pass_comefrom', true));
				echo '</td></tr>'.PHP_EOL;

			$i++;
			endwhile;
?>
</tbody>
</table>
<?php
		else :
?>
<div class="warning-box">
<p class="error" style="color:#c30; font-weight:bold; ">実行するエントリーがありませんでした。</p>
</div>
<?php
		the_content();
		endif;
		wp_reset_postdata();
	}

} else {

	the_content();

}
?>

</div><!-- .entry-content -->

<?php
//var_dump($_POST);
