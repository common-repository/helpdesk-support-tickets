var hst_dashboard_settings_system_currentTabKey = '';                             //holds key for the current selected tab



//called when user switches tabs 
function hst_dashboard_settings_system_TabClick(tabKey) {

    //hiding all panels
    jQuery("#hst-controls-settings-system-panel-settings").hide();
    jQuery("#hst-controls-settings-system-panel-logviewer").hide();
    

    //removing all active objects in menu
    jQuery("#hst-controls-settings-system-tab-settings").removeClass("active");
    jQuery("#hst-controls-settings-system-tab-logview").removeClass("active");

    //setting global variable to this panel
    hst_dashboard_settings_notifications_currentTabKey = tabKey;

    //shows correct panel
    if (hst_dashboard_settings_notifications_currentTabKey == "settings") {
        jQuery("#hst-controls-settings-system-panel-settings").show();
        jQuery("#hst-controls-settings-system-tab-settings").addClass("active");
        hst_dashboard_settings_system_loadsettings();
    }
    if (hst_dashboard_settings_notifications_currentTabKey == "logviewer") {
        jQuery("#hst-controls-settings-system-panel-logviewer").show();
        jQuery("#hst-controls-settings-system-tab-logview").addClass("active");
    }

}



//clicked on the back to settings buttons
function hst_dashboard_settings_system_backtosettingsclick() {

    hst_dashboard_setHashAndNavigate("settings");

}


//clicked on the back to settings buttons
function hst_dashboard_settings_system_saveclick() {

    hst_dashboard_settings_system_updatesettings();

}


//called when the user loads this settings page
function hst_dashboard_settings_system_loadsettings() {

    hst_shared_displayoverlay();

    jQuery("#hst-controls-settings-system-chk-deletetableuninstall").prop("checked", false);
    jQuery("#hst-controls-settings-system-chk-logerrors").prop("checked", false);
    jQuery("#hst-controls-settings-system-chk-logemails").prop("checked", false);
    jQuery("#hst-controls-settings-system-chk-logdebuginfo").prop("checked", false);

    jQuery.ajax(
    {
        url: hstvars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'hst_ajax_dashboard_settings_system_load',
            security: hstvars.ajaxNonce
        },
        success: function (response) {

            if (response.data.hstKeyDeleteTablesUninstall == 1) {
                jQuery("#hst-controls-settings-system-chk-deletetableuninstall").prop("checked", true);
            }
            if (response.data.hstKeyEventLogLogErrors == 1) {
                jQuery("#hst-controls-settings-system-chk-logerrors").prop("checked", true);
            }
            if (response.data.hstKeyEventLogLogEmails == 1) {
                jQuery("#hst-controls-settings-system-chk-logemails").prop("checked", true);
            }
            if (response.data.hstKeyEventLogLogDebug == 1) {
                jQuery("#hst-controls-settings-system-chk-logdebuginfo").prop("checked", true);
            }

            hst_shared_removeoverlay();

        }

    });

}


//called when the user clicks on save settings
function hst_dashboard_settings_system_updatesettings() {

    hst_shared_displayoverlay();

    paramValueDeleteTablesUninstall = 0;
    paramValueEventLogLogErrors = 0;
    paramValueEventLogLogEmails = 0;
    paramValueEventLogLogDebug = 0;

    if (jQuery("#hst-controls-settings-system-chk-deletetableuninstall").prop('checked')) {
        paramValueDeleteTablesUninstall = 1;
    }
    if (jQuery("#hst-controls-settings-system-chk-logerrors").prop('checked')) {
        paramValueEventLogLogErrors = 1;
    }
    if (jQuery("#hst-controls-settings-system-chk-logemails").prop('checked')) {
        paramValueEventLogLogEmails = 1;
    }
    if (jQuery("#hst-controls-settings-system-chk-logdebuginfo").prop('checked')) {
        paramValueEventLogLogDebug = 1;
    }

    jQuery.ajax(
    {
        url: hstvars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'hst_ajax_dashboard_settings_system_update',
            hstKeyDeleteTablesUninstall: paramValueDeleteTablesUninstall,
            hstKeyEventLogLogErrors: paramValueEventLogLogErrors,
            hstKeyEventLogLogEmails: paramValueEventLogLogEmails,
            hstKeyEventLogLogDebug: paramValueEventLogLogDebug,
            security: hstvars.ajaxNonce
        },
        success: function (response) {

            hst_shared_removeoverlay();

            hst_shared_displaynotify("saved", "", "Changes saved correctly.");

            hst_dashboard_setHashAndNavigate("settings");

        }

    });

}


//Executes when the user clicks on load event log
function hst_dashboard_settings_system_logview_loadlog() {

    hst_shared_displayoverlay();


    jQuery.ajax(
    {
        url: hstvars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'hst_ajax_dashboard_settings_system_readlog',
            security: hstvars.ajaxNonce
        },
        success: function (response) {

            jQuery('#hst-controls-settings-system-panel-logviewer-table').DataTable(
            {
                "dom": 'tp',
                "bDestroy": true,
                data: response.data,
                columns:
                [
                    { "width": "5%", title: "Date", data: "Date", className: "text-center", render: function (data, type, full, meta) { return data; } },
                    { "width": "20%", title: "Method", data: "Method", render: function (data, type, full, meta) { return data; } },
                    { "width": "5%", title: "Type", data: "Type", render: function (data, type, full, meta) { return "<div class='label label-default'>" + data + "</div>"; } },
                    { "width": "50%", title: "Message / Details", data: "EventText", render: function (data, type, full, meta) { return data; } }
                ],
                "order": [[0, "desc"]]
            });

            hst_shared_removeoverlay();

        }

    });

}



function hst_dashboard_settings_system_logview_clearlog() {

    swal({
        title: 'Are you sure?',
        text: "Click OK if you clear the file log contents",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#588c00',
        cancelButtonColor: '#588c00',
    }).then(function () {

        hst_shared_displayoverlay();

        jQuery.ajax(
                {
                    url: hstvars.ajaxHandlerUrl,
                    type: 'post',
                    dataType: 'json',
                    data:
                    {
                        action: 'hst_ajax_dashboard_settings_system_clearlog',
                        security: hstvars.ajaxNonce
                    },
                    success: function (response) {

                        //reload log
                        hst_dashboard_settings_system_logview_loadlog();

                    }

                });

    })

}
