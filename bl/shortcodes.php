<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//Called to trasnalte the [helpdesk-support-tickets] shortcode
function hst_ShortCodes_helpdesksupporttickets()
{

	ob_start();
	include( hst_consts_pluginPath . "/pages/frontend.php" );
	$content = ob_get_clean();
	return $content;

}



?>