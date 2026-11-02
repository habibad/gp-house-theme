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
	} elseif ( is_post_type_archive( 'gp_stay' ) || is_singular( 'gp_stay' ) ) {
		$key = 'stays';
	} elseif ( is_tax( 'gp_destination' ) ) {
		$key = 'destinations';
	} elseif ( is_home() || is_singular( 'post' ) || is_category() ) {
		$key = 'journal';
	} elseif ( is_page() ) {
		$key = get_post_field( 'post_name', get_queried_object_id() );
	}
	return apply_filters( 'gp_house_page_key', $key );
}

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'gp-house-fonts',
		'https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap',
		array(),
		null
	);

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

add_filter( 'wp_resource_hints', function ( $urls, $relation ) {
	if ( 'preconnect' === $relation ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
	}
	return $urls;
}, 10, 2 );

/** Drop block-library CSS on templates that render no block content. */
add_action( 'wp_enqueue_scripts', function () {
	if ( is_front_page() || is_post_type_archive() || is_page_template() ) {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'global-styles' );
		wp_dequeue_style( 'classic-theme-styles' );
	}
}, 100 );
