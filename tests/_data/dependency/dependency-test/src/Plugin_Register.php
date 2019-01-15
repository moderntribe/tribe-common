<?php

class DT_Plugin_Register extends Tribe__Abstract_Plugin_Register {

	protected $main_class = 'DT_Plugin';
	protected $dependencies = array(
		'parent-dependencies' => array(
			'Tribe__Events__Main' => '4.8',
		),
	);
	protected $main_path;

	public function __construct() {
		$this->base_file = DT_FILE;
		$this->main_path = dirname( $this->base_file ) . '/src/Plugin.php';
		$this->version   = '4.5.6';

		$this->register_plugin();
	}

	public function add_active_plugin() {
		Tribe__Dependency::instance()->add_active_plugin( $this->main_class, $this->version, $this->main_path );
	}
}