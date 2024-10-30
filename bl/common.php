<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//returns a date in a human friendly format
function hst_Common_ReturnNiceDate( $date )
{

	$methodName = 'hst_Common_ReturnNiceDate';
	$result = "";

	hst_Logger_AddEntry( 'DEBUG', $methodName, "Received date: " . $date );

	if ( is_null($date) )
	{
		$result =  "";
	}
	else
	{

		$dateToFormat = new DateTime( $date );
		$result = $dateToFormat->format('M-d-Y H:i');

	}

	hst_Logger_AddEntry( 'DEBUG', $methodName, "Returning date: " . $result );
	return $result;

}


//Returns a "x time" ago label, for a given date
function hst_Common_DateReturnHumanDifference( $datetime, $full = false )
{

	$methodName = 'hst_Common_DateReturnHumanDifference';
	$result = "";

	$now = new DateTime( current_time( 'mysql' ) );
	$ago = new DateTime($datetime);
	$diff = $now->diff($ago);

	$diff->w = floor($diff->d / 7);
	$diff->d -= $diff->w * 7;

	$string = array(
		'y' => 'year',
		'm' => 'month',
		'w' => 'week',
		'd' => 'day',
		'h' => 'hour',
		'i' => 'minute',
		's' => 'second',
	);
	foreach ($string as $k => &$v) {
		if ($diff->$k) {
			$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
		} else {
			unset($string[$k]);
		}
	}

	if (!$full) $string = array_slice($string, 0, 1);
	$result = $string ? implode(', ', $string) . ' ago' : 'just now';

	return $result;

}


//send email
//$emailparams["emailFrom"] mandatory, the email address used as sender
//$emailparams["emailTo"] = mandatory, the email address used as recipient
//$emailparams["emailSubject"] = mandatory, the subject of the email
//$emailparams["emailBody"] = mandatory, the body of the email
function hst_Common_SendEmail( $params )
{

	$methodName = 'hst_Common_SendEmail';
	$result = false;
	$paramsEmailFrom = "";
	$paramsEmailTo = "";
	$paramsSubject = "";
	$paramsBody = "";

	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));

	//checking for mandatory input params
	if ( isset($params["emailFrom"]) == false)
	{
		hst_Logger_AddEntry( 'ERROR', $methodName, "'emailFrom' parameter is missing. Aborting." );
		return false;
	}
	if ( isset($params["emailTo"]) == false)
	{
		hst_Logger_AddEntry( 'ERROR', $methodName, "'emailTo' parameter is missing. Aborting." );
		return false;
	}
	if ( isset($params["emailSubject"]) == false)
	{
		hst_Logger_AddEntry( 'ERROR', $methodName, "'emailSubject' parameter is missing. Aborting." );
		return false;
	}
	if ( isset($params["emailBody"]) == false)
	{
		hst_Logger_AddEntry( 'ERROR', $methodName, "'emailBody' parameter is missing. Aborting." );
		return false;
	}

	$paramsEmailFrom = $params["emailFrom"];
	$paramsEmailTo = $params["emailTo"];
	$paramsSubject = $params["emailSubject"];
	$paramsBody = $params["emailBody"];
	$paramsEmailFromDescriptionText = get_option( hst_consts_optionKeyHelpdeskEmailSenderName , "Helpdesk");

	//logs received params
	hst_Logger_AddEntry( 'DEBUG', $methodName, "'emailFrom' parameter: " . $paramsEmailFrom );
	hst_Logger_AddEntry( 'DEBUG', $methodName, "'emailTo' parameter: " . $paramsEmailTo  );
	hst_Logger_AddEntry( 'DEBUG', $methodName, "'emailSubject' parameter: " . $paramsSubject );
	hst_Logger_AddEntry( 'DEBUG', $methodName, "'emailBody' parameter: " . $paramsBody );
	hst_Logger_AddEntry( 'DEBUG', $methodName, "'emailHeaderSernderName' parameter: " . $paramsEmailFromDescriptionText );

	//adding headers
	$emailHeaders = array( 'From: ' . $paramsEmailFromDescriptionText . ' <' . $paramsEmailFrom . '>' . "\r\n", 'Content-Type: text/html; charset=UTF-8' );
	hst_Logger_AddEntry( 'DEBUG', $methodName, "'emailHeaderComplete' parameter: " . hst_Logger_exportVarContent($emailHeaders,true) );

	//sending email
	$sendEmailResult = wp_mail( $paramsEmailFrom, $paramsSubject, $paramsBody, $emailHeaders );

	hst_Logger_AddEntry( 'DEBUG', $methodName, "Email send result: " . hst_Logger_exportVarContent($sendEmailResult, true) );

	$result = $sendEmailResult;

	return $result;

}


//will return the current page url, together with full querystring
function hst_Common_GetCurrentUrl( $params )
{

	$methodName = 'hst_Common_GetCurrentUrl';
	$result = "";

	$uri = $_SERVER['REQUEST_URI'];
	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$result = $url;

	hst_Logger_AddEntry( 'DEBUG', $methodName, "Result: " . hst_Logger_exportVarContent($result, true) );
	return $result;

}


//will return the full path to plugin url in dashboard
function hst_Common_GetDashboardPluginURL( $params )
{

	$methodName = 'hst_Common_GetDashboardPluginURL';
	$result = "";

	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "Params values: " . hst_Logger_exportVarContent($params, true));

	$dashboard = admin_url();
	$result = $dashboard . "?page=hst";

	hst_Logger_AddEntry( 'DEBUG', $methodName, "Result: " . hst_Logger_exportVarContent($result, true) );
	return $result;

}


//when there is an error in some function, will return an error object based on WP_Error class
//do not enclose this function in try catches
function hst_Common_ReturnFunctionError( $methodName, $errorMessage)
{

	$result = new WP_Error( $methodName, $errorMessage);

	hst_Logger_AddEntry( 'ERROR', $methodName, $errorMessage );

	hst_Logger_AddEntry("DEBUG", $methodName, "Returning Error object as content: " . hst_Logger_exportVarContent($result, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	return $result;

}


//returns an ajax error, that will be used in wp_send_json as response
//errorCode can be: "generic", "missingparam"
function hst_Common_ReturnAjaxError( $methodName, $errorCode, $errorMessage )
{

	$result = new hst_Entities_AjaxResponse();

	hst_Logger_AddEntry("ERROR", $methodName, $errorMessage);

	$result->success = 0;
	$result->errorCode = $errorCode;
	$result->errorMessage = $errorMessage;

	wp_send_json($result);

}



//returns an ajax response, that will inform the user about some input validation
function hst_Common_ReturnAjaxValidationMessage( $methodName, $errorMessage, $inputIdValidationError )
{

	$result = new hst_Entities_AjaxResponse();

	hst_Logger_AddEntry("DEBUG", $methodName, "Validation Failed: " + $errorMessage);

	$result->success = 0;
	$result->errorCode = "inputValidation";
	$result->errorMessage = $errorMessage;
	$result->validationInputId = $inputIdValidationError;

	wp_send_json($result);

}



//called on plugin uninstall
function hst_Common_UninstallPlugin()
{

	//deleting DB table if the option is set to true
	if ( get_option( hst_consts_optionKeyDeleteTablesUninstall , "0") == "1" )
	{

		hst_DbInstall_UnInstallDatabaseObjects();

	}

}


?>