<?php

namespace Tribe;
use Tribe\Common\Tests\Dummy_Plugin_Origin;
use Tribe__Template as Template;

include_once codecept_data_dir( 'classes/Dummy_Plugin_Origin.php' );

class TemplateTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * It should allow setting a number of values at the same time
	 *
	 * @test
	 */
	public function should_allow_setting_a_number_of_values_at_the_same_time() {
		$template = new Template();

		$template->set_values( [
			'twenty-three' => '23',
			'eighty-nine'  => 89,
			'an_array'     => [ 'key' => 2389 ],
			'an_object'    => (object) [ 'key' => 89 ],
			'a_null_value' => null,
		] );

		$this->assertEquals( '23', $template->get( 'twenty-three' ) );
		$this->assertEquals( 89, $template->get( 'eighty-nine' ) );
		$this->assertEquals( [ 'key' => 2389 ], $template->get( 'an_array' ) );
		$this->assertEquals( (object) [ 'key' => 89 ], $template->get( 'an_object' ) );
		$this->assertEquals( null, $template->get( 'a_null_value' ) );
	}

	/**
	 * It should allow setting contextual values without overriding the primary values
	 *
	 * @test
	 */
	public function should_allow_setting_contextual_values_without_overriding_the_primary_values() {
		$template      = new Template();
		$global_set    = [
			'twenty-three' => '23',
			'eighty-nine'  => 89,
		];
		$global_values = $global_set;

		$template->set_values( $global_set, false );

		$this->assertEquals( $global_values, $template->get_global_values() );
		$this->assertEquals( [], $template->get_local_values() );
		$this->assertEquals( $global_values, $template->get_values() );

		$local_set = [
			'eighty-nine' => 2389,
			'another_var' => 'another_value',
		];
		$template->set_values( $local_set );

		$this->assertEquals( $global_values, $template->get_global_values() );
		$this->assertEquals( $local_set, $template->get_local_values() );
		$this->assertEquals( array_merge( $global_values, $local_set ), $template->get_values() );
	}

	/**
	 * @test
	 */
	public function should_include_entry_points_on_template_html() {
		$plugin   = new Dummy_Plugin_Origin();
		$template = new Template();
		$template->set_template_origin( $plugin );

		add_action( 'tribe_template_entry_point:dummy/dummy-template:after_container_open', function () {
			echo '%%after_container_open%%';
		} );
		add_action( 'tribe_template_entry_point:dummy/dummy-template:before_container_close', function () {
			echo '%%before_container_close%%';
		} );

		$html = $template->template( 'dummy-template', [], false );

		$this->assertContains( '<div class="test">%%after_container_open%%', $html );
		$this->assertStringEndsWith( '%%before_container_close%%</div>', $html );
	}

	/**
	 * @test
	 */
	public function should_include_custom_entry_points_on_template_html() {
		$plugin   = new Dummy_Plugin_Origin();
		$template = new Template();
		$template->set_template_origin( $plugin );

		add_action( 'tribe_template_entry_point::custom_entry_point', function () {
			echo '%%custom_entry_point%%';
		} );

		$customer_entry_point_html = $template->do_entry_point( 'custom_entry_point', false );
		$last_tag_html             = '</div>';
		$html                      = $template->template( 'dummy-template', [], false );
		$html                      = \Tribe\Utils\Strings::replace_last( $last_tag_html, $last_tag_html . $customer_entry_point_html, $html );

		$this->assertContains( '</div>%%custom_entry_point%%', $html );
	}

	/**
	 * @test
	 */
	public function should_not_include_with_invalid_html() {
		$plugin   = new Dummy_Plugin_Origin();
		$template = new Template();
		$template->set_template_origin( $plugin );

		add_action( 'tribe_template_entry_point:dummy/dummy-invalid-template-01:after_container_open', function () {
			echo '%%after_container_open%%';
		} );
		add_action( 'tribe_template_entry_point:dummy/dummy-invalid-template-01:before_container_close', function () {
			echo '%%before_container_close%%';
		} );
		$html = $template->template( 'dummy-invalid-template-01', [], false );

		$this->assertNotContains( '%%after_container_open%%', $html );
		$this->assertStringEndsNotWith( '%%before_container_close%%', $html );
	}

	/**
	 * @test
	 */
	public function should_not_include_with_invalid_html_02() {
		$plugin   = new Dummy_Plugin_Origin();
		$template = new Template();
		$template->set_template_origin( $plugin );

		add_action( 'tribe_template_entry_point:dummy/dummy-invalid-template-02:after_container_open', function () {
			echo '%%after_container_open%%';
		} );
		add_action( 'tribe_template_entry_point:dummy/dummy-invalid-template-02:before_container_close', function () {
			echo '%%before_container_close%%';
		} );
		$html = $template->template( 'dummy-invalid-template-02', [], false );

		$this->assertNotContains( '%%after_container_open%%', $html );
		$this->assertStringEndsNotWith( '%%before_container_close%%', $html );
	}

	/**
	 * @test
	 */
	public function should_not_include_with_invalid_html_03() {
		$plugin   = new Dummy_Plugin_Origin();
		$template = new Template();
		$template->set_template_origin( $plugin );

		add_action( 'tribe_template_entry_point:dummy/dummy-invalid-template-03:after_container_open', function () {
			echo '%%after_container_open%%';
		} );
		add_action( 'tribe_template_entry_point:dummy/dummy-invalid-template-03:before_container_close', function () {
			echo '%%before_container_close%%';
		} );
		$html = $template->template( 'dummy-invalid-template-03', [], false );

		$this->assertNotContains( '%%after_container_open%%', $html );
		$this->assertStringEndsNotWith( '%%before_container_close%%', $html );
	}

	/**
	 * @test
	 */
	public function should_not_include_with_invalid_html_04() {
		$plugin   = new Dummy_Plugin_Origin();
		$template = new Template();
		$template->set_template_origin( $plugin );

		add_action( 'tribe_template_entry_point:dummy/dummy-invalid-template-04:after_container_open', function () {
			echo '%%after_container_open%%';
		} );
		add_action( 'tribe_template_entry_point:dummy/dummy-invalid-template-04:before_container_close', function () {
			echo '%%before_container_close%%';
		} );
		$html = $template->template( 'dummy-invalid-template-04', [], false );

		$this->assertNotContains( '%%after_container_open%%', $html );
		$this->assertStringEndsNotWith( '%%before_container_close%%', $html );
	}

	/**
	 * @test
	 */
	public function should_include_with_valid_html() {
		$plugin   = new Dummy_Plugin_Origin();
		$template = new Template();
		$template->set_template_origin( $plugin );

		add_action( 'tribe_template_entry_point:dummy/dummy-valid-template-01:after_container_open', function () {
			echo '%%after_container_open%%';
		} );
		add_action( 'tribe_template_entry_point:dummy/dummy-valid-template-01:before_container_close', function () {
			echo '%%before_container_close%%';
		} );
		$html = $template->template( 'dummy-valid-template-01', [], false );

		$this->assertContains( '<a href="https://tri.be" class="test" target="_blank" title="Test Link" data-link="automated-tests">%%after_container_open%%', $html );
		$this->assertStringEndsWith( '%%before_container_close%%</a>', $html );

	}

	/**
	 * @test
	 */
	public function should_include_with_valid_html_02() {
		$plugin   = new Dummy_Plugin_Origin();
		$template = new Template();
		$template->set_template_origin( $plugin );

		add_action( 'tribe_template_entry_point:dummy/dummy-valid-template-02:after_container_open', function () {
			echo '%%after_container_open%%';
		} );
		add_action( 'tribe_template_entry_point:dummy/dummy-valid-template-02:before_container_close', function () {
			echo '%%before_container_close%%';
		} );
		$html = $template->template( 'dummy-valid-template-02', [], false );

		$replaced_html = str_replace( array( "\n", "\r" ), '', $html );
		$this->assertContains( 'data-view-breakpoint-pointer="99ccf293-c1b0-41b2-a1c8-033776ac6f10">%%after_container_open%%', $replaced_html );
		$this->assertStringEndsWith( '%%before_container_close%%</div>', $html );
	}

	/**
	 * @test
	 */
	public function should_include_with_valid_html_03() {
		$plugin   = new Dummy_Plugin_Origin();
		$template = new Template();
		$template->set_template_origin( $plugin );

		add_action( 'tribe_template_entry_point:dummy/dummy-valid-template-03:after_container_open', function () {
			echo '%%after_container_open%%';
		} );
		add_action( 'tribe_template_entry_point:dummy/dummy-valid-template-03:before_container_close', function () {
			echo '%%before_container_close%%';
		} );
		$html = $template->template( 'dummy-valid-template-03', [], false );

		$replaced_html = str_replace( array( "\n", "\r" ), '', $html );
		$this->assertContains( '<div class="tribe-view tribe-view--base tribe-view--dummy">%%after_container_open%%', $replaced_html );
		$this->assertStringEndsWith( '%%before_container_close%%</div>', $html );
	}

	/**
	 * @test
	 */
	public function should_not_include_with_entry_points_disabled() {
		$plugin   = new Dummy_Plugin_Origin();
		$template = new Template();
		$template->set_template_origin( $plugin );

		add_action( 'tribe_template_entry_point_is_enabled', '__return_false' );

		add_action( 'tribe_template_entry_point:dummy/dummy-template:after_container_open', function () {
			echo '%%after_container_open%%';
		} );
		add_action( 'tribe_template_entry_point:dummy/dummy-template:before_container_close', function () {
			echo '%%before_container_close%%';
		} );

		$html = $template->template( 'dummy-template', [], false );

		$this->assertNotContains( '<div class="test">%%after_container_open%%', $html );
		$this->assertStringEndsNotWith( '%%before_container_close%%</div>', $html );
	}

	/**
	 * It should add common template path when looking for files
	 *
	 * @test
	 */
	public function should_add_common_template_path_when_looking_for_files() {
		$template = new class extends Template {
			protected $template_base_path = __DIR__ . '/test-plugin';
			protected $folder = [ 'src', 'views' ];
		};
		/*
		 * We're not creating real template files (beyond the point), but want to ensure common path is included among
		 * the paths to search for the template files: the assertion will happen during the filtering.
		 */
		$assert = function ( array $folders ) {
			$this->assertEquals( [ 'plugin', 'common' ], array_keys( $folders ),
				'There should be a plugin and a common folder.' );
			$this->assertGreaterThan( $folders['plugin']['priority'], $folders['common']['priority'],
				'Common folder should be looked up after the plugin folder.' );
			$this->assertEquals( \Tribe__Main::instance()->plugin_path . 'src/views', $folders['common']['path'] );
		};
		add_filter( 'tribe_template_path_list', $assert, PHP_INT_MAX );

		// What we look for is not really relevant: the assertions happens before.
		$template->get_template_file( [ 'foo', 'bar', 'component' ] );
	}

	/**
	 * It should not add common template path if common lookup is disabled
	 *
	 * @test
	 */
	public function should_not_add_common_template_path_if_common_lookup_is_disabled() {
		$template = new class extends Template {
			protected $template_base_path = __DIR__ . '/test-plugin';
			protected $folder = [ 'src', 'views' ];
			protected $common_lookup = false;
		};
		$assert = function ( array $folders ) {
			$this->assertEquals( [ 'plugin' ], array_keys( $folders ),
				'There should be a plugin folder only.' );
		};
		add_filter( 'tribe_template_path_list', $assert, PHP_INT_MAX );

		// What we look for is not really relevant: the assertions happens before.
		$template->get_template_file( [ 'foo', 'bar', 'component' ] );
	}

	/**
	 * It should allow setting aliases for the folder paths
	 *
	 * @test
	 */
	public function should_allow_setting_aliases_for_the_folder_paths_w_common_lookup() {
		$template = new class extends Template {
			protected $template_base_path = __DIR__ . '/test-plugin';
			protected $folder = [ 'src', 'views' ];
			protected $common_lookup = true;
		};
	}

	/**
	 * It should allow setting aliases for the folder paths w/o common lookup
	 *
	 * Here we simulate the case where a template extending class is looking up a versioned path (v4_2), but would like
	 * to fall back on the version root too (v4).
	 *
	 * @test
	 */
	public function should_allow_setting_aliases_for_the_folder_paths_w_o_common_lookup() {
		$template = new class extends Template {
			protected $template_base_path = __DIR__ . '/test-plugin';
			protected $folder = [ 'src', 'views', 'v4_2' ];
			protected $common_lookup = false;
			protected $aliases = [ 'v4_2' => 'v4' ];
		};

		$assert = function ( array $folders ) {
			$this->assertEquals( [ 'plugin', 'plugin_v4' ], array_keys( $folders ),
				'There should be two plugin folders.' );
			$this->assertEquals( __DIR__ . '/test-plugin/src/views/v4_2', $folders['plugin']['path'] );
			$this->assertEquals( __DIR__ . '/test-plugin/src/views/v4', $folders['plugin_v4']['path'] );
			$this->assertEquals(
				(int) $folders['plugin']['priority'] + 1,
				$folders['plugin_v4']['priority'],
				'Aliases should be loaded at original priority+1'
			);
		};
		add_filter( 'tribe_template_path_list', $assert, PHP_INT_MAX );

		// What we look for is not really relevant: the assertions happens before.
		$template->get_template_file( [ 'foo', 'bar', 'component' ] );
	}

	/**
	 * It should allow setting aliases w/ common lookup
	 *
	 * @test
	 */
	public function should_allow_setting_aliases_w_common_lookup() {
		$template = new class extends Template {
			protected $template_base_path = __DIR__ . '/test-plugin';
			protected $folder = [ 'src', 'views', 'v4_2' ];
			protected $common_lookup = true;
			protected $aliases = [ 'v4_2' => 'v4' ];
		};

		$assert = function ( array $folders ) {
			$this->assertEquals( [ 'plugin', 'common', 'plugin_v4', 'common_v4' ], array_keys( $folders ),
				'There should be two plugin and two common folders.' );
			$this->assertEquals( __DIR__ . '/test-plugin/src/views/v4_2', $folders['plugin']['path'] );
			$this->assertEquals( __DIR__ . '/test-plugin/src/views/v4', $folders['plugin_v4']['path'] );
			$this->assertEquals( \Tribe__Main::instance()->plugin_path . 'src/views/v4_2', $folders['common']['path'] );
			$this->assertEquals( \Tribe__Main::instance()->plugin_path . 'src/views/v4', $folders['common_v4']['path'] );
			$this->assertEquals(
				(int) $folders['common']['priority'] + 1,
				$folders['common_v4']['priority'],
				'Common aliases should be loaded at original priority+1'
			);
		};
		add_filter( 'tribe_template_path_list', $assert, PHP_INT_MAX );

		// What we look for is not really relevant: the assertions happens before.
		$template->get_template_file( [ 'foo', 'bar', 'component' ] );
	}

	/**
	 * It should allow using aliases to rewrite path fragments
	 *
	 * Here we simulate the instance where the new version of the templates (v3_1) changed to use `templates/v3_1` where
	 * the old version used `views/v3`.
	 * Furthermore, this test will check if the DIRECTORY_SEPARATOR normalization will work.
	 *
	 * @test
	 */
	public function should_allow_using_aliases_to_rewrite_path_fragments() {
		$template = new class extends Template {
			protected $template_base_path = __DIR__ . '/test-plugin';
			protected $folder = [ 'src', 'templates', 'v3_1' ];
			protected $common_lookup = true;
			// Note: the aliases use Windows DIRECTORY_SEPARATOR as the tests will likely run on *nix machines.
			protected $aliases = [ 'templates\v3_1' => 'views\v3' ];
		};

		$assert = function ( array $folders ) {
			$this->assertEquals( [ 'plugin', 'common', 'plugin_views\v3', 'common_views\v3' ], array_keys( $folders ),
				'There should be two plugin and two common folders.' );
			$this->assertEquals( __DIR__ . '/test-plugin/src/templates/v3_1', $folders['plugin']['path'] );
			$this->assertEquals( __DIR__ . '/test-plugin/src/views/v3', $folders['plugin_views\v3']['path'] );
			$this->assertEquals( \Tribe__Main::instance()->plugin_path . 'src/templates/v3_1',
				$folders['common']['path'] );
			$this->assertEquals( \Tribe__Main::instance()->plugin_path . 'src/views/v3',
				$folders['common_views\v3']['path'] );
		};
		add_filter( 'tribe_template_path_list', $assert, PHP_INT_MAX );

		// What we look for is not really relevant: the assertions happens before.
		$template->get_template_file( [ 'foo', 'bar', 'component' ] );
	}
}
