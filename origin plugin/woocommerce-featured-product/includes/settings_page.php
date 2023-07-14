<?php


/**
 * Create the section beneath the featured product tab
 */

add_filter( 'woocommerce_get_sections_products', 'wcfeaturedproduct_add_settings_tab' );
function wcfeaturedproduct_add_settings_tab( $sections ) {
  
  $sections['wcfeaturedproduct'] = __( 'Featured Product', 'text-domain' );
  return $sections;
  
}



/**
 * Add settings to the specific section we created before
 */
add_filter( 'woocommerce_get_settings_products', 'wcfeaturedproduct_all_settings', 10, 2 );
function wcfeaturedproduct_all_settings( $settings, $current_section ) {
  /**
   * Check the current section is what we want
   */
  if ( $current_section == 'wcfeaturedproduct' ) {
    $custom_settings = array();
    /** Add Title to the Settings */
    $custom_settings[] = array(
          'name' => __( 'Featured Product Section' ),
          'type' => 'title',
          'desc' => __( 'Featured product main settings' ),
          'id'   => 'featured_product_title'
    );
    /** Add Enable / Disable option */
    $custom_settings[] = array(
        'name' => __( 'Enable featured product' ),
        'type' => 'checkbox',
        'desc' => __( 'enable / disable featured product'),
        'id'  => 'wcfp_enable_plugin'

    );
    /** Add replaced title of featured product */
    $custom_settings[] = array(
          'name' => __( 'Featured product title' ),
          'type' => 'text',
          'desc_tip' => __( 'set the setion title by this option!'),
          'id'  => 'wcfp_re_title'

    );
    /** Add background color option for the featured product */
    $custom_settings[] = array(
          'name' => __( 'Featured product background color' ),
          'type' => 'color',
          'desc' => __( 'set the background color of featured product by this option!'),
          'desc_tip' => true,
          'id'  => 'wcfp_bgcolor',

    );
    /** Add text color option for the title of featured product */
    $custom_settings[] = array(
          'name' => __( 'Featured product text color' ),
          'type' => 'color',
          'desc' => __( 'set the text color of featured product by this option!'),
          'desc_tip' => true,
          'id'  => 'wcfp_txtcolor',

    );
    
    $custom_settings[] = array( 'type' => 'sectionend', 'id' => 'wcfeaturedproduct' );
    return $custom_settings;
  
  /**
   * If not, return the standard settings
   */
  } else {
    return $settings;
  }
}


