<?php
/**
 * Real Testimonials Pro Widget
 *
 * @since 1.0
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Admin
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

namespace ShapedPlugin\TestimonialPro\Admin\Views;

/**
 * TPRO_Widget_Content
 */
class TPRO_Widget_Content extends \WP_Widget {

	/**
	 * __Construct
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct(
			'TPRO_Widget_Content',
			__( 'Real Testimonials Pro', 'testimonial-pro' ),
			array(
				'description' => __( 'Display Testimonials.', 'testimonial-pro' ),
			)
		);
	}

	/**
	 * Outputs the content of the widget.
	 *
	 * @param array $args args.
	 * @param array $instance instance.
	 */
	public function widget( $args, $instance ) {
		extract( $args );

		$title        = apply_filters( 'widget_title', esc_attr( $instance['title'] ) );
		$shortcode_id = isset( $instance['shortcode_id'] ) ? absint( $instance['shortcode_id'] ) : 0;

		if ( ! $shortcode_id ) {
			return;
		}

		echo wp_kses_post( $args['before_widget'] );

		if ( ! empty( $title ) ) {
			echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
		}

		echo do_shortcode( '[sp_testimonial id=' . $shortcode_id . ']' );
		echo wp_kses_post( $args['after_widget'] );
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param  mixed $instance The widget options.
	 * @return void
	 */
	public function form( $instance ) {
		$shortcodes   = $this->shortcodes_list();
		$shortcode_id = ! empty( $instance['shortcode_id'] ) ? absint( $instance['shortcode_id'] ) : null;
		$title        = ! empty( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';

		if ( count( $shortcodes ) > 0 ) {

			echo sprintf( '<p><label for="%1$s">%2$s</label>', esc_attr( $this->get_field_id( 'title' ) ), esc_html__( 'Title:', 'testimonial-pro' ) );
			echo sprintf( '<input type="text" class="widefat" id="%1$s" name="%2$s" value="%3$s" /></p>', esc_attr( $this->get_field_id( 'title' ) ), esc_attr( $this->get_field_name( 'title' ) ), esc_attr( $title ) );

			echo sprintf( '<p><label>%s</label>', esc_html__( 'Testimonial Shortcodes:', 'testimonial-pro' ) );
			echo sprintf( '<select class="widefat" name="%s">', esc_attr( $this->get_field_name( 'shortcode_id' ) ) );
			foreach ( $shortcodes as $shortcode ) {
				$selected = $shortcode_id === $shortcode->id ? 'selected="selected"' : '';
				echo sprintf(
					'<option value="%1$d" %3$s>%2$s</option>',
					esc_attr( $shortcode->id ),
					esc_html( $shortcode->title ),
					esc_attr( $selected )
				);
			}
			echo '</select></p>';

		} else {
			echo sprintf(
				'<p>%1$s <a href="' . esc_url(
					admin_url( 'post-new.php?post_type=spt_shortcodes' )
				) . '">%3$s</a> %2$s</p>',
				esc_html__( 'You did not generate any shortcode yet.', 'testimonial-pro' ),
				esc_html__( 'to generate a new shortcode now.', 'testimonial-pro' ),
				esc_html__( 'click here', 'testimonial-pro' )
			);
		}
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options.
	 * @param array $old_instance The previous options.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                 = array();
		$instance['title']        = sanitize_text_field( $new_instance['title'] );
		$instance['shortcode_id'] = absint( $new_instance['shortcode_id'] );

		return $instance;
	}

	/**
	 * Shortcodes_list
	 *
	 * @return statement
	 */
	private function shortcodes_list() {
		$shortcodes = get_posts(
			array(
				'post_type'   => 'spt_shortcodes',
				'post_status' => 'publish',
			)
		);

		if ( count( $shortcodes ) < 1 ) {
			return array();
		}

		return array_map(
			function ( $shortcode ) {
					return (object) array(
						'id'    => absint( $shortcode->ID ),
						'title' => esc_html( $shortcode->post_title ),
					);
			},
			$shortcodes
		);
	}

}
