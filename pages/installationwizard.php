<?php

//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}

?>

<!-- Using Open Sans font from google fonts archive -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" />

<!-- Main container -->
<div class="hst container hst-installationwizard-container">

    <!-- Main Title -->
	<div class="hst-installationwizard-maintitle"><?php _e("Helpdesk &amp; Support Tickets", "hst") ?></div>
    <div class="hst-installationwizard-maintitle2"><?php _e("Installation Wizard", "hst") ?></div>
    <!-- Main Title -->

	<!-- Steps -->
	<div class="hst-installationwizard-steps">
        <div class="hst-installationwizard-steps-progress" >
            <div class="hst-installationwizard-steps-line" id="hst-installationwizard-progressline"></div>
        </div>
        <div class="hst-installationwizard-step" id="hst-installationwizard-stepWelcome">
            <div class="hst-installationwizard-step-icon"><i class="fa fa-flag"></i></div>
            <p><?php _e("welcome", "hst") ?></p>
        </div>
        <div class="hst-installationwizard-step" id="hst-installationwizard-stepFrontpage">
            <div class="hst-installationwizard-step-icon"><i class="fa fa-file-text-o"></i></div>
            <p><?php _e("frontpage", "hst") ?></p>
        </div>
        <div class="hst-installationwizard-step" id="hst-installationwizard-stepNotifications">
            <div class="hst-installationwizard-step-icon"><i class="fa fa-envelope-o"></i></div>
            <p><?php _e("notifications", "hst") ?></p>
        </div>
        <div class="hst-installationwizard-step" id="hst-installationwizard-stepAttachments">
            <div class="hst-installationwizard-step-icon"><i class="fa fa-paperclip"></i></div>
            <p><?php _e("attachments", "hst") ?></p>
        </div>
        <div class="hst-installationwizard-step" id="hst-installationwizard-stepThankYou">
            <div class="hst-installationwizard-step-icon"><i class="fa fa-heart"></i></div>
            <p><?php _e("finish", "hst") ?></p>
        </div>
	</div>
    <!-- Steps -->

	<!-- Panel Welcome -->
	<div id="hst-installationwizard-panel-welcome" class="hst-installationwizard-panel" style="display:none;">

		<div class="hst-installationwizard-panel-title">
			<?php _e("Welcome", "hst") ?>
		</div>

		<div class="hst-installationwizard-panel-body">
            <?php _e("A big 'Thank you!' for choosing Helpdesk &amp; Support Tickets plugin. This wizard will guide you through the installation process and will help you setting up the minimal configuration in order to start supporting your customers immediately. You can modify this configuration, as well as find other settings, at any time by clicking on the 'Settings' menu.<br /><br />Click on the button below to start.", "hst") ?>
		</div>

		<div class="clear"></div>

		<div class="hst-installationwizard-buttonscontainer">

			<button type="button" class="btn btn-labeled btn-primary" onclick="hst_installationwizard_panel_welcome_click_continue();">
				<span class="btn-label">
					<i class="glyphicon glyphicon-play"></i>
				</span><?php _e("Start Installation", "hst") ?>
			</button>

		</div>

	</div>
	<!-- Panel Welcome -->

	<!-- Panel Frontpage -->
	<div id="hst-installationwizard-panel-frontpage" class="hst-installationwizard-panel" style="display:none;">

		<div class="hst-installationwizard-panel-title">
			<?php _e("Helpdesk Front Page", "hst") ?>
		</div>

		<div class="hst-installationwizard-panel-body">

            <div class="form-group row">
                <label class="col-sm-3">Front Page (for your Customers):</label>
                <div class="col-sm-4">
                    <select id="hst-installationwizard-panel-frontpage-dd-mode" class="hst-panels-input hst-panels-input-fullwidth" onchange="hst_installationwizard_panel_frontpage_ddmode_change();">
						<option value="new"><?php _e("Create a New Page", "hst") ?></option>
                        <option value="existing"><?php _e("Use an Existing Page", "hst") ?></option>
                        <option value="none"><?php _e("Do not use Front End", "hst") ?></option>
					</select>
                </div>
                <div class="col-sm-5 hst-helptext"><?php _e("The plugin will use a page of your website to collect Support Requests from your Customer. Please tell if you would like a new Page to be created or if you already have a support Page.", "hst") ?></div>
                <div class="clear"></div>
            </div>

			<div class="form-group row" id="hst-installationwizard-panel-frontpage-mode-existing" style="display:none;">
				<label class="col-sm-3"><?php _e("Select Front Page:", "hst") ?></label>
				<div class="col-sm-4">
					<select id="hst-installationwizard-panel-frontpage-dd-page" class="hst-panels-input hst-panels-input-fullwidth">
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
				<div class="col-sm-5 hst-helptext"><?php _e("Select the existing Page that will be used as Helpdesk Front End page. Note: you will need to manually add the shortcode:<br /><strong>[helpdesk-support-tickets]</strong>", "hst") ?></div>
				<div class="clear"></div>
			</div>

            <div class="form-group row" id="hst-installationwizard-panel-frontpage-mode-new" style="display:none;">
                <label class="col-sm-3"><?php _e("Name for the new Page:", "hst") ?></label>
                <div class="col-sm-4">
                    <input type="text" id="hst-installationwizard-panel-frontpage-txt-newpagename" class="hst-panels-input hst-panels-input-fullwidth" ></input>
                </div>
                <div class="col-sm-5 hst-helptext"><?php _e("Type the name for the new Page that will be used as helpdesk Front-End page (for example: 'Support').", "hst") ?></div>
                <div class="clear"></div>
            </div>

		</div>

		<div class="hst-installationwizard-buttonscontainer">

			<button type="button" class="btn btn-labeled btn-primary" onclick="hst_installationwizard_panel_frontpage_click_back();">
				<span class="btn-label">
					<i class="glyphicon glyphicon-chevron-left"></i>
				</span><?php _e("Go Back", "hst") ?>
			</button>
			<button type="button" class="btn btn-labeled btn-primary" onclick="hst_installationwizard_panel_frontpage_click_continue();">
				<span class="btn-label">
					<i class="glyphicon glyphicon-play"></i>
				</span><?php _e("Continue", "hst") ?>
			</button>

		</div>

	</div>
	<!-- Panel Frontpage -->

	<!-- Panel Email Notifications -->
	<div id="hst-installationwizard-panel-notifications" class="hst-installationwizard-panel" style="display:none;">

		<div class="hst-installationwizard-panel-title">
			<?php _e("EMail Notifications", "hst") ?>
		</div>

		<div class="hst-installationwizard-panel-body">

			<div class="form-group row">
				<label class="col-sm-2"><?php _e("Email address for Helpdesk:", "hst") ?></label>
				<div class="col-sm-5">
					<input type="text" class="hst-panels-input-fullwidth hst-panels-input" id="hst-installationwizard-panel-notifications-txt-emailaddress"></input>
				</div>
				<div class="col-sm-5 hst-helptext"><?php _e("This is the email address that shall be used as 'Sender' for the email notifications.", "hst") ?></div>
				<div class="clear"></div>
			</div>

		</div>

		<div class="hst-installationwizard-buttonscontainer">

			<button type="button" class="btn btn-labeled btn-primary" onclick="hst_installationwizard_panel_notifications_click_back();">
				<span class="btn-label">
					<i class="glyphicon glyphicon-chevron-left"></i>
				</span><?php _e("Go Back", "hst") ?>
			</button>
			<button type="button" class="btn btn-labeled btn-primary" onclick="hst_installationwizard_panel_notifications_click_continue();">
				<span class="btn-label">
					<i class="glyphicon glyphicon-play"></i>
				</span><?php _e("Continue", "hst") ?>
			</button>

		</div>

	</div>
	<!-- Panel Email Notifications -->

	<!-- Panel Attachments -->
	<div id="hst-installationwizard-panel-attachments" class="hst-installationwizard-panel" style="display:none;">

		<div class="hst-installationwizard-panel-title">
			<?php _e("File Attachments", "hst") ?>
		</div>

		<div class="hst-installationwizard-panel-body">

            <div class="form-group row">
                <label class="col-sm-2"><?php _e("Enable Attachments", "hst") ?></label>
                <div class="col-sm-5 text-left">
                    <input type="checkbox" class="hst-panels-input-checkbox" id="hst-installationwizard-panel-attachments-ch-enable"></input>
                </div>
                <div class="col-sm-5 hst-helptext"><?php _e("Check this box if you want to allow files attachment to be uploaded to Support Tickets.", "hst") ?></div>
                <div class="clear"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2"><?php _e("Allowed Extensions", "hst") ?></label>
                <div class="col-sm-5">
                    <input type="text" class="hst-panels-input-fullwidth hst-panels-input" id="hst-installationwizard-panel-attachments-txt-extensions"></input>
                </div>
                <div class="col-sm-5 hst-helptext"><?php _e("A comma separated list of the allowed file extensions for the Attachments. For example: txt,doc,xls,...<br />Note: do not allow dangerous file extensions, like php or exe.", "hst") ?></div>
                <div class="clear"></div>
            </div>

		</div>

		<div class="hst-installationwizard-buttonscontainer">

			<button type="button" class="btn btn-labeled btn-primary" onclick="hst_installationwizard_panel_attachments_click_back();">
				<span class="btn-label">
					<i class="glyphicon glyphicon-chevron-left"></i>
				</span><?php _e("Go Back", "hst") ?>
			</button>
			<button type="button" class="btn btn-labeled btn-primary" onclick="hst_installationwizard_panel_attachments_click_continue();">
				<span class="btn-label">
					<i class="glyphicon glyphicon-play"></i>
				</span><?php _e("Continue", "hst") ?>
			</button>

		</div>

	</div>
	<!-- Panel Attachments -->

	<!-- Panel Thank you -->
	<div id="hst-installationwizard-panel-thankyou" class="hst-installationwizard-panel" style="display:none;">

		<div class="hst-installationwizard-panel-title">
			<?php _e("All done!", "hst") ?>
		</div>

		<div class="hst-installationwizard-panel-body">
			<?php _e("You have completed the installation phase. Click on the following button to start using the Plugin.", "hst") ?>
			<br />
			<?php _e("Contact us in case you need support or have questions!", "hst") ?>
		</div>

		<div class="hst-installationwizard-buttonscontainer">

			<button type="button" class="btn btn-labeled btn-primary" onclick="hst_installationwizard_panel_thankyou_click_back();">
				<span class="btn-label">
					<i class="glyphicon glyphicon-chevron-left"></i>
				</span><?php _e("Go Back", "hst") ?>
			</button>
			<button type="button" class="btn btn-labeled btn-primary" onclick="hst_installationwizard_panel_thankyou_click_continue();">
				<span class="btn-label">
					<i class="glyphicon glyphicon-ok-sign"></i>
				</span><?php _e("Go to Helpdesk", "hst") ?>
			</button>

		</div>

	</div>
	<!-- Panel Thank you -->

</div>
<!-- Main container end -->
