<?php
/**
 * Display currency (header / footer switch): USD, THB, EUR, GBP.
 *
 * Website prices are kept in US$ (rooms' "from" prices follow eZee, converted with "THB per US$1").
 * The visitor's choice lives in the gp_currency cookie; prices are converted on the server and in JS.
 * Bookings are always charged in Thai baht — other currencies are shown as approximations.
 * Rates are edited in wp-admin → Bookings → Settings (THB / EUR / GBP per US$1).
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

/** Supported currencies with their rate per US$1. */
function gp_currencies() {
	$opt = function ( $key, $default ) {
		return function_exists( 'gp_booking_option' ) ? (float) gp_booking_option( $key, $default ) : (float) $default;
	};
	return apply_filters( 'gp_house_currencies', array(
		'USD' => array( 'symbol' => '$', 'rate' => 1 ),
		'THB' => array( 'symbol' => '฿', 'rate' => max( 1, $opt( 'sim_rate_thb', 35 ) ) ),
		'EUR' => array( 'symbol' => '€', 'rate' => max( 0.01, $opt( 'eur_per_usd', 0.92 ) ) ),
		'GBP' => array( 'symbol' => '£', 'rate' => max( 0.01, $opt( 'gbp_per_usd', 0.79 ) ) ),
	) );
}

/** The visitor's currency code (cookie), default USD. */
function gp_current_currency() {
	static $code = null;
	if ( null === $code ) {
		$c    = isset( $_COOKIE['gp_currency'] ) ? strtoupper( sanitize_key( wp_unslash( $_COOKIE['gp_currency'] ) ) ) : '';
		$code = isset( gp_currencies()[ $c ] ) ? $c : 'USD';
	}
	return $code;
}

/** US$ amount → "฿4,550" in the visitor's currency (or the one given). */
function gp_money( $usd, $currency = '' ) {
	$all  = gp_currencies();
	$code = $currency && isset( $all[ $currency ] ) ? $currency : gp_current_currency();
	return $all[ $code ]['symbol'] . number_format_i18n( round( (float) $usd * $all[ $code ]['rate'] ) );
}

/** Currency data for scripts (main.js formats the room booking card; booking.js shows approximations). */
add_action( 'wp_enqueue_scripts', function () {
	$all  = gp_currencies();
	$code = gp_current_currency();
	wp_localize_script( 'gp-house', 'gpCurrency', array(
		'code'   => $code,
		'symbol' => $all[ $code ]['symbol'],
		'rate'   => $all[ $code ]['rate'],
		'thb'    => $all['THB']['rate'],
	) );
}, 25 );

// Full-page caches must keep one copy per currency (LiteSpeed Cache reads this list).
add_filter( 'litespeed_vary_cookies', function ( $cookies ) {
	$cookies   = (array) $cookies;
	$cookies[] = 'gp_currency';
	return $cookies;
} );
