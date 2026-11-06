<?php
/**
 * Basic SEO head tags: description, Open Graph / Twitter card, theme colour.
 * Override per page with the `gp_house_meta_description` / `gp_house_meta_image` filters.
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

/** Description used by the meta tags for the current request. */
function gp_meta_description() {
	$defaults = array(
		'stays'              => 'Browse GP House stays and private pool villas across Phuket, Krabi and Bangkok. Book direct for the lowest rate.',
		'destinations'       => 'Explore GP House boutique sanctuaries across Thailand on an interactive map — Phuket, Krabi and Bangkok.',
		'offers'             => 'Direct-booking offers and packages from GP House: member rates, spa credits, transfers and seasonal escapes.',
		'club'               => 'GP House Club: earn points on every stay, enjoy member rates, late-night self check-in and rewards without blackout dates.',
		'journal'            => 'Stories on Thai architecture, wellness, culture and cuisine from the GP House journal.',
		'about'              => 'The story behind GP House — small teak sanctuaries, local design and warm Thai hospitality.',
		'investor-relations' => 'Partner with GP House Hospitality Management: revenue management, Thai design and a direct-booking engine for hotel owners.',
		'self-check-in'      => 'Arriving late? Enter your booking ID to get your room number, door PIN and gate directions between 11 PM and 6 AM.',
		'contact'            => 'Contact the GP House concierge for stays, transfers, celebrations and membership questions.',
	);
	$text = '';
	if ( is_front_page() ) {
		$text = get_bloginfo( 'description' ) ?: 'GP House — Thai boutique sanctuaries in Phuket, Krabi and Bangkok. Stay. Explore. Connect. Belong.';
	} elseif ( is_page() ) {
		$slug = get_post_field( 'post_name', get_queried_object_id() );
		$text = $defaults[ $slug ] ?? '';
	}
	if ( ! $text && is_singular() ) {
		$text = has_excerpt() ? get_the_excerpt() : wp_trim_words( wp_strip_all_tags( strip_shortcodes( (string) get_post_field( 'post_content', get_queried_object_id() ) ) ), 28, '…' );
	}
	if ( ! $text && is_tax() ) {
		$text = term_description();
	}
	return (string) apply_filters( 'gp_house_meta_description', trim( wp_strip_all_tags( $text ) ) );
}

add_action( 'wp_head', function () {
	if ( is_404() || is_search() ) {
		return;
	}
	$desc  = gp_meta_description();
	$title = wp_get_document_title();
	$image = (string) apply_filters( 'gp_house_meta_image', is_singular() && has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'large' ) : gp_img( 'photos/hero-room.jpg' ) );
	$url   = is_singular() ? get_permalink() : home_url( add_query_arg( null, null ) );

	echo '<meta name="theme-color" content="#011041">' . "\n";
	if ( $desc ) {
		printf( "<meta name=\"description\" content=\"%s\">\n", esc_attr( $desc ) );
		printf( "<meta property=\"og:description\" content=\"%s\">\n", esc_attr( $desc ) );
	}
	printf( "<meta property=\"og:site_name\" content=\"%s\">\n", esc_attr( get_bloginfo( 'name' ) ) );
	printf( "<meta property=\"og:title\" content=\"%s\">\n", esc_attr( $title ) );
	printf( "<meta property=\"og:type\" content=\"%s\">\n", is_singular( 'post' ) ? 'article' : 'website' );
	printf( "<meta property=\"og:url\" content=\"%s\">\n", esc_url( $url ) );
	printf( "<meta property=\"og:image\" content=\"%s\">\n", esc_url( $image ) );
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
}, 3 );
