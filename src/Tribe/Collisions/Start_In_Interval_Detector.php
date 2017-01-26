<?php
/**
 * Class Tribe__Collisions__Matching_Start_End_Detector
 *
 * A collision happens when a segment has its start contained in another segment.
 */
class Tribe__Collisions__Start_In_Interval_Detector
	extends Tribe__Collisions__Detection_Strategy
	implements Tribe__Collisions__Detector_Interface {

	/**
	 * Detects the collision of a segment with specified start and end points.
	 *
	 * @param array $segment  An array defining the end and start of a segment in the format [<start>, <end>].
	 * @param array $b_starts An array of starting points from the diff array
	 * @param array $b_ends   An array of end points form the diff array
	 *
	 * @return bool Whether a collision was detected or not.
	 */
	protected function detect_collision( array $segment, array $b_starts, array $b_ends ) {
		$start = $segment[0];

		$intervals = array();
		$count     = count( $b_starts );
		for ( $i = 0; $i < $count; $i ++ ) {
			$intervals[] = array( $b_starts[ $i ], $b_ends[ $i ] );
		}

		foreach ( $intervals as $interval ) {
			if ( $interval[0] <= $start && $interval[1] >= $start ) {
				return true;
			}
		}

		return false;
	}
}