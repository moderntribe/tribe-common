<?php

/**
 * Class Tribe__Process__Queue
 *
 * @since 4.7.12
 *
 * The base class to process queues asynchronously.
 */
abstract class Tribe__Process__Queue extends WP_Background_Process {

	/**
	 * @var string The common identified prefix to all our async process handlers.
	 */
	protected $prefix = 'tribe_queue';

	/**
	 * @var string The base that should be used to build the queue id.
	 */
	protected $id_base;

	/**
	 * @var string The queue unique identifier
	 */
	protected $id;

	/**
	 * @var int How many items this instance processed.
	 */
	protected $done = 0;

	/**
	 * @var int
	 */
	protected $original_batch_count = 0;

	/**
	 * @var int The maximum size of a fragment in bytes.
	 */
	protected $max_frag_size;

	/**
	 * @var bool Whether the current handling is sync or not.
	 */
	protected $doing_sync = false;

	/**
	 * @var bool Whether the queue `save` method was already called or not.
	 */
	protected $did_save = false;

	/**
	 * @var string The batch key used by the queue.
	 */
	protected $batch_key;

	/**
	 * {@inheritdoc}
	 */
	public function __construct() {
		$class        = get_class( $this );
		$this->action = call_user_func( array( $class, 'action' ) );

		parent::__construct();
	}

	/**
	 * Stops a queue that might be running.
	 *
	 * The queue process results are not rolled back (e.g. 200 posts to create, stopped
	 * after 50, those 50 posts will persist).
	 *
	 * @since 4.7.12
	 *
	 * @param string $queue_id The unique identifier of the queue that should be stopped.
	 *
	 * @see   Tribe__Process__Queue::save() to get the queue unique id.
	 */
	public static function stop_queue( $queue_id ) {
		$meta = (array) get_transient( $queue_id . '_meta' );
		delete_transient( $queue_id . '_meta' );

		if ( ! empty( $meta['identifier'] ) ) {
			delete_site_transient( $meta['identifier'] . '_process_lock' );
		}

		return delete_site_option( $queue_id );
	}

	/**
	 * Returns the async process action name.
	 *
	 * Extending classes must override this method to return their unique action slug.
	 *
	 * @since 4.7.12
	 *
	 * @return string
	 *
	 * @throws RuntimeException If the extending class does not override this method.
	 */
	public static function action() {
		$class = get_called_class();
		throw new RuntimeException( "Class {$class} should override the `action` method to define its own unique identifier." );
	}

	/**
	 * Whether a queue process is stuck or not.
	 *
	 * A queue process that has not been doing anything for an amount
	 * of time is considered "stuck".
	 *
	 * @since 4.7.18
	 *
	 * @param string $queue_id The queue process unique identifier.
	 *
	 * @return bool
	 */
	public static function is_stuck( $queue_id ) {
		$queue_status = self::get_status_of( $queue_id );
		$is_stuck     = false;

		/**
		 * Filters the maximum allowed time a queue process can go without updates
		 * before being considered stuck.
		 *
		 * @since 4.7.18
		 *
		 * @param int $time_limit A value in seconds, defaults to 5'.
		 */
		$limit = (float) apply_filters( 'tribe_process_queue_time_limit', 300 );

		if ( ! empty( $queue_status['last_update'] ) && is_numeric( $queue_status['last_update'] ) ) {
			$is_stuck = time() - (int) $queue_status['last_update'] > $limit;
		} else {
			$queue_status['last_update'] = time();
			set_transient( $queue_id . '_meta', $queue_status->to_array(), DAY_IN_SECONDS );
		}

		/**
		 * Filters whether a queue is considered "stuck" or not.
		 *
		 * @since 4.7.18
		 *
		 * @param bool $is_stuck
		 * @param string $queue_id
		 * @param Tribe__Data $queue_status
		 */
		return apply_filters( 'tribe_process_queue_is_stuck', $is_stuck, $queue_id, $queue_status );
	}

	/**
	 * Returns a queue status and information.
	 *
	 * @since 4.7.12
	 *
	 * @param string $queue_id
	 *
	 * @return Tribe__Data An object containing information about the queue.
	 *
	 * @see   Tribe__Process__Queue::save() to get the queue unique id.
	 */
	public static function get_status_of( $queue_id ) {
		$meta = (array) get_transient( $queue_id . '_meta' );
		$data = array(
			'identifier'  => $queue_id,
			'done'        => (int) Tribe__Utils__Array::get( $meta, 'done', 0 ),
			'total'       => (int) Tribe__Utils__Array::get( $meta, 'total', 0 ),
			'fragments'   => (int) Tribe__Utils__Array::get( $meta, 'fragments', 0 ),
			'last_update' => (int) Tribe__Utils__Array::get( $meta, 'last_update', false ),
		);

		return new Tribe__Data( $data, 0 );
	}

	/**
	 * Deletes all queues for a specific action.
	 *
	 * @since 4.7.19
	 *
	 * @param string $action The action (prefix) of the queues to delete.
	 *
	 * @return int The number of delete queues.
	 */
	public static function delete_all_queues( $action ) {
		global $wpdb;

		$table  = $wpdb->options;
		$column = 'option_name';

		if ( is_multisite() ) {
			$table  = $wpdb->sitemeta;
			$column = 'meta_key';
		}

		$action = $wpdb->esc_like( 'tribe_queue_' . $action ) . '%';

		$queues = $wpdb->get_col( $wpdb->prepare( "
			SELECT DISTINCT({$column})
			FROM {$table}
			WHERE {$column} LIKE %s
		", $action ) );

		if ( empty( $queues ) ) {
			return 0;
		}

		$deleted = 0;

		foreach ( $queues as $queue ) {
			$deleted ++;
			self::delete_queue( $queue );
		}

		return $deleted;
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete( $key ) {
		self::delete_queue( $key );

		return $this;
	}

	/**
	 * Deletes a queue batch(es) and meta information.
	 *
	 * @since 4.7.18
	 *
	 * @param string $key
	 */
	public static function delete_queue( $key ) {
		global $wpdb;

		$meta_key = $key . '_meta';

		$table  = $wpdb->options;
		$column = 'option_name';

		if ( is_multisite() ) {
			$table  = $wpdb->sitemeta;
			$column = 'meta_key';
		}

		$key = $wpdb->esc_like( $key ) . '%';

		$wpdb->query( $wpdb->prepare( "
			DELETE
			FROM {$table}
			WHERE {$column} LIKE %s
		", $key ) );

		delete_transient( $meta_key );
	}

	/**
	 * {@inheritdoc}
	 */
	public function update( $key, $data ) {
		$meta_key = $this->get_meta_key( $key );
		$meta     = (array) get_transient( $meta_key );
		$done     = $this->original_batch_count - count( $data );

		$update_data = array_merge( $meta, array(
			'done'        => $meta['done'] + $done,
			'last_update' => time(),
		) );

		/**
		 * Filters the information that will be updated in the database for this queue type.
		 *
		 * @since 4.7.12
		 *
		 * @param array $update_data
		 * @param self $this
		 */
		$update_data = apply_filters( "tribe_process_queue_{$this->identifier}_update_data", $update_data, $this );

		set_transient( $meta_key, $update_data, DAY_IN_SECONDS );

		return parent::update( $key, $data );
	}

	/**
	 * Returns the name of the transient that will store the queue meta information
	 * for the specific key.
	 *
	 * @since 4.7.12
	 *
	 * @param string $key
	 *
	 * @return string
	 */
	public function get_meta_key( $key ) {
		$key = preg_replace( '/^(.*)_\\d+$/', '$1', $key );

		return $key . '_meta';
	}

	/**
	 * {@inheritdoc}
	 */
	public function save() {
		$key = $this->generate_key();

		$fragments_count = $this->save_split_data( $key, $this->data );

		$save_data = array(
			'identifier'  => $this->identifier,
			'done'        => 0,
			'total'       => count( $this->data ),
			'fragments'   => $fragments_count,
			'last_update' => time(),
		);

		$this->id = $key;

		/**
		 * Filters the information that will be saved to the database for this queue type.
		 *
		 * @since 4.7.12
		 *
		 * @param array $save_data
		 * @param self $this
		 */
		$save_data = apply_filters( "tribe_process_queue_{$this->identifier}_save_data", $save_data, $this );

		set_transient( $this->get_meta_key( $key ), $save_data );

		$this->did_save = true;

		return $this;
	}

	/**
	 * Generates the unique key for the queue optionally using the client provided
	 * id.
	 *
	 * @since 4.7.12
	 *
	 * @return string
	 */
	protected function generate_key( $length = 64 ) {
		if ( empty( $this->id_base ) ) {
			$this->id_base = md5( microtime() . mt_rand() );
		}

		$prepend = $this->identifier . '_batch_';

		$this->batch_key = substr( $prepend . $this->id_base, 0, $length );

		return $this->batch_key;
	}

	/**
	 * Saves the queue data to the database taking max_packet_size into account.
	 *
	 * In some instances the serialized size of the data might be bigger than the
	 * database `max_packet_size`; trying to write all the data in one query would
	 * make the db "go away...".
	 * Here we try to read the database `max_packet_size` setting and use that information
	 * to avoid overloading the query.
	 *
	 * @param       string $key
	 * @param array $data
	 *
	 * @return int The number of fragments the data was split and stored into.
	 */
	protected function save_split_data( $key, array $data ) {
		if ( empty( $data ) ) {
			return 0;
		}

		$max_frag_size = $this->get_max_frag_size();
		// we add a 15% to the size to take the serialization and query overhead into account when fragmenting
		$serialized_size = ( strlen( utf8_decode( maybe_serialize( $data ) ) ) ) * 1.15;
		$frags_count     = (int) ceil( $serialized_size / $max_frag_size );
		$per_frag        = max( (int) floor( count( $data ) / $frags_count ), 1 );

		$split_data = array_chunk( $data, $per_frag );

		if ( empty( $split_data ) ) {
			return 0;
		}

		foreach ( $split_data as $i => $iValue ) {
			$postfix = 0 === $i ? '' : "_{$i}";
			update_site_option( $key . $postfix, $split_data[ $i ] );
		}

		return count( $split_data );
	}

	/**
	 * Returns the max frag size in bytes.
	 *
	 * The bottleneck here is the database `max_packet_size` so we try to read
	 * it from the database.
	 *
	 * @return int The max size, in bytes, of a data fragment.
	 */
	protected function get_max_frag_size() {
		if ( ! empty( $this->max_frag_size ) ) {
			return $this->max_frag_size;
		}

		return tribe( 'db' )->get_max_allowed_packet_size();
	}

	/**
	 * Sets the maximum size, in bytes, of the queue fragments.
	 *
	 * This will prevent the class from trying to read the value from the database.
	 *
	 * @since 4.7.12
	 *
	 * @param int $max_frag_size
	 */
	public function set_max_frag_size( $max_frag_size ) {
		$this->max_frag_size = $max_frag_size;
	}

	/**
	 * Returns the queue unique identifier.
	 *
	 * Mind that an id will only be available after saving a queue.
	 *
	 * @since 4.7.12
	 *
	 * @return string
	 * @throws RuntimeException if trying to get the queue id before saving it.
	 */
	public function get_id() {
		if ( null === $this->id ) {
			// not localized as this is a developer-land error
			throw new RuntimeException( 'Can only get the id of queue after saving it.' );
		}

		return $this->id;
	}

	/**
	 * Sets the queue unique id.
	 *
	 * When using this method the client code takes charge of the queue id uniqueness;
	 * the class will not check it.
	 *
	 * @since 4.7.12
	 *
	 * @param string $queue_id
	 *
	 * @throws RuntimeException If trying to set the queue id after saving it.
	 */
	public function set_id( $queue_id ) {
		if ( $this->did_save ) {
			throw new RuntimeException( 'The queue id can be set only before saving it.' );
		}

		$queue_id = preg_replace( '/^' . preg_quote( $this->identifier, '/' ) . '_batch_/', '', $queue_id );

		$this->id_base = $queue_id;
	}

	/**
	 * Overrides the base `dispatch` method to allow for constants and/or environment vars to run
	 * async requests in sync mode.
	 *
	 * @since 4.7.12
	 *
	 * @return mixed
	 */
	public function dispatch() {
		if (
			( defined( 'TRIBE_NO_ASYNC' ) && true === TRIBE_NO_ASYNC )
			|| true == getenv( 'TRIBE_NO_ASYNC' )
			|| (bool) tribe_get_request_var( 'tribe_queue_sync', false )
			|| tribe_is_truthy( tribe_get_option( 'tribe_queue_sync', false ) )
		) {
			$result = $this->sync_process( $this->data );
			$this->complete();

			return $result;
		}

		return parent::dispatch();
	}

	/**
	 * Handles the process immediately, not in an async manner.
	 *
	 * @since 4.7.12
	 *
	 * @return array An array containing the result of each item handling.
	 */
	public function sync_process() {
		$result           = array();
		$this->doing_sync = true;

		foreach ( $this->data as $item ) {
			$result[] = $this->task( $item );
		}

		return $result;
	}

	/**
	 * Returns the name of the option used by the queue to store its batch(es).
	 *
	 * Mind that this value will be set only when first saving the queue and it will not be set
	 * in following queue processing.
	 *
	 * @since 4.7.12
	 *
	 * @param int $n The number of a specific batch option name to get; defaults to `0` to get the
	 *               option name of the first one.
	 *
	 * @return string
	 *
	 * @throws RuntimeException If trying to get the value before saving the queue or during following
	 *                          processing.
	 */
	public function get_batch_key( $n = 0 ) {
		if ( null === $this->batch_key || ! $this->did_save ) {
			throw new RuntimeException( 'The batch key will only be set after the queue is first saved' );
		}

		return empty( $n ) ? $this->batch_key : $this->batch_key . '_' . (int) $n;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function get_batch() {
		$batch = parent::get_batch();

		$this->original_batch_count = ! empty( $batch->data ) ? count( $batch->data ) : 0;

		return $batch;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function get_post_args() {
		$post_args = parent::get_post_args();

		/**
		 * While sending the data into the body makes sense for the async process it does
		 * not make sense when processing a queue since the data will be stored and read
		 * from the database; furthermore this could raise issues with the max POST size.
		 */
		$post_args['body'] = array();

		return $post_args;
	}
}
