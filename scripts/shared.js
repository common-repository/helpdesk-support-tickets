var hst_current_modaltype = "";                                     //holds the last modal type that was opened
var hst_current_modalsubtype = "";                                  //holds the last modal sub type that was opened

var hst_shared_colors = ['#3366CC', '#DC3912', '#FF9900', '#109618', '#990099', '#3B3EAC', '#0099C6', '#DD4477', '#66AA00', '#B82E2E', '#316395', '#994499', '#22AA99', '#AAAA11', '#6633CC', '#E67300', '#8B0707', '#329262', '#5574A6', '#3B3EAC']

//displays a notify popup
function hst_shared_displaynotify(type, ptitle, message) {

    var iconpath = "images/check_32.png";
    var title = ptitle;
    var notifyClassName = "minimalistgreen";
    var valueDelay = 2000;

    if (type == "validation") {
        iconpath = "images/warning_32.png";
        notifyClassName = "minimalistyellow";
        valueDelay = 4000;
    }

    if (type == "saved") {
        iconpath = "images/check_32.png"
        title = "Saved!";
        notifyClassName = "minimalistgreen";
        valueDelay = 2000;
    }

    if (type == "deleted") {
        iconpath = "images/add_32.png"
        title = "Deleted";
        notifyClassName = "minimalistgreen";
        valueDelay = 2000;
    }

    if (type == "error") {
        iconpath = "images/warning_32.png";
        notifyClassName = "minimalistyellow"
        valueDelay = 2000;
    }

    if (type == "sendemail") {
        iconpath = "images/sending_email_32.png";
        notifyClassName = "minimalistgreen";
        valueDelay = 0;
    }

    if (type == "testsendemailpositive") {
        iconpath = "images/check_32.png";
        notifyClassName = "minimalistgreen";
        valueDelay = 2000;
    }

    if (type == "testsendemailpositive") {
        iconpath = "images/check_32.png";
        notifyClassName = "minimalistgreen";
        valueDelay = 2000;
    }

    if (type == "testsendemailnegative") {
        iconpath = "images/redcross_32.png";
        notifyClassName = "minimalistyellow";
        valueDelay = 2000;
    }

    return jQuery.notify(
    {
        icon: hstvars.pluginBaseUrl + iconpath,
        title: title,
        message: message,
    },
    {
        type: notifyClassName,
        icon_type: 'image',
        template: '<div data-notify="container" class="row alert alert-{0}" role="alert">' +
                        '<div class="col-md-3">' +
                        '<img data-notify="icon" class="img-circle pull-left">' +
                        '</div">' +
                        '<div class="col-md-9">' +
                        '<span data-notify="title">{1}</span>' +
                        '<div class="clear">' +
                        '<span data-notify="message">{2}</span>' +
                        '</div">' +
                    '</div>',
        animate:
        {
            enter: 'animated fadeInRight',
            exit: 'animated fadeOutRight'
        },
        offset: 10,
        z_index: 99931,
        delay: valueDelay,
        placement:
        {
            from: "bottom",
            align: "right"
        }
    });

}


//This function displays a background coverlay over the plugin content
function hst_shared_displayoverlay() {

    //jQuery("#hst-ajaxloader").addClass('hst-ajax-loader-show');
    jQuery("#hst-ajaxloader").fadeIn(200);
}


//This function removes an overlay previously displayed
function hst_shared_removeoverlay() {

    //jQuery("#hst-ajaxloader").removeClass('hst-ajax-loader-show');
    jQuery("#hst-ajaxloader").fadeOut(200);

}


//checks the query string for "goto=" parameters
//pagetype can be "dashboard" or "frontend"
function hst_shared_navigateonload(pagetype) {

    //reading params
    qparam_goto = hst_shared_getquerystringparam("goto");
    qparam_ticketid = hst_shared_getquerystringparam("ticketid");

    if (qparam_goto != undefined) {

        //navigate to some ticket id details page
        if ( pagetype == "dashboard")
        {
            if (qparam_goto == "ticketview")
            {
                hst_dashboard_tickets_currentticketidview = qparam_ticketid;
                hst_dashboard_setHashAndNavigate("tickets-view");
            }
        }
        if ( pagetype == "frontend")
        {
            if (qparam_goto == "ticketview")
            {
                hst_fe_current_ticketId = qparam_ticketid;
                hst_fe_viewticket_load();
                hst_fe_hideallpanels();
                hst_fe_navigate("viewticket");
            }
        }
    }

};



//read querystring params
function hst_shared_getquerystringparam(paramName) {

    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === paramName) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }

}



//displays a modal window given its id
function hst_shared_showmodal(modaltype, modalsubtype) {

    //showing correct modal and initialize it
    if (modaltype == "users") {
        hst_current_modaltype = modaltype;
        hst_current_modalsubtype = modalsubtype;
        jQuery('#hst-dashboard-modal-users').addClass('md-show');
        hst_shared_showmodal_users_loadtable();
    }

    //showing correct modal and initialize it
    if (modaltype == "customers") {
        hst_current_modaltype = modaltype;
        hst_current_modalsubtype = modalsubtype;
        hst_shared_showmodal_customers_loadtable();
    }

    //showing correct modal and initialize it
    if (modaltype == "uploadattachment") {
        hst_current_modaltype = modaltype;
        hst_current_modalsubtype = modalsubtype;
        jQuery('#hst-dashboard-modal-fileupload').addClass('md-show');
    }

    //showing correct modal and initialize it
    if (modaltype == "addticketmessage") {
        hst_current_modaltype = modaltype;
        hst_current_modalsubtype = modalsubtype;
        jQuery('#hst-dashboard-modal-addticketmessage').addClass('md-show');
    }

    //showing overlay
    jQuery('.md-overlay').addClass('md-show');

}

//closes a modal window given its id
function hst_shared_closemodal() {

    //showing correct modal and initialize it
    if (hst_current_modaltype == "users") {
        jQuery('#hst-dashboard-modal-users').removeClass('md-show');
    }
    if (hst_current_modaltype == "customers") {
        jQuery('#hst-dashboard-modal-customers').removeClass('md-show');
    }
    if (hst_current_modaltype == "uploadattachment") {
        jQuery('#hst-dashboard-modal-fileupload').removeClass('md-show');
    }
    if (hst_current_modaltype == "addticketmessage") {
        jQuery('#hst-dashboard-modal-addticketmessage').removeClass('md-show');
    }

    //showing overlay
    jQuery('.md-overlay').removeClass('md-show');

}


//loads the users table in the modal
function hst_shared_showmodal_users_loadtable() {

    hst_shared_displayoverlay();
    jQuery('#hst-dashboard-modal-users-table').DataTable().clear().draw();

    jQuery.ajax(
    {
        url: hstvars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'hst_ajax_dashboard_users_list',
            security: hstvars.ajaxNonce
        },
        success: function (response) {

            jQuery('#hst-dashboard-modal-users-table').DataTable(
            {
                "dom": 't',
                "bDestroy": true,
                "bAutoWidth": false,
                data: response.data,
                columns:
                [
                    { "width": "10%", title: "Id", data: "UserId", className: "text-center", render: function (data, type, full, meta) { return "<div class='label label-default'>" + data + "</div>"; } },
                    { "width": "80%", title: "Full Name", data: "UserDisplayName", render: function (data, type, full, meta) { return data; } },
                    { "width": "10%", title: "Action", data: "UserId", className: "text-center", render: function (data, type, full, meta) { return "<button type='button' class='btn btn-labeled btn-primary btn-table' onclick='hst_shared_showmodal_users_loadtable_userclicked(" + data + ");'><i class='glyphicon glyphicon-pencil'></i></button>"; } }
                ],
                "order": [[1, "asc"]],
                "scrollY": "300px",
                "scrollCollapse": true,
                "paging": false
            });

            hst_shared_removeoverlay();

        }

    });

}


//loads the customers table in the modal
function hst_shared_showmodal_customers_loadtable() {

    hst_shared_displayoverlay();
    jQuery('#hst-dashboard-modal-customers-table').DataTable().clear().draw();

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

            jQuery('#hst-dashboard-modal-customers-table').DataTable(
            {
                "dom": 't',
                "bDestroy": true,
                "bAutoWidth": false,
                data: response.data,
                columns:
                [
                    { "width": "10%", title: "Id", data: "CustomerId", className: "text-center", render: function (data, type, full, meta) { return "<div class='label label-default'>" + data + "</div>"; } },
                    { "width": "40%", title: "Customer Name", data: "CustomerDisplayName", render: function (data, type, full, meta) { return data; } },
                    { "width": "40%", title: "Email Address", data: "CustomerEmail", render: function (data, type, full, meta) { return data; } },
                    { "width": "10%", title: "Action", data: "CustomerId", className: "text-center", render: function (data, type, full, meta) { return "<button type='button' class='btn btn-labeled btn-primary btn-table' onclick='hst_shared_showmodal_users_loadtable_customerclicked(" + data + ");'><i class='glyphicon glyphicon-pencil'></i></button>"; } }
                ],
                "order": [[1, "asc"]],
                "scrollY": "300px",
                "scrollCollapse": true,
                "paging": false
            });

            hst_shared_removeoverlay();

            jQuery('#hst-dashboard-modal-customers').addClass('md-show');

        }

    });

}

//happens on user click
function hst_shared_showmodal_users_loadtable_userclicked(userid) {

    jQuery.ajax(
    {
        url: hstvars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'hst_ajax_dashboard_users_get',
            security: hstvars.ajaxNonce,
            UserId: userid
        },
        success: function (response) {

            jQuery("#hst-controls-settings-generic-divDefaultAgent").html(response.data.UserDisplayName);
            jQuery("#hst-controls-settings-generic-divDefaultAgentId").html(response.data.UserId);
            hst_shared_closemodal();

        }

    });

}


//happens on customer click
function hst_shared_showmodal_users_loadtable_customerclicked(customerid) {

    jQuery.ajax(
    {
        url: hstvars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'hst_ajax_dashboard_customers_get',
            security: hstvars.ajaxNonce,
            CustomerId: customerid
        },
        success: function (response) {

            if (hst_current_modalsubtype == "ticketview")
            {

                jQuery("#hst-controls-tickets-view-ticketdata-div-customerdisplayname").html(response.data.CustomerDisplayName);
                jQuery("#hst-controls-tickets-view-ticketdata-div-customerdisplayemail").html(response.data.CustomerEmail);
                jQuery("#hst-controls-tickets-view-ticketdata-div-customeravatar").html(response.data.CustomerAvatar);
                hst_tickets_viewticket_updatecustomer(customerid);

                hst_shared_closemodal();

            }

            if (hst_current_modalsubtype == "ticketnew") {

                jQuery("#hst-controls-tickets-new-divCustomer").html(response.data.CustomerDisplayName);
                jQuery("#hst-controls-tickets-new-divCustomerId").html(response.data.CustomerId);

                hst_shared_closemodal();

            }

        }

    });

}


//This function is triggered on ajax responses, altering the user about a missing field or validation triggered.
//It will display a notification to the user
function hst_shared_handleAjaxValidationResponse(validationMessage, inputIdToValidate) {

    jQuery("#" + inputIdToValidate).addClass('hst-panels-input-haserror');
    hst_shared_displaynotify("validation", "Please check your input", validationMessage);
    hst_shared_removeoverlay();

}


//This function is triggered on ajax responses, altering the user about a missing field or validation triggered.
//It will display a notification to the user, this version of the function is used only in the frontend.
function hst_fe_shared_handleAjaxValidationResponse(validationMessage, inputIdToValidate) {

    jQuery("#" + inputIdToValidate).addClass('hst-panels-input-haserror');
    //hst_shared_displaynotify("validation", "Please check your input", validationMessage);
    //hst_shared_removeoverlay();

}



//removes the validation error class from a input control
function has_shared_clearInputValidationCSS(inputId) {

    jQuery("#" + inputId).removeClass('hst-panels-input-haserror');

}



//This function is triggered when an Ajax fails to return 200 status.
//It will display a window to the user
function hst_shared_handleAjaxStatusError(errorCode) {

    if (errorCode == "generic") {
        swal(
              'Sorry, an error has occurred',
              'There was an error with the request. Please contact assistance',
              'error'
            );
    }
    if (errorCode == "notificationfailed") {
        swal(
              'Could not send Notification',
              'There was an error while sending the email. Please make sure that your website is capable to send emails and that the Helpdesk email address is set.',
              'error'
            );
    }


    hst_shared_removeoverlay();

}

//This function is triggered on any javascript error that might occur.
//It will display a window to the user
function hst_shared_handleScriptError(errorMsg, url, lineNumber) {

    swal(
          'Sorry, an error has occurred',
          'Unfortunately, the plugin encountered a problem. These are the details of the issue:<br>' + errorMsg + "<br>" + url + "<br>" + lineNumber,
          'error'
        );

    hst_shared_removeoverlay();

}


//This function is triggered on any javascript error that might occur.
//It will display a window to the user
function hst_shared_showModalWarning(title, message) {

    swal(
          title,
          message,
          'warning'
        );

    hst_shared_removeoverlay();

}

