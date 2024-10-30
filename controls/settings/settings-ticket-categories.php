<div id="hst-controls-settings-ticket-categories" style="display:none;">


    <!-- control body (list panel) -->
    <div class="row" id="hst-controls-settings-ticket-categories-listPanel">

        <div class="col-md-12">

            <div class="hst-panels-panel">

                <div class="hst-panels-panel-header">

                    <div class="hst-panels-panel-title"><?php _e("List of Support Ticket Categories", "hst") ?></div>

                    <div class="clear"></div>

                </div>

                <div class="hst-panels-panel-body">

                    <table id="hst-controls-settings-ticket-categories-table" class="table table-striped">
                        <thead>
                            <tr>
                                <th><?php _e("Category Name", "hst") ?></th>
                                <th class="text-right"><?php _e("Edit", "hst") ?></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <div class="clear"></div>

                    <button type="button" class="btn btn-labeled btn-primary" onclick="hst_dashboard_settings_ticket_categories_backtosettingsclick();">
                        <span class="btn-label">
                            <i class="glyphicon glyphicon-chevron-left"></i>
                        </span><?php _e("Cancel", "hst") ?>
                    </button>

                    <button type="button" class="btn btn-labeled btn-primary" onclick="hst_dashboard_settings_ticket_categories_editclick(-1);">
                        <span class="btn-label">
                            <i class="glyphicon glyphicon-ok"></i>
                        </span><?php _e("Create New Ticket Category", "hst") ?>
                    </button>

                    <div class="clear"></div>

                </div>

            </div>

        </div>

    </div>
    <!-- control body (list panel)  -->
    <!-- control body (edit panel) -->
    <div class="row" id="hst-controls-settings-ticket-categories-editPanel" style="display:none;">

        <div class="col-md-12">

            <div class="hst-panels-panel">

                <div class="hst-panels-panel-header">

                    <div class="hst-panels-panel-title"><?php _e("Create / Edit Support Ticket Category", "hst") ?></div>

                    <div class="clear"></div>

                </div>

                <div class="hst-panels-panel-body">

                    <div class="hst-panels-spacetopbig"></div>

                    <div class="form-group row">
                        <label  class="col-sm-2"><?php _e("Ticket Category Name:", "hst") ?> <span class="hst-asterisk">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control hst-panels-input" id="hst-controls-settings-ticket-categories-editPanel-txtName" maxlength="200" />
                        </div>
                        <div class="col-sm-4 hst-helptext"><?php _e("The name / description for the Support Category", "hst") ?></div>
                    </div>

                    <div class="clear"></div>

                    <div class="hst-panels-spacetopbig"></div>

                    <button type="button" class="btn btn-labeled btn-primary" onclick="hst_dashboard_settings_ticket_categories_edit_gobackclick();">
                        <span class="btn-label">
                            <i class="glyphicon glyphicon-chevron-left"></i>
                        </span><?php _e("Back to List", "hst") ?>
                    </button>
                    <button type="button" class="btn btn-labeled btn-primary" onclick="hst_dashboard_settings_ticket_categories_edit_saveclick();">
                        <span class="btn-label">
                            <i class="glyphicon glyphicon-ok"></i>
                        </span><?php _e("Save Changes", "hst") ?>
                    </button>
                    <button type="button" class="btn btn-labeled btn-primary" onclick="hst_dashboard_settings_ticket_categories_edit_deleteclick();">
                        <span class="btn-label">
                            <i class="glyphicon glyphicon-remove"></i>
                        </span><?php _e("Delete", "hst") ?>
                    </button>

                    <div class="clear"></div>

                </div>

            </div>

        </div>

    </div>
    <!-- control body (edit panel)  -->

</div>
