<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//registering ajax calls
add_action( 'wp_ajax_hst_ajax_dashboard_settings_generic_load', 'hst_ajax_dashboard_settings_generic_load' );
add_action( 'wp_ajax_hst_ajax_dashboard_settings_generic_update', 'hst_ajax_dashboard_settings_generic_update' );



//Load the generic settings
function hst_ajax_dashboard_settings_generic_load()
{

	$methodName = 'hst_ajax_dashboard_settings_generic_load';
	$response = new hst_Entities_AjaxResponse();

	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	//retrieve settings and builds an array for response
	$responseArray = array();
	$responseArray["hstHelpdeskDefaultAgentUserId"] = get_option( hst_consts_optionKeyHelpdeskDefaultAgentUserId, "-1" );
	$responseArray["hstHelpdeskEmailAddress"] = get_option( hst_consts_optionKeyHelpdeskEmailAddress, "" );
	$responseArray["hstHelpdeskEmailSenderName"] = get_option( hst_consts_optionKeyHelpdeskEmailSenderName, "" );
	$responseArray["hstHelpdeskAttachmentsAllowed"] = get_option( hst_consts_optionKeyHelpdeskAttachmentsAllowed, "0" );
	$responseArray["hstHelpdeskAttachmentsExtensions"] = get_option( hst_consts_optionKeyHelpdeskAttachmentsExtensions, "" );

	//getting user display name for the hstHelpdeskDefaultAgentUserId option value
	if ( $responseArray["hstHelpdeskDefaultAgentUserId"] != "-1" )
	{

		//getting the user data
		$classUser = new hst_Classes_User();
		$entityUser = $classUser->GetFromDB( array( UserId => $responseArray["hstHelpdeskDefaultAgentUserId"] ) ) ;
		$responseArray["hstHelpdeskDefaultAgentUserDisplayName"] = $entityUser->UserDisplayName;

	}

	//building response
	$response->success = 1;
	$response->data = $responseArray;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}



//Saves the generic settings
//Post[hstHelpdeskDefaultAgentUserId] is mandatory
//Post[hstHelpdeskEmailAddress] is mandatory
//Post[hstHelpdeskEmailSenderName] is mandatory
//Post[hstHelpdeskAttachmentsAllowed] is mandatory
//Post[hstHelpdeskAttachmentsExtensions] is mandatory
function hst_ajax_dashboard_settings_generic_update()
{

	$methodName = 'hst_ajax_dashboard_settings_generic_update';
	$response = new hst_Entities_AjaxResponse();

	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	//checking for mandatory input params
	if ( isset($_POST["hstHelpdeskDefaultAgentUserId"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['hstHelpdeskDefaultAgentUserId'] parameter is missing. Aborting.");
	}
	$postParamshstHelpdeskDefaultAgentUserId = sanitize_text_field($_POST["hstHelpdeskDefaultAgentUserId"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param hstHelpdeskDefaultAgentUserId: " . hst_Logger_exportVarContent($postParamshstHelpdeskDefaultAgentUserId, true));
	if ( isset($_POST["hstHelpdeskEmailAddress"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['hstHelpdeskEmailAddress'] parameter is missing. Aborting.");
	}
	$postParamshstHelpdeskEmailAddress = sanitize_text_field($_POST["hstHelpdeskEmailAddress"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param hstHelpdeskEmailAddress: " . hst_Logger_exportVarContent($postParamshstHelpdeskEmailAddress, true));
	if ( isset($_POST["hstHelpdeskEmailSenderName"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['hstHelpdeskEmailSenderName'] parameter is missing. Aborting.");
	}
	$postParamshstHelpdeskEmailSenderName = sanitize_text_field($_POST["hstHelpdeskEmailSenderName"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param hstHelpdeskEmailSenderName: " . hst_Logger_exportVarContent($postParamshstHelpdeskEmailSenderName, true));
	if ( isset($_POST["hstHelpdeskAttachmentsAllowed"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['hstHelpdeskAttachmentsAllowed'] parameter is missing. Aborting.");
	}
	$postParamshstHelpdeskAttachmentsAllowed = sanitize_text_field($_POST["hstHelpdeskAttachmentsAllowed"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param hstHelpdeskAttachmentsAllowed: " . hst_Logger_exportVarContent($postParamshstHelpdeskAttachmentsAllowed, true));
	if ( isset($_POST["hstHelpdeskAttachmentsExtensions"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['hstHelpdeskAttachmentsExtensions'] parameter is missing. Aborting.");
	}
	$postParamshstHelpdeskAttachmentsExtensions = sanitize_text_field($_POST["hstHelpdeskAttachmentsExtensions"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param hstHelpdeskAttachmentsExtensions: " . hst_Logger_exportVarContent($postParamshstHelpdeskAttachmentsExtensions, true));

	//fields validation
	if ($postParamshstHelpdeskEmailAddress == "")
	{
		hst_Common_ReturnAjaxValidationMessage($methodName, "Please type the Helpdesk Email Address.", "hst-controls-settings-generic-txtHelpdeskEmailAddress");
	}
	if ( is_email($postParamshstHelpdeskEmailAddress) == false )
	{
		hst_Common_ReturnAjaxValidationMessage($methodName, "Please type a valid Email Address.", "hst-controls-settings-generic-txtHelpdeskEmailAddress");
	}
	if ($postParamshstHelpdeskEmailSenderName == "")
	{
		hst_Common_ReturnAjaxValidationMessage($methodName, "Please type the Sender Name to be used in Email Notifications.", "hst-controls-settings-generic-txtHelpdeskEmailSenderName");
	}

	//saving options
	update_option( hst_consts_optionKeyHelpdeskDefaultAgentUserId, $postParamshstHelpdeskDefaultAgentUserId );
	update_option( hst_consts_optionKeyHelpdeskEmailAddress, $postParamshstHelpdeskEmailAddress );
	update_option( hst_consts_optionKeyHelpdeskEmailSenderName, $postParamshstHelpdeskEmailSenderName );
	update_option( hst_consts_optionKeyHelpdeskAttachmentsAllowed, $postParamshstHelpdeskAttachmentsAllowed );
	update_option( hst_consts_optionKeyHelpdeskAttachmentsExtensions, $postParamshstHelpdeskAttachmentsExtensions );

	//building response
	$response->success = 1;
	$response->data = "";

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}

?>