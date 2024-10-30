<?php

	//Is the helpdesk allowed only to registered users (logged), or also to guests?
	$allowedUserTypeOption = get_option( hst_consts_optionKeyFrontEndAllowedUserType, "registered" );

	//Display the Support Tickets interface?
	$displayInterface = false;		//default

	if ( $allowedUserTypeOption == "everyone" || is_user_logged_in() == true )
	{

		$displayInterface = true;

	}

?>


<div id="hst-fe-home" class="hst-frontend-panel-default">

    <div class="hst-frontend-panel-default-title">
		<?php _e("Welcome to our Support Page!", "hst") ?>
    </div>

	<?php
	if ( $displayInterface == true )
	{
    ?>

	<div class="hst-frontend-panel-parag">
		<?php _e("Welcome to our Helpdesk. If you have a problem, a question or a request for help, you can create a New Support Ticket by clicking on the following button.", "hst") ?>

		<?php
		if ( is_user_logged_in() == true )
		{
		?>

		<?php _e("You can review your existing Support Tickets by clicking on the 'Search Ticket' button.", "hst") ?>

		<?php
		}
        ?>


	</div>

	<div class="hst-button-genericbar">

		<div class="hst-button-squared" onclick="hst_fe_home_createticketclick();">
			<i class="fa fa-button fa-file-text hst-icon-createticket"></i>
			<div class="clear"></div>
			<span><?php _e("Create Ticket", "hst") ?></span>
			<div class="clear"></div>
		</div>

		<?php
		if ( is_user_logged_in() == true )
		{
		?>

		<div class="hst-button-squared" onclick="hst_fe_home_myticketsclick();">
			<i class="fa fa-button fa-search hst-icon-search"></i>
			<div class="clear"></div>
			<span><?php _e("My Tickets", "hst") ?></span>
			<div class="clear"></div>
		</div>

		<?php
		}
        ?>

	</div>

	<div style="clear:both;"></div>

	<?php
	}
	else
	{
    ?>
	
	<?php _e("The Support Service is available only to Registered Users.", "hst") ?>
	
	<?php _e("Please use the following button to Login (if you already have an account) or Register as a New User, thank you!", "hst") ?>

	<div style="clear:both;height:20px;"></div>

	<div class="hst-button-genericbar">

		<div class="hst-button" onclick="window.location.href = '<?php echo wp_login_url( get_permalink() ); ?>';">
			<i class="fa fa-button fa-user hst-icon-login"></i>
			<span><?php _e("Existing User Login", "hst") ?></span>
		</div>

		<div class="hst-button" onclick="window.location.href = '<?php echo wp_registration_url( get_permalink() ); ?>';">
			<i class="fa fa-button fa-pencil hst-icon-register"></i>
			<span><?php _e("Register as New User", "hst") ?></span>
		</div>

		<div style="clear:both;"></div>

	</div>
	
	<?php
	}
    ?>


	
</div>
