<?php
/**
 * Create the Metaboxes for the plugin
 *
 * @package owl-carousel-2
 */

/**
 * Class Owl Carousel 2 Meta
 */
class Owl_Carousel_2_Meta {
	/**
	 * Constructor
	 */
	public function __construct() {

		if ( is_admin() ) {
			add_action( 'load-post.php', array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
		}
	}

	/**
	 * Init the metaboxes
	 */
	public function init_metabox() {

		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
		add_action( 'save_post', array( $this, 'save_metabox' ), 10, 2 );

	}

	/**
	 * Add the metaboxes
	 */
	public function add_metabox() {

		add_meta_box(
			'Carousel_Data',
			__( 'Carousel Data', 'owl-carousel-2' ),
			array( $this, 'render_carousel_data' ),
			'owl-carousel',
			'normal',
			'default'
		);

		add_meta_box(
			'owl-carousel-settings',
			__( 'Carousel Functionality', 'owl-carousel-2' ),
			array( $this, 'owl_carousel_items_functions' ),
			'owl-carousel',
			'side',
			'default'
		);

		add_meta_box(
			'owl-items-displayed',
			__( 'Items Displayed', 'owl-carousel-2' ),
			array( $this, 'owl_carousel_items_content' ),
			'owl-carousel',
			'side',
			'default'
		);

		add_meta_box(
			'owl-shortcode-link',
			__( 'Shortcode', 'owl-carousel-2' ),
			array( $this, 'owl_carousel_shortcode_link' ),
			'owl-carousel',
			'side',
			'high'
		);

	}

	/**
	 * Render the carousel Meta Boxes.
	 *
	 * @param object $post .
	 */
	public function render_carousel_data( $post ) {
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_media();

		// Add nonce for security and authentication.
		wp_nonce_field( 'dd_owl_nonce_action', 'dd_owl_nonce' );

		$args = array(
			'public' => true,
		);

		$output   = 'objects'; // names or objects, note names is the default.
		$operator = 'and';

		$post_types = get_post_types( $args, $output, $operator );

		// Retrieve an existing value from the database.
		$dd_owl_post_type            = get_post_meta( $post->ID, 'dd_owl_post_type', true );
		$dd_owl_number_posts         = get_post_meta( $post->ID, 'dd_owl_number_posts', true );
		$dd_owl_duration             = get_post_meta( $post->ID, 'dd_owl_duration', true );
		$dd_owl_transition           = get_post_meta( $post->ID, 'dd_owl_transition', true );
		$dd_owl_orderby              = get_post_meta( $post->ID, 'dd_owl_orderby', true );
		$dd_owl_navs                 = get_post_meta( $post->ID, 'dd_owl_navs', true );
		$dd_owl_dots                 = get_post_meta( $post->ID, 'dd_owl_dots', true );
		$dd_owl_thumbs               = get_post_meta( $post->ID, 'dd_owl_thumbs', true );
		$dd_owl_css_id               = get_post_meta( $post->ID, 'dd_owl_css_id', true );
		$dd_owl_excerpt_length       = get_post_meta( $post->ID, 'dd_owl_excerpt_length', true );
		$dd_owl_margin               = get_post_meta( $post->ID, 'dd_owl_margin', true );
		$dd_owl_show_cta             = get_post_meta( $post->ID, 'dd_owl_show_cta', true );
		$dd_owl_cta                  = get_post_meta( $post->ID, 'dd_owl_cta', true );
		$dd_owl_show_title           = get_post_meta( $post->ID, 'dd_owl_show_title', true );
		$dd_owl_btn_class            = get_post_meta( $post->ID, 'dd_owl_btn_class', true );
		$dd_owl_image_options        = get_post_meta( $post->ID, 'dd_owl_image_options', true );
		$dd_owl_tax_options          = get_post_meta( $post->ID, 'dd_owl_tax_options', true );
		$dd_owl_nav_position         = get_post_meta( $post->ID, 'dd_owl_nav_position', true );
		$dd_owl_btn_display          = get_post_meta( $post->ID, 'dd_owl_btn_display', true );
		$dd_owl_btn_margin           = get_post_meta( $post->ID, 'dd_owl_btn_margin', true );
		$dd_owl_title_heading        = get_post_meta( $post->ID, 'dd_owl_title_heading', true );
		$dd_owl_excerpt_more         = get_post_meta( $post->ID, 'dd_owl_excerpt_more', true );
		$dd_owl_hide_excerpt_more    = get_post_meta( $post->ID, 'dd_owl_hide_excerpt_more', true );
		$dd_owl_img_width            = get_post_meta( $post->ID, 'dd_owl_img_width', true );
		$dd_owl_img_height           = get_post_meta( $post->ID, 'dd_owl_img_height', true );
		$dd_owl_img_crop             = get_post_meta( $post->ID, 'dd_owl_img_crop', true );
		$dd_owl_img_upscale          = get_post_meta( $post->ID, 'dd_owl_img_upscale', true );
		$dd_owl_media_items          = get_post_meta( $post->ID, 'dd_owl_media_items', true );
		$dd_owl_use_image_caption    = get_post_meta( $post->ID, 'dd_owl_use_image_caption', true );
		$dd_owl_image_size           = get_post_meta( $post->ID, 'dd_owl_image_size', true );
		$dd_owl_prev                 = get_post_meta( $post->ID, 'dd_owl_prev', true );
		$dd_owl_next                 = get_post_meta( $post->ID, 'dd_owl_next', true );
		$dd_owl_show_review_stars    = get_post_meta( $post->ID, 'dd_owl_show_review_stars', true );
		$dd_owl_show_review_product  = get_post_meta( $post->ID, 'dd_owl_show_review_product', true );
		$dd_owl_show_review_date     = get_post_meta( $post->ID, 'dd_owl_show_review_date', true );
		$dd_owl_show_review_reviewer = get_post_meta( $post->ID, 'dd_owl_show_review_reviewer', true );

		// Set default values.
		if ( empty( $dd_owl_post_type ) ) {
			$dd_owl_post_type = '';
		}
		if ( empty( $dd_owl_number_posts ) ) {
			$dd_owl_number_posts = '10';
		}
		if ( empty( $dd_owl_duration ) ) {
			$dd_owl_duration = '2000';
		}
		if ( empty( $dd_owl_transition ) ) {
			$dd_owl_transition = '400';
		}
		if ( empty( $dd_owl_orderby ) ) {
			$dd_owl_orderby = '';
		}
		if ( empty( $dd_owl_css_id ) ) {
			$dd_owl_css_id = 'carousel-' . $post->ID;
		}
		if ( null === ( $dd_owl_excerpt_length ) ) {
			$dd_owl_excerpt_length = '20';
		}
		if ( empty( $dd_owl_excerpt_more ) ) {
			$dd_owl_excerpt_more = '...';
		}
		if ( empty( $dd_owl_margin ) ) {
			$dd_owl_margin = '10';
		}
		if ( empty( $dd_owl_cta ) ) {
			$dd_owl_cta = 'Read More';
		}
		if ( empty( $dd_owl_btn_class ) ) {
			$dd_owl_btn_class = 'btn btn-primary';
		}
		if ( empty( $dd_owl_image_options ) ) {
			$dd_owl_image_options = 'null';
		}
		if ( empty( $dd_owl_tax_options ) ) {
			$dd_owl_tax_options = 'null';
		}
		if ( empty( $dd_owl_nav_position ) ) {
			$dd_owl_nav_position = 'bottom';
		}
		if ( empty( $dd_owl_btn_display ) ) {
			$dd_owl_btn_display = 'inline';
		}
		if ( empty( $dd_owl_title_heading ) ) {
			$dd_owl_title_heading = 'h4';
		}
		if ( empty( $dd_owl_img_width ) ) {
			$dd_owl_img_width = '600';
		}
		if ( empty( $dd_owl_img_height ) ) {
			$dd_owl_img_height = '400';
		}
		if ( empty( $dd_owl_img_crop ) ) {
			$dd_owl_img_crop = '';
		}
		if ( empty( $dd_owl_img_upscale ) ) {
			$dd_owl_img_upscale = '';
		}
		if ( empty( $dd_owl_prev ) ) {
			$dd_owl_prev = '&lt;';
		}
		if ( empty( $dd_owl_next ) ) {
			$dd_owl_next = '&gt;';
		}
		if ( empty( $dd_owl_show_review_stars ) ) {
			$dd_owl_show_review_stars = '';
		}
		if ( empty( $dd_owl_show_review_stars ) ) {
			$dd_owl_show_review_stars = '';
		}
		if ( empty( $dd_owl_show_review_product ) ) {
			$dd_owl_show_review_product = '';
		}
		if ( empty( $dd_owl_show_review_date ) ) {
			$dd_owl_show_review_date = '';
		}
		if ( empty( $dd_owl_show_review_reviewer ) ) {
			$dd_owl_show_review_reviewer = '';
		}

		// Hidden Media means it is hidden when someone chooses "Media" carousel.
		$hidden_media = ( 'attachment' === $dd_owl_post_type ) ? ' class="hidden"' : '';
		$hide_media   = ( 'attachment' !== $dd_owl_post_type ) ? ' hidden' : ' visible';
		$is_review    = ( 'reviews' !== $dd_owl_post_type ) ? 'hidden' : 'visible';
		$hidden       = ( 'postID' !== '$dd_owl_tax_options' ) ? ' class="hidden"' : '';

		/**
		 * Choose Post Type and Options
		 *
		 * @since    1.0.0
		 */
		echo '<div id="postOptions"><span class="ajax-loader"></span>';
		echo '<h4>Post Type and Post Options</h4>';
		echo '<table class="form-table">';
		echo '	<tr>';
		echo '		<th><label for="dd_owl_post_type" class="dd_owl_post_type_label">' . esc_html__( 'Post Type', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<select id="dd_owl_post_type" name="dd_owl_post_type" class="dd_owl_post_type_field" required>';
		if ( empty( $dd_owl_post_type ) ) {
			echo '<option value=""> - - ' . esc_html__( 'Choose a Post Type', 'owl-carousel-2' ) . ' - - </option>';
		}
		foreach ( $post_types as $post_type ) {
			if ( 'page' !== $post_type->name ) {
				echo '			<option value="' . esc_attr( $post_type->name ) . '" ';
				echo ( $post_type->name === $dd_owl_post_type ) ? 'selected' : '';
				echo '> ' . esc_html( $post_type->label ) . '</option>';
			}
		}
		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
			echo '      <option value="reviews" ' . selected( $dd_owl_post_type, 'reviews', false ) . '>Product Reviews</option>';
		}
		echo '			</select>';
		echo '			<p class="description">' . esc_html__( 'Type of Post', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';
		echo '<tr class="is-media' . esc_attr( $hide_media ) . '" id="choose-images">';
		echo '<th>' . esc_html__( 'Choose Images' ) . '</th>';
		echo '<td>';
		echo '<input type="button" class="button-primary" value="' . esc_attr__( 'Choose Images', 'owl-carousel-2' ) . '" id="dd-owl-add-media"/>';
		echo '<ul id="dd_owl_image_wrapper" class="ui-sortable">';
		if ( ! empty( $dd_owl_media_items ) ) {
			foreach ( $dd_owl_media_items as $media_item ) {
				echo '<li class="dd-owl-image-preview thumbnail" id="dd-owl-media-' . esc_attr( $media_item ) . '" data-media-id="' . esc_attr( $media_item ) . '">' . wp_get_attachment_image( $media_item, array( '100', '100' ) );
				echo '<input id="dd-owl-image-input-' . esc_attr( $media_item ) . '" type="hidden" name="dd_owl_media_items_array[]"  value="' . esc_attr( $media_item ) . '">';
				echo sprintf( '<ul class="actions"><li><a href="#" class="delete">%s</a></li></ul>', esc_attr__( 'Delete', 'owl-carousel-2' ) );
				echo '</li>';
			}
		}
		echo '</ul>';
		echo '</td></tr>';
		echo '<tr class="is-media' . esc_attr( $hide_media ) . '" id="show-image-caption">';
		echo '		<th><label for="dd_owl_use_caption" class="dd_owl_use_caption_label">' . esc_html__( 'Captions', 'owl-carousel-2' ) . '?</label></th>';
		echo '		<td>';
		echo '          <input type="checkbox" id="dd_owl_use_image_caption" name="dd_owl_use_image_caption" class="dd_owl_use_image_caption_field" value="checked" ' . checked( $dd_owl_use_image_caption, 'checked', false ) . '>&nbsp;' . esc_html__( 'Show the image caption below the image. Set this individually on the media item within the media library.', 'owl-carousel-2' );
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr id="tax-options">';
		echo '		<th><label for="dd_owl_tax_options" class="dd_owl_tax_options_label">' . esc_html__( 'Taxonomy Display Options', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<label><input type="radio" name="dd_owl_tax_options" class="dd_owl_tax_options_field" value="null" ' . checked( $dd_owl_tax_options, 'null', false ) . '> ' . esc_html__( 'None - Show Latest Posts - set number of posts below', 'owl-carousel-2' ) . '</label><br>';
		echo '			<span class="not-comment"><label><input type="radio" name="dd_owl_tax_options" class="dd_owl_tax_options_field" value="taxonomy" ' . checked( $dd_owl_tax_options, 'taxonomy', false ) . '> ' . esc_html__( 'By Taxonomy/Category - choose taxonomy below.', 'owl-carousel-2' ) . '</label><br></span>';
		echo '			<label><input type="radio" name="dd_owl_tax_options" class="dd_owl_tax_options_field" value="postID" ' . checked( $dd_owl_tax_options, 'postID', false ) . '> ' . esc_html__( 'By Post ID - Show Post / Product / Custom Post Type by Post ID.', 'owl-carousel-2' ) . '</label><br>';
		echo '			<span class="not-comment"><label><input type="radio" name="dd_owl_tax_options" class="dd_owl_tax_options_field" value="show_tax_only" ' . checked( $dd_owl_tax_options, 'show_tax_only', false ) . '> ' . esc_html__( 'Only Show Taxonomies / Categories. Do not show individual posts.', 'owl-carousel-2' ) . '</label><br></span>';
		echo '			<label class="product-rows"><input type="radio" name="dd_owl_tax_options" class="dd_owl_tax_options_field" value="featured_product" ' . checked( $dd_owl_tax_options, 'featured_product', false ) . '> ' . esc_html__( 'Show Featured &#40;Starred&#41; Products', 'owl-carousel-2' ) . '</label><br>';
		echo '		</td>';
		echo '	</tr>';
		echo '	<tr id="choose-postids"' . esc_attr( $hidden ) . '>';
		echo '		<th><label for="dd_owl_post_ids" class="dd_owl_post_ids_label">' . esc_html__( 'Post/Product ID&rsquo;s', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '        <select id="dd_owl_post_ids" class="dd-owl-multi-select" name="dd_owl_post_ids[]" multiple="multiple">';
		echo '        </select>';
		echo '			<p class="description">' . esc_html__( 'Select the items to be displayed, you may select multiple items.', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';
		echo '	<tr id="category-row" class="hidden">';
		echo '		<th><label for="dd_owl_post_taxonomy_type" class="dd_owl_post_taxonomy_type_label">' . esc_html__( 'Taxonomy Type', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '      <div id="taxonomy"></div>';
		echo '			<p class="description">' . esc_html__( 'Taxonomy &#40;Category, Tag, etc&#41; of Post', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr id="term-row" class="hidden not-media not-comment">';
		echo '		<th><label for="dd_owl_post_taxonomy_term" class="dd_owl_post_taxonomy_term_label">' . esc_html__( 'Taxonomy Term', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '      <div id="taxterm"></div>';
		echo '			<p class="description">' . esc_html__( 'Category, Tag, or other term of Post - You may choose multiple terms.', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr class="not-media" id="number_of_posts">';
		echo '		<th><label for="dd_owl_number_posts" class="dd_owl_number_posts_label">' . esc_html__( 'Number of Posts', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="number" id="dd_owl_number_posts" name="dd_owl_number_posts" class="dd_owl_number_posts_field" placeholder="10" value="' . esc_attr( $dd_owl_number_posts ) . '">';
		echo '			<p class="description">' . esc_html__( 'Enter the number of posts to show.  -1 &#40;negative 1&#41; shows all posts.', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';
		echo '	<tr class="not-media">';
		echo '		<th><label for="dd_owl_excerpt_length" class="dd_owl_excerpt_length_label">' . esc_html__( 'Post Excerpt Length', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="text" id="dd_owl_excerpt_length" name="dd_owl_excerpt_length" class="dd_owl_excerpt_length_field" value="' . esc_attr( $dd_owl_excerpt_length ) . '">';
		echo '			<p class="description">' . esc_html__( 'Number of words in the excerpt. If you put 0 &#40;zero&#41; it will not display any excerpt', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';
		echo '	<tr class="not-media">';
		echo '		<th><label for="dd_owl_excerpt_more" class="dd_owl_excerpt_more_label">' . esc_html__( 'Post Excerpt more', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="text" id="dd_owl_excerpt_more" name="dd_owl_excerpt_more" class="dd_owl_excerpt_more_field" value="' . esc_attr( $dd_owl_excerpt_more ) . '">';
		echo '          <input type="checkbox" id="dd_owl_hide_excerpt_more" name="dd_owl_hide_excerpt_more" class="dd_owl_hide_excerpt_more_field" value="checked" ' . checked( $dd_owl_hide_excerpt_more, 'checked', false ) . '>' . esc_html__( 'Check to hide this field ', 'owl-carousel-2' );
		echo '			<p class="description">' . esc_html__( 'What to append to the excerpt if the excerpt needs to be trimmed. Default &#39;&hellip;&#39;', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';
		echo '	<tr class="not-media">';
		echo '		<th><label for="dd_owl_orderby" class="dd_owl_orderby_label">' . esc_html__( 'Order Output', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<select id="dd_owl_orderby" name="dd_owl_orderby" class="dd_owl_orderby_field">';
		echo '			<option value="date_asc" ' . selected( $dd_owl_orderby, 'date_asc', false ) . '> ' . esc_html__( ' Date Ascending', 'owl-carousel-2' ) . '</option>';
		echo '			<option value="date_desc" ' . selected( $dd_owl_orderby, 'date_desc', false ) . '> ' . esc_html__( 'Date Descending', 'owl-carousel-2' ) . '</option>';
		echo '			<option value="rand" ' . selected( $dd_owl_orderby, 'rand', false ) . '> ' . esc_html__( 'Random', 'owl-carousel-2' ) . '</option>';
		echo '			<option value="title_asc" ' . selected( $dd_owl_orderby, 'title_asc', false ) . '> ' . esc_html__( 'Title Ascending', 'owl-carousel-2' ) . '</option>';
		echo '			<option value="title_desc" ' . selected( $dd_owl_orderby, 'title_desc', false ) . '> ' . esc_html__( 'Title Descending', 'owl-carousel-2' ) . '</option>';
		echo '			<option value="menu" ' . selected( $dd_owl_orderby, 'menu', false ) . '> ' . esc_html__( 'Menu Order', 'owl-carousel-2' ) . '</option>';
		echo '			</select>';
		echo '		</td>';
		echo '	</tr>';
		echo '  </table></div>';
		/**
		 * Options for Reviews
		 */
		echo '<div id="reviews_options" class="' . esc_attr( $is_review ) . '">';
		echo '<h4>' . esc_html__( 'Options for Reviews', 'owl-carousel-2' ) . '</h4>';
		echo '<table class="form-table">';
		echo sprintf( '<tr data-id="product_reviews" class="%s">', esc_attr( $is_review ) );
		echo '		<th><label for="dd_owl_show_review_product" class="dd_owl_show_review_product_label">' . esc_html__( 'Product Name', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<label><input type="checkbox" id="dd_owl_show_review_product" name="dd_owl_show_review_product" class="dd_owl_show_review_product_field" value="checked" ' . checked( $dd_owl_show_review_product, '', false ) . '> ' . esc_html__( 'Yes', 'owl-carousel-2' ) . '</label>';
		echo '			<p class="description">' . esc_html__( 'Show a link to the review product', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo '  </tr>';
		echo sprintf( '<tr data-id="product_reviews" class="%s">', esc_attr( $is_review ) );
		echo '		<th><label for="dd_owl_show_review_stars" class="dd_owl_show_review_stars_label">' . esc_html__( 'Show Stars', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<label><input type="checkbox" id="dd_owl_show_review_stars" name="dd_owl_show_review_stars" class="dd_owl_show_review_stars_field" value="checked" ' . checked( $dd_owl_show_review_stars, '', false ) . '> ' . esc_html__( 'Yes', 'owl-carousel-2' ) . '</label>';
		echo '			<p class="description">' . esc_html__( 'Show the stars.', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo '  </tr>';
		echo sprintf( '<tr data-id="product_reviews" class="%s">', esc_attr( $is_review ) );
		echo '		<th><label for="dd_owl_show_review_date" class="dd_owl_show_review_date_label">' . esc_html__( 'Show Date', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<label><input type="checkbox" id="dd_owl_show_review_date" name="dd_owl_show_review_date" class="dd_owl_show_review_date_field" value="checked" ' . checked( $dd_owl_show_review_date, '', false ) . '> ' . esc_html__( 'Yes', 'owl-carousel-2' ) . '</label>';
		echo '			<p class="description">' . esc_html__( 'Show the date of the review.', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo '  </tr>';
		echo sprintf( '<tr data-id="product_reviews" class="%s">', esc_attr( $is_review ) );
		echo '		<th><label for="dd_owl_show_review_reviewer" class="dd_owl_show_review_reviewer_label">' . esc_html__( 'Show Reviewer', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<label><input type="checkbox" id="dd_owl_show_review_reviewer" name="dd_owl_show_review_reviewer" class="dd_owl_show_review_reviewer_field" value="checked" ' . checked( $dd_owl_show_review_reviewer, '', false ) . '> ' . esc_html__( 'Yes', 'owl-carousel-2' ) . '</label>';
		echo '			<p class="description">' . esc_html__( 'Show reviewed by.', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo '  </tr>';
		echo '</table>';
		echo '</div>';
		/**
		 * Display Post Options - Set Appearance
		 *
		 * @since    1.0.0
		 */
		echo '<div id="displayPostOptions">';
		echo '<h4>' . esc_html__( 'Display Post Options', 'owl-carousel-2' ) . '</h4>';
		echo '<table class="form-table">';
		echo sprintf( '<tr data-id="display_post_options" %s>', esc_attr( $hidden_media ) );
		echo '		<th><label for="dd_owl_show_title" class="dd_owl_show_title_label">' . esc_html__( 'Hide the Post Title', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<label><input type="checkbox" id="dd_owl_show_title" name="dd_owl_show_title" class="dd_owl_show_title_field" value="checked" ' . checked( $dd_owl_show_title, 'checked', false ) . '> ' . esc_html__( 'Yes', 'owl-carousel-2' ) . '</label>';
		echo '			<p class="description">' . esc_html__( 'Include the post title in the carousel. This is shown by default, check yes to hide it.', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo '  </tr>';
		echo sprintf( '<tr data-id="display_post_options" %s>', esc_attr( $hidden_media ) );
		echo '		<th><label for="dd_owl_title_heading" class="dd_owl_title_heading_label">' . esc_html__( 'Heading Type', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<select id="dd_owl_title_heading" name="dd_owl_title_heading" class="dd_owl_title_heading_field">';
		echo '			<option value="h1" ' . selected( $dd_owl_title_heading, 'h1', false ) . '> ' . esc_html__( 'H1', 'owl-carousel-2' ) . '</option>';
		echo '			<option value="h2" ' . selected( $dd_owl_title_heading, 'h2', false ) . '> ' . esc_html__( 'H2', 'owl-carousel-2' ) . '</option>';
		echo '			<option value="h3" ' . selected( $dd_owl_title_heading, 'h3', false ) . '> ' . esc_html__( 'H3', 'owl-carousel-2' ) . '</option>';
		echo '			<option value="h4" ' . selected( $dd_owl_title_heading, 'h4', false ) . '> ' . esc_html__( 'H4', 'owl-carousel-2' ) . '</option>';
		echo '			<option value="h5" ' . selected( $dd_owl_title_heading, 'h5', false ) . '> ' . esc_html__( 'H5', 'owl-carousel-2' ) . '</option>';
		echo '			<option value="h6" ' . selected( $dd_owl_title_heading, 'h6', false ) . '> ' . esc_html__( 'H6', 'owl-carousel-2' ) . '</option>';
		echo '			<option value="p" ' . selected( $dd_owl_title_heading, 'p', false ) . '> ' . esc_html__( 'p', 'owl-carousel-2' ) . '</option>';
		echo '			<option value="strong" ' . selected( $dd_owl_title_heading, 'strong', false ) . '> ' . esc_html__( 'Bold', 'owl-carousel-2' ) . '</option>';
		echo '			</select>';
		echo '			<p class="description">' . esc_html__( 'What type of heading should the title be?', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo '  </tr>';
		echo sprintf( '<tr data-id="display_post_options" %s>', esc_attr( $hidden_media ) );
		echo '		<th><label for="dd_owl_show_cta" class="dd_owl_show_cta_label">' . esc_html__( 'Show Link to Post', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<label><input type="checkbox" id="dd_owl_show_cta" name="dd_owl_show_cta" class="dd_owl_show_cta_field" value="checked" ' . checked( $dd_owl_show_cta, 'checked', false ) . '> ' . esc_html__( 'Yes', 'owl-carousel-2' ) . '</label>';
		echo '			<p class="description">' . esc_html__( 'Include a link to the post. Additional options are available', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo '  </tr>';
		// Show button Options.
		$btn_options = ( 'checked' !== $dd_owl_show_cta || 'attachment' === $dd_owl_post_type ) ? ' hidden' : '';
		echo '  <tr class="show-button' . esc_attr( $btn_options ) . '">';
		echo '		<th><label for="dd_owl_cta" class="dd_owl_cta_label">' . esc_html__( 'Button Text', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="text" id="dd_owl_cta" name="dd_owl_cta" class="dd_owl_cta_field" placeholder="' . esc_attr__( 'Read More', 'owl-carousel-2' ) . '" value="' . esc_attr( $dd_owl_cta ) . '">';
		echo '			<p class="description">' . esc_html__( 'Text inside the button', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo '		<th><label for="dd_owl_btn_class" class="dd_owl_btn_class_label">' . esc_html__( 'Button CSS Class', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="text" id="dd_owl_btn_class" name="dd_owl_btn_class" class="dd_owl_btn_class_field" placeholder="' . esc_attr__( 'classname', 'owl-carousel-2' ) . '" value="' . esc_attr( $dd_owl_btn_class ) . '">';
		echo '			<p class="description">' . esc_html__( 'CSS Class for the button', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo '  </tr>';
		echo '  <tr class="show-button' . esc_attr( $btn_options ) . '">';
		echo '		<th><label for="dd_owl_btn_display" class="dd_owl_btn_display_label">' . esc_html__( 'Button CSS Display', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<select id="dd_owl_btn_display" name="dd_owl_btn_display" class="dd_owl_btn_display_field">';
		echo '			<option value="inline" ' . selected( $dd_owl_btn_display, 'inline', false ) . '> ' . esc_html__( 'Inline', 'owl-carousel-2' ) . '</option>';
		echo '			<option value="inline-block" ' . selected( $dd_owl_btn_display, 'inline-block', false ) . '> ' . esc_html__( 'Inline-Block', 'owl-carousel-2' ) . '</option>';
		echo '			<option value="block" ' . selected( $dd_owl_btn_display, 'block', false ) . '> ' . esc_html__( 'Block', 'owl-carousel-2' ) . '</option>';
		echo '			</select>';
		echo '			<p class="description">' . esc_html__( 'CSS Display option for the link / Button ', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		$show_margins = ( 'inline' === $dd_owl_btn_display ) ? 'hidden' : 'visible';
		echo '		<th class="button-margin ' . esc_attr( $show_margins ) . '"><label for="dd_owl_btn_margin" class="dd_owl_btn_margin_label">' . esc_html__( 'Button CSS margin', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td class="button-margin ' . esc_attr( $show_margins ) . '">';
		echo '			<input type="text" id="dd_owl_btn_margin" name="dd_owl_btn_margin" class="dd_owl_btn_margin_field" placeholder="' . esc_attr__( '10px', 'owl-carousel-2' ) . '" value="' . esc_attr( $dd_owl_btn_margin ) . '">';
		echo '			<p class="description">' . esc_html__( 'Margins for Button', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo '  </tr>';
		// End button display options.
		echo sprintf( '<tr data-id="display_post_options" %s>', esc_attr( $hidden_media ) );
		echo '		<th><label for="dd_owl_thumbs" class="dd_owl_thumbs_label">' . esc_html__( 'Show Post Thumbnails', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<label><input type="checkbox" id="dd_owl_thumbs" name="dd_owl_thumbs" class="dd_owl_thumbs_field" value="checked" ' . checked( $dd_owl_thumbs, 'checked', false ) . '> ' . esc_html__( 'Yes', 'owl-carousel-2' ) . '</label>';
		echo '			<p class="description">' . esc_html__( 'Check to show the post thumbnail or featured image if it exists.', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';
		$hidden = ( 'checked' !== $dd_owl_thumbs ) ? 'hidden ' : '';
		echo '	<tr class="' . esc_attr( $hidden ) . 'image-options is-media" id="image-options">';
		echo '		<th><label for="dd_owl_image_options" class="dd_owl_image_options_label">' . esc_html__( 'Image On Click Options', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<label><input type="radio" name="dd_owl_image_options" class="dd_owl_image_options_field" value="null" ' . checked( $dd_owl_image_options, 'null', false ) . '> ' . esc_html__( 'None - Just show image', 'owl-carousel-2' ) . '</label><br>';
		echo '			<label><input type="radio" name="dd_owl_image_options" class="dd_owl_image_options_field" value="lightbox" ' . checked( $dd_owl_image_options, 'lightbox', false ) . '> ' . esc_html__( 'Open in Lightbox', 'owl-carousel-2' ) . '</label><br>';
		echo '			<label><input type="radio" name="dd_owl_image_options" class="dd_owl_image_options_field" value="link" ' . checked( $dd_owl_image_options, 'link', false ) . '> ' . esc_html__( 'Link to Post', 'owl-carousel-2' ) . '</label><br>';
		echo '		</td>';
		echo '	</tr>';
		echo '	<tr class="' . esc_attr( $hidden ) . 'image-options is-media">';
		echo '		<th><label for="dd_owl_image_size" class="dd_owl_image_size_label">' . esc_html__( 'Image Size Options', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<select id="dd_owl_image_size" name="dd_owl_image_size" class="dd_owl_image_size_field">';
		echo '              <option value="">- - ' . esc_html__( 'Please Choose', 'owl-carousel-2' ) . '</option>';
		echo '			    <option value="custom" ' . selected( $dd_owl_image_size, 'custom', false ) . '> ' . esc_html__( 'Custom Size', 'owl-carousel-2' ) . '</option>';
		foreach ( Owl_Carousel_2::get_all_image_sizes() as $size => $sizes ) {
			echo '<option value="' . esc_attr( $size ) . '" ' . selected( $dd_owl_image_size, $size, false ) . '>' . esc_attr( $size ) . ' ' . esc_attr( $sizes['width'] ) . ' x ' . esc_attr( $sizes['height'] ) . '</option>';
		}
		echo '			</select>';
		echo '			<p class="description">' . esc_html__( 'Choose an existing image size or custom size ', 'owl-carousel-2' ) . '</p>';
		echo '      </td>';
		echo '  </tr>';
		echo '	<tr class="hidden show-custom">';
		echo '		<th><label for="dd_owl_img_width" class="dd_owl_img_width_label">' . esc_html__( 'Image Width', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="number" id="dd_owl_img_width" name="dd_owl_img_width" class="dd_owl_img_width_field" placeholder="' . esc_attr__( '600', 'owl-carousel-2' ) . '" value="' . esc_attr( $dd_owl_img_width ) . '">';
		echo '			<p class="description">' . esc_html__( 'Width of the image', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo '		<th><label for="dd_owl_img_height" class="dd_owl_img_height_label">' . esc_html__( 'Image Height', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="number" id="dd_owl_img_height" name="dd_owl_img_height" class="dd_owl_img_height_field" placeholder="' . esc_attr__( '400', 'owl-carousel-2' ) . '" value="' . esc_attr( $dd_owl_img_height ) . '">';
		echo '			<p class="description">' . esc_html__( 'Height of the Image', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr class="hidden show-custom">';
		echo '		<th><label for="dd_owl_img_crop" class="dd_owl_img_crop_label">' . esc_html__( 'Crop the Image', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<label><input type="checkbox" id="dd_owl_img_crop" name="dd_owl_img_crop" class="dd_owl_img_crop_field" value="checked" ' . checked( $dd_owl_img_crop, 'checked', false ) . '> ' . esc_html__( 'Yes', 'owl-carousel-2' ) . '</label>';
		echo '			<p class="description">' . esc_html__( 'If checked, image will be hard cropped', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';

		echo '		<th><label for="dd_owl_img_upscale" class="dd_owl_img_upscale_label">' . esc_html__( 'Upscale Image', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<label><input type="checkbox" id="dd_owl_img_upscale" name="dd_owl_img_upscale" class="dd_owl_img_upscale_field" value="checked" ' . checked( $dd_owl_img_upscale, 'checked', false ) . '> ' . esc_html__( 'Yes', 'owl-carousel-2' ) . '</label>';
		echo '			<p class="description">' . esc_html__( 'If checked, the image will be made larger if smaller than the specified size', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';
		echo '</table>';
		echo '</div>';

		/**
		 * Choose Owl Carousel Settings
		 *
		 * @since    1.0.0
		 */

		echo '<h4>Carousel Navigation Options</h4>';
		echo '<table class="form-table">';
		echo '  <tr>';
		echo '		<th><label for="dd_owl_css_id" class="dd_owl_css_id_label">' . esc_html__( 'CSS ID', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="text" id="dd_owl_css_id" name="dd_owl_css_id" class="dd_owl_css_id_field" placeholder="' . esc_attr( 'carousel-' . $post->ID ) . '" value="' . esc_attr( $dd_owl_css_id ) . '">';
		echo '			<p class="description">' . esc_html__( 'The CSS ID for the element no spaces please', 'owl-carousel-2' ) . '</p>';
		echo '		</td><th></th><td></td>';
		echo ' </tr>';
		echo ' <tr>';
		echo '		<th><label for="dd_owl_margin" class="dd_owl_margin_label">' . esc_html__( 'Margins around Carousel Items', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="text" id="dd_owl_margin" name="dd_owl_margin" class="dd_owl_margin_field" value="' . esc_attr( $dd_owl_margin ) . '">';
		echo '			<p class="description">' . esc_html__( 'Space between each carousel item in Pixels', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';
		echo '	<tr>';
		echo '		<th><label for="dd_owl_duration" class="dd_owl_duration_label">' . esc_html__( 'Slide Duration', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="number" id="dd_owl_duration" name="dd_owl_duration" class="dd_owl_duration_field" placeholder="' . esc_attr__( 'Slide duration time', 'owl-carousel-2' ) . '" value="' . esc_attr( $dd_owl_duration ) . '">';
		echo '			<p class="description">' . esc_html__( 'Duration in ms.', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo '		<th><label for="dd_owl_transition" class="dd_owl_transition_label">' . esc_html__( 'Slide Transition', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="number" id="dd_owl_transition" name="dd_owl_transition" class="dd_owl_transition_field" placeholder="' . esc_attr__( 'Slide transition time', 'owl-carousel-2' ) . '" value="' . esc_attr( $dd_owl_transition ) . '">';
		echo '			<p class="description">' . esc_html__( 'Transition Time in ms', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="dd_owl_dots" class="dd_owl_dots_label">' . esc_html__( 'Show Dots', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<label><input type="checkbox" id="dd_owl_dots" name="dd_owl_dots" class="dd_owl_dots_field" value="checked" ' . checked( $dd_owl_dots, 'checked', false ) . '> ' . esc_html__( 'Check to show dots', 'owl-carousel-2' ) . '</label>';
		echo '			<p class="description">' . esc_html__( 'Show the dots style navs underneath the carousel.', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="dd_owl_navs" class="dd_owl_navs_label">' . esc_html__( 'Show Nav Arrows', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<label><input type="checkbox" id="dd_owl_navs" name="dd_owl_navs" class="dd_owl_navs_field" value="checked" ' . checked( $dd_owl_navs, 'checked', false ) . '>' . esc_html__( 'Show navigation arrows below the carousel', 'owl-carousel-2' ) . '</label>';
		echo '		</td>';
		echo '		<th><label for="dd_owl_nav_position" class="dd_owl_nav_position_label">' . esc_html__( 'Button Position', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<select id="dd_owl_nav_position" name="dd_owl_nav_position" class="dd_owl_nav_position_field">';
		echo '			<option value="default" ' . selected( $dd_owl_nav_position, 'default', false ) . '> ' . esc_html__( 'Default (bottom)', 'owl-carousel-2' ) . '</option>';
		echo '			<option value="centered" ' . selected( $dd_owl_nav_position, 'centered', false ) . '> ' . esc_html__( 'Vertically Centered', 'owl-carousel-2' ) . '</option>';
		echo '			</select>';
		echo '			<p class="description">' . esc_html__( 'Position of the Prev/Next Buttons ', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';

		echo '	</tr>';
		echo '	<tr>';
		echo '		<th><label for="dd_owl_prev" class="dd_owl_prev_label">' . esc_html__( 'Prev Button Text', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<label><input type="text" id="dd_owl_prev" name="dd_owl_prev" class="dd_owl_prev_field" value="' . esc_attr( $dd_owl_prev ) . '"></label>';
		echo '			<p class="description">' . esc_html__( 'Text for Prev Button', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo '		<th><label for="dd_owl_next" class="dd_owl_next_label">' . esc_html__( 'Next Button Text', 'owl-carousel-2' ) . '</label></th>';
		echo '		<td>';
		echo '			<label><input type="text" id="dd_owl_next" name="dd_owl_next" class="dd_owl_next_field" value="' . esc_attr( $dd_owl_next ) . '"></label>';
		echo '			<p class="description">' . esc_html__( 'Text for Next Button', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';
		echo '</table>';

	}

	/**
	 * Sidebar Items
	 *
	 * @param object $post The WP Post Object.
	 */
	public function owl_carousel_items_content( $post ) {
		$items_width1 = intval( get_post_meta( $post->ID, 'dd_owl_items_width1', true ) );
		$items_width2 = intval( get_post_meta( $post->ID, 'dd_owl_items_width2', true ) );
		$items_width3 = intval( get_post_meta( $post->ID, 'dd_owl_items_width3', true ) );
		$items_width4 = intval( get_post_meta( $post->ID, 'dd_owl_items_width4', true ) );
		$items_width5 = intval( get_post_meta( $post->ID, 'dd_owl_items_width5', true ) );
		$items_width6 = intval( get_post_meta( $post->ID, 'dd_owl_items_width6', true ) );
		if ( 0 === $items_width1 ) {
			$items_width1 = 1;
		}
		if ( 0 === $items_width2 ) {
			$items_width2 = 1;
		}
		if ( 0 === $items_width3 ) {
			$items_width3 = 2;
		}
		if ( 0 === $items_width4 ) {
			$items_width4 = 3;
		}
		if ( 0 === $items_width5 ) {
			$items_width5 = 4;
		}
		if ( 0 === $items_width6 ) {
			$items_width6 = 6;
		}

		echo "<div id='items_displayed_metabox'>\n";
		echo '<p class="description">' . esc_html__( 'This setting determines the number of slides shown for specific css breakpoints.  Each must be set.', 'owl-carousel-2' ) . '</p>';
		echo "<h4>Browser/Device Width:</h4>\n";
		// items for browser width category 1.
		echo "<div><em class='dd_owl_tooltip' href='#' title='Up to 479 pixels'></em><span>Mobile Portrait</span><select name='dd_owl_items_width1'>";
		for ( $i = 1; $i <= 12; $i++ ) {
			if ( $i === $items_width1 ) {
				echo "<option value='" . esc_attr( $i ) . "' selected>" . esc_html( $i ) . '</option>';
			} else {
				echo "<option value='" . esc_attr( $i ) . "'>" . esc_html( $i ) . '</option>';
			}
		}
		echo "</select></div>\n";
		// items for browser width category 2.
		echo "<div><em class='dd_owl_tooltip' href='#' title='480 to 767 pixels'></em><span>Mobile Landscape</span><select name='dd_owl_items_width2'>";
		for ( $i = 1; $i <= 12; $i++ ) {
			if ( $i === $items_width2 ) {
				echo "<option value='" . esc_attr( $i ) . "' selected>" . esc_html( $i ) . '</option>';
			} else {
				echo "<option value='" . esc_attr( $i ) . "'>" . esc_html( $i ) . '</option>';
			}
		}
		echo "</select></div>\n";
		// items for browser width category 3.
		echo "<div><em class='dd_owl_tooltip' href='#' title='768 to 979 pixels'></em><span>Tablet Portrait</span><select name='dd_owl_items_width3'>";
		for ( $i = 1; $i <= 12; $i++ ) {
			if ( $i === $items_width3 ) {
				echo "<option value='" . esc_attr( $i ) . "' selected>" . esc_html( $i ) . '</option>';
			} else {
				echo "<option value='" . esc_attr( $i ) . "'>" . esc_html( $i ) . '</option>';
			}
		}
		echo "</select></div>\n";
		// items for browser width category 4.
		echo "<div><em class='dd_owl_tooltip' href='#' title='980 to 1199 pixels'></em><span>Desktop Small</span><select name='dd_owl_items_width4'>";
		for ( $i = 1; $i <= 12; $i++ ) {
			if ( $i === $items_width4 ) {
				echo "<option value='" . esc_attr( $i ) . "' selected>" . esc_html( $i ) . '</option>';
			} else {
				echo "<option value='" . esc_attr( $i ) . "'>" . esc_html( $i ) . '</option>';
			}
		}
		echo "</select></div>\n";
		// items for browser width category 5.
		echo "<div><em class='dd_owl_tooltip' href='#' title='1200 to 1499 pixels'></em><span>Desktop Large</span><select name='dd_owl_items_width5'>";
		for ( $i = 1; $i <= 12; $i++ ) {
			if ( $i === $items_width5 ) {
				echo "<option value='" . esc_attr( $i ) . "' selected>" . esc_html( $i ) . '</option>';
			} else {
				echo "<option value='" . esc_attr( $i ) . "'>" . esc_html( $i ) . '</option>';
			}
		}
		echo "</select></div>\n";
		// items for browser width category 6.
		echo "<div><em class='dd_owl_tooltip' href='#' title='Over 1500 pixels'></em><span>Desktop X-Large</span><select name='dd_owl_items_width6'>";
		for ( $i = 1; $i <= 12; $i++ ) {
			if ( $i === $items_width6 ) {
				echo "<option value='" . esc_attr( $i ) . "' selected>" . esc_html( $i ) . '</option>';
			} else {
				echo "<option value='" . esc_attr( $i ) . "'>" . esc_html( $i ) . '</option>';
			}
		}
		echo "</select></div>\n";

		echo "</div>\n";
	}

	/**
	 * Another Sidebar on the post
	 *
	 * @param object $post The WP Post Object.
	 */
	public function owl_carousel_items_functions( $post ) {

		$dd_owl_autoplay  = get_post_meta( $post->ID, 'dd_owl_autoplay', true );
		$dd_owl_slideby   = get_post_meta( $post->ID, 'dd_owl_slideby', true );
		$dd_owl_mousedrag = get_post_meta( $post->ID, 'dd_owl_mousedrag', true );
		$dd_owl_touchdrag = get_post_meta( $post->ID, 'dd_owl_touchdrag', true );
		$dd_owl_loop      = get_post_meta( $post->ID, 'dd_owl_loop', true );
		$dd_owl_lazy      = get_post_meta( $post->ID, 'dd_owl_lazy', true );
		$dd_owl_stop      = get_post_meta( $post->ID, 'dd_owl_stop', true );

		if ( ! metadata_exists( 'post', $post->ID, 'dd_owl_lazy' ) ) {
			$dd_owl_lazy = 'checked';
		}

		if ( 'auto-draft' === $post->post_status ) {
			$dd_owl_lazy      = 'checked';
			$dd_owl_mousedrag = 'checked';
			$dd_owl_touchdrag = 'checked';
		}

		if ( empty( $dd_owl_slideby ) ) {
			$dd_owl_slideby = 1;
		}

		echo '<div id="carousel-functions-metabox">';
		echo '<h4>Display Settings</h4>';
		echo '<p>These are additional settings for the carousels that are available to Owl Carousel</p>';
		echo '<table class="form-table">';
		echo '		<tr><td><label for="dd_owl_loop" class="dd_owl_loop_label side-box-label">' . esc_html__( 'Infinite Loop', 'owl-carousel-2' ) . '</label>';
		echo '			<label><input type="checkbox" id="dd_owl_loop" name="dd_owl_loop" class="dd_owl_loop_field" value="checked" ' . checked( $dd_owl_loop, 'checked', false ) . '> ' . esc_html__( 'Check for infinite loop', 'owl-carousel-2' ) . '</label>';
		echo '      <p class="description">' . esc_html__( 'Create an infinite loop of with the carousel, so that it continues to play', 'owl-carousel-2' ) . '</p>';
		echo '		</td></tr>';
		echo '	<tr>';
		echo '		<tr><td><label for="dd_owl_lazy" class="dd_owl_lazy_label side-box-label">' . esc_html__( 'Lazy Load', 'owl-carousel-2' ) . '</label>';
		echo '			<label><input type="checkbox" id="dd_owl_lazy" name="dd_owl_lazy" class="dd_owl_lazy_field" value="checked" ' . checked( $dd_owl_lazy, 'checked', false ) . '> ' . esc_html__( 'Check for Lazy Load', 'owl-carousel-2' ) . '</label>';
		echo '      <p class="description">' . esc_html__( 'Lazy Load Carousel Images. Helps with faster page loads.', 'owl-carousel-2' ) . '</p>';
		echo '		</td></tr>';
		echo '	<tr>';
		echo '		<td><label for="dd_owl_stop" class="dd_owl_stop_label side-box-label">' . esc_html__( 'Pause on Hover', 'owl-carousel-2' ) . '</label>';
		echo '			<label><input type="checkbox" id="dd_owl_stop" name="dd_owl_stop" class="dd_owl_stop_field" value="checked" ' . checked( $dd_owl_stop, 'checked', false ) . '> ' . esc_html__( 'Check to pause on hover', 'owl-carousel-2' ) . '</label>';
		echo '			<p class="description">' . esc_html__( 'Pause the carousel while the mouse is hovering on the item', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo ' </tr>';
		echo '	<tr>';
		echo '		<td><label for="dd_owl_autoplay" class="dd_owl_autoplay_label side-box-label">' . esc_html__( 'Autoplay Carousel', 'owl-carousel-2' ) . '</label>';
		echo '			<label><input type="checkbox" id="dd_owl_autoplay" name="dd_owl_autoplay" class="dd_owl_autoplay_field" value="checked" ' . checked( $dd_owl_autoplay, '', false ) . '> ' . esc_html__( 'Check to autoplay', 'owl-carousel-2' ) . '</label>';
		echo '			<p class="description">' . esc_html__( 'The carousel starts playing automatically', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo ' </tr>';
		echo '	<tr>';
		echo '		<td><label for="dd_owl_slideby" class="dd_owl_slideby_label"><b>' . esc_html__( 'Slide By', 'owl-carousel-2' ) . '</b></label>';
		echo '			<input type="number" id="dd_owl_slideby" name="dd_owl_slideby" class="dd_owl_slideby_field" value="' . esc_attr( $dd_owl_slideby ) . '">';
		echo '			<p class="description">' . esc_html__( 'Number of items to Slide or Swipe by', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo ' </tr>';
		echo '	<tr>';
		echo '		<td><label for="dd_owl_mousedrag" class="dd_owl_mousedrag_label side-box-label">' . esc_html__( 'Enable Mouse Drag', 'owl-carousel-2' ) . '</label>';
		echo '			<label><input type="checkbox" id="dd_owl_mousedrag" name="dd_owl_mousedrag" class="dd_owl_mousedrag_field" value="checked" ' . checked( $dd_owl_mousedrag, 'checked', false ) . '> ' . esc_html__( 'Check to Enable Mouse Drag', 'owl-carousel-2' ) . '</label>';
		echo '			<p class="description">' . esc_html__( 'Items can be dragged with a mouse click drag action.', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo ' </tr>';
		echo '	<tr>';
		echo '		<td><label for="dd_owl_touchdrag" class="dd_owl_touchdrag_label side-box-label">' . esc_html__( 'Enable TouchDrag', 'owl-carousel-2' ) . '</label>';
		echo '			<label><input type="checkbox" id="dd_owl_touchdrag" name="dd_owl_touchdrag" class="dd_owl_touchdrag_field" value="checked" ' . checked( $dd_owl_touchdrag, 'checked', false ) . '> ' . esc_html__( 'Check to Enable TouchDrag', 'owl-carousel-2' ) . '</label>';
		echo '			<p class="description">' . esc_html__( 'Allows users to drag items with touch devices.', 'owl-carousel-2' ) . '</p>';
		echo '		</td>';
		echo ' </tr>';
		echo '</table>';
		echo '</div>';
	}

	/**
	 * Create the shortcode link
	 *
	 * @param object $post WP Post Object.
	 */
	public function owl_carousel_shortcode_link( $post ) {
		if ( 'publish' !== $post->post_status ) {
			$shortcode = 'Publish First for Shortcode';
		} else {
			$shortcode = '[dd-owl-carousel id="' . $post->ID . '"]';
		}
		echo "<div id='dd_owl_shortcode'>" . esc_html( $shortcode ) . "</div>\n";
		echo "<div id='dd_shortcode_copy' class='button button-primary'>Copy to Clipboard</div>\n";
	}

	/**
	 * Save the Metabox
	 *
	 * @param int    $post_id the WP_Post ID of the item being saved.
	 * @param object $post the WP_Post Object of the item being saved.
	 */
	public function save_metabox( $post_id, $post ) {
		// Add nonce for security and authentication.
		$nonce_name   = $_POST['dd_owl_nonce'] ?? ''; //phpcs:ignore.
		$nonce_action = 'dd_owl_nonce_action';

		// Check if a nonce is set.
		if ( ! isset( $nonce_name ) ) {
			return;
		}
		// Check if a nonce is valid.
		if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
			return;
		}
		// Make sure it's the right post type.
		if ( $post && 'owl-carousel' !== $post->post_type ) {
			return;
		}
		// Sanitize user input.
		$dd_owl_new_post_type            = isset( $_POST['dd_owl_post_type'] ) ? sanitize_text_field( wp_unslash( $_POST['dd_owl_post_type'] ) ) : '';
		$dd_owl_new_number_posts         = isset( $_POST['dd_owl_number_posts'] ) ? floatval( sanitize_text_field( wp_unslash( $_POST['dd_owl_number_posts'] ) ) ) : '';
		$dd_owl_new_excerpt_more         = isset( $_POST['dd_owl_excerpt_more'] ) ? sanitize_text_field( wp_unslash( $_POST['dd_owl_excerpt_more'] ) ) : '';
		$dd_owl_new_hide_excerpt_more    = isset( $_POST['dd_owl_hide_excerpt_more'] ) ? 'checked' : '';
		$dd_owl_new_loop                 = isset( $_POST['dd_owl_loop'] ) ? 'checked' : '';
		$dd_owl_new_show_cta             = isset( $_POST['dd_owl_show_cta'] ) ? 'checked' : '';
		$dd_owl_new_show_title           = isset( $_POST['dd_owl_show_title'] ) ? 'checked' : '';
		$dd_owl_new_center               = isset( $_POST['dd_owl_center'] ) ? 'checked' : '';
		$dd_owl_new_duration             = isset( $_POST['dd_owl_duration'] ) ? floatval( sanitize_text_field( wp_unslash( $_POST['dd_owl_duration'] ) ) ) : '';
		$dd_owl_new_transition           = isset( $_POST['dd_owl_transition'] ) ? floatval( sanitize_text_field( wp_unslash( $_POST['dd_owl_transition'] ) ) ) : '';
		$dd_owl_new_stop                 = isset( $_POST['dd_owl_stop'] ) ? 'checked' : '';
		$dd_owl_new_orderby              = isset( $_POST['dd_owl_orderby'] ) ? sanitize_text_field( wp_unslash( $_POST['dd_owl_orderby'] ) ) : '';
		$dd_owl_title_heading            = isset( $_POST['dd_owl_title_heading'] ) ? sanitize_text_field( wp_unslash( $_POST['dd_owl_title_heading'] ) ) : '';
		$dd_owl_new_navs                 = isset( $_POST['dd_owl_navs'] ) ? 'checked' : '';
		$dd_owl_new_dots                 = isset( $_POST['dd_owl_dots'] ) ? 'checked' : '';
		$dd_owl_new_thumbs               = isset( $_POST['dd_owl_thumbs'] ) ? 'checked' : '';
		$dd_owl_new_css_id               = isset( $_POST['dd_owl_css_id'] ) ? sanitize_text_field( wp_unslash( $_POST['dd_owl_css_id'] ) ) : '';
		$dd_owl_new_cta                  = isset( $_POST['dd_owl_cta'] ) ? sanitize_text_field( wp_unslash( $_POST['dd_owl_cta'] ) ) : '';
		$dd_owl_new_btn_class            = isset( $_POST['dd_owl_btn_class'] ) ? sanitize_text_field( wp_unslash( $_POST['dd_owl_btn_class'] ) ) : '';
		$dd_owl_new_excerpt_length       = isset( $_POST['dd_owl_excerpt_length'] ) ? floatval( sanitize_text_field( wp_unslash( $_POST['dd_owl_excerpt_length'] ) ) ) : '';
		$dd_owl_new_margin               = isset( $_POST['dd_owl_margin'] ) ? floatval( sanitize_text_field( wp_unslash( $_POST['dd_owl_margin'] ) ) ) : '';
		$dd_owl_new_image_options        = isset( $_POST['dd_owl_image_options'] ) ? sanitize_text_field( wp_unslash( $_POST['dd_owl_image_options'] ) ) : '';
		$dd_owl_new_tax_options          = isset( $_POST['dd_owl_tax_options'] ) ? sanitize_text_field( wp_unslash( $_POST['dd_owl_tax_options'] ) ) : '';
		$dd_owl_new_post_taxonomy_type   = isset( $_POST['dd_owl_post_taxonomy_type'] ) ? sanitize_text_field( wp_unslash( $_POST['dd_owl_post_taxonomy_type'] ) ) : '';
		$dd_owl_new_post_taxonomy_term   = isset( $_POST['dd_owl_post_taxonomy_term'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['dd_owl_post_taxonomy_term'] ) ) : '';
		$dd_owl_new_post_ids             = isset( $_POST['dd_owl_post_ids'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['dd_owl_post_ids'] ) ) : '';
		$dd_owl_new_nav_position         = isset( $_POST['dd_owl_nav_position'] ) ? sanitize_text_field( wp_unslash( $_POST['dd_owl_nav_position'] ) ) : '';
		$dd_owl_new_btn_display          = isset( $_POST['dd_owl_btn_display'] ) ? sanitize_text_field( wp_unslash( $_POST['dd_owl_btn_display'] ) ) : '';
		$dd_owl_new_btn_margin           = isset( $_POST['dd_owl_btn_margin'] ) ? sanitize_text_field( wp_unslash( $_POST['dd_owl_btn_margin'] ) ) : '';
		$dd_owl_new_img_width            = isset( $_POST['dd_owl_img_width'] ) ? floatval( $_POST['dd_owl_img_width'] ) : '';
		$dd_owl_new_img_height           = isset( $_POST['dd_owl_img_height'] ) ? floatval( $_POST['dd_owl_img_height'] ) : '';
		$dd_owl_new_img_crop             = isset( $_POST['dd_owl_img_crop'] ) ? 'checked' : '';
		$dd_owl_new_img_upscale          = isset( $_POST['dd_owl_img_upscale'] ) ? 'checked' : '';
		$dd_owl_new_media_items          = isset( $_POST['dd_owl_media_items_array'] ) ? ( $_POST['dd_owl_media_items_array'] )  : ''; //phpcs:ignore
		$dd_owl_new_image_size           = isset( $_POST['dd_owl_image_size'] ) ? sanitize_text_field( wp_unslash( $_POST['dd_owl_image_size'] ) ) : '';
		$dd_owl_new_use_image_caption    = isset( $_POST['dd_owl_use_image_caption'] ) ? 'checked' : '';
		$dd_owl_new_show_review_stars    = isset( $_POST['dd_owl_show_review_stars'] ) ? '' : 'checked';
		$dd_owl_new_show_review_product  = isset( $_POST['dd_owl_show_review_product'] ) ? '' : 'checked';
		$dd_owl_new_show_review_date     = isset( $_POST['dd_owl_show_review_date'] ) ? '' : 'checked';
		$dd_owl_new_show_review_reviewer = isset( $_POST['dd_owl_show_review_reviewer'] ) ? '' : 'checked';
		$dd_owl_new_autoplay             = isset( $_POST['dd_owl_autoplay'] ) ? '' : 'checked';
		$dd_owl_new_mousedrag            = isset( $_POST['dd_owl_mousedrag'] ) ? 'checked' : '';
		$dd_owl_new_touchdrag            = isset( $_POST['dd_owl_touchdrag'] ) ? 'checked' : '';
		$dd_owl_new_lazy                 = isset( $_POST['dd_owl_lazy'] ) ? 'checked' : '';
		$dd_owl_new_slideby              = isset( $_POST['dd_owl_slideby'] ) ? abs( sanitize_text_field( wp_unslash( $_POST['dd_owl_slideby'] ) ) ) : 1;

		// Allow SVG in WP_KSES.
		$kses_defaults = wp_kses_allowed_html( 'post' );

		$svg_args = array(
			'svg'   => array(
				'class'           => true,
				'aria-hidden'     => true,
				'aria-labelledby' => true,
				'role'            => true,
				'xmlns'           => true,
				'width'           => true,
				'height'          => true,
				'viewbox'         => true, // <= Must be lower case!
			),
			'g'     => array( 'fill' => true ),
			'title' => array( 'title' => true ),
			'path'  => array(
				'd'    => true,
				'fill' => true,
			),
		);

		$allowed_tags = array_merge( $kses_defaults, $svg_args );

		$dd_owl_new_prev = isset( $_POST['dd_owl_prev'] ) ? wp_kses( wp_unslash( $_POST['dd_owl_prev'] ), $allowed_tags ) : '&lt;';
		$dd_owl_new_next = isset( $_POST['dd_owl_next'] ) ? wp_kses( wp_unslash( $_POST['dd_owl_next'] ), $allowed_tags ) : '&gt;';
		// Owl Carousel Settings.
		$dd_owl_new_items_width1 = isset( $_POST['dd_owl_items_width1'] ) ? abs( intval( sanitize_text_field( wp_unslash( $_POST['dd_owl_items_width1'] ) ) ) ) : '';
		$dd_owl_new_items_width2 = isset( $_POST['dd_owl_items_width2'] ) ? abs( intval( sanitize_text_field( wp_unslash( $_POST['dd_owl_items_width2'] ) ) ) ) : '';
		$dd_owl_new_items_width3 = isset( $_POST['dd_owl_items_width3'] ) ? abs( intval( sanitize_text_field( wp_unslash( $_POST['dd_owl_items_width3'] ) ) ) ) : '';
		$dd_owl_new_items_width4 = isset( $_POST['dd_owl_items_width4'] ) ? abs( intval( sanitize_text_field( wp_unslash( $_POST['dd_owl_items_width4'] ) ) ) ) : '';
		$dd_owl_new_items_width5 = isset( $_POST['dd_owl_items_width5'] ) ? abs( intval( sanitize_text_field( wp_unslash( $_POST['dd_owl_items_width5'] ) ) ) ) : '';
		$dd_owl_new_items_width6 = isset( $_POST['dd_owl_items_width6'] ) ? abs( intval( sanitize_text_field( wp_unslash( $_POST['dd_owl_items_width6'] ) ) ) ) : '';

		// Update the meta field in the database.
		update_post_meta( $post_id, 'dd_owl_post_type', $dd_owl_new_post_type );
		update_post_meta( $post_id, 'dd_owl_number_posts', $dd_owl_new_number_posts );
		update_post_meta( $post_id, 'dd_owl_excerpt_more', $dd_owl_new_excerpt_more );
		update_post_meta( $post_id, 'dd_owl_hide_excerpt_more', $dd_owl_new_hide_excerpt_more );
		update_post_meta( $post_id, 'dd_owl_loop', $dd_owl_new_loop );
		update_post_meta( $post_id, 'dd_owl_lazy', $dd_owl_new_lazy );
		update_post_meta( $post_id, 'dd_owl_show_cta', $dd_owl_new_show_cta );
		update_post_meta( $post_id, 'dd_owl_show_title', $dd_owl_new_show_title );
		update_post_meta( $post_id, 'dd_owl_title_heading', $dd_owl_title_heading );
		update_post_meta( $post_id, 'dd_owl_center', $dd_owl_new_center );
		update_post_meta( $post_id, 'dd_owl_duration', $dd_owl_new_duration );
		update_post_meta( $post_id, 'dd_owl_transition', $dd_owl_new_transition );
		update_post_meta( $post_id, 'dd_owl_stop', $dd_owl_new_stop );
		update_post_meta( $post_id, 'dd_owl_orderby', $dd_owl_new_orderby );
		update_post_meta( $post_id, 'dd_owl_navs', $dd_owl_new_navs );
		update_post_meta( $post_id, 'dd_owl_dots', $dd_owl_new_dots );
		update_post_meta( $post_id, 'dd_owl_thumbs', $dd_owl_new_thumbs );
		update_post_meta( $post_id, 'dd_owl_css_id', $dd_owl_new_css_id );
		update_post_meta( $post_id, 'dd_owl_cta', $dd_owl_new_cta );
		update_post_meta( $post_id, 'dd_owl_btn_class', $dd_owl_new_btn_class );
		update_post_meta( $post_id, 'dd_owl_excerpt_length', $dd_owl_new_excerpt_length );
		update_post_meta( $post_id, 'dd_owl_margin', $dd_owl_new_margin );
		update_post_meta( $post_id, 'dd_owl_image_options', $dd_owl_new_image_options );
		update_post_meta( $post_id, 'dd_owl_post_taxonomy_type', $dd_owl_new_post_taxonomy_type );
		update_post_meta( $post_id, 'dd_owl_post_taxonomy_term', $dd_owl_new_post_taxonomy_term );
		update_post_meta( $post_id, 'dd_owl_post_ids', $dd_owl_new_post_ids );
		update_post_meta( $post_id, 'dd_owl_tax_options', $dd_owl_new_tax_options );
		update_post_meta( $post_id, 'dd_owl_nav_position', $dd_owl_new_nav_position );
		update_post_meta( $post_id, 'dd_owl_btn_display', $dd_owl_new_btn_display );
		update_post_meta( $post_id, 'dd_owl_btn_margin', $dd_owl_new_btn_margin );
		update_post_meta( $post_id, 'dd_owl_img_width', $dd_owl_new_img_width );
		update_post_meta( $post_id, 'dd_owl_img_height', $dd_owl_new_img_height );
		update_post_meta( $post_id, 'dd_owl_img_crop', $dd_owl_new_img_crop );
		update_post_meta( $post_id, 'dd_owl_img_upscale', $dd_owl_new_img_upscale );
		update_post_meta( $post_id, 'dd_owl_media_items', $dd_owl_new_media_items );
		update_post_meta( $post_id, 'dd_owl_use_image_caption', $dd_owl_new_use_image_caption );
		update_post_meta( $post_id, 'dd_owl_image_size', $dd_owl_new_image_size );
		update_post_meta( $post_id, 'dd_owl_show_review_stars', $dd_owl_new_show_review_stars );
		update_post_meta( $post_id, 'dd_owl_show_review_product', $dd_owl_new_show_review_product );
		update_post_meta( $post_id, 'dd_owl_show_review_date', $dd_owl_new_show_review_date );
		update_post_meta( $post_id, 'dd_owl_show_review_reviewer', $dd_owl_new_show_review_reviewer );
		update_post_meta( $post_id, 'dd_owl_next', $dd_owl_new_next );
		update_post_meta( $post_id, 'dd_owl_prev', $dd_owl_new_prev );
		update_post_meta( $post_id, 'dd_owl_autoplay', $dd_owl_new_autoplay );
		update_post_meta( $post_id, 'dd_owl_slideby', $dd_owl_new_slideby );
		update_post_meta( $post_id, 'dd_owl_mousedrag', $dd_owl_new_mousedrag );
		update_post_meta( $post_id, 'dd_owl_touchdrag', $dd_owl_new_touchdrag );

		// Update Side Meta Fields.
		update_post_meta( $post_id, 'dd_owl_items_width1', $dd_owl_new_items_width1 );
		update_post_meta( $post_id, 'dd_owl_items_width2', $dd_owl_new_items_width2 );
		update_post_meta( $post_id, 'dd_owl_items_width3', $dd_owl_new_items_width3 );
		update_post_meta( $post_id, 'dd_owl_items_width4', $dd_owl_new_items_width4 );
		update_post_meta( $post_id, 'dd_owl_items_width5', $dd_owl_new_items_width5 );
		update_post_meta( $post_id, 'dd_owl_items_width6', $dd_owl_new_items_width6 );

	}

}
