<?php
/**
 * The template for displaying the Featured Content
 *
 * @package Catch Themes
 * @subpackage Jomsom
 * @since Jomsom 0.1
 */

if( !function_exists( 'jomsom_featured_content_display' ) ) :
/**
* Add Featured content.
*
* @uses action hook jomsom_before_content.
*
* @since Jomsom 0.1
*/
function jomsom_featured_content_display() {
	//jomsom_flush_transients();

	global $wp_query;

	// get data value from options
	$options 		= jomsom_get_theme_options();
	$enablecontent 	= $options['featured_content_option'];
	$contentselect 	= $options['featured_content_type'];
	$sliderselect	= $options['featured_content_slider'];

	// Front page displays in Reading Settings
	$page_on_front 	= get_option('page_on_front') ;
	$page_for_posts = get_option('page_for_posts');


	// Get Page ID outside Loop
	$page_id = $wp_query->get_queried_object_id();
	if ( $enablecontent == 'entire-site' || ( ( is_front_page() || ( is_home() && $page_for_posts != $page_id ) ) && $enablecontent == 'homepage' ) ) {
		if( ( !$jomsom_featured_content = get_transient( 'jomsom_featured_content_display' ) ) ) {
			$layouts 	 = $options['featured_content_layout'];
			$headline 	 = $options['featured_content_headline'];
			$subheadline = $options['featured_content_subheadline'];
			$imageloader = $options['featured_content_image_loader'];

			echo '<!-- refreshing cache -->';

			if ( !empty( $layouts ) ) {
				$classes = $layouts ;
			}

			if( $contentselect == 'demo-featured-content' ) {
				$classes 		.= ' demo-featured-content' ;
				$headline 		= '';
				$subheadline 	= '';
			}
			elseif ( $contentselect == 'featured-page-content' ) {
				$classes .= ' featured-page-content' ;
			}

			if ( '1' == $options ['featured_content_position'] ) {
				$classes .= ' border-top' ;
			}

			if ( $sliderselect ) {
				$classes .= ' slider-enabled' ;
			}

			$jomsom_featured_content ='
				<section id="featured-content" class="' . $classes . '">
					<div class="container">';
						if ( !empty( $headline ) || !empty( $subheadline ) ) {
							$jomsom_featured_content .='<div class="featured-heading-wrap">';
								if ( !empty( $headline ) ) {
									$jomsom_featured_content .='<h1 id="featured-heading" class="entry-title">'.  $headline .'</h1>';
								}
								if ( !empty( $subheadline ) ) {
									$jomsom_featured_content .='<p>'. $subheadline .'</p>';
								}
							$jomsom_featured_content .='</div><!-- .featured-heading-wrap -->';
						}
						$jomsom_featured_content .='
						<div class="featured-content-wrap">';

							if ( $sliderselect ) {
								$jomsom_featured_content .='
								<div class="cycle-slideshow"
								    data-cycle-log="false"
								    data-cycle-pause-on-hover="true"
								    data-cycle-swipe="true"
								    data-cycle-auto-height=container
									data-cycle-slides=".featured_content_slider_wrap"
									data-cycle-fx="scrollHorz"
									data-cycle-loader="'. esc_attr( $imageloader ) .'"
									>';

							 }

							// Select content
							if ( $contentselect == 'demo-featured-content'  && function_exists( 'jomsom_demo_content' ) ) {
								$jomsom_featured_content .= jomsom_demo_content( $options );
							}
							elseif ( $contentselect == 'featured-page-content' && function_exists( 'jomsom_page_content' ) ) {
								$jomsom_featured_content .= jomsom_page_content( $options );
							}

							if ( $sliderselect ) {
								$jomsom_featured_content .='
									<!-- empty element for pager links -->
		    					<div class="cycle-pager"></div>

	    						</div><!-- .cycle-slideshow -->';
							}

			$jomsom_featured_content .='

						</div><!-- .featured-content-wrap -->
					</div><!-- .container -->
				</section><!-- #featured-content -->';
		set_transient( 'jomsom_featured_content', $jomsom_featured_content, 86940 );
		}
	echo $jomsom_featured_content;
	}
}
endif;


if ( ! function_exists( 'jomsom_featured_content_display_position' ) ) :
/**
 * Homepage Featured Content Position
 *
 * @action jomsom_content, jomsom_after_secondary
 *
 * @since Jomsom 0.1
 */
function jomsom_featured_content_display_position() {
	// Getting data from Theme Options
	$options 		= jomsom_get_theme_options();

	if ( '1' != $options ['featured_content_position'] ) {
		add_action( 'jomsom_before_content', 'jomsom_featured_content_display', 60 );
	} else {
		add_action( 'jomsom_after_content', 'jomsom_featured_content_display', 40 );
	}

}
endif; // jomsom_featured_content_display_position
add_action( 'jomsom_before', 'jomsom_featured_content_display_position' );


if ( ! function_exists( 'jomsom_demo_content' ) ) :
/**
 * This function to display featured posts content
 *
 * @get the data value from customizer options
 *
 * @since Fullframe 1.0
 *
 */
function jomsom_demo_content( $options ) {
	$sliderselect	= $options['featured_content_slider'];

	$jomsom_demo_content = '
		<div class="featured_content_slider_wrap">
			<article id="featured-post-1" class="post hentry post-demo">
				<figure class="featured-content-image">
					<img alt="Aurora borealis" class="wp-post-image" src="'.get_template_directory_uri() . '/images/gallery/featured5-350x350.jpg" />
				</figure>
				<div class="entry-container">
					<header class="entry-header">
						<h1 class="entry-title">
							<a href="#" rel="bookmark">Aurora borealis</a>

							<p class="entry-meta">
								<span class="posted-on">
									<span class="screen-reader-text">Posted on</span>
										<time class="entry-date published">5 October, 2015</time>
									</span>
								</span>
							</p>
						</h1>
					</header>
				</div><!-- .entry-container -->
			</article>

			<article id="featured-post-2" class="post hentry post-demo">
				<figure class="featured-content-image">
					<img alt="Zenit Camera" class="wp-post-image" src="'.get_template_directory_uri() . '/images/gallery/featured6-350x350.jpg" />
				</figure>
				<div class="entry-container">
					<header class="entry-header">
						<h1 class="entry-title">
							<a href="#" rel="bookmark">Zenit Camera</a>

							<p class="entry-meta">
								<span class="posted-on">
									<span class="screen-reader-text">Posted on</span>
										<time class="entry-date published">13 November, 2015</time>
									</span>
								</span>
							</p>
						</h1>
					</header>
				</div><!-- .entry-container -->
			</article>

			<article id="featured-post-3" class="post hentry post-demo">
				<figure class="featured-content-image">
					<img alt="Domestic Cat" class="wp-post-image" src="'.get_template_directory_uri() . '/images/gallery/featured7-350x350.jpg" />
				</figure>
				<div class="entry-container">
					<header class="entry-header">
						<h1 class="entry-title">
							<a href="#" rel="bookmark">Domestic Cat</a>

							<p class="entry-meta">
								<span class="posted-on">
									<span class="screen-reader-text">Posted on</span>
										<time class="entry-date published">31 December, 2015</time>
									</span>
								</span>
							</p>
						</h1>
					</header>
				</div><!-- .entry-container -->
			</article>';

		if( 'layout-four' == $options ['featured_content_layout']) {
			$jomsom_demo_content .= '
			<article id="featured-post-4" class="post hentry post-demo">
				<figure class="featured-content-image">
					<img alt="Northern lights" class="wp-post-image" src="'.get_template_directory_uri() . '/images/gallery/featured8-350x350.jpg" />
				</figure>
				<div class="entry-container">
					<header class="entry-header">
						<h1 class="entry-title">
							<a href="#" rel="bookmark">Northern lights</a>

							<p class="entry-meta">
								<span class="posted-on">
									<span class="screen-reader-text">Posted on</span>
										<time class="entry-date published">25 January, 2016</time>
									</span>
								</span>
							</p>
						</h1>
					</header>
				</div><!-- .entry-container -->
			</article>';
		}

	$jomsom_demo_content .= '</div><!-- .featured_content_slider_wrap -->';

	if ( $sliderselect ) {
		//For Slider Effect
		$jomsom_demo_content .= '
		<div class="featured_content_slider_wrap">
			<article id="featured-post-1" class="post hentry post-demo">
				<figure class="featured-content-image">
					<img alt="Cherry Blossom" class="wp-post-image" src="'.get_template_directory_uri() . '/images/gallery/featured1-350x350.jpg" />
				</figure>
				<div class="entry-container">
					<header class="entry-header">
						<h1 class="entry-title">
							<a href="#" rel="bookmark">Cherry Blossom</a>

							<p class="entry-meta">
								<span class="posted-on">
									<span class="screen-reader-text">Posted on</span>
										<time class="entry-date published">January 7, 2015</time>
									</span>
								</span>
							</p>
						</h1>
					</header>
				</div><!-- .entry-container -->
			</article>

			<article id="featured-post-2" class="post hentry post-demo">
				<figure class="featured-content-image">
					<img alt="Alpsee" class="wp-post-image" src="'.get_template_directory_uri() . '/images/gallery/featured2-350x350.jpg" />
				</figure>
				<div class="entry-container">
					<header class="entry-header">
						<h1 class="entry-title">
							<a href="#" rel="bookmark">Alpsee</a>

							<p class="entry-meta">
								<span class="posted-on">
									<span class="screen-reader-text">Posted on</span>
										<time class="entry-date published">19 April, 2015</time>
									</span>
								</span>
							</p>
						</h1>
					</header>
				</div><!-- .entry-container -->
			</article>

			<article id="featured-post-3" class="post hentry post-demo">
				<figure class="featured-content-image">
					<img alt="Gautama Buddha" class="wp-post-image" src="'.get_template_directory_uri() . '/images/gallery/featured3-350x350.jpg" />
				</figure>
				<div class="entry-container">
					<header class="entry-header">
						<h1 class="entry-title">
							<a href="#" rel="bookmark">Gautama Buddha</a>

							<p class="entry-meta">
								<span class="posted-on">
									<span class="screen-reader-text">Posted on</span>
										<time class="entry-date published">2 August, 2015</time>
									</span>
								</span>
							</p>
						</h1>
					</header>
				</div><!-- .entry-container -->
			</article>';

		if( 'layout-four' == $options ['featured_content_layout']) {
			$jomsom_demo_content .= '
			<article id="featured-post-4" class="post hentry post-demo">
				<figure class="featured-content-image">
					<img alt="Clover Plant" class="wp-post-image" src="'.get_template_directory_uri() . '/images/gallery/featured4-350x350.jpg" />
				</figure>
				<div class="entry-container">
					<header class="entry-header">
						<h1 class="entry-title">
							<a href="#" rel="bookmark">Clover Plant</a>

							<p class="entry-meta">
								<span class="posted-on">
									<span class="screen-reader-text">Posted on</span>
										<time class="entry-date published">30 September, 2015</time>
									</span>
								</span>
							</p>
						</h1>
					</header>
				</div><!-- .entry-container -->
			</article>';
		}

		$jomsom_demo_content .= '</div><!-- .featured_content_slider_wrap -->';
	}

	return $jomsom_demo_content;
}
endif; // jomsom_demo_content


if ( ! function_exists( 'jomsom_page_content' ) ) :
/**
 * This function to display featured page content
 *
 * @param $options: jomsom_theme_options from customizer
 *
 * @since Jomsom 0.1
 */
function jomsom_page_content( $options ) {
	global $post;

	$quantity 					= $options [ 'featured_content_number' ];

	$more_link_text				= $options['excerpt_more_text'];

	$jomsom_page_content 	= '';

   	$number_of_page 			= 0; 		// for number of pages

	$page_list					= array();	// list of valid pages ids

	if( 'layout-four' == $options ['featured_content_layout']) {
		$layouts = 4;
	}
	else{
		$layouts = 3;
	}

	//Get valid pages
	for( $i = 1; $i <= $quantity; $i++ ){
		if( isset ( $options['featured_content_page_' . $i] ) && $options['featured_content_page_' . $i] > 0 ){
			$number_of_page++;

			$page_list	=	array_merge( $page_list, array( $options['featured_content_page_' . $i] ) );
		}

	}
	if ( !empty( $page_list ) && $number_of_page > 0 ) {
		$get_featured_posts = new WP_Query( array(
                    'posts_per_page' 		=> $number_of_page,
                    'post__in'       		=> $page_list,
                    'orderby'        		=> 'post__in',
                    'post_type'				=> 'page',
                ));

		$i=0;

		$jomsom_page_content = '
		<div class="featured_content_slider_wrap">';

		while ( $get_featured_posts->have_posts()) :
			$get_featured_posts->the_post();

			$i++;

			$title_attribute = the_title_attribute( array( 'before' => __( 'Permalink to:', 'jomsom' ), 'echo' => false ) );

			$excerpt = get_the_excerpt();

			$thumbnail_size = ( 3 == $layouts ) ? 'post-thumbnail':'jomsom-featured-content';

			$jomsom_page_content .= '
				<article id="featured-post-' . $i . '" class="post hentry featured-page-content">';
				if ( has_post_thumbnail() ) {
					$jomsom_page_content .= '
					<figure class="featured-homepage-image">
						<a href="' . esc_url( get_permalink() ) . '" title="' . the_title_attribute( array( 'before' => __( 'Permalink to:', 'jomsom' ), 'echo' => false ) ) . '">
						'. get_the_post_thumbnail( $post->ID, $thumbnail_size, array( 'title' => esc_attr( $title_attribute ), 'alt' => esc_attr( $title_attribute ), 'class' => 'pngfix ' . $thumbnail_size ) ) .'
						</a>
					</figure>';
				}
				else {
					//Default value if there is no first image
					$jomsom_image = '<img class="pngfix wp-post-image" src="'.get_template_directory_uri().'/images/gallery/no-featured-image-1680x720.jpg" >';

					//Get the first image in page, returns false if there is no image
					$jomsom_first_image = jomsom_get_first_image( $post->ID, $thumbnail_size, array( 'title' => esc_attr( $title_attribute ), 'alt' => esc_attr( $title_attribute ), 'class' => 'pngfix ' . $thumbnail_size ) );

					//Set value of image as first image if there is an image present in the page
					if ( '' != $jomsom_first_image ) {
						$jomsom_image =	$jomsom_first_image;
					}

					$jomsom_page_content .= '<a title="' . the_title_attribute( array( 'before' => __( 'Permalink to:', 'jomsom' ), 'echo' => false ) ) . '" href="' . esc_url( get_permalink() ) . '">
						'. $jomsom_image .'
					</a>';
				}

				if ( '1' == $options['featured_content_enable_title'] || '1' == $options['featured_content_enable_date'] || '0'!= $options['featured_content_enable_excerpt_content'] ) {
					$jomsom_page_content .= '
					<div class="entry-container">';
					if ( '1' == $options['featured_content_enable_title'] || '1' == $options['featured_content_enable_date'] ) {
						$jomsom_page_content .= '
							<header class="entry-header">';

						if ( '1' == $options['featured_content_enable_title'] ) {
							$jomsom_page_content .= '
								<h1 class="entry-title">
									<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . the_title( '','', false ) . '</a>
								</h1>';
						}

						if ( '1' == $options['featured_content_enable_date'] ) {
							$jomsom_page_content .= '
								<p class="entry-meta">';

							$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';

							$time_string = sprintf(
								$time_string,
								esc_attr( get_the_date( 'c' ) ),
								esc_html( get_the_date() )
							);

							$jomsom_page_content .= '
									<span class="posted-on">
										<span class="screen-reader-text">' . _x( '<span class="screen-reader-text">Posted on</span>', 'Used before publish date.', 'jomsom' ) . '</span>' . $time_string . '
										</span>
									</span>
								</p><!-- .entry-meta -->';
						}

						$jomsom_page_content .= '
							</header>';
					}

					if ( '1' == $options['featured_content_enable_excerpt_content'] ) {
						//Show Excerpt
						$jomsom_page_content .= '<div class="entry-excerpt"><p>' . $excerpt . '</p></div><!-- .entry-excerpt -->';
					}
					elseif ( '2' == $options['featured_content_enable_excerpt_content'] ) {
						//Show Content
						$content = apply_filters( 'the_content', get_the_content() );
						$content = str_replace( ']]>', ']]&gt;', $content );
						$jomsom_page_content .= '<div class="entry-content">' . $content . '</div><!-- .entry-content -->';
					}
				}
				$jomsom_page_content .= '
				</article><!-- .featured-page-'. $i .' -->';

				if ( 0 == ( $i % $layouts ) && $i < $number_of_page ) {
					//end and start featured_content_slider_wrap div based on logic
					$jomsom_page_content .= '
				</div><!-- .featured_content_slider_wrap -->

				<div class="featured_content_slider_wrap">';
				}
		endwhile;

		wp_reset_query();

		$jomsom_page_content .= '</div><!-- .featured_content_slider_wrap -->';
	}

	return $jomsom_page_content;
}
endif; // jomsom_page_content