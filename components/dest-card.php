<?php
/**
 * Destination card (Figma: "Card 1", 360 × 580).
 *
 * @var array $args { dest: array (gp_get_destinations() shape) }
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$dest = $args['dest'];
?>
<a class="gp-dest-card" href="<?php echo esc_url( $dest['url'] ); ?>">
	<img src="<?php echo esc_url( $dest['image'] ); ?>" alt="" loading="lazy">
	<span class="gp-dest-card__go"><?php gp_icon( 'arrow-up-right', 20 ); ?></span>
	<span class="gp-dest-card__shade" aria-hidden="true"></span>
	<span class="gp-dest-card__text">
		<strong><?php echo esc_html( $dest['card_title'] ); ?></strong>
		<?php if ( $dest['subtitle'] ) : ?>
			<span><?php echo esc_html( $dest['subtitle'] ); ?></span>
		<?php endif; ?>
	</span>
</a>
