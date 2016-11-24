<?php

?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script type="text/javascript" src="../../inc/tinymce_youtube/youTube.js"></script>
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css">
<script src="https://apis.google.com/js/client.js?onload=googleApiClientReady"></script>
<link rel="stylesheet" href="../../css/tinymce_youtube/youTube.css" />

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/css/bootstrap.css">
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.0/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.0/js/bootstrap-toggle.min.js"></script>

<div id="body" style="margin-bottom: 5px;">

    <div class="row" style="margin-bottom: 15px">
        <div class="col-lg-12">
            <div class="input-group">
                <input type="text" id="queryinput" class="form-control" placeholder="Search for...">
                <span class="input-group-btn">
                  <button class="btn btn-default" id="search_youtube" type="submit" style="padding: 4px 10px;">Search Now!</button>
                </span>
            </div><!-- /input-group -->
        </div>
    </div>

    <div class="row" style="margin-bottom: 15px">
        <div class="col-md-6">
            <div id="search-results-block" style="margin-right: 15px">
                Search results will display here...
            </div>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12" id="video_preview" style="text-align: center">
                    <img id="youtube_iframe" src="../../css/tinymce_youtube/preview.png" title="Preview" />
                </div>
                <div class="col-md-12">
                    <div class="row" style="margin-top: 15px">
                        <div class="col-md-3">
                            Width
                            <br>
                            <input type="number" id="youtube_width" size="2" class="form-control" value="330" />
                        </div>
                        <div class="col-md-3">
                            Height
                            <br>
                            <input type="number" id="youtube_height" size="2" class="form-control" value="230" />
                        </div>
                        <div class="col-md-3">
                            Autoplay
                            <br>
                            <input type="checkbox" data-toggle="toggle" id="youtube_autoplay" data-size="small"/><label id="youtube_autoplay_label" for="youtube_autoplay"></label>
                        </div>
                        <div class="col-md-3">
                            Strip
                            <br>
                            <input type="checkbox" data-toggle="toggle" id="youtube_strip" data-size="small" checked/><label id="youtube_strip_label" for="youtube_strip"></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="input-group col-md-12" style="margin-bottom: 5px;">
            <span class="input-group-addon" id="sizing-addon2">YouTube URL</span>
            <input type="text" id="youtube_url" class="form-control" placeholder="YouTube Url..." />
        </div>
        <div class="input-group col-md-12">
            <span class="input-group-addon" id="sizing-addon2">Title</span>
            <input type="text" id="youtube_title" size="80" class="form-control" placeholder="Title..." readonly/>
        </div>
    </div>

</div>
<button id="youtube_cancel" class="btn btn-default">Cancel</button> <button id="youtube_insert" class="btn btn-primary">Insert and Close</button>

<style>
    body {
        font-family: 'Open Sans', Tahoma, Arial, sans-serif !important;
    }
</style>