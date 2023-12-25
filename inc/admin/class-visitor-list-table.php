<?php

namespace GetVisitor\Admin;
use WP_List_Table;
use GetVisitor\Get_Visitor_Table as DB_Table;
    

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

    use DB_Table;

    public function __construct(){
        parent::__construct( 
            [
                'singular' => 'Visitor',    // Singular name of the item
                'plural'   => 'Visitors',   // Plural name of the items
                'ajax'     => false,        // If using AJAX, set to true
            ]
         );
    }

    /**
     * prepare items for display
     *
     * @return void
     */
    public function prepare_items(){
        $get_columns   = $this->get_columns();
        $data          = $this->get_data();
        $hidden_column = $this->get_hidden_columns();

        // for pagination
        $items_per_page = 10;
        $current_page   = $this->get_pagenum();
        $total_items    = (int) count( $data );
        $this->set_pagination_args([
            'total_items' => $total_items,
			'per_page'    => $items_per_page,
        ]);

        $slice_data = array_slice( $data, ( $current_page - 1 ) * $items_per_page, $items_per_page );

        $this->_column_headers = [ $get_columns, $hidden_column ];
        $this->items = $slice_data;
    }


    /**
     * HIDE COLUMNS
     *
     * @return void
     */
    public function get_hidden_columns(){
        return ['ID'];
    }
    
    /**
     * get all the data
     *
     * @return void
     */
    public function get_data(){
        global $wpdb;
        $table  = $this->table();
        $sql    = "SELECT * FROM $table ORDER BY ID DESC";
        $result = $wpdb->get_results( $sql, ARRAY_A );

        if ( count( $result ) > 0 ){
            return $result;
        }else{
            return 'no data found';
        }
    }

    /**
     * get the columns
     *
     * @return void
     */
    public function get_columns(){
        $columns = [
            'cb'         => '<input type="checkbox" />',
            'ID'         => 'ID',
            'email'      => 'Email Address',
            'created_at' => 'Create Date',
            'updated_at' => 'Update Date',
        ];

        return $columns;
    }

    /**
     * define checkbox for each item
     *
     * @param [type] $item
     * @return void
     */
    public function column_cb( $item ){
        return sprintf(
            '<input type="checkbox" name="get_visitor[]" value="%s" />',
            $item['ID']
        );
    }

    public function column_email( $item ){

        $link_edit = sprintf(
            '<a href="javascript:void(0)" class="edit-visitor-item" data-id="%s">%s</a>',
            (int)$item['ID'],
            esc_html( 'Edit' )
        );

        $link_delete = sprintf(
            '<a href="javascript:void(0)" class="delete-visitor-item" data-id="%s">%s</a>',
            (int)$item['ID'],
            esc_html( 'Delete' )
        );
        
        $action = [
            'edit'   => $link_edit,
            'delete' => $link_delete,
        ];

        return sprintf(
            '%1$s %2$s',
            $item['email'],
            $this->row_actions( $action )
        );
        
        
    }

    /**
     * set default columns
     *
     * @param [type] $item
     * @param [type] $column_name
     * @return void
     */
    public function column_default( $item, $column_name ){
        switch( $column_name ){
            case 'ID':
            case 'email':
            case 'created_at':
            case 'updated_at':

                return $item[ $column_name ];

            default:

                return 'No value';
        }
    }
 }