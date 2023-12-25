<?php

namespace GetVisitor\Admin;
use WP_List_Table;

// derectly access denied
defined('ABSPATH') || exit;

/**
 * create a visitor list table to show the visitor inside the table
 * @version 1.0
 * @author Rubel Mahmud <vxlrubel@gmail.com>
 * @link https://github.com/vxlrubel
 */

if ( ! class_exists('WP_List_Table') ){
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

 class Visitor_List_Table extends WP_List_Table{
    public function __construct(){
        $args = [
            'singular' => 'Visitor',    // Singular name of the item
            'plural'   => 'Visitors',   // Plural name of the items
            'ajax'     => false,        // If using AJAX, set to true
        ];
        parent::__construct( $args );
    }

    public function prepare_items(){
        echo 'hello world';
    }
 }