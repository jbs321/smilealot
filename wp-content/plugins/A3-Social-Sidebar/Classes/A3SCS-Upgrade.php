<?php
//**********************************************************
// CLASS >> A3SCS: Upgrade
// NOTES >> Provides upgrades for older installations.
//**********************************************************
class A3SCS_Upgrade {

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%	

//**********************************************************
// A3SCS >> Upgrade 1.0.2
// NOTES >> Converts delimited item list to JSON
//**********************************************************
public static function Upgrade_102()
{
	$Options = get_option( "A3SCS" );
	$Args    = array();
	
	$Options['Links_List'] = isset( $Options['Links_List'] ) ? stripslashes( $Options['Links_List'] ) : FALSE;
	
	if ( ! $Options['Links_List'] ) return;
	
	$Link_List = explode( "|||", $Options['Links_List'] );

	foreach ( $Link_List as $Link )
	{
		$Link = explode( "@@", $Link );

		if ( '' !== $Link[0] )
		{
			$Args[] = array(
				"Type"       => "Link",
				"Name"       => $Link[0],
				"URL"        => $Link[1],
				"Icon"       => $Link[2],
				"NoFollow"   => $Link[3],
				"NewWindow"  => $Link[4],
				"Share_Type" => ''
			);
		}
	}
	
	$Options['Links_List'] = base64_encode( serialize( $Args ) );

	update_option( "A3SCS", $Options );
}

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

} // End Class
?>