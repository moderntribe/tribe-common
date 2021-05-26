<?php
/**
 * Models a Customizer separator, a Control just in name, it does not control any setting.
 *
 * @since   TBD
 *
 * @package Tribe\Customizer\Controls
 */

namespace Tribe\Customizer\Controls;

use Tribe\Customizer\Control;

class Separator extends Control {

	/**
	 * Control's Type.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public $type = 'separator';

	/**
	 * Anyone able to set theme options will be able to see the header.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	public $capability = 'edit_theme_options';

	/**
	 * The heading does not control any setting.
	 *
	 * @since TBD
	 *
	 * @var array<string,mixed>
	 */
	public $settings = [];

	/**
	 * Render the control's content
	 *
	 * @since TBD
	 */
	public function render_content() {
		?>
		<p>
			<hr>
		</p>
		<?php
	}
}
