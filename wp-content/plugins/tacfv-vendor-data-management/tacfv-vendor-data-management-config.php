sftp<?php
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



// 【オプション】にデータを追加・更新・削除する関数
if( !function_exists('tvdmc_get_update_option') ) {
	function tvdmc_get_update_option($form=null, $data=null) {
		global $tvdmc_pluginKey;
		$aOptionData = get_option($tvdmc_pluginKey);

		if( isset($form) && isset($data) ) {
			if( isset($aOptionData[$form]) && $data == $aOptionData[$form] ) :
				return true;
			else :
				$aOptionData[$form] = $data;
				if( update_option($tvdmc_pluginKey, $aOptionData) ) :
					return $aOptionData;
				else :
					return false;
				endif;
			endif;
		}
		return false;
	}
}



// 【HTML出力】プラグインページ（設定編集画面）を作成
function tvdmc_options() {

	// ユーザーが必要な権限を持つか確認する必要がある
	if ( !current_user_can('manage_options') ) {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

	// グローバル変数
	global $tvdmc_pageTitle;
	global $tvdmc_pluginKey;
	// その他の変数
	$aOptionData = get_option($tvdmc_pluginKey);
	$aInputNames = array('select','ids','content','position','start','end');



	// ******** 【設定編集画面（HTML）】ここから ********

	// 設定表書き出しのための配列を定義
	$aAreaTableData = array();
	$aOtherTableData = array();

	// ポストされたデータを配列に保存
	$aPostedData = array();
	foreach( $aInputNames as $value ) :
		$aPostedData[$value] = ( isset($_POST[$value]) )? $_POST[$value]: '';
	endforeach;
	$error_messages = array();
	if( isset($_POST['submit-save-data']) ) :
		if( empty($aPostedData['select']) )
			$error_messages['select'] = '・「フォーム指定」を選択してください。';
		if( empty($aPostedData['position']) )
			$error_messages['position'] = '・「自動返信メール本文」を選択してください。';
	endif;
	$button = '<form action="" method="post" class="result" accept-charset="utf-8">'.PHP_EOL;
	$button .= '<p><button type="submit" name="submit-result-back" class="submit button button-primary" value="1">戻る</button></p>'.PHP_EOL;
	$button .= '</form>';

?>
<style>
section.section {margin: 0 0 2em; }
table {margin: 0 0 0.5em; }
table th, table td {padding: 0.125em 0.25em; }
table td.text-td {font-size: 14px; padding-left: 1em; }
table tbody th {text-align: left; }
table tfoot td.submit-btn-wrapper {padding: 0.275em 0 0; }
input.price {width: 8em; }
input.area-name {width: 200px; }
</style>
<div class="wrap">
<h1 style="margin-bottom: 1em; "><?php echo $tvdmc_pageTitle; ?></h1>
<?php
	// 結果を表示
	if( isset($_POST['submit-save-data']) && empty($error_messages) ) :

		// ******** 【ポストされた ＆ エラーがない場合】 ********
		if( tvdmc_get_update_option($form='', $aPostedData) ) :
			$aOptionData = get_option($tvdmc_pluginKey);
			echo '<div class="updated"><p>設定内容が保存されました。</p>'.$button.'</div>'.PHP_EOL;
		else :
			echo '<div class="error"><p>設定内容の保存に失敗しました。</p>'.$button.'</div>'.PHP_EOL;
		endif;

	else :

		// ******** 【エラーがあった場合にメッセージを表示】 ********
		if( !empty($error_messages) ) :
			echo '<div class="error"><p>設定を保存できませんでした。<br />';
			echo implode('<br />', $error_messages);
			echo '</p></div>'.PHP_EOL;
		endif;

		// ******** 【通常表示】 ********

		$aSavedData = array();
		foreach( $aInputNames as $input_name ) :
			if( isset($_POST['submit-save-data']) ) :
				$aSavedData[$input_name] = ( isset($_POST[$input_name]) )? $_POST[$input_name]: '';
			else :
				$aSavedData[$input_name] = ( !empty($aOptionData[$input_name]) )? $aOptionData[$input_name]: '';
			endif;
		endforeach;

?>

<hr>

<section class="section">
<form action="" method="post" class="save-data-form" accept-charset="utf-8">
<h2>エリアの数</h2>
<table>
<tbody>
<tr>
<td><input type="number" name="area-number" id="area-number" class="text number" step="1" min="1" max="32" value="" />カ所</td>
</tr>
</tbody>
<tfoot>
<tr><td class="submit-btn-wrapper"><button type="submit" name="submit-save-data" class="submit button button-primary" value="">保存する</button></td></tr>
</tfoot>
</table>
</form>
</section>

<hr>

<section class="section">
<form action="" method="post" class="save-data-form" accept-charset="utf-8">
<h2>エリア設定</h2>
<table>
<thead>
<tr>
<th class="area-id"></th><th class="area-label">名称</th><th class="area-price">金額</th>
</tr>
</thead>
<tbody>
<?php
		for($i=1; $i<=11; $i++) :
			$key = 'area'.sprintf('%02d', $i);
?>
<tr>
<td><?php echo $key; ?></td><td class="input-td"><input type="text" name="<?php echo $key; ?>-label" id="<?php echo $key; ?>-label" class="text area-name"  value="" /></td><td class="input-td"><input type="number" name="<?php echo $key; ?>-price" id="<?php echo $key; ?>-price" class="text number price" step="1000" min="0" max="100000" value="" /></td>
</tr>
<?php
		endfor;
?>
</tbody>
<tfoot>
<tr><td class="submit-btn-wrapper" colspan="3"><button type="submit" name="submit-save-data" class="submit button button-primary" value="">保存する</button></td></tr>
</tfoot>
</table>
</form>
</section>

<hr>

<section class="section">
<form action="" method="post" class="save-data-form" accept-charset="utf-8">
<h2>追加料金設定</h2>
<table>
<tbody>
<tr>
<td class="text-td">屋内追加料金：</td><td><input type="number" name="indoor-price" id="indoor-price" class="text number price" step="1000" min="0" max="100000" value="" /></td>
</tr>
<tr>
<td class="text-td">駐車場追加料金：</td><td><input type="number" name="parking-price" id="parking-price" class="text number price" step="1000" min="0" max="100000" value="" /></td>
</tr>
</tbody>
<tfoot>
<tr><td class="submit-btn-wrapper" colspan="3"><button type="submit" name="submit-save-data" class="submit button button-primary" value="">保存する</button></td></tr>
</tfoot>
</table>
</form>
</section>

<?php
	endif;
?>
</div>
<?php
/*
// **** デバッグ（ここから） ****
// var_dump()で書き出す値を整形
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);
ini_set('xdebug.var_display_max_depth', -1);
// var_dump()で書き出し
echo '<div style="position: relative; display: inline-block; background-color: white; margin: 60px 0 20px; padding: 10px 1em 0; z-index: 999; ">'.PHP_EOL;
echo '$_SESSION:'.PHP_EOL;
var_dump($_SESSION);
echo '<hr />';
echo '$_POST: '.PHP_EOL;
var_dump($_POST);
echo '<hr />';
echo '$aOptionData: '.PHP_EOL;
var_dump($aOptionData);
echo '</div>'.PHP_EOL;
// **** デバッグ（ここまで） ****
*/
 }
