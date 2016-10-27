<style type="text/css">
	.scsAdminMainLeftSide {
		width: 56%;
		float: left;
	}
	.scsAdminMainRightSide {
		width: <?php echo (empty($this->oscsDisplayOnMainPage) ? 100 : 40)?>%;
		float: left;
		text-align: center;
	}
	#scsMainOccupancy {
		box-shadow: none !important;
	}
</style>
<section>
	<div class="supsystic-item supsystic-panel">
		<div id="containerWrapper">
			<?php _e('Main page Go here!!!!', SCS_LANG_CODE)?>
		</div>
		<div style="clear: both;"></div>
	</div>
</section>