<?php
/**
 * The left sidebar template for our theme.
 * This template is used to display the sidebar on three column page template.
 *
 * @package GivingPress Lite
 * @since GivingPress Lite 1.0
 */

?>

<?php if ( is_active_sidebar( 'sidebar-left' ) ) { ?>

	<div class="sidebar left">
		<?php dynamic_sidebar( 'sidebar-left' ); ?>
	</div>

<?php } ?>
