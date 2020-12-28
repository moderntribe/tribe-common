<?php

namespace Tribe\Utils;

class Paths_Test extends \Codeception\Test\Unit {
	public function merge_data_provider() {
		return [
			'no paths'                   => [ '', [] ],
			'one relative string path'   => [ 'foo/bar/baz', [ 'foo/bar/baz' ] ],
			'one relative array path'    => [ 'foo/bar/baz', [ 'foo', 'bar', 'baz' ] ],
			'two relative array paths'   => [ 'foo/bar/baz/test.php', [ 'foo', 'bar', 'baz' ], [ 'bar', 'baz', 'test.php' ] ],
			'three relative array paths' => [
				'foo/bar/baz/tests/test.php',
				[ 'foo', 'bar', 'baz' ],
				[ 'bar', 'baz', 'tests' ],
				[ 'tests', 'test.php' ],
			],
			'four relative array paths'  => [
				'foo/bar/baz/tests/one/two/three/test.php',
				[ 'foo', 'bar', 'baz' ],
				[ 'bar', 'baz', 'tests' ],
				[ 'tests', 'one', 'two' ],
				[ 'one', 'two', 'three', 'test.php' ],
			],
			'a bit of everything'        => [
				'/var/www/html/plugins/plugin/src/views/v2/comp/link.php',
				'/var/www/html/plugins/',
				'plugin/src/',
				[ 'src', 'views', 'v2' ],
				'v2/comp/link.php',
			],
			'dir with trailing slash' => [
				'/var/www/html/plugins/plugin/src/views/v2/comp/',
				'/var/www/html/plugins/',
				'plugin/src/',
				[ 'src', 'views', 'v2' ],
				'v2/comp/',
			],
		];
	}

	/**
	 * @dataProvider merge_data_provider
	 */
	public function test_merge( $expected, ...$paths ) {
		$this->assertEquals( $expected, Paths::merge( ...$paths ) );
	}
}
