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

    public function __construct(){
        add_shortcode( 'get_visitor_form', [ $this, 'data_collection_form' ] );
    }

    public function data_collection_form(){
        ob_start(); ?>
            <div class="gv-collect-form">
                <h2 class="form-title">Subscribe Us</h2>
                <p>Your email address will be secure with us. Your privacy is our prime concern.</p>
                <form action="javascript:void(0)">
                    <input type="email" placeholder="example@domail.com">
                    <div class="submit-parent">
                        <button type="submit">Subscribe</button>
                    </div>
                </form>
            </div>

        <?php return ob_get_clean();
    }

    /**
     * display visitor list
     *
     * @return void
     */
    public function display_visitor_list(){
        $visitor_list = new Visitor_List_Table;
        echo "<div class=\"wrap get-visitor-list-parent\"> \n";
        echo '<h1 class="wp-heading-inline">Visitor List</h1>';
        $visitor_list->prepare_items();
        echo "<div> \n";
    }

    /**
     * display add visitor form
     *
     * @return void
     */
    public function add_new_visitor(){
        ?>
            <div class="wrap add-new-visitor">
                <h1 class="wp-heading-inline">Add New Visitor</h1>
                <a href="javascript:void(0)" class="page-title-action">Visitor list</a>
                <hr class="wp-header-end">
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