<?php

namespace BotMate\Server\v1;

/**
 * Server Class
 *
 *
 * @package BotMate\Server\v1
 * @since 1.0
 * @version 1.0
 */
class Server {


    /**
     * Namespace of routes
     * 
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    private $namespace = 'botmate/v1';

    /**
     * Base URL
     * 
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    private $base_url = '';

    /**
     * API Key
     * 
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    private $api_key = '';

    /**
     * Request Header
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    private $header = array();

    /**
     * Request Body
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    private $body = array();

    /**
     * Server constructor.
     *
     * @param string $base_url Base URL
     * @param string $api_key API Key
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function __construct( $base_url, $api_key ) {

        $this->base_url = $base_url . '/wp-json/' . $this->namespace;
        $this->api_key = $api_key;

    }

    /**
     * Request Header
     *
     * @param array $additional_header Additional Header
     * @return array
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function header( $additional_header = array() ) {

        $this->header = $additional_header;

        return $this->header;

    }

    /**
     * Request Body
     *
     * @param array $body Body args
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function body( $body = array() ) {

        $this->body = $body;

        return $this->body;

    }


    /**
     * Request
     *
     * @param string $method Request Method
     * @param string $endpoint End Point
     * @return
     * @version 1.0
     * @since 1.0
     */
    public function request( $method, $endpoint ) {

        $url = $this->base_url . $endpoint;

        $header = array(
            'X-Api-Key' =>  $this->api_key
        );

        $additional_header = array_merge( $header, $this->header );

        /**
         * Filters the header of Remote Request
         *
         * @param array $header Header
         *
         * @since 1.0
         */
        $header = apply_filters( 'botmate_server_request_header', $additional_header );

        $body = $this->body;

        /**
         * Filters the body of Remote Request
         *
         * @param array $header Header
         *
         * @since 1.0
         */
        $body = apply_filters( 'botmate_server_request_body', $body );

        $args = array(
            'method'    =>  $method,
            'headers'   =>  $header,
            'body'      =>  $body
        );

        /**
         * Filters args of Remote Request
         * 
         * @param array $args Arguments
         * 
         * @since 1.0
         */
        $args = apply_filters( 'botmate_server_request_args', $args );

        $response = wp_remote_post(
            $url,
            $args
        );

        return $response;

    }

}
