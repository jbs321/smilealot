
var processing_flag = false;
var group_processing_flag = false;
var processing_action_queue = [];
var processing_value_queue = [];
var total_tasks_count = 0;
var current_tasks_count = 0;
var create_post_result_queue = [];

function isUint32(n) {
    return +n === n && !(n % 1) && n < 0x100000000 && n >= 0;
}

jQuery(document).on('click', '#scan_for_proxies', function(){

    var websites = jQuery('#ProxyListWebsites').val().split('\n').join(',');
    var button = jQuery(this);
    button.prop('disabled', true);

    var data = {
        'action': 'gkty_proxy_scraper',
        'websites': websites
    };
    jQuery.post(ajaxurl, data)
        .done(function(d){
            button.prop('disabled', false);

            if(typeof (d) != 'undifined'){
                if(d.length > 1){

                    var html = '';
                    for(var i = 0; i < d.length; i++){
                        html += d[i] + "\n";
                    }
                    jQuery('#ProxyList').html(html);
                    jQuery('#custom_proxy_list').trigger('click');
                }else{
                    alert('No proxies found!');
                }
            }

        }, 'json');


});

function is_float(mixed_var) {
    //  discuss at: http://phpjs.org/functions/is_float/
    // original by: Paulo Freitas
    // bugfixed by: Brett Zamir (http://brett-zamir.me)
    // improved by: WebDevHobo (http://webdevhobo.blogspot.com/)
    // improved by: Rafał Kukawski (http://blog.kukawski.pl)
    //        note: 1.0 is simplified to 1 before it can be accessed by the function, this makes
    //        note: it different from the PHP implementation. We can't fix this unfortunately.
    //   example 1: is_float(186.31);
    //   returns 1: true

    return +mixed_var === mixed_var && (!isFinite(mixed_var) || !! (mixed_var % 1));
}

function trash_keyword_data(keyword_id) {
    var btn = jQuery('#keyword_trash_btn_' + keyword_id);
    var answer = confirm("Are you sure to delete this keyword?");
    if (!answer) {
        return false;
    }
    var td = btn.parent();
    var tr = td.parent();
    var data = {
        action : 'gkty_delete_keyword',
        keyword_id: keyword_id
    };
    jQuery.post(ajax_object.ajax_url, data, function(respond) {
        tr.css("background-color","#FF3700");
        tr.fadeOut(400, function(){
            tr.remove();
        });
    });
    return false;
}

function refresh_keyword_data(keyword_id) {
    if (processing_flag == true || group_processing_flag == true) {
        alert('Already on processing...');
        return false;
    }
    processing_flag = true;
    group_processing_flag = true;
    processing_action_queue = [];
    processing_value_queue = [];
    total_tasks_count = 0;
    current_tasks_count = 0;
    jQuery('#pr-progress-bar-area').removeClass('hide');
    var keyword_name = jQuery('td#keyword_name_' + keyword_id + ' div').text();
    if (keyword_name != '') {
        processing_action_queue.push('get_keyword_data');
        processing_value_queue.push(keyword_id);
        total_tasks_count++;
    }

    processing_flag = false;
    if (total_tasks_count <= 0) {
        group_processing_flag = false;
        alert('Please fill the keyword name.');
        return false;
    }
    showProgressDlg();
    return false;
}

function addKeyword(group_id) {
    var data = {
        action : 'gkty_add_keyword',
        group_id: group_id
    }
    if (processing_flag == true) {
        alert('Already in progressing...');
        return false;
    }
    processing_flag = true;
    jQuery.post(ajax_object.ajax_url, data, function(respond) {
        processing_flag = false;
        if (respond) {
            var ret_obj = jQuery.parseJSON(respond);
            if (ret_obj.result == 'success') {
                var tbody_ele = jQuery('#group_keyword_table_' + group_id).find('tbody');
                var row_html = '<tr id="group_keyword_row_' + ret_obj.k_id + '" class="group_keyword_row" data-changed="false" data-scraped="false">';
                row_html += '<td id="keyword_name_' + ret_obj.k_id + '" class="keyword_field" onclick="return onSetFocusEditor(this);"><div contenteditable="true" style="width: 100%;height: 12px;height: auto;" onkeyup="onKeywordChange(this);"></div></td>';
                row_html += '<td id="keyword_volumn_' + ret_obj.k_id + '" class="keyword_volumn keyword_field " onclick="return onSetFocusEditor(this);"><div contenteditable="true" style="width: 100%;height: 12px;height: auto;" onkeyup="onKeywordChange(this);" onblur="onVolumnBlue(this);"></div></td>';
                row_html += '<td id="keyword_cpc_' + ret_obj.k_id + '" class="keyword_cpc keyword_field " onclick="return onSetFocusEditor(this);"><div contenteditable="true" style="width: 100%;height: 12px;height: auto;" onkeyup="onKeywordChange(this);" onblur="onCPCBlue(this);"></div></td>';
                row_html += '<td id="keyword_inbroad_' + ret_obj.k_id + '" class=""></td>';
                row_html += '<td id="keyword_inparse_' + ret_obj.k_id + '" class=""></td>';
                row_html += '<td id="keyword_intitle_' + ret_obj.k_id + '" class=""></td>';
                row_html += '<td id="keyword_inurl_' + ret_obj.k_id + '" class=""></td>';
                row_html += '<td class="group_field text-left gkt_title">';
                row_html += '<button id="keyword_trash_btn_' + ret_obj.k_id + '" class="btn btn-link btn-keyword" onclick="return trash_keyword_data(' + ret_obj.k_id + ')"><i class="fa fa-times"></i></button>';
                row_html += '<button id="keyword_refresh_btn_' + ret_obj.k_id + '" class="btn btn-link btn-keyword" onclick="return refresh_keyword_data(' + ret_obj.k_id + ')"><i class="fa fa-refresh"></i></button>';
                row_html += '<input type="checkbox" data-id="' + ret_obj.k_id + '" class="keyword_ids"></td>';
                /*row_html += '</td>' +
                    '<td><input type="checkbox" data-id="' + ret_obj.k_id + '" class="keyword_ids"></td>' +
                    '</tr>';*/
                tbody_ele.append(row_html);
            }
        }
    });
    return true;
}

function addGroup(project_id) {
    var data = {
        action : 'gkty_add_group',
        project_id: project_id
    }
    if (processing_flag == true) {
        alert('Already in progressing...');
        return false;
    }
    processing_flag = true;
    jQuery.post(ajax_object.ajax_url, data, function(respond) {
        processing_flag = false;
        if (respond) {
            var ret_obj = jQuery.parseJSON(respond);
            if (ret_obj.result == 'success') {
                var group_contents_area_left_ele = jQuery('#groups_contents_area_left');
                var group_contents_area_right_ele = jQuery('#groups_contents_area_right');
                var group_left_height = group_contents_area_left_ele.height();
                var group_right_height = group_contents_area_right_ele.height();

                var new_group_html = '<div class="panel panel-default group-' + ret_obj.group_id + '"><div class="panel-body"><div class="row"><div class="col-md-9">' +
                    '<div class="group_main_area_' + ret_obj.group_id + '">' +
                    '<table id="group_data_table_' + ret_obj.group_id + '" class="table group_data_table table-bordered" data-changed="false" data-scraped="false">' +
                    '<colgroup><col style="width: 120px;"><col></colgroup><tbody><tr><td class="group_field gkt_title">Group</td>' +
                    '<td id="group_name_' + ret_obj.group_id + '" class="group_field" onclick="return onSetFocusEditor(this);">' +
                    '<div contenteditable="true" style="width: 100%;height: 12px;height: auto;" onkeyup="onGroupChange(this);"></div>' +
                    '</td></tr><tr><td class="group_field gkt_title">Title</td>' +
                    '<td id="group_title_' + ret_obj.group_id + '" class="group_field" onclick="return onSetFocusEditor(this);">' +
                    '<div contenteditable="true" style="width: 100%;height: 12px;height: auto;" onkeyup="onGroupChange(this);"></div>' +
                    '</td></tr><tr><td class="group_field gkt_title">PAGE URL</td>' +
                    '<td id="group_url_' + ret_obj.group_id + '" class="group_field group_url" onclick="return onSetFocusEditor(this);">' +
                    '<div placeholder="eg. mypagename" contenteditable="true" style="width: 100%;height: 12px;height: auto;" onkeyup="onGroupChange(this);" onblur="onGroupURLBlur(this);"></div>' +
                    '</td></tr><tr><td class="group_field gkt_title">DESC</td>' +
                    '<td id="group_description_' + ret_obj.group_id + '" class="group_field" onclick="return onSetFocusEditor(this);">' +
                    '<div contenteditable="true" style="width: 100%;height: 12px;height: auto;" onkeyup="onGroupChange(this);"></div>' +
                    '</td></tr><tr><td class="group_field gkt_title">H1</td>' +
                    '<td id="group_h1_' + ret_obj.group_id + '" class="group_field" onclick="return onSetFocusEditor(this);">' +
                    '<div contenteditable="true" style="width: 100%;height: 12px;height: auto;" onkeyup="onGroupChange(this);"></div>' +
                    '</td></tr></tbody></table></div></div><div class="col-md-3 menu_button_area"><ul><li>' +
                    '<button class="add-new-h2" onclick="return getScrape(' + ret_obj.group_id + '); ">Get Keyword Data</button>' +
                    '</li><li>' +
                    '<button id="keyword_add_btn_' + ret_obj.group_id + '" class="add-new-h2" onclick="return addKeyword(' + ret_obj.group_id + ');">Add Keyword</button>' +
                    '</li><li>' +
                    '<button class="add-new-h2" onclick="return deleteKeywords(' + ret_obj.group_id + '); ">Delete Keywords</button>' +
                    '</li><li>' +
                    '<button class="add-new-h2" onclick="return createPage(' + ret_obj.group_id + '); ">Create Page/Post</button>' +
                    '</li><li>' +
                    '<button class="add-new-h2" onclick="return goToPage(' + ret_obj.group_id + ');">Go to Page/Post Edit</button>' +
                    '</li></ul></div></div><div class="row"><div class="col-md-12">' +
                    '<table id="group_keyword_table_' + ret_obj.group_id + '" class="table group_keyword_table table-bordered">' +
                    '<colgroup><col style="width: 150px;"><col style="width: 60px;"><col style="width: 60px;"><col style="width: 60px;"><col style="width: 60px;"><col style="width: 60px;"><col style="width: 52px;"><col style="width: 30px;"></colgroup><thead>' +
                    '<tr><th class="gkt_title" data-sort="name">Keyword <i class="fa fa-sort"></i></th><th class="gkt_title" data-sort="volume">Volume <i class="fa fa-sort"></i></th><th class="gkt_title" data-sort="cpc">CPC ($) <i class="fa fa-sort"></i></th><th class="gkt_title" data-sort="broad">Broad <i class="fa fa-sort"></i></th><th class="gkt_title" data-sort="phrase">Phrase <i class="fa fa-sort"></i></th><th class="gkt_title" data-sort="intitle">inTITLE <i class="fa fa-sort"></i></th><th class="gkt_title" data-sort="inurl">inURL <i class="fa fa-sort"></i></th><th class="gkt_title" title="Select/Deselect All">Action <img src="/wp-content/plugins/project-supremacy/css/select-all.png" class="select-all"/></th></tr>' +
                    '</thead><tbody>';

                ret_obj.keywords.forEach(function(keyword_entry) {
                    new_group_html += '<tr id="group_keyword_row_' + keyword_entry + '" class="group_keyword_row" data-changed="false" data-scraped="false">' +
                    '<td id="keyword_name_' + keyword_entry + '" class="keyword_field" onclick="return onSetFocusEditor(this);"><div contenteditable="true" style="width: 100%;height: 12px;height: auto;" onkeyup="onKeywordChange(this);"></div></td>' +
                    '<td id="keyword_volumn_' + keyword_entry + '" class="keyword_volumn keyword_field" onclick="return onSetFocusEditor(this);"><div contenteditable="true" style="width: 100%;height: 12px;height: auto;" onkeyup="onKeywordChange(this);" onblur="onVolumnBlue(this);"></div></td>' +
                    '<td id="keyword_cpc_' + keyword_entry + '" class="keyword_cpc keyword_field" onclick="return onSetFocusEditor(this);"><div contenteditable="true" style="width: 100%;height: 12px;height: auto;" onkeyup="onKeywordChange(this);" onblur="onCPCBlue(this);"></div></td>' +
                    '<td id="keyword_inbroad_' + keyword_entry + '"></td>' +
                    '<td id="keyword_inparse_' + keyword_entry + '" ></td>' +
                    '<td id="keyword_intitle_' + keyword_entry + '""></td>' +
                    '<td id="keyword_inurl_' + keyword_entry + '"></td>' +
                    '<td class="group_field text-left gkt_title">' +
                    '<button id="keyword_trash_btn_' + keyword_entry + '" class="btn btn-link btn-keyword" onclick="return trash_keyword_data(' + keyword_entry + ')"><i class="fa fa-times"></i></button>' +
                    '<button id="keyword_refresh_btn_' + keyword_entry + '" class="btn btn-link btn-keyword" onclick="return refresh_keyword_data(' + keyword_entry + ')"><i class="fa fa-refresh"></i></button>' +
                    '<input type="checkbox" data-id="' + keyword_entry + '" class="keyword_ids"></td>';
                });
                new_group_html += '</tbody></table></div></div>' +
                    '<div class="clearfix"></div>' +
                    '<div class="row">' +
                    '' +
                    '<div class="button_row pull-right">' +
                    '<button class="add-new-h2" onclick="return changeGroup(' + ret_obj.group_id + ', '+project_id+');">Change Keyword Group</button>' +
                    '</div>' +
                    '' +
                    '<div class="button_row pull-right">' +
                    '<button class="add-new-h2" onclick="return deleteGroup(' + ret_obj.group_id + '); ">Delete Group</button>' +
                    '</div>' +
                    '' +
                    '<div class="button_row pull-right">' +
                    '<button class="add-new-h2" onclick="return saveGroup(' + ret_obj.group_id + ');">Save Group</button>' +
                    '</div>' +
                    '' +
                    '<div class="clearfix"></div>' +
                    '</div>';
                if (group_left_height <= group_right_height) {
                    group_contents_area_left_ele.append(new_group_html);
                }
                else {
                    group_contents_area_right_ele.append(new_group_html);
                }

                document.getElementById("group_data_table_" + ret_obj.group_id).scrollIntoView();
            }
        }
    });
}

function onKeywordChange(obj) {
    var td = jQuery(obj).parent();
    var tr = td.parent();
    tr.attr('data-changed', 'true');
    tr.attr('data-scraped', 'false');
}

function onGroupChange(obj) {
    var table_ele = jQuery(obj).parent().parent().parent().parent();
    table_ele.attr('data-changed', 'true');
}

function onGroupURLBlur(obj) {
    var url_val = jQuery(obj).html();
    //var url_val = jQuery(obj).html().trim();
    //jQuery(obj).html(encodeURIComponent(url_val).replace(/%20/g,'-'));
}

function onVolumnBlue(obj) {
    if (jQuery(obj).html() != '') {
        var volumn = parseInt(jQuery(obj).html());
        if (volumn <= 0 || isNaN(volumn)) {
            volumn = 0;
            jQuery(obj).html('');
           // jQuery(obj).focus();
            return;
        }
    }
}

function onCPCBlue(obj) {
    if (jQuery(obj).html() != '') {
        var cpc = parseFloat(jQuery(obj).html());
        if (cpc <= 0 || isNaN(cpc)) {
            cpc = 0;
            jQuery(obj).html('');
           // jQuery(obj).focus();
            return;
        }
    }
}

function updateQueueForGroup(group_id) {
    var group_table_ele = jQuery('#group_data_table_' + group_id);
    if (group_table_ele.attr('data-changed') != "false") {
        processing_action_queue.push('save_group');
        processing_value_queue.push(group_id);
        total_tasks_count++;
    }

    var group_keyword_table_ele = jQuery('#group_keyword_table_' + group_id);
    var keyword_rows = group_keyword_table_ele.find('.group_keyword_row');
    var i = 0, is_changed, keyword_id;
    for (i = 0; i < keyword_rows.length; i++) {
        is_changed = jQuery(keyword_rows[i]).attr('data-changed');
        if (is_changed != 'false') {
            keyword_id = jQuery(keyword_rows[i]).attr('id').replace('group_keyword_row_', '');
            processing_action_queue.push('save_keyword');
            processing_value_queue.push(keyword_id);
            total_tasks_count++;
        }
    }
}

function updateQueueForCreatePageAndPost(type, group_id) {

    var group_title = jQuery('#group_title_' + group_id + ' div').text();
    var group_url = jQuery('#group_url_' + group_id + ' div').text();
    var group_h1 = jQuery('#group_h1_' + group_id + ' div').text();

    if (group_title == '' || group_url == '' || group_h1 == '') {
        create_post_result_queue.push({
            group_id: group_id,
            action: type,
            result: 'Failure',
            description: 'Please full-fill Title, URL, H1 of this group to create ' + type
        });
        return false;
    }
    if (type == 'page') {
        processing_action_queue.push('create_page');
        processing_value_queue.push(group_id);
    }
    else if (type == 'post') {
        processing_action_queue.push('create_post');
        processing_value_queue.push(group_id);
    }

    total_tasks_count++;
    return true;
}

function saveGroup(group_id) {
    window.onbeforeunload = null;
    if (processing_flag == true || group_processing_flag == true) {
        alert('Already on processing...');
        return false;
    }
    processing_flag = true;
    group_processing_flag = true;
    processing_action_queue = [];
    processing_value_queue = [];
    total_tasks_count = 0;
    current_tasks_count = 0;
    updateQueueForGroup(group_id);
    processing_flag = false;
    if (total_tasks_count <= 0) {
        alert('Already saved data');
        return false;
    }
    showProgressDlg();
    return false;
}

function createPage(group_id) {
    if (processing_flag == true || group_processing_flag == true) {
        alert('Already on processing...');
        return false;
    }
    var desc = jQuery('#group_description_' + group_id);
    if (desc.text() == '') {
        alert('Description must not be empty in order to create a Page/Post! Please fill in your description!');
        desc.trigger('click');
        return false;
    }

    jQuery.fancybox(
        '<div class="white_row_wrapper  blue_lightbox" id="confirmation_blue">\
  <div class="conwrapper"><div class="modal-body">\
  <div class="lightbox_content">\
  <p><button style="width: 150px;" type="button" class="btn btn-primary" id="ps_create_page_btn" data-group-id="' + group_id + '">Create Page</button></p>\
  <p><button style="width: 150px;" type="button" class="btn btn-primary" id="ps_create_post_btn" data-group-id="' + group_id + '">Create Post</button></p>\
  </div>\
  </div>\
  </div>\
  </div>');
}

function createPageAndPost(type, group_id) {
    if (processing_flag == true || group_processing_flag == true) {
     alert('Already on processing...');
     return false;
     }
     processing_flag = true;
     group_processing_flag = true;
     processing_action_queue = [];
     processing_value_queue = [];
     total_tasks_count = 0;
     current_tasks_count = 0;
     updateQueueForCreatePageAndPost(type, group_id);
     processing_flag = false;
     if (total_tasks_count <= 0) {
        displayCreatePostResultPage();
        return false;
     }
     showProgressDlg();
     return false;
}

function displayCreatePostResultPage() {
    if (create_post_result_queue.length > 0) {
        var content_html = '<table class="table table-border">' +
            '<colgroup><col style="width: 200px;"><col style="width: 100px;"><col> </colgroup>' +
            '<thead>' +
            '<tr><th>Group (#UID : Name)</th><th>Action</th><th>Result</th></tr>' +
            '</thead>' +
            '<tbody>';
        var group_name, tr_class;
        create_post_result_queue.sort(function(a,b) {return a['group_id'] - b['group_id']});
        jQuery.each(create_post_result_queue, function(index, val) {
            group_name = jQuery('#group_name_' + val['group_id'] + ' div').text();
            if (val['result'] == 'Success') {
                tr_class = 'success';
            }
            else {
                tr_class = 'danger';
            }
            content_html += '<tr class="' + tr_class + '">' +
            '<td>#' + val['group_id']  + ':' + group_name + '</td>' +
            '<td>Create ' + val['action'] + '</td>' +
            '<td>' + val['result'] + ' : <a target="_blank" href="' + val['description'] + '">' + val['description'] + '</a></td></tr>';
        });
        content_html += '</tbody></table>';

        jQuery.fancybox(
            '<div class="white_row_wrapper  blue_lightbox" id="confirmation_blue">\
            <div class="title_bar text-center"><h3>Create Page/Post Result:</h3></div>\
      <div class="conwrapper"><div class="modal-body">\
      <div class="lightbox_content">' + content_html + '\
  </div>\
  </div>\
  </div>\
  </div>');
        create_post_result_queue = [];
    }
}

function createPageAndPostAll(project_id) {
    if (processing_flag == true || group_processing_flag == true) {
        alert('Already on processing...');
        return false;
    }
    var group_tables = jQuery('#groups_contents_area').find('table.group_data_table');
    var j = 0;
    var content_html = '<table class="table table-border">' +
        '<colgroup><col style="width: 200px;"><col style="width: 100px;"><col style="width: 100px;"></colgroup>' +
        '<thead>' +
        '<tr><th>Group (#UID : Name)</th><th>Create Post</th><th>Create Page</th></tr>' +
        '</thead>' +
        '<tbody>';
    var group_id_array = [];
    for (j = 0; j < group_tables.length; j++) {
        var group_table_id = parseInt(jQuery(group_tables[j]).attr('id').replace('group_data_table_',''));
        group_id_array.push(group_table_id);
    }
    group_id_array.sort(function(a, b) {return a-b});
    for (j = 0; j < group_id_array.length; j++) {
        var group_table_id = group_id_array[j];
        var group_name = jQuery('#group_name_' + group_table_id + ' div').text();
        content_html += '<tr>' +
        '<td>#' + group_table_id + ' : ' + group_name + '</td>' +
        '<td class="text-center"><input type="radio" name="group_method_radio_' + group_table_id + '" value="post"></td>' +
        '<td class="text-center"><input type="radio" name="group_method_radio_' + group_table_id + '" value="page" checked></td></tr>';
    }
    content_html += '</tbody></table>';
    jQuery.fancybox(
        '<div class="white_row_wrapper  blue_lightbox" id="confirmation_blue">\
        <div class="title_bar text-center"><h4>Create All Pages/Posts:</h4></div>\
  <div class="conwrapper"><div class="modal-body">\
  <div class="lightbox_content">' + content_html + '\
  </div>\
  <div class="lightbox_bottom text-right">\
  <button type="button" id="createAllPosts_btn" class="btn btn-primary" style="width: 100px;">OK</button> \
  <button type="button" id="createAllPosts_cancel_btn"  class="btn btn-default" style="width: 100px;">Cancel</button> \
  </div>\
  </div>\
  </div>\
  </div>');
}

function showProgressDlg() {
    jQuery('#progress-area .progress-bar').attr('aria-valuenow', 0);
    jQuery('#progress-area .progress-bar').css('width', '0%');
    jQuery('#span-processing-val').html('0');
    jQuery('#pr-progress-bar-area').removeClass('hide');
    jQuery(document).scrollTop();
}

function hideProgressDlg() {
    jQuery('#progress-area .progress-bar').attr('aria-valuenow', 0);
    jQuery('#progress-area .progress-bar').css('width', '0%');
    jQuery('#span-processing-val').html('0');
    jQuery('#pr-progress-bar-area').addClass('hide');
}

function setProgressDlgVal(cur_val, total_val) {
    var processing_percent = parseInt(cur_val * 100 / total_val);
    jQuery('#progress-area .progress-bar').attr('aria-valuenow', processing_percent);
    jQuery('#progress-area .progress-bar').css('width', processing_percent + '%');
    jQuery('#span-processing-val').html(processing_percent);
}

function getScrape(group_id) {
    if (processing_flag == true || group_processing_flag == true) {
        alert('Already on processing...');
        return false;
    }
    processing_flag = true;
    group_processing_flag = true;
    processing_action_queue = [];
    processing_value_queue = [];
    total_tasks_count = 0;
    current_tasks_count = 0;
    jQuery('#pr-progress-bar-area').removeClass('hide');
    var group_keyword_table_ele = jQuery('#group_keyword_table_' + group_id);
    var keyword_rows = group_keyword_table_ele.find('.group_keyword_row');

    var selected = [];

    keyword_rows.each(function(){
        var checkbox = jQuery(this).find('[type="checkbox"]');
        if (checkbox.is(':checked')) {
            selected.push(this);
        }
    });

    if (selected.length < 1) {
        var i = 0, keyword_id, keyword_name, is_changed, is_scrape;
        for (i = 0; i < keyword_rows.length; i++) {
            keyword_id = jQuery(keyword_rows[i]).attr('id').replace('group_keyword_row_', '');
            keyword_name = jQuery(keyword_rows[i]).find('#keyword_name_' + keyword_id + ' div').text();
            is_changed = jQuery(keyword_rows[i]).attr('data-changed');
            is_scrape = jQuery(keyword_rows[i]).attr('data-scraped');
            if ((keyword_name != '')) {
                processing_action_queue.push('get_keyword_data');
                processing_value_queue.push(keyword_id);
                total_tasks_count++;
            }
        }
    } else {
        var i = 0, keyword_id, keyword_name, is_changed, is_scrape;
        for(i = 0; i < selected.length; i++) {
            keyword_id = jQuery(selected[i]).attr('id').replace('group_keyword_row_', '');
            keyword_name = jQuery(selected[i]).find('#keyword_name_' + keyword_id + ' div').text();
            is_changed = jQuery(selected[i]).attr('data-changed');
            is_scrape = jQuery(selected[i]).attr('data-scraped');
            if ((keyword_name != '')) {
                processing_action_queue.push('get_keyword_data');
                processing_value_queue.push(keyword_id);
                total_tasks_count++;
            }
        }
    }




    processing_flag = false;
    if (total_tasks_count <= 0) {
        group_processing_flag = false;
        alert('No any keywords changed');
        return false;
    }
    showProgressDlg();
    return false;
}

function save_group(group_id) {
    if (processing_flag == true) {
        alert('Already in progressing...');
        return false;
    }

    processing_flag = true;
    var group_name = jQuery('#group_name_' + group_id + ' div').text();
    var group_title = jQuery('#group_title_' + group_id + ' div').text();
    var group_url = jQuery('#group_url_' + group_id + ' div').text();
    var group_description = jQuery('#group_description_' + group_id + ' div').text();
    var group_h1 = jQuery('#group_h1_' + group_id + ' div').text();

    var data = {
        action : 'gkty_save_group',
        group_id : group_id,
        group_name : group_name,
        group_title : group_title,
        group_url : group_url,
        group_description : group_description,
        group_h1 : group_h1
    };
    jQuery.post(ajax_object.ajax_url, data, function(respond) {
        processing_flag = false;
        setProgressDlgVal(current_tasks_count, total_tasks_count);
        if (current_tasks_count >= total_tasks_count) {
            total_tasks_count = 0;
            group_processing_flag = false;
        }
        jQuery('#group_data_table_' + group_id).attr('data-changed', 'false');
    });
}

function create_page(group_id) {
    if (processing_flag == true) {
        alert('Already in progressing...');
        return false;
    }

    processing_flag = true;
    var group_name = jQuery('#group_name_' + group_id + ' div').text();
    var group_title = jQuery('#group_title_' + group_id + ' div').text();
    var group_url = jQuery('#group_url_' + group_id + ' div').text();
    var group_description = jQuery('#group_description_' + group_id + ' div').text();
    var group_h1 = jQuery('#group_h1_' + group_id + ' div').text();

    var data = {
        action : 'gkty_create_page',
        group_id : group_id,
        group_name : group_name,
        group_title : group_title,
        group_url : group_url,
        group_description : group_description,
        group_h1 : group_h1,
        post_type: 'page'
    };
    jQuery.post(ajax_object.ajax_url, data, function(respond) {
        processing_flag = false;
        setProgressDlgVal(current_tasks_count, total_tasks_count);
        if (current_tasks_count >= total_tasks_count) {
            total_tasks_count = 0;
            group_processing_flag = false;
        }
        jQuery('#group_data_table_' + group_id).attr('data-changed', 'false');
        if (jQuery.type(respond) == 'object') {
            if (respond.result == 'success') {
                create_post_result_queue.push({
                    group_id: group_id,
                    action: 'page',
                    result: 'Success',
                    description: respond.url
                });
            }
            else if (respond.result == 'already_exist') {
                create_post_result_queue.push({
                    group_id: group_id,
                    action: 'page',
                    result: 'Failure',
                    description: 'Already exists a page or post with the URL. Please try again after input another URL.'
                });
            }
            else {
                create_post_result_queue.push({
                    group_id: group_id,
                    action: 'page',
                    result: 'Failure',
                    description: 'Failure to create page.'
                });
            }
        }
    });
}

function create_post(group_id) {
    if (processing_flag == true) {
        alert('Already in progressing...');
        return false;
    }

    processing_flag = true;
    var group_name = jQuery('#group_name_' + group_id + ' div').text();
    var group_title = jQuery('#group_title_' + group_id + ' div').text();
    var group_url = jQuery('#group_url_' + group_id + ' div').text();
    var group_description = jQuery('#group_description_' + group_id + ' div').text();
    var group_h1 = jQuery('#group_h1_' + group_id + ' div').text();

    var data = {
        action : 'gkty_create_page',
        group_id : group_id,
        group_name : group_name,
        group_title : group_title,
        group_url : group_url,
        group_description : group_description,
        group_h1 : group_h1,
        post_type: 'post'
    };
    jQuery.post(ajax_object.ajax_url, data, function(respond) {
        processing_flag = false;
        setProgressDlgVal(current_tasks_count, total_tasks_count);
        if (current_tasks_count >= total_tasks_count) {
            total_tasks_count = 0;
            group_processing_flag = false;
        }
        jQuery('#group_data_table_' + group_id).attr('data-changed', 'false');
        if (jQuery.type(respond) == 'object') {
            if (respond.result == 'success') {
                create_post_result_queue.push({
                    group_id: group_id,
                    action: 'post',
                    result: 'Success',
                    description: respond.url
                });
            }
            else if (respond.result == 'already_exist') {
                create_post_result_queue.push({
                    group_id: group_id,
                    action: 'post',
                    result: 'Failure',
                    description: 'Already exists a page or post with the URL. Please try again after input another URL.'
                });
            }
            else {
                create_post_result_queue.push({
                    group_id: group_id,
                    action: 'post',
                    result: 'Failure',
                    description: 'Failure to create post.'
                });
            }
        }
    });
}

function save_keyword(keyword_id) {
    if (processing_flag == true) {
        alert('Already in progressing...');
        return false;
    }

    processing_flag = true;
    var keyword_name = jQuery('#keyword_name_' + keyword_id + ' div').text().trim();
    var keyword_volumn = jQuery('#keyword_volumn_' + keyword_id + ' div').text().trim();
    var keyword_cpc = jQuery('#keyword_cpc_' + keyword_id + ' div').text().trim();

    var data = {
        action : 'gkty_save_keyword',
        keyword_id : keyword_id,
        keyword_name : keyword_name,
        keyword_volumn : keyword_volumn,
        keyword_cpc : keyword_cpc,
        is_scrape: '0'
    };

    jQuery.post(ajax_object.ajax_url, data, function(respond) {
        processing_flag = false;
        setProgressDlgVal(current_tasks_count, total_tasks_count);
        if (current_tasks_count >= total_tasks_count) {
            total_tasks_count = 0;
            group_processing_flag = false;
        }
        jQuery('#group_keyword_row_' + keyword_id).attr('data-changed', 'false');
        if (respond) {
            var ret_obj = jQuery.parseJSON(respond);
            jQuery('#keyword_cpc_' + keyword_id).removeClass('red_color');
            jQuery('#keyword_cpc_' + keyword_id).removeClass('green_color');
            jQuery('#keyword_cpc_' + keyword_id).removeClass('yellow_color');
            //jQuery('#keyword_cpc_' + keyword_id).addClass(ret_obj.cpc_color);
            jQuery('#keyword_cpc_' + keyword_id + ' div').html(ret_obj.cpc);

            jQuery('#keyword_volumn_' + keyword_id).removeClass('red_color');
            jQuery('#keyword_volumn_' + keyword_id).removeClass('green_color');
            jQuery('#keyword_volumn_' + keyword_id).removeClass('yellow_color');
            //jQuery('#keyword_volumn_' + keyword_id).addClass(ret_obj.volumn_color);
            jQuery('#keyword_volumn_' + keyword_id + ' div').html(ret_obj.volumn);
            jQuery('#keyword_inbroad_' + keyword_id).removeClass('red_color');
            jQuery('#keyword_inbroad_' + keyword_id).removeClass('green_color');
            jQuery('#keyword_inbroad_' + keyword_id).removeClass('yellow_color');
            jQuery('#keyword_inbroad_' + keyword_id).html('');
            jQuery('#keyword_inparse_' + keyword_id).removeClass('red_color');
            jQuery('#keyword_inparse_' + keyword_id).removeClass('green_color');
            jQuery('#keyword_inparse_' + keyword_id).removeClass('yellow_color');
            jQuery('#keyword_inparse_' + keyword_id).html('');
            jQuery('#keyword_intitle_' + keyword_id).removeClass('red_color');
            jQuery('#keyword_intitle_' + keyword_id).removeClass('green_color');
            jQuery('#keyword_intitle_' + keyword_id).removeClass('yellow_color');
            jQuery('#keyword_intitle_' + keyword_id).html('');
            jQuery('#keyword_inurl_' + keyword_id).removeClass('red_color');
            jQuery('#keyword_inurl_' + keyword_id).removeClass('green_color');
            jQuery('#keyword_inurl_' + keyword_id).removeClass('yellow_color');
            jQuery('#keyword_inurl_' + keyword_id).html('');
        }
    });
}

function get_keyword_data(keyword_id) {
     if (processing_flag == true) {
        alert('Already in progressing...');
        return false;
    }

    processing_flag = true;
    var keyword_name = jQuery('#keyword_name_' + keyword_id + ' div').text().trim();
    var keyword_volumn = jQuery('#keyword_volumn_' + keyword_id + ' div').text().trim();
    var keyword_cpc = jQuery('#keyword_cpc_' + keyword_id + ' div').text().trim();

    var data = {
        action : 'gkty_save_keyword',
        keyword_id : keyword_id,
        keyword_name : keyword_name,
        keyword_volumn : keyword_volumn,
        keyword_cpc : keyword_cpc,
        is_scrape: '1'
    };
    jQuery.post(ajax_object.ajax_url, data, function(respond) {
        processing_flag = false;
        setProgressDlgVal(current_tasks_count, total_tasks_count);
        if (current_tasks_count >= total_tasks_count) {
            total_tasks_count = 0;
            group_processing_flag = false;
        }
        jQuery('#group_keyword_row_' + keyword_id).attr('data-changed', 'false');
        jQuery('#group_keyword_row_' + keyword_id).attr('data-scraped', 'true');
        if (respond) {
            var ret_obj = jQuery.parseJSON(respond);
            jQuery('#keyword_cpc_' + keyword_id).removeClass('red_color');
            jQuery('#keyword_cpc_' + keyword_id).removeClass('green_color');
            jQuery('#keyword_cpc_' + keyword_id).removeClass('yellow_color');
            jQuery('#keyword_cpc_' + keyword_id).addClass(ret_obj.cpc_color);
            jQuery('#keyword_cpc_' + keyword_id + ' div').html(ret_obj.cpc);

            jQuery('#keyword_volumn_' + keyword_id).removeClass('red_color');
            jQuery('#keyword_volumn_' + keyword_id).removeClass('green_color');
            jQuery('#keyword_volumn_' + keyword_id).removeClass('yellow_color');
            jQuery('#keyword_volumn_' + keyword_id).addClass(ret_obj.volumn_color);
            jQuery('#keyword_volumn_' + keyword_id + ' div').html(ret_obj.volumn);

            jQuery('#keyword_inbroad_' + keyword_id).removeClass('red_color');
            jQuery('#keyword_inbroad_' + keyword_id).removeClass('green_color');
            jQuery('#keyword_inbroad_' + keyword_id).removeClass('yellow_color');
            jQuery('#keyword_inbroad_' + keyword_id).addClass(ret_obj.inbroad_color);
            jQuery('#keyword_inbroad_' + keyword_id).html(ret_obj.inbroad);

            jQuery('#keyword_inparse_' + keyword_id).removeClass('red_color');
            jQuery('#keyword_inparse_' + keyword_id).removeClass('green_color');
            jQuery('#keyword_inparse_' + keyword_id).removeClass('yellow_color');
            jQuery('#keyword_inparse_' + keyword_id).addClass(ret_obj.inparse_color);
            jQuery('#keyword_inparse_' + keyword_id).html(ret_obj.inparse);

            jQuery('#keyword_intitle_' + keyword_id).removeClass('red_color');
            jQuery('#keyword_intitle_' + keyword_id).removeClass('green_color');
            jQuery('#keyword_intitle_' + keyword_id).removeClass('yellow_color');
            jQuery('#keyword_intitle_' + keyword_id).addClass(ret_obj.intitle_color);
            jQuery('#keyword_intitle_' + keyword_id).html(ret_obj.intitle);

            jQuery('#keyword_inurl_' + keyword_id).removeClass('red_color');
            jQuery('#keyword_inurl_' + keyword_id).removeClass('green_color');
            jQuery('#keyword_inurl_' + keyword_id).removeClass('yellow_color');
            jQuery('#keyword_inurl_' + keyword_id).addClass(ret_obj.inurl_color);
            jQuery('#keyword_inurl_' + keyword_id).html(ret_obj.inurl);
        }
    });
}

function callAction(cur_action, cur_value) {
    if (cur_action == 'save_group') {
        save_group(cur_value);
    }
    else if (cur_action == 'save_keyword') {
        save_keyword(cur_value);
    }
    else if (cur_action == 'get_keyword_data') {
        get_keyword_data(cur_value);
    }
    else if (cur_action == 'create_page') {
        create_page(cur_value);
    }
    else if (cur_action == 'create_post') {
        create_post(cur_value);
    }
}

function saveProject(project_id) {
    window.onbeforeunload = null;

    if (processing_flag == true || group_processing_flag == true) {
        alert('Already on processing...');
        return false;
    }
    processing_flag = true;
    group_processing_flag = true;
    processing_action_queue = [];
    processing_value_queue = [];
    total_tasks_count = 0;
    current_tasks_count = 0;

    var group_tables = jQuery('#groups_contents_area').find('table.group_data_table');
    var j = 0;
    for (j = 0; j < group_tables.length; j++) {
        var group_table_id = parseInt(jQuery(group_tables[j]).attr('id').replace('group_data_table_',''));
        updateQueueForGroup(group_table_id);
    }

    processing_flag = false;
    if (total_tasks_count <= 0) {
        alert('Already saved data');
        return false;
    }
    showProgressDlg();
    return false;
}

function onSetFocusEditor(obj) {
    var child_ele = jQuery(obj).find('div');
    jQuery(child_ele).focus();
}

function goToPage(group_id) {
    jQuery.post(ajaxurl, 'action=gkty_get_page&group=' + group_id)
        .done(function(d){
            if (d.status == 'error') {
                alert('There is no page/post associated with this group. Please create a page/post first!');
            } else {
                var win = window.open('http://' + document.location.host + d.url, '_blank');
                win.focus();
            }
        });
}

function changeGroup(group_id, project_id) {
    if (processing_flag == true) {
        alert('Already in progressing...');
        return false;
    }

    // Find all selected keywords
    var keywords = [];
    jQuery('#group_keyword_table_' + group_id).find('.keyword_ids')
        .each(function(){
            if (jQuery(this).prop('checked')) {
                keywords.push(jQuery(this).data('id'));
            }
        });

    if (keywords.length < 1) {
        alert('Please select some keywords first!');
        return false;
    }

    jQuery.post(ajaxurl, 'action=gkty_get_groups&project_id=' + project_id)
        .done(function(d){

            var groups = '';
            for(var i = 0; i < d.length; i++) {
                var cd = d[i];
                if (cd.name == '' || cd.name == null) continue;
                groups += '<option value="'+cd.id+'">'+cd.name+'</option>' + "\n";
            }

            jQuery.fancybox('\
        <div class="white_row_wrapper  blue_lightbox" id="confirmation_blue">\
            <div class="conwrapper"><div class="modal-body">\
                <div class="lightbox_content">\
                    <form method="POST" action="admin-post.php" id="upload-form" enctype="multipart/form-data">\
                        <input type="hidden" name="action" value="ps_change_group">\
                        <input type="hidden" name="keywords" value="'+keywords.join(',')+'">\
                        <input type="hidden" name="group" value="'+group_id+'">\
                        <input type="hidden" name="project" value="'+project_id+'">\
                        <div class="form-group">\
                            <label for="new_group">Select a new Group:</label>\
                            <select id="new_group" name="new_group" class="form-control" requred>\
                            '+groups+'\
                            </select>\
                        </div>\
                        <div class="form-group">\
                            <label for="new_group_create">or Create a New Group</label>\
                            <input id="new_group_create" name="new_group_create" class="form-control" type="text"/>\
                        </div>\
                        <div class="lightbox_bottom text-right">\
                            <button type="submit" class="btn btn-primary" style="width: 100px;">Change</button>   \
                            <button type="button" id="close_fancybox" class="btn btn-default" style="width: 100px;">Cancel</button>   \
                        </div>\
                    </form>\
                </div>\
                </div>\
            </div>\
        </div>\
    ');

            jQuery('#close_fancybox')
                .click(function(){
                    jQuery.fancybox.close();
                });
        });


    //
    //processing_flag = true;
    //var data = {
    //    action : 'gkty_delete_keywords',
    //    group_id : group_id,
    //    keywords: keywords.join(',')
    //};
    //
    //jQuery.post(ajax_object.ajax_url, data, function(respond) {
    //    processing_flag = false;
    //    var main_ele = jQuery('div.group_main_area_' + group_id).parent().parent().parent().parent();
    //});
}

function deleteKeywords(group_id) {
    if (processing_flag == true) {
        alert('Already in progressing...');
        return false;
    }
    var answer = confirm("Are you sure to delete selected keywords ?");
    if (!answer) {
        return false;
    }

    // Find all selected keywords
    var keywords = [];
    jQuery('#group_keyword_table_' + group_id).find('.keyword_ids')
        .each(function(){
            if (jQuery(this).prop('checked')) {
                keywords.push(jQuery(this).data('id'));
                jQuery(this).parents('tr').remove();
            }
        });

    if (keywords.length < 1) {
        alert('Please select some keywords first!');
        return false;
    }

    processing_flag = true;
    var data = {
        action : 'gkty_delete_keywords',
        group_id : group_id,
        keywords: keywords.join(',')
    };

    jQuery.post(ajax_object.ajax_url, data, function(respond) {
        processing_flag = false;
        var main_ele = jQuery('div.group_main_area_' + group_id).parent().parent().parent().parent();
    });
}

function deleteGroup(group_id) {
    if (processing_flag == true) {
        alert('Already in progressing...');
        return false;
    }
    var answer = confirm("Are you sure to delete this group ?");
    if (!answer) {
        return false;
    }

    processing_flag = true;
    var data = {
        action : 'gkty_delete_group',
        group_id : group_id
    }

    jQuery.post(ajax_object.ajax_url, data, function(respond) {
        processing_flag = false;
        var main_ele = jQuery('div.group_main_area_' + group_id).parent().parent().parent().parent();
        main_ele.fadeOut(400, function(){
            main_ele.remove();
        });
    });
}

function showHideProfitArea(group_id) {
    var profit_area = jQuery('table#group_profit_area_' + group_id);
    if (profit_area.hasClass('hide')) {
        var group_keyword_table_ele = jQuery('#group_keyword_table_' + group_id);
        var keyword_rows = group_keyword_table_ele.find('.group_keyword_row');
        var i = 0, keyword_id, keyword_name, keyword_volume, keyword_cpc, total_keyword = 0, total_volume = 0, total_cpc = 0;
        for (i = 0; i < keyword_rows.length; i++) {
            keyword_id = jQuery(keyword_rows[i]).attr('id').replace('group_keyword_row_', '');
            keyword_name = jQuery(keyword_rows[i]).find('#keyword_name_' + keyword_id + ' div').text();
            if (keyword_name != '') {
                keyword_volume = parseInt(jQuery(keyword_rows[i]).find('#keyword_volumn_' + keyword_id + ' div').text().trim());
                keyword_cpc = parseFloat(jQuery(keyword_rows[i]).find('#keyword_cpc_' + keyword_id + ' div').text().trim());
                if (typeof keyword_volume != 'undefined' && !isNaN(keyword_volume)) {
                    total_volume += keyword_volume;
                }
                if (typeof keyword_cpc != 'undefined' && !isNaN(keyword_cpc)) {
                    total_cpc += keyword_cpc;
                }
                total_keyword ++;
            }
        }
        var average_cpc = 0;
        if (total_keyword > 0) {
            average_cpc = parseFloat(total_cpc / total_keyword);
        }
        var profit = parseFloat(parseFloat(parseFloat(total_volume) * 0.4) * parseFloat(average_cpc * 0.05));
        jQuery('#group_total_volume_' + group_id).html(total_volume);
        jQuery('#group_average_cpc_' + group_id).html(average_cpc.toFixed(2));
        jQuery('#group_potential_profit_' + group_id).html(profit.toFixed(2));
        profit_area.removeClass('hide');
        jQuery('button#group_profit_btn_' + group_id).text('[HIDE PROFITABILITY]');
    }
    else {
        profit_area.addClass('hide');
        jQuery('button#group_profit_btn_' + group_id).text('[SHOW PROFITABILITY]');
    }

}

function check_valid(value) {
    if (typeof value == 'undefined' || isNaN(value)) {
        return -1;
    }
    return value;
}
setInterval(function(){
    if (total_tasks_count == 0) {
        group_processing_flag = false;
        current_tasks_count = total_tasks_count = 0;
        hideProgressDlg();
        displayCreatePostResultPage();
    }
    if (group_processing_flag == true) {
        if (processing_flag == false && total_tasks_count > current_tasks_count) {
            var currentAction = processing_action_queue.shift();
            var currentValue = processing_value_queue.shift();
            callAction(currentAction, currentValue);
            current_tasks_count++;
        }
    }
}, 500);

function myConfirmation() {
    return 'You did not Save your Changes! Please stay on the page and click Save Project before navigating away to a different page in order to keep your changes!';
}

// Coding for Conditional Formatting Area
jQuery(document).ready(function() {

    jQuery(document).on('click', '.run_validation', function(e){
        schemaValidation();
    });

    jQuery(document).on('change paste keyup', '#ProxyList', function(){

        var proxy_list_val = jQuery(this).val();

        if(proxy_list_val == ''){
            jQuery('.form-enable-proxy input[type="checkbox"]').prop('checked', false);
        }else{
            jQuery('.form-enable-proxy input[type="checkbox"]').prop('checked', true);
        }

    });


    jQuery('body').on('click', 'th.gkt_title:last-child', function(){
        jQuery(this).parents('.panel-body').find('[type="checkbox"]').each(function(){
            if (jQuery(this).is(':checked')) {
                jQuery(this).removeAttr('checked');
            } else {
                jQuery(this).attr('checked', 'checked');
            }
        });
    });

    jQuery('#close_processing').click(function(){
        processing_flag = false;
        group_processing_flag = false;
        processing_action_queue = [];
        processing_value_queue = [];
        total_tasks_count = 0;
        current_tasks_count = 0;
        hideProgressDlg();
    });

    jQuery('body').on('focus', '[contenteditable]', function() {
        var $this = jQuery(this);
        $this.data('before', $this.html());
        return $this;
    }).on('blur keyup paste input', '[contenteditable]', function() {
        var $this = jQuery(this);
        if ($this.data('before') !== $this.html()) {
            $this.data('before', $this.html());
            $this.trigger('change');
        }
        return $this;
    });

    // If somebody wants to leave before finishing changes
    jQuery('[contenteditable=true]').on('change', function(){
        window.onbeforeunload = myConfirmation;
    });


    // Save APIs
    jQuery(document).on('submit', '.form-save-api', function(e){
        e.preventDefault();
        jQuery.post(ajaxurl, jQuery(this).serialize())
            .done(function(d){
                alert('Successfully saved API settings!');
            });
    });

    // Save JSON LD
    jQuery(document).on('submit', '.form-save-jsonld', function(e){
        e.preventDefault();
        jQuery.post(ajaxurl, jQuery(this).serialize())
            .done(function(d){
                alert('Successfully saved JSON LD settings!');
            });
    });

    // Save Proxies
    jQuery(document).on('submit', '.form-enable-proxy', function(e){
        e.preventDefault();
        jQuery.post(ajaxurl, jQuery(this).serialize())
            .done(function(d){
                alert('Successfully saved Proxy settings!');
            });
    });

    // Refresh Clickbank
    jQuery(document).on('click', '#refresh_clickbank_data', function(){
        var data = {
            'action': 'refresh_clickbank'
        };
        jQuery.post("/wp-admin/admin-post.php", data)
            .done(function(d){
                alert('Successfully refreshed ClickBank Marketplace data!');
            }, 'json');

    });

    jQuery(document).on('click', '.all_selection', function(){
        var table = jQuery(this).parents('table');
        table.find('.keyword_ids').each(function(){
            jQuery(this).trigger('click');
        });
    });

    jQuery('#tabbed-nav').zozoTabs({
        "theme": "white",
        "size": "medium",
        "multiline": true,
        "rounded": true,
        "shadows": true,
        "animation": {"duration": 500, "effects": "slideDown"},
        "position": "top-left",
        "orientation": "vertical"
    });
    jQuery('#tabbed-nav-api').zozoTabs({
        "theme": "white",
        "size": "medium",
        "multiline": true,
        "rounded": true,
        "shadows": true,
        "animation": {"duration": 500, "effects": "slideDown"},
        "position": "top-left",
        "orientation": "vertical"
    });
    jQuery('#tabbed-nav-json-ld').zozoTabs({
        "theme": "white",
        "size": "medium",
        "multiline": true,
        "rounded": true,
        "shadows": true,
        "animation": {"duration": 500, "effects": "slideDown"},
        "position": "top-left",
        "orientation": "vertical"
    });
    jQuery('#tabbed-nav-proxy').zozoTabs({
        "theme": "white",
        "size": "medium",
        "multiline": true,
        "rounded": true,
        "shadows": true,
        "animation": {"duration": 500, "effects": "slideDown"},
        "position": "top-left",
        "orientation": "vertical"
    });
    jQuery('#tabbed-nav-affiliate').zozoTabs(
        {
            "theme": "white",
            "size": "medium",
            "multiline": true,
            "rounded": true,
            "shadows": true,
            "animation": {
                "duration": 500,
                "effects": "slideH"
            },
            "position": "top-left",
            "orientation": "horizontal"
        }
    );

    jQuery('#gkty_btn_set_default').click(function() {
        var data = {
            action : 'gkty_set_default'
        };
        jQuery(this).attr('disabled', 'true');
        jQuery.post(ajax_object.ajax_url, data, function(respond) {
            jQuery('#gkty_btn_set_default').removeAttr('disabled');
            if (jQuery.type(respond) == 'object') {
                jQuery('#value1-volume').val(respond.volume.value1);
                jQuery('#value2-volume').val(respond.volume.value2);
                jQuery('#value1-volume-follow').val(respond.volume.value1);
                jQuery('#value2-volume-follow').val(respond.volume.value2);
                jQuery('#value1-volume-hidden').val(respond.volume.value1);
                jQuery('#value2-volume-hidden').val(respond.volume.value2);

                jQuery('#value1-cpc').val(respond.cpc.value1);
                jQuery('#value2-cpc').val(respond.cpc.value2);
                jQuery('#value1-cpc-follow').val(respond.cpc.value1);
                jQuery('#value2-cpc-follow').val(respond.cpc.value2);
                jQuery('#value1-cpc-hidden').val(respond.cpc.value1);
                jQuery('#value2-cpc-hidden').val(respond.cpc.value2);

                jQuery('#value1-broad').val(respond.broad.value1);
                jQuery('#value2-broad').val(respond.broad.value2);
                jQuery('#value1-broad-follow').val(respond.broad.value1);
                jQuery('#value2-broad-follow').val(respond.broad.value2);
                jQuery('#value1-broad-hidden').val(respond.broad.value1);
                jQuery('#value2-broad-hidden').val(respond.broad.value2);

                jQuery('#value1-phrase').val(respond.phrase.value1);
                jQuery('#value2-phrase').val(respond.phrase.value2);
                jQuery('#value1-phrase-follow').val(respond.phrase.value1);
                jQuery('#value2-phrase-follow').val(respond.phrase.value2);
                jQuery('#value1-phrase-hidden').val(respond.phrase.value1);
                jQuery('#value2-phrase-hidden').val(respond.phrase.value2);

                jQuery('#value1-intitle').val(respond.intitle.value1);
                jQuery('#value2-intitle').val(respond.intitle.value2);
                jQuery('#value1-intitle-follow').val(respond.intitle.value1);
                jQuery('#value2-intitle-follow').val(respond.intitle.value2);
                jQuery('#value1-intitle-hidden').val(respond.intitle.value1);
                jQuery('#value2-intitle-hidden').val(respond.intitle.value2);

                jQuery('#value1-inurl').val(respond.inurl.value1);
                jQuery('#value2-inurl').val(respond.inurl.value2);
                jQuery('#value1-inurl-follow').val(respond.inurl.value1);
                jQuery('#value2-inurl-follow').val(respond.inurl.value2);
                jQuery('#value1-inurl-hidden').val(respond.inurl.value1);
                jQuery('#value2-inurl-hidden').val(respond.inurl.value2);


                // Aff
                jQuery('#value1-volume_aff').val(respond.volume_aff.value1);
                jQuery('#value2-volume_aff').val(respond.volume_aff.value2);
                jQuery('#value1-volume_aff-follow').val(respond.volume_aff.value1);
                jQuery('#value2-volume_aff-follow').val(respond.volume_aff.value2);
                jQuery('#value1-volume_aff-hidden').val(respond.volume_aff.value1);
                jQuery('#value2-volume_aff-hidden').val(respond.volume_aff.value2);

                jQuery('#value1-cpc_aff').val(respond.cpc_aff.value1);
                jQuery('#value2-cpc_aff').val(respond.cpc_aff.value2);
                jQuery('#value1-cpc_aff-follow').val(respond.cpc_aff.value1);
                jQuery('#value2-cpc_aff-follow').val(respond.cpc_aff.value2);
                jQuery('#value1-cpc_aff-hidden').val(respond.cpc_aff.value1);
                jQuery('#value2-cpc_aff-hidden').val(respond.cpc_aff.value2);

                jQuery('#value1-broad_aff').val(respond.broad_aff.value1);
                jQuery('#value2-broad_aff').val(respond.broad_aff.value2);
                jQuery('#value1-broad_aff-follow').val(respond.broad_aff.value1);
                jQuery('#value2-broad_aff-follow').val(respond.broad_aff.value2);
                jQuery('#value1-broad_aff-hidden').val(respond.broad_aff.value1);
                jQuery('#value2-broad_aff-hidden').val(respond.broad_aff.value2);

                jQuery('#value1-phrase_aff').val(respond.phrase_aff.value1);
                jQuery('#value2-phrase_aff').val(respond.phrase_aff.value2);
                jQuery('#value1-phrase_aff-follow').val(respond.phrase_aff.value1);
                jQuery('#value2-phrase_aff-follow').val(respond.phrase_aff.value2);
                jQuery('#value1-phrase_aff-hidden').val(respond.phrase_aff.value1);
                jQuery('#value2-phrase_aff-hidden').val(respond.phrase_aff.value2);

                jQuery('#value1-intitle_aff').val(respond.intitle_aff.value1);
                jQuery('#value2-intitle_aff').val(respond.intitle_aff.value2);
                jQuery('#value1-intitle_aff-follow').val(respond.intitle_aff.value1);
                jQuery('#value2-intitle_aff-follow').val(respond.intitle_aff.value2);
                jQuery('#value1-intitle_aff-hidden').val(respond.intitle_aff.value1);
                jQuery('#value2-intitle_aff-hidden').val(respond.intitle_aff.value2);

                jQuery('#value1-inurl_aff').val(respond.inurl_aff.value1);
                jQuery('#value2-inurl_aff').val(respond.inurl_aff.value2);
                jQuery('#value1-inurl_aff-follow').val(respond.inurl_aff.value1);
                jQuery('#value2-inurl_aff-follow').val(respond.inurl_aff.value2);
                jQuery('#value1-inurl_aff-hidden').val(respond.inurl_aff.value1);
                jQuery('#value2-inurl_aff-hidden').val(respond.inurl_aff.value2);
            }
            else {
                alert('Failure to set to default.');
            }
        });
    });

    jQuery('button.condition-button-save').click(function() {
        var type = jQuery(this).attr('id').replace('condition-button-save-', '');
        var value1 = jQuery('#value1-' + type).val();
        var value2 = jQuery('#value2-' + type).val();
        var button_obj = this;
        var data = {
            action : 'gkty_save_condition',
            type : type,
            value1 : value1,
            value2 : value2
        };
        jQuery(button_obj).attr('disabled', 'true');
        jQuery.post(ajax_object.ajax_url, data, function(respond) {
            jQuery(button_obj).removeAttr('disabled');
            if (jQuery.type(respond) == 'object') {
                if (respond.result != 'success') {
                    alert('Failure to save this condition');
                }
            }
            else {
                alert('Failure to save this condition');
            }
        });
    });
    jQuery('input.condition-value1').blur(function() {
        var value1 = jQuery(this).val();
        var type = jQuery(this).attr('id').replace('value1-', '');
        if (value1 == '') {
            jQuery(this).val(jQuery('#value1-' + type + '-hidden').val());
        }
    });
    jQuery('input.condition-value1').change(function() {
        var type = jQuery(this).attr('id').replace('value1-', '');
        var value1 = jQuery(this).val();
        var value2 = jQuery('#value2-' + type).val();
        if (value1 == '') {
            jQuery(this).val(jQuery('#value1-' + type + '-hidden').val());
            value1 = jQuery(this).val();
        }
        if (type == 'cpc' || type == 'cpc_aff') {
            if (typeof value1 == 'undefined' || isNaN(value1) || !is_float(value1)) {
                //alert('Type of this value need to be type "FLOAT"');
                //jQuery(this).val('');
                //jQuery(this).focus();
                //return false;
            }
            value1 = parseFloat(value1);
        }
        else {
            if (typeof value1 == 'undefined' || isNaN(value1) || !isUint32(value1)) {
                //alert('Type of this value need to be type "INT"');
                //jQuery(this).val('');
                //jQuery(this).focus();
                //return false;
            }
            value1 = parseInt(value1);
        }

        if (type == 'volume' || type == 'cpc' || type == 'volume_aff' || type == 'cpc_aff') {
            if (value1 >= value2) {
                alert('Please input correct condition');
                jQuery(this).val('');
                jQuery(this).focus();
                return false;
            }
        }
        else {
            if (value1 <= value2) {
                alert('Please input correct condition');
                jQuery(this).val('');
                jQuery(this).focus();
                return false;
            }
        }
        jQuery('#value1-' + type + '-hidden').val(value1);
        jQuery('#value1-' + type + '-follow').val(value1);
    });
    jQuery('input.condition-value2').blur(function() {
        var value = jQuery(this).val();
        var type = jQuery(this).attr('id').replace('value2-', '');
        if (value == '') {
            jQuery(this).val(jQuery('#value2-' + type + '-hidden').val());
        }
    });
    jQuery('input.condition-value2').change(function() {
        var type = jQuery(this).attr('id').replace('value2-', '');
        var value2 = jQuery(this).val();
        var value1 = jQuery('#value1-' + type).val();

        if (value2 == '') {
            jQuery(this).val(jQuery('#value2-' + type + '-hidden').val());
            value2 = jQuery(this).val();
        }

        if (type == 'cpc' || type == 'cpc_aff') {
            if (typeof value2 == 'undefined' || isNaN(value2) || !is_float(value2)) {
                //alert('Type of this value need to be type "FLOAT"');
                //jQuery(this).val('');
                //jQuery(this).focus();
                //return false;
            }
            value2 = parseFloat(value2);
        }
        else {
            if (typeof value2 == 'undefined' || isNaN(value2) || !isUint32(value2)) {
                //alert('Type of this value need to be type "INT"');
                //jQuery(this).val('');
                //jQuery(this).focus();
                //return false;
            }
            value2 = parseInt(value2);
        }

        if (type == 'volume' || type == 'cpc' || type == 'volume_aff' || type == 'cpc_aff') {
            if (value1 >= value2) {
                alert('Please input correct condition');
                jQuery(this).val('');
                jQuery(this).focus();
                return false;
            }
        }
        else {
            if (value1 <= value2) {
                alert('Please input correct condition');
                jQuery(this).val('');
                jQuery(this).focus();
                return false;
            }
        }
        jQuery('#value2-' + type + '-follow').val(value2);
    });

    jQuery('#ps_create_page_btn').live('click', function(e) {
        var group_id = jQuery(this).attr('data-group-id');
        createPageAndPost('page', group_id);
        jQuery.fancybox.close();
    });

    jQuery('#ps_create_post_btn').live('click', function(e) {
        var group_id = jQuery(this).attr('data-group-id');
        createPageAndPost('post', group_id);
        jQuery.fancybox.close();
    });

    jQuery('#createAllPosts_btn').live('click', function(e) {
        jQuery.fancybox.close();
        if (processing_flag == true || group_processing_flag == true) {
            alert('Already on processing...');
            return false;
        }
        processing_flag = true;
        group_processing_flag = true;
        processing_action_queue = [];
        processing_value_queue = [];
        total_tasks_count = 0;
        current_tasks_count = 0;

        var group_tables = jQuery('#groups_contents_area').find('table.group_data_table');
        var j;
        for (j = 0; j < group_tables.length; j++) {
            var group_table_id = parseInt(jQuery(group_tables[j]).attr('id').replace('group_data_table_',''));
            var group_create_type = jQuery('input[type="radio"][name="group_method_radio_' + group_table_id + '"]:checked').val();
            updateQueueForCreatePageAndPost(group_create_type, group_table_id);
        }

        processing_flag = false;
        if (total_tasks_count <= 0) {
            displayCreatePostResultPage();
            return false;
        }
        showProgressDlg();
        return false;
    });

    jQuery('#createAllPosts_cancel_btn').live('click', function(e) {
        jQuery.fancybox.close();
    });

    jQuery('table.group_keyword_table th.gkt_title').live('click', function(e) {
        var table_ele = jQuery(this).parent().parent().parent();
        var current_order_by = jQuery(this).attr('data-sort');
        if (typeof current_order_by == 'undefined') {
            return false;
        }
        if (processing_flag == true) {
            alert('Already in progressing...');
            return false;
        }
        var order_by = jQuery(table_ele).attr('data-order-by');
        var origin_order = jQuery(table_ele).attr('data-order');
        var order = 'asc';
        if ((typeof order_by == 'undefined') || (current_order_by != order_by)) {
            order = 'asc';
            order_by = current_order_by;
        }
        else {
            if (origin_order == 'asc') {
                order = 'desc';
            }
            else {
                order = 'asc';
            }
        }
        jQuery(table_ele).attr('data-order-by', order_by);
        jQuery(table_ele).attr('data-order', order);
        jQuery(table_ele).find('thead th i').removeClass('fa-sort-desc');
        jQuery(table_ele).find('thead th i').removeClass('fa-sort-asc');
        jQuery(table_ele).find('thead th i').addClass('fa-sort');
        if (order == 'asc') {
            jQuery(table_ele).find('thead th[data-sort="' + order_by + '"] i').removeClass('fa-sort');
            jQuery(table_ele).find('thead th[data-sort="' + order_by + '"] i').removeClass('fa-sort-desc');
            jQuery(table_ele).find('thead th[data-sort="' + order_by + '"] i').addClass('fa-sort-asc');
        }
        else {
            jQuery(table_ele).find('thead th[data-sort="' + order_by + '"] i').removeClass('fa-sort');
            jQuery(table_ele).find('thead th[data-sort="' + order_by + '"] i').removeClass('fa-sort-asc');
            jQuery(table_ele).find('thead th[data-sort="' + order_by + '"] i').addClass('fa-sort-desc');
        }
        // Get keyword data.
        var group_keyword_table_ele = jQuery(table_ele);
        var keyword_rows = group_keyword_table_ele.find('.group_keyword_row');
        var i = 0;
        var keyword_array = [];
        for (i = 0; i < keyword_rows.length; i++) {
            var keyword_id = jQuery(keyword_rows[i]).attr('id').replace('group_keyword_row_', '');
            var keyword_changed = jQuery(keyword_rows[i]).attr('data-changed');
            var keyword_scraped = jQuery(keyword_rows[i]).attr('data-scraped');
            var keyword_name = jQuery('#keyword_name_' + keyword_id + ' div').text().trim();
            var keyword_volume = check_valid(parseInt(jQuery('#keyword_volumn_' + keyword_id + ' div').text().trim()));
            var keyword_volume_class = jQuery('#keyword_volumn_' + keyword_id).attr('class');
            var keyword_cpc = check_valid(parseFloat(jQuery('#keyword_cpc_' + keyword_id + ' div').text().trim()));
            var keyword_cpc_class = jQuery('#keyword_cpc_' + keyword_id).attr('class');
            var keyword_broad = check_valid(parseInt(jQuery('#keyword_inbroad_' + keyword_id).html().trim()));
            var keyword_broad_class = jQuery('#keyword_inbroad_' + keyword_id).attr('class');
            var keyword_phrase = check_valid(parseInt(jQuery('#keyword_inparse_' + keyword_id).html().trim()));
            var keyword_phrase_class = jQuery('#keyword_inparse_' + keyword_id).attr('class');
            var keyword_intitle = check_valid(parseInt(jQuery('#keyword_intitle_' + keyword_id).html().trim()));
            var keyword_intitle_class = jQuery('#keyword_intitle_' + keyword_id).attr('class');
            var keyword_inurl = check_valid(parseInt(jQuery('#keyword_inurl_' + keyword_id).html().trim()));
            var keyword_inurl_class = jQuery('#keyword_inurl_' + keyword_id).attr('class');
            keyword_array.push({
                id : keyword_id,
                name : keyword_name,
                volume : keyword_volume,
                cpc : keyword_cpc,
                broad : keyword_broad,
                phrase : keyword_phrase,
                intitle : keyword_intitle,
                inurl : keyword_inurl,
                volume_class : keyword_volume_class,
                cpc_class : keyword_cpc_class,
                broad_class : keyword_broad_class,
                phrase_class : keyword_phrase_class,
                intitle_class : keyword_intitle_class,
                inurl_class : keyword_inurl_class,
                changed : keyword_changed,
                scraped : keyword_scraped
            });
        }
        keyword_array.sort(function(a, b) {
            if (order_by == 'name') {
                if (order == 'asc') {
                    return a['name'] > b['name'];
                }
                else {
                    return b['name'] > a['name'];
                }
            }
            else {
                if (order == 'asc') {
                    return a[order_by] - b[order_by];
                }
                else {
                    return b[order_by] - a[order_by];
                }
            }
        });
        var row_html = '';
        jQuery.each(keyword_array, function(index, val) {
            if (val.volume == '-1') {
                val.volume = '';
            }
            if (val.cpc == '-1') {
                val.cpc = '';
            }
            if (val.broad == '-1') {
                val.broad = '';
            }
            if (val.phrase == '-1') {
                val.phrase = '';
            }
            if (val.intitle == '-1') {
                val.intitle = '';
            }
            if (val.inurl == '-1') {
                val.inurl = '';
            }
            row_html += '<tr id="group_keyword_row_' + val.id + '" class="group_keyword_row" data-changed="' + val.changed + '" data-scraped="' + val.scraped + '">';
            row_html += '<td id="keyword_name_' + val.id + '" class="keyword_field" onclick="return onSetFocusEditor(this);"><div contenteditable="true" style="width: 100%;height: 12px;height: auto;" onkeyup="onKeywordChange(this);">' + val.name + '</div></td>';
            row_html += '<td id="keyword_volumn_' + val.id + '" class="' + val.volume_class + '" onclick="return onSetFocusEditor(this);"><div contenteditable="true" style="width: 100%;height: 12px;height: auto;" onkeyup="onKeywordChange(this);" onblur="onVolumnBlue(this);">' + val.volume + '</div></td>';
            row_html += '<td id="keyword_cpc_' + val.id + '" class="' + val.cpc_class + '" onclick="return onSetFocusEditor(this);"><div contenteditable="true" style="width: 100%;height: 12px;height: auto;" onkeyup="onKeywordChange(this);" onblur="onCPCBlue(this);">' + val.cpc + '</div></td>';
            row_html += '<td id="keyword_inbroad_' + val.id + '" class="' + val.broad_class + '">' + val.broad + '</td>';
            row_html += '<td id="keyword_inparse_' + val.id + '" class="' + val.phrase_class + '">' + val.phrase + '</td>';
            row_html += '<td id="keyword_intitle_' + val.id + '" class="' + val.intitle_class + '">' + val.intitle + '</td>';
            row_html += '<td id="keyword_inurl_' + val.id + '" class="' + val.inurl_class + '">' + val.inurl + '</td>';
            row_html += '<td class="group_field text-left gkt_title">';
            row_html += '<button id="keyword_trash_btn_' + val.id + '" class="btn btn-link btn-keyword" onclick="return trash_keyword_data(' + val.id + ')"><i class="fa fa-times"></i></button>';
            row_html += '<button id="keyword_refresh_btn_' + val.id + '" class="btn btn-link btn-keyword" onclick="return refresh_keyword_data(' + val.id + ')"><i class="fa fa-refresh"></i></button>';
            row_html += '<input type="checkbox" data-id="' + val.id + '" class="keyword_ids"></td>';
        });
        group_keyword_table_ele.find('tbody').html(row_html);
    });

    if (jQuery('a#Import_project_btn').length) {
        jQuery('a#Import_project_btn').fancybox();
    }
    jQuery('#Import_project_btn_1').live('click', function() {
        // Check permission to create project.
        var data = {
            action : 'gkty_check_permission_project'
        };
        jQuery('#Import_project_btn_1').attr('disabled', 'disabled');
        jQuery.post(ajax_object.ajax_url, data, function(respond) {
            var alert_html = '';
            jQuery('#Import_project_btn_1').removeAttr('disabled');
            if (typeof respond.code != 'undefined') {
                if (respond.code == 'success') {
                    jQuery('a#Import_project_btn').trigger('click');
                    return;
                }
                else if (respond.code == 'network_error') {
                    alert_html = '<p style="padding: 10px;color: #333;Font-size: 16px;">You have something Internet connection error.</p>';
                }
                else if (respond.code == 'over_max') {
                    alert_html = "<p style='padding: 10px;color: #333;Font-size: 16px;'>I'm sorry, but you are currently limited to " + respond.project + " projects per domain, and you have reached your limit.  You can easily increase your plugins capabilities by giving it away for free.  Please click <a href='http://www.projectsupremacy.com/affiliate-info/' target='_blank'>here</a> to learn more!</p>";
                }
                else if (respond.code == 'fail') {
                    alert_html = '<p style="padding: 10px;color: #333;Font-size: 16px;">Please reactivate this plugin.</p>';
                }
            }
            else {
                alert_html = '<p style="padding: 10px;color: #333;Font-size: 16px;">Please reactivate this plugin.</p>';
            }

            jQuery.fancybox(
                '<div class="white_row_wrapper  blue_lightbox" id="confirmation_blue">\
          <div class="conwrapper"><div class="modal-body">\
          <div class="lightbox_content">' + alert_html +'\
  </div>\
  </div>\
  </div>\
  </div>');
            return false;
        });


    });
});

function importGroups(projectID, event) {
    event.preventDefault();

    jQuery.fancybox('\
        <div class="white_row_wrapper  blue_lightbox" id="confirmation_blue">\
            <div class="conwrapper"><div class="modal-body">\
                <div class="lightbox_content">\
                    <label>What do you want to do?</label>\
                    <div class="lightbox_bottom text-center">\
                        <button type="button" id="import_get_kw" class="btn btn-primary" style="width: 130px;">Get Keywords</button>\
                        <button type="button" id="import_get_csv" class="btn btn-primary" style="width: 130px;">Import from CSV</button>\
                    </div>\
                </div>\
                </div>\
            </div>\
        </div>\
        ');

    jQuery('#import_get_kw').click(function(){
        importGroups_get_kw(projectID, event);
    });
    jQuery('#import_get_csv').click(function(){
        importGroups_CSV(projectID, event);
        jQuery.fancybox.close();
    });
}

function importGroups_get_kw(projectID, event) {
    event.preventDefault();

    window.open('https://adwords.google.com/KeywordPlanner', '_blank');
}

function importGroups_CSV(projectID, event) {
    event.preventDefault();
    jQuery.fancybox('\
        <div class="white_row_wrapper  blue_lightbox" id="confirmation_blue">\
            <div class="conwrapper"><div class="modal-body">\
                <div class="lightbox_content">\
                    <form method="POST" action="admin-post.php" id="upload-form" enctype="multipart/form-data">\
                        <input type="hidden" name="action" value="ps_upload_keywords">\
                        <input type="hidden" name="projectID" value="'+projectID+'">\
                        <div class="form-group">\
                            <label for="importGroups_File">     Select a Keyword Planner CSV:</label>\
                            <input type="file" id="importGroups_File" name="importGroups_File" value="" />\
                        </div>\
                        <div class="lightbox_bottom text-right">\
                            <button type="submit" id="importGroups_btn" class="btn btn-primary" style="width: 100px;">Upload</button>   \
                            <button type="button" id="importGroups_cancel_btn" class="btn btn-default" style="width: 100px;">Cancel</button>   \
                        </div>\
                    </form>\
                </div>\
                </div>\
            </div>\
        </div>\
    ');

    jQuery('#upload-form').append(jQuery('#upload-nonce').html());

    jQuery('#importGroups_cancel_btn')
        .click(function(){
            jQuery.fancybox.close();
        })
}

function autoCreateGroups(projectID, event) {
    event.preventDefault();
    jQuery.fancybox(
        '<div class="white_row_wrapper  blue_lightbox" id="confirmation_blue">\
  <div class="conwrapper"><div class="modal-body">\
  <div class="lightbox_content">\
  <div class="form-group">\
  <label for="autoCreateGroups_seed">Enter a seed keyword:</label>\
  <input class="form-control" type="text" id="autoCreateGroups_seed" placeholder="eg. tasty seafood" style="width: 100%;"/>\
  <small><b>*by using your seed keyword, we will generate groups and keywords based on it</b></small>\
  </div>\
  <hr style="margin: 0px 0px 10px 0px;">\
  <b>Filters:</b>\
  \
  <div class="row">\
  <div class="col-md-6" style="width: 43%">\
  <div class="form-group">\
  <label for="autoCreateGroups_minSearchVolume">Min. Search Volume</label>\
  <input type="number" id="autoCreateGroups_minSearchVolume" required="" min="10" value="10" placeholder="eg. 1000" class="form-control"/>\
  </div>\
  </div>\
  <div class="col-md-6" style="width: 43%">\
  <div class="form-group">\
  <label for="autoCreateGroups_minCPC">Min. Cost Per Click</label>\
  <input type="number" id="autoCreateGroups_minCPC" required="" min="0.50" value="0.50" placeholder="eg. 0.50" class="form-control"/>\
  </div>\
  </div>\
  </div>\
  <div class="form-group">\
  <label for="autoCreateGroups_languageCode">Language:</label>\
  <select id="autoCreateGroups_languageCode" class="form-control">\
  <option value="0000" selected>-- All Languages --</option>\
  <option value="1019">Arabic</option>\
  <option value="1020">Bulgarian</option>\
  <option value="1038">Catalan</option>\
  <option value="1017">Chinese (simplified)</option>\
  <option value="1018">Chinese (traditional)</option>\
  <option value="1039">Croatian</option>\
  <option value="1021">Czech</option>\
  <option value="1009">Danish</option>\
  <option value="1010">Dutch</option>\
  <option value="1000">English</option>\
  <option value="1043">Estonian</option>\
  <option value="1042">Filipino</option>\
  <option value="1011">Finnish</option>\
  <option value="1002">French</option>\
  <option value="1001">German</option>\
  <option value="1022">Greek</option>\
  <option value="1027">Hebrew</option>\
  <option value="1023">Hindi</option>\
  <option value="1024">Hungarian</option>\
  <option value="1026">Icelandic</option>\
  <option value="1025">Indonesian</option>\
  <option value="1004">Italian</option>\
  <option value="1005">Japanese</option>\
  <option value="1012">Korean</option>\
  <option value="1028">Latvian</option>\
  <option value="1029">Lithuanian</option>\
  <option value="1102">Malay</option>\
  <option value="1013">Norwegian</option>\
  <option value="1064">Persian</option>\
  <option value="1030">Polish</option>\
  <option value="1014">Portuguese</option>\
  <option value="1032">Romanian</option>\
  <option value="1031">Russian</option>\
  <option value="1035">Serbian</option>\
  <option value="1033">Slovak</option>\
  <option value="1034">Slovenian</option>\
  <option value="1003">Spanish</option>\
  <option value="1015">Swedish</option>\
  <option value="1044">Thai</option>\
  <option value="1037">Turkish</option>\
  <option value="1036">Ukrainian</option>\
  <option value="1041">Urdu</option>\
  <option value="1040">Vietnamese</option>\
  </select>\
  </div>\
  \
  <div class="form-group">\
  <label for="autoCreateGroups_countryCode">Country:</label>\
  <select id="autoCreateGroups_countryCode" class="form-control">\
  <option value="0000" selected>-- All Countries --</option>\
  <option value="2004">Afghanistan</option>\
  <option value="2004">Afghanistan</option>\
  <option value="2008">Albania</option>\
  <option value="2010">Antarctica</option>\
  <option value="2012">Algeria</option>\
  <option value="2016">American Samoa</option>\
  <option value="2020">Andorra</option>\
  <option value="2024">Angola</option>\
  <option value="2028">Antigua and Barbuda</option>\
  <option value="2031">Azerbaijan</option>\
  <option value="2032">Argentina</option>\
  <option value="2036">Australia</option>\
  <option value="2040">Austria</option>\
  <option value="2044">The Bahamas</option>\
  <option value="2048">Bahrain</option>\
  <option value="2050">Bangladesh</option>\
  <option value="2051">Armenia</option>\
  <option value="2052">Barbados</option>\
  <option value="2056">Belgium</option>\
  <option value="2064">Bhutan</option>\
  <option value="2068">Bolivia</option>\
  <option value="2070">Bosnia and Herzegovina</option>\
  <option value="2072">Botswana</option>\
  <option value="2076">Brazil</option>\
  <option value="2084">Belize</option>\
  <option value="2090">Solomon Islands</option>\
  <option value="2096">Brunei</option>\
  <option value="2100">Bulgaria</option>\
  <option value="2108">Burundi</option>\
  <option value="2112">Belarus</option>\
  <option value="2116">Cambodia</option>\
  <option value="2120">Cameroon</option>\
  <option value="2124">Canada</option>\
  <option value="2132">Cape Verde</option>\
  <option value="2140">Central African Republic</option>\
  <option value="2144">Sri Lanka</option>\
  <option value="2148">Chad</option>\
  <option value="2152">Chile</option>\
  <option value="2156">China</option>\
  <option value="2162">Christmas Island</option>\
  <option value="2166">Cocos (Keeling) Islands</option>\
  <option value="2170">Colombia</option>\
  <option value="2174">Comoros</option>\
  <option value="2178">Congo</option>\
  <option value="2180">Democratic Republic of the Congo</option>\
  <option value="2184">Cook Islands</option>\
  <option value="2188">Costa Rica</option>\
  <option value="2191">Croatia</option>\
  <option value="2196">Cyprus</option>\
  <option value="2203">Czech Republic</option>\
  <option value="2204">Benin</option>\
  <option value="2208">Denmark</option>\
  <option value="2212">Dominica</option>\
  <option value="2214">Dominican Republic</option>\
  <option value="2218">Ecuador</option>\
  <option value="2222">El Salvador</option>\
  <option value="2226">Equatorial Guinea</option>\
  <option value="2231">Ethiopia</option>\
  <option value="2232">Eritrea</option>\
  <option value="2233">Estonia</option>\
  <option value="2239">South Georgia and the South Sandwich Islands</option>\
  <option value="2242">Fiji</option>\
  <option value="2246">Finland</option>\
  <option value="2250">France</option>\
  <option value="2258">French Polynesia</option>\
  <option value="2260">French Southern and Antarctic Lands</option>\
  <option value="2262">Djibouti</option>\
  <option value="2266">Gabon</option>\
  <option value="2268">Georgia</option>\
  <option value="2270">The Gambia</option>\
  <option value="2276">Germany</option>\
  <option value="2288">Ghana</option>\
  <option value="2296">Kiribati</option>\
  <option value="2300">Greece</option>\
  <option value="2308">Grenada</option>\
  <option value="2316">Guam</option>\
  <option value="2320">Guatemala</option>\
  <option value="2324">Guinea</option>\
  <option value="2328">Guyana</option>\
  <option value="2332">Haiti</option>\
  <option value="2334">Heard Island and McDonald Islands</option>\
  <option value="2336">Vatican City</option>\
  <option value="2340">Honduras</option>\
  <option value="2348">Hungary</option>\
  <option value="2352">Iceland</option>\
  <option value="2356">India</option>\
  <option value="2360">Indonesia</option>\
  <option value="2368">Iraq</option>\
  <option value="2372">Ireland</option>\
  <option value="2376">Israel</option>\
  <option value="2380">Italy</option>\
  <option value="2384">Cote dIvoire</option>\
  <option value="2388">Jamaica</option>\
  <option value="2392">Japan</option>\
  <option value="2398">Kazakhstan</option>\
  <option value="2400">Jordan</option>\
  <option value="2404">Kenya</option>\
  <option value="2410">South Korea</option>\
  <option value="2414">Kuwait</option>\
  <option value="2417">Kyrgyzstan</option>\
  <option value="2418">Laos</option>\
  <option value="2422">Lebanon</option>\
  <option value="2426">Lesotho</option>\
  <option value="2428">Latvia</option>\
  <option value="2430">Liberia</option>\
  <option value="2434">Libya</option>\
  <option value="2438">Liechtenstein</option>\
  <option value="2440">Lithuania</option>\
  <option value="2442">Luxembourg</option>\
  <option value="2450">Madagascar</option>\
  <option value="2454">Malawi</option>\
  <option value="2458">Malaysia</option>\
  <option value="2462">Maldives</option>\
  <option value="2466">Mali</option>\
  <option value="2470">Malta</option>\
  <option value="2478">Mauritania</option>\
  <option value="2480">Mauritius</option>\
  <option value="2484">Mexico</option>\
  <option value="2492">Monaco</option>\
  <option value="2496">Mongolia</option>\
  <option value="2498">Moldova</option>\
  <option value="2499">Montenegro</option>\
  <option value="2504">Morocco</option>\
  <option value="2508">Mozambique</option>\
  <option value="2512">Oman</option>\
  <option value="2516">Namibia</option>\
  <option value="2520">Nauru</option>\
  <option value="2524">Nepal</option>\
  <option value="2528">Netherlands</option>\
  <option value="2540">New Caledonia</option>\
  <option value="2548">Vanuatu</option>\
  <option value="2554">New Zealand</option>\
  <option value="2558">Nicaragua</option>\
  <option value="2562">Niger</option>\
  <option value="2566">Nigeria</option>\
  <option value="2570">Niue</option>\
  <option value="2574">Norfolk Island</option>\
  <option value="2578">Norway</option>\
  <option value="2580">Northern Mariana Islands</option>\
  <option value="2581">United States Minor Outlying Islands</option>\
  <option value="2583">Micronesia</option>\
  <option value="2584">Marshall Islands</option>\
  <option value="2585">Palau</option>\
  <option value="2586">Pakistan</option>\
  <option value="2591">Panama</option>\
  <option value="2598">Papua New Guinea</option>\
  <option value="2600">Paraguay</option>\
  <option value="2604">Peru</option>\
  <option value="2608">Philippines</option>\
  <option value="2612">Pitcairn Islands</option>\
  <option value="2616">Poland</option>\
  <option value="2620">Portugal</option>\
  <option value="2624">Guinea-Bissau</option>\
  <option value="2626">Timor-Leste</option>\
  <option value="2634">Qatar</option>\
  <option value="2642">Romania</option>\
  <option value="2643">Russia</option>\
  <option value="2646">Rwanda</option>\
  <option value="2654">Saint Helena</option>\
  <option value="2659">Saint Kitts and Nevis</option>\
  <option value="2662">Saint Lucia</option>\
  <option value="2666">Saint Pierre and Miquelon</option>\
  <option value="2670">Saint Vincent and the Grenadines</option>\
  <option value="2674">San Marino</option>\
  <option value="2678">Sao Tome and Principe</option>\
  <option value="2682">Saudi Arabia</option>\
  <option value="2686">Senegal</option>\
  <option value="2688">Serbia</option>\
  <option value="2690">Seychelles</option>\
  <option value="2694">Sierra Leone</option>\
  <option value="2702">Singapore</option>\
  <option value="2703">Slovakia</option>\
  <option value="2704">Vietnam</option>\
  <option value="2705">Slovenia</option>\
  <option value="2706">Somalia</option>\
  <option value="2710">South Africa</option>\
  <option value="2716">Zimbabwe</option>\
  <option value="2724">Spain</option>\
  <option value="2740">Suriname</option>\
  <option value="2748">Swaziland</option>\
  <option value="2752">Sweden</option>\
  <option value="2756">Switzerland</option>\
  <option value="2762">Tajikistan</option>\
  <option value="2764">Thailand</option>\
  <option value="2768">Togo</option>\
  <option value="2772">Tokelau</option>\
  <option value="2776">Tonga</option>\
  <option value="2780">Trinidad and Tobago</option>\
  <option value="2784">United Arab Emirates</option>\
  <option value="2788">Tunisia</option>\
  <option value="2792">Turkey</option>\
  <option value="2795">Turkmenistan</option>\
  <option value="2798">Tuvalu</option>\
  <option value="2800">Uganda</option>\
  <option value="2804">Ukraine</option>\
  <option value="2807">Macedonia (FYROM)</option>\
  <option value="2818">Egypt</option>\
  <option value="2826">United Kingdom</option>\
  <option value="2834">Tanzania</option>\
  <option value="2840">United States</option>\
  <option value="2854">Burkina Faso</option>\
  <option value="2858">Uruguay</option>\
  <option value="2860">Uzbekistan</option>\
  <option value="2862">Venezuela</option>\
  <option value="2876">Wallis and Futuna</option>\
  <option value="2882">Samoa</option>\
  <option value="2887">Yemen</option>\
  <option value="2894">Zambia</option>\
  </select>\
  </div>\
  \
  <div class="form-group">\
  <small><b class="text-danger">*please note that current groups and keywords will be removed</b></small>\
  </div>\
  <div class="lightbox_bottom text-right">\
  <button type="button" id="autoCreateGroups_btn" class="btn btn-primary" style="width: 100px;">OK</button>   \
  <button type="button" id="autoCreateGroups_cancel_btn" class="btn btn-default" style="width: 100px;">Cancel</button>   \
  </div>\
  </div>\
  </div>\
  </div>\
  </div>');

    // Send a request to admin ajax
    jQuery('#autoCreateGroups_btn')
        .click(function(){

            jQuery('#pr-progress-bar-area-infinite').removeClass('hide');

            var seedKeyword = jQuery('#autoCreateGroups_seed').val();
            var minSearchVolume = jQuery('#autoCreateGroups_minSearchVolume').val();
            var minCPC = jQuery('#autoCreateGroups_minCPC').val();
            var languageCode = jQuery('#autoCreateGroups_languageCode').val();
            var countryCode = jQuery('#autoCreateGroups_countryCode').val();

            if (seedKeyword == '') {
                alert('Seed Keyword cannot be empty!');
                return;
            }
            var data = {
                'action': 'gkty_auto_create_groups',
                'seed_keyword':seedKeyword,
                'project_id':projectID,
                'search_volume':minSearchVolume,
                'cpc':minCPC,
                'language':languageCode,
                'country':countryCode
            };
            jQuery.post(ajaxurl, data)
                .done(function(d){
                    if (d.result == 'success') {
                        document.location.reload();
                    } else {
                        alert('An error occured, please try again later!');
                    }
                }, 'json');

            jQuery.fancybox.close();
        });
    // Close the fancybox
    jQuery('#autoCreateGroups_cancel_btn')
        .click(function(){
            jQuery.fancybox.close();
        });
}

function setCookie() {
    var d = new Date();
    d.setTime(d.getTime() + (1*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = "use_aff=1; " + expires;
    document.location.reload();
}

function deleteCookie() {
    document.cookie = 'use_aff=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    document.location.reload();
}

function schemaValidation(data) {

    var v = jQuery('.validation-result');
    var p = jQuery('.pre-output');

    v.css('color','black');
    v.html(' - <i class="fa fa-refresh fa-spin"></i> Working...');

    jQuery.post('/wp-admin/admin-post.php', 'website=' + jQuery('#website_schema').val() + '&action=gkty_schema_validation', function(d){
        if (d.status == 'ERROR') {
            v.css('color','red');
            v.html(' - <i class="fa fa-close"></i> Website does not have Markup Schema present!');
            p.html('N/A');
        } else {
            var json = JSON.parse(d.data);
            json = JSON.stringify(json, null, 4);
            v.css('color','green');
            v.html(' - <i class="fa fa-check"></i> Valid Markup Schema Detected!');
            p.html(json);
        }

    }, 'json');
}