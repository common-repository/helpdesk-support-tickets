<?php


//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}


//registering ajax calls
add_action( 'wp_ajax_hst_ajax_dashboard_home_getdata', 'hst_ajax_dashboard_home_getdata' );


//Gets the dataset for rendering the dashboard
function hst_ajax_dashboard_home_getdata()
{

	$methodName = 'hst_ajax_dashboard_home_getdata';
	$response = new hst_Entities_AjaxResponse();

	hst_Logger_AddEntry("FUNCTION", $methodName, "Start");
	hst_Logger_AddEntry("DEBUG", $methodName, "sPOST values: " . hst_Logger_exportVarContent($_POST, true));

	//checking nonce
	check_ajax_referer( 'hst', 'security' );
	hst_Logger_AddEntry("DEBUG", $methodName, "Nonce Security passed.");

	//result dataset
	$resultDataset = array();

	//needed objects
	$ticketsClass = new hst_Classes_Ticket();
	$resultDataset["StatusCounters"] = array();
	$resultDataset["LastTickets"] = array();
	$resultDataset["AddedLast10Days"] = array();
	$resultDataset["ClosedLast10Days"] = array();
	$resultDataset["TicketsByPriority"] = array();
	$resultDataset["TicketsByCategory"] = array();
	$resultDataset["TicketsByStatus"] = array();

	//1. Status counters top of dashboard
	$arrayStatusCounters = $ticketsClass->ReturnStatusWithTicketsCounters( null );
	//iterating to include only needed info in the json
	foreach( $arrayStatusCounters as $arrayStatusCountersItem )
	{
		$newResultItem = array();
		$newResultItem["id"] = $arrayStatusCountersItem->TicketStatusId;
		$newResultItem["count"] = $arrayStatusCountersItem->TotalTicketsForCounters;
		$newResultItem["bgcolor"] = $arrayStatusCountersItem->TicketStatusBgColor;
		array_push($resultDataset["StatusCounters"], $newResultItem);
	}

	//2. Last 5 Tickets List
	$paramsTicketsList = array();
	$paramsTicketsList["SortingKey"] = "dateDesc";
	$paramsTicketsList["LimitRows"] = 5;
	$list5LastTickets = $ticketsClass->ListFromDB($paramsTicketsList);
	//include only needed columns

	//getting the list of tickets statuses in order to include the status color in the list of tickets returned
	$classTicketStatus = new hst_Classes_Ticket_Status();
	$listTicketsStatus = $classTicketStatus->ListFromDB( array() );

	//adjusting the ticket list with additional data
	foreach( $list5LastTickets as $ticketListItem )
	{

		//we need to find the status in the list of statuses found before. In this way we can attach the status color to this ticket returned record.
		foreach( $listTicketsStatus as $ticketsStatusEntity )
		{
			if ( $ticketListItem->TicketStatusId == $ticketsStatusEntity->TicketStatusId )
			{
				$ticketListItem->StatusBgColor = $ticketsStatusEntity->TicketStatusBgColor;
			}
		}

	}

	$resultDataset["LastTickets"] = $list5LastTickets;

	//3. Get the whole ticket dataset, ordered by creation date
	$paramsTicketsList = array();
	$paramsTicketsList["SortingKey"] = "dateAsc";
	$listFullTickets = $ticketsClass->ListFromDB($paramsTicketsList);

	//builds the -10 days dataset (all dates between today and last 10 days)

	array_push($resultDataset["AddedLast10Days"], array( "date" => date( 'Y-m-d', strtotime("-9 days") ), "count" => 0 ) );
	array_push($resultDataset["AddedLast10Days"], array( "date" => date( 'Y-m-d', strtotime("-8 days") ), "count" => 0 ) );
	array_push($resultDataset["AddedLast10Days"], array( "date" => date( 'Y-m-d', strtotime("-7 days") ), "count" => 0 ) );
	array_push($resultDataset["AddedLast10Days"], array( "date" => date( 'Y-m-d', strtotime("-6 days") ), "count" => 0 ) );
	array_push($resultDataset["AddedLast10Days"], array( "date" => date( 'Y-m-d', strtotime("-5 days") ), "count" => 0 ) );
	array_push($resultDataset["AddedLast10Days"], array( "date" => date( 'Y-m-d', strtotime("-4 days") ), "count" => 0 ) );
	array_push($resultDataset["AddedLast10Days"], array( "date" => date( 'Y-m-d', strtotime("-3 days") ), "count" => 0 ) );
	array_push($resultDataset["AddedLast10Days"], array( "date" => date( 'Y-m-d', strtotime("-2 days") ), "count" => 0 ) );
	array_push($resultDataset["AddedLast10Days"], array( "date" => date( 'Y-m-d', strtotime("-1 days") ), "count" => 0 ) );
	array_push($resultDataset["AddedLast10Days"], array( "date" => date( 'Y-m-d', strtotime("-0 days") ), "count" => 0 ) );

	array_push($resultDataset["ClosedLast10Days"], array( "date" => date( 'Y-m-d', strtotime("-9 days") ), "count" => 0 ) );
	array_push($resultDataset["ClosedLast10Days"], array( "date" => date( 'Y-m-d', strtotime("-8 days") ), "count" => 0 ) );
	array_push($resultDataset["ClosedLast10Days"], array( "date" => date( 'Y-m-d', strtotime("-7 days") ), "count" => 0 ) );
	array_push($resultDataset["ClosedLast10Days"], array( "date" => date( 'Y-m-d', strtotime("-6 days") ), "count" => 0 ) );
	array_push($resultDataset["ClosedLast10Days"], array( "date" => date( 'Y-m-d', strtotime("-5 days") ), "count" => 0 ) );
	array_push($resultDataset["ClosedLast10Days"], array( "date" => date( 'Y-m-d', strtotime("-4 days") ), "count" => 0 ) );
	array_push($resultDataset["ClosedLast10Days"], array( "date" => date( 'Y-m-d', strtotime("-3 days") ), "count" => 0 ) );
	array_push($resultDataset["ClosedLast10Days"], array( "date" => date( 'Y-m-d', strtotime("-2 days") ), "count" => 0 ) );
	array_push($resultDataset["ClosedLast10Days"], array( "date" => date( 'Y-m-d', strtotime("-1 days") ), "count" => 0 ) );
	array_push($resultDataset["ClosedLast10Days"], array( "date" => date( 'Y-m-d', strtotime("-0 days") ), "count" => 0 ) );

	array_push($resultDataset["TicketsByStatus"], array( "name" => "New", "count" => 0 ) );
	array_push($resultDataset["TicketsByStatus"], array( "name" => "Pending", "count" => 0 ) );
	array_push($resultDataset["TicketsByStatus"], array( "name" => "Closed", "count" => 0 ) );

	//Cycling the whole dataset of ticket to complete the results arrays
	foreach( $listFullTickets as $listFullTicketsItem )
	{

		//checking the creation date
		$ticketCreationDateWtihTime = new DateTime( $listFullTicketsItem->TicketDateCreated );
		$ticketCreationDate = $ticketCreationDateWtihTime->format('Y-m-d');

		//checks that Opening the date is within past 10 days
		if ( strtotime($ticketCreationDate) >= strtotime('-10 day'))
		{

			//checks if the last 10 days array contains already this date
			foreach($resultDataset["AddedLast10Days"] as $k => $AddedLast10DaysExistingItem )
			{
				//var_dump( $AddedLast10DaysExistingItem );
				if ( $AddedLast10DaysExistingItem["date"] == $ticketCreationDate )
				{

					//adds a unit to this counter
					$resultDataset["AddedLast10Days"][$k]["count"] = $resultDataset["AddedLast10Days"][$k]["count"] + 1;

				}

			}

		}

		//checking the closed date
		if ($listFullTicketsItem->TicketDateClosed != null)
		{
			$ticketClosednDateWtihTime = new DateTime( $listFullTicketsItem->TicketDateClosed );
			$ticketClosedDate = $ticketClosednDateWtihTime->format('Y-m-d');

			//checks that Closing the date is within past 10 days
			if ( strtotime($ticketClosedDate) >= strtotime('-10 day'))
			{

				//checks if the last 10 days array contains already this date
				foreach($resultDataset["ClosedLast10Days"] as $k => $ClosedLast10DaysExistingItem )
				{
					//var_dump( $AddedLast10DaysExistingItem );
					if ( $ClosedLast10DaysExistingItem["date"] == $ticketClosedDate )
					{

						//adds a unit to this counter
						$resultDataset["ClosedLast10Days"][$k]["count"] = $resultDataset["ClosedLast10Days"][$k]["count"] + 1;

					}

				}

			}

		}

		//breakdown by priorities check
		$itemPriorityNameExistsAlready = false;
		foreach( $resultDataset["TicketsByPriority"] as $p => $TicketsByPriorityItem )
		{
			if ( $TicketsByPriorityItem["name"] == $listFullTicketsItem->TicketPriorityText )
			{
				//adds a unit to this counter
				$resultDataset["TicketsByPriority"][$p]["count"] = $resultDataset["TicketsByPriority"][$p]["count"] + 1;
				$itemPriorityNameExistsAlready = true;
			}
		}
		if ( $itemPriorityNameExistsAlready == false )
		{
			//must add this new element
			$newResultItemForPriority = array();
			$newResultItemForPriority["name"] = $listFullTicketsItem->TicketPriorityText;
			$newResultItemForPriority["count"] = 1;
			array_push($resultDataset["TicketsByPriority"], $newResultItemForPriority);
		}

		//breakdown by category check
		$itemCategoryNameExistsAlready = false;
		foreach( $resultDataset["TicketsByCategory"] as $p => $TicketsByCategoryItem )
		{
			if ( $TicketsByCategoryItem["name"] == $listFullTicketsItem->TicketCategoryText )
			{
				//adds a unit to this counter
				$resultDataset["TicketsByCategory"][$p]["count"] = $resultDataset["TicketsByCategory"][$p]["count"] + 1;
				$itemCategoryNameExistsAlready = true;
			}
		}
		if ( $itemCategoryNameExistsAlready == false )
		{
			//must add this new element
			$newResultItemForCategory = array();
			$newResultItemForCategory["name"] = $listFullTicketsItem->TicketCategoryText;
			$newResultItemForCategory["count"] = 1;
			array_push($resultDataset["TicketsByCategory"], $newResultItemForCategory);
		}

		//breakdown by status check
		foreach( $resultDataset["TicketsByStatus"] as $p => $TicketsByStatusItem )
		{
			if ( $TicketsByStatusItem["name"] == $listFullTicketsItem->TicketStatusText )
			{
				//adds a unit to this counter
				$resultDataset["TicketsByStatus"][$p]["count"] = $resultDataset["TicketsByStatus"][$p]["count"] + 1;
			}
		}

	}



	//building response
	$response->success = 1;
	$response->data = $resultDataset;

	hst_Logger_AddEntry("DEBUG", $methodName, "Response content: " . hst_Logger_exportVarContent($response, true));
	hst_Logger_AddEntry("FUNCTION", $methodName,"End");

	wp_send_json($response);

}

?>