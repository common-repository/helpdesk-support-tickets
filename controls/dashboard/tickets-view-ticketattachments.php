
<div class="hst-panels-panel hst-panels-tabpanel">

	<div class="hst-panels-panel-body">

        <div class="hst-panels-spacetop"></div>
        <div class="clear"></div>

		<table class="table table-striped" id="hst-controls-tickets-view-attachments">
			<thead>
				<tr>
					<th><?php _e("File", "hst") ?></th>
					<th><?php _e("Uploaded By", "hst") ?></th>
					<th><?php _e("Uploaded On", "hst") ?></th>
					<th><?php _e("Size", "hst") ?></th>
					<th><?php _e("Action", "hst") ?></th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
        <div id="hst-controls-tickets-view-panel-ticketattachments-nodata" style="display:none;" class="hst-panels-greysubpanel text-center">
            <div class="hst-panels-greysubpanel-bigicon">
                <i class="fa fa-info-circle"></i>
            </div>
            <div class="clear"></div>
            <?php _e("No Attachments Support Ticket yet.", "hst") ?><br />
            <?php _e("If you want to Add a new Attachment, please click the button below.", "hst") ?>
        </div>

        <div class="hst-panels-spacetop"></div>
        <div class="clear"></div>

        <div class="hst-btnlink" onclick="hst_shared_showmodal('uploadattachment', '');" id="hst-controls-tickets-view-panel-ticketattachments-btn-saveattachment">
            <i class="glyphicon glyphicon-plus"></i>
            <span><?php _e("Upload New Attachment", "hst") ?></span>
        </div>

        <div class="clear"></div>
        <div class="hst-panels-spacetop"></div>

	</div>

</div>

<div class="clear"></div>
