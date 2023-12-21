<?php
/*
Plugin Name: Get Visitor
Description: Get visitor is a subscription base plugin. It collect them email address of the visitor who is subscribe the website for ger getting new update and so one.
Version: 1.0
Author: Rubel Mahmud ( Sujan )
*/

// Add your plugin functionalities here
// For demonstration purposes, let's include the description in the plugin page

// use Wsf\Inc\Subscribe_REST_API_CONTROLLER;


class Get_Visitor{

    // create instance
    private static $instance;

    public function __construct(){
        
    }

    /**
     * get instance of the class
     *
     * @return $instance
     */
    public static function get_instance(){
        if( is_null( self::$instance ) ){
            self::$instance = new self();
        }

        return self::$instance;
    }

}

function get_visitor(){
    return Get_Visitor::get_instance();
}

get_visitor();