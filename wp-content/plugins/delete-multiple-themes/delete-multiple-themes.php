<?php
/*
Plugin Name: Delete Multiple Themes
Plugin URI: http://happyplugins.com
Description: Delete Multiple Themes in one click. 
Author: HappyPlugins
Version: 1.0.7
Author URI: http://happyplugins.com
Text Domain: delete-multiple-themes
*/


class DeleteMultipleThemes
{

    function __construct()
    {


        add_action('admin_menu', array($this, 'add_menu'));
        add_action('admin_init', array($this, 'delete_themes'));

    }


    function add_menu()
    {

        add_theme_page("Delete Multiple Themes", "Delete Themes", 'manage_options', 'delete-themes', array($this, 'display_themes'));
    }


    function display_themes()
    {

        /* Display Theme Table */
        $themes = wp_get_themes();
        $current_theme = wp_get_theme();

        foreach ($themes as $theme) {

            if ($theme->parent_theme) {
                $parents[] = $theme->parent_theme;
            }
        }

        ?>
        <div class="wrap">
            <div id="icon-themes" class="icon32"><br></div>
            <h2>Delete Multiple Themes</h2>
            <br>

            <?php require_once(dirname(__FILE__) . '/templates/updates_form.php'); ?>

            <div style="width:70%;  float:left;">
                <p>Choose the themes you want to delete and click on the button to delete them. After you delete the themes you
                    can not restore them so choose your steps carefully.</p>

                <p>If you choose to delete a theme that has child themes, all of the child themes will be deactivated and
                    you will not be able to use them.</p>
                <br>

                <form method="post">
                    <table class="wp-list-table widefat fixed posts">
                        <thead>
                        <tr>
                            <th class="check-column"><?php _e('&nbsp;', 'pippinw'); ?></th>
                            <th><?php _e('Name', 'pippinw'); ?></th>
                            <th><?php _e('Parent', 'pippinw'); ?></th>
                            <th><?php _e('Author', 'pippinw'); ?></th>
                            <th><?php _e('Version', 'pippinw'); ?></th>
                            <th><?php _e('Path', 'pippinw'); ?></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th class="check-column"><?php _e('&nbsp;', 'pippinw'); ?></th>
                            <th><?php _e('Name', 'pippinw'); ?></th>
                            <th><?php _e('Parent', 'pippinw'); ?></th>
                            <th><?php _e('Author', 'pippinw'); ?></th>
                            <th><?php _e('Version', 'pippinw'); ?></th>
                            <th><?php _e('Path', 'pippinw'); ?></th>
                        </tr>
                        </tfoot>
                        <tbody class="the-list">
                        <?php

                        foreach ($themes as $row) : ?>
                            <tr>

                                <th scope="row" class="check-column">
                                    <?php if ($current_theme->stylesheet != $row->stylesheet) { ?>

                                        <input type="checkbox" name="theme[]" value='<?php echo $row->stylesheet; ?>'/>

                                    <?php } ?>


                                </th>
                                <td><?php echo $row->name; ?>
                                    <?php
                                    if ($current_theme->stylesheet == $row->stylesheet) {
                                        echo '<br><span style="color:green;">(Current Theme)</span>';
                                    }
                                    if (is_array($parents)){
                                        if (in_array($row->name, $parents)) {
                                            echo '<br><span style="color:orange;">Has Child Themes</span>';
                                        }
                                    }
                                    ?></td>
                                <td><?php echo $row->parent_theme; ?></td>
                                <td><?php echo $row->author; ?></td>
                                <td><?php echo $row->version; ?></td>
                                <td><?php echo $row->template; ?></td>
                            </tr>
                        <?php
                        endforeach; ?>

                        </tbody>
                    </table>
                    <br/><br/><br/>

                    <?php wp_nonce_field('delete_multiple_themes', 'themes_delete'); ?>
                    <input type="submit" class="primary" value="Delete Themes"
                           onclick="return confirm( 'You are about to delete all of the selected themes \'Cancel\' to stop, \'OK\' to delete.' );">


                </form>
            </div>



            <div style="border: 1px solid #cdcdcd; padding: 18px; width: 25%; float:right;">


                <div>
                    <a href="http://store.happyplugins.com"/>

                    <h3>The Store</h3></a>
                    <p>Find unique WordPress plugins on our plugins store. We have designed and developed hundreds of
                        custom plugins and solutions for customers. We are selling the best of on our store.</p>
                </div>
                <div>
                    <a href="http://happyplugins.com"/>

                    <h3>The Blog</h3></a>
                    <p>Interested in our work or do you want to improve your WordPress development skills? check our blog
                        We publish unique prescriptive and sample codes from our plugins.</p>
                </div>
                <div>
                    <a href="http://happyplugins.com/get-a-quote"/>

                    <h3>The Service</h3></a>
                    <p>Looking for a special solution for WordPress, one that will be the missing puzzle piece
                        on your website? Send us your request and we promise to return to you no later than 72
                        hours.</p>
                </div>


            </div>
            <div>

                <?php // echo  file_get_contents("http://google.com"); ?>


            </div>

        </div>

    <?php

    }


    function delete_themes()
    {
        /* Precess and Delete Themes */

        /* Check Security */

        if (isset($_POST['themes_delete']) && wp_verify_nonce($_POST['themes_delete'], 'delete_multiple_themes')) {

            $themes_delete = $_POST['theme'];


            if (!function_exists('delete_theme')) {
                require_once(ABSPATH . WPINC . '/pluggable.php');
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/theme.php');
            }

            foreach ($themes_delete as $theme) {
                delete_theme($theme);
            }

        }

    } // End Function

} // End Class



/* Init DeleteMultipleThemes Class */

$deleteMultiple = new DeleteMultipleThemes();

?>