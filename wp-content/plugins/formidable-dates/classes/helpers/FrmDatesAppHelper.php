<?php
class FrmDatesAppHelper {

	public static function plugin_file() {
		return dirname( dirname( dirname( __FILE__ ) ) ) . '/formidable-dates.php';
	}

	public static function get_path( $path = '/' ) {
		return plugin_dir_path( self::plugin_file() ) . $path;
	}

	public static function get_url( $path = '/' ) {
		return plugins_url( $path, self::plugin_file() );
	}

	public static function get_days_of_the_week( $args = null ) {
		global $wp_locale;

		$week_start = absint( get_option( 'start_of_week' ) );

		$n = $week_start;
		for ( $i = 0; $i < 7; $i++ ) {
			$week_days[ strval( ( $n + $i ) % 7 ) ] = $wp_locale->get_weekday_abbrev( $wp_locale->get_weekday( ( $n + $i ) % 7 ) );
		}

		return $week_days;
	}
}
