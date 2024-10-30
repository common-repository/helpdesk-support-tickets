<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//Class that represents a ticket status
class hst_Entities_Ticket_Status
{

	//Properties
	public $TicketStatusId;
    public $TicketStatusDescription;
	public $TicketStatusIsClosed;
	public $TicketStatusIsDefaultForNewTickets;
	public $TicketStatusBgColor;

	//Extended / loaded on demand
	public $TotalTicketsForCounters;

}


?>