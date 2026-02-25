<?php
/**
 * External Sources Flickr Class
 * @package   Essential_Grid
 * @author    ThemePunch <info@themepunch.com>
 * @link      http://www.themepunch.com/essential/
 * @copyright 2021 ThemePunch
 * @since: 3.0.13
 **/

if (!defined('ABSPATH')) die();

/**
 * Flickr
 *
 * with help of the API this class delivers all kind of Images from flickr
 *
 * @package    socialstreams
 * @subpackage socialstreams/flickr
 * @author     ThemePunch <info@themepunch.com>
 */
class Essential_Grid_Flickr
{

	/**
	 * API key
	 *
	 * @since    3.0
	 * @access   private
	 * @var      string $api_key flickr API key
	 */
	private $api_key;

	/**
	 * API params
	 *
	 * @since    3.0
	 * @access   private
	 * @var      array $api_param_defaults Basic params to call with API
	 */
	private $api_param_defaults;

	/**
	 * Stream Array
	 *
	 * @since    3.0
	 * @access   private
	 * @var      array $stream Stream Data Array
	 */
	private $stream;

	/**
	 * Basic URL
	 *
	 * @since    3.0
	 * @access   private
	 * @var      string $flickr_url Url to fetch user from
	 */
	private $flickr_url;

	/**
	 * Transient seconds
	 *
	 * @since    3.0
	 * @access   private
	 * @var      number $transient Transient time in seconds
	 */
	private $transient_sec;

	/**
	 * Records per page
	 *
	 * @since    3.0.16
	 * @access   private
	 * @var      number $per_page Number of records per page
	 */
	private $per_page;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $api_key flickr API key.
	 * @since    3.0
	 */
	public function __construct($api_key, $transient_sec = 86400, $per_page = 100)
	{
		$this->api_key = $api_key;
		$this->api_param_defaults = array(
			'api_key' => $this->api_key,
			'format' => 'json',
			'nojsoncallback' => 1,
		);
		$this->transient_sec = $transient_sec;
		$this->setPerPage($per_page);
	}

	/**
	 * @return int
	 */
	public function getPerPage()
	{
		return $this->per_page;
	}

	/**
	 * @param int $per_page
	 */
	public function setPerPage($per_page)
	{
		$this->per_page = $per_page;
	}

	/**
	 * @param $params
	 * @return string
	 */
	private function _get_api_url($params)
	{
		//build url
		$encoded_params = array();
		foreach ($params as $k => $v) {
			$encoded_params[] = urlencode($k) . '=' . urlencode($v);
		}

		return "https://api.flickr.com/services/rest/?" . implode('&', $encoded_params);
	}

	/**
	 * Calls Flicker API with set of params, returns json
	 *
	 * @param array $params Parameter build for API request
	 * @param int $item_count items count
	 * @param array $additional parameters to do pagination calls
	 * @since    3.0
	 */
	private function call_flickr_api($params, $additional = array(), $item_count = 0)
	{
		$clear_cache = Essential_Grid_Base::getPostVar(array('data', 'clear_cache'), '');
		$url = $this->_get_api_url($params);
		$transient_name = 'essgrid_' . md5($url . '&item_count=' . $item_count . '&sec=' . $this->transient_sec);
		if ($clear_cache != 'flickr' && false !== ($data = get_transient($transient_name))) {
			return $data;
		}

		$total = 0;
		$result = new stdClass;
		
		if (!empty($additional) && $item_count) {
			$params['page'] = 1;
			$params['per_page'] = min($item_count, $this->getPerPage());
			
			$result->{$additional[0]} = new stdClass;
			$result->{$additional[0]}->{$additional[1]} = array();
		}
		
		do {
			//call the API and decode the response
			$url = $this->_get_api_url($params);
			$rsp = json_decode(wp_remote_fopen($url));
			if (isset($rsp->stat) && $rsp->stat == "fail") {
				return 'esg_stream_failure';
			}
			
			if (!empty($additional)) {
				$result->{$additional[0]}->{$additional[1]} = array_merge(
					$result->{$additional[0]}->{$additional[1]},
					$rsp->{$additional[0]}->{$additional[1]}
				);
				$total = count($result->{$additional[0]}->{$additional[1]});
				$params['page']++;
				//check if there is no more pages to avoid infinite loop
				if ($params['page'] > $rsp->{$additional[0]}->pages) $total = $item_count;
			} else {
				$result = $rsp;
			}
			
		} while ($total < $item_count);
		
		set_transient($transient_name, $result, $this->transient_sec);
		
		return $result;
	}

	/**
	 * Get User ID from its URL
	 *
	 * @param string $user_url URL of the Gallery
	 * @since    3.0
	 */
	public function get_user_from_url($user_url)
	{
		//gallery params
		$user_params = $this->api_param_defaults + array(
				'method' => 'flickr.urls.lookupUser',
				'url' => $user_url,
			);

		//set User Url
		$this->flickr_url = $user_url;

		//get gallery info
		$user_info = $this->call_flickr_api($user_params);
		if (isset($user_info->user->id))
			return $user_info->user->id;
		else
			return false;
	}

	/**
	 * Get Group ID from its URL
	 *
	 * @param string $group_url URL of the Gallery
	 * @since    3.0
	 */
	public function get_group_from_url($group_url)
	{
		//gallery params
		$group_params = $this->api_param_defaults + array(
				'method' => 'flickr.urls.lookupGroup',
				'url' => $group_url,
			);

		//set User Url
		$this->flickr_url = $group_url;

		//get gallery info
		$group_info = $this->call_flickr_api($group_params);
		if (isset($group_info->group->id))
			return $group_info->group->id;
		else
			return false;
	}

	/**
	 * Get Gallery ID from its URL
	 *
	 * @param string $gallery_url URL of the Gallery
	 * @param int $item_count number of photos to pull
	 * @since    3.0
	 */
	public function get_gallery_from_url($gallery_url)
	{
		$gallery_params = $this->api_param_defaults + array(
				'method' => 'flickr.urls.lookupGallery',
				'url' => $gallery_url,
			);

		//get gallery info
		$gallery_info = $this->call_flickr_api($gallery_params);
		if (isset($gallery_info->gallery->id))
			return $gallery_info->gallery->id;
	}

	/**
	 * Get Photosets List from User
	 *
	 * @param string $user_id flicker User id (not name)
	 * @param int $current_photoset current photoset
	 * @since    3.0
	 */
	public function get_photo_sets($user_id, $current_photoset)
	{
		$photo_set_params = $this->api_param_defaults + array(
				'method' => 'flickr.photosets.getList',
				'user_id' => $user_id,
			);

		//get photoset list
		$photo_sets_list = $this->call_flickr_api($photo_set_params, array('photosets', 'photoset'));

		foreach ($photo_sets_list->photosets->photoset as $photo_set) {
			if (empty($photo_set->title->_content)) $photo_set->title->_content = "";
			if (empty($photo_set->photos)) $photo_set->photos = 0;
			$return[] = '<option title="' . $photo_set->description->_content . '" ' . selected($photo_set->id, $current_photoset, false) . ' value="' . $photo_set->id . '">' . $photo_set->title->_content . ' (' . $photo_set->photos . ' photos)</option>';
		}

		return $return;
	}

	/**
	 * Get Photoset Photos
	 *
	 * @param string $photo_set_id Photoset ID
	 * @param int $item_count number of photos to pull
	 * @since    3.0
	 */
	public function get_photo_set_photos($photo_set_id, $item_count = 10)
	{
		$this->stream = array();
		$photo_set_params = $this->api_param_defaults + array(
				'method' => 'flickr.photosets.getPhotos',
				'photoset_id' => $photo_set_id,
				'extras' => 'license, date_upload, date_taken, owner_name, icon_server, original_format, last_update, geo, tags, machine_tags, o_dims, views, media, path_alias, url_sq, url_t, url_s, url_q, url_m, url_n, url_z, url_c, url_l, url_o'
			);

		//get photo list
		$photo_set_photos = $this->call_flickr_api($photo_set_params, array('photosets', 'photo'), $item_count);
		if (!is_object($photo_set_photos)) return $photo_set_photos;

		$this->flickr_output_array($photo_set_photos->photoset->photo);

		return $this->stream;
	}

	/**
	 * Get Groop Pool Photos
	 *
	 * @param string $group_id Photoset ID
	 * @param int $item_count number of photos to pull
	 * @since    3.0
	 */
	public function get_group_photos($group_id, $item_count = 10)
	{
		//photoset photos params
		$group_pool_params = $this->api_param_defaults + array(
				'method' => 'flickr.groups.pools.getPhotos',
				'group_id' => $group_id,
				'extras' => 'license, date_upload, date_taken, owner_name, icon_server, original_format, last_update, geo, tags, machine_tags, o_dims, views, media, path_alias, url_sq, url_t, url_s, url_q, url_m, url_n, url_z, url_c, url_l, url_o'
			);

		//get photo list
		$group_pool_photos = $this->call_flickr_api($group_pool_params, array('photos', 'photo'), $item_count);
		if (isset($group_pool_photos->photos->photo))
			$this->flickr_output_array($group_pool_photos->photos->photo);

		return $this->stream;
	}

	/**
	 * Get Gallery Photos
	 *
	 * @param string $gallery_id flicker Gallery id (not name)
	 * @param int $item_count number of photos to pull
	 * @since    3.0
	 */
	public function get_gallery_photos($gallery_id, $item_count = 10)
	{
		$gallery_photo_params = $this->api_param_defaults + array(
				'method' => 'flickr.galleries.getPhotos',
				'gallery_id' => $gallery_id,
				'extras' => 'description, license, date_upload, date_taken, owner_name, icon_server, original_format, last_update, geo, tags, machine_tags, o_dims, views, media, path_alias, url_sq, url_t, url_s, url_q, url_m, url_n, url_z, url_c, url_l, url_o',
			);

		//get photo list
		$gallery_photos_list = $this->call_flickr_api($gallery_photo_params, array('photos', 'photo'), $item_count);
		if (isset($gallery_photos_list->photos->photo))
			$this->flickr_output_array($gallery_photos_list->photos->photo);

		return $this->stream;
	}

	/**
	 * Get Public Photos
	 *
	 * @param string $user_id flicker User id (not name)
	 * @param int $item_count number of photos to pull
	 * @since    3.0
	 */
	public function get_public_photos($user_id, $item_count = 10)
	{
		//public photos params
		$public_photo_params = $this->api_param_defaults + array(
				'method' => 'flickr.people.getPublicPhotos',
				'user_id' => $user_id,
				'extras' => 'description, license, date_upload, date_taken, owner_name, icon_server, original_format, last_update, geo, tags, machine_tags, o_dims, views, media, path_alias, url_sq, url_t, url_s, url_q, url_m, url_n, url_z, url_c, url_l, url_o',
			);

		//get photo list
		$public_photos_list = $this->call_flickr_api($public_photo_params, array('photos', 'photo'), $item_count);
		if (isset($public_photos_list->photos->photo))
			$this->flickr_output_array($public_photos_list->photos->photo);

		return $this->stream;
	}

	/**
	 * Prepare output array $stream
	 *
	 * @param string $photos flickr Output Data
	 * @since    3.0
	 */
	private function flickr_output_array($photos)
	{
		if (!is_array($photos)) return;

		// used to disable favourites and comments info load
		// if total > stream_limit ( default is 60 )
		// as it adds too much additional requests
		// two per each item
		$stream_limit = Essential_Grid_Base::get_stream_count_limit_default();
		$total = count($photos);
		
		foreach ($photos as $photo) {
			$stream = array();

			$image_url = @array(
				'Square' => array($photo->url_sq, $photo->width_sq, $photo->height_sq),
				'Large Square' => array($photo->url_q, $photo->width_q, $photo->height_q),
				'Thumbnail' => array($photo->url_t, $photo->width_t, $photo->height_t),
				'Small' => array($photo->url_s, $photo->width_s, $photo->height_s),
				'Small 320' => array($photo->url_n, $photo->width_n, $photo->height_n),
				'Medium' => array($photo->url_m, $photo->width_m, $photo->height_m),
				'Medium 640' => array($photo->url_z, $photo->width_z, $photo->height_z),
				'Medium 800' => array($photo->url_c, $photo->width_c, $photo->height_c),
				'Large' => array($photo->url_l, $photo->width_l, $photo->height_l),
				'Original' => array($photo->url_o, $photo->width_o, $photo->height_o),
			);

			$stream['id'] = $photo->id;
			$stream['custom-image-url'] = $image_url; //image for entry
			$stream['custom-type'] = 'image'; //image, vimeo, youtube, soundcloud, html
			$stream['title'] = $photo->title;
			if (!empty($photo->description->_content)) {
				$url = '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i';
				$text = preg_replace($url, '<a href="$0" target="_blank" title="$0">$0</a>', $photo->description->_content);
				$stream['content'] = $text;
			}

			$stream['date'] = date_i18n(get_option('date_format'), strtotime($photo->datetaken));
			$stream['date_modified'] = date_i18n(get_option('date_format'), strtotime($photo->datetaken));
			$stream['author_name'] = $photo->ownername;

			$stream['views'] = $photo->views;
			$stream['views_short'] = Essential_Grid_Base::thousandsViewFormat($photo->views);
			$stream['tag_list'] = str_replace(" ", ",", $photo->tags);

			$stream['post-link'] = 'http://flic.kr/p/' . $this->base_encode($photo->id);

			if ($total <= $stream_limit) {
				//get favorites
				$photo_fovorites_params = $this->api_param_defaults + array(
						'method' => 'flickr.photos.getFavorites',
						'photo_id' => $photo->id,
						'per_page' => 1,
						'page' => 1
					);
				$photo_favorites = $this->call_flickr_api($photo_fovorites_params);
				if (!empty($photo_favorites->photo->total)) {
					$stream['favorites'] = $photo_favorites->photo->total;
					$stream['favorites_short'] = Essential_Grid_Base::thousandsViewFormat($photo_favorites->photo->total);
				}

				//get comments
				$photo_info_params = $this->api_param_defaults + array(
						'method' => 'flickr.photos.getInfo',
						'photo_id' => $photo->id,
						'per_page' => 1,
						'page' => 1
					);
				$photo_infos = $this->call_flickr_api($photo_info_params);
				if (!empty($photo_infos->photo->comments->_content)) {
					$stream['num_comments'] = $photo_infos->photo->comments->_content;
				}
			}

			$this->stream[] = $stream;
		}
	}

	/**
	 * Encode the flickr ID for URL (base58)
	 *
	 * @param string $num flickr photo id
	 * @since    3.0
	 */
	private function base_encode($num, $alphabet = '123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ')
	{
		$base_count = strlen($alphabet);
		$encoded = '';
		while ($num >= $base_count) {
			$div = $num / $base_count;
			$mod = ($num - ($base_count * intval($div)));

			/* 2.1.5 */
			$mod = intval($mod);
			$encoded = $alphabet[$mod] . $encoded;

			$num = intval($div);
		}
		if ($num) $encoded = $alphabet[$num] . $encoded;
		return $encoded;
	}
}
