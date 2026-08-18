<?php
// 出店者情報管理リストの設定情報
$aItemPriceData = array(
	'location' => array(
		'Aエリア 路面' => 13000,
		'Aエリア 路面以外' => 12000,
		'Bエリア' => 11000,
		'Cエリア' => 9,000,
		'美術館エリア' => 11000,
		'中央エリア' => 9000,
		'新斎ホール 「屋内」' => 11000,
		'支所前フードコート' => 20000,
		'駅前フードコート' => 18000,
		'キッチンカー' => 15000,
		'その他' => null
	),
	'location_indoor' => array(
		'屋内追加料金' => 2000,
	),
	'car_price' => array(
		'駐車場追加料金' => 1000,
	),
);
$aLocationNameData = array();
foreach( $aItemPriceData['location'] as $key=>$val ) :
	$aLocationNameData[] = $key;
endforeach;
$aPrefixData = array(
	'meta' => array(
		'entry' => 'vendor_entry_',
		'manage' => 'vendor_manage_',
		'pass' => 'vendor_manage_pass_',
	),
	'tax' => 'vendor-',
);
$aVendorsData = array(

	'head-management' => array(
		'label' => '管理項目' // HEAD 「管理項目」
	),

	'number' => array(
		'label' => '受付',
		'db' => 'meta',
		'key' => 'vendor_manage_',
		'type' => 'number',
		'default' => 1,
		'sort' => 1,
	),
	'results' => array(
		'label' => '選考結果',
		'db' => 'tax',
		'default' => 0,
		'sort' => 1,
		'filter' => 1,
	),
	'booth_number' => array(
		'label' => '出店No.',
		'db' => 'meta',
		'key' => 'vendor_manage_pass_',
		'type' => 'number',
		'default' => 1,
		'sort' => 1,
	),
	'location' => array(
		'label' => '出店エリア',
		'db' => 'meta',
		'key' => 'vendor_manage_pass_',
		'option' => $aLocationNameData,
		'default' => 1,
		'sort' => 1,
		'filter' => 1,
	),
	'cancel' => array(
		'label' => 'キャンセル待ち',
		'option' => array('する','しない'),
		'db' => 'meta',
		'default' => 0,
		'sort' => 1,
		'filter' => 1,
	),
	'price' => array(
		'label' => '出店料',
		'db' => 'meta',
		'key' => 'vendor_manage_pass_',
		'type' => 'price',
		'default' => 1,
		'sort' => 1,
	),
	'payment' => array(
		'label' => '入金',
		'db' => 'meta',
		'key' => 'vendor_manage_pass_',
		'type' => 'center',
		'option' => array('未','済'),
		'default' => 1,
		'sort' => 1,
		'filter' => 1,
	),
	'receipt' => array(
		'label' => '領収証',
		'db' => 'meta',
		'option' => array('必要','不要'),
		'key' => 'vendor_manage_pass_',
		'default' => 0,
		'sort' => 1,
		'filter' => 1,
	),
	'parking' => array(
		'label' => '駐車場',
		'db' => 'tax',
		'default' => 1,
		'sort' => 1,
	),
	'comefrom' => array(
		'label' => '地域',
		'db' => 'meta',
		'key' => 'vendor_manage_pass_',
		'default' => 0,
		'sort' => 1,
	),

	'head-shopname' => array(
		'label' => '店舗名・連絡先' // HEAD 「店舗名・連絡先」
	),

	'shop' => array(
		'label' => '出店名',
		'db' => 'meta',
		'default' => 1,
	),
	'shop_kana' => array(
		'label' => '出店名(フリガナ)',
		'db' => 'meta',
		'default' => 1,
		'sort' => 1,
	),
	'name' => array(
		'label' => '代表者',
		'db' => 'meta',
		'default' => 1,
	),
	'name_kana' => array(
		'label' => '代表者（フリガナ）',
		'db' => 'meta',
		'default' => 1,
		'sort' => 1,
	),
	'phone' => array(
		'label' => '電話番号',
		'db' => 'meta',
		'default' => 0,
	),
	'mobile' => array(
		'label' => '携帯電話',
		'db' => 'meta',
		'default' => 0,
	),
	'besttimes' => array(
		'label' => '連絡可能な時間帯',
		'db' => 'meta',
		'option' => array('午前中','12時〜13時','午後','いつでも','その他'),
		'default' => 0,
		'filter' => 1,
	),
	'birthday' => array(
		'label' => '生年月日',
		'db' => 'meta',
		'type' => 'date',
		'default' => 1,
	),
	'email' => array(
		'label' => 'メールアドレス',
		'db' => 'meta',
		'default' => 1,
	),
	'zip' => array(
		'label' => '郵便番号',
		'db' => 'meta',
		'default' => 1,
	),
	'address' => array(
		'label' => '住所',
		'db' => 'meta',
		'type' => 'textarea',
		'default' => 1,
	),

	'head-detail' => array(
		'label' => '出店内容', // HEAD 「出店内容」
	),

	'genres' => array(
		'label' => 'ジャンル',
		'db' => 'tax',
		'default' => 1,
		'sort' => 1,
		'filter' => 1,
	),
	'genres_other' => array(
		'label' => 'ジャンル（その他）',
		'db' => 'meta',
		'default' => 1,
	),
	'details' => array(
		'label' => '出店内容',
		'db' => 'meta',
		'type' => 'textarea',
		'default' => 1,
	),
	'photo' => array(
		'label' => '選考写真',
		'db' => 'meta',
		'type' => 'image',
		'default' => 1,
	),
	'flyer_photo_inclusiv' => array(
		'label' => 'チラシ用写真の有無',
		'db' => 'meta',
		'option' => array('選考用写真と同じ','アップロードする','出店決定後に提出する'),
		'default' => 1,
		'sort' => 1,
		'filter' => 1,
	),
	'flyer_photo' => array(
		'label' => '公開写真１',
		'db' => 'meta',
		'type' => 'image',
		'default' => 1,
	),
	'upload_photo' => array(
		'label' => '公開写真２',
		'db' => 'meta',
		'key' => 'vendor_',
		'type' => 'image',
		'default' => 1,
	),
	'lisence_inclusiv' => array(
		'label' => '営業許可証の有無',
		'db' => 'meta',
		'option' => array('なし（飲食以外）','アップロードする','出店決定後に提出する'),
		'default' => 0,
		'sort' => 1,
		'filter' => 1,
	),
	'lisence' => array(
		'label' => '営業許可証１',
		'db' => 'meta',
		'type' => 'file',
		'default' => 0,
	),
	'upload_lisence' => array(
		'label' => '営業許可証２',
		'db' => 'meta',
		'key' => 'vendor_',
		'type' => 'file',
		'default' => 0,
	),

	'head-vehicle' => array(
		'label' => '車両・搬入', // HEAD 「車両・搬入」
	),

	'daybefore' => array(
		'label' => '前日搬入',
		'db' => 'meta',
		'option' => array('希望する','希望しない'),
		'default' => 1,
		'sort' => 1,
		'filter' => 1,
	),
	'vehicles' => array(
		'label' => '車両台数',
		'db' => 'meta',
		'option' => array('車両なし','１台','２台','３台'),
		'default' => 1,
	),
	'car1_model' => array(
		'label' => '車種[1]',
		'db' => 'meta',
		'default' => 1,
	),
	'car1_name' => array(
		'label' => '車名[1]',
		'db' => 'meta',
		'default' => 1,
	),
	'car1_plate' => array(
		'label' => 'ナンバー[1]',
		'db' => 'meta',
		'default' => 1,
	),
	'car2_model' => array(
		'label' => '車種[2]',
		'db' => 'meta',
		'default' => 1,
	),
	'car2_name' => array(
		'label' => '車名[2]',
		'db' => 'meta',
		'default' => 1,
	),
	'car2_plate' => array(
		'label' => 'ナンバー[2]',
		'db' => 'meta',
		'default' => 1,
	),
	'car3_model' => array(
		'label' => '車種[3]',
		'db' => 'meta',
		'default' => 1,
	),
	'car3_name' => array(
		'label' => '車名[3]',
		'db' => 'meta',
		'default' => 1,
	),
	'car3_plate' => array(
		'label' => 'ナンバー[3]',
		'db' => 'meta',
		'default' => 1,
	),

	'head-location' => array(
		'label' => '出店場所・設備', // HEAD 「出店場所・設備」
	),

	'location_first' => array(
		'label' => 'エリア[1]',
		'db' => 'meta',
		'default' => 1,
		'sort' => 1,
	),
	'location_first_indoor' => array(
		'label' => '屋内希望[1]',
		'db' => 'meta',
		'default' => 1,
	),
	'location_first_units' => array(
		'label' => '口数[1]',
		'db' => 'meta',
		'default' => 1,
	),
	'location_second' => array(
		'label' => 'エリア[2]',
		'db' => 'meta',
		'default' => 1,
		'sort' => 1,
	),
	'location_second_indoor' => array(
		'label' => '屋内希望[2]',
		'db' => 'meta',
		'default' => 1,
	),
	'location_second_units' => array(
		'label' => '口数[2]',
		'db' => 'meta',
		'default' => 1,
	),
	'tentsize' => array(
		'label' => 'テントサイズ',
		'db' => 'meta',
		'default' => 1,
	),
	'kitchencar' => array(
		'label' => 'キッチンカーサイズ',
		'db' => 'meta',
		'default' => 1,
	),
	'generator' => array(
		'label' => '発電機使用',
		'db' => 'meta',
		'option' => array('あり','なし'),
		'default' => 1,
		'sort' => 1,
	),
	'gasstove' => array(
		'label' => 'ガスコンロ使用',
		'db' => 'meta',
		'option' => array('あり','なし'),
		'default' => 1,
		'sort' => 1,
	),
	'portablestove' => array(
		'label' => 'カセットコンロ使用',
		'db' => 'meta',
		'option' => array('あり','なし'),
		'default' => 1,
		'sort' => 1,
		),

	'head-others' => array(
		'label' => 'その他', // HEAD 「その他」
	),

	'times' => array(
		'label' => '出店実績',
		'db' => 'meta',
		'option' => array('はじめて出店する','出店実績あり'),
		'default' => 0,
		'sort' => 1,
	),
	'experience' => array(
		'label' => '直近回の出店',
		'db' => 'meta',
		'default' => 0,
	),
	'share' => array(
		'label' => 'シェア出店',
		'db' => 'meta',
		'type' => 'textarea',
		'default' => 0,
	),
	'link' => array(
		'label' => 'リンク',
		'db' => 'meta',
		'type' => 'url',
		'default' => 1,
	),
	'message' => array(
		'label' => '通信欄',
		'db' => 'meta',
		'type' => 'textarea',
		'default' => 0,
	),
	'source' => array(
		'label' => '入力元',
		'db' => 'tax',
		'default' => 1,
		'sort' => 1,
	),
	'date' => array(
		'label' => '申込み日時',
		'db' => 'date',
		'type' => 'date',
		'default' => 1,
	),
);

$aSummaryData = array(
	// 集計表
	'tabulation' => array(
		'type' => 'radio',
		'head' => '集計データ',
		'array' => array(
			'yes' => array(
				'label' => '表示',
				'defalt' => 1,
			),
			'no' => array(
				'label' => '非表示',
				'defalt' => 0,
			),
		),
	),

);

