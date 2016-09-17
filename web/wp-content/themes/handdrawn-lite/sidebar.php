<?php
/**
 * The sidebar containing the main widget area.
 *
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>
<div id="secondary" class="sidebar widget-area show-for-large large-4 columns <?php if('handdrawn-lite' == get_theme_mod( 'handdrawn_sidebar_style' )) echo 'handy'; ?>" role="complementary">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</div><!-- #secondary -->
