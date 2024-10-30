<?php

/**
 * Get list of registered triggers
 *
 * @return mixed|void
 * @since 1.0.0
 * @version 1.0.0
 */
if( !function_exists( 'botmate_get_triggers_classes' ) ):
    function botmate_get_triggers_classes() {

    /**
     * Filters the triggers | Register triggers
     *
     * @param array array() Trigger(s)
     *
     * @since 1.0.0
     */
    $classes = apply_filters( 'botmate_register_trigger', array() );

    return $classes;

}
endif;

/**
 * Get list of registered actions
 *
 * @return mixed|void
 * @since 1.0.0
 * @version 1.0.0
 */
if( !function_exists( 'botmate_get_actions_classes' ) ):
    function botmate_get_actions_classes() {

        /**
         * Filters the actions | Register actions
         *
         * @param array array() Actions(s)
         *
         * @since 1.0.0
         */
        $classes = apply_filters( 'botmate_register_action', array() );

        return $classes;

    }
endif;

/**
 * Adds Site
 * 
 * @param array $site Site
 * @since 1.0.0
 * @version 1.0.0
 */
if( !function_exists( 'botmate_save_sites' ) ):
function botmate_save_sites( $sites ) {
    
    $database = new \BotMate\Classes\Database();

    $database->save_sites( $sites );
    
}
endif;

/**
 * Get Sites
 *
 * @param array $site Site
 * @since 1.0.0
 * @version 1.0.0
 */
if( !function_exists( 'botmate_get_sites' ) ):
    function botmate_get_sites() {

        $database = new \BotMate\Classes\Database();

        return $database->get_sites();

    }
endif;

/**
 * Sanitizes Array | Associative  Array | Multi-dimensional Array
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if( !function_exists( 'botmate_sanitize_array' ) ):
function botmate_sanitize_array( $_array ) {

    foreach( $_array as $key => $value )  {

        $key = sanitize_text_field( $key );

        if( is_array( $value ) ) {
            $value = mycred_sanitize_array( $value );
        }
        else {
            $_array[$key] = sanitize_text_field( $value );
        }

    }

    return $_array;

}
endif;

/**
 * Checks does API key exist
 * 
 * @param string $api_key API key
 * @since 1.0.0
 * @version 1.0.0
 */
if( !function_exists( 'botmate_api_key_exists' ) ):
function botmate_api_key_exists( $api_key ) {

    return  \BotMate\Classes\Database::api_key_exists( $api_key );

}
endif;

/**
 * Gets saved Actions
 *
 * @param $action_id
 * @return mixed
 * @since 1.0.0
 * @version 1.0.0
 */
if( !function_exists( 'botmate_get_saved_actions' ) ):
    function botmate_get_saved_actions( $action_id ) {

        return  \BotMate\Classes\Database::get_meta( $action_id, 'actions' );

    }
endif;

/**
 * Gets saved Triggers
 *
 * @param $trigger_id
 * @return mixed
 * @since 1.0.0
 * @version 1.0.0
 */
if( !function_exists( 'botmate_get_saved_triggers' ) ):
    function botmate_get_saved_triggers( $trigger_id ) {

        return  \BotMate\Classes\Database::get_meta( $trigger_id, 'triggers' );

    }
endif;

/**
 * Gets API Key by Trigger ID
 *
 * @param $trigger_id
 * @return mixed
 * @since 1.0.0
 * @version 1.0.0
 */
if( !function_exists( 'botmate_get_api_key' ) ):
function botmate_get_api_key( $trigger_id ) {

    return  \BotMate\Classes\Database::get_meta( $trigger_id, 'api_key' );

}
endif;

/**
 * Get actions by API Key
 * 
 * @param $api_key
 * @since 1.0.0
 * @version 1.0.0
 */
if( !function_exists( 'botmate_get_actions_by_api_key' ) ):
function botmate_get_actions_by_api_key( $api_key ) {

    return  \BotMate\Classes\Database::get_actions_by_api_key( $api_key );

}
endif;

/**
 * Call the mehtod of action
 * 
 * @param $action_unique_id
 * @param $method
 * @since 1.0.0
 * @version 1.0.0
 */
if( !function_exists( 'botmate_call_action_method' ) ):
function botmate_call_action_method( $action_unique_id, $method ) {

    $actions = botmate_get_actions_classes();
    $action = new $actions[$action_unique_id]();

    return $action->$method();

}
endif;


/**
 * Call the mehtod of trigger
 *
 * @param $trigger_unique_id
 * @param $method
 * @since 1.0.0
 * @version 1.0.0
 */
if( !function_exists( 'botmate_call_trigger_method' ) ):
function botmate_call_trigger_method( $trigger_unique_id, $method ) {

    $triggers = botmate_get_triggers_classes();
    $trigger = new $triggers[$trigger_unique_id]();

    return $trigger->$method();

}
endif;

/**
 * Gets Trigger Configuration
 *
 * @param $trigger_id
 * @return mixed
 * @since 1.0
 * @version 1.0
 */
if( !function_exists( 'botmate_get_trigger_config' ) ):
function botmate_get_trigger_config( $trigger_id ) {

    return  \BotMate\Classes\Database::get_meta( $trigger_id, 'trigger_configuration' );

}
endif;


/**
 * Gets Base URL by API Key
 *
 * @since 1.0
 * @version 1.0
 */
if( !function_exists( 'botmate_get_base_url_by_api_key' ) ):
function botmate_get_base_url_by_api_key( $api_key ) {

    $database = new \BotMate\Classes\Database();

    $option =  $database->get_sites( $api_key );

    if( $option ) {

        foreach ( $option as $key => $item ) {

            if( isset( $item['api_key'] ) && $item['api_key'] == $api_key ) {

                return $item['url'];

            }

        }

    }

    return false;

}
endif;

/**
 * Gets SIte Title by API Key
 *
 * @since 1.0
 * @version 1.0
 */
if( !function_exists( 'botmate_get_site_title_by_api_key' ) ):
    function botmate_get_site_title_by_api_key( $api_key ) {

        $database = new \BotMate\Classes\Database();

        $option =  $database->get_sites( $api_key );

        if( $option ) {

            foreach ( $option as $key => $item ) {

                if( isset( $item['api_key'] ) && $item['api_key'] == $api_key ) {

                    return $item['title'];

                }

            }

        }

        return false;

    }
endif;