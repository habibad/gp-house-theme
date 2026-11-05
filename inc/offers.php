<?php
/**
 * Offers page (Figma "Hero /Offers", 1148:7363; mobile 1438:10794).
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

/** Offer type filter tabs (taxonomy gp_offer_type slugs). */
function gp_offer_types() {
	return array(
		'room-only' => __( 'Room Only', 'gp-house' ),
		'packages'  => __( 'Packages', 'gp-house' ),
	);
}

/** Figma demo offers. Images live in assets/img/offers/. */
function gp_demo_offers() {
	return array(
		array(
			'slug'         => 'andaman-escape-stay-4-pay-3',
			'type'         => 'packages',
			'code'         => 'ISLANDRETREAT',
			'title'        => 'Andaman Escape: Stay 4, Pay 3',
			'excerpt'      => 'Book four nights in Phuket or Krabi and enjoy the fourth night complimentary, with breakfast and airport transfers.',
			'highlights'   => array( '4th Night Free on Pavilions & Villas', 'Daily Breakfast for Two', 'Private Airport Transfers', '15% Off Botanical Spa', 'Guaranteed 2 PM Checkout' ),
			'save'         => 'Save 25%',
			'image'        => 'offers/island-retreat.jpg',
			'image_mobile' => 'offers/island-retreat-mobile.jpg',
		),
		array(
			'slug'         => 'advance-discovery-privilege',
			'type'         => 'room-only',
			'code'         => 'EARLYBIRD20',
			'title'        => 'Advance Discovery Privilege',
			'excerpt'      => 'Book 30 days ahead and enjoy exclusive best-rate savings across Bangkok, Krabi, and Phuket.',
			'highlights'   => array( '20% Off Best Available Flexible Rate', 'Welcome Tropical Fruit Basket & Chilled Coconut', 'Premium High-Speed Wi-Fi Throughout', 'Flexible Date Changes up to 14 Days Before Arrival' ),
			'save'         => 'Save 20%',
			'image'        => 'offers/early-bird.jpg',
			'image_mobile' => 'offers/early-bird-mobile.jpg',
		),
		array(
			'slug'         => 'mindful-holistic-wellness-immersion',
			'type'         => 'packages',
			'code'         => 'WELLNESSZEN',
			'title'        => 'Mindful Holistic Wellness Immersion',
			'excerpt'      => 'Rebalance your mind and nervous system with private sound baths, daily elixirs, and tailored herbal treatments.',
			'highlights'   => array( 'Daily 90-Minute Botanical Spa Treatment', 'Personalized Naturopathic Consultation', 'Daily Three-Course Wellness Lunch', 'Morning Private Yoga & Pranayama', 'GP House Botanical Wellness Gift Set' ),
			'save'         => 'Save 15%',
			'image'        => 'offers/wellness-zen.jpg',
			'image_mobile' => 'offers/wellness-zen-mobile.jpg',
		),
		array(
			'slug'         => 'river-escape',
			'type'         => 'room-only',
			'code'         => 'RIVERESCAPE',
			// Figma repeats the first offer's title on this card.
			'title'        => 'Andaman Escape: Stay 4, Pay 3',
			'excerpt'      => 'Experience Bangkok by water with unlimited river shuttles, rooftop sunset cocktails, and early check-in.',
			'highlights'   => array( 'Complimentary River View Upgrade', 'Sunset Cocktails for Two', 'Unlimited Private Riverboat Transfers', 'Guaranteed 12:00 PM Early Check-In' ),
			'save'         => 'Save 15%',
			'image'        => 'offers/river-escape.jpg',
			'image_mobile' => 'offers/river-escape-mobile.jpg',
		),
	);
}

/** Booking link for an offer: the Stays page with the promo code pre-filled. */
function gp_offer_book_url( $code, $custom = '' ) {
	if ( $custom ) {
		return $custom;
	}
	return add_query_arg( 'promo', rawurlencode( $code ), get_post_type_archive_link( 'gp_stay' ) ?: home_url( '/stays/' ) );
}

/** Offers in one shape, from the plugin's gp_offer posts or the Figma demo. */
function gp_get_offers() {
	$labels = gp_offer_types();

	if ( gp_has_posts( 'gp_offer' ) ) {
		$posts = get_posts( array( 'post_type' => 'gp_offer', 'posts_per_page' => -1, 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'ASC' ) ) );
		return array_map( function ( $p ) use ( $labels ) {
			$terms  = get_the_terms( $p, 'gp_offer_type' );
			$type   = $terms && ! is_wp_error( $terms ) ? $terms[0] : null;
			$code   = get_post_meta( $p->ID, 'gp_offer_code', true );
			$mobile = get_post_meta( $p->ID, 'gp_offer_mobile_id', true );
			return array(
				'id'           => (string) $p->ID,
				'type'         => $type ? $type->slug : '',
				'type_label'   => $type ? $type->name : '',
				'code'         => $code,
				'title'        => get_the_title( $p ),
				'excerpt'      => get_the_excerpt( $p ),
				'highlights'   => array_slice( (array) get_post_meta( $p->ID, 'gp_offer_highlights', true ), 0, 5 ),
				'save'         => get_post_meta( $p->ID, 'gp_offer_save', true ),
				'image'        => get_the_post_thumbnail_url( $p, 'large' ) ?: '',
				'image_mobile' => $mobile ? gp_media_url( $mobile, 'large' ) : '',
				'url'          => gp_offer_book_url( $code, get_post_meta( $p->ID, 'gp_offer_url', true ) ),
			);
		}, $posts );
	}

	return array_map( function ( $o ) use ( $labels ) {
		return array(
			'id'           => $o['slug'],
			'type'         => $o['type'],
			'type_label'   => $labels[ $o['type'] ] ?? '',
			'code'         => $o['code'],
			'title'        => $o['title'],
			'excerpt'      => $o['excerpt'],
			'highlights'   => $o['highlights'],
			'save'         => $o['save'],
			'image'        => gp_img( $o['image'] ),
			'image_mobile' => gp_img( $o['image_mobile'] ),
			'url'          => gp_offer_book_url( $o['code'] ),
		);
	}, gp_demo_offers() );
}
