<?php
//**********************************************************
// ADMIN >> Option
// PARAM >> Array | Options
// PARAM >> Array | Args
// NOTES >> Create admin panel HTML based on args.
// ----------
// DATA MODEL
// String | Option
// String | Type
// String | Label
// Array  | Opt_Val
// Array  | Opt_Lbl
// Bool   | Outer_HTML
// Mixed  | Default
// NOTES >> Opt Fields for select type only.
//**********************************************************
function A3_Admin_Option( $Options = NULL, $Args = NULL )
{
	if ( ! is_array( $Options ) || ! is_array( $Args ) ) return;
	
	$Value = isset( $Options[$Args['Option']] ) ? $Options[$Args['Option']] : FALSE;
	$Field = '';
	
	if ( FALSE === $Value && "Checkbox" !== $Args['Type'] && isset( $Args['Default'] ) ) $Value = $Args['Default'];
	if ( ! isset( $Args['Label'] ) ) $Args['Label'] = '';
	
	if ( "Checkbox" === $Args['Type'] && ! isset( $Options[$Args['Option']] ) ) $Value = $Args['Default'];

	// TYPE: Select
	if ( "Select" === $Args['Type'] )
	{
		if ( ! isset( $Args['Opt_Lbl'] ) ) $Args['Opt_Lbl'] = $Args['Opt_Val'];
	
		$i = 0;
	
		foreach( $Args['Opt_Val'] as $Values )
		{
			$Selected = $Values === $Value ? " selected='selected'" : '';
			$Field .= "<option value='$Values'$Selected>" . $Args['Opt_Lbl'][$i] . "</option>";
			
			$i++;
		}
	
		$Field = "<select id='" . $Args['Option'] . "' name='" . $Args['Option'] . "'>$Field</select>";
		$HTML  = "<tr valign='top'><th scope='row'>
			<label for='" . $Args['Option'] . "'>" . $Args['Label'] . "</label>
			</th><td>$Field</td></tr>";
	}
	
	// TYPE: Text
	elseif ( "Text" === $Args['Type'] )
	{
		$Field = "<input type='text' name='" . $Args['Option'] . "' id='" . $Args['Option'] . "' value='$Value' />";
		$HTML  = "<tr valign='top'><th scope='row'>
			<label for='" . $Args['Option'] . "'>" . $Args['Label'] . "</label>
			</th><td>$Field</td></tr>";
	}
	
	// TYPE: Checkbox
	elseif ( "Checkbox" === $Args['Type'] )
	{
		$Check  = $Value ? " checked='checked'" : '';
		$Field  = "<input type='checkbox' name='" . $Args['Option'] . "' id='" . $Args['Option'] . "'$Check /> ";
		$Field .= "<label for='" . $Args['Option'] . "'>" . $Args['Label'] . "</label>";
		$HTML   = "<tr valign='top'><td>$Field</td></tr>";
	}
	
	// TYPE: Textarea
	elseif ( "Textarea" === $Args['Type'] )
	{
		$Field = "<textarea type='text' name='" . $Args['Option'] . "' id='" . $Args['Option'] . "'>$Value</textarea>";
		$HTML  = "<tr valign='top'><th scope='row'><label for='" . $Args['Option'] . "'>" . $Args['Label'] . "</label></th>
				<tr valign='top'><td>$Field</td></tr>";
	}
	
	// TYPE: Color
	elseif ( "Color" === $Args['Type'] )
	{
		$Field = "<input type='text' name='" . $Args['Option'] . "' id='" . $Args['Option'] . "' class='Color-Picker' value='$Value' data-default-color='" . $Args['Default'] . "' /> ";
		$HTML  = "<tr valign='top'><th scope='row'><label for='" . $Args['Option'] . "'>" . $Args['Label'] . "</label>
			</th><td>$Field</td></tr>";
	}
	
	// Output HTML
	echo ( ! isset( $Args['Outer_HTML'] ) || $Args['Outer_HTML'] ) ? $HTML : $Field;
}
?>