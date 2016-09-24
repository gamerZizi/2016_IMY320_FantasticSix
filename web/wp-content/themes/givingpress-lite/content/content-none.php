<?php
/**
 * This template is used when no content is present.
 *
 * @package GivingPress Lite
 * @since GivingPress Lite 1.0
 */

?>

<!-- BEGIN .no-results -->
<div class="no-results">

<?php if ( get_option('show_on_front') == 'page' && current_user_can( 'publish_posts') ) { ?>

	<h2 class="headline text-center"><?php esc_html_e( 'No Options Saved', 'givingpress-lite' ); ?></h2>
	<p class="text-center"><?php printf( wp_kses( __( 'Please set and save the theme options for the Home Page template within the <a href="%1$s">Customizer</a>.', 'givingpress-lite' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( admin_url( 'customize.php' ) ) ); ?></p>

<?php } else { ?>

	<h2 class="headline"><?php esc_html_e( 'No Results Found', 'givingpress-lite' ); ?></h2>
	<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'givingpress-lite' ); ?></p>
	<div class="no-result-search"><?php get_template_part( 'searchform' ); ?></div>

<?php } ?>

<!-- END .no-results -->
</div>
