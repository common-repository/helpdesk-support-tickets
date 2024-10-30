<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//Class that represents a ticket priority
class hst_Entities_Ticket_Priority
{

	//Properties
	public $TicketPriorityId;
    public $TicketPriorityDescription;
	public $TicketPriorityIsDefaultForNewTickets;

}


?>