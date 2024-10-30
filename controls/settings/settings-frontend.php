<div id="hst-controls-settings-frontend" style="display:none;">

    <!-- control body -->
    <div class="row">

        <div class="col-md-12">

            <div class="hst-panels-panel">

                <div class="hst-panels-panel-header">

                    <div class="hst-panels-panel-title"><?php _e("Front End Configuration", "hst") ?></div>

                    <div class="clear"></div>

                </div>

                <div class="hst-panels-panel-body">

                    <div class="hst-panels-spacetopbig"></div>

					<div class="form-group row">
                        <label class="col-sm-2"><?php _e("Customer's Helpdesk Page:", "hst") ?></label>
                        <div class="col-sm-4">
                            <select id="hst-controls-settings-frontend-dd-helpdeskpage" class="hst-panels-input">
                                <option value="-1">(...)</option>
                                <?php
								$pages = get_pages();
								foreach ( $pages as $page )
								{
									$option = '<option value="' . $page->ID  . '">';
									$option .= $page->post_title;
									$option .= '</option>';
									echo $option;
								}
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-6 hst-helptext"><?php _e("Select the page of this website that will be used as 'Support' for your Customers. Please make sure that the selected page contains the <strong>[helpdesk-support-tickets]</strong> shortcode. This page will also be used as target for the links contained in the email notifications.", "hst") ?></div>
                    </div>

					<div class="clear"></div>

					<div class="hst-panels-spacetop"></div>

					<div class="clear"></div>

					<div class="form-group row">
                        <label class="col-sm-2"><?php _e("Who can Create New Tickets?", "hst") ?></label>
                        <div class="col-sm-4">
                            <select id="hst-controls-settings-frontend-dd-allowedusertype" class="hst-panels-input">
                                <option value="registered"><?php _e("Only Registered Users", "hst") ?></option>
								<option value="everyone"><?php _e("Everyone", "hst") ?></option>
                            </select>
                        </div>
                        <div class="col-sm-6 hst-helptext"><?php _e("Select 'Everyone' if you want to allow non-registered visitors to Create New Support Tickets. These visitors will be asked for name and email address in the process. No Wordpress Users will be created for visitors.", "hst") ?></div>
                    </div>

                    <div class="clear"></div>

                    <div class="hst-panels-spacetopbig"></div>

					<button type="button" class="btn btn-labeled btn-primary" onclick="hst_dashboard_settings_frontend_backtosettingsclick();">
                        <span class="btn-label">
                            <i class="glyphicon glyphicon-chevron-left"></i>
                        </span><?php _e("Cancel", "hst") ?>
                    </button>

					<button type="button" class="btn btn-labeled btn-primary" onclick="hst_dashboard_settings_frontend_updatesettings();">
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