<?php

/** use this filter to add promotion division at the begining of page */

function adding_promotion_div($content) {

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
add_filter( 'the_content', 'adding_promotion_div' );


/** add the css style for the promotion div */
function adding_promotion_div_style()
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
add_action( 'wp_head', 'adding_promotion_div_style' );