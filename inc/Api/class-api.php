<?php

namespace GetVisitor\Api;

// derectly access denied
defined('ABSPATH') || exit;


/**
 * create Api class for register api
 * @version 1.0
 * @author Rubel Mahmud <vxlrubel@gmail.com>
 * @link https://github.com/vxlrubel
 */
class Api{

    public function __construct(){
        add_action( 'rest_api_init', [ $this, 'register_api' ] );
    }

    public function register_api(){
        $api = new Api_Get_Visitor;
        $api->register_routes();
    }
}