<?php
/**
 * This template is used to display the homepage bottom section.
 *
 * @package Giving
 * @since Giving 1.0
 */

?>

<!-- BEGIN .content -->
<div class="content no-bg">

	<!-- BEGIN .postarea -->
	<div class="postarea <?php if ( has_post_thumbnail() ) { ?>text-white<?php } ?>">

		<h2 class="headline text-center"><?php the_title(); ?></h2>

		<?php if ( ! empty( $post->post_excerpt ) ) { ?>

			<?php the_excerpt(); ?>

		<?php } else { ?>

			<?php the_content( esc_html__( 'Read More', 'givingpress-lite' ) ); ?>

		<?php } ?>

	<!-- END .postarea -->
	</div>

<!-- END .content -->
</div>
