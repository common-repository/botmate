<?php

namespace BotMate;

use BotMate\Server\v1\Server;

class MenuConnection {

    private static $_instance;

    public static function get_instance() {

        if( self::$_instance == null ) {
            self::$_instance = new self();
        }

        return self::$_instance;

    }

    /**
     * MenuConnection constructor.
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function __construct() {

        add_action( 'botmate_add_menu', array( $this, 'add_submenu' ) );
        add_action( 'wp_ajax_bm-save-sites', array( $this, 'save_sites' ) );
        add_action( 'wp_ajax_bm-test-connection', array( $this, 'test_connection' ) );

        if ( isset( $_GET['page'] ) && $_GET['page'] == 'botmate-connections' ) {

            add_action( 'botmate_admin_register_scripts', array( $this, 'admin_enqueue_scripts' ) );

        }

    }


    /**
     * Adds sub-menu | Action call-back
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function add_submenu() {

        add_submenu_page(
            Init::SLUG,
            __( 'Connections', 'botmate' ),
            __( 'Connections', 'botmate' ),
            Init::CAPABILITY,
            Init::SLUG . '-connections',
            array( $this, 'page' ),
            3
        );

    }

    /**
     * Admin enqueue scripts | Action call-back
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function admin_enqueue_scripts() {

        wp_enqueue_style( 'botmate-admin' );
        wp_enqueue_script( 'botmate-connections' );

    }


    /**
     * Html page
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function page() {

        require 'views/html-connections.php';

    }

    /**
     * Adds Site | AJAX
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function save_sites() {

        $nonce = isset( $_POST['_nonce'] ) ? $_POST['_nonce'] : '';

        wp_verify_nonce( $nonce, 'bm-security' );
        
        $sites = isset( $_POST['sites'] ) ? sanitize_text_field( $_POST['sites'] ) : '';
        $sites = stripslashes( $sites );
        $sites = json_decode( $sites, true );

        /**
         * Filter sites
         *
         * @param array $sites
         *
         * @since 1.0.0
         */
        $sites = apply_filters( 'botmate_save_sites', $sites );

        botmate_save_sites( $sites );

    }

    /**
     * Tests Site Connection | AJAX
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function test_connection() {

        $nonce = isset( $_POST['_nonce'] ) ? $_POST['_nonce'] : '';

        wp_verify_nonce( $nonce, 'bm-security' );

        $site_url = isset( $_POST['site_url'] ) ? sanitize_url( $_POST['site_url'] ) : '';
        $api_key = isset( $_POST['api_key'] ) ? sanitize_text_field( $_POST['api_key'] ) : '';

        $server = new Server( $site_url, $api_key );
        $response = $server->request( 'GET', '/test-connection' );
        $code = wp_remote_retrieve_response_code( $response );
        $response = wp_remote_retrieve_body( $response );


        wp_send_json(
            json_decode( $response ),
            $code
        );

    }

}

MenuConnection::get_instance();
