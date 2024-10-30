<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//registering ajax calls
add_action( 'wp_ajax_hst_ajax_dashboard_tickets_list', 'hst_ajax_dashboard_tickets_list' );
add_action( 'wp_ajax_hst_ajax_dashboard_tickets_get', 'hst_ajax_dashboard_tickets_get' );
add_action( 'wp_ajax_hst_ajax_dashboard_tickets_delete', 'hst_ajax_dashboard_tickets_delete' );
add_action( 'wp_ajax_hst_ajax_dashboard_tickets_updatedata', 'hst_ajax_dashboard_tickets_updatedata' );
add_action( 'wp_ajax_hst_ajax_dashboard_tickets_create', 'hst_ajax_dashboard_tickets_create' );
add_action( 'wp_ajax_hst_ajax_dashboard_tickets_events_list', 'hst_ajax_dashboard_tickets_events_list' );
add_action( 'wp_ajax_hst_ajax_dashboard_tickets_events_addmessage', 'hst_ajax_dashboard_tickets_events_addmessage' );



//Load support tickets
//param filterKey to pass in a search term
//param filterStatusId to pass in a status ticket id to filter to
//param returnStatusCounters pass as 1, to have returned also the status counters (simple search tickets badges)
//Response is an array:
//["Tickets"] -> the list of returned tickets
//["StatusCounters"] -> the list of status coutners (if requested by ajax call)
function hst_ajax_dashboard_tickets_list()
{

	$methodName = 'hst_ajax_dashboard_tickets_list';
	$response = new hst_Entities_AjaxResponse();
	$resultArray = array();

	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	//checking for input params
	$postParamsFilterKey = "";
	if ( isset($_POST["filterKey"]))
	{
		$postParamsFilterKey = sanitize_text_field($_POST["filterKey"]);
		hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param FilterKry: " . hst_Logger_exportVarContent($postParamsFilterKey, true));
	}
	$postParamsFilterStatusId = "";
	if ( isset($_POST["filterStatusId"]))
	{
		$postParamsFilterStatusId = sanitize_text_field($_POST["filterStatusId"]);
		hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param FilterStatusId: " . hst_Logger_exportVarContent($postParamsFilterStatusId, true));
	}
	$postParamsReturnStatusCounters = 0;
	if ( isset($_POST["returnStatusCounters"]))
	{
		$postParamsReturnStatusCounters = sanitize_text_field($_POST["returnStatusCounters"]);
		hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param returnStatusCounters: " . hst_Logger_exportVarContent($postParamsReturnStatusCounters, true));
	}

	//calling service to get tickets for dashboard list
	$classTicket = new hst_Classes_Ticket();
	$paramsTicketsList = array();
	if ( $postParamsFilterStatusId != "")
	{
		$paramsTicketsList["TicketStatusId"] = $postParamsFilterStatusId;
	}
	if ( $postParamsFilterKey != "")
	{
		$paramsTicketsList["FilterKey"] = $postParamsFilterKey;
	}
	hst_Logger_AddEntry("DEBUG", $methodName, "Params array for ticket list function: " . hst_Logger_exportVarContent($paramsTicketsList, true));
	$listTickets = $classTicket->ListFromDB($paramsTicketsList);

	if ( is_wp_error( $listTickets ) )
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "An error has occurred.");
	}

	//getting the list of tickets statuses in order to include the status color in the list of tickets returned
	$classTicketStatus = new hst_Classes_Ticket_Status();
	$listTicketsStatus = $classTicketStatus->ListFromDB( array() );

	//adjusting the ticket list with additional data
	foreach( $listTickets as $ticketListItem )
	{

		//we need to find the status in the list of statuses found before. In this way we can attach the status color to this ticket returned record.
		foreach( $listTicketsStatus as $ticketsStatusEntity )
		{
			if ( $ticketListItem->TicketStatusId == $ticketsStatusEntity->TicketStatusId )
			{
				$ticketListItem->StatusBgColor = $ticketsStatusEntity->TicketStatusBgColor;
			}
		}

	}

	//retrieves status list, if demanded
	$statusCounters = array();
	if ( $postParamsReturnStatusCounters == 1 )
	{

		hst_Logger_AddEntry("DEBUG", $methodName, "Start retrieve status counters list" );

		//retrieves status list with counters
		$statusCounters = $classTicket->ReturnStatusWithTicketsCounters( null );

		if ( is_wp_error( $statusCounters ) )
		{
			hst_Common_ReturnAjaxError($methodName, "generic", "An error has occurred.");
		}

		hst_Logger_AddEntry("DEBUG", $methodName, "Finished retrieving status counters list" );

	}

	//building object for response
	$resultArray["Tickets"] = $listTickets;
	$resultArray["StatusCounters"] = $statusCounters;

	//building response
	$response->success = 1;
	$response->data = $resultArray;


	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}


//Get single support ticket
//$_POST["TicketId"] is a mandatory param
//$_POST["IncludeEvents"] is an optional param. If set to "1", this function will also return the ticket events
//$_POST["MakeDescriptionAsFirstEvent"] this will transform the ticket problem field into the first event of type message.
function hst_ajax_dashboard_tickets_get()
{

	$methodName = 'hst_ajax_dashboard_tickets_get';
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
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param TicketId: " . hst_Logger_exportVarContent($postParamsTicketId, true));

	$postParamsIncludeEvents = sanitize_text_field($_POST["IncludeEvents"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param IncludeEvents: " . hst_Logger_exportVarContent($postParamsIncludeEvents, true));

	$postParamsMakeDescriptionAsFirstEvent = sanitize_text_field($_POST["MakeDescriptionAsFirstEvent"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param MakeDescriptionAsFirstEvent: " . hst_Logger_exportVarContent($postParamsMakeDescriptionAsFirstEvent, true));

	//calling service to get tickets for dashboard list
	$dataproviderTicketsGetByIdParams = array();
	$dataproviderTicketsGetByIdParams["TicketId"] = $postParamsTicketId;
	$dataproviderTicketsGetByIdParams["IncludeEvents"] = $postParamsIncludeEvents;
	$dataproviderTicketsGetByIdParams["MakeDescriptionAsFirstEvent"] = $postParamsMakeDescriptionAsFirstEvent;
	$classTicket = new hst_Classes_Ticket();
	$ticketInfo = $classTicket->GetFromDB( $dataproviderTicketsGetByIdParams );

	if ( is_wp_error( $ticketInfo ) )
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "An error has occurred.");
	}

	//building response
	$response->success = 1;
	$response->data = $ticketInfo;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}


//udpates some tickets data
//$_POST["TicketId"] is a mandatory param
function hst_ajax_dashboard_tickets_updatedata()
{

	$methodName = 'hst_ajax_dashboard_tickets_updatedata';
	$response = new hst_Entities_AjaxResponse();
	$postParamsTicketId = 0;
	$postParamsTicketCategoryId = null;
	$postParamsTicketStatusId = null;
	$postParamsTicketPriorityId = null;
	$postParamsTicketCustomerId = null;

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
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param TicketId: " . hst_Logger_exportVarContent($postParamsTicketId, true));

	//checking for optional input params
	if ( isset($_POST["TicketCategoryId"]) != false)
	{
		$postParamsTicketCategoryId = sanitize_text_field($_POST["TicketCategoryId"]);
		hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param TicketCategoryId: " . hst_Logger_exportVarContent($postParamsTicketCategoryId, true));
	}
	if ( isset($_POST["TicketStatusId"]) != false)
	{
		$postParamsTicketStatusId = sanitize_text_field($_POST["TicketStatusId"]);
		hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param TicketStatusId: " . hst_Logger_exportVarContent($postParamsTicketStatusId, true));
	}
	if ( isset($_POST["TicketPriorityId"]) != false)
	{
		$postParamsTicketPriorityId = sanitize_text_field($_POST["TicketPriorityId"]);
		hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param TicketPriorityId: " . hst_Logger_exportVarContent($postParamsTicketPriorityId, true));
	}
	if ( isset($_POST["TicketCustomerId"]) != false)
	{
		$postParamsTicketCustomerId = sanitize_text_field($_POST["TicketCustomerId"]);
		hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param TicketCustomerId: " . hst_Logger_exportVarContent($postParamsTicketCustomerId, true));
	}

	$paramsTicketUpdateData = array();
	$paramsTicketUpdateData["TicketId"] = $postParamsTicketId;
	$paramsTicketUpdateData["TicketCategoryId"] = $postParamsTicketCategoryId;
	$paramsTicketUpdateData["TicketStatusId"] = $postParamsTicketStatusId;
	$paramsTicketUpdateData["TicketPriorityId"] = $postParamsTicketPriorityId;
	$paramsTicketUpdateData["TicketCustomerId"] = $postParamsTicketCustomerId;
	$classTicket = new hst_Classes_Ticket();
	$functionResult = $classTicket->SaveTicketData($paramsTicketUpdateData);

	if ( is_wp_error( $functionResult ) )
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "An error has occurred.");
	}

	//building response
	$response->success = 1;
	$response->data = null;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}


//Load events for a support ticket
//$_POST["TicketId"] is a mandatory param
function hst_ajax_dashboard_tickets_events_list()
{

	$methodName = 'hst_ajax_dashboard_tickets_events_list';
	$response = new hst_Entities_AjaxResponse();

	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	if ( isset($_POST["TicketId"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['TicketId'] parameter is missing. Aborting.");
	}
	$postParamsTicketId = sanitize_text_field($_POST["TicketId"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param TicketId: " . hst_Logger_exportVarContent($postParamsTicketId, true));

	//calling service to get ticket events
	$paramsListEventsById = array();
	$paramsListEventsById["TicketId"] = $postParamsTicketId;
	$classTicketEvents = new hst_Classes_Ticket_Event();
	$listTicketsEvents = $classTicketEvents->ListForTicketId($paramsListEventsById);

	if ( is_wp_error( $listTicketsEvents ) )
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "An error has occurred.");
	}

	//building response
	$response->success = 1;
	$response->data = $listTicketsEvents;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}


//Inserts a new message in the tickets events
//This is called when the agent saves the content of the text editor in the messages list
function hst_ajax_dashboard_tickets_events_addmessage()
{

	$methodName = 'hst_ajax_dashboard_tickets_events_addmessage';
	$response = new hst_Entities_AjaxResponse();
	$postParamsTicketId = 0;
	$postParamsMessageContent = "";

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
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param TicketId: " . hst_Logger_exportVarContent($postParamsTicketId, true));

	if ( isset($_POST["MessageContent"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['MessageContent'] parameter is missing. Aborting.");
	}
	$postParamsMessageContent = wp_kses_post($_POST["MessageContent"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param MessageContent: " . hst_Logger_exportVarContent($postParamsMessageContent, true));

	//fields validation
	if ($postParamsMessageContent == "")
	{
		hst_Common_ReturnAjaxValidationMessage($methodName, "Please type a Message.", "");
	}

	$ticketEventEntity = new hst_Entities_Ticket_Event();
	$ticketEventEntity->TicketId = $postParamsTicketId;
	$ticketEventEntity->TicketEventUserType = "agent" ;
	$ticketEventEntity->TicketEventType = "message";
	$ticketEventEntity->TicketEventMessageContent = $postParamsMessageContent;
	if (is_user_logged_in())
	{
		$ticketEventEntity->TicketEventUserId = get_current_user_id();
		$ticketEventEntity->TicketEventUserDisplayName = wp_get_current_user()->display_name;
	}
	$classTicketEvent = new hst_Classes_Ticket_Event();
	$resultNewTicketEventId = $classTicketEvent->AddTicketEventData($ticketEventEntity);

	if ( is_wp_error( $resultNewTicketEventId ) )
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "An error has occurred.");
	}

	//building response
	$response->success = 1;
	$response->data = $resultNewTicketEventId;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}


//inserts a new support ticket
//$_POST["TicketCustomerId"] is a mandatory param, if equal to 0 a new Customer will be created
//$_POST["TicketCustomerDisplayName"] is a mandatory param, if no customer Id was passed
//$_POST["TicketCustomerEmail"] is a mandatory param, if no customer Id was passed
//$_POST["TicketTitle"] is a mandatory param
//$_POST["TicketProblem"] is a mandatory param
//$_POST["TicketCategoryId"] is a mandatory param
//$_POST["TicketPriorityId"] is a mandatory param
//$_POST["CustomerType"] is a mandatory param
function hst_ajax_dashboard_tickets_create()
{

	$methodName = 'hst_ajax_dashboard_tickets_create';
	$response = new hst_Entities_AjaxResponse();
	$postParamsTicketCustomerId = null;
	$postParamsTicketCustomerDisplayName = null;
	$postParamsTicketCustomerEmail = null;
	$postParamsTicketTitle = null;
	$postParamsTicketProblem = null;
	$postParamsTicketCategoryId = null;
	$postParamsTicketPriorityId = null;
	$postParamsCustomerType = null;

	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	//checking for mandatory input params
	if ( isset($_POST["CustomerType"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['CustomerType'] parameter is missing. Aborting.");
	}
	$postParamsCustomerType = sanitize_text_field($_POST["CustomerType"]);
	if ( isset($_POST["TicketCustomerId"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['TicketCustomerId'] parameter is missing. Aborting.");
	}
	$postParamsTicketCustomerId = sanitize_text_field($_POST["TicketCustomerId"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param TicketCustomerId: " . hst_Logger_exportVarContent($postParamsTicketCustomerId, true));

	if ( isset($_POST["TicketCustomerDisplayName"]) == false && $postParamsCustomerType == "new" )
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['TicketCustomerDisplayName'] parameter is missing. Aborting.");
	}
	if ( isset($_POST["TicketCustomerEmail"]) == false && $postParamsCustomerType == "new" )
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['TicketCustomerEmail'] parameter is missing. Aborting.");
	}
	if ( isset($_POST["TicketCustomerDisplayName"]) )
	{
		$postParamsTicketCustomerDisplayName = sanitize_text_field($_POST["TicketCustomerDisplayName"]);
		hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param TicketCustomerDisplayName: " . hst_Logger_exportVarContent($postParamsTicketCustomerDisplayName, true));
	}
	if ( isset($_POST["TicketCustomerEmail"]) )
	{
		$postParamsTicketCustomerEmail = sanitize_text_field($_POST["TicketCustomerEmail"]);
		hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param TicketCustomerEmail: " . hst_Logger_exportVarContent($postParamsTicketCustomerEmail, true));
	}
	if ( isset($_POST["TicketTitle"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['TicketTitle'] parameter is missing. Aborting.");
	}
	if ( isset($_POST["TicketProblem"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['TicketProblem'] parameter is missing. Aborting.");
	}
	if ( isset($_POST["TicketCategoryId"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['TicketCategoryId'] parameter is missing. Aborting.");
	}
	if ( isset($_POST["TicketPriorityId"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['TicketPriorityId'] parameter is missing. Aborting.");
	}

	//saving remaining post params
	$postParamsTicketTitle = sanitize_text_field($_POST["TicketTitle"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param TicketTitle: " . hst_Logger_exportVarContent($postParamsTicketTitle, true));
	$postParamsTicketProblem = sanitize_text_field($_POST["TicketProblem"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param TicketProblem: " . hst_Logger_exportVarContent($postParamsTicketProblem, true));
	$postParamsTicketCategoryId = sanitize_text_field($_POST["TicketCategoryId"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param TicketCategoryId: " . hst_Logger_exportVarContent($postParamsTicketCategoryId, true));
	$postParamsTicketPriorityId = sanitize_text_field($_POST["TicketPriorityId"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param TicketPriorityId: " . hst_Logger_exportVarContent($postParamsTicketPriorityId, true));

	//fields validation
	if ($postParamsCustomerType == "new" && $postParamsTicketCustomerDisplayName == "")
	{
		hst_Common_ReturnAjaxValidationMessage($methodName, "Please type a Name for the Customer.", "hst-controls-tickets-new-txt-customerdisplayname");
	}
	if ($postParamsCustomerType == "new" && $postParamsTicketCustomerEmail == "")
	{
		hst_Common_ReturnAjaxValidationMessage($methodName, "Please type an Email Address for the Customer.", "hst-controls-tickets-new-txt-customeremail");
	}
	if ($postParamsCustomerType == "new" && is_email($postParamsTicketCustomerEmail) == false)
	{
		hst_Common_ReturnAjaxValidationMessage($methodName, "The Email address for the Customer seems to be invalid.", "hst-controls-tickets-new-txt-customeremail");
	}
	if ($postParamsCustomerType == "existing" && $postParamsTicketCustomerId == "0")
	{
		hst_Common_ReturnAjaxValidationMessage($methodName, "Please select a customer by clicking 'Select Existing Customer' button.", "");
	}
	if ($postParamsTicketTitle == "")
	{
		hst_Common_ReturnAjaxValidationMessage($methodName, "Please type a Title for this Support Ticket.", "hst-controls-tickets-new-txt-tickettitle");
	}
	if ($postParamsTicketProblem == "")
	{
		hst_Common_ReturnAjaxValidationMessage($methodName, "Please type a Problem Description for this Support Ticket.", "hst-controls-tickets-new-txt-ticketproblem");
	}
	if ( strlen($postParamsTicketProblem) > 999 )
	{
		hst_Common_ReturnAjaxValidationMessage($methodName, "The text for the Ticket Problem is too long (max 1000).", "");
	}
	if ($postParamsTicketCategoryId == "0")
	{
		hst_Common_ReturnAjaxValidationMessage($methodName, "Please type a Category for this Support Ticket.", "hst-controls-tickets-new-dd-category");
	}
	if ($postParamsTicketPriorityId == "0")
	{
		hst_Common_ReturnAjaxValidationMessage($methodName, "Please type a Priority for this Support Ticket.", "hst-controls-tickets-new-dd-priority");
	}

	//building params for the ticket creation function
	$paramsTicketCreate = array();
	$paramsTicketCreate["TicketCustomerId"] = $postParamsTicketCustomerId;
	$paramsTicketCreate["TicketCustomerDisplayName"] = $postParamsTicketCustomerDisplayName;
	$paramsTicketCreate["TicketCustomerEmail"] = $postParamsTicketCustomerEmail;
	$paramsTicketCreate["TicketTitle"] = $postParamsTicketTitle;
	$paramsTicketCreate["TicketProblem"] = $postParamsTicketProblem;
	$paramsTicketCreate["TicketCategoryId"] = $postParamsTicketCategoryId;
	$paramsTicketCreate["TicketPriorityId"] = $postParamsTicketPriorityId;
	$paramsTicketCreate["TicketCreatedUserId"] = get_current_user_id();
	$paramsTicketCreate["TicketAssignedUserId"] = get_current_user_id();
	$paramsTicketCreate["TicketCreationSource"] = "dashboard";

	$classTicket = new hst_Classes_Ticket();
	$createdTicketId = $classTicket->Create($paramsTicketCreate);

	if ( is_wp_error( $createdTicketId ) )
	{

		if ( $createdTicketId->get_error_message() == "Error creating Customer" )
		{
			hst_Common_ReturnAjaxError($methodName, "errorCreateCustomer", "There was a problem creating the Customer. Check that the email address does not belong to an existing User and try again.");
		}
		else
		{
			hst_Common_ReturnAjaxError($methodName, "generic", "An error as occurred; please activate the System Log for debugging or contact us for assistance.");
		}

	}

	//building response
	$response->success = 1;
	$response->data = $createdTicketId;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}


//Delete single support ticket
//$_POST["TicketId"] is a mandatory param
function hst_ajax_dashboard_tickets_delete()
{

	$methodName = 'hst_ajax_dashboard_tickets_delete';
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
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param TicketId: " . hst_Logger_exportVarContent($postParamsTicketId, true));

	//calling service to delete ticket
	$deleteTicketParams = array();
	$deleteTicketParams["TicketId"] = $postParamsTicketId;
	$deleteTicketParams["IncludeEvents"] = $postParamsIncludeEvents;
	$classTicket = new hst_Classes_Ticket();
	$resultFunction = $classTicket->DeleteTicketFromDB( $deleteTicketParams );

	if ( is_wp_error( $resultFunction ) )
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "An error has occurred.");
	}

	//building response
	$response->success = 1;
	$response->data = null;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}


?>