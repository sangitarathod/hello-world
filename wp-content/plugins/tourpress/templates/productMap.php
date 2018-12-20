<?php

class productMap extends WP_Widget {
	function productMap() {
		parent::WP_Widget(false, 'Product Map', array('description' => 'Displays the origin (location) stored in TourPress using Google Maps. Only visible on Product pages.'));	
	}
	
	function widget($args, $instance) {
		global $post;
		
		// Check that this is a "product" and it has an origin (location) saved
		// as all Products in TourPress must have a location
		//if(is_single() && get_query_var('post_type') == 'tourpress_product') {
		if(is_singular('product')) {
			$origin = get_post_meta($post->ID, 'geocode', true );
			if(strlen($origin)>0)
				$do_output = true;	
			else
				$do_output = false;			
		} else {
			$do_output = false;
		}
		
		if($do_output) {
			extract($args);
			echo $before_widget;
			$title = empty($instance['title']) ? '&nbsp;' : apply_filters('widget_title', $instance['title']);
			if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
			?>
			<div id="<?php echo $widget_id."-map"; ?>" class=""></div>
			<script type="text/javascript">
				jQuery(document).ready(function() {
					var mapwidth = jQuery('#<?php echo $widget_id."-map"; ?>').width();
					
					jQuery('#<?php echo $widget_id."-map"; ?>').append('<iframe />');
					var tpmapframe = jQuery('#<?php echo $widget_id."-map"; ?> iframe');
					
					tpmapframe.width(mapwidth);
					
					var tpmapurl = "<?php echo plugins_url( 'map.php', __FILE__ ); ?>?width=" + mapwidth + '&height=' + tpmapframe.height() + '&zoomlevel=' + "<?php echo $instance['zoomlevel']; ?>" + '&apikey=' + "<?php echo $instance['apikey']; ?>" + '&latlng=' + "<?php echo $origin; ?>";
					tpmapframe.attr('src', tpmapurl); 
				});	
			</script>
			<?php
			echo $after_widget;
		}
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['apikey'] = strip_tags($new_instance['apikey']);
		$instance['zoomlevel'] = strip_tags($new_instance['zoomlevel']);
		return $instance;
	}
	
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => 'Product Location', 'apikey' => '', 'zoomlevel' => '5') );
		$title = strip_tags($instance['title']);
		$apikey = strip_tags($instance['apikey']);
		$zoomlevel = strip_tags($instance['zoomlevel']);
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
		
		<p><label for="<?php echo $this->get_field_id('zoomlevel'); ?>">Zoom level: 
		<select class="widefat" id="<?php echo $this->get_field_id('zoomlevel'); ?>" name="<?php echo $this->get_field_name('zoomlevel'); ?>">
			<?php
				for($i=1; $i<=18; $i++) {
					echo '<option value="'.$i.'"';
					if($i==(int)$zoomlevel)
						echo ' selected="selected"';
					echo '>'.$i;
					if($i==5)
						echo ' (Default)';
					echo '</option>';
				}
			?>
		</select>
		</label></p>
		
		<p><label for="<?php echo $this->get_field_id('apikey'); ?>">Google Maps API Key: <input class="widefat" id="<?php echo $this->get_field_id('apikey'); ?>" name="<?php echo $this->get_field_name('apikey'); ?>" type="text" value="<?php echo attribute_escape($apikey); ?>" /></label><a href="http://code.google.com/apis/maps/signup.html" target="_blank">Get a key</a></p>
		<?php
	}
}

add_action('widgets_init', create_function('', 'return register_widget("productMap");'));

?>