<?php
/**
 * @author    ThemePunch <info@themepunch.com>
 * @link      http://www.themepunch.com/
 * @copyright 2021 ThemePunch
 * @version   1.0.0
 */

if (!defined('ABSPATH')) exit();

if (!class_exists('ThemePunch_Newsletter')) {
	 
	class ThemePunch_Newsletter
	{
		protected static $remote_url = 'http://newsletter.themepunch.com/';
		protected static $subscribe = 'subscribe.php';
		protected static $unsubscribe = 'unsubscribe.php';

		public function __construct()
		{
		}
		
		private static function _call_newsletter_server($url, $data)
		{
			global $esg_loadbalancer;
			
			$request = $esg_loadbalancer->call_url($url, $data);
			if (is_wp_error($request)) return false;

			$response = wp_remote_retrieve_body($request);
			if ($response = json_decode($response, true)) {
				return is_array($response) ? $response :  false;
			}

			return false;
		}

		/**
		 * Subscribe to the ThemePunch Newsletter
		 * @since: 1.0.0
		 **/
		public static function subscribe($email)
		{
			$data = array(
				'email' => urlencode($email),
			);
			return self::_call_newsletter_server(self::$remote_url . self::$subscribe, $data);
		}

		/**
		 * Unsubscribe to the ThemePunch Newsletter
		 * @since: 1.0.0
		 **/
		public static function unsubscribe($email)
		{
			$data = array(
				'email' => urlencode($email),
			);
			return self::_call_newsletter_server(self::$remote_url . self::$unsubscribe, $data);
		}

	}
}
