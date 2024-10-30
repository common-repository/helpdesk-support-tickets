<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//registering ajax calls
add_action( 'wp_ajax_hst_ajax_dashboard_settings_ticket_notifications_get', 'hst_ajax_dashboard_settings_ticket_notifications_get' );
add_action( 'wp_ajax_hst_ajax_dashboard_settings_ticket_notifications_update', 'hst_ajax_dashboard_settings_ticket_notifications_update' );
add_action( 'wp_ajax_hst_ajax_dashboard_settings_ticket_notifications_sendtestemail', 'hst_ajax_dashboard_settings_ticket_notifications_sendtestemail' );


//Get a single email notification by its key
//Post[NotificationKey] is mandatory
function hst_ajax_dashboard_settings_ticket_notifications_get()
{

	$methodName = 'hst_ajax_dashboard_settings_ticket_notifications_get';
	$response = new hst_Entities_AjaxResponse();

	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	//checking for mandatory input params
	if ( isset($_POST["NotificationKey"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['NotificationKey'] parameter is missing. Aborting.");
	}
	$postParamsNotificationKey = sanitize_text_field($_POST["NotificationKey"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param NotificationKey: " . hst_Logger_exportVarContent($postParamsNotificationKey, true));

	//getting notification details
	$classNotifications = new hst_Classes_Notification();
	$notificationData = $classNotifications->GetNotificationDataByKey( array( "NotificationKey" => $postParamsNotificationKey ) );

	//building response
	$response->success = 1;
	$response->data = $notificationData;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}


//Updates a single email notification by its key
//Post[NotificationKey] is mandatory
//Post[NotificationEnabled] is mandatory
function hst_ajax_dashboard_settings_ticket_notifications_update()
{

	$methodName = 'hst_ajax_dashboard_settings_ticket_notifications_update';
	$response = new hst_Entities_AjaxResponse();

	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	//checking for mandatory input params
	if ( isset($_POST["NotificationKey"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['NotificationKey'] parameter is missing. Aborting.");
	}
	$postParamsNotificationKey = sanitize_text_field($_POST["NotificationKey"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param NotificationKey: " . hst_Logger_exportVarContent($postParamsNotificationKey, true));
	if ( isset($_POST["NotificationEnabled"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['NotificationEnabled'] parameter is missing. Aborting.");
	}
	$postParamsNotificationEnabled = sanitize_text_field($_POST["NotificationEnabled"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param NotificationEnabled: " . hst_Logger_exportVarContent($postParamsNotificationEnabled, true));

	//getting notification details
	$classNotifications = new hst_Classes_Notification();
	$classNotifications->SaveNotificationDataByKey( array( "NotificationKey" => $postParamsNotificationKey, "NotificationEnabled" => $postParamsNotificationEnabled ) );

	//building response
	$response->success = 1;
	$response->data = null;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}


//Called by he Send Test email button, will generated and send a test email based on the selected Notification key
//Post[NotificationKey] is mandatory
//Post[RecipientEmailAddress] is mandatory
function hst_ajax_dashboard_settings_ticket_notifications_sendtestemail()
{

	$methodName = 'hst_ajax_dashboard_settings_ticket_notifications_sendtestemail';
	$response = new hst_Entities_AjaxResponse();

	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	//checking for mandatory input params
	if ( isset($_POST["NotificationKey"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['NotificationKey'] parameter is missing. Aborting.");
	}
	$postParamsNotificationKey = sanitize_text_field($_POST["NotificationKey"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param NotificationKey: " . hst_Logger_exportVarContent($postParamsNotificationKey, true));
	if ( isset($_POST["RecipientEmailAddress"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['RecipientEmailAddress'] parameter is missing. Aborting.");
	}
	$postParamsRecipientEmailAddress = sanitize_text_field($_POST["RecipientEmailAddress"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param RecipientEmailAddress: " . hst_Logger_exportVarContent($postParamsRecipientEmailAddress, true));

	//fields validation
	if ($postParamsRecipientEmailAddress == "")
	{
		hst_Common_ReturnAjaxValidationMessage($methodName, "Please type the Email Address to send the test to.", "hst-controls-tickets-new-txt-testrecipientemailaddress");
	}

	$classNotifications = new hst_Classes_Notification();
	$paramsFireNotifications = array();
	$paramsFireNotifications["NotificationKey"] = $postParamsNotificationKey;
	$paramsFireNotifications["IsTest"] = 1;
	$paramsFireNotifications["TestRecipientEmail"] = $postParamsRecipientEmailAddress;
	$resultFireNotification = $classNotifications->FireNotification( $paramsFireNotifications );

	if ( $resultFireNotification == true )
	{

		//building response
		$response->success = 1;
		$response->data = null;

	}
	else
	{

		hst_Common_ReturnAjaxError($methodName, "Could not send email. Please make sure that your website is capable to send emails and that the Helpdesk email address is set.");

	}

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}

?>