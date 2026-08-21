<?php
$html_title = get_bloginfo('name');
$error_header = 'Sorry Access Denied';
$error_body = 'Tsuchizawa Art Craft Fare';
$hidden_button  = '';
if( is_404() ) {
	$html_title = '404 Error';
	$error_header = '404 Not Found';
	$error_body = 'The requested URL was not found on this server.';
// } elseif( is_front_page() ) {
// 	$hidden_button = '<div id="hidden-button" style="position:fixed;bottom:0;left:0;"><a href="/pagelink/" style="display:inline-block;width:40px;height:40px;cursor:default;"></a></div>'.PHP_EOL;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<title><?php echo $html_title; ?></title>
<meta name='robots' content='noindex, nofollow' />
<style>html, body, table {width:100%;height:100%;margin:0;padding:0;}</style>
</head>
<body>
<table><tr><td style="text-align:center;vertical-align:middle;"><h2 style="font-weight:bold;"><?php echo $error_header; ?></h2><p><?php echo $error_body; ?></p></td></tr></table>
<?php echo $hidden_button; ?>
</body>
</html>

