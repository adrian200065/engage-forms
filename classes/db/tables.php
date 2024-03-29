<?php


/**
 * Class Engage_Forms_DB_Tables
 */
class Engage_Forms_DB_Tables {

	/**
	 * WPDB instance
	 *
	 * @since 1.5.1
	 *
	 * @var wpdb
	 */
	protected  $wpdb;

	/**
	 * Charector collation
	 *
	 * @since 1.5.1
	 *
	 * @var string
	 */
	protected  $charset_collate;

	/**
	 * Max index length
	 *
	 * @since 1.5.1
	 *
	 * @var int
	 */
	protected  $max_index_length = 191;

	/**
	 * List of missing tables
	 *
	 * @since 1.5.4
	 *
	 * @var array
	 */
	protected $missing_tables;

	/**
	 * Engage_Forms_DB_Tables constructor.
	 *
	 * @since 1.5.1
	 *
	 * @param wpdb $wpdb
	 */
	public function __construct( wpdb $wpdb ){
		$this->wpdb = $wpdb;


	}

	/**
	 * Add EF tables if they are missing
	 *
	 * @since 1.5.1
	 */
	public function add_if_needed(){
		$this->missing_tables = $this->find_missing_tables();
		if( empty( $this->missing_tables ) ){
			return;
		}

		$this->set_charset();

		$search = $this->wpdb->prefix . 'ef_';
		foreach( $this->missing_tables as $table ){
			call_user_func( array( $this, str_replace( $search, '', $table ) ) );
		}


	}

	/**
	 * Get list of missing tables
	 *
	 * @since 1.5.4
	 *
	 * @return array
	 */
	public function get_missing_tables(){
		return $this->missing_tables;
	}

	/**
	 * Find any missing Engage Forms tables
	 *
	 * @return array
	 */
	protected function find_missing_tables(){
		$tables = $this->wpdb->get_results( "SHOW TABLES", ARRAY_A );
		$alltables = array();

		foreach ( $tables as $table ) {
			$alltables[] = implode( $table );
		}

		$missing_tables = array();
		foreach ( $this->get_tables_list()  as  $ef_table ){
			if( ! in_array( $ef_table, $alltables ) ){
				$missing_tables[] = $ef_table;
			}

		}

		return $missing_tables;

	}

	/**
	 * Get the list of Engage Forms tables, with wpdb prefix
	 *
	 * @since 1.5.1
	 *
	 * @return array
	 */
	protected function get_tables_list(){

		$tables = array(
			'ef_form_entry_values',
			'ef_form_entry_meta',
			'ef_form_entries',
			'ef_form_entry_values',
			'ef_forms',
			'ef_queue_failures',
			'ef_queue_jobs',
		);

		if( function_exists( 'engage_forms_pro_is_active') && engage_forms_pro_is_active() ){
		    $tables[] = 'ef_pro_messages';
        }

		foreach ( $tables as &$table ){
			$table = $this->wpdb->prefix . $table;
		}

		return $tables;
	}

	/**
	 * Add ef_form_entry_values table
	 *
	 * Warning: does not check if it exists first, which could cause SQL errors.
	 *
	 * @since 1.5.1
	 */
	public function form_entry_values(){
		$this->set_charset();

		$values_table = "CREATE TABLE `" . $this->wpdb->prefix . "ef_form_entry_values` (
				`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`entry_id` int(11) NOT NULL,
				`field_id` varchar(20) NOT NULL,
				`slug` varchar(255) NOT NULL DEFAULT '',
				`value` longtext NOT NULL,
				PRIMARY KEY (`id`),
				KEY `form_id` (`entry_id`),
				KEY `field_id` (`field_id`),
				KEY `slug` (`slug`(" . $this->max_index_length . "))
				) " . $this->charset_collate . ";";

		dbDelta( $values_table );
	}

	/**
	 * Add ef_form_entry_meta table
	 *
	 * Warning: does not check if it exists first, which could cause SQL errors.
	 *
	 * @since 1.5.1
	 */
	public function form_entry_meta(){
		$this->set_charset();

		$meta_table = "CREATE TABLE `" . $this->wpdb->prefix . "ef_form_entry_meta` (
			`meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			`entry_id` bigint(20) unsigned NOT NULL DEFAULT '0',
			`process_id` varchar(255) DEFAULT NULL,
			`meta_key` varchar(255) DEFAULT NULL,
			`meta_value` longtext,
			PRIMARY KEY (`meta_id`),
			KEY `meta_key` (meta_key(" . $this->max_index_length . ")),
			KEY `entry_id` (`entry_id`)
			) " . $this->charset_collate . ";";

		dbDelta( $meta_table );

	}
	/**
	 * Add the ef_tracking table
	 *
	 * Warning: does not check if it exists first, which could cause SQL errors.
	 *
	 * @since 1.5.1
	 */
	public function tracking(){
		$this->set_charset();
		$tacking_table = "CREATE TABLE `" . $this->wpdb->prefix . "ef_tracking` (
			`ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			`form_id` varchar(255) DEFAULT NULL,
			`process_id` varchar(255) DEFAULT NULL,
			PRIMARY KEY (`ID`)
			) " . $this->charset_collate . ";";

		dbDelta( $tacking_table );
	}

	/**
	 * Add the ef_tracking_meta table
	 *
	 * Warning: does not check if it exists first, which could cause SQL errors.
	 *
	 * @since 1.5.1
	 */
	public function tracking_meta(){
		$this->set_charset();
		$meta_table = "CREATE TABLE `" . $this->wpdb->prefix . "ef_tracking_meta` (
			`meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			`event_id` bigint(20) unsigned NOT NULL DEFAULT '0',
			`meta_key` varchar(255) DEFAULT NULL,
			`meta_value` longtext,
			PRIMARY KEY (`meta_id`),
			KEY `meta_key` (`meta_key`(" . $this->max_index_length . ")),
			KEY `event_id` (`event_id`)
			) " . $this->charset_collate . ";";

		dbDelta( $meta_table );
	}

	/**
	 * Add the ef_form_entries table
	 *
	 * Warning: does not check if it exists first, which could cause SQL errors.
	 *
	 * @since 1.5.1
	 */
	public function form_entries(){
		$this->set_charset();
		$entry_table = "CREATE TABLE `" . $this->wpdb->prefix . "ef_form_entries` (
				`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`form_id` varchar(18) NOT NULL DEFAULT '',
				`user_id` int(11) NOT NULL,
				`datestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`status` varchar(20) NOT NULL DEFAULT 'active',
				PRIMARY KEY (`id`),
				KEY `form_id` (`form_id`),
				KEY `user_id` (`user_id`),
				KEY `date_time` (`datestamp`),
				KEY `status` (`status`)
				) " . $this->charset_collate . ";";


		dbDelta( $entry_table );
	}

	/**
	 * Add ef_form_entries table
	 *
	 * Warning: does not check if it exists first, which could cause SQL errors.
	 *
	 * @since 1.5.1
	 */
	public function  ef_form_entries(){
		$this->set_charset();

		$entry_table = "CREATE TABLE `" . $this->wpdb->prefix . "ef_form_entries` (
				`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`form_id` varchar(18) NOT NULL DEFAULT '',
				`user_id` int(11) NOT NULL,
				`datestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`status` varchar(20) NOT NULL DEFAULT 'active',
				PRIMARY KEY (`id`),
				KEY `form_id` (`form_id`),
				KEY `user_id` (`user_id`),
				KEY `date_time` (`datestamp`),
				KEY `status` (`status`)
				) " . $this->charset_collate . ";";

		dbDelta( $entry_table );
	}

	/**
	 * Add ef_forms table
	 *
	 * Warning: does not check if it exists first, which could cause SQL errors.
	 *
	 * @since 1.5.3
	 */
	public function forms(){
		$this->set_charset();

		$forms_table = "CREATE TABLE `" . $this->wpdb->prefix . "ef_forms` (
				`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`form_id` varchar(18) NOT NULL DEFAULT '',
				`type` varchar(255) NOT NULL DEFAULT '',
				`config` longtext NOT NULL,
				PRIMARY KEY (`id`),
				KEY `form_id` (`form_id`)
				) " . $this->charset_collate . ";";
		dbDelta( $forms_table );
	}


    /**
     * Add ef_pro_messages table
     *
     * Warning: does not check if it exists first, which could cause SQL errors.
     *
     * @since 1.6.2
     */
	public function pro_messages(){
        if ( function_exists( 'engage_forms_pro_db_delta_1' ) ) {
            engage_forms_pro_db_delta_1();
            engage_forms_pro_db_delta_2();
        }
    }


	/**
	 * Install database table for job queue
	 *
	 * @since 1.8.0
	 */
	public function queue_jobs()
	{
		global $wpdb;

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		$wpdb->hide_errors();
		$charset_collate = $wpdb->get_charset_collate();
		$table = \engagewp\engageforms\ef2\Jobs\DatabaseConnection::QUEUED_JOBS_TABLE;
		$sql = "CREATE TABLE {$wpdb->prefix}{$table} (
				id bigint(20) NOT NULL AUTO_INCREMENT,
				job longtext NOT NULL,
				attempts tinyint(3) NOT NULL DEFAULT 0,
				reserved_at datetime DEFAULT NULL,
				available_at datetime NOT NULL,
				created_at datetime NOT NULL,
				PRIMARY KEY  (id)
				) $charset_collate;";

		dbDelta($sql);


	}

	/**
	 * Install database table for job queue fails
	 *
	 * @since 1.8.0
	 */
	public function queue_failures(){
		global $wpdb;

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		$wpdb->hide_errors();
		$charset_collate = $wpdb->get_charset_collate();
		$table = \engagewp\engageforms\ef2\Jobs\DatabaseConnection::FAILED_JOBS_TABLE;
		$sql = "CREATE TABLE {$wpdb->prefix}{$table} (
				id bigint(20) NOT NULL AUTO_INCREMENT,
				job longtext NOT NULL,
				error text DEFAULT NULL,
				failed_at datetime NOT NULL,
				PRIMARY KEY  (id)
				) $charset_collate;";

		dbDelta( $sql );
	}


	/**
	 * Set the charset_collate property if not set
	 *
	 * @since 1.5.1
	 */
	protected function set_charset(  ){
		if( is_string( $this->charset_collate ) ){
			return;
		}
		$charset_collate = '';

		if ( ! empty( $this->wpdb->charset ) ) {
			$charset_collate = "DEFAULT CHARACTER SET " . $this->wpdb->charset;
		}

		if ( ! empty( $this->wpdb->collate ) ) {
			$charset_collate .= " COLLATE " .$this->wpdb->collate;
		}

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$this->charset_collate = $charset_collate;
	}
}