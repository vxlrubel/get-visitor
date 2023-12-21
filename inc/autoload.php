<?php

// directly access denied
defined('ABSPATH') || exit;

$files = [
    'get-visitor-table',
];

foreach ( $files as $file ) {
    if ( file_exists( dirname(__FILE__) . '/' . $file ) ) {
        require_once dirname(__FILE__) . "/{$file}.php";
    }
}