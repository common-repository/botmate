<?php
/**
 * Plugin Name: BotMate
 * Plugin URI: https://botmate.me
 * Description: Allows you to connect your sites together, and Automate/ Sync workflows with no code.
 * Version: 1.0.0
 * Author: BotMate
 * Author URI: https://botmate.me/
 * Text Domain: botmate
 * Requires at least: 5.8
 * Requires PHP: 7.2
 *
 * @package BotMate
 */

use BotMate\Init;

/**
 * Initialize Freemius
 * 
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! function_exists( 'bot_fs' ) ) {
    // Create a helper function for easy SDK access.
    function bot_fs() {
        global $bot_fs;

        if ( ! isset( $bot_fs ) ) {
            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/includes/freemius/start.php';

            $bot_fs = fs_dynamic_init( array(
                'id'                  => '11339',
                'slug'                => 'botmate',
                'type'                => 'plugin',
                'public_key'          => 'pk_a4f7ee3bec9ac075b51796f9bb4bb',
                'is_premium'          => false,
                'has_addons'          => false,
                'has_paid_plans'      => false,
                'menu'                => array(
                    'slug'           => 'botmate',
                    'account'        => false,
                ),
            ) );
        }

        return $bot_fs;
    }

    // Init Freemius.
    bot_fs();
    // Signal that SDK was initiated.
    do_action( 'bot_fs_loaded' );
}

defined( 'ABSPATH' ) || exit;
define( 'BOTMATE_VERSION', '1.0.0' );
define( 'BOTMATE_DB_VERSION', '1.0.0' );
define( 'BOTMATE_PLUGIN_FILE', __FILE__ );
define( 'BOTMATE_PLUGIN_URL', plugins_url( '/', BOTMATE_PLUGIN_FILE ) );
define( 'BOTMATE_DIR_PATH', dirname( __FILE__ ) . '/' );

require_once dirname( BOTMATE_PLUGIN_FILE ) . '/includes/class-botmate.php';

/**
 * Returns the main instance of BotMate
 *
 * @author Syed Muhammad Usman (@smusman98)
 * @since 1.0.0
 * @version 1.0.0
 * @return mixed
 */
function BotMate_loader() {

    /**
     * Fires before loading BotMate
     *
     * @since 1.0.0
     */
    do_action( 'botmate_before_init' );

    Init::get_instance();

    /**
     * Fires when BotMate is loaded
     *
     * @since 1.0.0
     */
    do_action( 'botmate_init' );

}

BotMate_loader();
