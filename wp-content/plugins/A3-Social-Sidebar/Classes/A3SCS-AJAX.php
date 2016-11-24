<?php
//**********************************************************
// CLASS >> A3SCS: Admin AJAX
// NOTES >> Maintaines various admin panel AJAX calls.
//**********************************************************
class A3SCS_Admin_AJAX {

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

//**********************************************************
// A3SCS >> Settings Save
// NOTES >> Sanatizes, converts, and saves settings options.
//**********************************************************
public static function Settings_Save()
{
	// Get Data
	parse_str( $_POST['data'], $Input );
	$Options = get_option( "A3SCS" );

	// Modify Data
	$Options['Position'] = isset( $Input['Position'] ) ? $Input['Position'] : $Options['Position'];
	$Options['Theme']    = isset( $Input['Theme']    ) ? $Input['Theme']    : $Options['Theme'];
	$Options['Size']     = isset( $Input['Size']     ) ? $Input['Size']     : $Options['Size'];
	$Options['Style']    = isset( $Input['Style']    ) ? $Input['Style']    : $Options['Style'];
	$Options['Label']    = isset( $Input['Label']    ) ? $Input['Label']    : $Options['Label'];
	$Options['Shadow']   = isset( $Input['Shadow']   ) ? $Input['Shadow']   : $Options['Shadow'];
	$Options['Corners']  = isset( $Input['Corners']  ) ? $Input['Corners']  : $Options['Corners'];
	
	$Input['Display_Front']   = ! isset( $Input['Display_Front']   ) ? FALSE : TRUE;
	$Input['Display_Blog']    = ! isset( $Input['Display_Blog']    ) ? FALSE : TRUE;
	$Input['Display_Posts']   = ! isset( $Input['Display_Posts']   ) ? FALSE : TRUE;
	$Input['Display_Pages']   = ! isset( $Input['Display_Pages']   ) ? FALSE : TRUE;
	$Input['Display_Archive'] = ! isset( $Input['Display_Archive'] ) ? FALSE : TRUE;
	$Input['Display_Search']  = ! isset( $Input['Display_Search']  ) ? FALSE : TRUE;
	$Input['Display_404']     = ! isset( $Input['Display_404']     ) ? FALSE : TRUE;
	$Input['HTML5_Tags']      = ! isset( $Input['HTML5_Tags']      ) ? FALSE : TRUE;
	$Input['Manual_Mode']     =   isset( $Input['Manual_Mode']     ) ? TRUE : FALSE;
	
	$Options['Display_Front']   = isset( $Input['Display_Front']   ) ? $Input['Display_Front']   : $Options['Display_Front'];
	$Options['Display_Blog']    = isset( $Input['Display_Blog']    ) ? $Input['Display_Blog']    : $Options['Display_Blog'];
	$Options['Display_Posts']   = isset( $Input['Display_Posts']   ) ? $Input['Display_Posts']   : $Options['Display_Posts'];
	$Options['Display_Pages']   = isset( $Input['Display_Pages']   ) ? $Input['Display_Pages']   : $Options['Display_Pages'];
	$Options['Display_Archive'] = isset( $Input['Display_Archive'] ) ? $Input['Display_Archive'] : $Options['Display_Archive'];
	$Options['Display_Search']  = isset( $Input['Display_Search']  ) ? $Input['Display_Search']  : $Options['Display_Search'];
	$Options['Display_404']     = isset( $Input['Display_404']     ) ? $Input['Display_404']     : $Options['Display_404'];
	$Options['HTML5_Tags']      = isset( $Input['HTML5_Tags']      ) ? $Input['HTML5_Tags']      : $Options['HTML5_Tags'];
	$Options['Manual_Mode']     = isset( $Input['Manual_Mode']     ) ? $Input['Manual_Mode']     : $Options['Manual_Mode'];
	
	$Options['Links_List'] = isset( $Input['Links_List'] ) ? base64_encode( serialize( json_decode( str_replace( "\\", '', $Input['Links_List'] ) ) ) ) : $Options['Links_List'];
	
	$Options['AddThis_Profile']     = isset( $Input['AddThis_Profile']     ) ? $Input['AddThis_Profile']     : $Options['AddThis_Profile'];
	$Options['Default_Share_Title'] = isset( $Input['Default_Share_Title'] ) ? $Input['Default_Share_Title'] : $Options['Default_Share_Title'];
	$Options['Default_Share_Desc']  = isset( $Input['Default_Share_Desc']  ) ? $Input['Default_Share_Desc']  : $Options['Default_Share_Desc'];
	$Options['Default_Share_URL']   = isset( $Input['Default_Share_URL']   ) ? $Input['Default_Share_URL']   : $Options['Default_Share_URL'];
	
	$Input['Use_Default_Share_Title'] = isset( $Input['Use_Default_Share_Title'] ) ? TRUE : FALSE;
	$Input['Use_Default_Share_Desc']  = isset( $Input['Use_Default_Share_Desc']  ) ? TRUE : FALSE;
	$Input['Use_Default_Share_URL']   = isset( $Input['Use_Default_Share_URL']   ) ? TRUE : FALSE;
	
	$Options['Use_Default_Share_Title'] = isset( $Input['Use_Default_Share_Title'] ) ? $Input['Use_Default_Share_Title'] : $Options['Use_Default_Share_Title'];
	$Options['Use_Default_Share_Desc']  = isset( $Input['Use_Default_Share_Desc']  ) ? $Input['Use_Default_Share_Desc']  : $Options['Use_Default_Share_Desc'];
	$Options['Use_Default_Share_URL']   = isset( $Input['Use_Default_Share_URL']   ) ? $Input['Use_Default_Share_URL']   : $Options['Use_Default_Share_URL'];
	
	$Input['Custom_Enable']  = isset( $Input['Custom_Enable'] ) ? TRUE : FALSE;
	$Input['Mobile_Enable']  = isset( $Input['Mobile_Enable'] ) ? TRUE : FALSE;
	$Options['Mobile_Width'] = preg_replace( "/\D/", '', $Options['Mobile_Width'] );
	
	$Options['Mobile_Enable'] = isset( $Input['Mobile_Enable'] ) ? $Input['Mobile_Enable'] : $Options['Mobile_Enable'];
	$Options['Mobile_Type']   = isset( $Input['Mobile_Type']   ) ? $Input['Mobile_Type']   : $Options['Mobile_Type'];
	$Options['Mobile_Width']  = isset( $Input['Mobile_Width']  ) ? $Input['Mobile_Width']  : $Options['Mobile_Width'];
	
	$Options['Custom_Enable']     = isset( $Input['Custom_Enable']     ) ? $Input['Custom_Enable']     : $Options['Custom_Enable'];
	$Options['Custom_BG']         = isset( $Input['Custom_BG']         ) ? $Input['Custom_BG']         : $Options['Custom_BG'];
	$Options['Custom_Text']       = isset( $Input['Custom_Text']       ) ? $Input['Custom_Text']       : $Options['Custom_Text'];
	$Options['Custom_BG_Hover']   = isset( $Input['Custom_BG_Hover']   ) ? $Input['Custom_BG_Hover']   : $Options['Custom_BG_Hover'];
	$Options['Custom_Text_Hover'] = isset( $Input['Custom_Text_Hover'] ) ? $Input['Custom_Text_Hover'] : $Options['Custom_Text_Hover'];

	$Options['Custom_CSS'] = isset( $Input['Custom_CSS'] ) ? base64_encode( $Input['Custom_CSS'] ) : $Options['Custom_CSS'];
	
	// Save Options
	update_option( "A3SCS", $Options );
	
	// Send Message
	echo '{"type":"Success","message":"Settings Saved!"}';
}

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

} // End Class
?>