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
// Thumbnail carousel: up to 6 photos in view (main.js fits fewer on narrow cards). The arrows step the highlighted
// photo one at a time and the strip slides one photo whenever the highlight reaches an edge.
$size    = min( 6, count( $gallery ) );
$win     = max( 0, min( count( $gallery ) - $size, $active - $size + 1 ) );
$specs   = array(
	array( array( __( 'Area', 'gp-house' ), $room['area'] ), array( __( 'Guests', 'gp-house' ), $room['guests'] ) ),
	array( array( __( 'Bed', 'gp-house' ), $room['bed'] ), array( __( 'View', 'gp-house' ), $room['view'] ) ),
);
// Leave out empty specs (rooms imported from eZee have no area / view yet) and keep two columns.
$specs   = array_values( array_filter( array_merge( ...$specs ), fn( $x ) => '' !== trim( (string) $x[1] ) ) );
$specs   = $specs ? array_chunk( $specs, (int) ceil( count( $specs ) / 2 ) ) : array();
?>
<article class="gp-room" data-room data-location="<?php echo esc_attr( $room['loc_slug'] ); ?>" data-type="<?php echo esc_attr( $room['type'] ); ?>" data-beds="<?php echo esc_attr( implode( ' ', $room['beds'] ) ); ?>" data-tags="<?php echo esc_attr( implode( ' ', $room['tags'] ) ); ?>" data-price="<?php echo esc_attr( $room['price'] ); ?>" data-area="<?php echo esc_attr( (int) $room['area'] ); ?>"<?php echo empty( $room['booking_property'] ) ? '' : ' data-room-id="' . esc_attr( $room['id'] ) . '" data-property="' . esc_attr( $room['booking_property'] ) . '"'; ?><?php echo empty( $args['hidden'] ) ? '' : ' hidden'; ?>>
	<div class="gp-room__media" data-gallery>
		<img class="gp-room__photo" src="<?php echo esc_url( $main ); ?>" alt="<?php echo esc_attr( $room['title'] ); ?>" loading="lazy" data-gallery-main>
		<?php gp_chip( $room['location'] ); ?>
		<?php $gp_saved = function_exists( 'gp_is_saved' ) && gp_is_saved( 'room-' . $room['id'] ); ?>
		<button class="gp-fav<?php echo $gp_saved ? ' is-active' : ''; ?>" type="button" aria-pressed="<?php echo $gp_saved ? 'true' : 'false'; ?>" aria-label="<?php esc_attr_e( 'Save room', 'gp-house' ); ?>" data-fav="room-<?php echo esc_attr( $room['id'] ); ?>">
			<img class="gp-fav__off" src="<?php echo esc_url( gp_img( 'icons/heart.svg' ) ); ?>" width="20" height="20" alt="">
			<img class="gp-fav__on" src="<?php echo esc_url( gp_img( 'icons/heart-filled.svg' ) ); ?>" width="20" height="20" alt="">
		</button>
		<?php if ( count( $gallery ) > 1 ) : ?>
			<div class="gp-room__thumbs" data-thumbs style="--n:<?php echo (int) $size; ?>;--start:<?php echo (int) $win; ?>">
				<button class="gp-room__tarrow gp-room__tarrow--prev" type="button" data-thumbs-prev aria-label="<?php esc_attr_e( 'Previous photo', 'gp-house' ); ?>"<?php disabled( 0, $active ); ?>><svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M15 6C15 6 9 10.419 9 12c0 1.581 6 6 6 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
				<div class="gp-room__thumbs-view">
					<div class="gp-room__thumbs-list">
						<?php foreach ( $gallery as $i => $img ) : ?>
							<button class="gp-room__thumb<?php echo $i === $active ? ' is-active' : ''; ?>" type="button" data-full="<?php echo esc_url( $img['full'] ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Photo %d', 'gp-house' ), $i + 1 ) ); ?>"<?php echo $i === $active ? ' aria-current="true"' : ''; ?>>
								<img src="<?php echo esc_url( $img['thumb'] ); ?>" alt="" loading="lazy">
							</button>
						<?php endforeach; ?>
					</div>
				</div>
				<button class="gp-room__tarrow gp-room__tarrow--next" type="button" data-thumbs-next aria-label="<?php esc_attr_e( 'Next photo', 'gp-house' ); ?>"<?php disabled( count( $gallery ) - 1, $active ); ?>><svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M9 6c0 0 6 4.419 6 6 0 1.581-6 6-6 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
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
			<?php // Without dates: the lowest eZee price of the coming weeks ("From"). With the visitor's dates main.js shows that stay's price. ?>
			<div class="gp-room__pricing">
				<p class="gp-room__price" data-room-price><span data-room-from><?php esc_html_e( 'From', 'gp-house' ); ?></span> <s class="gp-room__was" data-room-was hidden></s> <strong data-room-night><?php echo esc_html( gp_money_thb( $room['price'], $room['price_thb'] ?? 0 ) ); ?></strong> <span>/ night</span></p>
				<p class="gp-room__stay" data-room-stay hidden></p>
			</div>
			<div class="gp-room__actions">
				<?php
				gp_button( array( 'label' => __( 'Details', 'gp-house' ), 'url' => $room['url'], 'variant' => 'outline', 'size' => 'room', 'text' => 'navy' ) );
				gp_button( array( 'label' => __( 'Book', 'gp-house' ), 'url' => apply_filters( 'gp_house_room_book_url', $room['url'] . '#reserve', $room ), 'size' => 'room' ) );
				?>
			</div>
		</div>
	</div>
</article>
