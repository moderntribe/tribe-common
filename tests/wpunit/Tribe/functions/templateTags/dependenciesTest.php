<?php
namespace Tribe\functions\templateTags;

class dependenciesTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @test
	 * It should not fail with empty set.
	 *
	 */
	public function it_should_not_fail_with_empty_set() {
		$deps = [];

		$dependencies = tribe_format_field_dependency( $deps );

		$this->assertEmpty( $dependencies );
	}

	/**
	 * @test
	 * It should return correct attributes for boolean.
	 *
	 */
	public function it_should_return_correct_attributes_for_boolean() {
		$deps = [
			'id' => 1,
			'is-not-empty' => true
		];

		$dependencies = tribe_format_field_dependency( $deps );

		// Note the leading space - this is intentional, so we're not trim()-ing it to ensure it gets added!
		$this->assertEquals( ' data-depends="#1" data-condition-is-not-empty', $dependencies );
	}

	/**
	 * @test
	 * It should return correct attributes for int.
	 *
	 */
	public function it_should_return_correct_attributes_for_int() {
		$deps = [
			'id' => 2,
			'is' => 1
		];
		$dependencies = tribe_format_field_dependency( $deps );

		$this->assertEquals( ' data-depends="#2" data-condition="1"', $dependencies );
	}

	/**
	 * @test
	 * It should return correct attributes for string.
	 *
	 */
	public function it_should_return_correct_attributes_for_string() {
		$deps = [
			'id' => 3,
			'is' => 'yes'
		];
		$dependencies = tribe_format_field_dependency( $deps );

		$this->assertEquals( ' data-depends="#3" data-condition="yes"', $dependencies );
	}

}
