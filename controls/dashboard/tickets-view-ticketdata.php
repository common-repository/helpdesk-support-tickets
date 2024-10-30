<div id="hst-controls-tickets-view-ticketdata" class="hst-panels-panel">

    <div class="hst-panels-panel-body">

        <div class="hst-panels-spacetop"></div>

        <div class="hst-btnlink hst-btnlink-spaceright" onclick="hst_dashboard_setHashAndNavigate('tickets-list');" id="hst-controls-tickets-view-panel-ticketevents-btn-savemessage">
            <i class="glyphicon glyphicon-chevron-left"></i>
            <span><?php _e("Go Back", "hst") ?></span>
        </div>
        <div class="hst-btnlink" onclick="hst_tickets_viewticket_deleteticketclick();" id="hst-controls-tickets-view-panel-ticketevents-btn-deleteticket">
            <i class="glyphicon glyphicon-remove"></i>
            <span><?php _e("Delete", "hst") ?></span>
        </div>

        <div class="clear"></div>

        <div class="hst-panels-sidepanelseparator"></div>

        <div class="hst-panels-panel-title hst-panels-panel-subtitle"><?php _e("Ticket Data", "hst") ?></div>

        <div class="clear"></div>
        <div class="hst-panels-spacetop"></div>

        <div class="form-group row"> 
            <label class="col-sm-4">Category:</label>
            <div class="col-md-8"><select id="hst-controls-tickets-view-ticketdata-dd-category" class="hst-panels-input"></select></div>
            <div class="clear"></div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4">Status:</label>
            <div class="col-md-8"><select id="hst-controls-tickets-view-ticketdata-dd-status" class="hst-panels-input"></select></div>
            <div class="clear"></div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4">Priority:</label>
            <div class="col-md-8"><select id="hst-controls-tickets-view-ticketdata-dd-priority" class="hst-panels-input"></select></div>
            <div class="clear"></div>
        </div>

        <div class="hst-panels-spacetop"></div>

        <button type="button" class="btn btn-labeled btn-primary" onclick="hst_tickets_viewticket_updateclick();" id="hst-controls-tickets-view-ticketdata-btn-saveticketdata">
            <span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span><?php _e("Save Ticket Data", "hst") ?>
        </button>

        <div class="clear"></div>

        <div class="hst-panels-sidepanelseparator"></div>

        <div class="hst-panels-panel-title hst-panels-panel-subtitle"><?php _e("Customer Information", "hst") ?></div>

        <div class="clear"></div>

        <div class="row hst-panels-sidepanelrowformcontrol">
            <div class="col-md-9">
                <div id="hst-controls-tickets-view-ticketdata-div-customerdisplayname" style="margin-bottom: 4px;"></div>
                <div id="hst-controls-tickets-view-ticketdata-div-customerdisplayemail" style="margin-bottom: 4px;"></div>
                <div class="hst-btnlink" onclick="hst_shared_showmodal('customers', 'ticketview');" id="hst-controls-tickets-view-home-btn-changecustomer">
                    <i class="glyphicon glyphicon-search"></i>
                    <span><?php _e("Change Customer", "hst") ?></span>
                </div>
            </div>
            <div class="col-md-3" id="hst-controls-tickets-view-ticketdata-div-customeravatar" style="text-align:right;">
            </div>
            <div class="clear"></div>
        </div>

        <div class="clear"></div>

        <div class="hst-panels-sidepanelseparator"></div>

        <div class="hst-panels-panel-title hst-panels-panel-subtitle"><?php _e("Support Agent", "hst") ?></div>

        <div class="clear"></div>

        <div class="row hst-panels-sidepanelrowformcontrol">
            <div class="col-md-9">
                <div id="hst-controls-tickets-view-ticketdata-div-agentdisplayname" style="margin-bottom: 4px;"></div>
                <div id="hst-controls-tickets-view-ticketdata-div-agentemail" style="margin-bottom: 4px;"></div>
            </div>
            <div class="col-md-3" id="hst-controls-tickets-view-ticketdata-div-agentavatar" style="text-align:right;">
            </div>
            <div class="clear"></div>
        </div>

    </div>

</div>
