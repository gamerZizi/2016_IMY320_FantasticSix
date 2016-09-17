<?php
/**
 * The template for displaying comments.
 *
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>
<div class="row">
	<div class="comments-area large-12 columns">
		<div class="comment-container">
			<div class="comment-title">
				<h3 class="handy"><?php _e( 'Comments', 'handdrawn-lite' ); ?></h3>
			</div>
			<?php if ( have_comments() ) : ?>
				<ul class="comment-list">
					<?php wp_list_comments( array( 'callback' => 'handdrawn_lite_comment' ) ); ?>					
					<?php paginate_comments_links( array('prev_text' => '<span class="screen-reader-text">'. __( "Previous", "handdrawn-lite" ) .'</span>', 'next_text' => '<span class="screen-reader-text">'. __( "Next", "handdrawn-lite" ) .'</span>') ); ?>
				</ul>
			<?php endif; // have_comments() ?>
		<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
			<p class="nocomments handy"><?php _e( 'Comments are closed.', 'handdrawn-lite' ); ?></p>
		<?php endif; ?>
			<div class="row">
			<?php $comments_args = array(
				'fields' => apply_filters( 'comment_form_default_fields', array(
											'author' => '<div class="large-6 columns"><p class="comment-form-author"><label for="author"  class="handy">'. __( 'name', 'handdrawn-lite' ) .'</label><input id="author" name="author" " type="text" value="' . esc_attr( $commenter['comment_author'] ) .'" size="30" /></p></div>',
											'email' => '<div class="large-6 columns"><p class="comment-form-email"><label for="email"  class="handy">'. __( 'email', 'handdrawn-lite' ) .'</label><input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .'" size="30" /></p></div>'
											)
							),
				'comment_notes_before' => '<p class="comment-notes handy"><span id="email-notes">'. __('Your email address will not be published. Name and email are required', 'handdrawn-lite') .'</p>',
				'title_reply_before' => '<h4 id="reply-title" class="comment-reply-title handy">',
				'title_reply_after' => '</h4>',
				'logged_in_as' => '<p class="logged-in-as handy">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'handdrawn-lite' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p>',
				'comment_field' => '<p class="comment-form-comment handy"><label for="comment" aria-required="true" class="screen-reader-text">'. __( 'Comment', 'handdrawn-lite' ) .'</label><textarea id="comment" name="comment" cols="45" rows="10" aria-required="true" required="required"></textarea></p>',
				'class_submit' => 'submit handy',
			);
			comment_form($comments_args); ?>
			</div>
		</div><!-- end .comment-container -->
	</div><!-- end .comments-area -->
</div><!-- end .row -->
