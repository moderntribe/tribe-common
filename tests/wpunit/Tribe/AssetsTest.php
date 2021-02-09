<?php
namespace Tribe;

use Tribe__Assets as Assets;
use Tribe__Main as Plugin;

class AssetsTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @test
	 *
	 * @since 4.12.13
	 */
	public function it_should_have_translations() {
		$locale = 'en';
		add_filter( 'pre_determine_locale', static function() use ( $locale ) {
			return $locale;
		} );

		$expected_msgid = 'Translations MSGID';
		$expected_msgstr = 'Translations MSGID';
		$domain = 'tribe-common';

		$plugin = Plugin::instance();
		$assets = new Assets;

		$asset_slug = 'test-script';

		$assets->register(
			$plugin,
			$asset_slug,
			codecept_data_dir( 'resources/test-script-1.js' ),
			[],
			null,
			[
				'translations' => [
					'domain' => $domain,
					'path'   => codecept_data_dir( 'lang/' ),
				]
			]
		);

		$assets->register_in_wp( [ $assets->get( $asset_slug ) ] );
		$assets->enqueue( $asset_slug );

		$translations_string = wp_scripts()->print_translations( $asset_slug, false );

		$this->assertContains( $expected_msgid, $translations_string );
		$this->assertContains( $expected_msgstr, $translations_string );
	}
}
