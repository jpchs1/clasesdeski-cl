<?php

namespace DynamicContentForElementor\Extensions;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use DynamicContentForElementor\Helper;
use DynamicContentForElementor\Tokens;
if (!\defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly
class ConfirmDialog extends \DynamicContentForElementor\Extensions\ExtensionPrototype
{
    private static $actions_added = \false;
    private $is_common = \false;
    public $has_action = \false;
    public $depended_scripts = ['dce-confirm-dialog'];
    public $depended_styles = ['dce-jquery-confirm'];
    public function get_label()
    {
        return esc_html__('Confirm Dialog', 'dynamic-content-for-elementor');
    }
    public function add_assets_depends($instance, $form)
    {
        if ('yes' === $instance['dce_confirm_dialog_enabled']) {
            foreach ($this->depended_scripts as $script) {
                $form->add_script_depends($script);
            }
            foreach ($this->depended_styles as $style) {
                $form->add_style_depends($style);
            }
        }
    }
    protected function add_actions()
    {
        if (self::$actions_added) {
            return;
        }
        self::$actions_added = \true;
        add_action('elementor-pro/forms/pre_render', [$this, 'add_assets_depends'], 10, 2);
        add_action('elementor/element/form/section_buttons/after_section_end', [$this, 'add_controls_to_form']);
    }
    public function add_controls_to_form($widget)
    {
        if (!\DynamicContentForElementor\Helper::can_register_unsafe_controls()) {
            return;
        }
        $widget->start_controls_section('section_dce_confirm_dialog', ['label' => '<span class="color-dce icon-dce-logo-dce pull-right ml-1"></span> ' . esc_html__('Confirm Dialog before Submit', 'dynamic-content-for-elementor')]);
        $widget->add_control('dce_confirm_dialog_enabled', ['label' => esc_html__('Confirm Dialog', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'no', 'frontend_available' => \true]);
        $widget->add_control('dce_confirm_dialog_title', ['label' => esc_html__('Title', 'dynamic-content-for-elementor'), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Confirm', 'label_block' => 'true', 'description' => esc_html__('You can use the same syntax as in Live HTML field', 'dynamic-content-for-elementor'), 'condition' => ['dce_confirm_dialog_enabled' => 'yes'], 'frontend_available' => \true]);
        $widget->add_control('dce_confirm_dialog_content', ['label' => esc_html__('Content', 'dynamic-content-for-elementor'), 'type' => \Elementor\Controls_Manager::CODE, 'default' => 'Hi {{ form.name }}, please confirm submission', 'language' => 'html', 'label_block' => 'true', 'description' => esc_html__('HTML code to be displayed in the modal. You can use the same syntax as in Live HTML field', 'dynamic-content-for-elementor'), 'condition' => ['dce_confirm_dialog_enabled' => 'yes'], 'frontend_available' => \true]);
        $widget->add_control('dce_confirm_dialog_width', ['label' => esc_html__('Width', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'default' => ['size' => '30', 'unit' => '%'], 'size_units' => ['%', 'px'], 'range' => ['%' => ['min' => 20, 'max' => 100], 'px' => ['min' => 400, 'max' => 1200]], 'condition' => ['dce_confirm_dialog_enabled' => 'yes'], 'frontend_available' => \true]);
        $widget->add_control('dce_confirm_dialog_theme', ['label' => esc_html__('Theme', 'dynamic-content-for-elementor'), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'light', 'options' => ['light' => esc_html__('Light', 'dynamic-content-for-elementor'), 'dark' => esc_html__('Dark', 'dynamic-content-for-elementor'), 'material' => 'Material', 'supervan' => 'Supervan', 'bootstrap' => 'Bootstrap'], 'separator' => 'after', 'label_block' => 'true', 'condition' => ['dce_confirm_dialog_enabled' => 'yes'], 'frontend_available' => \true]);
        $widget->add_control('dce_confirm_dialog_confirm_button', ['label' => esc_html__('Confirm Button', 'dynamic-content-for-elementor'), 'type' => \Elementor\Controls_Manager::HEADING, 'separator' => 'before', 'condition' => ['dce_confirm_dialog_enabled' => 'yes'], 'frontend_available' => \true]);
        $widget->add_control('dce_confirm_dialog_confirm_button_text', ['label' => esc_html__('Text', 'dynamic-content-for-elementor'), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => esc_html__('Confirm', 'dynamic-content-for-elementor'), 'condition' => ['dce_confirm_dialog_enabled' => 'yes'], 'frontend_available' => \true]);
        $widget->add_control('dce_confirm_dialog_confirm_button_color', ['label' => esc_html__('Color', 'dynamic-content-for-elementor'), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'default', 'options' => ['default' => esc_html__('Default', 'dynamic-content-for-elementor'), 'blue' => esc_html__('Blue', 'dynamic-content-for-elementor'), 'green' => esc_html__('Green', 'dynamic-content-for-elementor'), 'red' => esc_html__('Red', 'dynamic-content-for-elementor'), 'orange' => esc_html__('Orange', 'dynamic-content-for-elementor'), 'purple' => esc_html__('Purple', 'dynamic-content-for-elementor'), 'dark' => esc_html__('Dark', 'dynamic-content-for-elementor')], 'condition' => ['dce_confirm_dialog_enabled' => 'yes'], 'frontend_available' => \true]);
        $widget->add_control('dce_confirm_dialog_cancel_button', ['label' => esc_html__('Cancel Button', 'dynamic-content-for-elementor'), 'type' => \Elementor\Controls_Manager::HEADING, 'separator' => 'before', 'condition' => ['dce_confirm_dialog_enabled' => 'yes'], 'frontend_available' => \true]);
        $widget->add_control('dce_confirm_dialog_cancel_button_text', ['label' => esc_html__('Text', 'dynamic-content-for-elementor'), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => esc_html__('Cancel', 'dynamic-content-for-elementor'), 'condition' => ['dce_confirm_dialog_enabled' => 'yes'], 'frontend_available' => \true]);
        $widget->add_control('dce_confirm_dialog_cancel_button_color', ['label' => esc_html__('Color', 'dynamic-content-for-elementor'), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'default', 'options' => ['default' => esc_html__('Default', 'dynamic-content-for-elementor'), 'blue' => esc_html__('Blue', 'dynamic-content-for-elementor'), 'green' => esc_html__('Green', 'dynamic-content-for-elementor'), 'red' => esc_html__('Red', 'dynamic-content-for-elementor'), 'orange' => esc_html__('Orange', 'dynamic-content-for-elementor'), 'purple' => esc_html__('Purple', 'dynamic-content-for-elementor'), 'dark' => esc_html__('Dark', 'dynamic-content-for-elementor')], 'condition' => ['dce_confirm_dialog_enabled' => 'yes'], 'frontend_available' => \true]);
        $widget->end_controls_section();
    }
}
