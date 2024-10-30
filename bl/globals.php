<?php

//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}

//Ensure global is declared
global $wpdb;

//define some options keys used in the Wordpress options table
define( 'hst_consts_optionKeyEnableLogger', 'hstLoggerLevel' );
define( 'hst_consts_optionKeyDBVersion', 'hstDatabaseVersion' );
define( 'hst_consts_optionKeyDefaultCategoriesInstalled', 'hstDefaultCategoriesInstalled' );
define( 'hst_consts_optionKeyDefaultStatusesInstalled', 'hstDefaultStatusesInstalled' );
define( 'hst_consts_optionKeyDefaultPrioritiesInstalled', 'hstDefaultPrioritiesInstalled' );
define( 'hst_consts_optionKeyDefaultStatusLabelsInstalled', 'hstDefaultStatusLabelsInstalled' );
define( 'hst_consts_optionKeyHelpdeskDefaultAgentUserId', 'hstHelpdeskDefaultAgentUserId' );
define( 'hst_consts_optionKeyHelpdeskEmailAddress', 'hstHelpdeskEmailAddress' );
define( 'hst_consts_optionKeyHelpdeskEmailSenderName', 'hstHelpdeskEmailSenderName' );
define( 'hst_consts_optionKeyHelpdeskAttachmentsAllowed', 'hstHelpdeskAttachmentsAllowed' );
define( 'hst_consts_optionKeyHelpdeskAttachmentsExtensions', 'hstHelpdeskAttachmentsExtensions' );
define( 'hst_consts_optionKeyNotifTicketCreateCustomerEnabled', 'hstNotifTicketCreateCustomerEnabled' );
define( 'hst_consts_optionKeyNotifTicketCreateAgentEnabled', 'hstNotifTicketCreateAgentEnabled' );
define( 'hst_consts_optionKeyNotifTicketMessageCreateCustomerEnabled', 'hstNotifTicketMessageCreateCustomerEnabled' );
define( 'hst_consts_optionKeyNotifTicketMessageCreateAgentEnabled', 'hstNotifTicketMessageCreateAgentEnabled' );
define( 'hst_consts_optionKeyFrontEndPageId', 'hstFrontEndPageId' );
define( 'hst_consts_optionKeyFrontEndAllowedUserType', 'hstFrontEndAllowedUsersType' );
define( 'hst_consts_optionKeyEventLogLogErrors', 'hstEventLogLogErrors' );
define( 'hst_consts_optionKeyEventLogLogEmails', 'hstEventLogLogEmails' );
define( 'hst_consts_optionKeyEventLogLogDebug', 'hstEventLogLogDebug' );
define( 'hst_consts_optionInstallWizardCompleted', 'hstInstallWizardCompleted' );
define( 'hst_consts_optionKeyDeleteTablesUninstall', 'hstKeyDeleteTablesUninstall' );


//define some constants
define( 'hst_consts_pluginVersion', '1.1.4' );
define( 'hst_consts_logFilePath', hst_consts_pluginPath . '/utils/log/log.txt' );

//define consts for database objects
define( 'hst_consts_dbObjectsTableNamesTickets', $wpdb->prefix . 'hst_tickets' );
define( 'hst_consts_dbObjectsTableNamesTicketsCategories', $wpdb->prefix . 'hst_tickets_categories' );
define( 'hst_consts_dbObjectsTableNamesTicketsStatuses', $wpdb->prefix . 'hst_tickets_statuses' );
define( 'hst_consts_dbObjectsTableNamesTicketsPriorities', $wpdb->prefix . 'hst_tickets_priorities' );
define( 'hst_consts_dbObjectsTableNamesTicketsEvents', $wpdb->prefix . 'hst_tickets_events' );
define( 'hst_consts_dbObjectsTableNamesCustomers', $wpdb->prefix . 'users' );
define( 'hst_consts_dbObjectsTableNamesUsers', $wpdb->prefix . 'users' );
define( 'hst_consts_dbObjectsTableNamesAttachments', $wpdb->prefix . 'hst_attachments' );

//database standard selects query
define ( 'hst_consts_dbSelectsTickets',				   ' SELECT '
													 . ' T.TicketId, '
													 . ' T.TicketTitle, '
													 . ' T.TicketProblem, '
													 . ' T.TicketCategoryId, '
													 . ' T.TicketStatusId, '
													 . ' T.TicketPriorityId, '
													 . ' T.TicketDateCreated, '
													 . ' T.TicketDateClosed, '
													 . ' T.TicketDateLastUpdated, '
													 . ' T.TicketCustomerUserId, '
													 . ' T.TicketCustomerUserDisplayName, '
													 . ' T.TicketCustomerUserEmail, '
													 . ' T.TicketAssignedUserId, '
													 . ' TC.TicketCategoryDescription AS TicketCategoryText, '
													 . ' TS.TicketStatusDescription AS TicketStatusText, '
													 . ' TP.TicketPriorityDescription AS TicketPriorityText, '
													 . ' O.display_name AS TicketAssignedUserDisplayName, '
													 . ' O.user_email AS TicketAssignedUserEmail '
													 . ' FROM ' . hst_consts_dbObjectsTableNamesTickets . ' T '
													 . ' LEFT OUTER JOIN ' . hst_consts_dbObjectsTableNamesTicketsCategories . ' TC ON T.TicketCategoryId = TC.TicketCategoryId '
													 . ' LEFT OUTER JOIN ' . hst_consts_dbObjectsTableNamesTicketsStatuses . ' TS ON T.TicketStatusId = TS.TicketStatusId '
													 . ' LEFT OUTER JOIN ' . hst_consts_dbObjectsTableNamesTicketsPriorities . ' TP ON T.TicketPriorityId = TP.TicketPriorityId '
													 . ' LEFT OUTER JOIN ' . hst_consts_dbObjectsTableNamesUsers . ' O ON T.TicketAssignedUserId = O.ID '
	   );

define ( 'hst_consts_dbSelectsTicketsEvents',		   ' SELECT '
													 . ' TE.TicketEventId, '
													 . ' TE.TicketId, '
													 . ' TE.TicketEventDate, '
													 . ' TE.TicketEventUserType, '
													 . ' TE.TicketEventUserId, '
													 . ' TE.TicketEventUserDisplayName, '
													 . ' TE.TicketEventType, '
													 . ' TE.TicketEventMessageContent, '
													 . ' TE.TicketEventUserDataUpdateContent '
													 . ' FROM ' . hst_consts_dbObjectsTableNamesTicketsEvents . ' TE '
	   );

define ( 'hst_consts_dbSelectsCustomers',		       ' SELECT '
													 . ' C.ID, '
													 . ' C.display_name, '
													 . ' C.user_email '
													 . ' FROM ' . hst_consts_dbObjectsTableNamesCustomers . ' C '
	   );

define ( 'hst_consts_dbSelectsUsers',		           ' SELECT '
													 . ' U.ID, '
													 . ' U.display_name, '
													 . ' U.user_email '
													 . ' FROM ' . hst_consts_dbObjectsTableNamesUsers . ' U '
	   );

define ( 'hst_consts_dbSelectsAttachments',			   ' SELECT '
													 . ' A.AttachmentId, '
													 . ' A.EntityId, '
													 . ' A.EntityType, '
													 . ' A.AttachmentUrl, '
													 . ' A.AttachmentPath, '
													 . ' A.AttachmentSize, '
													 . ' A.AttachmentFilename, '
													 . ' A.AttachmentUploadUserId, '
													 . ' A.AttachmentUploadUserType, '
													 . ' A.AttachmentCreatedDate, '
													 . ' U.display_name AS AttachmentUploadUserDisplayName '
													 . ' FROM ' . hst_consts_dbObjectsTableNamesAttachments . ' A '
													 . ' LEFT OUTER JOIN ' . hst_consts_dbObjectsTableNamesUsers . ' U ON A.AttachmentUploadUserId = U.ID '
		);


//rendering blocks templates
define ( 'hst_templaterendering_ticketevents_messages',		   "<div class='hst-ticketsview-eventslist-block'>"
															 . "	<div class='col-md-1 text-center'>"
															 . "		<div class='hst-ticketsview-eventslist-block-userpane'>"
															 . "			<div class='hst-ticketsview-eventslist-block-userpane-photo'>##AuthorAvatar##</div>"
															 . "		</div>"
															 . "	</div>"
															 . "	<div class='col-md-8'>"
															 . "		<div class='hst-ticketsview-eventslist-block-messagepane'>"
															 . "			<div class='hst-ticketsview-eventslist-block-date'>"
															 . "				<strong>##AuthorDisplayName##</strong> <span class='hst-text-grayed'>on ##EventDateText## (##EventDateTimePassed##)</span> "
															 . "			</div>"
															 . "			<div class='hst-ticketsview-eventslist-block-message'>"
															 . "				##MessageContent##"
															 . "			</div>"
															 . "		</div>"
															 . "	</div>"
															 . "	<div class='clear'></div>"
															 . "</div>"
															 . "<div class='clear'></div>"
	   );

define ( 'hst_templaterendering_ticketevents_messagesself',	   "<div class='hst-ticketsview-eventslist-block'>"
															 . "	<div class='col-md-3'>"
															 . "	</div>"
															 . "	<div class='col-md-8'>"
															 . "		<div class='hst-ticketsview-eventslist-block-messagepane hst-ticketsview-eventslist-block-messagepane-self'>"
															 . "			<div class='hst-ticketsview-eventslist-block-date'>"
															 . "				<strong>##AuthorDisplayName##</strong> <span class='hst-text-grayed'>on ##EventDateText## (##EventDateTimePassed##)</span> "
															 . "			</div>"
															 . "			<div class='hst-ticketsview-eventslist-block-message'>"
															 . "				##MessageContent##"
															 . "			</div>"
															 . "		</div>"
															 . "	</div>"
															 . "	<div class='col-md-1 text-center'>"
															 . "		<div class='hst-ticketsview-eventslist-block-userpane'>"
															 . "			<div class='hst-ticketsview-eventslist-block-userpane-photo'>##AuthorAvatar##</div>"
															 . "		</div>"
															 . "	</div>"
															 . "	<div class='clear'></div>"
															 . "</div>"
															 . "<div class='clear'></div>"
	   );

define ( 'hst_templaterendering_ticketevents_datachange',	   "<div class='hst-ticketsview-eventslist-block'>"
															 . "	<div class='col-md-1 text-center'>"
															 . "		<div class='hst-ticketsview-eventslist-block-userpane'>"
															 . "			<div class='hst-ticketsview-eventslist-block-userpane-photo'>##AuthorAvatar##</div>"
															 . "		</div>"
															 . "	</div>"
															 . "	<div class='col-md-8'>"
															 . "		<div class='hst-ticketsview-eventslist-block-messagepane'>"
															 . "			<div class='hst-ticketsview-eventslist-block-date'>"
															 . "				<strong>##AuthorDisplayName##</strong> <span class='hst-text-grayed'>on ##EventDateText## (##EventDateTimePassed##)</span> "
															 . "			</div>"
															 . "			<div class='hst-ticketsview-eventslist-block-message'>"
															 . "				##DataChangeContent##"
															 . "			</div>"
															 . "		</div>"
															 . "	</div>"
															 . "	<div class='clear'></div>"
															 . "</div>"
															 . "<div class='clear'></div>"
	   );

define ( 'hst_templaterendering_ticketevents_datachangeself',  "<div class='hst-ticketsview-eventslist-block'>"
															 . "	<div class='col-md-3'>"
															 . "	</div>"
															 . "	<div class='col-md-8'>"
															 . "		<div class='hst-ticketsview-eventslist-block-messagepane hst-ticketsview-eventslist-block-messagepane-self'>"
															 . "			<div class='hst-ticketsview-eventslist-block-date'>"
															 . "				<strong>##AuthorDisplayName##</strong> <span class='hst-text-grayed'>on ##EventDateText## (##EventDateTimePassed##)</span> "
															 . "			</div>"
															 . "			<div class='hst-ticketsview-eventslist-block-message'>"
															 . "				##DataChangeContent##"
															 . "			</div>"
															 . "		</div>"
															 . "	</div>"
															 . "	<div class='col-md-1 text-center'>"
															 . "		<div class='hst-ticketsview-eventslist-block-userpane'>"
															 . "			<div class='hst-ticketsview-eventslist-block-userpane-photo'>##AuthorAvatar##</div>"
															 . "		</div>"
															 . "	</div>"
															 . "	<div class='clear'></div>"
															 . "</div>"
															 . "<div class='clear'></div>"
	   );

define ( 'hst_templaterendering_ticket_frontend',      "<div class='row hst-ticketslist-block' onclick='hst_fe_mytickets_ticketclick(##TicketId##)'>"
													 . "		<div class='col-md-1 hst-ticketslist-block-ticketid'>"
													 . "			<div>###TicketId##</div>"
													 . "		</div>"
													 . "		<div class='col-md-9 hst-ticketslist-block-tickettitle'>"
													 . "			<div>##TicketTitle##</div>"
													 . "		</div>"
													 . "		<div class='col-md-2 hst-ticketslist-block-status text-right'>"
													 . "			<div class='label label-success'>##TicketStatusText##</div>"
													 . "		</div>"
													 . "		<div class='clear'></div>"
													 . "</div>"

	   );

define ( 'hst_templaterendering_ticketevent_frontendself',	  "<div>"
															. "		<div class='col-md-2'></div> "
															. "		<div class='col-md-8 text-right hst-ticketsview-messageslist-messageblock '>"
															. "			<div class='hst-ticketsview-messageslist-datepane'>##EventDateText## (##EventDateTimePassed##)</div> "
															. "			<div class='hst-ticketsview-messageslist-message'>##MessageContent##</div> "
															. "		</div>"
															. "		<div class='col-md-2 text-center hst-ticketsview-messageslist-photoblock'>"
															. "			##AuthorAvatar##"
															. "		</div> "
															. "		<div class='clear'></div>"
															. "</div>"
															. "<div class='clear'></div>"

	   );

define ( 'hst_templaterendering_ticketevent_frontendothers',  "<div>"
															. "		<div class='col-md-2 text-center hst-ticketsview-messageslist-photoblock-others' >"
															. "			##AuthorAvatar##"
															. "		</div> "
															. "		<div class='col-md-8 text-left hst-ticketsview-messageslist-messageblock-others'>"
															. "			<div class='hst-ticketsview-messageslist-datepane'>##EventDateText## (##EventDateTimePassed##)</div> "
															. "			<div class='hst-ticketsview-messageslist-message'>##MessageContent##</div> "
															. "		</div>"
															. "		<div class='col-md-2'></div> "
															. "		<div class='clear'></div>"
															. "</div>"
															. "<div class='clear'></div>"
	   );

?>