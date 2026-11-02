<?php
/**
 * Guest stories (Figma: 1148:16419).
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$testimonials = gp_get_testimonials( 9 );
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
					<?php gp_button( array( 'label' => __( 'Write a Review', 'gp-house' ), 'url' => home_url( '/write-a-review/' ) ) ); ?>
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

			<div class="gp-photo-stack" aria-hidden="true">
				<img class="gp-photo-stack__3" src="<?php echo esc_url( gp_img( 'photos/review-stack-3.jpg' ) ); ?>" alt="" loading="lazy">
				<img class="gp-photo-stack__2" src="<?php echo esc_url( gp_img( 'photos/review-stack-2.jpg' ) ); ?>" alt="" loading="lazy">
				<img class="gp-photo-stack__1" src="<?php echo esc_url( gp_img( 'photos/review-stack-1.jpg' ) ); ?>" alt="" loading="lazy">
			</div>
		</div>

		<?php if ( $testimonials ) : ?>
			<div class="gp-testimonials">
				<?php foreach ( $columns as $column ) : ?>
					<div class="gp-testimonials__col">
						<?php
						foreach ( $column as $t ) {
							gp_component( 'testimonial-card', array( 'testimonial' => $t ) );
						}
						?>
					</div>
				<?php endforeach; ?>
				<div class="gp-testimonials__fade gp-testimonials__fade--top" aria-hidden="true"></div>
				<div class="gp-testimonials__fade gp-testimonials__fade--bottom" aria-hidden="true"></div>
			</div>
		<?php endif; ?>
	</div>
</section>
