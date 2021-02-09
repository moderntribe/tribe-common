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

	public function valid_falsy_paths_data_provider(  ) {
	return [
		'dir called 0' => [
			'/home/vps-49f1a7/0/staging_html/wp-content/plugins/the-events-calendar/common/src/views',
			'/home/vps-49f1a7/0/staging_html/wp-content/plugins/the-events-calendar/common/src/views/v2/base'
		],
		'dir called space on *nix' => [
			'/home/vps-49f1a7/\ /staging_html/wp-content/plugins/the-events-calendar/common/src/views',
			'/home/vps-49f1a7/\ /staging_html/wp-content/plugins/the-events-calendar/common/src/views/v2/base'
		],
		'dir called space on win' => [
			'/home/vps-49f1a7/^ /staging_html/wp-content/plugins/the-events-calendar/common/src/views',
			'/home/vps-49f1a7/^ /staging_html/wp-content/plugins/the-events-calendar/common/src/views/v2/base'
		],
		'dir name contains one *nix escaped space' => [
			'/home/vps-49f1a7/html\ root/staging_html/wp-content/plugins/the-events-calendar/common/src/views',
			'/home/vps-49f1a7/html\ root/staging_html/wp-content/plugins/the-events-calendar/common/src/views/v2/base'
		],
		'dir name contains two *nix escaped spaces' => [
			'/home/vps-49f1a7/html\ root/staging_html/the\ content/plugins/the-events-calendar/common/src/views',
			'/home/vps-49f1a7/html\ root/staging_html/the\ content/plugins/the-events-calendar/common/src/views/v2/base'
		],
		'dir name contains two win escaped spaces' => [
			'Z:\home\vps-49f1a7\html^ root\staging_html\the^ content\plugins\the-events-calendar\common\src\views',
			'Z:/home/vps-49f1a7/html^ root/staging_html/the^ content/plugins/the-events-calendar/common/src/views/v2/base'
		],
	];
}

	/**
	 * @dataProvider valid_falsy_paths_data_provider
	 */
	public function test_merge_of_paths_with_valid_falsy_paths(  $path_1, $expected ) {
		$merged = Paths::merge( $path_1, 'v2/base' );

		$this->assertEquals( $expected, $merged );
	}
}
