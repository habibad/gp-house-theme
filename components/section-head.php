<?php
/**
 * Section heading: kicker + title, optional actions on the right.
 *
 * @var array $args {
 *   kicker: string, title: string, tag?: h1|h2,
 *   tone?: ''|'light' (white kicker + gold title on navy),
 *   align?: ''|'center', kicker_class?: string, title_class?: string,
 *   actions?: array<array> gp_button() args, actions_html?: callable,
 *   class?: string
 * }
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$a = wp_parse_args( $args, array(
	'kicker'       => '',
	'title'        => '',
	'tag'          => 'h2',
	'tone'         => '',
	'align'        => '',
	'kicker_class' => '',
	'title_class'  => '',
	'gap'          => '',
	'actions'      => array(),
	'actions_html' => null,
	'class'        => '',
) );

$tag      = in_array( $a['tag'], array( 'h1', 'h2', 'h3' ), true ) ? $a['tag'] : 'h2';
$heading  = 'gp-heading' . ( 'center' === $a['align'] ? ' gp-heading--center' : '' ) . ( $a['gap'] ? ' gp-heading--' . $a['gap'] : '' );
$kicker   = trim( 'gp-kicker ' . ( 'light' === $a['tone'] ? 'gp-kicker--light ' : '' ) . $a['kicker_class'] );
$title    = trim( 'gp-title ' . ( 'light' === $a['tone'] ? 'gp-title--gold ' : '' ) . $a['title_class'] );
$has_side = $a['actions'] || is_callable( $a['actions_html'] );

$heading_html = function () use ( $a, $tag, $heading, $kicker, $title ) {
	?>
	<div class="<?php echo esc_attr( $heading ); ?>">
		<?php if ( $a['kicker'] ) : ?>
			<p class="<?php echo esc_attr( $kicker ); ?>"><?php echo esc_html( $a['kicker'] ); ?></p>
		<?php endif; ?>
		<<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput -- whitelisted. ?> class="<?php echo esc_attr( $title ); ?>"><?php echo esc_html( $a['title'] ); ?></<?php echo $tag; // phpcs:ignore ?>>
	</div>
	<?php
};

if ( ! $has_side ) {
	$heading_html();
	return;
}
?>
<div class="gp-section-head <?php echo esc_attr( $a['class'] ); ?>">
	<?php $heading_html(); ?>
	<div class="gp-section-head__actions">
		<?php
		if ( is_callable( $a['actions_html'] ) ) {
			call_user_func( $a['actions_html'] );
		}
		foreach ( $a['actions'] as $button ) {
			gp_button( $button );
		}
		?>
	</div>
</div>
