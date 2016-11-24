(function() {

    tinymce.PluginManager.add('ps_affiliate_shortcodes', function( editor, url ) {

        var boxes =  []; // my array
        var box   =  {}; // my obj

        if (shortcodes.length < 1) {
            box = {
                text: 'No Keywords',
                value: 'There are no available affiliate keywords! Please create some!',
                onclick: function() {
                    return false;
                }
            };
            boxes.push(box);
        }else{
            jQuery.each(shortcodes , function( index, value ) {
                //alert(JSON.stringify(value));
                box = {
                    text: value.Shortcode,
                    value: '[aff name="' + value.Shortcode + '" title="'+value.Title+'"]',
                    onclick: function() {

                        editor.windowManager.open( {
                            title: 'Keyword - ' + value.Shortcode,
                            width : 400,
                            height: 100,
                            body: [
                                {
                                    type: 'textbox',
                                    name: 'anchor_text',
                                    label: 'Anchor Text',
                                    value: value.Title
                                },
                                {
                                    hidden: true,
                                    type: 'textbox',
                                    name: 'shortcode_id',
                                    value: value.id
                                }
                            ],
                            onsubmit: function( e ) {

                                editor.insertContent( '[aff name="' + value.Shortcode + '" title="'+e.data.anchor_text+'"]');
                            }
                        });

                    }
                };
                boxes.push(box);
            });
        }

        editor.addButton( 'ps_affiliate_shortcodes', {
            title: 'Project Sup - Affiliate Shortcodes',
            type: 'menubutton',
            image: '/wp-content/plugins/project-supremacy/css/icon.png',
            menu: boxes
        });
    });
})();
