<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//registering ajax calls
add_action( 'wp_ajax_hst_ajax_installwizard_frontpage_continue', 'hst_ajax_installwizard_frontpage_continue' );
add_action( 'wp_ajax_hst_ajax_installwizard_notifications_continue', 'hst_ajax_installwizard_notifications_continue' );
add_action( 'wp_ajax_hst_ajax_installwizard_attachments_continue', 'hst_ajax_installwizard_attachments_continue' );
add_action( 'wp_ajax_hst_ajax_installwizard_thankyou_continue', 'hst_ajax_installwizard_thankyou_continue' );


//Executes when continuing from FrontPage panel
//Post[pageMode] mandatory, can be "new", "existing", "none"
//Post[pageNewName] the name/title of the new page to be created
//Post[pageExistingId] the id of the page to use as helpdesk
function hst_ajax_installwizard_frontpage_continue()
{

	$methodName = 'hst_ajax_installwizard_frontpage_continue';
	$response = new hst_Entities_AjaxResponse();
	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	if ( isset($_POST["pageMode"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['pageMode'] parameter is missing. Aborting.");
	}
	$postPageMode = sanitize_text_field($_POST["pageMode"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param pageMode: " . hst_Logger_exportVarContent($postPageMode, true));
	if ( isset($_POST["pageNewName"]))
	{
		$postpageNewName = sanitize_text_field($_POST["pageNewName"]);
	}
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param pageNewName: " . hst_Logger_exportVarContent($postpageNewName, true));
	if ( isset($_POST["pageExistingId"]))
	{
		$postpageExistingId = sanitize_text_field($_POST["pageExistingId"]);
	}
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param pageExistingId: " . hst_Logger_exportVarContent($postpageExistingId, true));

	//fields validation
	if ($postPageMode == 'new' && $postpageNewName == "")
	{
		hst_Common_ReturnAjaxValidationMessage($methodName, "Please type a Title for the new Page.", "hst-installationwizard-panel-frontpage-txt-newpagename");
	}
	if ($postPageMode == 'existing' && ( $postpageExistingId == "" || $postpageExistingId == "0") )
	{
		hst_Common_ReturnAjaxValidationMessage($methodName, "Please select the page you would like to use as Helpdesk Front End.", "hst-installationwizard-panel-frontpage-dd-page");
	}

	//building response
	$response->success = 1;
	$response->data = null;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}


//Executes when continuing from Notifications panel
//Post[helpdeskEmailAddress] mandatory
function hst_ajax_installwizard_notifications_continue()
{

	$methodName = 'hst_ajax_installwizard_notifications_continue';
	$response = new hst_Entities_AjaxResponse();
	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	if ( isset($_POST["helpdeskEmailAddress"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['helpdeskEmailAddress'] parameter is missing. Aborting.");
	}
	$posthelpdeskEmailAddress = sanitize_text_field($_POST["helpdeskEmailAddress"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param helpdeskEmailAddress: " . hst_Logger_exportVarContent($posthelpdeskEmailAddress, true));

	//fields validation
	if ($posthelpdeskEmailAddress == '' )
	{
		hst_Common_ReturnAjaxValidationMessage($methodName, "Please type an email address for the Helpdesk.", "hst-installationwizard-panel-notifications-txt-emailaddress");
	}
	if ( is_email($posthelpdeskEmailAddress) == false )
	{
		hst_Common_ReturnAjaxValidationMessage($methodName, "The email address you added seems to be invalid.", "hst-installationwizard-panel-notifications-txt-emailaddress");
	}

	//building response
	$response->success = 1;
	$response->data = null;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}


//Executes when continuing from Attachments panel
//Post[enableAttachments] mandatory
//Post[allowedExtensions]
function hst_ajax_installwizard_attachments_continue()
{

	$methodName = 'hst_ajax_installwizard_attachments_continue';
	$response = new hst_Entities_AjaxResponse();
	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	if ( isset($_POST["enableAttachments"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['enableAttachments'] parameter is missing. Aborting.");
	}
	$postenableAttachments = sanitize_text_field($_POST["enableAttachments"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param enableAttachments: " . hst_Logger_exportVarContent($postenableAttachments, true));
	if ( isset($_POST["allowedExtensions"]))
	{
		$postallowedExtensions = sanitize_text_field($_POST["allowedExtensions"]);
	}
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param allowedExtensions: " . hst_Logger_exportVarContent($postallowedExtensions, true));

	//fields validation
	if ( $postenableAttachments == "1" && $postallowedExtensions == "" )
	{
		hst_Common_ReturnAjaxValidationMessage($methodName, "Please type the file extensions you would like to allow as attachments.", "hst-installationwizard-panel-attachments-txt-extensions");
	}

	//building response
	$response->success = 1;
	$response->data = null;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}


//Post[pageMode]
//Post[pageNewName]
//Post[pageExistingId]
//Post[helpdeskEmailAddress]
//Post[enableAttachments]
//Post[allowedExtensions]
function hst_ajax_installwizard_thankyou_continue()
{

	$methodName = 'hst_ajax_installwizard_thankyou_continue';
	$response = new hst_Entities_AjaxResponse();
	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	if ( isset($_POST["pageMode"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['pageMode'] parameter is missing. Aborting.");
	}
	$postPageMode = sanitize_text_field($_POST["pageMode"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param pageMode: " . hst_Logger_exportVarContent($postPageMode, true));
	if ( isset($_POST["pageNewName"]))
	{
		$postpageNewName = sanitize_text_field($_POST["pageNewName"]);
	}
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param pageNewName: " . hst_Logger_exportVarContent($postpageNewName, true));
	if ( isset($_POST["pageExistingId"]))
	{
		$postpageExistingId = sanitize_text_field($_POST["pageExistingId"]);
	}
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param pageExistingId: " . hst_Logger_exportVarContent($postpageExistingId, true));
	if ( isset($_POST["helpdeskEmailAddress"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['helpdeskEmailAddress'] parameter is missing. Aborting.");
	}
	$posthelpdeskEmailAddress = sanitize_text_field($_POST["helpdeskEmailAddress"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param helpdeskEmailAddress: " . hst_Logger_exportVarContent($posthelpdeskEmailAddress, true));
	if ( isset($_POST["enableAttachments"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['enableAttachments'] parameter is missing. Aborting.");
	}
	$postenableAttachments = sanitize_text_field($_POST["enableAttachments"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param enableAttachments: " . hst_Logger_exportVarContent($postenableAttachments, true));
	if ( isset($_POST["allowedExtensions"]))
	{
		$postallowedExtensions = sanitize_text_field($_POST["allowedExtensions"]);
	}
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param allowedExtensions: " . hst_Logger_exportVarContent($postallowedExtensions, true));


	//sets the settings values
	if ( $postPageMode == "existing" )
	{
		update_option( hst_consts_optionKeyFrontEndPageId, $postpageExistingId );
	}
	if ( $postPageMode == "new" )
	{

		// Create new page
		$my_post = array(
		  'post_title'    => wp_strip_all_tags( $postpageNewName ),
		  'post_content'  => "[helpdesk-support-tickets]",
		  'post_status'   => 'publish',
		  'post_author'   => get_current_user_id(),
		  'post_type'	  => 'page'

		);

		// Insert the post into the database
		$newHelpdeskPage_id = wp_insert_post( $my_post );

		//saves into settings
		update_option( hst_consts_optionKeyFrontEndPageId, $newHelpdeskPage_id );

	}

	update_option( hst_consts_optionKeyHelpdeskEmailAddress, $posthelpdeskEmailAddress );
	update_option( hst_consts_optionKeyHelpdeskAttachmentsAllowed, $postenableAttachments );
	update_option( hst_consts_optionKeyHelpdeskAttachmentsExtensions, $postallowedExtensions );

	//default options set by system during installation
	update_option( hst_consts_optionKeyHelpdeskDefaultAgentUserId, get_current_user_id() );
	update_option( hst_consts_optionKeyHelpdeskEmailSenderName, "Helpdesk" );
	update_option( hst_consts_optionKeyFrontEndAllowedUsersType, "registered" );

	//setting wizard as finished
	update_option( hst_consts_optionInstallWizardCompleted, "1" );

	//building response
	$response->success = 1;
	$response->data = null;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}

?>