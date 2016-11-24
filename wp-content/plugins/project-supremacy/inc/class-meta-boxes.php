<?php
/**
 * The Class.
 */
class PS_Post_Seo {

    /**
     * Hook into the appropriate actions when the class is constructed.
     */
    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post', array( $this, 'save' ) );
    }

    public static function init(){
        new PS_Post_Seo();
    }

    /**
     * Adds the meta box container.
     */
    public function add_meta_box( $post_type ) {
        $post_types = array('post', 'page');     //limit meta box to certain post types
        if ( in_array( $post_type, $post_types )) {
            add_meta_box(
                'ps_post_seo_box'
                ,__( 'Project Supermacy Post SEO', 'myplugin_textdomain' )
                ,array( $this, 'render_meta_box_content' )
                ,$post_type
                ,'advanced'
                ,'high'
            );
        }
    }

    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save( $post_id ) {

        global $wpdb;

        /*
         * We need to verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times.
         */

        // Check if our nonce is set.
        if ( ! isset( $_POST['myplugin_inner_custom_box_nonce'] ) )
            return $post_id;

        $nonce = $_POST['myplugin_inner_custom_box_nonce'];

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'myplugin_inner_custom_box' ) )
            return $post_id;

        // If this is an autosave, our form has not been submitted,
        //     so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return $post_id;

        // Check the user's permissions.
        if ( 'page' == $_POST['post_type'] ) {

            if ( ! current_user_can( 'edit_page', $post_id ) )
                return $post_id;

        } else {

            if ( ! current_user_can( 'edit_post', $post_id ) )
                return $post_id;
        }

        /* OK, its safe for us to save the data now. */

        $post = get_post( $post_id );

        if ($_POST['ps_seo_title'] == 'NULL' && $_POST['ps_seo_desc'] == 'NULL') {
            $wpdb->update( 'gkty_group', array(
                'enabled_seo'=>$_POST['enabled_seo']
            ), array(
                'id_post_page' => $post_id
            ));
        } else {
            $wpdb->update( 'gkty_group', array(
                'title' => (empty($_POST['ps_seo_title'])) ? $_POST['post_title'] : $_POST['ps_seo_title'],
                'description' => $_POST['ps_seo_desc'],
                'h1'=>$_POST['post_title'],
                'enabled_seo'=>$_POST['enabled_seo']
            ), array(
                'id_post_page' => $post_id
            ));
        }

        if ( ! wp_is_post_revision( $post_id ) ) {
            remove_action( 'save_post', array( $this, 'save' ) );

            add_action( 'save_post', array( $this, 'save' ) );
        }

    }


    /**
     * Render Meta Box content.
     *
     * @param WP_Post $post The post object.
     */
    public function render_meta_box_content( $post ) {

        // Add an nonce field so we can check for it later.
        wp_nonce_field( 'myplugin_inner_custom_box', 'myplugin_inner_custom_box_nonce' );

        global $wpdb;
        $ps_post = '';

        $table_name = "gkty_group";
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
            $ps_post = $wpdb->get_row("SELECT * FROM gkty_group WHERE id_post_page = '$post->ID'");
        }

        if(!is_object($ps_post)){
            $title = $post->post_title. ' - '. get_bloginfo();
            $post_url = network_site_url( '/' ) . urldecode($post->post_name) . '/';
            $post_desc = '';
            $title_value = '';
            $enabled_seo = '';
        }else{
            $title = $ps_post->title;
            $post_url = str_replace(array('http://', 'https://'), '', network_site_url( '/' )) . strtolower($ps_post->url) . '/';
            $post_desc = $ps_post->description;
            $title_value = $ps_post->title;
            $enabled_seo = ($ps_post->enabled_seo == 1) ? 'checked' : '';
        }

        if ($enabled_seo != '') {

            ?>
            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row">Snippet Preview </th>
                    <td>
                        <div id="psseosnippet">
                            <a class="title" id="psseosnippet_title" href="#"><?php echo $title; ?></a>
                            <span class="url"><?php echo $post_url; ?></span>
                            <p class="desc"><span class="autogen"></span><span class="content"><?php echo $post_desc; ?></span></p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="ps_seo_title">SEO Title:</label></th>
                    <td><input placeholder="<?php echo $post->post_title. ' - '. get_bloginfo();?>" type="text" id="ps_seo_title" name="ps_seo_title" value="<?php echo $title_value; ?>" size="52" />
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="ps_seo_desc">Meta Description:</label></th>
                    <td><textarea class="large-text metadesc" rows="2" id="ps_seo_desc" name="ps_seo_desc"><?php echo $post_desc; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="enabled_seo">Enable PS SEO on this Page:</label></th>
                    <td>
                        <input type="hidden" name="enabled_seo" value="0"/>
                        <input type="checkbox" name="enabled_seo" id="enabled_seo" value="1" <?echo $enabled_seo;?> />
                    </td>
                </tr>
                </tbody>
            </table>
            <?php
        } else {
            ?>
            <table class="form-table">
            <tbody>
            <tr>
                <th scope="row"><label for="enabled_seo">Enable PS SEO on this Page:</label></th>
                <td>
                    <input type="hidden" name="ps_seo_title" value="NULL"/>
                    <input type="hidden" name="ps_seo_desc" value="NULL"/>
                    <input type="hidden" name="enabled_seo" value="0"/>
                    <input type="checkbox" name="enabled_seo" id="enabled_seo" value="1" <?echo $enabled_seo;?> />
                </td>
            </tr>
            </tbody>
            </table>
            <?php
        }

    }
}

