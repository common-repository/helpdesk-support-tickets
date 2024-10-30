var hst_dashboard_settings_notifications_currentEditKey = '';                            //holds the current edit key of the notification
var hst_dashboard_settings_notifications_currentTabKey = '';                             //holds key for the current selected tab in edit panel



//clicked on edit in the items table
function hst_dashboard_settings_notifications_editclick(itemKey) {

    hst_shared_displayoverlay();

    //saves into local var
    hst_dashboard_settings_notifications_currentEditKey = itemKey;

    //Load notification details
    hst_dashboard_settings_notifications_editPanel_LoadDetails();

    //defaults to first tab of edit page
    hst_dashboard_settings_notifications_editPanel_TabClick("settings");

    //switches to edit panel
    jQuery("#hst-controls-settings-notifications-editPanel").show();
    jQuery("#hst-controls-settings-notifications-listPanel").hide();
 

}


//load details for a single notification
function hst_dashboard_settings_notifications_editPanel_LoadDetails()
{

    //loading item data
    jQuery.ajax(
    {
        url: hstvars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'hst_ajax_dashboard_settings_ticket_notifications_get',
            NotificationKey: hst_dashboard_settings_notifications_currentEditKey,
            security: hstvars.ajaxNonce
        },
        success: function (response) {

            jQuery("#hst-controls-settings-notifications-editPanel-div-notificationName").html(response.data.NotificationName);
            jQuery("#hst-controls-settings-notifications-editPanel-div-notificationRecipient").html(response.data.NotificationRecipient);
            jQuery("#hst-controls-settings-notifications-editPanel-div-notificationDescription").html(response.data.NotificationDescription);
            if (response.data.NotificationEnabled == 1)
            {
                jQuery("#hst-controls-settings-notifications-editPanel-chk-notificationEnabled").prop("checked", true);
            }
            else
            {
                jQuery("#hst-controls-settings-notifications-editPanel-chk-notificationEnabled").prop("checked", false);
            }

            hst_shared_removeoverlay();

        }

    });

}


//saves details for a single notification
function hst_dashboard_settings_notifications_editPanel_SaveDetails() {

    hst_shared_displayoverlay();

    //building checkbox value param
    var notificationEnabledParam = 0;
    if (jQuery("#hst-controls-settings-notifications-editPanel-chk-notificationEnabled").prop('checked'))
    {
        notificationEnabledParam = 1;
    }

    //loading item data
    jQuery.ajax(
    {
        url: hstvars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'hst_ajax_dashboard_settings_ticket_notifications_update',
            NotificationKey: hst_dashboard_settings_notifications_currentEditKey,
            NotificationEnabled: notificationEnabledParam,
            security: hstvars.ajaxNonce
        },
        success: function (response) {

            hst_shared_removeoverlay();

            hst_shared_displaynotify("saved", "", "Changes saved correctly.");

            jQuery("#hst-controls-settings-notifications-editPanel").hide();
            jQuery("#hst-controls-settings-notifications-listPanel").show();

        }

    });

}


//called when user switches tabs in the edit notification page
function hst_dashboard_settings_notifications_editPanel_TabClick(tabKey)
{

    //hiding all panels
    jQuery("#hst-controls-settings-notifications-editPanel-panel-settings").hide();
    jQuery("#hst-controls-settings-notifications-editPanel-panel-template").hide();
    jQuery("#hst-controls-settings-notifications-editPanel-panel-test").hide();

    //removing all active objects in menu
    jQuery("#hst-controls-settings-notifications-editPanel-tab-settings").removeClass("active");
    jQuery("#hst-controls-settings-notifications-editPanel-tab-template").removeClass("active");
    jQuery("#hst-controls-settings-notifications-editPanel-tab-test").removeClass("active");

    //setting global variable to this panel
    hst_dashboard_settings_notifications_currentTabKey = tabKey;

    //shows correct panel
    if (hst_dashboard_settings_notifications_currentTabKey == "settings") {
        jQuery("#hst-controls-settings-notifications-editPanel-panel-settings").show();
        jQuery("#hst-controls-settings-notifications-editPanel-tab-settings").addClass("active");
    }
    if (hst_dashboard_settings_notifications_currentTabKey == "template") {
        jQuery("#hst-controls-settings-notifications-editPanel-panel-template").show();
        jQuery("#hst-controls-settings-notifications-editPanel-tab-template").addClass("active");
    }
    if (hst_dashboard_settings_notifications_currentTabKey == "test") {
        jQuery("#hst-controls-settings-notifications-editPanel-panel-test").show();
        jQuery("#hst-controls-settings-notifications-editPanel-tab-test").addClass("active");
    }

}


//clicked on the back to settings buttons
function hst_dashboard_settings_notifications_backtosettingsclick()
{

    hst_dashboard_setHashAndNavigate("settings");

}

//clicked on save changes for a notification 
function hst_dashboard_settings_notifications_savedetailsclick()
{

    hst_dashboard_settings_notifications_editPanel_SaveDetails();

}


//clicked to return to notifications list from notification settings
function hst_dashboard_settings_notifications_backtolistclick()
{

    jQuery("#hst-controls-settings-notifications-editPanel").hide();
    jQuery("#hst-controls-settings-notifications-listPanel").show();

}


//called when the user clicks on the test email button
function hst_dashboard_settings_notifications_sendtestemailclick()
{

    hst_shared_displayoverlay();

    has_shared_clearInputValidationCSS("hst-controls-tickets-new-txt-testrecipientemailaddress");

    jQuery.ajax(
    {
        url: hstvars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'hst_ajax_dashboard_settings_ticket_notifications_sendtestemail',
            NotificationKey: hst_dashboard_settings_notifications_currentEditKey,
            RecipientEmailAddress: jQuery("#hst-controls-tickets-new-txt-testrecipientemailaddress").val(),
            security: hstvars.ajaxNonce
        },
        success: function (response) {

            if (response.success == 1)
            {

                hst_shared_removeoverlay();

                hst_shared_displaynotify( 'testsendemailpositive', 'Email Sent!', 'Please check the email box for new email');

            }
            else
            {

                if (response.errorCode == "inputValidation") {
                    //checking for input validation message
                    hst_shared_handleAjaxValidationResponse(response.errorMessage, response.validationInputId);
                    return;
                }
                else
                {

                    //error in sending email
                    hst_shared_displaynotify('testsendemailnegative', 'Email Sending Error', 'Please make sure that your website is capable of sending email. Contact Us if you need more support!');

                }

            }

        }

    });


}