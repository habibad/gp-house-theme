<?php
/**
 * GP House theme bootstrap.
 *
 * Structure (see README.md):
 *   inc/         PHP modules (setup, assets, components API, content layer…)
 *   components/  reusable UI partials rendered with gp_component()
 *   template-parts/<page>/  page sections
 *   assets/css/  base → components → layout (global) + pages/<page>.css
 *
 * @package GP_House
 */

defined( 'ABSPATH' ) || exit;

define( 'GP_HOUSE_VERSION', '1.0.0' );
define( 'GP_HOUSE_DIR', get_template_directory() );
define( 'GP_HOUSE_URI', get_template_directory_uri() );

foreach ( array( 'helpers', 'setup', 'assets', 'components', 'defaults', 'content', 'rooms', 'customizer', 'menus' ) as $gp_module ) {
	require GP_HOUSE_DIR . '/inc/' . $gp_module . '.php';
}
unset( $gp_module );
