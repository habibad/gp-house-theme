<?php
/**
 * Empty state panel — icon tile, title and a short note
 * (Figma "Journal / Wellness": "Nothing Here Yet").
 *
 * @var array $args { icon: string URL, title: string, text?: string, class?: string }
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$a = wp_parse_args( $args, array( 'icon' => '', 'title' => '', 'text' => '', 'class' => '' ) );
?>
<div class="gp-empty<?php echo $a['class'] ? ' ' . esc_attr( $a['class'] ) : ''; ?>" role="status">
	<?php if ( $a['icon'] ) : ?>
		<span class="gp-empty__icon"><img src="<?php echo esc_url( $a['icon'] ); ?>" width="64" height="64" alt=""></span>
	<?php endif; ?>
	<h3 class="gp-empty__title"><?php echo esc_html( $a['title'] ); ?></h3>
	<?php if ( $a['text'] ) : ?>
		<p class="gp-empty__text"><?php echo esc_html( $a['text'] ); ?></p>
	<?php endif; ?>
</div>
