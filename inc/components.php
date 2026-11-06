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

/**
 * Filter dropdown (Figma: "Location tab", "Room", "Bed:", "Short by").
 * Renders a button + option list and a hidden input carrying the value, so it
 * works inside a GET form and is enhanced by main.js.
 *
 * @param array $args {
 *   name: string, label: string, options: array<value, label>, value?: string,
 *   show_value?: bool (button shows the selected option, e.g. Sort),
 *   class?: string, align?: 'left'|'right'
 * }
 */
function gp_dropdown( array $args ) {
	$args = wp_parse_args( $args, array( 'name' => '', 'label' => '', 'options' => array(), 'value' => '', 'show_value' => false, 'class' => '', 'align' => 'left', 'icon' => '' ) );

	$value   = isset( $args['options'][ $args['value'] ] ) ? (string) $args['value'] : (string) array_key_first( $args['options'] );
	$current = $args['options'][ $value ] ?? '';
	$text    = $args['show_value'] || '' !== $args['value'] ? $current : $args['label'];
	$id      = 'gp-dd-' . sanitize_key( $args['name'] );
	?>
	<div class="gp-select<?php echo $args['class'] ? ' ' . esc_attr( $args['class'] ) : ''; ?><?php echo '' !== $args['value'] && ! $args['show_value'] ? ' is-set' : ''; ?>" data-dropdown>
		<button class="gp-select__toggle" type="button" aria-haspopup="listbox" aria-expanded="false" aria-controls="<?php echo esc_attr( $id ); ?>" data-label="<?php echo esc_attr( $args['label'] ); ?>">
			<?php if ( $args['icon'] ) { gp_icon( $args['icon'], 20 ); } ?>
			<span class="gp-select__text"><?php echo esc_html( $text ); ?></span>
			<?php gp_icon( 'chevron-down', 16 ); ?>
		</button>
		<ul class="gp-select__menu gp-select__menu--<?php echo esc_attr( $args['align'] ); ?>" id="<?php echo esc_attr( $id ); ?>" role="listbox" hidden>
			<?php foreach ( $args['options'] as $opt_value => $opt_label ) : ?>
				<li role="option" tabindex="-1" data-value="<?php echo esc_attr( $opt_value ); ?>" aria-selected="<?php echo (string) $opt_value === $value ? 'true' : 'false'; ?>"><?php echo esc_html( $opt_label ); ?></li>
			<?php endforeach; ?>
		</ul>
		<input type="hidden" name="<?php echo esc_attr( $args['name'] ); ?>" value="<?php echo esc_attr( $args['value'] ); ?>">
	</div>
	<?php
}
