<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Register options
function tourpress_register() {
	register_setting('tourpress_settings', 'tourpress_servicetype', 'intval');
	register_setting('tourpress_settings', 'tourpress_service_url');		
	register_setting('tourpress_settings', 'tourpress_channelID'); 
	register_setting('tourpress_settings', 'tourpress_password');		
	register_setting('tourpress_settings', 'tourpress_bookstyle'); 
	register_setting('tourpress_settings', 'tourpress_bookheight', 'intval'); 
	register_setting('tourpress_settings', 'tourpress_bookwidth', 'intval'); 
	register_setting('tourpress_settings', 'tourpress_bookqs'); 
	register_setting('tourpress_settings', 'tourpress_booktext'); 
	register_setting('tourpress_settings', 'tourpress_update_frequency');
	register_setting('tourpress_settings', 'tourpress_unlinked_products','intval'); 
	register_setting('tourpress_settings', 'tourpress_last_product_cache'); 
	
	//dilate - wd
	register_setting('tourpress_settings', 'tourpress_last_specials_cache'); 
	register_setting('tourpress_settings', 'tourpress_specials_cache'); 
	
	// Add custom meta box
	if ( function_exists( 'add_meta_box' ) ) {
		add_meta_box( 'tourpress', 'TourPress (Explorer)', 'tourpress_product_edit' , 'product', 'advanced', 'high' );
	}
}

// Generate HTML for the menu page
function tourpress_options() {
	?>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"><br /></div>
		<h2>TourPress Settings</h2>
		<form method="post" action="options.php">
			<?php settings_fields('tourpress_settings'); ?>
			<h3>API Settings</h3>
			<p>You can find your settings by logging into TourPress then heading to <strong>Configuration &amp; Setup</strong> &gt; <strong>API</strong> &gt; <strong>XML API</strong>.</p>
			<input type="hidden" name="tourpress_servicetype" value="0" />
			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						<label for="tourpress_service_url">Service URL</label>
					</th>
					<td>
						<input type="text" name="tourpress_service_url" size="50" value="<?php echo get_option('tourpress_service_url'); ?>" autocomplete="true" />
					</td>
				</tr>					
				<tr valign="top">
					<th scope="row">
						<label for="tourpress_channelID">Username</label>
					</th>
					<td>
						<input type="text" name="tourpress_channelID" size="20" value="<?php echo get_option('tourpress_channelID'); ?>" autocomplete="false" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="tourpress_password">Password</label>
					</th>
					<td>
						<input type="password" name="tourpress_password" value="<?php echo get_option('tourpress_password'); ?>" autocomplete="false" />
					</td>
				</tr>
			</table>
			<h3>Booking Engine Settings</h3>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						Display style<br />
						<span class="description"><a href="http://www.tourpress.com/support/setup/booking_engine/iframe_or_popup.php" target="_blank">What's this?</a></span>
					</th>
					<td>
						<fieldset>
							<legend class="screen-reader-text">
								<span>Booking Engine display style</span>
							</legend>
							<?php
								(get_option('tourpress_bookstyle')=="") ? $bookstyle = "popup" : $bookstyle = get_option('tourpress_bookstyle');
							?>
							<label title="off"><input type="radio" name="tourpress_bookstyle" value="off" <?php echo ($bookstyle=="off" ? 'checked="checked"' : null); ?>/> Booking Engine Off</label><br />
							<label title="link"><input type="radio" name="tourpress_bookstyle" value="link" <?php echo ($bookstyle=="link" ? 'checked="checked"' : null); ?>/> Standard Link</label><br />
							<label title="popup"><input type="radio" name="tourpress_bookstyle" value="popup" <?php echo ($bookstyle=="popup" ? 'checked="checked"' : null); ?>/> Popup Window</label><br />
							<label title="iframe"><input type="radio" name="tourpress_bookstyle" value="iframe" <?php echo ($bookstyle=="iframe" ? 'checked="checked"' : null); ?>/> Iframe</label>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="tourpress_booktext">Text</label>
					</th>
					<td>
						<input type="text" name="tourpress_booktext" value="<?php echo (get_option('tourpress_booktext')=="") ? __( 'Book Online', 'tourpress' ) : get_option('tourpress_booktext'); ?>" placeholder='e.g. "Book Online"' />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Height<br />
						<span class="description">(Iframe &amp; Popup Window)</span>
					</th>
					<td>
						<input type="text" size="4" name="tourpress_bookheight" value="<?php echo (get_option('tourpress_bookheight')=="") ? "700" : get_option('tourpress_bookheight'); ?>" placeholder='e.g. "700"' /> <span class="description">px</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Width<br />
						<span class="description">(Popup Window only)</span>
					</th>
					<td>
						<input type="text" size="4" name="tourpress_bookwidth" value="<?php echo (get_option('tourpress_bookwidth')=="") ? "700" : get_option('tourpress_bookwidth'); ?>" /> <span class="description">px</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Extra Query String Parameters<br />
						<span class="description"><a href="http://www.tourpress.com/support/setup/booking_engine/integration_parameters.php" target="_blank">What's this?</a></span>
					</th>
					<td>
						<input type="text" size="30" name="tourpress_bookqs" value="<?php echo (get_option('tourpress_bookqs')=="") ? "" : get_option('tourpress_bookqs'); ?>" placeholder='e.g. "&people=0&month_year=12_2012"' /> <span class="description">Probably leave this blank</span>
					</td>
				</tr>					
			</table>
			
			
			<h3>Cache Settings</h3>
			<p>When you save a Product inside WordPress the plugin will get the latest content for that product from TourPress. It's also possible to update the content automatically if a Product is viewed on your site and hasn't been updated for a while.</p>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						Update TourPress Content
					</th>
					<td>
						<?php
							(get_option('tourpress_update_frequency')=="") ? $update_frequency = 14400 : $update_frequency = intval(get_option('tourpress_update_frequency'));
						?>
						<select name="tourpress_update_frequency">
							<option value="-1"<?php $update_frequency==-1 ? print ' selected="selected"' : null; ?>>Only when I edit the Product in WordPress</option>
							<option value="86400"<?php $update_frequency==86400 ? print ' selected="selected"' : null; ?>>After 24 hours</option>
							<option value="14400"<?php $update_frequency==14400 ? print ' selected="selected"' : null; ?>>After 4 hours [Default]</option>
							<option value="3600"<?php $update_frequency==3600 ? print ' selected="selected"' : null; ?>>After 1 hour</option>
							<option value="0"<?php $update_frequency==0 ? print ' selected="selected"' : null; ?>>Constantly (Don't cache)</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Unlinked TourPress Products
					</th>
					<td>
						<?php
							$unlinked_products = get_option('tourpress_unlinked_products');
						?>
						<input type="checkbox" name="tourpress_unlinked_products" value="1"
						<?php $unlinked_products == 1 ? print 'checked' : null; ?>/>
						<span class="description">Check if you want to create or temporarily unlink products</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Last Product Refresh
					</th>
					<td>
						<input type="date" name="tourpress_last_product_cache" 
						value="<?php echo get_option('tourpress_last_product_cache'); ?>"/>
						<span class="description">Change the date if you want to refresh TourPress products amended from this date</span>
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