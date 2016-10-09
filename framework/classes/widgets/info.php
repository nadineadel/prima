<?php
/**
 * Business Info Widget
 *
 * Learn more: http://codex.wordpress.org/Widgets_API
 *
 * @package Total WordPress Theme
 * @subpackage Widgets
 * @version 3.3.3
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start class
class WPEX_Info_Widget extends WP_Widget {
	
	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		$branding = wpex_get_theme_branding();
		$branding = $branding ? $branding . ' - ' : '';
		parent::__construct(
			'wpex_info_widget',
			$branding . esc_html__( 'Business Info', 'total' )
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

		// Set vars for widget usage
		$title        = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
		$address      = isset( $instance['address'] ) ? $instance['address'] : '';
		$phone_number = isset( $instance['phone_number'] ) ? $instance['phone_number'] : '';
		$fax_number   = isset( $instance['fax_number'] ) ? $instance['fax_number'] : '';
		$email        = isset( $instance['email'] ) ? $instance['email'] : '';

		// Before widget WP hook
		echo $args['before_widget'];

		// Display title if defined
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title']; 
		} ?>

		<div class="wpex-info-widget wpex-clr">

			<?php if ( $address ) : ?>

				<div class="wpex-info-widget-address wpex-clr">
					<span class="fa fa-map-marker"></span>
					<?php echo wpautop( wpex_sanitize_data( $address, 'html' ) ); ?>
				</div><!-- .wpex-info-widget-address -->

			<?php endif; ?>

			<?php if ( $phone_number ) : ?>

				<div class="wpex-info-widget-phone wpex-clr">
					<span class="fa fa-phone"></span><?php echo strip_tags( $phone_number ); ?>
				</div><!-- .wpex-info-widget-phone -->

			<?php endif; ?>

			<?php if ( $fax_number ) : ?>

			<div class="wpex-info-widget-fax wpex-clr">
				<span class="fa fa-fax"></span><?php echo strip_tags( $fax_number ); ?>
			</div><!-- .wpex-info-widget-fax -->

			<?php endif; ?>

			<?php if ( $email ) : ?>

				<div class="wpex-info-widget-email wpex-clr">
					<span class="fa fa-envelope"></span>
					<?php if ( is_email( sanitize_email( $email ) ) ) : ?>
						<a href="mailto:<?php echo sanitize_email( $email ); ?>" title="<?php esc_attr_e( 'Email Us', 'total' ); ?>"><?php echo sanitize_email( $email ); ?></a>
					<?php else : ?>
						<?php echo strip_tags( $email ); ?>
					<?php endif; ?>
				</div><!-- .wpex-info-widget-address -->

			<?php endif; ?>

		</div><!-- .wpex-info-widget -->

		<?php
		// After widget WP hook
		echo $args['after_widget']; ?>
		
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
		$instance                 = $old_instance;
		$instance['title']        = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['address']      = ( ! empty( $new_instance['address'] ) ) ? wpex_sanitize_data( $new_instance['address'], 'html' ) : '';
		$instance['phone_number'] = ( ! empty( $new_instance['phone_number'] ) ) ? strip_tags( $new_instance['phone_number'] ) : '';
		$instance['fax_number']   = ( ! empty( $new_instance['fax_number'] ) ) ? strip_tags( $new_instance['fax_number'] ) : '';
		$instance['email']        = ( ! empty( $new_instance['email'] ) ) ? strip_tags( $new_instance['email'] ) : '';
		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		extract( wp_parse_args( $instance, array(
			'title'        => esc_html__( 'Business Info', 'total' ),
			'address'      => '',
			'phone_number' => '',
			'fax_number'   => '',
			'email'        => '',
		) ) ); ?>

		<?php /* Title */ ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title', 'total' ); ?></label>
			<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<?php /* Address */ ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>">
			<?php esc_attr_e( 'Address', 'total' ); ?></label>
			<textarea rows="5" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'address' ) ); ?>" type="text"><?php echo wpex_sanitize_data( $address, 'html' ); ?></textarea>
		</p>

		<?php /* Phone Number */ ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'phone_number' ) ); ?>"><?php esc_attr_e( 'Phone Number', 'total' ); ?></label>
			<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'phone_number' ) ); ?>" type="text" value="<?php echo esc_attr( $phone_number ); ?>" />
		</p>

		<?php /* Fax Number */ ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'fax_number' ) ); ?>"><?php esc_attr_e( 'Fax Number', 'total' ); ?></label>
			<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'fax_number' ) ); ?>" type="text" value="<?php echo esc_attr( $fax_number ); ?>" />
		</p>

		<?php /* Email */ ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>"><?php esc_attr_e( 'Email', 'total' ); ?></label>
			<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'email' ) ); ?>" type="text" value="<?php echo esc_attr( $email ); ?>" />
		</p>

		
	<?php
	}
}
register_widget( 'WPEX_Info_Widget' );