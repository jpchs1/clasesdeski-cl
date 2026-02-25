<?php
/**
 * Theme Widget: Contacts
 */

// Theme init
if (!function_exists('mounthood_widget_contacts_theme_setup')) {
	add_action( 'mounthood_action_before_init_theme', 'mounthood_widget_contacts_theme_setup', 1 );
	function mounthood_widget_contacts_theme_setup() {

		// Register shortcodes in the shortcodes list
		if (function_exists('mounthood_exists_visual_composer') && mounthood_exists_visual_composer())
			add_action('mounthood_action_shortcodes_list_vc','mounthood_widget_contacts_reg_shortcodes_vc');
	}
}

// Load widget
if (!function_exists('mounthood_widget_contacts_load')) {
	add_action( 'widgets_init', 'mounthood_widget_contacts_load' );
	function mounthood_widget_contacts_load() {
		register_widget( 'mounthood_widget_contacts' );
	}
}

// Widget Class
class mounthood_widget_contacts extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'widget_contacts', 'description' => esc_html__('Show contact information', 'trx_utils') );
		parent::__construct( 'mounthood_widget_contacts', esc_html__('Mounthood - Show Contact information', 'trx_utils'), $widget_ops );
	}

	// Show widget
	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '' );
		$show_ci = isset($instance['show_ci']) ? (int) $instance['show_ci'] : 1; //Contacts in the header
		$show_coh = isset($instance['show_coh']) ? (int) $instance['show_coh'] : 1; //Open hours in the header
		$show_ce = isset($instance['show_ce']) ? (int) $instance['show_ce'] : 1; //Contact form email
		$show_ca1 = isset($instance['show_ca1']) ? (int) $instance['show_ca1'] : 1; //Company address (part 1)
		$show_ca2 = isset($instance['show_ca2']) ? (int) $instance['show_ca2'] : 1; //Company address (part 2)
		$show_cp = isset($instance['show_cp']) ? (int) $instance['show_cp'] : 1; //Phone
		$show_cf = isset($instance['show_cf']) ? (int) $instance['show_cf'] : 1; // Fax

		// Before widget (defined by themes)
		mounthood_show_layout($before_widget);

		// Display the widget title if one was input (before and after defined by themes)
		if ($title) mounthood_show_layout($before_title . $title . $after_title);

		// Display widget body
		?>
		<div class="widget_inner">
            <ul class="contact_info">
			<?php
            $ci = mounthood_get_theme_option('contact_info');
            $coh = mounthood_get_theme_option('contact_open_hours');
            $ce = mounthood_get_theme_option('contact_email');
            $ca1 = mounthood_get_theme_option('contact_address_1');
            $ca2 = mounthood_get_theme_option('contact_address_2');
            $cp = mounthood_get_theme_option('contact_phone');
            $cf = mounthood_get_theme_option('contact_fax');

            if ($show_ci && ($ci !='')) {
                echo '<li><i class="icon icon-home"></i><div>' . esc_html($ci) . '</div></li>';
            }

            if ($show_cp && ($cp !='')) {
                echo '<li><i class="icon icon-phone"></i><div><a href="tel:' . esc_html($cp) . '">' . esc_html($cp) . '</a> </div></li>';
            }
            if (($show_ca1 && ($ca1 !='')) || ($show_ca2 && ($ca2 !=''))) {
                echo '<li><i class="icon icon-location"></i><div>';
                if ($show_ca1 && ($ca1 !='')) echo esc_html($ca1) . '<br/>';
                if ($show_ca2 && ($ca2 !='')) echo esc_html($ca2);
                echo '</div></li>';
            }
            if ($show_cf && ($cf !='')) {
                echo '<li><i class="icon icon-eye"></i><div>' . esc_html($cf) . '</div></li>';
            }
            if ($show_ce && ($ce !='')) {
                echo '<li><i class="icon icon-pencil"></i><div><a href="mailto:' . esc_html($ce) . '">' . esc_html($ce) . '</a></div></li>';
            }
            if ($show_coh && ($coh !='')) {
                echo '<li><i class="icon icon-clock"></i><div>' . esc_html($coh) . '</div></li>';
            }
			?>
            </ul>
		</div>

		<?php
		// After widget (defined by themes)
		mounthood_show_layout($after_widget);
	}

	// Update the widget settings.
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['show_ci'] = (int) $new_instance['show_ci'];
		$instance['show_coh'] = (int) $new_instance['show_coh'];
		$instance['show_ce'] = (int) $new_instance['show_ce'];
		$instance['show_ca1'] = (int) $new_instance['show_ca1'];
		$instance['show_ca2'] = (int) $new_instance['show_ca2'];
		$instance['show_cp'] = (int) $new_instance['show_cp'];
		$instance['show_cf'] = (int) $new_instance['show_cf'];
		return $instance;
	}

	// Displays the widget settings controls on the widget panel.
	function form( $instance ) {

		// Set up some default widget settings
		$instance = wp_parse_args( (array) $instance, array(
				'title' => '',
                'show_ci' => '0', //Contacts in the header
                'show_coh' => '0', //Open hours in the header
                'show_ce' => '1', //Contact form email
                'show_ca1' => '1', //Company address (part 1)
                'show_ca2' => '1', //Company address (part 2)
                'show_cp' => '1', //Phone
                'show_cf' => '0' // Fax
			)
		);
		$title = $instance['title'];
		$show_ci = (int) $instance['show_ci'];
		$show_coh = (int) $instance['show_coh'];
		$show_ce = (int) $instance['show_ce'];
		$show_ca1 = (int) $instance['show_ca1'];
		$show_ca2 = (int) $instance['show_ca2'];
		$show_cp = (int) $instance['show_cp'];
		$show_cf = (int) $instance['show_cf'];
		?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e('Title:', 'trx_utils'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr($instance['title']); ?>" class="widgets_param_fullwidth" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_ci')); ?>_1"><?php esc_html_e('Show "contacts in the header":', 'trx_utils'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_ci')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_ci')); ?>" value="1" <?php echo (1==$show_ci ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_ci')); ?>_1"><?php esc_html_e('Show', 'trx_utils'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_ci')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_ci')); ?>" value="0" <?php echo (0==$show_ci ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_ci')); ?>_0"><?php esc_html_e('Hide', 'trx_utils'); ?></label>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_coh')); ?>_1"><?php esc_html_e('Show "open hours in the header":', 'trx_utils'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_coh')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_coh')); ?>" value="1" <?php echo (1==$show_coh ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_coh')); ?>_1"><?php esc_html_e('Show', 'trx_utils'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_coh')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_coh')); ?>" value="0" <?php echo (0==$show_coh ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_coh')); ?>_0"><?php esc_html_e('Hide', 'trx_utils'); ?></label>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_ce')); ?>_1"><?php esc_html_e('Show "contact form email":', 'trx_utils'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_ce')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_ce')); ?>" value="1" <?php echo (1==$show_ce ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_ce')); ?>_1"><?php esc_html_e('Show', 'trx_utils'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_ce')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_ce')); ?>" value="0" <?php echo (0==$show_ce ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_ce')); ?>_0"><?php esc_html_e('Hide', 'trx_utils'); ?></label>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_ca1')); ?>_1"><?php esc_html_e('Show "company address (part 1)":', 'trx_utils'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_ca1')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_ca1')); ?>" value="1" <?php echo (1==$show_ca1 ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_ca1')); ?>_1"><?php esc_html_e('Show', 'trx_utils'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_ca1')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_ca1')); ?>" value="0" <?php echo (0==$show_ca1 ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_ca1')); ?>_0"><?php esc_html_e('Hide', 'trx_utils'); ?></label>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_ca2')); ?>_1"><?php esc_html_e('Show "company address (part 2)":', 'trx_utils'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_ca2')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_ca2')); ?>" value="1" <?php echo (1==$show_ca2 ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_ca2')); ?>_1"><?php esc_html_e('Show', 'trx_utils'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_ca2')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_ca2')); ?>" value="0" <?php echo (0==$show_ca2 ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_ca2')); ?>_0"><?php esc_html_e('Hide', 'trx_utils'); ?></label>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_cp')); ?>_1"><?php esc_html_e('Show "phone":', 'trx_utils'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_cp')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_cp')); ?>" value="1" <?php echo (1==$show_cp ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_cp')); ?>_1"><?php esc_html_e('Show', 'trx_utils'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_cp')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_cp')); ?>" value="0" <?php echo (0==$show_cp ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_cp')); ?>_0"><?php esc_html_e('Hide', 'trx_utils'); ?></label>
		</p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('show_cf')); ?>_1"><?php esc_html_e('Show "fax":', 'trx_utils'); ?></label><br />
            <input type="radio" id="<?php echo esc_attr($this->get_field_id('show_cf')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_cf')); ?>" value="1" <?php echo (1==$show_cf ? ' checked="checked"' : ''); ?> />
            <label for="<?php echo esc_attr($this->get_field_id('show_cf')); ?>_1"><?php esc_html_e('Show', 'trx_utils'); ?></label>
            <input type="radio" id="<?php echo esc_attr($this->get_field_id('show_cf')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_cf')); ?>" value="0" <?php echo (0==$show_cf ? ' checked="checked"' : ''); ?> />
            <label for="<?php echo esc_attr($this->get_field_id('show_cf')); ?>_0"><?php esc_html_e('Hide', 'trx_utils'); ?></label>
        </p>
		<?php
	}
}



// trx_widget_contacts
//-------------------------------------------------------------
/*
[trx_widget_contacts id="unique_id" title="Widget title" show_ci="0|1" show_coh="0|1" show_ce="0|1" show_ca1="0|1" show_ca2="0|1" show_cp="0|1" show_cf="0|1"]
*/
if ( !function_exists( 'mounthood_sc_widget_contacts' ) ) {
	function mounthood_sc_widget_contacts($atts, $content=null){
		$atts = mounthood_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
            'show_ci' => '0', //Contacts in the header
            'show_coh' => '1', //Open hours in the header
            'show_ce' => '1', //Contact form email
            'show_ca1' => '1', //Company address (part 1)
            'show_ca2' => '0', //Company address (part 2)
            'show_cp' => '1', //Phone
            'show_cf' => '0', // Fax
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts));
		if ($atts['show_ci']=='') $atts['show_ci'] = 0;
		if ($atts['show_coh']=='') $atts['show_coh'] = 0;
		if ($atts['show_ce']=='') $atts['show_ce'] = 0;
		if ($atts['show_ca1']=='') $atts['show_ca1'] = 0;
		if ($atts['show_ca2']=='') $atts['show_ca2'] = 0;
		if ($atts['show_cp']=='') $atts['show_cp'] = 0;
		if ($atts['show_cf']=='') $atts['show_cf'] = 0;
		extract($atts);
		$type = 'mounthood_widget_contacts';
		$output = '';
		global $wp_widget_factory;
		if ( is_object( $wp_widget_factory ) && isset( $wp_widget_factory->widgets, $wp_widget_factory->widgets[ $type ] ) ) {
			$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
			          . ' class="widget_area sc_widget_contacts'
			          . (mounthood_exists_visual_composer() ? ' vc_widget_contacts wpb_content_element' : '')
			          . (!empty($class) ? ' ' . esc_attr($class) : '')
			          . '">';
			ob_start();
			the_widget( $type, $atts, mounthood_prepare_widgets_args(mounthood_storage_get('widgets_args'), $id ? $id.'_widget' : 'widget_contacts', 'widget_contacts') );
			$output .= ob_get_contents();
			ob_end_clean();
			$output .= '</div>';
		}
		return apply_filters('mounthood_shortcode_output', $output, 'trx_widget_contacts', $atts, $content);
	}
	mounthood_require_shortcode("trx_widget_contacts", "mounthood_sc_widget_contacts");
}


// Add [trx_widget_contacts] in the VC shortcodes list
if (!function_exists('mounthood_widget_contacts_reg_shortcodes_vc')) {
	function mounthood_widget_contacts_reg_shortcodes_vc() {

		vc_map( array(
			"base" => "trx_widget_contacts",
			"name" => esc_html__("Widget Contact Information", 'trx_utils'),
			"description" => wp_kses_data( __("Insert site's contact information from theme options.", 'trx_utils') ),
			"category" => esc_html__('Content', 'trx_utils'),
			"icon" => 'icon_trx_widget_contacts',
			"class" => "trx_widget_contacts",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("Widget title", 'trx_utils'),
					"description" => wp_kses_data( __("Title of the widget", 'trx_utils') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "show_ci",
					"heading" => esc_html__("Show 'contacts in the header'", 'trx_utils'),
					"description" => wp_kses_data( __("Do you want to display contacts in the header?", 'trx_utils') ),
					"class" => "",
					"std" => 1,
					"value" => array("Show" => "1" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "show_coh",
					"heading" => esc_html__("Show 'open hours in the header'", 'trx_utils'),
					"description" => wp_kses_data( __("Do you want to display open hours in the header?", 'trx_utils') ),
					"class" => "",
					"std" => 1,
					"value" => array("Show" => "1" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "show_ce",
					"heading" => esc_html__("Show 'contact form email'", 'trx_utils'),
					"description" => wp_kses_data( __("Do you want to display contact form email?", 'trx_utils') ),
					"class" => "",
					"std" => 1,
					"value" => array("Show" => "1" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "show_ca1",
					"heading" => esc_html__("Show 'company address (part 1)'", 'trx_utils'),
					"description" => wp_kses_data( __("Do you want to display company address (part 1)?", 'trx_utils') ),
					"class" => "",
					"std" => 1,
					"value" => array("Show" => "1" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "show_ca2",
					"heading" => esc_html__("Show 'company address (part 2)'", 'trx_utils'),
					"description" => wp_kses_data( __("Do you want to display company address (part 2)?", 'trx_utils') ),
					"class" => "",
					"std" => 1,
					"value" => array("Show" => "1" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "show_cp",
					"heading" => esc_html__("Show 'phone'", 'trx_utils'),
					"description" => wp_kses_data( __("Do you want to display phone?", 'trx_utils') ),
					"class" => "",
					"std" => 1,
					"value" => array("Show" => "1" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "show_cf",
					"heading" => esc_html__("Show 'fax'", 'trx_utils'),
					"description" => wp_kses_data( __("Do you want to display fax?", 'trx_utils') ),
					"class" => "",
					"std" => 1,
					"value" => array("Show" => "1" ),
					"type" => "checkbox"
				),

				mounthood_get_vc_param('id'),
				mounthood_get_vc_param('class'),
				mounthood_get_vc_param('css')
			)
		) );

		class WPBakeryShortCode_Trx_Widget_Contacts extends WPBakeryShortCode {}

	}
}
?>