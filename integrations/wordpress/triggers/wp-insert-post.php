<?php

namespace BotMate\Integrations\WordPress\Triggers;

use BotMate\Trigger;

/**
 * WP_Insert_Post Class
 *
 * @since 1.0
 * @version 1.0
 */
class WP_Insert_Post extends Trigger {

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
            'id'            =>  'wp_insert_post',
            'title'         =>  'WP Insert Post',
            'description'   =>  'Fires once a post has been saved.',
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

        add_action( 'wp_insert_post', array( $this, 'call_action' ), 10, 2 );

    }


    /**
     * Calls The Action | Action call-back
     *
     * @param $post_ID
     * @param $post
     * @param $update
     * @since 1.0
     * @version 1.0
     */
    public function call_action( $post_ID, $post ) {

        $args = array();
        $args['post_id'] = $post_ID;
        $post = (array)$post;

        $args = array_merge( $args, $post );

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
            '$post_id'                  =>  'The post ID. If equal to something other than 0, the post with that ID will be updated. Default 0.',
            '$post_author'              =>  'The ID of the user who added the post. Default is the current user ID.',
            '$post_date'                =>  'The date of the post. Default is the current time.',
            '$post_date_gmt'            =>  'The date of the post in the GMT timezone. Default is the value of $post_date.',
            '$post_content'             =>  'The post content. Default empty.',
            '$post_content_filtered'    =>  'The filtered post content. Default empty.',
            '$post_title'               =>  'The post title. Default empty.',
            '$post_excerpt'             =>  'The post excerpt. Default empty.',
            '$post_status'              =>  'The post status. Default \'draft\'.',
            '$post_type'                =>  'The post type. Default \'post\'.',
            '$comment_status'           =>  'Whether the post can accept comments. Accepts \'open\' or \'closed\'. Default is the value of \'default_comment_status\' option.',
            '$ping_status'              =>  'Whether the post can accept pings. Accepts \'open\' or \'closed\'. Default is the value of \'default_ping_status\' option.',
            '$post_password'            =>  'The password to access the post. Default empty.',
            '$post_type'                =>  'The post type. Default \'post\'.',
            '$comment_status'           =>  'Whether the post can accept comments. Accepts \'open\' or \'closed\'. Default is the value of \'default_comment_status\' option.',
            '$ping_status'              =>  'Whether the post can accept pings. Accepts \'open\' or \'closed\'. Default is the value of \'default_ping_status\' option.',
            '$post_password'            =>  'The password to access the post. Default empty.',
            '$post_name'                =>  'The post name. Default is the sanitized post title when creating a new post.',
            '$to_ping'                  =>  'Space or carriage return-separated list of URLs to ping. Default empty.',
            '$pinged'                   =>  'Space or carriage return-separated list of URLs that have been pinged. Default empty.',
            '$post_modified'            =>  'The date when the post was last modified. Default is the current time.',
            '$post_modified_gmt'        =>  'The date when the post was last modified in the GMT timezone. Default is the current time.',
            '$post_parent'              =>  'Set this for the post it belongs to, if any. Default 0.',
            '$menu_order'               =>  'The order the post should be displayed in. Default 0.',
            '$post_mime_type'           =>  'The mime type of the post. Default empty.',
            '$guid'                     =>  'Global Unique ID for referencing the post. Default empty.',
            '$import_id'                =>  'The post ID to be used when inserting a new post. If specified, must not match any existing post ID. Default 0.',
            '$post_category'            =>  'Array of category IDs. Defaults to value of the \'default_category\' option.',
            '$tags_input'               =>  'Array of tag names, slugs, or IDs. Default empty.',
            '$tax_input'                =>  'An array of taxonomy terms keyed by their taxonomy name. If the taxonomy is hierarchical, the term list needs to be either an array of term IDs or a comma-separated string of IDs. If the taxonomy is non-hierarchical, the term list can be an array that contains term names or slugs, or a comma-separated string of names or slugs. This is because, in hierarchical taxonomy, child terms can have the same names with different parent terms, so the only way to connect them is using ID. Default empty.',
            '$meta_input'                =>  'Array of post meta values keyed by their post meta key. Default empty.'
        );

    }

}