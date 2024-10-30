<?php

namespace BotMate;

abstract class Action {

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
     * Trigger constructor.
     *
     *
     * @param array $args
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function __construct( $args = array() ) {

        if( !empty( $args ) ) {

            foreach ( $args as $key => $value ) {

                $this->$key =  $value;

            }

        }

    }

    /**
     * Gets the fields for the setup
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function test_fields() {



    }


    /**
     * Does this action
     * 
     * @param Array $args Arguments 
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    abstract public function do_action( $args );


    /**
     * Action accepting fields
     *
     * Key (receiver variable), Value (associative array())
     * @example array( 
     *      'user_name'  => array( 
     *          'label'     =>  'User Name',
     *          'required'  =>  'required' 
     *      ) 
     * )
     * @return array 
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    abstract public function fields();

}
