<div id="hst-controls-settings-system" style="display:none;">


    <!-- control body -->
    <div class="row">

        <div class="col-md-12">

            <div class="hst-panels-panel">

                <div class="hst-panels-panel-header">

                    <div class="hst-panels-panel-title"><?php _e("Helpdesk System Settings", "hst") ?></div>

                    <div class="clear"></div>

                </div>

                <div class="hst-panels-panel-body">

                    <!-- main tab strip -->
                    <ul class="nav nav-tabs hst-subnavbar">
                        <li id="hst-controls-settings-system-tab-settings" onclick="hst_dashboard_settings_system_TabClick('settings')">
                            <a><?php _e("System Settings", "hst") ?></a>
                        </li>
                        <li id="hst-controls-settings-system-tab-logview" onclick="hst_dashboard_settings_system_TabClick('logviewer')">
                            <a><?php _e("Log Viewer", "hst") ?></a>
                        </li>
                    </ul>
                    <!-- main tab strip -->

                    <!-- panels -->

                    <div id="hst-controls-settings-system-panel-settings" class="hst-panels-navbartabbedconent">

                        <div class="hst-panels-spacetopbig"></div>
						
						<div class="form-group row">
							<label class="col-sm-4"><?php _e("Delete Table Content when Uninstall?", "hst") ?></label>
							<div class="col-sm-4">
								<input type="checkbox" id="hst-controls-settings-system-chk-deletetableuninstall" />
							</div>
							<div class="col-sm-4 hst-helptext">
								<?php _e("Check this box if you want the tables created from this plugin to be deleted when uninstalling the plugin itself.", "hst") ?>
								<br />
								<span style="color:red;">
									<strong><?php _e("Note: You will lose the Helpdesk plugin content if you check this box and uninstall the plugin!", "hst") ?></strong>
								</span>
							</div>
						</div>

                        <div class="clear"></div>
						<div style="height:20px;"></div>

                        <div class="form-group row">
                            <label class="col-sm-4"><?php _e("Select the Events to record in the System Log:", "hst") ?></label>
                            <div class="col-sm-4">
                                <input type="checkbox" id="hst-controls-settings-system-chk-logerrors" /><?php _e("Errors", "hst") ?>
                                <div class="clear"></div>
                                <input type="checkbox" id="hst-controls-settings-system-chk-logemails" /><?php _e("Email Notifications", "hst") ?>
                                <div class="clear"></div>
                                <input type="checkbox" id="hst-controls-settings-system-chk-logdebuginfo" /><?php _e("Debug Info", "hst") ?>
                            </div>
                            <div class="col-sm-4 hst-helptext"><?php _e("Select the events you would like to record in the System Event log file. Note: if you select 'Debug Info', the log file will drastically grow in size. Please use this option ONLY for debugging purpouses.", "hst") ?></div>
                        </div>

                        <div class="clear"></div>

                    </div>

                    <div id="hst-controls-settings-system-panel-logviewer" class="hst-panels-navbartabbedconent">

                        <div class="hst-panels-spacetopbig"></div>

                        <div class="hst-btnlink hst-btnlink-spaceright" onclick="hst_dashboard_settings_system_logview_loadlog();" >
                            <i class="glyphicon glyphicon-eye-open"></i>
                            <span><?php _e("View Event Log Content", "hst") ?></span>
                        </div>

                        <div class="hst-btnlink hst-btnlink-spaceright" onclick="hst_dashboard_settings_system_logview_clearlog();" >
                            <i class="glyphicon glyphicon-remove"></i>
                            <span><?php _e("Clear Event Log File", "hst") ?></span>
                        </div>

						<a class="hst-btnlink" href="<?php echo hst_consts_pluginUrl; ?>utils\log\log.txt" download>
							<i class="glyphicon glyphicon-download-alt"></i>
							<span><?php _e("Download Log File", "hst") ?></span>
						</a>

                        <div class="clear"></div>

                        <div class="hst-panels-spacetop"></div>

                        <table id="hst-controls-settings-system-panel-logviewer-table" class="table table-striped">
                            <thead>
                                <tr>
                                    <th><?php _e("Date", "hst") ?></th>
                                    <th><?php _e("Method", "hst") ?></th>
                                    <th><?php _e("Type", "hst") ?></th>
                                    <th><?php _e("Message / Details", "hst") ?></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                    </div>

                    <!-- panels -->

                    <div class="clear"></div>

                    <div class="hst-panels-spacetopbig"></div>

                    <button type="button" class="btn btn-labeled btn-primary" onclick="hst_dashboard_settings_system_backtosettingsclick();">
                        <span class="btn-label">
                            <i class="glyphicon glyphicon-chevron-left"></i>
                        </span><?php _e("Cancel", "hst") ?>
                    </button>

                    <button type="button" class="btn btn-labeled btn-primary" onclick="hst_dashboard_settings_system_saveclick();">
                        <span class="btn-label">
                            <i class="glyphicon glyphicon-ok"></i>
                        </span><?php _e("Save Changes", "hst") ?>
                    </button>

                    <div class="clear"></div>

                </div>

            </div>

        </div>

    </div>
    <!-- control body -->

</div>