
<div id="hst-controls-tickets-new" style="display:none;">

    <!-- control body -->
    <div class="row">

        <div class="col-md-4">

            <div class="hst-panels-panel" style="height:306px">

                <div class="hst-panels-panel-header">

                    <div class="hst-panels-panel-title"><?php _e("1. Select Customer", "hst") ?></div>

                    <div class="clear"></div>

                </div>

                <div class="hst-panels-panel-body">

                    <input type="radio" id="hst-controls-tickets-new-customertype-new" name="hst-controls-tickets-new-customertype" value="new" onchange="hst_tickets_new_customertypeswitch('new');" class="hst-panels-input-radio">
                    <label class="hst-panels-input-radiolabel"><strong><?php _e("Create Customer", "hst") ?></strong></label>

                    <div class="hst-panels-spacetop"></div>

                    <div class="hst-panels-sidepanelrowformcontrol">
                        <div class="hst-panels-sidepanelrowformlabel"><?php _e("Customer Name", "hst") ?> <span class="hst-asterisk">*</span></div>
                        <input type="text" id="hst-controls-tickets-new-txt-customerdisplayname" class="hst-panels-input hst-panels-input-fullwidth" maxlength="100" />
                        <div class="clear"></div>
                    </div>
                    <div class="hst-panels-spacetop"></div>
                    <div class="hst-panels-sidepanelrowformcontrol">
                        <div class="hst-panels-sidepanelrowformlabel"><?php _e("Customer Email Address", "hst") ?> <span class="hst-asterisk">*</span></div>
                        <input type="text" id="hst-controls-tickets-new-txt-customeremail" class="hst-panels-input hst-panels-input-fullwidth" maxlength="100" />
                        <div class="clear"></div>
                    </div>

                    <div class="clear"></div>

                    <div class="hst-panels-spacetop"></div>

                    <input type="radio" id="hst-controls-tickets-new-customertype-existing" name="hst-controls-tickets-new-customertype" value="existing" onchange="hst_tickets_new_customertypeswitch('existing');" class="hst-panels-input-radio">
                    <label class="hst-panels-input-radiolabel"><strong><?php _e("Existing Customer", "hst") ?></strong></label>

                    <div class="hst-panels-sidepanelrowformcontrol">

                        <div id="hst-controls-tickets-new-divCustomer" style="margin-top:8px;margin-bottom:10px;"><?php _e("(no customer selected)", "hst") ?></div>
                        <div id="hst-controls-tickets-new-divCustomerId" style="display:none;"></div>
                        <div class="hst-btnlink" onclick="hst_shared_showmodal('customers', 'ticketnew');" id="hst-controls-tickets-new-btnselectcustomer">
                            <i class="glyphicon glyphicon-search"></i>
                            <span><?php _e("Select Existing Customer", "hst") ?></span>
                        </div>

                    </div>

                    <div class="clear"></div>

                </div>
            </div>

        </div>

        <div class="col-md-4 nopadding-left">

            <div class="hst-panels-panel" style="height:306px">

                <div class="hst-panels-panel-header">

                    <div class="hst-panels-panel-title"><?php _e("2. Problem Details", "hst") ?></div>

                    <div class="clear"></div>

                </div>

                <div class="hst-panels-panel-body">

                    <div class="hst-panels-sidepanelrowformcontrol">
                        <div class="hst-panels-sidepanelrowformlabel"><?php _e("Ticket Title", "hst") ?> <span class="hst-asterisk">*</span></div>
                        <input type="text" id="hst-controls-tickets-new-txt-tickettitle" class="hst-panels-input hst-panels-input-fullwidth" maxlength="50" />
                        <div class="clear"></div>
                    </div>

                    <div class="hst-panels-spacetop"></div>

                    <div class="hst-panels-sidepanelrowformcontrol">
                        <div class="hst-panels-sidepanelrowformlabel"><?php _e("Problem Description", "hst") ?> <span class="hst-asterisk">*</span></div>
                        <textarea type="text" id="hst-controls-tickets-new-txt-ticketproblem" class="hst-panels-input hst-panels-input-fullwidth"></textarea>
                        <div class="clear"></div>
                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-4 nopadding-left">

            <div id="hst-controls-tickets-new-panelotherinfo" class="hst-panels-panel" style="height:306px">

                <div class="hst-panels-panel-header">

                    <div class="hst-panels-panel-title"><?php _e("3. Other Info", "hst") ?></div>

                    <div class="clear"></div>

                </div>

                <div class="hst-panels-panel-body">

                    <div class="hst-panels-sidepanelrowformcontrol">
                        <div class="hst-panels-sidepanelrowformlabel"><?php _e("Category", "hst") ?> <span class="hst-asterisk">*</span></div>
                        <select id="hst-controls-tickets-new-dd-category" class="hst-panels-input"></select>
                        <div class="clear"></div>
                    </div>

                    <div class="hst-panels-spacetop"></div>

                    <div class="hst-panels-sidepanelrowformcontrol">
                        <div class="hst-panels-sidepanelrowformlabel"><?php _e("Priority", "hst") ?> <span class="hst-asterisk">*</span></div>
                        <select id="hst-controls-tickets-new-dd-priority" class="hst-panels-input"></select>
                        <div class="clear"></div>
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="row">

        <div class="col-md-12">

            <div class="hst-panels-panel">

                <div class="hst-panels-panel-body">

					<div class="hst-panels-spacetop"></div>

                    <button id="hst-controls-tickets-new-btn-cancel" type="button" class="btn btn-labeled btn-primary" onclick="hst_tickets_new_backtolist();">
                        <span class="btn-label"><i class="glyphicon glyphicon-chevron-left"></i></span><?php _e("Cancel", "hst") ?>
                    </button>

                    <button id="hst-controls-tickets-new-btn-saveticket" type="button" class="btn btn-labeled btn-primary" onclick="hst_tickets_new_saveticket();">
                        <span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span><?php _e("Save Support Ticket", "hst") ?>
                    </button>

                    <div class="clear"></div>

                </div>

			</div>

        </div>

    </div>
    <!-- control body -->

</div>
