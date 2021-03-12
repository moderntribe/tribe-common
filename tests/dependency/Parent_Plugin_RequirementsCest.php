<?php

use DependencyTester as Tester;

class Parent_Plugin_RequirementsCest {

	protected $parent_plugin = 'the-events-calendar/the-events-calendar.php';
	protected $parent_plugin_slug = 'the-events-calendar';
	protected $addon_plugin = 'dependency-test/dependency-test.php';
	protected $addon_class = 'DT_Plugin';

	/**
	 * It should not show any notice if addon requirement is same as required by parent
	 *
	 * @test
	 */
	public function should_not_show_any_notice_if_addon_requirement_is_same_as_required_by_parent( Tester $I ) {
		$filtering_plugin = $I->have_plugin_with_template_and_data( 'main_and_addon_filter', [
			'parent_class'    => 'Tribe__Events__Main',
			'addon_class'     => $this->addon_class,
			'parent_requires' => '4.8',
			'addon_version'   => '4.8',
		] );

		$I->set_active_plugins( [ $filtering_plugin, $this->parent_plugin, $this->addon_plugin ] );

		$I->loginAsAdmin();
		$I->amOnPluginsPage();

		$I->dontSeeElement( '.tribe-notice.tribe-dependency-error' );
	}

	/**
	 * It should show a notice if addon version is lower than required by parent
	 * @skip Problematic addon dependency testing. Need to review.
	 * @test
	 */
	public function should_show_a_notice_if_addon_version_is_lower_than_required_by_parent( Tester $I ) {
		$filtering_plugin = $I->have_plugin_with_template_and_data( 'main_and_addon_filter', [
			'parent_class'    => 'Tribe__Events__Main',
			'addon_class'     => $this->addon_class,
			'parent_requires' => '4.8',
			'addon_version'   => '4.7',
		] );

		$I->set_active_plugins( [ $filtering_plugin, $this->parent_plugin, $this->addon_plugin ] );

		$I->loginAsAdmin();
		$I->amOnPluginsPage();

		$I->seeElement( '.tribe-notice.tribe-dependency-error[data-plugin="' . $this->parent_plugin_slug . '"]' );
	}
}
