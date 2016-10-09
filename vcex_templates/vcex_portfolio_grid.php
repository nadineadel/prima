<?php
/**
 * Visual Composer Portfolio Grid
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 3.5.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Not needed in admin ever
if ( is_admin() ) {
    return;
}

// Required VC functions
if ( ! function_exists( 'vc_map_get_attributes' ) || ! function_exists( 'vc_shortcode_custom_css_class' ) ) {
	vcex_function_needed_notice();
	return;
}

// Define output var
$output = '';

// Deprecated Attributes
if ( ! empty( $atts['term_slug'] ) && empty( $atts['include_categories']) ) {
	$atts['include_categories'] = $atts['term_slug'];
}

// Get and extract shortcode attributes
$atts = vc_map_get_attributes( 'vcex_portfolio_grid', $atts );
extract( $atts );

// Define user-generated attributes
$atts['post_type'] = 'portfolio';
$atts['taxonomy']  = 'portfolio_category';
$atts['tax_query'] = '';

// Build the WordPress query
$wpex_query = vcex_build_wp_query( $atts );

// Output posts
if ( $wpex_query->have_posts() ) :

	// IMPORTANT: Fallback required from VC update when params are defined as empty
	// AKA - set things to enabled by default
	$entry_media = ( ! $entry_media ) ? 'true' : $entry_media;
	$title       = ( ! $title ) ? 'true' : $title;
	$excerpt     = ( ! $excerpt ) ? 'true' : $excerpt;
	$read_more   = ( ! $read_more ) ? 'true' : $read_more;

	// Sanitize data & declare main variables
	$inline_js          = array();
	$grid_data          = array();
	$wrap_classes       = array( 'vcex-portfolio-grid-wrap', 'wpex-clr' );
	$grid_classes       = array( 'wpex-row', 'vcex-portfolio-grid', 'wpex-clr', 'entries' );
	$is_isotope         = false;
	$excerpt_length     = $excerpt_length ? $excerpt_length : '30';
	$css_animation      = vcex_get_css_animation( $css_animation );
	$css_animation      = ( 'true' == $filter ) ? false : $css_animation;
	$equal_heights_grid = ( 'true' == $equal_heights_grid && $columns > '1' ) ? true : false;
	$overlay_style      = $overlay_style ? $overlay_style : 'none';
	$title_tag          = apply_filters( 'vcex_grid_default_title_tag', $title_tag, $atts );
	$title_tag          = $title_tag ? $title_tag : 'h2';

	// Load lightbox scripts
	if ( 'lightbox' == $thumb_link || 'lightbox_gallery' == $thumb_link ) {
		wpex_enqueue_ilightbox_skin( $lightbox_skin );
		if ( 'lightbox' == $thumb_link ) {
			$inline_js[] = 'ilightbox';
		}
	}

	// Enable Isotope
	if ( 'true' == $filter || 'masonry' == $grid_style || 'no_margins' == $grid_style ) {
		$is_isotope = true;
	}

	// No need for masonry if not enough columns and filter is disabled
	if ( 'true' != $filter && 'masonry' == $grid_style ) {
		$post_count = count( $wpex_query->posts );
		if ( $post_count <= $columns ) {
			$is_isotope = false;
		}
	}

	// Get filter taxonomy
	if ( 'true' == $filter ) {
		$filter_taxonomy = apply_filters( 'vcex_filter_taxonomy', $atts['taxonomy'], $atts );
		$filter_taxonomy = taxonomy_exists( $filter_taxonomy ) ? $filter_taxonomy : '';
		if ( $filter_taxonomy ) {
			$atts['filter_taxonomy'] = $filter_taxonomy; // Add to array to pass on to vcex_grid_filter_args()
		}
	} else {
		$filter_taxonomy = null;
	}

	// Get filter terms
	if ( $filter_taxonomy ) {

		// Get filter terms
		$filter_terms = get_terms( $filter_taxonomy, vcex_grid_filter_args( $atts, $wpex_query ) );

		// Make sure we have terms before doing things
		if ( $filter_terms ) {

			// Get term ids
			$filter_terms_ids = wp_list_pluck( $filter_terms, 'term_id' );

			// Check url for filter cat
			$filter_url_param = vcex_grid_filter_url_param();
			if ( isset( $_GET[$filter_url_param] ) ) {
				$filter_active_category = esc_html( $_GET[$filter_url_param] );
				if ( ! is_numeric( $filter_active_category ) ) {
					$get_term = get_term_by( 'name', $filter_active_category, $filter_taxonomy );
					if ( $get_term ) {
						$filter_active_category = $get_term->term_id;
					}
				}
			}

			// Check if filter active cat exists on current page
			$filter_has_active_cat = in_array( $filter_active_category, $filter_terms_ids ) ? true : false;

			// Add show on load animation when active filter is enabled to prevent double animation
			if ( $filter_has_active_cat ) {
				$grid_classes[] = 'wpex-show-on-load';
			}

		} else {
			$filter = false; // no terms
		}

	}

	// Wrap classes
	if ( $visibility ) {
		$wrap_classes[] = $visibility;
	}
	if ( $classes ) {
		$wrap_classes[] = vcex_get_extra_class( $classes );
	}

	// Main grid classes
	if ( $columns_gap ) {
		$grid_classes[] = 'gap-'. $columns_gap;
	}
	if ( $equal_heights_grid ) {
		$grid_classes[] = 'match-height-grid';
		$inline_js[] = 'equal_heights'; // Must be before isotope JS
	}
	if ( $is_isotope ) {
		$grid_classes[] = 'vcex-isotope-grid';
		$inline_js[] = 'isotope';
	}
	if ( 'no_margins' == $grid_style ) {
		$grid_classes[] = 'vcex-no-margin-grid';
	}
	if ( 'left_thumbs' == $single_column_style ) {
		$grid_classes[] = 'left-thumbs';
	}
	if ( 'lightbox' == $thumb_link || 'lightbox_gallery' == $thumb_link ) {
		if ( 'true' == $thumb_lightbox_gallery ) {
			$grid_classes[] = ' lightbox-group';
			if ( $lightbox_skin ) {
				$grid_data[] = 'data-skin="'. $lightbox_skin .'"';
			}
			$lightbox_single_class = ' wpex-lightbox-group-item';
		} else {
			$lightbox_single_class = ' wpex-lightbox';
		}
	}

	// Grid data attributes
	if ( 'true' == $filter ) {
		if ( 'fitRows' == $masonry_layout_mode ) {
			$grid_data[] = 'data-layout-mode="fitRows"';
		}
		if ( $filter_speed ) {
			$grid_data[] = 'data-transition-duration="'. $filter_speed .'"';
		}
		if ( ! empty( $filter_has_active_cat ) ) {
			$grid_data[] = 'data-filter=".cat-'. $filter_active_category .'"';
		}
	} else {
		$grid_data[] = 'data-transition-duration="0.0"';
	}

	// Entry inner classes
	$inner_classes = 'portfolio-entry-inner entry-inner wpex-clr';
	if ( $entry_css ) {
		$inner_classes .= ' '. vc_shortcode_custom_css_class( $entry_css );;
	}

	// Media classes
	if ( 'true' == $entry_media ) {
		$media_classes = array( 'portfolio-entry-media', 'entry-media', 'wpex-clr' );
		if ( $img_filter ) {
			$media_classes[] = wpex_image_filter_class( $img_filter );
		}
		if ( $img_hover_style ) {
			$media_classes[] = wpex_image_hover_classes( $img_hover_style );
		}
		if ( 'none' != $overlay_style ) {
			$media_classes[] = wpex_overlay_classes( $overlay_style );
		}
		$media_classes = implode( ' ', $media_classes );
	}

	// Content Design
	$content_style = array(
		'color'      => $content_color,
		'opacity'    => $content_opacity,
		'text_align' => $content_alignment,
	);
	if ( ! $content_css ) {
		if ( isset( $content_background ) ) {
			$content_style['background'] = $content_background;
		}
		if ( isset( $content_padding ) ) {
			$content_style['padding'] = $content_padding;
		}
		if ( isset( $content_margin ) ) {
			$content_style['margin'] = $content_margin;
		}
		if ( isset( $content_border ) ) {
			$content_style['border'] = $content_border;
		}
	} else {
		$content_css = vc_shortcode_custom_css_class( $content_css );
	}
	$content_style = vcex_inline_style( $content_style );

	// Heading style
	if ( 'true' == $title ) {

		// Heading Design
		$heading_style = vcex_inline_style( array(
			'margin'         => $content_heading_margin,
			'font_size'      => $content_heading_size,
			'color'          => $content_heading_color,
			'font_weight'    => $content_heading_weight,
			'text_transform' => $content_heading_transform,
			'line_height'    => $content_heading_line_height,
		) );

		// Heading Link style
		$heading_link_style = vcex_inline_style( array(
			'color' => $content_heading_color,
		) );

	}

	// Categories style
	if ( 'true' == $show_categories ) {
		$categories_style = vcex_inline_style( array(
			'margin'    => $categories_margin,
			'font_size' => $categories_font_size,
			'color'     => $categories_color,
		) );
		$categories_classes = 'portfolio-entry-categories entry-categories wpex-clr';
		if ( $categories_color ) {
			$categories_classes .= ' wpex-child-inherit-color';
		}
	}

	// Excerpt style
	if ( 'true' == $excerpt ) {

		$excerpt_style = vcex_inline_style( array(
			'font_size' => $content_font_size,
		) );

	}

	// Readmore design
	if ( 'true' == $read_more ) {

		// Read more text
		$read_more_text = $read_more_text ? $read_more_text : esc_html__( 'read more', 'total' );

		// Readmore classes
		$readmore_classes = wpex_get_button_classes( $readmore_style, $readmore_style_color );
		if ( $readmore_hover_color || $readmore_hover_background ) {
			$readmore_classes .= ' wpex-data-hover';
			$inline_js[] = 'data_hover';
		}

		// Readmore style
		$readmore_style = vcex_inline_style( array(
			'background'    => $readmore_background,
			'color'         => $readmore_color,
			'font_size'     => $readmore_size,
			'padding'       => $readmore_padding,
			'border_radius' => $readmore_border_radius,
			'margin'        => $readmore_margin,
		) );

		// Readmore data
		$readmore_data = array();
		if ( $readmore_hover_color ) {
			$readmore_data[] = 'data-hover-color="'. $readmore_hover_color .'"';
		}
		if ( $readmore_hover_background ) {
			$readmore_data[] = 'data-hover-background="'. $readmore_hover_background .'"';
		}
		$readmore_data = implode( ' ', $readmore_data );

	}

	// Apply filters
	$wrap_classes = apply_filters( 'vcex_portfolio_grid_wrap_classes', $wrap_classes );
	$grid_classes = apply_filters( 'vcex_portfolio_grid_classes', $grid_classes );
	$grid_data    = apply_filters( 'vcex_portfolio_grid_data_attr', $grid_data );

	// Convert arrays into strings
	$wrap_classes = implode( ' ', $wrap_classes );
	$grid_classes = implode( ' ', $grid_classes );
	$grid_data    = $grid_data ? ' '. implode( ' ', $grid_data ) : '';

	// Inline JS for front-end editor
	if ( $inline_js ) {
		vcex_inline_js( $inline_js );
	}

	// Begin output
	$output .= '<div class="'. $wrap_classes .'"'. vcex_get_unique_id( $unique_id ) .'>';
	
		// Display filter links
		if ( 'true' == $filter && ! empty( $filter_terms ) ) :

			// Sanitize all text
			$all_text = $all_text ? $all_text : esc_html__( 'All', 'total' );

			// Filter button classes
			$filter_button_classes = wpex_get_button_classes( $filter_button_style, $filter_button_color );

			// Filter font size
			$filter_style = vcex_inline_style( array(
				'font_size' => $filter_font_size,
			) );

			$filter_classes = 'vcex-portfolio-filter vcex-filter-links clr';
			if ( 'yes' == $center_filter ) {
				$filter_classes .= ' center';
			}

			$output .= '<ul class="'. $filter_classes .'"'. $filter_style .'>';
				
				if ( 'true' == $filter_all_link ) {

					$output .= '<li';
						if ( ! $filter_has_active_cat ) {
							$output .= ' class="active"';
						}
					$output .= '>';

						$output .= '<a href="#" data-filter="*" class="'. $filter_button_classes .'"><span>'. $all_text .'</span></a>';

					$output .= '</li>';

				}

				foreach ( $filter_terms as $term ) :

					$output .= '<li class="filter-cat-'. $term->term_id;
						if ( $filter_active_category == $term->term_id ) {
							$output .= ' active';
						}
					$output .= '">';

					$output .= '<a href="#" data-filter=".cat-'. $term->term_id .'" class="'. $filter_button_classes .'">';
						$output .= $term->name;
					$output .= '</a></li>';

				endforeach;

				if ( $vcex_after_grid_filter = apply_filters( 'vcex_after_grid_filter', '', $atts ) ) { 
					$output .= $vcex_after_grid_filter;
				}

			$output .= '</ul>';

		endif; // End filter

		$output .= '<div class="'. $grid_classes .'"'. $grid_data .'>';

			// Define counter var to clear floats
			$count = 0;

			// Start loop
			while ( $wpex_query->have_posts() ) :

				// Get post from query
				$wpex_query->the_post();

				// Post Data
				$atts['post_id']           = get_the_ID();
				$atts['post_permalink']    = wpex_get_permalink( $atts['post_id'] );
				$atts['post_title']        = get_the_title();
				$atts['post_esc_title']    = wpex_get_esc_title();
				$atts['post_video']        = wpex_get_post_video( $atts['post_id'] );
				$atts['post_video_output'] = $atts['post_video'] ? wpex_get_post_video_html( $atts['post_video'] ) : '';
				$atts['post_excerpt']      = '';

				// Post Excerpt
				if ( 'true' == $excerpt || 'true' == $thumb_lightbox_caption ) {
					$atts['post_excerpt'] = wpex_get_excerpt( array(
						'length' => intval( $excerpt_length ),
					) );
				}

				// Does entry have details?
				if ( 'true' == $title
						|| 'true' == $show_categories
						|| ( 'true' == $excerpt && $atts['post_excerpt'] )
						|| 'true' == $read_more
				) {
					$entry_has_details = true;
				} else {
					$entry_has_details = false;
				}

				// Add to the counter var
				$count++;

				// Add classes to the entries
				$entry_classes = array( 'portfolio-entry' );
				if ( $entry_has_details ) {
					$entry_classes[] = 'entry-has-details';
				}
				$entry_classes[] = 'span_1_of_'. $columns;
				if ( 'false' == $columns_responsive ) {
					$entry_classes[] = 'nr-col';
				} else {
					$entry_classes[] = 'col';
				}
				if ( $count ) {
					$entry_classes[] = 'col-'. $count;
				}
				if ( $css_animation ) {
					$entry_classes[] = $css_animation;
				}
				if ( $is_isotope ) {
					$entry_classes[] = 'vcex-isotope-entry';
				}
				if ( 'no_margins' == $grid_style ) {
					$entry_classes[] = 'vcex-no-margin-entry';
				}
				if ( $filter_taxonomy ) {
					if ( $post_terms = get_the_terms( $atts['post_id'], $filter_taxonomy ) ) {
						foreach ( $post_terms as $post_term ) {
							$entry_classes[] = 'cat-'. $post_term->term_id;
						}
					}
				}

				$output .= '<div '. wpex_get_post_class( $entry_classes ) .'>';

					$classes = 

					$output .= '<div class="'. $inner_classes .'">';

						// Entry Media
						if ( 'true' == $entry_media ) :

							/* Video
							-------------------------------------------------------------------------------*/
							if ( 'true' == $featured_video && $atts['post_video_output'] ) :

								$output .= '<div class="portfolio-entry-media portfolio-featured-video entry-media wpex-clr">';
									$output .= $atts['post_video_output'];
								$output .= '</div>';

							/* Featured Image
							-------------------------------------------------------------------------------*/
							elseif ( has_post_thumbnail( $atts['post_id'] ) ) :

								$output .= '<div class="'. $media_classes .'">';

									// Open link tag if thumblink does not equal nowhere
									if ( 'nowhere' != $thumb_link ) :

										// Lightbox
										if ( 'lightbox' == $thumb_link || 'lightbox_gallery' == $thumb_link ) :

											// Define vars
											$atts['lightbox_data'] = array();
											$lightbox_gallery_imgs = null;

											// Save correct lightbox class
											$lightbox_class = $lightbox_single_class;

											// Gallery
											if ( 'lightbox_gallery' == $thumb_link && function_exists( 'wpex_get_gallery_images' ) ) {
												if ( $lightbox_gallery_imgs = wpex_get_gallery_images( $atts['post_id'], 'lightbox' ) ) {
													$lightbox_class = ' wpex-lightbox-gallery';
													$atts['lightbox_data'][] = 'data-gallery="'. implode( ',', $lightbox_gallery_imgs ) .'"';
												}
											}

											// Generate lightbox image
											$lightbox_image = wpex_get_lightbox_image();

											// Get lightbox link
											$atts['lightbox_link'] = $lightbox_image;

											// Add lightbox data attributes
											if ( $lightbox_skin ) {
												$atts['lightbox_data'][] = 'data-skin="'. $lightbox_skin .'"';
											}
											if ( 'true' == $thumb_lightbox_title ) {
												$atts['lightbox_data'][] = 'data-title="'. wpex_get_esc_title() .'"';
											} else {
												$atts['lightbox_data'][] = 'data-show_title="false"';
											}
											if ( 'true' == $thumb_lightbox_caption && $atts['post_excerpt'] ) {
												$atts['lightbox_data'][] = 'data-caption="'. str_replace( '"',"'", $atts['post_excerpt'] ) .'"';
											}

											// Check for video
											if ( ! $lightbox_gallery_imgs
												&& $atts['post_video'] = get_post_meta( $atts['post_id'], 'wpex_post_video', true )
											) {
												$embed_url = wpex_sanitize_data( $atts['post_video'], 'embed_url' );
												if ( $embed_url ) {
													$atts['lightbox_link']   = $embed_url;
													$atts['lightbox_data'][] = 'data-type="iframe"';
													$atts['lightbox_data'][] = 'data-options="thumbnail:\''. $lightbox_image .'\',width:1920,height:1080"';
												}
											}

											$lightbox_data = ! empty( $atts['lightbox_data']  ) ? ' '. implode( ' ', $atts['lightbox_data'] ) : '';

											$output .= '<a href="'. $atts["lightbox_link"] .'" title="'. $atts['post_esc_title'] .'" class="portfolio-entry-media-link'. $lightbox_class .'"'. $lightbox_data .'>';

										// Standard post link
										else :

											$output .= '<a href="'. $atts['post_permalink'] .'" title="'. $atts['post_esc_title'] .'" class="portfolio-entry-media-link"'. vcex_html( 'target_attr', $link_target ) .'>';

										endif;

									endif;

									// Display post thumbnail
									$output .= wpex_get_post_thumbnail( array(
										'width'  => $img_width,
										'height' => $img_height,
										'crop'   => $img_crop,
										'alt'    => $atts['post_esc_title'],
										'class'  => 'portfolio-entry-img',
										'size'   => $img_size,
									) );

									// Inner link overlay HTML
									ob_start();
									wpex_overlay( 'inside_link', $overlay_style, $atts );
									$output .= ob_get_clean();

									// Close link tag
									if ( 'nowhere' != $thumb_link ) {
										$output .= '</a>';
									}

									// Outer link overlay HTML
									ob_start();
									wpex_overlay( 'outside_link', $overlay_style, $atts );
									$output .= ob_get_clean();

								$output .= '</div>';

							endif;

						endif;

						// Display content if needed
						if ( $entry_has_details ) :
							
							// Entry details start
							$output .= '<div class="portfolio-entry-details entry-details wpex-clr';
								if ( $content_css ) {
									$output .= ' '. $content_css;
								}
								$output .= '"';
								$output .= $content_style;
							$output .= '>';

								// Equal height div
								if ( $equal_heights_grid ) {
									$output .= '<div class="match-height-content">';
								}

								// Display title
								if ( 'true' == $title ) :

									$output .= '<'. $title_tag .' class="portfolio-entry-title entry-title"'. $heading_style .'>';

										// Display title without link
										if ( 'nowhere' == $title_link ) :

											$output .= $atts['post_title'];

										// Link title to lightbox
										elseif ( 'lightbox' == $title_link ) :

											// Lightbox image
											$lightbox_image = isset( $atts['lightbox_image'] ) ? $atts['lightbox_image'] : wpex_get_lightbox_image();

											// Lightbox Data
											$atts['lightbox_data'] = array();
											if ( $lightbox_skin && 'true' !== $thumb_lightbox_gallery ) {
												$atts['lightbox_data'][] = 'data-skin="'. $lightbox_skin .'"';
											}
											if ( 'true' == $thumb_lightbox_title ) {
												$atts['lightbox_data'][] = 'data-title="'. wpex_get_esc_title() .'"';
											}
											// Display lightbox
											if ( 'true' == $thumb_lightbox_caption && $atts['post_excerpt'] ) {
												$atts['lightbox_data'][] = 'data-caption="'. str_replace( '"',"'", $atts['post_excerpt'] ) .'"';
											}
											$lightbox_data = ! empty( $atts['lightbox_data']  ) ? ' '. implode( ' ', $atts['lightbox_data'] ) : '';

											$output .= '<a href="'. $lightbox_image .'" title="'. $atts['post_esc_title'] .'" class="wpex-lightbox"'. $heading_link_style .''. $lightbox_data .'>';
												$output .= $atts['post_title'];
											$output .= '</a>';

										// Link title to post
										else :

											$output .= '<a href="'. $atts['post_permalink'] .'" title="'. $atts['post_esc_title'] .'"'. $heading_link_style .''. vcex_html( 'target_attr', $link_target ) .'>';
												$output .= $atts['post_title'];
											$output .= '</a>';

										endif;

									$output .= '</'. $title_tag .'>';

								endif;

								// Display categories
								if ( 'true' == $show_categories ) :

									$output .= '<div class="'. $categories_classes .'"'. $categories_style .'>';
										// Display categories
										if ( 'true' == $show_first_category_only ) {
											$output .= wpex_get_first_term_link( $atts['post_id'], 'portfolio_category' );
										} else {
											$output .= wpex_get_list_post_terms( 'portfolio_category', true, true );
										}
									$output .= '</div>';

								endif;

								// Display excerpt
								if ( 'true' == $excerpt && $atts['post_excerpt'] ) :

									$output .= '<div class="portfolio-entry-excerpt entry-excerpt wpex-clr"'. $excerpt_style .'>';
										$output .= $atts['post_excerpt'];
									$output .= '</div>';

								endif;

								// Display read more button
								if ( 'true' == $read_more ) :

									$output .= '<div class="portfolio-entry-readmore-wrap entry-readmore-wrap wpex-clr">';

										$output .= '<a href="'. $atts['post_permalink'] .'" title="'. esc_attr( $read_more_text ) .'" rel="bookmark" class="'. $readmore_classes .'"'. $readmore_style . $readmore_data . vcex_html( 'target_attr', $link_target ) .'>';
											$output .= $read_more_text;
											if ( 'true' == $readmore_rarr ) :
												$output .= '<span class="vcex-readmore-rarr">'. wpex_element( 'rarr' ) .'</span>';
											endif;
										$output .= '</a>';

									$output .= '</div>';

								endif;
								
								// Close Equal height container
								if ( $equal_heights_grid ) {
									$output .= '</div>';
								}

							$output .= '</div>';

						endif; // End details check

					$output .= '</div>'; // Close entry inner

				$output .= '</div>'; // Close entry

				// Reset entry counter
				if ( $count == $columns ) {
					$count = '';
				}
			
			endwhile; // End post loop

		$output .= '</div>';
		
		// Display pagination if enabled
		if ( 'true' == $pagination ) {
			$output .= wpex_pagination( $wpex_query, false );
		}

	$output .= '</div>';

	// Reset the post data to prevent conflicts with WP globals
	wp_reset_postdata();

	// Echo output
	echo $output;

// If no posts are found display message
else :

	// Display no posts found error if function exists
	echo vcex_no_posts_found_message( $atts );

// End post check
endif;