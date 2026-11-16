# Preview 1 validation

Release tag: `v0.1.0-preview.1`. Plugin header: `0.1.0`. Development preview, not WordPress.org listed or stable certification.

Install ZIP SHA256: `49c1ad5212288daa7f78771e1d6cdec3c7f5eb828168d7f1c61200becbf05707`. `python tools/package.py` rebuilds it byte-for-byte from `release-manifest.json`. Source: bixisoft-plugins `44398de5c53554087b3933eb9d630c6ac1baf67f`.

## Checks run

- Built with the repository's own steps: `composer install --no-dev --optimize-autoloader`, `.distignore`, `tools/prefix-core.php` (its residual-namespace, classmap and `php -l` checks passed).
- PHPUnit in the source repository: core 100, Rankavia 194, Rankavia Speed 36, Redirects & 404 4 tests, all passing. PHPCS: 0 errors on the changed code. PHPStan level 6: no errors.
- The exact install ZIP contents, with no development autoloader, on WordPress 7.1.4 / PHP 7.4 (Playground):
  - Rankavia SEO activated over redirects saved by preview 4: both migrated (301 and 410 served), old option kept as a backup;
  - Redirects & 404 activated next to it: it defers to Rankavia SEO, each redirect is sent once;
  - Rankavia SEO deactivated: Redirects & 404 takes over the same list and keeps serving it;
  - no PHP errors or warnings on the plugin screens.
- Source-tree tests on the same setup also covered automatic redirects for a page moved three times, the private 404 list, redirect-from-404, undo and uninstall with the other plugin installed.

## Not run for this preview

- Plugin Check; MySQL/MariaDB (tests ran on Playground's SQLite); the uninstall path that removes the tables when no Rankavia plugin is left; multisite.
