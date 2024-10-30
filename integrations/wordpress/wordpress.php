<?php

namespace BotMate\Integrations;

/**
 * WordPress Class
 * 
 * @since 1.0
 * @version 1.0
 */
class WordPress {

    /**
     * WordPress constructor.
     *
     * @since 1.0
     * @version 1.0
     */
    public function __construct() {

        require_once 'triggers/user-register.php';
        require_once 'triggers/wp-insert-post.php';
        require_once 'actions/wp-insert-user.php';
        require_once 'actions/wp-insert-post.php';

    }

}

new WordPress;
