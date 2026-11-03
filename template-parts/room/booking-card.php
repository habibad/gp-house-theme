<?php
/**
 * Room page booking card (Figma 1148:10358). The nights counter recalculates
 * the estimate in main.js; real availability/rates come from eZee later.
 *
 * @var array $args { room: array }
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$room   = $args['room'];
$nights = 3;
$price  = (float) $room['price'];
$book   = apply_filters( 'gp_house_room_reserve_url', '', $room );
?>
<aside class="gp-booking-card" id="book" data-booking data-price="<?php echo esc_attr( $price ); ?>">
	<div class="gp-booking-card__main">
		<div class="gp-booking-card__top">
			<div class="gp-booking-card__rate">
				<div class="gp-booking-card__price">
					<span class="gp-booking-card__label"><?php esc_html_e( 'Direct Rate', 'gp-house' ); ?></span>
					<p><strong><?php echo esc_html( gp_price( $price ) ); ?> / </strong><span class="gp-booking-card__unit"><?php esc_html_e( 'night', 'gp-house' ); ?></span></p>
				</div>
				<span class="gp-booking-card__label gp-booking-card__label--lh"><?php esc_html_e( 'Best Price Guarantee', 'gp-house' ); ?></span>
			</div>
			<div class="gp-counter" data-nights-counter>
				<button type="button" data-nights-step="-1" aria-label="<?php esc_attr_e( 'Fewer nights', 'gp-house' ); ?>"><?php gp_icon( 'minus-grey', 24 ); ?></button>
				<span class="gp-counter__value" aria-live="polite"><span data-nights><?php echo (int) $nights; ?></span> <?php esc_html_e( 'Nights', 'gp-house' ); ?></span>
				<button type="button" data-nights-step="1" aria-label="<?php esc_attr_e( 'More nights', 'gp-house' ); ?>"><?php gp_icon( 'plus-grey', 24 ); ?></button>
			</div>
		</div>

		<dl class="gp-booking-card__lines">
			<div><dt><?php echo esc_html( gp_price( $price ) ); ?> × <span data-nights><?php echo (int) $nights; ?></span> <?php esc_html_e( 'nights:', 'gp-house' ); ?></dt><dd data-subtotal><?php echo esc_html( gp_price( $price * $nights ) ); ?></dd></div>
			<div><dt><?php esc_html_e( 'Resort Services & Amenities:', 'gp-house' ); ?></dt><dd><?php esc_html_e( 'Included', 'gp-house' ); ?></dd></div>
			<div class="gp-booking-card__total"><dt><?php esc_html_e( 'Estimated Total:', 'gp-house' ); ?></dt><dd data-total><?php echo esc_html( gp_price( $price * $nights ) ); ?></dd></div>
		</dl>

		<?php
		gp_button( array(
			'label' => __( 'Reserve this Suite', 'gp-house' ),
			'class' => 'gp-booking-card__cta gp-no-caps',
			'url'   => $book ?: '#book',
			'size'  => 'card',
		) );
		?>
	</div>
	<p class="gp-booking-card__note"><?php echo esc_html( $room['note'] ?: gp_opt( 'room_booking_note' ) ); ?></p>
</aside>
