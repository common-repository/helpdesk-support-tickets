<?php

//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//Class that handles a support ticket
class hst_Classes_Ticket
{


	//Inserts a Ticket in the database
	//$_POST["TicketCustomerId"] is a mandatory param, if equal to 0 a new Customer will be created
	//$_POST["TicketCustomerDisplayName"] is a mandatory param, if no customer Id was passed
	//$_POST["TicketCustomerEmail"] is a mandatory param, if no customer Id was passed
	//$_POST["TicketTitle"] is a mandatory param
	//$_POST["TicketProblem"] is a mandatory param
	//$_POST["TicketCategoryId"] is a mandatory param
	//$_POST["TicketCreatedUserId"] is a mandatory param
	//$_POST["TicketAssignedUserId"] is a mandatory param
	//$_POST["TicketCreationSource"] is a mandatory param
	//returns the newly created ticket id (int)
	public function Create( $params )
	{

		$methodName = 'hst_Classes_Ticket.Create';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		$paramsTicketCustomerId = null;
		$paramsTicketCustomerDisplayName = null;
		$paramsTicketCustomerEmail = null;
		$paramsTicketTitle = null;
		$paramsTicketProblem = null;
		$paramsTicketCategoryId = null;
		$paramsTicketPriorityId = null;
		$paramsTicketCreatedUserId = null;
		$paramsTicketAssignedUserId = null;
		$paramsCreationSource = null;

		//checking for mandatory input params
		if ( isset($params["TicketCustomerId"]) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "params['TicketCustomerId'] parameter is missing. Aborting." );
		}
		$paramsTicketCustomerId = $params["TicketCustomerId"];

		if ( isset($params["TicketCustomerDisplayName"]) == false && $paramsTicketCustomerId == 0 )
		{
			return hst_Common_ReturnFunctionError( $methodName,  "params['TicketCustomerDisplayName'] parameter is missing, while TicketCustomerId=0. Aborting." );
		}
		if ( isset($params["TicketCustomerEmail"]) == false && $paramsTicketCustomerId == 0 )
		{
			return hst_Common_ReturnFunctionError( $methodName,"params['TicketCustomerEmail'] parameter is missing, while TicketCustomerId=0. Aborting." );
		}
		if ( isset($params["TicketCustomerDisplayName"]) )
		{
			$paramsTicketCustomerDisplayName = $params["TicketCustomerDisplayName"];
		}
		if ( isset($params["TicketCustomerEmail"]) )
		{
			$paramsTicketCustomerEmail = $params["TicketCustomerEmail"];
		}
		if ( isset($params["TicketTitle"]) == false)
		{
			$result = hst_Common_ReturnFunctionError( $methodName, "params['TicketTitle'] parameter is missing. Aborting." );
		}
		if ( isset($params["TicketProblem"]) == false)
		{
			$result = hst_Common_ReturnFunctionError( $methodName, "params['TicketProblem'] parameter is missing. Aborting." );
		}
		if ( isset($params["TicketCategoryId"]) == false)
		{
			$result = hst_Common_ReturnFunctionError( $methodName, "params['TicketCategoryId'] parameter is missing. Aborting." );
		}
		if ( isset($params["TicketPriorityId"]) == false)
		{
			$result = hst_Common_ReturnFunctionError( $methodName,  "params['TicketPriorityId'] parameter is missing. Aborting." );
		}
		if ( isset($params["TicketCreatedUserId"]) == false)
		{
			$result = hst_Common_ReturnFunctionError( $methodName, "params['TicketCreatedUserId'] parameter is missing. Aborting." );
		}
		if ( isset($params["TicketAssignedUserId"]) == false)
		{
			$result = hst_Common_ReturnFunctionError( $methodName, "params['TicketAssignedUserId'] parameter is missing. Aborting." );
		}
		if ( isset($params["TicketCreationSource"]) == false)
		{
			$result = hst_Common_ReturnFunctionError( $methodName, "params['TicketCreationSource'] parameter is missing. Aborting." );
		}

		//saving remaining post params
		$paramsTicketTitle = $params["TicketTitle"];
		$paramsTicketProblem = $params["TicketProblem"];
		$paramsTicketCategoryId = $params["TicketCategoryId"];
		$paramsTicketPriorityId = $params["TicketPriorityId"];
		$paramsTicketCreatedUserId = $params["TicketCreatedUserId"];
		$paramsTicketAssignedUserId = $params["TicketAssignedUserId"];
		$paramsCreationSource = $params["TicketCreationSource"];

		//must create a new customer?
		$createdCustomerId = 0;
		if ( $paramsTicketCustomerId == 0 )
		{

			hst_Logger_AddEntry( 'DEBUG', $methodName, "Creating New Customer");

			//will create a new customer (wp user) with the supplied data
			$classCustomers = new hst_Classes_Customer();
			$paramsCreateCustomer = array();
			$paramsCreateCustomer["CustomerDisplayName"] = $paramsTicketCustomerDisplayName;
			$paramsCreateCustomer["CustomerEmail"] = $paramsTicketCustomerEmail;
			$createdCustomerId = $classCustomers->Create($paramsCreateCustomer);

			if ( is_wp_error( $createdCustomerId ) )
			{
				return hst_Common_ReturnFunctionError( $methodName, "Error creating Customer" );
			}

			hst_Logger_AddEntry( 'DEBUG', $methodName, "Customer creating result: " . $createdCustomerId);

		}

		//determines what userId shall be user for ticket insert
		$customerIdUseForInsert = 0;
		$customerDisplayNameForInsert = "";
		$customerEmailForInsert = "";
		if ( $paramsTicketCustomerId == 0 )
		{
			$customerIdUseForInsert = $createdCustomerId;
		}
		else
		{
			$customerIdUseForInsert = $paramsTicketCustomerId;
		}

		//retrieves name and email for the set customer, in order to fill the fields in the tickets table
		$classCustomers = new hst_Classes_Customer();
		$paramsGetCustomer = array();
		$paramsGetCustomer["CustomerId"] = $customerIdUseForInsert;
		$entityGetCustomer = $classCustomers->GetFromDB( $paramsGetCustomer );
		if ( $entityGetCustomer != null)
		{
			$customerDisplayNameForInsert = $entityGetCustomer->CustomerDisplayName;
			$customerEmailForInsert = $entityGetCustomer->CustomerEmail;
		}

		//Retrieves the default status for tickets
		$defaultStatusOpenTickets = 0;
		$classTicketStatus = new hst_Classes_Ticket_Status();
		$entityTicketDefaultStatus = new hst_Entities_Ticket_Status();
		$entityTicketDefaultStatus = $classTicketStatus->GetDefaultForNewTickets( null );
		if ( $entityTicketDefaultStatus != null )
		{
			$defaultStatusOpenTickets = $entityTicketDefaultStatus->TicketStatusId;
		}
		hst_Logger_AddEntry( 'DEBUG', $methodName, "Default Ticket Status Id: " . hst_Logger_exportVarContent($defaultStatusOpenTickets, true));

		//building query
		global $wpdb;

		$query = "INSERT INTO " . hst_consts_dbObjectsTableNamesTickets . " ";
		$query .= " ( ";
		$query .= " TicketTitle, ";
		$query .= " TicketProblem, ";
		$query .= " TicketCategoryId, ";
		$query .= " TicketPriorityId, ";
		$query .= " TicketStatusId, ";
		$query .= " TicketDateCreated, ";
		$query .= " TicketDateClosed, ";
		$query .= " TicketDateLastUpdated, ";
		$query .= " TicketCreatedUserId, ";
		$query .= " TicketAssignedUserId, ";
		$query .= " TicketCustomerUserId, ";
		$query .= " TicketCustomerUserDisplayName, ";
		$query .= " TicketCustomerUserEmail, ";
		$query .= " TicketCreationSource ";
		$query .= " ) ";

		$query .= " VALUES ";

		$query .= " ( ";
		$query .= " %s, ";									//TicketTitle
		$query .= " %s, ";									//TicketProblem
		$query .= " %d, ";									//TicketCategoryId
		$query .= " %d, ";									//TicketPriorityId
		$query .= " %d, ";									//TicketStatusId
		$query .= " now(), ";								//TicketDateCreated
		$query .= " NULL, ";								//TicketDateClosed
		$query .= " now(), ";								//TicketDateLastUpdated
		$query .= " %d, ";									//TicketCreatedUserId
		$query .= " %d, ";									//TicketAssignedUserId
		$query .= " %d, ";									//TicketCustomerId
		$query .= " %s, ";									//TicketCustomerUserDisplayName
		$query .= " %s, ";									//TicketCustomerUserEmail
		$query .= " %s ";									//TicketCreationSource
		$query .= " ) ";

		$query = $wpdb->prepare(
			$query,
			$paramsTicketTitle,								//TicketTitle
			$paramsTicketProblem,							//TicketProblem
			$paramsTicketCategoryId,						//TicketCategoryId
			$paramsTicketPriorityId,						//TicketPriorityId
			$defaultStatusOpenTickets,						//TicketStatusId
			$paramsTicketCreatedUserId,						//TicketCreatedUserId
			$paramsTicketAssignedUserId,					//TicketAssignedUserId
			$customerIdUseForInsert,						//TicketCustomerId
			$customerDisplayNameForInsert,					//TicketCustomerUserDisplayName
			$customerEmailForInsert,						//TicketCustomerUserEmail
			$paramsCreationSource							//TicketCreationSource
		);

		//logs the query text
		hst_Logger_AddEntry( 'QUERY', $methodName,"Update Query: " . $query);

		$resultsQuery = $wpdb->query( $query );
		hst_Logger_AddEntry( 'DEBUG', $methodName, "Result query: " . hst_Logger_exportVarContent($resultsQuery, true));
		if ( $resultsQuery != false )
		{

			$result = $wpdb->insert_id;
			hst_Logger_AddEntry( 'DEBUG', $methodName, "Insert result: " . hst_Logger_exportVarContent($result, true));

		}
		else
		{

			return hst_Common_ReturnFunctionError( $methodName, $wpdb->last_error );

		}

		hst_Logger_AddEntry( 'DEBUG', $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry( 'FUNCTION', $methodName,"End");

		return $result;

	}


	//Retrieves a single ticket from the database
	//$params["TicketId"] is a mandatory data
	//$params["IncludeEvents"] is an optional param. If set to "1", this function will also return the ticket events
	//$params["IncludeEventsOnlyForCustomerView"] is an optional param. If set to "1", this function will also return the ticket events, but only for customer to see (public agent messages and customer messages)
	//$params["MakeDescriptionAsFirstEvent"] set to have ticket problem as first messages entry
	public function GetFromDB( $params )
	{

		$methodName = 'hst_Classes_Ticket.GetFromDB';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		//checking for mandatory input params
		if ( isset($params["TicketId"]) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketId' parameter is missing. Aborting." );
		}

		global $wpdb;

		//building query text
		$query = hst_consts_dbSelectsTickets;
		$query .= " WHERE TicketId=%d ";
		hst_Logger_AddEntry("DEBUG", $methodName, "Query Text: " . $query);

		//firing query
		$query = $wpdb->prepare( $query, $params["TicketId"] );
		$row = $wpdb->get_row( $query , OBJECT );

		if ( $wpdb->last_error == '' )
		{

			//transcoding
			$newTicketObject = $this->TranscodeFromDB( $row );
			$result = $newTicketObject;

			//if set, adds the ticket events to the entity (all events)
			if ( isset($params["IncludeEvents"]))
			{

				if ( $params["IncludeEvents"] == 1 )
				{

					hst_Logger_AddEntry("DEBUG", $methodName, "Including Ticket events (all events types)");

					$classTicketEvents = new hst_Classes_Ticket_Event();
					$listEventsParams = array();
					$listEventsParams["TicketId"] = $params["TicketId"];
					$result->TicketEvents = $classTicketEvents->ListForTicketId( $listEventsParams );

				}

			}

			//if set, adds the ticket events to the entity, only the ones available to customer to see
			if ( isset($params["IncludeEventsOnlyForCustomerView"]))
			{

				if ( $params["IncludeEventsOnlyForCustomerView"] == 1 )
				{

					hst_Logger_AddEntry("DEBUG", $methodName, "Including Ticket events for customers");

					$classTicketEvents = new hst_Classes_Ticket_Event();
					$listEventsParams = array();
					$listEventsParams["TicketId"] = $params["TicketId"];
					$listEventsParams["OnlyCustomerViewEvents"] = "1";
					$result->TicketEvents = $classTicketEvents->ListForTicketId( $listEventsParams );

				}

			}

			//if set, will make the ticket problem as first message
			if ( isset($params["MakeDescriptionAsFirstEvent"]))
			{

				if ( $params["MakeDescriptionAsFirstEvent"] == 1 )
				{

					hst_Logger_AddEntry("DEBUG", $methodName, "Making tikcet problem as first message");

					$TicketEventForProblemDescription = new hst_Entities_Ticket_Event();

					$TicketEventForProblemDescription->TicketEventAuthorAvatar = get_avatar( $newTicketObject->TicketCustomerUserId, 32 );
					if ( get_current_user_id() == $newTicketObject->TicketCustomerUserId )
					{
						$TicketEventForProblemDescription->TicketEventAuthorIsMe = 1;
					}
					else
					{
						$TicketEventForProblemDescription->TicketEventAuthorIsMe = 0;
					}
					$TicketEventForProblemDescription->TicketEventDate = $newTicketObject->TicketDateCreated;
					$TicketEventForProblemDescription->TicketEventDateText = hst_Common_ReturnNiceDate($newTicketObject->TicketDateCreated);
					$TicketEventForProblemDescription->TicketEventMessageContent = $newTicketObject->TicketProblem;
					$TicketEventForProblemDescription->TicketEventType = "message";
					$TicketEventForProblemDescription->TicketEventUserDisplayName = $newTicketObject->TicketCustomerUserDisplayName;
					$TicketEventForProblemDescription->TicketEventUserType = "customer";
					$TicketEventForProblemDescription->TicketEventDateHumanTimeDiff = hst_Common_DateReturnHumanDifference( $TicketEventForProblemDescription->TicketEventDate );

					array_unshift( $result->TicketEvents, $TicketEventForProblemDescription );

				}

			}

		}
		else
		{
			return hst_Common_ReturnFunctionError( $methodName, $wpdb->last_error );
		}

		hst_Logger_AddEntry("DEBUG", $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry("FUNCTION", $methodName,"End");

		return $result;

	}


	//Retrieves tickets from the tickets table. This function handles the result of the tickets list control
	//params["TicketStatusId"] optional param that can contain a integer of the Status Id to filter the status for.
	//params["FilterKey"] optional param that can contain a keywork to search in Tickets properties.
	//params["SortingKey"] optional param that defines the sorting of the result set: 'dateDesc','dateAsc','numberDesc','numberAsc'
	//params["TicketCustomerUserId"] optional param to filter tickets only for a single customer
	//params["LimitRows"] optional param, will limit the query to X rows
	public function ListFromDB( $params )
	{

		$methodName = 'hst_Classes_Ticket.ListFromDB';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		//checking for input params
		$paramsFilterKey = '';
		if ( isset($params["FilterKey"]) )
		{
			$paramsFilterKey = $params["FilterKey"];
			hst_Logger_AddEntry( 'DEBUG', $methodName, "'FilterKey' parameter received is " . $paramsFilterKey );
		}
		$paramsTicketStatusId = '';
		if ( isset($params["TicketStatusId"]) )
		{
			$paramsTicketStatusId = $params["TicketStatusId"];
			hst_Logger_AddEntry( 'DEBUG', $methodName, "'TicketStatusId' parameter received is " . $paramsTicketStatusId );
		}
		$paramsSortingKey = '';
		if ( isset($params["SortingKey"]) )
		{
			$paramsSortingKey = $params["SortingKey"];
			hst_Logger_AddEntry( 'DEBUG', $methodName, "'SortingKey' parameter received is " . $paramsSortingKey );
		}
		$paramsTicketCustomerUserId = '';
		if ( isset($params["TicketCustomerUserId"]) )
		{
			$paramsTicketCustomerUserId = $params["TicketCustomerUserId"];
			hst_Logger_AddEntry( 'DEBUG', $methodName, "'TicketCustomerUserId' parameter received is " . $paramsTicketCustomerUserId );
		}
		$paramsLimitRow = '';
		if ( isset($params["LimitRows"]) )
		{
			$paramsLimitRow = $params["LimitRows"];
			hst_Logger_AddEntry( 'DEBUG', $methodName, "'LimitRow' parameter received is " . $paramsLimitRow );
		}

		global $wpdb;

		//building query text
		$query = hst_consts_dbSelectsTickets;
		$query .= " WHERE 1=1 ";

		//filter by keyword, if passed
		if ( $paramsFilterKey != '' )
		{
			$query .= " AND ( T.TicketTitle LIKE %s OR T.TicketId = %d OR T.TicketCustomerUserDisplayName LIKE %s ) ";
		}
		else
		{
			$query .= " AND '' LIKE %s AND -999<>%d AND '' LIKE %s ";
		}

		//filter by status id, if passed
		if ( $paramsTicketStatusId != '' )
		{
			$query .= " AND T.TicketStatusId=%d ";
		}
		else
		{
			$query .= " AND -999<>%d ";
		}

		//filter by customer id, if passed
		if ( $paramsTicketCustomerUserId != '' )
		{
			$query .= " AND T.TicketCustomerUserId=%d ";
		}
		else
		{
			$query .= " AND -999<>%d ";
		}

		//Setting ordering, if passed
		if ( $paramsSortingKey != '' )
		{

			if ( $paramsSortingKey == 'dateDesc' )
			{
				$query .= " ORDER BY TicketDateCreated DESC ";
			}
			if ( $paramsSortingKey == 'dateAsc' )
			{
				$query .= " ORDER BY TicketDateCreated ASC ";
			}
			if ( $paramsSortingKey == 'numberDesc' )
			{
				$query .= " ORDER BY TicketId DESC ";
			}
			if ( $paramsSortingKey == 'numberAsc' )
			{
				$query .= " ORDER BY TicketId ASC ";
			}

		}

		//Adding Limit parameter
		if ( $paramsLimitRow != '' )
		{
			$query .= " LIMIT " . $paramsLimitRow ;
		}

		hst_Logger_AddEntry("DEBUG", $methodName, "Query Text: " . $query);

		//firing query
		$query = $wpdb->prepare( $query,
								 '%' . $wpdb->esc_like($paramsFilterKey) . '%',
								 $wpdb->esc_like($paramsFilterKey),
								 '%' . $wpdb->esc_like($paramsFilterKey) . '%',
								 $paramsTicketStatusId,
								 $paramsTicketCustomerUserId
							   );
		$dbrows = $wpdb->get_results( $query , OBJECT );

		if ( $wpdb->last_error == '' )
		{

			//log elements found
			hst_Logger_AddEntry("DEBUG", $methodName, "Found Tickets: " . count($dbrows) );

			//setting transcoding options
			$transcodeParams = array();

			$result = array();

			//filling an array and returning only the needed content
			foreach( $dbrows as $row )
			{

				$newTicketObject = $this->TranscodeFromDB( $row, $transcodeParams );

				array_push($result, $newTicketObject);

			}

		}
		else
		{
			return hst_Common_ReturnFunctionError( $methodName, $wpdb->last_error );
		}

		hst_Logger_AddEntry("DEBUG", $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry("FUNCTION", $methodName,"End");

		return $result;

	}


	//This function gives a list of Ticket Statuses with a counter of tickets list in this moment.
	//Used to render the tickets simple search panel
	public function ReturnStatusWithTicketsCounters( $params )
	{

		$methodName = 'hst_Classes_Ticket.ReturnStatusWithTicketsCounters';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		//gets the list of ticket statuses
		$ticketStatusClass = new hst_Classes_Ticket_Status();
		$listTicketStatus = $ticketStatusClass->ListFromDB( null );

		global $wpdb;

		//iterate every status and counts the tickets in it
		foreach( $listTicketStatus as $statusEntity )
		{

			//building query text
			$query = "SELECT COUNT(*) FROM " . hst_consts_dbObjectsTableNamesTickets . " WHERE TicketStatusId=%d";
			hst_Logger_AddEntry("DEBUG", $methodName, "Query Text: " . $query);

			//firing query
			$query = $wpdb->prepare( $query, $statusEntity->TicketStatusId );
			$row = $wpdb->get_var( $query );

			if ( $row != null )
			{

				$statusEntity->TotalTicketsForCounters = $row;

			}
			else
			{
				return hst_Common_ReturnFunctionError( $methodName, $wpdb->last_error );
			}

		}

		$result = $listTicketStatus;

		hst_Logger_AddEntry("DEBUG", $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry( 'DEBUG', $methodName, 'Function has ended' );

		return $result;

	}


	//this function saves some of the ticket details to the database
	//$params["TicketId"] is a mandatory data
	//$params["TicketCategoryId"] is an optional data
	//$params["TicketStatusId"] is an optional data
	//$params["TicketPriorityId"] is an optional data
	//$params["TicketCustomerId"] is an optional data
	public function SaveTicketData( $params )
	{

	    $methodName = 'hst_Classes_Ticket.SaveTicketData';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		//checking for mandatory input params
		if ( isset($params["TicketId"]) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketId' parameter is missing. Aborting." );
		}

		//logging parmas
		hst_Logger_AddEntry("DEBUG", $methodName, "Input params: " . hst_Logger_exportVarContent($params, true));

		//retrieves the existing ticket data
		$paramsGetTicket = array();
		$paramsGetTicket["TicketId"] = $params["TicketId"];
		$existingTicketEntity = $this->GetFromDB($paramsGetTicket);

		if ( $existingTicketEntity != null )
		{

			//if the category id was passed, proceeds to update it (if different from the current value of this object)
			if ( isset( $params["TicketCategoryId"] ) )
			{

				hst_Logger_AddEntry("DEBUG", $methodName, "param TicketCategoryId was found: " . $params["TicketCategoryId"]);

				//checks if the value of the param is different from the current
				if ( $existingTicketEntity->TicketCategoryId != $params["TicketCategoryId"] )
				{

					//value is different, proceed to save
					hst_Logger_AddEntry("DEBUG", $methodName, "Ticket Category changed, proceed to save");

					//saving current ticket category name for old value
					$currentTicketCategoryValue = $existingTicketEntity->TicketCategoryText;

					//saving
					$paramsUpdateTicketCategory = array();
					$paramsUpdateTicketCategory["TicketId"] = $params["TicketId"];
					$paramsUpdateTicketCategory["TicketCategoryId"] = $params["TicketCategoryId"];
					$this->UpdateCategory( $paramsUpdateTicketCategory );

					//getting name of category for the new value
					$updatedTicketCategoryValue = "";
					$classCategory = new hst_Classes_Ticket_Category;
					$paramsGetCategory = array();
					$paramsGetCategory["TicketCategoryId"] = $params["TicketCategoryId"];
					$updatedCategoryEntity = $classCategory->GetFromDB( $paramsGetCategory );
					if ( $updatedCategoryEntity != null )
					{
						$updatedTicketCategoryValue = $updatedCategoryEntity->TicketCategoryDescription;
					}

					//adding ticket event
					$ticketEventEntity = new hst_Entities_Ticket_Event();
					$ticketEventEntity->TicketId = $params["TicketId"];
					$ticketEventEntity->TicketEventUserType = "agent";
					if (is_user_logged_in())
					{
						$ticketEventEntity->TicketEventUserId = get_current_user_id();
						$ticketEventEntity->TicketEventUserDisplayName = wp_get_current_user()->display_name;
					}
					$ticketEventEntity->TicketEventType = "ticketdatachange";
					$ticketEventEntity->TicketEventUserDataUpdateContent = "Ticket Category changed from '" . $currentTicketCategoryValue . "' to '" . $updatedTicketCategoryValue . "'";
					$ticketEventClass = new hst_Classes_Ticket_Event();
					$ticketEventClass->AddTicketEventData($ticketEventEntity);
					hst_Logger_AddEntry("DEBUG", $methodName, "Ticket Category changed event added");

					//set the last update date of a ticket
					$paramsUpdateLastUpdateDate = array();
					$paramsUpdateLastUpdateDate["TicketId"] = $params["TicketId"];
					$this->UpdateLastUpdateDate($paramsUpdateLastUpdateDate);

					hst_Logger_AddEntry("DEBUG", $methodName, "Ticket Category updated");

				}
				else
				{

					//value is the same, no need to save
					hst_Logger_AddEntry("DEBUG", $methodName, "Ticket Category unchanged, skipping save");

				}

			}

			//if the status id was passed, proceeds to update it (if different from the current value of this object)
			if ( isset( $params["TicketStatusId"] ) )
			{

				hst_Logger_AddEntry("DEBUG", $methodName, "param TicketStatusId was found: " . $params["TicketStatusId"]);

				//checks if the value of the param is different from the current
				if ( $existingTicketEntity->TicketStatusId != $params["TicketStatusId"] )
				{

					//value is different, proceed to save
					hst_Logger_AddEntry("DEBUG", $methodName, "Ticket Status changed, proceed to save");

					//saving current ticket status name for old value
					$currentTicketStatusValue = $existingTicketEntity->TicketStatusText;

					//saving
					$paramsUpdateTicketStatus = array();
					$paramsUpdateTicketStatus["TicketId"] = $params["TicketId"];
					$paramsUpdateTicketStatus["TicketStatusId"] = $params["TicketStatusId"];
					$this->UpdateStatus( $paramsUpdateTicketStatus );

					//getting name of status for the new value
					$updatedTicketStatusValue = "";
					$classStatus = new hst_Classes_Ticket_Status;
					$paramsGetStatus = array();
					$paramsGetStatus["TicketStatusId"] = $params["TicketStatusId"];
					$updatedStatusEntity = $classStatus->GetFromDB( $paramsGetStatus );
					if ( $updatedStatusEntity != null )
					{
						$updatedTicketStatusValue = $updatedStatusEntity->TicketStatusDescription;
					}

					//adding ticket event
					$ticketEventEntity = new hst_Entities_Ticket_Event();
					$ticketEventEntity->TicketId = $params["TicketId"];
					$ticketEventEntity->TicketEventUserType = "agent";
					if (is_user_logged_in())
					{
						$ticketEventEntity->TicketEventUserId = get_current_user_id();
						$ticketEventEntity->TicketEventUserDisplayName = wp_get_current_user()->display_name;
					}
					$ticketEventEntity->TicketEventType = "ticketdatachange";
					$ticketEventEntity->TicketEventUserDataUpdateContent = "Ticket Status changed from '" . $currentTicketStatusValue . "' to '" . $updatedTicketStatusValue . "'";
					$ticketEventClass = new hst_Classes_Ticket_Event();
					$ticketEventClass->AddTicketEventData($ticketEventEntity);
					hst_Logger_AddEntry("DEBUG", $methodName, "Ticket Status changed event added");

					//set the last update date of a ticket
					$paramsUpdateLastUpdateDate = array();
					$paramsUpdateLastUpdateDate["TicketId"] = $params["TicketId"];
					$this->UpdateLastUpdateDate($paramsUpdateLastUpdateDate);

					hst_Logger_AddEntry("DEBUG", $methodName, "Ticket Status updated");

				}
				else
				{

					//value is the same, no need to save
					hst_Logger_AddEntry("DEBUG", $methodName, "Ticket Status unchanged, skipping save");

				}

			}

			//if the priority id was passed, proceeds to update it (if different from the current value of this object)
			if ( isset( $params["TicketPriorityId"] ) )
			{

				hst_Logger_AddEntry("DEBUG", $methodName, "param TicketPriorityId was found: " . $params["TicketPriorityId"]);

				//checks if the value of the param is different from the current
				if ( $existingTicketEntity->TicketPriorityId != $params["TicketPriorityId"] )
				{

					//value is different, proceed to save
					hst_Logger_AddEntry("DEBUG", $methodName, "Ticket Priority changed, proceed to save");

					//saving current ticket priority name for old value
					$currentTicketPriorityValue = $existingTicketEntity->TicketPriorityText;

					//saving
					$paramsUpdateTicketPriority = array();
					$paramsUpdateTicketPriority["TicketId"] = $params["TicketId"];
					$paramsUpdateTicketPriority["TicketPriorityId"] = $params["TicketPriorityId"];
					$this->UpdatePriority( $paramsUpdateTicketPriority );

					//getting name of priority for the new value
					$updatedTicketPriorityValue = "";
					$classPriority = new hst_Classes_Ticket_Priority;
					$paramsGetPriority = array();
					$paramsGetPriority["TicketPriorityId"] = $params["TicketPriorityId"];
					$updatedPriorityEntity = $classPriority->GetFromDB( $paramsGetPriority );
					if ( $updatedPriorityEntity != null )
					{
						$updatedTicketPriorityValue = $updatedPriorityEntity->TicketPriorityDescription;
					}

					//adding ticket event
					$ticketEventEntity = new hst_Entities_Ticket_Event();
					$ticketEventEntity->TicketId = $params["TicketId"];
					$ticketEventEntity->TicketEventUserType = "agent";
					if (is_user_logged_in())
					{
						$ticketEventEntity->TicketEventUserId = get_current_user_id();
						$ticketEventEntity->TicketEventUserDisplayName = wp_get_current_user()->display_name;
					}
					$ticketEventEntity->TicketEventType = "ticketdatachange";
					$ticketEventEntity->TicketEventUserDataUpdateContent = "Ticket Priority changed from '" . $currentTicketPriorityValue . "' to '" . $updatedTicketPriorityValue . "'";
					$ticketEventClass = new hst_Classes_Ticket_Event();
					$ticketEventClass->AddTicketEventData($ticketEventEntity);
					hst_Logger_AddEntry("DEBUG", $methodName, "Ticket Priority changed event added");

					//set the last update date of a ticket
					$paramsUpdateLastUpdateDate = array();
					$paramsUpdateLastUpdateDate["TicketId"] = $params["TicketId"];
					$this->UpdateLastUpdateDate($paramsUpdateLastUpdateDate);

					hst_Logger_AddEntry("DEBUG", $methodName, "Ticket Priority updated");

				}
				else
				{

					//value is the same, no need to save
					hst_Logger_AddEntry("DEBUG", $methodName, "Ticket Priority unchanged, skipping save");

				}

			}

			//if the customer id was passed, proceeds to update it (if different from the current value of this object)
			if ( isset( $params["TicketCustomerId"] ) )
			{

				hst_Logger_AddEntry("DEBUG", $methodName, "param CustomerId was found: " . $params["TicketCustomerId"]);

				//checks if the value of the param is different from the current
				if ( $existingTicketEntity->TicketCustomerUserId != $params["TicketCustomerId"] )
				{

					//value is different, proceed to save
					hst_Logger_AddEntry("DEBUG", $methodName, "Customer changed, proceed to save");

					//saving current ticket customer name for old value
					$currentTicketCustomerValue = $existingTicketEntity->TicketCustomerUserDisplayName;

					//getting name of customer for the new value
					$updatedTicketCustomerValue = "";
					$classCustomers = new hst_Classes_Customer;
					$paramsGetCustomer = array();
					$paramsGetCustomer["CustomerId"] = $params["TicketCustomerId"];
					$updatedCustomerEntity = $classCustomers->GetFromDB( $paramsGetCustomer );
					if ( $updatedCustomerEntity != null )
					{
						$updatedTicketCustomerValue = $updatedCustomerEntity->CustomerDisplayName;
					}

					//saving
					$paramsUpdateTicketCustomer = array();
					$paramsUpdateTicketCustomer["TicketId"] = $params["TicketId"];
					$paramsUpdateTicketCustomer["TicketCustomerId"] = $params["TicketCustomerId"];
					$this->UpdateCustomer( $paramsUpdateTicketCustomer );

					//adding ticket event
					$ticketEventEntity = new hst_Entities_Ticket_Event();
					$ticketEventEntity->TicketId = $params["TicketId"];
					$ticketEventEntity->TicketEventUserType = "agent";
					if (is_user_logged_in())
					{
						$ticketEventEntity->TicketEventUserId = get_current_user_id();
						$ticketEventEntity->TicketEventUserDisplayName = wp_get_current_user()->display_name;
					}
					$ticketEventEntity->TicketEventType = "ticketdatachange";
					$ticketEventEntity->TicketEventUserDataUpdateContent = "Ticket Customer changed from '" . $currentTicketCustomerValue . "' to '" . $updatedTicketCustomerValue . "'";
					$ticketEventClass = new hst_Classes_Ticket_Event();
					$ticketEventClass->AddTicketEventData($ticketEventEntity);
					hst_Logger_AddEntry("DEBUG", $methodName, "Ticket Customer changed event added");

					//set the last update date of a ticket
					$paramsUpdateLastUpdateDate = array();
					$paramsUpdateLastUpdateDate["TicketId"] = $params["TicketId"];
					$this->UpdateLastUpdateDate($paramsUpdateLastUpdateDate);

					hst_Logger_AddEntry("DEBUG", $methodName, "Ticket Customer updated");

				}
				else
				{

					//value is the same, no need to save
					hst_Logger_AddEntry("DEBUG", $methodName, "Ticket Customer unchanged, skipping save");

				}

			}


		}
		else
		{

			return hst_Common_ReturnFunctionError( $methodName, "Get existing Ticket before update returned null value. Aborting." );

		}

		hst_Logger_AddEntry("DEBUG", $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry("FUNCTION", $methodName,"End");
		return true;

	}


	//This function translates a record from the table into a Ticket entity
	//$ticketDBRecord is a object from the table (a record)
	//$params["LoadCustomerAvatar"] = 1 will load the customer avatar value in the property
	private function TranscodeFromDB( $ticketDBRecord, $params )
	{

		$methodName = 'hst_Classes_Ticket.TranscodeFromDB';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = new hst_Entities_Ticket();

		$result->TicketId = $ticketDBRecord->TicketId;
		$result->TicketTitle = $ticketDBRecord->TicketTitle;
		$result->TicketProblem = $ticketDBRecord->TicketProblem;
		$result->TicketCategoryId = $ticketDBRecord->TicketCategoryId;
		$result->TicketStatusId = $ticketDBRecord->TicketStatusId;
		$result->TicketPriorityId = $ticketDBRecord->TicketPriorityId;
		$result->TicketCustomerUserId = $ticketDBRecord->TicketCustomerUserId;
		$result->TicketCustomerUserDisplayName = $ticketDBRecord->TicketCustomerUserDisplayName;
		$result->TicketCustomerUserEmail = $ticketDBRecord->TicketCustomerUserEmail;
		$result->TicketDateCreated = $ticketDBRecord->TicketDateCreated;
		$result->TicketDateClosed = $ticketDBRecord->TicketDateClosed;
		$result->TicketDateLastUpdated = $ticketDBRecord->TicketDateLastUpdated;
		$result->TicketAssignedUserId = $ticketDBRecord->TicketAssignedUserId;

		$result->TicketCategoryText = $ticketDBRecord->TicketCategoryText;
		$result->TicketStatusText = $ticketDBRecord->TicketStatusText;
		$result->TicketPriorityText = $ticketDBRecord->TicketPriorityText;
		$result->TicketDateCreatedText = hst_Common_ReturnNiceDate($ticketDBRecord->TicketDateCreated);
		$result->TicketDateClosedText = hst_Common_ReturnNiceDate($ticketDBRecord->TicketDateClosed);
		$result->TicketDateLastUpdatedText = hst_Common_ReturnNiceDate($ticketDBRecord->TicketDateLastUpdated);
		$result->TicketDateCreatedHumanTimeDiff = hst_Common_DateReturnHumanDifference( $result->TicketDateCreated );
		$result->TicketDateClosedHumanTimeDiff = hst_Common_DateReturnHumanDifference( $result->TicketDateClosed );
		$result->TicketDateLastUpdatedHumanTimeDiff = hst_Common_DateReturnHumanDifference( $result->TicketDateLastUpdated );
		$result->TicketAssignedUserDisplayName = $ticketDBRecord->TicketAssignedUserDisplayName;
		$result->TicketAssignedUserEmail = $ticketDBRecord->TicketAssignedUserEmail;
		$result->TicketCustomerUserAvatar = get_avatar( $result->TicketCustomerUserId, 32 );
		$result->TicketAssignedUserAvatar = get_avatar( $result->TicketAssignedUserId, 32 );

		//load customer avatar?
		if( isset( $params["LoadCustomerAvatar"] ) )
		{
			if( $params["LoadCustomerAvatar"] == 1 )
			{
				$result->TicketCustomerUserAvatar = get_avatar( $result->TicketCustomerUserId, 32 );
			}
		}

		hst_Logger_AddEntry("DEBUG", $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry("FUNCTION", $methodName,"End");

		return $result;

	}


	//This is a private function, use SaveTicketData instead
	//Updates a Ticket's category in the database
	//$params["TicketId"] is a mandatory data
	//$params["TicketCategoryId"] is a mandatory data
	private function UpdateCategory( $params )
	{

		$methodName = 'hst_Classes_Ticket.UpdateCategory';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		//checking for mandatory input params
		if ( isset($params["TicketId"]) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketId' parameter is missing. Aborting." );
		}
		if ( isset($params["TicketCategoryId"]) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketCategoryId' parameter is missing. Aborting." );
		}

		global $wpdb;

		$query = "UPDATE " . hst_consts_dbObjectsTableNamesTickets . " SET TicketCategoryId=%d WHERE TicketId=%d ";

		$query = $wpdb->prepare(
			$query,
			$params["TicketCategoryId"],
			$params["TicketId"]
		);

		//logs the query text
		hst_Logger_AddEntry( 'QUERY', $methodName, "Update Query: " . $query);

		$resultsQuery = $wpdb->query( $query );
		if ( $resultsQuery != false )
		{
			$result = true;
		}
		else
		{
			return hst_Common_ReturnFunctionError( $methodName, $wpdb->last_error );
		}

		hst_Logger_AddEntry("DEBUG", $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry("FUNCTION", $methodName,"End");

		return $result;

	}


	//Updates the ticket status
	//This is a private function, use SaveTicketData instead
	//$params["TicketId"] is a mandatory data
	//$params["TicketStatusId"] is a mandatory data
	private function UpdateStatus( $params )
	{

		$methodName = 'hst_Classes_Ticket.UpdateStatus';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		//checking for mandatory input params
		if ( isset($params["TicketId"]) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketId' parameter is missing. Aborting." );
		}
		if ( isset($params["TicketStatusId"]) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketStatusId' parameter is missing. Aborting." );
		}

		global $wpdb;

		$query = "UPDATE " . hst_consts_dbObjectsTableNamesTickets . " SET TicketStatusId=%d WHERE TicketId=%d ";

		$query = $wpdb->prepare(
			$query,
			$params["TicketStatusId"],
			$params["TicketId"]
		);

		//logs the query text
		hst_Logger_AddEntry( 'QUERY', $methodName, "Update Query: " . $query);

		$resultsQuery = $wpdb->query( $query );
		if ( $resultsQuery != false )
		{

			//checks if the selected status is a closed ticket status
			$classTicketStatus = new hst_Classes_Ticket_Status();
			$entityTicketStatus = $classTicketStatus->GetFromDB( array( "TicketStatusId" => $params["TicketStatusId"] ) );
			hst_Logger_AddEntry("DEBUG", $methodName, "Status Retrieve value: " . hst_Logger_exportVarContent($entityTicketStatus, true));

			if ( is_wp_error( $entityTicketStatus ) == false )
			{

				if ( $entityTicketStatus->TicketStatusIsClosed == 1 )
				{

					hst_Logger_AddEntry("DEBUG", $methodName, "Closing the Ticket");

					//need to close the ticket
					$query = "UPDATE " . hst_consts_dbObjectsTableNamesTickets . " SET TicketDateClosed=NOW() WHERE TicketId=%d ";

					$query = $wpdb->prepare(
												$query,
												$params["TicketId"]
										    );

					$resultsQueryTicketClosed = $wpdb->query( $query );

					if ( $resultsQuery != false )
					{
						//all good
					}
					else
					{
						return hst_Common_ReturnFunctionError( $methodName, $wpdb->last_error );
					}

				}

			}
			else
			{
				//returns the error
				return $entityTicketStatus;
			}

			//all good
			$result = true;

		}
		else
		{
			return hst_Common_ReturnFunctionError( $methodName, $wpdb->last_error );
		}

		hst_Logger_AddEntry("DEBUG", $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry("FUNCTION", $methodName,"End");

		return $result;

	}


	//Updates the ticket priority
	//This is a private function, use SaveTicketData instead
	//$params["TicketId"] is a mandatory data
	//$params["TicketPriorityId"] is a mandatory data
	private function UpdatePriority( $params )
	{

		$methodName = 'hst_Classes_Ticket.UpdatePriority';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		//checking for mandatory input params
		if ( isset($params["TicketId"]) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketId' parameter is missing. Aborting." );
		}
		if ( isset($params["TicketPriorityId"]) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketPriorityId' parameter is missing. Aborting." );
		}

		global $wpdb;

		$query = "UPDATE " . hst_consts_dbObjectsTableNamesTickets . " SET TicketPriorityId=%d WHERE TicketId=%d ";

		$query = $wpdb->prepare(
			$query,
			$params["TicketPriorityId"],
			$params["TicketId"]
		);

		//logs the query text
		hst_Logger_AddEntry( 'QUERY', $methodName, "Update Query: " . $query);

		$resultsQuery = $wpdb->query( $query );
		if ( $resultsQuery != false )
		{
			$result = true;
		}
		else
		{
			return hst_Common_ReturnFunctionError( $methodName, $wpdb->last_error );
		}

		hst_Logger_AddEntry("DEBUG", $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry("FUNCTION", $methodName,"End");

		return $result;

	}


	//Updates the ticket customer
	//This is a private function, use SaveTicketData instead
	//$params["TicketId"] is a mandatory data
	//$params["TicketCustomerId"] is a mandatory data
	private function UpdateCustomer( $params )
	{

		$methodName = 'hst_Classes_Ticket.UpdateCustomer';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		//checking for mandatory input params
		if ( isset($params["TicketId"]) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketId' parameter is missing. Aborting." );
		}
		if ( isset($params["TicketCustomerId"]) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketCustomerId' parameter is missing. Aborting." );
		}

		//getting name of customer for the new value
		$newCustomerDisplayName = "";
		$newCustomerEmail = "";
		$classCustomers = new hst_Classes_Customer;
		$paramsGetCustomer = array();
		$paramsGetCustomer["CustomerId"] = $params["TicketCustomerId"];
		$newCustomerEntity = $classCustomers->GetFromDB( $paramsGetCustomer );
		if ( $newCustomerEntity != null )
		{
			$newCustomerDisplayName = $newCustomerEntity->CustomerDisplayName;
			$newCustomerEmail = $newCustomerEntity->CustomerEmail;
		}

		global $wpdb;

		$query = "UPDATE " . hst_consts_dbObjectsTableNamesTickets . " SET " .
																	 " TicketCustomerUserId=%d, " .
																	 " TicketCustomerUserDisplayName=%s, " .
																	 " TicketCustomerUserEmail=%s " .
																	 " WHERE TicketId=%d ";

		$query = $wpdb->prepare(
			$query,
			$params["TicketCustomerId"],
			$newCustomerDisplayName,
			$newCustomerEmail,
			$params["TicketId"]
		);

		//logs the query text
		hst_Logger_AddEntry( 'QUERY', $methodName, "Update Query: " . $query);

		$resultsQuery = $wpdb->query( $query );
		if ( $resultsQuery != false )
		{
			$result = true;
		}
		else
		{
			return hst_Common_ReturnFunctionError( $methodName, $wpdb->last_error );
		}

		hst_Logger_AddEntry("DEBUG", $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry("FUNCTION", $methodName,"End");

		return $result;

	}


	//Updates the last udpate date of the ticket with the current time
	//This function is used privately
	//$params["TicketId"] is a mandatory data
	public function UpdateLastUpdateDate( $params )
	{

		$methodName = 'hst_Classes_Ticket.UpdateLastUpdateDate';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		//checking for mandatory input params
		if ( isset($params["TicketId"]) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketId' parameter is missing. Aborting." );
		}

		global $wpdb;

		$query = "UPDATE " . hst_consts_dbObjectsTableNamesTickets . " SET TicketDateLastUpdated=now() WHERE TicketId=%d ";

		$query = $wpdb->prepare(
			$query,
			$params["TicketId"]
		);

		//logs the query text
		hst_Logger_AddEntry( 'QUERY', $methodName, "Update Query: " . $query);

		$resultsQuery = $wpdb->query( $query );
		if ( $resultsQuery != false )
		{
			$result = true;
		}
		else
		{
			return hst_Common_ReturnFunctionError( $methodName, $wpdb->last_error );
		}

		hst_Logger_AddEntry("DEBUG", $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry("FUNCTION", $methodName,"End");

		return $result;

	}


	//Deletes a single ticket from the database. Also deletes the events related to this ticket.
	//$params["TicketId"] is a mandatory data
	public function DeleteTicketFromDB( $params )
	{

		$methodName = 'hst_Classes_Ticket.DeleteTicketFromDB';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		//checking for mandatory input params
		if ( isset($params["TicketId"]) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketId' parameter is missing. Aborting." );
		}

		global $wpdb;

		//building query text
		$query = "DELETE FROM " . hst_consts_dbObjectsTableNamesTickets;
		$query .= " WHERE TicketId=%d ";
		hst_Logger_AddEntry("DEBUG", $methodName, "Query Text: " . $query);

		//firing query
		$query = $wpdb->prepare( $query, $params["TicketId"] );
		$row = $wpdb->query( $query );

		if ( $row != false )
		{

			hst_Logger_AddEntry("DEBUG", $methodName, "Ticket deleted.");

			//calls method to delete events as well
			$classTicketEvents = new hst_Classes_Ticket_Event();
			$paramsDeleteTicketEvents = array();
			$paramsDeleteTicketEvents["TicketId"] = $params["TicketId"];
			$classTicketEvents->DeleteForTicketId( $paramsDeleteTicketEvents  );
			hst_Logger_AddEntry("DEBUG", $methodName, "Ticket events deleted.");

			//calls method to delete files for an entity id and ticket type
			$classAttachments = new hst_Classes_Attachments();
			$classAttachments->DeleteByEntityTypeAndEntityId( array( "EntityType" => "ticket", "EntityId" => $params["TicketId"]) );

			$result = true;

		}
		else
		{
			return hst_Common_ReturnFunctionError( $methodName, $wpdb->last_error );
		}

		hst_Logger_AddEntry("DEBUG", $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry("FUNCTION", $methodName,"End");

		return $result;

	}

}

?>