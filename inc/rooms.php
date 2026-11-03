<?php
/**
 * Rooms & suites (Stays page). Demo content from Figma "Home / Stays" (1148:7257)
 * plus the data getter and the filter options.
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

/** Stays page filter options. Keys are taxonomy term slugs. */
function gp_room_filter_options() {
	return array(
		'room' => array(
			''          => __( 'All Room Types', 'gp-house' ),
			'deluxe'    => __( 'Deluxe Rooms', 'gp-house' ),
			'suite'     => __( 'Suites', 'gp-house' ),
			'villa'     => __( 'Pool Villas', 'gp-house' ),
			'residence' => __( 'Family Residences', 'gp-house' ),
		),
		'bed'  => array(
			''     => __( 'All Bed Types', 'gp-house' ),
			'king' => __( 'King Bed', 'gp-house' ),
			'twin' => __( 'Twin Beds', 'gp-house' ),
		),
		'tags' => array(
			'pool'      => __( 'Pool', 'gp-house' ),
			'breakfast' => __( 'Breakfast', 'gp-house' ),
			'spa'       => __( 'Spa', 'gp-house' ),
			'balcony'   => __( 'Balcony', 'gp-house' ),
		),
		'sort' => array(
			''           => __( 'Recommended', 'gp-house' ),
			'price-asc'  => __( 'Price: Low to High', 'gp-house' ),
			'price-desc' => __( 'Price: High to Low', 'gp-house' ),
			'size-desc'  => __( 'Largest First', 'gp-house' ),
		),
	);
}

/**
 * Figma demo rooms. Images live in assets/img/stays/<slug>-<n>.jpg; `gallery`
 * lists the image numbers in strip order and `active` is the highlighted slot.
 */
function gp_demo_rooms() {
	return array(
		array(
			'slug'        => 'chao-phraya-river-deluxe',
			'title'       => 'Chao Phraya River Deluxe',
			'destination' => 'bangkok',
			'type'        => 'deluxe',
			'beds'        => array( 'king' ),
			'tags'        => array( 'breakfast' ),
			'excerpt'     => 'Gaze across the historic river through sealed windows, with timber walls inspired by classic Thai homes.',
			'highlights'  => array( 'River-facing Bay Lounge', 'Marble Double Vanity', 'High-Speed Mesh Wi-Fi', 'Complimentary Riverboat Pass' ),
			'area'        => '52 sqm',
			'guests'      => 'Max 2 Adults',
			'max_guests'  => 2,
			'bed'         => 'King',
			'view'        => 'Historic Chao Phraya River',
			'price'       => 260,
			'gallery'     => array( 1, 2, 3, 4, 5, 6, 7 ),
			'active'      => 3,
			// Room page strip in Figma: 8 photos, the 5th highlighted.
			'detail_gallery' => array( 'd1', 'd2', 'd3', 'd3', 4, 4, 4, 4 ),
			'detail_active'  => 4,
		),
		array(
			'slug'        => 'old-town-heritage-suite',
			'title'       => 'Old Town Heritage Suite',
			'destination' => 'bangkok',
			'type'        => 'suite',
			'beds'        => array( 'king' ),
			'tags'        => array( 'breakfast', 'balcony' ),
			'excerpt'     => 'Expansive residence with a dining salon, private library, and river-facing teak balcony, plus daily chef-prepared breakfast.',
			'highlights'  => array( 'Private River Balcony', 'Separate Living & Dining Salon', 'Free-Standing Copper Soaking Tub', 'Daily In-Suite High Tea' ),
			'area'        => '90 sqm',
			'guests'      => 'Max 3 Adults',
			'max_guests'  => 3,
			'bed'         => 'King',
			'view'        => 'Riverfront & Old Town Temples',
			'price'       => 460,
			'gallery'     => array( 1, 2, 2, 3, 4, 5, 6 ),
			'active'      => 5,
		),
		array(
			'slug'        => 'ocean-view-deluxe-pavilion',
			'title'       => 'Ocean View Deluxe Pavilion',
			'destination' => 'phuket',
			'type'        => 'deluxe',
			'beds'        => array( 'king' ),
			'tags'        => array( 'balcony' ),
			'excerpt'     => 'Wake up to sea breezes on your teak balcony, with floor-to-ceiling glass doors and a terrazzo soaking tub overlooking the waves.',
			'highlights'  => array( 'Private Teak Balcony', 'Terrazzo Soaking Tub', 'Nespresso Coffee Machine', 'Dyson Supersonic Hairdryer' ),
			'area'        => '58 sqm',
			'guests'      => 'Max 2 Adults',
			'max_guests'  => 2,
			'bed'         => 'King',
			'view'        => 'Unobstructed Andaman Sea',
			'price'       => 380,
			'gallery'     => array( 1, 2, 3, 4, 5, 6, 7 ),
			'active'      => 1,
		),
		array(
			'slug'        => 'cliffside-private-pool-villa',
			'title'       => 'Cliffside Private Pool Villa',
			'destination' => 'phuket',
			'type'        => 'villa',
			'beds'        => array( 'king' ),
			'tags'        => array( 'pool', 'spa' ),
			'excerpt'     => 'The pinnacle of coastal seclusion, with a private saltwater infinity pool, ocean views, and outdoor daybed.',
			'highlights'  => array( '12m Private Saltwater Pool', 'Sunset Daybed Sala', 'Dedicated Villa Host', 'Daily In-Villa Sunset Cocktails' ),
			'area'        => '115 sqm',
			'guests'      => 'Max 3 Adults',
			'max_guests'  => 3,
			'bed'         => 'King',
			'view'        => '180° Sunset Panoramic Ocean',
			'price'       => 640,
			'gallery'     => array( 1, 2, 3, 3, 4, 5, 6 ),
			'active'      => 4,
		),
		array(
			'slug'        => 'grand-family-coastal-residence',
			'title'       => 'Grand Family Coastal Residence',
			'destination' => 'phuket',
			'type'        => 'residence',
			'beds'        => array( 'king', 'twin' ),
			'tags'        => array( 'breakfast' ),
			'excerpt'     => 'Spacious two-bedroom layout with a dining lounge, private garden terrace, and interconnected king beds.',
			'highlights'  => array( 'Two En-Suite Bathrooms', 'Private Teak Dining Lounge', 'Wine Cellar Cabinet', 'Child-Friendly Amenities' ),
			'area'        => '160 sqm',
			'guests'      => 'Max 5 Adults',
			'max_guests'  => 5,
			'bed'         => 'King or Twin',
			'view'        => 'Lush Gardens & Coral Bay',
			'price'       => 890,
			'gallery'     => array( 1, 2, 3, 4, 3, 5, 6 ),
			'active'      => 3,
		),
		array(
			'slug'        => 'karst-view-garden-suite',
			'title'       => 'Karst View Garden Suite',
			'destination' => 'krabi',
			'type'        => 'suite',
			'beds'        => array( 'king' ),
			'tags'        => array( 'spa' ),
			'excerpt'     => 'Blends into the jungle canopy with bamboo screens, high vaulted ceilings, and a shaded daybed overlooking Krabi’s limestone pillars.',
			'highlights'  => array( 'Outdoor Daybed Sala', 'Handmade Teak Furnishings', 'Locally Sourced Organic Coffee', 'Rainfall Shower' ),
			'area'        => '65 sqm',
			'guests'      => 'Max 2 Adults',
			'max_guests'  => 2,
			'bed'         => 'King',
			'view'        => 'Tropical Forest & Limestone Formations',
			'price'       => 1280,
			'gallery'     => array( 1, 2, 3, 4, 1, 5, 6 ),
			'active'      => 4,
		),
		array(
			'slug'        => 'over-water-lagoon-pool-villa',
			'title'       => 'Over-Water Lagoon Pool Villa',
			'destination' => 'krabi',
			'type'        => 'villa',
			'beds'        => array( 'king' ),
			'tags'        => array( 'pool', 'spa' ),
			'excerpt'     => 'Perched over gentle lagoon waters with a plunge pool, timber deck, and glass floor accents overlooking marine life.',
			'highlights'  => array( 'Private Infinity Plunge Pool', 'Wraparound Deck', 'Direct Lagoon Kayak Dock', 'Herbal Bath Salts' ),
			'area'        => '98 sqm',
			'guests'      => 'Max 3 Adults',
			'max_guests'  => 3,
			'bed'         => 'King',
			'view'        => 'Tranquil Lagoon & Sunset Karsts',
			'price'       => 540,
			'gallery'     => array( 1, 2, 1, 3, 4, 5, 6 ),
			'active'      => 1,
		),
	);
}

/** Convert a demo room into the shared room shape. */
function gp_room_from_demo( array $r ) {
	$dest   = wp_list_filter( gp_demo_content()['destinations'], array( 'slug' => $r['destination'] ) );
	$img    = function ( $n ) use ( $r ) {
		return array(
			'full'  => gp_img( "stays/{$r['slug']}-{$n}.jpg" ),
			'thumb' => gp_img( "stays/{$r['slug']}-{$n}-thumb.jpg" ),
			'strip' => gp_img( "stays/{$r['slug']}-{$n}-strip.jpg" ),
		);
	};
	$images = array_map( $img, $r['gallery'] );
	$detail = gp_demo_room_details( $r );

	return array(
		'id'         => $r['slug'],
		'title'      => $r['title'],
		'url'        => home_url( '/rooms/' . $r['slug'] . '/' ),
		'location'   => $dest ? reset( $dest )['name'] : '',
		'loc_slug'   => $r['destination'],
		'type'       => $r['type'],
		'beds'       => $r['beds'],
		'tags'       => $r['tags'],
		'excerpt'    => $r['excerpt'],
		'highlights' => $r['highlights'],
		'area'       => $r['area'],
		'guests'     => $r['guests'],
		'max_guests' => $r['max_guests'],
		'bed'        => $r['bed'],
		'view'       => $r['view'],
		'price'      => $r['price'],
		'gallery'    => $images,
		'active'     => $r['active'],
		'detail_gallery' => isset( $r['detail_gallery'] ) ? array_map( $img, $r['detail_gallery'] ) : $images,
		'detail_active'  => $r['detail_active'] ?? $r['active'],
		'hero'       => gp_img( "stays/{$r['slug']}-hero.jpg" ),
		'hero_mobile' => '',
		'main_mobile' => '',
	) + $detail;
}

/** Convert a gp_room post into the shared room shape. */
function gp_room_from_post( WP_Post $p ) {
	$terms  = function ( $tax ) use ( $p ) {
		$t = get_the_terms( $p, $tax );
		return $t && ! is_wp_error( $t ) ? $t : array();
	};
	$dest   = $terms( 'gp_destination' );
	$ids    = array_filter( array_map( 'absint', explode( ',', (string) get_post_meta( $p->ID, 'gp_gallery', true ) ) ) );
	$images = array_map( function ( $id ) {
		return array(
			'full'  => (string) wp_get_attachment_image_url( $id, 'gp-room' ),
			'thumb' => (string) wp_get_attachment_image_url( $id, 'gp-room-thumb' ),
			'strip' => (string) wp_get_attachment_image_url( $id, 'gp-room-strip' ),
		);
	}, array_values( $ids ) );
	$dids   = array_filter( array_map( 'absint', explode( ',', (string) get_post_meta( $p->ID, 'gp_detail_gallery', true ) ) ) );
	$detail = array_map( function ( $id ) {
		return array(
			'full'  => (string) wp_get_attachment_image_url( $id, 'gp-room' ),
			'thumb' => (string) wp_get_attachment_image_url( $id, 'gp-room-thumb' ),
			'strip' => (string) wp_get_attachment_image_url( $id, 'gp-room-strip' ),
		);
	}, array_values( $dids ) );

	return array(
		'id'         => (string) $p->ID,
		'title'      => get_the_title( $p ),
		'url'        => get_permalink( $p ),
		'location'   => $dest ? $dest[0]->name : '',
		'loc_slug'   => $dest ? $dest[0]->slug : '',
		'type'       => ( $terms( 'gp_room_type' )[0] ?? null )->slug ?? '',
		'beds'       => wp_list_pluck( $terms( 'gp_bed_type' ), 'slug' ),
		'tags'       => wp_list_pluck( $terms( 'gp_room_tag' ), 'slug' ),
		'excerpt'    => get_the_excerpt( $p ),
		'highlights' => (array) get_post_meta( $p->ID, 'gp_highlights', true ),
		'area'       => get_post_meta( $p->ID, 'gp_area', true ),
		'guests'     => get_post_meta( $p->ID, 'gp_guests_label', true ),
		'max_guests' => (int) get_post_meta( $p->ID, 'gp_max_guests', true ),
		'bed'        => get_post_meta( $p->ID, 'gp_bed_label', true ),
		'view'       => get_post_meta( $p->ID, 'gp_view', true ),
		'price'      => (float) get_post_meta( $p->ID, 'gp_price', true ),
		'gallery'    => $images,
		'active'     => (int) get_post_meta( $p->ID, 'gp_gallery_active', true ),
		'detail_gallery' => $detail ?: $images,
		'detail_active'  => (int) get_post_meta( $p->ID, $detail ? 'gp_detail_active' : 'gp_gallery_active', true ),
		'hero_mobile' => gp_media_url( get_post_meta( $p->ID, 'gp_hero_mobile_id', true ) ),
		'main_mobile' => gp_media_url( get_post_meta( $p->ID, 'gp_main_mobile_id', true ) ),
		'hero'       => get_post_meta( $p->ID, 'gp_hero_image_id', true ) ? gp_media_url( get_post_meta( $p->ID, 'gp_hero_image_id', true ) ) : ( $ids ? (string) wp_get_attachment_image_url( (int) ( array_values( $ids )[ (int) get_post_meta( $p->ID, 'gp_gallery_active', true ) ] ?? reset( $ids ) ), 'full' ) : '' ),
		'property'   => get_post_meta( $p->ID, 'gp_property', true ),
		'hero_text'  => get_post_meta( $p->ID, 'gp_hero_text', true ),
		'concept'    => get_post_meta( $p->ID, 'gp_concept', true ) ?: wp_strip_all_tags( $p->post_content ),
		'features'   => (array) get_post_meta( $p->ID, 'gp_features', true ),
		'amenities_intro' => get_post_meta( $p->ID, 'gp_amenities_intro', true ),
		'amenities'  => gp_parse_amenity_groups( get_post_meta( $p->ID, 'gp_amenity_groups', true ) ) ?: gp_default_room_amenities(),
		'note'       => get_post_meta( $p->ID, 'gp_booking_note', true ) ?: gp_opt( 'room_booking_note' ),
	);
}

/**
 * Rooms for the Stays page.
 *
 * @param array $args { destination?, room?, bed?, tags?: string[], guests?: int, sort? }
 */
function gp_get_rooms( $args = array() ) {
	$args = wp_parse_args( $args, array( 'destination' => '', 'room' => '', 'bed' => '', 'tags' => array(), 'guests' => 0, 'sort' => '' ) );

	$rooms = gp_has_posts( 'gp_room' )
		? array_map( 'gp_room_from_post', get_posts( array(
			'post_type'      => 'gp_room',
			'posts_per_page' => -1,
			'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
		) ) )
		: array_map( 'gp_room_from_demo', gp_demo_rooms() );

	$rooms = array_values( array_filter( $rooms, function ( $r ) use ( $args ) {
		return ( ! $args['destination'] || $r['loc_slug'] === $args['destination'] )
			&& ( ! $args['room'] || $r['type'] === $args['room'] )
			&& ( ! $args['bed'] || in_array( $args['bed'], $r['beds'], true ) )
			&& ( ! $args['guests'] || $r['max_guests'] >= $args['guests'] )
			&& ! array_diff( (array) $args['tags'], $r['tags'] );
	} ) );

	$sorts = array(
		'price-asc'  => fn( $a, $b ) => $a['price'] <=> $b['price'],
		'price-desc' => fn( $a, $b ) => $b['price'] <=> $a['price'],
		'size-desc'  => fn( $a, $b ) => (int) $b['area'] <=> (int) $a['area'],
	);
	if ( isset( $sorts[ $args['sort'] ] ) ) {
		usort( $rooms, $sorts[ $args['sort'] ] );
	}
	return $rooms;
}

/** Current Stays filters from the query string (shared by template and JS). */
function gp_room_query_args() {
	$opts = gp_room_filter_options();
	$get  = function ( $key ) {
		return isset( $_GET[ $key ] ) ? sanitize_key( wp_unslash( $_GET[ $key ] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	};
	$tags = array_filter( explode( ',', isset( $_GET['tags'] ) ? sanitize_text_field( wp_unslash( $_GET['tags'] ) ) : '' ) ); // phpcs:ignore WordPress.Security.NonceVerification

	return array(
		'destination' => $get( 'destination' ),
		'room'        => isset( $opts['room'][ $get( 'room' ) ] ) ? $get( 'room' ) : '',
		'bed'         => isset( $opts['bed'][ $get( 'bed' ) ] ) ? $get( 'bed' ) : '',
		'tags'        => array_values( array_intersect( array_map( 'sanitize_key', $tags ), array_keys( $opts['tags'] ) ) ),
		'guests'      => isset( $_GET['guests'] ) ? (int) wp_unslash( $_GET['guests'] ) : 0, // phpcs:ignore WordPress.Security.NonceVerification
		'sort'        => isset( $opts['sort'][ $get( 'sort' ) ] ) ? $get( 'sort' ) : '',
	);
}

/** Amenity groups shown on every room page unless the room overrides them (Figma 1148:10283). */
function gp_default_room_amenities() {
	return array(
		'Bedroom & Sleep Sanctuary'      => array( 'King Pillow-Top Mattress', '300-Thread-Count Thai Cotton Linens', 'Pillow Selection', 'Motorized Blackout Drapes', 'Wireless Charging & USB-C Ports' ),
		'Bathroom & Spa Bathing'         => array( 'Deep Soaking Terrazzo Bathtub', 'Dual Rain Shower with Views', 'Harnn Lemongrass & Bergamot Amenities', 'Waffle-Weave Robes & Slippers', 'Toto Smart Washlet' ),
		'Refreshments & In-Villa Dining' => array( 'Nespresso Vertuo & Complimentary Pods', 'Monsoon Tea Organic Tea Selection', 'Fresh Young Coconut on Arrival', 'Curated Spirits & Kombucha Minibar', '24-Hour In-Villa Dining' ),
		'Technology & Climate'           => array( 'High-Speed WiFi 6', '65" 4K Smart OLED TV', 'Marshall Bluetooth Sound System', 'Daikin Climate Control & HEPA', 'Digital Laptop-Size Safe' ),
	);
}

/** Parse "Group | item" lines into array<group, items[]>. */
function gp_parse_amenity_groups( $lines ) {
	$groups = array();
	foreach ( (array) $lines as $line ) {
		$parts = array_map( 'trim', explode( '|', (string) $line, 2 ) );
		if ( 2 === count( $parts ) && '' !== $parts[0] && '' !== $parts[1] ) {
			$groups[ $parts[0] ][] = $parts[1];
		}
	}
	return $groups;
}

/**
 * Detail-page fields (Figma "Stays/ overview", 1148:10234). Demo rooms get the
 * Figma copy for Chao Phraya River Deluxe and derived copy for the others.
 */
function gp_demo_room_details( array $r ) {
	$properties = array( 'bangkok' => 'GP House Bangkok Riverside', 'phuket' => 'GP House Phuket Sanctuary', 'krabi' => 'GP House Krabi Karst Reserve' );
	$details    = array(
		'property'        => $properties[ $r['destination'] ] ?? '',
		'hero_text'       => $r['view'],
		'concept'         => $r['excerpt'],
		'features'        => array( $r['area'] . ' interior space', $r['view'], 'Smart ambient lighting controls' ),
		'amenities_intro' => 'Thoughtfully appointed with bespoke luxury comforts and sustainable Thai botanical wellness essentials.',
		'amenities'       => gp_default_room_amenities(),
		'note'            => 'Instant confirmation • Pay upon check-in • Free cancellation 48 hrs prior',
	);
	if ( 'chao-phraya-river-deluxe' === $r['slug'] ) {
		$details['hero_text'] = 'Panoramic river barge and skyline panoramas';
		$details['concept']   = 'Gaze out across the bustling historic river from acoustic-sealed bay windows. Modern timber slatted walls evoke classic Thai riverside houses, complemented by hand-woven Jim Thompson silk pillows.';
		$details['features']  = array( '52 sqm interior space', 'Riverfront orientation', 'Smart ambient lighting controls' );
	}
	return $details;
}
