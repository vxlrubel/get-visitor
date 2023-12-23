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
            [''],                        // deps
            GV_VERSION,                  // version
            'all'                        // media
        );

        wp_enqueue_script(
            'gv-script',                       // handle
            GV_ASSETS . 'js/admin-script.js',  // source
            ['jquery'],                        // deps
            GV_VERSION,                        // version
            true                               // in footer
        );
    }

    /**
     * enqueue dashboard scripts and style
     *
     * @return void
     */
    public function dashboard_scripts(){
        wp_enqueue_style(
            'gv-admin-style',                  // handle
            GV_ASSETS_ADMIN . 'css/style.css', // source
            [''],                              // deps
            GV_VERSION,                        // version
            'all'                              // media
        );

        wp_enqueue_script(
            'gv-admin-script',                       // handle
            GV_ASSETS_ADMIN . 'js/admin-script.js',  // source
            ['jquery'],                              // deps
            GV_VERSION,                              // version
            true                                     // in footer
        );
    }
   
}