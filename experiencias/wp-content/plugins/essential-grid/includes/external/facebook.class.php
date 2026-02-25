<?php
/**
 * External Sources Facebook Class
 * @package   Essential_Grid
 * @author    ThemePunch <info@themepunch.com>
 * @link      http://www.themepunch.com/essential/
 * @copyright 2021 ThemePunch
 * @since: 3.0.13
 **/

if (!defined('ABSPATH')) die();

/**
 * Facebook
 *
 * with help of the API this class delivers album images from Facebook
 *
 * @package    socialstreams
 * @subpackage socialstreams/facebook
 * @author     ThemePunch <info@themepunch.com>
 */
class Essential_Grid_Facebook
{
	
	const TRANSIENT_PREFIX = 'essgrid_fb_';

	const URL_FB_AUTH = 'https://updates.themepunch.tools/fb/login.php';
	const URL_FB_API = 'https://updates.themepunch.tools/fb/api.php';

	const QUERY_SHOW = 'fb_show';
	const QUERY_TOKEN = 'fb_token';
	const QUERY_PAGE_ID = 'fb_page_id';
	const QUERY_CONNECTWITH = 'fb_page_name';
	const QUERY_ERROR = 'fb_error_message';

	/**
	 * @var number  Transient time in seconds
	 */
	private $transient_sec;

	public function __construct($transient_sec = 86400)
	{
		$this->transient_sec = $transient_sec;
	}

	public function add_actions()
	{
		add_action('init', array(&$this, 'do_init'), 5);
		add_action('admin_footer', array(&$this, 'footer_js'));
		add_action('essgrid_on_delete_grid_by_id', array(&$this, 'on_delete_grid'), 10, 2);
	}

	/**
	 * check if we have QUERY_ARG set
	 * try to login the user
	 */
	public function do_init()
	{
		// are we on essential-grid page?
		if (!isset($_GET['page']) || $_GET['page'] != ESG_PLUGIN_SLUG) return;

		//fb returned error
		if (isset($_GET[self::QUERY_ERROR])) return;

		//we need token and grid ID to proceed with saving token
		if (!isset($_GET[self::QUERY_TOKEN]) || !isset($_GET['create'])) return;

		$token = $_GET[self::QUERY_TOKEN];
		$connectwith = isset($_GET[self::QUERY_CONNECTWITH]) ? $_GET[self::QUERY_CONNECTWITH] : '';
		$page_id = isset($_GET[self::QUERY_PAGE_ID]) ? $_GET[self::QUERY_PAGE_ID] : '';
		$id = $_GET['create'];

		$grid = Essential_Grid::get_essential_grid_by_id(intval($id));
		if (empty($grid)) {
			$_GET[self::QUERY_ERROR] = esc_attr__('Grid could not be loaded', ESG_TEXTDOMAIN);
			return;
		}

		$grid['postparams']['facebook-token-source'] = 'account';
		$grid['postparams']['facebook-access-token'] = $token;
		$grid['postparams']['facebook-page-id'] = $page_id;
		$grid['postparams']['facebook-connected-to'] = $connectwith;
		Essential_Grid_Admin::update_create_grid($grid);

		//clear cache
		Essential_Grid_Base::clear_transients('ess_grid_trans_query_' . $grid['id']);
		Essential_Grid_Base::clear_transients('ess_grid_trans_full_grid_' . $grid['id']);

		//redirect
		$url = set_url_scheme('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
		$url = add_query_arg([self::QUERY_TOKEN => false, self::QUERY_PAGE_ID => false, self::QUERY_CONNECTWITH => false, self::QUERY_SHOW => 1], $url);
		wp_redirect($url);
		exit();
	}

	public function footer_js()
	{
		// are we on essential-grid page?
		if (!isset($_GET['page']) || $_GET['page'] != ESG_PLUGIN_SLUG) return;

		if (isset($_GET[self::QUERY_SHOW]) || isset($_GET[self::QUERY_ERROR])) {
			echo "<script>jQuery(document).ready(function(){ setTimeout(function(){jQuery('.selected-source-setting').trigger('click');}, 500); });</script>";
		}

		if (isset($_GET[self::QUERY_ERROR])) {
			$err = esc_attr__('Facebook API error: ', ESG_TEXTDOMAIN) . $_GET[self::QUERY_ERROR];
			echo '<script>jQuery(document).ready(function(){ AdminEssentials.showInfo({content: "' . $err . '", type: "warning", showdelay: 0, hidedelay: 0, hideon: "click", event: ""}) });</script>';
		}
	}

	public static function get_login_url()
	{
		$create = isset($_GET['create']) ? $_GET['create'] : '';
		$state = base64_encode(admin_url('admin.php?page=' . ESG_PLUGIN_SLUG . '&view=grid-create&create=' . $create));
		return self::URL_FB_AUTH . '?state=' . $state;
	}
	
	public function get_transient_name($data)
	{
		return self::TRANSIENT_PREFIX . $data['grid_id'] . '_' . md5(json_encode($data));
	}

	protected function _make_api_call($args = [])
	{
		global $esg_loadbalancer;

		$request = $esg_loadbalancer->call_url(self::URL_FB_API, $args);
		if (is_wp_error($request)) {
			return array(
				'error' => true,
				'message' => 'Facebook API error: ' . $request->get_error_message(),
			);
		}

		$responseData = json_decode(wp_remote_retrieve_body($request), true);
		if (empty($responseData)) {
			return array(
				'error' => true,
				'message' => 'Facebook API error: Empty response body or wrong data format',
			);
		}

		return $responseData;
	}

	protected function _get_transient_fb_data($requestData)
	{
		$requestData['transient_sec'] = $this->transient_sec;
		$transient_name = $this->get_transient_name($requestData);
		$clear_cache = Essential_Grid_Base::getPostVar(array('data', 'clear_cache'), '');
		if ($clear_cache != 'facebook' && false !== ($data = get_transient($transient_name))) {
			return $data;
		}

		$responseData = $this->_make_api_call($requestData);
		//code that use this function do not process errors
		//return empty array
		if ($responseData['error']) {
			return array();
		}

		if (isset($responseData['data'])) {
			$data = $this->facebook_output_array($responseData['data'], $requestData['action']);
			set_transient($transient_name, $data, $this->transient_sec);
			return $data;
		}

		return array();
	}

	/**
	 * Get Photosets List from User
	 *
	 * @param string $access_token page access token
	 * @param string $page_id page id
	 * @return    mixed
	 */
	public function get_photo_sets($access_token, $page_id)
	{
		return $this->_make_api_call(array(
			'token' => $access_token,
			'page_id' => $page_id,
			'action' => 'albums',
		));
	}

	/**
	 * Get Photosets List from User as Options for Selectbox
	 *
	 * @param string $access_token page access token
	 * @param string $page_id page id
	 * @return    mixed    options html string | array('error' => true, 'message' => '...');
	 */
	public function get_photo_set_photos_options($access_token, $page_id)
	{
		$photo_sets = $this->get_photo_sets($access_token, $page_id);

		if ($photo_sets['error']) {
			return $photo_sets;
		}

		$return = array();
		if (is_array($photo_sets['data'])) {
			foreach ($photo_sets['data'] as $photo_set) {
				$return[] = '<option title="' . $photo_set['name'] . '" value="' . $photo_set['id'] . '">' . $photo_set['name'] . '</option>"';
			}
		}
		return $return;
	}

	/**
	 * Get Photoset Photos
	 *
	 * @param mixed $grid_id    grid id
	 * @param string $access_token    page access token
	 * @param string $album_id    Album ID
	 * @param int $item_count    items count
	 * @return    array
	 */
	public function get_photo_set_photos($grid_id, $access_token, $album_id, $item_count = 8)
	{
		$requestData = array(
			'grid_id' => $grid_id,
			'token' => $access_token,
			'action' => 'photos',
			'album_id' => $album_id,
			'limit' => $item_count,
		);
		return $this->_get_transient_fb_data($requestData);
	}

	/**
	 * Get Feed
	 *
	 * @param mixed $grid_id    grid id
	 * @param string $access_token    page access token
	 * @param string $page_id    page id
	 * @param int $item_count    items count
	 * @return    array
	 */
	public function get_photo_feed($grid_id, $access_token, $page_id, $item_count = 8)
	{
		$requestData = array(
			'grid_id' => $grid_id,
			'token' => $access_token,
			'page_id' => $page_id,
			'action' => 'feed',
			'limit' => $item_count,
		);
		return $this->_get_transient_fb_data($requestData);
	}

	/**
	 * Prepare output array
	 *
	 * @param array $photos facebook data
	 * @param string $type data type ( album photos or feed)
	 * @return array
	 */
	private function facebook_output_array($photos, $type)
	{
		$return = array();

		foreach ($photos as $photo) {

			$stream = array();
			$stream['custom-image'] = '';
			$stream['id'] = $photo['id'];
			$stream['date'] = date_i18n(get_option('date_format'), strtotime($photo['created_time']));
			$stream['date_modified'] = date_i18n(get_option('date_format'), strtotime($photo['updated_time']));
			$stream['author_name'] = $photo['from']['name'];
			$stream['num_comments'] = $photo['comments']['summary']['total_count'];
			$stream['likes'] = $photo['likes']['summary']['total_count'];
			$stream['likes_short'] = Essential_Grid_Base::thousandsViewFormat($stream['likes']);

			switch ($type) {
				case 'photos':
					$stream['title'] = $photo['name'];
					$stream['content'] = $photo['name'];
					$stream['post-link'] = $photo['link'];
					$stream['custom-image-url'] = array(
						'thumbnail' => array(
							$photo['picture'],
							130,
							130,
						)
					);
					if (!empty($photo['images'][0]['source'])) {
						$stream['custom-image-url']['normal'] = array($photo['images'][0]['source']);
					} else {
						$stream['custom-image-url']['normal'] = array($photo['picture']);
					}
					$stream['custom-type'] = 'image';
					break;

				case 'feed' :
					$stream['title'] = Essential_Grid_Base::getVar($photo, 'message');
					$stream['content'] = Essential_Grid_Base::getVar($photo, 'message');
					$stream['post-link'] = $photo['permalink_url'];

					if (empty($photo['picture'])) {
						$stream['custom-type'] = 'html';
						$stream['custom-image-url'] = array();
					} else {
						$stream['custom-type'] = 'image';
						$stream['custom-image-url'] = array(
							'thumbnail' => array(
								$photo['picture'],
								130,
								130,
							),
							'normal' => array($photo['full_picture'])
						);
						if (isset($photo['attachments']['data'][0])) {
							$attach = $photo['attachments']['data'][0];
							switch ($attach['media_type']) {
								case 'link':
									$pattern = '/(?:.+?)?(?:\/v\/|watch\/|\?v=|\&v=|youtu\.be\/|\/v=|^youtu\.be\/|watch\%3Fv\%3D)([a-zA-Z0-9_-]{11})+/';
									preg_match($pattern, $attach['unshimmed_url'], $matches);
									if (isset($matches[1])) {
										$stream['custom-type'] = 'youtube';
										$stream['custom-youtube'] = $matches[1];
									}
									break;
								case 'video':
									$stream['custom-type'] = 'html5';
									$stream['custom-html5-mp4'] = $attach['media']['source'];
									break;
								default:
							}
						}
					}
					break;

				default:

			}

			$return[] = $stream;
		}

		return $return;
	}

	/**
	 * @param $response    response from wpdb on delete attempt
	 * @param $data    data passed to delete function
	 * @return void
	 */
	public function on_delete_grid($response, $data)
	{
		if (empty($data['id'])) return;
		Essential_Grid_Base::clear_transients(self::TRANSIENT_PREFIX . $data['id']);
	}

}
