<?php

namespace BotMate\Integrations\WordPress\Triggers;

use BotMate\Trigger;

/**
 * User_Register Class
 * 
 * @since 1.0
 * @version 1.0
 */
class User_Register extends Trigger {

    /**
     * User_Register constructor
     *
     * @param array $args
     * @version 1.0
     * @since 1.0
     */
    public function __construct( $args = array() )
    {
        $args = array(
            'id'            =>  'user_register',
            'title'         =>  'User Register',
            'description'   =>  'Fires immediately after a new user is registered.',
            'logo'          =>  BOTMATE_INTEGRATIONS_URL . 'wordpress/assets/logo.png'
        );

        parent::__construct( $args );
    }


    /**
     * Registers Trigger
     *
     * @since 1.0
     * @version 1.0
     */
    public function register_trigger() {

        /**
         * Fires before Trigger Happen
         *
         * @since 1.0
         * @version 1.0
         */
        do_action( "botmate_before_trigger_{$this->id}" );

        add_action( 'user_register', array( $this, 'call_action' ), 10, 2 );

    }


    /**
     * Calls The Action | Action call-back
     * 
     * @param string $user_id
     * @param string $userdata
     * @since 1.0
     * @version 1.0
     */
    public function call_action( $user_id, $userdata ) {

        $args = array();
        $args['user_id'] = $user_id;

        $args = array_merge( $args, $userdata );

        parent::do_action( $args );

    }


    /**
     * Trigger returning fields
     *
     * @return string[]
     * @since 1.0
     * @version 1.0
     */
    public function fields() {

        return array(
            '$user_id'              =>  'User ID. If supplied, the user will be updated.',
            '$user_pass'            =>  'The plain-text user password.',
            '$user_login'           =>  'The user\'s login username.',
            '$user_nicename'        =>  'The URL-friendly user name.',
            '$user_url'             =>  'The user URL.',
            '$user_email'           =>  'The user email address.',
            '$display_name'         =>  'The user\'s display name. Default is the user\'s username.',
            '$nickname'             =>  'The user\'s nickname. Default is the user\'s username.',
            '$first_name'           =>  'The user\'s first name. For new users, will be used to build the first part of the user\'s display name if `$display_name` is not specified.',
            '$last_name'            =>  'The user\'s last name. For new users, will be used to build the second part of the user\'s display name if `$display_name` is not specified.',
            '$description'          =>  'The user\'s biographical description.',
            '$rich_editing'         =>  'Whether to enable the rich-editor for the user. Accepts \'true\' or \'false\' as a string literal, not boolean. Default \'true\'.',
            '$syntax_highlighting'  =>  'Whether to enable the rich code editor for the user. Accepts \'true\' or \'false\' as a string literal, not boolean. Default \'true\'',
            '$comment_shortcuts'    =>  'Whether to enable comment moderation keyboard shortcuts for the user. Accepts \'true\' or \'false\' as a string literal, not boolean. Default \'false\'.',
            '$admin_color'          =>  'Admin color scheme for the user. Default \'fresh\'.',
            '$use_ssl'              =>  'Whether the user should always access the admin over https. Default false.',
            '$user_registered'      =>  'Date the user registered in UTC. Format is \'Y-m-d H:i:s\'.',
            '$user_activation_key'  =>  'Password reset key. Default empty.',
            '$spam'                 =>  'Multisite only. Whether the user is marked as spam. Default false.',
            '$show_admin_bar_front' =>  'Whether to display the Admin Bar for the user on the site\'s front end. Accepts \'true\' or \'false\' as a string literal, not boolean. Default \'true\'.',
            '$role'                 =>  'User\'s role.',
            '$locale'               =>  'User\'s locale. Default empty.',
            '$meta_input'           =>  'Array of custom user meta values keyed by meta key. Default empty.'
        );

    }

}