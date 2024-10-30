<div id="hst-fe-createticketconfirmation" class="hst-frontend-panel-default" style="display:none;">

    <div class="hst-frontend-panel-default-title">
        <?php _e("A New Support Ticket has been Created", "hst") ?>
    </div>

    <div class="hst-frontend-panel-parag text-center">
        <?php _e("Thank you for creating a Support Ticket.", "hst") ?>
		<?php _e("An Operator will manage your request as soon as possible, and contact you.", "hst") ?>
    </div>

    <div style="height:00px;clear:both;"></div>

    <div class="text-center">
        <?php _e("Your Ticket Number:", "hst") ?>
        <div id="hst-fe-createticketconfirmation-div-ticketnumbergenerated"></div>
    </div>
    
    <div style="clear:both;"></div>

    <div class="hst-button-genericbar text-center">

        <div class="hst-button hst-button-center " onclick="hst_fe_createticketconfirmation_gobackclick();">
            <i class="fa fa-button fa-arrow-left"></i>
            <span><?php _e("Go Back", "hst") ?></span>
        </div>

    </div>

    <div style="clear:both;"></div>

</div>