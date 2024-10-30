//clicked on the back to settings buttons
function hst_dashboard_settings_generic_backtosettingsclick() {

    hst_dashboard_setHashAndNavigate("settings");

}


//called when the user loads this settings page
function hst_dashboard_settings_generic_loadsettings() {

    hst_shared_displayoverlay();

    jQuery.ajax(
    {
        url: hstvars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'hst_ajax_dashboard_settings_generic_load',
            security: hstvars.ajaxNonce
        },
        success: function (response) {

            jQuery("#hst-controls-settings-generic-divDefaultAgentId").html(response.data.hstHelpdeskDefaultAgentUserId);
            jQuery("#hst-controls-settings-generic-divDefaultAgent").html(response.data.hstHelpdeskDefaultAgentUserDisplayName);
            jQuery("#hst-controls-settings-generic-txtHelpdeskEmailAddress").val(response.data.hstHelpdeskEmailAddress);
            jQuery("#hst-controls-settings-generic-txtHelpdeskEmailSenderName").val(response.data.hstHelpdeskEmailSenderName);
            if (response.data.hstHelpdeskAttachmentsAllowed == "1")
            {
                jQuery("#hst-controls-settings-generic-chAttachmentsEnable").attr("checked", "checked");
            }
            else
            {
                jQuery("#hst-controls-settings-generic-chAttachmentsEnable").removeAttr("checked");
            }
            jQuery("#hst-controls-settings-generic-txtAttachmentsAllowedExtensions").val(response.data.hstHelpdeskAttachmentsExtensions);

            hst_shared_removeoverlay();

        }

    });

}


//called when the user clicks on save settings
function hst_dashboard_settings_generic_updatesettings() {

    hst_shared_displayoverlay();

    has_shared_clearInputValidationCSS("hst-controls-settings-generic-txtHelpdeskEmailAddress");
    has_shared_clearInputValidationCSS("hst-controls-settings-generic-txtHelpdeskEmailSenderName");

    valuehstHelpdeskDefaultAgentUserId = jQuery("#hst-controls-settings-generic-divDefaultAgentId").html()
    valuehstHelpdeskEmailAddress = jQuery("#hst-controls-settings-generic-txtHelpdeskEmailAddress").val()
    valuehstHelpdeskEmailSenderName = jQuery("#hst-controls-settings-generic-txtHelpdeskEmailSenderName").val()
    valuehstHelpdeskAttachmentsAllowed = "0";
    if (jQuery("#hst-controls-settings-generic-chAttachmentsEnable").prop("checked") == true)
    {
        valuehstHelpdeskAttachmentsAllowed = "1";
    }
    valuehstHelpdeskAttachmentsExtensions = jQuery("#hst-controls-settings-generic-txtAttachmentsAllowedExtensions").val()

    jQuery.ajax(
    {
        url: hstvars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'hst_ajax_dashboard_settings_generic_update',
            hstHelpdeskDefaultAgentUserId: valuehstHelpdeskDefaultAgentUserId,
            hstHelpdeskEmailAddress: valuehstHelpdeskEmailAddress,
            hstHelpdeskEmailSenderName: valuehstHelpdeskEmailSenderName,
            hstHelpdeskAttachmentsAllowed: valuehstHelpdeskAttachmentsAllowed,
            hstHelpdeskAttachmentsExtensions: valuehstHelpdeskAttachmentsExtensions,
            security: hstvars.ajaxNonce
        },
        success: function (response) {

            if (response.success == 1) {

                hst_shared_removeoverlay();

                hst_shared_displaynotify("saved", "", "Changes saved correctly.");

                hst_dashboard_setHashAndNavigate("settings");

                //adjusts dinamically those parts of software that must hidden/showed according to settings
                if (valuehstHelpdeskAttachmentsAllowed == "1")
                {
                    jQuery("#hst-controls-tickets-view-tabs-li-attachments").show();
                }
                else
                {
                    jQuery("#hst-controls-tickets-view-tabs-li-attachments").hide();
                }
                
            }
            else {

                if (response.errorCode == "inputValidation") {
                    //checking for input validation message
                    hst_shared_handleAjaxValidationResponse(response.errorMessage, response.validationInputId);
                    return;
                }

            }         

        }

    });

}