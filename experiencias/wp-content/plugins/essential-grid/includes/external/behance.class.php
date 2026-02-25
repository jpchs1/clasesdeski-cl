<?php
/**
 * External Sources Behance Class
 * @package   Essential_Grid
 * @author    ThemePunch <info@themepunch.com>
 * @link      http://www.themepunch.com/essential/
 * @copyright 2021 ThemePunch
 * @since: 3.0.13
 **/

if(!defined('ABSPATH')) exit();

/**
 * Behance
 *
 * with help of the API this class delivers all kind of Images/Projects from Behance
 *
 * @package    socialstreams
 * @subpackage socialstreams/behance
 * @author     ThemePunch <info@themepunch.com>
 */
class Essential_Grid_Behance
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
	 * API key
	 *
	 * @since    3.0
	 * @access   private
	 * @var      string $api_key Youtube API key
	 */
	private $api_key;

	/**
	 * User ID
	 *
	 * @since    3.0
	 * @access   private
	 * @var      string $user_id Behance User ID
	 */
	private $user_id;

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
	 * @param string $api_key Youtube API key.
	 * @since    3.0
	 */
	public function __construct($api_key, $user_id, $transient_sec = 0)
	{
		$this->api_key = $api_key;
		$this->user_id = $user_id;
		$this->transient_sec = $transient_sec;
		$this->stream = array();
	}

	/**
	 * Get Playlists from Channel as Options for Selectbox
	 *
	 * @since    3.0
	 */
	public function get_behance_projects_options($current_project = "")
	{
		//call the API and decode the response
		$url = "https://www.behance.net/v2/users/" . $this->user_id . "/projects?api_key=" . $this->api_key;
		$rsp = json_decode(wp_remote_fopen($url));

		$return = array();
		if (isset($rsp->projects))
			foreach ($rsp->projects as $project) {
				$return[] = '<option ' . selected($project->id, $current_project, false) . ' value="' . $project->id . '">' . $project->name . '</option>"';
			}
		else
			$return = print_r($rsp, 1);

		return $return;
	}

	/**
	 * Get Behance User Projects
	 *
	 * @since    3.0
	 */
	public function get_behance_projects($count = 12)
	{
		$url = "https://www.behance.net/v2/users/" . $this->user_id . "/projects?api_key=" . $this->api_key . "&per_page=" . $count;
		return $this->_fetch_behance_data($url, 'behance_output_array');
	}

	/**
	 * Get Images from single Project
	 *
	 * @since    3.0
	 */
	public function get_behance_project_images($project = "", $count = 100)
	{
		if (empty($project)) return 'esg_stream_failure';
		
		$url = "https://www.behance.net/v2/projects/" . $project . "?api_key=" . $this->api_key . "&per_page=" . $count;
		return $this->_fetch_behance_data($url, 'behance_images_output_array');
	}

	/**
	 * @param string $url
	 * @param string $fill_output_array
	 * @return array|mixed|string
	 */
	protected function _fetch_behance_data($url, $fill_output_array)
	{
		//no processing function
		if (empty($fill_output_array)) return 'esg_stream_failure';
		
		$clear_cache = Essential_Grid_Base::getPostVar(array('data', 'clear_cache'), '');
		$transient_name = 'essgrid_' . md5($url . '&sec='.$this->transient_sec);
		if ($clear_cache != 'behance' && false !== ($data = get_transient($transient_name))) {
			return $data;
		}

		$rsp = json_decode(wp_remote_fopen($url));
		if (!empty($rsp)) {
			$this->$fill_output_array($rsp, $count);
			set_transient($transient_name, $this->stream, $this->transient_sec);
			return $this->stream;
		}

		return 'esg_stream_failure';
	}

	/**
	 * Prepare output array $stream for Behance images
	 *
	 * @param string $videos Behance Output Data
	 * @since    3.0
	 */
	private function behance_images_output_array($images, $count)
	{
		if (is_object($images)) {
			foreach ($images->project->modules as $image) {
				if (!$count--) break;
				$stream = array();

				$image_url = @array(
					'disp' => array($image->sizes->disp),
					'max_86400' => array($image->sizes->max_86400),
					'max_1240' => array($image->sizes->max_1240),
					'original' => array($image->sizes->original),
				);

				$stream['custom-image-url'] = $image_url;
				$stream['custom-type'] = 'image'; //image, vimeo, youtube, soundcloud, html
				$stream['post-link'] = $images->project->url;
				$stream['title'] = $images->project->name;
				$stream['content'] = $images->project->name;
				$stream['date'] = date_i18n(get_option('date_format'), strtotime($images->project->modified_on));
				$stream['date_modified'] = date_i18n(get_option('date_format'), strtotime($images->project->modified_on));
				$stream['author_name'] = $images->project->owners[0]->first_name;
				$this->stream[] = $stream;
			}
		}
	}

	/**
	 * Prepare output array $stream for Behance Projects
	 *
	 * @param string $videos Behance Output Data
	 * @since    3.0
	 */
	private function behance_output_array($images)
	{
		if (is_object($images) && isset($images->projects)) {
			foreach ($images->projects as $image) {
				$stream = array();

				$image_url = @array(
					'115' => array($image->covers->{'115'}),
					'202' => array($image->covers->{'202'}),
					'230' => array($image->covers->{'230'}),
					'404' => array($image->covers->{'404'}),
					'original' => array($image->covers->original),
				);
				$stream['custom-image-url'] = $image_url;

				$stream['custom-type'] = 'image'; //image, vimeo, youtube, soundcloud, html
				$stream['post-link'] = $image->url;
				$stream['title'] = $image->name;
				$stream['content'] = $image->name;
				$stream['date'] = $image->modified_on;
				$stream['date_modified'] = $image->modified_on;
				$stream['author_name'] = 'dude';
				$this->stream[] = $stream;
			}
		}
	}
}
