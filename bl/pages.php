<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//Called from main plugin file, it will create the page in Wordpress admin dashboard
function hst_Pages_AddAdminPage()
{

    //The capability that the user must have in order to access the page
	$capabilityName = 'manage_options';

	//Generates page
	if ( get_option( hst_consts_optionInstallWizardCompleted, "0") == "0" )
	{

		//points to the install wizard page
		$generatedPage = add_menu_page( 'Helpdesk', 'Helpdesk', $capabilityName, 'hst', 'hst_Pages_AddInstallWizardPage_callback', hst_consts_pluginUrl . '/images/wp-pluginlogo.png', 74 );

	}
	else
	{

		//points to the normal dashboard page
		$generatedPage = add_menu_page( 'Helpdesk', 'Helpdesk', $capabilityName, 'hst', 'hst_Pages_AddAdminPage_callback', hst_consts_pluginUrl . '/images/wp-pluginlogo.png', 74 );

	}

	//Adds the page and calls the function to include client resources needed
	add_action( 'load-' . $generatedPage, 'hst_ClientResources_InjectDashboardResources' );

}
function hst_Pages_AddAdminPage_callback() {

	//Including the page
	include( hst_consts_pluginPath . "/pages/dashboard.php" );

}
function hst_Pages_AddInstallWizardPage_callback() {

	//Including the page
	include( hst_consts_pluginPath . "/pages/installationwizard.php" );

}

?>