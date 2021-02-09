<?php

namespace Tribe\Repository;

require_once __DIR__ . '/ReadTestBase.php';

class OrderTest extends ReadTestBase {
	/**
	 * It should correctly order posts by default WordPress order
	 *
	 * @test
	 */
	public function should_correctly_order_posts_by_default_word_press_order() {
		$book_1 = static::factory()->post->create( [ 'post_type' => 'book', 'menu_order' => 2 ] );
		$book_2 = static::factory()->post->create( [ 'post_type' => 'book', 'menu_order' => 1 ] );
		$book_3 = static::factory()->post->create( [ 'post_type' => 'book', 'menu_order' => 3 ] );

		$repository = $this->repository();
		$results    = $repository
			->order_by( 'menu_order' )
			->order( 'ASC' )
			->get_ids();

		$this->assertEquals( [ $book_2, $book_1, $book_3 ], $results );
	}

	/**
	 * It should allow setting the order direction in the orderby method
	 *
	 * @test
	 */
	public function should_allow_setting_the_order_direction_in_the_orderby_method() {
		$book_1 = static::factory()->post->create( [ 'post_type' => 'book', 'menu_order' => 2 ] );
		$book_2 = static::factory()->post->create( [ 'post_type' => 'book', 'menu_order' => 1 ] );
		$book_3 = static::factory()->post->create( [ 'post_type' => 'book', 'menu_order' => 3 ] );

		$repository = $this->repository();
		$results    = $repository
			->order_by( [ 'menu_order' => 'DESC' ] )
			->get_ids();

		$this->assertEquals( [ $book_3, $book_1, $book_2 ], $results );
	}

	/**
	 * It should override the order direction specified w/ order w/ the orderby one.
	 *
	 * @test
	 */
	public function should_ld_override_the_order_direction_specified_w_order_w_the_orderby_one() {
		$book_1 = static::factory()->post->create( [ 'post_type' => 'book', 'menu_order' => 2 ] );
		$book_2 = static::factory()->post->create( [ 'post_type' => 'book', 'menu_order' => 1 ] );
		$book_3 = static::factory()->post->create( [ 'post_type' => 'book', 'menu_order' => 3 ] );

		$repository = $this->repository();
		$results    = $repository
			->order_by( [ 'menu_order' => 'DESC' ] )
			->order( 'ASC' )
			->get_ids();

		$this->assertEquals( [ $book_3, $book_1, $book_2 ], $results );
	}

	/**
	 * It shouldn't overwrite the array order when using order_by 2nd param.
	 *
	 * @test
	 */
	public function shouldnt_overwrite_when_passing_array_and_order_param_to_order_by() {
		$book_1 = static::factory()->post->create( [ 'post_type' => 'book', 'menu_order' => 2 ] );
		$book_2 = static::factory()->post->create( [ 'post_type' => 'book', 'menu_order' => 1 ] );
		$book_3 = static::factory()->post->create( [ 'post_type' => 'book', 'menu_order' => 3 ] );

		$repository = $this->repository();
		$results    = $repository
			->order_by( [ 'menu_order' => 'DESC' ], 'ASC' )
			->get_ids();

		$this->assertEquals( [ $book_3, $book_1, $book_2 ], $results );
	}
}