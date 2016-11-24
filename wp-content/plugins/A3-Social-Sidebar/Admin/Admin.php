<?php
//*************************************************************
// Enqueue Admin Styles
//*************************************************************
wp_enqueue_style( "dashboard" );
wp_enqueue_style( "wp-color-picker" );
wp_enqueue_style( "Admin", plugins_url( "/Styles/Admin.css", __FILE__ ) );
wp_enqueue_style( "Icons", plugins_url( "/Styles/Icons.css", __FILE__ ) );

//*************************************************************
// Enqueue Admin Scripts
//*************************************************************
wp_enqueue_script( "dashboard" );
wp_enqueue_script( "jquery" );
wp_enqueue_script( "jquery-ui-sortable" );
wp_enqueue_script( "wp-color-picker" );
wp_enqueue_script( "Admin", plugins_url( "/Scripts/Admin.js", __FILE__ ), array( "jquery", "jquery-ui-sortable" ) );
wp_enqueue_script( "Icons", plugins_url( "/Scripts/Social-Icons.js", __FILE__ ) );

//*************************************************************
// Options
//*************************************************************
require_once A3SCS_DIR . "/Admin/Admin-Functions.php";
$Options = get_option( "A3SCS" );

$Options['Links_List'] = isset( $Options['Links_List'] ) ? $Options['Links_List'] : FALSE;
$Options['Custom_CSS'] = isset( $Options['Custom_CSS'] ) ? $Options['Custom_CSS'] : FALSE;

if ( $Options['Links_List'] ) $Options['Links_List'] = unserialize( base64_decode( $Options['Links_List'] ) );
if ( $Options['Custom_CSS'] ) $Options['Custom_CSS'] = base64_decode( $Options['Custom_CSS'] );

//*************************************************************
// Localize Script Variables
//*************************************************************
wp_localize_script( "Admin", "LINKS_LIST", $Options['Links_List'] );
wp_localize_script( "Admin", "DATA", array(
	"AJAX_URL" => admin_url( "admin-ajax.php" ),
	"Version"  => A3SCS_VERSION
) );
?>

<div id="Overlay"></div>

<div id="Wrapper" class="wrap">

	<div id="Header" class="CF">
		<a href="http://a3webtools.com" target="_blank" id="Header-Logo">Social Sidebar</a>
		<div id="Header-Button">Save Settings</div>
		<div id="Header-Loading"></div>
	</div>
	
	<div id="Messages"></div>
	
	<div id="Main">
		<form id="Settings-Form">
			
			<!--********************************************************
			// Sidebar
			*********************************************************-->
			<nav id="Sidebar">
				<ul>
					<li id="Side-General">General</li>
					<li id="Side-Links">Sidebar Links</li>
					<li id="Side-Share">Link Sharing</li>
					<li id="Side-Mobile">Mobile Display</li>
					<li id="Side-Colors">Custom Colors</li>
					<li id="Side-Custom">Custom CSS</li>
					<li id="Side-Map">Icon Map</li>
				</ul>
				<div class="Sidebar-Line"></div>
				<ul>
					<li id="Side-About">About Plugin</li>
					<li id="Side-Support">Plugin Support</li>
				</ul>
			</nav>
			
			<!--********************************************************
			// SECTION: General
			*********************************************************-->
			<div id="Section-General" class="Section">
				<div class="Section-Box">
					<h2>Sidebar Styles</h2>
					<div class="Section-Inner">
						<table class="form-table">
							<?php
								A3_Admin_Option( $Options, array(
									"Option"  => "Position",
									"Type"    => "Select",
									"Label"   => "Sidebar Position",
									"Opt_Val" => array( "Left", "Right" )
								));
								
								A3_Admin_Option( $Options, array(
									"Option" => "Theme",
									"Type"   => "Select",
									"Label"  => "Sidebar Theme",
									"Opt_Val" => array( "Dark", "Light", "Trans", "Color" ),
									"Opt_Lbl" => array( "Dark", "Light", "Transparent", "Color" )
								));
								
								A3_Admin_Option( $Options, array(
									"Option"  => "Size",
									"Type"    => "Select",
									"Label"   => "Sidebar Links Size",
									"Opt_Val" => array( "Small", "Large" )
								));
								
								A3_Admin_Option( $Options, array(
									"Option"  => "Style",
									"Type"    => "Select",
									"Label"   => "Sidebar Links Style",
									"Opt_Val" => array( "Square", "Circle" )
								));
								
								A3_Admin_Option( $Options, array(
									"Option" => "Label",
									"Type"   => "Select",
									"Label"  => "Link Label Style",
									"Opt_Val" => array( "Square", "Curve", "Round", "Fancy" ),
									"Opt_Lbl" => array( "Square", "Curved", "Rounded", "Fancy" )
								));
								
								A3_Admin_Option( $Options, array(
									"Option"  => "Shadow",
									"Type"    => "Select",
									"Label"   => "Box Shadow",
									"Opt_Val" => array( "", "Bar", "Links" ),
									"Opt_Lbl" => array( "None", "Apply to Bar", "Apply to Links" )
								));
								
								A3_Admin_Option( $Options, array(
									"Option"  => "Corners",
									"Type"    => "Select",
									"Label"   => "Corners",
									"Opt_Val" => array( "", "Bar", "Links" ),
									"Opt_Lbl" => array( "None", "Apply to Bar", "Apply to Links" )
								));
							?>
						</table>
					</div>
				</div>
				
				<div class="Section-Box">
					<h2>Sidebar Display</h2>
					<div class="Section-Inner">
						<p>
							Select which pages &amp; general post types to display the sidebar on.
						</p>
						<table class="form-table">
							<?php
								A3_Admin_Option( $Options, array(
									"Option"  => "Display_Front",
									"Type"    => "Checkbox",
									"Label"   => "Front Page",
									"Default" => TRUE
								));
								
								A3_Admin_Option( $Options, array(
									"Option"  => "Display_Blog",
									"Type"    => "Checkbox",
									"Label"   => "Blog Page",
									"Default" => TRUE
								));
								
								A3_Admin_Option( $Options, array(
									"Option"  => "Display_Posts",
									"Type"    => "Checkbox",
									"Label"   => "Posts",
									"Default" => TRUE
								));
								
								A3_Admin_Option( $Options, array(
									"Option"  => "Display_Pages",
									"Type"    => "Checkbox",
									"Label"   => "Pages",
									"Default" => TRUE
								));
								
								A3_Admin_Option( $Options, array(
									"Option"  => "Display_Archive",
									"Type"    => "Checkbox",
									"Label"   => "Archives",
									"Default" => TRUE
								));
								
								A3_Admin_Option( $Options, array(
									"Option"  => "Display_Search",
									"Type"    => "Checkbox",
									"Label"   => "Search",
									"Default" => TRUE
								));
								
								A3_Admin_Option( $Options, array(
									"Option"  => "Display_404",
									"Type"    => "Checkbox",
									"Label"   => "404 Page",
									"Default" => TRUE
								));
							?>
						</table>
					</div>
				</div>

				<div class="Section-Box">
					<h2>HTML5 Tags</h2>
					<div class="Section-Inner">
						<p>
							The sidebar is defaulted to display code using the HTML5 &lt;aside&gt; tag. Disable this option to use the &lt;div&gt; tag instead. 
							This is useful for compatibility with older browsers.
						</p>
						<table class="form-table">
							<?php
								A3_Admin_Option( $Options, array(
									"Option"  => "HTML5_Tags",
									"Type"    => "Checkbox",
									"Label"   => "Use HTML5 Tags",
									"Default" => TRUE
								));
							?>
						</table>
					</div>
				</div>
				
				<div class="Section-Box">
					<h2>Manual Mode</h2>
					<div class="Section-Inner">
						<p>
							Enable this mode to place the PHP sidebar build code directly into your template. This mode will disable automatic display and override current display settings.
							<br /><br />
							To add the sidebar to your site, simply add the following function to your template: 
							<code>&lt;php a3_social_sidebar(); ?&gt;</code>
							<br /><br />
							To add the sidebar to a specific post or page, use the following shortcode: 
							<code>[a3_social_sidebar]</code>
						</p>
						<table class="form-table">
							<?php
								A3_Admin_Option( $Options, array(
									"Option"  => "Manual_Mode",
									"Type"    => "Checkbox",
									"Label"   => "Enable Manual Mode",
									"Default" => FALSE
								));
							?>
						</table>
					</div>
				</div>
				
				<div class="Section-Box">
					<h2>Settings Reset</h2>
					<div class="Section-Inner">
						<p>
							<strong>Note:</strong> You will still need to save your changes after resetting.
							<br /><br />
							<a href="#" onclick="ADMIN.Settings_Reset('All');"><strong>All</strong></a> &nbsp;<span class="LGray">|</span>&nbsp; 
							<a href="#" onclick="ADMIN.Settings_Reset('General');">General</a> &nbsp;<span class="LGray">|</span>&nbsp; 
							<a href="#" onclick="ADMIN.Settings_Reset('Links');">Sidebar Links</a> &nbsp;<span class="LGray">|</span>&nbsp; 
							<a href="#" onclick="ADMIN.Settings_Reset('Sharing');">Link Sharing</a> &nbsp;<span class="LGray">|</span>&nbsp; 
							<a href="#" onclick="ADMIN.Settings_Reset('Mobile');">Mobile Display</a> &nbsp;<span class="LGray">|</span>&nbsp; 
							<a href="#" onclick="ADMIN.Settings_Reset('Colors');">Custom Colors</a> &nbsp;<span class="LGray">|</span>&nbsp; 
							<a href="#" onclick="ADMIN.Settings_Reset('Custom');">Custom CSS</a>
						</p>
					</div>
				</div>
			</div>
			
			<!--********************************************************
			// SECTION: Links List
			*********************************************************-->
			<div id="Section-Links" class="Section">
				<div class="Section-Box">
					<h2>Manage Sidebar Links</h2>
					<div class="Section-Inner">
						
						<table id="Links-Header">
							<tr>
								<td id="Link-Header-Handle">&nbsp;</td>
								<td id="Link-Header-Icon">Icon</td>
								<td id="Link-Header-Name">Name</td>
								<td id="Link-Header-URL">URL</td>
								<td id="Link-Header-Actions">Actions</td>
							</tr>
						</table>

						<div id="Links-List">
							<ul id="Links-Sort"></ul>
						</div>
						
						<textarea id="Links_List" class="Hidden" name="Links_List"></textarea>
						<div id="Links-Button-Add" class="Button Button-Blue">Add Link</div>
					</div>
				</div>
				
				<div class="Section-Box">
					<h2>Notes</h2>
					<div class="Section-Inner">
						<p>
							<strong>Editing Links</strong> &nbsp;-&nbsp; Simply click the edit button under the actions column to bring up the link information. Click save once you have made your changes.
							<br /><br />
							<strong>Copy Button</strong> &nbsp;-&nbsp; This button will create a copy of the link that was clicked and place it at the bottom of the list.
							<br /><br />
							<strong>Link Icons</strong> &nbsp;-&nbsp; Link icon selection is available in it's own tab in the edit screen to accomodate a more visual selection.
							<br /><br />
							<strong>E-Mail Links</strong> &nbsp;-&nbsp; To create a basic e-mail link, use something similar to the following: <code>mailto:your_email@isp.com</code>
							<br /><br />
							<strong>Share Links</strong> &nbsp;-&nbsp; Information for the share links is automatically generated. See the Link Sharing tab for more options.
							<br /><br />
							<a href="http://www.addthis.com/services/list" target="_blank"><strong>AddThis Services</strong></a> 
							 &nbsp;-&nbsp; A full list of the AddThis share types and their descriptions is available at AddThis.com.
						</p>
					</div>
				</div>
			</div>
			
			<!--********************************************************
			// SECTION: Links Sharing
			*********************************************************-->
			<div id="Section-Share" class="Section">
				<div class="Section-Box">
					<h2>AddThis Analytics</h2>
					<div class="Section-Inner">
						<p>
							The AddThis share links do not require an ID to use, but if you want to track analytics through your AddThis account, a 
							<a href="https://www.addthis.com/settings/publisher" target="_blank">Publisher Profile ID</a> is available through the site 
							once you are signed in.
						</p>
						<table class="form-table">
							<?php
								A3_Admin_Option( $Options, array(
									"Option"  => "AddThis_Profile",
									"Type"    => "Text",
									"Label"   => "Publisher Profile ID"
								));
							?>
						</table>
					</div>
				</div>
				
				<div class="Section-Box">
					<h2>Share Link Defaults</h2>
					<div class="Section-Inner">
						<p>
							You can use these fields to set default values in the instance they cannot be automatically generated. Additionally, you can set these options to be exclusive
							so that they are the only information used when sharing links, allowing you total control over the content regardless of the page.
						</p>
						<table class="form-table">
							<tr valign="top">
								<th scope="row"><label for="Default_Share_Title">Default Title</label></th>
								<td>
									<?php
										A3_Admin_Option( $Options, array(
											"Option"     => "Default_Share_Title",
											"Type"       => "Text",
											"Outer_HTML" => FALSE
										));
									?>
									<br /><br />
									<?php
										A3_Admin_Option( $Options, array(
											"Option"     => "Use_Default_Share_Title",
											"Type"       => "Checkbox",
											"Label"      => "Use Default Title Exclusively",
											"Default"    => FALSE,
											"Outer_HTML" => FALSE
										));
									?>
									<br /><br />
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><label for="Default_Share_Desc">Default Description</label></th>
								<td>
									<?php
										A3_Admin_Option( $Options, array(
											"Option"     => "Default_Share_Desc",
											"Type"       => "Text",
											"Outer_HTML" => FALSE
										));
									?>
									<br /><br />
									<?php
										A3_Admin_Option( $Options, array(
											"Option"     => "Use_Default_Share_Desc",
											"Type"       => "Checkbox",
											"Label"      => "Use Default Description Exclusively",
											"Default"    => FALSE,
											"Outer_HTML" => FALSE
										));
									?>
									<br /><br />
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><label for="Default_Share_URL">Default URL</label></th>
								<td>
									<?php
										A3_Admin_Option( $Options, array(
											"Option"     => "Default_Share_URL",
											"Type"       => "Text",
											"Outer_HTML" => FALSE
										));
									?>
									<br /><br />
									<?php
										A3_Admin_Option( $Options, array(
											"Option"     => "Use_Default_Share_URL",
											"Type"       => "Checkbox",
											"Label"      => "Use Default URL Exclusively",
											"Default"    => FALSE,
											"Outer_HTML" => FALSE
										));
									?>
									<br /><br />
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			
			<!--********************************************************
			// SECTION: Mobile Display
			*********************************************************-->
			<div id="Section-Mobile" class="Section">
				<div class="Section-Box">
					<h2>Mobile Display Settings</h2>
					<div class="Section-Inner">
						<p>
							The sidebar is equipped with a couple of different options to handle mobile traffic. Enable the mobile display and select a display method.
							<br /><br />
							<strong>Note:</strong> Width option is required to be a whole number (Ex. 1, 2, 300, 450, etc.). 
						</p>
						<table class="form-table">
							<?php
								A3_Admin_Option( $Options, array(
									"Option"     => "Mobile_Enable",
									"Type"       => "Checkbox",
									"Label"      => "Enable Mobile Display",
									"Default"    => FALSE
								));
							?>
						</table>
						<br /><div class="Line"></div><br />
						<table class="form-table">
							<?php
								A3_Admin_Option( $Options, array(
									"Option" => "Mobile_Type",
									"Type"   => "Select",
									"Label"  => "Display Type",
									"Opt_Val" => array( "Hide", "Button" ),
									"Opt_Lbl" => array( "Hide Bar", "Turn to Buttons" ),
									"Default" => "Button"
								));

								A3_Admin_Option( $Options, array(
									"Option"  => "Mobile_Width",
									"Type"    => "Text",
									"Label"   => "Width (in Pixels)",
									"Default" => "400"
								));
							?>
						</table>
					</div>
				</div>
			</div>
			
			<!--********************************************************
			// SECTION: Custom Colors
			*********************************************************-->
			<div id="Section-Colors" class="Section">
				<div class="Section-Box">
					<h2>Custom Sidebar Colors</h2>
					<div class="Section-Inner">
						<p>
							Custom colors override the classic stylesheet in favor of the ability to set your own foreground and background colors and their hover equivalents.
							This is useful for theming the sidebar to your current website theme and having more control over the design of the bar.
							<br /><br />
							<strong>Note:</strong> This mode overrides the theme styles normally associated with the sidebar. You will not have multi-color hover styles if this mode
							is enabled.
						</p>
						<table class="form-table">
							<?php
								A3_Admin_Option( $Options, array(
									"Option"     => "Custom_Enable",
									"Type"       => "Checkbox",
									"Label"      => "Enable Custom Colors",
									"Default"    => FALSE
								));
							?>
						</table>
						<br /><div class="Line"></div><br />
						<table class="form-table">
							<?php
								A3_Admin_Option( $Options, array(
									"Option"     => "Custom_BG",
									"Type"       => "Color",
									"Label"      => "Background",
									"Default"    => "#444444"
								));
								
								A3_Admin_Option( $Options, array(
									"Option"     => "Custom_Text",
									"Type"       => "Color",
									"Label"      => "Text Color",
									"Default"    => "#FFFFFF"
								));
								
								A3_Admin_Option( $Options, array(
									"Option"     => "Custom_BG_Hover",
									"Type"       => "Color",
									"Label"      => "Hover: Background",
									"Default"    => "#444444"
								));
								
								A3_Admin_Option( $Options, array(
									"Option"     => "Custom_Text_Hover",
									"Type"       => "Color",
									"Label"      => "Hover: Text Color",
									"Default"    => "#FFFFFF"
								));
							?>
						</table>
					</div>
				</div>
			</div>
			
			<!--********************************************************
			// SECTION: Custom CSS
			*********************************************************-->
			<div id="Section-Custom" class="Section">
				<div class="Section-Box">
					<h2>Custom Sidebar CSS</h2>
					<div class="Section-Inner">
						<p>
							Depending on your theme CSS, you may need to make small corrections or modifications to the sidebar to get it
							where you want it. Enter that custom CSS code here.
							<br /><br />
							<strong>Icon Height Fix:</strong>&nbsp;&nbsp;
							A common fix for icons that appear to be stuck at the top of the link is to adjust the line height. Use the following
							code to fix this issue if you run into it: &nbsp;&nbsp;<code>#Social-Sidebar a:before { line-height: 1.9; }</code>
							<br /><br />
							<strong>Adjust Sidebar Position:</strong>&nbsp;&nbsp;
							A quick and easy modification to adjust the sidebar position on the side of the page is to change the height at which it is set.
							You can set this as a measure of pixels or percentage of the page with changes to the following code: 
							&nbsp;&nbsp;<code>#Social-Sidebar { top: 30%; }</code>
						</p>
						<table class="form-table">
							<?php
								A3_Admin_Option( $Options, array(
									"Option"     => "Custom_CSS",
									"Type"       => "Textarea",
									"Label"      => "Custom CSS"
								));
							?>
						</table>
					</div>
				</div>
			</div>
			
			<!--********************************************************
			// SECTION: Icon Map
			*********************************************************-->
			<div id="Section-Map" class="Section">
				<div class="Section-Box">
					<h2>Icon Map</h2>
					<div class="Section-Inner">
						<section id="Map">
							<div class="Row">
								<div class="Icon Aid"><span>Aid</span></div>
							</div>
							<div class="Row">
								<div class="Icon Android"><span>Android</span></div>
							</div>
							<div class="Row">
								<div class="Icon Apple"><span>Apple</span></div>
							</div>
							<div class="Row">
								<div class="Icon Behance"><span>Behance</span></div>
							</div>
							<div class="Row">
								<div class="Icon Blogger"><span>Blogger</span></div>
								<div class="Icon Blogger-2"><span>Blogger-2</span></div>
							</div>
							<div class="Row">
								<div class="Icon Bookmark"><span>Bookmark</span></div>
								<div class="Icon Bookmark-2"><span>Bookmark-2</span></div>
							</div>
							<div class="Row">
								<div class="Icon Bubbles"><span>Bubbles</span></div>
								<div class="Icon Bubbles-2"><span>Bubbles-2</span></div>
							</div>
							<div class="Row">
								<div class="Icon Bullhorn"><span>Bullhorn</span></div>
							</div>
							<div class="Row">
								<div class="Icon Calendar"><span>Calendar</span></div>
							</div>
							<div class="Row">
								<div class="Icon Cart"><span>Cart</span></div>
								<div class="Icon Cart-2"><span>Cart-2</span></div>
								<div class="Icon Cart-3"><span>Cart-3</span></div>
							</div>
							<div class="Row">
								<div class="Icon CC"><span>CC</span></div>
							</div>
							<div class="Row">
								<div class="Icon Circles"><span>Circles</span></div>
							</div>
							<div class="Row">
								<div class="Icon Cloud-Down"><span>Cloud-Down</span></div>
								<div class="Icon Cloud-Up"><span>Cloud-Up</span></div>
							</div>
							<div class="Row">
								<div class="Icon Cog"><span>Cog</span></div>
								<div class="Icon Cog-2"><span>Cog-2</span></div>
							</div>
							<div class="Row">
								<div class="Icon CSS3"><span>CSS3</span></div>
							</div>
							<div class="Row">
								<div class="Icon Delicious"><span>Delicious</span></div>
							</div>
							<div class="Row">
								<div class="Icon Deviantart"><span>Deviantart</span></div>
								<div class="Icon Deviantart-2"><span>Deviantart-2</span></div>
							</div>
							<div class="Row">
								<div class="Icon Dribbble"><span>Dribbble</span></div>
								<div class="Icon Dribbble-2"><span>Dribbble-2</span></div>
								<div class="Icon Dribbble-3"><span>Dribbble-3</span></div>
							</div>
							<div class="Row">
								<div class="Icon Dropbox"><span>Dropbox</span></div>
							</div>
							<div class="Row">
								<div class="Icon Evernote"><span>Evernote</span></div>
							</div>
							<div class="Row">
								<div class="Icon Facebook"><span>Facebook</span></div>
								<div class="Icon Facebook-2"><span>Facebook-2</span></div>
								<div class="Icon Facebook-3"><span>Facebook-3</span></div>
							</div>
							<div class="Row">
								<div class="Icon File-Down"><span>File-Down</span></div>
								<div class="Icon File-Up"><span>File-Up</span></div>
							</div>
							<div class="Row">
								<div class="Icon Flag"><span>Flag</span></div>
							</div>
							<div class="Row">
								<div class="Icon Flattr"><span>Flattr</span></div>
							</div>
							<div class="Row">
								<div class="Icon Flickr"><span>Flickr</span></div>
								<div class="Icon Flickr-2"><span>Flickr-2</span></div>
								<div class="Icon Flickr-3"><span>Flickr-3</span></div>
								<div class="Icon Flickr-4"><span>Flickr-4</span></div>
							</div>
							<div class="Row">
								<div class="Icon Forrst"><span>Forrst</span></div>
								<div class="Icon Forrst-2"><span>Forrst-2</span></div>
							</div>
							<div class="Row">
								<div class="Icon Foursquare"><span>Foursquare</span></div>
								<div class="Icon Foursquare-2"><span>Foursquare-2</span></div>
							</div>
							<div class="Row">
								<div class="Icon Github"><span>Github</span></div>
								<div class="Icon Github-2"><span>Github-2</span></div>
								<div class="Icon Github-3"><span>Github-3</span></div>
								<div class="Icon Github-4"><span>Github-4</span></div>
								<div class="Icon Github-5"><span>Github-5</span></div>
							</div>
							<div class="Row">
								<div class="Icon Google"><span>Google</span></div>
							</div>
							<div class="Row">
								<div class="Icon Google-Drive"><span>Google-Drive</span></div>
							</div>
							<div class="Row">
								<div class="Icon GPlus"><span>GPlus</span></div>
								<div class="Icon GPlus-2"><span>GPlus-2</span></div>
								<div class="Icon GPlus-3"><span>GPlus-3</span></div>
								<div class="Icon GPlus-4"><span>GPlus-4</span></div>
							</div>
							<div class="Row">
								<div class="Icon Heart"><span>Heart</span></div>
							</div>
							<div class="Row">
								<div class="Icon Home"><span>Home</span></div>
								<div class="Icon Home-2"><span>Home-2</span></div>
							</div>
							<div class="Row">
								<div class="Icon HTML5"><span>HTML5</span></div>
								<div class="Icon HTML5-2"><span>HTML5-2</span></div>
							</div>
							<div class="Row">
								<div class="Icon Instagram"><span>Instagram</span></div>
								<div class="Icon Instagram-2"><span>Instagram-2</span></div>
							</div>
							<div class="Row">
								<div class="Icon Joomla"><span>Joomla</span></div>
							</div>
							<div class="Row">
								<div class="Icon Lab"><span>Lab</span></div>
							</div>
							<div class="Row">
								<div class="Icon Lanyrd"><span>Lanyrd</span></div>
							</div>
							<div class="Row">
								<div class="Icon LastFM"><span>LastFM</span></div>
								<div class="Icon LastFM-2"><span>LastFM-2</span></div>
							</div>
							<div class="Row">
								<div class="Icon Lightning"><span>Lightning</span></div>
							</div>
							<div class="Row">
								<div class="Icon LinkedIn"><span>LinkedIn</span></div>
							</div>
							<div class="Row">
								<div class="Icon Linux"><span>Linux</span></div>
							</div>
							<div class="Row">
								<div class="Icon Mail"><span>Mail</span></div>
								<div class="Icon Mail-2"><span>Mail-2</span></div>
								<div class="Icon Mail-3"><span>Mail-3</span></div>
							</div>
							<div class="Row">
								<div class="Icon Mixi"><span>Mixi</span></div>
							</div>
							<div class="Row">
								<div class="Icon Paypal"><span>Paypal</span></div>
								<div class="Icon Paypal-2"><span>Paypal-2</span></div>
							</div>
								<div class="Row">
								<div class="Icon Phone"><span>Phone</span></div>
								<div class="Icon Phone-2"><span>Phone-2</span></div>
							</div>
							<div class="Row">
								<div class="Icon Picassa"><span>Picassa</span></div>
								<div class="Icon Picassa-2"><span>Picassa-2</span></div>
							</div>
							<div class="Row">
								<div class="Icon Pinterest"><span>Pinterest</span></div>
								<div class="Icon Pinterest-2"><span>Pinterest-2</span></div>
							</div>
							<div class="Row">
								<div class="Icon Power"><span>Power</span></div>
							</div>
							<div class="Row">
								<div class="Icon QQ"><span>QQ</span></div>
							</div>
							<div class="Row">
								<div class="Icon Rdio"><span>Rdio</span></div>
							</div>
							<div class="Row">
								<div class="Icon Reddit"><span>Reddit</span></div>
							</div>
							<div class="Row">
								<div class="Icon RenRen"><span>RenRen</span></div>
							</div>
							<div class="Row">
								<div class="Icon Rocket"><span>Rocket</span></div>
							</div>
							<div class="Row">
								<div class="Icon RSS"><span>RSS</span></div>
								<div class="Icon RSS-2"><span>RSS-2</span></div>
								<div class="Icon RSS-3"><span>RSS-3</span></div>
							</div>
							<div class="Row">
								<div class="Icon Share"><span>Share</span></div>
							</div>
							<div class="Row">
								<div class="Icon SinaWeibo"><span>SinaWeibo</span></div>
							</div>
							<div class="Row">
								<div class="Icon Skype"><span>Skype</span></div>
							</div>
							<div class="Row">
								<div class="Icon Soundcloud"><span>Soundcloud</span></div>
								<div class="Icon Soundcloud-2"><span>Soundcloud-2</span></div>
							</div>
							<div class="Row">
								<div class="Icon Spotify"><span>Spotify</span></div>
							</div>
							<div class="Row">
								<div class="Icon Stackoverflow"><span>Stackoverflow</span></div>
							</div>
							<div class="Row">
								<div class="Icon Star"><span>Star</span></div>
							</div>
							<div class="Row">
								<div class="Icon Steam"><span>Steam</span></div>
								<div class="Icon Steam-2"><span>Steam-2</span></div>
							</div>
							<div class="Row">
								<div class="Icon Stumbleupon"><span>Stumbleupon</span></div>
								<div class="Icon Stumbleupon-2"><span>Stumbleupon-2</span></div>
							</div>
							<div class="Row">
								<div class="Icon Support"><span>Support</span></div>
							</div>
							<div class="Row">
								<div class="Icon Tag"><span>Tag</span></div>
							</div>
							<div class="Row">
								<div class="Icon Thumbs-Up"><span>Thumbs-Up</span></div>
							</div>
							<div class="Row">
								<div class="Icon Tumblr"><span>Tumblr</span></div>
								<div class="Icon Tumblr-2"><span>Tumblr-2</span></div>
							</div>
							<div class="Row">
								<div class="Icon Twitter"><span>Twitter</span></div>
								<div class="Icon Twitter-2"><span>Twitter-2</span></div>
								<div class="Icon Twitter-3"><span>Twitter-3</span></div>
							</div>
							<div class="Row">
								<div class="Icon User"><span>User</span></div>
								<div class="Icon User-2"><span>User-2</span></div>
							</div>
							<div class="Row">
								<div class="Icon Users"><span>Users</span></div>
							</div>
							<div class="Row">
								<div class="Icon Vimeo"><span>Vimeo</span></div>
								<div class="Icon Vimeo-2"><span>Vimeo-2</span></div>
								<div class="Icon Vimeo-3"><span>Vimeo-3</span></div>
							</div>
							<div class="Row">
								<div class="Icon VK"><span>VK</span></div>
							</div>
							<div class="Row">
								<div class="Icon Windows"><span>Windows</span></div>
								<div class="Icon Windows8"><span>Windows8</span></div>
							</div>
							<div class="Row">
								<div class="Icon Wordpress"><span>Wordpress</span></div>
								<div class="Icon Wordpress-2"><span>Wordpress-2</span></div>
							</div>
							<div class="Row">
								<div class="Icon Xing"><span>Xing</span></div>
								<div class="Icon Xing-2"><span>Xing-2</span></div>
							</div>
							<div class="Row">
								<div class="Icon Yahoo"><span>Yahoo</span></div>
							</div>
							<div class="Row">
								<div class="Icon Yelp"><span>Yelp</span></div>
							</div>
							<div class="Row">
								<div class="Icon Youtube"><span>Youtube</span></div>
								<div class="Icon Youtube-2"><span>Youtube-2</span></div>
							</div>
						</section>
					</div>
				</div>
			</div>

			<!--********************************************************
			// SECTION: About Plugin
			*********************************************************-->
			<div id="Section-About" class="Section">
				<div class="Section-Box">
					<h2>About Plugin</h2>
					<div class="Section-Inner">
						<p>
							<strong>Developed by <a href="https://a3labs.net" target="_blank">A3 Labs, Inc.</a></strong>
							<br /><br />
							We specialize in web development, UI/UX design, and just about anything else we can think up.
							<br /><br />
							<a href="http://facebook.com/a3labs" target="_blank">Facebook Page</a>
							<br /><br />
							<a href="http://twitter.com/A3Labs" target="_blank">Twitter @A3Labs</a>
						</p>
					</div>
				</div>
			</div>
			
			<!--********************************************************
			// SECTION: Plugin Support
			*********************************************************-->
			<div id="Section-Support" class="Section">
				<div class="Section-Box">
					<h2>Plugin Support &amp; Feedback</h2>
					<div class="Section-Inner">
						<p>
							Having trouble with the plugin?<br /><br />Please check out our 	
							<a href="http://a3webtools.com/support/social-sidebar/" target="_blank"><strong>Support Page</strong></a>.
							<br /><br />
							If you have any feedback or suggestions on improving the plugin, please let us know via our 
							<a href="http://a3webtools.com/contact/" target="_blank"><strong>Contact Form</strong></a>.
						</p>
					</div>
				</div>
				
				<div class="Section-Box">
					<h2>Server Information</h2>
					<div class="Section-Inner">
						<p>
							This information can be used to help determine potential issues with the plugin based on your server information.
						</p>
						<table class="form-table">
							<tr valign='top'>
								<th scope='row'>Version: WordPress</th>
								<td><?php global $wp_version; echo $wp_version; ?></td>
							</tr>
							<tr valign='top'>
								<th scope='row'>Version: PHP</th>
								<td><?php echo phpversion(); ?></td>
							</tr>
						</table>
						<div class="Line"></div>
						<table class="form-table">
							<tr valign='top'>
								<th scope='row'>json_encode</th>
								<td><?php echo function_exists( "json_encode" ) ? "<span class='Green'>Enabled</span>" : "<span class='Red'>Disabled</span>" ?></td>
							</tr>
							<tr valign='top'>
								<th scope='row'>json_decode</th>
								<td><?php echo function_exists( "json_decode" ) ? "<span class='Green'>Enabled</span>" : "<span class='Red'>Disabled</span>" ?></td>
							</tr>
							<tr valign='top'>
								<th scope='row'>base64_encode</th>
								<td><?php echo function_exists( "base64_encode" ) ? "<span class='Green'>Enabled</span>" : "<span class='Red'>Disabled</span>" ?></td>
							</tr>
							<tr valign='top'>
								<th scope='row'>base64_decode</th>
								<td><?php echo function_exists( "base64_decode" ) ? "<span class='Green'>Enabled</span>" : "<span class='Red'>Disabled</span>" ?></td>
							</tr>
							<tr valign='top'>
								<th scope='row'>serialize</th>
								<td><?php echo function_exists( "serialize" ) ? "<span class='Green'>Enabled</span>" : "<span class='Red'>Disabled</span>" ?></td>
							</tr>
							<tr valign='top'>
								<th scope='row'>unserialize</th>
								<td><?php echo function_exists( "unserialize" ) ? "<span class='Green'>Enabled</span>" : "<span class='Red'>Disabled</span>" ?></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		
		</form>
	</div> <!-- #Main -->
	
</div> <!-- #Wrapper -->

<!-- TEMPLATE: Message -->
<div id="Template-Message" class="Hidden">
	<div id="Message-{{CODE}}" class="Message {{TYPE}}">
		<div class="Message-Icon dashicons"></div>
		<div class="Message-Text">{{TEXT}}</div>
		<div class="Clear"></div>
	</div>
</div>

<!-- TEMPLATE: Link Item -->
<div id="Template-Link-Item" class="Hidden">
	<li id="A3SCS-{{CODE}}" class="Link-Item">
		<table class="Link-Item-Layout">
			<tr>
				<td class="Link-Item-Handle">::</td>
				<td class="Link-Item-Icon" title="{{Icon}}">
					<div class="Icon {{Icon}}"></div>
				</td>
				<td class="Link-Item-Name" title="{{Name}}"><input type="text" value="{{Name}}" disabled /></td>
				<td class="Link-Item-URL" title="{{URL}}"><input type="text" value="{{URL}}" disabled /></td>
				<td class="Link-Item-Actions">
					<div id="Link-Item-{{CODE}}-Edit"   class="Link-Item-Button Button-Blue" title="Edit Link">Edit</div>
					<div id="Link-Item-{{CODE}}-Copy"   class="Link-Item-Button Button-Blue" title="Copy Link">Copy</div>
					<div id="Link-Item-{{CODE}}-Remove" class="Link-Item-Button Button-Red" title="Remove Link">X</div>
				</td>
			</tr>
		</table>
		<div class="Link-Item-ID Hidden">{{CODE}}</div>
		<input type="hidden" id="A3SCS-{{CODE}}-Icon" value="{{Icon}}" />
		<input type="hidden" id="A3SCS-{{CODE}}-Name" value="{{Name}}" />
		<input type="hidden" id="A3SCS-{{CODE}}-NewWindow" value="{{NewWindow}}" />
		<input type="hidden" id="A3SCS-{{CODE}}-NoFollow" value="{{NoFollow}}" />
		<input type="hidden" id="A3SCS-{{CODE}}-Share_Type" value="{{Share_Type}}" />
		<input type="hidden" id="A3SCS-{{CODE}}-Type" value="{{Type}}" />
		<input type="hidden" id="A3SCS-{{CODE}}-URL" value="{{URL}}" />
	</li>
</div>

<!-- TEMPLATE: Link Edit -->
<div id="Template-Link-Edit" class="Hidden">
	<div class="Overlay-Box">
		<div class="Overlay-Box-Top">
			<div class="Overlay-Box-Title">Edit Link</div>
			<div id="Overlay-Close" class="Overlay-Box-Button Button-Dark" title="Close">X</div>
			<div id="Overlay-Save" class="Overlay-Box-Button Button-Green">Save</div>
		</div>
		<div class="Overlay-Box-Mid">
			<div class="Overlay-Box-Tabs">
				<div class="Overlay-Box-Tab" id="Edit-Link-Tab-Details">Details</div>
				<div class="Overlay-Box-Tab" id="Edit-Link-Tab-Icon">Icon</div>
			</div>
			
			<div class="Overlay-Box-Content" id="Edit-Link-Section-Details">
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><label for="Edit-Link-Name">Link Name</label></th>
						<td><input type="text" id="Edit-Link-Name" /></td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="Edit-Link-Type">Link Type</label></th>
						<td>
							<select id="Edit-Link-Type">
								<option value="Link">Link</option>
								<option value="Share">Share</option>
							</select>
						</td>
					</tr>
					<tr valign="top" id="Edit-Toggle-URL">
						<th scope="row"><label for="Edit-Link-URL">Link URL</label></th>
						<td><input type="text" id="Edit-Link-URL" /></td>
					</tr>
					<tr valign="top" id="Edit-Toggle-Services">
						<th scope="row"><label for="Edit-Share-Service">Share Service</label></th>
						<td>
							<select id="Edit-Share-Service">
								<option value="AddThis_Baidu">AddThis: Baidu</option>
								<option value="AddThis_Bitly">AddThis: Bit.ly</option>
								<option value="AddThis_Blogger">AddThis: Blogger</option>
								<option value="AddThis_Box">AddThis: Box.net</option>
								<option value="AddThis_Delicious">AddThis: Delicious</option>
								<option value="AddThis_Digg">AddThis: Digg</option>
								<option value="AddThis_Email">AddThis: Email</option>
								<option value="AddThis_Evernote">AddThis: Evernote</option>
								<option value="AddThis_Facebook">AddThis: Facebook</option>
								<option value="AddThis_Fark">AddThis: Fark</option>
								<option value="AddThis_FriendFeed">AddThis: FriendFeed</option>
								<option value="AddThis_GMail">AddThis: GMail</option>
								<option value="AddThis_Google">AddThis: Google</option>
								<option value="AddThis_Google_Plusone_Share">AddThis: Google +1 Share</option>
								<option value="AddThis_Hotmail">AddThis: Hotmail</option>
								<option value="AddThis_Instapaper">AddThis: Instapaper</option>
								<option value="AddThis_LinkedIn">AddThis: LinkedIn</option>
								<option value="AddThis_LiveJournal">AddThis: LiveJournal</option>
								<option value="AddThis_Live">AddThis: Live Messenger</option>
								<option value="AddThis_Mixi">AddThis: Mixi</option>
								<option value="AddThis_MySpace">AddThis: MySpace</option>
								<option value="AddThis_NetVibes">AddThis: NetVibes</option>
								<option value="AddThis_Newsvine">AddThis: Newsvine</option>
								<option value="AddThis_Orkut">AddThis: Orkut</option>
								<option value="AddThis_Pinterest">AddThis: Pinterest Pin It</option>
								<option value="AddThis_Reddit">AddThis: Reddit</option>
								<option value="AddThis_Scoopit">AddThis: Scoop.it</option>
								<option value="AddThis_SinaWeibo">AddThis: Sina Weibo</option>
								<option value="AddThis_Tumblr">AddThis: Tumblr</option>
								<option value="AddThis_Twitter">AddThis: Twitter</option>
								<option value="AddThis_Typepad">AddThis: Typepad</option>
								<option value="AddThis_VK">AddThis: Vkontakte</option>
								<option value="AddThis_WordPress">AddThis: WordPress</option>
								<option value="AddThis_YahooMail">AddThis: Y! Mail</option>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="Edit-Link-NewWindow">New Window</label></th>
						<td><input type="checkbox" id="Edit-Link-NewWindow" /></td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="Edit-Link-NoFollow">No Follow</label></th>
						<td><input type="checkbox" id="Edit-Link-NoFollow" /></td>
					</tr>
				</table>
			</div>
			<div class="Overlay-Box-Content" id="Edit-Link-Section-Icon">
				<input type="text" id="Overlay-Icon-Search" placeholder="Search for..." />
				<div id="Overlay-Icon-List"></div>
			</div>
		</div>
	</div>
</div>