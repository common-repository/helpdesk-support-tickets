<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//Class that handles customers
class hst_Classes_Customer
{

	//This function creates a customer (wp user)
	//params["CustomerDisplayname"] mandatory
	//params["CustomerEmail"] mandatory
	function Create( $params )
	{

		$methodName = 'hst_Classes_Customer.Create';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		//checking for mandatory input params
		if ( isset($params["CustomerDisplayName"]) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "params['CustomerDisplayname'] parameter is missing. Aborting." );
		}
		if ( isset($params["CustomerEmail"]) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "params['CustomerEmail'] parameter is missing. Aborting." );
		}

		//prepares user settings
		$randomPwd = wp_generate_password();
		$userdata = array(
			'user_login'		=>  $params["CustomerEmail"],
			'user_nicename'     =>  $params["CustomerDisplayName"],
			'display_name'		=>  $params["CustomerDisplayName"],
			'user_email'		=>  $params["CustomerEmail"],
			'user_pass'			=>  $randomPwd
		);

		//excutes
		$userId = wp_insert_user( $userdata ) ;

		// On success.
		if ( ! is_wp_error( $userId ) )
		{

			hst_Logger_AddEntry( 'DEBUG', $methodName, 'User ID created: ' . $userId );
			$result = $userId;

		}
		else
		{
			return hst_Common_ReturnFunctionError( $methodName, $userId->get_error_message );
		}

		hst_Logger_AddEntry("DEBUG", $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry("FUNCTION", $methodName,"End");

		return $result;

	}


	//Get a single customer (user in wp) by its id
	//params["CustomerId"] mandatory
	function GetFromDB( $params )
	{

		$methodName = 'hst_Classes_Customer.GetFromDB';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		//checking for mandatory input params
		if ( isset(  $params["CustomerId"] ) == false)
		{
			return hst_Common_ReturnFunctionError( $methodName, "'CustomerId' value is missing. Aborting." );
		}

		global $wpdb;

		//building query text
		$query = hst_consts_dbSelectsCustomers . " WHERE C.ID=%d ";
		hst_Logger_AddEntry("DEBUG", $methodName, "Query Text: " . $query);

		//firing query
		$query = $wpdb->prepare( $query, $params["CustomerId"] );
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


	//Lists all tickets statuses available from the db table
	function ListFromDB( $params )
	{

		$methodName = 'hst_Classes_Customer.ListFromDB';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = false;

		global $wpdb;

		//building query text
		$query = hst_consts_dbSelectsCustomers;
		hst_Logger_AddEntry("DEBUG", $methodName, "Query Text: " . $query);

		//firing query
		$dbrows = $wpdb->get_results( $query , OBJECT );

		if ( $wpdb->last_error == '' )
		{

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


	//This function transcode a record from the db into an entity
	function TranscodeFromDB( $params )
	{

		$methodName = 'hst_Classes_Customer.TranscodeFromDB';
		hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
		hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));
		$result = new hst_Entities_Customer();

		$result->CustomerId = $params->ID;
		$result->CustomerDisplayName = $params->display_name;
		$result->CustomerEmail = $params->user_email;
		$result->CustomerAvatar = get_avatar( $result->CustomerId, 32 );

		hst_Logger_AddEntry("DEBUG", $methodName, "Returning content: " . hst_Logger_exportVarContent($result, true));
		hst_Logger_AddEntry("FUNCTION", $methodName,"End");

		return $result;

	}


}

?>