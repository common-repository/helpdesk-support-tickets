<?php

//instantiate an object of notification class that will be used to fill the table
$classNotification = new hst_Classes_Notification();
$notificationTicketCreateCustomer = $classNotification->GetNotificationDataByKey( array( "NotificationKey" => 'TicketCreateCustomer' ) );
$notificationTicketCreateAgent = $classNotification->GetNotificationDataByKey( array( "NotificationKey" => 'TicketCreateAgent' ) );;
$notificationMessageCreateCustomer = $classNotification->GetNotificationDataByKey( array( "NotificationKey" => 'MessageCreateCustomer' ) );;
$notificationMessageCreateAgent = $classNotification->GetNotificationDataByKey( array( "NotificationKey" => 'MessageCreateAgent' ) );;

?>

<div id="hst-controls-settings-notifications" style="display:none;">

	<!-- control body (list panel) -->
	<div class="row" id="hst-controls-settings-notifications-listPanel">

		<div class="col-md-12">

			<div class="hst-panels-panel">

				<div class="hst-panels-panel-header">

					<div class="hst-panels-panel-title"><?php _e("List of Email Notifications", "hst") ?></div>

					<div class="clear"></div>

				</div>

				<div class="hst-panels-panel-body">

					<table id="hst-controls-settings-ticket-notifications-table" class="table table-striped">
						<thead>
							<tr>
								<th><?php _e("Event", "hst") ?></th>
								<th><?php _e("Recipient", "hst") ?></th>
								<th><?php _e("Description", "hst") ?></th>
								<th class="text-center"><?php _e("Edit", "hst") ?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<?php echo $notificationTicketCreateCustomer["NotificationName"]; ?>
								</td>
								<td>
									<?php echo $notificationTicketCreateCustomer["NotificationRecipient"]; ?>
								</td>
								<td>
									<?php echo $notificationTicketCreateCustomer["NotificationDescription"]; ?>
								</td>
								<td class="text-center">
									<button type="button" class="btn btn-labeled btn-primary btn-table" onclick="hst_dashboard_settings_notifications_editclick('TicketCreateCustomer');">
										<i class="glyphicon glyphicon-pencil"></i>
									</button>
								</td>
							</tr>
							<tr>
								<td>
									<?php echo $notificationTicketCreateAgent["NotificationName"]; ?>
								</td>
								<td>
									<?php echo $notificationTicketCreateAgent["NotificationRecipient"]; ?>
								</td>
								<td>
									<?php echo $notificationTicketCreateAgent["NotificationDescription"]; ?>
								</td>
								<td class="text-center">
									<button type="button" class="btn btn-labeled btn-primary btn-table" onclick="hst_dashboard_settings_notifications_editclick('TicketCreateAgent');">
										<i class="glyphicon glyphicon-pencil"></i>
									</button>
								</td>
							</tr>
							<tr>
								<td>
									<?php echo $notificationMessageCreateCustomer["NotificationName"]; ?>
								</td>
								<td>
									<?php echo $notificationMessageCreateCustomer["NotificationRecipient"]; ?>
								</td>
								<td>
									<?php echo $notificationMessageCreateCustomer["NotificationDescription"]; ?>
								</td>
								<td class="text-center">
									<button type="button" class="btn btn-labeled btn-primary btn-table" onclick="hst_dashboard_settings_notifications_editclick('MessageCreateCustomer');">
										<i class="glyphicon glyphicon-pencil"></i>
									</button>
								</td>
							</tr>
							<tr>
								<td>
									<?php echo $notificationMessageCreateAgent["NotificationName"]; ?>
								</td>
								<td>
									<?php echo $notificationMessageCreateAgent["NotificationRecipient"]; ?>
								</td>
								<td>
									<?php echo $notificationMessageCreateAgent["NotificationDescription"]; ?>
								</td>
								<td class="text-center">
									<button type="button" class="btn btn-labeled btn-primary btn-table" onclick="hst_dashboard_settings_notifications_editclick('MessageCreateAgent');">
										<i class="glyphicon glyphicon-pencil"></i>
									</button>
								</td>
							</tr>
						</tbody>
					</table>

					<div class="clear"></div>

					<div class="hst-panels-spacetop"></div>

					<button type="button" class="btn btn-labeled btn-primary" onclick="hst_dashboard_settings_notifications_backtosettingsclick();">
						<span class="btn-label">
							<i class="glyphicon glyphicon-chevron-left"></i>
						</span><?php _e("Cancel", "hst") ?>
					</button>

					<div class="clear"></div>

				</div>

			</div>

		</div>

	</div>
	<!-- control body (list panel)  -->

	<!-- control body (edit panel) -->
	<div class="row" id="hst-controls-settings-notifications-editPanel" style="display:none;">

		<div class="col-md-12">

			<div class="hst-panels-panel">

				<div class="hst-panels-panel-header">

					<div class="hst-panels-panel-title"><?php _e("Manage Email Notification Details", "hst") ?></div>

					<div class="clear"></div>

				</div>

				<div class="hst-panels-panel-body">

					<!-- main tab strip -->
					<ul class="nav nav-tabs hst-subnavbar">
						<li id="hst-controls-settings-notifications-editPanel-tab-settings" onclick="hst_dashboard_settings_notifications_editPanel_TabClick('settings')">
							<a><?php _e("Notification Settings", "hst") ?></a>
						</li>
						<li id="hst-controls-settings-notifications-editPanel-tab-template" onclick="hst_dashboard_settings_notifications_editPanel_TabClick('template')" style="display:none;">
							<a><?php _e("Edit Email Template", "hst") ?></a>
						</li>
						<li id="hst-controls-settings-notifications-editPanel-tab-test" onclick="hst_dashboard_settings_notifications_editPanel_TabClick('test')">
							<a><?php _e("Test Notification", "hst") ?></a>
						</li>
					</ul>
					<!-- main tab strip -->

					<!-- panels -->
					<div id="hst-controls-settings-notifications-editPanel-panel-settings" class="hst-panels-navbartabbedconent">

						<div class="hst-panels-spacetopbig"></div>

						<div class="form-group row">
							<label class="col-sm-2"><?php _e("Notification Name:", "hst") ?></label>
							<div class="col-sm-6">
								<div id="hst-controls-settings-notifications-editPanel-div-notificationName"></div>
							</div>
							<div class="col-sm-4"></div>
							<div class="clear"></div>
						</div>

						<div class="form-group row">
							<label class="col-sm-2"><?php _e("Recipient:", "hst") ?></label>
							<div class="col-sm-6">
								<div id="hst-controls-settings-notifications-editPanel-div-notificationRecipient"></div>
							</div>
							<div class="col-sm-4"></div>
							<div class="clear"></div>
						</div>

						<div class="form-group row">
							<label class="col-sm-2"><?php _e("Event Description:", "hst") ?></label>
							<div class="col-sm-6">
								<div id="hst-controls-settings-notifications-editPanel-div-notificationDescription"></div>
							</div>
							<div class="col-sm-4"></div>
							<div class="clear"></div>
						</div>

						<div class="form-group row">
							<label class="col-sm-2"><?php _e("Enabled ?", "hst") ?></label>
							<div class="col-sm-6">
								<input type="checkbox" id="hst-controls-settings-notifications-editPanel-chk-notificationEnabled" />
							</div>
							<div class="col-sm-4"></div>
							<div class="clear"></div>
						</div>

					</div>

					<div id="hst-controls-settings-notifications-editPanel-panel-template" class="hst-panels-navbartabbedconent">
						template editing
					</div>

					<div id="hst-controls-settings-notifications-editPanel-panel-test" class="hst-panels-navbartabbedconent">

						<div class="hst-panels-spacetopbig"></div>

						<div class="form-group row">
							<label class="col-sm-2"><?php _e("Sender Email Address:", "hst") ?> <span class="hst-asterisk">*</span></label>
							<div class="col-sm-6">
								<input type="text" id="hst-controls-tickets-new-txt-customerdisplayname" class="hst-panels-input hst-panels-input-fullwidth" maxlength="100" />
							</div>
                            <div class="col-sm-4 hst-helptext"><?php _e("The email that will be used as sender for this test. Usually, this is the admin's email address.", "hst") ?></div>
							<div class="clear"></div>
						</div>

						<div class="form-group row">
							<label class="col-sm-2"><?php _e("Recipient Email Address:", "hst") ?> <span class="hst-asterisk">*</span></label>
							<div class="col-sm-6">
								<input type="text" id="hst-controls-tickets-new-txt-testrecipientemailaddress" class="hst-panels-input hst-panels-input-fullwidth" maxlength="100" />
							</div>
                            <div class="col-sm-4 hst-helptext"><?php _e("This is the email address that the test will be sent to. Usually, this is your email address.", "hst") ?></div>
							<div class="clear"></div>
						</div>

						<div class="form-group row">
							<label class="col-sm-2"><?php _e("Send Email:", "hst") ?></label>
							<div class="col-sm-6">
								<button type="button" class="btn btn-labeled btn-primary" onclick="hst_dashboard_settings_notifications_sendtestemailclick();">
									<span class="btn-label">
										<i class="glyphicon glyphicon-send"></i>
									</span><?php _e("Send Test Email", "hst") ?>
								</button>
							</div>
                            <div class="col-sm-4 hst-helptext"></div>
							<div class="clear"></div>
						</div>

						<div class="clear"></div>

					</div>
					<!-- panels -->

				</div>

			</div>

			<div class="hst-panels-panel">

                <div class="hst-panels-panel-body">

					<div class="clear"></div>

					<div class="hst-panels-spacetop"></div>

					<button type="button" class="btn btn-labeled btn-primary" onclick="hst_dashboard_settings_notifications_backtolistclick();">
						<span class="btn-label">
							<i class="glyphicon glyphicon-chevron-left"></i>
						</span><?php _e("Cancel", "hst") ?>
					</button>
					<button type="button" class="btn btn-labeled btn-primary" onclick="hst_dashboard_settings_notifications_savedetailsclick();">
						<span class="btn-label">
							<i class="glyphicon glyphicon-ok"></i>
						</span><?php _e("Save Changes", "hst") ?>
					</button>

					<div class="clear"></div>

				</div>

			</div>

		</div>

	</div>
	<!-- control body (edit panel) -->

</div>
