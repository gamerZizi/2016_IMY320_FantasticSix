<?php
class admin_navViewScs extends viewScs {
	public function getBreadcrumbs() {
		$this->assign('breadcrumbsList', dispatcherScs::applyFilters('mainBreadcrumbs', $this->getModule()->getBreadcrumbsList()));
		return parent::getContent('adminNavBreadcrumbs');
	}
}
