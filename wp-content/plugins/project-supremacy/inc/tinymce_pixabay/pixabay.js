jQuery(document).ready(function($) {

    $( window ).load(function() {
        initialize();
    });

    var geocoder;
    var map;
    var address ="San Diego, CA";
    function initialize() {
        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(-34.397, 150.644);
        var myOptions = {
            zoom: 8,
            center: latlng,
            mapTypeControl: true,
            mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
            navigationControl: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
        if (geocoder) {
            geocoder.geocode( { 'address': address}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
                        map.setCenter(results[0].geometry.location);

                        var infowindow = new google.maps.InfoWindow(
                            { content: '<b>'+address+'</b>',
                                size: new google.maps.Size(150,50)
                            });

                        var marker = new google.maps.Marker({
                            position: results[0].geometry.location,
                            map: map,
                            title:address
                        });

                        // populate the form fields with lat & lng
                        $('#latbox').val(results[0].geometry.location.lat());
                        $('#lngbox').val(results[0].geometry.location.lng());

                        google.maps.event.addListener(map, 'click', function(event) {

                            if (marker) {
                                marker.setMap(null);
                                marker = null;
                            }

                            var myLatLng = event.latLng ;

                            marker = new google.maps.Marker({
                                position: myLatLng,
                                map: map,
                                title:"Property Location"
                            });

                            // populate the form fields with lat & lng
                            $('#latbox').val(event.latLng.lat());
                            $('#lngbox').val(event.latLng.lng());

                        });

                    } else {
                        alert("No results found");
                    }
                } else {
                    alert("Geocode was not successful for the following reason: " + status);
                }
            });
        }
    }

    /////////////////////////////////////////////// GOOGLOOOO //////////////////////////////////////////////////////////

    var args = top.tinymce.activeEditor.windowManager.getParams();

    if (args.PixabayAPI == "false" || args.PixabayUsername == "false") {
        $('.api_no').show();
    }else{
        $('.api_yes').show();
    }

    $('#form_search').submit(function (e) {
        e.preventDefault();
        search_term = $('#queryinput').val();
        SearchPixabay(search_term);
    });

    $('#form_search_geo').submit(function (e) {
        e.preventDefault();
        address = $('#queryinput_geo').val();
        initialize();
    });

    $(document).on('click', '#test_container', function() {
        get_image_details();
    });

    function get_image_details(){
        setTimeout(function(){
            var debugID = $('.p.pav').parent("div").attr('debug-id');
            var image = $('#loaded_images[debug-id="'+debugID+'"]').children('img[u="image"]').attr('src');

            var image_full_name = image.replace(/.+\//g, "");

            $('#image_name').val(image_full_name.split(".")[0]);
            $('#image_ext').val(image_full_name.split(".")[1]);
        }, 1000);
    }

    $('#pixabay_insert').click(function (){

        $('#pixabay_insert').prop('disabled', true);
        $('#pixabay_cancel').prop('disabled', true);

        var debugID = $('.p.pav').parent("div").attr('debug-id');
        var image = $('#loaded_images[debug-id="'+debugID+'"]').children('img[u="image"]').attr('src');

        var new_image_name = $('#image_name').val() + '.' + $('#image_ext').val();

        var data_img = {
            action: 'gkty_get_images',
            image_url: image,
            new_name: new_image_name
        };

        jQuery.post(args.ajaxurl, data_img).done(function(d){

            if (d.status == "OK") {

                $('#pixabay_insert').prop('disabled', false);
                $('#pixabay_cancel').prop('disabled', false);

                if ($('#geo_on_off').is(':checked')) {
                    var data_geo = {
                        action: 'gkty_geo_location',
                        lat: $('#latbox').val(),
                        lon: $('#lngbox').val(),
                        desc: $('#g_desc').val(),
                        filename: '/wp-content/uploads/' + d.attach_data.file
                    };
                    jQuery.post(args.ajaxurl, data_geo).done(function(dData){

                        var final_link ='<a href="/wp-content/uploads/'+ d.attach_data.file+'">'+
                            '<img class="alignnone size-medium wp-image-' + d.attach_id + '" src="/wp-content/uploads/'+ d.attach_data.file+'" alt="'+ d.attach_data.sizes.medium.file+'" width="300" height="200" />'+
                            '</a>';
                        top.tinymce.activeEditor.execCommand('mceInsertContent', !1, final_link);  // Insert content into editor
                        top.tinymce.activeEditor.windowManager.close();  // Close window

                    })
                }else{
                    var final_link ='<a href="/wp-content/uploads/'+ d.attach_data.file+'">'+
                        '<img class="alignnone size-medium wp-image-' + d.attach_id + '" src="/wp-content/uploads/'+ d.attach_data.file+'" alt="'+ d.attach_data.sizes.medium.file+'" width="300" height="200" />'+
                        '</a>';
                    top.tinymce.activeEditor.execCommand('mceInsertContent', !1, final_link);  // Insert content into editor
                    top.tinymce.activeEditor.windowManager.close();  // Close window
                }

            }

            if (d.status == "ERROR") {
                $('#pixabay_insert').prop('disabled', false);
                $('#pixabay_cancel').prop('disabled', false);
                alert(d.message);
            }

        });
    });

    $('#pixabay_cancel').click(function () {
        top.tinymce.activeEditor.windowManager.close();  // Close window
    });

    function SearchPixabay(search_term) {

        $('#search_pixabay').prop('disabled', true);

        var data = {
            action: 'gkty_search_images',
            q : $('#queryinput').val(),
            min_width : $('#pixabay_width').val(),
            min_height : $('#pixabay_height').val(),
            order : $('#order').val(),
            image_type : $('#image_type').val(),
            orientation : $('#orientation').val(),
            lang : $('#lang').val(),
            per_page : 20,
            page : 1
        };


        jQuery.post(args.ajaxurl, data).done(function(d){

            var html = '';
            $('#search_pixabay').prop('disabled', false);

            if (d.hits.length < 1) {
                $('#images_new_container').html('<p>No Images found</p>');
                $('#image_name').val('');
                $('#image_ext').val('');
            }else{
                $('#images_new_container').html('<div id="slider1_container" style="position: relative; top: 0px; left: 75px; width: 600px; height: 300px;">\
                                                    <div u="slides" id="test_container" style="cursor: move; position: absolute; overflow: hidden; left: 0px; top: 0px; width: 600px; height: 300px;">\
                                                    </div>\
                                                 </div>');

                for (var i = 0; i < d.hits.length; i++) {
                    var obj = d.hits[i];
                    html += '<div id="loaded_images">' +
                        '<img u="image" src="'+obj.webformatURL+'" />' +
                        '<img u="thumb" src="'+obj.previewURL+'"/>' +
                        '</div>';
                }

                html += '<div u="thumbnavigator" class="jssort01" style="left: -100px; bottom: -10px;">'+
                            '<div u="slides" style="cursor: default;">'+
                                '<div u="prototype" class="p">'+
                                    '<div class=w><div u="thumbnailtemplate" class="t"></div></div>'+
                                    '<div class=c></div>'+
                                '</div>'+
                            '</div>'+
                        '</div>';

                $('#test_container').html(html);

                init_jssor_slider1 = function (containerId) {
                    var options = {
                        $AutoPlay: false,                                   //[Optional] Whether to auto play, to enable slideshow, this option must be set to true, default value is false
                        $SlideDuration: 500,                                //[Optional] Specifies default duration (swipe) for slide in milliseconds, default value is 500

                        $ThumbnailNavigatorOptions: {                       //[Optional] Options to specify and enable thumbnail navigator or not
                            $Class: $JssorThumbnailNavigator$,              //[Required] Class to create thumbnail navigator instance
                            $ChanceToShow: 2,                               //[Required] 0 Never, 1 Mouse Over, 2 Always

                            $ActionMode: 1,                                 //[Optional] 0 None, 1 act by click, 2 act by mouse hover, 3 both, default value is 1
                            $SpacingX: 8,                                   //[Optional] Horizontal space between each thumbnail in pixel, default value is 0
                            $DisplayPieces: 10,                             //[Optional] Number of pieces to display, default value is 1
                            $ParkingPosition: 360                           //[Optional] The offset position to park thumbnail
                        }
                    };

                    var jssor_slider1 = new $JssorSlider$(containerId, options);
                };

                init_jssor_slider1("slider1_container");

                get_image_details();
            }

        }, 'json');
    }
});