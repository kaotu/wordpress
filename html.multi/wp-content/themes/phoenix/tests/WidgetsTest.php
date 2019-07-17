<?php
require_once WP_CONTENT_DIR . '/themes/phoenix/lib/widgets.php';
require_once WP_CONTENT_DIR . '/themes/phoenix/lib/pronto-extended-widgets.php';

class WidgetsTest extends WP_UnitTestCase {

	function setUp() {
		parent::setUp();
		switch_theme( 'phoenix' );
	}

	function tearDown() {
		parent::tearDown();
	}

	function test_phoenix_widgets_init() {
		global $wp_registered_sidebars;

		do_action( 'widgets_init' );

		$expected = array(
			'sidebar-1' => array(
				'name'          => 'Sidebar',
				'id'            => 'sidebar-1',
				'description'   => '',
				'class'         => '',
				'before_widget' => '<div class="widget clearfix">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);

		// Ignore the ID.
		// Here we will check only the values inside which
		// are more important.
		$actual = $wp_registered_sidebars['sidebar-1'];

		$result = array_diff( $expected['sidebar-1'], $actual );

		$this->assertEquals( 1, count( $result ) );

		$expected_before_widget_args = '<div id="%1$s" class="widget clearfix %2$s">';
		$this->assertEquals( $expected_before_widget_args, $actual['before_widget']);
	}
}
