<?php

namespace GetVisitor\Admin;

// derectly access denied
defined('ABSPATH') || exit;

/**
 * create a trait
 * @version 1.0
 * @author Rubel Mahmud <vxlrubel@gmail.com>
 * @link https://github.com/vxlrubel
 */
class Get_Visitor_Template{

    /**
     * display visitor list
     *
     * @return void
     */
    public function display_visitor_list(){
        echo "<div class=\"wrap get-visitor-list-parent\"> \n";
        echo 'hello world';
        echo "<div> \n";
    }

    /**
     * display add visitor form
     *
     * @return void
     */
    public function add_new_visitor(){
        ?>
        <div class="wrap">
            hello world
        </div>
        
        <?php
    }
}