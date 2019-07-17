<?php
/**
 * Gallery Tests
 */

include_once( ABSPATH . 'wp-admin/includes/media.php' );
switch_theme( 'phoenix', 'style' );

class ProntoGalleryTest extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();
	}

	function _create_post() {

		$post_id = $this->factory->post->create( array(
			'post_author'  => '1',
			'post_date'    => '2013-06-18 02:25:11',
			'post_content' => 'Post Content',
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

	// gallery-0-1
	function test_with_no_parameter() {
		$actual   = do_shortcode( '[gallery]' );
		$expected = '';

		$this->assertEquals( $expected, $actual );
	}

	// gallery-0-2
	function test_with_one_image() {
		$attach_ids = array();

		$attach_id = $this->_set_featured_image_from_attachment();
		array_push( $attach_ids, $attach_id );

		$image_attributes = wp_get_attachment_image_src( $attach_ids[0] );

		$expected  = '';
		$expected .= '<div class="gallery gallery-0-2 popup-gallery">';
		$expected .= '<div class="row gallery-row">';
		$expected .= '<div class="col-xs-6 col-sm-3 col-md-3">';
		$expected .= '<a class="thumbnail img-thumbnail" href=' . "'" . wp_get_attachment_url( $attach_ids[0] ) . "'>";
		$expected .= '<img width="50" height="50" src="' . $image_attributes[0] . '"';
		$expected .= " class=" . '"' . 'attachment-thumbnail' . '"' . ' alt="test-image.jpg" />';
		$expected .= '</a></div></div></div>';

		$shortcode = '[gallery ids="' . $attach_ids[0] . '"]';
		$actual    = do_shortcode( $shortcode );

		wp_delete_attachment( $attach_ids[0], true );

		$this->assertEquals( $expected, $actual );
	}

	// gallery-0-3
	function test_with_three_images() {
		$attach_ids = array();

		$attach_id = $this->_set_featured_image_from_attachment();
		array_push( $attach_ids, $attach_id );

		$attach_id = $this->_set_featured_image_from_attachment();
		array_push( $attach_ids, $attach_id );

		$attach_id = $this->_set_featured_image_from_attachment();
		array_push( $attach_ids, $attach_id );

		$image_attributes = wp_get_attachment_image_src( $attach_ids[0] );

		$expected  = '';
		$expected .= '<div class="gallery gallery-0-3 popup-gallery">';
		$expected .= '<div class="row gallery-row">';
		$expected .= '<div class="col-xs-6 col-sm-3 col-md-3">';
		$expected .= '<a class="thumbnail img-thumbnail" href=' . "'" . wp_get_attachment_url( $attach_ids[0] ) . "'>";
		$expected .= '<img width="50" height="50" src="' . $image_attributes[0] . '"';
		$expected .= " class=" . '"' . 'attachment-thumbnail' . '"' . ' alt="test-image.jpg" />';
		$expected .= '</a></div>';

		$image_attributes = wp_get_attachment_image_src( $attach_ids[1] );

		$expected .= '<div class="col-xs-6 col-sm-3 col-md-3">';
		$expected .= '<a class="thumbnail img-thumbnail" href=' . "'" . wp_get_attachment_url( $attach_ids[1] ) . "'>";
		$expected .= '<img width="50" height="50" src="' . $image_attributes[0] . '"';
		$expected .= " class=" . '"' . 'attachment-thumbnail' . '"' . ' alt="test-image1.jpg" />';
		$expected .= '</a></div>';

		$image_attributes = wp_get_attachment_image_src( $attach_ids[2] );

		$expected .= '<div class="col-xs-6 col-sm-3 col-md-3">';
		$expected .= '<a class="thumbnail img-thumbnail" href=' . "'" . wp_get_attachment_url( $attach_ids[2] ) . "'>";
		$expected .= '<img width="50" height="50" src="' . $image_attributes[0] . '"';
		$expected .= " class=" . '"' . 'attachment-thumbnail' . '"' . ' alt="test-image2.jpg" />';
		$expected .= '</a></div></div></div>';

		$shortcode = '[gallery ids="' . $attach_ids[0] . ', ' . $attach_ids[1] . ', ' . $attach_ids[2] . '"]';
		$actual    = do_shortcode( $shortcode );

		wp_delete_attachment( $attach_ids[0], true );
		wp_delete_attachment( $attach_ids[1], true );
		wp_delete_attachment( $attach_ids[2], true );

		$this->assertEquals( $expected, $actual );
	}

	function test_image_gallery_2_columns() {
		$attach_ids = array();

		$attach_id = $this->_set_featured_image_from_attachment();
		array_push( $attach_ids, $attach_id );

		$image_attributes = wp_get_attachment_image_src( $attach_ids[0] );

		$expected  = '';
		$expected .= '<div class="gallery gallery-0-4 popup-gallery">';
		$expected .= '<div class="row gallery-row">';
		$expected .= '<div class="col-xs-6 col-sm-6 col-md-6">';
		$expected .= '<a class="thumbnail img-thumbnail" href=' . "'" . wp_get_attachment_url( $attach_ids[0] ) . "'>";
		$expected .= '<img width="50" height="50" src="' . $image_attributes[0] . '"';
		$expected .= " class=" . '"' . 'attachment-thumbnail' . '"' . ' alt="test-image.jpg" />';
		$expected .= '</a></div></div></div>';

		$shortcode = '[gallery columns="2" ids="' . $attach_ids[0] . '"]';
		$actual    = do_shortcode( $shortcode );

		wp_delete_attachment( $attach_ids[0], true );

		$this->assertEquals( $expected, $actual );
	}

	function test_image_gallery_3_columns() {
		$attach_ids = array();

		$attach_id = $this->_set_featured_image_from_attachment();
		array_push( $attach_ids, $attach_id );

		$image_attributes = wp_get_attachment_image_src( $attach_ids[0] );

		$expected  = '';
		$expected .= '<div class="gallery gallery-0-5 popup-gallery">';
		$expected .= '<div class="row gallery-row">';
		$expected .= '<div class="col-xs-4 col-sm-4 col-md-4">';
		$expected .= '<a class="thumbnail img-thumbnail" href=' . "'" . wp_get_attachment_url( $attach_ids[0] ) . "'>";
		$expected .= '<img width="50" height="50" src="' . $image_attributes[0] . '"';
		$expected .= " class=" . '"' . 'attachment-thumbnail' . '"' . ' alt="test-image.jpg" />';
		$expected .= '</a></div></div></div>';

		$shortcode = '[gallery columns="3" ids="' . $attach_ids[0] . '"]';
		$actual    = do_shortcode( $shortcode );

		wp_delete_attachment( $attach_ids[0], true );

		$this->assertEquals( $expected, $actual );
	}

	function test_image_gallery_4_columns() {
		$attach_ids = array();

		$attach_id = $this->_set_featured_image_from_attachment();
		array_push( $attach_ids, $attach_id );

		$image_attributes = wp_get_attachment_image_src( $attach_ids[0] );

		$expected  = '';
		$expected .= '<div class="gallery gallery-0-6 popup-gallery">';
		$expected .= '<div class="row gallery-row">';
		$expected .= '<div class="col-xs-6 col-sm-3 col-md-3">';
		$expected .= '<a class="thumbnail img-thumbnail" href=' . "'" . wp_get_attachment_url( $attach_ids[0] ) . "'>";
		$expected .= '<img width="50" height="50" src="' . $image_attributes[0] . '"';
		$expected .= " class=" . '"' . 'attachment-thumbnail' . '"' . ' alt="test-image.jpg" />';
		$expected .= '</a></div></div></div>';

		$shortcode = '[gallery columns="4" ids="' . $attach_ids[0] . '"]';
		$actual    = do_shortcode( $shortcode );

		wp_delete_attachment( $attach_ids[0], true );

		$this->assertEquals( $expected, $actual );
	}

	function test_image_gallery_6_columns() {
		$attach_ids = array();

		$attach_id = $this->_set_featured_image_from_attachment();
		array_push( $attach_ids, $attach_id );

		$image_attributes = wp_get_attachment_image_src( $attach_ids[0] );

		$expected  = '';
		$expected .= '<div class="gallery gallery-0-7 popup-gallery">';
		$expected .= '<div class="row gallery-row">';
		$expected .= '<div class="col-xs-6 col-sm-2 col-md-2">';
		$expected .= '<a class="thumbnail img-thumbnail" href=' . "'" . wp_get_attachment_url( $attach_ids[0] ) . "'>";
		$expected .= '<img width="50" height="50" src="' . $image_attributes[0] . '"';
		$expected .= " class=" . '"' . 'attachment-thumbnail' . '"' . ' alt="test-image.jpg" />';
		$expected .= '</a></div></div></div>';

		$shortcode = '[gallery columns="6" ids="' . $attach_ids[0] . '"]';
		$actual    = do_shortcode( $shortcode );

		wp_delete_attachment( $attach_ids[0], true );

		$this->assertEquals( $expected, $actual );
	}
}
?>
