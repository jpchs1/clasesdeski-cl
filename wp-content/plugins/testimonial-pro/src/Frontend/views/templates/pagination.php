<?php
/**
 * Pagination.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-pro/templates/pagination.php
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

if ( $post_query->max_num_pages > 1 ) {
	if ( ( 'grid' === $layout || 'masonry' === $layout || 'list' === $layout || 'filter' === $layout ) && $grid_pagination ) {
		$tp_error_text = apply_filters( 'sp_testimonial_pro_pagination_error_text', __( 'No more testimonials to load', 'testimonial-pro' ) );
		$tp_end_text   = apply_filters( 'sp_testimonial_pro_pagination_end_content_text', __( 'End of content', 'testimonial-pro' ) );
		if ( 'ajax_load_more' === $pagination_type ) :
			?>
		<div class="sp-testimonial-ajax-pagination hidden">
			<?php
			$paged_var  = 'paged' . $post_id;
			$args       = array(
				'format'    => '?paged' . $post_id . '=%#%',
				'total'     => $post_query->max_num_pages,
				'current'   => isset( $_GET[ "$paged_var" ] ) ? sanitize_text_field( wp_unslash( $_GET[ "$paged_var" ] ) ) : 1,
				'prev_next' => false,
				'next_text' => '<i class="fa fa-angle-right"></i>',
				'prev_text' => '<i class="fa fa-angle-left"></i>',
				'type'      => 'array',
				'show_all'  => true,
			);
			$page_links = paginate_links( $args );
			$prev_link  = '<a class="next page-numbers" href="#"><i class="fa fa-angle-right"></i></a>';
			$next_link  = '<a class="prev page-numbers current" href="#"><i class="fa fa-angle-left"></i></a>';
			array_unshift( $page_links, $next_link );
			$page_links[] = $prev_link;
			$html         = '';
			$p_num        = 0;
			foreach ( $page_links as $page_link ) {
				$class = 'page-numbers ';
				if ( strpos( $page_link, 'current' ) !== false ) {
					$class .= 'current ';
				}
				if ( strpos( $page_link, 'next' ) !== false ) {
					$data_page = 'data-page="next"';
					$class    .= 'next ';
				} elseif ( strpos( $page_link, 'prev' ) !== false ) {
					$data_page = 'data-page="prev"';
					$class    .= ' prev';
				} else {
					$data_page = 'data-page="' . $p_num . '"';
				}
				$page_link = preg_replace( '/<span[^>]*>/', '<a href="#" class="' . $class . '" ' . $data_page . '>', $page_link );
				$page_link = preg_replace( '/<a [^>]*>/', '<a href="#" class="' . $class . '" ' . $data_page . '>', $page_link );
				$page_link = str_replace( '</span>', '</a>', $page_link );
				$html     .= $page_link;
				$p_num++;
			}
			echo wp_kses_post( $html );
			?>
		</div>
		<div class='testimonial_page_load_status_alignment'>
			<div class="page-load-status">
				<p class="loader-ellips infinite-scroll-request">
					<span class="loader-ellips__dot"></span>
					<span class="loader-ellips__dot"></span>
					<span class="loader-ellips__dot"></span>
					<span class="loader-ellips__dot"></span>
				</p>
			<p class="infinite-scroll-last"><?php echo esc_html( $tp_end_text ); ?></p>
			<p class="infinite-scroll-error"><?php esc_html( $tp_error_text ); ?></p>
			</div>
		</div>
		<div class="tpro-items-load-more" data-buttontext="<?php echo esc_html( $load_more_label ); ?>">
			<span><?php echo esc_html( $load_more_label ); ?></span>
		</div>
			<?php
		endif;
		if ( 'infinite_scroll' === $pagination_type ) :
			?>
		<div class="tpro-items-load-more" data-processing='0' data-buttontext="<?php echo esc_html( $load_more_label ); ?>">
			<span><?php echo esc_html( $load_more_label ); ?></span>
		</div>
		<div class="sp-testimonial-ajax-pagination hidden">
			<?php
			$paged_var  = 'paged' . $post_id;
			$args       = array(
				'format'    => '?paged' . $post_id . '=%#%',
				'total'     => $post_query->max_num_pages,
				'current'   => isset( $_GET[ "$paged_var" ] ) ? sanitize_text_field( wp_unslash( $_GET[ "$paged_var" ] ) ) : 1,
				'prev_next' => false,
				'next_text' => '<i class="fa fa-angle-right"></i>',
				'prev_text' => '<i class="fa fa-angle-left"></i>',
				'type'      => 'array',
				'show_all'  => true,
			);
			$page_links = paginate_links( $args );
			$prev_link  = '<a class="next page-numbers" href="#"><i class="fa fa-angle-right"></i></a>';
			$next_link  = '<a class="prev page-numbers current" href="#"><i class="fa fa-angle-left"></i></a>';
			array_unshift( $page_links, $next_link );
			$page_links[] = $prev_link;
			$html         = '';
			$p_num        = 0;
			foreach ( $page_links as $page_link ) {
				$class = 'page-numbers ';
				if ( strpos( $page_link, 'current' ) !== false ) {
					$class .= 'current ';
				}
				if ( strpos( $page_link, 'next' ) !== false ) {
					$data_page = 'data-page="next"';
					$class    .= 'next ';
				} elseif ( strpos( $page_link, 'prev' ) !== false ) {
					$data_page = 'data-page="prev"';
					$class    .= ' prev';
				} else {
					$data_page = 'data-page="' . $p_num . '"';
				}
				$page_link = preg_replace( '/<span[^>]*>/', '<a href="#" class="' . $class . '" ' . $data_page . '>', $page_link );
				$page_link = preg_replace( '/<a [^>]*>/', '<a href="#" class="' . $class . '" ' . $data_page . '>', $page_link );
				$page_link = str_replace( '</span>', '</a>', $page_link );
				$html     .= $page_link;
				$p_num++;
			}
			echo $html;
			?>
		</div>
		<div class='testimonial_page_load_status_alignment'>
		<div class="page-load-status">
			<p class="loader-ellips infinite-scroll-request">
				<span class="loader-ellips__dot"></span>
				<span class="loader-ellips__dot"></span>
				<span class="loader-ellips__dot"></span>
				<span class="loader-ellips__dot"></span>
			</p>
			<p class="infinite-scroll-last"><?php echo esc_html( $tp_end_text ); ?></p>
			<p class="infinite-scroll-error"><?php esc_html( $tp_error_text ); ?></p>
		</div>
		</div>
			<?php
		endif;

		if ( 'ajax_pagination' === $pagination_type ) :
			?>
		<div class="sp-testimonial-ajax-pagination">
			<?php
			$paged_var  = 'paged' . $post_id;
			$args       = array(
				'format'    => '?paged' . $post_id . '=%#%',
				'total'     => $post_query->max_num_pages,
				'current'   => isset( $_GET[ "$paged_var" ] ) ? sanitize_text_field( wp_unslash( $_GET[ "$paged_var" ] ) ) : 1,
				'prev_next' => false,
				'next_text' => '<i class="fa fa-angle-right"></i>',
				'prev_text' => '<i class="fa fa-angle-left"></i>',
				'type'      => 'array',
				'show_all'  => true,
			);
			$page_links = paginate_links( $args );
			$prev_link  = '<a class="next page-numbers" href="#"><i class="fa fa-angle-right"></i></a>';
			$next_link  = '<a class="prev page-numbers current" href="#"><i class="fa fa-angle-left"></i></a>';
			array_unshift( $page_links, $next_link );
			$page_links[] = $prev_link;
			$html         = '';
			$p_num        = 0;
			foreach ( $page_links as $page_link ) {
				$class = 'page-numbers ';
				if ( strpos( $page_link, 'current' ) !== false ) {
					$class .= 'current ';
				}
				if ( strpos( $page_link, 'next' ) !== false ) {
					$data_page = 'data-page="next"';
					$class    .= 'next ';
				} elseif ( strpos( $page_link, 'prev' ) !== false ) {
					$data_page = 'data-page="prev"';
					$class    .= ' prev';
				} else {
					$data_page = 'data-page="' . $p_num . '"';
				}
				$page_link = preg_replace( '/<span[^>]*>/', '<a href="#" class="' . $class . '" ' . $data_page . '>', $page_link );
				$page_link = preg_replace( '/<a [^>]*>/', '<a href="#" class="' . $class . '" ' . $data_page . '>', $page_link );
				$page_link = str_replace( '</span>', '</a>', $page_link );
				$html     .= $page_link;
				$p_num++;
			}
			echo $html;
			?>
		</div>
			<?php
		endif;
		if ( 'no_ajax' === $pagination_type ) {
			?>
		<div class="tpro-col-xl-1 sp-tpro-pagination-area">
			<?php
			$paged_format = '?paged' . $post_id . '=%#%';
			$paged_query  = 'paged' . $post_id;
			$big          = 999999999; // need an unlikely integer.
			$items        = paginate_links(
				array(
					'format'    => $paged_format,
					'prev_next' => true,
					'current'   => isset( $_GET[ "$paged_query" ] ) ? wp_unslash( absint( $_GET[ "$paged_query" ] ) ) : 1,
					'total'     => $post_query->max_num_pages,
					'type'      => 'array',
					'prev_text' => '<i class="fa fa-angle-left"></i>',
					'next_text' => '<i class="fa fa-angle-right"></i>',
				)
			);
			$pagination   = "<ul class=\"sp-tpro-pagination\">\n\t<li>";
			$pagination  .= join( "</li>\n\t<li>", $items );
			$pagination  .= "</li>\n</ul>\n";

			echo wp_kses_post( $pagination );
			?>
		</div>
			<?php
		}
		wp_reset_postdata();
	}
}

