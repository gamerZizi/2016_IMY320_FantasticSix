<?php
/**
 * Footer Menu Links
 * @links http://css-tricks.com/snippets/wordpress/remove-li-elements-from-output-of-wp_nav_menu/
 */
if ( tamatebako_is_menu_registered( 'social-links' ) ){
	$args = array(
		'theme_location'  => 'social-links',
		'container'       => false,
		'echo'            => false,
		'items_wrap'      => '<ul class="social-links">%3$s</ul>',
		'depth'           => 1,
		'link_before'     => '<span class="screen-reader-text">',
		'link_after'      => '</span>',
		'fallback_cb'     => '__return_false',
	);
	?>
	<div id="social-links">
		<?php //echo strip_tags( wp_nav_menu( $args ), '<span><a>' ); ?>
		<?php echo wp_nav_menu( $args ); ?>
	</div>
<?php
}