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
?>
<section class="gp-explore" data-view="map" data-view-root>
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

	<?php
	if ( $stays ) {
		gp_component( 'stay-map', array( 'stays' => $stays, 'active' => $active, 'attrs' => ' data-panel="map"' ) );
	}
	?>

	<div class="gp-container gp-dest-grid" data-panel="grid" hidden>
		<?php
		foreach ( $destinations as $dest ) {
			gp_component( 'dest-card', array( 'dest' => $dest ) );
		}
		?>
	</div>
</section>
