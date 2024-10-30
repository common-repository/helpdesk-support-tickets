<div id="hst-fe-viewticket" style="display:none;" class="hst-frontend-panel-default">

    <div class="hst-frontend-panel-default-title">
        <?php _e("Review your Support Ticket", "hst") ?>
    </div>

    <div class="hst-frontend-panel-parag">
		<?php _e("These are the details of your Support Request. You are able to add a message by using the text box at the bottom.", "hst") ?>        
    </div>

	<div class="row hst-ticketsview-datablock">
		<div class="col-md-3"><div class="hst-ticketsview-label"><?php _e("Number:", "hst") ?></div></div>
        <div class="col-md-9"><div id="hst-fe-viewticket-div-ticketid" class="hst-ticketsview-label-value">&nbsp;</div></div>
	</div>
	<div class="clear"></div>
    <div class="row hst-ticketsview-datablock">
        <div class="col-md-3"><div class="hst-ticketsview-label"><?php _e("Problem:", "hst") ?></div></div>
        <div class="col-md-9"><div id="hst-fe-viewticket-div-tickettitle" class="hst-ticketsview-label-value">&nbsp;</div></div>
    </div>
    <div class="clear"></div>
    <div class="row hst-ticketsview-datablock">
        <div class="col-md-3"><div class="hst-ticketsview-label"><?php _e("Created On:", "hst") ?></div></div>
        <div class="col-md-9"><div id="hst-fe-viewticket-div-ticketcreateddate" class="hst-ticketsview-label-value">&nbsp;</div></div>
    </div>
    <div class="clear"></div>
    <div class="row hst-ticketsview-datablock">
        <div class="col-md-3"><div class="hst-ticketsview-label"><?php _e("Last Updated:", "hst") ?></div></div>
        <div class="col-md-9"><div id="hst-fe-viewticket-div-ticketlastupdateddate" class="hst-ticketsview-label-value">&nbsp;</div></div>
    </div>    
    <div class="clear"></div>

	<div class="hst-ticketsview-spacer"></div>

    <div class="hst-frontend-panel-parag">
        <?php _e("Below is the messages list between you and our Support Agent. Please use the controls at the bottom if you need to add a New Message.", "hst") ?>
    </div>

    <div id="hst-fe-viewticket-div-messagesplaceholder"></div>
    <div id="hst-fe-viewticket-messages-norecords" class="hst-fe-information" style="display:none;">
        <i class="fa fa-info"></i>
        <span><?php _e("No Messages for this Support Ticket yet.", "hst") ?></span>
    </div>

    <div style="clear:both;"></div>
    <div class="hst-ticketsview-spacer"></div>
    <div style="clear:both;"></div>

    <div class="hst-frontend-panel-parag">
        <?php _e("Add New Message if you want to add information or reply to our Support Agent:", "hst") ?>
    </div>

	<textarea id="hst_fe_viewticket-txt-newmessage" style="width:100% !important;"></textarea>

    <div style="clear:both;"></div>

    <div class="hst-button" onclick="hst_fe_viewticket_sendmessageclick();" style="margin-top:5px;">
        <i class="fa fa-button fa-send hst-icon-sendmessage"></i>
        <span><?php _e("Send Message", "hst") ?></span>
    </div>

    <div style="clear:both;"></div>

    <div id="hst-fe-ticketview-div-validation" class="hst-fe-validation" style="display:none;">
		<i class="fa fa-warning"></i>
        <span id="hst-fe-ticketview-div-validationmessage"></span>
    </div>

    <div style="clear:both;"></div>
    <div class="hst-ticketsview-spacer"></div>
    <div style="clear:both;"></div>

    <div class="hst-button" onclick="hst_fe_viewticket_goback();">
        <i class="fa fa-button fa-arrow-left"></i>
        <span><?php _e("Go Back", "hst") ?></span>
    </div>

    <div style="clear:both;"></div>

</div>
