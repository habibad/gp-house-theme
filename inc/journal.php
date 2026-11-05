<?php
/**
 * The GP Journal: listing page (/journal/, Figma 1148:8205 + empty state 1148:8356)
 * and story pages (/journal/<slug>/, Figma "Journal / Overview" 1148:8455).
 *
 * Stories are regular posts; topics (Architecture, Wellness, …) are post categories.
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

/** Topic tabs: slug => name, in Figma order (categories when they exist, demo topics otherwise). */
function gp_journal_topics() {
	$defaults = gp_journal_default_topics();
	$terms    = get_terms( array( 'taxonomy' => 'category', 'hide_empty' => false, 'exclude' => array( (int) get_option( 'default_category' ) ) ) );
	if ( is_wp_error( $terms ) || ! $terms ) {
		return apply_filters( 'gp_house_journal_topics', $defaults );
	}
	$topics = array();
	foreach ( $terms as $t ) {
		$topics[ $t->slug ] = $t->name;
	}
	// Known topics keep the Figma order; any others follow alphabetically.
	uksort( $topics, function ( $a, $b ) use ( $defaults ) {
		$order = array_flip( array_keys( $defaults ) );
		return array( $order[ $a ] ?? 99, $a ) <=> array( $order[ $b ] ?? 99, $b );
	} );
	return apply_filters( 'gp_house_journal_topics', $topics );
}

/** The topic selected on /journal/?topic=<slug> ('' = All). */
function gp_journal_current_topic() {
	$topic = isset( $_GET['topic'] ) ? sanitize_title( wp_unslash( $_GET['topic'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	return isset( gp_journal_topics()[ $topic ] ) ? $topic : '';
}

/** Link to a topic on the journal listing ('' = All). */
function gp_journal_topic_url( $slug = '' ) {
	$url = home_url( '/journal/' );
	return ( $slug ? add_query_arg( 'topic', $slug, $url ) : $url ) . '#stories';
}

/** Story pages swap the footer call-to-action (Figma "Experience It In Person"). */
add_filter( 'gp_house_footer_cta', function ( $cta ) {
	if ( ! is_singular( 'post' ) ) {
		return $cta;
	}
	return array(
		'kicker'  => gp_opt( 'story_cta_kicker' ),
		'title'   => gp_opt( 'story_cta_title' ),
		'text'    => gp_opt( 'story_cta_text' ),
		'class'   => 'gp-cta--story',
		'buttons' => array(
			array( 'label' => __( 'Book Your Stay', 'gp-house' ), 'url' => home_url( '/stays/' ), 'size' => 'tab' ),
		),
	);
} );
