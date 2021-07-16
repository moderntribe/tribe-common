<?php

namespace Tribe\Utils;

use Codeception\TestCase\WPTestCase;
use Tribe\Utils\Theme_Compatibility;

class Theme_CompatibilityTest extends WPTestCase {

	public function themes_supported_data_set() {
		return [
			'avada_is_supported' => [ 'avada' ],
			'divi_is_supported' => [ 'divi' ],
			'enfold_is_supported' => [ 'enfold' ],
			'genesis_is_supported' => [ 'genesis' ],
			'twentyseventeen_is_supported' => [ 'twentyseventeen' ],
			'twentynineteen_is_supported' => [ 'twentynineteen' ],
			'twentytwenty_is_supported' => [ 'twentytwenty' ],
			'twentytwentyone_is_supported' => [ 'twentytwentyone' ],
		];
	}

	/**
	 * @test
	 */
	public function it_should_detect_the_current_theme() {
		$theme = Theme_Compatibility::get_current_theme();

		$this->assertEquals( get_stylesheet(), $theme );
	}


	/**
	 * @test
	 */
	public function it_should_return_the_current_theme_object() {
		$theme = Theme_Compatibility::get_current_theme( true );

		$this->assertInstanceOf( 'WP_Theme', $theme );
	}

	/**
	 * @test
	 */
	public function it_should_correctly_identify_an_active_theme() {
		$theme  = get_stylesheet();
		$active = Theme_Compatibility::is_active_theme( $theme );

		$this->assertTrue( $active );
	}

	/**
	 * @test
	 */
	public function it_should_correctly_identify_an_inactive_theme() {
		$theme  = 'my-awesome-theme';
		$active = Theme_Compatibility::is_active_theme( $theme );

		$this->assertFalse( $active );
	}

	/**
	 * @test
	 */
	public function it_should_correctly_get_a_standalone_theme() {
		$themes = Theme_Compatibility::get_active_themes();

		$this->assertEquals( get_stylesheet(), $themes['parent'] );
	}

	/**
	 * @test
	 * @dataProvider themes_supported_data_set
	 */
	public function should_need_compatibility_for_supported_themes( $input ) {
		$is_compatibility_required = Theme_Compatibility::is_compatibility_required( $input );

		$this->assertTrue( $is_compatibility_required, true );
	}

	/**
	 * @test
	 */
	public function should_not_need_compatibility_for_non_supported_themes() {
		$is_compatibility_required = Theme_Compatibility::is_compatibility_required( 'invalid-value-for-theme' );

		$this->assertFalse( $is_compatibility_required );
	}
}
