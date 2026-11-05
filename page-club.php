<?php
/**
 * Club page (/club/) — Figma "Hero /Club" (1148:7818), mobile 1455:14304.
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$club    = gp_club_content();
$dests   = gp_club_calculator_destinations();
$hero_id = (int) gp_opt( 'club_hero_image' );
$nights  = 4;
$rate    = gp_club_points_per_dollar();

get_header();
?>
<main id="gp-main" class="gp-club-page">
	<?php
	gp_component( 'page-hero', array(
		'image'  => $hero_id ? wp_get_attachment_image_url( $hero_id, 'full' ) : gp_img( 'club/hero.jpg' ),
		'kicker' => gp_opt( 'club_kicker' ),
		'title'  => gp_opt( 'club_title' ),
		'text'   => gp_opt( 'club_text' ),
		'class'  => 'gp-page-hero--club' . ( $hero_id ? '' : ' gp-page-hero--shaded' ),
	) );
	?>

	<?php // How it works (steps carousel). ?>
	<section class="gp-club-steps" data-carousel>
		<div class="gp-container">
			<?php gp_component( 'section-head', array( 'kicker' => gp_opt( 'club_steps_kicker' ), 'title' => gp_opt( 'club_steps_title' ), 'align' => 'center', 'title_class' => 'gp-title--h3 gp-club-steps__title' ) ); ?>
			<div class="gp-club-steps__viewport">
				<div class="gp-club-steps__track" data-carousel-track>
					<?php foreach ( $club['steps'] as $step ) : ?>
						<article class="gp-step-card">
							<div class="gp-step-card__text">
								<p class="gp-step-card__num"><?php echo esc_html( $step['num'] ); ?></p>
								<div class="gp-step-card__body">
									<h3 class="gp-step-card__title"><?php echo esc_html( $step['title'] ); ?></h3>
									<p class="gp-step-card__desc"><?php echo esc_html( $step['text'] ); ?></p>
									<?php if ( $step['done'] ) : ?>
										<span class="gp-step-card__link"><?php echo esc_html( $step['link'] ); ?><svg class="gp-step-card__check" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2.5 7.5 5.5 10.5 11.5 3.5" stroke="#22A45D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
									<?php else : ?>
										<a class="gp-step-card__link" href="<?php echo esc_url( 0 === strpos( $step['url'], '#' ) ? $step['url'] : home_url( $step['url'] ) ); ?>"><?php echo esc_html( $step['link'] ); ?><?php gp_icon( 'arrow-right-sm', 16 ); ?></a>
									<?php endif; ?>
								</div>
							</div>
							<img class="gp-step-card__image" src="<?php echo esc_url( gp_img( $step['image'] ) ); ?>" alt="" loading="lazy">
						</article>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="gp-club-steps__nav">
				<div class="gp-club-steps__progress" aria-hidden="true"><span data-carousel-progress></span></div>
				<div class="gp-club-steps__arrows">
					<button class="gp-round-arrow" type="button" data-carousel-prev aria-label="<?php esc_attr_e( 'Previous step', 'gp-house' ); ?>"><?php gp_icon( 'arrow-left', 16 ); ?></button>
					<button class="gp-round-arrow" type="button" data-carousel-next aria-label="<?php esc_attr_e( 'Next step', 'gp-house' ); ?>"><?php gp_icon( 'arrow-right', 16 ); ?></button>
				</div>
			</div>
		</div>
	</section>

	<?php // Direct privileges (benefits grid). ?>
	<section class="gp-club-benefits">
		<div class="gp-container">
			<?php gp_component( 'section-head', array( 'kicker' => gp_opt( 'club_benefits_kicker' ), 'title' => gp_opt( 'club_benefits_title' ), 'align' => 'center', 'title_class' => 'gp-title--h3 gp-club-benefits__title' ) ); ?>
			<ul class="gp-club-benefits__grid">
				<?php foreach ( $club['benefits'] as $b ) : ?>
					<li class="gp-club-benefit">
						<span class="gp-club-benefit__icon"><?php gp_icon( $b['icon'], 24 ); ?></span>
						<h3 class="gp-club-benefit__title"><?php echo esc_html( $b['title'] ); ?></h3>
						<p class="gp-club-benefit__text"><?php echo esc_html( $b['text'] ); ?></p>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>

	<?php // Membership tiers. ?>
	<section class="gp-club-tiers">
		<div class="gp-container">
			<?php gp_component( 'section-head', array( 'kicker' => gp_opt( 'club_tiers_kicker' ), 'title' => gp_opt( 'club_tiers_title' ), 'align' => 'center', 'title_class' => 'gp-title--h3 gp-club-benefits__title' ) ); ?>
			<p class="gp-club-tiers__intro"><?php echo esc_html( gp_opt( 'club_tiers_text' ) ); ?></p>
			<div class="gp-club-tiers__grid">
				<?php foreach ( $club['tiers'] as $tier ) : ?>
					<article class="gp-tier-card<?php echo empty( $tier['featured'] ) ? '' : ' is-featured'; ?>">
						<img class="gp-tier-card__image" src="<?php echo esc_url( gp_img( $tier['image'] ) ); ?>" alt="" loading="lazy">
						<div class="gp-tier-card__body">
							<p class="gp-tier-card__tagline"><?php echo esc_html( $tier['tagline'] ); ?></p>
							<h3 class="gp-tier-card__name"><?php echo esc_html( $tier['name'] ); ?></h3>
							<ul class="gp-tier-card__perks">
								<?php foreach ( $tier['perks'] as $perk ) : ?>
									<li><?php gp_icon( 'circle-check-gold', 16 ); ?><?php echo esc_html( $perk ); ?></li>
								<?php endforeach; ?>
							</ul>
							<div class="gp-tier-card__points">
								<strong><?php echo esc_html( $tier['points'] ); ?></strong>
								<span><?php echo esc_html( $tier['earning'][0] ); ?> <br><?php echo esc_html( $tier['earning'][1] ); ?></span>
							</div>
							<?php gp_button( array( 'label' => $tier['button'], 'url' => home_url( '/stays/' ), 'size' => 'card', 'class' => 'gp-tier-card__cta' ) ); ?>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<?php // Interactive calculator. ?>
	<section class="gp-club-calc" id="calculator">
		<div class="gp-container">
			<?php gp_component( 'section-head', array( 'kicker' => gp_opt( 'club_calc_kicker' ), 'title' => gp_opt( 'club_calc_title' ), 'title_class' => 'gp-title--h3' ) ); ?>
			<div class="gp-calc" data-calc data-rate="<?php echo esc_attr( $rate ); ?>">
				<div class="gp-calc__inputs">
					<p class="gp-calc__intro"><?php echo esc_html( gp_opt( 'club_calc_text' ) ); ?></p>
					<div class="gp-calc__nights">
						<p class="gp-calc__nights-value"><span data-calc-nights><?php echo (int) $nights; ?></span> <?php esc_html_e( 'nights', 'gp-house' ); ?></p>
						<input class="gp-range" type="range" min="1" max="15" step="1" value="<?php echo (int) $nights; ?>" aria-label="<?php esc_attr_e( 'Number of nights', 'gp-house' ); ?>" data-calc-range>
						<div class="gp-calc__scale" aria-hidden="true">
							<span>1d</span><?php echo str_repeat( "<i></i>", 6 ); ?><span>10d</span><?php echo str_repeat( "<i></i>", 6 ); ?><span>15d</span>
						</div>
					</div>
					<div class="gp-calc__dests" role="radiogroup" aria-label="<?php esc_attr_e( 'Destination', 'gp-house' ); ?>">
						<?php foreach ( $dests as $i => $d ) : ?>
							<button class="gp-calc__dest<?php echo 0 === $i ? ' is-active' : ''; ?>" type="button" role="radio" aria-checked="<?php echo 0 === $i ? 'true' : 'false'; ?>" data-calc-dest="<?php echo esc_attr( $d['slug'] ); ?>" data-name="<?php echo esc_attr( $d['name'] ); ?>" data-price="<?php echo esc_attr( $d['price'] ); ?>">
								<strong><?php echo esc_html( $d['name'] ); ?></strong>
								<span><?php echo esc_html( gp_price( $d['price'] ) ); ?>/nt</span>
							</button>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="gp-calc__result">
					<p class="gp-calc__label"><?php esc_html_e( 'Total Estimated Points Earned', 'gp-house' ); ?></p>
					<p class="gp-calc__total" aria-live="polite">+<span data-calc-total><?php echo esc_html( number_format_i18n( ( $dests[0]['price'] ?? 0 ) * $nights * $rate ) ); ?></span></p>
					<p class="gp-calc__label gp-calc__label--sub"><?php esc_html_e( 'GP House Club Loyalty Points', 'gp-house' ); ?></p>
					<a class="gp-btn gp-btn--primary gp-btn--tab gp-calc__cta" href="<?php echo esc_url( home_url( '/stays/' ) ); ?>" data-calc-cta><span><?php printf( esc_html__( 'Reserve %1$s Nights In %2$s', 'gp-house' ), '<span data-calc-cta-nights>' . (int) $nights . '</span>', '<span data-calc-cta-dest>' . esc_html( $dests[0]['name'] ?? '' ) . '</span>' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span></a>
					<ul class="gp-calc__rewards">
						<?php foreach ( $club['rewards'] as $r ) : ?>
							<li data-calc-reward="<?php echo (int) $r[1]; ?>">
								<strong><?php echo esc_html( $r[0] ); ?></strong>
								<span><?php printf( esc_html__( 'Requires %s pts', 'gp-house' ), esc_html( number_format_i18n( $r[1] ) ) ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	</section>

	<?php // Points catalog (redemptions). ?>
	<section class="gp-club-redeem" id="redemptions">
		<div class="gp-container">
			<?php gp_component( 'section-head', array( 'kicker' => gp_opt( 'club_redeem_kicker' ), 'title' => gp_opt( 'club_redeem_title' ), 'title_class' => 'gp-title--h3' ) ); ?>
			<div class="gp-club-redeem__track">
				<?php foreach ( $club['redemptions'] as $r ) : ?>
					<article class="gp-redeem-card">
						<div class="gp-redeem-card__media">
							<img src="<?php echo esc_url( gp_img( $r['image'] ) ); ?>" alt="" loading="lazy">
							<?php gp_chip( $r['chip'] ); ?>
						</div>
						<h3 class="gp-redeem-card__title"><?php echo esc_html( $r['title'] ); ?></h3>
						<p class="gp-redeem-card__text"><?php echo esc_html( $r['text'] ); ?></p>
						<?php gp_button( array( 'label' => __( 'Redeem Voucher', 'gp-house' ), 'url' => home_url( '/profile/points/' ), 'size' => 'card', 'class' => 'gp-redeem-card__cta' ) ); ?>
						<p class="gp-redeem-card__pts"><?php printf( esc_html__( '%s Pts', 'gp-house' ), esc_html( $r['points'] ) ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<?php gp_component( 'faq', array( 'items' => $club['faqs'], 'kicker' => gp_opt( 'club_faq_kicker' ), 'title' => gp_opt( 'club_faq_title' ), 'open' => 1, 'variant' => 'compact' ) ); ?>
</main>
<?php
get_footer();
