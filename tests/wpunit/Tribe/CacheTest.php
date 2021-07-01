<?php

namespace Tribe;

use Tribe\Tests\Traits\With_Uopz;
use Tribe__Cache as Cache;

class CacheTest extends \Codeception\TestCase\WPTestCase {
	use With_Uopz;

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
	 * It should allow setting many different values using ArrayAccess API with long cache keys.
	 *
	 * @test
	 */
	public function it_should_allow_setting_many_different_values_using_array_access_api_with_long_cache_keys() {
		$cache = $this->make_instance();

		$expected = [
			'foo1'  => '',
			'foo2'  => '0',
			'foo3'  => '-1',
			'foo4'  => '100',
			'foo5'  => 0,
			'foo6'  => - 1,
			'foo7'  => 100,
			'foo8'  => [],
			'foo9'  => 'false',
			'foo10' => 'null',
			'foo11' => false,
			'foo12' => null,
		];

		foreach ( $expected as $key => $value ) {
			// Attempt to add a longer cache key to trigger the md5() cache key logic.
			$key .= __METHOD__ . '-' . $key;

			$cache[ $key ] = $value;
		}

		foreach ( $expected as $key => $value ) {
			// Attempt to add a longer cache key to trigger the md5() cache key logic.
			$key .= __METHOD__ . '-' . $key;

			// Any value set will always come back as 'set' because it exists in the non-persistent keys list.
			$this->assertTrue( isset( $cache[ $key ] ) );

			// The value will always match the type and value what we set.
			$this->assertSame( $value, $cache[ $key ] );
		}
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

	/**
	 * It should allow storing too large transients in chunks
	 *
	 * @test
	 */
	public function should_allow_storing_too_large_transients_in_chunks() {
		// Set a size for the MySQL max_allowed_packet_size in bytes.
		add_filter( 'tribe_max_allowed_packet_size', static function () {
			return 100;
		} );
		// Create a value that, in string format, is 4+ times the max allowed packet size.
		$value               = (object) [ 'value' => str_repeat( 'test', 100 ) ];
		$set_transient_calls = [];
		$this->set_fn_return( 'wp_using_ext_object_cache', false );
		$this->set_fn_return( 'set_transient', static function ( $name, $value ) use ( &$set_transient_calls ) {
			$set_transient_calls[$name] = $value;

			return true;
		}, true );

		$cache = $this->make_instance();

		$set = $cache->set_chunkable_transient( '__test__', $value, DAY_IN_SECONDS, [ 'save_post' ] );

		$this->assertTrue( $set );
		$this->assertCount( 5, array_filter( $set_transient_calls, static function ( $key ) {
			return strpos( $key, '__test__' ) === 0;
		}, ARRAY_FILTER_USE_KEY ) );
		$this->assertSame( serialize( $value ), implode( '', $set_transient_calls ) );
	}

	/**
	 * It should redirect chunkable not too large transients to normal transients
	 *
	 * @test
	 */
	public function should_redirect_chunkable_not_too_large_transients_to_normal_transients() {
		// Set a size for the MySQL max_allowed_packet_size in bytes.
		add_filter( 'tribe_max_allowed_packet_size', static function () {
			return 100;
		} );
		// Create a value that, in string format, is below the max allowed packet size.
		$value               = (object) [ 'value' => 'test' ];
		$set_transient_calls = [];
		$this->set_fn_return( 'wp_using_ext_object_cache', false );
		$this->set_fn_return( 'set_transient', static function ( $name ) use ( &$set_transient_calls ) {
			$set_transient_calls[] = $name;

			return true;
		}, true );

		$cache = $this->make_instance();

		$set = $cache->set_chunkable_transient( '__test__', $value, DAY_IN_SECONDS, [ 'save_post' ] );

		$this->assertTrue( $set );
		$this->assertCount( 1, array_filter( $set_transient_calls, static function ( $key ) {
			return strpos( $key, '__test__' ) === 0;
		} ) );
	}

	/**
	 * It should delete inserted transients when one chunk insertion fails
	 *
	 * @test
	 */
	public function should_delete_inserted_transients_when_one_chunk_insertion_fails() {
		// Set a size for the MySQL max_allowed_packet_size in bytes.
		add_filter( 'tribe_max_allowed_packet_size', static function () {
			return 100;
		} );
		// Create a value that, in string format, is below the max allowed packet size.
		$value               = (object) [ 'value' => str_repeat( 'test', 100 ) ];
		$set_transient_calls = [];
		$delete_transient_calls = [];
		$this->set_fn_return( 'wp_using_ext_object_cache', false );
		$this->set_fn_return( 'set_transient', static function ( $name ) use ( &$set_transient_calls ) {
			$set_transient_calls[] = $name;

			// On the insertion of the 3rd one return `false`.
			return count( $set_transient_calls ) <= 2;
		}, true );
		$this->set_fn_return( 'delete_transient', static function ( $name ) use ( &$delete_transient_calls ) {
			$delete_transient_calls[] = $name;
			return true;
		}, true );

		$cache = $this->make_instance();

		$set = $cache->set_chunkable_transient( '__test__', $value, DAY_IN_SECONDS, [ 'save_post' ] );

		$this->assertFalse( $set );
		$this->assertCount( 3, array_filter( $set_transient_calls, static function ( $key ) {
			return strpos( $key, '__test__' ) === 0;
		} ) );
		$this->assertCount( 2, array_filter( $delete_transient_calls, static function ( $key ) {
			return strpos( $key, '__test__' ) === 0;
		} ) );
	}

	/**
	 * It should return false when getting chunkable in incoherent state
	 *
	 * @test
	 */
	public function should_return_false_when_getting_chunkable_in_incoherent_state() {
		// Set a size for the MySQL max_allowed_packet_size in bytes.
		add_filter( 'tribe_max_allowed_packet_size', static function () {
			return 100;
		} );
		// Create a value that, in string format, is below the max allowed packet size.
		$value                  = (object) [ 'value' => str_repeat( 'test', 100 ) ];
		$set_transient_calls    = [];
		$this->set_fn_return( 'wp_using_ext_object_cache', false );
		$this->set_fn_return( 'set_transient', static function ( $name, $value ) use ( &$set_transient_calls ) {
			$set_transient_calls[] = $name;

			// Log the call.
			return set_transient( $name, $value );
		}, true );

		$cache = $this->make_instance();

		$this->assertTrue( $cache->set_chunkable_transient( '__test__', $value, DAY_IN_SECONDS, [ 'save_post' ] ) );

		// Delete the middle transient.
		delete_transient( $set_transient_calls[2] );

		// Then get the chunkable transient; when found broken, it should drop the other chunks.
		$this->assertFalse( $cache->get_chunkable_transient( '__test__', [ 'save_post' ] ) );
	}

	/**
	 * should return the chunks from the cache correctly when the cache is stored
	 *
	 * @test
	 */
	 public function should_return_the_chunks_from_the_cache_correctly_when_the_cache_is_stored() {
		 // Set a size for the MySQL max_allowed_packet_size in bytes.
		 add_filter( 'tribe_max_allowed_packet_size', static function () {
			 return 100;
		 } );
		 // Create a value that, in string format, is higher the max allowed packet size.
		 $value                  = [ 'value' => str_repeat( 'test', 250 ) ];
		 $set_transient_calls    = [];
		 $this->set_fn_return( 'wp_using_ext_object_cache', false );
		 $this->set_fn_return( 'set_transient', static function ( $name, $value ) use ( &$set_transient_calls ) {
			 $set_transient_calls[] = $name;

			 // Log the call.
			 return set_transient( $name, $value );
		 }, true );

		 $cache = $this->make_instance();

		 $this->assertTrue( $cache->set_chunkable_transient( '__test___retrival__from__cache', $value, DAY_IN_SECONDS, [ 'save_post' ] ) );

		 // The value from the cache should be the same that was stored.
		 $this->assertSame( $value, $cache->get_chunkable_transient( '__test___retrival__from__cache', [ 'save_post' ] ) );
	 }
}
