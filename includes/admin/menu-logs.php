<?php

namespace BotMate;

use BotMate\Classes\Logger;

if ( ! class_exists( 'WP_List_Table' ) ) {

    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

}

class MenuLogs extends \WP_List_Table {


    /**
     * MenuLogs constructor.
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function __construct() {

        if ( isset( $_GET['page'] ) && $_GET['page'] == 'botmate-logs' ) {

            add_action( 'botmate_admin_register_scripts', array( $this, 'admin_enqueue_scripts' ) );

        }

        parent::__construct(array(
            'singular'  => 'log',     // Singular name of the listed records.
            'plural'    => 'logs',    // Plural name of the listed records.
            'ajax'      => false,       // Does this table support ajax?
        ));

    }


    /**
     * Admin enqueue scripts | Action call-back
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function admin_enqueue_scripts() {

        Scripts::admin_enqueue_all();

    }


    /**
     * Html page
     *
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function page() {

        require 'views/html-logs.php';

    }


    /**
     * Get Columns
     *
     * @return array
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    public function get_columns() {

        return array(
            'cb'                    =>  '<input type="checkbox" />',
            'site'                  =>  __( 'Site', 'botmate' ),
            'action'                =>  __( 'Action', 'botmate' ),
            'trigger'               =>  __( 'trigger', 'botmate' ),
            'response_code'         =>  __( 'Response Code', 'botmate' ),
            'response_body'         =>  __( 'Response Body', 'botmate' ),
            'status'                =>  __( 'Status', 'botmate' ),
            'time'                  =>  __( 'Time', 'botmate' ),
            'session_transcript'    =>  __( 'Session Transcript', 'botmate' ),
            'transaction_type'      =>  __( 'Transaction Type', 'botmate' ),
        );

    }


    /**
     * Gets Sortable Columns
     *
     * @return array[]
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    protected function get_sortable_columns() {

        return array(
            'site'                  =>  array( 'site', true ),
            'action'                =>  array( 'action', true ),
            'trigger'               =>  array( 'trigger', true ),
            'response_code'         =>  array( 'response_code', true ),
            'status'                =>  array( 'status', true ),
            'time'                  =>  array( 'time', true ),
            'transaction_type'      =>  array( 'transaction_type', true ),
        );

    }


    /**
     * Set Columns
     *
     * @param $item
     * @param $column_name
     * @return bool|string|void
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    protected function column_default( $item, $column_name ) {

        switch ( $column_name ) {
            case 'site':
                return $item->site;
            case 'action':
                return $item->action;
            case 'trigger':
                return $item->trigger;
            case 'response_code':
                return $item->response_code;
            case 'response_body':
                return $item->response_body;
            case 'status':
                return $item->status;
            case 'time':
                return date( 'd-m-Y h:i:s A', $item->time );
            case 'session_transcript':
                return "<a href='javascript:void(0)' class='button button-primary bm-session-transcript' data-data='{$item->session_transcript}'>View</a><p class='bm-show-session-transcript'></p>";
            case 'transaction_type':
                $transaction_icon = $item->transaction_type == 'incoming' ? '<span class="dashicons dashicons-arrow-down-alt bm-request-icon"></span>' : '<span class="dashicons dashicons-arrow-up-alt bm-request-icon"></span>';
                return $item->transaction_type . $transaction_icon;
            default:
                return print_r( $item, true );
        }

    }


    /**
     * Sets Checkbox
     *
     * @param $item
     * @return string|void
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    protected function column_cb( $item ) {

        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            $this->_args['singular'],
            $item->id
        );

    }


    /**
     * Sets Delete Button
     *
     * @param $item
     * @return string
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    protected function column_title( $item ) {

        $page = isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : '';
        $page = wp_unslash( $page ); // WPCS: Input var ok.

        // Build delete row action.
        $delete_query_args = array(
            'page'      =>  $page,
            'action'    =>  'delete',
            'log'       =>  $item->id,
        );

        $actions['delete'] = sprintf(
            '<a href="%1$s">%2$s</a>',
            esc_url( wp_nonce_url( add_query_arg( $delete_query_args, 'admin.php' ), 'deletelog_' . $item->id ) ),
            _x( 'Delete', 'List table row action', 'botmate' )
        );

        // Return the title contents.
        return sprintf( '%1$s <span style="color:silver;">(id:%2$s)</span>%3$s',
            $item->site,
            $item->id,
            $this->row_actions( $actions )
        );

    }


    /**
     * Bulk Delete
     *
     * @return array
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    protected function get_bulk_actions() {

        $actions = array(
            'delete' => _x( 'Delete', 'List table bulk action', 'botmate' ),
        );

        return $actions;

    }


    /**
     * Processes Bulk Delete
     *
     * @return void
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    protected function process_bulk_action() {

        // security check!
        if ( isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ) {

            $nonce  = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
            $action = 'bulk-' . $this->_args['plural'];

            if ( ! wp_verify_nonce( $nonce, $action ) )
                wp_die( 'Nope! Security check failed!' );

        }

        // Detect when a bulk action is being triggered.
        if ( 'delete' === $this->current_action() ) {

            //Lets Start Deleting Logs ;'(
            if( isset( $_GET['log'] ) ) {

                $logs = botmate_sanitize_array( $_GET['log'] );

                foreach( $logs as $key => $value ) {

                    $logger = new Logger();
                    $logger->delete_log_by( 'id', $value );

                }

            }

        }

    }

    function prepare_items() {

        $per_page = 50;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        $this->process_bulk_action();

        $logs = new Logger();
        $data = $logs->get_all();

        usort( $data, array( $this, 'usort_reorder' ) );

        $current_page = $this->get_pagenum();
        $total_items = count( $data );
        $data = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );
        $this->items = $data;

        $this->set_pagination_args( array(
            'total_items'   =>  $total_items,
            'per_page'      =>  $per_page,
            'total_pages'   =>  ceil( $total_items / $per_page )
        ) );

    }


    /**
     * Sorts Order
     *
     * @param $a
     * @param $b
     * @return int
     * @author Syed Muhammad Usman (@smusman98)
	 * @since 1.0.0
	 * @version 1.0.0
     */
    protected function usort_reorder( $a, $b ) {

        // If no sort, default to title.
        $orderby = !empty( $_REQUEST['orderby'] ) ? wp_unslash( sanitize_text_field( $_REQUEST['orderby'] ) ) : 'site'; // WPCS: Input var ok.

        // If no order, default to asc.
        $order = !empty( $_REQUEST['order'] ) ? wp_unslash( sanitize_text_field( $_REQUEST['order'] ) ) : 'asc'; // WPCS: Input var ok.

        // Determine sort order.
        $result = strcmp( $a->$orderby, $b->$orderby );

        return ( 'asc' === $order ) ? $result : -$result;

    }

}