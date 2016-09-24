<?php
/**
 * This template is used to display the author page content.
 *
 * @package GivingPress Lite
 * @since GivingPress Lite 1.0
 */

?>

<h1 class="headline"><?php echo esc_html( get_the_author() ); ?></h1>

<div class="author-avatar">
	<?php echo get_avatar( get_the_author_meta( 'user_email' ), 120 ); ?>
</div>

<!-- BEGIN .author-column -->
<div class="author-column">

	<?php $website = get_the_author_meta( 'user_url' ); ?>
	<?php if ( ! empty( $website ) ) : ?>
		<h6><?php esc_html_e( 'Website:', 'givingpress-lite' ); ?></h6>
		<p><a href="<?php echo esc_url( $website ); ?>" rel="bookmark" title="<?php esc_attr_e( 'Link to author page', 'givingpress-lite' ); ?>" target="_blank"><?php echo esc_url( $website ); ?></a></p>
	<?php endif; ?>

	<?php $description = get_the_author_meta( 'description' ); ?>
	<?php if ( ! empty( $description ) ) : ?>
		<h6><?php esc_html_e( 'Profile:', 'givingpress-lite' ); ?></h6>
		<p><?php echo wp_kses_post( $description ); ?></p>
	<?php endif; ?>

	<?php if ( have_posts() ) : ?>

	<h6><?php printf( esc_html__( 'Posts by %1$s:', 'givingpress-lite' ), get_the_author() );  ?></h6>

	<ul class="author-posts">
		<?php while ( have_posts() ) : the_post(); ?>
		<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
		<?php endwhile; ?>
	</ul>

	<?php the_posts_pagination( array(
	    'prev_text' => esc_attr__( '&laquo;', 'givingpress-lite' ),
	    'next_text' => esc_attr__( '&raquo;', 'givingpress-lite' ),
	) ); ?>

	<?php else : ?>
		<p><?php esc_html_e( 'No posts by this author.', 'givingpress-lite' ); ?></p>
	<?php endif; ?>

<!-- END .author-column -->
</div>
