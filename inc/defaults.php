<?php
/**
 * Design defaults: Customizer texts and the demo content shown in the Figma UI.
 *
 * Demo content is used as a fallback while the site has no real content, and the
 * GP House Core plugin imports it (via the `gp_house_demo_content` filter) so it
 * becomes editable in wp-admin.
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

/** Customizer options, grouped by section. */
function gp_option_defaults() {
	return array(
		// Hero.
		'hero_kicker'      => array( 'section' => 'hero', 'label' => 'Kicker', 'default' => 'Thai Sanctuaries' ),
		'hero_title'       => array( 'section' => 'hero', 'label' => 'Title', 'default' => 'Stay. Explore. Connect. Belong.' ),
		'hero_badge'       => array( 'section' => 'hero', 'label' => 'Badge above search', 'default' => 'Become a Member & Get 10% Discount' ),
		'hero_promo'       => array( 'section' => 'hero', 'label' => 'Default promo code', 'default' => 'FIRSTBOOK20' ),
		'hero_image'       => array( 'section' => 'hero', 'label' => 'Background image', 'default' => '', 'type' => 'image' ),
		// Stays page.
		'stays_hero_kicker' => array( 'section' => 'stays_page', 'label' => 'Kicker', 'default' => 'Sanctuaries & Suites' ),
		'stays_hero_title'  => array( 'section' => 'stays_page', 'label' => 'Title', 'default' => 'Find Your Stay' ),
		'stays_hero_text'   => array( 'section' => 'stays_page', 'label' => 'Intro', 'default' => 'Discover tailored villas and pavilions with organic teak, private plunge pools, and uninterrupted natural views.', 'type' => 'textarea' ),
		'stays_hero_image'  => array( 'section' => 'stays_page', 'label' => 'Hero image (1440 × 562)', 'default' => '', 'type' => 'image' ),
		'room_booking_note' => array( 'section' => 'stays_page', 'label' => 'Room page booking note', 'default' => 'Instant confirmation • Pay upon check-in • Free cancellation 48 hrs prior' ),
		// Destinations page.
		'dpage_kicker'      => array( 'section' => 'dest_page', 'label' => 'Hero kicker', 'default' => 'Kingdom of Thailand' ),
		'dpage_title'       => array( 'section' => 'dest_page', 'label' => 'Hero title', 'default' => 'Explore Thailand' ),
		'dpage_text'        => array( 'section' => 'dest_page', 'label' => 'Hero text', 'default' => 'Explore our beautiful boutique sanctuaries across Thailand through an interactive map.', 'type' => 'textarea' ),
		'dpage_hero'        => array( 'section' => 'dest_page', 'label' => 'Hero image (1440 × 562)', 'default' => '', 'type' => 'image' ),
		'dpage_grid_kicker' => array( 'section' => 'dest_page', 'label' => 'Grid view kicker', 'default' => 'Destination Curations' ),
		'dpage_grid_title'  => array( 'section' => 'dest_page', 'label' => 'Grid view title', 'default' => 'Regional Guides & Cultural Highlights' ),
		'dpage_map_kicker'  => array( 'section' => 'dest_page', 'label' => 'Map view kicker', 'default' => 'Visual Map Interface' ),
		'dpage_map_title'   => array( 'section' => 'dest_page', 'label' => 'Map view title', 'default' => 'Thailand Property Map & Sanctuaries' ),
		// Offers page.
		'offers_kicker'      => array( 'section' => 'offers_page', 'label' => 'Hero kicker', 'default' => 'Privileged Rates & Curated Escapes' ),
		'offers_title'       => array( 'section' => 'offers_page', 'label' => 'Hero title', 'default' => 'Stay & Discover More' ),
		'offers_text'        => array( 'section' => 'offers_page', 'label' => 'Hero text', 'default' => 'Exclusive packages for extended stays, early savings, and wellness retreats across Phuket, Krabi, and Bangkok.', 'type' => 'textarea' ),
		'offers_hero_image'  => array( 'section' => 'offers_page', 'label' => 'Hero image (1440 × 562)', 'default' => '', 'type' => 'image' ),
		'offers_band_title'  => array( 'section' => 'offers_page', 'label' => 'Guarantee band title', 'default' => 'GP House Direct Booking Guarantee' ),
		'offers_band_text'   => array( 'section' => 'offers_page', 'label' => 'Guarantee band text', 'default' => 'Direct booking perks: best price guarantee, free upgrades when available, flexible cancellation, and instant GP House Club points.', 'type' => 'textarea' ),
		'offers_band_button' => array( 'section' => 'offers_page', 'label' => 'Guarantee band button', 'default' => 'Reserve Direct' ),
		'offers_band_image'  => array( 'section' => 'offers_page', 'label' => 'Guarantee band background', 'default' => '', 'type' => 'image' ),
		// Club page.
		'club_kicker' => array( 'section' => 'club_page', 'label' => 'Hero kicker', 'default' => 'GP House Club Privileges' ),
		'club_title' => array( 'section' => 'club_page', 'label' => 'Hero title', 'default' => 'Extraordinary Thai Hospitality' ),
		'club_text' => array( 'section' => 'club_page', 'label' => 'Hero text', 'default' => 'Exclusive benefits for discerning travelers, with preferred rates, upgrades, transfers, and rewards across Bangkok, Phuket, Krabi.', 'type' => 'textarea' ),
		'club_hero_image' => array( 'section' => 'club_page', 'label' => 'Hero image (1440 × 562)', 'default' => '', 'type' => 'image' ),
		'club_steps_kicker' => array( 'section' => 'club_page', 'label' => 'Steps kicker', 'default' => 'How It Works' ),
		'club_steps_title' => array( 'section' => 'club_page', 'label' => 'Steps title', 'default' => 'Four Steps To Exclusive Thai Club Privileges' ),
		'club_benefits_kicker' => array( 'section' => 'club_page', 'label' => 'Benefits kicker', 'default' => 'Direct Privileges' ),
		'club_benefits_title' => array( 'section' => 'club_page', 'label' => 'Benefits title', 'default' => 'Why Book As A GP House Club Member?' ),
		'club_tiers_kicker' => array( 'section' => 'club_page', 'label' => 'Tiers kicker', 'default' => 'Direct Privileges' ),
		'club_tiers_title' => array( 'section' => 'club_page', 'label' => 'Tiers title', 'default' => 'Why Book As A GP House Club Member?' ),
		'club_tiers_text' => array( 'section' => 'club_page', 'label' => 'Tiers intro', 'default' => 'As your GP House journey unfolds, unlock higher earning multiples, suite upgrades, and personal sanctuary hosts.', 'type' => 'textarea' ),
		'club_calc_kicker' => array( 'section' => 'club_page', 'label' => 'Calculator kicker', 'default' => 'Interactive Calculator' ),
		'club_calc_title' => array( 'section' => 'club_page', 'label' => 'Calculator title', 'default' => 'Calculate Your Next Stay’s Rewards' ),
		'club_calc_text' => array( 'section' => 'club_page', 'label' => 'Calculator intro', 'default' => 'Select your sanctuary destination and planned length of stay to view projected points accrued and immediate redemption opportunities.', 'type' => 'textarea' ),
		'club_redeem_kicker' => array( 'section' => 'club_page', 'label' => 'Redemptions kicker', 'default' => 'Points Catalog' ),
		'club_redeem_title' => array( 'section' => 'club_page', 'label' => 'Redemptions title', 'default' => 'Signature Club Redemptions' ),
		'club_faq_kicker' => array( 'section' => 'club_page', 'label' => 'FAQ kicker', 'default' => 'Club Knowledge' ),
		'club_faq_title' => array( 'section' => 'club_page', 'label' => 'FAQ title', 'default' => 'Frequently Asked Questions' ),
		'club_cta_kicker' => array( 'section' => 'club_page', 'label' => 'Footer CTA kicker', 'default' => 'Member Privilege' ),
		'club_cta_title' => array( 'section' => 'club_page', 'label' => 'Footer CTA title', 'default' => 'Your Siamese Sanctuary Awaits' ),
		'club_cta_text' => array( 'section' => 'club_page', 'label' => 'Footer CTA text', 'default' => 'Book directly today with promo code MEMBERDIRECT for an instant 10% discount and direct VIP privileges.', 'type' => 'textarea' ),
		'about_kicker' => array( 'section' => 'about_page', 'label' => 'Hero kicker', 'default' => 'Heritage & Craft' ),
		'about_title' => array( 'section' => 'about_page', 'label' => 'Hero title', 'default' => 'Our Story' ),
		'about_text' => array( 'section' => 'about_page', 'label' => 'Hero text', 'default' => 'GP House celebrates Thai hospitality through intimate sanctuaries rooted in community, landscape, and heritage.', 'type' => 'textarea' ),
		'about_hero_image' => array( 'section' => 'about_page', 'label' => 'Hero image', 'default' => '', 'type' => 'image' ),
		'about_ethos_kicker' => array( 'section' => 'about_page', 'label' => 'Ethos kicker', 'default' => 'Our Ethos' ),
		'about_ethos_title' => array( 'section' => 'about_page', 'label' => 'Ethos title', 'default' => 'Boutique Hospitality Rooted in Respect' ),
		'about_diff_kicker' => array( 'section' => 'about_page', 'label' => 'Difference kicker', 'default' => 'Pillars of Difference' ),
		'about_diff_title' => array( 'section' => 'about_page', 'label' => 'Difference title', 'default' => 'The GP House Difference' ),
		'about_diff_text' => array( 'section' => 'about_page', 'label' => 'Difference intro', 'default' => 'How our intentional boutique model compares to conventional commercial chain resorts.', 'type' => 'textarea' ),
		'about_map_kicker' => array( 'section' => 'about_page', 'label' => 'Map kicker', 'default' => 'Geographic Presence' ),
		'about_map_title' => array( 'section' => 'about_page', 'label' => 'Map title', 'default' => 'Our Footprint Across Thailand' ),
		'about_map_text' => array( 'section' => 'about_page', 'label' => 'Map text', 'default' => 'Select any sanctuary pin below to explore property details, photography, and available suites.', 'type' => 'textarea' ),
		'about_team_kicker' => array( 'section' => 'about_page', 'label' => 'Team kicker', 'default' => 'Our Profile' ),
		'about_team_title' => array( 'section' => 'about_page', 'label' => 'Team title', 'default' => 'Stewards of the Sanctuary' ),
		'about_team_text' => array( 'section' => 'about_page', 'label' => 'Team intro', 'default' => 'Meet the friendly faces who will welcome you, assist you, and make your stay exceptional at each of our locations. Our local teams are here to ensure you feel at home.', 'type' => 'textarea' ),
		'about_phone' => array( 'section' => 'about_page', 'label' => 'Team phone', 'default' => '+6620000000' ),
		'about_email' => array( 'section' => 'about_page', 'label' => 'Team email', 'default' => 'hello@gphouse.com' ),
		'about_cta_kicker' => array( 'section' => 'about_page', 'label' => 'Footer CTA kicker', 'default' => 'Your Thai Journey Begins Here' ),
		'about_cta_title' => array( 'section' => 'about_page', 'label' => 'Footer CTA title', 'default' => 'Begin Your Thai Chapter With Us' ),
		'about_cta_text' => array( 'section' => 'about_page', 'label' => 'Footer CTA text', 'default' => 'Reserve your stay directly with GP House and experience authentic boutique hospitality firsthand.', 'type' => 'textarea' ),
		'about_cta_title_m' => array( 'section' => 'about_page', 'label' => 'Footer CTA title (mobile)', 'default' => 'Discover Thailand, The GP House Way' ),
		'about_cta_text_m' => array( 'section' => 'about_page', 'label' => 'Footer CTA text (mobile)', 'default' => 'Book directly with GP House to enjoy authentic Thai hospitality, thoughtfully curated stays, and a more personal travel experience.', 'type' => 'textarea' ),
		'about_team_title_m' => array( 'section' => 'about_page', 'label' => 'Team title (mobile)', 'default' => 'The People Behind Your Stay' ),
		'about_team_text_m' => array( 'section' => 'about_page', 'label' => 'Team intro (mobile)', 'default' => 'Meet the welcoming faces behind GP House. Our dedicated local teams bring Thai hospitality to life, ensuring every guest feels at home through thoughtful service and genuine care.', 'type' => 'textarea' ),
		'ir_kicker' => array( 'section' => 'ir_page', 'label' => 'Hero kicker', 'default' => 'Hotel Owner Partnerships' ),
		'ir_title' => array( 'section' => 'ir_page', 'label' => 'Hero title', 'default' => 'Elevate Hotels. Maximize Profitability.' ),
		'ir_text' => array( 'section' => 'ir_page', 'label' => 'Hero text', 'default' => 'GPHM transforms independent hotels through Thai design, stronger direct bookings, and smarter management.', 'type' => 'textarea' ),
		'ir_hero_image' => array( 'section' => 'ir_page', 'label' => 'Hero image', 'default' => '', 'type' => 'image' ),
		'ir_adv_kicker' => array( 'section' => 'ir_page', 'label' => 'Advantage kicker', 'default' => 'The GPHM Advantage' ),
		'ir_adv_title' => array( 'section' => 'ir_page', 'label' => 'Advantage title', 'default' => 'Why Hotel Owners Entrust Their Assets To Us' ),
		'ir_adv_text' => array( 'section' => 'ir_page', 'label' => 'Advantage text', 'default' => 'GPHM preserves your property’s unique identity while delivering expert revenue management and global sales.', 'type' => 'textarea' ),
		'ir_models_kicker' => array( 'section' => 'ir_page', 'label' => 'Models kicker', 'default' => 'Flexible Engagement' ),
		'ir_models_title' => array( 'section' => 'ir_page', 'label' => 'Models title', 'default' => 'Three Partnership Models' ),
		'ir_sim_kicker' => array( 'section' => 'ir_page', 'label' => 'Simulator kicker', 'default' => 'Owner Financial Simulator' ),
		'ir_sim_title' => array( 'section' => 'ir_page', 'label' => 'Simulator title', 'default' => 'Estimate Your Asset\'s Revenue Uplift Under GPHM' ),
		'ir_sim_text' => array( 'section' => 'ir_page', 'label' => 'Simulator text', 'default' => 'Model GPHM’s ADR, occupancy, and booking savings to estimate your room revenue uplift.', 'type' => 'textarea' ),
		'sc_kicker' => array( 'section' => 'sc_page', 'label' => 'Hero kicker', 'default' => 'Late-Night Arrival (11 PM–6 AM)' ),
		'sc_title' => array( 'section' => 'sc_page', 'label' => 'Hero title', 'default' => 'Late Night Self Check-In' ),
		'sc_text' => array( 'section' => 'sc_page', 'label' => 'Hero text', 'default' => 'Arriving Late? Enter Your Booking ID To Access Your Room Number, Door PIN, And Private Gate Directions.', 'type' => 'textarea' ),
		'sc_hero_image' => array( 'section' => 'sc_page', 'label' => 'Hero image', 'default' => '', 'type' => 'image' ),
		'sc_lookup_title' => array( 'section' => 'sc_page', 'label' => 'Look-up title', 'default' => 'Look Up Your Reservation' ),
		'sc_lookup_text' => array( 'section' => 'sc_page', 'label' => 'Look-up text', 'default' => 'Enter The Booking Confirmation ID Provided In Your Reservation Confirmation Email Or Member Itinerary.', 'type' => 'textarea' ),
		// Journal page (Figma "Journal", 1148:8205) and story page (1148:8455).
		'jp_kicker'        => array( 'section' => 'journal_page', 'label' => 'Hero kicker', 'default' => 'Chronicles Of Thai Hospitality' ),
		'jp_title'         => array( 'section' => 'journal_page', 'label' => 'Hero title', 'default' => 'The GP Journal' ),
		'jp_text'          => array( 'section' => 'journal_page', 'label' => 'Hero text', 'default' => 'Discover local architecture, healing traditions, and authentic regional cuisine with our cultural hosts.', 'type' => 'textarea' ),
		'jp_hero_image'    => array( 'section' => 'journal_page', 'label' => 'Hero image', 'default' => '', 'type' => 'image' ),
		'jp_list_kicker'   => array( 'section' => 'journal_page', 'label' => 'Stories kicker', 'default' => 'The GP Journal' ),
		'jp_list_title'    => array( 'section' => 'journal_page', 'label' => 'Stories title', 'default' => 'All Stories & Chronicles' ),
		'jp_list_text'     => array( 'section' => 'journal_page', 'label' => 'Stories intro', 'default' => 'Explore Thai hospitality, local traditions, architecture, and authentic regional gastronomy.', 'type' => 'textarea' ),
		'jp_empty_title'   => array( 'section' => 'journal_page', 'label' => 'Empty topic title', 'default' => 'Nothing Here Yet' ),
		'jp_empty_text'    => array( 'section' => 'journal_page', 'label' => 'Empty topic text', 'default' => 'No stories in this category yet. Check back soon for new journal entries.', 'type' => 'textarea' ),
		'news_kicker'      => array( 'section' => 'journal_page', 'label' => 'Newsletter kicker', 'default' => 'Curated Dispatch' ),
		'news_title'       => array( 'section' => 'journal_page', 'label' => 'Newsletter title', 'default' => 'Receive The GP House Gazette' ),
		'news_text'        => array( 'section' => 'journal_page', 'label' => 'Newsletter text', 'default' => 'Quarterly essays on architectural heritage, seasonal culinary menus, and early invitations to private island retreats across Thailand.', 'type' => 'textarea' ),
		'news_placeholder' => array( 'section' => 'journal_page', 'label' => 'Newsletter placeholder', 'default' => 'Enter Your Email Address..' ),
		'news_button'      => array( 'section' => 'journal_page', 'label' => 'Newsletter button', 'default' => 'Subscribe' ),
		'story_kicker'     => array( 'section' => 'journal_page', 'label' => 'Story page hero kicker', 'default' => 'The GP Journal' ),
		'story_title'      => array( 'section' => 'journal_page', 'label' => 'Story page hero title', 'default' => 'Thai Hospitality Stories' ),
		'story_text'       => array( 'section' => 'journal_page', 'label' => 'Story page hero text', 'default' => 'Discover Thai culture, traditions, and inspiring stories behind every unforgettable stay.', 'type' => 'textarea' ),
		'story_hero_image' => array( 'section' => 'journal_page', 'label' => 'Story page hero image', 'default' => '', 'type' => 'image' ),
		'story_cta_kicker' => array( 'section' => 'journal_page', 'label' => 'Story footer CTA kicker', 'default' => 'Experience It In Person' ),
		'story_cta_title'  => array( 'section' => 'journal_page', 'label' => 'Story footer CTA title', 'default' => 'Ready To Immerse Yourself In Thailand?' ),
		'story_cta_text'   => array( 'section' => 'journal_page', 'label' => 'Story footer CTA text', 'default' => 'Reserve directly with GP House for guaranteed lowest rates, bespoke arrival amenities, and member points.', 'type' => 'textarea' ),
		// Explore destinations.
		'dest_kicker'      => array( 'section' => 'destinations', 'label' => 'Kicker', 'default' => 'Region Discovery' ),
		'dest_title'       => array( 'section' => 'destinations', 'label' => 'Title', 'default' => 'Explore Destinations' ),
		// Featured stays.
		'stays_kicker'     => array( 'section' => 'stays', 'label' => 'Kicker', 'default' => 'Featured Sanctuaries' ),
		'stays_title'      => array( 'section' => 'stays', 'label' => 'Title', 'default' => 'Stay Somewhere Special' ),
		'member_label'     => array( 'section' => 'stays', 'label' => 'Member price button', 'default' => '10% Member Price' ),
		// Why book direct.
		'why_kicker'       => array( 'section' => 'why', 'label' => 'Kicker', 'default' => 'Exclusive Benefits' ),
		'why_title'        => array( 'section' => 'why', 'label' => 'Title', 'default' => 'Why Book Direct?' ),
		'why_text'         => array( 'section' => 'why', 'label' => 'Intro', 'default' => 'More value, more privileges, and a more rewarding stay - only when you book direct with GP House.', 'type' => 'textarea' ),
		// Reviews.
		'reviews_kicker'   => array( 'section' => 'reviews', 'label' => 'Kicker', 'default' => 'Authentic Experiences' ),
		'reviews_title'    => array( 'section' => 'reviews', 'label' => 'Title', 'default' => 'Guest Stories That Stay With You Forever' ),
		'reviews_text'     => array( 'section' => 'reviews', 'label' => 'Intro', 'default' => 'Reflections and memories shared by travelers who made GP House their home in Thailand.', 'type' => 'textarea' ),
		'reviews_count'    => array( 'section' => 'reviews', 'label' => 'Review count', 'default' => '128 Reviews' ),
		'reviews_note'     => array( 'section' => 'reviews', 'label' => 'Review note', 'default' => 'Based on 2K+ reviews worldwide' ),
		// Journal.
		'journal_kicker'   => array( 'section' => 'journal', 'label' => 'Kicker', 'default' => 'Editorial Chronicles' ),
		'journal_title'    => array( 'section' => 'journal', 'label' => 'Title', 'default' => 'The GP Journal' ),
		// CTA.
		'cta_kicker'       => array( 'section' => 'cta', 'label' => 'Kicker', 'default' => 'Your Thai Journey Begins Here' ),
		'cta_title'        => array( 'section' => 'cta', 'label' => 'Title', 'default' => 'Ready for your next stay?' ),
		'cta_text'         => array( 'section' => 'cta', 'label' => 'Text', 'default' => 'Reserve directly with GP House for guaranteed lowest rates, personalized arrival itineraries, and instant membership privileges.', 'type' => 'textarea' ),
		// Footer.
		'footer_text'      => array( 'section' => 'footer', 'label' => 'About text', 'default' => 'GP House captures the quiet art of Thai hospitality, curating secluded and refined stays for discerning travelers.', 'type' => 'textarea' ),
		'footer_copyright' => array( 'section' => 'footer', 'label' => 'Copyright', 'default' => '© 2026 GP House Hospitality Group Ltd. All rights reserved.' ),
		'social_facebook'  => array( 'section' => 'footer', 'label' => 'Facebook URL', 'default' => '#', 'type' => 'url' ),
		'social_instagram' => array( 'section' => 'footer', 'label' => 'Instagram URL', 'default' => '#', 'type' => 'url' ),
		'social_x'         => array( 'section' => 'footer', 'label' => 'X URL', 'default' => '#', 'type' => 'url' ),
		'social_linkedin'  => array( 'section' => 'footer', 'label' => 'LinkedIn URL', 'default' => '#', 'type' => 'url' ),
	);
}

/** The four "Why book direct" benefits (static brand copy). */
function gp_benefits() {
	return array(
		array( 'icon' => 'percent', 'title' => '10% Member Discount', 'text' => 'Save instantly on every stay as a GP House member.' ),
		array( 'icon' => 'key-round', 'title' => 'Exclusive Member Access', 'text' => 'Early access to offers, limited packages and special events.' ),
		array( 'icon' => 'shield-check', 'title' => 'Best Rate Guarantee', 'text' => 'Always the beat price when you book direct.' ),
		array( 'icon' => 'gift', 'title' => 'More Flexibility', 'text' => 'Enjoy flexible booking and personalised guest support.' ),
	);
}

/**
 * Demo content exactly as shown in Figma. Image paths are relative to assets/img.
 */
function gp_demo_content() {
	return array(
		'destinations' => array(
			array( 'slug' => 'phuket', 'name' => 'Phuket', 'card_title' => 'Phuket Beach', 'subtitle' => 'Azure shores & tropical escapes', 'image' => 'photos/dest-phuket.jpg' ),
			array( 'slug' => 'krabi', 'name' => 'Krabi', 'card_title' => 'Krabi Island', 'subtitle' => 'Pristine shores & limestone cliffs', 'image' => 'photos/dest-krabi.jpg' ),
			array( 'slug' => 'bangkok', 'name' => 'Bangkok', 'card_title' => 'Bangkok City', 'subtitle' => 'Riverside calm & Thai elegance', 'image' => 'photos/dest-bangkok.jpg' ),
			array( 'slug' => 'bali', 'name' => 'Bali', 'card_title' => 'Bali', 'subtitle' => '', 'image' => '' ),
		),
		'stays'        => array(
			array(
				'slug'        => 'gp-house-phuket-sanctuary',
				'title'       => 'GP House Phuket Sanctuary',
				'destination' => 'phuket',
				'tagline'     => 'Coastal Sanctuary',
				'excerpt'     => 'Overlooking a secluded cove on the pristine northwest coast of Phuket, GP House Sanctuary pairs understated Thai craftsmanship with sweeping Andaman Sea views.',
				'price'       => 380,
				'reviews'     => 98,
				'image'       => 'photos/stay-phuket.jpg',
				'thumb'       => 'photos/stay-phuket-thumb.jpg',
				'amenities'   => array( 'pool|Cliffside Infinity Pool', 'flower|Andaman Spa Pavilion', 'utensils|Private Beach Dining', 'utensils|Sunset Yacht Transfers' ),
				'pin'         => array( 321, 292 ),
				'pin_mobile'  => array( 213, 56 ),
				'map'         => true,
				'featured'    => true,
			),
			array(
				'slug'        => 'gp-house-krabi-karst-reserve',
				'title'       => 'GP House Krabi Karst Reserve',
				'destination' => 'krabi',
				'tagline'     => 'Karst Reserve',
				'excerpt'     => 'Immersed between vertical emerald limestone cliffs and tranquil coastal mangrove waters. Access by traditional longtail boat makes every arrival an adventure.',
				'price'       => 320,
				'reviews'     => 87,
				'image'       => 'photos/stay-krabi.jpg',
				'thumb'       => 'photos/stay-krabi-thumb.jpg',
				'amenities'   => array( 'pool|Lagoon Plunge Pools', 'flower|Mangrove Wellness Deck', 'utensils|Longtail Boat Arrival', 'utensils|Floating Breakfast' ),
				'pin'         => array( 211, 524 ),
				'pin_mobile'  => array( 126, 243 ),
				'map'         => true,
				'featured'    => true,
			),
			array(
				'slug'        => 'gp-house-bangkok-riverside',
				'title'       => 'GP House Bangkok Riverside',
				'destination' => 'bangkok',
				'tagline'     => 'Riverside Sanctuary',
				'excerpt'     => 'An architectural jewel along the peaceful Chao Phraya River. Historic colonial brickwork meets contemporary Thai minimalism, with private boat shuttles to Iconsiam and Wat Arun.',
				'price'       => 260,
				'reviews'     => 125,
				'image'       => 'photos/stay-bangkok.jpg',
				'image_main'  => 'photos/stay-bangkok-main.jpg',
				'thumb'       => 'photos/stay-bangkok-thumb.jpg',
				'amenities'   => array( 'pool|Riverfront Infinity Pool', 'flower|Michelin-Guide Thai Bistro', 'utensils|Private Riverboat Shuttle', 'utensils|Soundproof Triple Glazing' ),
				'pin'         => array( 402, 430 ),
				'pin_mobile'  => array( 242, 159 ),
				'map'         => true,
				'featured'    => true,
				'map_active'  => true,
			),
			array(
				'slug'        => 'old-town-heritage-suite',
				'title'       => 'Old Town Heritage Suite',
				'destination' => 'bangkok',
				'tagline'     => 'Heritage Suite',
				'excerpt'     => 'An expansive residence featuring a separate dining salon, private reading library, and river-facing teak balcony. Includes daily chef-prepared breakfast served in your suite.',
				'price'       => 460,
				'reviews'     => 64,
				'image'       => 'photos/stay-oldtown.jpg',
				'thumb'       => 'photos/stay-oldtown.jpg',
				'amenities'   => array(),
				'pin'         => array(),
				'map'         => false,
				'featured'    => true,
			),
		),
		'testimonials' => array(
			array( 'name' => 'Eleanor Vance', 'location' => 'London, United Kingdom', 'avatar' => 'photos/guest-1.png', 'quote' => 'The attention to detail was extraordinary. From our private catamaran arrival to sunset by the cliffside pool, every moment felt unforgettable.' ),
			array( 'name' => 'Eleanor Vance', 'location' => 'London, United Kingdom', 'avatar' => 'photos/guest-4.png', 'quote' => 'The attention to detail was extraordinary. From our private' ),
			array( 'name' => 'Eleanor Vance', 'location' => 'London, United Kingdom', 'avatar' => 'photos/guest-6.png', 'quote' => 'The attention to detail here is extraordinary. From the moment our private catamaran pulled into the bay, the pace of life completely changed.' ),
			array( 'name' => 'Eleanor Vance', 'location' => 'Couple Travelers · Stockholm, Sweden', 'avatar' => 'photos/guest-2.png', 'quote' => 'Tucked beneath limestone cliffs, GP House offered an unforgettable escape.' ),
			array( 'name' => 'Eleanor Vance', 'location' => 'London, United Kingdom', 'avatar' => 'photos/guest-3.png', 'quote' => 'The attention to detail here is extraordinary. From the moment our private catamaran pulled into the bay, the pace of life completely changed.' ),
			array( 'name' => 'Eleanor Vance', 'location' => 'London, United Kingdom', 'avatar' => 'photos/guest-7.png', 'quote' => 'The attention to detail here is extraordinary.' ),
			array( 'name' => 'Eleanor Vance', 'location' => 'London, United Kingdom', 'avatar' => 'photos/guest-3.png', 'quote' => 'Watching the sunset from our cliffside plunge pool was the highlight of our journey through Southeast Asia.' ),
			array( 'name' => 'Eleanor Vance', 'location' => 'London, United Kingdom', 'avatar' => 'photos/guest-5.png', 'quote' => 'The attention to detail here is extraordinary. From the moment our private catamaran pulled into the bay, the pace of life completely changed.' ),
			array( 'name' => 'Eleanor Vance', 'location' => 'London, United Kingdom', 'avatar' => 'photos/guest-8.png', 'quote' => 'Tucked beneath limestone cliffs, GP House offered an unforgettable escape.' ),
		),
		'journal'      => array(
			array(
				'slug'          => 'silent-shores-of-northwest-phuket',
				'title'         => 'The Silent Shores of Northwest Phuket: Where Forest Meets Tide',
				'destination'   => 'phuket',
				'date'          => '2026-08-24 09:00:00',
				'read_time'     => '6 min read',
				'excerpt'       => 'Far from crowded resort strips, a string of quiet coves preserves the wild, untamed spirit of Old Phuket with sea turtles, granite rocks, and hidden fishermen stalls.',
				'image'         => 'photos/journal-phuket.jpg',
				'card_image'    => 'destinations/phuket-article-1.jpg',
				'author_name'   => 'Napat Sirisena',
				'author_role'   => 'Local Guides',
				'author_avatar' => 'photos/author-1.png',
				'author_photo'  => 'journal/author-napat.jpg',
				'story_image'   => 'journal/story-phuket-beach.jpg',
				'topic'         => 'travel-guide',
				'sticky'        => true,
				'content'       => gp_demo_story_content( 'silent-shores' ),
				'home'          => true,
			),
			array(
				'slug'          => 'thai-massage-treatments-explained',
				'title'         => 'Thai Massage Treatments Explained: Techniques, Benefits & What to Expect',
				'destination'   => 'bangkok',
				'date'          => '2026-08-12 10:00:00',
				'read_time'     => '5 min read',
				'excerpt'       => 'When the word “massage” pops up, the scene of a dimly lit room filled with the aroma of oils and the ultimate relaxation comes in. But Thai massage treatment is more than relaxation – it is a therapeutic practice which is laced with centuries of tradition.',
				'image'         => 'photos/journal-bangkok.jpg',
				'author_name'   => 'Dr. Araya Wongsuwan',
				'author_role'   => 'Wellness',
				'author_avatar' => 'photos/author-2.png',
				'topic'         => 'culture',
				'sticky'        => true,
				'home'          => true,
			),
			array(
				'slug'          => 'exploring-krabi-caves-hot-springs',
				'title'         => 'Exploring Krabi’s Caves, Hot Springs, and Emerald Pools',
				'destination'   => 'krabi',
				'date'          => '2026-08-12 09:00:00',
				'read_time'     => '5 min read',
				'excerpt'       => 'Tucked away in southern Thailand, Krabi Caves and its surrounding postcard-perfect natural wonders act as a magnet attracting travellers from across the globe. It doesn’t matter what type of traveller you are – an adventure seeker, looking for tranquillity, or just wanting to take a glimpse into nature’s artistry, Krabi will entice you to the fullest.',
				'image'         => 'photos/journal-krabi.jpg',
				'author_name'   => 'Chatchai Boonsoong',
				'author_role'   => 'Wellness',
				'author_avatar' => 'photos/author-3.png',
				'topic'         => 'travel-guide',
				'sticky'        => true,
				'home'          => true,
			),
			array(
				'slug'          => 'secret-beaches-of-phuket',
				'title'         => 'The Secret Beaches of Phuket: Beyond the Crowds',
				'destination'   => 'phuket',
				'date'          => '2026-09-12 09:00:00',
				'read_time'     => '4 min read',
				'excerpt'       => 'Discover secluded coves, hidden viewpoints, and quiet coastal paths where Phuket’s wild beauty still waits to be found.',
				'image'         => 'destinations/phuket-hero.jpg',
				'card_image'    => 'destinations/phuket-article-2.jpg',
				'topic'         => 'travel-guide',
				'author_name'   => 'Napat Sirisena',
				'author_role'   => 'Local Guides',
				'author_avatar' => 'photos/author-1.png',
			),
			array(
				'slug'          => 'old-phuket-town-living-heritage',
				'title'         => 'Old Phuket Town: A Morning Through Living Heritage',
				'destination'   => 'phuket',
				'date'          => '2026-09-18 09:00:00',
				'read_time'     => '5 min read',
				'excerpt'       => 'Wander historic streets, colorful Sino-Portuguese shophouses, local coffee stalls, and the timeless rhythm of Old Phuket Town.',
				'image'         => 'destinations/hero-thailand.jpg',
				'card_image'    => 'destinations/phuket-article-3.jpg',
				'topic'         => 'architecture',
				'author_name'   => 'Napat Sirisena',
				'author_role'   => 'Local Guides',
				'author_avatar' => 'photos/author-1.png',
			),
		),
	);
}

/** Let the GP House Core plugin import the demo content. */
add_filter( 'gp_house_demo_content', function () {
	$content         = gp_demo_content();
	$content['base']  = GP_HOUSE_DIR . '/assets/img/';
	$content['regions'] = function_exists( 'gp_demo_regions' ) ? gp_demo_regions() : array();
	$content['destination_pages'] = function_exists( 'gp_demo_destination_pages' ) ? gp_demo_destination_pages() : array();
	$content['pages']   = array( 'destinations' => 'Destinations', 'offers' => 'Offers', 'club' => 'Club', 'journal' => 'Journal', 'about' => 'About', 'investor-relations' => 'Investor Relations', 'self-check-in' => 'Late Night Self Check-In', 'profile' => 'Profile', 'sign-in' => 'Sign In', 'join' => 'Join GP House Club', 'forgot-password' => 'Forgot Password', 'reset-password' => 'Reset Password', 'verify-email' => 'Verify Email', 'contact' => 'Contact & Concierge', 'booking-policies' => 'Booking Policies', 'terms' => 'Terms of Service', 'sustainability' => 'Sustainability & Craft', 'experiences' => 'Curated Experiences', 'sitemap' => 'Interactive Sitemap' );
	$content['page_content'] = array(
		'booking-policies' => '<h2>Direct booking</h2><p>Reservations made on this website are 100% prepaid in Thai baht and confirmed instantly by email. Members enjoy the lowest public rate plus their member discount.</p><h2>Check-in and check-out</h2><p>Check-in is from 2:00 PM and check-out is until 12:00 noon. Late check-out and early arrival can be requested through our concierge and are subject to availability. Late-night arrivals (11 PM to 6 AM) can use Late Night Self Check-In.</p><h2>Changes and cancellations</h2><p>Flexible rates can be changed or cancelled free of charge up to 24 hours before arrival. Promotional rates may be non-refundable; the conditions are shown before you pay.</p><h2>Children, pets and extra guests</h2><p>Please contact the concierge before arrival so that we can prepare your room.</p><p><em>This is a starter text — replace it with your final policy in Pages → Booking Policies.</em></p>',
		'terms' => '<h2>Using this website</h2><p>By using this website you agree to these terms. Content is provided for information about GP House sanctuaries and may change without notice.</p><h2>Bookings and payments</h2><p>A booking is confirmed once payment is received and a confirmation email has been sent. Prices are shown in the currency you select and charged in Thai baht.</p><h2>Member accounts</h2><p>Keep your sign-in details private. Points have no cash value and may be changed or withdrawn as described in the GP House Club conditions.</p><h2>Privacy</h2><p>We use your details only to manage your stay and membership and to reply to your messages.</p><p><em>This is a starter text — replace it with your final terms in Pages → Terms of Service.</em></p>',
		'sustainability' => '<p>GP House sanctuaries are small by design — 24 to 36 private pavilions — built from reclaimed teak, local stone and natural airflow.</p><h2>Local first</h2><p>Around 85% of our ingredients and textiles come from family-run farms, studios and fishing communities near each property.</p><h2>Craft that lasts</h2><p>We restore rather than replace: over 120 historic teak pillars are preserved across our three sanctuaries.</p><p><em>Starter text — add your sustainability story in Pages → Sustainability & Craft.</em></p>',
		'experiences' => '<p>From catamaran cruises at sunset to herbal compress spa rituals and private candlelit dinners, our concierge curates days around the place you are staying in.</p><h2>Popular with guests</h2><ul><li>Sunset wooden catamaran cruise for two</li><li>90-minute signature herbal compress spa</li><li>Chef-prepared four-course degustation dinner</li><li>Private airport or boat transfer</li></ul><p>Ask the concierge through the Contact & Concierge page, or redeem points on your member dashboard.</p>',
	);
	$content['journal_topics'] = gp_journal_default_topics();
	$content['offers']  = function_exists( 'gp_demo_offers' ) ? gp_demo_offers() : array();
	$content['rooms'] = function_exists( 'gp_demo_rooms' ) ? array_map( function ( $r ) {
		return $r + gp_demo_room_details( $r );
	}, gp_demo_rooms() ) : array();
	return $content;
} );

/** Journal topics (WordPress categories) in Figma tab order. */
function gp_journal_default_topics() {
	return array(
		'architecture' => 'Architecture',
		'wellness'     => 'Wellness',
		'culture'      => 'Culture',
		'culinary'     => 'Culinary',
		'travel-guide' => 'Travel Guide',
	);
}

/**
 * Demo story bodies (Figma "Journal / Overview"). {{img:path}} is replaced
 * with the imported image URL by the GP House Core importer.
 */
function gp_demo_story_content( $key ) {
	$stories = array(
		'silent-shores' => <<<'HTML'
<!-- wp:quote -->
<blockquote class="wp-block-quote"><p>To know Phuket is to understand its duality: the energetic rhythm of its western beaches and the meditative stillness of its protected coastal reserves where the Andaman whispers against untouched granite rocks.</p></blockquote>
<!-- /wp:quote -->

<!-- wp:paragraph -->
<p>Before the advent of international tourism, Phuket was an island of tin barons, rubber plantations, and coconut groves. The northwest corner remains an acoustic haven where coastal pine trees shade secluded sandy beaches that shelter nesting sea turtles during the quiet monsoon months.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>At GP House Phuket Sanctuary, mornings begin not with engines, but with the cadence of Andaman breakers against the sea wall and the calls of crested serpent eagles soaring above the forested ridge.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Wandering just twenty minutes by foot along the coastal headland reveals a series of tidal pools cut into ancient volcanic stone. Here, local fishermen still cast hand-thrown nets at low tide, harvesting wild sea bream and blue swimmer crabs.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">The Ancient Granite Formations<br>of Cherngtalay</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Geologists estimate these cliff formations to be over 120 million years old. At twilight, the porous stone absorbs the setting sun, glowing in hues of deep amber and terracotta against the deep navy ocean.</p>
<!-- /wp:paragraph -->

<!-- wp:image -->
<figure class="wp-block-image"><img src="{{img:journal/story-pier.jpg}}" alt="Wooden pier over turquoise water"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">A Table for Two at Water’s Edge</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>When dusk settles, our culinary team sources wild Andaman snapper directly from the morning catch, charcoal-grilled simply over dried coconut husks and seasoned with sea salt crystals dried in traditional salt pans.</p>
<!-- /wp:paragraph -->
HTML,
	);
	return $stories[ $key ] ?? '';
}