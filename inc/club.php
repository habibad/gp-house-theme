<?php
/**
 * Club page (Figma "Hero /Club", 1148:7818; FAQs 1148:7805; mobile 1455:14304).
 *
 * Marketing copy lives here as filterable defaults (`gp_house_club_content`);
 * headings are editable in the Customizer (GP House → Club page).
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

/**
 * Club rules (tiers with minimum qualifying points and points per US$1, welcome bonus, credit timing) —
 * from the booking plugin's settings when it is active, otherwise the design defaults.
 */
function gp_club_rules() {
	if ( function_exists( 'gp_club_settings' ) ) {
		return gp_club_settings();
	}
	return array(
		'tiers'       => array(
			'silver'   => array( 'name' => 'Silver Explorer', 'short' => 'Silver', 'min' => 0, 'rate' => 1 ),
			'gold'     => array( 'name' => 'Gold Connoisseur', 'short' => 'Gold', 'min' => 2500, 'rate' => 1.5 ),
			'platinum' => array( 'name' => 'Platinum Patron', 'short' => 'Platinum', 'min' => 5000, 'rate' => 2 ),
		),
		'welcome'     => 500,
		'credit'      => 'checkout',
		'window_days' => 365,
	);
}

/** "1", "1.5", "2". */
function gp_club_rate_text( $rate ) {
	return rtrim( rtrim( number_format( (float) $rate, 2, '.', '' ), '0' ), '.' );
}

/**
 * Points per US$ for the Interactive Calculator: the member's own tier rate when signed in,
 * otherwise the top tier rate (Figma: 4 nights × $380 = +3,040 at 2 points per $1).
 */
function gp_club_points_per_dollar() {
	$rates = wp_list_pluck( gp_club_rules()['tiers'], 'rate' );
	$rate  = $rates ? max( $rates ) : 2;
	if ( is_user_logged_in() && function_exists( 'gp_club_tier_of' ) ) {
		$rate = (float) gp_club_tier_of( get_current_user_id() )['rate'];
	}
	return (float) apply_filters( 'gp_house_club_points_per_dollar', $rate );
}

/** Points page line under the balance. */
function gp_club_earn_note( array $member ) {
	if ( ! empty( $member['is_demo'] ) || empty( $member['rate'] ) ) {
		return __( 'Earn 10 Points For Every $1 Spent On Accommodations, Dining, And Spa Experiences.', 'gp-house' );
	}
	/* translators: 1: points per US$1, 2: tier */
	return sprintf( __( 'As A %2$s Member You Earn %1$s Points For Every $1 Spent On Direct Stays.', 'gp-house' ), gp_club_rate_text( $member['rate'] ), $member['tier'] );
}

/** All Club page lists (tier points, earning rates and the earn / welcome texts follow the Club rules). */
function gp_club_content() {
	return apply_filters( 'gp_house_club_content', gp_club_apply_rules( gp_club_content_defaults() ) );
}

/** Put the live Club rules into the Club page copy. */
function gp_club_apply_rules( array $c ) {
	$rules = gp_club_rules();
	$tiers = array_values( $rules['tiers'] );
	$rates = wp_list_pluck( $tiers, 'rate' );
	$range = gp_club_rate_text( min( $rates ) ) . ( min( $rates ) !== max( $rates ) ? '–' . gp_club_rate_text( max( $rates ) ) : '' );
	foreach ( $c['tiers'] as $i => &$card ) {
		if ( ! isset( $tiers[ $i ] ) ) {
			continue;
		}
		$t             = $tiers[ $i ];
		$next          = $tiers[ $i + 1 ] ?? null;
		$card['name']  = $t['name'];
		$card['points'] = $next ? number_format_i18n( max( 0, (int) $next['min'] - 1 ) ) . ' Pts' : number_format_i18n( (int) $t['min'] ) . '+ Pts';
		/* translators: %s: points */
		$card['earning'] = array( sprintf( _n( 'Earning: %s Point', 'Earning: %s Points', (float) $t['rate'] > 1 ? 2 : 1, 'gp-house' ), gp_club_rate_text( $t['rate'] ) ), __( 'Per $1 Spent', 'gp-house' ) );
	}
	unset( $card );
	/* translators: %s: welcome points */
	$c['steps'][0]['text'] = sprintf( __( 'Create your free account and receive %s welcome points instantly.', 'gp-house' ), number_format_i18n( (int) $rules['welcome'] ) );
	/* translators: %s: points range, e.g. 1–2 */
	$c['steps'][2]['text'] = sprintf( __( 'Earn %s points per $1 spent on direct stays, depending on your tier.', 'gp-house' ), $range );
	$c['faqs'][0][1] = sprintf(
		/* translators: 1: points range, 2: when points are credited */
		__( 'Earn %1$s points per $1 on direct stays, depending on your tier. %2$s', 'gp-house' ),
		$range,
		'confirm' === $rules['credit'] ? __( 'Points are added as soon as your booking is confirmed.', 'gp-house' ) : __( 'Points are added after checkout and show as pending until then.', 'gp-house' )
	);
	/* translators: %d: months */
	$c['faqs'][3][1] = sprintf( __( 'Your tier follows the stay points you earned in the last %d months, and updates automatically after every stay.', 'gp-house' ), max( 1, (int) round( (int) $rules['window_days'] / 30.4 ) ) );
	return $c;
}

/** Club page lists as designed (Figma copy). */
function gp_club_content_defaults() {
	return apply_filters( 'gp_house_club_content_defaults', array(
		'steps'        => array(
			array( 'num' => '01', 'title' => 'Sign Up', 'text' => 'Create your free account and receive 500 welcome points instantly.', 'link' => 'Active Member', 'url' => '', 'done' => true, 'image' => 'club/step-1.jpg' ),
			array( 'num' => '02', 'title' => 'Book', 'text' => 'Book direct with 10% off, priority check-in, and flexible check-out.', 'link' => 'Book Member Rate', 'url' => '/stays/', 'done' => false, 'image' => 'club/step-2.jpg' ),
			array( 'num' => '03', 'title' => 'Earn', 'text' => 'Earn 10–20 points per $1 spent on stays, dining, and spa experiences.', 'link' => 'Calculate Points', 'url' => '#calculator', 'done' => false, 'image' => 'club/step-3.jpg' ),
			array( 'num' => '04', 'title' => 'Redeem', 'text' => 'Free stays, catamaran cruises, spa rituals and more member rewards.', 'link' => 'View Rewards', 'url' => '#redemptions', 'done' => false, 'image' => 'club/step-4.jpg' ),
		),
		'benefits'     => array(
			array( 'icon' => 'percent-navy', 'title' => '10% Member Savings', 'text' => 'Members enjoy the lowest public rate plus 10% off suites and pool villas.' ),
			array( 'icon' => 'clock-navy', 'title' => 'Late-Night Self Check-In', 'text' => 'Enter your booking ID to unlock self check-in details after 11 PM.' ),
			array( 'icon' => 'sparkle-navy', 'title' => 'Thai Heritage Welcome', 'text' => 'Herbal infusions, garlands, and seasonal Thai fruits on arrival.' ),
			array( 'icon' => 'gift-navy', 'title' => 'Annual Spa Credits', 'text' => 'Up to $100 in annual complimentary spa vouchers.' ),
			array( 'icon' => 'plane', 'title' => 'VIP Transfers', 'text' => 'Chauffeured airport sedans or private catamaran transfers.' ),
			array( 'icon' => 'shield-check-navy', 'title' => 'No Blackout Dates', 'text' => 'Redeem points anytime, including peak and festive periods' ),
		),
		'tiers'        => array(
			array(
				'tagline' => 'The Gateway To Siamese Boutique Luxury',
				'name'    => 'Silver Explorer',
				'perks'   => array( 'Best Rate + 10% Member Discount', 'Thai Welcome Drink & Herbal Towel', '24-Hour Flexible Cancellation', 'Complimentary High-Speed Wi-Fi', '10% Dining Discount', 'In-Room Thai Fruit & Botanical Tea' ),
				'points'  => '2,499 Pts',
				'earning' => array( 'Earning: 1 Point', 'Per $1 Spent' ),
				'button'  => 'Book With Silver Rate',
				'image'   => 'club/tier-silver.jpg',
			),
			array(
				'tagline'  => 'Enhanced Privileges & Room Upgrades',
				'name'     => 'Gold Connoisseur',
				'perks'    => array( 'All Silver Benefits', '1 PM Early & 2 PM Late Check-Out', 'Complimentary Room Upgrade', '$100 Annual Spa Voucher', '15% Dining Discount', 'Priority Boat Reservations', 'Personalized Pillow & Aromatherapy' ),
				'points'   => '4,999 Pts',
				'earning'  => array( 'Earning: 1.5 Points', 'Per $1 Spent' ),
				'button'   => 'Book With Gold Rate',
				'image'    => 'club/tier-gold.jpg',
				'featured' => true,
			),
			array(
				'tagline' => 'The Pinnacle Of Bespoke Hospitality',
				'name'    => 'Platinum Patron',
				'perks'   => array( 'All Gold Benefits', '24/7 Personal Host & Butler', 'Guaranteed Suite Or Villa Upgrade', 'Complimentary Airport Or Boat Transfer', 'Unlimited Garment Pressing', '20% Dining Discount', 'Annual Founders’ Wine Gala Invitation', 'No Blackout Dates For Rewards' ),
				'points'  => '5,000+ Pts',
				'earning' => array( 'Earning: 2.0 Points', 'Per $1 Spent' ),
				'button'  => 'Book With Platinum Rate',
				'image'   => 'club/tier-platinum.jpg',
			),
		),
		'rewards'      => array(
			array( 'One-Tier Room Upgrade', 600 ),
			array( '90-Min Herbal Spa Ritual', 800 ),
			array( '4-Course Degustation', 1200 ),
			array( 'Complimentary Free Night', 2200 ),
		),
		'redemptions'  => array(
			array( 'chip' => 'Phuket', 'title' => '90-Minute Signature Herbal Compress Spa', 'text' => 'Traditional heated herbal poultice massage by senior practitioners.', 'points' => 800, 'image' => 'club/redeem-spa.jpg' ),
			array( 'chip' => 'Dining', 'title' => 'Chef-Prepared 4-Course Degustation Dinner', 'text' => 'Private candlelit dinner featuring local organic seafood and herbs.', 'points' => 1200, 'image' => 'club/redeem-dining.jpg' ),
			array( 'chip' => 'Upgrade', 'title' => 'One Category Room Upgrade', 'text' => 'Confirmed upgrade to next room or villa tier upon reservation.', 'points' => 600, 'image' => 'club/redeem-upgrade.jpg' ),
			array( 'chip' => 'Stay', 'title' => 'Complimentary Night Stay', 'text' => 'Redeem one full night in a pavilion or suite of your choice.', 'points' => 2200, 'image' => 'club/redeem-stay.jpg' ),
		),
		'faqs'         => array(
			array( 'How do I earn GP House Club points?', 'Earn 1–2 points per $1 on rooms, spa & dining. Credited within 24 hours after direct-booking checkout.' ),
			array( 'Do my points expire?', 'Points expire after 24 months of inactivity. Each qualifying stay extends them 24 months.' ),
			array( 'How do I redeem my points?', 'Redeem via Member Portal > Points & Rewards for stays, spa, dining, or checkout.' ),
			array( 'How is my tier status decided?', 'Tier status is based on yearly qualifying points and lasts through the following year.' ),
			array( 'Can I share points with family?', 'Gold & Platinum members can transfer 5,000 points yearly to family members sharing reservations.' ),
		),
	) );
}

/**
 * Destinations offered in the calculator: name, slug and the lowest nightly
 * stay price there (Figma: Phuket $380, Krabi $320, Bangkok $260).
 */
function gp_club_calculator_destinations() {
	$out = array();
	foreach ( gp_get_stays() as $stay ) {
		$slug = sanitize_title( $stay['location'] );
		if ( ! $slug || $stay['price'] <= 0 ) {
			continue;
		}
		if ( ! isset( $out[ $slug ] ) || $stay['price'] < $out[ $slug ]['price'] ) {
			$out[ $slug ] = array( 'name' => $stay['location'], 'slug' => $slug, 'price' => (float) $stay['price'] );
		}
	}
	$order = array( 'phuket' => 0, 'krabi' => 1, 'bangkok' => 2 );
	uasort( $out, fn( $a, $b ) => ( $order[ $a['slug'] ] ?? 9 ) <=> ( $order[ $b['slug'] ] ?? 9 ) );
	return apply_filters( 'gp_house_club_calculator_destinations', array_slice( array_values( $out ), 0, 3 ) );
}

/** Club page swaps the footer call-to-action (Figma "Member Privilege"). */
add_filter( 'gp_house_footer_cta', function ( $cta ) {
	if ( ! is_page( 'club' ) ) {
		return $cta;
	}
	return array(
		'kicker'  => gp_opt( 'club_cta_kicker' ),
		'title'   => gp_opt( 'club_cta_title' ),
		'text'    => gp_opt( 'club_cta_text' ),
		'class'   => 'gp-cta--club',
		'buttons' => array(
			array( 'label' => __( 'Reserve A Stay', 'gp-house' ), 'url' => add_query_arg( 'promo', 'MEMBERDIRECT', home_url( '/stays/' ) ), 'size' => 'tab' ),
			array( 'label' => __( 'Member Dashboard', 'gp-house' ), 'url' => home_url( '/profile/' ), 'size' => 'tab', 'variant' => 'outline', 'text' => 'navy' ),
		),
	);
} );
