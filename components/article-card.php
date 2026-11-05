<?php
/**
 * Article card (Figma: destination page "Articles & Journal" card, 360 wide).
 *
 * @var array $args { story: array (gp_get_journal() shape) }
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$s = $args['story'];
?>
<article class="gp-article-card">
	<a class="gp-article-card__media" href="<?php echo esc_url( $s['url'] ); ?>" tabindex="-1">
		<img src="<?php echo esc_url( $s['card_image'] ?: $s['image'] ); ?>" alt="" loading="lazy">
		<?php gp_chip( $s['author_role'] ); ?>
	</a>
	<div class="gp-article-card__body">
		<p class="gp-article-card__date"><?php gp_icon( 'clock', 16 ); ?><?php echo esc_html( $s['date'] ); ?></p>
		<h3 class="gp-article-card__title"><a href="<?php echo esc_url( $s['url'] ); ?>"><?php echo esc_html( $s['title'] ); ?></a></h3>
		<p class="gp-article-card__excerpt"><?php echo esc_html( $s['excerpt'] ); ?></p>
	</div>
	<a class="gp-link-arrow gp-article-card__link" href="<?php echo esc_url( $s['url'] ); ?>"><?php esc_html_e( 'Read Article', 'gp-house' ); ?><?php gp_icon( 'arrow-right-sm', 16 ); ?></a>
</article>
