<?php

require_once WP_CONTENT_DIR . '/themes/phoenix/lib/custom-landing-page-css.php';

class PerPageCSSTest extends WP_UnitTestCase {
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

	function test_landing_page_should_render_per_page_css() {
		global $post;

		$post_id = $this->_create_post();
		$post = get_post( $post_id );
		$post->post_type = 'landing_page';
		update_post_meta( $post_id ,'custom_landing_page_css', '.header' );

		$expected = '<!---Per Page CSS --->';

		ob_start();
		custom_landing_page_css_func();
		$actual = ob_get_clean();

		$this->assertContains( $expected, $actual);

	}

	function test_search_result_page_should_not_render_per_page_css() {
		global $wp_query;

		$wp_query->is_search = True;

		$post = get_post( $post_id );
		update_post_meta( $post_id ,'custom_per_page_css', '.header' );

		$expected = '<!---Per Page CSS --->';

		ob_start();
		do_action( 'custom_landing_page_css' );
		$actual = ob_get_clean();

		$this->assertNotContains( $expected, $actual);
	}
}
