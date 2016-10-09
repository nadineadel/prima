<?php
/**
 * Visual Composer Post Type Carousel
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 3.5.0
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

// Get and extract shortcode attributes
$atts = vc_map_get_attributes( 'vcex_post_type_carousel', $atts );
extract( $atts );

// Build the WordPress query
$wpex_query = vcex_build_wp_query( $atts );

//Output posts
if ( $wpex_query->have_posts() ) :

	// Extract attributes
	extract( $atts );

	// Load scripts
	$inline_js = array( 'carousel' );

	// Disable auto play if there is only 1 post
	if ( '1' == count( $wpex_query->posts ) ) {
		$auto_play = false;
	}

	// Prevent auto play in visual composer
	if ( wpex_vc_is_inline() ) {
		$auto_play = 'false';
	}

	// Items to scroll fallback for old setting
	if ( 'page' == $items_scroll ) {
		$items_scroll = $items;
	}

	// Main Classes
	$wrap_classes = array( 'wpex-carousel', 'wpex-carousel-post-type', 'wpex-clr', 'owl-carousel' );
	if ( $style ) {
		$wrap_classes[] = $style;
	}
	if ( $visibility ) {
		$wrap_classes[] = $visibility;
	}
	if ( $css_animation ) {
		$wrap_classes[] = vcex_get_css_animation( $css_animation );
	}
	if ( $classes ) {
		$wrap_classes[] = vcex_get_extra_class( $classes );
	}

	// Entry css
	$entry_css = $entry_css ? vc_shortcode_custom_css_class( $entry_css ) : '';

	// Entry media classes
	if ( 'true' == $media ) {
		$media_classes = array( 'wpex-carousel-entry-media', 'wpex-clr' );
		if ( $img_hover_style ) {
			$media_classes[] = wpex_image_hover_classes( $img_hover_style );
		}
		if ( $overlay_style ) {
			$media_classes[] = wpex_overlay_classes( $overlay_style );
		}
		if ( 'lightbox' == $thumbnail_link ) {
			$inline_js[] = 'carousel_lightbox';
			$wrap_classes[] = 'wpex-carousel-lightbox';
			vcex_enque_style( 'ilightbox' );
		}
		$media_classes = implode( ' ', $media_classes );
	}

	// Content Design
	$content_style = vcex_inline_style( array(
		'color'      => $content_color,
		'text_align' => $content_alignment,
		'font_size'  => $content_font_size,
	) );
	$content_css = $content_css ? vc_shortcode_custom_css_class( $content_css ) : '';

	// Title design
	if ( 'true' == $title ) {
		$heading_style = vcex_inline_style( array(
			'margin'         => $content_heading_margin,
			'text_transform' => $content_heading_transform,
			'font_size'      => $content_heading_size,
			'font_weight'    => $content_heading_weight,
			'line_height'    => $content_heading_line_height,
		) );
		$content_heading_color = vcex_inline_style( array(
			'color' => $content_heading_color,
		) );
	}

	// Date design
	if ( 'true' == $date ) {
		$date_style = vcex_inline_style( array(
			'color'     => $date_color,
			'font_size' => $date_font_size,
			'margin'    => $date_margin,
		) );
	}

	// Sanitize carousel data
	$arrows                 = wpex_esc_attr( $arrows, 'true' );
	$dots                   = wpex_esc_attr( $dots, 'false' );
	$auto_play              = wpex_esc_attr( $auto_play, 'false' );
	$infinite_loop          = wpex_esc_attr( $infinite_loop, 'true' );
	$center                 = wpex_esc_attr( $center, 'false' );
	$items                  = wpex_intval( $items, 4 );
	$items_scroll           = wpex_intval( $items_scroll, 1 );
	$timeout_duration       = wpex_intval( $timeout_duration, 5000 );
	$items_margin           = wpex_intval( $items_margin, 15 );
	$items_margin           = ( 'no-margins' == $style ) ? 0 : $items_margin;
	$tablet_items           = wpex_intval( $tablet_items, 3 );
	$mobile_landscape_items = wpex_intval( $mobile_landscape_items, 2 );
	$mobile_portrait_items  = wpex_intval( $mobile_portrait_items, 1 );
	$animation_speed        = wpex_intval( $animation_speed, 150 );

	// Convert arrays to strings
	$wrap_classes  = implode( ' ', $wrap_classes );

	// Load inline js
	vcex_inline_js( $inline_js ); ?>

	<div class="<?php echo $wrap_classes; ?>"<?php vcex_unique_id( $unique_id ); ?> data-items="<?php echo $items; ?>" data-slideby="<?php echo $items_scroll; ?>" data-nav="<?php echo $arrows; ?>" data-dots="<?php echo $dots; ?>" data-autoplay="<?php echo $auto_play; ?>" data-loop="<?php echo $infinite_loop; ?>" data-autoplay-timeout="<?php echo $timeout_duration ?>" data-center="<?php echo $center; ?>" data-margin="<?php echo intval( $items_margin ); ?>" data-items-tablet="<?php echo $tablet_items; ?>" data-items-mobile-landscape="<?php echo $mobile_landscape_items; ?>" data-items-mobile-portrait="<?php echo $mobile_portrait_items; ?>" data-smart-speed="<?php echo $animation_speed; ?>">
		<?php
		// Start loop
		$count = 0;
		while ( $wpex_query->have_posts() ) :
			$count++;

			// Get post from query
			$wpex_query->the_post();

			// Create new post object
			$post = new stdClass();

			// Get post data
			$get_post = get_post();
		
			// Post VARS
			$post->ID        = $get_post->ID;
			$post->type      = get_post_type( $get_post->ID );
			$post->permalink = wpex_get_permalink($get_post->ID );
			$post->the_title = get_the_title( $get_post->ID );

			// Only display carousel item if there is content to show
			if ( ( 'true' == $media && has_post_thumbnail() )
				|| 'true' == $title
				|| 'true' == $date
				|| 'true' == $excerpt
			) : ?>

				<div class="wpex-carousel-slide wpex-clr<?php if ( $entry_css ) echo ' '. $entry_css; ?>">

					<?php
					// Display media
					if ( 'true' == $media && has_post_thumbnail() ) : ?>
						
						<?php
						// Image html
						$img_html = wpex_get_post_thumbnail( array(
							'size'   => $img_size,
							'crop'   => $img_crop,
							'width'  => $img_width,
							'height' => $img_height,
							'alt'    => wpex_get_esc_title(),
						) ); ?>

						<div class="<?php echo $media_classes; ?>">

							<?php
							// No links
							if ( 'none' == $thumbnail_link ) : ?>

								<?php echo $img_html; ?>

							<?php
							// Lightbox
							elseif ( 'lightbox' == $thumbnail_link ) :

								$atts['lightbox_link'] = wpex_get_lightbox_image(); ?>

								<a href="<?php echo $atts['lightbox_link']; ?>" title="<?php wpex_esc_title(); ?>" class="wpex-carousel-entry-img wpex-carousel-lightbox-item" data-count="<?php echo $count; ?>">

									<?php echo $img_html; ?>

							<?php
							// Link to post
							else : ?>

								<a href="<?php echo $post->permalink; ?>" title="<?php wpex_esc_title(); ?>" class="wpex-carousel-entry-img">

									<?php echo $img_html; ?>

							<?php endif; ?>

							<?php
							// Overlay & close link
							if ( 'none' != $thumbnail_link ) {
								// Inner Overlay
								if ( $overlay_style ) {
									wpex_overlay( 'inside_link', $overlay_style, $atts );
								}
								// Close link
								echo '</a><!-- .wpex-carousel-entry-img -->';
								// Outside Overlay
								if ( $overlay_style ) {
									wpex_overlay( 'outside_link', $overlay_style, $atts );
								}
							} ?>

						</div><!-- .wpex-carousel-entry-media -->

					<?php endif; ?>

					<?php if ( 'true' == $title || 'true' == $excerpt || 'true' == $date ) : ?>

						<div class="wpex-carousel-entry-details wpex-clr<?php if ( $content_css ) echo ' '. $content_css; ?>"<?php echo $content_style; ?>>

							<?php
							// Title
							if ( 'true' == $title && $post->the_title ) : ?>

								<div class="wpex-carousel-entry-title entry-title"<?php echo $heading_style; ?>>
									<a href="<?php echo $post->permalink; ?>" title="<?php wpex_esc_title(); ?>"<?php echo $content_heading_color; ?>><?php echo $post->the_title; ?></a>
								</div><!-- .wpex-carousel-entry-title -->

							<?php endif; ?>

							<?php
							// Display publish date if $date is enabled
							if ( 'true' == $date ) : ?>

								<div class="vcex-carousel-entry-date wpex-clr"<?php echo $date_style; ?>>
									<?php if ( 'tribe_events' == $post->type && function_exists( 'tribe_get_start_date' ) ) { ?>
										<?php echo tribe_get_start_date( $post->ID, false, get_option( 'date_format' ) ); ?>
									<?php } else { ?> 
										<?php echo get_the_date(); ?>
									<?php } ?>
								</div><!-- .vcex-carousel-entry-date -->

							<?php endif; ?>

							<?php
							// Excerpt
							if ( 'true' == $excerpt ) :

								// Generate excerpt
								$post->excerpt = wpex_get_excerpt( array (
									'length' => intval( $excerpt_length ),
								) );

								if ( $post->excerpt ) { ?>

									<div class="wpex-carousel-entry-excerpt wpex-clr">
										<?php echo $post->excerpt; ?>
									</div><!-- .wpex-carousel-entry-excerpt -->

								<?php } ?>

							<?php endif; ?>

						</div><!-- .wpex-carousel-entry-details -->

					<?php endif; ?>

				</div><!-- .wpex-carousel-slide -->

			<?php endif; ?>

		<?php endwhile; ?>

	</div><!-- .wpex-carousel -->

	<?php
	// Remove post object from memory
	$post = null;

	// Reset the post data to prevent conflicts with WP globals
	wp_reset_postdata(); ?>

<?php
// If no posts are found display message
else : ?>

	<?php
	// Display no posts found error if function exists
	echo vcex_no_posts_found_message( $atts ); ?>

<?php
// End post check
endif; ?>