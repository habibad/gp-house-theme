<?php
/**
 * Stay card (Figma: Card / "Featured Sanctuaries", 360 × 490).
 *
 * Also used for the Destinations page region cards (Figma 1148:6998) with
 * variant 'region': no save button / slider dots, navy title, custom button.
 *
 * @var array $args { stay: array (gp_get_stays() shape), saved?: bool, variant?: ''|'region', cta?: string }
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$stay  = $args['stay'];
$saved  = ! empty( $args['saved'] );
$region = 'region' === ( $args['variant'] ?? '' );
$cta    = $args['cta'] ?? gp_opt( 'member_label' );
?>
<article class="gp-stay-card<?php echo $region ? ' gp-stay-card--region' : ''; ?>"<?php echo isset( $stay['loc_slug'] ) ? ' data-location="' . esc_attr( $stay['loc_slug'] ) . '"' : ''; ?>>
	<div class="gp-stay-card__media">
		<a href="<?php echo esc_url( $stay['url'] ); ?>" tabindex="-1"><img src="<?php echo esc_url( $stay['image'] ); ?>" alt="<?php echo esc_attr( $stay['title'] ); ?>" loading="lazy"></a>
		<?php gp_chip( $stay['location'] ); ?>
		<?php if ( ! $region ) : ?>
		<button class="gp-fav<?php echo $saved ? ' is-active' : ''; ?>" type="button" aria-pressed="<?php echo $saved ? 'true' : 'false'; ?>" aria-label="<?php esc_attr_e( 'Save stay', 'gp-house' ); ?>" data-fav="<?php echo esc_attr( $stay['id'] ); ?>">
			<img class="gp-fav__off" src="<?php echo esc_url( gp_img( 'icons/heart.svg' ) ); ?>" width="20" height="20" alt="">
			<img class="gp-fav__on" src="<?php echo esc_url( gp_img( 'icons/heart-filled.svg' ) ); ?>" width="20" height="20" alt="">
		</button>
		<img class="gp-stay-card__dots" src="<?php echo esc_url( gp_img( 'icons/slider-dots.svg' ) ); ?>" width="47" height="6" alt="" aria-hidden="true">
		<?php endif; ?>
	</div>
	<div class="gp-stay-card__body">
		<div class="gp-stay-card__text">
			<h3 class="gp-stay-card__title"><a href="<?php echo esc_url( $stay['url'] ); ?>"><?php echo esc_html( $stay['title'] ); ?></a></h3>
			<p class="gp-stay-card__desc"><?php echo esc_html( $stay['excerpt'] ); ?></p>
		</div>
		<div class="gp-stay-card__foot">
			<p class="gp-stay-card__price"><?php echo esc_html( gp_price( $stay['price'] ) ); ?>/<span>night</span></p>
			<?php gp_button( array( 'label' => $cta, 'url' => $stay['url'], 'size' => 'xs' ) ); ?>
		</div>
	</div>
</article>
