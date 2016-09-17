<?php
/**
 * This template displays the page loop.
 *
 * @package GivingPress Lite
 * @since GivingPress Lite 1.0
 */

?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<?php if ( ! has_post_thumbnail() ) { ?>
	<h1 class="headline"><?php the_title(); ?></h1>
<?php } ?>

<?php the_content( esc_html__( 'Read More', 'givingpress-lite' ) ); ?>

<?php wp_link_pages(array(
	'before' => '<p class="page-links"><span class="link-label">' . esc_html__( 'Pages:', 'givingpress-lite' ) . '</span>',
	'after' => '</p>',
	'link_before' => '<span>',
	'link_after' => '</span>',
	'next_or_number' => 'next_and_number',
	'nextpagelink' => esc_html__( 'Next', 'givingpress-lite' ),
	'previouspagelink' => esc_html__( 'Previous', 'givingpress-lite' ),
	'pagelink' => '%',
	'echo' => 1,
	)
); ?>

<?php edit_post_link( esc_html__( '(Edit)', 'givingpress-lite' ), '', '' ); ?>

<?php if ( comments_open() || '0' != get_comments_number() ) { comments_template(); } ?>

<div class="clear"></div>

<?php endwhile; else : ?>

	<?php get_template_part( 'content/content', 'none' ); ?>

<?php endif; ?>
