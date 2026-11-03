<?php
/**
 * Stays page (/stays/) — Figma "Home / Stays" (1148:7257).
 * Lists rooms & suites with filters; the gp_stay archive URL is kept so links
 * from the home search (/stays/?destination=…&guests=…) land here.
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$query = gp_room_query_args();
$rooms = gp_get_rooms( array( 'sort' => $query['sort'] ) );
$shown = wp_list_pluck( gp_get_rooms( $query ), 'id' );

get_header();
?>
<main id="gp-main" class="gp-stays-page">
	<?php
	gp_component( 'page-hero', array(
		'image'  => gp_opt( 'stays_hero_image' ) ? wp_get_attachment_image_url( (int) gp_opt( 'stays_hero_image' ), 'full' ) : gp_img( 'photos/hero-stays.jpg' ),
		'image_mobile' => gp_opt( 'stays_hero_image' ) ? '' : gp_img( 'photos/hero-stays-mobile.jpg' ),
		'kicker' => gp_opt( 'stays_hero_kicker' ),
		'title'  => gp_opt( 'stays_hero_title' ),
		'text'   => gp_opt( 'stays_hero_text' ),
	) );
	?>

	<section class="gp-stays">
		<div class="gp-container">
			<?php get_template_part( 'template-parts/stays/filters', null, array( 'query' => $query ) ); ?>

			<div class="gp-stays__list" data-rooms>
				<?php
				foreach ( $rooms as $room ) {
					gp_component( 'room-card', array( 'room' => $room, 'hidden' => ! in_array( $room['id'], $shown, true ) ) );
				}
				?>
				<p class="gp-stays__empty"<?php echo $shown ? ' hidden' : ''; ?> data-rooms-empty><?php esc_html_e( 'No rooms match these filters. Try removing one.', 'gp-house' ); ?></p>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
