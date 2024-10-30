<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//Called from the main file, it will include all needed php files for the plugin
function hst_Includes_IncludeFiles()
{

	//priority files
	include( hst_consts_pluginPath . '/bl/globals.php' );

	//including loggin utility
	include( hst_consts_pluginPath . '/utils/log/logger.php' );

	//including bl files
	include( hst_consts_pluginPath . '/bl/dbinstall.php' );
	include( hst_consts_pluginPath . '/bl/client_resources.php' );
	include( hst_consts_pluginPath . '/bl/common.php' );
	include( hst_consts_pluginPath . '/bl/pages.php' );
	include( hst_consts_pluginPath . '/bl/shortcodes.php' );

	//including entities
	include( hst_consts_pluginPath . '/entities/entity_ajaxresponse.php' );
	include( hst_consts_pluginPath . '/entities/entity_ticket.php' );
	include( hst_consts_pluginPath . '/entities/entity_ticket_category.php' );
	include( hst_consts_pluginPath . '/entities/entity_ticket_event.php' );
	include( hst_consts_pluginPath . '/entities/entity_ticket_priority.php' );
	include( hst_consts_pluginPath . '/entities/entity_ticket_status.php' );
	include( hst_consts_pluginPath . '/entities/entity_customer.php' );
	include( hst_consts_pluginPath . '/entities/entity_user.php' );
	include( hst_consts_pluginPath . '/entities/entity_attachment.php' );

	//including classes
	include( hst_consts_pluginPath . '/classes/class_ticket.php' );
	include( hst_consts_pluginPath . '/classes/class_ticket_category.php' );
	include( hst_consts_pluginPath . '/classes/class_ticket_event.php' );
	include( hst_consts_pluginPath . '/classes/class_ticket_priority.php' );
	include( hst_consts_pluginPath . '/classes/class_ticket_status.php' );
	include( hst_consts_pluginPath . '/classes/class_customer.php' );
	include( hst_consts_pluginPath . '/classes/class_user.php' );
	include( hst_consts_pluginPath . '/classes/class_attachments.php' );
	include( hst_consts_pluginPath . '/classes/class_notification.php' );

	//including ajax files
	include( hst_consts_pluginPath . '/ajax/ajax_dashboard_tickets.php' );
	include( hst_consts_pluginPath . '/ajax/ajax_dashboard_home.php' );
	include( hst_consts_pluginPath . '/ajax/ajax_dashboard_shared.php' );
	include( hst_consts_pluginPath . '/ajax/ajax_dashboard_customers.php' );
	include( hst_consts_pluginPath . '/ajax/ajax_dashboard_settings_ticket_categories.php' );
	include( hst_consts_pluginPath . '/ajax/ajax_dashboard_settings_notifications.php' );
	include( hst_consts_pluginPath . '/ajax/ajax_frontend_tickets.php' );
	include( hst_consts_pluginPath . '/ajax/ajax_shared_notifications.php' );
	include( hst_consts_pluginPath . '/ajax/ajax_dashboard_settings_generic.php' );
	include( hst_consts_pluginPath . '/ajax/ajax_dashboard_settings_frontend.php' );
	include( hst_consts_pluginPath . '/ajax/ajax_dashboard_settings_system.php' );
	include( hst_consts_pluginPath . '/ajax/ajax_dashboard_users.php' );
	include( hst_consts_pluginPath . '/ajax/ajax_install_wizard.php' );


}


?>