<?php
/**
 * Sign-in / join / forgot / reset screens (Figma auth modals). The account logic lives in the
 * GP House Core plugin (includes/auth.php); this file wires the screens, assets and the modal.
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

/** URL of an auth screen (plugin helper with a fallback when the plugin is off). */
function gp_auth_url( $view = 'signin', $args = array() ) {
	if ( function_exists( 'gp_core_auth_url' ) ) {
		return gp_core_auth_url( $view, $args );
	}
	return home_url( '/' );
}

/** Auth pages (slug → view). */
function gp_auth_pages() {
	return array(
		'sign-in'         => 'signin',
		'join'            => 'join',
		'forgot-password' => 'forgot',
		'reset-password'  => 'reset',
		'verify-email'    => 'signin',
	);
}

/** Whether the current request is one of the auth pages. */
function gp_is_auth_page() {
	return is_page() && isset( gp_auth_pages()[ get_post_field( 'post_name', get_queried_object_id() ) ] );
}

/** Auth pages are for guests: members go straight to their area. */
add_action( 'template_redirect', function () {
	if ( gp_is_auth_page() && is_user_logged_in() && ! isset( $_GET['key'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		wp_safe_redirect( home_url( '/profile/' ) );
		exit;
	}
	if ( gp_is_auth_page() ) {
		nocache_headers();
	}
} );
add_filter( 'wp_robots', function ( $robots ) {
	if ( gp_is_auth_page() ) {
		$robots['noindex'] = true;
	}
	return $robots;
} );

/** Styles + script data: on the auth pages, and for guests everywhere (the sign-in modal). */
add_action( 'wp_enqueue_scripts', function () {
	if ( is_user_logged_in() && ! gp_is_auth_page() ) {
		return;
	}
	wp_enqueue_style( 'gp-house-auth', GP_HOUSE_URI . '/assets/css/auth.css', array( 'gp-house-layout' ), gp_asset_version( 'assets/css/auth.css' ) );
	wp_localize_script( 'gp-house', 'gpAuth', array(
		'rest'  => esc_url_raw( rest_url( 'gp/v1/auth/' ) ),
		'nonce' => wp_create_nonce( 'wp_rest' ),
		'urls'  => array(
			'signin' => gp_auth_url( 'signin' ),
			'join'   => gp_auth_url( 'join' ),
			'forgot' => gp_auth_url( 'forgot' ),
		),
		'i18n'  => array(
			'network' => __( 'We could not reach the server. Check your connection and try again.', 'gp-house' ),
			'sending' => __( 'Please wait…', 'gp-house' ),
			'resend'  => __( 'Resend confirmation email', 'gp-house' ),
		),
	) );
}, 20 );

/** Body class so the CSS can tune the page. */
add_filter( 'body_class', function ( $classes ) {
	if ( gp_is_auth_page() ) {
		$classes[] = 'gp-auth-screen';
	}
	return $classes;
} );

/** The modal (all views, one visible at a time) — printed once for guests. */
add_action( 'wp_footer', function () {
	if ( is_user_logged_in() || gp_is_auth_page() ) {
		return;
	}
	?>
	<dialog class="gp-auth-modal" id="gp-auth-modal" aria-label="<?php esc_attr_e( 'Member sign in', 'gp-house' ); ?>">
		<button class="gp-auth-modal__close" type="button" data-auth-close aria-label="<?php esc_attr_e( 'Close', 'gp-house' ); ?>"><?php gp_icon( 'close-navy', 18 ); ?></button>
		<?php
		foreach ( array( 'signin', 'join', 'forgot' ) as $view ) {
			gp_component( 'auth-card', array( 'view' => $view, 'hidden' => true ) );
		}
		?>
	</dialog>
	<?php
} );

/** Render an auth page body (called by the page-*.php templates). */
function gp_render_auth_page( $view ) {
	// phpcs:disable WordPress.Security.NonceVerification
	$args = array(
		'view'     => $view,
		'redirect' => isset( $_GET['redirect_to'] ) ? esc_url_raw( rawurldecode( wp_unslash( $_GET['redirect_to'] ) ) ) : '',
		'notice'   => isset( $_GET['notice'] ) ? sanitize_key( wp_unslash( $_GET['notice'] ) ) : '',
		'key'      => isset( $_GET['key'] ) ? sanitize_text_field( wp_unslash( $_GET['key'] ) ) : '',
		'login'    => isset( $_GET['login'] ) ? sanitize_text_field( rawurldecode( wp_unslash( $_GET['login'] ) ) ) : '',
	);
	// phpcs:enable
	get_header();
	?>
	<main id="gp-main" class="gp-auth-page">
		<img class="gp-auth-page__bg" src="<?php echo esc_url( gp_img( 'photos/hero-room.jpg' ) ); ?>" alt="" aria-hidden="true">
		<div class="gp-auth-page__veil" aria-hidden="true"></div>
		<?php gp_component( 'auth-card', $args ); ?>
	</main>
	<?php
	get_footer();
}
