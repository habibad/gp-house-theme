<?php
/**
 * Late Night Self Check-In (/self-check-in/) — Figma 1148:11140 / 1148:11250 / 1148:10926, mobile 1452:*.
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$req      = gp_checkin_request();
$bookings = gp_checkin_bookings();
$booking  = $req['id'] && isset( $bookings[ $req['id'] ] ) ? $bookings[ $req['id'] ] : null;
$state    = gp_checkin_state( $req['mode'] );
$page_url = get_permalink();
$hero_id  = (int) gp_opt( 'sc_hero_image' );

/** URL for a look-up, keeping the simulation mode. */
$lookup_url = function ( $id, $mode ) use ( $page_url ) {
	return add_query_arg( array_filter( array( 'booking' => $id, 'sim' => 'real' === $mode ? '' : $mode ) ), $page_url );
};

get_header();
?>
<main id="gp-main" class="gp-sc-page">
	<?php
	gp_component( 'page-hero', array(
		'image'        => $hero_id ? wp_get_attachment_image_url( $hero_id, 'full' ) : gp_img( 'checkin/hero.jpg' ),
		'image_mobile' => $hero_id ? '' : gp_img( 'checkin/hero-m.jpg' ),
		'kicker'       => gp_opt( 'sc_kicker' ),
		'title'        => gp_opt( 'sc_title' ),
		'text'         => gp_opt( 'sc_text' ),
		'class'        => 'gp-page-hero--sc' . ( $hero_id ? '' : ' gp-page-hero--shaded' ),
	) );
	?>

	<section class="gp-sc">
		<div class="gp-container">
			<nav class="gp-sc-modes" aria-label="<?php esc_attr_e( 'Arrival time', 'gp-house' ); ?>">
				<?php foreach ( gp_checkin_modes() as $key => $mode ) : ?>
					<a class="gp-sc-modes__tab<?php echo $key === $req['mode'] ? ' is-active' : ''; ?>" href="<?php echo esc_url( $lookup_url( $booking ? $booking['id'] : '', $key ) ); ?>"<?php echo $key === $req['mode'] ? ' aria-current="true"' : ''; ?>>
						<?php gp_icon( $mode['icon'], 16 ); ?><span><?php echo esc_html( $mode['label'] ); ?></span>
					</a>
				<?php endforeach; ?>
			</nav>

			<div class="gp-sc-lookup">
				<h2 class="gp-sc-lookup__title"><?php echo esc_html( gp_opt( 'sc_lookup_title' ) ); ?></h2>
				<p class="gp-sc-lookup__text"><?php echo esc_html( gp_opt( 'sc_lookup_text' ) ); ?></p>
				<form class="gp-sc-lookup__form" action="<?php echo esc_url( $page_url ); ?>#result" method="get" role="search">
					<?php if ( 'real' !== $req['mode'] ) : ?>
						<input type="hidden" name="sim" value="<?php echo esc_attr( $req['mode'] ); ?>">
					<?php endif; ?>
					<label class="gp-sc-lookup__field">
						<span class="screen-reader-text"><?php esc_html_e( 'Booking confirmation ID', 'gp-house' ); ?></span>
						<?php gp_icon( 'key-navy', 20 ); ?>
						<input type="text" name="booking" value="<?php echo esc_attr( $req['id'] ); ?>" placeholder="<?php esc_attr_e( 'e.g. GPH-884219', 'gp-house' ); ?>" autocomplete="off" autocapitalize="characters" spellcheck="false" maxlength="40" required>
					</label>
					<button class="gp-btn gp-btn--primary gp-sc-lookup__submit" type="submit"><?php esc_html_e( 'Verify & Continue', 'gp-house' ); ?></button>
				</form>
				<ul class="gp-sc-lookup__samples">
					<?php foreach ( gp_checkin_samples() as $sample ) : ?>
						<li><a href="<?php echo esc_url( $lookup_url( $sample['id'], $req['mode'] ) . '#result' ); ?>"><?php echo esc_html( $sample['label'] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>

			<?php if ( $req['id'] && ! $booking ) : ?>
				<p class="gp-sc-notice" id="result" role="alert"><?php esc_html_e( 'We could not find that booking ID. Please check your confirmation email and try again.', 'gp-house' ); ?></p>
			<?php elseif ( $booking ) : ?>
				<div id="result" class="gp-sc-result<?php echo $state['unlocked'] ? ' is-unlocked' : ' is-locked'; ?>">
					<div class="gp-sc-banner">
						<span class="gp-sc-banner__icon"><?php gp_icon( $state['unlocked'] ? 'lock-open-white' : 'lock-white', 24 ); ?></span>
						<div class="gp-sc-banner__text">
							<h3><?php echo esc_html( $state['unlocked'] ? __( 'Late Night Access Details Active', 'gp-house' ) : __( 'Access Details Locked Until 11:00 PM', 'gp-house' ) ); ?></h3>
							<p><?php echo esc_html( $state['note'] ); ?></p>
						</div>
						<span class="gp-sc-banner__badge">
							<?php
							echo esc_html( $state['unlocked'] ? __( 'Unlocked For Entry', 'gp-house' ) : sprintf( /* translators: %s: time left */ __( 'Releases In %s', 'gp-house' ), $state['remaining'] ) );
							?>
						</span>
					</div>

					<div class="gp-sc-details">
						<article class="gp-sc-stay">
							<img class="gp-sc-stay__thumb" src="<?php echo esc_url( gp_img( $booking['thumb'] ) ); ?>" width="110" height="110" alt="">
							<div class="gp-sc-stay__info">
								<p class="gp-sc-stay__property"><?php echo esc_html( $booking['property'] ); ?></p>
								<h3><?php echo esc_html( $booking['room_name'] ); ?></h3>
								<p class="gp-sc-stay__conf"><?php echo esc_html( sprintf( /* translators: %s: booking id */ __( 'Confirmation: %s', 'gp-house' ), $booking['id'] ) ); ?></p>
							</div>
							<dl class="gp-sc-stay__facts">
								<div><dt><?php gp_icon( 'key-grey', 20 ); ?><?php esc_html_e( 'Check-In', 'gp-house' ); ?></dt><dd><?php echo esc_html( $booking['check_in'] ); ?></dd></div>
								<div><dt><?php gp_icon( 'external-grey', 20 ); ?><?php esc_html_e( 'Check-Out', 'gp-house' ); ?></dt><dd><?php echo esc_html( $booking['check_out'] ); ?></dd></div>
								<div><dt><?php gp_icon( 'guests-grey', 20 ); ?><?php esc_html_e( 'Guests', 'gp-house' ); ?></dt><dd><?php echo esc_html( $booking['guests'] ); ?></dd></div>
							</dl>
						</article>

						<div class="gp-sc-cols">
							<section class="gp-sc-card gp-sc-room<?php echo $state['unlocked'] ? '' : ' is-dim'; ?>">
								<h3><?php esc_html_e( 'Your Assigned Accommodation', 'gp-house' ); ?></h3>
								<p class="gp-sc-room__label"><?php esc_html_e( 'Room / Villa Designation', 'gp-house' ); ?></p>
								<p class="gp-sc-room__name"><?php echo esc_html( $booking['designation'] ); ?></p>
								<img class="gp-sc-room__photo" src="<?php echo esc_url( gp_img( $booking['photo'] ) ); ?>" alt="" loading="lazy">
								<p class="gp-sc-room__status"><?php echo esc_html( sprintf( /* translators: %s: room status */ __( 'Status: %s', 'gp-house' ), $booking['status'] ) ); ?></p>
								<?php if ( ! $state['unlocked'] ) : ?>
									<div class="gp-sc-lock">
										<span class="gp-sc-lock__icon"><img src="<?php echo esc_url( gp_img( 'icons/door-handle.svg' ) ); ?>" width="68" height="70" alt=""></span>
										<div><p><?php echo esc_html( $booking['room_name'] ); ?></p><strong><?php esc_html_e( 'Locked Until 11 PM', 'gp-house' ); ?></strong></div>
									</div>
								<?php endif; ?>
							</section>

							<section class="gp-sc-card gp-sc-pin<?php echo $state['unlocked'] ? '' : ' is-dim'; ?>">
								<p class="gp-sc-pin__label"><?php esc_html_e( 'Keyless Door PIN Code', 'gp-house' ); ?></p>
								<div class="gp-sc-pin__code">
									<span><?php echo esc_html( $state['unlocked'] ? $booking['pin'] : '••••#' ); ?></span>
									<?php if ( $state['unlocked'] ) : ?>
										<button type="button" class="gp-copy" data-copy="<?php echo esc_attr( $booking['pin'] ); ?>" aria-label="<?php esc_attr_e( 'Copy PIN', 'gp-house' ); ?>"><?php gp_icon( 'copy-navy', 20 ); ?></button>
									<?php else : ?>
										<span class="gp-copy"><?php gp_icon( 'copy-navy', 20 ); ?></span>
									<?php endif; ?>
								</div>
								<div class="gp-sc-how">
									<h4><?php esc_html_e( 'How to enter', 'gp-house' ); ?></h4>
									<ul>
										<li><?php esc_html_e( 'Touch The Keypad On Your Villa Door Handle.', 'gp-house' ); ?></li>
										<li><?php echo esc_html( $state['unlocked'] ? sprintf( /* translators: %s: PIN */ __( 'Enter %s.', 'gp-house' ), $booking['pin'] ) : __( 'Enter Your PIN.', 'gp-house' ) ); ?></li>
										<li><?php esc_html_e( 'Turn When Green Light Flashes.', 'gp-house' ); ?></li>
									</ul>
								</div>
								<?php if ( ! $state['unlocked'] ) : ?>
									<div class="gp-sc-lock">
										<span class="gp-sc-lock__icon"><img src="<?php echo esc_url( gp_img( 'icons/door-handle.svg' ) ); ?>" width="68" height="70" alt=""></span>
										<div><p><?php esc_html_e( 'Electronic PIN', 'gp-house' ); ?></p><strong>••••#</strong><p><?php esc_html_e( 'Unlocks At 23:00', 'gp-house' ); ?></p></div>
									</div>
								<?php endif; ?>
							</section>
						</div>

						<?php if ( $state['unlocked'] ) : ?>
							<section class="gp-sc-nfc">
								<div class="gp-sc-nfc__text">
									<h3><?php esc_html_e( 'Interactive Mobile NFC Room Key', 'gp-house' ); ?></h3>
									<p><?php echo esc_html( sprintf( /* translators: %s: room designation */ __( 'Standing Directly In Front Of %s? You Can Also Trigger The Wireless Lock Receiver Directly From Your Smartphone.', 'gp-house' ), $booking['nfc_room'] ) ); ?></p>
									<button type="button" class="gp-sc-nfc__btn" data-unlock data-done="<?php esc_attr_e( 'Door Unlocked', 'gp-house' ); ?>"><?php echo esc_html( sprintf( /* translators: %s: Villa Door / Suite Door */ __( 'Unlock %s', 'gp-house' ), $booking['door'] ) ); ?></button>
								</div>
								<div class="gp-sc-access">
									<div class="gp-sc-access__row">
										<?php gp_icon( 'moon-navy', 24 ); ?>
										<div>
											<h4><?php esc_html_e( 'Estate Access & Connectivity', 'gp-house' ); ?></h4>
											<p class="gp-sc-access__line"><span><?php esc_html_e( 'Night Gate Code:', 'gp-house' ); ?></span> <strong><?php echo esc_html( $booking['gate_code'] ); ?></strong><button type="button" class="gp-copy" data-copy="<?php echo esc_attr( $booking['gate_code'] ); ?>" aria-label="<?php esc_attr_e( 'Copy gate code', 'gp-house' ); ?>"><?php gp_icon( 'copy-navy', 20 ); ?></button></p>
										</div>
									</div>
									<div class="gp-sc-access__row">
										<?php gp_icon( 'wifi-navy', 24 ); ?>
										<div>
											<h4><?php esc_html_e( 'Villa Optical WiF', 'gp-house' ); ?></h4>
											<p class="gp-sc-access__sub"><?php echo esc_html( $booking['wifi_name'] ); ?></p>
											<p class="gp-sc-access__line"><strong><?php echo esc_html( $booking['wifi_pass'] ); ?></strong><button type="button" class="gp-copy" data-copy="<?php echo esc_attr( $booking['wifi_pass'] ); ?>" aria-label="<?php esc_attr_e( 'Copy Wi-Fi password', 'gp-house' ); ?>"><?php gp_icon( 'copy-navy', 20 ); ?></button></p>
										</div>
									</div>
									<div class="gp-sc-access__row">
										<?php gp_icon( 'phone-navy', 24 ); ?>
										<div>
											<h4><?php esc_html_e( 'Night Host On-Duty', 'gp-house' ); ?></h4>
											<p class="gp-sc-access__phone"><a href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', $booking['host_phone'] ) ); ?>"><?php echo esc_html( $booking['host_phone'] ); ?></a></p>
											<p class="gp-sc-access__sub"><?php esc_html_e( 'Available Via Call Or WhatsApp For Luggage, Buggy, Or Late Dining.', 'gp-house' ); ?></p>
										</div>
									</div>
								</div>
							</section>
						<?php endif; ?>
					</div>

					<div class="gp-sc-action">
						<?php if ( $state['unlocked'] ) : ?>
							<button type="button" class="gp-btn gp-btn--primary gp-sc-action__btn" data-arrived data-done="<?php esc_attr_e( 'Welcome — Enjoy Your Stay', 'gp-house' ); ?>"><?php esc_html_e( 'I Have Arrived In My Room', 'gp-house' ); ?></button>
						<?php else : ?>
							<a class="gp-btn gp-btn--primary gp-sc-action__btn" href="<?php echo esc_url( $lookup_url( $booking['id'], 'unlocked' ) . '#result' ); ?>"><?php esc_html_e( 'Simulate After 11:00 PM', 'gp-house' ); ?></a>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<section class="gp-sc-perks<?php echo ! $booking ? ' gp-sc-perks--idle' : ( $state['unlocked'] ? ' gp-sc-perks--unlocked' : '' ); ?>">
		<div class="gp-container">
			<ul class="gp-sc-perks__list">
				<?php foreach ( gp_checkin_perks() as $perk ) : ?>
					<li>
						<span class="gp-sc-perks__icon"><?php gp_icon( $perk['icon'], 24 ); ?></span>
						<h3><?php echo esc_html( $perk['title'] ); ?></h3>
						<p><?php echo esc_html( $perk['text'] ); ?></p>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
</main>
<?php
get_footer();
