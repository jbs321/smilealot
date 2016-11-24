<?php

?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script type="text/javascript" src="../../inc/tinymce_pixabay/pixabay.js"></script>
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<!--<script src="https://apis.google.com/js/client.js?onload=googleApiClientReady"></script>-->
<link rel="stylesheet" href="../../css/tinymce_youtube/youTube.css" />

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/css/bootstrap.css">
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.0/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.0/js/bootstrap-toggle.min.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<!--<script src="https://cdn.jsdelivr.net/jssor.slider/19.0.0/jssor.slider.mini.js"></script>-->

<script type="text/javascript" src="jssor.js"></script>
<script type="text/javascript" src="jssor.slider.js"></script>

<script type="text/javascript">


</script>


<div id="body" class="api_yes" style="display: none">
    <div class="panel panel-primary">
        <div class="panel-heading" style="border-radius: 5px 5px 0px 0px;">
            <h3 class="panel-title"><i class="fa fa-search"></i> Search Pixabay images</h3>
        </div>
        <div class="panel-body">
            <div class="row" style="margin-bottom: 15px">
                <div class="col-lg-12">
                    <form id="form_search" style="margin-bottom: -10px;">
                        <div class="input-group">
                            <input type="text" id="queryinput" class="form-control" placeholder="Search for...">
                            <span class="input-group-btn">
                                <button class="btn btn-default" id="search_pixabay" type="submit" style="padding: 4px 10px;">Search Now!</button>
                            </span>
                        </div><!-- /input-group -->
                    </form>
                </div>
            </div>
            <div class="row" style="margin-bottom: 15px">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-md-2">
                            Min Width
                            <br>
                            <input type="number" id="pixabay_width" size="2" class="form-control" value="0" />
                        </div>
                        <div class="col-md-2">
                            Min Height
                            <br>
                            <input type="number" id="pixabay_height" size="2" class="form-control" value="0" />
                        </div>
                        <div class="col-md-2">
                            Order
                            <br>
                            <select class="form-control" id="order">
                                <option value="popular">Popular</option>
                                <option value="latest">Latest</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            Type
                            <br>
                            <select class="form-control" id="image_type">
                                <option value="all">All</option>
                                <option value="photo">Photo</option>
                                <option value="illustration">Illustration</option>
                                <option value="vector">Vector</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            Orientation
                            <br>
                            <select class="form-control" id="orientation">
                                <option value="all">All</option>
                                <option value="horizontal">Horizontal</option>
                                <option value="vertical">Vertical</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            Language
                            <br>
                            <select class="form-control" id="lang">
                                <option value="en">en</option>
                                <option value="cs">cs</option>
                                <option value="da">da</option>
                                <option value="de">de</option>
                                <option value="es">es</option>
                                <option value="fr">fr</option>
                                <option value="id">id</option>
                                <option value="it">it</option>
                                <option value="hu">hu</option>
                                <option value="nl">nl</option>
                                <option value="no">no</option>
                                <option value="pl">pl</option>
                                <option value="pt">pt</option>
                                <option value="ro">ro</option>
                                <option value="sk">sk</option>
                                <option value="fi">fi</option>
                                <option value="sv">sv</option>
                                <option value="tr">tr</option>
                                <option value="vi">vi</option>
                                <option value="th">th</option>
                                <option value="bg">bg</option>
                                <option value="ru">ru</option>
                                <option value="el">el</option>
                                <option value="ja">ja</option>
                                <option value="ko">ko</option>
                                <option value="zh">zh</option>
                            </select>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row" id="images">
                <div class="col-md-12" id="images_new_container">

                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading" style="border-radius: 5px 5px 0px 0px;">
            <h3 class="panel-title"><i class="fa fa-pencil"></i> Image Details</h3>
        </div>
        <div class="panel-body" style="margin-bottom: 20px;">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info" role="alert">
                        <p>
                            <b><i class="fa fa-info-circle"></i></b>
                            Here you can change selected image name. Image extension is not changeable.
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    Image Name:
                    <br>
                    <input type="text" id="image_name" class="form-control" placeholder="Image name...">
                </div>
                <div class="col-md-6">
                    Image Extension:
                    <br>
                    <input type="text" id="image_ext" class="form-control" placeholder="jpeg,tiff..." disabled>
                </div>
            </div>
        </div>
    </div>


    <div class="panel panel-primary">
        <div class="panel-heading" style="border-radius: 5px 5px 0px 0px;">
            <h3 class="panel-title"><i class="fa fa-map-marker"></i> EXIF Geo Location</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info" role="alert">
                        <p>
                            <b><i class="fa fa-info-circle"></i></b>
                            For better SEO optimization, here you can change image metadata (Image Geo Location and Image Description). <b>This can be only applied to JPEG and TIFF images!</b>
                            <br>If you want to enable this option move slider Enable GEO -> YES!
                        </p>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-bottom: 25px">
                <div class="col-lg-12">
                    <form id="form_search_geo" style="margin-bottom: -10px;">
                        <div class="input-group">
                            <input type="text" id="queryinput_geo" class="form-control" placeholder="eg. 2 E 84th St, New York, NY 10028, USA">
                            <span class="input-group-btn">
                                <button class="btn btn-default" id="search_pixabay_geo" type="submit" style="padding: 4px 10px;">Search Location</button>
                            </span>
                        </div><!-- /input-group -->
                    </form>
                </div>
            </div>

            <div id="map_canvas"></div>

            <div class="row" style="margin-top: 20px">
                <div id="latlong">
                    <div class="col-md-3">
                        Latitude:
                        <br>
                        <input type="text" id="latbox" name="lat" style="width: 100%" disabled>
                    </div>
                    <div class="col-md-3">
                        Longitude:
                        <br>
                        <input type="text" id="lngbox" name="lng" style="width: 100%" disabled>
                    </div>
                    <div class="col-md-3">
                        Description:
                        <br>
                        <input type="text" id="g_desc" name="g_desc" style="width: 100%">
                    </div>
                    <div class="col-md-3">
                        Enable GEO:
                        <br>
                        <input type="checkbox" id="geo_on_off" data-toggle="toggle" data-size="small">
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>

<div id="body" class="api_no" style="display: none">
<p>Please provide UserName and API key for Pixabay</p>
</div>

<div style="float: right;padding: 10px">
    <button id="pixabay_cancel" class="btn btn-default">Cancel</button>
    <button id="pixabay_insert" class="btn btn-primary">Insert and Close</button>
</div>


<style>
    body {
        font-family: 'Open Sans', Tahoma, Arial, sans-serif !important;
    }

    #images_new_container {
        height: 320px;
    }
    .jssort01 {
        position: absolute;
        /* size of thumbnail navigator container */
        width: 800px;
        height: 100px;
    }

    .jssort01 .p {
        position: absolute;
        top: 0;
        left: 0;
        width: 72px;
        height: 72px;
    }

    .jssort01 .t {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
    }

    .jssort01 .w {
        position: absolute;
        top: 0px;
        left: 0px;
        width: 100%;
        height: 100%;
    }

    .jssort01 .c {
        position: absolute;
        top: 0px;
        left: 0px;
        width: 68px;
        height: 68px;
        border: #000 2px solid;
        box-sizing: content-box;
        background: url(t01.png) -800px -800px no-repeat;
        _background: none;
    }

    .jssort01 .pav .c {
        top: 2px;
        _top: 0px;
        left: 2px;
        _left: 0px;
        width: 68px;
        height: 68px;
        border: #000 0px solid;
        _border: #fff 2px solid;
        background-position: 50% 50%;
    }

    .jssort01 .p:hover .c {
        top: 0px;
        left: 0px;
        width: 70px;
        height: 70px;
        border: #fff 1px solid;
        background-position: 50% 50%;
    }

    .jssort01 .p.pdn .c {
        background-position: 50% 50%;
        width: 68px;
        height: 68px;
        border: #000 2px solid;
    }

    * html .jssort01 .c, * html .jssort01 .pdn .c, * html .jssort01 .pav .c {
        /* ie quirks mode adjust */
        width /**/: 72px;
        height /**/: 72px;
    }

    a.accordion-toggle {
        color: #0060B6;
        text-decoration: none;
    }

    a.accordion-toggle:hover
    {
        color:#00A0C6;
        text-decoration:none;
        cursor:pointer;
    }

    #map_canvas {
        width: 100%;
        height: 200px;
    }
    /******************************************************************************************************************/

    .panel-title {
        font-size: 20px;
        padding: 5px 15px;
        color: white;
    }
</style>