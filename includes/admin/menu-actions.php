<?php

namespace BotMate;

class MenuAction {

    /**
     * MenuAction constructor.
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function __construct() {

        add_action( 'init', array( $this, 'register_action_post' ) );
        add_action( 'add_meta_boxes', array( $this, 'register_actions_metabox' ) );
        add_action( 'botmate_admin_register_scripts', array( $this, 'admin_enqueue_scripts' ) );
        add_action( 'wp_ajax_bm-generate-api-key', array( $this, 'generate_api_key' ) );
        add_action( 'save_post_' . Init::ACTION_POST_TYPE, array( $this, 'save_action' ) );

    }


    /**
     * Admin enqueue scripts | Action call-back
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function admin_enqueue_scripts() {

        if( get_post_type() == Init::ACTION_POST_TYPE ) {

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
    public function register_action_post() {

        $labels = array(
            'name'                  => __( 'Actions', 'botmate' ),
            'singular_name'         => __( 'Action', 'botmate' ),
            'add_new'               => __( 'Add New', 'botmate' ),
            'add_new_item'          => __( 'Add New Action', 'botmate' ),
            'new_item'              => __( 'New Action', 'botmate' ),
            'edit_item'             => __( 'Edit Actions', 'botmate' ),
            'view_item'             => __( 'View Actions', 'botmate' ),
            'all_items'             => __( 'All Actions', 'botmate' ),
            'search_items'          => __( 'Search Actions', 'botmate' ),
            'not_found'             => __( 'No action found.', 'recipe' ),
            'not_found_in_trash'    => __( 'No action found in Trash.', 'recipe' ),
        );

        $args = array(
            'labels'        => $labels,
            'show_in_menu'  => false,
            'public'        => true,
            'supports'      => array( 'title' )
        );

        register_post_type( Init::ACTION_POST_TYPE, $args );

    }


    /**
     * Adds Metabox | Action call-back
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function register_actions_metabox() {

        add_meta_box(
            'action_config',
            __( 'Action Configuration', 'botmate' ),
            array( $this, 'action_configuration' ),
            Init::ACTION_POST_TYPE
        );

    }

    /**
     * Renders Action configuration HTML
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function action_configuration() {

        require 'views/html-actions.php';

    }

    /**
     * Generates API Key | AJAX 
     * 
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function generate_api_key() {

        if( !current_user_can( Init::CAPABILITY ) ) {
            wp_send_json_error( 
                array(
                    'code'      =>  'not_authorized',
                    'message'   =>  'User can not generate.',
                    'status'    =>  403
                ),
                403
            );
        }
        
        $nonce = isset( $_POST['_nonce'] ) ? $_POST['_nonce'] : '';

        wp_verify_nonce( $nonce, 'bm-security' );
        
        $api_key = wp_generate_password( 20, true, false );

        /**
         * Fires before returning API Key
         * 
         * @param string $api_key API Key 
         * 
         * @since 1.0
         */
        $api_key = apply_filters( 'botmate_action_config_api_key', $api_key );

        wp_send_json_success(
            $api_key,
            200
        );

    }
    
    /**
     * Save the Actions | Action Callback
     * 
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function save_action( $post_id ) {

        $api_key = !empty( $_POST['bm_api_key'] ) ? sanitize_text_field( sanitize_text_field( $_POST['bm_api_key'] ) ) : '';
        $actions = !empty( $_POST['bm_actions'] ) ? botmate_sanitize_array( $_POST['bm_actions'] ) : array();

        update_post_meta( $post_id, 'api_key', $api_key );
        update_post_meta( $post_id, 'actions', $actions );

    }

}

new MenuAction();
