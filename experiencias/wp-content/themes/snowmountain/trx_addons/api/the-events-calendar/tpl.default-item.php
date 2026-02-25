<?php
/**
 * The style "default" of the Events
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.2
 */

$args = get_query_var('trx_addons_args_sc_events');

if ($args['slider']) {
    ?><div class="swiper-slide"><?php
} else if ($args['columns'] > 1) {
    ?><div class="<?php echo esc_attr(trx_addons_get_column_class(1, $args['columns'])); ?>"><?php
}

?><div class="sc_events_item"><?php
// Event's date
$date_end = tribe_get_end_date(null, true, 'd-F');
$date = tribe_get_start_date(null, true, 'd-F');
if (empty($date)) $date = get_the_date('d-F');
$date = explode('-', $date);
if (empty($date_end)) $date_end = get_the_date('d-F');
$date_end = explode('-', $date_end);
?><div class="sc_events_item_date">
    <span class="sc_events_item_month"><?php echo esc_html($date[1]); ?></span>
    <span class="sc_events_item_day"><?php echo esc_html($date[0]); if (isset($date_end)) echo ' - '.esc_html($date_end[0]); ?></span>
    <?php
    if (isset($date_end) && $date_end[1] != $date[1]) {
        ?>
        <span class="sc_events_item_month"><?php echo esc_html($date_end[1]); ?></span>
        <?php
    }
    ?>
    </div><?php

trx_addons_get_template_part('templates/tpl.featured.php',
    'trx_addons_args_featured',
    apply_filters('trx_addons_filter_args_featured', array(
        'class' => 'sc_events_item_thumb',
        'hover' => 'zoomin',
        'thumb_size' => apply_filters('trx_addons_filter_thumb_size', trx_addons_get_thumb_size($args['columns'] > 2 ? 'events' : 'events-wide'), 'services-default')
    ), 'events-default')
);

// Event's title
?><div class="sc_events_item_content">
    <h4 class="sc_events_item_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
</div><?php

?></div><?php

if ($args['slider'] || $args['columns'] > 1) {
    ?></div><?php
}

?>