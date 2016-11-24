jQuery(document).ready(function($) {

    tinymce.PluginManager.add('ps_backto_shortcodes', function( editor, url ) {


        if (project_id != 0) {
            editor.addButton( 'ps_backto_shortcodes', {
                title: 'Project Sup - Back to project',
                icon: 'undo',
                onclick: function() {
                    document.location.href = "/wp-admin/admin.php?page=gkty_page_project&ch_page=edit_project&p_id=" + project_id;
                }
            });
        }

    });

});