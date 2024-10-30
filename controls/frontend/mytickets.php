<div id="hst-fe-mytickets" style="display:none;" class="hst-frontend-panel-default">

    <div class="hst-frontend-panel-default-title">
        <?php _e("My Support Tickets", "hst") ?>
    </div>

    <div class="hst-frontend-panel-parag">
        <?php _e("This is a list of the Support Tickets that you have created. Click on the pencil icon to view the details and progress of the solution process. To create a new Support Ticket, click on the link at the bottom.", "hst") ?>
    </div>

    <div id="hst-fe-mytickets-ticketsplaceholder" style="display:none;"></div>
    <div id="hst-fe-mytickets-norecords" class="hst-fe-information" style="display:none;">
        <i class="fa fa-info"></i>
        <span><?php _e("No Support Tickets found", "hst") ?></span>
    </div>

    <div class="hst-button-genericbar" style="margin-top:25px;">

        <div class="hst-button" onclick="hst_fe_mytickets_goback();">
            <i class="fa fa-button fa-arrow-left"></i>
            <span><?php _e("Go Back", "hst") ?></span>
        </div>

        <div class="hst-button" onclick="hst_fe_mytickets_create();">
            <i class="fa fa-button fa-file-text hst-icon-createticket" ></i>
            <span><?php _e("Create New Ticket", "hst") ?></span>
        </div>

    </div>

    <div style="clear:both;"></div>

</div>
