<?php
/**
 * Fallback template. Dedicated page templates are added page by page from the
 * Figma design.
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="gp-main" class="gp-page">
	<div class="gp-container">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class( 'gp-entry' ); ?>>
					<?php if ( is_singular() ) : ?>
						<h1 class="gp-title"><?php the_title(); ?></h1>
						<div class="gp-entry__content"><?php the_content(); ?></div>
					<?php else : ?>
						<h2 class="gp-entry__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<div class="gp-entry__content"><?php the_excerpt(); ?></div>
					<?php endif; ?>
				</article>
			<?php endwhile; ?>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<h1 class="gp-title"><?php esc_html_e( 'Nothing found', 'gp-house' ); ?></h1>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
