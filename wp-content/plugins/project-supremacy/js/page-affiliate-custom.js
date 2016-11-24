(function ($) {

    $(document).ready(function(){

        functions.get_clickbank_table();
        functions.get_markethealth_table();
        functions.search_amazon_by_keyword();
        functions.get_shortcode_manager();

        functions.join();
        functions.remove_shortcode();
        functions.edit_shortcode();
        functions.refresh_clickbank_table();
        functions.clickbank_custom_filters();
        functions.add_new_shortcode();


        $(document).on('click', '.copy_shortcode', function(){
            var title = $(this).attr('data-title');
            var name = $(this).attr('data-name');
            jQuery.fancybox('<div style="width:400px">' +
                '<b>Copy the shortcode below:</b>' +
                '<pre>[aff name="'+name+'" title="'+title+'"]</pre>' +
                '</div>');
        });
    });


    var functions = {
        get_clickbank_table: function(){
            var table = $('#click_bank_table').DataTable({
                "dom": '<"top"li<"datatable-actions">>rt<"bottom"ip><"clear">',
                "responsive": true,
                "bProcessing": true,
                "bDestroy": true,
                "bPaginate": true,
                "bAutoWidth": true,
                "bFilter": true,
                "bServerSide": true,
                "sServerMethod": "POST",
                "sAjaxSource": "/wp-admin/admin-post.php",
                "iDisplayLength": 20,
                "aLengthMenu": [[10, 20, 50, 100], [10, 20, 50, 100]],
                "aaSorting": [[10, 'desc']],
                "aoColumns": [
                    {
                        "bSearchable": true,
                        "sClass": "text-center",
                        "bSortable": false,
                        "mData": 'Tag',
                        "mRender": function(data, type, row){
                            return '<a target="_blank" data-title="'+encodeURIComponent(row['Title'])+'" data-group="Clickbank" id="join" href="https://accounts.clickbank.com/info/jmap.htm?vendor='+data+'" class="btn btn-primary btn-xs"><i class="fa fa-check"></i> Create Shortcode</a>';
                        },
                        "asSorting": ["desc", "asc"]
                    },
                    {
                        "bSearchable": true,
                        "sClass": "text-center",
                        "bSortable": true,
                        "mData": 'Title',
                        "mRender": function(data, type, row){
                            return data;
                        },
                        "asSorting": ["desc", "asc"]
                    },
                    {
                        "bSearchable": true,
                        "sClass": "text-center",
                        "bSortable": true,
                        "mData": 'Description',
                        "mRender": function(data, type, row){
                            return data;
                        },
                        "asSorting": ["desc", "asc"]
                    },
                    {
                        "bSearchable": true,
                        "sClass": "text-center",
                        "bSortable": true,
                        "mData": 'Commission',
                        "mRender": function(data, type, row){
                            return data + '%';
                        },
                        "asSorting": ["desc", "asc"]
                    },
                    {
                        "bSearchable": true,
                        "sClass": "text-center",
                        "bSortable": true,
                        "mData": 'Gravity',
                        "mRender": function(data, type, row){
                            return data;
                        },
                        "asSorting": ["desc", "asc"]
                    },
                    {
                        "bSearchable": true,
                        "sClass": "text-center",
                        "bSortable": true,
                        "mData": 'PercentPerSale',
                        "mRender": function(data, type, row){
                            return parseInt(data) + " %";
                        },
                        "asSorting": ["desc", "asc"]
                    },
                    {
                        "bSearchable": true,
                        "sClass": "text-center",
                        "bSortable": true,
                        "mData": 'PercentPerRebill',
                        "mRender": function(data, type, row){
                            return parseInt(data) + " %";
                        },
                        "asSorting": ["desc", "asc"]
                    },
                    {
                        "bSearchable": true,
                        "sClass": "text-center",
                        "bSortable": true,
                        "mData": 'AverageEarningsPerSale',
                        "mRender": function(data, type, row){
                            return "$ " + parseInt(data);
                        },
                        "asSorting": ["desc", "asc"]
                    },
                    {
                        "bSearchable": true,
                        "sClass": "text-center",
                        "bSortable": true,
                        "mData": 'InitialEarningsPerSale',
                        "mRender": function(data, type, row){
                            return "$ " + parseInt(data);
                        },
                        "asSorting": ["desc", "asc"]
                    },
                    {
                        "bSearchable": true,
                        "sClass": "text-center",
                        "bSortable": true,
                        "mData": 'TotalRebillAmt',
                        "mRender": function(data, type, row){
                            return parseInt(data);
                        },
                        "asSorting": ["desc", "asc"]
                    },
                    {
                        "bSearchable": true,
                        "sClass": "text-center",
                        "bSortable": true,
                        "mData": 'Referred',
                        "mRender": function(data, type, row){
                            return parseInt(data);
                        },
                        "asSorting": ["desc", "asc"]
                    },
                    {
                        "bSearchable": true,
                        "sClass": "text-center",
                        "bSortable": true,
                        "mData": 'ActivateDate',
                        "mRender": function(data, type, row){
                            return data;
                        },
                        "asSorting": ["desc", "asc"]
                    }

                ],
                "fnInitComplete": function(settings, json) {
                    $('#custom_clickbank_filters').find('button[type="submit"]').prop('disabled', false);
                    $('#custom_clickbank_filters').find('button[type="submit"] i').removeClass('fa-spin');
                    $('.datatable-actions').html('<button style="margin-left:10px" type="button" class="btn btn-primary btn-xs pull-right" id="refresh_clickbank_table"><i class="fa fa-refresh"></i> Refresh Table</button>')
                },
                "fnServerParams": function (aoData) {
                    var filters = $('#custom_clickbank_filters').serializeArray();
                    for(var i = 0; i < filters.length; i++) {
                        var filter = filters[i];
                        aoData.push(filter);
                    }
                    var f = {
                        name: "action",
                        value: "datatable_clickbank"
                    };
                    aoData.push(f);
                },
                "fnCreatedRow": function( nRow, aData, iDataIndex ) {

                }
            });
        },
        get_markethealth_table: function(){
            jQuery('#markethealth').DataTable();
        },
        search_amazon_by_keyword: function(){

            $('#search_amazon').submit(function(e){
                e.preventDefault();
                var textbox = $(this).find('#AmazonKeywordSearch');
                var button = $(this).find('button');

                textbox.prop('disabled', true);
                button.prop('disabled', true);
                button.find('i').removeClass().addClass('fa fa-refresh fa-spin');

                var keyword = $(this).find('#AmazonKeywordSearch').val();

                if(keyword.length < 1){
                    alert("Please enter some keyword");
                    return;
                }

                var data = {
                    'action': 'amazon_search',
                    'keywords': keyword
                };
                jQuery.post("/wp-admin/admin-post.php", data)
                    .done(function(d){
                        var items = JSON.parse(d);

                        if(items.status == 'ERROR'){
                            alert(items.message);
                            textbox.prop('disabled', false);
                            button.prop('disabled', false);
                            button.find('i').removeClass().addClass('fa fa-search');
                            return false;
                        }

                        //If there is no search results show empty table
                        if(typeof(items.ItemSearchResponse.Items.Item) == "undefined"){

                            $('#amazon_table').DataTable();
                            $('#amazon_table').show();
                            textbox.prop('disabled', false);
                            button.prop('disabled', false);
                            button.find('i').removeClass().addClass('fa fa-search');

                            return;
                        }

                        items = items.ItemSearchResponse.Items.Item;

                        var html = '';

                        for(var i = 0; i < items.length; i++){

                            if (typeof(items[i].ItemAttributes) != "undefined")
                            {
                                html += '<tr>';
                                html += '   <td style="text-align: center">';
                                html += '   <button data-title="'+encodeURIComponent(items[i].ItemAttributes.Title)+'" data-group="Amazon" data-url="http://www.asin.info/a/'+items[i].ASIN+'/nc0032-20" target="_blank" class="btn btn-primary btn-xs" id="join"><i class="fa fa-check"></i> Create Shortcode</a>';
                                html += '   </td>';
                                html += '   <td style="text-align: center">';
                                html +=         items[i].ItemAttributes.Title;
                                html += '   </td>';

                                html += '   <td>';
                                //Get Product Description
                                if (typeof(items[i].ItemAttributes.Feature) != "undefined")
                                {
                                    var desc = items[i].ItemAttributes.Feature;

                                    if($.isArray(desc)){
                                        for(var x = 0; x < desc.length; x++){
                                            html += desc[x] + '<br>';
                                        }

                                    }else{
                                        html += desc + '<br>';
                                    }

                                }else{
                                    html += ' <span class="text-warning"><i class="fa fa-warning"></i> No Description Provided</span>';
                                }
                                html += '   </td>';

                                html += '   <td style="text-align: center">';
                                //Get Product Image
                                if (typeof(items[i].MediumImage) != "undefined")
                                {
                                    html += '       <a href="'+items[i].DetailPageURL+'" target="_blank"><img style="border: 1px solid #E0E0E0;border-radius: 10px;" src="'+items[i].MediumImage.URL+'" alt="No Preview" /></a>';
                                }else{
                                    html += ' <span class="text-warning"><i class="fa fa-warning"></i> No Preview</span>';
                                }

                                html += '   </td>';
                                html += '   <td style="text-align: center">';
                                html +=         '<b>'+items[i].ItemAttributes.ProductGroup + '</b><br>' + items[i].ItemAttributes.Manufacturer;
                                html += '   </td>';
                                html += '</tr>';
                            }


                        }

                        $('#amazon_table tbody').html(html);

                        $('#amazon_table').DataTable();

                        $('#amazon_table').slideDown();

                        textbox.prop('disabled', false);
                        button.prop('disabled', false);
                        button.find('i').removeClass().addClass('fa fa-search');
                    }, 'json');

            });

        },
        get_shortcode_manager: function(){

            var data = {
                action: 'affiliate_shortcodes'
            };

            jQuery.post("/wp-admin/admin-post.php", data)
                .done(function(d){
                    d = JSON.parse(d);

                    if(typeof(d) == "undefined"){

                        $('#shortcode_manager').DataTable();
                        $('#shortcode_manager').show();

                        return;
                    }


                    var html = '';
                    for(var i = 0; i < d.length; i++){
                        html += '<tr>';

                        html += '   <td style="text-align: center">';
                        html +=         d[i].Title;
                        html += '   </td>';
                        html += '   <td style="text-align: center">';
                        html +=         '<a href="'+d[i].URL+'" target="_blank">'+d[i].URL+'</a>';
                        html += '   </td>';
                        html += '   <td style="text-align: center">';
                        html +=         d[i].Group;
                        html += '   </td>';

                        if(d[i].Follow == 1){
                            html += '   <td style="text-align: center">';
                            html +=  '      <i class="fa fa-check fa-2x">';
                            html += '   </td>';
                        }else{
                            html += '   <td style="text-align: center">';
                            html +=  '      <i class="fa fa-ban fa-2x">';
                            html += '   </td>';
                        }

                        if(d[i].Nofollow == 1){
                            html += '   <td style="text-align: center">';
                            html +=  '      <i class="fa fa-check fa-2x">';
                            html += '   </td>';
                        }else{
                            html += '   <td style="text-align: center">';
                            html +=  '      <i class="fa fa-ban fa-2x">';
                            html += '   </td>';
                        }

                        console.log(d[i]);

                        if(d[i].Mask == 1){
                            html += '   <td style="text-align: center">';
                            html +=  '      <i class="fa fa-check fa-2x">';
                            html += '   </td>';
                        }else{
                            html += '   <td style="text-align: center">';
                            html +=  '      <i class="fa fa-ban fa-2x">';
                            html += '   </td>';
                        }

                        html += '   <td style="text-align: center">';
                        html += '      <button data-name="'+d[i].Shortcode+'" data-title="'+d[i].Title+'" class="btn btn-default btn-xs copy_shortcode" title="Copy Shortcode"><i class="fa fa-copy"></i></button>';
                        html += '      <button data-id="'+d[i].id+'" class="btn btn-primary btn-xs" id="edit_shortcode" title="Edit Shortcode"><i class="fa fa-wrench"></i></button>' +
                                '      <button data-id="'+d[i].id+'" class="btn btn-danger btn-xs" id="remove_shortcode" title="Delete Shortcode"><i class="fa fa-trash-o"></i></button>';
                        html += '   </td>';

                        html += '</tr>';
                    }



                    $('#shortcode_manager tbody').html(html);
                    $('#shortcode_manager').DataTable();

                }, 'json');
        },
        join: function(){
            $(document).on('click', '#join', function(){

                var group = $(this).attr('data-group');

                var aff_link = $(this).attr('data-url');
                var prod_title = decodeURIComponent($(this).attr('data-title')).replace(/\+/g, ' ');
                var shortcode_name = decodeURIComponent($(this).attr('data-title')).replace('+', ' ');

                shortcode_name = shortcode_name.replace(/[. \/\\\-_)(*&^%$#@!":|~';}\{\[\]+,<>\?]/g, "_");
                shortcode_name = shortcode_name.toLowerCase();

                functions.make_join_fancybox(group, aff_link, prod_title, shortcode_name);

            });

            $(document).on('submit', '#join_form', function(e){
                e.preventDefault();

                var follow = 0;
                if($(this).find('#Follow').prop('checked')) {
                    follow = 1;
                }

                var mask = 0;
                if($(this).find('#Mask').prop('checked')) {
                    mask = 1;
                }

                var nofollow = 0;
                if($(this).find('#NoFollow').prop('checked')) {
                    nofollow = 1;
                }

                if($(this).find('#Shortcode').val() == ''){
                    alert('Please enter shortcode unique name!');
                    return;
                }

                if($(this).find('#URL').val() == '' || $(this).find('#URL').val().length < 4){
                    alert('Please enter affiliate link!');
                    return;
                }

                if($(this).find('#Title').val() == ''){
                    alert('Please enter shortcode title so there is something to be shown on your site!');
                    return;
                }

                var data = {};
                if($(this).find('button[type="submit"]').attr('data-action') == 'true'){
                    data = {
                        action: 'affiliate_edit_shortcode',
                        what: {
                            Shortcode: $(this).find('#Shortcode').val(),
                            URL: $(this).find('#URL').val(),
                            Title: $(this).find('#Title').val(),
                            Follow: follow,
                            Nofollow: nofollow,
                            Group: $(this).find('button[type="submit"]').attr('data-group'),
                            Mask: mask
                        },
                        where: {
                            id: $(this).find('button[type="submit"]').attr('data-id')
                        }
                    };
                }else{
                    data = {
                        action: 'affiliate_add_shortcode',
                        Shortcode: $(this).find('#Shortcode').val(),
                        URL: $(this).find('#URL').val(),
                        Title: $(this).find('#Title').val(),
                        Follow: follow,
                        Nofollow: nofollow,
                        Group: $(this).find('button[type="submit"]').attr('data-group'),
                        Mask: mask
                    };
                }

                var button_i = $(this).find('button[type="submit"] i');
                var button = $(this).find('button[type="submit"]');

                button_i.removeClass().addClass('fa fa-refresh fa-spin');
                button.prop('disabled', true);

                jQuery.post("/wp-admin/admin-post.php", data)
                    .done(function(d){
                        d = JSON.parse(d);

                        if(d.status == 'error'){
                            button_i.removeClass().addClass('fa fa-search');
                            button.prop('disabled', false);

                            alert(d.message);
                            return;
                        }

                        functions.get_shortcode_manager();

                        jQuery.fancybox('<div class="white_row_wrapper  blue_lightbox" id="confirmation_blue">\
                                            <div class="conwrapper"><div class="modal-body">\
                                                <div class="lightbox_content">\
                                                    <div class="alert alert-success" style="text-align: center;margin: 0;" role="alert">\
                                                        <i class="fa fa-check"></i>\
                                                            '+ d.message+' <br>\
                                                            You can go and use them right away!\
                                                            <div style="margin-top: 25px;text-align: center;width: 257px;">\
                                                                <a style="margin-right: 8px;" href="/wp-admin/edit.php?post_type=page" class="btn btn-primary"><i class="fa fa-files-o"></i> Go to Pages</a>\
                                                                <a href="/wp-admin/edit.php" class="btn btn-primary"><i class="fa fa-file-o"></i> Go to Posts</a>\
                                                            </div>\
                                                    </div>\
                                                </div>\
                                            </div>\
                                        </div>');

                        jQuery.fancybox.close();



                    });

            });
        },
        make_join_fancybox: function(group, aff_link, title, shortcode_name, follow, is_edit, id, nofollow, mask){

            if(typeof(aff_link) == "undefined"){
                aff_link = '';
            }

            var alert = '';
            if(group == 'Clickbank'){
                alert = '<div class="alert alert-info" style="width: 350px;" role="alert">\
                            <i class="fa fa-info-circle"></i>\
                            When you login to Clickbank and click Create button it will give you your affiliate link, just copy the link and paste it to\
                            "Enter the affiliate link" below.\
                        </div>';
            }

            var do_follow = follow;

            if(mask == '1'){
                mask = 'checked';
            }else{
                mask = '';
            }

            if(do_follow == '1'){
                do_follow = 'checked';
            }else{
                do_follow = '';
            }

            var nofollow = nofollow;

            if(nofollow == '1'){
                nofollow = 'checked';
            }else{
                nofollow = '';
            }

            var edit_button_text = '';
            var disable_textbox = '';
            var edit_shortcode_unique_name = '';
            var edit_shortcode_title = '';
            if(is_edit){
                edit_button_text = 'Save';
                disable_textbox = 'disabled';
                edit_shortcode_unique_name = shortcode_name;
                edit_shortcode_title = title;
            }else{
                is_edit = false;
                edit_button_text = 'Add Shortcode';
            }

            if(typeof(id) == "undefined" ){
                id = '';
            }

            jQuery.fancybox('\
                    <div class="white_row_wrapper  blue_lightbox" id="confirmation_blue">\
                        <div class="conwrapper"><div class="modal-body">\
                            <div class="lightbox_content">\
                                '+alert+'\
                                <div class="join_link_preview">\
                                    <label><i class="fa fa-question-circle" title="This preview shows how the link will look like on the site"></i> Shortcode Preview</label>\
                                    <div style="max-width: 350px;padding: 15px;border: 1px solid #475054;margin-bottom: 16px;border-radius: 6px;">\
                                        <a class="link" href="#" target="_blank">'+title+'</a>\
                                    </div>\
                                </div>\
                                <form id="join_form">\
                                    <div class="form-group">\
                                        <label for="Shortcode">Enter unique shortcode name: <i title="Enter unique name for your shortcode without spaces like: myShortcode" class="fa fa-question-circle"></i></label>\
                                        <input '+disable_textbox+' type="text" name="Shortcode" id="Shortcode" value="'+edit_shortcode_unique_name+'" required class="form-control" placeholder="eg. '+shortcode_name+'">\
                                    </div>\
                                    <div class="form-group">\
                                        <label for="URL">Enter the affiliate link: <i title="Here enter plain link to the product, you don\'t have to enter whole <a> tag just link!" class="fa fa-question-circle"></i></label>\
                                        <input type="text" name="URL" id="URL" value="'+aff_link+'" placeholder="#" required class="form-control">\
                                    </div>\
                                    <div class="form-group">\
                                        <label for="Title">Enter Shortcode Title: <i title="Here enter the name of Product that you would like to be displayed on site" class="fa fa-question-circle"></i> </label>\
                                        <input type="text" name="Title" id="Title" value="'+edit_shortcode_title+'" required class="form-control" placeholder="'+title+'">\
                                    </div>\
                                    <div class="form-group">\
                                        <label for="Follow">Check this if you like the URL to open in new window:</label>\
                                        <input data-size="small" data-toggle="toggle" style="margin-left: 4px;" type="checkbox" name="Follow" id="Follow" value="1" '+do_follow+'>\
                                    </div>\
                                    <div class="form-group">\
                                        <label for="NoFollow">NOFOLLOW Link? : <i title="Check this so that link that is created get \'nofollow\' atribute so it will instruct some search engines that the hyperlink should not influence the ranking of the link\'s target in the search engine\'s index." class="fa fa-question-circle"></i></label><br>\
                                        <input data-size="small" data-toggle="toggle" style="margin-left: 4px;" type="checkbox" name="NoFollow" id="NoFollow" value="1" '+nofollow+'>\
                                    </div>\
                                    <div class="form-group">\
                                        <label for="Mask">Mask this URL?:</label><br>\
                                        <input data-size="small" data-toggle="toggle" style="margin-left: 4px;" type="checkbox" name="Mask" id="Mask" value="1" '+mask+'/>\
                                    </div>\
                                    <div class="form-group">\
                                        <button data-id="'+id+'" data-action="'+is_edit+'" data-group="'+group+'" style="margin-top: 50px" type="submit" class="button button-primary" id=""><i class="fa fa-search"></i> '+edit_button_text+'</button>\
                                    </div>\
                                </form>\
                            </div>\
                            </div>\
                        </div>\
                    </div>\
                ');

            $(document).on('change keyup paste', '#join_form #URL', function(){

                $('.join_link_preview .link').html($('#join_form #Title').val());
                $('.join_link_preview .link').attr('href',$(this).val());

            });

            $(document).on('change keyup paste', '#join_form #Title', function(){

                $('.join_link_preview .link').html($(this).val());
                $('.join_link_preview .link').attr('href',$('#join_form #URL').val());

            });

            $('#join_form #URL').trigger('change');
        },
        remove_shortcode: function(){
            $(document).on('click', '#remove_shortcode', function(){

                var id = $(this).attr('data-id');

                jQuery.fancybox('<div class="white_row_wrapper  blue_lightbox" id="confirmation_blue">\
                                            <div class="conwrapper"><div class="modal-body">\
                                                <div class="lightbox_content">\
                                                    <div class="alert alert-warning" style="text-align: center;margin: 0;" role="alert">\
                                                        <i class="fa fa-warning"></i>\
                                                            Are you sure? <br>\
                                                            <div style="margin-top: 25px;text-align: center;">\
                                                                <button data-id="'+id+'" style="margin-right: 8px;" class="btn btn-warning" id="remove_shortcode_confirm"><i class="fa fa-trash"></i> Yes</button>\
                                                                <button class="btn btn-danger" id="cancel_s"><i class="fa fa-close"></i> Cancel</button>\
                                                            </div>\
                                                    </div>\
                                                </div>\
                                            </div>\
                                        </div>');
            });

            $(document).on('click', '#remove_shortcode_confirm', function(){

                var id = $(this).attr('data-id');

                if(id == ''){
                    alert('Id field cannot be empty!');
                    return;
                }

                var data = {
                    action: 'affiliate_remove_shortcode',
                    id: id
                };

                jQuery.post("/wp-admin/admin-post.php", data)
                    .done(function(d){
                        functions.get_shortcode_manager();
                        jQuery.fancybox.close();
                    }, 'json');
            });

            $(document).on('click', '#cancel_s', function(){
                jQuery.fancybox.close();
            });
        },
        edit_shortcode: function(){

            $(document).on('click','#edit_shortcode',function(){
                var button = $(this);
                var button_i = $(this).find('i');

                var id = $(this).attr('data-id');

                if(typeof(id) == "undefined" ){
                    alert('ID is missing!');
                    return false;
                }

                button.prop('disabled', true);
                button_i.removeClass().addClass('fa fa-refresh fa-spin');

                var data = {
                    id:     id,
                    action: 'affiliate_shortcodes'
                };

                jQuery.post("/wp-admin/admin-post.php", data)
                    .done(function(d){
                        d = JSON.parse(d);

                        functions.make_join_fancybox(d.Group, d.URL, d.Title, d.Shortcode, d.Follow, true, d.id, d.Nofollow, d.Mask);

                        button.prop('disabled', false);
                        button_i.removeClass().addClass('fa fa-wrench');

                    }, 'json');
            });
        },
        refresh_clickbank_table: function(){
            $(document).on('click', '#refresh_clickbank_table', function(){

                functions.get_clickbank_table();

            });

        },
        clickbank_custom_filters: function(){
            $('#custom_clickbank_filters').submit(function(e){

                e.preventDefault();

                $(this).find('button[type="submit"]').prop('disabled', true);
                $(this).find('button[type="submit"] i').addClass('fa-spin');
                functions.get_clickbank_table();
            });
        },
        add_new_shortcode: function(){
            $(document).on('click', '#add_new_shortcode', function(){

                functions.make_join_fancybox('Custom', '', '', 'my_custom_shortcode', 0, false, '', 0, 0);

            });

        }
    }


})(jQuery);