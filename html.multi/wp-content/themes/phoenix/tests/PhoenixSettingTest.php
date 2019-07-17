<?php
require_once WP_CONTENT_DIR . '/themes/phoenix/lib/phoenix_settings.php';

class PhoenixSettingsTest extends WP_UnitTestCase {
	function setUp() {
		parent::setUp();
	}

	function tearDown() {
		parent::tearDown();
	}

	function test_phoenix_setup() {
		global $_wp_theme_features;

		$expected = array(
			'woocommerce'          => true,
			'editor-style'         => true,
			'automatic-feed-links' => true,
			'post-thumbnails'      => true,
			'post-formats'         => array( array( 'aside', 'image', 'video', 'quote', 'link' ) ),
			'infinite-scroll'      => array( array( 'content', 'page' ) ),
			'widgets'              => true,
		);

		$actual = $_wp_theme_features;

		$result = array_diff( $expected, $actual );

		$this->assertEquals( 0, count( $result ) );
	}

	function test_list_plugins_to_activate() {
		$expected = array(
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
			'pronto-jetpack-overrides/pronto-jetpack-overrides.php',
			'pronto-locations-manager/pronto-locations-manager.php',
		);

		$actual = plugins_to_active();

		$this->assertEquals( $actual, $expected );
	}

	function test_activate_plugins() {
		run_activate_plugin();

		$this->assertTrue( is_plugin_active( 'pronto-breadcrumb-navigation/pronto-breadcrumb-navigation.php' ) );
		$this->assertTrue( is_plugin_active( 'pronto-header-manager/header-custom-types.php' ) );
		$this->assertTrue( is_plugin_active( 'pronto-footer-manager/footer-custom-types.php' ) );
		$this->assertTrue( is_plugin_active( 'pronto-page-option-settings/pronto_page_option_setting.php' ) );
		$this->assertTrue( is_plugin_active( 'pronto-shortcode/pronto-shortcode.php' ) );
		$this->assertTrue( is_plugin_active( 'pronto-shortcodes-ui/pronto-shortcodes-ui.php' ) );
		$this->assertTrue( is_plugin_active( 'pronto-sidebar-navigation/pronto-sidebar-navigation.php' ) );
		$this->assertTrue( is_plugin_active( 'pronto-site-option-backup/pronto-site-option-backup.php' ) );
		$this->assertTrue( is_plugin_active( 'pronto-company-information/pronto-company-information.php' ) );
		$this->assertTrue( is_plugin_active( 'add-descendants-as-submenu-items/add-descendants-as-submenu-items.php' ) );
		$this->assertTrue( is_plugin_active( 'simple-page-ordering/simple-page-ordering.php' ) );
		$this->assertTrue( is_plugin_active( 'wooslider/wooslider.php' ) );
		$this->assertTrue( is_plugin_active( 'woosidebars/woosidebars.php' ) );
		$this->assertTrue( is_plugin_active( 'gravityforms/gravityforms.php' ) );
		$this->assertTrue( is_plugin_active( 'jetpack/jetpack.php' ) );
		$this->assertTrue( is_plugin_active( 'pronto-jetpack-overrides/pronto-jetpack-overrides.php' ) );
	}

	function test_add_color_palette_var() {
		$public_query_vars = array();

		$expected = 'color-palette';
		$actual   = color_palette_var( $public_query_vars );

		$this->assertEquals( 1, count( $actual ) );
		$this->assertEquals( $expected, $actual[0] );
	}

	function test_not_enqueue_theme_customizer_scripts() {
		$expected  = "<script type='text/javascript' src='http://example.org/wp-includes/js/customize-base.min.js?ver=3.6'></script>\n";
		$expected .= "<script type='text/javascript' src='http://example.org/wp-includes/js/customize-preview.min.js?ver=3.6'></script>";

		$actual = get_echo( 'wp_print_scripts' );

		$this->assertNotContains( $expected, $actual );
	}

	function test_enqueue_theme_customizer_scripts() {
		theme_customizer_live_preview();

		$expected  = "<script type='text/javascript' src='http://example.org/wp-includes/js/customize-base.min.js?ver=3.8'></script>\n";
		$expected .= "<script type='text/javascript' src='http://example.org/wp-includes/js/customize-preview.min.js?ver=3.8'></script>";

		$actual = get_echo( 'wp_print_scripts' );

		$this->assertContains( $expected, $actual );
	}

	function test_not_add_css_to_header() {
		remove_action( 'wp_head', 'add_css_to_header', 101 );

		$expected  = '<link rel="stylesheet" id="main-css" href="http://example.org/wp-content/themes/phoenix/css/main.css" type="text/css" media="all" />';
		$expected .= "\n";
		$expected .= '<link rel="stylesheet" id="style-css" href="http://example.org/wp-content/themes/style/style.css" type="text/css" media="all" />';
		$expected .= "\n";
		$expected .= '<link rel="stylesheet" id="color-palette" href="http://example.org/?color-palette=css" type="text/css" media="all" />';

		$actual = get_echo( 'wp_head' );

		$this->assertNotContains( $expected, $actual );
	}

	function test_add_css_to_header() {
		add_action( 'wp_head', 'add_css_to_header', 101 );

		$expected_main_css      = 'main.css';
		$expected_style_css     = 'style.css';
		$expected_color_palette = 'color-palette=css';

		$actual = get_echo( 'wp_head' );

		$this->assertContains( $expected_main_css, $actual );
		$this->assertContains( $expected_style_css, $actual );
		$this->assertContains( $expected_color_palette, $actual );
	}

	function test_remove_margin_bottom_wooslider_if_wooslider_plugin_is_activated() {
		activate_plugin( 'wooslider/wooslider.php' );
		$expected  = 'var wooslider_holder = jQuery(slider).find("li.slide"); if(0 !== wooslider_holder.length){ var wooslides = ([]).concat(wooslider_holder.splice(0,2), wooslider_holder.splice(-2,2), jQuery.makeArray(wooslider_holder)); jQuery.each(wooslides, function(i,el){ var content = jQuery(this).attr("data-wooslidercontent"); if(typeof content == "undefined" || false == content) return; jQuery(this).append(content).removeAttr("data-wooslidercontent"); }); } jQuery(slider).fitVids(); var maxHeight = 0; jQuery(slider).find(".wooslider-control-nav li").each(function(i,el) { maxHeight = maxHeight > jQuery(this).height() ? maxHeight : jQuery(this).height(); }); jQuery(slider).css("margin-bottom", "0" + "px");';

		$start = '';
		$actual = phoenix_wooslider_override( $start );

		$this->assertEquals( $expected, $actual );
	}

	function test_not_remove_margin_bottom_wooslider_if_wooslider_plugin_is_deactivated() {
		$expected  = 'var wooslider_holder = jQuery(slider).find("li.slide"); if(0 !== wooslider_holder.length){ var wooslides = ([]).concat(wooslider_holder.splice(0,2), wooslider_holder.splice(-2,2), jQuery.makeArray(wooslider_holder)); jQuery.each(wooslides, function(i,el){ var content = jQuery(this).attr("data-wooslidercontent"); if(typeof content == "undefined" || false == content) return; jQuery(this).append(content).removeAttr("data-wooslidercontent"); }); } jQuery(slider).fitVids(); var maxHeight = 0; jQuery(slider).find(".wooslider-control-nav li").each(function(i,el) { maxHeight = maxHeight > jQuery(this).height() ? maxHeight : jQuery(this).height(); }); jQuery(slider).css("margin-bottom", "0" + "px");';

		$start = '';
		$actual = phoenix_wooslider_override( $start );

		$this->assertNotEquals( $expected, $actual );
	}

	function test_hiding_gutenburg_should_be_added_to_hook() {
		global $wp_filter;
		$this->assertTrue( array_key_exists(
			'__return_false',
			$wp_filter['use_block_editor_for_post'][10]
		) );
	}
}

?>
