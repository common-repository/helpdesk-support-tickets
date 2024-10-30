<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//registering ajax calls
add_action( 'wp_ajax_hst_ajax_dashboard_shared_loadtablescontent', 'hst_ajax_dashboard_shared_loadtablescontent' );
add_action( 'wp_ajax_hst_ajax_dashboard_shared_uploadattachment', 'hst_ajax_dashboard_shared_uploadattachment' );
add_action( 'wp_ajax_hst_ajax_dashboard_shared_listattachments', 'hst_ajax_dashboard_shared_listattachments' );


//Load tables content, such as priorities list, status list, mostly used to fill dropdowns controls
function hst_ajax_dashboard_shared_loadtablescontent()
{

	$methodName = 'hst_ajax_dashboard_shared_loadtablescontent';
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

	//retrieve tickets status
	$classTicketStatus = new hst_Classes_Ticket_Status();
	$listTicketStatuses = $classTicketStatus->ListFromDB(null);

	if ( is_wp_error( $listTicketStatuses ) )
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "An error has occurred.");
	}

	//retrieve tickets priorities
	$classTicketPriority = new hst_Classes_Ticket_Priority();
	$listTicketPriorities = $classTicketPriority->ListFromDB(null);

	if ( is_wp_error( $listTicketPriorities ) )
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "An error has occurred.");
	}

	$returnContent = array();
	$returnContent["ticketCategories"] = $listTicketCategories;
	$returnContent["ticketStatuses"] = $listTicketStatuses;
	$returnContent["ticketPriorities"] = $listTicketPriorities;

	//building response
	$response->success = 1;
	$response->data = $returnContent;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}


//Upload an attachment
//Post[entityId] is mandatory
//Post[entityType] is mandatory
function hst_ajax_dashboard_shared_uploadattachment()
{

	$methodName = 'hst_ajax_dashboard_shared_uploadattachment';
	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	//checking for mandatory input params
	if ( isset($_POST["entityId"]) == false)
	{
		hst_Logger_AddEntry( 'ERROR', $methodName, "POST['entityId'] parameter is missing. Aborting." );
		echo "<script>parent.hst_tickets_view_fileuploadresponse('error')</script>";
		die;
	}
	$postParamsentityId = sanitize_text_field($_POST["entityId"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param entityId: " . hst_Logger_exportVarContent($postParamsentityId, true));
	if ( isset($_POST["entityType"]) == false)
	{
		hst_Logger_AddEntry( 'ERROR', $methodName, "POST['entityType'] parameter is missing. Aborting." );
		echo "<script>parent.hst_tickets_view_fileuploadresponse('error')</script>";
		die;
	}
	$postParamsentityType = sanitize_text_field($_POST["entityType"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param entityType: " . hst_Logger_exportVarContent($postParamsentityType, true));

	//checking that the user is logged in
	if ( is_user_logged_in() == false)
	{
		hst_Logger_AddEntry( 'ERROR', $methodName, "User is not authenticated." );
		echo "<script>parent.hst_tickets_view_fileuploadresponse('notauthorized')</script>";
		die;
	}

	//check the file was actually uploaded
	hst_Logger_AddEntry("DEBUG", $methodName, "Starting to analyze file.");
	if( empty($_FILES) )
	{
		hst_Logger_AddEntry("ERROR", $methodName, "File not received" );
		echo "<script>parent.hst_tickets_view_fileuploadresponse('nofile')</script>";
		die;
	}
	if( $_FILES["file"]["name"] == "" )
	{
		hst_Logger_AddEntry("ERROR", $methodName, "File not received" );
		echo "<script>parent.hst_tickets_view_fileuploadresponse('nofile')</script>";
		die;
	}

	//reading posted file
	$uploadedfile = $_FILES["file"];

	//getting file name
	$uploaded_filename = $_FILES["file"]["name"];

	//getting extensions allowed
	$allowed_extensions = explode(',', get_option( hst_consts_optionKeyHelpdeskAttachmentsExtensions, "" ) );
	hst_Logger_AddEntry("DEBUG", $methodName, "Allowed extensions by option: " . get_option( hst_consts_optionKeyHelpdeskAttachmentsExtensions, "" ) );

	//checking file extension
	$uploaded_fileextension = pathinfo($uploaded_filename, PATHINFO_EXTENSION);
	if( !in_array($uploaded_fileextension, $allowed_extensions) )
	{
		hst_Logger_AddEntry("ERROR", $methodName, "File extension not allowed" );
		echo "<script>parent.hst_tickets_view_fileuploadresponse('extensionnotallowed')</script>";
	    die;
	}

	hst_Logger_AddEntry("DEBUG", $methodName, "File extension accepted, checking file size" );

	//checking file size
	$allowed_size_bytes = 5000;
	$allowed_size_bytes = $allowed_size_bytes * 1024;
	$uploaded_filesize = $_FILES["file"]["size"];
	if ( $uploaded_filesize > $allowed_size_bytes )
	{
		hst_Logger_AddEntry("ERROR", $methodName, "File size is too large" );
		echo "<script>parent.hst_tickets_view_fileuploadresponse('filetoolarge')</script>";
		die;
	}

	hst_Logger_AddEntry("DEBUG", $methodName, "File size passed, start wp handle upload" );

	//overriding the test WP parameter
	$upload_overrides = array( 'test_form' => false );

	//storing file
	$uploadresult = wp_handle_upload( $uploadedfile, $upload_overrides );

	hst_Logger_AddEntry("DEBUG", $methodName, "Handle upload result: " . hst_Logger_exportVarContent($uploadresult, true) );

	//checks for upload results
	if ( $uploadresult && !isset( $uploadresult['error'] ) )
	{

		//file uploaded succesfully
		hst_Logger_AddEntry("DEBUG", $methodName, "Wp handle upload succesfull, start building table insert params");

		//building parmas for attachment creation in table
		$paramsUpload = array();
		$paramsUpload["EntityId"] = $postParamsentityId;
		$paramsUpload["EntityType"] = $postParamsentityType;
		$paramsUpload["AttachmentUrl"] = $uploadresult["url"];
		$paramsUpload["AttachmentPath"] = $uploadresult["file"];
		$paramsUpload["AttachmentSize"] = $uploaded_filesize;
		$paramsUpload["AttachmentFilename"] = $uploaded_filename;
		$paramsUpload["AttachmentUploadUserId"] = get_current_user_id();
		$paramsUpload["AttachmentUploadUserType"] = 'agent';

		hst_Logger_AddEntry("DEBUG", $methodName, "Params for table insert: " . hst_Logger_exportVarContent($paramsUpload, true) );

		$classAttachments = new hst_Classes_Attachments();
		$insertAttachmentResult = $classAttachments->Create( $paramsUpload );

		if ( is_wp_error( $insertAttachmentResult ) == false )
		{

			hst_Logger_AddEntry("DEBUG", $methodName, "Insert into table succesfull" );

			//will return ok later at the end of the function

		}
		else
		{

			echo "<script>parent.hst_tickets_view_fileuploadresponse('error')</script>";
			die;

		}

	}
	else
	{

		//error
		hst_Logger_AddEntry("ERROR", $methodName, "Wordpress handle upload error: " . $uploadresult['error'] );
		echo "<script>parent.hst_tickets_view_fileuploadresponse('error')</script>";
		die;

	}

	echo "<script>parent.hst_tickets_view_fileuploadresponse('ok')</script>";

}


//List attachments for an entityid and an entitytype
//Post[entityId] is mandatory
//Post[entityType] is mandatory
function hst_ajax_dashboard_shared_listattachments()
{

	$methodName = 'hst_ajax_dashboard_shared_listattachments';
	$response = new hst_Entities_AjaxResponse();
	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	//checking for mandatory input params
	if ( isset($_POST["entityId"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['entityId'] parameter is missing. Aborting.");
	}
	$postParamsentityId = sanitize_text_field($_POST["entityId"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param entityId: " . hst_Logger_exportVarContent($postParamsentityId, true));
	if ( isset($_POST["entityType"]) == false)
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "POST['entityType'] parameter is missing. Aborting.");
	}
	$postParamsentityType = sanitize_text_field($_POST["entityType"]);
	hst_Logger_AddEntry("DEBUG", $methodName, "Sanitized Post Param entityType: " . hst_Logger_exportVarContent($postParamsentityType, true));

	//calling service to get tickets for frontend list
	$classAttachments = new hst_Classes_Attachments();
	$paramsAttachmentsList = array();
	$paramsAttachmentsList["EntityId"] = $postParamsentityId;
	$paramsAttachmentsList["EntityType"] = $postParamsentityType;
	$listAttachments = $classAttachments->ListByEntityTypeAndIdFromDB($paramsAttachmentsList);

	if ( is_wp_error( $listAttachments ) )
	{
		hst_Common_ReturnAjaxError($methodName, "generic", "An error has occurred.");
	}

	//building response
	$response->success = 1;
	$response->data = $listAttachments;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);


}

?>