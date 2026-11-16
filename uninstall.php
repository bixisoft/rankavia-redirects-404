<?php
/**
 * Removes the redirect tables and options, unless Rankavia SEO is still installed and managing them.
 *
 * @package RankaviaRedirects
 */

declare( strict_types=1 );

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

if ( ! file_exists( WP_PLUGIN_DIR . '/rankavia/rankavia.php' ) ) {
	global $wpdb;
	foreach ( array( 'rankavia_redirects', 'rankavia_404_log' ) as $rankavia_table ) {
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.DirectDatabaseQuery.SchemaChange,WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Uninstall of this plugin's own tables.
		$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}{$rankavia_table}" );
	}
	foreach ( array( 'rankavia_redirects_db', 'rankavia_redirect_rules', 'rankavia_redirect_rules_backup', 'rankavia_redirects_migration', 'rankavia_404_dropped' ) as $rankavia_option ) {
		delete_option( $rankavia_option );
	}
	wp_clear_scheduled_hook( 'rankavia_redirects_prune' );
}
