<?php
/**
 * The template for adding Custom Sidebars and Widgets
 *
 * @package Catch Themes
 * @subpackage Jomsom
 * @since Jomsom 0.1
 */

/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since Jomsom 0.1
 */
function jomsom_widgets_init() {
	//Primary Sidebar
	register_sidebar( array(
		'name'          => __( 'Primary Sidebar', 'jomsom' ),
		'id'            => 'primary-sidebar',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside><!-- .widget -->',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
		'description'	=> __( 'This is the primary sidebar if you are using a two column site layout option.', 'jomsom' ),
	) );

	$footer_sidebar_number = 3; //Number of footer sidebars

	for( $i=1; $i <= $footer_sidebar_number; $i++ ) {
		register_sidebar( array(
			'name'          => sprintf( __( 'Footer Area %d', 'jomsom' ), $i ),
			'id'            => sprintf( 'footer-%d', $i ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside><!-- .widget -->',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
			'description'	=> sprintf( __( 'Footer %d widget area.', 'jomsom' ), $i )
			)
		);
	}
}
add_action( 'widgets_init', 'jomsom_widgets_init' );


/**
 * Adds jomsomSocialIcons widget.
 *
 * @since Jomsom 0.1
 */
class Jomsom_social_icons_widget extends WP_Widget {
	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'jomsom_social_icons', // Base ID
			__( 'CT: Social Icons', 'jomsom' ), // Name
			array( 'description' => __( 'Use this widget to add Social Icons as a widget. ', 'jomsom' ) ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : '';

		echo $args['before_widget'];

		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];

		echo jomsom_get_social_icons();

		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		}
		else {
			$title = '';
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title (optional):', 'jomsom' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
        <?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
}


/**
 * Register Widgets
 *
 * @since Jomsom 0.1
 */
function jomsom_register_widgets() {
    register_widget( 'Jomsom_social_icons_widget' );
}
add_action( 'widgets_init', 'jomsom_register_widgets' );