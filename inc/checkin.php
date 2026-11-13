<?php
/**
 * Late Night Self Check-In (/self-check-in/) — Figma 1148:11140 (default), 1148:11250 (locked),
 * 1148:10926 (unlocked); mobile 1452:9683 / 1452:11947 / 1452:11153.
 *
 * The look-up is server-rendered (works without JS): ?booking=<ID>&sim=<real|unlocked|locked>.
 * Bookings come from gp_checkin_bookings(); swap the filter for the eZee reservation lookup later.
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

/** Late-night window: access details unlock at 23:00 (property time) and stay open until 06:00. */
function gp_checkin_window() {
	return apply_filters( 'gp_house_checkin_window', array( 'from' => 23, 'to' => 6, 'timezone' => 'Asia/Bangkok' ) );
}

/** Demo reservations (Figma sample data). Keyed by upper-case confirmation ID. */
function gp_checkin_bookings() {
	$villa = array(
		'property'    => 'GP House Phuket Sanctuary',
		'room_name'   => 'Cliffside Private Pool Villa',
		'check_in'    => '2026-10-14',
		'check_out'   => '2026-10-18',
		'guests'      => '2 Adults',
		'designation' => 'Villa 304 (Sunset Ridge)',
		'status'      => 'Ready & Turndown Complete',
		'pin'         => '7412#',
		'gate_code'   => '7412#',
		'wifi_name'   => 'GPHouse-Phuket-Guest',
		'wifi_pass'   => 'AndamanLotus2026',
		'host_phone'  => '+66 (0) 76 345 889',
		'nfc_room'    => 'Villa 302 (Sunset Cliffside)',
		'door'        => 'Villa Door',
		'thumb'       => 'checkin/thumb.jpg',
		'photo'       => 'checkin/room.jpg',
	);
	return apply_filters( 'gp_house_checkin_bookings', array(
		'GPH-884219' => array_merge( $villa, array( 'id' => 'GPH-884219' ) ),
		'GPH-992104' => array_merge( $villa, array( 'id' => 'GPH-992104', 'property' => 'GP House Bangkok Riverside', 'room_name' => 'Riverside Suite 408', 'designation' => 'Suite 408 (River View)', 'pin' => '2086#', 'gate_code' => '2086#', 'wifi_name' => 'GPHouse-Bangkok-Guest', 'wifi_pass' => 'ChaoPhraya2026', 'host_phone' => '+66 (0) 2 345 8899', 'nfc_room' => 'Suite 408 (River View)', 'door' => 'Suite Door', 'check_in' => '2026-10-14', 'check_out' => '2026-10-16' ) ),
		'GPH-341908' => array_merge( $villa, array( 'id' => 'GPH-341908', 'property' => 'GP House Krabi Retreat', 'room_name' => 'Karst Suite', 'designation' => 'Karst Suite 12 (Limestone View)', 'pin' => '5530#', 'gate_code' => '5530#', 'wifi_name' => 'GPHouse-Krabi-Guest', 'wifi_pass' => 'KarstOrchid2026', 'host_phone' => '+66 (0) 75 612 340', 'nfc_room' => 'Karst Suite 12 (Limestone View)', 'door' => 'Suite Door', 'check_in' => '2026-10-15', 'check_out' => '2026-10-19' ) ),
	) );
}

/** Quick-pick chips under the search field. */
function gp_checkin_samples() {
	return apply_filters( 'gp_house_checkin_samples', array(
		array( 'label' => "Today's Arrival: Suite 408 (GPH-992104)", 'id' => 'GPH-992104' ),
		array( 'label' => 'Phuket Pool Villa (GPH-884219)', 'id' => 'GPH-884219' ),
		array( 'label' => 'Krabi Karst Suite (GPH-341908)', 'id' => 'GPH-341908' ),
	) );
}

/** The three highlights under the look-up. */
function gp_checkin_perks() {
	return apply_filters( 'gp_house_checkin_perks', array(
		array( 'icon' => 'truck-navy', 'title' => 'Late Airport Transfer', 'text' => 'Your Driver Is Tracked Automatically By Flight Number, Even With Delays.' ),
		array( 'icon' => 'phone-navy', 'title' => '24/7 Night Concierge', 'text' => 'Night Butler Is On Duty. Press The Intercom Or Call For Assistance.' ),
		array( 'icon' => 'sparkle-navy', 'title' => 'Next Morning Breakfast', 'text' => 'Sleep In. Breakfast Is Served Until 11:30 AM Or Delivered To Your Bed.' ),
	) );
}

/** The three progress steps above the look-up (Figma update 2026-11-13). */
function gp_checkin_steps() {
	return apply_filters( 'gp_house_checkin_steps', array(
		array( 'label' => __( 'Find & Check', 'gp-house' ), 'icon' => 'search' ),
		array( 'label' => __( 'Verify', 'gp-house' ), 'icon' => 'shield' ),
		array( 'label' => __( 'Get Access', 'gp-house' ), 'icon' => 'key' ),
	) );
}

/** 16px stroke icons for the progress dots (inline so they take the dot's text colour). */
function gp_checkin_step_icon( $name ) {
	$paths = array(
		'search' => '<circle cx="7" cy="7" r="4.25"/><path d="m10.2 10.2 3.3 3.3"/>',
		'shield' => '<path d="M8 1.9 3.2 3.7v3.8c0 3 2 5.4 4.8 6.6 2.8-1.2 4.8-3.6 4.8-6.6V3.7L8 1.9Z"/><path d="m5.9 8 1.5 1.5 2.8-2.9"/>',
		'key'    => '<circle cx="4.4" cy="8" r="2.4"/><path d="M6.8 8h7.4M11.2 8v2.2M13.4 8v1.8"/>',
		'check'  => '<path d="m3.2 8.3 3.1 3.1 6.5-6.6"/>',
	);
	return '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . ( $paths[ $name ] ?? '' ) . '</svg>';
}

/** Simulation modes (?sim=); the Figma tabs were replaced by the progress steps, the modes stay for demos. */
function gp_checkin_modes() {
	return array(
		'real'     => array( 'label' => __( 'Real Clock', 'gp-house' ), 'icon' => 'clock-navy' ),
		'unlocked' => array( 'label' => __( '11:15 PM — Unlocked', 'gp-house' ), 'icon' => 'lock-open-navy' ),
		'locked'   => array( 'label' => __( '8:30 PM — Locked', 'gp-house' ), 'icon' => 'lock-navy' ),
	);
}

/**
 * Resolve the access state for a simulation mode.
 *
 * @return array{unlocked:bool,note:string,remaining:string}
 */
function gp_checkin_state( $mode ) {
	$w = gp_checkin_window();
	if ( 'unlocked' === $mode ) {
		return array( 'unlocked' => true, 'note' => __( 'Simulated Late-Night Arrival (11:15 PM)', 'gp-house' ), 'remaining' => '' );
	}
	if ( 'locked' === $mode ) {
		return array( 'unlocked' => false, 'note' => __( 'Simulated Daytime Arrival (8:30 PM) — Access Details Unlock At 11:00 PM', 'gp-house' ), 'remaining' => '2h 30m' );
	}
	try {
		$now = new DateTimeImmutable( 'now', new DateTimeZone( $w['timezone'] ) );
	} catch ( Exception $e ) {
		$now = new DateTimeImmutable( 'now' );
	}
	$hour     = (int) $now->format( 'G' );
	$unlocked = $hour >= $w['from'] || $hour < $w['to'];
	$left     = '';
	if ( ! $unlocked ) {
		$open = $now->setTime( $w['from'], 0 );
		$mins = max( 0, (int) round( ( $open->getTimestamp() - $now->getTimestamp() ) / 60 ) );
		$left = floor( $mins / 60 ) . 'h ' . str_pad( (string) ( $mins % 60 ), 2, '0', STR_PAD_LEFT ) . 'm';
	}
	/* translators: %s: current property time */
	$note = $unlocked
		? sprintf( __( 'Live Late-Night Arrival (%s)', 'gp-house' ), $now->format( 'g:i A' ) )
		: sprintf( __( 'Live Property Time (%s) — Access Details Unlock At 11:00 PM', 'gp-house' ), $now->format( 'g:i A' ) );
	return array( 'unlocked' => $unlocked, 'note' => $note, 'remaining' => $left );
}

/** Sanitised query: booking ID + simulation mode. */
function gp_checkin_request() {
	// phpcs:disable WordPress.Security.NonceVerification -- read-only look-up, no state change.
	$id   = isset( $_GET['booking'] ) ? strtoupper( preg_replace( '/[^A-Za-z0-9\-]/', '', wp_unslash( $_GET['booking'] ) ) ) : '';
	$mode = isset( $_GET['sim'] ) ? sanitize_key( wp_unslash( $_GET['sim'] ) ) : 'real';
	// phpcs:enable
	if ( ! isset( gp_checkin_modes()[ $mode ] ) ) {
		$mode = 'real';
	}
	return array( 'id' => $id, 'mode' => $mode );
}

// Look-ups are personal: never cache or index.
add_action( 'template_redirect', function () {
	if ( is_page( 'self-check-in' ) ) {
		nocache_headers();
	}
} );
add_filter( 'wp_robots', function ( $robots ) {
	if ( is_page( 'self-check-in' ) ) {
		$robots['noindex']  = true;
		$robots['nofollow'] = true;
	}
	return $robots;
} );
