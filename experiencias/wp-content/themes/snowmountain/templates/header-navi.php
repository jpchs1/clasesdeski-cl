<?php
/**
 * The template to display the main menu
 *
 * @package WordPress
 * @subpackage SNOWMOUNTAIN
 * @since SNOWMOUNTAIN 1.0
 */
?>
<div class="top_panel_navi sc_layouts_row sc_layouts_row_type_compact sc_layouts_row_fixed
			scheme_<?php echo esc_attr(snowmountain_is_inherit(snowmountain_get_theme_option('menu_scheme')) 
												? (snowmountain_is_inherit(snowmountain_get_theme_option('header_scheme')) 
													? snowmountain_get_theme_option('color_scheme') 
													: snowmountain_get_theme_option('header_scheme')) 
												: snowmountain_get_theme_option('menu_scheme')); ?>">
	<div class="content_wrap">
		<div class="columns_wrap">
			<div class="sc_layouts_column sc_layouts_column_align_left sc_layouts_column_icons_position_left column-1_4">
				<?php
				// Logo
				?><div class="sc_layouts_item"><?php
					get_template_part( 'templates/header-logo' );
				?></div>
			</div><?php
			
			// Attention! Don't place any spaces between columns!
			?><div class="sc_layouts_column sc_layouts_column_align_right sc_layouts_column_icons_position_left column-3_4">
				<div class="sc_layouts_item">
					<?php
					// Main menu
					$snowmountain_menu_main = snowmountain_get_nav_menu(array(
						'location' => 'menu_main', 
						'class' => 'sc_layouts_menu sc_layouts_menu_default sc_layouts_hide_on_mobile'
						)
					);
					if (empty($snowmountain_menu_main)) {
						$snowmountain_menu_main = snowmountain_get_nav_menu(array(
							'class' => 'sc_layouts_menu sc_layouts_menu_default sc_layouts_hide_on_mobile'
							)
						);
					}
					snowmountain_show_layout($snowmountain_menu_main);
					// Mobile menu button
					?>
					<div class="sc_layouts_iconed_text sc_layouts_menu_mobile_button">
						<a class="sc_layouts_item_link sc_layouts_iconed_text_link" href="#">
							<span class="sc_layouts_item_icon sc_layouts_iconed_text_icon trx_addons_icon-menu"></span>
						</a>
					</div>
				</div><?php
			
				// Attention! Don't place any spaces between layouts items!
				?>
			</div>
		</div><!-- /.sc_layouts_row -->
	</div><!-- /.content_wrap -->
</div><!-- /.top_panel_navi -->