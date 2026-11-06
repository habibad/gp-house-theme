<?php
/**
 * 404 — page not found.
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="gp-main" class="gp-notfound">
	<div class="gp-container">
		<p class="gp-notfound__code">404</p>
		<h1 class="gp-notfound__title"><?php esc_html_e( 'This Page Has Wandered Off', 'gp-house' ); ?></h1>
		<p class="gp-notfound__text"><?php esc_html_e( 'The page you are looking for may have moved or no longer exists. Let us take you somewhere beautiful instead.', 'gp-house' ); ?></p>
		<div class="gp-notfound__actions">
			<?php
			gp_button( array( 'label' => __( 'Back To Home', 'gp-house' ), 'url' => home_url( '/' ), 'size' => 'tab' ) );
			gp_button( array( 'label' => __( 'Browse Stays', 'gp-house' ), 'url' => home_url( '/stays/' ), 'size' => 'tab', 'variant' => 'outline', 'text' => 'navy' ) );
			?>
		</div>
	</div>
</main>
<?php
get_footer();
