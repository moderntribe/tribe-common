<?php


namespace Tribe\Tests;


class Data implements \ArrayAccess {
	/**
	 * @var array The array that contains the data.
	 */
	protected $data;

	/**
	 * @var The default value returned when a value is not found in the data
	 */
	protected $default;

	public function __construct( $data, $default = false ) {
		$this->data = (array) $data;
		$this->default = $default;
	}

	/**
	 * Whether a offset exists
	 *
	 * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
	 * @param mixed $offset <p>
	 *                      An offset to check for.
	 *                      </p>
	 * @return boolean true on success or false on failure.
	 *                      </p>
	 *                      <p>
	 *                      The return value will be casted to boolean if non-boolean was returned.
	 * @since 4.11.0
	 */
	public function offsetExists( $offset ) {
		return isset( $this->data[ $offset ] );
	}

	/**
	 * Offset to retrieve
	 *
	 * @link  http://php.net/manual/en/arrayaccess.offsetget.php
	 * @param mixed $offset <p>
	 *                      The offset to retrieve.
	 *                      </p>
	 * @return mixed Can return all value types.
	 * @since 4.11.0
	 */
	public function offsetGet( $offset ) {
		return isset( $this->data[ $offset ] )
			? $this->data[ $offset ]
			: $this->default;
	}

	/**
	 * Offset to set
	 *
	 * @link  http://php.net/manual/en/arrayaccess.offsetset.php
	 * @param mixed $offset <p>
	 *                      The offset to assign the value to.
	 *                      </p>
	 * @param mixed $value  <p>
	 *                      The value to set.
	 *                      </p>
	 * @return void
	 * @since 4.11.0
	 */
	public function offsetSet( $offset, $value ) {
		$this->data[ $offset ] = $value;
	}

	/**
	 * Offset to unset
	 *
	 * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
	 * @param mixed $offset <p>
	 *                      The offset to unset.
	 *                      </p>
	 * @return void
	 * @since 4.11.0
	 */
	public function offsetUnset( $offset ) {
		unset( $this->data[ $offset ] );
	}
}