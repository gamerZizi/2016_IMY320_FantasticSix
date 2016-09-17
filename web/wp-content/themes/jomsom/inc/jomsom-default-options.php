<?php
/**
 * Implement Default Theme/Customizer Options
 *
 * @package Catch Themes
 * @subpackage Jomsom
 * @since Jomsom 0.1
 */

/**
 * Returns the default options for jomsom.
 *
 * @since Jomsom 0.1
 */
function jomsom_get_default_theme_options() {

	$theme_data = wp_get_theme();

	$default_theme_options = array(
		//Site Title an Tagline
		'hide_site_title'								   => 0,
		//@remove when WP 4.8 is released
		'logo'                                             => get_template_directory_uri() . '/images/headers/logo.png',
		'logo_alt_text'                                    => '',
		'logo_enable'                                      => 0,
		//@remove end
		'move_title_tagline'                               => 0,

		//Color Options
		'background_color'                                 => '#f5f5f5',

		//Header Color Options
		'header_textcolor'                                 => '#7fc242',
		'tagline_color'									   => '#000000',

		//Layout
		'theme_layout'                                     => 'right-sidebar',
		'content_layout'                                   => 'excerpt-image-left',
		'single_post_image_layout'                         => 'large',

		//Header Image
		'enable_featured_header_image'                     => 'disabled',
		'featured_image_size'                              => 'full',
		'featured_header_image_url'                        => '',
		'featured_header_image_alt'                        => '',
		'featured_header_image_base'                       => 0,

		//Navigation
		'fix_primary_menu'                                 => 0,

		//Breadcrumb Options
		'breadcumb_option'                                 => 0,
		'breadcumb_onhomepage'                             => 0,
		'breadcumb_seperator'                              => '&raquo;',

		//Custom CSS
		'custom_css'                                       => '',

		//Scrollup Options
		'disable_scrollup'                                 => 0,

		//Form Elements
		'enable_jcf'                                       => 0,

		//Excerpt Options
		'excerpt_length'                                   => '30',
		'excerpt_more_text'                                => __( 'Continue reading', 'jomsom' ),

		//Homepage / Frontpage Settings
		'front_page_category'                              => array(),

		//Pagination Options
		'pagination_type'                                  => 'default',

		//Promotion Headline Options
		'promotion_headline_option'                        => 'disabled',
		'promotion_headline'                               => __( 'Jomsom is a Responsive WordPress Theme', 'jomsom' ),
		'promotion_subheadline'                            => __( 'This is promotion headline. You can edit this from Appearance -> Customize -> Theme Options -> Promotion Headline Options', 'jomsom' ),
		'promotion_headline_button'                        => __( 'Buy Now', 'jomsom' ),
		'promotion_headline_url'                           => esc_url( 'http://catchthemes.com/' ),
		'promotion_headline_target'                        => 1,

		//Search Options
		'search_text'                                      => __( 'Search...', 'jomsom' ),

		//Font Family Options
		'body_font'                                        => 'open-sans',
		'title_font'                                       => 'oswald',
		'tagline_font'                                     => 'pt-sans',
		'content_title_font'                               => 'droid-serif',
		'content_font'                                     => 'sans-serif',
		'headings_font'                                    => 'sans-serif',
		'reset_typography'                                 => 0,

		//Featured Content Options
		'featured_content_option'                          => 'disabled',
		'featured_content_layout'                          => 'layout-four',
		'featured_content_position'                        => 0,
		'featured_content_slider'                          => 1,
		'featured_content_image_loader'                    => 'true',
		'featured_content_headline'                        => '',
		'featured_content_subheadline'                     => '',
		'featured_content_type'                            => 'demo-featured-content',
		'featured_content_number'                          => '4',
		'featured_content_enable_title'                    => 1,
		'featured_content_enable_date'                     => 1,
		'featured_content_enable_excerpt_content'          => 0,

		//Featured Slider Options
		'featured_slider_option'                           => 'disabled',
		'featured_slider_image_loader'                     => 'true',
		'featured_slide_transition_effect'                 => 'fadeout',
		'featured_slide_transition_delay'                  => '4',
		'featured_slide_transition_length'                 => '1',
		'featured_slider_type'                             => 'demo-featured-slider',
		'featured_slide_number'                            => '4',

		//Social Links
		'disable_social_footer'                            => 0,

		//Reset all settings
		'reset_all_settings'                               => 0,
	);

	return apply_filters( 'jomsom_default_theme_options', $default_theme_options );
}



/**
 * Returns an array of layout options registered for jomsom.
 *
 * @since Jomsom 0.1
 */
function jomsom_layouts() {
	$layout_options = array(
		'left-sidebar' 	=> array(
			'value' => 'left-sidebar',
			'label' => __( 'Primary Sidebar, Content', 'jomsom' ),
		),
		'right-sidebar' => array(
			'value' => 'right-sidebar',
			'label' => __( 'Content, Primary Sidebar', 'jomsom' ),
		),
		'no-sidebar'	=> array(
			'value' => 'no-sidebar',
			'label' => __( 'No Sidebar ( Content Width )', 'jomsom' ),
		)
	);
	return apply_filters( 'jomsom_layouts', $layout_options );
}


/**
 * Returns an array of content layout options registered for jomsom.
 *
 * @since Jomsom 0.1
 */
function jomsom_get_archive_content_layout() {
	$layout_options = array(
		'excerpt-image-left' => array(
			'value' => 'excerpt-image-left',
			'label' => __( 'Excerpt Image Left', 'jomsom' ),
		),
		'full-content' => array(
			'value' => 'full-content',
			'label' => __( 'Show Full Content (No Featured Image)', 'jomsom' ),
		),
	);

	return apply_filters( 'jomsom_get_archive_content_layout', $layout_options );
}


/**
 * Returns an array of feature header enable options
 *
 * @since Jomsom 0.1
 */
function jomsom_enable_featured_header_image_options() {
	$enable_featured_header_image_options = array(
		'homepage' 		=> array(
			'value'	=> 'homepage',
			'label' => __( 'Homepage / Frontpage', 'jomsom' ),
		),
		'exclude-home' 		=> array(
			'value'	=> 'exclude-home',
			'label' => __( 'Excluding Homepage', 'jomsom' ),
		),
		'exclude-home-page-post' 	=> array(
			'value' => 'exclude-home-page-post',
			'label' => __( 'Excluding Homepage, Page/Post Featured Image', 'jomsom' ),
		),
		'entire-site' 	=> array(
			'value' => 'entire-site',
			'label' => __( 'Entire Site', 'jomsom' ),
		),
		'entire-site-page-post' 	=> array(
			'value' => 'entire-site-page-post',
			'label' => __( 'Entire Site, Page/Post Featured Image', 'jomsom' ),
		),
		'pages-posts' 	=> array(
			'value' => 'pages-posts',
			'label' => __( 'Pages and Posts', 'jomsom' ),
		),
		'disabled'		=> array(
			'value' => 'disabled',
			'label' => __( 'Disabled', 'jomsom' ),
		),
	);

	return apply_filters( 'jomsom_enable_featured_header_image_options', $enable_featured_header_image_options );
}


/**
 * Returns an array of feature image size
 *
 * @since Jomsom 0.1
 */
function jomsom_featured_image_size_options() {
	$featured_image_size_options = array(
		'full' 		=> array(
			'value'	=> 'full',
			'label' => __( 'Full Image', 'jomsom' ),
		),
		'large' 	=> array(
			'value' => 'large',
			'label' => __( 'Large Image', 'jomsom' ),
		),
		'slider'		=> array(
			'value' => 'slider',
			'label' => __( 'Slider Image', 'jomsom' ),
		),
	);

	return apply_filters( 'jomsom_featured_image_size_options', $featured_image_size_options );
}


/**
 * Returns an array of content and slider layout options registered for jomsom.
 *
 * @since Jomsom 0.1
 */
function jomsom_featured_slider_content_options() {
	$featured_slider_content_options = array(
		'homepage' 		=> array(
			'value'	=> 'homepage',
			'label' => __( 'Homepage / Frontpage', 'jomsom' ),
		),
		'entire-site' 	=> array(
			'value' => 'entire-site',
			'label' => __( 'Entire Site', 'jomsom' ),
		),
		'disabled'		=> array(
			'value' => 'disabled',
			'label' => __( 'Disabled', 'jomsom' ),
		),
	);

	return apply_filters( 'jomsom_featured_slider_content_options', $featured_slider_content_options );
}


/**
 * Returns an array of feature content types registered for jomsom.
 *
 * @since Jomsom 0.1
 */
function jomsom_featured_content_types() {
	$featured_content_types = array(
		'demo-featured-content' => array(
			'value' => 'demo-featured-content',
			'label' => __( 'Demo Featured Content', 'jomsom' ),
		),
		'featured-page-content' => array(
			'value' => 'featured-page-content',
			'label' => __( 'Featured Page Content', 'jomsom' ),
		)
	);

	return apply_filters( 'jomsom_featured_content_types', $featured_content_types );
}


/**
 * Returns an array of featured content options registered for jomsom.
 *
 * @since Jomsom 0.1
 */
function jomsom_featured_content_layout_options() {
	$featured_content_layout_option = array(
		'layout-three' 		=> array(
			'value'	=> 'layout-three',
			'label' => __( '3 columns', 'jomsom' ),
		),
		'layout-four' 	=> array(
			'value' => 'layout-four',
			'label' => __( '4 columns', 'jomsom' ),
		),
	);

	return apply_filters( 'jomsom_featured_content_layout_options', $featured_content_layout_option );
}


/**
 * Returns an array of featured content show registered for jomsom.
 *
 * @since Jomsom 0.1
 */
function jomsom_featured_content_show() {
	$featured_content_show_option = array(
		'excerpt' 		=> array(
			'value'	=> '1',
			'label' => __( 'Show Excerpt', 'jomsom' ),
		),
		'full-content' 	=> array(
			'value' => '2',
			'label' => __( 'Show Full Content', 'jomsom' ),
		),
		'hide-content' 	=> array(
			'value' => '0',
			'label' => __( 'Hide Content', 'jomsom' ),
		),
	);

	return apply_filters( 'jomsom_featured_content_show', $featured_content_show_option );
}


/**
 * Returns an array of feature slider types registered for jomsom.
 *
 * @since Jomsom 0.1
 */
function jomsom_featured_slider_types() {
	$featured_slider_types = array(
		'demo-featured-slider' => array(
			'value' => 'demo-featured-slider',
			'label' => __( 'Demo Featured Slider', 'jomsom' ),
		),
		'featured-page-slider' => array(
			'value' => 'featured-page-slider',
			'label' => __( 'Featured Page Slider', 'jomsom' ),
		)
	);

	return apply_filters( 'jomsom_featured_slider_types', $featured_slider_types );
}


/**
 * Returns an array of feature slider transition effects
 *
 * @since Jomsom 0.1
 */
function jomsom_featured_slide_transition_effects() {
	$featured_slide_transition_effects = array(
		'fade' 		=> array(
			'value'	=> 'fade',
			'label' => __( 'Fade', 'jomsom' ),
		),
		'fadeout' 	=> array(
			'value'	=> 'fadeout',
			'label' => __( 'Fade Out', 'jomsom' ),
		),
		'none' 		=> array(
			'value' => 'none',
			'label' => __( 'None', 'jomsom' ),
		),
		'scrollHorz'=> array(
			'value' => 'scrollHorz',
			'label' => __( 'Scroll Horizontal', 'jomsom' ),
		),
		'scrollVert'=> array(
			'value' => 'scrollVert',
			'label' => __( 'Scroll Vertical', 'jomsom' ),
		),
		'flipHorz'	=> array(
			'value' => 'flipHorz',
			'label' => __( 'Flip Horizontal', 'jomsom' ),
		),
		'flipVert'	=> array(
			'value' => 'flipVert',
			'label' => __( 'Flip Vertical', 'jomsom' ),
		),
		'tileSlide'	=> array(
			'value' => 'tileSlide',
			'label' => __( 'Tile Slide', 'jomsom' ),
		),
		'tileBlind'	=> array(
			'value' => 'tileBlind',
			'label' => __( 'Tile Blind', 'jomsom' ),
		),
		'shuffle'	=> array(
			'value' => 'shuffle',
			'label' => __( 'Suffle', 'jomsom' ),
		)
	);

	return apply_filters( 'jomsom_featured_slide_transition_effects', $featured_slide_transition_effects );
}


/**
 * Returns an array of featured slider image loader options
 *
 * @since Jomsom 2.3
 */
function jomsom_featured_slider_image_loader() {
	$options = array(
		'true' => array(
			'value' 				=> 'true',
			'label' 				=> __( 'True', 'jomsom' ),
		),
		'wait' => array(
			'value' 				=> 'wait',
			'label' 				=> __( 'Wait', 'jomsom' ),
		),
		'false' => array(
			'value' 				=> 'false',
			'label' 				=> __( 'False', 'jomsom' ),
		),
	);

	return apply_filters( 'jomsom_featured_slider_image_loader', $options );
}


/**
 * Returns an array of color schemes registered for jomsom.
 *
 * @since Jomsom 0.1
 */
function jomsom_get_pagination_types() {
	$pagination_types = array(
		'default' => array(
			'value' => 'default',
			'label' => __( 'Default(Older Posts/Newer Posts)', 'jomsom' ),
		),
		'numeric' => array(
			'value' => 'numeric',
			'label' => __( 'Numeric', 'jomsom' ),
		),
		'infinite-scroll-click' => array(
			'value' => 'infinite-scroll-click',
			'label' => __( 'Infinite Scroll (Click)', 'jomsom' ),
		),
		'infinite-scroll-scroll' => array(
			'value' => 'infinite-scroll-scroll',
			'label' => __( 'Infinite Scroll (Scroll)', 'jomsom' ),
		),
	);

	return apply_filters( 'jomsom_get_pagination_types', $pagination_types );
}


/**
 * Returns an array of content featured image size.
 *
 * @since Jomsom 0.1
 */
function jomsom_single_post_image_layout_options() {
	$single_post_image_layout_options = array(
		'large' => array(
			'value' => 'large',
			'label' => __( 'Large', 'jomsom' ),
		),
		'full-size' => array(
			'value' => 'full-size',
			'label' => __( 'Full size', 'jomsom' ),
		),
		'slider-image-size' => array(
			'value' => 'slider-image-size',
			'label' => __( 'Slider Image Size', 'jomsom' ),
		),
		'slider-image-size' => array(
			'value' => 'featured',
			'label' => __( 'Featured Image Size', 'jomsom' ),
		),
		'disabled' => array(
			'value' => 'disabled',
			'label' => __( 'Disabled', 'jomsom' ),
		),
	);
	return apply_filters( 'jomsom_single_post_image_layout_options', $single_post_image_layout_options );
}


/**
 * Returns list of social icons currently supported
 *
 * @since Jomsom 0.1
*/
function jomsom_get_social_icons_list() {
	$jomsom_social_icons_list =	array(
		'facebook_link'		=> array(
			'fa_class' 	=> 'facebook',
			'label' 			=> esc_html__( 'Facebook', 'jomsom' )
			),
		'twitter_link'		=> array(
			'fa_class' 	=> 'twitter',
			'label' 			=> esc_html__( 'Twitter', 'jomsom' )
			),
		'googleplus_link'	=> array(
			'fa_class' 	=> 'google-plus',
			'label' 			=> esc_html__( 'Googleplus', 'jomsom' )
			),
		'email_link'		=> array(
			'fa_class' 	=> 'mail',
			'label' 			=> esc_html__( 'Email', 'jomsom' )
			),
		'feed_link'			=> array(
			'fa_class' 	=> 'feed',
			'label' 			=> esc_html__( 'Feed', 'jomsom' )
			),
		'wordpress_link'	=> array(
			'fa_class' 	=> 'wordpress',
			'label' 			=> esc_html__( 'WordPress', 'jomsom' )
			),
		'github_link'		=> array(
			'fa_class' 	=> 'github',
			'label' 			=> esc_html__( 'GitHub', 'jomsom' )
			),
		'linkedin_link'		=> array(
			'fa_class' 	=> 'linkedin',
			'label' 			=> esc_html__( 'LinkedIn', 'jomsom' )
			),
		'pinterest_link'	=> array(
			'fa_class' 	=> 'pinterest',
			'label' 			=> esc_html__( 'Pinterest', 'jomsom' )
			),
		'flickr_link'		=> array(
			'fa_class' 	=> 'flickr',
			'label' 			=> esc_html__( 'Flickr', 'jomsom' )
			),
		'vimeo_link'		=> array(
			'fa_class' 	=> 'vimeo',
			'label' 			=> esc_html__( 'Vimeo', 'jomsom' )
			),
		'youtube_link'		=> array(
			'fa_class' 	=> 'youtube',
			'label' 			=> esc_html__( 'YouTube', 'jomsom' )
			),
		'tumblr_link'		=> array(
			'fa_class' 	=> 'tumblr',
			'label' 			=> esc_html__( 'Tumblr', 'jomsom' )
			),
		'instagram_link'	=> array(
			'fa_class' 	=> 'instagram',
			'label' 			=> esc_html__( 'Instagram', 'jomsom' )
			),
		'codepen_link'		=> array(
			'fa_class' 	=> 'codepen',
			'label' 			=> esc_html__( 'CodePen', 'jomsom' )
			),
		'dribbble_link'		=> array(
			'fa_class' 	=> 'dribbble',
			'label' 			=> esc_html__( 'Dribbble', 'jomsom' )
			),
		'skype_link'		=> array(
			'fa_class' 	=> 'skype',
			'label' 			=> esc_html__( 'Skype', 'jomsom' )
			),
		'digg_link'			=> array(
			'fa_class' 	=> 'digg',
			'label' 			=> esc_html__( 'Digg', 'jomsom' )
			),
		'reddit_link'		=> array(
			'fa_class' 	=> 'reddit',
			'label' 			=> esc_html__( 'Reddit', 'jomsom' )
			),
		'stumbleupon_link'	=> array(
			'fa_class' 	=> 'stumbleupon',
			'label' 			=> esc_html__( 'Stumbleupon', 'jomsom' )
			),
		'pocket_link'		=> array(
			'fa_class' 	=> 'get-pocket',
			'label' 			=> esc_html__( 'Pocket', 'jomsom' ),
			),
		'dropbox_link'		=> array(
			'fa_class' 	=> 'dropbox',
			'label' 			=> esc_html__( 'DropBox', 'jomsom' ),
			),
		'spotify_link'		=> array(
			'fa_class' 	=> 'spotify',
			'label' 			=> esc_html__( 'Spotify', 'jomsom' ),
			),
		'foursquare_link'	=> array(
			'fa_class' 	=> 'foursquare',
			'label' 			=> esc_html__( 'Foursquare', 'jomsom' ),
			),
		'twitch_link'		=> array(
			'fa_class' 	=> 'twitch',
			'label' 			=> esc_html__( 'Twitch', 'jomsom' ),
			),
		'website_link'		=> array(
			'fa_class' 	=> 'globe',
			'label' 			=> esc_html__( 'Website', 'jomsom' ),
			),
		'phone_link'		=> array(
			'fa_class' 	=> 'phone',
			'label' 			=> esc_html__( 'Phone', 'jomsom' ),
			),
		'mobile_link'		=> array(
			'fa_class' 	=> 'mobile-phone',
			'label' 	=> esc_html__( 'Mobile', 'jomsom' ),
			),
		'cart_link'			=> array(
			'fa_class' 	=> 'shopping-cart',
			'label' 	=> esc_html__( 'Cart', 'jomsom' ),
			),
		'cloud_link'		=> array(
			'fa_class' 	=> 'cloud',
			'label' 			=> esc_html__( 'Cloud', 'jomsom' ),
			),
		'link_link'		=> array(
			'fa_class' 	=> 'link',
			'label' 			=> esc_html__( 'Link', 'jomsom' ),
			),
	);

	return apply_filters( 'jomsom_social_icons_list', $jomsom_social_icons_list );
}


/**
 * Returns an array of metabox layout options registered for jomsom.
 *
 * @since Jomsom 0.1
 */
function jomsom_metabox_layouts() {
	$layout_options = array(
		'default' 	=> array(
			'id' 	=> 'jomsom-layout-option',
			'value' => 'default',
			'label' => __( 'Default', 'jomsom' ),
		),
		'left-sidebar' 	=> array(
			'id' 	=> 'jomsom-layout-option',
			'value' => 'left-sidebar',
			'label' => __( 'Primary Sidebar, Content', 'jomsom' ),
		),
		'right-sidebar' => array(
			'id' 	=> 'jomsom-layout-option',
			'value' => 'right-sidebar',
			'label' => __( 'Content, Primary Sidebar', 'jomsom' ),
		),
		'no-sidebar'	=> array(
			'id' 	=> 'jomsom-layout-option',
			'value' => 'no-sidebar',
			'label' => __( 'No Sidebar ( Content Width )', 'jomsom' ),
		)
	);
	return apply_filters( 'jomsom_layouts', $layout_options );
}

/**
 * Returns an array of metabox header featured image options registered for jomsom.
 *
 * @since Jomsom 0.1
 */
function jomsom_metabox_header_featured_image_options() {
	$header_featured_image_options = array(
		'default' => array(
			'id'		=> 'jomsom-header-image',
			'value' 	=> 'default',
			'label' 	=> __( 'Default', 'jomsom' ),
		),
		'enable' => array(
			'id'		=> 'jomsom-header-image',
			'value' 	=> 'enable',
			'label' 	=> __( 'Enable', 'jomsom' ),
		),
		'disable' => array(
			'id'		=> 'jomsom-header-image',
			'value' 	=> 'disable',
			'label' 	=> __( 'Disable', 'jomsom' )
		)
	);
	return apply_filters( 'header_featured_image_options', $header_featured_image_options );
}


/**
 * Returns an array of metabox featured image options registered for jomsom.
 *
 * @since Jomsom 0.1
 */
function jomsom_metabox_featured_image_options() {
	$featured_image_options = array(
		'default' => array(
			'id'		=> 'jomsom-featured-image',
			'value' 	=> 'default',
			'label' 	=> __( 'Default', 'jomsom' ),
		),
		'featured' => array(
			'id'		=> 'jomsom-featured-image',
			'value' 	=> 'featured',
			'label' 	=> __( 'Featured Image', 'jomsom' )
		),
		'full' => array(
			'id' => 'jomsom-featured-image',
			'value' => 'full',
			'label' => __( 'Full Image', 'jomsom' )
		),
		'slider' => array(
			'id' => 'jomsom-featured-image',
			'value' => 'slider',
			'label' => __( 'Slider Image', 'jomsom' )
		),
		'disable' => array(
			'id' => 'jomsom-featured-image',
			'value' => 'disable',
			'label' => __( 'Disable Image', 'jomsom' )
		)
	);
	return apply_filters( 'featured_image_options', $featured_image_options );
}


/**
 * Returns jomsom_contents registered for jomsom.
 *
 * @since Jomsom 0.1
 */
function jomsom_get_content() {
	$theme_data = wp_get_theme();

	$jomsom_content['left'] 	= sprintf( _x( 'Copyright &copy; %1$s %2$s. All Rights Reserved.', '1: Year, 2: Site Title with home URL', 'jomsom' ), date( 'Y' ), '<a href="'. esc_url( home_url( '/' ) ) .'">'. esc_attr( get_bloginfo( 'name', 'display' ) ) . '</a>' );

	$jomsom_content['right']	= esc_attr( $theme_data->get( 'Name') ) . '&nbsp;' . __( 'by', 'jomsom' ). '&nbsp;<a target="_blank" href="'. esc_url( $theme_data->get( 'AuthorURI' ) ) .'">'. esc_attr( $theme_data->get( 'Author' ) ) .'</a>';

	return apply_filters( 'jomsom_get_content', $jomsom_content );
}