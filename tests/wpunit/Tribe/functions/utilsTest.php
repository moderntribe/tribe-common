<?php

namespace Tribe\functions;

use PHPUnit\Framework\AssertionFailedError;

class Test_Class_With_Instance_Fetch_Method {
	public static function get_instance() {
		return new static;
	}
}

class utilsTest extends \Codeception\TestCase\WPTestCase {
	public function urls() {
		return [
			[ 'http://some.dev', 'foo', 'http://some.dev/foo/' ],
			[ 'http://some.dev', 'foo/', 'http://some.dev/foo/' ],
			[ 'http://some.dev', '/foo', 'http://some.dev/foo/' ],
			[ 'http://some.dev', '/foo/', 'http://some.dev/foo/' ],
			[ 'http://some.dev/', 'foo', 'http://some.dev/foo/' ],
			[ 'http://some.dev/', 'foo/', 'http://some.dev/foo/' ],
			[ 'http://some.dev/', '/foo', 'http://some.dev/foo/' ],
			[ 'http://some.dev/', '/foo/', 'http://some.dev/foo/' ],
			[ 'http://some.dev?bar=baz', 'foo', 'http://some.dev/foo/?bar=baz' ],
			[ 'http://some.dev/?bar=baz', 'foo', 'http://some.dev/foo/?bar=baz' ],
			[ 'http://some.dev?bar=baz', 'foo/', 'http://some.dev/foo/?bar=baz' ],
			[ 'http://some.dev/?bar=baz', 'foo/', 'http://some.dev/foo/?bar=baz' ],
			[ 'http://some.dev?bar=baz', '/foo', 'http://some.dev/foo/?bar=baz' ],
			[ 'http://some.dev/?bar=baz', '/foo', 'http://some.dev/foo/?bar=baz' ],
			[ 'http://some.dev?bar=baz', '/foo/', 'http://some.dev/foo/?bar=baz' ],
			[ 'http://some.dev/?bar=baz', '/foo/', 'http://some.dev/foo/?bar=baz' ],
			[ 'http://some.dev?bar=baz&another=value', 'foo', 'http://some.dev/foo/?bar=baz&another=value' ],
			[ 'http://some.dev/?bar=baz&another=value', 'foo', 'http://some.dev/foo/?bar=baz&another=value' ],
			[ 'http://some.dev?bar=baz&another=value', 'foo/', 'http://some.dev/foo/?bar=baz&another=value' ],
			[ 'http://some.dev/?bar=baz&another=value', 'foo/', 'http://some.dev/foo/?bar=baz&another=value' ],
			[ 'http://some.dev?bar=baz&another=value', '/foo', 'http://some.dev/foo/?bar=baz&another=value' ],
			[ 'http://some.dev/?bar=baz&another=value', '/foo', 'http://some.dev/foo/?bar=baz&another=value' ],
			[ 'http://some.dev?bar=baz&another=value', '/foo/', 'http://some.dev/foo/?bar=baz&another=value' ],
			[ 'http://some.dev/?bar=baz&another=value', '/foo/', 'http://some.dev/foo/?bar=baz&another=value' ],
			[ 'http://some.dev#frag', 'foo', 'http://some.dev/foo/#frag' ],
			[ 'http://some.dev#frag', 'foo/', 'http://some.dev/foo/#frag' ],
			[ 'http://some.dev#frag', '/foo', 'http://some.dev/foo/#frag' ],
			[ 'http://some.dev#frag', '/foo/', 'http://some.dev/foo/#frag' ],
			[ 'http://some.dev?bar=baz&another=value#p1', 'foo', 'http://some.dev/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev/?bar=baz&another=value#p1', 'foo', 'http://some.dev/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev?bar=baz&another=value#p1', 'foo/', 'http://some.dev/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev/?bar=baz&another=value#p1', 'foo/', 'http://some.dev/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev?bar=baz&another=value#p1', '/foo', 'http://some.dev/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev/?bar=baz&another=value#p1', '/foo', 'http://some.dev/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev?bar=baz&another=value#p1', '/foo/', 'http://some.dev/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev/?bar=baz&another=value#p1', '/foo/', 'http://some.dev/foo/?bar=baz&another=value#p1' ],
			[ 'http://some.dev', 'some/foo', 'http://some.dev/some/foo/' ],
			[ 'http://some.dev', 'some/foo/', 'http://some.dev/some/foo/' ],
			[ 'http://some.dev', '/some/foo', 'http://some.dev/some/foo/' ],
			[ 'http://some.dev', '/some/foo/', 'http://some.dev/some/foo/' ],
			[ 'http://some.dev/', 'some/foo', 'http://some.dev/some/foo/' ],
			[ 'http://some.dev/', 'some/foo/', 'http://some.dev/some/foo/' ],
			[ 'http://some.dev/', '/some/foo', 'http://some.dev/some/foo/' ],
			[ 'http://some.dev/', '/some/foo/', 'http://some.dev/some/foo/' ],
			[ 'http://some.dev?bar=baz', 'some/foo', 'http://some.dev/some/foo/?bar=baz' ],
			[ 'http://some.dev/?bar=baz', 'some/foo', 'http://some.dev/some/foo/?bar=baz' ],
			[ 'http://some.dev?bar=baz', 'some/foo/', 'http://some.dev/some/foo/?bar=baz' ],
			[ 'http://some.dev/?bar=baz', 'some/foo/', 'http://some.dev/some/foo/?bar=baz' ],
			[ 'http://some.dev?bar=baz', '/some/foo', 'http://some.dev/some/foo/?bar=baz' ],
			[ 'http://some.dev/?bar=baz', '/some/foo', 'http://some.dev/some/foo/?bar=baz' ],
			[ 'http://some.dev?bar=baz', '/some/foo/', 'http://some.dev/some/foo/?bar=baz' ],
			[ 'http://some.dev/?bar=baz', '/some/foo/', 'http://some.dev/some/foo/?bar=baz' ],
			[ 'http://some.dev?bar=baz&another=value', 'some/foo', 'http://some.dev/some/foo/?bar=baz&another=value' ],
			[ 'http://some.dev/?bar=baz&another=value', 'some/foo', 'http://some.dev/some/foo/?bar=baz&another=value' ],
			[ 'http://some.dev?bar=baz&another=value', 'some/foo/', 'http://some.dev/some/foo/?bar=baz&another=value' ],
			[
				'http://some.dev/?bar=baz&another=value',
				'some/foo/',
				'http://some.dev/some/foo/?bar=baz&another=value'
			],
			[ 'http://some.dev?bar=baz&another=value', '/some/foo', 'http://some.dev/some/foo/?bar=baz&another=value' ],
			[
				'http://some.dev/?bar=baz&another=value',
				'/some/foo',
				'http://some.dev/some/foo/?bar=baz&another=value'
			],
			[
				'http://some.dev?bar=baz&another=value',
				'/some/foo/',
				'http://some.dev/some/foo/?bar=baz&another=value'
			],
			[
				'http://some.dev/?bar=baz&another=value',
				'/some/foo/',
				'http://some.dev/some/foo/?bar=baz&another=value'
			],
			[ 'http://some.dev#frag', 'some/foo', 'http://some.dev/some/foo/#frag' ],
			[ 'http://some.dev#frag', 'some/foo/', 'http://some.dev/some/foo/#frag' ],
			[ 'http://some.dev#frag', '/some/foo', 'http://some.dev/some/foo/#frag' ],
			[ 'http://some.dev#frag', '/some/foo/', 'http://some.dev/some/foo/#frag' ],
			[
				'http://some.dev?bar=baz&another=value#p1',
				'some/foo',
				'http://some.dev/some/foo/?bar=baz&another=value#p1'
			],
			[
				'http://some.dev/?bar=baz&another=value#p1',
				'some/foo',
				'http://some.dev/some/foo/?bar=baz&another=value#p1'
			],
			[
				'http://some.dev?bar=baz&another=value#p1',
				'some/foo/',
				'http://some.dev/some/foo/?bar=baz&another=value#p1'
			],
			[
				'http://some.dev/?bar=baz&another=value#p1',
				'some/foo/',
				'http://some.dev/some/foo/?bar=baz&another=value#p1'
			],
			[
				'http://some.dev?bar=baz&another=value#p1',
				'/some/foo',
				'http://some.dev/some/foo/?bar=baz&another=value#p1'
			],
			[
				'http://some.dev/?bar=baz&another=value#p1',
				'/some/foo',
				'http://some.dev/some/foo/?bar=baz&another=value#p1'
			],
			[
				'http://some.dev?bar=baz&another=value#p1',
				'/some/foo/',
				'http://some.dev/some/foo/?bar=baz&another=value#p1'
			],
			[
				'http://some.dev/?bar=baz&another=value#p1',
				'/some/foo/',
				'http://some.dev/some/foo/?bar=baz&another=value#p1'
			],
		];
	}

	/**
	 * @test
	 * it should allow appending path to various urls
	 * @dataProvider urls
	 */
	public function it_should_allow_appending_path_to_various_urls( $url, $path, $expected ) {
		$this->assertEquals( $expected, tribe_append_path( $url, $path ) );
	}

	/**
	 * Test tribe_post_exists
	 */
	public function test_tribe_post_exists() {
		$this->assertFalse( tribe_post_exists( 2323 ) );
		$this->assertFalse( tribe_post_exists( 2323, 'post' ) );
		$this->assertFalse( tribe_post_exists( 2323, 'page' ) );
		$this->assertFalse( tribe_post_exists( 2323, [ 'post', 'page' ] ) );

		$post = $this->factory->post->create_and_get();

		$this->assertEquals( $post->ID, tribe_post_exists( $post ) );
		$this->assertEquals( $post->ID, tribe_post_exists( $post->ID ) );
		$this->assertEquals( $post->ID, tribe_post_exists( $post->ID, 'post' ) );
		$this->assertEquals( $post->ID, tribe_post_exists( $post->ID, [ 'post', 'page' ] ) );
		$this->assertFalse( tribe_post_exists( $post->ID, 'page' ) );
		$this->assertEquals( $post->ID, tribe_post_exists( $post->post_name ) );
		$this->assertEquals( $post->ID, tribe_post_exists( $post->post_name, 'post' ) );
		$this->assertEquals( $post->ID, tribe_post_exists( $post->post_name, [ 'post', 'page' ] ) );
		$this->assertFalse( tribe_post_exists( $post->post_name, 'page' ) );
	}

	/**
	 * Test tribe_post_exists with deleted post
	 */
	public function test_tribe_post_exists_with_deleted_post() {
		$post = $this->factory->post->create_and_get();

		wp_delete_post( $post->ID, true );

		$this->assertFalse( tribe_post_exists( $post ) );
		$this->assertFalse( tribe_post_exists( $post->ID ) );
		$this->assertFalse( tribe_post_exists( $post->ID, 'post' ) );
		$this->assertFalse( tribe_post_exists( $post->ID, 'page' ) );
		$this->assertFalse( tribe_post_exists( $post->ID, [ 'post', 'page' ] ) );
		$this->assertFalse( tribe_post_exists( $post->post_name ) );
		$this->assertFalse( tribe_post_exists( $post->post_name, 'post' ) );
		$this->assertFalse( tribe_post_exists( $post->post_name, 'page' ) );
		$this->assertFalse( tribe_post_exists( $post->post_name, [ 'post', 'page' ] ) );
	}

	/**
	 * Test tribe_post_exists with user
	 */
	public function test_tribe_post_exists_with_user() {
		$user_id = $this->factory->user->create();

		$this->assertFalse( tribe_post_exists( $user_id ) );
		$this->assertFalse( tribe_post_exists( $user_id, 'post' ) );
		$this->assertFalse( tribe_post_exists( $user_id, 'page' ) );
		$this->assertFalse( tribe_post_exists( $user_id, [ 'post', 'page' ] ) );
	}

	public function tribe_sanitize_deep_data_set() {
		return [
			'empty_string'            => [ '', '' ],
			'spaces'                  => [ '  ', '  ' ],
			'string'                  => [ 'hello world', 'hello world' ],
			'int_zero'                => [ 0, 0 ],
			'float_zero'              => [ 0.0, 0.0 ],
			'int_value'               => [ 23, 23 ],
			'float_value_wo_decimals' => [ 23.00, 23.00 ],
			'float_value_w_decimals'  => [ 23.89, 23.89 ],
			'string_w_tag'            => [ '<h1>Hello World!</h1>', 'Hello World!' ],
			'string_url'              => [ 'http://example.org', 'http://example.org' ],
			'string_ip_address'       => [ '1.1.2.3', '1.1.2.3' ],
			'object'                  => [ new \stdClass(), null ],
			'good_array'              => [
				[
					'string'                  => 'hello world',
					'int_zero'                => 0,
					'float_zero'              => 0.0,
					'int_value'               => 23,
					'float_value_wo_decimals' => 23.00,
					'string_w_tag'            => '<h1>Hello World!</h1>',
				],
				[
					'string'                  => 'hello world',
					'int_zero'                => 0,
					'float_zero'              => 0.0,
					'int_value'               => 23,
					'float_value_wo_decimals' => 23.00,
					'string_w_tag'            => 'Hello World!',
				],
			],
			'nested_array'            => [
				[
					'string'       => 'hello world',
					'int_zero'     => 0,
					'float_zero'   => 0.0,
					'string_w_tag' => '<h1>Hello World!</h1>',
					'sub_1'        => [
						'int_value'               => 23,
						'float_value_wo_decimals' => 23.00,
						'string_w_tag'            => '<h1>Hello World!</h1>',
						'sub_2'                   => [
							'int_zero'     => 0,
							'float_zero'   => 0.0,
							'string_w_tag' => '<h1>Hello World!</h1>',
						]
					]
				],
				[
					'string'       => 'hello world',
					'int_zero'     => 0,
					'float_zero'   => 0.0,
					'string_w_tag' => 'Hello World!',
					'sub_1'        => [
						'int_value'               => 23,
						'float_value_wo_decimals' => 23.00,
						'string_w_tag'            => 'Hello World!',
						'sub_2'                   => [
							'int_zero'     => 0,
							'float_zero'   => 0.0,
							'string_w_tag' => 'Hello World!',
						]
					]
				],
			]
		];
	}

	/**
	 * @dataProvider  tribe_sanitize_deep_data_set
	 */
	public function test_tribe_sanitize_deep( $input, $expected ) {
		$this->assertEquals( $expected, tribe_sanitize_deep( $input ) );
	}

	public function tribe_get_query_var_data_set() {
		return [
			'empty'                             => [ '', 'test', null ],
			'not_a_url'                         => [ 'foo-bar-baz', 'test', null ],
			'abs_no_query'                      => [ 'http://example.com/', 'test', null ],
			'rel_no_query'                      => [ '/index.php', 'test', null ],
			'abs_query_arg_not_set'             => [ 'http://example.com/?foo=bar', 'test', null ],
			'rel_query_arg_not_set'             => [ '/index.php?foo=bar', 'test', null ],
			'abs_query_arg_set'                 => [ 'http://example.com/?test=bar', 'test', 'bar' ],
			'rel_query_arg_set'                 => [ '/index.php?test=bar', 'test', 'bar' ],
			'abs_query_arg_set_array_of_args'   => [
				'http://example.com/?test=bar',
				[ 'test', 'baz' ],
				[ 'test' => 'bar' ]
			],
			'rel_query_arg_set_array_of_args'   => [ '/index.php?test=bar', [ 'test', 'baz' ], [ 'test' => 'bar' ] ],
			'abs_query_arg_set_array_of_args_2' => [
				'http://example.com/?test=bar&baz=23',
				[ 'test', 'baz' ],
				[ 'test' => 'bar', 'baz' => 23 ]
			],
			'rel_query_arg_set_array_of_args_2' => [
				'/index.php?test=bar&baz=23',
				[ 'test', 'baz' ],
				[ 'test' => 'bar', 'baz' => 23 ]
			],
		];
	}

	/**
	 * @dataProvider  tribe_get_query_var_data_set
	 */
	public function test_tribe_get_query_var( $input, $query_arg, $expected, $default = null ) {
		$this->assertEquals( $expected, tribe_get_query_var( $input, $query_arg, $default ) );
	}

	/**
	 * @dataProvider provider_get_successful_class_instance
	 */
	public function test_successfully_getting_a_class_instance( $class ) {
		if ( $class instanceof \Closure ) {
			$class = $class();
		}
		$this->assertTrue( is_object( tribe_get_class_instance( $class ) ) );
	}

	/**
	 * Different ways to successfully get a class instance.
	 *
	 * @return Generator
	 * @see \tribe_get_class_instance()
	 *
	 */
	public function provider_get_successful_class_instance() {
		yield 'class slug registered with tribe()' => [ 'assets' ];
		yield 'class string not registered with tribe()' => [ 'Tribe__App_Shop' ];

		// WordPress is still not loaded at this stage, delay the build to the test case, when WP will be loaded.
		yield 'instantiated class object' => [
			static function () {
				return new \Tribe__App_Shop();
			}
		];

		yield 'class string that has get_instance()' => [ '\\Tribe\\functions\\Test_Class_With_Instance_Fetch_Method' ];
	}

	/**
	 * @dataProvider provider_get_unsuccessful_class_instance
	 */
	public function test_unsuccessfully_getting_a_class_instance( $class ) {
		$this->assertNull( tribe_get_class_instance( $class ) );
	}

	/**
	 * Different ways to fail at getting a class instance (should all return null).
	 *
	 * @return Generator
	 * @see \tribe_get_class_instance()
	 *
	 */
	public function provider_get_unsuccessful_class_instance() {
		yield 'empty string' => [ '' ];
		yield 'string that is neither an existing class name nor slug' => [ 'ABC_123_XYZ' ];
		yield 'neither an object nor a string' => [ [] ];
	}

	/**
	 * It should allow running a callback detaching filters.
	 *
	 * @test
	 */
	public function should_allow_running_a_callback_detaching_filters() {
		add_filter( 'test_filter', '__return_false' );
		$callback = static function () {
			return apply_filters( 'test_filter', 23 );
		};

		$value = tribe_without_filters( [ 'test_filter' ], $callback );

		$this->assertEquals( 23, $value );
	}

	/**
	 * It should allow running a callback detaching actions.
	 *
	 * @test
	 */
	public function should_allow_running_a_callback_detaching_actions() {
		add_action( 'test_action', static function () {
			throw new AssertionFailedError( 'I should not be called!' );
		} );
		$callback = static function () {
			do_action( 'test_action' );

			return 23;
		};

		$value = tribe_without_filters( [ 'test_action' ], $callback );

		$this->assertEquals( 23, $value );
	}

	/**
	 * It should allow running a callback detaching filters and actions.
	 *
	 * @test
	 */
	public function should_allow_running_a_callback_detaching_filters_and_actions() {
		add_action( 'test_action', static function () {
			throw new AssertionFailedError( 'I should not be called!' );
		} );
		add_filter( 'test_filter', static function () {
			throw new AssertionFailedError( 'I should not be called!' );
		} );
		$callback = static function () {
			do_action( 'test_action' );

			return apply_filters( 'test_filter', 23 );
		};

		$value = tribe_without_filters( [ 'test_action', 'test_filter' ], $callback );

		$this->assertEquals( 23, $value );
	}

	/**
	 * It should not detach filters that are not in the list of filters to suspend.
	 *
	 * @test
	 */
	public function should_not_detach_filters_that_are_not_in_the_list_of_filters_to_suspend() {
		add_action( 'test_action', static function () {
			throw new AssertionFailedError( 'I should not be called!' );
		} );
		add_filter( 'test_filter', static function () {
			throw new AssertionFailedError( 'I should not be called!' );
		} );
		$action_2_called = false;
		add_action( 'test_action_2', static function () use ( &$action_2_called ) {
			$action_2_called = true;
		} );
		$callback = static function () {
			do_action( 'test_action' );
			do_action( 'test_action_2' );

			return apply_filters( 'test_filter', 23 );
		};

		$value = tribe_without_filters( [ 'test_action', 'test_filter' ], $callback );

		$this->assertEquals( 23, $value );
		$this->assertTrue( $action_2_called );
	}

	/**
	 * It should allow suspending filters
	 *
	 * @test
	 */
	public function should_allow_suspending_filters() {
		$callback_one = function ( string $input ) {
			$arguments = func_get_args();
			$this->assertCount( 3, $arguments );

			return 'one';
		};
		$callback_two = function ( string $input ) {
			$arguments = func_get_args();
			$this->assertCount( 5, $arguments );

			return 'two';
		};

		add_filter( 'test_filter', $callback_one, 23, 3 );
		add_filter( 'test_filter', $callback_two, 13, 5 );

		$this->assertEquals( 'one', apply_filters( 'test_filter', 'original', ...range( 1, 10 ) ) );
		$this->assertEquals(
			'two',
			tribe_suspending_filter(
				'test_filter',
				$callback_one,
				function () use ( $callback_one, $callback_two ) {
					$this->assertFalse( has_filter( 'test_filter', $callback_one ) );
					$this->assertEquals( 13, has_filter( 'test_filter', $callback_two ) );

					return apply_filters( 'test_filter', 'original', ...range( 1, 10 ) );
				},
				3
			)
		);
		$this->assertEquals( 23, has_filter( 'test_filter', $callback_one ) );
		$this->assertEquals( 13, has_filter( 'test_filter', $callback_two ) );
		$this->assertEquals(
			'one',
			tribe_suspending_filter(
				'test_filter',
				$callback_two,
				function () use ( $callback_one, $callback_two ) {
					$this->assertEquals( 23, has_filter( 'test_filter', $callback_one ) );
					$this->assertFalse( has_filter( 'test_filter', $callback_two ) );

					return apply_filters( 'test_filter', 'original', ...range( 1, 10 ) );
				},
				3
			)
		);
	}
}
