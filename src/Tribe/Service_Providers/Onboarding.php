<?php
namespace Tribe\Service_Providers;

/**
 * Class Onboarding
 *
 * @since TBD
 *
 * Handles the registration and creation of our async process handlers.
 */
class Onboarding extends \tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 *
	 * @since TBD
	 */
	public function register() {
		tribe_singleton( 'onboarding', '\Tribe\Onboarding\Main' );

		$this->hooks();
	}

	/**
	 * Set up hooks for classes.
	 *
	 * @since TBD
	 */
	private function hooks() {
		add_action( 'tribe_common_loaded', [ $this, 'register_assets' ] );

		add_action( 'admin_enqueue_scripts', tribe_callback( 'onboarding', 'localize_tour' ) );
		add_action( 'admin_enqueue_scripts', tribe_callback( 'onboarding', 'localize_hints' ) );
	}

	/**
	 * Register assets associated with onboarding.
	 *
	 * @since TBD
	 */
	public function register_assets() {
		$main = \Tribe__Main::instance();

		tribe_asset(
			$main,
			'intro-js',
			'node_modules/intro.js/intro.js',
			[],
			[ 'admin_enqueue_scripts' ],
			[
				'groups'       => 'tribe-onboarding',
				'conditionals' => [ $this, 'should_enqueue_assets' ],
			]
		);

		tribe_asset(
			$main,
			'intro-styles',
			'node_modules/intro.js/introjs.css',
			[],
			[ 'admin_enqueue_scripts' ],
			[
				'groups'       => 'tribe-onboarding',
				'conditionals' => [ $this, 'should_enqueue_assets' ],
			]
		);

		tribe_asset(
			$main,
			'tribe-onboarding-styles',
			'onboarding.css',
			[ 'intro-styles' ],
			[ 'admin_enqueue_scripts' ],
			[
				'groups'       => 'tribe-onboarding',
				'conditionals' => [ $this, 'should_enqueue_assets' ],
			]
		);

		tribe_asset(
			$main,
			'tribe-onboarding-js',
			'onboarding.js',
			[
				'tribe-common',
				'intro-js'
			],
			[ 'admin_enqueue_scripts' ],
			[
				'groups'       => 'tribe-onboarding',
				'in_footer'    => false,
				'localize'     => [
					'name' => 'TribeOnboarding',
					'data' => [
						'hintButtonLabel' => __( 'Got it', 'tribe-common' ),
					],
				],
				'conditionals' => [ $this, 'should_enqueue_assets' ],
			]
		);
	}

	public function should_enqueue_assets() {
		return $this->is_enabled();
	}

	/**
	 * Check if the onboarding is enabled or not.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public function is_enabled() {
		/**
		 * Filter to disable tribe onboarding
		 *
		 * @since TBD
		 *
		 * @param bool $disabled If we want to disable the onboarding.
		 */
		$is_disabled = (bool) apply_filters( 'tribe_onboarding_disable', false );

		return is_admin() && ! $is_disabled;
	}
}
