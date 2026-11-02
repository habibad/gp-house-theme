<?php
/**
 * Guest testimonial card (Figma: Review / Frame 414, 360 wide).
 *
 * @var array $args { testimonial: array (gp_get_testimonials() shape) }
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$t = $args['testimonial'];
?>
<figure class="gp-testimonial">
	<figcaption class="gp-testimonial__who">
		<img src="<?php echo esc_url( $t['avatar'] ); ?>" width="44" height="44" alt="" loading="lazy">
		<span>
			<strong><?php echo esc_html( $t['name'] ); ?></strong>
			<span><?php echo esc_html( $t['location'] ); ?></span>
		</span>
	</figcaption>
	<blockquote><?php echo esc_html( $t['quote'] ); ?></blockquote>
</figure>
