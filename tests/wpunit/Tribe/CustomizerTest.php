<?php

namespace Tribe;

use Tribe__Customizer as Customizer;

class CustomizerTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * It should allow getting Customizer styles
	 *
	 * @test
	 */
	public function should_allow_getting_customizer_styles() {
		add_action( 'tribe_events_views_v2_is_enabled', '__return_true' );

		// Unset the global Customizer object, if any, to ensure we're running out of Customizer context.
		unset( $GLOBALS['wp_customizer'] );

		// Inject a template as TEC or PRO would do.
		$input_template = '.tribe-common .tribe-common-form-control-toggle__input:checked {
					background-color: <%= global_elements.accent_color %>;
				}

				.tribe-common .tribe-common-cta--alt,
				.tribe-common .tribe-common-cta--thin-alt:hover {
					border-bottom-color: <%= global_elements.accent_color %>;
				}';

		add_filter( 'tribe_customizer_css_template', static function () use ( $input_template ) {
			return $input_template;
		}, PHP_INT_MAX );

		// Emulate some Customizer sections.
		add_filter( 'pre_option_tribe_customizer', static function () {
			return [
				'global_elements' =>
					[
						'link_color'   => '#8224e3',
						'accent_color' => '#eeee22',
					],
			];
		} );

		$printed    = tribe( 'customizer' )->get_styles_scripts();
		$expected = '<script type="text/css" id="tmpl-tribe_customizer_css">.tribe-common .tribe-common-form-control-toggle__input:checked {
					background-color: <%= global_elements.accent_color %>;
				}

				.tribe-common .tribe-common-cta--alt,
				.tribe-common .tribe-common-cta--thin-alt:hover {
					border-bottom-color: <%= global_elements.accent_color %>;
				}</script><style type="text/css" id="tribe_customizer_css">.tribe-common .tribe-common-form-control-toggle__input:checked {
					background-color: #eeee22;
				}

				.tribe-common .tribe-common-cta--alt,
				.tribe-common .tribe-common-cta--thin-alt:hover {
					border-bottom-color: #eeee22;
				}</style>';

		$this->assertEquals( $expected, $printed );
	}
}
