<?php
/**
 * Profile → Points & Privileges (Figma 1148:8570, mobile 1454:14048).
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$m       = gp_profile_user();
$rewards = gp_profile_rewards();
$history = gp_profile_history();
?>
<div class="gp-pts">
	<header class="gp-pts__head">
		<p class="gp-pts__kicker"><?php esc_html_e( 'GP House Club', 'gp-house' ); ?></p>
		<h1><?php esc_html_e( 'Your Rewards,', 'gp-house' ); ?><br><?php esc_html_e( 'Beautifully Considered.', 'gp-house' ); ?></h1>
	</header>

	<section class="gp-pts__balance">
		<p class="gp-pts__label"><?php esc_html_e( 'Your Points', 'gp-house' ); ?></p>
		<p class="gp-pts__value"><?php echo esc_html( number_format_i18n( $m['points'] ) ); ?></p>
		<div class="gp-pts__notes">
			<p><?php esc_html_e( 'Your Points Never Expire While Your Account Remains Active.', 'gp-house' ); ?></p>
			<p><?php esc_html_e( 'Earn 10 Points For Every $1 Spent On Accommodations, Dining, And Spa Experiences.', 'gp-house' ); ?></p>
		</div>
	</section>

	<section class="gp-pts__rewards">
		<p class="gp-pts__kicker"><?php esc_html_e( 'Rewards To Redeem', 'gp-house' ); ?></p>
		<h2><?php esc_html_e( 'Turn Points Into Memories.', 'gp-house' ); ?></h2>
		<div class="gp-pts__grid">
			<?php foreach ( $rewards as $r ) : ?>
				<?php $enough = $m['points'] >= $r['points']; ?>
				<article class="gp-reward">
					<div class="gp-reward__media">
						<img src="<?php echo esc_url( gp_img( $r['image'] ) ); ?>" width="348" height="240" alt="" loading="lazy">
						<?php gp_chip( number_format_i18n( $r['points'] ) . ' ' . __( 'Points', 'gp-house' ) ); ?>
					</div>
					<h3><?php echo wp_kses( str_replace( '|', '<br>', esc_html( $r['title'] ) ), array( 'br' => array() ) ); ?></h3>
					<p><?php echo esc_html( $r['text'] ); ?></p>
					<div class="gp-reward__foot">
						<span><?php echo esc_html( number_format_i18n( $m['points'] ) . ' / ' . number_format_i18n( $r['points'] ) . ' ' . __( 'Points', 'gp-house' ) ); ?></span>
						<?php if ( $enough ) : ?>
							<a class="gp-btn gp-btn--primary gp-btn--card" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Redeem Now', 'gp-house' ); ?></a>
						<?php else : ?>
							<button class="gp-btn gp-btn--primary gp-btn--card" type="button" disabled><?php esc_html_e( 'Insufficient Points', 'gp-house' ); ?></button>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</section>

	<?php if ( $history ) : ?>
		<div class="gp-pts__table" role="table" aria-label="<?php esc_attr_e( 'Points history', 'gp-house' ); ?>">
			<div class="gp-pts__row gp-pts__row--head" role="row"><span role="columnheader"><?php esc_html_e( 'Date', 'gp-house' ); ?></span><span role="columnheader"><?php esc_html_e( 'Description', 'gp-house' ); ?></span><span role="columnheader"><?php esc_html_e( 'Points Earned', 'gp-house' ); ?></span></div>
			<?php foreach ( $history as $h ) : ?>
				<div class="gp-pts__row" role="row"><span role="cell"><?php echo esc_html( $h['date'] ); ?></span><span role="cell"><?php echo esc_html( $h['text'] ); ?></span><span role="cell" class="gp-pts__earn">+<?php echo esc_html( number_format_i18n( $h['points'] ) ); ?></span></div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
