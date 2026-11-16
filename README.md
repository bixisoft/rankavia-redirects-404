# Rankavia Redirects & 404

Free, GPL-2.0-or-later WordPress plugin by Bixisoft.

**Development preview — plugin version 0.1.0; release v0.1.0-preview.1.** Test on a disposable copy before using it on a live site. This repository is public source distribution, not a WordPress.org listing or a stable compatibility certification.

- **Redirects:** exact same-site 301/302 redirects and 410 "gone", with no limit on how many.
- **Automatic redirects when an address changes:** moving a published post or page keeps its old address working; moving it again never creates a chain, and moving it back removes the redirect.
- **Missing pages (404):** a private list of addresses visitors did not find, most requested first, with a one-click redirect form. Only the path, a count and dates are stored; no IP address, query string or browser details. Kept for 30 days, at most 10,000 addresses.
- **Undo** for your last change and **CSV export**.

Works alone, or together with Rankavia SEO, which includes the same feature: when both are active, Rankavia SEO handles redirects and both use one list.

## Download and install

Download **rankavia-redirects-404.zip** from [the preview release](https://github.com/bixisoft/rankavia-redirects-404/releases/tag/v0.1.0-preview.1). Upload it through WordPress Plugins → Add New → Upload Plugin, activate it, then open Tools → Redirects & 404. Requires WordPress 6.5+ and PHP 7.4+. The GitHub-generated source archives are not the install ZIP. Read [VALIDATION.md](VALIDATION.md) for what was tested.

## Source and building

All plugin code is editable PHP, including the shared core bundled in `vendor/bixisoft/core-php` under a plugin-specific namespace. Do **not** run `composer install` in this extracted distribution. Rebuild the install ZIP from the verified runtime manifest with Python 3:

```sh
python tools/package.py
```

## Privacy and support

The plugin makes no HTTP requests and includes no analytics or tracking. See [readme.txt](readme.txt) for details. Report reproducible issues in this repository; never include credentials or private customer data.

## License

GPL-2.0-or-later; full GPL v2 text is in [LICENSE](LICENSE). Composer's bundled loader keeps its MIT licence in `vendor/composer/LICENSE`.
