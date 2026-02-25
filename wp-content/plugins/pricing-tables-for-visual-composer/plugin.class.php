<?php



class VC_WDO_Pricing_Tables {


    /**
     * Get things started
     */
    function __construct() {
    	$current_table_style = NULL; 

        add_action('init', array($this, 'wdo_pricing_table_parent'));
        add_action('init', array($this, 'wdo_pricing_table_child'));
        add_shortcode('wdo_pricing_tables', array($this, 'wdo_tables_rendering'));
        add_shortcode('wdo_pricing_table', array($this, 'wdo_table_rendering'));
    }

    function wdo_pricing_table_parent() {
        if (function_exists("vc_map")) {

            //Register "container" content element. It will hold all your inner (child) content elements
            vc_map(array(
                "name" => __("Pricing Tables - Free", "wdo-pricing-tables"),
                "base" => "wdo_pricing_tables",
                "as_parent" => array('only' => 'wdo_pricing_table'), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
                "content_element" => true,
                "show_settings_on_create" => true,
                "category" => 'by labibahmed',
                "is_container" => true,
                'description' => __('Insert Pricing Tables', 'wdo-pricing-tables'),
                "js_view" => 'VcColumnView',
                "icon" => 'icon-wpb-pricing_column',
                "params" => array(
	                	array(
				            "type" => "dropdown",
				            "heading" => "Columns",
				            "param_name" => "wdo_columns",
				            "value" => array(
				                "Two"       => "col-lg-6 col-xs-12",
				                "Three"     => "col-lg-4 col-xs-12",
				                "Four"      => "col-lg-3 col-xs-12",
				            ),
							'save_always' => true,
				            "description" => "Select number of pricing tables you want to show in a row."
				        ),

			            array(
			                "type" => "dropdown",
			                "class" => "",
			                "heading" => "Style",
			                "param_name" => "table_price_style",
			                "value" => array(
			                    "Select Style"  => "style1",
			                    "Style 1"       => "style1",
			                    "Style 2"       => "style2",
			                    "Style 3"       => "style3",
			                    "Style 4"       => "style4",
			                    "Style 5"       => "style5",
			                ),
			        		'save_always' => true,
			            ),
                ),
            ));


        }
    }

    function wdo_pricing_table_child() {
        if (function_exists("vc_map")) {
        	$animationEffects = array(
						'Fade'			=>	'tc-animation-fade',
						'Slide Top'		=>	'tc-animation-slide-top',
						'Slide Bottom'	=>	'tc-animation-slide-bottom',
						'Slide Left'	=>	'tc-animation-slide-left',
						'Slide Right'	=>	'tc-animation-slide-right',
						'Scale Up'		=>	'tc-animation-scale-up',
						'Scale Down'	=>	'tc-animation-scale-down',
						'Shake'			=>	'tc-animation-shake',
						'Rotate'		=>	'tc-animation-rotate',
						'Scale'			=>	'tc-animation-scale',
						'Scale'			=>	'tc-animation-scale',
			);

            //Register "container" content element. It will hold all your inner (child) content elements
            vc_map(array(
                "name" => __("Pricing Table", "wdo-pricing-tables"),
                "base" => "wdo_pricing_table",
                "content_element" => true,
                "as_child" => array('only' => 'wdo_pricing_tables'), // Use only|except attributes to limit parent (separate multiple values with comma)
                "icon" => 'icon-wpb-pricing_column',
                "params" => array(
			                	
								array(
									"type" => "textfield",
									"heading" => "Title",
									"param_name" => "table_title",
									"value" => "Basic Plan",
									"group"         => "Typography",
								),
								array(
									"type" => "textfield",
									"heading" => "Price",
									"param_name" => "table_price",
									"value" => "10",
									"group"         => "Typography",
								),
								array(
									"type" => "textfield",
									"heading" => "Currency",
									"param_name" => "table_currency",
									"description" => "Give your currency symbol here such as $.",
									"value" => "$",
									"group"         => "Typography",
								),
								array(
									"type" => "textfield",
									"heading" => "Price Period",
									"param_name" => "table_price_period",
									"value" => "Month",
									"group"         => "Typography",
								),
								array(
									"type" => "textarea_html",
									"heading" => "Content",
									"param_name" => "content",
									"value" => '<li class="whyt">Your Content Here</li>
												<li>Your Content Here</li>
												<li class="whyt">Your Content Here</li>
												<li>Your Content Here</li>
												<li class="whyt">Your Content Here</li>',
									"description" => "Add your listing here you could also use HTML here.",
									"group"         => "Typography",
								),

								array(
									"type" => "dropdown",
									"heading" => "Show Button",
									"param_name" => "table_show_button",
									"value" => array(
										"Yes" => "yes",
										"No" => "no"
									),
									'save_always' => true,
									"group"         => "Button",
								),
					            array(
					                "type" => "textfield",
					                "heading" => "Button Text",
					                "param_name" => "table_button_text",
					                "value" => "Purchase Now",
					                "description" => "Default label is Purchase",
					                "dependency" => array('element' => 'table_show_button', 'value' => 'yes'),
					                "group"         => "Button",
					            ),
								array(
									"type" => "textfield",
									"heading" => "Button Link",
									"param_name" => "table_link",
									"value" => "#",
									"dependency" => array('element' => 'table_show_button', 'value' => 'yes'),
									"group"         => "Button",
								),
								array(
									"type" => "dropdown",
									"class" => "",
									"heading" => "Button Target",
									"param_name" => "table_target",
									"value" => array(
										"Self" => "_self",
										"Blank" => "_blank",	
										"Parent" => "_parent"
									),
									"dependency" => array('element' => 'table_show_button', 'value' => 'yes'),
									"group"         => "Button",
								),
								array(
									"type" => "dropdown",
									"class" => "",
									"heading" => "Featured",
									"param_name" => "active",
									"value" => array(
										"No" => "no",
										"Yes" => "yes"	
									),
									'save_always' => true,
									"description" => "This would be shown as different as compared to other tables.",
									"group"         => "Featured",
								),
					            array(
					                "type" => "textfield",
					                "heading" => "Featured Text",
					                "param_name" => "active_text",
					                "value" => "Featured Plan",
					                "description" => "This will be shown at the top right corner of table",
					                "dependency" => array('element' => 'active', 'value' => 'yes'),
					                "group"         => "Featured",
					            ),


								/*Animation and style tab*/
								array(
						            "type" => "dropdown",
						            "heading" => "Style",
						            "group" => "Pro Features",
						            "param_name" => "wdo_tables_style_pro",
						            "value" => array(
						                "Style 1"       => "pricing-style1",
						                "Style 2"       => "pricing-style2",
						                "Style 3"       => "pricing-style3",
						                "Style 4"       => "pricing-style4",
						                "Style 5"       => "pricing-style5",
						                "Style 6"       => "pricing-style6", 
						                "Style 7"       => "pricing-style7",
						                "Style 8"       => "pricing-style8",
						                "Style 9"       => "pricing-style9",
						                "Style 10"      => "pricing-style10",
						                "Style 11"      => "pricing-style11",
						                "Style 12"      => "pricing-style12",
						                "Style 13"      => "pricing-style13",
						                "Style 14"      => "pricing-style14",
						                "Style 15"      => "pricing-style15",
						                "Style 16"      => "pricing-style16", 
						            ),
						            "description" => ""
						        ),
								
								array(
									"type" => "dropdown",
									"heading" => "Animations",
									"group" => "Pro Features",
									"param_name" => "pricing_animation_pro",
									"value" => $animationEffects,
									"description" => "Select animation when hovered over pricing."
								),

								array(
									"type" => "dropdown",
									"heading" => "Color Scheme",
									"group" => "Pro Features",
									"param_name" => "pricing_color_scheme",
									"value" => array(
										"Blue" => "blue.css",
										"Green" => "green.css",	
										"Orange" => "orange.css",
										"Red" 	=> "red.css",
										"Violet" 	=> "violet.css",
									),
									"description" => ""
								),
								array(
									"type" => "html",
									"heading" => "
													<h3 style='padding: 10px;background: #2b4b80;color: #fff;'>Limited Offer : 60% Discount</h3>
													<a target='_blank' href='https://webdevocean.com/product/pricing-table-vc-addon/' >Get Pro Version in $5</a>",
									"param_name" => "pro_feature",
									"group" => "Pro Features"
								),

								
					)
            ));


        }
    }

    function wdo_tables_rendering($atts, $content = null, $tag) {

		global $current_table_style;
        extract(shortcode_atts(array( 
            'wdo_columns' => 'vc_col-sm-4',
            'table_price_style' => 'style1',
        ), $atts)); 
        $current_table_style = $table_price_style;
        ob_start();

        ?>
     
        <div class="pricing-plans">    
            <div class="wrap">
                <div class="pricing-grids row" data-cols="<?php echo $wdo_columns; ?>">
                	<?php do_shortcode( $content ); ?>
                </div>
            </div>
        </div>

        <?php

        $output = ob_get_clean();

        return $output;
    }


    function wdo_table_rendering($atts, $content = null, $tag) {

    	global $current_table_style;
	    extract(shortcode_atts(array(
	        'table_title' => 'Basic Plan', 
	        'table_price' => '0',
	        'table_currency' => '$',
	        'table_price_period' => 'month',
	        'table_show_button' => 'yes',
	        'table_button_text' => 'Purchase',
	        'table_link' => '',
	        'table_target' => '_self', 
	        'active' => 'yes',
	        'active_text' => '',

	    ), $atts)); 
	    	wp_enqueue_style( 'pricing-bs-css', plugins_url( 'css/bootstrap.min.css' , __FILE__ ));
	    	wp_enqueue_style( 'pricing-table-css', plugins_url( 'css/pure-pricing.css' , __FILE__ ));
	    	wp_enqueue_script( 'wdo-pricing-js', plugins_url( '/js/pure-table.js', __FILE__ ),array('jquery'));

	    	$template_path ='templates/'.$current_table_style.'.php';
			include $template_path; 
	    ?>
		



<?php
	} 

}

//Your "container" content element should extend WPBakeryShortCodesContainer class to inherit all required functionality
if (class_exists('WPBakeryShortCodesContainer')) {
    class WPBakeryShortCode_wdo_pricing_tables extends WPBakeryShortCodesContainer {
    }
}
if (class_exists('WPBakeryShortCode')) {
    class WPBakeryShortCode_wdo_pricing_table extends WPBakeryShortCode {
    }
}