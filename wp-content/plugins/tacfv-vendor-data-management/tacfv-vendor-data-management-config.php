<?php
/*
Plugin Name: TACF Vendor Data Management Configuration
Plugin URI:
Description: 出店者の詳細情報管理のためのデータ（出店エリアや出店料金など）を設定します。
Version: 1.0.0
Plugin Name: TACF
Author URI: https://arttsuchizawa.com/
*/

// グローバル変数
$tvdmc_pageTitle   = '出店情報管理のための設定（エリアと料金）';
$tvdmc_menuTitle   = '出店管理元データ';
$tvdmc_pluginKey   = 'tacfv_vendor_data_management_config';
$tvdmc_pluginName  = 'tacfv-vendor-data-management-init';
$tvdmc_optionData  = get_option($tvdmc_pluginKey);

// 管理メニューに追加する
function tvdmc_admin_menu() {
	global $tvdmc_pageTitle;
	global $tvdmc_menuTitle;
	global $tvdmc_pluginName;
	// 「設定」下に新しいサブメニューを追加
	add_options_page($tvdmc_pageTitle, $tvdmc_menuTitle, 'manage_options', $tvdmc_pluginName, 'tvdmc_options' );
}
add_action('admin_menu', 'tvdmc_admin_menu');

// プラグイン画面に表示されるリンクのリストに追加 (有効化／無効化リンクのとなり)
function tvdmc_add_action_links($actions=array()) {
	global $tvdmc_pluginName;
	 $mylinks = array(
			'<a href="'.admin_url('options-general.php?page='.$tvdmc_pluginName).'">設定</a>',
	 );
	 $actions = array_merge($actions, $mylinks);
	 return $actions;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'tvdmc_add_action_links');

// jsonファイルを読み込む
$json_data = file_get_contents(plugin_dir_path(__FILE__) . 'tacfv-data.json');
$json_decode = json_decode($json_data, true);
$aAreaPriceData = $json_decode['location'] ? $json_decode['location'] : array();
$aIndoorPriceData = $json_decode['indoor'] ? $json_decode['indoor'] : array();
$aParkingPriceData = $json_decode['parking'] ? $json_decode['parking'] : array();



// *********************************************
// 【HTML出力】プラグインページ（設定編集画面）を作成
// *********************************************
function tvdmc_options() {

	// ユーザーが必要な権限を持つか確認する必要がある
	if (!current_user_can('manage_options')) {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

	// グローバル変数
	global $tvdmc_pageTitle;
	global $tvdmc_pluginKey;
	// その他の変数
	$aOptionData = get_option($tvdmc_pluginKey) ? get_option($tvdmc_pluginKey) : array(
		'location' => array(),
		'indoor' => array(),
		'parking' => array(),

	);
	$aPOST = array();
	$update_messages = array();
	$error_messages = array();

	// ポストされたデータを配列に保存
	if (isset($_POST)) :
		foreach( $_POST as $key=>$val ) :
			$val = mb_ereg_replace('^[ 　]+|[ 　]+$', '', $val); // 前後の空白を削除
			$aPOST[$key] = sanitize_text_field($val);
		endforeach;
		$aSavedData = array();

		if (isset($_POST['submit-save-data'])) :
			// 「エリア設定」がポストされた時
			$i = 1;
			while (array_key_exists("area{$i}-label", $aPOST)) {
				$label   = $aPOST["area{$i}-label"];
				$price = $aPOST["area{$i}-price"];
				if (!empty($label)) {
					$aSavedData[$label] = $price;
				}
				$i++;
			}
			$aOptionData['location'] = $aSavedData;
			if (update_option($tvdmc_pluginKey, $aOptionData)) :
				// 保存成功
				$update_messages[] = '<strong>エリア設定</strong>が保存されました。';
				$update_messages[] = '<span class="error" style="color: #c00;">※必ず、下のリンクから「<strong>当選店データ ⇒ 出店エリア</strong>」の「<strong>選択肢</strong>」欄が正しい値になっていることを確認して「<strong>変更内容を保存</strong>」ボタンで保存してください。<br>　<a href="/wp-admin/post.php?post=254&action=edit">管理画面左メニュー「ACF ⇒ フィールドグループ ⇒ 出店管理（事務局） 」</a></span>';
			else :
				// 保存失敗
				$error_messages[] = '<strong>エリア設定</strong>を保存できませんでした。';
		 endif;
		elseif (isset($_POST['submit-save-data2'])) :
			// 「追加料金設定」がポストされた時
			$aOptionData['indoor'] = array(
				'label' => $aPOST['indoor-label'],
				'price' => $aPOST['indoor-price'],
			);
			$aOptionData['parking'] = array(
				'label' => $aPOST['parking-label'],
				'price' => $aPOST['parking-price'],
			);
			if (update_option($tvdmc_pluginKey, $aOptionData)) :
				// 保存成功
				$update_messages[] = '<strong>追加料金設定</strong>が保存されました。';
			else :
				// 保存失敗
				$error_messages[] = '<strong>追加料金設定</strong>を保存できませんでした。';
		 endif;
		endif;

		// ポストされたデータ（オプションデータ）をjsonファイルに書き出す
		$json_data = json_encode($aOptionData);
		file_put_contents(plugin_dir_path(__FILE__) . 'tacfv-data.json', $json_data);
	endif;



	// ******** 【設定編集画面（HTML）】ここから ********
?>
<style>
section.section {margin: 0 0 2em; }
table {margin: 0 0 0.5em; }
table th, table td {padding: 0.125em 0.25em; }
table td.text-td {font-size: 14px; padding-left: 1em; }
table thead th {font-size: 14px; font-weight: normal; text-align: left; }
table tfoot td.submit-btn-wrapper {padding-top: 0.375em; }
input.price {width: 8em; }
input.area-name {width: 300px; }
</style>
<div class="wrap">
<h1 style="margin-bottom: 1em; "><?php echo $tvdmc_pageTitle; ?></h1>
<?php
	if (isset($_POST)) :
		// ポストされた時に結果を表示
		if (!empty($update_messages)) :
			// 【更新成功】更新メッセージを表示
			echo '<div class="updated"><p>';
			echo implode('<br />', $update_messages);
			echo '</p></div>'.PHP_EOL;
		elseif (!empty($error_messages)) :
			// 【エラー】エラーメッセージを表示
			echo '<div class="error"><p>';
			echo implode('<br />', $error_messages);
			echo '</p></div>'.PHP_EOL;
		endif;
	endif;
?>

<hr>

<section class="section">
<form action="" method="post" class="save-data-form" accept-charset="utf-8">
<h2>エリア設定</h2>
<table>
<thead>
<tr>
<!-- <th class="area-id"></th> -->
<th class="area-label">名称</th><th class="area-price">金額</th>
</tr>
</thead>
<tbody>
<?php
		$i = 0;
		foreach ($aOptionData['location'] as $key=>$val) :
			$i++;
?>
<tr>
<!-- <td><?php echo $key; ?></td> -->
<td class="input-td"><input type="text" name="area<?php echo $i; ?>-label" id="area<?php echo $i; ?>-label" class="text area-name"  value="<?PHP echo $key; ?>" /></td><td class="input-td"><input type="number" name="area<?php echo $i; ?>-price" id="area<?php echo $i; ?>-price" class="text number price" step="1000" min="0" max="100000" value="<?PHP echo $val; ?>" /></td>
</tr>
<?php
		endforeach;
		if (isset($_POST['submit-add-item'])) :
			// 追加エリアの入力欄を表示する
?>
<td class="input-td"><input type="text" name="area<?php echo $i+1; ?>-label" id="area<?php echo $i+1; ?>-label" class="text area-name"  value="" /></td><td class="input-td"><input type="number" name="area<?php echo $i+1; ?>-price" id="area<?php echo $i+1; ?>-price" class="text number price" step="1000" min="0" max="100000" value="" /></td>
<?PHP
		endif;
?>
</tbody>
<tfoot>
<tr><td class="submit-btn-wrapper" colspan="3"><button type="submit" name="submit-save-data" class="submit button button-primary" value="">保存する</button>
<?PHP if (!isset($_POST['submit-add-item'])) :	?>
 <button type="submit" name="submit-add-item" class="submit button button-secondary" value="">エリア入力欄を追加</button>
<?PHP endif; ?>
<p class="note" style="margin-top: 0.5em;">※記入された内容に変更がない場合には保存できません。<br>※欄を削除する場合には、「名称」欄を空にして保存してください。</p>
</td></tr>
</tfoot>
</table>
</form>
</section>

<hr>

<section class="section">
<form action="" method="post" class="save-data-form" accept-charset="utf-8">
<h2>追加料金設定</h2>
<table>
<thead>
<tr>
<th class="area-label">名称</th><th class="area-price">金額</th>
</tr>
</thead>
<tbody>
<tr>
<td><input type="text" name="indoor-label" id="indoor-label" class="text area-name"  value="<?PHP echo $aOptionData['indoor']['label']; ?>" /></td><td><input type="number" name="indoor-price" id="indoor-price" class="text number price" step="1000" min="0" max="100000" value="<?PHP echo $aOptionData['indoor']['price']; ?>" /></td>
</tr>
<tr>
<td><input type="text" name="parking-label" id="parking-label" class="text area-name"  value="<?PHP echo $aOptionData['parking']['label']; ?>" /></td><td><input type="number" name="parking-price" id="parking-price" class="text number price" step="1000" min="0" max="100000" value="<?PHP echo $aOptionData['parking']['price']; ?>" /></td>
</tr>
</tbody>
<tfoot>
<tr><td class="submit-btn-wrapper" colspan="3"><button type="submit" name="submit-save-data2" class="submit button button-primary" value="">保存する</button><p class="note" style="margin-top: 0.5em;">※記入された内容に変更がない場合には保存できません。</p>
</td></tr>
</tfoot>
</table>
</form>
</section>

</div>
<?php
// ******** 【設定編集画面（HTML）】ここまで ********
}
