<?php
/**
 * Booking page → guest details + payment panel (shown after a room is selected; driven by booking.js).
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="gp-book__panel" data-gate hidden>
	<h2><?php esc_html_e( 'Sign In To Complete Your Booking', 'gp-house' ); ?></h2>
	<p><?php esc_html_e( 'Your booking, receipt and points live in your GP House account. It takes a minute to join.', 'gp-house' ); ?></p>
	<div class="gp-book__actions">
		<a class="gp-btn gp-btn--primary gp-btn--tab" href="<?php echo esc_url( gp_auth_url( 'signin' ) ); ?>" data-auth="signin" data-gate-link="signin"><?php esc_html_e( 'Sign In', 'gp-house' ); ?></a>
		<a class="gp-btn gp-btn--outline gp-btn--tab gp-btn--navy-text" href="<?php echo esc_url( gp_auth_url( 'join' ) ); ?>" data-auth="join" data-gate-link="join"><?php esc_html_e( 'Join GP House Club', 'gp-house' ); ?></a>
	</div>
</div>

<form class="gp-book__panel gp-book__form" data-details-form hidden novalidate>
	<h2><?php esc_html_e( 'Guest Details', 'gp-house' ); ?></h2>
	<p class="gp-book__alert" data-form-alert role="alert" hidden></p>
	<div class="gp-book__grid">
		<label class="gp-book__field"><span><?php esc_html_e( 'First Name', 'gp-house' ); ?></span><input type="text" name="first" autocomplete="given-name" required></label>
		<label class="gp-book__field"><span><?php esc_html_e( 'Last Name', 'gp-house' ); ?></span><input type="text" name="last" autocomplete="family-name" required></label>
		<label class="gp-book__field"><span><?php esc_html_e( 'Email', 'gp-house' ); ?></span><input type="email" name="email" autocomplete="email" readonly></label>
		<label class="gp-book__field"><span><?php esc_html_e( 'Phone / WhatsApp', 'gp-house' ); ?></span><input type="tel" name="phone" autocomplete="tel" placeholder="+66 81 234 5678" required></label>
		<label class="gp-book__field"><span><?php esc_html_e( 'Country', 'gp-house' ); ?></span>
			<select name="country" autocomplete="country" required>
				<option value=""><?php esc_html_e( 'Select country', 'gp-house' ); ?></option>
				<?php foreach ( gp_countries() as $code => $name ) : ?>
					<option value="<?php echo esc_attr( $code ); ?>"><?php echo esc_html( $name ); ?></option>
				<?php endforeach; ?>
			</select>
		</label>
		<label class="gp-book__field gp-book__field--full"><span><?php esc_html_e( 'Special Requests (Optional)', 'gp-house' ); ?></span><textarea name="requests" rows="3" maxlength="400" placeholder="<?php esc_attr_e( 'Arrival time, bed type, celebrations…', 'gp-house' ); ?>"></textarea></label>
	</div>
	<label class="gp-book__terms">
		<input type="checkbox" name="terms" value="1" required>
		<span><?php
		printf(
			/* translators: 1: policies link, 2: terms link */
			esc_html__( 'I accept the %1$s and %2$s. Payment is taken in full in Thai baht.', 'gp-house' ),
			'<a href="' . esc_url( home_url( '/booking-policies/' ) ) . '" target="_blank" rel="noopener">' . esc_html__( 'booking policies', 'gp-house' ) . '</a>',
			'<a href="' . esc_url( home_url( '/terms/' ) ) . '" target="_blank" rel="noopener">' . esc_html__( 'terms of service', 'gp-house' ) . '</a>'
		);
		?></span>
	</label>
	<button class="gp-btn gp-btn--primary gp-btn--tab gp-book__pay" type="submit" data-pay-button><?php esc_html_e( 'Continue to payment', 'gp-house' ); ?></button>
</form>

<div class="gp-book__panel gp-book__payment" data-payment hidden>
	<h2><?php esc_html_e( 'Pay Securely With PayPal', 'gp-house' ); ?></h2>
	<p class="gp-book__hold"><?php esc_html_e( 'Your room is held for', 'gp-house' ); ?> <strong data-hold-timer>20:00</strong></p>
	<p class="gp-book__alert" data-pay-alert role="alert" hidden></p>
	<div id="gp-paypal-buttons" class="gp-book__paypal"></div>
	<p class="gp-book__fine"><?php esc_html_e( 'You can pay with your PayPal balance or a debit / credit card. Your booking is confirmed the moment the payment succeeds.', 'gp-house' ); ?></p>
	<button class="gp-book__link" type="button" data-change-room><?php esc_html_e( 'Choose a different room', 'gp-house' ); ?></button>
</div>
