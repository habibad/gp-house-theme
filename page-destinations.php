<?php
/**
 * Destinations page (/destinations/) — Figma "Hero /Destinations" (1148:6972,
 * grid view) and "Destination Curations / map" (1148:7060, map view).
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$regions = gp_get_region_cards();
$stays   = array_values( array_filter( gp_get_stays( array( 'map' => true ) ), function ( $s ) {
	return count( $s['pin'] ) === 2;
} ) );
$active  = 0;
foreach ( $stays as $i => $s ) {
	if ( $s['map_active'] ) {
		$active = $i;
	}
}
$view = isset( $_GET['view'] ) && 'map' === $_GET['view'] ? 'map' : 'grid'; // phpcs:ignore WordPress.Security.NonceVerification

get_header();
?>
<main id="gp-main" class="gp-destinations-page">
	<?php
	gp_component( 'page-hero', array(
		'image'  => gp_opt( 'dpage_hero' ) ? wp_get_attachment_image_url( (int) gp_opt( 'dpage_hero' ), 'full' ) : gp_img( 'destinations/hero-thailand.jpg' ),
		'kicker' => gp_opt( 'dpage_kicker' ),
		'title'  => gp_opt( 'dpage_title' ),
		'text'   => gp_opt( 'dpage_text' ),
		'class'  => 'gp-page-hero--narrow-text',
	) );
	?>

	<section class="gp-destinations" data-view-root data-view="<?php echo esc_attr( $view ); ?>">
		<div class="gp-container">
			<div class="gp-destinations__bar">
				<div class="gp-region-tabs" role="group" aria-label="<?php esc_attr_e( 'Regions', 'gp-house' ); ?>" data-region-tabs>
					<button class="gp-filter-chip is-active" type="button" aria-pressed="true" data-region-filter=""><?php esc_html_e( 'All Regions', 'gp-house' ); ?></button>
					<?php foreach ( $regions as $r ) : ?>
						<button class="gp-filter-chip" type="button" aria-pressed="false" data-region-filter="<?php echo esc_attr( $r['loc_slug'] ); ?>" data-region-name="<?php echo esc_attr( $r['location'] ); ?>"><?php echo esc_html( $r['location'] ); ?></button>
					<?php endforeach; ?>
				</div>
				<div class="gp-tabs" role="tablist">
					<?php foreach ( array( 'grid' => __( 'Grid view', 'gp-house' ), 'map' => __( 'Map View', 'gp-house' ) ) as $key => $label ) : ?>
						<button class="gp-tabs__tab<?php echo $key === $view ? ' is-active' : ''; ?>" type="button" role="tab" aria-selected="<?php echo $key === $view ? 'true' : 'false'; ?>" data-view-btn="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></button>
					<?php endforeach; ?>
				</div>
			</div>
		</div>

		<div class="gp-destinations__panel gp-destinations__panel--grid" data-panel="grid"<?php echo 'grid' === $view ? '' : ' hidden'; ?>>
			<div class="gp-container">
				<?php gp_component( 'section-head', array( 'kicker' => gp_opt( 'dpage_grid_kicker' ), 'title' => gp_opt( 'dpage_grid_title' ), 'title_class' => 'gp-title--h3' ) ); ?>
				<div class="gp-destinations__cards" data-region-items>
					<?php
					foreach ( $regions as $r ) {
						gp_component( 'stay-card', array( 'stay' => $r, 'variant' => 'region', 'cta' => __( 'Explore Stays', 'gp-house' ) ) );
					}
					?>
				</div>
			</div>
		</div>

		<div class="gp-destinations__panel gp-destinations__panel--map" data-panel="map"<?php echo 'map' === $view ? '' : ' hidden'; ?>>
			<div class="gp-container">
				<?php gp_component( 'section-head', array( 'kicker' => gp_opt( 'dpage_map_kicker' ), 'title' => gp_opt( 'dpage_map_title' ), 'title_class' => 'gp-title--h3' ) ); ?>
			</div>
			<?php
			if ( $stays ) {
				gp_component( 'stay-map', array( 'stays' => $stays, 'active' => $active ) );
			}
			?>
		</div>
	</section>
</main>
<?php
get_footer();
