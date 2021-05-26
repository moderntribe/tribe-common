<?php

namespace Tribe\Tests\Traits;

trait With_Uopz {
	private $uopz_set_returns = [];

	/**
	 * @after
	 */
	public function unset_uopz_returns() {
		if ( function_exists( 'uopz_set_return' ) ) {
			foreach ( $this->uopz_set_returns as $f ) {
				uopz_unset_return( $f );
			}
		}
	}

	private function set_fn_return( $fn, $value, $execute = false ) {
		if ( ! function_exists( 'uopz_set_return' ) ) {
			$this->markTestSkipped( 'uopz extension is not installed' );
		}
		uopz_set_return( $fn, $value, $execute );
		$this->uopz_set_returns[] = $fn;
	}
}
