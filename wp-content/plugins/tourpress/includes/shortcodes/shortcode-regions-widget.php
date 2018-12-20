<?php
// Shortcode to get the regions
function tourpress_regions($atts, $content = null ){
	
	ob_start();
	$atts = shortcode_atts(
		array(
			'location'					=> '',
		),
		$atts,
		'xpl_regions'
	);
	
	$location = $atts['location'];
	$taxonomy = 'location';
	
	$term = get_term_by( 'slug', $location , $taxonomy );
	
	$term_id = $term->ID;
	
	echo do_shortcode('[xpl_term_children term_id="'. $term_id .'" taxonomy_name="'. $taxonomy .'"]');
	
	return  ob_get_clean();
}
add_shortcode('xpl_regions', 'tourpress_regions');

// Shortcode for term Children
function tourpress_get_term_children( $atts, $content = null ){

	$atts = shortcode_atts(
		array(
			'term_id'					=> '',
			'taxonomy_name '	=> '',
			'class'						=> ''
		),
		$atts,
		'xpl_term_children'
	);
		
	$term_id = $atts['term_id'];
	$taxonomy_name = $atts['taxonomy_name'];
	$class = $atts['class'];
	$term_children = get_term_children( $term_id, $taxonomy_name );
	
	ob_start() ?>
	
	<ul class="<?php echo $class; ?>">
	<?php
	foreach ( $term_children as $child ) {
			$term = get_term_by( 'id', $child, $taxonomy_name ); ?>
			<li><a href="<?php echo get_term_link( $child, $taxonomy_name ); ?>"><?php echo $term->name; ?></a></li>
	<?php	} ?>
	</ul>

<?php
	return ob_get_clean();
}
add_shortcode('xpl_term_children', 'tourpress_get_term_children');


  