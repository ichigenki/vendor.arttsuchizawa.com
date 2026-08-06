<?php
/**
 * The template for displaying "pagelink" content in page.php
 */

// 【ショートコード】ページを指定してURLとＱＲコードを書き出す
if( !function_exists('shortcode_tacfv_urlqr') ) {
	function shortcode_tacfv_urlqr($atts) {
		$atts = shortcode_atts( array(
			'page' => '',
		), $atts, 'tacfv_urlqr' );
		global $aPageManagementData;
		$page = $atts['page'];
		$output = '';
		$attr_array = array();
		if( isset($aPageManagementData[$page]) && is_array($aPageManagementData[$page]) ) {
			foreach( $aPageManagementData[$page] as $key=>$val ) :
				$attr_array[] = $key.'='.$val;
			endforeach;
		}
		$url = 'https://vendor.arttsuchizawa.com/accessible/'.$page.'/?'.implode('&', $attr_array);
		ob_start();
?>
<form>
<p><input type="text" value="<?php echo $url; ?>" style="display: block; margin-bottom: 0.5em; ">
<button type="button" id="<?php echo $page; ?>-copy-url" data-url="<?php echo $url; ?>">リンクをコピー</button>
<span id="<?php echo $page; ?>-success-msg" class="success-msg" style="display: none;">クリップボードにコピーしました</span></p>
</form>
<style>
.entry-content a img {box-shadow: none; }
.wpkqcg_qrcode_wrapper img.wpkqcg_qrcode {border: 1px solid #ccc; }
</style>
<table style="width: auto; ">
<tbody>
<tr style="border: none; ">
<td style="padding: 0; ">
<?php echo do_shortcode('[kaya_qrcode content="'.$url.'" ecclevel="L" size="400" border="4" color="#000000" bgcolor="#FFFFFF" align="aligncenter" new_window="1" content_url="1" download_button="1" download_align="alignleft"]'); ?>
</td>
</tr>
</tbody>
</table>
<script type="text/javascript">
jQuery(document).ready(function($){
  $("#<?php echo $page; ?>-copy-url").click(function () {
    // data-urlの値を取得
    const url = $(this).data("url");
    // クリップボードにコピー
    navigator.clipboard.writeText(url);
    // フラッシュメッセージ表示
    $('#<?php echo $page; ?>-success-msg').fadeIn("slow", function () {
      $(this).delay(2000).fadeOut("normal");
    });
  });
});
</script>
<?php
		$output = ob_get_clean();
		return $output;
	}
	add_shortcode( 'tacfv_urlqr', 'shortcode_tacfv_urlqr' );
}

?>
<div class="entry-content">

<?php the_content(); ?>

</div><!-- .entry-content -->

