<?php
/**
 * Site header — floating white navigation bar (Figma: "Navigation").
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$others        = gp_menu_links( 'others' );
$others_active = (bool) wp_list_filter( $others, array( 'current' => true ) );
$points        = apply_filters( 'gp_house_header_points', is_user_logged_in() ? (int) get_user_meta( get_current_user_id(), 'gp_points', true ) : 450 );
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="gp-skip-link" href="#gp-main"><?php esc_html_e( 'Skip to content', 'gp-house' ); ?></a>

<header class="gp-header">
	<div class="gp-header__bar">
		<div class="gp-header__left">
			<a class="gp-header__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				<img src="<?php echo esc_url( gp_img( 'logo.svg' ) ); ?>" width="122" height="28" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
			</a>
			<nav class="gp-nav" aria-label="<?php esc_attr_e( 'Main', 'gp-house' ); ?>">
				<?php foreach ( gp_menu_links( 'primary' ) as $link ) : ?>
					<a class="gp-nav__link<?php echo $link['current'] ? ' is-current' : ''; ?>" href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['title'] ); ?></a>
				<?php endforeach; ?>
				<?php if ( $others ) : ?>
					<div class="gp-nav__more">
						<button class="gp-nav__link gp-nav__more-toggle<?php echo $others_active ? ' is-current' : ''; ?>" type="button" aria-expanded="false" aria-controls="gp-nav-more">
							<?php esc_html_e( 'Others', 'gp-house' ); ?>
							<?php gp_icon( 'arrow-down-nav', 16 ); ?>
						</button>
						<div class="gp-dropdown" id="gp-nav-more">
							<?php foreach ( $others as $link ) : ?>
								<a class="gp-dropdown__link<?php echo $link['current'] ? ' is-current' : ''; ?>" href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['title'] ); ?></a>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
			</nav>
		</div>

		<div class="gp-header__right">
			<div class="gp-locale" role="group" aria-label="<?php esc_attr_e( 'Language and currency', 'gp-house' ); ?>">
				<span class="gp-locale__lang">En</span>
				<span class="gp-locale__currency">USD</span>
			</div>
			<a class="gp-points" href="<?php echo esc_url( home_url( '/club/' ) ); ?>">
				<span class="gp-points__icon"><?php gp_icon( 'points', 16 ); ?></span>
				<?php echo esc_html( number_format_i18n( $points ) ); ?> P
			</a>
			<?php if ( is_user_logged_in() ) : ?>
				<a class="gp-account-btn" href="<?php echo esc_url( home_url( '/profile/' ) ); ?>" aria-label="<?php esc_attr_e( 'My account', 'gp-house' ); ?>">
					<?php gp_icon( 'menu', 24 ); ?>
					<?php gp_icon( 'user-circle', 24 ); ?>
				</a>
			<?php else : ?>
				<a class="gp-btn gp-btn--outline gp-btn--sm" href="<?php echo esc_url( wp_login_url( home_url( '/profile/' ) ) ); ?>"><?php esc_html_e( 'Sign In', 'gp-house' ); ?></a>
			<?php endif; ?>
			<a class="gp-btn gp-btn--primary gp-btn--sm" href="<?php echo esc_url( home_url( '/stays/' ) ); ?>"><?php esc_html_e( 'Book', 'gp-house' ); ?></a>
		</div>

		<button class="gp-header__toggle" type="button" aria-expanded="false" aria-controls="gp-mobile-menu" aria-label="<?php esc_attr_e( 'Open menu', 'gp-house' ); ?>">
			<span></span><span></span><span></span>
		</button>
	</div>

	<div class="gp-mobile-menu" id="gp-mobile-menu" hidden>
		<?php foreach ( array_merge( gp_menu_links( 'primary' ), $others ) as $link ) : ?>
			<a class="gp-mobile-menu__link" href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['title'] ); ?></a>
		<?php endforeach; ?>
		<div class="gp-mobile-menu__actions">
			<a class="gp-btn gp-btn--outline" href="<?php echo esc_url( wp_login_url( home_url( '/profile/' ) ) ); ?>"><?php esc_html_e( 'Sign In', 'gp-house' ); ?></a>
			<a class="gp-btn gp-btn--primary" href="<?php echo esc_url( home_url( '/stays/' ) ); ?>"><?php esc_html_e( 'Book', 'gp-house' ); ?></a>
		</div>
	</div>
</header>
