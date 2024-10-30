<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//registering ajax calls
add_action( 'wp_ajax_hst_ajax_dashboard_settings_ticket_categories_list', 'hst_ajax_dashboard_settings_ticket_categories_list' );
add_action( 'wp_ajax_hst_ajax_dashboard_settings_ticket_categories_get', 'hst_ajax_dashboard_settings_ticket_categories_get' );
add_action( 'wp_ajax_hst_ajax_dashboard_settings_ticket_categories_update', 'hst_ajax_dashboard_settings_ticket_categories_update' );
add_action( 'wp_ajax_hst_ajax_dashboard_settings_ticket_categories_delete', 'hst_ajax_dashboard_settings_ticket_categories_delete' );



//Load the list of categories
function hst_ajax_dashboard_settings_ticket_categories_list()
{

	$methodName = 'hst_ajax_dashboard_settings_ticket_categories_list';
	$response = new hst_Entities_AjaxResponse();

	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	//retrieve tickets categories
	$classTicketCategory = new hst_Classes_Ticket_Category();
	$listTicketCategories = $classTicketCategory->ListFromDB(null);

	if ( is_wp_error( $listTicketCategories ) )
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "An error has occurred.");
	}

	//building response
	$response->success = 1;
	$response->data = $listTicketCategories;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}



//Get a single category
//Post[TicketCategoryId] is mandatory
function hst_ajax_dashboard_settings_ticket_categories_get()
{

	$methodName = 'hst_ajax_dashboard_settings_ticket_categories_get';
	$response = new hst_Entities_AjaxResponse();

	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	//checking for mandatory input params
	if ( isset($_POST["TicketCategoryId"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['TicketCategoryId'] parameter is missing. Aborting.");
	}
	$postParamsTicketCategoryId = sanitize_text_field($_POST["TicketCategoryId"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param TicketCategoryId: " . hst_Logger_exportVarContent($postParamsTicketCategoryId, true));

	//retrieve a single category by its id
	$classTicketCategory = new hst_Classes_Ticket_Category();
	$paramsGetTicketCategory = array();
	$paramsGetTicketCategory["TicketCategoryId"]=$postParamsTicketCategoryId;
	$entityTicketCategory = $classTicketCategory->GetFromDB($paramsGetTicketCategory);

	if ( is_wp_error( $entityTicketCategory ) )
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "An error has occurred.");
	}

	//building response
	$response->success = 1;
	$response->data = $entityTicketCategory;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}



//Delete a single category
//Post[TicketCategoryId] is mandatory
function hst_ajax_dashboard_settings_ticket_categories_delete()
{

	$methodName = 'hst_ajax_dashboard_settings_ticket_categories_delete';
	$response = new hst_Entities_AjaxResponse();

	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	//checking for mandatory input params
	if ( isset($_POST["TicketCategoryId"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['TicketCategoryId'] parameter is missing. Aborting.");
	}
	$postParamsTicketCategoryId = sanitize_text_field($_POST["TicketCategoryId"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param TicketCategoryId: " . hst_Logger_exportVarContent($postParamsTicketCategoryId, true));

	//delete
	$classTicketCategory = new hst_Classes_Ticket_Category();
	$paramsGetTicketCategory = array();
	$paramsGetTicketCategory["TicketCategoryId"]=$postParamsTicketCategoryId;
	$entityTicketCategory = $classTicketCategory->DeleteFromDB($paramsGetTicketCategory);

	if ( is_wp_error( $entityTicketCategory ) )
	{
		hst_Common_ReturnAjaxError($methodName, "generic",  "An error has occurred.");
	}

	//building response
	$response->success = 1;
	$response->data = null;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}



//Updates a single category (insert or add)
//Post[TicketCategoryId] is mandatory. Pass 0 to insert, >0 to update and existing category
//Post[TicketCategoryDescription] is mandatory
function hst_ajax_dashboard_settings_ticket_categories_update()
{

	$methodName = 'hst_ajax_dashboard_settings_ticket_categories_update';
	$response = new hst_Entities_AjaxResponse();

	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	//checking for mandatory input params
	if ( isset($_POST["TicketCategoryId"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['TicketCategoryId'] parameter is missing. Aborting.");
	}
	$postParamsTicketCategoryId = sanitize_text_field($_POST["TicketCategoryId"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param TicketCategoryId: " . hst_Logger_exportVarContent($postParamsTicketCategoryId, true));
	if ( isset($_POST["TicketCategoryDescription"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['TicketCategoryDescription'] parameter is missing. Aborting.");
	}
	$postParamsTicketCategoryDescription = sanitize_text_field($_POST["TicketCategoryDescription"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param TicketCategoryDescription: " . hst_Logger_exportVarContent($postParamsTicketCategoryDescription, true));

	//fields validation
	if ($postParamsTicketCategoryDescription == "")
	{
		hst_Common_ReturnAjaxValidationMessage($methodName, "Please type the Description for the Category.", "hst-controls-settings-ticket-categories-editPanel-txtName");
	}

	//retrieve a single category by its id
	$classTicketCategory = new hst_Classes_Ticket_Category();
	$paramsTicketCategoryEntity = new hst_Entities_Ticket_Category();
	$paramsTicketCategoryEntity->TicketCategoryId = $postParamsTicketCategoryId;
	$paramsTicketCategoryEntity->TicketCategoryDescription = $postParamsTicketCategoryDescription;

	//update or insert?
	if ( $postParamsTicketCategoryId > 0 )
	{

		//updates
		$functionResult = $classTicketCategory->UpdateCategoryInDB($paramsTicketCategoryEntity);

		if ( is_wp_error( $functionResult ) )
		{
			hst_Common_ReturnAjaxError($methodName, "generic", "An error has occurred.");
		}

	}
	else
	{

		//insert
		$functionResult = $classTicketCategory->InsertCategoryInDB($paramsTicketCategoryEntity);

		if ( is_wp_error( $functionResult ) )
		{
			hst_Common_ReturnAjaxError($methodName, "generic", "An error has occurred.");
		}

	}

	//building response
	$response->success = 1;
	$response->data = null;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}
?>