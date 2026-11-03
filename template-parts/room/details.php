<?php
/**
 * Room page left column: specifications, concept, features, amenities
 * (Figma 1148:10245).
 *
 * @var array $args { room: array }
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$room  = $args['room'];
$specs = array(
	array(
		array( 'expand', __( 'Living Space:', 'gp-house' ), str_ireplace( 'sqm', 'm²', $room['area'] ) ),
		array( 'users-sm', __( 'Max Occupancy:', 'gp-house' ), $room['max_guests'] ? sprintf( __( 'Up to %d', 'gp-house' ), $room['max_guests'] ) : $room['guests'] ),
	),
	array(
		array( 'bed', __( 'Bed Configuration:', 'gp-house' ), $room['bed'] ),
		array( 'clock', __( 'Orientation & View:', 'gp-house' ), $room['view'] ),
	),
);
$intro = $room['amenities_intro'] ?: __( 'Thoughtfully appointed with bespoke luxury comforts and sustainable Thai botanical wellness essentials.', 'gp-house' );
?>
<div class="gp-room-info">
	<div class="gp-room-info__block">
		<h2 class="gp-room-info__title"><?php esc_html_e( 'Room Specifications', 'gp-house' ); ?></h2>
		<div class="gp-room-info__specs">
			<?php foreach ( $specs as $column ) : ?>
				<ul>
					<?php foreach ( $column as $spec ) : ?>
						<li><?php gp_icon( $spec[0], 16 ); ?><span><?php echo esc_html( $spec[1] ); ?> <strong><?php echo esc_html( $spec[2] ); ?></strong></span></li>
					<?php endforeach; ?>
				</ul>
			<?php endforeach; ?>
		</div>
	</div>

	<?php if ( $room['concept'] ) : ?>
		<div class="gp-room-info__block gp-room-info__block--concept">
			<h2 class="gp-room-info__title"><?php esc_html_e( 'Suite Concept & Architecture', 'gp-house' ); ?></h2>
			<p class="gp-room-info__text"><?php echo esc_html( $room['concept'] ); ?></p>
		</div>
	<?php endif; ?>

	<?php if ( $room['features'] ) : ?>
		<div class="gp-room-info__block gp-room-info__block--list">
			<h2 class="gp-room-info__title"><?php esc_html_e( 'Design & Sanctuary Features', 'gp-house' ); ?></h2>
			<ul class="gp-dot-list">
				<?php foreach ( $room['features'] as $line ) : ?>
					<li><?php echo esc_html( $line ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<?php if ( $room['amenities'] ) : ?>
		<div class="gp-room-info__block gp-room-info__block--amenities">
			<div class="gp-room-info__head">
				<h2 class="gp-room-info__title"><?php esc_html_e( 'Complete In-Room Amenities', 'gp-house' ); ?></h2>
				<p class="gp-room-info__text gp-room-info__text--intro"><?php echo esc_html( $intro ); ?></p>
			</div>
			<div class="gp-room-info__groups">
				<?php foreach ( $room['amenities'] as $group => $items ) : ?>
					<div class="gp-room-info__group">
						<h3><?php echo esc_html( $group ); ?></h3>
						<ul class="gp-dot-list">
							<?php foreach ( $items as $item ) : ?>
								<li><?php echo esc_html( $item ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>
</div>
