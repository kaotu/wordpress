<?php
/**
 * Tests for the Pronto Author Bio on Blog Post plugin.
 *
 * @package pronto
 */
require_once WP_CONTENT_DIR . '/themes/phoenix/lib/pronto-author-bio-on-blog-post.php';

class ProntoAuthorBioOnBlogPostTest extends WP_UnitTestCase {
	function setUp() {
		parent::setUp();
		$this->author = new WP_User( $this->factory->user->create( array( 'role' => 'editor' ) ) );
		$this->position_meta_id = add_metadata( 'user', $this->author->ID, 'position', 'Agile Coach' );
		$this->linkedin_profile_meta_id = add_metadata( 'user', $this->author->ID, 'linkedin_profile', 'nattanicha' );
		$this->author_box_meta_id = add_metadata( 'user', $this->author->ID, 'author_box', 'yes' );
		$this->author_email_meta_id = add_metadata( 'user', $this->author->ID, 'author_email', 'yes' );
	}

	function tearDown() {
		parent::tearDown();
	}

	function _create_post() {
		$this->post = $this->factory->post->create( array(
			'post_author'  => $this->author->ID,
			'post_date'    => '2013-06-18 02:25:11',
			'post_content' => 'Post Content',
			'post_title'   => 'Post Title',
			'post_status'  => 'publish',
			'post_type'    => 'post',
		) );
		return $this->post;
	}

	function test_show_bio_box_with_email() {
		$html = add_extra_social_links( $this->author );
		$expect_position = 'name="position" value="Agile Coach"';
		$expect_linkedin_profile = 'name="linkedin_profile" value="nattanicha"';
		$expect_author_box = 'name="author_box" value="yes" checked';
		$expect_author_email = 'name="author_email" value="yes" checked';

		$this->assertContains( $expect_position, $html );
		$this->assertContains( $expect_linkedin_profile, $html );
		$this->assertContains( $expect_author_box, $html );
		$this->assertContains( $expect_author_email, $html );

	}

	function test_show_bio_box_without_email() {
		$this->author_box_meta_id = update_metadata( 'user',
			$this->author->ID, 'author_email', 'no' );
		$html = add_extra_social_links( $this->author );
		$expect_position = 'name="position" value="Agile Coach"';
		$expect_linkedin_profile = 'name="linkedin_profile" value="nattanicha"';
		$expect_author_box = 'name="author_box" value="yes" checked';
		$expect_author_email = 'name="author_email" value="yes" checked';

		$this->assertContains( $expect_position, $html );
		$this->assertContains( $expect_linkedin_profile, $html );
		$this->assertContains( $expect_author_box, $html );
		$this->assertNotContains( $expect_author_email, $html );

	}

	function test_not_show_bio_box(){
		$this->author_box_meta_id = update_metadata( 'user',
			$this->author->ID, 'author_box', 'no' );
		$html = add_extra_social_links( $this->author );
		$expect_author_box = 'name="author_box" value="yes" checked';
		$this->assertNotContains( $expect_author_box, $html );

	}

	function test_change_avatar_css() {
		$class = change_avatar_css("avatar-249 photo");
		$expect = "avatar-249 photo img-responsive img-circle media-object pull-left col-xs-6 col-sm-4 col-md-3' itemprop='image";
		 $this->assertEquals( $expect, $class );
	}
}
