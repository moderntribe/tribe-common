<?php

namespace Tribe\Utils;

use PHPUnit\Framework\AssertionFailedError;
use Tribe__Utils__Array as Arr;

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
			'a_string'                     => [ 'foo', 'foo' ],
			'null'                         => [ null, null ],
			'an_object'                    => [ (object) [ '_foo' => 'bar' ], (object) [ '_foo' => 'bar' ] ],
			'no_prefix'                    => [ [ 'foo' => 'bar' ], [ 'foo' => 'bar' ] ],
			'w_prefix'                     => [ [ '_foo' => 'bar' ], [ '_foo' => 'bar', 'foo' => 'bar' ] ],
			'w_prefix_nested'              => [
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
	public function test_unprefix_array_keys( $input, $expected, bool $recursive = false ) {
		$this->assertEquals( $expected, \Tribe__Utils__Array::add_unprefixed_keys_to( $input, $recursive ) );
	}

	public function get_first_set_data_sets() {
		return [
			// $input, $indexes, $default, $expected
			'empty'                  => [ [], [ 'tree', 'car' ], null, null ],
			'first_element'          => [ [ 'tree' => 'pine', 'animal' => 'bear' ], [ 'tree', 'car' ], null, 'pine' ],
			'second_element'         => [ [ 'animal' => 'bear', 'tree' => 'pine' ], [ 'tree', 'car' ], null, 'pine' ],
			'first_index_set_second' => [ [ 'car' => 'VW Golf', 'tree' => 'pine' ], [ 'tree', 'car' ], null, 'pine' ],
			'first_index_set_first'  => [ [ 'car' => 'VW Golf', 'tree' => 'pine' ], [ 'car', 'tree' ], null, 'VW Golf' ],
			'not_set_wo_default'     => [ [ 'car' => 'VW Golf', 'tree' => 'pine' ], [ 'one', 'two' ], null, null ],
			'not_set_w_default'      => [ [ 'car' => 'VW Golf', 'tree' => 'pine' ], [ 'one', 'two' ], 'default', 'default' ],
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

	public function key_fitering_data_provider() {
		return [
			'empty array'                                  => [
				'input'                => [],
				'expected_numeric'     => [],
				'expected_associative' => []
			],
			'only implicit numeric keys array'             => [
				'input'                => [ 'foo', 'bar', [ 'baz_1', 'baz_2', [ 'lorem', 'dolor' ] ] ],
				'expected_numeric'     => [ 'foo', 'bar', [ 'baz_1', 'baz_2', [ 'lorem', 'dolor' ] ] ],
				'expected_associative' => [],
			],
			'mix of implicit numeric keys and string keys' => [
				'input'                => [
					'foo',
					'bar',
					[ 'baz_1', 'baz_2', [ 'lorem', 'dolor' ] ],
					'string_1' => [ 'string_1_1' => 23 ],
					[ 'string_2' => 89, 'lorem' ]
				],
				'expected_numeric'     => [ 'foo', 'bar', [ 'baz_1', 'baz_2', [ 'lorem', 'dolor' ] ], [ 'lorem' ] ],
				'expected_associative' => [ 'string_1' => [ 'string_1_1' => 23 ] ],
			],
			'explicit numeric keys only'                   => [
				'input'                => [ 23 => 'foo', 89 => 'bar', 121 => [ 1 => 'baz_1', 2 => 'baz_2', 142 => [ 3 => 'lorem', 4 => 'dolor' ] ] ],
				'expected_numeric'     => [ 23 => 'foo', 89 => 'bar', 121 => [ 1 => 'baz_1', 2 => 'baz_2', 142 => [ 3 => 'lorem', 4 => 'dolor' ] ] ],
				'expected_associative' => [],
			],
			'mix of explicit numeric keys and string keys' => [
				'input'                => [
					1          => 'foo',
					2          => 'bar',
					3          => [ 11 => 'baz_1', 12 => 'baz_2', 13 => [ 14 => 'lorem', 15 => 'dolor' ] ],
					'string_1' => [ 'string_1_1' => 23 ],
					4          => [ 'string_2' => 89, 17 => 'lorem' ]
				],
				'expected_numeric'     => [
					1 => 'foo',
					2 => 'bar',
					3 => [ 11 => 'baz_1', 12 => 'baz_2', 13 => [ 14 => 'lorem', 15 => 'dolor' ] ],
					4 => [ 17 => 'lorem' ]
				],
				'expected_associative' => [ 'string_1' => [ 'string_1_1' => 23 ] ],
			],
			'string arrays only'                           => [
				'input'                => [
					'string_1' => 'lorem',
					'string_2' => [ 'string_2_1' => 'lorem', 'string_2_2' => [ 'string_2_2_1' => 'lorem', 'string_2_2_2' => 'dolor' ] ]
				],
				'expected_numeric'     => [],
				'expected_associative' => [
					'string_1' => 'lorem',
					'string_2' => [
						'string_2_1' => 'lorem',
						'string_2_2' => [ 'string_2_2_1' => 'lorem', 'string_2_2_2' => 'dolor' ]
					]
				],
			],
		];
	}

	/**
	 * @dataProvider key_fitering_data_provider
	 */
	public function test_key_filtering( $input, $expected_numeric,$expected_associative ) {
		$this->assertEquals( $expected_numeric, Arr::remove_string_keys_recursive( $input ) );
		$this->assertEquals( $expected_associative, Arr::remove_numeric_keys_recursive( $input ) );
	}

	public function merge_wp_query_args_data_provider() {
		return [
//			'no input arrays'                         => [
//				'inputs'   => [],
//				'expected' => [],
//			],
//			'meta_query with numeric keys'            => [
//				'inputs'   => [
//					[ 'p' => 23, 'name' => 'foo', 'meta_query' => [ [ 'key' => '_key_1', 'compare' => '>', 'value' => 23 ] ] ],
//					[ 'p' => 89, 'meta_query' => [ 'relation' => 'OR', [ 'key' => '_key_1', 'compare' => '<', 'value' => 89 ] ] ],
//					[ 'name' => 'lorem', 'meta_query' => [ [ 'key' => '_key_2', 'compare' => 'EXISTS', 'value' => 'n' ] ] ],
//				],
//				'expected' => [
//					'p'          => 89,
//					'name'       => 'lorem',
//					'meta_query' => [
//						[ 'key' => '_key_1', 'compare' => '>', 'value' => 23 ],
//						'relation' => 'OR',
//						[ 'key' => '_key_1', 'compare' => '<', 'value' => 89 ],
//						[ 'key' => '_key_2', 'compare' => 'EXISTS', 'value' => 'n' ],
//					],
//				],
//			],
			'meta_query with string and numeric keys' => [
				'inputs'   => [
					[ 'p' => 23, 'name' => 'foo', 'meta_query' => [ [ 'key' => 'karma', 'compare' => '>', 'value' => 23 ] ] ],
					[
						'p'          => 89,
						'meta_query' => [
							'karma' => [
								'karma_gt_10'  => [ 'key' => 'karma', 'compare' => '>', 'value' => 10 ],
								'relation'     => 'AND',
								'karma_lt_100' => [ 'key' => 'karma', 'compare' => '>', 'value' => 10 ],
							],
							[ 'key' => 'testability', 'value' => 'n', 'compare' => 'EXISTS' ]
						],
					],
					[ 'name' => 'lorem', 'meta_query' => [ [ 'key' => '_key_2', 'compare' => 'EXISTS', 'value' => 'n' ] ] ],
					[
						'meta_query' => [ [ 'key' => 'votes', 'compare' => '<=', 'value' => 89  ] ],
					],
					[
						'meta_query' => [
							'votes' => [
								'votes_gt_10'  => [ 'key'     => 'votes', 'compare' => '>', 'value'   => 10 ],
								'relation'     => 'OR',
								'votes_lt_200' => [ 'key'     => 'votes', 'compare' => '<', 'value'   => 200 ],
							],
						],
					],
				],
				'expected' => [
					'p'          => 89,
					'name'       => 'lorem',
					'meta_query' => [
						0       => [ 'key' => 'karma', 'compare' => '>', 'value' => 23, ],
						'karma' => [
							'karma_gt_10'  => [ 'key' => 'karma', 'compare' => '>', 'value' => 10, ],
							'relation'     => 'AND',
							'karma_lt_100' => [ 'key' => 'karma', 'compare' => '>', 'value' => 10, ],
						],
						1       => [ 'key' => 'testability', 'value' => 'n', 'compare' => 'EXISTS', ],
						2       => [ 'key' => '_key_2', 'compare' => 'EXISTS', 'value' => 'n', ],
						3       => [ 'key' => 'votes', 'compare' => '<=', 'value' => 89, ],
						'votes' => [
							'votes_gt_10'  => [ 'key' => 'votes', 'compare' => '>', 'value' => 10, ],
							'relation'     => 'OR',
							'votes_lt_200' => [ 'key' => 'votes', 'compare' => '<', 'value' => 200, ],
						],
					],
				],
			]
		];
	}

	/**
	 * @dataProvider merge_wp_query_args_data_provider
	 */
	public function test_merge_wp_query_args( array $inputs, array $expected ) {
		$merged = Arr::merge_recursive_query_vars( ...$inputs );
		$this->assertEquals( $expected, $merged );
	}

	public function array_visit_recursive_data_provider() {
		$throw_on_call = static function () {
			throw new AssertionFailedError( 'This function should not be called' );
		};

		$drop_even = static function ( $key, $value ) {
			if ( $value % 2 === 0 ) {
				return false;
			}
		};

		return [
			'empty input'                => [
				'input'    => [],
				'visitor'  => $throw_on_call,
				'expected' => [],
			],
			'drop even values'           => [
				'input'    => [ 1, 2, 3, 4, 5, 6 ],
				'visitor'  => $drop_even,
				'expected' => [ 1, 3, 5 ],
			],
			'recursive drop even values' => [
				'input'    => [ 1, 2, 3, 4, 5, 6, [ 1, 2, 3 ], [ 4, 5, 6 ] ],
				'visitor'  => $drop_even,
				'expected' => [ 1, 3, 5, [ 1, 2 => 3 ], [ 1 => 5 ] ],
			],
			'recursive visit'            => [
				'input'    => [
					'lorem' => [
						1,
						2,
						3,
					],
					[
						'foo',
						'baz',
						'bar',
					],
					'dolor' => [
						'sit' => [ 'woot' => 23, 'waz' ]
					],
				],
				'visitor'  => static function ( $key, $value ) {
					$new_key   = is_numeric( $key ) ? 'n_' . $key : 's_' . $key;
					if ( ! is_array( $value ) ) {
						$new_value = is_string( $value ) ? 'is_string' : 'not_string';
					} else {
						$new_value = $value;
					}

					if ( $key === 'foo' ) {
						return false;
					}

					return [ $new_key, $new_value ];
				},
				'expected' => [
					's_lorem' =>
						[
							'n_0' => 'not_string',
							'n_1' => 'not_string',
							'n_2' => 'not_string',
						],
					'n_0'     =>
						[
							'n_0' => 'is_string',
							'n_1' => 'is_string',
							'n_2' => 'is_string',
						],
					's_dolor' =>
						[
							's_sit' =>
								[
									's_woot' => 'not_string',
									'n_0'    => 'is_string',
								],
						],
				],
			],
		];
	}

	/**
	 * @dataProvider array_visit_recursive_data_provider
	 */
	public function test_array_visit_recursive( $input, $visitor, $expected ) {
		$this->assertEqualSets( $expected, Arr::array_visit_recursive( $input, $visitor ) );
	}
}
