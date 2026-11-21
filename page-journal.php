<?php
/**
 * The GP Journal (/journal/) — Figma "Journal" (1148:8205), empty topic
 * "Journal / Wellness" (1148:8356); mobile 1423:10191 / 1436:7321.
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$topic   = gp_journal_current_topic();
$topics  = gp_journal_topics();
$per     = gp_journal_per_page();
$total   = gp_journal_total( $topic );
$pages   = max( 1, (int) ceil( $total / $per ) );
$page    = gp_journal_current_page( $pages );
$stories = gp_get_journal( $per, array( 'topic' => $topic, 'listing' => true, 'offset' => ( $page - 1 ) * $per ) );
$hero_id = (int) gp_opt( 'jp_hero_image' );

get_header();
?>
<main id="gp-main" class="gp-journal-page">
	<?php
	gp_component( 'page-hero', array(
		'image'  => $hero_id ? wp_get_attachment_image_url( $hero_id, 'full' ) : gp_img( 'journal/hero.jpg' ),
		'kicker' => gp_opt( 'jp_kicker' ),
		'title'  => gp_opt( 'jp_title' ),
		'text'   => gp_opt( 'jp_text' ),
		'class'  => 'gp-page-hero--journal' . ( $hero_id ? '' : ' gp-page-hero--shaded' ),
	) );
	?>

	<section class="gp-journal-list" id="stories">
		<div class="gp-container">
			<div class="gp-journal-list__head">
				<?php gp_component( 'section-head', array( 'kicker' => gp_opt( 'jp_list_kicker' ), 'title' => gp_opt( 'jp_list_title' ), 'align' => 'center' ) ); ?>
				<p class="gp-journal-list__intro"><?php echo esc_html( gp_opt( 'jp_list_text' ) ); ?></p>
				<nav class="gp-pills gp-pills--light" aria-label="<?php esc_attr_e( 'Journal topics', 'gp-house' ); ?>">
					<a class="gp-pills__tab<?php echo '' === $topic ? ' is-active' : ''; ?>" href="<?php echo esc_url( gp_journal_topic_url() ); ?>"<?php echo '' === $topic ? ' aria-current="page"' : ''; ?>><?php esc_html_e( 'All', 'gp-house' ); ?></a>
					<?php foreach ( $topics as $slug => $name ) : ?>
						<a class="gp-pills__tab<?php echo $slug === $topic ? ' is-active' : ''; ?>" href="<?php echo esc_url( gp_journal_topic_url( $slug ) ); ?>"<?php echo $slug === $topic ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $name ); ?></a>
					<?php endforeach; ?>
				</nav>
			</div>

			<?php if ( $stories ) : ?>
				<div class="gp-journal-list__stories">
					<?php
					foreach ( $stories as $i => $story ) {
						gp_component( 'story-card', array( 'story' => $story, 'index' => $i, 'class' => 'gp-story--flat' ) );
					}
					?>
				</div>
				<?php if ( $pages > 1 ) : ?>
					<nav class="gp-pager" aria-label="<?php esc_attr_e( 'Journal pages', 'gp-house' ); ?>">
						<?php if ( $page > 1 ) : ?>
							<a class="gp-pager__link gp-pager__link--nav" href="<?php echo esc_url( gp_journal_topic_url( $topic, $page - 1 ) ); ?>" rel="prev"><?php esc_html_e( 'Previous', 'gp-house' ); ?></a>
						<?php else : ?>
							<span class="gp-pager__link gp-pager__link--nav is-disabled" aria-disabled="true"><?php esc_html_e( 'Previous', 'gp-house' ); ?></span>
						<?php endif; ?>
						<ol class="gp-pager__pages">
							<?php foreach ( gp_journal_page_list( $page, $pages ) as $n ) : ?>
								<?php if ( 0 === $n ) : ?>
									<li class="gp-pager__gap" aria-hidden="true">…</li>
								<?php elseif ( $n === $page ) : ?>
									<li><span class="gp-pager__link is-active" aria-current="page"><?php echo (int) $n; ?></span></li>
								<?php else : ?>
									<li><a class="gp-pager__link" href="<?php echo esc_url( gp_journal_topic_url( $topic, $n ) ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %d: page number */ __( 'Page %d', 'gp-house' ), $n ) ); ?>"><?php echo (int) $n; ?></a></li>
								<?php endif; ?>
							<?php endforeach; ?>
						</ol>
						<?php if ( $page < $pages ) : ?>
							<a class="gp-pager__link gp-pager__link--nav" href="<?php echo esc_url( gp_journal_topic_url( $topic, $page + 1 ) ); ?>" rel="next"><?php esc_html_e( 'Next', 'gp-house' ); ?></a>
						<?php else : ?>
							<span class="gp-pager__link gp-pager__link--nav is-disabled" aria-disabled="true"><?php esc_html_e( 'Next', 'gp-house' ); ?></span>
						<?php endif; ?>
					</nav>
				<?php endif; ?>
			<?php else : ?>
				<?php gp_component( 'empty-state', array( 'icon' => gp_img( 'journal/empty-notepad.png' ), 'title' => gp_opt( 'jp_empty_title' ), 'text' => gp_opt( 'jp_empty_text' ), 'class' => 'gp-journal-list__empty' ) ); ?>
			<?php endif; ?>
		</div>
	</section>

	<?php gp_component( 'newsletter' ); ?>
</main>
<?php
get_footer();
