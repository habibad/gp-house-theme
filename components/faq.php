<?php
/**
 * FAQ section (Figma "FAQs", 1148:10734): centred heading + accordion. The
 * first item starts open. Used on destination pages and the Club page.
 *
 * @var array $args { items: array<array{0:string,1:string}>, kicker?: string, title?: string, open?: int (item open on load, -1 = none), variant?: string (e.g. 'compact') }
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$items = $args['items'] ?? array();
if ( ! $items ) {
	return;
}
$uid       = wp_unique_id( 'gp-faq-' );
$open_item = isset( $args['open'] ) ? (int) $args['open'] : 0;
$variant   = $args['variant'] ?? '';
?>
<section class="gp-faq<?php echo $variant ? ' gp-faq--' . esc_attr( $variant ) : ''; ?>">
	<div class="gp-container">
		<?php gp_component( 'section-head', array( 'kicker' => $args['kicker'] ?? __( 'Club Knowledge', 'gp-house' ), 'title' => $args['title'] ?? __( 'Frequently Asked Questions', 'gp-house' ), 'align' => 'center', 'title_class' => 'gp-title--h3' ) ); ?>
		<div class="gp-faq__list" data-accordion>
			<?php foreach ( $items as $i => $item ) : ?>
				<?php $open = $open_item === $i; ?>
				<div class="gp-faq__item<?php echo $open ? ' is-open' : ''; ?>">
					<h3 class="gp-faq__q">
						<button type="button" aria-expanded="<?php echo $open ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( "{$uid}-{$i}" ); ?>">
							<span><?php echo esc_html( $item[0] ); ?></span>
							<span class="gp-faq__icon" aria-hidden="true"></span>
						</button>
					</h3>
					<div class="gp-faq__a" id="<?php echo esc_attr( "{$uid}-{$i}" ); ?>"<?php echo $open ? '' : ' hidden'; ?>>
						<p><?php echo esc_html( $item[1] ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
