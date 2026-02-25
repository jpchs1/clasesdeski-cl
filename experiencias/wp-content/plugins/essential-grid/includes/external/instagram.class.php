<?php
/**
 * External Sources Instagram Class
 * @package   Essential_Grid
 * @author    ThemePunch <info@themepunch.com>
 * @link      http://www.themepunch.com/essential/
 * @copyright 2021 ThemePunch
 * @since: 3.0.13
 **/

if (!defined('ABSPATH')) die();

use EspressoDev\ESG_InstagramBasicDisplay as InstagramBasicDisplay;

/**
 * Instagram
 *
 * with help of the API this class delivers all kind of Images from instagram
 *
 * @package    socialstreams
 * @subpackage socialstreams/instagram
 * @author     ThemePunch <info@themepunch.com>
 */
class Essential_Grid_Instagram
{

	const QUERY_SHOW = 'ig_esg_show';
	const QUERY_TOKEN = 'ig_token';
	const QUERY_CONNECTWITH = 'ig_user';
	const QUERY_ERROR = 'ig_error_message';
	const QUERY_ESG_ERROR = 'ig_esg_error';

	/**
	 * API key
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $api_key Instagram API key
	 */
	private $api_key;

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
	 * @var array of InstagramBasicDisplay objects
	 */
	private $instagram;

	/**
	 * Transient for token refresh in seconds
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      number $transient_token_sec Transient time in seconds
	 */
	private $transient_token_sec;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $api_key Instagram API key.
	 * @since    3.0
	 */
	public function __construct($transient_sec = 86400)
	{
		$this->transient_sec = $transient_sec;
		$this->transient_token_sec = 86400 * 30; // 30 days
	}

	public function add_actions()
	{
		add_action('init', array(&$this, 'do_init'), 4);
		add_action('admin_footer', array(&$this, 'footer_js'));
	}

	/**
	 * check if we have QUERY_TOKEN set
	 */
	public function do_init()
	{
		// we are not on esg page
		if (!isset($_GET['page']) || $_GET['page'] != ESG_PLUGIN_SLUG) return;

		//rename error var to avoid conflict with revslider
		if (isset($_GET[self::QUERY_ERROR])) {
			$url = set_url_scheme('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
			$url = add_query_arg([self::QUERY_ERROR => false, self::QUERY_ESG_ERROR => $_GET[self::QUERY_ERROR]], $url);
			wp_redirect($url);
			exit();
		}

		if (
			!isset($_GET['page']) || $_GET['page'] != ESG_PLUGIN_SLUG // we are not on esg page
			|| isset($_GET[self::QUERY_ERROR]) //instagram api error
			|| !isset($_GET[self::QUERY_TOKEN]) // no token
			|| !isset($_GET['create']) // no grid id
		)
			return;

		$token = $_GET[self::QUERY_TOKEN];
		$connectwith = $_GET[self::QUERY_CONNECTWITH];
		$id = $_GET['create'];

		$grid = Essential_Grid::get_essential_grid_by_id(intval($id));
		if (empty($grid)) {
			$_GET[self::QUERY_ERROR] = esc_attr__('Grid could not be loaded', ESG_TEXTDOMAIN);
			return;
		}

		//update grid
		$grid['postparams']['instagram-api-key'] = $token;
		$grid['postparams']['instagram-connected-to'] = $connectwith;
		Essential_Grid_Admin::update_create_grid($grid);

		//clear cache
		Essential_Grid_Base::clear_transients('ess_grid_trans_query_' . $grid['id']);
		Essential_Grid_Base::clear_transients('ess_grid_trans_full_grid_' . $grid['id']);

		//redirect
		$url = set_url_scheme('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
		$url = add_query_arg([self::QUERY_TOKEN => false, self::QUERY_SHOW => 1], $url);
		wp_redirect($url);
		exit();
	}

	public function footer_js()
	{
		// we are not on esg page
		if (!isset($_GET['page']) || $_GET['page'] != ESG_PLUGIN_SLUG) return;

		//switch to source tab
		if (isset($_GET[self::QUERY_SHOW])) {
			echo "<script>jQuery(document).ready(function(){ setTimeout(function(){jQuery('.selected-source-setting').trigger('click');}, 500); });</script>";
		}

		//show error
		if (isset($_GET[self::QUERY_ESG_ERROR])) {
			$err = esc_attr__('Instagram Reports: ', ESG_TEXTDOMAIN) . $_GET[self::QUERY_ESG_ERROR];
			echo '<script>jQuery(document).ready(function(){ AdminEssentials.showInfo({content: "' . $err . '", type: "warning", showdelay: 0, hidedelay: 0, hideon: "click", event: ""}) });</script>';
		}
	}

	public static function get_login_url()
	{
		$app_id = '677807423170942';
		$redirect = 'https://updates.themepunch.tools/ig/auth.php';
		$create = isset($_GET['create']) ? $_GET['create'] : '';
		$state = base64_encode(admin_url('admin.php?page=' . ESG_PLUGIN_SLUG . '&view=grid-create&create=' . $create));
		return sprintf(
			'https://api.instagram.com/oauth/authorize?app_id=%s&redirect_uri=%s&response_type=code&scope=user_profile,user_media&state=%s',
			$app_id,
			$redirect,
			$state
		);
	}

	/**
	 * return instagram api object
	 *
	 * @param string $token
	 * @return InstagramBasicDisplay
	 */
	public function getInstagram($token)
	{
		if (empty($this->instagram[$token])) {
			$this->instagram[$token] = new InstagramBasicDisplay($token);
		}
		return $this->instagram[$token];
	}

	/**
	 * refresh Instagram token if needed
	 *
	 * @param string $token Instagram Access Token
	 * @return mixed
	 */
	protected function _refresh_token($token)
	{
		$transient_token_name = 'revslider_insta_token_' . md5($token);
		if ($this->transient_token_sec > 0 && false !== ($data = get_transient($transient_token_name))) {
			return;
		}

		$instagram = $this->getInstagram($token);
		//$refresh contain new token, however old token expiry date also updated, so we could still use it
		$refresh = $instagram->refreshToken($token);
		set_transient($transient_token_name, $token, $this->transient_token_sec);
	}

	/**
	 * get grid transient name
	 *
	 * @param int $grid_handle grid handle
	 * @param string $token
	 * @param int $count
	 */
	public function get_esg_transient_name($grid_handle, $token, $count)
	{
		$cacheKey = 'instagram' . '-' . $grid_handle . '-' . $token . '-' . $count;
		return 'essgrid_' . md5($cacheKey);
	}

	/**
	 * clear grid transient
	 *
	 * @param int $grid_handle grid handle
	 * @param string $token
	 * @param int $count
	 */
	public function clear_esg_transient($grid_handle, $token, $count)
	{
		$transient_name = $this->get_esg_transient_name($grid_handle, $token, $count);
		delete_transient($transient_name);
	}

	/**
	 * Get Instagram User Pictures
	 *
	 * @param int $grid_handle grid handle
	 * @param string $token Instagram Access Token
	 * @param string $count media count
	 * @param string $orig_image
	 * @return mixed
	 * @since    3.0
	 */
	public function get_public_photos($grid_handle, $token, $count, $orig_image)
	{

		if (empty($token)) {
			return 'esg_stream_failure';
		}

		//Getting instragram images
		$this->_refresh_token($token);
		$instagram = $this->getInstagram($token);

		$clear_cache = Essential_Grid_Base::getPostVar(array('data', 'clear_cache'), '');
		$transient_name = $this->get_esg_transient_name($grid_handle, $token, $count);
		if ($clear_cache != 'instagram' && false !== ($data = get_transient($transient_name))) {
			$this->stream = $data;
			return $this->stream;
		}

		//Getting instagram images
		$medias = $instagram->getUserMedia('me', $count);

		if (isset($medias->data)) {
			$this->instagram_output_array($medias->data, $count, $orig_image);
		}
		if (!empty($this->stream)) {
			set_transient($transient_name, $this->stream, $this->transient_sec);
			return $this->stream;
		}
		
		return 'esg_stream_failure';
	}

	/**
	 * Prepare output array $stream
	 *
	 * @param string $photos Instagram Output Data
	 * @since    3.0
	 */
	private function instagram_output_array($photos, $count, $orig_image)
	{
		foreach ($photos as $photo) {
			$text = empty($photo->caption) ? '' : $photo->caption;

			$stream['id'] = $photo->id;

			if ($photo->media_type != "VIDEO") {
				$stream['custom-type'] = 'image'; //image, vimeo, youtube, soundcloud, html
				$stream['custom-image-url'] = $photo->media_url; //image for entry
				$stream['custom-html5-mp4'] = '';
			} else {
				$stream['custom-type'] = 'html5'; //image, vimeo, youtube, soundcloud, html
				$stream['custom-html5-mp4'] = $photo->media_url;
				$stream['custom-image-url'] = $photo->thumbnail_url; //image for entry
			}

			$stream['post-link'] = $photo->permalink;
			$url = '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i';
			$text = preg_replace($url, '<a href="$0" target="_blank" title="$0">$0</a>', $text);
			$stream['title'] = $text;
			$stream['content'] = $text;
			$stream['date_modified'] = $photo->timestamp;
			$stream['date'] = date_i18n(get_option('date_format'), strtotime($photo->timestamp));
			$stream['author_name'] = $photo->username;

			$this->stream[] = $stream;
		}
		return $count;
	}

}
