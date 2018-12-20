<?php 
/*Template Name: Pending Products  */
$msg=$_REQUEST['msg'];
?>
    
		 <?php             
            $paged = isset($_GET['paged']) ? $_GET['paged'] : 1;
            $next_page = intval($paged) + 1;			
			$prev_page = intval($paged) - 1;
			$current=max( 1, $paged );
    
           // echo $paged;
			$args = array(          
                                'post_status'    =>'pending',
                                'post_type'      => 'product',
                                'posts_per_page' => '2',
                                'paged'          => $paged,
                                'order'          => 'DESC',
                                'orderby'        => 'date'
                                
                        );

			$loop= new WP_Query($args);		
			$total= $loop->max_num_pages;
			$total_posts=$loop->post_count;
			$Previous = '<span class="fa fa-long-arrow-left"></span>Previous';

                //previous_posts_link($Previous,$loop->max_num_pages);

                global $wp_query;
                $big = 999999999; // need an unlikely integer
                
                $args_pagination = array(
                            
                            'base'               => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                            'format'             => '?paged=%#%',
                            'total'              => $loop->max_num_pages,
                            'current'            =>  max( 1, $paged ),
                            'show_all'           => false,
                            'end_size'           => 0,
                            'mid_size'           => 0,
                            'prev_next'          => true,
                            'prev_text'          => __('Previous'),
                            'next_text'          => __('Next'),
                            'type'               => 'plain',
                            'add_args'           => false,
                            'add_fragment'       => '',
                            'before_page_number' => '',
                            'after_page_number'  => ''
                        
                        );				
                $Next = 'Next <span class="fa fa-long-arrow-right"></span>';
				$previous=previous_posts_link('&laquo; Newer', $loop ->max_num_pages);
                //next_posts_link($Next,$loop->max_num_pages);    
                $total= $loop->max_num_pages;  
        ?>
		<?php //include_once('templates/blog-sections/blog-pagination.php'); ?>

<h3><?php if($msg==1) echo "Product merged successfully"; else echo "";?></h3>
<br><Br>
<form id="posts-filter" method="get">
<p class="search-box">
	<!--<label class="screen-reader-text" for="post-search-input">Search products:</label>
	<input id="post-search-input" name="s" value="" type="search">
	<input id="search-submit" class="button" value="Search products" type="submit"></p>-->

<input name="post_status" class="post_status_page" value="all" type="hidden">
<input name="post_type" class="post_type_page" value="product" type="hidden">

<input id="_wpnonce" name="_wpnonce" value="40b986726c" type="hidden"><input name="_wp_http_referer" value="/wp-admin/edit.php?post_type=product" type="hidden">	<div class="tablenav top">

			<div class="alignleft actions bulkactions">
			<!--<label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label><select name="action" id="bulk-action-selector-top">
<option value="-1">Bulk Actions</option>
	<option value="edit" class="hide-if-no-js">Edit</option>
	<option value="trash">Move to Trash</option>
</select>
<input id="doaction" class="button action" value="Apply" type="submit">-->
		</div>
				<div class="alignleft actions">
		<!--<label for="filter-by-date" class="screen-reader-text">Filter by date</label>
		<select name="m" id="filter-by-date">
			<option selected="selected" value="0">All dates</option>
<option value="201712">December 2017</option>
<option value="201706">June 2017</option>
<option value="201705">May 2017</option>
<option value="201703">March 2017</option>
<option value="201701">January 2017</option>
<option value="201612">December 2016</option>
		</select>
<select name="is_marketplace">
<option value="0">Select Product type</option>
<option value="1">Buyback</option>
<option value="2">Marketplace</option>
</select>
<select name="product_type" id="dropdown_product_type"><option value="">Show all product types</option><option value="simple">Simple product</option><option value="downloadable"> → Downloadable</option><option value="virtual"> → Virtual</option><option value="variable">Variable product</option></select>
			<select name="seo_filter">
				<option value="">All SEO Scores</option>
				<option value="bad">SEO: Bad</option>
				<option value="ok">SEO: OK</option>
				<option value="good">SEO: Good</option>
				<option value="na">SEO: No Focus Keyword</option>
				<option value="noindex">SEO: Post Noindexed</option>
			</select><input name="filter_action" id="post-query-submit" class="button" value="Filter" type="submit">		--></div>
<h2 class="screen-reader-text">Products navigation</h2>
<div class="tablenav-pages"><span class="displaying-num"></span>
<span class="pagination-links">
<a class="first-page" href="<?php echo site_url();?>/wp-admin/admin.php?page=starconsults-admin-menu&post_type=product&amp;paged=1"><span class="screen-reader-text">First page</span><span aria-hidden="true">«</span></span></a>
<?php if( $prev_page > 0 ){?>
<a class="previous-page" href="<?php echo site_url();?>/wp-admin/admin.php?page=starconsults-admin-menu&post_type=product&amp;paged=<?php echo $prev_page;?>"><span class="screen-reader-text">Prev page</span><span aria-hidden="true">‹</span></a>
<?php }?>
<span class="screen-reader-text">Current Page</span><span id="table-paging" class="paging-input"><span class="tablenav-paging-text"><?php echo $current;?> of <span class="total-pages"><?php echo $total;?></span></span></span>
<?php if ( ( $next_page <= $total ) ) {?>
<a class="next-page" href="<?php echo site_url();?>/wp-admin/admin.php?page=starconsults-admin-menu&post_type=product&amp;paged=<?php echo $next_page;?>"><span class="screen-reader-text">Next page</span><span aria-hidden="true">›</span></a>
<?php }?>
<a class="last-page" href="<?php echo site_url();?>/wp-admin/admin.php?page=starconsults-admin-menu&post_type=product&amp;paged=<?php echo $total;?>"><span class="screen-reader-text">Last page</span><span aria-hidden="true">»</span></a></span></div>
		<br class="clear">
	</div>
<table class="wp-list-table widefat fixed striped posts">
	<thead>
	<tr>		
		<th scope="col" id="name" class="manage-column column-name column-primary sortable desc"><a href="<?php echo site_url();?>/wp-admin/admin.php?page=starconsults-admin-menu&?post_type=product&amp;orderby=title&amp;order=asc"><span>Name</span><span class="sorting-indicator"></span></a></th>
		<th scope="col" id="sku" class="manage-column column-sku sortable desc"><a href="<?php echo site_url();?>/wp-admin/edit.php?post_type=product&amp;orderby=sku&amp;order=asc"><span>SKU</span><span class="sorting-indicator"></span></a></th>
		<th scope="col" id="is_in_stock" class="manage-column column-is_in_stock">Stock</th>
		<th scope="col" id="price" class="manage-column column-price sortable desc"><a href="<?php echo site_url();?>/wp-admin/edit.php?post_type=product&amp;orderby=price&amp;order=asc"><span>Wholsale Price</span><span class="sorting-indicator"></span></a></th>
		<th scope="col" id="price" class="manage-column column-price sortable desc"><a href="<?php echo site_url();?>/wp-admin/edit.php?post_type=product&amp;orderby=price&amp;order=asc"><span>Suggested Retail</span><span class="sorting-indicator"></span></a></th>
		<th scope="col" id="date" class="manage-column column-date sortable asc"><a href="<?php echo site_url();?>/wp-admin/edit.php?post_type=product&amp;orderby=date&amp;order=desc"><span>Color</span><span class="sorting-indicator"></span></a></th>
		<th scope="col" id="date" class="manage-column column-date sortable asc"><a href="<?php echo site_url();?>/wp-admin/edit.php?post_type=product&amp;orderby=date&amp;order=desc"><span>Memory</span><span class="sorting-indicator"></span></a></th>
		<th scope="col" id="date" class="manage-column column-date sortable asc"><a href="<?php echo site_url();?>/wp-admin/edit.php?post_type=product&amp;orderby=date&amp;order=desc"><span>Brand</span><span class="sorting-indicator"></span></a></th>
		<th scope="col" id="author" class="manage-column column-author">Author</th>
		<th scope="col" id="swSocialShares" class="manage-column column-swSocialShares hidden">Social Shares</th>
		<th scope="col" id="wpseo-score" class="manage-column column-wpseo-score hidden sortable desc"><a href="<?php echo site_url();?>/wp-admin/edit.php?post_type=product&amp;orderby=wpseo-score&amp;order=asc"><span>SEO</span><span class="sorting-indicator"></span></a></th>
		<th scope="col" id="wpseo-title" class="manage-column column-wpseo-title hidden">SEO Title</th>
		<th scope="col" id="wpseo-metadesc" class="manage-column column-wpseo-metadesc hidden sortable desc"><a href="<?php echo site_url();?>/wp-admin/edit.php?post_type=product&amp;orderby=wpseo-metadesc&amp;order=asc"><span>Meta Desc.</span><span class="sorting-indicator"></span></a></th>
		<th scope="col" id="wpseo-focuskw" class="manage-column column-wpseo-focuskw hidden sortable desc"><a href="<?php echo site_url();?>/wp-admin/edit.php?post_type=product&amp;orderby=wpseo-focuskw&amp;order=asc"><span>Focus KW</span><span class="sorting-indicator"></span></a></th>
	</tr>
	</thead>

	<tbody id="the-list">
		<?php
			if($loop->have_posts()){ 

			    $l=1;
			    while ( $loop ->have_posts() ) : $loop ->the_post(); 
					$post_id = get_the_ID();
					$featured_img_post = get_the_post_thumbnail_url($post_id);					
					$author_id=get_post_field('post_author', $post_id);					
					$thumb_id = get_post_thumbnail_id();
					$thumb_url = wp_get_attachment_image_src($thumb_id,'thumbnail-size', true);
					//echo $thumb_url[0];

			  ?>			
	<tr id="post-<?php echo $post_id; ?>" class="iedit author-self level-0 post-<?php echo $post_id; ?> type-product status-publish hentry pmpro-has-access">		
	<td class="name column-name has-row-actions column-primary" data-colname="Name"><strong><a class="row-title" href="<?php echo site_url();?>/wp-admin/post.php?post=<?php echo $post_id; ?>&amp;action=edit"><?php echo get_post_field('post_title', $post_id);?></a></strong>
	<div class="hidden" id="inline_<?php echo $post_id; ?>">
	<div class="post_title">Samsung Galaxy Note 5</div><div class="post_name">samsung-galaxy-note-5-copy-5</div>
	<div class="post_author">15</div>
	<div class="comment_status">closed</div>
	<div class="ping_status">open</div>
	<div class="_status">publish</div>
	<div class="jj">17</div>
	<div class="mm">03</div>
	<div class="aa">2017</div>
	<div class="hh">14</div>
	<div class="mn">46</div>
	<div class="ss">20</div>
	<div class="post_password"></div><div class="page_template">default</div><div class="post_category" id="product_cat_<?php echo $post_id; ?>"></div><div class="tags_input" id="product_tag_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_computer-condition_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_computer-model_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_computer-processor-speed_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_computer-release-year_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_computer-retina-display_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_computer-screen-size_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_device_<?php echo $post_id; ?>">Smartphone</div><div class="tags_input" id="pa_device-condition_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_ipad_model_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_ipad-capacity_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_ipad-carrier_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_ipad-condition_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_ipod-condition_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_ipod-gen_26583"></div><div class="tags_input" id="pa_ipod-memory_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_ipod-model_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_is-your-ipod-engraved_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_phone-brand_<?php echo $post_id; ?>">Samsung</div><div class="tags_input" id="pa_phone-capacity_<?php echo $post_id; ?>">64 GB</div><div class="tags_input" id="pa_phone-carrier_<?php echo $post_id; ?>">Other Carrier</div><div class="tags_input" id="pa_phone-condition_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_phone-model_<?php echo $post_id; ?>">Galaxy Note 5</div><div class="tags_input" id="pa_phone-network_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_retina_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_tablet-brand_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_tablet-capacity_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_tablet-carrier_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_tablet-condition_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_tablet-model_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_tablet-mpn_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_tablet-network_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_tablet-processor_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_tablet-screensize_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_watch-band_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_watch-brand_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_watch-color_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_watch-condition_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_watch-model_<?php echo $post_id; ?>"></div><div class="tags_input" id="pa_watch-size_<?php echo $post_id; ?>"></div><div class="sticky"></div></div>
					<div class="hidden" id="woocommerce_inline_<?php echo $post_id; ?>">
						<div class="menu_order">0</div>
						<div class="sku">smartphone-samsung-other_carrier-galaxy_note_5-64_gb</div>
						<div class="regular_price">150</div>
						<div class="sale_price"></div>
						<div class="weight"></div>
						<div class="length"></div>
						<div class="width"></div>
						<div class="height"></div>
						<div class="shipping_class"></div>
						<div class="visibility">visible</div>
						<div class="stock_status">instock</div>
						<div class="stock"></div>
						<div class="manage_stock">no</div>
						<div class="featured">no</div>
						<div class="product_type">simple</div>
						<div class="product_is_virtual">no</div>
						<div class="tax_status">none</div>
						<div class="tax_class"></div>
						<div class="backorders"></div>
					</div>
				<div class="row-actions"><span class="id">ID: <?php echo $post_id; ?> | </span><span class="edit"><a href="<?php echo site_url();?>/wp-admin/admin.php?page=view-product&postid=<?php echo $post_id; ?>" >View</a>  </span>
				<td class="sku column-sku" data-colname="SKU"><?php echo get_post_meta($post_id,'_sku',true );?></td>
				<td class="is_in_stock column-is_in_stock" data-colname="Stock"><mark class="instock"><?php echo get_post_meta($post_id,'_stock',true );?></mark></td>
				<td class="price column-price" data-colname="Price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php echo get_post_meta($post_id,'_regular_price',true );?></span></td>
				<td class="price column-price" data-colname="Price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?php echo get_post_meta($post_id,'_retail_price',true );?></span></td>
				<td class="date column-date" data-colname="Date"><?php echo get_post_meta($post_id,'_product_color',true );?></td>
				<td class="date column-date" data-colname="Date"><?php echo get_post_meta($post_id,'_product_memory',true );?></td>
				<td class="date column-date" data-colname="Date"><?php echo get_post_meta($post_id,'_product_brand',true );?></td>
				<td class="author column-author" data-colname="Author"><a href="edit.php?post_type=product&amp;author=15"><?php echo  get_the_author_meta( 'display_name', $author_id ); ?></a></td>
				<td class="swSocialShares column-swSocialShares hidden" data-colname="Social Shares">0</td>
				<td class="wpseo-score column-wpseo-score hidden" data-colname="SEO"><div title="Focus keyword not set." class="wpseo-score-icon na"></div></td>
				<td class="wpseo-title column-wpseo-title hidden" data-colname="SEO Title">Samsung Galaxy Note 5 - Gizmogul Retail</td>
				<td class="wpseo-metadesc column-wpseo-metadesc hidden" data-colname="Meta Desc."></td>
				<td class="wpseo-focuskw column-wpseo-focuskw hidden" data-colname="Focus KW"></td>	
				</tr>
			<?php  

             $l= $l+1;  
	        
	         endwhile; 
	     

   			wp_reset_postdata(); 


             
			} else { ?>
				<tr class="no-items">
				<td class="colspanchange" colspan="13">No products found</td>
				</tr>
			<?php	}
			       
            ?>
		</tbody>

	<tfoot>
	<tr>
		<th scope="col" class="manage-column column-name column-primary sortable desc"><a href="<?php echo site_url();?>/wp-admin/edit.php?post_type=product&amp;orderby=title&amp;order=asc"><span>Name</span><span class="sorting-indicator"></span></a></th>
		<th scope="col" class="manage-column column-sku sortable desc"><a href="<?php echo site_url();?>/wp-admin/edit.php?post_type=product&amp;orderby=sku&amp;order=asc"><span>SKU</span><span class="sorting-indicator"></span></a></th><th scope="col" class="manage-column column-is_in_stock">Stock</th>
		<th scope="col" class="manage-column column-price sortable desc"><a href="<?php echo site_url();?>/wp-admin/edit.php?post_type=product&amp;orderby=price&amp;order=asc"><span>Wholsale price</span><span class="sorting-indicator"></span></a></th>
		<th scope="col" class="manage-column column-price sortable desc"><a href="<?php echo site_url();?>/wp-admin/edit.php?post_type=product&amp;orderby=price&amp;order=asc"><span>Suggested retail</span><span class="sorting-indicator"></span></a></th>
		<th scope="col" class="manage-column column-date sortable asc"><a href="<?php echo site_url();?>/wp-admin/edit.php?post_type=product&amp;orderby=date&amp;order=desc"><span>Color</span><span class="sorting-indicator"></span></a></th>
		<th scope="col" class="manage-column column-date sortable asc"><a href="<?php echo site_url();?>/wp-admin/edit.php?post_type=product&amp;orderby=date&amp;order=desc"><span>Memory</span><span class="sorting-indicator"></span></a></th>
		<th scope="col" class="manage-column column-date sortable asc"><a href="<?php echo site_url();?>/wp-admin/edit.php?post_type=product&amp;orderby=date&amp;order=desc"><span>Brand</span><span class="sorting-indicator"></span></a></th>		
		<th scope="col" class="manage-column column-author">Author</th>
		<th scope="col" class="manage-column column-swSocialShares hidden">Social Shares</th>
		<th scope="col" class="manage-column column-wpseo-score hidden sortable desc"><a href="<?php echo site_url();?>/wp-admin/edit.php?post_type=product&amp;orderby=wpseo-score&amp;order=asc"><span>SEO</span><span class="sorting-indicator"></span></a></th>
		<th scope="col" class="manage-column column-wpseo-title hidden">SEO Title</th>
		<th scope="col" class="manage-column column-wpseo-metadesc hidden sortable desc"><a href="<?php echo site_url();?>/wp-admin/edit.php?post_type=product&amp;orderby=wpseo-metadesc&amp;order=asc"><span>Meta Desc.</span><span class="sorting-indicator"></span></a></th>
		<th scope="col" class="manage-column column-wpseo-focuskw hidden sortable desc"><a href="<?php echo site_url();?>/wp-admin/edit.php?post_type=product&amp;orderby=wpseo-focuskw&amp;order=asc"><span>Focus KW</span><span class="sorting-indicator"></span></a></th>	
	</tr>
	</tfoot>

</table>    
<div class="tablenav bottom">
				<div class="alignleft actions bulkactions">
			<!--<label for="bulk-action-selector-bottom" class="screen-reader-text">Select bulk action</label><select name="action2" id="bulk-action-selector-bottom">
<option value="-1">Bulk Actions</option>
	<option value="edit" class="hide-if-no-js">Edit</option>
	<option value="trash">Move to Trash</option>
</select>
<input id="doaction2" class="button action" value="Apply" type="submit">-->
		</div>
				<div class="alignleft actions">
		</div>
<div class="tablenav-pages"><span class="displaying-num"></span>
<span class="pagination-links">
<a class="first-page" href="<?php echo site_url();?>/wp-admin/admin.php?page=starconsults-admin-menu&post_type=product&amp;paged=1"><span class="screen-reader-text">First page</span><span aria-hidden="true">«</span></span></a>
<?php if( $prev_page > 0 ){?>
<a class="previous-page" href="<?php echo site_url();?>/wp-admin/admin.php?page=starconsults-admin-menu&post_type=product&amp;paged=<?php echo $prev_page;?>"><span class="screen-reader-text">Prev page</span><span aria-hidden="true">‹</span></a>
<?php }?>
<span class="screen-reader-text">Current Page</span><span id="table-paging" class="paging-input"><span class="tablenav-paging-text"><?php echo $current;?> of <span class="total-pages"><?php echo $total;?></span></span></span>
<?php if ( ( $next_page <= $total ) ) {?>
<a class="next-page" href="<?php echo site_url();?>/wp-admin/admin.php?page=starconsults-admin-menu&post_type=product&amp;paged=<?php echo $next_page;?>"><span class="screen-reader-text">Next page</span><span aria-hidden="true">›</span></a>
<?php }?>
<a class="last-page" href="<?php echo site_url();?>/wp-admin/admin.php?page=starconsults-admin-menu&post_type=product&amp;paged=<?php echo $total;?>"><span class="screen-reader-text">Last page</span><span aria-hidden="true">»</span></a></span></div>
		<br class="clear">
	</div>

</form>
