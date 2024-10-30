<?php

//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//Class that handles ticket categories functions
class hst_Classes_Notification
{

	//holds the list of available notifications
	public $NotificationsDataSet;

	//on creation, fills the dataset of available notifications
	function __construct()
	{


		$this->NotificationsDataSet = array();


		//1 - TicketCreateCustomer-----------------------------------------------------------------------------------------------------------------
		$notificationDetails = array();
		$notificationDetails["NotificationKey"] = "TicketCreateCustomer";
		$notificationDetails["NotificationRecipient"] = "Customer";
		$notificationDetails["NotificationName"] = "New Ticket Creation";
		$notificationDetails["NotificationDescription"] = "Email will be sent to the customer's email address whenever a new support ticket is created by the customer or created on behalf the customer.";
		$notificationDetails["NotificationEnabled"] = get_option( hst_consts_optionKeyNotifTicketCreateCustomerEnabled , 0 );
		$notificationDetails["NotificationTemplateSubject"] = "New Support Ticket created";
		$notificationDetails["NotificationTemplateBody"] = "Hello ##CustomerName##, <br><br>a new support ticket has been created.<br><br><strong>Ticket Number:</strong> ##TicketNumber##<br><strong>Title:</strong> ##TicketTitle##<br><strong>Category:</strong> ##TicketCategory##<br><strong>Problem Description:</strong> ##TicketProblem##<br><br>You can review this Support Ticket by clicking the following link:<br><a href='##TicketLinkFrontEnd##'>Review Support Ticket</a><br><br>Thank you!<br><br>";
		array_push( $this->NotificationsDataSet, $notificationDetails);



		//2 - TicketCreateAgent-----------------------------------------------------------------------------------------------------------------
		$notificationDetails = array();
		$notificationDetails["NotificationKey"] = "TicketCreateAgent";
		$notificationDetails["NotificationRecipient"] = "Agent";
		$notificationDetails["NotificationName"] = "New Ticket Creation";
		$notificationDetails["NotificationDescription"] = "Email will be sent to the agent's email address whenever a new support ticket is created by the customer or created on behalf the customer.";
		$notificationDetails["NotificationEnabled"] = get_option( hst_consts_optionKeyNotifTicketCreateAgentEnabled , 0 );
		$notificationDetails["NotificationTemplateSubject"] = "New Support Ticket created";
		$notificationDetails["NotificationTemplateBody"] = "A new support ticket has been created.<br><br><strong>Ticket Number:</strong> ##TicketNumber##<br><strong>Title:</strong> ##TicketTitle##<br><strong>Category:</strong> ##TicketCategory##<br><strong>Problem Description:</strong> ##TicketProblem##<br><br>You can review this Support Ticket by clicking the following link:<br><a href='##TicketLinkDashboard##'>Review Support Ticket</a><br><br>Thank you!<br><br>";
		array_push( $this->NotificationsDataSet, $notificationDetails);



		//3 - MessageCreateCustomer-----------------------------------------------------------------------------------------------------------------
		$notificationDetails = array();
		$notificationDetails["NotificationKey"] = "MessageCreateCustomer";
		$notificationDetails["NotificationRecipient"] = "Customer";
		$notificationDetails["NotificationName"] = "New Message";
		$notificationDetails["NotificationDescription"] = "Email will be sent to the customer's email address whenever a new message is added by the customer.";
		$notificationDetails["NotificationEnabled"] = get_option( hst_consts_optionKeyNotifTicketMessageCreateCustomerEnabled , 0 );
		$notificationDetails["NotificationTemplateSubject"] = "New Message for the Support Ticket";
		$notificationDetails["NotificationTemplateBody"] = "Hello ##CustomerName##, <br><br>there is a new message related to the Support Ticket:<br><br><strong>Ticket Number:</strong> ##TicketNumber##<br><strong>Title:</strong> ##TicketTitle##<br><strong>Category:</strong> ##TicketCategory##<br><strong>Problem Description:</strong> ##TicketProblem##<br><br><br><strong>Message:</strong> ##MessageContent##<br><br><br>You can review this Support Ticket by clicking the following link:<br><a href='##TicketLinkFrontEnd##'>Review Support Ticket</a><br><br>Thank you!<br><br>";
		array_push( $this->NotificationsDataSet, $notificationDetails);



		//4 - MessageCreateAgent-----------------------------------------------------------------------------------------------------------------
		$notificationDetails = array();
		$notificationDetails["NotificationKey"] = "MessageCreateAgent";
		$notificationDetails["NotificationRecipient"] = "Agent";
		$notificationDetails["NotificationName"] = "New Message";
		$notificationDetails["NotificationDescription"] = "Email will be sent to the agent's email address whenever a new message is added by the agent(s).";
		$notificationDetails["NotificationEnabled"] = get_option( hst_consts_optionKeyNotifTicketMessageCreateAgentEnabled , 0 );
		$notificationDetails["NotificationTemplateSubject"] = "New Message for the Support Ticket";
		$notificationDetails["NotificationTemplateBody"] = "There is a new message related to the Support Ticket:<br><br><strong>Ticket Number:</strong> ##TicketNumber##<br><strong>Title:</strong> ##TicketTitle##<br><strong>Category:</strong> ##TicketCategory##<br><strong>Problem Description:</strong> ##TicketProblem##<br><br><br><strong>Message:</strong> ##MessageContent##<br><br><br>You can review this Support Ticket by clicking the following link:<br><a href='##TicketLinkDashboard##'>Review Support Ticket</a><br><br>Thank you!<br><br>";
		array_push( $this->NotificationsDataSet, $notificationDetails);


	}


	//returns a notification details array by a notification key
	//$params["NotificationKey"] is a mandatory param
	public function GetNotificationDataByKey( $params )
	{


		$methodName = 'hst_Classes_Notification.GetNotificationDataByKey';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		//checking for mandatory input params
		if ( isset(  $params["NotificationKey"] ) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'NotificationKey' value is missing. Aborting." );
		}

		foreach( $this->NotificationsDataSet as $notificationItem )
		{

			if ( $notificationItem["NotificationKey"] == $params["NotificationKey"] )
			{
				$result = $notificationItem;
				break;
			}

		}

		//checks if a notification has been found or not
		if ( $result == false )
		{
			return hst_Common_ReturnFunctionError( $methodName, "Notification not found with such Key." );
		}

		hst_Logger_AddEntry("DEBUG", $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry("FUNCTION", $methodName,"End");

		return $result;

	}


	//saves the settings for a notification by notification key
	//$params["NotificationKey"] is a mandatory param
	//$params["NotificationEnabled"] is a mandatory param
	public function SaveNotificationDataByKey( $params )
	{

		$methodName = 'hst_Classes_Notification.SaveNotificationDataByKey';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		//checking for mandatory input params
		if ( isset(  $params["NotificationKey"] ) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'NotificationKey' value is missing. Aborting." );
		}
		if ( isset(  $params["NotificationEnabled"] ) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'NotificationEnabled' value is missing. Aborting." );
		}

		//saving notifications data
		if ( $params["NotificationKey"] == "TicketCreateCustomer" )
		{

			//notification enabled?
			update_option( hst_consts_optionKeyNotifTicketCreateCustomerEnabled, $params["NotificationEnabled"] );

		}
		if ( $params["NotificationKey"] == "TicketCreateAgent" )
		{

			//notification enabled?
			update_option( hst_consts_optionKeyNotifTicketCreateAgentEnabled, $params["NotificationEnabled"] );

		}
		if ( $params["NotificationKey"] == "MessageCreateCustomer" )
		{

			//notification enabled?
			update_option( hst_consts_optionKeyNotifTicketMessageCreateCustomerEnabled, $params["NotificationEnabled"] );

		}
		if ( $params["NotificationKey"] == "MessageCreateAgent" )
		{

			//notification enabled?
			update_option( hst_consts_optionKeyNotifTicketMessageCreateAgentEnabled, $params["NotificationEnabled"] );

		}

		hst_Logger_AddEntry("DEBUG", $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry("FUNCTION", $methodName,"End");

		return $result;

	}


	//This function will generate an email, replacing the tokens, and send it.
	//params["NotificationKey"] is a mandatory param
	//params["IsTest"], not mandatory, set to 1 to enforce a Test email sent.
	//params["TestRecipientEmail"], not mandatory, will be used as recipient email address in case of testing.
	//params["TicketId"], not mandatory, will be used to retrieve the record to replace placeholder values with.
	//params["TicketEventId"], not mandatory, will be used to retrieve the record of ticket message to replace placeholder values with.
	public function FireNotification( $params )
	{

		$methodName = 'hst_Classes_Notification.FireNotification';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		$paramsNotificationKey = "";
		$paramsIsTest = 0;
		$paramsTestRecipientEmail = "";
		$paramsTicketId = 0;
		$paramsTicketEventId = 0;

		$ticketEntityForValues = new hst_Entities_Ticket;
		$ticketEventEntityForValues = new hst_Entities_Ticket_Event;

		//checking for mandatory input params
		if ( isset( $params["NotificationKey"] ) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'NotificationKey' value is missing. Aborting." );
		}
		$paramsNotificationKey = $params["NotificationKey"];
		if ( isset( $params["IsTest"] ) )
		{
			$paramsIsTest = $params["IsTest"];
		}
		if ( isset( $params["TestRecipientEmail"] ) )
		{
			$paramsTestRecipientEmail = $params["TestRecipientEmail"];
		}
		if ( isset( $params["TicketId"] ) )
		{
			$paramsTicketId = $params["TicketId"];
		}
		if ( isset( $params["TicketEventId"] ) )
		{
			$paramsTicketEventId = $params["TicketEventId"];
		}

		//logging parameters
		hst_Logger_AddEntry( 'DEBUG', $methodName, "'NotificationKey' param: " . $paramsNotificationKey );
		hst_Logger_AddEntry( 'DEBUG', $methodName, "'IsTest' param: " . $paramsIsTest );
		hst_Logger_AddEntry( 'DEBUG', $methodName, "'TestRecipientEmail' param: " . $paramsTestRecipientEmail );
		hst_Logger_AddEntry( 'DEBUG', $methodName, "'TicketId' param: " . $paramsTicketId );
		hst_Logger_AddEntry( 'DEBUG', $methodName, "'TicketEventId' param: " . $paramsTicketEventId );

		//getting notification data
		$notificationData = $this->GetNotificationDataByKey( array( "NotificationKey" => $paramsNotificationKey ) );
		hst_Logger_AddEntry( 'DEBUG', $methodName, "Notification data retrieved correctly: " . hst_Logger_exportVarContent($notificationData, true) );

		//sending this email only if enabled or IsTest=1
		if ( $notificationData["NotificationEnabled"] == 1 || $paramsIsTest == 1 )
		{

			$recipientEmailAddress = "";

			//retrieving record of ticket to use as values, according to the notification key
			if ( $paramsNotificationKey == "TicketCreateCustomer" || $paramsNotificationKey == "TicketCreateAgent"  || $paramsNotificationKey == "MessageCreateCustomer"  || $paramsNotificationKey == "MessageCreateAgent")
			{

				//retrieving the ticket
				$classTicket = new hst_Classes_Ticket();
				$ticketEntityForValues = $classTicket->GetFromDB( array( "TicketId" => $paramsTicketId) );
				hst_Logger_AddEntry( 'DEBUG', $methodName, "Retrieved Entity for TicketId: " . $paramsTicketId );

			}

			//retrieving record of ticket event/message to use as values, according to the notification key
			if ( $paramsNotificationKey == "MessageCreateCustomer"  || $paramsNotificationKey == "MessageCreateAgent")
			{

				//retrieving the ticket event record
				$classTicketEvent = new hst_Classes_Ticket_Event();
				$ticketEventEntityForValues = $classTicketEvent->GetById( array( "TicketEventId" => $paramsTicketEventId) );
				hst_Logger_AddEntry( 'DEBUG', $methodName, "Retrieved Entity for TicketEventId: " . $paramsTicketEventId );

			}

			//getting sender address email from helpdesk options
			$senderEmailAddress = get_option( hstHelpdeskEmailAddress, "" );
			hst_Logger_AddEntry( 'DEBUG', $methodName, "Sender Email Address: " . $senderEmailAddress );
			$senderEmailAddressDescriptionText = get_option( hstHelpdeskEmailSenderName, "" );
			hst_Logger_AddEntry( 'DEBUG', $methodName, "Sender Description Text: " . $senderEmailAddressDescriptionText );

			//getting email address for recipient
			if ( $paramsIsTest == 0 )
			{

				//normal process for getting recipient email address

				if ( $paramsNotificationKey == "TicketCreateCustomer" )
				{
					$recipientEmailAddress = $ticketEntityForValues->TicketCustomerUserEmail;
				}

				if ( $paramsNotificationKey == "TicketCreateAgent" )
				{
					$recipientEmailAddress = get_userdata( $ticketEntityForValues->TicketAssignedUserId )->user_email;
				}

				if ( $paramsNotificationKey == "MessageCreateCustomer" )
				{
					$recipientEmailAddress = $ticketEntityForValues->TicketCustomerUserEmail;
				}

				if ( $paramsNotificationKey == "MessageCreateAgent" )
				{
					$recipientEmailAddress = get_userdata( $ticketEntityForValues->TicketAssignedUserId )->user_email;
				}

			}
			else
			{

				//this is a test, so the sender email address was passed in as param ($paramsTestRecipientEmail)
				$recipientEmailAddress = $paramsTestRecipientEmail;

			}

			hst_Logger_AddEntry( 'DEBUG', $methodName, "Recipient Email Address: " . $recipientEmailAddress );

			//replacing email tokens
			$paramsReplacingTokens = array();
			$paramsReplacingTokens["NotificationTemplateSubject"] = $notificationData["NotificationTemplateSubject"];
			$paramsReplacingTokens["NotificationTemplateBody"] = $notificationData["NotificationTemplateBody"];
			$paramsReplacingTokens["NotificationKey"] = $paramsNotificationKey;
			$paramsReplacingTokens["TicketEntity"] = $ticketEntityForValues;
			$paramsReplacingTokens["TicketEventEntity"] = $ticketEventEntityForValues;
			$resultReplacingTokens = $this->SubstitutePlaceholders( $paramsReplacingTokens );

			hst_Logger_AddEntry( 'DEBUG', $methodName, "Placeholders substitution done." );

			//Firing the email
			$paramsSendEmail = array();
			$paramsSendEmail["emailFrom"] = $senderEmailAddress;
			$paramsSendEmail["emailTo"] = $recipientEmailAddress;
			$paramsSendEmail["emailSubject"] = $resultReplacingTokens["EmailSubject"];
			$paramsSendEmail["emailBody"] = $resultReplacingTokens["EmailBody"];
			$result = hst_Common_SendEmail( $paramsSendEmail );

		}
		else
		{

			//the notification was not enabled or this is not a test
			hst_Logger_AddEntry( 'DEBUG', $methodName, "Notification not enabled and not in TEST mode." );
			
			//we set this type of result to distinguish it from "false", which would mean an error while sending the email
			$result = "notenabled";

		}

		hst_Logger_AddEntry("DEBUG", $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry("FUNCTION", $methodName,"End");

		return $result;

	}


	//This function is called privately by the "FireNotification" function. It will replace the placeholders with actual values,
	//passed in the $params entities.
	//param["NotificationTemplateSubject"] mandatory
	//param["NotificationTemplateBody"] mandatory
	//param["NotificationKey"] mandatory
	//param["TicketEntity"] the entity of the ticket
	//param["TicketEventEntity"] the entity of the ticket event (message)
	//result["EmailSubject"] the result subject of the email, with replace tokens
	//result["EmailBody"] the result body of the email, with replace tokens
	public function SubstitutePlaceholders( $params )
	{

		$methodName = 'hst_Classes_Notification.SubstitutePlaceholders';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		//original content
		$emailSubject = $params["NotificationTemplateSubject"];
		$emailBody = $params["NotificationTemplateBody"];

		//entities
		$ticketEntity = new hst_Entities_Ticket();
		$ticketEventEntity = new hst_Entities_Ticket_Event();
		$ticketEntity = $params["TicketEntity"];
		$ticketEventEntity = $params["TicketEventEntity"];

		//sustitute tokens
		$emailSubject = str_replace("##TicketNumber#", $ticketEntity->TicketId, $emailSubject);
		$emailSubject = str_replace("##TicketTitle##", $ticketEntity->TicketId, $emailSubject);
		$emailSubject = str_replace("##TicketProblem##", $ticketEntity->TicketId, $emailSubject);
		$emailSubject = str_replace("##CustomerName##", $ticketEntity->TicketCustomerUserDisplayName, $emailSubject);
		$emailSubject = str_replace("##TicketCategory##", $ticketEntity->TicketCategoryText, $emailSubject);
		$emailSubject = str_replace("##TicketStatus##", $ticketEntity->TicketStatusText, $emailSubject);
		$emailSubject = str_replace("##TicketPriority##", $ticketEntity->TicketPriorityText, $emailSubject);
		$emailSubject = str_replace("##AgentName##", $ticketEntity->TicketAssignedUserDisplayName, $emailSubject);
		$emailSubject = str_replace("##MessageContent##", $ticketEventEntity->TicketEventMessageContent, $emailSubject);

		$emailBody = str_replace("##TicketNumber#", $ticketEntity->TicketId, $emailBody);
		$emailBody = str_replace("##TicketTitle##", $ticketEntity->TicketTitle, $emailBody);
		$emailBody = str_replace("##TicketProblem##", $ticketEntity->TicketProblem, $emailBody);
		$emailBody = str_replace("##CustomerName##", $ticketEntity->TicketCustomerUserDisplayName, $emailBody);
		$emailBody = str_replace("##TicketCategory##", $ticketEntity->TicketCategoryText, $emailBody);
		$emailBody = str_replace("##TicketStatus##", $ticketEntity->TicketStatusText, $emailBody);
		$emailBody = str_replace("##TicketPriority##", $ticketEntity->TicketPriorityText, $emailBody);
		$emailBody = str_replace("##AgentName##", $ticketEntity->TicketAssignedUserDisplayName, $emailBody);
		$emailBody = str_replace("##MessageContent##", $ticketEventEntity->TicketEventMessageContent, $emailBody);

		//urls dashabord
		$ticketURLDashboard = hst_Common_GetDashboardPluginURL() . "&goto=ticketview&ticketid=" . $ticketEntity->TicketId;

		//urls frontend
		$optionPageFrontEnd = get_option( hst_consts_optionKeyFrontEndPageId, "" );
		hst_Logger_AddEntry( 'DEBUG', $methodName, "Option FrontEnd page: " . hst_Logger_exportVarContent($optionPageFrontEnd, true) );
		$frontendpageurl = get_page_link($optionPageFrontEnd);
		hst_Logger_AddEntry( 'DEBUG', $methodName, "URL FrontEnd page: " . hst_Logger_exportVarContent($frontendpageurl, true) );
		$ticketURLFrontEnd = $frontendpageurl . "?goto=ticketview&ticketid=" . $ticketEntity->TicketId;

		$emailBody = str_replace("##TicketLinkDashboard##", $ticketURLDashboard, $emailBody);
		$emailBody = str_replace("##TicketLinkFrontEnd##", $ticketURLFrontEnd, $emailBody);

		//builds result
		$result["EmailSubject"] = $emailSubject;
		$result["EmailBody"] = $emailBody;

		hst_Logger_AddEntry("DEBUG", $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry("FUNCTION", $methodName,"End");

		return $result;

	}

}

?>