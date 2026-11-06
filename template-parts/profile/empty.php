<?php
/**
 * Profile empty state (Figma: "No Bookings Yet" / "No Saved Stays Yet").
 *
 * @var array $args { image: string, title: string, text: string }
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="gp-pf-empty" role="status">
	<img src="<?php echo esc_url( gp_img( $args['image'] ) ); ?>" alt="" aria-hidden="true">
	<h2><?php echo esc_html( $args['title'] ); ?></h2>
	<p><?php echo esc_html( $args['text'] ); ?></p>
	<?php gp_button( array( 'label' => __( 'Explore Stays', 'gp-house' ), 'url' => home_url( '/stays/' ), 'size' => 'tab' ) ); ?>
</div>
