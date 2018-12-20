<style type="text/css">      
.btn-success {
  background-color: #2ecc71;
  border-color: #27ae60;
}
.btn {
  border: none;
  padding: 6px 12px;
  border-bottom: 4px solid;
  -webkit-transition: border-color 0.1s ease-in-out 0s,background-color 0.1s ease-in-out 0s;
  transition: border-color 0.1s ease-in-out 0s,background-color 0.1s ease-in-out 0s;
  outline: none;
}
.checkbox-nice label {
  padding-top: 3px;
}
label {
  font-weight: 400;
  font-size: 0.875em;
}
.checkbox-nice input[type=checkbox] {
  visibility: hidden;
}
.checkbox-nice {
  position: relative;
  padding-left: 15px;
}
.widget-todo .name {
  float: left;
}
.widget-todo>li {
  border-bottom: 1px solid #ebebeb;
  padding: 10px 5px;
}
.widget-todo {
  list-style: none;
  margin: 0;
  padding: 0;
}
.widget-products li .product>.warranty>i {
  color: #f1c40f;
}
.widget-products li .product>.warranty {
  display: block;
  text-decoration: none;
  width: 50%;
  float: left;
  font-size: 0.875em;
}
.widget-products li .product>.price>i {
  color: #2ecc71;
}
.widget-products li .product>.price {
  display: block;
  text-decoration: none;
  width: 50%;
  float: left;
  font-size: 0.875em;
}
.widget-products li .product>.name {
  display: block;
  font-weight: 600;
  padding-bottom: 7px;
}
.widget-products li .product {
  display: block;
  margin-left: 90px;
  margin-top: 19px;
}
.widget-products li .img {
  display: block;
  float: left;
  text-align: center;
  width: 70px;
  height: 68px;
  overflow: hidden;
  margin-top: 7px;
}
.widget-products li>a {
  height: 88px;
  display: block;
  width: 100%;
  color: #344644;
  padding: 3px 10px;
  position: relative;
  -webkit-transition: border-color 0.1s ease-in-out 0s,background-color 0.1s ease-in-out 0s;
  transition: border-color 0.1s ease-in-out 0s,background-color 0.1s ease-in-out 0s;
}
.widget-products li {
  border-bottom: 1px solid #ebebeb;
}
.widget-products {
  list-style: none;
  margin: 0;
  padding: 0;
}
.widget-users li {
  border-bottom: 1px solid #ebebeb;
  padding: 15px 0;
  height: 96px;
}
.label {
  border-radius: 3px;
  font-size: 0.875em;
  font-weight: 600;
}
.widget-users li>.details>.time {
  color: #3498db;
  font-size: 0.75em;
  padding-bottom: 7px;
}
.widget-users li>.details>.name>a {
  color: #344644;
}
.widget-users li>.details>.name {
  font-weight: 600;
}
.widget-users li>.details {
  margin-left: 60px;
}
.widget-users li>.img {
  float: left;
  margin-top: 8px;
  width: 50px;
  height: 50px;
  overflow: hidden;
  border-radius: 50%;
}
.widget-users {
  list-style: none;
  margin: 0;
  padding: 0;
}
.tabs-wrapper.tabs-no-header .tab-content {
  padding: 0 20px 20px;
}
.nav-tabs>li>a {
  border-radius: 0;
  font-size: 1.125em;
  font-weight: 300;
  outline: none;
  color: #555;
  margin-right: 3px;
}
.nav>li {
  float: left;
}
.tabs-wrapper .nav-tabs {
  margin-bottom: 15px;
}
.nav-tabs {
  background: #d0d8de;
  border-color: transparent;
  -moz-border-radius: 3px 3px 0 0;
  -webkit-border-radius: 3px 3px 0 0;
  border-radius: 3px 3px 0 0;
}
.main-box {
  background: #FFFFFF;
  -webkit-box-shadow: 1px 1px 2px 0 #CCCCCC;
  -moz-box-shadow: 1px 1px 2px 0 #CCCCCC;
  -o-box-shadow: 1px 1px 2px 0 #CCCCCC;
  -ms-box-shadow: 1px 1px 2px 0 #CCCCCC;
  box-shadow: 1px 1px 2px 0 #CCCCCC;
  margin-bottom: 16px;
  -webikt-border-radius: 3px;
  -moz-border-radius: 3px;
  border-radius: 3px;
}
.checkbox{
/* Double-sized Checkboxes */
-ms-transform: scale(1.5); /* IE */
-moz-transform: scale(1.5); /* FF */
-webkit-transform: scale(1.5); /* Safari and Chrome */
-o-transform: scale(1.5); /* Opera */
}
.span-checkbox{
margin: auto;
float: right;
}
.return-form-submit-button{
	margin: 10px 0px;
}
<?php
	$products = $order->get_items();
  $already_payed = !empty( $returned = get_post_meta($order_id, 'wc_retured_products',1) ) ? $returned : array();
  ob_start();
?>        
</style>

<h2><?php echo __('Return products','wc_return' )?></h2>
<div>
  <label><?php 
    if( count($products) == count($already_payed) ){
      _e('All products are returned','wc_return');
    }else{
      _e('Select products for return','wc_return');
    }
    ?>
    
  </label>
</div>
<ul class="widget-products">
<?php
  foreach( $products as $item ) {
    $image = wp_get_attachment_image_src( get_post_thumbnail_id( $item->get_product_id() ), 'single-post-thumbnail' );
    $price = $order->get_item_total( $item );
    if(!in_array($item->get_product_id(), $already_payed)){?>
      <li>
         <a href="#">
         <span class="img">
         <img class="img-thumbnail" src="<?php echo $image[0] ?>" alt="">
         </span>
         <span class="product clearfix">
           <span class="name"><?php echo $item->get_name();?></span>
           <span class="price">
             <i class="fa fa-money"></i> <?php echo "Price: ".$price;?>
           </span>
           <span class="span-checkbox">
             <input type="checkbox" class="checkbox ni-product-ids" name="" value="<?php echo $item->get_product_id();?>">
           </span>
         </span>
         </a>
      </li><?php
    }else{?>
      <li style="background: #f8f8f8;">
         <a href="">
         <span class="img">
         <img class="img-thumbnail" src="<?php echo $image[0] ?>" alt="">
         </span>
         <span class="product clearfix">
           <span class="name"><?php echo $item->get_name();?></span>
           <span class="price">
             <i class="fa fa-money"></i> <?php echo "Price: ".$price;?>
           </span>
           <span class="span-checkbox">
             Returned product.
           </span>
         </span>
         </a>
      </li><?php
    }
  }?>
</ul>
<textarea name="wc_message" id="wc_message" cols="30" rows="10" placeholder="<?php _e('Explain the reasons for your return', 'wc_return') ?>"></textarea>
<input type="hidden" name="order" value="<?php echo $order->get_id(); ?>" />
<input type="hidden" name="customer" value="<?php echo $order->get_billing_email(); ?>" />
<?php 

do_action('after_myaccount_retrun_from');
	 // echo apply_filters( 'wc_return_order_button', '<a class="button return-form-submit-button" href="#">' . __('Return order','wc_return') . '</a>' );
?>
<a class="button return-form-submit-button" href="<?php echo $generate_url; ?>" data-tip="<?php _e('Retun Products', 'wc_return'); ?>"><?php _e('Retun Products', 'wc_return'); ?></a>

<div class="message" style="display: block;color: #26e126;font-size: 15px;"></div>
<?php
$form = ob_get_clean();

echo apply_filters( 'wc_return_order_form', $form );

?>
<script type="text/javascript">	
jQuery(document).ready(function(){
jQuery(".return-form-submit-button").one("click", function(e) {
e.preventDefault();
console.log('hi');
jQuery(this).click(function () { return false; });
var product_id_arr 	= 	jQuery(".ni-product-ids:checked").map(function(){return jQuery(this).val();}).get();
var product_ids 	=	JSON.stringify(product_id_arr);

wc_message = jQuery('#wc_message').val();
location.href = this.href
	+'&return_product_ids=' + product_ids
	+ '&reson=' + wc_message;
return false;			
});
});
</script>