<?php


if ( ! defined('ABSPATH') ) {
	die('Please do not load this file directly.');
}


/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function icp_add_meta_box() {

	$screens = array( 'post', 'page' );

	foreach ( $screens as $screen ) {

		add_meta_box(
			'icpswitcher',
			__( ICP_ITEM_NAME, 'image-carousel' ),
			'icp_metabox_markup',
			$screen
		);
	}
}
add_action( 'add_meta_boxes', 'icp_add_meta_box' );

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function icp_metabox_markup( $post ) {

	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'icp_save_meta_box_data', 'icp_meta_box_nonce' );

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	$value = get_post_meta( $post->ID, 'icp_meta_options', true );

	echo '<label for="icp_activate_carousel">';
	_e( 'Activate Carousel?', 'image-carousel' );
	echo '</label>'; ?>
	        <select name="icp_activate_carousel" id="icp_activate_carousel">
            <option value="yes" <?php selected( esc_attr( $value ), 'yes' ); ?>>Yes</option>
            <option value="no" <?php selected( esc_attr( $value ), 'no' ); ?>>No</option>
            </select>
     <?php
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function icp_save_meta_box_data( $post_id ) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['icp_meta_box_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['icp_meta_box_nonce'], 'icp_save_meta_box_data' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	/* OK, it's safe for us to save the data now. */
	
	// Make sure that it is set.
	if ( ! isset( $_POST['icp_activate_carousel'] ) ) {
		return;
	}

	// Sanitize user input.
	$my_data = sanitize_text_field( $_POST['icp_activate_carousel'] );

	// Update the meta field in the database.
	update_post_meta( $post_id, 'icp_meta_options', $my_data );
}

add_action( 'save_post', 'icp_save_meta_box_data' );