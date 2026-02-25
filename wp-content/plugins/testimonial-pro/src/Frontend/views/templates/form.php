<?php
/**
 * The Testimonial Form.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/form.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
<div id="testimonial_form_<?php echo esc_attr( $form_id ); ?>" class="sp-tpro-fronted-form">
	<form id="testimonial_form" name="testimonial_form" method="post" action="" enctype="multipart/form-data">
		<?php
		if ( ! empty( $validation_msg ) && 'top' === $form_data['tpro_message_position'] ) {
			include self::sptp_locate_template( 'form/validation-msg.php' );
		}

		foreach ( $form_fields as $field_id => $form_field ) {
			switch ( $field_id ) {
				case 'full_name':
					if ( in_array( 'name', $form_element, true ) ) {
						$full_name_label = isset( $full_name['label'] ) ? $full_name['label'] : '';
						include self::sptp_locate_template( 'form/full-name.php' );
					}
					break;
				case 'email_address':
					if ( in_array( 'email', $form_element, true ) ) {
						$email_address_label = isset( $email_address['label'] ) ? $email_address['label'] : '';
						include self::sptp_locate_template( 'form/email.php' );
					}
					break;
				case 'identity_position':
					if ( in_array( 'position', $form_element, true ) ) {
						$identity_position_label = isset( $identity_position['label'] ) ? $identity_position['label'] : '';
						include self::sptp_locate_template( 'form/position.php' );
					}
					break;
				case 'company_name':
					if ( in_array( 'company', $form_element, true ) ) {
						$company_name_label = isset( $company_name['label'] ) ? $company_name['label'] : '';
						include self::sptp_locate_template( 'form/company-name.php' );
					}
					break;
				case 'testimonial_title':
					if ( in_array( 'testimonial_title', $form_element, true ) ) {
						$testimonial_title_label = isset( $testimonial_title['label'] ) ? $testimonial_title['label'] : '';
						include self::sptp_locate_template( 'form/testimonial-title.php' );
					}
					break;
				case 'testimonial':
					if ( in_array( 'testimonial', $form_element, true ) ) {
						$testimonial_label = isset( $testimonial['label'] ) ? $testimonial['label'] : '';
						include self::sptp_locate_template( 'form/testimonial-content.php' );
					}
					break;
				case 'groups':
					if ( in_array( 'groups', $form_element, true ) ) {
						$group_label = isset( $groups['label'] ) ? $groups['label'] : '';
						include self::sptp_locate_template( 'form/testimonial-cat.php' );
					}
					break;
				case 'featured_image':
					if ( in_array( 'image', $form_element, true ) ) {
						$featured_image_label = isset( $featured_image['label'] ) ? $featured_image['label'] : '';
						include self::sptp_locate_template( 'form/image.php' );
					}
					break;
				case 'location':
					if ( in_array( 'location', $form_element, true ) ) {
						$location_label = isset( $location['label'] ) ? $location['label'] : '';
						include self::sptp_locate_template( 'form/location.php' );
					}
					break;
				case 'phone_mobile':
					if ( in_array( 'phone_mobile', $form_element, true ) ) {
						$phone_mobile_label = isset( $phone_mobile['label'] ) ? $phone_mobile['label'] : '';
						include self::sptp_locate_template( 'form/phone.php' );
					}
					break;
				case 'website':
					if ( in_array( 'website', $form_element, true ) ) {
						$website_label = isset( $website['label'] ) ? $website['label'] : '';
						include self::sptp_locate_template( 'form/website.php' );
					}
					break;
				case 'video_url':
					if ( in_array( 'video_url', $form_element, true ) ) {
						$video_label        = isset( $video_url['label'] ) ? $video_url['label'] : '';
						$video_record_label = isset( $video_url['record_label'] ) ? $video_url['record_label'] : '';
						$show_video_by_url  = isset( $video_url['video_by_url'] ) ? $video_url['video_by_url'] : true;
						$show_record_video  = isset( $video_url['record_video'] ) ? $video_url['record_video'] : false;
						$recording_time     = isset( $video_url['recording_time'] ) ? $video_url['recording_time'] : 2;
						$record_btn_text    = isset( $video_url['record_btn_text'] ) ? $video_url['record_btn_text'] : 'Record Video';
						include self::sptp_locate_template( 'form/video-url.php' );
					}
					break;
				case 'social_profile':
					if ( in_array( 'social_profile', $form_element, true ) ) {
						$social_profile_label = isset( $social_profile['label'] ) ? $social_profile['label'] : '';
						include self::sptp_locate_template( 'form/social-profile.php' );
					}
					break;
				case 'rating':
					if ( in_array( 'rating', $form_element, true ) ) {
						$rating_label = isset( $rating['label'] ) ? $rating['label'] : '';
						include self::sptp_locate_template( 'form/rating.php' );
					}
					break;
				case 'agree_checkbox':
					if ( in_array( 'agree_checkbox', $form_element, true ) ) {
						$checkbox_label    = isset( $agree_checkbox['label'] ) ? $agree_checkbox['label'] : '';
						$checkbox_required = isset( $agree_checkbox['required'] ) && $agree_checkbox['required'] ? 'required' : '';
						include self::sptp_locate_template( 'form/checkbox.php' );
					}
					break;
				case 'recaptcha':
					if ( in_array( 'recaptcha', $form_element, true ) && '' !== $setting_options['captcha_site_key'] && '' !== $setting_options['captcha_secret_key'] && 'v2' === $captcha_version ) {
						$recaptcha_label = isset( $recaptcha['label'] ) ? $recaptcha['label'] : '';
						include self::sptp_locate_template( 'form/recaptcha.php' );
					}
					break;
				case 'submit_btn':
					include self::sptp_locate_template( 'form/submit-btn.php' );
					break;
			}
		}
		?>

		<script>

		</script>
<?php
if ( ! empty( $validation_msg ) && ( 'bottom' === $form_data['tpro_message_position'] ) ) {
	include self::sptp_locate_template( 'form/validation-msg.php' );
}
?>
	</form>
</div>
