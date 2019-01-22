<?php

namespace Tribe;

use Tribe\Common\Tests\TestClass;
use Tribe__Context as Context;

function __context__test__function__() {
	return '__value__';
}

function __set_function__( $value ) {
	global $__test_function_set_value;
	$__test_function_set_value = $value;
}

include_once codecept_data_dir( 'classes/TestClass.php' );

class ContextTest extends \Codeception\TestCase\WPTestCase {

	public static $__key__;
	public static $__static_prop_1__;
	public static $__static_prop_2__;
	protected static $__static_method_return_value__;
	protected static $static_set_value_1;
	protected static $static_set_value_2;
	public $__public_key__;
	public $__public_key_2__;
	protected $__public_method_return_value__;
	protected $set_value;
	protected $public_set_instance_value_1;
	protected $public_set_instance_value_2;
	protected $function_set_value;
	protected $callable_set_value;

	public static function __test_static_method__() {
		return static::$__static_method_return_value__;
	}

	public static function static_setter_1( $value ) {
		static::$static_set_value_1 = $value;
	}

	public static function static_setter_2( $value ) {
		static::$static_set_value_2 = $value;
	}

	public function __public_method__() {
		return $this->__public_method_return_value__;
	}

	/**
	 * @test
	 * it should be instantiatable
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( Context::class, $sut );
	}

	/**
	 * @return Context
	 */
	private function make_instance() {
		return new Context();
	}

	/**
	 * It should correctly detect when editing a post
	 *
	 * @test
	 */
	public function should_correctly_detect_when_editing_a_post() {
		$sut = $this->make_instance();

		$this->assertFalse( $sut->is_editing_post() );

		$post_id        = $this->factory->post->create();
		$second_post_id = $this->factory->post->create();

		$this->go_to( "/wp-admin/post.php?post={$post_id}&action=edit" );
		global $pagenow;
		$pagenow = 'post.php';

		$this->assertFalse( $sut->is_editing_post( 'page' ) );
		$this->assertTrue( $sut->is_editing_post( 'post' ) );
		$this->assertTrue( $sut->is_editing_post( $post_id ) );
		$this->assertTrue( $sut->is_editing_post( array( 'page', 'post' ) ) );
		$this->assertFalse( $sut->is_editing_post( $second_post_id ) );
		$this->assertFalse( $sut->is_editing_post( 2389 ) );
		$this->assertTrue( $sut->is_editing_post( array( $post_id, $second_post_id ) ) );
	}

	/**
	 * It should be editing a post when creating a new post
	 *
	 * @test
	 */
	public function should_be_editing_a_post_when_creating_a_new_post() {
		$post_id        = $this->factory->post->create();
		$second_post_id = $this->factory->post->create();

		$sut = $this->make_instance();

		$this->assertFalse( $sut->is_editing_post() );

		$this->go_to( "/wp-admin/post-new.php" );
		global $pagenow;
		$pagenow = 'post-new.php';

		$this->assertTrue( $sut->is_editing_post() );
		$this->assertTrue( $sut->is_editing_post( 'post' ) );
		$this->assertFalse( $sut->is_editing_post( 'page' ) );
		$this->assertFalse( $sut->is_editing_post( $post_id ) );
		$this->assertTrue( $sut->is_editing_post( array( 'page', 'post' ) ) );
		$this->assertFalse( $sut->is_editing_post( $second_post_id ) );
		$this->assertFalse( $sut->is_editing_post( 2389 ) );
		$this->assertFalse( $sut->is_editing_post( array( $post_id, $second_post_id ) ) );

		$this->go_to( "/wp-admin/post-new.php?post_type=page" );
		global $pagenow;
		$pagenow           = 'post-new.php';
		$_GET['post_type'] = 'page';

		$this->assertTrue( $sut->is_editing_post() );
		$this->assertFalse( $sut->is_editing_post( 'post' ) );
		$this->assertTrue( $sut->is_editing_post( 'page' ) );
		$this->assertFalse( $sut->is_editing_post( $post_id ) );
		$this->assertTrue( $sut->is_editing_post( array( 'page', 'post' ) ) );
		$this->assertFalse( $sut->is_editing_post( $second_post_id ) );
		$this->assertFalse( $sut->is_editing_post( 2389 ) );
		$this->assertFalse( $sut->is_editing_post( array( $post_id, $second_post_id ) ) );
	}

	/**
	 * It should correctly identify new posts
	 *
	 * @test
	 */
	public function should_correctly_identify_new_posts() {
		$post_id        = $this->factory->post->create();
		$second_post_id = $this->factory->post->create();

		$sut = $this->make_instance();

		$this->assertFalse( $sut->is_new_post() );

		$this->go_to( "/wp-admin/post-new.php" );
		global $pagenow;
		$pagenow = 'post-new.php';

		$this->assertTrue( $sut->is_new_post() );
		$this->assertTrue( $sut->is_new_post( 'post' ) );
		$this->assertFalse( $sut->is_new_post( 'page' ) );
		$this->assertFalse( $sut->is_new_post( $post_id ) );
		$this->assertTrue( $sut->is_new_post( array( 'page', 'post' ) ) );
		$this->assertFalse( $sut->is_new_post( $second_post_id ) );
		$this->assertFalse( $sut->is_new_post( 2389 ) );
		$this->assertFalse( $sut->is_new_post( array( $post_id, $second_post_id ) ) );

		$this->go_to( "/wp-admin/post-new.php?post_type=page" );
		global $pagenow;
		$pagenow           = 'post-new.php';
		$_GET['post_type'] = 'page';

		$this->assertTrue( $sut->is_new_post() );
		$this->assertFalse( $sut->is_new_post( 'post' ) );
		$this->assertTrue( $sut->is_new_post( 'page' ) );
		$this->assertFalse( $sut->is_new_post( $post_id ) );
		$this->assertTrue( $sut->is_new_post( array( 'page', 'post' ) ) );
		$this->assertFalse( $sut->is_new_post( $second_post_id ) );
		$this->assertFalse( $sut->is_new_post( 2389 ) );
		$this->assertFalse( $sut->is_new_post( array( $post_id, $second_post_id ) ) );
	}

	function setUp() {
		parent::setUp();
		global $pagenow;
		$pagenow = null;
		unset( $_GET['post_type'] );
	}

	/**
	 * It should allow reading a value from a request var
	 *
	 * @test
	 */
	public function should_allow_reading_a_value_from_a_request_var() {
		$_REQUEST['__request_key__'] = '__request_value__';
		$_POST['__post_key__']       = '__post_value__';
		$_GET['__get_key__']         = '__get_value__';

		$context = tribe_context()->add_locations( [
			'__request__' => [ 'read' => [ Context::REQUEST_VAR => '__request_key__' ] ],
			'__post__'    => [ 'read' => [ Context::REQUEST_VAR => '__post_key__' ] ],
			'__get__'     => [ 'read' => [ Context::REQUEST_VAR => '__get_key__' ] ],
		] );

		$this->assertNotSame( $context, tribe_context() );

		$this->assertEquals( '__request_value__', $context->get( '__request__', '__default__' ) );
		$this->assertEquals( '__post_value__', $context->get( '__post__', '__default__' ) );
		$this->assertEquals( '__get_value__', $context->get( '__get__', '__default__' ) );
		$this->assertEquals( '__default__', $context->get( '__unset__', '__default__' ) );
	}

	/**
	 * It should allow reading a value from the global WP_Query object query vars
	 *
	 * @test
	 */
	public function should_allow_reading_a_value_from_the_global_wp_query_object_query_vars() {
		global $wp_query;
		$wp_query->set( '__key__', '__value__' );

		$context = tribe_context()->add_locations( [
			'__query_var__' => [ 'read' => [ Context::QUERY_VAR => '__key__' ] ],
		] );

		$this->assertNotSame( $context, tribe_context() );

		$this->assertEquals( '__value__', $context->get( '__query_var__', '__default__' ) );
	}

	/**
	 * It should allow reading a value from a global wp query prop
	 *
	 * @test
	 */
	public function should_allow_reading_a_value_from_a_global_wp_query_prop() {
		global $wp_query;
		$wp_query->__test_prop__ = '__value__';

		$context = tribe_context()->add_locations( [
			'__query_prop__' => [ 'read' => [ Context::QUERY_PROP => '__test_prop__' ] ],
		] );

		$this->assertNotSame( $context, tribe_context() );

		$this->assertEquals( '__value__', $context->get( '__query_prop__', '__default__' ) );
	}

	/**
	 * It should allow reading a value from a tribe_option
	 *
	 * @test
	 */
	public function should_allow_reading_a_value_from_a_tribe_option() {
		tribe_update_option( '__key__', '__value__' );

		$context = tribe_context()->add_locations( [
			'__tribe_option__' => [ 'read' => [ Context::TRIBE_OPTION => '__key__' ] ],
		] );

		$this->assertNotSame( $context, tribe_context() );

		$this->assertEquals( '__value__', $context->get( '__tribe_option__', '__default__' ) );
	}

	/**
	 * It should allow reading a value from an option
	 *
	 * @test
	 */
	public function should_allow_reading_a_value_from_an_option() {
		update_option( '__key__', '__value__' );

		$context = tribe_context()->add_locations( [
			'__option__' => [ 'read' => [ Context::OPTION => '__key__' ] ],
		] );

		$this->assertNotSame( $context, tribe_context() );

		$this->assertEquals( '__value__', $context->get( '__option__', '__default__' ) );
	}

	/**
	 * It should allow reading a value from a transient
	 *
	 * @test
	 */
	public function should_allow_reading_a_value_from_a_transient() {
		set_transient( '__key__', '__value__' );

		$context = tribe_context()->add_locations( [
			'__transient__' => [ 'read' => [ Context::TRANSIENT => '__key__' ] ],
		] );

		$this->assertNotSame( $context, tribe_context() );

		$this->assertEquals( '__value__', $context->get( '__transient__', '__default__' ) );
	}

	/**
	 * It should allow reading a value from a contstant
	 *
	 * @test
	 */
	public function should_allow_reading_a_value_from_a_contstant() {
		define( '__KEY__', '__value__' );

		$context = tribe_context()->add_locations( [
			'__constant__' => [ 'read' => [ Context::CONSTANT => '__KEY__' ] ],
		] );

		$this->assertNotSame( $context, tribe_context() );

		$this->assertEquals( '__value__', $context->get( '__constant__', '__default__' ) );
	}

	/**
	 * It should allow reading a value from a global var
	 *
	 * @test
	 */
	public function should_allow_reading_a_value_from_a_global_var() {
		global $__key__;
		$__key__ = '__value__';

		$context = tribe_context()->add_locations( [
			'__global__' => [ 'read' => [ Context::GLOBAL_VAR => '__key__' ] ],
		] );

		$this->assertNotSame( $context, tribe_context() );

		$this->assertEquals( '__value__', $context->get( '__global__', '__default__' ) );
	}

	/**
	 * It should allow reading a value from a static property
	 *
	 * @test
	 */
	public function should_allow_reading_a_value_from_a_static_property() {
		static::$__key__ = '__value__';

		$context = tribe_context()->add_locations( [
			'__static_prop__' => [ 'read' => [ Context::STATIC_PROP => [ static::class => '__key__' ] ] ],
		] );

		$this->assertNotSame( $context, tribe_context() );

		$this->assertEquals( '__value__', $context->get( '__static_prop__', '__default__' ) );
	}

	/**
	 * It should allow reading a value from a binding public prop
	 *
	 * @test
	 */
	public function should_allow_reading_a_value_from_a_binding_public_prop() {
		$this->__public_key__ = '__value__';
		tribe_register( '__test__', $this );

		$context = tribe_context()->add_locations( [
			'__prop__' => [ 'read' => [ Context::PROP => [ '__test__' => '__public_key__' ] ] ],
		] );

		$this->assertNotSame( $context, tribe_context() );

		$this->assertEquals( '__value__', $context->get( '__prop__', '__default__' ) );
	}

	/**
	 * It should allow reading a value from a public static method
	 *
	 * @test
	 */
	public function should_allow_reading_a_value_from_a_public_static_method() {
		static::$__static_method_return_value__ = '__value__';

		$context = tribe_context()->add_locations( [
			'__static_method__' => [ 'read' => [ Context::STATIC_METHOD => [ static::class => '__test_static_method__' ] ] ],
		] );

		$this->assertNotSame( $context, tribe_context() );

		$this->assertEquals( '__value__', $context->get( '__static_method__', '__default__' ) );
	}

	/**
	 * It should allow reading a value from a binding public method
	 *
	 * @test
	 */
	public function should_allow_reading_a_value_from_a_binding_public_method() {
		$this->__public_method_return_value__ = '__value__';
		tribe_register( '__test__', $this );

		$context = tribe_context()->add_locations( [
			'__method__' => [ 'read' => [ Context::METHOD => [ '__test__' => '__public_method__' ] ] ],
		] );

		$this->assertNotSame( $context, tribe_context() );

		$this->assertEquals( '__value__', $context->get( '__method__', '__default__' ) );
	}

	/**
	 * It should allow reading a value from a function
	 *
	 * @test
	 */
	public function should_allow_reading_a_value_from_a_function() {
		$context = tribe_context()->add_locations( [
			'__func__' => [ 'read' => [ Context::FUNC => [ 'Tribe\\__context__test__function__' ] ] ],
		] );

		$this->assertNotSame( $context, tribe_context() );

		$this->assertEquals( '__value__', $context->get( '__func__', '__default__' ) );
	}

	/**
	 * It should allow reading a value from a closure
	 *
	 * @test
	 */
	public function should_allow_reading_a_value_from_a_closure() {
		$context = tribe_context()->add_locations( [
			'__closure__' => [
				'read' => [
					Context::FUNC => [
						function () {
							return '__value__';
						},
					],
				],
			],
		] );

		$this->assertNotSame( $context, tribe_context() );

		$this->assertEquals( '__value__', $context->get( '__closure__', '__default__' ) );
	}

	/**
	 * It should allow reading values from a number of locations
	 *
	 * @test
	 */
	public function should_allow_reading_values_from_a_number_of_locations() {
		$context = tribe_context()->add_locations( [
				'__seeking__' => [
					'read' => [
						Context::GLOBAL_VAR => '__nope__',
						Context::QUERY_VAR  => [
							'__niet__',
							'try_here',
						],
						Context::FUNC       => [ 'some_non_existing_function', 'Tribe\\__context__test__function__' ],
					],
				],
			]
		);

		$this->assertNotSame( $context, tribe_context() );

		$this->assertEquals( '__value__', $context->get( '__seeking__', '__default__' ) );
	}

	/**
	 * It should allow setting request vars
	 *
	 * @test
	 */
	public function should_allow_setting_request_vars() {
		$context = tribe_context()->add_locations( [
			'request_var_1' => [ 'write' => [ Context::REQUEST_VAR => 'test_request_var_1' ] ],
			'request_var_2' => [
				'write' => [
					Context::REQUEST_VAR => [
						'test_request_var_2',
						'test_request_var_3',
					],
				],
			],
		] );

		$context->alter( [
			'request_var_1' => 'value_1',
			'request_var_2' => 'value_2',
		] )->dangerously_set_global_context();

		$this->assertEquals( 'value_1', tribe_get_request_var( 'test_request_var_1' ) );
		$this->assertEquals( 'value_2', tribe_get_request_var( 'test_request_var_2' ) );
		$this->assertEquals( 'value_2', tribe_get_request_var( 'test_request_var_3' ) );
	}

	/**
	 * It should allow setting global vars
	 *
	 * @test
	 */
	public function should_allow_setting_global_vars() {
		$context = tribe_context()->add_locations( [
			'global_var_1' => [ 'write' => [ Context::GLOBAL_VAR => 'test_global_var_1' ] ],
			'global_var_2' => [ 'write' => [ Context::GLOBAL_VAR => [ 'test_global_var_2', 'test_global_var_3' ] ] ],
		] );

		$context->alter( [
			'global_var_1' => 'value_1',
			'global_var_2' => 'value_2',
		] )->dangerously_set_global_context();

		global $test_global_var_1, $test_global_var_2, $test_global_var_3;
		$this->assertEquals( 'value_1', $test_global_var_1 );
		$this->assertEquals( 'value_2', $test_global_var_2 );
		$this->assertEquals( 'value_2', $test_global_var_3 );
	}

	/**
	 * It should allow setting a query var on the global WP_Query
	 *
	 * @test
	 */
	public function should_allow_setting_a_query_var_on_the_global_wp_query() {
		global $wp_query;
		$wp_query = new \WP_Query();

		$context = tribe_context()->add_locations( [
			'query_var_1' => [ 'write' => [ Context::QUERY_VAR => 'test_query_var_1' ] ],
			'query_var_2' => [ 'write' => [ Context::QUERY_VAR => [ 'test_query_var_2', 'test_query_var_3' ] ] ],
		] );

		$context->alter( [
			'query_var_1' => 'value_1',
			'query_var_2' => 'value_2',
		] )->dangerously_set_global_context();

		$this->assertEquals( 'value_1', $wp_query->get( 'test_query_var_1' ) );
		$this->assertEquals( 'value_2', $wp_query->get( 'test_query_var_2' ) );
		$this->assertEquals( 'value_2', $wp_query->get( 'test_query_var_3' ) );
	}

	/**
	 * It should allow setting a prop on the global WP_Query
	 *
	 * @test
	 */
	public function should_allow_setting_a_prop_on_the_global_wp_query() {
		global $wp_query;
		$wp_query = new \WP_Query();

		$context = tribe_context()->add_locations( [
			'query_prop_1' => [ 'write' => [ Context::QUERY_PROP => 'test_query_prop_1' ] ],
			'query_prop_2' => [ 'write' => [ Context::QUERY_PROP => [ 'test_query_prop_2', 'test_query_prop_3' ] ] ],
		] );

		$context->alter( [
			'query_prop_1' => 'value_1',
			'query_prop_2' => 'value_2',
		] )->dangerously_set_global_context();

		$this->assertEquals( 'value_1', $wp_query->test_query_prop_1 );
		$this->assertEquals( 'value_2', $wp_query->test_query_prop_2 );
		$this->assertEquals( 'value_2', $wp_query->test_query_prop_3 );
	}

	/**
	 * It should allow setting a value in a tribe option
	 *
	 * @test
	 */
	public function should_allow_setting_a_value_in_a_tribe_option() {
		$context = tribe_context()->add_locations( [
			'tribe_option_1' => [ 'write' => [ Context::TRIBE_OPTION => 'test_tribe_option_1' ] ],
			'tribe_option_2' => [
				'write' => [
					Context::TRIBE_OPTION => [
						'test_tribe_option_2',
						'test_tribe_option_3',
					],
				],
			],
		] );

		$context->alter( [
			'tribe_option_1' => 'value_1',
			'tribe_option_2' => 'value_2',
		] )->dangerously_set_global_context();

		$this->assertEquals( 'value_1', tribe_get_option( 'test_tribe_option_1' ) );
		$this->assertEquals( 'value_2', tribe_get_option( 'test_tribe_option_2' ) );
		$this->assertEquals( 'value_2', tribe_get_option( 'test_tribe_option_3' ) );
	}

	/**
	 * It should allow setting a value on an option
	 *
	 * @test
	 */
	public function should_allow_setting_a_value_on_an_option() {
		$context = tribe_context()->add_locations( [
			'option_1' => [ 'write' => [ Context::OPTION => 'test_option_1' ] ],
			'option_2' => [ 'write' => [ Context::OPTION => [ 'test_option_2', 'test_option_3' ] ] ],
		] );

		$context->alter( [
			'option_1' => 'value_1',
			'option_2' => 'value_2',
		] )->dangerously_set_global_context();

		$this->assertEquals( 'value_1', get_option( 'test_option_1' ) );
		$this->assertEquals( 'value_2', get_option( 'test_option_2' ) );
		$this->assertEquals( 'value_2', get_option( 'test_option_3' ) );
	}
	//'static_prop_key'   => [ Context::STATIC_PROP => [ static::class => '__key__' ] ],

	/**
	 * It should allow setting a value on a transient
	 *
	 * @test
	 */
	public function should_allow_setting_a_value_on_a_transient() {
		$context = tribe_context()->add_locations( [
			'transient_1' => [ 'write' => [ Context::TRANSIENT => [ 'test_transient_1' => 300 ] ] ],
			'transient_2' => [
				'write' => [
					Context::TRANSIENT => [
						'test_transient_2' => 600,
						'test_transient_3' => 900,
					],
				],
			],
		] );

		$context->alter( [
			'transient_1' => 'value_1',
			'transient_2' => 'value_2',
		] )->dangerously_set_global_context();

		$this->assertEquals( 'value_1', get_transient( 'test_transient_1' ) );
		$this->assertEquals( 'value_2', get_transient( 'test_transient_2' ) );
		$this->assertEquals( 'value_2', get_transient( 'test_transient_3' ) );
	}

	/**
	 * It should allow setting a value on a constant
	 *
	 * @test
	 */
	public function should_allow_setting_a_value_on_a_constant() {
		$context = tribe_context()->add_locations( [
			'constant_1' => [ 'write' => [ Context::CONSTANT => 'TEST_CONSTANT_1' ] ],
			'constant_2' => [ 'write' => [ Context::CONSTANT => [ 'TEST_CONSTANT_2', 'TEST_CONSTANT_3' ] ] ],
		] );

		$context->alter( [
			'constant_1' => 'value_1',
			'constant_2' => 'value_2',
		] )->dangerously_set_global_context();

		$this->assertEquals( 'value_1', TEST_CONSTANT_1 );
		$this->assertEquals( 'value_2', TEST_CONSTANT_2 );
		$this->assertEquals( 'value_2', TEST_CONSTANT_3 );
	}

	/**
	 * It should allow setting a value on a static prop
	 *
	 * @test
	 */
	public function should_allow_setting_a_value_on_a_static_prop() {
		$context = tribe_context()->add_locations( [
			'static_prop_1' => [ 'write' => [ Context::STATIC_PROP => [ static::class => '__static_prop_1__' ] ] ],
			'static_prop_2' => [
				'write' => [
					Context::STATIC_PROP => [
						static::class    => '__static_prop_2__',
						TestClass::class => '__static_prop__',
					],
				],
			],
		] );

		$context->alter( [
			'static_prop_1' => 'value_1',
			'static_prop_2' => 'value_2',
		] )->dangerously_set_global_context();

		$this->assertEquals( 'value_1', static::$__static_prop_1__ );
		$this->assertEquals( 'value_2', static::$__static_prop_2__ );
		$this->assertEquals( 'value_2', TestClass::$__static_prop__ );
	}

	/**
	 * It should allow setting a property on a bound implementation
	 *
	 * @test
	 */
	public function should_allow_setting_a_property_on_a_bound_implementation() {
		tribe_register( 'one', $this );
		$test_class = new TestClass();
		tribe_register( 'two', $test_class );
		$context = tribe_context()->add_locations( [
			'prop_1' => [ 'write' => [ Context::PROP => [ 'one' => '__public_key__' ] ] ],
			'prop_2' => [
				'write' => [
					Context::PROP => [
						'one' => '__public_key_2__',
						'two' => '__prop__',
					],
				],
			],
		] );

		$context->alter( [
			'prop_1' => 'value_1',
			'prop_2' => 'value_2',
		] )->dangerously_set_global_context();

		$this->assertEquals( 'value_1', $this->__public_key__ );
		$this->assertEquals( 'value_2', $this->__public_key_2__ );
		$this->assertEquals( 'value_2', $test_class->__prop__ );
	}

	/**
	 * It should allow setting a value calling a static method on a class
	 *
	 * @test
	 */
	public function should_allow_setting_a_value_calling_a_static_method_on_a_class() {
		$context = tribe_context()->add_locations( [
			'static_method_1' => [ 'write' => [ Context::STATIC_METHOD => [ static::class => 'static_setter_1' ] ] ],
			'static_method_2' => [
				'write' => [
					Context::STATIC_METHOD => [
						static::class    => 'static_setter_2',
						TestClass::class => 'static_setter',
					],
				],
			],
		] );

		$context->alter( [
			'static_method_1' => 'value_1',
			'static_method_2' => 'value_2',
		] )->dangerously_set_global_context();

		$this->assertEquals( 'value_1', static::$static_set_value_1 );
		$this->assertEquals( 'value_2', static::$static_set_value_2 );
		$this->assertEquals( 'value_2', TestClass::$public_set_value );
	}

	/**
	 * It should allow setting a value calling a bound implementation method
	 *
	 * @test
	 */
	public function should_allow_setting_a_value_calling_a_bound_implementation_method() {
		tribe_register( 'one', $this );
		$test_class = new TestClass();
		tribe_register( 'two', $test_class );
		$context = tribe_context()->add_locations( [
			'method_1' => [ 'write' => [ Context::METHOD => [ 'one' => 'setter_1' ] ] ],
			'method_2' => [
				'write' => [
					Context::METHOD => [
						'one' => 'setter_2',
						'two' => 'setter',
					],
				],
			],
		] );

		$context->alter( [
			'method_1' => 'value_1',
			'method_2' => 'value_2',
		] )->dangerously_set_global_context();

		$this->assertEquals( 'value_1', $this->public_set_instance_value_1 );
		$this->assertEquals( 'value_2', $this->public_set_instance_value_2 );
		$this->assertEquals( 'value_2', $test_class->public_set_instance_value );
	}

	/**
	 * It should allow setting a value calling a function
	 *
	 * @test
	 */
	public function should_allow_setting_a_value_calling_a_function() {
		$context = tribe_context()->add_locations( [
			'func_1' => [ 'write' => [ Context::FUNC => 'Tribe\\__set_function__' ] ],
			'func_2' => [
				'write' => [
					Context::FUNC => [
						function ( $value ) {
							$this->function_set_value = $value;
						},
						[ $this, 'callable_setter' ],
					],
				],
			],
		] );

		$context->alter( [
			'func_1' => 'value_1',
			'func_2' => 'value_2',
		] )->dangerously_set_global_context();

		global $__test_function_set_value;
		$this->assertEquals( 'value_1', $__test_function_set_value );
		$this->assertEquals( 'value_2', $this->function_set_value );
		$this->assertEquals( 'value_2', $this->callable_set_value );
	}

	public function setter_1( $value ) {
		$this->public_set_instance_value_1 = $value;
	}

	public function setter_2( $value ) {
		$this->public_set_instance_value_2 = $value;
	}

	public function callable_setter( $value ) {
		$this->callable_set_value = $value;
	}

	/**
	 * It should allow modifying a context locations and get a clone
	 *
	 * @test
	 */
	public function should_allow_modifying_a_context_locations_and_get_a_clone() {
		$var     = null;
		$context = tribe_context()->add_locations( [
			'foo' => [
				'read'  => [
					Context::FUNC => function () {
						return 'bar';
					},
				],
				'write' => [
					Context::FUNC => function ( $value ) use ( &$var ) {
						$var = $value;
					},
				],
			],
		] );

		$this->assertEquals( 'bar', $context->get( 'foo' ) );
		$context->alter( [ 'foo' => 'baz' ] )->dangerously_set_global_context();
		$this->assertEquals( 'baz', $var );
	}

	/**
	 * It should allow getting an array representation of the context
	 *
	 * @test
	 */
	public function should_allow_getting_an_array_representation_of_the_context() {
		$context = tribe_context()->set_locations( [
			'foo' => [
				'read' => [
					Context::FUNC => function () {
						return 'bar';
					},
				],
			],
			'bar' => [
				'read' => [
					Context::FUNC => function () {
						return 'baz';
					},
				],
			],
			'baz' => [
				'read' => [
					Context::FUNC => function () {
						return 'woot';
					},
				],
			],
		], false );

		$this->assertEquals( [
			'foo' => 'bar',
			'bar' => 'baz',
			'baz' => 'woot',
		], $context->to_array() );
	}

	/**
	 * It should allow producing ORM arguments
	 *
	 * @test
	 */
	public function should_allow_producing_orm_arguments() {
		$context = tribe_context()->set_locations( [
			'one' => [
				'read' => [
					Context::FUNC => function () {
						return 1;
					},
				],
			],
			'two' => [
				'read' => [
					Context::FUNC => function () {
						return 'two';
					},
				],
				'orm_arg' => 'alias_of_two',
			],
			'three' => [
				'read' => [
					Context::FUNC => function () {
						return 'thr33';
					},
				],
				'orm_arg' => false
			],
		], false );

		$orm_args = $context->get_orm_args();

		$this->assertEqualSets( [
			'one'          => 1,
			'alias_of_two' => 'two',
		], $orm_args );
	}

	/**
	 * It should allow getting a subset of ORM args
	 *
	 * @test
	 */
	public function should_allow_getting_a_subset_of_orm_args() {
		$context = tribe_context()->set_locations( [
			'one' => [
				'read' => [
					Context::FUNC => function () {
						return 1;
					},
				],
			],
			'two' => [
				'read' => [
					Context::FUNC => function () {
						return 'two';
					},
				],
				'orm_arg' => 'alias_of_two',
			],
			'three' => [
				'read' => [
					Context::FUNC => function () {
						return 'thr33';
					},
				],
				'orm_arg' => false
			],
			'four' => [
				'read' => [Context::FUNC => function(){return 23;}]
			]
		], false );

		$orm_args = $context->get_orm_args( [ 'one', 'alias_of_two', 'three' ] );

		$this->assertEqualSets( [
			'one'          => 1,
			'alias_of_two' => 'two',
		], $orm_args );
	}

	/**
	 * It should allow filtering out args from ORM args
	 *
	 * @test
	 */
	public function should_allow_filtering_out_args_from_orm_args() {
		$context = tribe_context()->set_locations( [
			'one' => [
				'read' => [
					Context::FUNC => function () {
						return 1;
					},
				],
			],
			'two' => [
				'read' => [
					Context::FUNC => function () {
						return 'two';
					},
				],
				'orm_arg' => 'alias_of_two',
			],
			'three' => [
				'read' => [
					Context::FUNC => function () {
						return 'thr33';
					},
				],
				'orm_arg' => false
			],
			'four' => [
				'read' => [Context::FUNC => function(){return 23;}]
			]
		], false );

		$orm_args = $context->get_orm_args( [ 'one', 'alias_of_two', 'three' ], false );

		$this->assertEqualSets( [
			'four' => 23,
		], $orm_args );
	}

	/**
	 * It should allow transforming ORM arguments before returning them
	 *
	 * @test
	 */
	public function should_allow_transforming_orm_arguments_before_returning_them() {
		$context = tribe_context()->set_locations( [
			'one' => [
				'read'          => [
					Context::FUNC => function () {
						return 1;
					},
				],
				'orm_arg'       => 'alias_of_one',
				'orm_transform' => function ( $input ) {
					return $input + 23;
				},
			],
			'two' => [
				'read'          => [
					Context::FUNC => function () {
						return 'two';
					},
				],
				'orm_transform' => '__return_false',
			],
		], false );

		$orm_args = $context->get_orm_args();

		$this->assertEqualSets( [
			'alias_of_one' => 24,
			'two'          => false,
		], $orm_args );
	}
}
