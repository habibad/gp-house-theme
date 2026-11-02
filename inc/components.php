<?php
/**
 * Component API.
 *
 * Small, frequently-used atoms (button, chip) are PHP functions. Larger
 * reusable blocks live in components/<name>.php and are rendered with
 * gp_component( 'name', $args ) — they receive $args (WordPress 5.5+).
 *
 * Every component maps to a Figma component on the "Component" page.
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

/** Render components/<name>.php with $args. */
function gp_component( $name, $args = array() ) {
	get_template_part( 'components/' . $name, null, $args );
}

/** Build an HTML attribute string from an array (null/false values skipped). */
function gp_attrs( array $attrs ) {
	$out = '';
	foreach ( $attrs as $name => $value ) {
		if ( null === $value || false === $value ) {
			continue;
		}
		$out .= true === $value ? ' ' . esc_attr( $name ) : sprintf( ' %s="%s"', esc_attr( $name ), esc_attr( $value ) );
	}
	return $out;
}

/**
 * Button (Figma: Button / tab components).
 *
 * @param array $args {
 *   label: string, url?: string (renders <a>, else <button>),
 *   variant?: primary|navy|outline, size?: xs|sm|tab|md|lg,
 *   text?: '' |'navy' (label colour override), class?: string, attrs?: array
 * }
 */
function gp_button( array $args ) {
	$args = wp_parse_args( $args, array(
		'label'   => '',
		'url'     => '',
		'variant' => 'primary',
		'size'    => 'md',
		'text'    => '',
		'class'   => '',
		'attrs'   => array(),
		'type'    => 'button',
	) );

	$classes = array( 'gp-btn', 'gp-btn--' . $args['variant'], 'gp-btn--' . $args['size'] );
	if ( $args['text'] ) {
		$classes[] = 'gp-btn--' . $args['text'] . '-text';
	}
	if ( $args['class'] ) {
		$classes[] = $args['class'];
	}
	$attrs = array_merge( array( 'class' => implode( ' ', $classes ) ), $args['attrs'] );

	if ( $args['url'] ) {
		printf( '<a href="%s"%s>%s</a>', esc_url( $args['url'] ), gp_attrs( $attrs ), esc_html( $args['label'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- gp_attrs escapes.
	} else {
		printf( '<button type="%s"%s>%s</button>', esc_attr( $args['type'] ), gp_attrs( $attrs ), esc_html( $args['label'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput
	}
}

/** Location chip on images (Figma: white label, 8px radius). */
function gp_chip( $text, $modifier = '' ) {
	if ( '' === (string) $text ) {
		return;
	}
	printf( '<span class="gp-chip%s">%s</span>', $modifier ? ' gp-chip--' . esc_attr( $modifier ) : '', esc_html( $text ) );
}
