<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//Class that handles attachments
class hst_Classes_Attachments
{


	//This function creates a new attachment
	//params["EntityId"] mandatory
	//params["EntityType"] mandatory
	//params["AttachmentUrl"] mandatory
	//params["AttachmentPath"] mandatory
	//params["AttachmentSize"] mandatory
	//params["AttachmentFilename"] mandatory
	//params["AttachmentUploadUserId"] mandatory
	//params["AttachmentUploadUserType"] mandatory
	function Create( $params )
	{

		$methodName = 'hst_Classes_Attachments.Create';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		//checking for mandatory input params
		if ( isset($params["EntityId"]) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "params['EntityId'] parameter is missing. Aborting." );
		}
		if ( isset($params["EntityType"]) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "params['EntityType'] parameter is missing. Aborting." );
		}
		if ( isset($params["AttachmentUrl"]) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "params['AttachmentUrl'] parameter is missing. Aborting." );
		}
		if ( isset($params["AttachmentPath"]) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "params['AttachmentPath'] parameter is missing. Aborting." );
		}
		if ( isset($params["AttachmentSize"]) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "params['AttachmentSize'] parameter is missing. Aborting." );
		}
		if ( isset($params["AttachmentFilename"]) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "params['AttachmentFilename'] parameter is missing. Aborting." );
		}
		if ( isset($params["AttachmentUploadUserId"]) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "params['AttachmentUploadUserId'] parameter is missing. Aborting." );
		}
		if ( isset($params["AttachmentUploadUserType"]) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "params['AttachmentUploadUserType'] parameter is missing. Aborting." );
		}

		global $wpdb;

		$query = "INSERT INTO " . hst_consts_dbObjectsTableNamesAttachments . " ";
		$query .= " ( ";
		$query .= " EntityId, ";
		$query .= " EntityType, ";
		$query .= " AttachmentUrl, ";
		$query .= " AttachmentPath, ";
		$query .= " AttachmentSize, ";
		$query .= " AttachmentFilename, ";
		$query .= " AttachmentUploadUserId, ";
		$query .= " AttachmentUploadUserType ";
		$query .= " ) ";

		$query .= " VALUES ";

		$query .= " ( ";
		$query .= " %d, ";								//EntityId
		$query .= " %s, ";								//EntityType
		$query .= " %s, ";								//AttachmentUrl
		$query .= " %s, ";								//AttachmentPath
		$query .= " %d, ";								//AttachmentSize
		$query .= " %s, ";								//AttachmentFilename
		$query .= " %d, ";								//AttachmentUploadUserId
		$query .= " %s ";								//AttachmentUploadUserType
		$query .= " ) ";

		$query = $wpdb->prepare(
			$query,
			$params["EntityId"],
			$params["EntityType"],
			$params["AttachmentUrl"],
			$params["AttachmentPath"],
			$params["AttachmentSize"],
			$params["AttachmentFilename"],
			$params["AttachmentUploadUserId"],
			$params["AttachmentUploadUserType"]
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

		if ( $params["EntityType"] == "ticket" )
		{
			hst_Logger_AddEntry( 'DEBUG', $methodName, "Begin update ticket last update date");
			$paramsUpdateLastUpdateDate = array();
			$paramsUpdateLastUpdateDate["TicketId"] = $params["EntityId"];
			$classTicket = new hst_Classes_Ticket();
			$classTicket->UpdateLastUpdateDate($paramsUpdateLastUpdateDate);
			hst_Logger_AddEntry( 'DEBUG', $methodName, "Finished update ticket last update date");
		}

		hst_Logger_AddEntry( 'DEBUG', $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry( 'FUNCTION', $methodName,"End");

		return $result;

	}


	//Get a single attachment by its id
	//params["AttachmentId"] mandatory
	function GetFromDB( $params )
	{

		$methodName = 'hst_Classes_Attachments.GetFromDB';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		//checking for mandatory input params
		if ( isset(  $params["AttachmentId"] ) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'AttachmentId' value is missing. Aborting." );
		}

		global $wpdb;

		//building query text
		$query = hst_consts_dbSelectsAttachments . " WHERE A.AttachmentId=%d ";
		hst_Logger_AddEntry("DEBUG", $methodName, "Query Text: " . $query);

		//firing query
		$query = $wpdb->prepare( $query, $params["AttachmentId"] );
		$dbrows = $wpdb->get_row( $query , OBJECT );

		if ( $dbrows != false )
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


	//Lists all attachments available from the db table, related to an entity
	//params["EntityId"] mandatory
	//params["EntityType"] mandatory
	function ListByEntityTypeAndIdFromDB( $params )
	{

		$methodName = 'hst_Classes_Attachments.ListByEntityTypeAndIdFromDB';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		global $wpdb;

		//building query text
		$query = hst_consts_dbSelectsAttachments . " WHERE A.EntityType=%s AND A.EntityId=%d ";
		hst_Logger_AddEntry("DEBUG", $methodName, "Query Text: " . $query);

		//firing query
		$query = $wpdb->prepare( $query, $params["EntityType"], $params["EntityId"] );
		$dbrows = $wpdb->get_results( $query , OBJECT );

		if ( $wpdb->last_error == '' )
		{

			hst_Logger_AddEntry("DEBUG", $methodName, "Query finished correctly");

			$result = array();

			//filling an array and returning only the needed content
			foreach( $dbrows as $row )
			{

				$newCustomerEntity = $this->TranscodeFromDB( $row );
				array_push($result, $newCustomerEntity);

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


	//Deletes all attachments by this entity type and entity id combination
	//params["EntityId"] mandatory
	//params["EntityType"] mandatory
	function DeleteByEntityTypeAndEntityId( $params )
	{

		$methodName = 'hst_Classes_Attachments.DeleteByEntityTypeAndEntityId';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		global $wpdb;

		//building query text
		$query = " DELETE FROM " . hst_consts_dbObjectsTableNamesAttachments . " WHERE EntityType=%s AND EntityId=%d ";
		hst_Logger_AddEntry("DEBUG", $methodName, "Query Text: " . $query);

		//firing query
		$query = $wpdb->prepare( $query, $params["EntityType"], $params["EntityId"] );
		$dbrows = $wpdb->query( $query , OBJECT );

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


	//This function transcode a record from the db into an entity
	function TranscodeFromDB( $params )
	{

		$methodName = 'hst_Classes_Attachments.TranscodeFromDB';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = new hst_Entities_Attachment();

		$result->AttachmentId = $params->AttachmentId;
		$result->EntityId = $params->EntityId;
		$result->EntityType = $params->EntityType;
		$result->AttachmentUrl = $params->AttachmentUrl;
		$result->AttachmentPath = $params->AttachmentPath;
		$result->AttachmentSize = $params->AttachmentSize;
		$result->AttachmentFilename = $params->AttachmentFilename;
		$result->AttachmentUploadUserId = $params->AttachmentUploadUserId;
		$result->AttachmentUploadUserType = $params->AttachmentUploadUserType;
		$result->AttachmentCreatedDate = $params->AttachmentCreatedDate;

		$result->AttachmentUploadUserDisplayName = $params->AttachmentUploadUserDisplayName;

		$result->AttachmentCreatedDateText = hst_Common_ReturnNiceDate($result->AttachmentCreatedDate);

		hst_Logger_AddEntry("DEBUG", $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry("FUNCTION", $methodName,"End");

		return $result;

	}



}

?>