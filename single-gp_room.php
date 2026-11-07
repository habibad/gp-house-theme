<?php
/**
 * Room page — Figma "Stays/ overview" (1148:10234).
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$room = gp_room_from_post( get_queried_object() );

get_header();
?>
<main id="gp-main" class="gp-room-page">
	<?php
	gp_component( 'page-hero', array(
		'image'  => $room['hero'],
		'image_mobile' => $room['hero_mobile'],
		'kicker' => $room['property'] ?: $room['location'],
		'title'  => $room['title'],
		'text'   => $room['hero_text'] ?: $room['view'],
		'class'  => 'gp-page-hero--room',
	) );

	get_template_part( 'template-parts/room/gallery', null, array( 'room' => $room ) );
	?>

	<section class="gp-room-details">
		<?php get_template_part( 'template-parts/room/details', null, array( 'room' => $room ) ); ?>
		<?php get_template_part( 'template-parts/room/booking-card', null, array( 'room' => $room ) ); ?>
	</section>
	<?php get_template_part( 'template-parts/room/reserve', null, array( 'room' => $room ) ); ?>
</main>
<?php
get_footer();
