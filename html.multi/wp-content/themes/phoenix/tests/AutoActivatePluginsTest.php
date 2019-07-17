<?php
/**
 * @Test function run_activate_plugin() and plugins_to_active()
 */
switch_theme( 'phoenix', 'style' );
//require_once WP_CONTENT_DIR . '/themes/phoenix/plugins_to_activate.php';
//require_once WP_CONTENT_DIR . '/themes/phoenix/functions.php';

class AutoActivatePluginsTest extends WP_UnitTestCase {
	function setUp() {
		parent::setUp();

		$this->plugins_list = array(
			'pronto-breadcrumb-navigation/pronto-breadcrumb-navigation.php',
			'pronto-header-manager/header-custom-types.php',
			'pronto-footer-manager/footer-custom-types.php',
			'pronto-page-option-settings/pronto_page_option_setting.php',
			'pronto-shortcode/pronto-shortcode.php',
			'pronto-shortcodes-ui/pronto-shortcodes-ui.php',
			'pronto-sidebar-navigation/pronto-sidebar-navigation.php',
			'pronto-site-option-backup/pronto-site-option-backup.php',
			'pronto-company-information/pronto-company-information.php',
			'add-descendants-as-submenu-items/add-descendants-as-submenu-items.php',
			'simple-page-ordering/simple-page-ordering.php',
			'wooslider/wooslider.php',
			'woosidebars/woosidebars.php',
			'gravityforms/gravityforms.php',
			'jetpack/jetpack.php',
			'apronto-jetpack-overrides/pronto-jetpack-overrides.php',
			'pronto-locations-manager/pronto-locations-manager.php',
		);
	}

	function tearDown() {
		parent::tearDown();
	}

	function test_plugins_to_active() {
		$test_plugins_to_active = plugins_to_active();

		$this->assertTrue( is_array( $test_plugins_to_active ) );
		$this->assertEquals( $test_plugins_to_active, $this->plugins_list );
	}

	function test_run_activate_plugin() {
		$active_plugins = get_option( 'active_plugins' );

		run_activate_plugin();

		$active_plugins = get_option( 'active_plugins' );
		sort( $this->plugins_list );

		$this->assertNotEmpty( $active_plugins );
		$this->assertEquals( $active_plugins, $this->plugins_list );
	}

}
