<?php

namespace GetVisitor\Admin;
use WP_List_Table;
use GetVisitor\Get_Visitor_Table as DB_Table;
    

// derectly access denied
defined('ABSPATH') || exit;

/**
 * create a visitor list table to show the visitor inside the table
 * @version 1.0
 * @author Rubel Mahmud <vxlrubel@gmail.com>
 * @link https://github.com/vxlrubel
 */

if ( ! class_exists('WP_List_Table') ){
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

 class Visitor_List_Table extends WP_List_Table{

    use DB_Table;

    public function __construct(){
        parent::__construct( 
            [
                'singular' => 'Visitor',    // Singular name of the item
                'plural'   => 'Visitors',   // Plural name of the items
                'ajax'     => false,        // If using AJAX, set to true
            ]
         );
    }

    /**
     * prepare items for display
     *
     * @return void
     */
    public function prepare_items(){
        $start_date = '';
        $end_date   = '';

        if ( isset( $_POST['date_submit'] ) ){
            $start_date = isset( $_POST['start_date'] ) ? sanitize_text_field( $_POST['start_date'] ) : '';
            $end_date = isset( $_POST['end_date'] ) ? sanitize_text_field( $_POST['end_date'] ) : '';
        }

        $order_by      = isset( $_GET['orderby'] ) ? trim($_GET['orderby']) : 'ID';
        $order         = isset( $_GET['order'] ) ? trim( $_GET['order'] ) : 'DESC';
        $search_term   = isset( $_POST['s'] ) ? trim( $_POST['s'] ) : '';
        $get_columns   = $this->get_columns();
        $data          = $this->get_data( $order_by, $order, $search_term, $start_date, $end_date );
        $hidden_column = $this->get_hidden_columns();

        // for pagination
        $items_per_page = get_option('_gv_list_items') ? get_option('_gv_list_items') : 10;
        $current_page   = $this->get_pagenum();
        $total_items    = (int) count( $data );
        $this->set_pagination_args([
            'total_items' => $total_items,
			'per_page'    => $items_per_page,
        ]);

        $slice_data = array_slice( $data, ( $current_page - 1 ) * $items_per_page, $items_per_page );

        $sortable_column = $this->get_sortable_columns();

        $this->_column_headers = [ $get_columns, $hidden_column, $sortable_column ];
        $this->items = $slice_data;
    }


    /**
     * HIDE COLUMNS
     *
     * @return void
     */
    public function get_hidden_columns(){
        return ['ID'];
    }
    
    /**
     * define sortable columns
     *
     * @return void
     */
    public function get_sortable_columns(){
        $sortable_column = [
            'email' => [ 'email', false ]
        ];
        return $sortable_column;
    }
    
    /**
     * get all the data
     *
     * @return void
     */
    public function get_data( $order_by, $order, $search_term, $start_date, $end_date ){
        global $wpdb;
        $table    = $this->table();
        $response = '';

        if( ! empty( $search_term ) ){
            $sql    = "SELECT * FROM $table WHERE email LIKE '%$search_term%'";
            $result = $wpdb->get_results( $sql, ARRAY_A );

            if ( $result > 0 ){
                $response = $result;
            }
        }else{
            if ( ! empty( $start_date ) && !empty( $end_date ) ){
                $sql = $wpdb->prepare(
                    "SELECT * FROM $table WHERE created_at BETWEEN %s AND %s",
                    $start_date,
                    $end_date
                );
                $result = $wpdb->get_results( $sql, ARRAY_A );
                
                if ( $result > 0 ){
                    $response = $result;
                }
            }else{
                $sql    = "SELECT * FROM $table ORDER BY $order_by $order";
                $result = $wpdb->get_results( $sql, ARRAY_A );
    
                if ( $result > 0 ){
                    $response = $result;
                }
            }
        }
        
        return $response;
    }

    /**
     * get the columns
     *
     * @return void
     */
    public function get_columns(){
        $columns = [
            'cb'         => '<input type="checkbox" />',
            'ID'         => 'ID',
            'email'      => 'Email Address',
            'created_at' => 'Create Date',
            'updated_at' => 'Update Date',
        ];

        return $columns;
    }

    /**
     * define checkbox for each item
     *
     * @param [type] $item
     * @return void
     */
    public function column_cb( $item ){
        return sprintf(
            '<input type="checkbox" name="get_visitor[]" value="%s" />',
            $item['ID']
        );
    }

    /**
     * add row action inside the email column
     *
     * @param [type] $item
     * @return void
     */
    public function column_email( $item ){

        $link_edit = sprintf(
            '<a href="javascript:void(0)" class="edit-visitor-item" data-id="%s">%s</a>',
            (int)$item['ID'],
            esc_html( 'Edit' )
        );

        $link_delete = sprintf(
            '<a href="javascript:void(0)" class="delete-visitor-item" data-id="%s">%s</a>',
            (int)$item['ID'],
            esc_html( 'Delete' )
        );
        
        $action = [
            'edit'   => $link_edit,
            'delete' => $link_delete,
        ];

        return sprintf(
            '<span class="get-email-address">%1$s</span> %2$s',
            $item['email'],
            $this->row_actions( $action )
        );
        
        
    }

    /**
     * set default columns
     *
     * @param [type] $item
     * @param [type] $column_name
     * @return void
     */
    public function column_default( $item, $column_name ){
        switch( $column_name ){
            case 'ID':
            case 'email':
            case 'created_at':
            case 'updated_at':

                return $item[ $column_name ];

            default:

                return 'No value';
        }
    }

    /**
     * set bulk actions
     *
     * @return void
     */
    public function get_bulk_actions(){
        $actions = [
            'delete' => 'Detete',
        ];
        return $actions;
    }

    /**
     * filterable method. it's allow to filer by date
     *
     * @param [type] $which
     * @return void
     */
    public function extra_tablenav( $which ) {
        $action = "{$_SERVER['PHP_SELF']}?page={$this->slug_admin_menu}";
        if ($which === 'top'): ?>
            <div class="alignleft actions">
                <form action="<?php echo $action; ?>" class="gv-get-filter-form" method="POST">
                    <div class="date-field">
                        <div class="date-input from-date">
                            <span>From</span>
                            <input type="date" name="start_date">
                        </div>
                        <div class="date-input to-date">
                            <span>To</span>
                            <input type="date" name="end_date">
                        </div>
                    </div>
                    <div class="filter-submit">
                        <button type="submit" class="button action" name="date_submit">Apply</button>
                    </div>
                </form>
            </div>
        <?php endif;
    }
 }