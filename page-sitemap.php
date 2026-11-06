<?php
/**
 * Interactive sitemap (/sitemap/) — a tidy map of the site.
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

$groups = apply_filters( 'gp_house_sitemap', array(
	__( 'Stay', 'gp-house' )      => array_merge(
		array( array( 'title' => __( 'Stays & Villas', 'gp-house' ), 'url' => home_url( '/stays/' ) ) ),
		array_map( fn( $d ) => array( 'title' => $d['name'], 'url' => $d['url'] ), gp_get_destinations() ),
		array( array( 'title' => __( 'Special Offers & Packages', 'gp-house' ), 'url' => home_url( '/offers/' ) ), array( 'title' => __( 'Late Night Self Check-In', 'gp-house' ), 'url' => home_url( '/self-check-in/' ) ) )
	),
	__( 'Discover', 'gp-house' )  => array(
		array( 'title' => __( 'Destinations', 'gp-house' ), 'url' => home_url( '/destinations/' ) ),
		array( 'title' => __( 'Curated Experiences', 'gp-house' ), 'url' => home_url( '/experiences/' ) ),
		array( 'title' => __( 'Journal / Stories', 'gp-house' ), 'url' => home_url( '/journal/' ) ),
		array( 'title' => __( 'Our Story & Heritage', 'gp-house' ), 'url' => home_url( '/about/' ) ),
		array( 'title' => __( 'Sustainability & Craft', 'gp-house' ), 'url' => home_url( '/sustainability/' ) ),
	),
	__( 'Members', 'gp-house' )   => array(
		array( 'title' => __( 'GP House Club', 'gp-house' ), 'url' => home_url( '/club/' ) ),
		array( 'title' => __( 'Sign In', 'gp-house' ), 'url' => gp_auth_url( 'signin' ) ),
		array( 'title' => __( 'Join GP House Club', 'gp-house' ), 'url' => gp_auth_url( 'join' ) ),
		array( 'title' => __( 'My Account', 'gp-house' ), 'url' => home_url( '/profile/' ) ),
	),
	__( 'Partners & Help', 'gp-house' ) => array(
		array( 'title' => __( 'Investor Relations', 'gp-house' ), 'url' => home_url( '/investor-relations/' ) ),
		array( 'title' => __( 'Contact & Concierge', 'gp-house' ), 'url' => home_url( '/contact/' ) ),
		array( 'title' => __( 'Booking Policies', 'gp-house' ), 'url' => home_url( '/booking-policies/' ) ),
		array( 'title' => __( 'Terms of Service', 'gp-house' ), 'url' => home_url( '/terms/' ) ),
	),
) );

get_header();
?>
<main id="gp-main" class="gp-doc gp-sitemap">
	<div class="gp-container gp-doc__inner">
		<p class="gp-kicker"><?php esc_html_e( 'GP House', 'gp-house' ); ?></p>
		<h1 class="gp-doc__title"><?php esc_html_e( 'Interactive Sitemap', 'gp-house' ); ?></h1>
		<div class="gp-sitemap__grid">
			<?php foreach ( $groups as $heading => $links ) : ?>
				<section>
					<h2><?php echo esc_html( $heading ); ?></h2>
					<ul>
						<?php foreach ( $links as $l ) : ?>
							<li><a href="<?php echo esc_url( $l['url'] ); ?>"><?php echo esc_html( $l['title'] ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</section>
			<?php endforeach; ?>
		</div>
	</div>
</main>
<?php
get_footer();
