
<!-- Top bar navigation panel -->
<div id="hst-nvarbar" class="navbar navbar-default" role="navigation">

        <div class="navbar-header">

			<!-- hamburger -->
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <!-- hamburger -->

            <a id="hst-nvarbar-brand" class="navbar-brand">
				<img class="hst-logo-image" src="<?php echo hst_consts_pluginUrl; ?>/images/helpdesk-support-tickets-logo.png" />
			</a>

        </div>

        <div class="collapse navbar-collapse">

            <ul class="nav navbar-nav">

				<li id="hst-nvarbar-li-dashboard">

					<a onclick="hst_dashboard_setHashAndNavigate('dashboard');">
						<i class="fa fa-home fa-menuicon"></i><?php _e("Dashboard", "hst") ?>
					</a>

				</li>

                <li id="hst-nvarbar-li-tickets">

					<a onclick="hst_dashboard_setHashAndNavigate('tickets-list');">
						<i class="fa fa-file-text-o fa-menuicon"></i><?php _e("Support Tickets", "hst") ?>
					</a>

                </li>

				<li id="hst-nvarbar-li-settings">

					<a onclick="hst_dashboard_setHashAndNavigate('settings');">
						<i class="fa fa-cogs fa-menuicon"></i><?php _e("Settings", "hst") ?>
					</a>

				</li>
				
            </ul>

            <!-- right corner -->
            <!--<ul class="nav navbar-nav navbar-right">
            </ul>-->
            <!-- right corner -->

        </div>

</div>

