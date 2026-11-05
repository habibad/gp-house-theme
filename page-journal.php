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
$stories = gp_get_journal( -1, array( 'topic' => $topic, 'listing' => true ) );
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
			<?php else : ?>
				<?php gp_component( 'empty-state', array( 'icon' => gp_img( 'journal/empty-notepad.png' ), 'title' => gp_opt( 'jp_empty_title' ), 'text' => gp_opt( 'jp_empty_text' ), 'class' => 'gp-journal-list__empty' ) ); ?>
			<?php endif; ?>
		</div>
	</section>

	<?php gp_component( 'newsletter' ); ?>
</main>
<?php
get_footer();
