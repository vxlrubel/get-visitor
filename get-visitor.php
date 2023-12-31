<?php
/*
 * Plugin Name:       Get Visitor
 * Plugin URI:        https://github.com/vxlrubel/get-visitor
 * Description:       The "Get Visitor" WordPress plugin is a lightweight tool designed to facilitate email subscription collection from site visitors. Leveraging the WordPress REST API, this plugin provides an easy interface for visitors to subscribe to your email list. Upon visitor subscription through the provided form, their email addresses are securely collected and stored for your email marketing purposes.
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Rubel Mahmud ( Sujan )
 * Author URI:        https://github.com/vxlrubel/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       get-visitor
 * Domain Path:       /lang
 */

// include autoload file
if ( file_exists( dirname(__FILE__) . '/inc/autoload.php' ) ){
    require_once dirname(__FILE__) . '/inc/autoload.php';
}

use GetVisitor\Assets;
use GetVisitor\Admin\Admin_Menu;
use GetVisitor\Admin\Ajax_Request;
use GetVisitor\Admin\Get_Visitor_Template;
use GetVisitor\Api\Api as Register_Api;

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

        register_activation_hook( __FILE__, [ $this, 'include_default_settings_options' ] );

        register_deactivation_hook( __FILE__, [ $this, 'plugin_deactive' ] );

        // add plugin action links
        add_filter( 'plugin_action_links', [ $this, 'settings_page_url' ], 10, 2 );

        // create admin menu
        new Admin_Menu;

        // register api
        new Register_Api;

        // initiate assets
        new Assets;

        // initiate the ajax request 
        new Ajax_Request;
        
    }

    /**
     * plugin deactivate
     *
     * @return void
     */
    public function plugin_deactive(){
        global $wpdb;
        $table = $this->table();
        $sql   =  "DROP TABLE IF EXISTS $table";
        $wpdb->query( $sql );
    }

    /**
     * create settins link
     *
     * @param [type] $links
     * @param [type] $file
     * @return void
     */
    public function settings_page_url( $links, $file ){

        if ( plugin_basename( __FILE__ ) === $file ){

            $anchor = sprintf(
                '<a href="%1$s">%2$s</a>',
                esc_url( admin_url( '/admin.php?page='. $this->slug_admin_settings ) ),
                'Settings'
            );
            
            array_unshift( $links, $anchor );
        }

        return $links;
    }

    /**
     * default settings options
     *
     * @return void
     */
    public function include_default_settings_options(){

        if ( empty( get_option('_gv_form_title') ) ){
            update_option( '_gv_form_title', 'Subscribe Us' );
        }

        if ( empty( get_option('_gv_form_desc') ) ){
            update_option( '_gv_form_desc', 'Your email address will be secure with us. Your privacy is our prime concern.' );
        }

        if ( empty( get_option('_gv_placeholder') ) ){
            update_option( '_gv_form_desc', 'example@domain.com' );
        }
        
        if ( empty( get_option('_gv_notice_success') ) ){
            update_option( '_gv_form_desc', 'subscribe successfully.' );
        }

        if ( empty( get_option('_gv_notice_success') ) ){
            update_option( '_gv_form_desc', 'something went wrong.' );
        }

        if( empty( get_option('_gv_list_items') ) ){
            update_option( '_gv_list_items', 10 );
        }
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
        define( 'GV_ASSETS_ADMIN', trailingslashit( GV_ASSETS . 'admin' ) );
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

$get_visitor = new Get_Visitor_Template;

get_visitor();