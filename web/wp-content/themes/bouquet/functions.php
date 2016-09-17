<?php
/**
 * Bouquet functions and definitions
 *
 * @package Bouquet
 */

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) )
	$content_width = 1048; /* pixels */

function bouquet_content_width() {
	global $content_width;

	if ( is_active_sidebar( 'sidebar-1' ) )
		$content_width = 714;

	if ( is_page_template( 'full-width-page.php' ) )
		$content_width = 1048;
}
add_action( 'init', 'bouquet_content_width' );

if ( ! function_exists( 'bouquet_setup' ) ):

// Sets up theme defaults and registers support for various WordPress features.
function bouquet_setup() {

	// Make theme available for translation
	load_theme_textdomain( 'bouquet', get_template_directory() . '/languages' );

	 // Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'bouquet' ),
	) );

	// This theme allows users to set a custom background.
	add_theme_support( 'custom-background' );

	// This theme allows users to upload a custom header.
	add_theme_support( 'custom-header', apply_filters( 'bouquet_custom_header_args', array(
		'default-image'          => '',
		'default-text-color'     => bouquet_header_text_color(),
		'width'                  => 1100,
		'height'                 => 180,
		'flex-height'            => true,
		'wp-head-callback'       => 'bouquet_header_style',
		'admin-head-callback'    => 'bouquet_admin_header_style',
	) ) );

	// Add support for Post Formats
	add_theme_support( 'post-formats', array( 'aside', 'image', 'gallery' ) );
}
endif; // bouquet_setup

// Tell WordPress to run bouquet_setup() when the 'after_setup_theme' hook is run.
add_action( 'after_setup_theme', 'bouquet_setup' );

/**
 * Enqueue scripts and styles
 */
function bouquet_scripts() {
	wp_enqueue_style( 'bouquet', get_stylesheet_uri() );

	wp_enqueue_script( 'bouquet-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
}
add_action( 'wp_enqueue_scripts', 'bouquet_scripts' );

// Get Bouquet options
function bouquet_get_options() {
	$defaults = array(
		'color_scheme' => 'pink-dogwood',
	);
	return get_option( 'bouquet_theme_options', $defaults );
}

// Register our color schemes and add them to the style queue
function bouquet_color_registrar() {
	$color_scheme = bouquet_current_floral_scheme();

	if ( ! empty( $color_scheme ) )
		wp_enqueue_style( $color_scheme, get_template_directory_uri() . '/colors/' . $color_scheme . '/' . $color_scheme . '.css', null, null );
}
add_action( 'wp_enqueue_scripts', 'bouquet_color_registrar' );

/**
 *  Returns the current floral scheme as selected in the theme options
 */
function bouquet_current_floral_scheme() {
	$options = bouquet_get_options();
	return $options['color_scheme'];
}

// Check the current color scheme and return a default header image that matches it
function bouquet_default_header_image() {
	$floral_scheme = bouquet_current_floral_scheme();
	return get_template_directory_uri() . '/colors/' . $floral_scheme . '/' . $floral_scheme . '-header.png';
}

// Set a default header text color
function bouquet_header_text_color() {
	return ( 'forget-me-not' == bouquet_current_floral_scheme() ) ? '123b66' : '891e42';
}

/**
* Add custom header support
*/
if ( ! function_exists( 'bouquet_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 */
function bouquet_header_style() {
	// If no custom options for text are set, let's bail
	if ( HEADER_TEXTCOLOR == get_header_textcolor() && '' == get_header_image() )
		return;
	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Do we have a custom header image?
		if ( '' != get_header_image() ) :
	?>
		#branding {
			background: url(<?php header_image(); ?>);
		}
	<?php
		endif;

		// Has the text been hidden?
		if ( 'blank' == get_header_textcolor() ) :
	?>
		#site-title,
		#site-description {
 	 		position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		#site-title a,
		#site-description {
			color: #<?php echo get_header_textcolor(); ?> !important;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif;

if ( ! function_exists( 'bouquet_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 */
function bouquet_admin_header_style() {
?>
	<style type="text/css">
	.appearance_page_custom-header #headimg {
		background-color: #<?php echo ( '' != get_background_color() ? get_background_color() : 'fff' ); ?>;
		<?php if ( '' == get_header_image() && '' == get_background_color() )
			echo 'background-image: url(' . bouquet_default_header_image() .') !important;';
		?>
		border: none;
		width: 1100px;
		height: 180px;
		text-align: left;
	}
	#headimg h1 {
		font: 50px 'Sorts Mill Goudy', "Times New Roman", serif;
		margin-bottom: .2em;
		text-transform: uppercase;
	}
	#headimg h1 a {
		background: rgba(255, 255, 255, 0.6);
	<?php
		if ( 'forget-me-not' == bouquet_current_floral_scheme() ) : ?>
		color: #123b66;
	<?php else : ?>
		color: #891e42;
	<?php endif; ?>
		display: inline-block;
		margin: 0.6em 0 0 -0.4em;
		padding: 0 0.7em;
		text-decoration: none;
	}
	#desc {
	<?php
		if ( 'forget-me-not' == bouquet_current_floral_scheme() ) : ?>
		color: #506f9e;
	<?php else : ?>
		color: #b14562;
	<?php endif; ?>
		font: 13px "Sorts Mill Goudy", serif;
		font-weight: normal;
		margin: 0;
		padding: 0 0 .8em 1.9em;
		text-transform: uppercase;
	}
	<?php
		// If the user has set a custom color for the text use that
		if ( HEADER_TEXTCOLOR != get_header_textcolor() ) :
	?>
		#site-title a,
		#site-description {
			color: #<?php echo get_header_textcolor(); ?>;
		}
	<?php endif; ?>
	</style>
<?php
}
endif;

// Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
function bouquet_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'bouquet_page_menu_args' );


// Register widgetized area and update sidebar with default widgets
function bouquet_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar 1', 'bouquet' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Drag widgets here to activate the sidebar.', 'bouquet' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'bouquet_widgets_init' );

if ( ! function_exists( 'bouquet_content_nav' ) ):

// Display navigation to next/previous pages when applicable
function bouquet_content_nav( $nav_id ) {
	global $wp_query;

	?>
	<nav id="<?php echo esc_attr( $nav_id ); ?>">
		<h1 class="assistive-text section-heading"><?php _e( 'Post navigation', 'bouquet' ); ?></h1>

	<?php if ( is_single() ) : // navigation links for single posts ?>

		<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'bouquet' ) . '</span> %title' ); ?>
		<?php next_post_link( '<div class="nav-next">%link</div>', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'bouquet' ) . '</span>' ); ?>

	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>

		<?php if ( get_next_posts_link() ) : ?>
		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'bouquet' ) ); ?></div>
		<?php endif; ?>

		<?php if ( get_previous_posts_link() ) : ?>
		<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'bouquet' ) ); ?></div>
		<?php endif; ?>

	<?php endif; ?>

	</nav><!-- #<?php echo $nav_id; ?> -->
	<?php
}
endif; // bouquet_content_nav


if ( ! function_exists( 'bouquet_comment' ) ) :

// Template for comments and pingbacks. Used as a callback by wp_list_comments() for displaying the comments.
function bouquet_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'bouquet' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'bouquet' ), ' ' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer>
				<div class="comment-author vcard">
					<?php echo get_avatar( $comment, 40 ); ?>
					<?php printf( __( '%s <span class="says">says:</span>', 'bouquet' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
				</div><!-- .comment-author .vcard -->
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php _e( 'Your comment is awaiting moderation.', 'bouquet' ); ?></em>
					<br />
				<?php endif; ?>

				<div class="comment-meta commentmetadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time pubdate datetime="<?php comment_time( 'c' ); ?>">
					<?php
						/* translators: 1: date, 2: time */
						printf( __( '%1$s at %2$s', 'bouquet' ), get_comment_date(), get_comment_time() ); ?>
					</time></a>
					<?php edit_comment_link( __( 'Edit', 'bouquet' ), ' ' );
					?>
				</div><!-- .comment-meta .commentmetadata -->
			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // ends check for bouquet_comment()

if ( ! function_exists( 'bouquet_posted_on' ) ) :

// Prints HTML with meta information for the current post-date/time and author.
function bouquet_posted_on() {
	printf( '<div class="entry-date"><a href="%1$s" title="%2$s" rel="bookmark">%3$s<b>%4$s</b></a></div>',
		esc_url( get_permalink() ),
		esc_attr( get_the_date() ),
		esc_html( get_the_date( 'M' ) ),
		esc_html( get_the_date( 'j' ) )
	);
}
endif;

function bouquet_post_meta() {
	if ( is_singular() ) :
		/* translators: used between list items, there is a space after the comma */
		$category_list = get_the_category_list( __( ', ', 'bouquet' ) );

		/* translators: used between list items, there is a space after the comma */
		$tag_list = get_the_tag_list( '', ', ' );

		if ( ! bouquet_categorized_blog() ) {
			// This blog only has 1 category so we just need to worry about tags in the meta text
			if ( '' != $tag_list ) {
				$meta_text = __( 'This entry was posted on %5$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'bouquet' );
			} else {
				$meta_text = __( 'This entry was posted on %5$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'bouquet' );
			}

		} else {
			// But this blog has loads of categories so we should probably display them here
			if ( '' != $tag_list ) {
				$meta_text = __( 'This entry was posted on %5$s, in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'bouquet' );
			} else {
				$meta_text = __( 'This entry was posted on %5$s, in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'bouquet' );
			}

		} // end check for categories on this blog

		printf(
			$meta_text,
			$category_list,
			$tag_list,
			esc_url( get_permalink() ),
			the_title_attribute( 'echo=0' ),
			esc_attr( get_the_date() )
		);
	else :
		/* translators: used between list items, there is a space after the comma */
		$category_list = get_the_category_list( __( ', ', 'bouquet' ) );

		/* translators: used between list items, there is a space after the comma */
		$tag_list = get_the_tag_list( '', ', ' );

		if ( ! bouquet_categorized_blog() ) {
			// This blog only has 1 category so we just need to worry about tags in the meta text
			if ( '' != $tag_list ) {
				$meta_text = __( 'This entry was posted on %3$s and tagged %2$s.', 'bouquet' );
			} else {
				$meta_text = __( 'This entry was posted on %3$s.', 'bouquet' );
			}

		} else {
			// But this blog has loads of categories so we should probably display them here
			if ( '' != $tag_list ) {
				$meta_text = __( 'This entry was posted on %3$s, in %1$s and tagged %2$s.', 'bouquet' );
			} else {
				$meta_text = __( 'This entry was posted on %3$s, in %1$s.', 'bouquet' );
			}

		} // end check for categories on this blog

		printf(
			$meta_text,
			$category_list,
			$tag_list,
			esc_attr( get_the_date() )
		);
	endif;
}

/**
 * Add special classes to the WordPress body class.
 */
function bouquet_body_classes( $classes ) {

	// If we have one sidebar active we have secondary content
	if ( ! is_active_sidebar( 'sidebar-1' ) )
		$classes[] = 'one-column';

	return $classes;
}
add_filter( 'body_class', 'bouquet_body_classes' );


// Returns true if a blog has more than 1 category
function bouquet_categorized_blog() {
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
		// This blog has more than 1 category so bouquet_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so bouquet_categorized_blog should return false
		return false;
	}
}

// Flush out the transients used in bouquet_categorized_blog
function bouquet_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'bouquet_category_transient_flusher' );
add_action( 'save_post', 'bouquet_category_transient_flusher' );


// Filter in a link to a content ID attribute for the next/previous image links on image attachment pages
function bouquet_enhanced_image_navigation( $url ) {
	global $wp_rewrite;

	$id = (int) get_the_ID();
	$object = get_post( $id );
	if ( wp_attachment_is_image( $id ) && ( $wp_rewrite->using_permalinks() && ( $object->post_parent > 0 ) && ( $object->post_parent != $id ) ) )
		$url = $url . '#main';

	return $url;
}
add_filter( 'attachment_link', 'bouquet_enhanced_image_navigation' );

// Enqueue font styles
function bouquet_fonts() {
	$protocol = is_ssl() ? 'https' : 'http';
	wp_enqueue_style( 'sorts-mill-goudy', "$protocol://fonts.googleapis.com/css?family=Sorts+Mill+Goudy:400" );
}
add_action( 'wp_enqueue_scripts', 'bouquet_fonts' );

/**
 * Enqueue font style for the custom header admin page.
 */
function bouquet_admin_fonts( $hook_suffix ) {
	if ( 'appearance_page_custom-header' != $hook_suffix )
		return;

	$protocol = is_ssl() ? 'https' : 'http';
	wp_enqueue_style( 'sorts-mill-goudy', "$protocol://fonts.googleapis.com/css?family=Sorts+Mill+Goudy:400" );
}
add_action( 'admin_enqueue_scripts', 'bouquet_admin_fonts' );

function bouquet_header_css() {
	// Hide the theme-provided background image if the user adds a custom background image or color
	if ( '' != get_background_image() || '' != get_background_color() ) : ?>
	<style type="text/css">
		body {
			background: none;
		}
	</style>
	<?php endif;
}
add_action( 'wp_head', 'bouquet_header_css' );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @since Bouquet 1.1
 */
function bouquet_wp_title( $title, $sep ) {
	global $page, $paged;

	if ( is_feed() )
		return $title;

	// Add the blog name
	$title .= get_bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title .= " $sep $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		$title .= " $sep " . sprintf( __( 'Page %s', 'bouquet' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'bouquet_wp_title', 10, 2 );

/**
 * This theme has an options page that lets users pick layout, color scheme,
 * featured post title text and configure a twitter icon.
 */
require get_template_directory() . '/inc/theme-options.php';

/**
 * Load Jetpack compatibility file.
 */
if( class_exists( 'Jetpack' ) ) {
	require get_template_directory() . '/inc/jetpack.compat.php';
}
