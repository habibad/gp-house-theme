<?php
/**
 * Investor Relations (/investor-relations/) — Figma 1148:10446, mobile 1438:13017.
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$ir      = gp_investor_content();
$sim     = gp_investor_sim();
$hero_id = (int) gp_opt( 'ir_hero_image' );
$status  = isset( $_GET['inquiry'] ) ? sanitize_key( wp_unslash( $_GET['inquiry'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification

get_header();
?>
<main id="gp-main" class="gp-ir-page">
	<?php
	gp_component( 'page-hero', array(
		'image'  => $hero_id ? wp_get_attachment_image_url( $hero_id, 'full' ) : gp_img( 'investors/hero.jpg' ),
		'kicker' => gp_opt( 'ir_kicker' ),
		'title'  => gp_opt( 'ir_title' ),
		'text'   => gp_opt( 'ir_text' ),
		'class'  => 'gp-page-hero--ir' . ( $hero_id ? '' : ' gp-page-hero--shaded' ),
	) );
	?>

	<section class="gp-ir-stats">
		<div class="gp-container">
			<ul class="gp-ir-stats__list">
				<?php foreach ( $ir['stats'] as $s ) : ?>
					<li><strong><?php echo esc_html( $s[0] ); ?></strong><span><?php echo esc_html( $s[1] ); ?></span></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>

	<section class="gp-ir-adv">
		<div class="gp-container">
			<div class="gp-ir-adv__head">
				<?php gp_component( 'section-head', array( 'kicker' => gp_opt( 'ir_adv_kicker' ), 'title' => gp_opt( 'ir_adv_title' ), 'title_class' => 'gp-title--h3' ) ); ?>
				<p class="gp-ir-adv__text"><?php echo esc_html( gp_opt( 'ir_adv_text' ) ); ?></p>
			</div>
			<div class="gp-ir-adv__grid">
				<article class="gp-noi" style="--bg:url('<?php echo esc_url( gp_img( 'investors/noi-bg.jpg' ) ); ?>')">
					<h3><?php echo esc_html( $ir['noi']['title'] ); ?></h3>
					<p><?php echo esc_html( $ir['noi']['text'] ); ?></p>
				</article>
				<div class="gp-ir-adv__cards">
					<?php foreach ( $ir['cards'] as $c ) : ?>
						<article class="gp-adv-card">
							<img class="gp-adv-card__image" src="<?php echo esc_url( gp_img( $c['image'] ) ); ?>" alt="" loading="lazy">
							<div class="gp-adv-card__body">
								<span class="gp-adv-card__icon"><?php gp_icon( $c['icon'], 24 ); ?></span>
								<div>
									<h3><?php echo esc_html( $c['title'] ); ?></h3>
									<p><?php echo esc_html( $c['text'] ); ?></p>
								</div>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>

	<section class="gp-ir-models">
		<div class="gp-container gp-ir-models__inner">
			<div class="gp-ir-models__content">
				<?php gp_component( 'section-head', array( 'kicker' => gp_opt( 'ir_models_kicker' ), 'title' => gp_opt( 'ir_models_title' ), 'title_class' => 'gp-title--h3' ) ); ?>
				<ul class="gp-ir-models__list" data-hover-list>
					<?php foreach ( $ir['models'] as $i => $m ) : ?>
						<li class="gp-model<?php echo 0 === $i ? ' is-active' : ''; ?>" tabindex="0">
							<span class="gp-model__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
							<div><h3><?php echo esc_html( $m[0] ); ?></h3><p><?php echo esc_html( $m[1] ); ?></p></div>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<img class="gp-ir-models__image" src="<?php echo esc_url( gp_img( 'investors/partner.jpg' ) ); ?>" alt="" loading="lazy">
		</div>
	</section>

	<section class="gp-ir-sim">
		<div class="gp-container">
			<?php gp_component( 'section-head', array( 'kicker' => gp_opt( 'ir_sim_kicker' ), 'title' => gp_opt( 'ir_sim_title' ), 'align' => 'center', 'title_class' => 'gp-title--h3' ) ); ?>
			<p class="gp-ir-sim__text"><?php echo esc_html( gp_opt( 'ir_sim_text' ) ); ?></p>
			<div class="gp-sim" data-sim data-adr-lift="<?php echo esc_attr( $sim['adr_lift'] ); ?>" data-occ-lift="<?php echo esc_attr( $sim['occ_lift'] ); ?>" data-ota="<?php echo esc_attr( $sim['ota_share'] ); ?>">
				<div class="gp-sim__inputs">
					<?php
					$rows = array(
						'keys' => array( __( 'Room Count', 'gp-house' ), __( '%s Keys', 'gp-house' ) ),
						'adr'  => array( __( 'Current ADR', 'gp-house' ), __( '$%s / Night', 'gp-house' ) ),
						'occ'  => array( __( 'Current Occupancy', 'gp-house' ), '%s%%' ),
					);
					foreach ( $rows as $key => $row ) :
						$r   = $sim[ $key ];
						$pct = ( $r['value'] - $r['min'] ) / ( $r['max'] - $r['min'] ) * 100;
						?>
						<div class="gp-sim__row">
							<label for="gp-sim-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $row[0] ); ?></label>
							<output class="gp-sim__chip" data-sim-out="<?php echo esc_attr( $key ); ?>" data-format="<?php echo esc_attr( $row[1] ); ?>"><?php echo esc_html( sprintf( $row[1], $r['value'] ) ); ?></output>
							<input class="gp-range gp-range--sim" id="gp-sim-<?php echo esc_attr( $key ); ?>" type="range" min="<?php echo (int) $r['min']; ?>" max="<?php echo (int) $r['max']; ?>" step="<?php echo (int) $r['step']; ?>" value="<?php echo (int) $r['value']; ?>" style="--pct:<?php echo esc_attr( round( $pct, 2 ) ); ?>%" data-sim-in="<?php echo esc_attr( $key ); ?>">
						</div>
					<?php endforeach; ?>
				</div>
				<div class="gp-sim__result" aria-live="polite">
					<h3><?php esc_html_e( 'Annual Revenue Uplift', 'gp-house' ); ?></h3>
					<p class="gp-sim__total">+$<span data-sim-total>0</span></p>
					<p class="gp-sim__note"><?php esc_html_e( 'GPHM Projected Room Revenue:', 'gp-house' ); ?> $<span data-sim-proj>0</span> USD <?php esc_html_e( 'Vs Current:', 'gp-house' ); ?> $<span data-sim-cur>0</span> USD</p>
					<dl class="gp-sim__stats">
						<div><dt><?php esc_html_e( 'Optimized ADR', 'gp-house' ); ?></dt><dd>$<span data-sim-adr>0</span> (+<?php echo (int) round( $sim['adr_lift'] * 100 ); ?>%)</dd></div>
						<div><dt><?php esc_html_e( 'Occupancy', 'gp-house' ); ?></dt><dd><span data-sim-occ>0</span>% (+<?php echo (int) $sim['occ_lift']; ?> Pts)</dd></div>
						<div><dt><?php esc_html_e( 'OTA Savings', 'gp-house' ); ?></dt><dd>+$<span data-sim-ota>0</span>/Yr</dd></div>
					</dl>
				</div>
			</div>
		</div>
	</section>

	<section class="gp-ir-form" id="inquiry">
		<div class="gp-container gp-ir-form__inner">
			<img class="gp-ir-form__image" src="<?php echo esc_url( gp_img( 'investors/form.jpg' ) ); ?>" alt="" loading="lazy">
			<form class="gp-inquiry" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
				<input type="hidden" name="action" value="gp_inquiry">
				<?php wp_nonce_field( 'gp_inquiry', 'gp_inquiry_nonce' ); ?>
				<p class="gp-inquiry__trap" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></p>
				<?php if ( 'sent' === $status ) : ?>
					<p class="gp-inquiry__notice is-ok" role="status"><?php esc_html_e( 'Thank you — our partnerships team will be in touch shortly.', 'gp-house' ); ?></p>
				<?php elseif ( $status ) : ?>
					<p class="gp-inquiry__notice is-error" role="alert"><?php esc_html_e( 'Please enter your name and a valid email, then try again.', 'gp-house' ); ?></p>
				<?php endif; ?>
				<div class="gp-inquiry__grid">
					<label><span><?php esc_html_e( 'Your Full Name', 'gp-house' ); ?></span><input type="text" name="name" placeholder="e.g. Khun Somchai Prasert" autocomplete="name" required></label>
					<label><span><?php esc_html_e( 'Ownership Entity / Company', 'gp-house' ); ?></span><input type="text" name="company" placeholder="e.g. Prasert Hospitality Holdings" autocomplete="organization"></label>
					<label><span><?php esc_html_e( 'Email Address', 'gp-house' ); ?></span><input type="email" name="email" placeholder="e.g. owner@estate.com" autocomplete="email" required></label>
					<label><span><?php esc_html_e( 'Direct Phone / WhatsApp', 'gp-house' ); ?></span><input type="tel" name="phone" placeholder="+66812345678" autocomplete="tel"></label>
					<label><span><?php esc_html_e( 'Property Name', 'gp-house' ); ?></span><input type="text" name="property" placeholder="e.g. The Andaman Cliff Resort"></label>
					<label><span><?php esc_html_e( 'Location / Island', 'gp-house' ); ?></span><input type="text" name="location" placeholder="e.g. Phuket / Koh Samui"></label>
					<label><span><?php esc_html_e( 'Current Room Keys', 'gp-house' ); ?></span><input type="number" name="keys" min="1" placeholder="45"></label>
					<div class="gp-inquiry__field"><span><?php esc_html_e( 'Management Model', 'gp-house' ); ?></span>
						<?php gp_dropdown( array( 'name' => 'model', 'label' => '', 'options' => $ir['form_models'], 'value' => 'hma', 'show_value' => true, 'class' => 'gp-select--field' ) ); ?>
					</div>
					<label class="gp-inquiry__wide"><span><?php esc_html_e( 'Property Overview & Goals', 'gp-house' ); ?></span><textarea name="goals" rows="3" placeholder="Property Details, Operating Status, Or Rebranding Goals…"></textarea></label>
				</div>
				<button class="gp-btn gp-btn--primary gp-btn--tab gp-inquiry__submit" type="submit"><?php esc_html_e( 'Submit Inquiry', 'gp-house' ); ?></button>
			</form>
		</div>
	</section>
</main>
<?php
get_footer();
