<ul class="frmdates_date_list" data-field-id="<?php echo absint( $field_id ); ?>" data-date-type="<?php echo esc_attr( $date_type ); ?>">
	<?php
	foreach ( $items as $i => $date ) :
		echo FrmDatesTemplatesHelper::settings_render_dates_list_item(
			array(
				'date'        => $date,
				'date_type'   => $date_type,
				'field_id'    => $field_id,
				'css_classes' => ( $i > 4 ) ? 'frm_hidden' : '',
			)
		); // XSS ok.
	endforeach;
	?>

	<li class="frmdates_show_all_placeholder <?php echo count( $items ) < 5 ? 'frm_hidden' : ''; ?>">
		<?php // translators: %s - the number of dates initially hidden. ?>
		<a href="#"><?php printf( esc_html( _n( '... and %s more', '... and %s more', count( $items ) - 5, 'frmdates' ) ), '<span class="count">' . ( count( $items ) - 5 ) . '</span>' ); // WPCS: XSS OK. ?></a>
	</li>
</ul>
