<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//registering ajax calls
add_action( 'wp_ajax_hst_ajax_shared_notifications_ticketcreate', 'hst_ajax_shared_notifications_ticketcreate' );
add_action( 'wp_ajax_hst_ajax_shared_notifications_ticketmessagecreate', 'hst_ajax_shared_notifications_ticketmessagecreate' );

add_action( 'wp_ajax_nopriv_hst_ajax_shared_notifications_ticketcreate', 'hst_ajax_shared_notifications_ticketcreate' );
add_action( 'wp_ajax_nopriv_hst_ajax_shared_notifications_ticketmessagecreate', 'hst_ajax_shared_notifications_ticketmessagecreate' );


//To be called on ticket creation event
//POST["TicketId"] is mandatory
function hst_ajax_shared_notifications_ticketcreate()
{

    $methodName = 'hst_ajax_shared_notifications_ticketcreate';
    $response = new hst_Entities_AjaxResponse();

	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	//checking for mandatory input params
	if ( isset($_POST["TicketId"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['TicketId'] parameter is missing. Aborting.");
	}
	$postParamsTicketId = sanitize_text_field($_POST["TicketId"]);

	$classNotifications = new hst_Classes_Notification();

	//calling function that will handle the notification, to Customer
	$notificationParams = array();
	$notificationParams["NotificationKey"] = "TicketCreateCustomer";
	$notificationParams["TicketId"] = $postParamsTicketId;
	$resultNotification = $classNotifications->FireNotification( $notificationParams );

	if ( $resultNotification != "notenabled" )
	{
		if ( $resultNotification == false )
		{
			hst_Common_ReturnAjaxError($methodName, "notificationfailed", "There was a problem in sending the email notification.");
		}
	}

	//calling function that will handle the notification, to Agent
	$notificationParams = array();
	$notificationParams["NotificationKey"] = "TicketCreateAgent";
	$notificationParams["TicketId"] = $postParamsTicketId;
	$resultNotification = $classNotifications->FireNotification( $notificationParams );

	if ( $resultNotification != "notenabled" )
	{
		if ( $resultNotification == false )
		{
			hst_Common_ReturnAjaxError($methodName, "notificationfailed", "There was a problem in sending the email notification.");
		}
	}

	//building response
	$response->success = 1;
	$response->data = null;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}


//To be called on ticket message creation event
//POST["TicketId"] is mandatory
//POST["TicketEventId"] is mandatory
function hst_ajax_shared_notifications_ticketmessagecreate()
{

    $methodName = 'hst_ajax_shared_notifications_ticketmessagecreate';
    $response = new hst_Entities_AjaxResponse();

	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	//checking for mandatory input params
	if ( isset($_POST["TicketId"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['TicketId'] parameter is missing. Aborting.");
	}
	$postParamsTicketId = sanitize_text_field($_POST["TicketId"]);
	if ( isset($_POST["TicketEventId"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['TicketEventId'] parameter is missing. Aborting.");
	}
	$postParamsTicketEventId = sanitize_text_field($_POST["TicketEventId"]);

	$classNotifications = new hst_Classes_Notification();

	//calling function that will handle the notification, to Customer
	$notificationParams = array();
	$notificationParams["NotificationKey"] = "MessageCreateCustomer";
	$notificationParams["TicketId"] = $postParamsTicketId;
	$notificationParams["TicketEventId"] = $postParamsTicketEventId;
	$resultNotification = $classNotifications->FireNotification( $notificationParams );

	if ( $resultNotification != "notenabled" )
	{
		if ( $resultNotification == false )
		{
			hst_Common_ReturnAjaxError($methodName,  "notificationfailed", "There was a problem in sending the email notification.");
		}
	}

	//calling function that will handle the notification, to Agent
	$notificationParams = array();
	$notificationParams["NotificationKey"] = "MessageCreateAgent";
	$notificationParams["TicketId"] = $postParamsTicketId;
	$notificationParams["TicketEventId"] = $postParamsTicketEventId;
	$resultNotification = $classNotifications->FireNotification( $notificationParams );

	if ( $resultNotification != "notenabled" )
	{
		if ( $resultNotification == false )
		{
			hst_Common_ReturnAjaxError($methodName,  "notificationfailed", "There was a problem in sending the email notification.");
		}
	}

	//building response
	$response->success = 1;
	$response->data = null;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}



?>