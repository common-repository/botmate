<?php

namespace BotMate;

/**
 * Class Trigger
 * @package BotMate
 * @since 1.0
 * @version 1.0
 */
abstract class Trigger {

    /**
     * Unique ID
     *
     *
     * @var bool
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public $id = false;


    /**
     * Title of Trigger
     *
     *
     * @var bool
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public $title = false;


    /**
     * Logo url of the Integration
     * 
     * @var string
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public $logo = false;

    /**
     * Description of the Integration
     *
     * @var string
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public $description = false;


    /**
     * Unique ID of Action to be called.
     *
     * @var bool
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public $action_id = false;


    /**
     * Arguments of Triggers and Action, to be posted
     *
     * @var bool
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public $trigger_action_args = false;


    /**
     * Trigger constructor.
     *
     *
     * @param array $args
     * @since 1.0
     * @version 1.0
     */
    public function __construct( $args = array() ) {

        if( !empty( $args ) ) {

            foreach ( $args as $key => $value ) {

                $this->$key =  $value;

            }

        }

    }

    /**
     * Set Arguments and Action ID to be Posted
     *
     * @param $action_id
     * @param $trigger_action_args
     * @return void
     * @since 1.0
     * @version 1.0
     */
    public function set_args( $action_id, $trigger_action_args ) {

        $this->action_id = $action_id;
        $this->trigger_action_args = $trigger_action_args;

    }

    /**
     * Registers Trigger
     *
     * @since 1.0
     * @version 1.0
     */
    abstract public function register_trigger();


    /**
     * Do this Call-back
     *
     * @return void
     * @since 1.0
     * @version 1.0
     */
    public function do_action( $args ) {

        /**
         * Fires before Remote Action
         *
         * @param $action_id String Unique Action ID
         * @param $args Array Trigger -> Action Arguments
         * @param $args Array Action Built-in Arguments
         * @since 1.0
         * @version 1.0
         */
        do_action( 'botmate_do_action', $this->action_id, $this->trigger_action_args, $args );

    }

    /**
     * Trigger returning fields
     *
     * @return array Key (returning variable), Value (Definition of Variable aka PHPDoc)
     * @since 1.0
     * @version 1.0
     */
    abstract public function fields();

}
