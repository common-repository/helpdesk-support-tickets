<div id="hst-controls-settings-generic" style="display:none;">

    <!-- control body -->
    <div class="row">

        <div class="col-md-12">

            <div class="hst-panels-panel">

                <div class="hst-panels-panel-header">

                    <div class="hst-panels-panel-title"><?php _e("Generic Helpdesk Settings", "hst") ?></div>

                    <div class="clear"></div>

                </div>

                <div class="hst-panels-panel-body">

                    <div class="hst-panels-spacetopbig"></div>

                    <div class="form-group row">
                        <label class="col-sm-2"><?php _e("Support Agent:", "hst") ?></label>
                        <div class="col-sm-5">
							<div id="hst-controls-settings-generic-divDefaultAgent"></div>
                            <div id="hst-controls-settings-generic-divDefaultAgentId" style="display:none;"></div>
                            <div class="hst-btnlink" onclick="hst_shared_showmodal('users');">
                                <i class="glyphicon glyphicon-search"></i>
                                <span><?php _e("Change User", "hst") ?></span>
                            </div>
                        </div>
                        <div class="col-sm-5 hst-helptext"><?php _e("Select the User that will be considered the Helpdesk Agent. This User will be dealing with the Support Tickets, and all the Support Tickets will be assigned to this User.", "hst") ?></div>
                        <div class="clear"></div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2"><?php _e("Helpdesk Email Address:", "hst") ?> <span class="hst-asterisk">*</span></label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control hst-panels-input" id="hst-controls-settings-generic-txtHelpdeskEmailAddress" maxlength="100" />
                        </div>
                        <div class="col-sm-5 hst-helptext"><?php _e("The Email address used to send out Email Notifications and used as Recipient for Email Notifications about Customers actions.", "hst") ?></div>
                        <div class="clear"></div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2"><?php _e("Helpdesk Email Sender Name:", "hst") ?> <span class="hst-asterisk">*</span></label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control hst-panels-input" id="hst-controls-settings-generic-txtHelpdeskEmailSenderName" maxlength="100" />
                        </div>
                        <div class="col-sm-5 hst-helptext"><?php _e("This is the text that will appear in the Email software, as Sender of the Helpdesk Email Notifications.", "hst") ?></div>
                        <div class="clear"></div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2"><?php _e("Enable Attachments?", "hst") ?></label>
                        <div class="col-sm-5">
                            <input type="checkbox" class="form-control hst-panels-input-checkbox" id="hst-controls-settings-generic-chAttachmentsEnable" />
                        </div>
                        <div class="col-sm-5 hst-helptext"><?php _e("Check this option if you would like to enable Attachments for the Support Tickets.", "hst") ?></div>
                        <div class="clear"></div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2"><?php _e("Allowed Attachments Extensions:", "hst") ?></label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control hst-panels-input " id="hst-controls-settings-generic-txtAttachmentsAllowedExtensions" maxlength="500" />
                        </div>
                        <div class="col-sm-5 hst-helptext"><?php _e("A comma separated list of the allowed file extensions for the Attachments. For example: txt,doc,xls,... Note: do not allow dangerous file extensions, like php or exe.", "hst") ?></div>
                        <div class="clear"></div>
                    </div>

                    <div class="hst-panels-spacetopbig"></div>

                    <button type="button" class="btn btn-labeled btn-primary" onclick="hst_dashboard_settings_generic_backtosettingsclick();">
                        <span class="btn-label">
                            <i class="glyphicon glyphicon-chevron-left"></i>
                        </span><?php _e("Cancel", "hst") ?>
                    </button>

                    <button type="button" class="btn btn-labeled btn-primary" onclick="hst_dashboard_settings_generic_updatesettings();">
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