<?php
namespace Tribe\Service_Providers;
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
		tribe_singleton( 'tooltip.view', \Tribe\Tooltip\View::class );

		$this->hook();
	}

	/**
	 * Setup hooks for classes.
	 *
	 * @since 4.9.8
	 */
	private function hook() {
		add_action( 'tribe_common_loaded', [ $this, 'add_tooltip_assets' ] );
	}

	/**
	 * Register assets associated with tooltip
	 *
	 * @since 4.9.8
	 */
	public function add_tooltip_assets() {
		$main = \Tribe__Main::instance();
		tribe_asset(
			$main,
			'tribe-tooltip-css',
			'tooltip.css',
			[ 'tribe-common' ],
			[ 'wp_enqueue_scripts', 'admin_enqueue_scripts' ]
		);

		tribe_asset(
			$main,
			'tribe-tooltip-js',
			'tooltip.js',
			[ 'tribe-common' ],
			[ 'wp_enqueue_scripts', 'admin_enqueue_scripts' ]
		);
	}
}
