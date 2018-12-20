<?php
/**
* Template Functions
*
* @package     TourPress
* @subpackage  Template Functions
* @copyright   Copyright (c) 2016, Explorer Technologies
* @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
* @since       1.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Booking engine hook function
function tourpress_book() {
	do_action('tourpress_book');
}

// From price hook function
function tourpress_price() {
	do_action('tourpress_price');
}

// Print out the booking engine
function tourpress_dobook($url = "", $t = "") {
	global $post;
	$continue = false;
	
	if($url == "") {
		//if(is_single() && get_query_var('post_type') == 'tourpress_product') {
		if(is_singular('product')) {
			$book_url = get_post_meta( $post->ID, 'tourpress_book_url', true );
			if($book_url<>"")
				$continue = true;
		}
	} else {
		$book_url = $url;
		$continue = true;
	}

	if($continue) {
		// Get our settings / defaults
		$book_style = get_option('tourpress_bookstyle')=="" ? "link" : get_option('tourpress_bookstyle');
		if($t == "") {
			$book_text = get_option('tourpress_booktext')=="" ? __( 'Book Online', 'tourpress' ) : get_option('tourpress_booktext');
		} else {
			$book_text = $t;
		}
		$book_height = get_option('tourpress_bookheight')=="" ? "600" : get_option('tourpress_bookheight');
		$book_width = get_option('tourpress_bookwidth')=="" ? "600" : get_option('tourpress_bookwidth');
		$book_params = get_option('tourpress_bookqs')=="" ? "" : get_option('tourpress_bookqs');
		$book_url .= $book_params;
	
		// Render the booking engine based on the book_style
		if($book_style=="link") {
			// Standard link
			?>
			<p class="booklink"><a href="<?php echo $book_url; ?>"><?php echo $book_text; ?></a></p>
			<?php
		} else if ($book_style=="popup") {
			// Popup window
			$book_height = (int)$book_height;
			$book_width = (int)$book_width;
			$if_width = $book_width - 20;
			$book_url .= "&if=1&ifwidth=$if_width";
			?>
			<p class="booklink"><a href="<?php echo $book_url; ?>" onclick="window.open(this, '_blank', 'height=<?php echo $book_height; ?>,width=<?php echo $book_width ; ?>,statusbar=0,scrollbars=1'); return false;"><?php echo $book_text; ?></a></p>
			<?php
		} else if ($book_style=="iframe") {
			// Iframe
			$book_height = (int)$book_height;
			$book_width = (int)$book_width;
			?>
			<iframe class="bookframe" src="" style="width: 100%; height: <?php echo $book_height; ?>px;"></iframe>
			
			<script type="text/javascript">
				jQuery(document).ready(function() {
					var tpbookframe = jQuery('.bookframe');
					var tpbookwidth = tpbookframe.width() - 20;
					
					var tpbookurl = "<?php echo $book_url; ?>&if=1&ifwidth=" + tpbookwidth;
					
					tpbookframe.attr('src', tpbookurl); 
				});
			</script>
			<?php
		}
	}
}

// Print out the actual from price
function tourpress_doprice() {
	global $post;

	$from_price = get_post_meta( $post->ID, 'tourpress_from_price_display', true );

	if($from_price<>"") {
		echo "<span class='fromprice'>".__( 'from', 'tourpress' )." ".$from_price."</span>";
	}
}


function tourpress_convtime($seconds) {
    $ret = "";

    $hours = intval(intval($seconds) / 3600);
    if($hours > 0)
    {
        $ret .= "$hours hours ";
    }

    $minutes = (intval($seconds) / 60)%60;
    
    if (function_exists('bcmod')) {
        $minutes = bcmod((intval($seconds) / 60),60);
    } else {
        $minutes = (intval($seconds) / 60)%60;
    }
    
    if($hours > 0 || $minutes > 0)
    {
        $ret .= "$minutes minutes ";
    }
  
    //$seconds = bcmod(intval($seconds),60);
    //$ret .= "$seconds seconds";

	if($ret =="")
		$ret .= "Seconds";

    return $ret;
}

