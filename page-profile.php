<?php
/**
 * Member area (/profile/ and its sections) — Figma "Profile" frames, mobile 1454:*.
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$section  = gp_profile_section();
$sections = gp_profile_sections();
$counts   = gp_profile_counts();
$member   = gp_profile_user();
$title    = $sections[ $section ]['label'];

get_header();
?>
<main id="gp-main" class="gp-profile-page">
	<div class="gp-container">
		<div class="gp-appbar">
			<a class="gp-appbar__btn" href="<?php echo esc_url( $section ? home_url( '/profile/' ) : home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Back', 'gp-house' ); ?>"><?php gp_icon( 'arrow-left', 20 ); ?></a>
			<p class="gp-appbar__title"><?php echo esc_html( $title ); ?></p>
			<button class="gp-appbar__btn" type="button" data-pf-toggle aria-expanded="false" aria-controls="gp-pf-side" aria-label="<?php esc_attr_e( 'Account menu', 'gp-house' ); ?>">
				<span class="gp-appbar__burger" aria-hidden="true"><i></i><i></i><i></i></span>
				<span class="gp-appbar__close" aria-hidden="true"><?php gp_icon( 'close-navy', 20 ); ?></span>
			</button>
		</div>

		<div class="gp-pf" data-pf>
			<aside class="gp-pf__side" id="gp-pf-side">
				<?php gp_component( 'member-card', array( 'member' => $member ) ); ?>
				<nav class="gp-pf__nav" aria-label="<?php esc_attr_e( 'Account', 'gp-house' ); ?>">
					<?php foreach ( $sections as $key => $item ) : ?>
						<a class="gp-pf__link<?php echo $key === $section ? ' is-active' : ''; ?>" href="<?php echo esc_url( $item['url'] ); ?>"<?php echo $key === $section ? ' aria-current="page"' : ''; ?>>
							<?php gp_icon( $item['icon'], 20 ); ?>
							<span><?php echo esc_html( $item['label'] . ( isset( $counts[ $key ] ) ? ' (' . $counts[ $key ] . ')' : '' ) ); ?></span>
						</a>
					<?php endforeach; ?>
				</nav>
				<a class="gp-pf__logout" href="<?php echo esc_url( gp_profile_logout_url() ); ?>"><?php gp_icon( 'logout-red', 20 ); ?><span><?php esc_html_e( 'Log Out', 'gp-house' ); ?></span></a>
			</aside>

			<div class="gp-pf__main gp-pf__main--<?php echo esc_attr( $section ? $section : 'overview' ); ?>">
				<?php get_template_part( 'template-parts/profile/' . ( $section ? $section : 'overview' ) ); ?>
			</div>
		</div>
	</div>
</main>
<?php
get_footer();
