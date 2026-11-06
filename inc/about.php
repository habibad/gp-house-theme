<?php
/**
 * About page (/about/) — Figma "About" (1148:7453), mobile 1439:10713.
 *
 * Marketing copy lives here as filterable defaults (`gp_house_about_content`);
 * headings are editable in the Customizer (GP House → About page).
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

function gp_about_content() {
	return apply_filters( 'gp_house_about_content', array(
		'ethos_paragraphs' => array(
			'We reject mass-market uniformity. Each GP House retreat is a sanctuary shaped by its surroundings—from teakwood pavilions above limestone waters to shaded riverfront courtyards.',
			'Our Thai hospitality is warm, unhurried, and discreet, with 85% of ingredients and textiles sourced locally from family-run farms, studios, and fishing communities.',
		),
		'badge'            => array( 'number' => '120+', 'text' => 'Over 120 historic teak pillars preserved across our three sanctuaries.' ),
		'ours'             => array(
			array( 'Intimate Scale', '24–36 private pavilions for quiet stays.' ),
			array( 'Local Design', 'Reclaimed teak, handcrafted stone, natural airflow.' ),
			array( 'Artisanal Cuisine', 'Fresh local produce and regional specialties.' ),
			array( 'Local Hosts', 'Connected to the community and its traditions.' ),
		),
		'theirs'           => array(
			array( 'Mega-Resorts', '400+ rooms and crowded spaces.' ),
			array( 'Corporate Design', 'Identical, globally sourced interiors.' ),
			array( 'Industrial Dining', 'Mass-produced food and cold-chain supply.' ),
			array( 'Scripted Service', 'High turnover and commission-driven upselling.' ),
		),
		// Team members per property tab. Only Bangkok is shown in Figma; Phuket and Krabi are placeholders.
		'team'             => array(
			'bangkok' => array(
				array( 'role' => 'Reception', 'name' => 'Somchai Prasert', 'text' => 'Nathalie is here to welcome you and assist with check-in, concierge requests, and anything you need during your stay in Bangkok.', 'image' => 'about/staff-1.jpg' ),
				array( 'role' => 'Concierge', 'name' => 'Somchai Prasert', 'text' => 'Arthit is here to guide your Bangkok journey with curated recommendations, restaurant reservations, private tours, and exclusive experiences.', 'image' => 'about/staff-2.jpg' ),
				array( 'role' => 'Guest Relations', 'name' => 'Pimchanok Srisuwan', 'text' => 'Pimchanok ensures your stay is comfortable and memorable, and is always ready to help with any special requests.', 'image' => 'about/staff-3.jpg' ),
			),
			'phuket'  => array(
				array( 'role' => 'Reception', 'name' => 'Somchai Prasert', 'text' => 'Our Phuket reception team is here for check-in, transfers, and anything you need during your stay.', 'image' => 'about/staff-1.jpg' ),
				array( 'role' => 'Concierge', 'name' => 'Arthit Chaiyo', 'text' => 'Let us arrange island excursions, spa rituals, private boat days, and candlelit dinners by the sea.', 'image' => 'about/staff-2.jpg' ),
				array( 'role' => 'Guest Relations', 'name' => 'Pimchanok Srisuwan', 'text' => 'Pimchanok ensures your stay is comfortable and memorable, and is always ready to help with any special requests.', 'image' => 'about/staff-3.jpg' ),
			),
			'krabi'   => array(
				array( 'role' => 'Reception', 'name' => 'Somchai Prasert', 'text' => 'Welcome to GP House Krabi — we are here to help with check-in, longtail transfers, and your every request.', 'image' => 'about/staff-1.jpg' ),
				array( 'role' => 'Concierge', 'name' => 'Arthit Chaiyo', 'text' => 'From limestone kayaking to emerald-pool picnics, our concierge plans each day around the karst landscape.', 'image' => 'about/staff-2.jpg' ),
				array( 'role' => 'Guest Relations', 'name' => 'Pimchanok Srisuwan', 'text' => 'Pimchanok ensures your stay is comfortable and memorable, and is always ready to help with any special requests.', 'image' => 'about/staff-3.jpg' ),
			),
		),
	) );
}

/** Footer call-to-action for the About page ("Begin Your Thai Chapter With Us", one button). */
add_filter( 'gp_house_footer_cta', function ( $cta ) {
	if ( ! is_page( 'about' ) ) {
		return $cta;
	}
	return array(
		'kicker'  => gp_opt( 'about_cta_kicker' ),
		'title'   => gp_opt( 'about_cta_title' ),
		'text'    => gp_opt( 'about_cta_text' ),
		'class'   => 'gp-cta--about',
		'mobile'  => array( 'title' => gp_opt( 'about_cta_title_m' ), 'text' => gp_opt( 'about_cta_text_m' ) ),
		'buttons' => array( array( 'label' => __( 'Book Your Stay', 'gp-house' ), 'url' => home_url( '/stays/' ), 'size' => 'tab' ) ),
	);
} );
