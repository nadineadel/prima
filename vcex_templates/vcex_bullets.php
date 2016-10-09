<?php
/**
 * Visual Composer Bullets
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
if ( ! function_exists( 'vc_map_get_attributes' ) ) {
	vcex_function_needed_notice();
	return;
}

// Get and extract shortcode attributes
extract( vc_map_get_attributes( 'vcex_bullets', $atts ) ); ?>

<div class="vcex-bullets vcex-bullets-<?php echo $style; ?>"><?php echo do_shortcode( $content ); ?></div>