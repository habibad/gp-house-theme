<?php
/**
 * Menus: links come from Appearance → Menus; until a menu is assigned the
 * design's default links are used.
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

function gp_default_menu_links() {
	$l = function ( $title, $path ) {
		return array( 'title' => $title, 'url' => home_url( $path ) );
	};
	return array(
		'primary'        => array( $l( 'Stays', '/stays/' ), $l( 'Destinations', '/destinations/' ), $l( 'Offers', '/offers/' ), $l( 'Investor Relations', '/investor-relations/' ) ),
		'others'         => array( $l( 'Self Check-In', '/self-check-in/' ), $l( 'Club', '/club/' ), $l( 'Journal', '/journal/' ), $l( 'About', '/about/' ) ),
		'footer_explore' => array( $l( 'Stays & Villas', '/stays/' ), $l( 'Destinations', '/destinations/' ), $l( 'Curated Experiences', '/experiences/' ), $l( 'Special Offers & Packages', '/offers/' ) ),
		'footer_about'   => array( $l( 'GP House Club', '/club/' ), $l( 'Journal / Stories', '/journal/' ), $l( 'Our Story & Heritage', '/about/' ), $l( 'Sustainability & Craft', '/sustainability/' ) ),
		'footer_support' => array( $l( 'Contact & Concierge', '/contact/' ), $l( 'Booking Policies', '/booking-policies/' ), $l( 'Terms of Service', '/terms/' ), $l( 'Interactive Sitemap', '/sitemap/' ) ),
	);
}

/** @return array<int, array{title: string, url: string, current: bool}> */
function gp_menu_links( $location ) {
	$links     = array();
	$locations = get_nav_menu_locations();
	if ( ! empty( $locations[ $location ] ) ) {
		$items = wp_get_nav_menu_items( $locations[ $location ] );
		foreach ( (array) $items as $item ) {
			if ( ! $item->menu_item_parent ) {
				$links[] = array( 'title' => $item->title, 'url' => $item->url );
			}
		}
	}
	if ( ! $links ) {
		$links = gp_default_menu_links()[ $location ] ?? array();
	}
	$path = strtok( wp_unslash( $_SERVER['REQUEST_URI'] ?? '/' ), '?' ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	$here = trailingslashit( set_url_scheme( 'http://' . wp_unslash( $_SERVER['HTTP_HOST'] ?? '' ) . $path ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	foreach ( $links as &$link ) {
		$link['current'] = trailingslashit( $link['url'] ) === $here;
	}
	return $links;
}
