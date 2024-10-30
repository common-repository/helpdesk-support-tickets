<?php

//Exists on direct loading
if ( defined( 'ABSPATH' ) == false )
{
	exit;
}

//Loading client resources, like style sheets and scripts
hst_ClientResources_InjectFrontEndResources();

//TODO, enable
//adds support for redirect after registration, included only when front end is included and rendered
//function bwhd_registration_redirect(){
//    wp_redirect( get_permalink() );
//    exit;
//}
//add_filter( 'registration_redirect', 'bwhd_registration_redirect' );

?>

<!-- Main container -->
<div class="hst" id="hst-container">

    <!-- ajax loader -->
    <div class="hst-ajax-loader" id="hst-ajaxloader" style="display:none;">
        <img src="<?php echo hst_consts_pluginUrl . "/images/loader.gif"; ?>" />
    </div>
    <!-- ajax loader -->

	<?php

	//including controls
	include( hst_consts_pluginPath . '/controls/frontend/home.php' );
	include( hst_consts_pluginPath . '/controls/frontend/mytickets.php' );
	include( hst_consts_pluginPath . '/controls/frontend/createticket.php' );
	include( hst_consts_pluginPath . '/controls/frontend/viewticket.php' );
	include( hst_consts_pluginPath . '/controls/frontend/createticketconfirmation.php' );

	?>

</div>