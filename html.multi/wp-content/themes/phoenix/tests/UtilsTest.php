<?php
require_once WP_CONTENT_DIR . '/themes/phoenix/lib/utils.php';
require_once WP_CONTENT_DIR . '/themes/phoenix/options.php';

class UtilsTest extends WP_UnitTestCase {
	function setUp() {
		parent::setUp();
	}

	function tearDown() {
		parent::tearDown();
	}

	function _create_post() {
		$post_id = $this->factory->post->create( array(
			'post_author'  => '1',
			'post_date'    => '2013-06-18 02:25:11',
			'post_content' => 'Post Content <img src="first-image-in-post-content.jpg"> Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus',
			'post_title'   => 'Post Title',
			'post_excerpt' => 'Post Excerpt',
			'post_status'  => 'publish',
			'post_type'    => 'post',
		) );

		return $post_id;
	}

	function _set_featured_image_from_attachment() {
		// Get image file
		$filename = ( DIR_TESTDATA . '/images/test-image.jpg' );

		// Get image data
		$contents = file_get_contents( $filename );

		// Upload file to uploads folder
		$upload = wp_upload_bits( basename( $filename ), null, $contents );
		$this->assertTrue( empty( $upload['error'] ) );

		$parent_post_id = -1;
		$type           = '';

		if ( ! empty( $upload['type'] ) ) {
			$type = $upload['type'];
		}
		else {
			$mime = wp_check_filetype( $upload['file'] );
			if ( $mime ) {
				$type = $mime['type'];
			}
		}

		// Set attachment data
		$attachment = array(
			'post_title'     => basename( $upload['file'] ),
			'post_content'   => '',
			'post_type'      => 'attachment',
			'post_parent'    => $parent_post_id,
			'post_mime_type' => $type,
			'guid'           => $upload['url'],
		);

		// Create the attachment: insert an attachment into the media library
		// generates all of the appropriate thumbnails
		$attach_id = wp_insert_attachment( $attachment, $upload['file'], $parent_post_id );

		// you must first include the image.php file
		// for the function wp_generate_attachment_metadata() to work
		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		// Define attachment metadata
		$attach_data = wp_generate_attachment_metadata( $attach_id, $upload['file'] );

		// Assign metadata to attachment
		wp_update_attachment_metadata( $attach_id, $attach_data );

		return $attach_id;
	}

	function _dummy_function_used_for_shortcode() {
		return NULL;
	}

	function test_pagination_first_page_from_three_pages() {
		global $paged;

		$paged = 1;
		$pages = 3;
		$range = 3;

		$expected  = '<div class="text-center">';
		$expected .= '<ul class="pagination">';
		$expected .= '<li class="active"><span>1</span></li>';
		$expected .= '<li><a href="http://example.org/?paged=2">2</a></li>';
		$expected .= '<li><a href="http://example.org/?paged=3">3</a></li>';
		$expected .= '</ul>';
		$expected .= '</div>';

		ob_start();
		pagination( $pages, $range );
		$actual = ob_get_clean();

		$this->assertEquals( $expected, $actual );
	}

	function test_pagination_second_page_from_three_pages() {
		global $paged;

		$paged = 2;
		$pages = 3;
		$range = 3;

		$expected  = '<div class="text-center">';
		$expected .= '<ul class="pagination">';
		$expected .= '<li><a href="http://example.org/">1</a></li>';
		$expected .= '<li class="active"><span>2</span></li>';
		$expected .= '<li><a href="http://example.org/?paged=3">3</a></li>';
		$expected .= '</ul>';
		$expected .= '</div>';

		ob_start();
		pagination( $pages, $range );
		$actual = ob_get_clean();

		$this->assertEquals( $expected, $actual );
	}

	function test_pagination_third_page_from_three_pages() {
		global $paged;

		$paged = 3;
		$pages = 3;
		$range = 3;

		$expected  = '<div class="text-center">';
		$expected .= '<ul class="pagination">';
		$expected .= '<li><a href="http://example.org/">1</a></li>';
		$expected .= '<li><a href="http://example.org/?paged=2">2</a></li>';
		$expected .= '<li class="active"><span>3</span></li>';
		$expected .= '</ul>';
		$expected .= '</div>';

		ob_start();
		pagination( $pages, $range );
		$actual = ob_get_clean();

		$this->assertEquals( $expected, $actual );
	}

	function test_pagination_third_page_from_nine_pages() {
		global $paged;

		$paged = 3;
		$pages = 9;
		$range = 3;

		$expected  = '<div class="text-center">';
		$expected .= '<ul class="pagination">';
		$expected .= '<li><a href="http://example.org/?paged=2">&lsaquo;</a></li>';
		$expected .= '<li><a href="http://example.org/">1</a></li>';
		$expected .= '<li><a href="http://example.org/?paged=2">2</a></li>';
		$expected .= '<li class="active"><span>3</span></li>';
		$expected .= '<li><a href="http://example.org/?paged=4">4</a></li>';
		$expected .= '<li><a href="http://example.org/?paged=5">5</a></li>';
		$expected .= '<li><a href="http://example.org/?paged=6">6</a></li>';
		$expected .= '<li><a href="http://example.org/?paged=4">&rsaquo;</a></li>';
		$expected .= '<li><a href="http://example.org/?paged=9">&raquo;</a></li>';
		$expected .= '</ul>';
		$expected .= '</div>';

		ob_start();
		pagination( $pages, $range );
		$actual = ob_get_clean();

		$this->assertEquals( $expected, $actual );
	}

	function test_get_post_image_if_no_featured_image() {
		global $post;

		$post_id = $this->_create_post();
		$post    = get_post( $post_id );

		// Argument's value can be anything here.
		// Check only the image's URL is correct.
		$image = get_post_image( 300, 'img-thumbnail' );

		$expected  = '<img src="first-image-in-post-content.jpg" alt="Post Title" class="img-thumbnail" />';
		$expected .= '<meta content="first-image-in-post-content.jpg" itemprop="image">';
		$actual    = $image;

		$this->assertEquals( $expected, $actual );
	}

	function test_get_post_image_if_featured_image() {
		global $post;

		$post_id = $this->_create_post();
		$post    = get_post( $post_id );

		$attach_id = $this->_set_featured_image_from_attachment();
		set_post_thumbnail( $post_id, $attach_id );

		// Argument's value can be anything here.
		// Check only the image's URL is correct.
		$image = get_post_image( 300, 'img-thumbnail' );

		// The size needs to be the same as we use in the catch_that_image function.
		$image_src = wp_get_attachment_image_src( $attach_id, 300 );
		$expected  = '<img width="50" height="50" src="' . $image_src[0] . '" ';
		$expected .= 'class="img-thumbnail wp-post-image" alt="test-image.jpg" />';
		$expected .= '<meta content="' . $image_src[0] . '" itemprop="image">';
		$actual    = $image;

		// Delete attached image on server
		// We need to delete the image before run a test because
		// if a test below fails, the image won't be deleted.
		wp_delete_attachment( $attach_id, true );

		$expected = 'first-image-in-post-content.jpg';
		$expected  = '<img width="50" height="50" src="first-image-in-post-content.jpg" ';
		$expected .= 'class="img-thumbnail wp-post-image" alt="test-image.jpg" />';
		$expected .= '<meta content="first-image-in-post-content.jpg" itemprop="image">';

		$this->assertNotEquals( $expected, $actual );
	}

	function test_catch_that_image_if_no_featured_image() {
		global $post;

		$post_id = $this->_create_post();
		$post    = get_post( $post_id );

		// Argument's value can be anything here.
		// Check only the image's URL is correct.
		$image = catch_that_image( 300, 'img-thumbnail' );

		$expected = 'first-image-in-post-content.jpg';
		$actual   = $image['url'];

		$this->assertEquals( $expected, $actual );
	}

	function test_catch_that_image_if_featured_image() {
		global $post;

		$post_id = $this->_create_post();
		$post    = get_post( $post_id );

		$attach_id = $this->_set_featured_image_from_attachment();
		set_post_thumbnail( $post_id, $attach_id );

		// Argument's value can be anything here.
		// Check only the image's URL is correct.
		$image = catch_that_image( 300, 'img-thumbnail' );

		// The size needs to be the same as we use in the catch_that_image function.
		$expected = wp_get_attachment_image_src( $attach_id, 300 );
		$actual   = $image['url'];

		// Delete attached image on server
		// We need to delete the image before run a test because
		// if a test below fails, the image won't be deleted.
		wp_delete_attachment( $attach_id, true );

		$this->assertEquals( $expected[0], $actual );

		$expected = 'first-image-in-post-content.jpg';

		$this->assertNotEquals( $expected, $actual );
	}

	function test_shortcode_exists() {
		$shortcode = 'dummy_shortcode';
		add_shortcode( $shortcode, array( __CLASS__, '_dummy_function_used_for_shortcode' ) );

		$shortcode_exists = shortcode_exists( $shortcode );

		$this->assertTrue( $shortcode_exists );
	}

	function test_shortcode_not_exist() {
		$shortcode = 'dummy_shortcode';
		remove_shortcode( $shortcode );

		$shortcode = 'dummy_shortcode';

		$shortcode_exists = shortcode_exists( $shortcode );

		$this->assertFalse( $shortcode_exists );
	}

	function test_custom_edit_post_link() {
		$text = 'post-edit-link custom class more';

		$actual   = custom_edit_post_link( $text );
		$expected = 'post-edit-link btn btn-default custom class more';

		$this->assertEquals( $expected, $actual );
	}

	function test_filter_revisions_to_keep_for_safecss() {
		$num  = 0;
		$post = new stdClass();
		   $post->post_type = 'safecss';

		$expected = 20;
		$actual   = filter_revisions_to_keep( $num, $post );

		$this->assertEquals( $expected, $actual );
	}

	function test_filter_revisions_to_keep_for_page() {
		$num  = 0;
		$post = new stdClass();
		   $post->post_type = 'page';

		$expected = 5;
		$actual   = filter_revisions_to_keep( $num, $post );

		$this->assertEquals( $expected, $actual );
	}

	function test_filter_revisions_to_keep_for_post() {
		$num  = 0;
		$post = new stdClass();
		   $post->post_type = 'post';

		$expected = 5;
		$actual   = filter_revisions_to_keep( $num, $post );

		$this->assertEquals( $expected, $actual );
	}

	function test_filter_revisions_to_keep_for_newsletter() {
		$num  = 0;
		$post = new stdClass();
		   $post->post_type = 'newsletter';

		$expected = 5;
		$actual   = filter_revisions_to_keep( $num, $post );

		$this->assertEquals( $expected, $actual );
	}

	function test_filter_revisions_to_keep_for_landing_page() {
		$num  = 0;
		$post = new stdClass();
		   $post->post_type = 'landing_page';

		$expected = 5;
		$actual   = filter_revisions_to_keep( $num, $post );

		$this->assertEquals( $expected, $actual );
	}

	function test_filter_revisions_to_keep_for_testimonial() {
		$num  = 0;
		$post = new stdClass();
		   $post->post_type = 'testimonial';

		$expected = 5;
		$actual   = filter_revisions_to_keep( $num, $post );

		$this->assertEquals( $expected, $actual );
	}

	function test_filter_revisions_to_keep_for_team_member() {
		$num  = 0;
		$post = new stdClass();
		   $post->post_type = 'team_member';

		$expected = 5;
		$actual   = filter_revisions_to_keep( $num, $post );

		$this->assertEquals( $expected, $actual );
	}

	function test_filter_revisions_to_keep_for_partner() {
		$num  = 0;
		$post = new stdClass();
		   $post->post_type = 'partner';

		$expected = 5;
		$actual   = filter_revisions_to_keep( $num, $post );

		$this->assertEquals( $expected, $actual );
	}

	function test_filter_revisions_to_keep_for_header() {
		$num  = 0;
		$post = new stdClass();
		   $post->post_type = 'header';

		$expected = 5;
		$actual   = filter_revisions_to_keep( $num, $post );

		$this->assertEquals( $expected, $actual );
	}

	function test_filter_revisions_to_keep_for_footer() {
		$num  = 0;
		$post = new stdClass();
		   $post->post_type = 'footer';

		$expected = 5;
		$actual   = filter_revisions_to_keep( $num, $post );

		$this->assertEquals( $expected, $actual );
	}

	function test_clip_normal_sentence() {
		$content  = 'this is a normal sentence.';
		$max_char = 10;

		$expected = 'this is a normal sentence.';
		$actual   = clip_sentence( $content, $max_char );

		$this->assertEquals( $expected, $actual );
	}

	function test_clip_no_sentence() {
		$content  = 'this is a normal sentence';
		$max_char = 10;

		$expected = 'this is a normal sentence';
		$actual   = clip_sentence( $content, $max_char );

		$this->assertEquals( $expected, $actual );
	}

	function test_clip_one_sentence() {
		$content  = 'this is a first sentence. And this is a second.';
		$max_char = 10;

		$expected = 'this is a first sentence.';
		$actual   = clip_sentence( $content, $max_char );

		$this->assertEquals( $expected, $actual );
	}

	function test_clip_two_sentence() {
		$content  = 'this is a first sentence. And this is a second.';
		$max_char = 30;

		$expected = 'this is a first sentence. And this is a second.';
		$actual   = clip_sentence( $content, $max_char );

		$this->assertEquals( $expected, $actual );
	}

	function test_clip_abbreviations_sentence() {
		$content  = 'this is a first sentence. This is a abbreviations sentence Eg. Ph.D. And this is a second.';
		$max_char = 30;

		$expected = 'this is a first sentence. This is a abbreviations sentence Eg. Ph.D. And this is a second.';
		$actual   = clip_sentence( $content, $max_char );

		$this->assertEquals( $expected, $actual );
	}

	function test_clip_number_order_sentence() {
		$content  = 'this is a first sentence. This is a number 1. and 2. but we will cut here.';
		$max_char = 30;

		$expected = 'this is a first sentence. This is a number 1. and 2. but we will cut here.';
		$actual   = clip_sentence( $content, $max_char );

		$this->assertEquals( $expected, $actual );
	}

	function test_change_comment_class_to_remove_unnecessary_classes() {
		$classes[] = 'even';
		$classes[] = 'thread-even';
		$classes[] = 'odd';
		$classes[] = 'thread-odd';
		$classes[] = 'alt';
		$classes[] = 'thread-alt';

		$expected = array();
		$actual   = change_comment_class( $classes );

		$result = array_diff( $expected, $actual );

		$this->assertEquals( 0, count( $result ) );
	}

	function test_change_comment_class_to_remove_unnecessary_classes_and_add_well_class() {
		$classes[] = 'even';
		$classes[] = 'thread-even';
		$classes[] = 'odd';
		$classes[] = 'thread-odd';
		$classes[] = 'alt';
		$classes[] = 'thread-alt';
		$classes[] = 'depth-1';

		$expected[] = 'depth-1';
		$expected[] = 'well';
		$actual     = change_comment_class( $classes );

		$result = array_diff( $expected, $actual );

		$this->assertEquals( 0, count( $result ) );
	}

	function test_change_avatar_class() {
		$class = 'test-class-before avatar avatar-60 photo test-class-after';

		$expected = 'test-class-before avatar photo media-object img-rounded test-class-after';
		$actual   = change_avatar_class( $class );

		$this->assertEquals( $expected, $actual );
	}

	function test_clip_text() {
		$max_char = 10;
		$sentence = 'This is a sentence to test clip text.';

		$expected = 'This is a';
		$actual   = clip_text( $sentence, $max_char );

		$this->assertEquals( $expected, $actual );
	}

	function test_clip_text_no_max_char() {
		$max_char = 0;
		$sentence = 'This is a sentence to test clip text.';

		$expected = '';
		$actual   = clip_text( $sentence, $max_char );

		$this->assertEquals( $expected, $actual );
	}

	function test_clip_text_with_added_text() {
		$max_char = 10;
		$sentence = 'This is a sentence to test clip text.';
		$added_text = ' ...';

		$expected = 'This is a ...';
		$actual   = clip_text( $sentence, $max_char, $added_text );

		$this->assertEquals( $expected, $actual );
	}

	function test_clip_text_with_max_char_is_over_sentence() {
		$max_char = 1000;
		$sentence = 'This is a sentence to test clip text.';

		$expected = 'This is a sentence to test clip text.';
		$actual   = clip_text( $sentence, $max_char );

		$this->assertEquals( $expected, $actual );
	}

	function test_clip_text_with_max_char_is_over_sentence_and_added_text() {
		$max_char = 1000;
		$sentence = 'This is a sentence to test clip text.';
		$added_text = ' ...';

		$expected = 'This is a sentence to test clip text.';
		$actual   = clip_text( $sentence, $max_char, $added_text );

		$this->assertEquals( $expected, $actual );
	}

	function test_search_highlight() {
		$content = 'hello world';

		$search_keyword = 'hello';

		$expected = '<strong class="search-highlight">hello</strong> world';

		$actual = search_highlight( $content, $search_keyword );

		$this->assertEquals( $expected, $actual );

	}

	function test_get_color_palette() {
		switch_theme( 'phoenix_child');
		add_option( 'phoenix_child' , array(
		   'headers_font_options' => "Helvetica Neue, Helvetica, sans-serif",
		   'default_heading_color' => '#595959',
		   'body_font_options' => "Helvetica Neue, Helvetica, sans-serif",
		   'default_body_text_color' => "#333333",
		   'site_background' => array(
				"color"=> "",
				"image"=>"",
				"repeat"=> "repeat",
				"position"=>"top center",
				"attachment"=>"scroll",
				"size"=>"auto",
			),
		   'background_inner_page' => false,
		   'default_link_color'=> '0088cc',
		   'body_text_color_segment_1'=> '#333333',
		   'background_segment_1' => array(
				"color"=> "",
				"image"=> "",
				"repeat"=> "repeat",
				"position"=> "top center",
				"attachment"=>"scroll",
				"size"=> "auto",
			),
			'heading_font_color_segment_1' => '#333333',
		));

		ob_start();
		get_color_palette();
		$actual = ob_get_clean();

		$expected = "h1, h2, h3, h4, h5, h6 { font-family: Helvetica Neue, Helvetica, sans-serif; color: #595959; }";
		$this->assertContains( $expected, $actual );

		$expected = "input, button, select, textarea, body { font-family: Helvetica Neue, Helvetica, sans-serif; }";
		$this->assertContains( $expected, $actual );

		$expected = "body { color: #333333;}";
		$this->assertContains( $expected, $actual );

		$expected = "a { color: 0088cc; }";
		$this->assertContains( $expected, $actual );

		$expected = ".segment1 { color:#333333;}";
		$this->assertContains( $expected, $actual );

		$expected = ".segment1 h1, .segment1 h2, .segment1 h3, .segment1 h4, .segment1 h5, .segment1 h6 { color: #333333; }";
		$this->assertContains( $expected, $actual );

		$expected = "@media (max-width: 768px)";
		$this->assertContains( $expected, $actual );

		$expected = "@media (max-width: 480px)";
		$this->assertContains( $expected, $actual );
	}

	function test_search_landing_page_should_render_starting_container() {
		global $wp_query;

		$wp_query->is_search = True;

		$expected = '<div class="segment body-background">';
		$expected = '<div class="container">';
		$expected = '<div class="row content">';

		ob_start();
		normal_page_end_header_func();
		$actual = ob_get_clean();

		$this->assertContains( $expected, $actual );

	}

	function test_post_type_showcase_should_not_render_starting_container() {
		global $post;

		$post_id = $this->_create_post();
		$post    = get_post( $post_id );

		$post->post_type = 'showcase';

		$expected = '';

		ob_start();
		normal_page_end_header_func();
		$actual = ob_get_clean();

		$this->assertEquals( $expected, $actual );
	}

	function test_search_landing_page_should_render_ending_container() {
		global $wp_query;

		$wp_query->is_search = True;

		$expected = '</div>';
		$expected = '</div>';
		$expected = '</div>';

		ob_start();
		normal_page_start_footer_func();
		$actual = ob_get_clean();

		$this->assertContains( $expected, $actual );

	}

	// function test_post_type_landing_page_shouldnt_render_starting_container() {
	//     global $post;

	//     $post->post_type = 'landing_page';

	//     $expected = '<div class="segment body-background">';
	//     $expected = '<div class="container">';
	//     $expected = '<div class="row content">';

	//     ob_start();
	//     normal_page_end_header_func();
	//     $actual = ob_get_clean();

	//     $this->assertNotContains( $expected, $actual );
	// }

	// function test_post_type_landing_page_shouldnt_render_ending_container() {
	//     global $post;

	//     $post->post_type = 'landing_page';

	//     $expected = '</div>';
	//     $expected = '</div>';
	//     $expected = '</div>';

	//     ob_start();
	//     normal_page_start_footer_func();
	//     $actual = ob_get_clean();

	//     $this->assertNotContains( $expected, $actual );
	// }

	function test_strip_content_on_blog_index() {
		global $wp_query;
		$wp_query->is_home = true;

		$post_id = $this->_create_post();
		$post    = get_post( $post_id );

		$expected  = '<p>Post Content  Pellentesque habitant morbi tristique ';
		$expected .= 'senectus et netus et malesuada fames ac turpis egestas. ';
		$expected .= 'Vestibulum tortor quam, feugiat vitae, ultricies eget, ';
		$expected .= 'tempor sit amet, ante. Donec eu libero sit amet quam ';
		$expected .= 'egestas semper. Aenean ultricies mi vitae est.</p>';
		$actual = strip_the_content( $post->post_content );

		$this->assertEquals( $expected, $actual );

		$wp_query->is_home = false;

		$expected = $post->post_content;
		$actual = strip_the_content( $post->post_content );

		$this->assertEquals( $expected, $actual );
	}

	function test_strip_shortcode_on_blog_index() {
		global $wp_query;
		$wp_query->is_home = true;

		$post_id = $this->_create_post();
		$post    = get_post( $post_id );

		$actual = strip_the_content( $post->post_content );

		$expected  = '<p>Post Content  Pellentesque habitant morbi tristique senectus et netus et';
		$expected .= ' malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, ';
		$expected .= 'tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est.</p>';
		$this->assertEquals( $expected, $actual );

		$wp_query->is_home = false;

		$content  = 'Post content [button text="Submit" target="_blank" ';
		$content .= 'type="primary" size="mini"] test.';
		$actual = strip_the_content( $content );

		$expected = $content;
		$this->assertEquals( $expected, $actual );
	}

	function test_strip_content_on_tag_archive_page() {
		global $wp_query;
		$wp_query->is_tag = true;

		$post_id = $this->_create_post();
		$post    = get_post( $post_id );

		$expected  = '<p>Post Content  Pellentesque habitant morbi tristique ';
		$expected .= 'senectus et netus et malesuada fames ac turpis egestas. ';
		$expected .= 'Vestibulum tortor quam, feugiat vitae, ultricies eget, ';
		$expected .= 'tempor sit amet, ante. Donec eu libero sit amet quam ';
		$expected .= 'egestas semper. Aenean ultricies mi vitae est.</p>';
		$actual = strip_the_content( $post->post_content );

		$this->assertEquals( $expected, $actual );

		$wp_query->is_tag = false;

		$expected = $post->post_content;
		$actual = strip_the_content( $post->post_content );

		$this->assertEquals( $expected, $actual );
	}

	function test_strip_shortcode_on_tag_archive_page() {
		global $wp_query;
		$wp_query->is_tag = true;

		$content  = 'Post content [button text="Submit" target="_blank" ';
		$content .= 'type="primary" size="mini"] test.';

		$actual = strip_the_content( $content );

		$expected  = '<p>Post content  test.</p>';
		$this->assertEquals( $expected, $actual );

		$wp_query->is_tag = false;

		$content  = 'Post content [button text="Submit" target="_blank" ';
		$content .= 'type="primary" size="mini"] test.';
		$actual = strip_the_content( $content );

		$expected = $content;
		$this->assertEquals( $expected, $actual );
	}

	function test_strip_content_on_category_archive_page() {
		global $wp_query;
		$wp_query->is_category = true;

		$post_id = $this->_create_post();
		$post    = get_post( $post_id );

		$expected  = '<p>Post Content  Pellentesque habitant morbi tristique ';
		$expected .= 'senectus et netus et malesuada fames ac turpis egestas. ';
		$expected .= 'Vestibulum tortor quam, feugiat vitae, ultricies eget, ';
		$expected .= 'tempor sit amet, ante. Donec eu libero sit amet quam ';
		$expected .= 'egestas semper. Aenean ultricies mi vitae est.</p>';
		$actual = strip_the_content( $post->post_content );

		$this->assertEquals( $expected, $actual );

		$wp_query->is_category = false;

		$expected = $post->post_content;
		$actual = strip_the_content( $post->post_content );

		$this->assertEquals( $expected, $actual );
	}

	function test_strip_shortcode_on_category_archive_page() {
		global $wp_query;
		$wp_query->is_category = true;

		$post_id = $this->factory->post->create( array(
			'post_author'  => '1',
			'post_date'    => '2013-06-18 02:25:11',
			'post_content' => 'Post Content [button text="submit" target="_blank" type="primary" size="mini"]',
			'post_title'   => 'Post Title',
			'post_excerpt' => 'Post Excerpt',
			'post_status'  => 'publish',
			'post_type'    => 'post',
		) );
		$post    = get_post( $post_id );

		$actual = strip_the_content( $post->post_content );

		$expected  = '<p>Post content test.</p>';
		$this->assertEquals( $expected, $actual );

		$wp_query->is_category = false;

		$content  = 'Post content [button text="Submit" target="_blank" ';
		$content .= 'type="primary" size="mini"] test.';
		$actual = strip_the_content( $content );

		$expected = $content;
		$this->assertEquals( $expected, $actual );
	}

	function test_strip_content_on_archive_page() {
		global $wp_query;
		$wp_query->is_archive = true;

		$post_id = $this->_create_post();
		$post    = get_post( $post_id );

		$expected  = '<p>Post Content  Pellentesque habitant morbi tristique ';
		$expected .= 'senectus et netus et malesuada fames ac turpis egestas. ';
		$expected .= 'Vestibulum tortor quam, feugiat vitae, ultricies eget, ';
		$expected .= 'tempor sit amet, ante. Donec eu libero sit amet quam ';
		$expected .= 'egestas semper. Aenean ultricies mi vitae est.</p>';
		$actual = strip_the_content( $post->post_content );

		$this->assertEquals( $expected, $actual );

		$wp_query->is_archive = false;

		$expected = $post->post_content;
		$actual = strip_the_content( $post->post_content );

		$this->assertEquals( $expected, $actual );
	}

	function test_strip_shortcode_on_archive_page() {
		global $wp_query;
		$wp_query->is_archive = true;

		$content  = 'Post content [button text="Submit" target="_blank" ';
		$content .= 'type="primary" size="mini"] test.';

		$actual = strip_the_content( $content );

		$expected  = '<p>Post content  test.</p>';
		$this->assertEquals( $expected, $actual );

		$wp_query->is_archive = false;

		$content  = 'Post content [button text="Submit" target="_blank" ';
		$content .= 'type="primary" size="mini"] test.';
		$actual = strip_the_content( $content );

		$expected = $content;
		$this->assertEquals( $expected, $actual );
	}

	function test_set_default_site_options() {
		$new_site = get_option( 'phoenix_child' );
		$this->assertEquals( false, $new_site );

		$actual = set_default_site_options();
		$expected = get_option( 'phoenix_child' );

		$this->assertEquals( $expected , $actual );
	}

	function test_max_safe_redirect_url_should_return_500() {
		$actual = dbx_srm_max_redirects();
		$this->assertEquals( 500, $actual );
	}

	function test_max_safe_redirect_url_should_be_added_to_hook() {
		global $wp_filter;
		$this->assertTrue( array_key_exists(
			'dbx_srm_max_redirects',
			$wp_filter['srm_max_redirects'][10]
		) );
	}

	function test_ssl_srcset_should_not_change_url_if_no_ssl() {
		$sources = array(
			'300' => array(
				'url'        => 'http://local.bypronto.dev/wp-content/uploads/2015/12/13874384551532261874-300x126.gif',
				'descriptor' => 'w',
				'value'      => 300
			)
		);
		$actual = ssl_srcset( $sources );
		$expected = $sources;
		$this->assertEquals( $expected, $actual );
	}

	function test_ssl_srcset_should_use_https_for_url_if_ssl() {
		$_SERVER['HTTPS'] = 'on';
		$sources = array(
			'300' => array(
				'url'        => 'http://local.bypronto.dev/wp-content/uploads/2015/12/13874384551532261874-300x126.gif',
				'descriptor' => 'w',
				'value'      => 300
			)
		);
		$actual = ssl_srcset( $sources );
		$expected = $sources;
		$expected[300]['url'] = 'https://local.bypronto.dev/wp-content/uploads/2015/12/13874384551532261874-300x126.gif';
		$this->assertEquals( $expected, $actual );

		$_SERVER['HTTPS'] = null;
	}

	function test_ssl_srcset_should_be_added_to_wp_calculate_image_srcset() {
		global $wp_filter;
		$this->assertTrue( array_key_exists(
			'ssl_srcset',
			$wp_filter['wp_calculate_image_srcset'][10]
		) );
	}

	function test_get_fav_icon() {
		$post_id = $this->factory->post->create( array(
			'post_author'  => '1',
			'post_date'    => '2013-06-18 02:25:11',
			'post_content' => 'Post Content',
			'post_title'   => 'Post Title',
			'post_excerpt' => 'Post Excerpt',
			'post_status'  => 'publish',
			'post_type'    => 'post',
			"guid"         => "http://www.prontotools.io"
		) );
		update_option( "site_icon", $post_id );

		$expected = '<link rel="shortcut icon" href="http://www.prontotools.io" type="image/x-icon" />';
		$actual   = get_fav_icon();

		$this->assertEquals( $expected, $actual );
	}

	function test_get_fav_icon_should_use_https_if_ssl_is_enabled() {
		$_SERVER['HTTPS'] = 'on';

		$post_id = $this->factory->post->create( array(
			'post_author'  => '1',
			'post_date'    => '2013-06-18 02:25:11',
			'post_content' => 'Post Content',
			'post_title'   => 'Post Title',
			'post_excerpt' => 'Post Excerpt',
			'post_status'  => 'publish',
			'post_type'    => 'post',
			"guid"         => "http://www.prontotools.io"
		) );
		update_option( "site_icon", $post_id );

		$expected = '<link rel="shortcut icon" href="https://www.prontotools.io" type="image/x-icon" />';
		$actual   = get_fav_icon();

		$this->assertEquals( $expected, $actual );

		$_SERVER['HTTPS'] = null;
	}

	function test_use_https_for_fav_icon_should_be_added_to_get_site_icon_url_filter() {
		global $wp_filter;
		$this->assertTrue( array_key_exists(
			"use_https_for_fav_icon",
			$wp_filter["get_site_icon_url"][10]
		) );
	}

	function test_use_https_for_fav_icon_if_ssl_is_enabled() {
		$_SERVER['HTTPS'] = 'on';

		$expected = "https://www.prontotools.io";
		$actual   = use_https_for_fav_icon( "http://www.prontotools.io", null, null );

		$this->assertEquals( $expected, $actual );

		$_SERVER['HTTPS'] = null;
	}

	function test_update_admin_email_should_update_in_db() {
		switch_theme( "phoenix_child" );

		add_option( 'phoenix_child', array(
			'admin_email' => 'test@mail.com'
		) );

		$update_admin_email = update_admin_email();

		$expected = get_option( 'phoenix_child' )['admin_email'];
		$actual = get_option( 'admin_email' );

		$this->assertEquals( $expected, $actual );
	}

	function test_hide_yoast_seo_ads_if_yoast_seo_plugin_is_activated() {
		activate_plugin('wordpress-seo/wp-seo.php');

		$expected  = "<style>";
		$expected .= ".wpseo_content_wrapper #sidebar-container.wpseo_content_cell, ";
		$expected .= "li#toplevel_page_wpseo_dashboard ul.wp-submenu.wp-submenu-wrap li:last-child";
		$expected .= "{ display: none; }";
		$expected .= "</style>\n";

		ob_start();
		hide_yoast_seo_ads();
		$actual = ob_get_clean();
		$this->assertEquals( $expected, $actual );
	}

	function test_should_not_show_style_for_hide_yoast_seo_if_yoast_seo_plugin_deactivated() {
		$expected = "<style>";
		$expected .= ".wpseo_content_wrapper #sidebar-container.wpseo_content_cell, ";
		$expected .= "li#toplevel_page_wpseo_dashboard ul.wp-submenu.wp-submenu-wrap li:last-child";
		$expected .= "{ display: none; }";
		$expected .= "</style>\n";

		ob_start();
		hide_yoast_seo_ads();
		$actual = ob_get_clean();
		$this->assertNotEquals( $expected, $actual );
	}

	function test_should_have_input_tag_in_allowedposttags() {
		global $allowedposttags;

		pheonix_allow_attributes_for_html();
		$this->assertTrue( array_key_exists(
			'input',
			$allowedposttags
		) );
		$this->assertTrue( array_key_exists(
			'name',
			$allowedposttags['input']
		) );
		$this->assertTrue( array_key_exists(
			'type',
			$allowedposttags['input']
		) );
		$this->assertTrue( array_key_exists(
			'placeholder',
			$allowedposttags['input']
		) );
		$this->assertTrue( array_key_exists(
			'required',
			$allowedposttags['input']
		) );
	}

	function test_should_have_link_tag_in_allowedposttags() {
		global $allowedposttags;

		pheonix_allow_attributes_for_html();

		$this->assertTrue( array_key_exists(
			'link',
			$allowedposttags
		) );
		$this->assertTrue( array_key_exists(
			'rel',
			$allowedposttags['link']
		) );
		$this->assertTrue( array_key_exists(
			'type',
			$allowedposttags['link']
		) );
		$this->assertTrue( array_key_exists(
			'href',
			$allowedposttags['link']
		) );
	}

	function test_page_header_should_contain_robot_tag_nofollow() {
		$expected = '<meta name="robots" content="noindex, nofollow" />';

		ob_start();
		apply_filters( 'login_head' , null );
		$actual = ob_get_clean();
		$this->assertContains( $expected, $actual );
	}

	function test_dequeue_fontawesome_css_if_quickiebar_pro_plugin_is_active() {
		wp_enqueue_style( 'fontawesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css', false, '4.3.0', 'all' );

		activate_plugin( 'quickiebar-pro/quickiebar-pro.php' );
		remove_fontawesome_css_from_quickiebarpro_plugin();
		do_action( 'init' );
		$expected = 'font-awesome.min.css';
		$actual = get_echo( 'wp_head' );
		$this->assertNotContains( $expected, $actual );
	}

	function test_not_dequeue_fontawesome_css_if_quickiebar_pro_plugin_is_not_active() {
		wp_enqueue_style( 'fontawesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css', false, '4.3.0', 'all' );

		remove_fontawesome_css_from_quickiebarpro_plugin();

		$expected = 'font-awesome.min.css';
		$actual = get_echo( 'wp_head' );
		$this->assertContains( $expected, $actual );
	}

	function test_remove_fontawesome_css_from_quickiebarpro_plugin_function_should_added_in_hook() {
		global $wp_filter;
		$this->assertTrue( array_key_exists(
			'remove_fontawesome_css_from_quickiebarpro_plugin',
			$wp_filter['wp_print_styles'][99]
		) );
	}

	function test_should_set_noindex_for_taxonomies_for_yoast_settings() {
		activate_plugin( 'wordpress-seo/wp-seo.php' );
		activate_plugin( 'pronto-partners-manager/pronto-partner.php' );
		activate_plugin( 'pronto-testimonials-manager/pronto-testimonial.php' );
		activate_plugin( 'pronto-team-members-manager/pronto-team-member.php' );

		add_option( 'wpseo_titles', array(
			'noindex-tax-slide-page'        => false,
			'noindex-tax-testimonial_group' => false,
			'noindex-tax-team_member_group' => false,
			'noindex-tax-partner_group'     => false,
			'noindex-tax-location_group'    => false,
			'noindex-tax-header_group'      => false,
			'noindex-tax-footer_group'      => false,
			'noindex-tax-post_tag'          => false,
			'noindex-tax-category'          => false,
			'noindex-author-wpseo'          => false,
			'disable-attachment'            => false
		) );

		change_yoast_settings_to_noindex();

		do_action( 'init' );

		$this->assertTrue(
			get_option( 'wpseo_titles' )['noindex-tax-slide-page'],
			true
		);
		$this->assertTrue(
			get_option( 'wpseo_titles' )['noindex-tax-testimonial_group'],
			true
		);
		$this->assertTrue(
			get_option( 'wpseo_titles' )['noindex-tax-team_member_group'],
			true
		);
		$this->assertTrue(
			get_option( 'wpseo_titles' )['noindex-tax-partner_group'],
			true
		);
		$this->assertTrue(
			get_option( 'wpseo_titles' )['noindex-tax-location_group'],
			true
		);
		$this->assertTrue(
			get_option( 'wpseo_titles' )['noindex-tax-header_group'],
			true
		);
		$this->assertTrue(
			get_option( 'wpseo_titles' )['noindex-tax-footer_group'],
			true
		);
		$this->assertTrue(
			get_option( 'wpseo_titles' )['noindex-tax-post_tag'],
			true
		);
		$this->assertTrue(
			get_option( 'wpseo_titles' )['noindex-tax-category'],
			true
		);
		$this->assertTrue(
			get_option( 'wpseo_titles' )['noindex-author-wpseo'],
			true
		);
		$this->assertTrue(
			get_option( 'wpseo_titles' )['disable-attachment'],
			true
		);
	}

	function test_should_set_noindex_for_content_type_for_yoast_settings() {
		activate_plugin( 'wordpress-seo/wp-seo.php' );
		activate_plugin( 'pronto-partners-manager/pronto-partner.php' );
		activate_plugin( 'pronto-testimonials-manager/pronto-testimonial.php' );
		activate_plugin( 'pronto-team-members-manager/pronto-team-member.php' );

		add_option( 'wpseo_titles', array(
			'noindex-testimonial'           => false,
			'noindex-ptarchive-testimonial' => false,
			'noindex-team_member'           => false,
			'noindex-ptarchive-team_member' => false,
			'noindex-partner'               => false,
			'noindex-ptarchive-partner'     => false
		) );

		change_yoast_settings_to_noindex();

		do_action( 'init' );

		$this->assertTrue(
			get_option( 'wpseo_titles' )['noindex-testimonial'],
			true
		);
		$this->assertTrue(
			get_option( 'wpseo_titles' )['noindex-ptarchive-testimonial'],
			true
		);
		$this->assertTrue(
			get_option( 'wpseo_titles' )['noindex-team_member'],
			true
		);
		$this->assertTrue(
			get_option( 'wpseo_titles' )['noindex-ptarchive-team_member'],
			true
		);
		$this->assertTrue(
			get_option( 'wpseo_titles' )['noindex-partner'],
			true
		);
		$this->assertTrue(
			get_option( 'wpseo_titles' )['noindex-ptarchive-partner'],
			true
		);
	}

	function test_remove_fontawesome_css_from_wpnotificationbar_plugin_function_should_added_in_hook() {
		global $wp_filter;

		$this->assertTrue( array_key_exists(
			'remove_fontawesome_css_from_wpnotificationbar_plugin',
			$wp_filter['wp_print_styles'][99]
		) );
	}

}
