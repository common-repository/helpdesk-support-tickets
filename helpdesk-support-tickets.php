<?php


/**
 * @package HelpdeskSupportTickets
 */
/*
Plugin Name: Helpdesk Support Tickets
Description: A solution for your customer care and support requests management. Please use the Wordpress plugin page to request for assistance or to report bugs. Or contact us via email at wp.helpdeskplugin@gmail.com, thanks!
Version: 1.1.4
Author: HelpdeskSupportTickets Crew
License: GPLv2 or later
Text Domain: hst
 */


//Checks if the ABSPATH is defined, and exits in case it is not
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//Defining some constants
define( 'hst_consts_pluginPath', plugin_dir_path( dirname(__FILE__) . '/helpdesk.php') );
define( 'hst_consts_pluginUrl', plugin_dir_url( dirname(__FILE__) . '/helpdesk.php') );


//including the file that will manage all inclusions
include_once( hst_consts_pluginPath . '/bl/includes.php' );
hst_Includes_IncludeFiles();


//checks for db objects delta
add_action( 'plugins_loaded', 'hst_DbInstall_InstallDatabaseObjects' );


//Plugin init function
function hst_Main_Init()
{

	//Adding menu pages in WP dashbaord
	add_action( 'admin_menu', 'hst_Pages_AddAdminPage' );

	//Handling front end shortcodes
	add_shortcode( 'helpdesk-support-tickets', 'hst_ShortCodes_helpdesksupporttickets' );

	//uninstall procedures hook
	register_uninstall_hook( __FILE__, 'hst_Common_UninstallPlugin' );

}
add_action( 'init', 'hst_Main_Init' );


//Adding Languages
load_plugin_textdomain( 'hst', false, dirname(plugin_basename(__FILE__) ) . '/languages/');

?>
