<?php
//*************************************************************
// Get Variables
//*************************************************************
$BG         = isset( $_GET['BG']         ) ? $_GET['BG']         : "#444444";
$Text       = isset( $_GET['Text']       ) ? $_GET['Text']       : "#FFFFFF";
$BG_Hover   = isset( $_GET['BG_Hover']   ) ? $_GET['BG_Hover']   : "#444444";
$Text_Hover = isset( $_GET['Text_Hover'] ) ? $_GET['Text_Hover'] : "#FFFFFF";

header("Content-type: text/css; charset: UTF-8");
?>
/**********************************************************
// Social >> Resets
**********************************************************/
#Social-Sidebar ol, 
#Social-Sidebar ul {
	margin: 0;
	padding: 0;
	list-style: none;
	}

#Social-Sidebar a,
#Social-Sidebar a:hover,
#Social-Sidebar a:active { text-decoration: none; }
#Social-Sidebar a img { border: none; }

#Social-Sidebar span {
	-webkit-box-sizing: content-box;
	   -moz-box-sizing: content-box;
	        box-sizing: content-box;
	}

/**********************************************************
// Social >> Font Icons
**********************************************************/
@font-face {
	font-family: "Social-Icons";
	src:url("../Fonts/Social-Icons.eot");
	src:url("../Fonts/Social-Icons.eot?#iefix") format("embedded-opentype"),
		url("../Fonts/Social-Icons.ttf") format("truetype"),
		url("../Fonts/Social-Icons.woff") format("woff"),
		url("../Fonts/Social-Icons.svg#Social-Icons") format("svg");
	font-weight: normal;
	font-style: normal;
	}
	
@media screen and (-webkit-min-device-pixel-ratio:0) {
	@font-face {
		font-family: "Social-Icons";
		src: url("../Fonts/Social-Icons.svg#Social-Icons") format("svg");
		}
}

/**********************************************************
// CSS Fix Point:
// For sites with normalized font-size, 
// change line-height from 1 to 1.9 or 2
**********************************************************/
#Social-Sidebar a:before {
	font-family: "Social-Icons";
	speak: none;
	font-style: normal;
	font-weight: normal;
	font-variant: normal;
	text-transform: none;
	line-height: 1;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
	text-align: center;
	}

#Social-Sidebar a.Aid:before           { content: "\e657"; }
#Social-Sidebar a.Android:before       { content: "\e639"; }
#Social-Sidebar a.Apple:before         { content: "\e602"; }

#Social-Sidebar a.Behance:before       { content: "\e67e"; }
#Social-Sidebar a.Blogger:before       { content: "\e641"; }
#Social-Sidebar a.Blogger-2:before     { content: "\e640"; }
#Social-Sidebar a.Bookmark:before      { content: "\e659"; }
#Social-Sidebar a.Bookmark-2:before    { content: "\e65a"; }
#Social-Sidebar a.Bubbles:before       { content: "\e64f"; }
#Social-Sidebar a.Bubbles-2:before     { content: "\e650"; }
#Social-Sidebar a.Bullhorn:before      { content: "\e63b"; }

#Social-Sidebar a.Calendar:before      { content: "\e66a"; }
#Social-Sidebar a.Cart:before          { content: "\e666"; }
#Social-Sidebar a.Cart-2:before        { content: "\e667"; }
#Social-Sidebar a.Cart-3:before        { content: "\e668"; }
#Social-Sidebar a.CC:before            { content: "\e681"; }
#Social-Sidebar a.Circles:before       { content: "\e67f"; }
#Social-Sidebar a.Cloud-Down:before    { content: "\e66e"; }
#Social-Sidebar a.Cloud-Up:before      { content: "\e66f"; }
#Social-Sidebar a.Cog:before           { content: "\e655"; }
#Social-Sidebar a.Cog-2:before         { content: "\e656"; }
#Social-Sidebar a.CSS3:before          { content: "\e65f"; }

#Social-Sidebar a.Delicious:before     { content: "\e643"; }
#Social-Sidebar a.Deviantart:before    { content: "\e625"; }
#Social-Sidebar a.Deviantart-2:before  { content: "\e626"; }
#Social-Sidebar a.Dribbble:before      { content: "\e620"; }
#Social-Sidebar a.Dribbble-2:before    { content: "\e621"; }
#Social-Sidebar a.Dribbble-3:before    { content: "\e622"; }
#Social-Sidebar a.Dropbox:before       { content: "\e679"; }

#Social-Sidebar a.Evernote:before      { content: "\e677"; }

#Social-Sidebar a.Facebook:before      { content: "\e61c"; }
#Social-Sidebar a.Facebook-2:before    { content: "\e61b"; }
#Social-Sidebar a.Facebook-3:before    { content: "\e61a"; }
#Social-Sidebar a.File-Down:before     { content: "\e670"; }
#Social-Sidebar a.File-Up:before       { content: "\e671"; }
#Social-Sidebar a.Flag:before          { content: "\e672"; }
#Social-Sidebar a.Flattr:before        { content: "\e64b"; }
#Social-Sidebar a.Flickr:before        { content: "\e60c"; }
#Social-Sidebar a.Flickr-2:before      { content: "\e60b"; }
#Social-Sidebar a.Flickr-3:before      { content: "\e60a"; }
#Social-Sidebar a.Flickr-4:before      { content: "\e61d"; }
#Social-Sidebar a.Forrst:before        { content: "\e623"; }
#Social-Sidebar a.Forrst-2:before      { content: "\e624"; }
#Social-Sidebar a.Foursquare:before    { content: "\e64d"; }
#Social-Sidebar a.Foursquare-2:before  { content: "\e64c"; }

#Social-Sidebar a.Github:before        { content: "\e62b"; }
#Social-Sidebar a.Github-2:before      { content: "\e62a"; }
#Social-Sidebar a.Github-3:before      { content: "\e629"; }
#Social-Sidebar a.Github-4:before      { content: "\e62c"; }
#Social-Sidebar a.Github-5:before      { content: "\e62d"; }
#Social-Sidebar a.Google:before        { content: "\e604"; }
#Social-Sidebar a.Google-Drive:before  { content: "\e609"; }
#Social-Sidebar a.GPlus:before         { content: "\e605"; }
#Social-Sidebar a.GPlus-2:before       { content: "\e606"; }
#Social-Sidebar a.GPlus-3:before       { content: "\e607"; }
#Social-Sidebar a.GPlus-4:before       { content: "\e608"; }

#Social-Sidebar a.Heart:before         { content: "\e65b"; }
#Social-Sidebar a.Home:before          { content: "\e662"; }
#Social-Sidebar a.Home-2:before        { content: "\e663"; }
#Social-Sidebar a.HTML5:before         { content: "\e65e"; }
#Social-Sidebar a.HTML5-2:before       { content: "\e661"; }

#Social-Sidebar a.Instagram:before     { content: "\e619"; }
#Social-Sidebar a.Instagram-2:before   { content: "\e67a"; }

#Social-Sidebar a.Joomla:before        { content: "\e642"; }

#Social-Sidebar a.Lab:before           { content: "\e66c"; }
#Social-Sidebar a.Lanyrd:before        { content: "\e60d"; }
#Social-Sidebar a.LastFM:before        { content: "\e630"; }
#Social-Sidebar a.LastFM-2:before      { content: "\e631"; }
#Social-Sidebar a.Lightning:before     { content: "\e66b"; }
#Social-Sidebar a.LinkedIn:before      { content: "\e632"; }
#Social-Sidebar a.Linux:before         { content: "\e601"; }

#Social-Sidebar a.Mail:before          { content: "\e63c"; }
#Social-Sidebar a.Mail-2:before        { content: "\e603"; }
#Social-Sidebar a.Mail-3:before        { content: "\e600"; }
#Social-Sidebar a.Mail-4:before        { content: "\e674"; }
#Social-Sidebar a.Mixi:before          { content: "\e67d"; }

#Social-Sidebar a.Paypal:before        { content: "\e64e"; }
#Social-Sidebar a.Paypal-2:before      { content: "\e660"; }
#Social-Sidebar a.Phone:before         { content: "\e63a"; }
#Social-Sidebar a.Phone-2:before       { content: "\e673"; }
#Social-Sidebar a.Picassa:before       { content: "\e61e"; }
#Social-Sidebar a.Picassa-2:before     { content: "\e61f"; }
#Social-Sidebar a.Pinterest:before     { content: "\e647"; }
#Social-Sidebar a.Pinterest-2:before   { content: "\e648"; }
#Social-Sidebar a.Power:before         { content: "\e658"; }

#Social-Sidebar a.QQ:before            { content: "\e678"; }

#Social-Sidebar a.Rdio:before          { content: "\e675"; }
#Social-Sidebar a.Reddit:before        { content: "\e633"; }
#Social-Sidebar a.RenRen:before        { content: "\e67b"; }
#Social-Sidebar a.Rocket:before        { content: "\e66d"; }
#Social-Sidebar a.RSS:before           { content: "\e614"; }
#Social-Sidebar a.RSS-2:before         { content: "\e615"; }
#Social-Sidebar a.RSS-3:before         { content: "\e613"; }

#Social-Sidebar a.Share:before         { content: "\e664"; }
#Social-Sidebar a.SinaWeibo:before     { content: "\e67c"; }
#Social-Sidebar a.Skype:before         { content: "\e634"; }
#Social-Sidebar a.Soundcloud:before    { content: "\e636"; }
#Social-Sidebar a.Soundcloud-2:before  { content: "\e635"; }
#Social-Sidebar a.Spotify:before       { content: "\e676"; }
#Social-Sidebar a.Stackoverflow:before { content: "\e646"; }
#Social-Sidebar a.Star:before          { content: "\e65c"; }
#Social-Sidebar a.Steam:before         { content: "\e627"; }
#Social-Sidebar a.Steam-2:before       { content: "\e628"; }
#Social-Sidebar a.Stumbleupon:before   { content: "\e645"; }
#Social-Sidebar a.Stumbleupon-2:before { content: "\e644"; }
#Social-Sidebar a.Support:before       { content: "\e669"; }

#Social-Sidebar a.Tag:before           { content: "\e665"; }
#Social-Sidebar a.Thumbs-Up:before     { content: "\e65d"; }
#Social-Sidebar a.Tumblr:before        { content: "\e63f"; }
#Social-Sidebar a.Tumblr-2:before      { content: "\e63e"; }
#Social-Sidebar a.Twitter:before       { content: "\e618"; }
#Social-Sidebar a.Twitter-2:before     { content: "\e617"; }
#Social-Sidebar a.Twitter-3:before     { content: "\e616"; }

#Social-Sidebar a.User:before          { content: "\e652"; }
#Social-Sidebar a.User-2:before        { content: "\e654"; }
#Social-Sidebar a.Users:before         { content: "\e653"; }

#Social-Sidebar a.Vimeo:before         { content: "\e610"; }
#Social-Sidebar a.Vimeo-2:before       { content: "\e60f"; }
#Social-Sidebar a.Vimeo-3:before       { content: "\e60e"; }
#Social-Sidebar a.VK:before            { content: "\e680"; }

#Social-Sidebar a.Windows:before       { content: "\e638"; }
#Social-Sidebar a.Windows8:before      { content: "\e637"; }
#Social-Sidebar a.Wordpress:before     { content: "\e62e"; }
#Social-Sidebar a.Wordpress-2:before   { content: "\e62f"; }

#Social-Sidebar a.Xing:before          { content: "\e649"; }
#Social-Sidebar a.Xing-2:before        { content: "\e64a"; }

#Social-Sidebar a.Yahoo:before         { content: "\e63d"; }
#Social-Sidebar a.Yelp:before          { content: "\e651"; }
#Social-Sidebar a.Youtube:before       { content: "\e612"; }
#Social-Sidebar a.Youtube-2:before     { content: "\e611"; }

/**********************************************************
// Social Sidebar
**********************************************************/
#Social-Sidebar {
	position: fixed;
	font-family: Arial, Verdana, sans-serif;
	z-index: 1000;
	top: 25%;
	}

#Social-Sidebar ul li a {
	width: 35px;
	height: 35px;
	line-height: 40px;
	font-size: 19px;
	text-align: center;
	position: relative;
	display: block;
	background: <?php echo $BG; ?>;
	color: <?php echo $Text; ?>;
	}
	
#Social-Sidebar ul li a:hover span {
	-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
	filter: alpha(opacity=100);
	opacity: 1;
	}
	
#Social-Sidebar ul li a:hover,
#Social-Sidebar ul li a span,
#Social-Sidebar ul li a span:before { background: <?php echo $BG; ?>; }
	
#Social-Sidebar ul li a span {
	width: 0;
	-webkit-transition: opacity .3s, left .4s, right .4s, top .4s, bottom .4s;
	   -moz-transition: opacity .3s, left .4s, right .4s, top .4s, bottom .4s;
	    -ms-transition: opacity .3s, left .4s, right .4s, top .4s, bottom .4s;
	     -o-transition: opacity .3s, left .4s, right .4s, top .4s, bottom .4s;
	        transition: opacity .3s, left .4s, right .4s, top .4s, bottom .4s, width .4s;
	-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
	filter: alpha(opacity=0);
	white-space: nowrap;
	opacity: 0;
	position: absolute;
	z-index: -1;
	}
	
/**********************************************************
// Social Sidebar >> Label: Square
**********************************************************/
#Social-Sidebar.Label-Square ul li a span {
	margin-left: -5px;
	padding: 0 8px 0 13px;
	min-width: 80px;
	width: auto;
	height: inherit;
	line-height: 35px;
	font-size: 15px;
	}
	
#Social-Sidebar.Label-Square.Pos-Right ul li a span {
	margin-right: -5px;
	padding: 0 13px 0 8px;
	}
	
#Social-Sidebar.Label-Square.Large ul li a span {
	line-height: 52px;
	font-size: 17px;
	}
	
#Social-Sidebar.Label-Square.Pos-Left  ul li a span { text-align: left;  }
#Social-Sidebar.Label-Square.Pos-Right ul li a span { text-align: right; }

#Social-Sidebar.Label-Square.Pos-Left.Circle ul li a span {
	margin-left: -16px;
	padding-left: 24px;
	}
	
#Social-Sidebar.Label-Square.Pos-Right.Circle ul li a span {
	margin-right: -16px;
	padding-right: 24px;
	}
	
#Social-Sidebar.Label-Square.Pos-Left.Circle.Large ul li a span {
	margin-left: -24px;
	padding-left: 32px;
	}

#Social-Sidebar.Label-Square.Pos-Right.Circle.Large ul li a span {
	margin-right: -24px;
	padding-right: 32px;
	}
	
/**********************************************************
// Social Sidebar >> Label: Curve
**********************************************************/
#Social-Sidebar.Label-Curve ul li a span {
	margin-left: -5px;
	padding: 0 8px 0 13px;
	min-width: 80px;
	width: auto;
	height: inherit;
	line-height: 35px;
	font-size: 15px;
	-webkit-border-radius: 0 5px 5px 0;
	   -moz-border-radius: 0 5px 5px 0;
			border-radius: 0 5px 5px 0;
	}
	
#Social-Sidebar.Label-Curve.Pos-Right ul li a span {
	margin-right: -5px;
	padding: 0 13px 0 8px;
	-webkit-border-radius: 5px 0 0 5px;
	   -moz-border-radius: 5px 0 0 5px;
			border-radius: 5px 0 0 5px;
	}

#Social-Sidebar.Label-Curve.Large ul li a span {
	line-height: 52px;
	font-size: 17px;
	}

#Social-Sidebar.Label-Curve.Pos-Left  ul li a span { text-align: left;  }
#Social-Sidebar.Label-Curve.Pos-Right ul li a span { text-align: right; }

#Social-Sidebar.Label-Curve.Pos-Left.Circle ul li a span {
	margin-left: -16px;
	padding-left: 24px;
	}

#Social-Sidebar.Label-Curve.Pos-Right.Circle ul li a span {
	margin-right: -16px;
	padding-right: 24px;
	}

#Social-Sidebar.Label-Curve.Pos-Left.Circle.Large ul li a span {
	margin-left: -24px;
	padding-left: 32px;
	}

#Social-Sidebar.Label-Curve.Pos-Right.Circle.Large ul li a span {
	margin-right: -24px;
	padding-right: 32px;
	}
	
/**********************************************************
// Social Sidebar >> Label: Round
**********************************************************/
#Social-Sidebar.Label-Round ul li a span {
	margin-left: -5px;
	padding: 0 8px 0 13px;
	min-width: 80px;
	width: auto;
	height: inherit;
	line-height: 35px;
	font-size: 15px;
	-webkit-border-radius: 0 25px 25px 0;
	   -moz-border-radius: 0 25px 25px 0;
			border-radius: 0 25px 25px 0;
	}
	
#Social-Sidebar.Label-Round.Pos-Right ul li a span {
	margin-right: -5px;
	padding: 0 13px 0 8px;
	-webkit-border-radius: 25px 0 0 25px;
	   -moz-border-radius: 25px 0 0 25px;
			border-radius: 25px 0 0 25px;
	}
	
#Social-Sidebar.Label-Round.Large ul li a span {
	line-height: 52px;
	font-size: 17px;
	}

#Social-Sidebar.Label-Round.Pos-Left  ul li a span { text-align: left;  }
#Social-Sidebar.Label-Round.Pos-Right ul li a span { text-align: right; }

#Social-Sidebar.Label-Round.Pos-Left.Circle ul li a span {
	margin-left: -16px;
	padding-left: 24px;
	}

#Social-Sidebar.Label-Round.Pos-Right.Circle ul li a span {
	margin-right: -16px;
	padding-right: 24px;
	}

#Social-Sidebar.Label-Round.Pos-Left.Circle.Large ul li a span {
	margin-left: -24px;
	padding-left: 32px;
	}

#Social-Sidebar.Label-Round.Pos-Right.Circle.Large ul li a span {
	margin-right: -24px;
	padding-right: 32px;
	}

/**********************************************************
// Social Sidebar >> Label: Fancy
**********************************************************/
#Social-Sidebar.Label-Fancy ul li a span {
	margin-top: -16px;
	padding: 4px 8px;
	min-width: 80px;
	width: auto;
	line-height: 24px;
	font-size: 14px;
	-webkit-border-radius: 3px;
	   -moz-border-radius: 3px;
			border-radius: 3px;
	top: 50%;
	}

#Social-Sidebar.Label-Fancy ul li a span:before {
	margin-top: -4px;
	width: 8px;
	height: 8px;
	content: "";
	display: block;
	-webkit-transform: rotate(45deg);
	   -moz-transform: rotate(45deg);
		-ms-transform: rotate(45deg);
		 -o-transform: rotate(45deg);
			transform: rotate(45deg);
	position: absolute;
	z-index: -2;
	top: 50%;
	}
	
#Social-Sidebar.Label-Fancy.Pos-Left  ul li a:hover span { left:  130%; }
#Social-Sidebar.Label-Fancy.Pos-Right ul li a:hover span { right: 130%; }
	
/**********************************************************
// Social Sidebar >> Large
**********************************************************/
#Social-Sidebar.Large ul li a {
	width: 50px;
	height: 50px;
	line-height: 57px;
	font-size: 25px;
	}

/**********************************************************
// Social Sidebar >> Position: Left
**********************************************************/
#Social-Sidebar.Pos-Left { left: 0; }

#Social-Sidebar.Pos-Left ul li a span        { left: -600%; }
#Social-Sidebar.Pos-Left ul li a:hover span  { left: 100%;  }
#Social-Sidebar.Pos-Left ul li a span:before { left: -4px;  }

/**********************************************************
// Social Sidebar >> Position: Right
**********************************************************/
#Social-Sidebar.Pos-Right { right: 0; }

#Social-Sidebar.Pos-Right ul li a span        { right: -600%; }
#Social-Sidebar.Pos-Right ul li a:hover span  { right: 100%;  }
#Social-Sidebar.Pos-Right ul li a span:before { right: -4px;  }

/**********************************************************
// Social Sidebar >> Corners
**********************************************************/
#Social-Sidebar.Corners.Pos-Left ul li:first-child a {
	-webkit-border-radius: 0 5px 0 0;
	   -moz-border-radius: 0 5px 0 0;
			border-radius: 0 5px 0 0;
	}

#Social-Sidebar.Corners.Pos-Left ul li:last-child a {	
	-webkit-border-radius: 0 0 5px 0;
	   -moz-border-radius: 0 0 5px 0;
			border-radius: 0 0 5px 0;
	}
	
#Social-Sidebar.Corners.Pos-Right ul li:first-child a {
	-webkit-border-radius: 5px 0 0 0;
	   -moz-border-radius: 5px 0 0 0;
			border-radius: 5px 0 0 0;
	}

#Social-Sidebar.Corners.Pos-Right ul li:last-child a {	
	-webkit-border-radius: 0 0 0 5px;
	   -moz-border-radius: 0 0 0 5px;
			border-radius: 0 0 0 5px;
	}
	
/**********************************************************
// Social Sidebar >> Corners: All
**********************************************************/
#Social-Sidebar.Corners-All.Pos-Left ul li a {
	-webkit-border-radius: 0 5px 5px 0;
	   -moz-border-radius: 0 5px 5px 0;
			border-radius: 0 5px 5px 0;
	}
	
#Social-Sidebar.Corners-All.Pos-Right ul li a {
	-webkit-border-radius: 5px 0 0 5px;
	   -moz-border-radius: 5px 0 0 5px;
			border-radius: 5px 0 0 5px;
	}
	
/**********************************************************
// Social Sidebar >> Shadow
**********************************************************/
#Social-Sidebar.Shadow.Pos-Left {
	-webkit-box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3);
	   -moz-box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3);
		    box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3);
	}
	
#Social-Sidebar.Shadow.Pos-Right {
	-webkit-box-shadow: -1px 1px 5px rgba(0, 0, 0, 0.3);
	   -moz-box-shadow: -1px 1px 5px rgba(0, 0, 0, 0.3);
			box-shadow: -1px 1px 5px rgba(0, 0, 0, 0.3);
	}
	
/**********************************************************
// Social Sidebar >> Shadow: All
**********************************************************/
#Social-Sidebar.Shadow-All.Pos-Left ul li a {
	-webkit-box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
	   -moz-box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
			box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
	}

#Social-Sidebar.Shadow-All.Pos-Right ul li a {
	-webkit-box-shadow: -1px 1px 2px rgba(0, 0, 0, 0.3);
	   -moz-box-shadow: -1px 1px 2px rgba(0, 0, 0, 0.3);
			box-shadow: -1px 1px 2px rgba(0, 0, 0, 0.3);
	}

/**********************************************************
// Social Sidebar >> Circle
**********************************************************/
#Social-Sidebar.Circle ul li a {
	-webkit-border-radius: 25px;
	   -moz-border-radius: 25px;
			border-radius: 25px;
	}

#Social-Sidebar.Circle.Pos-Left ul li a {
	margin-top: 5px;
	left: 5px;
	}
	
#Social-Sidebar.Circle.Pos-Right ul li a {
	margin-top: 5px;
	right: 5px;
	}
	
/**********************************************************
// Social Sidebar >> Custom Colors
**********************************************************/
#Social-Sidebar ul li a {
	background: <?php echo $BG; ?>;
	color: <?php echo $Text; ?>;
	}

#Social-Sidebar ul li a:hover,
#Social-Sidebar ul li a span,
#Social-Sidebar ul li a span:before {
	background: <?php echo $BG_Hover; ?>;
	color: <?php echo $Text_Hover; ?>;
	}