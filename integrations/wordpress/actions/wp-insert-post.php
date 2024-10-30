<?php

namespace BotMate\Integrations\WordPress\Actions;

use BotMate\Action;

/**
 * WP_Insert_Post Class
 *
 * @since 1.0
 * @version 1.0
 */
class WP_Insert_Post extends Action {

    /**
     * WP_Insert_Post constructor
     *
     * @param array $args
     * @version 1.0
     * @since 1.0
     */
    public function __construct( $args = array() )
    {
        $args = array(
            'id'            =>  'wp_insert_post',
            'title'         =>  'WordPress Insert Post',
            'description'   =>  'Insert or update post.',
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

        return wp_insert_post( $args );

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
            '$post_id'                  =>  array(
                'description'   =>  'The post ID. If equal to something other than 0, the post with that ID will be updated. Default 0.'
            ),
            '$post_author'              =>  array(
                'description'   =>  'The ID of the user who added the post. Default is the current user ID.',
            ),
            '$post_date'                =>  array(
                'description'   =>  'The date of the post. Default is the current time.',
            ),
            '$post_date_gmt'            =>  array(
                'description'   =>  'The date of the post in the GMT timezone. Default is the value of $post_date.',
            ),
            '$post_content'             =>  array(
                'description'   =>  'The post content. Default empty.',
            ),
            '$post_content_filtered'    =>  array(
                'description'   =>  'The filtered post content. Default empty.',
            ),
            '$post_title'               =>  array(
                'description'   =>  'The post title. Default empty.',
            ),
            '$post_excerpt'             =>  array(
                'description'   =>  'The post excerpt. Default empty.',
            ),
            '$post_status'              =>  array(
                'description'   =>  'The post status. Default \'draft\'.',
            ),
            '$post_type'                =>  array(
                'description'   =>  'The post type. Default \'post\'.',
            ),
            '$comment_status'           =>  array(
                'description'   =>  'Whether the post can accept comments. Accepts \'open\' or \'closed\'. Default is the value of \'default_comment_status\' option.',
            ),
            '$ping_status'              =>  array(
                'description'   =>  'Whether the post can accept pings. Accepts \'open\' or \'closed\'. Default is the value of \'default_ping_status\' option.',
            ),
            '$post_password'            =>  array(
                'description'   =>  'The password to access the post. Default empty.',
            ),
            '$post_type'                =>  array(
                'description'   =>  'The post type. Default \'post\'.',
            ),
            '$comment_status'           =>  array(
                'description'   =>  'Whether the post can accept comments. Accepts \'open\' or \'closed\'. Default is the value of \'default_comment_status\' option.',
            ),
            '$ping_status'              =>  array(
                'description'   =>  'Whether the post can accept pings. Accepts \'open\' or \'closed\'. Default is the value of \'default_ping_status\' option.',
            ),
            '$post_password'            =>  array(
                'description'   =>  'The password to access the post. Default empty.',
            ),
            '$post_name'                =>  array(
                'description'   =>  'The post name. Default is the sanitized post title when creating a new post.',
            ),
            '$to_ping'                  =>  array(
                'description'   =>  'Space or carriage return-separated list of URLs to ping. Default empty.',
            ),
            '$pinged'                   =>  array(
                'description'   =>  'Space or carriage return-separated list of URLs that have been pinged. Default empty.',
            ),
            '$post_modified'            =>  array(
                'description'   =>  'The date when the post was last modified. Default is the current time.',
            ),
            '$post_modified_gmt'        =>  array(
                'description'   =>  'The date when the post was last modified in the GMT timezone. Default is the current time.',
            ),
            '$post_parent'              =>  array(
                'description'   =>  'Set this for the post it belongs to, if any. Default 0.',
            ),
            '$menu_order'               =>  array(
                'description'   =>  'The order the post should be displayed in. Default 0.',
            ),
            '$post_mime_type'           =>  array(
                'description'   =>  'The mime type of the post. Default empty.',
            ),
            '$guid'                     =>  array(
                'description'   =>  'Global Unique ID for referencing the post. Default empty.',
            ),
            '$import_id'                =>  array(
                'description'   =>  'The post ID to be used when inserting a new post. If specified, must not match any existing post ID. Default 0.',
            ),
            '$post_category'            =>  array(
                'description'   =>  'Array of category IDs. Defaults to value of the \'default_category\' option.',
            ),
            '$tags_input'               =>  array(
                'description'   =>  'Array of tag names, slugs, or IDs. Default empty.',
            ),
            '$tax_input'                =>  array(
                'description'   =>  'An array of taxonomy terms keyed by their taxonomy name. If the taxonomy is hierarchical, the term list needs to be either an array of term IDs or a comma-separated string of IDs. If the taxonomy is non-hierarchical, the term list can be an array that contains term names or slugs, or a comma-separated string of names or slugs. This is because, in hierarchical taxonomy, child terms can have the same names with different parent terms, so the only way to connect them is using ID. Default empty.',
            ),
            '$meta_input'                =>  array(
                'description'   =>  'Array of post meta values keyed by their post meta key. Default empty.'
            ),
        );

    }

}
