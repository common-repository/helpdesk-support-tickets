<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//Class that represents a support ticket entity
class hst_Entities_Ticket
{

	//Properties
	public $TicketId;
    public $TicketTitle;
	public $TicketProblem;
	public $TicketCategoryId;
	public $TicketStatusId;
	public $TicketPriorityId;
	public $TicketCustomerUserId;
	public $TicketCustomerUserDisplayName;
	public $TicketCustomerUserEmail;
	public $TicketDateCreated;
	public $TicketDateLastUpdated;
	public $TicketAssignedUserId;

	//Readonly (joins)
	public $TicketCategoryText;
	public $TicketStatusText;
	public $TicketPriorityText;
	public $TicketAssignedUserDisplayName;

	//calculated on transcode
	public $TicketDateCreatedText;
	public $TicketDateLastUpdatedText;
	public $TicketDateClosedText;
	public $TicketDateCreatedHumanTimeDiff;
	public $TicketDateLastUpdatedHumanTimeDiff;
	public $TicketDateClosedHumanTimeDiff;

	//Loaded on demand
	public $TicketEvents = array();
	public $TicketCustomerUserAvatar;

}

?>