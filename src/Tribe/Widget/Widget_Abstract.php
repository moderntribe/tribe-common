<?php

namespace Tribe\Widget;

use Tribe__Utils__Array as Arr;

/**
 * The abstract base without Views that all widgets should implement.
 *
 * @since   5.12.12
 *
 * @package Tribe\Widget
 */
abstract class Widget_Abstract extends \WP_Widget implements Widget_Interface {

	/**
	 * Slug of the current widget.
	 *
	 * @since 5.12.12
	 *
	 * @var string
	 */
	protected $slug;

	/**
	 * An instance of template.
	 *
	 * @since 4.12.14
	 *
	 * @var \Tribe__Template
	 */
	protected $admin_template;

	/**
	 * The slug of the admin widget view.
	 *
	 * @since 4.12.14
	 *
	 * @var string
	 */
	protected $view_admin_slug;

	/**
	 * Default arguments to be merged into final arguments of the widget.
	 *
	 * @since 5.12.12
	 *
	 * @var array<string,mixed>
	 */
	protected $default_arguments = [];

	/**
	 * Array map allowing aliased widget arguments.
	 *
	 * The array keys are aliases of the array values (i.e. the "real" widget attributes to parse).
	 * Example array: [ 'alias' => 'canonical', 'from' => 'to', 'that' => 'becomes_this' ]
	 * Example widget usage: [some_tag alias=17 to='Fred'] will be parsed as [some_tag canonical=17 to='Fred']
	 *
	 * @since 5.12.12
	 *
	 * @var array<string,string>
	 */
	protected $aliased_arguments = [];

	/**
	 * Array of callbacks for validation of arguments.
	 *
	 * @since 5.12.12
	 *
	 * @var array<string,callable>
	 */
	protected $validate_arguments_map = [];

	/**
	 * Arguments of the current widget.
	 *
	 * @since 5.12.12
	 *
	 * @var array<string,mixed>
	 */
	protected $arguments;

	/**
	 * HTML content of the current widget.
	 *
	 * @since 5.12.12
	 *
	 * @var string
	 */
	protected $content;

	/**
	 * {@inheritDoc}
	 */
	public function __construct( $id_base = '', $name = '', $widget_options = [], $control_options = [] ) {
		$arguments = $this->setup_arguments();

		parent::__construct(
			Arr::get( $arguments, 'id_base', '' ),
			Arr::get( $arguments, 'name', '' ),
			Arr::get( $arguments, 'widget_options', [] ),
			Arr::get( $arguments, 'control_options', [] )
		);

		$this->setup();
	}

	/**
	 * Setup the widget.
	 *
	 * @since  5.2.1
	 *
	 * @return mixed
	 */
	public abstract function setup();

	/**
	 * Setup the widget.
	 *
	 * @since  4.12.14
	 *
	 * @param array<string,mixed> $arguments The widget arguments, as set by the user in the widget string.
	 */
	public abstract function setup_view( $arguments );

	/**
	 * {@inheritDoc}
	 */
	public function form( $instance ) {
		$arguments = $this->setup_arguments( $instance );

		$this->get_admin_html( $arguments );
	}

	/**
	 * Echoes the widget content.
	 *
	 * @since 5.12.12
	 *
	 * @param array<string,mixed> $args     Display arguments including 'before_title', 'after_title',
	 *                                      'before_widget', and 'after_widget'.
	 * @param array<string,mixed> $instance The settings for the particular instance of the widget.
	 */
	public function widget( $args, $instance ) {
		$arguments = $this->get_arguments( $instance );

		// Setup the View for the frontend.
		$this->setup_view( $arguments );

		echo $this->get_html();
	}

	/**
	 * Returns the rendered View HTML code.
	 *
	 * @since 5.12.12
	 *
	 * @return string
	 */
	public abstract function get_html();

	/**
	 * {@inheritDoc}
	 */
	public function set_aliased_arguments( array $alias_map ) {
		$this->aliased_arguments = Arr::filter_to_flat_scalar_associative_array( (array) $alias_map );
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_aliased_arguments() {
		return $this->aliased_arguments;
	}

	/**
	 * {@inheritDoc}
	 */
	public function parse_arguments( array $arguments ) {
		$arguments = Arr::parse_associative_array_alias( (array) $arguments, (array) $this->get_aliased_arguments() );

		return $this->validate_arguments( $arguments );
	}

	/**
	 * {@inheritDoc}
	 */
	public function validate_arguments( array $arguments ) {
		$validate_arguments_map = $this->get_validated_arguments_map();
		foreach ( $validate_arguments_map as $key => $callback ) {
			$arguments[ $key ] = $callback( isset( $arguments[ $key ] ) ? $arguments[ $key ] : null );
		}

		return $arguments;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_registration_slug() {
		return $this->slug;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_validated_arguments_map() {
		/**
		 * Applies a filter to the validation map for instance arguments.
		 *
		 * @since 5.12.12
		 *
		 * @param array<string,callable> $validate_arguments_map Current set of callbacks for arguments.
		 * @param static                             $instance                            The widget instance we are dealing with.
		 */
		$validate_arguments_map = apply_filters( 'tribe_widget_validate_arguments_map', $this->validate_arguments_map, $this );

		$registration_slug = $this->get_registration_slug();

		/**
		 * Applies a filter to the validation map for instance arguments for a specific widget. Based on the registration slug of the widget
		 *
		 * @since 5.12.12
		 *
		 * @param array<string,callable> $validate_arguments_map Current set of callbacks for arguments.
		 * @param static                            $instance                             The widget instance we are dealing with.
		 */
		$validate_arguments_map = apply_filters( "tribe__widget_{$registration_slug}_validate_arguments_map", $validate_arguments_map, $this );

		return $validate_arguments_map;
	}

	/**
	 * Sets up the widgets default admin fields.
	 *
	 * @since 4.12.14
	 *
	 * @return array<string,mixed> The array of widget admin fields.
	 */
	protected abstract function setup_admin_fields();

	/**
	 * {@inheritDoc}
	 */
	public function get_admin_fields() {
		return $this->filter_admin_fields( $this->setup_admin_fields() );
	}

	/**
	 * {@inheritDoc}
	 */
	public function filter_admin_fields( $admin_fields ) {
		/**
		 * Applies a filter to a widget's admin fields.
		 *
		 * @since 4.12.14
		 *
		 * @param array<string,mixed> $admin_fields The array of widget admin fields.
		 * @param static              $instance     The widget instance we are dealing with.
		 */
		$admin_fields = apply_filters( 'tribe_widget_admin_fields', $admin_fields, $this );

		$registration_slug = $this->get_registration_slug();

		/**
		 * Applies a filter to a widget's admin fields based on the registration slug of the widget.
		 *
		 * @since TBE
		 *
		 * @param array<string,mixed> $admin_fields The array of widget admin fields.
		 * @param static              $instance     The widget instance we are dealing with.
		 */
		$admin_fields = apply_filters( "tribe_widget_{$registration_slug}_admin_fields", $admin_fields, $this );

		return $admin_fields;
	}

	/**
	 * {@inheritDoc}
	 */
	public function filter_updated_instance( $updated_instance, $new_instance ) {
		/**
		 * Applies a filter to updated instance of a widget.
		 *
		 * @since 4.12.14
		 *
		 * @param array<string,mixed> $updated_instance The updated instance of the widget.
		 * @param array<string,mixed> $new_instance The new values for the widget instance.
		 * @param static              $instance  The widget instance we are dealing with.
		 */
		$updated_instance = apply_filters( 'tribe_widget_updated_instance', $updated_instance, $new_instance, $this );

		$registration_slug = $this->get_registration_slug();

		/**
		 * Applies a filter to updated instance of a widget arguments based on the registration slug of the widget.
		 *
		 * @since 4.12.14
		 *
		 * @param array<string,mixed> $updated_instance The updated instance of the widget.
		 * @param array<string,mixed> $new_instance The new values for the widget instance.
		 * @param static              $instance  The widget instance we are dealing with.
		 */
		$updated_instance = apply_filters( "tribe_widget_{$registration_slug}_updated_instance", $updated_instance, $new_instance, $this );

		return $updated_instance;
	}

	/**
	 * Sets up the widgets arguments, using saved values.
	 *
	 * @since 4.12.14
	 *
	 * @param array<string,mixed> $instance Saved values for the widget instance.
	 *
	 * @return array<string,mixed> The widget arguments, as set by the user in the widget string.
	 */
	protected function setup_arguments( array $instance = [] ) {
		$arguments = $this->arguments;

		$arguments = wp_parse_args(
			$arguments,
			$this->get_default_arguments()
		);

		$arguments = wp_parse_args(
			$instance,
			$arguments
		);

		return $arguments;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_arguments( array $instance = [] ) {
		return $this->filter_arguments( $this->setup_arguments( $instance ) );
	}

	/**
	 * {@inheritDoc}
	 */
	public function filter_arguments( $arguments ) {
		/**
		 * Applies a filter to instance arguments.
		 *
		 * @since 5.12.12
		 *
		 * @param array<string,mixed> $arguments Current set of arguments.
		 * @param static              $instance  The widget instance we are dealing with.
		 */
		$arguments = apply_filters( 'tribe_widget_arguments', $arguments, $this );

		$registration_slug = $this->get_registration_slug();

		/**
		 * Applies a filter to instance arguments based on the registration slug of the widget.
		 *
		 * @since 5.12.12
		 *
		 * @param array<string,mixed> $arguments Current set of arguments.
		 * @param static              $instance  The widget instance we are dealing with.
		 */
		$arguments = apply_filters( "tribe_widget_{$registration_slug}_arguments", $arguments, $this );

		return $arguments;
	}


	/**
	 * {@inheritDoc}
	 */
	public function get_argument( $index, $default = null ) {
		$arguments = $this->setup_arguments();
		$argument  = Arr::get( $arguments, $index, $default );

		return $this->filter_argument( $argument, $index, $default );
	}

	/**
	 * {@inheritDoc}
	 */
	public function filter_argument( $argument, $index, $default = null  ) {
		/**
		 * Applies a filter to a specific widget argument, catch all for all widgets.
		 *
		 * @since 5.12.12
		 *
		 * @param mixed               $argument The argument.
		 * @param string|int          $index    Which index we intend to fetch from the arguments.
		 * @param array<string,mixed> $default  Default value if it doesn't exist.
		 * @param static              $instance The widget instance we are dealing with.
		 */
		$argument = apply_filters( 'tribe_widget_argument', $argument, $index, $default, $this );

		$registration_slug = $this->get_registration_slug();

		/**
		 * Applies a filter to a specific widget argument, to a particular registration slug.
		 *
		 * @since 5.12.12
		 *
		 * @param mixed      $argument The argument value.
		 * @param string|int $index    Which index we intend to fetch from the arguments.
		 * @param mixed      $default  Default value if it doesn't exist.
		 * @param static     $instance The widget instance we are dealing with.
		 */
		$argument = apply_filters( "tribe_widget_{$registration_slug}_argument", $argument, $index, $default, $this );

		return $argument;
	}

	/**
	 * Sets up the widgets default arguments.
	 *
	 * @since 4.12.14
	 *
	 * @return array<string,mixed> The default widget arguments.
	 */
	protected function setup_default_arguments() {
		$default_arguments = $this->default_arguments;

		// Setup admin fields.
		$default_arguments['admin_fields'] = $this->get_admin_fields();

		// Add the Widget to the arguments to pass to the admin template.
		$default_arguments['widget_obj'] = $this;

		return $default_arguments;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_default_arguments() {
		return $this->filter_default_arguments( $this->setup_default_arguments() );
	}

	/**
	 * {@inheritDoc}
	 */
	public function filter_default_arguments( array $default_arguments = [] ) {
		/**
		 * Applies a filter to default instance arguments.
		 *
		 * @since 5.12.12
		 *
		 * @param array<string,mixed>  $default_arguments Current set of default arguments.
		 * @param static               $instance          The widget instance we are dealing with.
		 */
		$default_arguments = apply_filters( 'tribe_widget_default_arguments', $default_arguments, $this );

		$registration_slug = $this->get_registration_slug();

		/**
		 * Applies a filter to default instance arguments based on the registration slug of the widget.
		 *
		 * @since 5.12.12
		 *
		 * @param array<string,mixed>  $default_arguments Current set of default arguments.
		 * @param static               $instance          The widget instance we are dealing with.
		 */
		return apply_filters( "tribe_widget_{$registration_slug}_default_arguments", $default_arguments, $this );
	}

	/**
	 * Sets the admin template.
	 *
	 * @since 4.12.14
	 *
	 * @param \Tribe__Template $template The admin template to use.
	 */
	public function set_admin_template( \Tribe__Template $template ) {
		$this->admin_template = $template;
	}

	/**
	 * Returns the current admin template.
	 *
	 * @since 4.12.14
	 *
	 * @return \Tribe__Template The current admin template.
	 */
	public function get_admin_template() {
		return $this->admin_template;
	}

	/**
	 * Get the admin html for the widget form.
	 *
	 * @since 4.12.14
	 *
	 * @param array<string,mixed> $arguments Current set of arguments.
	 */
	public function get_admin_html( $arguments ) {
		$this->get_admin_template()->template( $this->view_admin_slug, $arguments );
	}
}
