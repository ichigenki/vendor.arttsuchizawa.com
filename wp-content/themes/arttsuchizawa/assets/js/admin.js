//* Admin Script *//

// 【グローバル変数】エリア名と料金の配列
// ※ WordPressプラグイン＜ACF：出店管理（事務局）＞の「出店エリア」フィールドの選択肢とこの配列キーの文言、それに＜vendorlist-parameters.php＞の値（'location'：２箇所）を一致させる必要がある
// let dataAreas = {
// 	"Aエリア 路面（13,000円）": 13000,
// 	"Aエリア 路面以外（12,000円）": 12000,
// 	"Bエリア（11,000円）": 11000,
// 	"Cエリア（9,000円）": 9000,
// 	"美術館エリア（11,000円）": 11000,
// 	"中央エリア（9,000円）": 9000,
// 	"新斎ホール 「屋内」（11,000円）": 11000,
// 	"支所前フードコート（20,000円）": 20000,
// 	"駅前フードコート（18,000円）": 18000,
// 	"キッチンカー（15,000円）": 15000,
// 	"その他": 0
// };

// 【グローバル変数】追加料金（と名称）の配列
// ※ WordPressプラグイン＜ACF：出店管理（事務局）＞の「屋内追加料金」「駐車場追加料金」フィールドのステップサイズ、それに＜vendorlist-parameters.php＞の値（'location_indoor'と'car_price'）を一致させる必要がある
// let dataAdds = {
// 	"屋内追加料金":2000,
// 	"駐車場追加料金":1000
// };

jQuery(document).ready(function($){

	// 【出店者詳細ページ】タクソノイミー＜選考結果：vendor-results＞とACFフィールド＜当落：vendor_manage_results＞を同期させる
	let dTaxResultsPass = 'ul#vendor-resultschecklist input#in-vendor-results-20-2[name="tax_input[vendor-results][]"]';
	let dTaxResultsFail = 'ul#vendor-resultschecklist input#in-vendor-results-26-2[name="tax_input[vendor-results][]"]';
	let dTaxResultsCancel = 'ul#vendor-resultschecklist input#in-vendor-results-25-2[name="tax_input[vendor-results][]"]';
	let dMetaPass = '#acf-group_69e024066e2f8 div.acf-field-69ec6995a12ee';
	let dMetaResultsPass = '#acf-group_69e024066e2f8 input[value="当選"]';
	let dMetaResultsFail = '#acf-group_69e024066e2f8 input[value="落選"]';
	let dMetaResultsCancel = '#acf-group_69e024066e2f8 input[value="キャンセル"]';
	$(dTaxResultsPass).change(function() {
		if( $(this).prop('checked') ) {
			$(dTaxResultsCancel).prop('checked', false);
			$(dMetaResultsPass).prop('checked', true);
			$(dMetaResultsFail).prop('checked', false);
			$(dMetaResultsCancel).prop('checked', false);
			$(dMetaPass).removeClass('acf-hidden').prop('hidden', false).css('display', 'block');
		} else {
			$(dMetaResultsPass).prop('checked', false);
			$(dMetaPass).addClass('acf-hidden').prop('hidden', true);
		}
	}).change();
	$(dTaxResultsCancel).change(function() {
		if( $(this).prop('checked') ) {
			$(dTaxResultsPass).prop('checked', false);
			$(dMetaResultsPass).prop('checked', false);
			$(dMetaResultsFail).prop('checked', false);
			$(dMetaResultsCancel).prop('checked', true);
			$(dMetaPass).removeClass('acf-hidden').prop('hidden', true).css('display', 'none');
		}
	}).change();
	$(dMetaResultsPass).change(function() {
		if( $(this).prop('checked') ) {
			$(dTaxResultsPass).prop('checked', true);
			$(dTaxResultsFail).prop('checked', false);
			$(dTaxResultsCancel).prop('checked', false);
			$(dMetaPass).removeClass('acf-hidden').prop('hidden', false).css('display', 'block');
		}
	}).change();
	$(dMetaResultsFail).change(function() {
		if( $(this).prop('checked') ) {
			$(dTaxResultsPass).prop('checked', false);
			$(dTaxResultsFail).prop('checked', true);
			$(dTaxResultsCancel).prop('checked', false);
		}
	}).change();
	$(dMetaResultsCancel).change(function() {
		if( $(this).prop('checked') ) {
			$(dTaxResultsPass).prop('checked', false);
			$(dTaxResultsFail).prop('checked', false);
			$(dTaxResultsCancel).prop('checked', true);
		}
	}).change();

	// 【出店者詳細ページ】出店料金の合計を計算する
	let inputLocationArea = 'div.acf-field-select[data-name="location"] select';
	let inputLocationIndoor = 'div.acf-field-true-false[data-name="location_indoor"] input[type="checkbox"]';
	let inputLocationIndPrice = 'div.acf-field-number[data-name="indoor_price"] input[type="number"]';
	let inputLocationUnits = 'div.acf-field-number[data-name="units"] input[type="number"]';
	let inputLocationPrice = 'div.acf-field-number[data-name="location_price"] input[type="number"]';
	let inputCarNumber = 'div.acf-field-number[data-name="car_number"] input[type="number"]';
	let inputCarPrice = 'div.acf-field-number[data-name="car_price"] input[type="number"]';
	let inputPriceTotal = 'div.acf-field-number[data-name="price"] input[type="number"]';
	let valArea='', valAreaUnits=1, valAreaPrice=0, valIndoorPrice=0, valCarNumber=1, valCarPrice=0;
	let valAreaAdd = dataIndoor["price"];
	let valCarAdd = dataParking["price"];
	// ※ 出店料関連の金額を出力する関数
	function printVendorFees() {
		valArea = $(inputLocationArea).val(); // 出店場所の値
		valAreaPrice = dataAreas[valArea]? dataAreas[valArea]: 0; // 出店料（出店場所）の値
		valAreaUnits = + $(inputLocationUnits).val(); // 口数の値
		valAreaPrice = valAreaPrice * valAreaUnits; // 出店料を計算（口数を乗算）
		propAreaIndoor = $(inputLocationIndoor).prop('checked'); // 野外／屋内の値
		if(propAreaIndoor){
			valIndoorPrice = valAreaAdd * valAreaUnits; // 屋内希望の場合に屋内追加料金を計算（口数を乗算）
		} else {
			valIndoorPrice = 0; // 屋内希望ではない場合は0
		}
		valCarNumber = + $(inputCarNumber).val() - 1; // 車両台数（の値）から1を引く
		valCarPrice = (0 > valCarNumber) ? 0 : valCarAdd * valCarNumber; // 駐車場料金を計算（車両台数を乗算）
		$(inputLocationIndPrice).val(valIndoorPrice); // 屋内追加料金を出力
		$(inputLocationPrice).val(valAreaPrice); // 出店料を出力
		$(inputCarPrice).val(valCarPrice); // 駐車場料金を出力
		$(inputPriceTotal).val(valAreaPrice + valIndoorPrice + valCarPrice); // 合計を出力
	}
	printVendorFees(); // ページを開いたときに計算結果を表示する
	// ※ それぞれの項目が入力・選択されたときに計算結果を表示する
	$(inputLocationArea).change(function() {
		var valThis = $(this).val(); // 出店場所の値
		if("新斎ホール 「屋内」（11,000円）" === valThis) {
			$(inputLocationIndoor).prop('checked', false); // 屋内のチェックを外す
			$(inputLocationIndPrice).val(0); // 屋内追加料金を0にする
		}
		printVendorFees();
	}).change(); // 「出店場所」
	$(inputLocationIndoor).change(function() {
		printVendorFees();
	}).change(); // 「屋内／屋外」
	$(inputLocationUnits).change(function() {
		printVendorFees();
	}).change(); // 「口数」
	$(inputLocationPrice).change(function() {
		printVendorFees();
	}).change(); // 「出店料」
	$(inputCarNumber).change(function() {
		printVendorFees();
	}).change(); // 「車両台数」
	$(inputCarPrice).change(function() {
		printVendorFees();
	}).change(); // 「駐車場追加料金」
});

jQuery(document).ready(function($){
	// 【Advanced Custom Fields】「出店管理（事務局） ⇒ 当選店データ ⇒ 出店エリア」の「選択肢」欄の値を書き出す
	let lines = $.map(dataAreas, function(value, key) {
		if (value !== "") {
			// 数値をカンマ区切りに変換
			let formatted = Number(value).toLocaleString();
			return key + "（" + formatted + "円）";
		} else {
			return key;
		}
	});
	$('body.acf-admin-single-field-group textarea#acf_fields-474-choices[name="acf_fields[474][choices]"]').val(lines.join("\n"));
});
