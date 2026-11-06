<?php
/**
 * Why Book Direct? (Figma: 1148:16345).
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="gp-why">
	<img class="gp-why__glow gp-why__glow--tl" src="<?php echo esc_url( gp_img( 'decor/why-ellipse-79.svg' ) ); ?>" alt="" aria-hidden="true">
	<img class="gp-why__glow gp-why__glow--br" src="<?php echo esc_url( gp_img( 'decor/why-ellipse-80.svg' ) ); ?>" alt="" aria-hidden="true">
	<img class="gp-why__dots" src="<?php echo esc_url( gp_img( 'decor/why-dots.png' ) ); ?>" alt="" aria-hidden="true" loading="lazy">

	<div class="gp-why__panel">
		<div class="gp-why__head">
			<?php gp_component( 'section-head', array( 'kicker' => gp_opt( 'why_kicker' ), 'title' => gp_opt( 'why_title' ), 'tone' => 'light', 'gap' => '12' ) ); ?>
			<p class="gp-why__intro"><?php echo esc_html( gp_opt( 'why_text' ) ); ?></p>
		</div>

		<div class="gp-why__body">
			<ul class="gp-benefits">
				<?php foreach ( gp_benefits() as $i => $benefit ) : ?>
					<li class="gp-benefit<?php echo 0 === $i ? ' is-active' : ''; ?>" tabindex="0">
						<span class="gp-icon-box"><?php gp_icon( $benefit['icon'], 30 ); ?></span>
						<span class="gp-benefit__text">
							<strong><?php echo esc_html( $benefit['title'] ); ?></strong>
							<span><?php echo esc_html( $benefit['text'] ); ?></span>
						</span>
					</li>
				<?php endforeach; ?>
			</ul>
			<img class="gp-why__art" src="<?php echo esc_url( gp_img( 'decor/why-card-illustration.webp' ) ); ?>" width="541" height="415" alt="<?php esc_attr_e( 'GP House Club member card — 10% off applied instantly', 'gp-house' ); ?>" loading="lazy">
		</div>
	</div>
</section>
