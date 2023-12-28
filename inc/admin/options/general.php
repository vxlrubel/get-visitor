<?php
// directly access denied
defined('ABSPATH') || exit;
?>

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