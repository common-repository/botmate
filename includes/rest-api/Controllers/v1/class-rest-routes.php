<?php

namespace BotMate\Controllers\v1;

use BotMate\Classes\Logger;

/**
 * RestRoutes Class
 *
 *
 * @package BotMate\Controllers\v1
 * @since 1.0
 * @version 1.0
 */
class RestRoutes {

     /**
     * Namespace of routes
     * 
     * @var string
     * @since 1.0
     * @version 1.0
     */
    private $namespace = 'botmate/v1';

     /**
     * RestRoutes constructor.
     *
     * @since 1.0
     * @version 1.0
     */
    public function __construct() {

        add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );

    }

    /**
     * Register Rest Routes | Action Callback
     *
     * @since 1.0
     * @version 1.0
     */
    public function register_rest_routes() {


        /**
         * Fires before rest route register
         * 
         * @since 1.0
         */
        do_action( 'botmate_before_register_rest_route' );


        register_rest_route( $this->namespace, '/test-connection', array(
            'methods'   =>  \WP_REST_Server::READABLE,
            'callback'  =>  array( $this, 'test_connection' )
        ) );

        register_rest_route( $this->namespace, '/get-actions', array(
            'methods'   =>  \WP_REST_Server::READABLE,
            'callback'  =>  array( $this, 'get_actions' )
        ) );

        register_rest_route( $this->namespace, '/get-action-fields', array(
            'methods'   =>  \WP_REST_Server::READABLE,
            'callback'  =>  array( $this, 'get_action_fields' )
        ) );

        register_rest_route( $this->namespace, '/do-action', array(
            'methods'   =>  \WP_REST_Server::CREATABLE,
            'callback'  =>  array( $this, 'do_action' )
        ) );


         /**
         * Fires after rest route register
         * 
         * @since 1.0
         */
        do_action( 'botmate_after_register_rest_route' );

    }

    /**
     * Returns the status of connection | Rest Route Callback
     * 
     * @since 1.0
     * @version 1.0
     */
    public function test_connection( \WP_REST_Request $request ) {

        $headers = $request->get_headers();

        if( !isset( $headers['x_api_key'][0] ) || empty( $headers['x_api_key'][0] ) ) {

            wp_send_json_error( 
                array( 
                    'code'      =>  'api_key_required',
                    'message'   =>  'API key required.',
                    'status'    =>  401    
                ),
                401
            );

        }

        $api_key = sanitize_text_field( $headers['x_api_key'][0] );

        $api_key_exists = botmate_api_key_exists( $api_key );

        if( $api_key_exists ) {

            wp_send_json_success( 
                array(
                    'code'      =>  'connected',
                    'message'   =>  'Successfully connected.',
                    'status'    =>  200    
                ),
                200
            );

        }

        wp_send_json_error( 
            array( 
                'code'      =>  'api_key_not_exists',
                'message'   =>  'API key does not exist.',
                'status'    =>  404    
            ),
            404
        );

    }

    /**
     * Returns Actions | Rest Route Callback
     * 
     * @since 1.0
     * @version 1.0
     */
    public function get_actions( \WP_REST_Request $request ) {

        $headers = $request->get_headers();

        if( !isset( $headers['x_api_key'][0] ) || empty( $headers['x_api_key'][0] ) ) {

            wp_send_json_error( 
                array( 
                    'code'      =>  'api_key_required',
                    'message'   =>  'API key required.',
                    'status'    =>  401    
                ),
                401
            );

        }

        $api_key = sanitize_text_field( $headers['x_api_key'][0] );

        $api_key_exists = botmate_api_key_exists( $api_key );

        if( $api_key_exists ) {

            $actions = botmate_get_actions_by_api_key( $api_key );

            if( !$actions ) {

                wp_send_json_error( 
                    array( 
                        'code'      =>  'no_action_exist',
                        'message'   =>  'No action exist.',
                        'status'    =>  404    
                    ),
                    404
                );

            }

            wp_send_json_success( 
                $actions,
                200
            );

        }

        wp_send_json_error( 
            array( 
                'code'      =>  'api_key_not_exists',
                'message'   =>  'API key does not exist.',
                'status'    =>  404    
            ),
            404
        );

    }

    /**
     * Returns Action Fields | Rest Route Callback
     * 
     * @since 1.0
     * @version 1.0
     */
    public function get_action_fields( \WP_REST_Request $request ) {

        $headers = $request->get_headers();
        $body = $request->get_params();
        
        if( !isset( $headers['x_api_key'][0] ) || empty( $headers['x_api_key'][0] ) ) {

            wp_send_json_error( 
                array( 
                    'code'      =>  'api_key_required',
                    'message'   =>  'API key required.',
                    'status'    =>  401    
                ),
                401
            );

        }

        $api_key = sanitize_text_field( $headers['x_api_key'][0] );

        $api_key_exists = botmate_api_key_exists( $api_key );

        if( $api_key_exists ) {

            $actions = botmate_get_actions_by_api_key( $api_key );
            $action = $body['action'];
            $with_this_api_key = $action ? array_key_exists( $action, $actions ) : false;

            if( isset( $action ) && $with_this_api_key ) {

                $fields = botmate_call_action_method( $action, 'fields' );
                
                wp_send_json_success( 
                    $fields,
                    200
                );

            } 
            if( !isset( $action ) ) {

                wp_send_json_error( 
                    array( 
                        'code'      =>  'action_parameter_required',
                        'message'   =>  'Action Parameter is required.',
                        'status'    =>  400    
                    ),
                    400
                );

            }
            

            if( !$actions || !$with_this_api_key ) {

                wp_send_json_error( 
                    array( 
                        'code'      =>  'no_action_exist',
                        'message'   =>  'No action exist.',
                        'status'    =>  404    
                    ),
                    404
                );

            }

            wp_send_json_success( 
                $actions,
                200
            );

        }

        wp_send_json_error( 
            array( 
                'code'      =>  'api_key_not_exists',
                'message'   =>  'API key does not exist.',
                'status'    =>  404    
            ),
            404
        );

    }

    /**
     * Does The action | Rest Route Callback
     * 
     * @since 1.0
     * @version 1.0
     */
    public function do_action( \WP_REST_Request $request ) {

        $headers = $request->get_headers();
        $body = $request->get_params();
        $logger = new Logger;
        
        if( !isset( $headers['x_api_key'][0] ) || empty( $headers['x_api_key'][0] ) ) {

            wp_send_json_error( 
                array( 
                    'code'      =>  'api_key_required',
                    'message'   =>  'API key required.',
                    'status'    =>  401    
                ),
                401
            );

        }

        $api_key = sanitize_text_field( $headers['x_api_key'][0] );

        $api_key_exists = botmate_api_key_exists( $api_key );

        if( $api_key_exists ) {

            $actions = botmate_get_actions_by_api_key( $api_key );
            $action = $body['action'];
            $trigger = $body['trigger'];
            $to_do = $body['to_do'];
            $session_transcript = serialize( $to_do );
            $with_this_api_key = $action ? array_key_exists( $action, $actions ) : false;

            if( isset( $action ) && $with_this_api_key ) {

                $actions = botmate_get_actions_classes();
                $action = new $actions[$action]();
                $response = $action->do_action( $to_do );


                /**
                 * Fites After a Successful Action by Trigger
                 * 
                 * @since 1.0
                 * @version 1.0
                 */
                do_action( 'botmate_sucessful_action_by_trigger' );

                $logger->success_log( $headers['host'][0], $body['action'], $trigger, 200, $response, 'success', time(), $session_transcript, 'incoming' );
                
                wp_send_json_success( 
                    $response,
                    200
                );

            } 
            if( !isset( $action ) ) {

                wp_send_json_error( 
                    array( 
                        'code'      =>  'action_parameter_required',
                        'message'   =>  'Action Parameter is required.',
                        'status'    =>  400    
                    ),
                    400
                );

            }
            if( !isset( $trigger ) ) {

                wp_send_json_error( 
                    array( 
                        'code'      =>  'trigger_parameter_required',
                        'message'   =>  'Trigger Parameter is required.',
                        'status'    =>  400    
                    ),
                    400
                );

            }
            if( !isset( $to_do ) ) {

                wp_send_json_error( 
                    array( 
                        'code'      =>  'to_do_parameter_required',
                        'message'   =>  'To do Parameter is required.',
                        'status'    =>  400    
                    ),
                    400
                );

            }
            

            if( !$actions || !$with_this_api_key ) {

                wp_send_json_error( 
                    array( 
                        'code'      =>  'no_action_exist',
                        'message'   =>  'No action exist.',
                        'status'    =>  404    
                    ),
                    404
                );

            }

            wp_send_json_success( 
                $actions,
                200
            );

        }

        wp_send_json_error( 
            array( 
                'code'      =>  'api_key_not_exists',
                'message'   =>  'API key does not exist.',
                'status'    =>  404    
            ),
            404
        );

    }

}

new RestRoutes;