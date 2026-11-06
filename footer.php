<?php
/**
 * Site footer — "Ready for your next stay?" call to action + dark footer card
 * (Figma: Frame 495).
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$columns = array(
	'footer_explore' => __( 'Explore', 'gp-house' ),
	'footer_about'   => __( 'About GP House', 'gp-house' ),
	'footer_support' => __( 'Support', 'gp-house' ),
);
$socials = array(
	'facebook'  => 'Facebook',
	'instagram' => 'Instagram',
	'x'         => 'X',
	'linkedin'  => 'LinkedIn',
);

// Pages can swap the call-to-action copy and buttons (e.g. the Club page "Your Siamese Sanctuary Awaits").
$cta = apply_filters( 'gp_house_footer_cta', array(
	'kicker'  => gp_opt( 'cta_kicker' ),
	'title'   => gp_opt( 'cta_title' ),
	'text'    => gp_opt( 'cta_text' ),
	'class'   => '',
	'buttons' => array(
		array( 'label' => __( 'Book Your Stay', 'gp-house' ), 'url' => home_url( '/stays/' ), 'size' => 'tab' ),
		array( 'label' => __( 'Browse All Rooms', 'gp-house' ), 'url' => home_url( '/stays/' ), 'size' => 'tab', 'variant' => 'outline', 'text' => 'navy' ),
	),
) );
?>
<section class="gp-footer-area">
	<div class="gp-container">
		<?php if ( apply_filters( 'gp_house_show_footer_cta', true ) ) : ?>
			<div class="gp-cta<?php echo $cta['class'] ? ' ' . esc_attr( $cta['class'] ) : ''; ?>">
				<?php
				// Some pages (About) show different copy on mobile: 'mobile' => array( kicker?, title, text ).
				$variants = array( 'desktop' => $cta );
				if ( ! empty( $cta['mobile'] ) ) {
					$variants['mobile'] = $cta['mobile'] + $cta;
				}
				foreach ( $variants as $which => $copy ) :
					?>
				<div class="gp-cta__text<?php echo count( $variants ) > 1 ? ( 'mobile' === $which ? ' gp-only-m' : ' gp-only-d' ) : ''; ?>">
					<?php gp_component( 'section-head', array( 'kicker' => $copy['kicker'], 'title' => $copy['title'], 'kicker_class' => 'gp-kicker--dark' ) ); ?>
					<p class="gp-cta__lead"><?php echo esc_html( $copy['text'] ); ?></p>
				</div>
				<?php endforeach; ?>
				<div class="gp-cta__actions">
					<?php
					foreach ( $cta['buttons'] as $button ) {
						gp_button( $button );
					}
					?>
				</div>
			</div>
		<?php endif; ?>

		<footer class="gp-footer">
			<img class="gp-footer__glow gp-footer__glow--right" src="<?php echo esc_url( gp_img( 'decor/footer-ellipse-79.svg' ) ); ?>" alt="" aria-hidden="true">
			<img class="gp-footer__glow gp-footer__glow--left" src="<?php echo esc_url( gp_img( 'decor/footer-ellipse-80.svg' ) ); ?>" alt="" aria-hidden="true">

			<div class="gp-footer__top">
				<div class="gp-footer__brand">
					<div class="gp-footer__about">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( gp_img( 'logo-white.svg' ) ); ?>" width="171" height="39" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"></a>
						<p><?php echo esc_html( gp_opt( 'footer_text' ) ); ?></p>
					</div>
					<div class="gp-footer__socials">
						<?php foreach ( $socials as $key => $label ) : ?>
							<a class="gp-icon-box gp-icon-box--sm" href="<?php echo esc_url( gp_opt( 'social_' . $key ) ); ?>" aria-label="<?php echo esc_attr( $label ); ?>"><?php gp_icon( $key, 24 ); ?></a>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="gp-footer__cols">
					<?php foreach ( $columns as $location => $heading ) : ?>
						<div class="gp-footer__col">
							<h3><?php echo esc_html( $heading ); ?></h3>
							<ul>
								<?php foreach ( gp_menu_links( $location ) as $link ) : ?>
									<li><a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['title'] ); ?></a></li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="gp-footer__bottom">
				<p><?php echo esc_html( gp_opt( 'footer_copyright' ) ); ?></p>
				<div class="gp-footer__currency">
					<span><?php esc_html_e( 'Currency:', 'gp-house' ); ?></span>
					<?php foreach ( array( 'USD', 'THB', 'EUR', 'GBP' ) as $code ) : ?>
						<button type="button" data-currency="<?php echo esc_attr( $code ); ?>"><?php echo esc_html( $code ); ?></button>
					<?php endforeach; ?>
				</div>
			</div>
		</footer>
	</div>
</section>

<?php wp_footer(); ?>
</body>
</html>
