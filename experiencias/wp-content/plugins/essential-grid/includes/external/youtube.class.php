<?php
/**
 * External Sources Youtube Class
 * @package   Essential_Grid
 * @author    ThemePunch <info@themepunch.com>
 * @link      http://www.themepunch.com/essential/
 * @copyright 2021 ThemePunch
 * @since: 3.0.13
 **/

if (!defined('ABSPATH')) die();

/**
 * Youtube
 *
 * with help of the API this class delivers all kind of Images/Videos from youtube
 *
 * @package    socialstreams
 * @subpackage socialstreams/youtube
 * @author     ThemePunch <info@themepunch.com>
 */
class Essential_Grid_Youtube
{

	/**
	 * API key
	 *
	 * @since    3.0
	 * @access   private
	 * @var      string $api_key Youtube API key
	 */
	private $api_key;

	/**
	 * Channel ID
	 *
	 * @since    3.0
	 * @access   private
	 * @var      string $channel_id Youtube Channel ID
	 */
	private $channel_id;

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
	 * Next page ID
	 *
	 * @since    3.2.6
	 * @access   private
	 * @var      string $nextpage give ID where the next page starts
	 */
	private $nextpage;

	/**
	 * No Cookie URL
	 *
	 * @since    3.0
	 * @access   private
	 * @var      boolean $enable_youtube_nocookie Enable no cookie URL
	 */
	private $enable_youtube_nocookie;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $api_key Youtube API key.
	 * @since    3.0
	 */
	public function __construct($api_key, $channel_id, $transient_sec = 86400)
	{
		$this->api_key = $api_key;
		$this->channel_id = $channel_id;
		$this->transient_sec = $transient_sec;
		$this->enable_youtube_nocookie = get_option('tp_eg_enable_youtube_nocookie', 'false');
		$this->nextpage = "";
	}

	/**
	 * Get Youtube Playlists
	 *
	 * @since    3.0
	 */
	public function get_playlists()
	{
		//call the API and decode the response
		$playlists = array();
		//first call to get playlists
		$url = "https://www.googleapis.com/youtube/v3/playlists?part=snippet&channelId=" . $this->channel_id . "&maxResults=50&key=" . $this->api_key;
		$rsp = json_decode(wp_remote_fopen($url));
		$playlists = $rsp->items;
		//generate as many calls as playlist pages are available
		$supervisor_count = 10;
		$nextpage = empty($rsp->nextPageToken) ? '' : $rsp->nextPageToken;
		while (!empty($rsp->nextPageToken) && $supervisor_count) {
			$url = "https://www.googleapis.com/youtube/v3/playlists?part=snippet&channelId=" . $this->channel_id . "&maxResults=50&key=" . $this->api_key . "&page_token=" . $rsp->nextPageToken;
			$rsp = json_decode(wp_remote_fopen($url));
			$playlists = array_merge($playlists, $rsp->items);
			$nextpage = empty($rsp->nextPageToken) ? '' : $rsp->nextPageToken;
			$supervisor_count--;
		}

		return $playlists;
	}

	/**
	 * Get Youtube Playlist Items
	 *
	 * @param string $playlist_id Youtube Playlist ID
	 * @param integer $count Max videos count
	 * @since    3.0
	 */
	public function show_playlist_videos($playlist_id, $count = 50)
	{
		$url = "https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId=" . $playlist_id . "&key=" . $this->api_key;
		return $this->_fetch_url($url, $count, 'youtube_playlist_output_array');
	}

	/**
	 * Get Youtube Playlist Overview
	 *
	 * @param integer $count Max videos count
	 * @since    3.0
	 */
	public function show_playlist_overview($count = 50)
	{
		$url = "https://www.googleapis.com/youtube/v3/playlists?part=snippet,contentDetails&channelId=" . $this->channel_id . "&key=" . $this->api_key;
		return $this->_fetch_url($url, $count, 'youtube_playlist_overview_output_array');
	}

	/**
	 * Get Youtube Channel Items
	 *
	 * @param integer $count Max videos count
	 * @since    3.0
	 */
	public function show_channel_videos($count = 50)
	{
		$url = "https://www.googleapis.com/youtube/v3/search?part=snippet&channelId=" . $this->channel_id . "&key=" . $this->api_key . "&order=date";
		return $this->_fetch_url($url, $count, 'youtube_channel_output_array');
	}

	/**
	 * @param string $url
	 * @param int $count
	 * @param string $fill_output_array  function name to process results array
	 * @return array|bool|mixed|string|null
	 */
	protected function _fetch_url($url, $count, $fill_output_array)
	{
		$clear_cache = Essential_Grid_Base::getPostVar(array('data', 'clear_cache'), '');
		$transient_name = 'essgrid_' . md5($url . '&count=' . $count . '&sec='.$this->transient_sec);
		if ($clear_cache != 'youtube' && false !== ($data = get_transient($transient_name))) {
			return $data;
		}

		$fetch_result = $this->_fetch_videos($url, $count, $fill_output_array);
		if ($fetch_result != true) return $fetch_result;

		set_transient($transient_name, $this->stream, $this->transient_sec);

		return $this->stream;
	}

	/**
	 * @param string $url
	 * @param int $count
	 * @param string $fill_output_array  function name to process results array
	 * @return bool|string
	 */
	protected function _fetch_videos($url, $count = 50, $fill_output_array = '')
	{
		//no processing function
		if (empty($fill_output_array)) return 'esg_stream_failure';

		$original_count = $count;
		$supervisor_count = 0;
		do {
			if ($original_count == -1) $count = 50;
			$nextpage = empty($page_rsp->nextPageToken) ? '' : "&pageToken=" . $page_rsp->nextPageToken;
			$supervisor_count++;
			$maxResults = $original_count > 50 || $original_count == -1 ? 50 : $original_count;

			$page_rsp = json_decode(wp_remote_fopen($url . "&maxResults=" . $maxResults . $nextpage));
			if (!empty($page_rsp) && !isset($page_rsp->error->message)) {
				$count = $this->$fill_output_array($page_rsp->items, $count);
			} else {
				return 'esg_stream_failure';
			}
		} while (
			($original_count == -1 || sizeof($this->stream) < $original_count)
			&& $supervisor_count < 20
			&& !empty($page_rsp->nextPageToken)
		);

		return true;
	}

	/**
	 * Get Playlists from Channel as Options for Selectbox
	 *
	 * @since    3.0
	 */
	public function get_playlist_options($current_playlist = "")
	{
		$return = array();
		$playlists = $this->get_playlists();
		$count = 1;
		if (!empty($playlists)) {
			foreach ($playlists as $playlist) {
				$return[] = '<option data-count="' . $count++ . '" title="' . $playlist->snippet->description . '" ' . selected($playlist->id, $current_playlist, false) . ' value="' . $playlist->id . '">' . $playlist->snippet->title . '</option>"';
			}
		}

		return $return;
	}

	/**
	 * Prepare output array $stream for Youtube Playlist Overview
	 *
	 * @param string $videos Youtube Output Data
	 * @since    3.0
	 */
	private function youtube_playlist_overview_output_array($videos, $count)
	{
		foreach ($videos as $video) {
			$stream = array();
			if ($count > 0) {
				$count--;
				$image_url = @array(
					'default' => array($video->snippet->thumbnails->default->url, $video->snippet->thumbnails->default->width, $video->snippet->thumbnails->default->height),
					'medium' => array($video->snippet->thumbnails->medium->url, $video->snippet->thumbnails->medium->width, $video->snippet->thumbnails->medium->height),
					'high' => array($video->snippet->thumbnails->high->url, $video->snippet->thumbnails->high->width, $video->snippet->thumbnails->high->height),
					'standard' => array($video->snippet->thumbnails->standard->url, $video->snippet->thumbnails->standard->width, $video->snippet->thumbnails->standard->height),
					'maxres' => array(str_replace('hqdefault', 'maxresdefault', $video->snippet->thumbnails->high->url), 1500, 900)
				);

				$stream['id'] = $video->id;
				$stream['custom-image-url'] = $image_url; //image for entry
				$stream['custom-type'] = 'image'; //image, vimeo, youtube, soundcloud, html
				$stream['post-link'] = 'https://www.youtube.com/playlist?list=' . $video->id;
				$stream['title'] = $video->snippet->title;
				$url = '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i';
				$text = preg_replace($url, '<a href="$0" target="_blank" title="$0">$0</a>', $video->snippet->description);
				$stream['content'] = $text;

				$stream['date'] = date_i18n(get_option('date_format'), strtotime($video->snippet->publishedAt));
				$stream['date_modified'] = date_i18n(get_option('date_format'), strtotime($video->snippet->publishedAt));

				$stream['author_name'] = $video->snippet->channelTitle;

				$stream['itemCount'] = $video->contentDetails->itemCount;

				$this->stream[] = $stream;
			}
		}
		return $count;
	}

	/**
	 * Prepare output array $stream for Youtube Playlist
	 *
	 * @param string $videos Youtube Output Data
	 * @since    3.0
	 */
	private function youtube_playlist_output_array($videos, $count)
	{
		foreach ($videos as $video) {
			$stream = array();

			if ($count > 0) {
				$count--;
				$image_url = @array(
					'default' => array($video->snippet->thumbnails->default->url, $video->snippet->thumbnails->default->width, $video->snippet->thumbnails->default->height),
					'medium' => array($video->snippet->thumbnails->medium->url, $video->snippet->thumbnails->medium->width, $video->snippet->thumbnails->medium->height),
					'high' => array($video->snippet->thumbnails->high->url, $video->snippet->thumbnails->high->width, $video->snippet->thumbnails->high->height),
					'standard' => array($video->snippet->thumbnails->standard->url, $video->snippet->thumbnails->standard->width, $video->snippet->thumbnails->standard->height),
					'maxres' => array(str_replace('hqdefault', 'maxresdefault', $video->snippet->thumbnails->high->url), 1500, 900)
				);

				$stream['id'] = $video->snippet->resourceId->videoId;
				$stream['custom-image-url'] = $image_url; //image for entry
				$stream['custom-type'] = 'youtube'; //image, vimeo, youtube, soundcloud, html
				$stream['custom-youtube'] = $video->snippet->resourceId->videoId;
				$stream['post-link'] = 'https://www.youtube.com/watch?v=' . $video->snippet->resourceId->videoId;
				if ($this->enable_youtube_nocookie != "false") $stream['post-link'] = 'https://www.youtube-nocookie.com/embed/' . $video->snippet->resourceId->videoId;
				$stream['title'] = $video->snippet->title;
				$stream['channel_title'] = $video->snippet->channelTitle;
				$url = '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i';
				$text = preg_replace($url, '<a href="$0" target="_blank" title="$0">$0</a>', $video->snippet->description);
				$stream['content'] = $text;

				$stream['date'] = $video->snippet->publishedAt;
				$stream['date_modified'] = $video->snippet->publishedAt;
				$stream['author_name'] = $video->snippet->channelTitle;

				$video_stats = file_get_contents("https://www.googleapis.com/youtube/v3/videos?part=statistics&id=" . $video->snippet->resourceId->videoId . "&key=" . $this->api_key);
				$video_stats = json_decode($video_stats);
				$stream['views'] = $video_stats->items[0]->statistics->viewCount;
				$stream['views_short'] = Essential_Grid_Base::thousandsViewFormat($video_stats->items[0]->statistics->viewCount);
				$stream["likes"] = $video_stats->items[0]->statistics->likeCount;
				$stream["likes_short"] = Essential_Grid_Base::thousandsViewFormat($video_stats->items[0]->statistics->likeCount);
				$stream["dislikes"] = $video_stats->items[0]->statistics->dislikeCount;
				$stream["dislikes_short"] = Essential_Grid_Base::thousandsViewFormat($video_stats->items[0]->statistics->dislikeCount);
				$stream["favorites"] = $video_stats->items[0]->statistics->favoriteCount;
				$stream["favorites_short"] = Essential_Grid_Base::thousandsViewFormat($video_stats->items[0]->statistics->favoriteCount);
				$stream["num_comments"] = $video_stats->items[0]->statistics->commentCount;
				$stream["num_comments_short"] = Essential_Grid_Base::thousandsViewFormat($video_stats->items[0]->statistics->commentCount);

				$this->stream[] = $stream;
			}
		}
		return $count;
	}

	/**
	 * Prepare output array $stream for Youtube channel
	 *
	 * @param string $videos Youtube Output Data
	 * @since    3.0
	 */
	private function youtube_channel_output_array($videos, $count)
	{
		foreach ($videos as $video) {
			if (!empty($video->id->videoId) && $count > 0) {
				$stream = array();
				$count--;
				$image_url = @array(
					'default' => array($video->snippet->thumbnails->default->url, $video->snippet->thumbnails->default->width, $video->snippet->thumbnails->default->height),
					'medium' => array($video->snippet->thumbnails->medium->url, $video->snippet->thumbnails->medium->width, $video->snippet->thumbnails->medium->height),
					'high' => array($video->snippet->thumbnails->high->url, $video->snippet->thumbnails->high->width, $video->snippet->thumbnails->high->height),
					'standard' => array($video->snippet->thumbnails->standard->url, $video->snippet->thumbnails->standard->width, $video->snippet->thumbnails->standard->height),
					'maxres' => array(str_replace('hqdefault', 'maxresdefault', $video->snippet->thumbnails->high->url), 1500, 900),
				);

				$stream['id'] = $video->id->videoId;
				$stream['custom-image-url'] = $image_url; //image for entry
				$stream['custom-type'] = 'youtube'; //image, vimeo, youtube, soundcloud, html
				$stream['custom-youtube'] = $video->id->videoId;
				$stream['post-link'] = 'https://www.youtube.com/watch?v=' . $video->id->videoId;
				if ($this->enable_youtube_nocookie != "false") $stream['post-link'] = 'https://www.youtube-nocookie.com/embed/' . $video->id->videoId;
				$stream['title'] = $video->snippet->title;
				$stream['channel_title'] = $video->snippet->channelTitle;
				$url = '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i';
				$text = preg_replace($url, '<a href="$0" target="_blank" title="$0">$0</a>', $video->snippet->description);
				$stream['content'] = $text;
				$stream['date'] = date_i18n(get_option('date_format'), strtotime($video->snippet->publishedAt));
				$stream['date_modified'] = date_i18n(get_option('date_format'), strtotime($video->snippet->publishedAt));
				$stream['author_name'] = $video->snippet->channelTitle;

				$video_stats = file_get_contents("https://www.googleapis.com/youtube/v3/videos?part=statistics&id=" . $video->id->videoId . "&key=" . $this->api_key);
				$video_stats = json_decode($video_stats);
				$stream['views'] = $video_stats->items[0]->statistics->viewCount;
				$stream['views_short'] = Essential_Grid_Base::thousandsViewFormat($video_stats->items[0]->statistics->viewCount);
				$stream["likes"] = $video_stats->items[0]->statistics->likeCount;
				$stream["likes_short"] = Essential_Grid_Base::thousandsViewFormat($video_stats->items[0]->statistics->likeCount);
				//no dislikes info passed
				$stream["dislikes"] = 0; //$video_stats->items[0]->statistics->dislikeCount;
				$stream["dislikes_short"] = 0; //Essential_Grid_Base::thousandsViewFormat($video_stats->items[0]->statistics->dislikeCount);
				$stream["favorites"] = $video_stats->items[0]->statistics->favoriteCount;
				$stream["favorites_short"] = Essential_Grid_Base::thousandsViewFormat($video_stats->items[0]->statistics->favoriteCount);
				$stream["num_comments"] = $video_stats->items[0]->statistics->commentCount;

				$this->stream[] = $stream;
			}
		}
		return $count;
	}
}
