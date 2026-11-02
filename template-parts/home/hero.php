<?php
/**
 * Home hero with booking search (Figma: Hero 1148:16163).
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$hero_image   = gp_opt( 'hero_image' ) ? wp_get_attachment_image_url( (int) gp_opt( 'hero_image' ), 'full' ) : gp_img( 'photos/hero-room.jpg' );
$destinations = gp_get_destinations();
$checkin      = gmdate( 'Y-m-d', strtotime( '+1 day', current_time( 'timestamp' ) ) );
?>
<section class="gp-hero">
	<div class="gp-hero__media">
		<img class="gp-hero__photo" src="<?php echo esc_url( $hero_image ); ?>" alt="" fetchpriority="high">
		<div class="gp-hero__shade" aria-hidden="true"></div>
		<img class="gp-hero__glow" src="<?php echo esc_url( gp_img( 'decor/hero-ellipse-80.svg' ) ); ?>" alt="" aria-hidden="true">
		<div class="gp-hero__curve" aria-hidden="true"><?php gp_inline_svg( 'decor/hero-curve.svg' ); ?></div>
		<img class="gp-hero__floor" src="<?php echo esc_url( gp_img( 'decor/hero-ellipse-20.svg' ) ); ?>" alt="" aria-hidden="true">

		<div class="gp-hero__inner">
			<div class="gp-hero__copy">
				<p class="gp-hero__kicker"><?php echo esc_html( gp_opt( 'hero_kicker' ) ); ?></p>
				<h1 class="gp-hero__title"><?php echo esc_html( gp_opt( 'hero_title' ) ); ?></h1>
			</div>

			<div class="gp-hero__booking">
				<a class="gp-hero__badge" href="<?php echo esc_url( home_url( '/club/' ) ); ?>"><?php echo esc_html( gp_opt( 'hero_badge' ) ); ?></a>

				<form class="gp-search" action="<?php echo esc_url( home_url( '/stays/' ) ); ?>" method="get">
					<div class="gp-search__fields">
						<label class="gp-search__field">
							<span class="gp-search__icon"><?php gp_icon( 'location', 24 ); ?></span>
							<span class="gp-search__text">
								<span class="gp-search__label"><?php esc_html_e( 'Destination', 'gp-house' ); ?></span>
								<select class="gp-search__value" name="destination">
									<option value=""><?php esc_html_e( 'All Thailand', 'gp-house' ); ?></option>
									<?php foreach ( $destinations as $dest ) : ?>
										<option value="<?php echo esc_attr( $dest['slug'] ); ?>"><?php echo esc_html( $dest['name'] ); ?></option>
									<?php endforeach; ?>
								</select>
							</span>
							<?php gp_icon( 'arrow-down-white', 24 ); ?>
						</label>

						<label class="gp-search__field">
							<span class="gp-search__icon"><?php gp_icon( 'calendar', 24 ); ?></span>
							<span class="gp-search__text">
								<span class="gp-search__label"><?php esc_html_e( 'Check-in Date', 'gp-house' ); ?></span>
								<input class="gp-search__value gp-search__date" type="date" name="checkin" value="<?php echo esc_attr( $checkin ); ?>" min="<?php echo esc_attr( gmdate( 'Y-m-d', current_time( 'timestamp' ) ) ); ?>">
							</span>
							<?php gp_icon( 'arrow-down-white', 24 ); ?>
						</label>

						<label class="gp-search__field">
							<span class="gp-search__icon"><?php gp_icon( 'users-round', 24 ); ?></span>
							<span class="gp-search__text">
								<span class="gp-search__label"><?php esc_html_e( 'Guest', 'gp-house' ); ?></span>
								<select class="gp-search__value" name="guests">
									<?php
									foreach ( array( '1-1' => '1 Guest. 1R', '2-1' => '2 Guests. 1R', '3-1' => '3 Guests. 1R', '4-2' => '4 Guests. 2R', '6-3' => '6 Guests. 3R' ) as $value => $label ) {
										printf( '<option value="%s"%s>%s</option>', esc_attr( $value ), selected( $value, '2-1', false ), esc_html( $label ) );
									}
									?>
								</select>
							</span>
							<?php gp_icon( 'arrow-down-white', 24 ); ?>
						</label>

						<label class="gp-search__field">
							<span class="gp-search__icon"><?php gp_icon( 'promo', 24 ); ?></span>
							<span class="gp-search__text">
								<span class="gp-search__label"><?php esc_html_e( 'Promo code', 'gp-house' ); ?></span>
								<input class="gp-search__value" type="text" name="promo" value="<?php echo esc_attr( gp_opt( 'hero_promo' ) ); ?>" autocomplete="off">
							</span>
						</label>
					</div>
					<button class="gp-btn gp-btn--primary gp-btn--lg gp-btn--shadow" type="submit"><?php esc_html_e( 'Check Availability', 'gp-house' ); ?></button>
				</form>
			</div>
		</div>
	</div>
</section>
