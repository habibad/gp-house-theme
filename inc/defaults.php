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
				'author_name'   => 'Napat Sirisena',
				'author_role'   => 'Local Guides',
				'author_avatar' => 'photos/author-1.png',
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
			),
		),
	);
}

/** Let the GP House Core plugin import the demo content. */
add_filter( 'gp_house_demo_content', function () {
	$content         = gp_demo_content();
	$content['base'] = GP_HOUSE_DIR . '/assets/img/';
	return $content;
} );
