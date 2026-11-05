<?php
/**
 * Journal story (/journal/<slug>/) — Figma "Journal / Overview" (1148:8455),
 * mobile 1436:7675.
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

the_post();
$story   = gp_journal_story_from_post( get_post() );
$hero_id = (int) gp_opt( 'story_hero_image' );
$story_img = (int) get_post_meta( get_the_ID(), 'gp_story_image_id', true ) ?: (int) get_post_thumbnail_id();
$socials = array(
	'facebook'  => 'Facebook',
	'instagram' => 'Instagram',
	'x'         => 'X',
	'linkedin'  => 'LinkedIn',
);

get_header();
?>
<main id="gp-main" class="gp-story-page">
	<?php // Mobile app bar (Figma 1436:7675): replaces the site header on story pages. ?>
	<div class="gp-app-bar">
		<a class="gp-app-bar__btn" href="<?php echo esc_url( home_url( '/journal/' ) ); ?>" aria-label="<?php esc_attr_e( 'Back to the journal', 'gp-house' ); ?>"><?php gp_icon( 'arrow-left', 20 ); ?></a>
		<p class="gp-app-bar__title"><?php esc_html_e( 'Journal', 'gp-house' ); ?></p>
		<button class="gp-app-bar__btn" type="button" data-share data-share-title="<?php echo esc_attr( get_the_title() ); ?>" data-share-url="<?php echo esc_url( get_permalink() ); ?>" aria-label="<?php esc_attr_e( 'Share this story', 'gp-house' ); ?>"><?php gp_icon( 'share', 20 ); ?></button>
	</div>

	<?php
	gp_component( 'page-hero', array(
		'image'  => $hero_id ? wp_get_attachment_image_url( $hero_id, 'full' ) : gp_img( 'journal/hero-story.jpg' ),
		'kicker' => gp_opt( 'story_kicker' ),
		'title'  => gp_opt( 'story_title' ),
		'text'   => gp_opt( 'story_text' ),
		'class'  => 'gp-page-hero--story',
	) );
	?>

	<div class="gp-container">
		<div class="gp-story-page__bar">
			<a class="gp-back-link" href="<?php echo esc_url( home_url( '/journal/' ) ); ?>">
				<span class="gp-back-link__icon"><?php gp_icon( 'arrow-left', 24 ); ?></span>
				<?php esc_html_e( 'Back To The GP Journal', 'gp-house' ); ?>
			</a>
			<?php if ( $story['topic'] ) : ?>
				<a class="gp-topic-chip" href="<?php echo esc_url( gp_journal_topic_url( $story['topic_slug'] ) ); ?>"><?php gp_icon( 'share', 20 ); ?><?php echo esc_html( $story['topic'] ); ?></a>
			<?php endif; ?>
		</div>

		<div class="gp-story-page__layout">
			<aside class="gp-author-card" aria-label="<?php esc_attr_e( 'About the author', 'gp-house' ); ?>">
				<img class="gp-author-card__photo" src="<?php echo esc_url( $story['author_photo'] ); ?>" width="110" height="110" alt="">
				<h2 class="gp-author-card__name"><?php echo esc_html( $story['author_name'] ); ?></h2>
				<?php if ( $story['author_role'] ) : ?>
					<p class="gp-author-card__role"><?php echo esc_html( $story['author_role'] ); ?></p>
				<?php endif; ?>
				<p class="gp-author-card__follow"><?php esc_html_e( 'Follow Me', 'gp-house' ); ?></p>
				<ul class="gp-author-card__socials">
					<?php foreach ( $socials as $key => $label ) : ?>
						<li><a href="<?php echo esc_url( $story['author_socials'][ $key ] ?? '#' ); ?>" aria-label="<?php echo esc_attr( $label ); ?>" rel="noopener"><?php gp_icon( $key . '-navy', 28 ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</aside>

			<article <?php post_class( 'gp-story-article' ); ?>>
				<div class="gp-story-article__meta">
					<span class="gp-meta-chip"><?php gp_icon( 'time', 16 ); ?><?php echo esc_html( $story['date'] ); ?></span>
					<?php if ( $story['read_time'] ) : ?>
						<span class="gp-meta-chip"><?php gp_icon( 'time', 16 ); ?><?php echo esc_html( $story['read_time'] ); ?></span>
					<?php endif; ?>
				</div>
				<h1 class="gp-story-article__title"><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?>
					<p class="gp-story-article__lead"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
				<?php if ( $story_img ) : ?>
					<figure class="gp-story-article__media">
						<?php echo wp_get_attachment_image( $story_img, 'large', false, array( 'alt' => '' ) ); ?>
						<?php
						if ( $story['location'] ) {
							gp_chip( $story['location'] );
						}
						?>
					</figure>
				<?php endif; ?>
				<div class="gp-prose">
					<?php the_content(); ?>
				</div>
			</article>
		</div>
	</div>
</main>
<?php
get_footer();
