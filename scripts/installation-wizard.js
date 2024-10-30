//global vars
var hst_installwizard_current_panelKey = "";


//page load
jQuery(document).ready(function () {

    hst_isdoinginstallationwizard = 1;

    //sets and shows default control to show
    hst_installwizard_current_panelKey = "welcome";
    hst_installwizard_navigate(hst_installwizard_current_panelKey);

    //defaults
    hst_installationwizard_panel_frontpage_ddmode_change();

});


//handles the navigation between panels
function hst_installwizard_navigate(controlKey) {

    //hiding all panels
    jQuery("#hst-installationwizard-panel-welcome").hide();
    jQuery("#hst-installationwizard-panel-frontpage").hide();
    jQuery("#hst-installationwizard-panel-notifications").hide();
    jQuery("#hst-installationwizard-panel-attachments").hide();
    jQuery("#hst-installationwizard-panel-thankyou").hide();

    //resetting icons active status
    jQuery("#hst-installationwizard-progressline").css("width", "0px");
    jQuery("#hst-installationwizard-stepWelcome").removeClass("hst-installationwizard-step-active");
    jQuery("#hst-installationwizard-stepFrontpage").removeClass("hst-installationwizard-step-active");
    jQuery("#hst-installationwizard-stepNotifications").removeClass("hst-installationwizard-step-active");
    jQuery("#hst-installationwizard-stepAttachments").removeClass("hst-installationwizard-step-active");
    jQuery("#hst-installationwizard-stepThankYou").removeClass("hst-installationwizard-step-active");

    //setting global variable to this panel
    hst_installwizard_current_panelKey = controlKey;

    //shows correct panel
    if (hst_installwizard_current_panelKey == "welcome") {
        jQuery("#hst-installationwizard-panel-welcome").show();
        jQuery("#hst-installationwizard-stepWelcome").addClass("hst-installationwizard-step-active");
        jQuery("#hst-installationwizard-progressline").css("width", "10%");
    }
    if (hst_installwizard_current_panelKey == "frontpage") {
        jQuery("#hst-installationwizard-panel-frontpage").show();
        jQuery("#hst-installationwizard-stepWelcome").addClass("hst-installationwizard-step-active");
        jQuery("#hst-installationwizard-stepFrontpage").addClass("hst-installationwizard-step-active");
        jQuery("#hst-installationwizard-progressline").css("width", "30%");
    }
    if (hst_installwizard_current_panelKey == "notifications") {
        jQuery("#hst-installationwizard-panel-notifications").show();
        jQuery("#hst-installationwizard-stepWelcome").addClass("hst-installationwizard-step-active");
        jQuery("#hst-installationwizard-stepFrontpage").addClass("hst-installationwizard-step-active");
        jQuery("#hst-installationwizard-stepNotifications").addClass("hst-installationwizard-step-active");
        jQuery("#hst-installationwizard-progressline").css("width", "50%");
    }
    if (hst_installwizard_current_panelKey == "attachments") {
        jQuery("#hst-installationwizard-panel-attachments").show();
        jQuery("#hst-installationwizard-stepWelcome").addClass("hst-installationwizard-step-active");
        jQuery("#hst-installationwizard-stepFrontpage").addClass("hst-installationwizard-step-active");
        jQuery("#hst-installationwizard-stepNotifications").addClass("hst-installationwizard-step-active");
        jQuery("#hst-installationwizard-stepAttachments").addClass("hst-installationwizard-step-active");
        jQuery("#hst-installationwizard-progressline").css("width", "70%");
    }
    if (hst_installwizard_current_panelKey == "thankyou") {
        jQuery("#hst-installationwizard-panel-thankyou").show();
        jQuery("#hst-installationwizard-stepWelcome").addClass("hst-installationwizard-step-active");
        jQuery("#hst-installationwizard-stepFrontpage").addClass("hst-installationwizard-step-active");
        jQuery("#hst-installationwizard-stepNotifications").addClass("hst-installationwizard-step-active");
        jQuery("#hst-installationwizard-stepAttachments").addClass("hst-installationwizard-step-active");
        jQuery("#hst-installationwizard-stepThankYou").addClass("hst-installationwizard-step-active");
        jQuery("#hst-installationwizard-progressline").css("width", "100%");
    }
    
}



//buttons click handling - Welcome Panel
function hst_installationwizard_panel_welcome_click_continue()
{

    hst_installwizard_navigate("frontpage");

}


//buttons click handling - FrontPage Panel
function hst_installationwizard_panel_frontpage_click_back()
{

    hst_installwizard_navigate("welcome");

}
function hst_installationwizard_panel_frontpage_click_continue()
{

    has_shared_clearInputValidationCSS("hst-installationwizard-panel-frontpage-txt-newpagename");
    has_shared_clearInputValidationCSS("hst-installationwizard-panel-frontpage-dd-page");

    jQuery.ajax(
    {
        url: hstvars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'hst_ajax_installwizard_frontpage_continue',
            security: hstvars.ajaxNonce,
            pageMode: jQuery("#hst-installationwizard-panel-frontpage-dd-mode").val(),
            pageNewName: jQuery("#hst-installationwizard-panel-frontpage-txt-newpagename").val(),
            pageExistingId: jQuery("#hst-installationwizard-panel-frontpage-dd-page").val()
        },
        success: function (response) {

            if (response.success == 1) {

                hst_installwizard_navigate("notifications");
                
            }
            else {

                if (response.errorCode == "inputValidation") {
                    hst_shared_handleAjaxValidationResponse(response.errorMessage, response.validationInputId);
                    return;
                }

            }

        }

    });

}
function hst_installationwizard_panel_frontpage_ddmode_change() {

    jQuery("#hst-installationwizard-panel-frontpage-mode-existing").hide();
    jQuery("#hst-installationwizard-panel-frontpage-mode-new").hide();

    if ( jQuery("#hst-installationwizard-panel-frontpage-dd-mode").val() == "new")
    {
        jQuery("#hst-installationwizard-panel-frontpage-mode-new").show();
    }
    if (jQuery("#hst-installationwizard-panel-frontpage-dd-mode").val() == "existing")
    {
        jQuery("#hst-installationwizard-panel-frontpage-mode-existing").show();
    }

}


//buttons click handling - Notifications Panel
function hst_installationwizard_panel_notifications_click_back() {

    hst_installwizard_navigate("frontpage");

}
function hst_installationwizard_panel_notifications_click_continue() {

    has_shared_clearInputValidationCSS("hst-installationwizard-panel-notifications-txt-emailaddress");

    jQuery.ajax(
    {
        url: hstvars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'hst_ajax_installwizard_notifications_continue',
            security: hstvars.ajaxNonce,
            helpdeskEmailAddress: jQuery("#hst-installationwizard-panel-notifications-txt-emailaddress").val()
        },
        success: function (response) {

            if (response.success == 1) {

                hst_installwizard_navigate("attachments");

            }
            else {

                if (response.errorCode == "inputValidation") {
                    hst_shared_handleAjaxValidationResponse(response.errorMessage, response.validationInputId);
                    return;
                }

            }

        }

    });

}



//buttons click handling - Attachments Panel
function hst_installationwizard_panel_attachments_click_back() {

    hst_installwizard_navigate("notifications");

}
function hst_installationwizard_panel_attachments_click_continue() {

    has_shared_clearInputValidationCSS("hst-installationwizard-panel-attachments-txt-extensions");

    paramAttachmentEnabled = "0";
    if (jQuery("#hst-installationwizard-panel-attachments-ch-enable").prop("checked") == true)
    {
        paramAttachmentEnabled = "1";
    }

    jQuery.ajax(
    {
        url: hstvars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'hst_ajax_installwizard_attachments_continue',
            security: hstvars.ajaxNonce,
            enableAttachments: paramAttachmentEnabled,
            allowedExtensions: jQuery("#hst-installationwizard-panel-attachments-txt-extensions").val()
        },
        success: function (response) {

            if (response.success == 1) {

                hst_installwizard_navigate("thankyou");

            }
            else {

                if (response.errorCode == "inputValidation") {
                    hst_shared_handleAjaxValidationResponse(response.errorMessage, response.validationInputId);
                    return;
                }

            }

        }

    });

}



//buttons click handling - Thank you Panel
function hst_installationwizard_panel_thankyou_click_back() {

    hst_installwizard_navigate("attachments");

}
function hst_installationwizard_panel_thankyou_click_continue() {

    jQuery.ajax(
    {
        url: hstvars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'hst_ajax_installwizard_thankyou_continue',
            security: hstvars.ajaxNonce,
            pageMode: jQuery("#hst-installationwizard-panel-frontpage-dd-mode").val(),
            pageNewName: jQuery("#hst-installationwizard-panel-frontpage-txt-newpagename").val(),
            pageExistingId: jQuery("#hst-installationwizard-panel-frontpage-dd-page").val(),
            helpdeskEmailAddress: jQuery("#hst-installationwizard-panel-notifications-txt-emailaddress").val(),
            enableAttachments: paramAttachmentEnabled,
            allowedExtensions: jQuery("#hst-installationwizard-panel-attachments-txt-extensions").val()
        },
        success: function (response) {

            if (response.success == 1) {

                //put page self refresh
                location.reload();

            }
            else {

                if (response.errorCode == "inputValidation") {
                    return;
                }

            }

        }

    });

}