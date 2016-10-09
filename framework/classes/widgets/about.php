<?php
/**
 * About widget
 *
 * Learn more: http://codex.wordpress.org/Widgets_API
 *
 * @package Total WordPress Theme
 * @subpackage Widgets
 * @version 3.3.3
 */

// Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
	exit;
}

// Start widget class
if ( ! class_exists( 'WPEX_About_Widget' ) ) {

	class WPEX_About_Widget extends WP_Widget {
		
		/**
		 * Register widget with WordPress.
		 *
		 * @since 3.2.0
		 */
		public function __construct() {
			$branding = wpex_get_theme_branding();
			$branding = $branding ? $branding . ' - ' : '';
			parent::__construct(
				'wpex_about',
				$branding . esc_html__( 'About', 'total' )
			);
			add_action( 'load-widgets.php', array( $this, 'scripts' ), 100 );
		}

		/**
		 * Enqueue media scripts
		 *
		 * @since 3.2.0
		 */
		public function scripts() {
			wp_enqueue_media();
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 * @since 3.2.0
		 *
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {

			// Widget options
			$title       = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
			$image       = isset( $instance['image'] ) ? esc_url( $instance['image'] ) : '';
			$description = isset( $instance['description'] ) ? $instance['description'] : '';

			// Before widget hook
			echo $args['before_widget']; ?>

				<?php
				// Display widget title
				if ( $title ) {
					echo $args['before_title'] . $title . $args['after_title'];
				} ?>

				<div class="wpex-about-widget wpex-clr">

					<?php
					// Display the image
					if ( $image ) :

						// Image classes
						$img_style = isset( $instance['img_style'] ) ? $instance['img_style'] : '';
						$img_class = ( 'round' == $img_style || 'rounded' == $img_style ) ? ' class="wpex-'. $img_style .'"' : ''; ?>

						<div class="wpex-about-widget-image">
							<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $title ); ?>"<?php echo $img_class; ?> />
						</div><!-- .wpex-about-widget-description -->

					<?php endif; ?>

					<?php
					// Display the description
					if ( $description ) : ?>

						<div class="wpex-about-widget-description wpex-clr">
							<?php echo wpex_sanitize_data( $description, 'html' ); ?>
						</div><!-- .wpex-about-widget-description -->

					<?php endif; ?>

				</div><!-- .mailchimp-widget -->

			<?php
			// After widget hook
			echo $args['after_widget'];
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 * @since 3.2.0
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance                = $old_instance;
			$instance['title']       = strip_tags( $new_instance['title'] );
			$instance['image']       = strip_tags( $new_instance['image'] );
			$instance['img_style']   = strip_tags( $new_instance['img_style'] );
			$instance['description'] = wpex_sanitize_data( $new_instance['description'], 'html' );
			return $instance;
		}

		/**
		 * Back-end widget form.
		 *
		 * @see WP_Widget::form()
		 * @since 3.2.0
		 *
		 * @param array $instance Previously saved values from database.
		 */
		public function form( $instance ) {
			$instance = wp_parse_args( ( array ) $instance, array(
				'title'       => esc_html__( 'About Me', 'total' ),
				'image'       => '',
				'img_style'   => 'plain',
				'description' => '',

			) );
			extract( $instance ); ?>
			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'total' ); ?>:</label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>"><?php esc_html_e( 'Image URL', 'total' ); ?>:</label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image' ) ); ?>" type="text" value="<?php echo esc_attr( $image ); ?>" style="margin-bottom:10px;" />
				<input class="wpex-widget-upload-button button button-secondary" type="button" value="<?php esc_html_e( 'Upload Image', 'total' ); ?>" />
			</p>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'img_style' ) ); ?>"><?php esc_html_e( 'Image Style', 'total' ); ?>:</label>
				<select id="<?php echo esc_attr( $this->get_field_id( 'img_style' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'img_style' ) ); ?>" class="widefat">
					<option value="plain" <?php selected( $img_style, 'plain' ) ?>><?php esc_html_e( 'Plain', 'total' ); ?></option>
					<option value="rounded" <?php selected( $img_style, 'rounded' ) ?>><?php esc_html_e( 'Rounded', 'total' ); ?></option>
					<option value="round" <?php selected( $img_style, 'round' ) ?>><?php esc_html_e( 'Round', 'total' ); ?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php esc_html_e( 'Description:','total' ); ?></label>
				<textarea class="widefat" rows="5" cols="20" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>"><?php echo wpex_sanitize_data( $instance['description'], 'html' ); ?></textarea>
			</p>
			<script type="text/javascript">
				(function($) {
					"use strict";
					$( document ).ready( function() {
						var _custom_media = true,
							_orig_send_attachment = wp.media.editor.send.attachment;
						$( '.wpex-widget-upload-button' ).click(function(e) {
							var send_attachment_bkp	= wp.media.editor.send.attachment,
								button = $(this),
								id = button.prev();
								_custom_media = true;
							wp.media.editor.send.attachment = function( props, attachment ) {
								if ( _custom_media ) {
									$( id ).val( attachment.url );
								} else {
									return _orig_send_attachment.apply( this, [props, attachment] );
								};
							}
							wp.media.editor.open( button );
							return false;
						} );
						$( '.add_media').on('click', function() {
							_custom_media = false;
						} );
					} );
				} ) ( jQuery );
			</script>

			<?php
		}
	}
}
register_widget( 'WPEX_About_Widget' );