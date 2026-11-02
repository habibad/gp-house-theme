<?php
/**
 * Explore Destinations — map view (1148:16216) and grid view (1148:6778).
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$stays        = array_values( array_filter( gp_get_stays( array( 'map' => true ) ), function ( $s ) {
	return count( $s['pin'] ) === 2;
} ) );
$destinations = array_values( array_filter( gp_get_destinations(), function ( $d ) {
	return ! empty( $d['image'] );
} ) );
$active       = 0;
foreach ( $stays as $i => $s ) {
	if ( $s['map_active'] ) {
		$active = $i;
	}
}
$map_regions = array_values( array_unique( wp_list_pluck( $stays, 'location' ) ) );
?>
<section class="gp-explore" data-view="map">
	<a class="gp-chatbot" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" aria-label="<?php esc_attr_e( 'Chat with GP House', 'gp-house' ); ?>">
		<img src="<?php echo esc_url( gp_img( 'icons/chatbot.svg' ) ); ?>" width="100" height="100" alt="">
	</a>

	<div class="gp-container">
		<?php
		gp_component( 'section-head', array(
			'kicker'       => gp_opt( 'dest_kicker' ),
			'title'        => gp_opt( 'dest_title' ),
			'kicker_class' => 'gp-kicker--lh15',
			'actions_html' => function () {
				?>
				<div class="gp-tabs" role="tablist">
					<button class="gp-tabs__tab is-active" type="button" role="tab" aria-selected="true" data-view-btn="map"><?php esc_html_e( 'Map View', 'gp-house' ); ?></button>
					<button class="gp-tabs__tab" type="button" role="tab" aria-selected="false" data-view-btn="grid"><?php esc_html_e( 'Grid view', 'gp-house' ); ?></button>
				</div>
				<?php
			},
			'actions'      => array( array( 'label' => __( 'View All', 'gp-house' ), 'url' => home_url( '/destinations/' ), 'variant' => 'navy' ) ),
		) );
		?>
	</div>

	<?php if ( $stays ) : ?>
		<div class="gp-map" data-panel="map" data-active="<?php echo esc_attr( $active ); ?>">
			<picture>
				<source media="(max-width: 600px)" srcset="<?php echo esc_url( gp_img( 'photos/map-mobile.jpg' ) ); ?>">
				<img class="gp-map__image" src="<?php echo esc_url( gp_img( 'photos/map.jpg' ) ); ?>" alt="" loading="lazy">
			</picture>

			<div class="gp-map__regions gp-tabs gp-tabs--white">
				<?php foreach ( $map_regions as $i => $region ) : ?>
					<button class="gp-tabs__tab<?php echo 0 === $i ? ' is-active' : ''; ?>" type="button" data-region="<?php echo esc_attr( $region ); ?>"><?php echo esc_html( $region ); ?></button>
				<?php endforeach; ?>
			</div>

			<div class="gp-map__zoom">
				<button type="button" data-zoom="in" aria-label="<?php esc_attr_e( 'Zoom in', 'gp-house' ); ?>"><?php gp_icon( 'plus', 24 ); ?></button>
				<button type="button" data-zoom="out" aria-label="<?php esc_attr_e( 'Zoom out', 'gp-house' ); ?>"><?php gp_icon( 'minus', 24 ); ?></button>
			</div>

			<img class="gp-map__area" src="<?php echo esc_url( gp_img( 'decor/map-area.svg' ) ); ?>" alt="" aria-hidden="true">

			<?php foreach ( $stays as $i => $stay ) : ?>
				<?php
				$pin_m = count( $stay['pin_mobile'] ) === 2 ? $stay['pin_mobile'] : $stay['pin'];
				$style = sprintf( '--x:%spx;--y:%spx;--mx:%spx;--my:%spx', (float) $stay['pin'][0], (float) $stay['pin'][1], (float) $pin_m[0], (float) $pin_m[1] );
				?>
				<button class="gp-pin" type="button" style="<?php echo esc_attr( $style ); ?>" data-stay="<?php echo esc_attr( $i ); ?>">
					<span class="gp-pin__label">
						<img src="<?php echo esc_url( $stay['thumb'] ); ?>" width="32" height="32" alt="" loading="lazy">
						<span>
							<span class="gp-pin__name"><?php echo esc_html( $stay['title'] ); ?></span>
							<span class="gp-pin__price"><?php printf( esc_html__( 'From %s / night', 'gp-house' ), esc_html( gp_price( $stay['price'] ) ) ); ?></span>
						</span>
					</span>
					<img class="gp-pin__tail" src="<?php echo esc_url( gp_img( 'decor/map-pin-tail.svg' ) ); ?>" width="18" height="10" alt="">
					<span class="gp-pin__num"><?php echo (int) $i + 1; ?></span>
				</button>
			<?php endforeach; ?>

			<div class="gp-map__panel">
				<?php foreach ( $stays as $i => $stay ) : ?>
					<article class="gp-map-card" data-stay-card="<?php echo esc_attr( $i ); ?>"<?php echo $i === $active ? '' : ' hidden'; ?>>
						<div class="gp-map-card__media">
							<img src="<?php echo esc_url( $stay['image_main'] ); ?>" alt="<?php echo esc_attr( $stay['title'] ); ?>" loading="lazy">
							<span class="gp-chip gp-chip--pill"><?php echo esc_html( $stay['location'] ); ?></span>
						</div>
						<div class="gp-map-card__body">
							<div class="gp-map-card__main">
								<div class="gp-map-card__info">
									<div class="gp-map-card__top">
										<p class="gp-map-card__tagline"><?php echo esc_html( $stay['tagline'] ); ?></p>
										<span class="gp-chip gp-chip--pill gp-chip--sand"><?php printf( esc_html( _n( '%s review', '%s reviews', $stay['reviews'], 'gp-house' ) ), esc_html( number_format_i18n( $stay['reviews'] ) ) ); ?></span>
									</div>
									<div class="gp-map-card__titles">
										<h3 class="gp-map-card__title"><?php echo esc_html( $stay['title'] ); ?></h3>
										<?php if ( $stay['amenities'] ) : ?>
											<ul class="gp-amenities">
												<?php foreach ( $stay['amenities'] as $amenity ) : ?>
													<li><?php gp_icon( $amenity['icon'], 16 ); ?><?php echo esc_html( $amenity['text'] ); ?></li>
												<?php endforeach; ?>
											</ul>
										<?php endif; ?>
									</div>
								</div>
								<p class="gp-map-card__desc"><?php echo esc_html( $stay['excerpt'] ); ?></p>
							</div>
							<div class="gp-map-card__foot">
								<p class="gp-map-card__price"><span><?php esc_html_e( 'Direct booking from', 'gp-house' ); ?></span> <strong><?php echo esc_html( gp_price( $stay['price'] ) ); ?>/night</strong></p>
								<a class="gp-btn gp-btn--primary gp-btn--md gp-btn--navy-text gp-map-card__cta" href="<?php echo esc_url( $stay['url'] ); ?>"><?php esc_html_e( 'Reserve', 'gp-house' ); ?></a>
							</div>
						</div>
					</article>
				<?php endforeach; ?>

				<div class="gp-map__list">
					<?php foreach ( $stays as $i => $stay ) : ?>
						<button class="gp-mini-card<?php echo $i === $active ? ' is-active' : ''; ?>" type="button" data-stay="<?php echo esc_attr( $i ); ?>">
							<img src="<?php echo esc_url( $stay['thumb'] ); ?>" alt="" loading="lazy">
							<span class="gp-mini-card__body">
								<span class="gp-mini-card__location"><?php echo esc_html( $stay['location'] ); ?></span>
								<span class="gp-mini-card__price"><?php echo esc_html( gp_price( $stay['price'] ) ); ?>/night</span>
								<span class="gp-mini-card__name"><?php echo esc_html( $stay['title'] ); ?></span>
							</span>
						</button>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<div class="gp-container gp-dest-grid" data-panel="grid" hidden>
		<?php
		foreach ( $destinations as $dest ) {
			gp_component( 'dest-card', array( 'dest' => $dest ) );
		}
		?>
	</div>
</section>
