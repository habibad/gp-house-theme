<?php
/**
 * Guest stories (Figma: 1148:16419).
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$testimonials = gp_get_testimonials( 12 );
$columns      = array( array(), array(), array() );
foreach ( $testimonials as $i => $t ) {
	$columns[ $i % 3 ][] = $t;
}
$avatars = array( 'photos/review-avatar-1.png', 'photos/review-avatar-2.png', 'photos/review-avatar-3.png', 'photos/review-avatar-4.png' );
?>
<section class="gp-reviews">
	<div class="gp-container">
		<div class="gp-reviews__intro">
			<div class="gp-reviews__copy">
				<div class="gp-reviews__lead">
					<?php gp_component( 'section-head', array( 'kicker' => gp_opt( 'reviews_kicker' ), 'title' => gp_opt( 'reviews_title' ), 'kicker_class' => 'gp-kicker--lhn', 'title_class' => 'gp-reviews__title' ) ); ?>
					<p class="gp-text"><?php echo esc_html( gp_opt( 'reviews_text' ) ); ?></p>
					<?php gp_button( array(
						'label' => __( 'Write a Review', 'gp-house' ),
						'url'   => home_url( '/write-a-review/' ),
						// Guests get the sign-in modal first and land on the form afterwards.
						'attrs' => is_user_logged_in() ? array() : array( 'data-auth' => 'signin', 'data-redirect' => home_url( '/write-a-review/' ) ),
					) ); ?>
				</div>
				<div class="gp-reviews__stats">
					<div class="gp-avatars">
						<?php foreach ( $avatars as $avatar ) : ?>
							<img src="<?php echo esc_url( gp_img( $avatar ) ); ?>" width="40" height="40" alt="">
						<?php endforeach; ?>
					</div>
					<div class="gp-reviews__numbers">
						<p class="gp-reviews__count"><?php echo esc_html( gp_opt( 'reviews_count' ) ); ?></p>
						<p class="gp-text"><?php echo esc_html( gp_opt( 'reviews_note' ) ); ?></p>
					</div>
				</div>
			</div>

			<?php // Guest photo stack: click a photo behind to bring it to the front; clicking the front photo shows the next one. ?>
			<div class="gp-photo-stack" role="group" aria-label="<?php esc_attr_e( 'Guest photos', 'gp-house' ); ?>" data-photo-stack>
				<?php foreach ( array( 3, 2, 1 ) as $n ) : ?>
					<button class="gp-photo-stack__item" type="button" data-pos="<?php echo (int) $n; ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %d: photo number */ __( 'Show guest photo %d', 'gp-house' ), 4 - $n ) ); ?>"<?php echo 1 === $n ? ' aria-pressed="true"' : ' aria-pressed="false"'; ?>>
						<img src="<?php echo esc_url( gp_img( 'photos/review-stack-' . $n . '.jpg' ) ); ?>" alt="" loading="lazy">
					</button>
				<?php endforeach; ?>
			</div>
		</div>

		<?php if ( $testimonials ) : ?>
			<?php // Auto-scrolling columns: outer columns move up, the middle one moves down; hovering pauses all (main.js). ?>
			<div class="gp-testimonials" data-marquee>
				<?php foreach ( $columns as $i => $column ) : ?>
					<div class="gp-testimonials__col" data-dir="<?php echo 1 === $i ? 'down' : 'up'; ?>">
						<div class="gp-testimonials__track">
							<?php
							foreach ( $column as $t ) {
								gp_component( 'testimonial-card', array( 'testimonial' => $t ) );
							}
							?>
						</div>
					</div>
				<?php endforeach; ?>
				<div class="gp-testimonials__fade gp-testimonials__fade--top" aria-hidden="true"></div>
				<div class="gp-testimonials__fade gp-testimonials__fade--bottom" aria-hidden="true"></div>
			</div>
		<?php endif; ?>
	</div>
</section>
