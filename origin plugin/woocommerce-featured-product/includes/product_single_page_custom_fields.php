<?php

/** Add featured product's meta data in single product page in dashboard */
add_action( 'woocommerce_product_options_general_product_data', 'woocommerce_product_custom_fields' ); 

function woocommerce_product_custom_fields () {

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
    $current_time = new DateTime('now');
            $c_t          = $current_time->format('H:i');
            echo $c_t;
            echo date("Y-m-d");
    echo '</div>';
}


/** save meta data for featured product */
add_action( 'woocommerce_process_product_meta', 'wpfp_product_meta_fields_save' );
function wpfp_product_meta_fields_save( $post_id ){

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




