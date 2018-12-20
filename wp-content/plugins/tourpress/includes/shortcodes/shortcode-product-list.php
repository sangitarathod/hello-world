<?php

function tourpress_product_list($atts, $content){
  $args = shortcode_atts( array(
    'tourpress_product_type' => '',
    'location' => ''
  ), $atts );
	
	 if(!empty($_GET['l'])){
		//$location = $_GET['l'];	
		$args['location'] = $_GET['l'];
		$currentLocation = $_GET['l'];
	}
  //echo $_GET['l'];
 //global $wp_query;
	wp_reset_query();
	
	$taxQuery = array('relation' => 'OR');
	
	foreach ($args as $key => $value) {
		if(strlen($value) > 0){
			array_push($taxQuery,array(
				'taxonomy' => $key,
				'field' => 'slug',
				'terms' => $value,
				'include_children ' => false
			));	
		}	
	}

	if((strlen($args['tourpress_product_type']) > 0) && (strlen($args['location']) > 0))
	{
		$taxQuery['relation'] = 'AND';
	}
	//var_dump($taxQuery);
	
	$args = array(
		'post_type'							=> 'product',
	 	'post_status' 					=> 'publish',
		'orderby' 							=> 'date',
		'order'									=>'DESC',
		'caller_get_posts' 			=> 1,
	 	'paged'                 => 1,
    'tax_query' 						=> 	$taxQuery,
		'post_parent' => 0
	);
	$wp_query = new WP_Query($args);
	//var_dump($query_result);
  
  
  ob_start();
  ?>
		<? if(!empty($currentLocation)){ ?>
		<h2 class="section-title" style="text-transform:capitalize;"><? echo str_replace('-',' ',$currentLocation); ?></h2>
		<? } ?>
    <div class="product-list-wrapper">
  <?php
  if ( $wp_query->have_posts() ):
    while ( $wp_query->have_posts() ) : $wp_query->the_post(); 
      ?>
        <div class="product-list" id="<?php echo get_the_ID(); ?>">
					<?php
						$featuredImage = TOURPRESS_PLUGIN_URL.'assets/images/placeholder.png';
						if(has_post_thumbnail(get_the_ID())){
							$featuredImage = wp_get_attachment_thumb_url(get_post_thumbnail_id(get_the_ID()));
							//$featuredImage = get_the_post_thumbnail_url($wp_query->the_post(),'medium');
						}
					?>
          <div class="product-thumbnail" style="background-image: url(<? echo $featuredImage; ?>)">
<!-- 						<img src="<?php echo $featuredImage; ?>" /> -->
					</div>  
          <div class="product-details">
            <h2 class="product-title"><a href="<? echo get_post_permalink(); ?>"><? echo the_title(); ?></a></h2>  
            <div class="product-excerpt" title="<? echo get_the_excerpt() ?>">
              <? 
								//echo the_excerpt();
								echo limit_text(get_the_excerpt(),40);
							?>   
            </div>
            <div class="product-list-bottom">
              <div class="product-price-range">
                <? echo 'From price <strong>'.get_post_meta(get_the_ID() , 'from_price', true ).'</strong> '.get_post_meta( get_the_ID(), 'from_price_type', true ) ?>    
              </div>  
              <a href="<? echo get_post_permalink(); ?>" class="btn btn-view">VIEW</a>
            </div>
          </div>
        </div>
      <?php
    endwhile;
  else :
    ?> <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p> <?php
  endif;
  
  ?>      
    </div>    
  <?php
 $ret = ob_get_clean();
 return $ret;
 
}
add_shortcode('xpl_product_list', 'tourpress_product_list');