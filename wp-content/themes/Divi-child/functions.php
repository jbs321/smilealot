<?php

// -------------- implement child theme -----------------

add_action( 'wp_enqueue_scripts', 'enqueue_parent_theme_style' );
function enqueue_parent_theme_style() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
}


// -------------- embed page builder in blog post -----------------

function myprefix_et_pb_before_main_editor( $post ) {
    if (in_array( $post->post_type, array('page', 'project'))) return;
    if (!post_type_supports($post->post_type, 'editor')) { return; }

    $is_builder_used = 'on' === get_post_meta( $post->ID, '_et_pb_use_builder', true ) ? true : false;

    printf( '<a href="#" id="et_pb_toggle_builder" data-builder="%2$s" data-editor="%3$s" class="button button-primary button-large%5$s">%1$s</a><div id="et_pb_main_editor_wrap"%4$s>',
        ( $is_builder_used ? __( 'Use Default Editor', 'Divi' ) : __( 'Use Page Builder', 'Divi' ) ),
        __( 'Use Page Builder', 'Divi' ),
        __( 'Use Default Editor', 'Divi' ),
        ( $is_builder_used ? ' class="et_pb_hidden"' : '' ),
        ( $is_builder_used ? ' et_pb_builder_is_used' : '' )
    );
}
add_action( 'edit_form_after_title', 'myprefix_et_pb_before_main_editor' );

function myprefix_et_pb_after_main_editor( $post ) {
    if (in_array( $post->post_type, array('page', 'project'))) return;
    if (!post_type_supports($post->post_type, 'editor')) { return; }
    ?>
    <p class="et_pb_page_settings" style="display: none;">
    <input type="hidden" id="et_pb_use_builder" name="et_pb_use_builder" value="" />
    <textarea id="et_pb_old_content" name="et_pb_old_content"></textarea>
    </p>
    </div>
    <?php
}
add_action( 'edit_form_after_editor', 'myprefix_et_pb_after_main_editor' );

function myprefix_et_pb_builder_post_types($post_types) {
    foreach(get_post_types() as $pt) {
        if (!in_array($pt, $post_types) and post_type_supports($pt, 'editor')) {
            $post_types[] = $pt;
        }
    }
    return $post_types;
}
add_filter('et_pb_builder_post_types', 'myprefix_et_pb_builder_post_types');
