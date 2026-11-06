<?php
/**
 * Profile → Saved Stays (Figma 1148:9361 / empty 1148:9273, mobile 1454:20300).
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$saved = gp_profile_saved();

if ( ! $saved ) {
	get_template_part( 'template-parts/profile/empty', null, array(
		'image' => 'profile/empty-saved.png',
		'title' => __( 'No Saved Stays Yet', 'gp-house' ),
		'text'  => __( 'Save Your Favorite Stays Here For Easy Access And Future Booking.', 'gp-house' ),
	) );
	return;
}
?>
<div class="gp-saved">
	<?php foreach ( $saved as $stay ) : ?>
		<?php gp_component( 'stay-card', array( 'stay' => $stay, 'saved' => true ) ); ?>
	<?php endforeach; ?>
</div>
