<?php
/**
 * External Sources Vimeo Class
 * @package   Essential_Grid
 * @author    ThemePunch <info@themepunch.com>
 * @link      http://www.themepunch.com/essential/
 * @copyright 2021 ThemePunch
 * @since: 3.0.13
 **/

if(!defined('ABSPATH')) exit();

/**
 * Vimeo
 *
 * with help of the API this class delivers all kind of Images/Videos from Vimeo
 *
 * @package    socialstreams
 * @subpackage socialstreams/vimeo
 * @author     ThemePunch <info@themepunch.com>
 */
class Essential_Grid_Vimeo
{
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
	 * @since    1.0.0
	 * @access   private
	 * @var      number $transient Transient time in seconds
	 */
	private $transient_sec;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $api_key Youtube API key.
	 * @since    3.0
	 */
	public function __construct($transient_sec = 86400)
	{
		$this->transient_sec = $transient_sec;
	}

	/**
	 * Get Vimeo User Videos
	 *
	 * @since    3.0
	 */
	public function get_vimeo_videos($type, $value, $count)
	{
		//call the API and decode the response
		if ($type == "user") {
			$url = "https://vimeo.com/api/v2/" . $value . "/videos.json?count=" . $count;
		} else {
			$url = "https://vimeo.com/api/v2/" . $type . "/" . $value . "/videos.json?count=" . $count;
		}

		$clear_cache = Essential_Grid_Base::getPostVar(array('data', 'clear_cache'), '');
		$transient_name = 'essgrid_' . md5($url . '&sec='.$this->transient_sec);
		if ($clear_cache != 'vimeo' && false !== ($data = get_transient($transient_name))) {
			return $data;
		}

		if ($count > 20) {
			$runs = ceil($count / 20);
			$supervisor_count = 0;
			for ($i = 0; $i < $runs && $supervisor_count < 20; $i++) {
				$page_rsp = json_decode(wp_remote_fopen($url . "&page=" . ($i + 1)));
				$supervisor_count++;
				if (!empty($page_rsp)) {
					$count = $count - 20;
					$this->vimeo_output_array($page_rsp, $count);
				} else {
					if ($i == 0) {
						return 'esg_stream_failure';
					}
				}
			}
		} else {
			$rsp = json_decode(wp_remote_fopen($url));

			if (!empty($rsp)) {
				$this->vimeo_output_array($rsp, $count);
			} else {
				return 'esg_stream_failure';
			}
		}
		set_transient($transient_name, $this->stream, $this->transient_sec);

		return $this->stream;
	}

	/**
	 * Prepare output array $stream for Vimeo videos
	 *
	 * @param string $videos Vimeo Output Data
	 * @since    3.0
	 */
	private function vimeo_output_array($videos, $count)
	{
		if (is_array($videos))
			foreach ($videos as $video) {
				if ($count-- == 0) break;

				$stream = array();
				$image_url = @array(
					'thumbnail_small' => array($video->thumbnail_small),
					'thumbnail_medium' => array($video->thumbnail_medium),
					'thumbnail_large' => array($video->thumbnail_large),
				);

				$stream['custom-image-url'] = $image_url; //image for entry
				$stream['custom-type'] = 'vimeo'; //image, vimeo, youtube, soundcloud, html
				$stream['custom-vimeo'] = $video->id;
				$stream['id'] = $video->id;
				$stream['post_id'] = $video->id;
				$stream['post-link'] = $video->url;
				$stream['title'] = $video->title;
				$url = '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i';
				$text = preg_replace($url, '<a href="$0" target="_blank" title="$0">$0</a>', $video->description);
				$stream['content'] = $text;
				$stream['date'] = date_i18n(get_option('date_format'), strtotime($video->upload_date));
				$stream['date_modified'] = date_i18n(get_option('date_format'), strtotime($video->upload_date));
				$stream['author_name'] = $video->user_name;
				$minutes = floor($video->duration / 60);
				$seconds = $video->duration % 60;
				$seconds = $seconds < 10 ? '0' . $seconds : $seconds;
				$stream['duration'] = $minutes . ':' . $seconds;
				$stream['tag_list'] = $video->tags;
				$stream["likes"] = isset($video->stats_number_of_likes) ? $video->stats_number_of_likes : 0;
				$stream["likes_short"] = isset($video->stats_number_of_likes) ? Essential_Grid_Base::thousandsViewFormat($video->stats_number_of_likes) : 0;
				$stream["views"] = isset($video->stats_number_of_plays) ? $video->stats_number_of_plays : 0;
				$stream["views_short"] = isset($video->stats_number_of_plays) ? Essential_Grid_Base::thousandsViewFormat($video->stats_number_of_plays) : 0;
				$stream["num_comments"] = isset($video->stats_number_of_comments) ? $video->stats_number_of_comments : 0;

				$this->stream[] = $stream;
			}
	}
}
