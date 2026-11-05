<?php
/**
 * Offers page (/offers/) — Figma "Hero /Offers" (1148:7363), mobile 1438:10794.
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$offers     = gp_get_offers();
$hero_id    = (int) gp_opt( 'offers_hero_image' );
$band_image = gp_opt( 'offers_band_image' ) ? wp_get_attachment_image_url( (int) gp_opt( 'offers_band_image' ), 'full' ) : gp_img( 'offers/guarantee-bg.jpg' );

get_header();
?>
<main id="gp-main" class="gp-offers-page">
	<?php
	gp_component( 'page-hero', array(
		'image'  => $hero_id ? wp_get_attachment_image_url( $hero_id, 'full' ) : gp_img( 'offers/hero.jpg' ),
		'kicker' => gp_opt( 'offers_kicker' ),
		'title'  => gp_opt( 'offers_title' ),
		'text'   => gp_opt( 'offers_text' ),
		// The demo photo was exported from Figma with the shade already applied.
		'class'  => 'gp-page-hero--offers' . ( $hero_id ? '' : ' gp-page-hero--shaded' ),
	) );
	?>

	<section class="gp-offers" data-view-root>
		<div class="gp-container">
			<?php // Same filter behaviour as the Destinations region chips (main.js initRegionTabs). ?>
			<div class="gp-offers__tabs" role="group" aria-label="<?php esc_attr_e( 'Offer type', 'gp-house' ); ?>" data-region-tabs>
				<button class="gp-filter-chip is-active" type="button" aria-pressed="true" data-region-filter=""><?php esc_html_e( 'All', 'gp-house' ); ?></button>
				<?php foreach ( gp_offer_types() as $slug => $label ) : ?>
					<button class="gp-filter-chip" type="button" aria-pressed="false" data-region-filter="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $label ); ?></button>
				<?php endforeach; ?>
			</div>
			<div class="gp-offers__grid" data-region-items>
				<?php
				foreach ( $offers as $offer ) {
					gp_component( 'offer-card', array( 'offer' => $offer ) );
				}
				?>
			</div>
		</div>
	</section>

	<?php
	gp_component( 'promo-band', array(
		'title'  => gp_opt( 'offers_band_title' ),
		'text'   => gp_opt( 'offers_band_text' ),
		'button' => array( 'label' => gp_opt( 'offers_band_button' ), 'url' => get_post_type_archive_link( 'gp_stay' ) ?: home_url( '/stays/' ) ),
		'image'  => $band_image,
		'image_mobile' => gp_opt( 'offers_band_image' ) ? '' : gp_img( 'offers/guarantee-bg-mobile.jpg' ),
	) );
	?>
</main>
<?php
get_footer();
