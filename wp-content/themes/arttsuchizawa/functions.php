<?php
// 公開画面側のCSSの読み込み.
add_action("wp_enqueue_scripts", "arttsuchizawa_thm_scripts", 10);
if (!function_exists("arttsuchizawa_thm_scripts")) {
	function arttsuchizawa_thm_scripts() {
		wp_enqueue_style(
			"parent-style",
			get_template_directory_uri() . "/style.css",
			[],
		);
		wp_enqueue_style(
			"arttsuchizawa-custom",
			get_stylesheet_directory_uri() . "/assets/css/custom.css",
			["twentyseventeen-block-style", "twentyseventeen-style"],
		);
		if (is_front_page()) {
			//wp_enqueue_style( 'custom-front', get_stylesheet_directory_uri().'/assets/css/custom-front.css', array('arttsuchizawa-custom') );
		} 
		elseif (is_page(["shoplist"])) {
			// 「出店者リスト」ページ（ワイドレイアウト）用スタイル
			wp_enqueue_style(
				"arttsuchizawa-page-wide",
				get_stylesheet_directory_uri() . "/assets/css/page-wide.css",
				["arttsuchizawa-custom"],
			);
		}
		// elseif( is_page('receipt') ) {
		// 	// 「領収証」ページ用スタイル
		// 	wp_enqueue_style( 'page-entry', get_stylesheet_directory_uri().'/assets/css/page-receipt.css', array('arttsuchizawa-custom') );
		// }
		elseif (is_page(["entry", "file", "flyer"])) {
			// 「出店申込み」ページ用スタイル
			wp_enqueue_style(
				"arttsuchizawa-page-external",
				get_stylesheet_directory_uri() . "/assets/css/page-external.css",
				["arttsuchizawa-custom"],
			);
		}
		wp_enqueue_script(
			"arttsuchizawa-jquery-custom",
			get_stylesheet_directory_uri() . "/assets/js/script.js",
			[],
			"1.0.0",
			true,
		);
	}
}
// add_action('wp_enqueue_scripts', function() {
// 	wp_enqueue_style('seoritsuhime-custom', get_stylesheet_directory_uri().'/assets/css/style.css', array('sydney-style-min-inline-css'));
// });
// 編集画面側のCSSの読み込み.
add_action("admin_enqueue_scripts", function () {
	wp_enqueue_style(
		"arttsuchizawa-editor-custom",
		get_stylesheet_directory_uri() . "/assets/css/editor.css",
		["ame-helper-style", "to.css"],
	);
	wp_enqueue_script(
		"arttsuchizawa-jquery-custom",
		get_stylesheet_directory_uri() . "/assets/js/admin.js",
		[],
		"1.0.0",
		true,
	);
});

// BEGIN CUSTOM FUNCTIONS

/************************************************
 * 独自の処理を必要に応じて書き足します
 */

/* ************************ */
/* 【管理画面】               */
/* 【グローバル変数】          */
/* 【関数】                  */
/* 【ショートコード】          */
/* 【その他】                */
/* ************************ */

// パスワード保護中の記事タイトルを変更（保護中：を削除）
add_filter(
	"protected_title_format",
	function ($prepend, $post) {
		return "%s";
	},
	10,
	2,
);

// パスワード保護ページの説明文を変更
add_filter("the_password_form", "tacf_hange_password_protected_page", 10, 2);
function tacf_hange_password_protected_page($output=null, $post=null) {
	//$description = 'This content is password-protected. '."\n".'Please enter the password below to view it.';
	$description =
		"このコンテンツはパスワードで保護されています。" .
		"\n" .
		"閲覧するには、以下の欄にパスワードを入力してください。";
	$placeholder = "";
	$button_label = "送信する";
	$content_start = is_page("shoplist") ? "" : "";
	$content_end = is_page("shoplist") ? "" : "";
	//var_dump($output);
	$output =
		$content_start .
		"<p>" .
		esc_html($description) .
		"</p>" .
		'<form action="' .
		esc_url(site_url("wp-login.php?action=postpass", "login_post")) .
		'" class="post-password-form" method="post">' .
		'<label for="password"><input type="password" name="post_password" id="password" spellcheck="false" size="20" placeholder="' .
		esc_attr($placeholder) .
		'" /></label>' .
		'<input type="submit" name="Submit" value="' .
		esc_attr($button_label) .
		'" />' .
		"</form>" .
		$content_end;
	return $output;
}

/* 【管理画面】               */
/* ************************ */

// 【管理画面】出店者一覧に項目を追加・並び替え・ソート
// -- カラム見出しを追加する
add_filter("manage_vendors_posts_columns", "tacf_add_custom_column_vendors");
function tacf_add_custom_column_vendors($columns=[]) {
	$columns = [
		"cb" =>
			'<input id="cb-select-all-1" type="checkbox" /><label for="cb-select-all-1"><span class="screen-reader-text">すべて選択</span></label>',
		"number" => "No.",
		"title" => "タイトル",
		"booth_number" => "出店番号",
		"location" => "エリア",
		"taxonomy-vendor-results" => "選考結果",
		"taxonomy-vendor-genres" => "ジャンル",
		"taxonomy-vendor-parking" => "駐車場",
		"taxonomy-vendor-source" => "入力元",
		"date" => "日付",
	];
	return $columns;
}
// -- カラムの内容を表示する
add_action("manage_vendors_posts_custom_column", "tacf_display_custom_column_vendors", 10, 2,);
function tacf_display_custom_column_vendors($column_name='', $post_id=0) {
	foreach (["number"] as $key):
		if ($column_name == $key) {
			$post_meta = get_post_meta($post_id, "vendor_manage_" . $key, true);
			echo $post_meta ? $post_meta : "-";
		}
	endforeach;
	foreach (["booth_number", "location"] as $key):
		if ($column_name == $key) {
			$post_meta = get_post_meta($post_id, "vendor_manage_pass_" . $key, true);
			echo $post_meta ? $post_meta : "-";
		}
	endforeach;
}
// -- カラム幅を調節
add_action("admin_head", "tacf_custom_column_width_vendors");
function tacf_custom_column_width_vendors() {
	echo '<style type="text/css">';
	echo ".fixed .column-number {width:2em; text-align:right; }";
	echo ".fixed .column-booth_number {width:7em; text-align:center; }";
	echo ".fixed .column-taxonomy-vendor-results {width:5em; }";
	echo ".fixed .column-taxonomy-vendor-genres {width:8em; }";
	echo ".fixed .column-taxonomy-vendor-source {width:8em; }";
	echo ".fixed .column-post_type {width:6em; }";
	echo "</style>";
}
// -- 「出店番号」ソート機能
add_filter( 'manage_edit-vendors_sortable_columns', 'tacf_custom_sortable_columns' );
function tacf_custom_sortable_columns($sort_column=[]) {
  $sort_column['booth_number'] = 'booth_number';
	return $sort_column;
}
add_filter( 'request', 'tacf_custom_orderby_columns' );
function tacf_custom_orderby_columns($vars=[]) {
  if (isset($vars['orderby']) && 'booth_number' == $vars['orderby']) {
    $vars = array_merge($vars, array(
      'orderby' => 'meta_value_num',
      'meta_key' => 'vendor_manage_pass_booth_number',
    ));
  }
  return $vars;
}

// 【管理画面】固定ページ一覧に項目を追加する
// -- カラム見出しを追加する
add_filter("manage_pages_columns", "tacf_add_custom_column_pages");
function tacf_add_custom_column_pages($columns=[]) {
	$columns["date_publish"] = "公開日";
	$columns["date_private"] = "削除日";
	return $columns;
}
// -- カラムの内容を表示する
add_action(
	"manage_pages_custom_column",
	"tacf_display_custom_column_pages",
	10,
	2,
);
function tacf_display_custom_column_pages($column_name='', $post_id=0) {
	//global $post;
	foreach (["date_publish", "date_private"] as $key):
		if ($column_name == $key) {
			$post_meta = get_post_meta($post_id, "manage_visibility_" . $key, true);
			echo $post_meta ? date("Y/m/d", strtotime($post_meta)) : "-";
		}
	endforeach;
}
// -- カラム幅を調節
add_action("admin_head", "tacf_custom_column_width_pages");
function tacf_custom_column_width_pages() {
	echo '<style type="text/css">';
	echo ".fixed .column-date_publish {width:8%; }";
	echo ".fixed .column-date_private {width:8%; }";
	echo "</style>";
}

/* 【グローバル変数】          */
/* ************************ */

$configPost = get_page_by_path("config");
$configID = $configPost ? $configPost->ID : 0;
define("CONFIG_ID", $configID);

// 【グローバル変数】「出店申込み」「チラシ用出店リスト」ページの管理用データ（URL属性と表示期間）を配列にする
global $aExternalPages;
global $aPageManagementData;
global $aPageUrlAttr;
$aExternalPages = ["entry", "flyer", "file"];
$aPageUrlAttr = ["id", "user", "action"];
$oPagelinkPost = get_page_by_path("management/pagelink");
$aPageManagementData = [];
if ($oPagelinkPost) {
	$post_id = $oPagelinkPost->ID;
	foreach ($aExternalPages as $key):
		$aPageManagementData[$key] = [];
		foreach ($aPageUrlAttr as $val):
			$aPageManagementData[$key][$val] = get_post_meta($post_id, "tacfv_externalpage_" . $key . "__" . $val, true,);
		endforeach;
	endforeach;
}

/* 【関数】                  */
/* ************************ */

// 【関数】cronイベントにスケジュールされる動作（外部公開ページの「公開／非公開」設定）を記述
if (!function_exists("tacfv_function_update_accessible_post")) {
	function tacfv_function_update_accessible_post() {
		global $aExternalPages;
		$today = date_i18n("Ymd");
		$aOutput = [];
		foreach ($aExternalPages as $slug):
			$post_obj = get_page_by_path("accessible/" . $slug);
			$post_id = $post_obj ? $post_obj->ID : 0;
			if ($post_id) {
				$date_publish = get_post_meta($post_id,	"manage_visibility_date_publish", true,);
				$date_private = get_post_meta($post_id, "manage_visibility_date_private", true,);
				if (!empty($date_publish) && $today >= $date_publish) {
					wp_update_post(["ID" => $post_id, "post_status" => "publish"]);
				}
				if (!empty($date_private) && $today >= $date_private) {
					wp_update_post(["ID" => $post_id, "post_status" => "private"]);
				}
			}
		endforeach;
		return true;
	}
}
add_action(
	"tacfv_update_accessible_post",
	"tacfv_function_update_accessible_post",
);

/* 【ショートコード】          */
/* ************************ */

/* 【その他】                */
/* ************************ */

// 【出店者リスト】リスト管理用のファイルを読み込む
require_once "inc/vendorlist-parameters.php";
require_once "inc/vendorlist-functions.php";

// 【外部公開ページ】公開／非公開を処理する
// function daily_action() {
// }
// add_action('schedule_daily_action', 'daily_action');
// if( !wp_next_scheduled('schedule_daily_action') ) {
// 	// 重複を防ぐ
// 	wp_schedule_event(strtotime('tomorrow'), 'daily', 'schedule_daily_action');
// }

//   ＊外部公開用のページが保存されたタイミングでスケジュールセット
// add_action('save_post','tacfv_expire_event');
// function tacfv_expire_event($pid) {
// 	$the_time = strtotime(date_i18n('Y-m-d H:i:s').' JST');
// 	foreach( array('publish','close') as $key ) :
// 		$meta_key = 'manage_visibility_date_'.$key;
// 		$event_date = get_post_meta($pid, $meta_key, true);
// 		if( !empty($event_date) ) {
// 			$event_time = strtotime($event_date.' JST');
// 			if( $the_time < $event_time) ;
// 			// 未来の日付ならスケジュールをセット
// 			$hook = 'tacfv_page_'.$key;
// 			wp_schedule_single_event($event_time, $hook, array($pid));
// 		}
// 	endforeach;
// }
