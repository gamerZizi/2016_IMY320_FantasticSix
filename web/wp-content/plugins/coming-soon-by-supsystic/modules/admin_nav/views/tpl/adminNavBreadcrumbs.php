<?php
	$countBreadcrumbs = count($this->breadcrumbsList);
?>
<nav id="supsystic-breadcrumbs" class="supsystic-breadcrumbs <?php dispatcherScs::doAction('adminBreadcrumbsClassAdd')?>">
	<?php dispatcherScs::doAction('beforeAdminBreadcrumbs')?>
	<?php foreach($this->breadcrumbsList as $i => $crumb) { ?>
		<a class="supsystic-breadcrumb-el" href="<?php echo $crumb['url']?>"><?php echo $crumb['label']?></a>
		<?php if($i < ($countBreadcrumbs - 1)) { ?>
			<span class="breadcrumbs-separator"></span>
		<?php }?>
	<?php }?>
	<?php dispatcherScs::doAction('afterAdminBreadcrumbs')?>
</nav>