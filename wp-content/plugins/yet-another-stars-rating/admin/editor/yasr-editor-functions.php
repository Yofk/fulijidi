<?php

/*

Copyright 2014 Dario Curvino (email : d.curvino@tiscali.it)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>
*/

if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly

//this load guten-block.js, only in admin side
add_action('enqueue_block_editor_assets', 'yasr_gutenberg_scripts');
function yasr_gutenberg_scripts() {
    //Script
    wp_enqueue_script(
        'yasr-gutenberg',
        YASR_JS_DIR_ADMIN . 'yasr-gutenberg.js',
        array(
            'wp-blocks',
            'wp-components',
            'wp-editor',
            'wp-edit-post',
            'wp-element',
            'wp-i18n',
            'wp-plugins',
        )
    );

}

//This filter is used to add a new category in gutenberg
add_filter('block_categories', 'yasr_add_gutenberg_category', 10, 2);

function yasr_add_gutenberg_category($categories) {
    return array_merge(
        $categories,
        array(
            array(
                'slug'  => 'yet-another-stars-rating',
                'title' => 'Yasr: Yet Another Stars Rating',
            ),
        )
    );
}

add_action('yasr_add_admin_scripts_end', 'yasr_add_js_constant_gutenberg');

function yasr_add_js_constant_gutenberg($hook) {

    if (($hook === 'post.php' || $hook === 'post-new.php') && yasr_is_gutenberg_page() ) {

        //create an empty array
        $constants_array = array();

        //apply filters to empty array
        $constants_array = apply_filters('yasr_gutenberg_constants', $constants_array);

        //sanitize
        $constants_array = filter_var_array($constants_array,FILTER_SANITIZE_STRING);

        if(is_array($constants_array) && !empty($constants_array)) {
            wp_localize_script(
                'yasradmin',
                'yasrConstantGutenberg',
                $constants_array
            );
        }
    }
}

//Save auto insert value to yasrConstantGutenberg
add_filter('yasr_gutenberg_constants', 'yasr_gutenberg_constants');

function yasr_gutenberg_constants($constants_array) {

    //add after
    if (YASR_AUTO_INSERT_ENABLED === 1) {
        $auto_insert = YASR_AUTO_INSERT_WHAT;
    } else {
        $auto_insert = 'disabled';
    }

    $auto_insert_array = array (
        'adminurl'   => get_admin_url(),
        'autoInsert' => $auto_insert
    );

    return $constants_array + $auto_insert_array;
}


/****** Create 2 metaboxes in post and pages ******/
add_action('add_meta_boxes', 'yasr_add_metaboxes');

function yasr_add_metaboxes() {

    //Default post type where display metabox
    $post_type_where_display_metabox = array('post', 'page');

    //get the custom post type
    $custom_post_types = YasrCustomPostTypes::getCustomPostTypes();

    if ($custom_post_types) {
        //First merge array then changes keys to int
        $post_type_where_display_metabox = array_values(array_merge($post_type_where_display_metabox, $custom_post_types));
    }

    //For classic editor, add this metabox
    foreach ($post_type_where_display_metabox as $post_type) {
        add_meta_box(
            'yasr_metabox_overall_rating',
            'YASR',
            'yasr_metabox_overall_rating_content',
            $post_type,
            'side',
            'high',
            //Set this to true, so this metabox will be only loaded to classic editor
            array(
                '__back_compat_meta_box' => true,
            )
        );
    }

    foreach ($post_type_where_display_metabox as $post_type) {
        add_meta_box(
            'yasr_metabox_below_editor_metabox',
            __('Yet Another Stars Rating', 'yet-another-stars-rating'),
            'yasr_metabox_below_editor_metabox_callback',
            $post_type,
            'normal',
            'high'
        );
    }


} //End function

function yasr_metabox_overall_rating_content() {

    if (current_user_can(YASR_USER_CAPABILITY_EDIT_POST)) {
        include(YASR_ABSOLUTE_PATH_ADMIN . '/editor/yasr-metabox-top-right.php');
    } else {
        _e('You don\'t have enought privileges to insert Overall Rating', 'yet-another-stars-rating');
    }

}

function yasr_metabox_below_editor_metabox_callback() {
    if (current_user_can(YASR_USER_CAPABILITY_EDIT_POST)) {
        include(YASR_ABSOLUTE_PATH_ADMIN . '/editor/yasr-metabox-below-editor.php');
    } else {
        _e('You don\'t have enough privileges to insert a Multi Set', 'yet-another-stars-rating');
    }
}

/******* Add a media content button ******/
add_action('media_buttons', 'yasr_shortcode_button_media', 99);
function yasr_shortcode_button_media() {
    if (is_admin()) {
        add_thickbox();
        echo '<a href="#TB_inline?width=530&height=600&inlineId=yasr-tinypopup-form" 
                 id="yasr-shortcode-creator" 
                 class="button thickbox">
                 <span class="dashicons dashicons-star-half" style="vertical-align: middle;"></span> Yasr Shortcode
              </a>';

    }
}

/****** Create the content for the button shortcode in Tinymce ******/
//Add ajax action that will be called from the .js for button in tinymce
add_action('wp_ajax_yasr_create_shortcode', 'wp_ajax_yasr_create_shortcode_callback');
function wp_ajax_yasr_create_shortcode_callback() {
    if (!isset($_GET['action'])) {
        exit();
    }
    global $wpdb;
    $multi_set = YasrMultiSetData::returnMultiSetNames();
    $n_multi_set = $wpdb->num_rows;

    ?>

    <div id="yasr-tinypopup-form">
        <h2 class="nav-tab-wrapper yasr-underline">
            <a href="#" id="yasr-link-tab-main"
               class="nav-tab nav-tab-active yasr-nav-tab"><?php _e("Main", 'yet-another-stars-rating'); ?></a>
            <a href="#" id="yasr-link-tab-charts"
               class="nav-tab yasr-nav-tab"><?php _e("Rankings", 'yet-another-stars-rating'); ?></a>
            <?php do_action('yasr_add_tabs_on_tinypopupform'); ?>

            <a href="https://yetanotherstarsrating.com/yasr-basics-shortcode/?utm_source=wp-plugin&utm_medium=tinymce-popup&utm_campaign=yasr_editor_screen"
               target="_blank"
               id="yasr-tinypopup-link-doc">
                <?php _e('Read the doc', 'yet-another-stars-rating'); ?>
            </a>
        </h2>

        <div id="yasr-content-tab-main" class="yasr-content-tab-tinymce">
            <table id="yasr-table-tiny-popup-main" class="form-table">

                <tr>
                    <th>
                        <label for="yasr-overall">
                            <?php _e('Overall Rating', 'yet-another-stars-rating'); ?>
                        </label>
                    </th>
                    <td>
                        <input
                            type="button"
                            class="button-primary"
                            id="yasr-overall"
                            name="yasr-overall"
                            value="<?php _e('Insert Overall Rating', 'yet-another-stars-rating'); ?>"
                        />
                        <br/>
                        <small>
                            <?php _e('Insert the author rating', 'yet-another-stars-rating'); ?>
                        </small>

                        <div id="yasr-overall-choose-size">
                            <small>
                                <?php _e('Choose Size', 'yet-another-stars-rating'); ?>
                            </small>
                            <div class="yasr-tinymce-button-size">
                                <?php
                                    echo yasr_tinymce_return_button('yasr_overall_rating');
                                ?>
                            </div>
                        </div>

                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="yasr-id">
                            <?php _e("Visitor Votes", 'yet-another-stars-rating'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="button" class="button-primary" name="yasr-visitor-votes" id="yasr-visitor-votes"
                               value="<?php _e("Insert Visitor Votes", 'yet-another-stars-rating'); ?>"/><br/>
                        <small>
                            <?php _e('Insert the ability for your visitors to vote', 'yet-another-stars-rating'); ?>
                        </small>

                        <div id="yasr-visitor-choose-size">
                            <small>
                                <?php _e("Choose Size", 'yet-another-stars-rating'); ?>
                            </small>
                            <div class="yasr-tinymce-button-size">
                                <?php
                                    echo yasr_tinymce_return_button('yasr_visitor_votes');
                                ?>
                            </div>
                        </div>
                    </td>
                </tr>

                <?php if ($n_multi_set > 0) { //If multiple Set are found ?>
                    <tr>
                        <th>
                            <?php _e("Insert Multiset:", 'yet-another-stars-rating'); ?>
                        </th>
                        <td>
                            <?php foreach ($multi_set as $name) { ?>
                                <label>
                                    <input type="radio" value="<?php echo $name->set_id ?>" name="yasr_tinymce_pick_set"
                                           class="yasr_tinymce_select_set">
                                    <?php echo $name->set_name ?>
                                </label>
                                <br/>
                            <?php } //End foreach ?>
                            <small>
                                <?php _e('Choose wich set you want to insert.', 'yet-another-stars-rating'); ?>
                            </small>

                            <p>
                                <label for="yasr-allow-vote-multiset">
                                    <input type="checkbox" id="yasr-allow-vote-multiset">
                                    <?php _e("Readonly?", 'yet-another-stars-rating'); ?>
                                </label>
                                <br/>
                            </p>

                            <small>
                                <?php _e('If Readonly is checked, only you can insert the votes (in the box above the editor)',
                                    'yet-another-stars-rating'); ?>
                            </small>

                            <p>
                                <label for="yasr-hide-average-multiset">
                                    <input type="checkbox" id="yasr-hide-average-multiset">
                                    <?php _e("Hide Average?", 'yet-another-stars-rating'); ?>
                                </label>
                                <br/>
                            </p>

                            <p>
                                <input type="button" class="button-primary"
                                       name="yasr-insert-multiset"
                                       id="yasr-insert-multiset-select"
                                       value="<?php _e("Insert Multi Set", 'yet-another-stars-rating') ?>"/
                                >
                                <br/>
                            </p>

                        </td>
                    </tr>
                    <?php
                } //End if
                ?>
            </table>

        </div>

        <div id="yasr-content-tab-charts" class="yasr-content-tab-tinymce" style="display:none">

            <table id="yasr-table-tiny-popup-charts" class="form-table">
                <tr>
                    <th>
                        <label for="yasr-10-overall">
                            <?php _e("Ranking by overall rating", 'yet-another-stars-rating'); ?>
                        </label>
                    </th>
                    <td>
                        <?php
                            echo yasr_tinymce_return_button('yasr_ov_ranking', 'Insert Ranking reviews')
                        ?>
                        <br/>
                        <small>
                            <?php _e('This ranking shows the highest rated posts rated through the overall_rating shortcode',
                                'yet-another-stars-rating'); ?>
                        </small>
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="yasr-10-highest-most-rated">
                            <?php _e('Ranking by visitors votes', 'yet-another-stars-rating'); ?>
                        </label>
                    </th>
                    <td>
                        <?php
                            echo yasr_tinymce_return_button('yasr_most_or_highest_rated_posts', 'Insert Users ranking')
                        ?>
                        <br/>
                        <small>
                            <?php _e(
                                    'This ranking shows both the highest and most rated posts rated through the 
                                    yasr_visitor_votes shortcode.  For an item to appear in this chart, it has to be rated at least twice. ',
                                'yet-another-stars-rating'); ?>
                        </small>
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="yasr-5-active-reviewers">
                            <?php _e('Most Active Authors', 'yet-another-stars-rating'); ?>
                        </label>
                    </th>
                    <td>
                        <?php
                            echo yasr_tinymce_return_button('yasr_top_reviewers', 'Insert Most Active Reviewers')
                        ?>
                        <br/>
                        <small>
                            <?php
                                _e('This ranking shows the most active reviewers on your site.',
                                'yet-another-stars-rating'); ?>
                        </small>
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="yasr-10-active-users">
                            <?php _e('Most Active Users', 'yet-another-stars-rating'); ?>
                        </label>
                    </th>
                    <td>
                        <?php
                            echo yasr_tinymce_return_button('yasr_most_active_users', 'Insert Most Active Reviewers')
                        ?>
                        <br/>
                        <small>
                            <?php _e('This ranking shows the most active users, displaying the login name if logged in or “Anonymous” if not.',
                                'yet-another-stars-rating'); ?>
                        </small>
                    </td>
                </tr>

            </table>

            <div style="font-size: medium">
                <?php
                    echo(
                        sprintf(__('%s Click here %s to customize the ranking and see a live preview',
                                    'yet-another-stars-rating'),
                    '<a href="options-general.php?page=yasr_settings_page&tab=rankings">', '</a>')
                    );
                ?>
            </div>

        </div>

        <?php do_action('yasr_add_content_on_tinypopupform'); ?>

    </div>

    <?php

    die();

} //End callback function

/**
 * @author Dario Curvino <@dudo>
 * @since  2.6.5 returns button to be used in tinymce
 *
 * @param      $shortcode
 * @param      $value
 *
 * @return string
 */
function yasr_tinymce_return_button ($shortcode, $value=false) {
    $html_to_return = '';

    if ($value === false) {
        $array_size = array('Small', 'Medium', 'Large');

        foreach ($array_size as $size) {
            $size_low       = strtolower($size);
            $data_attribute = "[$shortcode size=\"$size_low\"]";

            $html_to_return .= '<input type="button"
                                   class="button-secondary yasr-tinymce-shortcode-buttons"
                                   value="' . __($size, 'yet-another-stars-rating') . '"
                                   data-shortcode=\'' . $data_attribute . '\'
                                />&nbsp;';
        }
    } else {
        $data_attribute = "[$shortcode]";
        $html_to_return .= '<input type="button"
                                   class="button-primary yasr-tinymce-shortcode-buttons"
                                   value="' . __($value, 'yet-another-stars-rating') . '"
                                   data-shortcode=\'' . $data_attribute . '\'
                                />&nbsp;';
    }

    return $html_to_return;
}

/****** Get Set name from post or page and output the set,
 * used in yasr-metabox-multiple-rating******/
add_action('wp_ajax_yasr_send_id_nameset', 'yasr_output_multiple_set_callback');
function yasr_output_multiple_set_callback() {
    if (!current_user_can(YASR_USER_CAPABILITY_EDIT_POST)) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'yet-another-stars-rating'));
    }

    //in version < 2.1.0 set id could be 0
    $set_id  = (int) $_POST['set_id'];
    $post_id = (int) $_POST['post_id'];

    //set fields name and ids
    $set_fields = YasrMultiSetData::multisetFieldsAndID($set_id);

    //set meta values
    $array_to_return = YasrMultiSetData::returnArrayFieldsRatingsAuthor($set_id, $set_fields, $post_id);

    echo json_encode($array_to_return);

    die();
}