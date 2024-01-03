<?php

namespace GetVisitor\Admin;

// derectly access denied
defined('ABSPATH') || exit;

use GetVisitor\Get_Visitor_Table as Database_Table;

class Ajax_Request{
    use Database_Table;

    // export action
    private $action_export = 'export_get_visitors';
    
    public function __construct(){
        add_action( "wp_ajax_{$this->action_export}", [ $this, 'export_data' ] );
        add_action( "wp_ajax_nopriv_{$this->action_export}", [ $this, 'export_data' ] );
    }

    /**
     * export visitor list
     *
     * @return void
     */
    public function export_data(){
        global $wpdb;
        $table = $this->table();

        if( ! defined('DOING_AJAX') && ! DOING_AJAX ){
            return;
        }

        if ( ! isset( $_POST['action']) && $_POST['action'] !== 'export_get_visitors' ){
            return;
        }

        $csv_data = "Email Address, Created Date, Updated Date \n";
        $sql      = "SELECT * FROM $table";
        $rows = $wpdb->get_results( $sql );

        foreach ( $rows as $row ) {
            $csv_data .= "{$row->email}, {$row->created_at}, {$row->updated_at}\n";
        }
        $filename = 'get-visitors_'. rand( 9999, 999999 ) . '.csv';
        header( 'Content-Type: text/csv' );
        header( 'Content-Disposition: attachment; filename="' . $filename . '"' );

        echo $csv_data;
        
        wp_die();
    }

}