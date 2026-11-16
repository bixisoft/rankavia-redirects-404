=== Rankavia Redirects & 404 ===
Contributors: bixisoft
Tags: redirects, 404, 301, broken links, slug
Requires at least: 6.5
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Exact redirects, automatic redirects when a page moves, and a private list of missing pages. Free, with no limit on the number of redirects.

== Description ==

This is an early development preview.

* **Redirects:** send an old address on this site to a new one with a 301 (permanent) or 302 (temporary) redirect, or mark it 410 (gone). Exact paths only; destinations must stay on this site. There is no limit on the number of redirects; they are stored in their own indexed table.
* **Automatic redirects when an address changes:** when you change the address of a published post or page, the old address redirects to the new one. Moving it again updates earlier automatic redirects so visitors never go through a chain, and moving it back removes the redirect. Your own redirects always win. Drafts, private content and pages below a moved parent page are not covered.
* **Missing pages (404):** a list of addresses visitors asked for and did not find, most requested first, with a one-click form to redirect each one. Only the path, a count and first/last seen dates are kept: no IP address, query string, cookies or browser details. Logged-in visitors and common scanner probes are not counted. Paths not seen for 30 days are removed, and the list keeps at most 10,000 addresses.
* **Undo and export:** undo your last change for 15 minutes, and download all redirects as a CSV file.

Redirects are not applied to logged-in visitors, to requests with a query string, or to WordPress admin, login, REST API, cart, checkout or account paths.

Rankavia SEO includes the same feature. When both plugins are active, Rankavia SEO handles redirects and both use the same list, so nothing runs twice.

Not in this preview: pattern (regex) redirects, bulk import, redirects to other websites and query-string matching.

== External services ==

This plugin makes no HTTP requests and sends no data to Rankavia, Bixisoft or any third party. It includes no analytics or tracking.

== Installation ==

1. Upload the plugin zip through Plugins, Add New, Upload Plugin, then activate it.
2. Open Tools, Redirects & 404.

== Frequently Asked Questions ==

= I used redirects in an earlier Rankavia SEO preview. Are they kept? =

Yes. They are copied into the new list the first time the plugin runs and checked one by one. The old copy is kept as a backup.

= What happens when I uninstall it? =

Its tables and settings are removed, unless Rankavia SEO is still installed, because Rankavia SEO uses the same redirects.

== Changelog ==

= 0.1.0 =
* First development preview: unlimited exact redirects, automatic redirects for changed addresses, a private 404 list, undo and CSV export.
