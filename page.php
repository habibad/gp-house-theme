<?php
/**
 * Generic content page (policies, terms, sustainability …) — edit the copy in WP admin.
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="gp-main" class="gp-doc">
	<div class="gp-container gp-doc__inner">
		<?php while ( have_posts() ) : the_post(); ?>
			<p class="gp-kicker"><?php esc_html_e( 'GP House', 'gp-house' ); ?></p>
			<h1 class="gp-doc__title"><?php the_title(); ?></h1>
			<div class="gp-doc__content"><?php the_content(); ?></div>
		<?php endwhile; ?>
	</div>
</main>
<?php
get_footer();
