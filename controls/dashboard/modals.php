<!-- User selection modal-->
<div class="md-modal md-effect-2" id="hst-dashboard-modal-users">

    <div class="md-content">

        <div class="md-header">
            <div class="md-title">
                <?php _e("Select User", "hst") ?>
            </div>
        </div>

        <div class="md-body">

            <table class="table table-striped" id="hst-dashboard-modal-users-table">
                <thead>
                    <tr>
                        <th></th>
                        <th><?php _e("Name", "hst") ?></th>
                        <th><?php _e("Email", "hst") ?></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

            <div class="hst-panels-spacetopbig"></div>

            <button type="button" class="btn btn-labeled btn-primary" onclick="hst_shared_closemodal();">
                <span class="btn-label">
                    <i class="glyphicon glyphicon-plus"></i>
                </span><?php _e("Close", "hst") ?>
            </button>

        </div>

    </div>

</div>
<!-- User selection modal-->

<!-- Customer selection modal-->
<div class="md-modal md-effect-2" id="hst-dashboard-modal-customers">

	<div class="md-content">

		<div class="md-header">
			<div class="md-title">
				<?php _e("Select Customer", "hst") ?>
			</div>
		</div>

		<div class="md-body">

			<table class="table table-striped" id="hst-dashboard-modal-customers-table">
				<thead>
					<tr>
						<th></th>
						<th><?php _e("Name", "hst") ?></th>
						<th><?php _e("Email", "hst") ?></th>
                        <th><?php _e("Action", "hst") ?></th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>

			<div class="hst-panels-spacetopbig"></div>

			<button type="button" class="btn btn-labeled btn-primary" onclick="hst_shared_closemodal();">
				<span class="btn-label">
					<i class="glyphicon glyphicon-plus"></i>
				</span><?php _e("Close", "hst") ?>
			</button>

		</div>

	</div>

</div>
<!-- Customer selection modal-->

<!-- File upload modal-->
<div class="md-modal md-effect-2" id="hst-dashboard-modal-fileupload">

    <div class="md-content">

        <div class="md-header">
            <div class="md-title">
                <?php _e("Select and Upload New Attachment", "hst") ?>
            </div>
        </div>

        <div class="md-body">

			<form id="hst-dashboard-ticketview-attachments-form" method="post" enctype="multipart/form-data" target="hst-dashboard-ticketview-attachments-iframe">
				<input type="hidden" name="entityType" value="ticket" />
				<input type="hidden" name="entityId" value="-1" id="hst-dashboard-ticketview-attachments-form-paramEntityId" />
				<input type="hidden" name="action" value="hst_ajax_dashboard_shared_uploadattachment" />
				<?php wp_nonce_field('hst', 'security'); ?>
				<div class="form-group">
					<input type="file" name="file" />
				</div>
				<input class="btn btn-labeled btn-primary" type="submit" value="Upload Attachment" id="bwhd-admin-rightpane-control-ticketview-attachments-submitfilebtn" />
			</form>

            <iframe id="hst-dashboard-ticketview-attachments-iframe" name="hst-dashboard-ticketview-attachments-iframe" height="0" width="0" frameborder="0" scrolling="yes"></iframe>

            <div class="hst-panels-spacetopbig"></div>

            <button type="button" class="btn btn-labeled btn-primary" onclick="hst_shared_closemodal();">
                <span class="btn-label">
                    <i class="glyphicon glyphicon-chevron-left"></i>
                </span><?php _e("Close", "hst") ?>
            </button>

        </div>

    </div>

</div>
<!-- User selection modal-->

<!-- File upload modal-->
<div class="md-modal md-effect-2" id="hst-dashboard-modal-addticketmessage">

	<div class="md-content">

		<div class="md-header">
			<div class="md-title">
				<?php _e("Add New Message", "hst") ?>
			</div>
		</div>

		<div class="md-body">

			<?php

			$content = '';
			$editor_id = 'hstcontrolsticketsviewpaneleditor';
			$settings  = array( 'media_buttons' => false, 'editor_height' => '150' );

			wp_editor( $content, $editor_id, $settings );

            ?>

			<div class="hst-panels-spacetopbig"></div>

			<button type="button" class="btn btn-labeled btn-primary" onclick="hst_shared_closemodal();">
				<span class="btn-label">
					<i class="glyphicon glyphicon-chevron-left"></i>
				</span><?php _e("Cancel", "hst") ?>
			</button>

			<button type="button" class="btn btn-labeled btn-primary" onclick="hst_tickets_viewticket_ticketevents_savemessageclick();">
				<span class="btn-label">
					<i class="glyphicon glyphicon-ok"></i>
				</span><?php _e("Save Message", "hst") ?>
			</button>

		</div>

	</div>

</div>
<!-- User selection modal-->

<div class="md-overlay"></div>