<?php
/**
 * Home page (Figma: Home / "GP House Update UI 3").
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="gp-main" class="gp-home">
	<?php
	get_template_part( 'template-parts/home/hero' );
	get_template_part( 'template-parts/home/destinations' );
	get_template_part( 'template-parts/home/featured-stays' );
	get_template_part( 'template-parts/home/why-direct' );
	get_template_part( 'template-parts/home/reviews' );
	get_template_part( 'template-parts/home/journal' );
	?>
</main>
<?php
get_footer();
