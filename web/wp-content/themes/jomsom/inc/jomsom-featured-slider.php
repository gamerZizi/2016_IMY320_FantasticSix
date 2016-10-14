<?php
/**
 * The template for displaying the Slider
 *
 * @package Catch Themes
 * @subpackage Jomsom
 * @since Jomsom 0.1
 */

if( !function_exists( 'jomsom_featured_slider' ) ) :
/**
 * Add slider.
 *
 * @uses action hook jomsom_before_content.
 *
 * @since Jomsom 0.1
 */
function jomsom_featured_slider() {
	global $wp_query;
	//jomsom_flush_transients();
	// get data value from options
	$options 		= jomsom_get_theme_options();
	$enableslider 	= $options['featured_slider_option'];
	$sliderselect 	= $options['featured_slider_type'];
	$imageloader	= isset ( $options['featured_slider_image_loader'] ) ? $options['featured_slider_image_loader'] : 'true';

	// Get Page ID outside Loop
	$page_id = $wp_query->get_queried_object_id();

	// Front page displays in Reading Settings
	$page_on_front = get_option('page_on_front') ;
	$page_for_posts = get_option('page_for_posts');

	if ( $enableslider == 'entire-site' || ( ( is_front_page() || ( is_home() && $page_for_posts != $page_id ) ) && $enableslider == 'homepage' ) ) {
		if( ( !$jomsom_featured_slider = get_transient( 'jomsom_featured_slider' ) ) ) {
			echo '<!-- refreshing cache -->';

			$jomsom_featured_slider = '
				<section id="feature-slider">
					<div class="container">
						<div class="cycle-slideshow"
						    data-cycle-log="false"
						    data-cycle-pause-on-hover="true"
						    data-cycle-swipe="true"
						    data-cycle-auto-height=container
						    data-cycle-fx="'. esc_attr( $options['featured_slide_transition_effect'] ) .'"
							data-cycle-speed="'. esc_attr( $options['featured_slide_transition_length'] ) * 1000 .'"
							data-cycle-timeout="'. esc_attr( $options['featured_slide_transition_delay'] ) * 1000 .'"
							data-cycle-loader="'. esc_attr( $imageloader ) .'"
							data-cycle-slides="> article"
							>

						    <!-- prev/next links -->
						    <div class="cycle-prev fa fa-chevron-left"><span class="screen-reader-text">' . __( 'Previous', 'jomsom' ) . '</span></div>
						    <div class="cycle-next fa fa-chevron-right"><span class="screen-reader-text">' . __( 'Next', 'jomsom' ) . '</span></div>

						    <!-- empty element for pager links -->
	    					<div class="cycle-pager"></div>';

							// Select Slider
							if ( $sliderselect == 'demo-featured-slider' && function_exists( 'jomsom_demo_slider' ) ) {
								$jomsom_featured_slider .=  jomsom_demo_slider( $options );
							}
							elseif ( $sliderselect == 'featured-page-slider' && function_exists( 'jomsom_page_slider' ) ) {
								$jomsom_featured_slider .=  jomsom_page_slider( $options );
							}

			$jomsom_featured_slider .= '
						</div><!-- .cycle-slideshow -->
					</div><!-- .container -->
				</section><!-- #feature-slider -->';

			set_transient( 'jomsom_featured_slider', $jomsom_featured_slider, 86940 );
		}
		echo $jomsom_featured_slider;
	}
}
endif;
add_action( 'jomsom_before_content', 'jomsom_featured_slider', 10 );


if ( ! function_exists( 'jomsom_demo_slider' ) ) :
/**
 * This function to display featured posts slider
 *
 * @get the data value from customizer options
 *
 * @since Jomsom 0.1
 *
 */
function jomsom_demo_slider( $options ) {
	$jomsom_demo_slider ='
								<article class="post hentry slides demo-image displayblock">
									<figure class="slider-image">
										<a title="Slider Image 1" href="'. esc_url( home_url( '/' ) ) .'">
											<img src="'.get_template_directory_uri().'/images/gallery/slider1-1680x720.jpg" class="wp-post-image" alt="Slider Image 1" title="Slider Image 1">
										</a>
									</figure>
									<div class="entry-container">
										<header class="entry-header">
											<h1 class="entry-title">
												<a title="Slider Image 1" href="#"><span>Slider Image 1</span></a>
											</h1>
											<div class="screen-reader-text"><span class="post-time">Posted on <time pubdate="" datetime="2014-08-16T10:56:23+00:00" class="entry-date updated">16 August, 2014</time></span><span class="post-author">By <span class="author vcard"><a rel="author" title="View all posts by Author" href="#" class="url fn n">Author</a></span></span></div>
										</header>
										<div class="entry-content">
											<p>Slider Image 1 Content</p>
										</div>
									</div>
								</article><!-- .slides -->

								<article class="post hentry slides demo-image displaynone">
									<figure class="slider-image">
										<a title="Slider Image 2" href="'. esc_url( home_url( '/' ) ) .'">
											<img src="'. get_template_directory_uri() . '/images/gallery/slider2-1680x720.jpg" class="wp-post-image" alt="Slider Image 2" title="Slider Image 2">
										</a>
									</figure>
									<div class="entry-container">
										<header class="entry-header">
											<h1 class="entry-title">
												<a title="Slider Image 2" href="#"><span>Slider Image 2</span></a>
											</h1>
											<div class="screen-reader-text"><span class="post-time">Posted on <time pubdate="" datetime="2014-08-16T10:56:23+00:00" class="entry-date updated">16 August, 2014</time></span><span class="post-author">By <span class="author vcard"><a rel="author" title="View all posts by Author" href="#" class="url fn n">Author</a></span></span></div>
										</header>
										<div class="entry-content">
											<p>Slider Image 2 Content</p>
										</div>
									</div>
								</article><!-- .slides --> ';
	return $jomsom_demo_slider;
}
endif; // jomsom_demo_slider


if ( ! function_exists( 'jomsom_page_slider' ) ) :
/**
 * This function to display featured page slider
 *
 * @param $options: jomsom_theme_options from customizer
 *
 * @since Jomsom 0.1
 */
function jomsom_page_slider( $options ) {
	$quantity		= $options['featured_slide_number'];
	$more_link_text	=	$options['excerpt_more_text'];

    global $post;

    $jomsom_page_slider = '';
    $number_of_page 		= 0; 		// for number of pages
	$page_list				= array();	// list of valid page ids

	//Get number of valid pages
	for( $i = 1; $i <= $quantity; $i++ ){
		if( isset ( $options['featured_slider_page_' . $i] ) && $options['featured_slider_page_' . $i] > 0 ){
			$number_of_page++;

			$page_list	=	array_merge( $page_list, array( $options['featured_slider_page_' . $i] ) );
		}

	}

	if ( !empty( $page_list ) && $number_of_page > 0 ) {
		$get_featured_posts = new WP_Query( array(
			'posts_per_page'	=> $quantity,
			'post_type'			=> 'page',
			'post__in'			=> $page_list,
			'orderby' 			=> 'post__in'
		));
		$i=0;

		while ( $get_featured_posts->have_posts()) : $get_featured_posts->the_post(); $i++;
			$title_attribute = the_title_attribute( array( 'before' => __( 'Permalink to:', 'jomsom' ), 'echo' => false ) );
			$excerpt = get_the_excerpt();
			if ( $i == 1 ) { $classes = 'page pageid-'.$post->ID.' hentry slides displayblock'; } else { $classes = 'page pageid-'.$post->ID.' hentry slides displaynone'; }
			$jomsom_page_slider .= '
			<article class="'.$classes.'">
				<figure class="slider-image">';
				if ( has_post_thumbnail() ) {
					$jomsom_page_slider .= '<a title="' . the_title_attribute( array( 'before' => __( 'Permalink to:', 'jomsom' ), 'echo' => false ) ) . '" href="' . esc_url( get_permalink() ) . '">
						'. get_the_post_thumbnail( $post->ID, 'jomsom_slider', array( 'title' => esc_attr( $title_attribute ), 'alt' => esc_attr( $title_attribute ), 'class'	=> 'pngfix' ) ).'
					</a>';
				}
				else {
					//Default value if there is no first image
					$jomsom_image = '<img class="pngfix wp-post-image" src="'.get_template_directory_uri().'/images/gallery/no-featured-image-1680x720.jpg" >';

					//Get the first image in page, returns false if there is no image
					$jomsom_first_image = jomsom_get_first_image( $post->ID, 'jomsom-slider', array( 'title' => esc_attr( $title_attribute ), 'alt' => esc_attr( $title_attribute ), 'class' => 'pngfix' ) );

					//Set value of image as first image if there is an image present in the page
					if ( '' != $jomsom_first_image ) {
						$jomsom_image =	$jomsom_first_image;
					}

					$jomsom_page_slider .= '<a title="' . the_title_attribute( array( 'before' => __( 'Permalink to:', 'jomsom' ), 'echo' => false ) ) . '" href="' . esc_url( get_permalink() ) . '">
						'. $jomsom_image .'
					</a>';
				}

				$jomsom_page_slider .= '
				</figure><!-- .slider-image -->
				<div class="entry-container">
					<header class="entry-header">
						<h1 class="entry-title">
							<a title="' . the_title_attribute( array( 'before' => __( 'Permalink to:', 'jomsom' ), 'echo' => false ) ) . '" href="' . esc_url( get_permalink() ) . '">'.the_title( '<span>','</span>', false ).'</a>
						</h1>
						<div class="screen-reader-text">'.jomsom_page_post_meta().'</div>
					</header>';
					if( $excerpt !='') {
						$jomsom_page_slider .= '<div class="entry-content">'. $excerpt.'</div>';
					}
					$jomsom_page_slider .= '
				</div><!-- .entry-container -->
			</article><!-- .slides -->';
		endwhile;

		wp_reset_query();
  	}
	return $jomsom_page_slider;
}
endif; // jomsom_page_slider