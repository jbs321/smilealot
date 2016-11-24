<?php
/*
Plugin Name:    A3 | Social Sidebar
Plugin URI:     http://a3webtools.com
Description:    Quickly and easily add social links to the side of your WordPress site!
Version:        1.0.3
Author:         A3 Labs, Inc.
Author URI:     http://a3labs.net
Text Domain:    A3SCS
Domain Path:    /Languages/
*/

// Constants
if ( ! defined( "A3SCS_VERSION" ) ) define( "A3SCS_VERSION", "1.0.3" );
if ( ! defined( "A3SCS_DIR"     ) ) define( "A3SCS_DIR", dirname( __FILE__ ) );

// Store Version
add_option( "A3SCS_Version", A3SCS_VERSION );

// Get Functions
require_once A3SCS_DIR . "/Classes/A3SCS.php";

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

// ADMIN: Plugin Page Settings Link
add_filter( "plugin_action_links", array( "A3SCS", "Admin_Link" ), 10, 2 );

// ADMIN: Define Backend Actions
add_action( "admin_init", array( "A3SCS", "Admin_Init" ) );
add_action( "admin_menu", array( "A3SCS", "Admin_Menu" ) );

// ADMIN: AJAX Handling
add_action( "wp_ajax_a3scs_admin", array( "A3SCS", "Admin_AJAX" ) );

// ADMIN: Activate Plugin
if ( function_exists( "register_activation_hook" ) )
	register_activation_hook( __FILE__, array( "A3SCS", "Upgrade" ) );

// ADMIN: Uninstall Plugin
if ( function_exists( "register_uninstall_hook" ) )
	register_uninstall_hook( __FILE__, array( "A3SCS", "Uninstall" ) );

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

// OUTPUT: Set Up Styles / Output HTML
add_action( "wp_enqueue_scripts", array( "A3SCS", "Style" ) );
add_action( "wp_footer",          array( "A3SCS", "Build" ) );

// OUTPUT: Manual Mode Functions
function a3_social_sidebar() { A3SCS::Build( TRUE ); }

// OUTPUT: Manual Mode Shortcode
add_shortcode( "a3_social_sidebar", "a3_social_sidebar" );
?>