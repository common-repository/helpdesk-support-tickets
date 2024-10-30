<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//Adds the client needed resources for the dashboard page
function hst_ClientResources_InjectDashboardResources()
{

	$methodName = 'hst_ClientResources_InjectDashboardResources';

	hst_Logger_AddEntry( 'FUNCTION', $methodName, 'Function has started' );

	require_once(ABSPATH .'wp-includes/pluggable.php');

	hst_ClientResources_EnqueueResourceStyleFile( 'hst-css-reset', 'css/reset.css' );
	hst_ClientResources_EnqueueResourceStyleFile( 'hst-css-bootstrap', 'css/vendors/bootstrap.css' );
	hst_ClientResources_EnqueueResourceStyleFile( 'hst-css-animate', 'css/vendors/animate.css' );
	hst_ClientResources_EnqueueResourceStyleFile( 'hst-css-fontawesome', 'css/vendors/font-awesome.min.css' );
	hst_ClientResources_EnqueueResourceStyleFile( 'hst-css-modaleffects', 'css/vendors/modalEffects.css' );
	hst_ClientResources_EnqueueResourceStyleFile( 'hst-css-sweetalert2', 'css/vendors/sweetAlert.min.css' );
	hst_ClientResources_EnqueueResourceStyleFile( 'hst-css-dashboard', 'css/dashboard.css' );
	hst_ClientResources_EnqueueResourceStyleFile( 'hst-css-dashboard-tickets', 'css/dashboard_tickets.css' );
	hst_ClientResources_EnqueueResourceStyleFile( 'hst-css-dashboard-home', 'css/dashboard_home.css' );
	
	
// 	//Vendors CSS - Reset
// 	$reset_css_ver  = date("ymd-Gis", filemtime( hst_consts_pluginPath . 'css/reset.css' ));
// 	wp_enqueue_style( 'hst-css-reset', hst_consts_pluginUrl . '/css/reset.css', array(), $reset_css_ver);

// 	//Vendors CSS - Bootstrap
// 	$bootstrap_css_ver  = date("ymd-Gis", filemtime( hst_consts_pluginPath . 'css/vendors/bootstrap.css' ));
// 	wp_enqueue_style( 'hst-css-bootstrap', hst_consts_pluginUrl . '/css/vendors/bootstrap.css', array(), $bootstrap_css_ver);

// 	//Vendors CSS - Animate
// 	$animate_css_ver  = date("ymd-Gis", filemtime( hst_consts_pluginPath . 'css/vendors/animate.css' ));
// 	wp_enqueue_style( 'hst-css-animate', hst_consts_pluginUrl . '/css/vendors/animate.css', array(), $animate_css_ver);

// 	//Vendors CSS - FontAwesome
// 	$fontawesome_css_ver  = date("ymd-Gis", filemtime( hst_consts_pluginPath . 'css/vendors/font-awesome.min.css' ));
// 	wp_enqueue_style( 'hst-css-fontawesome', hst_consts_pluginUrl . '/css/vendors/font-awesome.min.css', array(), $fontawesome_css_ver);

// 	//Vendors CSS - ModalEffects
// 	$modaleffects_css_ver  = date("ymd-Gis", filemtime( hst_consts_pluginPath . 'css/vendors/modalEffects.css' ));
// 	wp_enqueue_style( 'hst-css-modaleffects', hst_consts_pluginUrl . '/css/vendors/modalEffects.css', array(), $modaleffects_css_ver);

// 	//Vendors CSS - SweetAlert2 
// 	$sweetalert2_css_ver  = date("ymd-Gis", filemtime( hst_consts_pluginPath . 'css/vendors/sweetAlert.min.css' ));
// 	wp_enqueue_style( 'hst-css-sweetalert2', hst_consts_pluginUrl . '/css/vendors/sweetAlert.min.css', array(), $sweetalert2_css_ver);

// 	//CSS - Dashboard
// 	$dashboard_css_ver  = date("ymd-Gis", filemtime( hst_consts_pluginPath . 'css/dashboard.css' ));
// 	wp_enqueue_style( 'hst-css-dashboard', hst_consts_pluginUrl . '/css/dashboard.css', array(), $dashboard_css_ver);

// 	//CSS - Dashboard Tickets
// 	$dashboard_tickets_css_ver  = date("ymd-Gis", filemtime( hst_consts_pluginPath . 'css/dashboard_tickets.css' ));
// 	wp_enqueue_style( 'hst-css-dashboard-tickets', hst_consts_pluginUrl . '/css/dashboard_tickets.css', array(), $dashboard_tickets_css_ver);

// 	//CSS - Dashboard Home
// 	$dashboard_home_css_ver  = date("ymd-Gis", filemtime( hst_consts_pluginPath . 'css/dashboard_home.css' ));
// 	wp_enqueue_style( 'hst-css-dashboard-home', hst_consts_pluginUrl . '/css/dashboard_home.css', array(), $dashboard_home_css_ver);

	wp_enqueue_script( 'jquery' );

	hst_ClientResources_EnqueueResourceScriptFile('bootstrap-js','scripts/vendors/bootstrap.js');
	hst_ClientResources_EnqueueResourceScriptFile('notify-js','scripts/vendors/bootstrap-notify.min.js');
	hst_ClientResources_EnqueueResourceScriptFile('datatabe-js','scripts/vendors/dataTable.min.js');
	hst_ClientResources_EnqueueResourceScriptFile('modalEffects-js','scripts/vendors/modalEffects.js');
	hst_ClientResources_EnqueueResourceScriptFile('sweetalert-js','scripts/vendors/sweetAlert.min.js');
	hst_ClientResources_EnqueueResourceScriptFile('chart-js','scripts/vendors/chart.min.js');
	
	hst_ClientResources_EnqueueResourceScriptFile('hst-shared-js','scripts/shared.js');
	hst_ClientResources_EnqueueResourceScriptFile('hst-dashboard-js','scripts/dashboard.js');
	hst_ClientResources_EnqueueResourceScriptFile('hst-dashboard-home-js','scripts/dashboard_home.js');
	hst_ClientResources_EnqueueResourceScriptFile('hst-dashboard-tickets-js','scripts/dashboard_tickets.js');
	hst_ClientResources_EnqueueResourceScriptFile('hst-dashboard-settings-js','scripts/dashboard_settings.js');
	hst_ClientResources_EnqueueResourceScriptFile('hst-dashboard-settings-categories-js','scripts/dashboard_settings_categories.js');
	hst_ClientResources_EnqueueResourceScriptFile('hst-dashboard-settings-notifications-js','scripts/dashboard_settings_notifications.js');
	hst_ClientResources_EnqueueResourceScriptFile('hst-dashboard-settings-generic-js','scripts/dashboard_settings_generic.js');
	hst_ClientResources_EnqueueResourceScriptFile('hst-dashboard-settings-frontend-js','scripts/dashboard_settings_frontend.js');
	hst_ClientResources_EnqueueResourceScriptFile('hst-dashboard-settings-system-js','scripts/dashboard_settings_system.js');
	hst_ClientResources_EnqueueResourceScriptFile('hst-installation-wizard-js','scripts/installation-wizard.js');

}


//Adds the client needed resources for the frontend page
function hst_ClientResources_InjectFrontEndResources()
{

	$methodName = 'hst_ClientResources_InjectFrontEndResources';

	hst_Logger_AddEntry( 'FUNCTION', $methodName, 'Function has started' );

	require_once(ABSPATH .'wp-includes/pluggable.php');

	hst_ClientResources_EnqueueResourceStyleFile( 'hst-css-reset', 'css/reset.css' );
	hst_ClientResources_EnqueueResourceStyleFile( 'hst-css-bootstrap', 'css/vendors/bootstrap.css' );
	hst_ClientResources_EnqueueResourceStyleFile( 'hst-css-animate', 'css/vendors/animate.css' );
	hst_ClientResources_EnqueueResourceStyleFile( 'hst-css-fontawesome', 'css/vendors/font-awesome.min.css' );
	hst_ClientResources_EnqueueResourceStyleFile( 'hst-css-frontend', 'css/frontend.css' );
	
	hst_ClientResources_EnqueueResourceScriptFile('hst-frontend-js','scripts/frontend.js');
	hst_ClientResources_EnqueueResourceScriptFile('hst-shared-js','scripts/shared.js');
	
	//Vendors CSS - Reset
// 	$reset_css_ver  = date("ymd-Gis", filemtime( hst_consts_pluginPath . 'css/reset.css' ));
// 	wp_enqueue_style( 'hst-css-reset', hst_consts_pluginUrl . '/css/reset.css', array(), $reset_css_ver);

// 	//Vendors CSS - Bootstrap
// 	$bootstrap_css_ver  = date("ymd-Gis", filemtime( hst_consts_pluginPath . 'css/vendors/bootstrap.css' ));
// 	wp_enqueue_style( 'hst-css-bootstrap', hst_consts_pluginUrl . '/css/vendors/bootstrap.css', array(), $bootstrap_css_ver);

// 	//Vendors CSS - Animate
// 	$animate_css_ver  = date("ymd-Gis", filemtime( hst_consts_pluginPath . 'css/vendors/animate.css' ));
// 	wp_enqueue_style( 'hst-css-animate', hst_consts_pluginUrl . '/css/vendors/animate.css', array(), $animate_css_ver);

// 	//Vendors CSS - FontAwesome
// 	$fontawesome_css_ver  = date("ymd-Gis", filemtime( hst_consts_pluginPath . 'css/vendors/font-awesome.min.css' ));
// 	wp_enqueue_style( 'hst-css-fontawesome', hst_consts_pluginUrl . '/css/vendors/font-awesome.min.css', array(), $fontawesome_css_ver);

// 	//Vendors CSS - Frontend
// 	$frontend_css_ver  = date("ymd-Gis", filemtime( hst_consts_pluginPath . 'css/frontend.css' ));
// 	wp_enqueue_style( 'hst-css-frontend', hst_consts_pluginUrl . '/css/frontend.css', array(), $frontend_css_ver);

	//Plugin JS - FrontEnd
// 	$frontend  = date("ymd-Gis", filemtime( hst_consts_pluginPath . 'scripts/frontend.js' ));
// 	wp_register_script( 'hst-frontend-js', hst_consts_pluginUrl . '/scripts/frontend.js', array(), $frontend, false);
// 	wp_localize_script( 'hst-frontend-js', 'hstvars', hst_ClientResources_BuildArrayConstantsForScripts());
// 	wp_enqueue_script( 'hst-frontend-js', hst_consts_pluginUrl . '/scripts/frontend.js');

// 	//Plugin JS - Shared
// 	$shared  = date("ymd-Gis", filemtime( hst_consts_pluginPath . 'scripts/shared.js' ));
// 	wp_register_script( 'hst-shared-js', hst_consts_pluginUrl . '/scripts/shared.js', array(), $shared, false);
// 	wp_localize_script( 'hst-shared-js', 'hstvars', hst_ClientResources_BuildArrayConstantsForScripts());
// 	wp_enqueue_script( 'hst-shared-js', hst_consts_pluginUrl . '/scripts/shared.js');
	


}


// enqueue a single client script resource file. Attaches this current plugin version for caching matters.
// example:
// $elementKey="hst-shared-js"
// $path="scripts/shared.js"
function hst_ClientResources_EnqueueResourceScriptFile( $elementKey, $path )
{
	
	//Plugin JS - Shared
	$version = hst_consts_pluginVersion; //"" date("ymd-Gis", filemtime( hst_consts_pluginPath . $path ));
	wp_register_script( $elementKey, hst_consts_pluginUrl . $path, array(), $version, false);
	wp_localize_script( $elementKey, 'hstvars', hst_ClientResources_BuildArrayConstantsForScripts());
	wp_enqueue_script( $elementKey, hst_consts_pluginUrl . $path);
	
}


// enqueue a single client style resource file. Attaches this current plugin version for caching matters.
// example:
// $elementKey="hst-css-reset"
// $path="css/reset.css"
function hst_ClientResources_EnqueueResourceStyleFile( $elementKey, $path )
{
		
	$version = hst_consts_pluginVersion;
	wp_enqueue_style( $elementKey, hst_consts_pluginUrl . $path, array(), $version );
	
}


//returns the array that will be passed to javascripts files (constants)
function hst_ClientResources_BuildArrayConstantsForScripts()
{

	$methodName = 'hst_ClientResources_BuildArrayConstantsForScripts';
	$result = array();

	hst_Logger_AddEntry( 'FUNCTION', $methodName, 'Function has started' );

	$ajax_nonce = wp_create_nonce( "hst" );

	$result = array(
		'ajaxNonce' => $ajax_nonce,
		'ajaxHandlerUrl' => admin_url( 'admin-ajax.php' ),
		'pluginBaseUrl' => hst_consts_pluginUrl,
		'templateRenderTicketEventsMessages' => hst_templaterendering_ticketevents_messages,
		'templateRenderTicketEventsMessagesSelf' => hst_templaterendering_ticketevents_messagesself,
		'templateRenderTicketEventsDataChange' => hst_templaterendering_ticketevents_datachange,
		'templateRenderTicketEventsDataChangeSelf' => hst_templaterendering_ticketevents_datachangeself,
		'templateRenderTicketFrontEnd' => hst_templaterendering_ticket_frontend,
		'templateRenderTicketEventFrontEndSelf' => hst_templaterendering_ticketevent_frontendself,
		'templateRenderTicketEventFrontEndOthers' => hst_templaterendering_ticketevent_frontendothers,
		'suggestClearLogFile' => hst_Logger_TimeToClearIt(),
		'isInstallWizardCompleted' => get_option( hst_consts_optionInstallWizardCompleted, "0"),
	);

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($result, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	return $result;

}

?>