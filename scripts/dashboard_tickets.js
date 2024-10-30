//global vars
var hst_dashboard_tickets_loadticketsonload = 1;                            //if 1, the tickets will be loaded when the tickets panel is shown.
var hst_dashboard_tickets_currentticketidview = 0;                          //holds the current ticket view id, if 0 it means insert mode
var hst_dashboard_tickets_currentticketviewpanelkey = 'home';               //holds what panel key / tab is currently selected in the ticket view page
var hst_dashboard_tickets_list_filters_statusid = '';                       //holds the current status id clicked, '' means all status (no filtering)



//loads/refreshes the list of tickets
function hst_tickets_loadtickets() {

    hst_shared_displayoverlay();

    jQuery.ajax(

        {
            url: hstvars.ajaxHandlerUrl,
            type: 'post',
            dataType: 'json',
            data:
            {
                action: 'hst_ajax_dashboard_tickets_list',
                security: hstvars.ajaxNonce,
                filterKey: jQuery("#hst-controls-tickets-simplesearch-txt-filterkey").val(),
                filterStatusId: hst_dashboard_tickets_list_filters_statusid,
                returnStatusCounters: 1
            },
            success: function (response) {

                //updates the list of status and counters
                jQuery.each(response.data.StatusCounters, function (index, ticketsStatusCounter) {

                    jQuery("#hst-controls-tickets-simplesearch-item-" + ticketsStatusCounter.TicketStatusId).html(ticketsStatusCounter.TotalTicketsForCounters);

                });

                //updates header result info
                jQuery("#hst-controls-tickets-list-resultpane-resultcounter-span").html(response.data.Tickets.length);

                if (response.data.Tickets.length > 0) {

                    jQuery('#hst-controls-tickets-list-nodata').hide();

                    jQuery('#hst-controls-tickets-list-table').DataTable(
                        {
                            "dom": 'tp',
                            "bDestroy": true,
                            "bAutoWidth": false,
                            data: response.data.Tickets,
                            columns:
                            [
                                { "width": "5%", title: "N", data: "TicketId", className: "text-center", render: function (data, type, full, meta) { return "<div class='label label-default'>" + data + "</div>"; } },
                                { "width": "20%", title: "Customer", data: "TicketCustomerUserDisplayName", render: function (data, type, full, meta) { return data; } },
                                { "width": "35%", title: "Problem", data: "TicketTitle", render: function (data, type, full, meta) { return data; } },
                                { "width": "10%", title: "Category", data: "TicketCategoryText", render: function (data, type, full, meta) { return data; } },
                                { "width": "10%", title: "Status", data: "TicketStatusText", className: "text-center", render: function (data, type, full, meta) { return "<div class='label' style='background-color:" + full.StatusBgColor + "'>" + data + "</div>"; } },
                                { "width": "10%", title: "Updated", data: "TicketDateLastUpdatedHumanTimeDiff", className: "text-center", render: function (data, type, full, meta) { return data; } },
                                { "width": "10%", title: "Action", data: "TicketCategoryText", className: "text-center", render: function (data, type, full, meta) { return "<button type='button' class='btn btn-labeled btn-primary btn-table' onclick='hst_tickets_listtickets_editticket(" + full.TicketId + ");'><i class='glyphicon glyphicon-pencil'></i></button>"; } }
                            ],
                            "order": [[0, "desc"]]
                        });

                    jQuery('#hst-controls-tickets-list-table_wrapper').show();
                    jQuery('#hst-controls-tickets-list-table').show();

                }
                else {

                    jQuery('#hst-controls-tickets-list-nodata').show();

                    jQuery('#hst-controls-tickets-list-table').DataTable(
                        {
                            "bDestroy": true
                        });
                    jQuery('#hst-controls-tickets-list-table_wrapper').hide();
                    jQuery('#hst-controls-tickets-list-table').hide();

                }

                hst_shared_removeoverlay();

            }

        }

    );

}

//called by the "search" button in the tickets filters
function hst_tickets_listtickets_searchclick() {

    //relaods the list
    hst_tickets_loadtickets();

}

//called when the user click on a status filter in the simple search
function hst_tickets_listtickets_searchitemstatusclick(statusid) {

    //if the status is already selected, then it must be de-selected
    if (jQuery("#hst_tickets_listtickets_searchitemstatus_" + statusid).hasClass("hst-controls-tickets-simplesearch-statusitem_statusfilteractive")) {

        //must be deselected

        jQuery(".hst-controls-tickets-simplesearch-statusitem").removeClass("hst-controls-tickets-simplesearch-statusitem_statusfilteractive");

        //set the local variable that will be sent to ajax as filter value
        hst_dashboard_tickets_list_filters_statusid = '';

    }
    else {

        //must be selected

        //removes the active class from other stats filters
        jQuery(".hst-controls-tickets-simplesearch-statusitem").removeClass("hst-controls-tickets-simplesearch-statusitem_statusfilteractive");
        jQuery("#hst_tickets_listtickets_searchitemstatus_" + statusid).addClass("hst-controls-tickets-simplesearch-statusitem_statusfilteractive");

        //set the local variable that will be sent to ajax as filter value
        hst_dashboard_tickets_list_filters_statusid = statusid;

    }

    //refreshes tickets results
    hst_tickets_loadtickets();

}


//function when a edit button of a ticket is clicked
function hst_tickets_listtickets_editticket(ticketId) {

    //sets the current ticket id
    hst_dashboard_tickets_currentticketidview = ticketId;

    //shows the ticket view page
    hst_dashboard_setHashAndNavigate('tickets-view');

}

//function when the create new ticket button is clicked
function hst_tickets_listtickets_newticket() {

    //sets the current ticket id
    hst_dashboard_tickets_currentticketidview = 0;
    //shows the ticket view page
    hst_dashboard_setHashAndNavigate('tickets-new');

}

//prepares the ticket edit page for the given record (edit or insert)
function hst_tickets_viewticket_prepare() {

    hst_shared_displayoverlay();

    //resets previous values/content
    hst_tickets_viewticket_showpanel(hst_dashboard_tickets_currentticketviewpanelkey);

    //starts loading attachments
    hst_tickets_view_loadattachments();

    //getting tickets data
    jQuery.ajax(
    {
        url: hstvars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'hst_ajax_dashboard_tickets_get',
            security: hstvars.ajaxNonce,
            TicketId: hst_dashboard_tickets_currentticketidview,
            IncludeEvents: 1,
            MakeDescriptionAsFirstEvent: 1
        },
        success: function (response) {

            //setting title
            jQuery("#hst-controls-tickets-view-titleeditticketnumber").html('#' + response.data.TicketId);

            //rendering home panel
            jQuery("#hst-controls-tickets-view-home-div-title").html(response.data.TicketTitle);
            jQuery("#hst-controls-tickets-view-ticketdata-div-customerdisplayname").html(response.data.TicketCustomerUserDisplayName);
            jQuery("#hst-controls-tickets-view-ticketdata-div-customerdisplayemail").html(response.data.TicketCustomerUserEmail);
            jQuery("#hst-controls-tickets-view-home-div-datecreated").html(response.data.TicketDateCreatedText);
            jQuery("#hst-controls-tickets-view-home-div-datecreatedpassed").html(response.data.TicketDateCreatedHumanTimeDiff);
            jQuery("#hst-controls-tickets-view-home-div-dateupdated").html(response.data.TicketDateLastUpdatedText);
            jQuery("#hst-controls-tickets-view-home-div-dateupdatedpassed").html(response.data.TicketDateLastUpdatedHumanTimeDiff);
            jQuery("#hst-controls-tickets-view-home-div-dateclosed").html(response.data.TicketDateClosedText);
            jQuery("#hst-controls-tickets-view-home-div-datecloseddpassed").html(response.data.TicketDateClosedHumanTimeDiff);

            //adjusting
            if (jQuery("#hst-controls-tickets-view-home-div-dateclosed").html(''))
            {
                jQuery("#hst-controls-tickets-view-home-div-dateclosed").html("Not closed yet");
            }

            //rendering messages
            hst_tickets_viewticket_ticketevents_renderlistevents(response.data.TicketEvents);

            //adding param to the file upload utility
            jQuery("#hst-dashboard-ticketview-attachments-form-paramEntityId").val(response.data.TicketId);

            //setting ticket data panel (right pane)
            jQuery("#hst-controls-tickets-view-ticketdata-dd-category").val(response.data.TicketCategoryId);
            jQuery("#hst-controls-tickets-view-ticketdata-dd-status").val(response.data.TicketStatusId);
            jQuery("#hst-controls-tickets-view-ticketdata-dd-priority").val(response.data.TicketPriorityId);
            jQuery("#hst-controls-tickets-view-ticketdata-div-agentdisplayname").html(response.data.TicketAssignedUserDisplayName);
            jQuery("#hst-controls-tickets-view-ticketdata-div-agentemail").html(response.data.TicketAssignedUserEmail);
            jQuery("#hst-controls-tickets-view-ticketdata-div-customeravatar").html(response.data.TicketCustomerUserAvatar);
            jQuery("#hst-controls-tickets-view-ticketdata-div-agentavatar").html(response.data.TicketAssignedUserAvatar);

            //set readonly inputs?
            if (response.data.TicketDateClosedText != "")
            {
                hst_ticket_view_setreadonlycontrols("readonly");
                jQuery("#hst-controls-tickets-view-infopanel-closedticket").show();
            }
            else
            {
                hst_ticket_view_setreadonlycontrols("readwrite");
                jQuery("#hst-controls-tickets-view-infopanel-closedticket").hide();
            }


            hst_shared_removeoverlay();

        }

    });

}


//fired when the user clicks on the update ticket data linkbutton
function hst_tickets_viewticket_updateclick() {

    hst_shared_displayoverlay();

    //getting fields values from input controls
    inputValueTicketCategoryId = jQuery("#hst-controls-tickets-view-ticketdata-dd-category").val();
    inputValueTicketStatusId = jQuery("#hst-controls-tickets-view-ticketdata-dd-status").val();
    inputValueTicketPriorityId = jQuery("#hst-controls-tickets-view-ticketdata-dd-priority").val();

    //getting tickets data
    jQuery.ajax(
    {
        url: hstvars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'hst_ajax_dashboard_tickets_updatedata',
            security: hstvars.ajaxNonce,
            TicketId: hst_dashboard_tickets_currentticketidview,
            TicketCategoryId: inputValueTicketCategoryId,
            TicketStatusId: inputValueTicketStatusId,
            TicketPriorityId: inputValueTicketPriorityId
        },
        success: function (response) {

            hst_tickets_loadtickets();

            //refreshes the ticket events 
            hst_tickets_viewticket_prepare();

            //informs user of success saving
            hst_shared_displaynotify("saved", "", "Ticket info changed correctly.");

            hst_shared_removeoverlay();

        }

    });

}


function hst_tickets_viewticket_updatecustomer(CustomerIdToApply) {

    jQuery.ajax(
    {
        url: hstvars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'hst_ajax_dashboard_tickets_updatedata',
            security: hstvars.ajaxNonce,
            TicketId: hst_dashboard_tickets_currentticketidview,
            TicketCustomerId: CustomerIdToApply
        },
        success: function (response) {

            hst_tickets_loadtickets();

            //refreshes the ticket events 
            hst_tickets_viewticket_ticketevents_loadlistevents();

            //informs user of success saving
            hst_shared_displaynotify("saved", "", "Customer was updated correctly.");

        }

    });

}


//fired when the user clicks on the save message button in the ticket events
function hst_tickets_viewticket_ticketevents_savemessageclick() {

    hst_shared_displayoverlay();

    //getting fields values from input controls
    inputMessageContent = "";
    if (jQuery("#wp-hstcontrolsticketsviewpaneleditor-wrap").hasClass("tmce-active")) {
        inputMessageContent = tinyMCE.activeEditor.getContent();
    } else {
        inputMessageContent = jQuery('#hstcontrolsticketsviewpaneleditor').val();
    }

    //saving message into ticket's events
    jQuery.ajax(
    {
        url: hstvars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'hst_ajax_dashboard_tickets_events_addmessage',
            security: hstvars.ajaxNonce,
            TicketId: hst_dashboard_tickets_currentticketidview,
            MessageContent: inputMessageContent
        },
        success: function (response) {

            if (response.success == 1) {

                hst_shared_closemodal();

                //refreshes the ticket events (refreshes whole ticket)
                hst_tickets_viewticket_prepare();

                hst_shared_removeoverlay();

                //informs user of success saving
                hst_shared_displaynotify("saved", "", "Your message was added.");

                hst_tickets_view_ticket_savemessage_sendnotifications(response.data);

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


//sending email notifications after new message creation
function hst_tickets_view_ticket_savemessage_sendnotifications(ticketEventId) {

    var notificationEmailNotify = hst_shared_displaynotify("sendemail", "Sending Notifications", "This process can take some time, you can continue your work!");

    jQuery.ajax(
    {
        url: hstvars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'hst_ajax_shared_notifications_ticketmessagecreate',
            security: hstvars.ajaxNonce,
            TicketId: hst_dashboard_tickets_currentticketidview,
            TicketEventId: ticketEventId
        },
        success: function (response) {
            notificationEmailNotify.close();
        }

    });

}


//refreshes the ticket's events list in the left pane
function hst_tickets_viewticket_ticketevents_loadlistevents() {

    //getting events list
    jQuery.ajax(
    {
        url: hstvars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'hst_ajax_dashboard_tickets_events_list',
            security: hstvars.ajaxNonce,
            TicketId: hst_dashboard_tickets_currentticketidview,
        },
        success: function (response) {
            hst_tickets_viewticket_ticketevents_renderlistevents(response.data);

        }

    });

}


//this function is called when loading a single ticket or reloading the events
function hst_tickets_viewticket_ticketevents_renderlistevents(eventsList) {

    if (eventsList != undefined) {

        if (eventsList.length > 0) {

            var htmlContent = "";

            //iterating the events/messages
            jQuery.each(eventsList, function (index, ticketEventInfo) {

                var htmlThisEvent = "";

                //sets the template according if the event is a message or a datachange event
                if (ticketEventInfo.TicketEventType == "ticketdatachange") {

                    if (ticketEventInfo.TicketEventAuthorIsMe == 1) {
                        htmlThisEvent += hstvars.templateRenderTicketEventsDataChangeSelf;
                    }
                    else {
                        htmlThisEvent += hstvars.templateRenderTicketEventsDataChange;
                    }

                    //replacing tokens
                    htmlThisEvent = htmlThisEvent.replace(new RegExp('##DataChangeContent##', 'g'), ticketEventInfo.TicketEventUserDataUpdateContent);
                    htmlThisEvent = htmlThisEvent.replace(new RegExp('##EventDateText##', 'g'), ticketEventInfo.TicketEventDateText);
                    htmlThisEvent = htmlThisEvent.replace(new RegExp('##EventDateTimePassed##', 'g'), ticketEventInfo.TicketEventDateHumanTimeDiff);
                    htmlThisEvent = htmlThisEvent.replace(new RegExp('##AuthorDisplayName##', 'g'), ticketEventInfo.TicketEventUserDisplayName);
                    htmlThisEvent = htmlThisEvent.replace(new RegExp('##AuthorAvatar##', 'g'), ticketEventInfo.TicketEventAuthorAvatar);

                }

                if (ticketEventInfo.TicketEventType == "message") {

                    if (ticketEventInfo.TicketEventAuthorIsMe == 1) {
                        htmlThisEvent += hstvars.templateRenderTicketEventsMessagesSelf;
                    }
                    else {
                        htmlThisEvent += hstvars.templateRenderTicketEventsMessages;
                    }

                    //replacing tokens
                    htmlThisEvent = htmlThisEvent.replace(new RegExp('##MessageContent##', 'g'), ticketEventInfo.TicketEventMessageContent);
                    htmlThisEvent = htmlThisEvent.replace(new RegExp('##EventDateText##', 'g'), ticketEventInfo.TicketEventDateText);
                    htmlThisEvent = htmlThisEvent.replace(new RegExp('##EventDateTimePassed##', 'g'), ticketEventInfo.TicketEventDateHumanTimeDiff);
                    htmlThisEvent = htmlThisEvent.replace(new RegExp('##AuthorDisplayName##', 'g'), ticketEventInfo.TicketEventUserDisplayName);
                    htmlThisEvent = htmlThisEvent.replace(new RegExp('##AuthorAvatar##', 'g'), ticketEventInfo.TicketEventAuthorAvatar);

                }

                //adding to result string
                htmlContent += htmlThisEvent;

            });

            //place the generated content in the content placeholder
            jQuery("#hst-controls-tickets-view-panel-ticketevents-placeholder").html(htmlContent);
            jQuery("#hst-controls-tickets-view-panel-ticketevents-placeholder").show();
        }
        else
        {
            jQuery("#hst-controls-tickets-view-panel-ticketevents-placeholder").html('');
            jQuery("#hst-controls-tickets-view-panel-ticketevents-placeholder").hide();
        }

    }

}


//this function displays the correct panel when the user clicks on the tabs
function hst_tickets_viewticket_showpanel(controlKey) {

    //hiding all panels
    jQuery("#hst-controls-tickets-view-panel-tickethome").hide();
    jQuery("#hst-controls-tickets-view-panel-ticketattachments").hide();
    jQuery("#hst-controls-tickets-view-panel-ticketothers").hide();

    //removing all active objects in menu
    jQuery("#hst-controls-tickets-view-tabs-li-home").removeClass("active");
    jQuery("#hst-controls-tickets-view-tabs-li-attachments").removeClass("active");
    jQuery("#hst-controls-tickets-view-tabs-li-others").removeClass("active");

    //setting global variable to this panel
    hst_dashboard_tickets_currentticketviewpanelkey = controlKey;

    //shows correct panel
    if (hst_dashboard_tickets_currentticketviewpanelkey == "home") {
        jQuery("#hst-controls-tickets-view-panel-tickethome").show();
        jQuery("#hst-controls-tickets-view-tabs-li-home").addClass("active");
    }
    if (hst_dashboard_tickets_currentticketviewpanelkey == "attachments") {
        jQuery("#hst-controls-tickets-view-panel-ticketattachments").show();
        jQuery("#hst-controls-tickets-view-tabs-li-attachments").addClass("active");
    }
    if (hst_dashboard_tickets_currentticketviewpanelkey == "others") {
        jQuery("#hst-controls-tickets-view-panel-ticketothers").show();
        jQuery("#hst-controls-tickets-view-tabs-li-others").addClass("active");
    }

}


//This function prepares the page to add a new support ticket
function hst_tickets_new_prepare() {

    //clear all controls and set defaults
    jQuery("#hst-controls-tickets-new-txt-customerdisplayname").val('');
    jQuery("#hst-controls-tickets-new-txt-customeremail").val('');
    jQuery("#hst-controls-tickets-new-txt-tickettitle").val('');
    jQuery("#hst-controls-tickets-new-txt-ticketproblem").val('');
    jQuery("#hst-controls-tickets-new-dd-category").val('0');
    jQuery("#hst-controls-tickets-new-dd-priority").val('0');
    jQuery("#hst-controls-tickets-new-customertype-new").prop("checked", true);
    hst_tickets_new_customertypeswitch("new");

    jQuery.ajax(

        {
            url: hstvars.ajaxHandlerUrl,
            type: 'post',
            dataType: 'json',
            data:
            {
                action: 'hst_ajax_dashboard_customers_list',
                security: hstvars.ajaxNonce
            },
            success: function (response) {

                //adding elements to the dropdown list

                var optionListHtml = "";

                jQuery.each(response.data, function (index, customerInfo) {

                    optionListHtml += '<option value="' + customerInfo.CustomerId + '">' + customerInfo.CustomerDisplayName + '</option>'

                });

                jQuery("#hst-controls-tickets-new-dd-customer").append(optionListHtml);

            }

        }

    );

}



//This handles the input switches for new or existing customer
function hst_tickets_new_customertypeswitch(customerType) {

    if (customerType == "new") {

        jQuery("#hst-controls-tickets-new-txt-customerdisplayname").removeAttr("disabled");
        jQuery("#hst-controls-tickets-new-txt-customeremail").removeAttr("disabled");
        jQuery("#hst-controls-tickets-new-btnselectcustomer").hide();
        jQuery("#hst-controls-tickets-new-divCustomer").html("(no customer selected)");
        jQuery("#hst-controls-tickets-new-divCustomerId").html("0");

    }

    if (customerType == "existing") {

        jQuery("#hst-controls-tickets-new-txt-customerdisplayname").attr('disabled', 'disabled');
        jQuery("#hst-controls-tickets-new-txt-customeremail").attr('disabled', 'disabled');
        jQuery("#hst-controls-tickets-new-btnselectcustomer").show();
        jQuery("#hst-controls-tickets-new-divCustomer").html("(no customer selected)");
        jQuery("#hst-controls-tickets-new-divCustomerId").html("0");

    }

}


//function called when the user clicks on Back To Tickets List in the ticket's creation page
function hst_tickets_new_backtolist() {

    hst_dashboard_setHashAndNavigate("tickets-list");

}


//This handles the input switches for new or existing customer
function hst_tickets_new_saveticket() {

    hst_shared_displayoverlay();

    //clears validation
    has_shared_clearInputValidationCSS("hst-controls-tickets-new-txt-customerdisplayname");
    has_shared_clearInputValidationCSS("hst-controls-tickets-new-txt-customeremail");
    has_shared_clearInputValidationCSS("hst-controls-tickets-new-txt-tickettitle");
    has_shared_clearInputValidationCSS("hst-controls-tickets-new-txt-ticketproblem");
    has_shared_clearInputValidationCSS("hst-controls-tickets-new-dd-category");
    has_shared_clearInputValidationCSS("hst-controls-tickets-new-dd-priority");

    var postParamsTicketCustomerId = null;
    var postParamsTicketCustomerDisplayName = null;
    var postParamsTicketCustomerEmail = null;
    var postParamsTicketTitle = null;
    var postParamsTicketProblem = null;
    var postParamsTicketCategoryId = null;
    var postParamsTicketPriorityId = null;
    var postParamsCustomerType = null;

    //assigning params
    if (jQuery("#hst-controls-tickets-new-customertype-new").is(':checked')) {
        postParamsTicketCustomerId = 0;
        postParamsTicketCustomerDisplayName = jQuery("#hst-controls-tickets-new-txt-customerdisplayname").val();
        postParamsTicketCustomerEmail = jQuery("#hst-controls-tickets-new-txt-customeremail").val();
        postParamsCustomerType = "new";
    }
    if (jQuery("#hst-controls-tickets-new-customertype-existing").is(':checked')) {
        postParamsTicketCustomerId = jQuery("#hst-controls-tickets-new-divCustomerId").html();
        postParamsTicketCustomerDisplayName = "";
        postParamsTicketCustomerEmail = "";
        postParamsCustomerType = "existing";
    }
    postParamsTicketTitle = jQuery("#hst-controls-tickets-new-txt-tickettitle").val();
    postParamsTicketProblem = jQuery("#hst-controls-tickets-new-txt-ticketproblem").val();
    postParamsTicketCategoryId = jQuery("#hst-controls-tickets-new-dd-category").val();
    postParamsTicketPriorityId = jQuery("#hst-controls-tickets-new-dd-priority").val();

    jQuery.ajax(
     {
         url: hstvars.ajaxHandlerUrl,
         type: 'post',
         dataType: 'json',
         data:
         {
             action: 'hst_ajax_dashboard_tickets_create',
             security: hstvars.ajaxNonce,
             TicketCustomerId: postParamsTicketCustomerId,
             TicketCustomerDisplayName: postParamsTicketCustomerDisplayName,
             TicketCustomerEmail: postParamsTicketCustomerEmail,
             TicketTitle: postParamsTicketTitle,
             TicketProblem: postParamsTicketProblem,
             TicketCategoryId: postParamsTicketCategoryId,
             TicketPriorityId: postParamsTicketPriorityId,
             CustomerType: postParamsCustomerType
         },
         success: function (response) {

             if (response.success == 1) {

                 hst_tickets_loadtickets();

                 hst_dashboard_setHashAndNavigate("tickets-list");

                 hst_shared_removeoverlay();

                 //informs user of success saving
                 hst_shared_displaynotify("", "Support Ticket Created", "The support Ticket was created with the number: <strong>" + response.data + "</strong>");

                 //now sending email for this ticket creation, in case
                 hst_tickets_new_sendnotifications(response.data);

             }
             else {

                 if (response.errorCode == "inputValidation") {
                     //checking for input validation message
                     hst_shared_handleAjaxValidationResponse(response.errorMessage, response.validationInputId);
                     return;
                 }

                 if (response.errorCode == "errorCreateCustomer") {
                     //error creating a new customer, probably duplicated emails
                     hst_shared_handleAjaxValidationResponse(response.errorMessage, "hst-controls-tickets-new-txt-customeremail");
                     return;
                 }
                 

             }

         }

     }

 );

}


//This function will set the inputs hidden and the controls in readonly state. Used for closed tickets.
function hst_ticket_view_setreadonlycontrols( mode )
{

    if (mode == "readonly")
    {
        jQuery("#hst-controls-tickets-view-home-btn-changecustomer").hide();
        jQuery("#hst-controls-tickets-view-panel-ticketevents-btn-deleteticket").hide();
        jQuery("#hst-controls-tickets-view-ticketdata-dd-category").attr("disabled", "disabled");
        jQuery("#hst-controls-tickets-view-ticketdata-dd-priority").attr("disabled", "disabled");
        jQuery("#hst-controls-tickets-view-ticketdata-dd-status").attr("disabled", "disabled");
        jQuery("#hst-controls-tickets-view-ticketdata-btn-saveticketdata").hide();
        jQuery("#hst-controls-tickets-view-panel-ticketevents-btn-savemessage").hide();
        jQuery("#hst-controls-tickets-view-panel-ticketattachments-btn-saveattachment").hide();
    }

    if (mode == "readwrite") {
        jQuery("#hst-controls-tickets-view-home-btn-changecustomer").show();
        jQuery("#hst-controls-tickets-view-panel-ticketevents-btn-deleteticket").show();
        jQuery("#hst-controls-tickets-view-ticketdata-dd-category").removeAttr("disabled");
        jQuery("#hst-controls-tickets-view-ticketdata-dd-status").removeAttr("disabled");
        jQuery("#hst-controls-tickets-view-ticketdata-dd-priority").removeAttr("disabled");
        jQuery("#hst-controls-tickets-view-ticketdata-btn-saveticketdata").show();
        jQuery("#hst-controls-tickets-view-panel-ticketevents-btn-savemessage").show();
        jQuery("#hst-controls-tickets-view-panel-ticketattachments-btn-saveattachment").show();
    }

}



//function called when user clicks on ticket delete button
function hst_tickets_viewticket_deleteticketclick() {

    swal({
        title: 'Are you sure?',
        text: "Click OK if you want to delete this Support Ticket and all connected elements",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#588c00',
        cancelButtonColor: '#588c00',
    }).then(function () {
        hst_tickets_viewticket_deleteticketconfirmed();
    })



}


//function called when user confirms ticket delete
function hst_tickets_viewticket_deleteticketconfirmed() {

    hst_shared_displayoverlay();

    jQuery.ajax(
    {
        url: hstvars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'hst_ajax_dashboard_tickets_delete',
            security: hstvars.ajaxNonce,
            TicketId: hst_dashboard_tickets_currentticketidview
        },
        success: function (response) {

            //goes back to ticket list
            hst_tickets_viewticket_gobacktolistclick();

            hst_shared_removeoverlay();

        }

    });

}


//function called when user clicks on go back to list of tickets
function hst_tickets_viewticket_gobacktolistclick() {

    //refreshes list for possible changes that might have occurred
    hst_tickets_loadtickets();

    //navigates back to tickets list
    hst_dashboard_setHashAndNavigate("tickets-list");

}



//sending email notifications after ticket creation
function hst_tickets_new_sendnotifications(ticketId) {

    var notificationEmailNotify = hst_shared_displaynotify("sendemail", "Sending Notifications", "This process can take some time, you can continue your work!");

    jQuery.ajax(
    {
        url: hstvars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'hst_ajax_shared_notifications_ticketcreate',
            security: hstvars.ajaxNonce,
            TicketId: ticketId
        },
        success: function (response) {
            notificationEmailNotify.close();
        }

    });

}


//loads the list of attachments for this ticket
function hst_tickets_view_loadattachments() {

    jQuery.ajax(
    {
        url: hstvars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'hst_ajax_dashboard_shared_listattachments',
            security: hstvars.ajaxNonce,
            entityId: hst_dashboard_tickets_currentticketidview,
            entityType: "ticket"
        },
        success: function (response) {

            //hst-controls-tickets-view-panel-ticketattachments-nodata

            if (response.data.length > 0) {

                jQuery("#hst-controls-tickets-view-panel-ticketattachments-nodata").hide();
                jQuery("#hst-controls-tickets-view-attachments").show();

                jQuery('#hst-controls-tickets-view-attachments').DataTable(
                {
                    "dom": 't',
                    "bDestroy": true,
                    "bAutoWidth": false,
                    data: response.data,
                    columns:
                    [
                        { "width": "40%", title: "File", data: "AttachmentFilename", render: function (data, type, full, meta) { return data; } },
                        { "width": "15%", title: "Uploaded By", data: "AttachmentUploadUserDisplayName", render: function (data, type, full, meta) { return data; } },
                        { "width": "20%", title: "Uploaded On", data: "AttachmentCreatedDateText", render: function (data, type, full, meta) { return data; } },
                        { "width": "10%", title: "Size", data: "AttachmentSize", render: function (data, type, full, meta) { return data; } },
                        { "width": "10%", title: "Action", data: "AttachmentId", render: function (data, type, full, meta) { return "<a class='btn btn-labeled btn-primary btn-table' target='new' href='" + full.AttachmentUrl + "'><i class='glyphicon glyphicon-pencil'></i></button>"; } }
                    ],
                    "order": [[0, "desc"]]
                });

            }
            else {
                jQuery("#hst-controls-tickets-view-panel-ticketattachments-nodata").show();
                jQuery("#hst-controls-tickets-view-attachments").hide();
            }


         }

      });

 }


//handles the return message for the attachments upload attemp
function hst_tickets_view_fileuploadresponse(responseKey) {

    //generic system error
    if (responseKey == "error") {
        //display notification
        hst_shared_displaynotify("error", "Ouch!", "A system error has occurred. Please contact us for assistance!")
    }

    //user not authorized
    if (responseKey == "notauthorized") {
        //display notification
        hst_shared_displaynotify("error", "Not Authorized", "You are not authorized to perform this action.")
    }

    //no file attached
    if (responseKey == "nofile") {
        //display notification
        hst_shared_displaynotify("validation", "No File Selected", "Please select a file to upload and try again.")
    }

    //not allowed extension
    if (responseKey == "extensionnotallowed") {
        //display notification
        hst_shared_displaynotify("validation", "Extension not allowed", "The type of file (extension) you are trying to upload is not allowed.")
    }

    //file too large
    if (responseKey == "filetoolarge") {
        //display notification
        hst_shared_displaynotify("validation", "File too large", "The file you are trying to upload is too large.")
    }

    //all good
    if (responseKey == "ok") {
        //refreshes attachments list
        hst_tickets_view_loadattachments();
        //closes modal
        hst_shared_closemodal();
        //display notification
        hst_shared_displaynotify("saved", "File Uploaded", "The file was uploaded and attached to the Ticket.")
    }

}


