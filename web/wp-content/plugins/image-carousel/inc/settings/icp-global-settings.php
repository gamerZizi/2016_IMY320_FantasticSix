<?php

if ( ! defined( 'ABSPATH' ) ) exit;


function icp_menu_page() {
	
	add_menu_page( 'Carousel Settings', 'Image Carousel', 'manage_options', 'icp-carousel-settings', 'icp_settings_page', 'dashicons-images-alt2', 12 );
	
}


function icp_settings_page() {
	
	if( has_filter( 'icp_settings_page_filter' ) ) {
		
		apply_filters( 'icp_settings_page_filter', '' );
		
		}

	add_thickbox(); // @since 1.0.53
	wp_enqueue_style( 'icp-settings' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-tabs' );
	wp_enqueue_script( 'icp-settings-tab' );
	wp_enqueue_script( 'wp-color-picker' );
	wp_enqueue_style( 'wp-color-picker' );
	
ob_start();
	
?>
<div class="wrap">
<div style="height:250px;display:block;" class="panelloader ploader"></div>
<div class="settings-wrap" id="page-settings" style="display:none;">
    <div id="option-tree-header-wrap">
        <ul id="option-tree-header">
            <li id="option-tree-version"><span><?php echo ICP_ITEM_NAME; ?></span></li>
            <li style="float:right;" id="option-tree-version"><span>v <?php echo ICP_VERSION; ?></span></li>
        </ul>
    </div>
    <div id="option-tree-settings-api">
        <div class="ui-tabs ui-widget ui-widget-content ui-corner-all">
            <ul >
                <li class="icp_tab_items"><a href="#section_lightbox">Carousel Settings</a></li>
                <li class="icp_tab_items"><a href="#section_documentation">Documentation</a></li>
                <li data-nonce="<?php echo wp_create_nonce( "icp_free_plugins_nonce" ); ?>" class="icp_tab_items icp_free_plugins"><a href="#section_freeplugins">Free Install Plugins</a></li>
            <?php    
			if( has_filter( 'icp_admin_settings_menu_filter' ) ) {
				
				apply_filters( 'icp_admin_settings_menu_filter', '' );
				
				}
			?>
                
            </ul>
    <div id="poststuff" class="metabox-holder">
        <div id="post-body">
			<div id="post-body-content">
			
			<?php
            
			require_once( 'icp-carousel-settings.php' );
			require_once( 'pages/icp-docs.php' );
			require_once( 'pages/icp-free-plugins.php' );
			
			if( has_filter( 'icp_admin_settings_container_filter' ) ) {
				
				apply_filters( 'icp_admin_settings_container_filter', '' );
				
				}
				
				?>
            
            
    			</div>
    		</div>
    	</div>
    <div class="clear"></div>
    </div>
   </div>
</div>
</div>

<?php

$content = ob_get_clean();
echo $content;

}

?>