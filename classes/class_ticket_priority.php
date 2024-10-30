<?php

//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//Class that holds a ticket priority
class hst_Classes_Ticket_Priority
{


	//Get a single ticket priority by its id
	//params["TicketPriorityId"] mandatory
	function GetFromDB( $params )
	{

		$methodName = 'hst_Classes_Ticket_Priority.GetFromDB';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		//checking for mandatory input params
		if ( isset(  $params["TicketPriorityId"] ) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketPriorityId' value is missing. Aborting." );
		}

		global $wpdb;

		//building query text
		$query = " SELECT * FROM " . hst_consts_dbObjectsTableNamesTicketsPriorities . " WHERE TicketPriorityId=%d ";
		hst_Logger_AddEntry("DEBUG", $methodName, "Query Text: " . $query);

		//firing query
		$query = $wpdb->prepare( $query, $params["TicketPriorityId"] );
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



	//Get the ticket priority that is the default for just created tickets. Used when creating tickets from FE, where the priority dropdown is not shown.
	function GetDefaultForNewTickets( $params )
	{

		$methodName = 'hst_Classes_Ticket_Priority.GetDefaultForNewTickets';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		global $wpdb;

		//building query text
		$query = " SELECT * FROM " . hst_consts_dbObjectsTableNamesTicketsPriorities . " WHERE TicketPriorityIsDefaultForNewTickets=1 LIMIT 1 ";
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
			hst_Logger_AddEntry("DEBUG", $methodName, "Default priority for new tickets was not found. Fall back to first status.");

			$query = " SELECT * FROM " . hst_consts_dbObjectsTableNamesTicketsPriorities . " LIMIT 1 ";
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



	//Lists all tickets priority available from the db table
	function ListFromDB( $params )
	{

		$methodName = 'hst_Classes_Ticket_Priority.ListFromDB';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		global $wpdb;

		//building query text
		$query = " SELECT * FROM " . hst_consts_dbObjectsTableNamesTicketsPriorities;
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

			$newTicketPriorityEntity = $this->TranscodeFromDB( $row );
			array_push($result, $newTicketPriorityEntity);

		}

		hst_Logger_AddEntry("DEBUG", $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry("FUNCTION", $methodName,"End");

		return $result;

	}


	//This function inserts a ticket priority in the database
	public function InsertStatusInDB( $params )
	{

		$methodName = 'hst_Classes_Ticket_Priority.InsertStatusInDB';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		//checking for mandatory input params
		if ( isset( $params->TicketPriorityDescription ) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketPriorityDescription' value is missing. Aborting." );
		}
		if ( isset( $params->TicketPriorityIsDefaultForNewTickets ) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketPriorityIsDefaultForNewTickets' value is missing. Aborting." );
		}

		global $wpdb;

		$query = "INSERT INTO " . hst_consts_dbObjectsTableNamesTicketsPriorities . " ";
		$query .= " ( ";
		$query .= " TicketPriorityDescription, ";
		$query .= " TicketPriorityIsDefaultForNewTickets ";
		$query .= " ) ";
		$query .= " VALUES ";
		$query .= " ( ";
		$query .= " %s, ";
		$query .= " %d ";
		$query .= " ) ";

		$query = $wpdb->prepare(
			$query,
			$params->TicketPriorityDescription,
			$params->TicketPriorityIsDefaultForNewTickets
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


	//This function transcode a record from the db into an entity
	function TranscodeFromDB( $params )
	{

		$methodName = 'hst_Classes_Ticket_Priority.TranscodeFromDB';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = new hst_Entities_Ticket_Priority();

		$result->TicketPriorityId = $params->TicketPriorityId;
		$result->TicketPriorityDescription = $params->TicketPriorityDescription;
		$result->TicketPriorityIsDefaultForNewTickets = $params->TicketPriorityIsDefaultForNewTickets;

		return $result;

	}

}

?>