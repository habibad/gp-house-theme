<?php
/**
 * Inner page hero (Figma: "Hero /Destinations", 1440 × 562). Used by Stays,
 * Destinations, Offers, Club, Journal and About.
 *
 * @var array $args {
 *   image: string URL (pre-cropped to 1440 × 562), kicker: string, title: string, text?: string,
 *   image_mobile?: string URL (uncropped image for the 402px layout), image_position?: string CSS object-position
 * }
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args( $args, array( 'class' => '', 'image_mobile' => '', 'image' => '', 'kicker' => '', 'title' => '', 'text' => '', 'image_position' => '50% 50%' ) );
?>
<section class="gp-page-hero<?php echo $args['class'] ? ' ' . esc_attr( $args['class'] ) : ''; ?>">
	<?php if ( $args['image'] ) : ?>
		<picture>
			<?php if ( $args['image_mobile'] ) : ?>
				<source media="(max-width: 600px)" srcset="<?php echo esc_url( $args['image_mobile'] ); ?>">
			<?php endif; ?>
			<img class="gp-page-hero__image" src="<?php echo esc_url( $args['image'] ); ?>" alt="" fetchpriority="high" style="object-position:<?php echo esc_attr( $args['image_position'] ); ?>">
		</picture>
	<?php endif; ?>
	<div class="gp-page-hero__shade" aria-hidden="true"></div>
	<div class="gp-page-hero__content gp-container">
		<div class="gp-page-hero__heading">
			<p class="gp-page-hero__kicker"><?php echo esc_html( $args['kicker'] ); ?></p>
			<h1 class="gp-page-hero__title"><?php echo esc_html( $args['title'] ); ?></h1>
		</div>
		<?php if ( $args['text'] ) : ?>
			<p class="gp-page-hero__text"><?php echo esc_html( $args['text'] ); ?></p>
		<?php endif; ?>
	</div>
</section>
