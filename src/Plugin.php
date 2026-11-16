<?php
/**
 * Plugin entry for Rankavia Redirects & 404.
 *
 * @package RankaviaRedirects
 */

declare( strict_types=1 );

namespace Bixisoft\RankaviaRedirects;

use Bixisoft\RankaviaRedirects\Vendor\Core\Redirects\Admin_Page;
use Bixisoft\RankaviaRedirects\Vendor\Core\Redirects\Service;

/**
 * Claims the shared redirect runtime with priority 10. When Rankavia SEO is active it claims with 20
 * and owns the handler, tables and screen; this plugin then only points to that screen.
 */
final class Plugin {
	/** Package name used in the ownership claim. */
	public const PROVIDER = 'rankavia-redirects-404';

	/** Admin page slug under Tools. */
	public const SLUG = 'rankavia-redirects-404';

	/**
	 * Admin screen when this plugin owns redirects.
	 *
	 * @var Admin_Page|null
	 */
	private ?Admin_Page $page = null;

	/**
	 * Why this plugin is idle: '' (it runs), 'rankavia' (Rankavia SEO runs it) or 'outdated' (an older
	 * Rankavia SEO with its own redirect handler is active).
	 *
	 * @var string
	 */
	private string $deferred = '';

	/**
	 * Add hooks.
	 *
	 * @return void
	 */
	public function boot(): void {
		Service::claim( self::PROVIDER, 10 );
		add_action( 'init', array( $this, 'start' ), 5 );
		add_action( 'admin_menu', array( $this, 'menu' ) );
		add_action( 'admin_notices', array( $this, 'notice' ) );
	}

	/**
	 * Boot the runtime when this plugin owns it.
	 *
	 * @return void
	 */
	public function start(): void {
		$owner = Service::owner();
		if ( self::PROVIDER !== $owner ) {
			$this->deferred = 'rankavia';
			return;
		}
		// A Rankavia SEO that predates the shared runtime does not claim, yet runs its own redirects.
		if ( defined( 'RANKAVIA_VERSION' ) ) {
			$this->deferred = 'outdated';
			return;
		}
		$service = new Service();
		$service->boot();
		$this->page = new Admin_Page( $service, self::SLUG, self::labels() );
		$this->page->hooks();
	}

	/**
	 * Tools menu entry.
	 *
	 * @return void
	 */
	public function menu(): void {
		if ( null !== $this->page ) {
			add_management_page( __( 'Redirects & 404', 'rankavia-redirects-404' ), __( 'Redirects & 404', 'rankavia-redirects-404' ), 'manage_options', self::SLUG, array( $this->page, 'render' ) );
		}
	}

	/**
	 * Explain on the Plugins screen why this plugin is idle.
	 *
	 * @return void
	 */
	public function notice(): void {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		if ( '' === $this->deferred || null === $screen || 'plugins' !== $screen->id || ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( 'outdated' === $this->deferred ) {
			echo '<div class="notice notice-warning"><p>' . esc_html__( 'Rankavia Redirects & 404 is paused because an older Rankavia SEO is handling redirects. Update Rankavia SEO; your redirects then move to one shared list automatically.', 'rankavia-redirects-404' ) . '</p></div>';
			return;
		}
		echo '<div class="notice notice-info"><p>' . esc_html__( 'Rankavia SEO is active, so redirects and the 404 list are managed there. Both plugins use the same list.', 'rankavia-redirects-404' ) . ' <a href="' . esc_url( admin_url( 'admin.php?page=rankavia-redirects' ) ) . '">' . esc_html__( 'Open Redirects', 'rankavia-redirects-404' ) . '</a></p></div>';
	}

	/**
	 * Translated labels for the shared screen.
	 *
	 * @return array<string,string>
	 */
	public static function labels(): array {
		return array(
			'forbidden'           => __( 'You cannot manage redirects.', 'rankavia-redirects-404' ),
			'title'               => __( 'Redirects & 404', 'rankavia-redirects-404' ),
			'tab_redirects'       => __( 'Redirects', 'rankavia-redirects-404' ),
			'tab_not_found'       => __( 'Not found (404)', 'rankavia-redirects-404' ),
			'intro'               => __( 'Send an old address on this site to a new one. Exact paths only; destinations stay on this site. Logged-in visitors and requests with a query string are not redirected.', 'rankavia-redirects-404' ),
			'add_heading'         => __( 'Add a redirect', 'rankavia-redirects-404' ),
			'rules_heading'       => __( 'Redirects', 'rankavia-redirects-404' ),
			'search'              => __( 'Search redirects', 'rankavia-redirects-404' ),
			'col_source'          => __( 'Old address', 'rankavia-redirects-404' ),
			'col_target'          => __( 'New address', 'rankavia-redirects-404' ),
			'col_status'          => __( 'Type', 'rankavia-redirects-404' ),
			'col_origin'          => __( 'Added by', 'rankavia-redirects-404' ),
			'col_used'            => __( 'Last used', 'rankavia-redirects-404' ),
			'col_action'          => __( 'Action', 'rankavia-redirects-404' ),
			'origin_manual'       => __( 'You', 'rankavia-redirects-404' ),
			'origin_slug'         => __( 'Address change', 'rankavia-redirects-404' ),
			'origin_not_found'    => __( 'From the 404 list', 'rankavia-redirects-404' ),
			'origin_migrated'     => __( 'Earlier version', 'rankavia-redirects-404' ),
			'never'               => __( 'Not yet', 'rankavia-redirects-404' ),
			'delete'              => __( 'Delete', 'rankavia-redirects-404' ),
			'empty'               => __( 'No redirects yet. Add the first one above.', 'rankavia-redirects-404' ),
			'no_match'            => __( 'No redirects match your search.', 'rankavia-redirects-404' ),
			'export'              => __( 'Download all as CSV', 'rankavia-redirects-404' ),
			'log_intro'           => __( 'Addresses on this site that visitors asked for and did not find, most requested first. Only the path and a count are kept: no IP address, query string or browser details. Paths not seen for 30 days are removed.', 'rankavia-redirects-404' ),
			/* translators: %s: number of requests. */
			'log_dropped'         => __( 'The list is full, so %s requests for new missing addresses were counted but not listed. Fix or dismiss some rows to make room.', 'rankavia-redirects-404' ),
			'col_path'            => __( 'Missing address', 'rankavia-redirects-404' ),
			'col_hits'            => __( 'Requests', 'rankavia-redirects-404' ),
			'col_last_seen'       => __( 'Last requested', 'rankavia-redirects-404' ),
			'col_fix'             => __( 'Fix', 'rankavia-redirects-404' ),
			'forget'              => __( 'Dismiss', 'rankavia-redirects-404' ),
			'log_empty'           => __( 'No missing pages recorded yet.', 'rankavia-redirects-404' ),
			'clear'               => __( 'Clear the whole list', 'rankavia-redirects-404' ),
			'source'              => __( 'Old address (path)', 'rankavia-redirects-404' ),
			'target'              => __( 'New address', 'rankavia-redirects-404' ),
			'target_help'         => __( 'A path such as /new-page/ or a full address on this site. Leave empty for "Gone".', 'rankavia-redirects-404' ),
			'status'              => __( 'Type', 'rankavia-redirects-404' ),
			'status_301'          => __( '301 Moved permanently', 'rankavia-redirects-404' ),
			'status_302'          => __( '302 Moved temporarily', 'rankavia-redirects-404' ),
			'status_410'          => __( '410 Gone', 'rankavia-redirects-404' ),
			'add'                 => __( 'Add redirect', 'rankavia-redirects-404' ),
			'redirect'            => __( 'Redirect', 'rankavia-redirects-404' ),
			'migration_failed'    => __( 'Redirects saved by an earlier version could not be moved to the new list because they look damaged. They were left as they were, and none of them run. Please contact support.', 'rankavia-redirects-404' ),
			'notice_added'        => __( 'Redirect added.', 'rankavia-redirects-404' ),
			'notice_deleted'      => __( 'Redirect deleted.', 'rankavia-redirects-404' ),
			'notice_invalid'      => __( 'That redirect cannot be added: check the paths, and that it does not point back to itself or lead in a circle.', 'rankavia-redirects-404' ),
			'notice_exists'       => __( 'That old address already has a redirect.', 'rankavia-redirects-404' ),
			'notice_stale'        => __( 'The list changed in the meantime. Reload and try again.', 'rankavia-redirects-404' ),
			'notice_undo_expired' => __( 'There is nothing to undo any more.', 'rankavia-redirects-404' ),
			'notice_undone'       => __( 'Change undone.', 'rankavia-redirects-404' ),
			'notice_forgotten'    => __( 'Address dismissed from the list.', 'rankavia-redirects-404' ),
			'notice_cleared'      => __( 'The list of missing pages was cleared.', 'rankavia-redirects-404' ),
			'undo'                => __( 'Undo', 'rankavia-redirects-404' ),
			'previous'            => __( 'Previous page', 'rankavia-redirects-404' ),
			'next'                => __( 'Next page', 'rankavia-redirects-404' ),
			/* translators: 1: current page number, 2: number of pages. */
			'page_of'             => __( 'Page %1$s of %2$s', 'rankavia-redirects-404' ),
		);
	}
}
