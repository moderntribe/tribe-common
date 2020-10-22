<?php

namespace Tribe\Test;

use Codeception\Test\Unit;
use Tribe__Autoloader as Autoloader;

class AutoloaderTest extends Unit {
	public function root_level_ns_class_data_provider() {
		return [
			'w/ leading slash'              => [ '\\Tribe\\Flat' ],
			'w/o leading slash'             => [ 'Tribe\\Flat' ],
			'w/ trailing slash'             => [ 'Tribe\\Flat\\' ],
			'w/ leading and trailing slash' => [ '\\Tribe\\Flat\\' ],
		];
	}

	/**
	 * It should correctly locate namespaced classes at root level
	 *
	 * @test
	 * @dataProvider root_level_ns_class_data_provider
	 */
	public function should_correctly_locate_namespaced_classes_at_root_level( $prefix ) {
		$autoloader = new Autoloader();
		$autoloader->register_prefix( $prefix, codecept_data_dir( 'Tribe/Flat' ) );

		$class_path = $autoloader->get_class_path( 'Tribe\\Flat\\One' );

		$this->assertEquals( codecept_data_dir( 'Tribe/Flat/One.php' ), $class_path );
	}

	public function nested_level_ns_class_data_provider() {
		return [
			'w/ leading slash'              => [ '\\Tribe\\Nested' ],
			'w/o leading slash'             => [ 'Tribe\\Nested' ],
			'w/ trailing slash'             => [ 'Tribe\\Nested\\' ],
			'w/ leading and trailing slash' => [ '\\Tribe\\Nested\\' ],
		];
	}

	/**
	 * It should correctly locate classes in nested namespaces
	 *
	 * @test
	 * @dataProvider nested_level_ns_class_data_provider
	 */
	public function should_correctly_locate_classes_in_nested_namespaces( $prefix ) {
		$autoloader = new Autoloader();
		$autoloader->register_prefix( $prefix, codecept_data_dir( 'Tribe/Nested' ) );

		$this->assertEquals(
			codecept_data_dir( 'Tribe/Nested/Root.php' ),
			$autoloader->get_class_path( 'Tribe\\Nested\\Root' )
		);

		$this->assertEquals(
			codecept_data_dir( 'Tribe/Nested/Level_One/Class_One.php' ),
			$autoloader->get_class_path( 'Tribe\\Nested\\Level_One\\Class_One' )
		);
		$this->assertEquals(
			codecept_data_dir( 'Tribe/Nested/Level_One/CamelcaseClassOne.php' ),
			$autoloader->get_class_path( 'Tribe\\Nested\\Level_One\\CamelcaseClassOne' )
		);

		$class_path = $autoloader->get_class_path( 'Tribe\\Nested\\Level_One\\Level_Two\\Class_Two' );
		$this->assertEquals(
			codecept_data_dir( 'Tribe/Nested/Level_One/Level_Two/Class_Two.php' ),
			$class_path
		);

		$this->assertEquals(
			codecept_data_dir( 'Tribe/Nested/Level_One/Level_Two/CamelcaseClassTwo.php' ),
			$autoloader->get_class_path( 'Tribe\\Nested\\Level_One\\Level_Two\\CamelcaseClassTwo' )
		);

		$this->assertEquals(
			codecept_data_dir( 'Tribe/Nested/Level_One/Level_Two/Level_Three/Class_Three.php' ),
			$autoloader->get_class_path( 'Tribe\\Nested\\Level_One\\Level_Two\\Level_Three\\Class_Three' )
		);

		$this->assertEquals(
			codecept_data_dir( 'Tribe/Nested/Level_One/Level_Two/Level_Three/CamelcaseClassThree.php' ),
			$autoloader->get_class_path( 'Tribe\\Nested\\Level_One\\Level_Two\\Level_Three\\CamelcaseClassThree' )
		);
	}

	/**
	 * It should correctly locate classes in camelcase namespaces
	 *
	 * @test
	 * @dataProvider cc_namespace_data_provider
	 */
	public function should_correctly_locate_classes_in_camelcase_namespaces( $prefix ) {
		$autoloader = new Autoloader();
		$autoloader->register_prefix( $prefix, codecept_data_dir( 'Tribe/CCNested' ) );

		$class_path = $autoloader->get_class_path( 'Tribe\\CCNested\\One' );
		$this->assertEquals(
			codecept_data_dir( 'Tribe/CCNested/One.php' ),
			$class_path
		);

		$this->assertEquals(
			codecept_data_dir( 'Tribe/CCNested/Class_One.php' ),
			$autoloader->get_class_path( 'Tribe\\CCNested\\Class_One' )
		);

		$this->assertEquals(
			codecept_data_dir( 'Tribe/CCNested/CCNestedSubOne/Sub_One.php' ),
			$autoloader->get_class_path( 'Tribe\\CCNested\\CCNestedSubOne\\Sub_One' )
		);

		$this->assertEquals(
			codecept_data_dir( 'Tribe/CCNested/CCNestedSubOne/SubOne.php' ),
			$autoloader->get_class_path( 'Tribe\\CCNested\\CCNestedSubOne\\SubOne' )
		);

		$this->assertEquals(
			codecept_data_dir( 'Tribe/CCNested/CCNestedSubOne/CCNestedSubTwo/Sub_Two.php' ),
			$autoloader->get_class_path( 'Tribe\\CCNested\\CCNestedSubOne\\CCNestedSubTwo\\Sub_Two' )
		);

		$this->assertEquals(
			codecept_data_dir( 'Tribe/CCNested/CCNestedSubOne/CCNestedSubTwo/SubTwo.php' ),
			$autoloader->get_class_path( 'Tribe\\CCNested\\CCNestedSubOne\\CCNestedSubTwo\\SubTwo' )
		);
	}

	public function cc_namespace_data_provider() {
		return [
			'w/ leading slash'              => [ '\\Tribe\\CCNested' ],
			'w/o leading slash'             => [ 'Tribe\\CCNested' ],
			'w/ trailing slash'             => [ 'Tribe\\CCNested\\' ],
			'w/ leading and trailing slash' => [ '\\Tribe\\CCNested\\' ],
		];
	}

	/**
	 * It should correctly get prefix by slug.
	 *
	 * @test
	 * @dataProvider cc_namespace_data_provider
	 */
	public function should_correctly_get_prefix_by_slug( $prefix ) {
		$autoloader = new Autoloader();
		$autoloader->register_prefix( $prefix, codecept_data_dir( 'Tribe/CCNested' ), 'ccnested' );

		// Confirm prefix is normalized already.
		$prefix_by_slug = $autoloader->get_prefix_by_slug( 'ccnested' );

		$this->assertEquals( trim( $prefix, '\\' ) . '\\', $prefix_by_slug );
	}
}
