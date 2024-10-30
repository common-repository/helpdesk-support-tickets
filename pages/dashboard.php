

<?php

//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}

//test area

?>

<!-- Using Open Sans font from google fonts archive -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" />

<!-- Main container -->
<div id="hst-container" class="hst container">

	<!-- ajax loader -->
	<div class="hst-ajax-loader" id="hst-ajaxloader">
		<img src="<?php echo hst_consts_pluginUrl . "/images/loader.gif"; ?>"  />
	</div>
    <!-- ajax loader -->

	<?php

	//including top menu
	include( hst_consts_pluginPath . '/controls/dashboard/topmenu.php' );

    ?>

	<!-- control title -->
	<div class="hst-controltitle">

		<div class="hst-controltitle-title" id="hst-control-titlemain">
		</div>

		<div class="clear"></div>

	</div>
	<!-- control title -->

	<!-- Content pane -->
	<div id="hst-contentpane">

		<?php


			//including controls
			include( hst_consts_pluginPath . '/controls/dashboard/dashboard.php' );
			include( hst_consts_pluginPath . '/controls/dashboard/tickets-list.php' );
			include( hst_consts_pluginPath . '/controls/dashboard/tickets-view.php' );
			include( hst_consts_pluginPath . '/controls/dashboard/tickets-new.php' );
			include( hst_consts_pluginPath . '/controls/dashboard/customers-list.php' );
			include( hst_consts_pluginPath . '/controls/dashboard/modals.php' );
			include( hst_consts_pluginPath . '/controls/settings/settings.php' );
			include( hst_consts_pluginPath . '/controls/settings/settings-ticket-categories.php' );
			include( hst_consts_pluginPath . '/controls/settings/settings-notifications.php' );
			include( hst_consts_pluginPath . '/controls/settings/settings-generic.php' );
			include( hst_consts_pluginPath . '/controls/settings/settings-frontend.php' );
			include( hst_consts_pluginPath . '/controls/settings/settings-system.php' );


		?>

		<div class="clear"></div>

	</div>
	<!-- Content pane -->

	<!-- Footer -->
	<div class="clear"></div>
	<div id="hst-footerpane" class="text-center">
		<div>
			ver <?php echo hst_consts_pluginVersion; ?> - <?php _e("Helpdesk &amp; Support Ticket website (coming soon)", "hst") ?>
		</div>
	</div>
	<!-- Footer -->
</div>
<!-- Main container end -->
