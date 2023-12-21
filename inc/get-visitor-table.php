<?php

namespace GetVisitor;

// derectly access denied
defined('ABSPATH') || exit;

/**
 * create a trait
 * @version 1.0
 * @author Rubel Mahmud <vxlrubel@gmail.com>
 * @link https://github.com/vxlrubel
 */


 trait Get_Visitor_Table{
    
    // define table name
    private $table = 'get_visitor';

    // create admin menu slug
    protected $slug_admin_menu = 'get-visitors';

    // create slug for settings options
    protected $slug_admin_settings = 'get-visitors-settings';

    // create slug for add visitor
    protected $slug_add_new = 'add-new-visitor';

    /**
     * generate the table name with dynamic prefix
     *
     * @return [string] $table_name
     */
    protected function table(){
        global $wpdb;
        $table_name = $wpdb->prefix . $this->table;

        return $table_name;
    }
 }
 