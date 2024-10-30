<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//registering ajax calls
add_action( 'wp_ajax_hst_ajax_frontend_tickets_list', 'hst_ajax_frontend_tickets_list' );
add_action( 'wp_ajax_hst_ajax_frontend_tickets_getsingle', 'hst_ajax_frontend_tickets_getsingle' );
add_action( 'wp_ajax_hst_ajax_frontend_tickets_events_addmessage', 'hst_ajax_frontend_tickets_events_addmessage' );
add_action( 'wp_ajax_hst_ajax_frontend_tickets_create', 'hst_ajax_frontend_tickets_create' );
add_action( 'wp_ajax_nopriv_hst_ajax_frontend_tickets_create', 'hst_ajax_frontend_tickets_create' );



//Load the list of tickets for a logged in user
function hst_ajax_frontend_tickets_list()
{

	$methodName = 'hst_ajax_frontend_tickets_list';
	$response = new hst_Entities_AjaxResponse();

	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	//calling service to get tickets for frontend list
	$classTicket = new hst_Classes_Ticket();
	$paramsTicketsList = array();
	$paramsTicketsList["TicketCustomerUserId"] = get_current_user_id();
	$listTickets = $classTicket->ListFromDB($paramsTicketsList);

	if ( is_wp_error( $listTickets ) )
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "An error has occurred.");
	}

	//building object for response
	$resultArray["Tickets"] = $listTickets;

	//building response
	$response->success = 1;
	$response->data = $resultArray;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}



//Gets a single ticket data and events
//Post["TicketId"] is mandatory
function hst_ajax_frontend_tickets_getsingle()
{

	$methodName = 'hst_ajax_frontend_tickets_getsingle';
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

	//calling service to get tickets for dashboard list
	$dataproviderTicketsGetByIdParams = array();
	$dataproviderTicketsGetByIdParams["TicketId"] = $postParamsTicketId;
	$dataproviderTicketsGetByIdParams["IncludeEventsOnlyForCustomerView"] = 1;
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



//Inserts a new message in the tickets events
//This is called when the customer saves the content of the text editor in the messages list
//Post["TicketId"] is mandatory
//Post["MessageContent"] is mandatory
function hst_ajax_frontend_tickets_events_addmessage()
{

	$methodName = 'hst_ajax_frontend_tickets_events_addmessage';
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
	$postParamsMessageContent = sanitize_text_field($_POST["MessageContent"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param MessageContent: " . hst_Logger_exportVarContent($postParamsMessageContent, true));

	//fields validation
	if ($postParamsMessageContent == "")
	{
		hst_Common_ReturnAjaxValidationMessage($methodName, "Please type a Message.","hst_fe_viewticket-txt-newmessage");
	}


	$ticketEventEntity = new hst_Entities_Ticket_Event();
	$ticketEventEntity->TicketId = $postParamsTicketId;
	$ticketEventEntity->TicketEventUserType = "customer" ;
	$ticketEventEntity->TicketEventType = "message";
	$ticketEventEntity->TicketEventMessageContent = $postParamsMessageContent;
	if (is_user_logged_in())
	{
		$ticketEventEntity->TicketEventUserId = get_current_user_id();
		$ticketEventEntity->TicketEventUserDisplayName = wp_get_current_user()->display_name;
	}
	$classTicketEvent = new hst_Classes_Ticket_Event();
	$resultFunction = $classTicketEvent->AddTicketEventData($ticketEventEntity);

	if ( is_wp_error( $resultFunction ) )
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "An error has occurred.");
	}

	//building response
	$response->success = 1;
	$response->data = $resultFunction;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}



//inserts a new support ticket
//$_POST["TicketCustomerDisplayName"] is a mandatory param, if no user is logged
//$_POST["TicketCustomerEmail"] is a mandatory param, if no user is logged
//$_POST["TicketTitle"] is a mandatory param
//$_POST["TicketProblem"] is a mandatory param
//$_POST["TicketCategoryId"] is a mandatory param
function hst_ajax_frontend_tickets_create()
{

	$methodName = 'hst_ajax_frontend_tickets_create';
	$response = new hst_Entities_AjaxResponse();
	$postParamsTicketCustomerId = 0;
	$postParamsTicketCustomerDisplayName = null;
	$postParamsTicketCustomerEmail = null;
	$postParamsTicketTitle = null;
	$postParamsTicketProblem = null;
	$postParamsTicketCategoryId = null;

	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	//checking for mandatory input params
	if ( isset($_POST["TicketCustomerDisplayName"]) == false && is_user_logged_in() == false )
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['TicketCustomerDisplayName'] parameter is missing. Aborting.");
	}
	if ( isset($_POST["TicketCustomerEmail"]) == false && is_user_logged_in() == false )
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

	//saving remaining post params
	$postParamsTicketTitle = sanitize_text_field($_POST["TicketTitle"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param TicketTitle: " . hst_Logger_exportVarContent($postParamsTicketTitle, true));
	$postParamsTicketProblem = sanitize_text_field($_POST["TicketProblem"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param TicketProblem: " . hst_Logger_exportVarContent($postParamsTicketProblem, true));
	$postParamsTicketCategoryId = sanitize_text_field($_POST["TicketCategoryId"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param TicketCategoryId: " . hst_Logger_exportVarContent($postParamsTicketCategoryId, true));

	//customer id or guest user? if the user is not logged in, we will use the values contained in POST values
	if ( is_user_logged_in() == true )
	{
		$postParamsTicketCustomerId = get_current_user_id();
		$postParamsTicketCustomerDisplayName = get_currentuserinfo()->display_name;
		$postParamsTicketCustomerEmail = get_currentuserinfo()->user_email;
		hst_Logger_AddEntry("DEBUG", $methodName, "Information for logged user were set correctly.");
	}
	else
	{
		$postParamsTicketCustomerId = 0;
		$postParamsTicketCustomerDisplayName = sanitize_text_field($_POST["TicketCustomerDisplayName"]);
		$postParamsTicketCustomerEmail = sanitize_text_field($_POST["TicketCustomerEmail"]);
		hst_Logger_AddEntry("DEBUG", $methodName, "Information for anonymous user were set correctly.");
	}

	//fields validation
	if ($postParamsTicketCustomerDisplayName == "")
	{
		hst_Common_ReturnAjaxValidationMessage($methodName, "Please type your Name.", "hst-fe-createticket-customerdisplayname");
	}
	if ($postParamsTicketCustomerEmail == "")
	{
		hst_Common_ReturnAjaxValidationMessage($methodName, "Please type your Email Address.", "hst-fe-createticket-customeremail");
	}
	if (is_email($postParamsTicketCustomerEmail) == false)
	{
		hst_Common_ReturnAjaxValidationMessage($methodName, "The supplied email address is not valid. Please check your input and try again.", "hst-fe-createticket-customeremail");
	}
	if ($postParamsTicketTitle == "")
	{
		hst_Common_ReturnAjaxValidationMessage($methodName, "Please type a Title for this Support Request.", "hst-fe-createticket-tickettitle");
	}
	if ($postParamsTicketProblem == "")
	{
		hst_Common_ReturnAjaxValidationMessage($methodName, "Please type a Description of your problem or question.", "hst-fe-createticket-ticketproblem");
	}
	if ( strlen($postParamsTicketProblem) > 999 )
	{
		hst_Common_ReturnAjaxValidationMessage($methodName, "The text for the Ticket Problem is too long (max 1000).", "hst-fe-createticket-ticketproblem");
	}
	if ($postParamsTicketCategoryId == "0")
	{
		hst_Common_ReturnAjaxValidationMessage($methodName, "Please select a Category for your Support Request.", "hst-fe-createticket-dd-category");
	}

	//calculates the default priority id for ticket
	$classPriorities = new hst_Classes_Ticket_Priority();
	$valueDefaultPriority = $classPriorities->GetDefaultForNewTickets(null);
	hst_Logger_AddEntry("DEBUG", $methodName, "Default priority id for new tickets:" . hst_Logger_exportVarContent($valueDefaultPriority, true));

	//building params for the ticket creation function
	$paramsTicketCreate = array();
	$paramsTicketCreate["TicketCustomerId"] = $postParamsTicketCustomerId;
	$paramsTicketCreate["TicketCustomerDisplayName"] = $postParamsTicketCustomerDisplayName;
	$paramsTicketCreate["TicketCustomerEmail"] = $postParamsTicketCustomerEmail;
	$paramsTicketCreate["TicketTitle"] = $postParamsTicketTitle;
	$paramsTicketCreate["TicketProblem"] = $postParamsTicketProblem;
	$paramsTicketCreate["TicketCategoryId"] = $postParamsTicketCategoryId;
	$paramsTicketCreate["TicketPriorityId"] = $valueDefaultPriority->TicketPriorityId;
	$paramsTicketCreate["TicketCreatedUserId"] = $postParamsTicketCustomerId;
	$paramsTicketCreate["TicketCreationSource"] = "frontend";
	$paramsTicketCreate["TicketAssignedUserId"] = get_option( hst_consts_optionKeyHelpdeskDefaultAgentUserId, "-1" );

	hst_Logger_AddEntry("DEBUG", $methodName, "Created function params: " . hst_Logger_exportVarContent($paramsTicketCreate, true));

	$classTicket = new hst_Classes_Ticket();
	$createdTicketId = $classTicket->Create($paramsTicketCreate);

	if ( is_wp_error( $createdTicketId ) )
	{

		//error catch: trying to create a customer with an existing email address
		$errorObject = $createdTicketId;

		if ( $errorObject->get_error_message() == "Error creating Customer" )
		{
			hst_Common_ReturnAjaxError($methodName, "errorCreatingCustomer",  "There was a problem registering your information as a Customer. This error can be cause by your email address being already registered in this website. Try to change the email address or contact us for assistance.");
		}
		else
		{
			hst_Common_ReturnAjaxError($methodName, "generic",  "An error has occurred.");
		}

	}

	//building response
	$response->success = 1;
	$response->data = $createdTicketId;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}




?>