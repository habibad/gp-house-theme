<?php
/**
 * Member area (/profile/…) — Figma "Profile" frames 1148:8570–10146, mobile 1454:*.
 *
 * Routes (one WP page "profile" + a rewrite for the sections):
 *   /profile/              Account Overview
 *   /profile/bookings/     My Booking
 *   /profile/points/       Points & Privileges
 *   /profile/preferences/  Profile & Preferences
 *   /profile/saved/        Saved Stays
 *
 * Data comes from gp_profile_user() and friends. Until the sign-in screens exist
 * the area shows the Figma sample member ("Kite") to visitors — turn that off with
 * add_filter( 'gp_house_profile_demo', '__return_false' ) and visitors are sent to the login.
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

/* Routing ----------------------------------------------------------------- */

add_action( 'init', function () {
	add_rewrite_rule( '^profile/(bookings|points|preferences|saved)/?$', 'index.php?pagename=profile&gp_section=$matches[1]', 'top' );
	if ( '2' !== get_option( 'gp_house_profile_rewrite' ) ) {
		flush_rewrite_rules( false );
		update_option( 'gp_house_profile_rewrite', '2' );
	}
} );
add_filter( 'query_vars', function ( $vars ) {
	$vars[] = 'gp_section';
	return $vars;
} );

/** Key of the section being shown ('' = overview). */
function gp_profile_section() {
	$s = sanitize_key( (string) get_query_var( 'gp_section' ) );
	return isset( gp_profile_sections()[ $s ] ) ? $s : '';
}

/** Whether the visitor sees the sample member instead of a real account. */
function gp_profile_is_demo() {
	// Real sign-in exists now; define( 'GP_HOUSE_PROFILE_DEMO', true ) in wp-config.php to preview the sample member.
	return ! is_user_logged_in() && (bool) apply_filters( 'gp_house_profile_demo', defined( 'GP_HOUSE_PROFILE_DEMO' ) && GP_HOUSE_PROFILE_DEMO );
}

/** Signed in, or previewing the sample member on a profile page. */
function gp_profile_signed_in() {
	return is_user_logged_in() || ( gp_profile_is_demo() && is_page( 'profile' ) );
}

// Not signed in and no demo → login first.
add_action( 'template_redirect', function () {
	if ( is_page( 'profile' ) && ! is_user_logged_in() && ! gp_profile_is_demo() ) {
		$here = ( is_ssl() ? 'https://' : 'http://' ) . ( isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '' ) . ( isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '/' ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		wp_safe_redirect( wp_login_url( $here ) );
		exit;
	}
	if ( is_page( 'profile' ) ) {
		nocache_headers();
	}
} );
add_filter( 'wp_robots', function ( $robots ) {
	if ( is_page( 'profile' ) ) {
		$robots['noindex'] = true;
	}
	return $robots;
} );

/* Data -------------------------------------------------------------------- */

/** Current member. */
function gp_profile_user() {
	static $user = null;
	if ( null !== $user ) {
		return $user;
	}
	$user = array(
		'id'         => 0,
		'first_name' => 'Kite',
		'name'       => 'Kite Itohara',
		'email'      => 'Example@Mail.Com',
		'login_mail' => 'kitedesign.ito@gmail.com',
		'tier'       => 'Gold',
		'avatar'     => gp_img( 'profile/avatar.jpg' ),
		'points'     => 500,
		'earned'     => 2450,
		'to_next'    => 5150,
		'next_tier'  => 'Gold',
		'progress'   => 48,
		'phone'      => '',
		'prefs'      => array(
			'bed'      => 'king',
			'pillow'   => 'feather',
			'floor'    => 'elevator',
			'dietary'  => 'Pescatarian, Gluten-Conscious',
			'requests' => 'Prefers quiet high-floor corner suite facing ocean or river.',
		),
		'is_demo'    => true,
	);
	if ( is_user_logged_in() ) {
		$wp         = wp_get_current_user();
		$meta       = get_user_meta( $wp->ID );
		$m          = function ( $k, $d = '' ) use ( $meta ) {
			return isset( $meta[ $k ][0] ) && '' !== $meta[ $k ][0] ? $meta[ $k ][0] : $d;
		};
		$user       = array_merge( $user, array(
			'id'         => $wp->ID,
			'first_name' => $wp->first_name ? $wp->first_name : $wp->display_name,
			'name'       => $wp->display_name,
			'email'      => $wp->user_email,
			'login_mail' => $wp->user_email,
			'tier'       => $m( 'gp_tier', 'Silver' ),
			'avatar'     => get_avatar_url( $wp->ID, array( 'size' => 88 ) ),
			'points'     => (int) $m( 'gp_points', 0 ),
			'earned'     => (int) $m( 'gp_points_earned', 0 ),
			'phone'      => $m( 'gp_phone' ),
			'is_demo'    => false,
		) );
		foreach ( array( 'bed', 'pillow', 'floor', 'dietary', 'requests' ) as $k ) {
			$user['prefs'][ $k ] = $m( 'gp_pref_' . $k, '' );
		}
	}
	return apply_filters( 'gp_house_profile_user', $user );
}

/** Sample reservations (replace with the eZee reservation list). */
function gp_profile_bookings() {
	if ( ! gp_profile_is_demo() ) {
		return apply_filters( 'gp_house_profile_bookings', array() );
	}
	$base = array(
		'rooms'     => 1,
		'guests'    => '2 Guests',
		'check_in'  => '2026-10-14',
		'check_out' => '2026-10-18',
		'nights'    => 4,
		'phone'     => '+66 76 345 889',
		'email'     => 'Concierge@Gphousehotels.Com',
		'perks'     => array(
			'Daily Artisanal Organic Thai & Continental Breakfast For Two',
			'Traditional Thai Botanical Cold Towel And Lemongrass Welcome Ritual',
			'High-Speed Optical Fiber WiFi Across Villas And Sanctuaries',
			'GP House Club 2:00 PM Late Check-Out Privilege (Subject To Availability)',
		),
	);
	return apply_filters( 'gp_house_profile_bookings', array(
		array_merge( $base, array( 'id' => 'GPH-884219', 'title' => 'GP House Phuket Sanctuary', 'status' => 'confirmed', 'dates' => 'Oct 14–18 · 4N', 'total' => '$2,560', 'code' => 'ISLANDRETREAT', 'image' => 'profile/booking-1.jpg', 'url' => home_url( '/destinations/phuket/' ), 'concierge' => 'GP House Phuket', 'accommodation' => '$2,560' ) ),
		array_merge( $base, array( 'id' => 'GPH-771530', 'title' => 'GP House Bangkok Riverside', 'status' => 'completed', 'dates' => 'Jun 12–15 · 3N', 'total' => '$1,470', 'code' => 'DIRECTMEMBER', 'image' => 'profile/booking-2.jpg', 'url' => home_url( '/destinations/bangkok/' ), 'check_in' => '2026-06-12', 'check_out' => '2026-06-15', 'nights' => 3, 'concierge' => 'GP House Bangkok', 'accommodation' => '$1,470' ) ),
		array_merge( $base, array( 'id' => 'GPH-341908', 'title' => 'GP House Krabi Karst Reserve', 'status' => 'completed', 'dates' => 'Apr 10–14 · 4N', 'total' => '$1,280', 'code' => 'EARLYBIRD20', 'image' => 'profile/booking-3.jpg', 'url' => home_url( '/destinations/krabi/' ), 'check_in' => '2026-04-10', 'check_out' => '2026-04-14', 'concierge' => 'GP House Krabi', 'accommodation' => '$1,280' ) ),
	) );
}

/** Reservation status → label. */
function gp_profile_status_label( $status ) {
	$labels = array( 'confirmed' => __( 'Confirmed', 'gp-house' ), 'completed' => __( 'Completed', 'gp-house' ), 'cancelled' => __( 'Cancelled', 'gp-house' ) );
	return $labels[ $status ] ?? ucfirst( $status );
}

/** Saved stays (the first four stays in the demo). */
function gp_profile_saved() {
	if ( ! gp_profile_is_demo() ) {
		return apply_filters( 'gp_house_profile_saved', array() );
	}
	return apply_filters( 'gp_house_profile_saved', array_slice( gp_get_stays(), 0, 4 ) );
}

/** Rewards that can be redeemed with points. */
function gp_profile_rewards() {
	return apply_filters( 'gp_house_profile_rewards', array(
		array( 'title' => '60-Minute Thai Herbal Poultice Spa|Massage', 'text' => 'Greet Dawn In Krabi’s Mangroves, Spotting Kingfishers Among Limestone Karsts.', 'points' => 2000, 'image' => 'profile/reward-spa.jpg' ),
		array( 'title' => '$100 Oceanfront Dining|Credit', 'text' => 'Enjoy An Elevated Dining Experience At Our Oceanfront Restaurants And Wine Cellars.', 'points' => 3000, 'image' => 'profile/reward-dining.jpg' ),
		array( 'title' => 'Sunset Wooden Catamaran|Cruise For Two', 'text' => 'A Private Bay Cruise In Phuket Or Krabi, Accompanied By Prosecco.', 'points' => 4500, 'image' => 'profile/reward-cruise.jpg' ),
		array( 'title' => 'Complimentary Mercedes-|Benz Airport Transfer', 'text' => 'One-Way Luxury Airport Transfer To Or From Any GP House Resort.', 'points' => 1500, 'image' => 'profile/reward-transfer.jpg' ),
	) );
}

/** Points ledger. */
function gp_profile_history() {
	if ( ! gp_profile_is_demo() ) {
		return apply_filters( 'gp_house_profile_history', array() );
	}
	return apply_filters( 'gp_house_profile_history', array(
		array( 'date' => 'Aug 28, 2026', 'text' => 'Stay At GP House Krabi Bay Reserve (3 Nights)', 'points' => 1450 ),
		array( 'date' => 'Aug 29, 2026', 'text' => 'Direct Booking Welcome Bonus', 'points' => 1000 ),
		array( 'date' => 'Jan 12, 2026', 'text' => 'Club Account Activation Bonus', 'points' => 500 ),
	) );
}

/** Option lists for the room preferences. */
function gp_profile_pref_options() {
	return array(
		'bed'    => array( 'label' => __( 'Preferred Bed', 'gp-house' ), 'icon' => 'bed-grey', 'options' => array( 'king' => 'King Bed', 'twin' => 'Twin Beds' ) ),
		'pillow' => array( 'label' => __( 'Pillow Style', 'gp-house' ), 'icon' => 'pillow-grey', 'options' => array( 'goose' => 'Firm Goose Down', 'feather' => 'Soft Feather', 'buckwheat' => 'Organic Buckwheat', 'foam' => 'Hypoallergenic Foam' ) ),
		'floor'  => array( 'label' => __( 'Floor Preference', 'gp-house' ), 'icon' => 'floor-grey', 'options' => array( 'high' => 'High Floor', 'ground' => 'Ground Floor Garden', 'elevator' => 'Near Elevator', 'quiet' => 'Quiet Corner Wing' ) ),
	);
}

/** Sidebar / account-menu items. */
function gp_profile_sections() {
	$base = home_url( '/profile/' );
	return array(
		''            => array( 'label' => __( 'Account Overview', 'gp-house' ), 'icon' => 'user-navy', 'url' => $base ),
		'bookings'    => array( 'label' => __( 'My Booking', 'gp-house' ), 'icon' => 'calendar-grid-navy', 'url' => $base . 'bookings/' ),
		'points'      => array( 'label' => __( 'Points & Privileges', 'gp-house' ), 'icon' => 'award-navy', 'url' => $base . 'points/' ),
		'preferences' => array( 'label' => __( 'Profile & Preferences', 'gp-house' ), 'icon' => 'gear-navy', 'url' => $base . 'preferences/' ),
		'saved'       => array( 'label' => __( 'Saved Stays', 'gp-house' ), 'icon' => 'heart-navy', 'url' => $base . 'saved/' ),
	);
}

/** Counts shown in brackets next to the menu entries. */
function gp_profile_counts() {
	return array( 'bookings' => count( gp_profile_bookings() ), 'saved' => count( gp_profile_saved() ) );
}

/** Log-out link (back to the home page). */
function gp_profile_logout_url() {
	if ( ! is_user_logged_in() ) {
		return home_url( '/' );
	}
	return function_exists( 'gp_core_logout_url' ) ? gp_core_logout_url( home_url( '/' ) ) : wp_logout_url( home_url( '/' ) );
}

/* Saving preferences ------------------------------------------------------- */

add_action( 'admin_post_gp_profile_save', function () {
	if ( ! isset( $_POST['gp_profile_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gp_profile_nonce'] ) ), 'gp_profile_save' ) ) {
		wp_die( esc_html__( 'The link expired. Please go back and try again.', 'gp-house' ), '', array( 'response' => 403 ) );
	}
	$back = home_url( '/profile/preferences/' );
	$uid  = get_current_user_id();
	if ( ! $uid ) {
		wp_safe_redirect( add_query_arg( 'saved', 'login', $back ) );
		exit;
	}
	$opts = gp_profile_pref_options();
	foreach ( $opts as $key => $def ) {
		$val = isset( $_POST[ 'pref_' . $key ] ) ? sanitize_key( wp_unslash( $_POST[ 'pref_' . $key ] ) ) : '';
		if ( isset( $def['options'][ $val ] ) ) {
			update_user_meta( $uid, 'gp_pref_' . $key, $val );
		}
	}
	update_user_meta( $uid, 'gp_pref_dietary', isset( $_POST['pref_dietary'] ) ? sanitize_text_field( wp_unslash( $_POST['pref_dietary'] ) ) : '' );
	update_user_meta( $uid, 'gp_pref_requests', isset( $_POST['pref_requests'] ) ? sanitize_textarea_field( wp_unslash( $_POST['pref_requests'] ) ) : '' );
	update_user_meta( $uid, 'gp_phone', isset( $_POST['phone'] ) ? preg_replace( '/[^\d+\s\-()]/', '', wp_unslash( $_POST['phone'] ) ) : '' );
	if ( ! empty( $_POST['full_name'] ) ) {
		wp_update_user( array( 'ID' => $uid, 'display_name' => sanitize_text_field( wp_unslash( $_POST['full_name'] ) ) ) );
	}
	wp_safe_redirect( add_query_arg( 'saved', '1', $back ) );
	exit;
} );
add_action( 'admin_post_nopriv_gp_profile_save', function () {
	wp_safe_redirect( add_query_arg( 'saved', 'login', home_url( '/profile/preferences/' ) ) );
	exit;
} );

/** Body class for the member-area section (gp-pf-<section>) so the CSS can tune each state. */
add_filter( 'body_class', function ( $classes ) {
	if ( is_page( 'profile' ) ) {
		$classes[] = 'gp-pf-' . ( gp_profile_section() ?: 'overview' );
	}
	return $classes;
} );
