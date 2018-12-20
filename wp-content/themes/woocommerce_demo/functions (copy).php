<?php
/**
 * Twenty Twelve functions and definitions
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see https://codex.wordpress.org/Theme_Development and
 * https://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, @link https://codex.wordpress.org/Plugin_API
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

// Set up the content width value based on the theme's design and stylesheet.
if ( ! isset( $content_width ) )
	$content_width = 625;

/**
 * Twenty Twelve setup.
 *
 * Sets up theme defaults and registers the various WordPress features that
 * Twenty Twelve supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
 * 	custom background, and post formats.
 * @uses register_nav_menu() To add support for navigation menus.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_setup() {
	/*
	 * Makes Twenty Twelve available for translation.
	 *
	 * Translations can be filed at WordPress.org. See: https://translate.wordpress.org/projects/wp-themes/twentytwelve
	 * If you're building a theme based on Twenty Twelve, use a find and replace
	 * to change 'twentytwelve' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'twentytwelve' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// This theme supports a variety of post formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'link', 'quote', 'status' ) );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Primary Menu', 'twentytwelve' ) );
	register_nav_menu( 'secondary', __( 'Secondary Menu', 'twentytwelve' ) );

	/*
	 * This theme supports custom background color and image,
	 * and here we also set up the default background color.
	 */
	add_theme_support( 'custom-background', array(
		'default-color' => 'e6e6e6',
	) );

	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 624, 9999 ); // Unlimited height, soft crop

	// Indicate widget sidebars can use selective refresh in the Customizer.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
add_action( 'after_setup_theme', 'twentytwelve_setup' );

/**
 * Add support for a custom header image.
 */
require( get_template_directory() . '/inc/custom-header.php' );

/**
 * Return the Google font stylesheet URL if available.
 *
 * The use of Open Sans by default is localized. For languages that use
 * characters not supported by the font, the font can be disabled.
 *
 * @since Twenty Twelve 1.2
 *
 * @return string Font stylesheet or empty string if disabled.
 */
function twentytwelve_get_font_url() {
	$font_url = '';

	/* translators: If there are characters in your language that are not supported
	 * by Open Sans, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'twentytwelve' ) ) {
		$subsets = 'latin,latin-ext';

		/* translators: To add an additional Open Sans character subset specific to your language,
		 * translate this to 'greek', 'cyrillic' or 'vietnamese'. Do not translate into your own language.
		 */
		$subset = _x( 'no-subset', 'Open Sans font: add new subset (greek, cyrillic, vietnamese)', 'twentytwelve' );

		if ( 'cyrillic' == $subset )
			$subsets .= ',cyrillic,cyrillic-ext';
		elseif ( 'greek' == $subset )
			$subsets .= ',greek,greek-ext';
		elseif ( 'vietnamese' == $subset )
			$subsets .= ',vietnamese';

		$query_args = array(
			'family' => 'Open+Sans:400italic,700italic,400,700',
			'subset' => $subsets,
		);
		$font_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $font_url;
}

/**
 * Enqueue scripts and styles for front end.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_scripts_styles() {
	global $wp_styles;

	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	// Adds JavaScript for handling the navigation menu hide-and-show behavior.
	wp_enqueue_script( 'twentytwelve-navigation', get_template_directory_uri() . '/js/navigation.js', array( 'jquery' ), '20140711', true );

	$font_url = twentytwelve_get_font_url();
	if ( ! empty( $font_url ) )
		wp_enqueue_style( 'twentytwelve-fonts', esc_url_raw( $font_url ), array(), null );

	// Loads our main stylesheet.
	wp_enqueue_style( 'twentytwelve-style', get_stylesheet_uri() );

	// Loads the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'twentytwelve-ie', get_template_directory_uri() . '/css/ie.css', array( 'twentytwelve-style' ), '20121010' );
	$wp_styles->add_data( 'twentytwelve-ie', 'conditional', 'lt IE 9' );
}
add_action( 'wp_enqueue_scripts', 'twentytwelve_scripts_styles' );

/**
 * Add preconnect for Google Fonts.
 *
 * @since Twenty Twelve 2.2
 *
 * @param array   $urls          URLs to print for resource hints.
 * @param string  $relation_type The relation type the URLs are printed.
 * @return array URLs to print for resource hints.
 */
function twentytwelve_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'twentytwelve-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		if ( version_compare( $GLOBALS['wp_version'], '4.7-alpha', '>=' ) ) {
			$urls[] = array(
				'href' => 'https://fonts.gstatic.com',
				'crossorigin',
			);
		} else {
			$urls[] = 'https://fonts.gstatic.com';
		}
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'twentytwelve_resource_hints', 10, 2 );

/**
 * Filter TinyMCE CSS path to include Google Fonts.
 *
 * Adds additional stylesheets to the TinyMCE editor if needed.
 *
 * @uses twentytwelve_get_font_url() To get the Google Font stylesheet URL.
 *
 * @since Twenty Twelve 1.2
 *
 * @param string $mce_css CSS path to load in TinyMCE.
 * @return string Filtered CSS path.
 */
function twentytwelve_mce_css( $mce_css ) {
	$font_url = twentytwelve_get_font_url();

	if ( empty( $font_url ) )
		return $mce_css;

	if ( ! empty( $mce_css ) )
		$mce_css .= ',';

	$mce_css .= esc_url_raw( str_replace( ',', '%2C', $font_url ) );

	return $mce_css;
}
add_filter( 'mce_css', 'twentytwelve_mce_css' );

/**
 * Filter the page title.
 *
 * Creates a nicely formatted and more specific title element text
 * for output in head of document, based on current view.
 *
 * @since Twenty Twelve 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string Filtered title.
 */
function twentytwelve_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'twentytwelve' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'twentytwelve_wp_title', 10, 2 );

/**
 * Filter the page menu arguments.
 *
 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_page_menu_args( $args ) {
	if ( ! isset( $args['show_home'] ) )
		$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'twentytwelve_page_menu_args' );

/**
 * Register sidebars.
 *
 * Registers our main widget area and the front page widget areas.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'twentytwelve' ),
		'id' => 'sidebar-1',
		'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'First Front Page Widget Area', 'twentytwelve' ),
		'id' => 'sidebar-2',
		'description' => __( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Second Front Page Widget Area', 'twentytwelve' ),
		'id' => 'sidebar-3',
		'description' => __( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'twentytwelve_widgets_init' );

if ( ! function_exists( 'twentytwelve_content_nav' ) ) :
/**
 * Displays navigation to next/previous pages when applicable.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_content_nav( $html_id ) {
	global $wp_query;

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo esc_attr( $html_id ); ?>" class="navigation" role="navigation">
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentytwelve' ); ?></h3>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentytwelve' ) ); ?></div>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentytwelve' ) ); ?></div>
		</nav><!-- .navigation -->
	<?php endif;
}
endif;

if ( ! function_exists( 'twentytwelve_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own twentytwelve_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:', 'twentytwelve' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'twentytwelve' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
		// Proceed with normal comments.
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<header class="comment-meta comment-author vcard">
				<?php
					echo get_avatar( $comment, 44 );
					printf( '<cite><b class="fn">%1$s</b> %2$s</cite>',
						get_comment_author_link(),
						// If current post author is also comment author, make it known visually.
						( $comment->user_id === $post->post_author ) ? '<span>' . __( 'Post author', 'twentytwelve' ) . '</span>' : ''
					);
					printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
						esc_url( get_comment_link( $comment->comment_ID ) ),
						get_comment_time( 'c' ),
						/* translators: 1: date, 2: time */
						sprintf( __( '%1$s at %2$s', 'twentytwelve' ), get_comment_date(), get_comment_time() )
					);
				?>
			</header><!-- .comment-meta -->

			<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'twentytwelve' ); ?></p>
			<?php endif; ?>

			<section class="comment-content comment">
				<?php comment_text(); ?>
				<?php edit_comment_link( __( 'Edit', 'twentytwelve' ), '<p class="edit-link">', '</p>' ); ?>
			</section><!-- .comment-content -->

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'twentytwelve' ), 'after' => ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->
	<?php
		break;
	endswitch; // end comment_type check
}
endif;

if ( ! function_exists( 'twentytwelve_entry_meta' ) ) :
/**
 * Set up post entry meta.
 *
 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own twentytwelve_entry_meta() to override in a child theme.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_entry_meta() {
	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'twentytwelve' ) );

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'twentytwelve' ) );

	$date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'twentytwelve' ), get_the_author() ) ),
		get_the_author()
	);

	// Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
	if ( $tag_list ) {
		$utility_text = __( 'This entry was posted in %1$s and tagged %2$s on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve' );
	} elseif ( $categories_list ) {
		$utility_text = __( 'This entry was posted in %1$s on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve' );
	} else {
		$utility_text = __( 'This entry was posted on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve' );
	}

	printf(
		$utility_text,
		$categories_list,
		$tag_list,
		$date,
		$author
	);
}
endif;

/**
 * Extend the default WordPress body classes.
 *
 * Extends the default WordPress body class to denote:
 * 1. Using a full-width layout, when no active widgets in the sidebar
 *    or full-width template.
 * 2. Front Page template: thumbnail in use and number of sidebars for
 *    widget areas.
 * 3. White or empty background color to change the layout and spacing.
 * 4. Custom fonts enabled.
 * 5. Single or multiple authors.
 *
 * @since Twenty Twelve 1.0
 *
 * @param array $classes Existing class values.
 * @return array Filtered class values.
 */
function twentytwelve_body_class( $classes ) {
	$background_color = get_background_color();
	$background_image = get_background_image();

	if ( ! is_active_sidebar( 'sidebar-1' ) || is_page_template( 'page-templates/full-width.php' ) )
		$classes[] = 'full-width';

	if ( is_page_template( 'page-templates/front-page.php' ) ) {
		$classes[] = 'template-front-page';
		if ( has_post_thumbnail() )
			$classes[] = 'has-post-thumbnail';
		if ( is_active_sidebar( 'sidebar-2' ) && is_active_sidebar( 'sidebar-3' ) )
			$classes[] = 'two-sidebars';
	}

	if ( empty( $background_image ) ) {
		if ( empty( $background_color ) )
			$classes[] = 'custom-background-empty';
		elseif ( in_array( $background_color, array( 'fff', 'ffffff' ) ) )
			$classes[] = 'custom-background-white';
	}

	// Enable custom font class only if the font CSS is queued to load.
	if ( wp_style_is( 'twentytwelve-fonts', 'queue' ) )
		$classes[] = 'custom-font-enabled';

	if ( ! is_multi_author() )
		$classes[] = 'single-author';

	return $classes;
}
add_filter( 'body_class', 'twentytwelve_body_class' );

/**
 * Adjust content width in certain contexts.
 *
 * Adjusts content_width value for full-width and single image attachment
 * templates, and when there are no active widgets in the sidebar.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_content_width() {
	if ( is_page_template( 'page-templates/full-width.php' ) || is_attachment() || ! is_active_sidebar( 'sidebar-1' ) ) {
		global $content_width;
		$content_width = 960;
	}
}
add_action( 'template_redirect', 'twentytwelve_content_width' );

/**
 * Register postMessage support.
 *
 * Add postMessage support for site title and description for the Customizer.
 *
 * @since Twenty Twelve 1.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function twentytwelve_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector' => '.site-title > a',
			'container_inclusive' => false,
			'render_callback' => 'twentytwelve_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector' => '.site-description',
			'container_inclusive' => false,
			'render_callback' => 'twentytwelve_customize_partial_blogdescription',
		) );
	}
}
add_action( 'customize_register', 'twentytwelve_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @since Twenty Twelve 2.0
 * @see twentytwelve_customize_register()
 *
 * @return void
 */
function twentytwelve_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @since Twenty Twelve 2.0
 * @see twentytwelve_customize_register()
 *
 * @return void
 */
function twentytwelve_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Enqueue Javascript postMessage handlers for the Customizer.
 *
 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_customize_preview_js() {
	wp_enqueue_script( 'twentytwelve-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), '20141120', true );
}
add_action( 'customize_preview_init', 'twentytwelve_customize_preview_js' );


/**
 * Modifies tag cloud widget arguments to display all tags in the same font size
 * and use list format for better accessibility.
 *
 * @since Twenty Twelve 2.4
 *
 * @param array $args Arguments for tag cloud widget.
 * @return array The filtered arguments for tag cloud widget.
 */
function twentytwelve_widget_tag_cloud_args( $args ) {
	$args['largest']  = 22;
	$args['smallest'] = 8;
	$args['unit']     = 'pt';
	$args['format']   = 'list';

	return $args;
}
add_filter( 'widget_tag_cloud_args', 'twentytwelve_widget_tag_cloud_args' );

//change title of shop page
add_filter( 'woocommerce_page_title', 'woo_shop_page_title');
           function woo_shop_page_title( $page_title ) {
                      if( 'Shop' == $page_title) {
                                   return "My Store";
                         }
            }
            

//change no of columns

add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 4; // 3 products per row
	}
}

// remove default sorting in shop page
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

//add custom post
function create_post_type() {
  register_post_type( 'acme_product',
    array(
      'labels' => array(
        'name' => __( 'Products' ),
        'singular_name' => __( 'Product' )
      ),
      'public' => true,
      'has_archive' => true,
      'supports'            => array( 'title', 'editor', 'excerpt',  'thumbnail',  ),
    )
  );
}
add_action( 'init', 'create_post_type' );

/**
 * Registers the event post type.
 */
function wpt_event_post_type() {
	$labels = array(
		'name'               => __( 'Events	' ),
		'singular_name'      => __( 'Event' ),
		'add_new'            => __( 'Add New Event' ),
		'add_new_item'       => __( 'Add New Event' ),
		'edit_item'          => __( 'Edit Event' ),
		'new_item'           => __( 'Add New Event' ),
		'view_item'          => __( 'View Event' ),
		'search_items'       => __( 'Search Event' ),
		'not_found'          => __( 'No events found' ),
		'not_found_in_trash' => __( 'No events found in trash' )
	);
	$supports = array(
		'title',
		'editor',
		'thumbnail',
		'comments',
		'revisions',
	);
	$args = array(
		'labels'               => $labels,
		'supports'             => $supports,
		'public'               => true,
		'capability_type'      => 'post',
		'rewrite'              => array( 'slug' => 'events' ),
		'has_archive'          => true,
		'menu_position'        => 30,
		'menu_icon'            => 'dashicons-calendar-alt',
		'register_meta_box_cb' => 'wpt_add_event_metaboxes_v2',
	);
	register_post_type( 'events', $args );
}
add_action( 'init', 'wpt_event_post_type' );

/**
 * If you wanted to have two sets of metaboxes.
 */
function wpt_add_event_metaboxes_v2() {
	add_meta_box(
		'wpt_events_date',
		'Event Date',
		'wpt_events_date',
		'events',
		'side',
		'default'
	);
	add_meta_box(
		'wpt_events_location',
		'Event Location',
		'wpt_events_location',
		'events',
		'normal',
		'high'
	);
}

// upload file

function upload_user_file( $file = array(),$path ) {
    if(!empty($file)) 
    {
        $upload_dir=$path;
        $uploaded=move_uploaded_file($file['tmp_name'], $upload_dir.$file['name']);
        if($uploaded) 
        {
            echo "uploaded successfully ";           
			redirect(site_url()."/index.php/shop/");
        }else
        {
            echo "some error in upload " ;print_r($file['error']);  
        }
    }

}

function checkSku($sku){
		global $wpdb;
		$sql = "SELECT wp.ID,wp.post_author FROM `wp_posts` wp LEFT JOIN wp_postmeta wpm ON wp.ID=wpm.post_id WHERE wp.post_type='product' AND wp.post_status='pending' AND wpm.meta_key='_sku' AND wpm.meta_value like '$sku'";
		$results = $wpdb->get_results($sql); 		
		return $results;	
}

//Function for page redirect
function redirect($url){
    $string = '<script type="text/javascript">';
    $string .= 'window.location = "' . $url . '"';
    $string .= '</script>';
    echo $string;
}

// add author column to woocommerce product admin screen
add_action('init', 'wpse_74054_add_author_woocommerce', 999 );
function wpse_74054_add_author_woocommerce() {
    add_post_type_support( 'product', 'author' );
}

//custom menu for pending products

add_action( 'admin_menu', 'addAdminMenu' );
function addAdminMenu(){
	 global $wpdb;
	 $current_user = wp_get_current_user();
	 
	 //$not_Approved = $wpdb->query("SELECT * FROM vp_employements  where job_status = '0' AND expiredate >= curdate()");
	 $count = '';
	 /*if($not_Approved){
	  $count = "<span class='update-plugins count-1'><span class='update-count'>$not_Approved </span></span>";
	 }*/

	 if ( !($current_user instanceof WP_User) )
		return;

	 if (isset( $current_user->roles[0] ) && ($current_user->roles[0]=='administrator' || $current_user->roles[0]=='vitalPartnersadmin')) {
	  $capability = 'manage_options';
	 }else{
	  $capability = 'organize_shop';
	 }
	 if($current_user->roles[0] == 'editor'){
	  $capability = 'ourlatestpost';
	 } 
	 add_menu_page('Pending Products', 'Pending Products', $capability, 'pending-products', 'viewPendingProducts');
	 add_submenu_page(Null,'View Product','View Product','manage_options','view-product','viewProduct');
 }
 
 function viewPendingProducts(){
	include("pending-products.php");
}
 function viewProduct(){
	include("pending-products-view.php");
}
//
add_filter('query_vars', 'add_my_var');
function add_my_var($public_query_vars) {
    $public_query_vars[] = 'some_unique_identifier_for_your_var';
    return $public_query_vars;
}

// add custom fields for color,brand and memory in woocommerce product
add_action( 'woocommerce_product_options_general_product_data', 'woo_add_custom_general_fields' );
add_action( 'woocommerce_process_product_meta', 'woo_add_custom_general_fields_save' );
function woo_add_custom_general_fields() {

  global $woocommerce, $post;
  echo '<div class="options_group">';  
  echo '<p>Color:<input type="text" name="_product_color" value=""></p>';
  echo '<p>Memory:<input type="text" name="_product_memory" value=""></p>';
  echo '<p>Brand:<input type="text" name="_product_brand" value=""></p>';
  echo '<p>Vendor Quantity:<input type="text" name="_vendor_quantity" value=""></p>';
  echo '<p>Last Vendor Order:<input type="text" name="_last_vendor" value=""></p>';
  echo '<p>Vendor email :<input type="text" name="_vendor_email" value=""></p>';
  // Custom fields will be created here...
  
  echo '</div>';
	
}

add_action( 'wp_ajax_load_variations', 'prefix_ajax_load_variations' );
//add_action( 'wp_ajax_nopriv_load_variations', 'prefix_ajax_load_variations' );
function prefix_ajax_load_variations() {
$productid= $_POST['data']['productid']; // bar
$_product = wc_get_product( $productid );
if(isset($productid) && !empty($productid)){
if( $_product->is_type( 'variable' ) ) {
	$options_color = array('hide_empty' => false);
	$terms_color=get_terms('pa_color',$options_color);
	echo "<select id='color' name='color'>";
	echo "<option value='select color'>Select Color</option>";
	foreach ($terms_color as $each_term_color) {
	echo '<option value="'.$each_term_color->slug.'">'.$each_term_color->name.'</option>';
} 
print "</select>";

$options_memory = array('hide_empty' => false);
$terms_memory=get_terms('pa_phone-capacity',$options_memory);
echo "<select id='memory' name='memory'>";
echo "<option value='select memory'>Select Memory</option>";
foreach ($terms_memory as $each_term_memory) {
echo '<option value="'.$each_term_memory->slug.'">'.$each_term_memory->name.'</option>';
} 
print "</select>";


$options_capacity = array('hide_empty' => false);
$terms_capacity=get_terms('pa_brand',$options_capacity);
echo "<select id='brand' name='brand'>";
echo "<option value='select brand'>Select Brand</option>";
foreach ($terms_capacity as $each_term_capacity) {
echo '<option value="'.$each_term_capacity->slug.'">'.$each_term_capacity->name.'</option>';
} 
print "</select>";
}
}
   wp_die();
}

function my_enqueue() {
    
//wp_enqueue_script( 'theme_jquery_js', "https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js",array(),NULL,true);
    
    $custom_javascript_file = get_stylesheet_directory_uri().'/js/script.js';
	wp_enqueue_script( 'my-js', $custom_javascript_file,array('theme_jquery_js'),NULL,true);

}

add_action( 'wp_enqueue_scripts', 'my_enqueue' );

function admin_enqueue_script_include_function(){
	
	 $custom_javascript_file = get_stylesheet_directory_uri().'/js/script.js';
	wp_enqueue_script( 'my-js', $custom_javascript_file,array(),NULL,true);
		
}


add_action( 'admin_enqueue_scripts', 'admin_enqueue_script_include_function' );


add_action('wp_head', 'myplugin_ajaxurl');

function myplugin_ajaxurl() {
    echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}
//check product id that are ordered
add_action( 'woocommerce_thankyou', 'bbloomer_check_order_product_id');
  
function bbloomer_check_order_product_id( $order_id ){
	//echo $order_id;
$order = new WC_Order( $order_id );
//print_r($order);
//
//echo "#".$a."###";
$items = $order->get_items();
//print_r($items); 
	foreach ( $items as $item ) {

	   $v_product_id=$item['variation_id'];	   
	   $check_pid=get_post_field('post_parent', $v_product_id);		
	  
	   if($check_pid==0){
			$product_id = $item['product_id'];
			$order_product_qty=$item['quantity'];
			$checked_vendor=get_post_meta($product_id,'_vendor_quantity',true);
			$count_v=count($checked_vendor)-1;						
			$get_last_vendor=get_post_meta($product_id,'_last_vendor',true);
			$i=array_search($get_last_vendor,array_keys($checked_vendor));						
			//echo "%%%%".$i;
			
			/* vendor data function call */
			$data=vendorData($product_id,$i,$flag=1,$checked_vendor,$count_v);
			$key=$data[0];	
			//echo $key;
			
			$checked_vendor=$data[2];			
			$allKeys = array_keys($checked_vendor);
			$count_v=$data[3];
			/*check qty start */
			a:
			$v_qty=$checked_vendor[$key]['qty'];
				if($v_qty>=$order_product_qty){
					$key=$key;	
					echo $key;				
				}else{
					$i=array_search($key,array_keys($checked_vendor));
					$data1=vendorData($product_id,$i,$flag=0,$checked_vendor,$count_v);
					$key=$data1[0];
					goto a;
				}				
			/*check qty end */
			/* common code start*/
			if(isset($key) && isset($flag)){
				//echo $flag;				
				$v_qty=$checked_vendor[$key]['qty'];
				$n_v_qty=$v_qty-$order_product_qty;									
				$vendor_info = get_userdata($key);						
				echo $vendor_email=$vendor_info->user_email."<br>";
				echo $vendor_name=$vendor_info->display_name."<br>";				
				update_post_meta($item['order_id'],'_vendor_email',$key);					
				if($flag==1){				
				update_post_meta( $product_id, '_last_vendor',$key);
				}
				if(isset($checked_vendor[$key]['qty'])){
					$checked_vendor[$key]['qty']=$n_v_qty;
					echo "%%%".$checked_vendor[$key]['qty']."%%%<br>";								
				}									
				update_post_meta($product_id,'_vendor_quantity',$checked_vendor);																		
			}
			/* common code end */
	   }else{ 
		 $order_product_qty=$item['quantity'];	
		 $checked_vendor=get_post_meta($v_product_id,'_vendor_quantity',true);
		 $count_v=count($checked_vendor)-1;						
		 $get_last_vendor=get_post_meta($v_product_id,'_last_vendor',true);
		 $i=array_search($get_last_vendor,array_keys($checked_vendor));						
		 /* vendor data function call */
			$data=vendorData($v_product_id,$i,$flag=1,$checked_vendor,$count_v);
			$key=$data[0];	
			$checked_vendor=$data[2];
			$allKeys = array_keys($checked_vendor);
			$count_v=$data[3];
			/*check qty start */
			b:
			$v_qty=$checked_vendor[$key]['qty'];
				if($v_qty>=$order_product_qty){
					$key=$key;	
					//echo $key;				
				}else{
					$i=array_search($key,array_keys($checked_vendor));
					$data1=vendorData($v_product_id,$i,$flag=0,$checked_vendor,$count_v);
					$key=$data1[0];
					goto b;
				}	
				
			/*check qty end */
			/* common code start*/
			if(isset($key) && isset($flag)){
				//echo $flag;				
				$v_qty=$checked_vendor[$key]['qty'];
				$n_v_qty=$v_qty-$order_product_qty;									
				$vendor_info = get_userdata($key);		
				echo $vendor_email=$vendor_info->user_email."<br>";
				echo $vendor_name=$vendor_info->display_name."<br>";
				update_post_meta($item['order_id'],'_vendor_email',$key);					
				if($flag==1){				
				update_post_meta( $v_product_id, '_last_vendor',$key);
				}
				if(isset($checked_vendor[$key]['qty'])){
					$checked_vendor[$key]['qty']=$n_v_qty;
					echo "%%%".$checked_vendor[$key]['qty']."%%%<br>";								
				}									
				update_post_meta($v_product_id,'_vendor_quantity',$checked_vendor);																		
			}
			/* common code end */		
	   }

	} 
    
    
}
/* Adding custom WooCommerce product vendor field start */
add_filter( 'woocommerce_product_data_tabs', 'add_my_custom_vendor_data_tab' );
function add_my_custom_vendor_data_tab( $product_data_tabs ) {
    $product_data_tabs['vendors'] = array(
        'label' => __( 'Vendors', 'woocommerce' ),
        'target' => 'vendors_data',
        
    );
    return $product_data_tabs;
}
/* Adding custom WooCommerce product vendor field end */

/*  Vendor panel data start. */
add_action('woocommerce_product_data_panels', 'woocom_vendors_data');

function woocom_vendors_data() {
    global $post;

    // Note the 'id' attribute needs to match the 'target' parameter set above
    ?> <div id = 'vendors_data'
    class = 'panel woocommerce_options_panel' > <?php
        ?> <div class = 'options_group' > <?php
			 global $wpdb;
             $postid=$post->ID;
             $_product = wc_get_product( $postid );              
              if( $_product->is_type('simple' ) ) 
             {
				 $all_vendors=get_post_meta($postid,'_vendor_quantity',true);
				   if(!empty($all_vendors)){
					//print_r($all_vendors);
					echo "<table border=1 align='center'  width='100%'>";
					echo "<tr><th>Vendor Name</th><th>Quantity</th><th>Price</th></tr>";
					//echo "s=".$postid;
						 foreach($all_vendors as $k=>$v){
							$v_info = get_userdata($k);						
							$v_name=$v_info->display_name;
							echo "<tr><td>".$v_name."</td><td>".$v['qty']."</td><td>".$v['wprice']."</td></tr>";
						}
					echo "</table>";
					}
			 }
			 if( $_product->is_type('variable' ) ) 
             {				 
				$qry = "select * from wp_posts where post_type='product_variation' AND post_status='publish' and post_parent=".$postid;
				$results = $wpdb->get_results($qry); 			
					echo "<table border=1 align='center'  width='100%'>";
					echo "<tr><th>Vendor Name</th><th>Quantity</th><th>Price</th><th>Attributes</th></tr>";
					foreach($results as $result){
						$color=get_post_meta($result->ID,'attribute_pa_color',true );
						$memory=get_post_meta($result->ID,'attribute_pa_phone-capacity',true );
						$condition=get_post_meta($result->ID,'attribute_pa_device-condition',true );
						$brand=get_post_meta($result->ID,'attribute_pa_brand',true);
						$attributes=$color.",".$memory.",".$brand;
						$all_vendors=get_post_meta($result->ID,'_vendor_quantity',true);
						if(!empty($all_vendors)){							
							foreach($all_vendors as $k=>$v){
								$v_info = get_userdata($k);						
								$v_name=$v_info->display_name;
								echo "<tr><td>".$v_name."</td><td>".$v['qty']."</td><td>".$v['wprice']."</td><td>".$attributes."</td></tr>";
							}
							
						}else{ echo "<tr><td colspan='4'>No data found.</td></tr>"; }					
					}
					echo "</table>";
            }                      
           ?>
        </div>
    </div><?php
}
/* Vendor panel data end. */

/* merge vendor qty function start */
function merge_vendor_qty($a,$b){
$all=$a+$b;
$ca=count($a);
$cb=count($b);
if($ca>$cb){
	foreach($a as $k=>$v){
		foreach($b as $kk=>$vv){
			if($k==$kk){
				$all[$k]['qty']=$all[$k]['qty']+$vv['qty'];
			}
		}
	}
}if($cb>$ca){
	foreach($b as $k=>$v){
		foreach($a as $kk=>$vv){
			if($k==$kk){
				$all[$k]['qty']=$all[$k]['qty']+$v['qty'];
			}
		}
	}
}
if($ca==$cb){
	foreach($a as $k=>$v){
		foreach($b as $kk=>$vv){
			if($k==$kk){
				$all[$k]['qty']=$all[$k]['qty']+$vv['qty'];
			}
		}
	}
}
return $all;
}
/* merge vendor qty function end */

/* vendor function for maintain product start */
function vendorData($product_id,$i,$flag,$checked_vendor,$count_v){

	$i++;
	//echo $i;
	if($i<=$count_v){
	$allKeys = array_keys($checked_vendor);
	$key=$allKeys[$i];	
	}else{
	reset($checked_vendor);
	$key = key($checked_vendor);
	}	
	return array($key,$i,$checked_vendor,$count_v);
}
/* vendor function for maintain product end */


/* only one product in cart
add_filter( 'woocommerce_add_cart_item_data', 'woo_custom_add_to_cart' );
 
function woo_custom_add_to_cart( $cart_item_data ) {
global $woocommerce;
$woocommerce->cart->empty_cart();
 
return $cart_item_data;
}
/* woocommerce order statues*/

add_filter( 'woocommerce_add_to_cart_validation', 'only_one_items_allowed_add_to_cart', 10, 3 );

function only_one_items_allowed_add_to_cart( $passed, $product_id, $quantity) {	
$carturl=site_url()."/index.php/cart";
    $cart_items_count = WC()->cart->get_cart_contents_count();    
    //$total_count = $cart_items_count + $quantity;
    if( $cart_items_count >=1){
        // Set to false
        $passed = false;
        // Display a message
         wc_add_notice( __("You can’t have more than one items in cart <a href='".$carturl."'>Back to cart</a>", "woocommerce" ), "error" );
    }   
    
    $v_product_id=$_POST['variation_id'];
	if(empty($v_product_id))
	{
		$p_id=$product_id;
	}else{
		$p_id=$v_product_id;
	}
	
    
    $vendor_qty=get_post_meta($p_id,'_vendor_quantity',true);
    $c=0;
    foreach($vendor_qty as $vkey => $vvalue){
		if($vvalue['qty']>=$quantity){
			$c++;
		}
	}
   if($c==0){
        // Set to false
        $passed = false;
        // Display a message
         wc_add_notice( __("Not enough quantity.", "woocommerce" ), "error" );
    }
    return $passed;
}



add_action('woocommerce_check_cart_items', 'validate_cart');

function validate_cart(){
	global $woocommerce;
     $quantity=$woocommerce->cart->cart_contents_count;
     foreach( WC()->cart->get_cart() as $cart_item ){
		$v_product_id = $cart_item['variation_id'];
	 }
	 $check_pid=get_post_field('post_parent', $v_product_id);			  
	 if($check_pid==0){
		 $product_id=$cart_item['product_id'];
	 }else{
		 $product_id=$cart_item['variation_id'];
	 }
	 $vendor_qty=get_post_meta($product_id,'_vendor_quantity',true);
	 $c=0;
    foreach($vendor_qty as $vkey => $vvalue){
		if($vvalue['qty']>=$quantity){
			$c++;
		}
	}	 
        if($c>0)
            return true;
        else
             wc_add_notice( __($product_id."qantity.", "woocommerce" ), "error" );
        
   
}
/* if not enough qty then don't display add to cart start 
 
 if (!function_exists('woocommerce_template_loop_add_to_cart')) {
    function woocommerce_template_loop_add_to_cart($product) {
        global $product;
        $check_pid=$product->id;
        echo $check_pid;
        $vendor_qty=get_post_meta($check_pid,'_vendor_qty',true);
        print_r($vendor_qty);
        //echo $product['post_type'];
		//print_r($product);
        if (  $product->is_in_stock() || $product->is_purchasable() ) return;
        woocommerce_get_template('loop/add-to-cart.php');
    }
}

/* if not enough qty then don't display add to cart end */


/* hide "n in stock" in product page start */
function my_wc_hide_in_stock_message( $html, $text, $product ) {
	$availability = $product->get_availability();
	if ( isset( $availability['class'] ) && 'in-stock' === $availability['class'] ) {
		return '';
	}
	return $html;
}
add_filter( 'woocommerce_stock_html', 'my_wc_hide_in_stock_message', 10, 3 );

/* hide "n in stock" in product page end */

function textdomain_body_classes( $classes ) {
     
    $classes[] = 'woocommerce-account';   
     
    return $classes;
}
add_filter( 'body_class', 'textdomain_body_classes' );


/* show hide menus based on condition start */
add_filter( 'if_menu_conditions', 'wpb_new_menu_conditions' );
 
function wpb_new_menu_conditions( $conditions ) {
  $conditions[] = array(
    'name'    =>  'If Payment done', // name of the condition
    'condition' =>  function($item) {     
		
		$logged_in_user_id = get_current_user_id();

		if($logged_in_user_id !=0){

			/* raw query 
			
			SELECT * FROM `wp_pmpro_membership_orders` WHERE status='success' AND timestamp > '2018-04-08 00:00:00'
			

			*/

			global $wpdb;

			$query = "SELECT * FROM {$wpdb->prefix}pmpro_membership_orders WHERE status='success'  AND timestamp > '2018-03-01 00:00:00' AND membership_id =2 AND user_id = $logged_in_user_id ";
			
			//echo $query;

			$results = $wpdb->get_results($query,OBJECT);
			
			if(count($results)>0){  return true;  }else{ return false;	}

		}else{
		    return false;
		
		}
		
    }
  );
 
  return $conditions;
}


/* show hide menus based on condition end */
add_filter('wp_link_pages_args', 'wp_link_pages_args_prevnext_add');

function wp_link_pages_args_prevnext_add($args)
{
    global $page, $numpages, $more, $pagenow;
//print_r($args);
   // echo $args['before'];
    if($args['next_or_number']=='number'){
		$args['next_or_number']='';
	}
	if($args['before']=='<p>' . __( 'Pages:' )){
		$args['before']='<p align="center">' . __( '' );
	}
	if($args['nextpagelink']=='Next page'){
		$args['nextpagelink']='Next >> ';
	}
	if($args['previouspagelink']=='Previous page'){
		$args['previouspagelink']='<< Previous';
	}
	if($args['separator']==' '){
		$args['separator']='.....';
	}
	
    return $args;
}
 /* Custom shortcode */
/* add_shortcode( 'divider', 'shortcode_insert_divider' );
function shortcode_insert_divider( ) {
 	return '<div class="divider"></div>';
}
function cn_include_content($pid) {
	$thepageinquestion = get_post($pid);
	$content = $thepageinquestion->post_content;
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]>', $content);
	echo $content;
}

function cn_include_page( $atts, $content = null ) {
	extract(shortcode_atts(array( // a few default values
	   'id' => '')
	   , $atts));
	   cn_include_content($id);
}
add_shortcode('includepage', 'cn_include_page');

add_shortcode('name', 'get_name');

function get_name() {

   return $_GET['ly'];

}*/
function layoutcontent_shortcode( $atts, $content = null ) {
	
	if(isset($_GET['ly'])){
	$value=$_GET['ly'];
	}
	
    $a = shortcode_atts( array(
        'layout' => " ",
    ), $atts );
	
	if(esc_attr($a['layout'])==$value){
		return "<div id='layoutcontent'>$content</div>";
	}
    //return '<a class="' . esc_attr($a['layout']) . '">' . $content . '</a>';*/
	
}
add_shortcode( 'pagebreak', 'layoutcontent_shortcode' );




/* Keep layout querystring in URL start */
add_filter( 'post_link', 'custom_retain_query_string' );
add_filter( 'page_link', 'custom_retain_query_string' );
function custom_retain_query_string( $permalink ) {
	$current_url       = home_url( add_query_arg( null, null ) );
	
	$current_url_parts = explode( '?', $current_url );
	
	if($current_url_parts[1]!=""){
		return $permalink."?".$current_url_parts[1];
	}

	return $permalink;
}
/* Keep layout querystring in URL end */

?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
jQuery(document).ready(function(){
	var url = window.location.href;
	hashes = url.split("?")[1];
	ly=hashes.split("=");
	if(ly[0]!=''){
		jQuery(".entry-content p").hide();
	}
});
</script>
