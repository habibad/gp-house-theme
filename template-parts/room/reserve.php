<?php
/**
 * Room page → inline reservation (opens from "Reserve this Suite"): dates and guests, rate plan,
 * guest details (sign-in required), PayPal. Driven by assets/js/booking.js; the booking plugin does the work.
 *
 * @var array $args { room: array }
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$room     = $args['room'];
$property = function_exists( 'gp_booking_room_property' ) ? gp_booking_room_property( $room['id'] ) : '';
$tomorrow = gmdate( 'Y-m-d', strtotime( '+1 day', current_time( 'timestamp' ) ) );
$max      = max( 2, (int) ( $room['max_guests'] ?? 2 ) );
?>
<section class="gp-reserve" id="reserve" data-reserve data-room="<?php echo (int) $room['id']; ?>" data-property="<?php echo esc_attr( $property ); ?>" hidden>
	<div class="gp-reserve__inner">
		<header class="gp-reserve__head">
			<h2><?php esc_html_e( 'Reserve This Suite', 'gp-house' ); ?></h2>
			<p><?php echo esc_html( $room['title'] ); ?></p>
		</header>

		<form class="gp-reserve__stay" data-stay-form novalidate>
			<label class="gp-book__field"><span><?php esc_html_e( 'Check-in', 'gp-house' ); ?></span><input type="date" name="checkin" value="<?php echo esc_attr( $tomorrow ); ?>" min="<?php echo esc_attr( gmdate( 'Y-m-d', current_time( 'timestamp' ) ) ); ?>" required></label>
			<label class="gp-book__field"><span><?php esc_html_e( 'Check-out', 'gp-house' ); ?></span><input type="date" name="checkout" readonly tabindex="-1"></label>
			<label class="gp-book__field"><span><?php esc_html_e( 'Adults', 'gp-house' ); ?></span>
				<select name="adults"><?php for ( $i = 1; $i <= $max; $i++ ) : ?><option value="<?php echo (int) $i; ?>" <?php selected( 2, $i ); ?>><?php echo (int) $i; ?></option><?php endfor; ?></select>
			</label>
			<label class="gp-book__field"><span><?php esc_html_e( 'Children', 'gp-house' ); ?></span>
				<select name="children"><?php for ( $i = 0; $i <= 3; $i++ ) : ?><option value="<?php echo (int) $i; ?>"><?php echo (int) $i; ?></option><?php endfor; ?></select>
			</label>
			<label class="gp-book__field"><span><?php esc_html_e( 'Promo code', 'gp-house' ); ?></span><input type="text" name="promo" autocomplete="off"></label>
		</form>
		<p class="gp-reserve__hint"><?php esc_html_e( 'Change the number of nights in the booking card above.', 'gp-house' ); ?></p>

		<div class="gp-book__status" data-book-status role="status" aria-live="polite" hidden></div>
		<div class="gp-reserve__rates" data-book-offers></div>

		<div class="gp-reserve__checkout" data-book-checkout hidden>
			<div class="gp-reserve__summary" data-book-summary hidden>
				<h3><?php esc_html_e( 'Your Stay', 'gp-house' ); ?></h3>
				<div data-summary-body></div>
			</div>
			<div class="gp-reserve__steps">
				<?php get_template_part( 'template-parts/booking/checkout' ); ?>
			</div>
		</div>
	</div>
</section>
