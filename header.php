<?php
/**
 * Site header — floating white navigation bar (Figma: "Navigation").
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$others        = gp_menu_links( 'others' );
$others_active = (bool) wp_list_filter( $others, array( 'current' => true ) );
$signed_in     = gp_profile_signed_in();
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
			<div class="gp-popover gp-locale-wrap" data-popover>
				<button class="gp-locale" type="button" aria-haspopup="true" aria-expanded="false" aria-label="<?php esc_attr_e( 'Language and currency', 'gp-house' ); ?>">
					<span class="gp-locale__lang">En</span>
					<span class="gp-locale__currency">USD</span>
				</button>
				<div class="gp-popover__panel gp-locale-menu" hidden>
					<p class="gp-locale-menu__label"><?php esc_html_e( 'Language', 'gp-house' ); ?></p>
					<div class="gp-locale-menu__row gp-locale-menu__row--lang">
						<button type="button" data-lang="th">TH (ภาษาไทย)</button>
						<button type="button" data-lang="en" class="is-active">EN (English)</button>
					</div>
					<p class="gp-locale-menu__label gp-locale-menu__label--cur"><?php esc_html_e( 'Currency', 'gp-house' ); ?></p>
					<div class="gp-locale-menu__row">
						<?php foreach ( array( 'USD', 'THB', 'EUR', 'GBP' ) as $code ) : ?>
							<button type="button" data-currency="<?php echo esc_attr( $code ); ?>"><?php echo esc_html( $code ); ?></button>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<a class="gp-points" href="<?php echo esc_url( home_url( '/club/' ) ); ?>">
				<span class="gp-points__icon"><?php gp_icon( 'points', 16 ); ?></span>
				<?php echo esc_html( number_format_i18n( $points ) ); ?> P
			</a>
			<?php if ( $signed_in ) : ?>
				<div class="gp-popover gp-account" data-popover data-hover>
					<button class="gp-account-btn" type="button" aria-haspopup="true" aria-expanded="false" aria-label="<?php esc_attr_e( 'My account', 'gp-house' ); ?>">
						<?php gp_icon( 'menu', 24 ); ?>
						<?php gp_icon( 'user-circle', 24 ); ?>
					</button>
					<div class="gp-popover__panel gp-account__menu" hidden>
						<?php gp_component( 'member-card', array( 'member' => gp_profile_user(), 'class' => 'gp-member--menu' ) ); ?>
						<?php $gp_counts = gp_profile_counts(); ?>
						<nav class="gp-account__nav" aria-label="<?php esc_attr_e( 'Account', 'gp-house' ); ?>">
							<?php foreach ( gp_profile_sections() as $gp_key => $gp_item ) : ?>
								<a href="<?php echo esc_url( $gp_item['url'] ); ?>"><?php gp_icon( $gp_item['icon'], 16 ); ?><?php echo esc_html( $gp_item['label'] . ( isset( $gp_counts[ $gp_key ] ) ? ' (' . $gp_counts[ $gp_key ] . ')' : '' ) ); ?></a>
							<?php endforeach; ?>
						</nav>
						<a class="gp-account__logout" href="<?php echo esc_url( gp_profile_logout_url() ); ?>"><?php gp_icon( 'user-navy', 16 ); ?><?php esc_html_e( 'Log Out', 'gp-house' ); ?></a>
					</div>
				</div>
			<?php else : ?>
				<a class="gp-btn gp-btn--outline gp-btn--sm" href="<?php echo esc_url( wp_login_url( home_url( '/profile/' ) ) ); ?>" data-auth="signin"><?php esc_html_e( 'Sign In', 'gp-house' ); ?></a>
			<?php endif; ?>
			<a class="gp-btn gp-btn--primary gp-btn--sm" href="<?php echo esc_url( home_url( '/stays/' ) ); ?>"><?php esc_html_e( 'Book', 'gp-house' ); ?></a>
		</div>

		<button class="gp-header__toggle" type="button" aria-expanded="false" aria-controls="gp-mobile-menu" aria-label="<?php esc_attr_e( 'Open menu', 'gp-house' ); ?>">
			<span></span><span></span><span></span>
		</button>
	</div>

	<div class="gp-mobile-menu" id="gp-mobile-menu" role="dialog" aria-label="<?php esc_attr_e( 'Menu', 'gp-house' ); ?>" hidden>
		<div class="gp-mobile-menu__top">
			<a class="gp-mobile-menu__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"><img src="<?php echo esc_url( gp_img( 'logo.svg' ) ); ?>" width="145" height="33" alt=""></a>
			<button class="gp-mobile-menu__close" type="button" data-menu-close aria-label="<?php esc_attr_e( 'Close menu', 'gp-house' ); ?>"><?php gp_icon( 'close-navy', 20 ); ?></button>
		</div>
		<?php if ( ! $signed_in ) : ?>
			<a class="gp-btn gp-btn--outline gp-mobile-menu__signin" href="<?php echo esc_url( wp_login_url( home_url( '/profile/' ) ) ); ?>" data-auth="signin"><?php esc_html_e( 'Sign In', 'gp-house' ); ?></a>
		<?php endif; ?>
		<nav class="gp-mobile-menu__nav" aria-label="<?php esc_attr_e( 'Main', 'gp-house' ); ?>">
			<?php foreach ( gp_menu_links( 'primary' ) as $link ) : ?>
				<a class="gp-mobile-menu__link" href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['title'] ); ?></a>
			<?php endforeach; ?>
		</nav>
		<?php if ( $others ) : ?>
			<div class="gp-mobile-menu__group">
				<p class="gp-mobile-menu__label"><?php esc_html_e( 'Others', 'gp-house' ); ?></p>
				<?php foreach ( $others as $link ) : ?>
					<a class="gp-mobile-menu__link gp-mobile-menu__link--sub" href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['title'] ); ?></a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<div class="gp-mobile-menu__meta">
			<span class="gp-mobile-menu__locale"><strong>En</strong><span class="gp-locale__currency">USD</span></span>
			<a class="gp-points gp-points--menu" href="<?php echo esc_url( $signed_in ? home_url( '/profile/points/' ) : home_url( '/club/' ) ); ?>">
				<?php gp_icon( 'sparkle-navy', 24 ); ?>
				<?php echo esc_html( number_format_i18n( $points ) ); ?> P
			</a>
		</div>
		<a class="gp-btn gp-btn--primary gp-mobile-menu__book" href="<?php echo esc_url( home_url( '/stays/' ) ); ?>"><?php esc_html_e( 'Book', 'gp-house' ); ?></a>
	</div>
</header>
