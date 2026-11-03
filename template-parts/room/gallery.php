<?php
/**
 * Room page gallery: main photo + thumbnail strip with arrows (Figma 1362:5915).
 *
 * @var array $args { room: array }
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$room    = $args['room'];
$photos  = $room['detail_gallery'];
$active  = min( max( 0, (int) $room['detail_active'] ), max( 0, count( $photos ) - 1 ) );
if ( ! $photos ) {
	return;
}
?>
<section class="gp-room-gallery" data-room-gallery>
	<div class="gp-room-gallery__inner">
		<picture class="gp-room-gallery__picture">
			<?php if ( $room['main_mobile'] ) : ?>
				<source media="(max-width: 600px)" srcset="<?php echo esc_url( $room['main_mobile'] ); ?>">
			<?php endif; ?>
			<img class="gp-room-gallery__main" src="<?php echo esc_url( $photos[ $active ]['full'] ); ?>" alt="<?php echo esc_attr( $room['title'] ); ?>" data-room-gallery-main>
		</picture>
		<div class="gp-room-gallery__strip" data-room-gallery-strip style="--active:<?php echo (int) $active; ?>">
			<?php foreach ( $photos as $i => $photo ) : ?>
				<button class="gp-room-gallery__thumb<?php echo $i === $active ? ' is-active' : ''; ?>" type="button" data-full="<?php echo esc_url( $photo['full'] ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Show photo %d', 'gp-house' ), $i + 1 ) ); ?>"<?php echo $i === $active ? ' aria-current="true"' : ''; ?>>
					<img src="<?php echo esc_url( $photo['strip'] ?: $photo['full'] ); ?>" alt="" loading="lazy">
				</button>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="gp-room-gallery__fade gp-room-gallery__fade--left" aria-hidden="true"></div>
	<div class="gp-room-gallery__fade gp-room-gallery__fade--right" aria-hidden="true"></div>
	<div class="gp-room-gallery__arrows">
		<button class="gp-room-gallery__arrow" type="button" data-room-gallery-prev aria-label="<?php esc_attr_e( 'Previous photo', 'gp-house' ); ?>"><?php gp_icon( 'arrow-left-grey', 24 ); ?></button>
		<button class="gp-room-gallery__arrow gp-room-gallery__arrow--next" type="button" data-room-gallery-next aria-label="<?php esc_attr_e( 'Next photo', 'gp-house' ); ?>"><?php gp_icon( 'arrow-right-navy', 24 ); ?></button>
	</div>
</section>
