<?php
/**
 * Destination page (/destinations/<slug>/) — Figma "All Thailand Destinations/
 * overview" (1148:10747) and "FAQs" (1148:10734).
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$term    = get_queried_object();
$dest_page    = gp_get_destination_page( $term );
$rooms   = gp_get_rooms( array( 'destination' => $term->slug ) );
$stories = gp_get_journal( 3, array( 'destination' => $term->slug ) );
$tabs    = array_map( function ( $d ) use ( $term ) {
	return array( 'label' => $d['name'], 'url' => $d['url'], 'active' => $d['slug'] === $term->slug );
}, array_values( array_filter( gp_get_destinations(), function ( $d ) {
	return ! empty( $d['image'] );
} ) ) );

get_header();
?>
<main id="gp-main" class="gp-destination-page">
	<?php
	gp_component( 'page-hero', array(
		'image'  => $dest_page['hero'],
		'image_mobile' => $dest_page['hero_mobile'],
		'kicker' => $dest_page['name'],
		'title'  => $dest_page['name'],
		'text'   => $dest_page['hero_text'],
		'tabs'   => $tabs,
		'class'  => 'gp-page-hero--tabs' . ( $dest_page['hero_shaded'] ? ' gp-page-hero--shaded' : '' ),
	) );
	?>

	<?php if ( $rooms ) : ?>
		<section class="gp-dest-rooms">
			<div class="gp-container">
				<?php // Mobile-only view switch (Figma 1435:8694); Map View opens the Destinations map. ?>
				<div class="gp-tabs gp-dest-rooms__views" role="tablist">
					<span class="gp-tabs__tab is-active" role="tab" aria-selected="true"><?php esc_html_e( 'Grid View', 'gp-house' ); ?></span>
					<a class="gp-tabs__tab" role="tab" aria-selected="false" href="<?php echo esc_url( home_url( '/destinations/?view=map' ) ); ?>"><?php esc_html_e( 'Map View', 'gp-house' ); ?></a>
				</div>
				<?php gp_component( 'section-head', array( 'kicker' => __( 'Accommodations', 'gp-house' ), 'title' => sprintf( __( 'Rooms & Villas in %s', 'gp-house' ), $dest_page['name'] ) ) ); ?>
				<div class="gp-dest-rooms__grid">
					<?php
					foreach ( array_slice( $rooms, 0, 3 ) as $room ) {
						gp_component( 'stay-card', array(
							'stay'    => array(
								'id'       => $room['id'],
								'title'    => $room['title'],
								'excerpt'  => $room['excerpt'],
								'price'    => $room['price'],
								'image'    => $room['gallery'][ $room['active'] ]['full'] ?? '',
								'url'      => $room['url'],
								'location' => gp_room_chip_label( $room['type'] ),
							),
							'variant' => 'region',
							'cta'     => __( 'Book Now', 'gp-house' ),
						) );
					}
					?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( $dest_page['insider'] ) : ?>
		<section class="gp-insider">
			<div class="gp-container gp-insider__inner">
				<picture class="gp-insider__picture">
					<?php if ( $dest_page['insider_mobile'] ) : ?>
						<source media="(max-width: 600px)" srcset="<?php echo esc_url( $dest_page['insider_mobile'] ); ?>">
					<?php endif; ?>
					<img class="gp-insider__image" src="<?php echo esc_url( $dest_page['insider_image'] ); ?>" alt="" loading="lazy">
				</picture>
				<div class="gp-insider__content">
					<?php gp_component( 'section-head', array( 'kicker' => __( 'Curated Destination Guide', 'gp-house' ), 'title' => sprintf( __( 'Insider Secrets of %s', 'gp-house' ), $dest_page['name'] ), 'title_class' => 'gp-title--h3' ) ); ?>
					<ul class="gp-insider__list">
						<?php foreach ( $dest_page['insider'] as $i => $item ) : ?>
							<li class="gp-insider__item<?php echo 0 === $i ? ' is-active' : ''; ?>" tabindex="0">
								<span class="gp-insider__icon"><?php gp_icon( sanitize_key( $item[0] ), 20 ); ?></span>
								<span class="gp-insider__text">
									<strong><?php echo esc_html( $item[1] ); ?></strong>
									<span><?php echo esc_html( $item[2] ); ?></span>
								</span>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( $stories ) : ?>
		<section class="gp-dest-articles">
			<div class="gp-container">
				<?php
				gp_component( 'section-head', array(
					'kicker'  => __( 'Stories & Culture', 'gp-house' ),
					'title'   => sprintf( __( 'Articles & Journal: %s', 'gp-house' ), $dest_page['name'] ),
					'actions' => array( array( 'label' => __( 'View All', 'gp-house' ), 'url' => home_url( '/journal/' ), 'variant' => 'navy' ) ),
				) );
				?>
				<div class="gp-dest-articles__grid">
					<?php
					foreach ( $stories as $story ) {
						gp_component( 'article-card', array( 'story' => $story ) );
					}
					?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php gp_component( 'faq', array( 'items' => $dest_page['faqs'] ) ); ?>
</main>
<?php
get_footer();
