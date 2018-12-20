<?php
  global $WCFM;
?>
<div class="collapse wcfmmp-collapse" id="wcfmmp_shipping_method_edit_container">
  <div class="wcfm-collapse-content" >
      <div class="page_collapsible  modal_head" id="wcfmmp_shipping_method_edit_general_head">
        <label class="fa fa-truck"></label>
        <span>
          <?php _e( 'Edit Shipping Methods', 'wc-multivendor-marketplace' ); ?>
        </span>
      </div>
      <div class="modal_body" id="wcfmmp_shipping_method_edit_form_general_body">
        <?php
          $WCFM->wcfm_fields->wcfm_generate_form_field ( 
            array(
              "method_id_selected" => array(
                'label' => false, 
                'name' => 'method_id_selected',
                'type' => 'hidden', 
                'class' => 'wcfm-text wcfm_ele', 
                'value' => ''  
              )
            )
          );
          $WCFM->wcfm_fields->wcfm_generate_form_field ( 
            array(
              "instance_id_selected" => array(
                'label' => false, 
                'name' => 'instance_id_selected',
                'type' => 'hidden', 
                'class' => 'wcfm-text wcfm_ele', 
                'value' => ''  
              )
            )
          );
        ?>
        <div class="shipping_form" id="free_shipping">
          <?php
          
            $WCFM->wcfm_fields->wcfm_generate_form_field ( 
              array(
                "method_title_fs" => array(
                  'label' => __('Method Title', 'wc-multivendor-marketplace'), 
                  'name' => 'method_title',
                  'type' => 'text', 
                  'class' => 'wcfm-text wcfm_ele', 
                  'label_class' => 'wcfm_title wcfm_ele', 
                  'placeholder' => __('Enter method title', 'wc-multivendor-marketplace'),
                  'value' => ''  
                )
              )
            );
          ?>
          <?php
            $WCFM->wcfm_fields->wcfm_generate_form_field ( 
              array(
                "minimum_order_amount_fs" => array(
                  'label' => __('Minimum order amount for free shipping', 'wc-multivendor-marketplace'), 
                  'name' => 'minimum_order_amount',
                  'type' => 'text', 
                  'class' => 'wcfm-text wcfm_ele', 
                  'label_class' => 'wcfm_title wcfm_ele', 
                  'placeholder' => __('0.00', 'wc-multivendor-marketplace'),
                  'value' => ''  
                )
              )
            );
          ?>
          <?php
            $WCFM->wcfm_fields->wcfm_generate_form_field ( 
              array(
                "method_description_fs" => array(
                  'label' => __('Description', 'wc-multivendor-marketplace'), 
                  'name' => 'method_description',
                  'type' => 'textarea', 
                  'class' => 'wcfm-textarea wcfm_ele', 
                  'label_class' => 'wcfm_title wcfm_ele', 
                  'value' => ''  
                )
              )
            );
          ?>
        </div>
        <!-- Local Pickup -->
        <div class="shipping_form" id="local_pickup">
          <?php
          
            $WCFM->wcfm_fields->wcfm_generate_form_field ( 
              array(
                "method_title_lp" => array(
                  'label' => __('Method Title', 'wc-multivendor-marketplace'), 
                  'name' => 'method_title',
                  'type' => 'text', 
                  'class' => 'wcfm-text wcfm_ele', 
                  'label_class' => 'wcfm_title wcfm_ele', 
                  'placeholder' => __('Enter method title', 'wc-multivendor-marketplace'),
                  'value' => ''  
                )
              )
            );
          ?>
          <?php
            $WCFM->wcfm_fields->wcfm_generate_form_field ( 
              array(
                "method_cost_lp" => array(
                  'label' => __('Cost', 'wc-multivendor-marketplace'), 
                  'name' => 'method_cost',
                  'type' => 'text', 
                  'class' => 'wcfm-text wcfm_ele', 
                  'label_class' => 'wcfm_title wcfm_ele', 
                  'placeholder' => __('0.00', 'wc-multivendor-marketplace'),
                  'value' => ''  
                )
              )
            );
          ?>
          
          <?php
          if(apply_filters('show_shipping_zone_tax', true) )
            $WCFM->wcfm_fields->wcfm_generate_form_field ( 
              array(
                "method_tax_status_lp" => array(
                  'label' => __('Tax Status', 'wc-multivendor-marketplace'), 
                  'name' => 'method_tax_status',
                  'type' => 'select', 
                  'class' => 'wcfm-select wcfm_ele', 
                  'label_class' => 'wcfm_title wcfm_ele', 
                  'options' => array(
                      'none' => __('None', 'wc-multivendor-marketplace'), 
                      'taxable' => __('Taxable' , 'wc-multivendor-marketplace') 
                      )  
                )
              )
            );
          ?>
          
          <?php
            $WCFM->wcfm_fields->wcfm_generate_form_field ( 
              array(
                "method_description_lp" => array(
                  'label' => __('Description', 'wc-multivendor-marketplace'), 
                  'name' => 'method_description',
                  'type' => 'textarea', 
                  'class' => 'wcfm-textarea wcfm_ele', 
                  'label_class' => 'wcfm_title wcfm_ele', 
                  'value' => ''  
                )
              )
            );
          ?>
        </div>
        
        <div class="shipping_form" id="flat_rate">
          <?php
          
            $WCFM->wcfm_fields->wcfm_generate_form_field ( 
              array(
                "method_title_fr" => array(
                  'label' => __('Method Title', 'wc-multivendor-marketplace'), 
                  'name' => 'method_title',
                  'type' => 'text', 
                  'class' => 'wcfm-text wcfm_ele', 
                  'label_class' => 'wcfm_title wcfm_ele', 
                  'placeholder' => __('Enter method title', 'wc-multivendor-marketplace'),
                  'value' => ''  
                )
              )
            );
          ?>
          <?php
            $WCFM->wcfm_fields->wcfm_generate_form_field ( 
              array(
                "method_cost_fr" => array(
                  'label' => __('Cost', 'wc-multivendor-marketplace'), 
                  'name' => 'method_cost',
                  'type' => 'text', 
                  'class' => 'wcfm-text wcfm_ele', 
                  'label_class' => 'wcfm_title wcfm_ele', 
                  'placeholder' => __('0.00', 'wc-multivendor-marketplace'),
                  'value' => ''  
                )
              )
            );
          ?>
          
          <?php
          if(apply_filters('show_shipping_zone_tax', true) )
            $WCFM->wcfm_fields->wcfm_generate_form_field ( 
              array(
                "method_tax_status_fr" => array(
                  'label' => __('Tax Status', 'wc-multivendor-marketplace'), 
                  'name' => 'method_tax_status',
                  'type' => 'select', 
                  'class' => 'wcfm-select wcfm_ele', 
                  'label_class' => 'wcfm_title wcfm_ele', 
                  'options' => array(
                      'none' => __('None', 'wc-multivendor-marketplace'), 
                      'taxable' => __('Taxable' , 'wc-multivendor-marketplace') 
                      )  
                )
              )
            );
          ?>
          
          <?php
            $WCFM->wcfm_fields->wcfm_generate_form_field ( 
              array(
                "method_description_fr" => array(
                  'label' => __('Description', 'wc-multivendor-marketplace'), 
                  'name' => 'method_description',
                  'type' => 'textarea', 
                  'class' => 'wcfm-textarea wcfm_ele', 
                  'label_class' => 'wcfm_title wcfm_ele', 
                  'value' => ''  
                )
              )
            );
          ?>
        </div>
        
        
      </div>
      <div class="modal_footer" id="wcfmmp_shipping_method_edit_general_footer">
        <div class="inner">
          <button class="wcfmmp_submit_button" id="wcfmmp_shipping_method_edit_button">
            <?php _e( 'Save Method Settings', 'wc-multivendor-marketplace' ); ?>
          </button>
        </div>
      </div>
    
  </div>
</div>