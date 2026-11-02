<?php
/**
 * Small template helpers.
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

/** Cache-busting version for a theme file (file mtime). */
function gp_asset_version( $relative ) {
	$path = GP_HOUSE_DIR . '/' . $relative;
	return file_exists( $path ) ? (string) filemtime( $path ) : GP_HOUSE_VERSION;
}

/** URL of a file inside assets/img. */
function gp_img( $relative ) {
	return GP_HOUSE_URI . '/assets/img/' . ltrim( $relative, '/' );
}

/** Echo an <img> for an icon in assets/img/icons. */
function gp_icon( $name, $size = 24, $alt = '' ) {
	printf(
		'<img class="gp-icon" src="%s" width="%d" height="%d" alt="%s" aria-hidden="%s">',
		esc_url( gp_img( 'icons/' . $name . '.svg' ) ),
		(int) $size,
		(int) $size,
		esc_attr( $alt ),
		$alt ? 'false' : 'true'
	);
}

/** Inline an SVG from assets/img (needed when the SVG uses foreignObject / backdrop blur). */
function gp_inline_svg( $relative, $class = '' ) {
	$path = GP_HOUSE_DIR . '/assets/img/' . $relative;
	if ( ! file_exists( $path ) ) {
		return;
	}
	$svg = file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	$svg = preg_replace( '/\sstyle="display: block;"/', '', $svg, 1 );
	if ( $class ) {
		$svg = preg_replace( '/<svg\b/', '<svg class="' . esc_attr( $class ) . '" aria-hidden="true" focusable="false"', $svg, 1 );
	}
	echo $svg; // phpcs:ignore WordPress.Security.EscapeOutput -- trusted theme file.
}

/** Format a nightly price like the design: $380. */
function gp_price( $amount ) {
	return '$' . number_format_i18n( (float) $amount );
}

/** Theme option with design default (Customizer). */
function gp_opt( $key ) {
	$defaults = gp_option_defaults();
	return get_theme_mod( 'gp_' . $key, $defaults[ $key ]['default'] ?? '' );
}
