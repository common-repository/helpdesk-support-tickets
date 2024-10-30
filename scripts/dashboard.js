//global vars
var hst_dashboard_current_panelKey = "";                            //holds the current main panel to show
var hst_navigation_on_load_done = 0;                                //used to stop executing navigations on load (for example, after the redirection by a link in a notification, it will prevent to execture the redirection again)
var hst_isdoinginstallationwizard = 0;


//page load
jQuery( document ).ready( function() 
{

    //global error catching
    window.onerror = function (errorMsg, url, lineNumber)
    {
        hst_shared_handleScriptError(errorMsg, url, lineNumber);
    }

    //global ajax non-200-status catching
    jQuery("#hst-container").ajaxError(
        function (e, x, settings, exception){
            hst_shared_handleAjaxStatusError("generic");
        });

    //global ajax 200-status catching
    jQuery("#hst-container").ajaxSuccess(
        function (event, xhr, settings){
            parsedResponse = JSON.parse(xhr.responseText);
            if (parsedResponse.success == 0)
            {
                hst_shared_handleAjaxStatusError(parsedResponse.errorCode);
            }
        });

    //page resize
    hst_dashboard_pageadjust();

    //start loading dropdown tables content
    hst_dashboard_loadtablescontent();

    //hash changed event
    window.location.hash = "dashboard"; //default page
    hst_dashboard_navigate();
    jQuery(window).bind('hashchange', function (e) { hst_dashboard_navigate(); });

    //redirects on load?
    if (hst_navigation_on_load_done == 0)
    {

        hst_shared_navigateonload("dashboard");
        hst_navigation_on_load_done = 1;

    }

    //setting action for the upload frame
    jQuery("#hst-dashboard-ticketview-attachments-form").attr("action", hstvars.ajaxHandlerUrl);

    //suggests log clearing because file is too big
    if (hstvars.suggestClearLogFile == 1)
    {
        hst_shared_showModalWarning("Log File Size", "The System Log file is quite large. Please consider clearing its content, to speed up the plugin user experience!");
    }
    
});


//adjusts the height of the main container
function hst_dashboard_pageadjust()
{

    //removes WP footer to allow more usable space
    jQuery("#wpfooter").remove();

    //removes padding at the bottom of wpcontent to allow more space for users
    jQuery("#wpbody-content").css("padding-bottom", "0px");

    //calculates the height of the wpwrap element
    var containerHeight = jQuery("#wpwrap").height() - jQuery("#hst-nvarbar").height() - jQuery("#hst-controltitle").height();
    //takes off a little of offset (admin bar at top)
    containerHeight = containerHeight - 101;
    //adjusts our container
    jQuery("#hst-contentpane").css("height", containerHeight + "px");

}


function hst_dashboard_setHashAndNavigate( newHashValue )
{

    window.location.hash = newHashValue;
    hst_dashboard_navigate();

}


//handles top menu navigation clicks
function hst_dashboard_navigate()
{

    //hiding all panels
    jQuery("#hst-controls-dashboard").hide();
    jQuery("#hst-controls-tickets-list").hide();
    jQuery("#hst-controls-tickets-view").hide();
    jQuery("#hst-controls-tickets-new").hide();
    jQuery("#hst-controls-customers-list").hide();
    jQuery("#hst-controls-settings").hide();
    jQuery("#hst-controls-settings-ticket-categories").hide();
    jQuery("#hst-controls-settings-notifications").hide();
    jQuery("#hst-controls-settings-generic").hide();
    jQuery("#hst-controls-settings-frontend").hide();
    jQuery("#hst-controls-settings-system").hide();

    //removing all active objects in menu
    jQuery("#hst-nvarbar-li-dashboard").removeClass("active");
    jQuery("#hst-nvarbar-li-tickets").removeClass("active");
    jQuery("#hst-nvarbar-li-customers").removeClass("active");
    jQuery("#hst-nvarbar-li-settings").removeClass("active");

    //setting global variable to this panel
    hst_dashboard_current_panelKey = window.location.hash;

    //shows correct panel
    if (hst_dashboard_current_panelKey == "#dashboard") {
        if (hstvars.isInstallWizardCompleted == 1) {
            hst_dashboardhome_loaddata();
        }
        jQuery("#hst-controls-dashboard").show();
        jQuery("#hst-nvarbar-li-dashboard").addClass("active");
        jQuery("#hst-control-titlemain").html("Dashboard");
    }

    if (hst_dashboard_current_panelKey == "#tickets-list" )
    {
        jQuery("#hst-controls-tickets-list").show();
        jQuery("#hst-nvarbar-li-tickets").addClass("active");
        jQuery("#hst-control-titlemain").html("Support Tickets Management");
        if ( hst_dashboard_tickets_loadticketsonload == 1 )
        {
            //loads the ticket on page load, only happens once
            hst_tickets_loadtickets();
            hst_dashboard_tickets_loadticketsonload = 0;
        }
    }

    if (hst_dashboard_current_panelKey == "#tickets-view") {
        jQuery("#hst-control-titlemain").html("Support Ticket Details - #" + hst_dashboard_tickets_currentticketidview);
        jQuery("#hst-controls-tickets-view").show();
        jQuery("#hst-nvarbar-li-tickets").addClass("active");
        hst_tickets_viewticket_prepare();
    }

    if (hst_dashboard_current_panelKey == "#tickets-new") {
        jQuery("#hst-controls-tickets-new").show();
        jQuery("#hst-nvarbar-li-tickets").addClass("active");
        jQuery("#hst-control-titlemain").html("Create New Support Ticket");
        hst_tickets_new_prepare();
    }

    if (hst_dashboard_current_panelKey == "#customers-list")
    {
        jQuery("#hst-controls-customers-list").show();
        jQuery("#hst-nvarbar-li-customers").addClass("active");
    }
    
    if (hst_dashboard_current_panelKey == "#settings") {
        jQuery("#hst-controls-settings").show();
        jQuery("#hst-nvarbar-li-settings").addClass("active");
        jQuery("#hst-control-titlemain").html("Settings");
    }

    if (hst_dashboard_current_panelKey == "#ticket_categories")
    {
        jQuery("#hst-controls-settings-ticket-categories").show();
        hst_dashboard_settings_ticket_categories_loadlist();
        jQuery("#hst-control-titlemain").html("Support Ticket Categories");
        jQuery("#hst-nvarbar-li-settings").addClass("active");
    }

    if (hst_dashboard_current_panelKey == "#notifications") {
        jQuery("#hst-controls-settings-notifications").show();
        jQuery("#hst-control-titlemain").html("Email Notifications");
        jQuery("#hst-nvarbar-li-settings").addClass("active");
    }

    if (hst_dashboard_current_panelKey == "#frontend") {
        jQuery("#hst-controls-settings-frontend").show();
        hst_dashboard_settings_frontend_loadsettings();
        jQuery("#hst-control-titlemain").html("Front-End Content Settings");
        jQuery("#hst-nvarbar-li-settings").addClass("active");
    }

    if (hst_dashboard_current_panelKey == "#generic") {
        jQuery("#hst-controls-settings-generic").show();
        hst_dashboard_settings_generic_loadsettings();
        jQuery("#hst-control-titlemain").html("Generic Configuration");
        jQuery("#hst-nvarbar-li-settings").addClass("active");
    }

    if (hst_dashboard_current_panelKey == "#system") {
        jQuery("#hst-controls-settings-system").show();
        hst_dashboard_settings_system_TabClick("settings");
        jQuery("#hst-control-titlemain").html("System Settings");
        jQuery("#hst-nvarbar-li-settings").addClass("active");
    }

}


//loads the values of the dropdowns in the system
//this is originally called at first load
//must be called again, when the settings (like priorities, status...) get changed
function hst_dashboard_loadtablescontent() {

    jQuery.ajax(

        {
            url: hstvars.ajaxHandlerUrl,
            type: 'post',
            dataType: 'json',
            data:
            {
                action: 'hst_ajax_dashboard_shared_loadtablescontent',
                security: hstvars.ajaxNonce
            },
            success: function (response)
            {

                //clearing previous content
                jQuery("#hst-controls-tickets-view-ticketdata-dd-category").empty();
                jQuery("#hst-controls-tickets-new-dd-category").empty();
                jQuery("#hst-controls-tickets-simplesearch-statusesplaceholder").empty();
                jQuery("#hst-controls-tickets-view-ticketdata-dd-status").empty();
                jQuery("#hst-controls-tickets-view-ticketdata-dd-priority").empty();
                jQuery("#hst-controls-tickets-new-dd-priority").empty();

                //adding empty item where needed
                jQuery("#hst-controls-tickets-new-dd-category").append("<option value='0'>(...)</option>");
                jQuery("#hst-controls-tickets-new-dd-priority").append("<option value='0'>(...)</option>");

                //filling categories dropdown
                jQuery.each(response.data.ticketCategories, function (index, ticketCategoryInfo)
                {

                    //filling dropdown of ticket details 
                    optionHtml = "<option value='" + ticketCategoryInfo.TicketCategoryId + "'>" + ticketCategoryInfo.TicketCategoryDescription + "</option>";
                    jQuery("#hst-controls-tickets-view-ticketdata-dd-category").append(optionHtml);

                    //filling option of create ticket
                    jQuery("#hst-controls-tickets-new-dd-category").append(optionHtml);                 

                });

                //filling statuses dropdown
                jQuery.each(response.data.ticketStatuses, function (index, ticketStatusInfo)
                {

                    //status list for ticket list filters
                    filterHtml = "<div class='row hst-controls-tickets-simplesearch-statusitem' onclick='hst_tickets_listtickets_searchitemstatusclick(" + ticketStatusInfo.TicketStatusId + ");' id='hst_tickets_listtickets_searchitemstatus_" + ticketStatusInfo.TicketStatusId + "'>";
                    filterHtml += "<div class='col-md-10' ><div class='hst-controls-tickets-simplesearch-statusitemlittleball' style='background-color:" + ticketStatusInfo.TicketStatusBgColor + ";' />" + ticketStatusInfo.TicketStatusDescription + "</div>";
                    filterHtml += "<div class='col-md-2' >" + "<div class='badge hst-controls-tickets-simplesearch-statusitem-badge' id='hst-controls-tickets-simplesearch-item-" + ticketStatusInfo.TicketStatusId + "' >0</div>" + "</div>";
                    filterHtml += "</div>";
                    jQuery("#hst-controls-tickets-simplesearch-statusesplaceholder").append(filterHtml);

                    //filling dropdown of ticket details 
                    optionHtml = "<option value='" + ticketStatusInfo.TicketStatusId + "'>" + ticketStatusInfo.TicketStatusDescription + "</option>";
                    jQuery("#hst-controls-tickets-view-ticketdata-dd-status").append(optionHtml);

                });

                //filling priorities dropdown
                jQuery.each(response.data.ticketPriorities, function (index, ticketPriorityInfo)
                {
                    //filling dropdown of ticket details 
                    optionHtml = "<option value='" + ticketPriorityInfo.TicketPriorityId + "'>" + ticketPriorityInfo.TicketPriorityDescription + "</option>";
                    jQuery("#hst-controls-tickets-view-ticketdata-dd-priority").append(optionHtml);

                    //filling option of create ticket
                    jQuery("#hst-controls-tickets-new-dd-priority").append(optionHtml);


                });


            }

        }

    );

}