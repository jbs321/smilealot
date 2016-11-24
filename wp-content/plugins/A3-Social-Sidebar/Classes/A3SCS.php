<?php
//**********************************************************
// CLASS >> A3SCS
// NOTES >> Houses social sidebar functions and data.
//**********************************************************
class A3SCS {

//**********************************************************
// A3SCS >> Admin: Link
// PARAM >> Array  | Links
// PARAM >> String | File
// NOTES >> Creates a settings link for the plugin page.
//**********************************************************
public static function Admin_Link( $Links, $File )
{
	static $Check;

	if ( ! $Check )
	{
		$Check = plugin_basename( __FILE__ );
		$Check = str_replace( "Classes/A3SCS.php", "A3-Social-Sidebar.php", $Check );
	}

	if ( $File === $Check )
	{
		$Blog = get_bloginfo( "wpurl" );
		$Link = "<a href='$Blog/wp-admin/options-general.php?page=A3SCS'>Settings</a>";
		array_unshift( $Links, $Link );
	}
	return $Links;
}

//*************************************************************
// A3SCS >> Admin: Init
// NOTES >> Register settings and sanitization callback.
//*************************************************************
public static function Admin_Init()
{
	$Opt_Group = "A3SCS_Options";
	$Opt_Name  = "A3SCS";

	register_setting( $Opt_Group, $Opt_Name );
}

//*************************************************************
// A3SCS >> Admin: Menu
// NOTES >> Add admin page to Settings menu.
//*************************************************************
public static function Admin_Menu()
{
	$Page_Title = "Social Sidebar";
	$Menu_Title = "Social Sidebar";
	$Capability = "manage_options";
	$Menu_Slug  = "A3SCS";
	$Function   = array( "A3SCS", "Admin_Page" );

	add_options_page( $Page_Title, $Menu_Title, $Capability, $Menu_Slug, $Function );
}

//*************************************************************
// A3SCS >> Admin: Page
// NOTES >> Build admin page HTML markup.
//*************************************************************
public static function Admin_Page()
{
	if ( ! current_user_can( "manage_options" ) )
		wp_die( "You do not have sufficient permissions to access this page." );

	include_once A3SCS_DIR . "/Admin/Admin.php";
}

//*************************************************************
// A3SCS >> Admin: AJAX
// NOTES >> Redirects AJAX traffic to new destination.
//*************************************************************
public static function Admin_AJAX()
{
	require_once A3SCS_DIR . "/Classes/A3SCS-AJAX.php";

	call_user_func( array( "A3SCS_Admin_AJAX", $_POST['funct'] ) );

	die();
}

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

//*************************************************************
// A3SCS >> Upgrade
// NOTES >> Performs version check and potential upgrades.
//*************************************************************
public static function Upgrade()
{
	$Current_Version = get_option( "A3SCS_Version" );

	// VERSION 1.0.2: Check for outmoded values, convert them.
	if ( version_compare( $Current_Version, "1.0.2" ) < 0 )
	{
		require_once A3SCS_DIR . "/Classes/A3SCS-Upgrade.php";
		A3SCS_Upgrade::Upgrade_102();
	}

	// Update Version to Current
	if ( version_compare( $Current_Version, A3SCS_VERSION ) < 0 )
		update_option( "A3SCS_Version", A3SCS_VERSION );
}

//*************************************************************
// A3SCS >> Uninstall
// NOTES >> Remove options for clean uninstall.
//*************************************************************
public static function Uninstall()
{
	delete_option( "A3SCS" );
	delete_option( "A3SCS_Version" );
}

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

//*************************************************************
// A3SCS >> Slashes
// PARAM >> String | Value
// NOTES >> Add slashes to string if magic quotes is not on.
//*************************************************************
public static function Slashes( $Value )
{
	if ( ! get_magic_quotes_gpc() ) $Value = addslashes( $Value );
	$Value = str_replace( "\"", "&quot;", $Value );
	return $Value;
}

//**********************************************************
// A3SCS >> String Cut
// PARAM >> String | Str
// NOTES >> Returns a cut string with ellipses if too long.
//**********************************************************
public static function String_Cut( $Str )
{
	if ( strlen( $Str ) >= 160 ) $Str = substr( $Str, 0, 157 ) . "...";
	return $Str;
}

//*************************************************************
// A3SCS >> Check Page Type
// PARAM >> Array | $Options
// NOTES >> Checks current page type to determine display.
//*************************************************************
public static function Check_Page_Type( $Options )
{
	$Options['Display_Front']   = isset( $Options['Display_Front']   ) ? $Options['Display_Front']   : TRUE;
	$Options['Display_Blog']    = isset( $Options['Display_Blog']    ) ? $Options['Display_Blog']    : TRUE;
	$Options['Display_Posts']   = isset( $Options['Display_Posts']   ) ? $Options['Display_Posts']   : TRUE;
	$Options['Display_Pages']   = isset( $Options['Display_Pages']   ) ? $Options['Display_Pages']   : TRUE;
	$Options['Display_Archive'] = isset( $Options['Display_Archive'] ) ? $Options['Display_Archive'] : TRUE;
	$Options['Display_Search']  = isset( $Options['Display_Search']  ) ? $Options['Display_Search']  : TRUE;
	$Options['Display_404']     = isset( $Options['Display_404']     ) ? $Options['Display_404']     : TRUE;

	if ( is_front_page() ) return $Options['Display_Front']   ? TRUE : FALSE;
	if ( is_home()       ) return $Options['Display_Blog']    ? TRUE : FALSE;
	if ( is_single()     ) return $Options['Display_Posts']   ? TRUE : FALSE;
	if ( is_page()       ) return $Options['Display_Pages']   ? TRUE : FALSE;
	if ( is_archive()    ) return $Options['Display_Archive'] ? TRUE : FALSE;
	if ( is_search()     ) return $Options['Display_Search']  ? TRUE : FALSE;
	if ( is_404()        ) return $Options['Display_404']     ? TRUE : FALSE;
	
	return FALSE;
}

//**********************************************************
// A3SCS >> Page URL
// NOTES >> Return the current page URL.
//**********************************************************
public static function Page_URL()
{
	
	return $URL;
}

//*************************************************************
// A3SCS >> Get Data
// PARAM >> Array  | Options
// NOTES >> Determine general data and return array for use.
//*************************************************************
public static function Get_Data( $Options )
{
	global $post;
	
	// DATA >> URL
	if ( $Options['Use_Default_Share_URL'] && ! empty( $Options['Default_Share_URL'] ) )
		$URL = $Options['Default_Share_URL'];
	else
	{
		$URL = isset( $_SERVER['HTTPS'] ) ? "https" : "http";
		$URL = $URL . "://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	}
	
	if ( '' === $URL && $Options['Default_Share_URL'] )
		$URL = $Options['Default_Share_URL'];

	// DATA >> Title
	if ( $Options['Use_Default_Share_Title'] && ! empty( $Options['Default_Share_Title'] ) )
		$Title = $Options['Default_Share_Title'];
	else
	{
		if ( is_home() || is_front_page() )
			$Title = get_bloginfo( "name" );
		else
			$Title = the_title_attribute( "echo=0" );
	}

	if ( '' === $Title && $Options['Default_Share_Title'] )
		$Title = $Options['Default_Share_Title'];

	// DATA >> Description
	if ( $Options['Use_Default_Share_Desc'] && ! empty( $Options['Default_Share_Desc'] ) )
		$Desc = $Options['Default_Share_Desc'];
	else
	{
		if ( is_singular() )
		{
			// Use Custom Excerpt
			if ( has_excerpt( $post->ID ) )
				$Desc = strip_tags( get_the_excerpt( $post->ID ) );
			else
			{
				$Desc = strip_tags( strip_shortcodes( $post->post_content ) );
				$Desc = str_replace( "\r\n", " " , substr( $Desc, 0, 160 ) );
			}
		}
		else $Desc = get_bloginfo( "description" );
	}

	if ( '' === $Desc && $Options['Default_Share_Desc'] )
		$Desc = $Options['Default_Share_Desc'];

	return array(
		"Title" => A3SCS::String_Cut( $Title ),
		"Desc"  => A3SCS::String_Cut( $Desc ),
		"URL"   => $URL
	);
}

//*************************************************************
// A3SCS >> Build: Share
// PARAM >> Array  | Options
// PARAM >> Array  | Page_Data
// PARAM >> String | Share_Type
// NOTES >> Construct share link URL.
//*************************************************************
public static function Build_Share( $Options, $Page_Data, $Share_Type )
{
	$URL  = "http://api.addthis.com/oexchange/0.8/forward/";
	$URL .= strtolower( str_replace( "AddThis_", '', $Share_Type ) ) . "/offer?";
	$URL .= http_build_query( array(
		"url"         => $Page_Data['URL'],
		"title"       => $Page_Data['Title'],
		"description" => $Page_Data['Desc']
	) );
	
	if ( isset( $Options['AddThis_Profile'] ) )
		$URL .= "&pubid=" . $Options['AddThis_Profile'];
	
	return $URL;
}

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

//*************************************************************
// A3SCS >> Style
// NOTES >> Output stylesheet links.
//*************************************************************
public static function Style()
{
	$Options = get_option( "A3SCS" );
	$Mobile  = isset( $Options['Mobile_Enable'] ) ? $Options['Mobile_Enable'] : FALSE;
	$Links   = isset( $Options['Links_List']    ) ? $Options['Links_List']    : FALSE;
	$Manual  = isset( $Options['Manual_Mode']   ) ? $Options['Manual_Mode']   : FALSE;
	$Custom  = isset( $Options['Custom_Enable'] ) ? $Options['Custom_Enable'] : FALSE;
	
	// Check Display
	if ( ! A3SCS::Check_Page_Type( $Options ) && ! $Manual ) return FALSE;
	
	// Main Styles
	if ( $Links )
	{
		if ( $Custom )
		{
			$Custom_BG         = isset( $Options['Custom_BG']         ) ? $Options['Custom_BG']         : "#444444";
			$Custom_Text       = isset( $Options['Custom_Text']       ) ? $Options['Custom_Text']       : "#FFFFFF";
			$Custom_BG_Hover   = isset( $Options['Custom_BG_Hover']   ) ? $Options['Custom_BG_Hover']   : "#444444";
			$Custom_Text_Hover = isset( $Options['Custom_Text_Hover'] ) ? $Options['Custom_Text_Hover'] : "#FFFFFF";
			
			$Params = http_build_query( array(
				"BG"         => $Custom_BG,
				"Text"       => $Custom_Text,
				"BG_Hover"   => $Custom_BG_Hover,
				"Text_Hover" => $Custom_Text_Hover
			) );

			wp_enqueue_style( "Social-Sidebar", plugins_url( "/Assets/Styles/Social-Sidebar-Custom.php?" . $Params, dirname( __FILE__ ) ) );	
		}
		else wp_enqueue_style( "Social-Sidebar", plugins_url( "/Assets/Styles/Social-Sidebar.min.css", dirname( __FILE__ ) ) );		
	}

	// Mobile Styles
	if ( $Mobile && $Links )
	{
		$Mobile_Type  = isset( $Options['Mobile_Type']  ) ? $Options['Mobile_Type']  : "Buttons";
		$Mobile_Width = isset( $Options['Mobile_Width'] ) ? $Options['Mobile_Width'] : "400";
		
		wp_enqueue_style( "Social-Sidebar-Mobile", plugins_url( "/Assets/Styles/Social-Sidebar-Mobile.php?Type=$Mobile_Type&Width=$Mobile_Width", dirname( __FILE__ ) ) );
	}
}

//*************************************************************
// A3SCS >> Build
// PARAM >> Bool | Override
// NOTES >> Overall build function for meta tags.
//*************************************************************
public static function Build( $Override = FALSE )
{
	// Variables
	$Options = get_option( "A3SCS" );
	$Manual  = isset( $Options['Manual_Mode'] ) ? $Options['Manual_Mode'] : FALSE;
	$Styles  = '';
	$Links   = '';
	
	// Check Display
	if ( ! A3SCS::Check_Page_Type( $Options ) && ! $Override ) return FALSE;
	if ( $Manual && ! $Override ) return FALSE;

	// Styles
	$Options['Position'] = isset( $Options['Position'] ) ? $Options['Position'] : "Left";
	$Options['Style']    = isset( $Options['Style']    ) ? $Options['Style']    : "Square";
	$Options['Size']     = isset( $Options['Size']     ) ? $Options['Size']     : "Small";
	$Options['Theme']    = isset( $Options['Theme']    ) ? $Options['Theme']    : "Dark";
	$Options['Label']    = isset( $Options['Label']    ) ? $Options['Label']    : "Square";
	
		if ( "Left"   === $Options['Position'] ) $Styles .= "Pos-Left";
	elseif ( "Right"  === $Options['Position'] ) $Styles .= "Pos-Right";
		if ( "Circle" === $Options['Style']    ) $Styles .= " Circle";
		if ( "Large"  === $Options['Size']     ) $Styles .= " Large";
		if ( "Light"  === $Options['Theme']    ) $Styles .= " Theme-Light";
	elseif ( "Trans"  === $Options['Theme']    ) $Styles .= " Theme-Trans";
	elseif ( "Color"  === $Options['Theme']    ) $Styles .= " Theme-Color";
		if ( "Square" === $Options['Label']    ) $Styles .= " Label-Square";
	elseif ( "Curve"  === $Options['Label']    ) $Styles .= " Label-Curve";
	elseif ( "Round"  === $Options['Label']    ) $Styles .= " Label-Round";
	elseif ( "Fancy"  === $Options['Label']    ) $Styles .= " Label-Fancy";
		if ( "Bar"    === $Options['Shadow']   ) $Styles .= " Shadow";
	elseif ( "Links"  === $Options['Shadow']   ) $Styles .= " Shadow-All";
		if ( "Bar"    === $Options['Corners']  ) $Styles .= " Corners";
	elseif ( "Links"  === $Options['Corners']  ) $Styles .= " Corners-All";
	
	// Links
	$Options['Links_List'] = isset( $Options['Links_List'] ) ? $Options['Links_List'] : FALSE;
	$Options['HTML5_Tags'] = isset( $Options['HTML5_Tags'] ) ? $Options['HTML5_Tags'] : TRUE;
	
	if ( ! $Options['Links_List'] ) return;
	
	$Link_List = unserialize( base64_decode( $Options['Links_List'] ) );
	
	foreach ( $Link_List as $Link )
	{
		$Type = $Link->Type;
		$Name = $Link->Name;
		$URL  = $Link->URL;
		$Icon = $Link->Icon;

		$NoFollow   = "True" === $Link->NoFollow  ? " rel='nofollow'"  : '';
		$NewWindow  = "True" === $Link->NewWindow ? " target='_blank'" : '';
		$Share_Type = isset( $Link->Share_Type )  ? $Link->Share_Type  : '';
		
		if ( "Share" === $Type )
		{
			$Page_Data = isset( $Page_Data ) ? $Page_Data : A3SCS::Get_Data( $Options );
			$URL = A3SCS::Build_Share( $Options, $Page_Data, $Share_Type );
		}
		
		$Links .= "<li><a href='$URL' class='$Icon'$NoFollow$NewWindow><span>$Name</span></a></li>";
	}
	
	// Begin Tag Output
	echo "<!-- A3 / Social Sidebar -->\n";
	
	if ( isset( $Options['Custom_CSS'] ) && ! empty( $Options['Custom_CSS'] ) )
		echo "<style>\n" . base64_decode( $Options['Custom_CSS'] ) . "\n</style>";
	
	if ( $Options['HTML5_Tags'] )
	{
		echo "<aside id='Social-Sidebar' class='$Styles'>";
		echo "<ul>$Links</ul>";
		echo "</aside>";
	}
	else
	{
		echo "<div id='Social-Sidebar' class='$Styles'>";
		echo "<ul>$Links</ul>";
		echo "</div>";
	}
}

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

} // End Class
?>