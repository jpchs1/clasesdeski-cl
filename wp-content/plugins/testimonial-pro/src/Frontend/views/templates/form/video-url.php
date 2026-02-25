<?php
/**
 * Video url.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/form/video-url.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */
if ( $show_video_by_url ) {
	?>
<div class="sp-tpro-form-field">
	<?php if ( $video_label ) { ?>
	<label for="tpro_client_video_url<?php echo esc_attr( $form_id ); ?>"><?php echo esc_html( $video_label ); ?></label><br>
	<?php } ?>

	<input type="text" name="tpro_client_video_url" id="tpro_client_video_url<?php echo esc_attr( $form_id ); ?>" <?php echo esc_html( $video_url_required ); ?> placeholder="<?php echo esc_attr( $video_url['placeholder'] ); ?>" />

</div>
<?php } ?>
<?php if ( $show_record_video ) { ?>
<div class="sp-tpro-form-field">
	<?php
	if ( $video_record_label ) {
		?>
	<label for="tpro_client_video_upload"><?php echo esc_html( $video_record_label ); ?></label><br>
		<?php
	}
	?>
	<div class="sp-testimonial-video-wrapper" style="display: none;"><video playsinline controls src="" type="video/mp4"></video></div>
	<a href="#" id="tpro_modal_btn"><i class="fa fa-video-camera" aria-hidden="true"></i><?php echo esc_html( $record_btn_text ); ?></a>
	<input type="file" name="tpro_client_video_upload" <?php echo esc_html( $video_url_required ); ?> id="tpro_client_video_upload" accept="video/mp4, video/x-m4v,video/webm,video/*" />
</div>
<!-- The Modal -->
<div id="tpro_video_modal" class="tpro_video_modal">
	<div class="tpro_background"></div>
	<!-- Modal content -->
	<div class="tpro_modal-content-wrapper">

		<div class="tpro_modal-content">
			<span class="tpro_modal_close">&times;</span>
			<div class="tpro_modal-content-inner">
				<h3 class="text-center"><img src="<?php echo SP_TPRO_URL . 'Frontend/assets/images/video-icon.svg'; ?>" alt=""><?php echo apply_filters( 'tpro_upper_record_text', __( 'Record Review', 'testimonial-pro' ) ); ?></h3>
				<div class="tpro_preview-recording">
					<div id="tpro_timer"><span id="tpro_timer-text" data-maxtime=<?php echo $recording_time; ?> style="display: none;">05:00</span></div>
					<video playsinline id="tpro_preview" width="450" height="337"  autoplay="" muted="" style="display: none;"></video>
					<video playsinline id="tpro_recording" width="450" height="337" controls style="display: none;"></video>
					<div class="tpro_no_camera text-center" style="display: none;">
						<div class="camera_inner">
							<img src="<?php echo SP_TPRO_URL . 'Frontend/assets/images/no-camera.svg'; ?>" alt="">
							<div><?php echo apply_filters( 'tpro_no_camera_text', __( 'No camera available', 'testimonial-pro' ) ); ?></div>
						</div>
					</div>
				</div>
				<div class="tpro_record_video_buttons">
					<div id="tpro_startButton" class="tpro_video_button" style="display: none;">
					<i class="fa fa-video-camera" aria-hidden="true"></i><span id="tpro_startButton_text">
							<?php echo apply_filters( 'tpro_start_recording_btn_text', __( 'Start Recording', 'testimonial-pro' ) ); ?>
						</span>
					</div>
					<div id="tpro_stopButton" class="tpro_video_button stop_recording_btn" style="display: none;">
					<i class="fa fa-stop-circle-o" aria-hidden="true"></i>
						<?php echo apply_filters( 'tpro_stop_recording_btn_text', __( 'Stop Recording', 'testimonial-pro' ) ); ?>
					</div>
					<a id="tpro_addButton" class="tpro_video_button add_video_btn" style="display: none;">
					<i class="fa fa-plus-circle" aria-hidden="true"></i>
						<?php echo apply_filters( 'tpro_add_video_btn_text', __( 'Add this video', 'testimonial-pro' ) ); ?>
					</a>
				</div>
			</div>
			<p class="tpro_modal-content-bottom text-center">
				<?php
					$tpro_video_duration_unit = $recording_time >= 2 ? __( 'minutes', 'testimonial-pro' ) : __( 'minute', 'testimonial-pro' );
				?>
				<span><?php echo apply_filters( 'tpro_video_duration_text', __( 'Maximum recording duration', 'testimonial-pro' ) ) . ' ' . $recording_time . ' ' . apply_filters( 'tpro_video_duration_unit', $tpro_video_duration_unit ); ?></span>
			</p>
		</div>
	</div>
</div>
<?php } ?>
