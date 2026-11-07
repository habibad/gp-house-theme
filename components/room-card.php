<?php
/**
 * Room row on the Stays page: photo gallery (Figma "Frame 490") + details card
 * (Figma component "Stays", 320:1388).
 *
 * @var array $args { room: array (gp_get_rooms() shape), hidden?: bool }
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$room    = $args['room'];
$gallery = $room['gallery'];
$active  = min( max( 0, (int) $room['active'] ), max( 0, count( $gallery ) - 1 ) );
$main    = $gallery[ $active ]['full'] ?? '';
// Mobile shows a 5-photo window around the highlighted thumbnail (Figma mobile frame).
$win     = max( 0, min( $active - 2, count( $gallery ) - 5 ) );
$specs   = array(
	array( array( __( 'Area', 'gp-house' ), $room['area'] ), array( __( 'Guests', 'gp-house' ), $room['guests'] ) ),
	array( array( __( 'Bed', 'gp-house' ), $room['bed'] ), array( __( 'View', 'gp-house' ), $room['view'] ) ),
);
?>
<article class="gp-room" data-room data-location="<?php echo esc_attr( $room['loc_slug'] ); ?>" data-type="<?php echo esc_attr( $room['type'] ); ?>" data-beds="<?php echo esc_attr( implode( ' ', $room['beds'] ) ); ?>" data-tags="<?php echo esc_attr( implode( ' ', $room['tags'] ) ); ?>" data-price="<?php echo esc_attr( $room['price'] ); ?>" data-area="<?php echo esc_attr( (int) $room['area'] ); ?>"<?php echo empty( $args['hidden'] ) ? '' : ' hidden'; ?>>
	<div class="gp-room__media" data-gallery>
		<img class="gp-room__photo" src="<?php echo esc_url( $main ); ?>" alt="<?php echo esc_attr( $room['title'] ); ?>" loading="lazy" data-gallery-main>
		<?php gp_chip( $room['location'] ); ?>
		<button class="gp-fav" type="button" aria-pressed="false" aria-label="<?php esc_attr_e( 'Save room', 'gp-house' ); ?>" data-fav="room-<?php echo esc_attr( $room['id'] ); ?>">
			<img class="gp-fav__off" src="<?php echo esc_url( gp_img( 'icons/heart.svg' ) ); ?>" width="20" height="20" alt="">
			<img class="gp-fav__on" src="<?php echo esc_url( gp_img( 'icons/heart-filled.svg' ) ); ?>" width="20" height="20" alt="">
		</button>
		<?php if ( count( $gallery ) > 1 ) : ?>
			<div class="gp-room__thumbs">
				<?php foreach ( $gallery as $i => $img ) : ?>
					<button class="gp-room__thumb<?php echo $i === $active ? ' is-active' : ''; ?><?php echo $i < $win || $i > $win + 4 ? ' is-extra' : ''; ?>" type="button" data-full="<?php echo esc_url( $img['full'] ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Photo %d', 'gp-house' ), $i + 1 ) ); ?>"<?php echo $i === $active ? ' aria-current="true"' : ''; ?>>
						<img src="<?php echo esc_url( $img['thumb'] ); ?>" alt="" loading="lazy">
					</button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="gp-room__card">
		<div class="gp-room__body">
			<div class="gp-room__intro">
				<div class="gp-room__text">
					<h2 class="gp-room__title"><a href="<?php echo esc_url( $room['url'] ); ?>"><?php echo esc_html( $room['title'] ); ?></a></h2>
					<p class="gp-room__desc"><?php echo esc_html( $room['excerpt'] ); ?></p>
				</div>
				<?php if ( $room['highlights'] ) : ?>
					<ul class="gp-room__highlights">
						<?php foreach ( $room['highlights'] as $line ) : ?>
							<li><?php gp_icon( 'circle-check', 16 ); ?><?php echo esc_html( $line ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
			<dl class="gp-room__specs">
				<?php foreach ( $specs as $column ) : ?>
					<div class="gp-room__spec-col">
						<?php foreach ( $column as $spec ) : ?>
							<div><dt><?php echo esc_html( $spec[0] ); ?></dt><dd><?php echo esc_html( $spec[1] ); ?></dd></div>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
			</dl>
		</div>
		<div class="gp-room__foot">
			<p class="gp-room__price"><strong><?php echo esc_html( gp_price( $room['price'] ) ); ?></strong> <span>/ night</span></p>
			<div class="gp-room__actions">
				<?php
				gp_button( array( 'label' => __( 'Details', 'gp-house' ), 'url' => $room['url'], 'variant' => 'outline', 'size' => 'room', 'text' => 'navy' ) );
				gp_button( array( 'label' => __( 'Book', 'gp-house' ), 'url' => apply_filters( 'gp_house_room_book_url', $room['url'] . '#reserve', $room ), 'size' => 'room' ) );
				?>
			</div>
		</div>
	</div>
</article>
