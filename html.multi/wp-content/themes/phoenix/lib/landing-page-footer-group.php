<?php
add_action( 'landing_page_footer_group', 'landing_page_footer_group_func' );
function landing_page_footer_group_func() {
	global $post;

	$current_page_id = NULL;
	if ( is_page() ) {
		$current_page_id = get_the_ID();
	} elseif ( is_home() ) {
		$current_page_id = get_option( 'page_for_posts', true );
	} elseif ( 'landing_page' == $post->post_type ) {
		$current_page_id = $post->ID;
	} elseif ( is_plugin_active ( $plugin = 'woocommerce/woocommerce.php' ) ) {
		if ( false === strpos( $_SERVER['REQUEST_URI'], 'wp-activate.php' ) ) {
			if ( is_shop() ) {
				$current_page_id = get_option( 'woocommerce_shop_page_id' );
			} elseif ( is_cart() ) {
				$current_page_id = get_option( 'woocommerce_cart_page_id' );
			} elseif ( is_checkout() ) {
				$current_page_id = get_option( 'woocommerce_checkout_page_id' );
			} elseif ( is_account_page() ) {
				$current_page_id = get_option( 'woocommerce_myaccount_page_id' );
			}
		}
	}

	//Get child theme's name
	$themename = get_option( 'stylesheet' );
	$themename = preg_replace( "/\W/", "_", strtolower( $themename ) );
	$site_options = get_option( $themename );

	$site_layout_setting = $site_options['site_layout'];

	$footer_open_tag  = '<footer id="footer">';
	$footer_close_tag = '</footer>';

	$disable_header_and_footer_option = get_post_meta( $current_page_id, 'custom_header_and_footer_disable', true );

	if ( 'Disable' != $disable_header_and_footer_option ) {
		if ( post_type_exists( 'footer' ) ) {
			$current_page_id = NULL;
			if ( is_page() ) {
				$current_page_id = get_the_ID();
			} elseif ( is_home() ) {
				$current_page_id = get_option( 'page_for_posts', true );
			} elseif ( 'landing_page' == $post->post_type ) {
				$current_page_id = $post->ID;
			}

			/* Landing Page Templates and bbPress forums Footer Group*/
			if ( 'landing_page' == $post->post_type ) {
				$options = landing_page_hook_footer_group_func();
			} elseif ( is_plugin_active ( 'bbpress/bbpress.php' ) && 'forum' == $post->post_type ) {
				$options = get_option( 'footer_group' );
				$option = $options['bbpress_footer_group_setting'];
				$options['footer_group'] = $option;
			} else {
				$options = get_footer_group( $current_page_id );
			}
	      
			$footer_additional_class = ' ' . get_post_meta( $post->ID, 'footer_additional_class', true );
			if ( '' != $options['footer_additional_class'] ) {
				echo '<footer id="footer" class="' . $options['footer_additional_class'] . '">';
			} else {
				echo $footer_open_tag;
			}

			$footer_term = get_terms(
				'footer_group',
				'slug=' . $options['footer_group']
			);

			if ( is_array( $footer_term ) and count( $footer_term ) > 0 ) {
				$get_footer_transient = get_transient( 'get_footer_transient_' . $options['footer_group'] );
				if ( false === $get_footer_transient ) {
					$get_footer_transient = new WP_Query( array(
						'posts_per_page' => -1,
						'post_type'      => 'footer',
						'taxonomy'       => 'footer_group',
						'term'           => $footer_term[0]->slug,
						'orderby'        => 'menu_order',
						'order'          => 'ASC'
					) );

					set_transient( 'get_footer_transient_' . $options['footer_group'], $get_footer_transient );

					//Set key group to collect key for cache.
					$footer_key_group = get_transient( 'footer_key_group' );
					if ( false === $footer_key_group ) {
						$footer_key_group = array();
					}
					
					if ( ! in_array( 'get_footer_transient_' . $options['footer_group'], $footer_key_group ) ) {
						$footer_key_group[] = 'get_footer_transient_' . $options['footer_group'];
						set_transient( 'footer_key_group', $footer_key_group );
					}
				}

				if ( $get_footer_transient->have_posts() ) {
					$footer_count = 0;
					global $post;
					while ( $get_footer_transient->have_posts() ):
						$get_footer_transient->the_post();
						$footer_count++;
						$custom_footer_segment = get_post_meta( $post->ID, 'custom_footer_segment', true );
						
						if ( 'segment_custom' == $custom_footer_segment ) {
							$custom_footer_segment = 'segment';

							$custom_footer_background = get_post_meta( $post->ID, 'custom_footer_background', true );
							$custom_footer_background_image = get_post_meta( $post->ID, 'custom_footer_background_image', true );
							$custom_footer_background_repeat = get_post_meta( $post->ID, 'custom_footer_background_repeat', true );
							$custom_footer_background_position = get_post_meta( $post->ID, 'custom_footer_background_position', true );
							$custom_footer_background_attachment = get_post_meta( $post->ID, 'custom_footer_background_attachment', true );
							$custom_footer_background_size = get_post_meta( $post->ID, 'custom_footer_background_size', true );

							if ( ! ( '' == $custom_footer_background and '' == $custom_footer_background_image ) ) {
								if ( '' != $custom_footer_background ) {
									$background_color = 'background-color: ' . $custom_footer_background . '; ';
								} else {
									$background_color = '';
								}
								
								if ( '' != $custom_footer_background_image ) {
									$background_image = 'background-image: url( \'' . $custom_footer_background_image . '\' );';
									$background_repeat = ' background-repeat: ' . $custom_footer_background_repeat . ';';
									$background_position = ' background-position: ' . preg_replace( '/[\s_]/', ' ', $custom_footer_background_position ) . ';';
									$background_attachment = ' background-attachment: ' . $custom_footer_background_attachment . ';';
								
									if ( 'auto' == $custom_footer_background_size ) {
										$background_size = '';
									} else {
										$background_size = ' background-size: ' . $custom_footer_background_size  . ';';
									}
								} else {
									$background_image = '';
								}
								$footer_style = ' style="' . $background_color . $background_image . $background_repeat . $background_position . $background_attachment . $background_size . '"';
							}
						} else {
							$footer_style = ' ';
						}

						$custom_footer_segment_spacing = get_post_meta( $post->ID, 'custom_footer_segment_spacing', true );
						$custom_footer_segment_spacing = ' space-' . $custom_footer_segment_spacing;

						$custom_footer_segment_class = get_post_meta( $post->ID, 'custom_footer_segment_class', true );
					
						if ( $custom_footer_segment_class ) {
							$custom_footer_segment_class = ' ' . $custom_footer_segment_class;
						}

						//landing page setting
						$custom_landing_page_footer_segment_class = get_post_meta( $current_page_id, 'custom_landing_page_footer_additional_class', true );
						
						if ( $custom_landing_page_footer_segment_class ) {
							$custom_footer_segment_class = ' ' . $custom_landing_page_footer_segment_class;
						}

						$container_class = 'container';
						$landing_page_template = get_post_meta( $current_page_id, 'custom_landing_page_template_option_settings', true );

						if ( 'narrow-template' == $landing_page_template ) {
							$container_class = 'container-narrow';
						}

						// Check Site Layout Option.
						if ( 'full' == $site_layout_setting ) {
							$div_segment_class	= '<div class="';
							$div_segment_class .= $custom_footer_segment;
							$div_segment_class .= $custom_footer_segment_spacing;
							$div_segment_class .= $custom_footer_segment_class;
							$div_segment_class .= '"' . $footer_style . '>';
							
							$div_container_class = '<div class="' . $container_class . '">';
							$div_close_tag       = '</div></div>';
						} elseif ( 'fixed' == $site_layout_setting ) {
							$div_segment_class    = '<div class="';
							$div_segment_class   .= $custom_footer_segment;
							$div_segment_class   .= $custom_footer_segment_spacing;
							$div_segment_class   .= ' ' . $container_class;
							$div_segment_class   .= $custom_footer_segment_class . '"';
							$div_segment_class   .= $footer_style . '>';
							
							$div_close_tag = '</div>';
						}
						echo $div_segment_class . $div_container_class;
						echo do_shortcode( $post->post_content );
						echo $div_close_tag;

					endwhile;
				}
			}
			echo $footer_close_tag;
		}
	}
}
