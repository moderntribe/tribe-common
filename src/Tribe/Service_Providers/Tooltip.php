<?php
namespace Tribe\Service_Providers;

use \Tribe\Tooltip\View;
use \Tribe__Main as Common;

/**
 * Class Tribe__Service_Providers__Tooltip
 *
 * @since 4.9.8
 *
 * Handles the registration and creation of our async process handlers.
 */
class Tooltip extends \tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 *
	 * @since 4.9.8
	 */
	public function register() {
		tribe_singleton( 'tooltip.view', View::class );

		$this->register_assets();
	}

	/**
	 * Registers the assets for the Tooltip class.
	 *
	 * @since TBD
	 */
	public function register_assets() {
		$main = Common::instance();

		tribe_asset(
			$main,
			'tribe-tooltip',
			'tooltip.css',
			[ 'tribe-common-skeleton-style' ],
			[],
			[ 'groups' => 'tribe-tooltip' ]
		);

		tribe_asset(
			$main,
			'tribe-tooltip-js',
			'tooltip.js',
			[ 'jquery', 'tribe-common' ],
			[],
			[ 'groups' => 'tribe-tooltip' ]
		);
	}
}
