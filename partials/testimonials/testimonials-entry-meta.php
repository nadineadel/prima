<?php
/**
 * Outputs the testimonial entry meta data
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 3.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="testimonial-entry-meta clr">
	<?php get_template_part( 'partials/testimonials/testimonials-entry-author' ); ?>
	<?php get_template_part( 'partials/testimonials/testimonials-entry-company' ); ?>
</div><!-- .testimonial-entry-meta -->