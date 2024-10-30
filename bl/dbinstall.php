<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//This function will check db objects and update them in case of new version
function hst_DbInstall_InstallDatabaseObjects()
{

	$methodName = 'hst_DbInstall_InstallDatabaseObjects';



	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");

	//reading current db version from the wp options table
	$hst_globals_pluginDatabaseVersion = get_option( hst_consts_optionKeyDBVersion );

	//Checks if the installed DB objects version is different from the plugin version, to avoid this check everytime
	if ( $hst_globals_pluginDatabaseVersion != hst_consts_pluginVersion )
	{

		//The two versions are different, checks for db differences
		hst_Logger_AddEntry( 'INFO', $methodName, 'DB version (' . $hst_globals_pluginDatabaseVersion . ') different from Plugin version (' . hst_consts_pluginVersion . '), begin db delta' );

		//Ensure WP objects are present
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$charsetCollate = $wpdb->get_charset_collate();

		//Begin install table: Tickets

		hst_Logger_AddEntry( 'INFO', $methodName, 'Begin installing database table "Tickets"' );

		$sql = " CREATE TABLE ". hst_consts_dbObjectsTableNamesTickets . " (
				TicketId int(11) NOT NULL AUTO_INCREMENT,
				TicketTitle varchar(50) NOT NULL,
				TicketProblem varchar(1000) NOT NULL,
				TicketCategoryId int(11) DEFAULT NULL,
				TicketPriorityId int(11) DEFAULT NULL,
				TicketStatusId int(11) DEFAULT NULL,
				TicketDateCreated datetime DEFAULT NULL,
				TicketDateClosed datetime DEFAULT NULL,
				TicketDateLastUpdated datetime DEFAULT NULL,
				TicketCreatedUserId int(11) DEFAULT NULL,
				TicketAssignedUserId int(11) DEFAULT NULL,
				TicketCustomerUserId int(11) DEFAULT NULL,
				TicketCustomerUserDisplayName varchar(100) NULL,
				TicketCustomerUserEmail varchar(100) NULL,
				TicketCreationSource varchar(50) NULL,
				PRIMARY KEY  (TicketId)
			) $charset_collate ";

		dbDelta( $sql );
		if( $wpdb->last_error !== '')
		{
			hst_Logger_AddEntry('ERROR', $methodName, $wpdb->last_error );
		}
		else
		{
			hst_Logger_AddEntry('INFO', $methodName, 'Done installing database table "Tickets"' );
		}

		//Begin install table: Tickets Categories

		hst_Logger_AddEntry( 'INFO', $methodName, 'Begin installing database table "Tickets Categories"' );

		$sql = " CREATE TABLE ". hst_consts_dbObjectsTableNamesTicketsCategories . " (
				TicketCategoryId int(11) NOT NULL AUTO_INCREMENT,
				TicketCategoryDescription varchar(200) NOT NULL,
				PRIMARY KEY  (TicketCategoryId)
			) $charset_collate ";

		dbDelta( $sql );
		if( $wpdb->last_error !== '')
		{
			hst_Logger_AddEntry('ERROR', $methodName, $wpdb->last_error );
		}
		else
		{
			hst_Logger_AddEntry('INFO', $methodName, 'Done installing database table "Tickets Categories"' );
		}

		//Begin install table: Tickets Status

		hst_Logger_AddEntry( 'INFO', $methodName, 'Begin installing database table "Tickets Status"' );

		$sql = " CREATE TABLE ". hst_consts_dbObjectsTableNamesTicketsStatuses . " (
				TicketStatusId int(11) NOT NULL AUTO_INCREMENT,
				TicketStatusDescription varchar(200) NOT NULL,
				TicketStatusIsClosed int(11) NOT NULL,
				TicketStatusIsDefaultForNewTickets int(11) DEFAULT NULL,
				TicketStatusBgColor varchar(50) NULL,
				PRIMARY KEY  (TicketStatusId)
			) $charset_collate ";

		dbDelta( $sql );
		if( $wpdb->last_error !== '')
		{
			hst_Logger_AddEntry('ERROR', $methodName, $wpdb->last_error );
		}
		else
		{
			hst_Logger_AddEntry('INFO', $methodName, 'Done installing database table "Tickets Status"' );
		}

		//Begin install table: Tickets Priority

		hst_Logger_AddEntry( 'INFO', $methodName, 'Begin installing database table "Tickets Priority"' );

		$sql = " CREATE TABLE ". hst_consts_dbObjectsTableNamesTicketsPriorities . " (
				TicketPriorityId int(11) NOT NULL AUTO_INCREMENT,
				TicketPriorityDescription varchar(200) NOT NULL,
				TicketPriorityIsDefaultForNewTickets int(11) DEFAULT NULL,
				PRIMARY KEY  (TicketPriorityId)
			) $charset_collate ";

		dbDelta( $sql );
		if( $wpdb->last_error !== '')
		{
			hst_Logger_AddEntry('ERROR', $methodName, $wpdb->last_error );
		}
		else
		{
			hst_Logger_AddEntry('INFO', $methodName, 'Done installing database table "Tickets Priority"' );
		}

		//Begin install table: Tickets Events

		hst_Logger_AddEntry( 'INFO', $methodName, 'Begin installing database table "Tickets Events"' );

		$sql = " CREATE TABLE ". hst_consts_dbObjectsTableNamesTicketsEvents . " (
				TicketEventId int(11) NOT NULL AUTO_INCREMENT,
				TicketId int(11) NOT NULL,
				TicketEventDate datetime NOT NULL,
				TicketEventUserType varchar(30) NOT NULL,
				TicketEventUserId int(11) NOT NULL,
				TicketEventUserDisplayName varchar(100) NOT NULL,
				TicketEventType varchar(30) NOT NULL,
				TicketEventMessageContent text DEFAULT NULL,
				TicketEventUserDataUpdateContent varchar(1000) NULL,
				PRIMARY KEY  (TicketEventId)
			) $charset_collate ";

		dbDelta( $sql );
		if( $wpdb->last_error !== '')
		{
			hst_Logger_AddEntry('ERROR', $methodName, $wpdb->last_error );
		}
		else
		{
			hst_Logger_AddEntry('INFO', $methodName, 'Done installing database table "Tickets Events"' );
		}

		//Begin install table: Attachments

		hst_Logger_AddEntry( 'INFO', $methodName, 'Begin installing database table "Attachments"' );

		$sql = " CREATE TABLE ". hst_consts_dbObjectsTableNamesAttachments . " (
				AttachmentId int(11) NOT NULL AUTO_INCREMENT,
				EntityId int(11) NOT NULL,
				EntityType varchar(30) NOT NULL,
				AttachmentUrl varchar(1000) NOT NULL,
				AttachmentPath varchar(1000) NOT NULL,
				AttachmentSize int(11) NOT NULL,
				AttachmentFilename varchar(500) NOT NULL,
				AttachmentUploadUserId int(11) NOT NULL,
				AttachmentUploadUserType varchar(30) NOT NULL,
				AttachmentCreatedDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY  (AttachmentId)
			) $charset_collate ";

		dbDelta( $sql );
		if( $wpdb->last_error !== '')
		{
			hst_Logger_AddEntry('ERROR', $methodName, $wpdb->last_error );
		}
		else
		{
			hst_Logger_AddEntry('INFO', $methodName, 'Done installing database table "Attachments"' );
		}


		//Updating option with db version
		$hst_globals_pluginDatabaseVersion = hst_consts_pluginVersion;
		update_option( hst_consts_optionKeyDBVersion, $hst_globals_pluginDatabaseVersion , '', 'yes' );
		hst_Logger_AddEntry( 'INFO', $methodName, 'wordpress option value "' . hst_consts_optionKeyDBVersion . '" updated to: ' . $hst_globals_pluginDatabaseVersion  );

		//All done
		hst_Logger_AddEntry( 'INFO', $methodName, 'db delta finished' );

		//Begin check for default records - Tickets Categories
		hst_Logger_AddEntry( 'INFO', $methodName, 'Check for install default records for TicketsCategories' );
		$ticketsCategoriesDefaultAlreadyInstalled = get_option( hst_consts_optionKeyDefaultCategoriesInstalled );
		if ( $ticketsCategoriesDefaultAlreadyInstalled != "1" )
		{

			hst_Logger_AddEntry( 'INFO', $methodName, 'Start installing default records for TicketsCategories' );

			$classTicketCategory = new hst_Classes_Ticket_Category();
			$entityTicketCategory = new hst_Entities_Ticket_Category();

			$entityTicketCategory->TicketCategoryDescription = "Generic";
			$classTicketCategory->InsertCategoryInDB( $entityTicketCategory );

			//setting option as "done"
			update_option( hst_consts_optionKeyDefaultCategoriesInstalled, '1' , '', 'yes' );
			hst_Logger_AddEntry( 'INFO', $methodName, 'Finised installing default records for TicketsCategories' );

		}

		//Begin check for default records - Tickets Statuses
		hst_Logger_AddEntry( 'INFO', $methodName, 'Check for install default records for TicketsStatuses' );
		$ticketsStatusesDefaultAlreadyInstalled = get_option( hst_consts_optionKeyDefaultStatusesInstalled );
		if ( $ticketsStatusesDefaultAlreadyInstalled != "1" )
		{

			hst_Logger_AddEntry( 'INFO', $methodName, 'Start installing default records for TicketsStatuses' );

			$classTicketStatus = new hst_Classes_Ticket_Status();
			$entityTicketStatus = new hst_Entities_Ticket_Status();

			$entityTicketStatus->TicketStatusDescription = "New";
			$entityTicketStatus->TicketStatusIsClosed = "0";
			$entityTicketStatus->TicketStatusIsDefaultForNewTickets = "1";
			$classTicketStatus->InsertStatusInDB( $entityTicketStatus );

			$entityTicketStatus->TicketStatusDescription = "Pending";
			$entityTicketStatus->TicketStatusIsClosed = "0";
			$entityTicketStatus->TicketStatusIsDefaultForNewTickets = "0";
			$classTicketStatus->InsertStatusInDB( $entityTicketStatus );

			$entityTicketStatus->TicketStatusDescription = "Closed";
			$entityTicketStatus->TicketStatusIsClosed = "1";
			$entityTicketStatus->TicketStatusIsDefaultForNewTickets = "0";
			$classTicketStatus->InsertStatusInDB( $entityTicketStatus );

			//setting option as "done"
			update_option( hst_consts_optionKeyDefaultStatusesInstalled, '1' , '', 'yes' );
			hst_Logger_AddEntry( 'INFO', $methodName, 'Finised installing default records for TicketsStatuses' );

		}

		//Begin check for default records - Tickets Priorities
		hst_Logger_AddEntry( 'INFO', $methodName, 'Check for install default records for TicketsPriorities' );
		$ticketsPrioritiesDefaultAlreadyInstalled = get_option( hst_consts_optionKeyDefaultPrioritiesInstalled );
		if ( $ticketsPrioritiesDefaultAlreadyInstalled != "1" )
		{

			hst_Logger_AddEntry( 'INFO', $methodName, 'Start installing default records for TicketsPriorities' );

			$classTicketPriority = new hst_Classes_Ticket_Priority();
			$entityTicketPriority = new hst_Entities_Ticket_Priority();

			$entityTicketPriority->TicketPriorityDescription = "Low";
			$entityTicketPriority->TicketPriorityIsDefaultForNewTickets = 0;
			$classTicketPriority->InsertStatusInDB( $entityTicketPriority );

			$entityTicketPriority->TicketPriorityDescription = "Normal";
			$entityTicketPriority->TicketPriorityIsDefaultForNewTickets = 1;
			$classTicketPriority->InsertStatusInDB( $entityTicketPriority );

			$entityTicketPriority->TicketPriorityDescription = "High";
			$entityTicketPriority->TicketPriorityIsDefaultForNewTickets = 0;
			$classTicketPriority->InsertStatusInDB( $entityTicketPriority );

			//setting option as "done"
			update_option( hst_consts_optionKeyDefaultPrioritiesInstalled, '1' , '', 'yes' );
			hst_Logger_AddEntry( 'INFO', $methodName, 'Finised installing default records for TicketsPriorities' );

		}

		//Begin check for status colors update
		hst_Logger_AddEntry( 'INFO', $methodName, 'Check for set up Status default label colors' );
		$ticketsStatusLabelsColorsAlreadyInstalled = get_option( hst_consts_optionKeyDefaultStatusLabelsInstalled );
		if ( $ticketsStatusLabelsColorsAlreadyInstalled != "1" )
		{

			hst_Logger_AddEntry( 'INFO', $methodName, 'Start installing Status default label colors' );

			$classTicketStatus = new hst_Classes_Ticket_Status();
			$entityTicketStatus = new hst_Entities_Ticket_Status();

			$entityTicketStatusNew = $classTicketStatus->GetFromDB( array( "TicketStatusId" => 1 ) );
			$entityTicketStatusNew->TicketStatusBgColor = "#5cb85c";
			$classTicketStatus->UpdateStatusInDB($entityTicketStatusNew);

			$entityTicketStatusPending = $classTicketStatus->GetFromDB( array( "TicketStatusId" => 2 ) );
			$entityTicketStatusPending->TicketStatusBgColor = "#0080c0";
			$classTicketStatus->UpdateStatusInDB($entityTicketStatusPending);

			$entityTicketStatusClosed = $classTicketStatus->GetFromDB( array( "TicketStatusId" => 3 ) );
			$entityTicketStatusClosed->TicketStatusBgColor = "#a7a7a7";
			$classTicketStatus->UpdateStatusInDB($entityTicketStatusClosed);

			//setting option as "done"
			update_option( hst_consts_optionKeyDefaultStatusLabelsInstalled, '1' , '', 'yes' );
			hst_Logger_AddEntry( 'INFO', $methodName, 'Finised installing Status default label colors' );

		}


	}
	else
	{

		//Versions are the same, no need to delta

	}


}



//Uninstall objects (on plugin uninstall)
function hst_DbInstall_UnInstallDatabaseObjects()
{

	global $wpdb;

	//deleting db objects
	$wpdb->query( "DROP TABLE " . hst_consts_dbObjectsTableNamesTickets );
	$wpdb->query( "DROP TABLE " . hst_consts_dbObjectsTableNamesTicketsCategories );
	$wpdb->query( "DROP TABLE " . hst_consts_dbObjectsTableNamesTicketsStatuses );
	$wpdb->query( "DROP TABLE " . hst_consts_dbObjectsTableNamesTicketsPriorities );
	$wpdb->query( "DROP TABLE " . hst_consts_dbObjectsTableNamesTicketsEvents );
	$wpdb->query( "DROP TABLE " . hst_consts_dbObjectsTableNamesAttachments );

	//removing options
	delete_option( hst_consts_optionKeyEnableLogger );
	delete_option( hst_consts_optionKeyDBVersion );
	delete_option( hst_consts_optionKeyDefaultCategoriesInstalled );
	delete_option( hst_consts_optionKeyDefaultStatusesInstalled );
	delete_option( hst_consts_optionKeyDefaultPrioritiesInstalled );
	delete_option( hst_consts_optionKeyHelpdeskDefaultAgentUserId );
	delete_option( hst_consts_optionKeyHelpdeskEmailAddress );
	delete_option( hst_consts_optionKeyHelpdeskEmailSenderName );
	delete_option( hst_consts_optionKeyHelpdeskAttachmentsAllowed );
	delete_option( hst_consts_optionKeyHelpdeskAttachmentsExtensions );
	delete_option( hst_consts_optionKeyNotifTicketCreateCustomerEnabled );
	delete_option( hst_consts_optionKeyNotifTicketCreateAgentEnabled );
	delete_option( hst_consts_optionKeyNotifTicketMessageCreateCustomerEnabled );
	delete_option( hst_consts_optionKeyNotifTicketMessageCreateAgentEnabled );
	delete_option( hst_consts_optionKeyFrontEndPageId );
	delete_option( hst_consts_optionKeyFrontEndAllowedUserType );
	delete_option( hst_consts_optionKeyEventLogLogErrors );
	delete_option( hst_consts_optionKeyEventLogLogEmails );
	delete_option( hst_consts_optionKeyEventLogLogDebug );
	delete_option( hst_consts_optionInstallWizardCompleted );
	delete_option( hst_consts_optionKeyDeleteTablesUninstall );
	delete_option( hst_consts_optionKeyDefaultStatusLabelsInstalled );


}


?>