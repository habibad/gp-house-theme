<?php
/**
 * Investor Relations (/investor-relations/) — Figma 1148:10446, mobile 1438:13017.
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

/** Owner financial simulator model (matches the Figma numbers: 50 keys × $280 × 65% → +22% ADR, +8 pts, 8% OTA savings). */
function gp_investor_sim() {
	return apply_filters( 'gp_house_investor_sim', array(
		'keys'      => array( 'min' => 10, 'max' => 210, 'step' => 1, 'value' => 50 ),
		'adr'       => array( 'min' => 100, 'max' => 620, 'step' => 5, 'value' => 280 ),
		'occ'       => array( 'min' => 10, 'max' => 90, 'step' => 1, 'value' => 65 ),
		'adr_lift'  => 0.22,
		'occ_lift'  => 8,
		'ota_share' => 0.08,
	) );
}

function gp_investor_content() {
	return apply_filters( 'gp_house_investor_content', array(
		'stats'   => array(
			array( '+38%', 'Year 1 RevPAR Uplift' ),
			array( '68%', 'Direct Booking Share' ),
			array( '120K+', 'GP Club Members' ),
			array( '94.2%', 'Guest Satisfaction' ),
		),
		'noi'     => array( 'title' => 'Superior Net Operating Income (NOI)', 'text' => 'Our direct booking engine and GP Club loyalty network drive 65%+ direct bookings, saving owners 15–22% in OTA commissions.' ),
		'cards'   => array(
			array( 'icon' => 'sparkle-navy', 'title' => 'Design & Re-Positioning', 'text' => 'Our in-house design teams transform unique properties into timeless icons that command premium ADR.', 'image' => 'investors/adv-1.jpg' ),
			array( 'icon' => 'shield-check-navy', 'title' => 'Rigorous Operational Integrity', 'text' => 'Transparent reporting, regional management, and strict service standards ensure lasting performance.', 'image' => 'investors/adv-2.jpg' ),
		),
		'models'  => array(
			array( 'Full Hotel Management (HMA)', 'Turnkey management. GPHM oversees operations, culinary, sales, recruitment, and guest experience under the GP House brand.' ),
			array( 'GPHM Collection (Soft Brand License)', 'Keep your hotel’s historic name while accessing our global reservations, loyalty program, and luxury distribution.' ),
			array( 'Pre-Opening & Re-Positioning Advisory', 'Comprehensive development advisory from master planning and architectural curation to pre-opening team training, F&B concept launch, and opening marketing.' ),
		),
		'form_models' => array(
			'hma'        => 'Hotel Management (HMA)',
			'collection' => 'GPHM Collection (Soft Brand License)',
			'advisory'   => 'Pre-Opening & Design Advisory',
			'feasibility' => 'Initial Feasibility Assessment',
		),
	) );
}
