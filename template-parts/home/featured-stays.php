<?php
/**
 * Featured Sanctuaries carousel (Figma: Frame 494 / 1148:16328).
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$stays = gp_get_stays( array( 'featured' => true ) );
if ( ! $stays ) {
	return;
}
?>
<section class="gp-featured" data-carousel>
	<div class="gp-container">
		<?php
		gp_component( 'section-head', array(
			'kicker'  => gp_opt( 'stays_kicker' ),
			'title'   => gp_opt( 'stays_title' ),
			'actions' => array( array( 'label' => __( 'View All', 'gp-house' ), 'url' => get_post_type_archive_link( 'gp_stay' ) ?: home_url( '/stays/' ), 'variant' => 'navy' ) ),
		) );
		?>
		<div class="gp-carousel">
			<button class="gp-carousel__arrow gp-carousel__arrow--prev" type="button" data-carousel-prev aria-label="<?php esc_attr_e( 'Previous', 'gp-house' ); ?>"><?php gp_icon( 'arrow-left', 24 ); ?></button>
			<div class="gp-carousel__track" data-carousel-track>
				<?php
				foreach ( $stays as $i => $stay ) {
					gp_component( 'stay-card', array( 'stay' => $stay, 'saved' => 0 === $i ) );
				}
				?>
			</div>
			<button class="gp-carousel__arrow gp-carousel__arrow--next" type="button" data-carousel-next aria-label="<?php esc_attr_e( 'Next', 'gp-house' ); ?>"><?php gp_icon( 'arrow-right', 24 ); ?></button>
		</div>
	</div>
</section>
