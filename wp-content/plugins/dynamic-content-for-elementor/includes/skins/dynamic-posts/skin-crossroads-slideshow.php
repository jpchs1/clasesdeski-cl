<?php

namespace DynamicContentForElementor\Includes\Skins;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;
use DynamicContentForElementor\Helper;
if (!\defined('ABSPATH')) {
    exit;
    // Exit if accessed directly
}
class SkinCrossroadsSlideshow extends \DynamicContentForElementor\Includes\Skins\SkinBase
{
    public $depended_scripts = ['dce-threejs-lib', 'dce-gsap-lib', 'imagesloaded', 'dce-dynamicPosts-crossroadsslideshow', 'dce-splitText-lib', 'dce-ScrollToPlugin-lib'];
    public $depended_styles = ['dce-dynamicPosts-crossroadsslideshow'];
    /**
     * Register Controls Actions
     *
     * @return void
     */
    protected function _register_controls_actions()
    {
        add_action('elementor/element/' . $this->get_parent()->get_name() . '/section_query/after_section_end', [$this, 'register_controls_layout']);
        add_action('elementor/element/' . $this->get_parent()->get_name() . '/section_dynamicposts/after_section_end', [$this, 'register_additional_crossroadsslideshow_controls']);
    }
    public function get_id()
    {
        return 'crossroadsslideshow';
    }
    public function get_title()
    {
        return esc_html__('Crossroads Slideshow', 'dynamic-content-for-elementor');
    }
    public function register_additional_crossroadsslideshow_controls(\DynamicContentForElementor\Widgets\DynamicPostsBase $widget)
    {
        $this->parent = $widget;
        $this->start_controls_section('section_crossroadsslideshow', ['label' => esc_html__('Crossroads Slideshow', 'dynamic-content-for-elementor'), 'tab' => Controls_Manager::TAB_CONTENT]);
        // Item settings
        $this->add_control('slideshow_layout_heading_item_caption', ['label' => esc_html__('Item Caption', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::HEADING]);
        // Item image caption
        $this->add_control('slideshow_image_caption_text', ['label' => esc_html__('Caption Text', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'default' => 'item_date', 'options' => ['item_date' => 'Publish Date', 'item_author' => 'Author', 'item_custommeta' => 'Custom Field'], 'frontend_available' => \true]);
        $this->add_control('slideshow_caption_date_format', ['label' => esc_html__('Caption Date Format', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'default' => esc_html__('F j, Y', 'dynamic-content-for-elementor'), 'condition' => [$this->get_control_id('slideshow_image_caption_text') => 'item_date']]);
        $this->add_control('slideshow_pre_caption_text', ['label' => esc_html__('Pre-Caption Text', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'placeholder' => esc_html__('By ', 'dynamic-content-for-elementor'), 'default' => esc_html__('By ', 'dynamic-content-for-elementor'), 'condition' => [$this->get_control_id('slideshow_image_caption_text') => 'item_author']]);
        $this->add_control('slideshow_metafield_key', ['label' => esc_html__('Custom Meta Field', 'dynamic-content-for-elementor'), 'type' => 'ooo_query', 'placeholder' => esc_html__('Meta key or Name', 'dynamic-content-for-elementor'), 'label_block' => \true, 'query_type' => 'metas', 'object_type' => 'post', 'default' => '', 'condition' => [$this->get_control_id('slideshow_image_caption_text') => 'item_custommeta']]);
        $this->add_control('slideshow_metafield_type', ['label' => esc_html__('Custom Meta Field Type', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'default' => 'text', 'options' => ['date' => esc_html__('Date', 'dynamic-content-for-elementor'), 'text' => esc_html__('Text', 'dynamic-content-for-elementor'), 'textarea' => esc_html__('Text Area', 'dynamic-content-for-elementor')], 'condition' => [$this->get_control_id('slideshow_image_caption_text') => 'item_custommeta']]);
        $this->add_control('slideshow_metafield_date_format_source', ['label' => esc_html__('Date Format: SOURCE', 'dynamic-content-for-elementor'), 'description' => '<a target="_blank" href="https://www.php.net/manual/en/function.date.php">' . esc_html__('Use standard PHP format character', 'dynamic-content-for-elementor') . '</a>' . esc_html__(', you can also use "timestamp"', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'default' => 'F j, Y, g:i a', 'placeholder' => esc_html__('YmdHis, d/m/Y, m-d-y', 'dynamic-content-for-elementor'), 'condition' => [$this->get_control_id('slideshow_image_caption_text') => 'item_custommeta', $this->get_control_id('slideshow_metafield_type') => 'date']]);
        $this->add_control('slideshow_metafield_date_format_display', ['label' => esc_html__('Date Format: DISPLAY', 'dynamic-content-for-elementor'), 'placeholder' => esc_html__('YmdHis, d/m/Y, m-d-y', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::TEXT, 'default' => 'F j, Y, g:i a', 'condition' => [$this->get_control_id('slideshow_metafield_type') => 'date']]);
        // Item settings
        $this->add_control('slideshow_layout_heading_item_image', ['label' => esc_html__('Item Image Overlay', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::HEADING, 'separator' => 'before']);
        // ----- Item image overlay
        $this->start_controls_tabs('tabs_slideshow_background_overlay');
        $this->start_controls_tab('tab_slideshow_background_overlay_center', ['label' => esc_html__('Center', 'dynamic-content-for-elementor')]);
        $this->add_group_control(Group_Control_Background::get_type(), ['name' => 'slideshow_background_overlay_center', 'selector' => '{{WRAPPER}} .dce-grid__item--center .dce-post-block .dce-img-wrap .dce-img-background-overlay']);
        $this->add_control('slideshow_background_overlay_opacity_center', ['label' => esc_html__('Opacity', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'default' => ['size' => 0.5], 'range' => ['px' => ['max' => 1, 'step' => 0.01]], 'selectors' => ['{{WRAPPER}} .dce-grid__item--center .dce-post-block .dce-img-wrap .dce-img-background-overlay' => 'opacity: {{SIZE}};'], 'condition' => [$this->get_control_id('slideshow_background_overlay_center_background') => ['classic', 'gradient']]]);
        $this->add_group_control(Group_Control_Css_Filter::get_type(), ['name' => 'slideshow_css_filters_center', 'selector' => '{{WRAPPER}} .dce-grid__item--center .dce-post-block .dce-img-wrap .dce-img-background-overlay']);
        $this->add_control('slideshow_background_overlay_transition', ['label' => esc_html__('Transition Duration', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'default' => ['size' => 0.3], 'range' => ['px' => ['max' => 3, 'step' => 0.1]], 'render_type' => 'ui', 'separator' => 'before', 'selectors' => ['{{WRAPPER}} .dce-post-block .dce-img-wrap .dce-img-background-overlay' => 'transition: background {{SIZE}}s, border-radius {{SIZE}}s, opacity {{SIZE}}s']]);
        $this->end_controls_tab();
        $this->start_controls_tab('tab_slideshow_background_overlay_normal', ['label' => esc_html__('Normal', 'dynamic-content-for-elementor')]);
        $this->add_group_control(Group_Control_Background::get_type(), ['name' => 'slideshow_background_overlay_normal', 'selector' => '{{WRAPPER}} .dce-grid__item--slide:not(.dce-grid__item--center) .dce-post-block .dce-img-wrap .dce-img-background-overlay']);
        $this->add_control('slideshow_background_overlay_opacity_normal', ['label' => esc_html__('Opacity', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'default' => ['size' => 0.5], 'range' => ['px' => ['max' => 1, 'step' => 0.01]], 'selectors' => ['{{WRAPPER}} .dce-grid__item--slide:not(.dce-grid__item--center) .dce-post-block .dce-img-wrap .dce-img-background-overlay' => 'opacity: {{SIZE}};'], 'condition' => [$this->get_control_id('slideshow_background_overlay_background') => ['classic', 'gradient']]]);
        $this->add_group_control(Group_Control_Css_Filter::get_type(), ['name' => 'slideshow_css_filters_normal', 'selector' => '{{WRAPPER}} .dce-grid__item--slide:not(.dce-grid__item--center) .dce-post-block .dce-img-wrap .dce-img-background-overlay']);
        $this->add_control('slideshow_overlay_blend_mode_normal', ['label' => esc_html__('Blend Mode', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'options' => ['' => esc_html__('Normal', 'dynamic-content-for-elementor'), 'multiply' => esc_html__('Multiply', 'dynamic-content-for-elementor'), 'screen' => esc_html__('Screen', 'dynamic-content-for-elementor'), 'overlay' => esc_html__('Overlay', 'dynamic-content-for-elementor'), 'darken' => esc_html__('Darken', 'dynamic-content-for-elementor'), 'lighten' => esc_html__('Lighten', 'dynamic-content-for-elementor'), 'color-dodge' => esc_html__('Color Dodge', 'dynamic-content-for-elementor'), 'saturation' => esc_html__('Saturation', 'dynamic-content-for-elementor'), 'color' => esc_html__('Color', 'dynamic-content-for-elementor'), 'luminosity' => esc_html__('Luminosity', 'dynamic-content-for-elementor')], 'selectors' => ['{{WRAPPER}} .dce-grid__item--slide:not(.dce-grid__item--center) .dce-post-block .dce-img-wrap .dce-img-background-overlay' => 'mix-blend-mode: {{VALUE}}']]);
        $this->end_controls_tab();
        $this->start_controls_tab('tab_slideshow_background_overlay_fullview', ['label' => esc_html__('Full View', 'dynamic-content-for-elementor')]);
        $this->add_group_control(Group_Control_Background::get_type(), ['name' => 'slideshow_background_overlay_fullview', 'selector' => '{{WRAPPER}} .dce-content-fullview .dce-img-wrap .dce-img-background-overlay']);
        $this->add_control('slideshow_background_overlay_opacity_fullview', ['label' => esc_html__('Opacity', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'default' => ['size' => 0.5], 'range' => ['px' => ['max' => 1, 'step' => 0.01]], 'selectors' => ['{{WRAPPER}} .dce-content-fullview .dce-img-wrap .dce-img-background-overlay' => 'opacity: {{SIZE}};'], 'condition' => [$this->get_control_id('slideshow_background_overlay_background') => ['classic', 'gradient']]]);
        $this->add_group_control(Group_Control_Css_Filter::get_type(), ['name' => 'slideshow_css_filters_fullview', 'selector' => '{{WRAPPER}} .dce-content-fullview .dce-img-wrap .dce-img-background-overlay']);
        $this->add_control('slideshow_overlay_blend_mode_fullview', ['label' => esc_html__('Blend Mode', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SELECT, 'options' => ['' => esc_html__('Normal', 'dynamic-content-for-elementor'), 'multiply' => esc_html__('Multiply', 'dynamic-content-for-elementor'), 'screen' => esc_html__('Screen', 'dynamic-content-for-elementor'), 'overlay' => esc_html__('Overlay', 'dynamic-content-for-elementor'), 'darken' => esc_html__('Darken', 'dynamic-content-for-elementor'), 'lighten' => esc_html__('Lighten', 'dynamic-content-for-elementor'), 'color-dodge' => esc_html__('Color Dodge', 'dynamic-content-for-elementor'), 'saturation' => esc_html__('Saturation', 'dynamic-content-for-elementor'), 'color' => esc_html__('Color', 'dynamic-content-for-elementor'), 'luminosity' => esc_html__('Luminosity', 'dynamic-content-for-elementor')], 'selectors' => ['{{WRAPPER}} .dce-content-fullview .dce-img-wrap .dce-img-background-overlay' => 'mix-blend-mode: {{VALUE}}']]);
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }
    protected function register_style_controls()
    {
        parent::register_style_controls();
        $this->start_controls_section('section_style_crossroadsslideshow_grid', ['label' => esc_html__('Grid View', 'dynamic-content-for-elementor'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_control('slideshow_style_background_color', ['label' => esc_html__('Background Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '#F4F4F4', 'selectors' => ['{{WRAPPER}} .dce-crossroadsslideshow-container .dce-revealer .dce-revealer__inner' => 'background-color: {{VALUE}};']]);
        $this->add_control('slideshow_style_heading_item_title', ['label' => esc_html__('Item Title', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::HEADING, 'separator' => 'before']);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'slideshow_item_title_typography', 'label' => esc_html__('Typography', 'dynamic-content-for-elementor'), 'default' => '', 'selector' => '{{WRAPPER}} .dce-titles-wrap .dce-grid-crossroadsslideshow.dce-grid--titles .dce-grid__item--title > *']);
        $this->add_control('slideshow_item_title_color', ['label' => esc_html__('Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '', 'selectors' => ['{{WRAPPER}} .dce-titles-wrap .dce-grid-crossroadsslideshow.dce-grid--titles .dce-grid__item--title > *' => 'color: {{VALUE}};']]);
        $this->add_control('slideshow_style_heading_item_number', ['label' => esc_html__('Item Number', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::HEADING, 'separator' => 'before']);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'slideshow_item_number_typography', 'label' => esc_html__('Typography', 'dynamic-content-for-elementor'), 'default' => '', 'selector' => '{{WRAPPER}} .dce-post-item.dce-crossroadsslideshow-item .dce-post-block .dce-figure-crossroads .dce-number']);
        $this->add_control('slideshow_item_number_text_stroke_color', ['label' => esc_html__('Text Stroke Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '', 'selectors' => ['{{WRAPPER}} .dce-post-item.dce-crossroadsslideshow-item .dce-post-block .dce-figure-crossroads .dce-number' => '-webkit-text-stroke-color: {{VALUE}};']]);
        $this->add_responsive_control('slideshow_item_number_text_stroke_width', ['label' => esc_html__('Text Stroke Width', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::SLIDER, 'range' => ['px' => ['min' => 0, 'max' => 10, 'step' => 0.1]], 'default' => ['size' => '', 'unit' => 'px'], 'tablet_default' => ['size' => '', 'unit' => 'px'], 'mobile_default' => ['size' => '', 'unit' => 'px'], 'size_units' => ['px'], 'selectors' => ['{{WRAPPER}} .dce-post-item.dce-crossroadsslideshow-item .dce-post-block .dce-figure-crossroads .dce-number' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}};']]);
        $this->add_control('slideshow_item_number_text_fill_color', ['label' => esc_html__('Text Fill Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '', 'selectors' => ['{{WRAPPER}} .dce-post-item.dce-crossroadsslideshow-item .dce-post-block .dce-figure-crossroads .dce-number' => 'color: {{VALUE}};', '{{WRAPPER}} .dce-post-item.dce-crossroadsslideshow-item .dce-post-block .dce-figure-crossroads .dce-number' => '-webkit-text-fill-color: {{VALUE}};']]);
        // ----- Item image/figure caption style
        $this->add_control('slideshow_style_heading_item_image_caption', ['label' => esc_html__('Item Image Caption', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::HEADING, 'separator' => 'before']);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'slideshow_item_image_caption_typography', 'label' => esc_html__('Typography', 'dynamic-content-for-elementor'), 'default' => '', 'selector' => '{{WRAPPER}} .dce-post-item.dce-crossroadsslideshow-item .dce-post-block .dce-figure-crossroads .dce-caption']);
        $this->add_control('slideshow_item_image_caption_color', ['label' => esc_html__('Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '', 'selectors' => ['{{WRAPPER}} .dce-post-item.dce-crossroadsslideshow-item .dce-post-block .dce-figure-crossroads .dce-caption' => 'color: {{VALUE}};']]);
        $this->end_controls_section();
        // Fullview
        $this->start_controls_section('section_style_crossroadsslideshow_fullview', ['label' => esc_html__('Full View', 'dynamic-content-for-elementor'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_control('slideshow_style_background_color_fullview', ['label' => esc_html__('Background Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '#F4F4F4', 'selectors' => ['{{WRAPPER}} .dce-crossroadsslideshow-container .dce-content-fullview' => 'background-color: {{VALUE}};']]);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'slideshow_fullview_title_typography', 'label' => esc_html__('Typography', 'dynamic-content-for-elementor'), 'separator' => 'before', 'default' => '', 'selector' => '{{WRAPPER}} .dce-titles-wrap .dce-grid-crossroadsslideshow.dce-grid--titles .dce-grid__item--title > *']);
        $this->add_control('slideshow_fullview_title_color', ['label' => esc_html__('Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '', 'selectors' => ['{{WRAPPER}} .dce-titles-wrap .dce-grid-crossroadsslideshow.dce-grid--titles .dce-grid__item--title > *' => 'color: {{VALUE}};']]);
        // Full view date-terms separator
        $this->add_control('slideshow_style_heading_fullview_datetermsdivider', ['label' => esc_html__('Date-Terms Divider', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::HEADING, 'separator' => 'before']);
        $this->add_control('slideshow_fullview_datetermsdivider_color', ['label' => esc_html__('Color', 'dynamic-content-for-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '', 'selectors' => ['{{WRAPPER}} .dce-content-fullview .dce-content__item--current .dce-content__item-header .dce-content__item-header-meta::before' => 'color: {{VALUE}};']]);
        $this->end_controls_section();
    }
    protected function render_post_items()
    {
        $_skin = $this->get_parent()->get_settings('_skin');
        $style_items = $this->get_parent()->get_settings('style_items');
        $post_items = $this->get_parent()->get_settings('list_items');
        $prefix0 = '0';
        $numberlabel = $this->counter + 1;
        if ($numberlabel > 9) {
            $prefix0 = '';
        }
        $metafield_key = $this->get_instance_value('slideshow_metafield_key');
        $metafield_type = $this->get_instance_value('slideshow_metafield_type');
        echo '<figure class="dce-figure-crossroads">';
        foreach ($post_items as $key => $item) {
            $_id = $item['item_id'];
            if ($this->get_instance_value('slideshow_image_caption_text') == 'item_date') {
                $item_date_format = wp_kses_post($this->get_instance_value('slideshow_caption_date_format'));
                $item_caption = get_the_date($item_date_format);
            } elseif ($this->get_instance_value('slideshow_image_caption_text') == 'item_author') {
                $item_pre_caption = wp_kses_post($this->get_instance_value('slideshow_pre_caption_text'));
                $item_caption = $item_pre_caption . get_the_author();
            } elseif ($this->get_instance_value('slideshow_image_caption_text') == 'item_custommeta') {
                $meta_value = get_post_meta($this->current_id, $metafield_key, \true);
                switch ($metafield_type) {
                    case 'date':
                        $metafield_date_format_source = wp_kses_post($this->get_instance_value('slideshow_metafield_date_format_source'));
                        $metafield_date_format_display = wp_kses_post($this->get_instance_value('slideshow_metafield_date_format_display'));
                        if ($metafield_date_format_source) {
                            if ($metafield_date_format_source == 'timestamp') {
                                $timestamp = $meta_value;
                            } else {
                                $d = \DateTime::createFromFormat($metafield_date_format_source, $meta_value);
                                if ($d) {
                                    $timestamp = $d->getTimestamp();
                                } else {
                                    $timestamp = \strtotime($meta_value);
                                }
                            }
                        } else {
                            $timestamp = \strtotime($meta_value);
                        }
                        $meta_value = date_i18n($metafield_date_format_display, $timestamp);
                        break;
                    case 'textarea':
                        $meta_value = wp_kses_post(\nl2br($meta_value));
                        break;
                    default:
                }
                $item_caption = $meta_value;
            }
            if ($_id == 'item_image') {
                echo '<span class="dce-number">' . $prefix0 . $numberlabel . '</span>';
                echo '<div class="dce-img-wrap">';
                $image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                if ($image_url) {
                    echo '<div class="dce-img-background-overlay"></div>';
                }
                if (isset($image_url[0])) {
                    echo '<div class="dce-img-el" style="background-image: url(' . esc_url($image_url[0]) . ');"></div>';
                }
                echo '</div>';
            }
            if ($_id == $this->get_instance_value('slideshow_image_caption_text')) {
                echo '<figcaption class="dce-caption">' . esc_html($item_caption) . '</figcaption>';
            }
        }
        echo '</figure>';
    }
    protected function render_posts_before()
    {
        ?>
		<div class="dce-content-fullview">
				<?php 
        $this->get_parent()->query_posts();
        $query = $this->get_parent()->get_query();
        if (!$query->found_posts) {
            return;
        }
        if ($query->in_the_loop) {
            $this->current_permalink = get_permalink();
            $this->current_id = get_the_ID();
            $this->render_content_item();
        } else {
            while ($query->have_posts()) {
                $query->the_post();
                $this->current_permalink = get_permalink();
                $this->current_id = get_the_ID();
                $this->render_content_item();
            }
        }
        wp_reset_postdata();
        ?>
			</div> <!-- end content -->

			<div class="dce-revealer">
				<div class="dce-revealer__inner"></div>
			</div>
		<?php 
    }
    public function render_content_item()
    {
        $_skin = $this->get_parent()->get_settings('_skin');
        $post_items = $this->get_parent()->get_settings('list_items');
        $image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
        ?>
		<article class="dce-content__item">
			<?php 
        foreach ($post_items as $key => $item) {
            $_id = $item['item_id'];
            if ($_id == 'item_image') {
                echo '<div class="dce-img-wrap dce-img-wrap--content">';
                ?>
					<div class="dce-img-background-overlay"></div>
					<?php 
                if (isset($image_url[0])) {
                    ?>
					<div class="dce-img-el dce-img--content elementor-repeater-item-item_image" style="background-image: url(<?php 
                    echo esc_url($image_url[0]);
                    ?>);"></div>
					<?php 
                }
                ?>

					<?php 
                echo '</div>';
            }
            if ($_id == 'item_title') {
                ?>
					<div class="dce-content__item-header">
						<div class="dce-content__item-header-date elementor-repeater-item-item_date"><?php 
                $this->render_date($item);
                ?></div>
						<div class="dce-content__item-header-meta elementor-repeater-item-item_termstaxonomy"><?php 
                $this->render_terms($item);
                ?></div>
						<div class="dce-content__item-header-title elementor-repeater-item-item_title"><?php 
                $this->render_title($item);
                ?></div>
						<div class="dce-content__item-header-meta elementor-repeater-item-item_custommeta"><?php 
                $this->render_custom_meta($item);
                ?></div>
					</div>
				<?php 
            }
            if ($_id == 'item_content') {
                echo '<div class="dce-content__item-copy">';
                ?>
					<p class="dce-content__item-copy-text elementor-repeater-item-item_content">
					<?php 
                $this->render_content($item);
                ?>
					</p>
					<div href="#" class="dce-content__item-copy-more dce-item_readmore elementor-repeater-item-item_readmore"><?php 
                $this->render_read_more($item);
                ?></div>
					<?php 
                echo '</div>';
            }
        }
        ?>
		</article>
		<?php 
    }
    protected function render_loop_end()
    {
        ?>
			<div class="dce-titles-wrap">
				<div class="dce-grid-crossroadsslideshow dce-grid--titles">
					<?php 
        $this->get_parent()->query_posts();
        $query = $this->get_parent()->get_query();
        if (!$query->found_posts) {
            return;
        }
        if ($query->in_the_loop) {
            $this->current_permalink = get_permalink();
            $this->current_id = get_the_ID();
            $this->render_title_item();
        } else {
            while ($query->have_posts()) {
                $query->the_post();
                $this->current_permalink = get_permalink();
                $this->current_id = get_the_ID();
                $this->render_title_item();
            }
        }
        wp_reset_postdata();
        ?>

				</div>
			</div>

			<div class="dce-grid-crossroadsslideshow dce-grid--interaction">
				<div class="dce-grid__item dce-grid__item--cursor dce-grid__item--left"></div>
				<div class="dce-grid__item dce-grid__item--cursor dce-grid__item--center"></div>
				<div class="dce-grid__item dce-grid__item--cursor dce-grid__item--right"></div>
			</div>

			</div><?php 
        // end wrapper
        ?>
			<?php 
        $this->render_posts_after();
        ?>
		</div><?php 
        // end container
        ?>
		<?php 
    }
    public function render_title_item()
    {
        ?>
		<h3 class="dce-grid__item dce-grid__item--title elementor-repeater-item-item_title">
			<?php 
        echo wp_kses_post(get_the_title());
        ?>
		</h3>
		<?php 
    }
    public function get_container_class()
    {
        return 'dce-crossroadsslideshow-container dce-skin-' . $this->get_id();
    }
    public function get_wrapper_class()
    {
        return 'dce-grid-crossroadsslideshow dce-grid--slideshow dce-crossroadsslideshow-wrapper dce-wrapper-' . $this->get_id();
    }
    public function get_item_class()
    {
        return 'dce-grid__item dce-grid__item--slide dce-crossroadsslideshow-item dce-item-' . $this->get_id();
    }
    public function get_image_class()
    {
        return 'dce-img-el';
    }
}
