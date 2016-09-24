<?php

add_action('admin_menu', 'free_register_newsdesigns_submenu_page');

function free_register_newsdesigns_submenu_page() {
	add_submenu_page( 'edit.php?post_type=news', 'Pro News Designs', 'Pro News Designs', 'manage_options', 'newsdesigns-submenu-page', 'free_newsdesigns_page_callback' );
}

function free_newsdesigns_page_callback() {
	$wpnw_feed_tabs = array(
								'design-feed' 	=> __('Plugin Designs', 'sp-news-and-widget'),
								'plugins-feed' 	=> __('Our Plugins', 'sp-news-and-widget')
							);

	
	$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'design-feed';
	?>
	
	<div class="wrap wpnw-wrap">

		<h2 class="nav-tab-wrapper">
			<?php
			foreach ($wpnw_feed_tabs as $tab_key => $tab_val) {

				$active_cls = ($tab_key == $active_tab) ? 'nav-tab-active' : '';
				$tab_link 	= add_query_arg( array('post_type' => WPNW_POST_TYPE, 'page' => 'newsdesigns-submenu-page', 'tab' => $tab_key), admin_url('edit.php') );
			?>

			<a class="nav-tab <?php echo $active_cls; ?>" href="<?php echo $tab_link; ?>"><?php echo $tab_val; ?></a>

			<?php } ?>
		</h2>

		<div class="wpnw-tab-cnt-wrp">
		<?php 
			if( isset($_GET['tab']) && $_GET['tab'] == 'plugins-feed' ) {
				echo wpnw_get_design( 'plugins-feed' );
			} else {
				echo wpnw_get_design();
			}
		?>
		</div><!-- end .wpnw-tab-cnt-wrp -->

	</div><!-- end .wpnw-wrap -->

<?php
}

/**
 * Gets the plugin design part feed
 *
 * @package WP News and three widgets
 * @since 3.2.6
 */
function wpnw_get_design( $feed_type = '' ) {
	
	$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'design-feed';
	
	$cache = get_transient( 'wpnw_' . $active_tab );
	
	if ( false === $cache ) {
		
		// Feed URL
		if( $feed_type == 'plugins-feed' ) {
			$url = 'http://wponlinesupport.com/plugin-data-api/plugins-data.php';
		} else {
			$url = 'http://wponlinesupport.com/plugin-data-api/wp-news-and-widget/wp-news-and-widget.php';
		}

		$feed = wp_remote_get( esc_url_raw( $url ), array( 'timeout' => 120, 'sslverify' => false ) );

		if ( ! is_wp_error( $feed ) ) {
			if ( isset( $feed['body'] ) && strlen( $feed['body'] ) > 0 ) {
				$cache = wp_remote_retrieve_body( $feed );
				set_transient( 'wpnw_' . $active_tab, $cache, 172800 );
			}
		} else {
			$cache = '<div class="error"><p>' . __( 'There was an error retrieving the data from the server. Please try again later.', 'sp-news-and-widget' ) . '</div>';
		}
	}
	return $cache;
}