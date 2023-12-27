<?php

namespace GetVisitor\Admin;
use GetVisitor\Get_Visitor_Table as Database_Table;
// derectly access denied
defined('ABSPATH') || exit;

/**
 * create a trait
 * @version 1.0
 * @author Rubel Mahmud <vxlrubel@gmail.com>
 * @link https://github.com/vxlrubel
 */
class Get_Visitor_Template{

    use Database_Table;

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
        printf(
            '<a href="%1$s" class="page-title-action">%2$s</a>',
            esc_url( admin_url('admin.php?page='. $this->slug_add_new ) ),
            esc_html('Add New')
        );
        echo '<hr class="wp-header-end">';
        $visitor_list->prepare_items();
        echo "<form method=\"POST\" name=\"get_visitor_form\" action=\"{$_SERVER['PHP_SELF']}?page=get-visitors\">";
            $visitor_list->search_box( 'Search', 'search_contact_info' );
            echo '</form>';
        $visitor_list->display();
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
                <a href="<?php echo admin_url( 'admin.php?page=' . $this->slug_admin_menu );?>" class="page-title-action">Visitor list</a>
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

    public function settings_option(){
        ?>
            <div class="wrap gv-settings">
                <h1 class="wp-heading-inline">Settings Option</h1>

                <hr class="wp-header-end">

                <div class="tab-parent">
                    <div id="tabs">
                        <ul>
                            <li><a href="#general">General</a></li>
                            <li><a href="#options">Options</a></li>
                        </ul>

                        <div id="general">
                            <form action="javascript:void();" class="settings-general-form">
                                <table class="form-table" role="presentation">
                                    <tbody>
                                        <tr>
                                            <th scope="row">
                                                <label for="title">Form Title</label>
                                                <span class="small">Show the title in the frontend.</span>
                                            </th>
                                            <td>
                                                <input type="text" class="regular-text" id="title">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">
                                                <label for="desc">Description</label>
                                                <span class="small">Insert short description.</span>
                                            </th>
                                            <td>
                                                <textarea id="desc" class="regular-text" rows="3"></textarea>
                                                <span class="small">This descriptions show above the subscribe form.</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">
                                                <label for="placeholder">Placeholder</label>
                                                <span class="small">Change placeholder text from here.</span>
                                            </th>
                                            <td>
                                                <input type="text" class="regular-text" id="placeholder">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">
                                                <label for="success">Success</label>
                                                <span class="small">Change success notice.</span>
                                            </th>
                                            <td>
                                                <input type="text" class="regular-text" id="success">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">
                                                <label for="warning">Warning</label>
                                                <span class="small">Change warning notice.</span>
                                            </th>
                                            <td>
                                                <input type="text" class="regular-text" id="warning">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <p class="submit">
                                    <input type="submit" class="button button-primary" value="Save Changes">
                                </p>

                            </form>
                        </div>

                        <div id="options">
                            <form action="javascript:void();" class="settings-option-form">
                                <table class="form-table" role="presentation">
                                    <tbody>
                                        <tr>
                                            <th scope="row">
                                                <label for="item-count">Items</label>
                                                <span class="small">Show items in the table data.</span>
                                            </th>
                                            <td>
                                                <select name="" id="item-count" class="regular-text">
                                                    <option value="10">10</option>
                                                    <option value="15">15</option>
                                                    <option value="20">20</option>
                                                    <option value="25">25</option>
                                                    <option value="30">30</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">
                                                <label for="desc">Description</label>
                                                <span class="small">Insert short description.</span>
                                            </th>
                                            <td>
                                                <textarea id="desc" class="regular-text" rows="3"></textarea>
                                                <span class="small">This descriptions show above the subscribe form.</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <p class="submit">
                                    <input type="submit" class="button button-primary" value="Save Changes">
                                </p>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        <?php
    }
}