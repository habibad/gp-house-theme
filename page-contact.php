<?php
/**
 * Contact & Concierge (/contact/) — message form (stored in Owner Inquiries as type "contact").
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

// phpcs:disable WordPress.Security.NonceVerification
$status  = isset( $_GET['inquiry'] ) ? sanitize_key( wp_unslash( $_GET['inquiry'] ) ) : '';
$topic   = isset( $_GET['topic'] ) ? sanitize_key( wp_unslash( $_GET['topic'] ) ) : '';
// phpcs:enable
$preset  = 'review' === $topic ? __( 'I would like to share a review of my stay: ', 'gp-house' ) : '';
$details = apply_filters( 'gp_house_contact_details', array(
	array( 'icon' => 'mail-navy', 'label' => __( 'Email', 'gp-house' ), 'value' => 'concierge@gphousehotels.com', 'url' => 'mailto:concierge@gphousehotels.com' ),
	array( 'icon' => 'phone-navy', 'label' => __( 'Phone / WhatsApp', 'gp-house' ), 'value' => '+66 76 345 889', 'url' => 'tel:+6676345889' ),
	array( 'icon' => 'clock-navy', 'label' => __( 'Concierge hours', 'gp-house' ), 'value' => __( 'Daily, 24 hours', 'gp-house' ), 'url' => '' ),
) );

get_header();
?>
<main id="gp-main" class="gp-doc gp-contact">
	<div class="gp-container gp-doc__inner">
		<p class="gp-kicker"><?php esc_html_e( 'Contact & Concierge', 'gp-house' ); ?></p>
		<h1 class="gp-doc__title"><?php esc_html_e( 'How May We Help You?', 'gp-house' ); ?></h1>
		<p class="gp-doc__lead"><?php esc_html_e( 'Questions about a stay, a transfer, a celebration or your membership — write to our concierge team and we will reply within one business day.', 'gp-house' ); ?></p>

		<div class="gp-contact__grid">
			<ul class="gp-contact__details">
				<?php foreach ( $details as $d ) : ?>
					<li>
						<span class="gp-contact__icon"><?php gp_icon( $d['icon'], 24 ); ?></span>
						<div>
							<p class="gp-contact__label"><?php echo esc_html( $d['label'] ); ?></p>
							<?php if ( $d['url'] ) : ?>
								<a href="<?php echo esc_url( $d['url'] ); ?>"><?php echo esc_html( $d['value'] ); ?></a>
							<?php else : ?>
								<p><?php echo esc_html( $d['value'] ); ?></p>
							<?php endif; ?>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>

			<form class="gp-inquiry gp-contact__form" id="inquiry" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
				<input type="hidden" name="action" value="gp_inquiry">
				<input type="hidden" name="type" value="contact">
				<?php wp_nonce_field( 'gp_inquiry', 'gp_inquiry_nonce' ); ?>
				<p class="gp-inquiry__trap" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></p>
				<?php if ( 'sent' === $status ) : ?>
					<p class="gp-inquiry__notice is-ok" role="status"><?php esc_html_e( 'Thank you — your message is with our concierge team.', 'gp-house' ); ?></p>
				<?php elseif ( $status ) : ?>
					<p class="gp-inquiry__notice is-error" role="alert"><?php esc_html_e( 'Please enter your name and a valid email, then try again.', 'gp-house' ); ?></p>
				<?php endif; ?>
				<div class="gp-inquiry__grid">
					<label><span><?php esc_html_e( 'Your Full Name', 'gp-house' ); ?></span><input type="text" name="name" autocomplete="name" required></label>
					<label><span><?php esc_html_e( 'Email Address', 'gp-house' ); ?></span><input type="email" name="email" autocomplete="email" required></label>
					<label><span><?php esc_html_e( 'Phone / WhatsApp', 'gp-house' ); ?></span><input type="tel" name="phone" autocomplete="tel"></label>
					<label><span><?php esc_html_e( 'Booking Reference (optional)', 'gp-house' ); ?></span><input type="text" name="property" placeholder="GPH-000000"></label>
					<label class="gp-inquiry__wide"><span><?php esc_html_e( 'How Can We Help?', 'gp-house' ); ?></span><textarea name="goals" rows="5"><?php echo esc_textarea( $preset ); ?></textarea></label>
				</div>
				<button class="gp-btn gp-btn--primary gp-btn--tab gp-inquiry__submit" type="submit"><?php esc_html_e( 'Send Message', 'gp-house' ); ?></button>
			</form>
		</div>
	</div>
</main>
<?php
get_footer();
