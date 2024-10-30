<?php

namespace BotMate;

class Scripts {

    /**
     * Scripts constructor.
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function __construct() {

        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

    }

    /**
     * Admin register scripts | Action call-back
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function admin_enqueue_scripts() {

        //Select2
        wp_register_script( 'botmate-select2', BOTMATE_PLUGIN_URL . 'assets/js/botmate-select2.min.js', array( 'jquery' ), BOTMATE_VERSION, true );

        //BotMate JS
        wp_register_script( 'botmate-admin', BOTMATE_PLUGIN_URL . 'assets/js/botmate-admin.js', array( 'jquery' ), BOTMATE_VERSION, true );

        //Connection JS
        wp_register_script( 'botmate-connections', BOTMATE_PLUGIN_URL . 'assets/js/botmate-connections.js', array( 'jquery' ), BOTMATE_VERSION, true );

        //Select2 CSS
        wp_register_style( 'botmate-select2', BOTMATE_PLUGIN_URL . 'assets/css/botmate-select2.min.css', false, BOTMATE_VERSION );
        
        //BotMate CSS
        wp_register_style( 'botmate-admin', BOTMATE_PLUGIN_URL . 'assets/css/botmate-admin.css', false, BOTMATE_VERSION );

        /**
         * Fires after registering scripts
         *
         * @since 1.0.0
         */
        do_action( 'botmate_admin_register_scripts' );

    }

    /**
     * Enqueue all Scripts
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public static function admin_enqueue_all() {

        wp_enqueue_script( 'botmate-select2' );
        wp_enqueue_script( 'botmate-admin' );
        wp_enqueue_script( 'botmate-connection' );
        wp_enqueue_style( 'botmate-select2' );
        wp_enqueue_style( 'botmate-admin' );

    }

}

new Scripts();
