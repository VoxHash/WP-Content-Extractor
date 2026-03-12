# FAQ

## General

### What is WP Content Extractor?

A WordPress plugin that automatically extracts content from external URLs and publishes it as WordPress posts via scheduled cron jobs.

### What WordPress/PHP versions are required?

- WordPress 5.0 or higher
- PHP 7.4 or higher

### Is this plugin free?

Yes, this plugin is open source and free to use under the MIT License.

## Installation

### How do I install the plugin?

See [Installation Guide](installation.md) for detailed steps. You can install manually via FTP or through WordPress admin.

### Can I install via WordPress plugin directory?

Not yet. Currently, you need to install manually from GitHub.

## Usage

### How often does the plugin run?

By default, the plugin runs every 30 minutes via WordPress cron.

### Can I change the cron interval?

Yes, edit `content-extractor.php` and modify the `ce_add_custom_cron_intervals()` function.

### Can I trigger extraction manually?

Yes, call `ce_extract_content()` function or use WP-CLI.

### What content formats are supported?

The plugin can work with any format, but you need to implement the parsing logic in `ce_parse_and_publish_content()`. Examples are provided for JSON and HTML.

## Configuration

### Where are settings stored?

Settings are stored in WordPress options table under `ce_settings`.

### Can I configure multiple source URLs?

Not in the current version. This is planned for a future release.

### How do I set the batch size?

Edit `$posts_per_page` in `ce_extract_content()` function.

## Troubleshooting

### Why aren't posts being created?

- Check source URL is correct
- Verify parsing function is implemented
- Check WordPress debug log
- Test with sample data

### Why aren't images downloading?

- Verify image URLs are accessible
- Check file permissions
- Ensure external URLs are allowed
- Check for SSL issues

### How do I enable debug logging?

Set `WP_DEBUG` and `WP_DEBUG_LOG` to `true` in `wp-config.php`.

## Development

### Can I extend the plugin?

Yes! The plugin uses WordPress hooks and filters. See [API Reference](api.md) for available hooks.

### How do I contribute?

See [CONTRIBUTING.md](../CONTRIBUTING.md) for guidelines.

### Where can I report bugs?

Open an issue on GitHub using the bug report template.
