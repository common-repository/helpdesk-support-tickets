<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//Class that handles users
class hst_Classes_User
{


	//Get a single user by its id
	//params["UserId"] mandatory
	function GetFromDB( $params )
	{

		$methodName = 'hst_Classes_User.GetFromDB';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		//checking for mandatory input params
		if ( isset(  $params["UserId"] ) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'UserId' value is missing. Aborting." );
		}

		global $wpdb;

		//building query text
		$query = hst_consts_dbSelectsUsers . " WHERE U.ID=%d ";
		hst_Logger_AddEntry("DEBUG", $methodName, "Query Text: " . $query);

		//firing query
		$query = $wpdb->prepare( $query, $params["UserId"] );
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


	//Lists all users for the website
	function ListFromDB( $params )
	{

		$methodName = 'hst_Classes_User.ListFromDB';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		global $wpdb;

		//building query text
		$query = hst_consts_dbSelectsUsers;
		$query .= " ORDER BY display_name";
		hst_Logger_AddEntry("DEBUG", $methodName, "Query Text: " . $query);

		//firing query
		$dbrows = $wpdb->get_results( $query , OBJECT );

		if ( $wpdb->last_error == '' )
		{

			$result = array();

			//filling an array and returning only the needed content
			foreach( $dbrows as $row )
			{

				$newUserEntity = $this->TranscodeFromDB( $row );
				array_push($result, $newUserEntity);

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


	//This function transcode a record from the db into an entity
	function TranscodeFromDB( $params )
	{

		$methodName = 'hst_Classes_User.TranscodeFromDB';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = new hst_Entities_User();

		$result->UserId = $params->ID;
		$result->UserDisplayName = $params->display_name;
		$result->UserEmail = $params->user_email;

		hst_Logger_AddEntry("DEBUG", $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry("FUNCTION", $methodName,"End");

		return $result;

	}


}

?>