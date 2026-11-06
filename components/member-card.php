<?php
/**
 * Member card — avatar, "Hello, <name>", e-mail and tier badge
 * (Figma: profile sidebar, 259 × 63; also the account pop-up in the header).
 *
 * @var array $args { member: array (gp_profile_user() shape), class?: string }
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$m = $args['member'];
?>
<div class="gp-member<?php echo ! empty( $args['class'] ) ? ' ' . esc_attr( $args['class'] ) : ''; ?>">
	<img class="gp-member__avatar" src="<?php echo esc_url( $m['avatar'] ); ?>" width="44" height="44" alt="">
	<div class="gp-member__text">
		<p class="gp-member__name"><?php echo esc_html( sprintf( /* translators: %s: first name */ __( 'Hello, %s', 'gp-house' ), $m['first_name'] ) ); ?></p>
		<p class="gp-member__mail"><?php echo esc_html( $m['email'] ); ?></p>
	</div>
	<span class="gp-member__tier"><?php echo esc_html( $m['tier'] ); ?></span>
</div>
