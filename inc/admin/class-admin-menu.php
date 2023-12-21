<?php

namespace GetVisitor\Admin;

// derectly access denied
defined('ABSPATH') || exit;

/**
 * create a trait
 * @version 1.0
 * @author Rubel Mahmud <vxlrubel@gmail.com>
 * @link https://github.com/vxlrubel
 */

use GetVisitor\Get_Visitor_Table as Database_Table;

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
            5                                   // position
        );
    }

    /**
     * create callback function to display the visitor list
     *
     * @return void
     */
    public function _cb_create_menu_page(){
        
    }
    
}