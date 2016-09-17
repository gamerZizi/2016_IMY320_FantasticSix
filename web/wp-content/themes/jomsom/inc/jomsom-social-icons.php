<?php
/**
 * The template for displaying Social Icons
 *
 * @package Catch Themes
 * @subpackage Jomsom
 * @since Jomsom 0.1
 */

if ( ! function_exists( 'jomsom_get_social_icons' ) ) :
/**
 * Generate social icons.
 *
 * @since Jomsom 0.1
 */
function jomsom_get_social_icons(){
	if( ( !$output = get_transient( 'jomsom_social_icons' ) ) ) {
		$output	= '';

		$options 	= jomsom_get_theme_options(); // Get options

		//Pre defined Social Icons Link Start
		$pre_def_social_icons 	=	jomsom_get_social_icons_list();

		foreach ( $pre_def_social_icons as $key => $item ) {
			if( isset( $options[ $key ] ) && '' != $options[ $key ] ) {
				$value = $options[ $key ];

				if ( 'email_link' == $key  ) {
					$output .= '<li><a class="fa fa-envelope-o" title="'. esc_attr__( 'Email', 'jomsom') . '" href="mailto:'. antispambot( sanitize_email( $value ) ) .'"><span class="screen-reader-text">'. __( 'Email', 'jomsom') . '</span></a></li>';
				}
				else if ( 'skype_link' == $key  ) {
					$output .= '<li><a class="fa fa-'. sanitize_key( $item['fa_class'] ) .'" title="'. esc_attr( $item['label'] ) . '" href="'. esc_attr( $value ) .'"><span class="screen-reader-text">'. esc_attr( $item['label'] ) . '</span></a></li>';
				}
				else if ( 'phone_link' == $key || 'handset_link' == $key ) {
					$output .= '<li><a class="fa fa-'. sanitize_key( $item['fa_class'] ) .'" title="'. esc_attr( $item['label'] ) . '" href="tel:' . preg_replace( '/\s+/', '', esc_attr( $value ) ) . '"><span class="screen-reader-text">'. esc_attr( $item['label'] ) . '</span></a></li>';
				}
				else {
					$output .= '<li><a class="fa fa-'. sanitize_key( $item['fa_class'] ) .'" target="_blank" title="'. esc_attr( $item['label'] )  .'" href="'. esc_url( $value ) .'"><span class="screen-reader-text">'. esc_attr( $item['label'] ) .'</span></a></li>';
				}
			}
		}
		//Pre defined Social Icons Link End

		set_transient( 'jomsom_social_icons', $output, 86940 );
	}

	if ( '' != $output ) {
			$output = '<ul class="social-networks">' . $output . '</ul>';
		}

	$jomsom_social_icons = $output;

	return $output;
} // jomsom_get_social_icons
endif;