
<div class="hst-panels-panel">

	<div class="hst-panels-panel-header">

		<div class="hst-panels-panel-title">
			<?php _e("Quick Ticket Search", "hst") ?>
		</div>

		<div class="clear"></div>

	</div>

	<div class="hst-panels-panel-body">

		<div>
			<strong>
				<?php _e("Search by Number, Customer, Problem:", "hst") ?>
			</strong>
		</div>

		<div class="input-group">
			<input type="text" class="form-control hst-panels-input" id="hst-controls-tickets-simplesearch-txt-filterkey" />
			<span class="input-group-addon">
				<i class="fa fa-bolt"></i>
			</span>
		</div>

		<div style="height:10px;"></div>

		<button type="button" class="btn btn-labeled btn-primary" onclick="hst_tickets_listtickets_searchclick();">
			<span class="btn-label">
				<i class="glyphicon glyphicon-search"></i>
			</span><?php _e("Search", "hst") ?>
		</button>

		<div class="clear"></div>

		<div class="hst-panels-sidepanelseparator"></div>

		<div>
			<strong>
				<?php _e("Filter By Status:", "hst") ?>
			</strong>
		</div>

		<div id="hst-controls-tickets-simplesearch-statusesplaceholder"></div>

		<div class="clear"></div>


	</div>

</div>
