(function() {

    tinymce.PluginManager.add('gavickpro_tc_button', function( editor, url ) {

        var boxes =  []; // my array
        var box   =  {}; // my obj

        if (keywords_array.length < 1) {
            box = {
                text: 'No Keywords',
                value: 'There are no available keywords for this post/page!',
                onclick: function() {
                    editor.insertContent(this.value());
                }
            };
            boxes.push(box);
        }else{
            jQuery.each(keywords_array , function( index, value ) {

                box = {
                    text: index,
                    menu: []
                };

                jQuery.each(value , function( i, v ) {

                    var body = [{
                        type: 'textbox',
                        name: 'url',
                        label: 'Anchor URL:',
                        value: (v.url == "") ? '' : document.location.protocol + '//' + document.location.host + '/' + v.url + '/',
                        placeholder: 'test123'
                    },
                        {
                            name: 'cpfl',
                            type: 'checkbox',
                            label: 'Capitalize first word:'
                        }];

                    if (v.id_post_page == 0) {
                        body.push( {
                            type   : 'container',
                            label  : 'Page not Created:',
                            html   : '<a style="color:red" href="/wp-admin/admin.php?page=gkty_page_project&ch_page=edit_project&p_id='+v.project_id+'" target="_blank">Click to Create Now!</a>'
                        });

                        v.url = '';
                    }

                    var boxic = {
                        text: v.name,
                        value: '[supreme id="'+v.id+'"]',
                        onclick: function() {
                            editor.windowManager.open( {
                                title: 'Keyword - ' + v.name,
                                width : 800,
                                height: 150,
                                body: body,
                                onsubmit: function( e ) {
                                    editor.insertContent( '[supreme kwid=' + v.id + ' kw="' + v.name + '" url="' + e.data.url + '" cpfl=' + e.data.cpfl + ']');
                                }
                            });
                        }
                    };

                    box.menu.push(boxic);
                });


                boxes.push(box);
            });
        }

        editor.addButton( 'gavickpro_tc_button', {
            title: 'Project Sup - ShortCode Keywords',
            type: 'menubutton',
            image: '/wp-content/plugins/project-supremacy/css/icon.png',
            menu: boxes
        });
    });
})();
