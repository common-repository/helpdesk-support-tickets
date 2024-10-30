<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//registering ajax calls
add_action( 'wp_ajax_hst_ajax_dashboard_settings_frontend_load', 'hst_ajax_dashboard_settings_frontend_load' );
add_action( 'wp_ajax_hst_ajax_dashboard_settings_frontend_update', 'hst_ajax_dashboard_settings_frontend_update' );


//Load the generic settings
function hst_ajax_dashboard_settings_frontend_load()
{

	$methodName = 'hst_ajax_dashboard_settings_frontend_load';
	$response = new hst_Entities_AjaxResponse();
	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	//retrieve settings and builds an array for response
	$responseArray = array();
	$responseArray["hstFrontEndPageId"] = get_option( hst_consts_optionKeyFrontEndPageId, "" );
	$responseArray["hstFrontEndAllowedUserType"] = get_option( hst_consts_optionKeyFrontEndAllowedUserType, "registered" );

	//building response
	$response->success = 1;
	$response->data = $responseArray;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}



//Saves the generic settings
//Post[hstFrontEndPageId] is mandatory
//Post[hstFrontEndAllowedUserType] is mandatory
function hst_ajax_dashboard_settings_frontend_update()
{

	$methodName = 'hst_ajax_dashboard_settings_frontend_update';
	$response = new hst_Entities_AjaxResponse();

	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	//checking for mandatory input params
	if ( isset($_POST["hstFrontEndPageId"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['hstFrontEndPageId'] parameter is missing. Aborting.");
	}
	$postParamshstFrontEndPageId = sanitize_text_field($_POST["hstFrontEndPageId"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param hstFrontEndPageId: " . hst_Logger_exportVarContent($postParamshstFrontEndPageId, true));
	if ( isset($_POST["hstFrontEndAllowedUserType"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['hstFrontEndAllowedUserType'] parameter is missing. Aborting.");
	}
	$postParamshstFrontEndAllowedUserType = sanitize_text_field($_POST["hstFrontEndAllowedUserType"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param hstFrontEndAllowedUserType: " . hst_Logger_exportVarContent($postParamshstFrontEndAllowedUserType, true));

	//saving options
	update_option( hst_consts_optionKeyFrontEndPageId, $postParamshstFrontEndPageId );
	update_option( hst_consts_optionKeyFrontEndAllowedUserType, $postParamshstFrontEndAllowedUserType );

	//building response
	$response->success = 1;
	$response->data = null;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}

?>