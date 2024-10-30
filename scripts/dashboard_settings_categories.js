var hst_dashboard_settings_ticket_categories_currentEditId = 0;                            //holds the current edit id 


//loads the list of ticket categories
function hst_dashboard_settings_ticket_categories_loadlist() {

    hst_shared_displayoverlay();

    jQuery.ajax(

      {
          url: hstvars.ajaxHandlerUrl,
          type: 'post',
          dataType: 'json',
          data:
          {
              action: 'hst_ajax_dashboard_settings_ticket_categories_list',
              security: hstvars.ajaxNonce
          },
          success: function (response)
          {

              jQuery("#hst-controls-settings-ticket-categories-table > tbody").empty();

              jQuery.each(response.data, function (index, categoryInfo) {

                  newRowContent = "<tr>";

                  newRowContent += "<td>" + categoryInfo.TicketCategoryDescription + "</td>";
                  newRowContent += "<td><button type='button' class='btn btn-labeled btn-primary btn-table pull-right' onclick='hst_dashboard_settings_ticket_categories_editclick(" + categoryInfo.TicketCategoryId + ");'><i class='glyphicon glyphicon-pencil'></i></button></td>";

                  newRowContent += "</tr>";

                  jQuery('#hst-controls-settings-ticket-categories-table > tbody:last-child').append(newRowContent);

                  hst_shared_removeoverlay();

              });             

          }

      }

  );

}


//clicked on the back to settings buttons
function hst_dashboard_settings_ticket_categories_backtosettingsclick()
{

    hst_dashboard_setHashAndNavigate("settings");

}


//clicked on edit in the items table
function hst_dashboard_settings_ticket_categories_editclick( itemId )
{

    hst_shared_displayoverlay();

    //saves into local var
    hst_dashboard_settings_ticket_categories_currentEditId = itemId;

    //switches to edit panel
    jQuery("#hst-controls-settings-ticket-categories-editPanel").show();
    jQuery("#hst-controls-settings-ticket-categories-listPanel").hide();

    //loading item data
    jQuery.ajax(
    {
        url: hstvars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'hst_ajax_dashboard_settings_ticket_categories_get',
            TicketCategoryId: hst_dashboard_settings_ticket_categories_currentEditId,
            security: hstvars.ajaxNonce
        },
        success: function (response) {

            jQuery("#hst-controls-settings-ticket-categories-editPanel-txtName").val(response.data.TicketCategoryDescription);

            hst_shared_removeoverlay();

        }

    });

}



//clicked on save in the items table
function hst_dashboard_settings_ticket_categories_edit_saveclick() {

    hst_shared_displayoverlay();

    has_shared_clearInputValidationCSS("hst-controls-settings-ticket-categories-editPanel-txtName");

    //saving item data
    jQuery.ajax(
    {
        url: hstvars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'hst_ajax_dashboard_settings_ticket_categories_update',
            TicketCategoryId: hst_dashboard_settings_ticket_categories_currentEditId,
            TicketCategoryDescription: jQuery("#hst-controls-settings-ticket-categories-editPanel-txtName").val(),
            security: hstvars.ajaxNonce
        },
        success: function (response) {

            if (response.success == 1) {

                //reload dropdowns used in the plugin
                hst_dashboard_loadtablescontent();

                //refreshes list
                hst_dashboard_settings_ticket_categories_loadlist();

                //needed to get ticket status counters after tablelist items refresh
                hst_tickets_loadtickets()

                //goes to list panel
                hst_dashboard_settings_ticket_categories_edit_gobackclick();

                hst_shared_removeoverlay();

                hst_shared_displaynotify("saved", "", "Changes saved correctly.");

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



//clicked to delete a category from the DB
function hst_dashboard_settings_ticket_categories_edit_deleteclick() {

    swal({
        title: 'Are you sure?',
        text: "Click OK if you want to delete this Support Category",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#588c00',
        cancelButtonColor: '#588c00',
    }).then(function () {
        hst_dashboard_settings_ticket_categories_edit_delete_afterconfirm();
    })

}


//when the deleted is confirmed
function hst_dashboard_settings_ticket_categories_edit_delete_afterconfirm()
{

    hst_shared_displayoverlay();

    //deleting item data
    jQuery.ajax(
    {
        url: hstvars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'hst_ajax_dashboard_settings_ticket_categories_delete',
            TicketCategoryId: hst_dashboard_settings_ticket_categories_currentEditId,
            security: hstvars.ajaxNonce
        },
        success: function (response) {

            //reload dropdowns used in the plugin
            hst_dashboard_loadtablescontent();

            //refreshes list
            hst_dashboard_settings_ticket_categories_loadlist();

            //needed to get ticket status counters after tablelist items refresh
            hst_tickets_loadtickets()

            //goes to list panel
            hst_dashboard_settings_ticket_categories_edit_gobackclick();

            hst_shared_removeoverlay();

            hst_shared_displaynotify("deleted", "", "Category was deleted.");

        }

    });

}



//clicked from edit panel go back to list
function hst_dashboard_settings_ticket_categories_edit_gobackclick() {

    //saves into local var
    hst_dashboard_settings_ticket_categories_currentEditId = 0;

    //switches to edit panel
    jQuery("#hst-controls-settings-ticket-categories-editPanel").hide();
    jQuery("#hst-controls-settings-ticket-categories-listPanel").show();


}