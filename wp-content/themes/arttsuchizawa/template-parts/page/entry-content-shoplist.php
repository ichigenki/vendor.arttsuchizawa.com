<?php
/**
 * The template for displaying "shoplist" content in page.php
 */

global $aVendorsData;
global $aPrefixData;
global $aPrefixData;
global $aItemPriceData;

$submit_button = '<button type="submit" name="set-config" id="set-config" class="submit" value="update-shoplist">出店者リストを更新</button>';

// 【関数】出店リストテーブルのコンテンツ書き出し
if( !function_exists('tacfv_get_shoplist_table_content') ) {
	function tacfv_get_shoplist_table_content($args=array()) {
		global $aVendorsData;
		global $aPrefixData;
		global $aPrefixData;

		$the_query = new WP_Query($args);
		if( $the_query->have_posts() ) :
			while( $the_query->have_posts() ) :
				$the_query->the_post();
				$post_id = get_the_ID();

				echo '<tr>'.PHP_EOL;
				foreach( $aVendorsData as $key=>$arr ) :
					if( tacfv_is_show_column_shoplist($key, $arr) ) :
						if( !preg_match('/head-/',$key) ) :
							$value = null;
							$td_class = array(); // <td>にclass属性を設定
							if( !empty($arr['type']) ) :
								$td_class[] = $arr['type'];
							endif;

							if( 'tax' === $arr['db'] ) :
								// タクソノミー
								$taxonomy = $aPrefixData['tax'].$key;
								$terms = get_the_terms($post_id, $taxonomy);
								$aTermName = array();
								$value = '-';
								if( !empty($terms) && is_array($terms) ) :
									foreach( $terms as $term ) :
										$aTermName[] = $term->name;
									endforeach;
									$value = !empty($aTermName)? implode('、', $aTermName): '-';
								endif;

							elseif( 'meta' === $arr['db'] ) :
								// ポストメタ
								$meta_key = !empty($arr['key'])? $arr['key'].$key: $aPrefixData['meta']['entry'].$key;
								$post_meta = get_post_meta($post_id, $meta_key, true);
								$value = $post_meta;
								if( !empty($arr['type']) && $post_meta ) :
									if( 'date' === $arr['type'] ) :
										$value = date_i18n('Y年n月j日', strtotime(esc_attr($post_meta)));
									elseif( 'image' === $arr['type'] ) :
										$value = '<a href="'.esc_attr($post_meta).'" target="_blank"><img src="'.esc_attr($post_meta).'" class="thumbnail" /></a>';
									elseif( 'file' === $arr['type'] ) :
										$value = '<a href="'.esc_attr($post_meta).'" target="_blank">開く</a>';
									elseif( 'url' === $arr['type'] ) :
										$value = '<a href="'.esc_attr($post_meta).'" target="_blank">'.esc_attr($post_meta).'</a>';
									elseif( 'textarea' === $arr['type'] ) :
										$value = nl2br(esc_html($post_meta));
									endif;
								endif;

							elseif( 'date' === $arr['db'] ) :
								// 投稿日時
								$value = esc_html(get_post($post_id)->post_date);
							endif;

							if( !empty($arr['type']) && 'price' === $arr['type'] ) :
								// 金額にカンマをつける
								$value = number_format_i18n((int)$value);
							endif;

							echo '<td class="'.esc_attr(implode(' ', $td_class)).'">'.$value.'</td>'.PHP_EOL;
						endif; // if( !preg_match('/head-/',$key) )
					endif;
				endforeach;
				echo '</tr>'.PHP_EOL;

			endwhile;
		endif;
		wp_reset_postdata();
	}
}

// 【関数】出店リストのカラム表示条件
if( !function_exists('tacfv_is_show_column_shoplist') ) {
	function tacfv_is_show_column_shoplist($key='', $arr=array()) {
		if( !empty($_POST) ) {
			if( !empty($key) && !empty($_POST['show-items']) && in_array($key, $_POST['show-items']) ) {
				return true;
			}
		}
		elseif( !empty($arr['default']) ) {
			return true;
		}
	}
}
?>
<div class="entry-content">

<?php the_content(); ?>

<?php
if( !post_password_required() ) {
	// ******** 設定ボックス（ここから） ********
?>
<div id="table-config" class="table-config">
<div class="openclose-button">
<button id="show-items-btn" class="simple-btn open-btn">表示項目</button >
<button id="sort-items-btn" class="simple-btn open-btn">ソート</button >
<button id="filter-items-btn" class="simple-btn open-btn">フィルター</button >
</div>
<div class="sections">
<form action="" method="post" accept-charset="utf-8">

<section id="show-items-section" class="show-section section">
<h2 class="items-title config-title"><label for="all_check"><input type="checkbox" id="all_check" class="all_check checkbox" />表示項目</label></h2>
<table id="items-table" class="config-table">
<tbody>
<?php
	// **** 表示項目の設定****
	$i = 1;
	foreach( $aVendorsData as $key=>$arr ) :
		if( preg_match('/head-/',$key) ) {
			echo 1 < $i? '</td></tr>'.PHP_EOL: '';
			echo '<tr><th><span class="heading">'.$arr['label'].'：</span></th><td>';
		} else {
			echo '<label for="show-items-'.$key.'"><input';
			echo ' type="checkbox"';
			echo ' name="show-items[]"';
			echo ' id="show-items-'.$key.'"';
			echo ' class="checkbox"';
			echo ' value="'.$key.'"';
			if( tacfv_is_show_column_shoplist($key, $arr) ) {
				echo ' checked="checked"';
			}
			echo ' />'.$arr['label'].'</label>';
		}
		$i++;
	endforeach;
?>
</td></tr>
</tbody>
</table>
<div class="submit-wrapper"><?php echo $submit_button; // 更新ボタン ?></div>
</section>

<section id="sort-items-section" class="sort-section section">
<h2 class="sort-title config-title">ソート</h2>
<table id="sort-table" class="config-table">
<tbody>
<?php
	// **** ソートの設定 ****
	$i = 1;
	foreach( $aVendorsData as $key=>$arr ) :
		if( preg_match('/head-/',$key) ) {
			echo 1 < $i? '</td></tr>'.PHP_EOL: '';
			echo '<tr><th><span class="heading">'.$arr['label'].'：</span></th><td>';
		} elseif( !empty($arr['sort']) ) {
			echo '<label for="sort-items-'.$key.'"><input';
			echo ' type="radio"';
			echo ' name="sort-items"';
			echo ' id="sort-items-'.$key.'"';
			echo ' class="radio"';
			echo ' value="'.$key.'"';
			if( !empty($_POST['sort-items']) ) {
				echo $_POST['sort-items']===$key? ' checked="checked"': '';
			}
			else {
				echo 'number'===$key? ' checked="checked"': '';
			}
			echo ' />'.$arr['label'].'</label>';
		}
		$i++;
	endforeach;
?>
</td></tr>
</tbody>
</table>
<div class="submit-wrapper"><?php echo $submit_button; // 更新ボタン ?></div>
</section>

<section id="filter-items-section" class="filter-section section">
<h2 class="filter-title config-title">フィルター</h2>
<table id="filter-table" class="config-table">
<tbody>
<?php
	// **** フィルタの設定 ****
	$i = 1;
	foreach( $aVendorsData as $key=>$arr ) :
		if( !empty($arr['filter']) ) {
			echo 1<$i? '</td></tr>'.PHP_EOL: '';
			echo '<tr><th><span class="heading">'.$arr['label'].'：</span></th><td>';
			if( 'tax' === $arr['db'] ) {
				$taxonomy = $aPrefixData['tax'].$key;
				$terms = get_terms($taxonomy);
				if( $terms && is_array($terms) ) {
					foreach( $terms as $term ) :
						$value = $arr['db'].'.'.$key.'.'.$term->name;
						echo '<label for="filter-items-'.$term->slug.'"><input';
						echo ' type="radio"';
						echo ' name="filter-items"';
						echo ' id="filter-items-'.$term->slug.'"';
						echo ' class="radio"';
						echo ' value="'.$value.'"';
						echo !empty($_POST['filter-items'])&&$value===$_POST['filter-items']? 'checked="checked"': '';
						echo ' />'.$term->name.'</label>';
						$i++;
					endforeach;
				}
			}
			elseif( 'meta' === $arr['db'] ) {
				$option = !empty($arr['option'])? $arr['option']: null;
				if( is_array($option) ) {
					foreach( $option as $item ) :
						$item_suffix = 'location'===$key&&!empty($aItemPriceData['location'][$item])? '（'.number_format($aItemPriceData['location'][$item]).'円）': '';
						$value = $arr['db'].'.'.$key.'.'.$item.$item_suffix.(!empty($arr['key'])? '.'.$arr['key']: '');
						echo '<label for="filter-items-'.$key.'-'.$i.'"><input';
						echo ' type="radio"';
						echo ' name="filter-items"';
						echo ' id="filter-items-'.$key.'-'.$i.'"';
						echo ' class="radio"';
						echo ' value="'.$value.'"';
						echo !empty($_POST['filter-items'])&&$value===$_POST['filter-items']? 'checked="checked"': '';
						echo ' />'.$item.'</label>';
						$i++;
					endforeach;
				}
			}
		}
		$i++;
	endforeach;
?>
</td></tr>
</tbody>
</table>
<div class="submit-wrapper"><?php echo $submit_button; // 更新ボタン ?></div>
</section>

</form>
</div><!-- /.sections -->
</div><!-- /#table-config -->
<?php
// ******** 設定ボックス（ここまで）********
?>

<?php
// ******** 出店リスト（ここから） ********
//var_dump($_POST['sort-items']);
?>
<div class="wide-table-wrapper">
<table class="shoplist-table">
<thead>
<tr>
<?php
	foreach( $aVendorsData as $key=>$arr ) :
		if( tacfv_is_show_column_shoplist($key, $arr) ) :
			$th_class = array(); // <th>にclass属性を設定
			if( !empty($arr['type']) ) :
				$th_class[] = $arr['type'];
			endif;
			if( !preg_match('/head-/',$key) ) :
				echo '<th class="'.implode(' ', $th_class).'">'.(!empty($arr['label'])? $arr['label']: '').'</th>'.PHP_EOL;
			endif;
		endif;
	endforeach;
?>
</tr>
</thead>
<tbody>
<?php
	$args = array(
		'post_type' => 'vendors',
		'post_status' => 'publish',
		'meta_key' => $aPrefixData['meta']['manage'].'number',
		'meta_type' => 'NUMERIC', // ‘NUMERIC’,‘BINARY’,‘CHAR’,‘DATE’,‘DATETIME’,‘DECIMAL’,‘SIGNED’,‘TIME’,‘UNSIGNED’
		'orderby' => 'meta_value',
		'order' => 'ASC',
		'posts_per_page' => -1,
	);

	// **** フィルタ設定による$args（表示内容）の変更 ****
	if( !empty($_POST['filter-items']) ) :
		$aPosted = explode('.', $_POST['filter-items']);
		if( 'tax' === $aPosted[0] ) :
			$taxonomy = $aPrefixData['tax'].$aPosted[1];
			$tarms = $aPosted[2];
			$args['tax_query'] = array(
				array(
					'taxonomy' => $taxonomy,
					'field' => 'name',
					'terms' => $tarms,
				),
			);
		elseif( 'meta' === $aPosted[0] ) :
			$args['meta_query'] = array(
				array(
					'key' => (!empty($aPosted[3])? $aPosted[3]: $aPrefixData['meta']['entry']).$aPosted[1],
					'value' => $aPosted[2],
					'compare' => 'LIKE',
				),
			);
		endif;
	endif;

	// **** ソート設定による$args（表示順）の変更 ****
	if( !empty($_POST['sort-items']) ) :
		$data_arr = $aVendorsData[$_POST['sort-items']];
		if( 'tax' === $data_arr['db'] ) :
			// ソート設定がタクソノミーの場合には最初にタームで分類する
			$taxonomy = $aPrefixData['tax'].$_POST['sort-items'];
			$aTerms = get_terms($taxonomy);
			foreach( $aTerms as $oTerm ) :
				$args['tax_query'] = array(
					array(
						'taxonomy' => $taxonomy,
						'field' => 'name',
						'terms' => $oTerm->name,
					),
				);
				tacfv_get_shoplist_table_content($args); // テーブルコンテンツの書き出しを実行
			endforeach;
		elseif( 'meta' === $data_arr['db'] ) :
			// ソート設定がポストメタの場合にはそのままリスト表示
			$args['meta_key'] = (!empty($data_arr['key'])? $data_arr['key']: $aPrefixData['meta']['entry']).$_POST['sort-items'];
			$args['meta_type'] = !empty($data_arr['type'])&&('number'===$data_arr['type']||'price'===$data_arr['type'])? 'NUMERIC': 'CHAR';
			tacfv_get_shoplist_table_content($args); // テーブルコンテンツの書き出しを実行
		endif;
	else :
//var_dump($args['meta_key']);

		// **** ソート設定のない場合 ****
		tacfv_get_shoplist_table_content($args); // テーブルコンテンツの書き出しを実行
	endif;
?>
</tbody>
</table>
</div><!-- /.wide-table-wrapper -->
<?php
	// ******** 出店リスト（ここまで） ********
}// if( !post_password_required() )
?>

</div><!-- .entry-content -->

<script type="text/javascript">
jQuery(document).ready(function($){
  // 設定ボックスの開閉
  $('button#show-items-btn').click(function() {
    $('#show-items-section').toggle('fast');
    $(this).toggleClass('open-btn').toggleClass('close-btn');
  });
  $('button#sort-items-btn').click(function() {
    $('#sort-items-section').toggle('fast');
    $(this).toggleClass('open-btn').toggleClass('close-btn');
  });
  $('button#filter-items-btn').click(function() {
    $('#filter-items-section').toggle('fast');
    $(this).toggleClass('open-btn').toggleClass('close-btn');
  });
  // 「表示項目」のチェックボックスを全選択／全選択解除
  $('input#all_check').click(function() {
    $('#show-items-section input[name="show-items[]"]').prop('checked',this.checked);
  });
  $('#show-items-section input[name="show-items[]"]').on('click',function(){
    if($('#items-table :checked').length === $('#items-table input[name="show-items[]"]').length ) {
      $('#all_check').prop('checked',true);
    } else {
      $('#all_check').prop('checked',false);
    }
  });
  // ラジオボタンの選択解除
  var radio_id = $('input[name="filter-items"]:checked').attr('id');
  $('input[type="radio"]').click(function() {
    if($(this).attr('id') == radio_id) {
      $(this).prop('checked', false);
      radio_id = null;
    } else {
      radio_id = $(this).attr('id');
    }
  });
});
</script>

<?php
/*
// デバッグ
echo '<hr>$_POST["sort-items"]: ';
//var_dump($_POST['sort-items']);
*/
