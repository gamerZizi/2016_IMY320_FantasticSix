<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Catch Themes
 * @subpackage Jomsom
 * @since Jomsom 0.1
 */
?>

<?php
/**
 * jomsom_before_secondary hook
 */
do_action( 'jomsom_before_secondary' );

$jomsom_layout = jomsom_get_theme_layout();

//Bail early if no sidebar layout is selected
if ( 'no-sidebar' == $jomsom_layout ) {
	return;
}

do_action( 'jomsom_before_primary_sidebar' );
?>
	<div id="secondary" class="widget-area" role="complementary">
		<?php
		if ( is_active_sidebar( 'primary-sidebar' ) ) {
        	dynamic_sidebar( 'primary-sidebar' );
   		}
		else {
			//Helper Text
			if ( current_user_can( 'edit_theme_options' ) ) { ?>
				<aside id="widget-default-text" class="widget widget_text">
                	<h2 class="widget-title"><?php _e( 'Primary Sidebar Widget Area', 'jomsom' ); ?></h2>

           			<div class="textwidget">
                   		<p><?php _e( 'This is the Primary Sidebar Widget Area if you are using a two or three column site layout option.', 'jomsom' ); ?></p>
                   		<p><?php printf( __( 'By default it will load Search and Archives widgets as shown below. You can add widget to this area by visiting your <a href="%s">Widgets Panel</a> which will replace default widgets.', 'jomsom' ), esc_url( admin_url( 'widgets.php' ) ) ); ?></p>
                 	</div>
	       		</aside><!-- #widget-default-text -->
			<?php
			} ?>
			<aside class="widget widget_search" id="default-search">
				<?php get_search_form(); ?>
			</aside><!-- #default-search -->
			<aside class="widget widget_archive" id="default-archives">
				<h2 class="widget-title"><?php _e( 'Archives', 'jomsom' ); ?></h2>
				<ul>
					<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
				</ul>
			</aside><!-- #default-archives -->
			<?php
		} ?>
	</div><!-- #secondary -->
<?php
/**
 * jomsom_after_primary_sidebar hook
 */
do_action( 'jomsom_after_primary_sidebar' );


/**
 * jomsom_after_secondary hook
 *
 */
do_action( 'jomsom_after_secondary' );