<?php
/**
 * Full-width navy promo band with a centred title, text and one button
 * (Figma: Offers "GP House Direct Booking Guarantee").
 *
 * @var array $args { title: string, text?: string, button?: array gp_button() args, image?: string bg URL, image_mobile?: string bg URL (≤600px), class?: string }
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$a = wp_parse_args( $args, array( 'title' => '', 'text' => '', 'button' => array(), 'image' => '', 'image_mobile' => '', 'class' => '' ) );
?>
<section class="gp-promo-band<?php echo $a['class'] ? ' ' . esc_attr( $a['class'] ) : ''; ?>"<?php
$style = ( $a['image'] ? '--bg:url(' . esc_url( $a['image'] ) . ');' : '' ) . ( $a['image_mobile'] ? '--bg-m:url(' . esc_url( $a['image_mobile'] ) . ');' : '' );
echo $style ? ' style="' . esc_attr( $style ) . '"' : '';
?>>
	<div class="gp-promo-band__inner">
		<h2 class="gp-promo-band__title"><?php echo esc_html( $a['title'] ); ?></h2>
		<?php if ( $a['text'] ) : ?>
			<p class="gp-promo-band__text"><?php echo esc_html( $a['text'] ); ?></p>
		<?php endif; ?>
		<?php
		if ( $a['button'] ) {
			gp_button( wp_parse_args( $a['button'], array( 'size' => 'tab', 'class' => 'gp-promo-band__btn' ) ) );
		}
		?>
	</div>
</section>
