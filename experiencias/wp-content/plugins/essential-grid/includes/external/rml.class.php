<?php
/**
 * External Sources RML Class
 * @package   Essential_Grid
 * @author    ThemePunch <info@themepunch.com>
 * @link      http://www.themepunch.com/essential/
 * @copyright 2021 ThemePunch
 * @since: 3.0.13
 **/

if(!defined('ABSPATH')) exit();

/**
 * Real Media Library
 *
 * show images from Real Media Library Folders and Galleries
 *
 * @package    socialstreams
 * @subpackage socialstreams/nextgen
 * @author     ThemePunch <info@themepunch.com>
 */
class Essential_Grid_Rml
{
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    3.0
	 */
	/**
	 * Stream Array
	 *
	 * @since    3.0
	 * @access   private
	 * @var      array $stream Stream Data Array
	 */
	private $stream;

	public function __construct()
	{

	}

	public function get_images($folder_id = -1)
	{
		$query = new WP_Query(array(
			'post_status' => 'inherit',
			'post_type' => 'attachment',
			'rml_folder' => $folder_id,
			'orderby' => "rml",
			'posts_per_page' => 9999
		));

		$posts = $this->rml_output_array($query->posts);
		return $this->stream;
	}

	public static function option_list_image_sizes($selected = "")
	{
		$image_sizes = Essential_Grid_Rml::get_image_sizes();
		$options = "";
		foreach ($image_sizes as $image_name => $image_size) {
			$options .= '<option value="' . $image_name . '" ' . selected($selected, $image_name, false) . '>' . $image_name . '</option>';
		}
		$options .= '<option value="original" ' . selected($selected, "original", false) . '>original</option>';
		return $options;
	}

	public static function get_image_sizes()
	{
		global $_wp_additional_image_sizes;

		$sizes = array();
		foreach (get_intermediate_image_sizes() as $_size) {
			if (in_array($_size, array('thumbnail', 'medium', 'medium_large', 'large'))) {
				$sizes[$_size]['width'] = get_option("{$_size}_size_w");
				$sizes[$_size]['height'] = get_option("{$_size}_size_h");
				$sizes[$_size]['crop'] = (bool)get_option("{$_size}_crop");
			} elseif (isset($_wp_additional_image_sizes[$_size])) {
				$sizes[$_size] = array(
					'width' => $_wp_additional_image_sizes[$_size]['width'],
					'height' => $_wp_additional_image_sizes[$_size]['height'],
					'crop' => $_wp_additional_image_sizes[$_size]['crop'],
				);
			}
		}

		return $sizes;
	}

	public function rml_output_array($images)
	{
		$this->stream = array();
		$image_sizes = $this->get_image_sizes();
		foreach ($images as $image) {
			foreach ($image_sizes as $slug => $details) {
				$image_url[$slug] = wp_get_attachment_image_src($image->ID, $slug);
			}
			$image_url['original'] = array($image->guid);
			$stream['custom-image-url'] = $image_url;
			$stream['custom-type'] = 'image';
			$stream['post-link'] = $image->guid;
			$stream['title'] = $image->post_title;
			$stream['content'] = $image->post_content;
			$stream['date'] = date_i18n(get_option('date_format'), strtotime($image->post_date));
			$stream['date_modified'] = date_i18n(get_option('date_format'), strtotime($image->post_modified));

			$this->stream[] = $stream;
		}
	}
}
