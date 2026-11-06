<?php
/**
 * Booking itinerary dialog — "Concierge Services" (Figma 1148:9944).
 *
 * @var array $args { booking: array (gp_profile_bookings() shape) }
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$b  = $args['booking'];
$id = 'gp-it-' . sanitize_key( $b['id'] );

// Calendar file for "Add To Calendar (.ics)".
$ics = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//GP House//Stay//EN\r\nBEGIN:VEVENT\r\nUID:" . $b['id'] . "@gphouse\r\nDTSTART;VALUE=DATE:" . gmdate( 'Ymd', strtotime( $b['check_in'] ) ) . "\r\nDTEND;VALUE=DATE:" . gmdate( 'Ymd', strtotime( $b['check_out'] ) ) . "\r\nSUMMARY:" . $b['title'] . "\r\nEND:VEVENT\r\nEND:VCALENDAR\r\n";
?>
<dialog class="gp-it" id="<?php echo esc_attr( $id ); ?>" aria-labelledby="<?php echo esc_attr( $id ); ?>-title">
	<div class="gp-it__panel" tabindex="-1" autofocus>
		<header class="gp-it__head">
			<h2 id="<?php echo esc_attr( $id ); ?>-title"><?php esc_html_e( 'Concierge Services', 'gp-house' ); ?></h2>
			<button class="gp-it__close" type="button" data-itinerary-close aria-label="<?php esc_attr_e( 'Close', 'gp-house' ); ?>"><?php gp_icon( 'close-navy', 24 ); ?></button>
		</header>

		<div class="gp-it__grid">
			<div class="gp-it__col">
				<section class="gp-it__box gp-it__ref">
					<div>
						<h3><?php esc_html_e( 'Reservation Itinerary', 'gp-house' ); ?></h3>
						<p><?php echo esc_html( sprintf( /* translators: %s: confirmation code */ __( 'Confirmation Code:%s', 'gp-house' ), $b['id'] ) ); ?></p>
					</div>
					<span class="gp-bk__status is-<?php echo esc_attr( $b['status'] ); ?>"><?php echo esc_html( gp_profile_status_label( $b['status'] ) ); ?></span>
				</section>

				<dl class="gp-it__box gp-it__dates">
					<div><dt><?php echo esc_html( $b['check_in'] ); ?></dt><dd><?php esc_html_e( 'Check-In Date', 'gp-house' ); ?></dd></div>
					<div><dt><?php echo esc_html( $b['check_out'] ); ?></dt><dd><?php esc_html_e( 'Check-Out Date', 'gp-house' ); ?></dd></div>
					<div><dt><?php echo esc_html( sprintf( /* translators: %d: nights */ _n( '%d Night', '%d Nights', (int) $b['nights'], 'gp-house' ), (int) $b['nights'] ) ); ?></dt><dd><?php esc_html_e( 'Duration', 'gp-house' ); ?></dd></div>
					<div><dt><?php echo esc_html( preg_replace( '/Guests?$/', 'Adults', $b['guests'] ) ); ?></dt><dd><?php esc_html_e( 'All Guests Registered', 'gp-house' ); ?></dd></div>
				</dl>

				<section class="gp-it__box gp-it__perks">
					<h3><?php esc_html_e( 'Reservation Itinerary', 'gp-house' ); ?></h3>
					<ul>
						<?php foreach ( $b['perks'] as $perk ) : ?>
							<li><?php gp_icon( 'check-circle-green', 16 ); ?><span><?php echo esc_html( $perk ); ?></span></li>
						<?php endforeach; ?>
						<li><?php gp_icon( 'check-circle-green', 16 ); ?><span><?php echo esc_html( sprintf( /* translators: %s: promo code */ __( 'Applied Promotional Benefit: Code %s Verified', 'gp-house' ), $b['code'] ) ); ?></span></li>
					</ul>
				</section>

				<section class="gp-it__box gp-it__desk">
					<h3><?php echo esc_html( sprintf( /* translators: %s: hotel */ __( 'Concierge Desk — %s', 'gp-house' ), $b['concierge'] ) ); ?></h3>
					<p><?php esc_html_e( 'Need A Transfer, Ride, Or Private Dining?', 'gp-house' ); ?></p>
					<a href="mailto:<?php echo esc_attr( $b['email'] ); ?>"><?php gp_icon( 'mail-grey', 16 ); ?><?php echo esc_html( $b['email'] ); ?></a>
					<a href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', $b['phone'] ) ); ?>"><?php gp_icon( 'phone-grey', 16 ); ?><?php echo esc_html( $b['phone'] ); ?></a>
				</section>
			</div>

			<div class="gp-it__col gp-it__col--side">
				<section class="gp-it__box gp-it__rate">
					<h3><?php esc_html_e( 'Rate Breakdown', 'gp-house' ); ?></h3>
					<div class="gp-it__total"><span><?php esc_html_e( 'Total Paid In Full', 'gp-house' ); ?></span><strong><?php echo esc_html( $b['total'] ); ?></strong></div>
					<p class="gp-it__note"><?php esc_html_e( 'Direct Best-Rate Guaranteed • Fully Paid & Secured.', 'gp-house' ); ?></p>
					<dl>
						<div><dt><?php echo esc_html( sprintf( /* translators: %d: nights */ __( 'Accommodation (%d Nights):', 'gp-house' ), (int) $b['nights'] ) ); ?></dt><dd><?php echo esc_html( $b['accommodation'] ); ?></dd></div>
						<div><dt><?php esc_html_e( 'Resort Service Fee (10%', 'gp-house' ); ?></dt><dd><?php esc_html_e( 'Included', 'gp-house' ); ?></dd></div>
						<div><dt><?php esc_html_e( 'Provincial VAT (7%)', 'gp-house' ); ?></dt><dd><?php esc_html_e( 'Included', 'gp-house' ); ?></dd></div>
					</dl>
				</section>

				<section class="gp-it__box gp-it__handy">
					<h3><?php esc_html_e( 'Keep Your Reservation Handy', 'gp-house' ); ?></h3>
					<p><?php esc_html_e( 'Save Or Print Your Itinerary Anytime.', 'gp-house' ); ?></p>
					<a class="gp-it__btn" href="data:text/calendar;charset=utf-8,<?php echo esc_attr( rawurlencode( $ics ) ); ?>" download="<?php echo esc_attr( sanitize_file_name( $b['id'] ) ); ?>.ics"><?php esc_html_e( 'Add To Calendar (.Ics)', 'gp-house' ); ?></a>
					<button class="gp-it__btn" type="button" data-print><?php esc_html_e( 'Print Itinerary', 'gp-house' ); ?></button>
				</section>
			</div>
		</div>
	</div>
</dialog>
