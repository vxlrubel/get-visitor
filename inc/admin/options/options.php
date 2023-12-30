<?php
// directly access denied
defined('ABSPATH') || exit;
?>
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
                        <?php $items = get_option('_gv_list_items'); ?>
                        <select id="item-count" class="regular-text">
                            <option value="10" <?php echo selected( $items, 10 );?>>10</option>
                            <option value="20" <?php echo selected( $items, 20 );?>>20</option>
                            <option value="30" <?php echo selected( $items, 30 );?>>30</option>
                            <option value="40" <?php echo selected( $items, 40 );?>>40</option>
                            <option value="50" <?php echo selected( $items, 50 );?>>50</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>

        <p class="submit">
            <input type="submit" class="button button-primary" value="Save Changes">
        </p>
    </form>
</div>