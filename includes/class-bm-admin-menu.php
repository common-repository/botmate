<?php

namespace BotMate;

/**
 * Admin menu pages call-backs
 *
 *
 * @package BotMate
 * @since 1.0.0
 * @version 1.0.0
 */
class AdminPage {


    /**
     * AdminPage constructor.
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function __construct() {

        $this->hooks();

    }


    /**
     * Hooks & Filters
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    private function hooks() {

        if ( isset( $_GET['page'] ) && $_GET['page'] == 'botmate' ) {

            add_action( 'botmate_admin_register_scripts', array( $this, 'admin_enqueue_scripts' ) );

        }

        add_action( 'admin_menu', array( $this, 'add_menu_page' ) );

    }

    /**
     * Admin enqueue scripts | Action call-back
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function admin_enqueue_scripts() {

        Scripts::admin_enqueue_all();

    }


    /**
     * Registers menu
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function add_menu_page() {

        $menu_logs = new MenuLogs;

        add_menu_page(
            __( 'BotMate', 'botmate' ),
            __( 'BotMate', 'botmate' ),
            Init::CAPABILITY,
            Init::SLUG,
            array( $this, 'botmate' ),
            BOTMATE_PLUGIN_URL . '/assets/images/menu-icon.png'
        );

        add_submenu_page(
            Init::SLUG,
            '',
            __( 'Dashboard', 'botmate' ),
            Init::CAPABILITY,
            Init::SLUG,
            '',
            1
        );

        add_submenu_page(
            Init::SLUG,
            __( 'Actions', 'botmate' ),
            __( 'Actions', 'botmate' ),
            Init::CAPABILITY,
            'edit.php?post_type=' . Init::ACTION_POST_TYPE,
            false,
            1
        );

        add_submenu_page(
            Init::SLUG,
            __( 'Triggers', 'botmate' ),
            __( 'Triggers', 'botmate' ),
            Init::CAPABILITY,
            'edit.php?post_type=' . Init::TRIGGER_POST_TYPE,
            false,
            2
        );

        add_submenu_page(
            Init::SLUG,
            __( 'Logs', 'botmate' ),
            __( 'Logs', 'botmate' ),
            Init::CAPABILITY,
            Init::SLUG . '-logs',
            array( $menu_logs, 'page' ),
            4
        );


        /**
         * Fires when menu is loaded
         *
         * @param object $this class.
         *
         * @since 1.0.0
         */
        do_action( 'botmate_add_menu', $this );

    }


    /**
     * call-back | Botmate
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function botmate() {

        require 'admin/views/html-dashboard.php';

    }

}

new AdminPage();
