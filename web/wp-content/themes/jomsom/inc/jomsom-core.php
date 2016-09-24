<?php
/**
 * Core functions and definitions
 *
 * Sets up the theme
 *
 * The first function, jomsom_initial_setup(), sets up the theme by registering support
 * for various features in WordPress, such as theme support, post thumbnails, navigation menu, and the like.
 *
 * jomsom functions and definitions
 *
 * @package Catch Themes
 * @subpackage Jomsom
 * @since Jomsom 0.1
 */

if ( ! function_exists( 'jomsom_content_width' ) ) :
	/**
	 * Set the content width in pixels, based on the theme's design and stylesheet.
	 *
	 * Priority 0 to make it available to lower priority callbacks.
	 *
	 * @global int $content_width
	 */
	function jomsom_content_width() {
		$layout  = jomsom_get_theme_layout();

		$content_width = 826;

		$GLOBALS['content_width'] = apply_filters( 'jomsom_content_width', $content_width );
	}
endif;
add_action( 'after_setup_theme', 'jomsom_content_width', 0 );


if ( ! function_exists( 'jomsom_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which runs
	 * before the init hook. The init hook is too late for some features, such as indicating
	 * support post thumbnails.
	 */
	function jomsom_setup() {
		/**
		 * Get Theme Options Values
		 */
		$options 	= jomsom_get_theme_options();
		/**
		 * Make theme available for translation
		 * Translations can be filed in the /languages/ directory
		 * If you're building a theme based on jomsom, use a find and replace
		 * to change 'jomsom' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'jomsom', get_template_directory() . '/languages' );

		/**
		 * Add default posts and comments RSS feed links to head
		 */
		add_theme_support( 'automatic-feed-links' );

		/**
		 * Enable support for Post Thumbnails on posts and pages
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );

		set_post_thumbnail_size( 826, 435, true );	// Used in Archive Excerpt Image Top Ratio 4.2

		// Add Jomsom's custom image sizes
		add_image_size( 'jomsom-featured-content', 350, 350, true ); // Used for Featured Content Ratio 1:1 to support 3 columns

        add_image_size( 'jomsom-slider', 1680, 720, true); // Used for Featured Slider Ratio 21:9

		//Archive Images
		add_image_size( 'jomsom-portrait', 360, 375, true); // Used in Archive Excerpt Image Left and Right

		//Custom Widgets Thumbnail Size
		add_image_size( 'jomsom-widget-thumbnail', 67, 67, true ); // Used in Custom Widgets 1:1


		/**
		 * This theme uses wp_nav_menu() in one location.
		 */
		register_nav_menus( array(
			'primary' 		=> __( 'Primary Menu', 'jomsom' ),
			'footer' 		=> __( 'Footer Menu', 'jomsom' ),
		) );

		/**
		 * Enable support for Post Formats
		 */
		add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

		// Add theme support for responsive videos.
		add_theme_support( 'jetpack-responsive-videos' );

		/**
		 * Setup the WordPress core custom background feature.
		 */
		$default_bg_color = jomsom_get_default_theme_options();
		$default_bg_color = $default_bg_color['background_color'];

		add_theme_support( 'custom-background', apply_filters( 'jomsom_custom_background_args', array(
			'default-color' => $default_bg_color,
		) ) );

		/**
		 * Setup Editor style
		 */
		add_editor_style( 'css/editor-style.css' );

		/**
		 * Setup title support for theme
		 * Supported from WordPress version 4.1 onwards
		 * More Info: https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
		 */
		add_theme_support( 'title-tag' );

		/**
		 * Setup Infinite Scroll using JetPack if navigation type is set
		 */
		$pagination_type	= isset( $options['pagination_type'] ) ? $options['pagination_type'] : '';

		if( 'infinite-scroll-click' == $pagination_type ) {
			add_theme_support( 'infinite-scroll', array(
				'type'		=> 'click',
				'container' => 'main',
				'footer'    => 'page'
			) );
		}
		else if ( 'infinite-scroll-scroll' == $pagination_type ) {
			//Override infinite scroll disable scroll option
        	update_option('infinite_scroll', true);

			add_theme_support( 'infinite-scroll', array(
				'type'		=> 'scroll',
				'container' => 'main',
				'footer'    => 'page'
			) );
		}

		//@remove Remove check when WordPress 4.8 is released
		if ( function_exists( 'has_custom_logo' ) ) {
			/**
			* Setup Custom Logo Support for theme
			* Supported from WordPress version 4.5 onwards
			* More Info: https://make.wordpress.org/core/2016/03/10/custom-logo/
			*/
			add_theme_support( 'custom-logo' );
		}
	}
endif; // jomsom_setup
add_action( 'after_setup_theme', 'jomsom_setup' );


/**
 * Enqueue scripts and styles
 *
 * @uses  wp_register_script, wp_enqueue_script, wp_register_style, wp_enqueue_style, wp_localize_script
 * @action wp_enqueue_scripts
 *
 * @since Jomsom 0.1
 */
function jomsom_scripts() {
	$options = jomsom_get_theme_options();

	//Google Fonts
	$web_fonts_stylesheet = '//fonts.googleapis.com/css?family=Open+Sans|Oswald|PT+Sans|Droid+Serif';

	wp_register_style( 'jomsom-web-font', $web_fonts_stylesheet, false, null );

	$jomsom_deps = array( 'jomsom-web-font' );

	if ( $options['enable_jcf'] ) {
		wp_register_style( 'jomsom-jcf', get_template_directory_uri() . '/css/jcf.css', false, null );

		$jomsom_deps = array_merge( $jomsom_deps, array( 'jomsom-jcf' ) );
	}

	wp_enqueue_style( 'jomsom-style', get_stylesheet_uri(), $jomsom_deps, JOMSOM_THEME_VERSION );

	//For font-awesome
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.css', false, '4.5.0' );

	wp_enqueue_script( 'jomsom-navigation', get_template_directory_uri() . '/js/navigation.min.js', array(), '20120206', true );

	wp_enqueue_script( 'jomsom-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.min.js', array(), '20130115', true );

	/**
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	/**
	 * Loads up Scroll Up script
	 */
	if ( ! $options['disable_scrollup'] ) {
		wp_enqueue_script( 'jomsom-scrollup', get_template_directory_uri() . '/js/jomsom-scrollup.min.js', array( 'jquery' ), '20072014', true  );
	}

	//Responsive Menu
    wp_enqueue_script('sidr', get_template_directory_uri() . '/js/jquery.sidr.min.js', array('jquery'), '1.2.1.1', false );

	/**
	 * Loads up Cycle JS
	 */
	if( 'disabled' != $options['featured_slider_option'] || $options['featured_content_slider']  ) {
		wp_register_script( 'jquery.cycle2', get_template_directory_uri() . '/js/jquery.cycle/jquery.cycle2.min.js', array( 'jquery' ), '2.1.5', true );

		/**
		 * Condition checks for additional slider transition plugins
		 */
		// Scroll Vertical transition plugin addition
		if ( 'scrollVert' ==  $options['featured_slide_transition_effect'] ){
			wp_enqueue_script( 'jquery.cycle2.scrollVert', get_template_directory_uri() . '/js/jquery.cycle/jquery.cycle2.scrollVert.min.js', array( 'jquery.cycle2' ), '20140128', true );
		}
		// Flip transition plugin addition
		else if ( 'flipHorz' ==  $options['featured_slide_transition_effect'] || 'flipVert' ==  $options['featured_slide_transition_effect'] ){
			wp_enqueue_script( 'jquery.cycle2.flip', get_template_directory_uri() . '/js/jquery.cycle/jquery.cycle2.flip.min.js', array( 'jquery.cycle2' ), '20140128', true );
		}
		// Suffle transition plugin addition
		else if ( 'tileSlide' ==  $options['featured_slide_transition_effect'] || 'tileBlind' ==  $options['featured_slide_transition_effect'] ){
			wp_enqueue_script( 'jquery.cycle2.tile', get_template_directory_uri() . '/js/jquery.cycle/jquery.cycle2.tile.min.js', array( 'jquery.cycle2' ), '20140128', true );
		}
		// Suffle transition plugin addition
		else if ( 'shuffle' ==  $options['featured_slide_transition_effect'] ){
			wp_enqueue_script( 'jquery.cycle2.shuffle', get_template_directory_uri() . '/js/jquery.cycle/jquery.cycle2.shuffle.min.js', array( 'jquery.cycle2' ), '20140128 ', true );
		}
		else {
			wp_enqueue_script( 'jquery.cycle2' );
		}
	}

    if ( $options['enable_jcf'] ) {
		//Register JavaScript Custom Forms Script
		wp_register_script( 'jcf', get_template_directory_uri() . '/js/jcf.js', '', '1.2.0', true );

		//Register JCF File Module
		wp_register_script( 'jcf.file', get_template_directory_uri() . '/js/jcf.file.js', '', '1.2.0', true );

		//Register JCF Radio Module
		wp_register_script( 'jcf.radio', get_template_directory_uri() . '/js/jcf.radio.js', '', '1.2.0', true );

		//Register JCF Range Module
		wp_register_script( 'jcf.range', get_template_directory_uri() . '/js/jcf.range.js', '', '1.2.0', true );

		//Register JCF Number Module
		wp_register_script( 'jcf.number', get_template_directory_uri() . '/js/jcf.number.js', '', '1.2.0', true );

		//Register JCF Select Module
		wp_register_script( 'jcf.select', get_template_directory_uri() . '/js/jcf.select.js', '', '1.2.0', true );

		//Register JCF Checkbox Module
		wp_register_script( 'jcf.checkbox', get_template_directory_uri() . '/js/jcf.checkbox.js', '', '1.2.0', true );

		/**
		 * Enqueue JCF
		 */
		wp_enqueue_script( 'jomsom-custom-jcf-script', get_template_directory_uri() . '/js/jomsom-custom-jcf-scripts.min.js', array( 'jquery', 'jcf', 'jcf.file', 'jcf.radio', 'jcf.range', 'jcf.number', 'jcf.select', 'jcf.checkbox' ), '1.2.0', true );
	}

	/**
	 * Enqueue custom script for jomsom.
	 */
	wp_enqueue_script( 'jomsom-custom-scripts', get_template_directory_uri() . '/js/jomsom-custom-scripts.min.js', array( 'jquery' ), null );

	// Load the html5 shiv on IE 9
	wp_enqueue_script( 'jomsom-html5', get_template_directory_uri() . '/js/html5.min.js', array(), '3.7.0' );
	wp_script_add_data( 'jomsom-html5', 'conditional', 'lt IE 9' );
}
add_action( 'wp_enqueue_scripts', 'jomsom_scripts' );


/**
 * Enqueue scripts and styles for Metaboxes
 * @uses wp_register_script, wp_enqueue_script, and  wp_enqueue_style
 *
 * @action admin_print_scripts-post-new, admin_print_scripts-post, admin_print_scripts-page-new, admin_print_scripts-page
 *
 * @since Jomsom 0.1
 */
function jomsom_enqueue_metabox_scripts() {
    //Scripts
	wp_enqueue_script( 'jomsom-metabox', get_template_directory_uri() . '/js/jomsom-metabox.min.js', array( 'jquery', 'jquery-ui-tabs' ), '2013-10-05' );

	//CSS Styles
	wp_enqueue_style( 'jomsom-metabox-tabs', get_template_directory_uri() . '/css/jomsom-metabox-tabs.css' );
}
add_action( 'admin_print_scripts-post-new.php', 'jomsom_enqueue_metabox_scripts', 11 );
add_action( 'admin_print_scripts-post.php', 'jomsom_enqueue_metabox_scripts', 11 );
add_action( 'admin_print_scripts-page-new.php', 'jomsom_enqueue_metabox_scripts', 11 );
add_action( 'admin_print_scripts-page.php', 'jomsom_enqueue_metabox_scripts', 11 );


/**
 * Default Options.
 */
require get_template_directory() . '/inc/jomsom-default-options.php';

/**
 * Custom Header.
 */
require get_template_directory() . '/inc/jomsom-custom-header.php';


/**
 * Structure for jomsom
 */
require get_template_directory() . '/inc/jomsom-structure.php';


/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer-includes/jomsom-customizer.php';


/**
 * Custom Menus
 */
require get_template_directory() . '/inc/jomsom-menus.php';


/**
 * Load Featured Content file.
 */
require get_template_directory() . '/inc/jomsom-featured-content.php';


/**
 * Load Slider file.
 */
require get_template_directory() . '/inc/jomsom-featured-slider.php';


/**
 * Load Breadcrumb file.
 */
require get_template_directory() . '/inc/jomsom-breadcrumb.php';


/**
 * Load Widgets and Sidebars
 */
require get_template_directory() . '/inc/jomsom-widgets.php';


/**
 * Load Social Icons
 */
require get_template_directory() . '/inc/jomsom-social-icons.php';


/**
 * Load Metaboxes
 */
require get_template_directory() . '/inc/jomsom-metabox.php';


/**
 * Returns the options array for jomsom.
 * @uses  get_theme_mod
 *
 * @since Jomsom 0.1
 */
function jomsom_get_theme_options() {
	$jomsom_default_options = jomsom_get_default_theme_options();

	return array_merge( $jomsom_default_options , get_theme_mod( 'jomsom_theme_options', $jomsom_default_options ) ) ;
}


/**
 * Flush out all transients
 *
 * @uses delete_transient
 *
 * @action customize_save, jomsom_customize_preview (see jomsom_customizer function: jomsom_customize_preview)
 *
 * @since Jomsom 0.1
 */
function jomsom_flush_transients(){
	delete_transient( 'jomsom_featured_slider' );

	delete_transient( 'jomsom_custom_css' );

	delete_transient( 'jomsom_footer_content' );

	delete_transient( 'jomsom_featured_image' );

	delete_transient( 'jomsom_social_icons' );

	delete_transient( 'jomsom_scrollup' );

	delete_transient( 'all_the_cool_cats' );

	//Add jomsom default themes if there is no values
	if ( !get_theme_mod('jomsom_theme_options') ) {
		set_theme_mod( 'jomsom_theme_options', jomsom_get_default_theme_options() );
	}
}
add_action( 'customize_save', 'jomsom_flush_transients' );

/**
 * Flush out category transients
 *
 * @uses delete_transient
 *
 * @action edit_category
 *
 * @since Jomsom 0.1
 */
function jomsom_flush_category_transients(){
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'jomsom_flush_category_transients' );


/**
 * Flush out post related transients
 *
 * @uses delete_transient
 *
 * @action save_post
 *
 * @since Jomsom 0.1
 */
function jomsom_flush_post_transients(){
	delete_transient( 'jomsom_featured_carousel_slider' );

	delete_transient( 'jomsom_featured_image' );

	delete_transient( 'all_the_cool_cats' );
}
add_action( 'save_post', 'jomsom_flush_post_transients' );


if ( ! function_exists( 'jomsom_custom_css' ) ) :
	/**
	 * Enqueue Custon CSS
	 *
	 * @uses  set_transient, wp_head, wp_enqueue_style
	 *
	 * @action wp_enqueue_scripts
	 *
	 * @since Jomsom 0.1
	 */
	function jomsom_custom_css() {
		//jomsom_flush_transients();
		$options 	= jomsom_get_theme_options();

		$defaults 	= jomsom_get_default_theme_options();

		if ( ( !$jomsom_custom_css = get_transient( 'jomsom_custom_css' ) ) ) {
			$jomsom_custom_css ='';

			$text_color = get_header_textcolor();

			if ( 'blank' == $text_color ){
				//@remove only if block when WordPress 4.8 is released
				if ( !function_exists( 'has_custom_logo' ) && ( '' == $options['logo'] || !$options['logo_enable'] ) ) {
					$jomsom_custom_css	.=  "#site-branding-wrap { position: absolute !important; clip: rect(1px 1px 1px 1px); clip: rect(1px, 1px, 1px, 1px); }". "\n";
				}
				else {
					$jomsom_custom_css	.=  "#header-text { position: absolute !important; clip: rect(1px 1px 1px 1px); clip: rect(1px, 1px, 1px, 1px); }". "\n";
				}
			}
			elseif ( HEADER_TEXTCOLOR != $text_color ) {
				$jomsom_custom_css	.=  ".site-title, .site-title a { color: #".  $text_color ."; }". "\n";
			}

			//Header Color Options
			if( $defaults[ 'tagline_color' ] != $options[ 'tagline_color' ] ) {
				$jomsom_custom_css	.=  ".site-description { color: ".  $options[ 'tagline_color' ] ."; }". "\n";
			}

			//Custom CSS Option
			if( !empty( $options[ 'custom_css' ] ) ) {
				$jomsom_custom_css	.=  $options[ 'custom_css'] . "\n";
			}

			if ( '' != $jomsom_custom_css ){
				echo '<!-- refreshing cache -->' . "\n";

				$jomsom_custom_css = '<!-- '.get_bloginfo('name').' inline CSS Styles -->' . "\n" . '<style type="text/css" media="screen" rel="CT-Custom-CSS">' . "\n" . $jomsom_custom_css;

				$jomsom_custom_css .= '</style>' . "\n";

			}

			set_transient( 'jomsom_custom_css', htmlspecialchars_decode( $jomsom_custom_css ), 86940 );
		}

		echo $jomsom_custom_css;
	}
endif; //jomsom_custom_css
add_action( 'wp_head', 'jomsom_custom_css', 101  );


/**
 * Alter the query for the main loop in homepage
 *
 * @action pre_get_posts
 *
 * @since Jomsom 0.1
 */
function jomsom_alter_home( $query ){
	$options 	= jomsom_get_theme_options();

    $cats 		= $options[ 'front_page_category' ];

	if ( is_array( $cats ) && !in_array( '0', $cats ) ) {
		if( $query->is_main_query() && $query->is_home() ) {
			$query->query_vars['category__in'] =  $cats;
		}
	}
}
add_action( 'pre_get_posts','jomsom_alter_home' );


if ( ! function_exists( 'jomsom_content_nav' ) ) :
	/**
	 * Display navigation to next/previous pages when applicable
	 *
	 * @since Jomsom 0.1
	 */
	function jomsom_content_nav( $nav_id ) {
		global $wp_query, $post;

		// Don't print empty markup on single pages if there's nowhere to navigate.
		if ( is_single() ) {
			$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
			$next = get_adjacent_post( false, '', false );

			if ( ! $next && ! $previous )
				return;
		}

		// Don't print empty markup in archives if there's only one page.
		if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) ) {
			return;
		}

		$options			= jomsom_get_theme_options();

		$pagination_type	= $options['pagination_type'];

		$nav_class = ( is_single() ) ? 'site-navigation post-navigation' : 'site-navigation paging-navigation';

		/**
		 * Check if navigation type is Jetpack Infinite Scroll and if it is enabled, else goto default pagination
		 * if it's active then disable pagination
		 */
		if ( ( 'infinite-scroll-click' == $pagination_type || 'infinite-scroll-scroll' == $pagination_type ) && class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'infinite-scroll' ) ) {
			return false;
		}

		$classes = $pagination_type;

		if ( 'numeric' == $pagination_type && function_exists( 'wp_pagenavi' ) ) {
			$classes = 'wp-pagenavi';
		}
		?>
	        <nav role="navigation" class="navigation pagination <?php echo $classes; ?>" id="<?php echo esc_attr( $nav_id ); ?>">
	        	<h3 class="screen-reader-text"><?php _e( 'Post navigation', 'jomsom' ); ?></h3>
				<?php
				/**
				 * Check if navigation type is numeric and if Wp-PageNavi Plugin is enabled
				 */
				if ( 'numeric' == $pagination_type && ( function_exists( 'wp_pagenavi' ) || function_exists( 'the_posts_pagination' ) ) ) {
					if ( function_exists( 'wp_pagenavi' ) ) {
						wp_pagenavi();
					}
					elseif ( function_exists( 'the_posts_pagination' ) ) {
						// Previous/next page navigation.
						the_posts_pagination( array(
							'prev_text'          => __( 'Previous page', 'jomsom' ),
							'next_text'          => __( 'Next page', 'jomsom' ),
							'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'jomsom' ) . ' </span>',
						) );
					}
	            }
	            else { ?>
	                <div class="nav-links nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'jomsom' ) ); ?></div>
	                <div class="nav-links nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'jomsom' ) ); ?></div>
	            <?php
	            } ?>
	        </nav><!-- #nav -->
		<?php
	}
endif; // jomsom_content_nav


if ( ! function_exists( 'jomsom_posts_link_attributes' ) ) :
	/**
	 * Add class to links generated by WordPress “next_post_link” and “previous_post_link” functions
	 *
	 * @since Jomsom 0.1
	 */
	function jomsom_posts_link_attributes() {
	    return 'class="next page-numbers"';
	}
endif; // jomsom_posts_link_attributes
add_filter('next_posts_link_attributes', 'jomsom_posts_link_attributes');
add_filter('previous_posts_link_attributes', 'jomsom_posts_link_attributes');


if ( ! function_exists( 'jomsom_comment' ) ) :
	/**
	 * Template for comments and pingbacks.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 *
	 * @since Jomsom 0.1
	 */
	function jomsom_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;

		if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>

		<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
			<div class="comment-body">
				<?php _e( 'Pingback:', 'jomsom' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'jomsom' ), '<span class="edit-link">', '</span>' ); ?>
			</div>

		<?php else : ?>

		<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
			<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
				<footer class="comment-meta">
					<div class="comment-author vcard">
						<?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
						<?php printf( __( '%s <span class="says">says:</span>', 'jomsom' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
					</div><!-- .comment-author -->

					<div class="comment-metadata">
						<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
							<time datetime="<?php comment_time( 'c' ); ?>">
								<?php printf( _x( '%1$s at %2$s', '1: date, 2: time', 'jomsom' ), get_comment_date(), get_comment_time() ); ?>
							</time>
						</a>
						<?php edit_comment_link( __( 'Edit', 'jomsom' ), '<span class="edit-link">', '</span>' ); ?>
					</div><!-- .comment-metadata -->

					<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'jomsom' ); ?></p>
					<?php endif; ?>
				</footer><!-- .comment-meta -->

				<div class="comment-content">
					<?php comment_text(); ?>
				</div><!-- .comment-content -->

				<?php
					comment_reply_link( array_merge( $args, array(
						'add_below' => 'div-comment',
						'depth'     => $depth,
						'max_depth' => $args['max_depth'],
						'before'    => '<div class="reply">',
						'after'     => '</div>',
					) ) );
				?>
			</article><!-- .comment-body -->

		<?php
		endif;
	}
endif; // jomsom_comment()


if ( ! function_exists( 'jomsom_the_attached_image' ) ) :
	/**
	 * Prints the attached image with a link to the next attached image.
	 *
	 * @since Jomsom 0.1
	 */
	function jomsom_the_attached_image() {
		$post                = get_post();
		$attachment_size     = apply_filters( 'jomsom_attachment_size', array( 1200, 1200 ) );
		$next_attachment_url = wp_get_attachment_url();

		/**
		 * Grab the IDs of all the image attachments in a gallery so we can get the
		 * URL of the next adjacent image in a gallery, or the first image (if
		 * we're looking at the last image in a gallery), or, in a gallery of one,
		 * just the link to that image file.
		 */
		$attachment_ids = get_posts( array(
			'post_parent'    => $post->post_parent,
			'fields'         => 'ids',
			'numberposts'    => 1,
			'post_status'    => 'inherit',
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'order'          => 'ASC',
			'orderby'        => 'menu_order ID'
		) );

		// If there is more than 1 attachment in a gallery...
		if ( count( $attachment_ids ) > 1 ) {
			foreach ( $attachment_ids as $attachment_id ) {
				if ( $attachment_id == $post->ID ) {
					$next_id = current( $attachment_ids );
					break;
				}
			}

			// get the URL of the next image attachment...
			if ( $next_id )
				$next_attachment_url = get_attachment_link( $next_id );

			// or get the URL of the first image attachment.
			else
				$next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );
		}

		printf(
			'<a href="%1$s" title="%2$s" rel="attachment">%3$s</a>',
			esc_url( $next_attachment_url ),
			the_title_attribute( array( 'echo' => false ) ),
			wp_get_attachment_image( $post->ID, $attachment_size )
		);
	}
endif; //jomsom_the_attached_image


if ( ! function_exists( 'jomsom_entry_meta' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 *
	 * @since Jomsom 0.1
	 */
	function jomsom_entry_meta() {
		/* Check Author URL to Support Google Authorship
		*
		* By deault the author will link to author archieve page
		* But if the author have added their Website in Profile page then it will link to author website
		*/
		if ( get_the_author_meta( 'user_url' ) != '' ) {
			$jomsom_author_url = get_the_author_meta( 'user_url' );
		}
		else {
			$jomsom_author_url = get_author_posts_url( get_the_author_meta( "ID" ) );
		}

		$time_string = '<i class="fa fa-calendar"></i>&nbsp;<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<i class="fa fa-calendar"></i>&nbsp;<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		echo $time_string;

		if ( is_singular() || is_multi_author() ) {
			echo '<span class="sep">&nbsp;/</span>';

			echo '&nbsp;<span class="sep screen-reader-text">' . __( 'by', 'jomsom') .'</span>&nbsp;';

			printf(
				'<span class="byline"><i class="fa fa-user"></i>&nbsp;<span class="author vcard">%1$s <a class="url fn n" href="%2$s" title="%3$s" rel="author">%4$s</a></span>',
				'<span class="screen-reader-text">' . _x( 'Author' , 'Accessibility: Used before author names.', 'jomsom' ) . '</span>',
				esc_url( $jomsom_author_url ),
				esc_attr( sprintf( __( 'View all posts by %s', 'jomsom' ), get_the_author() ) ),
				esc_html( get_the_author() )
			);
		}

		edit_post_link( '&nbsp;' . esc_html__( 'Edit', 'jomsom' ), '<span class="edit-link">', '</span>' );
	}
endif; //jomsom_entry_meta



if ( ! function_exists( 'jomsom_tag_category' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags.
	 *
	 * @since Jomsom 0.1
	 */
	function jomsom_tag_category() {
		if ( 'post' == get_post_type() ) {
			if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) {
				echo '<span class="comments-link"><i class="fa fa-comments"></i>&nbsp;';
				comments_popup_link( esc_html__( 'Leave a comment', 'jomsom' ), esc_html__( '1 Comment', 'jomsom' ), esc_html__( '% Comments', 'jomsom' ) );
				echo '</span>';
			}

			$categories_list = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'jomsom' ) );
			if ( $categories_list && jomsom_categorized_blog() ) {
				printf(
					'<span class="cat-links"><i class="fa fa-folder-open"></i>&nbsp;%1$s%2$s</span>',
					'<span class="screen-reader-text">' . _x( 'Categories' , 'Accessibility: Used before category names.', 'jomsom' ) . '</span>',
					$categories_list
				);
			}

			$tags_list = get_the_tag_list( '', _x( ', ', 'Used between list items, there is a space after the comma.', 'jomsom' ) );
			if ( $tags_list ) {
				printf(
					'<span class="tags-links"><i class="fa fa-tags"></i>&nbsp;%1$s%2$s</span>',
					'<span class="screen-reader-text">' . _x( 'Tags' , 'Accessibility: Used before tag names.', 'jomsom' ) . '</span>',
					$tags_list
				);
			}
		}
	}
endif; //jomsom_tag_category


/**
 * Returns true if a blog has more than 1 category
 *
 * @since Jomsom 0.1
 */
function jomsom_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	if ( '1' != $all_the_cool_cats ) {
		// This blog has more than 1 category so jomsom_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so jomsom_categorized_blog should return false
		return false;
	}
}


/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @since Jomsom 0.1
 */
function jomsom_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'jomsom_page_menu_args' );


/**
 * Filter in a link to a content ID attribute for the next/previous image links on image attachment pages
 *
 * @since Jomsom 0.1
 */
function jomsom_enhanced_image_navigation( $url, $id ) {
	if ( ! is_attachment() && ! wp_attachment_is_image( $id ) )
		return $url;

	$image = get_post( $id );
	if ( ! empty( $image->post_parent ) && $image->post_parent != $id )
		$url .= '#main';

	return $url;
}
add_filter( 'attachment_link', 'jomsom_enhanced_image_navigation', 10, 2 );


/**
 * Count the number of footer sidebars to enable dynamic classes for the footer
 *
 * @since Jomsom 0.1
 */
function jomsom_footer_sidebar_class() {
	$count = 0;

	if ( is_active_sidebar( 'footer-1' ) )
		$count++;

	if ( is_active_sidebar( 'footer-2' ) )
		$count++;

	if ( is_active_sidebar( 'footer-3' ) )
		$count++;

	if ( is_active_sidebar( 'footer-4' ) )
		$count++;

	$class = '';

	switch ( $count ) {
		case '1':
			$class = 'one';
			break;
		case '2':
			$class = 'two';
			break;
		case '3':
			$class = 'three';
			break;
		case '4':
			$class = 'four';
			break;
	}

	if ( $class )
		echo 'class="footer-t ' . $class . '"';
}


if ( ! function_exists( 'jomsom_excerpt_length' ) ) :
	/**
	 * Sets the post excerpt length to n words.
	 *
	 * function tied to the excerpt_length filter hook.
	 * @uses filter excerpt_length
	 *
	 * @since Jomsom 0.1
	 */
	function jomsom_excerpt_length( $length ) {
		// Getting data from Customizer Options
		$options	= jomsom_get_theme_options();
		$length	= $options['excerpt_length'];
		return $length;
	}
endif; //jomsom_excerpt_length
add_filter( 'excerpt_length', 'jomsom_excerpt_length' );


/**
 * Change the defult excerpt length of 30 to whatever passed as value
 *
 * @use excerpt(10) or excerpt (..)  if excerpt length needs only 10 or whatevere
 * @uses get_permalink, get_the_excerpt
 */
function jomsom_excerpt_desired( $num ) {
    $limit = $num+1;
    $excerpt = explode( ' ', get_the_excerpt(), $limit );
    array_pop( $excerpt );
    $excerpt = implode( " ",$excerpt )."<a href='" .get_permalink() ." '></a>";
    return $excerpt;
}


if ( ! function_exists( 'jomsom_continue_reading' ) ) :
	/**
	 * Returns a "Custom Continue Reading" link for excerpts
	 *
	 * @since Jomsom 0.1
	 */
	function jomsom_continue_reading() {
		// Getting data from Customizer Options
		$options		=	jomsom_get_theme_options();
		$more_tag_text	= $options['excerpt_more_text'];

		return '&nbsp;<span class="readmore"><a class="more-link" href="' . esc_url( get_permalink() ) . '">' .  sprintf( __( '%s', 'jomsom' ), $more_tag_text ) . '</a></span><!-- .readmore -->
		';
	}
endif; //jomsom_continue_reading
add_filter( 'excerpt_more', 'jomsom_continue_reading' );


if ( ! function_exists( 'jomsom_excerpt_more' ) ) :
	/**
	 * Replaces "[...]" (appended to automatically generated excerpts) with jomsom_continue_reading().
	 *
	 * @since Jomsom 0.1
	 */
	function jomsom_excerpt_more( $more ) {
		return jomsom_continue_reading();
	}
endif; //jomsom_excerpt_more
add_filter( 'excerpt_more', 'jomsom_excerpt_more' );


if ( ! function_exists( 'jomsom_custom_excerpt' ) ) :
	/**
	 * Adds Continue Reading link to more tag excerpts.
	 *
	 * function tied to the get_the_excerpt filter hook.
	 *
	 * @since Jomsom 0.1
	 */
	function jomsom_custom_excerpt( $output ) {
		if ( has_excerpt() && ! is_attachment() ) {
			$output .= jomsom_continue_reading();
		}
		return $output;
	}
endif; //jomsom_custom_excerpt
add_filter( 'get_the_excerpt', 'jomsom_custom_excerpt' );


if ( ! function_exists( 'jomsom_more_link' ) ) :
	/**
	 * Replacing Continue Reading link to the_content more.
	 *
	 * function tied to the the_content_more_link filter hook.
	 *
	 * @since Jomsom 0.1
	 */
	function jomsom_more_link( $more_link, $more_link_text ) {
	 	$options		=	jomsom_get_theme_options();
		$more_tag_text	= $options['excerpt_more_text'];

		return str_replace( $more_link_text, $more_tag_text, $more_link );
	}
endif; //jomsom_more_link
add_filter( 'the_content_more_link', 'jomsom_more_link', 10, 2 );


if ( ! function_exists( 'jomsom_body_classes' ) ) :
	/**
	 * Adds jomsom layout classes to the array of body classes.
	 *
	 * @since Jomsom 0.1
	 */
	function jomsom_body_classes( $classes ) {
		$options = jomsom_get_theme_options();

		// Adds a class of group-blog to blogs with more than 1 published author
		if ( is_multi_author() ) {
			$classes[] = 'group-blog';
		}

		//Get current layout and add class with respect to current layout
		$layout  = jomsom_get_theme_layout();

		switch ( $layout ) {
			case 'left-sidebar':
				$classes[] = 'two-columns content-right';
			break;

			case 'right-sidebar':
				$classes[] = 'two-columns content-left';
			break;

			case 'no-sidebar':
				$classes[] = 'no-sidebar content-width';
			break;
		}

		//Add Content Layout to body class
		if( "" != $options['content_layout'] ) {
			$classes[] = $options['content_layout'];
		}

		$classes[] = 'mobile-menu-one';

		$classes 	= apply_filters( 'jomsom_body_classes', $classes );

		$classes[] = ( $options['fix_primary_menu']  ) ? 'primary-menu-fixed' : '';

		if( ( 'disabled' != $options['featured_slider_option'] || 'disabled' != $options['enable_featured_header_image'] ) && ( 'disabled' != $options['featured_content_option'] ) ) {
			$classes[] = 'featured-content-space';
		}

		return $classes;
	}
endif; //jomsom_body_classes
add_filter( 'body_class', 'jomsom_body_classes' );


if ( ! function_exists( 'jomsom_post_classes' ) ) :
	/**
	 * Adds jomsom post classes to the array of post classes.
	 * used for supporting different content layouts
	 *
	 * @since Jomsom 0.1
	 */
	function jomsom_post_classes( $classes ) {
		//Getting Ready to load data from Theme Options Panel
		$options 		= jomsom_get_theme_options();

		$contentlayout = $options['content_layout'];

		if ( is_archive() || is_home() ) {
			$classes[] = $contentlayout;
		}

		return $classes;
	}
endif; //jomsom_post_classes
add_filter( 'post_class', 'jomsom_post_classes' );


if ( ! function_exists( 'jomsom_get_theme_layout' ) ) :
	/**
	 * Returns Theme Layout prioritizing the meta box layouts
	 *
	 * @uses  get_theme_mod
	 *
	 * @action wp_head
	 *
	 * @since Jomsom 0.1
	 */
	function jomsom_get_theme_layout() {
		$id = '';

		// Set $id as Shop Page id for WooCommerce Pages excluding cart and checkout
		global $post, $wp_query;

	    // Front page displays in Reading Settings
		$page_on_front  = get_option('page_on_front') ;
		$page_for_posts = get_option('page_for_posts');

		// Get Page ID outside Loop
		$page_id = $wp_query->get_queried_object_id();

		// Blog Page or Front Page setting in Reading Settings
		if ( $page_id == $page_for_posts || $page_id == $page_on_front ) {
	        $id = $page_id;
	    }
	    else if ( is_singular() ) {
	 		if ( is_attachment() ) {
				$id = $post->post_parent;
			}
			else {
				$id = $post->ID;
			}
		}

		//Get appropriate metabox value of layout
		if ( '' != $id ) {
			$layout = get_post_meta( $id, 'jomsom-layout-option', true );
		}
		else {
			$layout = 'default';
		}

		//Load options data
		$options = jomsom_get_theme_options();

		//check empty and load default
		if ( empty( $layout ) || 'default' == $layout ) {
			$layout = $options['theme_layout'];
		}

		return $layout;
	}
endif; //jomsom_get_theme_layout


if ( ! function_exists( 'jomsom_archive_content_image' ) ) :
	/**
	 * Template for Featured Image in Archive Content
	 *
	 * To override this in a child theme
	 * simply create your own jomsom_archive_content_image(), and that function will be used instead.
	 *
	 * @since Jomsom 0.1
	 */
	function jomsom_archive_content_image() {
		$options 			= jomsom_get_theme_options();

		$featured_image = $options['content_layout'];

		if ( has_post_thumbnail() && 'excerpt-image-left' == $featured_image ) { ?>
			<figure class="entry-thumbnail post-thumbnail featured-image">
	            <a rel="bookmark" href="<?php the_permalink(); ?>">
	            <?php the_post_thumbnail( 'jomsom-portrait' ); ?>
				</a>
			</figure>
	   	<?php
		}
	}
endif; //jomsom_archive_content_image
add_action( 'jomsom_before_entry_container', 'jomsom_archive_content_image', 10 );


if ( ! function_exists( 'jomsom_single_content_image' ) ) :
	/**
	 * Template for Featured Image in Single Post
	 *
	 * To override this in a child theme
	 * simply create your own jomsom_single_content_image(), and that function will be used instead.
	 *
	 * @since Jomsom 0.1
	 */
	function jomsom_single_content_image() {
		global $post, $wp_query;

		// Get Page ID outside Loop
		$page_id = $wp_query->get_queried_object_id();
		if( $post) {
	 		if ( is_attachment() ) {
				$parent = $post->post_parent;
				$individual_featured_image = get_post_meta( $parent,'jomsom-featured-image', true );
			} else {
				$individual_featured_image = get_post_meta( $page_id,'jomsom-featured-image', true );
			}
		}

		if( empty( $individual_featured_image ) || ( !is_page() && !is_single() ) ) {
			$individual_featured_image = 'default';
		}

		// Getting data from Theme Options
	   	$options = jomsom_get_theme_options();

		$featured_image = $options['single_post_image_layout'];

		if ( ( $individual_featured_image == 'disable' || '' == get_the_post_thumbnail() || ( $individual_featured_image=='default' && $featured_image == 'disabled') ) ) {
			echo '<!-- Page/Post Single Image Disabled or No Image set in Post Thumbnail -->';
			return false;
		}
		else {
			$class = '';

			if ( 'default' == $individual_featured_image ) {
				$class = $featured_image;
			}
			else {
				$class = 'from-metabox ' . $individual_featured_image;
			}

			?>
			<figure class="entry-thumbnail post-thumbnail featured-image <?php echo $class; ?>">
                <?php
				if ( $individual_featured_image == 'large' || ( $individual_featured_image=='default' && $featured_image == 'large' ) ) {
                     the_post_thumbnail( 'large' );
                }
               	elseif ( $individual_featured_image == 'slider' || ( $individual_featured_image=='default' && $featured_image == 'slider-image-size' ) ) {
					the_post_thumbnail( 'jomsom-slider' );
				}
				elseif ( $individual_featured_image == 'featured' || ( $individual_featured_image=='default' && $featured_image == 'featured' ) ) {
					the_post_thumbnail(); // Use image set in set_post_thumbnail
				}
				else {
					the_post_thumbnail( 'full' );
				} ?>
		   	</figure><!-- .entry-thumbnail -->
	   	<?php
		}
	}
endif; //jomsom_single_content_image
add_action( 'jomsom_before_post_container', 'jomsom_single_content_image', 10 );
add_action( 'jomsom_before_page_container', 'jomsom_single_content_image', 10 );


if ( ! function_exists( 'jomsom_get_comment_section' ) ) :
	/**
	 * Comment Section
	 *
	 * @get comment setting from theme options and display comments sections accordingly
	 * @display comments_template
	 * @action jomsom_comment_section
	 *
	 * @since Jomsom 0.1
	 */
	function jomsom_get_comment_section() {
		if ( comments_open() || '0' != get_comments_number() ) {
			comments_template();
		}
}
endif;
add_action( 'jomsom_comment_section', 'jomsom_get_comment_section', 10 );


if ( ! function_exists( 'jomsom_promotion_headline' ) ) :
	/**
	 * Template for Promotion Headline
	 *
	 * To override this in a child theme
	 * simply create your own jomsom_promotion_headline(), and that function will be used instead.
	 *
	 * @uses jomsom_before_main action to add it in the header
	 * @since Jomsom 0.1
	 */
	function jomsom_promotion_headline() {
		global $post, $wp_query;
	   	$options 	= jomsom_get_theme_options();

		$enable_promotion 	= $options['promotion_headline_option'];


		// Front page displays in Reading Settings
		$page_on_front = get_option( 'page_on_front' ) ;
		$page_for_posts = get_option('page_for_posts');

		// Get Page ID outside Loop
		$page_id = $wp_query->get_queried_object_id();

		 if ( 'entire-site' == $enable_promotion || ( ( is_front_page() || ( is_home() && $page_for_posts != $page_id ) ) && 'homepage' ==  $enable_promotion  ) ) {
			$jomsom_options 	= jomsom_get_theme_options();
			echo '
				<div id="promotion-message">
					<div class="container">';

			    	$jomsom_promotion_headline 		= $jomsom_options['promotion_headline'];
					$jomsom_promotion_subheadline 		= $jomsom_options['promotion_subheadline'];

					echo '
					<div class="section left">';

					if ( "" != $jomsom_promotion_headline ) {
						echo '<h2>' . $jomsom_promotion_headline . '</h2>';
					}

					if ( "" != $jomsom_promotion_subheadline ) {
						echo '<p>' . $jomsom_promotion_subheadline . '</p>';
					}

					echo '
					</div><!-- .section.left -->';

					$jomsom_promotion_headline_button 	= $jomsom_options['promotion_headline_button'];
					$jomsom_promotion_headline_target 	= $jomsom_options['promotion_headline_target'];

					//support qTranslate plugin
					if ( function_exists( 'qtrans_convertURL' ) ) {
						$jomsom_promotion_headline_url = qtrans_convertURL($jomsom_options[ 'promotion_headline_url' ]);
					}
					else {
						$jomsom_promotion_headline_url = $jomsom_options[ 'promotion_headline_url' ];
					}

					if ( "" != $jomsom_promotion_headline_url ) {
						if ( "1" == $jomsom_promotion_headline_target ) {
							$jomsom_headlinetarget = '_blank';
						}
						else {
							$jomsom_headlinetarget = '_self';
						}

						echo '
						<div class="section right">
							<a class="promotion-button" href="' . esc_url( $jomsom_promotion_headline_url ) . '" target="' . $jomsom_headlinetarget . '">' . esc_attr( $jomsom_promotion_headline_button ) . '
							</a>
						</div><!-- .section.right -->';
					}

				echo '
					</div><!-- .container -->
				</div><!-- #promotion-message -->';
		}
	}
endif; // jomsom_promotion_featured_content
add_action( 'jomsom_after_content', 'jomsom_promotion_headline', 40 );


/**
 * Footer Text
 *
 * @get footer text from theme options and display them accordingly
 * @display footer_text
 * @action jomsom_footer
 *
 * @since Jomsom 0.1
 */
function jomsom_footer_content() {
	jomsom_flush_transients();
	if ( ( !$jomsom_footer_content = get_transient( 'jomsom_footer_content' ) ) ) {
		echo '<!-- refreshing cache -->';

		$jomsom_content = jomsom_get_content();

		$jomsom_footer_content .=  '
	    	<div class="site-info two">
	    		<div class="copyright">' . $jomsom_content['left'] . '</div>

	    		<div class="powered">' . $jomsom_content['right'] . '</div>
	    	</div><!-- .site-info -->';

    	set_transient( 'jomsom_footer_content', $jomsom_footer_content, 86940 );
    }

    echo $jomsom_footer_content;
}
add_action( 'jomsom_footer', 'jomsom_footer_content', 80 );


/**
 * Return the first image in a post. Works inside a loop.
 * @param [integer] $post_id [Post or page id]
 * @param [string/array] $size Image size. Either a string keyword (thumbnail, medium, large or full) or a 2-item array representing width and height in pixels, e.g. array(32,32).
 * @param [string/array] $attr Query string or array of attributes.
 * @return [string] image html
 *
 * @since Jomsom 0.1
 */

function jomsom_get_first_image( $postID, $size, $attr ) {
	ob_start();

	ob_end_clean();

	$image 	= '';

	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', get_post_field('post_content', $postID ) , $matches);

	if( isset( $matches [1] [0] ) ) {
		//Get first image
		$first_img = $matches [1] [0];

		return '<img class="pngfix wp-post-image" src="'. $first_img .'">';
	}

	return false;
}


if ( ! function_exists( 'jomsom_scrollup' ) ) {
	/**
	 * This function loads Scroll Up Navigation
	 *
	 * @action jomsom_footer action
	 * @uses set_transient and delete_transient
	 */
	function jomsom_scrollup() {
		//jomsom_flush_transients();
		if ( !$jomsom_scrollup = get_transient( 'jomsom_scrollup' ) ) {

			// get the data value from theme options
			$options = jomsom_get_theme_options();
			echo '<!-- refreshing cache -->';

			//site stats, analytics header code
			if ( ! $options['disable_scrollup'] ) {
				$jomsom_scrollup =  '<a href="#masthead" id="scrollup" class="fa fa-chevron-up"><span class="screen-reader-text">' . __( 'Scroll Up', 'jomsom' ) . '</span></a>' ;
			}

			set_transient( 'jomsom_scrollup', $jomsom_scrollup, 86940 );
		}
		echo $jomsom_scrollup;
	}
}
add_action( 'jomsom_after', 'jomsom_scrollup', 10 );


if ( ! function_exists( 'jomsom_page_post_meta' ) ) :
	/**
	 * Post/Page Meta for Google Structure Data
	 */
	function jomsom_page_post_meta() {
		$jomsom_author_url = esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) );

		$jomsom_page_post_meta = '<span class="post-time">' . __( 'Posted on', 'jomsom' ) . ' <time class="entry-date updated" datetime="' . esc_attr( get_the_date( 'c' ) ) . '" pubdate>' . esc_html( get_the_date() ) . '</time></span>';
	    $jomsom_page_post_meta .= '<span class="post-author">' . __( 'By', 'jomsom' ) . ' <span class="author vcard"><a class="url fn n" href="' . $jomsom_author_url . '" title="View all posts by ' . get_the_author() . '" rel="author">' .get_the_author() . '</a></span>';

		return $jomsom_page_post_meta;
	}
endif; //jomsom_page_post_meta


if ( ! function_exists( 'jomsom_truncate_phrase' ) ) :
	/**
	 * Return a phrase shortened in length to a maximum number of characters.
	 *
	 * Result will be truncated at the last white space in the original string. In this function the word separator is a
	 * single space. Other white space characters (like newlines and tabs) are ignored.
	 *
	 * If the first `$max_characters` of the string does not contain a space character, an empty string will be returned.
	 *
	 * @since 1.5
	 *
	 * @param string $text            A string to be shortened.
	 * @param integer $max_characters The maximum number of characters to return.
	 *
	 * @return string Truncated string
	 */
	function jomsom_truncate_phrase( $text, $max_characters ) {

		$text = trim( $text );

		if ( mb_strlen( $text ) > $max_characters ) {
			//* Truncate $text to $max_characters + 1
			$text = mb_substr( $text, 0, $max_characters + 1 );

			//* Truncate to the last space in the truncated string
			$text = trim( mb_substr( $text, 0, mb_strrpos( $text, ' ' ) ) );
		}

		return $text;
	}
endif; //jomsom_truncate_phrase


if ( ! function_exists( 'jomsom_get_the_content_limit' ) ) :
	/**
	 * Return content stripped down and limited content.
	 *
	 * Strips out tags and shortcodes, limits the output to `$max_char` characters, and appends an ellipsis and more link to the end.
	 *
	 * @since 0.1.0
	 *
	 * @param integer $max_characters The maximum number of characters to return.
	 * @param string  $more_link_text Optional. Text of the more link. Default is "(more...)".
	 * @param bool    $stripteaser    Optional. Strip teaser content before the more text. Default is false.
	 *
	 * @return string Limited content.
	 */
	function jomsom_get_the_content_limit( $max_characters, $more_link_text = '(more...)', $stripteaser = false ) {

		$content = get_the_content( '', $stripteaser );

		//* Strip tags and shortcodes so the content truncation count is done correctly
		$content = strip_tags( strip_shortcodes( $content ), apply_filters( 'get_the_content_limit_allowedtags', '<script>,<style>' ) );

		//* Remove inline styles / scripts
		$content = trim( preg_replace( '#<(s(cript|tyle)).*?</\1>#si', '', $content ) );

		//* Truncate $content to $max_char
		$content = jomsom_truncate_phrase( $content, $max_characters );

		//* More link?
		if ( $more_link_text ) {
			$link   = apply_filters( 'get_the_content_more_link', sprintf( '<a href="%s" class="more-link">%s</a>', esc_url( get_permalink() ), $more_link_text ), $more_link_text );
			$output = sprintf( '<p>%s %s</p>', $content, $link );
		} else {
			$output = sprintf( '<p>%s</p>', $content );
			$link = '';
		}

		return apply_filters( 'jomsom_get_the_content_limit', $output, $content, $link, $max_characters );

	}
endif; //jomsom_get_the_content_limit


if ( ! function_exists( 'jomsom_post_navigation' ) ) :
	/**
	 * Displays Single post Navigation
	 *
	 * @uses  the_post_navigation
	 *
	 * @action jomsom_after_post
	 *
	 * @since Jomsom 3.0
	 */
	function jomsom_post_navigation() {
		the_post_navigation( array(
			'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next &rarr;', 'jomsom' ) . '</span> ' .
				'<span class="screen-reader-text">' . __( 'Next post:', 'jomsom' ) . '</span> ' .
				'<span class="post-title">%title</span>',
			'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( '&larr; Previous', 'jomsom' ) . '</span> ' .
				'<span class="screen-reader-text">' . __( 'Previous post:', 'jomsom' ) . '</span> ' .
				'<span class="post-title">%title</span>',
		) );
	}
endif; //jomsom_post_navigation
add_action( 'jomsom_after_post', 'jomsom_post_navigation', 10 );


/**
 * Migrate Logo to New WordPress core Custom Logo
 *
 *
 * Runs if version number saved in theme_mod "logo_version" doesn't match current theme version.
 */
function jomsom_logo_migrate() {
	$ver = get_theme_mod( 'logo_version', false );

	// Return if update has already been run
	if ( version_compare( $ver, '1.1' ) >= 0 ) {
		return;
	}

	/**
	 * Get Theme Options Values
	 */
	$options 	= jomsom_get_theme_options();

	// If a logo has been set previously, update to use logo feature introduced in WordPress 4.5
	if ( function_exists( 'the_custom_logo' ) ) {
		if( isset( $options['logo'] ) && '' != $options['logo'] ) {
			// Since previous logo was stored a URL, convert it to an attachment ID
			$logo = attachment_url_to_postid( $options['logo'] );

			if ( is_int( $logo ) ) {
				set_theme_mod( 'custom_logo', $logo );
			}
		}

  		// Update to match logo_version so that script is not executed continously
		set_theme_mod( 'logo_version', '1.1' );
	}

}
add_action( 'after_setup_theme', 'jomsom_logo_migrate' );