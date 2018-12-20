<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Register options
function epower_register() {
	
	register_setting('epower_settings', 'epower_api_url');		
	register_setting('epower_settings', 'epower_username'); 
	register_setting('epower_settings', 'epower_password');		
	register_setting('epower_settings', 'epower_city_url'); 
	
}

// Generate HTML for the menu page
function epower_settings() {
	?>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"><br /></div>
		<h2>EPower Settings</h2>
		<form method="post" action="options.php">
			<?php settings_fields('epower_settings'); ?>
			<h3>EPower Settings</h3>			
			
			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						<label for="epower_api_url">API URL</label>
					</th>
					<td>
						<input type="text" name="epower_api_url" size="50" value="<?php echo get_option('epower_api_url'); ?>" autocomplete="true" />
					</td>
				</tr>					
				<tr valign="top">
					<th scope="row">
						<label for="epower_username">Username</label>
					</th>
					<td>
						<input type="text" name="epower_username" size="20" value="<?php echo get_option('epower_username'); ?>" autocomplete="false" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="epower_password">Password</label>
					</th>
					<td>
						<input type="password" name="epower_password" value="<?php echo get_option('epower_password'); ?>" autocomplete="false" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="epower_city_url">City URL</label>
					</th>
					<td>
						<input type="text" name="epower_city_url" size="50" value="<?php echo get_option('epower_city_url'); ?>" autocomplete="true" />
					</td>
				</tr>					
			</table>
			<p class="submit">
				<input class="button-primary" type="submit" value="Save Changes" name="Submit" />
			</p>

		</form>

	</div>
	<?php
}

?>
