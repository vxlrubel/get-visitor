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

// include autoload file
if ( file_exists( dirname(__FILE__) . '/inc/autoload.php' ) ){
    require_once dirname(__FILE__) . '/inc/autoload.php';
}

class Get_Visitor{

    use GetVisitor\Get_Visitor_Table;
    
    // create instance
    private static $instance;

    // plugin version
    private $version = '1.0';

    public function __construct(){

        // define constant
        $this->define_constant();

        // create database table
        register_activation_hook( __FILE__, [ $this, 'create_db_table' ] );
        
    }

    /**
     * create database table
     *
     * @return void
     */
    public function create_db_table(){
        global $wpdb;
        $table = $this->table();
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table(
            ID mediumint(9) NOT NULL AUTO_INCREMENT,
            email VARCHAR(55) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (ID)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        dbDelta( $sql );
    }

    /**
     * define constant
     *
     * @return void
     */
    public function define_constant(){
        define( 'GV_DOMAIN', 'get-visitor' );
        define( 'GV_VERSION', $this->version );
        define( 'GV_ASSETS', trailingslashit( plugins_url( 'assets', __FILE__ ) ) );
        define( 'GV_ASSETS_ADMIN', trailingslashit( plugins_url( GV_ASSETS . '/admin', __FILE__ ) ) );
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