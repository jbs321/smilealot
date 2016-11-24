(function ($) {

    $(document).ready(function(){

        $(document).on('click', '#stylefee', function(){
            if ($(this).is(':checked')) {
                $('#instructions').removeAttr('disabled');
            } else {
                $('#instructions').attr('disabled', 'disabled');
            }
        });

        functions.submit_iNeedArticles();
        functions.get_iNeedArticles_table();
        functions.refresh_articles_table();
        functions.remove_article_modal();
        functions.view_and_insert();
        functions.recheck_all();
        functions.recheck_single();
        functions.existing_page();
        functions.existing_post();

    });


    var functions = {
        submit_iNeedArticles: function(){
            $('#submit_iNeedArticles_form').submit(function(e){
                e.preventDefault();

                // Check if style fee is checked and no instructions have been set
                if ($('#stylefee').is(':checked')) {
                    if ($('#instructions').val() == '') {
                        alert('Please insert some instructions if you enabled Style fee.');
                        return;
                    }
                }

                var button = $(this).find('button[type="submit"]');

                var data = $(this).serialize();

                button.prop('disabled', true);
                jQuery.post(ajaxurl, data).done(function(d){
                    if (d.status == "ERROR") {
                        alert(d.message);
                    } else {
                        alert(d.message);
                        functions.get_iNeedArticles_table();
                    }
                    button.prop('disabled', false);
                });
            });
        },
        get_iNeedArticles_table: function(){
            var table = $('#iNeedArticles_table').DataTable({
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
                "aaSorting": [[1, 'desc']],
                "aoColumns": [
                    {
                        "bSearchable": true,
                        "sClass": "text-center",
                        "bSortable": false,
                        "mData": 'article',
                        "mRender": function(data, type, row){
                            var html = '';
                            if (row['status'] == "2") {
                                html += '<button type="button" class="btn btn-primary" id="view_article" title="View/Insert article!" data-aid="'+data+'"><i class="fa fa-file-word-o"></i></button> ';
                            }

                            html += '<button type="button" class="btn btn-success" id="recheck_article_single" title="Check article status!" data-aid="'+data+'" data-bid="'+row['batch']+'"><i class="fa fa-refresh"></i></button> ';

                            html += '<button type="button" class="btn btn-danger" id="remove_article" title="Remove article!" data-aid="'+data+'"><i class="fa fa-trash-o"></i></button>';
                            return html;
                        },
                        "asSorting": ["desc", "asc"]
                    },
                    {
                        "bSearchable": true,
                        "sClass": "text-center",
                        "bSortable": true,
                        "mData": 'batch',
                        "mRender": function(data, type, row){
                            return data;
                        },
                        "asSorting": ["desc", "asc"]
                    },
                    {
                        "bSearchable": true,
                        "sClass": "text-center",
                        "bSortable": false,
                        "mData": 'article',
                        "mRender": function(data, type, row){
                            return data;
                        },
                        "asSorting": ["desc", "asc"]
                    },
                    {
                        "bSearchable": true,
                        "sClass": "text-center",
                        "bSortable": true,
                        "mData": 'keywords',
                        "mRender": function(data, type, row){
                            return data;
                        },
                        "asSorting": ["desc", "asc"]
                    },
                    {
                        "bSearchable": true,
                        "sClass": "text-center",
                        "bSortable": true,
                        "mData": 'status',
                        "mRender": function(data, type, row){
                            var html = '';
                            if (data == "0") {
                                html += '<button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Article unassigned!"><i class="fa fa-circle"></i></button>';
                            }
                            if (data == "1.5") {
                                html += '<button type="button" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Article being written!"><i class="fa fa-circle-o"></i></button>';
                            }

                            if (data == "2") {
                                html += '<button type="button" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Article completed!"><i class="fa fa-check-circle-o"></i></button>';
                            }
                            return html;
                        },
                        "asSorting": ["desc", "asc"]
                    }

                ],
                "fnInitComplete": function(settings, json) {
                    $('.datatable-actions').html(
                        '<button style="margin-left:10px" type="button" class="btn btn-primary btn-xs pull-right" id="refresh_articles_table"><i class="fa fa-refresh"></i> Refresh Table</button>' +
                        '<button style="margin-left:10px" type="button" class="btn btn-primary btn-xs pull-right" id="recheck_articles_table"><i class="fa fa-arrow-circle-down"></i> Recheck all Articles</button>'
                    )
                },
                "fnServerParams": function (aoData) {
                    var filters = $('#custom_clickbank_filters').serializeArray();
                    for(var i = 0; i < filters.length; i++) {
                        var filter = filters[i];
                        aoData.push(filter);
                    }
                    var f = {
                        name: "action",
                        value: "datatable_iNeedArticles"
                    };
                    aoData.push(f);
                },
                "fnCreatedRow": function( nRow, aData, iDataIndex ) {

                }
            });
        },
        refresh_articles_table: function(){
            $(document).on('click', '#refresh_articles_table', function(){

                functions.get_iNeedArticles_table();

            });

        },
        remove_article_modal: function() {
            var article_id = null;

            $(document).on('click', '#remove_article', function () {
                $('#remove_article_modal').modal('show');
                article_id = $(this).data('aid');
            });

            $(document).on('click', '#remove_this_article', function () {

                var button = $(this);
                button.prop('disabled', true);

                var data = {
                    action: 'gkty_remove_iNeedArticles',
                    article: article_id
                };

                $.post(ajaxurl, data).done(function(dData){
                    button.prop('disabled', false);
                    if (dData.status == "OK") {
                        functions.get_iNeedArticles_table();
                        $('#remove_article_modal').modal('hide');
                    }else{
                        $('#remove_article_modal').modal('hide');
                        alert('There was some error, please try again!');
                    }
                })
            });
        },
        view_and_insert: function() {
            $(document).on('click', '#view_article', function() {

                $('#spintax').bootstrapToggle('off');

                var button = $(this);
                button.prop('disabled', true);

                var data = {
                    action: 'gkty_load_iNeedArticles',
                    article: $(this).data('aid')
                };

                $.post(ajaxurl, data).done(function(dData){
                    button.prop('disabled', false);

                    $('#view_article_modal').modal('show');
                    $('#title_a').val(dData.data.title);
                    $('#body_a').html(dData.data.body);

                    $(document).on("click", "div.form-group.spintax > div", function(){
                        if($("#spintax").prop("checked")) {
                            $('#body_a').html(dData.data.spun);
                        }else{
                            $('#body_a').html(dData.data.body);
                        }
                    });

                    $(document).on("click", "#create_post", function(){
                        $('#view_article_modal .modal-footer .btn').prop('disabled', true);

                        var data = {
                            action: 'gkty_create_iNeedArticles',
                            post_type: 'post',
                            post_title: $('#title_a').val(),
                            post_content: $('#body_a').val()
                        };

                        $.post(ajaxurl, data).done(function(dDataP) {
                            $('#view_article_modal .modal-footer .btn').prop('disabled', false);
                            if(dDataP.result == "success"){
                                $('#view_article_modal').modal('hide');
                                alert('Post successfully created!');
                            }else{
                                $('#view_article_modal').modal('hide');
                                alert('Unexpected error oared, please try again!');
                            }
                        });
                    });

                    $(document).on("click", "#create_page", function(){
                        $('#view_article_modal .modal-footer .btn').prop('disabled', true);

                        var data = {
                            action: 'gkty_create_iNeedArticles',
                            post_type: 'page',
                            post_title: $('#title_a').val(),
                            post_content: $('#body_a').val()
                        };

                        $.post(ajaxurl, data).done(function(dDataP) {
                            $('#view_article_modal .modal-footer .btn').prop('disabled', false);
                            if(dDataP.result == "success"){
                                $('#view_article_modal').modal('hide');
                                alert('Page successfully created!');
                            }else{
                                $('#view_article_modal').modal('hide');
                                alert('Unexpected error oared, please try again!');
                            }
                        });
                    });
                })
            })
        },
        recheck_all: function(){
            $(document).on('click', '#recheck_articles_table', function(){

                $('#pr-progress-bar-articles').removeClass('hide');

                var data = {
                    action: 'gkty_recheck_articles_table'
                };

                $.post(ajaxurl, data).done(function(dData){
                    $('#pr-progress-bar-articles').addClass('hide');
                    functions.get_iNeedArticles_table();
                })
            });
        },
        recheck_single: function() {
            $(document).on('click', '#recheck_article_single', function() {

                $('#pr-progress-bar-articles').removeClass('hide');

                var data = {
                    action: 'gkty_recheck_single_article',
                    article: $(this).data('aid'),
                    batch: $(this).data('bid')
                };

                $.post(ajaxurl, data).done(function(dData){
                    $('#pr-progress-bar-articles').addClass('hide');
                    if (dData.status == "OK") {
                        alert(dData.message);
                        functions.get_iNeedArticles_table();
                    }else{
                        alert(dData.message);
                    }
                })
            })
        },
        existing_page: function() {
            $(document).on('click', '#edit_page', function(){
                var append = false;

                $('#view_article_modal').modal('hide');
                $('#body_a1').val($('#body_a').val());
                $('#view_pages_modal').modal('show');

                $(document).on('click', '#a_create_page', function() {

                    $('#a_create_page').prop('disabled', true);

                    if($("#append_page").prop("checked")) {
                        append = true;
                    }else{
                        append = false;
                    }

                    var data = {
                        action: 'gkty_append_post_page',
                        post_type: 'page',
                        post_id: $('.select_page').val(),
                        append: append,
                        post_content: $('#body_a').val()
                    };

                    $.post(ajaxurl, data).done(function(dData) {
                        alert('Page successfully updated!');
                        $('#a_create_page').prop('disabled', false);
                        $('#view_posts_modal').modal('hide');
                    });

                });

            })
        },
        existing_post: function() {
            $(document).on('click', '#edit_post', function(){
                var append = false;

                $('#view_article_modal').modal('hide');
                $('#body_a2').val($('#body_a').val());
                $('#view_posts_modal').modal('show');

                $(document).on('click', '#a_create_post', function() {
                    $('#a_create_post').prop('disabled', true);

                    if($("#append_post").prop("checked")) {
                        append = true;
                    }else{
                        append = false;
                    }

                    var data = {
                        action: 'gkty_append_post_page',
                        post_type: 'post',
                        post_id: $('.select_post').val(),
                        append: append,
                        post_content: $('#body_a2').val()
                    };

                    $.post(ajaxurl, data).done(function(dData) {
                        alert('Post successfully updated!');
                        $('#a_create_post').prop('disabled', false);
                        $('#view_posts_modal').modal('hide');
                    });

                });
            })
        }
    }


})(jQuery);