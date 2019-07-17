<?php

switch_theme( 'phoenix_child' );

require_once WP_CONTENT_DIR . '/themes/phoenix/lib/site_options.php';

class StieOptionsTest extends WP_UnitTestCase {
	function setUp() {
		parent::setUp();
	}

	function tearDown() {
		parent::tearDown();
	}

	function _create_post( $author, $date, $title, $content, $status, $type ) {
		$this->page_id = $this->factory->post->create( array(
			'post_author'  => $author,
			'post_date'    => $date,
			'post_content' => $content,
			'post_title'   => $title,
			'post_status'  => $status,
			'post_type'    => $type,
		) );
		return $this->page_id;
	}

	function test_get_featured_image_size_a() {
		add_option( 'phoenix_child' , array(
			'blog_style' => 'a'
		) );

		$actual   = get_featured_image_size();
		$expected = 'featured-small';

		$this->assertEquals( $expected, $actual );
	}

	function test_get_featured_image_size_b() {
		add_option( 'phoenix_child', array(
			'blog_style' => 'b'
		) );

		$actual   = get_featured_image_size();
		$expected = 'featured-large';

		$this->assertEquals( $expected, $actual );
	}

	function test_get_sidebar_setting_left() {
		add_option( 'optionsframework', array(
			'id' => 'phoenix_child'
		) );
		add_option( 'phoenix_child', array(
			'default_sidebar' => 'left'
		) );

		$actual   = get_sidebar_setting( 1 );
		$expected = 'left';

		$this->assertEquals( $expected, $actual );
	}

	function test_get_sidebar_setting_right() {
		add_option( 'phoenix_child', array(
			'default_sidebar' => 'right'
		) );

		$actual   = get_sidebar_setting( 1 );
		$expected = 'right';

		$this->assertEquals( $expected, $actual );
	}

	function test_get_svg_logo() {
		add_option( 'phoenix_child', array(
			'svg_logo' => '<svg height="100" width="100"><circle cx="50" cy="50" r="40" stroke="black" stroke-width="3" fill="red" /></svg>'
		) );

		$actual   = get_option( 'phoenix_child' )['svg_logo'];
		$expected = '<svg height="100" width="100"><circle cx="50" cy="50" r="40" stroke="black" stroke-width="3" fill="red" /></svg>';

		$this->assertEquals( $expected, $actual );
	}

	function test_get_sidebar_setting_left_per_page() {
		add_post_meta( 1, 'custom_page_sidebar', 'left' );

		$actual   = get_sidebar_setting( 1 );
		$expected = 'left';

		$this->assertEquals( $expected, $actual );
	}

	function test_get_sidebar_setting_right_per_page() {
		add_post_meta( 1, 'custom_page_sidebar', 'right' );

		$actual   = get_sidebar_setting( 1 );
		$expected = 'right';

		$this->assertEquals( $expected, $actual );
	}

	function test_get_sidebar_setting_default() {
		add_option( 'phoenix_child' , array(
			'default_sidebar' => 'right'
		) );
		add_post_meta( 1, 'custom_page_sidebar', 'default' );

		$actual   = get_sidebar_setting( 1 );
		$expected = 'right';

		$this->assertEquals( $expected, $actual );
	}

	function test_get_footer_group_from_per_page() {
		add_option( 'phoenix_child' , array(
			'footer_group' => 'group_a'
		) );
		add_post_meta( 1, 'custom_footer_group', 'per_page_group_a' );

		$actual   = get_footer_group( 1 );
		$expected = 'per_page_group_a';

		$this->assertEquals( $expected, $actual["footer_group"] );
	}

	function test_get_footer_from_per_page() {
		add_post_meta( 1, 'custom_footer_group', 'per_page_group_a' );
		add_post_meta( 1, 'custom_footer_additional_class', 'additional footer class' );

		$actual   = get_footer_group( 1 );
		$expected_group = 'per_page_group_a';
		$expected_class = 'additional footer class';

		$this->assertEquals( $expected_group, $actual["footer_group"] );
		$this->assertEquals( $expected_class, $actual["footer_additional_class"] );
	}

	function test_get_footer_group_from_site_option() {
		add_option('footer_group', 'group_a');

		$actual   = get_footer_group( 1 );
		$expected = 'group_a';

		$this->assertEquals($expected, $actual);
	}

	function test_get_header_from_per_page() {
		add_post_meta( 1, 'custom_header_group', 'per_page_group_a' );
		add_post_meta( 1, 'custom_header_additional_class', 'additional header class' );

		$actual   = get_header_group( 1 );
		$expected_group = 'per_page_group_a';
		$expected_class = 'additional header class';

		$this->assertEquals( $expected_group, $actual["header_group"] );
		$this->assertEquals( $expected_class, $actual["header_additional_class"] );
	}

	function test_get_header_group_from_site_option() {
		add_option( 'header_group', 'group_a' );

		$actual   = get_header_group( 1 );
		$expected = 'group_a';

		$this->assertEquals( $expected, $actual );
	}

	function test_get_page_title_default_location_with_default_settings() {
		$author  = 'test';
		$date    = date( 'Y-m-d H:i:s' );
		$title   = 'Test Default Location Title';
		$content = 'Test Content';
		$status  = 'publish';
		$type    = 'page';

		$post_id = $this->_create_post( $author, $date, $title, $content, $status, $type );

		$location = 'default';
		add_post_meta( $post_id, 'custom_title_location', $location );

		$actual   = get_page_title( $location, $post_id );
		$expected = '<h1>Test Default Location Title</h1>';

		$this->assertEquals( $expected, $actual );
	}

	function test_get_page_title_segment_location_with_per_page_settings() {
		$author  = 'test';
		$date    = date( 'Y-m-d H:i:s' );
		$title   = 'Test Default Location Title';
		$content = 'Test Content';
		$status  = 'publish';
		$type    = 'page';

		$post_id = $this->_create_post( $author, $date, $title, $content, $status, $type );

		add_post_meta( $post_id, 'custom_title_location',     'segment' );
		add_post_meta( $post_id, 'custom_title_custom_class', 'custom_class' );
		add_post_meta( $post_id, 'custom_title_segment',      'segment1' );
		add_post_meta( $post_id, 'custom_title_spacing',      'mini' );

		$location = 'segment';

		$expected = '<div class="segment1 custom_class space-mini"><div class="container"><h1>Test Default Location Title</h1></div></div>';
		$actual   = get_page_title( $location, $post_id );

		$this->assertEquals( $expected, $actual );
	}

	function test_get_page_title_segment_location_with_site_settings() {
		$author  = 'test';
		$date    = date( 'Y-m-d H:i:s' );
		$title   = 'Test Default Location Title';
		$content = 'Test Content';
		$status  = 'publish';
		$type    = 'page';

		$post_id = $this->_create_post( $author, $date, $title, $content, $status, $type );

		add_post_meta( $post_id, 'custom_title_location',     'site' );
		add_post_meta( $post_id, 'custom_title_custom_class', 'custom_class' );

		add_option( 'optionsframework', array(
			'id' => 'phoenix_child',
		) );
		add_option( 'phoenix_child', array(
			'page_title_segment' => 'segment3',
			'page_title_spacing' => 'huge',
			'page_title_option'  => 'segment',
		) );

		$location = 'segment';

		$expected = '<div class="segment3 space-huge"><div class="container"><h1>Test Default Location Title</h1></div></div>';
		$actual   = get_page_title( $location, $post_id );

		$this->assertEquals( $expected, $actual );
	}

	function test_get_masthead_when_disable() {
		global $post;

		$author  = 'test';
		$date    = date( 'Y-m-d H:i:s' );
		$title   = 'Test Default Location Title';
		$content = 'Test Content';
		$status  = 'publish';
		$type    = 'page';

		$post_id = $this->_create_post( $author, $date, $title, $content, $status, $type );

		$post = get_post( $post_id );

		global $wp_query;

		// To tell WP test that we are on a page ( is_page() becomes true ).
		$wp_query->is_page = true;

		add_post_meta( $post_id, 'custom_masthead_enable',  '' );
		add_post_meta( $post_id, 'custom_masthead_content', 'Masthead Content' );

		$actual = get_masthead();

		$this->assertNull( $actual );
	}

	function test_get_masthead_when_enable() {
		global $post;

		$author  = 'test';
		$date    = date( 'Y-m-d H:i:s' );
		$title   = 'Test Default Location Title';
		$content = 'Test Content';
		$status  = 'publish';
		$type    = 'page';

		$post_id = $this->_create_post( $author, $date, $title, $content, $status, $type );

		$post = get_post( $post_id );

		global $wp_query;

		// To tell WP test that we are on a page ( is_page() becomes true ).
		$wp_query->is_page = true;

		add_post_meta( $post_id, 'custom_masthead_enable',       'Enable' );
		add_post_meta( $post_id, 'custom_masthead_content',      'Masthead Content' );
		add_post_meta( $post_id, 'custom_masthead_custom_class', 'segment_custom' );
		add_post_meta( $post_id, 'custom_masthead_color',        '#8224e3' );
		add_post_meta( $post_id, 'custom_masthead_spacing',      'small' );

		$expected = '<div class=" space-small segment_custom"><div class="container"><div class="row"><div class="col-md-12">Masthead Content</div></div></div></div>';
		$actual   = get_masthead();

		$this->assertEquals( $expected, $actual );
	}

	function test_get_masthead_jumpdown_when_enable() {
		global $post;

		$author  = 'test';
		$date    = date( 'Y-m-d H:i:s' );
		$title   = 'Test Default Location Title';
		$content = 'Test Content';
		$status  = 'publish';
		$type    = 'page';

		$post_id = $this->_create_post( $author, $date, $title, $content, $status, $type );

		$post = get_post( $post_id );

		global $wp_query;
		$wp_query->is_page = true;

		add_post_meta( $post_id, 'custom_masthead_enable',             'Enable' );
		add_post_meta( $post_id, 'custom_masthead_content',            'Masthead Content' );
		add_post_meta( $post_id, 'custom_masthead_custom_class',       'segment_custom' );
		add_post_meta( $post_id, 'custom_masthead_spacing',            'small' );
		add_post_meta( $post_id, 'custom_masthead_jumpdown_enable',    'Enable' );
		add_post_meta( $post_id, 'custom_masthead_jumpdown_animation', '0' );
		add_post_meta( $post_id, 'custom_masthead_jumpdown_duration',  '800' );
		add_post_meta( $post_id, 'custom_masthead_jumpdown_target',    'jumpdown-action' );


		$expected  = '<div class=" space-small segment_custom"><div class="container"><div class="row"><div class="col-md-12">Masthead Content</div></div></div>';
		$expected .= '<div class="footer-cta animate hidden-xs">';
		$expected .= '<a href="#jumpdown-action" rel="jumpdown" class="halfCircle scroll" id="jumpdown" data-animation="0" data-duration="800">';
		$expected .= '<i class="fa fa-angle-down"></i></a></div></div>';

		$actual = get_masthead();

		$this->assertEquals( $expected, $actual );
	}

	function test_get_masthead_jumpdown_without_target() {
		global $post;

		$author  = 'test';
		$date    = date( 'Y-m-d H:i:s' );
		$title   = 'Test Default Location Title';
		$content = 'Test Content';
		$status  = 'publish';
		$type    = 'page';

		$post_id = $this->_create_post( $author, $date, $title, $content, $status, $type );

		$post = get_post( $post_id );

		global $wp_query;
		$wp_query->is_page = true;

		add_post_meta( $post_id, 'custom_masthead_enable',             'Enable' );
		add_post_meta( $post_id, 'custom_masthead_content',            'Masthead Content' );
		add_post_meta( $post_id, 'custom_masthead_custom_class',       'segment_custom' );
		add_post_meta( $post_id, 'custom_masthead_spacing',            'small' );
		add_post_meta( $post_id, 'custom_masthead_jumpdown_enable',    'Enable' );
		add_post_meta( $post_id, 'custom_masthead_jumpdown_animation', '0' );
		add_post_meta( $post_id, 'custom_masthead_jumpdown_duration',  '800' );
		add_post_meta( $post_id, 'custom_masthead_jumpdown_target',    '' );

		$expected  = '<div class=" space-small segment_custom"><div class="container"><div class="row"><div class="col-md-12">Masthead Content</div></div></div>';
		$expected .= '<div class="footer-cta animate hidden-xs">';
		$expected .= '<a href="#jumpdown-target" rel="jumpdown" class="halfCircle scroll" id="jumpdown" data-animation="0" data-duration="800">';
		$expected .= '<i class="fa fa-angle-down"></i></a></div></div>';
		$expected .= '<div id="jumpdown-target"><!--Jumpdown Target--></div>';

		$actual = get_masthead();

		$this->assertEquals( $expected, $actual );
	}

	function test_embed_google_fonts_set_header_font_only() {
		add_option( 'phoenix_child', array(
			'headers_font_options' => '"Open Sans", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Verdana, Tahoma, sans-serif',
		) );

		$expected  = '<link href="//fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">';
		$expected .= "\n";
		$actual    = embed_google_fonts();

		$this->assertEquals( $expected, $actual );
	}

	function test_embed_google_fonts_set_body_font_only() {
		add_option( 'optionsframework', array(
			'id' => 'phoenix_child',
		) );
		add_option( 'phoenix_child', array(
			'body_font_options' => '"Source Sans Pro", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Verdana, Tahoma, sans-serif',
		) );

		$expected  = '<link href="//fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet" type="text/css">';
		$expected .= "\n";
		$actual    = embed_google_fonts();

		$this->assertEquals( $expected, $actual );
	}

	function test_embed_google_fonts_set_different_header_body_fonts() {
		add_option( 'phoenix_child', array(
			'headers_font_options' => '"Open Sans", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Verdana, Tahoma, sans-serif',
			'body_font_options'    => '"Source Sans Pro", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Verdana, Tahoma, sans-serif',
		) );

		$expected  = '<link href="//fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">';
		$expected .= "\n";
		$expected .= '<link href="//fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet" type="text/css">';
		$expected .= "\n";
		$actual    = embed_google_fonts();

		$this->assertEquals( $expected, $actual );
	}

	function test_embed_google_fonts_set_same_header_body_fonts() {
		add_option( 'phoenix_child', array(
			'headers_font_options' => '"Open Sans", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Verdana, Tahoma, sans-serif',
			'body_font_options'    => '"Open Sans", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Verdana, Tahoma, sans-serif',
		) );

		$expected  = '<link href="//fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">';
		$expected .= "\n";
		$actual    = embed_google_fonts();

		$this->assertEquals( $expected, $actual );
	}

}
?>
