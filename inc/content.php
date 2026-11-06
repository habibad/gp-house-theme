<?php
/**
 * Content access layer.
 *
 * Every getter returns plain arrays in one shape, read from the GP House Core
 * plugin's content types when they have entries, otherwise from the Figma demo
 * content — so templates never care where data came from.
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

/** Attachment URL, or theme asset URL for demo paths. */
function gp_media_url( $id_or_path, $size = 'full' ) {
	if ( is_numeric( $id_or_path ) && $id_or_path ) {
		return (string) wp_get_attachment_image_url( (int) $id_or_path, $size );
	}
	return $id_or_path ? gp_img( $id_or_path ) : '';
}

function gp_has_posts( $post_type ) {
	if ( ! post_type_exists( $post_type ) ) {
		return false;
	}
	$counts = wp_count_posts( $post_type );
	return ! empty( $counts->publish );
}

/** Destinations (taxonomy gp_destination). */
function gp_get_destinations() {
	if ( taxonomy_exists( 'gp_destination' ) ) {
		$terms = get_terms( array( 'taxonomy' => 'gp_destination', 'hide_empty' => false ) );
		if ( ! is_wp_error( $terms ) && $terms ) {
			usort( $terms, function ( $a, $b ) {
				return (int) get_term_meta( $a->term_id, 'gp_order', true ) <=> (int) get_term_meta( $b->term_id, 'gp_order', true );
			} );
			return array_map( function ( $t ) {
				return array(
					'slug'       => $t->slug,
					'name'       => $t->name,
					'card_title' => get_term_meta( $t->term_id, 'gp_card_title', true ) ?: $t->name,
					'subtitle'   => get_term_meta( $t->term_id, 'gp_subtitle', true ),
					'image'      => gp_media_url( get_term_meta( $t->term_id, 'gp_image_id', true ), 'gp-portrait' ),
					'url'        => get_term_link( $t ),
				);
			}, $terms );
		}
	}
	return array_map( function ( $d ) {
		$d['image'] = gp_media_url( $d['image'] );
		$d['url']   = home_url( '/destinations/' . $d['slug'] . '/' );
		return $d;
	}, gp_demo_content()['destinations'] );
}

/** Split "icon|text" amenity lines. */
function gp_parse_amenities( $lines ) {
	$out = array();
	foreach ( (array) $lines as $line ) {
		$parts = array_map( 'trim', explode( '|', (string) $line, 2 ) );
		if ( count( $parts ) === 2 && $parts[1] !== '' ) {
			$out[] = array( 'icon' => sanitize_key( $parts[0] ), 'text' => $parts[1] );
		}
	}
	return $out;
}

/**
 * Stays.
 *
 * @param array $args { featured?: bool, map?: bool, limit?: int }
 */
function gp_get_stays( $args = array() ) {
	$args = wp_parse_args( $args, array( 'featured' => false, 'map' => false, 'limit' => -1 ) );

	if ( gp_has_posts( 'gp_stay' ) ) {
		$meta_query = array();
		if ( $args['featured'] ) {
			$meta_query[] = array( 'key' => 'gp_featured', 'value' => '1' );
		}
		if ( $args['map'] ) {
			$meta_query[] = array( 'key' => 'gp_show_on_map', 'value' => '1' );
		}
		$posts = get_posts( array(
			'post_type'      => 'gp_stay',
			'posts_per_page' => $args['limit'],
			'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
			'meta_query'     => $meta_query, // phpcs:ignore WordPress.DB.SlowDBQuery
		) );
		return array_map( function ( $p ) {
			$terms = get_the_terms( $p, 'gp_destination' );
			$thumb = get_post_meta( $p->ID, 'gp_thumb_id', true );
			$main  = get_post_meta( $p->ID, 'gp_main_image_id', true );
			return array(
				'id'         => $p->ID,
				'title'      => get_the_title( $p ),
				'url'        => get_permalink( $p ),
				'location'   => $terms && ! is_wp_error( $terms ) ? $terms[0]->name : '',
				'tagline'    => get_post_meta( $p->ID, 'gp_tagline', true ),
				'excerpt'    => get_the_excerpt( $p ),
				'price'      => (float) get_post_meta( $p->ID, 'gp_price', true ),
				'reviews'    => (int) get_post_meta( $p->ID, 'gp_reviews', true ),
				'image'      => get_the_post_thumbnail_url( $p, 'gp-card' ) ?: '',
				'image_main' => gp_media_url( $main ?: get_post_thumbnail_id( $p ), 'large' ),
				'thumb'      => gp_media_url( $thumb ?: get_post_thumbnail_id( $p ), 'medium' ),
				'amenities'  => gp_parse_amenities( get_post_meta( $p->ID, 'gp_amenities', true ) ),
				'pin'        => array_filter( array( get_post_meta( $p->ID, 'gp_pin_x', true ), get_post_meta( $p->ID, 'gp_pin_y', true ) ), 'strlen' ),
				'pin_mobile' => array_filter( array( get_post_meta( $p->ID, 'gp_pin_mx', true ), get_post_meta( $p->ID, 'gp_pin_my', true ) ), 'strlen' ),
				'map_active' => (bool) get_post_meta( $p->ID, 'gp_map_active', true ),
			);
		}, $posts );
	}

	$stays = array();
	foreach ( gp_demo_content()['stays'] as $i => $s ) {
		if ( ( $args['featured'] && empty( $s['featured'] ) ) || ( $args['map'] && empty( $s['map'] ) ) ) {
			continue;
		}
		$dest    = wp_list_filter( gp_demo_content()['destinations'], array( 'slug' => $s['destination'] ) );
		$stays[] = array(
			'id'         => $i + 1,
			'title'      => $s['title'],
			'url'        => home_url( '/stays/' . $s['slug'] . '/' ),
			'location'   => $dest ? reset( $dest )['name'] : '',
			'tagline'    => $s['tagline'],
			'excerpt'    => $s['excerpt'],
			'price'      => $s['price'],
			'reviews'    => $s['reviews'],
			'image'      => gp_media_url( $s['image'] ),
			'image_main' => gp_media_url( $s['image_main'] ?? $s['image'] ),
			'thumb'      => gp_media_url( $s['thumb'] ),
			'amenities'  => gp_parse_amenities( $s['amenities'] ),
			'pin'        => $s['pin'],
			'pin_mobile' => $s['pin_mobile'] ?? array(),
			'map_active' => ! empty( $s['map_active'] ),
		);
	}
	return $args['limit'] > 0 ? array_slice( $stays, 0, $args['limit'] ) : $stays;
}

/** Guest testimonials. */
function gp_get_testimonials( $limit = 9 ) {
	if ( gp_has_posts( 'gp_testimonial' ) ) {
		$posts = get_posts( array( 'post_type' => 'gp_testimonial', 'posts_per_page' => $limit, 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'DESC' ) ) );
		return array_map( function ( $p ) {
			return array(
				'name'     => get_the_title( $p ),
				'location' => get_post_meta( $p->ID, 'gp_location', true ),
				'avatar'   => get_the_post_thumbnail_url( $p, 'thumbnail' ) ?: '',
				'quote'    => wp_strip_all_tags( $p->post_content ),
			);
		}, $posts );
	}
	return array_map( function ( $t ) {
		$t['avatar'] = gp_media_url( $t['avatar'] );
		return $t;
	}, array_slice( gp_demo_content()['testimonials'], 0, $limit ) );
}

/**
 * Journal stories (regular posts, tagged with a destination and filed under a topic category).
 *
 * @param int   $limit -1 for all.
 * @param array $args {
 *   destination?: string slug, home?: bool (only stories flagged for the home page),
 *   topic?: string category slug, listing?: bool (sticky "featured" stories first, then newest)
 * }
 */
function gp_get_journal( $limit = 3, $args = array() ) {
	$args  = wp_parse_args( $args, array( 'destination' => '', 'home' => false, 'topic' => '', 'listing' => false ) );
	$query = array( 'post_type' => 'post', 'posts_per_page' => $limit, 'meta_key' => 'gp_read_time', 'ignore_sticky_posts' => true ); // phpcs:ignore WordPress.DB.SlowDBQuery
	if ( $args['home'] ) {
		$query['meta_query'] = array( array( 'key' => 'gp_home_featured', 'value' => '1' ) ); // phpcs:ignore WordPress.DB.SlowDBQuery
	}
	if ( $args['destination'] && taxonomy_exists( 'gp_destination' ) ) {
		$query['order']     = 'ASC'; // Figma lists a destination's stories oldest first.
		$query['tax_query'] = array( array( 'taxonomy' => 'gp_destination', 'field' => 'slug', 'terms' => $args['destination'] ) ); // phpcs:ignore WordPress.DB.SlowDBQuery
	}
	if ( $args['topic'] ) {
		$query['category_name'] = $args['topic'];
	}

	if ( $args['listing'] ) {
		$sticky = array_filter( array_map( 'intval', (array) get_option( 'sticky_posts', array() ) ) );
		$first  = $sticky ? get_posts( array( 'post__in' => $sticky, 'posts_per_page' => -1 ) + $query ) : array();
		$rest   = get_posts( array( 'post__not_in' => $sticky ) + $query );
		$posts  = array_merge( $first, $rest );
		$posts  = $limit > 0 ? array_slice( $posts, 0, $limit ) : $posts;
	} else {
		$posts = get_posts( $query );
	}
	// Real stories exist (even if none match this filter): never mix in demo content.
	if ( $posts || get_posts( array( 'post_type' => 'post', 'posts_per_page' => 1, 'fields' => 'ids', 'meta_key' => 'gp_read_time' ) ) ) { // phpcs:ignore WordPress.DB.SlowDBQuery
		return array_map( 'gp_journal_story_from_post', $posts );
	}

	$dests   = gp_demo_content()['destinations'];
	$journal = array_filter( gp_demo_content()['journal'], function ( $j ) use ( $args ) {
		return ( ! $args['home'] || ! empty( $j['home'] ) )
			&& ( ! $args['destination'] || $j['destination'] === $args['destination'] )
			&& ( ! $args['topic'] || ( $j['topic'] ?? '' ) === $args['topic'] );
	} );
	if ( $args['listing'] ) {
		usort( $journal, fn( $a, $b ) => (int) empty( $a['sticky'] ) <=> (int) empty( $b['sticky'] ) );
	}
	$topics = gp_journal_default_topics();
	return array_map( function ( $j ) use ( $dests, $topics ) {
		$dest = wp_list_filter( $dests, array( 'slug' => $j['destination'] ) );
		return array(
			'id'             => 0,
			'title'          => $j['title'],
			'url'            => home_url( '/journal/' . $j['slug'] . '/' ),
			'location'       => $dest ? reset( $dest )['name'] : '',
			'location_slug'  => $j['destination'],
			'topic'          => $topics[ $j['topic'] ?? '' ] ?? '',
			'topic_slug'     => $j['topic'] ?? '',
			'date'           => date_i18n( 'F j, Y', strtotime( $j['date'] ) ),
			'read_time'      => $j['read_time'],
			'excerpt'        => $j['excerpt'],
			'image'          => gp_media_url( $j['image'] ),
			'card_image'     => gp_media_url( $j['card_image'] ?? $j['image'] ),
			'author_name'    => $j['author_name'],
			'author_role'    => $j['author_role'],
			'author_avatar'  => gp_media_url( $j['author_avatar'] ),
			'author_photo'   => gp_media_url( $j['author_photo'] ?? $j['author_avatar'] ),
			'author_socials' => array(),
		);
	}, $limit > 0 ? array_slice( array_values( $journal ), 0, $limit ) : array_values( $journal ) );
}

/** One journal story (WP post) in the shared array shape. */
function gp_journal_story_from_post( $p ) {
	$p      = get_post( $p );
	$terms  = taxonomy_exists( 'gp_destination' ) ? get_the_terms( $p, 'gp_destination' ) : false;
	$topics = array_values( array_filter( (array) get_the_category( $p->ID ), fn( $t ) => 'uncategorized' !== $t->slug ) );
	$avatar = get_post_meta( $p->ID, 'gp_author_avatar_id', true );
	$photo  = get_post_meta( $p->ID, 'gp_author_photo_id', true );
	$card   = get_post_meta( $p->ID, 'gp_card_image_id', true );
	$socials = array();
	foreach ( array( 'facebook', 'instagram', 'x', 'linkedin' ) as $net ) {
		$socials[ $net ] = get_post_meta( $p->ID, 'gp_author_' . $net, true ) ?: gp_opt( 'social_' . $net );
	}
	return array(
		'id'             => $p->ID,
		'title'          => get_the_title( $p ),
		'url'            => get_permalink( $p ),
		'location'       => $terms && ! is_wp_error( $terms ) ? $terms[0]->name : '',
		'location_slug'  => $terms && ! is_wp_error( $terms ) ? $terms[0]->slug : '',
		'topic'          => $topics ? $topics[0]->name : '',
		'topic_slug'     => $topics ? $topics[0]->slug : '',
		'date'           => get_the_date( 'F j, Y', $p ),
		'read_time'      => get_post_meta( $p->ID, 'gp_read_time', true ),
		'excerpt'        => get_the_excerpt( $p ),
		'image'          => get_the_post_thumbnail_url( $p, 'gp-wide' ) ?: '',
		'card_image'     => $card ? gp_media_url( $card, 'gp-card' ) : ( get_the_post_thumbnail_url( $p, 'gp-card' ) ?: '' ),
		'author_name'    => get_post_meta( $p->ID, 'gp_author_name', true ) ?: get_the_author_meta( 'display_name', $p->post_author ),
		'author_role'    => get_post_meta( $p->ID, 'gp_author_role', true ),
		'author_avatar'  => $avatar ? gp_media_url( $avatar, 'thumbnail' ) : get_avatar_url( $p->post_author, array( 'size' => 76 ) ),
		'author_photo'   => $photo ? gp_media_url( $photo, 'medium' ) : ( $avatar ? gp_media_url( $avatar, 'medium' ) : get_avatar_url( $p->post_author, array( 'size' => 220 ) ) ),
		'author_socials' => $socials,
	);
}
