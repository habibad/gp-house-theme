<?php
/**
 * Stays filter bar (Figma: Frame 514, 1148:7290).
 *
 * @var array $args { query: array (gp_room_query_args()) }
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$q    = $args['query'];
$opts = gp_room_filter_options();
$locs = array( '' => __( 'All Destinations', 'gp-house' ) );
foreach ( gp_get_destinations() as $dest ) {
	$locs[ $dest['slug'] ] = $dest['name'];
}
?>
<form class="gp-filters" action="<?php echo esc_url( get_post_type_archive_link( 'gp_stay' ) ?: home_url( '/stays/' ) ); ?>" method="get" data-filters>
	<div class="gp-filters__selects">
		<?php
		gp_dropdown( array( 'name' => 'destination', 'label' => __( 'Location', 'gp-house' ), 'options' => $locs, 'value' => $q['destination'] ) );
		gp_dropdown( array( 'name' => 'room', 'label' => __( 'Room', 'gp-house' ), 'options' => $opts['room'], 'value' => $q['room'] ) );
		gp_dropdown( array( 'name' => 'bed', 'label' => __( 'Bed', 'gp-house' ), 'options' => $opts['bed'], 'value' => $q['bed'] ) );
		?>
	</div>
	<div class="gp-filters__right">
		<div class="gp-filters__tags" role="group" aria-label="<?php esc_attr_e( 'Amenities', 'gp-house' ); ?>">
			<?php foreach ( $opts['tags'] as $slug => $label ) : ?>
				<?php $on = in_array( $slug, $q['tags'], true ); ?>
				<button class="gp-filter-chip<?php echo $on ? ' is-active' : ''; ?>" type="button" aria-pressed="<?php echo $on ? 'true' : 'false'; ?>" data-tag="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $label ); ?></button>
			<?php endforeach; ?>
			<input type="hidden" name="tags" value="<?php echo esc_attr( implode( ',', $q['tags'] ) ); ?>">
		</div>
		<div class="gp-filters__sort">
			<span class="gp-filters__sort-label"><?php esc_html_e( 'Sort:', 'gp-house' ); ?></span>
			<?php gp_dropdown( array( 'name' => 'sort', 'label' => '', 'options' => $opts['sort'], 'value' => $q['sort'], 'show_value' => true, 'class' => 'gp-select--sort', 'align' => 'right' ) ); ?>
		</div>
	</div>
	<noscript><button class="gp-btn gp-btn--navy gp-btn--tab" type="submit"><?php esc_html_e( 'Apply', 'gp-house' ); ?></button></noscript>
</form>
