<?php

//prefilling some of the users data if logged in
$currentUserDisplayName = "";
$currentUserEmailAddress = "";
$disableInputValueForLoggedInUsers = "";

if ( is_user_logged_in() == true )
{
	$currentUser = wp_get_current_user();
	$currentUserDisplayName = $currentUser->display_name;
	$currentUserEmailAddress = $currentUser->user_email;
	$disableInputValueForLoggedInUsers = "readonly";
}

?>

<div id="hst-fe-createticket" class="hst-frontend-panel-default" style="display:none;">

	<div class="hst-frontend-panel-default-title">
		<?php _e("Create New Support Ticket", "hst") ?>
	</div>

	<div class="hst-frontend-panel-parag">
		<?php _e("Fill the information below to create a new Support Ticket. Our team will get in touch with your as soon as possible. Thank you!", "hst") ?>
	</div>

	<div class="hst-panels-sidepanelrowformcontrol">
		<div class="hst-panels-formlabel">
			<?php _e("Your Name", "hst") ?>
			<span class="hst-asterisk">*</span>
		</div>
		<input type="text" id="hst-fe-createticket-customerdisplayname" class="hst-panels-input hst-panels-input-fullwidth" maxlength="100" value="<?php echo $currentUserDisplayName; ?>" <?php echo $disableInputValueForLoggedInUsers; ?> />
		<div class="clear"></div>
	</div>

	<div class="hst-panels-sidepanelrowformcontrol">
		<div class="hst-panels-formlabel">
			<?php _e("Your Email Address", "hst") ?>
			<span class="hst-asterisk">*</span>
		</div>
		<input type="text" id="hst-fe-createticket-customeremail" class="hst-panels-input hst-panels-input-fullwidth" maxlength="100" value="<?php echo $currentUserEmailAddress; ?>" <?php echo $disableInputValueForLoggedInUsers; ?> />
		<div class="clear"></div>
	</div>

	<div class="hst-panels-sidepanelrowformcontrol">
		<div class="hst-panels-formlabel">
			<?php _e("Support Request Title", "hst") ?>
			<span class="hst-asterisk">*</span>
		</div>
		<input type="text" id="hst-fe-createticket-tickettitle" class="hst-panels-input hst-panels-input-fullwidth" maxlength="50" />
		<div class="clear"></div>
	</div>

	<div class="hst-panels-sidepanelrowformcontrol">
		<div class="hst-panels-formlabel">
			<?php _e("Problem Description", "hst") ?>
			<span class="hst-asterisk">*</span>
		</div>
		<textarea type="text" id="hst-fe-createticket-ticketproblem" class="hst-panels-input hst-panels-input-fullwidth" style="height:100px;"></textarea>
		<div class="clear"></div>
	</div>

	<div class="hst-panels-sidepanelrowformcontrol">
		<div class="hst-panels-formlabel">
			<?php _e("Category", "hst") ?>
			<span class="hst-asterisk">*</span>
		</div>
		<select id="hst-fe-createticket-dd-category" class="hst-panels-select">
			<option value="0">(...)</option>
			<?php
			$ticketCategoriesClass = new hst_Classes_Ticket_Category();
			$listCategories = $ticketCategoriesClass->ListFromDB( array() );
			foreach ( $listCategories as $category )
			{
				$option = '<option value="' . $category->TicketCategoryId  . '">';
				$option .= $category->TicketCategoryDescription;
				$option .= '</option>';
				echo $option;
			}
            ?>
		</select>
		<div class="clear"></div>
	</div>

	<div style="clear:both;"></div>

	<div class="hst-button-genericbar">

		<div class="hst-button" onclick="hst_fe_createticket_cancelclick();">
			<i class="fa fa-button fa-arrow-left"></i>
			<span><?php _e("Cancel", "hst") ?></span>
		</div>

		<div class="hst-button" onclick="hst_fe_createticket_updateclick();">
			<i class="fa fa-button fa-check hst-icon-check"></i>
			<span><?php _e("Submit Ticket", "hst") ?></span>
		</div>

	</div>

	<div style="clear:both;"></div>

	<div id="hst-fe-ticketcreation-div-validation" class="hst-fe-validation" style="display:none;">
		<i class="fa fa-warning"></i>
		<span id="hst-fe-ticketcreation-div-validationmessage"></span>
	</div>

	<div style="clear:both;"></div>

</div>