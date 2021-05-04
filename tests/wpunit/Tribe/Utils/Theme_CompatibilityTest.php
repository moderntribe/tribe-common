<?php

namespace Tribe\Utils;

use Codeception\TestCase\WPTestCase;
use Tribe\Utils\Theme_Compatibility;

class Theme_CompatibilityTest extends WPTestCase {

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
}