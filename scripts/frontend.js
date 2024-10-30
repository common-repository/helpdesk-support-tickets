//global vars
var hst_fe_current_panelKey = "";                            //holds the current main panel to show
var hst_fe_current_ticketId = 0;                             //holds the current ticket id managed
var hst_fe_navigation_on_load_done = 0;                      //used to stop executing navigations on load (for example, after the redirection by a link in a notification, it will prevent to execture the redirection again)

//page load
jQuery( document ).ready( function() 
{

    //redirects on load?
    if (hst_fe_navigation_on_load_done == 0) {

        hst_shared_navigateonload("frontend");
        hst_fe_navigation_on_load_done = 1;

    }

});

//handles top menu navigation clicks
function hst_fe_navigate(controlKey) {

    //setting global variable to this panel
    hst_fe_current_panelKey = controlKey;

    //shows correct panel
    if (hst_fe_current_panelKey == "home") {
        hst_fe_hideallpanels();
        jQuery("#hst-fe-home").show();
    }
    if (hst_fe_current_panelKey == "createticket") {
        hst_fe_hideallpanels();
        hst_fe_createticket_clearcontent();
        jQuery("#hst-fe-createticket").show();
    }
    if (hst_fe_current_panelKey == "createticketconfirmation") {
        jQuery("#hst-fe-createticketconfirmation").show();
    }
    if (hst_fe_current_panelKey == "mytickets") {
        hst_fe_displayloading();
        hst_fe_mytickets_loadtickets();
    }
    if (hst_fe_current_panelKey == "viewticket") {
        jQuery("#hst-fe-viewticket").show();
    }

}


function hst_fe_hideallpanels()
{

    //hiding all panels
    jQuery("#hst-fe-home").hide();
    jQuery("#hst-fe-createticket").hide();
    jQuery("#hst-fe-createticketconfirmation").hide();
    jQuery("#hst-fe-mytickets").hide();
    jQuery("#hst-fe-viewticket").hide();

}


function hst_fe_displayloading()
{

    offsetTopTitle = 66;
    offsetAdjust = 12;
    overlayWidth = jQuery("#hst-container").width() - 2;
    overlayHeight = jQuery("#hst-container").height() - offsetTopTitle + offsetAdjust;
    
    jQuery("#hst-ajaxloader").css("width", overlayWidth);
    jQuery("#hst-ajaxloader").css("height", overlayHeight);
    jQuery("#hst-ajaxloader").fadeIn();

}
function hst_fe_hideloading()
{
    jQuery("#hst-ajaxloader").fadeOut(100);
}


//button home create ticket was clicked
function hst_fe_home_createticketclick() {
    hst_fe_navigate("createticket");
}

//button search ticket was clicked
function hst_fe_home_myticketsclick()
{
    hst_fe_navigate("mytickets");
}

//button go back from my tickets
function hst_fe_mytickets_goback() {
    hst_fe_navigate("home");
}

//button create new from my tickets
function hst_fe_mytickets_create() {
    hst_fe_navigate("createticket");
}

//button ticket clicked from my tickets
function hst_fe_mytickets_ticketclick(ticketId) {
    hst_fe_displayloading();
    hst_fe_current_ticketId = ticketId;
    hst_fe_viewticket_load();
}

//button save ticket from create ticket
function hst_fe_createticket_updateclick() {
    hst_fe_createticket_submit();
}

//button cancel from create ticket
function hst_fe_createticket_cancelclick() {
    hst_fe_navigate("home");
}

//button clicked go back from ticket view
function hst_fe_viewticket_goback() {
    hst_fe_navigate("mytickets");
}

//button clicked go back from ticket view
function hst_fe_viewticket_sendmessageclick() {
    hst_fe_viewticket_sendmessage();
}

//button clicked to go back from ticket confirmation page
function hst_fe_createticketconfirmation_gobackclick()
{
    hst_fe_navigate("home");
}



//clear the existing content of the ticket creation page
function hst_fe_createticket_clearcontent()
{

    has_shared_clearInputValidationCSS("hst-fe-createticket-customerdisplayname");
    has_shared_clearInputValidationCSS("hst-fe-createticket-customeremail");
    has_shared_clearInputValidationCSS("hst-fe-createticket-tickettitle");
    has_shared_clearInputValidationCSS("hst-fe-createticket-ticketproblem");
    has_shared_clearInputValidationCSS("hst-fe-createticket-dd-category");

    jQuery("#hst-fe-ticketcreation-div-validation").hide();
    jQuery("#hst-fe-ticketcreation-div-validationmessage").html('');

    jQuery("#hst-fe-createticket-tickettitle").val('');
    jQuery("#hst-fe-createticket-ticketproblem").val('');
    jQuery("#hst-fe-createticket-dd-category").val('0');
}


//loads/refreshes the list of tickets
function hst_fe_mytickets_loadtickets() {

    jQuery("#hst-fe-mytickets-ticketsplaceholder").html('');
    jQuery("#hst-fe-mytickets-ticketsplaceholder").hide();
    jQuery("#hst-fe-mytickets-norecords").hide();

    jQuery.ajax(
        {
            url: hstvars.ajaxHandlerUrl,
            type: 'post',
            dataType: 'json',
            data:
            {
                action: 'hst_ajax_frontend_tickets_list',
                security: hstvars.ajaxNonce
            },
            success: function (response) {

                //renderes the list of tickets found
                hst_fe_mytickets_loadtickets_renderlist(response.data.Tickets);

                //display pane
                hst_fe_hideallpanels();
                jQuery("#hst-fe-mytickets").show();
                hst_fe_hideloading();

            }

        }

    );

}

//this function is called when the list of tickets is loaded and needs to be rendered
function hst_fe_mytickets_loadtickets_renderlist(ticketsList) {

    var htmlContent = "";

    //iterating the tickets
    jQuery.each(ticketsList, function (index, ticketInfo) {

        var htmlThisTicket = hstvars.templateRenderTicketFrontEnd;

        //replacing tokens
        htmlThisTicket = htmlThisTicket.replace(new RegExp('##TicketId##', 'g'), ticketInfo.TicketId);
        htmlThisTicket = htmlThisTicket.replace(new RegExp('##TicketTitle##', 'g'), ticketInfo.TicketTitle);
        htmlThisTicket = htmlThisTicket.replace(new RegExp('##TicketStatusText##', 'g'), ticketInfo.TicketStatusText);

        //adding to result string
        htmlContent += htmlThisTicket;

    });

    //place the generated content in the content placeholder
    jQuery("#hst-fe-mytickets-ticketsplaceholder").html(htmlContent);

    if (htmlContent == "")
    {
        jQuery("#hst-fe-mytickets-ticketsplaceholder").hide();
        jQuery("#hst-fe-mytickets-norecords").show();
    }
    else
    {
        jQuery("#hst-fe-mytickets-ticketsplaceholder").show();
        jQuery("#hst-fe-mytickets-norecords").hide();
    }
    

}




//used to send ticket creation data from customer
function hst_fe_createticket_submit() {

    hst_fe_displayloading();

    has_shared_clearInputValidationCSS("hst-fe-createticket-customerdisplayname");
    has_shared_clearInputValidationCSS("hst-fe-createticket-customeremail");
    has_shared_clearInputValidationCSS("hst-fe-createticket-tickettitle");
    has_shared_clearInputValidationCSS("hst-fe-createticket-ticketproblem");
    has_shared_clearInputValidationCSS("hst-fe-createticket-dd-category");

    jQuery("#hst-fe-ticketcreation-div-validation").hide();
    jQuery("#hst-fe-ticketcreation-div-validationmessage").html('');

    var postParamsTicketCustomerDisplayName = null;
    var postParamsTicketCustomerEmail = null;
    var postParamsTicketTitle = null;
    var postParamsTicketProblem = null;
    var postParamsTicketCategoryId = null;

    postParamsTicketCustomerDisplayName = jQuery("#hst-fe-createticket-customerdisplayname").val();
    postParamsTicketCustomerEmail = jQuery("#hst-fe-createticket-customeremail").val();
    postParamsTicketTitle = jQuery("#hst-fe-createticket-tickettitle").val();
    postParamsTicketProblem = jQuery("#hst-fe-createticket-ticketproblem").val();
    postParamsTicketCategoryId = jQuery("#hst-fe-createticket-dd-category").val();

    jQuery.ajax(
     {
         url: hstvars.ajaxHandlerUrl,
         type: 'post',
         dataType: 'json',
         data:
         {
             action: 'hst_ajax_frontend_tickets_create',
             security: hstvars.ajaxNonce,
             TicketCustomerDisplayName: postParamsTicketCustomerDisplayName,
             TicketCustomerEmail: postParamsTicketCustomerEmail,
             TicketTitle: postParamsTicketTitle,
             TicketProblem: postParamsTicketProblem,
             TicketCategoryId: postParamsTicketCategoryId
         },
         success: function (response)
         {

             hst_fe_hideloading();

             if (response.success == 1)
             {

                 //displays a thank you message, showing the generated ticket number
                 jQuery("#hst-fe-createticketconfirmation-div-ticketnumbergenerated").html(response.data);

                 //sending email
                 hst_fe_createticket_submit_sendnotification(response.data);

                 //showing confirm
                 hst_fe_hideallpanels();
                 hst_fe_navigate("createticketconfirmation");

             }
             else {

                 if (response.errorCode == "inputValidation") {
                     //checking for input validation message
                     hst_fe_shared_handleAjaxValidationResponse(response.errorMessage, response.validationInputId);
                     jQuery("#hst-fe-ticketcreation-div-validation").show();
                     jQuery("#hst-fe-ticketcreation-div-validationmessage").html(response.errorMessage);
                     return;
                 }

                 if (response.errorCode == "errorCreatingCustomer") {
                     //probably email of the user being already used
                     hst_fe_shared_handleAjaxValidationResponse(response.errorMessage, "hst-fe-createticket-customeremail");
                     jQuery("#hst-fe-ticketcreation-div-validation").show();
                     jQuery("#hst-fe-ticketcreation-div-validationmessage").html(response.errorMessage);
                     return;
                 }
                    

             }


         }

     }

     );

}



//sending email notifications after ticket creation
function hst_fe_createticket_submit_sendnotification(ticketId) {

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
        }

    });

}


//used to load an existing ticket
function hst_fe_viewticket_load() {

    jQuery.ajax(
     {
         url: hstvars.ajaxHandlerUrl,
         type: 'post',
         dataType: 'json',
         data:
         {
             action: 'hst_ajax_frontend_tickets_getsingle',
             security: hstvars.ajaxNonce,
             TicketId: hst_fe_current_ticketId
         },
         success: function (response) {


             jQuery("#hst-fe-viewticket-div-ticketid").html(response.data.TicketId);
             jQuery("#hst-fe-viewticket-div-tickettitle").html(response.data.TicketTitle);
             jQuery("#hst-fe-viewticket-div-ticketproblem").html(response.data.TicketProblem);
             jQuery("#hst-fe-viewticket-div-ticketcreateddate").html(response.data.TicketDateCreatedText);
             jQuery("#hst-fe-viewticket-div-ticketlastupdateddate").html(response.data.TicketDateLastUpdatedHumanTimeDiff);


             //renderes the list of tickets found
             hst_fe_ticketview_rendermessages(response.data.TicketEvents);

             hst_fe_hideloading();

             hst_fe_hideallpanels();
             hst_fe_navigate("viewticket");             

         }

     }

     );

}

//this function is called to render the messages of a ticket
function hst_fe_ticketview_rendermessages(ticketEvents) {

    var htmlContent = "";

    //iterating the events/messages
    jQuery.each(ticketEvents, function (index, ticketEventInfo) {

        htmlThisTicketEvent = "";

        if (ticketEventInfo.TicketEventAuthorIsMe == 1) {
            htmlThisTicketEvent += hstvars.templateRenderTicketEventFrontEndSelf;
        }
        else {
            htmlThisTicketEvent += hstvars.templateRenderTicketEventFrontEndOthers;
        }

        //replacing tokens
        htmlThisTicketEvent = htmlThisTicketEvent.replace(new RegExp('##MessageContent##', 'g'), ticketEventInfo.TicketEventMessageContent);
        htmlThisTicketEvent = htmlThisTicketEvent.replace(new RegExp('##EventDateText##', 'g'), ticketEventInfo.TicketEventDateText);
        htmlThisTicketEvent = htmlThisTicketEvent.replace(new RegExp('##EventDateTimePassed##', 'g'), ticketEventInfo.TicketEventDateHumanTimeDiff);
        htmlThisTicketEvent = htmlThisTicketEvent.replace(new RegExp('##AuthorAvatar##', 'g'), ticketEventInfo.TicketEventAuthorAvatar);

        //adding to result string
        htmlContent += htmlThisTicketEvent;

    });

    //place the generated content in the content placeholder
    jQuery("#hst-fe-viewticket-div-messagesplaceholder").html(htmlContent);

    if (htmlContent == "")
    {
        jQuery("#hst-fe-viewticket-messages-norecords").show();
        jQuery("#hst-fe-viewticket-div-messagesplaceholder").hide();
    }
    else
    {
        jQuery("#hst-fe-viewticket-messages-norecords").hide();
        jQuery("#hst-fe-viewticket-div-messagesplaceholder").show();
    }

    jQuery("#hst-fe-ticketview-div-validation").hide();
    jQuery("#hst-fe-ticketview-div-validationmessage").html('');

    has_shared_clearInputValidationCSS("hst_fe_viewticket-txt-newmessage");

}

//this function is called when the user wants to send a new message
function hst_fe_viewticket_sendmessage() {

    hst_fe_displayloading();

    jQuery("#hst-fe-ticketview-div-validation").hide();
    jQuery("#hst-fe-ticketview-div-validationmessage").html('');

    has_shared_clearInputValidationCSS("hst_fe_viewticket-txt-newmessage");

    jQuery.ajax(
     {
         url: hstvars.ajaxHandlerUrl,
         type: 'post',
         dataType: 'json',
         data:
         {
             action: 'hst_ajax_frontend_tickets_events_addmessage',
             security: hstvars.ajaxNonce,
             TicketId: hst_fe_current_ticketId,
             MessageContent: jQuery("#hst_fe_viewticket-txt-newmessage").val()
         },
         success: function (response)
         {



             if (response.success == 1) {

                 jQuery("#hst_fe_viewticket-txt-newmessage").val('');

                 //refreshing message list
                 hst_fe_viewticket_load();

                 hst_fe_viewticket_addmessage_sendnotification(response.data);

             }
             else {

                 if (response.errorCode == "inputValidation") {

                     hst_fe_hideloading();

                     //checking for input validation message
                     hst_fe_shared_handleAjaxValidationResponse(response.errorMessage, response.validationInputId);
                     jQuery("#hst-fe-ticketview-div-validation").show();
                     jQuery("#hst-fe-ticketview-div-validationmessage").html(response.errorMessage);
                     return;
                 }

             }

         }

     });

}

//sending email notifications after message creation
function hst_fe_viewticket_addmessage_sendnotification(ticketEventId) {

    jQuery.ajax(
    {
        url: hstvars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'hst_ajax_shared_notifications_ticketmessagecreate',
            security: hstvars.ajaxNonce,
            TicketId: hst_fe_current_ticketId,
            TicketEventId: ticketEventId
        },
        success: function (response) {
        }

    });

}