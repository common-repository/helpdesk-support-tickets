<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//registering ajax calls
add_action( 'wp_ajax_hst_ajax_dashboard_customers_get', 'hst_ajax_dashboard_customers_get' );
add_action( 'wp_ajax_hst_ajax_dashboard_customers_list', 'hst_ajax_dashboard_customers_list' );


//Gets a single customer by its id
//sPOST[CustomerId] is a mandatory param
function hst_ajax_dashboard_customers_get()
{

	$methodName = 'wp_ajax_hst_ajax_dashboard_customers_get';
	$response = new hst_Entities_AjaxResponse();

	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	//checking for mandatory input params
	if ( isset($_POST["CustomerId"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['CustomerId'] parameter is missing. Aborting.");
	}
	$postCustomerId = sanitize_text_field($_POST["CustomerId"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param CustomerId: " . hst_Logger_exportVarContent($postCustomerId, true));

	//retrieve a single customer by its id
	$classCustomer = new hst_Classes_Customer();
	$paramsGetCustomer = array();
	$paramsGetCustomer["CustomerId"]=$postCustomerId;
	$entityCustomer = $classCustomer->GetFromDB($paramsGetCustomer);

	if ( is_wp_error( $entityCustomer ) )
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "An error has occurred.");
	}

	//building response
	$response->success = 1;
	$response->data = $entityCustomer;


	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}


//Load customers
function hst_ajax_dashboard_customers_list()
{

	$methodName = 'hst_ajax_dashboard_customers_list';
	$response = new hst_Entities_AjaxResponse();
	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	//calling service to get customers list
	$classCustomers = new hst_Classes_Customer();
	$listCustomers = $classCustomers->ListFromDB(null);

	if ( is_wp_error($listCustomers) )
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "An error has occurred.");
	}

	//building response
	$response->success = 1;
	$response->data = $listCustomers;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}

?>