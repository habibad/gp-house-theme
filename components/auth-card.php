<?php
/**
 * Auth card — Sign In / Join / Forgot / Reset / Check-your-email
 * (Figma: auth modals; 366 wide, white, 28px radius).
 *
 * Used twice: as the body of the sign-in modal (all views, one visible at a time) and as the
 * content of the /sign-in/, /join/ … pages (one view).
 *
 * @var array $args {
 *   view: signin|join|forgot|reset|check-email,
 *   hidden?: bool, redirect?: string, notice?: string (verified|password_reset|verify_failed),
 *   key?: string, login?: string (reset), email?: string
 * }
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$a    = wp_parse_args( $args, array( 'view' => 'signin', 'hidden' => false, 'redirect' => '', 'notice' => '', 'key' => '', 'login' => '', 'email' => '' ) );
$view = $a['view'];
$tag  = ! empty( $args['page'] ) ? 'h1' : 'h2';

$notices = array(
	'verified'      => array( 'ok', __( 'Email confirmed — welcome to GP House Club! Sign in to continue.', 'gp-house' ) ),
	'password_reset' => array( 'ok', __( 'Your password was updated. Sign in with your new password.', 'gp-house' ) ),
	'verify_failed' => array( 'error', __( 'That confirmation link is invalid or has expired. Sign in and we will send you a new one.', 'gp-house' ) ),
);

/** One labelled field. */
$field = static function ( $name, $label, $icon, $type = 'text', $extra = array() ) {
	$extra = wp_parse_args( $extra, array( 'autocomplete' => '', 'placeholder' => '', 'toggle' => false, 'required' => true, 'value' => '' ) );
	?>
	<label class="gp-auth__field">
		<span class="gp-auth__label"><?php echo esc_html( $label ); ?></span>
		<span class="gp-auth__input">
			<?php gp_icon( $icon, 20 ); ?>
			<input type="<?php echo esc_attr( $type ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $extra['value'] ); ?>"<?php echo $extra['autocomplete'] ? ' autocomplete="' . esc_attr( $extra['autocomplete'] ) . '"' : ''; ?><?php echo $extra['placeholder'] ? ' placeholder="' . esc_attr( $extra['placeholder'] ) . '"' : ''; ?><?php echo $extra['required'] ? ' required' : ''; ?>>
			<?php if ( $extra['toggle'] ) : ?>
				<button class="gp-auth__eye" type="button" data-toggle-password aria-label="<?php esc_attr_e( 'Show password', 'gp-house' ); ?>" aria-pressed="false">
					<img class="gp-icon gp-auth__eye-off" src="<?php echo esc_url( gp_img( 'icons/eye-off-grey.svg' ) ); ?>" width="20" height="20" alt="">
					<img class="gp-icon gp-auth__eye-on" src="<?php echo esc_url( gp_img( 'icons/eye-grey.svg' ) ); ?>" width="20" height="20" alt="">
				</button>
			<?php endif; ?>
		</span>
		<em class="gp-auth__error" aria-live="polite"></em>
	</label>
	<?php
};

$head = static function ( $title, $sub ) use ( $tag ) {
	?>
	<span class="gp-auth__logo"><img src="<?php echo esc_url( gp_img( 'logo-mark.svg' ) ); ?>" width="22" height="24" alt=""></span>
	<<?php echo esc_attr( $tag ); ?> class="gp-auth__title"><?php echo esc_html( $title ); ?></<?php echo esc_attr( $tag ); ?>>
	<p class="gp-auth__sub"><?php echo esc_html( $sub ); ?></p>
	<?php
};
?>
<section class="gp-auth gp-auth--<?php echo esc_attr( $view ); ?>" data-auth-card="<?php echo esc_attr( $view ); ?>"<?php echo $a['hidden'] ? ' hidden' : ''; ?>>
	<?php if ( 'signin' === $view ) : ?>
		<?php $head( __( 'Welcome To GP House', 'gp-house' ), __( 'Access Reservations, Points, And Stays', 'gp-house' ) ); ?>
		<form class="gp-auth__form" data-auth-form="login" novalidate>
			<?php if ( $a['notice'] && isset( $notices[ $a['notice'] ] ) ) : ?>
				<p class="gp-auth__alert is-<?php echo esc_attr( $notices[ $a['notice'] ][0] ); ?>" role="status"><?php echo esc_html( $notices[ $a['notice'] ][1] ); ?></p>
			<?php endif; ?>
			<p class="gp-auth__alert is-error" role="alert" data-auth-alert hidden></p>
			<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $a['redirect'] ); ?>">
			<?php $field( 'email', __( 'Email Address', 'gp-house' ), 'mail-grey', 'email', array( 'autocomplete' => 'email' ) ); ?>
			<?php $field( 'password', __( 'Password', 'gp-house' ), 'lock-grey', 'password', array( 'autocomplete' => 'current-password', 'toggle' => true ) ); ?>
			<a class="gp-auth__forgot" href="<?php echo esc_url( gp_auth_url( 'forgot' ) ); ?>" data-auth-view="forgot"><?php esc_html_e( 'Forgot Password?', 'gp-house' ); ?></a>
			<button class="gp-btn gp-btn--primary gp-auth__submit" type="submit"><?php esc_html_e( 'Sign In To Member Portal', 'gp-house' ); ?></button>
			<button class="gp-auth__resend" type="button" data-auth-resend hidden><?php esc_html_e( 'Resend confirmation email', 'gp-house' ); ?></button>
		</form>
		<p class="gp-auth__switch"><?php esc_html_e( 'Not Yet A Member?', 'gp-house' ); ?> <a href="<?php echo esc_url( gp_auth_url( 'join' ) ); ?>" data-auth-view="join"><?php esc_html_e( 'Join GP House Club', 'gp-house' ); ?></a></p>

	<?php elseif ( 'join' === $view ) : ?>
		<?php $head( __( 'Join GP House Club', 'gp-house' ), __( 'Earn 10 Points Per $1, Room Upgrades & Rate', 'gp-house' ) ); ?>
		<form class="gp-auth__form" data-auth-form="register" novalidate>
			<p class="gp-auth__alert is-error" role="alert" data-auth-alert hidden></p>
			<p class="gp-auth__trap" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></p>
			<?php $field( 'name', __( 'Full Name', 'gp-house' ), 'user-grey', 'text', array( 'autocomplete' => 'name' ) ); ?>
			<?php $field( 'email', __( 'Email Address', 'gp-house' ), 'mail-grey', 'email', array( 'autocomplete' => 'email' ) ); ?>
			<?php $field( 'phone', __( 'Mobile Phone', 'gp-house' ), 'phone-grey', 'tel', array( 'autocomplete' => 'tel', 'required' => false ) ); ?>
			<?php $field( 'password', __( 'Password', 'gp-house' ), 'lock-grey', 'password', array( 'autocomplete' => 'new-password', 'toggle' => true ) ); ?>
			<button class="gp-btn gp-btn--primary gp-auth__submit" type="submit"><?php esc_html_e( 'Complete Registration', 'gp-house' ); ?></button>
		</form>
		<p class="gp-auth__switch"><?php esc_html_e( 'Already Have An Account?', 'gp-house' ); ?> <a href="<?php echo esc_url( gp_auth_url( 'signin' ) ); ?>" data-auth-view="signin"><?php esc_html_e( 'Sign In', 'gp-house' ); ?></a></p>

	<?php elseif ( 'forgot' === $view ) : ?>
		<?php $head( __( 'Reset Your Password', 'gp-house' ), __( 'Enter Your Email For Recovery Instructions', 'gp-house' ) ); ?>
		<form class="gp-auth__form" data-auth-form="forgot" novalidate>
			<p class="gp-auth__alert is-error" role="alert" data-auth-alert hidden></p>
			<?php $field( 'email', __( 'Email Address', 'gp-house' ), 'mail-grey', 'email', array( 'autocomplete' => 'email' ) ); ?>
			<button class="gp-btn gp-btn--primary gp-auth__submit" type="submit"><?php esc_html_e( 'Send Reset Link', 'gp-house' ); ?></button>
		</form>
		<p class="gp-auth__switch"><?php esc_html_e( 'Already Have An Account?', 'gp-house' ); ?> <a href="<?php echo esc_url( gp_auth_url( 'signin' ) ); ?>" data-auth-view="signin"><?php esc_html_e( 'Sign In', 'gp-house' ); ?></a></p>

	<?php elseif ( 'reset' === $view ) : ?>
		<?php $head( __( 'Set A New Password', 'gp-house' ), __( 'Choose A Strong Password For Your Account', 'gp-house' ) ); ?>
		<form class="gp-auth__form" data-auth-form="reset" novalidate>
			<p class="gp-auth__alert is-error" role="alert" data-auth-alert hidden></p>
			<input type="hidden" name="key" value="<?php echo esc_attr( $a['key'] ); ?>">
			<input type="hidden" name="login" value="<?php echo esc_attr( $a['login'] ); ?>">
			<?php $field( 'password', __( 'New Password', 'gp-house' ), 'lock-grey', 'password', array( 'autocomplete' => 'new-password', 'toggle' => true ) ); ?>
			<?php $field( 'password_confirm', __( 'Confirm Password', 'gp-house' ), 'lock-grey', 'password', array( 'autocomplete' => 'new-password', 'toggle' => true ) ); ?>
			<button class="gp-btn gp-btn--primary gp-auth__submit" type="submit"><?php esc_html_e( 'Update Password', 'gp-house' ); ?></button>
		</form>
		<p class="gp-auth__switch"><a href="<?php echo esc_url( gp_auth_url( 'forgot' ) ); ?>"><?php esc_html_e( 'Request A New Link', 'gp-house' ); ?></a></p>

	<?php else : /* check-email */ ?>
		<span class="gp-auth__logo gp-auth__logo--ok"><?php gp_icon( 'check-circle-navy', 32 ); ?></span>
		<h2 class="gp-auth__title"><?php esc_html_e( 'Check Your Email', 'gp-house' ); ?></h2>
		<p class="gp-auth__sub" data-auth-email-note><?php esc_html_e( 'We Sent You A Link To Confirm Your Email Address', 'gp-house' ); ?></p>
		<div class="gp-auth__form gp-auth__form--done">
			<p class="gp-auth__alert is-ok" role="status" data-auth-done hidden></p>
			<button class="gp-btn gp-btn--primary gp-auth__submit" type="button" data-auth-view="signin"><?php esc_html_e( 'Back To Sign In', 'gp-house' ); ?></button>
			<button class="gp-auth__resend is-visible" type="button" data-auth-resend><?php esc_html_e( 'Resend confirmation email', 'gp-house' ); ?></button>
		</div>
	<?php endif; ?>
</section>
