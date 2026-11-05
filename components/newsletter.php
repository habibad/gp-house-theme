<?php
/**
 * Newsletter sign-up band (Figma "Receive The GP House Gazette", 1440 × 590).
 * The form posts to admin-post.php?action=gp_newsletter (handled by GP House Core).
 *
 * @var array $args { kicker, title, text, placeholder, button: string, image?: string URL, class?: string }
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$a = wp_parse_args( $args, array(
	'kicker'      => gp_opt( 'news_kicker' ),
	'title'       => gp_opt( 'news_title' ),
	'text'        => gp_opt( 'news_text' ),
	'placeholder' => gp_opt( 'news_placeholder' ),
	'button'      => gp_opt( 'news_button' ),
	'image'       => gp_img( 'journal/gazette-bg.jpg' ),
	'class'       => '',
) );
$id = wp_unique_id( 'gp-news-' );
?>
<section class="gp-newsletter<?php echo $a['class'] ? ' ' . esc_attr( $a['class'] ) : ''; ?>" style="--bg:url('<?php echo esc_url( $a['image'] ); ?>')">
	<div class="gp-container gp-newsletter__inner">
		<?php gp_component( 'section-head', array( 'kicker' => $a['kicker'], 'title' => $a['title'], 'align' => 'center', 'kicker_class' => 'gp-kicker--light', 'title_class' => 'gp-newsletter__title' ) ); ?>
		<p class="gp-newsletter__text"><?php echo esc_html( $a['text'] ); ?></p>
		<form class="gp-newsletter__form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
			<input type="hidden" name="action" value="gp_newsletter">
			<?php wp_nonce_field( 'gp_newsletter', 'gp_newsletter_nonce' ); ?>
			<label class="screen-reader-text" for="<?php echo esc_attr( $id ); ?>"><?php esc_html_e( 'Email address', 'gp-house' ); ?></label>
			<input class="gp-newsletter__input" id="<?php echo esc_attr( $id ); ?>" type="email" name="email" placeholder="<?php echo esc_attr( $a['placeholder'] ); ?>" autocomplete="email" required>
			<button class="gp-btn gp-btn--primary gp-newsletter__button" type="submit"><?php echo esc_html( $a['button'] ); ?></button>
		</form>
	</div>
</section>
