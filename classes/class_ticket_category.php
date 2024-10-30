<?php

//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//Class that handles ticket categories functions
class hst_Classes_Ticket_Category
{


	//Get a single ticket category by its id
	//params["TicketCategoryId"] mandatory
	function GetFromDB( $params )
	{

		$methodName = 'hst_Classes_Ticket_Category.GetFromDB';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		//checking for mandatory input params
		if ( isset(  $params["TicketCategoryId"] ) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketCategoryId' value is missing. Aborting." );
		}

		global $wpdb;

		//building query text
		$query = " SELECT * FROM " . hst_consts_dbObjectsTableNamesTicketsCategories . " WHERE TicketCategoryId=%d ";
		hst_Logger_AddEntry("DEBUG", $methodName, "Query Text: " . $query);

		//firing query
		$query = $wpdb->prepare( $query, $params["TicketCategoryId"] );
		$dbrows = $wpdb->get_row( $query , OBJECT );

		if ( $wpdb->last_error == '' )
		{

			//filling an array and returning only the needed content
			$result = $this->TranscodeFromDB( $dbrows );

		}
		else
		{
			return hst_Common_ReturnFunctionError( $methodName, $wpdb->last_error );
		}

		hst_Logger_AddEntry("DEBUG", $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry("FUNCTION", $methodName,"End");

		return $result;

	}


	//Delete a single ticket category by its id
	//params["TicketCategoryId"] mandatory
	function DeleteFromDB( $params )
	{

		$methodName = 'hst_Classes_Ticket_Category.DeleteFromDB';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		//checking for mandatory input params
		if ( isset(  $params["TicketCategoryId"] ) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketCategoryId' value is missing. Aborting." );
		}

		global $wpdb;

		//building query text
		$query = " DELETE FROM " . hst_consts_dbObjectsTableNamesTicketsCategories . " WHERE TicketCategoryId=%d ";
		hst_Logger_AddEntry("DEBUG", $methodName, "Query Text: " . $query);

		//firing query
		$query = $wpdb->prepare( $query, $params["TicketCategoryId"] );
		$dbrows = $wpdb->query( $query );

		if ( $dbrows != false )
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


	//Lists all tickets categories available from the db table
	function ListFromDB( $params )
	{

		$methodName = 'hst_Classes_Ticket_Category.ListFromDB';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		global $wpdb;

		//building query text
		$query = " SELECT * FROM " . hst_consts_dbObjectsTableNamesTicketsCategories;
		hst_Logger_AddEntry("DEBUG", $methodName, "Query Text: " . $query);

		//firing query
		$dbrows = $wpdb->get_results( $query , OBJECT );

		if ( $wpdb->last_error == '' )
		{

			$result = array();

			//filling an array and returning only the needed content
			foreach( $dbrows as $row )
			{

				$newTicketCategoryEntity = $this->TranscodeFromDB( $row );
				array_push($result, $newTicketCategoryEntity);

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


	//This function inserts a ticket category in the database
	public function InsertCategoryInDB( $params )
	{

		$methodName = 'hst_Classes_Ticket_Category.InsertCategoryInDB';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		//checking for mandatory input params
		if ( isset( $params->TicketCategoryDescription ) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketCategoryDescription' value is missing. Aborting." );
		}

		global $wpdb;

		$query = "INSERT INTO " . hst_consts_dbObjectsTableNamesTicketsCategories . " ";
		$query .= " ( ";
		$query .= " TicketCategoryDescription ";
		$query .= " ) ";

		$query .= " VALUES ";

		$query .= " ( ";
		$query .= " %s ";
		$query .= " ) ";

		$query = $wpdb->prepare(
			$query,
			$params->TicketCategoryDescription
		);

		//logs the query text
		hst_Logger_AddEntry( 'QUERY', $methodName,"Update Query: " . $query);

		$resultsQuery = $wpdb->query( $query );
		$result = $wpdb->insert_id;
		if ( $result == false )
		{
			return hst_Common_ReturnFunctionError( $methodName, $wpdb->last_error );
		}

		hst_Logger_AddEntry( 'DEBUG', $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry( 'FUNCTION', $methodName,"End");

		return $result;

	}


	//This function updates a ticket category in the database
	public function UpdateCategoryInDB( $params )
	{

		$methodName = 'hst_Classes_Ticket_Category.UpdateCategoryInDB';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

        //checking for mandatory input params
		if ( isset( $params->TicketCategoryId ) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketCategoryId' value is missing. Aborting." );
		}
		if ( isset( $params->TicketCategoryDescription ) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'TicketCategoryDescription' value is missing. Aborting." );
		}

		global $wpdb;

		$query = "UPDATE " . hst_consts_dbObjectsTableNamesTicketsCategories . " SET ";
		$query .= " TicketCategoryDescription = %s ";
		$query .= " WHERE TicketCategoryId = %d ";

		$query = $wpdb->prepare(
			$query,
			$params->TicketCategoryDescription,
			$params->TicketCategoryId
		);

		//logs the query text
		hst_Logger_AddEntry( 'QUERY', $methodName,"Update Query: " . $query);

		$resultsQuery = $wpdb->query( $query );
		hst_Logger_AddEntry( 'QUERY', $methodName,"Result Query Response: " . hst_Logger_exportVarContent($resultsQuery) );
		if ( $resultsQuery == false && $resultsQuery != 0 )
		{
			return hst_Common_ReturnFunctionError( $methodName, $wpdb->last_error );
		}
		else
		{
			$result = true;
		}

		hst_Logger_AddEntry( 'DEBUG', $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry( 'FUNCTION', $methodName,"End");

		return $result;

	}


	//This function transcode a record from the db into an entity
	function TranscodeFromDB( $params )
	{

		$methodName = 'hst_Classes_Ticket_Category.TranscodeFromDB';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = new hst_Entities_Ticket_Category();

		$result->TicketCategoryId = $params->TicketCategoryId;
		$result->TicketCategoryDescription = $params->TicketCategoryDescription;

		return $result;

	}

}

?>