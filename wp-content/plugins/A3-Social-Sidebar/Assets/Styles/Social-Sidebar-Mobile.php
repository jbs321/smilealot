<?php
//*************************************************************
// Get Variables
//*************************************************************
$Type  = isset( $_GET['Type']  ) ? $_GET['Type']  : "Button";
$Width = isset( $_GET['Width'] ) ? $_GET['Width'] : "400";

header("Content-type: text/css; charset: UTF-8");
?>
@media 
all and (max-width: <?php echo $Width; ?>px),
all and (max-device-width: <?php echo $Width; ?>px)
{
<?php if ( "Hide" === $Type ): ?>
	#Social-Sidebar { display: none; }
<?php else: ?>
	#Social-Sidebar.Pos-Left  { float: left;  }
	#Social-Sidebar.Pos-Right { float: right; }
	#Social-Sidebar {
		margin-top: 10px;
		position: static;
		top: 0;
		}

	#Social-Sidebar ul li {
		margin: 5px;
		display: inline-block;
		}

	#Social-Sidebar ul li a {
		padding: 0 10px;
		width: auto;
		overflow: hidden;
		}

	#Social-Sidebar ul li a span {
		width: auto;
		-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
		filter: alpha(opacity=100);
		opacity: 1;
		position: static;
		display: inline-block;
		background: #333;
		color: #FFF;
		float: right;
		z-index: 1000;
		}

	/* Label: Square */	
	#Social-Sidebar.Label-Square.Pos-Left  ul li a span { text-align: center; float: right; }
	#Social-Sidebar.Label-Square.Pos-Right ul li a span { text-align: center; float: left;  }

	/* Label: Curve */
	#Social-Sidebar.Label-Curve.Pos-Left  ul li a span { text-align: center; float: right; }
	#Social-Sidebar.Label-Curve.Pos-Right ul li a span { text-align: center; float: left;  }

	/* Label: Round */
	#Social-Sidebar.Label-Round.Pos-Left  ul li a span { text-align: center; float: right; }
	#Social-Sidebar.Label-Round.Pos-Right ul li a span { text-align: center; float: left;  }

	/* Label: Fancy */
	#Social-Sidebar.Label-Fancy ul li a span {
		margin-top: 0;
		padding: 0 8px;
		line-height: 35px;
		font-size: 15px;
		top: 0;
		}

	#Social-Sidebar.Label-Fancy.Large ul li a span {
		line-height: 52px;
		font-size: 17px;
		}

	#Social-Sidebar.Label-Fancy ul li a span:before { display: none; }
	#Social-Sidebar.Label-Fancy.Pos-Left  ul li a:hover span { left:  0; }
	#Social-Sidebar.Label-Fancy.Pos-Right ul li a:hover span { right: 0; }
	#Social-Sidebar.Label-Fancy.Pos-Left  ul li a span { text-align: center; float: right; }
	#Social-Sidebar.Label-Fancy.Pos-Right ul li a span { text-align: center; float: left;  }

	/* Large */
	#Social-Sidebar.Large ul li a {
		padding-left: 13px;
		width: auto;
		}

	/* Position: Left */
	#Social-Sidebar.Pos-Left ul li a span        { left: 0; }
	#Social-Sidebar.Pos-Left ul li a:hover span  { left: 0; }
	#Social-Sidebar.Pos-Left ul li a span:before { left: 0; }

	/* Position: Right */
	#Social-Sidebar.Pos-Right ul li a span        { right: 0; }
	#Social-Sidebar.Pos-Right ul li a:hover span  { right: 0; }
	#Social-Sidebar.Pos-Right ul li a span:before { right: 0; }

	/* Corners */
	#Social-Sidebar.Corners.Pos-Left  ul li:first-child a,
	#Social-Sidebar.Corners.Pos-Left  ul li:last-child a,
	#Social-Sidebar.Corners.Pos-Right ul li:first-child a,
	#Social-Sidebar.Corners.Pos-Right ul li:last-child a,
	#Social-Sidebar.Corners.Pos-Left  ul li a {
		-webkit-border-radius: 5px;
		   -moz-border-radius: 5px;
				border-radius: 5px;
		}

	/* Corners: All */
	#Social-Sidebar.Corners-All.Pos-Left  ul li a,
	#Social-Sidebar.Corners-All.Pos-Right ul li a {
		-webkit-border-radius: 5px;
		   -moz-border-radius: 5px;
				border-radius: 5px;
		}

	/* Shadow */
	#Social-Sidebar.Shadow.Pos-Left,
	#Social-Sidebar.Shadow.Pos-Right {
		-webkit-box-shadow: 0 0 0 #000;
		   -moz-box-shadow: 0 0 0 #000;
				box-shadow: 0 0 0 #000;
		}

	/* Shadow: All */
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

	/* Theme: Light */
	#Social-Sidebar.Theme-Light ul li a,
	#Social-Sidebar.Theme-Light ul li a span {
		background: #E0E0E0;
		color: #555;
		}

	#Social-Sidebar.Theme-Light ul li a:hover,
	#Social-Sidebar.Theme-Light ul li a:hover span { color: #FFF; }

	/* Theme: Trans */
	#Social-Sidebar.Theme-Trans ul li a,
	#Social-Sidebar.Theme-Trans ul li a span {
		background: none;
		color: rgba(0, 0, 0, 0.5);
		}

	#Social-Sidebar.Theme-Trans ul li a:hover,
	#Social-Sidebar.Theme-Trans ul li a:hover span { color: #FFF; }

	/* Theme: Dark */
	#Social-Sidebar ul li a[class*="Aid"] span,
	#Social-Sidebar ul li a[class*="Android"] span,
	#Social-Sidebar ul li a[class*="Apple"] span,
	#Social-Sidebar ul li a[class*="Behance"] span,
	#Social-Sidebar ul li a[class*="Blogger"] span,
	#Social-Sidebar ul li a[class*="Bookmark"] span,
	#Social-Sidebar ul li a[class*="Bubbles"] span,
	#Social-Sidebar ul li a[class*="Bullhorn"] span,
	#Social-Sidebar ul li a[class*="Calendar"] span,
	#Social-Sidebar ul li a[class*="Cart"] span,
	#Social-Sidebar ul li a[class*="CC"] span,
	#Social-Sidebar ul li a[class*="Circles"] span,
	#Social-Sidebar ul li a[class*="Cloud-Down"] span,
	#Social-Sidebar ul li a[class*="Cloud-Up"] span,
	#Social-Sidebar ul li a[class*="Cog"] span,
	#Social-Sidebar ul li a[class*="CSS3"] span,
	#Social-Sidebar ul li a[class*="Delicious"] span,
	#Social-Sidebar ul li a[class*="Deviantart"] span,
	#Social-Sidebar ul li a[class*="Dribbble"] span,
	#Social-Sidebar ul li a[class*="Dropbox"] span,
	#Social-Sidebar ul li a[class*="Evernote"] span,
	#Social-Sidebar ul li a[class*="Facebook"] span,
	#Social-Sidebar ul li a[class*="File-Down"] span,
	#Social-Sidebar ul li a[class*="File-Up"] span,
	#Social-Sidebar ul li a[class*="Flag"] span,
	#Social-Sidebar ul li a[class*="Flattr"] span,
	#Social-Sidebar ul li a[class*="Flickr"] span,
	#Social-Sidebar ul li a[class*="Forrst"] span,
	#Social-Sidebar ul li a[class*="Foursquare"] span,
	#Social-Sidebar ul li a[class*="Github"] span,
	#Social-Sidebar ul li a[class*="Google"] span,
	#Social-Sidebar ul li a[class*="Google-Drive"] span,
	#Social-Sidebar ul li a[class*="GPlus"] span,
	#Social-Sidebar ul li a[class*="Heart"] span,
	#Social-Sidebar ul li a[class*="Home"] span,
	#Social-Sidebar ul li a[class*="HTML5"] span,
	#Social-Sidebar ul li a[class*="Instagram"] span,
	#Social-Sidebar ul li a[class*="Joomla"] span,
	#Social-Sidebar ul li a[class*="Lab"] span,
	#Social-Sidebar ul li a[class*="Lanyrd"] span,
	#Social-Sidebar ul li a[class*="LastFM"] span,
	#Social-Sidebar ul li a[class*="Lightning"] span,
	#Social-Sidebar ul li a[class*="LinkedIn"] span,
	#Social-Sidebar ul li a[class*="Linux"] span,
	#Social-Sidebar ul li a[class*="Mail"] span,
	#Social-Sidebar ul li a[class*="Mixi"] span,
	#Social-Sidebar ul li a[class*="Paypal"] span,
	#Social-Sidebar ul li a[class*="Phone"] span,
	#Social-Sidebar ul li a[class*="Picassa"] span,
	#Social-Sidebar ul li a[class*="Pinterest"] span,
	#Social-Sidebar ul li a[class*="Power"] span,
	#Social-Sidebar ul li a[class*="QQ"] span,
	#Social-Sidebar ul li a[class*="Rdio"] span,
	#Social-Sidebar ul li a[class*="Reddit"] span,
	#Social-Sidebar ul li a[class*="RenRen"] span,
	#Social-Sidebar ul li a[class*="Rocket"] span,
	#Social-Sidebar ul li a[class*="RSS"] span,
	#Social-Sidebar ul li a[class*="Share"] span,
	#Social-Sidebar ul li a[class*="SinaWeibo"] span,
	#Social-Sidebar ul li a[class*="Skype"] span,
	#Social-Sidebar ul li a[class*="Soundcloud"] span,
	#Social-Sidebar ul li a[class*="Spotify"] span,
	#Social-Sidebar ul li a[class*="Stackoverflow"] span,
	#Social-Sidebar ul li a[class*="Star"] span,
	#Social-Sidebar ul li a[class*="Steam"] span,
	#Social-Sidebar ul li a[class*="Stumbleupon"] span,
	#Social-Sidebar ul li a[class*="Support"] span,
	#Social-Sidebar ul li a[class*="Tag"] span,
	#Social-Sidebar ul li a[class*="Thumbs-Up"] span,
	#Social-Sidebar ul li a[class*="Tumblr"] span,
	#Social-Sidebar ul li a[class*="Twitter"] span,
	#Social-Sidebar ul li a[class*="User"] span,
	#Social-Sidebar ul li a[class*="Users"] span,
	#Social-Sidebar ul li a[class*="Vimeo"] span,
	#Social-Sidebar ul li a[class*="VK"] span,
	#Social-Sidebar ul li a[class*="Windows"] span,
	#Social-Sidebar ul li a[class*="Windows8"] span,
	#Social-Sidebar ul li a[class*="Wordpress"] span,
	#Social-Sidebar ul li a[class*="Xing"] span,
	#Social-Sidebar ul li a[class*="Yahoo"] span,
	#Social-Sidebar ul li a[class*="Yelp"] span,
	#Social-Sidebar ul li a[class*="Youtube"] span {
		background: none;
		}
<?php endif; ?>
}