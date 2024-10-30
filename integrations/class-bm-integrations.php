<?php

namespace BotMate\Integrations;

class Integrations {

    /**
     * Integrations constructor.
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function __construct() {

        $this->includes();
        $this->define();
        $this->hooks();

    }


    /**
     * Include files
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function includes() {

        //WordPress Core integration
        require_once 'wordpress/wordpress.php';

    }

    /**
     * Define constants
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function define() {

        define( 'BOTMATE_INTEGRATIONS_URL', BOTMATE_PLUGIN_URL . 'integrations/' );

    }

    /**
     * Action, Filters
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function hooks() {

        add_filter( 'botmate_register_trigger', array( $this, 'botmate_register_triggers' ) );
        add_filter( 'botmate_register_action', array( $this, 'botmate_register_actions' ) );

    }

    /**
     * Register builtin triggers | Filter call-back
     *
     * @param $triggers
     * @return string[]
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function botmate_register_triggers( $triggers ) {

        $new_triggers = array(
            'user_register' =>  'BotMate\Integrations\WordPress\Triggers\User_Register',
            'wp_insert_post' =>  'BotMate\Integrations\WordPress\Triggers\WP_Insert_Post'
        );

        $triggers = array_merge( $new_triggers, $triggers );

        return $triggers;

    }

    /**
     * Register builtin actions | Filter call-back
     *
     * @param $actions
     * @return string[]
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function botmate_register_actions( $actions ) {

        $new_actions = array(
            'wp_insert_user'  =>  'BotMate\Integrations\WordPress\Actions\WP_Insert_User',
            'wp_insert_post'  =>  'BotMate\Integrations\WordPress\Actions\WP_Insert_Post'
        );

        $actions = array_merge( $new_actions, $actions );

        return $actions;

    }

}

new Integrations();
