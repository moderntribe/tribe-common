<?php
namespace Tribe\Repository;

require_once  __DIR__ . '/ReadTestBase.php';

class ReadTest extends ReadTestBase {

	/**
	 * It should return all posts (non paginated) by default
	 *
	 * @test
	 */
	public function should_return_all_posts_by_default() {
		$ids = $this->factory()->post->create_many( 5, [ 'post_type' => 'book' ] );
		update_option( 'posts_per_page', 2 );

		$this->assertEquals( 5, $this->repository()->found() );
		$this->assertEquals( 5, $this->repository()->count() );
		$this->assertCount( 5, $this->repository()->all() );
		$this->assertEquals( reset( $ids ), $this->repository()->first()->ID );
		$this->assertEquals( end( $ids ), $this->repository()->last()->ID );
		$this->assertEquals( $ids[1], $this->repository()->nth( 2 )->ID );
		$this->assertEquals( $ids[2], $this->repository()->nth( 3 )->ID );
		$this->assertNull( $this->repository()->nth( 23 ) );
	}

	/**
	 * It should allow offsetting the results
	 *
	 * @test
	 */
	public function should_allow_offsetting_the_results() {
		$ids = $this->factory()->post->create_many( 5, [ 'post_type' => 'book' ] );
		update_option( 'posts_per_page', 2 );

		$this->assertEquals( 5, $this->repository()->offset( 2 )->found() );
		$this->assertEquals( 3, $this->repository()->offset( 2 )->count() );
		$this->assertCount( 3, $this->repository()->offset( 2 )->all() );
		$this->assertEquals( $ids[2], $this->repository()->offset( 2 )->first()->ID );
		$this->assertEquals( end( $ids ), $this->repository()->offset( 2 )->last()->ID );
		$this->assertEquals( $ids[3], $this->repository()->offset( 2 )->nth( 2 )->ID );
		$this->assertEquals( $ids[4], $this->repository()->offset( 2 )->nth( 3 )->ID );
		$this->assertNull( $this->repository()->nth( 23 ) );
	}

	/**
	 * It should allow paginating results
	 *
	 * @test
	 */
	public function should_allow_paginating_results() {
		$ids = $this->factory()->post->create_many( 5, [ 'post_type' => 'book' ] );
		update_option( 'posts_per_page', 2 );

		$page_1 = $this->repository()
		               ->per_page( 3 )
		               ->page( 1 );

		$this->assertEquals( 5, $page_1->found() );
		$this->assertEquals( 3, $page_1->count() );
		$this->assertCount( 3, $page_1->all() );
		$this->assertEquals( $ids[0], $page_1->first()->ID );
		$this->assertEquals( $ids[2], $page_1->last()->ID );
		$this->assertEquals( $ids[0], $page_1->nth( 1 )->ID );
		$this->assertEquals( $ids[1], $page_1->nth( 2 )->ID );
		$this->assertEquals( $ids[2], $page_1->nth( 3 )->ID );
		$this->assertNull( $page_1->nth( 4 ) );

		$page_2 = $this->repository()
		               ->per_page( 3 )
		               ->page( 2 );

		$this->assertEquals( 5, $page_2->found() );
		$this->assertEquals( 2, $page_2->count() );
		$this->assertCount( 2, $page_2->all() );
		$this->assertEquals( $ids[3], $page_2->first()->ID );
		$this->assertEquals( $ids[4], $page_2->last()->ID );
		$this->assertEquals( $ids[3], $page_2->nth( 1 )->ID );
		$this->assertEquals( $ids[4], $page_2->nth( 2 )->ID );
		$this->assertNull( $page_2->nth( 3 ) );
	}

	/**
	 * It should allow paginating results with found after query
	 *
	 * @test
	 */
	public function should_allow_paginating_results_with_found_after_query() {
		$ids = $this->factory()->post->create_many( 5, [ 'post_type' => 'book' ] );
		update_option( 'posts_per_page', 2 );

		$page_1 = $this->repository()
		               ->per_page( 3 )
		               ->page( 1 );

		$this->assertCount( 3, $page_1->all() );
		$this->assertEquals( 5, $page_1->found() );
		$this->assertEquals( 3, $page_1->count() );

		$page_2 = $this->repository()
		               ->per_page( 3 )
		               ->page( 2 );

		$this->assertCount( 2, $page_2->all() );
		$this->assertEquals( 5, $page_2->found() );
		$this->assertEquals( 2, $page_2->count() );
	}

	/**
	 * It should respect the fields setting
	 *
	 * @test
	 */
	public function should_respect_the_fields_setting() {
		$ids = $this->factory()->post->create_many( 5, [ 'post_type' => 'book' ] );
		update_option( 'posts_per_page', 2 );

		$page_1 = $this->repository()
		               ->per_page( 3 )
		               ->page( 1 )
		               ->fields( 'ids' );

		$this->assertEquals( 5, $page_1->found() );
		$this->assertEquals( 3, $page_1->count() );
		$this->assertCount( 3, $page_1->all() );
		$this->assertEquals( $ids[0], $page_1->first() );
		$this->assertEquals( $ids[2], $page_1->last() );
		$this->assertEquals( $ids[0], $page_1->nth( 1 ) );
		$this->assertEquals( $ids[1], $page_1->nth( 2 ) );
		$this->assertEquals( $ids[2], $page_1->nth( 3 ) );
		$this->assertNull( $page_1->nth( 4 ) );

		$page_2 = $this->repository()
		               ->per_page( 3 )
		               ->page( 2 )
		               ->fields( 'ids' );

		$this->assertEquals( 5, $page_2->found() );
		$this->assertEquals( 2, $page_2->count() );
		$this->assertCount( 2, $page_2->all() );
		$this->assertEquals( $ids[3], $page_2->first() );
		$this->assertEquals( $ids[4], $page_2->last() );
		$this->assertEquals( $ids[3], $page_2->nth( 1 ) );
		$this->assertEquals( $ids[4], $page_2->nth( 2 ) );
		$this->assertNull( $page_2->nth( 3 ) );
	}

	/**
	 * It should allow getting posts by title
	 *
	 * @test
	 */
	public function should_allow_getting_posts_by_title() {
		$titles = [ 'one', 'one two', 'one two three' ];
		$posts  = array_map( function ( $title ) {
			return $this->factory()->post->create( [ 'post_type' => 'book', 'post_title' => $title ] );
		}, $titles );

		$this->assertEquals(
			$posts[1],
			$this->repository()->fields( 'ids' )->by( 'title', 'one two' )->first()
		);
		$this->assertEquals(
			[ $posts[1], $posts[2] ],
			$this->repository()->fields( 'ids' )->by( 'title_like', 'two' )->all()
		);
		$this->assertEquals(
			$posts,
			$this->repository()->by( 'title_like', 'one' )->fields( 'ids' )->all()
		);
	}

	/**
	 * It should allow getting posts by content
	 *
	 * @test
	 */
	public function should_allow_getting_posts_by_content() {
		$contents = [ 'one', 'one two', 'one two three', 'foo bar' ];
		$posts    = array_map( function ( $content ) {
			return $this->factory()->post->create( [ 'post_type' => 'book', 'post_content' => $content ] );
		}, $contents );

		$this->assertEquals(
			[ $posts[1], $posts[2] ],
			$this->repository()->fields( 'ids' )->by( 'content', 'two' )->all()
		);
		$this->assertEquals(
			$posts[3],
			$this->repository()->fields( 'ids' )->by( 'content', 'bar' )->first()
		);
	}

	/**
	 * It should allow searching posts
	 *
	 * @test
	 */
	public function should_allow_searching_posts() {
		$ids [] = $this->factory()->post->create( [
			'post_type'    => 'book',
			'post_title'   => 'One',
			'post_content' => 'lorem'
		] );
		$ids [] = $this->factory()->post->create( [
			'post_type'    => 'book',
			'post_title'   => 'two',
			'post_content' => 'lorem one'
		] );
		$ids [] = $this->factory()->post->create( [
			'post_type'    => 'book',
			'post_title'   => 'three',
			'post_content' => 'lorem two'
		] );

		$this->assertEquals(
			[ $ids[0], $ids[1] ],
			$this->repository()->fields( 'ids' )->search( 'one' )->all()
		);
	}

	/**
	 * It should allow getting posts by meta
	 *
	 * @test
	 */
	public function should_allow_getting_posts_by_meta() {
		$post_1 = $this->factory()->post->create( [
			'post_type'  => 'book',
			'meta_input' => [
				'common'        => 'common_1',
				'number_meta'   => '1',
				'string_meta'   => 'foo',
				'interval_meta' => 'foo',
				'woot'          => 'zap',
			]
		] );
		$post_2 = $this->factory()->post->create( [
			'post_type'  => 'book',
			'meta_input' => [
				'common'        => 'common_2',
				'number_meta'   => '23',
				'string_meta'   => 'bar',
				'interval_meta' => 'bar',
			]
		] );

		$this->assertEquals( [ $post_1 ], $this->repository()->fields( 'ids' )->by( 'meta', 'common', 'common_1' )->all() );
		$this->assertEquals( [ $post_1 ], $this->repository()->fields( 'ids' )->by( 'meta_equals', 'common', 'common_1' )->all() );
		$this->assertEquals( [ $post_1 ], $this->repository()->fields( 'ids' )->by( 'meta_not_equals', 'common', 'common_2' )->all() );
		$this->assertEquals( [ $post_2 ], $this->repository()->fields( 'ids' )->by( 'meta_gt', 'number_meta', 12 )->all() );
		$this->assertEquals( [ $post_2 ], $this->repository()->fields( 'ids' )->by( 'meta_greater_than', 'number_meta', 12 )->all() );
		$this->assertEquals( [
			$post_1,
			$post_2
		], $this->repository()->fields( 'ids' )->by( 'meta_gte', 'number_meta', '1' )->all() );
		$this->assertEquals( [
			$post_1,
			$post_2
		], $this->repository()->fields( 'ids' )->by( 'meta_greater_than_or_equal', 'number_meta', '1' )->all() );
		$this->assertEquals( [ $post_1 ], $this->repository()->fields( 'ids' )->by( 'meta_like', 'string_meta', 'fo' )->all() );
		$this->assertEquals( [ $post_2 ], $this->repository()->fields( 'ids' )->by( 'meta_not_like', 'string_meta', 'fo' )->all() );
		$this->assertEquals( [ $post_1 ], $this->repository()->fields( 'ids' )->by( 'meta_lt', 'number_meta', 12 )->all() );
		$this->assertEquals( [ $post_1 ], $this->repository()->fields( 'ids' )->by( 'meta_less_than', 'number_meta', '12' )->all() );
		$this->assertEquals( [ $post_1 ], $this->repository()->fields( 'ids' )->by( 'meta_lte', 'number_meta', 1 )->all() );
		$this->assertEquals( [ $post_1 ], $this->repository()->fields( 'ids' )->by( 'meta_less_than_or_equal', 'number_meta', 1 )->all() );
		$this->assertEquals( [
			$post_1,
			$post_2
		], $this->repository()->fields( 'ids' )->by( 'meta_in', 'interval_meta', [ 'foo', 'bar' ] )->all() );
		$this->assertEquals( [ $post_2 ], $this->repository()->fields( 'ids' )->by( 'meta_not_in', 'interval_meta', [
			'foo',
			'baz'
		] )->all() );
		$this->assertEquals( [ $post_2 ], $this->repository()->fields( 'ids' )->by( 'meta_between', 'number_meta', [
			18,
			25
		] )->all() );
		$this->assertEquals( [ $post_1 ], $this->repository()->fields( 'ids' )->by( 'meta_not_between', 'number_meta', [
			18,
			25
		] )->all() );
		$this->assertEquals( [ $post_1 ], $this->repository()->fields( 'ids' )->by( 'meta_exists', 'woot' )->all() );
		$this->assertEquals( [ $post_2 ], $this->repository()->fields( 'ids' )->by( 'meta_not_exists', 'woot' )->all() );
		$this->assertEquals( [ $post_2 ], $this->repository()->fields( 'ids' )->by( 'meta_regexp', 'string_meta', '^b.*' )->all() );
		$this->assertEquals( [ $post_2 ], $this->repository()->fields( 'ids' )->by( 'meta_regexp', 'string_meta', '/^b.*/' )->all() );
		$this->assertEquals( [ $post_2 ], $this->repository()->fields( 'ids' )->by( 'meta_equals_regexp', 'string_meta', '^b.*' )->all() );
		$this->assertEquals( [ $post_2 ], $this->repository()->fields( 'ids' )->by( 'meta_equals_regexp', 'string_meta', '/^b.*/' )->all() );
		$this->assertEquals( [ $post_1 ], $this->repository()->fields( 'ids' )->by( 'meta_not_regexp', 'string_meta', '^b.*' )->all() );
		$this->assertEquals( [ $post_1 ], $this->repository()->fields( 'ids' )->by( 'meta_not_regexp', 'string_meta', '/^b.*/' )->all() );
		$this->assertEquals( [ $post_1 ], $this->repository()->fields( 'ids' )->by( 'meta_not_equals_regexp', 'string_meta', '^b.*' )->all() );
		$this->assertEquals( [ $post_1 ], $this->repository()->fields( 'ids' )->by( 'meta_not_equals_regexp', 'string_meta', '/^b.*/' )->all() );

		// Test regexp with regexp_or_like
		$this->assertEquals( [ $post_2 ], $this->repository()->fields( 'ids' )->by( 'meta_regexp_or_like', 'string_meta', '/^b.*/' )->all() );
		$this->assertEquals( [ $post_2 ], $this->repository()->fields( 'ids' )->by( 'meta_equals_regexp_or_like', 'string_meta', '/^b.*/' )->all() );
		$this->assertEquals( [ $post_1 ], $this->repository()->fields( 'ids' )->by( 'meta_not_regexp_or_like', 'string_meta', '/^b.*/' )->all() );
		$this->assertEquals( [ $post_1 ], $this->repository()->fields( 'ids' )->by( 'meta_not_equals_regexp_or_like', 'string_meta', '/^b.*/' )->all() );

		// Test regexp with not_regexp_or_like
		$this->assertEquals( [ $post_1 ], $this->repository()->fields( 'ids' )->by( 'meta_regexp_or_like', 'string_meta', 'fo' )->all() );
		$this->assertEquals( [ $post_1 ], $this->repository()->fields( 'ids' )->by( 'meta_equals_regexp_or_like', 'string_meta', 'fo' )->all() );
		$this->assertEquals( [ $post_2 ], $this->repository()->fields( 'ids' )->by( 'meta_not_regexp_or_like', 'string_meta', 'fo' )->all() );
		$this->assertEquals( [ $post_2 ], $this->repository()->fields( 'ids' )->by( 'meta_not_equals_regexp_or_like', 'string_meta', 'fo' )->all() );
	}

	/**
	 * It should allow getting posts by simple meta schemas
	 *
	 * @test
	 */
	public function should_allow_getting_posts_by_simple_meta_schemas() {
		$post_1 = $this->factory()->post->create( [
			'post_type'  => 'book',
			'meta_input' => [
				'common'        => 'common_1',
				'number_meta'   => '1',
				'string_meta'   => 'foo',
				'interval_meta' => 'foo',
				'woot'          => 'zap',
			]
		] );
		$post_2 = $this->factory()->post->create( [
			'post_type'  => 'book',
			'meta_input' => [
				'common'        => 'common_2',
				'number_meta'   => '23',
				'string_meta'   => 'bar',
				'interval_meta' => 'bar',
			]
		] );

		// Test simple meta schema LIKE or REGEXP (meta_regexp_or_like).
		$repository = $this->repository();
		$repository->add_simple_meta_schema_entry( 'test_meta_regexp_or_like_schema', 'string_meta' );
		$this->assertEquals( [ $post_2 ], $repository->fields( 'ids' )->by( 'test_meta_regexp_or_like_schema', '/^b.*/' )->all() );

		$repository = $this->repository();
		$repository->add_simple_meta_schema_entry( 'test_meta_regexp_or_like_schema', 'string_meta' );
		$this->assertEquals( [ $post_1 ], $repository->fields( 'ids' )->by( 'test_meta_regexp_or_like_schema', 'fo' )->all() );

		// Test simple meta schema equals (meta).
		$repository = $this->repository();
		$repository->add_simple_meta_schema_entry( 'test_meta_schema', 'string_meta', 'meta' );
		$this->assertEquals( [ $post_1 ], $repository->fields( 'ids' )->by( 'test_meta_schema', 'foo' )->all() );

		$repository = $this->repository();
		$repository->add_simple_meta_schema_entry( 'test_meta_schema', 'string_meta', 'meta' );
		$this->assertEquals( [], $repository->fields( 'ids' )->by( 'test_meta_schema', 'fo' )->all() );

		// Test simple meta schema support with where_multi.
		$repository = $this->repository();
		$repository->add_simple_meta_schema_entry( 'test_meta_schema', 'string_meta' );
		$repository->add_simple_meta_schema_entry( 'test_other_meta_schema', 'interval_meta' );
		$this->assertEquals( [ $post_1 ], $repository->fields( 'ids' )->where_multi( [ 'test_meta_schema', 'test_other_meta_schema' ], 'LIKE', 'foo' )->all() );

		$repository = $this->repository();
		$repository->add_simple_meta_schema_entry( 'test_meta_schema', 'string_meta' );
		$repository->add_simple_meta_schema_entry( 'test_other_meta_schema', 'interval_meta' );
		$this->assertEquals( [], $repository->fields( 'ids' )->where_multi( [ 'test_meta_schema', 'test_other_meta_schema' ], '=', 'food' )->all() );

		$repository = $this->repository();
		$repository->add_simple_meta_schema_entry( 'test_meta_schema', 'string_meta' );
		$repository->add_simple_meta_schema_entry( 'test_other_meta_schema', 'interval_meta' );
		$this->assertEquals( [ $post_1 ], $repository->fields( 'ids' )->where_multi( [ 'test_meta_schema', 'test_other_meta_schema' ], 'LIKE', 'fo' )->all() );

		$repository = $this->repository();
		$repository->add_simple_meta_schema_entry( 'test_meta_schema', 'string_meta' );
		$repository->add_simple_meta_schema_entry( 'test_other_meta_schema', 'interval_meta' );
		$this->assertEquals( [], $repository->fields( 'ids' )->where_multi( [ 'test_meta_schema', 'test_other_meta_schema' ], '=', 'fun' )->all() );
	}

	/**
	 * It should allow getting posts by simple tax schemas
	 *
	 * @test
	 */
	public function should_allow_getting_posts_by_simple_tax_schemas() {
		// needed to assign terms
		wp_set_current_user( $this->factory()->user->create( [ 'role' => 'administrator' ] ) );

		$tax = 'genre';

		$fiction     = $this->factory()->term->create( [
			'taxonomy' => $tax,
			'name'     => 'fiction',
			'slug'     => 'fict',
		] );
		$history     = $this->factory()->term->create( [
			'taxonomy' => $tax,
			'name'     => 'history',
			'slug'     => 'hist',
		] );
		$non_fiction = $this->factory()->term->create( [
			'taxonomy' => $tax,
			'name'     => 'non-fiction',
			'slug'     => 'non-fict',
		] );
		$post_1      = $this->factory()->post->create( [
			'post_type' => 'book',
			'tax_input' => [
				$tax => [ 'fiction' ],
			],
		] );
		$post_2      = $this->factory()->post->create( [
			'post_type' => 'book',
			'tax_input' => [
				$tax => [ 'non-fiction', 'history' ],
			],
		] );
		$post_3      = $this->factory()->post->create( [
			'post_type' => 'book',
			'tax_input' => [
				$tax => [ 'non-fiction' ],
			],
		] );
		$post_4      = $this->factory()->post->create( [ 'post_type' => 'book' ] );

		$term_fiction     = get_term( $fiction );
		$term_history     = get_term( $history );
		$term_non_fiction = get_term( $non_fiction );

		// Test simple tax schema (term_in).

		// Term ID
		$repository = $this->repository();
		$repository->add_simple_tax_schema_entry( 'test_tax_term_in_schema', $tax );
		$this->assertEquals( [ $post_1 ], $repository->fields( 'ids' )->by( 'test_tax_term_in_schema', $term_fiction->term_id )->all() );

		// Term slug
		$repository = $this->repository();
		$repository->add_simple_tax_schema_entry( 'test_tax_term_in_schema', $tax );
		$this->assertEquals( [ $post_1 ], $repository->fields( 'ids' )->by( 'test_tax_term_in_schema', $term_fiction->slug )->all() );

		// Term object
		$repository = $this->repository();
		$repository->add_simple_tax_schema_entry( 'test_tax_term_in_schema', $tax );
		$this->assertEquals( [ $post_1 ], $repository->fields( 'ids' )->by( 'test_tax_term_in_schema', $term_fiction )->all() );

		// Term array (mixed types)
		$repository = $this->repository();
		$repository->add_simple_tax_schema_entry( 'test_tax_term_in_schema', $tax );
		$this->assertEquals( [ $post_1, $post_2 ], $repository->fields( 'ids' )->by( 'test_tax_term_in_schema', [ $term_fiction->term_id, $term_history ] )->all() );

		// Test simple tax schema using term_not_in.

		// Term ID
		$repository = $this->repository();
		$repository->add_simple_tax_schema_entry( 'test_tax_term_not_in_schema', $tax, 'term_not_in' );
		$this->assertEquals( [ $post_2, $post_3, $post_4 ], $repository->fields( 'ids' )->by( 'test_tax_term_not_in_schema', $term_fiction->term_id )->all() );

		// Term slug
		$repository = $this->repository();
		$repository->add_simple_tax_schema_entry( 'test_tax_term_not_in_schema', $tax, 'term_not_in' );
		$this->assertEquals( [ $post_2, $post_3, $post_4 ], $repository->fields( 'ids' )->by( 'test_tax_term_not_in_schema', $term_fiction->slug )->all() );

		// Term object
		$repository = $this->repository();
		$repository->add_simple_tax_schema_entry( 'test_tax_term_not_in_schema', $tax, 'term_not_in' );
		$this->assertEquals( [ $post_2, $post_3, $post_4 ], $repository->fields( 'ids' )->by( 'test_tax_term_not_in_schema', $term_fiction )->all() );

		// Term array (mixed types)
		$repository = $this->repository();
		$repository->add_simple_tax_schema_entry( 'test_tax_term_not_in_schema', $tax, 'term_not_in' );
		$this->assertEquals( [ $post_3, $post_4 ], $repository->fields( 'ids' )->by( 'test_tax_term_not_in_schema', [ $term_fiction->term_id, $term_history ] )->all() );

		// Test simple tax schema using term_and.

		$repository = $this->repository();
		$repository->add_simple_tax_schema_entry( 'test_tax_term_and_schema', $tax, 'term_and' );
		$this->assertEquals( [ $post_2 ], $repository->fields( 'ids' )->by( 'test_tax_term_and_schema', [ $term_non_fiction->term_id, $term_history ] )->all() );

		$repository = $this->repository();
		$repository->add_simple_tax_schema_entry( 'test_tax_term_and_schema', $tax, 'term_and' );
		$this->assertEquals( [], $repository->fields( 'ids' )->by( 'test_tax_term_and_schema', [ $term_fiction->term_id, $term_history ] )->all() );

		// Test simple tax schema with where_multi.

		// Term slug
		$repository = $this->repository();
		$repository->add_simple_tax_schema_entry( 'test_tax_schema', $tax );
		$repository->add_simple_tax_schema_entry( 'test_category_schema', 'category' );
		$this->assertEquals( [ $post_1 ], $repository->fields( 'ids' )->where_multi( [ 'test_tax_schema', 'test_category_schema' ], '=', $term_fiction->slug )->all() );
	}

	/**
	 * It should allow getting posts by menu_order
	 *
	 * @test
	 */
	public function should_allow_getting_posts_by_menu_order() {
		$post_1 = $this->factory()->post->create( [
			'post_type'  => 'book',
			'menu_order' => 0,
		] );
		$post_2 = $this->factory()->post->create( [
			'post_type'  => 'book',
			'menu_order' => 1,
		] );
		$post_3 = $this->factory()->post->create( [
			'post_type'  => 'book',
			'menu_order' => 2,
		] );
		$post_4 = $this->factory()->post->create( [ 'post_type' => 'book' ] );

		$this->assertEquals( [ $post_1, $post_4 ], $this->repository()->fields( 'ids' )->by( 'menu_order', 0 )->all() );
		$this->assertEquals( [ $post_2 ], $this->repository()->fields( 'ids' )->by( 'menu_order', 1 )->all() );
	}

	/**
	 * It should allow getting posts by taxonomy terms
	 *
	 * @test
	 */
	public function should_allow_getting_posts_by_taxonomy_terms() {
		// needed to assign terms
		wp_set_current_user( $this->factory()->user->create( [ 'role' => 'administrator' ] ) );

		$fiction     = $this->factory()->term->create( [
			'taxonomy' => 'genre',
			'name'     => 'fiction',
			'slug'     => 'fict'
		] );
		$history     = $this->factory()->term->create( [
			'taxonomy' => 'genre',
			'name'     => 'history',
			'slug'     => 'hist'
		] );
		$non_fiction = $this->factory()->term->create( [
			'taxonomy' => 'genre',
			'name'     => 'non-fiction',
			'slug'     => 'non-fict'
		] );
		$post_1      = $this->factory()->post->create( [
			'post_type' => 'book',
			'tax_input' => [ 'genre' => [ 'fiction' ] ]
		] );
		$post_2      = $this->factory()->post->create( [
			'post_type' => 'book',
			'tax_input' => [ 'genre' => [ 'non-fiction', 'history' ] ]
		] );
		$post_3      = $this->factory()->post->create( [
			'post_type' => 'book',
			'tax_input' => [ 'genre' => [ 'non-fiction' ] ]
		] );
		$post_4      = $this->factory()->post->create( [ 'post_type' => 'book' ] );

		$tax = 'genre';

		$this->assertEquals( [
			$post_1,
			$post_2,
			$post_3
		], $this->repository()->fields( 'ids' )->by( 'taxonomy_exists', $tax )->all() );
		$this->assertEquals( [
			$post_4
		], $this->repository()->fields( 'ids' )->by( 'taxonomy_not_exists', $tax )->all() );
		$this->assertEquals( [
			$post_1,
			$post_2,
		], $this->repository()->fields( 'ids' )->by( 'term_id_in', $tax, [ $fiction, $history ] )->all() );
		$this->assertEquals( [
			$post_3,
			$post_4,
		], $this->repository()->fields( 'ids' )->by( 'term_id_not_in', $tax, [ $fiction, $history ] )->all() );
		$this->assertEquals( [
			$post_2,
		], $this->repository()->fields( 'ids' )->by( 'term_id_and', $tax, [ $non_fiction, $history ] )->all() );
		$this->assertEquals( [
			$post_1,
			$post_2,
		], $this->repository()->fields( 'ids' )->by( 'term_name_in', $tax, [ 'fiction', 'history' ] )->all() );
		$this->assertEquals( [
			$post_3,
			$post_4,
		], $this->repository()->fields( 'ids' )->by( 'term_name_not_in', $tax, [ 'fiction', 'history' ] )->all() );
		$this->assertEquals( [
			$post_2,
		], $this->repository()->fields( 'ids' )->by( 'term_name_and', $tax, [ 'non-fiction', 'history' ] )->all() );
		$this->assertEquals( [
			$post_1,
			$post_2
		], $this->repository()->fields( 'ids' )->by( 'term_slug_in', $tax, [ 'fict', 'hist' ] )->all() );
		$this->assertEquals( [
			$post_3,
			$post_4,
		], $this->repository()->fields( 'ids' )->by( 'term_slug_not_in', $tax, [ 'fict', 'hist' ] )->all() );
		$this->assertEquals( [
			$post_2,
		], $this->repository()->fields( 'ids' )->by( 'term_slug_and', $tax, [ 'non-fict', 'hist' ] )->all() );
	}

	/**
	 * It should allow selecting posts by date
	 *
	 * @test
	 */
	public function should_allow_selecting_posts_by_date() {
		$tz_string = 'Asia/Tokyo';
		update_option( 'timezone_string', $tz_string );
		wp_set_current_user( $this->factory()->user->create( [ 'role' => 'administrator' ] ) );

		$tz           = new \DateTimeZone( $tz_string );
		$a_week_ago   = new \DateTime( '-1 week', $tz );
		$two_days_ago = new \DateTime( '-2 days', $tz );
		$an_hour_ago  = new \DateTime( '-1 hour', $tz );
		$in_a_week    = new \DateTime( '+1 week', $tz );

		// create posts using the timezone-localized `post_date`
		$past_post   = $this->factory()->post->create( [
			'post_type' => 'book',
			'post_date' => $a_week_ago->format( 'Y-m-d H:i:s' )
		] );
		$recent_post = $this->factory()->post->create( [
			'post_type' => 'book',
			'post_date' => $an_hour_ago->format( 'Y-m-d H:i:s' )
		] );
		$future_post = $this->factory()->post->create( [
			'post_type'   => 'book',
			'post_date'   => $in_a_week->format( 'Y-m-d H:i:s' ),
			'post_status' => 'future'
		] );

		$string_date = '-2 days';
		$date        = $two_days_ago->format( 'Y-m-d H:i:s' );
		$date_gmt    = $two_days_ago
			->setTimezone( new \DateTimeZone( 'UTC' ) )
			->format( 'Y-m-d H:i:s' );

		codecept_debug( 'Setup: ' . json_encode( [
				'system_timezone' => date_default_timezone_get(),
				'wp_timezone'     => get_option( 'timezone_string' ),
				'past_post'       => [
					'date'     => get_post( $past_post )->post_date,
					'date_gmt' => get_post( $past_post )->post_date_gmt,
				],
				'recent_post'     => [
					'date'     => get_post( $recent_post )->post_date,
					'date_gmt' => get_post( $recent_post )->post_date_gmt,
				],
				'future_post'     => [
					'date'     => get_post( $future_post )->post_date,
					'date_gmt' => get_post( $future_post )->post_date_gmt,
				],
				'a_week_ago'      => $a_week_ago->format( 'Y-m-d H:i:s' ),
				'an_hour_ago'     => $an_hour_ago->format( 'Y-m-d H:i:s' ),
				'in_a_week'       => $in_a_week->format( 'Y-m-d H:i:s' ),
				'string_date'     => $string_date,
				'date'            => $date,
				'date_gmt'        => $date_gmt,
			], JSON_PRETTY_PRINT ) );

		$this->assertEquals( [
			$recent_post,
			$future_post,
		], $this->repository()->fields( 'ids' )->by( 'date', $date )->by( 'post_status', 'any' )->all() );
		$this->assertEquals( [
			$recent_post,
			$future_post,
		], $this->repository()->fields( 'ids' )->by( 'after_date', $string_date )->by( 'post_status', 'any' )->all() );
		$this->assertEquals( [
			$past_post,
		], $this->repository()->fields( 'ids' )->by( 'before_date', $date )->by( 'post_status', 'any' )->all() );
		$this->assertEquals( [
			$recent_post,
			$future_post,
		], $this->repository()->fields( 'ids' )->by( 'date_gmt', $string_date )->by( 'post_status', 'any' )->all() );
		$this->assertEquals( [
			$recent_post,
			$future_post,
		], $this->repository()->fields( 'ids' )->by( 'after_date_gmt', $date_gmt )->by( 'post_status', 'any' )->all() );
		$this->assertEquals( [
			$past_post,
		], $this->repository()->fields( 'ids' )->by( 'before_date_gmt', $string_date )->by( 'post_status', 'any' )->all() );
	}

	/**
	 * It should allow fetching posts by status
	 *
	 * @test
	 */
	public function should_allow_fetching_posts_by_status() {
		wp_set_current_user( $this->factory()->user->create( [ 'role' => 'administrator' ] ) );

		$in_a_week = date( 'Y-m-d H:i:s', strtotime( '+1 week' ) );
		$draft     = $this->factory()->post->create( [ 'post_type' => 'book', 'post_status' => 'draft' ] );
		$published = $this->factory()->post->create( [ 'post_type' => 'book' ] );
		$future    = $this->factory()->post->create( [
			'post_type'   => 'book',
			'post_date'   => $in_a_week,
			'post_status' => 'future'
		] );

		$repository = $this->repository()->fields( 'ids' );

		$this->assertEquals( [ $published ], $repository->by( 'status', 'publish' )->all() );
		$this->assertEquals( [ $published, $future ], $repository->by( 'status', [
			'publish',
			'future'
		] )->all() );
		$this->assertEquals( [ $draft, $published, $future ], $repository->by( 'status', 'any' )->all() );
	}

	/**
	 * It should not accept WP_Query arguments with multiple arguments that aren't filters.
	 *
	 * @test
	 */
	public function should_not_accept_wp_query_arguments_with_multiple_arguments_that_arent_filters() {
		$repository = $this->repository();
		$all_ids    = $this->factory()->post->create_many( 5, [ 'post_type' => 'book' ] );

		$results = $repository->by( 'this_filter_does_not_exist', 1, 2, 3 )->by( 'post__in', $all_ids )->get_ids();

		$wp_query = $repository->get_query();

		$this->assertArrayNotHasKey( 'this_filter_does_not_exist', $wp_query->query_vars );
		$this->assertCount( 0, $results );
		$this->assertEquals( [], $results );
		$this->assertEquals( 0, $repository->found() );
		$this->assertEquals( 0, $repository->count() );
	}

	/**
	 * It should accept WP_Query arguments that aren't filters.
	 *
	 * @test
	 */
	public function should_should_accept_wp_query_arguments_that_arent_filters() {
		$repository = $this->repository();
		$all_ids    = $this->factory()->post->create_many( 5, [ 'post_type' => 'book' ] );

		$results = $repository->by( 'this_filter_does_not_exist', 1 )->by( 'post__in', $all_ids )->get_ids();

		$wp_query = $repository->get_query();

		$this->assertArrayHasKey( 'this_filter_does_not_exist', $wp_query->query_vars );
		$this->assertEquals( 1, $wp_query->query_vars['this_filter_does_not_exist'] );
		$this->assertCount( count( $all_ids ), $results );
		$this->assertEquals( $all_ids, $results );
		$this->assertEquals( count( $all_ids ), $repository->found() );
		$this->assertEquals( count( $all_ids ), $repository->count() );
	}

	/**
	 * It should allow taking subset of query
	 *
	 * @test
	 */
	public function should_allow_taking_subset_of_query() {
		$repository = $this->repository();
		$all_ids    = $this->factory()->post->create_many( 10, [ 'post_type' => 'book' ] );

		$results = $repository->fields( 'ids' )->take( 2 );

		$this->assertCount( 2, $results );
		$this->assertEquals( [ $all_ids[0], $all_ids[1] ], $results );
		$this->assertEquals( 10, $repository->found() );
		$this->assertEquals( 10, $repository->count() );
	}

	/**
	 * It should allow taking a subset of the query when paginating
	 *
	 * @test
	 */
	public function should_allow_taking_a_subset_of_the_query_when_paginating() {
		$repository = $this->repository();
		$all_ids    = $this->factory()->post->create_many( 10, [ 'post_type' => 'book' ] );

		$results = $repository
			->fields( 'ids' )
			->per_page( 3 )
			->page( 2 )
			->take( 2 );

		$this->assertCount( 2, $results );
		$this->assertEquals( [ $all_ids[3], $all_ids[4] ], $results );
		$this->assertEquals( 10, $repository->found() );
		$this->assertEquals( 3, $repository->count() );
	}

	/**
	 * It should return available when taking more then available
	 *
	 * @test
	 */
	public function should_return_available_when_taking_more_then_available() {
		$repository = $this->repository();
		$all_ids    = $this->factory()->post->create_many( 10, [ 'post_type' => 'book' ] );

		$results = $repository
			->fields( 'ids' )
			->per_page( 3 )
			->page( 2 )
			->take( 4 );

		$this->assertCount( 3, $results );
		$this->assertEquals( [ $all_ids[3], $all_ids[4], $all_ids[5] ], $results );
		$this->assertEquals( 10, $repository->found() );
		$this->assertEquals( 3, $repository->count() );
	}

	/**
	 * It should allow getting a post by its primary key
	 *
	 * @test
	 */
	public function should_allow_getting_a_post_by_its_primary_key() {
		$post_id    = $this->factory()->post->create( [ 'post_type' => 'book' ] );
		$repository = $this->repository();

		$book = $repository->by_primary_key( $post_id );

		$this->assertEquals( get_post( $post_id ), $book );
	}

	/**
	 * It should return null when getting a post by primary key and it does not exist
	 *
	 * @test
	 */
	public function should_return_null_when_getting_a_post_by_primary_key_and_it_does_not_exist() {
		$repository = $this->repository();

		$book = $repository->by_primary_key( 24234234 );

		$this->assertNull( $book );
	}

	/**
	 * It should not take permissions into account when reading posts by primary key
	 *
	 * @test
	 */
	public function should_not_take_permissions_into_account_when_reding_posts_by_primary_key() {
		$ids        = $this->factory()->post->create_many( 3, [ 'post_type' => 'book' ] );
		$repository = $this->repository();

		$this->assertInstanceOf( \Tribe__Repository__Update_Interface::class, $repository->where( 'post__in', $ids )->set( 'post_title', 'foo' ) );
	}

	/**
	 * It should allow querying by multiple meta keys
	 *
	 * @test
	 */
	public function should_allow_querying_by_multiple_meta_keys() {
		$ids = $this->factory()->post->create_many( 3, [ 'post_type' => 'book' ] );
		update_post_meta( $ids[0], 'one', 'foo' );
		update_post_meta( $ids[1], 'two', 'bar' );
		update_post_meta( $ids[2], 'three', 'bar' );
		$repository = $this->repository();

		$this->assertEquals( \array_slice( $ids, 0, 2 ), $this->repository()->where( 'meta_exists', [
			'one',
			'two'
		] )->fields( 'ids' )->all() );
		$this->assertEquals( [ $ids[2] ], $this->repository()->where( 'meta_not_exists', [
			'one',
			'two'
		] )->fields( 'ids' )->all() );
		$this->assertEquals( \array_slice( $ids, 1, 1 ), $this->repository()->where( 'meta_in', [
			'one',
			'two'
		], 'bar' )->fields( 'ids' )->all() );
		$this->assertEquals( [ $ids[0], $ids[2] ], $this->repository()->where( 'meta_in', [
			'one',
			'three'
		], [ 'foo', 'bar' ] )->fields( 'ids' )->all() );
		$this->assertEquals( [], $this->repository()->where( 'meta_not_in', [
			'one',
			'three'
		], [ 'foo', 'bar' ] )->fields( 'ids' )->all() );
		$this->assertEquals( [ $ids[2] ], $this->repository()->where( 'meta_not_in', [
			'one',
			'three'
		], [ 'foo' ] )->fields( 'ids' )->all() );
	}

	/**
	 * It should allow filtering posts by related post fields
	 *
	 * @test
	 */
	public function should_allow_filtering_posts_by_related_post_fields() {
		$books = $this->factory()->post->create_many( 4, [ 'post_type' => 'book' ] );
		list( $first_book, $second_book, $third_book, $fourth_book ) = $books;

		$first_book_reviews = array_reduce( [ 'good', 'good', 'good' ], [ $this, 'create_review' ], [] );
		foreach ( $first_book_reviews as $review ) {
			add_post_meta( $first_book, '_review', $review );
		}
		$second_book_reviews = array_reduce( [ 'good', 'good', 'bad' ], [ $this, 'create_review' ], [] );
		foreach ( $second_book_reviews as $review ) {
			add_post_meta( $second_book, '_review', $review );
		}
		$third_book_reviews  = [];
		$fourth_book_reviews = array_reduce( [ 'bad' ], [ $this, 'create_review' ], [] );
		foreach ( $fourth_book_reviews as $review ) {
			add_post_meta( $fourth_book, '_review', $review );
		}

		$w_reviews = $this->repository()
		                  ->where_meta_related_by( '_review', 'EXISTS' )
		                  ->fields( 'ids' )
		                  ->all();
		$this->assertEquals( [
			$first_book,
			$second_book,
			$fourth_book,
		], $w_reviews );

		$wo_reviews = $this->repository()
		                   ->where_meta_related_by( '_review', 'NOT EXISTS' )
		                   ->fields( 'ids' )
		                   ->all();
		$this->assertEquals( [
			$third_book,
		], $wo_reviews );

		$w_good_reviews = $this->repository()
		                       ->where_meta_related_by( '_review', '=', 'post_status', 'good' )
		                       ->fields( 'ids' )
		                       ->all();
		$this->assertEquals( [
			$first_book,
			$second_book
		], $w_good_reviews );

		$wo_good_reviews = $this->repository()
		                        ->where_meta_related_by( '_review', '!=', 'post_status', 'good' )
		                        ->fields( 'ids' )
		                        ->all();
		$this->assertEquals( [
			$second_book,
			$fourth_book
		], $wo_good_reviews );

		$repository                   = $this->repository();
		$w_good_reviews_or_no_reviews = $repository
			->where_or(
				[ 'where_meta_related_by', '_review', 'NOT EXISTS' ],
				[ 'where_meta_related_by', '_review', '=', 'post_status', 'good' ]
			)
			->fields( 'ids' )
			->all();
		$this->assertEquals( [
			$first_book,
			$second_book,
			$third_book,
		], $w_good_reviews_or_no_reviews );
	}

	/**
	 * It should allow filtering posts by related post meta fields
	 *
	 * @test
	 */
	public function should_allow_filtering_posts_by_related_post_meta_fields() {
		// Create books.
		$books = $this->factory()->post->create_many( 5, [ 'post_type' => 'book' ] );
		list( $first_book, $second_book, $third_book, $fourth_book, $fifth_book ) = $books;

		$first_book_reviews = array_reduce( [ 'good', 'good', 'good' ], [ $this, 'create_review' ], [] );

		foreach ( $first_book_reviews as $review ) {
			add_post_meta( $first_book, '_review', $review );
			add_post_meta( $review, '_short_line', 'Would read it again!' );
		}

		$second_book_reviews = array_reduce( [ 'good', 'good', 'bad' ], [ $this, 'create_review' ], [] );

		foreach ( $second_book_reviews as $review ) {
			add_post_meta( $second_book, '_review', $review );
			add_post_meta( $review, '_short_line', 'Would not read it again!' );
		}

		// Intentionally left empty.
		$third_book_reviews = [];

		$fourth_book_reviews = array_reduce( [ 'bad' ], [ $this, 'create_review' ], [] );

		foreach ( $fourth_book_reviews as $review ) {
			add_post_meta( $fourth_book, '_review', $review );
			add_post_meta( $review, '_short_line', 'Would totally read it again!' );
		}

		$fifth_book_reviews = array_reduce( [ 'bad' ], [ $this, 'create_review' ], [] );

		foreach ( $fifth_book_reviews as $review ) {
			add_post_meta( $fifth_book, '_review', $review );
			// Intentionally do not set short line.
		}

		// Check for books that have _short_line set.
		$w_reviews = $this->repository()->where_meta_related_by_meta( '_review', 'EXISTS', '_short_line' )
		                  ->fields( 'ids' )->all();
		$this->assertEquals( [
			$first_book,
			$second_book,
			$fourth_book,
		], $w_reviews );

		// Check for books that have no _short_line set.
		$wo_reviews = $this->repository()->where_meta_related_by_meta( '_review', 'NOT EXISTS', '_short_line' )
		                   ->fields( 'ids' )->all();
		$this->assertEquals( [
			$fifth_book,
		], $wo_reviews );

		// Check for books that have _short_line set as a specific value.
		$w_good_reviews = $this->repository()
		                       ->where_meta_related_by_meta( '_review', '=', '_short_line', 'Would read it again!' )
		                       ->fields( 'ids' )->all();
		$this->assertEquals( [
			$first_book,
		], $w_good_reviews );

		// Check for books that do NOT have _short_line set as a specific value.
		$wo_good_reviews = $this->repository()
		                        ->where_meta_related_by_meta( '_review', '!=', '_short_line', 'Would read it again!' )
		                        ->fields( 'ids' )->all();
		$this->assertEquals( [
			$second_book,
			$fourth_book,
		], $wo_good_reviews );

		// Check for books that do NOT have _short_line set as a specific value OR that value does NOT EXIST.
		$wo_good_reviews = $this->repository()
		                        ->where_meta_related_by_meta( '_review', '!=', '_short_line', 'Would read it again!', true )
		                        ->fields( 'ids' )->all();
		$this->assertEquals( [
			$second_book,
			$fourth_book,
			$fifth_book,
		], $wo_good_reviews );

		// Check for books that have no _review set OR _short_line set as a specific value.
		$first_match = [
			'where_meta_related_by_meta',
			'_review',
			'NOT EXISTS',
			'_short_line',
		];

		$second_match = [
			'where_meta_related_by_meta',
			'_review',
			'=',
			'_short_line',
			'Would read it again!',
		];
		$w_good_reviews_or_no_reviews = $this->repository()->where_or( $first_match, $second_match )->fields( 'ids' )
		                                     ->all();
		$this->assertEquals( [
			$first_book,
			$fifth_book,
		], $w_good_reviews_or_no_reviews );
	}

	/**
	 * It should allow checking if a READ filter will be applied
	 *
	 * @test
	 */
	public function should_allow_checking_if_a_read_filter_will_be_applied() {
		$repository = $this->repository();

		$this->assertFalse($repository->has_filter('search'));
		$this->assertFalse($repository->has_filter('meta_exists'));
		$this->assertFalse($repository->has_filter('meta_exists','foo'));

		$repository->by('meta_exists', 'foo');

		$this->assertFalse($repository->has_filter('search'));
		$this->assertTrue($repository->has_filter('meta_exists'));
		$this->assertTrue($repository->has_filter('meta_exists','foo'));
		$this->assertFalse($repository->has_filter('meta_exists','bar'));
	}

	/**
	 * It should allow getting a query object built on an array of posts
	 *
	 * @test
	 */
	public function should_allow_getting_a_query_object_built_on_an_array_of_posts() {
		$posts = array_map( 'get_post', $this->factory()->post->create_many( 3, [ 'post_type' => 'post' ] ) );

		$query = $this->repository()->get_query_for_posts( $posts );

		global $wpdb;
		$num_queries = $wpdb->num_queries;
		$this->assertInstanceOf( \WP_Query::class, $query );
		$this->assertEqualSets( $posts, $query->posts );
		$this->assertEquals( 3, $query->found_posts );
		$this->assertEquals( $num_queries, $wpdb->num_queries );
	}

	/**
	 * It should correctly build a query object on an empty array
	 *
	 * @test
	 */
	public function should_correctly_build_a_query_object_on_an_empty_array() {
		$query = $this->repository()->get_query_for_posts( [] );

		global $wpdb;
		$num_queries = $wpdb->num_queries;
		$this->assertInstanceOf( \WP_Query::class, $query );
		$this->assertEquals( [], $query->posts );
		$this->assertEquals( 0, $query->found_posts );
		$this->assertEquals( $num_queries, $wpdb->num_queries );
	}

	/**
	 * It should allow voiding a repository queries
	 *
	 * @test
	 */
	public function should_allow_voiding_a_repository_queries() {
		$books = static::factory()->post->create_many( 2, [ 'post_type' => 'book' ] );

		$queries_count = $this->queries()->countQueries();

		$repository = $this->repository();

		$repository->void_query( true );

		$results = $repository->all();

		$this->assertEquals( [], $results );
		$this->assertEquals( $queries_count, $this->queries()->countQueries() );

		$repository->void_query( false );

		$results = $repository->all();

		$this->assertCount( 2, $results );
		$this->assertContainsOnlyInstancesOf( \WP_Post::class, $results );
		$this->assertEquals( $queries_count + 1, $this->queries()->countQueries() );
	}

	/**
	 * It should allow invalidating a query using args
	 *
	 * @test
	 */
	public function should_allow_invalidating_a_query_using_args() {
		$repository = $this->repository();

		foreach ( range( 1, 3 ) as $i ) {
			static::factory()->post->create( [ 'post_type' => 'book' ] );
		}

		$this->assertCount( 3, $repository->all() );
		$this->assertEmpty( $repository->by( 'void_query', true )->all() );
		$this->assertEmpty( $repository->by_args( [ 'void_query' => true ] )->all() );
		$this->assertCount( 0, $repository->by_args( [ 'void_query' => false ] )->all() );
		$this->assertCount( 0, $repository->by( 'void_query', false )->all() );
	}

	/**
	 * Create review using array_reduce().
	 *
	 * @param array  $reviews       List of ongoing reviews.
	 * @param string $review_status Review post status.
	 *
	 * @return array List of reviews.
	 */
	protected function create_review( array $reviews, $review_status ) {
		$reviews[] = $this->factory()->post->create( [
			'post_type'   => 'review',
			'post_status' => $review_status,
		] );

		return $reviews;
	}
}
