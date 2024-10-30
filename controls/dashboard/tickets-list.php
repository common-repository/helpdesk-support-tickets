
<div id="hst-controls-tickets-list" style="display:none;">
	
	<!-- control body -->
	<div class="row">

		<div class="col-md-3">

			<!-- simple search panel -->
			<?php include( hst_consts_pluginPath . '/controls/dashboard/tickets-list-simplesearch.php' ); ?>
			<!-- simple search panel -->

		</div>

		<div class="col-md-9 nopadding-left ">

            <div class="hst-panels-panel">

                <div class="hst-panels-panel-header">

                    <div class="hst-panels-panel-title"><?php _e("Search Result for Support Tickets", "hst") ?></div>

					<div class="hst-panels-panel-buttonsheader">
						<button type="button" class="btn btn-labeled btn-primary" onclick="hst_tickets_listtickets_newticket();">
							<span class="btn-label">
								<i class="glyphicon glyphicon-plus"></i>
							</span><?php _e("New Support Ticket", "hst") ?>
						</button>
					</div>

					<div class="clear"></div>

                </div>

                <div class="hst-panels-panel-body">

                    <!-- result header -->
                    <div class="hst-panels-spacebottom">

                        <div id="hst-controls-tickets-list-resultpane-resultcounter" class="pull-left" style="margin-top: 3px;">
                            <?php _e("Search returned", "hst") ?>
                            <span id="hst-controls-tickets-list-resultpane-resultcounter-span"></span>
                            <?php _e("support ticket(s).", "hst") ?>
                        </div>

                        <div class="clear"></div>

                    </div>
                    <!-- result header -->

                    <!-- result body -->

                    <table class="table table-striped" id="hst-controls-tickets-list-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php _e("Customer", "hst") ?></th>
                                <th><?php _e("Problem", "hst") ?></th>
                                <th><?php _e("Category", "hst") ?></th>
                                <th><?php _e("Status", "hst") ?></th>
                                <th><?php _e("Updated", "hst") ?></th>
                                <th><?php _e("Action", "hst") ?></th>
                            </tr>
                        </thead>
                        <tbody>
						</tbody>
					</table>

					<div id="hst-controls-tickets-list-nodata" style="display:none;" class="hst-panels-greysubpanel text-center">
						<div class="hst-panels-greysubpanel-bigicon">
							<i class="fa fa-info-circle"></i>
						</div>
						<div class="clear"></div>
						<?php _e("The search returned no result or the Support Tickets database is empty.", "hst") ?><br /><?php _e("Review your search filter or create a New Support Ticket by using the button at top right of this screen.", "hst") ?>
					</div>
					
                    <div class="clear"></div>

                    <!-- result body -->

                </div>

            </div>

        </div>

	</div>
	<!-- control body -->

</div>