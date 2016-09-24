<?php
/**
 * The template for displaying the footer
 *
 * @package Catch Themes
 * @subpackage Jomsom
 * @since Jomsom 0.1
 */
?>

<?php
    /**
     * jomsom_after_content hook
     *
     * @hooked jomsom_content_end - 30
     * @hooked jomsom_promotion_headline - 40
     *
     */
    do_action( 'jomsom_after_content' );
?>

<?php
    /**
     * jomsom_footer hook
     *
     * @hooked jomsom_mobile_footer_nav_anchor - 20
     * @hooked jomsom_footer_content_start - 30
     * @hooked jomsom_footer_sidebar(footer-t) - 40
     * @hooked jomsom_footer_b_start - 50
     * @hooked jomsom_footer_menu - 60
     * @hooked jomsom_footer_social_icon - 70
     * @hooked jomsom_footer_content - 80
     * @hooked jomsom_footer_b_end - 90
     * @hooked jomsom_footer_content_end - 110
     * @hooked jomsom_page_end - 200
     *
     */
    do_action( 'jomsom_footer' );
?>

<?php
/**
 * jomsom_after hook
 *
 * @hooked jomsom_scrollup - 10
 * @hooked jomsom_mobile_menus- 20
 *
 */
do_action( 'jomsom_after' );?>

<?php wp_footer(); ?>

</body>
</html>