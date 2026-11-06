<?php
/**
 * Profile → Account Overview (Figma 1148:9671, mobile 1454:10222).
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$m        = gp_profile_user();
$sections = gp_profile_sections();
$upcoming = count( array_filter( gp_profile_bookings(), fn( $b ) => 'confirmed' === $b['status'] ) );
?>
<div class="gp-pf-cards">
	<article class="gp-pf-card gp-pf-card--tier">
		<span class="gp-pf-card__icon"><?php gp_icon( 'award-navy', 24 ); ?></span>
		<div class="gp-pf-card__body">
			<h2><?php echo esc_html( sprintf( /* translators: %s: next tier */ __( 'Silver To %s Tier Status', 'gp-house' ), $m['next_tier'] ) ); ?></h2>
			<p><?php echo esc_html( sprintf( /* translators: 1: points earned, 2: points to next tier, 3: tier name */ __( '%1$s Points Earned This Year. Earn %2$s More To Unlock %3$s Member Perks.', 'gp-house' ), number_format_i18n( $m['earned'] ), number_format_i18n( $m['to_next'] ), $m['next_tier'] ) ); ?></p>
		</div>
		<div class="gp-pf-progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?php echo (int) $m['progress']; ?>">
			<span class="gp-pf-progress__bar"><i style="width:<?php echo (int) $m['progress']; ?>%"></i></span>
			<span class="gp-pf-progress__text"><?php echo esc_html( sprintf( /* translators: %d: percent */ __( '%d%% Achieved', 'gp-house' ), (int) $m['progress'] ) ); ?></span>
		</div>
	</article>

	<a class="gp-pf-card gp-pf-card--link" href="<?php echo esc_url( $sections['points']['url'] ); ?>">
		<span class="gp-pf-card__icon"><?php gp_icon( 'gift-navy', 24 ); ?></span>
		<div class="gp-pf-card__body">
			<h2><?php esc_html_e( 'Redeem Rewards', 'gp-house' ); ?></h2>
			<p><?php echo esc_html( sprintf( /* translators: %s: points */ __( '%s Points Ready For Spa, Dining, Or Transfers.', 'gp-house' ), number_format_i18n( $m['points'] ) ) ); ?></p>
		</div>
	</a>

	<a class="gp-pf-card gp-pf-card--link" href="<?php echo esc_url( $sections['bookings']['url'] ); ?>">
		<span class="gp-pf-card__icon"><?php gp_icon( 'calendar-grid-navy', 24 ); ?></span>
		<span class="gp-pf-card__more"><?php esc_html_e( 'View Reservations', 'gp-house' ); ?><?php gp_icon( 'chevron-right-navy', 16 ); ?></span>
		<div class="gp-pf-card__body">
			<h2><?php esc_html_e( 'Active Bookings', 'gp-house' ); ?></h2>
			<p><?php echo esc_html( $upcoming ? __( 'You Have Upcoming Sanctuary Stays Confirmed In Thailand.', 'gp-house' ) : __( 'No Upcoming Stays Yet — Your Next Sanctuary Is One Booking Away.', 'gp-house' ) ); ?></p>
		</div>
	</a>

	<a class="gp-pf-card gp-pf-card--link" href="<?php echo esc_url( $sections['preferences']['url'] ); ?>">
		<span class="gp-pf-card__icon"><?php gp_icon( 'gear-navy', 24 ); ?></span>
		<span class="gp-pf-card__more"><?php esc_html_e( 'Update Profile', 'gp-house' ); ?><?php gp_icon( 'chevron-right-navy', 16 ); ?></span>
		<div class="gp-pf-card__body">
			<h2><?php esc_html_e( 'Hospitality Preferences', 'gp-house' ); ?></h2>
			<p><?php esc_html_e( 'Pillow, Dietary & Arrival Preferences Saved.', 'gp-house' ); ?></p>
		</div>
	</a>
</div>
