<?php






/** add custom js file */
add_action( 'admin_enqueue_scripts', 'wcfp_js_files' );
function wcfp_js_files()
{

    wp_enqueue_script('wcfp_main', plugin_dir_url( __FILE__ ) . '/assets/js/main.js', '', '1.0.0', 'all');
}



/** add settings link to plugin */
add_filter( 'plugin_action_links_woocommerce-featured-product/featured-product.php', 'wcpf_settings_link' );
function wcpf_settings_link( $links ) {
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


/** code for disable product as promoted after date & time are expired */
// if (!wp_next_scheduled('expire_promoted1')){
//   wp_schedule_event(time(), 'daily', 'expire_promoted1');
// }


add_action('expire_promoted1', 'expire_promoted1_function');

function expire_promoted1_function() {


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