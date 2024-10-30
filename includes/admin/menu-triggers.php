<?php

namespace BotMate;

use BotMate\Server\v1\Server;
use Couchbase\KeyExistsException;

class MenuTrigger {

    /**
     * MenuAction constructor.
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function __construct() {

        add_action( 'init', array( $this, 'register_trigger_post' ) );
        add_action( 'add_meta_boxes', array( $this, 'register_trigger_config_metabox' ) );
        add_action( 'botmate_admin_register_scripts', array( $this, 'admin_enqueue_scripts' ) );
        add_action( 'wp_ajax_bm-get-actions', array( $this, 'get_actions' ) );
        add_action( 'wp_ajax_bm-get-action-fields', array( $this, 'get_action_fields' ) );
        add_action( 'save_post_' . Init::TRIGGER_POST_TYPE, array( $this, 'save_trigger' ) );

    }


    /**
     * Admin enqueue scripts | Action call-back
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function admin_enqueue_scripts() {

        if( get_post_type() == Init::TRIGGER_POST_TYPE ) {

            wp_enqueue_style( 'botmate-select2' );
            wp_enqueue_style( 'botmate-admin' );
            wp_enqueue_script( 'botmate-select2' );
            wp_enqueue_script( 'botmate-admin' );

        }

    }

    /**
     * Registers Custom Post Type | Action call-back
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function register_trigger_post() {

        $labels = array(
            'name'                  => __( 'Triggers', 'botmate' ),
            'singular_name'         => __( 'Trigger', 'botmate' ),
            'add_new'               => __( 'Add New', 'botmate' ),
            'add_new_item'          => __( 'Add New Trigger', 'botmate' ),
            'new_item'              => __( 'New Trigger', 'botmate' ),
            'edit_item'             => __( 'Edit Triggers', 'botmate' ),
            'view_item'             => __( 'View Triggers', 'botmate' ),
            'all_items'             => __( 'All Triggers', 'botmate' ),
            'search_items'          => __( 'Search Triggers', 'botmate' ),
            'not_found'             => __( 'No trigger found.', 'recipe' ),
            'not_found_in_trash'    => __( 'No trigger found in Trash.', 'recipe' ),
        );

        $args = array(
            'labels'        => $labels,
            'show_in_menu'  => false,
            'public'        => true,
            'supports'      => array( 'title' )
        );

        register_post_type( Init::TRIGGER_POST_TYPE, $args );

    }


    /**
     * Adds Metabox | Action call-back
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function register_trigger_config_metabox() {

       add_meta_box(
           'trigger_config',
           __( 'Trigger Configuration', 'botmate' ),
           array( $this, 'trigger_configuration' ),
           Init::TRIGGER_POST_TYPE
       );

    }


    /**
     * Renders Trigger configuration HTML
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function trigger_configuration() {

        require 'views/html-triggers.php';

    }

    /**
     * Get Actions | AJAX
     *
     * @return void
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function get_actions() {

        $nonce = isset( $_POST['_nonce'] ) ? $_POST['_nonce'] : '';

        wp_verify_nonce( $nonce, 'bm-security' );

        $base_url = isset( $_POST['base_url'] ) ? sanitize_url( $_POST['base_url'] ) : '';
        $api_key = isset( $_POST['api_key'] ) ? sanitize_text_field( $_POST['api_key'] ) : '';

        $server = new Server( $base_url, $api_key );
        $response = $server->request( 'GET', '/get-actions' );
        $code = wp_remote_retrieve_response_code( $response );
        $response = wp_remote_retrieve_body( $response );


        wp_send_json(
            json_decode( $response ),
            $code
        );

    }

    /**
     * Renders the Action Fields
     *
     * @param $fields
     * @return void
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function render_action_fileds( $fields ) {

        var_dump( $fields );die;

    }


    /**
     * Get Action Fields | AJAX
     *
     * @return void
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function get_action_fields() {

        $nonce = isset( $_POST['_nonce'] ) ? $_POST['_nonce'] : '';

        wp_verify_nonce( $nonce, 'bm-security' );

        $base_url = isset( $_POST['base_url'] ) ? sanitize_url( $_POST['base_url'] ) : '';
        $api_key = isset( $_POST['api_key'] ) ? sanitize_text_field( $_POST['api_key'] ) : '';
        $action = isset( $_POST['bm_action'] ) ? sanitize_text_field( $_POST['bm_action'] ) : '';
        $selected_action = isset( $_POST['selected_action'] ) ? sanitize_text_field( $_POST['selected_action'] ) : '';
        $header = array(
            'Content-type'  =>  'application/json'
        );
        $body = array(
            'action'  =>  $action
        );

        $server = new Server( $base_url, $api_key );
        $server->header( $header );
        $server->body( $body );
        $response = $server->request( 'GET', '/get-action-fields' );
        $code = wp_remote_retrieve_response_code( $response );
        $response = wp_remote_retrieve_body( $response );
        $response = array(
            'action'    =>  json_decode( $response ),
            'trigger'   =>  botmate_call_trigger_method( $selected_action, 'fields' )
        );

        wp_send_json(
            $response,
            $code
        );

    }

    /**
     * Save the Triggers | Action Callback
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function save_trigger( $post_id ) {

        $data = array();
        $data['trigger'] = !empty( $_POST['bm_trigger'] ) ? sanitize_text_field( $_POST['bm_trigger'] ) : '';
        $data['site'] = !empty( $_POST['bm_triggers_site'] ) ? sanitize_text_field( $_POST['bm_triggers_site'] ) : '';
        $data['action'] = !empty( $_POST['bm_triggers_action'] ) ? sanitize_text_field( $_POST['bm_triggers_action'] ) : '';
        $fetched_trigger_options = !empty( $_POST['fetched_trigger_options'] ) ? sanitize_text_field( stripslashes( $_POST['fetched_trigger_options'] ) ) : '';
        $fetched_trigger_options = json_decode( $fetched_trigger_options );
        $fetched_action_fields = !empty( $_POST['fetched_action_fields'] ) ? sanitize_text_field( stripslashes( $_POST['fetched_action_fields'] ) ) : '';
        $fetched_action_fields = json_decode( $fetched_action_fields );

        //If already saved
        if( !empty( $fetched_trigger_options ) ) {

            //Remove Dollars
            foreach ( $fetched_trigger_options as $key => $value ) {

                $new_key = str_replace( '$', '', $key );
                $data['fetched_trigger_options'][$new_key] =  $value;

            }

        }

        //If already saved
        if( !empty( $fetched_action_fields ) ) {

            //Remove Dollars
            foreach ( $fetched_action_fields as $key => $value ) {

                $new_key = str_replace( '$', '', $key );
                $data['fetched_action_fields'][$new_key] =  $value;

            }

        }

        //Store Selected Action on Trigger Configuration
        if( isset( $_POST['bm_trigger_action'] ) ) {

            foreach( $_POST['bm_trigger_action'] as $key => $trigger ) {

                $data['selected_trigger_action'][$key] = !empty( $trigger ) ? sanitize_text_field( $trigger ) : '';

            }

        }

        /**
         * Filters Trigger Configuration
         *
         * @param $data array
         * @since 1.0
         * @version 1.0
         */
        $data = apply_filters( 'bm_save_trigger_configuration', $data );

        update_post_meta( $post_id, 'trigger_configuration', $data );

    }

}

new MenuTrigger();
