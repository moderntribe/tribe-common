<?php

namespace Tribe\Utils;

use Tribe\Utils\Links;
use tad\Codeception\SnapshotAssertions\SnapshotAssertions;

class LinksTest extends \Codeception\TestCase\WPTestCase {
	use SnapshotAssertions;

	public function setUp() {
		// before.
		parent::setUp();
		add_filter( 'home_url', function() { return 'http://www.subgenius.com/'; } );

		$this->link_Object             = new Links;
		$this->link_Object->local_host = 'http://www.subgenius.com/';
		$this->local_host              = 'http://www.subgenius.com/';
		$this->local_subdomain         = 'http://www.fnord.subgenius.com/';
		$this->remote_subdomain        = 'https://support.theeventscalendar.com';
		$this->url                     = 'https://theeventscalendar.com/knowledgebase/k/embedding-calendar-views-with-the-tribe_events-shortcode/';
	}

	public function tearDown() {
		// your tear down methods here.

		// then.
		parent::tearDown();
	}

	public function set_target_blank() {
		add_filter(
			'tribe_get_link_target_attribute',
			function() {
				return '_blank';
			}
		);
	}

	/**
	 * @test
	 * It should correctly identify the host.
	 */
	public function it_should_correctly_identify_the_host() {
		$this->assertEquals( 'theeventscalendar.com', $this->link_Object->get_link_host( $this->url ) );
		$this->assertNotEquals( 'theeventscalendar.com', $this->link_Object->get_link_host( $this->local_host ) );
	}

	/**
	 * @test
	 * It should correctly identify a local host.
	 */
	public function it_should_correctly_identify_a_local_host() {
		$this->assertTrue( $this->link_Object->is_local_link( $this->local_host ) );
		$this->assertFalse( $this->link_Object->is_local_link( $this->url ) );
	}


	/**
	 * @test
	 * It should correctly identify a local subdomain.
	 */
	public function it_should_correctly_identify_a_local_subdomain() {
		$this->assertTrue( $this->link_Object->is_local_subdomain( $this->local_subdomain ) );
		$this->assertFalse( $this->link_Object->is_local_subdomain( $this->remote_subdomain ) );
	}

	/**
	 * @test
	 * It should correctly identify a relative url.
	 */
	public function it_should_correctly_identify_a_relative_url() {
		$this->assertTrue( $this->link_Object->is_relative_url( '/' ) );
		$this->assertFalse( $this->link_Object->is_relative_url( $this->url ) );
	}

	/**
	 * @test
	 * It should correctly identify a local url.
	 */
	public function it_should_correctly_identify_a_local_url() {
		$this->assertTrue( $this->link_Object->is_local_link( $this->local_host ) );
		$this->assertTrue( $this->link_Object->is_local_link( $this->local_subdomain ) );
		$this->assertFalse( $this->link_Object->is_local_link( $this->url ) );
	}

	/**
	 * @test
	 * It should correctly identify a relative url as local.
	 */
	public function it_should_correctly_identify_a_relative_url_as_local() {
		$this->assertTrue( $this->link_Object->is_local_link( '/' ) );
	}

	/**
	 * @test
	 * It should correctly output a local rel attribute.
	 */
	public function it_should_correctly_output_a_local_rel_attribute() {
		$this->assertEmpty( $this->link_Object->get_rel_attr( $this->local_host ) );
	}

	/**
	 * @test
	 * It should correctly output an external rel attribute.
	 */
	public function it_should_correctly_output_an_external_rel_attribute() {
		$this->assertEquals( 'external', $this->link_Object->get_rel_attr( $this->url ) );
	}

	/**
	 * @test
	 * It should correctly output a default target.
	 */
	public function it_should_correctly_output_a_default_target() {
		$this->assertEquals( '_self', $this->link_Object->get_target_attr( $this->url ) );
	}

	/**
	 * @test
	 * It should correctly output a filtered target.
	 */
	public function it_should_correctly_output_a_filtered_target() {
		$this->set_target_blank();
		$this->assertEquals( '_blank', $this->link_Object->get_target_attr( $this->url ) );
	}

	/**
	 * @test
	 * It should correctly output a rel attribute for target="blank" on an internal link.
	 */
	public function it_should_correctly_output_a_rel_attribute_for_target_blank_internal() {
		$this->set_target_blank();
		$this->assertEquals( 'noopener noreferrer', $this->link_Object->get_rel_attr( $this->local_subdomain ) );
	}

	/**
	 * @test
	 * It should correctly output a rel attribute for target="blank" on an external link.
	 */
	public function it_should_correctly_output_a_rel_attribute_for_target_blank_external() {
		$this->set_target_blank();

		$this->assertEquals( 'external noopener noreferrer', $this->link_Object->get_rel_attr( $this->url ) );
	}

	/**
	 * @test
	 * Build should handle default args for internal links.
	 */
	public function build_should_handle_default_args_internal() {
		$build = $this->link_Object->build( $this->local_host );

		$this->assertEquals(
			[
				'rel'    => false,
				'target' => '_self',
			],
			$build
		);
	}

	/**
	 * @test
	 * Build should handle default args for internal links with target _blank.
	 */
	public function build_should_handle_default_args_internal_for_target_blank() {
		$args  = [];
		$this->set_target_blank();
		$build = $this->link_Object->build( $this->local_host, $args );

		$this->assertEquals(
			[
				'rel'    => 'noopener noreferrer',
				'target' => '_blank',
			],
			$build
		);
	}

	/**
	 * @test
	 * Build should handle default args for external links.
	 */
	public function build_should_handle_default_args_external() {
		$args  = [];
		$build = $this->link_Object->build( $this->url, $args );

		$this->assertEquals(
			[
				'target' => '_self',
      			'rel'    => 'external',
			],
			$build
		);
	}

	/**
	 * @test
	 * Build should handle additional args.
	 */
	public function build_should_handle_additional_args() {
		$args  = [
			'disabled' => true,
			'title' => 'Title'
		];
		$build = $this->link_Object->build( $this->url, $args );

		$this->assertEquals(
			[
				'disabled' => true,
				'rel'      => 'external',
				'target'   => '_self',
				'title'    => 'Title',
			],
			$build
		);
	}

	/**
	 * @test
	 * Build attr string should handle default args for internal links.
	 */
	public function build_attr_string_should_handle_default_args_internal() {
		$build = $this->link_Object->build_attr_string( $this->local_host );

		$this->assertEquals( 'target="_self"', $build );
	}

	/**
	 * @test
	 * Build attr string should handle default args for external links.
	 */
	public function build_attr_string_should_handle_default_args_external() {
		$build = $this->link_Object->build_attr_string( $this->url );

		$this->assertEquals( 'target="_self" rel="external"', $build );
	}

	/**
	 * @test
	 * Build attr string should handle additional args for links.
	 */
	public function build_attr_string_should_handle_additional_args() {
		$args = [
			'disabled' => true,
			'media'    => 'screen',
			'target' => '_blank',
		];

		$build = $this->link_Object->build_attr_string( $this->url, $args );

		$this->assertEquals( 'target="_blank" rel="external" disabled media="screen"', $build );
	}

	/**
	 * @test
	 *
	 * It should render internal link correctly.
	 */
	public function it_should_render_internal_link_correctly() {
		$render = $this->link_Object->render( $this->local_host, 'Test' );

		$this->assertMatchesHtmlSnapshot( $render );
	}

	/**
	 * @test
	 *
	 * It should render internal link correctly with target _blank.
	 */
	public function it_should_render_internal_link_correctly_with_target_blank() {
		$this->set_target_blank();
		$render = $this->link_Object->render( $this->local_host, 'Test' );

		$this->assertMatchesHtmlSnapshot( $render );
	}

	/**
	 * @test
	 *
	 * It should render internal link correctly with added attributes.
	 */
	public function it_should_render_internal_link_correctly_with_added_attributes() {
		$args = [
			'disabled' => true,
			'media'    => 'screen',

		];
		$render = $this->link_Object->render( $this->local_host, 'Test', $args );

		$this->assertMatchesHtmlSnapshot( $render );
	}

	/**
	 * @test
	 *
	 * It should render external link correctly.
	 */
	public function it_should_render_external_link_correctly() {
		$render = $this->link_Object->render( $this->url, 'Test' );

		$this->assertMatchesHtmlSnapshot( $render );
	}

	/**
	 * @test
	 *
	 * It should render external link correctly with target _blank.
	 */
	public function it_should_render_external_link_correctly_with_target_blank() {
		$this->set_target_blank();
		$render = $this->link_Object->render( $this->url, 'Test' );

		$this->assertMatchesHtmlSnapshot( $render );
	}

	/**
	 * @test
	 *
	 * It should render external link correctly with added attributes.
	 */
	public function it_should_render_external_link_correctly_with_added_attributes() {
		$args = [
			'disabled' => true,
			'media'    => 'screen',
			'target'   => '_blank,'

		];

		$render = $this->link_Object->render( $this->url, 'Test', $args );

		$this->assertMatchesHtmlSnapshot( $render );
	}
}
