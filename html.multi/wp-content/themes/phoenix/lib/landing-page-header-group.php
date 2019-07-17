<?php
add_action( 'landing_page_header_group', 'landing_page_header_group_func' );
function landing_page_header_group_func(){
	
	global $post;

	$current_page_id = NULL;
	if ( is_page() ) {
		$current_page_id = get_the_ID();
	}
	elseif ( is_home() ) {
		$current_page_id = get_option('page_for_posts', true);
	}
	elseif( 'landing_page' == $post->post_type ) {
		$current_page_id = $post->ID;
	}
	elseif ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
		if( strpos( $_SERVER['REQUEST_URI'], 'wp-activate.php' ) === false ){
			if ( is_shop() ) {
				$current_page_id = get_option( 'woocommerce_shop_page_id' );
			}
			elseif ( is_cart() ) {
				$current_page_id = get_option( 'woocommerce_cart_page_id' );
			}
			elseif ( is_checkout() ) {
				$current_page_id = get_option( 'woocommerce_checkout_page_id' );
			}
			elseif ( is_account_page() ) {
				$current_page_id = get_option( 'woocommerce_myaccount_page_id' );
			}
		}
	}

	$pronto_landing_page_plugin_path = plugin_dir_path( __FILE__ ) . 'landing-page-cpt.php';

	//Get child theme's name
	$themename = get_option( 'stylesheet' );
	$themename = preg_replace( "/\W/", "_", strtolower( $themename ) );
	$site_options = get_option($themename);

	$site_layout_setting = $site_options['site_layout'];

	$main_nav_setting = $site_options['main_nav'];
	$header_open_tag  = '<header id="header"';
	$header_close_tag = '</header>';
	$header_additional_class = ' ' . get_post_meta( $current_page_id, 'header_additional_class', true );
	$disable_header_and_footer_option = get_post_meta( $current_page_id, 'custom_header_and_footer_disable', true );
	if ( 'Disable' != $disable_header_and_footer_option ) {
		if ( post_type_exists( 'header' ) ) {
			/* Landing Page Templates Header Group*/
			if ( $post->post_type == 'landing_page' ) {
				$options = landing_page_hook_header_group_func();
			} elseif( is_plugin_active( 'bbpress/bbpress.php' ) && 'forum' == $post->post_type ) {
				$options = get_option( 'header_group' );
				$option = $options['bbpress_header_group_setting'];
				$options['header_group'] = $option;
			} else {
				$options = get_header_group( $current_page_id );
			}
			
			if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
				if ( is_product() ) {
					$options['header_group'] = $options['single_product_header_group_setting'];
				} elseif ( is_product_category() ) {
					$options['header_group'] = $options['product_cat_header_group_setting'];
				}
			}

			if( $options['header_additional_class'] != '' and $main_nav_setting == 'fixed' ) {
				echo $header_open_tag . ' class="navbar-fixed-top ' . $options['header_additional_class'] . '">';
			} elseif( $options['header_additional_class'] == '' and $main_nav_setting == 'fixed' ) {
				echo $header_open_tag . ' class="navbar-fixed-top">';
			} elseif( $options['header_additional_class'] != '' and $main_nav_setting != 'fixed' ) {
				echo $header_open_tag . ' class="' . $options['header_additional_class'] . '">';
			} else {
				echo $header_open_tag . '>';
			}
	        
			$header_term = get_terms(
				'header_group',
				'slug=' . $options['header_group']
			);
	        
			if ( is_array( $header_term ) and count( $header_term ) > 0 ) {
				$get_headers_transient = get_transient( 'get_header_transient_' . $options['header_group'] );
				if( $get_headers_transient === false ) {
					$get_headers_transient = new WP_Query(array(
						'posts_per_page' => -1,
						'post_type'      => 'header',
						'taxonomy'       => 'header_group',
						'term'           => $header_term[0]->slug,
						'orderby'        => 'menu_order',
						'order'          => 'ASC'
					) );
					set_transient( 'get_header_transient_' . $options['header_group'], $get_headers_transient );
					$header_key_group = get_transient( 'header_key_group' );
					if ( false === $header_key_group ) {
						$header_key_group = array();
					}
					if ( ! in_array( 'get_header_transient_' . $options['header_group'], $header_key_group ) ) {
						$header_key_group[] = 'get_header_transient_' . $options['header_group'];
						set_transient( 'header_key_group', $header_key_group );
					}
				}
				if ( $get_headers_transient->have_posts() ) {
					$header_count = 0;
					while ( $get_headers_transient->have_posts() ):
						$get_headers_transient->the_post();
						$header_count++;
						$custom_header_segment = get_post_meta( $post->ID, 'custom_header_segment', true );
						if ( $custom_header_segment == 'segment_custom' ) {
							$custom_header_segment = 'segment';
							$custom_header_background       = get_post_meta( $post->ID, 'custom_header_background', true );
							$custom_header_background_image = get_post_meta( $post->ID, 'custom_header_background_image', true );
							$custom_header_background_repeat     = get_post_meta( $post->ID, 'custom_header_background_repeat', true );
							$custom_header_background_position   = get_post_meta( $post->ID, 'custom_header_background_position', true );
							$custom_header_background_attachment = get_post_meta( $post->ID, 'custom_header_background_attachment', true );
							$custom_header_background_size       = get_post_meta( $post->ID, 'custom_header_background_size', true );
							if ( !( $custom_header_background == '' and $custom_header_background_image == '' ) ) {
								if ( $custom_header_background != '' ) {
									$background_color = 'background-color: ' . $custom_header_background . '; ';
								} else {
									$background_color = '';
								}

								if ( $custom_header_background_image != '' ) {
									$background_image = 'background-image: url(\'' . $custom_header_background_image . '\');';
									$background_repeat = ' background-repeat: ' . $custom_header_background_repeat . ';';
									$background_position = ' background-position: ' . preg_replace( '/[\s_]/', ' ', $custom_header_background_position ) . ';';
									$background_attachment = ' background-attachment: ' . $custom_header_background_attachment . ';';
									if ( $custom_header_background_size == 'auto' ) {
										$background_size = '';
									} else {
										$background_size = ' background-size: ' . $custom_header_background_size  . ';';
									}
								} else {
									$background_image = '';
									$background_repeat     = '';
									$background_position   = '';
									$background_attachment = '';
									$background_size       = '';
								}
								$header_style = ' style="' . $background_color . $background_image . $background_repeat . $background_position . $background_attachment . $background_size . '"';
							}
						} else {
							$header_style = ' ';
						}

						$custom_header_segment_spacing = get_post_meta( $post->ID, 'custom_header_segment_spacing', true );
						$custom_header_segment_spacing = ' space-' . $custom_header_segment_spacing;
						$custom_header_segment_class = get_post_meta( $post->ID, 'custom_header_segment_class', true );
						if ( $custom_header_segment_class ) {
							$custom_header_segment_class = ' ' . $custom_header_segment_class;
						}
						
						//landing page setting
						$custom_landing_page_header_segment_class = get_post_meta( $current_page_id, 'custom_landing_page_header_additional_class', true );
						if ( $custom_landing_page_header_segment_class ) {
							$custom_header_segment_class = ' ' . $custom_landing_page_header_segment_class;
						}
						$container_class = 'container';
						$landing_page_template = get_post_meta( $current_page_id, 'custom_landing_page_template_option_settings', true );
						if ( $landing_page_template == 'narrow-template' ) {
							$container_class = 'container-narrow';
						}
						// Check Site Layout Option.
						if ( $site_layout_setting == 'full' ) {
							$div_segment_class = '<div class="' . $custom_header_segment . $custom_header_segment_spacing . $custom_header_segment_class . '"' . $header_style . '>';
							$div_container_class = '<div class="' . $container_class . '">';
							$div_close_tag       = '</div></div>';
						} elseif ( $site_layout_setting == 'fixed' ) {
							$div_segment_class   = '';
							$div_container_class = '<div class="' . $custom_header_segment . $custom_header_segment_spacing . $custom_header_segment_class . ' ' . $container_class . '"'  . $header_style  . '>';
							$div_close_tag = '</div>';
						}
						echo $div_segment_class . $div_container_class;
						echo do_shortcode( $post->post_content );
						echo $div_close_tag;
					endwhile;
				}
				wp_reset_query();
			}
			echo $header_close_tag;
		}
	}
}
