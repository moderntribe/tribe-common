<?php

/**
 * Class Tribe__Collisions__Matching_Start_Detector
 *
 * A collision happens when two segments have the same start.
 */
class Tribe__Collisions__Matching_Start_Detector
	extends Tribe__Collisions__Detection_Strategy
	implements Tribe__Collisions__Detector_Interface {

	/**
	 * Detects the collision of a segment with specified start and end points.
	 *
	 * @param array $segment  An array defining the end and start of a segment in the format [<start>, <end>].
	 * @param array $b_starts An array of starting points from the diff array
	 * @param array $b_ends   An array of end points form the diff array
	 * @return bool|array Whether a collision was detected or not or the colliding "b" segment if $report is `true`
	 *
	 * @return bool Whether a collision was detected or not.
	 */
	protected function detect_collision( array $segment, array $b_starts, array $b_ends, $report = false ) {
		$index = array_search( $segment[0], $b_starts );

		if ( false === $index ) {
			return false;
		}

		return $report ? array( $b_starts[ $index ], $b_ends[ $index ] ) : false !== $index;
	}
}