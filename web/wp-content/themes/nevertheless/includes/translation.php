<?php
/**
 * Make Framework Translatable
**/

/* Load Text Domain */
load_theme_textdomain( 'nevertheless', get_template_directory() . '/assets/languages' );

/* Make all string in the framework translatable. */
$texts = array(

	/* functions/template/accessibility.php */
	'skip_to_content'             => _x( 'Skip to content', 'accessibility', 'nevertheless' ),

	/* functions/template/general.php */
	'next_posts'                  => _x( 'Next', 'pagination', 'nevertheless' ),
	'previous_posts'              => _x( 'Previous', 'pagination', 'nevertheless' ),

	/* functions/template/menu.php */
	'menu_search_placeholder'     => _x( 'Search&hellip;', 'nav menu', 'nevertheless' ),
	'menu_search_button'          => _x( 'Search', 'nav menu', 'nevertheless' ),
	'menu_search_form_toggle'     => _x( 'Expand Search Form', 'nav menu', 'nevertheless' ),
	'menu_default_home'           => _x( 'Home', 'nav menu', 'nevertheless' ),

	/* functions/template/entry.php */
	'error_title'                 => _x( '404 Not Found', 'entry', 'nevertheless' ),
	'error_message'               => _x( 'Apologies, but no entries were found.', 'entry', 'nevertheless' ),
	'next_post'                   => _x( 'Next', 'entry', 'nevertheless' ),
	'previous_post'               => _x( 'Previous', 'entry', 'nevertheless' ),

	/* functions/template/comment.php */
	'next_comment'                => _x( 'Next', 'comment', 'nevertheless' ),
	'previous_comment'            => _x( 'Previous', 'comment', 'nevertheless' ),
	'comments_closed_pings_open'  => _x( 'Comments are closed, but trackbacks and pingbacks are open.', 'comment', 'nevertheless' ),
	'comments_closed'             => _x( 'Comments are closed.', 'comment', 'nevertheless' ),

	/* functions/setup.php */
	'untitled'                    => _x( '(Untitled)', 'entry', 'nevertheless' ),
	'read_more'                   => _x( 'Read More', 'entry', 'nevertheless' ),
	'search_title_prefix'         => _x( 'Search:', 'archive title', 'nevertheless' ),
	'comment_moderation_message'  => _x( 'Your comment is awaiting moderation.', 'comment', 'nevertheless' ),

);

/* Add text to tamatebako */
tamatebako_load_strings( $texts );