<?php
/**
 * The Helper class to manage all public facing stuffs.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package Testimonial_pro.
 * @subpackage Testimonial_Pro/Frontend
 */

namespace ShapedPlugin\TestimonialPro\Frontend;

use ShapedPlugin\TestimonialPro\Includes\SP_Testimonial_Pro_Functions;
use ShapedPlugin\TestimonialPro\Includes\ImageResize;

/**
 * Helper
 */
class Helper {

	/**
	 * Custom Template locator.
	 *
	 * @param  mixed $template_name template name.
	 * @param  mixed $template_path template path.
	 * @param  mixed $default_path default path.
	 * @return string
	 */
	public static function sptp_locate_template( $template_name, $template_path = '', $default_path = '' ) {
		if ( ! $template_path ) {
			$template_path = 'testimonial-pro/templates';
		}

		if ( ! $default_path ) {
			$default_path = SP_TPRO_PATH . 'Frontend/views/templates/';
		}

		$template = locate_template( trailingslashit( $template_path ) . $template_name );

		// Get default template.
		if ( ! $template ) {
			$template = $default_path . $template_name;
		}
		// Return what we found.
		return $template;
	}

	/**
	 * Redirect function.
	 *
	 * @param string $url url.
	 * @param string $hash hash url.
	 * @return void
	 */
	public static function tpro_redirect( $url, $hash = false ) {
		if ( $hash ) {
			$string  = '<script type="text/javascript">';
			$string .= '
			var elmnt = document.getElementById("' . $url . '");
			elmnt.scrollIntoView(true);
			// window.location.hash = "' . $url . '"';
			$string .= '</script>';
		} else {
			$string  = '<script type="text/javascript">';
			$string .= 'window.location = "' . esc_url( $url ) . '"';
			$string .= '</script>';
		}

		echo $string;
	}

	/**
	 * The social icons.
	 *
	 * @param string $social_name social name.
	 * @return string
	 */
	public static function tpro_social_icon( $social_name ) {
		$function         = new SP_Testimonial_Pro_Functions();
		$social_icon_list = $function->social_profile_list();
		foreach ( $social_icon_list as $social_icon_id => $social_icon ) {
			if ( $social_icon_id == $social_name ) {
				return $social_icon['icon'];
			}
		}
	}

	/**
	 * Image resize function to crop the images with custom width and height.
	 *
	 * @param string  $url The image URL.
	 * @param int     $width The width of the image.
	 * @param int     $height The image height.
	 * @param boolean $crop Hard crop or not.
	 * @param boolean $single  The image.
	 * @param boolean $upscale The upscale.
	 * @return statement
	 */
	public static function sptp_image_resize( $url, $width = null, $height = null, $crop = null, $single = true, $upscale = false ) {
		/*
		WPML Fix
		if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
		global $sitepress;
		$url = $sitepress->convert_url( $url, $sitepress->get_default_language() );
		}
		WPML Fix
		*/
		$sptp_image_resize = ImageResize::getInstance();
		return $sptp_image_resize->process( $url, $width, $height, $crop, $single, $upscale );
	}

	/**
	 * Short content of the text.
	 *
	 * @param mixed  $testimonial_strip_tags use allow and strip tags.
	 * @param mixed  $testimonial_content_length_type get word and characters.
	 * @param mixed  $full_content get full content.
	 * @param int    $testimonial_word_limit The number of words to display.
	 * @param int    $testimonial_characters_limit The number of words to display.
	 * @param string $testimonial_read_more_ellipsis The ellipsis at the end of the text.
	 * @return statement
	 */
	public static function short_content( $testimonial_strip_tags, $testimonial_content_length_type, $full_content, $testimonial_word_limit, $testimonial_characters_limit, $testimonial_read_more_ellipsis ) {
		$limit_chars = self::limit_content_chr( $full_content, (int) $testimonial_characters_limit, $testimonial_read_more_ellipsis );
		$limit_words = self::limit_content_words( $full_content, (int) $testimonial_word_limit, $testimonial_read_more_ellipsis );
		if ( 'allow_all' === $testimonial_strip_tags ) {
			$limit_chars = $limit_chars;
			$limit_words = $limit_words;
		} elseif ( 'strip_all' === $testimonial_strip_tags ) {
			$limit_chars      = wp_html_excerpt( $full_content, (int) $testimonial_characters_limit, $testimonial_read_more_ellipsis );
			$_trimmed_content = $limit_words;
			$limit_words      = wp_strip_all_tags( $_trimmed_content );
		}

		$short_content = 'characters' === $testimonial_content_length_type ? $limit_chars : $limit_words;
		return force_balance_tags( $short_content );
	}

	/**
	 * Characters Limit of the text.
	 *
	 * @param mixed  $text The text you want to limit.
	 * @param int    $limit The number of words to display.
	 * @param string $ellipsis The ellipsis at the end of the text.
	 * @return statement
	 */
	public static function limit_content_chr( $text, $limit, $ellipsis = '...' ) {
		$length = $limit;
		if ( strlen( $text ) < $length + 10 ) {
			return $text; // don't cut if too short.
		}
		$visible = substr( $text, 0, $length );
		return $visible . $ellipsis;
	}

	/**
	 * Limit the the text.
	 *
	 * @param mixed  $text The text you want to limit.
	 * @param int    $limit The number of words to display.
	 * @param string $ellipsis The ellipsis at the end of the text.
	 * @return statement
	 */
	public static function limit_content_words( $text, $limit, $ellipsis = '...' ) {
		$word_arr = explode( ' ', $text );
		if ( count( $word_arr ) > $limit ) {
			$words = implode( ' ', array_slice( $word_arr, 0, $limit ) ) . $ellipsis;
			return $words;
		} else {
			return $text;
		}
	}

	/**
	 * Minify output
	 *
	 * @param  statement $html output.
	 * @return statement
	 */
	public static function minify_output( $html ) {
		$html = preg_replace( '/<!--(?!s*(?:[if [^]]+]|!|>))(?:(?!-->).)*-->/s', '', $html );
		$html = str_replace( array( "\r\n", "\r", "\n", "\t" ), '', $html );
		while ( stristr( $html, '  ' ) ) {
			$html = str_replace( '  ', ' ', $html );
		}
		return $html;
	}

	/**
	 * Testimonial items
	 *
	 * @param  object $post_query Query.
	 * @param  array  $shortcode_data options.
	 * @param  string $layout layout.
	 * @param  int    $post_id shortcode id.
	 * @return array
	 */
	public static function testimonial_items( $post_query, $shortcode_data, $layout, $post_id ) {
		include SP_TPRO_PATH . 'Frontend/views/partials/loop-settings.php';
		ob_start();
		$post_ids    = $post_query['all_testimonial_ids'];
		$post_query  = $post_query['query'];
		$total_posts = '';
		if ( $post_query->have_posts() ) {
			$tpro_total_rating   = 0;
			$total_posts         = $post_query->found_posts;
			$per_page_post_count = $post_query->post_count;
			$testimonial_count   = 0;
			$aggregate_rating    = 0;
			while ( $post_query->have_posts() ) :
				$post_query->the_post();
				global $post;
				$testimonial_data  = get_post_meta( get_the_ID(), 'sp_tpro_meta_options', true );
				$tpro_rating_star  = isset( $testimonial_data['tpro_rating'] ) ? $testimonial_data['tpro_rating'] : '';
				$tpro_designation  = isset( $testimonial_data['tpro_designation'] ) ? $testimonial_data['tpro_designation'] : '';
				$tpro_company_logo = isset( $testimonial_data['tpro_company_logo']['id'] ) ? $testimonial_data['tpro_company_logo']['id'] : '';
				$tpro_name         = isset( $testimonial_data['tpro_name'] ) ? $testimonial_data['tpro_name'] : '';
				$tpro_company_name = isset( $testimonial_data['tpro_company_name'] ) ? $testimonial_data['tpro_company_name'] : '';
				$tpro_website      = isset( $testimonial_data['tpro_website'] ) ? $testimonial_data['tpro_website'] : '';
				$tpro_location     = isset( $testimonial_data['tpro_location'] ) ? $testimonial_data['tpro_location'] : '';
				$tpro_phone        = isset( $testimonial_data['tpro_phone'] ) ? $testimonial_data['tpro_phone'] : '';
				$tpro_email        = isset( $testimonial_data['tpro_email'] ) ? $testimonial_data['tpro_email'] : '';

				$full_content = apply_filters( 'the_content', get_the_content() );

				$short_content = self::short_content( $testimonial_strip_tags, $testimonial_content_length_type, $full_content, $testimonial_word_limit, $testimonial_characters_limit, $testimonial_read_more_ellipsis );
				$count         = strlen( $full_content );
				$review_text   = ( 'full_content' === $testimonial_content_type || $testimonial_read_more && 'expand' === $testimonial_read_more_link_action ) ? $full_content : $short_content;
				$review_text   = apply_filters( 'sp_testimonial_review_content', $review_text, $post_id );
				if ( ! $show_testimonial_text && $testimonial_read_more ) {
					$testimonial_read_more_link_action = 'popup';
				}
				switch ( $testimonial_read_more_link_action ) {
					case 'expand':
						$read_more_data = '<a href="#" class="tpro-read-more"></a>';
						break;
					case 'popup':
						$read_more_data = sprintf( '<a href="#" data-remodal-target="sp-tpro-testimonial-id-%1$s" class="tpro-read-more">%2$s</a>', get_the_ID(), $testimonial_read_more_text );
						break;
				}
				$read_more_link = ( $count >= $testimonial_characters_limit && $testimonial_read_more && 'content_with_limit' === $testimonial_content_type ) ? $read_more_data : '';
				$read_more_link = apply_filters( 'sp_testimonial_read_more_link', $read_more_link, $post_id );

				$testimonial_title_with_limit = ( '' !== $title_limit ) ? wp_html_excerpt( get_the_title(), $title_limit, '...' ) : get_the_title();
				$t_cat_terms                  = get_the_terms( get_the_ID(), 'testimonial_cat' );
				if ( $t_cat_terms && ! is_wp_error( $t_cat_terms ) && ! empty( $t_cat_terms ) ) {
					$t_cat_name = array();
					foreach ( $t_cat_terms as $t_cat_term ) {
						$t_cat_name[] = $t_cat_term->name;
					}
					$tpro_itemreviewed = $t_cat_name[0];
				} else {
					$tpro_itemreviewed = $tpro_global_item_name;
				}
				if ( 'slider' === $layout && $thumbnail_slider ) {
					include SP_TPRO_PATH . 'Frontend/views/partials/thumbnail-slider-items.php';
				} else {
					switch ( $layout ) {
						case 'grid':
						case 'masonry':
						case 'filter':
							$layout_class = sprintf( ( join( ' ', get_post_class( 'tpro-col-xl-%1$s tpro-col-lg-%2$s tpro-col-md-%3$s tpro-col-sm-%4$s tpro-col-xs-%5$s' ) ) ), $columns_large_desktop, $columns_desktop, $columns_laptop, $columns_tablet, $columns_mobile );
							break;
						case 'slider':
							if ( 'fade' === $slider_animation ) {
								$layout_class = '';
							} else {
								$layout_class = 'swiper-slide';
							}
							break;
						default:
							$layout_class = '';
							break;
					}
					include SP_TPRO_PATH . 'Frontend/views/partials/item.php';
				}
				// Schema markup.
				if ( $tpro_schema_markup ) {
					$tpro_name        = isset( $testimonial_data['tpro_name'] ) ? $testimonial_data['tpro_name'] : '';
					$tpro_rating_star = isset( $testimonial_data['tpro_rating'] ) ? $testimonial_data['tpro_rating'] : '';

					switch ( $tpro_rating_star ) {
						case 'five_star':
							$rating_value = '5';
							break;
						case 'four_star':
							$rating_value = '4';
							break;
						case 'three_star':
							$rating_value = '3';
							break;
						case 'two_star':
							$rating_value = '2';
							break;
						case 'one_star':
							$rating_value = '1';
							break;
						default:
							$rating_value = '0';
					}

					$name         = get_the_title() ? esc_attr( wp_strip_all_tags( get_the_title() ) ) : '';
					$review_body  = get_the_content() ? esc_attr( wp_strip_all_tags( get_the_content() ) ) : '';
					$date         = get_the_date( 'F j, Y' );
					$schema_html .= '{
						"@type": "Review",
						"datePublished": "' . $date . '",
						"name": "' . $name . '",
						"reviewBody": "' . $review_body . '",
						"reviewRating": {
							"@type": "Rating",
							"bestRating": "5",
							"ratingValue": "' . $rating_value . '",
							"worstRating": "1"
						},
						"author": {
							"@type": "Person",
							"name": "' . $tpro_name . '"
						}
					}';

					if ( ++$testimonial_count !== $per_page_post_count ) {
						$schema_html .= ',';
					}
				}
			endwhile;

		} else {
			echo '<h2 class="sp-not-found-any-testimonial">' . esc_html( apply_filters( 'sptpro_not_found_any_testimonial', __( 'No testimonials found', 'testimonial-pro' ) ) ) . '</h2>';
		}
		wp_reset_postdata();
		$output           = ob_get_clean();
		$aggregate_rating = 5;

			$tpro_total_rating = 0;
		foreach ( $post_ids as $id ) {
			$testimonial_data = get_post_meta( $id, 'sp_tpro_meta_options', true );
			$tpro_rating_star = isset( $testimonial_data['tpro_rating'] ) ? $testimonial_data['tpro_rating'] : '';
			switch ( $tpro_rating_star ) {
				case 'five_star':
					$rating_value = '5';
					break;
				case 'four_star':
					$rating_value = '4';
					break;
				case 'three_star':
					$rating_value = '3';
					break;
				case 'two_star':
					$rating_value = '2';
					break;
				case 'one_star':
					$rating_value = '1';
					break;
				default:
					$rating_value = '5';
			}
			$tpro_total_rating += (int) $rating_value;
		}
		$total_posts      = $total_posts > 0 ? $total_posts : 1;
		$aggregate_rating = round( ( $tpro_total_rating / $total_posts ), 2 );
		return array(
			'output'                          => $output,
			'aggregate_rating'                => $aggregate_rating,
			'schema_html'                     => $schema_html,
			'total_testimonial'               => $total_posts,
			'thumbnail_slider_content_markup' => $thumbnail_slider_content_markup,
			'thumbnail_slider_image_markup'   => $thumbnail_slider_image_markup,
		);
	}

	/**
	 * The font variants for the Advanced Typography.
	 *
	 * @param string $sp_tpro_font_variant The typography field ID with.
	 * @param string $font_style font style.
	 * @return string
	 * @since 1.0
	 */
	public static function tpro_the_font_variants( $sp_tpro_font_variant, $font_style = 'normal' ) {
		$filter_style  = isset( $font_style ) && ! empty( $font_style ) ? $font_style : 'normal';
		$filter_weight = isset( $sp_tpro_font_variant ) && ! empty( $sp_tpro_font_variant ) ? $sp_tpro_font_variant : '400';

		return 'font-style: ' . $filter_style . '; font-weight: ' . $filter_weight . ';';
	}

	/**
	 * Advanced Typography Output.
	 *
	 * @param string $tpro_normal_typography The typography array.
	 * @param string $font_load The typography font load conditions.
	 * @return string
	 * @since 1.0
	 */
	public static function tpro_typography_output( $tpro_normal_typography, $font_load ) {
		$typo = '';
		if ( isset( $tpro_normal_typography['color'] ) ) {
			$typo .= 'color: ' . $tpro_normal_typography['color'] . ';';
		}
		if ( isset( $tpro_normal_typography['font-size'] ) ) {
			$typo .= 'font-size: ' . $tpro_normal_typography['font-size'] . 'px;';
		}
		if ( isset( $tpro_normal_typography['line-height'] ) ) {
			$typo .= 'line-height: ' . $tpro_normal_typography['line-height'] . 'px;';
		}
		if ( isset( $tpro_normal_typography['text-transform'] ) ) {
			$typo .= 'text-transform: ' . $tpro_normal_typography['text-transform'] . ';';
		}
		if ( isset( $tpro_normal_typography['letter-spacing'] ) ) {
			$typo .= 'letter-spacing: ' . $tpro_normal_typography['letter-spacing'] . 'px;';
		}
		if ( isset( $tpro_normal_typography['text-align'] ) ) {
			$typo .= 'text-align: ' . $tpro_normal_typography['text-align'] . ';';
		}
		if ( $font_load ) {
			$typo .= ' font-family: ' . $tpro_normal_typography['font-family'] . ';
			' . self::tpro_the_font_variants( $tpro_normal_typography['font-weight'], $tpro_normal_typography['font-style'] );
		}
		return $typo;
	}

	/**
	 * Advanced Typography Output.
	 *
	 * @param string $tpro_normal_typography The typography array.
	 * @param string $font_load The typography font load conditions.
	 * @return string
	 * @since 1.0
	 */
	public static function tpro_typography_modal_output( $tpro_normal_typography, $font_load ) {
		$typo = 'color: #444444; text-align: center;';
		if ( isset( $tpro_normal_typography['font-size'] ) ) {
			$typo .= 'font-size: ' . $tpro_normal_typography['font-size'] . 'px;';
		}
		if ( isset( $tpro_normal_typography['line-height'] ) ) {
			$typo .= 'line-height: ' . $tpro_normal_typography['line-height'] . 'px;';
		}if ( isset( $tpro_normal_typography['text-transform'] ) ) {
			$typo .= 'text-transform: ' . $tpro_normal_typography['text-transform'] . ';';
		}
		if ( isset( $tpro_normal_typography['letter-spacing'] ) ) {
			$typo .= 'letter-spacing: ' . $tpro_normal_typography['letter-spacing'] . 'px;';
		}
		if ( $font_load ) {
			$typo .= 'font-family: ' . $tpro_normal_typography['font-family'] . '; ' . self::tpro_the_font_variants( $tpro_normal_typography['font-weight'], $tpro_normal_typography['font-style'] );
		}
		return $typo;
	}

	/**
	 * Item schema markup
	 *
	 * @param  object $post_query query.
	 * @param  string $tpro_global_item_name Global item name.
	 * @param  string $aggregate_rating ratting.
	 * @param  string $schema_html schema HTML.
	 * @param  int    $total_posts  total post.
	 * @return void
	 */
	public static function testimonials_schema( $post_query, $tpro_global_item_name, $aggregate_rating, $schema_html, $total_posts ) {
		$schema_type = apply_filters( 'sptestimonialpro_schema_type', 'Product' );
		if ( $post_query->have_posts() ) {
			ob_start();
			include self::sptp_locate_template( 'schema-markup.php' );
			echo ob_get_clean();
		}
	}

	/**
	 * Testimonial Query
	 *
	 * @param  array  $shortcode_data shortcode options.
	 * @param  int    $post_id shortcode id.
	 * @param  string $value search value.
	 * @param  string $group get testimonial groups.
	 * @param  string $page gets page number.
	 * @return object
	 */
	public static function testimonial_query( $shortcode_data, $post_id, $value = '', $group = '', $page = '' ) {
		$layout               = isset( $shortcode_data['layout'] ) ? $shortcode_data['layout'] : 'slider';
		$total_testimonial    = isset( $shortcode_data['number_of_total_testimonials'] ) ? $shortcode_data['number_of_total_testimonials'] : '';
		$total_testimonials   = ! empty( $total_testimonial ) ? $total_testimonial : 1000;
		$testimonial_per_page = isset( $shortcode_data['tp_per_page'] ) ? $shortcode_data['tp_per_page'] : '10';

		$random_order              = isset( $shortcode_data['random_order'] ) ? $shortcode_data['random_order'] : '';
		$tpro_order_by             = isset( $shortcode_data['testimonial_order_by'] ) ? $shortcode_data['testimonial_order_by'] : 'menu_order';
		$order_by                  = $random_order ? 'rand' : $tpro_order_by;
		$order                     = isset( $shortcode_data['testimonial_order'] ) ? $shortcode_data['testimonial_order'] : 'DESC';
		$display_testimonials_from = isset( $shortcode_data['display_testimonials_from'] ) ? $shortcode_data['display_testimonials_from'] : 'latest';
		$category_operator         = isset( $shortcode_data['category_operator'] ) ? $shortcode_data['category_operator'] : 'IN';
		$specific_testimonial      = isset( $shortcode_data['specific_testimonial'] ) ? $shortcode_data['specific_testimonial'] : '';
		$grid_pagination           = isset( $shortcode_data['grid_pagination'] ) ? $shortcode_data['grid_pagination'] : '';
		$category_list             = isset( $shortcode_data['category_list'] ) ? $shortcode_data['category_list'] : '';
		$exclude_testimonial       = isset( $shortcode_data['exclude_testimonial'] ) ? $shortcode_data['exclude_testimonial'] : '';
		$pagination_type           = isset( $shortcode_data['tp_pagination_type'] ) ? $shortcode_data['tp_pagination_type'] : 'no_ajax';

		$tpargs      = array(
			'post_type'        => 'spt_testimonial',
			'posts_per_page'   => $total_testimonials,
			'fields'           => 'ids',
			'orderby'          => $order_by,
			'order'            => $order,
			'suppress_filters' => false,
		);
		$tax_setting = array();
		if ( 'specific_testimonials' === $display_testimonials_from && ! empty( $specific_testimonial ) ) {
			$order_by           = 'menu_order' === $order_by ? 'post__in' : $order_by;
			$tpargs['post__in'] = $specific_testimonial;
			$tpargs['orderby']  = $order_by;
		} elseif ( 'category' === $display_testimonials_from && ! empty( $category_list ) ) {
			$tax_setting[] = array(
				'taxonomy' => 'testimonial_cat',
				'field'    => 'term_id',
				'terms'    => $category_list,
				'operator' => $category_operator,
			);
			$tpargs        = array_merge( $tpargs, array( 'tax_query' => $tax_setting ) );
		}
		if ( isset( $group ) && ! empty( $group ) ) {
			$tpargs['tax_query'] = array(
				array(
					'taxonomy' => 'testimonial_cat',
					'field'    => 'slug',
					'terms'    => $group,
				),
			);
		}
		if ( 'filter' === $layout ) {
			$grid_pagination      = isset( $shortcode_data['filter_pagination'] ) ? $shortcode_data['filter_pagination'] : false;
			$testimonial_per_page = isset( $shortcode_data['filter_per_page'] ) ? $shortcode_data['filter_per_page'] : $total_testimonials;
		}
		// If orderBy is random and not ajax pagination then run this block of code.
		if ( 'rand' === $order_by && 'slider' !== $layout && $grid_pagination ) {
			$tpargs['orderby'] = 'rand(' . get_transient( 'sp_testimonial_rand_order' . $post_id ) . ')';
		}
		if ( 'exclude' === $display_testimonials_from ) {
			$display_testimonials_from = 'latest';
		}
		if ( 'specific_testimonials' !== $display_testimonials_from && ! empty( $exclude_testimonial ) ) {
			$tpargs['post__not_in'] = $exclude_testimonial;
		}
		$tpargs     = apply_filters( 'spt_testimonial_pro_query_all_post_arg', $tpargs, $post_id );
		$meta_value = array();
		if ( ! empty( $value ) ) {
			$tpargs['s'] = $value;
			$meta_value  = array(
				'post_type' => 'spt_testimonial',
				'fields'    => 'ids',
			);

			$meta_value['meta_query'][] = array(
				'key'     => 'sp_tpro_meta_options',
				'value'   => $value,
				'compare' => 'LIKE',
			);

			$meta_value = get_posts( $meta_value );
		}

		$all_post_ids = get_posts( $tpargs );
		$all_post_ids = array_unique( array_merge( $all_post_ids, $meta_value ) );
		$all_post_ids = $all_post_ids ? $all_post_ids : array( 0 );
		$args         = array(
			'post_type'        => 'spt_testimonial',
			'orderby'          => $order_by,
			'order'            => $order,
			'post__in'         => $all_post_ids,
			'posts_per_page'   => $total_testimonials,
			'suppress_filters' => apply_filters( 'spt_testimonial_pro_suppress_filters', false, $post_id ),
		);

		if ( ( 'grid' === $layout || 'masonry' === $layout || 'list' === $layout || 'filter' === $layout ) && $grid_pagination ) {
			$paged                  = 'paged' . $post_id;
			$paged                  = isset( $_GET[ "$paged" ] ) ? wp_unslash( absint( $_GET[ "$paged" ] ) ) : 1;
			$args['posts_per_page'] = ! empty( $value ) ? $total_testimonials : $testimonial_per_page;
			$args['paged']          = $paged;
			if ( ! empty( $page ) && isset( $page ) ) {
					$args['paged'] = $page;
			}
			// If orderBy is random then run this block of code.
			if ( 'rand' === $order_by ) {
				if ( $paged && 1 === $paged ) {
					set_transient( 'sp_testimonial_rand_order' . $post_id, wp_rand() );
				}
				$args['orderby'] = 'rand(' . get_transient( 'sp_testimonial_rand_order' . $post_id ) . ')';
			}
		}
		$args       = array_merge( $args, array( 'tax_query' => $tax_setting ) );
		$args       = apply_filters( 'spt_testimonial_pro_query_args', $args, $post_id );
		$post_query = new \WP_Query( $args );
		return array(
			'query'               => $post_query,
			'all_testimonial_ids' => $all_post_ids,
		);
	}

	/**
	 * Retrieves the isotope button metadata for testimonials.
	 *
	 * @param array $filter_layout_testimonials The filter layout testimonials array.
	 * @param array $shortcode_data The shortcode data array.
	 * @param int   $id The ID of the post.
	 * @return array The filter groups metadata.
	 */
	public static function isotope_button_meta( $filter_layout_testimonials, $shortcode_data, $id ) {
		$filter_array   = array();
		$group_relation = str_replace( ' ', '', $shortcode_data['category_operator'] );

		// This block of code has been executed for the selected groups.
		// Check if specific category lists are defined and 'display_testimonials_from' is set to 'category'.
		// Also, check that the category operator is not 'NOTIN'.
		if ( isset( $shortcode_data['category_list'] ) && '' !== $shortcode_data['category_list'] && 'category' === $shortcode_data['display_testimonials_from'] && 'NOTIN' !== $group_relation ) {
			$testimonial_groups = $shortcode_data['category_list'];
			if ( ! empty( $testimonial_groups ) ) {
				foreach ( $testimonial_groups as $testimonial_group ) {
					// Get the term object and add it to the filter array.
					if ( term_exists( (int) $testimonial_group, 'testimonial_cat' ) ) {
						$filter_array[] = get_term( $testimonial_group );
					}
				}
			}
		} else {
			// This block of code has been executed for all groups.
			foreach ( $filter_layout_testimonials as $testimonial ) {
				$testimonial_groups = get_the_terms( $testimonial, 'testimonial_cat' );
				if ( ! empty( $testimonial_groups ) ) {
					foreach ( $testimonial_groups as $testimonial_group ) {
						// Add child group to the filter array.
						$filter_array[] = $testimonial_group;
					}
				}
			}
		}

		// Remove duplicates from the filter array.
		$unique_filter_array = array_unique( $filter_array, SORT_REGULAR );
		$group_filter        = array();

		// Process the unique filter array to create the group filter.
		foreach ( $unique_filter_array as $testimonial_group ) {
			if ( ! isset( $testimonial_group->name ) ) {
				continue;
			}
			$group_filter[ $testimonial_group->name ] = array(
				'id'   => isset( $testimonial_group->term_id ) ? $testimonial_group->term_id : $testimonial_group->id,
				'name' => $testimonial_group->name,
				'slug' => $testimonial_group->slug,
			);
		}

		// Sort the group filter by name.
		ksort( $group_filter );

		// Create the filter groups array.
		$filter_groups = array(
			'category' => apply_filters( 'sp_testimonial_group_filter_sort', $group_filter, $id ),
		);

		// Return the filter groups.
		return $filter_groups;
	}

	/**
	 * The load google fonts function merge all fonts from shortcodes.
	 *
	 * @param  array $typography store the all shortcode typography.
	 * @return array
	 */
	public static function load_google_fonts( $typography ) {
		$enqueue_fonts = array();
		if ( ! empty( $typography ) ) {
			foreach ( $typography as $font ) {
				if ( isset( $font['type'] ) && 'google' === $font['type'] ) {
					$weight          = isset( $font['font-weight'] ) ? ( ( 'normal' !== $font['font-weight'] ) ? ':' . $font['font-weight'] : ':400' ) : ':400';
					$style           = isset( $font['font-style'] ) ? substr( $font['font-style'], 0, 1 ) : '';
					$enqueue_fonts[] = str_replace( ' ', '+', $font['font-family'] ) . $weight . $style;
				}
			}
		}
		$enqueue_fonts = array_unique( $enqueue_fonts );
		return $enqueue_fonts;
	}

	/**
	 * Gets the existing shortcode-id, page-id and option-key from the current page.
	 *
	 * @return array
	 */
	public static function get_page_data() {
		$current_page_id    = get_queried_object_id();
		$option_key         = 'sp_testimonial_page_id' . $current_page_id;
		$found_generator_id = get_option( $option_key );
		if ( is_multisite() ) {
			$option_key         = 'sp_testimonial_page_id' . get_current_blog_id() . $current_page_id;
			$found_generator_id = get_site_option( $option_key );
		}
		$get_page_data = array(
			'page_id'      => $current_page_id,
			'generator_id' => $found_generator_id,
			'option_key'   => $option_key,
		);
		return $get_page_data;
	}

	/**
	 * If the option does not exist, it will be created.
	 *
	 * It will be serialized before it is inserted into the database.
	 *
	 * @param  string $post_id existing shortcode id.
	 * @param  array  $get_page_data get current page-id, shortcode-id and option-key from the page.
	 * @return void
	 */
	public static function db_options_update( $post_id, $get_page_data ) {
		$found_generator_id = $get_page_data['generator_id'];
		$option_key         = $get_page_data['option_key'];
		$current_page_id    = $get_page_data['page_id'];
		if ( $found_generator_id ) {
			$found_generator_id = is_array( $found_generator_id ) ? $found_generator_id : array( $found_generator_id );
			if ( ! in_array( $post_id, $found_generator_id ) || empty( $found_generator_id ) ) {
				// If not found the shortcode id in the page options.
				array_push( $found_generator_id, $post_id );
				if ( is_multisite() ) {
					update_site_option( $option_key, $found_generator_id );
				} else {
					update_option( $option_key, $found_generator_id );
				}
			}
		} else {
			// If option not set in current page add option.
			if ( $current_page_id ) {
				if ( is_multisite() ) {
					add_site_option( $option_key, array( $post_id ) );
				} else {
					add_option( $option_key, array( $post_id ) );
				}
			}
		}
	}
	/**
	 * Load dynamic style of the existing shortcode id.
	 *
	 * @param  mixed $found_generator_id to push id option for getting how many shortcode in the page.
	 * @param  mixed $shortcode_data to push all options.
	 * @param  mixed $setting_options gets option of the plugin.
	 * @return array dynamic style and typography use in the specific shortcode.
	 */
	public static function load_dynamic_style( $found_generator_id, $shortcode_data = '', $setting_options = '' ) {
		$dequeue_google_fonts = isset( $setting_options['tpro_dequeue_google_fonts'] ) ? $setting_options['tpro_dequeue_google_fonts'] : true;
		$outline              = '';
		$tpro_typography      = array();
		// If multiple shortcode found in the page.
		if ( is_array( $found_generator_id ) ) {
			foreach ( $found_generator_id as $post_id ) {
				if ( $post_id && is_numeric( $post_id ) && get_post_status( $post_id ) !== 'trash' ) {
					$setting_options = get_option( 'sp_testimonial_pro_options' );
					$shortcode_data  = get_post_meta( $post_id, 'sp_tpro_shortcode_options', true );
					include SP_TPRO_PATH . 'Frontend/views/partials/style.php';
				}
			}
		} else {
			// If single shortcode found in the page.
			$post_id = $found_generator_id;
			include SP_TPRO_PATH . 'Frontend/views/partials/style.php';
		}
		// Custom css merge with dynamic style.
		$custom_css = isset( $setting_options['custom_css'] ) ? trim( html_entity_decode( $setting_options['custom_css'] ) ) : '';
		if ( ! empty( $custom_css ) ) {
			$outline .= $custom_css;
		}
		// Google font enqueue dequeue check.
		$tpro_typography = $dequeue_google_fonts ? $tpro_typography : array();
		$dynamic_style   = array(
			'dynamic_css' => self::minify_output( $outline ),
			'typography'  => $tpro_typography,
		);
		return $dynamic_style;
	}

	/**
	 * Full html show.
	 *
	 * @param array $post_id Shortcode ID.
	 * @param array $setting_options get all layout options.
	 * @param array $shortcode_data get all meta options.
	 * @param array $main_section_title section title.
	 */
	public static function sp_tpro_html_show( $post_id, $setting_options, $shortcode_data, $main_section_title ) {
		// Load css file in header or not.
		$style_load_in_header = apply_filters( 'sp_testimonial_style_load_in_header', false );
		//
		// General Settings.
		//
		$layout                    = isset( $shortcode_data['layout'] ) ? $shortcode_data['layout'] : 'slider';
		$theme_style               = isset( $shortcode_data['theme_style'] ) ? $shortcode_data['theme_style'] : 'theme-one';
		$display_testimonials_from = isset( $shortcode_data['display_testimonials_from'] ) ? $shortcode_data['display_testimonials_from'] : 'latest';
		$category_list             = isset( $shortcode_data['category_list'] ) ? $shortcode_data['category_list'] : '';
		$preloader                 = isset( $shortcode_data['preloader'] ) ? $shortcode_data['preloader'] : '';
		$testimonial_live_filter   = isset( $shortcode_data['ajax_live_filter'] ) && 'filter' !== $layout ? $shortcode_data['ajax_live_filter'] : false;
		$ajax_testimonial_search   = isset( $shortcode_data['ajax_search'] ) ? $shortcode_data['ajax_search'] : false;
		$search_text               = isset( $shortcode_data['testimonial_search_text'] ) ? $shortcode_data['testimonial_search_text'] : '';
		//
		// Stylization.
		//
		$show_testimonial_text = isset( $shortcode_data['testimonial_text'] ) ? $shortcode_data['testimonial_text'] : '';
		$video_icon            = isset( $shortcode_data['video_icon'] ) ? $shortcode_data['video_icon'] : true;
		$img_lightbox          = isset( $shortcode_data['img_lightbox'] ) && $shortcode_data['img_lightbox'] ? $shortcode_data['img_lightbox'] : 'false';
		$section_title         = isset( $shortcode_data['section_title'] ) ? $shortcode_data['section_title'] : false;
		$average_rating_top    = isset( $shortcode_data['average_rating_top'] ) ? $shortcode_data['average_rating_top'] : false;

		$space_between            = isset( $shortcode_data['testimonial_margin']['all'] ) && $shortcode_data['testimonial_margin']['all'] ? $shortcode_data['testimonial_margin']['all'] : '0';
		$testimonial_margin_value = isset( $shortcode_data['testimonial_margin']['top'] ) && $shortcode_data['testimonial_margin']['top'] ? $shortcode_data['testimonial_margin']['top'] : $space_between;

		// Pagination.
		$pagination_data = isset( $shortcode_data['pagination'] ) ? $shortcode_data['pagination'] : 'true';
		switch ( $pagination_data ) {
			case 'true':
				$pagination_dots   = 'true';
				$pagination_mobile = 'true';
				break;
			case 'hide_on_mobile':
				$pagination_dots   = 'true';
				$pagination_mobile = 'false';
				break;
			case 'false':
				$pagination_dots   = 'false';
				$pagination_mobile = 'false';
				break;
		}

		$columns               = isset( $shortcode_data['columns'] ) ? $shortcode_data['columns'] : '';
		$columns_large_desktop = isset( $columns['large_desktop'] ) ? $columns['large_desktop'] : '1';
		$columns_desktop       = isset( $columns['desktop'] ) ? $columns['desktop'] : '1';
		$columns_laptop        = isset( $columns['laptop'] ) ? $columns['laptop'] : '1';
		$columns_tablet        = isset( $columns['tablet'] ) ? $columns['tablet'] : '1';
		$columns_mobile        = isset( $columns['mobile'] ) ? $columns['mobile'] : '1';
		$slider_direction      = isset( $shortcode_data['slider_direction'] ) ? $shortcode_data['slider_direction'] : 'ltr';
		$rtl_mode              = ( 'rtl' === $slider_direction ) ? 'true' : 'false';
		$the_rtl               = ( 'slider' === $layout ) ? 'dir="' . esc_attr( $slider_direction ) . '"' : '';

		$testimonial_content_length_type = isset( $shortcode_data['testimonial_content_length_type'] ) ? $shortcode_data['testimonial_content_length_type'] : 'characters';
		$testimonial_word_limit          = isset( $shortcode_data['testimonial_word_limit'] ) ? $shortcode_data['testimonial_word_limit'] : '';
		$testimonial_strip_tags          = isset( $shortcode_data['testimonial_strip_tags'] ) ? $shortcode_data['testimonial_strip_tags'] : 'strip_all';

		// Schema settings.
		$tpro_schema_markup    = isset( $shortcode_data['tpro_schema_markup'] ) ? $shortcode_data['tpro_schema_markup'] : '';
		$tpro_global_item_name = isset( $shortcode_data['tpro_global_item_name'] ) && ! empty( $shortcode_data['tpro_global_item_name'] ) ? $shortcode_data['tpro_global_item_name'] : get_the_title();

		// Navigation.
		$navigation_data = isset( $shortcode_data['navigation'] ) ? $shortcode_data['navigation'] : 'true';
		switch ( $navigation_data ) {
			case 'true':
				$navigation_arrows = 'true';
				$navigation_mobile = 'true';
				break;
			case 'hide_on_mobile':
				$navigation_arrows = 'true';
				$navigation_mobile = 'false';
				break;
			case 'false':
				$navigation_arrows = 'false';
				$navigation_mobile = 'false';
				break;
		}
		$navigation_position    = isset( $shortcode_data['navigation_position'] ) ? $shortcode_data['navigation_position'] : 'vertical_center';
		$show_testimonial_title = isset( $shortcode_data['testimonial_title'] ) ? $shortcode_data['testimonial_title'] : '';
		$thumbnail_slider       = isset( $shortcode_data['thumbnail_slider'] ) && $shortcode_data['thumbnail_slider'] ? 'true' : 'false';
		$slider_mode            = isset( $shortcode_data['slider_mode'] ) ? $shortcode_data['slider_mode'] : 'standard';

		$grid_pagination     = isset( $shortcode_data['grid_pagination'] ) ? $shortcode_data['grid_pagination'] : '';
		$pagination_type     = isset( $shortcode_data['tp_pagination_type'] ) ? $shortcode_data['tp_pagination_type'] : 'no_ajax';
		$pagination_type     = $testimonial_live_filter && 'no_ajax' === $pagination_type ? 'ajax_pagination' : $pagination_type;
		$load_more_label     = isset( $shortcode_data['load_more_label'] ) ? $shortcode_data['load_more_label'] : 'Load More';
		$show_social_profile = isset( $shortcode_data['social_profile'] ) ? $shortcode_data['social_profile'] : '';
		$navigation_icons    = isset( $shortcode_data['navigation_icons'] ) ? $shortcode_data['navigation_icons'] : 'angle';

		/**
		 * Image Settings.
		 */
		$show_client_image   = isset( $shortcode_data['client_image'] ) ? $shortcode_data['client_image'] : false;
		$image_sizes         = isset( $shortcode_data['image_sizes'] ) ? $shortcode_data['image_sizes'] : 'custom';
		$image_custom_size   = isset( $shortcode_data['image_custom_size'] ) ? $shortcode_data['image_custom_size'] : '';
		$client_image_width  = isset( $image_custom_size['width'] ) ? $image_custom_size['width'] : '120';
		$client_image_height = isset( $image_custom_size['height'] ) ? $image_custom_size['height'] : '120';
		$image_crop          = isset( $image_custom_size['crop'] ) ? $image_custom_size['crop'] : 'soft-crop';
		$client_image_crop   = ( 'hard-crop' === $image_crop ) ? true : false;
		$image_grayscale     = isset( $shortcode_data['image_grayscale'] ) ? $shortcode_data['image_grayscale'] : 'none';
		$show_2x_image       = isset( $shortcode_data['load_2x_image'] ) ? $shortcode_data['load_2x_image'] : '';
		$image_zoom          = isset( $shortcode_data['image_zoom_effect'] ) ? $shortcode_data['image_zoom_effect'] : '';

		$star_icon       = isset( $shortcode_data['tpro_star_icon'] ) ? $shortcode_data['tpro_star_icon'] : 'fa fa-star';
		$adaptive_height = isset( $shortcode_data['adaptive_height'] ) && $shortcode_data['adaptive_height'] ? 'true' : 'false';

		$testimonial_characters_limit      = isset( $shortcode_data['testimonial_characters_limit'] ) ? $shortcode_data['testimonial_characters_limit'] : '100';
		$testimonial_content_type          = isset( $shortcode_data['testimonial_content_type'] ) ? $shortcode_data['testimonial_content_type'] : '';
		$testimonial_read_more_link_action = isset( $shortcode_data['testimonial_read_more_link_action'] ) ? $shortcode_data['testimonial_read_more_link_action'] : '';
		$testimonial_read_more             = isset( $shortcode_data['testimonial_read_more'] ) ? $shortcode_data['testimonial_read_more'] : '';

		$testimonial_same_height = '';
		if ( ( 'slider' === $layout || 'grid' === $layout ) && 'expand' !== $testimonial_read_more_link_action ) {
			$testimonial_same_height = isset( $shortcode_data['testimonial_same_height'] ) && $shortcode_data['testimonial_same_height'] ? 'data-same-height = true' : ' ';
		}

		if ( $show_testimonial_text && 'content_with_limit' === $testimonial_content_type && $testimonial_read_more && 'expand' === $testimonial_read_more_link_action ) {
			wp_enqueue_script( 'tpro-curtail-min-js' );
			$testimonial_read_more_text     = isset( $shortcode_data['testimonial_read_more_text'] ) ? $shortcode_data['testimonial_read_more_text'] : 'Read More';
			$testimonial_read_less_text     = isset( $shortcode_data['testimonial_read_less_text'] ) ? $shortcode_data['testimonial_read_less_text'] : 'Read Less';
			$testimonial_read_more_ellipsis = isset( $shortcode_data['testimonial_read_more_ellipsis'] ) ? $shortcode_data['testimonial_read_more_ellipsis'] : '...';
			$read_more_config               = 'data-read_more=\'{
				"testimonial_characters_limit": ' . $testimonial_characters_limit . ',
				"testimonial_read_more_text": "' . $testimonial_read_more_text . '",
				"testimonial_read_less_text": "' . $testimonial_read_less_text . '",
				"testimonial_read_more_ellipsis": "' . $testimonial_read_more_ellipsis . '"
			}\'';
		} elseif ( $testimonial_read_more ) {
			wp_enqueue_script( 'tpro-remodal-js' );
		}
		$testimonial_query_and_ids   = self::testimonial_query( $shortcode_data, $post_id );
		$testimonial_read_more_class = '';
		$testimonial_items           = self::testimonial_items( $testimonial_query_and_ids, $shortcode_data, $layout, $post_id );
		$post_query                  = $testimonial_query_and_ids['query'];
		$data_attr                   = '';
		$data_attr                  .= 'data-testimonial=\'{ "videoIcon": ' . $video_icon . ', "lightboxIcon": ' . $img_lightbox . ', "thumbnailSlider": ' . $thumbnail_slider . '}\'';
		$data_attr                  .= 'data-preloader=\'' . $preloader . '\'';

		if ( ( $video_icon || $img_lightbox ) ) {
			wp_enqueue_script( 'tpro-magnific-popup-js' );
		}
		if ( $testimonial_read_more ) {
			$testimonial_read_more_class = 'true';
		}
		if ( 'slider' === $layout ) {
			$slider_swipe           = isset( $shortcode_data['slider_swipe'] ) && $shortcode_data['slider_swipe'] ? 'true' : 'false';
			$slider_draggable       = isset( $shortcode_data['slider_draggable'] ) && $shortcode_data['slider_draggable'] && 'true' === $slider_swipe ? 'true' : 'false';
			$slider_draggable_thumb = isset( $shortcode_data['slider_draggable'] ) && $shortcode_data['slider_draggable'] && 'true' === $slider_swipe ? 'true' : 'false';
			$tpro_swipe_to_slide    = isset( $shortcode_data['swipe_to_slide'] ) ? $shortcode_data['swipe_to_slide'] : 'false';
			$swipe_to_slide         = $tpro_swipe_to_slide && 'true' === $slider_swipe ? 'true' : 'false';
			$slider_swipe_thumb     = isset( $shortcode_data['slider_swipe'] ) && $shortcode_data['slider_swipe'] ? 'true' : 'false';
			// Auto Play.
			$slider_auto_play_data = isset( $shortcode_data['slider_auto_play'] ) ? $shortcode_data['slider_auto_play'] : 'true';
			switch ( $slider_auto_play_data ) {
				case 'true':
					$slider_auto_play        = 'true';
					$slider_auto_play_mobile = 'true';
					break;
				case 'off_on_mobile':
					$slider_auto_play        = 'true';
					$slider_auto_play_mobile = 'false';
					break;
				case 'false':
					$slider_auto_play        = 'false';
					$slider_auto_play_mobile = 'false';
					break;
				default:
					$slider_auto_play        = 'true';
					$slider_auto_play_mobile = 'false';
					break;
			}
			$slider_auto_play_speed        = isset( $shortcode_data['slider_auto_play_speed'] ) ? $shortcode_data['slider_auto_play_speed'] : '3000';
			$slider_scroll_speed           = isset( $shortcode_data['slider_scroll_speed'] ) ? $shortcode_data['slider_scroll_speed'] : '600';
			$slide_to_scroll               = isset( $shortcode_data['slide_to_scroll'] ) ? $shortcode_data['slide_to_scroll'] : '1';
			$slide_to_scroll_large_desktop = isset( $slide_to_scroll['large_desktop'] ) ? $slide_to_scroll['large_desktop'] : '1';
			$slide_to_scroll_desktop       = isset( $slide_to_scroll['desktop'] ) ? $slide_to_scroll['desktop'] : '1';
			$slide_to_scroll_laptop        = isset( $slide_to_scroll['laptop'] ) ? $slide_to_scroll['laptop'] : '1';
			$slide_to_scroll_tablet        = isset( $slide_to_scroll['tablet'] ) ? $slide_to_scroll['tablet'] : '1';
			$slide_to_scroll_mobile        = isset( $slide_to_scroll['mobile'] ) ? $slide_to_scroll['mobile'] : '1';
			$slider_row                    = isset( $shortcode_data['slider_row'] ) ? $shortcode_data['slider_row'] : '';
			$row_large_desktop             = isset( $slider_row['large_desktop'] ) ? $slider_row['large_desktop'] : '1';
			$row_desktop                   = isset( $slider_row['desktop'] ) ? $slider_row['desktop'] : '1';
			$row_laptop                    = isset( $slider_row['laptop'] ) ? $slider_row['laptop'] : '1';
			$row_tablet                    = isset( $slider_row['tablet'] ) ? $slider_row['tablet'] : '1';
			$row_mobile                    = isset( $slider_row['mobile'] ) ? $slider_row['mobile'] : '1';
			$slider_pause_on_hover         = isset( $shortcode_data['slider_pause_on_hover'] ) && $shortcode_data['slider_pause_on_hover'] ? 'true' : 'false';
			$slider_pause_on_hover_thumb   = isset( $shortcode_data['slider_pause_on_hover'] ) && $shortcode_data['slider_pause_on_hover'] ? 'true' : 'false';
			$slider_infinite               = isset( $shortcode_data['slider_infinite'] ) && $shortcode_data['slider_infinite'] ? 'true' : 'false';
			$slider_animation              = isset( $shortcode_data['slider_animation'] ) ? $shortcode_data['slider_animation'] : '';
			$fade_carousel_class           = '';
			$slider_fade_effect            = 'false';
			switch ( $slider_animation ) {
				case 'slide':
					$slider_fade_effect = 'false';
					break;
				case 'fade':
					$slider_fade_effect  = 'true';
					$fade_carousel_class = 'sp-testimonial-fade-carousel';
					break;
			}
			$slider_fade_effect_thumb = 'fade' === $slider_animation ? 'true' : 'false';
			wp_enqueue_script( 'tpro-swiper-js' );
			if ( 'true' === $thumbnail_slider ) {
				wp_enqueue_script( 'tpro-thumbnail-js' );
				$slides_to_show = 5;
				if ( $testimonial_items['total_testimonial'] < 6 && $testimonial_items['total_testimonial'] > 1 ) {
					$slides_to_show = (int) $testimonial_items['total_testimonial'] - 1;
				}
				include self::sptp_locate_template( 'thumbnail-slider.php' );
			} else {
				wp_enqueue_script( 'tpro-swiper-config' );
				if ( 'ticker' === $slider_mode ) {
					wp_enqueue_script( 'tpro-bx_slider' );
					$data_attr .= 'data-bx_ticker=\'{"pauseOnHover": ' . $slider_pause_on_hover . ', "slidesPerView": {"lg_desktop":' . $columns_large_desktop . ' , "desktop": ' . $columns_desktop . ', "laptop":' . $columns_laptop . ' , "tablet": ' . $columns_tablet . ', "mobile": ' . $columns_mobile . '}, "speed": ' . $slider_scroll_speed . '0, "rtl": ' . $rtl_mode . '}\'';
				} else {
					$data_attr .= 'data-arrowicon="' . $navigation_icons . '" data-swiper_slider=\'{"dots": ' . $pagination_dots . ',"spaceBetween":' . $testimonial_margin_value . ', "adaptiveHeight": ' . $adaptive_height . ', "rows": ' . $row_large_desktop . ', "pauseOnHover": ' . $slider_pause_on_hover . ',  "effect": ' . $slider_fade_effect_thumb . ', "slidesToShow": ' . $columns_large_desktop . ', "speed": ' . $slider_scroll_speed . ', "arrows": ' . $navigation_arrows . ', "autoplay": ' . $slider_auto_play . ', "autoplaySpeed": ' . $slider_auto_play_speed . ', "swipe": ' . $slider_swipe . ', "swipeToSlide": ' . $swipe_to_slide . ', "draggable": ' . $slider_draggable . ', "rtl": ' . $rtl_mode . ', "infinite": ' . $slider_infinite . ', "slidesToScroll": ' . $slide_to_scroll_large_desktop . ', "fade": ' . $slider_fade_effect . ',"slidesPerView": {"lg_desktop":' . $columns_large_desktop . ' , "desktop": ' . $columns_desktop . ', "laptop":' . $columns_laptop . ' , "tablet": ' . $columns_tablet . ', "mobile": ' . $columns_mobile . '},"slideToScroll": {"lg_desktop":' . $slide_to_scroll_large_desktop . '  , "desktop": ' . $slide_to_scroll_desktop . ', "laptop":' . $slide_to_scroll_laptop . ' , "tablet":' . $slide_to_scroll_tablet . ' , "mobile":' . $slide_to_scroll_mobile . ' }, "slidesRow": {"lg_desktop":' . $row_large_desktop . '  , "desktop": ' . $row_desktop . ', "laptop": ' . $row_laptop . ' , "tablet":' . $row_tablet . ', "mobile": ' . $row_mobile . '},"navigation_mobile": ' . $navigation_mobile . ', "pagination_mobile":' . $pagination_mobile . ', "autoplay_mobile": ' . $slider_auto_play_mobile . '}\'';
				}
				include self::sptp_locate_template( 'slider.php' );
			}
		} elseif ( 'grid' === $layout ) {
			include self::sptp_locate_template( 'grid.php' );
		} elseif ( 'masonry' === $layout ) {
			wp_enqueue_script( 'jquery-masonry' );
			include self::sptp_locate_template( 'masonry.php' );
		} elseif ( 'list' === $layout ) {
			include self::sptp_locate_template( 'list.php' );
		} elseif ( 'filter' === $layout ) {
			$all_tab_text    = isset( $shortcode_data['all_tab_text'] ) ? $shortcode_data['all_tab_text'] : 'All';
			$all_tab_show    = isset( $shortcode_data['all_tab'] ) ? $shortcode_data['all_tab'] : true;
			$filter_style    = isset( $shortcode_data['filter_style'] ) ? $shortcode_data['filter_style'] : 'even';
			$grid_pagination = isset( $shortcode_data['filter_pagination'] ) ? $shortcode_data['filter_pagination'] : false;
			$pagination_type = isset( $shortcode_data['filter_pagination_type'] ) ? $shortcode_data['filter_pagination_type'] : 'infinite_scroll';
			$load_more_label = isset( $shortcode_data['filter_load_more_label'] ) ? $shortcode_data['filter_load_more_label'] : 'Load More';
			switch ( $filter_style ) {
				case 'even':
					$filter_mode = 'fitRows';
					break;
				case 'masonry':
					$filter_mode = 'masonry';
					break;
			}
			$tpro_filter_config = $filter_mode;
			wp_enqueue_script( 'tpro-isotope-js' );
			wp_enqueue_script( 'imagesloaded' );
			include self::sptp_locate_template( 'filter.php' );
		}
		if ( $tpro_schema_markup ) {
			ob_start();
			self::testimonials_schema( $post_query, $tpro_global_item_name, $testimonial_items['aggregate_rating'], $testimonial_items['schema_html'], $testimonial_items['total_testimonial'] );
			echo ob_get_clean();
		}
		wp_enqueue_script( 'tpro-scripts' );
	}

	/**
	 * Frontend form html.
	 *
	 * @param  int   $form_id form id.
	 * @param  array $setting_options options.
	 * @param  array $form_elements element.
	 * @param  array $form_data data.
	 * @return void
	 */
	public static function frontend_form_html( $form_id, $setting_options, $form_elements, $form_data ) {

		$form_element          = isset( $form_elements['form_elements'] ) ? $form_elements['form_elements'] : array();
		$captcha_site_key_v3   = isset( $setting_options['captcha_site_key_v3'] ) ? $setting_options['captcha_site_key_v3'] : '';
		$captcha_version       = isset( $setting_options['captcha_version'] ) ? $setting_options['captcha_version'] : 'v2';
		$captcha_secret_key_v3 = isset( $setting_options['captcha_secret_key_v3'] ) ? $setting_options['captcha_secret_key_v3'] : '';

		// Form Scripts and Styles.
		if ( in_array( 'recaptcha', $form_element, true ) && ( '' !== $setting_options['captcha_site_key'] || '' !== $captcha_site_key_v3 ) && ( '' !== $setting_options['captcha_secret_key'] || '' !== $captcha_secret_key_v3 ) ) {
			if ( 'v2' === $captcha_version ) {
				wp_enqueue_script( 'tpro-recaptcha-js' );
			} else {
				wp_enqueue_script( 'tpro-recaptcha-v3-js', '//www.google.com/recaptcha/api.js?render=' . $captcha_site_key_v3, array(), SP_TPRO_VERSION, true );
				wp_add_inline_script(
					'tpro-recaptcha-v3-js',
					'grecaptcha.ready(function() {
						grecaptcha.execute("' . $captcha_site_key_v3 . '", {action: "submit"}).then(function(token) {
							document.getElementById("token").value = token;
						});
					});'
				);
			}
		}
		wp_enqueue_script( 'tpro-chosen-jquery' );
		wp_enqueue_script( 'tpro-chosen-config' );
		// wp_enqueue_style( 'tpro-chosen' );
		wp_enqueue_style( 'tpro-form' );
		wp_enqueue_style( 'tpro-font-awesome' );

		$form_fields                = $form_data['form_fields'];
		$full_name                  = $form_fields['full_name'];
		$full_name_required         = $full_name['required'] ? 'required' : '';
		$email_address              = $form_fields['email_address'];
		$email_address_required     = $email_address['required'] ? 'required' : '';
		$identity_position          = $form_fields['identity_position'];
		$identity_position_required = $identity_position['required'] ? 'required' : '';
		$company_name               = $form_fields['company_name'];
		$company_name_required      = $company_name['required'] ? 'required' : '';
		$testimonial_title          = $form_fields['testimonial_title'];
		$testimonial_title_required = $testimonial_title['required'] ? 'required' : '';
		$testimonial                = $form_fields['testimonial'];
		$testimonial_required       = $testimonial['required'] ? 'required' : '';
		$featured_image             = $form_fields['featured_image'];
		$featured_image_required    = $featured_image['required'] ? 'required' : '';
		$location                   = $form_fields['location'];
		$location_required          = $location['required'] ? 'required' : '';
		$phone_mobile               = $form_fields['phone_mobile'];
		$phone_mobile_required      = $phone_mobile['required'] ? 'required' : '';
		$website                    = $form_fields['website'];
		$website_required           = $website['required'] ? 'required' : '';
		$video_url                  = $form_fields['video_url'];
		$video_url_required         = $video_url['required'] ? 'required' : '';
		$groups                     = $form_fields['groups'];
		$groups_list                = isset( $groups['groups_list'] ) ? $groups['groups_list'] : '';
		$groups_multiple_selection  = $groups['multiple_selection'] ? 'multiple="multiple" ' : '';
		$rating                     = $form_fields['rating'];
		$agree_checkbox             = isset( $form_fields['agree_checkbox'] ) ? $form_fields['agree_checkbox'] : '';
		$recaptcha                  = $form_fields['recaptcha'];
		$submit_btn                 = $form_fields['submit_btn'];
		$social_profile             = $form_fields['social_profile'];
		$social_profile_list        = isset( $social_profile['social_profile_list'] ) && ! empty( $social_profile['social_profile_list'] ) ? $social_profile['social_profile_list'] : '';
		$tpro_function              = new SP_Testimonial_Pro_Functions();
		// Testimonial submit form.
		include SP_TPRO_PATH . 'Frontend/views/partials/submit-form.php';
		// END THE IF STATEMENT THAT STARTED THE WHOLE FORM.

		$label_color         = isset( $form_data['label_color'] ) ? $form_data['label_color'] : '';
		$submit_button_color = isset( $form_data['submit_button_color'] ) ? $form_data['submit_button_color'] : '';
		$record_button_color = isset( $form_data['record_button_color'] ) ? $form_data['record_button_color'] : array(
			'color'            => '#ffffff',
			'hover-color'      => '#ffffff',
			'background'       => '#005bdf',
			'hover-background' => '#005bdf',
		);
		echo '<style>#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field label{
				font-size: 16px;
				color: ' . esc_attr( $label_color ) . ';
			}
			#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form #tpro_modal_btn {
				color: ' . esc_attr( $record_button_color['color'] ) . ';
				background: ' . esc_attr( $record_button_color['background'] ) . ';
			}
			#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form  #tpro_modal_btn:hover{
				color: ' . esc_attr( $record_button_color['hover-color'] ) . ';
				background: ' . esc_attr( $record_button_color['hover-background'] ) . ';
			}
			#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-submit-button input[type=\'submit\']{
				color: ' . esc_attr( $submit_button_color['color'] ) . ';
				background: ' . esc_attr( $submit_button_color['background'] ) . ';
				padding: 15px 25px;
				text-transform: uppercase;
				font-size: 14px;
				transition: all 0.25s;
				text-decoration: none;
				line-height: 1;
				border-radius: 4px;
				margin-top: 6px;
			}
			#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-submit-button input[type=\'submit\']:hover{
				color: ' . esc_attr( $submit_button_color['hover-color'] ) . ';
				background: ' . esc_attr( $submit_button_color['hover-background'] ) . ';

			}
			#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-client-rating:not(:checked)>label{
				color: #d4d4d4;
			}
			#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-client-rating>input:checked~label {
				color: #f3bb00;
			}
			#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-client-rating:not(:checked)>label:hover,
			#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-client-rating:not(:checked)>label:hover~label {
				color: #de7202;
			}</style>';
		include self::sptp_locate_template( 'form.php' );
	}
}
