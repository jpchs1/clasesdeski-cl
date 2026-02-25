<?php
/**
 * Social profile.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/form/social-profile.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

?>
<div class="sp-tpro-form-field">
	<?php
	if ( $social_profile_label ) {
		?>
	<label for="tpro_social_profile"><?php echo esc_html( $social_profile_label ); ?></label><br>
	<?php } ?>
	<div class="tpro-social-profile-wrapper">
		<div id="tpro-social-profiles">
			<div class="tpro-social-profile-item" data-group="tpro_social_profiles">
				<div class="tpro-social-profile-content">
					<div class="tpro-social-name-field">
						<select class="tpro-social-name" data-name="social_name" placeholder="Select">
							<option value="">Select</option>
							<?php
							if ( $social_profile_list ) {
								foreach ( $social_profile_list as $profile ) {
									echo '<option value="' . esc_attr( $profile ) . '">' . esc_html( ucfirst( $profile ) ) . '</option>';
								}
							} else {
								echo $tpro_function->social_profile_name_list();
							}
							?>
						</select>
					</div>
					<div class="tpro-social-url-field">
						<input type="text" class="tpro-social-url" data-name="social_url" placeholder="Social URL">
					</div>
				</div>
				<div class="tpro-social-remove"><i class="tpro-social-profile-remove fa fa-times"></i></div>
			</div>
		</div>
		<span class="tpro-add-new-profile-btn">Add New</span>
	</div>
</div>
