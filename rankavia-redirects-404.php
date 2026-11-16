<?php
/**
 * Plugin Name:       Rankavia Redirects & 404
 * Plugin URI:        https://rankavia.com/products/rankavia-redirects-404/
 * Description:       Exact same-site redirects, automatic redirects when a page's address changes, and a private list of missing pages. Works alone or alongside Rankavia SEO.
 * Version:           0.1.0
 * Requires at least: 6.5
 * Requires PHP:      7.4
 * Author:            Bixisoft
 * Author URI:        https://bixisoft.com/
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       rankavia-redirects-404
 *
 * @package RankaviaRedirects
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

define( 'RANKAVIA_REDIRECTS_VERSION', '0.1.0' );
define( 'RANKAVIA_REDIRECTS_FILE', __FILE__ );
define( 'RANKAVIA_REDIRECTS_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Load the Composer autoloader: the plugin's own vendor/ in a built zip, or the monorepo root vendor/ in development.
 *
 * @return bool Whether an autoloader was found.
 */
function rankavia_redirects_load_autoloader(): bool {
	$candidates = array(
		defined( 'BIXISOFT_AUTOLOADER' ) ? (string) BIXISOFT_AUTOLOADER : '',
		RANKAVIA_REDIRECTS_PATH . 'vendor/autoload.php',
		dirname( RANKAVIA_REDIRECTS_PATH, 2 ) . '/vendor/autoload.php',
	);
	foreach ( $candidates as $autoloader ) {
		if ( '' !== $autoloader && file_exists( $autoloader ) ) {
			require_once $autoloader;
			return true;
		}
	}
	return false;
}

if ( ! rankavia_redirects_load_autoloader() || ! class_exists( \Bixisoft\RankaviaRedirects\Plugin::class ) ) {
	add_action(
		'admin_notices',
		static function (): void {
			echo '<div class="notice notice-error"><p>' . esc_html__( 'Rankavia Redirects & 404 could not load its files. Install the plugin from a release zip.', 'rankavia-redirects-404' ) . '</p></div>';
		}
	);
	return;
}

add_action(
	'plugins_loaded',
	static function (): void {
		( new \Bixisoft\RankaviaRedirects\Plugin() )->boot();
	}
);
