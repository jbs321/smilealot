//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

var ADMIN = {
	Curr_Section: '',
	Over_Section: "Details",
	Item_List:    '',
	Item_Cnt:     0
	};

jQuery(document).ready(function()
{
	// Bind: Buttons
	jQuery("#Links-Button-Add").click(function() { ADMIN.Item_Add() });

	// Bind: Form
	jQuery("#Header-Button").click( function() { ADMIN.Settings_Save() });
	jQuery("#Settings-Form").submit(function() { ADMIN.Settings_Save() });
	
	// Bind: Sidebar
	jQuery("#Sidebar li").each(function(){
		var ID = jQuery(this).prop("id").replace("Side-", '');	
		jQuery(this).click(function(){ ADMIN.Sidebar(ID) });
	});
	ADMIN.Sidebar("General");
	
	// Bind: Titles / Inner
	jQuery("#Main .Section").each(function(){
		var Count = jQuery(this).find("h2").length;
		
		for (var i = 0, Code = []; i < Count; i++) { Code[i] = ADMIN.Code(5); }
	
		jQuery(this).find("h2").each(function(Index){
			jQuery(this).prop("id", "Toggle-" + Code[Index]);
			jQuery("#Toggle-" + Code[Index]).click(function(){ ADMIN.Toggle(this) });
		});
	
		jQuery(this).find(".Section-Inner").each(function(Index){
			jQuery(this).prop("id", "Inner-" + Code[Index]);
		});		
	});
	
	// Color Picker
	if ( typeof jQuery.wp === "object" && typeof jQuery.wp.wpColorPicker === "function" )
		jQuery(".Color-Picker").wpColorPicker();
		
	// Overlay
	jQuery(window).resize(function()
	{
		var Height = jQuery(window).height();
		if (jQuery("#Overlay").css("display") === "block")
			jQuery("#Overlay").css("min-height", Height);
	});
	
	//Load Items
	ADMIN.Load_Items();
});

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

//**********************************************************
// ADMIN >> Code
// PARAM >> Int | Length
// NOTES >> Generate a randomized code.
//**********************************************************
ADMIN.Code = function(Length)
{	
	Length = Length || 10;
	var Chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
	var Code  = '';

	for (var i = 0; i < Length; i++)
	{
		var Rand = Math.floor(Math.random() * Chars.length);
		Code += Chars.substring(Rand, Rand + 1);
	}
	return Code;
};

//**********************************************************
// ADMIN >> Message
// PARAM >> String | Type
// PARAM >> String | Message
//**********************************************************
ADMIN.Message = function(Type, Message)
{
	Type    = Type    || "Error";
	Message = Message || '';

	var Args = {
		TYPE: Type,
		CODE: ADMIN.Code(20),
		TEXT: Message
	};
	
	var Str = ADMIN.Template("Message", Args);
	jQuery("#Messages").append(Str);
	
	jQuery("#Message-" + Args.CODE).fadeIn(200);
	
	setTimeout(function(){
		jQuery("#Message-" + Args.CODE).fadeOut(200, function(){
			jQuery("#Message-" + Args.CODE).remove();
		});
	}, 7000);
};

//**********************************************************
// ADMIN >> Sidebar
// PARAM >> String | ID
//**********************************************************
ADMIN.Sidebar = function(ID)
{
	ID = ID || "General";
	
	if (ADMIN.Curr_Section !== ID)
	{	
		// Sidebar Links
		jQuery("#Sidebar li").each(function(){
			jQuery(this).prop("class", '');
		});
	
		jQuery("#Side-" + ID).prop("class", "Active");
	
		// Sections
		jQuery("#Main .Section").each(function(){ jQuery(this).hide() });
		jQuery("#Section-" + ID).fadeIn(200);
		
		ADMIN.Curr_Section = ID;
	}
};

//**********************************************************
// ADMIN >> Toggle
// PARAM >> Object | Obj
//**********************************************************
ADMIN.Toggle = function(Obj)
{
	Obj = Obj || "Toggle-0";
	
	try { Obj = Obj.id; } catch(e){}
	Obj = Obj.replace("Toggle-", '');

	if (jQuery("#Toggle-" + Obj).prop("class") !== "Closed")
	{
		jQuery("#Toggle-" + Obj).prop("class", "Closed");
		jQuery("#Inner-"  + Obj).slideUp();
	}
	else
	{
		jQuery("#Toggle-" + Obj).prop("class", '');
		jQuery("#Inner-"  + Obj).slideDown();
	}
};

//**********************************************************
// ADMIN >> Template
// PARAM >> String | Template
// PARAM >> Object | Args
// PARAM >> String | Container
//**********************************************************
ADMIN.Template = function(Template, Args, Container)
{
	var Str = jQuery("#Template-" + Template).html();
		Str = ADMIN.Template_Parse(Str, Args);

	if (Container) $(Container).html(Str);
	return Str;
};

//**********************************************************
// ADMIN >> Template: Parse
// PARAM >> String | Template
// PARAM >> Object | Args
//**********************************************************
ADMIN.Template_Parse = function(Template, Args)
{
	Template = Template.replace(/\{{(.*?)\}}/g,
	function(Markup, Content)
	{
		Content = Content.split('.');
		Count   = Content.length;

		if (Count > 1)
		{
			var Initial = Args[Content[0]];
			var Explode = Content.splice(1, Count);
			for (var i = 0; i < Explode.length; i++)
			{
				Initial = Initial[Explode[i]];
			}
			return Initial;
		}
		else { return Args[Content] }
	});
	return Template;
};

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

//**********************************************************
// PROTO >> String: Title
// NOTES >> Capitalizes the first letter of each word.
//**********************************************************
String.prototype.Title = function()
{
	return this.toLowerCase().replace(/_/g, ' ')
	.replace(/\b([a-z\u00C0-\u00ff])/g,
	function (_, Initial) { return Initial.toUpperCase();
	}).replace(/(\s(?:de|a|o|e|da|do|em|ou|[\u00C0-\u00ff]))\b/ig,
	function (_, Match) { return Match.toLowerCase(); });
};

//**********************************************************
// PROTO >> String: Cut
// PARAM >> Int  | Length
// PARAM >> Bool | Trail
//**********************************************************
String.prototype.Cut = function(Length, Trail)
{
	Length = Length || false;
	Trail  = Trail  || true;

	if (!Length || this.length <= Length) return this.toString();
	else
	{
		if (Trail) return this.substring(0, Length - 3) + "...";
		else { return this.substring(0, Length); }
	}
};

//**********************************************************
// PROTO >> String: Commas
// NOTES >> Add commas to numbers eg: 1,000,000
//**********************************************************
String.prototype.Commas = function()
{
	var Str = this.toString() + '',
		X   = Str.split('.'),
		X1  = X[0],
		X2  = X.length > 1 ? '.' + X[1] : '',
		Rx  = /(\d+)(\d{3})/;
	while (Rx.test(X1)) { X1 = X1.replace(Rx, '$1' + ',' + '$2'); }
	return X1 + X2;
};

//**********************************************************
// PROTO >> String: Search
// PARAM >> String | A
// PARAM >> String | B
// NOTES >> Grab a section of string based on A/B.
//**********************************************************
String.prototype.Search = function(A, B)
{
	var Str = this.toString();
		Str = Str.substring(Str.indexOf(A) + A.length);
		Str = Str.substring(0, Str.indexOf(B));
	return Str;
};

//**********************************************************
// PROTO >> String: Trim
// NOTES >> Trim extra spaces and line breaks.
//**********************************************************
String.prototype.Trim = function()
{
	return $.trim(this.replace(/(\r\n|\n|\r)/gm, " ").replace(/\s+/g, " "));
};

//**********************************************************
// PROTO >> String: Strip
// PARAM >> String | Type
// PARAM >> String | Allowed
// NOTES >> Strip out special characters and HTML tags.
//**********************************************************
String.prototype.Strip = function(Type, Allowed)
{
	var Tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi;
	var PHP  = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;

		 if (Type === "Special") 		  { return this.replace(/[^a-zA-Z 0-9]+/g, ''); }
	else if (Type === "Tags" && !Allowed) { return this.replace(/(<([^>]+)>)/g, '');    }
	else
	{
		Allowed = (((Allowed || '') + '').toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join('');
		return this.replace(PHP, '').replace(Tags, function($0, $1)
		{ return Allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : ' '; });
	}
};

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

//**********************************************************
// ADMIN >> Settings: Save
//**********************************************************
ADMIN.Settings_Save = function()
{
	ADMIN.Build_Items();

	// Get Fields
	var $Form   = jQuery("#Settings-Form");
	var $Fields = $Form.find("input, select, checkbox, textarea");
	var Data    = $Form.serialize();

	// Reset Message
	jQuery("#Messages").html('');

	// Set Button
	jQuery("#Header-Button").hide();
	jQuery("#Header-Loading").show();

	// Disable Fields
	$Fields.prop("disabled", true);
	
	var Send = {
		action: "a3scs_admin",
		funct: "Settings_Save",
		data: Data
	};
	jQuery.post(DATA.AJAX_URL, Send, function(Response) {
		jQuery("#Header-Loading").hide();
		jQuery("#Header-Button").show();
		$Fields.prop("disabled", false);
	
		try
		{
			Response = jQuery.parseJSON(Response);
	
			var Type = Response.type;
			var Msg  = Response.message;
			
			ADMIN.Message(Type, Msg);
		}
		catch(e){ ADMIN.Message("Error", "Save Request Failed: " + e); }
	});
};

//**********************************************************
// ADMIN >> Settings: Reset
// PARAM >> String | Type
//**********************************************************
ADMIN.Settings_Reset = function(Type)
{
	if (Type === "All" || Type === "General")
	{
		jQuery("#Position").val("Left");
		jQuery("#Theme"   ).val("Color");
		jQuery("#Size"    ).val("Small");
		jQuery("#Style"   ).val("Square");
		jQuery("#Label"   ).val("Square");
		jQuery("#Shadow"  ).val('');
		jQuery("#Corners" ).val('');
		
		jQuery("#Display_Front"  ).prop("checked", true);
		jQuery("#Display_Blog"   ).prop("checked", true);
		jQuery("#Display_Posts"  ).prop("checked", true);
		jQuery("#Display_Pages"  ).prop("checked", true);
		jQuery("#Display_Archive").prop("checked", true);
		jQuery("#Display_Search" ).prop("checked", true);
		jQuery("#Display_404"    ).prop("checked", true);
		jQuery("#HTML5_Tags"     ).prop("checked", true);
		jQuery("#Manual_Mode"    ).prop("checked", false);
	}
	
	if (Type === "All" || Type === "Links")
	{
		var List = ADMIN.Item_List.split("|");	
		for (var i = 0, Len = List.length - 1; i < Len; i++)
		{
			ADMIN.Item_Remove(List[i]);
		}
		ADMIN.Build_Items();
	}
	
	if (Type === "All" || Type === "Sharing")
	{
		jQuery("#AddThis_Profile"    ).val('');
		jQuery("#Default_Share_Title").val('');
		jQuery("#Default_Share_Desc" ).val('');
		jQuery("#Default_Share_URL"  ).val('');
		
		jQuery("#Use_Default_Share_Title").prop("checked", false);
		jQuery("#Use_Default_Share_Desc" ).prop("checked", false);
		jQuery("#Use_Default_Share_URL"  ).prop("checked", false);
	}
	
	if (Type === "All" || Type === "Mobile")
	{
		jQuery("#Mobile_Enable").prop("checked", false);
		
		jQuery("#Mobile_Type" ).val("Button");
		jQuery("#Mobile_Width").val("400");
	}
	
	if (Type === "All" || Type === "Colors")
	{
		jQuery("#Custom_Enable").prop("checked", false);
		
		jQuery("#Section-Colors .wp-picker-default").each(function(){
			jQuery(this).click();
		});
	}
	
	if (Type === "All" || Type === "Custom")
	{
		jQuery("#Custom_CSS").val('');
	}
	
	ADMIN.Message("Warning", Type + " Settings Reset!");
};

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

//**********************************************************
// ADMIN >> Load: Items
//**********************************************************
ADMIN.Load_Items = function()
{
	if ( ! LINKS_LIST || LINKS_LIST[0] === '' ) return;

	for (var i = 0; i < LINKS_LIST.length; i++)
	{
		ADMIN.Item_Add(LINKS_LIST[i]);
	}
};

//**********************************************************
// ADMIN >> Item: Add
// PARAM >> Object | Item
//**********************************************************
ADMIN.Item_Add = function(Item)
{
	Item = Item || {};
	
	var Flag = (Item.Name === undefined) ? true : false;
	
	if (Item.Name      === undefined) Item.Name = '';
	if (Item.URL       === undefined) Item.URL  = '';
	if (Item.Type      === undefined) Item.Type = "Link";
	if (Item.NewWindow === undefined) Item.NewWindow = "False";
	if (Item.NoFollow  === undefined) Item.NoFollow  = "False";

	// Generate Code
	var Code = ADMIN.Item_Cnt + ADMIN.Code(20);
	Item.CODE = Code;
	ADMIN.Item_List += Code + "|";
	ADMIN.Item_Cnt++;

	// Create / Append Div
	var Str = ADMIN.Template("Link-Item", Item);
	jQuery("#Links-Sort").append(Str);
	
	// Share Link Display
	if (Item.Type === "Share" && Item.Share_Type !== '')
	{
		jQuery("#A3SCS-" + Code + " .Link-Item-URL").prop("title", "Share Link: " + Item.Share_Type);
		jQuery("#A3SCS-" + Code + " .Link-Item-URL input").val("Share Link: " + Item.Share_Type);
	}

	// Bind Link Buttons
	jQuery("#Link-Item-" + Code + "-Edit"  ).click(function(){ ADMIN.Item_Edit(Code)   });
	jQuery("#Link-Item-" + Code + "-Copy"  ).click(function(){ ADMIN.Item_Copy(Code)   });
	jQuery("#Link-Item-" + Code + "-Remove").click(function(){ ADMIN.Item_Remove(Code) });

	// Display Link Item
	jQuery("#A3SCS-" + Code).hide().fadeIn(400);

	// Display List
	if (jQuery("#Links-List").css("display") === "none") jQuery("#Links-List").fadeIn(400);

	// Links Sort
	jQuery("#Links-Sort").sortable({ 
		placeholder: "ui-state-highlight", 
		handle: ".Link-Item-Handle", 
		update: function(event, ui) { ADMIN.Item_Order() }
	});
	
	if (Flag) ADMIN.Item_Edit(Code);
};

//**********************************************************
// ADMIN >> Item: Order
//**********************************************************
ADMIN.Item_Order = function()
{
	ADMIN.Item_List = '';
	jQuery("#Links-List li div.Link-Item-ID").each(function()
	{
		ADMIN.Item_List += jQuery(this).text() + "|";
	});
};

//**********************************************************
// ADMIN >> Item: Copy
// PARAM >> String | Code
//**********************************************************
ADMIN.Item_Copy = function(Code)
{
	var Item = {};
	
	Item.Icon       = jQuery("#A3SCS-" + Code + "-Icon"      ).val();
	Item.Name       = jQuery("#A3SCS-" + Code + "-Name"      ).val();
	Item.NewWindow  = jQuery("#A3SCS-" + Code + "-NewWindow" ).val();
	Item.NoFollow   = jQuery("#A3SCS-" + Code + "-NoFollow"  ).val();
	Item.Share_Type = jQuery("#A3SCS-" + Code + "-Share_Type").val();
	Item.Type       = jQuery("#A3SCS-" + Code + "-Type"      ).val();
	Item.URL        = jQuery("#A3SCS-" + Code + "-URL"       ).val();
	
	ADMIN.Item_Add(Item);
};

//**********************************************************
// ADMIN >> Item: Remove
// PARAM >> String | Code
//**********************************************************
ADMIN.Item_Remove = function(Code)
{
	var List = ADMIN.Item_List.split("|");	
	for (var Str = '', i = 0, Len = List.length - 1; i < Len; i++)
	{
		if (List[i] != Code)
			Str += List[i] + "|";
	}
	ADMIN.Item_List = Str;
	
	jQuery("#A3SCS-" + Code).hide(200, function()
	{
		jQuery("#A3SCS-" + Code).remove();
		if (ADMIN.Item_List === '') jQuery("#Links-List").hide();
	});
};

//**********************************************************
// ADMIN >> Item: Edit
// PARAM >> String | Code
//**********************************************************
ADMIN.Item_Edit = function(Code)
{
	// Edit Template
	var Str = ADMIN.Template("Link-Edit", {});
	jQuery("#Overlay").html(Str);
	
	// Bind: Overlay Save
	jQuery("#Overlay #Overlay-Save").click(function(){ ADMIN.Overlay_Save(Code) });
	
	// Bind: Overlay Close
	jQuery("#Overlay #Overlay-Close").click(function(){
		jQuery("#Overlay").fadeOut(200, function() {
			jQuery("#Overlay").html('');
		});
	});
	
	// Bind: Overlay Tabs
	jQuery("#Edit-Link-Tab-Details").click(function(){ ADMIN.Overlay_Tab("Details") });
	jQuery("#Edit-Link-Tab-Icon"   ).click(function(){ ADMIN.Overlay_Tab("Icon")    });
	
	ADMIN.Overlay_Tab(ADMIN.Over_Section);
	
	// Bind: Link Type
	jQuery("#Edit-Link-Type").change(function(){
		ADMIN.Overlay_Link_Type();
	});
	
	// Bind: Icons
	jQuery("#Overlay-Icon-Search").dblclick(function(){ jQuery(this).val(''); ADMIN.Overlay_Icons() });
	jQuery("#Overlay-Icon-Search").keyup(function(){ ADMIN.Overlay_Icons() });
	
	// Link Content
	var Link_Icon       = jQuery("#A3SCS-" + Code + "-Icon"      ).val();
	var Link_Name       = jQuery("#A3SCS-" + Code + "-Name"      ).val();
	var Link_NewWindow  = jQuery("#A3SCS-" + Code + "-NewWindow" ).val();
	var Link_NoFollow   = jQuery("#A3SCS-" + Code + "-NoFollow"  ).val();
	var Link_Share_Type = jQuery("#A3SCS-" + Code + "-Share_Type").val();
	var Link_Type       = jQuery("#A3SCS-" + Code + "-Type"      ).val();
	var Link_URL        = jQuery("#A3SCS-" + Code + "-URL"       ).val();
	
	jQuery("#Overlay #Edit-Link-Name"    ).val(Link_Name);
	jQuery("#Overlay #Edit-Link-Type"    ).val(Link_Type);
	jQuery("#Overlay #Edit-Link-URL"     ).val(Link_URL);
	jQuery("#Overlay #Edit-Share-Service").val(Link_Share_Type);
	
	if (Link_NewWindow === "True") jQuery("#Overlay #Edit-Link-NewWindow").prop("checked", true);
	if (Link_NoFollow  === "True") jQuery("#Overlay #Edit-Link-NoFollow" ).prop("checked", true);
	
	ADMIN.Icons_Selected = Link_Icon;
	ADMIN.Overlay_Icons();
	ADMIN.Overlay_Link_Type();
	
	// Open Overlay
	var Height = jQuery(window).height();
	jQuery("#Overlay").css("min-height", Height);
	jQuery("#Overlay").fadeIn(200);
};

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

//**********************************************************
// ADMIN >> Overlay: Tab
// PARAM >> String | ID
//**********************************************************
ADMIN.Overlay_Tab = function(ID)
{
	// Hide Boxes
	jQuery(".Overlay-Box-Content").each(function(){
		jQuery(this).hide();
	});
	
	// Reset Tabs
	jQuery(".Overlay-Box-Tab").each(function(){
		jQuery(this).removeClass("Active");
	});
	
	// Set Tab / Section
	jQuery("#Edit-Link-Tab-" + ID).addClass("Active");
	jQuery("#Edit-Link-Section-" + ID).fadeIn(200);
	
	ADMIN.Over_Section = ID;
};

//**********************************************************
// ADMIN >> Overlay: Link Type
//**********************************************************
ADMIN.Overlay_Link_Type = function()
{
	var Val = jQuery("#Edit-Link-Type").val();

	jQuery("#Edit-Toggle-URL, #Edit-Toggle-Services").hide();

	if (Val === "Share") jQuery("#Edit-Toggle-Services").fadeIn(200);
	else jQuery("#Edit-Toggle-URL").fadeIn(200);
};

//**********************************************************
// ADMIN >> Overlay: Icons
//**********************************************************
ADMIN.Overlay_Icons = function()
{
	var Search = jQuery("#Overlay-Icon-Search").val().toLowerCase();
	var Icons  = '';

	for (var i = 0, Len = ICONS.length; i < Len; i++)
	{
		if (Search === '' || ICONS[i].toLowerCase().indexOf(Search) > -1)
		{
			Icons += "<div id='" + ICONS[i] + "' class='Button-Alt' onclick='ADMIN.Overlay_Icon_Select(\"" + ICONS[i] + "\")' title='" + ICONS[i] + "'>";
			Icons += "<i class='" + ICONS_TYPE + " " + ICONS[i] + "'></i></div>";
		}
	}

	jQuery("#Overlay-Icon-List").html(Icons);
	ADMIN.Overlay_Icon_Select(ADMIN.Icons_Selected);
};

//**********************************************************
// ADMIN >> Overlay: Icons (Select)
// PARAM >> String | ID
//**********************************************************
ADMIN.Overlay_Icon_Select = function(ID)
{
	jQuery("#Overlay-Icon-List div").each(function() { jQuery(this).removeClass("Selected"); });
	jQuery("#Overlay-Icon-List #" + ID).addClass("Selected");
	ADMIN.Icons_Selected = ID;
};

//**********************************************************
// ADMIN >> Overlay: Save
// PARAM >> String | Code
//**********************************************************
ADMIN.Overlay_Save = function(Code)
{
	var Link_Icon       = ADMIN.Icons_Selected;
	var Link_Name       = jQuery("#Overlay #Edit-Link-Name").val();
	var Link_NewWindow  = "False";
	var Link_NoFollow   = "False"
	var Link_Share_Type = jQuery("#Overlay #Edit-Share-Service").val();
	var Link_Type       = jQuery("#Overlay #Edit-Link-Type").val();
	var Link_URL        = jQuery("#Overlay #Edit-Link-URL" ).val();

	if (jQuery("#Overlay #Edit-Link-NewWindow").is(":checked")) Link_NewWindow = "True";
	if (jQuery("#Overlay #Edit-Link-NoFollow" ).is(":checked")) Link_NoFollow  = "True";

	jQuery("#A3SCS-" + Code + "-Icon"      ).val(Link_Icon);
	jQuery("#A3SCS-" + Code + "-Name"      ).val(Link_Name);
	jQuery("#A3SCS-" + Code + "-NewWindow" ).val(Link_NewWindow);
	jQuery("#A3SCS-" + Code + "-NoFollow"  ).val(Link_NoFollow);
	jQuery("#A3SCS-" + Code + "-Share_Type").val(Link_Share_Type);
	jQuery("#A3SCS-" + Code + "-Type"      ).val(Link_Type);
	jQuery("#A3SCS-" + Code + "-URL"       ).val(Link_URL);
	
	jQuery("#A3SCS-" + Code + " .Link-Item-Icon").prop("title", Link_Icon);
	jQuery("#A3SCS-" + Code + " .Link-Item-Icon").html('<div class="Icon ' + Link_Icon + '"></div>');
	
	jQuery("#A3SCS-" + Code + " .Link-Item-Name").prop("title", Link_Name);
	jQuery("#A3SCS-" + Code + " .Link-Item-Name input").val(Link_Name);
	
	jQuery("#A3SCS-" + Code + " .Link-Item-URL").prop("title", Link_URL);
	jQuery("#A3SCS-" + Code + " .Link-Item-URL input").val(Link_URL);
	
	if (Link_Type === "Share" && Link_Share_Type !== '')
	{
		jQuery("#A3SCS-" + Code + " .Link-Item-URL").prop("title", "Share Link: " + Link_Share_Type);
		jQuery("#A3SCS-" + Code + " .Link-Item-URL input").val("Share Link: " + Link_Share_Type);
	}
	
	jQuery("#Overlay").fadeOut(200, function() {
		jQuery("#Overlay").html('');
		ADMIN.Icons_Selected = '';
	});
};

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

//**********************************************************
// ADMIN >> Build: Items
//**********************************************************
ADMIN.Build_Items = function()
{
	var List = ADMIN.Item_List.split("|");
	
	for (var Str = '', i = 0, Len = List.length - 1; i < Len; i++)
	{
		try
		{
			var Code = List[i];
			var Temp = {			
				Icon:       jQuery("#A3SCS-" + Code + "-Icon"      ).val(),
				Name:       jQuery("#A3SCS-" + Code + "-Name"      ).val(),
				NewWindow:  jQuery("#A3SCS-" + Code + "-NewWindow" ).val(),
				NoFollow:   jQuery("#A3SCS-" + Code + "-NoFollow"  ).val(),
				Share_Type: jQuery("#A3SCS-" + Code + "-Share_Type").val(),
				Type:       jQuery("#A3SCS-" + Code + "-Type"      ).val(),
				URL:        jQuery("#A3SCS-" + Code + "-URL"       ).val()
			};
			
			Str += JSON.stringify(Temp) + ",";
		}
		catch(e){}		
	}
	
	jQuery("#Links_List").text("[" + Str.slice(0, - 1) + "]");
};