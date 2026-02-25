<?php
/**
 * External Sources Twitter Class
 * @package   Essential_Grid
 * @author    ThemePunch <info@themepunch.com>
 * @link      http://www.themepunch.com/essential/
 * @copyright 2021 ThemePunch
 * @since: 3.0.13
 **/

if (!defined('ABSPATH')) die();


/**
 * Twitter
 *
 * with help of the API this class delivers all kind of tweeted images from twitter
 *
 * @package    socialstreams
 * @subpackage socialstreams/twitter
 * @author     ThemePunch <info@themepunch.com>
 */
class Essential_Grid_Twitter
{

	/**
	 * Consumer Key
	 *
	 * @since    3.0
	 * @access   private
	 * @var      string $consumer_key Consumer Key
	 */
	private $consumer_key;

	/**
	 * Consumer Secret
	 *
	 * @since    3.0
	 * @access   private
	 * @var      string $consumer_secret Consumer Secret
	 */
	private $consumer_secret;

	/**
	 * Access Token
	 *
	 * @since    3.0
	 * @access   private
	 * @var      string $access_token Access Token
	 */
	private $access_token;

	/**
	 * Access Token Secret
	 *
	 * @since    3.0
	 * @access   private
	 * @var      string $access_token_secret Access Token Secret
	 */
	private $access_token_secret;

	/**
	 * Twitter Account
	 *
	 * @since    3.0
	 * @access   private
	 * @var      string $twitter_account Account User Name
	 */
	private $twitter_account;

	/**
	 * Stream Array
	 *
	 * @since    3.0
	 * @access   private
	 * @var      array $stream Stream Data Array
	 */
	private $stream;

	/**
	 * Transient seconds
	 *
	 * @since    3.0
	 * @access   private
	 * @var      number $transient Transient time in seconds
	 */
	private $transient_sec;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $consumer_key Twitter App Registration Consomer Key
	 * @param string $consumer_secret Twitter App Registration Consomer Secret
	 * @param string $access_token Twitter App Registration Access Token
	 * @param string $access_token_secret Twitter App Registration Access Token Secret
	 * @since    3.0
	 */
	public function __construct($consumer_key, $consumer_secret, $access_token, $access_token_secret, $transient_sec = 86400)
	{
		$this->consumer_key = $consumer_key;
		$this->consumer_secret = $consumer_secret;
		$this->access_token = $access_token;
		$this->access_token_secret = $access_token_secret;
		$this->transient_sec = $transient_sec;
	}

	/**
	 * Get Tweets
	 *
	 * @param string $twitter_account Twitter account without trailing @ char
	 * @since    3.0
	 */
	public function get_public_photos($twitter_account, $include_rts, $exclude_replies, $count, $imageonly)
	{
		$credentials = array(
			'consumer_key' => $this->consumer_key,
			'consumer_secret' => $this->consumer_secret
		);

		$this->twitter_account = $twitter_account;

		// Let's instantiate our class with our credentials
		$twitter_api = new EssGridTwitterApi($credentials, $this->transient_sec);

		$include_rts = $include_rts == "on" ? "true" : "false";
		$exclude_replies = $exclude_replies == "on" ? "true" : "false";

		$query = '&tweet_mode=extended&count=150&include_entities=true&include_rts=' . $include_rts . '&exclude_replies=' . $exclude_replies . '&screen_name=' . $twitter_account;
		$security = 50;
		$supervisor_count = 0;

		while ($count > 0 && $security > 0 && $supervisor_count < 20) {

			//get last stream array element and insert ID with max_id parameter
			$supervisor_count++;

			if (is_array($this->stream)) {
				$current_query = $query . "&max_id=" . $this->stream[sizeof($this->stream) - 1]["tweet_id"];
			} else {
				$current_query = $query;
			}

			$tweets = $twitter_api->query($current_query);
			$count = $this->twitter_output_array($tweets, $count, $imageonly);

			$security--;
		}

		return $this->stream;
	}

	/**
	 * Find Key in array and return value (multidim array possible)
	 *
	 * @param string $key Needle
	 * @param array $form Haystack
	 * @since    3.0
	 */
	public function array_find_element_by_key($key, $form)
	{
		if (is_array($form) && array_key_exists($key, $form)) {
			$ret = $form[$key];
			return $ret;
		}
		if (is_array($form))
			foreach ($form as $k => $v) {
				if (is_array($v)) {
					$ret = $this->array_find_element_by_key($key, $form[$k]);
					if ($ret) {
						return $ret;
					}
				}
			}
		return FALSE;
	}

	/**
	 * Prepare output array $stream
	 *
	 * @param string $tweets Twitter Output Data
	 * @since    3.0
	 */
	private function twitter_output_array($tweets, $count, $imageonly)
	{
		if (is_array($tweets)) {

			foreach ($tweets as $tweet) {

				$stream = array();
				$image_url = array();
				if ($count < 1) break;

				$image_url_array = $this->array_find_element_by_key("media", $tweet);
				$image_url_large = $this->array_find_element_by_key("large", $image_url_array);

				if (isset($tweet->entities->media[0])) {
					$image_url = array($tweet->entities->media[0]->media_url_https, $tweet->entities->media[0]->sizes->large->w, $tweet->entities->media[0]->sizes->large->h);
				}

				$stream['custom-image-url'] = $image_url; //image for entry
				$stream['custom-image-url-full'] = $image_url; //image for entry
				$stream['custom-type'] = isset($image_url[0]) ? 'image' : 'html';
				if ($imageonly == "true" && $stream['custom-type'] == 'html') continue;
				$stream['custom-type'] = 'image';

				$content_array = explode("https://t.co", $tweet->full_text);
				if (sizeof($content_array) > 1) array_pop($content_array);
				$content = implode("https://t.co", $content_array);

				$url = '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i';
				$content = preg_replace($url, '<a href="$0" target="_blank" title="$0">$0</a>', $content);


				$stream['title'] = $content;
				$stream['content'] = $content;
				$stream['date'] = date_i18n(get_option('date_format'), strtotime($tweet->created_at));
				$stream['date_modified'] = date_i18n(get_option('date_format'), strtotime($tweet->created_at));
				$stream['author_name'] = $tweet->user->screen_name;
				$stream['post-link'] = 'https://twitter.com/' . $this->twitter_account . '/status/' . $tweet->id_str;

				$stream['retweets'] = $tweet->retweet_count;
				$stream['retweets_short'] = Essential_Grid_Base::thousandsViewFormat($tweet->retweet_count);
				$stream['likes'] = $tweet->favorite_count;
				$stream['likes_short'] = Essential_Grid_Base::thousandsViewFormat($tweet->favorite_count);
				$stream['tweet_id'] = $tweet->id;
				$stream['id'] = $tweet->id;
				$this->stream[] = $stream;
				$count--;
			}
			return $count;
		} else {
			return false;
		}
	}
}

/**
 * Class WordPress Twitter API
 *
 * https://github.com/micc83/Twitter-API-1.1-Client-for-Wordpress/blob/master/class-wp-twitter-api.php
 * @version 1.0.0
 * @since   3.0
 */
class EssGridTwitterApi
{

	var $bearer_token,
		// Default credentials
		$args = array(
		'consumer_key' => 'default_consumer_key',
		'consumer_secret' => 'default_consumer_secret'
	),
		// Default type of the resource and cache duration
		$query_args = array(
		'type' => 'statuses/user_timeline',
		'cache' => 1800
	),
		$has_error = false;

	/**
	 * WordPress Twitter API Constructor
	 *
	 * @param array $args
	 */
	public function __construct($args = array(), $transient_sec = 0)
	{
		if (is_array($args) && !empty($args))
			$this->args = array_merge($this->args, $args);

		if (!$this->bearer_token = get_option('twitter_bearer_token'))
			$this->bearer_token = $this->get_bearer_token();

		$this->query_args['cache'] = $transient_sec;
	}

	/**
	 * Get the token from oauth Twitter API
	 *
	 * @return string Oauth Token
	 */
	private function get_bearer_token()
	{
		$bearer_token_credentials = $this->args['consumer_key'] . ':' . $this->args['consumer_secret'];
		$bearer_token_credentials_64 = base64_encode($bearer_token_credentials);

		$args = array(
			'method' => 'POST',
			'timeout' => 5,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(
				'Authorization' => 'Basic ' . $bearer_token_credentials_64,
				'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8',
				'Accept-Encoding' => 'gzip'
			),
			'body' => array('grant_type' => 'client_credentials'),
			'cookies' => array()
		);

		$response = wp_remote_post('https://api.twitter.com/oauth2/token', $args);
		if (is_wp_error($response) || 200 != $response['response']['code']) {
			return 'esg_stream_failure';
		}
		$result = json_decode($response['body']);

		update_option('twitter_bearer_token', $result->access_token);

		return $result->access_token;
	}

	/**
	 * Query twitter's API
	 *
	 * @param string $query Insert the query in the format "count=1&include_entities=true&include_rts=true&screen_name=micc1983!
	 * @param array $query_args Array of arguments: Resource type (string) and cache duration (int)
	 * @param bool $stop Stop the query to avoid infinite loop
	 *
	 * @return bool|object Return an object containing the result
	 * @uses $this->get_bearer_token() to retrieve token if not working
	 *
	 */
	public function query($query, $query_args = array(), $stop = false)
	{
		if ($this->has_error)
			return false;

		if (is_array($query_args) && !empty($query_args))
			$this->query_args = array_merge($this->query_args, $query_args);

		$clear_cache = Essential_Grid_Base::getPostVar(array('data', 'clear_cache'), '');
		$transient_name = 'essgrid_' . md5($query . '&sec='.$this->query_args['cache']);
		if ($clear_cache != 'twitter' && false !== ($data = get_transient($transient_name))) {
			return json_decode($data);
		}

		$args = array(
			'method' => 'GET',
			'timeout' => 5,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(
				'Authorization' => 'Bearer ' . $this->bearer_token,
				'Accept-Encoding' => 'gzip'
			),
			'body' => null,
			'cookies' => array()
		);

		$response = wp_remote_get('https://api.twitter.com/1.1/' . $this->query_args['type'] . '.json?' . $query, $args);
		if (is_wp_error($response) || 200 != $response['response']['code']) {

			if (!$stop) {
				$this->bearer_token = $this->get_bearer_token();
				return $this->query($query, $this->query_args, true);
			} else {
				return 'esg_stream_failure';
			}

		}
		set_transient($transient_name, $response['body'], $this->query_args['cache']);

		return json_decode($response['body']);
	}

	/**
	 * Let's manage errors
	 *
	 * WP_DEBUG has to be set to true to show errors
	 *
	 * @param string $error_text Error message
	 * @param string $error_object Server response or wp_error
	 */
	private function bail($error_text, $error_object = '')
	{
		$this->has_error = true;

		if (is_wp_error($error_object)) {
			$error_text .= ' - Wp Error: ' . $error_object->get_error_message();
		} elseif (!empty($error_object) && isset($error_object['response']['message'])) {
			$error_text .= ' ( Response: ' . $error_object['response']['message'] . ' )';
		}

		return false;
	}

}
