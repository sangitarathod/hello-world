<?php

class productRates extends WP_Widget {

	function productRates() {
		parent::WP_Widget(false, 'Product Rates', array('description' => 'Display rates and availability based on a date range and pax configuration. Only visible on Product pages.'));	
	}
	
	function widget($args, $instance) {
		// global $post;
		
		// if(is_singular('tourpress_product')) {
		// 	$do_output = true;		
		// } else {
		// 	$do_output = false;
		// }
		
		// if($do_output) {
		if( is_singular('product') ) {
			// extract($args);
			// $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
   //  		$text = empty($instance['text']) ? '' : $instance['text'];

			// echo $before_widget;
			// $title = empty($instance['title']) ? '&nbsp;' : apply_filters('widget_title', $instance['title']);
			// if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
				
			?>
			<!--form>	
				<div>Date In<input type="date" name="dateIn"/></div>
				<div>Date Out<input type="date" name="dateOut"/></div>

				
				<p class="submit">
				<input class="button-primary" type="submit" value="Search" name="Submit" />
				</p>
			</form-->

			<?php
			//echo $after_widget;
			//
		    extract($args, EXTR_SKIP);
			
		    $title 		= empty($instance['title']) 		? ' ' : apply_filters('widget_title', $instance['title']);
				$template = empty($instance['template']) 	? ' ' : $instance['template'];
				$class		=	empty($instance['class']) 		? ' ' : $instance['class'];
			
		    echo (isset($before_widget)?$before_widget:'');
		    if (!empty($title))
		    	echo $before_title . '<label class="'.$class.'">'.$title.'</label>' . $after_title ;

			// $dateIn = get_transient( 'dateIn' );
			// if( empty( $sessionID ) ) {
			// 	$result = $this->soapClient->authenticate( new SoapVar ($params, SOAP_ENC_OBJECT) );
			//     $sessionID = $result->id;
			//     set_transient( 'sessionID', $sessionID, HOUR_IN_SECONDS );
			// }
			
				$fieldsMap = array(
					'DATE_IN'			=> 	'<input type="date" name="date_in" value="' . (isset( $_POST['date_in'] ) ? sanitize_text_field( $_POST['date_in'] ) : '') . '" />',
					'DATE_OUT'		=>	'<input type="date" name="date_out" value="'. (isset( $_POST['date_out'] ) ? sanitize_text_field( $_POST['date_out'] ) : '' ).'"/>',
					'UNIT_ROOMS'	=>	'<input type="number" name="units" min="1" max="4" value="1">',
					'ADULTS'			=>	'<input type="number" name="adults" min="0" max="9" value="2">',
					'CHILDREN'		=>	'<input type="number" name="children" min="0" max="9" value="0">',
					'SUBMIT'			=>	'<button  class="button-primary" type="submit">Search</button>'
					
				);
				foreach ($fieldsMap as $key => $value){
					$template = str_replace('{{'.$key.'}}',$value,$template);
				}
				
				echo '<form method="POST" action="">'.$template.'</form>';
			?>
				
			<?php
		   
		    // After widget code, if any  
		    echo (isset($after_widget)?$after_widget:'');
		}
	}
	
	function form($instance)
	{
		$instance = wp_parse_args( (array) $instance, 
															array( 'title' => 'Rates & Availability',
														 					'template' => '&lt;label&gt;Date In{{DATE_IN}}&#13;&#10;&lt;/label&gt;&lt;label&gt;Date Out{{DATE_OUT}}&lt;/label&gt;&#13;&#10;&lt;label&gt;Unit/Rooms{{UNIT_ROOMS}}&lt;/label&gt;&#13;&#10;&lt;label&gt;Adults{{ADULTS}}&lt;/label&gt;&#13;&#10;&lt;label&gt;Children{{CHILDREN}}&lt;/label&gt;&#13;&#10;&lt;p class="submit"&gt;{{SUBMIT}}&lt;/p&gt;'
																	 ));
		$title = strip_tags($instance['title']);
		$class = strip_tags($instance['class']);
		$template = ($instance['template']);
		
		?>

		<!-- title -->
		<p>
		  <label for="<?php echo $this->get_field_id('title'); ?>">Title: 
		    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
				   name="<?php echo $this->get_field_name('title'); ?>" type="text" 
		  		   value="<?php echo attribute_escape($title); ?>" />
		  </label>
		</p>

		<!-- template -->
		<div>
		  <label for="<?php echo $this->get_field_id('template'); ?>">Template: 
		    <textarea rows="8" class="widefat"
									id="<?php echo $this->get_field_id('template'); ?>"
									name="<?php echo $this->get_field_name('template'); ?>"
				><?php echo $template; ?></textarea>
		  </label>
			<small>Note: All variables should be in the template. Otherwise this widget will not work.</small>
			<strong><small>{{DATE_IN}}</small></strong>, <strong><small>{{DATE_OUT}}</small></strong>,<strong><small>{{UNIT_ROOMS}}</small></strong>,<strong><small>{{ADULTS}}</small></strong>,<strong><small>{{CHILDREN}}</small></strong>,<strong><small>{{SUBMIT}}</small></strong>
		</div>

		<!-- custom class name -->
		<p>
		  <label for="<?php echo $this->get_field_id('class'); ?>">Custom Class Name: 
		    <input class="widefat" id="<?php echo $this->get_field_id('class'); ?>" 
				   name="<?php echo $this->get_field_name('class'); ?>" type="text" 
		  		   value="<?php echo attribute_escape($class); ?>" />
		  </label>
		</p>
		<?php
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['class'] = strip_tags($new_instance['class']);
		$instance['template'] = ($new_instance['template']);
		return $instance;
	}

}

add_action('widgets_init', create_function('', 'return register_widget("productRates");'));

?>