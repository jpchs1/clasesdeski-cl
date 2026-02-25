<?php
/**
 * Mounthood Framework: messages subsystem
 *
 * @package	mounthood
 * @since	mounthood 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('mounthood_messages_theme_setup')) {
	add_action( 'mounthood_action_before_init_theme', 'mounthood_messages_theme_setup' );
	function mounthood_messages_theme_setup() {
		// Core messages strings
		add_filter('mounthood_filter_localize_script', 'mounthood_messages_localize_script');
	}
}


/* Session messages
------------------------------------------------------------------------------------- */

if (!function_exists('mounthood_get_error_msg')) {
	function mounthood_get_error_msg() {
		return mounthood_storage_get('error_msg');
	}
}

if (!function_exists('mounthood_set_error_msg')) {
	function mounthood_set_error_msg($msg) {
		$msg2 = mounthood_get_error_msg();
		mounthood_storage_set('error_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('mounthood_get_success_msg')) {
	function mounthood_get_success_msg() {
		return mounthood_storage_get('success_msg');
	}
}

if (!function_exists('mounthood_set_success_msg')) {
	function mounthood_set_success_msg($msg) {
		$msg2 = mounthood_get_success_msg();
		mounthood_storage_set('success_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('mounthood_get_notice_msg')) {
	function mounthood_get_notice_msg() {
		return mounthood_storage_get('notice_msg');
	}
}

if (!function_exists('mounthood_set_notice_msg')) {
	function mounthood_set_notice_msg($msg) {
		$msg2 = mounthood_get_notice_msg();
		mounthood_storage_set('notice_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}


/* System messages (save when page reload)
------------------------------------------------------------------------------------- */
if (!function_exists('mounthood_set_system_message')) {
	function mounthood_set_system_message($msg, $status='info', $hdr='') {
		update_option(mounthood_storage_get('options_prefix') . '_message', array('message' => $msg, 'status' => $status, 'header' => $hdr));
	}
}

if (!function_exists('mounthood_get_system_message')) {
	function mounthood_get_system_message($del=false) {
		$msg = get_option(mounthood_storage_get('options_prefix') . '_message', false);
		if (!$msg)
			$msg = array('message' => '', 'status' => '', 'header' => '');
		else if ($del)
			mounthood_del_system_message();
		return $msg;
	}
}

if (!function_exists('mounthood_del_system_message')) {
	function mounthood_del_system_message() {
		delete_option(mounthood_storage_get('options_prefix') . '_message');
	}
}


/* Messages strings
------------------------------------------------------------------------------------- */

if (!function_exists('mounthood_messages_localize_script')) {
	//Handler of add_filter('mounthood_filter_localize_script', 'mounthood_messages_localize_script');
	function mounthood_messages_localize_script($vars) {
		$vars['strings'] = array(
			'ajax_error'		=> esc_html__('Invalid server answer', 'mounthood'),
			'bookmark_add'		=> esc_html__('Add the bookmark', 'mounthood'),
            'bookmark_added'	=> esc_html__('Current page has been successfully added to the bookmarks. You can see it in the right panel on the tab \'Bookmarks\'', 'mounthood'),
            'bookmark_del'		=> esc_html__('Delete this bookmark', 'mounthood'),
            'bookmark_title'	=> esc_html__('Enter bookmark title', 'mounthood'),
            'bookmark_exists'	=> esc_html__('Current page already exists in the bookmarks list', 'mounthood'),
			'search_error'		=> esc_html__('Error occurs in AJAX search! Please, type your query and press search icon for the traditional search way.', 'mounthood'),
			'email_confirm'		=> esc_html__('On the e-mail address "%s" we sent a confirmation email. Please, open it and click on the link.', 'mounthood'),
			'reviews_vote'		=> esc_html__('Thanks for your vote! New average rating is:', 'mounthood'),
			'reviews_error'		=> esc_html__('Error saving your vote! Please, try again later.', 'mounthood'),
			'error_like'		=> esc_html__('Error saving your like! Please, try again later.', 'mounthood'),
			'error_global'		=> esc_html__('Global error text', 'mounthood'),
			'name_empty'		=> esc_html__('The name can\'t be empty', 'mounthood'),
			'name_long'			=> esc_html__('Too long name', 'mounthood'),
			'email_empty'		=> esc_html__('Too short (or empty) email address', 'mounthood'),
			'email_long'		=> esc_html__('Too long email address', 'mounthood'),
			'email_not_valid'	=> esc_html__('Invalid email address', 'mounthood'),
			'subject_empty'		=> esc_html__('The subject can\'t be empty', 'mounthood'),
			'subject_long'		=> esc_html__('Too long subject', 'mounthood'),
			'text_empty'		=> esc_html__('The message text can\'t be empty', 'mounthood'),
			'text_long'			=> esc_html__('Too long message text', 'mounthood'),
			'send_complete'		=> esc_html__("Send message complete!", 'mounthood'),
			'send_error'		=> esc_html__('Transmit failed!', 'mounthood'),
			'geocode_error'			=> esc_html__('Geocode was not successful for the following reason:', 'mounthood'),
			'googlemap_not_avail'	=> esc_html__('Google map API not available!', 'mounthood'),
			'editor_save_success'	=> esc_html__("Post content saved!", 'mounthood'),
			'editor_save_error'		=> esc_html__("Error saving post data!", 'mounthood'),
			'editor_delete_post'	=> esc_html__("You really want to delete the current post?", 'mounthood'),
			'editor_delete_post_header'	=> esc_html__("Delete post", 'mounthood'),
			'editor_delete_success'	=> esc_html__("Post deleted!", 'mounthood'),
			'editor_delete_error'	=> esc_html__("Error deleting post!", 'mounthood'),
			'editor_caption_cancel'	=> esc_html__('Cancel', 'mounthood'),
			'editor_caption_close'	=> esc_html__('Close', 'mounthood')
			);
		return $vars;
	}
}
?>