<?php

if ( ! defined( 'ABSPATH' ) ) exit;

?>

<div id="section_lightbox" class= "postbox">
        <div class="inside">
            <div id="design_customization_settings" class="format-settings">
                <div class="format-setting-wrap">
                
<script type="text/javascript">
	jQuery(document).ready(function($) {
		
		$('.icp_tooltip_link').bind('click', function() {
			$(this).siblings('.icp_sett_tooltip').slideToggle();
			});
			
		$('.icp_tooltip_link_close').on('click', function() {
			$('.icp_sett_tooltip').slideToggle();
			});
		
		});
	 </script> 
                
    <div class="icpnote red rounded"><span class="dashicons dashicons-megaphone" style="margin-right:8px;"></span><strong>NEW FEATURE:</strong> Ability to redirect an images to specific URL. <a class="icp_tooltip_link" href="JavaScript:void(0);">Watch this Video to learn more</a>
    <div style="display: none;margin-top:10px;" class="icp_sett_tooltip">
    <iframe width="750" height="377" src="https://www.youtube.com/embed/Ln0_KCa5Mh4?rel=0" frameborder="0" allowfullscreen></iframe>
    <a class="icp_tooltip_link_close" href="JavaScript:void(0);" style="float:right;margin-top:5px;color:#FFF;">close</a></div>
    </div> 
	<hr />	
		
	<h2><?php _e('General','image-carousel');?></h2><hr>
    	
	<form id="icp_lightbox_form" method="post" action="#">

		<table class="tbl_custom">

		<tr valign="top">
        <th scope="row"><?php _e('Carousel Auto Play', 'image-carousel');?></th>
        <td><select name="icp_carousel_autoplay" id="icp_carousel_autoplay">
            <option value="true" <?php selected( esc_attr( icp_get_option('icp_carousel_autoplay') ), 'true' ); ?>>Yes</option>
            <option value="false" <?php selected( esc_attr( icp_get_option('icp_carousel_autoplay') ), 'false' ); ?>>No</option>
            </select>
        <p class="description"><?php  _e('Should the Carousel autoplay on start or not', 'image-carousel'); ?></p>
        </td>
        </tr>
        
		<tr valign="top">
        <th scope="row"><?php _e('Show Caption', 'image-carousel');?></th>
        <td><select name="icp_carousel_caption" id="icp_carousel_caption">
            <option value="true" <?php selected( esc_attr( icp_get_option('icp_carousel_caption') ), 'true' ); ?>>Yes</option>
            <option value="false" <?php selected( esc_attr( icp_get_option('icp_carousel_caption') ), 'false' ); ?>>No</option>
            </select>
        <p class="description"><?php  _e('Use this option to show / hide Carousel captions', 'image-carousel'); ?></p>
        </td>
        </tr>
        
		<tr valign="top">
        <th scope="row"><?php _e('Fancy Caption', 'image-carousel');?></th>
        <td><select name="icp_fancy_caption" id="icp_fancy_caption">
            <option value="true" <?php selected( esc_attr( icp_get_option('icp_fancy_caption') ), 'true' ); ?>>Yes</option>
            <option value="false" <?php selected( esc_attr( icp_get_option('icp_fancy_caption') ), 'false' ); ?>>No</option>
            </select>
        <p class="description"><?php  _e('If Enabled the plugin will automatically convert uppercase the first character of each word and replace - with spaces in a title. For example : ferrari-f12-berlinetta will change to Ferrari F12 Berlinetta', 'image-carousel'); ?></p>
        </td>
        </tr>

		<tr valign="top">
        <th scope="row"><?php _e('Show Bullets Navigation', 'image-carousel');?></th>
        <td><select name="icp_carousel_bullet" id="icp_carousel_bullet">
            <option value="true" <?php selected( esc_attr( icp_get_option('icp_carousel_bullet') ), 'true' ); ?>>Yes</option>
            <option value="false" <?php selected( esc_attr( icp_get_option('icp_carousel_bullet') ), 'false' ); ?>>No</option>
            </select>
        <p class="description"><?php  _e('Use this option to show / hide Carousel bullets navigation', 'image-carousel'); ?></p>
        </td>
        </tr>
        
		<tr valign="top">
        <th scope="row"><?php _e('Slideshow Effect', 'image-carousel');?></th>
        <td><select style="width:155px;" name="icp_carousel_slide_effect" id="icp_carousel_slide_effect">
							<?php 
							
$easing = array( "easeInQuad", "easeOutQuad", "easeInOutQuad", "easeInCubic", "easeOutCubic", "easeInOutCubic", "easeInQuart", "easeOutQuart", "easeInOutQuart", "easeInQuint", "easeOutQuint", "easeInOutQuint", "easeInSine", "easeOutSine", "easeInOutSine", "easeInExpo", "easeOutExpo", "easeInOutExpo", "easeInCirc", "easeOutCirc", "easeInOutCirc", "easeInElastic" , "easeOutElastic", "easeInOutElastic", "easeInBack", "easeOutBack", "easeInOutBack", "easeInBounce", "easeOutBounce", "easeInOutBounce");

							$easing_sel = ( '' == icp_get_option('icp_carousel_slide_effect') ? 'easeOutBounce' : icp_get_option('icp_carousel_slide_effect') );
							
							foreach ( $easing as $key => $value ): ?>
							<option value="<?php esc_attr_e( $value ); ?>"<?php esc_attr_e( $value == $easing_sel ? ' selected="selected"' : '' ); ?>><?php esc_attr_e( $value ); ?></option>
							<?php endforeach;?>
						</select>
        <p class="description"><?php  _e('Choose an entrance animation for the Carousel. Default: easeOutBounce', 'image-carousel'); ?></p>
        </td>
        </tr>
        
		<tr valign="top">
        <th scope="row"><?php _e('Slideshow Interval', 'image-carousel');?></th>
        <td><select style="width:54px;" name="icp_carousel_slide_every" id="icp_carousel_slide_every">
							<?php 
							
							$intrvl = ( '' == icp_get_option('icp_carousel_slide_every') ? '3' : icp_get_option('icp_carousel_slide_every') );
				
								foreach ( range( 1, 60 ) as $i ) {
									
									$every[$i] = $i;
						
										}
							
							foreach ( $every as $key => $value ): ?>
							<option value="<?php esc_attr_e( $key ); ?>"<?php esc_attr_e( $key == $intrvl ? ' selected="selected"' : '' ); ?>><?php esc_attr_e( $value ); ?></option>
							<?php endforeach;?>
						</select> <span> seconds</span>
        <p class="description"><?php  _e('The time in seconds when autoplaying. Default 3 seconds', 'image-carousel'); ?></p>
        </td>
        </tr>
        
        <tr valign="top" >
        <th><?php _e('Carousel Items Width', 'image-carousel');?></th>
        
        <?php $c_width = ( icp_get_option('icp_carousel_width') ? icp_get_option('icp_carousel_width') : '250' ); ?>
        
        <td><input style="width:55px" name="icp_carousel_width" type="text" id="icp_carousel_width" value="<?php echo esc_attr_e( $c_width ); ?>" size="40" /><span> px</span>
        <p class="description"><?php  _e('Set the Carousel width to fit your needs. Default: 250 px', 'image-carousel'); ?></p>
        </td>
        </tr>
        
        <?php if ( trim( floatval( get_bloginfo('version') ) ) >= '4.4' ) { ?>
        
		<tr valign="top">
        <th scope="row"><?php _e('Carousel Auto Height', 'image-carousel');?></th>
        <td><select name="icp_carousel_adapt_height" id="icp_carousel_adapt_height">
            <option value="true" <?php selected( esc_attr( icp_get_option('icp_carousel_adapt_height') ), 'true' ); ?>>Yes</option>
            <option value="false" <?php selected( esc_attr( icp_get_option('icp_carousel_adapt_height') ), 'false' ); ?>>No</option>
            </select>
        <p class="description"><?php  _e('If enabled, this plugin will dynamically adjust slider height based on each slides height. If disabled, carousel items height will be equal with the width', 'image-carousel'); ?></p>
        </td>
        </tr>
        
        <?php } ?>
        
		<tr valign="top">
        <th scope="row"><?php _e('Carousel Items Margin', 'image-carousel');?></th>
        <td><select style="width:54px;" name="icp_carousel_margin" id="icp_carousel_margin">
							<?php 
							
							$margin = ( '' == icp_get_option('icp_carousel_margin') ? '10' : icp_get_option('icp_carousel_margin') );
				
								foreach ( range( 5, 30, 5 ) as $i ) {
									
									$mrgn[$i] = $i;
						
										}
							
							foreach ( $mrgn as $key => $value ): ?>
							<option value="<?php esc_attr_e( $key ); ?>"<?php esc_attr_e( $key == $margin ? ' selected="selected"' : '' ); ?>><?php esc_attr_e( $value ); ?></option>
							<?php endforeach;?>
						</select> <span> px</span>
        <p class="description"><?php  _e('Set margin between each carousel items. Default 10 px', 'image-carousel'); ?></p>
        </td>
        </tr>
        
		<tr valign="top">
        <th scope="row"><?php _e('Open in Lightbox when Carousel Clicked', 'image-carousel');?></th>
        <td>
        
        <?php
        
		
		$req_plugin = WP_PLUGIN_DIR . '/gallery-lightbox-slider';
		
		if ( is_dir( $req_plugin ) ) {
			
			if ( !is_plugin_active( 'gallery-lightbox-slider/gallery-lightbox-lite.php' ) ) {
			
			$action = 'activate';
			$slug = 'gallery-lightbox-slider/gallery-lightbox-lite.php';
			$activate = wp_nonce_url( admin_url( 'plugins.php?action=activate&plugin='.$slug.'' ), 'activate-plugin_'.$slug );
			
			echo '<p class="icpnotesfailed"><strong>Gallery Lightbox Lite</strong> is not active. You can activate from <a href="'.$activate.'">here</a></p>';
			
		} else { ?>
        
        <select name="icp_carousel_use_lightbox" id="icp_carousel_use_lightbox">
            <option value="yes" <?php selected( esc_attr( icp_get_option('icp_carousel_use_lightbox') ), 'yes' ); ?>>Yes</option>
            <option value="no" <?php selected( esc_attr( icp_get_option('icp_carousel_use_lightbox') ), 'no' ); ?>>No</option>
            </select>
        <p class="description"><?php  _e('Use this option to enable / disable Lighbox mode', 'image-carousel'); ?></p>
        <?php } } else {
			
			$action = 'install-plugin';
			$slug = 'gallery-lightbox-slider';
			$install_url = wp_nonce_url(
				add_query_arg(
					array(
						'action' => $action,
            			'plugin' => $slug
        				),
        		admin_url( 'update.php' )
   			 		),
    			$action.'_'.$slug
				);
			
			echo '<p class="icpnotesfailed"><strong>Gallery Lightbox Lite</strong> plugin is not installed. Please install first from <a href="'.$install_url.'">here</a></p>';	
			
		}?>
        </td>
        </tr>
        
        
        <tr valign="top" >
        <th><?php _e('Disable All Carousel', 'image-carousel');?></th>
        <td>
        
<input name="icp_global_active_sel" type="checkbox" id="icp_global_carousel_active" <?php checked( 'active', icp_get_option('icp_global_carousel_active') );?> value='off' />
   <input name="icp_global_carousel_active" type="hidden" class="icp_global_carousel_active" value='<?php print( icp_get_option('icp_global_carousel_active') ); ?>' />     
        <p class="description"><?php  _e('Use this option to mass Disable Carousel in all Post & Page', 'image-carousel'); ?></p>
        </td>
        </tr>
        
        <tr valign="top" >
        <th><?php _e('Image Cache Control', 'image-carousel');?></th>
        <td>
        <div class="gbox_cache_cont">
        	<p><span class='dashicons dashicons-arrow-right'></span>&nbsp;Total cache : <span id="cache_total"><?php echo icp_get_total_images();?> image(s)</span></p>
            <p><span class='dashicons dashicons-arrow-right'></span>&nbsp;Total cache size : <span id="cache_size"><?php echo icp_get_size_of_cache(); ?></span></p>
            <span data-nonce="<?php echo wp_create_nonce( "icp_clear_cache" ); ?>" class="button" id="icp_purge_cache" <?php if ( icp_get_total_images() <= 0 ) { echo 'disabled="disabled"';}?>>Purge All Caches</span><span id="loader_icp_purge"></span><span id="cache_act_status" style="display:none;position:relative;color:#148919; margin-left:11px; top:6px;"><?php _e('All Cache Cleared', 'image-carousel');?></span>
        </div>
        </td>
        </tr>
        
        
		</table>
        <hr />
            <?php    
			if( has_filter( 'icp_admin_settings_fields_filter' ) ) {
				
				apply_filters( 'icp_admin_settings_fields_filter', '' );
				
				?>
     <hr />           
	<table class="tbl_custom">
		<tr valign="top">
        <td><input data-formname="icp_lightbox_form" data-nonce="<?php echo wp_create_nonce( "icp_form_settings" ); ?>" type="submit" value="<?php _e('Save Changes'); ?>" class="button button-primary icp_form_submit" /><span id="loader_icp_lightbox_form"></span><span style="display:none;position:relative;color:#148919; margin-left:11px; top:6px;" class="set_icp_lightbox_form icp_save_status">Settings Saved</span></td>
        </tr>
		
		</table>                
                
                <?php

				
				} else { ?>


	<table class="tbl_custom">
		<tr valign="top">
        <td><input data-formname="icp_lightbox_form" data-nonce="<?php echo wp_create_nonce( "icp_form_settings" ); ?>" type="submit" value="<?php _e('Save Changes'); ?>" class="button button-primary icp_form_submit" /><span id="loader_icp_lightbox_form"></span><span style="display:none;position:relative;color:#148919; margin-left:11px; top:6px;" class="set_icp_lightbox_form icp_save_status">Settings Saved</span></td>
        </tr>
		
		</table>
                    <hr />
                    <table class="tbl_custom">
                    
                    	<tr valign="top">
                        	<td>
                    		<h2><a href="http://ghozylab.com/plugins/ordernow.php?order=addons-image-carousel" target="_blank"><span class="icl-btn icl-btn-danger"><span style="margin-right: 5px;top:5px;margin-top: 3px;" class="dashicons dashicons-cart"></span>UPGRADE NOW !</span></a></h2>
                    	  </td>
                    	</tr>
                    
                    	<tr valign="top">
                        	<td>
                    		<img src="<?php echo plugins_url( 'images/carousel-pro.png' , dirname(__FILE__) ); ?>" width="926" height="900" />
                    	  </td>
                    	</tr>
                        
                    	<tr valign="top">
                        	<td>
                    		<h2><a href="http://ghozylab.com/plugins/ordernow.php?order=addons-image-carousel" target="_blank"><span class="icl-btn icl-btn-danger"><span style="margin-right: 5px;top:5px;margin-top: 3px;" class="dashicons dashicons-cart"></span>UPGRADE NOW !</span></a></h2>
                    	  </td>
                    	</tr>
                        
                    </table>
                    
					
					<?php
				}
			?>        

	</form>	

				</div>
			</div>
		</div>
    </div>