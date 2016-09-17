<?php
/**
 * The template for adding Customizer Custom Controls
 *
 * @package Catch Themes
 * @subpackage Jomsom
 * @since Jomsom 0.1
 */

//Custom control for dropdown category multiple select
class jomsom_Customize_Dropdown_Categories_Control extends WP_Customize_Control {
	public $type = 'dropdown-categories';

	public $name;

	public function render_content() {
		$dropdown = wp_dropdown_categories(
			array(
				'name'             => $this->name,
				'echo'             => 0,
				'hide_empty'       => false,
				'show_option_none' => false,
				'hide_if_empty'    => false,
			)
		);

		$dropdown = str_replace('<select', '<select multiple = "multiple" style = "height:95px;" ' . $this->get_link(), $dropdown );

		printf(
			'<label class="customize-control-select"><span class="customize-control-title">%s</span> %s</label>',
			$this->label,
			$dropdown
		);

		echo '<p class="description">'. __( 'Hold down the Ctrl (windows) / Command (Mac) button to select multiple options.', 'jomsom' ) . '</p>';
	}
}

//Custom control for any note, use label as output description
class jomsom_Note_Control extends WP_Customize_Control {
	public $type = 'description';

	public function render_content() {
		echo '<h2 class="description">' . $this->label . '</h2>';
	}
}

//Custom control for dropdown category multiple select
class jomsom_Important_Links extends WP_Customize_Control {
    public $type = 'important-links';

    public function render_content() {
    	//Add Theme instruction, Support Forum, Changelog, Donate link, Review, Facebook, Twitter, Google+, Pinterest links
        $important_links = array(
						'theme_instructions' => array(
							'link'	=> esc_url( 'http://catchthemes.com/theme-instructions/jomsom/' ),
							'text' 	=> __( 'Theme Instructions', 'jomsom' ),
							),
						'support' => array(
							'link'	=> esc_url( 'http://catchthemes.com/support/' ),
							'text' 	=> __( 'Support', 'jomsom' ),
							),
						'changelog' => array(
							'link'	=> esc_url( 'http://catchthemes.com/changelogs/jomsom-theme/' ),
							'text' 	=> __( 'Changelog', 'jomsom' ),
							),
						'facebook' => array(
							'link'	=> esc_url( 'https://www.facebook.com/catchthemes/' ),
							'text' 	=> __( 'Facebook', 'jomsom' ),
							),
						'twitter' => array(
							'link'	=> esc_url( 'https://twitter.com/catchthemes/' ),
							'text' 	=> __( 'Twitter', 'jomsom' ),
							),
						'gplus' => array(
							'link'	=> esc_url( 'https://plus.google.com/+Catchthemes/' ),
							'text' 	=> __( 'Google+', 'jomsom' ),
							),
						'pinterest' => array(
							'link'	=> esc_url( 'http://www.pinterest.com/catchthemes/' ),
							'text' 	=> __( 'Pinterest', 'jomsom' ),
							),
						);
		foreach ( $important_links as $important_link) {
			echo '<p><a target="_blank" href="' . $important_link['link'] .'" >' . esc_attr( $important_link['text'] ) .' </a></p>';
		}
    }
}