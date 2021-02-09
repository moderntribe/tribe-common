<?php

namespace Tribe\Utils;

class ArrayTest extends \Codeception\TestCase\WPTestCase {
	public function list_to_array_inputs() {
		return [
			[ '', ',', [] ],
			[ ',', ',', [] ],
			[ 'foo,bar', ',', [ 'foo', 'bar' ] ],
			[ 'foo;bar', ',', [ 'foo;bar' ] ],
			[ [ 'foo', 'bar' ], ',', [ 'foo', 'bar' ] ],
			[ false, ',', [] ],
			[ null, ',', [] ],
			[ 23, ',', [ '23' ] ],
			[ '23,89,2389', ',', [ '23', '89', '2389' ] ],
			[ [ '23', '89', '2389' ], ',', [ '23', '89', '2389' ] ],
			[ '23,89,2389,,,', ',', [ '23', '89', '2389' ] ],
			[ [ '23', '89', '2389', '', '' ], ',', [ '23', '89', '2389' ] ],
			[ [ '23', '89', '2389', 'false', '' ], ',', [ '23', '89', '2389', 'false' ] ],
			[ '23, 89, 2389, false', ',', [ '23', '89', '2389', 'false' ] ],
			[ '23, 89, 2389, false, , , ', ',', [ '23', '89', '2389', 'false' ] ],
			[ 'false, 0 ,1', ',', [ 'false', '0', '1' ] ],
			[ [ false, 0, 1 ], ',', [ false, 0, 1 ] ],
		];
	}

	/**
	 * Test list_to_array
	 *
	 * @test
	 * @dataProvider list_to_array_inputs
	 */
	public function test_list_to_array( $input, $sep, $expected ) {
		$this->assertEquals( $expected, \Tribe__Utils__Array::list_to_array( $input, $sep ) );
	}

	public function map_or_discard_inputs() {
		return [
			'all-mapped'              => [
				[ 'a', 'b', 'c' ],
				[ 'a' => 'foo', 'b' => 'baz', 'c' => 'bar' ],
				[ 'foo', 'baz', 'bar' ],
				true
			],
			'some-mapped'             => [ [ 'a', 'b', 'c' ], [ 'a' => 'foo', 'b' => 'baz' ], [ 'foo', 'baz' ], true ],
			'one-mapped'              => [ [ 'a', 'b', 'c' ], [ 'a' => 'foo' ], [ 'foo' ], true ],
			'one-key-mapped'          => [ 'a', [ 'a' => 'foo', 'b' => 'baz' ], 'foo', true ],
			'one-key-not-mapped'      => [ 'd', [ 'a' => 'foo', 'b' => 'baz' ], false, false ],
			'one-key-mapped-to-false' => [ 'a', [ 'a' => false, 'b' => 'baz' ], false, true ],
		];
	}

	/**
	 * Test map_or_discard
	 *
	 * @dataProvider map_or_discard_inputs
	 */
	public function test_map_or_discard( $input, $map, $expected, $expected_found ) {
		$this->assertEquals( $expected, \Tribe__Utils__Array::map_or_discard( $input, $map, $found ) );
		$this->assertEquals( $expected_found, $found );
	}

	public function unprefix_keys() {
		return [
			'a_string'  => [ 'foo', 'foo' ],
			'null'      => [ null, null ],
			'an_object' => [ (object) [ '_foo' => 'bar' ], (object) [ '_foo' => 'bar' ] ],
			'no_prefix' => [ [ 'foo' => 'bar' ], [ 'foo' => 'bar' ] ],
			'w_prefix'  => [ [ '_foo' => 'bar' ], [ '_foo' => 'bar', 'foo' => 'bar' ] ],
			'w_prefix_nested' => [
				[ '_foo' => [ 'bar' => 23, '_baz' => 89 ] ],
				[
					'_foo' => [ 'bar' => 23, '_baz' => 89, 'baz' => 89 ],
					'foo'  => [ 'bar' => 23, '_baz' => 89, 'baz' => 89 ]
				],
				true
			],
			'w_prefix_nested_no_recursion' => [
				[ '_foo' => [ 'bar' => 23, '_baz' => 89 ] ],
				[
					'_foo' => [ 'bar' => 23, '_baz' => 89 ],
					'foo'  => [ 'bar' => 23, '_baz' => 89 ]
				]
			],
		];
}
	/**
	 * Test unprefix array keys
	 * @dataProvider unprefix_keys
	 */
	public function test_unprefix_array_keys($input, $expected, bool $recursive = false) {
		$this->assertEquals( $expected, \Tribe__Utils__Array::add_unprefixed_keys_to( $input, $recursive ) );
	}

	public function get_first_set_data_sets() {
		return [
			// $input, $indexes, $default, $expected
			'empty' => [ [], [ 'tree', 'car' ], null, null ],
			'first_element' => [ ['tree'=>'pine','animal'=>'bear'], [ 'tree', 'car' ], null, 'pine' ],
			'second_element' => [ ['animal'=>'bear','tree'=>'pine'], [ 'tree', 'car' ], null, 'pine' ],
			'first_index_set_second' => [ ['car'=>'VW Golf','tree'=>'pine'], [ 'tree', 'car' ], null, 'pine' ],
			'first_index_set_first' => [ ['car'=>'VW Golf','tree'=>'pine'], [ 'car', 'tree' ], null, 'VW Golf' ],
			'not_set_wo_default' => [ ['car'=>'VW Golf','tree'=>'pine'], [ 'one', 'two' ], null, null ],
			'not_set_w_default' => [ ['car'=>'VW Golf','tree'=>'pine'], [ 'one', 'two' ], 'default', 'default' ],
		];
	}

	/**
	 * Test get_first_set
	 * @dataProvider get_first_set_data_sets
	 */
	public function test_get_first_set( $input, $indexes, $default, $expected ) {
		$this->assertEquals( $expected, \Tribe__Utils__Array::get_first_set( $input, $indexes, $default ) );
	}

	public function filter_to_flat_scalar_associative_array_data_sets() {
		return [
			// $input, $expected
			'empty'                      => [ [], [] ],
			'all_numeric_keys'           => [ [ 'a', 'b', 'c' ], [] ],
			'all_multi_dimensional'      => [ [ 'letters' => [ 'a', 'b' ] ], [] ],
			'mostly_associative_scalar'  => [
				[ 'a' => 'apple', 'b' => 'banana', 'c' => [ 'multi' ] ],
				[ 'a' => 'apple', 'b' => 'banana' ],
			],
			'already_associative_scalar' => [
				[ 'a' => 'apple', 'b' => 'banana' ],
				[ 'a' => 'apple', 'b' => 'banana' ],
			],
		];
	}

	/**
	 * Test filter_to_flat_scalar_associative_array
	 * @dataProvider filter_to_flat_scalar_associative_array_data_sets
	 */
	public function test_filter_to_flat_scalar_associative_array( $input, $expected ) {
		$this->assertEquals( $expected, \Tribe__Utils__Array::filter_to_flat_scalar_associative_array( $input ) );
	}

	public function parse_associative_array_alias_data_sets() {
		$starter = [ 'card' => 'ace' ];

		return [
			// $original, $alias_map, $expected
			'empty'                 => [ [], [], [] ],
			'wo_alias'              => [ $starter, [], $starter ],
			'non_associative_alias' => [ $starter, [ 'ace' ], $starter ],
			'non_scalar_alias'      => [ $starter, [ [ 'ace' ] ], $starter ],
			'wo_canonical_conflict' => [
				$starter + [ 'player' => 'John' ],
				[ 'player' => 'name' ],
				$starter + [ 'name' => 'John' ],
			],
			'w_canonical_conflict'  => [
				$starter + [ 'player' => 'John', 'name' => 'Sally' ],
				[ 'player' => 'name' ],
				$starter + [ 'name' => 'Sally' ],
			],
		];
	}

	/**
	 * Test parse_associative_array_alias
	 * @dataProvider parse_associative_array_alias_data_sets
	 */
	public function test_parse_associative_array_alias( $original, $alias_map, $expected ) {
		$this->assertEquals( $expected, \Tribe__Utils__Array::parse_associative_array_alias( $original, $alias_map ) );
	}
}
