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
                <h2 class="form-title"><?php echo esc_html( get_option( '_gv_form_title' ) );?></h2>
                <p><?php echo esc_html( get_option( '_gv_form_desc' ) );?></p>
                <form action="javascript:void(0)">
                    <input type="email" placeholder="<?php echo esc_attr( get_option( '_gv_form_desc' ) );?>">
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

                        <?php
                        
                            require_once dirname(__FILE__) . '/options/general.php';

                            require_once dirname(__FILE__) . '/options/options.php';
                        ?>
                    </div>
                </div>

            </div>
        <?php
    }
}