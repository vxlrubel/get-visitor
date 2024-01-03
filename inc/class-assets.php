<?php

namespace GetVisitor;

// derectly access denied
defined('ABSPATH') || exit;

/**
 * create a assets class for enqueue scripts and style
 * @version 1.0
 * @author Rubel Mahmud <vxlrubel@gmail.com>
 * @link https://github.com/vxlrubel
 */

class Assets{
    public function __construct(){

        // enqueue forntend scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ] );

        // enqueue dashboard scripts
        add_action( 'admin_enqueue_scripts', [ $this, 'dashboard_scripts' ] );
    }

    /**
     * enqueue frontend scripts and styles
     *
     * @return void
     */
    public function frontend_scripts(){
        wp_enqueue_style(
            'gv-style',                  // handle
            GV_ASSETS . 'css/style.css', // source
            [],                          // deps
            GV_VERSION,                  // version
        );

        wp_enqueue_script(
            'gv-script',                       // handle
            GV_ASSETS . 'js/main.js',          // source
            ['jquery'],                        // deps
            GV_VERSION,                        // version
            true                               // in footer
        );

        wp_localize_script( 'gv-script', 'GV', [
            'nonce'   => wp_create_nonce('wp_rest'),
            'api_url' => home_url( '/wp-json/getvisitor/v1/visitor' ),
        ] );
    }

    /**
     * enqueue dashboard scripts and style
     *
     * @return void
     */
    public function dashboard_scripts(){
        wp_enqueue_style(
            'gv-admin-style',                  // handle
            GV_ASSETS_ADMIN . 'css/admin-style.css', // source
            [],                                // deps
            GV_VERSION,                        // version
        );

        wp_enqueue_script(
            'gv-admin-script',                       // handle
            GV_ASSETS_ADMIN . 'js/admin-script.js',  // source
            ['jquery', 'jquery-ui-tabs'],            // deps
            GV_VERSION,                              // version
            true                                     // in footer
        );

        wp_localize_script( 'gv-admin-script', 'GV', [
            'nonce'             => wp_create_nonce('wp_rest'),
            'api_url'           => home_url( '/wp-json/getvisitor/v1/visitor' ),
            'api_settings_url'  => home_url( '/wp-json/getvisitor/v1/settings' ),
            'multiple_delete'   => home_url( '/wp-json/getvisitor/v1/dropvisitors' ),
            'ajax_url'          => admin_url( 'admin-ajax.php' ),
            'deactivation_link' => esc_url( $this->get_plugin_deactiveation_url() ),
        ] );
    }

    /**
     * create plugin deactivation link
     *
     * @return void
     */
    private function get_plugin_deactiveation_url(){
        $plugin_file       = 'get-visitor/get-visitor.php';
        $deactivation_link = wp_nonce_url( admin_url( 'plugins.php?action=deactivate&plugin=' . urlencode( $plugin_file ) ) , 'deactivate-plugin_' . $plugin_file  );

        return $deactivation_link;
    }
   
}