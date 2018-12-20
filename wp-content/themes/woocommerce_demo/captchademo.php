<?php
/*
 Template Name: Captcha template
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">
			<style>
        #bootstrapCaptchaDiv,#bootstrapCaptchaDiv.hide {
            display: block;
        }
    </style>
		<script src="http://code.jquery.com/jquery-latest.js"></script>
		<script src="http://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script>
		<script src="<?php echo get_template_directory_uri(); ?>/js/bootstrap-captcha.js"></script> 
		<script type="text/javascript">
			jQuery(document).ready(function () {
                           jQuery('#someDiv').bootstrapCaptcha({
                                user: 'user',
                                options: 'options',
                                here: 'here'
                           });
                           jQuery('#bootstrapCaptchaDiv').removeClass('hide');
                         });
		</script>
		    <div id="someDiv">
		</div>
		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
