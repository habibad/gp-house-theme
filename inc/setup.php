<?php
/**
 * Theme supports, image sizes and menu locations.
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', function () {
	load_theme_textdomain( 'gp-house', GP_HOUSE_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );

	add_image_size( 'gp-card', 720, 480, true );
	add_image_size( 'gp-wide', 2256, 840, true );
	add_image_size( 'gp-portrait', 720, 1160, true );
	add_image_size( 'gp-room', 1120, 864, true );
	add_image_size( 'gp-room-thumb', 172, 146, true );
	add_image_size( 'gp-room-strip', 344, 240, true );

	register_nav_menus( array(
		'primary'        => __( 'Header — main links', 'gp-house' ),
		'others'         => __( 'Header — "Others" dropdown', 'gp-house' ),
		'footer_explore' => __( 'Footer — Explore', 'gp-house' ),
		'footer_about'   => __( 'Footer — About GP House', 'gp-house' ),
		'footer_support' => __( 'Footer — Support', 'gp-house' ),
	) );
} );

/** Remove front-end weight WordPress adds by default that this theme does not use. */
add_action( 'init', function () {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'wp_generator' );
} );
