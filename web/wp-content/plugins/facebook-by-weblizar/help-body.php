<style>
label {
	margin-right:10px;
}

#fb-msg {
	border: 1px #888888 solid; background-color: #C0CCFE; padding: 10px; font-size: inherit; font-weight: bold; font-family: inherit; font-style: inherit; text-decoration: inherit;
}
</style>
<script>
function SaveSettings(){
	var FacebookPageUrl = jQuery("#facebook-page-url").val();
	var ColorScheme = jQuery("#show-widget-header").val();	
	var Header = jQuery("#show-widget-header").val();
	var Stream = jQuery("#show-live-stream").val();
	var Width = jQuery("#widget-width").val();
	var Height = jQuery("#widget-height").val();
	var FbAppId = jQuery("#fb-app-id").val();
	if(!FacebookPageUrl) {
		jQuery("#facebook-page-url").focus();
		return false;
	}
	if(!FbAppId) {
		jQuery("#fb-app-id").focus();
		return false;
	}
	jQuery("#fb-save-settings").hide();
	jQuery("#fb-img").show();
	jQuery.ajax({
		url: location.href,
		type: "POST",
		data: jQuery("form#fb-form").serialize(),
		dataType: "html",
		//Do not cache the page
		cache: false,
		//success
		success: function (html) {
			jQuery("#fb-img").hide();
			jQuery("#fb-msg").show();
			
			setTimeout(function() {
				location.reload(true);
			}, 2000);
			
		}
	});
}
</script>

<?php
wp_enqueue_style('op-bootstrap-css', WEBLIZAR_FACEBOOK_PLUGIN_URL. 'css/bootstrap.min.css');
if(isset($_POST['facebook-page-url']) && isset($_POST['fb-app-id'])){
	$FacebookSettingsArray = serialize(
		array(
			'FacebookPageUrl' => $_POST['facebook-page-url'],
			'ColorScheme' =>	'',
			'Header' => $_POST['show-widget-header'],
			'Stream' => $_POST['show-live-stream'],
			'Width' => $_POST['widget-width'],
			'Height' => $_POST['widget-height'],
			'FbAppId' => $_POST['fb-app-id'],
			'ShowBorder' => 'true',
			'ShowFaces' => $_POST['show-fan-faces'],
			'ForceWall' => 'false'
		)
	);
	update_option("weblizar_facebook_shortcode_settings", $FacebookSettingsArray);
}
?>

<div class="block ui-tabs-panel active" id="option-general">		
	<div class="row">
		<div class="col-md-10">
			<div id="heading">
				<h2>Facebook Like Box [FBW] <?php _e( 'Shortcode Settings', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></h2>
			</div>
			<?php
			$FacebookSettings = unserialize(get_option("weblizar_facebook_shortcode_settings"));
			//load default values OR saved values
			$ForceWall = 'false';
			if ( isset( $FacebookSettings[ 'ForceWall' ] ) ) {
				$ForceWall = $FacebookSettings[ 'ForceWall' ];
			}

			$Header = 'true';
			if ( isset( $FacebookSettings[ 'Header' ] ) ) {
				$Header = $FacebookSettings[ 'Header' ];
			}

			$Height = 560;
			if ( isset( $FacebookSettings[ 'Height' ] ) ) {
				$Height = $FacebookSettings[ 'Height' ];
			}

			$FacebookPageUrl = 'https://www.facebook.com/pages/Weblizar/1440510482872657';
			if ( isset( $FacebookSettings[ 'FacebookPageUrl' ] ) ) {
				$FacebookPageUrl = $FacebookSettings[ 'FacebookPageUrl' ];
			}

			$ShowBorder = 'true';
			if ( isset( $FacebookSettings[ 'ShowBorder' ] ) ) {
				$ShowBorder = $FacebookSettings[ 'ShowBorder' ];
			}

			$ShowFaces = 'true';
			if ( isset( $FacebookSettings[ 'ShowFaces' ] ) ) {
				$ShowFaces = $FacebookSettings[ 'ShowFaces' ];
			}

			$Stream = 'true';
			if ( isset( $FacebookSettings[ 'Stream' ] ) ) {
				$Stream = $FacebookSettings[ 'Stream' ];
			}

			$Width = 292;
			if ( isset( $FacebookSettings[ 'Width' ] ) ) {
				$Width = $FacebookSettings[ 'Width' ];
			}

			$FbAppId = "488390501239538";
			if ( isset( $FacebookSettings[ 'FbAppId' ] ) ) {
				$FbAppId = $FacebookSettings[ 'FbAppId' ];
			}
			?>
			<form name='fb-form' id='fb-form'>
			<p>
				<p><label><?php _e( 'Facebook Page URL', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></label></p>
				<input class="widefat" id="facebook-page-url" name="facebook-page-url" type="text" value="<?php echo esc_attr( $FacebookPageUrl ); ?>">
			</p>
			<br>
			
			<p>
				<label><?php _e( 'Show Faces', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></label>
				<select id="show-fan-faces" name="show-fan-faces">
					<option value="true" <?php if($ShowFaces == "true") echo "selected=selected" ?>><?php _e( 'Yes', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></option>
					<option value="false" <?php if($ShowFaces == "false") echo "selected=selected" ?>><?php _e( 'No', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></option>
				</select>
			</p>
			<br>
			
			<p>
				<label><?php _e( 'Show Live Stream', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></label>
				<select id="show-live-stream" name="show-live-stream">
					<option value="true" <?php if($Stream == "true") echo "selected=selected" ?>><?php _e( 'Yes', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></option>
					<option value="false" <?php if($Stream == "false") echo "selected=selected" ?>><?php _e( 'No', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></option>
				</select>
			</p>
			<br>
			
			<p>
				<p><label><?php _e( 'Widget Width', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></label></p>
				<input class="widefat" id="widget-width" name="widget-width" type="text" value="<?php echo esc_attr( $Width ); ?>">
			</p>
			<br>
			
			<p>
				<p><label><?php _e( 'Widget Height', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></label></p>
				<input class="widefat" id="widget-height" name="widget-height" type="text" value="<?php echo esc_attr( $Height ); ?>">
			</p>
			<br>
			
			<p>
				<p><label><?php _e( 'Facebook App ID', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?> (<?php _e('Required', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>)</label></p>
				<input class="widefat" id="fb-app-id" name="fb-app-id" type="text" value="<?php echo esc_attr( $FbAppId ); ?>">
				<?php _e('Get Your Own Facebook APP Id', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>: <a href="http://weblizar.com/get-facebook-app-id/" target="_blank">HERE</a>
			</p>
			<br>
			
			<p>
				<input onclick="return SaveSettings();" type="button" class="button button-primary button-hero" id="fb-save-settings" name="fb-save-settings" value="SAVE">
			</p>
			<p>
				<div id="fb-img" style="display: none;"><img src="<?php echo WEBLIZAR_FACEBOOK_PLUGIN_URL.'images/loading.gif'; ?>" /></div>
				<div id="fb-msg" style="display: none;" class"alert">
					<?php _e( 'Settings successfully saved. Reloading page for generating preview below.', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?> 
				</div>
			</p>
			<br>
			</form>
			<?php
			if($FbAppId && $FacebookPageUrl) { ?>
			<div id="heading">
				<h2>Facebook Likebox Shortcode <?php _e( 'Preview', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></h2>
			</div>
			<p>
				<div id="fb-root"></div>
				<script>(function(d, s, id) {
						var js, fjs = d.getElementsByTagName(s)[0];
						if (d.getElementById(id)) return;
						js = d.createElement(s); js.id = id;
						js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=<?php echo $FbAppId; ?>&version=v2.0";
						fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));
				</script>
				<div class="fb-like-box" data-small-header="<?php echo $Header; ?>" data-height="<?php echo $Height; ?>" data-href="<?php echo $FacebookPageUrl; ?>" data-show-border="<?php echo $ShowBorder; ?>" data-show-faces="<?php echo $ShowFaces; ?>" data-stream="<?php echo $Stream; ?>" data-width="<?php echo $Width; ?>" data-force-wall="<?php echo $ForceWall; ?>"></div>
			</p>
			<?php } ?>
		</div>
	</div>
</div>



<!---------------- our product tab------------------------>
<div class="block ui-tabs-panel deactive" id="option-recommendation">
	<!-- Dashboard Settings panel content --- >
<!----------------------------------------> 

<div class="row">
	
	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table2">
				
				<tr class="radio-span" style="border-bottom:none;">
					<td><?php
							include( ABSPATH . "wp-admin/includes/plugin-install.php" );
	global $tabs, $tab, $paged, $type, $term;
	$tabs = array();
	$tab = "search";
	$per_page = 20;
	$args = array
	(
		"author"=> "weblizar",
		"page" => $paged,
		"per_page" => $per_page,
		"fields" => array( "last_updated" => true, "downloaded" => true, "icons" => true ),
		"locale" => get_locale(),
	);
	$arges = apply_filters( "install_plugins_table_api_args_$tab", $args );
	$api = plugins_api( "query_plugins", $arges );
	$item = $api->plugins;
	if(!function_exists("wp_star_rating"))
	{
		function wp_star_rating( $args = array() )
		{
			$defaults = array(
					'rating' => 0,
					'type' => 'rating',
					'number' => 0,
			);
			$r = wp_parse_args( $args, $defaults );
	
			// Non-english decimal places when the $rating is coming from a string
			$rating = str_replace( ',', '.', $r['rating'] );
	
			// Convert Percentage to star rating, 0..5 in .5 increments
			if ( 'percent' == $r['type'] ) {
				$rating = round( $rating / 10, 0 ) / 2;
			}
	
			// Calculate the number of each type of star needed
			$full_stars = floor( $rating );
			$half_stars = ceil( $rating - $full_stars );
			$empty_stars = 5 - $full_stars - $half_stars;
	
			if ( $r['number'] ) {
				/* translators: 1: The rating, 2: The number of ratings */
				$format = _n( '%1$s rating based on %2$s rating', '%1$s rating based on %2$s ratings', $r['number'] );
				$title = sprintf( $format, number_format_i18n( $rating, 1 ), number_format_i18n( $r['number'] ) );
			} else {
				/* translators: 1: The rating */
				$title = sprintf( __( '%s rating' ), number_format_i18n( $rating, 1 ) );
			}
	
			echo '<div class="star-rating" title="' . esc_attr( $title ) . '">';
			echo '<span class="screen-reader-text">' . $title . '</span>';
			echo str_repeat( '<div class="star star-full"></div>', $full_stars );
			echo str_repeat( '<div class="star star-half"></div>', $half_stars );
			echo str_repeat( '<div class="star star-empty"></div>', $empty_stars);
			echo '</div>';
		}
	}
	?>
	<form id="frmrecommendation" class="layout-form">
		<div id="poststuff" style="width: 99% !important;">
			<div id="post-body" class="metabox-holder">
				<div id="postbox-container-2" class="postbox-container">
					<div id="advanced" class="meta-box-sortables">
						<div id="gallery_bank_get_started" class="postbox" >
							<div class="handlediv" data-target="ux_recommendation" title="Click to toggle" data-toggle="collapse"><br></div>
							<h2 class="hndle"><span><?php _e("Get More Free Wordpress Plguins From Weblizar", WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></span></h3>
							<div class="inside">
								<div id="ux_recommendation" class="gallery_bank_layout">
									
									<div class="separator-doubled"></div>
									<div class="fluid-layout">
										<div class="layout-span12">
											<div class="wp-list-table plugin-install">
												<div id="the-list">
													<?php 
													foreach ((array) $item as $plugin) 
													{
														if (is_object( $plugin))
														{
															$plugin = (array) $plugin;
															
														}
														if (!empty($plugin["icons"]["svg"]))
														{
															$plugin_icon_url = $plugin["icons"]["svg"];
														} 
														elseif (!empty( $plugin["icons"]["2x"])) 
														{
															$plugin_icon_url = $plugin["icons"]["2x"];
														} 
														elseif (!empty( $plugin["icons"]["1x"]))
														{
															$plugin_icon_url = $plugin["icons"]["1x"];
														} 
														else 
														{
															$plugin_icon_url = $plugin["icons"]["default"];
														}
														$plugins_allowedtags = array
														(
															"a" => array( "href" => array(),"title" => array(), "target" => array() ),
															"abbr" => array( "title" => array() ),"acronym" => array( "title" => array() ),
															"code" => array(), "pre" => array(), "em" => array(),"strong" => array(),
															"ul" => array(), "ol" => array(), "li" => array(), "p" => array(), "br" => array()
														);
														$title = wp_kses($plugin["name"], $plugins_allowedtags);
														$description = strip_tags($plugin["short_description"]);
														$author = wp_kses($plugin["author"], $plugins_allowedtags);
														$version = wp_kses($plugin["version"], $plugins_allowedtags);
														$name = strip_tags( $title . " " . $version );
														$details_link   = self_admin_url( "plugin-install.php?tab=plugin-information&amp;plugin=" . $plugin["slug"] .
														"&amp;TB_iframe=true&amp;width=600&amp;height=550" );
														
														/* translators: 1: Plugin name and version. */
														$action_links[] = '<a href="' . esc_url( $details_link ) . '" class="thickbox" aria-label="' . esc_attr( sprintf("More information about %s", $name ) ) . '" data-title="' . esc_attr( $name ) . '">' . __( 'More Details' ) . '</a>';
														$action_links = array();
														if (current_user_can( "install_plugins") || current_user_can("update_plugins"))
														{
															$status = install_plugin_install_status( $plugin );
															switch ($status["status"])
															{
																case "install":
																	if ( $status["url"] )
																	{
																		/* translators: 1: Plugin name and version. */
																		$action_links[] = '<a class="install-now button" href="' . $status['url'] . '" aria-label="' . esc_attr( sprintf("Install %s now", $name ) ) . '">' . __( 'Install Now' ) . '</a>';
																	}
																break;
																case "update_available":
																	if ($status["url"])
																	{
																		/* translators: 1: Plugin name and version */
																		$action_links[] = '<a class="button" href="' . $status['url'] . '" aria-label="' . esc_attr( sprintf( "Update %s now", $name ) ) . '">' . __( 'Update Now' ) . '</a>';
																	}
																break;
																case "latest_installed":
																case "newer_installed":
																	$action_links[] = '<span class="button button-disabled" title="' . esc_attr__( "This plugin is already installed and is up to date" ) . ' ">' . _x( 'Installed', 'plugin' ) . '</span>';
																break;
															}
														}
														?>
														<div class="plugin-div plugin-div-settings">
															<div class="plugin-div-top plugin-div-settings-top">
																<div class="plugin-div-inner-content">
																	<a href="<?php echo esc_url( $details_link ); ?>" class="thickbox plugin-icon plugin-icon-custom">
																		<img class="custom_icon" src="<?php echo esc_attr( $plugin_icon_url ) ?>" />
																	</a>
																	<div class="name column-name">
																		<h4>
																			<a href="<?php echo esc_url( $details_link ); ?>" class="thickbox"><?php echo $title; ?></a>
																		</h4>
																	</div>
																	<div class="desc column-description">
																		<p>
																			<?php echo $description; ?>
																		</p>
																		<p class="authors">
																			<cite>
																				<?php _e( "By ",WEBLIZAR_FACEBOOK_TEXT_DOMAIN); echo $author;?>
																			</cite>
																		</p>
																	</div>
																</div>
																<div class="action-links">
																	<ul class="plugin-action-buttons-custom">
																		<li>
																			<?php
																				if ($action_links)
																				{
																					echo implode("</li><li>", $action_links);
																				}
																					
																				switch($plugin["slug"])
																				{
																					case "gallery-bank" :
																						?>
																							<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-gallery-bank/pricing/" target="_blank" >
																								<?php _e("Premium Editions", WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>
																							</a>
																							<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-gallery-bank/" target="_blank" >
																								<?php _e("Visit Website", WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>
																							</a>
																						<?php
																					break;
																					case "contact-bank" :
																						?>
																							<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-contact-bank/pricing/" target="_blank" >
																								<?php _e("Premium Editions", WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>
																							</a>
																							<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-contact-bank/" target="_blank" >
																								<?php _e("Visit Website", WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>
																							</a>
																						<?php
																					break;
																					case "captcha-bank" :
																						?>
																							<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-captcha-bank/pricing/" target="_blank" >
																								<?php _e("Premium Editions", WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>
																							</a>
																							<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-captcha-bank/" target="_blank" >
																								<?php _e("Visit Website", WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>
																							</a>
																						<?php 
																					break;
																					case "wp-clean-up-optimizer" :
																						?>
																							<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-clean-up-optimizer/pricing/" target="_blank" >
																								<?php _e("Premium Editions", WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>
																							</a>
																							<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-clean-up-optimizer/" target="_blank" >
																								<?php _e("Visit Website", WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>
																							</a>
																						<?php 
																					break;
																					case "google-maps-bank":
																						?>
																							<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-google-maps-bank/pricing/" target="_blank" >
																								<?php _e("Premium Editions", WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>
																							</a>
																							<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-google-maps-bank/" target="_blank" >
																								<?php _e("Visit Website", WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>
																							</a>
																						<?php
																					break;
																					case "wp-backup-bank":
																						?>
																							<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-backup-bank/pricing/" target="_blank" >
																								<?php _e("Premium Editions", WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>
																							</a>
																							<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-backup-bank/" target="_blank" >
																								<?php _e("Visit Website", WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>
																							</a>
																						<?php
																					break;
																				}
																			?>
																		</li>
																	</ul>
																</div>
															</div>
															<div class="plugin-card-bottom plugin-card-bottom_settings">
																<div class="vers column-rating">
																	<?php wp_star_rating( array( "rating" => $plugin["rating"], "type" => "percent", "number" => $plugin["num_ratings"] ) ); ?>
																	<span class="num-ratings">
																		(<?php echo number_format_i18n( $plugin["num_ratings"] ); ?>)
																	</span>
																</div>
																<div class="column-updated">
																	<strong><?php _e("Last Updated:"); ?></strong> <span title="<?php echo esc_attr($plugin["last_updated"]); ?>">
																		<?php printf("%s ago", human_time_diff(strtotime($plugin["last_updated"]))); ?>
																	</span>
																</div>
																<div class="column-downloaded">
																	<?php echo sprintf( _n("%s download", "%s downloads", $plugin["downloaded"]), number_format_i18n($plugin["downloaded"])); ?>
																</div>
																<div class="column-compatibility">
																	<?php
																	if ( !empty($plugin["tested"]) && version_compare(substr($GLOBALS["wp_version"], 0, strlen($plugin["tested"])), $plugin["tested"], ">"))
																	{
																		echo '<span class="compatibility-untested">' . __( "<strong>Untested</strong> with your version of WordPress" ) . '</span>';
																	} 
																	elseif (!empty($plugin["requires"]) && version_compare(substr($GLOBALS["wp_version"], 0, strlen($plugin["requires"])), $plugin["requires"], "<")) 
																	{
																		echo '<span class="compatibility-incompatible">' . __("Incompatible with your version of WordPress") . '</span>';
																	} 
																	else
																	{
																		echo '<span class="compatibility-compatible">' . __("Compatible with your version of WordPress") . '</span>';
																	}
																	?>
																</div>
															</div>
														</div>
													<?php
													}
													?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
					</td>
				</tr>
			</table>
		</div>
	</div>
	
   
	
</div>
<!-- /row -->

</div>




<!---------------- need help tab------------------------>
<div class="block ui-tabs-panel deactive" id="option-needhelp">		
	<div class="row">
		<div class="col-md-10">
			<div id="heading">
				<h2>Facebook Like Box Help Section</h2>
			</div>
			<p>Facebook By Weblizar plugin comes with 2 functionality.</p>
			<br>
			<p><strong>1 - Facebook Like Box Widget</strong></p>
			<p><strong>2 - Facebook Like Box Shoertcode [FBW]</strong></p>
			<br><br>
			
			<p><strong>Facebook Like Box Widget</strong></p>
			<hr>
			<p>You can use the widget to display your Facebook Like Box in any theme Widget Sections.</p>
			<p>Simple go to your <a href="<?php echo get_site_url(); ?>/wp-admin/widgets.php"><strong>Widgets</strong></a> section and activate available <strong>"Facebook By Weblizar"</strong> widget in any sidebar section, like in left sidebar, right sidebar or footer sidebar.</p>
			<br><br>
			
			<p><strong>Facebook Like Box Shoertcode [FBW]</strong></p>
			<hr>
			<p><strong>[FBW]</strong> shortcode give ability to display Facebook Like Box in any Page / Post with content.</p>
			<p>To use shortcode, just copy <strong>[FBW]</strong> shortcode and paste into content editor of any Page / Post.</p>
		
			<br><br>
			<p><strong>Q. What is Facebook Page URL?</strong></p>
			<p><strong> Ans. Facebook Page URL</strong> is your Facebook page your where you promote your business. Here your customers, clients, friends, guests can like, share, comment review your POST.</p>
			<br><br>
			<p><strong>Q. What is Facebook APP ID?</strong></p>
			<p><strong>Ans. Facebook Application ID</strong> used to authenticate your Facebook Page data & settings. To get your own Facebook APP ID please read our 4 Steps very simple and easy <a href="http://weblizar.com/get-facebook-app-id/" target="_blank"><strong>Tutorial.</p>
		</div>
	</div>
</div>

<!---------------- our product tab------------------------>
<div class="block ui-tabs-panel deactive" id="option-ourproduct">
	<div class="row-fluid pricing-table pricing-three-column">
		<div class="plan-name centre"> 
			<a href="http://weblizar.com" target="_new" style="margin-bottom:10px;textt-align:center"><img src="<?php echo WEBLIZAR_FACEBOOK_PLUGIN_URL.'images/weblizar3.png' ;?>"></a>
		</div>	
		<div class="plan-name">
			<h2>Weblizar Responsive WordPress Theme</h2>
			<h6>Get The Premium, And Create your website Beautifully.  </h6>
		</div>
		<div class="section container">
			<div class="col-lg-6">
				<h2>Premium Themes </h2><hr>
				<ol id="weblizar_product">
					<li><a href="http://weblizar.com/themes/enigma-premium/">Enigma </a> </li>
					<li><a href="http://weblizar.com/themes/weblizar-premium-theme/">Weblizar </a></li>					
					<li><a href="http://weblizar.com/themes/guardian-premium-theme/">Guardian </a></li>
					<li><a href="http://weblizar.com/plugins/green-lantern-premium-theme/">Green-lantern</a> </li>
					<li><a href="https://weblizar.com/themes/creative-premium-theme/">Creative </a> </li>
					<li><a href="https://weblizar.com/themes/incredible-premium-theme/">Incredible </a></li>
				</ol>
			</div>
			<div class="col-lg-6">
				<h2>Premium Plugins</h2><hr>
				<ol id="weblizar_product">
					<li><a href="http://weblizar.com/plugins/responsive-photo-gallery-pro/">Responsive Photo Gallery</a></li>
					<li><a href="http://weblizar.com/plugins/ultimate-responsive-image-slider-pro/">Ultimate Responsive Image Slider</a></li>
					<li><a href="http://weblizar.com/plugins/responsive-portfolio-pro/">Responsive Portfolio</a></li>
					<li><a href="http://weblizar.com/plugins/photo-video-link-gallery-pro//">Photo Video Link Gallery</a></li>
					<li><a href="http://weblizar.com/plugins/lightbox-slider-pro/">Lightbox Slider</a></li>
					<li><a href="http://weblizar.com/plugins/flickr-album-gallery-pro/">Flickr Album Gallery</a></li>
					<li><a href="https://weblizar.com/plugins/instagram-shortcode-and-widget-pro/">Instagram Shortcode &amp; Widget</a></li>
					<li><a href="https://weblizar.com/plugins/instagram-gallery-pro/">Instagram Gallery</a></li>
				</ol>
			</div>
		</div>	
		<div id="product_decs" class="section container">
			<p>Note: More details to click on weblizar Products site link are below given view site button.</p>	
		</div>
	</div>
	<div class="plan-name centre"> 
		  <a class="btn btn-primary btn-lg" target="_new" href="https://www.weblizar.com">View Site</a>		
	</div>
</div>