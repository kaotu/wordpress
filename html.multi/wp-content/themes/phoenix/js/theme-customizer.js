/**
 * This file adds some LIVE to the Theme Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and 
 * then make any necessary changes to the page using jQuery.
 */

( function( $ ) {
	var inner_page;
	var site_bg_color, site_bg_img, site_bg_position, site_bg_repeat, site_bg_attachment, site_bg_size;

	// Get value inner page option from Site Options.
	var inner_page_option = theme_name_var.inner_option;

	//Font Heading and Body
	wp.customize( theme_name_var.theme_name+'[headers_font_options]', function( value ) {
		value.bind( function( newval ) {
			$( 'h1, h2, h3, h4, h5, h6' ).css( 'font-family', newval );			
		} );
	} );
	wp.customize( theme_name_var.theme_name+'[body_font_options]', function( value ) {
		value.bind( function( newval ) {
			$( 'input, button, select, textarea, body' ).css( 'font-family', newval );			
		} );
	} );
	//Background Color for Body
	wp.customize( theme_name_var.theme_name+'[site_background][color]', function( value ) {
		value.bind( function( newval_site_bg_color ) {
			site_bg_color= newval_site_bg_color;
			$( 'body' ).css( 'background-color', newval_site_bg_color );

			// Check if clear color value, it will be white background.
			if (newval_site_bg_color) {
					var get_site_bg_color = newval_site_bg_color;
				} else {
					var get_site_bg_color = '#ffffff';
				}

			// Check if inner page background is checked, body-background will change follow the background-color.
			if(typeof(inner_page) == 'undefined' && inner_page_option) {
				$( '.body-background' ).css('background-color', get_site_bg_color);
			} else if(inner_page) {
				$( '.body-background' ).css('background-color', get_site_bg_color);
			}
		} );
	} );
	//Background Image for Body
	wp.customize( theme_name_var.theme_name+'[site_background][image]', function( value ) {
		value.bind( function( newval_site_bg_img ) {
			site_bg_img = newval_site_bg_img;
			$( 'body' ).css( 'background-image', 'url('+newval_site_bg_img+')' );

			// Check if inner page background is checked, body-background will change follow the background-image.
			if(typeof(inner_page) == 'undefined' && inner_page_option) {
				$( '.body-background' ).css( 'background-image', 'url('+newval_site_bg_img+')' );
			} else if(inner_page) {
				$( '.body-background' ).css( 'background-image', 'url('+newval_site_bg_img+')' );
			}	
		} );
	} );
	//Background Image Position for Body
	wp.customize( theme_name_var.theme_name+'[site_background][position]', function( value ) {
		value.bind( function( newval_site_bg_position ) {
			site_bg_position = newval_site_bg_position;
			$( 'body' ).css( 'background-position', newval_site_bg_position );		

			// Check if inner page background is checked, body-background will change follow the background-position.
			if(typeof(inner_page) == 'undefined' && inner_page_option) {
				$( '.body-background' ).css( 'background-position', newval_site_bg_position );
			} else if(inner_page) {
				$( '.body-background' ).css( 'background-position', newval_site_bg_position );
			}		
		} );
	} );
	//Background Repeat for Body
	wp.customize( theme_name_var.theme_name+'[site_background][repeat]', function( value ) {
		value.bind( function( newval_site_bg_repeat ) {
			site_bg_repeat = newval_site_bg_repeat;
			$( 'body' ).css( 'background-repeat', newval_site_bg_repeat );	

			// Check if inner page background is checked, body-background will change follow the background-repeat.
			if(typeof(inner_page) == 'undefined' && inner_page_option) {
				$( '.body-background' ).css( 'background-repeat', newval_site_bg_repeat );	
			} else if(inner_page) {
				$( '.body-background' ).css( 'background-repeat', newval_site_bg_repeat );	
			}			
		} );
	} );
	//Background Image Attachment for Body
	wp.customize( theme_name_var.theme_name+'[site_background][attachment]', function( value ) {
		value.bind( function( newval_site_bg_attachment ) {
			site_bg_attachment = newval_site_bg_attachment;
			$( 'body' ).css( 'background-attachment', newval_site_bg_attachment );

			// Check if inner page background is checked, body-background will change follow the background-attachment.
			if(typeof(inner_page) == 'undefined' && inner_page_option) {
				$( '.body-background' ).css( 'background-attachment', newval_site_bg_attachment );	
			} else if(inner_page) {
				$( '.body-background' ).css( 'background-attachment', newval_site_bg_attachment );	
			}			
		} );
	} );
	//Background Image Size for Body
	wp.customize( theme_name_var.theme_name+'[site_background][size]', function( value ) {
		value.bind( function( newval_site_bg_size ) {
			site_bg_size = newval_site_bg_size;
			$( 'body' ).css( 'background-size', newval_site_bg_size );	


			// Check if inner page background is checked, body-background will change follow the background-size.
			if(typeof(inner_page) == 'undefined' && inner_page_option) {
				$( '.body-background' ).css( 'background-size', newval_site_bg_size );	
			} else if(inner_page) {
				$( '.body-background' ).css( 'background-size', newval_site_bg_size );	
			}			
		} );
	} );
	//Background for Inner Page
	wp.customize( theme_name_var.theme_name+'[background_inner_page]', function( value ) {
		value.bind( function( newval_inner_page ) {
			inner_page = newval_inner_page;
			var site_bg_color_option = theme_name_var.site_bgcolor_option;
			var site_bg_img_option = theme_name_var.site_bgimg_option;
			var site_bg_position_option = theme_name_var.site_bgposition_option;
			var site_bg_repeat_option = theme_name_var.site_bgrepeat_option;
			var site_bg_attachment_option = theme_name_var.site_bgattachement_option;
			var site_bg_size_option = theme_name_var.site_bgsize_option;

    		if(newval_inner_page) {
    			// First time site_bg_color is empty, it will get value from site_bg_color_option(in db).
    			// Background Color
    			if( (typeof(site_bg_color) == 'undefined') ) {
    				$( '.body-background' ).css('background-color', site_bg_color_option);
    			} else {
    				$( '.body-background' ).css('background-color', site_bg_color);
    			} 

    			//Background Image
    			if( (typeof(site_bg_img) == 'undefined') ) {
    				$( '.body-background' ).css( 'background-image', 'url('+site_bg_img_option+')' );
    			} else {
    				$( '.body-background' ).css( 'background-image', 'url('+site_bg_img+')' );
    			}

    			// Background Position
    			if( (typeof(site_bg_position) == 'undefined') ) {
    				$( '.body-background' ).css( 'background-position', site_bg_position_option );
    			} else {
    				$( '.body-background' ).css( 'background-position', site_bg_position );
    			}

    			// Background Repeat
    			if( (typeof(site_bg_repeat) == 'undefined') ) {
    				$( '.body-background' ).css( 'background-position', site_bg_repeat_option );
    			} else {
    				$( '.body-background' ).css( 'background-position', site_bg_repeat );
    			}

    			// Background Attachment
    			if( (typeof(site_bg_attachment) == 'undefined') ) {
    				$( '.body-background' ).css( 'background-position', site_bg_attachment_option );
    			} else {
    				$( '.body-background' ).css( 'background-position', site_bg_attachment );
    			}

    			// Background Attachment
    			if( (typeof(site_bg_size) == 'undefined') ) {
    				$( '.body-background' ).css( 'background-position', site_bg_size_option );
    			} else {
    				$( '.body-background' ).css( 'background-position', site_bg_size );
    			}

    		} else {
    			$( '.body-background' ).css('background-color', '#ffffff');
    			$( '.body-background' ).css('background-image', 'none');
    		}
		} );
	} );	
	//Heading Font Color
	wp.customize( theme_name_var.theme_name+'[default_heading_color]', function( value ) {
		value.bind( function( newval ) {
			$( 'h1, h2, h3, h4, h5, h6' ).css( 'color', newval );			
		} );
	} );
	// Body Font Color
	wp.customize( theme_name_var.theme_name+'[default_body_text_color]', function( value ) {
		value.bind( function( newval ) {
			$( 'body' ).css( 'color', newval );		
		} );
	} );
	//Link Font Color
	wp.customize( theme_name_var.theme_name+'[default_link_color]', function( value ) {
		value.bind( function( newval ) {
			$( 'a' ).css( 'color', newval );			
		} );
	} );
	//Accent Color
	wp.customize( theme_name_var.theme_name+'[accent_color]', function( value ) {
		value.bind( function( newval ) {
			$( '.accent' ).css( 'color', newval );			
		} );
	} );
	//Body Text Color for Segment 1
	wp.customize( theme_name_var.theme_name+'[body_text_color_segment_1]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment1' ).css( 'color', newval );			
		} );
	} );
	//Heading Color for Segment 1
	wp.customize( theme_name_var.theme_name+'[heading_font_color_segment_1]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment1 h1, .segment1 h2, .segment1 h3, .segment1 h4, .segment1 h5, .segment1 h6' ).css( 'color', newval );			
		} );
	} );
	//Background Color for Segment 1
	wp.customize( theme_name_var.theme_name+'[background_segment_1][color]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment1' ).css( 'background-color', newval );			
		} );
	} );
	//Background Image for Segment 1
	wp.customize( theme_name_var.theme_name+'[background_segment_1][image]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment1' ).css( 'background-image', 'url('+newval+')' );	
		} );
	} );
	//Background Image Position for Segment 1
	wp.customize( theme_name_var.theme_name+'[background_segment_1][position]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment1' ).css( 'background-position', newval );			
		} );
	} );
	//Background Repeat for Segment 1
	wp.customize( theme_name_var.theme_name+'[background_segment_1][repeat]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment1' ).css( 'background-repeat', newval );			
		} );
	} );
	//Background Image Attachment for Segment 1
	wp.customize( theme_name_var.theme_name+'[background_segment_1][attachment]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment1' ).css( 'background-attachment', newval );			
		} );
	} );
	//Background Image Size for Segment 1
	wp.customize( theme_name_var.theme_name+'[background_segment_1][size]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment1' ).css( 'background-size', newval );			
		} );
	} );

	//Body Text Color for Segment 2
	wp.customize( theme_name_var.theme_name+'[body_text_color_segment_2]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment2' ).css( 'color', newval );			
		} );
	} );
	//Heading Color for Segment 2
	wp.customize( theme_name_var.theme_name+'[heading_font_color_segment_2]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment2 h1, .segment2 h2, .segment2 h3, .segment2 h4, .segment2 h5, .segment2 h6' ).css( 'color', newval );			
		} );
	} );
	//Background Color for Segment 2
	wp.customize( theme_name_var.theme_name+'[background_segment_2][color]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment2' ).css( 'background-color', newval );			
		} );
	} );
	//Background Image for Segment 2
	wp.customize( theme_name_var.theme_name+'[background_segment_2][image]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment2' ).css( 'background-image', 'url('+newval+')' );		
		} );
	} );
	//Background Image Position for Segment 2
	wp.customize( theme_name_var.theme_name+'[background_segment_2][position]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment2' ).css( 'background-position', newval );			
		} );
	} );
	//Background Repeat for Segment 2
	wp.customize( theme_name_var.theme_name+'[background_segment_2][repeat]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment2' ).css( 'background-repeat', newval );			
		} );
	} );
	//Background Image Attachment for Segment 2
	wp.customize( theme_name_var.theme_name+'[background_segment_2][attachment]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment2' ).css( 'background-attachment', newval );			
		} );
	} );
	//Background Image Attachment for Segment 2
	wp.customize( theme_name_var.theme_name+'[background_segment_2][size]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment2' ).css( 'background-size', newval );			
		} );
	} );

	//Body Text Color for Segment 3
	wp.customize( theme_name_var.theme_name+'[body_text_color_segment_3]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment3' ).css( 'color', newval );			
		} );
	} );
	//Heading Color for Segment 3
	wp.customize( theme_name_var.theme_name+'[heading_font_color_segment_3]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment3 h1, .segment3 h2, .segment3 h3, .segment3 h4, .segment3 h5, .segment3 h6' ).css( 'color', newval );			
		} );
	} );
	//Background Color for Segment 3
	wp.customize( theme_name_var.theme_name+'[background_segment_3][color]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment3' ).css( 'background-color', newval );			
		} );
	} );
	//Background Image for Segment 3
	wp.customize( theme_name_var.theme_name+'[background_segment_3][image]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment3' ).css( 'background-image', 'url('+newval+')' );		
		} );
	} );
	//Background Image Position for Segment 3
	wp.customize( theme_name_var.theme_name+'[background_segment_3][position]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment3' ).css( 'background-position', newval );			
		} );
	} );
	//Background Repeat for Segment 3
	wp.customize( theme_name_var.theme_name+'[background_segment_3][repeat]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment3' ).css( 'background-repeat', newval );			
		} );
	} );
	//Background Image Attachment for Segment 3
	wp.customize( theme_name_var.theme_name+'[background_segment_3][attachment]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment3' ).css( 'background-attachment', newval );			
		} );
	} );
	//Background Image Size for Segment 3
	wp.customize( theme_name_var.theme_name+'[background_segment_3][size]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment3' ).css( 'background-size', newval );			
		} );
	} );

	//Body Text Color for Segment 4
	wp.customize( theme_name_var.theme_name+'[body_text_color_segment_4]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment4' ).css( 'color', newval );			
		} );
	} );
	//Heading Color for Segment 4
	wp.customize( theme_name_var.theme_name+'[heading_font_color_segment_4]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment4 h1, .segment4 h2, .segment4 h3, .segment4 h4, .segment4 h5, .segment4 h6' ).css( 'color', newval );			
		} );
	} );
	//Background Color for Segment 4
	wp.customize( theme_name_var.theme_name+'[background_segment_4][color]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment4' ).css( 'background-color', newval );			
		} );
	} );
	//Background Image for Segment 4
	wp.customize( theme_name_var.theme_name+'[background_segment_4][image]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment4' ).css( 'background-image', 'url('+newval+')' );		
		} );
	} );
	//Background Image Position for Segment 4
	wp.customize( theme_name_var.theme_name+'[background_segment_4][position]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment4' ).css( 'background-position', newval );			
		} );
	} );
	//Background Repeat for Segment 4
	wp.customize( theme_name_var.theme_name+'[background_segment_4][repeat]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment4' ).css( 'background-repeat', newval );			
		} );
	} );
	//Background Image Attachment for Segment 4
	wp.customize( theme_name_var.theme_name+'[background_segment_4][attachment]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment4' ).css( 'background-attachment', newval );			
		} );
	} );
	//Background Image Attachment for Segment 4
	wp.customize( theme_name_var.theme_name+'[background_segment_4][size]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment4' ).css( 'background-size', newval );			
		} );
	} );

	//Body Text Color for Segment 5
	wp.customize( theme_name_var.theme_name+'[body_text_color_segment_5]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment5' ).css( 'color', newval );			
		} );
	} );
	//Heading Color for Segment 5
	wp.customize( theme_name_var.theme_name+'[heading_font_color_segment_5]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment5 h1, .segment5 h2, .segment5 h3, .segment5 h4, .segment5 h5, .segment5 h6' ).css( 'color', newval );			
		} );
	} );
	//Background Color for Segment 5
	wp.customize( theme_name_var.theme_name+'[background_segment_5][color]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment5' ).css( 'background-color', newval );			
		} );
	} );
	//Background Image for Segment 5
	wp.customize( theme_name_var.theme_name+'[background_segment_5][image]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment5' ).css( 'background-image', 'url('+newval+')' );		
		} );
	} );
	//Background Image Position for Segment 5
	wp.customize( theme_name_var.theme_name+'[background_segment_5][position]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment5' ).css( 'background-position', newval );			
		} );
	} );
	//Background Repeat for Segment 5
	wp.customize( theme_name_var.theme_name+'[background_segment_5][repeat]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment4' ).css( 'background-repeat', newval );			
		} );
	} );
	//Background Image Attachment for Segment 5
	wp.customize( theme_name_var.theme_name+'[background_segment_5][attachment]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment4' ).css( 'background-attachment', newval );			
		} );
	} );
	//Background Image Size for Segment 5
	wp.customize( theme_name_var.theme_name+'[background_segment_5][size]', function( value ) {
		value.bind( function( newval ) {
			$( '.segment4' ).css( 'background-size', newval );			
		} );
	} );
} )( jQuery );