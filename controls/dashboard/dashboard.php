
<div id="hst-controls-dashboard" style="display:none;">

	<!-- main row -->
	<div class="row">

		<!-- left pane -->
		<div class="col-md-9">

			<!-- statuses blocks row -->
			<div class="row">

				<div class="col-md-4">

					<div id="hst-dashboard-home-ticketcounterpanel-iconpanel-statusnew" class="hst-dashboard-home-ticketcounterpanel-iconpanel">

						<i class="fa fa-file-text-o"></i>

					</div>

					<div class="hst-dashboard-home-ticketcounterpanel-countpanel">

						<div class="hst-dashboard-home-ticketcounterpanel-countpanel-label">
							New Tickets
						</div>
						<div class="hst-dashboard-home-ticketcounterpanel-countpanel-counter">
							<span id="hst-dashboard-home-ticketcounterpanel-countpanel-statusnew">0</span>
						</div>

					</div>

				</div>

				<div class="col-md-4">

					<div id="hst-dashboard-home-ticketcounterpanel-iconpanel-statuspending" class="hst-dashboard-home-ticketcounterpanel-iconpanel">

						<i class="fa fa-clock-o"></i>

					</div>

					<div class="hst-dashboard-home-ticketcounterpanel-countpanel">

						<div class="hst-dashboard-home-ticketcounterpanel-countpanel-label">
							Pending Tickets
						</div>
						<div class="hst-dashboard-home-ticketcounterpanel-countpanel-counter">
							<span id="hst-dashboard-home-ticketcounterpanel-countpanel-statuspending">0</span>
						</div>

					</div>

				</div>

				<div class="col-md-4">

					<div id="hst-dashboard-home-ticketcounterpanel-iconpanel-statusclosed" class="hst-dashboard-home-ticketcounterpanel-iconpanel">

						<i class="fa fa-thumbs-o-up"></i>

					</div>

					<div class="hst-dashboard-home-ticketcounterpanel-countpanel">

						<div class="hst-dashboard-home-ticketcounterpanel-countpanel-label">
							Closed Tickets
						</div>
						<div class="hst-dashboard-home-ticketcounterpanel-countpanel-counter">
							<span id="hst-dashboard-home-ticketcounterpanel-countpanel-statusclosed">0</span>
						</div>

					</div>

				</div>

			</div>
			<!-- statuses blocks row -->

			<div class="clearfix"></div>

			<!-- last 5 tickets -->
			<div class="row hst-panels-spacetop">

				<div class="col-md-12">

					<div class="hst-panels-panel">

						<div class="hst-panels-panel-header">

							<div class="hst-panels-panel-title">
								<?php _e("Last 5 Support Tickets", "hst") ?>
							</div>

							<div class="hst-panels-panel-buttonsheader">
								<button type="button" class="btn btn-labeled btn-primary" onclick="hst_dashboard_setHashAndNavigate('tickets-list');">
									<span class="btn-label">
										<i class="glyphicon glyphicon-search"></i>
									</span><?php _e("See All Tickets", "hst") ?>
								</button>
							</div>

							<div class="clear"></div>

						</div>

						<div class="hst-panels-panel-body">

							<!-- result body -->
							<table class="table table-striped" id="hst-dashboard-home-lasttickets-table">
								<thead>
									<tr>
										<th>#</th>
										<th>
											<?php _e("Customer", "hst") ?>
										</th>
										<th>
											<?php _e("Problem", "hst") ?>
										</th>
										<th>
											<?php _e("Status", "hst") ?>
										</th>
										<th>
											<?php _e("Action", "hst") ?>
										</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>

							<div id="hst-dashboard-home-lasttickets-nodata" style="display:none;" class="hst-panels-greysubpanel text-center">
								<div class="hst-panels-greysubpanel-bigicon">
									<i class="fa fa-info-circle"></i>
								</div>
								<div class="clear"></div>
								<?php _e("The Support Tickets database is empty.", "hst") ?>
								<br /><?php _e("Click on the button on this page to create new Tickets, or wait for your Customers to add Tickets.", "hst") ?>
							</div>

							<div class="clear"></div>

							<!-- result body -->

						</div>

					</div>

				</div>

			</div>
			<!-- last 5 tickets -->

			<div class="clearfix"></div>

			<!-- graph tickets added -->
			<div class="row">

				<div class="col-md-12">

					<div class="hst-panels-panel">

						<div class="hst-panels-panel-header">

							<div class="hst-panels-panel-title">
								<?php _e("Tickets Created Last 10 days", "hst") ?>
							</div>

							<div class="clear"></div>

						</div>

						<div class="hst-panels-panel-body">

							<canvas id="hst-dashboard-home-10daystickets-chartplaceholder"></canvas>

							<div class="clear"></div>

						</div>

					</div>

				</div>

			</div>
			<!-- graph tickets added -->

		</div>
		<!-- left pane -->

		<!-- right pane -->
		<div class="col-md-3">

			<div class="row">

				<div class="col-md-12">

					<div class="hst-panels-panel">

						<div class="hst-panels-panel-body">

							<div class="hst-panels-panel-title hst-panels-panel-subtitle" style="margin-top: 15px;">
								<?php _e("Tickets By Category", "hst") ?>
							</div>
							
							<canvas id="hst-dashboard-home-pieforcategory-chartplaceholder"></canvas>
							<div class="clear"></div>
                            <div class="hst-panels-sidepanelseparator"></div>

							<div class="hst-panels-panel-title hst-panels-panel-subtitle" style="margin-top: 15px;">
								<?php _e("Tickets By Status", "hst") ?>
							</div>

							<canvas id="hst-dashboard-home-pieforstatus-chartplaceholder"></canvas>
							<div class="clear"></div>
                            <div class="hst-panels-sidepanelseparator"></div>

							<div class="hst-panels-panel-title hst-panels-panel-subtitle" style="margin-top: 15px;">
								<?php _e("Tickets By Priority", "hst") ?>
							</div>

							<canvas id="hst-dashboard-home-pieforpriority-chartplaceholder"></canvas>
							<div class="clear"></div>

						</div>

					</div>

				</div>

			</div>

		</div>
		<!-- right pane -->

	</div>

</div>
