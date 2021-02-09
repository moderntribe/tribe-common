<?php

namespace Tribe;

use Tribe__Cache as Cache;

class CacheTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * It should be instantiatable
	 *
	 * @test
	 */
	public function be_instantiatable() {
		$this->assertInstanceOf( Cache::class, $this->make_instance() );
	}

	/**
	 * @return Cache
	 */
	protected function make_instance() {
		return new Cache();
	}

	/**
	 * It should allow storing value using ArrayAccess API
	 *
	 * @test
	 */
	public function it_should_allow_storing_value_using_array_access_api() {
		$cache = $this->make_instance();

		$this->assertFalse( isset( $cache['foo'] ) );

		$cache['foo'] = 'bar';

		$this->assertTrue( isset( $cache['foo'] ) );
		$this->assertEquals( 'bar', $cache['foo'] );
	}

	/**
	 * It should allow changing value using ArrayAccess API
	 *
	 * @test
	 */
	public function it_should_allow_changing_value_using_array_access_api() {
		$cache = $this->make_instance();

		$this->assertFalse( isset( $cache['foo'] ) );

		$cache['foo'] = 'bar';

		$this->assertTrue( isset( $cache['foo'] ) );
		$this->assertEquals( 'bar', $cache['foo'] );

		// Change the value.
		$cache['foo'] = 'rob';

		$this->assertTrue( isset( $cache['foo'] ) );
		$this->assertEquals( 'rob', $cache['foo'] );
	}

	/**
	 * It should allow removing value using ArrayAccess API
	 *
	 * @test
	 */
	public function it_should_allow_removing_value_using_array_access_api() {
		$cache = $this->make_instance();

		$this->assertFalse( isset( $cache['foo'] ) );

		$cache['foo'] = 'bar';

		unset( $cache['foo'] );

		$this->assertFalse( isset( $cache['foo'] ) );
		$this->assertEquals( null, $cache['foo'] );
	}

	/**
	 * It should correctly fabricate keys
	 *
	 * @test
	 */
	public function it_should_correctly_fabricate_keys() {
		$components_1 = [ __FILE__, [ 23, 89 ], [ 'foo' => 'bar', 'bar' => 'baz' ] ];
		$components_2 = [ __FILE__, [ 23, 89 ], [ 'bar' => 'baz', 'foo' => 'bar' ] ];

		$cache = $this->make_instance();

		$this->assertEquals( $cache->make_key( $components_1 ), $cache->make_key( $components_2 ) );
		$this->assertEquals( $cache->make_key( $components_1, 'pre' ), $cache->make_key( $components_2, 'pre' ) );
		$this->assertNotEquals( $cache->make_key( $components_1, 'foo' ), $cache->make_key( $components_2, 'pre' ) );
		$this->assertNotEquals( $cache->make_key( $components_1, '', false ), $cache->make_key( $components_2, '', false ) );
	}

	/**
	 * It should correctly handle long keys
	 *
	 * @test
	 */
	public function it_should_correctly_handle_long_keys() {
		$components = [ __FILE__, [ 23, 89 ], [ 'foo' => 'bar', 'bar' => 'baz' ] ];

		$cache = $this->make_instance();

		$long_prefix = 'some very long prefix that should trigger some kind of minification on the key creation or so I hope';
		$key = $cache->make_key( $components, $long_prefix );
		$cache[ $key ] = 'bar';

		$this->assertTrue( isset( $cache[ $key ] ) );
		$this->assertEquals( 'bar', $cache[ $key ] );
	}

	/**
	 * It should correctly generate key for numeric array components
	 *
	 * @test
	 */
	public function it_should_correctly_generate_key_for_numeric_array_components() {
		$components_1 = [ __FILE__, [ 23, 89 ], [ 1 => 'bar', 23 => 'baz', 89 => 'bar' ] ];
		$components_2 = [ __FILE__, [ 23, 89 ], [ 89 => 'bar', 23 => 'baz', 1 => 'bar' ] ];

		$cache = $this->make_instance();

		$this->assertEquals( $cache->make_key( $components_1 ), $cache->make_key( $components_2 ) );
		$this->assertEquals( $cache->make_key( $components_1, 'pre' ), $cache->make_key( $components_2, 'pre' ) );
		$this->assertNotEquals( $cache->make_key( $components_1, 'foo' ), $cache->make_key( $components_2, 'pre' ) );
		$this->assertNotEquals( $cache->make_key( $components_1, '', false ), $cache->make_key( $components_2, '', false ) );
	}

	/**
	 * @test
	 */
	public function it_should_not_try_to_delete_transients_right_away() {
		$cache = $this->make_instance();

		$cache->set_transient( 'foo', 'bar', -2, 'foo_bar' );

		$passed = false;

		add_filter( 'tribe_cache_delete_expired_transients_sql', static function( $sql ) use ( $passed ) {
			$passed = true;
			return $sql;
		} );

		$cache->set_last_occurrence( 'foo_bar' );

		$this->assertFalse( $passed );
	}

	/**
	 * It should not clean expired transients more than once per request
	 *
	 * In this test method we call the clean process as the `shutdown` process would after a Cache user flagged the
	 * transients as in need of being deleted by means of a `Tribe__Cache::flag_required_delete_transients` method call.
	 *
	 * @test
	 */
	public function should_not_clean_expired_transients_more_than_once_per_request() {
		// Reset the transient clearing state.
		tribe_set_var( 'should_delete_expired_transients', false );
		tribe_set_var( 'has_deleted_expired_transients', false );
		/** @var Cache $provided_cache */
		$provided_cache     = tribe( 'cache' );
		$cache_instance_one = new \Tribe__Cache();
		$cache_instance_two = new \Tribe__Cache();

		$passed = 0;
		add_filter( 'tribe_cache_delete_expired_transients_sql', static function () use ( & $passed ) {
			$passed ++;

			// Return  a real query to make sure the "cancellation" will go through.
			return 'SELECT 1';
		} );

		$provided_cache->flag_required_delete_transients( true );

		$provided_cache->maybe_delete_expired_transients();
		$provided_cache->delete_expired_transients();
		$provided_cache->maybe_delete_expired_transients();
		$cache_instance_one->maybe_delete_expired_transients();
		$cache_instance_one->delete_expired_transients();
		$cache_instance_one->maybe_delete_expired_transients();
		$cache_instance_two->maybe_delete_expired_transients();
		$cache_instance_two->delete_expired_transients();
		$cache_instance_two->maybe_delete_expired_transients();

		$this->assertEquals( 1, $passed );
	}

	/**
	 * It should not clean transients more than once per request when triggered before shutdown
	 *
	 * In this method we test the transient deletion when the deletion has been triggered directly, in place of being
	 * booked at shutdown using a `Tribe__Cache::flag_required_delete_transients` method call.
	 *
	 * @test
	 */
	public function should_not_clean_transients_more_than_once_per_request_when_triggered_before_shutdown() {
		// Reset the transient clearing state.
		tribe_set_var( 'should_delete_expired_transients', false );
		tribe_set_var( 'has_deleted_expired_transients', false );
		/** @var Cache $provided_cache */
		$provided_cache     = tribe( 'cache' );
		$cache_instance_one = new \Tribe__Cache();
		$cache_instance_two = new \Tribe__Cache();

		$passed = 0;
		add_filter( 'tribe_cache_delete_expired_transients_sql', static function () use ( & $passed ) {
			$passed ++;

			// Return  a real query to make sure the "cancellation" will go through.
			return 'SELECT 1';
		} );

		$provided_cache->delete_expired_transients();
		$provided_cache->delete_expired_transients();
		$provided_cache->delete_expired_transients();
		$cache_instance_one->delete_expired_transients();
		$cache_instance_one->delete_expired_transients();
		$cache_instance_one->delete_expired_transients();
		$cache_instance_two->delete_expired_transients();
		$cache_instance_two->delete_expired_transients();
		$cache_instance_two->delete_expired_transients();

		$this->assertEquals( 1, $passed );
	}

	/**
	 * It should not cache overly large strings in transients
	 *
	 * @test
	 */
	public function should_not_cache_overly_large_strings_in_transients() {
		$max_allow_packet = 200;
		// Filter the feature detection (tested elsewhere).
		add_filter( 'tribe_max_allowed_packet_size', static function () use ( $max_allow_packet ) {
			return $max_allow_packet;
		} );
		// Simulate a case where external object caching is NOT in use.
		$GLOBALS['_wp_using_ext_object_cache'] = false;
		$small_size_value                      = str_repeat( '#', $max_allow_packet * .1 );
		$medium_size_value                     = str_repeat( '#', $max_allow_packet * .5 );
		$large_size_value                      = str_repeat( '#', $max_allow_packet * .9 );
		$too_large_size_value                  = str_repeat( '#', $max_allow_packet * 1.1 );

		/** @var \Tribe__Cache $cache */
		$cache = tribe( 'cache' );

		$this->assertTrue( $cache->set_transient( 'test', $small_size_value ) );
		$this->assertFalse( $cache->data_size_over_packet_size( $small_size_value ) );
		$this->assertTrue( $cache->set_transient( 'test', $medium_size_value ) );
		$this->assertFalse( $cache->data_size_over_packet_size( $medium_size_value ) );
		$this->assertTrue( $cache->set_transient( 'test', $large_size_value ) );
		$this->assertFalse( $cache->data_size_over_packet_size( $large_size_value ) );
		$this->assertFalse( $cache->set_transient( 'test', $too_large_size_value ) );
		$this->assertTrue( $cache->data_size_over_packet_size( $too_large_size_value ) );
	}

	/**
	 * It should not cache overly large values in database
	 *
	 * @test
	 */
	public function should_not_cache_overly_large_values_in_database() {
		$max_allow_packet = 200;
		// Filter the feature detection (tested elsewhere).
		add_filter( 'tribe_max_allowed_packet_size', static function () use ( $max_allow_packet ) {
			return $max_allow_packet;
		} );
		// Build an object whose serialized size is known before-hand.
		$build_object_to_size = function ( int $size ) {
			$template_size = 33;
			$template      = 'O:8:"stdClass":1:{s:1:"0";s:{{ size }}:"{{ v }}";}';
			$v_size        = $size - $template_size - ( strlen( $size - $template_size ) );
			$t             = [
				'{{ size }}' => $v_size,
				'{{ v }}'    => str_repeat( '#', $v_size ),
			];

			$serialized = str_replace( array_keys( $t ), $t, $template );

			$this->assertEquals( $size, strlen( $serialized ) );

			return unserialize( $serialized );
		};
		// Simulate a case where external object caching is NOT in use.
		$GLOBALS['_wp_using_ext_object_cache'] = false;
		$medium_size_value                     = $build_object_to_size( $max_allow_packet * .5 );
		$large_size_value                      = $build_object_to_size( $max_allow_packet * .9 );
		$too_large_size_value                  = $build_object_to_size( $max_allow_packet * 1.1 );

		/** @var \Tribe__Cache $cache */
		$cache = tribe( 'cache' );

		$this->assertTrue( $cache->set_transient( 'test', $medium_size_value ) );
		$this->assertFalse( $cache->data_size_over_packet_size( $medium_size_value ) );
		$this->assertTrue( $cache->set_transient( 'test', $large_size_value ) );
		$this->assertFalse( $cache->data_size_over_packet_size( $large_size_value ) );
		$this->assertFalse( $cache->set_transient( 'test', $too_large_size_value ) );
		$this->assertTrue( $cache->data_size_over_packet_size( $too_large_size_value ) );
	}

	/**
	 * It should not prevent caching of large values when using external cache
	 *
	 * @test
	 */
	public function should_not_prevent_caching_of_large_values_when_using_external_cache() {
		$max_allow_packet = 200;
		// Filter the feature detection (tested elsewhere).
		add_filter( 'tribe_max_allowed_packet_size', static function () use ( $max_allow_packet ) {
			return $max_allow_packet;
		} );
		// Simulate a case where external object caching is NOT in use.
		$GLOBALS['_wp_using_ext_object_cache'] = true;
		$large_size_value                      = str_repeat( '#', $max_allow_packet * .9 );
		$too_large_size_value                  = str_repeat( '#', $max_allow_packet * 1.1 );

		/** @var \Tribe__Cache $cache */
		$cache = tribe( 'cache' );

		$this->assertTrue( $cache->set_transient( 'test', $large_size_value ) );
		$this->assertFalse( $cache->data_size_over_packet_size( $large_size_value) );
		$this->assertTrue( $cache->set_transient( 'test', $too_large_size_value ) );
		$this->assertFalse( $cache->data_size_over_packet_size( $too_large_size_value ) );
	}
}
