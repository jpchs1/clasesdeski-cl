<?php
if (!class_exists('VC_Extensions_CoverFlow')){
    class VC_Extensions_CoverFlow{
        private $thumbtextcolor, $captionsize;
        function __construct() {
            vc_map(array(
            "name" => esc_attr__("Coveflow with lightbox", 'cq_allinone_vc'),
            "base" => "cq_vc_coverflow",
            "class" => "cq_vc_coverflow",
            "icon" => "cq_vc_coverflow",
            "category" => esc_attr__('Sike Extensions', 'js_composer'),
            "as_parent" => array('only' => 'cq_vc_coverflow_item'),
            "js_view" => 'VcColumnView',
            "show_settings_on_create" => true,
            'description' => esc_attr__('Video or image', 'js_composer'),
            "params" => array(
                array(
                   "type" => "dropdown",
                   "holder" => "",
                   "edit_field_class" => "vc_col-xs-6 vc_column",
                   "heading" => esc_attr__("Auto delay slideshow", "js_composer"),
                   "param_name" => "autodelay",
                   "value" => array("no", "2", "3", "4", "5", "6", "7", "8"),
                   "std" => "no",
                   "description" => esc_attr__("In seconds, default is no, which is disabled.", "js_composer")
              ),
              array(
                "type" => "checkbox",
                "heading" => esc_attr__("Display the link as lightbox?", "js_composer" ),
                "edit_field_class" => "vc_col-xs-6 vc_column",
                "param_name" => "islightbox",
                "std" => "yes",
                "description" => esc_attr__("Support YouTube, Vimeo video, image, Google Map etc.", "js_composer" ),
                "value" => array( esc_attr__( "Yes, apply lightbox effect", "js_composer" ) => "yes" ),
              ),
              array(
                "type" => "colorpicker",
                "edit_field_class" => "vc_col-xs-6 vc_column",
                "heading" => esc_attr__("Thumbnail text color", 'cq_allinone_vc'),
                "param_name" => "thumbtextcolor",
                "value" => "",
                "description" => esc_attr__("", 'cq_allinone_vc')
              ),
              array(
                "type" => "colorpicker",
                "holder" => "div",
                "class" => "",
                "edit_field_class" => "vc_col-xs-6 vc_column",
                "heading" => esc_attr__("Arrow color", 'cq_allinone_vc'),
                "param_name" => "arrowcolor",
                "value" => '',
                "description" => esc_attr__("Default is white.", 'cq_allinone_vc')
              ),
              array(
                "type" => "colorpicker",
                "holder" => "div",
                "class" => "",
                "edit_field_class" => "vc_col-xs-6 vc_column",
                "heading" => esc_attr__("Arrow hover color", 'cq_allinone_vc'),
                "param_name" => "arrowhovercolor",
                "value" => '',
                "description" => esc_attr__("Default is white.", 'cq_allinone_vc')
              ),
              array(
                "type" => "dropdown",
                "holder" => "div",
                "class" => "",
                "edit_field_class" => "vc_col-xs-6 vc_column",
                "heading" => esc_attr__("Dot pagination default color:", "cq_allinone_vc"),
                "param_name" => "dotcolor",
                "value" => array("Grape Fruit" => "grapefruit", "Bitter Sweet" => "bittersweet", "Sunflower" => "sunflower", "Grass" => "grass", "Mint" => "mint", "Aqua" => "aqua", "Blue Jeans" => "bluejeans", "Lavender" => "lavender", "Pink Rose" => "pinkrose", "Light Gray" => "lightgray", "Medium Gray" => "mediumgray", "Dark Gray" => "darkgray"),
                "std" => "mediumgray",
                "description" => esc_attr__("", "cq_allinone_vc")
              ),
              array(
                "type" => "dropdown",
                "holder" => "div",
                "class" => "",
                "edit_field_class" => "vc_col-xs-6 vc_column",
                "heading" => esc_attr__("Dot pagination active color:", "cq_allinone_vc"),
                "param_name" => "dotactivecolor",
                "value" => array("Grape Fruit" => "grapefruit", "Bitter Sweet" => "bittersweet", "Sunflower" => "sunflower", "Grass" => "grass", "Mint" => "mint", "Aqua" => "aqua", "Blue Jeans" => "bluejeans", "Lavender" => "lavender", "Pink Rose" => "pinkrose", "Light Gray" => "lightgray", "Medium Gray" => "mediumgray", "Dark Gray" => "darkgray"),
                "std" => "grass",
                "description" => esc_attr__("", "cq_allinone_vc")
              ),
              array(
                "type" => "textfield",
                "edit_field_class" => "vc_col-xs-6 vc_column",
                "heading" => esc_attr__("Thumbnail caption font size", "cq_allinone_vc"),
                "param_name" => "captionsize",
                "value" => "",
                "description" => esc_attr__("Support value like 1em or 14px etc.", "cq_allinone_vc")
              ),
              array(
                "type" => "textfield",
                "edit_field_class" => "vc_col-xs-6 vc_column",
                "heading" => esc_attr__("Extra class name", "cq_allinone_vc"),
                "param_name" => "extraclass",
                "value" => "",
                "description" => esc_attr__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "cq_allinone_vc")
              ),
              array(
                "type" => "css_editor",
                "heading" => esc_attr__( "CSS", "cq_allinone_vc" ),
                "param_name" => "css",
                "description" => esc_attr__("It's recommended to use this to customize the padding/margin only.", "cq_allinone_vc"),
                "group" => esc_attr__( "Design options", "cq_allinone_vc" ),
             )
           )
        ));

        vc_map(
          array(
             "name" => esc_attr__("Coverflow Item","cq_allinone_vc"),
             "base" => "cq_vc_coverflow_item",
             "class" => "cq_vc_coverflow_item",
             "icon" => "cq_vc_coverflow_item",
             "category" => esc_attr__('Sike Extensions', 'js_composer'),
             "description" => esc_attr__("Add video and thumbnail","cq_allinone_vc"),
             "as_child" => array('only' => 'cq_vc_coverflow'),
             "show_settings_on_create" => true,
             "content_element" => true,
             "params" => array(
              array(
                  "type" => "attach_image",
                  "heading" => esc_attr__("Item image:", "cq_allinone_vc"),
                  "param_name" => "image",
                  "value" => "",
                  "description" => esc_attr__("Select from media library.", "cq_allinone_vc")
              ),
              array(
                  "type" => "vc_link",
                  "heading" => esc_attr__( "link URL (can be opened as lightbox)", "cq_allinone_vc" ),
                  "param_name" => "thelink",
                  "description" => esc_attr__("Support YouTube, Vimeo video, image, Google Map etc, for example, https://vimeo.com/639845104, or https://www.youtube.com/watch?v=ba2OnpjbncQ", "cq_allinone_vc")
              ),
              array(
                  "type" => "attach_image",
                  "heading" => esc_attr__("Lightbox image (optional):", "cq_allinone_vc"),
                  "param_name" => "lightboximage",
                  "value" => "",
                  "description" => esc_attr__("Select from media library. The link above will be ignored if added.", "cq_allinone_vc")
              ),
              array(
                "type" => "textfield",
                "heading" => esc_attr__("Thumbnail caption", "cq_allinone_vc"),
                "param_name" => "caption",
                "value" => "Default title",
                "description" => esc_attr__("Displayed under the thumbnail.", "cq_allinone_vc")
              ),
              array(
                "type" => "textfield",
                "heading" => esc_attr__("Thumbnail tooltip (optional)", "cq_allinone_vc"),
                "param_name" => "tooltip",
                "value" => "",
                "description" => esc_attr__("Tooltip for the thumbnail.", "cq_allinone_vc")
              ),


              ),
            )
        );

          add_shortcode('cq_vc_coverflow', array($this,'cq_vc_coverflow_func'));
          add_shortcode('cq_vc_coverflow_item', array($this,'cq_vc_coverflow_item_func'));

      }

      function cq_vc_coverflow_func($atts, $content=null) {
        $css_class = $css = $arrowcolor = $arrowhovercolor = $dotcolor = $dotactivecolor = $extraclass = '';
        $imageposition = $navposition = $thumbtextcolor = $captionsize = '';
        $this -> thumbtextcolor = '';
        $this -> captionsize = '';
        $this -> islightbox = '';
        extract(shortcode_atts(array(
          "thumbtextcolor" => "",
          "islightbox" => "yes",
          "captionsize" => "",
          "imageposition" => "top",
          "navposition" => "float-left",
          "arrowcolor" => "",
          "arrowhovercolor" => "",
          "dotcolor" => "mediumgray",
          "dotactivecolor" => "grass",
          "autodelay" => "no",
          "css" => "",
          "extraclass" => ""
        ),$atts));

        $output = "";
        $css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ''), 'cq_vc_coverflow', $atts);
        wp_register_style( 'vc-extensions-coverflow-style', plugins_url('css/style.css', __FILE__) );
        wp_enqueue_style( 'vc-extensions-coverflow-style' );


        wp_register_style('swiper', plugins_url('../cardslider/css/swiper-bundle.min.css', __FILE__));
        wp_enqueue_style('swiper');
        wp_register_style('tooltipster', plugins_url('../appmockup/css/tooltipster.css', __FILE__));
        wp_enqueue_style('tooltipster');

        wp_register_script('tooltipster', plugins_url('../appmockup/js/jquery.tooltipster.min.js', __FILE__), array('jquery'));
        wp_enqueue_script('tooltipster');
        wp_register_script('my-swiper', plugins_url('../cardslider/js/swiper-bundle.min.js', __FILE__), array('jquery'));
        wp_enqueue_script('my-swiper');

        wp_register_style('lity', plugins_url('../hotspot/css/lity.min.css', __FILE__));
        wp_enqueue_style('lity');

        wp_register_script('lity', plugins_url('../hotspot/js/lity.min.js', __FILE__), array('jquery'));
        wp_enqueue_script('lity');


        wp_register_script('vc-extensions-coverflow-script', plugins_url('js/init.min.js', __FILE__), array("jquery", "my-swiper", "tooltipster"));
        wp_enqueue_script('vc-extensions-coverflow-script');

        $this -> thumbtextcolor = $thumbtextcolor;
        $this -> islightbox = $islightbox;
        $this -> captionsize = $captionsize;

        $output .= '<div class="cq-coverflow-dot-'.$dotcolor.' cq-coverflow-active-'.$dotactivecolor.' cq-coverflow '.$extraclass.' '.$css_class.'" data-arrowcolor="'.$arrowcolor.'" data-autodelay="'.$autodelay.'" data-arrowhovercolor="'.$arrowhovercolor.'">';


        $output .= '<div class="swiper cq-coverflow-swiper">';
        $output .= '<div class="swiper-wrapper">';

        $output .= do_shortcode($content);

        $output .= '</div>';
        $output .= '<div class="swiper-button-next" style="color:'.$arrowcolor.';"></div>';
        $output .= '<div class="swiper-button-prev" style="color:'.$arrowcolor.';"></div>';
        $output .= '<div class="swiper-pagination"></div>';
        $output .= '</div>';


        $output .= '</div>';
        return $output;

      }


      function cq_vc_coverflow_item_func($atts, $content=null, $tag=null) {
          $output = $thelink = $videourl = $image = $imagesize = $videowidth = $isresize = $islightbox = $caption = $tooltip =  $itembgcolor =  $css =   "";
            extract(shortcode_atts(array(
              "thelink" => "",
              "islightbox" => "yes",
              "videourl" => "",
              "image" => "",
              "lightboximage" => "",
              "imagesize" => "64",
              "isresize" => "yes",
              "iscaption" => "",
              "tooltip" => "",
              "caption" => "Default title",
              "itembgcolor" => "",
              "css" => ""
            ), $atts));

          $content = wpb_js_remove_wpautop($content, true); // fix unclosed/unwanted paragraph tags in $content


          $thelink = vc_build_link($thelink);

          $img = $thumbnail = "";

          $fullimage = wp_get_attachment_image_src($image, 'full');
          $attachment = get_post($image);
          $thumbnail = $fullimage[0] ?? "";

          $openedimage = wp_get_attachment_image_src($lightboximage, 'full');
          $lightboxattachment = get_post($lightboximage);
          $openedimageurl = $openedimage[0] ?? "";

          $output = '';

          $lightboxurl = '';

          $is_lity = $this -> islightbox == "yes" ? "data-lity" : "";


          $output .= '<div class="cq-coverflow-item swiper-slide" data-image="'.$thumbnail.'" data-tooltip="'.esc_html($tooltip).'" title="'.esc_html($tooltip).'">';

          if($openedimageurl != ""){
              $output .= '<a href="'.$openedimageurl.'" class="cq-coverflow-link" title="'.get_post_meta($lightboxattachment->ID, '_wp_attachment_image_alt', true ).'" '.$is_lity.' data-lity-desc="'.get_post_meta($lightboxattachment->ID, '_wp_attachment_image_alt', true ).'">';
          } else {
              if($thelink['url'] != ""){
                  $output .= '<a href="'.$thelink['url'].'" class="cq-coverflow-link" title="'.$thelink["title"].'" '.$is_lity.' target="'.$thelink["target"].'" data-lity-desc="'.esc_html($caption).'">';
              } else {
                  $output .= '<a href="'.$thelink['url'].'" class="cq-coverflow-link" title="'.$thelink["title"].'"  target="'.$thelink["target"].'" data-lity-desc="'.esc_html($caption).'">';

              }
          }


          $output .= '<img src="'.$thumbnail.'" alt="'.get_post_meta($image, '_wp_attachment_image_alt', true ).'" class="cq-coverflow-image" />';
          if($caption != ""){
              $output .= '<span class="cq-coverflow-caption" style="color:'.$this -> thumbtextcolor.';font-size:'.$this -> captionsize.';">'.esc_html($caption).'</span>';
          }

          $output .= '</a>';

          $output .= '</div>';


          return $output;

        }

  }
}
//Extend WPBakeryShortCodesContainer class to inherit all required functionality
if ( class_exists( 'WPBakeryShortCodesContainer' ) && !class_exists('WPBakeryShortCode_cq_vc_coverflow')) {
    class WPBakeryShortCode_cq_vc_coverflow extends WPBakeryShortCodesContainer {
    }
}
if ( class_exists( 'WPBakeryShortCode' ) && !class_exists('WPBakeryShortCode_cq_vc_coverflow_item')) {
    class WPBakeryShortCode_cq_vc_coverflow_item extends WPBakeryShortCode {
    }
}

?>
