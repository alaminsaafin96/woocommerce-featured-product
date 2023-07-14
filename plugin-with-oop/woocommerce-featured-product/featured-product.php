<?php

/* Plugin name: WooCommerce Featured Product
**
**
** Plugin Name: WooCommerce Featured Product
** Author: Alamin Alsaafin
** Version: 1.0
** Author Email: alaminsaafin96@gmail.com
** Description: Featured Product will be appeared in every page under the header (The WooCommerce Plugin is required to be able to use this plugin)
**
*/



class wc_featured_product {
    public function __construct() {
        // Add the necessary hooks
        add_action('init', array($this, 'init'));

        add_action( 'admin_enqueue_scripts', array($this, 'wcfp_js_files') );

        add_filter( 'plugin_action_links_woocommerce-featured-product/featured-product.php', array($this, 'wcpf_settings_link') );

        add_filter( 'woocommerce_get_sections_products', array($this, 'wcfeaturedproduct_add_settings_tab') );

        add_filter( 'woocommerce_get_settings_products',array($this, 'wcfeaturedproduct_all_settings'), 10, 2 );

        add_action( 'woocommerce_product_options_general_product_data', array($this, 'woocommerce_product_custom_fields') ); 

        add_action( 'woocommerce_process_product_meta', array($this, 'wpfp_product_meta_fields_save') );

        add_filter( 'the_content', array($this, 'adding_promotion_div') );

        add_action( 'wp_head', array($this, 'adding_promotion_div_style') );

        add_action('expire_promoted1', array($this, 'expire_promoted1_function'));

    }

    public function init() {
        if (!wp_next_scheduled('expire_promoted1')){
		  wp_schedule_event(time(), 'daily', 'expire_promoted1');
		}
    }


    public function wcfp_js_files()
	{

	    wp_enqueue_script('wcfp_main', plugin_dir_url( __FILE__ ) . '/assets/js/main.js', '', '1.0.0', 'all');
	}

    public function wcpf_settings_link( $links ) {
		/** Build and escape the URL. */
		$url = admin_url( 'admin.php?page=wc-settings&tab=products&section=wcfeaturedproduct' );
		/**  Create the link. */
		$settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';
		/** Adds the link to the end of the array. */
		array_push(
			$links,
			$settings_link
		);
		return $links;


	}//end nc_settings_link()


	/**
	 * Create the section beneath the featured product tab
	 */
	public function wcfeaturedproduct_add_settings_tab( $sections ) {
  
	  $sections['wcfeaturedproduct'] = __( 'Featured Product', 'text-domain' );
	  return $sections;
	  
	}


	/**
	 * Add settings to the specific section we created before
	 */

	public function wcfeaturedproduct_all_settings( $settings, $current_section ) {
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


	/** Add featured product's meta data in single product page in dashboard */
	public function woocommerce_product_custom_fields () {

	    global $woocommerce, $post;
	    echo '<div class="options_group">';
	    echo '<h4 style="margin-left:10px">Featured Product Settings</h4>';

		$wcfp_enabled   = get_post_meta($post->ID,'wcfp_enabled',true);
		$wcfp_enable_ed = get_post_meta($post->ID,'wcfp_enable_ed',true);
		?>

		<p class="form-field wcfp_enabled_field ">
			<label for="wcfp_enabled">Promote this product</label>
			<input type="checkbox" class="checkbox" style="" name="wcfp_enabled" id="wcfp_enabled" value="<?php echo $wcfp_enabled; ?>" <?php if($wcfp_enabled == 'yes'){echo 'checked="checked"';} ?>> 
			<span class="description">Promoted</span>
		</p>
		<?php
	    woocommerce_wp_text_input(
	        array(
	            'id'          => 'wcfp_title',
	            'label'       => __( 'Promoted Title', 'woocommerce' ),
	            'description'   => __( 'Write the promoted title!', 'theme_domain' ),
	            'desc_tip'    => 'true'
	        )
	    );
	    ?>
	    <p class="form-field wcfp_enabled_field ">
			<label for="wcfp_enable_ed">Set Expiration Date</label>
			<input type="checkbox" class="checkbox" style="" name="wcfp_enable_ed" id="wcfp_enable_ed" value="<?php echo $wcfp_enable_ed; ?>" <?php if($wcfp_enable_ed == 'yes'){echo 'checked="checked"';} ?>> 
			<span class="description">Enable / Disable</span>
		</p>
	    <div id="wpcf_expiration_date_fields" <?php if(get_post_meta($post->ID,'wcfp_enable_ed',true) != 'yes'){echo 'style="display:none"';}?>>
	    	<p class="form-field wcfp_expire_date_field ">
				<label for="wcfp_expire_date">Expiration Date</label>
				<input type="date" class="short" style="" name="wcfp_expire_date" id="wcfp_expire_date" value="<?php echo get_post_meta($post->ID,'wcfp_expire_date',true); ?>" placeholder=""> 
			</p>

			<p class="form-field wcfp_expire_time_field ">
				<label for="wcfp_expire_time">Expiration Time</label>
				<input type="time" class="short" style="" name="wcfp_expire_time" id="wcfp_expire_time" value="<?php echo get_post_meta($post->ID,'wcfp_expire_time',true); ?>" placeholder=""> 
			</p>
	   

	    </div>
	    <?php
	    echo '</div>';
	}

	/** save meta data for featured product */
	public function wpfp_product_meta_fields_save( $post_id ){

	    /** update last product determined */
	    if( (isset( $_POST['wcfp_enabled'] )) && ($_POST['wcfp_enabled'] == 'yes') )
	    {
	        update_option( 'wcfp_last_product_updated', $post_id );
	    }

	    update_post_meta( $post_id, 'wcfp_enabled', $_POST['wcfp_enabled'] );

	    if( isset( $_POST['wcfp_title'] ) )
	        update_post_meta( $post_id, 'wcfp_title', esc_attr( $_POST['wcfp_title'] ) );

	    
	    update_post_meta( $post_id, 'wcfp_enable_ed', $_POST['wcfp_enable_ed'] );

	    if( isset( $_POST['wcfp_expire_date'] ) )
	        update_post_meta( $post_id, 'wcfp_expire_date', esc_attr( $_POST['wcfp_expire_date'] ) );

	    if( isset( $_POST['wcfp_expire_time'] ) )
	        update_post_meta( $post_id, 'wcfp_expire_time', esc_attr( $_POST['wcfp_expire_time'] ) );

	  
	}

	/** use this filter to add promotion division at the begining of page */
	public function adding_promotion_div($content) {

		$enable_option = get_option("wcfp_enable_plugin");
		/** check if enable option is enabled */
		if($enable_option == 'yes'){
			$last_product_promoted = get_option("wcfp_last_product_updated");
			/** check if there is a promoted product existed */
			if($last_product_promoted != '')
			{

				$expired_date_is_enabled  = get_post_meta($last_product_promoted,'wcfp_enable_ed',true);
				/** check if expired date is set or not */
				if($expired_date_is_enabled == 'yes')
				{
					$current_date = date("Y-m-d");
					$expired_date = get_post_meta($last_product_promoted,'wcfp_expire_date',true);

					$date1 = strtotime($current_date);
		            $date2 = strtotime($expired_date);
		            /** check if the date of promotion is expired or not */
		            if($date1 <= $date2)
		            {
		            	$replaced_title = get_option("wcfp_re_title");
						$promoted_title = get_post_meta($last_product_promoted,'wcfp_title',true);

						if($promoted_title != ''){
							$the_title = $promoted_title;
						}else{
							$the_title = $replaced_title;
						}

						$custom_content = '<div class="banner">';
						$custom_content .= '<a href="#">';
						$custom_content .= '<h3 class="textcolor">';
						$custom_content .= '[Promoted title from backend]: ['.$promoted_title.' \| '.$replaced_title.']';
						$custom_content .= '</h3>';
						$custom_content .= '</a>';
						$custom_content .= '</div>';

					    $custom_content .= $content;

					    return $custom_content;
		            }else{
		            	return;
		            }
				}else{
						$replaced_title = get_option("wcfp_re_title");
						$promoted_title = get_post_meta($last_product_promoted,'wcfp_title',true);

						if($promoted_title != ''){
							$the_title = $promoted_title;
						}else{
							$the_title = $replaced_title;
						}

						$custom_content = '<div class="banner">';
						$custom_content .= '<a href="#">';
						$custom_content .= '<h3 class="textcolor">';
						$custom_content .= '[Promoted title from backend]: ['.$promoted_title.' \| '.$replaced_title.']';
						$custom_content .= '</h3>';
						$custom_content .= '</a>';
						$custom_content .= '</div>';

					    $custom_content .= $content;

					    return $custom_content;
				}
			}
			else
			{
				return;
			}
		}else{
			return;
		}  
	}

	/** add the css style for the promotion div */
	public function adding_promotion_div_style()
	{
		$bg_color   = get_option("wcfp_bgcolor");
		$text_color = get_option("wcfp_txtcolor");
		?>
	     <style type="text/css">
	    	.banner
	    	{
	    		width: 100%;
	    		height: 250px;
	    		background-color: <?php echo $bg_color; ?>;
	    		padding-top:50px;
	    	}
	    	.banner a
	    	{
	    		width: 100%;
	    		display:block;
	    		height: 100%;
	    		text-decoration: none;
	    	}
	    	.banner .textcolor
	    	{
	    		text-align: center;
	    		font-size:20px;
	    		font-weight: bold;
	    		color: <?php echo $text_color; ?>;
	    		height: 250px;
	    		line-height: 30px;
	    	}
	    </style>
	    <?php
	}


	/** code for disable product as promoted after date & time are expired */
	public function expire_promoted1_function() {

		$last_product_id = get_option("wcfp_last_product_updated");

		if($last_product_id != ''){

			$expired_date = get_post_meta($last_product_id,'wcfp_expire_date',true);
			$expired_time = get_post_meta($last_product_id,'wcfp_expire_time',true);

			$current_date = date("Y-m-d");

			$date1 = strtotime($current_date);
		    $date2 = strtotime($expired_date);
			
			if($date2 < $date1)
			{
				update_option("wcfp_last_product_updated","");
				update_post_meta($last_product_id,'wcfp_enabled','no');
				return;
			}
			else if($date2 == $date1)
			{
				// date_default_timezone_set('Asia/Jerusalem');
				$current_time = new DateTime('now');
				$c_t          = $current_time->format('H:i');
				if($expired_time <= $c_t)
				{
					update_option("wcfp_last_product_updated","");
					update_post_meta($last_product_id,'wcfp_enabled','no');
					return;	
				}
				else
				{
					return;
				}
			}
			else
			{
				return;
			}

		}else{
			return ;
		}
	}
}

// Instantiate the plugin class
new wc_featured_product();




