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
// Signed-in members see their own saved items; the design's sample state only applies to the demo profile.
$saved  = is_user_logged_in() && function_exists( 'gp_is_saved' ) ? gp_is_saved( $stay['id'] ) : ( ! empty( $args['saved'] ) && function_exists( 'gp_profile_is_demo' ) && gp_profile_is_demo() );
$region = 'region' === ( $args['variant'] ?? '' );
$cta    = $args['cta'] ?? gp_opt( 'member_label' );
// Photo slider (Figma dots under the photo): only on stay cards with more than one photo.
$images = $region ? array() : array_values( array_filter( (array) ( $stay['images'] ?? array() ) ) );
$slider = count( $images ) > 1;
if ( ! $slider ) {
	$images = array( $stay['image'] );
}
?>
<article class="gp-stay-card<?php echo $region ? ' gp-stay-card--region' : ''; ?>"<?php echo isset( $stay['loc_slug'] ) ? ' data-location="' . esc_attr( $stay['loc_slug'] ) . '"' : ''; ?>>
	<div class="gp-stay-card__media"<?php echo $slider ? ' data-card-slider' : ''; ?>>
		<a href="<?php echo esc_url( $stay['url'] ); ?>" tabindex="-1"><?php if ( $slider ) : ?><span class="gp-stay-card__track"><?php endif; ?><?php foreach ( $images as $i => $src ) : ?><img src="<?php echo esc_url( $src ); ?>" alt="<?php echo esc_attr( $i ? '' : $stay['title'] ); ?>" loading="lazy"<?php echo $i ? ' aria-hidden="true"' : ''; ?>><?php endforeach; ?><?php if ( $slider ) : ?></span><?php endif; ?></a>
		<?php gp_chip( $stay['location'] ); ?>
		<?php if ( ! $region ) : ?>
		<button class="gp-fav<?php echo $saved ? ' is-active' : ''; ?>" type="button" aria-pressed="<?php echo $saved ? 'true' : 'false'; ?>" aria-label="<?php esc_attr_e( 'Save stay', 'gp-house' ); ?>" data-fav="<?php echo esc_attr( $stay['id'] ); ?>">
			<img class="gp-fav__off" src="<?php echo esc_url( gp_img( 'icons/heart.svg' ) ); ?>" width="20" height="20" alt="">
			<img class="gp-fav__on" src="<?php echo esc_url( gp_img( 'icons/heart-filled.svg' ) ); ?>" width="20" height="20" alt="">
		</button>
		<?php endif; ?>
		<?php if ( $slider ) : ?>
			<div class="gp-stay-card__dots" role="group" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: stay name */ __( 'Photos of %s', 'gp-house' ), $stay['title'] ) ); ?>">
				<?php foreach ( $images as $i => $src ) : ?>
					<button class="gp-dot<?php echo 0 === $i ? ' is-active' : ''; ?>" type="button" data-slide="<?php echo (int) $i; ?>" data-dist="<?php echo (int) min( $i, 4 ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: 1: photo number, 2: total */ __( 'Show photo %1$d of %2$d', 'gp-house' ), $i + 1, count( $images ) ) ); ?>"<?php echo 0 === $i ? ' aria-current="true"' : ''; ?>></button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
	<div class="gp-stay-card__body">
		<div class="gp-stay-card__text">
			<h3 class="gp-stay-card__title"><a href="<?php echo esc_url( $stay['url'] ); ?>"><?php echo esc_html( $stay['title'] ); ?></a></h3>
			<p class="gp-stay-card__desc"><?php echo esc_html( $stay['excerpt'] ); ?></p>
		</div>
		<div class="gp-stay-card__foot">
			<p class="gp-stay-card__price"><span class="gp-stay-card__from"><?php esc_html_e( 'From', 'gp-house' ); ?></span> <?php echo esc_html( gp_money_thb( $stay['price'], $stay['price_thb'] ?? 0 ) ); ?>/<span>night</span></p>
			<?php gp_button( array( 'label' => $cta, 'url' => $stay['url'], 'size' => 'xs' ) ); ?>
		</div>
	</div>
</article>
