<?php
/**
 * Room-page reservation glue: the GP House Booking plugin does the work (eZee + PayPal); this file
 * loads the script and supplies the country list.
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

/** Whether the booking plugin is active and at least one property takes online bookings. */
function gp_booking_enabled() {
	if ( ! function_exists( 'gp_booking_properties' ) ) {
		return false;
	}
	foreach ( array_keys( gp_booking_properties() ) as $slug ) {
		$p = gp_booking_property( $slug );
		if ( 'link' === $p['flow'] || gp_booking_property_ready( $slug ) ) {
			return true;
		}
	}
	return false;
}

/** Countries for the guest form (ISO code => name). */
function gp_countries() {
	return array(
		'AF' => 'Afghanistan', 'AL' => 'Albania', 'DZ' => 'Algeria', 'AR' => 'Argentina', 'AM' => 'Armenia', 'AU' => 'Australia', 'AT' => 'Austria', 'AZ' => 'Azerbaijan',
		'BH' => 'Bahrain', 'BD' => 'Bangladesh', 'BY' => 'Belarus', 'BE' => 'Belgium', 'BT' => 'Bhutan', 'BO' => 'Bolivia', 'BA' => 'Bosnia and Herzegovina', 'BR' => 'Brazil',
		'BN' => 'Brunei', 'BG' => 'Bulgaria', 'KH' => 'Cambodia', 'CA' => 'Canada', 'CL' => 'Chile', 'CN' => 'China', 'CO' => 'Colombia', 'CR' => 'Costa Rica', 'HR' => 'Croatia',
		'CY' => 'Cyprus', 'CZ' => 'Czechia', 'DK' => 'Denmark', 'DO' => 'Dominican Republic', 'EC' => 'Ecuador', 'EG' => 'Egypt', 'EE' => 'Estonia', 'ET' => 'Ethiopia',
		'FJ' => 'Fiji', 'FI' => 'Finland', 'FR' => 'France', 'GE' => 'Georgia', 'DE' => 'Germany', 'GH' => 'Ghana', 'GR' => 'Greece', 'GT' => 'Guatemala', 'HK' => 'Hong Kong',
		'HU' => 'Hungary', 'IS' => 'Iceland', 'IN' => 'India', 'ID' => 'Indonesia', 'IR' => 'Iran', 'IQ' => 'Iraq', 'IE' => 'Ireland', 'IL' => 'Israel', 'IT' => 'Italy',
		'JM' => 'Jamaica', 'JP' => 'Japan', 'JO' => 'Jordan', 'KZ' => 'Kazakhstan', 'KE' => 'Kenya', 'KW' => 'Kuwait', 'KG' => 'Kyrgyzstan', 'LA' => 'Laos', 'LV' => 'Latvia',
		'LB' => 'Lebanon', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MO' => 'Macao', 'MY' => 'Malaysia', 'MV' => 'Maldives', 'MT' => 'Malta', 'MU' => 'Mauritius', 'MX' => 'Mexico',
		'MD' => 'Moldova', 'MN' => 'Mongolia', 'ME' => 'Montenegro', 'MA' => 'Morocco', 'MM' => 'Myanmar', 'NP' => 'Nepal', 'NL' => 'Netherlands', 'NZ' => 'New Zealand',
		'NG' => 'Nigeria', 'MK' => 'North Macedonia', 'NO' => 'Norway', 'OM' => 'Oman', 'PK' => 'Pakistan', 'PA' => 'Panama', 'PE' => 'Peru', 'PH' => 'Philippines', 'PL' => 'Poland',
		'PT' => 'Portugal', 'QA' => 'Qatar', 'RO' => 'Romania', 'RU' => 'Russia', 'SA' => 'Saudi Arabia', 'RS' => 'Serbia', 'SG' => 'Singapore', 'SK' => 'Slovakia', 'SI' => 'Slovenia',
		'ZA' => 'South Africa', 'KR' => 'South Korea', 'ES' => 'Spain', 'LK' => 'Sri Lanka', 'SE' => 'Sweden', 'CH' => 'Switzerland', 'TW' => 'Taiwan', 'TZ' => 'Tanzania',
		'TH' => 'Thailand', 'TR' => 'Türkiye', 'UA' => 'Ukraine', 'AE' => 'United Arab Emirates', 'GB' => 'United Kingdom', 'US' => 'United States', 'UY' => 'Uruguay',
		'UZ' => 'Uzbekistan', 'VN' => 'Vietnam', 'ZW' => 'Zimbabwe',
	);
}

/** Script + data for the booking page. */
add_action( 'wp_enqueue_scripts', function () {
	if ( ! is_singular( 'gp_room' ) || ! function_exists( 'gp_booking_properties' ) ) {
		return;
	}
	wp_enqueue_script( 'gp-house-booking', GP_HOUSE_URI . '/assets/js/booking.js', array(), gp_asset_version( 'assets/js/booking.js' ), array( 'strategy' => 'defer', 'in_footer' => true ) );
	$user  = wp_get_current_user();
	$props = array();
	foreach ( gp_booking_properties() as $slug => $p ) {
		$props[] = array( 'slug' => $slug, 'label' => $p['label'], 'flow' => $p['flow'], 'url' => $p['engine_url'] );
	}
	wp_localize_script( 'gp-house-booking', 'gpBooking', array(
		'rest'       => esc_url_raw( rest_url( 'gp/v1/booking/' ) ),
		'nonce'      => wp_create_nonce( 'wp_rest' ),
		'loggedIn'   => is_user_logged_in(),
		'user'       => array(
			'first' => $user->first_name ?: ( $user->display_name ? strtok( $user->display_name, ' ' ) : '' ),
			'last'  => $user->last_name,
			'email' => $user->user_email,
			'phone' => (string) get_user_meta( $user->ID, 'gp_phone', true ),
		),
		'properties' => $props,
				'cancelHrs'  => function_exists( 'gp_booking_option' ) ? (int) gp_booking_option( 'cancel_hours', 48 ) : 48,
		'i18n'       => array(
			'searching' => __( 'Checking availability…', 'gp-house' ),
			'none'      => __( 'No rooms are available for these dates. Try different dates or another property.', 'gp-house' ),
			'error'     => __( 'We could not load availability. Please try again.', 'gp-house' ),
			'select'    => __( 'Select', 'gp-house' ),
			'selected'  => __( 'Selected', 'gp-house' ),
			'left'      => __( 'Only %d left', 'gp-house' ),
			'night'     => __( 'night', 'gp-house' ),
			'nights'    => __( 'nights', 'gp-house' ),
			'expired'   => __( 'Your hold has expired. Please choose your room again.', 'gp-house' ),
			'pay'       => __( 'Continue to payment', 'gp-house' ),
			'processing' => __( 'Please wait…', 'gp-house' ),
			'cancelled' => __( 'Payment cancelled. You can try again below.', 'gp-house' ),
			'linkText'  => __( 'Complete your booking on our secure booking page.', 'gp-house' ),
			'linkButton' => __( 'Continue to booking page', 'gp-house' ),
			'sdk'       => __( 'We could not load PayPal. Please refresh the page.', 'gp-house' ),
		),
	) );
}, 30 );

