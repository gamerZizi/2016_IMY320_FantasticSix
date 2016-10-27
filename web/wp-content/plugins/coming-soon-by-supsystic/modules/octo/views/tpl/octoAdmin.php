<section>
	<div class="supsystic-item supsystic-panel">
		<div id="containerWrapper">
			<ul class="supsystic-bar-controls">
				<li title="<?php _e('Delete selected', SCS_LANG_CODE)?>">
					<button class="button" id="scsPagesRemoveGroupBtn" disabled data-toolbar-button>
						<i class="fa fa-fw fa-trash-o"></i>
						<?php _e('Delete selected', SCS_LANG_CODE)?>
					</button>
				</li>
				<li title="<?php _e('Clear All')?>">
					<button class="button" id="scsPagesClearBtn" disabled data-toolbar-button>
						<?php _e('Clear', SCS_LANG_CODE)?>
					</button>
				</li>
				<li title="<?php _e('Search', SCS_LANG_CODE)?>">
					<input id="scsPagesTblSearchTxt" type="text" name="tbl_search" placeholder="<?php _e('Search', SCS_LANG_CODE)?>">
				</li>
			</ul>
			<div id="scsPagesTblNavShell" class="supsystic-tbl-pagination-shell"></div>
			<div style="clear: both;"></div>
			<hr />
			<table id="scsPagesTbl"></table>
			<div id="scsPagesTblNav"></div>
			<div id="scsPagesTblEmptyMsg" style="display: none;">
				<h3><?php _e('You have no Templates for now.', SCS_LANG_CODE)?></h3>
			</div>
		</div>
		<div style="clear: both;"></div>
	</div>
</section>