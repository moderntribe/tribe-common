<?php
/**
 * Link Utilities
 *
 * @since   TBD
 * @package Tribe\Utils
 */

namespace Tribe\Utils;
class Links {
	/**
	 * Contains the local host.
	 *
	 * @var string
	 */
	public $local_host;

	/**
	 * Register allthethings!
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function register() {
		$this->local_host = strtolower( wp_parse_url( home_url() ) );
	}

	/**
	 * Normalize the url.
	 *
	 * @since TBD
	 *
	 * @param string $url
	 * @return string
	 */
	public function normalize( $url ) {
		// strip off "www";
		$url = preg_replace('/^www\./', '', $url );

		return $url;

	}

	public function __return_blank() {
		return '__blank';
	}

	/**
	 * Get the link host.
	 *
	 * @since TBD
	 *
	 * @param string $url The URL to assess.
	 *
	 * @return string The host portion of the URL.
	 */
	public function get_link_host( $url ) {
		$url_components   = wp_parse_url( $url );
		if ( empty( $url_components['host'] ) ) {
			$url_components['host'] = '';
		}
		return strtolower( $url_components['host'] );
	}

	/**
	 * Determine if a URL is relative.
	 *
	 * @since TBD
	 *
	 * @param string $url The URL to assess.
	 *
	 * @return boolean
	 */
	public function is_relative_url( $url ) {
		if ( empty( $url ) ) {
			return false;
		}

		$url_host = trim( $this->get_link_host( $url ) );

		return empty( $url_host );
	}

	/**
	 * Determine if a URL is a local subdomain.
	 *
	 * @since TBD
	 *
	 * @param string $url The URL to assess.
	 *
	 * @return boolean
	 */
	public function is_local_subdomain( $url ) {
		// Prevent issue with "www" and such.
		$normalized_url   = $this->normalize( $url );
		$normalized_local = $this->normalize( $this->get_link_host( $this->local_host ) );

		return (bool) strrpos( $normalized_url, $normalized_local );
	}

	/**
	 * Determine if a link is local to the site.
	 *
	 * @since TBD
	 *
	 * @param string $url The URL to assess.
	 *
	 * @return boolean
	 */
	public function is_local_link( $url ) {
		$url_host     = $this->normalize( $this->get_link_host( $url ) );
		$local_hosted = $this->normalize( $this->get_link_host( $this->local_host ) );

		return $this->is_relative_url( $url )
				|| $this->is_local_subdomain( $url )
				|| 0 === strcasecmp( $url_host, $local_hosted );
	}

	/**
	 * Get the appropriate rel attribute for a link.
	 *
	 * @since TBD
	 *
	 * @param string $url The URL to assess.
	 *
	 * @return string The value of the rel attribute.
	 */
	public function get_rel_attr( $url ) {
		// The rel attribute by default is empty.
		$rel = '';

		// For external links, use "external"
		if ( ! $this->is_local_link( $url ) ) {
			$rel = 'external';
		}

		// Safety dance!
		if ( '_blank' === $this->get_target_attr( $url ) ) {
			$rel .= empty( $rel ) ? 'noopener noreferrer' : ' noopener noreferrer';
		}

		return apply_filters( 'tribe_get_link_rel_attribute', $rel );
	}

	/**
	 * Get the appropriate target for a link.
	 *
	 * @since TBD
	 *
	 * @param string $url The URL to assess.
	 *
	 * @return string The value of the target attribute.
	 */
	public function get_target_attr( $url ) {
		// The default target is _self -> the same window/tab.
		$target = "_self";

		/**
		 * Allows filtering the default target.
		 * @param string $target The default target value.
		 * @param string $url The URL we are setting the target for.
		 */
		return apply_filters( 'tribe_get_link_target_attribute', $target, $url );
	}

	/**
	 * Build the args array.
	 *
	 * @since TBD
	 *
	 * @param string $url The URL we are building a link for.
	 * @param array  $args  Additional arguments. This should be additional attributes in format
	 * 							[
	 *								'class' => 'fnord',
	 *								'download' => false,
	 * 							]
	 * 							Where false values will be purposefully ignored.
	 * @return array
	 */
	public function build( $url, $args = [] ) {
		if ( isset( $args['target'] ) && '_blank' === $args['target'] ) {
			add_filter(
				'tribe_get_link_target_attribute',
				[
					$this,
					'__return_blank'
				]
			);
		}

		$default_args = [
			'target' => empty( $this->get_target_attr( $url ) ) ? false : esc_attr( $this->get_target_attr( $url ) ),
			'rel'    => empty( $this->get_rel_attr( $url ) ) ? false : esc_attr( $this->get_rel_attr( $url ) ),
		];

		remove_filter(
			'tribe_get_link_target_attribute',
			[
				$this,
				'__return_blank'
			]
		);

		return wp_parse_args( $args, $default_args );
	}

	/**
	 * Build the attributes string.
	 *
	 * @since TBD
	 *
	 * @param string $url The URL we are building the attributes for.
	 * @param array  $additional_args  Additional arguments. This should be additional attributes in format
	 *                                 [
	 *                                     'class' => 'fnord',
	 *                                     'download' => false,
	 *                                 ]
	 * 							Where false values will be purposefully ignored.
	 * @return string
	 */
	public function build_attr_string( $url, $additional_args = [] ) {
		$args = $this->build( $url, $additional_args );

		foreach ( $args as $key => $val ) {
				// Explicitly setting false skips the attribute.
				if ( false === $val ) {
					unset( $args[ $key ] );
					continue;
				}

				// Using preg_replace since for the key we don't want to convert special chars, but remove them.
				$key = preg_replace('/[^A-Za-z_]/', '', $key);

				// Setting true or an empty string is a no-value attribute.
				if ( true === $val || '' === $val ) {
					$args[ $key ] = esc_attr( $key );
					continue;
				}

				//$key="$value" attribute.
				$args[ $key ] = esc_html( $key ) .'="'. esc_attr( $val ) .'"';
			}

		$args = implode( ' ', $args );

		return $args;
	}

	/**
	 * Get the HTML for a link
	 *
	 * @since TBD
	 *
	 * @param string  $url   The URL to assess.
	 * @param string  $label The link text.
	 * @param boolean $echo  Echo or return the string.
	 *
	 * @return string|void HTML string return, or echo, determined by $echo, above.
	 */
	public function render( $url, $label, $additional_args = [], $echo = false ) {
		$html   = sprintf(
			'<a href="%s" %s>%s</a>',
			esc_url( $url ),
			$this->build_attr_string( $url, $additional_args ),
			esc_html( $label )
		);

		if ( empty( $echo ) ) {
			return $html;
		}

		echo $html;
	}

}
