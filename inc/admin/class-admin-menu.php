<?php

namespace GetVisitor\Admin;

// derectly access denied
defined('ABSPATH') || exit;

use GetVisitor\Get_Visitor_Table as Database_Table;


/**
 * create a Admin_Menu class to create admin menu
 * @version 1.0
 * @author Rubel Mahmud <vxlrubel@gmail.com>
 * @link https://github.com/vxlrubel
 */
class Admin_Menu{
    
    use Database_Table;

    public function __construct(){
        // create admin menu
        add_action( 'admin_menu', [ $this, 'create_admin_menu' ] );
    }

    /**
     * create admin menu
     *
     * @return void
     */
    public function create_admin_menu(){

        add_menu_page(
            __( 'Get Visitor', 'GV_DOMAIN' ),  // page title
            __( 'Get Visitor', 'GV_DOMAIN' ),  // menu title
            'manage_options',                  // capability
            $this->slug_admin_menu,            // menu slug
            [ $this, '_cb_create_menu_page' ], // callback
            'dashicons-buddicons-groups',      // icon
            20                                 // position
        );

        // create submenu
        add_submenu_page(
            $this->slug_admin_menu,             // parent slug
            __( 'Visitor List', 'GV_DOMAIN' ),  // page title
            __( 'Visitor List', 'GV_DOMAIN' ),  // menu title
            'manage_options',                   // capability
            $this->slug_admin_menu,             // menu slug
            [ $this, '_cb_create_menu_page' ],  // callback
        );

        // create submenu for create new visitor
        add_submenu_page(
            $this->slug_admin_menu,                // parent slug
            __( 'Add New Visitor', 'GV_DOMAIN' ),  // page title
            __( 'Add New', 'GV_DOMAIN' ),          // menu title
            'manage_options',                      // capability
            $this->slug_add_new,                   // menu slug
            [ $this, '_cb_add_visitor_page' ],     // callback
        );

        // create submenu settings
        add_submenu_page(
            $this->slug_admin_menu,                // parent slug
            __( 'Visitor Options', 'GV_DOMAIN' ),  // page title
            __( 'Options', 'GV_DOMAIN' ),          // menu title
            'manage_options',                      // capability
            $this->slug_admin_settings,            // menu slug
            [ $this, '_cb_visitor_options_page' ], // callback
        );
    }

    /**
     * create callback function to display the visitor list
     *
     * @return void
     */
    public function _cb_create_menu_page(){
        global $get_visitor;
        $get_visitor->display_visitor_list();
    }

    /**
     * create callback function to create a new visitor
     *
     * @return void
     */
    public function _cb_add_visitor_page(){
        global $get_visitor;
        $get_visitor->add_new_visitor();
    }

    /**
     * create callback function to display the settings options
     *
     * @return void
     */
    public function _cb_visitor_options_page(){
        global $get_visitor;
        $get_visitor->settings_option();
    }
    
}