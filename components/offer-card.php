<?php
/**
 * Offer card (Figma: Offers page "Card", 554 × 703).
 *
 * @var array $args { offer: array (gp_get_offers() shape) }
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$o = $args['offer'];
?>
<article class="gp-offer-card" data-location="<?php echo esc_attr( $o['type'] ); ?>">
	<div class="gp-offer-card__media">
		<picture>
			<?php if ( $o['image_mobile'] ) : ?>
				<source media="(max-width: 600px)" srcset="<?php echo esc_url( $o['image_mobile'] ); ?>">
			<?php endif; ?>
			<img src="<?php echo esc_url( $o['image'] ); ?>" alt="" loading="lazy">
		</picture>
		<?php if ( $o['code'] ) : ?>
			<span class="gp-offer-card__code"><?php gp_icon( 'tag', 16 ); ?><?php echo esc_html( $o['code'] ); ?></span>
		<?php endif; ?>
	</div>
	<div class="gp-offer-card__body">
		<div class="gp-offer-card__intro">
			<p class="gp-offer-card__type"><?php echo esc_html( $o['type_label'] ); ?></p>
			<h2 class="gp-offer-card__title"><?php echo esc_html( $o['title'] ); ?></h2>
			<p class="gp-offer-card__desc"><?php echo esc_html( $o['excerpt'] ); ?></p>
		</div>
		<?php if ( $o['highlights'] ) : ?>
			<ul class="gp-offer-card__list">
				<?php foreach ( $o['highlights'] as $line ) : ?>
					<li><?php gp_icon( 'circle-check', 16 ); ?><?php echo esc_html( $line ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<div class="gp-offer-card__foot">
			<p class="gp-offer-card__save"><?php echo esc_html( $o['save'] ); ?></p>
			<?php gp_button( array( 'label' => __( 'Book With Code', 'gp-house' ), 'url' => $o['url'], 'size' => 'xs' ) ); ?>
		</div>
	</div>
</article>
