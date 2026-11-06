<?php
/**
 * Profile → Profile & Preferences (Figma 1148:8798 + dropdown states, mobile 1454:17916).
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$m      = gp_profile_user();
$prefs  = $m['prefs'];
$opts   = gp_profile_pref_options();
$status = isset( $_GET['saved'] ) ? sanitize_key( wp_unslash( $_GET['saved'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
?>
<form class="gp-prefs" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
	<input type="hidden" name="action" value="gp_profile_save">
	<?php wp_nonce_field( 'gp_profile_save', 'gp_profile_nonce' ); ?>

	<?php if ( '1' === $status ) : ?>
		<p class="gp-prefs__notice is-ok" role="status"><?php esc_html_e( 'Your preferences have been saved.', 'gp-house' ); ?></p>
	<?php elseif ( 'login' === $status ) : ?>
		<p class="gp-prefs__notice is-error" role="alert"><?php esc_html_e( 'Please sign in to save your preferences.', 'gp-house' ); ?></p>
	<?php endif; ?>

	<h2 class="gp-prefs__title"><?php esc_html_e( 'Personal Contact Information', 'gp-house' ); ?></h2>
	<div class="gp-prefs__box gp-prefs__contact">
		<label class="gp-prefs__field">
			<span><?php esc_html_e( 'Full Legal Name', 'gp-house' ); ?></span>
			<span class="gp-prefs__input"><?php gp_icon( 'user-navy', 20 ); ?><input type="text" name="full_name" value="<?php echo esc_attr( $m['name'] ); ?>" autocomplete="name"></span>
		</label>
		<label class="gp-prefs__field">
			<span><?php esc_html_e( 'Email Address', 'gp-house' ); ?></span>
			<span class="gp-prefs__input"><?php gp_icon( 'user-navy', 20 ); ?><input type="email" value="<?php echo esc_attr( $m['login_mail'] ); ?>" readonly autocomplete="email"></span>
		</label>
		<label class="gp-prefs__field gp-prefs__field--wide">
			<span><?php esc_html_e( 'Mobile Phone (For Arrival Concierge)', 'gp-house' ); ?></span>
			<span class="gp-prefs__input gp-prefs__input--phone">
				<span class="gp-prefs__dial"><img src="<?php echo esc_url( gp_img( 'profile/flag-us.png' ) ); ?>" width="24" height="16" alt=""><span>+1</span></span>
				<input type="tel" name="phone" value="<?php echo esc_attr( $m['phone'] ); ?>" placeholder="<?php esc_attr_e( 'Phone number', 'gp-house' ); ?>" autocomplete="tel">
			</span>
		</label>
	</div>

	<h2 class="gp-prefs__title gp-prefs__title--room"><?php esc_html_e( 'Curated Room Preferences', 'gp-house' ); ?></h2>
	<p class="gp-prefs__lead"><?php esc_html_e( 'Our Housekeepers And Villa Managers Tailor Each Arrival Based On Your Saved Specifications.', 'gp-house' ); ?></p>
	<div class="gp-prefs__box gp-prefs__room">
		<div class="gp-prefs__selects">
			<?php foreach ( $opts as $key => $def ) : ?>
				<div class="gp-prefs__field">
					<span><?php echo esc_html( $def['label'] ); ?></span>
					<?php gp_dropdown( array( 'name' => 'pref_' . $key, 'label' => $def['label'], 'options' => $def['options'], 'value' => $prefs[ $key ] ?? '', 'show_value' => true, 'class' => 'gp-select--field gp-select--icon', 'icon' => $def['icon'] ) ); ?>
				</div>
			<?php endforeach; ?>
		</div>
		<label class="gp-prefs__field">
			<span><?php esc_html_e( 'Dietary & Allergy Specifications', 'gp-house' ); ?></span>
			<span class="gp-prefs__input"><input type="text" name="pref_dietary" value="<?php echo esc_attr( $prefs['dietary'] ); ?>"></span>
		</label>
		<label class="gp-prefs__field">
			<span><?php esc_html_e( 'Special Hospitality Requests', 'gp-house' ); ?></span>
			<span class="gp-prefs__input gp-prefs__input--area"><textarea name="pref_requests" rows="3"><?php echo esc_textarea( $prefs['requests'] ); ?></textarea></span>
		</label>
	</div>

	<div class="gp-prefs__actions">
		<button class="gp-btn gp-btn--primary gp-btn--tab" type="submit"><?php esc_html_e( 'Save Preferences', 'gp-house' ); ?></button>
	</div>
</form>
