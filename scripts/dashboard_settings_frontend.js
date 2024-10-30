//clicked on the back to settings buttons
function hst_dashboard_settings_frontend_backtosettingsclick() {

    hst_dashboard_setHashAndNavigate("settings");

}


//called when the user loads this settings page
function hst_dashboard_settings_frontend_loadsettings() {

    hst_shared_displayoverlay();

    jQuery.ajax(
    {
        url: hstvars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'hst_ajax_dashboard_settings_frontend_load',
            security: hstvars.ajaxNonce
        },
        success: function (response) {

            jQuery("#hst-controls-settings-frontend-dd-helpdeskpage").val(response.data.hstFrontEndPageId);
            jQuery("#hst-controls-settings-frontend-dd-allowedusertype").val(response.data.hstFrontEndAllowedUserType);
            
            hst_shared_removeoverlay();

        }

    });

}


//called when the user clicks on save settings
function hst_dashboard_settings_frontend_updatesettings() {

    hst_shared_displayoverlay();

    jQuery.ajax(
    {
        url: hstvars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'hst_ajax_dashboard_settings_frontend_update',
            hstFrontEndPageId: jQuery("#hst-controls-settings-frontend-dd-helpdeskpage").val(),
            hstFrontEndAllowedUserType: jQuery("#hst-controls-settings-frontend-dd-allowedusertype").val(),
            security: hstvars.ajaxNonce
        },
        success: function (response) {

            hst_shared_removeoverlay();

            hst_shared_displaynotify("saved", "", "Changes saved correctly.");

            hst_dashboard_setHashAndNavigate("settings");

        }

    });

}