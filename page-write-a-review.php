<?php
/**
 * Write a review (/write-a-review/) — signed-in members only. The review is saved as a pending
 * Guest Story and shown on the home page after an administrator approves it.
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$user   = wp_get_current_user();
$status = isset( $_GET['review'] ) ? sanitize_key( wp_unslash( $_GET['review'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
$avatar = function_exists( 'gp_core_user_avatar_id' ) ? gp_core_user_avatar_id( $user->ID ) : 0;
$avatar_url = $avatar ? wp_get_attachment_image_url( $avatar, 'thumbnail' ) : '';
$location   = (string) get_user_meta( $user->ID, 'gp_location', true );
$stays      = array();
foreach ( gp_get_stays() as $s ) {
	$stays[ $s['title'] ] = $s['title'];
}
$errors = array(
	'location' => __( 'Please tell us where you are from (city and country).', 'gp-house' ),
	'text'     => sprintf( /* translators: 1: min chars, 2: max chars */ __( 'Your review needs %1$d to %2$d characters.', 'gp-house' ), 20, 700 ),
	'consent'  => __( 'Please tick the box so we may show your review.', 'gp-house' ),
	'limit'    => __( 'You already have reviews waiting for approval — please try again once they are published.', 'gp-house' ),
	'expired'  => __( 'Your session expired. Please try again.', 'gp-house' ),
	'name'     => __( 'Please add your name to your account first.', 'gp-house' ),
	'error'    => __( 'We could not save your review. Please try again.', 'gp-house' ),
);

get_header();
?>
<main id="gp-main" class="gp-doc gp-review">
	<div class="gp-container gp-doc__inner">
		<p class="gp-kicker"><?php esc_html_e( 'Guest Stories', 'gp-house' ); ?></p>
		<h1 class="gp-doc__title"><?php esc_html_e( 'Share Your Stay With Us', 'gp-house' ); ?></h1>
		<p class="gp-doc__lead"><?php esc_html_e( 'Tell future guests what made your stay special. Your review is checked by our team and appears on the home page once approved.', 'gp-house' ); ?></p>

		<?php if ( 'sent' === $status ) : ?>
			<div class="gp-review__done" role="status">
				<span class="gp-review__done-icon"><?php gp_icon( 'check-circle-navy', 32 ); ?></span>
				<h2><?php esc_html_e( 'Thank You — Review Received', 'gp-house' ); ?></h2>
				<p><?php esc_html_e( 'Our team will read it shortly. We will email you as soon as it is live on the website.', 'gp-house' ); ?></p>
				<?php gp_button( array( 'label' => __( 'Back To Home', 'gp-house' ), 'url' => home_url( '/' ), 'size' => 'tab' ) ); ?>
			</div>
		<?php else : ?>
			<form class="gp-inquiry gp-review__form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" enctype="multipart/form-data" data-review-form>
				<input type="hidden" name="action" value="gp_review_submit">
				<?php wp_nonce_field( 'gp_review', 'gp_review_nonce' ); ?>
				<p class="gp-inquiry__trap" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></p>
				<?php if ( $status && isset( $errors[ $status ] ) ) : ?>
					<p class="gp-inquiry__notice is-error" role="alert"><?php echo esc_html( $errors[ $status ] ); ?></p>
				<?php endif; ?>

				<div class="gp-review__who">
					<span class="gp-review__avatar" data-review-avatar>
						<?php if ( $avatar_url ) : ?>
							<img src="<?php echo esc_url( $avatar_url ); ?>" width="56" height="56" alt="">
						<?php else : ?>
							<span><?php echo esc_html( mb_strtoupper( mb_substr( $user->display_name, 0, 1 ) ) ); ?></span>
						<?php endif; ?>
					</span>
					<div>
						<p class="gp-review__name"><?php echo esc_html( $user->display_name ); ?></p>
						<p class="gp-review__mail"><?php echo esc_html( $user->user_email ); ?></p>
					</div>
					<p class="gp-review__hint"><?php esc_html_e( 'Taken from your account', 'gp-house' ); ?></p>
				</div>

				<div class="gp-inquiry__grid">
					<label><span><?php esc_html_e( 'Where Are You From?', 'gp-house' ); ?></span><input type="text" name="location" value="<?php echo esc_attr( $location ); ?>" placeholder="<?php esc_attr_e( 'e.g. London, United Kingdom', 'gp-house' ); ?>" maxlength="80" required></label>
					<label><span><?php esc_html_e( 'Which Stay? (Optional)', 'gp-house' ); ?></span>
						<select name="stay">
							<option value=""><?php esc_html_e( 'Select a sanctuary', 'gp-house' ); ?></option>
							<?php foreach ( $stays as $title ) : ?>
								<option value="<?php echo esc_attr( $title ); ?>"><?php echo esc_html( $title ); ?></option>
							<?php endforeach; ?>
						</select>
					</label>
					<?php if ( ! $avatar_url ) : ?>
						<label class="gp-inquiry__wide"><span><?php esc_html_e( 'Your Photo (Optional)', 'gp-house' ); ?></span>
							<input type="file" name="photo" accept="image/jpeg,image/png,image/webp" data-review-photo>
							<small><?php esc_html_e( 'JPG, PNG or WebP, up to 2 MB. Without a photo we show your initial.', 'gp-house' ); ?></small>
						</label>
					<?php endif; ?>
					<label class="gp-inquiry__wide"><span><?php esc_html_e( 'Your Review', 'gp-house' ); ?></span>
						<textarea name="review" rows="6" minlength="20" maxlength="700" required placeholder="<?php esc_attr_e( 'What did you love about your stay?', 'gp-house' ); ?>" data-review-text></textarea>
						<small class="gp-review__count"><span data-review-count>0</span> / 700</small>
					</label>
					<label class="gp-review__consent gp-inquiry__wide">
						<input type="checkbox" name="consent" value="1" required>
						<span><?php esc_html_e( 'I agree that my name, location, photo and review may be shown publicly on the GP House website.', 'gp-house' ); ?></span>
					</label>
				</div>
				<button class="gp-btn gp-btn--primary gp-btn--tab gp-inquiry__submit" type="submit"><?php esc_html_e( 'Submit Review', 'gp-house' ); ?></button>
			</form>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
