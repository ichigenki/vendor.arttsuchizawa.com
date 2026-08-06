<?php
/**
 * The template for displaying "parking" content in page.php
 */


?>
<div class="entry-content">

<?php the_content(); ?>

<?php
if( !post_password_required() ) :
?>
<table class="parking-table">
<thead>
<tr>
<th class="num">No.</th><th class="parking">駐車場名</th><th class="plate">ナンバー <span class="normal">車名 [出店No.]</span></th>
</tr>
</thead>
<tbody>
<?php
//var_dump($oTerm);
$aTerms = get_terms('vendor-parking');
foreach( $aTerms as $oTerm ) :
	$car_number = array();

	$args = array(
		'post_type' => 'vendors',
		'post_status' => 'publish',
		'tax_query' => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'vendor-results',
				'field'		 => 'name',
				'terms'		 => '当選',
			),
			array(
				'taxonomy' => 'vendor-parking',
				'field'		 => 'name',
				'terms'		 => $oTerm->name,
			),
		),
		'orderby' => 'date',
		'order' => 'ASC',
		'posts_per_page' => -1
	);
	$the_query = new WP_Query($args);
	if( $the_query->have_posts() ) :
		while( $the_query->have_posts() ) :
			$the_query->the_post();
			$post_id = get_the_ID();
			$booth_num = get_post_meta($post_id, 'vendor_manage_pass_booth_number', true);
			for( $i = 1; $i <= 3; $i++ ) :
				$car_plate = get_post_meta($post_id, 'vendor_entry_car'.$i.'_plate', true);
				$car_name = get_post_meta($post_id, 'vendor_entry_car'.$i.'_name', true);
				if( $car_plate ) :
					$car_number[] = '<span class="inline-block"><span class="number">'.esc_html($car_plate).'</span>'.($car_name? ' '.esc_html($car_name).' ['.$booth_num.']': '').'</span>';
				endif;
			endfor;
		endwhile;
	endif;
	wp_reset_postdata();

	echo '<tr><td class="num">'.esc_html($oTerm->description).'</td><td class="parking">'.esc_html($oTerm->name).'</td><td class="plate">'.implode('、', $car_number).'</td></tr>'.PHP_EOL;
endforeach;
?>
<tr>

</tr>
</tbody>
</table>

<?php
endif;
?>

</div><!-- .entry-content -->

