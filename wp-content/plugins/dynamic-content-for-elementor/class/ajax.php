<?php

namespace DynamicContentForElementor;

use Elementor\Controls_Manager;
use Elementor\Core\Common\Modules\Ajax\Module as Elementor_Ajax;
use Elementor\Widget_Base;
use Elementor\TemplateLibrary\Source_Local;
if (!\defined('ABSPATH')) {
    exit;
}
class Ajax
{
    public $query_control;
    public function __construct()
    {
        add_action('wp_ajax_wpa_update_postmetas', array($this, 'wpa_update_postmetas'));
        add_action('wp_ajax_wpa_update_options', array($this, 'wpa_update_options'));
        add_action('wp_ajax_dce_file_browser_hits', array($this, 'dce_file_browser_hits'));
        add_action('wp_ajax_nopriv_dce_file_browser_hits', array($this, 'dce_file_browser_hits'));
        add_action('wp_ajax_dce_get_next_post', array($this, 'dce_get_next_post'));
        add_action('wp_ajax_nopriv_dce_get_next_post', array($this, 'dce_get_next_post'));
        add_action('wp_ajax_dce_add_to_favorites', [$this, 'add_to_favorites']);
        add_action('wp_ajax_nopriv_dce_add_to_favorites', [$this, 'add_to_favorites']);
        add_action('wp_ajax_dce_generate_dynamic_shortcode', [$this, 'generate_dynamic_shortcode']);
        add_action('wp_ajax_load_elementor_template_content', [$this, 'load_elementor_template_content']);
        add_action('wp_ajax_nopriv_load_elementor_template_content', [$this, 'load_elementor_template_content']);
        // Ajax Select2 autocomplete
        $this->query_control = new \DynamicContentForElementor\Modules\QueryControl\Module();
    }
    /**
     * @return void
     */
    public function generate_dynamic_shortcode()
    {
        if (!current_user_can('manage_options')) {
            wp_die('Access denied');
        }
        // phpcs:ignore WordPress.Security.NonceVerification
        $settings = $_POST['settings'];
        if (!$settings) {
            wp_send_json_error('Settings not provided or invalid');
        }
        $result = \DynamicContentForElementor\Modules\DynamicTags\Tags\DynamicShortcodesWizard\Engine::process_settings($settings);
        if (!empty($result['error'])) {
            wp_send_json_error($result['error']);
        }
        wp_send_json_success($result['result']);
    }
    public function add_to_favorites()
    {
        if (!wp_verify_nonce($_REQUEST['nonce'], 'dce_add_to_favorites')) {
            exit('Nonce verification error');
        }
        $element_id = empty($_GET['eid']) ? 0 : sanitize_text_field($_GET['eid']);
        $favorite_post_id = empty($_GET['dce_post_id']) ? 0 : \intval($_GET['dce_post_id']);
        $favorite_post_id = apply_filters('wpml_object_id', $favorite_post_id, get_post_type($favorite_post_id), \true);
        $list_key = empty($_GET['dce_list']) ? 0 : sanitize_text_field($_GET['dce_list']);
        if ($element_id && $favorite_post_id && $list_key) {
            status_header(200);
            global $wp_query;
            $wp_query->is_singular = \true;
            $wp_query->is_page = $wp_query->is_singular;
            $wp_query->is_404 = \false;
            $element = \DynamicContentForElementor\Helper::get_elementor_element_by_id($element_id);
            if ($element) {
                $element->update_list($element_id, $favorite_post_id, $list_key);
                $settings = $element->get_settings_for_display();
                $favorite = $element->get_favorite_value($list_key, $settings['dce_favorite_scope']);
                echo \implode(', ', $favorite);
            }
        }
        die;
    }
    public function wpa_update_postmetas()
    {
        if (!current_user_can('administrator')) {
            wp_die();
        }
        if (!wp_verify_nonce($_REQUEST['nonce'], 'wpa_update_postmetas')) {
            wp_die();
        }
        $post_id = 0;
        if (isset($_REQUEST['post_id'])) {
            $post_id = \intval($_REQUEST['post_id']);
        }
        if ($post_id) {
            foreach ($_REQUEST as $key => $value) {
                if ($key != 'action' && $key != 'post_id' && $key != 'nonce') {
                    if ($value) {
                        $tmp = get_post_meta($post_id, $key, \true);
                        if (\is_array($value)) {
                            if (!empty($tmp)) {
                                $value = \array_merge($tmp, $value);
                            }
                        }
                        update_post_meta($post_id, $key, $value);
                    } else {
                        delete_post_meta($post_id, $key);
                    }
                }
            }
        } else {
            return \false;
        }
        echo wp_json_encode($_REQUEST);
        wp_die();
    }
    public function wpa_update_options()
    {
        if (!current_user_can('administrator')) {
            wp_die();
        }
        if (!wp_verify_nonce($_REQUEST['nonce'], 'wpa_update_options')) {
            wp_die();
        }
        foreach ($_REQUEST as $key => $value) {
            if ($key != 'action' && $key != 'nonce') {
                if ($value) {
                    if (\is_array($value)) {
                        $tmp = get_option($key);
                        if (!empty($tmp)) {
                            $value = \array_merge($tmp, $value);
                        }
                    }
                    update_option($key, $value);
                } else {
                    delete_option($key);
                }
            }
        }
        echo wp_json_encode($_REQUEST);
        wp_die();
    }
    public function dce_file_browser_hits()
    {
        // The $_REQUEST contains all the data sent via ajax
        if (isset($_REQUEST)) {
            if (isset($_REQUEST['post_id'])) {
                $post_id = \intval($_REQUEST['post_id']);
                $key = 'dce-file';
                $tmp = get_post_meta($post_id, $key, \true);
                $value = array('hits' => 1);
                if (!empty($tmp)) {
                    if (\is_array($tmp)) {
                        if (isset($tmp['hits'])) {
                            $tmp['hits'] = \intval($tmp['hits']) + 1;
                        } else {
                            $tmp['hits'] = 1;
                        }
                    }
                    $value = $tmp;
                }
                update_post_meta($post_id, $key, $value);
            } elseif (isset($_REQUEST['md5'])) {
                $md5 = sanitize_text_field($_REQUEST['md5']);
                $key = 'dce-file-' . $md5;
                $tmp = get_option($key);
                $value = array('hits' => 1);
                if (!empty($tmp)) {
                    if (\is_array($tmp)) {
                        if (isset($tmp['hits'])) {
                            $tmp['hits'] = \intval($tmp['hits']) + 1;
                        } else {
                            $tmp['hits'] = 1;
                        }
                    }
                    $value = $tmp;
                }
                update_option($key, $value);
            }
        }
        echo wp_json_encode($_REQUEST);
        // Always die in functions echoing ajax content
        wp_die();
        // this is required to terminate immediately and return a proper response
    }
    /**
     * Get Next Post
     *
     * @param string $id
     * @return void
     */
    public function dce_get_next_post($id = null)
    {
        $ret = array();
        // The $_REQUEST contains all the data sent via ajax
        if (isset($_REQUEST)) {
            $next = \DynamicContentForElementor\Helper::get_adjacent_post_by_id(null, null, \true, null, \intval($_REQUEST['post_id']));
            $next_url = get_permalink($next->ID);
            $ret['ID'] = $next->ID;
            $ret['permalink'] = $next_url;
            $ret['title'] = wp_kses_post(get_the_title($next->ID));
            $ret['thumbnail'] = get_the_post_thumbnail($next->ID);
        }
        echo wp_json_encode($ret);
        // Always die in functions echoing ajax content
        wp_die();
        // this is required to terminate immediately and return a proper response
    }
    /**
     * @return void
     */
    public function load_elementor_template_content()
    {
        $template_id = isset($_REQUEST['template_id']) ? \intval($_REQUEST['template_id']) : 0;
        if (!$template_id) {
            wp_send_json_error('Template ID not set');
        }
        if (!is_post_publicly_viewable($template_id)) {
            wp_send_json_error('Template ID not viewable');
        }
        $post_id = $_REQUEST['post_id'] ?? '';
        if ($post_id && !is_post_publicly_viewable($post_id)) {
            wp_send_json_error('Post ID not viewable');
        }
        $template_system = \DynamicContentForElementor\Plugin::instance()->template_system;
        $content = $template_system->build_elementor_template_special(['id' => $template_id, 'post_id' => $post_id]);
        if ($content) {
            wp_send_json_success($content);
        } else {
            wp_send_json_error('Template content could not be loaded');
        }
    }
}
