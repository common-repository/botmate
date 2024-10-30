<?php

namespace BotMate\Integrations\WordPress\Actions;

use BotMate\Action;

/**
 * User_Register Class
 *
 * @since 1.0
 * @version 1.0
 */
class WP_Insert_User extends Action {

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
            'id'            =>  'wp_insert_user',
            'title'         =>  'WordPress Insert User',
            'description'   =>  'Inserts a user into the database.',
            'logo'          =>  BOTMATE_INTEGRATIONS_URL . 'wordpress/assets/logo.png'
        );

        parent::__construct( $args );
    }


    /**
     * Gets the fields for the setup
     *
     * @since 1.0
     * @version 1.0
     */
    public function test_fields() {

        return array(

        );

    }


     /**
     * Does this action
     * 
     * @param Array $args Arguments 
     * @since 1.0
     * @version 1.0
     */
    public function do_action( $args ) {

        return wp_insert_user( $args );

    }


    /**
     * Action accepting fields
     *
     * @return array 
     * @since 1.0
     * @version 1.0
     */
    public function fields() {

        return array(
            '$user_id'              =>  array(
                'description' =>  'User ID. If supplied, the user will be updated.'
            ),
            '$user_pass'            =>  array(
                'description' =>  'The plain-text user password.'
            ),
            '$user_login'           =>  array(
                'description' =>  'The user\'s login username.'
            ),
            '$user_nicename'        =>  array(
                'description' =>  'The URL-friendly user name.'
            ),
            '$user_url'             =>  array(
                'description' =>  'The user URL.'
            ),
            '$user_email'           =>  array(
                'description' =>  'The user email address.'
            ),
            '$display_name'         =>  array(
                'description' =>  'The user\'s display name. Default is the user\'s username.'
            ),
            '$nickname'             =>  array(
                'description' =>  'The user\'s nickname. Default is the user\'s username.'
            ),
            '$first_name'           =>  array(
                'description' =>  'The user\'s first name. For new users, will be used to build the first part of the user\'s display name if `$display_name` is not specified.' 
            ),
            '$last_name'            =>  array(
                'description' =>  'The user\'s last name. For new users, will be used to build the second part of the user\'s display name if `$display_name` is not specified.'
            ),
            '$description'          =>  array(
                'description' =>  'The user\'s biographical description.'
            ),
            '$rich_editing'         =>  array(
                'description' =>  'Whether to enable the rich-editor for the user. Accepts \'true\' or \'false\' as a string literal, not boolean. Default \'true\'.'
            ),
            '$syntax_highlighting'  =>  array(
                'description' =>  'Whether to enable the rich code editor for the user. Accepts \'true\' or \'false\' as a string literal, not boolean. Default \'true\''
            ),
            '$comment_shortcuts'    =>  array(
                'description' =>  'Whether to enable comment moderation keyboard shortcuts for the user. Accepts \'true\' or \'false\' as a string literal, not boolean. Default \'false\'.'
            ),
            '$admin_color'          =>  array(
                'description' =>  'Admin color scheme for the user. Default \'fresh\'.'
            ),
            '$use_ssl'              =>  array(
                'description' =>  'Whether the user should always access the admin over https. Default false.'
            ),
            '$user_registered'      =>  array(
                'description' =>  'Date the user registered in UTC. Format is \'Y-m-d H:i:s\'.'
            ),
            '$user_activation_key'  =>  array(
                'description' =>  'Password reset key. Default empty.'
            ),
            '$spam'                 =>  array(
                'description' =>  'Multisite only. Whether the user is marked as spam. Default false.'
            ),
            '$show_admin_bar_front' =>  array(
                'description' =>  'Whether to display the Admin Bar for the user on the site\'s front end. Accepts \'true\' or \'false\' as a string literal, not boolean. Default \'true\'.'
            ),
            '$role'                 =>  array(
                'description' =>  'User\'s role.'
            ),
            '$locale'               =>  array(
                'description' =>  'User\'s locale. Default empty.'
            ),
            '$meta_input'           =>  array(
                'description' =>  'Array of custom user meta values keyed by meta key. Default empty.'
            )
        );

    }

}
