<?php

namespace GetVisitor\Api;

use WP_REST_Server;
use WP_REST_Controller;

// derectly access denied
defined('ABSPATH') || exit;


/**
 * create class "Api_get_Visitor" to actualy register the routes.
 * @version 1.0
 * @author Rubel Mahmud <vxlrubel@gmail.com>
 * @link https://github.com/vxlrubel
 */

 class Api_Get_Visitor extends WP_REST_Controller{

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
    private function _cb_permission_check(){
        $permission = current_user_can( 'manage_options' );
        return $permission;
    }

    /**
     * insert the visitor
     *
     * @param [type] $request
     * @return void
     */
    public function insert_item( $request ){}

    /**
     * get visitors
     *
     * @param [type] $request
     * @return void
     */
    public function get_items( $request ){}

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
    public function delete_item( $request ){}
 }