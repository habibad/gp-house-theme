<?php
/**
 * Destinations page (Figma "Hero /Destinations" 1148:6972 and its map state
 * 1148:7060): region cards and page copy.
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

/** Figma copy for the region cards, keyed by destination slug. */
function gp_demo_regions() {
	return array(
		'phuket'  => array(
			'title' => 'Andaman Serenity & Coastal Seclusion',
			'text'  => 'Cliffside sanctuaries and private beachfront pavilions framed by azure waters, ancient coconut groves, and dramatic limestone vistas.',
			'price' => 380,
			'image' => 'destinations/region-phuket.jpg',
		),
		'krabi'   => array(
			'title' => 'Emerald Waters & Towering Karsts',
			'text'  => 'Tucked away along pristine Railay shores and mangrove waterways where limestone cliffs rise dramatically out of the Andaman Sea.',
			'price' => 320,
			'image' => 'destinations/region-krabi.jpg',
		),
		'bangkok' => array(
			'title' => 'Chao Phraya Elegance & Urban Heritage',
			'text'  => 'An architectural oasis on the historic River of Kings blending contemporary Thai design with peaceful courtyard gardens and skyline tranquility.',
			'price' => 360,
			'image' => 'destinations/region-bangkok.jpg',
		),
	);
}

/**
 * Region cards in the stay-card shape (gp_component( 'stay-card', variant region )).
 * Destinations without a region title are skipped.
 */
function gp_get_region_cards() {
	$cards = array();
	$demo  = gp_demo_regions();
	$terms = taxonomy_exists( 'gp_destination' ) ? get_terms( array( 'taxonomy' => 'gp_destination', 'hide_empty' => false, 'meta_key' => 'gp_order', 'orderby' => 'meta_value_num' ) ) : array();

	if ( $terms && ! is_wp_error( $terms ) ) {
		foreach ( $terms as $t ) {
			$title = get_term_meta( $t->term_id, 'gp_region_title', true );
			if ( ! $title ) {
				continue;
			}
			$cards[] = array(
				'id'       => $t->slug,
				'loc_slug' => $t->slug,
				'location' => $t->name,
				'title'    => $title,
				'excerpt'  => get_term_meta( $t->term_id, 'gp_region_text', true ),
				'price'    => (float) get_term_meta( $t->term_id, 'gp_from_price', true ),
				'image'    => gp_media_url( get_term_meta( $t->term_id, 'gp_region_image_id', true ), 'gp-card' ),
				'url'      => get_term_link( $t ),
			);
		}
		if ( $cards ) {
			return $cards;
		}
	}

	foreach ( gp_get_destinations() as $dest ) {
		if ( empty( $demo[ $dest['slug'] ] ) ) {
			continue;
		}
		$r       = $demo[ $dest['slug'] ];
		$cards[] = array(
			'id'       => $dest['slug'],
			'loc_slug' => $dest['slug'],
			'location' => $dest['name'],
			'title'    => $r['title'],
			'excerpt'  => $r['text'],
			'price'    => $r['price'],
			'image'    => gp_img( $r['image'] ),
			'url'      => $dest['url'],
		);
	}
	return $cards;
}

/**
 * Destination page copy (Figma "All Thailand Destinations/ overview", 1148:10747).
 * Phuket is the Figma copy; Krabi and Bangkok reuse the structure with their
 * own text until real content is entered in wp-admin.
 */
function gp_demo_destination_pages() {
	$faqs_phuket = array(
		array( 'Does GP House Phuket have direct private beach access?', 'Yes. GP House Phuket is situated on an exclusive secluded granite cove on the north-west coast with private, direct access to soft golden sands. Unlike public beaches, our cove is sheltered and exclusively reserved for resident guests.' ),
		array( 'Can private boats depart from the resort?', 'Yes. Our 42-foot luxury catamaran departs from the resort’s private mooring at high tide, or from a protected marina just 8 minutes away.' ),
		array( 'What are the seasonal swimming conditions at the cove?', 'From November–April, enjoy calm, crystal-clear Andaman waters for swimming and snorkeling. From May–October, relax in our oceanfront infinity pools while watching dramatic monsoon waves.' ),
		array( 'Are electric buggies available for cliffside villas?', 'Yes, 24-hour electric buggies are available. Dial “0” or use the in-suite tablet to request a pickup.' ),
		array( 'How far is the resort from Phuket Airport (HKT)?', 'About 35 minutes by private Mercedes-Benz limousine. Gold, Platinum & Diamond Club members receive complimentary VIP fast-track airport meet-and-greet.' ),
	);
	return array(
		'phuket'  => array(
			'hero'          => 'destinations/phuket-hero.jpg',
			'hero_mobile'   => 'destinations/phuket-hero-mobile.jpg',
			'hero_shaded'   => true,
			'insider_mobile' => 'destinations/phuket-insider-mobile.jpg',
			'hero_text'     => 'Cliffside sanctuaries and private beachfront pavilions framed by azure waters, ancient coconut groves, and dramatic limestone vistas.',
			'insider_image' => 'destinations/phuket-insider.jpg',
			'insider'       => array(
				array( 'viewpoint', 'Hidden Viewpoints & Excursions', 'A hidden rocky headland 15 minutes south where wild African tulip trees frame unobstructed golden hour sunsets with zero tourist crowds.' ),
				array( 'season', 'Best Season & Climate', 'November to April for calm turquoise seas and clear skies; May to October for quiet lush greenery and dramatic tropical surf.' ),
				array( 'gastronomy', 'Regional Gastronomy Icon', 'Slow-braised pork belly infused with star anise, palm sugar, garlic, black peppercorns, and aged soy for a rich Peranakan flavor.' ),
			),
			'faqs'          => $faqs_phuket,
		),
		'krabi'   => array(
			'hero'          => 'destinations/hero-thailand.jpg',
			'hero_text'     => 'Tucked away along pristine Railay shores and mangrove waterways where limestone cliffs rise dramatically out of the Andaman Sea.',
			'insider_image' => 'destinations/phuket-insider.jpg',
			'insider'       => array(
				array( 'viewpoint', 'Hidden Viewpoints & Excursions', 'Climb the Railay lagoon trail at dawn for emerald pools and karst panoramas before the day-trip boats arrive.' ),
				array( 'season', 'Best Season & Climate', 'November to April for glassy seas and island hopping; May to October for misty karsts and quiet beaches.' ),
				array( 'gastronomy', 'Regional Gastronomy Icon', 'Southern-style gaeng som — a fiery sour curry of fresh catch, turmeric, and young coconut shoots.' ),
			),
			'faqs'          => array(),
		),
		'bangkok' => array(
			'hero'          => 'destinations/hero-thailand.jpg',
			'hero_text'     => 'An architectural oasis on the historic River of Kings blending contemporary Thai design with peaceful courtyard gardens and skyline tranquility.',
			'insider_image' => 'destinations/phuket-insider.jpg',
			'insider'       => array(
				array( 'viewpoint', 'Hidden Viewpoints & Excursions', 'Take the resort’s longtail to Wat Arun at first light, before the city wakes and the river is still glass.' ),
				array( 'season', 'Best Season & Climate', 'November to February for cool, dry evenings on the river; March to October for festivals and quieter temples.' ),
				array( 'gastronomy', 'Regional Gastronomy Icon', 'Boat noodles from the Victory Monument canal stalls — rich, spiced broth served in tiny bowls.' ),
			),
			'faqs'          => array(),
		),
	);
}

/** Split "a | b | c" lines into arrays. */
function gp_split_lines( $lines, $parts ) {
	$out = array();
	foreach ( (array) $lines as $line ) {
		$cols = array_map( 'trim', explode( '|', (string) $line, $parts ) );
		if ( count( $cols ) === $parts && '' !== $cols[0] ) {
			$out[] = $cols;
		}
	}
	return $out;
}

/** Room-type chip label on destination room cards (Figma: Deluxe / Villa / Suite). */
function gp_room_chip_label( $type ) {
	$labels = apply_filters( 'gp_house_room_chip_labels', array( 'deluxe' => 'Deluxe', 'villa' => 'Villa', 'suite' => 'Suite', 'residence' => 'Suite' ) );
	return $labels[ $type ] ?? ucfirst( (string) $type );
}

/** All data for a destination page. */
function gp_get_destination_page( WP_Term $term ) {
	$demo = gp_demo_destination_pages()[ $term->slug ] ?? array();
	$meta = function ( $key ) use ( $term ) {
		return get_term_meta( $term->term_id, $key, true );
	};
	$insider = gp_split_lines( $meta( 'gp_insider_items' ), 3 ) ?: ( $demo['insider'] ?? array() );
	$faqs    = gp_split_lines( $meta( 'gp_faqs' ), 2 ) ?: ( $demo['faqs'] ?? array() );

	return array(
		'slug'          => $term->slug,
		'name'          => $term->name,
		'hero'          => $meta( 'gp_page_hero_id' ) ? gp_media_url( $meta( 'gp_page_hero_id' ) ) : ( isset( $demo['hero'] ) ? gp_img( $demo['hero'] ) : '' ),
		'hero_mobile'   => $meta( 'gp_page_hero_mobile_id' ) ? gp_media_url( $meta( 'gp_page_hero_mobile_id' ) ) : ( isset( $demo['hero_mobile'] ) && ! $meta( 'gp_page_hero_id' ) ? gp_img( $demo['hero_mobile'] ) : '' ),
		'insider_mobile' => $meta( 'gp_insider_mobile_id' ) ? gp_media_url( $meta( 'gp_insider_mobile_id' ), 'large' ) : ( isset( $demo['insider_mobile'] ) && ! $meta( 'gp_insider_image_id' ) ? gp_img( $demo['insider_mobile'] ) : '' ),
		// Demo heroes were exported from Figma with the shade already applied.
		'hero_shaded'   => $meta( 'gp_page_hero_id' ) ? (bool) $meta( 'gp_page_hero_shaded' ) : ! empty( $demo['hero_shaded'] ),
		'hero_text'     => $meta( 'gp_page_hero_text' ) ?: ( $demo['hero_text'] ?? $term->description ),
		'insider_image' => $meta( 'gp_insider_image_id' ) ? gp_media_url( $meta( 'gp_insider_image_id' ), 'large' ) : ( isset( $demo['insider_image'] ) ? gp_img( $demo['insider_image'] ) : '' ),
		'insider'       => $insider,
		'faqs'          => $faqs,
	);
}
