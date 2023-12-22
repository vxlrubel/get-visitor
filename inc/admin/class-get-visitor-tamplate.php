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
                <h1 class="wp-heading-inline">Add New Visitor</h1>
                <a href="javascript:void(0)" class="page-title-action">Visitor list</a>
                <hr class="wp-header-end">
                <div class="notice notice-success is-dismissible"> 
                    <p><strong>Email add successfully.</strong></p>
                    <button type="button" class="notice-dismiss">
                        <span class="screen-reader-text">Dismiss this notice.</span>
                    </button>
                </div>
                <form action="javascript:void(0)" novalidate="novalidate">
                    <table class="form-table" role="presentation">
                        <tbody>
                            <tr>
                                <th scope="row">
                                    <label for="email-address">Email Address</label>
                                </th>
                                <td>
                                    <input name="blogname" type="email" class="regular-text" id="email-address">
                                    <p class="submit">
                                        <input type="submit" name="submit" class="button button-primary" value="Add User">
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
                
            </div>
        
        <?php
    }
}