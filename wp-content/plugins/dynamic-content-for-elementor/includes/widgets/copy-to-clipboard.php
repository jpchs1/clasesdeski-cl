<?php

namespace DynamicContentForElementor\Widgets;

use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use DynamicContentForElementor\Helper;
if (!\defined('ABSPATH')) {
    exit;
    // Exit if accessed directly
}
class CopyToClipboard extends \DynamicContentForElementor\Widgets\WidgetPrototype
{
    private static $counter = 1;
    private static function uniq_id()
    {
        return self::$counter++;
    }
    public function get_script_depends()
    {
        return ['dce-clipboard-js'];
    }
    public function get_style_depends()
    {
        return ['dce-copy-to-clipboard'];
    }
    /**
     * Register controls after check if this feature is only for admin
     *
     * @return void
     */
    protected function safe_register_controls()
    {
        $this->start_controls_section('section_button', ['label' => esc_html__('Button', 'dynamic-content-for-elementor')]);
        $this->add_control('button_type', ['label' => esc_html__('Type', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'options' => ['' => esc_html__('Default', 'dynamic-content-for-elementor'), 'info' => esc_html__('Info', 'dynamic-content-for-elementor'), 'success' => esc_html__('Success', 'dynamic-content-for-elementor'), 'warning' => esc_html__('Warning', 'dynamic-content-for-elementor'), 'danger' => esc_html__('Danger', 'dynamic-content-for-elementor')], 'prefix_class' => 'elementor-button-']);
        $this->add_control('text', ['label' => esc_html__('Text', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'dynamic' => ['active' => \true], 'placeholder' => esc_html__('Copy to Clipboard', 'dynamic-content-for-elementor')]);
        $this->add_control('animation_on_copy', ['label' => esc_html__('Animation on copy', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'options' => ['change-text' => esc_html__('Change Text', 'dynamic-content-for-elementor'), 'shake-animation' => esc_html__('Shake Animation', 'dynamic-content-for-elementor'), 'none' => esc_html__('None', 'dynamic-content-for-elementor')], 'default' => 'shake-animation']);
        $this->add_control('change_text', ['label' => esc_html__('Change text to', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'default' => esc_html__('Copied', 'dynamic-content-for-elementor'), 'condition' => ['animation_on_copy' => 'change-text']]);
        $this->add_responsive_control('align', ['label' => esc_html__('Alignment', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::CHOOSE, 'options' => ['left' => ['title' => esc_html__('Left', 'dynamic-content-for-elementor'), 'icon' => 'eicon-text-align-left'], 'center' => ['title' => esc_html__('Center', 'dynamic-content-for-elementor'), 'icon' => 'eicon-text-align-center'], 'right' => ['title' => esc_html__('Right', 'dynamic-content-for-elementor'), 'icon' => 'eicon-text-align-right'], 'justify' => ['title' => esc_html__('Justified', 'dynamic-content-for-elementor'), 'icon' => 'eicon-text-align-justify']], 'prefix_class' => 'elementor%s-align-', 'default' => '']);
        $this->add_control('size', ['label' => esc_html__('Size', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'default' => 'sm', 'options' => Helper::get_button_sizes(), 'style_transfer' => \true]);
        $this->add_control('selected_icon', ['label' => esc_html__('Icon', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::ICONS, 'label_block' => \true, 'fa4compatibility' => 'icon', 'default' => ['value' => 'far fa-clipboard', 'library' => 'fa-regular']]);
        $this->add_control('icon_align', ['label' => esc_html__('Icon Position', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'default' => 'left', 'options' => ['left' => esc_html__('Before', 'dynamic-content-for-elementor'), 'right' => esc_html__('After', 'dynamic-content-for-elementor')], 'condition' => ['selected_icon[value]!' => '']]);
        $this->add_control('icon_indent', ['label' => esc_html__('Icon Spacing', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'range' => ['px' => ['max' => 50]], 'default' => ['size' => 0, 'unit' => 'px'], 'selectors' => ['{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};', '{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};']]);
        $this->add_control('icon_size', ['label' => esc_html__('Icon Size', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'size_units' => ['px', 'em', 'rem', 'vw', 'custom'], 'range' => ['px' => ['min' => 10, 'max' => 60], 'em' => ['min' => 0, 'max' => 10], 'rem' => ['min' => 0, 'max' => 10]], 'selectors' => ['{{WRAPPER}} .elementor-button .elementor-button-icon' => 'font-size: {{SIZE}}{{UNIT}};']]);
        $this->add_control('button_css_id', ['label' => esc_html__('Button ID', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'dynamic' => ['active' => \true], 'default' => '', 'title' => esc_html__('Add your custom id WITHOUT the Pound key. e.g: my-id', 'dynamic-content-for-elementor'), 'label_block' => \false, 'description' => \sprintf(
            /* translators: %1$s: opening <code> tag, %2$s: closing </code> tag */
            esc_html__('Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows %1$sA-z 0-9%2$s & underscore chars without spaces.', 'dynamic-content-for-elementor'),
            '<code>',
            '</code>'
        ), 'separator' => 'before']);
        $this->end_controls_section();
        $this->start_controls_section('section_style', ['label' => esc_html__('Button', 'dynamic-content-for-elementor'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'typography', 'selector' => '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button']);
        $this->add_group_control(Group_Control_Text_Shadow::get_type(), ['name' => 'text_shadow', 'selector' => '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button']);
        $this->start_controls_tabs('tabs_button_style');
        $this->start_controls_tab('tab_button_normal', ['label' => esc_html__('Normal', 'dynamic-content-for-elementor')]);
        $this->add_control('button_text_color', ['label' => esc_html__('Text Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '', 'selectors' => ['{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}};']]);
        $this->add_control('background_color', ['label' => esc_html__('Background Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};']]);
        $this->end_controls_tab();
        $this->start_controls_tab('tab_button_hover', ['label' => esc_html__('Hover', 'dynamic-content-for-elementor')]);
        $this->add_control('hover_color', ['label' => esc_html__('Text Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover, {{WRAPPER}} a.elementor-button:focus, {{WRAPPER}} .elementor-button:focus' => 'color: {{VALUE}};', '{{WRAPPER}} a.elementor-button:hover svg, {{WRAPPER}} .elementor-button:hover svg, {{WRAPPER}} a.elementor-button:focus svg, {{WRAPPER}} .elementor-button:focus svg' => 'fill: {{VALUE}};']]);
        $this->add_control('button_background_hover_color', ['label' => esc_html__('Background Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover, {{WRAPPER}} a.elementor-button:focus, {{WRAPPER}} .elementor-button:focus' => 'background-color: {{VALUE}};']]);
        $this->add_control('button_hover_border_color', ['label' => esc_html__('Border Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'condition' => ['border_border!' => ''], 'selectors' => ['{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover, {{WRAPPER}} a.elementor-button:focus, {{WRAPPER}} .elementor-button:focus' => 'border-color: {{VALUE}};']]);
        $this->add_control('hover_animation', ['label' => esc_html__('Hover Animation', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::HOVER_ANIMATION]);
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_group_control(Group_Control_Border::get_type(), ['name' => 'border', 'selector' => '{{WRAPPER}} .elementor-button', 'separator' => 'before']);
        $this->add_control('border_radius', ['label' => esc_html__('Border Radius', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%'], 'selectors' => ['{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->add_group_control(Group_Control_Box_Shadow::get_type(), ['name' => 'button_box_shadow', 'selector' => '{{WRAPPER}} .elementor-button']);
        $this->add_responsive_control('text_padding', ['label' => esc_html__('Padding', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', 'em', '%'], 'selectors' => ['{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'], 'separator' => 'before']);
        $this->end_controls_section();
        $this->start_controls_section('section_content', ['label' => esc_html__('Content', 'dynamic-content-for-elementor')]);
        $this->add_control('dce_clipboard_type', ['label' => esc_html__('Type', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::CHOOSE, 'options' => ['text' => ['title' => esc_html__('Text', 'dynamic-content-for-elementor'), 'icon' => 'fa fa-window-minimize'], 'textarea' => ['title' => esc_html__('Textarea', 'dynamic-content-for-elementor'), 'icon' => 'fa fa-bars'], 'code' => ['title' => esc_html__('Code', 'dynamic-content-for-elementor'), 'icon' => 'fa fa-code']], 'default' => 'text', 'toggle' => \false]);
        $this->add_control('dce_clipboard_text', ['label' => esc_html__('Text', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'label_block' => \true, 'default' => 'https://www.dynamic.ooo', 'condition' => ['dce_clipboard_type' => 'text']]);
        $this->add_control('dce_clipboard_textarea', ['label' => esc_html__('Text', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXTAREA, 'label_block' => \true, 'default' => esc_html__('I am a sample text.', 'dynamic-content-for-elementor') . \PHP_EOL . esc_html__('Discover more at', 'dynamic-content-for-elementor') . ' Dynamic.ooo', 'condition' => ['dce_clipboard_type' => 'textarea']]);
        $this->add_control('dce_clipboard_code', ['label' => esc_html__('Code', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::CODE, 'dynamic' => ['active' => \true], 'label_block' => \true, 'default' => "echo 'Hello Dynamic.ooo';", 'condition' => ['dce_clipboard_type' => 'code']]);
        $code_modes = array('' => 'other');
        $modes_files = \glob(DCE_PATH . 'assets/lib/codemirror/mode/*');
        if (!empty($modes_files)) {
            foreach ($modes_files as $key => $value) {
                $mname = \basename($value);
                $code_modes[$mname] = $mname;
            }
        }
        $this->add_control('dce_clipboard_code_type', ['label' => esc_html__('Language', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'options' => $code_modes, 'label_block' => \true, 'default' => 'php', 'condition' => ['dce_clipboard_type' => 'code']]);
        $code_themes = array('' => 'default');
        $themes_files = \glob(DCE_PATH . 'assets/lib/codemirror/theme/*.css');
        if (!empty($themes_files)) {
            foreach ($themes_files as $key => $value) {
                $tname = \str_replace('.css', '', \basename($value));
                $code_themes[$tname] = $tname;
            }
        }
        $this->add_control('dce_clipboard_code_theme', ['label' => esc_html__('Theme', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'options' => $code_themes, 'label_block' => \true, 'condition' => ['dce_clipboard_type' => 'code']]);
        $this->add_control('dce_clipboard_visible', ['label' => esc_html__('Visible value', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER]);
        $this->add_control('dce_clipboard_readonly', ['label' => esc_html__('Read Only', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SWITCHER, 'default' => \true, 'condition' => ['dce_clipboard_visible!' => '']]);
        $this->end_controls_section();
        $this->start_controls_section('section_style_value', ['label' => esc_html__('Content', 'dynamic-content-for-elementor'), 'tab' => Controls_Manager::TAB_STYLE, 'condition' => ['dce_clipboard_visible!' => '', 'dce_clipboard_type!' => 'code']]);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'typography_value', 'selector' => '{{WRAPPER}} .dce-clipboard-value']);
        $this->add_group_control(Group_Control_Text_Shadow::get_type(), ['name' => 'text_shadow_value', 'selector' => '{{WRAPPER}} .dce-clipboard-value']);
        $this->start_controls_tabs('tabs_button_style_value');
        $this->start_controls_tab('tab_button_normal_value', ['label' => esc_html__('Normal', 'dynamic-content-for-elementor')]);
        $this->add_control('button_text_color_value', ['label' => esc_html__('Text Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '', 'selectors' => ['{{WRAPPER}} .dce-clipboard-value' => 'fill: {{VALUE}}; color: {{VALUE}};']]);
        $this->add_control('background_color_value', ['label' => esc_html__('Background Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .dce-clipboard-value' => 'background-color: {{VALUE}};']]);
        $this->end_controls_tab();
        $this->start_controls_tab('tab_button_hover_value', ['label' => esc_html__('Hover', 'dynamic-content-for-elementor')]);
        $this->add_control('hover_color_value', ['label' => esc_html__('Text Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .dce-clipboard-value:hover, {{WRAPPER}} .dce-clipboard-value:focus' => 'color: {{VALUE}};', '{{WRAPPER}} .dce-clipboard-value:hover svg, {{WRAPPER}} .dce-clipboard-value:focus svg' => 'fill: {{VALUE}};']]);
        $this->add_control('button_background_hover_color_value', ['label' => esc_html__('Background Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .dce-clipboard-value:hover, {{WRAPPER}} .dce-clipboard-value:focus' => 'background-color: {{VALUE}};']]);
        $this->add_control('button_hover_border_color_value', ['label' => esc_html__('Border Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'condition' => ['border_border!' => ''], 'selectors' => ['{{WRAPPER}} .dce-clipboard-value:hover, {{WRAPPER}} .dce-clipboard-value:focus' => 'border-color: {{VALUE}};']]);
        $this->add_control('hover_animation_value', ['label' => esc_html__('Hover Animation', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::HOVER_ANIMATION]);
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_group_control(Group_Control_Border::get_type(), ['name' => 'border_value', 'selector' => '{{WRAPPER}} .dce-clipboard-value', 'separator' => 'before']);
        $this->add_control('border_radius_value', ['label' => esc_html__('Border Radius', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%'], 'selectors' => ['{{WRAPPER}} .dce-clipboard-value' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->add_group_control(Group_Control_Box_Shadow::get_type(), ['name' => 'button_box_shadow_value', 'selector' => '{{WRAPPER}} .dce-clipboard-value']);
        $this->add_responsive_control('text_padding_value', ['label' => esc_html__('Padding', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', 'em', '%'], 'selectors' => ['{{WRAPPER}} .dce-clipboard-value' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'], 'separator' => 'before']);
        $this->end_controls_section();
        $this->start_controls_section('section_style_textarea', ['label' => esc_html__('Textarea', 'dynamic-content-for-elementor'), 'tab' => Controls_Manager::TAB_STYLE, 'condition' => ['dce_clipboard_visible!' => '', 'dce_clipboard_type!' => 'text']]);
        $this->add_control('dce_clipboard_textarea_height', ['label' => esc_html__('Height', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'range' => ['px' => ['max' => 500]], 'selectors' => ['{{WRAPPER}} .dce-clipboard-value' => 'height: {{SIZE}}{{UNIT}};', '{{WRAPPER}} .CodeMirror' => 'height: {{SIZE}}{{UNIT}};'], 'default' => ['size' => 150, 'unit' => 'px']]);
        $this->add_control('dce_clipboard_btn_position', ['label' => esc_html__('Button Position', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'default' => 'static', 'options' => ['static' => esc_html__('Static', 'dynamic-content-for-elementor'), 'absolute' => esc_html__('Absolute', 'dynamic-content-for-elementor')], 'toggle' => \false, 'selectors' => ['{{WRAPPER}} .elementor-button' => 'position: {{VALUE}};'], 'render_type' => 'template']);
        $this->add_control('dce_clipboard_btn_position_top', ['label' => esc_html__('Top', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'range' => ['px' => ['max' => 50]], 'selectors' => ['{{WRAPPER}} .elementor-button' => 'top: {{SIZE}}{{UNIT}};'], 'default' => ['size' => 0, 'unit' => 'px'], 'condition' => ['dce_clipboard_btn_position' => 'absolute']]);
        $this->add_control('dce_clipboard_btn_position_right', ['label' => esc_html__('Right', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'range' => ['px' => ['max' => 50]], 'selectors' => ['{{WRAPPER}} .elementor-button' => 'right: {{SIZE}}{{UNIT}};'], 'default' => ['size' => 0, 'unit' => 'px'], 'condition' => ['dce_clipboard_btn_position' => 'absolute']]);
        $this->add_control('dce_clipboard_btn_hide', ['label' => esc_html__('Button Visibility', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'default' => '1', 'options' => ['1' => esc_html__('Always visible', 'dynamic-content-for-elementor'), '0' => esc_html__('On Hover', 'dynamic-content-for-elementor')], 'toggle' => \false, 'selectors' => ['{{WRAPPER}} .dce-clipboard-wrapper .elementor-button' => 'opacity: {{VALUE}}; z-index: 3;', '{{WRAPPER}} .dce-clipboard-wrapper:hover .elementor-button' => 'opacity: 1;', '{{WRAPPER}} .dce-clipboard-wrapper .elementor-button.animated' => 'opacity: 1;'], 'render_type' => 'template', 'condition' => ['dce_clipboard_btn_position' => 'absolute']]);
        $this->end_controls_section();
    }
    protected function safe_render()
    {
        $uniqid = self::uniq_id();
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('wrapper', 'class', 'dce-clipboard-wrapper');
        $this->add_render_attribute('wrapper', 'class', 'dce-clipboard-wrapper-' . $settings['dce_clipboard_type']);
        if ($settings['dce_clipboard_type'] == 'text' && $settings['dce_clipboard_visible']) {
            $this->add_render_attribute('wrapper', 'class', 'elementor-field-group');
            $this->add_render_attribute('wrapper', 'class', 'dce-input-group');
            if ($settings['align'] == 'right') {
                $this->add_render_attribute('wrapper-btn', 'class', 'dce-input-group-append');
            } else {
                $this->add_render_attribute('wrapper-btn', 'class', 'dce-input-group-prepend');
            }
            $this->add_render_attribute('wrapper-btn', 'class', 'elementor-field-type-submit');
        }
        if ($settings['dce_clipboard_type'] == 'code' && $settings['dce_clipboard_visible']) {
            wp_enqueue_script('wp-codemirror');
            wp_enqueue_code_editor(array('type' => $settings['dce_clipboard_code_type'], 'codemirror' => array('indentUnit' => 2, 'tabSize' => 2)));
            if ($settings['dce_clipboard_code_type']) {
                wp_enqueue_script('dce-codemirror-mode', DCE_URL . 'assets/lib/codemirror/mode/' . $settings['dce_clipboard_code_type'] . '/' . $settings['dce_clipboard_code_type'] . '.js', [], DCE_VERSION);
            }
            if ($settings['dce_clipboard_code_theme']) {
                wp_enqueue_style('dce-codemirror-theme', DCE_URL . 'assets/lib/codemirror/theme/' . $settings['dce_clipboard_code_theme'] . '.css', [], DCE_VERSION);
            }
        }
        $this->add_render_attribute('button', 'class', 'elementor-button');
        if (!empty($settings['button_css_id'])) {
            $this->add_render_attribute('button', 'id', $settings['button_css_id']);
        }
        if (!empty($settings['size'])) {
            $this->add_render_attribute('button', 'class', 'elementor-size-' . $settings['size']);
            $this->add_render_attribute('input', 'class', 'elementor-size-' . $settings['size']);
        }
        if ($settings['hover_animation']) {
            $this->add_render_attribute('button', 'class', 'elementor-animation-' . $settings['hover_animation']);
        }
        $this->add_render_attribute('input', 'class', 'dce-clipboard-value');
        $this->add_render_attribute('input', 'class', 'elementor-field-textual');
        $this->add_render_attribute('button', 'type', 'button');
        $this->add_render_attribute('input', 'id', 'dce-clipboard-value-' . $uniqid);
        $this->add_render_attribute('button', 'id', 'dce-clipboard-btn-' . $uniqid);
        $this->add_render_attribute('button', 'data-clipboard-target', '#dce-clipboard-value-' . $uniqid);
        if (!$settings['dce_clipboard_visible'] || $settings['dce_clipboard_type'] == 'code') {
            $this->add_render_attribute('input', 'aria-hidden', 'true');
            $this->add_render_attribute('input', 'class', 'dce-offscreen');
        }
        if ($settings['dce_clipboard_readonly']) {
            $this->add_render_attribute('input', 'readonly');
        }
        ?>
		<div <?php 
        echo $this->get_render_attribute_string('wrapper');
        ?>>
			<?php 
        if ($settings['dce_clipboard_type'] == 'text') {
            $this->add_render_attribute('input', 'type', 'text');
            $this->add_render_attribute('input', 'value', $settings['dce_clipboard_text']);
            $this->add_render_attribute('input', 'class', 'dce-form-control');
            ?>
				<?php 
            if ($settings['align'] != 'right') {
                ?>
				<div <?php 
                echo $this->get_render_attribute_string('wrapper-btn');
                ?>>
					<?php 
                $this->render_text();
                ?>
				</div>
				<?php 
            }
            ?>
				<input <?php 
            echo $this->get_render_attribute_string('input');
            ?>>
				<?php 
            if ($settings['align'] == 'right') {
                ?>
				<div <?php 
                echo $this->get_render_attribute_string('wrapper-btn');
                ?>>
					<?php 
                $this->render_text();
                ?>
				</div>
				<?php 
            }
        }
        if ($settings['dce_clipboard_type'] == 'textarea' || $settings['dce_clipboard_type'] == 'code') {
            $this->add_render_attribute('input', 'class', 'dce-block');
            ?>
				<?php 
            $this->render_text();
            ?>
				<textarea <?php 
            echo $this->get_render_attribute_string('input');
            ?>><?php 
            echo $settings['dce_clipboard_type'] == 'textarea' ? $settings['dce_clipboard_textarea'] : $settings['dce_clipboard_code'];
            ?></textarea>
			<?php 
        }
        ?>
		</div>

		<script>
			<?php 
        if ($settings['dce_clipboard_type'] == 'code' && $settings['dce_clipboard_visible']) {
            ?>
			jQuery(function () {
				if (wp.codeEditor) {
					var editorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
					editorSettings.codemirror = _.extend( {}, editorSettings.codemirror,
						{
							mode: '<?php 
            echo $settings['dce_clipboard_code_type'];
            ?>',
							readOnly: <?php 
            echo !empty($settings['dce_clipboard_readonly']) ? 'true' : 'false';
            ?>,
							theme: '<?php 
            echo !empty($settings['dce_clipboard_code_theme']) ? $settings['dce_clipboard_code_theme'] : 'default';
            ?>',
						}
					);
					var editor_<?php 
            echo $uniqid;
            ?> = wp.codeEditor.initialize( jQuery('#dce-clipboard-value-<?php 
            echo $uniqid;
            ?>'), editorSettings );
				}
			});
			<?php 
        }
        ?>
			<?php 
        if ($settings['animation_on_copy'] == 'shake-animation') {
            ?>
			jQuery(function () {
				var clipboard_<?php 
            echo $uniqid;
            ?> = new ClipboardJS('#dce-clipboard-btn-<?php 
            echo $uniqid;
            ?>');
				clipboard_<?php 
            echo $uniqid;
            ?>.on('success', function (e) {
					jQuery('#dce-clipboard-btn-<?php 
            echo $uniqid;
            ?>').addClass('animated').addClass('tada');
					setTimeout(function(){
						jQuery('#dce-clipboard-btn-<?php 
            echo $uniqid;
            ?>').removeClass('animated').removeClass('tada');
					}, 3000);
					return false;
				});
				clipboard_<?php 
            echo $uniqid;
            ?>.on('error', function (e) {
					console.log(e);
				});
			});
			<?php 
        } elseif ($settings['animation_on_copy'] == 'change-text') {
            ?>
			jQuery(function () {
				var clipboard_<?php 
            echo $uniqid;
            ?> = new ClipboardJS('#dce-clipboard-btn-<?php 
            echo $uniqid;
            ?>');
				clipboard_<?php 
            echo $uniqid;
            ?>.on('success', function (e) {
					jQuery('#dce-clipboard-btn-<?php 
            echo $uniqid;
            ?>').html('<?php 
            echo $settings['change_text'];
            ?>');
					return false;
				});
				clipboard_<?php 
            echo $uniqid;
            ?>.on('error', function (e) {
					console.log(e);
				});
			});
			<?php 
        } elseif ($settings['animation_on_copy'] == 'none') {
            ?>
			jQuery(function () {
				var clipboard_<?php 
            echo $uniqid;
            ?> = new ClipboardJS('#dce-clipboard-btn-<?php 
            echo $uniqid;
            ?>');
				clipboard_<?php 
            echo $uniqid;
            ?>.on('error', function (e) {
					console.log(e);
				});
			});
			<?php 
        }
        ?>
		</script>
		<?php 
    }
    protected function render_text()
    {
        $settings = $this->get_settings_for_display();
        $migrated = isset($settings['__fa4_migrated']['selected_icon']);
        $is_new = empty($settings['icon']) && Icons_Manager::is_migration_allowed();
        if (!$is_new && empty($settings['icon_align'])) {
            // @todo: remove when deprecated
            // added as bc in 2.6
            //old default
            $settings['icon_align'] = $this->get_settings('icon_align');
        }
        $this->add_render_attribute(['content-wrapper' => ['class' => ['elementor-button-content-wrapper', 'dce-flexbox']], 'icon-align' => ['class' => ['elementor-button-icon', 'elementor-align-icon-' . $settings['icon_align']]], 'text' => ['class' => 'elementor-button-text']]);
        $this->add_inline_editing_attributes('text', 'none');
        ?>
		<button <?php 
        echo $this->get_render_attribute_string('button');
        ?>>
			<span <?php 
        echo $this->get_render_attribute_string('content-wrapper');
        ?>>
				<?php 
        if (!empty($settings['icon']) || !empty($settings['selected_icon']['value'])) {
            ?>
					<span <?php 
            echo $this->get_render_attribute_string('icon-align');
            ?>>
						<?php 
            if ($is_new || $migrated) {
                Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']);
            } else {
                ?>
							<i class="<?php 
                echo esc_attr($settings['icon']);
                ?>" aria-hidden="true"></i>
					<?php 
            }
            ?>
					</span>
				<?php 
        }
        ?>
				<span <?php 
        echo $this->get_render_attribute_string('text');
        ?>><?php 
        echo $settings['text'];
        ?></span>
			</span>
		</button>
		<?php 
    }
    public function on_import($element)
    {
        return Icons_Manager::on_import_migration($element, 'icon', 'selected_icon');
    }
}
