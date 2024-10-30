<?php

	//code to hide attachments tab in case it is disabled by settings
	$AttachmentsAllowed = get_option( hst_consts_optionKeyHelpdeskAttachmentsAllowed, 0 );

	$CodeHideAttachments = " display:none; ";

	if ( $AttachmentsAllowed == 1 )
	{
		$CodeHideAttachments = "";
	}

?>


<div id="hst-controls-tickets-view" style="display:none;">

	<!-- control body -->
	<div class="row" id="hst-controls-tickets-view-infopanel-closedticket" style="display:none;">
		<div class="col-md-12">
			<div class="hst-panels-greysubpanel text-center">
				<i class="glyphicon glyphicon-info-sign hst-panels-greysubpanel-icon"></i>
				<span class="hst-panels-greysubpanel-iconspantext"><?php _e("This Support Ticket has been closed, therefore no modifications are allowed.", "hst") ?></span>
			</div>
		</div>
		<div class="clear"></div>
		<div class="hst-panels-spacetop"></div>
	</div>

	<div class="row">

		<div class="col-md-9">

			<!-- main tab strip -->
			<ul class="nav nav-tabs hst-subnavbar">
				<li id="hst-controls-tickets-view-tabs-li-home" onclick="hst_tickets_viewticket_showpanel('home')">
					<a><?php _e("General", "hst") ?></a>
				</li>
				<li id="hst-controls-tickets-view-tabs-li-attachments" onclick="hst_tickets_viewticket_showpanel('attachments')" style="<?php echo $CodeHideAttachments; ?>">
					<a><?php _e("Attachments", "hst") ?></a>
				</li>
				<li id="hst-controls-tickets-view-tabs-li-others" onclick="hst_tickets_viewticket_showpanel('others')" class="hide">
					<a><?php _e("Other Info", "hst") ?></a>
				</li>
			</ul>
			<!-- main tab strip -->
			<!-- panels -->
			<div id="hst-controls-tickets-view-panel-tickethome" >
				<?php include( hst_consts_pluginPath . '/controls/dashboard/tickets-view-tickethome.php' ); ?>
			</div>
			<div id="hst-controls-tickets-view-panel-ticketattachments" >
				<?php include( hst_consts_pluginPath . '/controls/dashboard/tickets-view-ticketattachments.php' ); ?>
			</div>
			<div id="hst-controls-tickets-view-panel-ticketothers" >
				<?php _e("others", "hst") ?>
			</div>
			<!-- panels -->

		</div>

		<div class="col-md-3 nopadding-left">

			<?php include( hst_consts_pluginPath . '/controls/dashboard/tickets-view-ticketdata.php' ); ?>

		</div>

	</div>
	<!-- control body -->

</div>
