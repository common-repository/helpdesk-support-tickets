//page load
//jQuery(document).ready(function ()
//{

//    hst_dashboardhome_loaddata();

//});



//loads/refreshes the dashboard data
function hst_dashboardhome_loaddata() {

    //checks that the page is visible (eg not installation wizard)
        hst_shared_displayoverlay();

        jQuery.ajax(

            {
                url: hstvars.ajaxHandlerUrl,
                type: 'post',
                dataType: 'json',
                data:
                {
                    action: 'hst_ajax_dashboard_home_getdata',
                    security: hstvars.ajaxNonce
                },
                success: function (response) {

                    //updates the status counters at top of dashboard
                    jQuery("#hst-dashboard-home-ticketcounterpanel-countpanel-statusnew").html(response.data.StatusCounters[0].count);
                    jQuery("#hst-dashboard-home-ticketcounterpanel-countpanel-statuspending").html(response.data.StatusCounters[1].count);
                    jQuery("#hst-dashboard-home-ticketcounterpanel-countpanel-statusclosed").html(response.data.StatusCounters[2].count);

                    //updates the status colors at top
                    jQuery("#hst-dashboard-home-ticketcounterpanel-iconpanel-statusnew").css("background-color", response.data.StatusCounters[0].bgcolor);
                    jQuery("#hst-dashboard-home-ticketcounterpanel-iconpanel-statuspending").css("background-color", response.data.StatusCounters[1].bgcolor);
                    jQuery("#hst-dashboard-home-ticketcounterpanel-iconpanel-statusclosed").css("background-color", response.data.StatusCounters[2].bgcolor);

                    //table last 5 tickets
                    if (response.data.LastTickets.length > 0) {

                        jQuery('#hst-dashboard-home-lasttickets-nodata').hide();

                        jQuery('#hst-dashboard-home-lasttickets-table').DataTable(
                            {
                                "dom": 't',
                                "bDestroy": true,
                                "bAutoWidth": false,
                                data: response.data.LastTickets,
                                columns:
                                [
                                    { "width": "5%", title: "N", data: "TicketId", className: "text-center", render: function (data, type, full, meta) { return "<div class='label label-default'>" + data + "</div>"; } },
                                    { "width": "20%", title: "Customer", data: "TicketCustomerUserDisplayName", render: function (data, type, full, meta) { return data; } },
                                    { "width": "35%", title: "Problem", data: "TicketTitle", render: function (data, type, full, meta) { return data; } },
                                    { "width": "10%", title: "Status", data: "TicketStatusText", className: "text-center", render: function (data, type, full, meta) { return "<div class='label' style='background-color:" + full.StatusBgColor + "'>" + data + "</div>"; } },
                                    { "width": "10%", title: "Action", data: "TicketCategoryText", className: "text-center", render: function (data, type, full, meta) { return "<button type='button' class='btn btn-labeled btn-primary btn-table' onclick='hst_tickets_listtickets_editticket(" + full.TicketId + ");'><i class='glyphicon glyphicon-pencil'></i></button>"; } }
                                ],
                                "order": [[0, "desc"]]
                            });

                        jQuery('#hst-dashboard-home-lasttickets-table').show();

                    }
                    else {

                        jQuery('#hst-controls-tickets-list-nodata').show();

                        jQuery('#hst-dashboard-home-lasttickets-table').DataTable(
                            {
                                "bDestroy": true
                            });
                        jQuery('#hst-dashboard-home-lasttickets-nodata').hide();

                    }

                    //chart last 10 days tickets
                    var ctx10daystickets = document.getElementById("hst-dashboard-home-10daystickets-chartplaceholder");
                    ctx10daystickets.height = 300;

                    var ctx10daystickets_labels = [];
                    var ctx10daystickets_values = [];
                    jQuery.each(response.data.AddedLast10Days, function (index, value) {
                        ctx10daystickets_labels.push(value.date);
                        ctx10daystickets_values.push(value.count);
                    });
                    var ctx10daysticketsClosed_values = [];
                    jQuery.each(response.data.ClosedLast10Days, function (index, value) {
                        ctx10daysticketsClosed_values.push(value.count);
                    });
                    var myChart = new Chart(ctx10daystickets, {
                        type: 'bar',
                        data: {
                            labels: ctx10daystickets_labels,
                            datasets: [
                                {
                                    label: "Created Tickets",
                                    data: ctx10daystickets_values,
                                    borderColor: response.data.StatusCounters[0].bgcolor,
                                    borderWidth: "0",
                                    backgroundColor: response.data.StatusCounters[0].bgcolor
                                },
                                {
                                    label: "Closed Tickets",
                                    data: ctx10daysticketsClosed_values,
                                    borderColor: response.data.StatusCounters[2].bgcolor,
                                    borderWidth: "0",
                                    backgroundColor: response.data.StatusCounters[2].bgcolor
                                }
                            ]
                        },
                        options: {
                            maintainAspectRatio: false,
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true
                                    }
                                }]
                            }
                        }
                    });

                    //Pie for categories

                    var ctxByCategory_labels = [];
                    var ctxByCategory_values = [];
                    jQuery.each(response.data.TicketsByCategory, function (index, value) {
                        if (value.name == null) {
                            value.name = "(none)";
                        }
                        ctxByCategory_labels.push(value.name);
                        ctxByCategory_values.push(value.count);
                    });
                    var ctxByCategory_data = {
                        labels: ctxByCategory_labels,
                        datasets: [
                            {
                                data: ctxByCategory_values,
                                backgroundColor: hst_shared_colors
                            }]
                    };
                    ctx = document.getElementById("hst-dashboard-home-pieforcategory-chartplaceholder");
                    ctx.height = 200;
                    var myPieChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: ctxByCategory_data,
                        options:
                        {
                            legend: { display: false }
                        }
                    });

                    //Pie for statuses

                    var ctxByStatus_labels = [];
                    var ctxByStatus_values = [];
                    jQuery.each(response.data.TicketsByStatus, function (index, value) {
                        if (value.name == null) {
                            value.name = "(none)";
                        }
                        ctxByStatus_labels.push(value.name);
                        ctxByStatus_values.push(value.count);
                    });
                    var ctxByStatus_data = {
                        labels: ctxByStatus_labels,
                        datasets: [
                            {
                                data: ctxByStatus_values,
                                backgroundColor: [response.data.StatusCounters[0].bgcolor, response.data.StatusCounters[1].bgcolor, response.data.StatusCounters[2].bgcolor]
                            }]
                    };
                    ctx = document.getElementById("hst-dashboard-home-pieforstatus-chartplaceholder");
                    ctx.height = 200;
                    var myPieChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: ctxByStatus_data,
                        options:
                        {
                            legend: { display: false }
                        }
                    });

                    //Pie for priorities

                    var ctxByPriority_labels = [];
                    var ctxByPriority_values = [];
                    jQuery.each(response.data.TicketsByPriority, function (index, value) {
                        if (value.name == null) {
                            value.name = "(none)";
                        }
                        ctxByPriority_labels.push(value.name);
                        ctxByPriority_values.push(value.count);
                    });
                    var ctxByPriority_data = {
                        labels: ctxByPriority_labels,
                        datasets: [
                            {
                                data: ctxByPriority_values,
                                backgroundColor: hst_shared_colors
                            }]
                    };
                    ctx = document.getElementById("hst-dashboard-home-pieforpriority-chartplaceholder");
                    ctx.height = 200;
                    var myPieChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: ctxByPriority_data,
                        options:
                        {
                            legend: { display: false }
                        }
                    });

                    hst_shared_removeoverlay();

                }

            }

        );

}