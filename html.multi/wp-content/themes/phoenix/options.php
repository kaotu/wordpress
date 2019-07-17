<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 */

function optionsframework_options() {
	// If using image radio buttons, define a directory path
	$imagepath =  get_template_directory_uri() . '/images/';

	// Post Meta Data Defaults
	$post_meta_data_defaults = array(
		'date'          => 1,
		'author'        => 1,
		'categories'    => 1,
		'tags'          => 1
	);

	// Post Meta Data Array
	$post_meta_data_array = array(
		'date'          => 'Date',
		'author'        => 'Author',
		'categories'    => 'Categories',
		'tags'          => 'Tags'
	);

	// Background Defaults
	$background_defaults = array(
		'color'      => '',
		'image'      => '',
		'repeat'     => 'repeat',
		'position'   => 'top center',
		'attachment' => 'scroll',
		'size'       => 'auto'
	);

	// Typography Defaults
	$typography_defaults = array(
		'size'  => '15px',
		'face'  => 'georgia',
		'style' => 'bold',
		'color' => '#bada55'
	);

	$site_layout = array(
		'fixed' => 'Fixed',
		'full'  => 'Full'
	);

	$blog_style = array(
		'a' => 'Blog A',
		'b' => 'Blog B',
		'c' => 'Blog C'
	);

	$page_title_option = array(
		'default' => 'Page Content Area',
		'segment' => 'Segment Above Page',
		'none'    => 'None'
	);

	$page_title_segment = array(
		'segment1' => 'Segment 1',
		'segment2' => 'Segment 2',
		'segment3' => 'Segment 3',
		'segment4' => 'Segment 4',
		'segment5' => 'Segment 5',
	);

	$main_nav = array(
		'fixed'     => 'Fixed',
		'non_fixed' => 'Non-Fixed'
	);

	$default_sidebar = array(
		'right' =>  $imagepath . 'sidebar-leftsidebar.jpg',
		'left'  =>  $imagepath . 'sidebar-rightsidebar.jpg'
	);

	$base_color_option = array(
		'light' => 'Light',
		'dark'  => 'Dark'
	);

	$breadcrumb_options = array(
		'on'  => 'Enable',
		'off' => 'Disable'
	);

	$page_title_spacing = array(
		'mini'    => 'Mini',
		'small'   => 'Small',
		'medium'  => 'Medium',
		'large'   => 'Large',
		'huge'    => 'Huge'
	);

	$breadcrumb_segment = array(
		'segment1'       => 'Segment 1',
		'segment2'       => 'Segment 2',
		'segment3'       => 'Segment 3',
		'segment4'       => 'Segment 4',
		'segment5'       => 'Segment 5',
		'none'           => 'None'
	);

	//Typography Options
	$typography_options = array(
		'"Helvetica Neue", Helvetica, sans-serif' => 'Helvetica Neue',
		'Arial, sans-serif'                       => 'Arial',
		'"Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Verdana, Tahoma, sans-serif' => 'Lucida Grande',
		'Georgia, serif' => 'Georgia',
		'"Open Sans", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Verdana, Tahoma, sans-serif' => 'Open Sans',
		'Lato, sans-serif'      => 'Lato',
		'"Source Sans Pro", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Verdana, Tahoma, sans-serif' => 'Source Sans Pro',
		'"Signika", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Verdana, Tahoma, sans-serif' => 'Signika',
		'"Vollkorn", Georgia, serif' => 'Vollkorn',
		'"Lora", Georgia, serif' => 'Lora'
	);

	// Pull all the categories into an array
	$options_categories = array();
	$options_categories_obj = get_categories();
	foreach ($options_categories_obj as $category) {
		$options_categories[$category->cat_ID] = $category->cat_name;
	}

	// Pull all tags into an array
	$options_tags = array();
	$options_tags_obj = get_tags();
	foreach ( $options_tags_obj as $tag ) {
		$options_tags[$tag->term_id] = $tag->name;
	}

	// Pull all the pages into an array
	$options_pages = array();
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
	$options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
		$options_pages[$page->ID] = $page->post_title;
	}

	$options = array();

	$options[] = array(
		'name' => __('Basic Settings', 'options_framework_theme'),
		'type' => 'heading'
	);

	$options['site_layout'] = array(
		'name'    => 'Site Layout',
		'id'      => 'site_layout',
		'std'     => 'full',
		'type'    => 'radio',
		'options' => $site_layout
	);

	$options['blog_style'] = array(
		'name'    => 'Blog Style',
		'id'      => 'blog_style',
		'std'     => 'a',
		'type'    => 'radio',
		'options' => $blog_style
	);

	$options['post_meta_data'] = array(
		'name'    => 'Post Meta Data',
		'id'      => 'post_meta_data',
		'std'     => $post_meta_data_defaults,
		'type'    => 'multicheck',
		'options' => $post_meta_data_array
	);

	$options['post_meta_date'] = array(
		'name'    => 'Date',
		'id'      => 'post_meta_date',
		'std'     => 1,
		'type'    => 'checkbox'
	);

	$options['post_meta_author'] = array(
		'name'    => 'Author',
		'id'      => 'post_meta_author',
		'std'     => 1,
		'type'    => 'checkbox'
	);

	$options['post_meta_categories'] = array(
		'name'    => 'Categories',
		'id'      => 'post_meta_categories',
		'std'     => false,
		'type'    => 'checkbox'
	);

	$options['post_meta_tags'] = array(
		'name'    => 'Tags',
		'id'      => 'post_meta_tags',
		'std'     => false,
		'type'    => 'checkbox'
	);

	$options['blog_filter_except_category'] = array(
		'name'    => 'Exclude Cats',
		'id'      => 'blog_filter_except_category',
		'std'     => false,
		'type'    => 'checkbox'
	);

	$options['blog_filter_only_category'] = array(
		'name'    => 'Only Cats',
		'id'      => 'blog_filter_only_category',
		'std'     => false,
		'type'    => 'checkbox'
	);

	$options['blog_filter_except_tag'] = array(
		'name'    => 'Exclude Tags',
		'id'      => 'blog_filter_except_tag',
		'std'     => false,
		'type'    => 'checkbox'
	);

	$options['blog_filter_only_tag'] = array(
		'name'    => 'Only Tags',
		'id'      => 'blog_filter_only_tag',
		'std'     => false,
		'type'    => 'checkbox'
	);

	$options['default_sidebar'] = array(
		'name'    => 'Default Sidebar',
		'id'      => 'default_sidebar',
		'std'     => 'left',
		'type'    => 'images',
		'options' => $default_sidebar
	);

	$options['default_post_sidebar'] = array(
		'name'    => 'Sidebar Location',
		'id'      => 'default_post_sidebar',
		'std'     => 'default',
		'type'    => 'images',
		'options' => $default_sidebar
	);


	$options['site_background'] = array(
		'name'    => 'Background',
		'id'      => 'site_background',
		'std'     => $background_defaults,
		'type'    => 'background'
	);

	$options['background_inner_page'] = array(
		'name'    => 'Use for Inner Page Background',
		'id'      => 'background_inner_page',
		'std'     => false,
		'type'    => 'checkbox'
	);

	$options['page_title_option'] = array(
		'name'    => 'Page Title Option',
		'id'      => 'page_title_option',
		'type'    => 'select',
		'std'     => 'default',
		'options' => $page_title_option
	);

	$options['page_title_segment'] = array(
		'name'    => 'Page Title Segment',
		'id'      => 'page_title_segment',
		'type'    => 'select',
		'std'     => 'segment1',
		'options' => $page_title_segment
	);

	$options['page_title_spacing'] = array(
		'name'    => 'Page Title Spacing',
		'id'      => 'page_title_spacing',
		'type'    => 'select',
		'std'     => 'mini',
		'options' => $page_title_spacing
	);

	$options['logo'] = array(
		'name'    => 'Site Logo',
		'id'      => 'logo',
		'type'    => 'upload'
	);

	$options['fav_icon'] = array(
		'name'    => 'Fav Icon',
		'desc'    => 'Please use a 16px by 16px png or ico image.',
		'id'      => 'fav_icon',
		'type'    => 'upload'
	);

	$options['main_nav'] = array(
		'name'    => 'Main Navigation',
		'id'      => 'main_nav',
		'std'     => 'non_fixed',
		'type'    => 'radio',
		'options' => $main_nav
	);

	$options['breadcrumb_nav'] = array(
		'name'    => 'Breadcrumb Navigation',
		'id'      => 'breadcrumb_nav',
		'std'     => 'off',
		'type'    => 'radio',
		'options' => $breadcrumb_options
	);

	$options['breadcrumb_nav_segment'] = array(
		'name'    => 'Breadcrumb Navigation Segment',
		'id'      => 'breadcrumb_nav_segment',
		'std'     => 'segment1',
		'type'    => 'select',
		'options' => $breadcrumb_segment
	);

	/**
	 * For $settings options see:
	 * http://codex.wordpress.org/Function_Reference/wp_editor
	 *
	 * 'media_buttons' are not supported as there is no post to attach items to
	 * 'textarea_name' is set by the 'id' you choose
	 */

	$wp_editor_settings = array(
		'wpautop'       => true, // Default
		'textarea_rows' => 5,
		'tinymce'       => array( 'plugins' => 'wordpress' )
	);

	/* Design Options */
	$options[] = array(
		'name'    => 'Design',
		'type'    => 'heading'
	);

	$options['base_color_option'] = array(
		'name'    => 'Base Color Scheme',
		'id'      => 'base_color_option',
		'type'    => 'select',
		'class'   => 'mini',
		'options' => $base_color_option
	);

	$options['basic_color_scheme'] = array(
		'name' => 'Basic Color Scheme',
		'id'   => 'basic_color_scheme',
		'type' => 'info'
	);

	$options['default_heading_color'] = array(
		'name' => 'Default Heading Color',
		'id'   => 'default_heading_color',
		'std'  => '#333333',
		'type' => 'color'
	);

	$options['default_body_text_color'] = array(
		'name' => 'Default Body Text  Color',
		'id'   => 'default_body_text_color',
		'std'  => '#333333',
		'type' => 'color'
	);

	$options['default_link_color'] = array(
		'name' => 'Default Link Color',
		'id'   => 'default_link_color',
		'std'  => '#0088cc',
		'type' => 'color'
	);

	$options['accent_color'] = array(
		'name' => 'Accent Color',
		'id'   => 'accent_color',
		'std'  => '#dd3333',
		'type' => 'color'
	);

	$options['fonts'] = array(
		'name' => 'Fonts',
		'id'   => 'fonts',
		'type' => 'info'
	);

	$options['headers_font_options'] = array(
		'name'    => 'Headers Font',
		'id'      => 'headers_font_options',
		'desc'    => 'Choose a font style for the headers',
		'type'    => 'select',
		'class'   => 'mini',
		'options' => $typography_options
	);

	$options['body_font_options'] = array(
		'name'    => 'Body Font',
		'id'      => 'body_font_options',
		'desc'    => 'Choose a font style for the body text',
		'type'    => 'select',
		'class'   => 'mini',
		'options' => $typography_options
	);

	$options[] = array(
		'name' => 'Segment Styles',
		'id'   => 'segment_styles',
		'type' => 'info'
	);

	$options['segment_1'] = array(
		'name' => 'Segment 1',
		'id'   => 'segment_1',
		'desc' => 'Choose the colorstyle to be applied to the segment',
		'type' => 'info'
	);
	$options['heading_font_color_segment_1'] = array(
		'name' => 'Heading Font Color',
		'id'   => 'heading_font_color_segment_1',
		'std'  => '#333333',
		'type' => 'color'
	);

	$options['body_text_color_segment_1'] = array(
		'name' => 'Body Text Color',
		'id'   => 'body_text_color_segment_1',
		'std'  => '#333333',
		'type' => 'color'
	);
	$options['background_segment_1'] = array(
		'name' => 'Background',
		'id'   => 'background_segment_1',
		'std'  => $background_defaults,
		'type' => 'background'
	);

	$options['segment_2'] = array(
		'name' => 'Segment 2',
		'id'   => 'segment_2',
		'desc' => 'Choose the color style to be applied to the segment',
		'type' => 'info'
	);

	$options['heading_font_color_segment_2'] = array(
		'name' => 'Heading Font Color',
		'id'   => 'heading_font_color_segment_2',
		'std'  => '#333333',
		'type' => 'color'
	);

	$options['body_text_color_segment_2'] = array(
		'name' => 'Body Text Color',
		'id'   => 'body_text_color_segment_2',
		'std'  => '#333333',
		'type' => 'color'
	);

	$options['background_segment_2'] = array(
		'name' => 'Background',
		'id'   => 'background_segment_2',
		'std'  => $background_defaults,
		'type' => 'background'
	);

	$options['segment_3'] = array(
		'name' => 'Segment 3',
		'id'   => 'segment_3',
		'desc' => 'Choose the color style to be applied to the segment',
		'type' => 'info'
	);

	$options['heading_font_color_segment_3'] = array(
		'name' => 'Heading Font Color',
		'id'   => 'heading_font_color_segment_3',
		'std'  => '#333333',
		'type' => 'color'
	);

	$options['body_text_color_segment_3'] = array(
		'name' => 'Body Text Color',
		'id'   => 'body_text_color_segment_3',
		'std'  => '#333333',
		'type' => 'color'
	);

	$options['background_segment_3'] = array(
		'name' => 'Background',
		'id'   => 'background_segment_3',
		'std'  => $background_defaults,
		'type' => 'background'
	);

	$options['segment_4'] = array(
		'name' => 'Segment 4',
		'id'   => 'segment_4',
		'desc' => 'Choose the color style to be applied to the segment',
		'type' => 'info'
	);
	$options['heading_font_color_segment_4'] = array(
		'name' => 'Heading Font Color',
		'id'   => 'heading_font_color_segment_4',
		'std'  => '#ffffff',
		'type' => 'color'
	);
	$options['body_text_color_segment_4'] = array(
		'name' => 'Body Text Color',
		'id'   => 'body_text_color_segment_4',
		'std'  => '#ffffff',
		'type' => 'color'
	);
	$options['background_segment_4'] = array(
		'name' => 'Background',
		'id'   => 'background_segment_4',
		'std'  => $background_defaults,
		'type' => 'background'
	);

	$options['segment_5'] = array(
		'name' => 'Segment 5',
		'id'   => 'segment_5',
		'desc' => 'Choose the color style to be applied to the segment',
		'type' => 'info'
	);
	$options['heading_font_color_segment_5'] = array(
		'name' => 'Heading Font Color',
		'id'   => 'heading_font_color_segment_5',
		'std'  => '#ffffff',
		'type' => 'color'
	);
	$options['body_text_color_segment_5'] = array(
		'name' => 'Body Text Color',
		'id'   => 'body_text_color_segment_5',
		'std'  => '#ffffff',
		'type' => 'color'
	);
	$options['background_segment_5'] = array(
		'name' => 'Background',
		'id'   => 'background_segment_5',
		'std'  => $background_defaults,
		'type' => 'background'
	);

	return $options;
}

/**
 * Front End Customizer
 *
 * WordPress 3.4 Required
 */
add_action( 'customize_register', 'options_theme_customizer_register' );

function options_theme_customizer_register($wp_customize) {
	$options = optionsframework_options();

	/* Get child theme's name */
	$themename = get_option( 'stylesheet' );
	$themename = preg_replace("/\W/", "_", strtolower($themename) );

	/* Site Identity Section - Admin Email */
	$wp_customize->add_setting( $themename.'[admin_email]', array(
		'default'    => get_option( 'admin_email' ),
		'type'       => 'option',
		'capability' => 'manage_options',
		'transport'  => 'postMessage'
	) );

	$wp_customize->add_control( $themename.'[admin_email]', array(
		'label'    => 'Admin Email',
		'section'  => 'title_tagline',
		'priority' => 100
	) );

	/* Branding Section -- upload logo */
	$wp_customize->add_section( 'branding', array(
		'title'    => 'Branding',
		'priority' => 50
	) );

	$wp_customize->add_setting( $themename.'[logo]', array(
		//'default'  => $options['logo']['std'],
		'type'     => 'option'
	) );

	$wp_customize->add_control(
		new WP_Customize_Image_Control( $wp_customize, $themename.'_logo', array(
			'label'    => $options['logo']['name'],
			'section'  => 'branding',
			'settings' => $themename.'[logo]'
		)
	) );

	/* SVG Logo */
	$wp_customize->add_setting( $themename . '[svg_logo]', array(
		'type' => 'option'
	) );

	$wp_customize->add_control(
		new WP_Customize_Image_Control( $wp_customize, $themename . '_svg_logo', array(
			'label'       => 'SVG Logo',
			'section'     => 'branding',
			'settings'    => $themename . '[svg_logo]',
			'type'        => 'textarea',
			'description' => 'To avoid p tag problem, please use one line SVG (No new line)',
		)
	) );

	/* Site Layout Section */
	$wp_customize->add_section( 'site_layout', array(
		'title'    => 'Site Layout',
		'priority' => 125
	) );

	/* Main Nav - can be either fixed or non-fixed */
	$wp_customize->add_setting( $themename.'[main_nav]', array(
		'default' => $options['main_nav']['std'],
		'type'    => 'option'
	) );

	$wp_customize->add_control( $themename.'_main_nav', array(
		'label'    => $options['main_nav']['name'],
		'section'  => 'site_layout',
		'settings' => $themename.'[main_nav]',
		'type'     => $options['main_nav']['type'],
		'choices'  => $options['main_nav']['options']
	) );

	/* Site Layout - can be either fixed-width or full-width */
	$wp_customize->add_setting( $themename.'[site_layout]', array(
		'default' => $options['site_layout']['std'],
		'type'    => 'option'
	) );

	$wp_customize->add_control( $themename.'_site_layout', array(
		'label'    => $options['site_layout']['name'],
		'section'  => 'site_layout',
		'settings' => $themename.'[site_layout]',
		'type'     => $options['site_layout']['type'],
		'choices'  => $options['site_layout']['options']
	) );

	/* Sidebar */
	$wp_customize->add_setting( $themename.'[default_sidebar]', array(
		'default' => $options['default_sidebar']['std'],
		'type'    => 'option'
	) );

	$wp_customize->add_control( $themename.'_default_sidebar', array(
		'label'    => $options['default_sidebar']['name'],
		'section'  => 'site_layout',
		'settings' => $themename.'[default_sidebar]',
		'type'     => 'radio',
		'choices'  => array(
			'left'  => __( 'Right Sidebar', 'options_framework_theme' ),
			'right' => __( 'Left Sidebar', 'options_framework_theme' )
		)
	) );

	/* Blog Layout section */
	$wp_customize->add_section( 'blog_layout', array(
		'title'    => 'Blog Layout',
		'priority' => 130
	) );

	/* Blog Style - can be A or B or C */
	$wp_customize->add_setting( $themename.'[blog_style]', array(
		'default' => $options['blog_style']['std'],
		'type'    => 'option'
	) );

	$wp_customize->add_control( $themename.'_blog_style', array(
		'label'    => 'Style',
		'section'  => 'blog_layout',
		'settings' => $themename.'[blog_style]',
		'type'     => $options['blog_style']['type'],
		'choices'  => $options['blog_style']['options']
	) );

	/* Blog Byline */
	$wp_customize->add_setting( $themename.'[blog_byline]', array(
		'default'   => '',
		'type'    => 'option',
	) );

	$wp_customize->add_control( $themename.'_blog_byline', array(
		'label'    => 'Show in Byline',
		'section'  => 'blog_layout',
		'settings' => $themename.'[blog_byline]',
		'type'     => 'hidden',
	) );

	/** Post Meta Data: Date **/
	$wp_customize->add_setting( $themename.'[post_meta_date]', array(
		'label' => 'Post Meta Data',
		'default' => 1,
		'type' => 'option'
	) );
	$wp_customize->add_control( $themename.'[post_meta_date]', array(
		'label' => 'Date',
		'type' => 'checkbox',
		'section' => 'blog_layout'
	) );

	/** Post Meta Data: Author **/
	$wp_customize->add_setting( $themename.'[post_meta_author]', array(
		'default' => 1,
		'type' => 'option'
	) );
	$wp_customize->add_control( $themename.'[post_meta_author]', array(
		'label' => 'Author',
		'type' => 'checkbox',
		'section' => 'blog_layout'
	) );

	/** Post Meta Data: Categories **/
	$wp_customize->add_setting( $themename.'[post_meta_categories]', array(
		'default' => 1,
		'type' => 'option'
	) );
	$wp_customize->add_control( $themename.'[post_meta_categories]', array(
		'label' => 'Categories',
		'type' => 'checkbox',
		'section' => 'blog_layout'
	) );

	/** Post Meta Data: Tags **/
	$wp_customize->add_setting( $themename.'[post_meta_tags]', array(
		'default' => 1,
		'type' => 'option'
	) );
	$wp_customize->add_control( $themename.'[post_meta_tags]', array(
		'label' => 'Tags',
		'type' => 'checkbox',
		'section' => 'blog_layout'
	) );

	/* Sidebar */
	$wp_customize->add_setting( $themename.'[default_post_sidebar]', array(
		'default' => $options['default_post_sidebar']['std'],
		'type'    => 'option'
	) );

	$wp_customize->add_control( $themename.'_default_post_sidebar', array(
		'label'    => $options['default_post_sidebar']['name'],
		'section'  => 'blog_layout',
		'settings' => $themename.'[default_post_sidebar]',
		'type'     => 'radio',
		'choices'  => array(
			'default' => __( 'Site Default', 'options_framework_theme' ),
			'left'  => __( 'Right', 'options_framework_theme' ),
			'right' => __( 'Left', 'options_framework_theme' )
		)
	) );

	/* Blog Filter */
	$wp_customize->add_setting( $themename.'[blog_filter]', array(
		'default'   => '',
		'type'    => 'option',
	) );

	$wp_customize->add_control( $themename.'_blog_filter', array(
		'label'    => 'Filter Posts on Index Page',
		'description' => 'Use category and tag slugs, separated by a comma.',
		'section'  => 'blog_layout',
		'settings' => $themename.'[blog_filter]',
		'type'     => 'hidden',
	) );

	/** Blog Filter: Exclude Categories **/
	$wp_customize->add_setting( $themename.'[blog_filter_except_category]', array(
		'default' => '',
		'type' => 'option'
	) );
	$wp_customize->add_control( $themename.'[blog_filter_except_category]', array(
		'label' => 'All, except these categories:',
		'type' => 'text',
		'section' => 'blog_layout'
	) );

	/** Blog Filter: Exclude Tags **/
	$wp_customize->add_setting( $themename.'[blog_filter_except_tag]', array(
		'default' => '',
		'type' => 'option'
	) );
	$wp_customize->add_control( $themename.'[blog_filter_except_tag]', array(
		'label' => 'All, except these tags:',
		'type' => 'text',
		'section' => 'blog_layout'
	) );

	/** Blog Filter: Only Categories **/
	$wp_customize->add_setting( $themename.'[blog_filter_only_category]', array(
		'default' => '',
		'type' => 'option'
	) );
	$wp_customize->add_control( $themename.'[blog_filter_only_category]', array(
		'label' => 'None, except these categories:',
		'type' => 'text',
		'section' => 'blog_layout'
	) );

	/** Blog Filter: Only Tags **/
	$wp_customize->add_setting( $themename.'[blog_filter_only_tag]', array(
		'default' => '',
		'type' => 'option'
	) );
	$wp_customize->add_control( $themename.'[blog_filter_only_tag]', array(
		'label' => 'None, except these tags:',
		'type' => 'text',
		'section' => 'blog_layout'
	) );

	/* Page Settings Section */
	$wp_customize->add_section( 'page_settings', array(
		'title'    => 'Page Settings',
		'priority' => 130
	) );

	/* Page Title Option */
	$wp_customize->add_setting( $themename.'[page_title_option]', array(
		'default'   => $options['page_title_option']['std'],
		'type'      => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control( $themename.'_page_title_option', array(
		'label'    => $options['page_title_option']['name'],
		'section'  => 'page_settings',
		'settings' => $themename.'[page_title_option]',
		'type'     => 'select',
		'choices'  => $options['page_title_option']['options']
	) );

	/* Page Title Segment */
	$wp_customize->add_setting( $themename.'[page_title_segment]', array(
		'default'   => $options['page_title_segment']['std'],
		'type'      => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control( $themename.'_page_title_segment', array(
		'label'    => $options['page_title_segment']['name'],
		'section'  => 'page_settings',
		'settings' => $themename.'[page_title_segment]',
		'type'     => 'select',
		'choices'  => $options['page_title_segment']['options']
	) );

	/* Page Title Spacing */
	$wp_customize->add_setting( $themename.'[page_title_spacing]', array(
		'default'   => $options['page_title_spacing']['std'],
		'type'      => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control( $themename.'_page_title_spacing', array(
		'label'    => $options['page_title_spacing']['name'],
		'section'  => 'page_settings',
		'settings' => $themename.'[page_title_spacing]',
		'type'     => 'select',
		'choices'  => $options['page_title_spacing']['options']
	) );

	/* Breadcrumb Section */
	$wp_customize->add_section( 'breadcrumb', array(
		'title'    => 'Breadcrumb',
		'priority' => 130
	) );

	/* Breadcrumb Navigation */
	$wp_customize->add_setting( $themename.'[breadcrumb_nav]', array(
		'default' => $options['breadcrumb_nav']['std'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control( $themename.'_breadcrumb_nav', array(
		'label'    => $options['breadcrumb_nav']['name'],
		'section'  => 'breadcrumb',
		'settings' => $themename.'[breadcrumb_nav]',
		'type'     => $options['breadcrumb_nav']['type'],
		'choices'  => $options['breadcrumb_nav']['options']
	) );

	/* Breadcrumb Navigation Segment */
	$wp_customize->add_setting( $themename.'[breadcrumb_nav_segment]', array(
		'type'    => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control( $themename.'_breadcrumb_nav_segment', array(
		'label'    => $options['breadcrumb_nav_segment']['name'],
		'section'  => 'breadcrumb',
		'settings' => $themename.'[breadcrumb_nav_segment]',
		'type'     => 'select',
		'choices'  => $options['breadcrumb_nav_segment']['options']
	) );


	/* Background Section */
	$wp_customize->add_section( 'background', array(
		'title'    => 'Background',
		'priority' => 130
	) );

	/* Background Color */
	$wp_customize->add_setting( $themename.'[site_background][color]', array(
		'default' => $options['site_background']['std']['color'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );

	$wp_customize->add_control(
		new WP_Customize_Color_Control( $wp_customize, $themename.'_site_background_color', array(
		'label'    => 'Background Color',
		'section'  => 'background',
		'settings' => $themename.'[site_background][color]',
	) ) );

	/* Background Inner Page */
	$wp_customize->add_setting( $themename.'[background_inner_page]', array(
		'default'   => $options['background_inner_page']['std'],
		'type'      => 'option',
		'transport' => 'postMessage'
	) );

	$wp_customize->add_control( $themename.'_background_inner_page', array(
		'label' => $options['background_inner_page']['name'],
		'section' => 'background',
		'settings' => $themename. '[background_inner_page]',
		'type'  => $options['background_inner_page']['type']
	) );

	/* Background Image Upload */
	$wp_customize->add_setting( $themename.'[site_background][image]', array(
		'default' => $options['site_background']['std']['image'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );

	$wp_customize->add_control(
		new WP_Customize_Image_Control( $wp_customize, $themename.'_site_background_image', array(
			'label'    => 'Background Image',
			'section'  => 'background',
			'settings' => $themename.'[site_background][image]'
		)
	) );

	/* Background Image Position */
	$wp_customize->add_setting( $themename.'[site_background][position]', array(
		'default' => $options['site_background']['std']['position'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );

	$wp_customize->add_control( $themename.'_site_background_position', array(
		'label'    => 'Background Image Position',
		'section'  => 'background',
		'settings' => $themename.'[site_background][position]',
		'type'     => 'select',
		'choices'  => array(
			'top left'      => __( 'Top Left', 'options_framework_theme' ),
			'top center'    => __( 'Top Center', 'options_framework_theme' ),
			'top right'     => __( 'Top Right', 'options_framework_theme' ),
			'center left'   => __( 'Middle Left', 'options_framework_theme' ),
			'center center' => __( 'Middle Center', 'options_framework_theme' ),
			'center right'  => __( 'Middle Right', 'options_framework_theme' ),
			'bottom left'   => __( 'Bottom Left', 'options_framework_theme' ),
			'bottom center' => __( 'Bottom Center', 'options_framework_theme' ),
			'bottom right'  => __( 'Bottom Right', 'options_framework_theme')
			)
	) );

	/* Background Image Repeat */
	$wp_customize->add_setting( $themename.'[site_background][repeat]', array(
		'default'  => $options['site_background']['std']['repeat'],
		'type'     => 'option',
		'transport' => 'postMessage'
	) );

	$wp_customize->add_control( $themename.'_site_background_repeat', array(
		'label'    => 'Background Image Repeat',
		'section'  => 'background',
		'settings' => $themename.'[site_background][repeat]',
		'type'     => 'select',
		'choices'  => array(
			'no-repeat' => __( 'No Repeat', 'options_framework_theme' ),
			'repeat-x'  => __( 'Repeat Horizontally', 'options_framework_theme' ),
			'repeat-y'  => __( 'Repeat Vertically', 'options_framework_theme' ),
			'repeat'    => __( 'Repeat All', 'options_framework_theme' ),
			)
	) );

	/* Background Image Attachment */
	$wp_customize->add_setting( $themename.'[site_background][attachment]', array(
		'default'  => $options['site_background']['std']['attachment'],
		'type'     => 'option',
		'transport' => 'postMessage'
	) );

	$wp_customize->add_control( $themename.'_site_background_attachment', array(
		'label'    => 'Background Image Attachment',
		'section'  => 'background',
		'settings' => $themename.'[site_background][attachment]',
		'type'     => 'select',
		'choices'  => array(
			'scroll' => __( 'Scroll Normally', 'options_framework_theme' ),
			'fixed'  => __( 'Fixed in Place', 'options_framework_theme')
			)
	) );

	/* Background Image Size */
	$wp_customize->add_setting( $themename.'[site_background][size]', array(
		'default'  => $options['site_background']['std']['size'],
		'type'     => 'option',
		'transport' => 'postMessage'
	) );

	$wp_customize->add_control( $themename.'_site_background_size', array(
		'label'    => 'Background Image Size',
		'section'  => 'background',
		'settings' => $themename.'[site_background][size]',
		'type'     => 'select',
		'choices'  => array(
			'auto' => __( 'Auto', 'options_framework_theme' ),
			'cover' => __( 'Cover', 'options_framework_theme' ),
			'contain'  => __( 'Contain', 'options_framework_theme')
			)
	) );

	/*-- Color Scheme Section --*/
	$wp_customize->add_section( 'base_color_option', array(
		'title'    => 'Color Scheme',
		'priority' => 135
	) );

	/* Base Color Scheme can be either Light or Dark scheme */
	$wp_customize->add_setting( $themename.'[base_color_option]', array(
		'default' => $options['base_color_option']['std'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control( $themename.'_base_color_option', array(
		'label'    => 'Base Color Scheme',
		'section'  => 'base_color_option',
		'settings' => $themename.'[base_color_option]',
		'type'     => 'select',
		'choices'  => $options['base_color_option']['options']
	) );

	/* Heading Font Color */
	$wp_customize->add_setting( $themename.'[default_heading_color]', array(
		'default' => $options['default_heading_color']['std'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control(
		new WP_Customize_Color_Control( $wp_customize, $themename.'_default_heading_color', array(
		'label'    => 'Default Heading Color',
		'section'  => 'base_color_option',
		'settings' => $themename.'[default_heading_color]',
	) ) );

	/* Body Font Color */
	$wp_customize->add_setting( $themename.'[default_body_text_color]', array(
		'default' => $options['default_body_text_color']['std'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );

	$wp_customize->add_control(
		new WP_Customize_Color_Control( $wp_customize, $themename.'_default_body_text_color', array(
		'label'    => 'Default Body Text Color',
		'section'  => 'base_color_option',
		'settings' => $themename.'[default_body_text_color]',
	) ) );

	/* Link Font Color */
	$wp_customize->add_setting( $themename.'[default_link_color]', array(
		'default' => $options['default_link_color']['std'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );

	$wp_customize->add_control(
		new WP_Customize_Color_Control( $wp_customize, $themename.'_default_link_color', array(
		'label'    => 'Default Link Color',
		'section'  => 'base_color_option',
		'settings' => $themename.'[default_link_color]',
	) ) );

	/* Accent Color */
	$wp_customize->add_setting( $themename.'[accent_color]', array(
		'default' => $options['accent_color']['std'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );

	$wp_customize->add_control(
		new WP_Customize_Color_Control( $wp_customize, $themename.'_accent_color', array(
		'label'    => 'Accent Color',
		'section'  => 'base_color_option',
		'settings' => $themename.'[accent_color]',
	) ) );

	/*-- Fonts Section --*/
	$wp_customize->add_section( 'fonts', array(
		'title'    => 'Fonts',
		'priority' => 140
	) );

	/* Heading Fonts */
	$wp_customize->add_setting( $themename.'[headers_font_options]', array(
		//'default' => $options['headers_font_options']['std'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control( $themename.'_headers_font_options', array(
		'label'    => $options['headers_font_options']['name'],
		'section'  => 'fonts',
		'settings' => $themename.'[headers_font_options]',
		'type'     => 'select',
		'choices'  => $options['headers_font_options']['options']
	) );

	/* Body Fonts */
	$wp_customize->add_setting( $themename.'[body_font_options]', array(
		//'default' => $options['body_font_options']['std'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control( $themename.'_body_font_options', array(
		'label'    => $options['body_font_options']['name'],
		'section'  => 'fonts',
		'settings' => $themename.'[body_font_options]',
		'type'     => 'select',
		'choices'  => $options['body_font_options']['options']
	) );

	/*-- Segment1 Section --*/
	$wp_customize->add_section( 'segment_1', array(
		'title'    => 'Segment 1',
		'priority' => 145
	) );
	/* Body Text Color for Segment 1 */
	$wp_customize->add_setting( $themename.'[body_text_color_segment_1]', array(
		'default' => $options['body_text_color_segment_1']['std'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control(
		new WP_Customize_Color_Control( $wp_customize, $themename.'_body_text_color_segment_1', array(
		'label'    => 'Body Text Color',
		'section'  => 'segment_1',
		'settings' => $themename.'[body_text_color_segment_1]',
	) ) );
	/* Heading Color for Segment 1 */
	$wp_customize->add_setting( $themename.'[heading_font_color_segment_1]', array(
		'default' => $options['heading_font_color_segment_1']['std'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );

	$wp_customize->add_control(
		new WP_Customize_Color_Control( $wp_customize, $themename.'_heading_font_color_segment_1', array(
		'label'    => 'Heading Font Color',
		'section'  => 'segment_1',
		'settings' => $themename.'[heading_font_color_segment_1]',
	) ) );

	/* Background Color for Segment 1*/
	$wp_customize->add_setting( $themename.'[background_segment_1][color]', array(
		//'default' => $options['background_segment_1']['color'],
		'type'    => 'option',
		'transport' => 'postMessage'
	));

	$wp_customize->add_control(
		new WP_Customize_Color_Control( $wp_customize, $themename.'_background_segment_1_color', array(
			'label'    => 'Background Color',
			'section'  => 'segment_1',
			'settings' => $themename.'[background_segment_1][color]'
		)
	));

	/* Background Image for Segment 1*/
	$wp_customize->add_setting( $themename.'[background_segment_1][image]', array(
		'default' => $options['background_segment_1']['std']['image'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );

	$wp_customize->add_control(
		new WP_Customize_Image_Control( $wp_customize, $themename.'_background_segment_1_image', array(
			'label'    => 'Background Image',
			'section'  => 'segment_1',
			'settings' => $themename.'[background_segment_1][image]'
	) ) );

	/* Background Image Position for Segment 1*/
	$wp_customize->add_setting( $themename.'[background_segment_1][position]', array(
		'default' => $options['background_segment_1']['std']['position'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );

	$wp_customize->add_control( $themename.'_background_segment_1_position', array(
		'label'    => 'Background Image Position',
		'section'  => 'segment_1',
		'settings' => $themename.'[background_segment_1][position]',
		'type'     => 'select',
		'choices'  => array(
			'top left'      => __( 'Top Left', 'options_framework_theme' ),
			'top center'    => __( 'Top Center', 'options_framework_theme' ),
			'top right'     => __( 'Top Right', 'options_framework_theme' ),
			'center left'   => __( 'Middle Left', 'options_framework_theme' ),
			'center center' => __( 'Middle Center', 'options_framework_theme' ),
			'center right'  => __( 'Middle Right', 'options_framework_theme' ),
			'bottom left'   => __( 'Bottom Left', 'options_framework_theme' ),
			'bottom center' => __( 'Bottom Center', 'options_framework_theme' ),
			'bottom right'  => __( 'Bottom Right', 'options_framework_theme')
			)
	) );
	/* Background Repeat for Segment 1*/
	$wp_customize->add_setting( $themename.'[background_segment_1][repeat]', array(
		'default' => $options['background_segment_1']['std']['repeat'],
		'type'    => 'option',
		'transport' => 'postMessage'
	));
	$wp_customize->add_control( $themename.'_background_segment_1_repeat', array (
		'label'    => 'Background Repeat',
		'section'  => 'segment_1',
		'settings' => $themename.'[background_segment_1][repeat]',
		'type'     => 'select',
		'choices'  => array(
			'no-repeat' => 'No Repeat',
			'repeat-x'  => 'Repeat Horizontally',
			'repeat-y'  => 'Repeat Vertically',
			'repeat'    => 'Repeat All'
			)
	));
	/* Background Image Attachment for Segment 1 */
	$wp_customize->add_setting( $themename.'[background_segment_1][attachment]', array(
		'default'  => $options['background_segment_1']['std']['attachment'],
		'type'     => 'option',
		'transport' => 'postMessage'
	) );

	$wp_customize->add_control( $themename.'_background_segment_1_attachment', array(
		'label'    => 'Background Image Attachment',
		'section'  => 'segment_1',
		'settings' => $themename.'[background_segment_1][attachment]',
		'type'     => 'select',
		'choices'  => array(
			'scroll' => __( 'Scroll Normally', 'options_framework_theme' ),
			'fixed'  => __( 'Fixed in Place', 'options_framework_theme')
			)
	) );

	/* Background Image size for Segment 1 */
	$wp_customize->add_setting( $themename.'[background_segment_1][size]', array(
		'default'  => $options['background_segment_1']['std']['size'],
		'type'     => 'option',
		'transport' => 'postMessage'
	) );

	$wp_customize->add_control( $themename.'_background_segment_1_size', array(
		'label'    => 'Background Image Size',
		'section'  => 'segment_1',
		'settings' => $themename.'[background_segment_1][size]',
		'type'     => 'select',
		'choices'  => array(
			'auto' => __( 'Auto', 'options_framework_theme' ),
			'cover' => __( 'Cover', 'options_framework_theme' ),
			'contain'  => __( 'Contain', 'options_framework_theme')
			)
	) );

	/*-- Segment 2 Section --*/
	$wp_customize->add_section( 'segment_2', array(
		'title'    => 'Segment 2',
		'priority' => 150
	) );
	/* Body Text Color for Segment 2 */
	$wp_customize->add_setting( $themename.'[body_text_color_segment_2]', array(
		'default' => $options['body_text_color_segment_2']['std'],
		'type'    => 'option',
		'transport' => 'postMessage'

	) );
	$wp_customize->add_control(
		new WP_Customize_Color_Control( $wp_customize, $themename.'_body_text_color_segment_2', array(
		'label'    => 'Body Text Color',
		'section'  => 'segment_2',
		'settings' => $themename.'[body_text_color_segment_2]',
	) ) );
	/* Heading Color for Segment 2 */
	$wp_customize->add_setting( $themename.'[heading_font_color_segment_2]', array(
		'default' => $options['heading_font_color_segment_2']['std'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control(
		new WP_Customize_Color_Control( $wp_customize, $themename.'_heading_font_color_segment_2', array(
		'label'    => 'Heading Font Color',
		'section'  => 'segment_2',
		'settings' => $themename.'[heading_font_color_segment_2]',
	) ) );
	/* Background Color for Segment 2 */
	$wp_customize->add_setting( $themename.'[background_segment_2][color]', array(
		//'default' => $options['background_segment_2']['color'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control(
		new WP_Customize_Color_Control( $wp_customize, $themename.'_background_segment_2_color', array(
		'label'    => 'Background Color',
		'section'  => 'segment_2',
		'settings' => $themename.'[background_segment_2][color]',
	) ) );
	/* Background Image for Segment 2*/
	$wp_customize->add_setting( $themename.'[background_segment_2][image]', array(
		'default' => $options['background_segment_2']['std']['image'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control(
		new WP_Customize_Image_Control( $wp_customize, $themename.'_background_segment_2_image', array(
			'label'    => 'Background Image',
			'section'  => 'segment_2',
			'settings' => $themename.'[background_segment_2][image]'
	) ) );
	/* Background Image Position for Segment 2*/
	$wp_customize->add_setting( $themename.'[background_segment_2][position]', array(
		'default' => $options['background_segment_2']['std']['position'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control( $themename.'_background_segment_2_position', array(
		'label'    => 'Background Image Position',
		'section'  => 'segment_2',
		'settings' => $themename.'[background_segment_2][position]',
		'type'     => 'select',
		'choices'  => array(
			'top left'      => __( 'Top Left', 'options_framework_theme' ),
			'top center'    => __( 'Top Center', 'options_framework_theme' ),
			'top right'     => __( 'Top Right', 'options_framework_theme' ),
			'center left'   => __( 'Middle Left', 'options_framework_theme' ),
			'center center' => __( 'Middle Center', 'options_framework_theme' ),
			'center right'  => __( 'Middle Right', 'options_framework_theme' ),
			'bottom left'   => __( 'Bottom Left', 'options_framework_theme' ),
			'bottom center' => __( 'Bottom Center', 'options_framework_theme' ),
			'bottom right'  => __( 'Bottom Right', 'options_framework_theme')
			)
	) );
	/* Background Image Repeat for Segment 2 */
	$wp_customize->add_setting( $themename.'[background_segment_2][repeat]', array(
		'default'  => $options['background_segment_2']['std']['repeat'],
		'type'     => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control( $themename.'_background_segment_2_repeat', array(
		'label'    => 'Background Image Repeat',
		'section'  => 'segment_2',
		'settings' => $themename.'[background_segment_2][repeat]',
		'type'     => 'select',
		'choices'  => array(
			'no-repeat' => __( 'No Repeat', 'options_framework_theme' ),
			'repeat-x'  => __( 'Repeat Horizontally', 'options_framework_theme' ),
			'repeat-y'  => __( 'Repeat Vertically', 'options_framework_theme' ),
			'repeat'    => __( 'Repeat All', 'options_framework_theme' ),
			)
	) );
	/* Background Image Attachment for Segment 2 */
	$wp_customize->add_setting( $themename.'[background_segment_2][attachment]', array(
		'default'  => $options['background_segment_2']['std']['attachment'],
		'type'     => 'option',
		'transport' => 'postMessage'
	) );

	$wp_customize->add_control( $themename.'_background_segment_2_attachment', array(
		'label'    => 'Background Image Attachment',
		'section'  => 'segment_2',
		'settings' => $themename.'[background_segment_2][attachment]',
		'type'     => 'select',
		'choices'  => array(
			'scroll' => __( 'Scroll Normally', 'options_framework_theme' ),
			'fixed'  => __( 'Fixed in Place', 'options_framework_theme')
			)
	) );

	/* Background Image size for Segment 2 */
	$wp_customize->add_setting( $themename.'[background_segment_2][size]', array(
		'default'  => $options['background_segment_2']['std']['size'],
		'type'     => 'option',
		'transport' => 'postMessage'
	) );

	$wp_customize->add_control( $themename.'_background_segment_2_size', array(
		'label'    => 'Background Image Size',
		'section'  => 'segment_2',
		'settings' => $themename.'[background_segment_2][size]',
		'type'     => 'select',
		'choices'  => array(
			'auto' => __( 'Auto', 'options_framework_theme' ),
			'cover' => __( 'Cover', 'options_framework_theme' ),
			'contain'  => __( 'Contain', 'options_framework_theme')
			)
	) );

	/* Segment 3 */
	$wp_customize->add_section( 'segment_3', array(
		'title'    => 'Segment 3',
		'priority' => 155
	) );
	/* Body text Color for Segment 3*/
	$wp_customize->add_setting( $themename.'[body_text_color_segment_3]', array(
		'default'  => $options['body_text_color_segment_3']['std'],
		'type'     => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control(
		new WP_Customize_Color_Control( $wp_customize, $themename.'_body_text_color_segment_3', array(
		'label'    => 'Body Text Color',
		'section'  => 'segment_3',
		'settings' => $themename.'[body_text_color_segment_3]',
	) ) );
	/* Heading Color for Segment 3*/
	$wp_customize->add_setting( $themename.'[heading_font_color_segment_3]', array(
		'default'  => $options['heading_font_color_segment_3']['std'],
		'type'     => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control(
		new WP_Customize_Color_Control( $wp_customize, $themename.'_heading_font_color_segment_3', array(
		'label'    => 'Heading Font Color',
		'section'  => 'segment_3',
		'settings' => $themename.'[heading_font_color_segment_3]',
	) ) );
	/* Background Color for segment_3 */
	$wp_customize->add_setting( $themename.'[background_segment_3][color]', array(
		//'default' => $options['background_segment_3']['color'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control(
		new WP_Customize_Color_Control( $wp_customize, $themename.'_background_segment_3_color', array(
		'label'    => 'Background Color',
		'section'  => 'segment_3',
		'settings' => $themename.'[background_segment_3][color]',
	) ) );
	/* Background Image for Segment 3*/
	$wp_customize->add_setting( $themename.'[background_segment_3][image]', array(
		'default' => $options['background_segment_3']['std']['image'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control(
		new WP_Customize_Image_Control( $wp_customize, $themename.'_background_segment_3_image', array(
			'label'    => 'Background Image',
			'section'  => 'segment_3',
			'settings' => $themename.'[background_segment_3][image]'
	) ) );
	/* Background Image Position for Segment 3*/
	$wp_customize->add_setting( $themename.'[background_segment_3][position]', array(
		'default' => $options['background_segment_3']['std']['position'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control( $themename.'_background_segment_3_position', array(
		'label'    => 'Background Image Position',
		'section'  => 'segment_3',
		'settings' => $themename.'[background_segment_3][position]',
		'type'     => 'select',
		'choices'  => array(
			'top left'      => __( 'Top Left', 'options_framework_theme' ),
			'top center'    => __( 'Top Center', 'options_framework_theme' ),
			'top right'     => __( 'Top Right', 'options_framework_theme' ),
			'center left'   => __( 'Middle Left', 'options_framework_theme' ),
			'center center' => __( 'Middle Center', 'options_framework_theme' ),
			'center right'  => __( 'Middle Right', 'options_framework_theme' ),
			'bottom left'   => __( 'Bottom Left', 'options_framework_theme' ),
			'bottom center' => __( 'Bottom Center', 'options_framework_theme' ),
			'bottom right'  => __( 'Bottom Right', 'options_framework_theme')
			)
	) );
	/* Background Image Repeat for Segment 3 */
	$wp_customize->add_setting( $themename.'[background_segment_3][repeat]', array(
		'default'  => $options['background_segment_3']['std']['repeat'],
		'type'     => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control( $themename.'_background_segment_3_repeat', array(
		'label'    => 'Background Image Repeat',
		'section'  => 'segment_3',
		'settings' => $themename.'[background_segment_3][repeat]',
		'type'     => 'select',
		'choices'  => array(
			'no-repeat' => __( 'No Repeat', 'options_framework_theme' ),
			'repeat-x'  => __( 'Repeat Horizontally', 'options_framework_theme' ),
			'repeat-y'  => __( 'Repeat Vertically', 'options_framework_theme' ),
			'repeat'    => __( 'Repeat All', 'options_framework_theme' ),
			)
	) );
	/* Background Image Attachment for Segment 3 */
	$wp_customize->add_setting( $themename.'[background_segment_3][attachment]', array(
		'default'  => $options['background_segment_3']['std']['attachment'],
		'type'     => 'option',
		'transport' => 'postMessage'
	) );

	$wp_customize->add_control( $themename.'_background_segment_3_attachment', array(
		'label'    => 'Background Image Attachment',
		'section'  => 'segment_3',
		'settings' => $themename.'[background_segment_3][attachment]',
		'type'     => 'select',
		'choices'  => array(
			'scroll' => __( 'Scroll Normally', 'options_framework_theme' ),
			'fixed'  => __( 'Fixed in Place', 'options_framework_theme')
			)
	) );

	/* Background Image size for Segment 3 */
	$wp_customize->add_setting( $themename.'[background_segment_3][size]', array(
		'default'  => $options['background_segment_3']['std']['size'],
		'type'     => 'option',
		'transport' => 'postMessage'
	) );

	$wp_customize->add_control( $themename.'_background_segment_3_size', array(
		'label'    => 'Background Image Size',
		'section'  => 'segment_3',
		'settings' => $themename.'[background_segment_3][size]',
		'type'     => 'select',
		'choices'  => array(
			'auto' => __( 'Auto', 'options_framework_theme' ),
			'cover' => __( 'Cover', 'options_framework_theme' ),
			'contain'  => __( 'Contain', 'options_framework_theme')
			)
	) );

	/*-- Segment 4 Section --*/
	$wp_customize->add_section( 'segment_4', array(
		'title'    => 'Segment 4',
		'priority' => 160
	) );
	/* Body Text Color for Segment 4 */
	$wp_customize->add_setting( $themename.'[body_text_color_segment_4]', array(
		'default' => $options['body_text_color_segment_4']['std'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control(
		new WP_Customize_Color_Control( $wp_customize, $themename.'_body_text_color_segment_4', array(
		'label'    => 'Body Text Color',
		'section'  => 'segment_4',
		'settings' => $themename.'[body_text_color_segment_4]',
	) ) );
	/* Heading Color for Segment 4 */
	$wp_customize->add_setting( $themename.'[heading_font_color_segment_4]', array(
		'default' => $options['heading_font_color_segment_4']['std'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control(
		new WP_Customize_Color_Control( $wp_customize, $themename.'_heading_font_color_segment_4', array(
		'label'    => 'Heading Font Color',
		'section'  => 'segment_4',
		'settings' => $themename.'[heading_font_color_segment_4]',
	) ) );
	/* Background Color for segment_4 */
	$wp_customize->add_setting( $themename.'[background_segment_4][color]', array(
		//'default' => $options['background_segment_4']['color'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control(
		new WP_Customize_Color_Control( $wp_customize, $themename.'_background_segment_4_color', array(
		'label'    => 'Background Color',
		'section'  => 'segment_4',
		'settings' => $themename.'[background_segment_4][color]',
	) ) );
	/* Background Image for Segment 4*/
	$wp_customize->add_setting( $themename.'[background_segment_4][image]', array(
		'default' => $options['background_segment_4']['std']['image'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control(
		new WP_Customize_Image_Control( $wp_customize, $themename.'_background_segment_4_image', array(
			'label'    => 'Background Image',
			'section'  => 'segment_4',
			'settings' => $themename.'[background_segment_4][image]'
	) ) );
	/* Background Image Position for Segment 4*/
	$wp_customize->add_setting( $themename.'[background_segment_4][position]', array(
		'default' => $options['background_segment_4']['std']['position'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control( $themename.'_background_segment_4_position', array(
		'label'    => 'Background Image Position',
		'section'  => 'segment_4',
		'settings' => $themename.'[background_segment_4][position]',
		'type'     => 'select',
		'choices'  => array(
			'top left'      => __( 'Top Left', 'options_framework_theme' ),
			'top center'    => __( 'Top Center', 'options_framework_theme' ),
			'top right'     => __( 'Top Right', 'options_framework_theme' ),
			'center left'   => __( 'Middle Left', 'options_framework_theme' ),
			'center center' => __( 'Middle Center', 'options_framework_theme' ),
			'center right'  => __( 'Middle Right', 'options_framework_theme' ),
			'bottom left'   => __( 'Bottom Left', 'options_framework_theme' ),
			'bottom center' => __( 'Bottom Center', 'options_framework_theme' ),
			'bottom right'  => __( 'Bottom Right', 'options_framework_theme')
			)
	) );
	/* Background Image Repeat for Segment 4 */
	$wp_customize->add_setting( $themename.'[background_segment_4][repeat]', array(
		'default'  => $options['background_segment_4']['std']['repeat'],
		'type'     => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control( $themename.'_background_segment_4_repeat', array(
		'label'    => 'Background Image Repeat',
		'section'  => 'segment_4',
		'settings' => $themename.'[background_segment_4][repeat]',
		'type'     => 'select',
		'choices'  => array(
			'no-repeat' => __( 'No Repeat', 'options_framework_theme' ),
			'repeat-x'  => __( 'Repeat Horizontally', 'options_framework_theme' ),
			'repeat-y'  => __( 'Repeat Vertically', 'options_framework_theme' ),
			'repeat'    => __( 'Repeat All', 'options_framework_theme' ),
			)
	) );
	/* Background Image Attachment for Segment 4 */
	$wp_customize->add_setting( $themename.'[background_segment_4][attachment]', array(
		'default'  => $options['background_segment_4']['std']['attachment'],
		'type'     => 'option',
		'transport' => 'postMessage'
	) );

	$wp_customize->add_control( $themename.'_background_segment_4_attachment', array(
		'label'    => 'Background Image Attachment',
		'section'  => 'segment_4',
		'settings' => $themename.'[background_segment_4][attachment]',
		'type'     => 'select',
		'choices'  => array(
			'scroll' => __( 'Scroll Normally', 'options_framework_theme' ),
			'fixed'  => __( 'Fixed in Place', 'options_framework_theme')
			)
	) );

	/* Background Image size for Segment 4 */
	$wp_customize->add_setting( $themename.'[background_segment_4][size]', array(
		'default'  => $options['background_segment_4']['std']['size'],
		'type'     => 'option',
		'transport' => 'postMessage'
	) );

	$wp_customize->add_control( $themename.'_background_segment_4_size', array(
		'label'    => 'Background Image Size',
		'section'  => 'segment_4',
		'settings' => $themename.'[background_segment_4][size]',
		'type'     => 'select',
		'choices'  => array(
			'auto' => __( 'Auto', 'options_framework_theme' ),
			'cover' => __( 'Cover', 'options_framework_theme' ),
			'contain'  => __( 'Contain', 'options_framework_theme')
			)
	) );

	/*-- Segment 5 Section --*/
	$wp_customize->add_section( 'segment_5', array(
		'title'    => 'Segment 5',
		'priority' => 165
	) );
	/* Body Text Color for Segment 5 */
	$wp_customize->add_setting( $themename.'[body_text_color_segment_5]', array(
		'default' => $options['body_text_color_segment_5']['std'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control(
		new WP_Customize_Color_Control( $wp_customize, $themename.'_body_text_color_segment_5', array(
		'label'    => 'Body Text Color',
		'section'  => 'segment_5',
		'settings' => $themename.'[body_text_color_segment_5]',
	) ) );
	/* Heading Color for Segment 5 */
	$wp_customize->add_setting( $themename.'[heading_font_color_segment_5]', array(
		'default' => $options['heading_font_color_segment_5']['std'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control(
		new WP_Customize_Color_Control( $wp_customize, $themename.'_heading_font_color_segment_5', array(
		'label'    => 'Heading Font Color',
		'section'  => 'segment_5',
		'settings' => $themename.'[heading_font_color_segment_5]',
	) ) );
	/* Background Color for segment_5 */
	$wp_customize->add_setting( $themename.'[background_segment_5][color]', array(
		//'default' => $options['background_segment_5']['color'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control(
		new WP_Customize_Color_Control( $wp_customize, $themename.'_background_segment_5_color', array(
		'label'    => 'Background Color',
		'section'  => 'segment_5',
		'settings' => $themename.'[background_segment_5][color]',
	) ) );
	/* Background Image for Segment 5*/
	$wp_customize->add_setting( $themename.'[background_segment_5][image]', array(
		'default' => $options['background_segment_5']['std']['image'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control(
		new WP_Customize_Image_Control( $wp_customize, $themename.'_background_segment_5_image', array(
			'label'    => 'Background Image',
			'section'  => 'segment_5',
			'settings' => $themename.'[background_segment_5][image]'
	) ) );
	/* Background Image Position for Segment 5*/
	$wp_customize->add_setting( $themename.'[background_segment_5][position]', array(
		'default' => $options['background_segment_5']['std']['position'],
		'type'    => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control( $themename.'_background_segment_5_position', array(
		'label'    => 'Background Image Position',
		'section'  => 'segment_5',
		'settings' => $themename.'[background_segment_5][position]',
		'type'     => 'select',
		'choices'  => array(
			'top left'      => __( 'Top Left', 'options_framework_theme' ),
			'top center'    => __( 'Top Center', 'options_framework_theme' ),
			'top right'     => __( 'Top Right', 'options_framework_theme' ),
			'center left'   => __( 'Middle Left', 'options_framework_theme' ),
			'center center' => __( 'Middle Center', 'options_framework_theme' ),
			'center right'  => __( 'Middle Right', 'options_framework_theme' ),
			'bottom left'   => __( 'Bottom Left', 'options_framework_theme' ),
			'bottom center' => __( 'Bottom Center', 'options_framework_theme' ),
			'bottom right'  => __( 'Bottom Right', 'options_framework_theme')
			)
	) );
	/* Background Image Repeat for Segment 5 */
	$wp_customize->add_setting( $themename.'[background_segment_5][repeat]', array(
		'default'  => $options['background_segment_5']['std']['repeat'],
		'type'     => 'option',
		'transport' => 'postMessage'
	) );
	$wp_customize->add_control( $themename.'_background_segment_5_repeat', array(
		'label'    => 'Background Image Repeat',
		'section'  => 'segment_5',
		'settings' => $themename.'[background_segment_5][repeat]',
		'type'     => 'select',
		'choices'  => array(
			'no-repeat' => __( 'No Repeat', 'options_framework_theme' ),
			'repeat-x'  => __( 'Repeat Horizontally', 'options_framework_theme' ),
			'repeat-y'  => __( 'Repeat Vertically', 'options_framework_theme' ),
			'repeat'    => __( 'Repeat All', 'options_framework_theme' ),
			)
	) );
	/* Background Image Attachment for Segment 5 */
	$wp_customize->add_setting( $themename.'[background_segment_5][attachment]', array(
		'default'  => $options['background_segment_5']['std']['attachment'],
		'type'     => 'option',
		'transport' => 'postMessage'
	) );

	$wp_customize->add_control( $themename.'_background_segment_5_attachment', array(
		'label'    => 'Background Image Attachment',
		'section'  => 'segment_5',
		'settings' => $themename.'[background_segment_5][attachment]',
		'type'     => 'select',
		'choices'  => array(
			'scroll' => __( 'Scroll Normally', 'options_framework_theme' ),
			'fixed'  => __( 'Fixed in Place', 'options_framework_theme')
			)
	) );

	/* Background Image size for Segment 5 */
	$wp_customize->add_setting( $themename.'[background_segment_5][size]', array(
		'default'  => $options['background_segment_5']['std']['size'],
		'type'     => 'option',
		'transport' => 'postMessage'
	) );

	$wp_customize->add_control( $themename.'_background_segment_5_size', array(
		'label'    => 'Background Image Size',
		'section'  => 'segment_5',
		'settings' => $themename.'[background_segment_5][size]',
		'type'     => 'select',
		'choices'  => array(
			'auto' => __( 'Auto', 'options_framework_theme' ),
			'cover' => __( 'Cover', 'options_framework_theme' ),
			'contain'  => __( 'Contain', 'options_framework_theme')
			)
	) );

}

/*
** Custom Script for Base Color Scheme
*/
add_action('optionsframework_custom_scripts', 'optionsframework_custom_scripts');
function optionsframework_custom_scripts() { ?>

	<script type="text/javascript">
		jQuery(document).ready(function($) {

			//Dark Color Options
			var dark = new Array();
			dark['default_heading_color'] = '#3c3c3c';
			dark['default_body_text_color'] = '#3c3c3c';
			dark['default_link_color'] = '#0088cc';

			dark['background_segment_1_color'] = '#808080';
			dark['heading_font_color_segment_1'] = '#FAFAFA';
			dark['body_text_color_segment_1'] = '#FAFAFA';

			dark['background_segment_2_color'] = '#B4B4B4';
			dark['heading_font_color_segment_2'] = '#3C3C3C';
			dark['body_text_color_segment_2'] = '#3C3C3C';

			dark['background_segment_3_color'] = '#C8C8C8';
			dark['heading_font_color_segment_3'] = '#3C3C3C';
			dark['body_text_color_segment_3'] = '#3C3C3C';

			dark['background_segment_4_color'] = '#FAFAFA';
			dark['heading_font_color_segment_4'] = '#3C3C3C';
			dark['body_text_color_segment_4'] = '#808080';

			dark['background_segment_5_color'] = '#3C3C3C';
			dark['heading_font_color_segment_5'] = '#808080';
			dark['body_text_color_segment_5'] = '#C8C8C8';

			//Light Color Options
			var light = new Array();
			light['default_heading_color'] = '#808080';
			light['default_body_text_color'] = '#808080';
			light['default_link_color'] = '#0088cc';

			light['background_segment_1_color'] = '#B4B4B4';
			light['heading_font_color_segment_1'] = '#3C3C3C';
			light['body_text_color_segment_1'] = '#3C3C3C';

			light['background_segment_2_color'] = '#C8C8C8';
			light['heading_font_color_segment_2'] = '#3C3C3C';
			light['body_text_color_segment_2'] = '#3C3C3C';

			light['background_segment_3_color'] = '#FAFAFA';
			light['heading_font_color_segment_3'] = '#3C3C3C';
			light['body_text_color_segment_3'] = '#3C3C3C';

			light['background_segment_4_color'] = '#808080';
			light['heading_font_color_segment_4'] = '#FAFAFA';
			light['body_text_color_segment_4'] = '#FAFAFA';

			light['background_segment_5_color'] = '#3C3C3C';
			light['heading_font_color_segment_5'] = '#808080';
			light['body_text_color_segment_5'] = '#C8C8C8';

			$('#base_color_option').change(function() {
				colorscheme = $(this).val();
				if (colorscheme == 'dark') { colorscheme = dark; }
				if (colorscheme == 'light') { colorscheme = light; }
				for (id in colorscheme) {
					of_update_color(id, colorscheme[id]);
				}
			});
			function of_update_color(id, hex) {
				$('#' + id).wpColorPicker('color', hex);

			}
		});
	</script>
<?php
}
?>
