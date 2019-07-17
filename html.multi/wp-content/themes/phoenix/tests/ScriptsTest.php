<?php
require_once WP_CONTENT_DIR . '/themes/phoenix/lib/scripts.php';

class ScriptsTest extends WP_UnitTestCase {
	function setUp() {
		parent::setUp();
	}

	function tearDown() {
		parent::tearDown();
	}

	function test_enqueue_script_comment_reply() {
		global $wp_scripts;
		phoenix_scripts();
		$actual = $wp_scripts->registered['comment-reply'];
		$this->assertNotNull($actual);
	}

	function test_enqueue_script_skip_link_focus_fix() {
		global $wp_scripts;
		phoenix_scripts();
		$actual = $wp_scripts->registered['skip-link-focus-fix'];
		$this->assertNotNull($actual);
	}

	/*
	 * check script keyboard-image-navigation (need mock)
	 * check script new relic
	 */
}
?>
