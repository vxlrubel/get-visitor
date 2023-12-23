<?php

// directly access denied
defined('ABSPATH') || exit;

$files = [
    'get-visitor-table',
    'admin/class-admin-menu',
    'admin/class-get-visitor-tamplate',
    'api/class-api',
    'api/class-api-get-visitor',
    'class-assets',
];

foreach ( $files as $file ) {
    if( file_exists(  dirname( __FILE__ ) . '/' . $file . '.php' ) ){
        require_once dirname( __FILE__ ) . '/' . $file . '.php';
    }
}