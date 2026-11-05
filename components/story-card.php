<?php
/**
 * Journal story card — wide image with floating article card
 * (Figma: The GP Journal / Frame 432, 1128 × 420).
 *
 * @var array $args { story: array (gp_get_journal() shape), index?: int, class?: string ('gp-story--flat' for plain lists) }
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$story = $args['story'];
$index = (int) ( $args['index'] ?? 0 );
$class = trim( 'gp-story ' . ( $args['class'] ?? '' ) );
?>
<article class="<?php echo esc_attr( $class ); ?>" data-location="<?php echo esc_attr( $story['location_slug'] ); ?>" style="--i:<?php echo (int) $index; ?>">
	<img class="gp-story__image" src="<?php echo esc_url( $story['image'] ); ?>" alt="" loading="lazy">
	<span class="gp-story__word" aria-hidden="true"><?php echo esc_html( $story['location'] ); ?></span>
	<?php gp_chip( $story['location'], 'story' ); ?>
	<div class="gp-story__card">
		<div class="gp-story__main">
			<div class="gp-story__meta">
				<span class="gp-meta-chip"><?php gp_icon( 'time', 16 ); ?><?php echo esc_html( $story['date'] ); ?></span>
				<?php if ( $story['read_time'] ) : ?>
					<span class="gp-meta-chip"><?php gp_icon( 'time', 16 ); ?><?php echo esc_html( $story['read_time'] ); ?></span>
				<?php endif; ?>
			</div>
			<div class="gp-story__text">
				<h3><a href="<?php echo esc_url( $story['url'] ); ?>"><?php echo esc_html( $story['title'] ); ?></a></h3>
				<p><?php echo esc_html( $story['excerpt'] ); ?></p>
			</div>
		</div>
		<div class="gp-story__foot">
			<div class="gp-story__author">
				<img src="<?php echo esc_url( $story['author_avatar'] ); ?>" width="38" height="38" alt="" loading="lazy">
				<span>
					<strong><?php echo esc_html( $story['author_name'] ); ?></strong>
					<span><?php echo esc_html( $story['author_role'] ); ?></span>
				</span>
			</div>
			<a class="gp-link-arrow" href="<?php echo esc_url( $story['url'] ); ?>"><?php esc_html_e( 'Read Story', 'gp-house' ); ?><?php gp_icon( 'arrow-right-sm', 16 ); ?></a>
		</div>
	</div>
</article>
