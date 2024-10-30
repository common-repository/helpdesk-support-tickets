<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//Class that handles support ticket statuses
class hst_Classes_Ticket_Status
{


	//Get a single ticket status by its id
	//params["TicketStatusId"] mandatory
	function GetFromDB( $params )
	{

		$methodName = 'hst_Classes_Ticket_Status.GetFromDB';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		//checking for mandatory input params
		if ( isset(  $params["TicketStatusId"] ) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketStatusId' value is missing. Aborting." );
		}

		global $wpdb;

		//building query text
		$query = " SELECT * FROM " . hst_consts_dbObjectsTableNamesTicketsStatuses . " WHERE TicketStatusId=%d ";
		hst_Logger_AddEntry("DEBUG", $methodName, "Query Text: " . $query);

		//firing query
		$query = $wpdb->prepare( $query, $params["TicketStatusId"] );
		$dbrows = $wpdb->get_row( $query , OBJECT );

		if ( $wpdb->last_error != '' )
		{
			return hst_Common_ReturnFunctionError( $methodName, $wpdb->last_error );
		}

		//filling an array and returning only the needed content
		$result = $this->TranscodeFromDB( $dbrows );

		hst_Logger_AddEntry("DEBUG", $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry("FUNCTION", $methodName,"End");

		return $result;

	}


	//Get the ticket status that is the default for just created tickets (usually 'New')
	function GetDefaultForNewTickets( $params )
	{

		$methodName = 'hst_Classes_Ticket_Status.GetDefaultForNewTickets';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		global $wpdb;

		//building query text
		$query = " SELECT * FROM " . hst_consts_dbObjectsTableNamesTicketsStatuses . " WHERE TicketStatusIsDefaultForNewTickets=1 LIMIT 1 ";
		hst_Logger_AddEntry("DEBUG", $methodName, "Query Text: " . $query);

		//firing query
		$query = $wpdb->prepare( $query );
		$dbrows = $wpdb->get_row( $query , OBJECT );

		if ( $wpdb->last_error != '' )
		{
			return hst_Common_ReturnFunctionError( $methodName, $wpdb->last_error );
		}

		//checks if the query returned one result or nothing
		if ( $dbrows == null )
		{

			//the default status for new tickets is missing. Get the first status in the table
			hst_Logger_AddEntry("DEBUG", $methodName, "Default status for new tickets was not found. Fall back to first status.");

			$query = " SELECT * FROM " . hst_consts_dbObjectsTableNamesTicketsStatuses . " LIMIT 1 ";
			hst_Logger_AddEntry("DEBUG", $methodName, "Query Text: " . $query);

			//firing query
			$query = $wpdb->prepare( $query );
			$dbrows = $wpdb->get_row( $query , OBJECT );

			if ( $dbrows == false )
			{
				hst_Logger_AddEntry( 'ERROR', $methodName, $wpdb->last_error );
			}

		}

		//filling an array and returning only the needed content
		$result = $this->TranscodeFromDB( $dbrows );

		hst_Logger_AddEntry("DEBUG", $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry("FUNCTION", $methodName,"End");

		return $result;

	}


	//Lists all tickets statuses available from the db table
	function ListFromDB( $params )
	{

		$methodName = 'hst_Classes_Ticket_Status.ListFromDB';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		global $wpdb;

		//building query text
		$query = " SELECT * FROM " . hst_consts_dbObjectsTableNamesTicketsStatuses;
		hst_Logger_AddEntry("DEBUG", $methodName, "Query Text: " . $query);

		//firing query
		$dbrows = $wpdb->get_results( $query , OBJECT );

		if ( $wpdb->last_error != '' )
		{
			return hst_Common_ReturnFunctionError( $methodName, $wpdb->last_error );
		}

		$result = array();

		//filling an array and returning only the needed content
		foreach( $dbrows as $row )
		{

			$newTicketStatusEntity = $this->TranscodeFromDB( $row );
			array_push($result, $newTicketStatusEntity);

		}

		hst_Logger_AddEntry("DEBUG", $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry("FUNCTION", $methodName,"End");

		return $result;

	}


	//This function inserts a ticket status in the database
	public function InsertStatusInDB( $paramsTicketStatusEntity )
	{

		$methodName = 'hst_Classes_Ticket_Status.InsertStatusInDB';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($paramsTicketStatusEntity, true));
		$result = false;

		//checking for mandatory input params
		if ( isset( $paramsTicketStatusEntity->TicketStatusDescription ) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketStatusDescription' value is missing. Aborting." );
		}
		if ( isset( $paramsTicketStatusEntity->TicketStatusIsClosed ) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketStatusIsClosed' parameter is missing. Aborting." );
		}

		global $wpdb;
		$query = "INSERT INTO " . hst_consts_dbObjectsTableNamesTicketsStatuses . " ";
		$query .= " ( ";
		$query .= " TicketStatusDescription, ";
		$query .= " TicketStatusIsClosed, ";
		$query .= " TicketStatusIsDefaultForNewTickets ";
		$query .= " ) ";
		$query .= " VALUES ";
		$query .= " ( ";
		$query .= " %s, ";
		$query .= " %d, ";
		$query .= " %d ";
		$query .= " ) ";

		$query = $wpdb->prepare(
			$query,
			$paramsTicketStatusEntity->TicketStatusDescription,
			$paramsTicketStatusEntity->TicketStatusIsClosed,
			$paramsTicketStatusEntity->TicketStatusIsDefaultForNewTickets
		);

		//logs the query text
		hst_Logger_AddEntry( 'QUERY', $methodName,"Update Query: " . $query);

		$resultsQuery = $wpdb->query( $query );
		$result = $wpdb->insert_id;
		if ( $result == false )
		{
			return hst_Common_ReturnFunctionError( $methodName, $wpdb->last_error );
		}

		hst_Logger_AddEntry("DEBUG", $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry("FUNCTION", $methodName,"End");

		return $result;

	}



	//This function updates a ticket status in the database
	public function UpdateStatusInDB( $paramsTicketStatusEntity )
	{

		$methodName = 'hst_Classes_Ticket_Status.UpdateStatusInDB';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($paramsTicketStatusEntity, true));
		$result = false;

		//checking for mandatory input params
		if ( isset( $paramsTicketStatusEntity->TicketStatusId ) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketStatusId' value is missing. Aborting." );
		}
		if ( isset( $paramsTicketStatusEntity->TicketStatusDescription ) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketStatusDescription' value is missing. Aborting." );
		}
		if ( isset( $paramsTicketStatusEntity->TicketStatusIsClosed ) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketStatusIsClosed' parameter is missing. Aborting." );
		}
		if ( isset( $paramsTicketStatusEntity->TicketStatusIsDefaultForNewTickets ) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketStatusIsDefaultForNewTickets' parameter is missing. Aborting." );
		}
				if ( isset( $paramsTicketStatusEntity->TicketStatusBgColor ) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketStatusBgColor' parameter is missing. Aborting." );
		}

		global $wpdb;
		$query = "UPDATE " . hst_consts_dbObjectsTableNamesTicketsStatuses . " SET ";
		$query .= " TicketStatusDescription = %s, ";
		$query .= " TicketStatusIsClosed = %d, ";
		$query .= " TicketStatusIsDefaultForNewTickets = %d, ";
		$query .= " TicketStatusBgColor = %s ";
		$query .= " WHERE TicketStatusId = %d ";


		$query = $wpdb->prepare(
			$query,
			$paramsTicketStatusEntity->TicketStatusDescription,
			$paramsTicketStatusEntity->TicketStatusIsClosed,
			$paramsTicketStatusEntity->TicketStatusIsDefaultForNewTickets,
			$paramsTicketStatusEntity->TicketStatusBgColor,
			$paramsTicketStatusEntity->TicketStatusId
		);

		//logs the query text
		hst_Logger_AddEntry( 'QUERY', $methodName,"Update Query: " . $query);

		$result = $wpdb->query( $query );
		if ( $result == false )
		{
			return hst_Common_ReturnFunctionError( $methodName, $wpdb->last_error );
		}

		hst_Logger_AddEntry("DEBUG", $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry("FUNCTION", $methodName,"End");

		return $result;

	}


	//This function transcode a record from the db into an entity
	function TranscodeFromDB( $params )
	{

		$methodName = 'hst_Classes_Ticket_Status.TranscodeFromDB';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = new hst_Entities_Ticket_Status();

		$result->TicketStatusId = $params->TicketStatusId;
		$result->TicketStatusDescription = $params->TicketStatusDescription;
		$result->TicketStatusIsClosed = $params->TicketStatusIsClosed;
		$result->TicketStatusIsDefaultForNewTickets = $params->TicketStatusIsDefaultForNewTickets;
		$result->TicketStatusBgColor = $params->TicketStatusBgColor;

		return $result;

	}


}

?>