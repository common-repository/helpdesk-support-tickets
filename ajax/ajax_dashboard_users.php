<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//registering ajax calls
add_action( 'wp_ajax_hst_ajax_dashboard_users_list', 'hst_ajax_dashboard_users_list' );
add_action( 'wp_ajax_hst_ajax_dashboard_users_get', 'hst_ajax_dashboard_users_get' );


//Gets a single user by its id
//sPOST[UserId] is a mandatory param
function hst_ajax_dashboard_users_get()
{

	$methodName = 'hst_ajax_dashboard_users_get';
	$response = new hst_Entities_AjaxResponse();

	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	//checking for mandatory input params
	if ( isset($_POST["UserId"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['UserId'] parameter is missing. Aborting.");
	}
	$postUserId = sanitize_text_field($_POST["UserId"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param UserId: " . hst_Logger_exportVarContent($postUserId, true));

	//retrieve a single user by its id
	$classUser = new hst_Classes_User();
	$paramsGetUser = array();
	$paramsGetUser["UserId"]=$postUserId;
	$entityUser = $classUser->GetFromDB($paramsGetUser);

	if ( is_wp_error( $entityUser ) )
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "An error has occurred.");
	}

	//building response
	$response->success = 1;
	$response->data = $entityUser;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}

//Load users for the website
function hst_ajax_dashboard_users_list()
{

	$methodName = 'hst_ajax_dashboard_users_list';
	$response = new hst_Entities_AjaxResponse();
	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	//calling service to get users list
	$classUsers = new hst_Classes_User();
	$listUsers = $classUsers->ListFromDB(null);

	if ( is_wp_error($listUsers) )
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "An error has occurred.");
	}

	//building response
	$response->success = 1;
	$response->data = $listUsers;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}

?>