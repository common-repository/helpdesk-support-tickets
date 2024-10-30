<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//Class that represents a support ticket event
class hst_Classes_Ticket_Event
{


	//this function inserts a new event in the database about this event, some of the class properties values are mandatory before calling this function:
    //this->$TicketId;
	//this->$TicketEventUserType;
	//this->$TicketEventType;
	//this->$TicketEventMessageContent || this->$TicketEventUserDataUpdateContent;
	public function AddTicketEventData( $params )
	{

	    $methodName = 'hst_Classes_Ticket_Event.AddTicketEventData';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		//checking for mandatory input params
		if ( isset( $params->TicketId ) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketId' value is missing. Aborting." );
		}
		if ( isset( $params->TicketEventUserType ) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketEventUserType' value is missing. Aborting." );
		}
		if ( isset( $params->TicketEventType ) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketEventType' value is missing. Aborting." );
		}
		if ( isset( $params->TicketEventMessageContent ) == false && isset( $params->TicketEventUserDataUpdateContent ) == false )
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketEventMessageContent' and 'TicketEventUserDataUpdateContent' value is missing. Aborting." );
		}

		//calculated by system
		$ticketEventDate = date('Y-m-d H:i:s');

		//inserting new record
		global $wpdb;

		$query = "INSERT INTO " . hst_consts_dbObjectsTableNamesTicketsEvents . " ";
		$query .= " ( ";
		$query .= " TicketId, ";
		$query .= " TicketEventDate, ";
		$query .= " TicketEventUserType, ";
		$query .= " TicketEventUserId, ";
		$query .= " TicketEventUserDisplayName, ";
		$query .= " TicketEventType, ";
		$query .= " TicketEventMessageContent, ";
		$query .= " TicketEventUserDataUpdateContent ";
		$query .= " ) ";

		$query .= " VALUES ";

		$query .= " ( ";
		$query .= " %d, ";					//TicketId
		$query .= " %s, ";					//TicketEventDate
		$query .= " %s, ";					//TicketEventUserType
		$query .= " %d, ";					//TicketEventUserId
		$query .= " %s, ";					//TicketEventUserDisplayName
		$query .= " %s, ";					//TicketEventType
		$query .= " %s, ";					//TicketEventMessageContent
		$query .= " %s ";					//TicketEventUserDataUpdateContent
		$query .= " ) ";

		$query = $wpdb->prepare(
			$query,
			$params->TicketId,
			$ticketEventDate,
			$params->TicketEventUserType,
			$params->TicketEventUserId,
			$params->TicketEventUserDisplayName,
			$params->TicketEventType,
			$params->TicketEventMessageContent,
			$params->TicketEventUserDataUpdateContent
		);

		//logs the query text
		hst_Logger_AddEntry( 'QUERY', $methodName,"Update Query: " . $query);

		$resultsQuery = $wpdb->query( $query );
		$result = $wpdb->insert_id;
		if ( $result == false )
		{
			return hst_Common_ReturnFunctionError( $methodName, $wpdb->last_error );
		}

		//updates ticket last edit date
		$paramsUpdateLastUpdateDate = array();
		$paramsUpdateLastUpdateDate["TicketId"] = $params->TicketId;
		$classTicket = new hst_Classes_Ticket();
		$classTicket->UpdateLastUpdateDate($paramsUpdateLastUpdateDate);

		hst_Logger_AddEntry( 'DEBUG', $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry("FUNCTION", $methodName,"End");

		return $result;

	}


	//This function lists all events for a ticket id.
	//Used to show the messages and events in a ticket details page (left pane)
	//this->$TicketId is a mandatory param
	//this->$OnlyCustomerViewEvents optional event to filter only events that are visible to customer
	public function ListForTicketId( $params )
	{

		$methodName = 'hst_Classes_Ticket_Event.ListForTicketId';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		//checking for mandatory input params
		if ( isset(  $params["TicketId"] ) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketId' value is missing. Aborting." );
		}

		global $wpdb;

		//building query text
		$query = hst_consts_dbSelectsTicketsEvents;
		$query .= " WHERE TE.TicketId=%d ";

		//filtering only for customer view events?
		if ( isset( $params["OnlyCustomerViewEvents"] ) )
		{
			if ( $params["OnlyCustomerViewEvents"] == "1" )
			{
				$query .= " AND TE.TicketEventType='message' ";
			}
		}

		$query .= " ORDER BY TE.TicketEventDate ";
		hst_Logger_AddEntry("DEBUG", $methodName, "Query Text: " . $query);

		//firing query
		$query = $wpdb->prepare( $query, $params["TicketId"] );
		$dbrows = $wpdb->get_results( $query , OBJECT );

		if ( $wpdb->last_error != '' )
		{
			return hst_Common_ReturnFunctionError( $methodName, $wpdb->last_error );
		}

		hst_Logger_AddEntry( 'DEBUG', $methodName, "List of events retrieved from database.");

		$result = array();

		//filling an array
		$paramsTranscodeDB = array();
		$paramsTranscodeDB["LoadAuthorAvatar"] = 1;
		foreach( $dbrows as $row )
		{

			$newTicketEventObject = $this->TranscodeFromDB( $row, $paramsTranscodeDB );

			array_push($result, $newTicketEventObject);

		}

		hst_Logger_AddEntry( 'DEBUG', $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry("FUNCTION", $methodName,"End");

		return $result;

	}


	//This function gets a single ticket event by its id
	//params["TicketEventId"] is a mandatory param
	public function GetById( $params )
	{

		$methodName = 'hst_Classes_Ticket_Event.GetById';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		//checking for mandatory input params
		if ( isset(  $params["TicketEventId"] ) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketEventId' value is missing. Aborting." );
		}

		global $wpdb;

		//building query text
		$query = hst_consts_dbSelectsTicketsEvents;
		$query .= " WHERE TicketEventId=%d ";
		hst_Logger_AddEntry("DEBUG", $methodName, "Query Text: " . $query);

		//firing query
		$query = $wpdb->prepare( $query, $params["TicketEventId"] );
		$dbrows = $wpdb->get_row( $query , OBJECT );

		if ( $wpdb->last_error != '' )
		{
			return hst_Common_ReturnFunctionError( $methodName, $wpdb->last_error );
		}

		$paramsTranscodeDB = array();
		$paramsTranscodeDB["LoadAuthorAvatar"] = 1;
		$result = $this->TranscodeFromDB( $dbrows, $paramsTranscodeDB );

		hst_Logger_AddEntry( 'DEBUG', $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry("FUNCTION", $methodName,"End");

		return $result;

	}



	//This function delete all events for a ticket id.
	//Used when a support ticket is deleted, to clear this table as well, and not leave dirty data
	//$params[TicketId] is a mandatory param
	public function DeleteForTicketId( $params )
	{

		$methodName = 'hst_Classes_Ticket_Event.DeleteForTicketId';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		//checking for mandatory input params
		if ( isset(  $params["TicketId"] ) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketId' value is missing. Aborting." );
		}

		global $wpdb;

		//building query text
		$query = "DELETE FROM " . hst_consts_dbObjectsTableNamesTicketsEvents . " WHERE TicketId=%d ";
		hst_Logger_AddEntry("DEBUG", $methodName, "Query Text: " . $query);

		//firing query
		$query = $wpdb->prepare( $query, $params["TicketId"] );
		$dbrows = $wpdb->query( $query );

		if ( $dbrows == false )
		{
			return hst_Common_ReturnFunctionError( $methodName, $wpdb->last_error );
		}

		$result = true;

		hst_Logger_AddEntry( 'DEBUG', $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry("FUNCTION", $methodName,"End");

		return $result;

	}


	//This function translates a record from the table into a Ticket Event entity
	//$ticketEventDBRecord is a object from the table (a record)
	//$params["LoadAuthorAvatar"] set to 1 if you want the entity to have the url of the author avatar
	private function TranscodeFromDB( $ticketEventDBRecord, $params )
	{

		$methodName = 'hst_Classes_Ticket_Event.TranscodeFromDB';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = new hst_Entities_Ticket_Event();

		$result->TicketEventId = $ticketEventDBRecord->TicketEventId;
		$result->TicketId = $ticketEventDBRecord->TicketId;
		$result->TicketEventDate = $ticketEventDBRecord->TicketEventDate;
		$result->TicketEventUserType = $ticketEventDBRecord->TicketEventUserType;
		$result->TicketEventUserId = $ticketEventDBRecord->TicketEventUserId;
		$result->TicketEventUserDisplayName = $ticketEventDBRecord->TicketEventUserDisplayName;
		$result->TicketEventType = $ticketEventDBRecord->TicketEventType;
		$result->TicketEventMessageContent = $ticketEventDBRecord->TicketEventMessageContent;
		$result->TicketEventUserDataUpdateContent = $ticketEventDBRecord->TicketEventUserDataUpdateContent;

		$result->TicketEventDateText = hst_Common_ReturnNiceDate($ticketEventDBRecord->TicketEventDate);
		$result->TicketEventDateHumanTimeDiff = hst_Common_DateReturnHumanDifference( $result->TicketEventDate );

		//is the current user the same as the event author?
		if ( get_current_user_id() == $result->TicketEventUserId)
		{
			$result->TicketEventAuthorIsMe = 1;
		}
		else
		{
			$result->TicketEventAuthorIsMe = 0;
		}

		//load customer avatar?
		if( isset( $params["LoadAuthorAvatar"] ) )
		{
			if( $params["LoadAuthorAvatar"] == 1 )
			{
				$result->TicketEventAuthorAvatar = get_avatar( $result->TicketEventUserId, 32 );
			}
		}

		return $result;

	}

}

?>