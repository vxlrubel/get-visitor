<?php

namespace GetVisitor\Api;

use WP_REST_Server;
use WP_REST_Controller;
use GetVisitor\Get_Visitor_Table as Database_Table;
// derectly access denied
defined('ABSPATH') || exit;


/**
 * create class "Api_get_Visitor" to actualy register the routes.
 * @version 1.0
 * @author Rubel Mahmud <vxlrubel@gmail.com>
 * @link https://github.com/vxlrubel
 */

 class Api_Get_Visitor extends WP_REST_Controller{

    use Database_Table;

    // defined settings route
    private $settings = 'settings';

    // general settings rest_base
    private $settings_general = 'general';

    // define option setting rest_base
    private $settings_option = 'options';

    // setting reset base
    private $settings_reset = 'reset';

    // multiple delete item base
    private $multiple_delete = 'dropvisitors';
    
    public function __construct(){

        // define namespace
        $this->namespace = 'getvisitor/v1';

        // define rest base
        $this->rest_base = 'visitor';

    }

    /**
     * Register rest routes
     *
     * @return void
     */
    public function register_routes(){
        
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            [
                [
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => [ $this, 'insert_item' ],
                    'permission_callback' => [ $this, '_cb_insert_permission_check' ],
                ],
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'get_items' ],
                    'permission_callback' => [ $this, '_cb_permission_check' ],
                ],
            ]
        );
        
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/(?P<id>[\d]+)',
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'get_single_item' ],
                    'permission_callback' => [ $this, '_cb_permission_check' ],
                ],
                [
                    'methods'             => WP_REST_Server::EDITABLE,
                    'callback'            => [ $this, 'update_item' ],
                    'permission_callback' => [ $this, '_cb_permission_check' ],
                ],
                [
                    'methods'             => WP_REST_Server::DELETABLE,
                    'callback'            => [ $this, 'delete_item' ],
                    'permission_callback' => [ $this, '_cb_permission_check' ],
                ],
            ]
        );

        // register settings route
        register_rest_route(
            $this->namespace,
            '/' . $this->settings . '/' . $this->settings_general,
            [
                [
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => [ $this, 'settings_general' ],
                    'permission_callback' => [ $this, '_cb_permission_check' ],
                ]
            ]
        );

        // register settings option route
        register_rest_route(
            $this->namespace,
            '/' . $this->settings . '/' . $this->settings_option,
            [
                [
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => [ $this, 'settings_option' ],
                    'permission_callback' => [ $this, '_cb_permission_check' ],
                ]
            ]
        );

        // register general setting reset
        register_rest_route(
            $this->namespace,
            '/' . $this->settings . '/' . $this->settings_general . '/' . $this->settings_reset,
            [
                [
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => [ $this, 'settings_general_reset' ],
                    'permission_callback' => [ $this, '_cb_permission_check' ],
                ]
            ]
        );

        // register route for multiple delete
        register_rest_route(
            $this->namespace,
            '/' . $this->multiple_delete,
            [
                [
                    'methods'             => WP_REST_Server::DELETABLE,
                    'callback'            => [ $this, 'multiple_delete' ],
                    'permission_callback' => [ $this, '_cb_permission_check' ],
                ]
            ]
        );
    }

    /**
     * check permission
     *
     * @return void
     */
    public function _cb_permission_check(){
        $permission = current_user_can( 'manage_options' );
        return $permission;
    }

    /**
     * check insert permission
     *
     * @return void
     */
    public function _cb_insert_permission_check(){
        return true;
    }

    /**
     * insert the visitor
     *
     * @param [type] $request
     * @return void
     */
    public function insert_item( $request ){
        global $wpdb;
        $table  = $this->table();
        $params = $request->get_params();
        $email  = sanitize_email( $params['email'] );

        if ( empty( $email ) ){
            return '<span style="color:#eb3b5a">Faield, try again after fill the input field.</span>';
        }
        
        $data   = [
            'email' => $email
        ];

        $insert_result = $wpdb->insert( $table, $data );

        if( $insert_result === false ){
            return new WP_Error( 'failed_insert', 'Failed to insert data', [ 'status' => 500 ] );
        }

        return 'Subscription successfull.';
    }

    /**
     * get single item for view details
     *
     * @param [type] $request
     * @return [string] $email
     */
    public function get_single_item( $request ){
        global $wpdb;
        $table  = $this->table();
        $params = $request->get_params();
        $id     = (int)$params['id'];
        $sql    = "SELECT email FROM $table WHERE ID = $id";
        $result = $wpdb->get_results( $sql, ARRAY_A );

        if ( count( $result ) === 1 ){
            $email = $result;
        }else{
            $email = 'Email address not found';
        }

        return $email;
    }

    /**
     * get visitors
     *
     * @param [type] $request
     * @return void
     */
    public function get_items( $request ){
        global $wpdb;
        $table   = $this->table();
        $sql     = "SELECT * FROM $table ORDER BY ID DESC";
        $result  = $wpdb->get_results( $sql );
        $request = $result;
        
        if ( count( $request ) < 1 ){
            return 'No result found';
        }

        return $request;
    }

    /**
     * update indivual item
     *
     * @param [type] $request
     * @return void
     */
    public function update_item( $request ){
        global $wpdb;
        $table  = $this->table();
        $params = $request->get_params();
        $id     = (int)$params['id'];
        $email  = sanitize_email( $params['email'] );
        $data   = [
            'email' => $email
        ];
        $where_clause = [
            'id' => $id
        ];
        $data_format = ['%s'];
        $where_clause_format = ['%d'];


        $update_result = $wpdb->update( $table, $data, $where_clause, $data_format, $where_clause_format );

        if ( $update_result === false ){
            return new WP_Error('failed_update', 'Failed to update data', array('status' => 500));
        }

        return 'update successfully';
    }

    /**
     * delete indivual item
     *
     * @param [type] $request
     * @return void
     */
    public function delete_item( $request ){
        global $wpdb;
        $table  = $this->table();
        $id     = (int)$request['id'];

        // print_r( $id );
        // return;

        $where_clause        = [ 'ID' => $id ];
        $where_clause_format = ['%d'];
        
        $delete_result = $wpdb->delete( $table, $where_clause, $where_clause_format );

        if ( $delete_result === false ){
            return new WP_Error('failed_delete', 'Failed to delete data', [ 'status' => 500 ] );
        }

        return 'delete data successfully';
    }

    /**
     * this method for multiple delete
     *
     * @param [type] $request
     * @return void
     */
    public function multiple_delete( $request ){
        global $wpdb;
        $table  = $this->table();
        $params = $request->get_params();
        $ids    = $params['ids'];
        
        foreach ( $ids as $id ) {
            $where_clause        = [ 'ID' => (int)$id ];
            $where_clause_format = ['%d'];
            $delete_result       = $wpdb->delete( $table, $where_clause, $where_clause_format );

            if ( $delete_result === false ){
                return new WP_Error('failed_delete', 'Failed to delete data', [ 'status' => 500 ] );
            }
            
            $response = 'delete successfull.';
        }

        return rest_ensure_response( $response );

    }

    /**
     * general settings
     *
     * @param [type] $request
     * @return void
     */
    public function settings_general( $request ){

        $params = $request->get_params();
        
        $title          = sanitize_text_field( $params['title'] );
        $desc           = sanitize_textarea_field( $params['desc'] );
        $placeholder    = sanitize_text_field( $params['placeholder'] );
        $notice_success = sanitize_text_field( $params['notice_success'] );
        $notice_warning = sanitize_text_field( $params['notice_warning'] );
        
        $request = [
            'title'          => update_option( '_gv_form_title', $title ),
            'desc'           => update_option( '_gv_form_desc', $desc ),
            'placeholder'    => update_option( '_gv_placeholder', $placeholder ),
            'notice_success' => update_option( '_gv_notice_success', $notice_success ),
            'notice_warning' => update_option( '_gv_notice_warning', $notice_warning ),
        ];

        $result = [
            'title'          => get_option( '_gv_form_title' ),
            'desc'           => get_option( '_gv_form_desc' ),
            'placeholder'    => get_option( '_gv_placeholder' ),
            'notice_success' => get_option( '_gv_notice_success' ),
            'notice_warning' => get_option( '_gv_notice_warning' ),
        ];
        
        $response = rest_ensure_response( $result );
        
        return $response;
    }

    /**
     * reset to default options
     *
     * @param [type] $request
     * @return void
     */
    public function settings_general_reset( $request ){
        return $this->settings_general( $request );
    }

    /**
     * set settings option for list item count
     *
     * @param [type] $request
     * @return void
     */
    public function settings_option( $request ){
        $params     = $request->get_params();
        $list_count = sanitize_text_field( $params['list_count'] );
        $request    = [
            'list_count' => update_option( '_gv_list_items', (int)$list_count )
        ];
        $result     = [
            'list_count' => get_option( '_gv_list_items' )
        ];

        $response = rest_ensure_response( $result );
        return $response;
    }

 }