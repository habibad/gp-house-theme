<?php
/**
 * Customizer: every heading/text on the home page is editable under
 * Appearance → Customize → GP House.
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

add_action( 'customize_register', function ( WP_Customize_Manager $wp_customize ) {
	$sections = array(
		'hero'         => __( 'Home — Hero', 'gp-house' ),
		'destinations' => __( 'Home — Explore Destinations', 'gp-house' ),
		'stays'        => __( 'Home — Featured Stays', 'gp-house' ),
		'why'          => __( 'Home — Why Book Direct', 'gp-house' ),
		'reviews'      => __( 'Home — Guest Stories', 'gp-house' ),
		'journal'      => __( 'Home — Journal', 'gp-house' ),
		'cta'          => __( 'Home — Call to action', 'gp-house' ),
		'stays_page'   => __( 'Stays page', 'gp-house' ),
		'footer'       => __( 'Footer', 'gp-house' ),
	);

	$wp_customize->add_panel( 'gp_house', array( 'title' => __( 'GP House', 'gp-house' ), 'priority' => 30 ) );
	foreach ( $sections as $id => $title ) {
		$wp_customize->add_section( 'gp_' . $id, array( 'title' => $title, 'panel' => 'gp_house' ) );
	}

	foreach ( gp_option_defaults() as $key => $opt ) {
		$type     = $opt['type'] ?? 'text';
		$sanitize = array(
			'textarea' => 'sanitize_textarea_field',
			'url'      => 'esc_url_raw',
			'image'    => 'absint',
		)[ $type ] ?? 'sanitize_text_field';

		$wp_customize->add_setting( 'gp_' . $key, array( 'default' => $opt['default'], 'sanitize_callback' => $sanitize ) );

		if ( 'image' === $type ) {
			$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'gp_' . $key, array(
				'label'     => $opt['label'],
				'section'   => 'gp_' . $opt['section'],
				'mime_type' => 'image',
			) ) );
			continue;
		}

		$wp_customize->add_control( 'gp_' . $key, array(
			'label'   => $opt['label'],
			'section' => 'gp_' . $opt['section'],
			'type'    => $type,
		) );
	}
} );
