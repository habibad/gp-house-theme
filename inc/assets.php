<?php
/**
 * Asset loading.
 *
 * Global CSS: base → components → layout. Each page adds only its own
 * stylesheet (assets/css/pages/<page>.css), chosen by gp_current_page_key().
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

/**
 * Key of the current design page; matches assets/css/pages/<key>.css and
 * template-parts/<key>/. Extend via the `gp_house_page_key` filter.
 */
function gp_current_page_key() {
	$key = '';
	if ( is_front_page() ) {
		$key = 'home';
	} elseif ( is_singular( 'gp_room' ) ) {
		$key = 'room';
	} elseif ( is_post_type_archive( 'gp_stay' ) || is_singular( 'gp_stay' ) ) {
		$key = 'stays';
	} elseif ( is_tax( 'gp_destination' ) ) {
		$key = 'destinations';
	} elseif ( is_home() || is_singular( 'post' ) || is_category() ) {
		$key = 'journal';
	} elseif ( is_page( 'destinations' ) ) {
		$key = 'destinations';
	} elseif ( is_page() ) {
		$key = get_post_field( 'post_name', get_queried_object_id() );
	}
	return apply_filters( 'gp_house_page_key', $key );
}

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'gp-house-fonts', GP_HOUSE_URI . '/assets/css/fonts.css', array(), gp_asset_version( 'assets/css/fonts.css' ) );

	$deps = array( 'gp-house-fonts' );
	foreach ( array( 'base', 'components', 'layout' ) as $sheet ) {
		wp_enqueue_style( 'gp-house-' . $sheet, GP_HOUSE_URI . "/assets/css/{$sheet}.css", $deps, gp_asset_version( "assets/css/{$sheet}.css" ) );
		$deps = array( 'gp-house-' . $sheet );
	}

	$page = sanitize_key( gp_current_page_key() );
	if ( $page && file_exists( GP_HOUSE_DIR . "/assets/css/pages/{$page}.css" ) ) {
		wp_enqueue_style( 'gp-house-page', GP_HOUSE_URI . "/assets/css/pages/{$page}.css", $deps, gp_asset_version( "assets/css/pages/{$page}.css" ) );
	}

	wp_enqueue_script( 'gp-house', GP_HOUSE_URI . '/assets/js/main.js', array(), gp_asset_version( 'assets/js/main.js' ), array( 'strategy' => 'defer', 'in_footer' => true ) );
} );

/** Preload the two Latin font files used above the fold. */
add_action( 'wp_head', function () {
	foreach ( array( 'dm-sans-latin', 'plus-jakarta-sans-latin' ) as $font ) {
		printf( "<link rel=\"preload\" href=\"%s\" as=\"font\" type=\"font/woff2\" crossorigin>\n", esc_url( GP_HOUSE_URI . "/assets/fonts/{$font}.woff2" ) );
	}
}, 1 );

/** Drop block-library CSS on templates that render no block content. */
add_action( 'wp_enqueue_scripts', function () {
	if ( is_front_page() || is_post_type_archive() || is_singular( 'gp_room' ) || is_tax( 'gp_destination' ) || is_singular( 'post' ) || ( is_page() && locate_template( 'page-' . get_post_field( 'post_name', get_queried_object_id() ) . '.php' ) ) || is_page_template() ) {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'global-styles' );
		wp_dequeue_style( 'classic-theme-styles' );
	}
}, 100 );

/** Body class per design page (gp-page-<key>) so page CSS can adjust shared areas such as the footer CTA. */
add_filter( 'body_class', function ( $classes ) {
	$key = sanitize_html_class( gp_current_page_key() );
	if ( $key ) {
		$classes[] = 'gp-page-' . $key;
	}
	return $classes;
} );
