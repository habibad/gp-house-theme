<?php
/**
 * Profile → My Booking (Figma 1148:9787 / empty 1148:10146 / itinerary 1148:9944, mobile 1454:12950).
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$bookings = gp_profile_bookings();
$booked   = isset( $_GET['booked'] ) ? sanitize_text_field( wp_unslash( $_GET['booked'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
if ( $booked ) {
	echo '<p class="gp-bookings__notice" role="status">' . esc_html( sprintf( /* translators: %s: booking reference */ __( 'Thank you! Your reservation %s is confirmed. A confirmation email is on its way.', 'gp-house' ), $booked ) ) . '</p>';
}
$filters  = array(
	'all'       => __( 'All', 'gp-house' ),
	'confirmed' => __( 'Confirmed', 'gp-house' ),
	'completed' => __( 'Completed', 'gp-house' ),
	'cancelled' => __( 'Cancelled', 'gp-house' ),
);

if ( ! $bookings ) {
	// The phone layout keeps the filter tabs above the empty state.
	echo '<div class="gp-bookings__filters gp-bookings__filters--empty" aria-hidden="true">';
	foreach ( $filters as $key => $label ) {
		echo '<span class="gp-bookings__filter' . ( 'all' === $key ? ' is-active' : '' ) . '">' . esc_html( $label ) . '</span>';
	}
	echo '</div>';
	get_template_part( 'template-parts/profile/empty', null, array(
		'image' => 'profile/empty-booking.png',
		'title' => __( 'No Bookings Yet', 'gp-house' ),
		'text'  => __( 'Your stays will appear here once you make a reservation.', 'gp-house' ),
	) );
	return;
}
?>
<div class="gp-bookings" data-bookings>
	<div class="gp-bookings__filters" role="tablist" aria-label="<?php esc_attr_e( 'Filter reservations', 'gp-house' ); ?>">
		<?php foreach ( $filters as $key => $label ) : ?>
			<button class="gp-bookings__filter<?php echo 'all' === $key ? ' is-active' : ''; ?>" type="button" role="tab" aria-selected="<?php echo 'all' === $key ? 'true' : 'false'; ?>" data-filter="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></button>
		<?php endforeach; ?>
	</div>

	<div class="gp-bookings__list">
		<?php foreach ( $bookings as $b ) : ?>
			<article class="gp-bk" data-status="<?php echo esc_attr( $b['status'] ); ?>">
				<button class="gp-bk__open" type="button" data-itinerary="gp-it-<?php echo esc_attr( sanitize_key( $b['id'] ) ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: hotel */ __( 'View itinerary for %s', 'gp-house' ), $b['title'] ) ); ?>"></button>
				<img class="gp-bk__image" src="<?php echo esc_url( ! empty( $b['image_url'] ) ? $b['image_url'] : gp_img( $b['image'] ) ); ?>" width="111" height="113" alt="" loading="lazy">
				<div class="gp-bk__main">
					<div class="gp-bk__head">
						<h3><?php echo esc_html( $b['title'] ); ?></h3>
						<span class="gp-bk__status is-<?php echo esc_attr( $b['status'] ); ?>"><?php echo esc_html( gp_profile_status_label( $b['status'] ) ); ?></span>
						<a class="gp-btn gp-btn--primary gp-btn--xs gp-bk__again" href="<?php echo esc_url( $b['url'] ); ?>"><?php esc_html_e( 'Book Again', 'gp-house' ); ?></a>
					</div>
					<dl class="gp-bk__facts">
						<div><dt><?php esc_html_e( 'Dates', 'gp-house' ); ?></dt><dd><?php echo esc_html( $b['dates'] ); ?></dd></div>
						<div><dt><?php esc_html_e( 'Total Paid', 'gp-house' ); ?></dt><dd><?php echo esc_html( $b['total'] ); ?></dd></div>
						<div><dt><?php esc_html_e( 'Guests', 'gp-house' ); ?></dt><dd><?php echo esc_html( $b['guests'] ); ?></dd></div>
						<div><dt><?php echo esc_html( ! empty( $b['code'] ) ? __( 'Code', 'gp-house' ) : __( 'Booking Ref', 'gp-house' ) ); ?></dt><dd><?php echo esc_html( ! empty( $b['code'] ) ? $b['code'] : $b['id'] ); ?></dd></div>
					</dl>
				</div>
			</article>
		<?php endforeach; ?>
		<p class="gp-bookings__none" hidden><?php esc_html_e( 'No reservations match this filter.', 'gp-house' ); ?></p>
	</div>
</div>

<?php foreach ( $bookings as $b ) : ?>
	<?php get_template_part( 'template-parts/profile/itinerary', null, array( 'booking' => $b ) ); ?>
<?php endforeach; ?>
