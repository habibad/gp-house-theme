<?php
/**
 * Booking confirmation (/book/?ref=…).
 *
 * @var array $args { booking: array (gp_booking_get() shape) }
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$b  = $args['booking'];
$p  = gp_booking_property( $b['property'] ) ?: array();
$st = $b['status'];
$ics = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//GP House//Stay//EN\r\nBEGIN:VEVENT\r\nUID:" . $b['ref'] . "@gphouse\r\nDTSTART;VALUE=DATE:" . gmdate( 'Ymd', strtotime( $b['checkin'] ) ) . "\r\nDTEND;VALUE=DATE:" . gmdate( 'Ymd', strtotime( $b['checkout'] ) ) . "\r\nSUMMARY:" . $b['hotel'] . "\r\nEND:VEVENT\r\nEND:VCALENDAR\r\n";
$rows = array(
	__( 'Property', 'gp-house' )     => $b['hotel'],
	__( 'Room', 'gp-house' )         => $b['room'] . ( $b['rate'] && $b['rate'] !== $b['room'] ? ' — ' . $b['rate'] : '' ),
	__( 'Check-in', 'gp-house' )     => wp_date( 'D, j M Y', strtotime( $b['checkin'] ) ) . ( ! empty( $p['checkin_time'] ) ? ' · ' . $p['checkin_time'] : '' ),
	__( 'Check-out', 'gp-house' )    => wp_date( 'D, j M Y', strtotime( $b['checkout'] ) ) . ( ! empty( $p['checkout_time'] ) ? ' · ' . $p['checkout_time'] : '' ),
	__( 'Guests', 'gp-house' )       => sprintf( /* translators: 1: adults, 2: children */ __( '%1$d adults, %2$d children', 'gp-house' ), $b['adults'], $b['children'] ),
	__( 'Total paid', 'gp-house' )   => gp_booking_money( $b['total'], $b['currency'] ),
);
?>
<section class="gp-book-done">
	<?php if ( 'confirmed' === $st || 'cancel_requested' === $st ) : ?>
		<span class="gp-book-done__icon"><?php gp_icon( 'check-circle-navy', 40 ); ?></span>
		<h1 class="gp-book__title"><?php esc_html_e( 'Your Stay Is Confirmed', 'gp-house' ); ?></h1>
		<p class="gp-book__lead"><?php echo esc_html( sprintf( /* translators: %s: email */ __( 'A confirmation was sent to %s.', 'gp-house' ), $b['email'] ) ); ?></p>
	<?php elseif ( 'cancelled' === $st ) : ?>
		<h1 class="gp-book__title"><?php esc_html_e( 'This Booking Was Cancelled', 'gp-house' ); ?></h1>
	<?php elseif ( 'pending' === $st ) : ?>
		<h1 class="gp-book__title"><?php esc_html_e( 'Waiting For Payment', 'gp-house' ); ?></h1>
		<p class="gp-book__lead"><?php esc_html_e( 'We are holding your room. Complete the payment to confirm it.', 'gp-house' ); ?></p>
	<?php else : ?>
		<h1 class="gp-book__title"><?php esc_html_e( 'Booking Not Completed', 'gp-house' ); ?></h1>
		<p class="gp-book__lead"><?php esc_html_e( 'The payment was not completed, so no booking was made and you were not charged.', 'gp-house' ); ?></p>
	<?php endif; ?>

	<div class="gp-book-done__card">
		<p class="gp-book-done__ref"><span><?php esc_html_e( 'Booking reference', 'gp-house' ); ?></span><strong><?php echo esc_html( $b['ref'] ); ?></strong></p>
		<dl>
			<?php foreach ( $rows as $label => $value ) : ?>
				<div><dt><?php echo esc_html( $label ); ?></dt><dd><?php echo esc_html( $value ); ?></dd></div>
			<?php endforeach; ?>
		</dl>
		<?php if ( 'confirmed' === $st ) : ?>
			<p class="gp-book-done__note"><?php echo esc_html( sprintf( /* translators: %d: hours */ $b['nonrefundable'] ? __( 'This rate is non-refundable.', 'gp-house' ) : __( 'Free cancellation up to %d hours before arrival — you can request it from My Booking.', 'gp-house' ), (int) gp_booking_option( 'cancel_hours', 48 ) ) ); ?></p>
		<?php endif; ?>
	</div>

	<div class="gp-book__actions">
		<?php gp_button( array( 'label' => __( 'View My Bookings', 'gp-house' ), 'url' => home_url( '/profile/bookings/' ), 'size' => 'tab' ) ); ?>
		<?php if ( in_array( $st, array( 'confirmed', 'cancel_requested' ), true ) ) : ?>
			<a class="gp-btn gp-btn--outline gp-btn--tab gp-btn--navy-text" href="data:text/calendar;charset=utf-8,<?php echo esc_attr( rawurlencode( $ics ) ); ?>" download="<?php echo esc_attr( $b['ref'] ); ?>.ics"><?php esc_html_e( 'Add To Calendar', 'gp-house' ); ?></a>
		<?php else : ?>
			<?php gp_button( array( 'label' => __( 'Try Again', 'gp-house' ), 'url' => home_url( '/book/' ), 'size' => 'tab', 'variant' => 'outline', 'text' => 'navy' ) ); ?>
		<?php endif; ?>
	</div>
</section>
