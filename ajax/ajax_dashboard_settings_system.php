<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//registering ajax calls
add_action( 'wp_ajax_hst_ajax_dashboard_settings_system_load', 'hst_ajax_dashboard_settings_system_load' );
add_action( 'wp_ajax_hst_ajax_dashboard_settings_system_update', 'hst_ajax_dashboard_settings_system_update' );
add_action( 'wp_ajax_hst_ajax_dashboard_settings_system_readlog', 'hst_ajax_dashboard_settings_system_readlog' );
add_action( 'wp_ajax_hst_ajax_dashboard_settings_system_clearlog', 'hst_ajax_dashboard_settings_system_clearlog' );



//Load the system settings
function hst_ajax_dashboard_settings_system_load()
{

	$methodName = 'hst_ajax_dashboard_settings_system_load';
	$response = new hst_Entities_AjaxResponse();
	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	//retrieve settings and builds an array for response
	$responseArray = array();
	$responseArray["hstKeyEventLogLogErrors"] = get_option( hst_consts_optionKeyEventLogLogErrors, 0 );
	$responseArray["hstKeyEventLogLogEmails"] = get_option( hst_consts_optionKeyEventLogLogEmails, 0 );
	$responseArray["hstKeyEventLogLogDebug"] = get_option( hst_consts_optionKeyEventLogLogDebug, 0 );
	$responseArray["hstKeyDeleteTablesUninstall"] = get_option( hst_consts_optionKeyDeleteTablesUninstall, 0 );

	//building response
	$response->success = 1;
	$response->data = $responseArray;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}



//Saves the system settings
//Post[hstKeyEventLogLogErrors] is mandatory
//Post[hstKeyEventLogLogEmails] is mandatory
//Post[hstKeyEventLogLogDebug] is mandatory
function hst_ajax_dashboard_settings_system_update()
{

	$methodName = 'hst_ajax_dashboard_settings_system_update';
	$response = new hst_Entities_AjaxResponse();

	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	//checking for mandatory input params
	if ( isset($_POST["hstKeyEventLogLogErrors"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['hstKeyEventLogLogErrors'] parameter is missing. Aborting.");
	}
	$postParamshstKeyEventLogLogErrors = sanitize_text_field($_POST["hstKeyEventLogLogErrors"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param hstKeyEventLogLogErrors: " . hst_Logger_exportVarContent($postParamshstKeyEventLogLogErrors, true));
	if ( isset($_POST["hstKeyEventLogLogEmails"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['hstKeyEventLogLogEmails'] parameter is missing. Aborting.");
	}
	$postParamshstKeyEventLogLogEmails = sanitize_text_field($_POST["hstKeyEventLogLogEmails"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param hstKeyEventLogLogEmails: " . hst_Logger_exportVarContent($postParamshstKeyEventLogLogEmails, true));
	if ( isset($_POST["hstKeyEventLogLogDebug"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['hstKeyEventLogLogDebug'] parameter is missing. Aborting.");
	}
	$postParamshstKeyEventLogLogDebug = sanitize_text_field($_POST["hstKeyEventLogLogDebug"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param hstKeyEventLogLogDebug: " . hst_Logger_exportVarContent($postParamshstKeyEventLogLogDebug, true));
	if ( isset($_POST["hstKeyDeleteTablesUninstall"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['hstKeyDeleteTablesUninstall'] parameter is missing. Aborting.");
	}
	$postParamshstKeyDeleteTablesUninstall = sanitize_text_field($_POST["hstKeyDeleteTablesUninstall"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param hstKeyDeleteTablesUninstall: " . hst_Logger_exportVarContent($postParamshstKeyDeleteTablesUninstall, true));

	//saving options
	update_option( hst_consts_optionKeyEventLogLogErrors, $postParamshstKeyEventLogLogErrors );
	update_option( hst_consts_optionKeyEventLogLogEmails, $postParamshstKeyEventLogLogEmails );
	update_option( hst_consts_optionKeyEventLogLogDebug, $postParamshstKeyEventLogLogDebug );
	update_option( hst_consts_optionKeyDeleteTablesUninstall, $postParamshstKeyDeleteTablesUninstall );

	//building response
	$response->success = 1;
	$response->data = null;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}



//Reads the log content file
function hst_ajax_dashboard_settings_system_readlog()
{

	$methodName = 'hst_ajax_dashboard_settings_system_readlog';
	$response = new hst_Entities_AjaxResponse();

	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	//retrieve the log content
	$logContent = hst_Logger_ReadLogFile();

	//building response
	$response->success = 1;
	$response->data = $logContent;

	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}



//Clears the log content file
function hst_ajax_dashboard_settings_system_clearlog()
{

	$methodName = 'hst_ajax_dashboard_settings_system_clearlog';
	$response = new hst_Entities_AjaxResponse();

	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	//clears the log content
	hst_Logger_ClearLogFile();

	//building response
	$response->success = 1;
	$response->data = $logContent;


	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}


?>