<?php
/**
 * Booking page (/book/) — search, choose a room, guest details, PayPal payment; and the
 * confirmation view (?ref=GPB-XXXXXX). The search runs in booking.js against the booking plugin's REST API.
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$enabled = function_exists( 'gp_booking_properties' );
$ref     = isset( $_GET['ref'] ) ? sanitize_text_field( wp_unslash( $_GET['ref'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification

// Query defaults (tomorrow + 2 nights; the hero search sends check-in and "adults-rooms").
// phpcs:disable WordPress.Security.NonceVerification
$tomorrow = gmdate( 'Y-m-d', strtotime( '+1 day', current_time( 'timestamp' ) ) );
$checkin  = isset( $_GET['checkin'] ) ? sanitize_text_field( wp_unslash( $_GET['checkin'] ) ) : $tomorrow;
$checkin  = preg_match( '/^\d{4}-\d{2}-\d{2}$/', $checkin ) && $checkin >= gmdate( 'Y-m-d', current_time( 'timestamp' ) ) ? $checkin : $tomorrow;
$nights   = isset( $_GET['nights'] ) ? max( 1, min( 30, (int) $_GET['nights'] ) ) : 2;
$checkout = isset( $_GET['checkout'] ) ? sanitize_text_field( wp_unslash( $_GET['checkout'] ) ) : gmdate( 'Y-m-d', strtotime( $checkin . ' +' . $nights . ' day' ) );
$checkout = preg_match( '/^\d{4}-\d{2}-\d{2}$/', $checkout ) && $checkout > $checkin ? $checkout : gmdate( 'Y-m-d', strtotime( $checkin . ' +2 day' ) );
$guests   = isset( $_GET['guests'] ) ? sanitize_text_field( wp_unslash( $_GET['guests'] ) ) : '';
$adults   = isset( $_GET['adults'] ) ? (int) $_GET['adults'] : ( $guests ? (int) explode( '-', $guests )[0] : 2 );
$adults   = max( 1, min( 8, $adults ) );
$children = isset( $_GET['children'] ) ? max( 0, min( 4, (int) $_GET['children'] ) ) : 0;
$promo    = isset( $_GET['promo'] ) ? strtoupper( preg_replace( '/[^A-Za-z0-9_-]/', '', wp_unslash( $_GET['promo'] ) ) ) : '';
$offer    = isset( $_GET['offer'] ) ? sanitize_key( wp_unslash( $_GET['offer'] ) ) : '';
// phpcs:enable

$property = $enabled ? gp_booking_resolve_property() : '';
$booking  = null;
if ( $enabled && $ref && is_user_logged_in() ) {
	foreach ( gp_booking_for_user( get_current_user_id(), array( 'pending', 'confirmed', 'cancel_requested', 'cancelled', 'failed' ) ) as $b ) {
		if ( $b['ref'] === $ref ) {
			$booking = $b;
			break;
		}
	}
}

get_header();
?>
<main id="gp-main" class="gp-book-page">
	<div class="gp-container">
		<?php if ( ! $enabled ) : ?>
			<div class="gp-book__empty">
				<h1 class="gp-book__title"><?php esc_html_e( 'Online booking is coming soon', 'gp-house' ); ?></h1>
				<p><?php esc_html_e( 'Please browse our stays or contact the concierge to reserve.', 'gp-house' ); ?></p>
				<?php gp_button( array( 'label' => __( 'Browse Stays', 'gp-house' ), 'url' => home_url( '/stays/' ), 'size' => 'tab' ) ); ?>
			</div>

		<?php elseif ( $ref ) : ?>
			<?php if ( ! is_user_logged_in() ) : ?>
				<div class="gp-book__empty">
					<h1 class="gp-book__title"><?php esc_html_e( 'Sign in to see your booking', 'gp-house' ); ?></h1>
					<p><?php esc_html_e( 'Your booking details are private to your account.', 'gp-house' ); ?></p>
					<?php gp_button( array( 'label' => __( 'Sign In', 'gp-house' ), 'url' => gp_auth_url( 'signin', array( 'redirect_to' => rawurlencode( add_query_arg( 'ref', $ref, home_url( '/book/' ) ) ) ) ), 'size' => 'tab', 'attrs' => array( 'data-auth' => 'signin', 'data-redirect' => add_query_arg( 'ref', $ref, home_url( '/book/' ) ) ) ) ); ?>
				</div>
			<?php elseif ( ! $booking ) : ?>
				<div class="gp-book__empty">
					<h1 class="gp-book__title"><?php esc_html_e( 'Booking not found', 'gp-house' ); ?></h1>
					<p><?php esc_html_e( 'We could not find that booking on your account.', 'gp-house' ); ?></p>
					<?php gp_button( array( 'label' => __( 'My Bookings', 'gp-house' ), 'url' => home_url( '/profile/bookings/' ), 'size' => 'tab' ) ); ?>
				</div>
			<?php else : ?>
				<?php get_template_part( 'template-parts/booking/confirmation', null, array( 'booking' => $booking ) ); ?>
			<?php endif; ?>

		<?php else : ?>
			<header class="gp-book__head">
				<p class="gp-kicker"><?php esc_html_e( 'Direct Booking', 'gp-house' ); ?></p>
				<h1 class="gp-book__title"><?php esc_html_e( 'Book Your Stay', 'gp-house' ); ?></h1>
				<p class="gp-book__lead"><?php esc_html_e( 'Best rate guaranteed when you book direct. Pay securely with PayPal — your booking is confirmed instantly.', 'gp-house' ); ?></p>
			</header>

			<form class="gp-book__search" data-book-search action="<?php echo esc_url( home_url( '/book/' ) ); ?>" method="get">
				<label class="gp-book__field gp-book__field--wide"><span><?php esc_html_e( 'Property', 'gp-house' ); ?></span>
					<select name="property" required>
						<?php foreach ( gp_booking_properties() as $slug => $p ) : ?>
							<option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $property, $slug ); ?>><?php echo esc_html( $p['label'] ); ?></option>
						<?php endforeach; ?>
					</select>
				</label>
				<label class="gp-book__field"><span><?php esc_html_e( 'Check-in', 'gp-house' ); ?></span><input type="date" name="checkin" value="<?php echo esc_attr( $checkin ); ?>" min="<?php echo esc_attr( gmdate( 'Y-m-d', current_time( 'timestamp' ) ) ); ?>" required></label>
				<label class="gp-book__field"><span><?php esc_html_e( 'Check-out', 'gp-house' ); ?></span><input type="date" name="checkout" value="<?php echo esc_attr( $checkout ); ?>" min="<?php echo esc_attr( $tomorrow ); ?>" required></label>
				<label class="gp-book__field gp-book__field--narrow"><span><?php esc_html_e( 'Adults', 'gp-house' ); ?></span>
					<select name="adults"><?php for ( $i = 1; $i <= 8; $i++ ) : ?><option value="<?php echo (int) $i; ?>" <?php selected( $adults, $i ); ?>><?php echo (int) $i; ?></option><?php endfor; ?></select>
				</label>
				<label class="gp-book__field gp-book__field--narrow"><span><?php esc_html_e( 'Children', 'gp-house' ); ?></span>
					<select name="children"><?php for ( $i = 0; $i <= 4; $i++ ) : ?><option value="<?php echo (int) $i; ?>" <?php selected( $children, $i ); ?>><?php echo (int) $i; ?></option><?php endfor; ?></select>
				</label>
				<label class="gp-book__field"><span><?php esc_html_e( 'Promo code', 'gp-house' ); ?></span><input type="text" name="promo" value="<?php echo esc_attr( $promo ); ?>" autocomplete="off"></label>
				<button class="gp-btn gp-btn--primary gp-book__submit" type="submit"><?php esc_html_e( 'Check Availability', 'gp-house' ); ?></button>
			</form>

			<div class="gp-book" data-book data-offer="<?php echo esc_attr( $offer ); ?>" data-room="<?php echo isset( $_GET['room'] ) ? (int) $_GET['room'] : 0; // phpcs:ignore WordPress.Security.NonceVerification ?>">
				<div class="gp-book__main">
					<div class="gp-book__status" data-book-status role="status" aria-live="polite"></div>
					<div class="gp-book__offers" data-book-offers></div>
					<section class="gp-book__checkout" data-book-checkout hidden>
						<?php get_template_part( 'template-parts/booking/checkout' ); ?>
					</section>
				</div>
				<aside class="gp-book__summary" data-book-summary hidden>
					<h2><?php esc_html_e( 'Your Stay', 'gp-house' ); ?></h2>
					<div data-summary-body></div>
				</aside>
			</div>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
