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