<?php
/**
 * About / Our Story (/about/) — Figma "About" (1148:7453), mobile 1439:10713.
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$about     = gp_about_content();
$hero_id   = (int) gp_opt( 'about_hero_image' );
$stays     = array_values( array_filter( gp_get_stays( array( 'map' => true ) ), function ( $s ) {
	return count( $s['pin'] ) === 2;
} ) );
$team_tabs = array( 'bangkok' => 'GP House Bangkok', 'phuket' => 'GP House Phuket', 'krabi' => 'GP House Krabi' );

get_header();
?>
<main id="gp-main" class="gp-about-page">
	<?php
	gp_component( 'page-hero', array(
		'image'  => $hero_id ? wp_get_attachment_image_url( $hero_id, 'full' ) : gp_img( 'about/hero.jpg' ),
		'image_position' => '36% 50%',
		'kicker' => gp_opt( 'about_kicker' ),
		'title'  => gp_opt( 'about_title' ),
		'text'   => gp_opt( 'about_text' ),
		'class'  => 'gp-page-hero--about' . ( $hero_id ? '' : ' gp-page-hero--shaded' ),
	) );
	?>

	<section class="gp-ethos">
		<div class="gp-container gp-ethos__inner">
			<div class="gp-ethos__media">
				<img class="gp-ethos__image" src="<?php echo esc_url( gp_img( 'about/ethos.jpg' ) ); ?>" alt="" loading="lazy">
				<div class="gp-badge" role="img" aria-label="<?php echo esc_attr( $about['badge']['number'] . ' — ' . $about['badge']['text'] ); ?>">
					<svg viewBox="0 0 244 244" aria-hidden="true">
						<?php
						$pts = array();
						for ( $i = 0; $i < 10; $i++ ) {
							$a     = deg2rad( -90 + $i * 36 );
							$pts[] = round( 122 + 114 * cos( $a ), 1 ) . ',' . round( 122 + 114 * sin( $a ), 1 );
						}
						?>
						<polygon class="gp-badge__shape" points="<?php echo esc_attr( implode( ' ', $pts ) ); ?>"/>
						<defs><path id="gp-badge-circle" d="M122 214a92 92 0 1 1 0-184a92 92 0 1 1 0 184"/></defs>
						<text class="gp-badge__ring"><textPath href="#gp-badge-circle" startOffset="9%"><?php echo esc_html( $about['badge']['text'] ); ?></textPath></text>
					</svg>
					<span class="gp-badge__number"><?php echo esc_html( $about['badge']['number'] ); ?></span>
				</div>
			</div>
			<div class="gp-ethos__text">
				<?php gp_component( 'section-head', array( 'kicker' => gp_opt( 'about_ethos_kicker' ), 'title' => gp_opt( 'about_ethos_title' ) ) ); ?>
				<div class="gp-ethos__copy">
					<?php foreach ( $about['ethos_paragraphs'] as $p ) : ?>
						<p><?php echo esc_html( $p ); ?></p>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>

	<section class="gp-diff">
		<div class="gp-container">
			<?php gp_component( 'section-head', array( 'kicker' => gp_opt( 'about_diff_kicker' ), 'title' => gp_opt( 'about_diff_title' ), 'align' => 'center' ) ); ?>
			<p class="gp-diff__intro"><?php echo esc_html( gp_opt( 'about_diff_text' ) ); ?></p>

			<div class="gp-diff__row">
				<figure class="gp-diff__photo">
					<img src="<?php echo esc_url( gp_img( 'about/way.jpg' ) ); ?>" alt="" loading="lazy">
					<figcaption><strong><?php esc_html_e( 'The GP House Way', 'gp-house' ); ?></strong><span><?php esc_html_e( 'Intimate, Authentic, Sustainable', 'gp-house' ); ?></span></figcaption>
				</figure>
				<ul class="gp-diff__list">
					<?php foreach ( $about['ours'] as $row ) : ?>
						<li><span class="gp-diff__icon"><?php gp_icon( 'check-green', 20 ); ?></span><div><h3><?php echo esc_html( $row[0] ); ?></h3><p><?php echo esc_html( $row[1] ); ?></p></div></li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div class="gp-diff__vs"><span>V/S</span></div>

			<div class="gp-diff__row gp-diff__row--rev">
				<ul class="gp-diff__list gp-diff__list--neg">
					<?php foreach ( $about['theirs'] as $row ) : ?>
						<li><span class="gp-diff__icon"><?php gp_icon( 'cross-red', 20 ); ?></span><div><h3><?php echo esc_html( $row[0] ); ?></h3><p><?php echo esc_html( $row[1] ); ?></p></div></li>
					<?php endforeach; ?>
				</ul>
				<figure class="gp-diff__photo">
					<img src="<?php echo esc_url( gp_img( 'about/chain.jpg' ) ); ?>" alt="" loading="lazy">
					<figcaption><strong><?php esc_html_e( 'Generic Chain Hotels', 'gp-house' ); ?></strong><span><?php esc_html_e( 'Mass-Market Commercial Hospitality', 'gp-house' ); ?></span></figcaption>
				</figure>
			</div>
		</div>
	</section>

	<section class="gp-footprint">
		<div class="gp-container">
			<div class="gp-footprint__head">
				<?php gp_component( 'section-head', array( 'kicker' => gp_opt( 'about_map_kicker' ), 'title' => gp_opt( 'about_map_title' ) ) ); ?>
				<p class="gp-footprint__text"><?php echo esc_html( gp_opt( 'about_map_text' ) ); ?></p>
			</div>
		</div>
		<?php
		if ( $stays ) {
			$active = 0;
			foreach ( $stays as $i => $s ) {
				if ( 'Bangkok' === $s['location'] ) {
					$active = $i; // Figma shows the Bangkok stay selected.
				}
			}
			gp_component( 'stay-map', array( 'stays' => $stays, 'active' => $active ) );
		}
		?>
	</section>

	<section class="gp-team" data-team>
		<div class="gp-container">
			<div class="gp-only-d"><?php gp_component( 'section-head', array( 'kicker' => gp_opt( 'about_team_kicker' ), 'title' => gp_opt( 'about_team_title' ), 'align' => 'center' ) ); ?></div>
			<p class="gp-team__intro gp-only-d"><?php echo esc_html( gp_opt( 'about_team_text' ) ); ?></p>
			<div class="gp-only-m"><?php gp_component( 'section-head', array( 'kicker' => gp_opt( 'about_team_kicker' ), 'title' => gp_opt( 'about_team_title_m' ), 'align' => 'center' ) ); ?></div>
			<p class="gp-team__intro gp-only-m"><?php echo esc_html( gp_opt( 'about_team_text_m' ) ); ?></p>
			<div class="gp-pills gp-pills--light" role="tablist">
				<?php $first = true; foreach ( $team_tabs as $key => $label ) : ?>
					<button class="gp-pills__tab<?php echo $first ? ' is-active' : ''; ?>" type="button" role="tab" aria-selected="<?php echo $first ? 'true' : 'false'; ?>" data-team-tab="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></button>
				<?php $first = false; endforeach; ?>
			</div>
			<?php $first = true; foreach ( $team_tabs as $key => $label ) : ?>
				<div class="gp-team__grid" data-team-panel="<?php echo esc_attr( $key ); ?>"<?php echo $first ? '' : ' hidden'; ?>>
					<?php foreach ( $about['team'][ $key ] ?? array() as $m ) : ?>
						<article class="gp-team-card">
							<img class="gp-team-card__photo" src="<?php echo esc_url( gp_img( $m['image'] ) ); ?>" alt="<?php echo esc_attr( $m['name'] ); ?>" loading="lazy">
							<div class="gp-team-card__body">
								<p class="gp-team-card__role"><?php echo esc_html( $m['role'] ); ?></p>
								<h3 class="gp-team-card__name"><?php echo esc_html( $m['name'] ); ?></h3>
								<p class="gp-team-card__text"><?php echo esc_html( $m['text'] ); ?></p>
								<div class="gp-team-card__contact">
									<a href="<?php echo esc_url( 'tel:' . gp_opt( 'about_phone' ) ); ?>" aria-label="<?php esc_attr_e( 'Call', 'gp-house' ); ?>"><?php gp_icon( 'phone-navy', 24 ); ?></a>
									<a href="<?php echo esc_url( 'mailto:' . gp_opt( 'about_email' ) ); ?>" aria-label="<?php esc_attr_e( 'Email', 'gp-house' ); ?>"><?php gp_icon( 'mail-navy', 24 ); ?></a>
									<a href="<?php echo esc_url( 'https://wa.me/' . preg_replace( '/\D/', '', gp_opt( 'about_phone' ) ) ); ?>" aria-label="WhatsApp" rel="noopener"><?php gp_icon( 'whatsapp-navy', 24 ); ?></a>
								</div>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php $first = false; endforeach; ?>
		</div>
	</section>
</main>
<?php
get_footer();
