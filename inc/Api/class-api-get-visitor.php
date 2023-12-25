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
                    'permission_callback' => [ $this, '_cb_permission_check' ],
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
     * insert the visitor
     *
     * @param [type] $request
     * @return void
     */
    public function insert_item( $request ){
        global $wpdb;
        $table   = $this->table();
        $params = $request->get_params();
        
        $data = [
            'email' => sanitize_email( $params['email'] )
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
    public function update_item( $request ){}

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
 }