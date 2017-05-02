<?php

class Tribe__Collisions__Closest_Unique_Start_Detector
	extends Tribe__Collisions__Detection_Strategy
	implements Tribe__Collisions__Detector_Interface {

	/**
	 * The segment that's currently being collided.
	 * @var array
	 */
	protected $segment;

	/**
	 * Computes the collision-based difference of two or more arrays of segments returning an array of elements from
	 * the first array not colliding with any element from the second array according to the collision detection
	 * strategy implemented by the class.
	 *
	 * Note: points are segments with matching start and end.
	 *
	 * @param array $set_a     An array of elements each defining the start and end of a segment in the format
	 *                         [<start>,
	 *                         <end>].
	 * @param array $set_b,... An array (ore more arrays) of elements each defining the start and end of a segment in
	 *                         the format [<start>, <end>].
	 *
	 * @return array An array of elements each defining the start and end of a segment in the format [<start>, <end>].
	 */
	public function diff( array $set_a, array $set_b ) {
		if ( empty( $set_b ) ) {
			return $set_a;
		}

		return array();
	}

	/**
	 * Computes the collision-based intersection of two or more arrays of segments returning an array of elements from
	 * the first array colliding with at least one element from the second array according to the collision detection
	 * strategy implemented by the class.
	 * If more than one array is specified then a segment from the first array has to collide with at least one segment
	 * from each intersecting segment.
	 * If a lax intersection is needed use `touch`.
	 *
	 * Note: points are segments with matching start and end.
	 *
	 * @see Tribe__Collisions__Detection_Strategy::touch()
	 *
	 * @param array $set_a     An array of elements each defining the start and end of a segment in the format
	 *                         [<start>,
	 *                         <end>].
	 * @param array $b_set,... An array (ore more arrays) of elements each defining the start and end of a segment in
	 *                         the format [<start>, <end>].
	 *
	 * @return array An array of elements each defining the start and end of a segment in the format [<start>, <end>].
	 */
	public function intersect( array $set_a, array $b_set ) {
		if ( empty( $set_a ) ) {
			return array();
		}

		$b_sets = func_get_args();
		array_shift( $b_sets );

		$b_sets = array_filter( $b_sets );
		if ( empty( $b_sets ) ) {
			return array();
		}

		$reported = call_user_func_array( array( $this, 'report_intersect' ), func_get_args() );

		return reset( $reported );
	}

	/**
	 * Computes the collision-based lax intersection of two or more arrays of segments returning an array of elements
	 * from the first array colliding with at least one element from the second array according to the collision
	 * detection strategy implemented by the class. If more than one array is specified then a segment from the first
	 * array has to collide with at least one segment from one of the intersecting segment arrays; this method will
	 * work exactly like `intersect` when applied to 1 vs 1 arrays of segments; the "lax" part comes into play when
	 * used in 1 vs many.
	 *
	 * @see Tribe__Collisions__Detection_Strategy::intersect()
	 *
	 * Note: points are segments with matching start and end.
	 *
	 * @param array $set_a     An array of elements each defining the start and end of a segment in the format
	 *                         [<start>,
	 *                         <end>].
	 * @param array $set_b,... An array (ore more arrays) of elements each defining the start and end of a segment in
	 *                         the format [<start>, <end>].
	 *
	 * @return array An array of elements each defining the start and end of a segment in the format [<start>, <end>].
	 */
	public function touch( array $set_a, array $set_b ) {
		if ( empty( $set_a ) ) {
			return array();
		}

		return call_user_func_array( array( $this, 'intersect' ), func_get_args() );
	}

	/**
	 * Computes the collision-based intersection of two or more arrays of segments returning an array of elements from
	 * the first array colliding with at least one element from the second array according to the collision detection
	 * strategy implemented by the class.
	 * If more than one array is specified then a segment from the first array has to collide with at least one segment
	 * from each intersecting segment.
	 * If a lax intersection is needed use `touch`.
	 *
	 * Note: points are segments with matching start and end.
	 *
	 * @see Tribe__Collisions__Detection_Strategy::touch()
	 *
	 * @param array $set_a     An array of elements each defining the start and end of a segment in the format
	 *                         [<start>,
	 *                         <end>].
	 * @param array $set_b,... An array (ore more arrays) of elements each defining the start and end of a segment in
	 *                         the format [<start>, <end>].
	 *
	 * @return array An array of arrays of elements each defining the start and end of a segment in the format
	 *               [<start>, <end>]; the first array contains the segments of $a that collided while the second array
	 *               contains the segments that did collide with each colliding element of $a
	 */
	public function report_intersect( array $set_a, array $set_b ) {
		if ( empty( $set_a ) ) {
			return array();
		}

		$b_sets = func_get_args();
		$set_a = array_shift( $b_sets );

		// remove duplicates
		$set_a = array_map( 'unserialize', array_unique( array_map( 'serialize', $set_a ) ) );

		$closest_as = array();
		foreach ( $b_sets as $set_b ) {
			foreach ( $set_b as $b_segment ) {
				// find the a closest to each b
				$this->segment = $b_segment;
				$closest_a = array_reduce( $set_a, array( $this, 'find_closest_segment' ), - 1 );
				$key = serialize( $closest_a );
				if ( ! isset( $closest_as[ $key ] ) ) {
					$closest_as[ $key ] = array();
				}
				$closest_as[ $key ][] = $b_segment;
			}
		}

		$a_and_closest_b_sets = array();
		foreach ( $closest_as as $key => $closest_b_sets ) {
			$a_segment = unserialize( $key );

			if ( count( $closest_b_sets ) === 1 ) {
				$closest_b = reset( $closest_b_sets );
			} else {
				$initial = array_shift( $closest_b_sets );
				$this->segment = $a_segment;
				$closest_b = array_reduce( $closest_b_sets, array( $this, 'find_closest_segment' ), $initial );
			}

			$i = array_search( $closest_b, $a_and_closest_b_sets );
			if ( false === $i ) {
				$a_and_closest_b_sets[ $key ] = $closest_b;
			} else {
				// only the a closest to this b survives
				$current_closest_a = unserialize( $i );
				$this->segment = $closest_b;
				$input = array( $current_closest_a, $a_segment );
				$new_closest_a = array_reduce( $input, array( $this, 'find_closest_segment' ), - 1 );
				if ( $new_closest_a !== $current_closest_a ) {
					unset( $a_and_closest_b_sets[ $i ] );
					$a_and_closest_b_sets[ serialize( $new_closest_a ) ] = $closest_b;
				}
			}
		}

		uksort( $a_and_closest_b_sets, array( $this, 'compare_serialized_starts' ) );

		$intersected = array_map( 'unserialize', array_keys( $a_and_closest_b_sets ) );
		$intersecting = array_values( $a_and_closest_b_sets );

		return array( $intersected, $intersecting );
	}

	/**
	 * Computes the collision-based lax intersection of two or more arrays of segments returning an array of elements
	 * from the first array colliding with at least one element from the second array according to the collision
	 * detection strategy implemented by the class. If more than one array is specified then a segment from the first
	 * array has to collide with at least one segment from one of the intersecting segment arrays; this method will
	 * work exactly like `intersect` when applied to 1 vs 1 arrays of segments; the "lax" part comes into play when
	 * used in 1 vs many.
	 *
	 * @see Tribe__Collisions__Detection_Strategy::intersect()
	 *
	 * Note: points are segments with matching start and end.
	 *
	 * @param array $set_a     An array of elements each defining the start and end of a segment in the format [<start>,
	 *                     <end>].
	 * @param array $set_b,... An array (ore more arrays) of elements each defining the start and end of a segment in the
	 *                     format [<start>, <end>].
	 *
	 * @return array An array of arrays of elements each defining the start and end of a segment in the format
	 *               [<start>, <end>]; the first array contains the segments of $a that collided while the second array
	 *               contains the segments that did collide with each colliding element of $a
	 */
	public function report_touch( array $set_a, array $set_b ) {
		if ( empty( $set_a ) ) {
			return array();
		}

		return call_user_func_array( array( $this, 'report_intersect' ), func_get_args() );
	}

	/**
	 * Detects the collision of a segment with specified start and end points.
	 *
	 * @param array $segment  An array defining the end and start of a segment in the format [<start>, <end>].
	 * @param array $b_starts An array of starting points from the diff array
	 * @param array $b_ends   An array of end points form the diff array
	 * @param bool  $report   Whether the colliding "b" segment should be returned or not.
	 *
	 * @return bool|array Whether a collision was detected or not or the colliding "b" segment if $report is `true`
	 */
	protected function detect_collision( array $segment, array $b_starts, array $b_ends, $report = false ) {
		if ( empty( $b_starts ) ) {
			return false;
		}

		return true;
	}

	protected function find_closest_segment( $current_closest, $candidate ) {
		if ( - 1 === $current_closest ) {
			// first iteration
			return $candidate;
		}

		$start = $this->segment[0];
		$distance = abs( $candidate[0] - $start );
		$current_closest_distance = abs( $current_closest[0] - $start );

		return $distance < $current_closest_distance ? $candidate : $current_closest;
	}

	protected function compare_serialized_starts( $a, $b ) {
		return $this->compare_starts( unserialize( $a ), unserialize( $b ) );
	}
}