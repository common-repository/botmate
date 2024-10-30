<?php

namespace BotMate\Middleware\v1;

use BotMate\Classes\Logger;
use BotMate\Server\v1\Server;

/**
 * Automate the process now
 *
 * @package Middleware
 * @since 1.0
 */
class Middleware {

    /**
     * Middleware Constructor
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function __construct() {

        add_action( 'botmate_init', array( $this, 'automate' ) );
        add_action( 'botmate_do_action', array( $this, 'remote_action' ), 10, 3 );

    }


    /**
     * Automate the Flow
     *
     * @return void
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function automate() {

        global $wpdb;

        $rows = $wpdb->get_results(
            "SELECT * FROM {$wpdb->postmeta} WHERE meta_key = 'trigger_configuration'"
        );

        if( !empty( $rows ) ) {

            foreach ( $rows as $key => $row ) {

                $row = ( !empty( $row ) ) ? unserialize( $row->meta_value ) : '';

                //Check if all set to perform Action
                if(
                    !empty( $row )
                    &&
                    isset( $row['trigger'] )
                    &&
                    isset( $row['site'] )
                    &&
                    isset( $row['action'] )
                    &&
                    isset( $row['selected_trigger_action'] )
                ) {

                    $trigger = $row['trigger'];
                    $action = $row['action'];

                    $triggers = botmate_get_triggers_classes();
                    $trigger = new $triggers[$trigger]();

                    $trigger->set_args( $action, $row );
                    $trigger->register_trigger();

                }

            }

        }

    }


    /**
     * Calls Remote Action
     *
     * @param $action_id
     * @param $trigger_action_args
     * @param $args
     * @return void
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function remote_action( $action, $trigger_action_args, $args ) {

        $api_key = $trigger_action_args['site'];
        $base_url = botmate_get_base_url_by_api_key( $api_key );
        $site_title = botmate_get_site_title_by_api_key( $api_key );
        $fake_args = $trigger_action_args['selected_trigger_action'];
        $trigger = $trigger_action_args['trigger'];

        //Replace fake args by real one
        foreach ( $fake_args as $fake_key => $fake_value ) {

            $key_exists = array_key_exists( $fake_value, $args );

            if( $key_exists ) {

                $fake_args[$fake_key] = $args[$fake_value];

            }
            //Set blank, that doesn't have data
            else {

                $fake_args[$fake_key] = '';

            }

        }

        /**
         * Filters the Arguments before Remote Request
         *
         * @param $fake_args Array Action Arguments
         * @param $action String Unique Action ID
         * @param $trigger_action_args String Trigger -> Action Arguments
         * @param $args Array Action Built-in Arguments
         * @since 1.0
         * @version 1.0
         */
        $fake_args = apply_filters( 'botmate_before_request_action', $fake_args, $action, $trigger_action_args, $args );

        $body = array(
            'action'    =>  $action,
            'trigger'   =>  $trigger,
            'to_do'     =>  $fake_args
        );

        $server = new Server( $base_url, $api_key );
        $server->body( $body );
        $response = $server->request( 'POST', '/do-action' );
        $code = wp_remote_retrieve_response_code( $response );
        $response = wp_remote_retrieve_body( $response );
        $response = serialize( $response );
        unset( $trigger_action_args['fetched_trigger_options'] );
        unset( $trigger_action_args['fetched_action_fields'] );
        $session_transcript = serialize( $trigger_action_args );
        $logger = new Logger();

        if( $code == 200 ) {

            /**
             * Fires After Successful Action Response From Remote
             *
             * @since 1.0
             * @version 1.0
             */
            do_action( 'botmate_successful_remote_action' );

            $logger->success_log( $site_title, $action, $trigger, $code, $response, 'success', time(), $session_transcript, 'outgoing' );

        }
        else {

            /**
             * Fires After Failed Action Response From Remote
             *
             * @since 1.0
             * @version 1.0
             */
            do_action( 'botmate_failed_remote_action' );

            $logger->failed_log( $site_title, $action, $trigger, $code, $response, 'failed', time(), $session_transcript, 'outgoing' );

        }

    }

}

new Middleware;