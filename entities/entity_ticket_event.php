<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//Class that represents a support ticket event
class hst_Entities_Ticket_Event
{

	//Properties
	public $TicketEventId;
    public $TicketId;
	public $TicketEventDate;
	public $TicketEventUserType;															//Can be "agent" or "customer"
	public $TicketEventUserId;
	public $TicketEventUserDisplayName;
	public $TicketEventType;																//Can be "ticketdatachange", "message"
	public $TicketEventMessageContent;
	public $TicketEventUserDataUpdateContent;

	//calculated on transcode
	public $TicketEventDateText;
	public $TicketEventDateTextHumanTimeDiff;
	public $TicketEventAuthorAvatar;
	public $TicketEventAuthorIsMe;

}

?>