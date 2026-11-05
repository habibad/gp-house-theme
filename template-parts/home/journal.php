<?php
/**
 * The GP Journal — filter tabs + stacking story cards
 * (Figma: 1148:16524, stacked end state 1148:18057).
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$stories = gp_get_journal( 3, array( 'home' => true ) );
if ( ! $stories ) {
	return;
}
$tabs  = gp_get_destinations();
$order = apply_filters( 'gp_house_journal_tab_order', array( 'phuket', 'bangkok', 'bali', 'krabi' ) );
usort( $tabs, function ( $a, $b ) use ( $order ) {
	$pa = array_search( $a['slug'], $order, true );
	$pb = array_search( $b['slug'], $order, true );
	return ( false === $pa ? PHP_INT_MAX : $pa ) <=> ( false === $pb ? PHP_INT_MAX : $pb );
} );
?>
<section class="gp-journal" data-journal>
	<img class="gp-journal__dots" src="<?php echo esc_url( gp_img( 'decor/journal-dots.png' ) ); ?>" alt="" aria-hidden="true" loading="lazy">
	<img class="gp-journal__glow gp-journal__glow--l" src="<?php echo esc_url( gp_img( 'decor/journal-ellipse-79.svg' ) ); ?>" alt="" aria-hidden="true">
	<img class="gp-journal__glow gp-journal__glow--r" src="<?php echo esc_url( gp_img( 'decor/journal-ellipse-80.svg' ) ); ?>" alt="" aria-hidden="true">

	<div class="gp-container">
		<div class="gp-journal__head">
			<?php gp_component( 'section-head', array( 'kicker' => gp_opt( 'journal_kicker' ), 'title' => gp_opt( 'journal_title' ), 'tone' => 'light', 'align' => 'center' ) ); ?>
			<div class="gp-pills" role="tablist">
				<button class="gp-pills__tab is-active" type="button" data-filter=""><?php esc_html_e( 'All', 'gp-house' ); ?></button>
				<?php foreach ( $tabs as $tab ) : ?>
					<button class="gp-pills__tab" type="button" data-filter="<?php echo esc_attr( $tab['slug'] ); ?>"><?php echo esc_html( $tab['name'] ); ?></button>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="gp-stack" data-stack>
			<?php
			foreach ( $stories as $i => $story ) {
				gp_component( 'story-card', array( 'story' => $story, 'index' => $i ) );
			}
			?>
		</div>

		<div class="gp-journal__more">
			<?php gp_button( array( 'label' => __( 'View All Stories', 'gp-house' ), 'url' => home_url( '/journal/' ) ) ); ?>
		</div>
	</div>
</section>
