<?php

/**
 * Shows an admin notice telling users which requisite plugins they need to download
 */
class Tribe__Admin__Notice__Plugin_Download {

	private $plugin_path;

	private $plugins_required = array();

	/**
	 * @param string $plugin_path Path to the plugin file we're showing a notice for
	 */
	public function __construct( $plugin_path ) {
		$this->plugin_path = $plugin_path;

		tribe_notice(
			plugin_basename( $plugin_path ),
			array( $this, 'show_inactive_plugins_alert' )
		);
	}

	/**
	 * Add a required plugin to the notice
	 *
	 * @since 4.8.3 Method introduced.
	 * @since 4.9 Added $version and $addon parameters.
	 * @since TBD Add $has_pue_notice param
	 *
	 * @param string $name           Name of the required plugin
	 * @param null   $thickbox_url   Download or purchase URL for plugin from within /wp-admin/ thickbox
	 * @param bool   $is_active      Indicates if the plugin is installed and active or not
	 * @param string $version        Optional version number of the required plugin
	 * @param bool   $addon          Indicates if the plugin is an add-on for The Events Calendar or Event Tickets
	 * @param bool   $has_pue_notice Indicates that we need to change the messaging due to expired key.
	 */
	public function add_required_plugin( $name, $thickbox_url = null, $is_active = null, $version = null, $addon = false, $has_pue_notice = false ) {
		$this->plugins_required[ $name ] = array(
			'name'           => $name,
			'thickbox_url'   => $thickbox_url,
			'is_active'      => $is_active,
			'version'        => $version,
			'addon'          => $addon,
			'has_pue_notice' => $has_pue_notice,
		);
	}

	/**
	 * Echoes the admin notice, attach to admin_notices
	 */
	public function show_inactive_plugins_alert() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$plugin_data = get_plugin_data( $this->plugin_path );
		$req_plugins = array();

		if ( empty( $this->plugins_required ) ) {
			return;
		}

		$has_pue_notices = false;

		foreach ( $this->plugins_required as $req_plugin ) {
			$item    = $req_plugin['name'];
			$version = empty( $req_plugin['version'] ) ? '' : ' (' . str_replace( '-dev', '', $req_plugin['version'] ) . ')';

			if ( ! empty( $req_plugin['thickbox_url'] ) ) {
				$item = sprintf(
					'<a href="%1$s" class="thickbox" title="%2$s">%3$s%4$s</a>',
					esc_attr( $req_plugin['thickbox_url'] ),
					esc_attr( $req_plugin['name'] ),
					esc_html( $item ),
					esc_html( $version )
				);
			}

			if ( false === $req_plugin['is_active'] ) {
				$item = sprintf(
					'<strong class="tribe-inactive-plugin">%1$s</strong>',
					$item
				);
			}

			if ( ! empty( $req_plugin['addon'] ) ) {
				$plugin_name[] = $req_plugin['name'];
			}

			$req_plugins[] = $item;

			// If any of the items has PUE notice we will warn the user.
			if ( $req_plugin['has_pue_notice'] ) {
				$has_pue_notices = true;
			}
		}

		// If empty then add in the default name.
		if ( empty( $plugin_name[0] ) ) {
			$plugin_name[] = $plugin_data['Name'];
		}

		$allowed_html = array(
			'strong' => array(),
			'a'      => array( 'href' => array() ),
		);

		$plugin_names_clean_text = wp_kses( $this->implode_with_grammar( $plugin_name ), $allowed_html );
		$req_plugin_names_clean_text = wp_kses( $this->implode_with_grammar( $req_plugins ), $allowed_html );

		$notice_html_content = '<p>' . esc_html__( 'To begin using %2$s, please install and activate the latest version of %3$s.', 'tribe-common' ) . '</p>';

		$read_more_link = '<a href="http://m.tri.be/1aev" target="_blank">' . esc_html__( 'Read more.', 'tribe-common' ) . '</a>';
		$pue_notice_text = __( 'There’s a new version of %1$s available, but your license is expired. You’ll need to renew your license to get access to the latest version. If you plan to continue using your current version of the plugin(s), be sure to use a compatible version of The Events Calendar. %2$s', 'tribe-common' );
		$pue_notice_html = '<p>' . sprintf( $pue_notice_text, $plugin_names_clean_text, $read_more_link ) . '</p>';

		printf(
			'<div class="error tribe-notice tribe-dependency-error" data-plugin="%1$s">'
			. $notice_html_content
			. ( $has_pue_notices ? $pue_notice_html : '' )
			. '</div>',
			esc_attr( sanitize_title( $plugin_data['Name'] ) ),
			$plugin_names_clean_text,
			$req_plugin_names_clean_text
		);
	}

	/**
	 * Implodes a list items using 'and' as the final separator and a comma everywhere else
	 *
	 * @param array $items List of items to implode
	 *
	 * @return string String of items
	 */
	public function implode_with_grammar( $items ) {
		$separator   = _x( ', ', 'separator used in a list of items', 'tribe-common' );
		$conjunction = _x( ' and ', 'the final separator in a list of two or more items', 'tribe-common' );
		$output      = $last_item = array_pop( $items );

		if ( $items ) {
			$output = implode( $separator, $items ) . $conjunction . $last_item;
		}

		return $output;
	}

}
