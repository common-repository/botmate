<?php

namespace BotMate\Classes;

class Database {

    const LOGS_TABLE = 'botmate_logs';
    const SITE_OPTION = 'botmate_sites';

    private $site_option = '';

    /**
     * Database constructor.
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function __construct() {

        /**
         * Filters the option name
         *
         * @param string SITE_OPTION Site Option
         *
         * @since 1.0
         */
        $this->site_option = apply_filters( 'botmate_sites_option', self::SITE_OPTION );

    }

    /**
     * Creates log table
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function create_logs_table() {

        $table_exists = self::get_option( 'botmate_db_version' );

        //If table not exists create one
        if( !$table_exists ) {

            global $wpdb;
            $charset_collate = $wpdb->get_charset_collate();
            $table_name = $wpdb->prefix . self::LOGS_TABLE;
            $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
              id                    INT(11) NOT NULL AUTO_INCREMENT, 
              site                  VARCHAR(256) NOT NULL,
              `action`              VARCHAR(256),
              `trigger`             VARCHAR(256),
              response_code         VARCHAR(3),
              response_body         LONGTEXT,
              status                VARCHAR(9),
              `time`                BIGINT(20) DEFAULT NULL,              
              session_transcript    LONGTEXT,
              transaction_type      VARCHAR(9) NOT NULL,
              PRIMARY KEY  (id)
            ) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
            update_option( 'botmate_db_version', BOTMATE_DB_VERSION );

        }

        //Update existing table if current on is older thn BOTMATE_DB_VERSION
        if( $table_exists && version_compare( $table_exists, BOTMATE_DB_VERSION, '<' ) ) {

            //Do something new :D

        }

    }

    /**
     * Save sites
     *
     * @param array $sites Sites
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function save_sites( $sites ) {

        update_option( $this->site_option, $sites );

    }

    /**
     * Get Sites
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function get_sites() {

        return get_option( $this->site_option );

    }


    /**
     * Gets Option
     *
     * @param $option_key
     * @return false|mixed|void
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public static function get_option( $option_key ) {

        return get_option( $option_key );

    }

    /**
     * Checks does API key exist
     * 
     * @param string $api_key API key
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public static function api_key_exists( $api_key ) {

        global $wpdb;

        $result = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->postmeta} WHERE meta_value = %s",
                $api_key
            )
        );
        
        if( !empty( $result ) ) {
            return true;
        }

        return false;

    }

    /**
     * Gets Postmeta by Key
     *
     * @param $trigger_id
     * @param $key
     * @return mixed
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public static function get_meta( $trigger_id, $key ) {

        return get_post_meta( $trigger_id, $key, true );

    }

    /**
     * Update Postmeta by Key
     *
     * @param $post_id
     * @param $key
     * @param $value
     * @return mixed
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public static function update_meta( $post_id, $key, $value ) {

        return update_post_meta( $post_id, $key, $value );

    }

    /**
     * Get actions by API Key
     * 
     * @param $api_key
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public static function get_actions_by_api_key( $api_key ) {

        global $wpdb;
        $result = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_value = %s",
                $api_key
            )
        );

        $all_actions = botmate_get_actions_classes();
        $stored_actions = self::get_meta( $result->post_id,  'actions' );
        $organized_actions = array();

        foreach ( $all_actions as $action ) {

            $class = new $action;
            
            if( in_array( $class->id, $stored_actions ) ) {

                $organized_actions[$class->id] = $class;

            }

        }

        return $organized_actions;

    }


    /**
     * Insert Log Entry
     *
     * @param $site
     * @param $action
     * @param $trigger
     * @param $response_code
     * @param $response_body
     * @param $status
     * @param $time
     * @param $session_transcript
     * @param $transaction_type
     * @return void
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public static function insert_log_entry( $site, $action, $trigger, $response_code, $response_body, $status, $time, $session_transcript, $transaction_type ) {

        global $wpdb;
        $table_name = $wpdb->prefix . self::LOGS_TABLE;

        return $wpdb->insert(
            $table_name,
            array(
                'site'                  =>  $site,
                'action'                =>  $action,
                'trigger'               =>  $trigger,
                'response_code'         =>  $response_code,
                'response_body'         =>  $response_body,
                'status'                =>  $status,
                'time'                  =>  $time,
                'session_transcript'    =>  $session_transcript,
                'transaction_type'      =>  $transaction_type
            )
        );

    }

    /**
     * Get Logs
     *
     * @param array $args
     * @param string $condition
     * @return array|object|\stdClass[]|null
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public static function get_logs( $args = array(), $condition = '' ) {

        global $wpdb;
        $table_name = $wpdb->prefix . self::LOGS_TABLE;
        $query = "SELECT * FROM {$table_name}";

        if( !empty( $args ) ) {

            $query .= ' WHERE';
            $counter = 0;

            foreach( $args as $column => $value ) {

                $query .= $wpdb->prepare(
                    " `{$column}` = %s ",
                    $value
                );

                $counter++;

                if( !empty( $condition ) && count( $args ) > 1 && $counter !== count( $args ) ) {

                    $query .= $condition;

                }

            }

        }

        return $wpdb->get_results(
            $query
        );

    }

    /**
     * Delete Log By (id, etc...)
     *
     * @param $column
     * @param $value
     * @return void
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public static function delete_log_by( $column, $value ) {

        global $wpdb;
        $table_name = $wpdb->prefix . self::LOGS_TABLE;

        return $wpdb->delete(
            $table_name,
            array(
                $column =>  $value
            )
        );

    }

    /**
     * Runs on Plugin activation :)
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function activate_plugin() {

        $this->create_logs_table();

        /**
         * Fires After BotMate Activation
         *
         * @since 1.0
         * @version 1.0
         */
        do_action( 'botmate_activated' );

    }

    /**
     * Runs on Plugin deactivate :(
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function deactivate_plugin() {


    }


    /**
     * Runs on Plugin uninstall ;'(
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function uninstall_plugin() {


    }

}
