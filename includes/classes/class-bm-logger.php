<?php

namespace BotMate\Classes;

class Logger {

    /**
     * Insert Successful Log Entry
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
    public function success_log( $site, $action, $trigger, $response_code, $response_body, $status, $time, $session_transcript, $transaction_type ) {

        return  $this->log( $site, $action, $trigger, $response_code, $response_body, $status, $time, $session_transcript, $transaction_type );

    }


    /**
     * Insert Failed Log Entry
     *
     * @param $site
     * @param $action
     * @param $trigger
     * @param $response_code
     * @param $response_body
     * @param $status
     * @param $time
     * @param $session_transcript
     * @return void
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function failed_log( $site, $action, $trigger, $response_code, $response_body, $status, $time, $session_transcript, $transaction_type ) {

       return  $this->log( $site, $action, $trigger, $response_code, $response_body, $status, $time, $session_transcript, $transaction_type );

    }


    /**
     * Log Entry
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
    public function log( $site, $action, $trigger, $response_code, $response_body, $status, $time, $session_transcript, $transaction_type ) {

        return Database::insert_log_entry( $site, $action, $trigger, $response_code, $response_body, $status, $time, $session_transcript, $transaction_type );

    }

    /**
     * Get All Logs
     *
     * @return void
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function get_all() {

        return Database::get_logs();

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
    public function delete_log_by( $column, $value ) {

        Database::delete_log_by( $column, $value );

    }

}