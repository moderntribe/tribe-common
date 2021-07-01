<?php

namespace Tribe\Tests\Snapshots\Views\V2\Admin_Views\Components;

use tad\Codeception\SnapshotAssertions\SnapshotAssertions;
use Tribe\Tests\Snapshots\Snapshot_Test_Case;

class SwitchTest extends Snapshot_Test_Case {

	use SnapshotAssertions;

	/**
	 * @var string The path to the template, either relative to the `/src` directory, or absolute.
	 */
	protected $template_path = 'admin-views/components/switch.php';

	/**
	 * @test
	 */
	public function should_render_without_classes_and_not_checked() {
		$context = [
			'id'            => 'switch-id',
			'label'         => 'Switch Label',
			'classes_wrap'  => [],
			'classes_input' => [],
			'classes_label' => [],
			'name'          => 'switch-status',
			'value'         => 1,
			'checked'       => false,
			'attrs'         => [],
		];
		$this->assertMatchesHtmlSnapshot( $this->render( $context ) );
	}

	/**
	 * @test
	 */
	public function should_render_with_classes_and_checked() {
		$context = [
			'id'            => 'switch-id',
			'label'         => 'Switch Label',
			'classes_wrap'  => [ 'test-class-1', 'test-class-4' ],
			'classes_input' => [ 'test-class-2' ],
			'classes_label' => [ 'test-class-3' ],
			'name'          => 'switch-status',
			'value'         => 0,
			'checked'       => true,
			'attrs'         => [],
		];
		$this->assertMatchesHtmlSnapshot( $this->render( $context ) );
	}
}
